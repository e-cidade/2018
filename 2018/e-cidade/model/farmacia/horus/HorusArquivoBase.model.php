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

abstract class HorusArquivoBase  {

  const MENSAGEM            = 'saude.farmacia.IntegracaoHorus.';
  const ARQUIVO_ENTRADA     = 1;
  const ARQUIVO_SAIDA       = 2;
  const ARQUIVO_DISPENSACAO = 3;

  /**
   * Situações da integração de uma competencia
   */
  const SEM_DADOS            = 1;
  const AGUARDANDO_ENVIO     = 2;
  const PARCIALMENTE_ENVIADO = 3;
  const AGUARDANDO_HORUS     = 4;
  const INCONSISTENTE        = 5;
  const CONCLUIDO            = 6;

  protected static $aSituacoes = array(
    self::SEM_DADOS            => "SEM DADOS",
    self::AGUARDANDO_ENVIO     => "AGUARDANDO ENVIO",
    self::PARCIALMENTE_ENVIADO => "PARCIALMENTE ENVIADO",
    self::AGUARDANDO_HORUS     => "AGUARDANDO HÓRUS",
    self::INCONSISTENTE        => "INCONSISTENTE",
    self::CONCLUIDO            => "CONCLUÍDO",
  );

  /**
   * Competencia que será extraido os dados
   * @var DBCompetencia
   */
  protected $oCompetencia;

  /**
   * UPS que esta gerando o arquivo
   * @var UnidadeProntoSocorro
   */
  protected $oUPS;

  /**
   * Usuário logado
   * @var UsuarioSistema
   */
  protected $oUsuario;

  /**
   * @var XMLWriter
   */
  protected $oXML;

  /**
   * Código que representa o conjunto de dados de um dos arquivos da integração horus
   * @var integer
   */
  protected $iCodigoIntegracaoCompetencia;


  /**
   * Identifica se existe um ou mais registro com inconsistencia dos dados na competencia
   * @var boolean
   */
  protected $lPossuiRegistroInconsistente = false;

  protected $iSituacao;

  public function __construct(DBCompetencia $oCompetencia, UnidadeProntoSocorro $oUPS, UsuarioSistema $oUsuario) {

    $this->oCompetencia = $oCompetencia;
    $this->oUPS         = $oUPS;
    $this->oUsuario     = $oUsuario;

  }

  /**
   * Cria o arquivo xml e o cabeçalho das informações requiridas pelo Horus
   * @param  string    $sTipoArquivo Informa o tipo de XML enviado; E - Entrada; S - Saida, ET - Estoque; DP- Dispensacao e Paciente
   * @return XMLWriter
   */
  protected function criarArquivo( $sTipoArquivo ) {

    $this->oXML = new XMLWriter();
    $this->oXML->openMemory();
    $this->oXML->setIndent(true);
    $this->oXML->startDocument('1.0','UTF-8');
    $this->oXML->startElement("root");
    //$this->oXML->writeAttribute("xmlns", "http://www.saude.gov.br/ws-horus-paciente-dispensacao");

    /**
     * @todo verificar se necessita namespace
     * @todo lembrar de informar o usuário
     */
    //$this->oXML->writeAttribute("xmlns","http://www.saude.gov.br/ws-horus-saida-diversa");

    $this->oXML->startElement("identificador");
    $this->oXML->startElement("stEsferaEnvio");
    $this->oXML->text("M");
    $this->oXML->endElement();

    $this->oXML->startElement("coMunicipioIbge");
    $this->oXML->text( substr($this->oUPS->getIBGE(), 0, 6));
    $this->oXML->endElement();

    $this->oXML->startElement("coUfIbge");
    $this->oXML->endElement();

    $this->oXML->startElement("noUsuario");
    $this->oXML->text(DBString::removerAcentuacao($this->oUsuario->getNome()));
    $this->oXML->endElement();

    $this->oXML->startElement("tpXML");
    $this->oXML->text($sTipoArquivo);
    $this->oXML->endElement();

    $this->oXML->startElement("stHorus");
    $this->oXML->text("N");
    $this->oXML->endElement();
    $this->oXML->endElement(); // fecha identificador

  }


  /**
   * Cria o agrupador dos dados do arquivo e suas informações
   * @param  string   $sNomeAgrupador nome do agrupador: "registro" quando Saida e Entrada, "dispensacao" quando Dispensacao
   * @param  stdClass $oDados         Objeto contendo todos os dados do registro
   * @param  array    $aCampos        Lista dos campos que o arquivo contém
   * @return null
   */
  protected function adicionarRegistro($sNomeAgrupador, $oDados, $aCampos) {

    $this->oXML->startElement($sNomeAgrupador);

    $this->oXML->startElement("coUnidadeCnes");
    $this->oXML->text($this->oUPS->getCNES());
    $this->oXML->endElement();

    foreach ($aCampos as $sIndex => $sCampo) {

      $this->oXML->startElement($sCampo);
      $this->oXML->text( $oDados->$sIndex );
      $this->oXML->endElement();
    }
    $this->oXML->endElement();
  }

  /**
   * Fecha o XML e escreve o arquivo
   * @param  string $sNomeArquivo nome do arquivo
   * @return string               nome do arquivo com diretorio
   */
  protected function fecharArquivo($sNomeArquivo) {

    $this->oXML->endElement();
    $sArquivo        = $this->oXML->outputMemory();
    $sPathArquivo    = "tmp/{$sNomeArquivo}";
    $rArquivoEntrada = fopen($sPathArquivo, 'w');
    fputs($rArquivoEntrada, $sArquivo);
    fclose($rArquivoEntrada);

    return $sPathArquivo;
  }

  /**
   * Retorna a situação referente a um arquivo em uma competência
   * @param $iTipoArquivo
   * @return integer
   * @throws DBException
   */
  public function situacaoArquivoCompetencia( $iTipoArquivo ) {

    $this->iSituacao        = self::AGUARDANDO_ENVIO;
    $oDaoIntegracaoHorus    = new cl_integracaohorus();
    $sWhereIntegracaoHorus  = "     fa59_mesreferente = {$this->oCompetencia->getMes()}";
    $sWhereIntegracaoHorus .= " and fa59_anoreferente = {$this->oCompetencia->getAno()}";
    $sWhereIntegracaoHorus .= " and fa59_db_depart    = " . $this->oUPS->getCodigo();
    $sWhereIntegracaoHorus .= " and fa59_tipoarquivo  = {$iTipoArquivo}";

    $sSqlIntegracaoHorus = $oDaoIntegracaoHorus->sql_query_integracao_envio(
                                                                             null,
                                                                             "fa59_situacaohorus",
                                                                             null,
                                                                             $sWhereIntegracaoHorus
                                                                           );
    $rsIntegracaoHorus = db_query( $sSqlIntegracaoHorus );

    if( !$rsIntegracaoHorus ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( self::MENSAGEM . 'erro_buscar_situacao', $oErro ) );
    }

    if( pg_num_rows( $rsIntegracaoHorus ) > 0 ) {
      $this->iSituacao = db_utils::fieldsMemory( $rsIntegracaoHorus, 0 )->fa59_situacaohorus;
    }

    return $this->iSituacao;
  }

  /**
   * Retorna o código da integracao horus na competencia.
   * Se não tiver dados para competência, cria
   * @param  integer $iTipoArquivo código para identificar o tipo do arquivo (Dispensação, Saida ou Entrada)
   * @return integer
   */
  protected function getCodigoIntegracaoCompetencia(  ) {

    if ( !empty($this->iCodigoIntegracaoCompetencia) ) {
      return $this->iCodigoIntegracaoCompetencia;
    }

    $sWhere  = "     fa59_mesreferente = " . $this->oCompetencia->getMes();
    $sWhere .= " and fa59_anoreferente = " . $this->oCompetencia->getAno();
    $sWhere .= " and fa59_db_depart    = " . $this->oUPS->getCodigo();
    $sWhere .= " and fa59_tipoarquivo  = {$this->iCodigoTipo} " ;

    $oDaoIntegracaoHorus = new cl_integracaohorus();
    $sSqlDadosIntegracao = $oDaoIntegracaoHorus->sql_query_file(null, " fa59_codigo, fa59_situacaohorus ", null, $sWhere );
    $rsDadosIntegracao   = db_query( $sSqlDadosIntegracao );

    $oMsgErro = new stdClass();
    if ( !$rsDadosIntegracao ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MENSAGEM . "erro_verificar_dados_integracao", $oMsgErro ) );
    }

    if (pg_num_rows($rsDadosIntegracao) == 1) {

      $oDadosIntegracao                   = db_utils::fieldsMemory($rsDadosIntegracao, 0);
      $this->iCodigoIntegracaoCompetencia = $oDadosIntegracao->fa59_codigo;
      $this->iSituacao                    = $oDadosIntegracao->fa59_situacaohorus;
      return $this->iCodigoIntegracaoCompetencia;
    }

    $oDaoIntegracaoHorus->fa59_codigo        = null;
    $oDaoIntegracaoHorus->fa59_usuario       = $this->oUsuario->getCodigo();
    $oDaoIntegracaoHorus->fa59_mesreferente  = $this->oCompetencia->getMes();
    $oDaoIntegracaoHorus->fa59_anoreferente  = $this->oCompetencia->getAno();
    $oDaoIntegracaoHorus->fa59_tipoarquivo   = $this->iCodigoTipo;
    $oDaoIntegracaoHorus->fa59_situacaohorus = self::AGUARDANDO_ENVIO;
    $oDaoIntegracaoHorus->fa59_db_depart     = $this->oUPS->getCodigo();
    $oDaoIntegracaoHorus->incluir(null);

    if ( $oDaoIntegracaoHorus->erro_status == 0 ) {

      $oMsgErro->sErro = str_replace('\\n', "\n", $oDaoIntegracaoHorus->erro_msg);
      throw new Exception( _M(self::MENSAGEM . "erro_incluir_competencia_integracao", $oMsgErro ));
    }
    $this->iCodigoIntegracaoCompetencia = $oDaoIntegracaoHorus->fa59_codigo;
    return $this->iCodigoIntegracaoCompetencia;

  }

  /**
   * Atualiza a situacao da integração de uma competencia
   *
   * @param integer $iSituacao
   */
  public function atualizaSituacaoIntegracao($iSituacao) {

    $oDaoIntegracaoHorus                     = new cl_integracaohorus();
    $oDaoIntegracaoHorus->fa59_situacaohorus = $iSituacao;
    $oDaoIntegracaoHorus->fa59_codigo        = $this->iCodigoIntegracaoCompetencia;
    $oDaoIntegracaoHorus->alterar($this->iCodigoIntegracaoCompetencia);

    $oMsgErro = new stdClass();
    if ( $oDaoIntegracaoHorus->erro_status == 0 ) {

      $oMsgErro->sErro = str_replace('\\n', "\n", $oDaoIntegracaoHorus->erro_msg);
      throw new Exception( _M(self::MENSAGEM . "erro_alterar_situacao_competencia_integracao", $oMsgErro ));
    }
    $this->iSituacao = $iSituacao;
    return true;
  }

  /**
   * Validações comum a todo pre-processamento dos arquivos de entrada, saída e dispensação
   *
   * @throws DBException
   * @throws BusinessException
   */
  public function preProcessar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M(self::MENSAGEM . "sem_transacao") );
    }

    $oMsgErro = new stdClass();

    /**
     * Se UPS não possui código do CNES, para o processamento, pois é registro obrigatorio em todas linhas da dispensação
     */
    if ($this->oUPS->getCNES() == '' || strlen($this->oUPS->getCNES()) != 7) {

      $oMsgErro->sDepartamento = $this->oUPS->getCodigo() . ' - ' . $this->oUPS->getDepartamento()->getNomeDepartamento();
      throw new BusinessException( _M( self::MENSAGEM . "ups_sem_cnes", $oMsgErro) );
    }

    /**
     * Se código IBGE tem menos de 6 digitos é considerado inválido
     */
    if ( strlen($this->oUPS->getIBGE()) < 6 ) {

      $oMsgErro->sDepartamento = $this->oUPS->getCodigo() . ' - ' . $this->oUPS->getDepartamento()->getNomeDepartamento();
      throw new BusinessException( _M( self::MENSAGEM . "ups_ibeg_inconsistente", $oMsgErro) );
    }
  }

  /**
   * Salva o protocolo retornado pelo webservice hórus
   * @param  integer $iProtocolo
   * @return boolean
   * @throws Exception
   */
  public function salvarProtocolo( $iProtocolo ) {

    if ( empty($iProtocolo) ) {
      throw new Exception( _M( self::MENSAGEM . "protocolo_nao_informado" ) );
    }

    $oDaoIntegracaoEnvio                        = new cl_integracaohorusenvio();
    $oDaoIntegracaoEnvio->fa64_sequencial       = null;
    $oDaoIntegracaoEnvio->fa64_protocolo        = $iProtocolo;
    $oDaoIntegracaoEnvio->fa64_integracaohorus  = $this->iCodigoIntegracaoCompetencia;
    $oDaoIntegracaoEnvio->fa64_hora             = date('H:i');
    $oDaoIntegracaoEnvio->fa64_data             = date('Y-m-d');

    $oDaoIntegracaoEnvio->incluir(null);

    $oMsgErro = new stdClass();
    if ( $oDaoIntegracaoEnvio->erro_status == 0 ) {

      $oMsgErro->sErro = str_replace('\\n', "\n", $oDaoIntegracaoEnvio->erro_msg);
      throw new Exception( _M( self::MENSAGEM . "erro_salvar_protocolo", $oMsgErro ) );
    }

    foreach ($this->aMedicamentos as $oDados ) {

      $oDaoDadosCompetencia = new cl_integracaohorusenviodadoscompetencia();
      $oDaoDadosCompetencia->fa65_sequencial           = null;
      $oDaoDadosCompetencia->fa65_integracaohorusenvio = $oDaoIntegracaoEnvio->fa64_sequencial;
      $oDaoDadosCompetencia->fa65_dadoscompetencia     = $oDados->codigo;

      $oDaoDadosCompetencia->incluir(null);

      if ( $oDaoIntegracaoEnvio->erro_status == 0 ) {

        $oMsgErro->sErro    = str_replace('\\n', "\n", $oDaoDadosCompetencia->erro_msg);
        $oMsgErro->sArquivo = self::NOME;
        throw new Exception( _M( self::MENSAGEM . "erro_salvar_dado_enviado", $oMsgErro ) );
      }
    }

    return true;
  }

  public function getUltimoEnvio() {

    $sCampo = " max (fa64_data), fa64_protocolo, fa64_integracaohorus  ";

    $sWhere  = "";
    $sWhere  = "     fa59_mesreferente = {$this->oCompetencia->getMes()}";
    $sWhere .= " and fa59_anoreferente = {$this->oCompetencia->getAno()}";
    $sWhere .= " and fa59_db_depart    = " . $this->oUPS->getCodigo();
    $sWhere .= " and fa59_tipoarquivo  = {$this->iCodigoTipo} ";
    $sWhere .= " and fa64_data < current_date";
    $sWhere .= " group by fa64_protocolo, fa64_integracaohorus limit 1";

    $oDaoIntegracaoEnvio = new cl_integracaohorusenvio();
    $sSqlIntegracaoEnvio = $oDaoIntegracaoEnvio->sql_query(null, $sCampo, null, $sWhere );
    $rsIntegracaoEnvio   = pg_query( $sSqlIntegracaoEnvio );

    $oMsgErro = new stdClass();
    if ( !$rsIntegracaoEnvio ) {

      $oMsgErro->sErro    = pg_last_error();
      $oMsgErro->sArquivo = self::TIPO;
      throw new DBException( _M(self::MENSAGEM . "erro_buscar_envio", $oMsgErro ) );
    }

    if ( pg_num_rows($rsIntegracaoEnvio) == 0 ) {
      return null;
    }

    $oDadosEnvio = db_utils::fieldsMemory($rsIntegracaoEnvio, 0);
    $this->iCodigoIntegracaoCompetencia = $oDadosEnvio->fa64_integracaohorus;

    return $oDadosEnvio->fa64_protocolo;
  }

  /**
   * Retorna a Situação do arquivo
   * @param  integer $iSituacaoArquivo
   * @return string
   */
  public function getSituacaoArquivo( $iSituacaoArquivo ) {
    return isset(self::$aSituacoes[$iSituacaoArquivo]) ? self::$aSituacoes[$iSituacaoArquivo] : null;
  }

  /**
   * Verifica se o arquivo pode ser enviado
   * @param $iTipoArquivo
   * @return stdClass
   * @throws Exception
   */
  public function permiteEnvio( $iTipoArquivo ) {

    $oDaoIntegracaoHorus    = new cl_integracaohorusenvio();
    $sWhereIntegracaoHorus  = "     fa59_mesreferente = {$this->oCompetencia->getMes()}";
    $sWhereIntegracaoHorus .= " and fa59_anoreferente = {$this->oCompetencia->getAno()}";
    $sWhereIntegracaoHorus .= " and fa59_tipoarquivo  = {$iTipoArquivo}";
    $sWhereIntegracaoHorus .= " and fa59_db_depart    = " . $this->oUPS->getCodigo();
    $sWhereIntegracaoHorus .= " and fa64_data = current_date";

    $sSqlDataEnvio = $oDaoIntegracaoHorus->sql_query( null, 'fa64_data', null, $sWhereIntegracaoHorus );
    $rsDataEnvio = db_query($sSqlDataEnvio);

    if( !$rsDataEnvio ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();

      throw new Exception( _M( self::MENSAGEM . "erro_buscar_data_envio", $oMsgErro ) );
    }

    $oRetornoValidar = new stdClass();
    $oRetornoValidar->lDataEnvioValida = pg_num_rows($rsDataEnvio) > 0 ? false : true;
    $oRetornoValidar->lSituacaoArquivo =  ( $this->iSituacao != self::SEM_DADOS && $this->iSituacao != self::CONCLUIDO ) ? true : false;

    return $oRetornoValidar;
  }

  /**
   * Verifica se existe inconsistência nos dados da competência de acordo com o tipo do arquivo
   * @return bool
   * @throws DBException
   */
  public function temInconsistencia() {

    $lTemInconsistencia = false;
    $sInstanciaDAO      = "cl_dadoscompetenciaentrada";
    $sWhere             = "( fa62_enviar is false )";

    if( $this->iCodigoTipo == 2 ) {

      $sInstanciaDAO = "cl_dadoscompetenciasaida";
      $sWhere        = "( fa63_enviar is false )";
    }

    if( $this->iCodigoTipo == 3 ) {

      $sInstanciaDAO = "cl_dadoscompetenciadispensacao";
      $sWhere        = "( fa61_enviar is false )";
    }

    $sWhere .= " and fa59_db_depart = {$this->oUPS->getCodigo()}";
    $sWhere .= " and fa59_mesreferente = {$this->oCompetencia->getMes()}";
    $sWhere .= " and fa59_anoreferente = {$this->oCompetencia->getAno()}";
    $sWhere .= " and fa59_tipoarquivo  = {$this->iCodigoTipo}";

    $oDaoDadosCompetencia    = new $sInstanciaDAO();
    $sSqlDadosCompetencia    = $oDaoDadosCompetencia->sql_query( null, '1', null, $sWhere );
    $rsDadosCompetencia      = db_query( $sSqlDadosCompetencia );

    if( !$rsDadosCompetencia ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( self::MENSAGEM . "erro_validar_competencia", $oErro ) );
    }

    if( pg_num_rows( $rsDadosCompetencia ) > 0 ) {
      $lTemInconsistencia = true;
    }

    return $lTemInconsistencia;
  }
}