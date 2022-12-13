<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


class GeracaoArquivoPitITBIUrbano {

	/**
	 * Caminho do JSON com as mensagens
	 */
	const MENSAGENS = 'arrecadacao.cadastro.GeracaoArquivoPitITBIUrbano.';

  private $iAno;

  private $iPeriodo;

  private $oDom;

  private $erros = array();

  private $sNome;

  function __construct($iAno, $iPeriodo) {

    $this->iAno = $iAno;
    $this->iPeriodo = $iPeriodo;

    $this->oDom = new DOMDocument("1.0", "ISO-8859-1");
    $this->oDom->xmlStandalone = true;

  }

  private function registraErro($msg) {

    $this->erros[] = $msg;
  }

  public function getErros() {
    return $this->erros;
  }

  public function geraArquivo() {

    $oInformacao = $this->oDom->createElement("INFORMACAO");
    $oInformacao->setAttribute("tipo", "ITBI-URBANO");
    $oInformacao->setAttribute("versao", $this->oDom->xmlVersion);

    /**
     * <INFORMACAO>
     */
    $this->oDom->appendChild($oInformacao);

    /**
     * <MUNICIPIO >
     */
    $lMunicipio = $this->escreveMunicipio($oInformacao);

    /**
     * <IMOVEL>
     */
    $lRetornaArquivo = $this->escreveImovel($oInformacao);


    $this->sNome = "tmp/ITBIU_{$this->iCodigoMunicipio}_{$this->iPeriodo}_{$this->iAno}.xml";

    if ($lRetornaArquivo && $lMunicipio && $this->oDom->save($this->sNome)) {

        return $this->sNome;
    }

    return false;
  }

  /**
   * Cria a tag municipio e insere no XML dentro do parent
   * @param $oParent - DOMElement - Tag onde vai ser inserido o municipio
   */
  private function escreveMunicipio($oParent) {

    $oDadosMunicipio  = $this->getDadosMunicipio();
    $lMunicipioValido = true;

    $oMunicipio = $this->oDom->createElement("MUNICIPIO");

    $this->iCodigoMunicipio = $oDadosMunicipio->codigo;

    $oMunicipio->setAttribute("codigo", $oDadosMunicipio->codigo);
    $oMunicipio->setAttribute("nome", $oDadosMunicipio->nome);
    $oMunicipio->setAttribute("ano", $this->iAno);
    $oMunicipio->setAttribute("semestre", $this->iPeriodo);

    $oParent->appendChild($oMunicipio);

    if (empty($oDadosMunicipio->nome)) {
      $this->registraErro("MUNICÍPIO - Nome é obrigatório.");
      $lMunicipioValido = false;
    }

    return $lMunicipioValido;
  }

  /**
   * Retorna os dados do municipio
   * @return Object $oDadoMunicipio
   */
  private function getDadosMunicipio() {

    $sSqlMunicipio = "select db21_codigomunicipoestado as codigo, munic as nome from db_config where codigo = " . db_getsession("DB_instit") .";";

    $rsMunicipio = db_query($sSqlMunicipio);

    if (!$rsMunicipio) {
      throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_municipio' ) );
    }

    return db_utils::fieldsMemory($rsMunicipio, 0);
  }

  /**
   * Cria a tag imovel e insere no XML dentro do parent
   * @param DOMElement $oParent
   * @param integer $iMatricula
   *
   * @return boolean -- FALSE caso não tenha inserido nenhum imóvel
   */
  private function escreveImovel($oParent) {

    $lArquivoVazio = true;
    foreach ( $this->getDadosImoveis() as $oDadoImovel) {

      $oImovel = $this->oDom->createElement("imovel");

      $oImovel->setAttribute("matricula", $oDadoImovel->matricula);
      $oImovel->setAttribute("zona"     , $oDadoImovel->zona);
      $oImovel->setAttribute("nro_guia_ITBI", $oDadoImovel->numero_guia_itbi);


      /**
       * <TRANSMNITENTES>
       */
      $lTransmitentesValidos = $this->escreveTransmitentes($oImovel, $oDadoImovel->numero_guia_itbi);
      /**
       * </TRANSMITENTES>
       */

      /**
       * <ADQUIRENTES>
       */
       $lAdquirentesValidos = $this->escreveAdquirentes($oImovel, $oDadoImovel->numero_guia_itbi);
      /**
       * </ADQUIRENTES>
       */

      /**
       * <logradouro>
       */
      $lLogradouroValido = $this->escreveLogradouro($oImovel, $oDadoImovel->numero_guia_itbi);
      /**
       * </logradouro>
       */

      /**
       * <terreno>
       */
      $lTerrenoValido = $this->escreveTerreno($oImovel, $oDadoImovel->numero_guia_itbi);
      /**
       * </terreno>
       */

      /**
       * <BENFEITORIAS>
       */
      $lBenfeitoriasValido = $this->escreveBenfeitorias($oImovel, $oDadoImovel->numero_guia_itbi);
      /*
       * </BENFEITORIAS>
       */

      if ($lTransmitentesValidos && $lAdquirentesValidos && $lLogradouroValido && $lTerrenoValido && $lBenfeitoriasValido) {

        $lArquivoVazio = false;
        $oParent->appendChild($oImovel);
      }

    }

    return !$lArquivoVazio;
  }

  /**
   * Retorna os dados do imoveis
   * @return Object $oDadosImovel
   */
  private function getDadosImoveis(){

    $sDataInicial = $this->iAno . '-01-01';
    $sDataFinal   = $this->iAno . '-06-30';

    if ($this->iPeriodo == 2) {
      $sDataInicial = $this->iAno . '-07-01';
      $sDataFinal   = $this->iAno . '-12-31';
    }

    $sSql  =  "    select j04_matricregimo as matricula,                                         ";
    $sSql .=  "       case                                                                       ";
    $sSql .=  "         when j04_matric is not null then 1                                       ";
    $sSql .=  "       end as zona,                                                               ";
    $sSql .=  "       itbi.it01_guia as numero_guia_itbi                                         ";
    $sSql .=  "  from itbimatric                                                                 ";
    $sSql .=  "       inner join itbi                 on itbi.it01_guia = itbimatric.it06_guia   ";
    $sSql .=  "       inner join itburbano iu         on iu.it05_guia   = itbi.it01_guia         ";
    $sSql .=  "       inner join itbiavalia ita       on itbi.it01_guia = ita.it14_guia          ";
    $sSql .=  "                                      and ita.it14_dtliber BETWEEN '{$sDataInicial}'::date and '{$sDataFinal}'::date ";
    $sSql .=  "        left join iptubaseregimovel on j04_matric  = itbimatric.it06_matric";
    $sSql .=  "        left join itbicancela       on it16_guia   = itbi.it01_guia";
    $sSql .= " where it16_guia is null";
    $sSql .= " order by it14_dtliber";


    $rsImoveis = db_query($sSql);
    if (!$rsImoveis) {
      throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_imoveis' ) );
    }

    if (pg_num_rows($rsImoveis) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsImoveis);
  }

  /**
   * Cria e insere a tag de transmitentes
   * @param DOMElement $oImovel - Tag do pai
   * @param integer $iGuia - Numero da guia do itbi
   * @return boolean - Verifica de é válido
   */
  private function escreveTransmitentes($oImovel, $iGuia) {

    $lTransmitentesValidos = true;

    $oTransmitentes = $this->oDom->createElement("TRANSMITENTES");

    foreach( $this->getDadosTransmitentes($iGuia)  as $oDadoTransmitente) {

      $lTransmitenteValido = true;

      if (empty($oDadoTransmitente->nome)) {
        $this->registraErro("GUIA: ". $iGuia . " - TRANSMITENTE - Nome é obrigatório.");
        $lTransmitenteValidos = false;
        $lTransmitenteValido  = false;
      }

      if (empty($oDadoTransmitente->cpfcnpj)) {
        $this->registraErro("GUIA: ". $iGuia . " - TRANSMITENTE - CPF/CNPJ é obrigatório.");
        $lTransmitentesValidos = false;
        $lTransmitenteValido   = false;
      }

      if ($lTransmitenteValido) {
        $oTransmitente = $this->oDom->createElement("transmitente");
        $oTransmitente->setAttribute("nome",    utf8_encode($oDadoTransmitente->nome));
        $oTransmitente->setAttribute("cpf_cnpj", $oDadoTransmitente->cpfcnpj);

        $oTransmitentes->appendChild($oTransmitente);
      }

    }

    if ($lTransmitentesValidos) {
      $oImovel->appendChild($oTransmitentes);
    }

    return $lTransmitentesValidos;
  }

  /**
   * Retorna os dados de trasmitentes de uma guia
   * @param integer $iGuia - Numero da guia
   * @return Object $transmitentes
   */
  private function getDadosTransmitentes($iGuia) {

    $sSql  = "select it03_nome    as nome,                 ";
    $sSql .= "       it03_cpfcnpj as cpfcnpj               ";
    $sSql .= "  from itbinome                              ";
    $sSql .= " where it03_guia = {$iGuia}                  ";
    $sSql .= "   and it03_tipo = 'T';                      ";

    $rsTransmitentes = db_query($sSql);

    if (!$rsTransmitentes) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_trasmitentes' ) );
    }

    if (pg_num_rows($rsTransmitentes) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsTransmitentes);
  }

  /**
   * Cria e insere a tag de adquirentes do xml
   * @param DOMElemente $oImovel - Tag imovel pai
   * @param integer - número da guia
   * @return boolean - Se a tag é valida
   */
  private function escreveAdquirentes($oImovel, $iGuia){

    $lAdquirentesValidos = true;

    $oAdquirentes = $this->oDom->createElement("ADQUIRENTES");

    foreach ($this->getDadosAdquirentes($iGuia) as $oDadosAdquirente) {

      $lAdquirenteValido = true;

      if (empty($oDadosAdquirente->nome)) {
        $this->registraErro("GUIA: $iGuia - ADQUIRENTE - Nome é obrigatório.");
        $lAdquirentesValidos = false;
        $lAdquirenteValido   = false;
      }

      if (empty($oDadosAdquirente->cpfcnpj)) {
        $this->registraErro("GUIA: $iGuia - ADQUIRENTE - CPF/CNPJ é obrigatório.");
        $lAdquirentesValidos = false;
        $lAdquirenteValido   = false;
      }

      if ($lAdquirenteValido) {
        $oAdquirente = $this->oDom->createElement("adquirente");
        $oAdquirente->setAttribute("nome",     utf8_encode($oDadosAdquirente->nome));
        $oAdquirente->setAttribute("cpf_cnpj", $oDadosAdquirente->cpfcnpj);

        $oAdquirentes->appendChild($oAdquirente);
      }

    }

    if ($lAdquirentesValidos) {
      $oImovel->appendChild($oAdquirentes);
    }

    return $lAdquirentesValidos;
  }

  /**
   * Retorna os adquirentes de uma guia
   * @param integer - $iGuia - Numero da guia
   * @return Object[]
   */
  private function getDadosAdquirentes($iGuia) {

    $sSql  = "select it03_nome    as nome,                 ";
    $sSql .= "       it03_cpfcnpj as cpfcnpj               ";
    $sSql .= "  from itbinome                              ";
    $sSql .= " where it03_guia = $iGuia                    ";
    $sSql .= "   and it03_tipo = 'C';                      ";

    $rsAdquirentes = db_query($sSql);

    if (!$rsAdquirentes) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_adquirentes' ) );
    }

    if (pg_num_rows($rsAdquirentes) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsAdquirentes);
  }

  /**
   * Cria e insere a tag de logradouro no XML
   * @param DOMElement $oImovel - Tag do imovel pai
   * @param integer $iGuia - Número da guia
   * @return boolean
   */
  private function escreveLogradouro($oImovel, $iGuia) {

    $lLogradouroValido = true;

    $oDadosLogradouro = $this->getDadosLogradouro($iGuia);

    if (empty($oDadosLogradouro->tipo)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Tipo é obrigatório.");
      $lLogradouroValido = false;
    }

    if (empty($oDadosLogradouro->nome)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Nome é obrigatório.");
      $lLogradouroValido = false;
    }

    if (empty($oDadosLogradouro->lote)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Lote é obrigatório.");
      $lLogradouroValido = false;
    }

    if (empty($oDadosLogradouro->bairro)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Bairro é obrigatório.");
      $lLogradouroValido = false;
    }

    if (empty($oDadosLogradouro->quadra)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Quadra é obrigatória.");
      $lLogradouroValido = false;
    }

    if (empty($oDadosLogradouro->setor)) {
      $this->registraErro("GUIA: $iGuia - LOGRADOURO - Setor é obrigatório.");
      $lLogradouroValido = false;
    }

    if ($lLogradouroValido) {
      $oLogradouro = $this->oDom->createElement("logradouro");
      $oLogradouro->setAttribute("tipo"   , $oDadosLogradouro->tipo);
      $oLogradouro->setAttribute("nome"   , utf8_encode($oDadosLogradouro->nome));
      $oLogradouro->setAttribute("nro"    , $oDadosLogradouro->nro);
      $oLogradouro->setAttribute("compl"  , utf8_encode($oDadosLogradouro->complemento));
      $oLogradouro->setAttribute("lote"   , $oDadosLogradouro->lote);
      $oLogradouro->setAttribute("bairro" , utf8_encode($oDadosLogradouro->bairro));
      $oLogradouro->setAttribute("vila"   , $oDadosLogradouro->vila);
      $oLogradouro->setAttribute("quadra" , $oDadosLogradouro->quadra);
      $oLogradouro->setAttribute("setor"  , $oDadosLogradouro->setor);
    
      $oImovel->appendChild($oLogradouro);
    }

    return $lLogradouroValido;
  }

  /**
   * Retorna os dados de logradouro de uma itbi
   * @param integer $iGuia - Numero da guia
   * @return Object
   */
  private function getDadosLogradouro($iGuia) {

    $sSql  = "select ruastipo.j88_descricao as tipo,                                                ";
    $sSql .= "       it22_numero            as nro,                                                 ";
    $sSql .= "       j14_nome               as nome,                                                ";
    $sSql .= "       ''                     as complemento,                                         ";
    $sSql .= "       j34_lote               as lote,                                                ";
    $sSql .= "       j13_descr              as bairro,                                              ";
    $sSql .= "       ''                     as vila,                                                ";
    $sSql .= "       j34_quadra             as quadra,                                              ";
    $sSql .= "       j34_setor              as setor                                                ";
    $sSql .= "  from itbimatric                                                                     ";
    $sSql .= "       inner join itbidadosimovel on itbidadosimovel.it22_itbi = itbimatric.it06_guia ";
    $sSql .= "       inner join iptubase        on iptubase.j01_matric = itbimatric.it06_matric     ";
    $sSql .= "       inner join lote l          on l.j34_idbql         = iptubase.j01_idbql         ";
    $sSql .= "       inner join testpri tp      on tp.j49_idbql        = l.j34_idbql                ";
    $sSql .= "       inner join ruas            on ruas.j14_codigo     = tp.j49_codigo              ";
    $sSql .= "       inner join ruastipo        on ruastipo.j88_codigo = ruas.j14_tipo              ";
    $sSql .= "       inner join bairro          on j34_bairro          = j13_codi                   ";
    $sSql .= "       inner join testada         on ruas.j14_codigo     = j36_codigo                 ";
    $sSql .= "                                 and j01_idbql           = j36_idbql                  ";
    $sSql .= " where itbimatric.it06_guia = $iGuia                                                  ";


    $rsLogradouro = db_query($sSql);

    if (!$rsLogradouro) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_logradouro' ) );
    }

    if (pg_num_rows($rsLogradouro) == 0) {
      return array();
    }

    return db_utils::fieldsMemory($rsLogradouro, 0);
  }

  /**
   * Cria e insere a tag terreno dentro de imovel
   * @param DOMElement $oImovel - Tag imovel pai
   * @param integer $iGuia - Numero da guia
   * @return boolean
   */
  private function escreveTerreno($oImovel, $iGuia) {

    $lTerrenoValido = true;

    $oDadosTerreno = $this->getDadosTerreno($iGuia);

    if (empty($oDadosTerreno->areatotal_m2)) {
      $this->registraErro("GUIA: $iGuia - TERRENO - Área é obrigatória.");
      $lTerrenoValido = false;
    }

    if (empty($oDadosTerreno->area_transmitida_m2)) {
      $this->registraErro("GUIA: $iGuia - TERRENO - Área Transmitida é obrigatória.");
      $lTerrenoValido = false;
    }

    if (empty($oDadosTerreno->testada)) {
      $this->registraErro("GUIA: $iGuia - TERRENO - Testada é obrigatória.");
      $lTerrenoValido = false;
    }

    if (empty($oDadosTerreno->codigo_situacao_quadra)) {
      $this->registraErro("GUIA: $iGuia - TERRENO - Código Situação da Quadra é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( $oDadosTerreno->valor_declarado == "") {
      $this->registraErro("GUIA: $iGuia - TERRENO - Valor Declarado é obrigatório.");
      $lTerrenoValido = false;
    }

    if ( $oDadosTerreno->valor_avaliado == "") {
      $this->registraErro("GUIA: $iGuia - TERRENO - Valor Avaliado é obrigatório.");
      $lTerrenoValido = false;
    }

    if (empty($oDadosTerreno->data_avaliacao)) {
      $this->registraErro("GUIA: $iGuia - TERRENO - Data da Avaliação é obrigatório.");
      $lTerrenoValido = false;
    } else {
      $oDadosTerreno->data_avaliacao = implode("/", array_reverse( explode("-", $oDadosTerreno->data_avaliacao) ) );
    }

    if ($lTerrenoValido) {
      $oTerreno = $this->oDom->createElement("terreno");
      $oTerreno->setAttribute("area_total_m2"         , number_format($oDadosTerreno->areatotal_m2, 5,",",""));
      $oTerreno->setAttribute("area_transmitida_m2"   , number_format($oDadosTerreno->area_transmitida_m2, 5, ",",""));
      $oTerreno->setAttribute("testada"               ,            $oDadosTerreno->testada                    );
      $oTerreno->setAttribute("codigo_situacao_quadra",            $oDadosTerreno->codigo_situacao_quadra      );
      $oTerreno->setAttribute("valor_declarado"       , number_format($oDadosTerreno->valor_declarado, 2,",",""));
      $oTerreno->setAttribute("valor_avaliado"        , number_format($oDadosTerreno->valor_avaliado,2,",",""));
      $oTerreno->setAttribute("data_avaliacao"        ,            $oDadosTerreno->data_avaliacao              );
    
      $oImovel->appendChild($oTerreno);
    }

    return $lTerrenoValido;
  }

  /**
   * Retorna os dados do terreno da guia
   * @param integer $iGuia - Numero da guia
   * @return Object
   */
  private function getDadosTerreno($iGuia) {

    $sSql  = "select it01_areaterreno as areatotal_m2,                                                          ";
    $sSql .= "       it01_areatrans   as area_transmitida_m2,                                                   ";
    $sSql .= "       it05_frente      as testada,                                                               ";
    $sSql .= "       (  select db142_codigopitsefaz                                                             ";
    $sSql .= "           from carlote                                                                           ";
    $sSql .= "                inner join caractercaracteristica on db143_caracter            = j35_caract       ";
    $sSql .= "                inner join caracteristica         on db143_caracteristica      = db140_sequencial ";
    $sSql .= "                inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial ";
    $sSql .= "          where db140_grupocaracteristica = 47                                                    ";
    $sSql .= "            and j35_idbql = j01_idbql                                               LIMIT 1       ";
    $sSql .= "       ) as codigo_situacao_quadra,                                                               ";
    $sSql .= "       it01_valorterreno as valor_declarado,                                                      ";
    $sSql .= "       it14_valoravalter as valor_avaliado,                                                       ";
    $sSql .= "       it14_dtliber      as data_avaliacao                                                        ";
    $sSql .= "  from itbi                                                                                       ";
    $sSql .= "       inner join itbimatric      on itbimatric.it06_guia = itbi.it01_guia                        ";
    $sSql .= "       inner join iptubase        on iptubase.j01_matric  = itbimatric.it06_matric                ";
    $sSql .= "       inner join itburbano iu    on iu.it05_guia         = itbi.it01_guia                        ";
    $sSql .= "       inner join itbiavalia      on itbiavalia.it14_guia = itbi.it01_guia                        ";
    $sSql .= " where it01_guia = $iGuia                                                                         ";

    $rsTerreno = db_query($sSql);

    if (!$rsTerreno) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_terreno' ) );
    }

    if (pg_num_rows($rsTerreno) == 0) {
      return array();
    }

    return db_utils::fieldsMemory($rsTerreno, 0);
  }

  /**
   * Criar e insere a tag de benfeitorias no XML
   * @param DOMElement $oImovel
   * @param integer $iGuia - Numero da guia
   * @return boolean
   */
  private function escreveBenfeitorias($oImovel, $iGuia) {

    $lBenfeitoriasValidos = true;

    $oBenfeitorias = $this->oDom->createElement("BENFEITORIAS");

    foreach ($this->getDadosBenfeitorias($iGuia) as $oDadoEdificacao) {

      $lEdificacaoValido = true;

      $oEdificacao = $this->oDom->createElement("edificacao");

      if (empty($oDadoEdificacao->codigo_especie_urbana)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Código Espécie Urbana é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if ( $oDadoEdificacao->area_total == "") {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Área é obrigatória.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if ( $oDadoEdificacao->area_transmitida == "") {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Área Transmitida é obrigatória.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if (empty($oDadoEdificacao->codigo_tipo_material)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Código Tipo de Material é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if (empty($oDadoEdificacao->codigo_padrao_construtivo)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Código Padrão Construtivo é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if (empty($oDadoEdificacao->ano_construcao)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Ano é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if ( $oDadoEdificacao->valor_declarado == "") {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Valor Declarado é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if ( $oDadoEdificacao->valor_avaliado == "") {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Valor Avaliado é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if (empty($oDadoEdificacao->data_avaliacao)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Data da Avaliação é obrigatória.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }
      if (empty($oDadoEdificacao->tipo_utilizacao)) {
        $this->registraErro("GUIA: $iGuia - EDIFICAÇÃO - Tipo de Utilização é obrigatório.");
        $lEdificacaoValido    = false;
        $lBenfeitoriasValidos = false;
      }

      if (!empty($oDadoEdificacao->data_avaliacao)) {
        $oDadoEdificacao->data_avaliacao = implode("/", array_reverse( explode("-", $oDadoEdificacao->data_avaliacao) ));
      }

      if ($lEdificacaoValido) {
        $oEdificacao->setAttribute("codigo_especie_urbana"    , $oDadoEdificacao->codigo_especie_urbana);
        $oEdificacao->setAttribute("area_total_m2"            , number_format($oDadoEdificacao->area_total, 5,",",""));
        $oEdificacao->setAttribute("area_transmitida_m2"      , number_format($oDadoEdificacao->area_transmitida, 5,",",""));
        $oEdificacao->setAttribute("area_privativa"           , $oDadoEdificacao->area_privativa);
        $oEdificacao->setAttribute("codigo_tipo_material"     , $oDadoEdificacao->codigo_tipo_material);
        $oEdificacao->setAttribute("codigo_padrao_construtivo", $oDadoEdificacao->codigo_padrao_construtivo);
        $oEdificacao->setAttribute("ano_construcao"           , $oDadoEdificacao->ano_construcao);
        $oEdificacao->setAttribute("valor_declarado"          , number_format($oDadoEdificacao->valor_declarado, 2,",",""));
        $oEdificacao->setAttribute("valor_avaliado"           , number_format($oDadoEdificacao->valor_avaliado,2,",",""));
        $oEdificacao->setAttribute("data_avaliacao"           , $oDadoEdificacao->data_avaliacao);
        $oEdificacao->setAttribute("Tipo_utilizacao"          , utf8_encode($oDadoEdificacao->tipo_utilizacao));
      
        $oBenfeitorias->appendChild($oEdificacao);
      }

    }

    if ($lBenfeitoriasValidos && isset($lEdificacaoValido)) {
      $oImovel->appendChild($oBenfeitorias);
    }

    return $lBenfeitoriasValidos;
  }

  /**
   * Retorna os dados das benfeitorias/edificacoes
   * @param integer $iGuia - Numero da guia
   * @return Object[]
   */
  private function getDadosBenfeitorias($iGuia) {

    $sSql  = "select it08_area   as area_total,                                                                   ";
    $sSql .= "       null           as area_privativa,                                                            ";
    $sSql .= "       it08_areatrans as area_transmitida,                                                          ";

    $sSql .= "( select db142_codigopitsefaz                                                                       ";
    $sSql .= "    from itbiconstrespecie                                                                          ";
    $sSql .= "         inner join caractercaracteristica on db143_caracter            = it09_caract               ";
    $sSql .= "         inner join caracteristica         on db143_caracteristica      = db140_sequencial          ";
    $sSql .= "         inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial          ";
    $sSql .= "   where itbiconstrespecie.it09_codigo = it08_codigo limit 1) as codigo_especie_urbana,             ";

    $sSql .= "       (select db142_codigopitsefaz                                                                 ";
    $sSql .= "          from itbiconstrtipo                                                                       ";
    $sSql .= "               inner join caractercaracteristica on db143_caracter            = it10_caract         ";
    $sSql .= "               inner join caracteristica         on db143_caracteristica      = db140_sequencial    ";
    $sSql .= "               inner join caracteristicapitsefaz on db142_caracteristica      = db140_sequencial    ";
    $sSql .= "         where itbiconstrtipo.it10_codigo = it08_codigo limit 1                                     ";
    $sSql .= "       ) as codigo_tipo_material,                                                                   ";

    $sSql .= "       (select db142_codigopitsefaz                                                                 ";
    $sSql .= "          from itbiconstrpadraoconstrutivo                                                          ";
    $sSql .= "               inner join caractercaracteristica on db143_caracter = it34_caract                    ";
    $sSql .= "               inner join caracteristicapitsefaz on db142_caracteristica = db143_caracteristica     ";
    $sSql .= "         where it34_codigo = it08_codigo limit 1                                                    ";
    $sSql .= "       )     as codigo_padrao_construtivo,                                                          ";


    $sSql .= "       it08_ano as ano_construcao,                                                                  ";
    $sSql .= "       it01_valorconstr      as valor_declarado,                                                    ";
    $sSql .= "       it14_valoravalconstr  as valor_avaliado,                                                     ";
    $sSql .= "       it14_dtliber          as data_avaliacao,                                                     ";

    $sSql .= "       (select caracter.j31_descr                                                                   ";
    $sSql .= "          from cargrup                                                                              ";
    $sSql .= "               inner join caracter          on caracter.j31_grupo            = cargrup.j32_grupo    ";
    $sSql .= "               inner join itbiconstrespecie on itbiconstrespecie.it09_caract = caracter.j31_codigo  ";
    $sSql .= "         where itbiconstrespecie.it09_codigo = it08_codigo limit 1                                  ";
    $sSql .= "       ) as tipo_utilizacao                                                                         ";

    $sSql .= "  from itbiconstr                                                                                   ";
    $sSql .= "       inner join itbi        on itbi.it01_guia       = itbiconstr.it08_guia                        ";
    $sSql .= "       inner join itbiavalia  on itbiavalia.it14_guia = itbiconstr.it08_guia                        ";
    $sSql .= " where itbiconstr.it08_guia = $iGuia                                                                ";


    $rsEdificacoes = db_query($sSql);

    if (!$rsEdificacoes) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_benfeitorias' ) );
    }

    if (pg_num_rows($rsEdificacoes) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsEdificacoes);
  }

  /**
   * Retorna o nome do arquivo gerado;
   * @return string
   */
  public function getNome() {
    $this->sNome;
  }
}