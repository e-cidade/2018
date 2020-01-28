<?php
namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento;

class Construcao
{
  /**
   * C�digo da matr�cula
   * @var integer
   */
  private $iMatricula = null;

  /**
   * Area de construcao
   * @var integer
   */
  private $iAreaContrucao = null;

  /**
   * Caracteristicas da constru��o
   * @var array
   */
  private $aCaracteristicas = array();

  /**
   * Id da construcao
   * @var integer
   */
  private $iIdConstrucao = null;

  /**
   * Data da demoli��o
   * @var DBDate
   */
  private $oDataDemolicao = null;

  /**
   * C�digo do Idbql
   * @var integer
   */
  private $iIdbql = null;

  /**
   * C�digo da rua
   * @var integer
   */
  private $iRua = null;

  /**
   * N�mero da constru��o
   * @var integer
   */
  private $iNumero = 0;

  /**
   * CGMs que s�o propriet�rios da matr�cula
   * @var array
   */
  private $aCgm = array();

  /**
   * Retorna o id da constru��o
   * @return integer
   */
  public function getIdConstrucao(){
    return $this->iIdConstrucao;
  }

  /**
   * Retorna a matr�cula do lote
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
   * Data da demoli��o
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
   * Retorna o c�digo da rua
   * @return integer
   */
  public function getRua() {
    return $this->iRua;
  }

  /**
   * Retorna o n�mero da constru��o
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Define o c�digo da matr�cula
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
   * Define a data de domoli�ao
   * @param \DBDate $oDataDemolicao
   */
  public function setDataDemolicao( \DBDate $oDataDemolicao ) {
    $this->oDataDemolicao = $oDataDemolicao;
  }

  /**
   * Define o c�digo do Idbql
   * @param integer $iIdbql
   */
  public function setIdbql( $iIdbql ) {
    $this->iIdbql = $iIdbql;
  }

  /**
   * Define o c�digo da rua
   * @param integer $iRua
   */
  public function setRua( $iRua ) {
    $this->iRua = $iRua;
  }

  /**
   * Define o n�mero da constru��o
   * @param integer $iNumero
   */
  public function setNumero( $iNumero ) {
    $this->iNumero = $iNumero;
  }

  /**
   * Atualiza os dados do lote da matr�cula informada
   */
  public function salvar() {

    if ( empty($this->iIdConstrucao) ) {
      throw new \BusinessException("C�digo da constru��o n�o informado.");
    }

    if ( empty($this->iIdbql) ) {
      throw new \BusinessException("C�digo do lote n�o informado.");
    }

    if ( empty($this->iRua) ) {
      throw new \BusinessException("C�digo da rua n�o informado.");
    }

    if ( empty($this->iMatricula) ) {

      $this->incluir();
      return;
    }

    $this->alterar();
  }

  /**
   * Altera uma matr�cula j� existente no sistema.
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
      throw new \DBException("Erro ao atualizar a constru��o da matr�cula {$this->iMatricula}");
    }

    $this->incluirCaracteristicas();
  }

  /**
   * Inclui uma nova matr�cula, uma nova constru��o e suas caracter�stucas
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
   * Busca o CGM da IPTUBase e verifica se ele � �nico dentro do lote, caso seja o retorna;
   * Se h� dois CGM's vinculados ao lote, os retorna sendo o primeiro o principal e o segundo um outro propriet�rio;
   * Se houver mais de 2 CGM's vinculados ao lote, buscamos um CGM fict�cio(cadastrado via migration) e o retornamos.
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
      throw new \DBException("Erro ao buscar o CGM vinculado a matr�cula.");
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
   * Inclui uma nova matr�cula
   */
  private function incluirMatricula() {

    $oDaoIptubase             = new \cl_iptubase();
    $oDaoIptubase->j01_numcgm = $this->aCgm[0];
    $oDaoIptubase->j01_idbql  = $this->iIdbql;
    $oDaoIptubase->incluir(null);

    if ( $oDaoIptubase->erro_status == '0' ) {
      throw new \DBException("Erro ao cadastrar a matr�cula.");
    }

    $this->iMatricula = $oDaoIptubase->j01_matric;
  }

  /**
   * Inclui uma nova constru��o
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
      throw new \DBException("Erro ao cadastrar uma nova constru��o.");
    }
  }

  /**
   * Remove todas as caracter�sticas da constru��o e as inclui novamente
   */
  private function incluirCaracteristicas() {

    $oDaoCarconstr = new \cl_carconstr();
    $oDaoCarconstr->excluir($this->iMatricula, $this->iIdConstrucao);

    if ( $oDaoCarconstr->erro_status == '0' ) {
      throw new \DBException("Erro ao excluir as caracter�sticas da constru��o.");
    }

    foreach ($this->aCaracteristicas as $iCaracteristica) {

      $oDaoCarconstr->incluir($this->iMatricula,$this->iIdConstrucao,$iCaracteristica );

      if ( $oDaoCarconstr->erro_status == '0' ) {
        throw new \DBException("Erro ao incluir as caracter�sticas da constru��o.");
      }
    }
  }

  /**
   * Inclui um outro propriet�rio
   */
  private function incluirOutrosProprietarios() {

    $oDaoPropri = new \cl_propri();
    $oDaoPropri->j42_matric = $this->iMatricula;
    $oDaoPropri->j42_numcgm = $this->aCgm[1];
    $oDaoPropri->incluir($this->iMatricula, $this->aCgm[1]);

    if ( $oDaoPropri->erro_status == '0' ) {
      throw new \DBException("Erro ao incluir propriet�rio.");
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