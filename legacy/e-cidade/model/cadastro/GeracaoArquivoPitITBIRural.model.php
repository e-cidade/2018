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

class GeracaoArquivoPitITBIRural {

	/**
	 * Caminho do JSON com as mensagens
	 */
	const MENSAGENS = 'arrecadacao.cadastro.GeracaoArquivoPitITBIRural.';

  /**
   * Objeto que irá armazenar o xml
   */
  private $oDomDocument;

  /**
   * Ano a ser consultado
   */
  private $iAno;

  /**
   * Período a ser consultado
   */
  private $iPeriodo;

  /**
   * Início do período a ser consultado
   */
  private $dtInicio;

  /**
   * Final do período a ser consultado
   */
  private $dtFim;

  /**
   * Nome do arquivo a ser gerado
   */
  private $sNomeArquivo;

  /**
   * Inconsistencias encontradas durante a geração do XML
   */
  private $erros = array();

  public function __construct($iAno, $iPeriodo) {

    $this->setAnoPeriodo($iAno, $iPeriodo);
  }

  /**
   * Seta o período da consuta e o ano
   *
   * @param Integer $iAno
   * @param Integer $iPeriodo
   *        1 - 1º Semestre
   *        2 - 2º Semestre
   */
  public function setAnoPeriodo($iAno, $iPeriodo) {
    $this->iAno     = $iAno;
    $this->iPeriodo = $iPeriodo;

    $aPeriodo = array(
      1 => array(
        'inicio' => "{$iAno}-01-01",
        'fim' => "{$iAno}-06-30"
      ),
      2 => array(
        'inicio' => "{$iAno}-07-01",
        'fim' => "{$iAno}-12-31"
      )
    );

    $this->dtInicio = $aPeriodo[$iPeriodo]['inicio'];
    $this->dtFim    = $aPeriodo[$iPeriodo]['fim'];
  }

  /**
   * Gera o XML do ITBI Rural
   *
   * @return mixed Boolean|String -- Caminho do arquivo XML gerado
   */
  public function geraArquivo() {

    $this->erros = array();

    $this->oDomDocument             = new DOMDocument('1.0', 'iso-8859-1');
    $this->oDomDocument->standalone = true;

    /**
     * Adiciona o nó iformacao
     */
    $oInformacao = $this->oDomDocument->createElement('INFORMACAO');

    $oInformacao->setAttribute('tipo', 'ITBI-RURAL');
    $oInformacao->setAttribute('versao', '1.0');

    /**
     * Adiciona o nó MUNICIPIO
     */
    $oInformacao->appendChild( $this->preencheMunicipio() );

    /**
     * Busca os ITBIs Rurais
     */
    $aImoveis = $this->getImoveis();

    $lRetornaVazio = true;

    foreach ($aImoveis as $oImovelDados) {

      /**
       * Adiciona o nó imovel
       */
      $oImovelTag  = $this->oDomDocument->createElement('imovel');
      $lErroImovel = false;

      $oImovelTag->setAttribute( 'matricula',     $oImovelDados->matricula );
      $oImovelTag->setAttribute( 'zona',          $oImovelDados->zona );
      $oImovelTag->setAttribute( 'nro_guia_ITBI', $oImovelDados->nro_guia_itbi );
      $oImovelTag->setAttribute( 'utilizacao',    utf8_encode($oImovelDados->utilizacao) );

      /**
       * Adiciona o nó TRANSMITENTES
       */
      $oTransmitentes = $this->preencheTransmitentes($oImovelDados->nro_guia_itbi);

      if (!$oTransmitentes) {
        $lErroImovel = true;
      } else {
        $oImovelTag->appendChild( $oTransmitentes );
      }

      /**
       * Adiciona o nó ADQUIRENTES
       */
      $oAdquirentes = $this->preencheAdquirentes($oImovelDados->nro_guia_itbi);

      if (!$oAdquirentes) {
        $lErroImovel = true;
      } else {
        $oImovelTag->appendChild( $oAdquirentes );
      }

      /**
       * Adiciona o nó endereco
       */
      $oEndereco = $this->preencheEndereco($oImovelDados->nro_guia_itbi);

      if (!$oEndereco) {
        $lErroImovel = true;
      } else {
        $oImovelTag->appendChild( $oEndereco );
      }

      /**
       * Adiciona o nó terra
       */
      $oTerra = $this->preencheTerra($oImovelDados->nro_guia_itbi);

      if (!$oTerra) {
        $lErroImovel = true;
      } else {
        $oImovelTag->appendChild( $oTerra );
      }

      /**
       * Adiciona o nó BENFEITORIAS
       */
      $oBenfeitorias = $this->preencheBenfeitorias($oImovelDados->nro_guia_itbi);

      if (!$oBenfeitorias) {
        $lErroImovel = true;
      } else if ($oBenfeitorias instanceof DOMElement) {
        $oImovelTag->appendChild( $oBenfeitorias );
      }

      if (!$lErroImovel) {

        $lRetornaVazio = false;
        $oInformacao->appendChild( $oImovelTag );
      }
    }

    $this->oDomDocument->appendChild( $oInformacao );

    $this->sNomeArquivo  = 'tmp/ITBIR_' . $oInformacao->getElementsByTagName('MUNICIPIO')->item(0)->getAttribute('codigo');
    $this->sNomeArquivo .= "_{$this->iPeriodo}_{$this->iAno}.xml";

    if (file_exists($this->sNomeArquivo)) {
      unlink($this->sNomeArquivo);
    }

    if ($lRetornaVazio || !$this->oDomDocument->save( $this->sNomeArquivo )) {
      $this->sNomeArquivo = null;
    }

    return $this->getXmlGerado();
  }

  /**
   * Retorna o caminho do arquivo XML gerado
   *
   * @return mixed String|Boolean
   */
  public function getXmlGerado() {
    if ($this->sNomeArquivo != null) {
      return $this->sNomeArquivo;
    }

    return false;
  }

  /**
   * Retorna os erros ocorridos na ultima geração do arquivo
   *
   * @return array;
   */
  public function getErros() {
    return $this->erros;
  }

  /**
   * Adiciona um erro ao array de erros
   *
   * @param String $sErro
   */
  private function registraErro($sErro) {
    $this->erros[] = $sErro;
  }

  /**
   * Monta a tag MUNICIPIO do XML
   *
   * @return DOMElement
   */
  private function preencheMunicipio() {

    $oMunicipio      = $this->oDomDocument->createElement('MUNICIPIO');
    $oDadosMunicipio = $this->getDadosMunicipio();

    $oMunicipio->setAttribute( 'codigo',   $oDadosMunicipio->codigo );
    $oMunicipio->setAttribute( 'nome',     utf8_encode($oDadosMunicipio->nome) );
    $oMunicipio->setAttribute( 'ano',      $this->iAno );
    $oMunicipio->setAttribute( 'semestre', $this->iPeriodo );

    return $oMunicipio;
  }

  /**
   * Monta a tag TRANSMITENTES do XML
   *
   * @param Integer $iCodigoItbi
   * @return mixed DOMElement|Boolean
   */
  private function preencheTransmitentes($iCodigoItbi) {

    $oTransmitentesContainer = $this->oDomDocument->createElement('TRANSMITENTES');
    $aTransmitentes          = $this->getTransmitentes($iCodigoItbi);

    $lErroTransmitentes = false;

    foreach ($aTransmitentes as $oTransmitenteDados) {

      $oTransmitenteTag = $this->oDomDocument->createElement('transmitente');
      $lRegistroValido  = true;

      if (empty($oTransmitenteDados->nome)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - TRANSMITENTES - Nome é obrigatório.");

        $lErroTransmitentes = true;
        $lRegistroValido    = false;
      }

      if (empty($oTransmitenteDados->cpfcnpj)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - TRANSMITENTES - CPF/CNPJ é obrigatório.");

        $lErroTransmitentes = true;
        $lRegistroValido    = false;
      }

      if ($lRegistroValido) {

        $oTransmitenteTag->setAttribute( 'nome',     utf8_encode($oTransmitenteDados->nome) );
        $oTransmitenteTag->setAttribute( 'cpf_cnpj', $oTransmitenteDados->cpfcnpj );

        $oTransmitentesContainer->appendChild($oTransmitenteTag);
      }
    }

    return $lErroTransmitentes ? false : $oTransmitentesContainer;
  }

  /**
   * Monta a tag ADQUIRENTES do XML
   *
   * @param Integer $iCodigoItbi
   * @return mixed DOMElement|Boolean
   */
  private function preencheAdquirentes($iCodigoItbi) {

    $oAdquirentesContainer = $this->oDomDocument->createElement('ADQUIRENTES');
    $aAdquirentes          = $this->getAdquirentes($iCodigoItbi);

    $lErroAdquirentes = false;

    foreach ($aAdquirentes as $oAdquirenteDados) {

      $oAdquirenteTag  = $this->oDomDocument->createElement('adquirente');
      $lRegistroValido = true;

      if (empty($oAdquirenteDados->nome)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - ADQUIRENTES - Nome é obrigatório.");

        $lErroAdquirentes = true;
        $lRegistroValido    = false;
      }

      if (empty($oAdquirenteDados->cpfcnpj)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - ADQUIRENTES - CPF/CNPJ é obrigatório.");

        $lErroAdquirentes = true;
        $lRegistroValido    = false;
      }

      if ($lRegistroValido) {
        $oAdquirenteTag->setAttribute( 'nome',     utf8_encode($oAdquirenteDados->nome) );
        $oAdquirenteTag->setAttribute( 'cpf_cnpj', $oAdquirenteDados->cpfcnpj );

        $oAdquirentesContainer->appendChild($oAdquirenteTag);
      }
    }

    return $lErroAdquirentes ? false : $oAdquirentesContainer;
  }

  /**
   * Monta a tag endereco do XML
   *
   * @param Integer $iCodigoItbi
   * @return mixed DOMElement|Boolean
   */
  private function preencheEndereco($iCodigoItbi) {

    $oEndereco      = $this->oDomDocument->createElement('endereco');
    $oEnderecoDados = $this->getEndereco($iCodigoItbi);

    $lRegistroValido = true;

    if (empty($oEnderecoDados->localidade)) {

      $this->registraErro("ITBI: {$iCodigoItbi} - ENDEREÇO - Localidade é obrigatória.");

      $lRegistroValido = false;
    }

    if (empty($oEnderecoDados->distrito)) {

      $this->registraErro("ITBI: {$iCodigoItbi} - ENDEREÇO - Distrito é obrigatório.");

      $lRegistroValido = false;
    }

    if ($lRegistroValido) {

      $oEndereco->setAttribute( 'localidade',    utf8_encode($oEnderecoDados->localidade) );
      $oEndereco->setAttribute( 'distrito',      utf8_encode($oEnderecoDados->distrito ) );
      $oEndereco->setAttribute( 'lote',          $oEnderecoDados->lote );
      $oEndereco->setAttribute( 'compl',         utf8_encode($oEnderecoDados->compl) );
      $oEndereco->setAttribute( 'confrontacoes', utf8_encode($oEnderecoDados->confrontacoes) );
    }


    return $lRegistroValido ? $oEndereco : false;
  }

  /**
   * Monta a tag terra do XML
   *
   * @param Integer $iCodigoItbi
   * @return mixed DOMElement|Boolean
   */
  private function preencheTerra($iCodigoItbi) {

    $oTerra      = $this->oDomDocument->createElement('terra');
    $oTerraDados = $this->getTerra( $iCodigoItbi );

    $lRegistroValido = true;

    if (empty($oTerraDados->area_transmitida)) {
      $this->registraErro("ITBI: {$iCodigoItbi} - TERRA - Área Transmitida Ha é obrigatória.");

      $lRegistroValido = false;
    }


    if (empty($oTerraDados->valor_avaliado)) {
      $this->registraErro("ITBI: {$iCodigoItbi} - TERRA - Valor Avaliado é obrigatório.");

      $lRegistroValido = false;
    }

    if (empty($oTerraDados->valor_declarado)) {
      $this->registraErro("ITBI: {$iCodigoItbi} - TERRA - Valor Declarado é obrigatório.");

      $lRegistroValido = false;
    }

    if (empty($oTerraDados->data_avaliacao)) {
      $this->registraErro("ITBI: {$iCodigoItbi} - TERRA - Data Avaliação é obrigatória.");

      $lRegistroValido = false;
    }

    if ($lRegistroValido) {

      $oTerra->setAttribute( 'area_total_ha',         number_format($oTerraDados->area_total, 5, ',', '') );
      $oTerra->setAttribute( 'area_transmitida_ha',   number_format($oTerraDados->area_transmitida, 5, ',', '') );
      $oTerra->setAttribute( 'codigo_situacao_terra', $oTerraDados->codigo_situacao_terra );
      $oTerra->setAttribute( 'valor_declarado',       number_format($oTerraDados->valor_declarado, 2, ',', '') );
      $oTerra->setAttribute( 'valor_avaliado',        number_format($oTerraDados->valor_avaliado, 2, ',', '') );
      $oTerra->setAttribute( 'data_avaliacao',        date("d/m/Y", strtotime( $oTerraDados->data_avaliacao )) );
      $oTerra->setAttribute( 'tipo_utilizacao',       utf8_encode($oTerraDados->tipo_utilizacao) );
    }

    return $lRegistroValido ? $oTerra : false;
  }

  /**
   * Monta a tag BENFEITORIAS do XML
   *
   * @param Integer $iCodigoItbi
   * @return mixed DOMElement|Boolean
   */
  private function preencheBenfeitorias($iCodigoItbi) {

    $oBenfeitoriasContainer = $this->oDomDocument->createElement('BENFEITORIAS');
    $aBenfeitorias          = $this->getBenfeitorias($iCodigoItbi);

    if (empty($aBenfeitorias)) {
      return true;
    }

    $lErroBenfeitorias = false;

    foreach ($aBenfeitorias as $oBenfeitoriaDados) {

      $oBenfeitoria    = $this->oDomDocument->createElement('edificacao');
      $lRegistroValido = true;

      if (empty($oBenfeitoriaDados->codigo_especie_rural)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Espécie Rural é obrigatória.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->area_transmitida)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Área Transmitida é obrigatória.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->codigo_tipo_material)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Tipo de Material é obrigatório.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->codigo_padrao_construtivo)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Padrão Construtivo é obrigatório.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->ano_construcao)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Ano Construção é obrigatório.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->valor_avaliado)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Valor Avaliado é obrigatório.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if (empty($oBenfeitoriaDados->data_avaliacao)) {
        $this->registraErro("ITBI: {$iCodigoItbi} - BENFEITORIA - Data Avaliação é obrigatória.");

        $lRegistroValido   = false;
        $lErroBenfeitorias = true;
      }

      if ($lRegistroValido) {

        $oBenfeitoria->setAttribute( 'codigo_especie_rural',      $oBenfeitoriaDados->codigo_especie_rural );
        $oBenfeitoria->setAttribute( 'area_total_m2',             number_format( $oBenfeitoriaDados->area_total, 2, ',', '') );
        $oBenfeitoria->setAttribute( 'area_transmitida_m2',       number_format( $oBenfeitoriaDados->area_transmitida, 2, ',', '') );
        $oBenfeitoria->setAttribute( 'area_privativa',            $oBenfeitoriaDados->area_privativa );
        $oBenfeitoria->setAttribute( 'codigo_tipo_material',      $oBenfeitoriaDados->codigo_tipo_material );
        $oBenfeitoria->setAttribute( 'codigo_padrao_construtivo', $oBenfeitoriaDados->codigo_padrao_construtivo );
        $oBenfeitoria->setAttribute( 'ano_construcao',            $oBenfeitoriaDados->ano_construcao );
        $oBenfeitoria->setAttribute( 'valor_declarado',           number_format( $oBenfeitoriaDados->valor_declarado, 2, ',', '') );
        $oBenfeitoria->setAttribute( 'valor_avaliado',            number_format( $oBenfeitoriaDados->valor_avaliado, 2, ',', '') );
        $oBenfeitoria->setAttribute( 'data_avaliacao',            date("d/m/Y", strtotime( $oBenfeitoriaDados->data_avaliacao )) );

        $oBenfeitoriasContainer->appendChild( $oBenfeitoria );
      }
    }

    return $lErroBenfeitorias ? false : $oBenfeitoriasContainer;
  }

  /**
   * Retorna os dados do municipio
   *
   * @return STDClass
   */
  private function getDadosMunicipio() {

    $sSql  = " select db21_codigomunicipoestado as codigo,        ";
    $sSql .= "        munic as nome                               ";
    $sSql .= "   from db_config                                   ";
    $sSql .= "  where codigo = " . db_getsession("DB_instit") . ";";

    $rsMunicipio = db_query( $sSql );

    if (!$rsMunicipio) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_municipio' ) );
    }

    return db_utils::fieldsMemory($rsMunicipio, 0);
  }

  /**
   * Busca os imóveis de acordo com o período e ano
   *
   * @return array
   */
  private function getImoveis() {

    $sSql  = " select it01_guia        as nro_guia_itbi,                                                                     ";
    $sSql .= "        j04_matricregimo as matricula,                                                                         ";
    $sSql .= "        case                                                                                                   ";
    $sSql .= "          when j04_matric is not null then 1                                                                   ";
    $sSql .= "        end as zona,                                                                                           ";
    $sSql .= "        (select array_to_string(array_accum(distinct caracter.j31_descr), ', ')                                ";
    $sSql .= "           from cargrup                                                                                        ";
    $sSql .= "                inner join caracter                         on caracter.j31_grupo        = cargrup.j32_grupo   ";
    $sSql .= "                inner join itbiruralcaract                  on it19_codigo               = caracter.j31_codigo ";
    $sSql .= "                inner join paritbi                          on it24_grupodistrterrarural = cargrup.j32_grupo  ";
    $sSql .= "          where itbiruralcaract.it19_guia = it01_guia and it19_valor > 0) as utilizacao                        ";
    $sSql .= "   from itbi                                                                                                   ";
    $sSql .= "        left join  itbimatric        on it06_guia  = it01_guia                                                 ";
    $sSql .= "        left join  iptubaseregimovel on j04_matric = it06_matric                                               ";
    $sSql .= "        inner join itbirural         on it18_guia  = it01_guia                                                 ";
    $sSql .= "        inner join itbiavalia        on it14_guia  = it01_guia                                                 ";
    $sSql .= "                                    and it14_dtliber between '{$this->dtInicio}' and '{$this->dtFim}'          ";
    $sSql .= "        left join itbicancela       on it16_guia   = itbi.it01_guia                                            ";
    $sSql .= " where it16_guia is null                                                                                       ";

    $rsImoveis = db_query( $sSql );

    if (!$rsImoveis) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_municipio' ) );
    }

    if (pg_num_rows($rsImoveis) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsImoveis);
  }

  /**
   * Retorna os dados dos Transmitentes para o ITBI passado
   *
   * @param Integer $iGuiaItbi
   * @return array
   */
  private function getTransmitentes($iGuiaItbi) {

    $sSql  = " select it03_nome    as nome,    ";
    $sSql .= "        it03_cpfcnpj as cpfcnpj  ";
    $sSql .= "   from itbinome                 ";
    $sSql .= "  where it03_guia = {$iGuiaItbi} ";
    $sSql .= "    and it03_tipo = 'T';         ";

    $rsTransmitentes = db_query( $sSql );

    if (!$rsTransmitentes) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_trasmitentes' ) );
    }

    if (pg_num_rows($rsTransmitentes) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsTransmitentes);
  }

  /**
   * Retorna os dados dos adquirentes para o ITBI passado
   *
   * @param Integer $iGuiaItbi
   * @return array
   */
  private function getAdquirentes($iGuiaItbi) {

    $sSql  = " select it03_nome    as nome,    ";
    $sSql .= "        it03_cpfcnpj as cpfcnpj  ";
    $sSql .= "   from itbinome                 ";
    $sSql .= "  where it03_guia = {$iGuiaItbi} ";
    $sSql .= "    and it03_tipo = 'C';         ";

    $rsAdquirentes = db_query( $sSql );

    if (!$rsAdquirentes) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_adquirentes' ) );
    }

    if (pg_num_rows($rsAdquirentes) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsAdquirentes);
  }

  /**
   * Retorna o endereço para o ITBI passado
   *
   * @param Integer $iGuiaItbi
   * @return StdClass
   */
  private function getEndereco($iGuiaItbi) {

    $sSql  = " select it18_localimovel as localidade,                     ";
    $sSql .= "        1 as distrito,                                      ";
    $sSql .= "        it22_lote as lote,                                  ";
    $sSql .= "        it22_compl as compl,                                ";
    $sSql .= "        '' as confrontacoes                                 ";
    $sSql .= "   from itbirural                                           ";
    $sSql .= "        inner join itbidadosimovel on it22_itbi = it18_guia ";
    $sSql .= "  where it18_guia = {$iGuiaItbi};                           ";

    $rsEndereco = db_query( $sSql );

    if (!$rsEndereco) {
    	throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_endereco' ) );
    }

    if (pg_num_rows($rsEndereco) == 0) {
      return array();
    }

    return db_utils::fieldsMemory($rsEndereco, 0);
  }

  /**
   * Retorna os dados da terra para o ITBI passado
   *
   * @param Integer $iGuiaItbi
   * @return StdClass
   */
  private function getTerra($iGuiaItbi) {

    $sSql  = " select it01_areaterreno    as area_total,                                                                         ";
    $sSql .= "        it01_areatrans      as area_transmitida,                                                                   ";
    $sSql .= "        ''                  as codigo_situacao_terra,                                                              ";
    $sSql .= "        it01_valorterreno   as valor_declarado,                                                                    ";
    $sSql .= "        it14_valoravalter   as valor_avaliado,                                                                     ";
    $sSql .= "        it14_dtliber        as data_avaliacao,                                                                     ";
    $sSql .= "        (select array_to_string(array_accum(distinct caracter.j31_descr), ', ')                                    ";
    $sSql .= "           from cargrup                                                                                            ";
    $sSql .= "                inner join caracter                         on caracter.j31_grupo            = cargrup.j32_grupo   ";
    $sSql .= "                inner join itbiruralcaract                  on it19_codigo                   = caracter.j31_codigo ";
    $sSql .= "                inner join paritbi                          on it24_grupoutilterrarural     = cargrup.j32_grupo   ";
    $sSql .= "          where itbiruralcaract.it19_guia = it01_guia and it19_valor > 0) as tipo_utilizacao                       ";
    $sSql .= "   from itbi                                                                                                       ";
    $sSql .= "        inner join itbiavalia on it14_guia = it01_guia                                                             ";
    $sSql .= "  where it01_guia = {$iGuiaItbi};                                                                                  ";

    $rsTerra = db_query( $sSql );

    if (!$rsTerra) {
      throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_terra' ) );
    }

    if (pg_num_rows($rsTerra) == 0) {
      return array();
    }

    return db_utils::fieldsMemory($rsTerra, 0);
  }

  /**
   * Retorna os dados das benfeitorias para o ITBI passado
   *
   * @param Integer $iGuiaItbi
   * @return array
   */
  private function getBenfeitorias($iGuiaItbi) {

    $sSql  = " select ( select db142_codigopitsefaz                                                             ";
    $sSql .= "            from itbiconstrespecie                                                                ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter       = it09_caract          ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica = db140_sequencial     ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica = db140_sequencial     ";
    $sSql .= "           where itbiconstrespecie.it09_codigo = it08_codigo limit 1) as codigo_especie_rural,    ";
    $sSql .= "        it08_area      as area_total,                                                             ";
    $sSql .= "        it08_areatrans as area_transmitida,                                                       ";
    $sSql .= "        ''             as area_privativa,                                                         ";
    $sSql .= "        ( select db142_codigopitsefaz                                                             ";
    $sSql .= "            from itbiconstrtipo                                                                   ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter       = it10_caract          ";
    $sSql .= "                 inner join caracteristica         on db143_caracteristica = db140_sequencial     ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica = db140_sequencial     ";
    $sSql .= "           where itbiconstrtipo.it10_codigo = it08_codigo limit 1) as codigo_tipo_material,       ";
    $sSql .= "        ( select db142_codigopitsefaz                                                             ";
    $sSql .= "            from itbiconstrpadraoconstrutivo                                                      ";
    $sSql .= "                 inner join caractercaracteristica on db143_caracter       = it34_caract          ";
    $sSql .= "                 inner join caracteristicapitsefaz on db142_caracteristica = db143_caracteristica ";
    $sSql .= "           where it08_codigo = it34_codigo limit 1) as codigo_padrao_construtivo,                 ";
    $sSql .= "       it08_ano             as ano_construcao,                                                    ";
    $sSql .= "       it01_valorconstr     as valor_declarado,                                                   ";
    $sSql .= "       it14_valoravalconstr as valor_avaliado,                                                    ";
    $sSql .= "       it14_dtliber         as data_avaliacao                                                     ";
    $sSql .= "  from itbiconstr                                                                                 ";
    $sSql .= "       inner join itbi       on itbi.it01_guia       = itbiconstr.it08_guia                       ";
    $sSql .= "       inner join itbiavalia on itbiavalia.it14_guia = itbiconstr.it08_guia                       ";
    $sSql .= " where itbiconstr.it08_guia = {$iGuiaItbi};                                                       ";

    $rsBenfeitorias = db_query( $sSql );

    if (!$rsBenfeitorias) {
      throw new DBException( _M( self::MENSAGENS . 'erro_buscar_dados_benfeitorias' ) );
    }

    if (pg_num_rows($rsBenfeitorias) == 0) {
      return array();
    }

    return db_utils::getCollectionByRecord($rsBenfeitorias);
  }
}