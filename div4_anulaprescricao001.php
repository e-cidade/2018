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
include_once("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$clrotulo = new rotulocampo;

$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k31_obs');
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

$instit = db_getsession("DB_instit");



?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" > 
<form class="container" name="form1" method="post" action="" >
  <fieldset>
	<legend>Anular Prescrição por Lista</legend>
	<table class="form-container">
  	  <tr>
  	    <td nowrap title="<?=@$Tk60_codigo?>" >
  	      <?
  	        db_ancora('<b>Lista de débitos : </b>',"js_pesquisalista(true);",4)
  	      ?>
  	    </td>
  	    <td>
  	      <?
  	        db_input('k60_codigo',10,$Ik60_codigo,true,'text',4,"onchange='js_pesquisalista(false);'");
  	        db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
  	      ?>
  	    </td>
  	  </tr>
      <tr>
        <td colspan="2" >
          <fieldset class="separator">
            <legend><?=@$Lk31_obs?></legend> 
              <? 
                db_textarea('k31_obs',2,70,$Ik31_obs,'','text',$db_opcao,"") 
              ?>
          </fieldset>
        </td>
  	  </tr>
    </table>
  </fieldset>     
  <input name="processar" type="button" id="processar" value="Processar" onclick='js_prescreve();'>      
</form>
  
<script>

function js_prescreve() {

  if ( $F('k60_codigo') == '' ) {
  
     alert('Nenhuma lista informada!');
     
  } else if ($F('k31_obs') == '' ){
  
     alert('Preencha o campo Observações');
         
  } else if ( confirm('Deseja realmente anular as prescrições da lista informada ?')){

    js_divCarregando("Aguarde ...",'msgBox');

    var oParam            = new Object();
        oParam.exec       = 'AnulacaoLista';
        oParam.iCodLista  = $F('k60_codigo');
        oParam.obs        = $F('k31_obs');
           
    var oAjax             = new Ajax.Request('div4_anulaprescricao.RPC.php',
                                               {method: "post",
                                                parameters:'json='+Object.toJSON(oParam),
                                                onComplete: js_retorno
                                               });   
  }
}

function js_retorno(oAjax){

  var oRetorno = eval("("+oAjax.responseText+")");
  
  js_removeObj('msgBox');
  
  if ( oRetorno.status == 0 ) {
    alert(""+oRetorno.message.urlDecode()+"");
    return false;
  } else {
    alert("Anulação efetuada com sucesso!");
    $('k60_codigo').value = '';
    $('k31_obs').value    = '';    
  }

}

function js_pesquisalista(mostra){

  if (mostra) {       
    var sUrl = 'func_lista.php?prescricao=true&funcao_js=parent.js_mostralista1|k60_codigo|k60_descr'; 
  } else {
    var sUrl = 'func_lista.php?prescricao=true&pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
  }
  
  js_OpenJanelaIframe('top.corpo','db_iframe',sUrl,'Pesquisa',mostra);
  
}

function js_mostralista(chave,erro){
  
  document.form1.k60_descr.value = chave;
  
  if (erro) {
    document.form1.k60_descr.focus();
    document.form1.k60_descr.value = '';
  }
}

function js_mostralista1(chave1,chave2){
  document.form1.k60_codigo.value = chave1;
  document.form1.k60_descr.value = chave2;
  db_iframe.hide();
}
</script>  

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size9");
</script>