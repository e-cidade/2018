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
include("classes/db_benstransfcodigo_classe.php");
include("classes/db_benstransf_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clbenstransfcodigo = new cl_benstransfcodigo;
$clbenstransf = new cl_benstransf;
$db_opcao = 22;
$db_botao = false;
if(isset($incluir)){
  $sqlerro = false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbenstransfcodigo->incluir($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  $sqlerro = false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbenstransfcodigo->alterar($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  $sqlerro = false;
  if($sqlerro==false){
    db_inicio_transacao();
    $clbenstransfcodigo->excluir($t95_codtran,$t95_codbem);
    if($clbenstransfcodigo->erro_status==0){
      $sqlerro=true;
    }
    $erro_msg = $clbenstransfcodigo->erro_msg;
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query($t95_codtran,$t95_codbem));
   if($result!=false && $clbenstransfcodigo->numrows>0){
     db_fieldsmemory($result,0);
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
	include("forms/db_frmbenstransfcodigo.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbenstransfcodigo->erro_campo.".focus();</script>";
    }else{
      echo "<script>
              top.corpo.iframe_benstransf.location.href='pat1_benstransf005.php?chavepesquisa=".@$t95_codtran."';
            </script>";
    }
}
?>