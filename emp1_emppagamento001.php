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

include("classes/db_pagordem_classe.php");
include("classes/db_empparametro_classe.php");
$clpagordem     = new cl_pagordem;
$clempparametro = new cl_empparametro;
$rsParametro    = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu")));
if ($clempparametro->numrows > 0) {
  db_fieldsMemory($rsParametro,0);
  if ($e30_notaliquidacao != '') {
   db_redireciona("emp1_emppagamentonota001.php");
  }
 
}


parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;



$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
    function js_mascara(evt){
      var evt = (evt) ? evt : (window.event) ? window.event : "";
      
      if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
	return true;
      }else{
	return false;
      }  
    }
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.e50_codord.focus()" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1' action='emp1_emppagamento002.php'>
    <center>
      <table>
		<tr>
		  <td nowrap title="<?=@$Te50_codord?>" align='right'>
		     <? db_ancora(@$Le50_codord,"js_pesquisae50_codord(true);",$db_opcao);  ?>
		  </td>
		  <td> 
		     <? db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")  ?>
		  </td>
		</tr>
          <tr> 
            <td  align="right" nowrap title="<?=$Te60_numemp?>">
                 <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
            </td>
	    
            <td  nowrap> 
             
	      <input name="e60_codemp" title='<?=$Te60_codemp?>' size="12" type='text'  onKeyPress="return js_mascara(event);" >
            </td>
          </tr> 
		<tr>
		  <td nowrap title="<?=@$Te60_numemp?>" align='right'>
		     <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",$db_opcao);  ?>
		  </td>
		  <td> 
		     <? db_input('e60_numemp',12,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false);'")  ?>
		  </td>
		</tr>
		<tr>
		  <td colspan='2' align='center'>
		    <input name="entrar_codord" type="button" id="pesquisar" value="Entrar" onclick="js_entra();" >
		  </td>
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
<script>
function js_entra(){
  if(document.form1.e50_codord.value != ""){
      obj=document.createElement('input');
      obj.setAttribute('name','pag_ord');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','true');
      document.form1.appendChild(obj);
      document.form1.submit();
  }else  if(document.form1.e60_numemp.value != "" || document.form1.e60_codemp.value != ""){
      obj=document.createElement('input');
      obj.setAttribute('name','pag_emp');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','true');
      document.form1.appendChild(obj);
      document.form1.submit();
  }else{
	alert("Seleciona uma ordem de pagamento ou um numero de empenho!");
 	return false;
  }
}
function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
}


function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho(chave,erro){
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.e60_numemp.value = ''; 
  }
}
function js_mostraempempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisae50_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e50_codord.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
  }
}
function js_mostrapagordem(chave,erro){
  if(erro==true){ 
    document.form1.e50_codord.focus(); 
    document.form1.e50_codord.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e50_codord.value = chave1;
  db_iframe_pagordem.hide();
}
</script>
<?
if(isset($erro_msg)){
  db_msgbox($erro_msg);
  if(isset($e60_numemp)){
    $clpagordem->sql_record($clpagordem->sql_query(null,'e50_codord as codord',"","e50_numemp = $e60_numemp")); 
     $numrows01 = $clpagordem->numrows;
     if($numrows01>0){
       echo "<script>";
       echo "js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem001.php?funcao_js=parent.js_mostrapagordem1|e50_codord&chave_e50_numemp=$e60_numemp','Pesquisa',true);";
       echo "</script>";
     }  
  }
}

?>