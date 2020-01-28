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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matrequi_classe.php");
include("classes/db_db_depusu_classe.php");
include("classes/db_matrequiitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
//if (substr($DB_BASE,0,5) != "ontem") {
//	  die("rotina indisponivel");
//}
$clmatrequi = new cl_matrequi;
$cldb_depusu = new cl_db_depusu;
$clmatrequiitem = new cl_matrequiitem;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  db_inicio_transacao();
  $sqlerro=false;
  $db_opcao = 2;
  $clmatrequi->alterar($m40_codigo);
  $erro_msg=$clmatrequi->erro_msg;
  if ($clmatrequi->erro_status==0){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $opcao = 2;
   $result = $clmatrequi->sql_record($clmatrequi->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   if ($m40_auto == "t"){
        $db_botao = false;
        db_msgbox("Esta requisicao nao pode ser alterada.");
   } else {
        $db_botao = true;
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
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmatrequi.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($chavepesquisa)){
    echo "<script>
               parent.iframe_matrequiitem.location.href='mat1_matrequiitemalt001.php?m40_codigo=".@$chavepesquisa."&m40_almox=".@$m40_almox."';\n
               parent.mo_camada('matrequiitem');
               parent.document.formaba.matrequiitem.disabled = false;\n
	 </script>";

}
if(isset($alterar)){
  if($clmatrequi->erro_status=="0"){
    $clmatrequi->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clmatrequi->erro_campo!=""){
      echo "<script> document.form1.".$clmatrequi->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatrequi->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
  };
};
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>