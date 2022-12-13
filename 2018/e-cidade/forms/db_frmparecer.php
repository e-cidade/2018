<?
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

//MODULO: educação
$clparecer->rotulo->label();
?>
<form name="form1" method="post" action="">
  <fieldset style="width: 590px;">
    <legend><b>Cadastro de Pareceres</b></legend>
    <table border="0">
      <tr>
        <td nowrap="nowrap" title="<?=@$Ted92_i_codigo?>" >
          <?=@$Led92_i_codigo?>
        </td>
        <td>
          <?db_input('ed92_i_codigo',10,$Ied92_i_codigo,true,'text',3,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted92_i_sequencial?>">
          <?=@$Led92_i_sequencial?>
        </td>
        <td>
          <?db_input('ed92_i_sequencial',10,$Ied92_i_sequencial,true,'text',3,"")?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted92_c_descr?>">
          <?=@$Led92_c_descr?>
        </td>
        <td>
          <?
          db_textarea('ed92_c_descr', 2, 88, $Ied92_c_descr, true, 'text', 1, null, null, null, 150)
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Selecione a(s) disciplina(s) para vincular ao parecer.">
          <?db_ancora('<b>Disciplina:</b>', "js_pesquisadisciplina(true);", $db_opcao);?>
        </td>
        <td>
          <div id="arquivodisciplina" >
            <?db_input('lista_disciplinas', 50, '', true, 'hidden', 3, '')?>
            <select multiple size="5" name="disciplina" id="disciplina" style="width:653px;"
                    onDblClick="js_apagarLinha(this);"
                    <?=($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22 ?"" : "disabled")?> >
            </select>
            <p align="center"><b>Dois cliques sobre o item exclui!</b></p>
          </div>
        </td>
      </tr>
      <tr>
        <td nowrap title="Selecione o(s) período(s) para vincular ao parecer.">
          <?db_ancora('<b>Período:</b>', "js_pesquisaPeriodo(true);", $db_opcao);?>
        </td>
        <td>
          <div id="arquivoperiodo" >
            <?db_input('lista_periodos', 50, '', true, 'hidden', 3, '')?>
            <select multiple size="5" name="periodo" id="periodo" style="width:653px;"
                    onDblClick="js_apagarLinhaPeriodo(this);"
                    <?=($db_opcao == 1 || $db_opcao == 2 || $db_opcao == 22 ?"" : "disabled")?> >
            </select>
            <p align="center"><b>Dois cliques sobre o item exclui!</b></p>
          </div>
        </td>
      </tr>
    </table>
  </fieldset>
  <div>
    <?php if ($db_opcao != 3) {?>
      <input name="salvar" id="salvar" type="button" value="Salvar" onclick="js_salvar();" />
    <?php } else {?>
      <input name="excluir" id="excluir" type="button" value="Excluir" onclick="js_excluirParecer();" />
    <?php }?>
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
    <input name="ordenar" type="button" value="Ordenar Pareceres" onclick="js_ordenar(<?=$db_opcao?>,'<?=@$ed92_i_codigo?>');" />
    <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" />
  </div>
</form>
<script type="text/javascript">

var oUrl     = js_urlToObject();
var sUrlRpc  = "edu4_parecer.RPC.php";
var iDbOpcao = <?php echo $db_opcao;?>;
$('lista_disciplinas').value = '';

function js_salvar() {

  var oObject              = new Object();
  oObject.exec             = 'Salvar';
  oObject.opcao            = iDbOpcao;
  oObject.iCodigo          = $F('ed92_i_codigo');
  oObject.iSequencia       = $F('ed92_i_sequencial');
  oObject.sDescricao       = encodeURIComponent(tagString($F('ed92_c_descr')));
  oObject.sListaDisciplina = $F('lista_disciplinas');
  oObject.sListaPeriodos   = $F('lista_periodos');

  js_divCarregando("Aguarde...salvando dados", "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oObject),
    	                           asynchronous: false,
    	                           onComplete: js_retornaSalvar
  	                           }
  	                          );
}

function js_retornaSalvar(oAjax) {

	js_removeObj("msgBox");
	var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
	if (oRetorno.status == 1) {

	  iDbOpcao                     = 2;
	  $('ed92_i_codigo').value     = oRetorno.iCodigo;
	  $('ed92_i_sequencial').value = oRetorno.iSequencia;

	  var sUrlAbaTurmas  = 'edu1_parecerturmanovo002.php?codigoparecer='+oRetorno.iCodigo;
	      sUrlAbaTurmas += '&descrparecer='+$F('ed92_c_descr');
	      sUrlAbaTurmas += '&listadisciplinas='+$F('lista_disciplinas');
        sUrlAbaTurmas += '&lPesquisaVinculos';

    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a2.style.color = "black";
	  top.corpo.iframe_a2.location.href = sUrlAbaTurmas;
	  parent.mo_camada("a2");
  }

}


function js_carregarParecer(iParecer) {

  var oObject              = new Object();
  oObject.exec             = 'buscarParecer';
  oObject.iCodigo          = iParecer;
  js_divCarregando("Aguarde...carregando dados do parecer", "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
                               { method:     'post',
                                 parameters: 'json='+Object.toJSON(oObject),
                                 asynchronous: false,
                                 onComplete: js_retornaParecer
                               }
                              );
}

function js_retornaParecer(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');

  var oListaPeriodos             = $('lista_periodos');
  var oListaDisciplinas          = $('lista_disciplinas');
  oListaPeriodos.value           = '';
  oListaDisciplinas.value        = '';
  $('disciplina').options.length = 0;
  $('periodo').options.length    = 0
  if (oRetorno.status == 1) {

    $('ed92_i_codigo').value     = oRetorno.iCodigo;
    $('ed92_i_sequencial').value = oRetorno.iSequencia;
    $('ed92_c_descr').value      = oRetorno.sDescricao.urlDecode();

    var iCountDisciplinas = oRetorno.dados.length;
    var iCountPeriodos    = oRetorno.aPeriodos.length;


    var sVirgula            = '';
    for (var i = 0; i < iCountDisciplinas; i++) {

      var oOption       = document.createElement('option');
      oOption.value     = oRetorno.dados[i].codigo_disciplina;
      oOption.innerHTML = oRetorno.dados[i].disciplina.urlDecode();
      $('disciplina').appendChild(oOption);

      oListaDisciplinas.value += sVirgula+oRetorno.dados[i].codigo_disciplina;
      sVirgula                 = ',';
    }

    sVirgula = '';
    for (var i = 0; i < iCountPeriodos; i++) {

      var oOption = document.createElement('option');
      oOption.value     = oRetorno.aPeriodos[i].ed09_i_codigo;
      oOption.innerHTML = oRetorno.aPeriodos[i].periodo.urlDecode();
      $('periodo').appendChild(oOption);

      oListaPeriodos.value +=  sVirgula+oRetorno.aPeriodos[i].ed09_i_codigo;
      sVirgula              = ',';
    }
  }

  var sUrlAbaTurmas  = 'edu1_parecerturmanovo002.php?codigoparecer='+oRetorno.iCodigo;
      sUrlAbaTurmas += '&descrparecer='+$F('ed92_c_descr');
      sUrlAbaTurmas += '&listadisciplinas='+$F('lista_disciplinas');

  parent.document.formaba.a2.disabled    = false;
  parent.document.formaba.a2.style.color = "black";
  top.corpo.iframe_a2.location.href      = sUrlAbaTurmas;
}


function js_excluirParecer() {

  var oObject              = new Object();
  oObject.exec             = 'excluirParecer';
  oObject.iCodigo          = $F('ed92_i_codigo');

  var oAjax = new Ajax.Request(sUrlRpc,
                               { method:     'post',
                                 parameters: 'json='+Object.toJSON(oObject),
                                 asynchronous: false,
                                 onComplete: js_retornoExcluiParecer
                               }
                              );
}

function js_retornoExcluiParecer(oAjax) {

  var oRetorno = eval('('+oAjax.responseText+')');
  alert(oRetorno.message.urlDecode());
  document.form1.pesquisar.click();
  document.form1.reset();
}


function js_ordenar(opcao,codigo){
 js_OpenJanelaIframe('','db_iframe_ordenacao','edu1_parecer004.php?opcaoatual='+opcao+'&codigoparec='+codigo,'Ordenar Pareceres',true);
}
function js_pesquisa(){
 js_OpenJanelaIframe('','db_iframe_parecer','func_parecer.php?funcao_js=parent.js_preenchepesquisa|ed92_i_codigo','Pesquisa Pareceres',true);
}
function js_preenchepesquisa(chave) {
  db_iframe_parecer.hide();
  if (iDbOpcao != 1) {
    js_carregarParecer(chave)
  }
}
function js_novo(){
 location.href = "edu1_parecer001.php";
}

function js_pesquisadisciplina(mostra) {

  js_OpenJanelaIframe('', 'db_iframe_disciplinaparecer',
                      'func_disciplinaparecer.php?funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed10_c_abrev|ed232_c_descr',
                      'Pesquisa de Disciplinas', true
                     );

}

function js_mostradisciplina1(iCodigo, sEnsino, sDisciplina) {

	if (js_checaSelect(iCodigo, 'disciplina') == false) {

		var sString = sDisciplina + " ["+sEnsino+"]";
    $('disciplina').options[$('disciplina').length] = new Option(sString, iCodigo);
	}

	if ($F('lista_disciplinas') == '') {
  	$('lista_disciplinas').value = iCodigo;
	} else {
	  $('lista_disciplinas').value += ","+iCodigo;
	}

	db_iframe_disciplinaparecer.hide();
}

function js_checaSelect(iCodigo, id) {

  var oSelect = $(id);
  var iSize   = oSelect.length;
  var lResult = false;

  for (var iCont = 0; iCont < iSize; iCont++) {

    if (oSelect.options[iCont].value == iCodigo) {
      lResult = true;
    }
  }

  return lResult;
}

function js_apagarLinha(oAux) {

  var oObject              = new Object();
  oObject.exec             = 'validaDisciplinaTemTurmaVinculada';
  oObject.iParecer         = $F('ed92_i_codigo');
  oObject.iDisciplina      = oAux.value;
  var oAjax = new Ajax.Request(sUrlRpc,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oObject),
    	                           onComplete: js_retornaApagarLinha
  	                           }
  	                          );
}

function js_retornaApagarLinha(oAjax) {

	var oRetorno = eval('('+oAjax.responseText+')');

	if (oRetorno.status == 1) {

    $('disciplina').options[$('disciplina').selectedIndex] = null;
    js_reescreveLista('lista_disciplinas', 'disciplina');
  } else {
    alert(oRetorno.message.urlDecode());
  }
}


function js_reescreveLista(sIdLista, sIdSelect) {

  var oListaDisciplinas = $(sIdLista);
  var oSelect           = $(sIdSelect);
  var iSize             = oSelect.length;
  var sVirgula          = "";
  oListaDisciplinas.value = '';

  for (var iCont = 0; iCont < iSize; iCont++) {

    oListaDisciplinas.value += sVirgula+oSelect.options[iCont].value;
    sVirgula = ',';
  }

}


function js_pesquisaPeriodo(mostra) {

  js_OpenJanelaIframe('', 'db_iframe_periodoavaliacao',
                      'func_periodoavaliacao.php?funcao_js=parent.js_mostraPeriodo|ed09_i_codigo|ed09_c_descr|ed09_c_abrev&'+
                      'calendario=',
                      'Pesquisa de Períodos de Avaliação', true
                     );
}

function js_mostraPeriodo(iCodigo, sDescricao, sAbreviatura) {

	if (js_checaSelect(iCodigo, 'periodo') == false) {

		var sString = sDescricao + " ["+sAbreviatura+"]";
    $('periodo').options[$('periodo').length] = new Option(sString, iCodigo);
	}

	if ($F('lista_periodos') == '') {
  	$('lista_periodos').value = iCodigo;
	} else {
	  $('lista_periodos').value += ","+iCodigo;
	}

	db_iframe_periodoavaliacao.hide();
}

function js_apagarLinhaPeriodo(oAux) {

  var oObject              = new Object();
  oObject.iPeriodo         = oAux.value;
  $('periodo').options[$('periodo').selectedIndex] = null;
  js_reescreveLista('lista_periodos', 'periodo');
}
</script>