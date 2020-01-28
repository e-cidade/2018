<?php
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

require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js"); 
  db_app::load("estilos.css");
  ?>
</head>
<body style='margin-top: 25px' bgcolor="#cccccc">

<center>      
<div style="width: 500px;" >
  <form name="form1" id='frmFichaAcompanhamento' method="post">
    <fieldset>
      <legend style="font-weight: bold">Familias por Bairro</legend>
      <table style="width:100%;">
          <tr>
            <td  class='bold'>Família:</td>
            <td >
              <select id='familia' style="width:100%;">
                <option value='1' selected="selected"> Todas</option>
                <option value='2' >Somente com Benefícios</option> 
              </select>
            </td>
          </tr>
      </table>
      <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
        <legend>Lista de Bairros</legend>
        <table>
          <tr>
            <td id='ctnBairro'></td>
            <td>
             <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
             <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
             <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
             <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
            </td>
            <td id='ctnBairroSelecionados'></td>
          </tr>
        </table>
      </fieldset>
    </fieldset>
    <input type="button" value="Imprimir" id="imprimir"  />
  </form>
</div>
</center>
</body>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">
  
var sUrlRPC     = "soc4_relatoriossociais.RPC.php";
var sUrlRPCBase = 'soc4_importabasemunicipio.RPC.php';

function js_pesquisaFamiliasSemAvaliacao() {

  var oParametro  = new Object();
  oParametro.exec = 'getTotalCidadoesFamiliasSemAvaliacao';

  var oAjax = new Ajax.Request(sUrlRPCBase,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaFamiliasSemAvaliacao
                               }
                              );
}

/**
 * Caso existam familias ou cidadaos com avaliacoes nao processadas, apresenta a mensagem ao usuario
 */
function js_retornaPesquisaFamiliasSemAvaliacao(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.qtdFamiliaSemAvaliacao > 0 || oRetorno.qtdCidadaoSemAvaliacao > 0) {

    sMsg  = 'Existem avaliações ainda não processadas.';
    sMsg += '\nAvaliações de Famílias: '+oRetorno.qtdFamiliaSemAvaliacao;
    sMsg += '\nAvaliações de Cidadãos: '+oRetorno.qtdCidadaoSemAvaliacao;
    sMsg += '\nPara um relatório completo, processe as demais avaliações em: ';
    sMsg += '\nProcedimentos -> Cadastro Único -> Processar Avaliação Sócio Econômica';
    alert(sMsg);
  }
}

var oCboBairro = new DBComboBox("cboBairro", "oCboBairro", null, "200px", 10);
oCboBairro.setMultiple(true);
oCboBairro.addEvent("onDblClick", "moveSelected(oCboBairro, oCboBairroSelecionados)");
oCboBairro.show($('ctnBairro'));

var oCboBairroSelecionados = new DBComboBox("cboBairroSelecionados", "oCboBairroSelecionados", null, "200px", 10);
oCboBairroSelecionados.setMultiple(true);
oCboBairroSelecionados.addEvent("onDblClick", "moveSelected(oCboBairroSelecionados, oCboBairro)");
oCboBairroSelecionados.show($('ctnBairroSelecionados'));

/**
 * Controla ações de movimentos entre os select box
 */
function moveSelected(oCboOrigem, oCboDestino) {

	if(oCboOrigem.getValue() != null) {

		var aItens = oCboOrigem.getValue();
		aItens.each(function(oLinha, iContador) {

			oLinha = oCboOrigem.aItens[oLinha];
			oCboDestino.addItem(oLinha.id, oLinha.descricao);
			oCboOrigem.removeItem(oLinha.id);
		});
	}
}

function moveAll(oCboOrigem, oCboDestino) {

	oCboOrigem.aItens.each(function(oLinha, iContador) {

		oCboDestino.addItem(oLinha.id, oLinha.descricao);
		oCboOrigem.removeItem(oLinha.id);
	});
}

$('btnMoveOneRightToLeft').observe("click", function() {
	moveSelected(oCboBairro, oCboBairroSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboBairroSelecionados, oCboBairro);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboBairro, oCboBairroSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboBairroSelecionados, oCboBairro);
});

function js_listaBairros() {
  
  var oObjeto   = new Object();
  oObjeto.exec  = 'buscaBairrosCidadao';
  js_divCarregando("Aguarde, pesquisando bairros.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           sUrlRPC,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oObjeto),
    	                           onComplete: js_retornaBairros
  	                           }
  	                          );
}

function js_retornaBairros(oAjax) {

  oCboBairro.clearItens();
  oCboBairroSelecionados.clearItens();
  var oRetorno = eval('('+oAjax.responseText+')');
	js_removeObj("msgBox");

	oRetorno.dados.each(function(sLinha, iContador) {
		oCboBairro.addItem(iContador, sLinha);
	});
}

$('imprimir').observe('click', function () {

  var aBairros = new Array();

  if ($('cboBairroSelecionados').options.length == 0) {
    
    alert("Você deve selecionar um bairro.");
    return false;
  }

  oCboBairroSelecionados.aItens.each(function(oLinha, id) {
    aBairros.push(oLinha.descricao);
	});

  var sLocation  = "soc2_familiabairro002.php?";
	sLocation     += "&sBairros="+aBairros;
	sLocation     += "&iTipoFamilia="+$F('familia');
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0);
});

js_pesquisaFamiliasSemAvaliacao();
js_listaBairros();
</script>