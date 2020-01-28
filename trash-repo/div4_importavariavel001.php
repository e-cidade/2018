<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arretipo_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_arrenumcgm_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("classes/db_cissqn_classe.php");
require_once("classes/db_divida_classe.php");
require_once("classes/db_proced_classe.php");
require_once("classes/db_procedarretipo_classe.php");
require_once("classes/db_issvardiv_classe.php");
require_once("classes/db_issvar_classe.php");
require_once("classes/db_issvarlev_classe.php");
require_once("classes/db_divold_classe.php");
require_once("classes/db_parfiscal_classe.php");
require_once("classes/db_divimporta_classe.php");
require_once("classes/db_divimportareg_classe.php");
require_once("classes/db_dividaprotprocesso_classe.php");

$clarrecad			        = new cl_arrecad;
$clarretipo			        = new cl_arretipo;
$clarrenumcgm		        = new cl_arrenumcgm;
$clarreinscr		        = new cl_arreinscr;
$clissbase		 	        = new cl_issbase;
$clnumpref			        = new cl_numpref;
$clcissqn				        = new cl_cissqn;
$cldivida				        = new cl_divida;
$clproced			 	        = new cl_proced;
$clprocedarretipo       = new cl_procedarretipo;
$clissvardiv		        = new cl_issvardiv;
$clissvar				        = new cl_issvar;
$clissvarlev		        = new cl_issvarlev;
$cldivold				        = new cl_divold;
$clparfiscal		        = new cl_parfiscal;
$cldivimporta           = new cl_divimporta;
$cldivimportareg        = new cl_divimportareg;
$oDaoDividaprotprocesso = new cl_dividaprotprocesso;

db_postmemory($HTTP_POST_VARS);
$oPost          = db_utils::postMemory($_POST);
$oGet           = db_utils::postMemory($_GET);
$db_opcao       = 1;
$db_botao       = true;

if (isset($lancar)) {
  
  if ((int)$oPost->lProcessoSistema == 1) {
  
    $lProcessoSistema = (int)$oPost->lProcessoSistema;
    $iProcesso        = $oPost->v01_processo;
    $sTitular         = "";
    $dDataProcesso    = "";
  } else {
  
    $lProcessoSistema = (int)$oPost->lProcessoSistema;
    $iProcesso        = $oPost->v01_processoExterno;
    $sTitular         = $oPost->v01_titular;
    $dDataProcesso    = implode("-", array_reverse(explode("/", $oPost->v01_dtprocesso)));
  }  
  
  $sqlerro = false;
  db_inicio_transacao();
  $dataini = date("Y-m-d", db_getsession('DB_datausu') );
  $horaini = date("H:i");
  
  if (isset($inscricao) && $inscricao != "") {
       
    $result = $clissbase->sql_record($clissbase->sql_query_file($inscricao, "q02_numcgm")); 
    db_fieldsmemory($result,0);
    $numcgm = $q02_numcgm;
    
  } else if (isset($z01_numcgm) && $z01_numcgm != "") {
    $numcgm = $z01_numcgm;
    
  }
  
  $data				= date("Y-m-d",db_getsession("DB_datausu"));
  $matriz01	  = split('#',$chaves);  
  
  $jateminscr = "";
  
	for ($q = 0; $q < count($matriz01); $q++ ) {
    
		$arr_dad = split('-',$matriz01[$q]);  
    
		$numpre     = $arr_dad[0];
    $numpar     = $arr_dad[1];
    $ano        = $arr_dad[2];
    $q05_codigo = $arr_dad[3];
    $mes        = $arr_dad[4];
    
    $numpre_novo = $clnumpref->sql_numpre();
    
		if (($inscricao == "") || ($jateminscr == "1")) {   
      
      $sqlinscr  = " select arreinscr.*																																		 ";
			$sqlinscr .= "	 from arreinscr																																			 ";
			$sqlinscr .= "			  inner join arreinstit on arreinstit.k00_numpre = arreinscr.k00_numpre					 ";
			$sqlinscr .= "														 and arreinstit.k00_instit = ".db_getsession('DB_instit').""; 
			$sqlinscr .= "  where arreinscr.k00_numpre = {$numpre}																							 "; 
      
			$resultinscr = db_query($sqlinscr);
      $linhasinscr = pg_num_rows($resultinscr);
      
			if ($linhasinscr > 0) {
			  
        db_fieldsmemory($resultinscr,0);
        $inscricao  = $k00_inscr;
        $jateminscr = "1";
      }
    
		}

    $result_lev = $clissvarlev->sql_record($clissvarlev->sql_query($q05_codigo));
    
		if ($clissvarlev->numrows>0){
		  
			db_fieldsmemory($result_lev, 0);
      $result_parfiscal = $clparfiscal->sql_record($clparfiscal->sql_query_file());
      db_fieldsmemory($result_parfiscal, 0);
      
			if ($y60_espontaneo == 'f'){
        $procedencia = $y32_proced;
      }else{
        $procedencia = $y32_procedexp;
      }	
    } else {
      
      $result001 = $clcissqn->sql_record($clcissqn->sql_query_file(db_getsession("DB_anousu"),"q04_proced")); 
      db_fieldsmemory($result001, 0);
      $procedencia = @$q04_proced;
    }
    
    $result01 = $clarrecad->sql_record($clarrecad->sql_query_info(null,"distinct arretipo.k03_tipo, 
                                                                        arrecad.k00_valor, 
                                                                        arrecad.k00_dtvenc,
                                                                        arrecad.k00_dtoper",
                                                                       null,
                                                                       "arrecad.k00_numpre = {$numpre}
                                                                    and arrecad.k00_numpar = {$numpar}
                                                                    and arreinstit.k00_instit = ".db_getsession('DB_instit') ));
    db_fieldsmemory($result01, 0);
    
    $result01 = $clissvar->sql_record($clissvar->sql_query_file(null, "*", "", "q05_numpre = {$numpre} and q05_numpar = {$numpar}"));
    db_fieldsmemory($result01, 0);
    
    if ($k00_valor == 0) {
      
      if ($q05_vlrinf > 0) {
        $k00_valor = $q05_vlrinf;
      }
    }
    
    if ($k00_valor <= 0) {
      continue;
    }  

    $cldivimporta->v02_usuario = db_getsession('DB_id_usuario');
    $cldivimporta->v02_instit  = db_getsession('DB_instit');
    $cldivimporta->v02_data		 = $dataini;
    $cldivimporta->v02_hora		 = $horaini;
    $cldivimporta->v02_tipo		 = 1;  
    $cldivimporta->v02_datafim = $dataini;
    $cldivimporta->v02_horafim = $horaini;
    $cldivimporta->incluir(null);
    if ($cldivimporta->erro_status == 0) {
      
      db_msgbox($cldivimporta->erro_msg);
      $sqlerro = true;
    }
    
    //----INCLUSÃO DIVIDA--------------------------------------------------------------
    if ($sqlerro == false) {
      		
      $v01_obs = $cldivida->resumo_importacao($numpre, $k03_tipo);
      
//       $iExercicioDivida = $cldivida->getExercicioDivida($numpre, $k03_tipo, $ano);
   
      $cldivida->v01_instit     =  db_getsession('DB_instit');
      $cldivida->v01_dtinclusao =  date('Y-m-d',db_getsession('DB_datausu'));
      $cldivida->v01_numcgm     =  $numcgm;
      $cldivida->v01_dtinsc     =  $data;
      $cldivida->v01_exerc      =  $ano;
      $cldivida->v01_numpre     =  $numpre_novo;
      $cldivida->v01_numpar     =  $numpar;
      $cldivida->v01_numtot     =  1;
      $cldivida->v01_numdig     =  "0";
      $cldivida->v01_vlrhis     =  $k00_valor;
      $cldivida->v01_proced     =  $procedencia;
      $cldivida->v01_obs        =  "Importação de issqn variável. Competência: $ano/$mes - ".$v01_obs;
      $cldivida->v01_livro      =  "";
      $cldivida->v01_folha      =  "";
      $cldivida->v01_dtvenc     =  $k00_dtvenc;
      // $arr    = split("-", $k00_dtvenc);
      // $dtoper = "{$arr[0]}-{$arr[1]}-01";
      $cldivida->v01_dtoper  = $k00_dtoper;
      $cldivida->v01_valor   = $k00_valor;
      
      if ($lProcessoSistema == 0) {
        
        $cldivida->v01_processo   = $iProcesso;
        $cldivida->v01_titular    = $sTitular;
        $cldivida->v01_dtprocesso = $dDataProcesso;
      }
      
      $cldivida->incluir(null);
      
      $erro_msg = $cldivida->erro_msg."--- Inclusão Divida";
      
      if ($cldivida->erro_status == 0) {
        
        $sqlerro = true;
        break;
      }
      $v01_coddiv = $cldivida->v01_coddiv;
      
      if ($lProcessoSistema == 1 && $iProcesso != null) {
        
        $oDaoDividaprotprocesso->v88_divida       = $v01_coddiv;
        $oDaoDividaprotprocesso->v88_protprocesso = $iProcesso;
        $oDaoDividaprotprocesso->incluir(null);
        if ($oDaoDividaprotprocesso->erro_status == 0) {

          $erro_msg = $oDaoDividaprotprocesso->erro_msg;
          $sqlerro = true;
        }
      }
      
      
    }
    //----FINAL DIVIDA-----------------------------------------------------------------
    
    //------- INCLUSÃO NO DIVIMPORTAREG -----------------------------------------------
    $cldivimportareg->v04_divimporta = $cldivimporta->v02_divimporta;
    $cldivimportareg->v04_coddiv     = $cldivida->v01_coddiv;
    $cldivimportareg->incluir();
    
    if ($cldivimportareg->erro_status == 0) {

      $sqlerro = true;
      $erro_msg = $cldivimportareg->erro_msg;
      break;
    }     
    //-------INCLUSÃO NO ARRECAD------------------------------------------------------- 
    
    $result01 = $clproced->sql_record($clproced->sql_query_file($procedencia,"v03_receit,k00_hist"));
    
    db_fieldsmemory($result01,0);
    if ($sqlerro == false){
      
      $clarrecad->k00_receit = $v03_receit;
      $clarrecad->k00_hist   = $k00_hist;
      $clarrecad->k00_numpre = $numpre_novo;
      $clarrecad->k00_numpar = $numpar;
      $clarrecad->k00_numcgm = $numcgm;
      $clarrecad->k00_dtoper = $k00_dtoper;
      $clarrecad->k00_valor  = $k00_valor;
      $clarrecad->k00_dtvenc = $k00_dtvenc;
      $clarrecad->k00_numtot = 1;
      $clarrecad->k00_numdig = "0";
			$clarrecad->k00_tipo   = $k00_tipo;
      $clarrecad->k00_tipojm = "0";
      $clarrecad->incluir();
      $erro_msg = $clarrecad->erro_msg."--- Inclusão Arrecad";
      if ($clarrecad->erro_status == 0) {
        
        $sqlerro = true;
        break;
      }
    }
    //-------FINAL NO ARRECAD---------------------------------------------------------- 
    
    
    //-------INICIO EM ARREINSCR-------------------------------------------------------
    
		if ($sqlerro == false) {
		   
      if (isset($inscricao) && $inscricao != "") { 
        
        $clarreinscr->k00_numpre = $numpre_novo;
        $clarreinscr->k00_inscr  = $inscricao;
        $clarreinscr->k00_perc   = 100;
        $clarreinscr->incluir($numpre_novo,$inscricao);
        $erro_msg = $clarreinscr->erro_msg."--- Inclusão Arreinscr";
        if ($clarreinscr->erro_status == '0') {
          
          $sqlerro = true;
          break;
        }
      }  
    }
    
		//-------INICIO EM ARREINSCR------------------------------------------------------- 
    
		if ($sqlerro == false) {
		  
      $clissvardiv->q19_coddiv = $v01_coddiv; 
      $clissvardiv->q19_issvar = $q05_codigo; 
      $clissvardiv->incluir($v01_coddiv,$q05_codigo);
      $erro_msg = $clissvardiv->erro_msg."--- Inclusão issvardiv";
      
      if ($clissvardiv->erro_status == '0') {
        
        $sqlerro = true;
        break;
      }
    }
    
		//-------FINAL EM ARREINSCR-------------------------------------------------------- 
    
		//-------INCLUSÃO DIVOLD ----------------------------------------------------------
    
		if ($sqlerro == false) {
      
		  $sSqlresult_arrecad_ant = $clarrecad->sql_query_file_instit(null,"arrecad.k00_numpre as numpre_ant,
		                                                                    k00_numpar as numpar_ant,k00_receit as receit_ant",
		                                                             null,
		                                                             "    arrecad.k00_numpre = {$numpre} 
		                                                              and k00_numpar = {$numpar}");
		  $result_arrecad_ant     = $clarrecad->sql_record($sSqlresult_arrecad_ant);
      
      for ($w = 0; $w < $clarrecad->numrows; $w++) {
        
        db_fieldsmemory($result_arrecad_ant, $w);	
        $cldivold->k10_coddiv  = $v01_coddiv;
        $cldivold->k10_numpre  = $numpre_ant;
        $cldivold->k10_numpar  = $numpar_ant;
        $cldivold->k10_receita = $receit_ant;
        $cldivold->incluir(null);
        if ($cldivold->erro_status == 0) {	
              
          $sqlerro  = true;
          $erro_msg = $cldivold->erro_msg."--- Inclusão DIVOLD";
          break;
        }
      }
    }
    
		//-------FIM DIVOLD----------------------------------------------------------------
    
    //-------INICIO EXLCUSÃO DO ARRECAD------------------------------------------------ 
    
		if ($sqlerro==false){
      
			$clarrecad->excluir_arrecad($numpre,$numpar);  	
      $erro_msg = $clarrecad->erro_msg."--- Exclusão arrecad";
			if($clarrecad->erro_status == '0'){
        $sqlerro = true;
        break;
      }
    }
    
		//-------FINAL EXLCUSÃO DO ARRECAD------------------------------------------------- 
 
  }
  if ($sqlerro == true) {
    db_msgbox('O processo não foi efetuado... Contate suporte!');    
  } else {
    $erro_msg = "Importação efetuada com sucesso ";
  }
  
  $datafim = date("Y-m-d");
  $horafim = date("H:i");
  
  $cldivimporta->v02_divimporta = $cldivimporta->v02_divimporta;
  $cldivimporta->v02_datafim    = $datafim;
  $cldivimporta->v02_horafim    = $horafim;
  $cldivimporta->alterar($cldivimporta->v02_divimporta);
  if ($cldivimporta->erro_status == 0) {
    db_msgbox($cldivimporta->erro_msg);
    $sqlerro = true;
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

				<?
					include("forms/db_frmimportavariavel.php");
				?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($lancar)){
  db_msgbox($erro_msg);
}
?>