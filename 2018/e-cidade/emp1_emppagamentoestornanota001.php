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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once ("classes/ordemPagamento.model.php");
require_once("libs/db_app.utils.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
db_app::load("scripts.js");
db_app::load("dbtextField.widget.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/DBViewContaCorrenteDetalhe.js");
db_app::load("classes/DBViewNovoDetalhamento.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("widgets/DBAncora.widget.js");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<center>
<table width="790" border="0" cellspacing="0" cellpadding="0" style="margin-top:30px;">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <form name='form1' action='emp1_emppagamentoestornanota002.php'>
     <center>
      <table>
        <tr>
          <td>
        <fieldset>
          <legend><b>Ordem de Pagamento</b></legend>
          <table>
      	    <tr>
        		  <td nowrap title="<?=@$Te50_codord?>" align='right'>
		            <? db_ancora("<b>Ordem de Pagamento:</b>","js_pesquisae50_codord(true);",$db_opcao);  ?>
      	 	   </td>
		         <td>
		           <?
               db_input('e50_codord',8,$Ie50_codord,true,'text',$db_opcao," onchange='js_pesquisae50_codord(false);'")
               ?>
		         </td>
      		  </tr>
          </table>
        </fieldset>
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
</center>


<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
document.getElementById('pesquisar').disabled  = true;


function js_pesquisae50_codord(mostra){

	  document.getElementById('pesquisar').disabled  = true;
	  if(mostra==true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
	  }else{
	    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e50_codord.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
	  }
	}

	function js_mostrapagordem(chave,erro) {

	  document.getElementById('pesquisar').disabled  = false;
	  if(erro==true) {

	    document.getElementById('pesquisar').disabled  = true;
	    document.form1.e50_codord.focus();
	    document.form1.e50_codord.value = '';
	  }
	  js_verificaEmpenho();
	}
	function js_mostrapagordem1(chave1,chave2) {

	  document.getElementById('pesquisar').disabled  = false;
	  document.form1.e50_codord.value = chave1;
	  db_iframe_pagordem.hide();

	  js_verificaEmpenho();
	}

/*
 * funcao para validar se o empenho é uma prestação de contas.
   se for nao podes estornar por essa rotina.
 */
var sUrlRPC = "cai4_devolucaoadiantamento004.RPC.php";
function js_verificaEmpenho() {

	var oParametros             = new Object();
	var msgDiv                  = "Verificando evento do empenho selecionado \n Aguarde ...";
	oParametros.exec            = 'verificaEventoEmpenho';
	oParametros.iOrdemPagamento = $F("e50_codord");

	if ($F("e50_codord") == null || $F("e50_codord") == '') {
    return false;
	}

	js_divCarregando(msgDiv,'msgBox');

	new Ajax.Request(sUrlRPC,
	                 {method: "post",
	                  parameters:'json='+Object.toJSON(oParametros),
	                  onComplete: js_retornoVerificacaoEmpenho
	                 });
}

function js_retornoVerificacaoEmpenho(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("(" + oAjax.responseText + ")");

	if (oRetorno.iStatus == '2') {

	  alert(oRetorno.sMessage.urlDecode());
	  document.getElementById('pesquisar').disabled  = true;
	  return false;
	}
}


function js_entra(){
  if(document.form1.e50_codord.value != ""){
      obj=document.createElement('input');
      obj.setAttribute('name','pag_ord');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','true');
      document.form1.appendChild(obj);
      document.form1.submit();
  }else{

   	alert("Selecione uma nota de liquidação!");
 	  return false;

  }
}
function js_pesquisae60_codemp(mostra) {
document.getElementById('pesquisar').disabled  = true;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
  document.getElementById('pesquisar').disabled  = false;
}


function js_pesquisae60_numemp(mostra) {
 document.getElementById('pesquisar').disabled  = true;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho(chave,erro) {

 document.getElementById('pesquisar').disabled  = false;
  if (erro==true) {

    document.form1.e60_numemp.focus();
    document.form1.e60_numemp.value = '';
    document.getElementById('pesquisar').disabled  = true;
  }
}
function js_mostraempempenho1(chave1){

 document.getElementById('pesquisar').disabled  = false;
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}


</script>
<?
if(isset($erro_msg)){
  db_msgbox($erro_msg);
  if(isset($erro) && $erro != ''){
    echo "<script>";
    echo "js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem001.php?funcao_js=parent.js_mostrapagordem1|e50_codord&chave_e50_numemp=$e60_numemp','Pesquisa',true);";
    echo "</script>";
  }

}

?>