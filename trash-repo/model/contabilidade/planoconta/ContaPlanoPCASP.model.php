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
/**
 * Model para persistir dados na tabela conplano
 * @author Matheus Felini
 * @package contabilidade
 */
class ContaPlanoPCASP extends ContaPlano {

  /**
   *
   * Instancia de ContaCorrente
   * @var ContaCorrente
   */
  protected $oContaCorrente;

  public function __construct($iCodigoConta = null, $iAnoUsu = null, $iReduz = null, $iInstituicao = null) {


    $this->setNomeDao("conplano");
    if ((!empty($iCodigoConta)) || (!empty($iReduz)) && !empty($iAnoUsu)) {
      parent::__construct($iCodigoConta, $iAnoUsu, $iReduz, $iInstituicao);
    }

    if ($this->getCodigoConta() != "" && $this->getAno() != "") {

      $oDaoPCASPContaCorrente = db_utils::getDao("conplanocontacorrente");

      $sWhere  = "     c18_codcon = " . $this->getCodigoConta();
      $sWhere .= " and c18_anousu = " . $this->getAno();

      $sSqlVinculoContaCorrente = $oDaoPCASPContaCorrente->sql_query_file(null, "c18_contacorrente", null, $sWhere);
      $rsVinculoContaCorrente   = $oDaoPCASPContaCorrente->sql_record($sSqlVinculoContaCorrente);

      if ($oDaoPCASPContaCorrente->numrows == 1) {

        $iCodigoContaCorrente = db_utils::fieldsMemory($rsVinculoContaCorrente, 0)->c18_contacorrente;
        $this->setContaCorrente(ContaCorrenteRepository::getContaCorrenteByCodigo($iCodigoContaCorrente));
      }
    }
  }

  /**
   * Salva os dados na tabela conplano/conplanoreduz
   * @throws Exception
   * @return boolean
   */
  public function salvar() {

    /*
     * Verifica se existe transa��o com o banco de dados ativa
     */
    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    /*
     * Valida se o estrutural j� esta cadastrado
     */
    if ($this->hasEstruturalCadastrado() && !isset($this->iCodigoConta)) {
      throw new Exception("O estrutural {$this->getEstrutural()} j� est� cadastrado.");
    }

    /*
     * Verifica se o sistema de conta selecionado � o "Informa��es Patrimoniais". Caso seja
     * o indicador de superavit n�o pode ser o "N�o Se Aplica"
     */
    if ($this->getSubSistema()->getCodigo() == 2 && $this->getIdentificadorFinanceiro() == "N") {

      $sMsgErro  = "O subsistema de conta selecionado � de Informa��es Patrimoniais. Selecione o ";
      $sMsgErro .= "indicador de superavit financeiro.";
      throw new Exception($sMsgErro);
    }

    $oDaoConPlano                              = db_utils::getDao('conplano');
    $oDaoConPlano->c60_codcon                  = $this->getCodigoConta();
    $oDaoConPlano->c60_estrut                  = "{$this->getEstrutural()}";
    $oDaoConPlano->c60_descr                   = $this->getDescricao();
    $oDaoConPlano->c60_finali                  = $this->getFinalidade();
    $oDaoConPlano->c60_codsis                  = "{$this->getSistemaConta()->getCodigoSistemaConta()}";
    $oDaoConPlano->c60_codcla                  = "{$this->getClassificacaoConta()->getCodigoClasse()}";
    $oDaoConPlano->c60_consistemaconta         = "{$this->getSubSistema()->getCodigo()}";
    $oDaoConPlano->c60_identificadorfinanceiro = $this->getIdentificadorFinanceiro();
    $oDaoConPlano->c60_naturezasaldo           = $this->getNaturezaSaldo();
    $oDaoConPlano->c60_funcao                  = $this->getFuncao();

    /*
     * Inclui/Altera os dados caso j� estejam cadastrados.
     * Caso seja inclu�do um novo plano de conta, � setado o sequencial do c�digo da conta.
     */

    $lInclusao = true;
    if (!empty($this->iCodigoConta)) {
      $lInclusao = false;
    }
    $iUltimoAno = $this->getUltimoAnoPlano();
    for ($iAno = $this->getAno(); $iAno <= $iUltimoAno; $iAno++) {

      $oDaoConPlano->c60_anousu = $iAno;
      if (!empty($this->iCodigoConta) && !$lInclusao) {

        $oDaoConPlano->alterar($this->getCodigoConta(), $this->getAno());
      } else {

        $oDaoConPlano->incluir($this->getCodigoConta(), $iAno);
        $this->setCodigoConta($oDaoConPlano->c60_codcon);
      }

      if ($this->getSistemaConta()->getCodigoSistemaConta() == 6) {

        if ($this->getContaBancaria() instanceof ContaBancaria) {

          $oDaoConplanoContaBancaria                    = db_utils::getDao("conplanocontabancaria");
          $oDaoConplanoContaBancaria->c56_codcon        = $this->getCodigoConta();
          $oDaoConplanoContaBancaria->c56_anousu        = $iAno;
          $oDaoConplanoContaBancaria->c56_contabancaria = $this->getContaBancaria()->getSequencialContaBancaria();
          /**
           * Verificamos se a conta bancaria j� est� cadastrada no ano
           */
          $sWhere                         = "    c56_codcon = {$this->getCodigoConta()} ";
          $sWhere                        .= "and c56_anousu = {$iAno}";
          $sSqlVerificaDadosContaBancaria = $oDaoConplanoContaBancaria->sql_query_file(null,
                                                                                       "c56_sequencial",
                                                                                       null,
                                                                                       $sWhere
                                                                                       );
          $rsDadosContaBancaria           = $oDaoConplanoContaBancaria->sql_record($sSqlVerificaDadosContaBancaria);
          if ($oDaoConplanoContaBancaria->numrows > 0)  {

            $iCodigoSequencialContaBancaria = db_utils::fieldsMemory($rsDadosContaBancaria, 0)->c56_sequencial;

            $oDaoConplanoContaBancaria->c56_sequencial = $iCodigoSequencialContaBancaria;
            $oDaoConplanoContaBancaria->alterar($iCodigoSequencialContaBancaria);
          } else {

            $oDaoConplanoContaBancaria->incluir(null);
            $this->iCodigoContaBancaria = $oDaoConplanoContaBancaria->c56_sequencial;
          }

          if ($oDaoConplanoContaBancaria->erro_status == 0) {

            $sErroMsg  = "Erro ao salvar dados da conta banc�ria do conta {$this->getEstrutural()}.\n";
            $sErroMsg .= "Erro T�cnico:\n";
            $sErroMsg .= str_replace("\\n", "\n", $oDaoConplanoContaBancaria->erro_msg);
            throw new Exception($sErroMsg);
          }
        }
      } else if (isset($this->oDadosAnteriores->c60_codsis)) {

        if ($this->oDadosAnteriores->c60_codsis == 6) {

          /**
           * Pesquisamos os dados de todos os reduzidos da conta
           */
          $aReduzidos = $this->getContasReduzidas();
          if ($aReduzidos) {
            throw new Exception('Antes de mudar o detalhamento da conta, remova todos os seus reduzidos.');
          }
          $this->removerDadosContaBancaria($iAno);
        }
      }


      if ($oDaoConPlano->erro_status == 0) {

        $sMsgConPlano  = "O plano de contas n�o pode ser inserido.\n\n";
        $sMsgConPlano .= $oDaoConPlano->erro_msg;
        throw new Exception($sMsgConPlano);
      }

      /**
       * Sempre exclui vinculo com conta corrente se houver
       */
      $oDaoPCASPContaCorrente = db_utils::getDao("conplanocontacorrente");
      $sWhereExcluiVinculo    = "     c18_codcon = " . $this->getCodigoConta();
      $sWhereExcluiVinculo   .= " and c18_anousu = " . $iAno;

      $rsExcluiVinculoContaCorrente = $oDaoPCASPContaCorrente->excluir(null, $sWhereExcluiVinculo);

      $oContaCorrente = $this->getContaCorrente();

      /**
       * Insere vinculo com a conta corrente
       */
      if (!empty($oContaCorrente)) {

        if ($oContaCorrente instanceof ContaCorrente) {

          $oDaoPCASPContaCorrente->c18_sequencial    = null;
          $oDaoPCASPContaCorrente->c18_codcon        = $this->getCodigoConta();
          $oDaoPCASPContaCorrente->c18_anousu        = $iAno;
          $oDaoPCASPContaCorrente->c18_contacorrente = $this->getContaCorrente()->getCodigo();
          $oDaoPCASPContaCorrente->incluir(null);

          if ($oDaoPCASPContaCorrente->erro_status == 0){

              $sErroMsg  = "Erro ao salvar dados da conta corrente.\n";
              $sErroMsg .= "Erro T�cnico:\n";
              $sErroMsg .= str_replace("\\n", "\n", $oDaoPCASPContaCorrente->erro_msg);
              throw new Exception($sErroMsg);
          }
        }
      }
    }
    return true;
  }


  /**
   * Persiste dados reduzidos de uma conta
   * @throws Exception
   * @return true
   */
  public function persistirReduzido() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    $oDaoReduzido    = db_utils::getDao("conplanoreduz");
    $oDaoConPlanoExe = db_utils::getDao("conplanoexe");
    if ($this->hasReduzidoAnoInstituicao() && $this->getReduzido() != "" &&
        $this->getRecurso() == $this->oDadosAnteriores->c61_codigo) {
      throw new Exception("Existe uma conta reduzida para o ano e institui��o informados.");
    }


    /**
     * Insere os dados de acordo com o �ltimo ano cadastrado na tabela conplano.
     * Percorre um FOR inserindo na conplanoreduz utilizando o mesmo c�digo reduzido (c61_reduz) sempre.
     */
    $iUltimoAno   = $this->getUltimoAnoPlano();
    $lAlteraReduz = false;
    for ($iAno = $this->getAno(); $iAno <= $iUltimoAno; $iAno++) {

      $oDaoReduzido->c61_anousu        = $iAno;
      $oDaoReduzido->c61_instit        = $this->getInstituicao();
      $oDaoReduzido->c61_codigo        = $this->getRecurso();
      $oDaoReduzido->c61_codcon        = $this->getCodigoConta();
      $oDaoReduzido->c61_reduz         = $this->getReduzido();
      $oDaoReduzido->c61_contrapartida = $this->getContraPartida();

      /*
       * Dados que ser�o inseridos na tabela conplanoexe
       */
      $oDaoConPlanoExe->c62_anousu = $iAno;
      $oDaoConPlanoExe->c62_codrec = $this->getRecurso();
      $oDaoConPlanoExe->c62_reduz  = $this->getReduzido();

      if ($this->getReduzido() != "") {

        $sWhereReduzido  = "    c61_reduz  = {$this->getReduzido()}";
        $sWhereReduzido .= "and c61_instit = {$this->getInstituicao()}";
        $sWhereReduzido .= "and c61_anousu = {$iAno}";
        $oDaoValidaReduz = db_utils::getDao("conplanoreduz");
        $sSqlValidaReduz = $oDaoValidaReduz->sql_query_file(null, null, "*", null, $sWhereReduzido);
        $rsValidaReduz   = $oDaoValidaReduz->sql_record($sSqlValidaReduz);
        if ($oDaoValidaReduz->numrows > 0) {
          $lAlteraReduz = true;
        }

        $sWhereReduzido                  = " c61_reduz  = {$this->getReduzido()}";
        $sSqlVerificaReduzidoInstituicao = $oDaoReduzido->sql_query_file (null, null, "*","1 limit 1", $sWhereReduzido);
        $rsReduzidoInstituicao           = $oDaoReduzido->sql_record($sSqlVerificaReduzidoInstituicao);
        if ($oDaoReduzido->numrows > 0) {

            $oDadoReduzidoInstituicao = db_utils::fieldsMemory($rsReduzidoInstituicao, 0);
            if ($oDadoReduzidoInstituicao->c61_instit != $this->getInstituicao()) {
                $oDaoReduzido->c61_reduz = null;
            }
        }
      }

      if ($this->getReduzido() == "" && !$lAlteraReduz) {


        $oDaoReduzido->incluir($oDaoReduzido->c61_reduz, $iAno);
        $this->setReduzido($oDaoReduzido->c61_reduz);

        $oDaoConPlanoExe->c62_vlrcre = "0";
        $oDaoConPlanoExe->c62_vlrdeb = "0";
        $oDaoConPlanoExe->incluir($iAno, $this->getReduzido());
      } else {

        if (!$lAlteraReduz) {


          $oDaoReduzido->incluir($oDaoReduzido->c61_reduz, $iAno);
          $this->setReduzido($oDaoReduzido->c61_reduz);
          $oDaoConPlanoExe->c62_vlrcre = "0";
          $oDaoConPlanoExe->c62_vlrdeb = "0";
          $oDaoConPlanoExe->incluir($iAno, $this->getReduzido());
        } else {

          $oDaoReduzido->alterar($oDaoReduzido->c61_reduz, $iAno);
          $oDaoConPlanoExe->alterar($iAno, $oDaoReduzido->c61_reduz);
        }
      }

      $lAlteraReduz = false;

      if ($oDaoReduzido->erro_status == 0) {

        $sMsgReduzido  = "Ocorreu um erro ao processar os reduzidos.\n\n";
        $sMsgReduzido .= $oDaoReduzido->erro_msg;
        throw new Exception($sMsgReduzido);
      }

      if ($oDaoConPlanoExe->erro_status == 0) {

        $sMsgConPlanoExe  = "Ocorreu um erro ao processar os registros do exerc�cio.\n\n";
        $sMsgConPlanoExe .= $oDaoConPlanoExe->erro_msg;
        throw new Exception($sMsgConPlanoExe);
      }
    }
    $this->getSistemaConta()->integrarDados($this);
    return true;
  }

  /**
   * Exclui um plano de conta caso as valida��es ocorram com sucesso
   * @throws Exception
   * @return boolean
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }
    if ($this->getVinculoContaOrcamento()) {

      $sMsg  = "Existem vinculos dessa conta com contas do plano or�ament�rio.\n";
      $sMsg .= "Para a exclus�o dessa conta, reenvicule as contas do plano or�amentario com outra conta do PCASP.";
      throw new Exception($sMsg);
    }
    /*
     * Instancia as classes que ser�o utilizadas
     */
    $oDaoConPlano              = db_utils::getDao('conplano');
    $oDaoConPlanoReduz         = db_utils::getDao('conplanoreduz');
    $oDaoConlancamVal          = db_utils::getDao('conlancamval');
    $oDaoConPlanoExe           = db_utils::getDao('conplanoexe');
    $oDaoConPlanoContaBancaria = db_utils::getDao('conplanocontabancaria');
    $oDaoPCASPContaCorrente    = db_utils::getDao("conplanocontacorrente");

    $aContasReduzidas = $this->getContasReduzidas();
    if ($aContasReduzidas) {

      foreach ($aContasReduzidas as $oContaReduzida) {

        $this->iReduzido = $oContaReduzida->c61_reduz;
        $this->removerReduzido($oContaReduzida->c61_reduz);
      }
    }

    /**
     *  exclus�o da conplanoreduz
     */
    $oDaoConPlanoReduz->excluir(null, null, 'c61_codcon = ' .$this->getCodigoConta());
    if ($oDaoConPlanoReduz->erro_status == 0) {

      $sMsgConPlanoReduz  = "N�o � poss�vel excluir o plano de contas.\n\n";
      $sMsgConPlanoReduz .= $oDaoConPlanoReduz->erro_msg;
      throw new Exception($sMsgConPlanoReduz);
    }



    /**
     * Exclui da tabela conplano
     */
    $iUltimoAno   = $this->getUltimoAnoPlano();
    $lAlteraReduz = false;
    for ($iAno = $this->getAno(); $iAno <= $iUltimoAno; $iAno++) {

      /**
       * Exclui os Vinculos com a Conta Corrente
       */
      $sWhereExcluiVinculoContaCorrente  = " c18_codcon = " . $this->getCodigoConta();
      $sWhereExcluiVinculoContaCorrente .= " and c18_anousu = " . $this->getAno();
      $rsExcluiVinculoContaCorrente      = $oDaoPCASPContaCorrente->excluir(null, $sWhereExcluiVinculoContaCorrente);

      /**
       * Exclui os Dados da Conta Banc�ria
       */
      $this->removerDadosContaBancaria($iAno);
      $rsExcluiConPlano = $oDaoConPlano->excluir($this->getCodigoConta(), $iAno);
      if ($oDaoConPlano->erro_status == 0) {

        $sMsgConPlano  = "N�o � poss�vel excluir o plano de contas.\n\n";
        $sMsgConPlano .= $oDaoConPlano->erro_msg;
        throw new Exception($sMsgConPlano);
      }
    }
    return true;
  }

  /**
   * Fun��o que remove o reduzido de uma conta plano sint�tica
   * @param integer $iCodigoReduzido - C�digo reduzido (conplanoreduz)
   */
  public function removerReduzido($iCodigoReduzido) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    $iUltimoAno = $this->getUltimoAnoPlano();
    $iAnoUso    = db_getsession("DB_anousu");
    for ($iAnoFor = $iAnoUso; $iAnoFor <= $iUltimoAno; $iAnoFor++) {

      /**
       * Valida se existem lan�amentos cont�beis para o ano e reduzido informado
       */
      if ($this->hasLancamentosContabeis($iCodigoReduzido, $iAnoFor)) {
        throw new Exception("Conta possui lan�amentos, n�o pode ser excluida.");
      }

      /**
       * Valida se existe saldo lan�ado para a conta no ano e reduzido informado
       */
      if ($this->hasMovimentacao($iCodigoReduzido, $iAnoFor)) {
        throw new Exception("Esta conta n�o pode ser exclu�da pois possui saldo inicial lan�ado.");
      }

      /**
       * Exclui da tabela conplanoexe
       */
      $oDaoConPlanoExe = db_utils::getDao("conplanoexe");
      $sSqlConPlanoExe = $oDaoConPlanoExe->sql_query_file($iAnoFor, $iCodigoReduzido);
      $rsConPlanoExe   = $oDaoConPlanoExe->sql_record($sSqlConPlanoExe);
      if ($oDaoConPlanoExe->numrows > 0) {

        $rsExcluiConPlanoExe = $oDaoConPlanoExe->excluir($iAnoFor, $iCodigoReduzido);
        if ($oDaoConPlanoExe->erro_status == 0) {

          $sMsgConPlanoExe  = "N�o � poss�vel excluir o plano de conta do exerc�cio {$iAnoFor}.\n\n";
          $sMsgConPlanoExe .= $oDaoConPlanoExe->erro_msg;
          throw new Exception($sMsgConPlanoExe);
        }
      }

      /**
       * Exclui da tabela conplanoreduz
       */
      $oDaoConPlanoReduz = db_utils::getDao("conplanoreduz");
      $sSqlConPlanoReduz = $oDaoConPlanoReduz->sql_query_file($iCodigoReduzido, $iAnoFor);
      $rsConPlanoReduz   = $oDaoConPlanoReduz->sql_record($sSqlConPlanoReduz);

      /**
       * altera��o realizada para excluir os reduzidos a partir do codcon a qual pertence,
       * da maneira em que est� ele exclui somente o reduzido do ano.
       */

     $oDaoConPlanoReduz->excluir (null, null, "c61_reduz = {$iCodigoReduzido}");
     if ($oDaoConPlanoReduz->erro_status == 0) {

       $sMsgConPlanoReduz  = "N�o � poss�vel excluir as contas reduzidas deste plano.\n\n";
       $sMsgConPlanoReduz .= $oDaoConPlanoReduz->erro_msg;
       throw new Exception($sMsgConPlanoReduz);
     }


     $sWhereExistReduzido = " c61_codcon = " .$this->getCodigoConta();
     $sSqlExistReduzido   = $oDaoConPlanoReduz->sql_query_file(null, null, "*", null, $sWhereExistReduzido);
     $rsExistReduzido     = $oDaoConPlanoReduz->sql_record($sSqlExistReduzido);
     $iNumrowsSelect      = $oDaoConPlanoReduz->numrows;

      /**
       * Exclui os Vinculos com a Conta Corrente caso n�o tenha mais reduzidos na conta do plano
       */
      if ($iNumrowsSelect == 0 ) {

        $oDaoPCASPContaCorrente            = db_utils::getDao("conplanocontacorrente");
        $sWhereExcluiVinculoContaCorrente  = "     c18_codcon = " . $this->getCodigoConta();
        $sWhereExcluiVinculoContaCorrente .= " and c18_anousu >= " . $iAnoUso;
        $rsExcluiVinculoContaCorrente      = $oDaoPCASPContaCorrente->excluir(null, $sWhereExcluiVinculoContaCorrente);
      }

      $this->getSistemaConta()->excluirDadosIntegrados($this);
    }
    return true;
  }

  /**
   * Verifica se existem movimenta��o com o reduzido e ano. Retorna TRUE caso haja saldo.
   * @param integer $iReduzido
   * @param integer $iAno
   * @return boolean
   */
  public function hasMovimentacao($iReduzido, $iAno) {

    $oDaoConPlanoExe    = db_utils::getDao("conplanoexe");
    $sWhereConPlanoExe  = "     (c62_vlrcre > 0 or c62_vlrdeb > 0) ";
    $sWhereConPlanoExe .= " and c61_reduz = {$iReduzido}        ";
    $sWhereConPlanoExe .= " and c61_anousu = {$iAno}             ";
    $sSqlConPlanoExe    = $oDaoConPlanoExe->sql_query_file(null, null, "*", null, $sWhereConPlanoExe);
    $rsConPlanoExe      = $oDaoConPlanoExe->sql_record($sSqlConPlanoExe);
    if ($oDaoConPlanoExe->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Valida se j� existe um reduzido cadastrado para o Ano e Institui��o
   * @return boolean
   */
  public function hasReduzidoAnoInstituicao() {

    $oDaoReduzido       = db_utils::getDao("conplanoreduz");
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
   * Busca todos os reduzidos do plano de conta passado por par�metro
   * @param integer $iPlanoConta
   * @return mixed
   */
  public function getContasReduzidas() {

    if ($this->getCodigoConta() != "") {

      $oDaoReduzido       = db_utils::getDao("conplanoreduz");
      $sWhereReduzido     = "    conplanoreduz.c61_codcon = {$this->getCodigoConta()}";
      $sWhereReduzido    .= "and conplanoreduz.c61_anousu = ".db_getsession("DB_anousu");
      $sSqlBuscaReduzidos = $oDaoReduzido->sql_query_reduz_contacorrente(null, null, "*", null, $sWhereReduzido);
      $rsBuscaReduzidos   = $oDaoReduzido->sql_record($sSqlBuscaReduzidos);

      if ($oDaoReduzido->numrows > 0) {
        return db_utils::getCollectionByRecord($rsBuscaReduzidos, false, false, true);
      }
    }
    return false;
  }

  /**
   * Vincula um diversos planos de contas do plano or�ament�rio a uma conta do plano PCASP
   * @param integer $iCodigoPlanoOrcamento
   */
  public function vinculaPlanoContasOrcamento($iCodigoPlanoOrcamento) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    /*
     * Verifica se j� existe o plano vinculado.
     */
    $oDaoVinculo   = db_utils::getDao("conplanoconplanoorcamento");
    $sWhereVinculo = "c72_conplanoorcamento = {$iCodigoPlanoOrcamento}";

    $sCampos  = "conplano.c60_codcon as codigo, conplano.c60_estrut as estrutural,";
    $sCampos .= "conplano.c60_descr as descricao_conta";

    $sSqlBuscaVinculo = $oDaoVinculo->sql_query(null, $sCampos, null, $sWhereVinculo);
    $rsBuscaVinculo   = $oDaoVinculo->sql_record($sSqlBuscaVinculo);

    if ($oDaoVinculo->numrows > 0) {

      $oDadosConta  = db_utils::fieldsMemory($rsBuscaVinculo, 0);
      $sMsgVinculo  = "Esta conta do plano or�ament�rio j� est� v�nculada para a conta PCASP ";
      $sMsgVinculo .= "{$oDadosConta->codigo} - {$oDadosConta->estrutural} {$oDadosConta->descricao_conta}";
      throw new Exception($sMsgVinculo);
    }

    /*
     * Seta as propriedades que ser�o utilizadas na tabela e salva sempre para frente.
     */
    $oDaoVinculo->c72_conplano          = $this->getCodigoConta();
    $oDaoVinculo->c72_conplanoorcamento = $iCodigoPlanoOrcamento;

    $iUltimoAno = $this->getUltimoAnoPlano("c60_codcon = {$this->getCodigoConta()}");

    for ($iAno = db_getsession("DB_anousu"); $iAno <= $iUltimoAno; $iAno++) {

      $oDaoVinculo->c72_anousu = $iAno;
      $oDaoVinculo->incluir(null);
      if ($oDaoVinculo->erro_status == "0") {

        $sMsgVinculo  = "N�o foi poss�vel vincular a conta {$this->getCodigoConta()}.\n\n";
        $sMsgVinculo .= $oDaoVinculo->erro_msg;
      }
    }
    return true;
  }

  /**
   * Fun��o que exclui o v�nculo
   * Enter description here ...
   * @param unknown_type $iSequencialVinculo
   * @throws Exception
   */
  public function excluiVinculoContaOrcamento($iCodigoContaOrcamento) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Transa��o com o banco de dados n�o encontrada.");
    }

    $oDaoExcluiVinculo = db_utils::getDao("conplanoconplanoorcamento");
    $sWhereExclui      = "    c72_conplanoorcamento = {$iCodigoContaOrcamento}";
    $sWhereExclui     .= "and c72_conplano = {$this->getCodigoConta()}";
    $rsExcluiVinculo   = $oDaoExcluiVinculo->excluir(null, $sWhereExclui);

    if ($oDaoExcluiVinculo->erro_status == "0") {
      throw new Exception("Ocorreu um erro ao desvincular a conta orcament�ria.\n\n{$this->erro_msg}");
    }
    return true;
  }

  /**
   * Busca as contas do plano or�ament�rio vinculadas a um plano de contas.
   * @return mixed
   */
  public function getVinculoContaOrcamento() {

    $oDaoVinculoContas = db_utils::getDao("conplanoconplanoorcamento");
    $sCampoVinculo     = "conplanoorcamento.c60_codcon, conplanoorcamento.c60_descr, conplanoorcamento.c60_estrut";
    $sWhereVinculo     = "     conplano.c60_codcon = {$this->getCodigoConta()}          ";
    $sWhereVinculo    .= " and conplanoconplanoorcamento.c72_anousu = {$this->getAno()} ";
    $sSqlVinculoConta  = $oDaoVinculoContas->sql_query(null, $sCampoVinculo, null, $sWhereVinculo);
    $rsVinculoContas   = $oDaoVinculoContas->sql_record($sSqlVinculoConta);

    if ($oDaoVinculoContas->numrows > 0) {
      return db_utils::getCollectionByRecord($rsVinculoContas);
    }
    return false;
  }

  /*
   * Remove os dados da conta bancaria
   * @param integer $iAno ano de cadastro da conta bancaria.
   */
  protected function removerDadosContaBancaria($iAno) {

    $oDaoConPlanoContaBancaria = db_utils::getDao("conplanocontabancaria");
    $sWhereContaBancaria       = "c56_codcon = {$this->getCodigoConta()} and c56_anousu = {$iAno}";
    $sSqlContaBancaria         = $oDaoConPlanoContaBancaria->sql_query_file(null, "*", null, $sWhereContaBancaria);
    $rsContaBancaria           = $oDaoConPlanoContaBancaria->sql_record($sSqlContaBancaria);
    if ($oDaoConPlanoContaBancaria->numrows > 0) {

      $rsExcluiContaBancaria = $oDaoConPlanoContaBancaria->excluir(null, $sWhereContaBancaria);
      if ($oDaoConPlanoContaBancaria->erro_status == 0) {

        $sMsgContaBancaria  = "N�o � poss�vel excluir as contas banc�rias do exerc�cio {$iAno}.\n\n";
        $sMsgContaBancaria .= str_replace("\\n", "\n", $oDaoConPlanoContaBancaria->erro_msg);
        throw new Exception($sMsgContaBancaria);
      }
    }
  }

  /**
   *
   * Seta a propriedade ContaCorrente
   */
  public function setContaCorrente(ContaCorrente $oContaCorrente) {

    $this->oContaCorrente = $oContaCorrente;
    return $this;
  }

  /**
   * Retorna uma instancia de Conta Corrente
   * @return ContaCorrente
   */
  public function getContaCorrente() {

    return $this->oContaCorrente;
  }
}
?>