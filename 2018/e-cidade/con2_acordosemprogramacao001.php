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

require_once(modification("libs/db_stdlib.php"));
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
    <?php
        db_app::load("scripts.js, strings.js, prototype.js");
        db_app::load("estilos.css");
    ?>
    <script src="scripts/widgets/Input/DBInput.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputDate.widget.js" type="text/javascript"></script>
    </head>
    <body >
        <form class="container">
            <fieldset style="width: 300px;" >
                <legend>Contratos sem Programação de Competência</legend>
                <table class="form-container">
                    <tr>
                        <td class="field-size2" > <label for="contratos">Contratos:</label> </td>
                        <td  consplan="3">
                            <?php db_select('contratos', array('1'=>'Vigentes', '2'=>'Todos', '3'=> 'Período'), true, 1); ?>
                        </td>

                    </tr>
                    <tr style="display: none;" id="liDate">
                        <td><label for="dtInicio">Vigência:</label></td>
                        <td><input name="inicio" id="dtInicio"/></td>
                        <td><label for="dtFim">até:</label></td>
                        <td><input name="fim" id="dtFim"/></td>
                    </tr>
              </table>
            </fieldset>
            <input id="btnImprimir" name="imprimir" type="button" value="Imprimir">
        </form>
        <?php
            db_menu();
        ?>
    </body>
    <script type="text/javascript">

    new DBInputDate($('dtInicio'));
    new DBInputDate($('dtFim'));

    $('contratos').addEventListener('change', function($element) {

        if (this.value == 3) {
            $('liDate').style.display = 'table-row';
        } else {
            $('liDate').style.display = 'none';
        }
    });

    $('btnImprimir').addEventListener('click', function() {

        var dtInicio =  $('dtInicio').value ? $('dtInicio').value : '';
        var dtFim = $('dtFim').value ? $('dtFim').value : '';

        var sArquivoRelatorio = "con2_acordosemprogramacao002.php?tipoVigencia=" + $F('contratos')
        sArquivoRelatorio += "&dtInicio=" + $F('dtInicio') + "&dtFim=" + $F('dtFim');

        jan = window.open(sArquivoRelatorio , '', 'scrollbars=1, location=0');
        jan.moveTo(0,0);
    });
    </script>
</html>