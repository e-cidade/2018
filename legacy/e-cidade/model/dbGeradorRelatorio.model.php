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

class dbGeradorRelatorio {

  private $iCodRelatorio    = null;
  private $oXmlWriter       = null;
  private $aPropriedades    = array();
  private $sCabecalho       = "";
  private $sRodape          = "";
  public  $aVariaveis       = array();
  public  $aColunas         = array();
  public  $aSqlFrom         = array();
  public  $aConsulta        = array();
  public  $aFiltros         = array();
  public  $aOrdem           = array();
  private $aAgrupamento     = array();
  private $iOrigemRelatorio = null;
  private $sBuffer          = "";
  private $sBufferAgt       = "";

  /**
   * Estrutura do xml em string
   * @var string
   */
  private $sEstruturaXML;
  /**
   * Código do tipo do relatório...
   * -> 1-Relatório
   * -> 2-Documento Template
   * @var integer
   */
  private $iTipoRelatorio;
  const VERSAOXML           = "1.0";



  function __construct($iCodRelatorio="") {

    if(!empty($iCodRelatorio)){
      $this->loadRelatorio($iCodRelatorio);
    }

  }

  private function loadRelatorio($iCodRelatorio){

    if(empty($iCodRelatorio)){
      throw new Exception("Código do relatório vazio!");
    }

    $cldb_relatorio = new cl_db_relatorio();

    $rsConsultaRelatorio = $cldb_relatorio->sql_record($cldb_relatorio->sql_query($iCodRelatorio));

    if( $cldb_relatorio->numrows > 0 ){

      $oRelatorio           = db_utils::fieldsMemory($rsConsultaRelatorio,0);
      $this->sEstruturaXML  = $oRelatorio->db63_xmlestruturarel;
      $this->iTipoRelatorio = $oRelatorio->db63_db_tiporelatorio;

      $oDomXml = new DOMDocument();
      $oDomXml->loadXML($this->sEstruturaXML);


      try {
        $this->setCodRelatorio($oRelatorio->db63_sequencial);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }

      try {
        $this->setOrigemRelatorio($oRelatorio->db63_db_relatorioorigem);
      } catch (Exception $eException){
        throw new Exception($eException->getMessage());
      }


      $aPropriedades = $oDomXml->getElementsByTagName('Propriedades');

      foreach ($aPropriedades as $oXMLPropriedades) {

        $oPropriedades = new dbPropriedadeRelatorio();

        $oPropriedades->setVersao    (utf8_decode($oXMLPropriedades->getAttribute('versao')));
        $oPropriedades->setFormato   (utf8_decode($oXMLPropriedades->getAttribute('formato')));
        $oPropriedades->setLayout    (utf8_decode($oXMLPropriedades->getAttribute('layout')));
        $oPropriedades->setMargemDir (utf8_decode($oXMLPropriedades->getAttribute('margemdir')));
        $oPropriedades->setMargemEsq (utf8_decode($oXMLPropriedades->getAttribute('margemesq')));
        $oPropriedades->setMargemInf (utf8_decode($oXMLPropriedades->getAttribute('margeminf')));
        $oPropriedades->setMargemSup (utf8_decode($oXMLPropriedades->getAttribute('margemsup')));
        $oPropriedades->setNome      (utf8_decode($oXMLPropriedades->getAttribute('nome')));
        $oPropriedades->setOrientacao(utf8_decode($oXMLPropriedades->getAttribute('orientacao')));
        $oPropriedades->setTipoSaida (utf8_decode($oXMLPropriedades->getAttribute('tiposaida')));

        $this->addPropriedades($oPropriedades);

      }


      $aCabecalho = $oDomXml->getElementsByTagName("Cabecalho");
      if(!empty($aCabecalho)){
        foreach ($aCabecalho as $oXMLCabecalho) {
          if ($oXMLCabecalho->nodeValue) {
            $this->addCabecalho($oXMLCabecalho->nodeValue);
          }
        }
      }

    $aRodape = $oDomXml->getElementsByTagName("Rodape");
    if(!empty($aRodape)){
      foreach ($aRodape as $oXMLRodape) {
        if($oXMLRodape->nodeValue){
          $this->addRodape($oXMLRodape->nodeValue);
        }
      }
    }

    $aVariavel = $oDomXml->getElementsByTagName("Variavel");
    if (!empty($aVariavel)){
      foreach ( $aVariavel as $oXMLVariavel ){
        $oVariavel = new dbVariaveisRelatorio();
        $oVariavel->setNome    (utf8_decode($oXMLVariavel->getAttribute('nome')));
        $oVariavel->setLabel   (utf8_decode($oXMLVariavel->getAttribute('label')));
        $oVariavel->setValor   (utf8_decode($oXMLVariavel->getAttribute('valor')));
        $oVariavel->setTipoDado(utf8_decode($oXMLVariavel->getAttribute('tipodado')));
        $this->addVariavel(utf8_decode($oXMLVariavel->getAttribute('nome')),$oVariavel);
      }
    }

    $aConsulta = $oDomXml->getElementsByTagName("Consulta");

    foreach ( $aConsulta as $oXMLConsulta ){
      $aSelect = $oXMLConsulta->getElementsByTagName('Select');
      foreach ($aSelect as $oXMLCamposSelect ){
        $aCampoSelect = $oXMLCamposSelect->getElementsByTagName('Campo');
        foreach ( $aCampoSelect as $oXMLCampoSelect){
          $aCampos = $oDomXml->getElementsByTagName("Campos");
          foreach ($aCampos as $oXMLCampos) {
            $aCampo = $oXMLCampos->getElementsByTagName("Campo");
            foreach ($aCampo as $oXMLCampo){
              if ( $oXMLCampo->getAttribute('id') == $oXMLCampoSelect->getAttribute('id')){

                $oCampo = new dbColunaRelatorio();

                $oCampo->setId            ($oXMLCampo->getAttribute('id'));
                $oCampo->setNome          (utf8_decode($oXMLCampo->getAttribute('nome')));
                $oCampo->setAlias         (utf8_decode($oXMLCampo->getAttribute('alias')));
                $oCampo->setAlinhamento   (utf8_decode($oXMLCampo->getAttribute('alinhamento')));
                $oCampo->setAlinhamentoCab(utf8_decode($oXMLCampo->getAttribute('alinhamentocab')));
                $oCampo->setLargura       (utf8_decode($oXMLCampo->getAttribute('largura')));
                $oCampo->setMascara       (utf8_decode($oXMLCampo->getAttribute('mascara')));
                $oCampo->setTotalizar     (utf8_decode($oXMLCampo->getAttribute('totalizar')));
                if ( $oXMLCampo->hasAttribute('quebra') ) {
                  $oCampo->setQuebra($oXMLCampo->getAttribute('quebra'));
                } else {
                  $oCampo->setQuebra(false);
                }
                $this->addColuna($oCampo,$oXMLConsulta->getAttribute('tipo'));


              }
            }
          }
        }
      }

      $aWhere  = $oXMLConsulta->getElementsByTagName('Filtro');
      foreach ($aWhere as $oXMLWhere){
        $oFiltro = new dbFiltroRelatorio();
        $oFiltro->setOperador(utf8_decode($oXMLWhere->getAttribute('operador')));
        $oFiltro->setCampo   (utf8_decode($oXMLWhere->getAttribute('campo')));
        $oFiltro->setCondicao(utf8_decode($oXMLWhere->getAttribute('condicao')));
        $oFiltro->setValor   (utf8_decode($oXMLWhere->getAttribute('valor')));
        $this->addFiltro($oFiltro,$oXMLConsulta->getAttribute('tipo'));
      }

      $aGroup  = $oXMLConsulta->getElementsByTagName('Group');
      foreach ($aGroup as $oXMLGroup){
      }

      $aOrder  = $oXMLConsulta->getElementsByTagName('Ordem');
      foreach ($aOrder as $oXMLOrder){
        $oOrdem = new dbOrdemRelatorio();
        $oOrdem->setId     ($oXMLOrder->getAttribute('id'));
        $oOrdem->setNome   (utf8_decode($oXMLOrder->getAttribute('nome')));
        $oOrdem->setAscDesc(utf8_decode($oXMLOrder->getAttribute('ascdesc')));
        $oOrdem->setAlias  (utf8_decode($oXMLOrder->getAttribute('alias')));
        $this->addOrdem($oOrdem,$oXMLConsulta->getAttribute('tipo'));
      }

      $aFrom   = $oXMLConsulta->getElementsByTagName('From');
      foreach ($aFrom as $oXMLFrom){
        $this->addSqlFrom($oXMLFrom->nodeValue,$oXMLConsulta->getAttribute('tipo'));
      }

    }

    } else {
      throw new Exception("Nenhum relatório encontrado!");
    }


  }

  public function getDadosCampos( $sTipoRel="Principal" ){

    db_inicio_transacao();

    $aRetornaCampos = array();

    if ( $this->getOrigemRelatorio() == 1 ) {

      $sSqlView       = $this->getSqlFrom($sTipoRel);
      $aVariaveis     = $this->getVariaveis();

      if ( count($aVariaveis) > 0 ) {
        foreach ( $aVariaveis as $sNomeVariavel => $oVariavel ) {
          $sSqlView = str_replace($sNomeVariavel,"null",$sSqlView);
        }
      }

      $sNomeView      = "tmpgeradorrelatorioview".date('ymdis');
      $rsCriaTempView = db_query("create temp view {$sNomeView} as {$sSqlView};");

      if ( !$rsCriaTempView ) {
        throw new Exception("Erro na pesquisa! \n" . pg_last_error());
      }

    } else {
      $sNomeView      = $this->getSqlFrom($sTipoRel);
    }

    $sSqlConsultaCampos  = " select db_syscampo.codcam,                                                               ";
    $sSqlConsultaCampos .= "        db_syscampo.nomecam,                                                              ";
    $sSqlConsultaCampos .= "        db_syscampo.rotulorel as rotulo,                                                  ";
    $sSqlConsultaCampos .= "        db_syscampo.aceitatipo,                                                           ";
    $sSqlConsultaCampos .= "        db_syscampo.nulo,                                                                 ";
    $sSqlConsultaCampos .= "        db_syscampo.tamanho,                                                              ";
    $sSqlConsultaCampos .= "        db_syscampo.conteudo                                                              ";
    $sSqlConsultaCampos .= "   from pg_class                                                                          ";
    $sSqlConsultaCampos .= "        inner join pg_attribute on pg_attribute.attrelid   = pg_class.oid                 ";
    $sSqlConsultaCampos .= "        inner join db_syscampo  on trim(db_syscampo.nomecam) = trim(pg_attribute.attname) ";
    $sSqlConsultaCampos .= "  where pg_class.relkind = 'v'::\"char\"                                                  ";
    $sSqlConsultaCampos .= "    and pg_class.relname = '{$sNomeView}'                                                 ";
    $sSqlConsultaCampos .= " union all                                                                                ";
    $sSqlConsultaCampos .= " select null,                                                                             ";
    $sSqlConsultaCampos .= "        attname,                                                                          ";
    $sSqlConsultaCampos .= "        attname as rotulo,                                                                ";
    $sSqlConsultaCampos .= "        null,                                                                             ";
    $sSqlConsultaCampos .= "        null,                                                                             ";
    $sSqlConsultaCampos .= "        10,                                                                               ";
    $sSqlConsultaCampos .= "        null                                                                              ";
    $sSqlConsultaCampos .= "   from pg_class                                                                          ";
    $sSqlConsultaCampos .= "        inner join pg_attribute on pg_attribute.attrelid     = pg_class.oid               ";
    $sSqlConsultaCampos .= "        left  join db_syscampo  on trim(db_syscampo.nomecam) = trim(pg_attribute.attname) ";
    $sSqlConsultaCampos .= "  where db_syscampo.nomecam is null                                                       ";
    $sSqlConsultaCampos .= "    and pg_class.relkind = 'v'::\"char\"                                                  ";
    $sSqlConsultaCampos .= "    and pg_class.relname = '{$sNomeView}'                                                 ";

    $rsConsultaCampos  = db_query($sSqlConsultaCampos);


    if ( $rsConsultaCampos ) {

      $iNroLinhas = pg_num_rows($rsConsultaCampos);

      if ( $iNroLinhas > 0 ) {

        $sSqlMaxCodCam  = " select max(x.codcam) as max       ";
        $sSqlMaxCodCam .= "   from ($sSqlConsultaCampos) as x ";

        $rsMaxCodCam    = db_query($sSqlMaxCodCam);
        $oMaxCodCam     = db_utils::fieldsMemory($rsMaxCodCam,0);
        $iMaxCodCam     = $oMaxCodCam->max;

        for ( $iInd=0; $iInd < $iNroLinhas; $iInd++ ) {

          $oCampos = db_utils::fieldsMemory($rsConsultaCampos,$iInd);

          $oRetornoCampo = new stdClass();

          if ( trim($oCampos->codcam) == "") {
            $iId = ++$iMaxCodCam;
          } else {
            $iId = $oCampos->codcam;
          }

          if ($oCampos->conteudo == "float4" || $oCampos->conteudo == "float8" ) {
            $sAlinhamento = "r";
          } else if ( substr(trim($oCampos->conteudo),0,7) == "varchar"  ) {
            $sAlinhamento = "l";
          } else {
            $sAlinhamento = "c";
          }

          if ($oCampos->conteudo == "float4" || $oCampos->conteudo == "float8" ) {
            $sMascara = "m";
          } else if ( $oCampos->conteudo == "date" ) {
            $sMascara = "d";
          } else {
            $sMascara = "t";
          }

          $oRetornoCampo = new dbColunaRelatorio( $iId,
              $oCampos->nomecam,
              $oCampos->rotulo,
              $oCampos->tamanho*2,
              $sAlinhamento,
              "c",
              $sMascara,
              "n",
              false);
          $aRetornaCampos[] = $oRetornoCampo;

        }

      } else {
        throw new Exception("Campos não encontrados!");
      }
    } else {
      throw new Exception("Erro na Pesquisa: $sSqlConsultaCampos");
    }

    db_fim_transacao(true);

    return $aRetornaCampos;

  }

  public function setCodRelatorio($iCodRelatorio=null){

    if ( empty($iCodRelatorio) ) {
      throw new Exception("Código do relatório não informado");
    }

    $this->iCodRelatorio = $iCodRelatorio;

  }

  public function getCodRelatorio(){
    return $this->iCodRelatorio;
  }

  public function setOrigemRelatorio($iOrigem=null){

    if ( empty($iOrigem) ) {
      throw new Exception("Origem não informada");
    }

    $this->iOrigemRelatorio = $iOrigem;

  }

  public function getOrigemRelatorio(){
    return $this->iOrigemRelatorio;
  }

  public function addPropriedades(dbPropriedadeRelatorio $oPropriedade) {

    if (empty($oPropriedade)) {
      throw new Exception("Inclusão de propriedades abortada, valor nulo ou vazio.");
    }

    $this->aPropriedades = $oPropriedade;

  }

  public function getPropriedades(){
    return $this->aPropriedades;
  }

  public function addCabecalho($sValor) {

    if (empty($sValor)) {
      throw new Exception("Inclusão de cabeçalho abortada, valor nulo ou vazio.");
    }

    $this->sCabecalho = $sValor;

  }

  public function addRodape($sValor) {

    if (empty($sValor)) {
      throw new Exception("Inclusão de rodapé abortada, valor nulo ou vazio.");
    }

    $this->sRodape = $sValor;

  }

  public function addVariavel($sNome, dbVariaveisRelatorio $oVariavel) {

    if (empty($sNome)) {
      throw new Exception("Inclusão de variável abortada, nome nulo ou vazio.");
    }

    if (empty($oVariavel)) {
      throw new Exception("Inclusão de variável abortada, valor nulo ou vazio.");
    }

    $this->aVariaveis[$sNome] = $oVariavel;

  }

  public function getVariaveis($sNome=""){

    if (empty($sNome)) {
      return $this->aVariaveis;
    } else {
      return $this->aVariaveis[$sNome];
    }

  }

  public function addSqlFrom( $sSqlFrom="", $sTipoRel="Principal") {

    if ( trim($sSqlFrom) == "" ) {
      throw new Exception("Inclusão de consulta abortada, valor nulo ou vazio.");
    }

    $this->aSqlFrom[$sTipoRel] = $sSqlFrom;

  }

  public function getSqlFrom($sTipoRel=""){

    if (empty($sTipoRel)){
      return $this->aSqlFrom;
    } else {
      return $this->aSqlFrom[$sTipoRel];
    }

  }

  public function verificaVariaveisConsulta($sTipoRel=""){

    $aFrom = $this->getSqlFrom($sTipoRel);

    foreach ($aFrom as $sTipoRel => $sSql) {

      $aPalavrasFrom = split("[\n ]+",trim($sSql));

      foreach ($aPalavrasFrom as $iInd => $sValor) {
        $sPalavra = trim($sValor);
        if ( isset($sPalavra{0}) && $sPalavra{0} == '$' ){
          $oVariavel = new dbVariaveisRelatorio($sPalavra,"","","varchar");
          if (!isset($this->aVariaveis[$sPalavra])) {
            $this->addVariavel($sPalavra,$oVariavel);
          }
        }
      }

    }

  }

  public function addColuna( dbColunaRelatorio $oColuna, $sTipoRel="Principal") {

    if (empty($oColuna)) {
      throw new Exception("Inclusão de coluna abortada, valor nulo ou vazio.");
    }

    $this->aColunas[$sTipoRel][$oColuna->getNome()] = $oColuna;

  }

  public function getColunas($sNome="",$sTipoRel="Principal") {

    if (empty($sNome)) {

      if ( isset($this->aColunas[$sTipoRel]) ) {
        return $this->aColunas[$sTipoRel];
      } else {
        return null;
      }

    } else {
      return $this->aColunas[$sTipoRel][$sNome];
    }

  }

  public function converteColunaDocumento($aColunas){

   foreach ($aColunas as $sNome => $oColuna) {
    $oColuna->setAlias("");
    $this->addColuna($oColuna);
   }

  }

  public function addFiltro( dbFiltroRelatorio $oFiltro, $sTipoRel="Principal") {

    if (empty($oFiltro)) {
      throw new Exception("Inclusão de filtro abortada, valor nulo ou vazio.");
    }

    $this->aFiltros[$sTipoRel]["{$oFiltro->getCampo()}{$oFiltro->getCondicao()}{$oFiltro->getValor()}"] = $oFiltro;

  }

  public function getFiltros($sTipoRel=""){
    if (empty($sTipoRel)){
      return $this->aFiltros;
    } else {
      return $this->aFiltros[$sTipoRel];
    }
  }

  public function addOrdem(dbOrdemRelatorio $oOrdem, $sTipoRel="Principal") {

    if (empty($oOrdem)) {
      throw new Exception("Inclusão de ordem abortada, valor nulo ou vazio.");
    }

    $this->aOrdem[$sTipoRel][$oOrdem->getNome()] = $oOrdem;

  }

  public function getOrdem($sNome="",$sTipoRel="Principal") {

    if (empty($sNome)) {
      return $this->aOrdem;
    } else {
      return $this->aOrdem[$sTipoRel][$sNome];
    }

  }

  public function addAgrupamento(dbColunaRelatorio $oColuna, $sTipoRel="Principal") {

    if (empty($oColuna)) {
      throw new Exception("Inclusão de agrupamento abortada, valor nulo ou vazio.");
    }

    $this->aAgrupamento[$sTipoRel][] = $oColuna->getId();

  }

  public function addConsulta( $sTipoRel="Principal" ) {

    $aConsulta    = array();
    $aFiltro      = array();
    $aOrdem       = array();
    $aAgrupamento = array();

    if (empty($this->aColunas[$sTipoRel])) {
      throw new Exception("Inclusão de consulta abortada, nenhum coluna definida.");
    }

    if (empty($sTipoRel)) {
      throw new Exception("Inclusão abortada, valor nulo ou vazio.");
    }

    foreach ($this->aColunas as $sTipo => $aColunas) {
      foreach ($aColunas as $iIndice => $oColunas) {
        if ( $sTipo == $sTipoRel ) {
          $aConsulta[] = $oColunas->getId();
        }
      }
    }

    foreach ($this->aSqlFrom as $sTipo => $sFrom ) {
      if ( $sTipo == $sTipoRel ) {
        $sSqlFrom = $sFrom;
      }
    }

    foreach ($this->aFiltros as $sTipo => $aFiltros) {
      foreach ($aFiltros as $iIndice => $oFiltros) {
        if ( $sTipo == $sTipoRel ) {
          $aFiltro[] = $oFiltros;
        }
      }
    }

    foreach ($this->aOrdem as $sTipo => $aOrdens) {
      foreach ($aOrdens as $iIndice => $oOrdem) {
        if ( $sTipo == $sTipoRel ) {
          $aOrdem[] = $oOrdem;
        }
      }
    }


    foreach ($this->aAgrupamento as $sTipo => $aAgrupamentos) {
      foreach ($aAgrupamentos as $iIndice => $oAgrupamento) {
        if ( $sTipo == $sTipoRel ) {
          $aAgrupamento[] = $oAgrupamento;
        }
      }
    }


    $this->aConsulta[$sTipoRel]['Select'] = $aConsulta;
    $this->aConsulta[$sTipoRel]['From']   = $sSqlFrom;
    $this->aConsulta[$sTipoRel]['Where']  = $aFiltro;
    $this->aConsulta[$sTipoRel]['Group']  = $aAgrupamento;
    $this->aConsulta[$sTipoRel]['Order']  = $aOrdem;

  }

  public function getBuffer(){
    return $this->sBuffer;
  }

  public function getBufferAgt(){
    return $this->sBufferAgt;
  }

  public function buildXML() {

    if (empty($this->aPropriedades)) {
      throw new Exception("Construção do XML abortada, propriedades do relatório não definidas");
    }
    if (empty($this->aColunas)){
      throw new Exception("Construção do XML abortada, colunas do relatório não definidas");
    }
    if (empty($this->aConsulta)){
      throw new Exception("Construção do XML abortada, consulta não definida");
    }

    $this->sBuffer    = "";
    $this->oXmlWriter = new XMLWriter();
    $this->oXmlWriter->openMemory();
    $this->oXmlWriter->setIndent(true);
    $this->oXmlWriter->startDocument('1.0','ISO-8859-1');
    $this->oXmlWriter->endDtd();

    // Início XML
    $this->oXmlWriter->startElement('Relatorio');

    // Versão DBRelatório
    $this->oXmlWriter->writeElement("Versao",self::VERSAOXML);

    // Propriedades do Relatório
    $this->aPropriedades->toXml($this->oXmlWriter);

    // Cabeçalho do Relatório
    $this->oXmlWriter->writeElement("Cabecalho",$this->sCabecalho);

    // Rodapé do Relatório
    $this->oXmlWriter->writeElement("Rodape",$this->sRodape);

    // Monta Variáveis
    if (!empty($this->aVariaveis)) {

      $this->oXmlWriter->startElement('Variaveis');

      foreach ($this->aVariaveis as $sNomeVariavel => $oVariavel){
        $oVariavel->toXml($this->oXmlWriter);
      }

      $this->oXmlWriter->endElement();//Variaveis

    }


    // Monta Campos
    $this->oXmlWriter->startElement('Campos');

    foreach ($this->aColunas as $sTipo => $aColunas){
      foreach ($aColunas as $iIndice => $oColunas) {
        $oColunas->toXml($this->oXmlWriter);
      }
    }

    $this->oXmlWriter->endElement();//Campos


    // Monta Consultas (Query)
    $this->oXmlWriter->startElement('Consultas');

    foreach ( $this->aConsulta as $sTipo => $aConsulta ){

      $this->oXmlWriter->startElement('Consulta');
      $this->oXmlWriter->writeAttribute("tipo",$sTipo);

      foreach ($aConsulta as $sTagQuery => $aValores){

        switch ($sTagQuery){

          case "Select":

            $this->oXmlWriter->startElement('Select');

            foreach ( $aValores as $iIndice => $oConsulta) {
              $this->oXmlWriter->startElement('Campo');
              $this->oXmlWriter->writeAttribute("id",$oConsulta);
              $this->oXmlWriter->endElement();
            }

            $this->oXmlWriter->endElement();

          break;

          case "From":

            if (is_string($aValores) && !db_utils::isUTF8($aValores)) {
              $aValores = utf8_encode($aValores);
            }
            $this->oXmlWriter->writeElement("From",$aValores);
          break;

          case "Where":

            if(!empty($aValores)){

              $this->oXmlWriter->startElement('Where');

              foreach ( $aValores as $iIndice => $oFiltro ) {
                $oFiltro->toXml($this->oXmlWriter);
              }

              $this->oXmlWriter->endElement();

            } else {
              $this->oXmlWriter->writeElement("Where");
            }

          break;

          case "Order":

            if(!empty($aValores)){

              $this->oXmlWriter->startElement('Order');
              foreach ( $aValores as $iIndice => $oOrdem ) {
                $oOrdem->toXml($this->oXmlWriter);
              }

              $this->oXmlWriter->endElement();

            } else {
              $this->oXmlWriter->writeElement("Order","");
            }

          break;

          case "Group":

            if(!empty($aValores)){

              $this->oXmlWriter->startElement('Group');

              foreach ( $aValores as $iIndice => $oAgrupamento ) {
                $this->oXmlWriter->startElement('Campo');
                $this->oXmlWriter->writeAttribute("id",$oAgrupamento);
                $this->oXmlWriter->endElement();
              }

              $this->oXmlWriter->endElement();

            } else {
              $this->oXmlWriter->writeElement("Group","");
            }

          break;

        }
      }

      $this->oXmlWriter->endElement();//Consulta

    }

    $this->oXmlWriter->endElement();//Consultas
    $this->oXmlWriter->endElement();//Relatorio

    // Fim XML
    $this->sBuffer .= $this->oXmlWriter->outputMemory();

  }

  public function converteAgt($sXml) {

    if (empty($sXml)){
      throw new Exception("Conversão para AGT abortada, nenhum xml encontrado!");
    }


    $oXmlWriter = new XMLWriter();
    $oDomXml  = new DOMDocument();

    $oDomXml->loadXML($sXml);

    $aPropriedades = $oDomXml->getElementsByTagName('Propriedades');
    $aCabecalho    = $oDomXml->getElementsByTagName("Cabecalho");
    $aRodape       = $oDomXml->getElementsByTagName("Rodape");
    $aVariavel     = $oDomXml->getElementsByTagName("Variavel");
    $aConsulta     = $oDomXml->getElementsByTagName("Consulta");
    $aCampos       = $oDomXml->getElementsByTagName("Campos");

    foreach ($aCampos as $oCampos){
      $aCampo = $oCampos->getElementsByTagName("Campo");
    }

    $oElementoPropriedades = $aPropriedades->item(0);

    if ( !$oElementoPropriedades->hasAttribute("tiposaida") ) {
      $lEscreveCabecalho = true;
    } else {
      $lEscreveCabecalho = $oElementoPropriedades->getAttribute('tiposaida') == "pdf";
    }

    $oXmlWriter->openMemory();
    $oXmlWriter->setIndent(true);
    $oXmlWriter->startDocument('1.0');
    $oXmlWriter->endDtd();

    $oXmlWriter->startElement("Report");

    foreach ($aPropriedades as $oPropriedades) {

      if ($oPropriedades->getAttribute('orientacao') == "landscape"){
        $sCabecalho = '#sety020
            #image $db_logo
            #sety020
            #setfaw10
            #setspace012
            #tab80 $db_nomeinst
            #setfai10
            #tab80 $db_enderinst
            #tab80 $db_municinst - $db_ufinst
            #tab80 $db_foneinst
            #tab80 $db_emailinst
            #tab80 $db_siteinst
            #sety100
            #lineH790
            #sety020
            #tab600#rect*000*000*200*080*1*#e7e7e7*#000000
            #setfan07
            #sety023
            #setspace009
            #tab610#frameNL190$head1
            #setspace012
            #sety038
            #tab610$head2
            #sety045
            #tab610$head3
            #sety055
            #tab610$head4
            #sety065
            #tab610$head5
            #sety075
            #tab610$head6
            #sety085
            #tab610$head7
            #sety095';

        $sRodape    = '#lineH800
            #setfan06
            Base: $db_base #tab220 Emissor: $db_login  Exercício: $db_anousu  Data: $db_datausu  Hora: $db_horausu  #tab735 Página: $page de {nb} ';
      } else {

        $sCabecalho = '#sety010
            #image $db_logo
            #sety010
            #setfaw10
            #setspace012#sety010
            #tab80 $db_nomeinst
            #setfai10
            #tab80 $db_enderinst
            #tab80 $db_municinst - $db_ufinst
            #tab80 $db_foneinst
            #tab80 $db_emailinst
            #tab80 $db_siteinst
            #sety90
            #lineH500
            #sety010
            #tab355#rect*000*000*200*080*1*#e7e7e7*#000000
            #setfan07
            #sety020
            #setspace009
            #tab365#frameNL190$head1
            #setspace012
            #sety033
            #tab365$head2
            #sety040
            #tab365$head3
            #sety050
            #tab365$head4
            #sety060
            #tab365$head5
            #sety070
            #tab365$head6
            #sety080
            #tab365$head7';

        $sRodape    = '#lineH550
            #setfan06
            Base: $db_base   Emissor: $db_login Exercício: $db_anousu Data: $db_datausu Hora: $db_horausu #tab490 Página: $page de {nb}';

      }

      if ( !$lEscreveCabecalho ) {
        $sCabecalho = '';
        $sRodape    = '';
      }


      //Version
      $oXmlWriter->writeElement("Version",$oPropriedades->getAttribute('versao'));
      //Properties
      $oXmlWriter->startElement("Properties");
      $oXmlWriter->writeElement("Description","");
      $oXmlWriter->writeElement("Title",utf8_decode($oPropriedades->getAttribute('nome')));
      $oXmlWriter->writeElement("Author","");
      $oXmlWriter->writeElement("Keywords","");
      $oXmlWriter->writeElement("Date","");
      $oXmlWriter->writeElement("FrameSize","");
      $oXmlWriter->writeElement("Layout",utf8_decode($oPropriedades->getAttribute('layout')));
      $oXmlWriter->writeElement("UseTemplates","");
      $oXmlWriter->endElement();//Properties
    }


    foreach ($aCabecalho as $oCabecalho){
      $oXmlWriter->startElement("Header");
      $oXmlWriter->writeElement("Body",$sCabecalho);
      $oXmlWriter->writeElement("Align","center");
      $oXmlWriter->endElement();//Header
    }

    foreach ($aRodape as $oRodape){
      $oXmlWriter->startElement("Footer");
      $oXmlWriter->writeElement("Body",$sRodape);
      $oXmlWriter->writeElement("Align","center");
      $oXmlWriter->endElement();//Footer
    }


    if(!empty($aVariavel)){
      $oXmlWriter->startElement("Parameters");
      foreach ($aVariavel as $oVariavel){
        $oXmlWriter->startElement(str_replace("$","",utf8_decode($oVariavel->getAttribute('nome'))));
        $oXmlWriter->writeElement("mask","");
        $oXmlWriter->writeElement("value","");
        $oXmlWriter->writeElement("source","");
        $oXmlWriter->writeElement("label","");
        $oXmlWriter->endElement();//TagNomeVariavel
      }
      $oXmlWriter->endElement();//Parameters
    }

    $aQuebra = array();
    $sOrder  = array();

    foreach ($aConsulta as $oConsulta) {

    if ($oConsulta->getAttribute('tipo') == "Principal"){

      $aSelect = $oConsulta->getElementsByTagName('Select');
      $aFrom   = $oConsulta->getElementsByTagName('From');
      $aWhere  = $oConsulta->getElementsByTagName('Where');
      $aGroup  = $oConsulta->getElementsByTagName('Group');
      $aOrder  = $oConsulta->getElementsByTagName('Order');

      foreach ($aFrom as $oFrom){
        if ($this->getOrigemRelatorio() == 1 ) {
          $sNomeTmpTabela = "tmpgeradorrelatorio".date('ymdis');
          $sFrom  = "({$oFrom->nodeValue}) as {$sNomeTmpTabela}";
        } else {
          $sFrom  = $oFrom->nodeValue;
        }
      }

      $aTotalizador = array();
      $iIndiceCampo = 1;

      foreach ($aCampo as $oCampo){

        $iIdCampo    = $oCampo->getAttribute('id');
        $sNomeCampo  = $oCampo->getAttribute('nome');
        $sAliasCampo = $oCampo->getAttribute('alias');

        if ( $oCampo->getAttribute('totalizar') != "n" ) {

          if ($oCampo->getAttribute('totalizar') == "s") {
            $aTotalizador[] = "sum({$iIndiceCampo})";
          } else if ($oCampo->getAttribute('totalizar') == "q") {
            $aTotalizador[] = "count({$iIndiceCampo})";
          }

        }

        if ( $oCampo->getAttribute('quebra') == "true") {
          $aQuebra[] = $iIndiceCampo;
        }

        foreach ($aSelect as $oSelect){
          $aCampoSelect = $oSelect->getElementsByTagName('Campo');
          foreach ($aCampoSelect as $oCampoSelect){
            if ( $oCampoSelect->getAttribute('id') == $iIdCampo ) {
              $aFields[] = $oCampo;
              $sSelect[] = "\"{$sNomeCampo}\"".($sAliasCampo!=""?' as "'.$sAliasCampo.'" ':'');
            }
          }
        }

        $sGroup = array();
        foreach ($aGroup as $oGroup){

          $aCampoGroup = $oGroup->getElementsByTagName('Campo');
          foreach ($aCampoGroup as $oCampoGroup){
            if ( $oCampoGroup->getAttribute('id') == $iIdCampo ) {
              $sGroup[] = $sNomeCampo;
            }
          }
        }


        foreach ($aOrder as $oOrder){
          $aCampoOrdem = $oOrder->getElementsByTagName('Ordem');
          foreach ($aCampoOrdem as $oCampoOrdem){
            if ( $oCampoOrdem->getAttribute('id') == $iIdCampo ) {
              $sNomeCampo  = $oCampoOrdem->getAttribute('nome');
              $sAscDesc    = $oCampoOrdem->getAttribute('ascdesc');
              $sOrder[]    = "\"{$sNomeCampo}\" {$sAscDesc}";
            }
          }
        }

        $iIndiceCampo++;
      }


      $sWhere = array();
      foreach ($aWhere as $oWhere){
        $aCampoFiltro = $oWhere->getElementsByTagName('Filtro');
        foreach ($aCampoFiltro as $oCampoFiltro){
          $sOperador  = $oCampoFiltro->getAttribute('operador');
          $sNomeCampo = $oCampoFiltro->getAttribute('campo');
          $sCondicao  = $oCampoFiltro->getAttribute('condicao');
          $sValor     = $oCampoFiltro->getAttribute('valor');

          // Verifica primeira posição do where retirando o operador
          if(empty($sWhere)){
            $sOperador = "";
          }

          if ( trim($sValor)!= "" && is_string($sValor) && $sValor{0} != "$" ){
            $sWhere[]  = $sOperador." \"".$sNomeCampo."\" ".$sCondicao." '".$sValor."' ";
          } else if (trim($sCondicao) == "in") {
            $sWhere[]  = $sOperador." \"".$sNomeCampo."\" ".$sCondicao." (".$sValor.") ";
          } else {
            $sWhere[]  = $sOperador." \"".$sNomeCampo."\" ".$sCondicao." ".$sValor." ";
          }
        }
      }


      $oXmlWriter->startElement("DataSet");

      $oXmlWriter->startElement("DataSource");
      $oXmlWriter->writeElement("Name","");
      $oXmlWriter->writeElement("Remote","");
      $oXmlWriter->endElement();//DataSource

      $oXmlWriter->writeElement("PreQuery","");
      $oXmlWriter->writeElement("PosQuery","");

      $oXmlWriter->startElement("Query");

      $oXmlWriter->writeElement("Select" ,utf8_decode(implode(",",$sSelect)));
      $oXmlWriter->writeElement("From"   ,utf8_decode($sFrom));
      $oXmlWriter->writeElement("Where"  ,utf8_decode(implode(" ",$sWhere)));
      $oXmlWriter->writeElement("GroupBy",utf8_decode(implode(",",$sGroup)));
      $oXmlWriter->writeElement("OrderBy",utf8_decode(implode(",",$sOrder)));

      $oXmlWriter->startElement("Config");
      $oXmlWriter->writeElement("Distinct","0");
      $oXmlWriter->writeElement("OffSet","0");
      $oXmlWriter->writeElement("Limit","0");
      $oXmlWriter->endElement();//Config
      $oXmlWriter->endElement();//Query


      $oXmlWriter->startElement("Groups");
      $oXmlWriter->startElement("Config");
      $oXmlWriter->writeElement("ShowGroup","");
      $oXmlWriter->writeElement("ShowDetail","1");
      $oXmlWriter->writeElement("ShowLabel","");
      $oXmlWriter->writeElement("ShowNumber","1");
      $oXmlWriter->writeElement("ShowIndent","1");
      $oXmlWriter->writeElement("ShowHeader","");
      $oXmlWriter->endElement();//Config

      $oXmlWriter->startElement("Formulas");
      if(!empty($aTotalizador)){
        $oXmlWriter->writeElement("Group0",implode(",",$aTotalizador));
      }

      if (!empty($aQuebra)) {
        foreach ( $aQuebra as $iIndQuebra => $iQuebra ){
          $oXmlWriter->writeElement("Group{$iQuebra}","");
        }
      }

      $oXmlWriter->endElement();//Formulas
      $oXmlWriter->endElement();//Groups


      $oXmlWriter->startElement("Fields");
      foreach ($aFields as $iInd =>$oFields){
        $oXmlWriter->startElement("Column".($iInd+1));
        $oXmlWriter->writeElement("Chars" ,($oFields->getAttribute('largura')/2));
        $oXmlWriter->writeElement("Points",$oFields->getAttribute('largura'));
        switch ($oFields->getAttribute('alinhamento')){
          case "c":
            $sAlign = "center";
            break;
          case "l":
            $sAlign = "left";
            break;
          case "r":
            $sAlign = "right";
            break;
        }

        $oXmlWriter->writeElement("Align",$sAlign);
        switch ($oFields->getAttribute('alinhamentocab')){
          case "c":
            $sAlignCab = "center";
            break;
          case "l":
            $sAlignCab = "left";
            break;
          case "r":
            $sAlignCab = "right";
            break;
        }

        $oXmlWriter->writeElement("HeadAlign",$sAlignCab);

        $sMascara = "";
        $sFuncao  = "";

        switch ($oFields->getAttribute('mascara')) {
          case "m":
            $sMascara = "#  -9.999,99s";
            break;
          case "d":
            $sFuncao = "/dbseller/a_formata_data.fun";
            break;
        }

        $oXmlWriter->writeElement("Mask",$sMascara);
        $oXmlWriter->writeElement("Function",$sFuncao);
        $oXmlWriter->writeElement("Cross","");
        $oXmlWriter->writeElement("Conditional","");
        $oXmlWriter->writeElement("Hidden","");
        $oXmlWriter->endElement();//Column
      }
      $oXmlWriter->endElement();//Fields
      $oXmlWriter->endElement();//DataSet
    }
    }


    foreach ($aPropriedades as $oPropriedades) {
      $oXmlWriter->startElement("PageSetup");
      $oXmlWriter->writeElement("Format",$oPropriedades->getAttribute('formato'));
      $oXmlWriter->writeElement("Orientation",$oPropriedades->getAttribute('orientacao'));
      $oXmlWriter->writeElement("LeftMargin",$oPropriedades->getAttribute('margemesq'));
      $oXmlWriter->writeElement("RightMargin",$oPropriedades->getAttribute('margemdir'));
      $oXmlWriter->writeElement("TopMargin",$oPropriedades->getAttribute('margemsup'));
      $oXmlWriter->writeElement("BottonMargin",$oPropriedades->getAttribute('margeminf'));
      $oXmlWriter->writeElement("LineSpace","");
      $oXmlWriter->endElement();//PageSetup
    }

    $oXmlWriter->startElement("Graph");

    $oXmlWriter->writeElement("Title","");
    $oXmlWriter->writeElement("TitleX","");
    $oXmlWriter->writeElement("TitleY","");
    $oXmlWriter->writeElement("With","");
    $oXmlWriter->writeElement("Height","");
    $oXmlWriter->writeElement("Description","");
    $oXmlWriter->writeElement("ShowData","");
    $oXmlWriter->writeElement("ShowValues","");
    $oXmlWriter->writeElement("Orientation","");
    $oXmlWriter->writeElement("PlottedColumns","");
    $oXmlWriter->writeElement("Legend","");
    $oXmlWriter->endElement();//Graph



    $oXmlWriter->startElement("Merge");

    $oXmlWriter->writeElement("ReportHeader","");

    $oXmlWriter->startElement("Details");
    $oXmlWriter->startElement("Detail1");

    $oXmlWriter->writeElement("GroupHeader","");
    $oXmlWriter->writeElement("Body","");

    $oXmlWriter->startElement("DataSet");
    $oXmlWriter->startElement("Query");

    $oXmlWriter->writeElement("Select","");
    $oXmlWriter->writeElement("From","");
    $oXmlWriter->writeElement("Where","");
    $oXmlWriter->writeElement("GroupBy","");
    $oXmlWriter->writeElement("OrderBy","");

    $oXmlWriter->startElement("Config");
    $oXmlWriter->writeElement("Distinct","1");
    $oXmlWriter->writeElement("OffSet","0");
    $oXmlWriter->writeElement("Limit","0");
    $oXmlWriter->endElement();//Config

    $oXmlWriter->endElement();//Query

    $oXmlWriter->writeElement("Fields","");

    $oXmlWriter->endElement();//DataSet

    $oXmlWriter->writeElement("GroupFooter","");

    $oXmlWriter->endElement();//Detail1
    $oXmlWriter->endElement();//Details

    $oXmlWriter->writeElement("ReportFooter","");
    $oXmlWriter->writeElement("PageSetup","");

    $oXmlWriter->startElement("Config");
    $oXmlWriter->writeElement("RepeatHeader","");
    $oXmlWriter->writeElement("ShowFooter","");
    $oXmlWriter->endElement();//Config

    $oXmlWriter->endElement();//Merge


    $oXmlWriter->startElement("Label");
    $oXmlWriter->writeElement("Body","");
    $oXmlWriter->startElement("Config");
    $oXmlWriter->writeElement("HorizontalSpacing","15");
    $oXmlWriter->writeElement("VerticalSpacing","0");
    $oXmlWriter->writeElement("LabelWidth","288");
    $oXmlWriter->writeElement("LabelHeight","72");
    $oXmlWriter->writeElement("LeftMargin","11");
    $oXmlWriter->writeElement("TopMargin","36");
    $oXmlWriter->writeElement("Columns","2");
    $oXmlWriter->writeElement("Rows","10");
    $oXmlWriter->writeElement("PageFormat","A3");
    $oXmlWriter->writeElement("LineSpacing","14");
    $oXmlWriter->endElement();//Config
    $oXmlWriter->endElement();//Label



    $oXmlWriter->startElement("OpenOffice");
    $oXmlWriter->writeElement("Source","");
    $oXmlWriter->startElement("Config");
    $oXmlWriter->writeElement("FixedDetails","1");
    $oXmlWriter->writeElement("ExpandDetails","");
    $oXmlWriter->writeElement("printEmptyDetail","1");
    $oXmlWriter->writeElement("SumByTotal","1");
    $oXmlWriter->writeElement("RepeatHeader","1");
    $oXmlWriter->writeElement("RepeatFooter","1");
    $oXmlWriter->endElement();//Config
    $oXmlWriter->endElement();//OpenOffice

    $oXmlWriter->endElement();//Report

    $this->sBufferAgt = $oXmlWriter->outputMemory();

  }

  public function geraArquivoAgt($DBXml="", $lGerarNomeUnico = false){

    $lErro = false;

    if (empty($DBXml)) {

      // Cria DBXML
      try {
        $this->buildXML();
      } catch (Exception $e){
        $lErro  = true;
        $sMsgErro = $e->getMessage();
      }

      if($lErro){
        throw new Exception($sMsgErro);
      }

      $DBXml = $this->getBuffer();

    }


    // Cria AGT
    try {
      $this->converteAgt($DBXml);
    } catch (Exception $e){
      $lErro  = true;
      $sMsgErro = $e->getMessage();
    }

    if($lErro){
      throw new Exception($sMsgErro);
    }

    $sArquivoAgt = "geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".agt";

    if ( $lGerarNomeUnico ) {
      $sArquivoAgt = "geraRelatorio".uniqid().date("YmdHis").db_getsession("DB_id_usuario").".agt";      
    }

    $sCaminhoRelatorio = "tmp/".$sArquivoAgt;
    $rsRelatorioTemp   = fopen($sCaminhoRelatorio,"w");

    fputs($rsRelatorioTemp ,$this->sBufferAgt);
    fclose($rsRelatorioTemp );

    return $sCaminhoRelatorio;

  }

  public function exportar() {

    if (empty($this->iCodRelatorio)) {
      throw new ParameterException('Código do relatório não informado.');
    }

    $oDaoDb_Relatorio = new cl_db_relatorio();
    $sSqlDb_Relatorio = $oDaoDb_Relatorio->sql_query_file($this->getCodRelatorio());
    $rsDb_Relatorio   = $oDaoDb_Relatorio->sql_record($sSqlDb_Relatorio);

    if ($oDaoDb_Relatorio->numrows == 0) {
      throw new DBException('Nenhum registro encontrado.');
    }

    $oDb_Relatorio           = db_utils::fieldsMemory($rsDb_Relatorio, 0);

    $iCodigo                 = $oDb_Relatorio->db63_sequencial;
    $iCodigoGrupo            = $oDb_Relatorio->db63_db_gruporelatorio;
    $iCodigoTipoRelatorio    = $oDb_Relatorio->db63_db_tiporelatorio;
    $sNomeRelatorio          = urlencode($oDb_Relatorio->db63_nomerelatorio);
    $sVersaoXML              = $oDb_Relatorio->db63_versao_xml;
    $dDataCriacao            = $oDb_Relatorio->db63_data;
    $sEstruturaXML           = base64_encode($oDb_Relatorio->db63_xmlestruturarel);
    $iCodigoOrigem           = $oDb_Relatorio->db63_db_relatorioorigem;

    $oXMLRelatorioExportacao = new DOMDocument('1.0', 'ISO-8859-1');

    $oRelatorio              = $oXMLRelatorioExportacao->createElement('relatorio');

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'codigo_grupo');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($iCodigoGrupo));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'codigo_tipo_relatorio');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($iCodigoTipoRelatorio));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'nome_relatorio');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($sNomeRelatorio));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'versao_xml');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($sVersaoXML));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'data_criacao');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($dDataCriacao));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'estrutura_xml');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($sEstruturaXML));
    $oRelatorio->appendChild($oCampo);

    $oCampo                  = $oXMLRelatorioExportacao->createElement('campo');
    $oCampo->setAttribute('id', 'codigo_origem');
    $oCampo->appendChild($oXMLRelatorioExportacao->createTextNode($iCodigoOrigem));
    $oRelatorio->appendChild($oCampo);

    $oXMLRelatorioExportacao->appendChild($oRelatorio);

    $oXMLRelatorioExportacao->formatOutput = true;

    $sNomeArquivoXML = "/tmp/relatorio{$this->getCodRelatorio()}".date('YmdHis').".xml";

    if (!$oXMLRelatorioExportacao->save($sNomeArquivoXML)) {
      return false;
    }

    return $sNomeArquivoXML;

  }

  /**
   * Importa um relatorio para o dbportal apartir de um DOMDocument
   * @param DOMDocument $oDomDocument
   * @param integer $iGrupoRelatorio
   * @param integer $iTipoRelatorio
   * @throws DBException
   */
  public static function importar(DOMDocument $oDomDocument, $iGrupoRelatorio, $iTipoRelatorio) {

    if (empty($iGrupoRelatorio)) {
      throw new ParameterException(_M('configuracao.configuracao.dbGeradorRelatorio.erro_codigo_grupo_relatorio'));
    }

    if (empty($iTipoRelatorio)) {
      throw new ParameterException(_M('configuracao.configuracao.dbGeradorRelatorio.erro_codigo_tipo_relatorio'));
    }

    $oRelatorio       = $oDomDocument->getElementsByTagName('relatorio')->item(0);

    try {
      self::validarXml($oRelatorio);
    } catch (Exception $oException) {
      throw new BusinessException( $oException->getMessage());
    }

    $oDaoDb_relatorio = db_utils::getDao('db_relatorio');

    $aCampos = array();

    foreach ($oRelatorio->getElementsByTagName('campo') as $oCampo) {
      $aCampos[$oCampo->getAttribute('id')] = $oCampo->nodeValue;
    }

    $oDaoDb_relatorio->db63_db_gruporelatorio  = $iGrupoRelatorio;
    $oDaoDb_relatorio->db63_db_tiporelatorio   = $iTipoRelatorio;
    $oDaoDb_relatorio->db63_nomerelatorio      = urldecode($aCampos['nome_relatorio']);
    $oDaoDb_relatorio->db63_versao_xml         = $aCampos['versao_xml'];
    $oDaoDb_relatorio->db63_data               = $aCampos['data_criacao'];
    $oDaoDb_relatorio->db63_xmlestruturarel    = pg_escape_string(base64_decode($aCampos['estrutura_xml']));

    $oDaoDb_relatorio->db63_db_relatorioorigem = $aCampos['codigo_origem'];
    $oDaoDb_relatorio->incluir(null);

    if ($oDaoDb_relatorio->erro_status == '0') {
      throw new DBException( $oDaoDb_relatorio->erro_msg);
    }

    return true;

  }

  private static function validarXml(DOMElement $oDomElementoRelatorio) {

    $aCamposRelatorio['codigo_grupo']          = 'codigo_grupo';
    $aCamposRelatorio['codigo_tipo_relatorio'] = 'codigo_tipo_relatorio';
    $aCamposRelatorio['nome_relatorio']        = 'nome_relatorio';
    $aCamposRelatorio['versao_xml']            = 'versao_xml';
    $aCamposRelatorio['data_criacao']          = 'data_criacao';
    $aCamposRelatorio['estrutura_xml']         = 'estrutura_xml';
    $aCamposRelatorio['codigo_origem']         = 'codigo_origem';

    $aCamposXML = $oDomElementoRelatorio->getElementsByTagName('campo');

    foreach ($aCamposXML as $oCampo) {

      if (!$oCampo->hasAttribute('id')) {
        throw new FileException(_M('configuracao.configuracao.dbGeradorRelatorio.erro_xml_node_sem_atributo_id'));
      }

      if (!in_array($oCampo->getAttribute('id'), $aCamposRelatorio)) {
        throw new FileException(_M('configuracao.configuracao.dbGeradorRelatorio.erro_xml_node_inexistente'));
      }

      unset($aCamposRelatorio[$oCampo->getAttribute('id')]);

    }

    if (count($aCamposRelatorio) <> 0) {
      throw new FileException(_M('configuracao.configuracao.dbGeradorRelatorio.erro_xml_node_inexistente'));
    }

    return true;

  }

  /**
   * Gera o relatório conforme tipo informado
   *
   * @param  string $sNome       Nome do arquivo
   * @param  array  $aParametros array com os dados dos filtros. Ex.:
   *                             aParametros   : [{"sNome":"$aluno","sValor":"123"}]
   * @return string              o caminho para o arquivo informado
   */
  public function gerarRelatorio( $sNomeArquivo = null, $aParametros = array()) {

    ini_set("error_reporting","E_ALL & ~NOTICE");

    $sCaminhoAgt  = $this->geraArquivoAgt($this->sEstruturaXML, true);

    $oAgata       = new cl_dbagata();
    $oAgataApi    = $oAgata->api;
    $oAgataApi->setReportPath($sCaminhoAgt);

    $oPropriedades = $this->getPropriedades();
    $oAgataApi->setParameter('$head1', $oPropriedades->getNome());

    $sFormatoSaida = $oPropriedades->getTipoSaida();
    if (!$sFormatoSaida) {
      $sFormatoSaida = "pdf";
    }

    // informa para a API do agata o ordem by
    $aOrdem = $this->getOrdem();
    if (!empty($aOrdem)) {

      $aNomeOrdem = array();

      foreach ($aOrdem as $iInd1 => $aOrdem2){

        foreach ($aOrdem2 as $iInd2 => $oOrdem ){
          $aNomeOrdem[] = $oOrdem->getAlias();
        }
      }

      if (!empty($aNomeOrdem)) {

        $sNomeOrdem = implode(", ",$aNomeOrdem);
        $iLinha     = 2;

        for($iIni=0; $iIni < strlen($sNomeOrdem); $iIni++ ){

          $iFim = 52;

          if ($iLinha == 2) {
            $sPrefix = "Ordem: ";
            $iFim   -= 8;
          } else {
            $sPrefix = "";
          }

          $oAgataApi->setParameter('$head'.$iLinha, $sPrefix.(substr($sNomeOrdem,$iIni,$iFim)));
          $iLinha++;
          $iIni += $iFim;

          if ($iLinha == 7) {
            break;
          }
        }
      }
    }

    // informa para a API do agata o dados do where
    if (count($aParametros) > 0) {

      $aVariaveisGerador = $this->getVariaveis();

      foreach ( $aVariaveisGerador as $sIndVar => $oVariavelGerador) {

        foreach ( $aParametros as $iInd => $oParamentro) {

          if ( $oParamentro->sNome == $oVariavelGerador->getNome()) {

            if ( $oVariavelGerador->getTipoDado() == 'date') {
              $sValor = implode('-',array_reverse(explode('/',$oParamentro->sValor)));
            } else {
              $sValor = $oParamentro->sValor;
            }
            $oAgataApi->setParameter($oParamentro->sNome, utf8_decode($sValor));
          }
        }
      }
    }

    $sNomeRelatorio = "";
    // Verifica o tipo de relatório 1-Relatório,  2-Documento Template e utiliza o método da API do Agata referente ao tipo
    if ( $this->iTipoRelatorio == 2 ) {
      /**
       * @todo tem um modelo de implementação desse bloco de código no arquivo sys4_processarelatorioRPC.php
       */
      throw new Exception("Não foi implementado geração de relatório utilizando documento template.");
    } else {

      $oAgataApi->setFormat($sFormatoSaida);

      $sNomeRelatorio = "tmp/geraRelatorio".date("YmdHis").db_getsession("DB_id_usuario").".".$sFormatoSaida;
      if ( !empty($sNomeArquivo) ) {
        $sNomeRelatorio = "tmp/{$sNomeArquivo}.{$sFormatoSaida}";
      }

      $oAgataApi->setOutputPath($sNomeRelatorio);

      ob_start();
      $lGerou = $oAgataApi->generateReport();
      if ( !$lGerou ) {

        ob_end_clean();
        throw new Exception($oAgataApi->getError());
      } else {

        ob_end_clean();
        if ($oAgataApi->getRowNum() == 0) {
          throw new Exception("Nenhum registro encontrado!");
        }
      }
    }
    return $sNomeRelatorio;
  }

}
