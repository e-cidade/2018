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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, strings.js, dbcomboBox.widget.js");
      db_app::load("estilos.css");
    ?>
    <style type="text/css">
      .fieldset-hr {
        border:none;
        border-top: 1px outset #000;
      }
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
  <div>
    <center>
      <fieldset style="width: 350px;">
        <legend><b>Pessoas EJA</b></legend>
        <form action="" name="form1">
        </form>
        <table>
          <tr>
            <td id='ctnTipoEnsino'></td>
             <td>
             <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
             <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
             <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
             <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
            </td>
            <td id='ctnTipoEnsinoSelecionados'></td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Processar" name='processar' id='btnProcessar'>
    </center>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script>
var sUrlRPCProcessamento = 'soc4_importabasemunicipio.RPC.php';
var sUrlRPCEscolaridade  = 'soc4_relatoriossociais.RPC.php';

var oCboTipoEnsino = new DBComboBox("cboTipoEnsino", "oCboTipoEnsino", null, "350px", 5);
oCboTipoEnsino.setMultiple(true);
oCboTipoEnsino.addEvent("onDblClick", "moveSelected(oCboTipoEnsino, oCboTipoEnsinoSelecionados)");
oCboTipoEnsino.show($('ctnTipoEnsino'));

var oCboTipoEnsinoSelecionados = new DBComboBox("cboTipoEnsinoSelecionados", "oCboTipoEnsinoSelecionados", null, "350px", 5);
oCboTipoEnsinoSelecionados.setMultiple(true);
oCboTipoEnsinoSelecionados.addEvent("onDblClick", "moveSelected(oCboTipoEnsinoSelecionados, oCboTipoEnsino)");
oCboTipoEnsinoSelecionados.show($('ctnTipoEnsinoSelecionados'));

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
	moveSelected(oCboTipoEnsino, oCboTipoEnsinoSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboTipoEnsinoSelecionados, oCboTipoEnsino);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboTipoEnsino, oCboTipoEnsinoSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboTipoEnsinoSelecionados, oCboTipoEnsino);
});

/**
 * Envia os dados para impressao do relatorio
 */
$('btnProcessar').observe("click", function() {

  var aTipoEnsino = new Array();

  oCboTipoEnsinoSelecionados.aItens.each(function(oLinha, id) {
    aTipoEnsino.push(oLinha.id);
	});

  if (aTipoEnsino.length == 0) {

    alert('Selecione ao menos um tipo de ensino');
    return false;
  }

  var aEscolaridadesSelecionadas = new Array();
  oCboTipoEnsinoSelecionados.aItens.each(function(oLinha, iContador) {
     
     if (aEscolaridades[oLinha.id]) {

       aEscolaridadesSelecionadas.push(aEscolaridades[oLinha.id].identificador);
     }
  });
  
  var sLocation  = "soc2_pessoaseja002.php?";
	sLocation     += "&sEscolaridades="+aEscolaridadesSelecionadas+"&sTipoEnsino="+aTipoEnsino;
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0);
});

/**
* Pesquisamos se existem familias sem avaliacao processada
*/
function js_pesquisaFamiliasSemAvaliacao() {

 var oParametro           = new Object();
 oParametro.exec          = 'getTotalCidadoesFamiliasSemAvaliacao';

 var oAjax = new Ajax.Request(sUrlRPCProcessamento,
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

/**
 * Buscamos as escolaridades a serem utilizadas no filtro
 */
var aEscolaridades = new Array();
function js_listaEscolaridade() {
  
  var oParametro   = new Object();
  oParametro.exec  = 'getEscolaridade';
  js_divCarregando("Aguarde, pesquisando escolaridades.", "msgBox");
  var oAjax = new Ajax.Request(
                               sUrlRPCEscolaridade,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oParametro),
    	                           onComplete: js_retornaListaEscolaridade
  	                           }
  	                          );
}

function js_retornaListaEscolaridade(oResponse) {

  oCboTipoEnsino.clearItens();
  oCboTipoEnsinoSelecionados.clearItens();
	js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  aEscolaridades = oRetorno.escolaridades;
	oRetorno.escolaridades.each(function(sLinha, iContador) {
	  oCboTipoEnsino.addItem(iContador, sLinha.descricao.urlDecode());
	});
}

js_pesquisaFamiliasSemAvaliacao();
js_listaEscolaridade();
</script>