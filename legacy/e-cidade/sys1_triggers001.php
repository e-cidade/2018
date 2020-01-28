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
include("classes/db_db_sysarquivo_classe.php");
include("classes/db_db_sysfuncoes_classe.php");
include("dbforms/db_funcoes.php");

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $sql = "select  t.codtrigger,t.nometrigger,t.quandotrigger,t.eventotrigger,f.nomefuncao as db_funcao,f.codfuncao ,a.codarq ,a.nomearq 
          from db_systriggers t
		  inner join db_sysfuncoes f
		  on f.codfuncao = t.codfuncao
		  inner join db_sysarquivo a
		  on a.codarq = t.codarq
          where codtrigger = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}
//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  //$result = pg_exec("select max(codtrigger) + 1 from db_systriggers");
  $result = pg_exec("select nextval('db_systriggers_codtrigger_seq')");
  $codtrigger = pg_result($result,0,0);
  $codtrigger = $codtrigger==""?"1":$codtrigger;
  pg_exec("insert into db_systriggers  (codtrigger,nometrigger,quandotrigger,eventotrigger,codfuncao,codarq )
                                       values($codtrigger,
                                             '$nometrigger',
				  	     '$quandotrigger',
					     '$eventotrigger',
					     $codfuncao,
					     $codarq)") or die("Erro(23) inserindo em db_systriggers");
  db_redireciona("sys1_triggers001.php");											
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("update db_systriggers set nometrigger = '$nometrigger',
									 quandotrigger = '$quandotrigger',
									 eventotrigger = '$eventotrigger',
									 codfuncao = $codfuncao,
									 codarq = $codarq
			where codtrigger = $codtrigger") or die("Erro(32) alterando db_systrigger");
  db_redireciona("sys1_triggers001.php");
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from db_systriggers where codtrigger = ".$HTTP_POST_VARS["codtrigger"]) or die("Erro(36) excluindo db_systrigger");			
  db_redireciona("sys1_triggers001.php");
}

$cldb_sysarquivo = new cl_db_sysarquivo;
$cldb_sysfuncoes = new cl_db_sysfuncoes;

$cldb_sysarquivo->rotulo->label();
$cldb_sysfuncoes->rotulo->label();

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = "";
function js_submeter(obj) {
  if(Botao == 'incluir' || Botao == 'alterar') {
    if(obj.nometrigger.value == "") {
	  alert("Campo nome da trigger é obrigatório!");
	  obj.nometrigger.focus();
	  return false;
	}
    if(obj.codfuncao.value == "") {
	  alert("clique em função para escolher uma!");
	  obj.codfuncao.focus();
	  return false;
	}
  }  
  return true;
}
function js_iniciar() {
if(document.form1)
  document.form1.nometrigger.focus();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"> 
      <?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
		 $sql = "SELECT codtrigger as \"Código\",nometrigger as \"Nome\"
                 FROM db_systriggers
			     WHERE nometrigger like '".$HTTP_POST_VARS["nometrigger"]."%'
                 ORDER BY nometrigger";
		db_lov($sql,15,"sys1_triggers001.php"); 
	  } else {
	 ?>
      <form name="form1" method="post" onSubmit="return js_submeter(this)">
	    <input type="hidden" name="codtrigger" value="<?=@$codtrigger?>">
        <table width="44%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td height="30" nowrap><strong>Nome:&nbsp; 
              <input name="nometrigger" type="text" id="nometrigger" value="<?=@$nometrigger?>" size="40" maxlength="50">
              </strong> </td>
          </tr>
          <tr> 
            <td height="30" nowrap><strong>Quando disparar evento: </strong> <select name="quandotrigger" id="quandotrigger">
                <option value="BEFORE" <? echo @$quandotrigger=="BEFORE"?"selected":"" ?>>Antes</option>
                <option value="AFTER" <? echo @$quandotrigger=="AFTER"?"selected":"" ?>>Depois</option>
              </select> &nbsp;&nbsp;<strong>Evento: </strong> <select name="eventotrigger" id="eventotrigger">
                <option value="INSERT" <? echo @$eventotrigger=="INSERT"?"selected":"" ?>>Inserir</option>
                <option value="UPDATE" <? echo @$eventotrigger=="UPDATE"?"selected":"" ?>>Atualizar</option>
                <option value="INSERT OR UPDATE" <? echo @$eventotrigger=="INSERT OR UPDATE"?"selected":"" ?>>Inserir or Atualizar</option>
                <option value="DELETE" <? echo @$eventotrigger=="DELETE"?"selected":"" ?>>Excluir</option>
              </select> </td>
          </tr>
          <tr>
            <td height="30" nowrap title="$Tcodarq">
              <?
                db_ancora(@$Lcodarq,"js_pesquisacodarq(true);",1);
	      ?>
              <? 
                db_input('codarq',5,$Icodarq,true,'text',1," onchange='js_pesquisacodarq(false);'");
                db_input('nomearq',40,$Inomearq,true,'text',3,"");
	      ?>
            </td>
          </tr>
          <tr> 
           <td height="30" nowrap> 
              <?
                db_ancora(@$Lcodfuncao,"js_pesquisacodfuncao(true);",1);
                db_input('codfuncao',5,$Icodfuncao,true,'text',1," onchange='js_pesquisacodfuncao(false);'");
                db_input('nomefuncao',40,$Inomefuncao,true,'text',3,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td height="30" nowrap> <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="alterar" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
              &nbsp; <input name="procurar" onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar"></td>
          </tr>
        </table>
      </form> 
      <?
	} // fim do else do       if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {	  
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
function js_pesquisacodarq(chave){
  if(chave==true){
     db_iframe.jan.location.href = 'func_db_sysarquivo.php?funcao_js=parent.js_preenchepesquisa|0|1';
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
  }else{
     db_iframe.jan.location.href = 'func_db_sysarquivo.php?pesquisa_chave='+document.form1.codarq.value+'&funcao_js=parent.js_preenchepesquisa1';
  }
}
function js_preenchepesquisa(chave,chave1){
  db_iframe.hide();
  document.form1.codarq.value = chave;
  document.form1.nomearq.value = chave1;
}
function js_preenchepesquisa1(chave,chave1){
  if(chave==true){
    document.form1.codarq.value = "";
    document.form1.nomearq.value = chave1;
    document.form1.codarq.focus();
  }else{
    document.form1.nomearq.value = chave;
  }
}
function js_pesquisacodfuncao(chave){
  if(chave==true){
     db_iframe.jan.location.href = 'func_db_sysfuncoes.php?funcao_js=parent.js_preenchepesquisafun|0|1';
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
  }else{
     db_iframe.jan.location.href = 'func_db_sysfuncoes.php?pesquisa_chave='+document.form1.codfuncao.value+'&funcao_js=parent.js_preenchepesquisafun1';
  }
}
function js_preenchepesquisafun(chave,chave1){
  db_iframe.hide();
  document.form1.codfuncao.value = chave;
  document.form1.nomefuncao.value = chave1;
}
function js_preenchepesquisafun1(chave,chave1){
  if(chave==true){
    document.form1.codfuncao.value = "";
    document.form1.nomefuncao.value = chave;
    document.form1.codfuncao.focus();
  }else{
    document.form1.nomefuncao.value = chave;
  }
}

</script>
<?
$db_iframe = new janela('db_iframe','');
$db_iframe->posX=1;
$db_iframe->posY=20;
$db_iframe->largura=780;
$db_iframe->altura=430;
$db_iframe->titulo='Pesquisa';
$db_iframe->iniciarVisivel = false;
$db_iframe->mostrar();
?>