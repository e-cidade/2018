<?PHP
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
require_once("classes/db_conhistdoc_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_empagegera_classe.php");

db_postmemory($HTTP_POST_VARS);
$clempagegera     = new cl_empagegera;
$clrotulo         = new rotulocampo;
$clempagegera->rotulo->label();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>

<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("DBLancador.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");
  db_app::load("dbcomboBox.widget.js");
?>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>

<form name="form1" method="post" action="">

	<fieldset style="margin-top: 50px; width: 800px;">

	<legend><strong>Cancelar Arquivo Transmissão</strong></legend>

		<table border='0' align='left' width="100%">
		  <tr>
		    <td  align="left" nowrap title="Arquivo de transmissão">
		      <? db_ancora("Arquivo:","js_pesquisa_gera(true);",1);?>
		    </td>
		    <td align="left" nowrap>
				  <?
				     db_input("e87_codgera",10,$Ie87_codgera,true,"text",1,"onchange='js_pesquisa_gera(false);'");
				     db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
				  ?>
		    </td>
		  </tr>
		</table>

		<fieldset style="width:98%; margin-top: 30px;">
		  <legend>Movimentos Vinculados</legend>
		  <div id='ctnGridMovimentos'></div>
		</fieldset>
</fieldset>

<div style="margin-top: 10px;">
  <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="js_cancelarArquivo();">
</div>

</form>
</center>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
var sUrlRPC = "cai4_arquivoBanco004.RPC.php";

function js_cancelarArquivo(){

  var iArquivo    = $F("e87_codgera");
  var aMovimentos = js_getMovimentosSelecionados();
	var msgDiv      = "Cancelando movimentos.<br>Aguarde ...";

  if (aMovimentos.length == 0) {

    alert('Selecione um movimento a ser cancelado.');
    return false;
	}

	if (!confirm("Deseja cancelar os movimentos selecionados ?")) {
    return false;
  }

	var oParametros         = new Object();

	oParametros.exec        = 'cancelarMovimentos';
	oParametros.iArquivo    = iArquivo  ;
	oParametros.aMovimentos = aMovimentos;

	js_divCarregando(msgDiv,'msgBox');

	new Ajax.Request(sUrlRPC,
	                        {method: "post",
	                         parameters:'json='+Object.toJSON(oParametros),
	                         onComplete: js_retornoCancelarArquivo
	                        });
}
function js_retornoCancelarArquivo(oAjax){

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());

  if (oRetorno.iStatus == "1") {

    oGridMovimentos.clearAll(true);
    $("e87_codgera").value = "";
    $("e87_descgera").value = "";
  }
}

//====  função para retornar um array de movimentos selecionados para cancelamento
function js_getMovimentosSelecionados () {

	var aMovimentos   = oGridMovimentos.getSelection();
	var aSelecionados = new Array();

	aMovimentos.each(function(oItem, iIndice) {
    aSelecionados.push(oItem[1]);
	})
	return aSelecionados;
}


//======================= Funcao para retornar movimentos do arquivo selecionado
function getMovimentosVinculados(){

	oGridMovimentos.clearAll(true);

  var iArquivo           = $F("e87_codgera");
	var oParametros        = new Object();
	var msgDiv             = "Buscando movimentos.<br>Aguarde ...";

	oParametros.exec       = 'getMovimentosVinculados';
	oParametros.iArquivo   = iArquivo  ;
	js_divCarregando(msgDiv,'msgBox');

	new Ajax.Request(sUrlRPC,
	                        {method: "post",
	                         parameters:'json='+Object.toJSON(oParametros),
	                         onComplete: js_retornogetMovimentos
	                        });
}

function js_retornogetMovimentos(oAjax) {

	  js_removeObj('msgBox');
	  var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.aDados.length > 0) {

        oRetorno.aDados.each(function (oDado, iInd) {

          var aRow   = new Array();

          aRow[0] = oDado.iMovimento;
          aRow[1] = oDado.iEmpenho;
          aRow[2] = oDado.iSlip;
          aRow[3] = oDado.sNome.urlDecode();
          aRow[4] = oDado.dtEmissao;
          aRow[5] = js_formatar(oDado.nValor, "f");
          aRow[6] = oDado.sContaPagadora;
          oGridMovimentos.addRow(aRow);

        });
        oGridMovimentos.renderRows();
    } else {

      alert("O código informado não possui nenhum movimento à ser cancelado.");
      $('e87_codgera').value = '';
      $('e87_descgera').value = '';
    }
}


//===================== Grid que conterá os movimentos a serem cancelados ==========//

function js_criaGridMovimentos() {

  oGridMovimentos = new DBGrid('oGridMovimentos');
  oGridMovimentos.nameInstance = 'oGridMovimentos';
  oGridMovimentos.setCheckbox(0);
  oGridMovimentos.setCellWidth(new Array( '80px' ,
                                          '80px',
                                          '80px',
                                          '300px',
                                          '80px',
                                          '80px' ,
                                          '80px'
                                        ));
  oGridMovimentos.setCellAlign(new Array( 'left'  ,
                                          'left'  ,
                                          'left',
                                          'left',
                                          'left',
                                          'right',
                                          'center'
                                         ));
  oGridMovimentos.setHeader(new Array( 'Cód.Mov',
                                       'Empenho',
                                       'Slip / Ordem',
                                       'Nome',
                                       'Emissão',
                                       'Valor',
                                       'Cta Pagadora'
                                        ));
  oGridMovimentos.hasTotalizador = true;
  oGridMovimentos.setHeight(400);
  oGridMovimentos.show($('ctnGridMovimentos'));
  oGridMovimentos.clearAll(true);
}


js_criaGridMovimentos();





//======================================pesquisa arquivo
function js_pesquisa_gera(mostra){

	  if(mostra == true){
	    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=0&funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa de Arquivos Gerados',true);
	  } else {

	     if(document.form1.e87_codgera.value != ''){
	        js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=0&pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
	     }else{
	       document.form1.e87_descgera.value = '';
	     }
	  }
	}
	function js_mostragera(chave,erro){

	  document.form1.e87_descgera.value = chave;
	  if(erro == true){
	    document.form1.e87_codgera.focus();
	    document.form1.e87_codgera.value = '';
	    return false;
	  }
	  getMovimentosVinculados();
	}
	function js_mostragera1(chave1,chave2){
	  document.form1.e87_codgera.value = chave1;
	  document.form1.e87_descgera.value = chave2;
	  db_iframe_empagegera.hide();
	  getMovimentosVinculados();
	}
	//--------------------------------

</script>