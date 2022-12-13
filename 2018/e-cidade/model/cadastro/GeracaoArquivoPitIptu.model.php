<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


/**
 * Classe responsável pela geração do arquivo PIT do IPTU.
 * @author  Renan Melo <renan@dbseller.com.br>
 * @package Cadastro
 */
class GeracaoArquivoPitIptu {

  /**
   * Ano que deve ser usado para a geração do arquivo.
   * @var Integer
   */
  private $iAno;

  /**
   * Ano que selecionado pelo usuário no qual vai ser mostrado no arquivo
   * @var integer
   */
  private $iAnoSelecionado;

  /**
   * Periodo que deve ser usado para a geração do arquivo
   * @var Insteger
   */
  private $iPeriodo;

  /**
   * Objeto do tipo DomDocument para geração do XML
   * @var DomDocument
   */
  private $DomDocument;


  /**
   * Objeto do tipo DomDocument para representação dos Imoveis utilizados no XML
   * @var DomDocument
   */
  private $oImovel;

  /**
   * Array com as inconsistencias
   * @var array
   */
  private $aErros = array();

  /**
   * Codigo do Municipio.
   * @var integer
   */
  private $iMunicipio;

  /**
   * Caminho do arquivo gerado;
   * @var String
   */
  private $sCaminhoArquivo;

  /**
   * Instituição que esta sendo utilizada.
   * @var Integer
   */
  private $iInstit;

  /**
   * Caminhos para o arquivo JSON contendo as mensagens utilizadas na função _M
   */
  const MENSAGENS   = 'tributario.cadastro.geracaoarquivopitiptu.';

  /**
   * Função construtora para geração de arquivo IPTU
   * @param integer $iAno     Ano que deve ser usado para a geração de Arquivo
   * @param integer $iPeriodo Periodo que deve ser usado para a geração do Arquivo
   */
  function __construct($iAno, $iPeriodo){

    $this->setAno($iAno);
    $this->iPeriodo = $iPeriodo;
    $this->iInstit  = db_getsession('DB_instit');

    $this->oDomDocument = new DOMDocument('1.0', 'iso-8859-1');
    $this->oDomDocument->xmlStandalone = true;
    $this->oDomDocument->formatOutput  = true;
  }

  /**
   * Seta o ano na classe,
   * caso não exista calculo de iptu no ano informado, a classe pega o ano anterior
   * @param integer $iAno Ano do exercício
   * @return void
   */
  public function setAno($iAno) {

    $this->iAnoSelecionado = $iAno;

    $sSqlVerificacao = "select exists(select 1 from iptucalc where j23_anousu = $iAno);";

    $rsVerificacao = db_query($sSqlVerificacao);

    if (!$rsVerificacao) {
      throw new DBException("Erro na busca dos dados.");
    }

    if (pg_num_rows($rsVerificacao) == 0) {
      $iAno--;
    } else {

      $oVerificacao = db_utils::fieldsMemory($rsVerificacao, 0);

      if ($oVerificacao->exists == 'f') {
        $iAno--;
      }
    }

    $this->iAno = $iAno;
  }

  /**
   * Função utilizada para realizar o registro dos
   * erros no array $aErros.
   * @param  integer $sMensagem mensagem de erro
   */
  private function registraErros( $sMensagem ) {
    $this->aErros[] = $sMensagem;
  }

  /**
   * Retorna as inconsistências dos registros que não foram gerados no arquivo.
   *
   * @return array
   */
  public function getErros() {
    return $this->aErros;
  }

  /**
   * Função responsável por realizar a geração do Arquivo
   * @return String url onde foi salvo o XML.
   */
  public function geraArquivo(){

    /**
     * Escreve o nó de informação no arquivo.
     * <informacao>
     */
    $oInformacao = $this->escreveInformacao();

    /**
     * Escre o nó do municipio.
     * <municipio>
     */
    $oInformacao->appendChild($this->escreveMunicipio());

    /**
     * Percorre a lista de imoveis
     * <imovel>
     */
    $rsImoveis     = $this->getImoveis();
    $iTotalImoveis = pg_num_rows($rsImoveis);

    for($iImoveis = 0; $iImoveis < $iTotalImoveis; $iImoveis++) {

      $oDadosMatricula = db_utils::fieldsMemory($rsImoveis, $iImoveis);

      $this->escreveImovel($oDadosMatricula);

      $lProprietarios  = $this->escreveProprietarios ($oDadosMatricula->nro_registro_iptu);
      $lLogradouro     = $this->escreveLogradouro    ($oDadosMatricula->lote, $oDadosMatricula->nro_registro_iptu);
      $lTerreno        = $this->escreveTerreno       ($oDadosMatricula->lote, $oDadosMatricula->nro_registro_iptu);
      $lBenfeitoria    = $this->escreveBenfeitorias  ($oDadosMatricula->nro_registro_iptu);
      $lAdicionaImovel = $lProprietarios && $lLogradouro && $lTerreno && $lBenfeitoria;

      /**
       * Se foi fornecido todos os parmetros obrigatorios, será escrito o nó do Imovel
       */
      if ( $lAdicionaImovel ) {
        $oInformacao->appendChild($this->oImovel);
      }
    }

    $this->oDomDocument->appendChild($oInformacao);

    $this->sCaminhoArquivo = "tmp/IPTU_{$this->iMunicipio}_{$this->iPeriodo}_{$this->iAnoSelecionado}.xml";

    /**
     * Caso exista o arquivo deve ser removido
     */
    if( file_exists( $this->sCaminhoArquivo ) ){
      unlink( $this->sCaminhoArquivo );
    }

    if ( $this->oDomDocument->save($this->sCaminhoArquivo) ) {
      return $this->sCaminhoArquivo;
    }

    return false;
  }

  /**
   * Escreve o nó de informação
   * <informacao>
   * @return DomDocument $oInformacao
   */
  private function escreveInformacao(){

    $oInformacao = $this->oDomDocument->createElement('INFORMACAO');
    $oInformacao->setAttribute('tipo'  , 'IPTU');
    $oInformacao->setAttribute('versao', '1.0');
    return $oInformacao;
  }

  /**
   * Escreve o nó de municipio
   * <municipio>
   * @return DomDocument $oMunicipio
   */
  private function escreveMunicipio(){

    $oDadosMunicipio = $this->getMunicipio();

    $this->iMunicipio = $oDadosMunicipio->codigo;
    $oMunicipio = $this->oDomDocument->createElement('MUNICIPIO', '');
    $oMunicipio->setAttribute('codigo'  , $this->iMunicipio);
    $oMunicipio->setAttribute('nome'    , $oDadosMunicipio->nome);
    $oMunicipio->setAttribute('ano'     , $this->iAnoSelecionado);
    $oMunicipio->setAttribute('semestre', $this->iPeriodo);

    return $oMunicipio;
  }

  /**
   * Escreve o nó com as informações do imovel
   * <imovel>
   * @param  Object $oMatricula dados da matricula do imóvel
   */
  private function escreveImovel($oMatricula){

    $this->oImovel = $this->oDomDocument->createElement('imovel');
    $this->oImovel->setAttribute('matricula'        , $oMatricula->matricula);
    $this->oImovel->setAttribute('zona'             , $oMatricula->zona);
    $this->oImovel->setAttribute('nro_registro_iptu', $oMatricula->nro_registro_iptu);
  }

  /**
   * Escreve o nó com as informações do Proprietário de cada imóvel.
   * <proprietarios>
   * @param  integer $iGuiaIPTU matricula do imóvel que esta buscando as informações
   * @return boolean
   *         -true: Todos os paramentros obrigatorios válidos.
   *         -false: Um ou mais parâmetros obrigatórios inválidos.
   */
  private function escreveProprietarios($iGuiaIPTU) {

    $lProprietariosValidos = false;
    $oProprietarios        = $this->oDomDocument->createElement('PROPRIETARIOS');
    $rsDadosProprietarios  = $this->getProprietarios($iGuiaIPTU);
    $iTotalProprietarios   = pg_num_rows($rsDadosProprietarios);

    for($iProprietario = 0; $iProprietario < $iTotalProprietarios; $iProprietario++) {

      $oDadosProprietario  = db_utils::fieldsMemory($rsDadosProprietarios, $iProprietario);
      $lProprietarioValido = true;
      $oProprietario       = $this->oDomDocument->createElement('proprietario');

      $oProprietario->setAttribute('nome'    , utf8_encode($oDadosProprietario->nome));
      $oProprietario->setAttribute('cpf_cnpj', $oDadosProprietario->cpf_cnpj);

      if (empty($oDadosProprietario->nome)) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - PROPRIETÁRIO - Nome é obrigatório.");
        $lProprietarioValido = false;
      }

      if (empty($oDadosProprietario->cpf_cnpj)) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - PROPRIETÁRIO - CPF/CNPJ é obrigatório.");
        $lProprietarioValido = false;
      }

      if ($lProprietarioValido) {

        $oProprietarios->appendChild($oProprietario);
        $lProprietariosValidos = true;
      }
    }

    /**
     * Verifica se existe pelo menos um Proprietario válido para o imovel
     */
    if ( $lProprietariosValidos ) {

      $this->oImovel->appendChild($oProprietarios);
      return true;
    }
    return false;
  }

  /**
   * Escreve o nó com as informações do logradouro do imóvel.
   * <logradouro>
   * @param  integer $iLote código do lote onde será buscado o logradouro
   * @return boolean
   *         -true: Todos os paramentros obrigatorios válidos.
   *         -false: Um ou mais parâmetros obrigatórios inválidos.
   */
  private function escreveLogradouro($iLote, $iGuiaIPTU) {

    $lLogradouroValido = true;
    $oDadosLogradouro  = $this->getLogradouro($iLote);
    $oLogradouro       = $this->oDomDocument->createElement('logradouro');

    $oLogradouro->setAttribute('tipo'  , $oDadosLogradouro->tipo);
    $oLogradouro->setAttribute('nome'  , utf8_encode($oDadosLogradouro->nome));
    $oLogradouro->setAttribute('nro'   , utf8_encode($oDadosLogradouro->nro));
    $oLogradouro->setAttribute('compl' , utf8_encode($oDadosLogradouro->complemento));
    $oLogradouro->setAttribute('lote'  , $oDadosLogradouro->lote);
    $oLogradouro->setAttribute('bairro', utf8_encode($oDadosLogradouro->bairro));
    $oLogradouro->setAttribute('vila'  , utf8_encode($oDadosLogradouro->vila));
    $oLogradouro->setAttribute('quadra', $oDadosLogradouro->quadra);
    $oLogradouro->setAttribute('setor' , $oDadosLogradouro->setor);

    if ( empty($oDadosLogradouro->tipo) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Tipo é obrigatório.");
      $lLogradouroValido  = false;
    }

    if ( empty($oDadosLogradouro->nome) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Nome é obrigatório.");
      $lLogradouroValido = false;
    }

    if ( empty($oDadosLogradouro->lote) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Lote é obrigatório.");
      $lLogradouroValido = false;
    }

    if ( empty($oDadosLogradouro->bairro) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Bairro é obrigatório.");
      $lLogradouroValido = false;
    }

    if ( empty($oDadosLogradouro->quadra) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Quadra é obrigatório.");
      $lLogradouroValido = false;
    }

    if ( empty($oDadosLogradouro->setor) ) {

      $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - LOGRADOURO - Setor é obrigatório.");
      $lLogradouroValido = false;
    }

    if ( $lLogradouroValido )  {
      $this->oImovel->appendChild($oLogradouro);
      return true;
    }
    return false;
  }

  /**
   * Escreve o nó com as informações do terreno.
   * <terreno>
   * @param  integer $iLote número do lode onde será buscado as informações
   * @return boolean
   *         -true: Todos os paramentros obrigatorios válidos.
   *         -false: Um ou mais parâmetros obrigatórios inválidos.
   */
  private function escreveTerreno($iLote, $iMatricula) {

    $lTerrenoValido = true;
    $oDadosTerreno  = $this->getTerreno($iLote, $iMatricula);
    $oTerreno       = $this->oDomDocument->createElement('terreno');

    if ( empty($oDadosTerreno->area_total) ) {

      $this->registraErros("MATRÍCULA: ". $iMatricula . " - TERRENO - Área Total é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( empty($oDadosTerreno->testada) ) {

      $this->registraErros("MATRÍCULA: ". $iMatricula . " - TERRENO - Testada é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( empty($oDadosTerreno->codigo_situacao_quadra) ) {

      $this->registraErros("MATRÍCULA: ". $iMatricula . " - TERRENO - Situação da Quadra é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( empty($oDadosTerreno->valor) ) {

      $this->registraErros("MATRÍCULA: ". $iMatricula . " - TERRENO - Valor é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( $lTerrenoValido ) {

      $oTerreno->setAttribute('area_total'            , number_format($oDadosTerreno->area_total, 2, ',', ''));
      $oTerreno->setAttribute('testada'               , $oDadosTerreno->testada);
      $oTerreno->setAttribute('codigo_situacao_quadra', $oDadosTerreno->codigo_situacao_quadra);
      $oTerreno->setAttribute('valor'                 , number_format($oDadosTerreno->valor, 2, ',', ''));

      $this->oImovel->appendChild($oTerreno);
      return true;
    }
    return false;
  }

  /**
   * Escreve o nó com os dados das contruções
   * <benfeitorias>
   * @param  integer $iGuiaIPTU Matricula pela qual será buscado os dados da Benfeitoria.
   * @return boolean
   *         -true: Todos os paramentros obrigatorios válidos.
   *         -false: Um ou mais parâmetros obrigatórios inválidos.
   */
  private function escreveBenfeitorias($iGuiaIPTU) {

    $lBenfeitoria        = true;
    $rsDadosBenfeitorias = $this->getBenfeitorias($iGuiaIPTU);
    $oBenfeitorias       = $this->oDomDocument->createElement('BENFEITORIAS');
    $iTotalBenfeitorias  = pg_num_rows($rsDadosBenfeitorias);

    if ($iTotalBenfeitorias == 0) {
      return true;
    }

    for ($iBenfeitoria = 0; $iBenfeitoria < $iTotalBenfeitorias; $iBenfeitoria++) {

      $oDadosBenfeitoria  = db_utils::fieldsMemory($rsDadosBenfeitorias, $iBenfeitoria);
      $lEdificacoesValida = true;
      $oEdificacao        = $this->oDomDocument->createElement('edificacao');

      if ( empty($oDadosBenfeitoria->codigo_especie_urbana) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Código Especie Urbana é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->area_total) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Area total é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->codigo_tipo_material) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Tipo de material é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->codigo_padrao_construtivo) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Padrão Construtivo é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->ano_construcao) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Ano Construção é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->valor) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Valor é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( empty($oDadosBenfeitoria->tipo_utilizacao) ) {

        $this->registraErros("MATRÍCULA: ". $iGuiaIPTU . " - BENFEITORIA - Tipo de Utilização é obrigatório.");
        $lEdificacoesValida = false;
        $lBenfeitoria       = false;
      }

      if ( !$lEdificacoesValida ) {
        continue;
      }


      $oDadosBenfeitoria = DBString::utf8_encode_all($oDadosBenfeitoria);

      $oEdificacao->setAttribute('codigo_especie_urbana'    , $oDadosBenfeitoria->codigo_especie_urbana);
      $oEdificacao->setAttribute('area_total'               , number_format($oDadosBenfeitoria->area_total, 2, ',', ''));
      $oEdificacao->setAttribute('area_privativa'           , $oDadosBenfeitoria->area_privativa);
      $oEdificacao->setAttribute('codigo_tipo_material'     , $oDadosBenfeitoria->codigo_tipo_material);
      $oEdificacao->setAttribute('codigo_padrao_construtivo', $oDadosBenfeitoria->codigo_padrao_construtivo);
      $oEdificacao->setAttribute('ano_construcao'           , $oDadosBenfeitoria->ano_construcao);
      $oEdificacao->setAttribute('valor'                    , number_format($oDadosBenfeitoria->valor, 2, ',', ''));
      $oEdificacao->setAttribute('tipo_utilizacao'          , $oDadosBenfeitoria->tipo_utilizacao);

      $oBenfeitorias->appendChild($oEdificacao);
    }

    /**
     * Verifica se existe pelo menos uma benfeitoria válida
     */
    if ( $lBenfeitoria ) {

      $this->oImovel->appendChild($oBenfeitorias);
      return true;
    }

    return false;
  }

  /**
   * Retorna os dados do municipio.
   * @return Object Dados do municipio
   */
  private function getMunicipio(){

    $sSql = "select munic as nome, db21_codigomunicipoestado as codigo from db_config where codigo = {$this->iInstit}";
    $rsMunicipio = db_query($sSql);

    if ( !$rsMunicipio ) {
      throw new DBException(_M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_municipio'));
    }

    $oMunicipio = db_utils::fieldsMemory($rsMunicipio, 0);
    return $oMunicipio;
  }

  /**
   * Retorna os imóveis para geração do arquivo
   * @return Resource recordset com os dados dos imóveis
   */
  private function getImoveis() {

    $sSql  = "select j04_matricregimo as matricula,                                                  ";
    $sSql .= "       case when j04_matricregimo <> ''                                                ";
    $sSql .= "                 then 1                                                                ";
    $sSql .= "                 else null                                                             ";
    $sSql .= "       end as zona,                                                                    ";
    $sSql .= "       j01_matric as nro_registro_iptu,                                                ";
    $sSql .= "       j34_idbql as lote                                                               ";
    $sSql .= "  from iptubase as matricula                                                           ";
    $sSql .= "       inner join lote lote                on lote.j34_idbql = matricula.j01_idbql     ";
    $sSql .= "       left  join iptubaseregimovel imovel on imovel.j04_matric = matricula.j01_matric ";
    $sSql .= " where j01_baixa is null                                                               ";

    $rsImoveis = db_query($sSql);

    if ( !$rsImoveis ) {
      throw new DBException( _M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_imoveis') );
    }

    if ( pg_num_rows($rsImoveis) == 0 ) {}

    return $rsImoveis;
  }

  /**
   * Retorna os dados dos proprietários do imovel
   * @param  integer $iGuiaIPTU Matricula pelo qual será realizado a busca
   * @return Resource Dados dos imóveis
   */
  private function getProprietarios($iGuiaIPTU) {

    $sSql  = "select z01_nome as nome,             ";
    $sSql .= "       z01_cgccpfpropri as cpf_cnpj  ";
    $sSql .= "  from proprietario                  ";
    $sSql .= " where j01_matric = {$iGuiaIPTU}     ";

    $rsProprietarios = db_query($sSql);

    if ( !$rsProprietarios ) {
      throw new DBException(_M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_proprietario'));
    }

    return $rsProprietarios;
  }

  /**
   * Retorna os dados do logradouro do imóvel
   * @param  integer $iLote código do lote pelo qual será realizado a busca
   * @return Object         Dados do Logradouro
   */
  private function getLogradouro($iLote) {

    $sSql  = " select ruastipo.j88_descricao as tipo,                                     ";
    $sSql .= "        j14_nome   as nome,                                                 ";
    $sSql .= "        ''         as complemento,                                          ";
    $sSql .= "        j34_lote   as lote,                                                 ";
    $sSql .= "        j13_descr  as bairro,                                               ";
    $sSql .= "        ''         as vila,                                                 ";
    $sSql .= "        j34_quadra as quadra,                                               ";
    $sSql .= "        j34_setor  as setor,                                                ";
    $sSql .= "        j39_numero as nro,                                                  ";
    $sSql .= "        j39_compl  as complemento                                           ";
    $sSql .= "   from lote l                                                              ";
    $sSql .= "        inner join testpri tp        on tp.j49_idbql        = l.j34_idbql   ";
    $sSql .= "        inner join ruas              on ruas.j14_codigo     = tp.j49_codigo ";
    $sSql .= "        inner join ruastipo          on ruastipo.j88_codigo = ruas.j14_tipo ";
    $sSql .= "        inner join bairro            on j34_bairro          = j13_codi      ";
    $sSql .= "        inner join testada           on testada.j36_codigo  = tp.j49_codigo ";
    $sSql .= "                                    and testada.j36_idbql   = tp.j49_idbql  ";
    $sSql .= "        inner join iptubase          on j01_idbql           = j34_idbql     ";
    $sSql .= "        left  join iptuconstr        on j39_matric          = j01_matric    ";
    $sSql .= "                                    and j39_idprinc         = true          ";
    $sSql .= "  where j34_idbql = {$iLote}                                                ";

    $rsLote = db_query($sSql);

    if ( !$rsLote ) {
      throw new DBException(_M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_lote'));
    }

    if ( pg_num_rows($rsLote) == 0 ) {}

    $oDadosLogradouros = db_utils::fieldsMemory($rsLote, 0);
    return $oDadosLogradouros;
  }

  /**
   * Retorna os dados referente ao terreno do imovel
   * @param  integer $iLote       Código do lote pelo qual será realizada a busca
   * @param  integer $$iMatricula Código da matrícula pela qual será realizada a busca
   * @return Object         Dados do terreno
   */
  private function getTerreno($iLote, $iMatricula) {

    $sSql  = "select j34_area   as area_total,                                                                   ";
    $sSql .= "       j36_testad as testada,                                                                      ";
    $sSql .= "       ( select db142_codigopitsefaz                                                               ";
    $sSql .= "            from carlote                                                                           ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter            = j35_caract       ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica      = db140_sequencial ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial ";
    $sSql .= "          where db140_grupocaracteristica = 47                                                     ";
    $sSql .= "            and j35_idbql = j34_idbql limit 1) as codigo_situacao_quadra,                          ";
    $sSql .= "       j23_vlrter as valor                                                                         ";
    $sSql .= "  from iptubase m                                                                                  ";
    $sSql .= "       inner join lote l      on l.j34_idbql         = m.j01_idbql                                 ";
    $sSql .= "       left join iptucalc c   on c.j23_matric        = m.j01_matric                                ";
    $sSql .= "                             and c.j23_anousu        = '{$this->iAno}'                             ";
    $sSql .= "       inner join testpri tp  on tp.j49_idbql        = l.j34_idbql                                 ";
    $sSql .= "       inner join testada     on testada.j36_codigo  = tp.j49_codigo                               ";
    $sSql .= "                             and testada.j36_idbql   = tp.j49_idbql                                ";
    $sSql .= " where j34_idbql    = $iLote                                                                       ";
    $sSql .= "   and m.j01_matric = $iMatricula                                                                  ";

    $rsTerreno = db_query($sSql);

    if ( !$rsTerreno ){
      throw new DBException(_M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_terreno'));
    }

    $oTerreno = db_utils::fieldsMemory($rsTerreno, 0);
    return $oTerreno;
  }

  /**
   * Retorna as Benfeitorias do imóvel
   * @param  integer $iGuiaIPTU Matricula pelo qual será será realizado a busca
   * @return Resource Dados das Benfeitorias
   */
  private function getBenfeitorias($iGuiaIPTU) {

    $sSql  = "select ( select db142_codigopitsefaz                                                                            ";
    $sSql .= "           from  carconstr as c                                                                                 ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter            = j48_caract                    ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica      = db140_sequencial              ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial              ";
    $sSql .= "          where db140_grupocaracteristica = 46                                                                  ";
    $sSql .= "            and c.j48_matric = j39_matric and c.j48_idcons = j39_idcons limit 1 ) as codigo_tipo_material,      ";
    $sSql .= "       ( select db142_codigopitsefaz                                                                            ";
    $sSql .= "           from  carconstr as c                                                                                 ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter            = j48_caract                    ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica      = db140_sequencial              ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial              ";
    $sSql .= "          where db140_grupocaracteristica = 43                                                                  ";
    $sSql .= "            and c.j48_matric = j39_matric and c.j48_idcons = j39_idcons limit 1 ) as codigo_especie_urbana,     ";
    $sSql .= "       ( select db142_codigopitsefaz                                                                            ";
    $sSql .= "           from  carconstr as c                                                                                 ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter            = j48_caract                    ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica      = db140_sequencial              ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial              ";
    $sSql .= "          where db140_grupocaracteristica = 45                                                                  ";
    $sSql .= "            and c.j48_matric = j39_matric and c.j48_idcons = j39_idcons limit 1 ) as codigo_padrao_construtivo, ";
    $sSql .= "        (select                                                                                                 ";
    $sSql .= "                (CASE                                                                                            ";
    $sSql .= "                   WHEN j32_tipo = 'L' THEN                                                                     ";
    $sSql .= "                     (SELECT j31_descr                                                                          ";
    $sSql .= "                       FROM caracter                                                                            ";
    $sSql .= "                            INNER JOIN carlote ON (j35_caract = j31_codigo)                                     ";
    $sSql .= "                                              AND (j35_idbql  = j01_idbql)                                      ";
    $sSql .= "                      WHERE j31_grupo = j32_grupo                                                               ";
    $sSql .= "                      LIMIT 1                                                                                   ";
    $sSql .= "                     )                                                                                          ";
    $sSql .= "                   WHEN j32_tipo = 'C' THEN                                                                     ";
    $sSql .= "                     (SELECT j31_descr                                                                          ";
    $sSql .= "                       FROM caracter                                                                            ";
    $sSql .= "                            INNER JOIN carconstr ON (j48_caract = j31_codigo)                                   ";
    $sSql .= "                                                AND (j48_idcons = j39_idcons)                                   ";
    $sSql .= "                                                AND (j48_matric = j01_matric)                                   ";
    $sSql .= "                      WHERE j31_grupo = j32_grupo                                                               ";
    $sSql .= "                      LIMIT 1                                                                                   ";
    $sSql .= "                     )                                                                                          ";
    $sSql .= "                                                                                                                ";
    $sSql .= "                   ELSE null                                                                                    ";
    $sSql .= "                END)                                                                                             ";
    $sSql .= "           from configuracaogrupocaracteristicas                                                                ";
    $sSql .= "                inner join cargrup on j32_grupo = db144_tipoutilizacaoiptu                                      ";
    $sSql .= "        ) as tipo_utilizacao,                                                                                   ";
    $sSql .= "        j39_area  as area_total,                                                                                ";
    $sSql .= "        j39_areap as area_privativa,                                                                            ";
    $sSql .= "        j39_ano   as ano_construcao,                                                                            ";
    $sSql .= "        j22_valor as valor                                                                                      ";
    $sSql .= "  from iptubase ib                                                                                              ";
    $sSql .= "       inner join iptuconstr   on j01_matric = j39_matric                                                       ";
    $sSql .= "       inner join iptucale     on j39_matric = j22_matric                                                       ";
    $sSql .= "                              and j39_idcons = j22_idcons                                                       ";
    $sSql .= "                              and j22_anousu = {$this->iAno}                                                    ";
    $sSql .= "where j01_matric = {$iGuiaIPTU};                                                                                ";

    $rsBenfeitorias = db_query($sSql);

    if ( !$rsBenfeitorias ){
      throw new DBException(_M(GeracaoArquivoPitIptu::MENSAGENS . 'erro_benfeitoria'));
    }

    return $rsBenfeitorias;
  }
}


?>
