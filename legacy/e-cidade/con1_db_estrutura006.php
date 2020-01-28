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
include("classes/db_db_estrutura_classe.php");
include("classes/db_db_estruturanivel_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

/*
   //       1.9.2.1.2.0.2.00.03.00.00
$codigo  = '1.9.2.2.1.1.0.00.00.00.00';
$mascara = '0.0.0.0.0.0.0.00.00.00.00';
$clestrutural = new  cl_estrutural;

 $clestrutural->db_estrut_inclusao($codigo,$mascara,"conplano","c60_estrut");
 db_msgbox($clestrutural->erro_msg);
    

die();
*/
$cldb_estrutura = new cl_db_estrutura;
$cldb_estruturanivel = new cl_db_estruturanivel;
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();
  $db_opcao = 3;
   
  $result = $cldb_estrutura->sql_record($cldb_estruturanivel->sql_query_file($db77_codestrut)); 
  if($cldb_estrutura->numrows>0){
    $cldb_estruturanivel->excluir($db77_codestrut);
    $erro_msg=$cldb_estruturanivel->erro_msg;
    if($cldb_estruturanivel->erro_status==0){
      $sqlerro=true;
    }
  }
   
  $cldb_estrutura->excluir($db77_codestrut);
  if($cldb_estrutura->erro_status==0){
    $sqlerro=true;
  }
  $erro_msg=$cldb_estrutura->erro_msg;
  db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cldb_estrutura->sql_record($cldb_estrutura->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
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
	include("forms/db_frmdb_estrutura.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
 if(isset($chavepesquisa)){
    echo "
          <script>
	      parent.document.formaba.db_estruturanivel.disabled=false;\n
	      top.corpo.iframe_db_estruturanivel.location.href='con1_db_estruturanivel001.php?db78_codestrut=$db77_codestrut';\n
             // parent.mo_camada('db_estruturanivel');
	     // location.href='con1_db_estrutura006.php?chavepesquisa=$db77_codestrut';
          </script>
    ";
}    
if(isset($excluir)){
  db_msgbox($erro_msg);;
    db_redireciona("con1_db_estrutura006.php");
}
if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>