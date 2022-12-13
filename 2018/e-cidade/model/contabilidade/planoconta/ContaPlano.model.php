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

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;

require_once(modification("model/financeiro/ContaBancaria.model.php"));
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

  /**
   * @type GrupoContaOrcamento
   */
  protected $oGrupoConta;

  /**
   * @type ClassificacaoConta
   */
  protected $oClassificacaoConta  = null;

  /**
   * @type ContaBancaria
   */
  protected $oContaBancaria       = null;
  protected $sFuncao              = null;
  protected $oDadosAnteriores     = null;
  protected $iCodigoContaBancaria = null;

  const CAMINHO_MENSAGEM = 'financeiro.contabilidade.ContaPlano';

  /**
   * Natureza de Saldo Devedor
   * @type integer
   */
  const NATUREZA_SALDO_DEVEDOR = 1;

  /**
   * Natureza de Saldo Credor
   * @type integer
   */
  const NATUREZA_SALDO_CREDOR = 2;

  /**
   * Natureza de Saldo Credor e Devedor
   * @type integer
   */
  const NATUREZA_SALDO_CREDOR_DEVEDOR = 3;


  /**
   *
   * Classe construtora, Se setado os parâmetros busca os dados
   * @param integer $iCodigoConta codcon
   * @param integer $iAnoUsu Ano
   * @param integer $iReduz  Código Reduzido
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
         * refatorar o método construtor
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
   * Verifica se já existe o estrutural cadastrado na tabela conplano
   * Retorna um valor do tipo boolean:
   * FALSE - não existe o estrutural cadastrado
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
   * Busca o último ano cadastrado na tabela conplano ou conplanoorcamento
   * @return integer
   */
  public function getUltimoAnoPlano($sWhere = null) {

    $oDaoPlano     = db_utils::getDao($this->getNomeDao());
    $sCampo        = "max(c60_anousu) as c60_anousu";
    $sSqlMaximoAno = $oDaoPlano->sql_query_file(null, null, $sCampo, null, $sWhere);
    $rsMaximoAno   = $oDaoPlano->sql_record($sSqlMaximoAno);

    return db_utils::fieldsMemory($rsMaximoAno, 0)->c60_anousu;
  }


  /**
   * Valida a estrutura do Plano de Contas
   * @return bool
   * @throws \Exception
   */
  protected function validaEstrutural() {

    $oDaoPlano = db_utils::getDao($this->getNomeDao());
    if (empty($this->sEstrutural)) {
      throw new Exception("Código estrutural da conta é um campo obrigatório e não pode ser vazio.");
    }

    /**
     * Verifica se o estrutural já existe para o ano atual
     */
    if (empty($this->iCodigoConta)) {

      $sCampos = "c60_anousu as anousuanterior";
      $sWhere = "     c60_estrut = '{$this->getEstrutural()}'";
      $sWhere .= " and c60_anousu >= " . db_getsession("DB_anousu");
      $sSqlBuscaaEstrutural = $oDaoPlano->sql_query_file(null, null, $sCampos, null, $sWhere);
      $rsConPlano = $oDaoPlano->sql_record($sSqlBuscaaEstrutural);
      if ($oDaoPlano->numrows > 0) {

        $sMsgErroEstrutura = "Este estrutural {$this->getEstrutural()} ja existe no plano de contas ";
        $sMsgErroEstrutura .= "(Exercício " . db_getsession("DB_anousu") . ")!";
        throw new Exception($sMsgErroEstrutura);
      }
    }

    /**
     * Verifica se o estrutural tem um nível acima.
     */
    if ($oDaoPlano->db_verifica_conplano($this->getEstrutural(),$this->getAno()) == false) {

      $sMsgErroValidaEstrutura  = "";
      $sMsgErroValidaEstrutura .= str_replace("\\n", "\n", $oDaoPlano->erro_msg);
      throw new Exception($sMsgErroValidaEstrutura);
    }

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
      $rsConPlanoReduz   = $oDaoPlanoReduz->sql_record($sSqlConPlanoReduz);

      if ($oDaoPlanoReduz->numrows > 0) {
        throw new Exception("Conta superior $sEstruturalMae é analítica!\\n Inclusão não permitida!");
      }
    }
    return true;
  }

  /**
   * Verifica se existem lançamentos contabeis para o ANO e REDUZIDO. Retorna TRUE caso haja lançamento contabil
   * @param integer $iAno
   * @param integer $iReduzido
   * @return boolean
   */
  public function possuiLancamentoContabil($iReduzido, $iAno) {

    $oDaoConlancamVal    = new cl_conlancamval();
    $sWhereConLacamVal   = "c69_anousu >= {$iAno} and ";
    $sWhereConLacamVal  .= "(c69_debito = {$iReduzido} or c69_credito = {$iReduzido})";
    $sSqlBuscaLancamento = $oDaoConlancamVal->sql_query_file(null, "*", null, $sWhereConLacamVal);
    $rsBuscaLancamento   = $oDaoConlancamVal->sql_record($sSqlBuscaLancamento);
    if ($oDaoConlancamVal->numrows > 0) {
      return true;
    }
    return false;
  }

  /**
   * Valida se já existe um reduzido cadastrado para o Ano e Instituição
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
   * @return integer
   */
  public function getCodigoConta() {
    return $this->iCodigoConta;
  }


  /**
   * @param $iCodigoConta
   * @return $this
   */
  public function setCodigoConta($iCodigoConta) {
    $this->iCodigoConta = $iCodigoConta;
    return $this;
  }


  /**
   * @return string
   */
  public function getEstrutural(){
    return $this->sEstrutural;
  }


  /**
   * @param $sEstrutural
   * @return $this
   */
  public function setEstrutural($sEstrutural) {
    $this->sEstrutural = str_replace(".", "", $sEstrutural);
    return $this;
  }

  /**
   * Retorna o ano da inclusão
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
   * Retorna o tipo da conta bancária.
   * Tipos: 1 Sintética
   * 		    2 Analitica
   * @return
   */
  public function getTipo() {
    return $this->sTipo;
  }

  /**
   * Recebe o tipo da conta bancária.
   * Tipos: 1 Sintética
   * 				2 Analitica
   * @param $sTipo
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
    return $this;
  }

  /**
   * Retorna a descrição do plano de contas
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
   * Retorna o código reduzido
   * @return integer
   */
  public function getReduzido() {
    return $this->iReduzido;
  }

  /**
   * Recebe o código do Recurso
   * @param $iReduzido
   */
  public function setReduzido($iReduzido) {
    $this->iReduzido = $iReduzido;
    return $this;
  }

  /**
   * Retorna o código do Recurso
   * @return integer
   */
  public function getRecurso() {
    return $this->iRecurso;
  }

  /**
   * Recebe o código do Recurso
   * @param $iRecurso
   */
  public function setRecurso($iRecurso) {
    $this->iRecurso = $iRecurso;
    return $this;
  }

  /**
   * Retorna o código da istituição
   * @return integer
   */
  public function getInstituicao() {
    return $this->iInstituicao;
  }

  /**
   * Recebe o código da istituição
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
    return $this->oSistemaConta;
  }

  /**
   * Recebe uma instancia de um tipo de sistema de conta
   * @param SistemaConta
   * @return ContaPlano
   */
  public function setSistemaConta(SistemaConta $oSistema) {
    $this->oSistemaConta = $oSistema;
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
   * @return ContaPlano
   */
  public function setFinalidade($sFinalidade) {
    $this->sFinalidade = $sFinalidade;
    return $this;
  }

  /**
   * @return ClassificacaoConta
   */
  public function getClassificacaoConta() {
    return $this->oClassificacaoConta;
  }

  /**
   * Recebe uma instancia de ClassificacaoConta
   * @param ClassificacaoConta
   * @return ContaPlano
   */
  public function setClassificacaoConta(ClassificacaoConta $oClassificacaoConta) {
    $this->oClassificacaoConta = $oClassificacaoConta;
    return $this;
  }

  /**
   * Retorna o código da Conta de Contra Partida
   * @return integer
   */
  public function getContraPartida() {
    return $this->iContraPartida;
  }

  /**
   * Recebe uma conta de Contra Partida
   * @param $iContraPartida
   * @return ContaPlano
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
   * @return ContaPlano
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
   * @return ContaPlano
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
   * N => Não se aplica
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
   * N => Não se aplica
   * @param string
   * @return ContaPlano
   */
  public function setIdentificadorFinanceiro($sIdentificadorFinanceiro) {

    $this->sIdentificadorFinanceiro = $sIdentificadorFinanceiro;
    return $this;
  }

  /**
   * Retorna Código sequencial do grupo
   * @return integer
   */
  public function getCodigoGrupo() {

    if (empty($this->iCodigoGrupo)) {

      $oDaoGrupo = new cl_conplanoorcamentogrupo();
      if ($this->sNomeDao == "conplano") {
        $oDaoGrupo = new cl_conplanogrupo();
      }
      $sWhere    = "c21_codcon = {$this->iCodigoConta} and c21_anousu = {$this->iAno} and c21_instit = ".db_getsession('DB_instit');
      $rsBuscaGrupo  = $oDaoGrupo->sql_record($oDaoGrupo->sql_query_file(null, "*", null, $sWhere));
      if ($oDaoGrupo->numrows > 0) {
        $this->setCodigoGrupo(db_utils::fieldsMemory($rsBuscaGrupo, 0)->c21_congrupo);
      }
    }
    return $this->iCodigoGrupo;
  }

  /**
   * Seta Código sequencial do grupo
   * @param integer $iCodigoGrupo
   * @return ContaPlano
   */
  public function setCodigoGrupo($iCodigoGrupo) {

    $this->iCodigoGrupo = $iCodigoGrupo;
    return $this;
  }

  /**
   * @return GrupoContaOrcamento
   */
  public function getGrupoConta() {

    $iCodigoGrupo = $this->getCodigoGrupo();
    if (!empty($iCodigoGrupo)) {
      $this->oGrupoConta = new GrupoContaOrcamento($iCodigoGrupo);
    }
    return $this->oGrupoConta;
  }



  /**
   * Retorna Código do grupo Financeiro (congrupo)
   * @return integer
   */
  public function getGrupoComplano() {
    return $this->iGrupoComplano;
  }


  /**
   * @param $iGrupoComplano
   * @return $this
   */
  public function setGrupoComplano($iGrupoComplano) {

    $this->iGrupoComplano = $iGrupoComplano;
    return $this;
  }

  /**
   * Retorna a função da conta
   * @return string
   */
  public function getFuncao() {
    return $this->sFuncao;
  }

  /**
   * @param $sFuncao
   * @return $this
   */
  public function setFuncao($sFuncao) {

    $this->sFuncao = $sFuncao;
    return $this;
  }

  /**
   * Verifica se a conta passada é uma conta analítica
   *
   * @return boolean
   */
  public function isContaAnalitica() {

    $sEstrutural = $this->getEstrutural();

    if (strlen($sEstrutural) != 15) {
      throw new Exception("Estrutural inválido.");
    }

    /**
     * Substitui os zeros do final do estrutural por um caracter vazio
     * respeitando a máscara do estrutural
     *
     * 1.1.1.1.1.11.11.11.10.00 -> 1.1.1.1.1.11.11.11.10.xx
     * 1.1.1.1.1.10.00.00.00.00 -> 1.1.1.1.1.10.xx.xx.xx.xx
     * 1.1.1.1.0.00.00.00.00.00 -> 1.1.1.1.x.xx.xx.xx.xx.xx
     */
    $sEstrutural = preg_replace("/(0{1,4}(?=0{10}))?(0{2}){1,5}$/", "", $sEstrutural);

    $sClass    = "cl_{$this->sNomeDao}";
    $oDaoPlano = new $sClass;
    $sSqlPlano = $oDaoPlano->sql_query_file( null,
                                             null,
                                             "count(*) as contas",
                                             null,
                                             "c60_estrut ilike '{$sEstrutural}%' and c60_anousu = {$this->getAno()}" );
    $rsPlano = $oDaoPlano->sql_record( "{$sSqlPlano} limit 2" );

    if (!empty($oDaoPlano->erro_sql)) {
      throw new Exception("Erro ao verificar se a conta {$this->getEstrutural()} é analítica.");
    }

    return (db_utils::fieldsMemory($rsPlano, 0)->contas == 1);
  }


  /**
   * retorna o nivel em que a estrutura está digitada
   * @param string $sStrutural Estrutural
   * @return integer
   */
  public static function getNivelEstrutura($sStrutural) {

    $oEstrutural = new Estrutural($sStrutural);
    return $oEstrutural->getNivel();
  }

  public static function getCodigoEstruturalPai($sStrutural) {

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
  public static function getNiveisEstruturais ($sEstruturalVincular) {

    //echo "nivel na func " . $iNivelFinal;

    $sEstrutural       = ContaPlano::montaEstrutural($sEstruturalVincular);
    $iNivelEstrutural  = ContaPlano::getNivelEstrutura($sEstrutural);
    $sMascara          = "0.0.0.0.0.00.00.00.00.00";
    $aArvoreVincular   = array($sEstrutural);
    $iNivelFinal       = $iNivelEstrutural;

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
   * 111% , onde o % sera substituido por zeros até o 15 nivel
   *
   * @param string $sEstrutural
   * @return string
   */
  public static function montaEstrutural($sEstrutural) {
    /*
     * verificamos se o estrutural digitado, possui %
    * ele significa que do % em diante serão zeros até o ultimo nivel da mascara
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
   * Retorna a descrição da conta contábil
   * @param integer $iReduzido
   * @throws Exception
   */
  static function getDescricaoContaPorReduzido($iReduzido) {

    $oDaoConPlano = new cl_conplano();
    $sWhere       = "     conplanoreduz.c61_reduz = {$iReduzido} ";
    $sSqlDescrConta = $oDaoConPlano->sql_query(null, null, "c60_descr", null, $sWhere);
    $rsDescrConta   = $oDaoConPlano->sql_record($sSqlDescrConta);

    if ($oDaoConPlano->numrows == 0) {
      throw new Exception("Não foi possível localizar descrição da conta do reduzido: {$iReduzido}");
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
          throw new BusinessException(_M(ContaPlano::CAMINHO_MENSAGEM.'.sem_niveis_abaixo'));
        }
        $iValorNivel = $aNiveis[$iIndiceNivel];
        if ($iValorNivel > $iMaiorNivel) {
          $iMaiorNivel = $iValorNivel;
        }
      }
    }
    return $iMaiorNivel;
  }

  /**
   * @param $sEstrutura
   * @param $iNivel
   *
   * @return string
   * @deprecated
   * @see self::getEstruturalAteNivel
   */
  public function getEstruturaAteNivel($sEstrutura, $iNivel) {

    $aPartesEstrutural = explode(".", $sEstrutura);
    return implode(".", array_slice($aPartesEstrutural, 0, $iNivel));
  }


  public static function getEstruturalAteNivel($sEstrutura, $iNivel) {

    $aPartesEstrutural = explode(".", $sEstrutura);
    return implode(".", array_slice($aPartesEstrutural, 0, $iNivel));
  }
  /**
   * @return bool
   */
  public function sintetica() {
    return empty($this->iRecurso);
  }

  /**
   * @return bool
   */
  public function analitica() {
    return !empty($this->iRecurso);
  }

  /**
   * @return string
   */
  public function getEstruturalComMascara() {
    return ContaPlano::montaEstrutural($this->getEstrutural());
  }

  /**
   * Verifica se a conta tratada é do orçamento ou não
   * @return bool
   */
  protected function orcamento() {
    return $this->getNomeDao() == "conplanoorcamento";
  }
}