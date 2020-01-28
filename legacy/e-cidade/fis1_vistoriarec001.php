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
include("classes/db_vistoriarec_classe.php");
include("classes/db_tipovistoriasrec_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clvistoriarec = new cl_vistoriarec;
$cltipovistoriasrec = new cl_tipovistoriasrec;
$db_opcao = 1;
$db_botao = true;
global $y76_codvist;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $clvistoriarec->incluir($y76_codvist,$y76_receita);
  db_fim_transacao();
}
if(isset($y76_codvist) && $y76_codvist != ""){
  $result = $clvistoriarec->sql_record($clvistoriarec->sql_query_file($y76_codvist));
  if($clvistoriarec->numrows == 0){
    $result = $cltipovistoriasrec->sql_record($cltipovistoriasrec->sql_query_tipovist("","","*",""," vistorias.y70_codvist = $y76_codvist"));
    if($cltipovistoriasrec->numrows > 0){
      db_inicio_transacao();
      $numrows = $cltipovistoriasrec->numrows;
      for($i=0;$i<$numrows;$i++){
	db_fieldsmemory($result,$i);
	$clvistoriarec->y76_valor = $y78_valor;
	$clvistoriarec->y76_descr = $y78_descr;
	$clvistoriarec->incluir($y70_codvist,$y78_receit);
      }
      db_fim_transacao();
    }
  }
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
	include("forms/db_frmvistoriarec.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
  js_setatabulacao();
</script>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clvistoriarec->erro_status=="0"){
    $clvistoriarec->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clvistoriarec->erro_campo!=""){
      echo "<script> document.form1.".$clvistoriarec->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clvistoriarec->erro_campo.".focus();</script>";
    };
  }else{
    $clvistoriarec->erro(true,false);
    echo "<script>parent.iframe_receitas.location.href='fis1_vistoriarec001.php?y76_codvist=$y76_codvist';</script>";
  };
};
?>