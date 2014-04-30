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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

if(isset($campodefault)){
  $retorno = $campodefault;
}
$processamento = 0;
if(isset($retorno)){
  $sql = "select m.codmod,a.codarq as tabela,c.codcam,c.nomecam,c.conteudo,c.descricao,c.rotulo,c.valorinicial,c.tamanho,c.nulo,c.maiusculo,c.autocompl,c.aceitatipo,c.tipoobj,c.rotulorel
          from db_syscampo c
		  left outer join db_sysarqcamp ac
		  on ac.codcam = c.codcam
		  left outer join db_sysarquivo a
		  on a.codarq = ac.codarq
		  left outer join db_sysarqmod m
		  on m.codarq = a.codarq
		  where c.codcam = $retorno";
  $result = pg_exec($sql);
  if(isset($HTTP_POST_VARS["modulo"]) && $HTTP_POST_VARS["modulo"] == "")
    $HTTP_POST_VARS["modulo"] = pg_result($result,0,"codmod");
  if(isset($HTTP_POST_VARS["tabela"]) && $HTTP_POST_VARS["tabela"] == "")
    $HTTP_POST_VARS["tabela"] = pg_result($result,0,"tabela");
  db_fieldsmemory($result,0);

  if(isset($campodefault)){
    $campdes=$nomecam;
    $codcampai = $retorno;
    unset($retorno);
    unset($nomecam);
  }
}
//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  switch($conteudo) {
    case "char":
    case "varchar":
      if($tamanho == "") {
        db_msgbox("Campo tipo nao pode ser vazio para $tipo");
        db_redireciona();
        break;
      } else {
        $conteudo .= "($tamanho)";
	    break;
      }
  }
  if($tamanho == "") {
    $tamanho = 0;
  }
  
  if(isset($vnulo)) $nulo = 't';
  else $nulo = 'f';
  if(isset($vmaiusculo)) $maiusculo = 't';
  else $maiusculo = 'f';
  if(isset($vautocompl)) $autocompl = 't';
  else $autocompl = 'f';
  $result = pg_exec("select nextval('db_syscampo_codcam_seq')");
  $codcam = pg_result($result,0,0);
  if((substr($conteudo,0,4)=="date") && empty($valorinicial)){
    $valorinicial = "null";
  } 
  if((substr($conteudo,0,3)=="int") && empty($valorinicial)){
    $valorinicial = "0";
  } 
  if((substr($conteudo,0,5)=="float") && empty($valorinicial)){
    $valorinicial = "0";
  } 
  if((substr($conteudo,0,3)=="boo") && empty($valorinicial)){
    $valorinicial = "f";
  } 

  $result = pg_query("select nomecam from db_syscampo where nomecam ='$nomecam'");
  $numrows = @pg_num_rows($result);
  if($numrows>0){
    $processamento = "4";
    $erro_msg      = "Inclusão abortada! \\n O campo $nomecam já foi incluido!";
  }else{  
      pg_exec("BEGIN");
      pg_exec("insert into db_syscampo 
	       values($codcam,'$nomecam','$conteudo','$descricao','$valorinicial',
			      '$rotulo',$tamanho,'$nulo','$maiusculo','$autocompl',$aceitatipo,'$tipoobj','$rotulorel')") or die("Erro(43) inserindo em db_syscampo");
      if($codcampai!=0){
	 pg_exec("insert into db_syscampodep
		  values($codcam,'$codcampai')") or die("Erro(43) inserindo em db_syscampodep");
      }
      if(isset($itensdef)){
	$numArray = sizeof($itensdef);
	for($i = 0;$i < $numArray;$i++) {
	  $aux = split("#&",$itensdef[$i]);
	      pg_exec("insert into db_syscampodef values(".$codcam.",'".$aux[0]."','".$aux[1]."')") or die("Erro(44) inserindo em db_syscampodef");
	}
      }
      pg_exec("END");
      $processamento = 1;
       unset($nomecam,$conteudo,$tamanho,$descricao,$rotulo,$valorinicial,$codcampai);
  }    
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  if(isset($vnulo)){
     $nulo = 't';
  }else{
     $nulo = 'f';
  }
  if(isset($vmaiusculo)){
     $maiusculo = 't';
  }else{
     $maiusculo= 'f';
  }
  if(isset($vautocompl)){
     $autocompl= 't';
  }else{
     $autocompl= 'f';
  }
  pg_exec("BEGIN");
  if($conteudo == "char" || $conteudo == "varchar")
    if($tamanho == "")
	  db_erro("Tamanho do tipo não pode ser vazio");
	else
      $conteudo .= "(".$tamanho.")";
  if($tamanho == ""){
     $tamanho = 0;
  }
  if((substr($conteudo,0,4)=="date") && empty($valorinicial)){
    $valorinicial = "null";
  } 
  if((substr($conteudo,0,3)=="int") && empty($valorinicial)){
    $valorinicial = "0";
  } 
  if((substr($conteudo,0,5)=="float") && empty($valorinicial)){
    $valorinicial = "0";
  } 
  if((substr($conteudo,0,3)=="boo") && empty($valorinicial)){
    $valorinicial = "f";
  } 

  $pode_ir=true;
  
  $result05 = pg_query("select codcam as codcamal from db_syscampo where nomecam ='$nomecam'");
  $numrows = @pg_num_rows($result);
  if($numrows>0){
    db_fieldsmemory($result05,0);
    if($codcam!=$codcamal){
      $processamento = "4";
      $erro_msg      = "Alteração abortada! \\n O campo $nomecam já foi incluido!";
    }   
  }
  if($pode_ir==true){  
    pg_exec("update db_syscampo set nomecam  = '$nomecam',
						    conteudo = '$conteudo',
				    descricao     = '$descricao',
								    valorinicial = '$valorinicial',
								    rotulo  = '$rotulo',
								    nulo   = '$nulo',
								    tamanho = $tamanho,
								    maiusculo = '$maiusculo',
								    autocompl = '$autocompl',
								    aceitatipo = $aceitatipo,
				    tipoobj    = '$tipoobj',
				    rotulorel  = '$rotulorel'
		     where codcam = $codcam") or die("Erro(65) inserindo em db_syscampo");

    pg_exec("delete from db_syscampodep where codcam = $codcam") or die("Erro(43) Alterando em db_syscampodep");
    if($codcampai!=0){
       pg_exec("insert into db_syscampodep
		values($codcam,'$codcampai')") or die("Erro(43) inserindo em db_syscampodep");
    }

    pg_exec("delete from db_syscampodef where codcam = $codcam") or die("Erro(44) Alterando em db_syscampodef");
    if(isset($itensdef)){
      $numArray = sizeof($itensdef);
      for($i = 0;$i < $numArray;$i++) {
	$aux = split("#&",$itensdef[$i]);
	    pg_exec("insert into db_syscampodef values(".$codcam.",'".$aux[0]."','".$aux[1]."')") or die("Erro(44) inserindo em db_syscampodef");
      }
    }
    $processamento = 2;
  } 
  pg_exec("END");
//  db_redireciona();
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("BEGIN");
  pg_exec("delete from db_syscampodef where codcam = $codcam") or die("Erro(44) Excluindo em db_syscampodef");
  pg_exec("delete from db_syscampodep where codcam = $codcam") or die("Erro(43) Excluindo em db_syscampodep");
  pg_exec("delete from db_syscampo    where codcam = $codcam") or die("Erro(72) Excluindo em db_syscampo");			
  pg_exec("END");
  $processamento = 3;
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao != 'procurar') {  	
    if(obj.nomecam.value == "") {
      alert("Campo nome do campo é obrigatório");
	  obj.nomecam.focus();
	  return false;
    }

        if(obj.conteudo.options[obj.conteudo.selectedIndex].value == "") {
	  alert("Escolha um tipo para continuar.");
	  obj.conteudo.focus();
	  return false;
	}
	if(obj.conteudo.options[obj.conteudo.selectedIndex].value == "varchar" || obj.conteudo.options[obj.conteudo.selectedIndex].value == "char") {
      if(obj.tamanho.value == "") {
	    alert("Campo tamanho não pode ser vazio para tipo char ou varchar.");
	    obj.tamanho.focus();
	    return false;
	  }
	}
	if(obj.descricao.value == "") {
      alert("Campo descrição é obrigatório");
	  obj.descricao.focus();
	  return false;
    }
  }
  obj.elements["itensdef[]"].multiple = true;	  
  for(var i = 0;i < obj.itensdef.length;i++)
    obj.itensdef.options[i].selected = true;
  return true;
}
</script>
<script language="JavaScript">
function js_valida(valor) {
   document.form1.tamanho.focus();
   document.form1.vmaiusculo.disabled=true;
   document.form1.vmaiusculo.checked=false;
   if(valor=='int4' || valor=='int8' || valor=='date' || valor=='oid'){
     if(valor=='oid'){
       document.form1.tamanho.value='1';
     }  
     if(valor=='date'){
       document.form1.tamanho.value='10';
     }  
     document.form1.aceitatipo.options[1].selected=true;   
     return true;
   }  
   if(valor=='char' || valor=='varchar' || valor=='text'){
     document.form1.vmaiusculo.checked=true;
     document.form1.vmaiusculo.disabled=false;
     document.form1.aceitatipo.options[0].selected=true;   
     if(valor=='text'){
       document.form1.tamanho.value='1';
     }  
     return true;
   } 
   if(valor=='bool'){
     document.form1.aceitatipo.options[5].selected=true;   
     document.form1.tamanho.value='1';
     return true;
     
   }  
   if(valor=='float4' || valor=='float8'){
     document.form1.aceitatipo.options[4].selected=true;   
     return true;
   } 
   document.form1.aceitatipo.options[valor].selected=true;   
   return true;
	
}
function js_iniciar() {
  if(document.form1) {
    //if(document.form1.tamanho.value.length > 0)
      //document.form1.tamanho.disabled = false;
    document.form1.nomecam.focus();
  }
}
</script>
<script>
function js_adddef(obj) {
  if(obj.textodef.value == "") {
    alert("Campo não pode ser vazio!");
	obj.textodef.focus();
	return false;
  }
  obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].length] = new Option(obj.textodef.value,obj.textodef.value + '#&' + obj.descitensdef.value);
  obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].length-1].select = true; 
  js_trocacordeselect();
  obj.textodef.value = "";
  obj.descitensdef.value = "";
  obj.textodef.focus();
}
function js_mostradef(obj) {
  var mat = new String(obj.options[obj.selectedIndex].value);
  mat = mat.split("#&");
  document.form1.textodef.value = mat[0];
  document.form1.descitensdef.value = mat[1];
  document.form1.adicionar.disabled = true;
  document.form1.retirar.disabled = false;
  document.form1.alterardef.disabled = false;
}
function js_alterardef(obj) {
  document.form1.adicionar.disabled = false;
  document.form1.retirar.disabled = true;
  document.form1.alterardef.disabled = true;
  obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex].text = obj.textodef.value; 
  obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex].value = obj.textodef.value + '#&' + obj.descitensdef.value;
  obj.textodef.value = "";
  obj.descitensdef.value = "";
  obj.textodef.focus();  
}
function js_remdef(obj) {
  if(!confirm("Excluir Item Default?"))
    return false;
  obj.elements["itensdef[]"].options[obj.elements["itensdef[]"].selectedIndex] = null;
  js_trocacordeselect();
  document.form1.adicionar.disabled = false;
  document.form1.retirar.disabled = true;
  document.form1.alterardef.disabled = true;
  obj.textodef.value = "";
  obj.descitensdef.value = "";
  obj.textodef.focus();  
}
function js_verifica(){
  if(document.form1.conteudo.value==0){
    alert('Selecione o tipo do campo!');
    return false;
  }
  var tam = new Number(document.form1.tamanho.value);
  if(isNaN(tam) || tam==''){
    alert('Verifique o tamanho do campo!');
    document.form1.tamanho.focus();
    return false;
  }  
  return true;
}
</script>

<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar();js_trocacordeselect()">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" align='center' border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<?
/*      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
	    if(isset($HTTP_POST_VARS["filtro"]))
		  $str = $HTTP_POST_VARS["filtro"];
		else
		  $str = $HTTP_POST_VARS["nomecam"];
        $sql = "SELECT c.codcam as db_codcam,c.codcam as Código,c.nomecam as Nome, c.conteudo as tipo,c.descricao as descrição
                   FROM db_syscampo c
                   WHERE c.nomecam like '".$str."%'
                   ORDER BY c.nomecam";
        db_lov($sql,15,"sys1_campos001.php",$str);
	//  } else {
	  */
	 ?>
	 
  <form method="post" name="form1" onSubmit="return js_submeter(this)">
  <br>
  <input type="hidden" name="codcam" value="<?=@$codcam?>">
        <table width="439">

          <tr> 
            <td nowrap><strong>Campo Principal:</strong></td>
	    <script>
	    function js_buscadefault(valor){
	      if(document.form1.incluir.disabled==false && valor!=0){
                location.href="sys1_campos001.php?campodefault="+valor;
	      }
	    }
	    
	    </script>
            <td> <select name="codcampai" size="1" onchange="js_buscadefault(this.value)">
                <option value='0'>Campo Principal...</option>
                <?
                if(isset($campodefault)){
                    echo "<option selected  value='$retorno'>$campdes</option>";
		}
                if(isset($codcam)){
                  $sql="Select nomecam as nom,codcampai as pai from db_syscampodep inner join db_syscampo on db_syscampo.codcam=codcampai  where db_syscampodep.codcam = $codcam";
                  $result = pg_query($sql);
  	          if(pg_numrows($result)>0){
		    db_fieldsmemory($result,0);
                     echo "<option selected value='$pai'>$nom</option>";
		  }   
               	}	    
		/*
		  if(!isset($codcampai)){
                     $result = pg_exec("Select codcampai from db_syscampodep where codcam = $codcam");
		     if(pg_numrows($result)>0){
		       $codcampai = pg_result($result,0,'codcampai');
		     }else{
		       $codcampai = 0;
		     }
		  }
                  $result = pg_exec("Select codcam,nomecam from db_syscampo where not nomecam like 'DB_%' order by nomecam");
				  for($ic=0;$ic<pg_numrows($result);$ic++){
                    echo "<option ".(pg_result($result,$ic,'codcam')==$codcampai?"selected":"")." value='".pg_result($result,$ic,'codcam')."'>".pg_result($result,$ic,'nomecam')."</option>";
			 	  }
                */				  
				?>
              </select>
	      </td>
	      <td>
	      <input name="docum_principal" value="Campo Principal" type="button" onclick="js_documentacao_iframe()"> 
	      </td>
          </tr>

	
          <tr> 
            <td width="92"><strong>Nome/Tipo:</strong></td>
            <td width="302"> <input type="text" name="nomecam" value="<?=@$nomecam?>"> 
              <?
	  if(isset($conteudo)) {
	    $v_tipo = split('\(',$conteudo);
		$conteudo = $v_tipo[0];
		if(isset($v_tipo[1])){
		    $v = split("\)",$v_tipo[1]);
		}else{
		  $v = "";
		}
		//$tamanho = $v[0];
	  }
	  ?>
              <select name="conteudo" size="1" OnChange="js_valida(this.value)">
                <option value="0" >Tipos</option>
                <option value="varchar" <? echo @$conteudo=="varchar"?"selected":"" ?>>Varchar</option>
                <option value="text" <? echo @$conteudo=="text"?"selected":"" ?>>Text</option>
                <option value="oid" <? echo @$conteudo=="oid"?"selected":"" ?>>Oid</option>
                <option value="int4" <? echo (@$conteudo=="int4" || @$conteudo=="integer")?"selected":"" ?>>Int4</option>
                <option value="int8" <? echo @$conteudo=="int8"?"selected":"" ?>>Int8</option>
                <option value="float4" <? echo @$conteudo=="float4"?"selected":"" ?>>Float4</option>
                <option value="float8" <? echo @$conteudo=="float8"?"selected":"" ?>>Float8</option>
                <option value="bool" <? echo @$conteudo=="bool"?"selected":"" ?>>Lógico</option>
                <option value="char" <? echo @$conteudo=="char"?"selected":"" ?>>Char</option>
                <option value="date" <? echo @$conteudo=="date"?"selected":"" ?>>Data</option>
              </select> &nbsp;
	      <input type="text" name="tamanho" value="<?=@$tamanho?>" size="3"     onKeyUp="js_ValidaCampos(this,1,'O tamanho do campo','f','f',event);" > 
            </td>
            <td width="302" rowspan="10"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td> <strong>Valores default:</strong> 
                    <select multiple name="itensdef[]" onChange="js_mostradef(this)" size="13" id="itensdef" style="width:300">
                      <?
					  if(isset($retorno)){
				        $result = pg_exec("select * from db_syscampodef where codcam = ".$retorno);
					    $numrows = pg_numrows($result);
					    for($i = 0;$i < $numrows;$i++)
					      echo "<option value=\"".pg_result($result,$i,"defcampo")."#&".pg_result($result,$i,"defdescr")."\">".pg_result($result,$i,"defcampo")."</option>\n";
					  }
				      ?>
                    </select> </td>
                </tr>
                <tr> 
                  <td> <input name="textodef" type="text" id="textodef" style="width:245" maxlength="100"> 
                    <input type="button" name="adicionar" onClick="js_adddef(this.form)" style="width:15" value="+"> 
                    <input type="button" name="retirar" onClick="js_remdef(this.form)" style="width:15" value="-" disabled> 
                    <input type="button" name="alterardef" onClick="js_alterardef(this.form)" style="width:15" value="A" disabled> 
                  </td>
                </tr>
                <tr> 
                  <td> <strong>Descrição Valor Default:</strong> 
                    <textarea name="descitensdef" rows="5" id="descitensdef" style="width:300"></textarea>	
                  </td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td><strong>Label Forms:</strong></td>
            <td title="Label nos formulários"><input name="rotulo" type="text" id="rotulo" value="<?=@$rotulo?>" size="50" maxlength="50"></td>
          </tr>
          <tr> 
            <td title="Label nos relatórios"><strong>Label Relat.:</strong></td>
            <td><input name="rotulorel" type="text" id="rotulorel" value="<?=@$rotulorel?>" onfocus="this.value==''?this.value=rotulo.value:this.value" size="50" maxlength="40"></td>
          </tr>
 
          <tr> 
            <td><strong>Default:</strong></td>
            <td><input name="valorinicial" style="background-color:#E6E4F1" type="text" id="valorinicial" value="<?=@$valorinicial?>" size="50" maxlength="100"></td>
          </tr>
          <tr> 
            <td><strong>Descrição:</strong>&nbsp;&nbsp;</td>
            <td> <textarea name="descricao" cols="50" rows="8"><?=@$descricao?></textarea></td>
          </tr>
          <tr> 
            <td><strong>Aceita Nulo:</strong></td>
            <td> <input name="vnulo" type="checkbox" id="vnulo" value="true" <?=(@$nulo=='t'?"checked":"")?>></td>
          </tr>
          <tr> 
            <td><strong>Mai&uacute;sculo:</strong></td>
            <td><input name="vmaiusculo" type="checkbox" id="maiusculo" value="false" <?=(@$maiusculo=='t'?"checked":"")?>></td>
          </tr>
          <tr> 
            <td><strong>Auto Completar:</strong></td>
            <td><input name="vautocompl" type="checkbox" id="autocompl" value="false" <?=(@$autocompl=='t'?"checked":"")?>></td>
          </tr>
          <tr> 
            <td><strong>Valida&ccedil;&atilde;o:</strong></td>
            <td><select name="aceitatipo" size="1" OnChange="js_valida(this.selectedIndex)">
                <option value='0'>Não Valida Campo</option>
                <option value="1" <? echo @$aceitatipo=="1"?"selected":"" ?>>Somente 
                Números</option>
                <option value="2" <? echo @$aceitatipo=="2"?"selected":"" ?>>Somente 
                Letras</option>
                <option value="3" <? echo @$aceitatipo=="3"?"selected":"" ?>>Números 
                e Letras</option>
                <option value="4" <? echo @$aceitatipo=="4"?"selected":"" ?>>Números 
                Casa Dec.</option>
                <option value="5" <? echo @$aceitatipo=="5"?"selected":"" ?>> 
                Vardadeiro/Falso</option>
              </select></td>
          </tr>
          <tr style='display:none'> 
            <td><strong>Tipo Objeto:</strong></td>
            <td><select name="tipoobj" size="1">
                <option <?=(@$tipoobj=='text'?"selected":"")?> value='text'>Input 
                Text</option>
                <option <?=(@$tipoobj=='checkbox'?"selected":"")?> value='checkbox'>Input 
                Checkbox</option>
                <option <?=(@$tipoobj=='radiobutton'?"selected":"")?> value='radiobutton'>Input 
                Radio Button</option>
                <option <?=(@$tipoobj=='image'?"selected":"")?> value='image'>Input 
                Imagem</option>
                <option <?=(@$tipoobj=='textarea'?"selected":"")?> value='textarea'>TextArea</option>
                <option <?=(@$tipoobj=='select'?"selected":"")?> value='select'>Select</option>
                <option <?=(@$tipoobj=='multiple'?"selected":"")?> value='multiple'>Select 
                Multiplo</option>
              </select> </td>
          </tr>
          <tr> 
            <td colspan="3" >
                  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
	             <input name="incluir" onClick="return js_verifica(); Botao = 'incluir'; " accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="alterar" accesskey="a" onClick="return js_verifica();" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
	      &nbsp; <input name="docum_alteracao" value="Procurar campo" type="button" onclick="botao_pesquisa=false;js_alteracao_iframe()"> 
              &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="hidden" id="procurar2" value="Procurar">	
            </td>
          </tr>
        </table>
  </form>
      <?
//	  } // fim do else do if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
    ?>
    </td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>

<script>

function js_documentacao_iframe(){
  js_OpenJanelaIframe('top.corpo','db_iframe','func_db_syscampo.php?funcao_js=parent.js_recebecampo|codcam|nomecam','Pesquisa',true);
}

function js_recebecampo(chave,nome){
  db_iframe.hide();
  if(document.form1.alterar.disabled==false || document.form1.alterar.disabled==false){ 
    tam=document.form1.codcampai.options.length;  
    document.form1.codcampai.options[tam]=new Option(nome,chave,true);   
    if(tam%2==0){
      document.form1.codcampai.options[tam].style.backgroundColor= "#D7CC06";
    }else{  
      document.form1.codcampai.options[tam].style.backgroundColor= "#F8EC07";
    }
  }else{  
    js_buscadefault(chave);
  }  
}
function js_alteracao_iframe(){
  nomecam = document.form1.nomecam.value;1
  if(nomecam!=""){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_syscampo.php?chave_nomecam='+nomecam+'&funcao_js=parent.js_alteracampo|0','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_db_syscampo.php?funcao_js=parent.js_alteracampo|0','Pesquisa',true);
  }    
}

function js_alteracampo(chave){
  location.href="sys1_campos001.php?retorno="+chave;
}


</script>


<?

if($processamento==1){
//  db_msgbox("Inclusao processada com sucesso!");
  db_redireciona();
}else if($processamento==2){
//  db_msgbox("Alteracao processada com sucesso!");
  db_redireciona();
}else if($processamento==3){
//  db_msgbox("Exclusao processada com sucesso!");
  db_redireciona();
}else if($processamento=='4'){
  db_msgbox($erro_msg);
}
?>