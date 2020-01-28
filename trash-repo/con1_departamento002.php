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

if (isset($HTTP_POST_VARS["alterar"])) {
   db_postmemory($HTTP_POST_VARS); 
   pg_exec("begin");
   pg_exec("UPDATE db_depart SET descrdepto='$descrdepto' WHERE coddepto=$coddepto") or die ("Erro: (10). Processo de alteracao.");
   pg_exec("UPDATE db_depart SET nomeresponsavel='$nomeresponsavel' WHERE coddepto=$coddepto") or die ("Erro: (11). Processo de alteracao.");
   pg_exec("UPDATE db_depart SET emailresponsavel='$emailresponsavel' WHERE coddepto=$coddepto") or die ("Erro: (12). Processo de alteracao.");
   pg_exec("end");
   db_redireciona();
} else 
  if (isset($HTTP_POST_VARS["excluir"])) {
    db_postmemory($HTTP_POST_VARS);
    $result = @pg_exec("delete from db_depart where coddepto=$coddepto");
	if (!$result) {
	  db_msgbox("Este departamento está sendo usado por outros registros. Não será possível sua exclusão.");
	} else {db_msgbox("Departamento excluído com sucesso.");}
	db_redireciona();
  }
else 
  if (isset($HTTP_POST_VARS["cancelar"])) {
    db_redireciona();
  
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script>
function js_verificaFormulario() {
  var form = document.form1;
  if (form.descrdepto.value=="") {
    alert("O campo descricao nao pode estar vazio!");
	form.descrdepto.focus();
	return false;
  }
  return true;
}
</script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <? 
	  if (!isset($HTTP_POST_VARS["procurar"])) {
	    include("forms/db_frmdepartamento002.php"); 
	  } else { ?>
    <p><a href="con1_departamento002.php" style="font-size:13px" > << voltar a pagina anterior.</a></p><br>
    <table width="80%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="8%" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center"><strong>Codigo</strong></td>
            <td width="92%" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center"><strong>Descri&ccedil;&atilde;o</strong></td>
          </tr>
          <tr> 
		    <?
			  db_postmemory($HTTP_POST_VARS);
			  $result = pg_exec("select * from db_depart where descrdepto like '".$descrdepto."%'");
			  $num = pg_numrows($result);
			  for ($i=0;$i<$num;$i++) {
			?>
			<tr  style="cursor:hand"  onClick="location.href='con1_departamento002.php?retornoCod=<? echo pg_result($result,$i,"coddepto") ?>&retornoDescr=<? echo pg_result($result,$i,"descrdepto")?>&retornonomeresponsavel= <? echo pg_result($result,$i,"nomeresponsavel") ?>&retornoemailresponsavel= <? echo pg_result($result,$i,"emailresponsavel")?>'" title="Clique aqui">
              <td style="text-decoration:none;color:#000000;font-size:13px" bgcolor=<? echo $i%2==0?"#97B5E6":"#E796A4" ?> nowrap><? echo pg_result($result,$i,"coddepto"); ?>
			  </td>
              <td style="text-decoration:none;color:#000000;font-size:13px" bgcolor=<? echo $i%2==0?"#97B5E6":"#E796A4" ?> nowrap><? echo pg_result($result,$i,"descrdepto"); ?></td>
			</tr>
			<?
			  }
			?>
          </tr>
         </table>	  
	<?
	 }
	?>
	</td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>