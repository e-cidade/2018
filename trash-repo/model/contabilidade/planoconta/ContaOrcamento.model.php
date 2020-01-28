<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once "model/contabilidade/planoconta/ContaPlano.model.php";
require_once ("model/orcamento/ReceitaContabilRepository.model.php");
require_once ("model/configuracao/InstituicaoRepository.model.php");
/**
 *
 * Classe respons�vel pelo Plano de conta or�ament�rio (Receita / Despesa)
 * @author andrio.costa
 * @name ContaOrcamento
 * @package contabilidade
 * @subpackage planoconta
 */
class ContaOrcamento extends ContaPlano {

  private $sMsgErro;
  private $oPlanoContaPCASP              = null;
  private $oDaoConplanoconplanoorcamento = null;
  private $aGrupoConta                   = array();
  
  /**
   *
   * Classe construtora, Se setado os par�metros busca os dados
   * @param integer $iCodigoConta codcon
   * @param integer $iAnoUsu Ano
   * @param integer $iReduz c�digo do reduzido
   */
  public function __construct($iCodigoConta = null, $iAnoUsu = null, $iReduz = null, $iInstituicao = null) {

    //fazer passar institui��o
    $this->setNomeDao("conplanoorcamento");
      if ((!empty($iCodigoConta) && !empty($iAnoUsu)) || (!empty($iAnoUsu) && !empty($iReduz))) {
      parent::__construct($iCodigoConta, $iAnoUsu, $iReduz, $iInstituicao);
    }
    $this->oDaoConplanoconplanoorcamento = db_utils::getDao("conplanoconplanoorcamento");
  }
  /**
   * Seter PlanoContaPCASP
   * @param PlanoContaPCASP $oPlanoContaPCASP
   */
  public function setPlanoContaPCASP(ContaPlanoPCASP $oPlanoContaPCASP) {
    $this->oPlanoContaPCASP = $oPlanoContaPCASP;
  }

  /**
   * Retorna objeto PlanoContaPCASP
   * @return PlanoContaPCASP
   */
  public function getPlanoContaPCASP() {


    if ($this->oPlanoContaPCASP == null) {

      $iCodigoInstituicao = $this->getInstituicao();
      $sWhere  = " c72_conplanoorcamento = {$this->getCodigoConta()} and c72_anousu = {$this->getAno()}";
      $sWhere .= " and (case                                                                         ";
      $sWhere .= "        when conplanoreduz.c61_instit is not null                                  ";
      $sWhere .= "          then case                                                                ";
      $sWhere .= "                 when conplanoreduz.c61_instit = {$iCodigoInstituicao}             ";
      $sWhere .= "                   then true                                                       ";
      $sWhere .= "                   else false                                                      ";
      $sWhere .= "                end                                                                ";
      $sWhere .= "          else true                                                                ";
      $sWhere .= "      end)                                                                         ";
      $sWhere .= " and (case                                                                         ";
      $sWhere .= "        when conplanoorcamentoanalitica.c61_instit is not null                     ";
      $sWhere .= "          then case                                                                ";
      $sWhere .= "                when conplanoorcamentoanalitica.c61_instit = {$iCodigoInstituicao} ";
      $sWhere .= "                  then true                                                        ";
      $sWhere .= "                  else false                                                       ";
      $sWhere .= "               end                                                                 ";
      $sWhere .= "          else true                                                                ";
      $sWhere .= "      end)";
      $sSqlVerificaContaPCASP = $this->oDaoConplanoconplanoorcamento->sql_query_pcasp_analitica(null,
                                                                                                "c72_conplano,
                                                                                                c72_anousu,
                                                                                                conplanoreduz.c61_reduz",
                                                                                                null,
                                                                                                $sWhere);

      $rsVerificaContaPCASP   = $this->oDaoConplanoconplanoorcamento->sql_record($sSqlVerificaContaPCASP);

      if ($rsVerificaContaPCASP && $this->oDaoConplanoconplanoorcamento->numrows > 0 ) {

        $oVerificaContaPCASP = db_utils::fieldsMemory($rsVerificaContaPCASP,0);
        $this->setPlanoContaPCASP(ContaPlanoPCASPRepository::getContaByCodigo($oVerificaContaPCASP->c72_conplano,
                                                                              $oVerificaContaPCASP->c72_anousu,
                                                                              $oVerificaContaPCASP->c61_reduz,
                                                                              $iCodigoInstituicao
                                                                             )
                                 );
      }
    }
    return $this->oPlanoContaPCASP;
  }

  /**
   *
   * Verifica se os dados est�o ok, e persite
   */
  public function salvar() {

    if(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (!$this->validarDadosContaOrcamentaria()) {
      throw new Exception($this->sMsgErro);
    }
    /**
     * Inclui / Altera conta or�ament�ria s�mente se o c�digo do sistema conta for == 6
     * se falhar a inclus�o lan�a exce��o
     */
    $this->persistirContaOrcamentaria();
    /**
     * Inclui / Altera o elemento da conta
     */
    $this->persistirElementoOuFontes();

  }

  /**
   *
   * Valida Dados obrigat�rios no plano de contas
   * @throws Exception
   * @return boolean
   */
  private function validarDadosContaOrcamentaria() {


    if (!$this->validaEstrutural()) {
      return false;
    }

    if (empty($this->iAno)) {

      $this->sMsgErro = "Ano n�o pode ser nulo!";
      return false;
    }

    return true;
  }

  /**
   *
   * Salva / Altera dados na conplanoorcamento
   * @throws Exception
   */
  private function persistirContaOrcamentaria() {

    if ($this->getPlanoContaPCASP() == null || !$this->getPlanoContaPCASP() instanceof ContaPlanoPCASP) {
      throw new Exception('Conta de v�nculo do PCASP deve ser informada.');
    }
    $iCodigoConta          = null;
    $iUltimoAno            = $this->getUltimoAnoPlano();
    $oDaoPlanoOrcamentario = db_utils::getDao("conplanoorcamento");

    $oDaoPlanoOrcamentario->c60_estrut                   = $this->getEstrutural();
    $oDaoPlanoOrcamentario->c60_descr                    = $this->getDescricao();
    $oDaoPlanoOrcamentario->c60_finali                   = $this->getFinalidade();
    $oDaoPlanoOrcamentario->c60_codsis                   = $this->getSistemaConta()->getCodigoSistemaConta();
    $oDaoPlanoOrcamentario->c60_codcla                   = $this->getClassificacaoConta()->getCodigoClasse();
    $oDaoPlanoOrcamentario->c60_consistemaconta          = $this->getSubSistema()->getCodigo();
    $oDaoPlanoOrcamentario->c60_identificadorfinanceiro  = $this->getIdentificadorFinanceiro();
    $oDaoPlanoOrcamentario->c60_naturezasaldo            = $this->getNaturezaSaldo();
    $oDaoPlanoOrcamentario->c60_funcao                   = $this->getFuncao();
    /**
     * Se o c�digo da conta estiver setado, entra em modo de altera��o
     * Se n�o inclui at� o ano m�ximo do plano
     */
    for ($iAnoInclusao = $this->getAno(); $iAnoInclusao <= $iUltimoAno; $iAnoInclusao++) {

      $oDaoPlanoOrcamentario->c60_anousu  = $iAnoInclusao;
      if (!empty($this->iCodigoConta)) {

        $oDaoPlanoOrcamentario->c60_codcon = $this->getCodigoConta();
        $oDaoPlanoOrcamentario->alterar($this->getCodigoConta(), $iAnoInclusao);

        $iCodigoConta = $oDaoPlanoOrcamentario->c60_codcon;
      } else {

        $oDaoPlanoOrcamentario->incluir($iCodigoConta, $iAnoInclusao);
        $iCodigoConta = $oDaoPlanoOrcamentario->c60_codcon;
      }
      if ($oDaoPlanoOrcamentario->erro_status == "0") {
        throw new Exception($oDaoPlanoOrcamentario->erro_msg);
      }
      /*
       * Caso tenha contaPCASP faz o vinculo
       */

      if ( $this->getPlanoContaPCASP() != null ) {


        $sWhereContaVinculo = "c72_conplanoorcamento = {$iCodigoConta} and c72_anousu = {$iAnoInclusao}";
        $this->oDaoConplanoconplanoorcamento->excluir(null, $sWhereContaVinculo);
        if ($oDaoPlanoOrcamentario->erro_status == 0) {
          throw new Exception("Erro ao excluir dados da vincula��o de contas");
        }

        $this->oDaoConplanoconplanoorcamento->c72_conplano          = $this->getPlanoContaPCASP()->getCodigoConta();
        $this->oDaoConplanoconplanoorcamento->c72_conplanoorcamento = $iCodigoConta;
        $this->oDaoConplanoconplanoorcamento->c72_anousu            = $iAnoInclusao;

        $this->oDaoConplanoconplanoorcamento->incluir(null);

        if ( $this->oDaoConplanoconplanoorcamento->erro_status == "0" ) {

          throw new Exception($this->oDaoConplanoconplanoorcamento->erro_msg);
        }
      }
    } // end for
    $this->setCodigoConta($iCodigoConta);
    return true;
  }

  /**
   *
   * Vincula a conta banc�ria ao plano de contas
   */
  private function persistirContaBancaria() {

    $oDaoOrcamentoContaBancaria = db_utils::getDao("conplanoorcamentocontabancaria");

    $sCampos          = "c56_sequencial";
    $sWhere           = "     c56_codcon = {$this->getCodigoConta()} ";
    $sWhere          .= " and c56_anousu = {$this->getAno()}         ";
    $sSqlVerificaCona = $oDaoOrcamentoContaBancaria->sql_query_file(null, $sCampos, null, $sWhere);
    $rsVerificaConta  = $oDaoOrcamentoContaBancaria->sql_record($sSqlVerificaCona);

    $oDaoOrcamentoContaBancaria->c56_contabancaria = $this->getContaBancaria()->getCodigoBanco();
    $oDaoOrcamentoContaBancaria->c56_codcon        = $this->getCodigoConta();
    $oDaoOrcamentoContaBancaria->c56_anousu        = $this->getAno();

    if ($oDaoOrcamentoContaBancaria->numrows > 0) {

      $iCodigoConta = db_utils::fieldsMemory($rsVerificaConta, 0)->c56_sequencial;
      $oDaoOrcamentoContaBancaria->alterar($iCodigoConta);
    } else {
      $oDaoOrcamentoContaBancaria->incluir(null);
    }
    if ($oDaoOrcamentoContaBancaria->erro_status == 0) {
      throw new Exception($oDaoOrcamentoContaBancaria->erro_msg);
    }
    return true;
  }

  /**
   *
   * Verifica o estrutural e vincula o plano de contas h�:
   *  -> Uma fonte de recurso
   *  -> ou Um elemento de despesa
   */
  private function persistirElementoOuFontes() {

    $iMaxAno  = $this->getUltimoAnoPlano();
    $iAnoUsu  = db_getsession("DB_anousu");

    /**
     * Vincula a um elemento de despesa
     */
    if ($this->isDespesa()) {

      $oDaoElemento = db_utils::getDao("orcelemento");
      $oDaoElemento->o56_codele   = $this->getCodigoConta();
      $oDaoElemento->o56_elemento = substr($this->getEstrutural(), 0,13);
      $oDaoElemento->o56_descr    = $this->getDescricao();
      $oDaoElemento->o56_finali   = $this->getFinalidade();
      $oDaoElemento->o56_orcado   = 'true';

      for ($iAno = $iAnoUsu; $iAno <= $iMaxAno; $iAno++) {

        $sWhereVerificaElemento  = "     o56_codele = {$this->getCodigoConta()} ";
        $sWhereVerificaElemento .= " and o56_anousu = {$iAno}                   ";
        $sSqlVerificaElemento    = $oDaoElemento->sql_query_file(null, null, "*", null, $sWhereVerificaElemento);
        $oDaoElemento->sql_record($sSqlVerificaElemento);
        $oDaoElemento->o56_anousu   = $iAno;

        if ($oDaoElemento->numrows > 0) {
          $oDaoElemento->alterar($this->getCodigoConta(), $iAno);
        } else {
          $oDaoElemento->incluir($this->getCodigoConta(), $iAno);
        }
        if ($oDaoElemento->erro_status == 0) {
          throw new Exception(str_replace("\\n", "\n", $oElemento->erro_msg));
        }
      }
    } else if ($this->isReceita()) {

      /**
       * Vincula a uma fonte de receita
       */
      $oDaoFontes = db_utils::getDao("orcfontes");
      $oDaoFontes->o57_codfon = $this->getCodigoConta();
      $oDaoFontes->o57_fonte  = $this->getEstrutural();
      $oDaoFontes->o57_descr  = $this->getDescricao();
      $oDaoFontes->o57_finali = $this->getFinalidade();

      for ($iAno = $iAnoUsu; $iAno <= $iMaxAno; $iAno++) {

        $sWhereVerificaFontes   = "     o57_codfon = {$this->getCodigoConta()} ";
        $sWhereVerificaFontes  .= " and o57_anousu = {$iAno}                   ";
        $sSqlVerificaFontes     = $oDaoFontes->sql_query_file(null, null, "*", null, $sWhereVerificaFontes);
        $oDaoFontes->sql_record($sSqlVerificaFontes);
        $oDaoFontes->o57_anousu = $iAno;

        if($oDaoFontes->numrows > 0) {
          $oDaoFontes->alterar($this->getCodigoConta(), $iAno);
        } else {
          $oDaoFontes->incluir($this->getCodigoConta(), $iAno);
        }
        if ($oDaoFontes->erro_status == 0) {
          throw new Exception(str_replace("\\n", "\n", $oDaoFontes->erro_msg));
        }
      }
    }
    return true;
  }
  
  /**
   * Busca eventos contabeis pelo elemento da tabela contranslrelemento do reduzido criado
   *
   * @access public
   * @return bool | array
   */
  public function getEventosContabeisPeloElemento() {
  	
    $aEventoContabil       = array();
    $aComparaDebitoCredito = array(RegraLancamentoContabil::COMPARA_DEBITO, RegraLancamentoContabil::COMPARA_CREDITO);
    $sComparaDebitoCredito = implode(',', $aComparaDebitoCredito);

  	$oDaoConTranslrElemento = db_utils::getDao("contranslrelemento");
  	$sEstrutural            = $this->getEstrutural();
  	$sEstruturaMae          = db_le_mae_conplano($sEstrutural) ; 

  	$sWereVinculo = "c114_elemento = '{$sEstruturaMae}' and c47_compara in({$sComparaDebitoCredito})" ;
  	$sSqlVinculo  = $oDaoConTranslrElemento->sql_query (null, "c46_seqtranslan", null, $sWereVinculo);
  	$rsVinculo    = $oDaoConTranslrElemento->sql_record($sSqlVinculo);

  	if ( $oDaoConTranslrElemento->numrows == 0 ) {
      return $aEventoContabil;
    }

    foreach( db_utils::getCollectionByRecord($rsVinculo) as $oDadosEventoContabil ) {

      $oEventoContabilLancamento = new EventoContabilLancamento($oDadosEventoContabil->c46_seqtranslan); 
      $oEventoContabil = EventoContabil::getInstanciaPorCodigo($oEventoContabilLancamento->getSequencialTransacao());
      $aEventoContabil[] = $oEventoContabil;
    } 
    
    return $aEventoContabil; 
  }
  
  /**
   *
   * Persiste Reduzido
   * @throws Exception
   */
  public function persistirReduzido() {

    if(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (empty($this->iCodigoConta)) {
      throw new Exception("C�digo da conta esta nulo.");
    }

    $oDaoOrcamentoAnalitica = db_utils::getDao("conplanoorcamentoanalitica");

    if ($this->hasReduzidoAnoInstituicao() && $this->getReduzido() == "") {
      throw new Exception("Existe uma conta reduzida para o ano e institui��o informados.");
    }

    /**
    * Insere os dados de acordo com o �ltimo ano cadastrado na tabela conplanoanalitica.
    * Percorre um FOR inserindo na conplanoorcamentoanalitica utilizando o mesmo c�digo reduzido (c61_reduz) sempre.
    */

    $iUltimoAno   = $this->getUltimoAnoPlano();
    $lAlteraReduz = false;
    $iAnoInicio   = $this->getAno();
    for ($iAno = $iAnoInicio; $iAno <= $iUltimoAno; $iAno++) {

    	$oDaoOrcamentoAnalitica->c61_anousu        = $iAno;
      $oDaoOrcamentoAnalitica->c61_instit        = $this->getInstituicao();
      $oDaoOrcamentoAnalitica->c61_codigo        = $this->getRecurso();
      $oDaoOrcamentoAnalitica->c61_codcon        = $this->getCodigoConta();
      $oDaoOrcamentoAnalitica->c61_reduz         = $this->getReduzido();
      $oDaoOrcamentoAnalitica->c61_contrapartida = $this->getContraPartida();

      /**
       * Verifica se j� existe um reduzido cadastrado para o ano sendo percorrido no FOR.
       * Caso encontre algum registro, seta na vari�vel de controle $lAlteraReduz = true;
       */
      if ($this->getReduzido() != "") {

        $oDaoValidaAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
        $sSqlValidaAnalitica = $oDaoValidaAnalitica->sql_query_file($this->getReduzido(), $iAno);

        $rsValidaAnalitica   = $oDaoValidaAnalitica->sql_record($sSqlValidaAnalitica);

        if ($oDaoValidaAnalitica->numrows > 0) {

          $oDadosValidaAnalitica = db_utils::fieldsMemory($rsValidaAnalitica, 0);

          if ($oDadosValidaAnalitica->c61_instit != $this->getInstituicao()) {

            $this->setReduzido(null);
            $oDaoOrcamentoAnalitica->c61_reduz = null;
            $lAlteraReduz = false;
          }
        }
      }

      /**
       * Verifica se o reduzido est� vazio e a vari�vel $lAlteraReduz � falsa para definir se deve alterar o
       * registro ou incluir
       */


      if ($this->getReduzido() == "" || !$lAlteraReduz) {
      	
        $oDaoOrcamentoAnalitica->incluir($oDaoOrcamentoAnalitica->c61_reduz, $iAno);
        $this->setReduzido($oDaoOrcamentoAnalitica->c61_reduz);

      } else {

        $oDaoOrcamentoAnalitica->alterar($oDaoOrcamentoAnalitica->c61_reduz, $iAno);
      }

      $lAlteraReduz = false;

      if ($oDaoOrcamentoAnalitica->erro_status == 0) {

        $sMsgErro = $oDaoOrcamentoAnalitica->erro_msg;
        throw new Exception($sMsgErro);
      }
      $this->getSistemaConta()->integrarDados($this);
    }
    return true;
  }

  /**
   * Exclui a conta
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    $oDaoContaOrcamentaria = db_utils::getDao("conplanoorcamento");
    $oDaoAnalitica         = db_utils::getDao("conplanoorcamentoanalitica");
    $oDaoContaGrupo        = db_utils::getDao("conplanoorcamentogrupo");
    $oDaoVinculoConplano   = db_utils::getDao("conplanoconplanoorcamento");

    $sWhereVinculo  = "     c72_conplanoorcamento = {$this->getCodigoConta()} ";
    $sWhereVinculo .= " and c72_anousu >= {$this->getAno()}                   ";
    $oDaoVinculoConplano->excluir(null, $sWhereVinculo);

    if ($oDaoVinculoConplano->erro_status == "0") {

      $sMsgErro  = "N�o foi poss�vel remover o v�nculo com a conta do PCASP.\n\n";
      $sMsgErro .= str_replace("\\n", "\n", $oDaoVinculoConplano->erro_msg);
      throw new Exception($sMsgErro);
    }

    if ($this->isDespesa()) {

      $oDaoElemento   = db_utils::getDao('orcelemento');
      $sWhereDespesa  = "     o56_codele = {$this->getCodigoConta()} ";
      $sWhereDespesa .= " and o56_anousu >= {$this->getAno()}        ";
      $oDaoElemento->excluir(null, null, $sWhereDespesa);

      if ($oDaoElemento->erro_status == "0") {

        $sMsgErro  = "N�o foi poss�vel remover o elemento de despesa: {$this->getEstrutural()}\n\n";
        $sMsgErro .= str_replace("\\n", "\n", $oDaoElemento->erro_msg);
        throw new Exception($sMsgErro);
      }
    }

    if ($this->isReceita()) {

      $oDaoFontes     = db_utils::getDao('orcfontes');
      $sWhereReceita  = "     o57_codfon = {$this->getCodigoConta()} ";
      $sWhereReceita .= " and o57_anousu >= {$this->getAno()}        ";
      $oDaoFontes->excluir(null, null, $sWhereReceita);

      if ($oDaoFontes->erro_status == "0") {

        $sMsgErro  = "N�o foi poss�vel remover a fonte de receita: {$this->getEstrutural()}\n\n";
        $sMsgErro .= str_replace("\\n", "\n", $oDaoFontes->erro_msg);
        throw new Exception($sMsgErro);
      }
    }


    $aGruposContas = $this->getGruposContas();
    if ($aGruposContas) {
      foreach ($aGruposContas as $oGrupo) {
        $this->removeContaGrupo($oGrupo->c20_sequencial);
      }
    }
    $aContasReduzidas = $this->getContasReduzidas();

    if ($aContasReduzidas) {
      foreach ($aContasReduzidas as $oContaAnalitica) {

        $this->setReduzido($oContaAnalitica->c61_reduz);
        $this->excluirReduzido();
      }
    }
    $sWhereExcluir            = "     c60_codcon = {$this->getCodigoConta()} ";
    $sWhereExcluir            .= " and c60_anousu >= {$this->getAno()}        ";
    $rsExcluirContaOrcamento  = $oDaoContaOrcamentaria->excluir(null, null, $sWhereExcluir);

    if ($oDaoContaOrcamentaria->erro_status == "0") {

      $sMsgContaOrcamento  = "N�o foi poss�vel excluir os dados da conta or�ament�ria.\n\n";
      $sMsgContaOrcamento .= str_replace("\\n", "\n", $oDaoContaOrcamentaria->erro_msg);
      throw new Exception($sMsgContaOrcamento);
    }

    return true;
  }
  /**
  * Valida se j� existe um reduzido cadastrado para o Ano e Institui��o
  * @return boolean
  */
  public function hasReduzidoAnoInstituicao() {

    $oDaoReduzido       = db_utils::getDao("conplanoorcamentoanalitica");
    $sWhereReduzido     = "     c61_instit = {$this->getInstituicao()} ";
    $sWhereReduzido    .= " and c61_codcon = {$this->getCodigoConta()} ";
    $sWhereReduzido    .= " and c61_anousu = {$this->getAno()}         ";
    $sSqlReduzidoInstit = $oDaoReduzido->sql_query_file(null, null, "*", null, $sWhereReduzido);
    $rsReduzidoInstit   = $oDaoReduzido->sql_record($sSqlReduzidoInstit);
    if ($oDaoReduzido->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Testa se o estrutural � do grupo "4" ou "9" (Receita)
   * @return boolean
   */
  public function isReceita() {

    if ($this->getEstrutural() == null){
      return false;
    }
    if ( substr($this->getEstrutural(), 0, 1) == "4" ||
         substr($this->getEstrutural(), 0, 1) == "9" ) {
      return true;
    }else {
      return false;
    }
  }

  /**
   * Testa se o estrutural � do grupo "3" (Despesa)
   * @return boolean
   */
  public function isDespesa() {

    if ( $this->getEstrutural() == null ) {
      return false;
    }
    if (substr($this->getEstrutural(), 0, 1) == "3" ) {
      return true;
    }else {
      return false;
    }
  }



  /**
   *
   * Realiza uma s�rie de valida��es antes de remover o reduzido
   * @throws Exception
   */
  public function excluirReduzido() {

    if(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (empty($this->iCodigoConta)) {
      throw new Exception("C�digo da conta esta nulo.");
    }

    $iMaximoAno = $this->getUltimoAnoPlano();
    $iAnoUsu    = db_getsession("DB_anousu");

    for ($iAno = $iAnoUsu; $iAno <= $iMaximoAno; $iAno++) {

      /**
       * Verifica se a conta possui lan�amento cont�beis
       * Se sim n�o deixa excluir
       */
      if ($this->hasLancamentosContabeis($this->getReduzido(), $iAno)) {
        throw new Exception("Conta possui lan�amentos, n�o pode ser excluida !");
      }

      /**
       * Verifica PPA
       * Caso tenha estimativas de receita no ppa, nao pode excluir reduzido
       */
      if ($this->hasEstimativaPPA($this->getCodigoConta(), $iAno)) {

        $sMsg  = "Voc� est� tentando excluir uma receita com registro nas proje��es do PPA. Se realmente deseja fazer a";
        $sMsg .= "exclus�o, voc� dever� excluir ou substituir esta receita no menu: ";
        $sMsg .= "OR�AMENTO > PROCEDIMENTOS > PPA > RECEITAS DO PPA > ALTERA��O DE RECEITAS";
        throw new Exception($sMsg);
      }

      /**
       * Verifica se a conta possui saldo inicial
       * Se sim n�o deixa excluir
       */
      if ($this->hasSaldo($this->getReduzido(), $iAno)) {
        throw new Exception("N�o pode-se excluir esta conta porque ela possui saldo inicial lan�ado ! ");
      }

      /**
       * Verifica se possui conta na tesouraria
       * Se sim n�o deixa excluir
       */
      if ($this->getSistemaConta()->excluirDadosIntegrados($this)) {
        throw new Exception("Conta n�o pode ser excluida. Conta com movimentacao!");
      }

      /**
       * Verifica se a conta possue autorizacao de empenho
       * Se sim n�o deixa excluir
       */
      if ($this->hasEmpenhoAutorizado($this->getCodigoConta(), $iAno)) {

        $sMsg = "N�o � poss�vel excluir este reduzido, pois seu c�digo consta em Autoriza��es de Empenho. Verifique.";
        throw new Exception($sMsg);
      }

      /**
       * Verifica se a conta possue solicita��o de compras
       * Se sim n�o deixa excluir
       */
      if ($this->hasSolicitacaoCompra($this->getCodigoConta(), $iAno)) {

        $sMsg = "N�o � poss�vel excluir este reduzido, pois seu c�digo consta em Solicita��es de Compra. Verifique.";
        throw new Exception($sMsg);
      }
      if ($this->hasContaAnalitica($this->getReduzido(), $iAno)) {
        $this->removeContaAnalitica($this->getReduzido(), $iAno);
      }
    } //end for
  }

  /**
  *
  * Verifica se a conta possui saldo
  * Caso tenha, nao pode excluir reduzido
  */
  private function hasSaldo($iReduzido, $iAno) {

    $oDaoConPlanoExe = db_utils::getDao("conplanoexe");
    $sWhere          = " c62_anousu    = {$iAno}                ";
    $sWhere         .= " and c62_reduz = {$iReduzido}          ";
    $sWhere         .= " and (c62_vlrcre >0 or c62_vlrdeb >0 ) ";

    $sSqlVerificaSaldoIni = $oDaoConPlanoExe->sql_query_file(null, null, "*", null, $sWhere);
    $rsConPlanoExe        = $oDaoConPlanoExe->sql_record($sSqlVerificaSaldoIni);

    if ($oDaoConPlanoExe->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   *
   * Verifica se a conta possui estimativa de receita do PPA
   * Caso tenha, nao pode excluir reduzido
   */
  private function hasEstimativaPPA($iCodigoConta, $iAno) {

    $oDaoEstimativaPPA = db_utils::getDao("ppaestimativareceita");

    $sWhere            = "o06_codrec = {$iCodigoConta} and ";
    $sWhere           .= "o06_anousu = {$iAno} ";

    $sSQlVerificaPPA   = $oDaoEstimativaPPA->sql_query_file(null, "*", null, $sWhere);
    $rsVerificaPPA     = $oDaoEstimativaPPA->sql_record($sSQlVerificaPPA);

    if ($oDaoEstimativaPPA->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   *
   * Verifica se possui conta analitica
   * @return boolean
   */
  private function hasContaAnalitica($iReduzido, $iAno) {

    $oDaoContaAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
    $sWhere             = " c61_reduz      = {$iReduzido} ";
    $sWhere            .= " and c61_anousu = {$iAno}      ";

    $sSqlContaAnalitica = $oDaoContaAnalitica->sql_query_file(null, null, "*", null, $sWhere);
    $rsContaAnalitica = $oDaoContaAnalitica->sql_record($sSqlContaAnalitica);

    if ($oDaoContaAnalitica->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   *
   * Exclui a conta da conplanoorcamentoanalitica
   */
  private function removeContaAnalitica ($iReduzido, $iAno) {

    $oDaoContaAnalitica = db_utils::getDao("conplanoorcamentoanalitica");
    $rsContaAnalitica   = $oDaoContaAnalitica->excluir($iReduzido, $iAno);
    if ($oDaoContaAnalitica->erro_status == 0) {
      throw new Exception($clconplanoreduz->erro_msg);
    }
  }

  /**
  *
  * Verifica se o reduzido possui autoriza��o de empenho
  * Se tiv�r n�o pode deixar cancelar
  * @param ContaPlano $oContaPlano
  * @throws Exception
  */
  private function hasEmpenhoAutorizado($iCodigoConta, $iAno) {

    $oDaoEmpAutItem = db_utils::getDao("empautitem");
    $sSqlVerificaAutorizacoes = $oDaoEmpAutItem->sql_query(null,null,
                                                                   "*",
                                                                   "e55_codele",
                                                                   "e55_codele = {$iCodigoConta}
                                                                    and e54_anousu = {$iAno}"
    );
    $rsEmpAutItem = $oDaoEmpAutItem->sql_record($sSqlVerificaAutorizacoes);
    if ($oDaoEmpAutItem->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   *
   * Verifica se o reduzido possui solicita��o de compras.
   * Se tiv�r n�o pode deixar cancelar
   *
   * @param ContaPlano $oContaPlano
   * @throws Exception
   */
  private function hasSolicitacaoCompra($iCodigoConta, $iAno) {

    /**
     * N�o pode excluir reduzidos que possuam solicita��o de compras
     */
    $oDaoSolicitemEle = db_utils::getDao("solicitemele");
    $sSqlVerificaSolicitacoes  = $oDaoSolicitemEle->sql_query(null,null,
                                                                          "*",
                                                                          "pc18_codele",
                                                                          "pc18_codele = {$iCodigoConta}
                                                                           and o56_anousu = {$iAno}"
    );
    $rsSolicitemEle = $oDaoSolicitemEle->sql_record($sSqlVerificaSolicitacoes);
    if ($oDaoSolicitemEle->numrows > 0) {
      return true;
    }
    return false;
  }

  ##########################################################################################
  ######################################  GRUPO  ###########################################
  ##########################################################################################

  /**
   *
   * Retorna todos grupos de contas da conta e do ano atual
   * @return mixed
   */
  public function getGruposContas () {

    $oDaoGrupoConta = db_utils::getDao("conplanoorcamentogrupo");

    $sCampos    = "c20_sequencial, c20_descr";
    $sWhere     = "     c21_anousu = {$this->getAno()}         ";
    $sWhere    .= " and c21_codcon = {$this->getCodigoConta()} ";
    $sWhere    .= " and c21_instit = ". db_getsession("DB_instit");
    $sSqlGrupos = $oDaoGrupoConta->sql_query(null, $sCampos, "c20_sequencial", $sWhere);
    $rsGrupos   = $oDaoGrupoConta->sql_record($sSqlGrupos);

    if ($oDaoGrupoConta->numrows > 0) {

      return db_utils::getCollectionByRecord($rsGrupos, false, false, true);
    }
    return false;
  }

  /**
  *
  * Insere um Grupo de Contas
  * @throws Exception
  */
  public function addContaGrupo($iCodigoGrupo) {

    if(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (empty($this->iCodigoConta)) {
      throw new Exception("C�digo da conta esta nulo.");
    }

    if (!$this->hasContaGrupo($iCodigoGrupo)) {

      $iUltimoAno   = $this->getUltimoAnoPlano();

      $oDaoGrupoConta = db_utils::getDao("conplanoorcamentogrupo");

      $sWhere  = " c21_anousu > ".$this->getAno();
      $sWhere .= " and c21_codcon = ".$this->getCodigoConta();
      $sWhere .= " and c21_congrupo = {$iCodigoGrupo}";
      $sWhere .= " and c21_instit   = ". db_getsession("DB_instit");
      $oDaoGrupoConta->excluir(null, $sWhere);
      if ($oDaoGrupoConta->erro_status == "0") {
      	throw new Exception($oDaoGrupoConta->erro_msg);
      }

      for ($iAno = $this->getAno(); $iAno <= $iUltimoAno; $iAno++) {

        $oDaoGrupoConta->c21_anousu       = $iAno;
        $oDaoGrupoConta->c21_codcon       = $this->getCodigoConta();
        $oDaoGrupoConta->c21_congrupo     = $iCodigoGrupo;
        $oDaoGrupoConta->c21_instit       = db_getsession("DB_instit");
        $oDaoGrupoConta->incluir(null);

        if ($oDaoGrupoConta->erro_status == "0") {

          throw new Exception($oDaoGrupoConta->erro_msg);
        }
      }
    } else {
      throw new Exception("Este grupo j� esta vinculado ao c�digo da conta: {$this->getCodigoConta()}");
    }
  }

  /**
   *
   * Verifica se existe o c�digo "c21_congrupo" na conplanoorcamentogrupo para o ano e a conta setada
   * @param integer $iCodigoGrupo
   * @return boolean
   */
  private function hasContaGrupo ($iCodigoGrupo) {

    $oDaoGrupoConta = db_utils::getDao("conplanoorcamentogrupo");
    $sWhere         = "     c21_anousu = {$this->getAno()} ";
    $sWhere        .= " and c21_codcon = {$this->getCodigoConta()}";
    $sWhere        .= " and c21_congrupo = {$iCodigoGrupo}";
    $sWhere        .= " and c21_instit   = ". db_getsession("DB_instit");

    $sSqlGrupo = $oDaoGrupoConta->sql_query(null, "*", null, $sWhere);
    $rsGrupo   = $oDaoGrupoConta->sql_record($sSqlGrupo);

    if ($oDaoGrupoConta->numrows > 0) {
      return true;
    }
    return false;

  }

 /**
  *
  * Exclui o Grupo de uma Conta
  * @throws Exception
  */
  public function removeContaGrupo($iCodigoGrupo) {

    if(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (empty($this->iCodigoConta)) {
      throw new Exception("C�digo da conta esta nulo.");
    }

    $iUltimoAno     = $this->getUltimoAnoPlano();
    for ($iAno = $this->getAno(); $iAno <= $iUltimoAno; $iAno++) {

      $oDaoGrupoConta = db_utils::getDao("conplanoorcamentogrupo");
      $sWhere  = "     c21_anousu   = {$iAno}                   ";
      $sWhere .= " and c21_codcon   = {$this->getCodigoConta()} ";
      $sWhere .= " and c21_congrupo = {$iCodigoGrupo}           ";
      $sWhere .= " and c21_instit   = ". db_getsession("DB_instit");
      $oDaoGrupoConta->excluir(null, $sWhere);

      if ($oDaoGrupoConta->erro_status == "0") {
        throw new Exception($oDaoGrupoConta->erro_msg);
      }
    }
  }

  /**
  *
  * Retorna todas as contas reduzidas/analiticas
  * @param integer $iPlanoConta
  */
  public function getContasReduzidas() {

    if (!empty($this->iCodigoConta)) {

      $oDaoReduzido       = db_utils::getDao("conplanoorcamentoanalitica");
      $sWhereReduzido     = "     conplanoorcamentoanalitica.c61_codcon = {$this->getCodigoConta()}";
      $sWhereReduzido    .= " and conplanoorcamentoanalitica.c61_anousu = ".db_getsession("DB_anousu");
      $sSqlBuscaReduzidos = $oDaoReduzido->sql_query(null, null, "*", null, $sWhereReduzido);
      $rsBuscaReduzidos   = $oDaoReduzido->sql_record($sSqlBuscaReduzidos);

      if ($oDaoReduzido->numrows > 0) {
        return db_utils::getCollectionByRecord($rsBuscaReduzidos);
      }
    }
    return false;
  }

  /**
   * Retorna o c�digo da conta de acordo com o estrutural passado por par�metro para o m�todo.
   * @param string  $sEstrutural
   * @param integer $iAno
   * @throws BusinessException
   */
  public static function getContaPorEstrutural($sEstrutural, $iAno = null) {

    if (strlen($sEstrutural) < 15) {
      throw new BusinessException("Estrutural informado possui menos de 15 caracteres.");
    }

    if (empty($iAno)) {
      $iAno = db_getsession("DB_anousu");
    }
    $oDaoContaOrcamento      = db_utils::getDao("conplanoorcamento");
    $sWhereContaOrcamento    = "     c60_estrut = '{$sEstrutural}'";
    $sWhereContaOrcamento   .= " and c60_anousu = {$iAno}";
    $sSqlBuscaContaOrcamento = $oDaoContaOrcamento->sql_query_file(null, null, "c60_codcon", null, $sWhereContaOrcamento);
    $rsBuscaContaOrcamento   = $oDaoContaOrcamento->sql_record($sSqlBuscaContaOrcamento);

    if ($oDaoContaOrcamento->erro_status == "0") {
      throw new BusinessException("N�o foi localizado nenhuma conta do plano or�ament�rio com o estrutural {$sEstrutural}/{$iAno}.");
    }

    return ContaOrcamentoRepository::getContaByCodigo(db_utils::fieldsMemory($rsBuscaContaOrcamento, 0)->c60_codcon,
                                                      $iAno);
  }

  /**
   * Retorna a receita Contabil que a conta do plano or�ament�rio est� vinculada.
   * Caso n�o exista v�nculo com a receita, � retornado false.
   * @return ReceitaContabil
   */
  public function getReceitaContabil() {

    $oDaoReceitaOrcamento  = db_utils::getDao('orcreceita');
    $sWhereReceita         = "     o70_codfon = {$this->getCodigoConta()}";
    $sWhereReceita        .= " and o70_anousu = {$this->getAno()}";
    $sSqlBuscaReceita      = $oDaoReceitaOrcamento->sql_query_file(null, null, "o70_codrec", null, $sWhereReceita);
    $rsBuscaReceita        = $oDaoReceitaOrcamento->sql_record($sSqlBuscaReceita);

    if ($oDaoReceitaOrcamento->numrows > 0) {

      $iCodigoReceita = db_utils::fieldsMemory($rsBuscaReceita, 0)->o70_codrec;
      return ReceitaContabilRepository::getReceitaByCodigo($iCodigoReceita, $this->getAno());
    }
    return false;
  }
}