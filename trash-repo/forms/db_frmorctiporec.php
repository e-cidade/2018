<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: orcamento
$clorctiporec->rotulo->label();
?>
<form name="form1" method="post" action="">
	<table border="0">
		<tr>
			<td>
				<fieldset>
					<legend>
					  <b>Recurso</b>
					</legend>
					<table>
	          <tr>
	            <td nowrap title="<?=@$To15_codigo?>">
	              <b>Código:</b>
	            </td>
	            <td>
						    <?
						      db_input('o15_codigo', 10, $Io15_codigo, true, 'text', $db_opcao, "onblur='js_preencherCodigoRecurso();'");
						    ?>
	            </td>
	          </tr>
            <tr id="boxMascara" style="display: none;">
              <td>
                <b>Máscara:</b>
              </td>
              <td>
                <?
                  db_input('sMascara', 40, null, true, 'text', 3);
                ?>
              </td>
            </tr>
	          <tr>
	            <td nowrap title="<?=@$To15_codtri?>">
	              <?=@$Lo15_codtri?>
	            </td>
	            <td>
							  <?
								  db_input('o15_codtri', 40, $Io15_codtri, true, 'text', $db_opcao, "onblur='js_preencherCodigoRecurso();'");
								?>
	            </td>
	          </tr>
	          <tr>
	            <td nowrap title="<?=@$To15_descr?>">
	              <b>Descrição:</b>
	            </td>
	            <td>
	              <?
	                db_input('o15_descr', 40, $Io15_descr, true, 'text', $db_opcao);
	              ?>
	            </td>
	          </tr>
            <tr id="boxTipo" style="display: none;">
              <td nowrap title="Tipo">
                <b>Tipo:</b>
              </td>
              <td>
                <?
                  $aTipo = array('1' => 'Sintético',
                                 '2' => 'Analítico');
                  db_select('iTipo', $aTipo, true, $db_opcao);
                ?>
              </td>
            </tr>
	          <tr>
	            <td nowrap title="<?=@$To15_tipo?>">
	              <?=@$Lo15_tipo?>
	            </td>
	            <td>
							  <?
								  $aTipoRecurso = array('1' => 'Recurso Livre',
								                        '2' => 'Recurso Vinculado');
								  db_select('o15_tipo', $aTipoRecurso, true, $db_opcao);
								?>
	            </td>
	          </tr>
	          <tr>
	            <td nowrap title="<?=@$To15_datalimite?>">
	              <b>Data Limite:</b>
	            </td>
	            <td>
							  <?
								  db_inputdata('o15_datalimite', @$o15_datalimite_dia, @$o15_datalimite_mes, @$o15_datalimite_ano, true, 'text', $db_opcao);
								?>
	            </td>
	          </tr>
	          <tr>
	            <td colspan="2" nowrap title="<?=@$To15_finali?>">
	              <fieldset>
	                <legend>
	                  <b>Finalidade</b>
	                </legend>
	                <?
	                  db_textarea('o15_finali', 10, 60, $Io15_finali, true, 'text', $db_opcao);
	                ?>
	              </fieldset>
	            </td>
	          </tr>
				  </table>
			  </fieldset>
			</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
    <tr align="center">
      <td>
        <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
               type="button" 
               id="db_opcao" 
               value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
               <?=($db_botao==false?"disabled":"")?>>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
      </td>
    </tr>
	</table>
</form>
<script>
var sUrlRPC = 'orc4_manutencaoRecurso.RPC.php';

function js_pesquisa() {

  var sUrl = 'func_orctiporec.php?funcao_js=parent.js_preenchepesquisa|o15_codigo&ativo=1';
  js_OpenJanelaIframe('', 'db_iframe_orctiporec', sUrl, 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_orctiporec.hide();
  js_buscaDadosRecurso(chave);
}

function js_buscaDadosRecurso(iCodigoRecurso) {
  
  if ($('db_opcao').value != 'Incluir') {
  
	  if (iCodigoRecurso == '') {
	  
	    alert("Informe o código do recurso!");
	    return false;
	  }
	  
	  js_divCarregando('Aguarde buscando recurso...', 'msgBoxGetDadosRecurso');
	   
	  var oParam           = new Object();
	  oParam.exec          = "getDadosRecurso";
	  oParam.codigorecurso = iCodigoRecurso;
	    
	  var oAjax   = new Ajax.Request( sUrlRPC, 
	                                  {
	                                    method: 'post', 
	                                    parameters: 'json='+js_objectToJson(oParam), 
	                                    onComplete: js_retornoDadosRecurso
	                                  }
	                                );
  }
}

function js_retornoDadosRecurso(oAjax) {

  js_removeObj("msgBoxGetDadosRecurso");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  } else {
    
    $('o15_codigo').value     = oRetorno.codigorecurso;
    $('o15_descr').value      = oRetorno.descricaorecurso.urlDecode();
    $('o15_codtri').value     = oRetorno.codigotribunalrecurso;
    $('o15_finali').value     = oRetorno.finalidaderecurso.urlDecode();
    
    if ($('db_opcao').value == 'Alterar') {
    
      $('iTipo').value          = oRetorno.tipo;
      $('o15_codigo').disabled  = true;
      $('o15_codigo').style.backgroundColor = "#DEB887";
      $('o15_tipo').value       = oRetorno.tiporecurso;
      $('o15_datalimite').value = js_formatar(oRetorno.datalimiterecurso, 'd');
    } else {
    
      $('iTipo_select_descr').value    = (oRetorno.tipo==1?'Sintético':'Analítico');  
	    $('o15_tipo_select_descr').value = (oRetorno.tiporecurso==1?'Recurso Livre':'Recurso Vinculado');	    
	    $('o15_datalimite').value        = js_formatar(oRetorno.datalimiterecurso, 'd');
    }
    
    $('db_opcao').disabled = false;
    
    js_preencherCodigoRecurso();
    return true;
  }
}

function js_buscaDadosMascara() {

  js_divCarregando('Aguarde buscando mascara...', 'msgBoxGetDadosMascara');
  var oParam  = new Object();
  oParam.exec = "getDadosMascara";
    
  var oAjax   = new Ajax.Request( sUrlRPC, 
                                  {
                                    method: 'post', 
                                    parameters: 'json='+js_objectToJson(oParam), 
                                    onComplete: js_retornoDadosMascara
                                  }
                                );
}

function js_retornoDadosMascara(oAjax) {

  js_removeObj("msgBoxGetDadosMascara");
  var oRetorno = eval("("+oAjax.responseText+")");
  
  $('boxMascara').hide();
  $('boxTipo').hide();
  //$('o15_codtri').focus();
  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    $('sMascara').value = "";
    $('iTipo').value = '1';
    return false;
  } else {
    
    $('sMascara').value   = oRetorno.mascara.urlDecode();
    $('o15_codtri').value = oRetorno.mascara.urlDecode();
    $('o15_codtri').maxLength = oRetorno.mascara.urlDecode().length;
    new MaskedInput("#o15_codtri", oRetorno.mascara.urlDecode().replace(/0/g,"*"), {placeholder:"0"});  
    
    js_preencherCodigoRecurso();
    if (oRetorno.niveis > 1) {
    
      $('boxMascara').show();
      $('boxTipo').show();
      $('iTipo').value = '2';
    }
    
    return true;
  }
}

function js_salvar() {

  var iCodigoRecurso = $('o15_codigo').value;
  if (iCodigoRecurso == '') {
    
    alert("Informe o código do recurso!");
    return false;
  }

  var iCodigoTribunal = $('o15_codtri').value;
  if (iCodigoTribunal == '') {
    
    alert("Informe o código do tribunal!");
    return false;
  }

  var sFinalidadeRecurso = $('o15_finali').value;
  if (sFinalidadeRecurso == '') {
    
    alert("Informe a finalidade do recurso!");
    return false;
  }

  js_divCarregando('Aguarde salvando recurso...', 'msgBoxSalvarRecurso');
   
  var oParam                   = new Object();
  oParam.exec                  = "salvarRecurso";
  oParam.codigorecurso         = iCodigoRecurso;
  oParam.descricaorecurso      = encodeURIComponent(tagString($('o15_descr').value));
  oParam.tipo                  = encodeURIComponent(tagString($('iTipo').value));
  oParam.codigotribunalrecurso = encodeURIComponent(tagString(iCodigoTribunal));
  oParam.tiporecurso           = $('o15_tipo').value;
  oParam.datalimiterecurso     = $('o15_datalimite').value;
  oParam.finalidaderecurso     = encodeURIComponent(tagString(sFinalidadeRecurso));
  oParam.modo                  = $F('db_opcao') == "Incluir"?1:2;
  var oAjax   = new Ajax.Request( sUrlRPC, 
                                  {
                                    method: 'post', 
                                    parameters: 'json='+js_objectToJson(oParam), 
                                    onComplete: js_retornoSalvar
                                  }
                                );
}

function js_retornoSalvar(oAjax) {

  js_removeObj("msgBoxSalvarRecurso");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.message.urlDecode());  
  if (oRetorno.status == 2) {
        
	  $('o15_codigo').value     = '';
	  $('o15_descr').value      = '';
	  $('o15_finali').value     = '';
	  $('o15_datalimite').value = '';
	  $('iTipo').value          = '1';
	  $('o15_tipo').value       = '1';
	  
	  if ($('db_opcao').value == 'Alterar') {
	    $('db_opcao').disabled = true;
	  }
	  
	  js_preencherCodigoRecurso();
	  js_pesquisa();
    return false;
    
  } else {
    
    $('o15_codigo').style.backgroundColor = "#DEB887";
    //$('o15_codigo').disabled = true;
    //$('db_opcao').value      = 'Alterar';  
    if ($F('db_opcao') == 'Incluir') {
      
      $('o15_codigo').disabled              = false;
      $('o15_codigo').style.backgroundColor = "white";
      $('o15_codigo').value                 = '';
      $('o15_codtri').value                 = $F('sMascara');
      $('o15_descr').value                  = '';
      $('o15_finali').value                 = '';
      $('o15_datalimite').value             = '';
      $('iTipo').value                      = '1';
      $('o15_tipo').value                   = '1';
    }
    return true;
  }
}

function js_remover() {

  var iCodigoRecurso = $('o15_codigo').value;
  if (iCodigoRecurso == '') {
    
    alert("Informe o código do recurso!");
    return false;
  }

  js_divCarregando('Aguarde removendo recurso...', 'msgBoxRemoverRecurso');
   
  var oParam           = new Object();
  oParam.exec          = "removerRecurso";
  oParam.codigorecurso = iCodigoRecurso;
    
  var oAjax   = new Ajax.Request( sUrlRPC, 
                                  {
                                    method: 'post', 
                                    parameters: 'json='+js_objectToJson(oParam), 
                                    onComplete: js_retornoRemover
                                  }
                                );
}

function js_retornoRemover(oAjax) {

  js_removeObj("msgBoxRemoverRecurso");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.message.urlDecode());
  
  $('o15_codigo').value     = '';
  $('o15_descr').value      = '';
  $('o15_finali').value     = '';
  $('sMascara').value       = '';
  $('o15_datalimite').value = '';
  $('iTipo').value          = '1';
  $('o15_tipo').value       = '1';
  $('db_opcao').disabled    = true;
  
  js_preencherCodigoRecurso();
  js_pesquisa();
  return false;
}

function js_preencherCodigoRecurso() {

  if ($('o15_codigo').value == '' || new Number($('o15_codigo').value) == 0) {
    $('o15_codigo').value = $('o15_codtri').value.replace(/\./g,"");
  }
}

$('db_opcao').observe("click", function () {

  if ($('db_opcao').value == 'Excluir') {
    js_remover();
  } else {
    js_salvar();
  }
});
js_buscaDadosMascara();
</script>