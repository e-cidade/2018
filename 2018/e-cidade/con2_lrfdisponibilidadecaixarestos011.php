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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_liborcamento.php");

$anoSessao = db_getsession('DB_anousu');
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <meta http-equiv="Expires" CONTENT="0"/>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css"/>
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css"/>
</head>
<body class="body-default">
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend>Demonstrativo da Disponibilidade de Caixa e dos Restos a Pagar</legend>
            <table>
                <tr>
                    <td colspan="2" id="lista-instituicao">

                    </td>
                </tr>
                <tr>
                    <td nowrap width="1%">
                        <label for="o116_periodo" class="bold">Período:</label>
                    </td>
                    <td>
                        <?php

                        $oRelatorio = new relatorioContabil($anoSessao >= 2017 ? 174 : 155);

                        $aPeriodos         = $oRelatorio->getPeriodos();
                        $aListaPeriodos    = array();
                        $aListaPeriodos[0] = "Selecione";

                        foreach ($aPeriodos as $oPeriodo) {
                            $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
                        }

                        db_select("o116_periodo", $aListaPeriodos, true, 1);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="imprimir" id="imprimir" type="button" value="Imprimir"/>
    </form>
</div>
<script type="text/javascript">


    var anoSessao = <?php echo $anoSessao; ?>;

  oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('lista-instituicao'));
  oViewInstituicao.show();

  $("imprimir").observe("click", function() {


    var oInstituicoes = oViewInstituicao.getInstituicoesSelecionadas().map(function(oItem) {
      return oItem.codigo;
    });

    if (oInstituicoes.length == 0) {
      return alert('Selecione ao menos uma Instituição.');
    }

    if ($F('o116_periodo') == 0) {
      return alert("Campo Período é de preenchimento obrigatório.");
    }

    var sGetParam = "con2_lrfdisponibilidadecaixarestos002.php?instituicoes=" + oInstituicoes.join(',') + '&periodo=' + $F('o116_periodo');
    if (Number(anoSessao) >= 2017) {

      validarRecursos();

    } else {

      var jan = window.open(sGetParam,
        '',
        'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
      jan.moveTo(0, 0);
    }
  });


  function validarRecursos()
  {
    var oInstituicoes = oViewInstituicao.getInstituicoesSelecionadas().map(function(oItem) {
      return oItem.codigo;
    });

    var sGetParam;
    AjaxRequest.create(
      'con2_relatoriosdcasp.RPC.php',
      {
        'exec' : 'verificarRecursos',
        'iCodigoRelatorio' : 174,
        'iCodigoPeriodo' : 13,
        'aCodigosInstituicao' : oViewInstituicao.getInstituicoesSelecionadas(true),
        'imprimirValorExercicioAnterior' : 'f'
      },
      function (retorno, erro) {

        var mensagem = "Existem recursos com movimentação que não estão configurados neste relatório, desta forma os valores estarão inconsistentes. Deseja emitir a lista dos recursos não configurados?";
        if (retorno.lEmiteLista && confirm( mensagem ) ) {

          var oDownload = new DBDownload();
          oDownload.addFile( retorno.sArquivo, 'Recursos não configurados' );
          oDownload.show();
        }

        if (retorno.lEmiteLista && !confirm( "Deseja emitir o relatório?" )) {
          return false;
        }

        sGetParam = "con2_lrfdisponibilidadecaixarestos002_2017.php?instituicoes=" + oInstituicoes.join(',') + '&periodo=' + $F('o116_periodo');
        var jan = window.open(sGetParam,
          '',
        'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
        jan.moveTo(0, 0);
      }
    ).execute();
  }
</script>
</body>
</html>