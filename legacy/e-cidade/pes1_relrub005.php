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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_relrub_classe.php"));
include(modification("classes/db_relrubmov_classe.php"));
include(modification("classes/db_selecao_classe.php"));
$clrelrub = new cl_relrub;
$clrelrubmov = new cl_relrubmov;
$clselecao = new cl_selecao;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  $clrelrub->alterar($rh45_codigo);
  if($clrelrub->erro_status==0){
    $sqlerro=true;
  } 
  $erro_msg = $clrelrub->erro_msg; 
  db_fim_transacao($sqlerro);
   $db_opcao = 2;
   $db_botao = true;
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   $result = $clrelrub->sql_record($clrelrub->sql_query($chavepesquisa)); 
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
	    <?php include(modification("forms/db_frmrelrub.php")); ?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrelrub->erro_campo!=""){
      echo "<script> document.form1.".$clrelrub->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrelrub->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
  }
}
if(isset($chavepesquisa)){
  
 echo "<script>                                                                                                          \n ";
 echo "  function js_db_libera(){                                                                                        \n ";
 echo "     parent.document.formaba.relrubmov.disabled    = false;                                                       \n ";
 echo "     parent.document.formaba.relrubcampos.disabled = false;                                                       \n ";
 echo "     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relrubmov.location.href      = 'pes1_relrubmov001.php?rh46_codigo="    . @$rh45_codigo . "'; \n ";
 echo "     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relrubcampos.location.href   = 'pes1_relrubcampos001.php?rh45_codigo=" . @$rh45_codigo . "&db_opcao=" . $db_opcao . "';  \n ";

 if( isset($liberaaba) ) {
   echo "  parent.mo_camada('relrubmov');";
 }

 echo "  }                                                                                                               \n ";
 echo "  js_db_libera()                                                                                                  \n ";
 echo "</script>                                                                                                         \n ";
}
if( $db_opcao == 22 || $db_opcao == 33 ) {
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
