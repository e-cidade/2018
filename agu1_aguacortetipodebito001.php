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
include("classes/db_aguacortetipodebito_classe.php");
include("classes/db_aguacorte_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claguacortetipodebito = new cl_aguacortetipodebito;
$claguacorte = new cl_aguacorte;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$claguacortetipodebito->x45_codcortetipodebito = $x45_codcortetipodebito;
$claguacortetipodebito->x45_codcorte = $x45_codcorte;
$claguacortetipodebito->x45_tipo = $x45_tipo;
$claguacortetipodebito->x45_parcelas = $x45_parcelas;
$claguacortetipodebito->x45_dtvenc = $x45_dtvenc;
$claguacortetipodebito->x45_vlrminimo = $x45_vlrminimo;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortetipodebito->incluir($x45_codcortetipodebito);
    $erro_msg = $claguacortetipodebito->erro_msg;
    if($claguacortetipodebito->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortetipodebito->alterar($x45_codcortetipodebito);
    $erro_msg = $claguacortetipodebito->erro_msg;
    if($claguacortetipodebito->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortetipodebito->excluir($x45_codcortetipodebito);
    $erro_msg = $claguacortetipodebito->erro_msg;
    if($claguacortetipodebito->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $sql = $claguacortetipodebito->sql_query($x45_codcortetipodebito);
   //echo $sql;
   $result = $claguacortetipodebito->sql_record( $sql );
   if($result!=false && $claguacortetipodebito->numrows>0){
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
	include("forms/db_frmaguacortetipodebito.php");
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
    if($claguacortetipodebito->erro_campo!=""){
        echo "<script> document.form1.".$claguacortetipodebito->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$claguacortetipodebito->erro_campo.".focus();</script>";
    }
}
?>