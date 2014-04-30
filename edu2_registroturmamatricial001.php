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
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_aluno");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  webseller.js,
                  DBVisualizadorImpressaoTexto.js");

    db_app::load("estilos.css,
                  grid.style.css,
                  dbVisualizadorImpressaoTexto.style.css"
                );
    ?>
    <style type="text/css">
      div#ctnForm table tr td:FIRST-CHILD{width: 150px;}

     .prePagina {font-family: monospace;}
    </style>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmDiarioClasse' method="post">
      <center>
        <div style='display:table;' id='ctnForm'>
          <fieldset>
          <legend style="font-weight: bold">Registro de Turma</legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr>
                <td nowrap title="" >
                  <b>Escola: </b>
                </td>
                <td nowrap id="ctnCboEscola">
                </td>
              </tr>
              <tr>
               <td nowrap title="" >
                  <b>Calendário: </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Curso: </b>
                </td>
                <td nowrap id="ctnCboCurso">
                </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Formato do Arquivo: </b>
                </td>
                <td nowrap id="ctnCboFormatoArquivo">
                </td>
              </tr>
            </table>
            </fieldset>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Turmas</legend>
              <table>
                <tr>
                  <td><b>Turmas para Impressão:</b></td>
                  <td id='ctnTurmas'>

                  </td>
                  <td>
                   <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                   <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
                   <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                   <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                  </td>
                  <td id='ctnTurmasSelecionadas'>
                  </td>
                </tr>
              </table>
           </fieldset>

          </fieldset>
        </div>
        <input name="btnPesquisar" id="btnPesquisar" type="button" value="Imprimir">
        <input type="hidden" id='sSessionNome'>
      </center>
    </form>
  </body>
  <script>

  sUrlRpc           = "edu4_escola.RPC.php";
  init = function () {

    oCboEscola   = new DBComboBox("cboEscola", "oCboEscola",null, "100%");
    oCboEscola.addItem("", "Selecione");
    oCboEscola.addEvent("onChange", "js_pesquisarCalendario()");
    oCboEscola.show($('ctnCboEscola'));

    oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
    oCboCalendario.addItem("", "Selecione");
    oCboCalendario.addEvent("onChange", "js_pesquisarCurso()");
    oCboCalendario.show($('ctnCboCalendario'));

    oCboCurso = new DBComboBox("cboCurso", "oCboCurso", null, "100%");
    oCboCurso.addEvent("onChange", "js_pesquisarTurmas()");
    oCboCurso.addItem("", "Selecione");
    oCboCurso.show($('ctnCboCurso'));

    oCboFormatoArquivo = new DBComboBox("cboFormatoArquivo", "oCboFormatoArquivo", null, "100%");
    oCboFormatoArquivo.addItem("1", "Matricial");
    oCboFormatoArquivo.addItem("2", "A4 (PDF)");
    oCboFormatoArquivo.show($('ctnCboFormatoArquivo'));

    var oParametros          = new Object();
    oParametros.exec         = 'getEscola';
    oParametros.filtraModulo = true;

    oCboTurma  = new DBComboBox("cboTurma", "oCboTurma", null,"150px", 10);
    oCboTurma.setMultiple(true);
    oCboTurma.addEvent("onDblClick", "moveSelected(oCboTurma, oCboTurmaSelecionada)");
    oCboTurma.show($('ctnTurmas'));

    oCboTurmaSelecionada  = new DBComboBox("cboTurmaSelecionada", "oCboTurmaSelecionada",null,"150px", 10);
    oCboTurmaSelecionada.setMultiple(true);
    oCboTurmaSelecionada.addEvent("onDblClick", "moveSelected(oCboTurmaSelecionada,oCboTurma)");
    oCboTurmaSelecionada.show($('ctnTurmasSelecionadas'));

    js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   {
                                     method:'post',
                                     parameters: 'json='+Object.toJSON(oParametros),
                                     onComplete: js_retornoPreencheEscolas
                                   }
                                );



  }

  js_retornoPreencheEscolas = function (oAjax) {

	 js_removeObj('msgBox');
	 var oRetorno = eval("("+oAjax.responseText+")");

	   oCboEscola.clearItens();
	   oCboEscola.addItem("", "Selecione");
	   oRetorno.itens.each(function(oEscola, iSeq) {
	      oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
	   });

	   if (oRetorno.itens.length == 1) {

	     oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
	     js_pesquisarCalendario();
	   }
  }

  js_pesquisarCalendario = function() {

	  oCboTurma.clearItens();
	  oCboTurmaSelecionada.clearItens();
    oCboCalendario.clearItens();
    oCboCalendario.addItem("", "Selecione");
    oCboCurso.clearItens();
    oCboCurso.addItem("", "Selecione");
	  if (oCboEscola.getValue() == "") {
	    return false;
	  }
	  js_divCarregando('Aguarde, pesquisando calendarios', 'msgBox');

	  var oParametros               = new Object();
	      oParametros.exec          = "PesquisaCalendario";
	      oParametros.escola        = oCboEscola.getValue();
	  var oAjax = new Ajax.Request(sUrlRpc ,
	                               {
	                                 method:'post',
	                                 parameters: 'json='+Object.toJSON(oParametros),
	                                 onComplete: js_retornoPesquisarCalendario
	                               });
  };
  function js_retornoPesquisarCalendario(oResponse) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oResponse.responseText+")");
		oRetorno.aResult.each(function(oCalendario, iSeq) {
		  oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
        });

		if (oRetorno.aResult.length == 1) {
		  oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
		  js_pesquisarCurso();
		}


  }


  js_pesquisarCurso = function() {

		oCboTurma.clearItens();
		oCboTurmaSelecionada.clearItens();
		oCboCurso.clearItens();
    oCboCurso.addItem("", "Selecione");
    if (oCboCalendario.getValue() == "") {
      return false;
    }
  	js_divCarregando('Aguarde, pesquisando cursos', 'msgBox');
  	var oParametros               = new Object();
  	    oParametros.exec          = "PesquisaCurso";
  	    oParametros.iCodigoEscola = oCboEscola.getValue();
  	var oAjax = new Ajax.Request(sUrlRpc ,
  		                         {
  		                           method:'post',
  		                           parameters: 'json='+Object.toJSON(oParametros),
  		                           onComplete: js_retornoPesquisarCurso
  		                         });



  };

  function js_retornoPesquisarCurso(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
  	  oRetorno.aResultCursoEscola.each(function(oCurso, iSeq) {
  	    oCboCurso.addItem(oCurso.codigo_curso, oCurso.nome_curso.urlDecode());
  	  });

  	  if (oRetorno.aResultCursoEscola.length == 1) {
  	    oCboCurso.setValue(oRetorno.aResultCursoEscola[0].codigo_curso);
  	    js_pesquisarTurmas ();
  	  }

  }

  function js_pesquisarTurmas () {

    oCboTurmaSelecionada.clearItens();
    oCboTurma.clearItens();
    if (oCboCurso.getValue() == "") {
      return false;
    }
    js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');
    var oParametros                    = new Object();
        oParametros.exec               = "getTurmas";
        oParametros.iEscola      = oCboEscola.getValue();
        oParametros.iCurso       = oCboCurso.getValue();
        oParametros.iCalendario  = oCboCalendario.getValue();
    var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarTurma
                               });



  };

  function js_retornoPesquisarTurma(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    oRetorno.aTurmas.each(function(aTurma, iSeq) {
      oCboTurma.addItem(aTurma.codigo_turma, aTurma.nome_turma.urlDecode());
    });

  }

  $('btnMoveOneRightToLeft').observe("click", function() {
    moveSelected(oCboTurma, oCboTurmaSelecionada);
  });

  $('btnMoveAllRightToLeft').observe("click", function() {
    moveAll(oCboTurma, oCboTurmaSelecionada);
  });

  $('btnMoveOneLeftToRight').observe("click", function() {
    moveSelected(oCboTurmaSelecionada, oCboTurma);
  });

  $('btnMoveAllLeftToRight').observe("click", function() {
    moveAll(oCboTurmaSelecionada, oCboTurma);
  });

  function moveSelected(oComboOrigin, oComboDestiny) {

    if (oComboOrigin.getValue() != null) {

      var aItens = oComboOrigin.getValue();
      aItens.each(function(oItem, iSeq) {

        oItem = oComboOrigin.aItens[oItem];
        oComboDestiny.addItem(oItem.id, oItem.descricao);
        oComboOrigin.removeItem(oItem.id);
      });
    }
  }

  function moveAll(oComboOrigin, oComboDestiny) {

     var aItens       =  $(oComboOrigin.sName).options;
     var iTotalItens  = aItens.length;
     for (var iOption = 0; iOption < iTotalItens; iOption++) {

       if (oComboOrigin.aItens[aItens[iOption].value]) {

          var oItem =  oComboOrigin.aItens[aItens[iOption].value];
          oComboDestiny.addItem(oItem.id, oItem.descricao);

       }
     }
     oComboOrigin.clearItens();

  }

  $('btnPesquisar').observe('click', function() {

    var aTurmasSelecionadas = oCboTurmaSelecionada.aItens;
    if (aTurmasSelecionadas == null || aTurmasSelecionadas.length == 0) {

      alert('Nenhuma turma selecionada');
      return false;
    }
    var aTurmas                 = new Array();
    aTurmasSelecionadas.each(function(oTurma, id) {
      aTurmas.push(oTurma.id);
    });
    if (oCboFormatoArquivo.getValue() == 1) {

       js_divCarregando('Aguarde, arquivo sendo processado', 'msgBox');
      var oParametros     = new Object();
      oParametros.exec    = "processarImpressaoRegistroClasse";
      oParametros.aTurmas = aTurmas;

      var oAjax = new Ajax.Request('edu04_diarioclassematricial.RPC.php',
                                 {
                                   method:'post',
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_openVisualizadorImpressao
                                 });

    } else {
      js_visualizariarRelatorioPDF(aTurmas);
    }
  });
  function js_openVisualizadorImpressao(oAjax) {

    js_removeObj('msgBox');
    var oRetorno            = eval("("+oAjax.responseText+")");
    $('sSessionNome').value = oRetorno.sSessionNome;
    var iWidth  = document.body.getWidth() - 10;
    oWindow = new windowAux('oWindow', 'Visualizar Impressão', iWidth);
    oWindow.setShutDownFunction(function() {
      oWindow.destroy();
    });
    oWindow.setContent("<div style='width:100%; height:100%' id='visualizador'></div>");
    oVisualizador = new DBVisualizadorImpressaoTexto('visualizador', '90%', '100%');
    oVisualizador.setImpressoras(oRetorno.aImpressoraId, oRetorno.aImpressoraDescr);
    oVisualizador.iIpPadrao = oRetorno.iIpPadrao;
    oVisualizador.setDimensoes(66, 350);
    oVisualizador.gerarVisualizador();
    var iTam = oRetorno.aArquivo.length;
    for (i=0; i < iTam; i++) {
      oVisualizador.addArquivo(oRetorno.aArquivo[i]);
    }

    lResultado = oVisualizador.renderizarArquivos();
    $('fechar').stopObserving('click');
    $('fechar').observe('click', function () {
      oWindow.destroy();
    });
    oWindow.show();
  }
  init();

  function js_visualizariarRelatorioPDF(aTurmas) {

    var sTurmas = aTurmas.implode(",");
    jan = window.open('edu2_registroclasse002.php?turmas='+sTurmas+'&iEscola='+oCboEscola.getValue(),
                      '', 'width='+(screen.availWidth-5)+',height='+
                     (screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    return false;
  }
 </script>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>