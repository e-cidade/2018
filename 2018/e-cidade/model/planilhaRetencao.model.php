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

require_once("model/issqn/NotaPlanilhaRetencao.model.php");

/**
 * @author dbseller
 *
 */
class planilhaRetencao {
  
  protected $iCodigoPlanilha = null;
  
  protected $iAnoUsu         = null;
  protected $iMes            = null;
  protected $dtDatausu       = null;
  
  protected $nValorTotal     = 0;


  protected $iNotaLiquidacao = null;
  protected $iNumpre         = null;  

  /**
   * iMesCompetencia 
   * 
   * @var float
   * @access protected
   */
  protected $iMesCompetencia = 0;
  
  protected $iInscricao      = 0;
  
  protected $iNumCgm         = null;

  protected $aNotas          = array();
           
  /**
   * Adiciona uma planilha
   */
  function __construct($iCodigoPlanilha = null, $iNumCgm = null, $iAnoUsu = null, $iMesUsu = null, $iInscricao = null) {
    
    $this->dtDatausu = date("Y-m-d", db_getsession("DB_datausu"));
    $this->iAnoUsu   = !empty($iAnoUsu) ? $iAnoUsu : db_getsession("DB_anousu");
    $this->iMes      = !empty($iMesUsu) ? $iMesUsu : date("m", db_getsession("DB_datausu"));
    $this->iInscricao= $iInscricao;
    
    if (empty($iCodigoPlanilha)) {
      
      if (!db_utils::inTransaction()) {
        throw new Exception("Erro [0] - Não Existe transação ativa");
      }
      
      if (empty($iNumCgm)){
        throw new Exception("Erro [1] - Código do fornecedor não informado.");
      }

      $this->iNumCgm               = $iNumCgm;
      $oDaoIssPlan                 = db_utils::getDao("issplan");
      $oDaoIssPlan->q20_ano        = $this->iAnoUsu;
      $oDaoIssPlan->q20_mes        = $this->iMes;
      $oDaoIssPlan->q20_numbco     = 0;
      $oDaoIssPlan->q20_numpre     = 0;
      $oDaoIssPlan->q20_fonecontri = "";
      $oDaoIssPlan->q20_numcgm     = $this->iNumCgm;
      $oDaoIssPlan->q20_nomecontri = "";
      $oDaoIssPlan->q20_situacao   = 1;
      $oDaoIssPlan->incluir(null);

      if ($oDaoIssPlan->erro_status == 0) {
        throw new Exception("Erro [2] - Não foi possível incluir planilha.\n{$oDaoIssPlan->erro_msg}");
      }

      $this->iCodigoPlanilha = $oDaoIssPlan->q20_planilha;
      
      if ( !empty($iInscricao) ) {
        
        $oDaoIssPlanInscri                 = db_utils::getDao("issplaninscr");
        $oDaoIssPlanInscri->q24_planilha   = $this->iCodigoPlanilha;
        $oDaoIssPlanInscri->q24_inscr      = $iInscricao;
        $oDaoIssPlanInscri->incluir(null);
        
        if ($oDaoIssPlanInscri->erro_status == 0) {
          throw new Exception("Erro [3] - Nao foi possivel vincular a planilha a inscricao.\n{$oDaoIssPlanInscri->erro_msg}");
        }
      }
    } else {
      
      $this->iCodigoPlanilha = $iCodigoPlanilha;
      
      //Valores das Notas para Chegar ao valor total da planilha
      $oDaoIssPlanIt     = db_utils::getDao("issplanit");
      $oDaoIssPlan       = db_utils::getDao("issplan");
      $sSqlNotasPlanilha = $oDaoIssPlanIt->sql_query_file(null, "q21_sequencial", null, "q21_planilha = {$iCodigoPlanilha}");
      $sSqlDadosPlanilha = $oDaoIssPlan->sql_query_issplaninscr($iCodigoPlanilha); 
      
      $rsDadosPlanilha   = $oDaoIssPlan->sql_record($sSqlDadosPlanilha);
      
      if ( !$rsDadosPlanilha ) {
        throw new DBException("Erro ao buscar os dados da planilha");
      }
      
      $oDadosPlanilha = db_utils::fieldsMemory($rsDadosPlanilha, 0);

      $this->iNumCgm         = $oDadosPlanilha->q20_numcgm;
      $this->iNumpre         = $oDadosPlanilha->q20_numpre;
      $this->iMesCompetencia = $oDadosPlanilha->q20_mes;
      $this->iInscricao      = empty($oDadosPlanilha->q24_inscr) ? null : $oDadosPlanilha->q24_inscr;
      
      $rsNotasPlanilha   = db_query($sSqlNotasPlanilha);
      
      if ( !$rsNotasPlanilha ) { 
        throw new DBException( "Erro ao Buscar Notas da Planilha" . pg_last_error() );
      }
      
      foreach ( db_utils::getCollectionByRecord($rsNotasPlanilha) as $oDadosPlanilha ) {
        
        $oNota              = new NotaPlanilhaRetencao( $oDadosPlanilha->q21_sequencial );
        $this->aNotas[]     = $oNota;
        $this->nValorTotal += $oNota->getValorImposto();
      }
    }
  }
  /**
   * @return string
   */
  public function getDatausu() {

    return $this->dtDatausu;
  }
  
  /**
   * @param string $dtDatausu
   */
  public function setDatausu($dtDatausu) {

    $this->dtDatausu = $dtDatausu;
  }

  /**
   * Adiciona uma Nota a planilha
   *
   * @param  object $oNota Objeto com os dados da nota
   * @return void
   * @deprecated
   * @see -  planilhaRetencao::adicionarNota();
   */
  function adicionaNota($oNota) {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não Existe transação ativa");
    }
    if (!is_object($oNota)) {
      throw new Exception("Erro [1] - oNota deve ser um objeto");
    }
    $oDaoNotas = db_utils::getDao("issplanit");
   // $oDaoNotas = new cl_issplanit();//db_utils::getDao("issplanit");
    $oDaoNotas->q21_planilha = $this->iCodigoPlanilha;
    $oDaoNotas->q21_dataop   = $this->getDatausu();
    $oDaoNotas->q21_horaop   = db_hora();
    $oDaoNotas->q21_tipolanc = 1;
    $oDaoNotas->q21_nome     = "";
    $oDaoNotas->q21_retido   = "true";
    $oDaoNotas->q21_status   = 1;
    $oDaoNotas->q21_situacao = "0";
    /*
     * informações que devem vir do objeto oNotas
     */
    $oDaoNotas->q21_datanota     = $oNota->dtNota;
    $oDaoNotas->q21_cnpj         = $oNota->sCnpj;
    $oDaoNotas->q21_serie        = "";
    $oDaoNotas->q21_nome         = substr($oNota->sNome,0,40);
    $oDaoNotas->q21_nota         = $oNota->sNumeroNota;
    $oDaoNotas->q21_valorser     = $oNota->nValor;
    $oDaoNotas->q21_valor        = $oNota->nValorTotalRetencao;
    $oDaoNotas->q21_aliq         = $oNota->nAliquota;
    $oDaoNotas->q21_valordeducao = "{$oNota->nValorDeducao}";
    $oDaoNotas->q21_valorbase    = $oNota->nValorBase;
    $oDaoNotas->q21_valorimposto = $oNota->nValorTotalRetencao;
    $oDaoNotas->q21_servico      = "Recolhimento de retencao";
    $oDaoNotas->q21_obs          = "";
    $oDaoNotas->incluir(null);
    if ($oDaoNotas->erro_status == 0) {
      throw new Exception("Erro [2]- Erro ao incluir nota na planilha.\n{$oDaoNotas->erro_msg}"); 
    }
    
    if (isset($oNota->iNotaLiquidacao) && !empty($oNota->iNotaLiquidacao))  {
      
      $this->iNotaLiquidacao = $oNota->iNotaLiquidacao;
      $oDaoIssPlanOp = db_utils::getDao("issplanitop");
      $oDaoIssPlanOp->q96_issplanit = $oDaoNotas->q21_sequencial;
      $oDaoIssPlanOp->q96_pagordem  = $oNota->iNotaLiquidacao;
      $oDaoIssPlanOp->incluir(null);
      if ($oDaoIssPlanOp->erro_status == 0) {
        throw new Exception("Erro [3]- Erro ao incluir nota na planilha."); 
      }
    }
    $this->nValorTotal += $oNota->nValorTotalRetencao;
    return true;
  }
  
  function gerarDebito($sHistorico=null) {

    if (!db_utils::inTransaction()) {
      throw new Exception("Erro [0] - Não Existe transação ativa");
    }
    //Criamos um novo Numpre 
    $rsNumpre      = db_query("select nextval('numpref_k03_numpre_seq') as k03_numpre");
    $this->iNumpre = db_utils::fieldsMemory($rsNumpre, 0)->k03_numpre;
    /*
     *Buscamos as informações de configuração da db_confplam 
     */
    $oDaoConfPlan = db_utils::getDao("db_confplan");
    $rsConfPlan   = $oDaoConfPlan->sql_record($oDaoConfPlan->sql_query_file());
    if ($oDaoConfPlan->numrows == 0) {

      $sErro  = "Erro [1] - Não há configurações informadas para a planilha.";
      $sErro .= "\nConfigure acessando  Prefeitura Online -> Procedimentos -> Manutenção de Planilhas.";
      throw new Exception($sErro);

    }
    $oConfPlanilhas = db_utils::fieldsMemory($rsConfPlan, 0);
    /**
     * Alteramos a planilha , informado o numpre gerado
     */
    $oDaoIssPlan   = db_utils::getDao("issplan");
    $oDaoIssPlan->q20_numpre   = $this->iNumpre;
    $oDaoIssPlan->q20_planilha = $this->iCodigoPlanilha;
    $oDaoIssPlan->q20_situacao = 3;
    $oDaoIssPlan->alterar($this->iCodigoPlanilha);

    /*
     * incluimos um issvariavel para o mes.
     */

    $oDaoIssVar = db_utils::getDao("issvar");
    $oDaoIssVar->q05_numpre = $this->iNumpre;
    $oDaoIssVar->q05_histor = "ISSQN retenção na fonte.";
    $oDaoIssVar->q05_numpar = 1;
    $oDaoIssVar->q05_ano    = $this->iAnoUsu;
    $oDaoIssVar->q05_mes    = $this->iMes;
    $oDaoIssVar->q05_valor  = $this->nValorTotal;
    $oDaoIssVar->q05_aliq   = "0"; 
    $oDaoIssVar->q05_bruto  = "0"; 
    $oDaoIssVar->q05_vlrinf = "0";
    $oDaoIssVar->incluir(null);
    if ($oDaoIssVar->erro_status == 0 ) {
      throw new Exception("Erro [2] - Não foi possivel incluir issqn Variavel.");
    }

    /**
     * Incluimos o débito no arrecad
     */
    $oDaoArrecad  = db_utils::getDao("arrecad");
    $oDaoArrecad->k00_dtoper = $this->getDatausu();
    $oDaoArrecad->k00_dtvenc = $this->getDatausu();
    $oDaoArrecad->k00_hist   = $oConfPlanilhas->w10_hist;
    $oDaoArrecad->k00_receit = $oConfPlanilhas->w10_receit;
    $oDaoArrecad->k00_numcgm = $this->iNumCgm;
    $oDaoArrecad->k00_numdig = "0";
    $oDaoArrecad->k00_numpar = "1";
    $oDaoArrecad->k00_numpre = $this->iNumpre;
    $oDaoArrecad->k00_numtot = 1;
    $oDaoArrecad->k00_tipo   = $oConfPlanilhas->w10_tipo;
    $oDaoArrecad->k00_tipojm = "0";
    $oDaoArrecad->k00_valor  = $this->nValorTotal;
    $oDaoArrecad->incluir();
    if ( $oDaoArrecad->erro_status == "0")  {
      throw new Exception("Erro [3] - Não Foi possível incluir débito | " . $oDaoArrecad->erro_msg);
    }
    
    if ( !empty($this->iInscricao) ) {
      
      $oDaoArreinscr = db_utils::getDao("arreinscr");
      $oDaoArreinscr->k00_numpre = $this->iNumpre;
      $oDaoArreinscr->k00_inscr  = $this->iInscricao;
      $oDaoArreinscr->k00_perc   = 100;
      $oDaoArreinscr->excluir($this->iNumpre,$this->iInscricao);
      $oDaoArreinscr->k00_perc   = 100;
      $oDaoArreinscr->incluir($this->iNumpre,$this->iInscricao);
      
      if ( $oDaoArreinscr->erro_status == "0" ) {
        throw new Exception("Erro [3.1] - Não Foi possível vincular débito a inscricao | " . $oDaoArreinscr->erro_msg);
      }
    }
    

    /**
     * Incluimos o Historico, caso nao seje nulo
     */
    if (!empty($sHistorico)) {
      
      $oDaoHistorico                 = db_utils::getDao("arrehist");
      $oDaoHistorico->k00_numpre     = $this->iNumpre;    
      $oDaoHistorico->k00_numpar     = "0";   
      $oDaoHistorico->k00_hist       = 502;    
      $oDaoHistorico->k00_dtoper     = $this->dtDatausu; 
      $oDaoHistorico->k00_hora       = date("H:i");
      $oDaoHistorico->k00_id_usuario = db_getsession("DB_id_usuario");
      $oDaoHistorico->k00_histtxt    = $sHistorico;  
      $oDaoHistorico->k00_limithist  = null;
      $oDaoHistorico->incluir(null);
      if ($oDaoHistorico->erro_status == "0") {
        throw new DBException("Erro [3.5] - Não Foi possível incluir Histórico do Recibo" . pg_last_error());
      }
    }
    /**
     * Incluimos na tabela issplannumpre - Ligação do numpre da planilha com o numpre;
     */
    $oDaoIssPlanNumpre = db_utils::getDao("issplannumpre");
    $oDaoIssPlanNumpre->q32_planilha = $this->iCodigoPlanilha;
    $oDaoIssPlanNumpre->q32_numpre   = $this->iNumpre;
    $oDaoIssPlanNumpre->q32_dataop   = $this->getDatausu();
    $oDaoIssPlanNumpre->q32_horaop   = db_hora();
    $oDaoIssPlanNumpre->q32_status   = 1 ;
    $oDaoIssPlanNumpre->incluir(null);
    if ($oDaoIssPlanNumpre->erro_status == 0) {
      throw new Exception("Erro [4] - Não Foi possível vincular débito com a Planilha");
    }

    /**
     * Selecionamos todos as notas cadastradas para a planilha , e 
     * vinculamos ao numpre
     */
    $oDaoIssplanIt     = db_utils::getDao("issplanit");
    $sSqlNotasPlanilha = $oDaoIssplanIt->sql_query_file(null,"*",
        null,
        "q21_planilha = {$this->iCodigoPlanilha}
        and q21_status = 1"
        );
    $rsNotasPlanilha = $oDaoIssplanIt->sql_record($sSqlNotasPlanilha);
    for ($i = 0; $i < $oDaoIssplanIt->numrows; $i++) {

      $oNotaPlanilha  = db_utils::fieldsMemory($rsNotasPlanilha, $i);  
      $oDaoNotaNumpre = db_utils::getDao("issplannumpreissplanit");
      $oDaoNotaNumpre->q77_issplanit     = $oNotaPlanilha->q21_sequencial;
      $oDaoNotaNumpre->q77_issplannumpre = $oDaoIssPlanNumpre-> q32_sequencial;
      $oDaoNotaNumpre->incluir(null);
      if ($oDaoNotaNumpre->erro_status == 0) {
        throw new Exception("Erro [5] - Não Foi possível incluir débito");
      }
    }

    /**
     * vinculamos o numpre a nota de liquidação
     */
    if ($this->iNotaLiquidacao != null) {

      $oDaoCaiRetOrdem = db_utils::getDao("cairetordem");
      $oDaoCaiRetOrdem->k32_numpre = $this->iNumpre;
      $oDaoCaiRetOrdem->k32_ordpag = $this->iNotaLiquidacao;
      $oDaoCaiRetOrdem->incluir(null);
      if ($oDaoCaiRetOrdem->erro_status == 0){
        throw new Exception("Erro [6] - Não Foi possível incluir débito");
      }
    }
    return $this->iNumpre;
  }
  /**
   * Retorna o Numpre gerado pelo metodo gerarDebito;
   *
   * @return unknown
   */
  function getNumpre() {
    return $this->iNumpre; 
  }
  
  function anularPlanilha($sMotivo){
  
    require_once("libs/db_sql.php");
    $clissplananula = db_utils::getDao("issplananula");
    $clissplan      = db_utils::getDao("issplan");
    $clarrecad      = db_utils::getDao("arrecad");
    $clcancdebitos  = db_utils::getDao("cancdebitos");
    $planilha       = $this->iCodigoPlanilha;
    $data = date("Y-m-d");
    $hora = date("H:i"); 
    $ip   = db_getsession("DB_ip"); 
    $usuario = db_getsession("DB_id_usuario");
    if($usuario == ""){
      $usuario = 1;
    }
    $sql = "select * from issplan where q20_planilha = $planilha";
    $result = db_query($sql);
    $linhas = pg_num_rows($result);
    if($linhas > 0){
      $q20_numpre = pg_result($result,0,"q20_numpre");
    } else {
      $q20_numpre = "";
    }
    
    $sqlerro = false;
    
    //gravar na issplananula: os dados da anulação
    $clissplananula->q76_planilha   = $planilha;
    $clissplananula->q76_data       = $data;
    $clissplananula->q76_hora       = $hora;
    $clissplananula->q76_motivo     = $sMotivo ;
    $clissplananula->q76_ip         = $ip;
    $clissplananula->q76_id_usuario = $usuario;
    $clissplananula->incluir(null);
    if ($clissplananula->erro_status == 0) {
      $sqlerro = true;
      //die($clissplananula->erro_sql);
      throw new Exception("Erro [1] - {$clissplananula->erro_msg}");
    }
   
    //alterar a situação da issplan para anulada
    $clissplan->q20_planilha = $planilha;
    $clissplan->q20_situacao = 5;
    $clissplan->alterar($planilha);
    if ($clissplan->erro_status == 0) {
      $sqlerro = true;
      throw new Exception("Erro [2] - {$clissplan->erro_msg}");
    }
    if($q20_numpre > 0){
      //gravar na cancdebitos, cancdebitosreg, cancdebitosproc, cancdebitosprocreg
      $sqltipo = "select w10_tipo from db_confplan ";
      $resulttipo = db_query($sqltipo);
      $linhastipo = pg_num_rows($resulttipo);
      if($linhastipo > 0){
        //db_fieldsmemory($resulttipo,0);
        $w10_tipo = pg_result($resulttipo,0,"w10_tipo");
      }else{
        $sqlerro = true;
        throw new Exception("Deve-se configurar a planilha (db_confplan)");
      }
      $clcancdebitos->k20_descr   = "anulação de planilha no dbpref.";
      $clcancdebitos->k20_hora    = $hora;
      $clcancdebitos->k20_data    = $data;
      $clcancdebitos->k20_usuario = $usuario;
      $clcancdebitos->k20_instit  = db_getsession("DB_instit");
      $clcancdebitos->k20_cancdebitostipo = 1;
      $clcancdebitos->numpre              = $q20_numpre;
      $clcancdebitos->numpar              = 1;
      $clcancdebitos->k21_obs     = $sMotivo;
      $clcancdebitos->usuario     = $usuario;
      $clcancdebitos->tipo        = $w10_tipo;
      $clcancdebitos->planilha    = $planilha;
      $clcancdebitos->incluir_cancelamento(true);
      if ($clcancdebitos->erro_status == "0") {

        $sqlerro = true;
          throw new Exception("Erro [3] - {$clcancdebitos->erro_msg}");
        
      }
    
      //grava na cancdebitosissplan
      
      //deletar da arrecad e gravar na arrecant
      $clarrecad->excluir_arrecad_inc_arrecant($q20_numpre,1, true);
      if ($clarrecad->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = "Erro [4] - {$clarrecad->erro_msg}";
      }
    }
    return $sqlerro;
  }
 
  /**
   * Adiciona nota
   * @param NotaPlanilhaRetencao - Nota a ser adicionada.
   */
  public function adicionarNota(NotaPlanilhaRetencao $oNota) {
    
    $this->nValorTotal += $oNota->salvar();
    $this->aNotas[]     = $oNota;
    
    return $oNota->getCodigoNotaPlanilha();
  }
  
  /**
   * Retorna o Codigo da Planilha Gerada
   * @access public
   * @return void
   */
  public function getCodigoPlanilha() {
    return $this->iCodigoPlanilha;
  }

  /**
   * getDataPlanilha 
   * 
   * @access public
   * @return void
   */
  public function getDataPlanilha() {
    return $this->dtDatausu;
  }
  
  /**
   * getInscricao 
   * 
   * @access public
   * @return void
   */
  public function getInscricao() {
    return $this->iInscricao;
  }
  
  
  /**
   * getNumeroCGM 
   * 
   * @access public
   * @return void
   */
  public function getNumeroCGM() {
    return $this->iNumCgm;
  }
}