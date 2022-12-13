<?php
/**
 * Class SituacaoLicitacao
 * Controla as situações da licitação
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
   * Código Sequencial da Situação
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição
   * @var string
   */
  private $sDescricao;

  /**
   * Define se a situação permite alterar os dados da licitação
   * @var bool
   */
  private $lPermiteAlterar;

  /**
   * Define o caminho das mensagens utilizadas pelo objeto
   * @const string
   */
  const URL_MENSAGEM = "patrimonial.licitacao.SituacaoLicitacao.";

  /**
   * Constrói o objeto
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
   * Retorna o código sequencial
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna se é permitido alterar
   * @return boolean
   */
  public function permiteAlterar() {
    return $this->lPermiteAlterar;
  }

  /**
   * Descrição
   * @return string
   */
  public function getSDescricao() {
    return $this->sDescricao;
  }

  /**
   * Verifica se a situação corresponde à uma licitação julgada, considerando situações após julgamento.
   * @return bool
   * @throws ParameterException
   */
  public function isJulgada() {
    return self::isSituacaoJulgada($this->getCodigo());
  }

  /**
   * Verifica se a Situação pertence a uma licitação julgada, considerando situações após julgamento.
   * @param $iSituacao
   *
   * @return bool
   * @throws ParameterException
   */
  public static function isSituacaoJulgada($iSituacao) {

    if (!Check::isInt($iSituacao)) {
      throw new ParameterException("Código da Situação Informado é inválido.");
    }

    $aSituacoesJulgadas = array(self::SITUACAO_JULGADA, self::SITUACAO_ADJUDICADA, self::SITUACAO_HOMOLOGADA);
    return in_array($iSituacao, $aSituacoesJulgadas);
  }
}
