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
 *model para controle de controle versoes do CVS
 * @author Iuri guntchnigg
 * @revision $Author: dbmauricio $
 * @version $Revision: 1.27 $
 */
class ppaVersao {

  /**
   * @type integer
   */
  private   $codigoversao      = null;

  /**
   * @type string
   */
  protected $datainicio        = null;

  /**
   * @type string
   */
  protected $datatermino       = null;

  /**
   * @type int
   */
  protected $codigolei         = null;

  /**
   * @type int
   */
  protected $numerolei         = null;

  /**
   * @type int
   */
  protected $idusuario         = null;

  /**
   * @type int
   */
  protected $versao            = null;

  /**
   * @type string
   */
  private   $nomeusuario       = null;

  /**
   * @type bool
   */
  protected $finalizada        = false;

  /**
   * @type bool
   */
  protected $homologada        = false;

  /**
   * @type bool
   */
  protected $receitaprocessada = false;

  /**
   * @type bool
   */
  protected $despesaprocessada = false;

  /**
   * @type int
   */
  protected $anoinicio         = null;

  /**
   * @type int
   */
  protected $anofim            = null;

  /**
   * @type bool
   */
  protected $lAtivo            = true;

  /**
   * @param null $iCodigoVersao
   */
  public function __construct($iCodigoVersao = null) {

    $this->codigoversao = $iCodigoVersao;
    if (empty($this->codigoversao)) {
      return;
    }

    $oDaoPPaversao = db_utils::getDao("ppaversao");
    $sSqlversao    = $oDaoPPaversao->sql_query($iCodigoVersao);
    $rsVersao      = $oDaoPPaversao->sql_record($sSqlversao);

    if ($oDaoPPaversao->numrows == 1) {

      $oVersaoPPA         = db_utils::fieldsMemory($rsVersao, 0);
      $this->codigoversao = $iCodigoVersao;
      $this->setCodigolei($oVersaoPPA->o119_ppalei);
      $this->setDatainicio($oVersaoPPA->o119_datainicio);
      $this->setDatatermino($oVersaoPPA->o119_datatermino);
      $this->setVersao($oVersaoPPA->o119_versao);
      $this->setFinalizada($oVersaoPPA->o119_finalizada);
      $this->setHomologada($oVersaoPPA->o119_versaofinal);
      $this->setIdusuario($oVersaoPPA->o119_idusuario);
      $this->setNumerolei($oVersaoPPA->o01_numerolei);
      $this->setAnoinicio($oVersaoPPA->o01_anoinicio);
      $this->setAnofim($oVersaoPPA->o01_anofinal);

      $this->lAtivo = true;
      if ( $oVersaoPPA->o119_ativo == 'f') {
        $this->lAtivo = false;
      }

      $clppaestimativareceita = db_utils::getDao("ppaestimativareceita");
      $sSqlEstimativas = $clppaestimativareceita->sql_query(null,"*",
                                                            "o05_ppaversao limit 1",
                                                            "o05_ppaversao = {$iCodigoVersao}");
      $rsEstmativas    = $clppaestimativareceita->sql_record($sSqlEstimativas);
      if ($clppaestimativareceita->numrows > 0) {
        $this->receitaprocessada = true;
      }
      $clppaestimativadespesa = db_utils::getDao("ppaestimativadespesa");
      $sSqlEstimativas = $clppaestimativadespesa->sql_query(null,"*",
                                                            "o05_ppaversao limit 1",
                                                            "o05_ppaversao = {$iCodigoVersao}");
      $rsEstmativas    = $clppaestimativadespesa->sql_record($sSqlEstimativas);
      if ($clppaestimativadespesa->numrows > 0) {
        $this->despesaprocessada = true;
      }
    }
  }
  /**
   * retorna o codigo da versao
   * @return integer
   */
  public function getCodigoversao() {
    return $this->codigoversao;
  }

  /**
   * @return int
   */
  public function getCodigolei() {
    return $this->codigolei;
  }

  /**
   * @param $codigolei
   */
  public function setCodigolei($codigolei) {
    $this->codigolei = $codigolei;
  }

  /**
   * @return string
   */
  public function getDatainicio() {
    return $this->datainicio;
  }

  /**
   * @param $datainicio
   */
  public function setDatainicio($datainicio) {

    $this->datainicio = $datainicio;
  }

  /**
   * @return string
   */
  public function getDatatermino() {

    return $this->datatermino;
  }

  /**
   * @param $datatermino
   */
  public function setDatatermino($datatermino) {
    $this->datatermino = $datatermino;
  }

  /**
   * @return bool
   */
  public function isFinalizada() {
    return $this->finalizada;
  }

  /**
   * @param $finalizada
   */
  public function setFinalizada($finalizada) {
    $this->finalizada = $finalizada == "t";
  }

  /**
   * @return bool
   */
  public function isHomologada() {

    return $this->homologada;
  }

  /**
   * @param $homologada
   */
  public function setHomologada($homologada) {
    $this->homologada = $homologada == "t";
  }

  /**
   * @return int
   */
  public function getIdusuario() {

    return $this->idusuario;
  }

  /**
   * @param $idusuario
   */
  public function setIdusuario($idusuario) {
    $this->idusuario = $idusuario;
  }

  /**
   * @return int
   */
  public function getNumerolei() {
    return $this->numerolei;
  }

  /**
   * @param $numerolei
   */
  public function setNumerolei($numerolei) {
    $this->numerolei = $numerolei;
  }

  /**
   * @return int
   */
  public function getVersao() {
    return $this->versao;
  }

  /**
   * @param $versao
   */
  public function setVersao($versao) {
    $this->versao = $versao;
  }

  /**
   * @return int
   */
  public function getAnofim() {
    return $this->anofim;
  }

  /**
   * @return int
   */
  public function getAnoinicio() {
    return $this->anoinicio;
  }

  /**
   * @param $anofim
   */
  public function setAnofim($anofim) {
    $this->anofim = $anofim;
  }

  /**
   * @param $anoinicio
   */
  public function setAnoinicio($anoinicio) {
    $this->anoinicio = $anoinicio;
  }

  /**
   * @return bool
   */
  public function hasDespesaProcessada() {
    return $this->despesaprocessada;
  }

  /**
   * @return bool
   */
  public function hasReceitaProcessada() {
    return $this->receitaprocessada;
  }

  /**
   * Retorna se a Versão está ativa
   **/
  public function getAtivo() {
    return $this->lAtivo;
  }

  /**
   *
   * cria uma nova versao do ppa, baseada nas informacoes do ppa anteriors
   * @param $lHomologar
   *
   * @return bool
   * @throws Exception
   */
  public function novaVersao($lHomologar) {

    /**
     * Incluimos uma versao nova
     */
    $oDaoPPaVersao = db_utils::getDao("ppaversao");
    $sSqlProximaVersao          = $oDaoPPaVersao->sql_query_file(null,"coalesce(max(o119_versao), 0)+1 as versao");
    $rsProximaVersao            = $oDaoPPaVersao->sql_record($sSqlProximaVersao);
    $oDaoPPaVersao->o119_datainicio  = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoPPaVersao->o119_datatermino = null;
    $oDaoPPaVersao->o119_finalizada  = "false";
    $oDaoPPaVersao->o119_versaofinal = "false";
    $oDaoPPaVersao->o119_versao      = db_utils::fieldsMemory($rsProximaVersao, 0)->versao;
    $oDaoPPaVersao->o119_idusuario   = db_getsession("DB_id_usuario");
    $oDaoPPaVersao->o119_ppalei      = $this->getCodigolei();

    $sAtivo = 'true';
    if (!$this->lAtivo) {
      $sAtivo = 'false';
    }
    $oDaoPPaVersao->o119_ativo = $sAtivo;
    $oDaoPPaVersao->incluir(null);
    if ($oDaoPPaVersao->erro_status == 0) {
      throw new Exception("Erro ao salvar versão do ppa\n{$oDaoPPaVersao->erro_msg}", 1);
    }
    /**
     * Consultamos todas as estimativas de receita
     */
    $oDaoPPAEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPPAEstimativareceita = db_utils::getDao("ppaestimativareceita");
    $sSqlEstimativasreceita   = $oDaoPPAEstimativareceita->sql_query(null,
                                                                     "ppaestimativareceita.*,ppaestimativa.*",
                                                                     null,
                                                                     "o05_ppaversao={$this->codigoversao} and o05_ppaversao={$this->codigoversao}");
    $rsEstimativasreceita     = $oDaoPPAEstimativareceita->sql_record($sSqlEstimativasreceita);
    $aItensEstimativasreceita = db_utils::getCollectionByRecord($rsEstimativasreceita);

    foreach ($aItensEstimativasreceita as $oEstimativa) {

      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $oDaoPPaVersao->o119_sequencial;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaNova->erro_status}", 2);
      }
      $oDaoPPAEstimativaReceitaNova = new cl_ppaestimativareceita;
      $oDaoPPAEstimativaReceitaNova->o06_anousu         = $oEstimativa->o06_anousu;
      $oDaoPPAEstimativaReceitaNova->o06_codrec         = $oEstimativa->o06_codrec;
      $oDaoPPAEstimativaReceitaNova->o06_ppaversao      = $oDaoPPaVersao->o119_sequencial;
      $oDaoPPAEstimativaReceitaNova->o06_ppaestimativa  = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaReceitaNova->o06_concarpeculiar = "$oEstimativa->o06_concarpeculiar";
      $oDaoPPAEstimativaReceitaNova->incluir(null);
      if ($oDaoPPAEstimativaReceitaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaReceitaNova->erro_status}", 2);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaReceitaNova);
    }

    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);

    /**
     * Pesquisamos todas as estimativas de despesa
     */
    $oDaoPPAEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $sSqlEstimativasDespesa   = $oDaoPPAEstimativaDespesa->sql_query_conplano(null,
                                                                              "ppaestimativadespesa.*,
      ppaestimativa.*,
      ppadotacao.*,
      ppadotacaoorcdotacao.*",
                                                                              null,
                                                                              "o05_ppaversao={$this->codigoversao}");
    $rsEstimativasDespesa     = $oDaoPPAEstimativaDespesa->sql_record($sSqlEstimativasDespesa);
    $aItensEstimativasDespesa = db_utils::getCollectionByRecord($rsEstimativasDespesa);
    $oPPADotacao = db_utils::getDao("ppadotacao");
    foreach ($aItensEstimativasDespesa as $oEstimativa) {

      $oPPADotacao->o08_ano               = $oEstimativa->o08_ano;
      $oPPADotacao->o08_elemento          = $oEstimativa->o08_elemento;
      $oPPADotacao->o08_funcao            = $oEstimativa->o08_funcao;
      $oPPADotacao->o08_instit            = $oEstimativa->o08_instit;
      $oPPADotacao->o08_localizadorgastos = $oEstimativa->o08_localizadorgastos;
      $oPPADotacao->o08_orgao             = $oEstimativa->o08_orgao;
      $oPPADotacao->o08_ppaversao         = $oDaoPPaVersao->o119_sequencial;
      $oPPADotacao->o08_programa          = $oEstimativa->o08_programa;
      $oPPADotacao->o08_projativ          = $oEstimativa->o08_projativ;
      $oPPADotacao->o08_recurso           = $oEstimativa->o08_recurso;
      $oPPADotacao->o08_subfuncao         = $oEstimativa->o08_subfuncao;
      $oPPADotacao->o08_unidade           = $oEstimativa->o08_unidade;
      $oPPADotacao->o08_concarpeculiar    = "{$oEstimativa->o08_concarpeculiar}";
      $oPPADotacao->incluir(null);
      if ($oPPADotacao->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oPPADotacao->erro_status}", 2);
      }
      if ($oEstimativa->o19_coddot != "") {

        $oDaoPPaORcdotacao                 = db_utils::getDao("ppadotacaoorcdotacao");
        $oDaoPPaORcdotacao->o19_anousu     = $oEstimativa->o19_anousu;
        $oDaoPPaORcdotacao->o19_coddot     = $oEstimativa->o19_coddot;
        $oDaoPPaORcdotacao->o19_ppadotacao = $oPPADotacao->o08_sequencial;
        $oDaoPPaORcdotacao->incluir(null);
        if ($oDaoPPaORcdotacao->erro_status == 0) {
          throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPaORcdotacao->erro_status}", 2);
        }
      }
      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $oDaoPPaVersao->o119_sequencial;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaNova->erro_status}", 2);
      }
      $oDaoPPAEstimativaDespesaNova = new cl_ppaestimativaDespesa;
      $oDaoPPAEstimativaDespesaNova->o07_anousu        = $oEstimativa->o07_anousu;
      $oDaoPPAEstimativaDespesaNova->o07_coddot        = $oPPADotacao->o08_sequencial;
      $oDaoPPAEstimativaDespesaNova->o07_ppaestimativa = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaDespesaNova->incluir(null);
      if ($oDaoPPAEstimativaDespesaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaDespesaNova->erro_status}", 2);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaDespesaNova);
    }
    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);

    /**
     * finalizamos a versao anterior;
     */

    $this->setFinalizada(true);
    $this->setHomologada($lHomologar);
    $this->setDatatermino(date("Y-m-d", db_getsession("DB_datausu")));
    $this->save();
    return true;

  }

  /**
   * Retorna a versao atual com dos dados da versao passada por parametro
   * @param $iVersaoBase
   *
   * @return bool
   * @throws Exception
   */
  public function retornaVersao($iVersaoBase) {

    /**
     * excluimos  as estimativas da versao atual
     */
    $oDaoPPAEstimativa        = db_utils::getDao("ppaestimativa");
    $oDaoPPAEstimativareceita = db_utils::getDao("ppaestimativareceita");
    $oDaoPPAEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $sSqlEstimativasDespesa   = $oDaoPPAEstimativaDespesa->sql_query_conplano(null,
                                                                              "ppaestimativadespesa.*,
      ppaestimativa.*.
      ppadotacao.*,
      ppadotacaoorcdotacao.*",
                                                                              null,
                                                                              "o05_ppaversao={$this->codigoversao}");
    $rsEstimativasDespesa     = $oDaoPPAEstimativaDespesa->sql_record($sSqlEstimativasDespesa);
    $aItensEstimativasDespesa = db_utils::getCollectionByRecord($rsEstimativasDespesa);
    foreach ($aItensEstimativasDespesa as $oEstimativaDespesa) {

      $oDaoPPAEstimativaDespesa->excluir($oEstimativaDespesa->o07_sequencial);
      $oDaoPPAEstimativa->excluir($oEstimativaDespesa->o05_sequencial);

    }

    unset($aItensEstimativasDespesa);
    /**
     * Excluimos as estimativas de receita
     */
    $sSqlEstimativasreceita   = $oDaoPPAEstimativareceita->sql_query(null,
                                                                     "ppaestimativareceita.*,
      ppaestimativa.*",
                                                                     null,
                                                                     "o05_ppaversao={$this->codigoversao}");
    $rsEstimativasreceita     = $oDaoPPAEstimativareceita->sql_record($sSqlEstimativasreceita);
    $aItensEstimativasreceita = db_utils::getCollectionByRecord($rsEstimativasreceita);

    foreach ($aItensEstimativasreceita as $oEstimativa) {

      $oDaoPPAEstimativareceita->excluir($oEstimativa->o06_sequencial);
      $oDaoPPAEstimativa->excluir($oEstimativa->o05_sequencial);

    }
    unset($aItensEstimativasreceita);

    /**
     * Incluimos as estimativas novamente;
     */

    $sSqlEstimativasreceita   = $oDaoPPAEstimativareceita->sql_query(null,
                                                                     "ppaestimativareceita.*,
      ppaestimativa.*",
                                                                     null,
                                                                     "o05_ppaversao={$iVersaoBase}");
    $rsEstimativasreceita     = $oDaoPPAEstimativareceita->sql_record($sSqlEstimativasreceita);
    $aItensEstimativasreceita = db_utils::getCollectionByRecord($rsEstimativasreceita);

    foreach ($aItensEstimativasreceita as $oEstimativa) {

      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $this->codigoversao;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar versao!\n{$oDaoPPAEstimativaNova->erro_status}", 2);
      }
      $oDaoPPAEstimativaReceitaNova = new cl_ppaestimativareceita;
      $oDaoPPAEstimativaReceitaNova->o06_anousu        = $oEstimativa->o06_anousu;
      $oDaoPPAEstimativaReceitaNova->o06_codrec        = $oEstimativa->o06_codrec;
      $oDaoPPAEstimativaReceitaNova->o06_ppaversao     = $this->codigoversao;
      $oDaoPPAEstimativaReceitaNova->o06_ppaestimativa = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaReceitaNova->incluir(null);
      if ($oDaoPPAEstimativaReceitaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar versao!\n{$oDaoPPAEstimativaReceitaNova->erro_status}", 2);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaReceitaNova);
    }

    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);

    /**
     * Pesquisamos todas as estimativas de despesa
     */
    $oDaoPPAEstimativaDespesa = db_utils::getDao("ppaestimativadespesa");
    $sSqlEstimativasDespesa   = $oDaoPPAEstimativaDespesa->sql_query(null,
                                                                     "ppaestimativadespesa.*,
      ppaestimativa.*",
                                                                     null,
                                                                     "o05_ppaversao={$iVersaoBase}");
    $rsEstimativasDespesa     = $oDaoPPAEstimativaDespesa->sql_record($sSqlEstimativasDespesa);
    $aItensEstimativasDespesa = db_utils::getCollectionByRecord($rsEstimativasDespesa);

    foreach ($aItensEstimativasDespesa as $oEstimativa) {

      $oDaoPPAEstimativaNova = new cl_ppaestimativa;
      $oDaoPPAEstimativaNova->o05_anoreferencia = $oEstimativa->o05_anoreferencia;
      $oDaoPPAEstimativaNova->o05_base          = $oEstimativa->o05_base == "t"?"true":"false";
      $oDaoPPAEstimativaNova->o05_ppaversao     = $this->codigoversao;
      $oDaoPPAEstimativaNova->o05_valor         = "{$oEstimativa->o05_valor}";
      $oDaoPPAEstimativaNova->incluir(null);
      if ($oDaoPPAEstimativaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaNova->erro_status}", 2);
      }
      $oDaoPPAEstimativaDespesaNova = new cl_ppaestimativaDespesa;
      $oDaoPPAEstimativaDespesaNova->o07_anousu        = $oEstimativa->o07_anousu;
      $oDaoPPAEstimativaDespesaNova->o07_coddot        = $oEstimativa->o07_coddot;
      $oDaoPPAEstimativaDespesaNova->o07_ppaestimativa = $oDaoPPAEstimativaNova->o05_sequencial;
      $oDaoPPAEstimativaDespesaNova->incluir(null);
      if ($oDaoPPAEstimativaDespesaNova->erro_status == 0) {
        throw new Exception("Erro ao salvar nova versao!\n{$oDaoPPAEstimativaDespesaNova->erro_status}", 2);
      }
      unset($oDaoPPAEstimativaNova);
      unset($oDaoPPAEstimativaDespesaNova);
    }
    unset($aItensEstimativasreceita);
    unset($rsEstimativasreceita);

    return true;
  }

  public function save() {

    $oDaoPPaVersao = db_utils::getDao("ppaversao");
    $oDaoPPaVersao->o119_datainicio  = $this->getDatainicio();
    $oDaoPPaVersao->o119_datatermino = $this->getDatatermino();
    $oDaoPPaVersao->o119_finalizada  = $this->isFinalizada()?"true":"false";
    $oDaoPPaVersao->o119_versaofinal = $this->isHomologada()?"true":"false";
    $oDaoPPaVersao->o119_versao      = $this->getVersao();
    $oDaoPPaVersao->o119_idusuario   = db_getsession("DB_id_usuario");
    $oDaoPPaVersao->o119_ppalei      = $this->getCodigolei();

    $sAtivo = 'true';
    if (!$this->lAtivo) {
      $sAtivo = 'false';
    }

    $oDaoPPaVersao->o119_ativo = $sAtivo;


    if (empty($this->codigoversao)) {

      $t = "I";
      $oDaoPPaVersao->incluir(null);
      $this->codigoversao = $oDaoPPaVersao->o119_sequencial;

    } else {

      $t = "A";
      $oDaoPPaVersao->o119_sequencial = $this->codigoversao;
      $oDaoPPaVersao->alterar($this->codigoversao);

    }

    if ($oDaoPPaVersao->erro_status == 0) {
      throw new Exception("Erro ao salvar ($t)versão do ppa\n{$oDaoPPaVersao->erro_msg}", 1);
    }
  }

  /**
   * Retorna o ultimo ano integrado com o orcamento
   * @return  integer;
   */
  public function getUltimoAnoIntegrado() {

    $oDaoPPAIntegracao    = db_utils::getDao("ppaintegracao");
    $sSqlUltimaIntegracao = $oDaoPPAIntegracao->sql_query_file(null,
                                                               "max(o123_Ano) as ultimoano",
                                                               null,
                                                               "exists (select 1
      from ppaintegracaodespesa
      where o121_ppaintegracao = o123_sequencial)
      and o123_instit = ".db_getsession("DB_instit")."
      and o123_tipointegracao = 1
      and o123_situacao = 1"
    );

    $rsUltimaIntegracao = $oDaoPPAIntegracao->sql_record($sSqlUltimaIntegracao);
    $iUltimoAno         = db_utils::fieldsMemory($rsUltimaIntegracao, 0)->ultimoano;
    if ($iUltimoAno == "") {
      $iUltimoAno = null;
    }
    return $iUltimoAno;
  }

  /**
   * Gera a integracao do ppa com o orcamento
   *
   */
  public function gerarIntegracao() {

    $iAno  = $this->getUltimoAnoIntegrado();
    if ($iAno == null) {
      $iAno = $this->getAnoinicio();
    } else {
      $iAno = $iAno+1;
    }

    $iAnoExercicio = db_getsession("DB_anousu");

    if(($iAno - $iAnoExercicio) > 1 ){
      $sErroMsg = "Perspectiva já processada o exercício seguinte.";
      throw new Exception($sErroMsg, 10);
    }
    $oDaoPPAReceita = db_utils::getDao("ppaestimativareceita");
    $oDaoOrcReceita = db_utils::getDao("orcreceita");
    $oDaoIntegracao = db_utils::getDao("ppaintegracao");

    $oDaoIntegracao->o123_ano       = $iAno;
    $oDaoIntegracao->o123_data      = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoIntegracao->o123_idusuario = db_getsession("DB_id_usuario");
    $oDaoIntegracao->o123_situacao  = 1;
    $oDaoIntegracao->o123_instit    = db_getsession("DB_instit");
    $oDaoIntegracao->o123_tipointegracao = 1;
    $oDaoIntegracao->o123_ppaversao = $this->getCodigoversao();
    $oDaoIntegracao->incluir(null);
    if ($oDaoIntegracao->erro_status == 0) {

      $sErroMsg = "Erro ao iniciar integração do PPA com o Orçamento\nSolicite Suporte\nErro Numero 10";
      throw new Exception($sErroMsg, 10);

    }
    $sSqlPPaReceita = $oDaoPPAReceita->sql_query_analitica(null,
                                                           "ppaestimativareceita.*,
      o05_valor,
      c61_codigo",
                                                           null,
                                                           "c61_instit           = ".db_getsession("DB_instit")."
      and o06_ppaversao     = ".$this->getCodigoversao()."
      and o05_ppaversao     = ".$this->getCodigoversao()."
      and o06_anousu        = {$iAno}"
    );
    $rsReceita      = db_query(analiseQueryPlanoOrcamento($sSqlPPaReceita));
    $aReceitas      = db_utils::getCollectionByRecord($rsReceita);
    foreach ($aReceitas as $oReceita) {

      /**
       * Verificamos se ja existe receita na tabela orcreceita para o ano corrente;
       * caso sim, usamos o mesmp codrec para a receita do ppa
       */

      /**
       * Ignora receitas com valor igual a zero
       */
      if (round($oReceita->o05_valor, 2) == 0) {
        continue;
      }
      $sWhere       = "     o70_anousu = ".db_getsession("DB_anousu");
      $sWhere      .= " and o70_instit = ".db_getsession("DB_instit");
      $sWhere      .= " and o70_codfon = {$oReceita->o06_codrec}";
      $sWhere      .= " and o70_concarpeculiar = '{$oReceita->o06_concarpeculiar}'";
      $sSqlReceita  = $oDaoOrcReceita->sql_query_file(null,
                                                      null,
                                                      "*",
                                                      null,
                                                      $sWhere
      );
      $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
      if ($oDaoOrcReceita->numrows == 1) {

        $oReceitaOrcamentaria = db_utils::fieldsMemory($rsReceita, 0);
        $oDaoOrcReceita->o70_codrec = $oReceitaOrcamentaria->o70_codrec;

      } else {
        $oDaoOrcReceita->o70_codrec = null;
      }

      /**
       * Verificamos se a receita já existe no ano da integracao:
       */
      $sWhereJaLancada       = "     o70_anousu = {$iAno} ";
      $sWhereJaLancada      .= " and o70_instit = ".db_getsession("DB_instit");
      $sWhereJaLancada      .= " and o70_codfon = {$oReceita->o06_codrec}";
      $sWhereJaLancada      .= " and o70_concarpeculiar = '{$oReceita->o06_concarpeculiar}'";
      $sSqlReceitaJaLancada  = $oDaoOrcReceita->sql_query_file(null, null, "*", null, $sWhereJaLancada);

      $rsReceitaLancada     = $oDaoOrcReceita->sql_record($sSqlReceitaJaLancada);
      if ($oDaoOrcReceita->numrows > 0) {

        $sErroMsg  = "Erro ao Incluir nova Receita.\nReceita já lançada no exercício {$iAno}";
        throw new Exception($sErroMsg, 12);
      }
      /**
       * Incluimos a receita
       */
      $oDaoOrcReceita->o70_anousu         = $iAno;
      $oDaoOrcReceita->o70_codigo         = $oReceita->c61_codigo;
      $oDaoOrcReceita->o70_codfon         = $oReceita->o06_codrec;
      $oDaoOrcReceita->o70_concarpeculiar = $oReceita->o06_concarpeculiar;
      $oDaoOrcReceita->o70_instit         = db_getsession("DB_instit");
      $oDaoOrcReceita->o70_reclan         = "false";
      $oDaoOrcReceita->o70_valor          = "{$oReceita->o05_valor}";
      $oDaoOrcReceita->incluir($iAno, $oDaoOrcReceita->o70_codrec);
      if ($oDaoOrcReceita->erro_status == '0') {

        $sErroMsg  = "Erro ao Incluir nova Receita.\n";
        $sErroMsg .= $oDaoOrcReceita->erro_banco;
        throw new Exception($sErroMsg, 11);

      }
      /**
       * Incluimos a ligacao da receita gerada com a estimativa do ppa
       */
      $oDaoPPAIntegracaoReceita = db_utils::getDao("ppaintegracaoreceita");
      $oDaoPPAIntegracaoReceita->o122_anousu = $iAno;
      $oDaoPPAIntegracaoReceita->o122_codrec = $oDaoOrcReceita->o70_codrec;
      $oDaoPPAIntegracaoReceita->o122_ppaintegracao        = $oDaoIntegracao->o123_sequencial;
      $oDaoPPAIntegracaoReceita->o122_ppaestimativareceita = $oReceita->o06_sequencial;
      $oDaoPPAIntegracaoReceita->incluir(null);
      if ($oDaoPPAIntegracaoReceita->erro_status == 0) {

        $sErroMsg  = "Erro ao Incluir integração da Receita com o ppa.\n";
        $sErroMsg .= $oDaoPPAIntegracaoReceita->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 12";
        throw new Exception($sErroMsg, 12);

      }
    }

    /**
     * incluimos as Dotacoes
     */
    $oDaoPPADespesa = new cl_ppaestimativadespesa;
    $sSqlDotacoes   = $oDaoPPADespesa->sql_query(null,
                                                 "ppadotacao.*,
      o05_valor,
      o07_sequencial",
                                                 null,
                                                 "o08_ppaversao = {$this->codigoversao}
      and o08_instit = ".db_getsession("DB_instit")."
      and o05_anoreferencia = {$iAno}"
    );
    $rsDotacao      = $oDaoPPADespesa->sql_record($sSqlDotacoes);
    $rsDotacoes     = $oDaoPPADespesa->sql_record($sSqlDotacoes);
    $aDespesas      = db_utils::getCollectionByRecord($rsDotacoes);
    $oDaoOrcDotacao = db_utils::getDao("orcdotacao");

    foreach ($aDespesas as $oDespesa) {

      /**
       * Verificamos se ja existe despesa na tabela orcdotacao para o ano corrente;
       * caso sim, usamos o mesmo coddot para a receita do ppa
       */
      /**
       * Ignora despesas com valor igual a zero
       */
      if (round($oDespesa->o05_valor, 2) == 0) {

        continue;
      }

      $sWhere       = "     o58_anousu            = ".db_getsession("DB_anousu");
      $sWhere      .= " and o58_instit            = ".db_getsession("DB_instit");
      $sWhere      .= " and o58_orgao             = {$oDespesa->o08_orgao}";
      $sWhere      .= " and o58_unidade           = {$oDespesa->o08_unidade}";
      $sWhere      .= " and o58_funcao            = {$oDespesa->o08_funcao}";
      $sWhere      .= " and o58_subfuncao         = {$oDespesa->o08_subfuncao}";
      $sWhere      .= " and o58_programa          = {$oDespesa->o08_programa}";
      $sWhere      .= " and o58_projativ          = {$oDespesa->o08_projativ}";
      $sWhere      .= " and o58_codele            = {$oDespesa->o08_elemento}";
      $sWhere      .= " and o58_codigo            = {$oDespesa->o08_recurso}";
      $sWhere      .= " and o58_concarpeculiar    = '{$oDespesa->o08_concarpeculiar}'";
      $sWhere      .= " and o58_localizadorgastos = {$oDespesa->o08_localizadorgastos}";
      $sSqlDotacao  = $oDaoOrcDotacao->sql_query_file(null,
                                                      null,
                                                      "*",
                                                      null,
                                                      $sWhere
      );
      $rsDotacoes    = $oDaoOrcDotacao->sql_record($sSqlDotacao);
      if ($oDaoOrcDotacao->numrows == 1) {

        $oDespesaOrcamentaria = db_utils::fieldsMemory($rsDotacoes, 0);
        $oDaoOrcDotacao->o58_coddot = $oDespesaOrcamentaria->o58_coddot;

      } else {

        /**
         * Atualizamos o Codigo da Dotacao para o proximo ano
         */
        $oDaoOrcParametro    = db_utils::getDao("orcparametro");

        /**
         * Buscamos o ultimo coddot da orcdotacao para atualizar na orcparametro
         */
        $sSqlMaxOrcParametro = "select max(o58_coddot) as o58_coddot from orcdotacao";
        $rsMaxOrcParametro   = db_query($sSqlMaxOrcParametro);
        $iCodDot             = db_utils::fieldsMemory($rsMaxOrcParametro, 0)->o58_coddot;
        $sSqlCodDot          = "update orcparametro set o50_coddot = {$iCodDot} + 1 where o50_anousu = {$iAno}";
        $rsCodDot            = db_query($sSqlCodDot);
        if (!$rsCodDot) {

          $sErroMsg  = "Erro ao gerar número da dotação. Verifique o cadastro dos paramêtros do orçamento para {$iAno}.\n";
          $sErroMsg .= "Solicite Suporte\nErro Número 1\n{$oDaoOrcParametro->erro_msg}";
          throw new Exception($sErroMsg, 1);

        }

        $sSqlNumeroDotacao          = $oDaoOrcParametro->sql_query_file($iAno, "o50_coddot as o58_coddot");
        $rsNumeroDotacao            = $oDaoOrcParametro->sql_record($sSqlNumeroDotacao);
        $oDaoOrcDotacao->o58_coddot = db_utils::fieldsMemory($rsNumeroDotacao, 0)->o58_coddot;
      }

      $oDaoOrcDotacao->o58_anousu            = $iAno;
      $oDaoOrcDotacao->o58_orgao             = $oDespesa->o08_orgao;
      $oDaoOrcDotacao->o58_unidade           = $oDespesa->o08_unidade;
      $oDaoOrcDotacao->o58_funcao            = $oDespesa->o08_funcao;
      $oDaoOrcDotacao->o58_subfuncao         = $oDespesa->o08_subfuncao;
      $oDaoOrcDotacao->o58_programa          = $oDespesa->o08_programa;
      $oDaoOrcDotacao->o58_projativ          = $oDespesa->o08_projativ;
      $oDaoOrcDotacao->o58_codele            = $oDespesa->o08_elemento;
      $oDaoOrcDotacao->o58_codigo            = $oDespesa->o08_recurso;
      $oDaoOrcDotacao->o58_valor             = "{$oDespesa->o05_valor}";
      $oDaoOrcDotacao->o58_localizadorgastos = $oDespesa->o08_localizadorgastos;
      $oDaoOrcDotacao->o58_instit            = $oDespesa->o08_instit;
      $oDaoOrcDotacao->o58_concarpeculiar    = $oDespesa->o08_concarpeculiar;
      $oDaoOrcDotacao->incluir($iAno, $oDaoOrcDotacao->o58_coddot);
      if ($oDaoOrcDotacao->erro_status == 0) {

        $sErroMsg  = "Erro ao Incluir nova Dotação ({$oDaoOrcDotacao->o58_coddot}).\n";
        $iNumeroErro = 13;
        if (strpos(strtolower(pg_last_error()),"orcdotacao_oufspae_in") != 0 ) {
          $iNumeroErro = 199;
        }
        $sErroMsg .= $oDaoOrcDotacao->erro_msg."\n";
        $sErroMsg .= "Solicite Suporte\nErro Número {$iNumeroErro}";
        throw new Exception($sErroMsg, $iNumeroErro);

      }
      /**
       * Vinculamos a despesa incluida ao ppa
       */
      $oDaoPPAIntegracaoDespesa = db_utils::getDao("ppaintegracaodespesa");
      $oDaoPPAIntegracaoDespesa->o121_anousu               = $iAno;
      $oDaoPPAIntegracaoDespesa->o121_coddot               = $oDaoOrcDotacao->o58_coddot;
      $oDaoPPAIntegracaoDespesa->o121_ppaintegracao        = $oDaoIntegracao->o123_sequencial;
      $oDaoPPAIntegracaoDespesa->o121_ppaestimativadespesa = $oDespesa->o07_sequencial;
      $oDaoPPAIntegracaoDespesa->incluir(null);
      if ($oDaoPPAIntegracaoDespesa->erro_status == 0) {

        $sErroMsg  = "Erro ao Incluir integração da Despesa com o ppa.\n";
        $sErroMsg .= $oDaoPPAIntegracaoDespesa->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 14";
        throw new Exception($sErroMsg, 14);

      }
    }
    return true;
  }

  public function cancelarIntegracao() {

    $sSqlAnoCancelar  =  "SELECT o123_ano, o123_sequencial ";
    $sSqlAnoCancelar .=  "  from ppaintegracao ";
    $sSqlAnoCancelar .=  " where o123_ano        = ".(db_getsession("DB_anousu")+1);
    $sSqlAnoCancelar .=  "   and o123_ppaversao  = {$this->codigoversao}";
    $sSqlAnoCancelar .=  "   and o123_situacao       = 1 ";
    $sSqlAnoCancelar .=  "   and o123_tipointegracao = 1 ";
    $sSqlAnoCancelar .=  "   and o123_instit     = ".db_getsession("DB_instit");
    //    $sSqlAnoCancelar .=  "   and exists(select 1 ";
    //    $sSqlAnoCancelar .=  "                from ppaintegracaodespesa ";
    //    $sSqlAnoCancelar .=  "               where o121_ppaintegracao = o123_sequencial)";
    $rsAnoCancelar   = db_query($sSqlAnoCancelar);
    $iAnoCancelar    = "";

    if (pg_num_rows($rsAnoCancelar) == 0) {
      throw new Exception("Não existe integrações a cancelar para esse ano.");
    }


    $iAnoCancelar = db_utils::fieldsMemory($rsAnoCancelar, 0)->o123_ano;
    $iIntegracao  = db_utils::fieldsMemory($rsAnoCancelar, 0)->o123_sequencial;

    /**
     * Iniciamos com o cancelamento das Dotacoes
     */
    $sSqlDotacoes  = "SELECT o58_coddot, ";
    $sSqlDotacoes .= "       o58_anousu,";
    $sSqlDotacoes .= "       o121_sequencial ,";
    $sSqlDotacoes .= "       exists(select 1 ";
    $sSqlDotacoes .= "             from orcreserva ";
    $sSqlDotacoes .= "            where o80_anousu = o58_anousu ";
    $sSqlDotacoes .= "              and o80_coddot = o58_coddot) as temreserva, ";
    $sSqlDotacoes .= "       exists(select 1 ";
    $sSqlDotacoes .= "               from conlancamdot ";
    $sSqlDotacoes .= "              where c73_coddot = o58_coddot";
    $sSqlDotacoes .= "                and c73_anousu = o58_anousu) as temlancam ";
    $sSqlDotacoes .= "  from ppaintegracaodespesa ";
    $sSqlDotacoes .= "       inner join orcdotacao on o58_coddot  = o121_coddot ";
    $sSqlDotacoes .= "                            and o121_anousu = o58_anousu  ";
    $sSqlDotacoes .= " where o121_ppaintegracao = {$iIntegracao}";

    $rsDotacoes               = db_query($sSqlDotacoes);
    $aDotacoes                = db_utils::getCollectionByRecord($rsDotacoes);
    $oDaoOrcDotacao           = db_utils::getDao("orcdotacao");
    $oDaoPPAIntegracaoDespesa = db_utils::getDao("ppaintegracaodespesa");
    foreach ($aDotacoes as $oDotacao) {

      if ($oDotacao->temreserva == "t") {

        $sMsg  = "Dotação {$oDotacao->o58_coddot}/{$oDotacao->o58_anousu} possui reservas de saldo.\n";
        $sMsg .= "Não podera ser cancelado a integração.";
        throw new Exception($sMsg);

      }

      if ($oDotacao->temlancam == "t") {

        $sMsg  = "Dotação {$oDotacao->o58_coddot}/{$oDotacao->o58_anousu} possui lançamentos contábeis.\n";
        $sMsg .= "Não podera ser cancelado a integração.";
        throw new Exception($sMsg);

      }
    }

    /**
     * senão existe nenhuma excessao. excluimos os registros gerados
     */
    foreach ($aDotacoes as $oDotacao) {


      $oDaoPPAIntegracaoDespesa->excluir($oDotacao->o121_sequencial);
      if ($oDaoPPAIntegracaoDespesa->erro_status == 0) {

        $sErroMsg  = "Erro ao cancelar integração da Despesa com o ppa.\n";
        $sErroMsg .= $oDaoPPAIntegracaoDespesa->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 3";
        throw new Exception($sErroMsg, 3);

      }
      /**
       * excluimos da tabela orcdotacao
       */
      $oDaoOrcDotacao->excluir($oDotacao->o58_anousu, $oDotacao->o58_coddot);
      if ($oDaoOrcDotacao->erro_status == 0) {

        $sErroMsg  = "Erro ao cancelar Dotação.\n";
        $sErroMsg .= $oDaoOrcDotacao->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 2";
        throw new Exception($sErroMsg, 2);

      }
    }
    /**
     * Iniciamos com o cancelamento das receitas
     */
    $sSqlReceitas  = "SELECT o70_codrec, ";
    $sSqlReceitas .= "       o70_anousu,";
    $sSqlReceitas .= "       o122_sequencial ,";
    $sSqlReceitas .= "       exists(select 1 ";
    $sSqlReceitas .= "             from orcsuplemrec ";
    $sSqlReceitas .= "            where o85_anousu = o70_anousu ";
    $sSqlReceitas .= "              and o85_codrec = o70_codrec) as temsuplem, ";
    $sSqlReceitas .= "       exists(select 1 ";
    $sSqlReceitas .= "               from conlancamrec ";
    $sSqlReceitas .= "              where c74_codrec = o70_codrec";
    $sSqlReceitas .= "                and c74_anousu = o70_anousu) as temlancam ";
    $sSqlReceitas .= "  from ppaintegracaoreceita";
    $sSqlReceitas .= "       inner join orcreceita on o70_codrec  = o122_codrec ";
    $sSqlReceitas .= "                            and o122_anousu = o70_anousu  ";
    $sSqlReceitas .= " where o122_ppaintegracao = {$iIntegracao}";
    $rsReceitas               = db_query($sSqlReceitas);
    $aReceitas                = db_utils::getCollectionByRecord($rsReceitas);
    $oDaoOrcReceita           = db_utils::getDao("orcreceita");
    $oDaoPPAIntegracaoReceita = db_utils::getDao("ppaintegracaoreceita");

    foreach ($aReceitas as $oReceita) {

      if ($oReceita->temsuplem == "t") {

        $sMsg  = "Receita {$oReceita->o70_codrec}/{$oReceita->o70_anousu} possui suplementações\n";
        $sMsg .= "Não podera ser cancelado a integração.";
        throw new Exception($sMsg);

      }

      if ($oReceita->temlancam == "t") {

        $sMsg  = "Receita {$oReceita->o70_codrec}/{$oReceita->o70_anousu} possui lançamentos contábeis.\n";
        $sMsg .= "Não podera ser cancelado a integração.";
        throw new Exception($sMsg);

      }
    }

    /**
     * senão existe nenhuma excessao. excluimos os registros gerados
     */
    foreach ($aReceitas as $oReceita) {

      $oDaoPPAIntegracaoReceita->excluir($oReceita->o122_sequencial);
      if ($oDaoPPAIntegracaoReceita->erro_status == 0) {

        $sErroMsg  = "Erro ao cancelar integração da Receita com o ppa.\n";
        $sErroMsg .= $oDaoPPAIntegracaoReceita->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 3";
        throw new Exception($sErroMsg, 3);

      }

      /**
       * excluimos da tabela orcReceita
       */
      $oDaoOrcReceita->excluir($oReceita->o70_anousu, $oReceita->o70_codrec);
      if ($oDaoOrcReceita->erro_status == 0) {

        $sErroMsg  = "Erro ao cancelar Receita\n";
        $sErroMsg .= $oDaoOrcReceita->erro_msg;
        $sErroMsg .= "Solicite Suporte\nErro Número 2";
        throw new Exception($sErroMsg, 2);

      }

    }
    $oDaoppaIntegracao = db_utils::getDao("ppaintegracao");
    $oDaoppaIntegracao->o123_sequencial = $iIntegracao;
    $oDaoppaIntegracao->o123_situacao   = 2;
    $oDaoppaIntegracao->alterar($iIntegracao);
    if ($oDaoppaIntegracao->erro_status == 0) {

      $sErroMsg  = "Erro ao cancelar integração com o ppa.\n";
      $sErroMsg .= $oDaoppaIntegracao->erro_msg;
      $sErroMsg .= "Solicite Suporte\nErro Número 3";
      throw new Exception($sErroMsg, 3);
    }

    return true;
  }

  /**
   * retorna os anos integrados ao orçamento
   * @return Array com os anos já integrados
   */
  public function getAnosIntegrados() {

    $sSqlVerificaAnos  = "SELECT o123_ano, ";
    $sSqlVerificaAnos .= "       o123_sequencial ";
    $sSqlVerificaAnos .= "  from ppaintegracao ";
    $sSqlVerificaAnos .= " where o123_ppaversao = {$this->getCodigoversao()} ";
    $sSqlVerificaAnos .= "   and o123_situacao  = 1 ";
    $sSqlVerificaAnos .= "   and o123_tipointegracao  = 1 ";
    $sSqlVerificaAnos .= "   and o123_instit    =  ".db_getsession("DB_instit");
    //    $sSqlVerificaAnos .= "   and exists(select 1 ";
    //    $sSqlVerificaAnos .= "                from ppaintegracaodespesa ";
    //    $sSqlVerificaAnos .= "               where o121_ppaintegracao = o123_sequencial)";
    $rsAnosIntegrados = db_query($sSqlVerificaAnos);
    $iTotalAnos       = pg_num_rows($rsAnosIntegrados);
    $aAnosIntegrados = array();
    for ($i = 0; $i < $iTotalAnos; $i++) {
      $aAnosIntegrados = db_utils::fieldsMemory($rsAnosIntegrados, $i)->o123_ano;
    }
    return $aAnosIntegrados;
  }


  public function alterarStatusAtivacaoPerspectiva($lAtivo) {

    $this->lAtivo = $lAtivo;
    $this->save();
  }
}