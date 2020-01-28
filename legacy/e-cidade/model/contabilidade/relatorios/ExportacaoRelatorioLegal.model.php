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

class ExportacaoRelatorioLegal{


  /**
   * codigo do relatorio a ser importado
   * @var integer
   */
  private $iCodigoRelatorio;

  /**
   * objeto com os dados do relatorio que será convertido em json
   * @var object
   */
  private $oValoresRelatorio;

  /**
   * Constante do caminho da mensagem do model
   * @var string
   */
  const CAMINHO_MENSAGENS = "financeiro/contabilidade/ExportacaoRelatorioLegal.";

  /**
   *
   * metodo construtor, seta o codigo do relatorio.
   * @param integer $iCodigoRelatorio a ser exportado
   * @throws ParameterException se nao for setado relatorio
   */
  public function __construct( $iCodigoRelatorio ){

    if (empty($iCodigoRelatorio)) {
      throw new ParameterException( _M( self::CAMINHO_MENSAGENS. "sem_codigo_relatorio"));
    }
    $this->setCodigoRelatorio($iCodigoRelatorio);
  }

  /**
   * retorna o codigo do relatorio
   * @return integer
   */
  private function getCodigoRelatorio(){
    return $this->iCodigoRelatorio;
  }

  /**
   * setamos o codigo do relatorio
   * @param integer $iCodigoRelatorio
   */
  private function setCodigoRelatorio($iCodigoRelatorio){

    $this->iCodigoRelatorio = $iCodigoRelatorio;
  }


  /**
   *
   * funcao para retornar os dados do relatorio a ser exportado
   * @return object
   *
   */
  private function getDadosRelatorio(){

    $oDaoOrcParamRel    = db_utils::getDao('orcparamrel');
    $sSqlDadosRelatorio = $oDaoOrcParamRel->sql_query_file($this->getCodigoRelatorio());
    $rsDadosRelatorio   = $oDaoOrcParamRel->sql_record($sSqlDadosRelatorio);
    if ($oDaoOrcParamRel->numrows == 0) {
      throw new DBException( _M(self::CAMINHO_MENSAGENS . ('relatorio_nao_encontrado') ) );
    }

    $oRelatorio = db_utils::fieldsMemory($rsDadosRelatorio, 0);
    $oDadosRelatorio = new stdClass();
    $oDadosRelatorio->o42_codparrel        = $oRelatorio->o42_codparrel;
    $oDadosRelatorio->o42_descrrel         = $oRelatorio->o42_descrrel;
    $oDadosRelatorio->o42_orcparamrelgrupo = $oRelatorio->o42_orcparamrelgrupo;
    $oDadosRelatorio->o42_notapadrao       = $oRelatorio->o42_notapadrao;
    return $oDadosRelatorio;

  }

  /**
   * retornará um objeto de periodos que terá um array de periodos vinculados ao relatorio
   * e um array de periodos cadastrados
   * @return object
   */
  private function getPeriodosRelatorio(){

    $oDadosPeriodos                     = new stdClass();
    //propriedade que se refere aos periodos vinculados
    $oDadosPeriodos->periodos_relatorio = array();
    // propriedade dos periodos vinculados, caso necessario incluir na importação
    $oDadosPeriodos->periodos           = array();
    $oDaoPeriodos                       = db_utils::getDao('orcparamrelperiodos');

    $sCamposPeriodos  = "o113_sequencial  , ";
    $sCamposPeriodos .= "o113_periodo     , ";
    $sCamposPeriodos .= "o113_orcparamrel , ";

    $sCamposPeriodos .= "o114_sequencial  , ";
    $sCamposPeriodos .= "o114_descricao   , ";
    $sCamposPeriodos .= "o114_qdtporano   , ";
    $sCamposPeriodos .= "o114_diainicial  , ";
    $sCamposPeriodos .= "o114_mesinicial  , ";
    $sCamposPeriodos .= "o114_diafinal    , ";
    $sCamposPeriodos .= "o114_mesfinal    , ";
    $sCamposPeriodos .= "o114_sigla       , ";
    $sCamposPeriodos .= "o114_ordem         ";

    $sSqlPeriodos       = $oDaoPeriodos->sql_query ( null, $sCamposPeriodos, null, "o113_orcparamrel = " . $this->getCodigoRelatorio());
    $rsPeriodos         = $oDaoPeriodos->sql_record($sSqlPeriodos);

    if ($oDaoPeriodos->numrows >= 1) {

      for( $iPeriodo = 0; $iPeriodo < $oDaoPeriodos->numrows; $iPeriodo++){

        $oValorPeriodo  = db_utils::fieldsMemory($rsPeriodos, $iPeriodo);
        $oPeriodosRelatorio = new stdClass();
        $oPeriodos          = new stdClass();

        $oPeriodosRelatorio->codigo  = $oValorPeriodo->o113_sequencial;
        $oPeriodosRelatorio->periodo = $oValorPeriodo->o113_periodo;

        $oPeriodos->codigo            = $oValorPeriodo->o114_sequencial;
        $oPeriodos->nome              = urlencode($oValorPeriodo->o114_descricao);
        $oPeriodos->quantidade_no_ano = $oValorPeriodo->o114_qdtporano ;
        $oPeriodos->dia_inicial       = $oValorPeriodo->o114_diainicial;
        $oPeriodos->mes_inicial       = $oValorPeriodo->o114_mesinicial;
        $oPeriodos->dia_final         = $oValorPeriodo->o114_diafinal  ;
        $oPeriodos->mes_final         = $oValorPeriodo->o114_mesfinal  ;
        $oPeriodos->sigla             = $oValorPeriodo->o114_sigla     ;
        $oPeriodos->ordem             = $oValorPeriodo->o114_ordem     ;

        $oDadosPeriodos->periodos_relatorio[] = $oPeriodosRelatorio;//
        $oDadosPeriodos->periodos[]           = $oPeriodos;
      }
    }

    return $oDadosPeriodos;
  }

  /**
   * metodo que retornará um array de colunas utilizadas no relatorio
   * a query é feita baseada no relatorio e na linha em questão
   * @return array $aColunas
   */
  private function getColunasLinhaRelatorio( $iLinha = null ){

    if ( empty($iLinha)) {
      throw new DBException( _M(self::CAMINHO_MENSAGENS . ('linha_coluna') ) );
    }

    $oDadosColuna = new stdClass();
    $oDadosColuna->colunas           = array();
    $oDadosColuna->colunas_relatorio = array();

    $oDadoColunas = db_utils::getDao("orcparamseqorcparamseqcoluna");

    $sCamposColunas  = "o116_sequencial         , ";
    $sCamposColunas .= "o116_codseq             , ";
    $sCamposColunas .= "o116_codparamrel        , ";
    $sCamposColunas .= "o116_orcparamseqcoluna  , ";
    $sCamposColunas .= "o116_ordem              , ";
    $sCamposColunas .= "o116_periodo            , ";
    $sCamposColunas .= "o116_formula              ";

    $sSqlColunas  = $oDadoColunas->sql_query ( null,$sCamposColunas , null,"o116_codparamrel = {$this->getCodigoRelatorio()} and o116_codseq = {$iLinha} ");
    $rsColunas    = $oDadoColunas->sql_record($sSqlColunas);

    if ($oDadoColunas->numrows >= 1) {

      for($iColuna = 0; $iColuna < $oDadoColunas->numrows;$iColuna++){

        $oValoresColuna    = db_utils::fieldsMemory($rsColunas, $iColuna);
        $oColunas          = new stdClass();
        $oColunasRelatorio = new stdClass();
        /*
         * objeto com os dados da coluna a ser vinculado com a linha do relatorio (orcparamseqorcparamseqcoluna)
         * essa coluna ja deverá existir na orcparamseqcoluna
         */
        $oColunasRelatorio->codigo  = $oValoresColuna->o116_sequencial          ;
        $oColunasRelatorio->coluna  = $oValoresColuna->o116_orcparamseqcoluna   ;
        $oColunasRelatorio->periodo = $oValoresColuna->o116_periodo             ;
        $oColunasRelatorio->formula = $oValoresColuna->o116_formula             ;
        $oColunasRelatorio->ordem   = $oValoresColuna->o116_ordem               ;
        $oDadosColuna->colunas_relatorio[] = $oColunasRelatorio;

      }
    }
    return $oDadosColuna;
  }

  /**
   * metodo que retorna todas colunas utilizadas pelo relatorio
   * se no vinculo tiver uma coluna que na base de destino não tem, devemos incluir na importação na orcparamseqcoluna
   *
   * @return multitype:stdClass
   */
  private function getDadosColunas(){

    $oDadoColunas = db_utils::getDao("orcparamseqorcparamseqcoluna");
    $sSqlColunas  = $oDadoColunas->sql_query ( null, "*" , null, "o116_codparamrel = {$this->getCodigoRelatorio()} ");
    $rsColunas    = $oDadoColunas->sql_record($sSqlColunas);
    $aColunas     = array();

    if ($oDadoColunas->numrows >= 1) {

      for($iColuna = 0; $iColuna < $oDadoColunas->numrows;$iColuna++){

        $oValoresColuna         = db_utils::fieldsMemory($rsColunas, $iColuna);
        $oColunas               = new stdClass();
        $oColunas->codigo       = $oValoresColuna->o115_sequencial           ;
        $oColunas->ano          = $oValoresColuna->o115_anousu               ;
        $oColunas->descricao    = urlencode($oValoresColuna->o115_descricao) ;
        $oColunas->tipo         = $oValoresColuna->o115_tipo                 ;
        $oColunas->valor_padrao = $oValoresColuna->o115_valoresdefault       ;
        $oColunas->nome_coluna  = urlencode($oValoresColuna->o115_nomecoluna);

        $aColunas[] = $oColunas;
      }
    }

    return  $aColunas;
  }

  /**
   *
   * metodo que retornará os filtros padroes das linhas do relatorio
   *
   * @param integer $iLinha
   * @return array $aFiltroPadrao
   */
  private function getFiltroPadrao( $iLinha = null ){

    if ( empty($iLinha)) {
      throw new DBException( _M(self::CAMINHO_MENSAGENS . ('linha_filtro_padrao') ) );
    }

    $oDaoFiltroPardao = db_utils::getDao("orcparamseqfiltropadrao");

    $sCamposFiltroPadrao  = "o132_sequencial , ";
    $sCamposFiltroPadrao .= "o132_anousu     , ";
    $sCamposFiltroPadrao .= "o132_filtro       ";

    $sSqlFiltroPadrao = $oDaoFiltroPardao->sql_query (null, $sCamposFiltroPadrao, null, "o132_orcparamrel = {$this->getCodigoRelatorio()} and o132_orcparamseq = {$iLinha}");
    $rsFiltroPadrao   = $oDaoFiltroPardao->sql_record($sSqlFiltroPadrao);
    $aFiltroPadrao    = array();
    if ($oDaoFiltroPardao->numrows >= 1) {

      for ($iLinha = 0; $iLinha < $oDaoFiltroPardao->numrows; $iLinha++) {

        $oValorFiltroPadrao         = db_utils::fieldsMemory($rsFiltroPadrao, $iLinha);
        $oDadosFiltroPadrao         = new stdClass();
        $oDadosFiltroPadrao->codigo = $oValorFiltroPadrao->o132_sequencial ;
        $oDadosFiltroPadrao->ano    = $oValorFiltroPadrao->o132_anousu     ;
        $oDadosFiltroPadrao->filtro = urlencode($oValorFiltroPadrao->o132_filtro);
        $aFiltroPadrao[] = $oDadosFiltroPadrao;
      }
    }
    return $aFiltroPadrao;
  }

  /**
   * metodo que retornará array de objetos de linhas do relatorio
   * cada linha pode ter um filtro por ano
   * @return ArrayObject
   */
  private function getLinhasRelatorio(){

    $aDadosLinhaRelatorio = array();
    $oDaoLinha = db_utils::getDao("orcparamseq");

    $sCamposLinha  = "o69_codseq         , ";
    $sCamposLinha .= "o69_descr          , ";
    $sCamposLinha .= "o69_nivel          , ";
    $sCamposLinha .= "o69_labelrel       , ";
    $sCamposLinha .= "o69_manual         , ";
    $sCamposLinha .= "o69_totalizador    , ";
    $sCamposLinha .= "o69_ordem          , ";
    $sCamposLinha .= "o69_observacao     , ";
    $sCamposLinha .= "o69_desdobrarlinha , ";
    $sCamposLinha .= "o69_origem         , ";
    $sCamposLinha .= "o69_nivellinha     , ";
    $sCamposLinha .= "o69_grupo          , ";
    $sCamposLinha .= "o69_grupoexclusao  , ";
    $sCamposLinha .= "o69_verificaano    , ";
    $sCamposLinha .= "o69_libnivel       , ";
    $sCamposLinha .= "o69_librec         , ";
    $sCamposLinha .= "o69_libsubfunc     , ";
    $sCamposLinha .= "o69_libfunc          ";


    $sSqlLinha = $oDaoLinha->sql_query_file ( null, null, $sCamposLinha,null,"o69_codparamrel = " . $this->getCodigoRelatorio());
    $rsLinha   = $oDaoLinha->sql_record($sSqlLinha);

    if ($oDaoLinha->numrows >= 1) {

      for ($iLinha = 0; $iLinha < $oDaoLinha->numrows; $iLinha++) {

        $oValorLinha = db_utils::fieldsMemory($rsLinha, $iLinha);
        $oDadosLinha = new stdClass();

        $oDadosLinha->codigo          = $oValorLinha->o69_codseq               ;
        $oDadosLinha->descricao       = urlencode($oValorLinha->o69_descr)     ;
        $oDadosLinha->nivel           = $oValorLinha->o69_nivel                ;
        $oDadosLinha->nivel_linha     = $oValorLinha->o69_nivellinha           ;
        $oDadosLinha->label           = urlencode($oValorLinha->o69_labelrel)  ;
        $oDadosLinha->ordem           = $oValorLinha->o69_ordem                ;
        $oDadosLinha->observacao      = urlencode($oValorLinha->o69_observacao);
        $oDadosLinha->origem          = $oValorLinha->o69_origem               ;
        $oDadosLinha->grupo           = $oValorLinha->o69_grupo                ;
        $oDadosLinha->grupo_exclusao  = $oValorLinha->o69_grupoexclusao        ;
        $oDadosLinha->desdobrar       = $oValorLinha->o69_desdobrarlinha == 't' ? 'true' : 'false' ;
        $oDadosLinha->digital_manual  = $oValorLinha->o69_manual         == 't' ? 'true' : 'false' ;
        $oDadosLinha->totalizadora    = $oValorLinha->o69_totalizador    == 't' ? 'true' : 'false' ;
        $oDadosLinha->verifica_ano    = $oValorLinha->o69_verificaano    == 't' ? 'true' : 'false' ;
        $oDadosLinha->libera_nivel    = $oValorLinha->o69_libnivel       == 't' ? 'true' : 'false' ;
        $oDadosLinha->libera_rec      = $oValorLinha->o69_librec         == 't' ? 'true' : 'false' ;
        $oDadosLinha->libera_sub_func = $oValorLinha->o69_libsubfunc     == 't' ? 'true' : 'false' ;
        $oDadosLinha->liberafunc      = $oValorLinha->o69_libfunc        == 't' ? 'true' : 'false' ;

        //propriedade com as colunas vinculadas à linha
        $oDadosLinha->aColunas       = $this->getColunasLinhaRelatorio($oValorLinha->o69_codseq)->colunas_relatorio;
        // filtros vinculados à linha
        $oDadosLinha->aFiltros       = $this->getFiltroPadrao($oValorLinha->o69_codseq);
        $aDadosLinhaRelatorio[]      = $oDadosLinha;
      }
    }
    return $aDadosLinhaRelatorio;
  }

  /**
   * funcao que irá gerar os dados a serem exportados
   * irá dar encode json num objeto e retornar o caminho salvo do arquivo.
   *
   * @return string caminho do arquivo exportado
   * @throws DBException
   */
  public function exportar(){

    $oOJson     = new services_json();
    $oRelatorio = new stdClass();

    $oRelatorio->codigo_relatorio    = $this->getDadosRelatorio()->o42_codparrel;
    $oRelatorio->descricao           = DBString::urlencode_all($this->getDadosRelatorio()->o42_descrrel);
    $oRelatorio->grupo_relatorio     = $this->getDadosRelatorio()->o42_orcparamrelgrupo;
    $oRelatorio->nota_padrao         = DBString::urlencode_all($this->getDadosRelatorio()->o42_notapadrao);
    $oRelatorio->periodos            = $this->getPeriodosRelatorio()->periodos;
    $oRelatorio->periodos_relatorio  = $this->getPeriodosRelatorio()->periodos_relatorio;
    $oRelatorio->colunas             = $this->getDadosColunas();
    $oRelatorio->linhas              = $this->getLinhasRelatorio();


    $sArquivoJSON    = "relatorioLegal_{$this->getCodigoRelatorio()}.json";
    $sCaminhoArquivo = "tmp/{$sArquivoJSON}";
    $fJson           = fopen($sCaminhoArquivo, "w");
    $sObjectJson     = $oOJson->encode($oRelatorio);

    if ( !fwrite($fJson, $sObjectJson) ) {

      throw new DBException( _M(self::CAMINHO_MENSAGENS . ('erro_gerar') ) );
    }
    chmod($sCaminhoArquivo, 0777);
    fclose($fJson);

    return $sCaminhoArquivo;
  }
}