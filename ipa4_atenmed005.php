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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}-->
</style>
</head>

<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
  $sql = "  select aa01_nome,w12_descr, to_char(ag40_data,'DD-MM-YYYY') as ag40_data,ag40_hora ,ag40_press,ag40_pulso,ag40_diag,ag40_recei,ag40_obser 
                   from agenate				   				   
	  		       inner join atendmed
				   on ag40_codigo = ag30_codigo
				   inner join medicos
				   on aa01_codig = ag40_medico
				   inner join especial
				   on w12_codigo = ag40_espec
	   			   where ".(trim($dependente)!="0"?"ag30_depend = '$dependente'":"ag30_regist = '$funcionario'")."
				   and atendmed.ag40_codigo != $codigo
                   order by atendmed.ag40_data desc";				   
  
  if(!isset($HTTP_POST_VARS["antprox"])) {
    $result = pg_exec("select count(*) from ($sql) as pedro");
	$totreg = pg_result($result,0,0);
  }
  $sql .= " limit 1 offset ".(!isset($HTTP_POST_VARS["antprox"])?"0":$HTTP_POST_VARS["antprox"]);
  $result = pg_exec($conn,$sql);
  $numrows = pg_numrows($result);
  if($numrows == 0) {
    echo "<br><BR><Br><center><h3>Sem consultas.</h3></center>";
  } else {
    db_fieldsmemory($result,0); 
  ?>
<center>
<table width="80%" border="0" cellspacing="5" cellpadding="0">
  <tr> 
    <td width="22%"><strong>M&eacute;dico:</strong></td>
      <td width="27%" nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$aa01_nome?>
        &nbsp;</td>
    <td width="17%"><strong>Especialidade:&nbsp;</strong></td>
      <td width="34%" nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$w12_descr?>
        &nbsp;</td>
  </tr>
  <tr> 
    <td><strong>Data:</strong></td>
      <td nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$ag40_data?>
        &nbsp;</td>
    <td><strong>Hora:</strong></td>
      <td nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$ag40_hora?>
        &nbsp;</td>
  </tr>
  <tr> 
    <td><strong>Press&atilde;o:</strong></td>
      <td nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$ag40_press?>
        &nbsp;</td>
    <td><strong>Pulso:</strong></td>
      <td nowrap bgcolor="#FFFFFF">&nbsp; 
        <?=$ag40_pulso?>
        &nbsp;</td>
  </tr>
  <tr align="center"> 
    <td colspan="4">
	  <form name="form1" method="post">
        <input name="diagnostico" onClick="parent.js_abrir(this.form.diagR,0)" type="button" id="diagnostico2" value="Diagn&oacute;stico">
        <input name="diagR" type="hidden" id="diagR" value="<?=$ag40_diag?>">
        &nbsp; 
        <input name="receita" onClick="parent.js_abrir(this.form.receR,0)" type="button" id="receita" value="Receita">
        <input name="receR" type="hidden" id="receR" value="<?=$ag40_recei?>">
        &nbsp; 
        <input name="observacao" onClick="parent.js_abrir(this.form.obsR,0)" type="button" id="observacao" value="Observa&ccedil;&atilde;o">
        <input name="obsR" type="hidden" id="obsR" value="<?=$ag40_obser?>">
        &nbsp; 
        <input name="doencas" onClick="parent.js_abrir(this.form.doenR,0)" type="button" id="doencas" value="Doen&ccedil;as">
        <input name="doenR" type="hidden" id="doenR" value="do R">
		<input type="hidden" name="antprox" value="<? echo isset($HTTP_POST_VARS["antprox"])?$HTTP_POST_VARS["antprox"]:"0" ?>">
		<input type="hidden" name="totreg" value="<? echo isset($HTTP_POST_VARS["totreg"])?$HTTP_POST_VARS["totreg"]:$totreg ?>">
      </form>
	  <script>
	  if(document.form1.antprox.value != (document.form1.totreg.value - 1))
        parent.document.getElementById("anterior").disabled = false;
      </script>
	</td>
  </tr>
</table>
</center>
<?   
  }//fim do else do   if(pg_numrows($result) == 0) {
?>
</body>
</html>
<?
if(isset($DB_MSG)) {
  ?>
  <script>
    parent.alert('<?=$DB_MSG?>');
  </script>
  <?
}
?>