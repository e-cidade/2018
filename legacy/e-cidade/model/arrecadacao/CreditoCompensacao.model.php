<?php
/**
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
 * Model Crédito Compensação
 *
 * Classe responsavel por realizar a compensação dos créditos no sitema
 *
 * classe irá efetuar o lançamento do crédito caso sejá utlizado contra um débito
 * ou efetuado a sua baixa caso pago em dinheiro.
 *
 * @author everton.heckler <everton.heckler@dbseller.com.br>
 * @package Arrecadacao
 *
 * @version $
 *
 */

class CreditoCompensacao extends Credito {

  const TIPO_UTILIZACAO_TRANFERENCIA = 1;
  const TIPO_UTILIZACAO_COMPENSACAO  = 2;
  const TIPO_UTILIZACAO_DEVOLUCAO    = 3;

  const REGRA_COMPENSACAO_PROPORCIONAL = 1;
  const REGRA_COMPENSACAO_VENCIMENTO   = 2;

  /** @var integer Numpre destino que será utilizado o valor */
  private $iNumpreDestino;

  /** @var integer Parcela destino que será utilizado o valor */
  private $iNumparDestino;

  /** @var integer Receita destino que será utilizado o valor */
  private $iReceitaDestino;

  /** @var string Observação referente ao lançamento do crédito */
  private $sObservacao;
  
  /** @var float Valor do crédito para efetuar a compensação */
  private $nValorCompensacao;
  
  /** @var DBDate Instância do objeto DBDate com a data da transferência */
  private $oDataCompensacao;
  
  /** @var string Hora da transferência */
  private $sHoraCompensacao;
  
  /** @var integer Usuário que efetuou a transferência */
  private $iUsuario;
  
  /** @var integer Instituição do crédito */
  private $iInstituicao;
  
  /** @var integer Codigo do Tipo (debito destino) */
  private $iTipoDestino;
  
  /** @var integer Codigo do Histórico (hist destino) */
  private $iHistoricoDestino;
  
  /** @var integer Define o Cgm possuidor do crédito */
  private $iCgm;

  /** @var float Valor de Crédito com Correção */
  private $nValorDisponivelCorrigido;

  /** @var integer */
  private $iRegraCompensacao;

  /** @var stdClass[] */
  private $aDebitos = array();

  /** @var integer */
  private $iCodigoHistorico;

  /**
   * CreditoCompensacao constructor.
   * @param int|null $iCodigoCredito
   * @throws DBException
   * @throws Exception
   */
  public function __construct($iCodigoCredito = null) {

    if (!empty($iCodigoCredito)) {

      $oDaoAbatimento = new cl_abatimento;

      $sWhere = "k125_sequencial = {$iCodigoCredito}";

      $sCampos  = "abatimento.k125_sequencial,                        ";
      $sCampos .= "abatimento.k125_tipoabatimento,                    ";
      $sCampos .= "abatimento.k125_datalanc,                          ";
      $sCampos .= "abatimento.k125_hora,                              ";
      $sCampos .= "abatimento.k125_usuario,                           ";
      $sCampos .= "abatimento.k125_instit,                            ";
      $sCampos .= "abatimento.k125_valor,                             ";
      $sCampos .= "abatimento.k125_perc,                              ";
      $sCampos .= "abatimento.k125_valordisponivel,                   ";
      $sCampos .= "arrenumcgm.k00_numcgm                              ";

      $sSqlAbatimento = $oDaoAbatimento->sql_queryCreditoManual($sCampos, $sWhere);
      $rsAbatimento   = $oDaoAbatimento->sql_record($sSqlAbatimento);

      if ($oDaoAbatimento->numrows == 0) {
        throw new Exception("Nenhum crédito encontrado com o código: {$iCodigoCredito}");
      }

      $oAbatimento = db_utils::fieldsMemory($rsAbatimento, 0);

      $this->setCodigoCredito              ($oAbatimento->k125_sequencial);
      $this->setTipoAbatimento             ($oAbatimento->k125_tipoabatimento);
      $this->setDataLancamento             (new DBDate($oAbatimento->k125_datalanc));
      $this->setHora                       ($oAbatimento->k125_hora);
      $this->setUsuario                    ($oAbatimento->k125_usuario);
      $this->setInstituicao                ($oAbatimento->k125_instit);
      $this->setValor                      ($oAbatimento->k125_valor);
      $this->setPercentual                 ($oAbatimento->k125_perc);
      $this->setValorDisponivel            ($oAbatimento->k125_valordisponivel);
      $this->setCgm                        (CgmFactory::getInstanceByCgm($oAbatimento->k00_numcgm));
      $this->getValoresCorrigido();
    }
  }

  /**
   * @param integer $iRegraCompensacao
   * @throws ParameterException
   */
  public function setRegraCompensacao($iRegraCompensacao) {

    $aRegras = array(
      self::REGRA_COMPENSACAO_PROPORCIONAL,
      self::REGRA_COMPENSACAO_VENCIMENTO
    );

    if (!in_array($iRegraCompensacao, $aRegras)) {
      throw new ParameterException("Regra de Compensação não informada.");
    }

    $this->iRegraCompensacao = $iRegraCompensacao;
  }

  /**
   * @return integer|null
   */
  public function getRegraCompensacao() {
    return $this->iRegraCompensacao;
  }

  /**
   * @param stdClass $oDebito
   */
  public function addDebito(stdClass $oDebito) {
    $this->aDebitos[] = $oDebito;
  }

  /**
   * @return array
   */
  public function getDebitos() {
    return $this->aDebitos;
  }

  /**
   * @return float
   */
  public function getValorDisponivelCorrigido() {
    return $this->nValorDisponivelCorrigido;
  }

  /**
   * @throws DBException
   */
  private function getValoresCorrigido() {

    $oDaoCorrecao = new cl_abatimentocorrecao();
    $sSqlCorrecao = $oDaoCorrecao->sql_query_file(
      null, '*', ' k167_data desc limit 1', "k167_abatimento = {$this->getCodigoCredito()}"
    );
    $rsCorrecao = db_query($sSqlCorrecao);

    if (!$rsCorrecao) {
      throw new DBException("Não foi possível encontrar as informações de Correção de Crédito.");
    }

    $oDaoAbatimento     = new cl_abatimento();
    $iAno               = db_getsession("DB_anousu");
    $iCodigoInstituicao = db_getsession("DB_instit");
    $iCodigoCredito     = $this->getCodigoCredito();
    $sCampos = "";

    if (pg_numrows($rsCorrecao)) {

      $oUltimaCorrecao = db_utils::fieldsMemory($rsCorrecao, 0);
      $sCampos = "(
        select fc_corre(recibo.k00_receit, '{$oUltimaCorrecao->k167_data}', abatimento.k125_valordisponivel,
                        current_date, {$iAno}, '{$oUltimaCorrecao->k167_data}'
        )) as valor_corrigido
      ";
    }

    if (!pg_numrows($rsCorrecao)) {
      $sCampos = "(
        select fc_corre(recibo.k00_receit, abatimento.k125_datalanc, abatimento.k125_valordisponivel,
                        current_date, {$iAno}, abatimento.k125_datalanc
         )) as valor_corrigido
      ";
    }

    $sWhere  = "abatimento.k125_sequencial = {$iCodigoCredito} and ";
    $sWhere .= "abatimento.k125_instit = {$iCodigoInstituicao} and ";
    $sWhere .= "abatimento.k125_tipoabatimento = " . Abatimento::TIPO_CREDITO;

    $sSql  = "select {$sCampos}";
    $sSql .= "  from abatimento";
    $sSql .= "  inner join abatimentorecibo on abatimentorecibo.k127_abatimento = abatimento.k125_sequencial";
    $sSql .= "  inner join recibo           on recibo.k00_numpre                = abatimentorecibo.k127_numprerecibo";
    $sSql .= " where {$sWhere}";

    $rsAbatimento = $oDaoAbatimento->sql_record($sSql);

    if (!$rsAbatimento && $oDaoAbatimento->numrows == 0) {
      throw new DBException("Não foi possível obter as informações da Correção do Crédito.");
    }
    
    $oCredito = db_utils::fieldsMemory($rsAbatimento, 0);
    $this->nValorDisponivelCorrigido = $oCredito->valor_corrigido;
  }

  /**
   * @throws BusinessException
   * @throws DBException
   * @throws Exception
   */
  public function realizarCompensacao() {

    if (!$this->getCodigoCredito()) {
      throw new BusinessException("Código do Crédito não informado.");
    }

    if (!$this->getRegraCompensacao()) {
      throw new BusinessException("Regra de compensação não informada.");
    }

    if (!$this->getCgm()) {
      throw new BusinessException("CGM não informado.");
    }

    if (!count($this->getDebitos())) {
      throw new BusinessException("Nenhum débito foi adicionado.");
    }

    if ($this->getValorCompensacao() <= 0 || !$this->getValorCompensacao()) {
      throw new BusinessException("Valor da Compensação é inválido.");
    }

    /**
     * Regra de Compensação Por Valor Proporcional
     */
    if ($this->getRegraCompensacao() == self::REGRA_COMPENSACAO_PROPORCIONAL) {

      $oRecibo = new recibo(Recibo::TIPOEMISSAO_RECIBO_CGF, $this->getCgm());
      foreach ($this->getDebitos() as $oDebito) {
        $oRecibo->addNumpre($oDebito->numpre, $oDebito->numpar);
      }
      $oRecibo->emiteRecibo();

      $nTotalReciboPaga = $oRecibo->getTotalRecibo();
      $nValorRestanteCompensacao = $nTotalReciboPaga - $this->getValorCompensacao();
      $this->verificarCorrecaoCredito();

      /**
       * Pagamento Total dos Débitos
       */
      if ($nValorRestanteCompensacao <= 0) {

        $oDaoCreditoUtilizacao                      = new cl_abatimentoutilizacao();
        $oDaoCreditoUtilizacao->k157_observacao     = $this->getObservacao();
        $oDaoCreditoUtilizacao->k157_valor          = $nTotalReciboPaga;
        $oDaoCreditoUtilizacao->k157_tipoutilizacao = self::TIPO_UTILIZACAO_COMPENSACAO;
        $oDaoCreditoUtilizacao->k157_abatimento     = $this->getCodigoCredito();
        $oDaoCreditoUtilizacao->k157_usuario        = db_getsession("DB_id_usuario");
        $oDaoCreditoUtilizacao->k157_data           = date('Y-m-d');
        $oDaoCreditoUtilizacao->k157_hora           = date('H:i');
        $oDaoCreditoUtilizacao->incluir(null);

        if ($oDaoCreditoUtilizacao->erro_status == '0') {
          throw new DBException("Não foi possível salvar informações da Utilização do Crédito.");
        }

        $iCodigoCreditoUtilizacao = $oDaoCreditoUtilizacao->k157_sequencial;

        foreach ($this->getDebitos() as $oDebito) {

          $aDebitosReceita = $this->getDebitoReceitas($oDebito->numpre, $oDebito->numpar);

          foreach ($aDebitosReceita as $oDebitoReceita) {

            $this->incluirArrecant($oDebitoReceita);
            $oDebitoCorrigido = $this->getDebitoCorrigido(
              $oDebitoReceita->k00_numpre,
              $oDebitoReceita->k00_numpar,
              $oDebitoReceita->k00_receit
            );

            $this->incluirCreditoUtilizacaoDestino(
              $iCodigoCreditoUtilizacao,
              $oDebitoReceita,
              $oDebitoCorrigido->valor_total
            );
          }

          $oDaoReciboPaga = new cl_recibopaga();
          $sSqlReciboPaga = $oDaoReciboPaga->sql_query_file(
            null, "*", null,
            "k00_numnov = {$oRecibo->getNumpreRecibo()} and k00_numpre = {$oDebito->numpre} and k00_numpar = {$oDebito->numpar}"
          );
          $rsReciboPaga = $oDaoReciboPaga->sql_record($sSqlReciboPaga);

          if (!$rsReciboPaga && $oDaoReciboPaga->numrows == 0) {
            throw new DBException("Não foi possível obter informações do Recibo a Pagar.");
          }

          $aReciboRecita = db_utils::getCollectionByRecord($rsReciboPaga);

          foreach ($aReciboRecita as $oReciboReceita) {
            $this->incluirArrepaga($oReciboReceita);
          }

          $oDaoArrehist                 = new cl_arrehist;
          $oDaoArrehist->k00_numpre     = $oDebito->numpre;
          $oDaoArrehist->k00_numpar     = $oDebito->numpar;
          $oDaoArrehist->k00_hist       = 918;
          $oDaoArrehist->k00_dtoper     = date("Y-m-d");
          $oDaoArrehist->k00_hora       = db_hora();
          $oDaoArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArrehist->k00_histtxt    = "Utilização do Crédito: {$this->getCodigoCredito()} - {$this->getObservacao()}";
          $oDaoArrehist->k00_limithist  = null;
          $oDaoArrehist->incluir(null);

          if ($oDaoArrehist->erro_status == '0') {
            throw new DBException("Nâo foi possível salvar as informações da Histórico do Pagamento do Débido.");
          }

          $sWhereDeleteArrecad = "     k00_numpre = {$oDebito->numpre}";
          $sWhereDeleteArrecad .= " and k00_numpar = {$oDebito->numpar}";

          $oDaoArrecad = new cl_arrecad;
          $oDaoArrecad->excluir(null, $sWhereDeleteArrecad);
        }

        $nValorDisponivel = $this->getValorDisponivelCorrigido() - $nTotalReciboPaga;
        $this->alterarValorDisponivelCredito($nValorDisponivel);
      }

      /**
       * Pagamento Parcial dos Débitos
       */
      if ($nValorRestanteCompensacao > 0) {

        /**
         * Cria um novo Abatimento com o Valor da Compensação
         */
        $nPercentualCompensacao = ($this->getValorCompensacao() * 100) / $oRecibo->getTotalRecibo();

        $oDaoAbatimento                          = new cl_abatimento();
        $oDaoAbatimento->k125_hora               = date('H:i');
        $oDaoAbatimento->k125_perc               = $nPercentualCompensacao;
        $oDaoAbatimento->k125_valor              = $this->getValorCompensacao();
        $oDaoAbatimento->k125_instit             = db_getsession("DB_instit");
        $oDaoAbatimento->k125_datalanc           = date('Y-m-d');
        $oDaoAbatimento->k125_usuario            = db_getsession("DB_id_usuario");
        $oDaoAbatimento->k125_observacao         = "Utilização do Crédito ({$this->getCodigoCredito()}) - Pagamento Parcial.";
        $oDaoAbatimento->k125_tipoabatimento     = Abatimento::TIPO_COMPENSACAO;
        $oDaoAbatimento->k125_valordisponivel    = "null";
        $oDaoAbatimento->k125_abatimentosituacao = Abatimento::SITUACAO_ATIVO;
        $oDaoAbatimento->incluir(null);

        if ($oDaoAbatimento->erro_status == '0') {
          throw new DBException("Não foi possível salvar informações do Abatimento do Pagamento Parcial.");
        }

        /**
         * Gera um novo Numpre para o Recibo e salva no Abatimento
         */
        $iCodigoAbatimento = $oDaoAbatimento->k125_sequencial;
        $iNovoNumpre       = $this->gerarNovoNumpre();
        $this->incluirAbatimentoRecibo($iCodigoAbatimento, $oRecibo->getNumpreRecibo(), $iNovoNumpre);

        /**
         * Registra a Utilização do Crédito, com o valor da Compensação
         */
        $oDaoCreditoUtilizacao                      = new cl_abatimentoutilizacao();
        $oDaoCreditoUtilizacao->k157_observacao     = $this->getObservacao();
        $oDaoCreditoUtilizacao->k157_valor          = $this->getValorCompensacao();
        $oDaoCreditoUtilizacao->k157_tipoutilizacao = self::TIPO_UTILIZACAO_COMPENSACAO;
        $oDaoCreditoUtilizacao->k157_abatimento     = $this->getCodigoCredito();
        $oDaoCreditoUtilizacao->k157_usuario        = db_getsession("DB_id_usuario");
        $oDaoCreditoUtilizacao->k157_data           = date('Y-m-d');
        $oDaoCreditoUtilizacao->k157_hora           = date('H:i');
        $oDaoCreditoUtilizacao->incluir(null);

        if ($oDaoCreditoUtilizacao->erro_status == '0') {
          throw new DBException("Não foi possível salvar informações da Utilização do Crédito.");
        }

        $iCodigoCreditoUtilizacao = $oDaoCreditoUtilizacao->k157_sequencial;

        /**
         * Obtem Informações de Débito do Recibo Gerado para o Pagamento
         */
        $oDaoReciboPaga = new cl_recibopaga();
        $sCamposReciboPaga = "k00_numcgm, k00_receit, round(sum(k00_valor), 2) as k00_valor, 504 as k00_hist, k00_numnov";
        $sWhereReciboPaga = "k00_numnov = {$oRecibo->getNumpreRecibo()} group by k00_numcgm, k00_receit, k00_numnov";
        $sSqlReciboPaga = $oDaoReciboPaga->sql_query_file(null, $sCamposReciboPaga, null, $sWhereReciboPaga);
        $rsReciboPaga = $oDaoReciboPaga->sql_record($sSqlReciboPaga);

        if (!$rsReciboPaga || $oDaoReciboPaga->numrows == 0) {
          throw new DBException("Não foi possível encontrar as informações do Recibo de Pagamento.");
        }

        $oReciboReceitas = db_utils::getCollectionByRecord($rsReciboPaga);

        /**
         * Cria Recibo Avulso Com o Valor Proporcional as Receitas.
         */
        foreach ($oReciboReceitas as $oReceita) {

          $nPercentualReceita = ($oReceita->k00_valor * 100) / $oRecibo->getTotalRecibo();
          $nValorReceita = $this->getValorCompensacao() * ($nPercentualReceita / 100);
          $oData         = new DateTime();

          $oDaoRecibo = new cl_recibo();
          $oDaoRecibo->k00_numcgm    = $oReceita->k00_numcgm;
          $oDaoRecibo->k00_dtoper    = $oData->format('Y-m-d');
          $oDaoRecibo->k00_receit    = $oReceita->k00_receit;
          $oDaoRecibo->k00_hist      = 504;
          $oDaoRecibo->k00_valor     = $nValorReceita;
          $oDaoRecibo->k00_dtvenc    = $oData->format('Y-m-d');
          $oDaoRecibo->k00_numpre    = $iNovoNumpre;
          $oDaoRecibo->k00_numpar    = 1;
          $oDaoRecibo->k00_numtot    = 1;
          $oDaoRecibo->k00_numdig    = '0';
          $oDaoRecibo->k00_tipo      = $this->getTipoDebitoPagamentoParcial($oRecibo->getNumpreRecibo());
          $oDaoRecibo->k00_tipojm    = "0";
          $oDaoRecibo->k00_codsubrec = "0";
          $oDaoRecibo->incluir();

          if ($oDaoRecibo->erro_status == '0') {
            throw new DBException("Não foi possível salvar as informações de Recibo.");
          }

          /**
           * Cria um novo Histórico do Pagamento.
           */
          $oDaoArrehist = new cl_arrehist();
          $oDaoArrehist->k00_numpre     = $iNovoNumpre;
          $oDaoArrehist->k00_numpar     = 1;
          $oDaoArrehist->k00_hist       = 502;
          $oDaoArrehist->k00_dtoper     = $oData->format('Y-m-d');
          $oDaoArrehist->k00_hora       = $oData->format('H:i');;
          $oDaoArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArrehist->k00_histtxt    = "Utilização do Crédito ({$this->getCodigoCredito()})";
          $oDaoArrehist->k00_limithist  = "";
          $oDaoArrehist->incluir(null);

          if ($oDaoArrehist->erro_status == '0') {
            throw new DBException("Não foi possível salvar as informações do Histórico do Pagamento.");
          }

          /**
           * Verifica e Cria Vínculo da novo Numpre com o CGM
           */
          $this->incluirVinculoNumcgm($this->getCgm(), $iNovoNumpre);

          $nValorDebito = ($oReceita->k00_valor / $oRecibo->getTotalRecibo()) * $this->getValorCompensacao();
          $oDaoArrepaga = new cl_arrepaga;
          $oDaoArrepaga->k00_numcgm = $oReceita->k00_numcgm;
          $oDaoArrepaga->k00_numpre = $iNovoNumpre;
          $oDaoArrepaga->k00_hist = $oReceita->k00_hist;
          $oDaoArrepaga->k00_numpar = 1;
          $oDaoArrepaga->k00_numtot = 1;
          $oDaoArrepaga->k00_dtoper = $oData->format('Y-m-d');
          $oDaoArrepaga->k00_dtpaga = $oData->format('Y-m-d');
          $oDaoArrepaga->k00_dtvenc = $oData->format('Y-m-d');
          $oDaoArrepaga->k00_numdig = '0';
          $oDaoArrepaga->k00_conta = '0';
          $oDaoArrepaga->k00_receit = $oReceita->k00_receit;
          $oDaoArrepaga->k00_valor = $nValorDebito;
          $oDaoArrepaga->incluir();

          if ($oDaoArrepaga->erro_status == '0') {
            throw new DBException("Não foi possível salvar as informações do Pagamento Parcial.");
          }
        }

        /**
         * Obtem Débitos a serem pagos
         */
        $aDebitosRecibo = $this->getDebitosRecibo($oRecibo->getNumpreRecibo());

        foreach ($aDebitosRecibo as $oDebitoRecibo) {

          $iCodigoArreckey = $this->incluirArreckey($oDebitoRecibo);

          $oDebitoCorrigido = $this->getDebitoCorrigido(
            $oDebitoRecibo->k00_numpre,
            $oDebitoRecibo->k00_numpar,
            $oDebitoRecibo->k00_receit
          );

          $nValorCompensado = $oDebitoCorrigido->k00_valor * ($nPercentualCompensacao / 100);
          $nValorCorrecao = ($oDebitoCorrigido->valor_correcao - $oDebitoCorrigido->k00_valor) * ($nPercentualCompensacao / 100);
          $nValorJuros = $oDebitoCorrigido->valor_juros * ($nPercentualCompensacao / 100);
          $nValorMulta = $oDebitoCorrigido->valor_multa * ($nPercentualCompensacao / 100);

          $oDaoAbatimentoArreckey = new cl_abatimentoarreckey();
          $oDaoAbatimentoArreckey->k128_arreckey = $iCodigoArreckey;
          $oDaoAbatimentoArreckey->k128_abatimento = $iCodigoAbatimento;
          $oDaoAbatimentoArreckey->k128_valorabatido = $nValorCompensado;
          $oDaoAbatimentoArreckey->k128_correcao = $nValorCorrecao;
          $oDaoAbatimentoArreckey->k128_juros = $nValorJuros;
          $oDaoAbatimentoArreckey->k128_multa = $nValorMulta;
          $oDaoAbatimentoArreckey->incluir(null);

          if ($oDaoAbatimentoArreckey->erro_status == '0') {
            throw new DBException("Não foi possível salvar as informações da Arrecadação.");
          }

          $nValorCompensacao  = $oDebitoCorrigido->valor_total * ($nPercentualCompensacao / 100);
          $oDaoCreditoDestino = new cl_abatimentoutilizacaodestino();
          $oDaoCreditoDestino->k170_utilizacao = $iCodigoCreditoUtilizacao;
          $oDaoCreditoDestino->k170_numpre = $oDebitoCorrigido->k00_numpre;
          $oDaoCreditoDestino->k170_numpar = $oDebitoCorrigido->k00_numpar;
          $oDaoCreditoDestino->k170_receit = $oDebitoCorrigido->k00_receit;
          $oDaoCreditoDestino->k170_hist = $oDebitoCorrigido->k00_hist;
          $oDaoCreditoDestino->k170_tipo = $oDebitoCorrigido->k00_tipo;
          $oDaoCreditoDestino->k170_valor = $nValorCompensacao;
          $oDaoCreditoDestino->incluir();

          if ($oDaoCreditoDestino->erro_status == '0') {
            throw new DBException("Nâo foi possível salvar as informações do Destino do Crédito.");
          }

          $oDaoArrecad = new cl_arrecad();
          $oDaoArrecad->k00_valor = ($oDebitoCorrigido->k00_valor - $nValorCompensado);
          $oDaoArrecad->alterar(null,
            "k00_numpre = {$oDebitoCorrigido->k00_numpre} " .
            "and k00_numpar = {$oDebitoCorrigido->k00_numpar} " .
            "and k00_receit = {$oDebitoCorrigido->k00_receit}"
          );

          if ($oDaoArrecad->erro_status == '0') {
            throw new DBException("Não foi possível salvar as informações do Débito.");
          }
        }

        foreach ($this->getDebitos() as $oDebito) {

          $oDaoArreHist                 = new cl_arrehist;
          $oDaoArreHist->k00_numpre     = $oDebito->numpre;
          $oDaoArreHist->k00_numpar     = $oDebito->numpar;
          $oDaoArreHist->k00_hist       = 918;
          $oDaoArreHist->k00_dtoper     = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoArreHist->k00_hora       = db_hora();
          $oDaoArreHist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArreHist->k00_histtxt    = "Utilização do Crédito: {$this->getCodigoCredito()} - {$this->getObservacao()}";
          $oDaoArreHist->k00_limithist  = null;
          $oDaoArreHist->incluir(null);

          if ($oDaoArreHist->erro_status == '0') {
            throw new Exception('Erro ao incluir registro de abatimento. ERRO: (arrehist)' . $oDaoArreHist->erro_sql);
          }
        }

        $nValorDisponivel = $this->getValorDisponivelCorrigido() - $this->getValorCompensacao();
        $this->alterarValorDisponivelCredito($nValorDisponivel);
      }
    }

    /**
     * Regra de Compensação Por Data de Vencimento do Débito
     */
    if ($this->getRegraCompensacao() == self::REGRA_COMPENSACAO_VENCIMENTO) {

      $aDebitos = array_map(function ($oDebito) {
        $oDebito->data_vencimento = new DBDate($oDebito->data_vencimento);
        return $oDebito;
      }, $this->getDebitos());

      usort($aDebitos, function ($oDebito1, $oDebito2) {
        return $oDebito1->data_vencimento->getTimestamp() - $oDebito2->data_vencimento->getTimestamp();
      });

      $nValorRestanteCompensacao = $this->getValorCompensacao();
      $nValorCreditoCorrigido    = $this->getValorDisponivelCorrigido();
      $this->verificarCorrecaoCredito();

      foreach ($aDebitos as $oDebito) {

        if ($nValorRestanteCompensacao <= 0) {
          break;
        }

        $oRecibo = new recibo(Recibo::TIPOEMISSAO_RECIBO_CGF, $this->getCgm());
        $oRecibo->addNumpre($oDebito->numpre, $oDebito->numpar);
        $oRecibo->emiteRecibo();

        /**
         * Compensação Total do Débito
         */
        if ($nValorRestanteCompensacao >= $oRecibo->getTotalRecibo()) {

          $iCodigoCreditoUtilizacao = $this->incluirUtilizacaoCredito(
            self::TIPO_UTILIZACAO_COMPENSACAO,
            $oDebito->valor
          );

          $aDebitosReceita = $this->getDebitoReceitas($oDebito->numpre, $oDebito->numpar);

          foreach ($aDebitosReceita as $oDebitoReceita) {

            $this->incluirArrecant($oDebitoReceita);
            $oDebitoCorrigido = $this->getDebitoCorrigido(
              $oDebitoReceita->k00_numpre,
              $oDebitoReceita->k00_numpar,
              $oDebitoReceita->k00_receit
            );

            $this->incluirCreditoUtilizacaoDestino(
              $iCodigoCreditoUtilizacao,
              $oDebitoReceita,
              $oDebitoCorrigido->valor_total
            );
          }

          $oDaoReciboPaga = new cl_recibopaga();
          $sSqlReciboPaga = $oDaoReciboPaga->sql_query_file(
            null, "*", null,
            "k00_numnov = {$oRecibo->getNumpreRecibo()} and " .
            "k00_numpre = {$oDebito->numpre} and " .
            "k00_numpar = {$oDebito->numpar}"
          );
          $rsReciboPaga = $oDaoReciboPaga->sql_record($sSqlReciboPaga);

          if (!$rsReciboPaga && $oDaoReciboPaga->numrows == 0) {
            throw new DBException("Não foi possível obter informações do Recibo a Pagar.");
          }

          $aReciboRecita = db_utils::getCollectionByRecord($rsReciboPaga);

          foreach ($aReciboRecita as $oReciboReceita) {
            $this->incluirArrepaga($oReciboReceita);
          }

          $oDaoArrehist                 = new cl_arrehist;
          $oDaoArrehist->k00_numpre     = $oDebito->numpre;
          $oDaoArrehist->k00_numpar     = $oDebito->numpar;
          $oDaoArrehist->k00_hist       = 918;
          $oDaoArrehist->k00_dtoper     = date("Y-m-d");
          $oDaoArrehist->k00_hora       = db_hora();
          $oDaoArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArrehist->k00_histtxt    = "Utilização do Crédito ({$this->getCodigoCredito()})";
          $oDaoArrehist->k00_limithist  = null;
          $oDaoArrehist->incluir(null);

          $sWhereDeleteArrecad = "     k00_numpre = {$oDebito->numpre}";
          $sWhereDeleteArrecad .= " and k00_numpar = {$oDebito->numpar}";

          $oDaoArrecad = new cl_arrecad;
          $oDaoArrecad->excluir(null, $sWhereDeleteArrecad);

          $nValorCreditoCorrigido -= $oDebito->valor;
          $this->alterarValorDisponivelCredito($nValorCreditoCorrigido);
          $nValorRestanteCompensacao -= $oDebito->valor;

          continue;
        }

        /**
         * Compenseção Parcial do Débito
         */
        if ($nValorRestanteCompensacao < $oRecibo->getTotalRecibo()) {

          /**
           * Cria um novo Abatimento com o Valor da Compensação
           */
          $nPercentualCompensacao = ($nValorRestanteCompensacao * 100) / $oRecibo->getTotalRecibo();

          $oDaoAbatimento                          = new cl_abatimento();
          $oDaoAbatimento->k125_hora               = date('H:i');
          $oDaoAbatimento->k125_perc               = $nPercentualCompensacao;
          $oDaoAbatimento->k125_valor              = $nValorRestanteCompensacao;
          $oDaoAbatimento->k125_instit             = db_getsession("DB_instit");
          $oDaoAbatimento->k125_datalanc           = date('Y-m-d');
          $oDaoAbatimento->k125_usuario            = db_getsession("DB_id_usuario");
          $oDaoAbatimento->k125_observacao         = "Utilização do Crédito ({$this->getCodigoCredito()}) - Pagamento Parcial.";
          $oDaoAbatimento->k125_tipoabatimento     = Abatimento::TIPO_COMPENSACAO;
          $oDaoAbatimento->k125_valordisponivel    = "null";
          $oDaoAbatimento->k125_abatimentosituacao = Abatimento::SITUACAO_ATIVO;
          $oDaoAbatimento->incluir(null);

          if ($oDaoAbatimento->erro_status == '0') {
            throw new DBException("Não foi possível salvar informações do Abatimento do Pagamento Parcial.");
          }

          /**
           * Gera um novo Numpre para o Recibo e salva no Abatimento
           */
          $iCodigoAbatimento = $oDaoAbatimento->k125_sequencial;
          $iNovoNumpre       = $this->gerarNovoNumpre();
          $this->incluirAbatimentoRecibo($iCodigoAbatimento, $oRecibo->getNumpreRecibo(), $iNovoNumpre);

          /**
           * Registra a Utilização do Crédito, com o valor da Compensação
           */
          $oDaoCreditoUtilizacao                      = new cl_abatimentoutilizacao();
          $oDaoCreditoUtilizacao->k157_observacao     = $this->getObservacao();
          $oDaoCreditoUtilizacao->k157_valor          = $nValorRestanteCompensacao;
          $oDaoCreditoUtilizacao->k157_tipoutilizacao = self::TIPO_UTILIZACAO_COMPENSACAO;
          $oDaoCreditoUtilizacao->k157_abatimento     = $this->getCodigoCredito();
          $oDaoCreditoUtilizacao->k157_usuario        = db_getsession("DB_id_usuario");
          $oDaoCreditoUtilizacao->k157_data           = date('Y-m-d');
          $oDaoCreditoUtilizacao->k157_hora           = date('H:i');
          $oDaoCreditoUtilizacao->incluir(null);

          if ($oDaoCreditoUtilizacao->erro_status == '0') {
            throw new DBException("Não foi possível salvar informações da Utilização do Crédito.");
          }

          $iCodigoCreditoUtilizacao = $oDaoCreditoUtilizacao->k157_sequencial;

          /**
           * Obtem Informações de Débito do Recibo Gerado para o Pagamento
           */
          $oDaoReciboPaga = new cl_recibopaga();
          $sCamposReciboPaga = "k00_numcgm, k00_receit, round(sum(k00_valor), 2) as k00_valor, 504 as k00_hist, k00_numnov";
          $sWhereReciboPaga = "k00_numnov = {$oRecibo->getNumpreRecibo()} group by k00_numcgm, k00_receit, k00_numnov";
          $sSqlReciboPaga = $oDaoReciboPaga->sql_query_file(null, $sCamposReciboPaga, null, $sWhereReciboPaga);
          $rsReciboPaga = $oDaoReciboPaga->sql_record($sSqlReciboPaga);

          if (!$rsReciboPaga || $oDaoReciboPaga->numrows == 0) {
            throw new DBException("Não foi possível encontrar as informações do Recibo de Pagamento.");
          }

          $oReciboReceitas = db_utils::getCollectionByRecord($rsReciboPaga);

          /**
           * Cria Recibo Avulso Com o Valor Proporcional as Receitas.
           */
          foreach ($oReciboReceitas as $oReceita) {

            $nPercentualReceita = ($oReceita->k00_valor * 100) / $oRecibo->getTotalRecibo();
            $nValorReceita = $nValorRestanteCompensacao * ($nPercentualReceita / 100);
            $oData         = new DateTime();

            $oDaoRecibo = new cl_recibo();
            $oDaoRecibo->k00_numcgm = $oReceita->k00_numcgm;
            $oDaoRecibo->k00_dtoper = $oData->format('Y-m-d');
            $oDaoRecibo->k00_receit = $oReceita->k00_receit;
            $oDaoRecibo->k00_hist   = 504;
            $oDaoRecibo->k00_valor  = $nValorReceita;
            $oDaoRecibo->k00_dtvenc = $oData->format('Y-m-d');
            $oDaoRecibo->k00_numpre = $iNovoNumpre;
            $oDaoRecibo->k00_numpar = 1;
            $oDaoRecibo->k00_numtot = 1;
            $oDaoRecibo->k00_numdig = '0';
            $oDaoRecibo->k00_tipo = $this->getTipoDebitoPagamentoParcial($oRecibo->getNumpreRecibo());
            $oDaoRecibo->k00_tipojm = "0";
            $oDaoRecibo->k00_codsubrec = "0";
            $oDaoRecibo->incluir();

            if ($oDaoRecibo->erro_status == '0') {
              throw new DBException("Não foi possível salvar as informações de Recibo.");
            }

            /**
             * Cria um novo Histórico do Pagamento.
             */
            $oDaoArrehist = new cl_arrehist();
            $oDaoArrehist->k00_numpre = $iNovoNumpre;
            $oDaoArrehist->k00_numpar = 1;
            $oDaoArrehist->k00_hist = 502;
            $oDaoArrehist->k00_dtoper = $oData->format('Y-m-d');
            $oDaoArrehist->k00_hora = $oData->format('H:i');;
            $oDaoArrehist->k00_id_usuario = db_getsession("DB_id_usuario");
            $oDaoArrehist->k00_histtxt = "Utilização do Crédito ({$this->getCodigoCredito()})";
            $oDaoArrehist->k00_limithist = "";
            $oDaoArrehist->incluir(null);

            if ($oDaoArrehist->erro_status == '0') {
              throw new DBException("Não foi possível salvar as informações do Histórico do Pagamento.");
            }

            /**
             * Verifica e Cria Vínculo da novo Numpre com o CGM
             */
            $this->incluirVinculoNumcgm($this->getCgm(), $iNovoNumpre);

            $nValorDebito = ($oReceita->k00_valor / $oRecibo->getTotalRecibo()) * $nValorRestanteCompensacao;
            $oDaoArrepaga = new cl_arrepaga;
            $oDaoArrepaga->k00_numcgm = $oReceita->k00_numcgm;
            $oDaoArrepaga->k00_numpre = $iNovoNumpre;
            $oDaoArrepaga->k00_hist = $oReceita->k00_hist;
            $oDaoArrepaga->k00_numpar = 1;
            $oDaoArrepaga->k00_numtot = 1;
            $oDaoArrepaga->k00_dtoper = $oData->format('Y-m-d');
            $oDaoArrepaga->k00_dtpaga = $oData->format('Y-m-d');
            $oDaoArrepaga->k00_dtvenc = $oData->format('Y-m-d');
            $oDaoArrepaga->k00_numdig = '0';
            $oDaoArrepaga->k00_conta = '0';
            $oDaoArrepaga->k00_receit = $oReceita->k00_receit;
            $oDaoArrepaga->k00_valor = $nValorDebito;
            $oDaoArrepaga->incluir();

            if ($oDaoArrepaga->erro_status == '0') {
              throw new DBException("Não foi possível salvar as informações do Pagamento Parcial.");
            }
          }

          /**
           * Obtem Débitos a serem pagos
           */
          $aDebitosRecibo = $this->getDebitosRecibo($oRecibo->getNumpreRecibo());

          foreach ($aDebitosRecibo as $oDebitoRecibo) {

            $iCodigoArreckey = $this->incluirArreckey($oDebitoRecibo);

            $oDebitoCorrigido = $this->getDebitoCorrigido(
              $oDebitoRecibo->k00_numpre,
              $oDebitoRecibo->k00_numpar,
              $oDebitoRecibo->k00_receit
            );

            /**
             * Calcula Valores do Abatimento
             */
            $nValorCompensado = $oDebitoCorrigido->k00_valor * ($nPercentualCompensacao / 100);
            $nValorCorrecao = ($oDebitoCorrigido->valor_correcao - $oDebitoCorrigido->k00_valor) * ($nPercentualCompensacao / 100);
            $nValorJuros = $oDebitoCorrigido->valor_juros * ($nPercentualCompensacao / 100);
            $nValorMulta = $oDebitoCorrigido->valor_multa * ($nPercentualCompensacao / 100);

            $oDaoAbatimentoArreckey = new cl_abatimentoarreckey();
            $oDaoAbatimentoArreckey->k128_arreckey = $iCodigoArreckey;
            $oDaoAbatimentoArreckey->k128_abatimento = $iCodigoAbatimento;
            $oDaoAbatimentoArreckey->k128_valorabatido = $nValorCompensado;
            $oDaoAbatimentoArreckey->k128_correcao = $nValorCorrecao;
            $oDaoAbatimentoArreckey->k128_juros = $nValorJuros;
            $oDaoAbatimentoArreckey->k128_multa = $nValorMulta;
            $oDaoAbatimentoArreckey->incluir(null);

            if ($oDaoAbatimentoArreckey->erro_status == '0') {
              throw new DBException("Não foi possível salvar as informações da Arrecadação.");
            }

            /**
             * Cria Vínculo do Abatimento Utilização com o Destino do Crédito
             */
            $nValorCompensacao = $oDebitoCorrigido->valor_total * ($nPercentualCompensacao / 100);
            $oDaoCreditoDestino = new cl_abatimentoutilizacaodestino();
            $oDaoCreditoDestino->k170_utilizacao = $iCodigoCreditoUtilizacao;
            $oDaoCreditoDestino->k170_numpre = $oDebitoCorrigido->k00_numpre;
            $oDaoCreditoDestino->k170_numpar = $oDebitoCorrigido->k00_numpar;
            $oDaoCreditoDestino->k170_receit = $oDebitoCorrigido->k00_receit;
            $oDaoCreditoDestino->k170_hist = $oDebitoCorrigido->k00_hist;
            $oDaoCreditoDestino->k170_tipo = $oDebitoCorrigido->k00_tipo;
            $oDaoCreditoDestino->k170_valor = $nValorCompensacao;
            $oDaoCreditoDestino->incluir();

            if ($oDaoCreditoDestino->erro_status == '0') {
              throw new DBException("Nâo foi possível salvar as informações do Destino do Crédito.");
            }

            /**
             * Altera Valor do Débito Original
             */
            $oDaoArrecad = new cl_arrecad();
            $oDaoArrecad->k00_valor = ($oDebitoCorrigido->k00_valor - $nValorCompensado);
            $oDaoArrecad->alterar(null,
              "k00_numpre = {$oDebitoCorrigido->k00_numpre} " .
              "and k00_numpar = {$oDebitoCorrigido->k00_numpar} " .
              "and k00_receit = {$oDebitoCorrigido->k00_receit}"
            );

            if ($oDaoArrecad->erro_status == '0') {
              throw new DBException("Não foi possível salvar as informações do Débito.");
            }
          }

          $oDaoArreHist                 = new cl_arrehist;
          $oDaoArreHist->k00_numpre     = $oDebito->numpre;
          $oDaoArreHist->k00_numpar     = $oDebito->numpar;
          $oDaoArreHist->k00_hist       = 918;
          $oDaoArreHist->k00_dtoper     = date("Y-m-d", db_getsession("DB_datausu"));
          $oDaoArreHist->k00_hora       = db_hora();
          $oDaoArreHist->k00_id_usuario = db_getsession("DB_id_usuario");
          $oDaoArreHist->k00_histtxt    = "Utilização do Crédito ({$this->getCodigoCredito()})";
          $oDaoArreHist->k00_limithist  = null;
          $oDaoArreHist->incluir(null);

          if ($oDaoArreHist->erro_status == '0') {
            throw new Exception('Erro ao incluir registro de abatimento. ERRO: (arrehist)' . $oDaoArreHist->erro_sql);
          }

          $nValorDisponivel = $this->getValorDisponivelCorrigido() - $this->getValorCompensacao();
          $this->alterarValorDisponivelCredito($nValorDisponivel);
          $nValorRestanteCompensacao = 0;

          continue;
        }
      }
    }
  }

  /**
   * @throws ParameterException
   * @throws DBException
   * @return bool
   */
  public function realizarDevolucao() {

    if (!$this->getCodigoCredito()) {
      throw new ParameterException("Crédito não informado para a compesação.");
    }

    if (!$this->getValorCompensacao()) {
      throw  new ParameterException("Valor para efetuar a compensação não foi informado.");
    }

    if ($this->getValorDisponivelCorrigido() < $this->getValorCompensacao()) {
      throw new ParameterException("Valor Informado para compensar é maior que o saldo disponivel do crédito.");
    }

    if ($this->getValorCompensacao() <= 0) {
      throw new ParameterException("Valor Informado para compensar está inválido.");
    }

    $this->verificarCorrecaoCredito();
    $this->incluirUtilizacaoCredito(self::TIPO_UTILIZACAO_DEVOLUCAO, $this->getValorCompensacao());

    $fNovoValorDisponivel = $this->getValorDisponivelCorrigido() - $this->getValorCompensacao();

    $oDaoAbatimento                       = new cl_abatimento;
    $oDaoAbatimento->k125_valordisponivel = "{$fNovoValorDisponivel}";
    $oDaoAbatimento->k125_sequencial      = $this->getCodigoCredito();
    $oDaoAbatimento->alterar($this->getCodigoCredito());

    if ($oDaoAbatimento->erro_status == '0') {
      throw new DBException('Não foi possível salvar as informações do crédito. ');
    }

    return true;
  }

  /**
   * @param stdClass $oDebito
   * @return int
   * @throws DBException
   */
  public function incluirArreckey(stdClass $oDebito) {

    $oDaoArreckey = new cl_arreckey();
    $sArreckey = $oDaoArreckey->sql_query_file(null, 'k00_sequencial', null, "
      k00_numpre     = {$oDebito->k00_numpre}
      and k00_numpar = {$oDebito->k00_numpar}
      and k00_receit = {$oDebito->k00_receit}
      and k00_hist   = {$oDebito->k00_hist}
      and k00_tipo   = {$oDebito->k00_tipo}
    ");
    $rsArreckeyExiste = db_query($sArreckey);

    if (!$rsArreckeyExiste) {
      throw new DBException("Não foi possível encontrar as informações de Parcelamento do Débito.");
    }

    if (pg_numrows($rsArreckeyExiste) == 0) {

      $oDaoArreckey = new cl_arreckey();
      $oDaoArreckey->k00_numpre = $oDebito->k00_numpre;
      $oDaoArreckey->k00_numpar = $oDebito->k00_numpar;
      $oDaoArreckey->k00_receit = $oDebito->k00_receit;
      $oDaoArreckey->k00_hist   = $oDebito->k00_hist;
      $oDaoArreckey->k00_tipo   = $oDebito->k00_tipo;
      $oDaoArreckey->incluir(null);

      if ($oDaoArreckey->erro_status == '0') {
        throw new DBException("Não foi possível salvar as informações de Parcelamento do Débito.");
      }

      return $oDaoArreckey->k00_sequencial;
    }

    return db_utils::fieldsMemory($rsArreckeyExiste, 0)->k00_sequencial;
  }

  /**
   * @param $iNumpre
   * @param $iNumpar
   * @return stdClass[]
   * @throws DBException
   */
  private function getDebitoReceitas($iNumpre, $iNumpar) {

    $oDaoArrecad = new cl_arrecad();
    $sSqlArrecad = $oDaoArrecad->sql_query_file(null, "*", null, "k00_numpre = {$iNumpre} and k00_numpar = {$iNumpar}");
    $rsArrecad   = $oDaoArrecad->sql_record($sSqlArrecad);

    if (!$rsArrecad && $oDaoArrecad->numrows == 0) {
      throw new DBException("Não foi possível encontrar as informações do débito.");
    }

    return db_utils::getCollectionByRecord($rsArrecad);
  }

  /**
   * @param $iNumnov
   * @return mixed
   * @throws DBException
   */
  private function getTipoDebitoPagamentoParcial($iNumnov) {

    $sSql = "
      select (
        select arrecad.k00_tipo
          from arrecad
          where arrecad.k00_numpre = recibopaga.k00_numpre
            and arrecad.k00_numpar = recibopaga.k00_numpar
    
        union
    
        select arrecant.k00_tipo
          from arrecant
          where arrecant.k00_numpre = recibopaga.k00_numpre
            and arrecant.k00_numpar = recibopaga.k00_numpar limit 1
        ) as k00_tipo
      from recibopaga
      where k00_numnov = {$iNumnov}
    ";
    
    $rsTipoDebito = db_query($sSql);

    if (!$rsTipoDebito || pg_numrows($rsTipoDebito) == 0) {
      throw new DBException("Não foi possível encontrar as informações do Tipo de Debito.");
    }

    return db_utils::fieldsMemory($rsTipoDebito, 0)->k00_tipo;
  }

  /**
   * @throws DBException
   */
  private function verificarCorrecaoCredito() {

    if ($this->getValorDisponivelCorrigido() > $this->getValorDisponivel()) {
      $this->incluirValorCorrigido();
    }
  }

  /**
   * @return int
   * @throws DBException
   */
  private function incluirValorCorrigido() {

    $oDaoCorrecao = new cl_abatimentocorrecao();
    $oDaoCorrecao->k167_valorantigo = $this->getValorDisponivel();
    $oDaoCorrecao->k167_valorcorrigido = $this->getValorDisponivelCorrigido();
    $oDaoCorrecao->k167_data = date('Y-m-d');
    $oDaoCorrecao->k167_abatimento = $this->getCodigoCredito();
    $oDaoCorrecao->incluir(null);

    if ($oDaoCorrecao->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações de Correção de Crédito.");
    }

    return $oDaoCorrecao->k167_sequencial;
  }

  /**
   * Define o usuario que efetuou a transferência
   * @param integer $iUsuario
   */
  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }
  
  /**
   * Retorna o código do usuário que efetuou a tranferência
   * @return integer
   */
  public function getUsuario() {
    return $this->iUsuario;
  }
  
  /**
   * Define a data da transferência do crédito
   * @param DBDate $oDataCompensacao
   */
  public function setDataCompensacao(DBDate $oDataCompensacao) {
    $this->oDataCompensacao = $oDataCompensacao;
  }
  
  /**
   * Retorna uma instância do objeto DBData com a data do sistema
   * @return DBDate
   */
  public function getDataCompensacao() {
    return $this->oDataCompensacao;
  }
  
  /**
   * Define a hora da transferência do crédito
   * @param string $sHoraCompensacao
   */
  public function setHoraCompensacao($sHoraCompensacao) {
    $this->sHoraCompensacao = $sHoraCompensacao;
  }
  
  /**
   * Retorna a hora da transferência do crédito
   * @return string
   */
  public function getHoraCompensacao() {
    return $this->sHoraCompensacao;
  }
  
  /**
   * Define o Numpre de Destino
   * @param integer $iNumpreDestino
   */
  public function setNumpreDestino($iNumpreDestino) {
    $this->iNumpreDestino = $iNumpreDestino;
  }

  /**
   * Retorna o Numpre de Destino
   * @return integer
   */
  public function getNumpreDestino() {
    return $this->iNumpreDestino;
  }

  /**
   * Define o Numpar de Destino
   * @param integer $iNumparDestino
   */
  public function setNumparDestino($iNumparDestino) {
    $this->iNumparDestino = $iNumparDestino;
  }

  /**
   * Retorna o Numpar de Destino
   * @return integer
   */
  public function getNumparDestino() {
    return $this->iNumparDestino;
  }

   /**
    * Define a Receita de Destino
    * @param integer $iReceitaDestino
    */
   public function setReceitaDestino($iReceitaDestino) {
     $this->iReceitaDestino = $iReceitaDestino;
   }

   /**
    * Retorna a Receita de Destino
    * @return integer
    */
   public function getReceitaDestino() {
     return $this->iReceitaDestino;
   }

   /**
    * Retorna a observação referente a transferência
    * @return string
    */
   public function getObservacao() {
     return $this->sObservacao;
   }

   /**
    * Define a observação referente a Compensacao
    * @param string $sObservacao
    */
   public function setObservacao($sObservacao) {
     $this->sObservacao = $sObservacao;
   }

   /**
    * Retorna o valor a ser compensado
    * @return number
    */
   public function getValorCompensacao() {
     return $this->nValorCompensacao;
   }

   /**
    * Define o valor a ser transferido
    * @param number $nValorCompensacao
    */
   public function setValorCompensacao($nValorCompensacao) {
     $this->nValorCompensacao = $nValorCompensacao;
   }
   
   /**
    * Retorna o historico do debito destino
    * @return integer
    */
   public function getHistoricoDestino() {
     return $this->iHistoricoDestino;
   }
   
   /**
    * Define o historico destino
    * @param integer $iHistoricoDestino
    */
   public function setHistoricoDestino($iHistoricoDestino) {
     $this->iHistoricoDestino = $iHistoricoDestino;
   }
   
   /**
    * Retorna o tipo do debito destino
    * @return integer
    */
   public function getTipoDestino() {
     return $this->iTipoDestino;
   }
    
   /**
    * Define o tipo destino
    * @param integer $iTipo
    */
   public function setTipoDestino($iTipo) {
     $this->iTipoDestino = $iTipo;
   }
  
   /**
    * Define Cgm Possuidor do Abatimetno
    * @param integer $iCgm
    */
   public function setCgm($iCgm) {
     $this->iCgm = $iCgm;
   }

   /**
    * Retorna o Cgm possuidor do Abatimento
    * return integer
    */
   public function getCgm() {
     return $this->iCgm;
   }

  /**
   * @param int $iTipoUtilizacao
   * @param double $nValorUtilizacao
   * @throws DBException
   * @return int|string
   */
  private function incluirUtilizacaoCredito($iTipoUtilizacao, $nValorUtilizacao) {

    $oData = new DateTime();
    $oDaoUtilizacaoCredito                      = new cl_abatimentoutilizacao;
    $oDaoUtilizacaoCredito->k157_tipoutilizacao = $iTipoUtilizacao;
    $oDaoUtilizacaoCredito->k157_data           = $oData->format('Y-m-d');
    $oDaoUtilizacaoCredito->k157_valor          = $nValorUtilizacao;
    $oDaoUtilizacaoCredito->k157_observacao     = $this->getObservacao();
    $oDaoUtilizacaoCredito->k157_hora           = $oData->format('H:i');
    $oDaoUtilizacaoCredito->k157_usuario        = db_getsession('DB_id_usuario');
    $oDaoUtilizacaoCredito->k157_abatimento     = $this->getCodigoCredito();
    $oDaoUtilizacaoCredito->incluir(null);

    if ($oDaoUtilizacaoCredito->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações da utilização de crédito.");
    }

    return $oDaoUtilizacaoCredito->k157_sequencial;
  }

  /**
   * @param integer $iNumpre
   * @param integer $iNumpar
   * @param integer $iReceita
   * @return cl_arrecad
   * @throws DBException
   */
  private function getDebitoCorrigido($iNumpre, $iNumpar, $iReceita) {

    $oDaoArrecad   = new cl_arrecad;
    $sWhereArrecad = '     k00_numpre = ' . $iNumpre;
    $sWhereArrecad .= ' and k00_numpar = ' . $iNumpar;
    $sWhereArrecad .= ' and k00_receit = ' . $iReceita;

    $iAno = db_getsession('DB_anousu');

    $sSql = "
      select *
      from
        (select
          substr(fc_calcula,2,13)::float8  as valor_historico,
          substr(fc_calcula,15,13)::float8 as valor_correcao,
          substr(fc_calcula,28,13)::float8 as valor_juros,
          substr(fc_calcula,41,13)::float8 as valor_multa,
          substr(fc_calcula,54,13)::float8 as valor_desconto,
          (
            substr(fc_calcula,15,13)::float8
            + substr(fc_calcula,28,13)::float8
            + substr(fc_calcula,41,13)::float8
            - substr(fc_calcula,54,13)::float8
          ) as valor_total
        from (
          select
            fc_calcula(
              arrecad.k00_numpre,
              arrecad.k00_numpar,
              arrecad.k00_receit,
              current_date,
              current_date,
              {$iAno}
            )
          from
            arrecad
          where
            $sWhereArrecad)
          as calculo
        ) as calculo_correcao,
          arrecad
      where
        {$sWhereArrecad}
     ";

    $rsArrecadDestino = $oDaoArrecad->sql_record($sSql);

    if (!$rsArrecadDestino || $oDaoArrecad->numrows == 0) {
      throw new DBException("Não foi possível encontrar as informações do débito.");
    }

    return db_utils::fieldsMemory($rsArrecadDestino, 0);
  }

  /**
   * @param $iNumpre
   * @param $iNumpar
   * @param null $iReceita
   * @param null $iAno
   * @param DBDate|null $oDataOperacao
   * @param DBDate|null $oDataVencimento
   * @return stdClass[]
   * @throws DBException
   */
  public static function getDebitosCorrigido($iNumpre, $iNumpar, $iReceita = null, $iAno = null, DBDate $oDataOperacao = null, DBDate $oDataVencimento = null) {

    if (!$iAno) {
      $iAno = db_getsession("DB_anousu");
    }

    if (!$oDataOperacao) {
      $oDataOperacao = new DBDate(date("Y-m-d"));
    }

    if (!$oDataVencimento) {
      $oDataVencimento = new DBDate(date("Y-m-d"));
    }

    $sCampos = "
      (select (
        substr(fc_calcula,15,13)::float8
        + substr(fc_calcula,28,13)::float8
        + substr(fc_calcula,41,13)::float8
        - substr(fc_calcula,54,13)::float8) as valor_total
      from (
        select fc_calcula(
          arrecad.k00_numpre,
          arrecad.k00_numpar,
          arrecad.k00_receit,
          '{$oDataOperacao->getDate()}',
          '{$oDataVencimento->getDate()}',
          {$iAno}
        )) as calculo
      ) as valor_atualizado, *";

    $oDaoDebito = new cl_arrecad();

    $aWhere = array(
      "k00_numpre = {$iNumpre}",
      "k00_numpar = {$iNumpar}"
    );

    if ($iReceita) {
      $aWhere[] = "k00_receit = {$iReceita}";
    }

    $sWhere = implode(' and ', $aWhere);
    $sSql = $oDaoDebito->sql_query_file(null, $sCampos, null, $sWhere);
    $rsDebitos = $oDaoDebito->sql_record($sSql);

    if (!$rsDebitos) {
      throw new DBException("Não foi possível encontrar as informações do Débito Corrigido.");
    }

    return db_utils::getCollectionByRecord($rsDebitos);
  }

  /**
   * @param $oDebitoReceita
   * @throws DBException
   */
  private function incluirArrecant($oDebitoReceita) {

    $oDaoArrecant             = new cl_arrecant;
    $oDaoArrecant->k00_numpre = $oDebitoReceita->k00_numpre;
    $oDaoArrecant->k00_numpar = $oDebitoReceita->k00_numpar;
    $oDaoArrecant->k00_numcgm = $oDebitoReceita->k00_numcgm;
    $oDaoArrecant->k00_dtoper = $oDebitoReceita->k00_dtoper;
    $oDaoArrecant->k00_receit = $oDebitoReceita->k00_receit;
    $oDaoArrecant->k00_hist   = $oDebitoReceita->k00_hist;
    $oDaoArrecant->k00_valor  = $oDebitoReceita->k00_valor;
    $oDaoArrecant->k00_dtvenc = $oDebitoReceita->k00_dtvenc;
    $oDaoArrecant->k00_numtot = $oDebitoReceita->k00_numtot;
    $oDaoArrecant->k00_numdig = $oDebitoReceita->k00_numdig;
    $oDaoArrecant->k00_tipo   = $oDebitoReceita->k00_tipo;
    $oDaoArrecant->k00_tipojm = $oDebitoReceita->k00_tipojm;
    $oDaoArrecant->incluir();

    if ($oDaoArrecant->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Pagamento do Débito.");
    }
  }

  /**
   * @param $iCodigoCreditoUtilizacao
   * @param $oDebitoReceita
   * @param $nValorCompensacao
   * @return boolean
   * @throws DBException
   */
  private function incluirCreditoUtilizacaoDestino($iCodigoCreditoUtilizacao, $oDebitoReceita, $nValorCompensacao) {

    $oDaoCreditoDestino                  = new cl_abatimentoutilizacaodestino();
    $oDaoCreditoDestino->k170_utilizacao = $iCodigoCreditoUtilizacao;
    $oDaoCreditoDestino->k170_numpre     = $oDebitoReceita->k00_numpre;
    $oDaoCreditoDestino->k170_numpar     = $oDebitoReceita->k00_numpar;
    $oDaoCreditoDestino->k170_hist       = $oDebitoReceita->k00_hist;
    $oDaoCreditoDestino->k170_receit     = $oDebitoReceita->k00_receit;
    $oDaoCreditoDestino->k170_tipo       = $oDebitoReceita->k00_tipo;
    $oDaoCreditoDestino->k170_valor      = $nValorCompensacao;
    $oDaoCreditoDestino->incluir();

    if ($oDaoCreditoDestino->erro_status == '0') {
      throw new DBException("Não foi possível salvar informações do Destino da Utilização de Crédito.");
    }

    return true;
  }

  /**
   * @param $oReciboReceita
   * @return cl_arrepaga
   * @throws DBException
   */
  private function incluirArrepaga($oReciboReceita) {

    $oDaoArrepaga             = new cl_arrepaga();
    $oDaoArrepaga->k00_numcgm = $oReciboReceita->k00_numcgm;
    $oDaoArrepaga->k00_dtoper = $oReciboReceita->k00_dtoper;
    $oDaoArrepaga->k00_receit = $oReciboReceita->k00_receit;
    $oDaoArrepaga->k00_hist   = $oReciboReceita->k00_hist;
    $oDaoArrepaga->k00_valor  = $oReciboReceita->k00_valor;
    $oDaoArrepaga->k00_dtvenc = $oReciboReceita->k00_dtvenc;
    $oDaoArrepaga->k00_numpre = $oReciboReceita->k00_numpre;
    $oDaoArrepaga->k00_numpar = $oReciboReceita->k00_numpar;
    $oDaoArrepaga->k00_numtot = $oReciboReceita->k00_numtot;
    $oDaoArrepaga->k00_numdig = $oReciboReceita->k00_numdig;
    $oDaoArrepaga->k00_conta  = "0";
    $oDaoArrepaga->k00_dtpaga = $oReciboReceita->k00_dtpaga;
    $oDaoArrepaga->incluir();

    if ($oDaoArrepaga->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Pagamento do Débito.");
    }

    return true;
  }

  /**
   * @return mixed
   */
  private function gerarNovoNumpre() {

    $rsNumpre    = db_query("select nextval('numpref_k03_numpre_seq') as numpre");
    $iNovoNumpre = db_utils::fieldsMemory($rsNumpre, 0)->numpre;

    return $iNovoNumpre;
  }

  /**
   * @param $iCodigoAbatimento
   * @param $iNumpreRecibo
   * @param $iNumpreOriginal
   * @return string|integer
   * @throws DBException
   */
  private function incluirAbatimentoRecibo($iCodigoAbatimento, $iNumpreOriginal, $iNumpreRecibo) {

    $oDaoAbatimentoRecibo                      = new cl_abatimentorecibo();
    $oDaoAbatimentoRecibo->k127_abatimento     = $iCodigoAbatimento;
    $oDaoAbatimentoRecibo->k127_numpreoriginal = $iNumpreRecibo;
    $oDaoAbatimentoRecibo->k127_numprerecibo   = $iNumpreOriginal;
    $oDaoAbatimentoRecibo->incluir(null);

    if ($oDaoAbatimentoRecibo->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Recibo.");
    }

    return $oDaoAbatimentoRecibo->k127_sequencial;
  }

  /**
   * @param integer $iNovoNumpre
   * @param integer $iNumcgm
   * @return boolean
   * @throws DBException
   */
  private function incluirVinculoNumcgm($iNumcgm, $iNovoNumpre) {

    $oDaoArrenumcgm = new cl_arrenumcgm();
    $sSqlArrenumcgm = $oDaoArrenumcgm->sql_query_file(
      null, null, '*', null, "k00_numcgm = {$iNumcgm} and k00_numpre = {$iNovoNumpre} limit 1"
    );
    $rsArrenumcgm   = db_query($sSqlArrenumcgm);

    if (!$rsArrenumcgm) {
      throw new DBException("Não foi possível encontrar as informações do CGM.");
    }

    if (pg_numrows($rsArrenumcgm) == 0) {

      $oDaoArrenumcgm = new cl_arrenumcgm();
      $oDaoArrenumcgm->incluir($iNumcgm, $iNovoNumpre);

      if ($oDaoArrenumcgm->erro_status == '0') {
        throw new DBException("Não foi possível salvar vinculo entre o CGM e o Novo Numpre.");
      }
    }

    return true;
  }

  /**
   * @param integer $iNumpreRecibo
   * @return stdClass[]
   * @throws DBException
   */
  private function getDebitosRecibo($iNumpreRecibo) {

    $sSqlDebitosRecibo = "
          select distinct
            arrecad.k00_numpre,
            arrecad.k00_numpar,
            arrecad.k00_hist,
            arrecad.k00_receit,
            arrecad.k00_tipo
          from recibopaga
            inner join arrecad on arrecad.k00_numpre = recibopaga.k00_numpre
                                  and arrecad.k00_numpar = recibopaga.k00_numpar
                                  and arrecad.k00_receit = recibopaga.k00_receit
          where recibopaga.k00_numnov = {$iNumpreRecibo}
          order by arrecad.k00_numpre,
                   arrecad.k00_numpar,
                   arrecad.k00_receit
        ";
    $rsDebitosRecibo   = db_query($sSqlDebitosRecibo);

    if (!$rsDebitosRecibo) {
      throw new DBException("Nâo foi possível encontrar as informações dos Débitos no Recibo.");
    }

    return db_utils::getCollectionByRecord($rsDebitosRecibo);
  }

  /**
   * @param $nValorDisponivel
   * @throws DBException
   */
  private function alterarValorDisponivelCredito($nValorDisponivel) {

    $oDaoCredito                       = new cl_abatimento();
    $oDaoCredito->k125_valordisponivel = "{$nValorDisponivel}";
    $oDaoCredito->k125_sequencial      = $this->getCodigoCredito();
    $oDaoCredito->alterar($this->getCodigoCredito());

    if ($oDaoCredito->erro_status == '0') {
      throw new DBException("Não foi possível salvar as informações do Crédito.");
    }
  }
}
