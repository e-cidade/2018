<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * Condicionante para Parecer Técnico
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class Empreendimento {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Nome
   * @var string
   */
  private $sNome = null;

  /**
   * Nome Fantasia
   * @var string
   */
  private $sNomeFantasia = null;

  /**
   * numero
   * @var integer
   */
  private $iNumero = null;

  /**
   * Complemento
   * @var string
   */
  private $sComplemento = null;

  /**
   * CEP
   * @var string
   */
  private $aCep = null;

  /**
   * Bairro
   * @var integer
   */
  private $iBairro = null;

  /**
   * Ruas
   * @var integer
   */
  private $iRuas = null;

  /**
   * CNPJ
   * @var string
   */
  private $sCnpj = null;

  /**
   * cgm
   * @var Cgm
   */
  private $oCgm = null;

  /**
   * Protprocesso
   * @var integer
   */
  private $iProtocolo = null;

  /**
   * Area Total
   * @var integer
   */
  private $nAreaTotal = null;

  /**
   * Método construtor
   */
  public function __construct( $iSequencial = null ) {

    $oDaoEmpreendimento = new cl_empreendimento();
    $rsEmpreendimento   = null;

    if (!empty($iSequencial)) {

      $sSql             = $oDaoEmpreendimento ->sql_query($iSequencial);
      $rsEmpreendimento = $oDaoEmpreendimento ->sql_record($sSql);
    }

    if (!empty($rsEmpreendimento)) {

      $oDados = db_utils::fieldsMemory($rsEmpreendimento, 0);

      $this->iSequencial   = $oDados->am05_sequencial;
      $this->sNome         = $oDados->am05_nome;
      $this->sNomeFantasia = $oDados->am05_nomefanta;
      $this->iNumero       = $oDados->am05_numero;
      $this->sComplemento  = $oDados->am05_complemento;
      $this->sCep          = $oDados->am05_cep;
      $this->iBairro       = $oDados->am05_bairro;
      $this->iRuas         = $oDados->am05_ruas;
      $this->sCnpj         = $oDados->am05_cnpj;
      $this->oCgm          = CgmFactory::getInstanceByCgm($oDados->am05_cgm);
      $this->nAreaTotal    = $oDados->am05_areatotal;
      $this->iProtocolo    = $oDados->am05_protprocesso;
    }
  }

  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Altera o Nome
   * @param string
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Busca o Nome
   * @return string
   */
  public function getNome () {
    return $this->sNome;
  }

  /**
   * Altera o Nome Fantasia
   * @param string
   */
  public function setNomeFantasia ($sNomeFantasia) {
    $this->sNomeFantasia = $sNomeFantasia;
  }

  /**
   * Busca o Nome Fantasia
   * @return $sNomeFantasia
   */
  public function getNomeFantasia () {
    return $this->sNomeFantasia;
  }

  /**
   * Altera o Numero
   * @param integer
   */
  public function setNumero ($iNumero) {
    $this->iNumero = $iNumero;
  }

  /**
   * Busca o Numero
   * @return integer
   */
  public function getNumero () {
    return $this->iNumero;
  }

  /**
   * Altera o Complemento
   * @param string
   */
  public function setComplemento ($sComplemento) {
    $this->sComplemento = $sComplemento;
  }

  /**
   * Busca o Complemento
   * @return string
   */
  public function getComplemento () {
    return $this->sComplemento;
  }

  /**
   * Altera o CEP
   * @param string
   */
  public function setCep ($sCep) {
    $this->sCep = $sCep;
  }

  /**
   * Busca o CEP
   * @return string
   */
  public function getCep () {
    return $this->sCep;
  }

  /**
   * Altera o bairro
   * @param integer
   */
  public function setBairro ($iBairro) {
    $this->iBairro = $iBairro;
  }

  /**
   * Busca o bairro
   * @return integer
   */
  public function getBairro () {
    return $this->iBairro;
  }

  /**
   * Altera a rua
   * @param integer
   */
  public function setRuas ($iRuas) {
    $this->iRuas = $iRuas;
  }

  /**
   * Busca a rua
   * @return integer
   */
  public function getRuas () {
    return $this->iRuas;
  }

  /**
   * Altera o CNPJ
   * @param string
   */
  public function setCnpj ($sCnpj) {
    $this->sCnpj = $sCnpj;
  }

  /**
   * Busca o CNPJ
   * @return string
   */
  public function getCnpj () {
    return $this->sCnpj;
  }

  /**
   * Altera o CGM
   * @param Cgm
   */
  public function setCgm ($oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Busca o CGM
   * @return Cgm
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Altera a Area Total
   * @param numeric
   */
  public function setAreaTotal ($nAreaTotal) {
    $this->nAreaTotal = $nAreaTotal;
  }

 /**
   * Busca a Area Total do empreendimento
   * @return numeric
   */
  public function getAreaTotal() {
    return $this->nAreaTotal;
  }

  /**
   * Set Protocolo
   * @param integer
   */
  public function setProtocolo ($iProtocolo) {
    $this->iProtocolo = $iProtocolo;
  }

  /**
   * @return integer
   */
  public function getProtocolo() {
    return $this->iProtocolo;
  }

  /**
   * Busca condicionantes vinculadas a atividade e ao tipo de licença
   * @param  integer $iTipoLicenca Tipo de licença
   * @return array
   */
  public function getCondicionantes( $iTipoLicenca ) {

    $oDaoEmpreedimento = db_utils::getDao("empreendimento");
    $sSql              = $oDaoEmpreedimento->sql_query_condicionante(  $this->iSequencial, $iTipoLicenca );
    $rsEmpreedimento   = $oDaoEmpreedimento->sql_record( $sSql );

    if( $rsEmpreedimento ){

      $aCondicionantes = db_utils::getCollectionByRecord( $rsEmpreedimento, true, false, true );
    }

    return $aCondicionantes;
  }

  /**
   * Buscamos a última licenca válida para emissão deste empreendimento
   *
   * @return object/boolean
   */
  public function getLicencaValida() {

    try {

      $oDaoEmpreedimento = new cl_empreendimento();
      $sSqlLicenca       = $oDaoEmpreedimento->sql_query_licenca($this->iSequencial);
      $rsLicenca         = $oDaoEmpreedimento->sql_record($sSqlLicenca);

      if (empty($rsLicenca)) {
        return false;
      }

      $oLicenca = db_utils::fieldsMemory($rsLicenca, 0);

      return $oLicenca;
    } catch (Exception $oErro) {
      throw $oErro;
    }
  }

  /**
   * Funçao que altera ou inclui um empreendimento
   *
   * @throws Exception
   */
  public function processar() {

    try {

      $oDaoEmpreendimento = db_utils::getDao('empreendimento');

      $oDaoEmpreendimento->am05_nome         = $this->sNome;
      $oDaoEmpreendimento->am05_nomefanta    = $this->sNomeFantasia;
      $oDaoEmpreendimento->am05_numero       = $this->iNumero;
      $oDaoEmpreendimento->am05_complemento  = $this->sComplemento;
      $oDaoEmpreendimento->am05_cep          = $this->sCep;
      $oDaoEmpreendimento->am05_bairro       = $this->iBairro;
      $oDaoEmpreendimento->am05_ruas         = $this->iRuas;
      $oDaoEmpreendimento->am05_cnpj         = $this->sCnpj;
      $oDaoEmpreendimento->am05_cgm          = $this->oCgm->getCodigo();
      $oDaoEmpreendimento->am05_areatotal    = $this->nAreaTotal;
      $oDaoEmpreendimento->am05_protprocesso = $this->iProtocolo;

      if ( empty( $this->iSequencial ) ) {

        $oDaoEmpreendimento->incluir();
        $this->iSequencial = $oDaoEmpreendimento->am05_sequencial;
      } else {
        $oDaoEmpreendimento->alterar($this->iSequencial);
      }

      if ( $oDaoEmpreendimento->erro_status == "0" ) {
        throw new Exception( 'erro_incluir_empreendimento' );
      }

    } catch (Exception $oErro) {
      throw $oErro;
    }
  }
}