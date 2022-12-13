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
include("dbforms/db_classesgenericas.php");
include("classes/db_conreltitulos_classe.php");
$clconreltitulos          = new cl_conreltitulos;
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clconreltitulos->rotulo->label();

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

$instit = db_getsession("DB_instit");
$anousu = db_getsession("DB_anousu");

if(isset($alterar) || isset($excluir) || isset($incluir)){
  $clconreltitulos->c44_lei        = $c44_lei;
  $clconreltitulos->c44_quantidade = $c44_quantidade;
  $clconreltitulos->c44_valemiss   = $c44_valemiss;
  $clconreltitulos->c44_saldo      = $c44_saldo;
  $clconreltitulos->c44_movemiss   = $c44_movemiss;
  $clconreltitulos->c44_movresgate = $c44_movresgate;
  $clconreltitulos->c44_saldoqtd   = $c44_saldoqtd;
  $clconreltitulos->c44_saldovalor = $c44_saldovalor;
  $clconreltitulos->c44_anousu     = $anousu;
  $clconreltitulos->c44_instit     = $instit;
}
if(isset($incluir)){
	db_inicio_transacao();

    $clconreltitulos->incluir($c44_sequencia);
	if($clconreltitulos->erro_status==0){
        $sqlerro=true;
    }

	db_fim_transacao($sqlerro);
	
    db_msgbox($clconreltitulos->erro_msg);
}else if(isset($alterar)){
	db_inicio_transacao();

    $clconreltitulos->alterar($c44_sequencia);
	if($clconreltitulos->erro_status==0){
        $sqlerro=true;
        $db_opcao=2;
    }

	db_fim_transacao($sqlerro);
	
    db_msgbox($clconreltitulos->erro_msg);
}else if(isset($excluir)){
	db_inicio_transacao();

    $clconreltitulos->excluir($c44_sequencia);
	if($clconreltitulos->erro_status==0){
        $sqlerro=true;
    }

	db_fim_transacao($sqlerro);
	
   db_msgbox($clconreltitulos->erro_msg);
}else if(isset($opcao)) {
	$result = $clconreltitulos->sql_record($clconreltitulos->sql_query($c44_sequencia));
	if($clconreltitulos->numrows>0) {
		db_fieldsmemory($result,0);		
	}
	if($opcao == "incluir") {
	 	$db_opcao = 1;
	}
	if($opcao == "alterar") {
	 	$db_opcao = 2;
	}
	if($opcao == "excluir") {
	 	$db_opcao = 3;
	}
}
if($db_opcao==1&&!$sqlerro) {
	$c44_sequencia  = "";		
	$c44_lei        = "";
	$c44_quantidade = "";
    $c44_valemiss   = "";
	$c44_saldo      = "";
	$c44_movemiss   = "";
	$c44_movresgate = "";
	$c44_saldoqtd   = "";
	$c44_saldovalor = "";
	$c44_anousu     = $anousu;
	$c44_instit     = $instit;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">

<form name="form1" method="post" action="" >
 <center>
 <table  align="center" border=0>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_sequencia?>">       
 	  <?=@$Lc44_sequencia?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_anousu',10,$Ic44_anousu,true,'hidden',3,"");
     	db_input('c44_instit',10,$Ic44_instit,true,'hidden',3,"");
     	db_input('c44_sequencia',10,$Ic44_sequencia,true,'text',3,"");
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_lei?>">       
 	  <?=@$Lc44_lei?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_lei',50,$Ic44_lei,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_quantidade?>">       
 	  <?=@$Lc44_quantidade?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_quantidade',50,$Ic44_quantidade,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_valemiss?>">       
 	  <?=@$Lc44_valemiss?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_valemiss',15,$Ic44_valemiss,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_saldo?>">       
 	  <?=@$Lc44_saldo?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_saldo',15,$Ic44_saldo,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_movemiss?>">       
 	  <?=@$Lc44_movemiss?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_movemiss',15,$Ic44_movemiss,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_movresgate?>">       
 	  <?=@$Lc44_movresgate?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_movresgate',15,$Ic44_movresgate,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_saldoqtd?>">       
 	  <?=@$Lc44_saldoqtd?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_saldoqtd',5,$Ic44_saldoqtd,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr>
 	<td nowrap align="right" title="<?=@$Tc44_saldovalor?>">       
 	  <?=@$Lc44_saldovalor?>
 	</td>
     <td align="left">
     <? 
     	db_input('c44_saldovalor',15,$Ic44_saldovalor,true,'text',$db_opcao,"")
     ?>
     </td>
 </tr>
 <tr><td colspan=2 align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
	  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
     </td>
 </tr>    
 
 <tr>
 <td colspan=2>
 <?
   $chavepri= array("c44_sequencia"=>@$c44_sequencia);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql   = $clconreltitulos->sql_query(null,"c44_sequencia,c44_lei,c44_quantidade,c44_valemiss,c44_saldo,c44_movemiss,c44_movresgate,c44_saldoqtd,c44_saldovalor","c44_sequencia desc","c44_anousu=$anousu and c44_instit=$instit");
   $cliframe_alterar_excluir->campos = "c44_sequencia,c44_lei,c44_quantidade,c44_valemiss,c44_saldo,c44_movemiss,c44_movresgate,c44_saldoqtd,c44_saldovalor";
   $cliframe_alterar_excluir->legenda= "DADOS";     
   $cliframe_alterar_excluir->iframe_height ="240";
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
 ?>
  </td>
 </tr>
 </table>
</center>
</form>
<?
if(isset($alterar) || isset($excluir) || isset($incluir)){
  if($sqlerro==true){
    if($clconreltitulos->erro_campo!=""){
      echo "<script> document.form1.".$clconreltitulos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clconreltitulos->erro_campo.".focus();</script>";
    };
  }
}
?>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>
</body>
</html>