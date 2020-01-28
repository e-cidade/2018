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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php

      db_app::load("scripts.js, strings.js, prototype.js, DBViewCalculoIssqn.js, dbcomboBox.widget.js");
      db_app::load("dbtextField.widget.js, windowAux.widget.js, widgets/dbtextFieldData.widget.js");
      db_app::load("estilos.css, grid.style.css, datagrid.widget.js");
    ?>
  </head>
<body class="body-default">
  <div class="container">
    <form name="form1" id="form1">
      <fieldset class="form-container">
        <legend>Cálculo Individual ISSQN</legend>
        <table>
          <tr>
            <td title="<?php echo $Tq02_inscr; ?>">
              <?php
                db_ancora($Lq02_inscr, "js_pesquisaInscricao(true)", 1);
              ?>
            </td>
            <td>
            <?php

              db_input("q02_inscr", 10, $Iq02_inscr, true, "text", 1, "onchange='js_pesquisaInscricao(false)'");
              db_input("z01_nome" , 40, $Iz01_nome,  true, "text", 3);
            ?>
            </td>
          </tr>
        </table>
      </fieldset>

    </form>
    <div id="divCalculoAlvara" style="text-align: center;"></div>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</form>
</body>
</html>
<script type="text/javascript">

  function js_pesquisaInscricao(lMostra) {

    iInscricao = document.form1.q02_inscr.value;
    if (lMostra) {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscr','func_issbase.php?funcao_js=parent.js_mostraInscricao|q02_inscr|z01_nome&calculo=sim','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_inscr','func_issbase.php?pesquisa_chave='+iInscricao+'&funcao_js=parent.js_mostraInscricaoHide&calculo=sim','Pesquisa',false);
    }
  }

  function js_mostraInscricaoHide(sNome, lErro) {

    if (lErro == false) {

      $('z01_nome').setValue(sNome);
      js_carregaDados(iInscricao);
    }
  }

  function js_mostraInscricao(iInscricao, sNome) {

    $('q02_inscr').setValue(iInscricao);
    $('z01_nome') .setValue(sNome);
    db_iframe_inscr.hide();
    js_carregaDados(iInscricao);
  }

  function js_carregaDados(iInscricao) {

    if ( typeof(oCalculoAlvara) != "undefined" ) {
      delete(oCalculoAlvara);
    }
    $('divCalculoAlvara').innerHTML = "";
    oCalculoAlvara = new DBViewCalculoIssqn('oCalculoAlvara', 'calculoAlvara');
    oCalculoAlvara.setInscricao(iInscricao);
    oCalculoAlvara.show($('divCalculoAlvara'));
  }
</script>