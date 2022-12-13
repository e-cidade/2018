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

function db_data($nome,$dia="",$mes="",$ano="") {
  ?>
  <input name="<?=$nome."_dia"?>" onKeyUp="js_Passa(this.name)" type="text" id="<?=$nome."_dia"?>" value="<?=$dia?>" size="2" maxlength="2"><strong>/</strong>
  <input name="<?=$nome."_mes"?>" onKeyUp="js_Passa(this.name)" type="text" id="<?=$nome."_mes"?>" value="<?=$mes?>" size="2" maxlength="2"><strong>/</strong>
  <input name="<?=$nome."_ano"?>" onKeyUp="js_Passa(this.name)" type="text" id="<?=$nome."_ano"?>" value="<?=$ano?>" size="4" maxlength="4">
  <script language="JavaScript">

    function js_VerDaTa(nome) {
	  var data = new Date(<?=date("Y")?>,<?=(date("n") - 1)?>,<?=date("j")?>);
	  var F = document.form1;
	  var str = new String(F.elements[nome].value);
	  
	  if(nome.indexOf("dia") != -1) {	    	   
		var expr = new RegExp("[0-"+(data.getMonth()==1?2:3)+"][0-9]");
		var dia = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
		if(str.match(expr) == null || str > dia[data.getMonth()] || str == "00") {
		  alert("Dia inválido!!!!!");
		  F.elements[nome].select();
		  return false;
		} else
		  return true;
	  } else if(nome.indexOf("mes") != -1) {
        var expr = new RegExp("[01][0-9]");	  
		if(str.match(expr) == null || str > 12) {
		  alert("Mes inválido");
		  F.elements[nome].select();
		  return false;
		} else
		  return true;
	  } else if(nome.indexOf("ano") != -1) {
        var expr = new RegExp("[12][0-9][0-9][0-9]");
		if(str.match(expr) == null) {
		  alert("Ano inválido");
		  F.elements[nome].select();
		  return false;
		} else
		  return true;
	  } else
	    alert("Erro fatal na função de verificação de datas!!!!");
	}
    function js_Passa(nome) {	
      var F = document.form1;
      if(F.elements[nome].value.length == F.elements[nome].size && js_VerDaTa(nome) == true) {
	    for(var i = 0;i < F.elements.length;i++)		  
		  if(F.elements[i].name == nome) {
		    var index = i+1;
			break;
		  }	
		F.elements[index].focus();
	  }
	}
  </script>
  <?
}
/*************************************/
function db_label($tab,$label,$campo="") {
$campo = ($campo=="")?$label:$campo;
?>
  <strong>
  <label for="db_<?=$campo?>">
  <a href="" class="rotulos" onClick="js_lista('dbforms/db_<?=$tab?>.php','db_<?=$campo?>' + '==' + document.form1.db_<?=$campo?>.value,'<?=$campo?>',100,50,600);return false">
    <?=ucwords($label)?>:
  </a>
  </label>
  </strong>
<?
}
/************************************/
function db_text($campo,$tamanho,$max,$db_nome="",$dbh_nome="") {
?>
  <input name="db_<?=$campo?>" id="db_<?=$campo?>" value="<?=$db_nome?>" type="text" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
  <input name="dbh_<?=$campo?>" type="hidden" value="<?=$dbh_nome?>">
<?
}
/************************************/
function db_file($campo,$tamanho,$max,$dbh_nome="",$db_nome="") {
?>
  <input onChange="js_preencheCampo(this.value,this.form.dbh_<?=$campo?>.name)" name="db_<?=$campo?>" id="db_<?=$campo?>" value="<?=$db_nome?>" type="file" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off"><br>
  <input name="dbh_<?=$campo?>" type="text" value="<?=$dbh_nome?>" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
<?
}
/************************************/
function db_getfile($arq,$text,$funcao="0") {
  db_postmemory($GLOBALS["_FILES"][$arq]);
  $DB_FILES = $GLOBALS["DB_FILES"];
  $tmp_name = $GLOBALS["tmp_name"];
  $name = $GLOBALS["name"];
  $size = $GLOBALS["size"];
  if($funcao != "0") {
    if($name != "") {
      system("rm -f $DB_FILES/$funcao");
      copy($tmp_name,"$DB_FILES/$text");
	  return $text;
    } else if($text != "") {
      if($text != $funcao) {
	    system("mv $DB_FILES/$funcao $DB_FILES/$text");
		return $text;
	  } else
	    return $text;
    } else if($text == "") {
	  system("rm -f $DB_FILES/$funcao");
	  return "";
	}
  } else if($name != "" && $size == 0) {
      db_erro("O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.");
  } else {
    copy($tmp_name,"$DB_FILES/$text");
    return $text;
  }
}
?>