<?php
/*
 *  E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017 DBSeller Servicos de Informatica
 *                              www.dbseller.com.br
 *                              e-cidade@dbseller.com.br
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('dbforms/db_funcoes.php'));

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" content="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <style>
        textarea {
            resize: none;
        }

        @media screen and (min-width: 576px) {
            .td {
                width: 45%;
            }
        }

        @media screen and (min-width: 768px) {
            .td {
                width: 40%;
            }
        }

        @media screen and (min-width: 992px) {
            .td {
                width: 35%;
            }
        }

        @media screen and (min-width: 1200px) {
            .td {
                width: 30%;
            }
        }
    </style>
</head>
<body>
<form style="width: 40%; text-align: center; margin: auto;" onsubmit="salvar(event)">
    <fieldset style="width: 100%; margin-top: 5%;">
        <legend>Notificação de Movimentação de Processos</legend>
        <table style="width: 100%; margin-top: 10px;">
            <tbody>
            <tr>
                <td class="td">
                    <label for="notificar_receber_processo">
                        <strong>Notificar ao Receber Processo:</strong>
                    </label>
                </td>
                <td>
                    <select name="notificar_receber_processo" id="notificar_receber_processo"
                            onchange="validarNotificarReceberProcesso()">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="notificar_data_vencimento">
                        <strong>Notificar na Data de Vencimento:</strong>
                    </label>
                </td>
                <td>
                    <select name="notificar_data_vencimento" id="notificar_data_vencimento"
                            onchange="validarNotificarDataVencimento()">
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <fieldset style="margin-top: 10px;">
                        <legend>Dados da Notificação de Vencimento</legend>
                        <table style="width: 100%; margin-top: 10px;">
                            <tbody>
                            <tr>
                                <td colspan="2">
                                    <fieldset style="margin-bottom: 5px;">
                                        <legend>Variáveis Disponíveis</legend>
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td style="width: 30%;">[numero]</td>
                                                <td>Número do Processo.</td>
                                            </tr>
                                            <tr>
                                                <td>[ano]</td>
                                                <td>Ano do Processo.</td>
                                            </tr>
                                            <tr>
                                                <td>[data_inicial]</td>
                                                <td>Data da Última Movimentação do Processo.</td>
                                            </tr>
                                            <tr>
                                                <td>[data_final]</td>
                                                <td>Data Limite do Prazo Para Realizar Próxima Movimentação.</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </fieldset>
                                </td>
                            </tr>
                            <tr>
                                <td class="td">
                                    <label for="assunto">
                                        <strong>Assunto:</strong>
                                    </label>
                                </td>
                                <td><input type="text" name="assunto" id="assunto" style="width: 100%;"
                                           onchange="validarAssunto()"
                                           oninvalid="alertMensagem(event, 'O campo Assunto é obrigatório.')">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="mensagem">
                                        <strong>Mensagem:</strong>
                                    </label>
                                </td>
                                <td><textarea name="mensagem" id="mensagem" rows="5" style="width: 100%;"
                                              onchange="validarMensagem()"
                                              oninvalid="alertMensagem(event, 'O campo Mensagem é obrigatório.')"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="dias_prazo_movimentacao">
                                        <strong>Dias de Prazo Para Movimentação:</strong>
                                    </label>
                                </td>
                                <td><input type="text" id="dias_prazo_movimentacao" name="dias_prazo_movimentacao"
                                           style="width: 15%;" onchange="validarDiasPrazoMovimentacao()"
                                           oninvalid="alertMensagem(event, 'O campo Dias de Prazo Para Movimentação é obrigatório.')">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>
    </fieldset>
    <input value="Salvar" type="submit" style="margin-top: 15px;">
</form>
<script>
    var notificarReceberProcesso = document.getElementById('notificar_receber_processo');
    var notificarDataVencimento = document.getElementById('notificar_data_vencimento');
    var assunto = document.getElementById('assunto');
    var mensagem = document.getElementById('mensagem');
    var diasPrazoMovimentacao = document.getElementById('dias_prazo_movimentacao');
    var requisicao = {};
    requisicao.url = 'pro1_mensageriaprocesso001.RPC.php';
    requisicao.mensageria = {sequencial: null};

    buscar();

    function atualizarAtributos() {
        notificarReceberProcesso.onchange();
        notificarDataVencimento.onchange();
        assunto.onchange();
        mensagem.onchange();
        diasPrazoMovimentacao.onchange();

        atualizarObrigatoriedade();
    }

    function atualizarObrigatoriedade() {
        var obrigatorio = notificarDataVencimento.value == 1;

        assunto.required = obrigatorio;
        mensagem.required = obrigatorio;
        diasPrazoMovimentacao.required = obrigatorio;
    }

    function buscar() {
        js_divCarregando('Buscando configuração da notificação.', 'mensagem_buscar');

        var formData = new FormData();
        formData.append('acao', 'buscar');

        var oReq = new XMLHttpRequest();
        oReq.open('POST', requisicao.url);
        oReq.onload = function () {
            var dados = JSON.parse(oReq.response).dados;

            requisicao.mensageria.sequencial = dados.p101_sequencial;
            notificarReceberProcesso.value = dados.p101_notificarreceberprocesso == 'f' ? 0 : 1;
            notificarDataVencimento.value = dados.p101_notificardatavencimento == 'f' ? 0 : 1;
            assunto.value = dados.p101_assunto;
            mensagem.value = dados.p101_mensagem;
            diasPrazoMovimentacao.value = dados.p101_diasprazo;

            atualizarAtributos();

            js_removeObj('mensagem_buscar');
        };
        oReq.send(formData);
    }

    function salvar(event) {
        js_divCarregando('Salvando configuração da notificação.', 'mensagem_salvar');

        event.preventDefault();

        var formData = new FormData();
        formData.append('mensageria', JSON.stringify(requisicao.mensageria));
        formData.append('acao', 'salvar');

        var oReq = new XMLHttpRequest();
        oReq.open('POST', requisicao.url);
        oReq.onload = function () {
            js_removeObj('mensagem_salvar');

            var mensagem = JSON.parse(oReq.response).mensagem;
            alert(mensagem);
        };
        oReq.send(formData);
    }

    function validarNotificarReceberProcesso() {
        var valor = notificarReceberProcesso.value;
        var mensageria = requisicao.mensageria;

        mensageria.notificarReceberProcesso = valor;
        requisicao.mensageria = mensageria;
    }

    function validarNotificarDataVencimento() {
        var valor = notificarDataVencimento.value;
        var mensageria = requisicao.mensageria;

        mensageria.notificarDataVencimento = valor;
        requisicao.mensageria = mensageria;

        atualizarObrigatoriedade();
    }

    function validarAssunto() {
        var valor = assunto.value;
        var mensageria = requisicao.mensageria;

        mensageria.assunto = valor;
        requisicao.mensageria = mensageria;
    }

    function validarMensagem() {
        var valor = mensagem.value;
        var mensageria = requisicao.mensageria;

        mensageria.mensagem = valor;
        requisicao.mensageria = mensageria;
    }

    function validarDiasPrazoMovimentacao() {
        var valor = diasPrazoMovimentacao.value;
        var mensageria = requisicao.mensageria;

        if (valor.length && !is_numeric(valor)) {
            alert('O campo "Dias de Prazo Para Movimentação" deve conter um número.');
            valor = diasPrazoMovimentacao.value = '';
        }

        mensageria.diasPrazoMovimentacao = valor;
        requisicao.mensageria = mensageria;
    }

    function alertMensagem(event, mensagem) {
        event.preventDefault();
        alert(mensagem);
    }

    function is_numeric(mixedVar) {
        var whitespace = [
            ' ',
            '\n',
            '\r',
            '\t',
            '\f',
            '\x0b',
            '\xa0',
            '\u2000',
            '\u2001',
            '\u2002',
            '\u2003',
            '\u2004',
            '\u2005',
            '\u2006',
            '\u2007',
            '\u2008',
            '\u2009',
            '\u200a',
            '\u200b',
            '\u2028',
            '\u2029',
            '\u3000'
        ].join('');

        return (typeof mixedVar === 'number' ||
            (typeof mixedVar === 'string' &&
                whitespace.indexOf(mixedVar.slice(-1)) === -1)) &&
            mixedVar !== '' &&
            !isNaN(mixedVar);
    }
</script>
</body>
<?php db_menu(); ?>
</html>