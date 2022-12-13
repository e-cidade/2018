<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_tabrectipo_classe.php");
require_once("classes/db_tabrecregrasjm_classe.php");
require_once("classes/db_taborc_classe.php");
require_once("classes/db_tabplan_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tabrecarretipo_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cltabrec         = new cl_tabrec;
$cltabtiporec     = new cl_tabrectipo;
$cltabrecregrasjm = new cl_tabrecregrasjm;
$cltaborc         = new cl_taborc;
$cltabplan        = new cl_tabplan;
$clnumpref        = new cl_numpref;
$cltabrecarretipo = new cl_tabrecarretipo;

$db_opcao = 1;
$db_botao = true;
$anousu   = db_getsession("DB_anousu");

if(isset($incluir)){
  db_inicio_transacao();

  $sqlerro=false;
  if(trim($k02_codigo) == ""){
    $result = $cltabrec->sql_record($cltabrec->atualiza_sequencia());
    db_fieldsmemory($result, 0);
  }   
  
  $cltabrec->k02_tabrectipo = $k02_tabrectipo;
  $cltabrec->incluir($k02_codigo);
  $erro_msg = $cltabrec->erro_msg;
  if($cltabrec->erro_status == 0){
    $sqlerro = true;
  }

  if($sqlerro == false){
    $cltabrecregrasjm->k04_receit = $k02_codigo;
    $cltabrecregrasjm->k04_codjm  = $k02_codjm;
    $cltabrecregrasjm->k04_dtini  = "1900-01-01"; 
    $cltabrecregrasjm->k04_dtfim  = "2099-12-31";
    $cltabrecregrasjm->incluir(null);
    if($cltabrecregrasjm->erro_status == 0){
      $erro_msg = $cltabrecregrasjm->erro_msg;
      $sqlerro = true;
    }
  }

  if($sqlerro == false && $k02_tipo == "O"){
    $cltaborc->k02_codigo = $k02_codigo;
    $cltaborc->k02_anousu = $anousu;
    $cltaborc->k02_estorc = $estrut;
    $cltaborc->k02_codrec = $codigo;
    $cltaborc->incluir($anousu,$k02_codigo);
    if($cltaborc->erro_status==0){
      $sqlerro = true;
      $erro_msg = $cltaborc->erro_msg;
    }
  }

  if($sqlerro == false && strtoupper($k02_tipo) == "E"){
    $cltabplan->k02_codigo  = $k02_codigo;
    $cltabplan->k02_anousu  = db_getsession("DB_anousu");
    $cltabplan->k02_reduz   = $codigo;
    $cltabplan->k02_estpla  = $estrut;
    $cltabplan->incluir($k02_codigo,$anousu);
    if($cltabplan->erro_status==0){
      $sqlerro = true;
      $erro_msg = $cltabplan->erro_msg;
    }
  }
	
	if($sqlerro == false and $k79_arretipo != ""){
		$cltabrecarretipo-> k79_receit   = $k02_codigo;
		$cltabrecarretipo-> k79_arretipo = $k79_arretipo;
		$cltabrecarretipo->incluir(null);
		if($cltabrecarretipo->erro_status="0"){
  	  $erro = true;
		  $msgerro = $cltabrecarretipo->erro_msg;
    } 
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_onload();">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
      include("forms/db_frmtabrec.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($cltabrec->erro_campo!=""){
      echo "<script> document.form1.".$cltabrec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltabrec->erro_campo.".focus();</script>";
    };
  }else{
    db_redireciona("cai1_receita005.php?opcao=1&chavepesquisa=".$k02_codigo."&liberaaba=true&k02_descr=$k02_descr");
  }  
}
?>