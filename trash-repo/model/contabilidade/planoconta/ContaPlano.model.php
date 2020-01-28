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

require_once("model/financeiro/ContaBancaria.model.php");
/**
 *
 * @author dbseller
 * @name ContaPlano
 * @package contabilidade
 * @subpackage planoconta
 */
abstract class ContaPlano {

   protected $iCodigoConta;
   protected $sEstrutural;
   protected $iAno;
   protected $sTipo;
   protected $sDescricao;
   protected $sFinalidade;
   protected $iReduzido;
   protected $iRecurso;
   protected $iInstituicao;
   protected $iContraPartida;
   protected $sNomeDao;
   protected $iNaturezaSaldo;
   protected $sIdentificadorFinanceiro;
   protected $iCodigoGrupo;
   protected $iGrupoComplano;
   protected $oSistemaConta        = null;
   protected $oSubSistemaConta     = null;
   protected $oClassificacaoConta  = null;
   protected $oContaBancaria       = null;
   protected $sFuncao              = null;
   protected $oDadosAnteriores     = null;
   protected $iCodigoContaBancaria = null;

   const CAMINHO_MENSAGEM = 'financeiro.contabilidade.ContaPlano';
   /**
    *
    * Classe construtora, Se setado os par�metros busca os dados
    * @param integer $iCodigoConta codcon
    * @param integer $iAnoUsu Ano
    * @param integer $iReduz  C�digo Reduzido
    */
  public function __construct($iCodigoConta = null, $iAnoUsu = null, $iReduz = null, $iInstituicao = null) {

    $oDaoConPlano = db_utils::getDao($this->getNomeDao());
    $aWhere       = array();
    if (!empty($iCodigoConta)) {
      $aWhere[] = "c60_codcon = {$iCodigoConta}";
    }

    if (!empty($iAnoUsu)) {
      $aWhere[] = "c60_anousu = {$iAnoUsu}";
    }

    $iInstituicaoSessao = db_getsession("DB_instit");
    if (!empty($iInstituicao)) {
      $iInstituicaoSessao = $iInstituicao;
    }

    if (!empty($iReduz)) {

      $aWhere[] = "c61_reduz  = {$iReduz}";
      $aWhere[] = "c61_instit = {$iInstituicaoSessao}";
    }

    $sWhere = implode(" and ", $aWhere);

    if (!empty($sWhere)) {

      $sSqlContaPlano = $oDaoConPlano->sql_query_dados_plano(null,"*", null, $sWhere);
      $rsContaPlano   = $oDaoConPlano->sql_record($sSqlContaPlano);

      if ($oDaoConPlano->numrows > 0) {


        /**
         * @todo
         * refatorar o m�todo construtor
         */
        $iLinhaResult = 0;
        for ($iRowPlano = 0; $iRowPlano < $oDaoConPlano->numrows; $iRowPlano++) {

          $oStdPlano = db_utils::fieldsMemory($rsContaPlano, $iRowPlano);
          if (!empty($oStdPlano->c61_reduz) && $oStdPlano->c61_instit == $iInstituicaoSessao) {
            $iLinhaResult = $iRowPlano;
            break;
          }
        }

        $oContaPlano = db_utils::fieldsMemory($rsContaPlano, $iLinhaResult);
        $this->setCodigoConta($oContaPlano->c60_codcon);
        $this->setAno($oContaPlano->c60_anousu);
        $this->setEstrutural($oContaPlano->c60_estrut);
        $this->setDescricao($oContaPlano->c60_descr);
        $this->setFinalidade($oContaPlano->c60_finali);
        $this->setFuncao($oContaPlano->c60_funcao);
        $this->setIdentificadorFinanceiro($oContaPlano->c60_identificadorfinanceiro);
        $this->setSistemaConta(SistemaContaRepository::getSistemaContaByCodigo($oContaPlano->c60_codsis));
        $this->setClassificacaoConta(new ClassificacaoConta($oContaPlano->c60_codcla));
        if ($oContaPlano->c56_contabancaria != '') {
          $this->setContaBancaria(new ContaBancaria($oContaPlano->c56_contabancaria));
        }
        if (isset($oContaPlano->c61_reduz) && !empty($oContaPlano->c61_reduz)) {
          $this->setReduzido($oContaPlano->c61_reduz);
        }

        if (empty($oContaPlano->c61_instit)) {
          $oContaPlano->c61_instit = $iInstituicaoSessao;
        }
        $this->setInstituicao($oContaPlano->c61_instit);
        $this->setRecurso($oContaPlano->c61_codigo);
        $this->setContraPartida($oContaPlano->c61_contrapartida);
        $this->setSubSistema(new SubSistemaConta($oContaPlano->c60_consistemaconta));
        $this->setNaturezaSaldo($oContaPlano->c60_naturezasaldo);
        $this->oDadosAnteriores     = $oContaPlano;
        $this->iCodigoContaBancaria = $oContaPlano->c56_sequencial;
      }
    }
  }

  /**
   * Verifica se j� existe o estrutural cadastrado na tabela conplano
   * Retorna um valor do tipo boolean:
   * FALSE - n�o existe o estrutural cadastrado
   * TRUE  - existe o estrutural cadastrado
   * @return boolean
   */
  protected function hasEstruturalCadastrado() {

    $oDaoPlano            = db_utils::getDao($this->getNomeDao());
    $sWhereEstrutural     = "     c60_estrut = '{$this->getEstrutural()}'";
    $sWhereEstrutural    .= " and c60_anousu = {$this->getAno()}";
    if ($this->getCodigoConta() != "") {
      $sWhereEstrutural  .= " and c60_codcon = {$this->getCodigoConta()}";
    }
    $sSqlBuscaEstrutural  = $oDaoPlano->sql_query_file(null, null, "*", null, $sWhereEstrutural);
    $rsBuscaEstrutural    = $oDaoPlano->sql_record($sSqlBuscaEstrutural);

    if ($oDaoPlano->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Busca o �ltimo ano cadastrado na tabela conplano ou conplanoorcamento
   * @return integer
   */
  protected function getUltimoAnoPlano($sWhere = null) {

    $oDaoPlano     = db_utils::getDao($this->getNomeDao());
    $sCampo        = "max(c60_anousu) as c60_anousu";
    $sSqlMaximoAno = $oDaoPlano->sql_query_file(null, null, $sCampo, null, $sWhere);
    $rsMaximoAno   = $oDaoPlano->sql_record($sSqlMaximoAno);

    return db_utils::fieldsMemory($rsMaximoAno, 0)->c60_anousu;
  }

  /**
  * Valida a estrutura do Plano de Contas
  * @return boolean
  */
  protected function validaEstrutural() {

    $oDaoPlano = db_utils::getDao($this->getNomeDao());
    if (empty($this->sEstrutural)) {

      throw new Exception("C�digo estrutural da conta � um campo obrigat�rio e n�o pode ser vazio.");
    } else if ($this->getEstrutural()) {

      /**
       * Verifica se o estrutural j� existe para o ano atual
       */
      $sCampos      = "c60_anousu as anousuAnterior";
      $sWhere       = "c60_estrut = '{$this->getCodigoConta()}'";

      $rsConPlano   = $oDaoPlano->sql_record($oDaoPlano->sql_query_file("","",$sCampos, "", $sWhere));

      if ($oDaoPlano->numrows > 0) {

        $iAnoAnterior = db_utils::fieldsMemory($rsConPlano, 0)->anousuAnterior;
        if ($iAnoAnterior == db_getsession("DB_anousu")) {

          $sMsgErroEstrutura  = "Este estrutural {$this->getCodigoConta()} ja existe no plano de contas ";
          $sMsgErroEstrutura .= "(Exerc�cio $iAnoAnterior)!";
          throw new Exception($sMsgErroEstrutura);
        }
      }
    } else if ($this->getEstrutural()) {

      /**
       * Verifica se o estrutural tem um n�vel acima.
       */
      if ($oDaoPlano->db_verifica_conplano($this->getEstrutural(),$this->getAno()) == false) {

        $sMsgErroValidaEstrutura  = "";
        $sMsgErroValidaEstrutura .= str_replace("\\n", "\n", $oDaoPlano->erro_msg);
        throw new Exception($sMsgErroValidaEstrutura);
      } else {

        $iNivel = db_le_mae_conplano($this->getEstrutural(), true);
        if ($iNivel != 1) {

          $iAnoUsu        = db_getsession("DB_anousu");
          $sEstruturalMae = db_le_mae_conplano($this->getEstrutural(), false);
          $sCampos        = "c60_codcon as c60_codcon_mae";
          $sWhere         = "c60_anousu = {$iAnoUsu} and c60_estrut='{$sEstruturalMae}'";
          $sSqlConPlano   = $oDaoPlano->sql_query_file("","",$sCampos, "", $sWhere);
          $rsConPlano     = $oDaoPlano->sql_record($sSqlConPlano);
          $oConPlano      = db_utils::fieldsMemory($rsConPlano, 0);

          $oDaoPlanoReduz = db_utils::getDao("conplanoreduz");
          if ($this->getNomeDao() == "conplanoorcamento") {
            $oDaoPlanoReduz = db_utils::getDao("conplanoorcamentoanalitica");
          }

          $sWhereReduz       = "c61_anousu = {$iAnoUsu} and c61_codcon = {$oConPlano->c60_codcon_mae}";
          $sSqlConPlanoReduz = $oDaoPlanoReduz->sql_query_file(null,null, "*", '', $sWhereReduz);
          $rsConPlanoReduz   = $clconplanoreduz->sql_record($sSqlConPlanoReduz);

          if ($oDaoPlanoReduz->numrows > 0) {
            throw new Exception("Conta superior $sEstruturalMae � anal�tica!\\n Inclus�o n�o permitida!");
          }
        }
      }
    }
    return true;
  }

 /**
  * Verifica se existem lan�amentos contabeis para o ANO e REDUZIDO. Retorna TRUE caso haja lan�amento contabil
  * @param integer $iAno
  * @param integer $iReduzido
  * @return boolean
  */
  public function hasLancamentosContabeis($iReduzido, $iAno) {

    $oDaoConlancamVal    = db_utils::getDao('conlancamval');
    $sWhereConLacamVal   = "c69_anousu = {$iAno} and ";
    $sWhereConLacamVal  .= "(c69_debito = {$iReduzido} or c69_credito = {$iReduzido})";
    $sSqlBuscaLancamento = $oDaoConlancamVal->sql_query_file(null, "*", null, $sWhereConLacamVal);
    $rsBuscaLancamento   = $oDaoConlancamVal->sql_record($sSqlBuscaLancamento);
    if ($oDaoConlancamVal->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
  * Valida se j� existe um reduzido cadastrado para o Ano e Institui��o
  * @return boolean
  */
  public function hasReduzidoAnoInstituicao() {

    $oDaoReduzido       = db_utils::getDao($this->getNomeDao());
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
  * Retorna C�digo da Conta
  * @return  integer
  */
  public function getCodigoConta() {
    return $this->iCodigoConta;
  }

  /**
   * Recebe o C�digo da Conta
   * @param $iCodigoConta
   */
  public function setCodigoConta($iCodigoConta) {
    $this->iCodigoConta = $iCodigoConta;
    return $this;
  }

  /**
   * Retorna o c�digo estrutural da conta
   * @return  o c�digo estrutural da conta
   */
  public function getEstrutural(){
    return $this->sEstrutural;
  }

  /**
   * Recebe o c�digo estrutural da conta
   * @param $sEstrutural
   */
  public function setEstrutural($sEstrutural) {
    $this->sEstrutural = str_replace(".", "", $sEstrutural);
    return $this;
  }

  /**
   * Retorna o ano da inclus�o
   * @return integer
   */
  public function getAno()  {
    return $this->iAno;
  }

  /**
   * Recebe o Ano
   * @param $iAno
   */
  public function setAno($iAno) {
    $this->iAno = $iAno;
    return $this;
  }

  /**
   * Retorna o tipo da conta banc�ria.
   * Tipos: 1 Sint�tica
   * 		    2 Analitica
   * @return
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Recebe o tipo da conta banc�ria.
   * Tipos: 1 Sint�tica
   * 				2 Analitica
   * @param $sTipo
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
    return $this;
  }

  /**
   * Retorna a descri��o do plano de contas
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Recebe Descricao do plano de contas
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
    return $this;
  }

  /**
   * Retorna o c�digo reduzido
   * @return integer
   */
  public function getReduzido() {
    return $this->iReduzido;
  }

  /**
   * Recebe o c�digo do Recurso
   * @param $iReduzido
   */
  public function setReduzido($iReduzido) {
    $this->iReduzido = $iReduzido;
    return $this;
  }

  /**
   * Retorna o c�digo do Recurso
   * @return interger
   */
  public function getRecurso() {
    return $this->iRecurso;
  }

  /**
   * Recebe o c�digo do Recurso
   * @param $iRecurso
   */
  public function setRecurso($iRecurso) {
    $this->iRecurso = $iRecurso;
    return $this;
  }

  /**
   * Retorna o c�digo da istitui��o
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Recebe o c�digo da istitui��o
   * @param $iIsntituicao
   */
  public function setInstituicao($iInstituicao) {
    $this->iInstituicao = $iInstituicao;
    return $this;
  }

  /**
   * Retorna uma instancia de Conta Bancaria
   * @return ContaBancaria
   */
  public function getContaBancaria() {
      return $this->oContaBancaria;
  }

  /**
   * Recebe uma instancia de Conta Bancaria
   * @param $oContaBancaria
   */
  public function setContaBancaria(ContaBancaria $oContaBancaria) {
    $this->oContaBancaria = $oContaBancaria;
    return $this;
  }

 /**
  * Retorna uma instancia de  um tipo de sistema de conta
  * @return SistemaConta
  */
  public function getSistemaConta() {
      return $this->oSistema;
  }

  /**
   * Recebe uma instancia de um tipo de sistema de conta
   * @param SistemaConta
   */
  public function setSistemaConta(SistemaConta $oSistema) {
    $this->oSistema = $oSistema;
    return $this;
  }

  /**
  * Retorna a Descricao da Finalidade
  * @return string
  */
  public function getFinalidade() {
    return $this->sFinalidade;
  }

  /**
   * Recebe a Descricao da Finalidade
   * @param SistemaConta
   */
  public function setFinalidade($sFinalidade) {
    $this->sFinalidade = $sFinalidade;
    return $this;
  }

  /**
   * Retorna uma instancia de ClassificacaoConta
   * @return ClassificacaoContaif(!db_utils::inTransaction()){
      throw new Exception("Sem transa��o");
    }

    if (empty($this->getCodigoConta())) {
      throw new Exception("C�digo da conta esta nule.");
    }

    $iMaximoAno = $this->getUltimoAnoPlano($sWhereMaximoAno);
    $iAnoUsu    = db_getsession("DB_anousu");
   */
  public function getClassificacaoConta() {
    return $this->oClassificacaoConta;
  }

  /**
   * Recebe uma instancia de ClassificacaoConta
   * @param ClassificacaoConta
   */
  public function setClassificacaoConta(ClassificacaoConta $oClassificacaoConta) {
    $this->oClassificacaoConta = $oClassificacaoConta;
    return $this;
  }

  /**
   * Retorna o c�digo da Conta de Contra Partida
   * @return integer
   */
  public function getContraPartida() {
    return $this->iContraPartida;
  }

  /**
   * Recebe uma conta de Contra Partida
   * @param $iContraPartida
   */
  public function setContraPartida($iContraPartida) {
    $this->iContraPartida = $iContraPartida;
    return $this;
  }

  /**
  * Retorna o nome da tabela utilizada pela query
  * @return string
  */
  protected function getNomeDao() {
    return $this->sNomeDao;
  }

  /**
   * Recebe o nome da tabela utilizada pela query
   * @param $sNomeDao
   */
  protected function setNomeDao($sNomeDao) {
    $this->sNomeDao = $sNomeDao;
  }

  /** Retorna a Natureza do Saldo
   *
   * 1- Saldo Devedor
   * 2- Saldo Credor
   * 3- Ambos
   * @return integer
   */
  public function getNaturezaSaldo() {
    return $this->iNaturezaSaldo;
  }

  /**
   * Recebe a Natureza do Saldo
   *
   * 1- Saldo Devedor
   * 2- Saldo Credor
   * 3- Ambos
   * @param $iNaturezaSaldo
   */
  public function setNaturezaSaldo($iNaturezaSaldo) {

    $this->iNaturezaSaldo = $iNaturezaSaldo;
    return $this;
  }

  /**
  * Retorna uma instancia de SubSistemaConta
  * @return SubSistemaConta
  */
  public function getSubSistema() {
    return $this->oSubSistemaConta;
  }

  /**
   * Recebe uma instancia de SubSistemaConta
   * @param SubSistemaConta
   */
  public function setSubSistema(SubSistemaConta $oSubSistemaConta) {
    $this->oSubSistemaConta = $oSubSistemaConta;
    return $this;
  }

  /**
  * Retorna um caracter
  *
  * P => Patrimonial
  * F => Financeiro
  * N => N�o se aplica
  * @return string
  */
  public function getIdentificadorFinanceiro() {
    return $this->sIdentificadorFinanceiro;
  }

  /**
  * Seta um caracter
  *
  * P => Patrimonial
  * F => Financeiro
  * N => N�o se aplica
   * @param string
   */
  public function setIdentificadorFinanceiro($sIdentificadorFinanceiro) {

    $this->sIdentificadorFinanceiro = $sIdentificadorFinanceiro;
    return $this;
  }

  /**
  * Retorna C�digo sequencial do grupo
  * @return integer
  */
  public function getCodigoGrupo() {
    return $this->iCodigoGrupo;
  }

  /**
   * Seta C�digo sequencial do grupo
   * @param integer $iCodigoGrupo
   */
  public function setCodigoGrupo($iCodigoGrupo) {

    $this->iCodigoGrupo = $iCodigoGrupo;
    return $this;
  }


  /**
   * Retorna C�digo do grupo Financeiro (congrupo)
   * @return integer
   */
  public function getGrupoComplano() {
    return $this->iGrupoComplano;
  }

  /**
   * Seta C�digo do grupo Financeiro (congrupo)
   * @param integer $iGrupoComplano
   */
  public function setGrupoComplano($iGrupoComplano) {

    $this->iGrupoComplano = $iGrupoComplano;
    return $this;
  }

  /**
   * Retorna a fun��o da conta
   * @return string
   */
  public function getFuncao() {
  	return $this->sFuncao;
  }

  /**
   * Seta a fun��o da conta
   * @param string $sFuncao
   */
  public function setFuncao($sFuncao) {

  	$this->sFuncao = $sFuncao;
  	return $this;
  }


  /**
   * retorna o nivel em que a estrutura est� digitada
   * @param $sStrutural Estrutural
   * @return integer
   */
  static function getNivelEstrutura($sStrutural) {

    $aNiveis = explode(".", $sStrutural);
    $iNivel  = 1;
    foreach ($aNiveis as $iIndice => $sNivel) {

      $iTamanhoNivel = strlen($sNivel);
      if ($sNivel != str_repeat('0', $iTamanhoNivel)){
        $iNivel  = $iIndice+1;
      }
    }
    return $iNivel;
  }

  static function getCodigoEstruturalPai($sStrutural) {

    $aNiveis          = explode(".", $sStrutural);
    $iNivel           = ContaPlano::getNivelEstrutura($sStrutural) - 1;

    $iTamanho         = strlen($aNiveis[$iNivel]);
    $aNiveis[$iNivel] = str_repeat('0', $iTamanho);

    return implode(".", $aNiveis);
  }


  /**
   * funcao para construir a arvore estrutural do registro a ser vinculado
   * @param string $sEstruturalVincular
   * @return array
   */
  static function getNiveisEstruturais ($sEstruturalVincular) {

   //echo "nivel na func " . $iNivelFinal;

    $sEstrutural       = ContaPlano::montaEstrutural($sEstruturalVincular);
    $iNivelEstrutural  = ContaPlano::getNivelEstrutura($sEstrutural);
    $sMascara          = "0.0.0.0.0.00.00.00.00.00";
    $aArvoreVincular   = array($sEstrutural);
    $iNivelFinal       = $iNivelEstrutural;
   // if ($lLimite == true) {
   //   $iNivelFinal = 1;
   // }

    //echo "-------------> " .$iNivelFinal;

   // echo "$iNivelEstrutural > $iLevel";
   // die();

    while ($iNivelEstrutural > 1 ) {

      $sEstruturalPaiVincular = ContaPlano::getCodigoEstruturalPai($sEstrutural);
      if ($sEstruturalPaiVincular != $sMascara) {
        $aArvoreVincular[] = $sEstruturalPaiVincular;
      }
      $sEstrutural = $sEstruturalPaiVincular;
      $iNivelEstrutural --;
    }
    return $aArvoreVincular;
  }


  /**
   * funcao para montar o estrutural
   * caso seja digitado algo como
   * 111% , onde o % sera substituido por zeros at� o 15 nivel
   *
   * @param string $sEstrutural
   * @return string
   */
  static function montaEstrutural($sEstrutural) {
    /*
     * verificamos se o estrutural digitado, possui %
    * ele significa que do % em diante ser�o zeros at� o ultimo nivel da mascara
    */
    $aEstrutural            = explode("%", $sEstrutural);
    if (count($aEstrutural) > 1) {

      $sEstruturalDigitado = str_pad($aEstrutural[0] , 15, "0", STR_PAD_RIGHT);
    } else {

      $sEstruturalDigitado = $sEstrutural;
    }

    if (strlen($sEstruturalDigitado) < 15) {
      $sEstruturalDigitado = str_pad($sEstruturalDigitado , 15, "0", STR_PAD_RIGHT);
    }


    return db_formatar($sEstruturalDigitado, "receita");
  }
  
  /**
   * Retorna a descri��o da conta cont�bil
   * @param integer $iReduzido
   * @throws Exception
   */
  static function getDescricaoContaPorReduzido($iReduzido) {
  	
    $oDaoConPlano = new cl_conplano();
    $sWhere       = "     conplanoreduz.c61_reduz = {$iReduzido} ";
    $sSqlDescrConta = $oDaoConPlano->sql_query(null, null, "c60_descr", null, $sWhere);
    $rsDescrConta   = $oDaoConPlano->sql_record($sSqlDescrConta);
    
    if ($oDaoConPlano->numrows == 0) {
    	throw new Exception("N�o foi poss�vel localizar descri��o da conta do reduzido: {$iReduzido}");
    }
    
    return db_utils::fieldsMemory($rsDescrConta, 0)->c60_descr;
  }

  /**
   * Retorna o proximo estrutural disponivel na estrutura $sEstrutural
   * @param string $sEstrutural
   * @throws BusinessException
   * @return string
   */
  public static function getProximoEstruturalDisponivel($sEstrutural) {
    
    $sEstrutural         = db_formatar($sEstrutural, 'receita');
    $iNivelEstrutura     = ContaPlano::getNivelEstrutura($sEstrutural);
    $iTamanhoMaximoNivel = 9;
    if ($iNivelEstrutura >= 6) {
      $iTamanhoMaximoNivel = 99;
    }
    
    if ($iNivelEstrutura == 10) {
      
      $oParametros = (object)array("estrutural" => $sEstrutural);
      throw new BusinessException(_M(ContaPlano::CAMINHO_MENSAGEM.'.sem_niveis_abaixo', $oParametros));
    }
    $iTamanhoNivel     = strlen($iTamanhoMaximoNivel);
    $iUltimaContaNivel = ContaPlano::getUltimaContaDaEstrutura($sEstrutural);
    $iProximoNivel     = $iUltimaContaNivel + 1;
    if ($iProximoNivel > $iTamanhoMaximoNivel) {
      
      $oParametros = (object)array("estrutural" => $sEstrutural, "nivel_conta" => $iNivelEstrutura);
      throw new BusinessException(_M(ContaPlano::CAMINHO_MENSAGEM.'.quantidade_contas_excedida_nivel', $oParametros));
    }
    $iProximoNivel = str_pad($iProximoNivel, $iTamanhoNivel, "0", STR_PAD_LEFT);
    
    $aNivelContaVerificar = explode(".", $sEstrutural);
    $sContaPlano          = implode("", array_splice($aNivelContaVerificar, 0, $iNivelEstrutura));
    $sProximoEstrutural   = str_pad($sContaPlano.$iProximoNivel, 15, "0", STR_PAD_RIGHT);
    return $sProximoEstrutural;
  }
  
  /**
   * Retorna a ultima conta cadastrada no proximo nivel da estrutura $sEstrutural
   * @param string $sEstrutural codigo estrutural
   * @throws BusinessException
   * @return Integer
   */
  public static function getUltimaContaDaEstrutura($sEstrutural) {
    
    $iNivel               = ContaPlano::getNivelEstrutura($sEstrutural);
    $aNivelContaVerificar = explode(".", $sEstrutural);
    $sContaPlano          = implode("", array_splice($aNivelContaVerificar, 0, $iNivel));
    $aContasDoNivel       = array();
    
    $oDaoConPlano = new cl_conplano();
    $sWhere       = "c60_estrut like '{$sContaPlano}%'";
    $sWhere      .= " and c60_anousu = ".db_getsession("DB_anousu");
    $sSqlContas   = $oDaoConPlano->sql_query_file(null, null, "c60_estrut", 'c60_estrut', $sWhere);
    $rsContas     = $oDaoConPlano->sql_record($sSqlContas);
    $iTotalContas = $oDaoConPlano->numrows;
    for ($iConta = 0; $iConta < $iTotalContas; $iConta++) {
      $aContasDoNivel[] = db_utils::fieldsMemory($rsContas, $iConta)->c60_estrut;
    }
    $iMaiorNivel  = 0;
    
    /**
     * Devemos verificar o proximo nivel da conta passada como parametro
     * Percorremos todas as contas abaixo da conta Cadastrada.
     */
    $iNivelVerificar = $iNivel + 1;
    foreach($aContasDoNivel as $sConta) {
      
      $sConta      = db_formatar($sConta, 'receita');
      $iNivelConta = ContaPlano::getNivelEstrutura($sConta);
      if ($iNivelConta == $iNivelVerificar) {
        
        $aNiveis      = explode(".", $sConta);
        $iIndiceNivel = $iNivelConta - 1;
        if (!isset($aNiveis[$iIndiceNivel])) {
          throw new BusinessException(_M(ContaPlano::CAMINHO_MENSAGEM.'.sem_niveis_abaixo', $oParametros));
        }
        $iValorNivel = $aNiveis[$iIndiceNivel];
        if ($iValorNivel > $iMaiorNivel) {
          $iMaiorNivel = $iValorNivel;
        }
      }
    }
    return $iMaiorNivel;
  }
}