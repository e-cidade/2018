<?php

class ProporcionalizacaoPontoSalario {

  /**
   * Ponto do Funcionário que será proporcionalizado.
   *
   * @var PontoSalario
   */
  private $oPonto;

  private $aSituacoesAfastamento = array(
    Afastamento::AFASTADO_SEM_REMUNERACAO,
    Afastamento::AFASTADO_ACIDENTE_TRABALHO_MAIS_15_DIAS,
    Afastamento::AFASTADO_SERVICO_MILITAR,
    Afastamento::AFASTADO_LICENCA_GESTANTE,
    Afastamento::AFASTADO_DOENCA_MAIS_15_DIAS,
    Afastamento::AFASTADO_DOENCA_MAIS_30_DIAS,
    Afastamento::LICENCA_SEM_VENCIMENTO
  );

  public function __construct(PontoSalario $oPontoSalario, $iSituacaoFuncionario, DBDate $oDataRetorno = null ) {

    $this->oPonto               = $oPontoSalario;
    $this->oServidor            = $oPontoSalario->getServidor();
    $this->iSituacaoFuncionario = $iSituacaoFuncionario;
    $this->oDataRetorno         = $oDataRetorno;
  }


  /**
   * Executa a proporcionalização
   *
   * - Busca todos os registros do ponto de salário com rubricas proporcionalizaveis
   * - Verifica quantida/valor que estão lancados
   * - realiza a proporção dos dias em que o servidor não está afastado.
   * - salva as novas quantidades/valores que no ponto
   *
   * @return Boolean - Confirmação do processamento
   */
  public function processar() {

    if( !in_array($this->iSituacaoFuncionario,$this->aSituacoesAfastamento) ){
      return false;
    }

    /**
     * Verificamos se existe uma folha de salário aberta para a competência atual, 
     * caso sejá utilizado a estrutura da suplementar, se não possui a proporcionalização
     * não é realizada.
     */
    if(DBPessoal::verificarUtilizacaoEstruturaSuplementar() && !FolhaPagamentoSalario::hasFolhaAberta()){
      return true;
    } 


    $clpontofx = new cl_pontofx();
    $clpontofs = new cl_pontofs();

    $result_pontofx  = $clpontofx->sql_record($clpontofx->sql_query_file(db_anofolha(),db_mesfolha(),$this->oServidor->getMatricula()));
    $numrows_pontofx = $clpontofx->numrows;

    $subpes = db_anofolha();
    $subpes.= db_mesfolha();

    global $dias_pagamento, $data_afastamento, $dtfim,$subpes;

    $dias_pagamento        = $this->getDiasTrabalhados();
    $oDiasAfastado         = $this->verificarRubricasAfastamento();
    $aAfastamentosDoenca   = array(Afastamento::AFASTADO_DOENCA_MAIS_15_DIAS, Afastamento::AFASTADO_DOENCA_MAIS_30_DIAS);

    $lAfastadoPorDoenca    = !(in_array($this->iSituacaoFuncionario, $aAfastamentosDoenca) && $oDiasAfastado->saude == 0);
    $lAfastadoMaternidade  = !($this->iSituacaoFuncionario == Afastamento::AFASTADO_LICENCA_GESTANTE && $oDiasAfastado->maternidade == 0);
    $lAfastadoAcidente     = !($this->iSituacaoFuncionario == Afastamento::AFASTADO_ACIDENTE_TRABALHO_MAIS_15_DIAS && $oDiasAfastado->acidente == 0);
    $lAfastadoSemRemuneracao = $this->iSituacaoFuncionario == Afastamento::AFASTADO_SEM_REMUNERACAO;
    $lLicencaSemVencimento   = $this->iSituacaoFuncionario == Afastamento::LICENCA_SEM_VENCIMENTO;

    $aSituacoesDescartadas = array(
      Afastamento::AFASTADO_DOENCA_MAIS_15_DIAS,
      Afastamento::AFASTADO_LICENCA_GESTANTE,
      Afastamento::AFASTADO_ACIDENTE_TRABALHO_MAIS_15_DIAS,
      Afastamento::AFASTADO_DOENCA_MAIS_30_DIAS
    );

    /**
     * @FIXME - Lógica do IF para estar igual  AQUI (!$lAfastadoPorDoenca  || !$lAfastadoMaternidade || !$lAfastadoAcidente ) e 
     * @FIXME - AQUI !in_array($this->iSituacaoFuncionario, $aSituacoesDescartadas) 
     */
    if ( $dias_pagamento > 0 && ( (!$lAfastadoPorDoenca  || !$lAfastadoMaternidade || !$lAfastadoAcidente ) || !in_array($this->iSituacaoFuncionario, $aSituacoesDescartadas) ) ) {

      for ( $i=0; $i < $numrows_pontofx; $i++) {

        $oRegistroPonto = db_utils::fieldsMemory($result_pontofx, $i);
        $oRurbrica      = RubricaRepository::getInstanciaByCodigo($oRegistroPonto->r90_rubric);

        $valor_ponto    = $oRegistroPonto->r90_valor;
        $quant_ponto    = $oRegistroPonto->r90_quant;

        if($oRegistroPonto->r90_valor > 0  && $oRurbrica->proporcionalizarMedias() ){
          $valor_ponto = ($oRegistroPonto->r90_valor / 30) * $dias_pagamento;
        }

        if ( $oRegistroPonto->r90_quant > 0 && $oRurbrica->proporcionalizarMedias() ) {
          $quant_ponto = ($oRegistroPonto->r90_quant / 30) * $dias_pagamento;
        }

        $altinclui      = true;
        $result_pontofs = $clpontofs->sql_record($clpontofs->sql_query_file(db_anofolha(),db_mesfolha(),$this->oServidor->getMatricula(),$oRegistroPonto->r90_rubric));

        if ($clpontofs->numrows == 0) {
          $altinclui = false;
        }

        $clpontofs->r10_anousu = db_anofolha();
        $clpontofs->r10_mesusu = db_mesfolha();
        $clpontofs->r10_regist = $this->oServidor->getMatricula();
        $clpontofs->r10_rubric = $oRegistroPonto->r90_rubric;
        $clpontofs->r10_valor  = "round($valor_ponto,2)";
        $clpontofs->r10_quant  = "round($quant_ponto,2)";
        $clpontofs->r10_lotac  = $oRegistroPonto->r90_lotac;
        $clpontofs->r10_datlim = $oRegistroPonto->r90_datlim;
        $clpontofs->r10_instit = db_getsession("DB_instit");

        if ($altinclui == true) {
          $clpontofs->alterar(db_anofolha(),db_mesfolha(),$this->oServidor->getMatricula(),$oRegistroPonto->r90_rubric);
        }else{
          $clpontofs->incluir(db_anofolha(),db_mesfolha(),$this->oServidor->getMatricula(),$oRegistroPonto->r90_rubric);
        }

        if($clpontofs->erro_status=="0"){
          throw new DBException($clpontofs->erro_msg);
        }
      }
    } else if ( $lLicencaSemVencimento || $lAfastadoSemRemuneracao || !$lAfastadoPorDoenca  || !$lAfastadoMaternidade || !$lAfastadoAcidente ) {

      $clpontofs->excluir( db_anofolha(), db_mesfolha(), $this->oServidor->getMatricula(), null);

      if ($clpontofs->erro_status=="0") {
        throw new DBException($clpontofs->erro_msg);
      }

      /**
       * Realizmos a proporcionalização na tabela rhhistoricoponto, 
       * caso sejá utilizado a nova estrutura da suplementar.
       */
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        
        $iSequencialFolhaAberta = FolhaPagamentoSalario::getFolhaAberta()->getSequencial();
        $oDaoRhHistoricoPonto   = new cl_rhhistoricoponto();
        $sWhereRhHistoricoPonto = "rh144_folhapagamento = {$iSequencialFolhaAberta} and rh144_regist = {$this->oServidor->getMatricula()}";
        $oDaoRhHistoricoPonto->excluir(null, $sWhereRhHistoricoPonto);

        if ($oDaoRhHistoricoPonto->erro_status == "0") {
          throw new DBException($oDaoRhHistoricoPonto->erro_msg);
        }
      } 
    }

    return true;
  }

  /**
   * Retorna os dias trabalhados no mês
   * @return int
   * @throws \DBException
   */
  private function getDiasTrabalhados() {

    $lUtilizaDiasDoMesBase = false;
    if( is_null($this->oDataRetorno) || $this->oDataRetorno->getDate() > $this->getDataUltimoDiasDoMes()->getDate() ) {
      $lUtilizaDiasDoMesBase = true;
    }

    $iMatricula            = $this->oServidor->getMatricula();
    $iAnoFolha             = DBPessoal::getAnoFolha();
    $iMesFolha             = DBPessoal::getMesFolha();
    $iInstituicao          = db_getsession('DB_instit');
    $sUtilizaDiasNoMesBase = $lUtilizaDiasDoMesBase ? 'true' : 'false';
    $rsDiasTrabalhados = db_query("select fc_dias_trabalhados({$iMatricula},{$iAnoFolha},{$iMesFolha},{$sUtilizaDiasNoMesBase},{$iInstituicao}) as dias_pagamento");
    if (!$rsDiasTrabalhados) {
      throw new DBException("Erro ao Buscar os dados dos dias trabalhados.");
    }

    if( pg_num_rows($rsDiasTrabalhados) == 0 ) {
      return 0;
    }

    return db_utils::fieldsMemory($rsDiasTrabalhados, 0)->dias_pagamento;
  }

  /**
   * Retorna o código da tabela de previdencia do funcionario, variando a diferenda da faixa do IRRF
   * @return Integer
   */
  private function getTabelaPrevidenciaUsoInterno() {
    return $this->oServidor->getTabelaPrevidencia() + 2; // +2 para Desconsiderar as tabelas de IRRF.
  }

  /**
   * Retorna a data do Ultimo dia dos mês
   *
   * @return DBDate
   */
  private function getDataUltimoDiasDoMes() {

    return new DBDate(
      db_dias_mes(db_anofolha(), db_mesfolha(), true)
    );
  }

  /**
   * @todo revisar nome e funcionamento deste metodo
   * @return \stdClass
   */
  private function verificarRubricasAfastamento() {

    $iTabelaPrevidencia = $this->getTabelaPrevidenciaUsoInterno();

    $clinssirf = new cl_inssirf();
    $result_inssirfsau = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '{$iTabelaPrevidencia}' and trim(r33_rubsau) <> '' "));
    $numrows_sau = $clinssirf->numrows;

    $result_inssirfmat = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '{$iTabelaPrevidencia}' and trim(r33_rubmat) <> '' "));
    $numrows_mat = $clinssirf->numrows;

    $result_inssirfaci = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"*","","r33_anousu = ".db_anofolha()." and r33_mesusu = ".db_mesfolha()." and r33_codtab = '{$iTabelaPrevidencia}' and trim(r33_rubaci) <> '' "));
    $numrows_aci = $clinssirf->numrows;

    $oRetorno = new \stdClass();

    $oRetorno->saude       = $numrows_sau;
    $oRetorno->maternidade = $numrows_mat;
    $oRetorno->acidente    = $numrows_aci;
    return $oRetorno;

  }
}