<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <?php
        db_app::load(array(
            "scripts.js",
            "strings.js",
            "prototype.js",
            "AjaxRequest.js",
            "datagrid.widget.js",
            "DBAbas.widget.js",
            "DBInputHora.widget.js",
            "estilos.css"
        ));
        $aTipos = array(
            'f'=>'Não',
            't'=>'Sim'
        );
        ?>
    </head>
    <body>
        <div style="margin-top: 20px;" id='ctnAbas'></div>

        <div id="ctnJornada" class="container">

            <fieldset>
                <legend>Escala de Trabalho</legend>

                <table align="center">
                    <tr>
                        <td>
                            <strong>Descrição:</strong>
                        </td>
                        <td>
                            <input type="text" autocomplete="off" maxlength="10" size="10" id="gradehorario_sequencial" name="gradehorario_sequencial" title="Código Sequencial: gradehorario_sequencial" readonly="readonly" style="background-color:#DEB887"/>
                            <input type="text" autocomplete="off" onkeydown="return js_controla_tecla_enter(this,event);" oninput="js_ValidaCampos(this,0,'Descrição','f','t',event);"      onblur="js_ValidaMaiusculo(this,'t',event);" style="text-transform:uppercase;" maxlength="40" size="40" value="" id="gradehorario_descricao" name="gradehorario_descricao" title="Descrição da Jornada Campo:gradehorario_descricao" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Data Base</strong>
                        </td>
                        <td>
                            <?php db_inputdata('gradehorario_database', null, null, null, true, 'text', 1); ?>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <strong>Revezamento</strong>
                        </td>
                        <td>
                            <?php db_select('gradehorario_revezamento', $aTipos, '', 1) ?><br/>
                        </td>
                    </tr>
                </table>
                <div name='lancadorJornadas' id='lancadorJornadas'></div>
                <fieldset>
                    <?php db_ancora('Jornada', 'js_pesquisaJornada()', 1); ?>
                    <input type="text" maxlength="10" size="10" id="jornada_sequencial" name="jornada_sequencial" title="Código Sequencial: jornada_sequencial" readonly="readonly" style="background-color:#DEB887"/>
                    <input type="text"   autocomplete="off" onkeydown="return js_controla_tecla_enter(this,event);" oninput="js_ValidaCampos(this,0,'Descrição','f','t',event);"      onblur="js_ValidaMaiusculo(this,'t',event);" style="text-transform:uppercase;" maxlength="40" size="40" value="" id="jornada_descricao" name="jornada_descricao" title="Descrição da Jornada Campo:jornada_descricao" >
                    <input type="button" name="lancar" id="lancar" value="Lançar" onclick="js_lancar()"/>

                    <div id="grid_registros" style="margin-top: 10px; width:600px"></div>
                </fieldset>
            </fieldset>
            <input type="button" name="salvar"    id="salvar"    value="Salvar"     onclick="js_salvar()"/>
            <input type="button" name="pesquisar" id="pesquisar" value="Pesquisar"  onclick="js_pesquisarGrade()"/>
            <input type="button" name="novo"      id="novo"      value="Nova Grade" onclick="js_novo()"/>

            <input type="button" name="excluirGradesHorarios" id="excluirGradesHorarios" value="Excluir" onclick="js_excluirGradesHorarios()"/>
        </div>
        <?php db_menu();?>
        <script>
            sUrl              = 'rec4_cadastrogradeshorarios.RPC.php';
            aJornadasHorarios = new Array();
            js_montaGrid();

            function js_pesquisarGrade() {
                js_OpenJanelaIframe(
                    'top.corpo',
                    'db_iframe_gradeshorarios',
                    'func_gradeshorarios.php?funcao_js=parent.js_retornoPesquisaGrade|rh190_sequencial|rh190_descricao|rh190_database|rh190_revezamento',
                    'Pesquisa Grades de Horários',
                    true
                );
            }

            function js_retornoPesquisaGrade(iCodigoGrade, sDescricao, dDataBase, fRevezamento) {

                $('gradehorario_sequencial').value  = iCodigoGrade;
                $('gradehorario_descricao').value   = sDescricao;
                $('gradehorario_database').value    = js_formatar(dDataBase, 'd');
                $('gradehorario_revezamento').value = fRevezamento;

                db_iframe_gradeshorarios.hide();

                oParametros               = new Object();
                oParametros.exec          = 'getInformacoesGrade';
                oParametros.iCodigoGrade  = iCodigoGrade;

                aJornadasHorarios          = new Array();

                var oAjaxRequest = new AjaxRequest(sUrl, oParametros, function (oAjax, lErro) {

                    oAjax.aJornadaHorarios.each( function(oLinha, iIndice) {
                        aJornadasHorarios.push(oLinha);
                    });
                    js_registrosGrid();
                });

                oAjaxRequest.setMessage("Salvando");
                oAjaxRequest.execute();
            }

            function js_novo() {
                if (!confirm('Deseja iniciar nova grade de escala?')) {
                    return false;
                }
                window.location = 'rec4_cadastrogradeshorarios001.php';
            }

            function js_salvar() {
                iCodigoGrade    = $F('gradehorario_sequencial');
                sDescricaoGrade = $F('gradehorario_descricao');
                dDataBase       = $F('gradehorario_database');
                fRevezamento    = $F('gradehorario_revezamento');
                aJornadas       = new Array();

                oGridJornadas.getRows().each( function (oLinha, iIndice) {
                    oDados                = new Object();
                    oDados.iOrdem         = oLinha.aCells[0].content;
                    oDados.iCodigoJornada = oLinha.aCells[1].content; //oculta

                    aJornadas[iIndice]    = oDados;
                });

                oParametros                 = new Object();
                oParametros.exec            = 'salvar';
                oParametros.iCodigoGrade    = $F('gradehorario_sequencial');
                oParametros.sDescricaoGrade = encodeURIComponent( tagString( $F('gradehorario_descricao') ) );
                oParametros.dDataBase       = $F('gradehorario_database');
                oParametros.aJornadas       = aJornadas;
                oParametros.fRevezamento    = $F('gradehorario_revezamento');

                var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
                    function (oAjax, lErro) {
                        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));
                        if (lErro == false) {
                            if (oAjax.erro == false) {
                                $('gradehorario_sequencial').value = oAjax.iCodigoGradeHorarios;
                            }
                        }
                        window.location = 'rec4_cadastrogradeshorarios001.php';
                    }
                );

                oAjaxRequest.setMessage("Salvando");
                oAjaxRequest.execute();
            }

            function js_excluirGradesHorarios () {
                oParametros                 = new Object();
                oParametros.exec            = 'excluir';
                oParametros.iCodigoGrade    = $F('gradehorario_sequencial');

                if( oParametros.iCodigoGrade == '' || !confirm('Deseja excluir a grade de horário código '+ oParametros.iCodigoGrade +'?')) {
                    return false;
                }

                var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
                    function (oAjax, lErro) {
                        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));
                        if (lErro == false) {
                            if (oAjax.erro == false) {
                                window.location = 'rec4_cadastrogradeshorarios001.php';
                            }
                        }
                    }
                );

                oAjaxRequest.setMessage("Excluíndo grade de horário.");
                oAjaxRequest.execute();
            }

            function js_montaGrid() {

                oGridJornadas              = new DBGrid("dataGridJornadas");
                oGridJornadas.sName        = "dataGridJornadas";
                oGridJornadas.nameInstance = "oGridJornadas";

                oGridJornadas.setHeader(["Ordem/Dia", "", "Jornada", "Excluir"]);
                oGridJornadas.setCellWidth(["50px","", "200px", "50px"]);
                oGridJornadas.setCellAlign(["center", "", "center", "center"]);
                oGridJornadas.show( $('grid_registros') );
                oGridJornadas.showColumn(false, 2);
            }

            function js_pesquisaJornada() {
                js_OpenJanelaIframe(
                    'top.corpo',
                    'db_iframe_jornada',
                    'func_jornada.php?funcao_js=parent.js_retornoPesquisa|rh188_sequencial|rh188_descricao',
                    'Pesquisa Jornada',
                    true
                );
            }

            function js_retornoPesquisa(iCodigoJornada, sDescricao) {
                $('jornada_sequencial').value = iCodigoJornada;
                $('jornada_descricao').value  = sDescricao;
                db_iframe_jornada.hide();
            }

            function js_lancar() {
                if ($F('jornada_sequencial') == '' ) {
                    alert('Selecione uma jornada para prosseguir.');
                    return false;
                }

                oJornada                = new Object();
                oJornada.iOrdem         = aJornadasHorarios.length + 1;
                oJornada.iCodigoJornada = $F('jornada_sequencial');
                oJornada.sDescricao     = $F('jornada_descricao');

                aJornadasHorarios.push(oJornada);

                js_registrosGrid();
            }

            function js_excluir(iOrdemExcluir) {

                if (!confirm('Confirma a exclusão dessa jornada?')) {
                    return false;
                }

                var aNovaJornadaHorarios = new Array();

                for (var iOrdem = 0; iOrdem < aJornadasHorarios.length; iOrdem++) {
                    oJornada = aJornadasHorarios[iOrdem];

                    if (oJornada.iOrdem != iOrdemExcluir) {
                        oNovaJornada                = new Object();
                        oNovaJornada.iOrdem         = aNovaJornadaHorarios.length + 1;
                        oNovaJornada.iCodigoJornada = oJornada.iCodigoJornada;
                        oNovaJornada.sDescricao     = oJornada.sDescricao;

                        aNovaJornadaHorarios.push(oNovaJornada);
                    }
                }

                aJornadasHorarios = aNovaJornadaHorarios;
                js_registrosGrid();
            }

            function js_registrosGrid() {
                var aBotoes = new Array();

                oGridJornadas.clearAll(true);

                for (var iOrdem = 0; iOrdem < aJornadasHorarios.length; iOrdem++) {
                    oJornada = aJornadasHorarios[iOrdem];

                    oGridJornadas.addRow([oJornada.iOrdem, oJornada.iCodigoJornada, oJornada.sDescricao.urlDecode(), '']);

                    oBotaoExcluir            = document.createElement('input');
                    oBotaoExcluir.type       = 'button';
                    oBotaoExcluir.value      = 'Excluir';
                    oBotaoExcluir.setAttribute('onclick', 'js_excluir(' + (iOrdem + 1) + ')');

                    oBotoes                  = new Object();
                    oBotoes.oBotaoExcluir    = oBotaoExcluir;
                    oBotoes.sIdCelulaExcluir = oGridJornadas.aRows[iOrdem].aCells[3].sId;

                    aBotoes.push(oBotoes);
                }

                oGridJornadas.renderRows();

                for ( var iBotoes = 0; iBotoes < aBotoes.length; iBotoes++ ) {
                    oBotoes              = aBotoes[iBotoes];
                    oCelulaBotaoExcluir  = document.getElementById(oBotoes.sIdCelulaExcluir);
                    oCelulaBotaoExcluir.appendChild(oBotoes.oBotaoExcluir);
                }
            }
        </script>
    </body>
</html>
