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

require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_avaliacaoperguntaopcaolayoutcampo_classe.php");
require_once("dbforms/db_funcoes.php");
$oDaoAvaliacaoPergunta = new cl_avaliacaoperguntaopcaolayoutcampo;
$oDaoAvaliacaoPergunta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db52_descr");
$clrotulo->label("db104_descricao");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, strings.js, datagrid.widget.js, prototype.js");
      db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
    <form name="form1" id='' method="post">
    <center>      
      <div style='display:table;'>
      <fieldset>
      <legend style="font-weight: bold">Vincular Campos</legend>
        <table border='0'>
          <tr>
            <td nowrap title="<?=@$Ted313_db_layoutcampo?>" >
              <?
                db_ancora($Led313_db_layoutcampo, "js_pesquisaed313_db_layoutcampo(true)", 1);
              ?>
            </td>
            <td nowrap >
              <?php 
                db_input("ed313_db_layoutcampo", 
                         10, 
                         $Ied313_db_layoutcampo, 
                         true, 
                         "text", 
                         1, 
                         "onchange='js_pesquisaed313_db_layoutcampo(false);'"
                        );
                db_input("db52_descr", 50, $Idb52_descr, true, "text", 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted313_avaliacaoperguntaopcao?>" >
              <?
                db_ancora($Led313_avaliacaoperguntaopcao, "js_pesquisaed313_avaliacaoperguntaopcao(true)", 1);
              ?>
            </td>
            <td nowrap >
              <?
                db_input("ed313_avaliacaoperguntaopcao", 
                         10, 
                         $Ied313_avaliacaoperguntaopcao, 
                         true,
                         "text", 
                         1,
                         "onchange='js_pesquisaed313_avaliacaoperguntaopcao(false);'"
                        );
                db_input("db104_descricao", 50, $Idb104_descricao, true, "text", 3, "");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap id="ano" >
              <b>Ano: </b>
            </td>
            <td nowrap>
              <?
                $ed313_ano = db_getsession("DB_anousu");
                db_input("ed313_ano", 10, $Ied313_ano, true, "text", 1, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap id="ano" >
              <b><?=$Led313_layoutvalorcampo?></b>
            </td>
            <td nowrap>
              <?
                db_input("ed313_layoutvalorcampo", 10, $Ied313_layoutvalorcampo, true, "text", 1, '');
              ?>
            </td>
          </tr>
        </table>
        <?
          db_menu(db_getsession("DB_id_usuario"), 
                  db_getsession("DB_modulo"), 
                  db_getsession("DB_anousu"),
                  db_getsession("DB_instit")
                 );
        ?>
      </fieldset>
    </div>
    <input name="btnIncluir" type="button" value="Incluir" onclick="js_incluir()">
    <div style='width: 70%'>
      <fieldset>
        <legend style="font-weight: bold">Vínculos Realizados</legend>
            <div id="ctnDataGridVinculos">
            </div>
          </fieldset>
        </div>
      </center>
    </form>
  </body>
</html>
<script>

var sUrlRPC = 'con4_vincularcampolayout.RPC.php';
          
function js_init() {

  oDataGridVinculo = new DBGrid("gridVinculo");
  oDataGridVinculo.nameInstance = "oDataGridVinculo";
  oDataGridVinculo.setCellWidth(new Array("20%", "20%", "20%", "8%", "8%", "8%", "5%"));
  oDataGridVinculo.setCellAlign(new Array("left", 
                                      	  "left", 
                                      	  "left", 
                                      	  "left", 
                                      	  "center", 
                                      	  "center",
                                      	  "center"
                                      	 ));
  oDataGridVinculo.setHeader(new Array("Campo", 
                                       "Linha", 
                                       "Layout", 
                                       "Resposta", 
                                       "Valor Padrão",  
                                       "Avaliação",
                                       "Ação"
                                      ));
  oDataGridVinculo.show($("ctnDataGridVinculos"));
  
}

js_init();

/* 
 * Funções de pesquisa "Campo Layout
 */
function js_pesquisa_ed313_db_layoutcampo(mostra) {
  
  js_OpenJanelaIframe('top.corpo', 
                      'db_iframe_db_layoutcampo', 
                      'func_db_layoutcampos.php?funcao_js.parent.js_preenchepesquisaed313_db_layoutcampo|ed313_db_layoutcampo', 
                      'Pesquisa', 
                      true
                     );
  
}

function preenchepesquisaed313_db_layoutcampo(chave) {
  
  db_iframe_db_layoutcampo.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
  
}

function js_pesquisaed313_db_layoutcampo(mostra){
  
  if(mostra==true){
    
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_db_layoutcampo', 
                        'func_db_layoutcampos.php?funcao_js=parent.js_mostraed313_db_layoutcampo_true|db52_codigo|db52_descr', 
                        'Pesquisa', 
                        true
                       );
    
  }else{
    
     if(document.form1.ed313_db_layoutcampo.value != ''){ 
       
       js_OpenJanelaIframe('top.corpo', 
                           'db_iframe_db_layoutcampo', 
                           'func_db_layoutcampos.php?pesquisa_chave='+document.form1.ed313_db_layoutcampo.value+'&funcao_js=parent.js_mostraed313_db_layoutcampo_erro', 
                           'Pesquisa', 
                           false
                          );
       
     }else{
       document.form1.db52_descr.value = ''; 
     }
  }
}

function js_mostraed313_db_layoutcampo_erro(chave,erro){
  
  document.form1.db52_descr.value = chave; 
  if(erro==true){ 
    
    document.form1.ed313_db_layoutcampo.focus(); 
    document.form1.ed313_db_layoutcampo.value = ''; 
    
  }
  
}

function js_mostraed313_db_layoutcampo_true(chave1,chave2){
  
  document.form1.ed313_db_layoutcampo.value = chave1;
  document.form1.db52_descr.value = chave2;
  db_iframe_db_layoutcampo.hide();
  
}

/* 
 * Funções de pesquisa "Resposta"
 */
function js_pesquisa_ed313_avaliacaoperguntaopcao(mostra) {
  
  js_OpenJanelaIframe('top.corpo', 
                      'db_iframe_avaliacaoperguntaopcao', 
                      'func_avaliacaoperguntaopcap.php?funcao_js.parent.js_preenchepesquisaed313_avaliacaoperguntaopcao|ed313_avaliacaoperguntaopcao', 
                      'Pesquisa', 
                      true
                     );
  
}

function preenchepesquisaed313_avaliacaoperguntaopcao(chave) {
  
  db_iframe_db_layoutcampo.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
    
}

function js_pesquisaed313_avaliacaoperguntaopcao(mostra){
  
  if(mostra==true){
    
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_avaliacaoperguntaopcao', 
                        'func_avaliacaoperguntaopcao.php?funcao_js=parent.js_mostraed313_avaliacaoperguntaopcao_true|db104_sequencial|db104_descricao', 
                        'Pesquisa', 
                        true
                       );
    
  }else{
    
     if(document.form1.ed313_avaliacaoperguntaopcao.value != ''){ 
       
       js_OpenJanelaIframe('top.corpo', 
                           'db_iframe_avaliacaoperguntaopcao', 
                           'func_avaliacaoperguntaopcao.php?pesquisa_chave='+document.form1.ed313_avaliacaoperguntaopcao.value+'&funcao_js=parent.js_mostraed313_avaliacaoperguntaopcao_erro', 
                           'Pesquisa', 
                           false
                          );
       
     }else{
       document.form1.db104_descricao.value = ''; 
     }
  }
}

function js_mostraed313_avaliacaoperguntaopcao_erro(chave,erro){
  
  document.form1.db104_descricao.value = chave; 
  if(erro==true){ 
    
    document.form1.ed313_avaliacaoperguntaopcao.focus(); 
    document.form1.ed313_avaliacaoperguntaopcao.value = ''; 
    
  }
  
}

function js_mostraed313_avaliacaoperguntaopcao_true(chave1,chave2){
  
  document.form1.ed313_avaliacaoperguntaopcao.value = chave1;
  document.form1.db104_descricao.value = chave2;
  db_iframe_avaliacaoperguntaopcao.hide();
  
}


function js_incluir() {

	var iLayoutCampo = $F('ed313_db_layoutcampo');
	var iResposta    = $F('ed313_avaliacaoperguntaopcao');
	var iAno         = $F('ed313_ano');
  
	if (iLayoutCampo == '') {
  	
    alert ('Deve ser informado um valor em Código do Campo');
    $('ed313_db_layoutcampo').focus();
    return false;
    
	} 
	if (iResposta == '') {

		alert ('Deve ser informado um valor em Resposta');
		$('ed313_avaliacaoperguntaopcao').focus();
    return false;
    
	}
	if (iAno == '') {

		alert ('Deve ser informado o Ano');
		$('ed313_ano').focus();
    return false;
		
	}

  js_divCarregando("Aguarde, incluindo vínculo", "msgBox");
  var oParametro                = new Object();
  oParametro.exec               = 'incluirVinculo';
  oParametro.dados              = new Object();
  oParametro.dados.iLayoutCampo = iLayoutCampo;
  oParametro.dados.iResposta    = iResposta;
  oParametro.dados.iAno         = iAno;
  oParametro.dados.sValor       = $F('ed313_layoutvalorcampo');
  
	var oAjax = new Ajax.Request(sUrlRPC,
			                         {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornoSalvar
			                         } 
                              );
  
}

function js_retornoSalvar(oResponse) {

  js_removeObj("msgBox");
  if (oResponse.responseText) { 

    $('ed313_db_layoutcampo').value         = '';
    $('db52_descr').value                   = '';
    $('ed313_avaliacaoperguntaopcao').value = '';
    $('db104_descricao').value              = '';
    $('ed313_layoutvalorcampo').value       = ''; 
    js_buscar();

  } else {
  	alert ('Erro ao salvar');
  }
	
}

/*
 * Função para chamar a listagem dos vínculos
 */
function js_buscar () {

  var oParametro      = new Object();
  oParametro.exec     = 'listarVinculos';

  var oAjax = new Ajax.Request(sUrlRPC,
                               {
                                method: 'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_preencheBusca
                               } 
                              );
	
}

/*
 * Função para preencher o DBGrid
 */
function js_preencheBusca (oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');
  oDataGridVinculo.clearAll(true);
  oRetorno.aDados.each(function (oLinha, iContador) {

    var aLinha = new Array();
    aLinha[0] = oLinha.db52_nome.urlDecode();
    aLinha[1] = oLinha.db51_descr.urlDecode();
    aLinha[2] = oLinha.db50_descr.urlDecode();
    aLinha[3] = oLinha.db104_descricao.urlDecode();
    aLinha[4] = oLinha.ed313_layoutvalorcampo.urlDecode();
    aLinha[5] = "";
    aLinha[6] = '<input type="button" value="E" onclick="js_excluir('+oLinha.ed313_sequencial+')" />';
    oDataGridVinculo.addRow(aLinha);
    
  }); 
  
	oDataGridVinculo.renderRows();
	
}

/*
 * Função que passa o parametro para exclusão do vínculo
 */
function js_excluir(iCodigoVinculo) {

  var oParametro            = new Object();
  oParametro.exec           = 'excluirVinculo';
  oParametro.iCodigoVinculo = iCodigoVinculo;
  
  if (!confirm('Você confirma a exclusão do vínculo?')) {
    return false;
  }

  js_divCarregando('Aguarde, excluindo vínculo','msgBox');
  var oAjax = new Ajax.Request(sUrlRPC,
                            		{
                              		method: 'post',
                              		parameters: 'json='+Object.toJSON(oParametro),
                              		onComplete: js_retornaExclusao
                            		}
  	                          );
	
}

function js_retornaExclusao(oResponse) {

	if (oResponse.responseText) {

		alert ('Vínculo excluído com sucesso');
		js_removeObj('msgBox');
    js_buscar();
    
	} else {
		alert ('Erro ao excluir');
	}
}
 
js_buscar();

</script>