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
include("classes/db_apolice_classe.php");
$clapolice = new cl_apolice;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;

if(isset($chavepesquisa) || isset($alterar)){
  $db_opcao = 2;
  $db_botao = true;
  if(isset($alterar)){
    $sqlerro=false;
    if($sqlerro==false){
      db_inicio_transacao();
      $clapolice->alterar($t81_codapo);
      if($clapolice->erro_status==0){
        $sqlerro=true;
      }
      $erro_msg = $clapolice->erro_msg;
      db_fim_transacao($sqlerro);
    }
  }
  if(isset($chavepesquisa)){
    $t81_codapo = $chavepesquisa;
  }
  $result = $clapolice->sql_record($clapolice->sql_query($t81_codapo));
  db_fieldsmemory($result,0);
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
	include("forms/db_frmapolice.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clapolice->erro_campo!=""){
      echo "<script> document.form1.".$clapolice->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clapolice->erro_campo.".focus();</script>";
    };
  }
}
if(isset($chavepesquisa)){
echo "
 <script>
     function js_db_libera(){
        parent.document.formaba.apolitem.disabled=false;
        top.corpo.iframe_apolitem.location.href='pat1_apolitem001.php?t82_codapo=".@$t81_codapo."';
    ";
        if(isset($liberaaba)){
          echo "  parent.mo_camada('apolitem');";
        }
echo"}\n
   js_db_libera();
 </script>\n
";
}
if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>