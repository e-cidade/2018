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
 * Classe abstrata para tratar uma movimentação
 *
 * @package Contratos
 */
abstract class AcordoMovimentacao {

  /**
   * Código para Mapeamento ORM
   *
   * @var integer
   */
  protected $iCodigo             = null;

  /**
   * Data do Movimento
   *
   * @var string
   */
  protected $dtMovimento         = '';

  /**
   * Hora do Movimento
   *
   * @var string
   */
  protected $sHora               = '';

  /**
   * Observação
   *
   * @var string
   */
  protected $sObservacao         = '';

  /**
   * Código do Usuário
   *
   * @var integer
   */
  protected $iUsuario            = null;

  /**
   * Código do Acordo
   *
   * @var integer
   */
  protected $iAcordo             = null;

  /**
   * Tipo da Movimentação
   *
   * @var integer
   */
  protected $iTipo               = null;

  /**
   * Código do Movimento de Cancelamento
   *
   * @var integer
   */
  protected $iCodigoCancelamento = null;

  /**
   * Constante do caminho da mensagem do model
   * @var string
   */
  const CAMINHO_MENSAGENS = 'patrimonial.contratos.AcordoMovimentacao.';

  /**
   * Retorna a data de movimentação
   *
   * @return string
   */
  public function getDataMovimento() {

    return $this->dtMovimento;
  }

  /**
   * Retorna o código de movimentação
   *
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * Retorna o id do usuário logado no sistema
   *
   * @return integer
   */
  public function getUsuario() {

    return $this->iUsuario;
  }

  /**
   * Retorna a hora atual
   *
   * @return string
   */
  public function getHora() {

    return $this->sHora;
  }

  /**
   * Retorna o acordo
   *
   * @return integer
   */
  public function getAcordo() {

    return $this->iAcordo;
  }

  /**
   * Retorna o tipo de acordo
   *
   * @return integer
   */
  public function getTipo() {

    return $this->iTipo;
  }

  /**
   * Retorna a observação da movimentação
   *
   * @return string
   */
  public function getObservacao() {

    return $this->sObservacao;
  }

  /**
   * Seta o acordo de movimentação
   *
   * @param integer $iAcordo
   */
  public function setAcordo($iAcordo) {

    $this->iAcordo = $iAcordo;
  }

  /**
   * Seta o tipo de acordo para a movimentação
   *
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {

    $this->iTipo = $iTipo;
  }

  /**
   * Seta a observação da movimentação
   *
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
  }

  /**
   * Método construtor
   *
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

    	$oDaoAcordoMovimentacao     = db_utils::getDao("acordomovimentacao");
    	$sWhere                     = "ac10_sequencial = {$iCodigo}";
    	$sSqlAcordoMovimentacao     = $oDaoAcordoMovimentacao->sql_query(null, "*", null, $sWhere);
    	$rsSqlAcordoMovimentacao    = $oDaoAcordoMovimentacao->sql_record($sSqlAcordoMovimentacao);
    	$iNumRowsAcordoMovimentacao = $oDaoAcordoMovimentacao->numrows;

      if ($iNumRowsAcordoMovimentacao == 0) {
        throw new Exception("Nenhum Registro Encontrado para o Código de Movimentação {$iCodigo}!");
      }

      $oAcordoMovimentacao = db_utils::fieldsMemory($rsSqlAcordoMovimentacao, 0);

      $this->iCodigo       = $oAcordoMovimentacao->ac10_sequencial;
      $this->dtMovimento   = $oAcordoMovimentacao->ac10_datamovimento;
      $this->sHora         = $oAcordoMovimentacao->ac10_hora;
      $this->iUsuario      = $oAcordoMovimentacao->ac10_id_usuario;
      $this->setObservacao($oAcordoMovimentacao->ac10_obs);
      $this->setAcordo($oAcordoMovimentacao->ac10_acordo);
      $this->setTipo($oAcordoMovimentacao->ac10_acordomovimentacaotipo);
    }
  }

  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @return AcordoMovimentacao
   */
  public function save() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Não existe transação Ativa.");
    }

  	$iTipo = $this->getTipo();
    if (empty($iTipo)) {
      throw new Exception("Tipo de movimentação não informado!\nInclusão não efetuada.");
    }

    $iAcordo = $this->getAcordo();
    if (empty($iAcordo)) {
      throw new Exception("Acordo da movimentação não informado!\nInclusão não efetuada.");
    }

    $oDaoAcordoMovimentacao                              = new cl_acordomovimentacao();
    $oDaoAcordoMovimentacao->ac10_acordomovimentacaotipo = $this->getTipo();
    $oDaoAcordoMovimentacao->ac10_acordo                 = $this->getAcordo();
    $oDaoAcordoMovimentacao->ac10_obs                    = $this->getObservacao();

    $iCodigo = $this->iCodigo;
    if (!empty($iCodigo)) {

    	/**
    	 * Altera movimentacao corrente
    	 */
    	$oDaoAcordoMovimentacao->ac10_sequencial             = $this->iCodigo;
      $oDaoAcordoMovimentacao->ac10_id_usuario             = $this->iUsuario;
      $oDaoAcordoMovimentacao->ac10_datamovimento          = $this->dtMovimento;
      $oDaoAcordoMovimentacao->ac10_hora                   = $this->sHora;
      $oDaoAcordoMovimentacao->alterar($oDaoAcordoMovimentacao->ac10_sequencial);
      if ($oDaoAcordoMovimentacao->erro_status == 0) {
        throw new Exception($oDaoAcordoMovimentacao->erro_msg);
      }

    } else {

      if ($this->possuiMovimentacaoCorrente()) {
        throw new Exception("Acordo {$this->getAcordo()} já possui movimentação corrente!\nInclusão não efetuada.");
      }

      /**
       * Inclui uma nova movimentação
       */
	    $oDaoAcordoMovimentacao->ac10_id_usuario        = db_getsession('DB_id_usuario');
	    $oDaoAcordoMovimentacao->ac10_datamovimento     = date("Y-m-d",db_getsession("DB_datausu"));
	    $oDaoAcordoMovimentacao->ac10_hora              = db_hora();
    	$oDaoAcordoMovimentacao->incluir(null);
      if ($oDaoAcordoMovimentacao->erro_status == 0) {
        throw new Exception($oDaoAcordoMovimentacao->erro_msg);
      }
      $this->iCodigo = $oDaoAcordoMovimentacao->ac10_sequencial;
    }

    return $this;

  }

  public function possuiMovimentacaoCorrente() {

    $iAcordo = $this->getAcordo();
    $iTipo = $this->getTipo();

    if (empty($iAcordo) || empty($iTipo)) {
      return false;
    }

    $oDaoAcordoMovimentacao = new cl_acordomovimentacao();

    $aCampos = array(
        "ac10_acordomovimentacaotipo",
        "ac10_sequencial",
        "ac25_acordomovimentacao",
        "ac25_acordomovimentacaocancela",
        "ac10_acordo"
      );

    $aWhere = array(
        "ac10_acordo = {$iAcordo}",
        "ac10_acordomovimentacaotipo = {$iTipo}",
        "ac10_acordomovimentacaotipo not in (16,17,18,19)"
      );

    $sSqlMovimentacao = $oDaoAcordoMovimentacao->sql_query_verificacancelado(null, implode(',', $aCampos), null, implode(" and ", $aWhere));
    $rsMovimentacao   = $oDaoAcordoMovimentacao->sql_record($sSqlMovimentacao);

    if (!$rsMovimentacao || $oDaoAcordoMovimentacao->numrows == 0) {
      return false;
    }

    for ($iInd = 0; $iInd < $oDaoAcordoMovimentacao->numrows; $iInd++) {

      $oMovimentacao = db_utils::fieldsMemory($rsMovimentacao, $iInd);

      if (empty($oMovimentacao->ac25_acordomovimentacaocancela)) {
        return true;
      }
    }
  }

  /**
   * Cancela o movimento na base de dados
   *
   * @return AcordoMovimentacao
   */
  public function cancelar() {

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
    $sCampos                           = "ac10_sequencial, ac25_acordomovimentacao,              ";
    $sCampos                          .= "ac25_acordomovimentacaocancela, ac10_acordo            ";
    $sWhere                            = "    ac10_sequencial             = {$this->iCodigo}     ";
    $sWhere                           .= "and ac10_acordomovimentacaotipo = {$this->getTipo()}   ";
    $sWhere                           .= "and ac10_acordo                 = {$this->getAcordo()} ";
    $sSqlAcordoMovimentacaoCancela     = $oDaoAcordoMovimentacao->sql_query_verificacancelado(null, $sCampos,
                                                                                              null, $sWhere);
    $rsSqlAcordoMovimentacaoCancela    = $oDaoAcordoMovimentacao->sql_record($sSqlAcordoMovimentacaoCancela);
    $iNumRowsAcordoMovimentacaoCancela = $oDaoAcordoMovimentacao->numrows;

    if ($iNumRowsAcordoMovimentacaoCancela > 0) {

    	/**
       * Se já possuir movimentacao, não efetua o cancelamento
       */
      $oAcordoMovimentacaoCancela = db_utils::fieldsMemory($rsSqlAcordoMovimentacaoCancela, 0);
      if (!empty($oAcordoMovimentacaoCancela->ac25_acordomovimentacaocancela)) {
        throw new Exception("O movimento {$this->iCodigo} já foi cancelado!\nCancelamento não efetuado.");
      }
    }

    /**
     * Inclui uma nova movimentação
     */
    $oDaoAcordoMovimentacao->ac10_acordomovimentacaotipo = $this->iCodigoCancelamento;
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
     * Inclui um novo cancelamento
     */
    $oDaoAcordoMovimentacaoCancela->ac25_acordomovimentacao        = $oDaoAcordoMovimentacao->ac10_sequencial;
    $oDaoAcordoMovimentacaoCancela->ac25_acordomovimentacaocancela = $this->iCodigo;
    $oDaoAcordoMovimentacaoCancela->incluir(null);
    if ($oDaoAcordoMovimentacaoCancela->erro_status == 0) {
      throw new Exception($oDaoAcordoMovimentacaoCancela->erro_msg);
    }

    /**
     * Acerta movimentacao corrente para alterar um movimento anterior
     */
    $sCampos                    = "ac10_sequencial, ac10_acordomovimentacaotipo, ";
    $sCampos                   .= "ac10_acordo, ac09_acordosituacao              ";
    $sWhere                     = "ac10_sequencial < {$this->iCodigo} and ac10_acordo = " . $this->iAcordo;
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
   * corrige as reservas de saldo
   *
   */
  protected function corrigeReservas() {

    $oContrato       = new Acordo($this->getAcordo());
    $iOrigemContrato = $oContrato->getOrigem();
    $oUltimaPosicao  = $oContrato->getUltimaPosicao();
    $iAnousu        = db_getsession("DB_anousu");
    foreach ($oUltimaPosicao->getItens() as $oItem) {

      $oItem->removerReservas();
      if ($iOrigemContrato == 1  || $iOrigemContrato == 2) {

        $oOrigemItem = $oItem->getOrigem();
        if ($oOrigemItem->tipo == 1) {

          /**
           * Verificamos no processo de compras qual o codigo do item da solicitacao.
           */
          $oDaoPcProcitem   = db_utils::getDao("pcprocitem");
          $sSqlDadosDotacao = $oDaoPcProcitem->sql_query_dotac($oOrigemItem->codigo, "pcdotac.*");
          $rsDotacoes       = $oDaoPcProcitem->sql_record($sSqlDadosDotacao);

          $oDaoReservalSolicitacao = db_utils::getDao("orcreservasol");
          $oDaoReserva             = db_utils::getDao("orcreserva");
          if ($oDaoPcProcitem->numrows > 0) {

            for ($iDot = 0; $iDot < $oDaoPcProcitem->numrows; $iDot++) {

              $oDotacao       = db_utils::fieldsMemory($rsDotacoes, $iDot);
              $oDotacaoSaldo  = new Dotacao($oDotacao->pc13_coddot, $oDotacao->pc13_anousu);
              $nSaldoReservar = $oDotacao->pc13_valor;
              if (round($oDotacaoSaldo->getSaldoFinal() <= $oDotacao->pc13_valor, 2)) {
                $nSaldoReservar = $oDotacaoSaldo->getSaldoFinal();
              }
              if ($nSaldoReservar > 0 && $oDotacao->pc13_anousu >= $iAnousu) {

                $oDaoReserva->o80_anousu = $oDotacao->pc13_anousu;
                $oDaoReserva->o80_coddot = $oDotacao->pc13_coddot;
                $oDaoReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_dtfim  = "{$oDotacao->pc13_anousu}-12-31";
                $oDaoReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_valor  = $nSaldoReservar;
                $oDaoReserva->o80_descr  = "reserva de saldo";
                $oDaoReserva->incluir(null);

                if ($oDaoReserva->erro_status == 0) {

                  $sMessage = "Erro ao reservar saldo!\n{$oDaoReserva->erro_msg}";
                  throw new Exception($sMessage);
                }

                $oDaoReservalSolicitacao->o82_codres    = $oDaoReserva->o80_codres;
                $oDaoReservalSolicitacao->o82_solicitem = $oDotacao->pc13_codigo;
                $oDaoReservalSolicitacao->o82_pcdotac   = $oDotacao->pc13_sequencial;
                $oDaoReservalSolicitacao->incluir(null);
                if ($oDaoReservalSolicitacao->erro_status == 0) {

                  $sMessage = "Erro ao reservar saldo!\n{$oDaoReservalSolicitacao->erro_msg}";
                  throw new Exception($sMessage);
                }
              }
            }
          }
        } else if ($oOrigemItem->tipo == 2) {

          /**
           * Verificamos na licitacao qual o codigo do item da solicitacao.
           */
          $oDaoLicLicitem  = db_utils::getDao("liclicitem");
          $sSqlDadosDotacao = $oDaoLicLicitem->sql_query_orc($oOrigemItem->codigo, "pcdotac.*");
          $rsDotacoes       = $oDaoLicLicitem->sql_record($sSqlDadosDotacao);
          $oDaoReservalSolicitacao = db_utils::getDao("orcreservasol");
          $oDaoReserva             = db_utils::getDao("orcreserva");
          if ($oDaoLicLicitem->numrows > 0) {

            for ($iDot = 0; $iDot < $oDaoLicLicitem->numrows; $iDot++) {

              $oDotacao       = db_utils::fieldsMemory($rsDotacoes, $iDot);
              $oDotacaoSaldo  = new Dotacao($oDotacao->pc13_coddot, $oDotacao->pc13_anousu);
              $nSaldoReservar = $oDotacao->pc13_valor;
              if (round($oDotacaoSaldo->getSaldoFinal() <= $oDotacao->pc13_valor, 2)) {
                $nSaldoReservar = $oDotacaoSaldo->getSaldoFinal();
              }
              if ($nSaldoReservar > 0 && $oDotacao->pc13_anousu >= $iAnousu) {

                $oDaoReserva->o80_anousu = $oDotacao->pc13_anousu;
                $oDaoReserva->o80_coddot = $oDotacao->pc13_coddot;
                $oDaoReserva->o80_dtini  = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_dtfim  = "{$oDotacao->pc13_anousu}-12-31";
                $oDaoReserva->o80_dtlanc = date("Y-m-d", db_getsession("DB_datausu"));
                $oDaoReserva->o80_valor  = $nSaldoReservar;
                $oDaoReserva->o80_descr  = "reserva de saldo";
                $oDaoReserva->incluir(null);

                if ($oDaoReserva->erro_status == 0) {

                  $sMessage = "Erro ao reservar saldo!\n{$oDaoReserva->erro_msg}";
                  throw new Exception($sMessage);
                }

                $oDaoReservalSolicitacao->o82_codres    = $oDaoReserva->o80_codres;
                $oDaoReservalSolicitacao->o82_solicitem = $oDotacao->pc13_codigo;
                $oDaoReservalSolicitacao->o82_pcdotac   = $oDotacao->pc13_sequencial;
                $oDaoReservalSolicitacao->incluir(null);
                if ($oDaoReservalSolicitacao->erro_status == 0) {

                  $sMessage = "Erro ao reservar saldo!\n{$oDaoReservalSolicitacao->erro_msg}";
                  throw new Exception($sMessage);
                }
              }
            }
          }
        }
      }
    }
  }

  /**
   * Remove um acordomovimentacao
   *
   * @throws DBException
   * @throws BusinessException
   */
  public function remover() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::CAMINHO_MENSAGENS."sem_transacao_ativa" ) );
    }

    if ( $this->getCodigo() == null ) {
      throw new BusinessException( _M( self::CAMINHO_MENSAGENS."sequencial_nao_existente" ) );
    }

    $oDaoAcordoMovimentacao = new cl_acordomovimentacao();
    $oDaoAcordoMovimentacao->excluir( $this->getCodigo() );

    if ( $oDaoAcordoMovimentacao->erro_status == 0 ) {
      throw new BusinessException( $oDaoAcordoMovimentacao->erro_msg );
    }
  }
}
?>