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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
</head>
<body class="body-default">
<div class="container">
    <form id="frmGestaoProcessos">
        <fieldset>
            <legend>Gestão de Processos Vencidos</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="codigoGestor">
                            <a href="#" id="ancoraGestor">Gestor(a) de Processos:</a>
                        </label>
                    </td>
                    <td>
                        <input id="codigoGestor" lang="id_usuario" type="text" value=""/>
                    </td>
                    <td>
                        <input id="gestorProcessosDesc" lang="nome" type="text" value="" class="readonly"
                               disabled="disabled"/>
                    </td>
                </tr>

                <tr>
                    <td colspan="3">
                        <fieldset class="separator">
                            <legend>Responsável Por Departamento</legend>

                            <table>
                                <tr>
                                    <td>
                                        <label for="codigoResponsavel">
                                            <a href="#" id="ancoraResponsavel">Responsável:</a>
                                        </label>
                                    </td>
                                    <td>
                                        <input id="codigoResponsavel" lang="id_usuario" type="text" value=""/>
                                    </td>
                                    <td>
                                        <input id="responsavelDesc" lang="nome" type="text" value="" class="readonly"
                                               disabled="disabled"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <label for="codigoDepartamento">
                                            <a href="#" id="ancoraDepartamento">Departamento:</a>
                                        </label>
                                    </td>
                                    <td>
                                        <input id="codigoDepartamento" lang="coddepto" type="text" value=""/>
                                    </td>
                                    <td>
                                        <input id="departamentoDesc" lang="descrdepto" type="text" value=""
                                               class="readonly" disabled="disabled"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <div class="center">
                                            <input id="incluir" type="button" value="Incluir"
                                                   onclick="incluirResponsavel()"/>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3">
                                        <div id="gridResponsavel"></div>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </fieldset>

        <input id="btnSalvar" type="button" value="Salvar"/>
        <input id="iSequencialGestorProcesso" type="hidden" value=""/>
    </form>
</div>
</body>
<?php db_menu(); ?>
<script>

    var sRPC = 'prot4_gestaoprocessosvencidos.RPC.php';
    var aResponsaveisExistentes = [];

    var oLookupGestor = new DBLookUp(
        $('ancoraGestor'),
        $('codigoGestor'),
        $('gestorProcessosDesc'),
        {
            "sArquivo": "func_db_usuarios.php",
            "sObjetoLookUp": "db_iframe_db_usuarios",
            "sLabel": "Pesquisar Usuários"
        }
    );

    var oLookupResponsavel = new DBLookUp(
        $('ancoraResponsavel'),
        $('codigoResponsavel'),
        $('responsavelDesc'),
        {
            "sArquivo": "func_db_usuarios.php",
            "sObjetoLookUp": "db_iframe_db_usuarios",
            "sLabel": "Pesquisar Usuários"
        }
    );

    var oLookupDepartamento = new DBLookUp(
        $('ancoraDepartamento'),
        $('codigoDepartamento'),
        $('departamentoDesc'),
        {
            "sArquivo": "func_db_depart.php",
            "sObjetoLookUp": "db_iframe_db_depart",
            "sLabel": "Pesquisar Departamento"
        }
    );

    /**
     * Grid dos responsáveis
     * Id registro é uma combinação do código do responsável com o código do departamento(Ex.: 1#1), pois é necessário
     * um campo único como chave para controlar a inserção na Grid
     */
    var oCollectionResponsavel = Collection.create().setId('registro');
    var oGridResponsavel = new DatagridCollection(oCollectionResponsavel, 'gridResponsavel');
    oGridResponsavel.configure({'width': '500', 'height': '200'});
    oGridResponsavel.addColumn('registro');
    oGridResponsavel.addColumn('codigoResponsavel');
    oGridResponsavel.addColumn('nomeResponsavel')
        .configure({'label': 'Responsável', 'align': 'left', 'width': '43%'});
    oGridResponsavel.addColumn('codigoDepartamento');
    oGridResponsavel.addColumn('nomeDepartamento')
        .configure({'label': 'Departamento', 'align': 'left', 'width': '43%'});
    oGridResponsavel.addAction('Excluir', 'Excluir', function (oEvento, oRegistro) {

        oCollectionResponsavel.remove(oRegistro.registro);
        oGridResponsavel.reload();
    });
    oGridResponsavel.hideColumns([0, 1, 3]);
    oGridResponsavel.show($('gridResponsavel'));

    function incluirResponsavel() {

        if (empty($F('codigoResponsavel')) || empty($F('responsavelDesc'))) {

            alert('Selecione um Responsável.');
            return false;
        }

        if (empty($F('codigoDepartamento')) || empty($F('departamentoDesc'))) {

            alert('Selecione um Departamento.');
            return false;
        }

        var oResponsavelIncluir = {
            'registro': $F('codigoResponsavel') + '#' + $F('codigoDepartamento'),
            'codigoResponsavel': $F('codigoResponsavel'),
            'nomeResponsavel': $F('responsavelDesc'),
            'codigoDepartamento': $F('codigoDepartamento'),
            'nomeDepartamento': $F('departamentoDesc')
        };

        oGridResponsavel.collection.add(oResponsavelIncluir);
        oGridResponsavel.reload();

        $('codigoResponsavel').value = '';
        $('responsavelDesc').value = '';
        $('codigoDepartamento').value = '';
        $('departamentoDesc').value = '';
    }

    $('btnSalvar').observe('click', function () {

        var aResponsaveisDepartamento = [];
        for (var oResponsavel of oCollectionResponsavel.get()) {
            aResponsaveisDepartamento.push({
                'iResponsavel': oResponsavel.codigoResponsavel,
                'iDepartamento': oResponsavel.codigoDepartamento
            });
        }

        var oParametro = {
            exec: 'salvar',
            iSequencialGestorProcesso: $F('iSequencialGestorProcesso'),
            iGestorProcesso: $F('codigoGestor'),
            aResponsaveisDepartamento: aResponsaveisDepartamento,
            aResponsaveisExistentes: aResponsaveisExistentes
        };

        new AjaxRequest(sRPC, oParametro, function (oRetorno, lErro) {
            alert(oRetorno.sMessage);
            buscar();
        }).setMessage('Aguarde, salvando gestores...').execute();
    });

    /**
     * Busca os registros de gestão ao acessar a rotina
     */
    (function () {
        buscar();
    })();

    function buscar() {
        var oParametro = {'exec': 'buscar'};
        new AjaxRequest(sRPC, oParametro, function (oRetorno, lErro) {

            if (lErro) {

                alert(oRetorno.sMessage);
                return;
            }

            $('iSequencialGestorProcesso').value = oRetorno.iSequencialGestorProcesso;
            $('codigoGestor').value = oRetorno.iGestorProcesso;
            $('gestorProcessosDesc').value = oRetorno.sNomeGestorProcesso ? oRetorno.sNomeGestorProcesso : '';

            oCollectionResponsavel.clear();
            for (var oResponsavel of oRetorno.aResponsaveisDepartamento) {

                if (!aResponsaveisExistentes.in_array(oResponsavel.codigoResponsavel)) {
                    aResponsaveisExistentes.push(oResponsavel.codigoResponsavel);
                }
                oCollectionResponsavel.add(oResponsavel);
                oGridResponsavel.reload();
            }

        }).setMessage('Aguarde, buscando configurações...').execute();
    }
</script>
</html>