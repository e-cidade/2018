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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_db_depart_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_db_almox_classe.php");
db_postmemory($HTTP_POST_VARS);
$clmatestoque       = new cl_matestoque;
$clmatestoqueitem   = new cl_matestoqueitem;
$clmatestoqueini    = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$cldb_depart        = new cl_db_depart;
$cldb_usuarios      = new cl_db_usuarios;
$cldb_almox         = new cl_db_almox;

$db_opcao = 22;
$db_botao = false;
$mostrapesquisa = true;
if(isset($chavepesquisa)){
  $chavepesquisa=(!empty($chavepesquisa))?$chavepesquisa:'null';
  $result_dadosaltexc = $clmatestoqueinimei->sql_record($clmatestoqueinimei->sql_query_matestoque(null," distinct matestoqueini.m80_codigo as valores,matestoqueini.m80_coddepto as departamentoorigem, m83_coddepto as departamentodestino",""," matestoqueini.m80_codigo=$chavepesquisa  "));
  if($clmatestoqueinimei->numrows>0){
    db_fieldsmemory($result_dadosaltexc,0);
    $db_opcao = 2;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.departamentodestino.focus()">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmmattransfdepto.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($chavepesquisa)){
  if($clmatestoqueinimei->numrows>0){
   echo "
   <script>
     top.corpo.iframe_itens.location.href = 'mat1_mattransfitens001.php?departamentodestino=$departamentodestino&departamentoorigem=$departamentoorigem&valores=$valores';
     parent.document.formaba.itens.disabled=false;
     parent.mo_camada('itens');
   </script>
   ";
  }
}
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>