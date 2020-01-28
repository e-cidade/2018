<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_contabancaria_classe.php");
include("dbforms/db_funcoes.php");

include("classes/db_conciliapendcorrente_classe.php");
include("classes/db_conciliapendextrato_classe.php");
include("classes/db_conciliacor_classe.php");
include("classes/db_conciliaextrato_classe.php");
include("classes/db_conciliaitem_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clcontabancaria = new cl_contabancaria;
$db_opcao = 1;
$db_botao = false;


//MODULO: Configuracoes
$clcontabancaria->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db89_codagencia");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table align="center" style="padding:45px;">
  <tr> 
    <td> 
    <center>
			<center>
			<fieldset>
			  <legend>
			    <b>Cadastro de Conta Bancária</b>
			  </legend>
			  <table border="0">
			    <tr>
			      <td nowrap title="<?=@$Tdb83_descricao?>">
			        <?=db_ancora(@$Ldb83_sequencial,"js_pesquisadb83_sequencial(true);",1);?>
			      </td>
			      <td> 
			        <?
			          db_input('db83_sequencial',10,$Idb83_sequencial,true,'text',3,"");
			          db_input('db83_descricao',50,$Idb83_descricao,true,'text',3,"");
			        ?>
			      </td>
			    </tr>
          <tr>
            <td nowrap title="<?=@$Tdb83_bancoagencia?>">
              <b>Data de processamento : </b>
            </td>
            <td> 
              <?
                db_inputdata('data',null,null,null,true,'text',1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="Observações">
              <b>Observações da exclusão : </b>
            </td>
            <td> 
              <?
                db_textarea("obs",5,80,null,true,"",1,"");  
              ?>
            </td>
          </tr>
			
			  </table>
			</fieldset>  
			</center>
			<input name="processar" type="button" id="processar" value="Processar" onclick="return js_prescreve();" >

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
function js_prescreve() {

   if ($F('data') == '') {
     alert("Informe a data de processamento ! ");
     return false;
   }

   if ($F('db83_sequencial') == '') {
     alert("Informe a conta para processamento ! ");
     return false;
   }
   
   if ($F('obs') == '') {
     alert("Informe a Observação ! ");
     return false;
   }
   
  var oParam             = new Object();
  oParam.exec            = 'exclusao';
  oParam.data            = $F('data');
  oParam.db83_sequencial = $F('db83_sequencial');
  oParam.obs             = $F('obs');

  var msgDiv = "Aguarde ...";
  js_divCarregando(msgDiv,'msgBox');

  var oAjax              = new Ajax.Request('cai1_conciliazeralog.RPC.php',
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParam),
                                              onComplete: js_retorno
                                             });   
  
}

function js_retorno(oAjax){

  js_removeObj('msgBox');
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 1) {
    alert("Exclusão efetuada com sucesso!");
  } else {
	  alert(oRetorno.message.urlDecode());
  }

} 


function js_pesquisadb83_sequencial(mostra){

  if(mostra==true){
    var sUrl = 'func_contabancariaconcilia.php?funcao_js=parent.js_mostrasequencial1|db83_sequencial|db83_descricao'
  }else{
    var sUrl = 'func_contabancariaconcilia.php?pesquisa_chave='+$F('db83_sequencial')+'&funcao_js=parent.js_mostrasequencial|db83_sequencial|db83_descricao';
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria',sUrl,'Pesquisa',mostra);
  
}

function js_mostrasequencial(chave1,chave2){
  $('db83_sequencial').value = chave1;
  $('db83_descricao').value  = chave2;
}

function js_mostrasequencial1(chave1,chave2){
  
  $('db83_sequencial').value = chave1;
  $('db83_descricao').value  = chave2;
  db_iframe_contabancaria.hide();
  
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_preenchepesquisa|db83_sequencial','Pesquisa',true);

}
function js_preenchepesquisa(chave1,chave2){
  $F('db83_sequencial') = chave1;
  $F('db83_descricao')  = chave2;
  db_iframe_contabancaria.hide();
}

</script>