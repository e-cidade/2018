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

/**
 * Classe representa a folha de pagamento do suplementar
 *
 * @author $Author: dbandrio.costa $
 * @version $Revision: 1.9 $
 */

class FolhaPagamentoSuplementar extends FolhaPagamento {

  //@todo criar arquivo de mensagens
  const MENSAGENS = 'recursoshumanos.pessoal.FolhaPagamentoSuplementar.';

  function __construct($iSequencial = null) {
    parent::__construct($iSequencial, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR);
  }

  /**
   * Retorna a ultima folha aberta do tipo suplementar
   *
   * @example FolhaPagamentoSuplementar::getFolhaAberta()
   * @return FolhaPagamentoSuplementar
   */
  public static function getFolhaAberta(DBCompetencia $oCompetencia = null) {

    $iCodigoFolha = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, true, $oCompetencia);

    if ($iCodigoFolha){
      return new FolhaPagamentoSuplementar($iCodigoFolha);
    }
    return false;
  }

  /**
   * Retorna se há uma folha aberta.
   *
   * @example FolhaPagamentoSuplementar::hasFolhaAberta()
   * @return  boolean
   */
  public static function hasFolhaAberta( DBCompetencia $oCompetencia = null) {
    return FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, $oCompetencia);
  }

  /**
   * Verifica se existe algum registro do tipo folha salario na
   * competencia passada por parametro ou caso não seja passado
   * pega a competencia atual
   *
   * @param DBCompetencia $oCompetencia Opcional
   * @return Boolean
   */
  public static function hasFolha(DBCompetencia $oCompetencia = null) {

    if ($oCompetencia) {
      return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, $oCompetencia);
    }

    return FolhaPagamento::hasFolhaTipo(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR,
      new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha())
    );
  }

  /**
   * Retorna a última folha da competencia informada ou da ultia competência.
   * @param  DBCompetencia $oCompetencia
   * @return FolhaPAgamentoSuplementar Última folha retornada.
   */
  public static function getUltimaFolha( DBCompetencia $oCompetencia = null ) {
    return new FolhaPagamentoSuplementar(FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, null, $oCompetencia) );
  }

  /**
   * Retorna o ultimo número unico da folha pagamento, conforme o tipo passado.
   *
   * @example  FolhaPagamento:getProximoNumero(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR)
   * @return   Integer  Próximo numero de folha suplementar
   */
  public static function getProximoNumero() {
    return FolhaPagamento::getProximoNumero(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR);
  }

  /**
   * Verifica se existe pelo menos um registro na tabela pontofs.
   * @return boolean
   */
  public function pesquisarPonto(){

    $oDaoPontofs = new cl_pontofs();

    $sWherePonto  = "     r10_anousu = {$this->getCompetencia()->getAno()} ";
    $sWherePonto .= "and  r10_mesusu = {$this->getCompetencia()->getMes()} ";
    $sWherePonto .= "and  r10_instit = {$this->getINstituicao()->getSequencial()} ";

    $sSqlPontofs = $oDaoPontofs->sql_query_file(null, null, null, null, "r10_rubric", null, $sWherePonto);
    $rsPontoFs   = db_query($sSqlPontofs);

    if (!$rsPontoFs) {
      throw new DBException(_M(self::MENSAGENS . "erro_ponto"));
    }

    if (pg_num_rows($rsPontoFs) != 0) {
      return true;
    }

    return false;
  }


  public function fechar() {

    /**
     * Verifica se a folha não está aberta
     */
    if (!$this->isAberto()){
      throw new DBException( _M(self::MENSAGENS . "fechamento_folha_fechada"));
    }

    /**
     * Verifica se existe pelo menos um registro
     * para a folha complementar.
     */
    $aServidoresPontofs = ServidorRepository::getServidoresNoPontoPorFolhaPagamento($this);

    if ( count($aServidoresPontofs) == 0 ) {
      throw new BusinessException(_M(self::MENSAGENS . "sem_registro_ponto"));
    }

    /**
     * Remove os pontos lançados para a folha atual
     */
    $oDaoPontofs  = new cl_pontofs;
    $sWhere       = "     r10_anousu = {$this->getCompetencia()->getAno()}";
    $sWhere      .= " AND r10_mesusu = {$this->getCompetencia()->getMes()}";
    $sWhere      .= " AND r10_instit = {$this->getInstituicao()->getSequencial()}";
    $oDaoPontofs->excluir(null, null, null, null, $sWhere);

    /**
     * Faz update no semest para ficar com o
     * numero atual da folha de pagamento.
     */
    $oDaoGerfSal             = new cl_gerfsal();
    $oDaoGerfSal->r14_anousu = $this->getCompetencia()->getAno();
    $oDaoGerfSal->r14_mesusu = $this->getCompetencia()->getMes();
    $oDaoGerfSal->r14_semest = $this->getNumero();

    $oDaoGerfSal->alterar($this->getCompetencia()->getAno(), $this->getCompetencia()->getMes());

    $this->fecharFolha();

    return true;

  }

  public function cancelarAbertura(){

    $oDaoAssentaLoteregistroponto = new cl_assentaloteregistroponto;

    /**
     * Recupera os lotes vinculados a esta folha para removê-los
     */
    $oDaoLoteregistropontoRhfolhapagamento   = new cl_loteregistropontorhfolhapagamento;
    $sWhereLoteregistropontoRhfolhapagamento = " rh162_rhfolhapagamento = ". $this->getSequencial();
    $sSqlLoteregistropontoRhfolhapagamento   = $oDaoLoteregistropontoRhfolhapagamento->sql_query_file(null, "rh162_sequencial, rh162_loteregistroponto", null, $sWhereLoteregistropontoRhfolhapagamento);
    $rsLoteregistropontoRhfolhapagamento     = db_query($sSqlLoteregistropontoRhfolhapagamento);

    if(is_resource($rsLoteregistropontoRhfolhapagamento) && pg_num_rows($rsLoteregistropontoRhfolhapagamento) > 0) {

      for ($iIndLotesregistroponto=0; $iIndLotesregistroponto < pg_num_rows($rsLoteregistropontoRhfolhapagamento) ; $iIndLotesregistroponto++) {

        /**
         * Remove a ligação entre lote e a folha de pagamento
         */
        $oDaoLoteregistropontoRhfolhapagamento->excluir(db_utils::fieldsMemory($rsLoteregistropontoRhfolhapagamento, $iIndLotesregistroponto)->rh162_sequencial);

        /**
         * Remove a ligação entre o lote e um assentamento de substituição
         */
        $oDaoAssentaLoteregistroponto->excluir(null, "rh160_loteregistroponto = ". db_utils::fieldsMemory($rsLoteregistropontoRhfolhapagamento, $iIndLotesregistroponto)->rh162_loteregistroponto);

        /**
         * Remove os lotes de registros vinculados a esta folha
         */
        LoteRegistrosPontoRepository::remover(LoteRegistrosPontoRepository::getInstanceByCodigo(db_utils::fieldsMemory($rsLoteregistropontoRhfolhapagamento, $iIndLotesregistroponto)->rh162_loteregistroponto));
      }
    }

    /**
     * Verifica se a folha esta aberta.
     */
    if (!$this->isAberto()) {
      throw new BusinessException(_M(self::MENSAGENS . 'folha_fechada'));
    }

    /**
     * Remove os calculos lançados para a folha atual
     */
    $oDaoGerfSal = new cl_gerfsal();

    $sWhereGerfSal  = "     r14_anousu = {$this->getCompetencia()->getAno()}";
    $sWhereGerfSal .= " and r14_mesusu = {$this->getCompetencia()->getMes()}";
    $sWhereGerfSal .= " and r14_semest = {$this->getNumero()}";
    $sWhereGerfSal .= " and r14_instit = {$this->getInstituicao()->getSequencial()}";

    $oDaoGerfSal->excluir(null, null, null, null, $sWhereGerfSal);

    if ($oDaoGerfSal->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_gerfsal'));
    }

    /**
     * Remove os pontos lançados para a folha atual
     */
    $oDaoPontofs = new cl_pontofs;

    $sWherePontofs  = "     r10_anousu = {$this->getCompetencia()->getAno()}";
    $sWherePontofs .= " and r10_mesusu = {$this->getCompetencia()->getMes()}";
    $sWherePontofs .= " and r10_instit = {$this->getInstituicao()->getSequencial()}";

    $oDaoPontofs->excluir(null, null, null, null, $sWherePontofs);

    if ($oDaoPontofs->erro_status == "0") {
      throw new DBException(_M(self::MENSAGENS . 'erro_excluir_pontofs'));
    }

    $this->excluir();

    $oFolhaAnterior = FolhaPagamentoSuplementar::getUltimaFolha();

    if (!!$oFolhaAnterior->getSequencial()) {

      // $oFolhaAnterior->retornarCalculo();
      $oDaoGerfSal             = new cl_gerfsal();
      $oDaoGerfSal->r14_anousu = $oFolhaAnterior->getCompetencia()->getAno();
      $oDaoGerfSal->r14_mesusu = $oFolhaAnterior->getCompetencia()->getMes();
      $oDaoGerfSal->r14_semest = $oFolhaAnterior->getNumero();
      /**
       * Faz update no semest para ficar com o
       * numero atual da folha de pagamento.
       */
      $oDaoGerfSal->alterar($oFolhaAnterior->getCompetencia()->getAno(), $oFolhaAnterior->getCompetencia()->getMes());
    }

    /**
     * Retorna o cálculo das demais folhas suplementares/salário, caso existam.
     */
    $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
    $sSqlEventosFinanceiros = $oDaoRhHistoricoCalculo->sql_query_eventosfinanceiros_fechados($this->getCompetencia());
    $rsHistoricoCalculo    = db_query($sSqlEventosFinanceiros);

    if (!$rsHistoricoCalculo) {
      throw new DBException(_M(self::MENSAGENS . 'erro_buscar_historicocalculo'));
    }

    $aEventosFinanceiros = array();

    /**
     * Percorre os eventos financeiros retornados da consulta, criando os seus respectivos eventos financeiros
     */
    for ($iEventoFinanceiro = 0; $iEventoFinanceiro < pg_num_rows($rsHistoricoCalculo); $iEventoFinanceiro++) {

      $oHistorico = db_utils::fieldsMemory($rsHistoricoCalculo, $iEventoFinanceiro);
      $oServidor  = ServidorRepository::getInstanciaByCodigo($oHistorico->rh143_regist, $this->getCompetencia()->getAno(), $this->getCompetencia()->getMes(), $this->getInstituicao()->getSequencial());

      $oEventoFinanceiro = new EventoFinanceiroFolha();
      $oEventoFinanceiro->setServidor($oServidor);
      $oEventoFinanceiro->setRubrica(RubricaRepository::getInstanciaByCodigo($oHistorico->rh143_rubrica));
      $oEventoFinanceiro->setValor($oHistorico->rh143_valor);
      $oEventoFinanceiro->setQuantidade($oHistorico->rh143_quantidade);
      $oEventoFinanceiro->setNatureza($oHistorico->rh143_tipoevento);
      $oEventoFinanceiro->setCalculo( new CalculoFolhaSalario($oServidor) ) ;

      $aEventosFinanceiros[$oServidor->getMatricula()][] = $oEventoFinanceiro;
    }

    /**
     * Percorre os eventos financeiros, persistindo os dados no banco.
     */
    while (list($iMatricula, $aEventosFinanceirosServidor) = each($aEventosFinanceiros)) {

      $oCalculoFolhaSalario = new CalculoFolhaSalario($aEventosFinanceirosServidor[0]->getServidor());

      foreach ($aEventosFinanceirosServidor as $oEventoFinanceiro) {
        $oCalculoFolhaSalario->adicionarEvento($oEventoFinanceiro);
      }

      $oCalculoFolhaSalario->salvar();
    }

    return true;
  }

  /**
   * Retorna todas as folhas suplementares fechadas na compentência.
   *
   * @param  DBCompetencia $oCompetencia Competencia da Folha
   * @return FolhaPagamentoSuplementar[]
   */
  public static function getFolhasFechadasCompetencia( DBCompetencia $oCompetencia ) {
    return FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR);
  }
}
