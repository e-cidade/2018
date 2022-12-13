<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

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
                  datagrid/plugins/DBOrderRows.plugin.js,
                  datagrid/plugins/DBHint.plugin.js,
                  AjaxRequest.js");

    db_app::load("estilos.css,
                  grid.style.css"
                );
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmDiarioClasse' method="post">
      <center>
        <div style='display:table;'>
          <fieldset>
          <legend style="font-weight: bold">Diário de Classe - Lançamentos Diários </legend>
            <table border='0'>
              <tr>
                <td nowrap title="" >
                  <b>Regente : </b>
                </td>
                <td nowrap id="ctnTxtRegente">
                </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Disciplina : </b>
                </td>
                <td nowrap id="ctnCboDisciplina">
                </td>
              </tr>
              <tr>
                <td nowrap title="">
                  <b>Data : </b>
                </td>
                <td nowrap id="ctnCboData">
                </td>
              </tr>
              <tr>
                <td nowrap title="">
                  <b>Turma : </b>
                </td>
                <td nowrap id="ctnCboTurma">
                </td>
              </tr>
            </table>
          </fieldset>
        </div>
        <input name="btnPesquisar" id="btnPesquisar" type="button" value="Pesquisar">
      </center>
    </form>
  </body>
  <script>

  var sFonteMSG     = 'educacao.escola.edu4_lancdiario001.';
  var sUrlRpc       = "edu4_diarioclasse.RPC.php";
  var iRegente      = 0;
  var aPeriodosAula = new Array();
  init = function () {

    oTxtFieldRegente = new DBTextField("txtFieldRegente ", "oTxtFieldRegente",null, "54" );
    oTxtFieldRegente.setReadOnly(true);
    oTxtFieldRegente.show($('ctnTxtRegente'));

    oCboDisciplina   = new DBComboBox("cboDisciplina", "oCboDisciplina",null,"400px");
    oCboDisciplina.addItem("", "Selecione");
    oCboDisciplina.addEvent("onChange", "js_getDatasProfessor()");
    oCboDisciplina.show($('ctnCboDisciplina'));

    oCboData = new DBComboBox("cboData", "oCboData",null,"400px");
    oCboData.addItem("", "Selecione");
    oCboData.addEvent("onChange", "js_getTurmasNoDia()");
    oCboData.show($('ctnCboData'));

    oCboTurma = new DBComboBox("cboTurma", "oCboTurma",null,"400px");
    oCboTurma.addItem("", "Selecione");
    oCboTurma.show($('ctnCboTurma'));

    var oParametros      = new Object();
    oParametros.exec     = 'getDisciplinasRegenteEscola';
    oParametros.iRegente =  iRegente;

    js_divCarregando('Aguarde, pesquisando disciplinas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   {
                                     method:'post',
                                     parameters: 'json='+Object.toJSON(oParametros),
                                     onComplete: js_loadDados
                                   }
                                );
  };

  js_loadDados = function (oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    oCboDisciplina.clearItens();
    oCboDisciplina.addItem("", "Selecione");
    oRetorno.itens.each(function(oDisciplina, iSeq) {
       oCboDisciplina.addItem(oDisciplina.codigo_disciplina, oDisciplina.descricao_disciplina.urlDecode());
    });
    iRegente = oRetorno.codigo_regente;
    oTxtFieldRegente.setValue(oRetorno.nome_regente.urlDecode());
    if (oRetorno.itens.length == 1) {

      oCboDisciplina.setValue(oRetorno.itens[0].codigo_disciplina);
      js_getDatasProfessor();
    }
  };

  function js_atualizarDadosLeitura() {

    if ( empty( $('cboDisciplina' ).value ) ) {

      alert( 'Selecione uma disciplina.' );
      return false;
    }

    if ( empty( $('cboData' ).value ) ) {

      alert( 'Selecione uma data.' );
      return false;
    }

    if ( empty( $('cboTurma' ).value ) ) {

      alert( 'Selecione uma turma.' );
      return false;
    }

    var oParametros = new Object();
    oParametros.exec = 'atualizarDados';

    js_divCarregando('Aguarde, Atualizando leituras...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
    new Ajax.Request('edu03_consultaacessoalunos.RPC.php' ,
                   {
                     method:'post',
                     parameters: 'json='+Object.toJSON(oParametros),
                     onComplete: js_pesquisarAlunos
                   }
    );
  }

  js_pesquisarAlunos = function() {

    if ($('msgBox')) {
      js_removeObj('msgBox');
    }
    js_divCarregando('Aguarde, pesquisando alunos da turma', 'msgBox');
    var oParametros               = new Object();
    oParametros.exec              = "getAlunosTurma";
    oParametros.dtAula            = oCboData.getValue();
    oParametros.iCodigoDisciplina = oCboDisciplina.getValue();
    oParametros.iCodigoTurma      = oCboTurma.getValue();
    oParametros.iRegente          = iRegente;
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   {
                                     method:'post',
                                     parameters: 'json='+Object.toJSON(oParametros),
                                     onComplete: js_diarioClasse
                                   });
  };

  function js_diarioClasse(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if(oRetorno.status == 2) {

      alert(oRetorno.message.urlDecode());
      return false;
    }

    if (oRetorno.aPeriodosAulaDia.length == 0) {

      alert('Turma sem períodos de aula cadastrados.');
      return false;
    }

    /**
     *Variavel no escopo globar, Colocada para acessar os periodos de aula na função salvar
     */
    aPeriodosAula = oRetorno.aPeriodosAulaDia;
    $('frmDiarioClasse').disable();
    oWindowDiarioClasse = new windowAux('wndDiarioClasse',
                                        'Diario de Classe',
                                        document.body.getWidth() - 10
                                       );

    oWindowDiarioClasse.setShutDownFunction(function() {

       oWindowDiarioClasse.destroy();
       $('frmDiarioClasse').enable();
    });

    var sContentWindow  = "<div style='width: 99% !important;'>";
    sContentWindow     += "  <fieldset>";
    sContentWindow     += "    <legend><b>Frequência Diária</b></legend>";
    sContentWindow     += "     <div id='ctnDataGridDiarioClasse'></div>";
    sContentWindow     += "   </fieldset>";
    sContentWindow     += "  <fieldset>";
    sContentWindow     += "    <legend><b>Aula Desenvolvida</b></legend>";
    sContentWindow     += "     <div id='ctnAulaDesenvolvida'></div>";
    sContentWindow     += "   </fieldset>";
    sContentWindow     += "</div>";
    sContentWindow     += "<div style='text-align:center;padding-top:4px;padding-right:3px;'>";
    sContentWindow     += "  <div style='float:right;clear:both;font-weight:bold'>";
    sContentWindow     += "    <span style='background-color:#009600;padding:2px 8px 2px 8px;'>&nbsp;</span>&nbsp;Aluno na escola";
    sContentWindow     += "    <span style='background-color:#FF0000;padding:2px 8px 2px 8px;'>&nbsp;</span>&nbsp;Aluno ausente na escola";
    sContentWindow     += "  </div>";
    sContentWindow     += "  <input type='button' value='Salvar' id='btnSalvar' onclick ='js_salvarDiarioClasse()'>";
    sContentWindow     += "  <input type='button' value='Dia Anterior' id='btnDiaAnterior' onclick ='js_diaAnterior()'>";
    sContentWindow     += "  <input type='button' value='Próximo Dia'  id='btnProximoDia'onclick ='js_proximoDia()'>";
    sContentWindow     += "  <input type='button' value='Fechar'       id='btnFechar'>";
    sContentWindow     += '</div>';
    oWindowDiarioClasse.setContent(sContentWindow);

    var sTurma   = oCboTurma.getLabel();
    var sData    = oCboData.getLabel();
    var sRegente = oTxtFieldRegente.getValue();

    var sMensagem        = 'Frequência da Turma <b>'+sTurma+' dia '+sData+' </b>';
    var oMessageBoard    = new DBMessageBoard('msgBoardAcesso',
                                             'Lançamento do Diário de Classe - '+sRegente+' - '+ oCboDisciplina.getLabel(),
                                             sMensagem,
                                             oWindowDiarioClasse.getContentContainer());
    oMessageBoard.show();
    oWindowDiarioClasse.show();

    oTxtFieldAulaDesenvolvida = document.createElement( 'input' );
    oTxtFieldAulaDesenvolvida.setAttribute( 'id', 'oTxtFieldAulaDesenvolvida' );
    oTxtFieldAulaDesenvolvida.setAttribute( 'value', oRetorno.sAulaData.urlDecode() );
    oTxtFieldAulaDesenvolvida.style.width = '100%';
    oTxtFieldAulaDesenvolvida.setAttribute( 'maxLength', 200 );
    $('ctnAulaDesenvolvida').appendChild( oTxtFieldAulaDesenvolvida );


    $('btnFechar').observe('click', function() {

      oWindowDiarioClasse.destroy();
      $('frmDiarioClasse').enable();
    });

    setTimeout(function() {

      montaGridAlunos(oRetorno);
    }, 500);

  }

  function montaGridAlunos(oRetorno) {

    oDBGridDiarioClasse              = new DBGrid('idDBGridDiarioClasse');
    oDBGridDiarioClasse.nameInstance = 'oDBGridDiarioClasse';

    var iSomaDasColunas = 72;
    var iColunaNome     = 51;

    var aHeader = new Array('Nº', 'Aluno', 'Código', 'Situação');
    var aAligns = new Array('center', 'left', 'center', 'center');
    var aWidth  = new Array('2%', iColunaNome+'%', '5%','10%');


    /**
     *Criamos as colunas com os periodos Dinamicamente para os periodos de Aula.
     */
    var iTotalNumeroPeriodos = oRetorno.aPeriodosAulaDia.length;

    /**
     * Recalcula o tamanho das colunas para fechar 100% na grid
     */
    var iTamanhoPeriodo           = 7;
    var iTamanhoPercentualPeriodo = iTotalNumeroPeriodos * iTamanhoPeriodo;
    iColunaNome = iColunaNome + ((100 - iTamanhoPercentualPeriodo) - iSomaDasColunas);
    aWidth[1]   = iColunaNome + '%';

    oRetorno.aPeriodosAulaDia.each(function (oPeriodo, iPeriodo) {

       aHeader.push(oPeriodo.descricao_periodo.urlDecode());
       aWidth.push(iTamanhoPeriodo+'%');
       aAligns.push('center');
    });

    aHeader.push('Acesso');
    aWidth.push('4%');
    aAligns.push('center');

    oDBGridDiarioClasse.setCellWidth(aWidth);
    oDBGridDiarioClasse.setCellAlign(aAligns);
    oDBGridDiarioClasse.setHeader(aHeader);
    oDBGridDiarioClasse.setHeight((oWindowDiarioClasse.getHeight() / 1.8));
    oDBGridDiarioClasse.show($('ctnDataGridDiarioClasse'));
    oDBGridDiarioClasse.clearAll(true);

    /**
     * Armazena as informações de data de matrícula e data de saída de cada linha
     */
    var aDatasLinhas = new Array();

    /**
     * Preenchemos a grid com os dados do alunos
     */
     oRetorno.aAlunos.each(function (oAluno, iAluno) {

       var aLinha  = new Array();
       aLinha[0]   = oAluno.ordem_turma;
       var sCor    = "#FF0000";
       if (oAluno.acessoescola) {
         sCor = "#009600";
       }

       var sNecessidade = "";
       if ( oAluno.lTemNecessidade ) {
         sNecessidade = "<b> - NEE</b>";
       }

       aLinha[1]   = "<span>" + oAluno.nome.urlDecode() + sNecessidade + "</span>";
       aLinha[2]   = oAluno.codigo;
       aLinha[3]   = oAluno.situacao.urlDecode();

       /**
        *Criamos os Checkboxes dos Periodos que
        */
       oRetorno.aPeriodosAulaDia.each(function (oPeriodo, iPeriodo) {

         var sCheckboxMarcado = "checked";
         if (js_search_in_array(oAluno.faltas, oPeriodo.codigo_regencia_periodo)) {
           sCheckboxMarcado = " ";
         }
         var sCheckbox  = "<input type='checkbox' value='"+oPeriodo.codigo_regencia_periodo+"'";
         sCheckbox     += " onfocus='js_sinalizarLinhaGrid(this, true)'";
         sCheckbox     += " onblur='js_sinalizarLinhaGrid(this, false)'";
         if (oAluno.lBloqueioFalta) {
           sCheckbox  += " disabled ";
         }

         oPeriodo.aTurnosReferentes.each(function( iTurnoReferente ){
            if (oAluno.aTurnosReferentes.indexOf(iTurnoReferente) == -1 ){
              sCheckbox  += " disabled ";
            }
         });

         sCheckbox     += sCheckboxMarcado+" >";
         aLinha.push(sCheckbox);
        });
        aLinha.push("<div style='margin:auto;text-align:center;background-color:"+sCor+";width:15px;height:80%'>&nbsp;&nbsp;</div>");
        oDBGridDiarioClasse.addRow(aLinha);
        oDBGridDiarioClasse.aRows[iAluno].sEvents += "onmouseover='js_sinalizarLinhaGrid(this, true);'";
        oDBGridDiarioClasse.aRows[iAluno].sEvents += "onmouseout='js_sinalizarLinhaGrid(this, false);'";

       /**
        * Dados da linha para adicionar ao hint
        */
       var oDadosAluno                = new Object();
           oDadosAluno.iLinha         = iAluno;
           oDadosAluno.data_matricula = oAluno.data_matricula;
           oDadosAluno.data_saida     = oAluno.data_saida;

       aDatasLinhas.push( oDadosAluno );
    });

    oDBGridDiarioClasse.renderRows();

    aDatasLinhas.each(function( oDadosData, iLinha ) {

      var sHint       = "Data de Matrícula: " + js_formatar( oDadosData.data_matricula, 'd' );
          sHint      += "<br>Data de Saída: " + js_formatar( oDadosData.data_saida, 'd' );
      var oParametros = {iWidth:'500', oPosition : {sVertical : 'T', sHorizontal : 'L'}};
      oDBGridDiarioClasse.setHint(iLinha, 1, sHint);
    });
  }

  function js_getDatasProfessor() {

    if ( oCboDisciplina.getValue() == '') {

      oCboData.clearItens()
      return;
    }

    oCboData.clearItens();
    oCboData.addItem("", "Selecione");
    oCboTurma.clearItens();
    oCboTurma.addItem("", "Selecione");

    var oParametros               = new Object();
    oParametros.exec              = "getDatasProfessorDisciplina";
    oParametros.iRegente          = iRegente;
    oParametros.iCodigoDisciplina = oCboDisciplina.getValue();

    new AjaxRequest(sUrlRpc, oParametros, function(oRetorno, lErro) {

      if ( lErro ) {
        alert(oRetorno.message.urlDecode());
        return;
      }

      for (var oData of oRetorno.aDiasLetivos) {

        var aTurmaNoDia    = [];
        var oAtributoTurma = {nome : 'turmas', valor : []};

        for ( var oTurma of oData.turmas) {
          oAtributoTurma.valor.push(oTurma.codigo_turma);
        }
        aTurmaNoDia.push(oAtributoTurma);

        var sData         = js_formatar(oData.data, 'd');
        var sDescricaoDia = sData +" - "+oData.diasemana.urlDecode();
        oCboData.addItem(oData.data, sDescricaoDia, null, aTurmaNoDia);
        if (oRetorno.dataatual == oData.data) {

          oCboData.setValue(oData.data);
          js_getTurmasNoDia();
        }
      }
    }).setMessage( _M(sFonteMSG + "buscando_dias") ).execute();
  }


  function js_getTurmasNoDia() {

    var oParametros      = new Object();
    oParametros.exec     = "getTurmasNoDia";
    oParametros.iRegente = iRegente;
    oParametros.dtAula   = oCboData.getValue();
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   {
                                      method:'post',
                                      parameters: 'json='+Object.toJSON(oParametros),
                                      onComplete: function (oResponse) {

                                        var oRetorno = eval("("+oResponse.responseText+")");
                                        oCboTurma.clearItens();
                                        oCboTurma.addItem("", "Selecione");

                                        for (var turma in oRetorno.aTurmas){
                                          oCboTurma.addItem(oRetorno.aTurmas[turma].codigo_turma, oRetorno.aTurmas[turma].descricao_turma.urlDecode());
                                        }
                                      }
                                   });
  }

  function js_sinalizarLinhaGrid(oObjeto, lPintar) {

    if (oObjeto.nodeName == 'TR') {
      oLinha = oObjeto;
    }
    if (oObjeto.nodeName == 'INPUT') {
      oLinha = oObjeto.parentNode.parentNode;
    }
    var sCor      = 'white';
    var sCorFonte = 'black';
    if (lPintar) {

       sCor      = '#2C7AFE';
       sCorFonte = 'white';
    }
    oLinha.style.backgroundColor = sCor;
    oLinha.style.color           = sCorFonte;
  }

  function js_salvarDiarioClasse (lConfirmar) {

    if (lConfirmar == null) {
      lConfirmar = true;
    }

    if (lConfirmar) {
      if (!confirm('Todas as Presenças/Faltas foram informadas?')) {
        return false;
      }
    }

    /**
     * Coletamos os codigos das regencias
     */
    var aRegencias = new Array();
    aPeriodosAula.each(function (oPeriodo, iPeriodo) {
      aRegencias.push(oPeriodo.codigo_regencia_periodo);
    });

    var aAlunos = new Array();
    oDBGridDiarioClasse.aRows.each(function(aLinha, iSeq) {

       var aFaltas         = new Array();
       var iPrimeiraColuna = 4;
       aPeriodosAula.each(function (oPeriodo, iPeriodo) {

         var iIdColunaPeriodo  = aLinha.aCells[iPrimeiraColuna +iPeriodo].sId;
         var oCheckboxPresenca = $(iIdColunaPeriodo).childNodes[0];
         if (!oCheckboxPresenca.checked) {
           aFaltas.push(oCheckboxPresenca.value);
         }
       });
       oAluno         = new Object();
       oAluno.iCodigo = aLinha.aCells[2].getValue();
       oAluno.faltas  = aFaltas;
       aAlunos.push(oAluno);
    });

    js_divCarregando('Aguarde, salvando os dados do Diário de Classe.', 'msgBox');
    var oParametros               = new Object();
    oParametros.exec              = "salvarDiarioClasse";
    oParametros.iRegente          = iRegente;
    oParametros.dtAula            = oCboData.getValue();
    oParametros.iDisciplina       = oCboDisciplina.getValue();
    oParametros.iTurma            = oCboTurma.getValue();
    oParametros.aAlunos           = aAlunos;
    oParametros.aRegencias        = aRegencias;
    oParametros.sAulaDesenvolvida = encodeURIComponent(tagString($F('oTxtFieldAulaDesenvolvida')));
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   {
                                     method:'post',
                                     parameters: 'json='+Object.toJSON(oParametros),
                                     onComplete: function (oResponse) {

                                       js_removeObj('msgBox');
                                       var oRetorno = eval("("+oResponse.responseText+")");
                                       if (oRetorno.status == 1) {

                                         alert('Diário de Classe Salvo com sucesso.');
                                       } else {
                                         alert(oRetorno.message.urlDecode());
                                       }
                                     }
                                   });
  }

  function js_proximoDia() {

     var aDatasComATurma   = new Array();
     var oCombo            = $('cboData');
     var iTurmaSelecionada = oCboTurma.getValue();
     var iItemSelecionado  = $('cboData').selectedIndex;
     for (var i = 1; i < oCombo.options.length; i++) {

      console.log(oCombo.options);

        var sTurmas      = oCombo.options[i].getAttribute("turmas");
        console.log(sTurmas);
        var aTurmasNoDia = sTurmas.split(",");
        if (js_search_in_array(aTurmasNoDia, iTurmaSelecionada) && i > iItemSelecionado) {
         aDatasComATurma.push(oCombo.options[i].value);
        }
     }
     if (aDatasComATurma.length > 0) {

       oCboData.setValue(aDatasComATurma[0]);
       oWindowDiarioClasse.destroy();
       js_pesquisarAlunos();

     }

  }
  function js_diaAnterior() {

     var aDatasComATurma    = new Array();
     var oCombo             = $('cboData');
     var iTurmaSelecionada  = oCboTurma.getValue();
     var iItemSelecionado   = $('cboData').selectedIndex;
     for (var i = 1; i < oCombo.options.length; i++) {

        var sTurmas      = oCombo.options[i].getAttribute("turmas");
        var aTurmasNoDia = sTurmas.split(",");
        if (js_search_in_array(aTurmasNoDia, iTurmaSelecionada) && i < iItemSelecionado) {
         aDatasComATurma.push(oCombo.options[i].value);
        }
     }
     if (aDatasComATurma.length > 0) {

       oCboData.setValue(aDatasComATurma.last());
       oWindowDiarioClasse.destroy();
       js_pesquisarAlunos();

     }

  }
  $('btnPesquisar').observe('click', js_atualizarDadosLeitura);
  init();

  </script>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
