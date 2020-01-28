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
$oDaoDb_sysregrasacessoip->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("db46_observ");
$sNameBotaoProcessar = "";
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Cadastro da Mascara (IP) de Acesso</legend>
            <table>
                <tr>
                    <td nowrap title="<?php echo $Tdb48_idacesso; ?>" >
                        <label class="bold" for="db48_idacesso" id="lbl_db48_idacesso">
                            <?php
                            db_ancora( $Ldb48_idacesso, '', 3);
                            ?>
                        </label>
                    </td>
                    <td>
                        <?
                        db_input('db48_idacesso',6,$Idb48_idacesso,true,'text',3)
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?php echo $Tdb48_ip; ?>" >
                        <label class="bold" for="db48_ip" id="lbl_db48_ip"><?php echo $Sdb48_ip; ?>:</label>
                    </td>
                    <td>
                        <?php db_input('db48_ip', 40, $Idb48_ip, true, 'text', $db_opcao, ""); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="<?php echo $Tdb48_tokenpublico; ?>" >
                        <label class="bold" for="db48_tokenpublico" id="lbl_db48_tokenpublico"><?php echo $Sdb48_tokenpublico; ?>:</label>
                    </td>
                    <td>
                        <?php db_input('db48_tokenpublico', 64, $Idb48_tokenpublico, true, 'text', 3, ""); ?>
                    </td>
                </tr>
            </table>
            <center>
            <?php if($db_opcao == 2) {?>
                <input name="gerarToken" type="button" value="Gerar token" onclick="return js_gerarToken();">
            <?php } ?>
            <input name="incluir" type="submit" id="db_opcaoi" value="Incluir" >
            <input name="alterar" type="submit" id="db_opcaoa" value="Alterar" >
            <input name="excluir" type="submit" id="db_opcaoe" value="Excluir" >
            </center>
    </form>
</div>
</body>
<script>

    function js_gerarToken() {
        var sUrlRC = "con1_db_sysregrasacesso.RPC.php";

        var msgDiv = "Gerando novo token de acesso ...";
        js_divCarregando(msgDiv,'msgBox');

        var oParam      = new Object();
        oParam.exec     = "gerarToken";
        oParam.idRegra  = $F("db48_idacesso");

        var oAjax       = new Ajax.Request(sUrlRC,
            {
                method: "post",
                parameters:'json='+Object.toJSON(oParam),
                onComplete: js_retornoToken
            });

    }

    function js_retornoToken(oAjax) {

        var oRetorno = eval("("+oAjax.responseText+")");

        js_removeObj("msgBox");

        if (oRetorno.erro) {
            alert(oRetorno.message.urlDecode());
        }

        if (oRetorno.token) {
            $("db48_tokenpublico").value = oRetorno.token;
        }

    }

    function js_pesquisadb48_idacesso(lExibeJanela) {

        if (lExibeJanela) {
            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                'db_iframe_db_sysregrasacesso',
                'func_db_sysregrasacesso.php?funcao_js=parent.js_mostradb_sysregrasacesso1|db46_idacesso|db46_observ',
                'Pesquisa', true);
        } else {
            if (document.form1.db48_idacesso.value != '') {
                js_OpenJanelaIframe( 'CurrentWindow.corpo',
                    'db_iframe_db_sysregrasacesso',
                    'func_db_sysregrasacesso.php?pesquisa_chave=' + document.form1.db48_idacesso.value + '&funcao_js=parent.js_mostradb_sysregrasacesso',
                    'Pesquisa', false);
            } else {
                document.form1.db46_observ.value = '';
            }
        }
    }

    function js_mostradb_sysregrasacesso(sChave, lErro) {

        document.form1.db46_observ.value = sChave;
        if (lErro) {

            document.form1.db48_idacesso.focus();
            document.form1.db48_idacesso.value = '';
        }
    }

    function js_mostradb_sysregrasacesso1(sChave, sDescricao) {

        document.form1.db48_idacesso.value = sChave;
        document.form1.db46_observ.value = sDescricao;
        db_iframe_db_sysregrasacesso.hide();
    }

    function js_pesquisa() {
        js_OpenJanelaIframe( 'CurrentWindow.corpo',
            'db_iframe_db_sysregrasacessoip',
            'func_db_sysregrasacessoip.php?funcao_js=parent.js_preenchepesquisa|db48_idacesso',
            'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

        db_iframe_db_sysregrasacessoip.hide();
        <?php
        if ($db_opcao != 1) {
            echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
        ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
</script>
</html>
