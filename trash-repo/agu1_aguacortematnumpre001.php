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
include("classes/db_aguacortematnumpre_classe.php");
include("classes/db_aguacortemat_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claguacortematnumpre = new cl_aguacortematnumpre;
$claguacortemat = new cl_aguacortemat;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar) || isset($excluir) || isset($incluir)){
  $sqlerro = false;
  /*
$claguacortematnumpre->x44_codcortematnumpre = $x44_codcortematnumpre;
$claguacortematnumpre->x44_codcortemat = $x44_codcortemat;
$claguacortematnumpre->x44_numpre = $x44_numpre;
$claguacortematnumpre->x44_numpar = $x44_numpar;
$claguacortematnumpre->x44_dtvenc = $x44_dtvenc;
$claguacortematnumpre->x44_tipo = $x44_tipo;
$claguacortematnumpre->x44_vlrhis = $x44_vlrhis;
$claguacortematnumpre->x44_vlrcor = $x44_vlrcor;
$claguacortematnumpre->x44_juros = $x44_juros;
$claguacortematnumpre->x44_multa = $x44_multa;
$claguacortematnumpre->x44_desconto = $x44_desconto;
  */
}
if(isset($incluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortematnumpre->incluir($x44_codcortematnumpre);
    $erro_msg = $claguacortematnumpre->erro_msg;
    if($claguacortematnumpre->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortematnumpre->alterar($x44_codcortematnumpre);
    $erro_msg = $claguacortematnumpre->erro_msg;
    if($claguacortematnumpre->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($excluir)){
  if($sqlerro==false){
    db_inicio_transacao();
    $claguacortematnumpre->excluir($x44_codcortematnumpre);
    $erro_msg = $claguacortematnumpre->erro_msg;
    if($claguacortematnumpre->erro_status==0){
      $sqlerro=true;
    }
    db_fim_transacao($sqlerro);
  }
}else if(isset($opcao)){
   $result = $claguacortematnumpre->sql_record($claguacortematnumpre->sql_query($x44_codcortematnumpre));
   if($result!=false && $claguacortematnumpre->numrows>0){
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
	include("forms/db_frmaguacortematnumpre.php");
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
    if($clpagordemrec->erro_campo!=""){
        echo "<script> document.form1.".$claguacortematnumpre->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$claguacortematnumpre->erro_campo.".focus();</script>";
    }
}
?>