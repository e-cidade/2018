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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

$oGet = db_utils::postMemory($_GET);
$lModelo2 = (!empty($oGet->modelo) && $oGet->modelo == 2);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="adiantamentos" id="adiantamentos" method="post" action="">
        <fieldset>
          <legend>Demonstrativo <?php echo $lModelo2 ? "dos Adiantamentos Concedidos" : "das Subvenções e Auxílios"; ?></legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="data_inicial" id="lbl_data_inicial">Período de:</label>
              </td>
              <td>
                <?php db_inputdata('data_inicial', '', '', '', true, 'text', 1); ?>
                <label class="bold" for="data_final" id="lbl_data_final">até</label>
                <?php db_inputdata('data_final', '', '', '', true, 'text', 1); ?>
              </td>
            </tr>
            <?php if (!$lModelo2) { ?>
              <tr>
                <td>
                  <label class="bold" for="data_remessa" id="lbl_data_remessa">Data da Remessa:</label>
                </td>
                <td>
                  <?php
                  db_inputdata('data_remessa', date('d', db_getsession('DB_datausu')), date('m', db_getsession('DB_datausu')), date('Y', db_getsession('DB_datausu')), true, 'text', 1);
                  ?>
                </td>
              </tr>
            <?php } ?>
          </table>
        </fieldset>
        <input name="gerar" type="submit" id="gerar" value="Gerar" />
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
    <script type="text/javascript">
      (function() {

        const MENSAGENS = "financeiro.empenho.emp2_deliberacao20096adiantamentos.";

        Event.observe('adiantamentos', 'submit', function(e) {
          e.stop();

          var sDataInicio = $F('data_inicial'),
              sDataFim    = $F('data_final'),
              oGet        = js_urlToObject();

          if (empty(sDataInicio)) {
            alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Data Inicial"}) );
            return false;
          }

          if (empty(sDataFim)) {
            alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Data Final"}) );
            return false;
          }

          if (sDataInicio.split(/(\d{4})$/)[1] != sDataFim.split(/(\d{4})$/)[1]) {
            alert( _M(MENSAGENS + "periodo_ano") );
            return false;
          }

          var sParametros = "data_inicio=" + encodeURIComponent(sDataInicio) + "&data_fim=" + encodeURIComponent(sDataFim);

          if (oGet.modelo == 3) {

            var sDataRemessa = $F('data_remessa');

            if (empty(sDataRemessa)) {
              alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Data da Remessa"}) );
              return false;
            }

            sParametros += "&data_remessa=" + encodeURIComponent(sDataRemessa);
          }

          sParametros += "&modelo=" + oGet.modelo;

          oJanela = window.open( "emp2_deliberacao20096adiantamentos002.php?" + sParametros,
                                 '',
                                 'width=' + (screen.availWidth-5) + ',height=' + (screen.availHeight-40) + ',scrollbars=1,location=0 ');
          oJanela.moveTo(0,0);

          return false;
        });
      })();
    </script>
  </body>
</html>