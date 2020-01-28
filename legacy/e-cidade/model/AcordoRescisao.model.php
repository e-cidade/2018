<?
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

/**
 * Model extends metodos e propriedades do model AcordoMovimentacao
 * Model para tratar a recisão dos contratos
 *
 * @package Contratos
 */
require_once(modification("model/AcordoMovimentacao.model.php"));
class AcordoRescisao extends AcordoMovimentacao {

  /**
   * Tipo da Movimentação
   *
   * @var integer
   */
	protected $iTipo               = 6;

  /**
   * Data do Movimento
   *
   * @var string
   */
  protected $dtMovimento         = '';

  /**
   * Código do Movimento de Cancelamento
   *
   * @var integer
   */
	protected $iCodigoCancelamento = 7;

  /**
   * Método construtor
   *
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

  	parent::__construct($iCodigo);
  }

  /**
   * Seta o tipo de acordo para a recisão, alterado para protected para nao poder atribuir um novo valor
   *
   * @return integer $iTipo
   */
  public function setTipo($iTipo) {

  	$this->iTipo = 6;
  }

  /**
   * Seta a data da movimentação
   *
   * @param string $dtMovimento
   */
  public function setDataMovimento($dtMovimento = '') {

    $this->dtMovimento = $dtMovimento;
  }

  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @return AcordoRescisao
   */
  public function save() {

    parent::save();
    $iCodigoAcordo = $this->getAcordo();

    $oDaoAcordo                      = db_utils::getDao("acordo");
    $oDaoAcordo->ac16_sequencial     = $iCodigoAcordo;
    $oDaoAcordo->ac16_acordosituacao = 2;
    $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
    if ($oDaoAcordo->erro_status == 0) {
      throw new Exception($oDaoAcordo->erro_msg);
    }
    $this->corrigeReservas();
    return $this;
  }

  /**
   * Cancela o movimento
   *
   * @return AcordoRescisao
   */
  public function cancelar() {

    parent::cancelar();
  /**
     * verificamos o tipo do acordo
     */
    $oContrato       = new Acordo($this->getAcordo());
    $iOrigemContrato = $oContrato->getOrigem();
    $oUltimaPosicao  = $oContrato->getUltimaPosicao();
    foreach ($oUltimaPosicao->getItens() as $oItem) {

      $oOrigemItem = $oItem->getOrigem();
      if ($oOrigemItem->tipo == 1) {

        /**
         * Verificamos no processo de compras qual o codigo do item da solicitacao.
         */
        $oDaoPcProcitem   = db_utils::getDao("pcprocitem");
        $sSqlDadosDotacao = $oDaoPcProcitem->sql_query_dotacao_reserva($oOrigemItem->codigo, "pcdotac.*, orcreserva.*");
        $rsDotacoes       = $oDaoPcProcitem->sql_record($sSqlDadosDotacao);
        $oDaoReservalSolicitacao = db_utils::getDao("orcreservasol");
        $oDaoReserva             = db_utils::getDao("orcreserva");
        for ($iDot = 0; $iDot < $oDaoPcProcitem->numrows; $iDot++) {

          $oDadosDotacao = db_utils::fieldsMemory($rsDotacoes, $iDot);
          $oDaoReservalSolicitacao->excluir(null, "o82_codres={$oDadosDotacao->o80_codres}");
          if ($oDaoReservalSolicitacao->erro_status == 0) {

            $sErroMsg = "Houve um erro ao remover a reserva de saldo da solicitacao\n{$oDaoReservalSolicitacao->erro_msg}";
            throw new Exception($sErroMsg);
          }
          $oDaoReserva->excluir($oDadosDotacao->o80_codres);
          if ($oDaoReserva->erro_status == 0) {

            $sErroMsg = "Houve um erro ao remover a reserva de saldo da solicitacao\n{$oDaoReserva->erro_msg}";
            throw new Exception($sErroMsg);
          }
        }
        $oItem->reservarSaldos();
      } else if ($oOrigemItem->tipo == 2) {

        $oDaoLiclicitem   = db_utils::getDao("liclicitem");
        $sSqlDadosDotacao = $oDaoLiclicitem->sql_query_dotacao_reserva($oOrigemItem->codigo, "pcdotac.*, orcreserva.*");
        $rsDotacoes       = $oDaoLiclicitem->sql_record($sSqlDadosDotacao);
        $oDaoReservalSolicitacao = db_utils::getDao("orcreservasol");
        $oDaoReserva             = db_utils::getDao("orcreserva");
        for ($iDot = 0; $iDot < $oDaoLiclicitem->numrows; $iDot++) {

          $oDadosDotacao = db_utils::fieldsMemory($rsDotacoes, $iDot);
          $oDaoReservalSolicitacao->excluir(null, "o82_codres={$oDadosDotacao->o80_codres}");
          if ($oDaoReservalSolicitacao->erro_status == 0) {

            $sErroMsg = "Houve um erro ao remover a reserva de saldo da solicitacao\n{$oDaoReservalSolicitacao->erro_msg}";
            throw new Exception($sErroMsg);
          }
          $oDaoReserva->excluir($oDadosDotacao->o80_codres);
          if ($oDaoReserva->erro_status == 0) {

            $sErroMsg = "Houve um erro ao remover a reserva de saldo da solicitacao\n{$oDaoReserva->erro_msg}";
            throw new Exception($sErroMsg);
          }
        }
        $oItem->reservarSaldos();
      }
    }
    return $this;
  }

  /**
   * Cancela o cancelamento da recisão
   *
   * @return AcordoRescisao
   */
  public function desfazerCancelamento() {


    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe Transação Ativa.");
    }

    $iCodigo = $this->iCodigo;
    if (empty($iCodigo)) {
      throw new Exception("Código para o cancelamento não informado!\nCancelamento não efetuado.");
    }

    $iTipo = $this->getTipo();
    if (empty($iTipo)) {
      throw new Exception("Tipo de movimentação não informado!\nCancelamento não efetuado.");
    }

    $iAcordo = $this->getAcordo();
    if (empty($iAcordo)) {
      throw new Exception("Acordo da movimentação não informado!\nCancelamento não efetuado.");
    }

    $oDaoAcordo                        = db_utils::getDao("acordo");
    $oDaoAcordoMovimentacao            = db_utils::getDao("acordomovimentacao");
    $oDaoAcordoMovimentacaoCancela     = db_utils::getDao("acordomovimentacaocancela");

    /**
     * Verifica se já possui movimentação cancelada
     */
    $sWhere                            = "ac25_acordomovimentacao = {$this->iCodigo}";
    $sSqlAcordoMovimentacaoCancela     = $oDaoAcordoMovimentacaoCancela->sql_query(null, "*", null, $sWhere);
    $rsSqlAcordoMovimentacaoCancela    = $oDaoAcordoMovimentacaoCancela->sql_record($sSqlAcordoMovimentacaoCancela);
    $iNumRowsAcordoMovimentacaoCancela = $oDaoAcordoMovimentacaoCancela->numrows;
    if ($iNumRowsAcordoMovimentacaoCancela == 0) {
    	throw new Exception("Não existe cancelamento dessa rescisão!\nProcessamento cancelado.");
    }

    $oMovimentoCancelado = db_utils::fieldsMemory($rsSqlAcordoMovimentacaoCancela, 0);

    /**
     * Inclui uma nova movimentação
     */
    $oDaoAcordoMovimentacao->ac10_acordomovimentacaotipo = 14;
    $oDaoAcordoMovimentacao->ac10_acordo                 = $this->getAcordo();
    $oDaoAcordoMovimentacao->ac10_obs                    = $this->getObservacao();
    $oDaoAcordoMovimentacao->ac10_id_usuario             = db_getsession('DB_id_usuario');
    $oDaoAcordoMovimentacao->ac10_datamovimento          = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoAcordoMovimentacao->ac10_hora                   = db_hora();
    $oDaoAcordoMovimentacao->incluir(null);
    if ($oDaoAcordoMovimentacao->erro_status == 0) {
      throw new Exception($oDaoAcordoMovimentacao->erro_msg);
    }

    /**
     * Inclui um novo cancelamento cancelado de recisão
     */
    $oDaoAcordoMovimentacaoCancela->ac25_acordomovimentacao        = $oDaoAcordoMovimentacao->ac10_sequencial;
    $oDaoAcordoMovimentacaoCancela->ac25_acordomovimentacaocancela = $oMovimentoCancelado->ac25_acordomovimentacao;
    $oDaoAcordoMovimentacaoCancela->incluir(null);
    if ($oDaoAcordoMovimentacaoCancela->erro_status == 0) {
      throw new Exception($oDaoAcordoMovimentacaoCancela->erro_msg);
    }

    /**
     * Altera situacao do movimento
     */
    $oDaoAcordo->ac16_sequencial     = $this->getAcordo();
    $oDaoAcordo->ac16_acordosituacao = 2;
    $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
    if ($oDaoAcordo->erro_status == 0) {
      throw new Exception($oDaoAcordo->erro_msg);
    }
  	$this->corrigeReservas();
    return $this;
  }
}
?>