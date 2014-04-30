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
include("classes/db_db_layouttxt_classe.php");
include("classes/db_db_layoutlinha_classe.php");
include("classes/db_db_layoutcampos_classe.php");
$cldb_layouttxt = new cl_db_layouttxt;
$cldb_layoutlinha = new cl_db_layoutlinha;
$cldb_layoutcampos = new cl_db_layoutcampos;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){
  $sqlerro=false;
  db_inicio_transacao();

  $result_linha = $cldb_layoutlinha->sql_record($cldb_layoutlinha->sql_query_file(null,"distinct db51_codigo as excluirlinha",""," db51_layouttxt = ".$db50_codigo));
  for($i=0; $i<$cldb_layoutlinha->numrows; $i++){
    db_fieldsmemory($result_linha, $i);
    $cldb_layoutcampos->excluir(null,"db52_layoutlinha = ".$excluirlinha);
    if($cldb_layoutcampos->erro_status==0){
      $erro_msg = $cldb_layoutcampos->erro_msg; 
      $sqlerro=true;
      break;
    }
  }

  if($sqlerro == false){
    $cldb_layoutlinha->excluir(null,"db51_layouttxt = ".$db50_codigo);
    if($cldb_layoutlinha->erro_status==0){
      $erro_msg = $cldb_layoutlinha->erro_msg; 
      $sqlerro=true;
    } 
  }

  if($sqlerro == false){
    $cldb_layouttxt->excluir($db50_codigo);
    $erro_msg = $cldb_layouttxt->erro_msg; 
    if($cldb_layouttxt->erro_status==0){
      $sqlerro=true;
    } 
  } 
  db_fim_transacao($sqlerro);
  $db_opcao = 3;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  $db_opcao = 3;
  $db_botao = true;
  $result = $cldb_layouttxt->sql_record($cldb_layouttxt->sql_query($chavepesquisa)); 
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_layouttxt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>
<?
if(isset($excluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_layouttxt->erro_campo!=""){
      echo "<script> document.form1.".$cldb_layouttxt->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_layouttxt->erro_campo.".focus();</script>";
    };
  }else{
    db_msgbox($erro_msg);
    echo "
     <script>
       function js_db_tranca(){
         parent.location.href='con1_db_layouttxt003.php';
       }\n
       js_db_tranca();
     </script>\n
    ";
  }
}
if($db_opcao==22||$db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>