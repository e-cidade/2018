<?
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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_gerfcom_classe.php");
require_once("classes/db_rhslipfolha_classe.php");

db_app::import('exceptions.*');
db_app::import('pessoal.dadosEmpenhoFolha');
db_app::import('pessoal.dadosEmpenhoFolhaRescisao');
db_app::import('slipFolha');
db_app::import("contabilidade.*");
db_app::import("CgmFactory");
db_app::import("CgmBase");
db_app::import("CgmJuridico");
db_app::import("CgmFisico");
db_app::import("Dotacao");
db_app::import('empenho.*');
db_app::import('empenhoFolha');


$oPost = db_utils::postMemory($_POST);

$oJson              = new services_json();
$clgerfcom          = new cl_gerfcom;
$oDadosEmpenhoFolha = new dadosEmpenhoFolha();
$oSlipFolha         = new slipFolha();
$clrhslipfolha      = new cl_rhslipfolha(); 

$lErro    = false;
$sMsgErro = '';

try {
  
  switch ($oPost->sMethod) {
    
    case 'consultaPontoComplementar':

      $oInstituicao   = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
      $aListaSemestre = array();

      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

        $oDaoHistorico = new cl_rhhistoricocalculo;

        $aWhere[] = "rh141_anousu    = {$oPost->iAnoFolha}";
        $aWhere[] = "rh141_mesusu    = {$oPost->iMesFolha}";
        $aWhere[] = "rh141_tipofolha = 3";

        $sWhere = implode(' AND ', $aWhere);
        $sCampo = 'DISTINCT rh141_codigo AS semestre';

        $sSql        = $oDaoHistorico->sql_query(null, $sCampo, null, $sWhere);
        $rsHistorico = $oDaoHistorico->sql_record($sSql);

        if ($oDaoHistorico->numrows) {
          $aListaSemestre = db_utils::getCollectionByRecord($rsHistorico);
        }
      } else {

        $sWhere  = "     r48_anousu = {$oPost->iAnoFolha}";
        $sWhere .= " and r48_mesusu = {$oPost->iMesFolha}";
        $sWhere .= " and r48_instit = {$oInstituicao->getCodigo()}";

        if(isset($oPost->lNaoExibeComplementarZero) and $oPost->lNaoExibeComplementarZero) {
          $sWhere .= " and r48_semest <> 0 ";
        }

        $sSqlPontoComplementar = $clgerfcom->sql_query_file(null,
                                                            null,
                                                            null,
                                                            null,
                                                            "distinct r48_semest as semestre",
                                                            "r48_semest",
                                                            $sWhere);



        $rsPontoComplementar  = $clgerfcom->sql_record($sSqlPontoComplementar);

        if ( $clgerfcom->numrows > 0 ) {
          $aListaSemestre = db_utils::getCollectionByRecord($rsPontoComplementar);
        }
      }
  	  
      $aRetorno = array("lErro"     =>false,
  	                    "aSemestre" =>$aListaSemestre);
    break;

    case 'consultaPontoSuplementar':
      
      $oDaoHistorico = new cl_rhhistoricocalculo;

      $aWhere[] = "rh141_anousu    = {$oPost->iAnoFolha}";
      $aWhere[] = "rh141_mesusu    = {$oPost->iMesFolha}";
      $aWhere[] = "rh141_tipofolha = 6";

      $sWhere = implode(' AND ', $aWhere);
      $sCampo = 'DISTINCT rh141_codigo AS semestre';

      $sSql        = $oDaoHistorico->sql_query(null, $sCampo, null, $sWhere);
      $rsHistorico = $oDaoHistorico->sql_record($sSql);

      $aSemestre = array();
      if ($oDaoHistorico->numrows) {
        $aSemestre =  db_utils::getCollectionByRecord($rsHistorico);
      }
      
      $aRetorno = array(
        'lErro'     => false,
        'aSemestre' => $aSemestre
      );
      break;

    /**
     * Retorna todas as folhas complementares fechadas que não esteja empenhadas.
     * 
     * @param Integer $iAnoFolha;
     * @param Integer $iMesFolha
     */  
    case 'consultaComplementaresFechadas':
     
      $aFolhas      = array();
      $iAnoUsu      = $oPost->iAnoFolha;
      $iMesUsu      = $oPost->iMesFolha;
      $oCompetencia = new DBCompetencia($iAnoUsu, $iMesUsu);
      
      $aFolhaComplementares = FolhaPagamentoComplementar::getFolhasFechadasCompetencia($oCompetencia);
      
      foreach ($aFolhaComplementares as $oFolhaComplementar) {
        
        try {
          
          $oFolhaComplementar->verificarEmpenho();
          $aFolhas[] = $oFolhaComplementar->getNumero();
          
        } catch (Exception $ex) { }
      }
      
      $aRetorno = array("lErro"     =>false,
                        "aSemestre" =>$aFolhas);
      
    break;  
    case 'gerarEmpenhos':
    
      if ( isset($oPost->sSemestre)) {
        $sSemestre = $oPost->sSemestre;
        
        if ($sSemestre == 0) {
        	throw new Exception('Complementar em aberto. Execute o fechamento.');
        }
      } else {
        $sSemestre = '';
      }
      if ($oPost->sSigla == 'r20' && $oPost->iTipo == 1) {
      
        $oDadosEmpenhoFolha  = new dadosEmpenhoFolhaRescisao;
        $sSemestre           = explode(",", $oPost->sRescisoes);
      // include_once "pes4_acertaempenhospcasp.RPC.php";
      }
      
      db_inicio_transacao();
      
      $oDadosEmpenhoFolha->excluiDadosFolha($oPost->sSigla,
  	  			                                $oPost->iAnoFolha,
  	  			                                $oPost->iMesFolha,
  	  			                                db_getsession('DB_instit'),
  	  			                                $sSemestre);
  	  $oDadosEmpenhoFolha->geraDadosEmpenhosFolha($oPost->sSigla,
  	  				                                    $oPost->iAnoFolha,
  	  				                                    $oPost->iMesFolha,
  	  				                                    db_getsession('DB_instit'),
  	  				                                    $sSemestre);

      $oDaoEmpenhoElementoPCASP = db_utils::getDao('rhempenhoelementopcasp', true);
  	  				                                  
      include_once "pes4_acertaempenhospcasp.RPC.php";
  
  	  db_fim_transacao(false);
  	   				                                    
  	  $aRetorno = array( "lErro"=>false,
                         "sMsg" =>urlencode($sMsgErro));
      
      
    break;
    case 'consultarEmpenhos':
     
      if ( isset($oPost->sSemestre)) {
        $sSemestre = $oPost->sSemestre;
      } else {
        $sSemestre = '';
      }
      
      $aListaEmpenhos = array();
      if ($oPost->sSigla == 'r20'&& $oPost->iTipo == 1) {
        $oDadosEmpenhoFolha  = new dadosEmpenhoFolhaRescisao;
      }
      
      if ($oPost->sSigla == 'r20' && $oPost->iTipo == 1) {
          
          $aRescisoes = explode(",", $oPost->sRescisoes);
          $aListaEmpenhos = $oDadosEmpenhoFolha->getDadosEmpenhosFolha($oPost->sSigla,
                                                                     $oPost->iAnoFolha,
                                                                     $oPost->iMesFolha,
                                                                     db_getsession('DB_instit'),
                                                                     $aRescisoes
                                                                     );
      } else {
         $aListaEmpenhos = $oDadosEmpenhoFolha->getDadosEmpenhosFolha($oPost->sSigla,
  	  												                                       $oPost->iAnoFolha,
  	  												                                       $oPost->iMesFolha,
  	  												                                       db_getsession('DB_instit'),
  	  												                                       $sSemestre);
      }
        
      if ( empty($aListaEmpenhos) ) {
      	$lExiste = false;
      } else {
      	$lExiste = true;
      }
      
      $aRetorno = array( "lErro"  =>false,
                         "lExiste"=>$lExiste);  	
      
    break;
    case 'gerarEmpenhosFGTS':
     
      db_inicio_transacao();
      
      $oDadosEmpenhoFolha->excluiDadosEmpenhosFGTS($oPost->sTipo,
                                                   $oPost->iAnoFolha,
                                                   $oPost->iMesFolha,
                                                   db_getsession('DB_instit'));    
      
      $oDadosEmpenhoFolha->geraDadosEmpenhosFGTS($oPost->sTipo,
                                                 $oPost->iAnoFolha,
                                                 $oPost->iMesFolha,
                                                 db_getsession('DB_instit'));
  
      db_fim_transacao(false);
                                                     
      $aRetorno = array( "lErro"=>false,
                         "sMsg" =>urlencode($sMsgErro));
      
    break;
    case 'consultarEmpenhosFGTS':
     
      $aListaEmpenhos = array();
      
      $aListaEmpenhos = $oDadosEmpenhoFolha->getRubricasEmpenhosFGTS($oPost->sTipo,
                                                                     $oPost->iAnoFolha,
                                                                     $oPost->iMesFolha,
                                                                     db_getsession('DB_instit'));
      if ( empty($aListaEmpenhos) ) {
        $lExiste = false;
      } else {
        $lExiste = true;
      }
      
      $aRetorno = array( "lErro"  =>false,
                         "lExiste"=>$lExiste);    
      
    break;
    case 'gerarEmpenhosPrev':
     
  	  $aListaPrev = explode(",",$oPost->sPrev);
  	  $sListaPrev = "'".implode("','", $aListaPrev)."'";
      
  	  if ( count($aListaPrev) > 1 ) {
  	  	
  	  	$sSqlPercPatro  = "  select distinct r33_ppatro              ";
  	  	$sSqlPercPatro .= "    from inssirf                          ";
  	  	$sSqlPercPatro .= "   where r33_codtab in ({$sListaPrev})  ";
  	  	$sSqlPercPatro .= "     and r33_anousu = {$oPost->iAnoFolha} ";
  	  	$sSqlPercPatro .= "     and r33_mesusu = {$oPost->iMesFolha} ";
  	  	$sSqlPercPatro .= "     and r33_instit = ".db_getsession('DB_instit');
      
        $rsPercPatro      = db_query($sSqlPercPatro);
        $iLinhasPercPatro = pg_num_rows($rsPercPatro);
        
  	  }
      
  	  $aNovaListaPrev = array();
  	  
      foreach ($aListaPrev as $sPrev) {
      	$aNovaListaPrev[] = $sPrev-2;
      }
      
      $sListaPrev = implode(",",$aNovaListaPrev);
      
      db_inicio_transacao();
      
  	  $oDadosEmpenhoFolha->excluiDadosEmpenhosPrev($oPost->sTipo,
  	                                               $oPost->iAnoFolha,
  	                                               $oPost->iMesFolha,
  	                                               $sListaPrev,
  	                                               db_getsession('DB_instit'));    
  	  
  	  $oDadosEmpenhoFolha->geraDadosEmpenhosPrev($oPost->sTipo,
  	                                             $oPost->iAnoFolha,
  	                                             $oPost->iMesFolha,
  	                                             $sListaPrev,
  	                                             db_getsession('DB_instit'));

  	  db_fim_transacao(false);
  	                                             
  	  $aRetorno = array( "lErro"=>false,
                         "sMsg" =>urlencode($sMsgErro));
      
    break;
    case 'consultarEmpenhosPrev':
     
      $aListaPrev = explode(",",$oPost->sPrev);
      if ( count($aListaPrev) > 1 ) {
      
      	$sListaPrevPatro = implode("','",$aListaPrev);
      	
        $sSqlPercPatro   = "  select distinct r33_ppatro                 ";
        $sSqlPercPatro  .= "    from inssirf                             ";
        $sSqlPercPatro  .= "   where r33_codtab in ('{$sListaPrevPatro}')";
        $sSqlPercPatro  .= "     and r33_anousu = {$oPost->iAnoFolha}    ";
        $sSqlPercPatro  .= "     and r33_mesusu = {$oPost->iMesFolha}    ";
        $sSqlPercPatro  .= "     and r33_instit = ".db_getsession('DB_instit');
      
        $rsPercPatro      = db_query($sSqlPercPatro);
        $iLinhasPercPatro = pg_num_rows($rsPercPatro);
        
      }
      
      $aNovaListaPrev = array();
      
      foreach ($aListaPrev as $sPrev) {
        $aNovaListaPrev[] = $sPrev-2;
      }
      
      $sListaPrev = implode(",",$aNovaListaPrev);
      
      $aListaEmpenhos = array();
        
  	  $aListaEmpenhos = $oDadosEmpenhoFolha->getRubricasEmpenhosPrev($oPost->sTipo,
  	                                                                 $oPost->iAnoFolha,
  	                                                                 $oPost->iMesFolha,
  	                                                                 $sListaPrev,
  	                                                                 db_getsession('DB_instit'));
  	  if ( empty($aListaEmpenhos) ) {
  	    $lExiste = false;
  	  } else {
  	    $lExiste = true;
  	  }
      
      $aRetorno = array( "lErro"  =>false,
                         "lExiste"=>$lExiste);    
      
    break;
    case 'consultarSLIP':
     
      if ( isset($oPost->sSemestre)) {
        $sSemestre = $oPost->sSemestre;
      } else {
        $sSemestre = 0;
      }
      
      $lGerados     = false;
  	  $lSlipGerados = false;
      
      $sWhereSlip  = "     rh79_siglaarq = '{$oPost->sSigla}'                  "; 
      $sWhereSlip .= " and rh79_anousu   = {$oPost->iAnoFolha}                 ";
      $sWhereSlip .= " and rh79_mesusu   = {$oPost->iMesFolha}                 ";
      $sWhereSlip .= " and rh73_instit   = ".db_getsession('DB_instit');
      $sWhereSlip .= " and (case                                               ";
      $sWhereSlip .= "        when k17_codigo is not null                      "; 
      $sWhereSlip .= "          then k17_instit   = ".db_getsession('DB_instit');  
      $sWhereSlip .= "        else                                             ";
      $sWhereSlip .= "          true                                          ";
      $sWhereSlip .= "      end)                                               ";
      if (isset($oPost->sRescisoes) && $oPost->sRescisoes != "") {
        $aRescisoes  = explode(",", $oPost->sRescisoes);
        $sWhereSlip .= " and rh73_seqpes in({$oPost->sRescisoes})";
      } else {
        $sWhereSlip .= " and rh79_seqcompl = {$sSemestre}        ";
      }
      $sWhereSlip .= " and rh73_tiporubrica = 3                  ";
      
      $rsSlips     = $clrhslipfolha->sql_record($clrhslipfolha->sql_query_slip(null,"*",null,$sWhereSlip." and rh82_sequencial is null limit 1"));
      $iLinhasSlip = $clrhslipfolha->numrows;
      
  	  if ( $iLinhasSlip > 0 ) {
  	    $lGerados = true;
      } else {
      	$rsSlips     = $clrhslipfolha->sql_record($clrhslipfolha->sql_query_slip(null,"*",null,$sWhereSlip." and rh82_sequencial is not null limit 1"));
  	    $iLinhasSlip = $clrhslipfolha->numrows;
  	       
  	    if ( $iLinhasSlip > 0 ) {
  	      $lSlipGerados = true;
  	    }
  	      
      }
      
      $aRetorno = array( "lErro"       =>$lErro,
                         "lGerados"    =>$lGerados,
                         "lSlipGerados"=>$lSlipGerados);    
      
    break;
    case 'consultarDadosGeracaoSLIP':
     
      if ( isset($oPost->sSemestre) && trim($oPost->sSemestre) != '' ) {
        $sSemestre = $oPost->sSemestre;
      } else {
        $sSemestre = 0;
      }
      
      
      $sCamposSlip  = " distinct rh79_recurso as recurso,       ";
      $sCamposSlip .= " o15_descr             as descrrecurso,  ";
      $sCamposSlip .= " c61_reduz             as contadebito,   ";
      $sCamposSlip .= " rh41_conta            as contacredito,  ";
      $sCamposSlip .= " sum(rh73_valor)       as valor,         ";
      $sCamposSlip .= " rh79_sequencial       as folhaslip,     ";
      $sCamposSlip .= " e48_cgm               as cgm,           ";
      $sCamposSlip .= " e21_sequencial        as retencao,      ";
      $sCamposSlip .= " rh79_concarpeculiar   as concarpeculiar ";
      
      $sGroupSlip   = "group by rh79_recurso,   ";
      $sGroupSlip  .= "         o15_descr,      ";
      $sGroupSlip  .= "         contadebito,    ";
      $sGroupSlip  .= "         contacredito,   ";
      $sGroupSlip  .= "         folhaslip,      ";
      $sGroupSlip  .= "         cgm,            ";
      $sGroupSlip  .= "         retencao,       ";
      $sGroupSlip  .= "         concarpeculiar  ";
      
      $sWhereSlip   = "     rh79_siglaarq = '{$oPost->sSigla}'         "; 
      $sWhereSlip  .= " and rh79_anousu   = {$oPost->iAnoFolha}        ";
      $sWhereSlip  .= " and rh79_mesusu   = {$oPost->iMesFolha}        ";
      $sWhereSlip  .= " and rh73_instit   = ".db_getsession('DB_instit');
      $sWhereSlip  .= " and rh73_tiporubrica = 3                       ";
      $sWhereSlip  .= " and rh82_sequencial is null                    ";
      $aRescisoes = array();
      if (isset($oPost->sRescisoes) && $oPost->sRescisoes != "") {
          
        $aRescisoes  = explode(",", $oPost->sRescisoes);
        $sWhereSlip .= " and rh73_seqpes in({$oPost->sRescisoes})        ";
        $oDadosEmpenhoFolha  = new dadosEmpenhoFolhaRescisao;
      } else {
      	
        $sWhereSlip .= " and rh79_seqcompl = {$sSemestre}               ";
        $aRescisoes  = $sSemestre;
        
      }
      $sWhereSlip  .= $sGroupSlip;  
      $rsSlips     = $clrhslipfolha->sql_record($clrhslipfolha->sql_query_slip(null,$sCamposSlip,null,$sWhereSlip));
      $aListaSlips = db_utils::getCollectionByRecord($rsSlips,false,false,true);
      
      $lLiberada = $oDadosEmpenhoFolha->isLiberada($oPost->sSigla,
  	  					  	                               1,
  	  					  	                               $oPost->iAnoFolha,
  	  					  	                               $oPost->iMesFolha,
  	  					  	                               $aRescisoes
  	  					  	                               );
      	                                
      $aRetorno = array( "lErro"    =>false,
                         "aSlips"   =>$aListaSlips,
                         "lLiberada"=>$lLiberada);
          
    break;
    case 'consultarSelectContas':
  	 
  	  include("classes/db_conplanoexe_classe.php");
      
      $clconplanoexe = new cl_conplanoexe;
  	  
      $dtDataUsu = date('Y-m-d',db_getsession("DB_datausu"));
      
      $sCampos  = " c62_reduz as reduz,                                 "; 
      $sCampos .= " c62_reduz||'-'||c60_estrut||'-'||c60_descr as descr ";
        
      $sWhere  = "     c62_anousu = ".db_getsession("DB_anousu");
      $sWhere .= " and c60_codsis in (1,5,6,7,8)                                 ";
      $sWhere .= " and substr(c60_estrut,1,1) not in ('3','4')                   ";
      $sWhere .= " and c61_instit = ".db_getsession("DB_instit");
      $sWhere .= " and ( case                                                    ";
      $sWhere .= "         when (     (      t1.k02_codigo is not null           "; 
      $sWhere .= "                       and t1.k02_limite is not null           "; 
      $sWhere .= "                       and t1.k02_limite < '{$dtDataUsu}')     ";
      $sWhere .= "                 or (      t2.k02_codigo is not null           ";
      $sWhere .= "                       and t2.k02_limite is not null           "; 
      $sWhere .= "                       and t2.k02_limite  < '{$dtDataUsu}')    ";
      $sWhere .= "                 or (      saltes.k13_reduz  is not null       ";
      $sWhere .= "                       and saltes.k13_limite is not null       "; 
      $sWhere .= "                       and saltes.k13_limite < '{$dtDataUsu}') ";
      $sWhere .= "              ) then false                                     ";
      $sWhere .= "         else true                                             ";
      $sWhere .= "       end )                                                   ";
          
      $sSqlContas = $clconplanoexe->sql_conta_debitar(date('Y-m-d',db_getsession("DB_datausu")),null,$sCampos,"c60_estrut",$sWhere);
      $rsContas   = $clconplanoexe->sql_record($sSqlContas);      
      
      if ( $rsContas ) {
      	 
      	$iLinhasContas = pg_num_rows($rsContas);
        $aListaContas  = array();
        $aListaContas  = db_utils::getCollectionByRecord($rsContas,false,false,true);
        
      } else {
        
        throw new DBException("Erro consultando dados das contas. ".pg_last_error());

      }
      
        $aRetorno = array( "lErro" =>$lErro,
                           "aContas"=>$aListaContas);    
    break;
    case 'geraSLIP':
     
  	  $aSlips   = $oJson->decode(str_replace("\\","",$oPost->aSlips));
      $aObjSlip = array();
  	  
      switch ($oPost->sSigla) {
        case "r48":
          $sTipoFolha = "COMPLEMENTAR";
        break;
        case "r14":
          $sTipoFolha = "SALÁRIO";
        break;
        case "r35":
          $sTipoFolha = "13º SALÁRIO";
        break;
        case "r22":
          $sTipoFolha = "ADIANTAMENTO";
        break;
        case "r20":
          $sTipoFolha = "RESCISÃO";
        break;
        case "sup":
          $sTipoFolha = "SUPLEMENTAR";
        break;
      }      
      
      $sAnoMesFolha  = $oPost->iAnoFolha."/".str_pad($oPost->iMesFolha,2,"0",STR_PAD_LEFT);
      
      foreach ( $aSlips as $aSlip ){
      
      	$oSlip = new stdClass();
      	$oSlip->iRecurso      = $aSlip[0];
        $oSlip->iContaDebito  = $aSlip[2];
        $oSlip->iContaCredito = $aSlip[3];
        $oSlip->nValor        = $aSlip[4];
        $oSlip->iCodFolhaSlip = $aSlip[5];
        $oSlip->iNumCgm       = $aSlip[6];
        $oSlip->iRetencao     = $aSlip[7];
        
        $sRecursoFolha = $aSlip[0]." - ".$aSlip[1];
        
        if ( trim($aSlip[8]) != '' && trim($aSlip[8]) != 0 ) {
        	$sConcarPeculiar = " Característica Peculiar : ".$aSlip[8];
        } else {
        	$sConcarPeculiar = "";
        }
        
        $oSlip->sObservacao   = "DESPESA EXTRA-ORÇAMENTÁRIA FOLHA DE PAGAMENTO - FOLHA {$sTipoFolha} {$sAnoMesFolha} - {$sRecursoFolha} {$sConcarPeculiar}";
        
        $aObjSlip[] = $oSlip;
                                     
      }	
  	  
  	  db_inicio_transacao();
  	  
      $aListaSlips     = $oSlipFolha->geraSlipFolhaLote($aObjSlip);
      
      db_fim_transacao(false);
      
      $iSlipIni = $aListaSlips[0];
  	  $iSlipFin = end($aListaSlips);
  	  
      $aRetorno = array( "lErro"       =>$lErro,
                         "sMsg"        =>urlencode("Gerados com sucesso SLIPs de {$iSlipIni} a {$iSlipFin}!"),
                         "sListaSlips" =>implode(",",$aListaSlips));
         
    break;
    case 'geraPlanilha':

  	  if ( isset($oPost->sSemestre) && trim($oPost->sSemestre) != '' ) {
  	  	$sSemestre = $oPost->sSemestre;
  	  } else {
  	  	$sSemestre = "0";
  	  }
  	  
      db_inicio_transacao();
      
      if ($oPost->sSigla == 'r20') {
          
          $oDadosEmpenhoFolha  = new dadosEmpenhoFolhaRescisao;
          $sSemestre           = explode(",", $oPost->sRescisoes);
      }
       
      $aPlanilha = $oDadosEmpenhoFolha->geraPlanilhaGeral($oPost->sSigla,
                                                          $oPost->iAnoFolha,
                                                          $oPost->iMesFolha,
                                                          db_getsession('DB_instit'),
                                                          $oPost->iCgm,
                                                          $sSemestre);
      if ( count($aPlanilha) > 0 ) {
        
  	    if ( count($aPlanilha) > 1 ) {
  	    	$sMsgPlan = "Gerado Planilhas nº".implode(",",$aPlanilha);                                                        
  	    } else {
  	    	$sMsgPlan = "Gerado Planilha nº {$aPlanilha[0]}";
  	    }
  	    
      } else {
        throw new DBException("Nenhum registro encontrado!");
      }
                                                            
      db_fim_transacao(false);
      
      $aRetorno = array( "lErro"    =>false,
                         "sMsg"     =>urlencode($sMsgPlan),
                         "sListaPla"=>implode(",",$aPlanilha) );
     
    break;
    case 'getRescisoesNaoEmpenhadas':
     
      $oDadosEmpenhoFolhaRescisao = new dadosEmpenhoFolhaRescisao();
      $aRescisoes  = $oDadosEmpenhoFolhaRescisao->getRescisoesNaoEmpenhadas($oPost->iMesFolha, $oPost->iAnoFolha, $oPost->sDataInicial, $oPost->sDataFinal);
      $aRetorno = array("lErro"           => false,
                        "sListaRescisoes" =>$aRescisoes
                       );
                       
    break;
    case 'getRescisoesEmpenhadas':
     
      $oDadosEmpenhoFolhaRescisao = new dadosEmpenhoFolhaRescisao();
      $aRescisoes  = $oDadosEmpenhoFolhaRescisao->getRescisoesEmpenhadas($oPost->iMesFolha, $oPost->iAnoFolha, $oPost->sDataInicial, $oPost->sDataFinal);
      $aRetorno = array("lErro"           => false,
                        "sListaRescisoes" =>$aRescisoes
                       );
                         
    break;
    case 'getRescisoesSlips':
     
      $oDadosEmpenhoFolhaRescisao = new dadosEmpenhoFolhaRescisao();
      $aRescisoes  = $oDadosEmpenhoFolhaRescisao->getRescisoesSlips($oPost->iMesFolha, $oPost->iAnoFolha);
      $aRetorno = array("lErro"           => false,
                        "sListaRescisoes" =>$aRescisoes
                       );
            
    break;
    case 'getRescisoesPlanilhas':
     
      $oDadosEmpenhoFolhaRescisao = new dadosEmpenhoFolhaRescisao();
      $aRescisoes  = $oDadosEmpenhoFolhaRescisao->getRescisoesPlanilhas($oPost->iMesFolha, $oPost->iAnoFolha);
      $aRetorno = array("lErro"           => false,
                        "sListaRescisoes" =>$aRescisoes
                       );
            
    break;
    
    case 'anularEmpenho':

    	$lExisteEmpenho = false;
    	
    	$aSiglas        = explode(",", $oPost->sSigla);
    	
    	db_inicio_transacao();
    	
    	foreach ($aSiglas as $sSigla) {
    		
    		$oPost->sSigla = trim($sSigla);
      
	      /**
	       * selecionamos todos os empenhos do tipo que tenham empenho gerados e anulamos
	       */
	    	
	    	$iInstituicao  = db_getsession("DB_instit");
	    	              
	      $sSqlEmpenhos  = "select rh72_sequencial,                                                                          ";
	      $sSqlEmpenhos .= "       rh72_coddot,                                                                              ";
	      $sSqlEmpenhos .= "       rh72_codele,                                                                              ";
	      $sSqlEmpenhos .= "       rh72_unidade,                                                                             ";
	      $sSqlEmpenhos .= "       rh72_orgao,                                                                               ";
	      $sSqlEmpenhos .= "       rh72_projativ,                                                                            ";
	      $sSqlEmpenhos .= "       rh72_anousu,                                                                              ";
	      $sSqlEmpenhos .= "       rh72_mesusu,                                                                              ";
	      $sSqlEmpenhos .= "       rh72_recurso,                                                                             ";
	      $sSqlEmpenhos .= "       rh72_siglaarq,                                                                            ";
	      $sSqlEmpenhos .= "       round(sum(rh73_valor), 2) as valorretencao                                                ";
	      $sSqlEmpenhos .= "  from rhempenhofolha 													                                                 "; 
	      $sSqlEmpenhos .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial            "; 
	      $sSqlEmpenhos .= "       inner join rhempenhofolharubrica      on rh73_sequencial     = rh81_rhempenhofolharubrica ";
	      $sSqlEmpenhos .= "       inner join rhpessoalmov               on rh73_seqpes         = rh02_seqpes                ";
	      $sSqlEmpenhos .= "                                            and rh73_instit     		 = rh02_instit               ";
	      $sSqlEmpenhos .= "       inner join rhempenhofolhaempenho      on rh72_sequencial 		 = rh76_rhempenhofolha       ";
	      $sSqlEmpenhos .= "                                            and rh72_tipoempenho    =  {$oPost->iTipo}           ";
	      $sSqlEmpenhos .= "                                            and rh73_tiporubrica    =  1                         ";
	      $sSqlEmpenhos .= "                                            and rh73_instit         =  {$iInstituicao}           ";
	      $sSqlEmpenhos .= "                                            and rh72_anousu         =  {$oPost->iAnoFolha}       "; 
	      $sSqlEmpenhos .= "                                            and rh72_mesusu         =  {$oPost->iMesFolha}       "; 
	      $sSqlEmpenhos .= "                                            and rh72_siglaarq       = '{$oPost->sSigla}'	       ";
	                                                                                                                          
	      if ($oPost->sSigla == 'r20' && $oPost->iTipo == 1) {
	        $sSqlEmpenhos .= " 																				  and rh73_seqpes in({$oPost->aRescisoes})          	 ";
	      }
	                                                                                               
	      $sSqlEmpenhos .= " group by rh72_sequencial,                                                                       ";
	      $sSqlEmpenhos .= "          rh72_coddot,                                                                           ";
	      $sSqlEmpenhos .= "          rh72_codele,                                                                           ";
	      $sSqlEmpenhos .= "          rh72_unidade,                                                                          ";
	      $sSqlEmpenhos .= "          rh72_orgao,                                                                            ";
	      $sSqlEmpenhos .= "          rh72_projativ,                                                                         ";
	      $sSqlEmpenhos .= "          rh72_mesusu,                                                                           ";
	      $sSqlEmpenhos .= "          rh72_anousu,                                                                           ";
	      $sSqlEmpenhos .= "          rh72_recurso,                                                                          ";
	      $sSqlEmpenhos .= "          rh72_siglaarq                                                                          ";
	      
	      $rsDadosEmpenho = db_query($sSqlEmpenhos);
	      $aEmpenhos      = db_utils::getCollectionByRecord($rsDadosEmpenho);
	      
	      if (count($aEmpenhos) > 0) {
	      	
	      	$lExisteEmpenho = true;
	      	
	        foreach ($aEmpenhos as $oEmpenho) {
	            
	          $oEmpenhoFolha = new empenhoFolha($oEmpenho->rh72_sequencial);
	          $oEmpenhoFolha->estornarEmpenho();
	            
	        }
	
	        /**
	         * Marcamos as rescisoes como não Empenhadas
	         */
	        if ($oPost->sSigla == 'r20' && $oPost->iTipo == 1) {
	          
	          $aRescisoes = explode(",",$oPost->aRescisoes);
	          
	          foreach ($aRescisoes as $iRescisao) {
	          
	            $oDaoPesRescisao = db_utils::getDao("rhpesrescisao");
	            $oDaoPesRescisao->rh05_empenhado = "false";
	            $oDaoPesRescisao->rh05_seqpes    = $iRescisao;
	            $oDaoPesRescisao->alterar($iRescisao);
	            
	          }
	           
	        }
	         
	      } 

    	}
    		
    	db_fim_transacao(false);
    	
    	if (!$lExisteEmpenho) {
    		throw new Exception('Não foram encontrados empenhos gerados com os filtros informados.');
    	}
      
      $aRetorno = array("lErro" => false,"sMsg"  => urlencode("Empenhos anulados com sucesso!"));
      
    break;
    
    /**
     * Retorna todas as folhas suplementares fechadas que não esteja empenhadas.
     * 
     * @param Integer $iAnoFolha;
     * @param Integer $iMesFolha
     */
    case 'consultaSuplementaresFechadas':
     
      $aFolhas      = array();
      $iAnoUsu      = $oPost->iAnoFolha;
      $iMesUsu      = $oPost->iMesFolha;
      $oCompetencia = new DBCompetencia($iAnoUsu, $iMesUsu);
      
      $aFolhaSuplementares = FolhaPagamentoSuplementar::getFolhasFechadasCompetencia($oCompetencia);
      
      foreach ($aFolhaSuplementares as $oFolhaSuplementar) {
        
        try {

          $oFolhaSuplementar->verificarEmpenho();
          $aFolhas[] = $oFolhaSuplementar->getNumero();
          
        } catch (Exception $ex) { }
      }
      
      $aRetorno = array("lErro"     =>false,
                        "aSemestre" =>$aFolhas);
      
    break;
    
    /**
     * Retorna todas as folhas de pagamento que foram empenhadas.
     * 
     * @param Integer iTipoFolha
     * @param Integer $iAnoFolha;
     * @param Integer $iMesFolha
     */
    case 'getFolhasPagamentoEmpenhas':
     
      $aFolhas      = array();
      $iTipoFolha   = $oPost->iTipoFolha;
      $iAnoUsu      = $oPost->iAnoFolha;
      $iMesUsu      = $oPost->iMesFolha;
      $oCompetencia = new DBCompetencia($iAnoUsu, $iMesUsu);
      
      $aFolhasPagamento = FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, $iTipoFolha);
      
      foreach ($aFolhasPagamento as $oFolhaPagamento) {
        
        try {
          $oFolhaPagamento->verificarEmpenho();     
        } catch (Exception $ex) {
          $aFolhas[] = $oFolhaPagamento->getNumero();
        }
        
      }
      
      $aRetorno = array("lErro"     =>false,
                        "aSemestre" =>$aFolhas);
      
    break;
    
    /**
     * Este case válida se as folhas fechadas na competência geram Slip da Folha(e liberadas) pelas rotinas:
     * 
     *  Geração Empenhos(novo) > Folha
     *  Geração Empenhos(novo) > Liberar Empenhos/SLIP
     *
     * @param Integer iTipoFolha
     * @param Integer $iAnoFolha;
     * @param Integer $iMesFolha
     *
     */
    case 'getFolhasComPreSlipGerado':
     
      $aFolhas          = array();
      $iTipoFolha       = $oPost->iTipoFolha;
      $iAnoUsu          = $oPost->iAnoFolha;
      $iMesUsu          = $oPost->iMesFolha;
      $oCompetencia     = new DBCompetencia($iAnoUsu, $iMesUsu);
      $aFolhasPagamento = FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, $iTipoFolha);
     
      foreach ($aFolhasPagamento as $oFolhaPagamento) {
       
        if(slipFolha::isFolhaPagamentoSlipLiberado($oFolhaPagamento)) {
          $aFolhas[] = $oFolhaPagamento->getNumero();
        } 
      }
      
      $aRetorno = array("lErro"     =>false,
                        "aSemestre" =>$aFolhas);
      
    break;
    
    /**
     * Este case retorna todas as folhas pagamentos que estão fechadas. 
     * 
     * @param Integer iTipoFolha
     * @param Integer $iAnoFolha;
     * @param Integer $iMesFolha
     */
    case 'getFolhaPagamentoFechada':
     
      $aFolhas          = array();
      $iTipoFolha       = $oPost->iTipoFolha;
      $iAnoUsu          = $oPost->iAnoFolha;
      $iMesUsu          = $oPost->iMesFolha;
      $oCompetencia     = new DBCompetencia($iAnoUsu, $iMesUsu);
      $aFolhasPagamento = FolhaPagamento::getFolhasFechadasCompetencia($oCompetencia, $iTipoFolha);
     
      foreach ($aFolhasPagamento as $oFolhaPagamento) {
        $aFolhas[] = $oFolhaPagamento->getNumero();
      }
      
      $aRetorno = array("lErro"     =>false,
                        "aSemestre" =>$aFolhas);
      
    break;
  }
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  
  $aRetorno = array( "lErro"=>true,
                   "sMsg" =>urlencode($eErro->getMessage()));
} 

echo $oJson->encode($aRetorno);