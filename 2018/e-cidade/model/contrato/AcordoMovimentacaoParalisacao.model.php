<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/03/14
 * Time: 13:54
 */

class AcordoMovimentacaoParalisacao extends AcordoMovimentacao {

  /**
   * Tipo da movimentacao na inclusão da Situacao
   * @var int
   */
  protected $iTipo = 16;

  /**
   * Tipo da movimentacao no cancelamento da movimentação
   * @var int
   */
  protected $iCodigoCancelamento = 17;

  /**
   * Método construtor
   * Instancia os dados da movimentção passada com parâmetro
   *
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    parent::__construct($iCodigo);
  }

  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @throws Exception
   * @return AcordoMovimentacaoParalisacao
   */
  public function save() {

    parent::save();
    $oDaoAcordoMovimentacao = new cl_acordomovimentacao;
    $oDaoAcordo             = new cl_acordo;

    /**
     * Acerta movimentacao corrente para alterar um movimento anterior
     */
    $sCampos                    = "ac10_sequencial, ac10_acordomovimentacaotipo, ";
    $sCampos                   .= "ac10_acordo, ac09_acordosituacao              ";
    $sWhere                     = "ac10_sequencial = {$this->iCodigo}            ";
    $sOrderBy                   = "ac10_sequencial desc limit 1                  ";
    $sSqlAcordoMovimentacao     = $oDaoAcordoMovimentacao->sql_query_acertaracordo(null, $sCampos, $sOrderBy, $sWhere);

    $rsSqlAcordoMovimentacao    = db_query($sSqlAcordoMovimentacao);
    $iNumRowsAcordoMovimentacao = pg_num_rows($rsSqlAcordoMovimentacao);
    if ($iNumRowsAcordoMovimentacao > 0) {

      /**
       * Altera situacao do movimento
       */
      $oAcordoMovimentacao             = db_utils::fieldsMemory($rsSqlAcordoMovimentacao, 0);
      $oDaoAcordo->ac16_sequencial     = $oAcordoMovimentacao->ac10_acordo;
      $oDaoAcordo->ac16_acordosituacao = $oAcordoMovimentacao->ac09_acordosituacao;
      $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
      if ($oDaoAcordo->erro_status == 0) {
        throw new Exception($oDaoAcordo->erro_msg);
      }
    }
    return $this;
  }

  /**
   * Seta o tipo de acordo para a movimentação, alterado para protected para nao poder atribuir um novo valor
   *
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
    $this->iTipo = 16;
  }

  /**
   * Cancela o movimento
   *
   * @return AcordoMovimentacaoParalisacao
   */
  public function cancelar() {
    parent::cancelar();
    return $this;
  }

} 