<?php
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


require_once( modification( 'libs/exceptions/BusinessException.php' ) );
require_once( modification( 'libs/exceptions/DBException.php' ) );

/**
 * Cancelamento ISSQN Variável
 *
 * @package ISSQN
 */
class CancelamentoISSQNVariavel {

  const SITUACAO_INCLUIR         = 'incluir';
  const SITUACAO_EXCLUIR         = 'excluir';

  private static $aObservacoes   = array(
    'SEM_MOVIMENTO' => 'Cancelado pelo NFS-e devido a competência ter sido encerrada sem movimentação',
    'SEM_IMPOSTO'   => 'Cancelado pelo NFS-e devido a competência ter sido encerrada sem imposto'
  );

  /**
   * Observação/Histórico
   *
   * @var string
   * @access protected
   */
  protected $sObservacao;

  /**
   * Empresa dos Débitos (Inscrição Municipal)
   *
   * @var Empresa
   * @access protected
   */
  protected $oEmpresa;

  /**
   * Débitos que seram utilizados no model
   *
   * @var array
   * @access protected
   */
  protected $aDebitos = array();

  /**
   * Retorna a observação/histórico
   *
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define a observação/histórico
   *
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna dados da empresa
   *
   * @return Empresa
   */
  public function getEmpresa() {
    return $this->oEmpresa;
  }

  /**
   * Define a empresa
   *
   * @param object $oEmpresa
   * @throws BusinessException
   */
  public function setEmpresa($oEmpresa) {
    $this->oEmpresa = $oEmpresa;
  }

  /**
   * Metodo responsável por excluir os cancelamentos
   *
   * @throws DBException
   * @return boolean
   */
  public function excluirCancelamento() {

    if (count($this->aDebitos) > 0) {

      foreach ($this->aDebitos as $oDebito) {

        $sSqlCancDebReg    = "select                                                                 \n";
        $sSqlCancDebReg   .= "  k21_sequencia as cancdebitosreg,                                     \n";
        $sSqlCancDebReg   .= "  k24_sequencia as cancdebitosprocreg,                                 \n";
        $sSqlCancDebReg   .= "  k21_codigo    as cancdebitos,                                        \n";
        $sSqlCancDebReg   .= "  k24_codigo    as cancdebitosproc                                     \n";
        $sSqlCancDebReg   .= "from                                                                   \n";
        $sSqlCancDebReg   .= "  cancdebitosreg                                                       \n";
        $sSqlCancDebReg   .= "  inner join cancdebitosprocreg on k24_cancdebitosreg = k21_sequencia  \n";
        $sSqlCancDebReg   .= "where                                                                  \n";
        $sSqlCancDebReg   .= "  k21_numpre = {$oDebito->iNumpre} and                                 \n";
        $sSqlCancDebReg   .= "  k21_numpar = {$oDebito->iNumpar}                                     \n";

        $rResultCancDebReg = db_query($sSqlCancDebReg);
        $iLinhasCancDebReg = pg_num_rows($rResultCancDebReg);

        if ($rResultCancDebReg && $iLinhasCancDebReg > 0) {

          $oCancDebReg = db_utils::fieldsMemory($rResultCancDebReg, 0);

          $sSqlDeb     = "SELECT * FROM cancdebitosreg WHERE k21_codigo = {$oCancDebReg->cancdebitos}";
          $rResultDeb  = db_query($sSqlDeb);
          $iLinhasDeb  = pg_num_rows($rResultDeb);

          if ($iLinhasDeb = 0) {

            $oDaoCancDebitos = new cl_cancdebitos();
            $oDaoCancDebitos->excluir($iCancDebitos);

            if ($oDaoCancDebitos->erro_status == 0) {
              throw new DBException("Exclusão da cancdebitos: {$oDaoCancDebitos->erro_sql}");
            }
          }

          // Exclusão da cancdebitosproc
          $sSqlProcReg     = "SELECT * FROM cancdebitosprocreg WHERE k24_codigo = {$oCancDebReg->cancdebitosproc}";
          $rResultProcReg  = db_query($sSqlProcReg);
          $iLinhasProcReg  = pg_num_rows($rResultProcReg);

          if ($iLinhasProcReg = 0) {

            $oDaoCancDebitosProc = new cl_cancdebitosproc();
            $oDaoCancDebitosProc->excluir($cancdebitosproc2[$a]);

            if ($oDaoCancDebitosProc->erro_status == 0) {
              throw new DBException("Exclusão da cancdebitosproc: {$oDaoCancDebitosProc->erro_sql}");
            }
          }

          // Exclusão da cancdebitosprocreg
          $oDaoCancDebitosProcReg = new cl_cancdebitosprocreg;
          $oDaoCancDebitosProcReg->excluir(null, "k24_cancdebitosreg = {$oCancDebReg->cancdebitosreg}");

          if ($oDaoCancDebitosProcReg->erro_status == 0) {
            throw new DBException("Exclusão da cancdebitosprocreg: {$oDaoCancDebitosProcReg->erro_sql}");
          }

          // Exclusão da cancdebitosreg
          $oDaoCancDebitosReg = new cl_cancdebitosreg;
          $oDaoCancDebitosReg->excluir($oCancDebReg->cancdebitosreg);

          if ($oDaoCancDebitosReg->erro_status == 0) {
            throw new DBException("Exclusão da cancdebitosreg: {$oDaoCancDebitosReg->erro_sql}");
          }
        }

        $sSqlIssVar    = "select                                                  \n";
        $sSqlIssVar   .= "  q15_issvarsemmov as issvarsemmov,                     \n";
        $sSqlIssVar   .= "  q15_sequencial   as issvarsemmovreg,                  \n";
        $sSqlIssVar   .= "  q05_codigo                                            \n";
        $sSqlIssVar   .= "from                                                    \n";
        $sSqlIssVar   .= "  issvar                                                \n";
        $sSqlIssVar   .= "  inner join issvarsemmovreg on q05_codigo = q15_issvar \n";
        $sSqlIssVar   .= "where                                                   \n";
        $sSqlIssVar   .= "  q05_numpre = {$oDebito->iNumpre} and                  \n";
        $sSqlIssVar   .= "  q05_numpar = {$oDebito->iNumpar}                      \n";

        $rResultIssVar = db_query($sSqlIssVar);
        $iLinhasIssVar = pg_num_rows($rResultIssVar);

        if ($rResultIssVar && $iLinhasIssVar > 0) {

          $oIssVar = db_utils::fieldsMemory($rResultIssVar, 0);

          // Exclusão da issvarsemmov
          $sSqlSemMovReg    = "SELECT * FROM  issvarsemmovreg WHERE q15_issvarsemmov = {$oIssVar->issvarsemmov}";
          $rResultSemMovReg = db_query($sSqlSemMovReg);
          $iLinhasSemMovReg = pg_num_rows($rResultSemMovReg);

          if ($iLinhasSemMovReg = 0) {

            $oDaoIssVarSemMov = new cl_issvarsemmov();
            $oDaoIssVarSemMov->excluir($iIssVarSemMov);

            if ($oDaoIssVarSemMov->erro_status == 0) {
              throw new DBException("Exclusão da issvarsemmov: {$oDaoIssVarSemMov->erro_sql}");
            }
          }

          // Exclusão da issvarsemmovreg
          $oDaoIssVarSemMovReg = new cl_issvarsemmovreg;
          $oDaoIssVarSemMovReg->excluir($oIssVar->issvarsemmovreg);

          if ($oDaoIssVarSemMovReg->erro_status == 0) {
            throw new DBException("Exclusão da issvarsemmovreg: {$oDaoIssVarSemMovReg->erro_sql}");
          }
        }

        // Exclusão da arrecant
        $oDaoArreCant = new cl_arrecant;
        $oDaoArreCant->excluir_arrecant($oDebito->iNumpre, $oDebito->iNumpar, 0);

        if ($oDaoArreCant->erro_status == 0) {
          throw new DBException("Exclusão da arrecant: {$oDaoArreCant->erro_sql}");
        }
      }
    }

    return true;
  }

  /**
   * Metodo responsavel por incluir os cancelamentos
   *
   * @param  sObservacao  Observação do cancelamento
   * @throws DBException
   * @return boolean
   */
  public function incluirCancelamento( $sObservacao = null ) {

    if ( !empty($sObservacao) ) {
      $this->setObservacao(self::$aObservacoes[$sObservacao]);
    }

    $sData                                = date('Y-m-d', db_getsession('DB_datausu'));
    $sHora                                = db_hora();
    $iCodigoUsuario                       = db_getsession('DB_id_usuario');
    $iCodigoInstituicao                   = db_getsession('DB_instit');

    $oDaoCancDebitos                      = new cl_cancdebitos;
    $oDaoCancDebitos->k20_instit          = $iCodigoInstituicao;
    $oDaoCancDebitos->k20_descr           = "CANCELAMENTO DE ISSQN VARIAVEL INSCRIÇÃO: {$this->oEmpresa->getInscricao()}";
    $oDaoCancDebitos->k20_hora            = $sHora;
    $oDaoCancDebitos->k20_data            = $sData;
    $oDaoCancDebitos->k20_usuario         = $iCodigoUsuario;
    $oDaoCancDebitos->k20_cancdebitostipo = 1;

    $oDaoCancDebitos->incluir(null);

    if ($oDaoCancDebitos->erro_status == '0') {
      throw new DBException ($oDaoCancDebitos->erro_sql);
    }

    $oDaoCancDebitosProc                      = new cl_cancdebitosproc;
    $oDaoCancDebitosProc->k23_data            = $sData;
    $oDaoCancDebitosProc->k23_hora            = $sHora;
    $oDaoCancDebitosProc->k23_usuario         = $iCodigoUsuario;
    $oDaoCancDebitosProc->k23_obs             = $this->getObservacao();
    $oDaoCancDebitosProc->k23_cancdebitostipo = 1;

    $oDaoCancDebitosProc->incluir(null);

    if ($oDaoCancDebitosProc->erro_status == '0') {
      throw new DBException ($oDaoCancDebitosProc->erro_sql);
    }

    $oDaoIssVarSemMov               = new cl_issvarsemmov;
    $oDaoIssVarSemMov->q08_usuario  = $iCodigoUsuario;
    $oDaoIssVarSemMov->q08_data     = $sData;
    $oDaoIssVarSemMov->q08_hora     = $sHora;
    $oDaoIssVarSemMov->q08_tipolanc = '0';
    $oDaoIssVarSemMov->incluir(null);

    if ($oDaoIssVarSemMov->erro_status == '0') {
      throw new DBException ($oDaoIssVarSemMov->erro_sql);
    }

    if (count($this->aDebitos) > 0) {

      foreach ($this->aDebitos as $oDebito) {

        $oDaoParIssqn                 = new cl_parissqn;
        $sSqlParIssqn                 = $oDaoParIssqn->sql_query_file('', 'q60_histsemmov');
        $rsParIssqn                   = $oDaoParIssqn->sql_record($sSqlParIssqn);
        $iCodigoHistoricoSemMov       = db_utils::fieldsMemory($rsParIssqn, 0)->q60_histsemmov;

        $oDaoArreHist                 = new cl_arrehist;
        $oDaoArreHist->k00_numpre     = $oDebito->iNumpre;
        $oDaoArreHist->k00_numpar     = $oDebito->iNumpar;
        $oDaoArreHist->k00_hist       = $iCodigoHistoricoSemMov;
        $oDaoArreHist->k00_dtoper     = $sData;
        $oDaoArreHist->k00_hora       = $sHora;
        $oDaoArreHist->k00_id_usuario = $iCodigoUsuario;
        $oDaoArreHist->k00_histtxt    = $this->getObservacao();
        $oDaoArreHist->incluir(null);

        if ($oDaoArreHist->erro_status == '0') {
          throw new DBException($oDaoArreHist->erro_sql);
        }

        $oDaoArrecad = new cl_arrecad();

        $sWhere      = "k00_numpre = {$oDebito->iNumpre} and k00_numpar = {$oDebito->iNumpar} ";
        $sSqlArrecad = $oDaoArrecad->sql_query_file(null, 'k00_receit', null, $sWhere);
        $rsArrecad   = $oDaoArrecad->sql_record($sSqlArrecad);

        if ($oDaoArrecad->erro_status == '0') {
          throw new DBException($oDaoArrecad->erro_sql);
        }

        if ($rsArrecad && $oDaoArrecad->numrows > 0) {

          $iCodigoReceita                 = db_utils::fieldsMemory($rsArrecad, 0)->k00_receit;

          $oDaoCancDebitosReg             = new cl_cancdebitosreg;
          $oDaoCancDebitosReg->k21_codigo = $oDaoCancDebitos->k20_codigo;
          $oDaoCancDebitosReg->k21_numpre = $oDebito->iNumpre;
          $oDaoCancDebitosReg->k21_numpar = $oDebito->iNumpar;
          $oDaoCancDebitosReg->k21_receit = $iCodigoReceita;
          $oDaoCancDebitosReg->k21_data   = $sData;
          $oDaoCancDebitosReg->k21_hora   = $sHora;
          $oDaoCancDebitosReg->k23_obs    = $this->getObservacao();
          $oDaoCancDebitosReg->incluir(null);

          if ($oDaoCancDebitosReg->erro_status == '0') {
            throw new DBException($oDaoCancDebitosReg->erro_sql);
          }

          $oDaoCancDebitosProcReg                     = new cl_cancdebitosprocreg;
          $oDaoCancDebitosProcReg->k24_codigo         = $oDaoCancDebitosProc->k23_codigo;
          $oDaoCancDebitosProcReg->k24_cancdebitosreg = $oDaoCancDebitosReg->k21_sequencia;
          $oDaoCancDebitosProcReg->k24_vlrhis         = '0';
          $oDaoCancDebitosProcReg->k24_vlrcor         = '0';
          $oDaoCancDebitosProcReg->k24_juros          = '0';
          $oDaoCancDebitosProcReg->k24_multa          = '0';
          $oDaoCancDebitosProcReg->k24_desconto       = '0';
          $oDaoCancDebitosProcReg->incluir(null);

          if ($oDaoCancDebitosProcReg->erro_status == '0') {
            throw new DBException($oDaoCancDebitosProcReg->erro_sql);
          }

        } else {
          throw new BusinessException("Nenhum Débito encontrado para o Numpre: {$oDebito->iNumpre}");
        }

        $oDaoIssVar = new cl_issvar();

        $sWhere       = "q05_numpre = {$oDebito->iNumpre} and q05_numpar = {$oDebito->iNumpar}";
        $sSqlParIssqn = $oDaoIssVar->sql_query_file(null, 'q05_codigo', null, $sWhere);
        $rsParIssqn   = $oDaoIssVar->sql_record($sSqlParIssqn);

        if ($oDaoIssVar->erro_status == '0') {
          throw new DBException($oDaoIssVar->erro_sql);
        }

        if ($rsParIssqn && $oDaoIssVar->numrows > 0) {

          $iCodigoIssVar = db_utils::fieldsMemory($rsParIssqn, 0)->q05_codigo;

          // incluir issvarsemmovreg
          $oDaoIssVarSemMovReg                   = new cl_issvarsemmovreg;
          $oDaoIssVarSemMovReg->q15_issvarsemmov = $oDaoIssVarSemMov->q08_sequencial;
          $oDaoIssVarSemMovReg->q15_issvar       = $iCodigoIssVar;
          $oDaoIssVarSemMovReg->incluir(null);

          if ($oDaoIssVarSemMovReg->erro_status == '0') {
            throw new DBException($oDaoIssVarSemMovReg->erro_sql);
          }
        }

        $oDaoArreCant = new cl_arrecant;
        $oDaoArreCant->incluir_arrecant($oDebito->iNumpre, $oDebito->iNumpar, 0, true);

        if ($oDaoArreCant->erro_status == '0') {
          throw new DBException($oDaoArreCant->erro_sql);
        }
      }
    }

    return true;
  }

  /**
   * Método para incluir os debitos à cancelar ou cancelar o cancelamento.
   *
   * @param integer $iNumpre
   * @param integer $iNumpar
   * @throws BusinessException
   */
  public function addDebito($iNumpre, $iNumpar) {

    if (!$iNumpre) {
      throw new BusinessException('Numpre não informada');
    }

    if (!$iNumpar) {
      throw new BusinessException('Numpar não informada');
    }

    $oDebito          = new stdClass();
    $oDebito->iNumpre = $iNumpre;
    $oDebito->iNumpar = $iNumpar;

    $this->aDebitos[] = $oDebito;
  }

  /**
   * Método para buscar os debitos à cancelar ou cancelar o cancelamento por inscrição municipal.
   *
   * @param string $iMes
   * @param string $iAno
   * @throws BusinessException
   * @return Ambigous <boolean, stdClass>
   */
  public function getDebitosInscricaoMunicipal($iMes = null, $iAno = null, $sSituacao) {

    if (!is_object($this->oEmpresa)) {
      throw new BusinessException('Empresa não informada!');
    }

    if (!$iMes) {
      throw new BusinessException('Mês não informado!');
    }

    if (!$iAno) {
      throw new BusinessException('Ano não informado!');
    }

    $oIssVar = new cl_issvar();

    return $oIssVar->getDadosCompetenciaSituacaoInscricao($iMes, $iAno, $this->oEmpresa->getInscricao(), $sSituacao);
  }
}

?>