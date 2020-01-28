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

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\GestaoProcesso;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));

$idUsuario = db_getsession("DB_id_usuario");
$oGestor = GestaoProcesso::getById($idUsuario);

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("scripts.js, prototype.js, strings.js, estilos.css, widgets/DBLancador.widget.js, widgets/DBAncora.widget.js, EmissaoRelatorio.js, AjaxRequest.js, dates.js"); ?>
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Relatório de Processos Vencidos</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="dataInicial">Período:</label>
                    </td>
                    <td>
                        <input type="text" id="dataInicial" name="dataInicial">
                        <label for="dataFinal">Até:</label>
                        <input type="text" id="dataFinal" name="dataFinal">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="tipoEmissao">Tipo de Emissão:</label>
                    </td>
                    <td>
                        <select id="tipoEmissao">
                            <?php echo $oGestor->ehGestorPrincipal() ? "<option value='0'>Todos os departamentos</option>" : "" ?>
                            <option value='1'>Com os departamentos selecionados</option>
                            <option value='2'>Sem os departamentos selecionados</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="detalhamento">Detalhamento:</label>
                    </td>
                    <td>
                        <select id="detalhamento" style="width: 84px;">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="divLancadorDepartamentos"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <div>
            <input type="button" value="Emitir" onClick="js_emitir()">
            <input id="gestorPrincipal" type="hidden" value="<?= $oGestor->ehGestorPrincipal(); ?>"/>
        </div>
    </form>
</div>
</body>
<?php db_menu(); ?>
</html>
<script type="text/javascript">

    require_once('scripts/widgets/Input/DBInput.widget.js');
    require_once("scripts/widgets/Input/DBInputDate.widget.js");

    var dataInicial = new DBInputDate($('dataInicial'));
    var dataFinal = new DBInputDate($('dataFinal'));

    var hoje = new Date();
    var primeiroDiaDoMesAtual = new Date(hoje.getFullYear(), hoje.getMonth() - 1, hoje.getDate());
    
    dataInicial.setValue(primeiroDiaDoMesAtual.toLocaleDateString());
    dataFinal.setValue(hoje.toLocaleDateString());

    var sFuncao = $F('gestorPrincipal') == true ? "func_db_depart.php" : "func_gestaodepartamentoprocesso.php";
    var sRPC = 'prot4_gestaoprocessosvencidos.RPC.php';

    oLancadorDepartamentos = new DBLancador("oLancadorDepartamentos");
    oLancadorDepartamentos.setNomeInstancia("oLancadorDepartamentos");
    oLancadorDepartamentos.setLabelAncora("Departamento: ");
    oLancadorDepartamentos.setTextoFieldset("Departamentos Selecionados");
    oLancadorDepartamentos.setParametrosPesquisa(sFuncao, ['coddepto', 'descrdepto'], '&lSomenteDadosDepartamento');
    oLancadorDepartamentos.setGridHeight("400px");
    oLancadorDepartamentos.show($("divLancadorDepartamentos"));

    function js_emitir() 
    {
        var oDataInicial = Date.convertFrom($F('dataInicial'), DATA_PTBR);
        var oDataFinal = Date.convertFrom($F('dataFinal'), DATA_PTBR);
        var iDeferencaTempo = Math.abs(oDataFinal.getTime() - oDataInicial.getTime());
        var iDiferencaDias  = Math.ceil(iDeferencaTempo / (1000 * 3600 * 24)); 
        var aDepartamentosSelecionados = [];

        if (empty($F('dataInicial'))) {
            alert("Informe o período inicial.");
            return false;
        }

        if (empty($F('dataFinal'))) {
            alert("Informe o período final.");
            return false;
        }

        if (js_comparadata($F('dataInicial'), $F('dataFinal'), ">")) {
            alert("Data Inicial do Período não pode ser maior que a Data Final do Período.");
            return false;
        }


        if (iDiferencaDias > 365) {
            if (!confirm("Períodos com mais de um ano de duração poderão ocasionar lentidão e um número alto de páginas.\nDeseja emitir o relatório mesmo assim?")) {
                return false;    
            }
            
        }

        if ($F('tipoEmissao') != 0 && oLancadorDepartamentos.getRegistros().length == 0) {
            alert("Selecione ao menos um departamento.");
            return false;
        }

        oLancadorDepartamentos.getRegistros().each(function (oLinha) {
            aDepartamentosSelecionados.push(oLinha.sCodigo);
        });

        var oParametros = {
            'tipoEmissao': $F('tipoEmissao'),
            'aDepartamentosSelecionados': aDepartamentosSelecionados,
            'dataInicial': $F('dataInicial'),
            'dataFinal': $F('dataFinal'),
            'detalhamento': $F('detalhamento')
        };


        var oRelatorio = new EmissaoRelatorio('pro2_processosvencidos002.php', oParametros);
        oRelatorio.open();
    }

    function validaTipoEmissao() {
        if ($F('tipoEmissao') == 0) {
            oLancadorDepartamentos.clearAll();
            $('divLancadorDepartamentos').setStyle({'display': 'none'});
        } else {
            $('divLancadorDepartamentos').setStyle({'display': ''});
        }
    }

    $('tipoEmissao').observe('change', function () {
        validaTipoEmissao();
    });

    (function () {
        buscarDepartamentos();
        validaTipoEmissao();
    })();

    function buscarDepartamentos() {

        new AjaxRequest(sRPC, {'exec': 'buscarPorUsuario'}, function (oRetorno, lErro) {

            if (oRetorno.iStatus == 2) {

                alert(oRetorno.sMessage);
                return;
            }

            $('gestorPrincipal').value = oRetorno.lGestorPrincipal;

            for (var oDepartamento of oRetorno.aDepartamentos) {
                oLancadorDepartamentos.adicionarRegistro(oDepartamento.iCodigo, oDepartamento.sNome);
            }
        }).setMessage('Aguarde, salvando gestores...').execute();
    }
</script>
