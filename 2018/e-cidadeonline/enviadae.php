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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
		       WHERE m_arquivo = 'digitadae.php'
		       ORDER BY m_descricao
		       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");
postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_confirma(){
  alerta='';
  nome=document.form1.responsavel.value;
  tel=document.form1.telcontato.value;
  if(nome==""){
    alerta +="Responsável\n";
  }
  if(tel==""){
    alerta +="Telefone de contato\n";
  }
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
    var confirma = confirm("Confirma o envio da DAI?\nApós o envio os dados não poderão mais ser alterados!");
    if(confirma==true)
      return true;
  }
return false;  
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
mens_div();
?>
<center>
<table align"center" width="760" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
<tr>
    <td align="left" valign="top">
      <table width="100%" height="200" border="0" cellpadding="0" cellspacing="0">
      <form name="form1" action="enviadae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo)?>" method="post" >
        <tr class="titulo2" >
          <td align="center" valign="top"><br><br>
	    Informe o responsável e o telefone de contato nos campos abaixo<br><br><br>
	    Responsável:
	    <input type="text" name="responsavel" value="" size="40" maxlength="40" onKeyUp="js_maiusculo(this)">
	    Telefone:
	    <input type="text" name="telcontato" value="" size="10" maxlength="10" onKeyUp="js_maiusculo(this)">
          </td>
        </tr>
        <tr class="titulo2" >
          <td align="center" valign="top"><br><br>
	   <strong> Após o envio da DAI você não poderá mais alterá-la,<br>
	    confira todos os dados salvos.</strong> 
          </td>
        </tr>
	<tr valign="top">
	  <td align="center">
	    <input type="submit" name="ver_rel" value="Verificar relatório antes de enviar" class="botao"> 
	    <input type="submit" name="enviadae" value="Enviar DAI" class="botao" onclick="return js_confirma()">
	  </td>
	</tr>
      </form>  
      </table>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<?
if(isset($enviadae)){
  $data = date("Y-m-d");
  db_query("update db_dae set w04_enviado = 't', w04_resp = '$responsavel', w04_telcontato = '$telcontato' where w04_codigo = $codigo");
  db_query("update db_dae set w04_data = '$data' where w04_codigo = $codigo");
  echo "<script>var relatorio = confirm(\"Deseja imprimir o relatório da DAI informada?\");
                  if(relatorio == true){
                  window.open('daerelatorio.php?codigo=$codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }</script>";  
  echo "<script>alert('DAI enviada com sucesso!')</script>";
  echo "<script>parent.document.location.href = 'digitadae.php'</script>";
  exit;
}

if(isset($ver_rel)){
  $data = date("Y-m-d");
  echo "<script>var relatorio = confirm(\"Deseja imprimir o relatório da DAI informada?\");
                  if(relatorio == true){
                  window.open('daerelatorio2.php?codigo=$codigo','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    }</script>";  

  //echo "<script>parent.document.location.href = 'digitadae.php'</script>";
  exit;
}

?>