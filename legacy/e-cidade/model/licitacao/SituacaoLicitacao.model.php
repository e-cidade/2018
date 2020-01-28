<?php
/**
 * Class SituacaoLicitacao
 * Controla as situa��es da licita��o
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package licitacao
 * @version $Revision: 1.8 $
 */
class SituacaoLicitacao {

  const SITUACAO_EM_ANDAMENTO = 0;
  const SITUACAO_JULGADA      = 1;
  const SITUACAO_REVOGADA     = 2;
  const SITUACAO_DESERTA      = 3;
  const SITUACAO_FRACASSADA   = 4;
  const SITUACAO_ANULADA      = 5;
  const SITUACAO_ADJUDICADA   = 6;
  const SITUACAO_HOMOLOGADA   = 7;

  /**
   * C�digo Sequencial da Situa��o
   * @var integer
   */
  private $iCodigo;

  /**
   * Descri��o
   * @var string
   */
  private $sDescricao;

  /**
   * Define se a situa��o permite alterar os dados da licita��o
   * @var bool
   */
  private $lPermiteAlterar;

  /**
   * Define o caminho das mensagens utilizadas pelo objeto
   * @const string
   */
  const URL_MENSAGEM = "patrimonial.licitacao.SituacaoLicitacao.";

  /**
   * Constr�i o objeto
   * @param integer $iCodigo
   * @throws BusinessException
   */
  public function __construct($iCodigo) {

    $oDaoLicSituacao   = new cl_licsituacao();
    $sSqlBuscaSituacao = $oDaoLicSituacao->sql_query_file($iCodigo);
    $rsBuscaSituacao   = $oDaoLicSituacao->sql_record($sSqlBuscaSituacao);
    if ($oDaoLicSituacao->erro_status == "0") {
      throw new BusinessException(_M(self::URL_MENSAGEM."licitacao_nao_encontrada"));
    }

    $oStdSituacao          = db_utils::fieldsMemory($rsBuscaSituacao, 0);
    $this->iCodigo         = $iCodigo;
    $this->sDescricao      = $oStdSituacao->l08_descr;
    $this->lPermiteAlterar = $oStdSituacao->l08_altera == "t" ? true : false;
    unset($oStdSituacao);
  }

  /**
   * Retorna o c�digo sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna se � permitido alterar
   * @return boolean
   */
  public function permiteAlterar() {
    return $this->lPermiteAlterar;
  }

  /**
   * Descri��o
   * @return string
   */
  public function getSDescricao() {
    return $this->sDescricao;
  }

  /**
   * Verifica se a situa��o corresponde � uma licita��o julgada, considerando situa��es ap�s julgamento.
   * @return bool
   * @throws ParameterException
   */
  public function isJulgada() {
    return self::isSituacaoJulgada($this->getCodigo());
  }

  /**
   * Verifica se a Situa��o pertence a uma licita��o julgada, considerando situa��es ap�s julgamento.
   * @param $iSituacao
   *
   * @return bool
   * @throws ParameterException
   */
  public static function isSituacaoJulgada($iSituacao) {

    if (!Check::isInt($iSituacao)) {
      throw new ParameterException("C�digo da Situa��o Informado � inv�lido.");
    }

    $aSituacoesJulgadas = array(self::SITUACAO_JULGADA, self::SITUACAO_ADJUDICADA, self::SITUACAO_HOMOLOGADA);
    return in_array($iSituacao, $aSituacoesJulgadas);
  }
}
