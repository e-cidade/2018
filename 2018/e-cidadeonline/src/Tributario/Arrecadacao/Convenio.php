<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao;

class Convenio {

  const MODALIDADE_COBRANCA     = 1;
  const MODALIDADE_ARRECADACAO  = 2;
  const MODALIDADE_CAIXA_PADRAO = 3;

  const TIPO_CONVENIO_COMPENSACAO_BDL     = 1;
  const TIPO_CONVENIO_COMPENSACAO_BSJ     = 2;
  const TIPO_CONVENIO_ARRECADACAO         = 3;
  const TIPO_CONVENIO_CAIXA_PADRAO        = 4;
  const TIPO_CONVENIO_COMPENSACAO_SICOB   = 5;
  const TIPO_CONVENIO_COMPENSACAO_SIGCB   = 6;
  const TIPO_CONVENIO_COBRANCA_REGISTRADA = 7;

  /**
   * @var integer
   */
  private $iModalidadeConvenio;

  /**
   * @var integer
   */
  private $iCodConvenio;

  /**
   * @var integer
   */
  private $iFormatoVenc;

  /**
   * @var string
   */
  private $sSegmento;

  /**
   * @var integer
   */
  private $iCodBanco;

  /**
   * @var integer
   */
  private $iCodAgencia;

  /**
   * @var integer
   */
  private $iDigAgencia;

  /**
   * @var string
   */
  private $sCarteira;

  /**
   * @var string
   */
  private $sVariacao;

  /**
   * @var integer
   */
  private $iConvenioArrecadacao;

  /**
   * @var integer
   */
  private $iConvenioCobranca;

  /**
   * @var string
   */
  private $sCedente;

  /**
   * @var string
   */
  private $sSiglaTipoConvenio;

  /**
   * @var integer
   */
  private $iTipoConvenio;

  /**
   * @var integer
   */
  private $sDigitoCedente;

  /**
   * @var string
   */
  private $sOperacao;

  /**
   * @var string
   */
  private $sEspecie;

  /**
   * @var string
   */
  private $sNomeConvenio;

  function __construct($iCodConvenio) {

  	if (empty($iCodConvenio)) {
      throw new \Exception("Código do convênio não informado.");
  	}

  	$sSqlConvenio  = " select ar12_cadconveniomodalidade,	                                                                         		         ";
  	$sSqlConvenio .= "        ar12_sigla,                                                                                                      ";
    $sSqlConvenio .= "        ar12_sequencial,                                                                                                 ";
  	$sSqlConvenio .= "        ar11_sequencial,                                                         									                       ";
    $sSqlConvenio .= "        ar13_carteira,                                                         						                   			       ";
    $sSqlConvenio .= "        ar13_variacao,                                                                         									         ";
    $sSqlConvenio .= "        ar13_operacao,                                                                                                   ";
    $sSqlConvenio .= "        ar13_cedente,                                                         									                         ";
    $sSqlConvenio .= "        ar13_especie,                                                         									                         ";
    $sSqlConvenio .= "        ar13_digcedente,                                                                                                 ";
    $sSqlConvenio .= "        ar13_convenio,                                                          									                       ";
    $sSqlConvenio .= "        ar16_convenio,                                                          									                       ";
    $sSqlConvenio .= "        ar16_segmento,                                                         									   	                     ";
    $sSqlConvenio .= "        ar16_formatovenc,                                                           									                   ";
    $sSqlConvenio .= "        ar11_nome,                                                           									                           ";
    $sSqlConvenio .= "        case 																											                                                       ";
	  $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_db_bancos  else b.db89_db_bancos								                   ";
 	  $sSqlConvenio .= "        end as codbco, 																								                                                   ";
	  $sSqlConvenio .= "        case 																											                                                       ";
	  $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_codagencia                     								                   ";
	  $sSqlConvenio .= "          else b.db89_codagencia								   									                                                     ";
	  $sSqlConvenio .= "        end as codagencia, 																							                                                 ";
    $sSqlConvenio .= "        case                                                                                                             ";
    $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_digito                                                            ";
    $sSqlConvenio .= "          else b.db89_digito                                                                                             ";
    $sSqlConvenio .= "        end as digagencia                                                                                                ";
    $sSqlConvenio .= "   from cadconvenio                                                               									                     ";
    $sSqlConvenio .= "        inner join cadtipoconvenio     on cadtipoconvenio.ar12_sequencial      = cadconvenio.ar11_cadtipoconvenio        ";
    $sSqlConvenio .= "        left  join conveniocobranca    on conveniocobranca.ar13_cadconvenio    = cadconvenio.ar11_sequencial      	     ";
	  $sSqlConvenio .= "        left  join bancoagencia  a     on a.db89_sequencial                    = conveniocobranca.ar13_bancoagencia	     ";
    $sSqlConvenio .= "        left  join convenioarrecadacao on convenioarrecadacao.ar14_cadconvenio = cadconvenio.ar11_sequencial     		     ";
	  $sSqlConvenio .= "        left  join bancoagencia  b     on b.db89_sequencial                    = convenioarrecadacao.ar14_bancoagencia   ";
    $sSqlConvenio .= "        left  join cadarrecadacao      on cadarrecadacao.ar16_sequencial 		   = convenioarrecadacao.ar14_cadarrecadacao ";
	  $sSqlConvenio .= "  where ar11_sequencial = {$iCodConvenio}																				                                         ";

		$rsConvenio = \db_query($sSqlConvenio);

    if (!$rsConvenio || pg_num_rows($rsConvenio) == 0) {
      throw new \Exception("Erro ao buscar os dados do convênio.");
    }

	  $oConvenio = \db_utils::fieldsMemory($rsConvenio,0);

	  $this->iModalidadeConvenio  = $oConvenio->ar12_cadconveniomodalidade;
	  $this->iCodConvenio  		    = $oConvenio->ar11_sequencial;
	  $this->sSegmento    		    = $oConvenio->ar16_segmento;
	  $this->iFormatoVenc 		    = $oConvenio->ar16_formatovenc;
	  $this->iConvenioArrecadacao = $oConvenio->ar16_convenio;
   	$this->iCodBanco	 	        = $oConvenio->codbco;
   	$this->sCarteira	 	        = $oConvenio->ar13_carteira;
   	$this->sVariacao	 	        = $oConvenio->ar13_variacao;
	  $this->iConvenioCobranca    = $oConvenio->ar13_convenio;
   	$this->sCedente  		        = $oConvenio->ar13_cedente;
   	$this->sDigitoCedente       = $oConvenio->ar13_digcedente;
   	$this->sOperacao            = $oConvenio->ar13_operacao;
   	$this->iTipoConvenio        = $oConvenio->ar12_sequencial;
   	$this->iDigAgencia          = $oConvenio->digagencia;
   	$this->sEspecie             = $oConvenio->ar13_especie;
    $this->sNomeConvenio        = $oConvenio->ar11_nome;

   	if ( $this->iTipoConvenio == static::TIPO_CONVENIO_COMPENSACAO_SICOB ) {
   		if ( $oConvenio->ar13_carteira == '9' ) {
   		  $this->sSiglaTipoConvenio = 'CR';
   		} else {
   			$this->sSiglaTipoConvenio = 'SR';
   		}
   		$this->iCodAgencia   = substr(str_pad($oConvenio->codagencia,5,"0",STR_PAD_LEFT),1,4);

   	} else if ( $this->iTipoConvenio == static::TIPO_CONVENIO_COMPENSACAO_SIGCB ) {

      $this->sSiglaTipoConvenio = $oConvenio->ar12_sigla;
      $this->iCodAgencia   = $oConvenio->codagencia;

      /**
       *  Calcula dígito do cedente
       */
      $sSqlDigCedente  = " select 11 - fc_modulo11('{$oConvenio->ar13_cedente}',2,9) as digito ";
      $rsDigCedente    = \db_query($sSqlDigCedente);
      $iDigitoCendente = \db_utils::fieldsMemory($rsDigCedente,0)->digito;

      if ( $iDigitoCendente > 9 ) {
        $iDigitoCendente = 0;
      }

      $this->sDigitoCedente = $iDigitoCendente;

   	} else {
	   	$this->sSiglaTipoConvenio = $oConvenio->ar12_sigla;
  	  $this->iCodAgencia	 = $oConvenio->codagencia."-".$oConvenio->digagencia;
   	}
  }

  /**
   * @return integer
   */
  public function getModalidadeConvenio() {
    return $this->iModalidadeConvenio;
  }

  /**
   * @param integer iModalidadeConvenio
   */
  public function setModalidadeConvenio($iModalidadeConvenio) {
    $this->iModalidadeConvenio = $iModalidadeConvenio;
  }

  /**
   * @return integer
   */
  public function getCodConvenio() {
    return $this->iCodConvenio;
  }

  /**
   * @param integer iCodConvenio
   */
  public function setCodConvenio($iCodConvenio) {
    $this->iCodConvenio = $iCodConvenio;
  }

  /**
   * @return integer
   */
  public function getFormatoVenc() {
    return $this->iFormatoVenc;
  }

  /**
   * @param integer iFormatoVenc
   */
  public function setFormatoVenc($iFormatoVenc) {
    $this->iFormatoVenc = $iFormatoVenc;
  }

  /**
   * @return string
   */
  public function getSegmento() {
    return $this->sSegmento;
  }

  /**
   * @param string sSegmento
   */
  public function setSegmento($sSegmento) {
    $this->sSegmento = $sSegmento;
  }

  /**
   * @return integer
   */
  public function getCodBanco() {
    return $this->iCodBanco;
  }

  /**
   * @param integer iCodBanco
   */
  public function setCodBanco($iCodBanco) {
    $this->iCodBanco = $iCodBanco;
  }

  /**
   * @return integer
   */
  public function getCodAgencia() {
    return $this->iCodAgencia;
  }

  /**
   * @param integer iCodAgencia
   */
  public function setCodAgencia($iCodAgencia) {
    $this->iCodAgencia = $iCodAgencia;
  }

  /**
   * @return integer
   */
  public function getDigAgencia() {
    return $this->iDigAgencia;
  }

  /**
   * @param integer iDigAgencia
   */
  public function setDigAgencia($iDigAgencia) {
    $this->iDigAgencia = $iDigAgencia;
  }

  /**
   * @return string
   */
  public function getCarteira() {
    return $this->sCarteira;
  }

  /**
   * @param string sCarteira
   */
  public function setCarteira($sCarteira) {
    $this->sCarteira = $sCarteira;
  }

  /**
   * @return string
   */
  public function getVariacao() {
    return $this->sVariacao;
  }

  /**
   * @param string sVariacao
   */
  public function setVariacao($sVariacao) {
    $this->sVariacao = $sVariacao;
  }

  /**
   * @return integer
   */
  public function getConvenioArrecadacao() {
    return $this->iConvenioArrecadacao;
  }

  /**
   * @param integer iConvenioArrecadacao
   */
  public function setConvenioArrecadacao($iConvenioArrecadacao) {
    $this->iConvenioArrecadacao = $iConvenioArrecadacao;
  }

  /**
   * @return integer
   */
  public function getConvenioCobranca() {
    return $this->iConvenioCobranca;
  }

  /**
   * @param integer iConvenioCobranca
   */
  public function setConvenioCobranca($iConvenioCobranca) {
    $this->iConvenioCobranca = $iConvenioCobranca;
  }

  /**
   * @return string
   */
  public function getCedente() {
    return $this->sCedente;
  }

  /**
   * @param string sCedente
   */
  public function setCedente($sCedente) {
    $this->sCedente = $sCedente;
  }

  /**
   * @return string
   */
  public function getSiglaTipoConvenio() {
    return $this->sSiglaTipoConvenio;
  }

  /**
   * @param string sSiglaTipoConvenio
   */
  public function setSiglaTipoConvenio($sSiglaTipoConvenio) {
    $this->sSiglaTipoConvenio = $sSiglaTipoConvenio;
  }

  /**
   * @return integer
   */
  public function getTipoConvenio() {
    return $this->iTipoConvenio;
  }

  /**
   * @param integer iTipoConvenio
   */
  public function setTipoConvenio($iTipoConvenio) {
    $this->iTipoConvenio = $iTipoConvenio;
  }

  /**
   * @return integer
   */
  public function getDigitoCedente() {
    return $this->sDigitoCedente;
  }

  /**
   * @param integer sDigitoCedente
   */
  public function setDigitoCedente($sDigitoCedente) {
    $this->sDigitoCedente = $sDigitoCedente;
  }

  /**
   * @return string
   */
  public function getOperacao() {
    return $this->sOperacao;
  }

  /**
   * @param string sOperacao
   */
  public function setOperacao($sOperacao) {
    $this->sOperacao = $sOperacao;
  }

  /**
   * @return string
   */
  public function getEspecie() {
    return $this->sEspecie;
  }

  /**
   * @param string sEspecie
   */
  public function setEspecie($sEspecie) {
    $this->sEspecie = $sEspecie;
  }

  /**
   * @return string
   */
  public function getNomeConvenio() {
    return $this->sNomeConvenio;
  }

  /**
   * @param string sNomeConvenio
   */
  public function setNomeConvenio($sNomeConvenio) {
    $this->sNomeConvenio = $sNomeConvenio;
  }
}
