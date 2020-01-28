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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$db_erro = "";
db_postmemory($HTTP_POST_VARS);
if(isset($confirma)){
  pg_exec("begin");
  $sql = "update numpref set k03_numsli = k03_numsli + 1 
          where k03_anousu = ".db_getsession('DB_anousu');
  pg_exec($sql);
  $db_erro = pg_ErrorMessage($conn);  
  if($db_erro==""){
    $result = pg_exec("select k03_numsli from numpref 
                       where k03_anousu = ".db_getsession('DB_anousu'));
    db_fieldsmemory($result,0);
	if($hist=="") $hist = 0;
      $sql = "insert into slip (k17_codigo,
                            k17_data,
							k17_debito,
							k17_credito,
							k17_valor,
							k17_hist,
							k17_texto,
							k17_instit)
                           values(".$k03_numsli.",
                                  '".date("Y/m/d",db_getsession("DB_datausu"))."',
								  ".$debito.",
								  ".$credito.",
								  ".$valor.",
								  ".$hist.",
								  '".$texto."',
     								  ".db_getsession("DB_instit") .
								  ")";
    pg_exec($sql); 
    $db_erro = pg_ErrorMessage($conn);  
    if($db_erro=="") {
	   pg_exec('commit');
	   $db_erro = "Código Incluído : ".$k03_numsli;
    }
	$k17_texto = $texto;
  }
}else{
  $k17_texto = 'Transferência entre Contas'; 
}

$result_conta1 = pg_exec("select 0 as c01_reduz,'Nenhuma...' as c01_descr,'' as c01_estrut
                          union
                          select c01_reduz,c01_descr,c01_estrut 
 	                      from plano 
						  where c01_reduz <> 0 and c01_anousu = ".db_getsession('DB_anousu').
						  "' order by c01_estrut");
if(pg_numrows($result_conta1) == 0){
  echo "<script>parent.alert('Sem Contas Cadastradas no Plano de Contas.');</script>";
  exit;
}
$result_conta2 = $result_conta1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_gravar(){
  if(document.form1.valor.value == ''){
    alert('valor Zerado.');
	return false;
  }
  if(document.form1.debito.value == document.form1.credito.value ){
    alert('Contas nao podem ser iguais.');
	return false;
  }
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top" bgcolor="#CCCCCC">
	  <?
	  include("forms/db_frmslip.php");
	  ?>
	</td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($db_erro!=""){
  echo "<script>alert('".$db_erro."')</script>";
}
?>