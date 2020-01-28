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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_contranslan_classe.php");
include("classes/db_contranslr_classe.php");
include("classes/db_contrans_classe.php");
include("classes/db_conhistdoc_classe.php");

$clcontranslan = new cl_contranslan;

$clconhistdoc = new cl_conhistdoc;
$clcontrans = new cl_contrans;

db_postmemory($HTTP_POST_VARS);
if(isset($c53_coddoc)){
  $db_opcao = 1;
  $db_botao = true;
}else{
  $db_opcao = 11;
  $db_botao = false;
}  

$anousu = db_getsession("DB_anousu");
$instit     = db_getsession("DB_instit");



if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  //rotina que verifica contrans
  $result01 = $clcontrans->sql_record(
                      $clcontrans->sql_query_file(
                             "",
      						"c45_seqtrans as c46_seqtrans",
      						null,
							"c45_anousu = $anousu and c45_coddoc = $c53_coddoc and c45_instit = $instit"));
  if($clcontrans->numrows == 0){
    $clcontrans->c45_anousu = $anousu;
    $clcontrans->c45_coddoc = $c53_coddoc;
    $clcontrans->c45_instit = $instit;
    $clcontrans->incluir(null);
    if($clcontrans->erro_status==0){
      $sqlerro=true;
    }else{
      $c46_seqtrans = $clcontrans->c45_seqtrans;
    } 
    $erro_msg = $clcontrans->erro_msg;
  }else{
    db_fieldsmemory($result01,0);
  }
  
  if($sqlerro==false){
    $clcontranslan->c46_seqtrans = $c46_seqtrans;
    $clcontranslan->incluir(null);
    if($clcontranslan->erro_status==0){
      $sqlerro=true;
    }else{
        $c46_seqtranslan = $clcontranslan->c46_seqtranslan;
    } 
    $erro_msg = $clcontranslan->erro_msg; 
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmcontranslan.php");
	?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clcontranslan->erro_campo!=""){
      echo "<script> document.form1.".$clcontranslan->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcontranslan->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("con1_contranslan005.php?liberaaba=true&chavepesquisa=$c46_seqtranslan");
  }
}
if(isset($c53_coddoc)){
 echo "
  <script>\n
      function js_db_tranca(){\n
         parent.document.formaba.contranslr.disabled=true;\n
      }   \n
       js_db_tranca();\n
    </script>\n
  ";	 
}
?>