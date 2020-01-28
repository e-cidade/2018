<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento;

class Construcao
{
  /**
   * Código da matrícula
   * @var integer
   */
  private $iMatricula = null;

  /**
   * Area de construcao
   * @var integer
   */
  private $iAreaContrucao = null;

  /**
   * Caracteristicas da construção
   * @var array
   */
  private $aCaracteristicas = array();

  /**
   * Id da construcao
   * @var integer
   */
  private $iIdConstrucao = null;

  /**
   * Data da demolição
   * @var DBDate
   */
  private $oDataDemolicao = null;

  /**
   * Código do Idbql
   * @var integer
   */
  private $iIdbql = null;

  /**
   * Código da rua
   * @var integer
   */
  private $iRua = null;

  /**
   * Número da construção
   * @var integer
   */
  private $iNumero = 0;

  /**
   * CGMs que são proprietários da matrícula
   * @var array
   */
  private $aCgm = array();

  /**
   * Retorna o id da construção
   * @return integer
   */
  public function getIdConstrucao(){
    return $this->iIdConstrucao;
  }

  /**
   * Retorna a matrícula do lote
   * @return integer
   */
  public function getMatricula() {
    return $this->iMatricula;
  }

  /**
   * Retorna a Area de Construcao
   * @return integer
   */
  public function getAreaConstrucao() {
    return $this->iAreaContrucao;
  }

  /**
   * Retorna as Caracteristicas
   * @return integer
   */
  public function getCaracteristicas() {
    return $this->aCaracteristicas;
  }

  /**
   * Data da demolição
   * @return DBDate/
   */
  public function getDataDemolicao() {
    return $this->oDataDemolicao;
  }

  /**
   * Retorna o Idbql
   * @return integer
   */
  public function getIdbql() {
    return $this->iIdbql;
  }

  /**
   * Retorna o código da rua
   * @return integer
   */
  public function getRua() {
    return $this->iRua;
  }

  /**
   * Retorna o número da construção
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define o código da matrícula
   * @param integer $iMatricula
   */
  public function setMatricula($iMatricula) {
    $this->iMatricula = $iMatricula;
  }

  /**
   * Define a Area Construcao
   * @param integer
   */
  public function setAreaConstrucao($iAreaContrucao){
    $this->iAreaContrucao = $iAreaContrucao;
  }

  /**
   * Define as caracteristicas
   * @param array
   */
  public function setCaracteristicas($aCaracteristicas){
    $this->aCaracteristicas = $aCaracteristicas;
  }

  /**
   * Define o idconstrucao
   * @param integer
   */
  public function setIdConstrucao($iIdConstrucao){
    $this->iIdConstrucao = $iIdConstrucao;
  }

  /**
   * Define a data de domoliçao
   * @param \DBDate $oDataDemolicao
   */
  public function setDataDemolicao( \DBDate $oDataDemolicao ) {
    $this->oDataDemolicao = $oDataDemolicao;
  }

  /**
   * Define o código do Idbql
   * @param integer $iIdbql
   */
  public function setIdbql( $iIdbql ) {
    $this->iIdbql = $iIdbql;
  }

  /**
   * Define o código da rua
   * @param integer $iRua
   */
  public function setRua( $iRua ) {
    $this->iRua = $iRua;
  }

  /**
   * Define o número da construção
   * @param integer $iNumero
   */
  public function setNumero( $iNumero ) {
    $this->iNumero = $iNumero;
  }

  /**
   * Atualiza os dados do lote da matrícula informada
   */
  public function salvar() {

    if ( empty($this->iIdConstrucao) ) {
      throw new \BusinessException("Código da construção não informado.");
    }

    if ( empty($this->iIdbql) ) {
      throw new \BusinessException("Código do lote não informado.");
    }

    if ( empty($this->iRua) ) {
      throw new \BusinessException("Código da rua não informado.");
    }

    if ( empty($this->iMatricula) ) {

      $this->incluir();
      return;
    }

    $this->alterar();
  }

  /**
   * Altera uma matrícula já existente no sistema.
   */
  private function alterar() {

    $oDaoIptuConstr = new \cl_iptuconstr();
    $oDaoIptuConstr->j39_area   = $this->iAreaContrucao;
    $oDaoIptuConstr->j39_matric = $this->iMatricula;
    $oDaoIptuConstr->j39_idcons = $this->iIdConstrucao;


    if ( !is_null($this->oDataDemolicao) ) {
      $oDaoIptuConstr->j39_dtdemo = $this->oDataDemolicao->getDate();
    }

    $oDaoIptuConstr->alterar($this->iMatricula, $this->iIdConstrucao);

    if ( $oDaoIptuConstr->erro_status == '0' ) {
      throw new \DBException("Erro ao atualizar a construção da matrícula {$this->iMatricula}");
    }

    $this->incluirCaracteristicas();
  }

  /**
   * Inclui uma nova matrícula, uma nova construção e suas característucas
   */
  private function incluir() {

    $this->buscarCGM();
    $this->incluirMatricula();
    $this->incluirConstrucao();
    $this->incluirCaracteristicas();

    if ( !empty( $this->aCgm[1] ) ) {
      $this->incluirOutrosProprietarios();
    }
  }

  /**
   * Busca o CGM da IPTUBase e verifica se ele é único dentro do lote, caso seja o retorna;
   * Se há dois CGM's vinculados ao lote, os retorna sendo o primeiro o principal e o segundo um outro proprietário;
   * Se houver mais de 2 CGM's vinculados ao lote, buscamos um CGM fictício(cadastrado via migration) e o retornamos.
   */
  private function buscarCGM () {

    if (count($this->aCgm) > 0) {
      return $this->aCgm;
    }
    $oDaoIptubase   = new \cl_iptubase();
    $sWhereIptuBase = " j01_idbql = {$this->iIdbql} AND j01_baixa is null ";
    $sSqlIptubase   = $oDaoIptubase->sql_query_file(null, 'distinct j01_numcgm', null, $sWhereIptuBase);
    $rsIptuBase     = db_query($sSqlIptubase);

    if ( !$rsIptuBase || pg_num_rows($rsIptuBase) == 0 ) {
      throw new \DBException("Erro ao buscar o CGM vinculado a matrícula.");
    }

    $iLinhas = pg_num_rows($rsIptuBase);

    switch ($iLinhas) {
      case 1:

        $this->aCgm[] = \db_utils::fieldsMemory( $rsIptuBase, 0)->j01_numcgm;
        break;

      case 2:

        $this->aCgm[] = \db_utils::fieldsMemory( $rsIptuBase, 0)->j01_numcgm;
        $this->aCgm[] = \db_utils::fieldsMemory( $rsIptuBase, 1)->j01_numcgm;
        break;

      default:

        $oDaoCGM = new \cl_cgm();
        $sWhereCGM = " z01_nome = 'RECADASTRAMENTO CIVITAS'";
        $sSqlCGM = $oDaoCGM->sql_query_file( null, "z01_numcgm", null, $sWhereCGM );
        $rsCGM = db_query( $sSqlCGM );

        if ( !$rsCGM || pg_num_rows($rsCGM) == 0 ) {
          throw new \DBException("Erro ao buscar o CGM.");
        }

        $this->aCgm[] = \db_utils::fieldsMemory( $rsCGM, 0 )->z01_numcgm;
        break;
    }
  }

  /**
   * Inclui uma nova matrícula
   */
  private function incluirMatricula() {

    $oDaoIptubase             = new \cl_iptubase();
    $oDaoIptubase->j01_numcgm = $this->aCgm[0];
    $oDaoIptubase->j01_idbql  = $this->iIdbql;
    $oDaoIptubase->incluir(null);

    if ( $oDaoIptubase->erro_status == '0' ) {
      throw new \DBException("Erro ao cadastrar a matrícula.");
    }

    $this->iMatricula = $oDaoIptubase->j01_matric;
  }

  /**
   * Inclui uma nova construção
   */
  private function incluirConstrucao() {

    $oDataLancamento             = new \DBDate( date('Y-m-d') );
    $oDaoIptuConstr              = new \cl_iptuconstr();
    $oDaoIptuConstr->j39_matric  = $this->iMatricula;
    $oDaoIptuConstr->j39_idcons  = $this->iIdConstrucao;
    $oDaoIptuConstr->j39_area    = $this->iAreaContrucao;
    $oDaoIptuConstr->j39_dtlan   = $oDataLancamento->getDate();
    $oDaoIptuConstr->j39_codigo  = $this->iRua;
    $oDaoIptuConstr->j39_numero  = $this->iNumero;
    $oDaoIptuConstr->j39_idprinc = 'false';
    $oDaoIptuConstr->j39_pavim   = '0';
    $oDaoIptuConstr->incluir($this->iMatricula, $this->iIdConstrucao);

    if ( $oDaoIptuConstr->erro_status == '0' ) {
      throw new \DBException("Erro ao cadastrar uma nova construção.");
    }
  }

  /**
   * Remove todas as características da construção e as inclui novamente
   */
  private function incluirCaracteristicas() {

    $oDaoCarconstr = new \cl_carconstr();
    $oDaoCarconstr->excluir($this->iMatricula, $this->iIdConstrucao);

    if ( $oDaoCarconstr->erro_status == '0' ) {
      throw new \DBException("Erro ao excluir as características da construção.");
    }

    foreach ($this->aCaracteristicas as $iCaracteristica) {

      $oDaoCarconstr->incluir($this->iMatricula,$this->iIdConstrucao,$iCaracteristica );

      if ( $oDaoCarconstr->erro_status == '0' ) {
        throw new \DBException("Erro ao incluir as características da construção.");
      }
    }
  }

  /**
   * Inclui um outro proprietário
   */
  private function incluirOutrosProprietarios() {

    $oDaoPropri = new \cl_propri();
    $oDaoPropri->j42_matric = $this->iMatricula;
    $oDaoPropri->j42_numcgm = $this->aCgm[1];
    $oDaoPropri->incluir($this->iMatricula, $this->aCgm[1]);

    if ( $oDaoPropri->erro_status == '0' ) {
      throw new \DBException("Erro ao incluir proprietário.");
    }
  }

  /**
   * @return array
   */
  public function getCgm() {
    return $this->aCgm;
  }

  /**
   * @param array $aCgm
   */
  public function setCgm($aCgm) {
    $this->aCgm = $aCgm;
  }
}