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
include("db_funcoes.php");
postmemory($HTTP_POST_VARS);
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
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
</script>
<script>
function js_maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_vericampos(){
  var alerta="";
  ruas=document.form1.ruas.value;
  numero=document.form1.numero.value;
  bairro=document.form1.bairro.value;
  if(ruas==""){
    alerta +="Rua\n";
  }
  if(numero==""){
    alerta +="Número\n";
  }
  if(bairro==""){
    alerta +="Bairro\n";
  }
  if(alerta!=""){
    alert("Verifique os seguintes campos:\n"+alerta);
    return false;
  }else{
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
  <tr>
    <td align="left" valign="top">
      <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top">
	   <form name="form1" method="post" action="enderecodae.php" onSubmit="return js_vericampos()">
	     <table width="100%" border="0" cellspacing="2" cellpadding="0">
	       <tr><br>
	         <td nowrap title="Endereço">
		   <strong>
		   Rua:
		   </strong>
		 </td>
		 <td>
		  <input type="hidden" size="40" maxlength="40" name="codigo" value="<?=@$codigo?>" >
		  <input type="hidden" size="40" maxlength="40" name="inscricaow" value="<?=@$inscricaow?>" >
		  <input type="text" size="40" maxlength="40" name="ruas" onKeyUp="js_maiusculo(this)">
		  <input type="button" name="xxx" class="botao" value="Pesquisa Ruas" onClick="js_ruas()">
	         </td>
               </tr>
	       <tr>
	         <td nowrap title="Número">
		   <strong>
		   Número: 
		   </strong>
		 </td>
		 <td>
		   <input type="text" name="numero" size="6" maxlength="6">
		   <strong>
		   Complemento: 
		   </strong>
		   <input type="text" name="complemento" size="10" maxlength="10">
	         </td>
               </tr>
	       <tr>
	         <td nowrap title="Bairro">
		   <strong>
		   Bairro:
		   </strong>
		 </td>
		 <td>
		   <input type="text" size="40" maxlength="40" name="bairro" onKeyUp="js_maiusculo(this)">
		  <input type="button" class="botao" name="yyy" value="Pesquisa Bairros" onClick="js_bairro()">
	         </td>
               </tr>
	       <tr align="center">
	         <td colspan="2"><br><br>
		   <input class="botao" type="submit" name="salvaender" value="Salvar">
		 </td>
	       </tr>
	     </table>  
	   </form>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_ruas(){
  func.location.href = 'func_ruas.php?funcao_js=js_mostraruas|0|1';
  document.getElementById('pesq').style.visibility = 'visible';
}
function js_bairro(){
  func.location.href = 'func_bairro.php?funcao_js=js_mostrabairro|0';
  document.getElementById('pesq').style.visibility = 'visible';
}
</script>
<?
if (isset($funcao)){
  if(isset($ruas)){
    echo "<script>document.form1.ruas.value = '$chave1 - $chave';</script>";
  }else{
    echo "<script>document.form1.bairro.value = '$bairro';</script>";
  }
}
if(isset($inscricaow) && !isset($salvaender)){
  $result = db_query("select * from db_daeend where w05_codigo = $codigo");
  if(pg_numrows($result) == 0){
    db_redireciona("enderecodae.php?".base64_encode('codigo='.$codigo));
  }else{
    db_fieldsmemory($result,0);
    echo"<script>document.form1.ruas.value = '".@$w05_rua."'</script>";
    echo"<script>document.form1.numero.value = '".@$w05_numero."'</script>";
    echo"<script>document.form1.complemento.value = '".@$w05_compl."'</script>";
    echo"<script>document.form1.bairro.value = '".@$w05_bairro."'</script>";
  }  
}
if(isset($salvaender)){
  $result = db_query("select * from db_daeend where w05_codigo = $codigo");
  if(pg_numrows($result) == 0){  
    $result = db_query("insert into db_daeend values($codigo,'$ruas',$numero,'$complemento','$bairro')");  
  }else{
    $result = db_query("update db_daeend set w05_codigo = $codigo, w05_rua = '$ruas', w05_numero = $numero, w05_compl = '$complemento', w05_bairro = '$bairro' where w05_codigo = $codigo ");  
  }
  db_redireciona("enderecodae.php?".base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo));
}
?>
<div id="pesq" style="position:absolute; top:0px; left:0px; z-index: 11; visibility: hidden; width:100%; height: 350px">
  <iframe name="func" frameborder="0" src="" width="100%" height="100%">
  </iframe
</div>
</body>
</html>