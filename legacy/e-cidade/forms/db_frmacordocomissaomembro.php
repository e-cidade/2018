<?php
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

require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("libs/db_libdicionario.php"));
require_once(modification("libs/db_utils.php"));

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clacordocomissaomembro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac08_sequencial");
$clrotulo->label("ac07_descricao");
$clrotulo->label("z01_nome");

if (isset($ac07_acordocomissao)) {
    $oAcordo = new cl_acordocomissao;
    $sSqlAcordo = $oAcordo->sql_query_file(null, 'ac08_descricao', '', "ac08_sequencial={$ac07_acordocomissao}");
    $rsAcordo = $oAcordo->sql_record($sSqlAcordo);
    $ac08_descricao = db_utils::fieldsMemory($rsAcordo, 0)->ac08_descricao;
}

$Tac07_acordocomissao = isset($Tac07_acordocomissao) ? $Tac07_acordocomissao : null;
$Tac07_numcgm = isset($Tac07_numcgm) ? $Tac07_numcgm : null;
$Lac07_numcgm = isset($Lac07_numcgm) ? $Lac07_numcgm : null;
$Tac07_tipomembro = isset($Tac07_tipomembro) ? $Tac07_tipomembro : null;
$Lac07_tipomembro = isset($Lac07_tipomembro) ? $Lac07_tipomembro : null;
$Tac07_datainicio = isset($Tac07_datainicio) ? $Tac07_datainicio : null;
$Lac07_datainicio = isset($Lac07_datainicio) ? $Lac07_datainicio : null;
$Tac07_datatermino = isset($Tac07_datatermino) ? $Tac07_datatermino : null;
$Lac07_datatermino = isset($Lac07_datatermino) ? $Lac07_datatermino : null;
$Tac07_numeroatodesignacao = isset($Tac07_numeroatodesignacao) ? $Tac07_numeroatodesignacao : null;
$Lac07_numeroatodesignacao = isset($Lac07_numeroatodesignacao) ? $Lac07_numeroatodesignacao : null;
$Tac07_anoatodesignacao = isset($Tac07_anoatodesignacao) ? $Tac07_anoatodesignacao : null;
$Lac07_anoatodesignacao = isset($Lac07_anoatodesignacao) ? $Lac07_anoatodesignacao : null;
$Tac07_arquivo = isset($Tac07_arquivo) ? $Tac07_arquivo : null;
$Lac07_arquivo = isset($Lac07_arquivo) ? $Lac07_arquivo : null;

?>
<style>
    fieldset {
        width: 100%;
    }

    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }

    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
</style>
<form name="form1" method="post" enctype="multipart/form-data" id="membroForm">
    <div style="margin: 50px auto; width: 750px;">
        <fieldset>
            <legend><b>Membros</b></legend>

            <table border="0" align='left'>

                <?php db_input('ac07_sequencial', 10, $Iac07_sequencial, true, 'hidden', 3, ''); ?>

                <tr>
                    <td title="<?php echo $Tac07_acordocomissao ?>">
                        <?php db_ancora("<b>Comissão</b>:", "js_pesquisaac07_acordocomissao(true);", 3); ?>
                    </td>
                    <td>
                        <?php
                        db_input('ac07_acordocomissao', 10, $Iac07_acordocomissao, true, 'text', 3, "");
                        db_input('ac08_descricao', 60, $Iac07_acordocomissao, true, 'text', 3, '');
                        ?>
                    </td>
                </tr>

                <tr>
                    <td title="<?php echo $Tac07_numcgm; ?>">
                        <?php db_ancora($Lac07_numcgm, "js_pesquisaac07_numcgm(true);", 2); ?>
                    </td>
                    <td>
                        <?php
                        db_input('ac07_numcgm', 10, $Iac07_numcgm, true, 'text', 2,
                            " onchange='js_pesquisaac07_numcgm(false);'");
                        db_input('z01_nome', 60, $Iz01_nome, true, 'text', 3, '');
                        ?>
                    </td>
                </tr>

                <tr>
                    <td title="<?php echo $Tac07_tipomembro ?>">
                        <?php echo $Lac07_tipomembro ?>
                    </td>
                    <td>
                        <?php
                        $oDaoAcordoComissaoTipoMembro = new cl_acordocomissaotipomembro;
                        $sSqlBuscaTipoMembro = $oDaoAcordoComissaoTipoMembro->sql_query_file(null, "*",
                            "ac42_sequencial");
                        $rsBuscaTipoMembro = $oDaoAcordoComissaoTipoMembro->sql_record($sSqlBuscaTipoMembro);

                        $aTipo = array("0" => "Selecione");
                        if ($oDaoAcordoComissaoTipoMembro->numrows > 0) {
                            $aTipo = array();
                            for ($iRowTipoMembro = 0; $iRowTipoMembro < $oDaoAcordoComissaoTipoMembro->numrows; $iRowTipoMembro++) {
                                $oDadoTipoMembro = db_utils::fieldsMemory($rsBuscaTipoMembro, $iRowTipoMembro);
                                $aTipo[$oDadoTipoMembro->ac42_sequencial] = $oDadoTipoMembro->ac42_descricao;
                                unset($oDadoTipoMembro);
                            }
                        }
                        db_select('ac07_tipomembro', $aTipo, true, 1, '');
                        ?>
                    </td>
                </tr>
                <tr>
                    <td title="<?php echo $Tac07_datainicio; ?>">
                        <label><?php echo $Lac07_datainicio; ?></label>
                    </td>
                    <td>
                        <?php db_inputdata('ac07_datainicio', '', '', '', true, 'text', 2); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?php echo $Tac07_datatermino; ?>">
                        <label><?php echo $Lac07_datatermino; ?></label>
                    </td>
                    <td>
                        <?php db_inputdata('ac07_datatermino', '', '', '', true, 'text', 2); ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <legend>LicitaCon</legend>
            <table>
                <tbody>
                <tr>
                    <td title="<?php echo $Tac07_numeroatodesignacao; ?>">
                        <label for="ac07_numeroatodesignacao"><?php echo $Lac07_numeroatodesignacao; ?></label>
                    </td>
                    <td>
                        <input type="text" id="ac07_numeroatodesignacao" name="ac07_numeroatodesignacao"
                               onkeyup="validaNumero(event, 'Número do Ato de Designação')">
                    </td>
                </tr>
                <tr>
                    <td title="<?php echo $Tac07_anoatodesignacao; ?>">
                        <label for="ac07_anoatodesignacao"><?php echo $Lac07_anoatodesignacao; ?></label>
                    </td>
                    <td>
                        <input type="text" id="ac07_anoatodesignacao" name="ac07_anoatodesignacao"
                               onkeyup="validaAnoAtoDesignacao(event, 'Ano do Ato de Designação')">
                    </td>
                </tr>
                <tr>
                    <td title="<?php echo $Tac07_arquivo; ?>">
                        <label for="ac07_arquivo"><?php echo $Lac07_arquivo; ?></label>
                    </td>
                    <td>
                        <input type="file" id="ac07_arquivo" name="ac07_arquivo">
                    </td>
                </tr>
                <tr id="baixar_tr" style="display: none;">
                    <td>
                        <label for="baixar">
                            <b>Arquivo Atual</b>
                        </label>
                    </td>
                    <td>
                        <input type="text" id="nome_arquivo" readonly
                               style="background-color: #DEB887;text-transform: uppercase;">
                        <input type="button" value="Baixar" id="baixar">
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="alert alert-warning" role="alert">
                Os campos "Número do Ato de Designação", "Ano do Ato de Designação" e "Arquivo" são obrigatórios para
                uso do LicitaCon. Se não informados, poderão haver futuras inconsistências.
            </div>
        </fieldset>
        <div style="margin-top: 10px; text-align: center;">
            <input type="button" id="botao_controle" value="Incluir" onclick="js_incluiMembro();">
            <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_limpa()">
        </div>
        <fieldset style="margin-top:10px;">
            <legend align=center><b>Membros Cadastrados</b></legend>
            <div id='cntGridMembros'></div>
        </fieldset>
    </div>
</form>
<script>

    var sUrl = 'con4_contratos.RPC.php';
    var baixar = document.getElementById('baixar');

    js_init();

    baixar.addEventListener('click', function () {
        var baixarTr = document.getElementById('baixar_tr');
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_download', 'con4_contratos.RPC.php?exec=download&codigo=' + baixarTr.dataset.codigo, 'Download de Arquivos', false);
    });

    function js_pesquisaac07_acordocomissao(mostra) {
        if (mostra === true) {
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordocomissaomembro', 'db_iframe_acordocomissao', 'func_acordocomissao.php?funcao_js=parent.js_mostraacordocomissao1|ac08_sequencial|ac08_sequencial', 'Pesquisa', true, '0', '1');
        } else {
            if (document.form1.ac07_acordocomissao.value) {
                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordocomissaomembro', 'db_iframe_acordocomissao', 'func_acordocomissao.php?pesquisa_chave=' + document.form1.ac07_acordocomissao.value + '&funcao_js=parent.js_mostraacordocomissao', 'Pesquisa', false);
            } else {
                document.form1.ac08_sequencial.value = '';
            }
        }
    }

    function js_mostraacordocomissao(chave, erro) {
        document.form1.ac08_sequencial.value = chave;
        if (erro === true) {
            document.form1.ac07_acordocomissao.focus();
            document.form1.ac07_acordocomissao.value = '';
        }
    }

    function js_mostraacordocomissao1(chave1, chave2) {
        document.form1.ac07_acordocomissao.value = chave1;
        document.form1.ac08_sequencial.value = chave2;
        db_iframe_acordocomissao.hide();
    }

    function js_pesquisaac07_numcgm(mostra) {
        if (mostra === true) {
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordocomissaomembro', 'db_iframe_cgm', 'func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true, '0', '1');
        } else {
            if (document.form1.ac07_numcgm.value) {
                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_acordocomissaomembro', 'db_iframe_cgm', 'func_cgm.php?pesquisa_chave=' + document.form1.ac07_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
            } else {
                document.form1.z01_nome.value = '';
            }
        }
    }

    function js_mostracgm(erro, chave) {

        document.form1.z01_nome.value = chave;
        if (erro === true) {
            document.form1.ac07_numcgm.focus();
            document.form1.ac07_numcgm.value = '';
        }
    }

    function js_mostracgm1(chave1, chave2) {
        document.form1.ac07_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_cgm.hide();
    }

    function js_init() {

        oGridMembrosComissao = new DBGrid("gridMembros");
        oGridMembrosComissao.nameInstance = "oGridMembrosComissao";

        oGridMembrosComissao.setCellWidth(new Array('70px',
            '70px',
            '280px',
            '200px',
            '80px'
        ));

        oGridMembrosComissao.setCellAlign(new Array("center",
            "center",
            "left",
            "left",
            "center")
        );
        oGridMembrosComissao.setHeader(new Array("Código",
            "Cgm",
            "Membro",
            "Responsabilidade",
            "Ação")
        );
        oGridMembrosComissao.show($('cntGridMembros'));
    }

    function js_consultaMembros(iAcordo) {

        var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.consultando_membros");
        js_divCarregando(sMsg, 'msgBox');

        var strJson = '{"exec":"getMembros","iAcordo":"' + iAcordo + '"}';

        var oAjax = new Ajax.Request(sUrl, {
                method: 'post',
                parameters: 'json=' + strJson,
                onComplete: js_completaGrid
            }
        );
    }

    function js_completaGrid(oAjax) {

        js_removeObj("msgBox");

        var oRetorno = eval("(" + oAjax.responseText + ")");
        var aMembros = oRetorno.oAcordo.aMembros;

        oGridMembrosComissao.clearAll(true);

        aMembros.each(function (oMembro, id) {

            var aLinha = new Array();

            aLinha[0] = oMembro.iCodigo;
            aLinha[1] = oMembro.iCodigoCgm;
            aLinha[2] = oMembro.sNome.urlDecode();
            aLinha[3] = oMembro.sResponsabilidade.urlDecode();
            aLinha[4] = "<input type='button' value='A' onclick='js_carrega(" + oMembro.iCodigo + ", \"alt\")' width='1'>";

            oGridMembrosComissao.addRow(aLinha);
        });

        oGridMembrosComissao.renderRows();
    }

    function js_incluiMembro(iCodigo = null) {

        var ac07NumeroAtoDesignacao = document.getElementById('ac07_numeroatodesignacao');
        var ac07AnoAtoDesignacao = document.getElementById('ac07_anoatodesignacao');
        var ac07Arquivo = document.getElementById('ac07_arquivo');
        var ac07NumCgm = document.getElementById('ac07_numcgm');
        var ac07AcordoComissao = document.getElementById('ac07_acordocomissao');
        var ac07DataInicio = document.getElementById('ac07_datainicio');
        var ac07DataTermino = document.getElementById('ac07_datatermino');
        var ac07TipoMembro = document.getElementById('ac07_tipomembro');

        if (ac07NumCgm.value) {

            js_divCarregando(_M('patrimonial.patrimonio.db_frmacordocomissaomembro.incluindo_membro'), 'incluindoMembro');

            var formData = new FormData();
            formData.append('iCodigoComissao', ac07AcordoComissao.value);
            formData.append('sDataInicio', ac07DataInicio.value);
            formData.append('sDataTermino', ac07DataTermino.value);
            formData.append('iResponsabilidade', ac07TipoMembro.value);
            formData.append('iCodigoCgm', ac07NumCgm.value);
            formData.append('numeroAtoDesignacao', ac07NumeroAtoDesignacao.value);
            formData.append('anoAtoDesignacao', ac07AnoAtoDesignacao.value);

            if (iCodigo) {
                formData.append('exec', 'alteraMembro');
                formData.append('iCodigo', iCodigo);
            } else {
                formData.append('exec', 'incluiMembro');
            }

            if (ac07Arquivo.files[0]) {
                formData.append('arquivo', ac07Arquivo.files[0]);
            }

            var oReq = new XMLHttpRequest();
            oReq.open('POST', 'con4_contratos.RPC.php');
            oReq.onload = function () {

                js_removeObj('incluindoMembro');

                var response = JSON.parse(oReq.response);

                alert(response.message.urlDecode());

                if (response.status === 1) {
                    js_limpa();
                    js_consultaMembros(response.iCodigo);
                }
            };
            oReq.send(formData);

        } else {
            alert(_M("patrimonial.patrimonio.db_frmacordocomissaomembro.selecione_cgm"));
            ac07NumCgm.focus();
        }
    }

    function js_excluirMembro(iCodigo) {

        var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.excluindo_membro");
        js_divCarregando(sMsg, 'msgBox');

        var strJson = '{"exec":"exluiMembro", "iCodigo":"' + iCodigo + '"}';

        var oAjax = new Ajax.Request(sUrl, {
                method: 'post',
                parameters: 'json=' + strJson,
                onComplete: js_concluiExclusao
            }
        );
    }

    function js_concluiExclusao(oAjax) {
        js_removeObj("msgBox");
        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sMsg = oRetorno.message;

        alert(sMsg.urlDecode());

        js_limpa();
        js_consultaMembros(oRetorno.iCodigo);
    }

    function js_carrega(iCodigo, sAcao) {

        var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.carregando_membro");
        js_divCarregando(sMsg, 'msgBox');

        var strJson = '{"exec":"carregaMembro", "iCodigo":"' + iCodigo + '", "sAcao":"' + sAcao + '"}';

        var oAjax = new Ajax.Request(sUrl, {
                method: 'post',
                parameters: 'json=' + strJson,
                onComplete: js_setCampos
            }
        );
    }

    function js_setCampos(oAjax) {

        js_removeObj("msgBox");

        var ac07NumeroAtoDesignacao = document.getElementById('ac07_numeroatodesignacao');
        var ac07AnoAtoDesignacao = document.getElementById('ac07_anoatodesignacao');
        var nomeArquivo = document.getElementById('nome_arquivo');
        var oRetorno = eval("(" + oAjax.responseText + ")");
        var sAcao = oRetorno.sAcao;
        var oMembro = oRetorno.oMembro;
        var ac07Numcgm = $('ac07_numcgm');
        var botaoControle = $('botao_controle');
        var baixarTr = document.getElementById('baixar_tr');

        ac07Numcgm.value = oMembro.iCodigoCgm;
        ac07NumeroAtoDesignacao.value = oMembro.numeroAtoDesignacao;
        ac07AnoAtoDesignacao.value = oMembro.anoAtoDesignacao;
        nomeArquivo.value = oMembro.nomeArquivo;
        $('ac07_sequencial').value = oMembro.iCodigo;
        $('z01_nome').value = oMembro.sNome.urlDecode();
        $('ac07_tipomembro').value = oMembro.iResponsabilidade;
        $('ac07_datainicio').value = oMembro.sDataInicio;
        $('ac07_datatermino').value = oMembro.sDataTermino;

        botaoControle.stopObserving("click");

        baixarTr.style.display = oMembro.arquivo ? '' : 'none';
        baixarTr.dataset.codigo = oMembro.iCodigo;

        if (sAcao == 'alt') {

            botaoControle.value = 'Alterar';
            botaoControle.onclick = function () {
                js_incluiMembro($F('ac07_sequencial'));
            };
            botaoControle.disabled = false;
            ac07Numcgm.style.backgroundColor = 'white';

        } else {

            botaoControle.value = "Excluir";
            botaoControle.onclick = function () {
                js_excluirMembro($F('ac07_sequencial'))
            };
            botaoControle.disabled = false;
            ac07Numcgm.readOnly = true;
            ac07Numcgm.style.backgroundColor = 'rgb(222, 184, 135)';

        }
    }

    function js_limpa() {

        var ac07NumeroAtoDesignacao = document.getElementById('ac07_numeroatodesignacao');
        var ac07AnoAtoDesignacao = document.getElementById('ac07_anoatodesignacao');
        var ac07Arquivo = document.getElementById('ac07_arquivo');
        var baixarTr = document.getElementById('baixar_tr');

        baixarTr.style.display = 'none';
        ac07NumeroAtoDesignacao.value = '';
        ac07AnoAtoDesignacao.value = '';
        ac07Arquivo.value = '';

        $('ac07_sequencial').value = '';
        $('ac07_numcgm').value = '';
        $('z01_nome').value = '';
        $('ac07_tipomembro').value = 1;
        $('ac07_datainicio').value = '';
        $('ac07_datatermino').value = '';

        with ($('ac07_numcgm')) {
            readOnly = false;
            value = '';
            style.backgroundColor = 'white';
        }

        $('botao_controle').value = "Incluir";
        $('botao_controle').onclick = function () {
            js_incluiMembro()
        };
        $('botao_controle').disabled = false;
    }

    js_consultaMembros(<?php echo $ac07_acordocomissao; ?>);

    function validaAnoAtoDesignacao(e, message) {
        var campo = e.target;
        var valor = campo.value;

        if (valor.length > 4) {
            campo.value = '';
            alert(message + 'deve conter no máximo 4 dígitos.');

            return false;
        }

        validaNumero(e, message);
    }

    function validaNumero(e, message) {
        var campo = e.target;
        var valor = campo.value;
        var isNumber = /^\d+$/;

        if (valor.length > 10) {
            campo.value = '';
            alert(message + ' deve conter no máximo 10 dígitos.');

            return false;
        }

        if (valor && !isNumber.test(valor)) {
            campo.value = '';
            alert(message + ' deve conter um valor numérico.');

            return false;
        }
    }
</script>