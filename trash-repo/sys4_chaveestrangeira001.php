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

db_postmemory($_GET);
db_postmemory($_POST);

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
//$tabela = isset($HTTP_POST_VARS["tabela"])?$HTTP_POST_VARS["tabela"]:$tabela;
$nomearq = pg_exec("select nomearq from db_sysarquivo where codarq = $tabela");
$nomearq = pg_result($nomearq,0,0);
if(!isset($ref) && isset($HTTP_POST_VARS["ref"]))
  $ref = $HTTP_POST_VARS["ref"];

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="5000">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = '';
function js_submeter(obj) {
  if(Botao == 'atualizar') {
    if(obj.dbh_tabela.selectedIndex == -1) {
	  alert("Clique em tabela para escolher uma.");  
	  return false;
	}
	if(obj.alt_ind.value == "") {
	  alert("Informe o(s) campo(s) para foreign key");
	  obj.alt_ind.focus();
	  return false;
	}
  }
  return true;
}

function js_campos_mostra(){
  js_OpenJanelaIframe('top.corpo','db_iframe_campo','sys4_chaveprim002.php?<? echo base64_encode("tabela=$tabela") ?>','Pesquisa campo',true,"20","160","300","300");
}


</script>
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
}
-->
</style>

<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_iniciar() {
  var str;
  for(var i in NomeTabelas) {   
    document.form1.dbh_tabela.options[document.form1.dbh_tabela.length] = new Option(NomeTabelas[i],CodTabelas[i]);
	<?
	if(isset($ref)) {
	  echo "
	  if(document.form1.dbh_tabela.options[document.form1.dbh_tabela.length - 1].value == '$ref') {
		  document.form1.dbh_tabela.options[document.form1.dbh_tabela.length - 1].selected = true;
		  document.form1.campop.value = PriKeys[$ref];
		}
	  ";
	} else {
	  echo "
		  if(typeof((str = PriKeys[document.form1.dbh_tabela.options[document.form1.dbh_tabela.length - 1].value])) != \"undefined\")	  
            document.form1.campop.value = str;
          else
            document.form1.campop.value = \"\";
	  ";
	}
	?>
  }
  document.form1.campop.value = document.form1.campop.value.replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n");
  js_trocacordeselect();
}
function js_insereTabelas(obj,mod) {
  var str;
  
  while(obj.dbh_tabela.length) {
    document.form1.dbh_tabela.options[0] = null;  
  }
  if(mod == "Todas Tabelas") {
    for(var i in NomeTabelas)
    document.form1.dbh_tabela.options[document.form1.dbh_tabela.length] = new Option(NomeTabelas[i],CodTabelas[i]);    
  } else {
    for(var i in NomeTabelas) {
      var aux = new String(i);
      if(aux.indexOf(mod) != -1)
	    obj.dbh_tabela.options[obj.dbh_tabela.length] = new Option(NomeTabelas[aux],CodTabelas[aux]);
    }
  }
  if(typeof((str = PriKeys[document.form1.dbh_tabela.options[0].value])) != "undefined")
    document.form1.campop.value = str;
  else
    document.form1.campop.value = "";
  
  document.form1.campop.value = document.form1.campop.value.replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n");
  js_trocacordeselect();
    //document.getElementById('sd').innerHTML += i + ' = ' + NomeTabelas[i] + '<br>';
}
function js_mostraPK(obj) {
  var str;
  if(typeof((str = PriKeys[obj.options[obj.selectedIndex].value])) != "undefined")
    document.form1.campop.value = str;
  else
    document.form1.campop.value = "";	
  
  document.form1.campop.value = document.form1.campop.value.replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n").replace(',', "\n");
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
<?
function form($ref,$tab,$tab_pai,$tab_campos,$codarq="") {

global $nomearq;

?>
<center>
<form method="post" name="form1" onSubmit="return js_submeter(this)">
  <input type="text"   name="tabela" value="<?=$tab?>">
  <input type="text"   name="ref"    value="<?=$ref?>">
<fieldset style="width:500px">
  <legend><b>Tabela: <?=$nomearq?></b></legend>
<table border="0" cellpadding="3" cellspacing="0">
  <tr>
     <td>
       <b>Módulo:</b>
     </td>
     <td>
    <select name="dbh_modulo" size="1" style="width:200px" onChange="js_insereTabelas(this.form,this.options[this.selectedIndex].text)">
    <?
	echo '<option value="0">Todas Tabelas</option>'."\n";
	$result = pg_exec("select m.codmod,m.nomemod 
	                   from db_sysmodulo m
					   inner join db_sysarqmod s
					   on s.codmod = m.codmod
					   group by m.codmod,m.nomemod
					   order by nomemod");
	for($i=0;$i<pg_numrows($result);$i++){
	  echo '<option value="'.str_replace(" ","_",trim(pg_result($result,$i,"nomemod"))).'">'.trim(pg_result($result,$i,"nomemod")).'</option>'."\n";
	}
	?>
    </select>
  </td>
  <td>
    <? 
	 echo "<script>\n";
	 echo "NomeTabelas = new Array();\n";
	 echo "CodTabelas = new Array();\n";
	 echo "PriKeys = new Array();\n";
     $result = pg_exec("select d.nomemod,m.codarq,a.nomearq
                        from db_sysarquivo a
                        inner join db_sysarqmod m
                        on a.codarq = m.codarq
                        inner join db_sysmodulo d
                        on d.codmod = m.codmod
                        order by d.nomemod,a.nomearq");
	 $numrows = pg_numrows($result);
	 for($i = 0;$i < $numrows;$i++) {
	   echo "NomeTabelas['".str_replace(" ","_",trim(pg_result($result,$i,"nomemod"))).$i."'] = '".trim(pg_result($result,$i,"nomearq"))."'; CodTabelas['".str_replace(" ","_",trim(pg_result($result,$i,"nomemod"))).$i."'] = '".trim(pg_result($result,$i,"codarq"))."';\n";
	 }
	 $groupCampos = pg_exec("select codarq from db_sysprikey group by codarq");
	 $numGroupCampos = pg_numrows($groupCampos);
	 for($i = 0;$i < $numGroupCampos;$i++) {
	   $result = pg_exec("select p.codarq,c.nomecam 
	                      from db_sysprikey p 
						  inner join db_syscampo c 
						  on c.codcam = p.codcam 
						  where p.codarq = ".pg_result($groupCampos,$i,"codarq")."
						  order by p.sequen");
		$aux = "";
		$c = "";
	   for($j = 0;$j < pg_numrows($result);$j++) {
	     $aux .= $c.trim(pg_result($result,$j,"nomecam"));
		 $c = ",";
	   }
	   echo "PriKeys[".trim(pg_result($groupCampos,$i,"codarq"))."] = '".$aux."';\n";
	 }
	 echo "</script>\n";     
     ?>
     <b>Tabela:</b>
    </td>
  <td>
  	<select name="dbh_tabela" size="1" style="width:200px" onChange="js_mostraPK(this)">
      <option>Selecione o Módulo</option>
	  </select>
  </td>
  </tr>
  <tr>
  <td valign='top' nowrap> 
    <b>Campos PK :</b>
  </td>
  <td>
    <!-- <input type="text" style="font-weight: bold" name="campop" id="campop" size="25" readonly> -->
  <textarea name="campop" id="campop" cols="23" rows="8" readonly wrap></textarea>
  </td> 
    <td valign='top' nowrap><b>Campos FK :</b></td>
    <td>
      <textarea name="alt_ind" cols="23" rows="8"><?echo $tab_campos?></textarea>
    </td>
  </tr>

<?

}
	

if(!isset($ref)) {
  form("0",$tabela,"","");
} else {
  if($ref == 0) {
    $result = pg_exec("
			select codarq 
			from db_sysarquivo 
			where nomearq = '$tabela'");
    $ref = pg_result($result,0,0);
  }
  if(isset($HTTP_POST_VARS["b_ex"])) {
    $result = pg_exec("
			delete from db_sysforkey 
			where codarq = $tabela
			and referen = $ref");
    db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
  }
  if(!isset($HTTP_POST_VARS["b_fk"])) {
    $result	= pg_exec("
			select c.nomecam,a.nomearq,a.codarq, f.tipoobjrel
	                from db_sysarquivo a,db_syscampo c,db_sysforkey f
	                where a.codarq = f.referen
	                and c.codcam = f.codcam
	                and f.codarq = $tabela
			and f.referen = $ref
            order by f.sequen");
    $num_linhas = pg_numrows($result);
    if($num_linhas == 0)
      form($ref,$tab,"","");
    else {
      $aux = "";
      $tipoobjrel = pg_result($result,0,'tipoobjrel');
      for($i = 0;$i < $num_linhas;$i++)
        $aux .= trim(pg_result($result,$i,"nomecam"))."\n";
      form((isset($HTTP_POST_VARS["ref"])?$HTTP_POST_VARS["ref"]:$ref),$tabela,pg_result($result,0,"nomearq"),$aux,pg_result($result,0,"codarq"));
    }
  } else {
    db_postmemory($HTTP_POST_VARS);
    $campos = split("\r\n",$alt_ind);
    pg_exec("BEGIN");
    $result = pg_exec("delete from db_sysforkey where codarq = $tabela and referen = $ref") or die("Erro(122) excluindo db_sysforkey");
    for($i = 0;$i < sizeof($campos) - 1;$i++) {
      if($campos[$i] != "" && $campos[$i] != " " && $campos[$i] != "  ") {
        $campos[$i] = trim(str_replace("#","",$campos[$i]));
	    $campos[$i] = trim(str_replace("#","",$campos[$i]));

        $result = pg_exec("select codcam
	                       from db_syscampo
	                       where nomecam = '$campos[$i]'") or die("Erro(127) selecionando db_syscampo");
        $s = $i + 1;
        $result = pg_exec("insert into db_sysforkey values($tabela,".pg_result($result,0,'codcam').",$s,$dbh_tabela,$tipoobjrel)") or die("Erro(129) inserindo em sb_sysforkey");
      }
	}
	pg_exec("END");
    db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
  }
}
?>
  <tr>
    <td nowrap><b>Tipo de Objeto : </b></td>
    <td>
      <select name="tipoobjrel" style="width:200px">
        <option value="0" <?=(isset($tipoobjrel) && $tipoobjrel=="0")?"selected":""?>>Âncora</option>
        <option value="1" <?=(isset($tipoobjrel) && $tipoobjrel=="1")?"selected":""?>>Select</option>
      </select>
    </td>
  </tr>
</table>
</fieldset>

  <input type="submit" name="b_fk" onClick="Botao = 'atualizar'" class="botao" value="Atualizar">
  <input type="submit" name="b_ex" class="botao" value="Excluir" OnClick="return confirm('Voce quer realmente excluir este registro?')">
  <input type="button" value="Procurar" OnClick="js_campos_mostra();">
  <input type="button" name="voltar" value="Voltar" onClick="location.href='sys3_campos001.php?<?=base64_encode("tabela=".$GLOBALS["tabela"])?>'">
</form>
</center>
</td>
</tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
	<div id="sd"></div>
</body>
</html>