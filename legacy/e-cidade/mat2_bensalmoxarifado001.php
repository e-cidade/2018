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
    <div class="container">
      <form name="bensalmoxarifado" id="bensalmoxarifado" method="post">

        <fieldset>
          <legend>Modelo XXI - Bens em Almoxarifado - Demonstrativo Mensal das Operações</legend>
          <table>
            <tr>
              <td>
                <label for="ano" id="lbl_ano" class="bold">Ano/Mês:</label>
              </td>
              <td>
                <?php
                  $ano = date('Y');
                  $Sano = "Ano";
                  db_input('ano', 4, 1, true, 'text', 1, '', '', '', '', 4);
                ?>
                &nbsp;/&nbsp;
                <?php
                  $mes = date('m');
                  $Smes = "Mês";
                  db_input('mes', 2, 1, true, 'text', 1, '', '', '', '', 2);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="tipo_material" id="lbl_tipo_material">Tipo de Material:</label>
              </td>
              <td>
                <?php
                  $aOpcoes = array(
                      TipoGrupo::MATERIAL_CONSUMO => 'Consumo',
                      TipoGrupo::BEM_PERMANENTE => 'Permanente'
                    );
                  db_select('tipo_material', $aOpcoes, true, 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="gerar" type="submit" value="Gerar" />
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script type="text/javascript">

    const MENSAGENS = "patrimonial.material.mat2_bensalmoxarifado.";

    Event.observe('bensalmoxarifado', 'submit', function(e) {
      e.stop();

      var iMes = $F('mes'),
          iAno = $F('ano'),
          iTipo = $F('tipo_material');

      if (iAno.length < 4 || +iAno == 0) {
        alert( _M(MENSAGENS + "ano_invalido") );
        return false;
      };

      if (iMes < 1 || iMes > 12) {
        alert( _M(MENSAGENS + "mes_invalido") );
        return false;
      };

      if (iMes == '') {
        alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Mês"}) );
        return false;
      }

      if (iAno == '') {
        alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Ano"}) );
        return false;
      }

      oJanela = window.open( "mat2_bensalmoxarifado002.php?ano=" + iAno + "&mes=" + iMes + "&tipo=" + iTipo,
                             '',
                             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);

      return false;
    })

  </script>
</html>