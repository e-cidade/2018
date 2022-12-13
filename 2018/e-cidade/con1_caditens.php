<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("libs/db_usuariosonline.php");

include("classes/db_db_syscadproced_classe.php");
$cldb_syscadproced = new cl_db_syscadproced;
$cldb_syscadproced->rotulo->label();


parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $sql = "SELECT *
          FROM db_itensmenu i
              LEFT OUTER JOIN db_modulos m ON i.id_item = m.id_item
              LEFT JOIN db_syscadproceditem p on p.id_item = i.id_item
              LEFT JOIN db_syscadproced cp on cp.codproced = p.codproced
		  WHERE i.id_item = $retorno";
  $result = db_query($sql);
  db_fieldsmemory($result,0);
}

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS); 
  db_query("begin");
  $result = db_query("select nextval('db_itensmenu_id_item_seq')");
  $id_item = pg_result($result,0,0);
  $id_item = $id_item==""?"1":$id_item;
  $sql = "insert into db_itensmenu values(
           $id_item,
		   '$descricao',
		   '$help',
		   '$funcao',
		   '".(isset($modulo_sel)?"2":$itemativo)."',
		   '1',
		   '$desctec',
		    '$libcliente'	)";
  db_query($sql) or die("Erro(15) inserindo em db_itensmenu: ".pg_errormessage());
  //insere os itensfilhos  
  if(isset($itensfilho)){
    $numArray = sizeof($itensfilho);
    for($i = 0;$i < $numArray;$i++) {
       $codfilho = $itensfilho[$i];
       db_query("insert into db_itensfilho (id_item, codfilho) values(".$id_item.",".$codfilho.")") or die("Erro(29) inserindo em db_itensfilho");
    }
  }
  /////
  if(isset($modulo_sel) && $modulo_sel == 't') {
	db_query("insert into db_modulos values(
	         $id_item,
		 '$nome_modulo',
		 '$descr_modulo',
		 '$imagem',
		 '$temexerc')") or die("Erro(26) inserindo em db_modulos: ".pg_errormessage());
  }
  
  // grava a procedencia do item
  
  if(isset($codproced) && $codproced > 0){
    $sql = "insert into db_syscadproceditem values(nextval('db_syscadproceditem_seqproitem_seq'),$codproced,$id_item)";
    db_query($sql);
  }

  db_query("commit");

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();

  db_redireciona();
  exit;		   
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  db_query("begin");
  db_query("update db_itensmenu set
                               descricao = '$descricao',
                               help = '$help',
                               funcao = '$funcao',
                               itemativo = '".(isset($modulo_sel)?"2":$itemativo)."',
							   desctec = '$desctec',
							   libcliente = '$libcliente'
           where id_item = $id_item") or die("Erro(40) alterando db_itensmenu: ".pg_errormessage());
  //altera os itensfilhos  
  db_query("delete from db_itensfilho where id_item = ".$id_item) or die("Erro(67) excluindo db_itensfilho");
  if(isset($itensfilho)){
    $numArray = sizeof($itensfilho);
    for($i = 0;$i < $numArray;$i++) {
      $codfilho = $itensfilho[$i];
      db_query("insert into db_itensfilho values(".$id_item.",".$codfilho.")") or die("Erro(29) alterando em db_itensfilho");
    }
  }
  /////
  
  if(isset($modulo_sel) && $modulo_sel == 't') {
    $result = db_query("select id_item from db_modulos where id_item = $id_item");
	if(pg_numrows($result) > 0) {
      db_query("update db_modulos set
				               nome_modulo = '$nome_modulo',
			                   descr_modulo = '$descr_modulo',
			                   imagem = '$imagem',
			                   temexerc = '$temexerc'
			where id_item = $id_item") or die("Erro(62) atualizando db_modulos: ".pg_errormessage());
	} else {
	  	db_query("insert into db_modulos values(
	         $id_item,
			 '$nome_modulo',
			 '$descr_modulo',
			 '$imagem',
			 '$temexerc')") or die("Erro(69) inserindo em db_modulos: ".pg_errormessage());
	}
  } else {
     $result = db_query("select id_item from db_modulos where id_item = $id_item");
	if(pg_numrows($result) > 0) {
	  db_query("delete from db_modulos where id_item = $id_item") or die("Erro(51) deletando db_modulos: ".pg_errormessage());
	}
  }


  // grava a procedencia do item
  $sql = "delete from db_syscadproceditem where id_item = $id_item";
  db_query($sql);

  if(isset($codproced) && $codproced > 0){
    $sql = "insert into db_syscadproceditem values(nextval('db_syscadproceditem_seqproitem_seq'),$codproced,$id_item)";
    db_query($sql);
  }

  db_query("commit");

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();

  db_redireciona();
  exit;		     
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  db_query("begin");
  // grava a procedencia do item
  $sql = "delete from db_syscadproceditem where id_item = $id_item";
  db_query($sql);

  //exclui os itens filho
  db_query("delete from db_itensfilho where id_item = ".$HTTP_POST_VARS["id_item"]) or die("Erro(107) excluindo db_itensfilho");
  db_query("delete from db_itensmenu where id_item = ".$HTTP_POST_VARS["id_item"]) or die("Erro(33) deletando db_itensmenu: ".pg_errormessage());
  $result = db_query("select id_item from db_modulos where id_item = ".$HTTP_POST_VARS["id_item"]);
  if(pg_numrows($result) > 0)
    db_query("delete from db_modulos where id_item = ".$HTTP_POST_VARS["id_item"]) or die("Erro(36) deletando tabela db_modulos: ".pg_errormessage());
  db_query("commit");	

  /**
   * Limpa o cache dos menus
   */
  DBMenu::limpaCache();
  
  db_redireciona();
  exit;  
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<script>
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao != 'procurar') {  
    if(obj.descricao.value == "") {
      alert("Campo descrição é obrigatório");
	  obj.descricao.focus();
	  return false;
    }
  }  

  //obj.elements["itensfilho[]"].multiple = true;	  
  //for(var i = 0;i < obj.itensfilho.mength;i++)
  //  obj.itensfilho.options[i].selected = true;
  if(document.getElementById('itemativo1').checked ){
    if(document.form1.funcao.value != ""){
      if(js_atualiza_item()==false)
        return false;
    }
  }
}
function js_modulo() {
  if(document.form1.modulo_sel.checked) {
    document.form1.nome_modulo.disabled = false;
	document.form1.descr_modulo.disabled = false;
	document.form1.imagem.disabled = false;
//	document.getElementById("instit").disabled = false;	
	document.getElementById("temexerc1").disabled = false;		
	document.getElementById("temexerc2").disabled = false;
	document.getElementById("nome_modulo").style.backgroundColor = "white";
	document.getElementById("descr_modulo").style.backgroundColor = "white";
	document.getElementById("imagem").style.backgroundColor = "white";
//	document.getElementById("instit").style.backgroundColor = "white";	
	document.getElementById("at1").disabled = false;
	document.getElementById("at2").disabled = false;
	document.getElementById("at3").disabled = false;
	document.getElementById("at4").disabled = false;
	document.getElementById("at5").disabled = false;
	document.getElementById("at6").disabled = false;
	document.getElementById("at7").disabled = false;
	document.form1.nome_modulo.focus();
  } else {
    document.form1.nome_modulo.value = '';
	document.form1.descr_modulo.value = '';
	document.form1.imagem.value = '';	
    document.form1.nome_modulo.disabled = true;
	document.form1.descr_modulo.disabled = true;
	document.form1.imagem.disabled = true;
//	document.getElementById("instit").disabled = true;
	document.getElementById("temexerc1").disabled = true;		
	document.getElementById("temexerc2").disabled = true;
	document.getElementById("nome_modulo").style.backgroundColor = "#999999";
	document.getElementById("descr_modulo").style.backgroundColor = "#999999";
	document.getElementById("imagem").style.backgroundColor = "#999999";
//	document.getElementById("instit").style.backgroundColor = "#999999";	
	document.getElementById("at1").disabled = true;
	document.getElementById("at2").disabled = true;
	document.getElementById("at3").disabled = true;
	document.getElementById("at4").disabled = true;
	document.getElementById("at5").disabled = true;
	document.getElementById("at6").disabled = true;
	document.getElementById("at7").disabled = true;	
  }
}
function js_iniciar() {
  if(document.form1) {    
    js_trocacordeselect();
    var mod = '<?=@$nome_modulo?>';
    if(mod != "")
      document.form1.modulo_sel.click();  
	document.form1.descricao.focus();
  }	  
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_addItem(obj) {
/*
  var hid = document.createElement("INPUT");
  hid.setAttribute("type","text");
  hid.setAttribute("name","hid" + obj.elements["itensfilho[]"].length);
  hid.setAttribute("value",obj.descitensfilho.value);
  document.getElementById(obj.id).appendChild(hid);
  */
  if(obj.textoItem.value == "") {
    alert("Campo não pode ser vazio!");
	obj.textoItem.focus();
	return false;
  }
  obj.elements["itensfilho[]"].options[obj.elements["itensfilho[]"].length] = new Option(obj.textoItem.value,obj.textoItem.value + '#&' + obj.descitensfilho.value);
 // alert(obj.elements["itensfilho[]"].options[obj.elements["itensfilho[]"].length-1].value);
  js_trocacordeselect();
  obj.textoItem.value = "";
  obj.descitensfilho.value = "";
  obj.textoItem.focus();
}
function js_mostraItem(obj) {
  var mat = new String(obj.options[obj.selectedIndex].value);
  mat = mat.split("#&");
  document.form1.textoItem.value = mat[0];
  document.form1.descitensfilho.value = mat[1];
  document.form1.adicionar.disabled = true;
  document.form1.retirar.disabled = false;
  document.form1.alteraritem.disabled = false;
}
function js_alterarItem(obj) {
  document.form1.adicionar.disabled = false;
  document.form1.retirar.disabled = true;
  document.form1.alteraritem.disabled = true;
  obj.elements["itensfilho[]"].options[obj.elements["itensfilho[]"].selectedIndex].text = obj.textoItem.value; 
  obj.elements["itensfilho[]"].options[obj.elements["itensfilho[]"].selectedIndex].value = obj.textoItem.value + '#&' + obj.descitensfilho.value;
  obj.textoItem.value = "";
  obj.descitensfilho.value = "";
  obj.textoItem.focus();  
}
function js_remItem(obj) {
  if(!confirm("Excluir Item?"))
    return false;
  obj.elements["itensfilho[]"].options[obj.elements["itensfilho[]"].selectedIndex] = null;
  js_trocacordeselect();
  document.form1.adicionar.disabled = false;
  document.form1.retirar.disabled = true;
  document.form1.alteraritem.disabled = true;
  obj.textoItem.value = "";
  obj.descitensfilho.value = "";
  obj.textoItem.focus();  
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> <center>
	<?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
        if(!isset($HTTP_POST_VARS["filtro"]))
		  $filtro = $HTTP_POST_VARS["descricao"];
		else
		  $filtro = $HTTP_POST_VARS["filtro"];
        $sql = "SELECT i.id_item,
                       i.descricao   as \"Nome do Item\",
                       i.funcao      as arquivo, 
                       m.nome_modulo as \"Nome do Módulo\",
                       CASE 
                         WHEN m.nome_modulo is not null 
                           THEN 'Sim'::text 
                         ELSE 'Não'::text 
                       END as \"É Módulo \",
                       cp.codproced  as Procedencia
                  FROM db_itensmenu i
                       LEFT OUTER JOIN db_modulos m ON i.id_item = m.id_item
                       LEFT JOIN db_syscadproceditem p on p.id_item = i.id_item
                       LEFT JOIN db_syscadproced cp on cp.codproced = p.codproced	
          		   WHERE upper(descricao) like upper('".$filtro."%')
          		   ORDER BY i.descricao";
        db_lov($sql,15,"con1_caditens.php",$filtro); 
// db_lov($query,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe") { 		
      } else {
    ?>
        <form id="form1" name="form1" method="post" onSubmit="return js_submeter(this)">
		<input type="hidden" name="id_item" value="<?=@$id_item?>">
          <table border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td width="55%" align="center" valign="middle">			 
			    <table border="0" cellspacing="0" cellpadding="0">
                  <tr> 
                    <td width="91" height="25" nowrap><strong>Descri&ccedil;&atilde;o:</strong></td>
                    <td width="332" height="25" nowrap><input name="descricao" onBlur="js_ValidaCamposText(this,3)" type="text" id="descricao" value="<?=@$descricao?>" size="50" maxlength="40"></td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><strong>Ajuda:</strong></td>
                    <td height="25" nowrap><input name="help" type="text" id="help" value="<?=@$help?>" size="50"></td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><strong>Fun&ccedil;&atilde;o:</strong></td>
                    <td height="25" nowrap><input name="funcao" type="text" id="funcao" value="<?=@$funcao?>" size="50" maxlength="100"></td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><strong>Descr Tecnica:&nbsp;&nbsp;</strong></td>
                    <td height="25" nowrap><textarea name="desctec" cols="60" rows="8" id="desctec"><?=@$desctec?></textarea></td>
                  </tr>

                  <tr style='display:none'> 
                    <td height="25" nowrap><strong>Ambiente:</strong></td>
                    <td height="25" nowrap> <input name="itemativo" id="itemativo1" type="radio" value="1" <? echo isset($retorno)?($itemativo=='1'?"checked":""):"checked" ?>> 
                      <label for="itemativo1"><strong>Web&nbsp;&nbsp;&nbsp;&nbsp;</strong></label> 
                      <input type="radio" name="itemativo" id="itemativo2" value="0" <? echo isset($retorno)?($itemativo=='0'?"checked":""):"" ?>> 
                      <label for="itemativo2"><strong>Caracter</strong></label> 
                    </td>
                  </tr>

                  <tr> 
                    <td height="25" nowrap><label for="modulo_sel"><strong>M&oacute;dulo:</strong></label></td>
                    <td height="25" nowrap><input name="modulo_sel" type="checkbox" id="modulo_sel" value="t" onClick="js_modulo()"> 
                    </td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><label for="nome_modulo" id="at1" disabled><strong>Nome:</strong></label></td>
                    <td height="25" nowrap><input name="nome_modulo" style="background-color:#999999" type="text" id="nome_modulo" value="<?=@$nome_modulo?>" size="50" maxlength="20" disabled></td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><label for="descr_modulo" id="at2" disabled><strong>Descri&ccedil;&atilde;o:</strong></label></td>
                    <td height="25" nowrap><input name="descr_modulo" style="background-color:#999999" type="text" id="descr_modulo" value="<?=@$descr_modulo?>" size="50" disabled></td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><label for="imagem" id="at3" disabled><strong>Imagem:</strong></label></td>
                    <td height="25" nowrap><input name="imagem" style="background-color:#999999" type="text" id="imagem" value="<?=@$imagem?>" size="50" maxlength="100" disabled></td>
                  </tr>
                  <tr style='display:none'> 
                    <td height="25" nowrap><label for="temexerc1" id="at4" disabled>
                      <strong>Tem Exerc&iacute;cio:</strong></label>
                    </td>
                    <td height="25" nowrap><input name="temexerc" id="temexerc1" type="radio" value="t" <? echo isset($temexerc)?($temexerc=='t'?"checked":""):"" ?> disabled> 
                      <label for="temexerc1" id="at5" disabled><strong>Sim</strong></label> 
                      <input type="radio" id="temexerc2" name="temexerc" <? echo isset($temexerc)?($temexerc=='f'?"checked":""):"checked" ?> value="f" disabled> 
                      <label for="temexerc2" id="at6" disabled><strong>N&atilde;o</strong></label> 
                    </td>
                  </tr>
                  <tr> 
                    <td height="25" nowrap><label for="instit" id="at7" disabled><strong>Liberado p/ Cliente:</strong></label></td>
                    <td height="25" nowrap> 
                      <select name="libcliente" size="1" id="libcliente"  style="background-color:#999999" >
                      <option value="0" <?=(@pg_result($result,0,"libcliente")=="f"?"selected":"")?> >Não</option>
                      <option value="1" <?=(@pg_result($result,0,"libcliente")=="t"?"selected":"")?> >Sim</option>
                      </select>
                      &nbsp; </td>
                  </tr>

 <tr>
    <td nowrap title="<?=@$Tcodproced?>">
       <?
       db_ancora(@$Lcodproced,"js_pesquisacodproced(true);",@$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('codproced',8,$Icodproced,true,'text',@$db_opcao," onchange='js_pesquisacodproced(false);'")
?>
       <?
db_input('descrproced',40,$Idescrproced,true,'text',3,'')
       ?>
    </td>
  </tr>
                  
                  <tr> 
                    <td height="25" nowrap>&nbsp;</td>
                    <td height="25" nowrap> <input name="incluir" onClick="Botao='incluir'" accesskey="i" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
                      &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
                      &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
                      &nbsp; <input name="procurar" onClick="Botao='procurar'" accesskey="p" type="submit" id="procurar" value="Procurar">
                      &nbsp; <input name="Seleciona" onClick="js_pesquisa();" accesskey="s" type="button" id="seleciona" value="Seleciona">
		      </td>
                  </tr>
                </table>
	      </td>
              <td width="45%" align="left" valign="top">
	      
			  <table border="0" cellpadding="0" cellspacing="0">

	

<tr>
<td colspan="2">
<table align="center" >
   <tr>
    <td nowrap title="" > 
      <fieldset><Legend>Selecione um Programa</legend>
      <table border="0">
      
         <tr>
            <?
	    $clrotulocampo = new rotulocampo;
	    $clrotulocampo->label('codfilho');
	    $clrotulocampo->label('arqfilho');
            ?>
             <td nowrap title="<?=@$Tcodarq?>" >
            <?
	    db_ancora($Lcodfilho,"js_arquivos(true);",2);
            ?>
            <?
              db_input('codfilho',8,'',true,'text',2," onchange='js_arquivos(false);'")
            ?>
	    <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
            <?
              db_input('arqfilho',25,'',true,'text',3,'')
            ?>
	    <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
	    
	    <input name="lanca" type="button" value="Lançar" >
           </td>
	 </tr> 
         <tr>   
	   <td align="right" colspan="" width="80%">
              <select name="itensfilho[]" id="itensfilho" size="15" style="width:250px" multiple onDblClick="js_excluir_item()">
	      <?
	      if(isset($id_item)){
	        $sql = "select db_arquivos.*  
	                from db_itensfilho f
			     inner join db_arquivos on f.codfilho = db_arquivos.codfilho
		        where f.id_item = $id_item";
	        $result = db_query($sql);
		for($i=0;$i<pg_numrows($result);$i++){
		  echo "<option value='".pg_result($result,$i,'codfilho')."'>".pg_result($result,$i,'arqfilho')."</option>";
		}
	      }
	      ?>
             </select> 
	   </td>
         </tr>
         </tr>
	   <td ><strong>
	   Dois Clicks sobre o ítem Exclui</strong>
	   </td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
</table>
</td>
</tr>	


			  
			  
			  </table>			    

            </tr>
          </table>
          </form>
		<?
		}
		?>
      </center></td>
  </tr>
</table>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
function js_pesquisa(){
  db_iframe.jan.location.href = 'con1_caditens002.php';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}

///// aqui funcao

function js_atualiza_item(){
  var F = document.getElementById("itensfilho").options;
  if(F.length==0){
    alert('Cadastre um ítem para prosseguir.');
    document.form1.codfilho.focus();
    return false;
  }else{  
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
  }
  return true;
}
					       
function js_excluir_item() {
  var F = document.getElementById("itensfilho");
  if(F.length == 1)
     F.options[0].selected = true;
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
     F.options[SI] = null;
     js_trocacordeselect();
     if(SI <= (F.length - 1))
       F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.arqfilho.value;
  var valor=document.form1.codfilho.value;
  if(texto != "" && valor != ""){
     var F = document.getElementById("itensfilho");
     var testa = false;
     for(var x = 0; x < F.length; x++){
        if(F.options[x].value == valor || F.options[x].text == texto){
          testa = true;
          break;
        }
     }
     if(testa == false){
       F.options[F.length] = new Option(texto,valor);
       js_trocacordeselect();
     }
  }
  texto=document.form1.arqfilho.value="";
  valor=document.form1.codfilho.value="";
  document.form1.lanca.onclick = '';
}
function js_arquivos(chave){
  document.form1.lanca.onclick = '';
  if(chave){
    js_OpenJanelaIframe('top.corpo','db_arquivos_iframe','func_db_arquivos.php?funcao_js=parent.js_arquivosret|codfilho|arqfilho','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_arquivos_iframe','func_db_arquivos.php?pesquisa_chave='+document.form1.codfilho.value+'&funcao_js=parent.js_arquivosret1','Pesquisa',false);
  }
}

function js_arquivosret(chave,chave1){
  document.form1.codfilho.value = chave;
  document.form1.arqfilho.value = chave1;
  db_arquivos_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_arquivosret1(chave,chave1){
  document.form1.arqfilho.value = chave;
  if(chave1){
    document.form1.codfilho.select();
    document.form1.codfilho.focus();
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
  db_arquivos_iframe.hide();
}
//// ate aqui


function js_pesquisaitemcad(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?retorno="+chave;
}





function js_pesquisacodproced(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_syscadproced','func_db_syscadproced.php?funcao_js=parent.js_mostradb_syscadproced1|codproced|descrproced','Pesquisa',true);
  }else{
     if(document.form1.codproced.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_syscadproced','func_db_syscadproced.php?pesquisa_chave='+document.form1.codproced.value+'&funcao_js=parent.js_mostradb_syscadproced','Pesquisa',false);
     }else{
       document.form1.descrproced.value = ''; 
     }
  }
}
function js_mostradb_syscadproced(chave,erro){
  document.form1.descrproced.value = chave; 
  if(erro==true){ 
    document.form1.codproced.focus(); 
    document.form1.codproced.value = ''; 
  }
}
function js_mostradb_syscadproced1(chave1,chave2){
  document.form1.codproced.value = chave1;
  document.form1.descrproced.value = chave2;
  db_iframe_db_syscadproced.hide();
}








</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>