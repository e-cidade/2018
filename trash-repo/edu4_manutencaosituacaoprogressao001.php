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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
$oRotulo = new rotulo('aluno');
$oRotulo->label();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, arrays.js');
  db_app::load('dbmessageBoard.widget.js, dbcomboBox.widget.js, dbtextField.widget.js');
  db_app::load('estilos.css, grid.style.css');
  ?>
</head>
<body style="margin-top:25px">
  <center>
     <div style="display: table;width: 75%">
       <fieldset>
         <legend>Ativar / Desativar Progressões</legend>
         <fieldset style="border:0px; border-top: 2px groove #ffffff">
           <Legend>Aluno</Legend>
           <table>
             <Tr>
               <td>
                 <?php
                   db_ancora("<b>Aluno</b>","js_pesquisaAlunos();", 1);
                 ?>
               <td>
                 <?php
                 db_input('ed47_i_codigo',10,$Ied47_i_codigo,true,'text',3,"");
                 db_input('ed47_v_nome',60,$Ied47_v_nome,true,'text',3,"");
                 ?>
               </td>
             </Tr>
           </table>
         </fieldset>
         <fieldset style="border:0px; border-top: 2px groove #ffffff;">

           <Legend>Etapas Com Progressão do Aluno</Legend>
           <div id="ctnDataGridProgessoes" style="width: 100%">

           </div>
         </fieldset>
       </fieldset>
     </div>
       <input type="button" id="btnProcessarSituacaoProgressao" value="Processar">
  </center>
</body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

  const CAMINHO_MENSAGEM    = 'educacao.escola.edu4_manutencaosituacaoprogressao001.';
  var sUrlRpc               = 'edu4_progressaoparcial.RPC.php';
  var aProgressoesAgrupadas = new Array();

  var oGridProgressoesAluno          = new DBGrid('gridProgressoes');
  oGridProgressoesAluno.nameInstance = 'oGridProgressoesAluno';
  oGridProgressoesAluno.setCheckbox(0);
  oGridProgressoesAluno.setHeader(['Etapa', 'Disciplinas', 'Escola', 'Ano', 'Situação', 'codigos']);
  oGridProgressoesAluno.setCellWidth(['15%', '43%', '30%', '5%', '7%', '5%']);
  oGridProgressoesAluno.aHeaders[6].lDisplayed = false;
  oGridProgressoesAluno.show($('ctnDataGridProgessoes'));



  function js_pesquisaAlunos() {

    var sFiltroProgressao  = '&listar_apenas_progressao_parcial=1&listar_situacao_progressao_parcial=1,2';
        sFiltroProgressao += '&lMatriculaEscola';
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_aluno',
                        'func_aluno.php?funcao_js=parent.js_preencheAluno|ed47_i_codigo|ed47_v_nome'+sFiltroProgressao,
                        'Pesquisar Alunos com Progressão Parcial',
                         true
                       );
  }

  function js_preencheAluno(iCodigo, sNome) {

    $('ed47_i_codigo').value = iCodigo;
    $('ed47_v_nome').value   = sNome;
    db_iframe_aluno.hide();
    buscaProgressoesDoAluno();
  }


  function buscaProgressoesDoAluno () {

    var oParametros           = new Object();
        oParametros.exec      = 'buscaDadosProgressaoAluno';
        oParametros.iAluno    = $F('ed47_i_codigo');
        oParametros.lInativos = true;

    js_divCarregando(_M(CAMINHO_MENSAGEM+"aguarde_pesquisando"), 'msgBox');
    new Ajax.Request(sUrlRpc,
                     {method:'post',
                      parameters:'json='+Object.toJSON(oParametros),
                      onComplete: function (oResponse) {

                        js_removeObj('msgBox');
                        var oRetorno = eval("("+oResponse.responseText+")");
                        agruparProgressoesPorEtapa(oRetorno.aProgressoes);
                        preencheDataGrid();
                     }

    });

  }


  function agruparProgressoesPorEtapa(aProgressoes) {

    aProgressoesAgrupadas = new Array();
    aProgressoes.each(function(oProgressao, iSeq) {

      oProgressaoEtapa = procurarProgressaoDaEtapa(oProgressao.iEtapa);
      if (empty(oProgressaoEtapa)) {

        oProgressaoEtapa              = new Object();
        oProgressaoEtapa.sNomeEtapa   = oProgressao.sEtapa.urlDecode();
        oProgressaoEtapa.iEtapa       = oProgressao.iEtapa;
        oProgressaoEtapa.aDisciplinas = [];
        oProgressaoEtapa.sNomeEscola  = oProgressao.sEscola.urlDecode();
        oProgressaoEtapa.iEscola      = oProgressao.iEscola;
        oProgressaoEtapa.iAno         = oProgressao.iAno;
        oProgressaoEtapa.lAtiva       = oProgressao.lAtiva;
        oProgressaoEtapa.aProgressoes = [];
        aProgressoesAgrupadas.push(oProgressaoEtapa);
      }
      oProgressaoEtapa.aDisciplinas.push(oProgressao.sDisciplina.urlDecode());
      oProgressaoEtapa.aProgressoes.push(oProgressao.iCodigo);
    });
  }

  /**
   *
   * @param iCodigoEtapa
   * @returns {*}
   */
  function procurarProgressaoDaEtapa(iCodigoEtapa) {

    var oProgressaoDaEtapa = '';
    aProgressoesAgrupadas.each(function(oProgressao) {
      if (oProgressao.iEtapa == iCodigoEtapa) {
        oProgressaoDaEtapa = oProgressao;
      }
    });
    return oProgressaoDaEtapa;
  }

  function preencheDataGrid() {

    oGridProgressoesAluno.clearAll(true);
    aProgressoesAgrupadas.each(function(oProgressao) {

      var aLinha = [];
      aLinha[0]  = oProgressao.sNomeEtapa;
      aLinha[1]  = oProgressao.aDisciplinas.implode(",");
      aLinha[2]  = oProgressao.iEscola + " - " + oProgressao.sNomeEscola;
      aLinha[3]  = oProgressao.iAno;
      aLinha[4]  = montarComboBoxSituacao(oProgressao.aProgressoes.implode(''), oProgressao.lAtiva);
      aLinha[5]  = oProgressao.aProgressoes.implode(",");
      oGridProgressoesAluno.addRow(aLinha);
    });
    oGridProgressoesAluno.renderRows();
  }

  function montarComboBoxSituacao(sProgressao, lSituacao) {

    var sComboBox = '<select id="cboAtivoInativo'+sProgressao+'" style="width:100%">';
    sComboBox    += "<option value=1"+(lSituacao == true ?' selected ':'')+">Ativa</option>";
    sComboBox    += "<option value=2"+(lSituacao == false ?' selected ':'')+">Inativa</option>";
    sComboBox    += "/select>";
    return sComboBox;
  }

  $('btnProcessarSituacaoProgressao').observe('click', function() {

    var aProgressoesSelecionadas = oGridProgressoesAluno.getSelection('object');
    if (aProgressoesSelecionadas.length  == 0) {

      alert(_M(CAMINHO_MENSAGEM+"sem_progressoes_selecionadas"));
      return false;
    }

    if (!confirm(_M(CAMINHO_MENSAGEM+"confirma_processamento"))) {
      return;
    }

    var aProgressaosProcessarSituacao = [];
        aProgressoesSelecionadas.each(function(oProgressao) {

          var oProgressaoProcessar              = {};
              oProgressaoProcessar.situacao     = oProgressao.aCells[5].getValue() == 1;
              oProgressaoProcessar.aProgressoes = oProgressao.aCells[6].getValue().split(",");

          aProgressaosProcessarSituacao.push(oProgressaoProcessar);
        });

    var oParametro          = {};
    oParametro.exec         = 'atualizarSituacaoProgressao';
    oParametro.aProgressoes = aProgressaosProcessarSituacao;


    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = retornoAtualizarSituacaoProgressao;

    js_divCarregando(_M(CAMINHO_MENSAGEM+"aguarde_processamento"), 'msgBox');
    new Ajax.Request( sUrlRpc, oDadosRequisicao);
  });

  function retornoAtualizarSituacaoProgressao( oResposta ) {

    js_removeObj("msgBox");
    var oRetorno = eval( '('+oResposta.responseText+')' );

    alert( oRetorno.message.urlDecode() );

    if ( oRetorno.status == 1 ) {
      limpaCampos();
    }
  };

  function limpaCampos() {

    $('ed47_i_codigo').value = '';
    $('ed47_v_nome').value   = '';
    oGridProgressoesAluno.clearAll( true );
  }
</script>