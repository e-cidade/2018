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

/**
 * Busca os exercícios para impressão do relatório
 */
$oDaoEmpresto   = new cl_empresto();
$sSqlExercicios = $oDaoEmpresto->sql_query_empenho( db_getsession("DB_anousu"),
                                                    null,
                                                    'distinct e60_anousu',
                                                    'e60_anousu desc' );
$rsExercicios   = $oDaoEmpresto->sql_record( $sSqlExercicios );

$aExercicios = array();

if ($rsExercicios && $oDaoEmpresto->numrows > 0) {

  for ($iRow = 0; $iRow < $oDaoEmpresto->numrows; $iRow++) {

    $iExercicio = db_utils::fieldsMemory($rsExercicios, $iRow)->e60_anousu;
    $aExercicios[$iExercicio] = $iExercicio;
  }
}

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
      <form id="restosapagar" name="form1" method="post" action="">
        <fieldset>
          <legend>Modelo 5 - Relação de Restos a Pagar</legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="exercicio" id="lbl_exercicio">Exercício:</label>
              </td>
              <td>
                <?php db_select('exercicio', $aExercicios, true, 1); ?>
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="tipo" id="lbl_tipo">Tipo:</label>
              </td>
              <td>
                <?php

                  $aOpcoes = array(
                      RelatorioRelacaoRestosPagar::RESTOS_PROCESSADOS => 'Processados',
                      RelatorioRelacaoRestosPagar::RESTOS_NAO_PROCESSADOS => 'Não Processados'
                    );

                  db_select('tipo', $aOpcoes, true, 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="gerar" type="submit" id="gerar" value="Gerar" />
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script type="text/javascript">

    const MENSAGENS = "financeiro.contabilidade.con2_deliberacao20096restosapagar001.";

    Event.observe('restosapagar', 'submit', function(e) {
      e.stop();

      var iExercicio = $F('exercicio'),
          iTipo = $F('tipo');

      if (iExercicio == '') {

        alert( _M(MENSAGENS + "campo_obrigatorio", { sCampo : "Exercício" }) );
        return false;
      }

      oJanela = window.open( "con2_deliberacao20096restosapagar002.php?"
                             + "exercicio=" + iExercicio
                             + "&tipo=" + iTipo,
                             '',
                             'width=' + (screen.availWidth-5) + ', height=' + (screen.availHeight-40) + ', scrollbars=1, location=0 ');
      oJanela.moveTo(0,0);
    });

  </script>
</html>