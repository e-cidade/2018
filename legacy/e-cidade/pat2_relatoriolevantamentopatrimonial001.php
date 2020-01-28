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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("p13_departamento");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

  </head>

  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

    <div class="container">
      <form id="frmLevantamentoPatrimonial" method="post" action="">
        <table>
          <tr>
            <td align="center">
              <fieldset>
                <legend>Levantamento Patrimonial</legend>
                <table>
                  <tr>
                    <td>
                      <label class="bold" for="p13_departamento">
                        <a id="buscarDepartamento"><?=$Lp13_departamento?></a>
                      </label>
                    </td>
                    <td>
                      <?php
                      db_input("p13_departamento", 10, 0, true, 'text', 1);
                      db_input("descrdepto", 20, 0, true, 'text');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td><label class="bold" for="situacao">Situação:</label></td>
                    <td>
                      <?php
                      $aSituacoes = array(0                                                             => "Todos",
                                          RelatorioLevantamentoPatrimonial::SITUACAO_NAO_ENCONTRADO_TXT => "Não encontrado no txt",
                                          RelatorioLevantamentoPatrimonial::SITUACAO_NAO_CADASTRADO     => "Não cadastrado",
                                          RelatorioLevantamentoPatrimonial::SITUACAO_INCONSISTENTE      => "Inconsistente",
                                          RelatorioLevantamentoPatrimonial::SITUACAO_CONSISTENTE        => "Consistente",
                                          RelatorioLevantamentoPatrimonial::SITUACAO_BAIXADO_NO_TXT     => "Bem Baixado");
                      db_select("situacao", $aSituacoes, true, 1);
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <input type="button" id="btnEmitir" name="btnEmitir" value="Emitir" onclick="js_emitir()">
            </td>
          </tr>
        </table>
      </form>
      <script>
        const URL_RELATORIO          = "pat2_relatoriolevantamentopatrimonial002.php";
        var   oCodigoDepartamento    = $('p13_departamento');
        var   oDescricaoDepartamento = $('descrdepto');
        var   oAncoraDepartamento    = $('buscarDepartamento');
        var   oSituacao              = $('situacao');
        var   oBtnEmitir             = $('btnEmitir');
        var   oForm                  = $('frmLevantamentoPatrimonial');

        oForm.reset();

        oLookupDepartamento = new DBLookUp(oAncoraDepartamento, oCodigoDepartamento, oDescricaoDepartamento, {
            "sArquivo"              : "func_levantamentopatrimonial.php",
            "sObjetoLookUp"         : "db_iframe_levantamentopatrimonial",
            "sLabel"                : "Pesquisar Departamento"
          });

        function js_emitir() {

          if (empty(oCodigoDepartamento.value)) {

            alert("Campo Departamento é de preenchimento obrigatório.");
            return false;
          }
          
          var sQuery = '?';
          sQuery += 'iDepartamento=' + oCodigoDepartamento.value;
          if (!empty(oSituacao.value) && oSituacao.value != 0) {
            sQuery += '&iSituacao='   + oSituacao.value;
          }

          var iHeight = (screen.availHeight - 40);
          var iWidth  = (screen.availWidth - 5);
          var sOpcoes = 'width=' + iWidth + ',height=' + iHeight + ',scrollbars=1,location=0';
          var oJanela = window.open(URL_RELATORIO + sQuery, '', sOpcoes);
          oJanela.moveTo(0, 0);
        }
      </script>
      <?php db_menu(); ?>
    </div>
  </body>
</html>