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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_diversos_classe.php");
require_once("classes/db_procdiver_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_arreinscr_classe.php");
require_once("classes/db_arreold_classe.php");
require_once("classes/db_arrematric_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_arrepaga_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_inflan_classe.php");
require_once("classes/db_disbancodiver_classe.php");

require_once("model/cancelamentoDebitos.model.php");

//db_postmemory($HTTP_SERVER_VARS,2);
db_postmemory($HTTP_POST_VARS);
$oCancelaDebito  = new cancelamentoDebitos();
$cldiversos      = new cl_diversos;
$clinflan        = new cl_inflan;
$clprocdiver     = new cl_procdiver;
$clcgm           = new cl_cgm;
$clarreold       = new cl_arreold;
$clarrecad       = new cl_arrecad;
$cliptubase      = new cl_iptubase;
$clissbase       = new cl_issbase;
$clarrematric    = new cl_arrematric;
$clarreinscr     = new cl_arreinscr;
$clarrepaga      = new cl_arrepaga;
$cldisbancodiver = new cl_disbancodiver;
$db_opcao        = 33;
$db_botao        = false;
$sMsgErro        = '';

if (isset($subtes) && $subtes=="ok" || isset($HTTP_POST_VARS["db_opcao"])) {
  $db_botao = true;
  $db_opcao=3;
}

if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Excluir") {
	
	db_inicio_transacao();
  
  try {

    $sWhereDebitosDiversos  = "     arrecad.k00_numpre    = $dv05_numpre "; 
	  $sWhereDebitosDiversos .= " and arreinstit.k00_instit = ".db_getsession('DB_instit');
	  
	  $sSqlDebitosDiversos    = $clarrecad->sql_query_file_instit("","arrecad.*","",$sWhereDebitosDiversos);
	  $rsDebitosDiversos      = $clarrecad->sql_record($sSqlDebitosDiversos);
	  $iLinhasDebitosDiversos = $clarrecad->numrows;
	  
	  $aDebitos = array();
	       
	  if ($iLinhasDebitosDiversos > 0) {
	    
	    for ( $iInd=0; $iInd < $iLinhasDebitosDiversos; $iInd++ ) {
	      
	      $oDebitoDiverso = db_utils::fieldsMemory($rsDebitosDiversos,$iInd);
	      
	      $aDadosDebitos = array();  
	      $aDadosDebitos['Numpre']  = $oDebitoDiverso->k00_numpre;
	      $aDadosDebitos['Numpar']  = $oDebitoDiverso->k00_numpar;
	      $aDadosDebitos['Receita'] = $oDebitoDiverso->k00_receit;
	
	      $aDebitos[] = $aDadosDebitos;
	    }
	  }  
	
	  if ( count($aDebitos) > 0 ) {
	    
	    try {
	              
	      $oCancelaDebito->setArreHistTXT("EXCLUSÃO DE DIVERSOS");
	      $oCancelaDebito->setTipoCancelamento(2);
	      $oCancelaDebito->setCadAcao(1);
	      $oCancelaDebito->geraCancelamento($aDebitos);
	    } catch (Exception $eException) {
	      throw new Exception($eException->getMessage());
	    }  
	  }
	  
	  $result52  = $clarreinscr->sql_record($clarreinscr->sql_query_file("","","*","","k00_numpre=$dv05_numpre"));
	  $numrows52 = $clarreinscr->numrows;
	  
	  if ($numrows52 > 0) {
	  	
	    $clarreinscr->k00_numpre=$dv05_numpre;
	    $clarreinscr->excluir($dv05_numpre);
	    
	    if ($clarreinscr->erro_status==0) {
	      throw new Exception($clarreinscr->erro_msg);
	    }
    }
	
    
	  $result22  = $clarrematric->sql_record($clarrematric->sql_query_file("","","*","","k00_numpre=$dv05_numpre"));
	  $numrows22 = $clarrematric->numrows;
	  
	  if ($numrows22 > 0) {
	  	
	    $clarrematric->k00_numpre=$dv05_numpre;
	    $clarrematric->excluir($dv05_numpre);
	    
	    if ($clarrematric->erro_status==0) {
	      throw new Exception($clarrematric->erro_msg);
	    }
    }
	
	  $sql33     = $cldisbancodiver->sql_query_file(null,"*",null,"k44_coddiver=$dv05_coddiver");
	  $result33  = $cldisbancodiver->sql_record($sql33);
	  $numrows33 = $cldisbancodiver->numrows;
	  
    if ($numrows33 > 0) {
	    
    	$cldisbancodiver->excluir(null, "k44_coddiver=$dv05_coddiver");
	    
      if ($cldisbancodiver->erro_status==0) {
        throw new Exception($cldisbancodiver->erro_msg);
      }
    }

    $cldiversos->excluir($dv05_coddiver);
	  
	  if ($cldiversos->erro_status=='0') {
	    throw new Exception($cldiversos->erro_msg);
	  }  	
	  db_fim_transacao(false);
  	
  } catch (Exception $eException) {
  	
  	db_fim_transacao(true);
  	
  	$sMsgErro = $eException->getMessage(); 
  }

  
} else if(isset($chavepesquisa)) {
   $db_opcao = 3;
   $db_botao = true;
   $result = $cldiversos->sql_record($cldiversos->sql_query_file($chavepesquisa,"*",null," 
                                                                       dv05_coddiver = $chavepesquisa and 
                                                                       dv05_instit = ".db_getsession('DB_instit')." ")); 
   db_fieldsmemory($result,0);
 
   $venc=$dv05_privenc_ano."-".$dv05_privenc_mes."-".$dv05_privenc_dia;
   $result03=db_query("select tabrecjm.k02_corr, procdiver.dv09_receit from procdiver 
                                                      inner join tabrec   on tabrec.k02_codigo  = procdiver.dv09_receit 
                                                      inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm 
                                                   where procdiver.dv09_procdiver = $dv05_procdiver and 
                                                         dv09_instit = ".db_getsession('DB_instit')." ");
   db_fieldsmemory($result03,0);
   $i02_codigo=$k02_corr;

   $result10=$clarrepaga->sql_record($clarrepaga->sql_query_file("","*","","k00_numpre=$dv05_numpre"));   
   if($clarrepaga->numrows>0){
     $foipago="ok";
     $db_botao = false;
   } 		
 
}
$HTTP_SERVER_VARS['QUERY_STRING']="";

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

			<?
			  include("forms/db_frmdiversosalt.php");
			?>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($foipago) && $foipago=="ok"){
  db_msgbox(_M("tributario.diversos.db_frmdiversosalt.debito_parcela_paga"));      
} 		

if($db_opcao==33 && !isset($foipago)){
  echo "<script>js_pesquisa();</script>";  
}
if ((isset($HTTP_POST_VARS["db_opcao"]))) {
	
	if (trim($sMsgErro) != '') {
		
		db_msgbox($sMsgErro);
	  $db_botao = true;
	  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
	  
	} else {
	  $cldiversos->erro(true,false);
	 // db_redireciona("dvr3_diversos007.php");
	}
}
?>