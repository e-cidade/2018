<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * controle de suplementacoes
 * @package Orcamento
 * @subpackage Suplementacao 
 * @author dbiuri $
 * @version 1.6 $
 */
class Suplementacao {
  
  protected $iCodigo;
  
  protected $aSuplementacoes = array();
  
  protected $aReducoes = array();
  
  protected $iTipo;
  
  protected $sDescricaoTipo;
  
  protected $dtProcessamento;
  /**
   * 
   */
  function __construct($iSuplementacao) {
    $this->iCodigo = $iSuplementacao;
    if (!empty($this->iCodigo)) {
      
      $oDaoSuplementacao = db_utils::getDao("orcsuplem");
      $sSqlSuplementacao = $oDaoSuplementacao->sql_query_sup($this->iCodigo);
      $rsSuplementacao   = $oDaoSuplementacao->sql_record($sSqlSuplementacao);
      if ($oDaoSuplementacao->numrows == 1) {
        
        $oDadosSuplementacao   = db_utils::fieldsMemory($rsSuplementacao, 0);
        $this->iTipo           = $oDadosSuplementacao->o48_tiposup;
        $this->dtProcessamento = $oDadosSuplementacao->o49_data;
        $this->sDescricaoTipo  = $oDadosSuplementacao->o48_descr;
        unset($oDaoSuplementacao);
      }
    }
  }
  /**
   * @return unknown
   */
  public function getAReducoes() {

    return $this->aReducoes;
  }
  
  /**
   * @return unknown
   */
  public function getSuplementacoes() {

    return $this->aSuplementacoes;
  }
  
  /**
   * @param unknown_type $aSuplementacoes
   */
  public function setSuplementacoes($aSuplementacoes) {

    $this->aSuplementacoes = $aSuplementacoes;
  }
  
  /**
   * @return unknown
   */
  public function getDataProcessamento() {

    return $this->dtProcessamento;
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getTipo() {

    return $this->iTipo;
  }
  
  /**
   * @return unknown
   */
  public function getDescricaoTipo() {

    return $this->sDescricaoTipo;
  }

  /**
   * calcula o valor toda das suplementacoes da suplementação
   
   * @return float valor total da suplementacao
   */
  public function getvalorSuplementacao() {
    
    $sSqlTotalSuplementacao  = "select sum(o47_valor) as soma_suplem ";
    $sSqlTotalSuplementacao .= "  from orcsuplemval "; 
    $sSqlTotalSuplementacao .= " where o47_codsup={$this->iCodigo} and o47_valor > 0";
    $sSqlTotalSuplementacao .= " union all ";
    $sSqlTotalSuplementacao .= "select sum(o136_valor) as soma_suplem ";
    $sSqlTotalSuplementacao .= "  from orcsuplemdespesappa "; 
    $sSqlTotalSuplementacao .= " where o136_orcsuplem={$this->iCodigo} and o136_valor > 0";
    $rsTotalSuplementacao    = db_query($sSqlTotalSuplementacao);
    $nValorSuplementacoes    = 0;
    $aSuplementacoes         = db_utils::getColectionByRecord($rsTotalSuplementacao);
    foreach ($aSuplementacoes as $oSuplementacao) {
    	$nValorSuplementacoes += $oSuplementacao->soma_suplem;
    }
    
    unset($aSuplementacoes);
    return $nValorSuplementacoes;
  }
  
  /**
   * retorna os valores das reduções 
   *
   * @return float
   */
  public function getValorReducao() {
    
    $sSqlTotalSuplementacao  = "select sum(o47_valor) as soma_suplem ";
    $sSqlTotalSuplementacao .= "  from orcsuplemval "; 
    $sSqlTotalSuplementacao .= " where o47_codsup={$this->iCodigo} and o47_valor < 0";
    $sSqlTotalSuplementacao .= " union all ";
    $sSqlTotalSuplementacao .= "select sum(o136_valor) as soma_suplem ";
    $sSqlTotalSuplementacao .= "  from orcsuplemdespesappa "; 
    $sSqlTotalSuplementacao .= " where o136_orcsuplem={$this->iCodigo} and o136_valor < 0";
    $rsTotalSuplementacao    = db_query($sSqlTotalSuplementacao);
    $nValorSuplementacoes    = 0;
    $aSuplementacoes         = db_utils::getColectionByRecord($rsTotalSuplementacao);
    foreach ($aSuplementacoes as $oSuplementacao) {
      $nValorSuplementacoes += $oSuplementacao->soma_suplem;
    }
    unset($aSuplementacoes);
    return $nValorSuplementacoes;    
  }
  /**
   * retorna o valor de receitas suplementadas
   *
   * @return float
   */
  public function getValorReceita() {
    
    $sSqlTotalReceita  = "select sum(o85_valor) as soma_suplem ";
    $sSqlTotalReceita .= "  from orcsuplemrec "; 
    $sSqlTotalReceita .= " where o85_codsup={$this->iCodigo}";
    $sSqlTotalReceita .= " union all ";
    $sSqlTotalReceita .= "select sum(o137_valor) as soma_suplem ";
    $sSqlTotalReceita .= "  from orcsuplemreceitappa "; 
    $sSqlTotalReceita .= " where o137_orcsuplem={$this->iCodigo}";
    $rsTotalReceita    = db_query($sSqlTotalReceita);
    $nValorReceitas    = 0;
    $aReceitas         = db_utils::getColectionByRecord($rsTotalReceita);
    foreach ($aReceitas as $oReceita) {
      $nValorReceitas += $oReceita->soma_suplem;
    }
    unset($aReceitas);
    return $nValorReceitas;    
  }
  
  /**
   * processa as dotacoes/Receitas novas no orçamento
   *
   */
  public function processarDadosPPA() {
    
    /**
     * Criamos a dotações no orçaamento, caso existe alguma dotacao usada na suplementacao
     */
    $iAnoIntegracao   = db_getsession("DB_anousu");
    $oDaoDotacoesPPA  = db_utils::getDao("orcsuplemdespesappa");
    $sWhere           = " o136_orcsuplem = {$this->iCodigo} ";
    $sSqlDotacoesPPA  = $oDaoDotacoesPPA->sql_query_dotacaoppa(null, 
                                                               "o136_sequencial,
                                                               o136_valor,
                                                               ppadotacao.*,
                                                               o07_sequencial", null, $sWhere);
    $rsDotacoes       = $oDaoDotacoesPPA->sql_record($sSqlDotacoesPPA);
    $aDotacoesPPA     = db_utils::getColectionByRecord($rsDotacoes);
    foreach ($aDotacoesPPA as $oDotacaoPPA) {
      
      /**
       * verificamos se a dotaçao já existe no ano 
       */
      $iAno = $oDotacaoPPA->o08_ano;
      $sSqlVerificaDotacao  = "Select o58_coddot ";
      $sSqlVerificaDotacao .= "  from orcdotacao";
      $sSqlVerificaDotacao .= " where o58_orgao             = {$oDotacaoPPA->o08_orgao} ";
      $sSqlVerificaDotacao .= "   and o58_unidade           = {$oDotacaoPPA->o08_unidade} ";
      $sSqlVerificaDotacao .= "   and o58_funcao            = {$oDotacaoPPA->o08_funcao} ";
      $sSqlVerificaDotacao .= "   and o58_subfuncao         = {$oDotacaoPPA->o08_subfuncao} ";
      $sSqlVerificaDotacao .= "   and o58_programa          = {$oDotacaoPPA->o08_programa} ";
      $sSqlVerificaDotacao .= "   and o58_projativ          = {$oDotacaoPPA->o08_projativ} ";
      $sSqlVerificaDotacao .= "   and o58_codele            = {$oDotacaoPPA->o08_elemento} ";
      $sSqlVerificaDotacao .= "   and o58_codigo            = {$oDotacaoPPA->o08_recurso} ";
      $sSqlVerificaDotacao .= "   and o58_localizadorgastos = {$oDotacaoPPA->o08_localizadorgastos} ";
      $sSqlVerificaDotacao .= "   and o58_concarpeculiar    = '{$oDotacaoPPA->o08_concarpeculiar}'";
      $sSqlVerificaDotacao .= "   and o58_instit            = {$oDotacaoPPA->o08_instit} ";
      $sSqlVerificaDotacao .= "   and o58_anousu            = {$iAnoIntegracao} ";
      $rsVerificaDotacao    = db_query($sSqlVerificaDotacao);
      $iCodigoDotacao       = null;
      if (pg_num_rows($rsVerificaDotacao) == 1) {
        $iCodigoDotacao = db_utils::fieldsMemory($rsDotacoes, 0)->o58_coddot;      
      } else {
        
        /**
         * integramos a dotaçao com o orcamento, atravez de uma integração do tipo 2
         */
        $oDaoIntegracao = db_utils::getDao("ppaintegracao");
        $oDaoOrcDotacao = db_utils::getDao("orcdotacao");
        $oDaoIntegracao->o123_ano       = db_getsession("DB_anousu");
        $oDaoIntegracao->o123_data      = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoIntegracao->o123_idusuario = db_getsession("DB_id_usuario");
        $oDaoIntegracao->o123_situacao  = 1;
        $oDaoIntegracao->o123_instit    = db_getsession("DB_instit");
        $oDaoIntegracao->o123_tipointegracao = 2;
        $oDaoIntegracao->o123_ppaversao = $oDotacaoPPA->o08_ppaversao;
        $oDaoIntegracao->incluir(null);
        
        if ($oDaoIntegracao->erro_status == 0) {
          throw new Exception("Erro ao gerar integração da dotação do ppa com o orçamento.");
        }
        /**
         * verifica se a dotação existe no ano anterior, e tentamos manter o mesmo codigo de dotação.
         */
        $sWhere       = "     o58_anousu            = ".($iAnoIntegracao-1);
        $sWhere      .= " and o58_instit            = ".db_getsession("DB_instit");
        $sWhere      .= " and o58_orgao             = {$oDotacaoPPA->o08_orgao}";
        $sWhere      .= " and o58_unidade           = {$oDotacaoPPA->o08_unidade}";
        $sWhere      .= " and o58_funcao            = {$oDotacaoPPA->o08_funcao}";
        $sWhere      .= " and o58_subfuncao         = {$oDotacaoPPA->o08_subfuncao}";
        $sWhere      .= " and o58_programa          = {$oDotacaoPPA->o08_programa}";
        $sWhere      .= " and o58_projativ          = {$oDotacaoPPA->o08_projativ}";
        $sWhere      .= " and o58_codele            = {$oDotacaoPPA->o08_elemento}";
        $sWhere      .= " and o58_codigo            = {$oDotacaoPPA->o08_recurso}";
        $sWhere      .= " and o58_localizadorgastos = {$oDotacaoPPA->o08_localizadorgastos}";
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
          $oDaoOrcParametro = db_utils::getDao("orcparametro");
          $sSqlCodDot       = "update orcparametro set o50_coddot = o50_coddot + 1 where o50_anousu = {$iAnoIntegracao}";
          $rsCodDot         = db_query($sSqlCodDot);
          if (!$rsCodDot) {
            
            $sErroMsg  = "Erro ao gerar número da dotação. Verifique o cadastro dos paramêtros do orçamento para {$iAnoIntegracao}.\n";
            $sErroMsg .= "Solicite Suporte\nErro Número 1\n{$oDaoOrcParametro->erro_msg}";
            throw new Exception($sErroMsg, 1);
            
          }
          
          $sSqlNumeroDotacao          = $oDaoOrcParametro->sql_query_file($iAnoIntegracao, 'o50_coddot as o58_coddot');
          $rsNumeroDotacao            = $oDaoOrcParametro->sql_record($sSqlNumeroDotacao);
          $oDaoOrcDotacao->o58_coddot = db_utils::fieldsMemory($rsNumeroDotacao, 0)->o58_coddot;
          
        }
        $iCodigoDotacao  = $oDaoOrcDotacao->o58_coddot;
        $oDaoOrcDotacao->o58_anousu            = $iAnoIntegracao;
        $oDaoOrcDotacao->o58_orgao             = $oDotacaoPPA->o08_orgao;
        $oDaoOrcDotacao->o58_unidade           = $oDotacaoPPA->o08_unidade;
        $oDaoOrcDotacao->o58_funcao            = $oDotacaoPPA->o08_funcao;
        $oDaoOrcDotacao->o58_subfuncao         = $oDotacaoPPA->o08_subfuncao;
        $oDaoOrcDotacao->o58_programa          = $oDotacaoPPA->o08_programa;
        $oDaoOrcDotacao->o58_projativ          = $oDotacaoPPA->o08_projativ;
        $oDaoOrcDotacao->o58_codele            = $oDotacaoPPA->o08_elemento;
        $oDaoOrcDotacao->o58_codigo            = $oDotacaoPPA->o08_recurso;
        $oDaoOrcDotacao->o58_datacriacao       = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoOrcDotacao->o58_valor             = "0";
        $oDaoOrcDotacao->o58_concarpeculiar    = $oDotacaoPPA->o08_concarpeculiar;
        $oDaoOrcDotacao->o58_localizadorgastos = $oDotacaoPPA->o08_localizadorgastos;
        $oDaoOrcDotacao->o58_instit            = $oDotacaoPPA->o08_instit;
        $oDaoOrcDotacao->incluir($iAnoIntegracao, $oDaoOrcDotacao->o58_coddot);
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
        $oDaoPPAIntegracaoDespesa->o121_anousu               = $iAnoIntegracao;
        $oDaoPPAIntegracaoDespesa->o121_coddot               = $oDaoOrcDotacao->o58_coddot;
        $oDaoPPAIntegracaoDespesa->o121_ppaintegracao        = $oDaoIntegracao->o123_sequencial;
        $oDaoPPAIntegracaoDespesa->o121_ppaestimativadespesa = $oDotacaoPPA->o07_sequencial;
        $oDaoPPAIntegracaoDespesa->incluir(null);
        if ($oDaoPPAIntegracaoDespesa->erro_status == 0) {
          
          $sErroMsg  = "Erro ao Incluir integração da Despesa com o ppa.\n";
          $sErroMsg .= $oDaoPPAIntegracaoDespesa->erro_msg; 
          $sErroMsg .= "Solicite Suporte\nErro Número 14";
          throw new Exception($sErroMsg, 14);
          
        }
        
        /**
         * incluimos o valor da suplementacao na tabela orcsuplemval, e excluimos o registro da tabela 
         * orcsuplemdespesappa
         */
        $oDaoSuplementacaoDespesa = db_utils::getDao("orcsuplemval");
        $oDaoSuplementacaoDespesa->o47_anousu = $iAnoIntegracao;
        $oDaoSuplementacaoDespesa->o47_coddot = $iCodigoDotacao;
        $oDaoSuplementacaoDespesa->o47_valor  = $oDotacaoPPA->o136_valor;
        $oDaoSuplementacaoDespesa->o47_codsup = $this->iCodigo;
        $oDaoSuplementacaoDespesa->incluir($this->iCodigo, $iAnoIntegracao, $iCodigoDotacao);
        if ($oDaoSuplementacaoDespesa->erro_status == 0) {
          throw new Exception("Erro ao vincular nova dotação a suplementação!\n{$oDaoSuplementacaoDespesa->erro_msg}");
        }
        
        $oDaoDotacoesPPA->excluir($oDotacaoPPA->o136_sequencial);
        if ($oDaoDotacoesPPA->erro_status == 0) {
          throw new Exception("Erro ao vincular nova dotação a suplementação!\n{$oDaoDotacoesPPA->erro_msg}");  
        }
      }
    }
    
    /**
     * verificamos as receitas de suplementações originadas do ppa
     */
    $oDaoReceitasPPA  = db_utils::getDao("orcsuplemreceitappa");
    $sWhere           = " o137_orcsuplem = {$this->iCodigo} ";
    $sWhere          .= " and c61_instit = ".db_getsession("DB_instit");
    $sSqlReceitasPPA  = $oDaoReceitasPPA->sql_query_receitappa(null, 
                                                               "o137_sequencial,
                                                               o137_valor,
                                                               c61_codigo,
                                                               ppaestimativareceita.*,
                                                               o06_sequencial", null, $sWhere);
    $rsReceitas       = $oDaoReceitasPPA->sql_record($sSqlReceitasPPA);
    $aReceitasPPA     = db_utils::getColectionByRecord($rsReceitas);
    $oDaoOrcReceita = db_utils::getDao("orcreceita");
    foreach ($aReceitasPPA as $oReceitaPPA) {

       $iReceita     = null;
       $sWhere       = "     o70_anousu = ".db_getsession("DB_anousu");
       $sWhere      .= " and o70_instit = ".db_getsession("DB_instit");
       $sWhere      .= " and o70_codfon = {$oReceitaPPA->o06_codrec}";
       $sWhere      .= " and o70_concarpeculiar = {$oReceitaPPA->o06_concarpeculiar}";
       $sSqlReceita  = $oDaoOrcReceita->sql_query_file(null,
                                                       null,
                                                       "*",
                                                       null,
                                                       $sWhere
                                                       );
       $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
       if ($oDaoOrcReceita->numrows == 1) {
         $iReceita = db_utils::fieldsMemory($rsReceita, 0)->o70_codrec;
       } else {
         
         /**
          * integra a receita com o orçamento
          */
         $oDaoIntegracao = db_utils::getDao("ppaintegracao");
    
         $oDaoIntegracao->o123_ano       = $iAnoIntegracao;
         $oDaoIntegracao->o123_data      = date("Y-m-d", db_getsession("DB_datausu"));
         $oDaoIntegracao->o123_idusuario = db_getsession("DB_id_usuario");
         $oDaoIntegracao->o123_situacao  = 1;
         $oDaoIntegracao->o123_instit    = db_getsession("DB_instit");
         $oDaoIntegracao->o123_tipointegracao = 2;
         $oDaoIntegracao->o123_ppaversao = $oReceitaPPA->o06_ppaversao;
         $oDaoIntegracao->incluir(null);
        
         $sWhere       = "     o70_anousu = ".(db_getsession("DB_anousu")-1);
         $sWhere      .= " and o70_instit = ".db_getsession("DB_instit");
         $sWhere      .= " and o70_codfon = {$oReceitaPPA->o06_codrec}";
         $sWhere      .= " and o70_concarpeculiar = '{$oReceitaPPA->o06_concarpeculiar}'";
         $sSqlReceita  = $oDaoOrcReceita->sql_query_file(null,
                                                       null,
                                                       "*",
                                                       null,
                                                       $sWhere
                                                       );
         $rsReceita    = $oDaoOrcReceita->sql_record($sSqlReceita);
         if ($oDaoOrcReceita->numrows == 1) {
  
           $oReceitaOrcamentaria = db_utils::fieldsMemory($rsReceita, 0);
           $iReceita = $oReceitaOrcamentaria->o70_codrec;
              
         }
         /**
         * Incluimos a receita 
         */
        $oDaoOrcReceita->o70_codrec         = $iReceita; 
        $oDaoOrcReceita->o70_anousu         = $iAnoIntegracao;
        $oDaoOrcReceita->o70_codigo         = $oReceitaPPA->c61_codigo;
        $oDaoOrcReceita->o70_codfon         = $oReceitaPPA->o06_codrec;
        $oDaoOrcReceita->o70_concarpeculiar = $oReceitaPPA->o06_concarpeculiar;
        $oDaoOrcReceita->o70_instit         = db_getsession("DB_instit");
        $oDaoOrcReceita->o70_datacriacao    = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoOrcReceita->o70_reclan         = "false";
        $oDaoOrcReceita->o70_valor          = "0";
        $oDaoOrcReceita->incluir($iAnoIntegracao, $iReceita);
        if ($oDaoOrcReceita->erro_status == 0) {
  
          $sErroMsg  = "Erro ao Incluir nova Receita.\n";
          $sErroMsg .= $oDaoOrcReceita->erro_banco; 
          $sErroMsg .= "Solicite Suporte\nErro Número 11";
          throw new Exception($sErroMsg, 11);
          
        }
        $iReceita = $oDaoOrcReceita->o70_codrec;
        /**
         * Incluimos a ligacao da receita gerada com a estimativa do ppa
         */ 
        $oDaoPPAIntegracaoReceita = db_utils::getDao("ppaintegracaoreceita");
        $oDaoPPAIntegracaoReceita->o122_anousu = $iAnoIntegracao;
        $oDaoPPAIntegracaoReceita->o122_codrec = $iReceita;
        $oDaoPPAIntegracaoReceita->o122_ppaintegracao        = $oDaoIntegracao->o123_sequencial;
        $oDaoPPAIntegracaoReceita->o122_ppaestimativareceita = $oReceitaPPA->o06_sequencial;
        $oDaoPPAIntegracaoReceita->incluir(null);
        if ($oDaoPPAIntegracaoReceita->erro_status == 0) {
          
          $sErroMsg  = "Erro ao Incluir integração da Receita com o ppa.\n";
          $sErroMsg .= $oDaoPPAIntegracaoReceita->erro_msg; 
          $sErroMsg .= "Solicite Suporte\nErro Número 12";
          throw new Exception($sErroMsg, 12);
          
        } 
      }
      /**
       * incluimos nas receitas da suplementação 
       */
      $oDaoReceitaSuplem = db_utils::getDao("orcsuplemrec");
      $oDaoReceitaSuplem->o85_anousu = $iAnoIntegracao;
      $oDaoReceitaSuplem->o85_codrec = $iReceita;
      $oDaoReceitaSuplem->o85_codsup = $this->getCodigo();
      $oDaoReceitaSuplem->o85_valor  = $oReceitaPPA->o137_valor;
      $oDaoReceitaSuplem->incluir($this->getCodigo(), $iReceita);
      if ($oDaoReceitaSuplem->erro_status == 0) {
        throw new Exception("Erro ao vincular nova receita a suplementação!\n{$oDaoReceitaSuplem->erro_msg}");
      }
      $oDaoReceitasPPA->excluir($oReceitaPPA->o137_sequencial);
      if ($oDaoReceitasPPA->erro_status == 0) {
        throw new Exception("Erro ao vincular nova receita a suplementação!\n{$oDaoReceitasPPA->erro_msg}");
      }
    }
  }
  
  public function processar($dtData) {
    
    /**
     * processa as desepsas/receitas que foram utilizadas no ppa no orçamento;
     */
    $this->processarDadosPPA();
    
    /**
     * a PL abaixo, valida se os valores de reduçao/suplementação estão corretos.
     */
    $iUsuario = db_getsession("DB_id_usuario");
    $sSqlValidaSuplementacao = "select fc_lancam_suplementacao({$this->iCodigo}, '{$dtData}', {$iUsuario}) as retorno";
    $rsValidaSuplementacao   = db_query($sSqlValidaSuplementacao);
    if (!$rsValidaSuplementacao) {
      throw new Exception("Erro ao validar dotações/receitas da suplementação!");
    }
    if (pg_num_rows($rsValidaSuplementacao) > 0) {

      $sRetorno = db_utils::fieldsMemory($rsValidaSuplementacao, 0)->retorno;
      if (substr($sRetorno, 0, 1) != '1') {
         throw new Exception($sRetorno);
      }
    }
    /**
     * calcula o valor das suplementações/reduções/receita de cada dotacao/receita
     */
    $sSqlLancamentos  = " select  codsup,o48_tiposup,tipo,dot,valor,o48_coddocsup ";
    /**
     * suplementacoes
     */ 
    $sSqlLancamentos .= "   from (select o47_codsup as codsup,'s'::char(1) as tipo, "; 
    $sSqlLancamentos .= "                o47_coddot as dot,  ";
    $sSqlLancamentos .= "                o47_valor as valor ";
    $sSqlLancamentos .= "           from orcsuplemval ";
    $sSqlLancamentos .= "          where o47_codsup={$this->getCodigo()} ";
    $sSqlLancamentos .= "            and o47_valor > 0 ";
    
    $sSqlLancamentos .= "union ";
    /**
     * reduções
     */
    $sSqlLancamentos .= "         select o47_codsup as codsup,  ";
    $sSqlLancamentos .= "                'r'::char(1) as tipo,";
    $sSqlLancamentos .= "                o47_coddot as dot, ";
    $sSqlLancamentos .= "                o47_valor*-1   as valor ";
    $sSqlLancamentos .= "           from orcsuplemval  ";
    $sSqlLancamentos .= "           where o47_codsup={$this->getCodigo()}  ";
    $sSqlLancamentos .= "             and o47_valor < 0 ";
     
    $sSqlLancamentos .= "union ";
    /**
     * receitas
     */
    $sSqlLancamentos .= "          select  o85_codsup as codsup,  ";
    $sSqlLancamentos .= "                  'rec'::char(3) as tipo, ";
    $sSqlLancamentos .= "                  o85_codrec as dot, ";
    $sSqlLancamentos .= "                  o85_valor   as valor ";
    $sSqlLancamentos .= "             from orcsuplemrec  ";
    $sSqlLancamentos .= "            where o85_codsup={$this->getCodigo()} ";
    $sSqlLancamentos .= "  ) as x ";
    $sSqlLancamentos .= " inner join orcsuplem on o46_codsup = codsup ";
    $sSqlLancamentos .= " inner join orcsuplemtipo on o46_tiposup =o48_tiposup ";
    $rsVerificaLancamentos = db_query($sSqlLancamentos);
    if (pg_num_rows($rsVerificaLancamentos) == 0) {
      throw new Exception("Erro ao consultar dados para iniciar lançamentos contábeis.");
    }
    $aLancamentos = db_utils::getColectionByRecord($rsVerificaLancamentos); 
    
    /**
     * iniciamos os lancamentos contábeis;
     */
         
    foreach ($aLancamentos as $oLancamentoSuplementacao) {


      $iCodigoDocumento = 0;
      $oTransacao       = new cl_translan;

      if ($oLancamentoSuplementacao->tipo == 'rec') {

         $sSqlValidaDotacao  = "select o70_instit "; 
         $sSqlValidaDotacao .= "   from orcreceita ";
         $sSqlValidaDotacao .= "  where o70_anousu = ".db_getsession("DB_anousu"); 
         $sSqlValidaDotacao .= "    and o70_codrec = {$oLancamentoSuplementacao->dot}";
         $rsDotacao        = db_query($sSqlValidaDotacao);
         if (pg_num_rows($rsDotacao) > 0) {
           $oDotacao     = db_utils::fieldsMemory($rsDotacao, 0);
         } else {
           throw new Exception("Dotacao {$oLancamentoSuplementacao->dot} não encontrada no orçamento.");
         }
        
        $oTransacao->db_trans_suplem(db_getsession("DB_anousu"),
                                    $oLancamentoSuplementacao->o48_tiposup,
                                    true,
                                    false, 
                                    $oDotacao->o58_instit);
        $iCodigoDocumento = $oTransacao->coddoc;
                                            
      } else {
        /**
	       * valida a dotação no orçamento 
	       */
	      $sSqlValidaDotacao  = "select o58_instit, ";
	      $sSqlValidaDotacao .= "      o58_valor  ";
	      $sSqlValidaDotacao .= "  from orcdotacao  ";
	      $sSqlValidaDotacao .= " where o58_anousu = ".db_getsession("DB_anousu"); 
	      $sSqlValidaDotacao .= "   and o58_coddot = {$oLancamentoSuplementacao->dot} ";
	      $rsDotacao        = db_query($sSqlValidaDotacao);
	      if (pg_num_rows($rsDotacao) > 0) {
	        $oDotacao     = db_utils::fieldsMemory($rsDotacao, 0);
	      } else {
	        throw new Exception("Dotacao {$oLancamentoSuplementacao->dot} não encontrada no orçamento.");
	      }      	
      	
        $lSuplementacaoEspecial = false;
        /**
         * verifica se a suplementacao é especial.(dotacao com valor = 0 e já existe uma suplmentacao para essa mesma
         * dotação)
         */
        if ($oDotacao->o58_valor == 0) {
          
          $sSqlOutrasSuplementacoes  = "select 1 "; 
          $sSqlOutrasSuplementacoes .= "  from orcsuplemval ";
          $sSqlOutrasSuplementacoes .= "  left join orcsuplemlan on o47_codsup = o49_codsup ";
          $sSqlOutrasSuplementacoes .= " where o47_coddot = {$oLancamentoSuplementacao->dot}";   
          $sSqlOutrasSuplementacoes .= "   and o47_codsup <> {$this->getCodigo()}";
          $sSqlOutrasSuplementacoes .= "   and o49_codsup is null";
          $rsOUtrasSuplementacoes    = db_query($sSqlOutrasSuplementacoes);
          if (pg_num_rows($rsOUtrasSuplementacoes) > 0) {
            $lSuplementacaoEspecial = true;   
          }
        }
        if ($oLancamentoSuplementacao->tipo == "s") { //suplementacao
          $oTransacao->db_trans_suplem(db_getsession("DB_anousu"), 
                                       $oLancamentoSuplementacao->o48_tiposup,
                                       false, 
                                       $lSuplementacaoEspecial, 
                                       $oDotacao->o58_instit);
        } else { // reducao ou receita
          $oTransacao->db_trans_suplem(db_getsession("DB_anousu"), 
                                       $oLancamentoSuplementacao->o48_tiposup,
                                       true,
                                       $lSuplementacaoEspecial,
                                       $oDotacao->o58_instit);
        }
        $iCodigoDocumento = $oTransacao->coddoc;
      }
      $oLancamento = new lancamentoContabil($iCodigoDocumento,
                                            db_getsession("DB_anousu"),
                                            $dtData, 
                                            $oLancamentoSuplementacao->valor
                                            );
      $oLancamento->setCodigoSuplementacao($this->iCodigo);
      if ($oLancamentoSuplementacao->tipo == 'rec') {
        
        $oLancamento->setReceita($oLancamentoSuplementacao->dot);                                              
      } else {
        $oLancamento->setDotacao($oLancamentoSuplementacao->dot);
      }
      $oLancamento->setDadosTransacao($oTransacao);
      $oLancamento->salvar();
      /**
       * Validamos o saldo final da dotação.
       */
       if ($oLancamentoSuplementacao->tipo != "rec") {

         $iAnoUsu  = db_getsession("DB_anousu");
         $oDotacao = new Dotacao($oLancamentoSuplementacao->dot, $iAnoUsu );
         $dtini = $iAnoUsu.'-01-01';
         $dtfim = $iAnoUsu.'-12-31';
          
         if ($oDotacao->getSaldoAtual() < 0) {

           $sMessage  = "Suplementação não Processada.\n";
           $sMessage .= "Dotação {$oLancamentoSuplementacao->dot} ficará com saldo negativo!";
           throw new Exception($sMessage);
        }
      }   
    }
  }
}

?>