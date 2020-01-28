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
include("classes/db_vistorias_classe.php");
include("classes/db_vistlocal_classe.php");
include("classes/db_vistexec_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clvistorias = new cl_vistorias;
$clvistexec = new cl_vistexec;
$clvistlocal = new cl_vistlocal;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
$campos = "y70_codvist,y70_data,y70_numbloco";
$db_opcao = 3;
$db_botao = false;
$funcao_js = "js_retorna|0'";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_retorna(chave){
  document.location.href = 'fis3_consultavist002.php?y70_codvist='+chave;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<center>
<?
//echo "$cgm---------$matricula-----------$inscricao--------$sanitario";
$ordem="";
if(isset($y70_codvist) && (trim($y70_codvist)!="") ){
  $result = $clvistorias->sql_record($clvistorias->sql_query("","*","y70_codvist"," y70_codvist = $y70_codvist and y70_instit = ".db_getsession('DB_instit') ));
	if ($clvistorias->numrows > 0 ){
    db_fieldsmemory($result,0);
	}
}elseif(isset($cgm)&&$cgm!=""||isset($matricula)&&$matricula!=""||isset($inscricao)&&$inscricao!=""||isset($sanitario)&&$sanitario!=""){

  $where = "y70_instit = ".db_getsession('DB_instit');

  if (isset($cgm)&&$cgm!=""){
    $where .= "and y73_numcgm = $cgm";
  }else if(isset($matricula)&&$matricula!=""){
    $where .= "and y72_matric = $matricula";
  }else if(isset($inscricao)&&$inscricao!=""){
    $where .= "and y71_inscr = $inscricao";
  }else if (isset($sanitario)&&$sanitario!=""){
    $where .= "and y74_codsani = $sanitario";
  }
  $sql = ($clvistorias->sql_query_cons(null,"*","y70_codvist",$where));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
 <? 
}elseif(isset($numbloco) && $numbloco != ""){
  $sql=($clvistorias->sql_query("","*","y70_codvist $ordem"," y70_numbloco = '$numbloco' and y70_instit = ".db_getsession('DB_instit') )); 
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($dataini) && $dataini != "--" && $datafim == "--"){
  $sql = ($clvistorias->sql_query("","*","y70_codvist $ordem"," y70_data >= '$dataini' and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($dataini) && $dataini != "--" && isset($datafim) && $datafim != "--"){
  $sql = ($clvistorias->sql_query("","*","y70_codvist $ordem"," y70_data >= '$dataini' and y70_data <= '$datafim' and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($tipovist) && (trim($tipovist)!="") ){
  $sql = ($clvistorias->sql_query("","*","y70_codvist $ordem"," y70_tipovist = $tipovist and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($rua) && (trim($rua)!="") ){
  $sql = ($clvistlocal->sql_query("","*","y70_codvist $ordem"," y10_codigo = $rua and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($bairro) && (trim($bairro)!="") ){
  $sql = ($clvistlocal->sql_query("","*","y70_codvist $ordem"," y10_codi = $bairro and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($ruae) && (trim($ruae)!="") ){
  $sql = ($clvistexec->sql_query("","*","y70_codvist $ordem"," y11_codigo = $ruae and y70_instit = ".db_getsession('DB_instit') ));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultasani.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}elseif(isset($bairroe) && (trim($bairroe)!="") ){
  $sql = ($clvistexec->sql_query("","*","y70_codvist $ordem"," y11_codi = $bairroe and y70_instit = ".db_getsession('DB_instit')));
  ?>
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr> 
      <td align="center" valign="top">
	<? 
	  db_lovrot($sql,15,"()","",$funcao_js);
	?>
       </td>
     </tr>
    <tr> 
      <td align="center" valign="top">
	<input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultavist.hide();" > 
      </td>
    </tr>
  </table>
  <?
  exit;
}
  $resultlocal = $clvistexec->sql_record($clvistexec->sql_query(null,"*",null," y70_instit = ".db_getsession('DB_instit')." and y70_codvist = {$y70_codvist}")); 
  if($clvistexec->numrows > 0){
    db_fieldsmemory($resultlocal,0);
  }
  $resultexec = $clvistlocal->sql_record($clvistlocal->sql_query(null,"*",null," y70_instit = ".db_getsession('DB_instit')." and y70_codvist={$y70_codvist}")); 
  if($clvistlocal->numrows > 0){
    db_fieldsmemory($resultexec,0);
  }
  include("forms/db_frmvistorias.php");
  echo "<script>document.form1.db_opcao.type='hidden'</script>";
  echo "<script>document.form1.pesquisar.type='hidden'</script>";
?>
  <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_consultavist.hide();" > 
  <input type="button" name="imprimir" value="Imprimir" onclick="js_imprime('<?=$y70_codvist?>');" > 
  </center>
</form>
</body>
</html>
<script>
function js_imprime(chave){
    jan = window.open('fis2_relatoriovist002.php?y70_codvist='+chave+'&listatipo='+document.form1.y70_tipovist.value+'&consulta=1','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
}
</script>