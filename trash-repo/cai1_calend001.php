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

if(isset($HTTP_POST_VARS["excluir"])) {
  $data = mktime(0,0,0,$HTTP_POST_VARS["data_mes"],$HTTP_POST_VARS["data_dia"],$HTTP_POST_VARS["data_ano"]);
  pg_exec("delete from calend where k13_data = '".date("Y-m-d",$data)."'") or die("Erro(9) excluindo calend");
  unset($HTTP_POST_VARS);
}

if(isset($HTTP_POST_VARS["sabdom"])) {
  $anoexe = $HTTP_POST_VARS["exercicio"];
  if(!preg_match("/[12][0-9][0-9][0-9]/",$anoexe) || preg_match("/[^0-9]/",$anoexe))
    db_erro("Exercício inválido");
  for($i = 1;$i <= 12;$i++) {
    $totdia = date("t",mktime(0,0,0,$i,1,$anoexe));
    for($j = 1;$j <= $totdia;$j++) {
	  $data = mktime(0,0,0,$i,$j,$anoexe);
	  if(date("w",$data) == "0" || date("w",$data) == "6") {
	    $result = pg_exec("select k13_data from calend where k13_data = '".date("Y-m-d",$data)."'");
		if(pg_numrows($result) == 0)
		  pg_exec("insert into calend values('".date("Y-m-d",$data)."')") or die("Erro(67)($i)($j) inserindo em calend");
	  }
	}
  }
  unset($HTTP_POST_VARS);
  $MSG = "Exercício $anoexe inserido com sucesso.";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_validasabdom() {
  var F = document.form1.exercicio;
  var str = F.value;
  var expr = /[12][0-9][0-9][0-9]/;
  var expr1 = /[^0-9]/;
  if(str == "") {
    alert("Informe o Exercício");
	F.focus();
	return false;
  }
  if(str.match(expr) == null || str.match(expr1)) {
    alert("Exercicio Inválido");
	F.select();
	return false; 
  }
}
function js_feriado() {
  var F = document.form1;
  if(F.data_dia.value == "" || F.data_mes.value == "" || F.data_ano.value == "") {
    alert("Informe a data");
	if(F.data_dia.value == "")
	  F.data_dia.focus();
    else
      F.data_dia.select();
	return false;
  }
}
function js_geracalendario(){
  window.open('cai2_calend002.php?anousu='+document.form1.exercicio.value,'','width=790,height=530,scrollbars=1,location=0');
}

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <form name="form1" method="post">
          <table width="41%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td height="30"><strong>Exerc&iacute;cio:</strong></td>
              <td height="30"><input name="exercicio" type="text" id="exercicio" value="<?=@$HTTP_POST_VARS["exercicio"]?>" size="4" maxlength="4"></td>
            </tr>
            <tr> 
              <td height="30">&nbsp;</td>
              <td height="30"><input name="sabdom" type="submit" onClick="return js_validasabdom()" id="sabdom" value="Incluir S&aacute;bados e Domingos"></td>
            </tr>
            <tr>
              <td height="30"><strong>Data:</strong></td>
              <td height="30">
			  <?
			  include("dbforms/db_funcoes.php");
			  db_inputdata("data",@$HTTP_POST_VARS["data_dia"],@$HTTP_POST_VARS["data_mes"],@$HTTP_POST_VARS["data_ano"],true,"text",1);
			  ?>
			  </td>
            </tr>
            <tr>
              <td height="30">&nbsp;</td>
              <td height="30"><input name="feriado" onClick="return js_feriado()" type="submit" id="feriado" value="Incluir Feriado">
                <input name="emite" onClick="return js_geracalendario()" type="button" id="emite" value="Emite Calend&aacute;rio"></td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
      <?
        db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
      ?>
</body>
</html>
<?
if(isset($HTTP_POST_VARS["feriado"])) {
  $data = mktime(0,0,0,$HTTP_POST_VARS["data_mes"],$HTTP_POST_VARS["data_dia"],$HTTP_POST_VARS["data_ano"]);
  $result = pg_exec("select k13_data from calend where k13_data = '".date("Y-m-d",$data)."'");
  if(pg_numrows($result) > 0) {
  ?>
    <script>
      if(confirm("Esta data já esta cadastrada, deseja exclui-la?")) {
        document.getElementById("feriado").name = "excluir";
		document.getElementById("feriado").click();
      } else
	    location.href = location.href;
    </script>
  <?		
  } else {
    pg_exec("insert into calend values('".date("Y-m-d",$data)."')");
	db_redireciona();
  }
}
if(isset($MSG))
  db_msgbox($MSG);
?>