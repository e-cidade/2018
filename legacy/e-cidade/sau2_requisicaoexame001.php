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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label("z01_v_nome");
$oRotulo->label("sd24_i_codigo");
$oRotulo->label("sd24_i_numcgs");

?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class='body-default'>

  <div class="container">
    <fieldset>

      <legend>Requisição de Exames</legend>
      <form  name='form1'>
        <table class="form-container">
          <tr>
            <td>
              <?php
                db_ancora("$Lsd24_i_codigo", "js_pesquisaFaa(true)", 1);
              ?>
            </td>
            <td>
              <?php
                db_input("sd24_i_codigo",10, $Isd24_i_codigo, true, "text", 1, " onchange= 'js_pesquisaFaa(false);'");
                db_input("z01_v_nome",   40, $Iz01_v_nome,    true, "text", 3, "");
                db_input("sd24_i_numcgs",10, $Isd24_i_numcgs, true, "hidden", 3);
              ?>
            </td>
          </tr>

        </table>
      </form>
    </fieldset>
    <input name="imprimir" type="button" id="imprimir" value="Imprimir" onclick="imprimir();" >
  </div>


<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script type="text/javascript">

const MSG_SAUCONSULTAFAA = "saude.ambulatorial.sau2_requisicaoexame.";

$('imprimir').disabled = true;

function js_pesquisaFaa(lMostra) {

  var sUrl = 'func_fichaatendimento.php?';
  if( lMostra) {

    sUrl += 'funcao_js=parent.js_retornoPesquisa|sd24_i_codigo|z01_v_nome|sd24_i_numcgs';
    js_OpenJanelaIframe( '', 'db_iframe_fichaatendimento', sUrl, 'Pesquisa FAA', true);
  } else if ( $F('sd24_i_codigo') != '' ) {

    sUrl += 'funcao_js=parent.js_retornoPesquisa';
    sUrl += '&pesquisa_chave=' + $F('sd24_i_codigo');
    js_OpenJanelaIframe( '', 'db_iframe_fichaatendimento', sUrl, 'Pesquisa FAA', false);
  } else {

    $('sd24_i_codigo').value = '';
    $('z01_v_nome').value    = '';
  }
}

function js_retornoPesquisa() {

  $('imprimir').disabled = true;
  if( typeof arguments[1] == "boolean" ) {

      $('z01_v_nome').value    = arguments[0];
      $('sd24_i_numcgs').value = arguments[2];
      if( arguments[1] ) {
        $('sd24_i_codigo').value = "";
        return;
      }
  } else {

    $('sd24_i_codigo').value = arguments[0];
    $('z01_v_nome').value    = arguments[1];
    $('sd24_i_numcgs').value = arguments[2];
  }
  $('imprimir').disabled = false;
  db_iframe_fichaatendimento.hide();
}

function imprimir() {

  if ( $F('sd24_i_codigo') == '' ) {

    alert( _M( MSG_REQUISICAOEXAME + "informe_faa" ) );
    return;
  }
  var sUrl = "sau2_requisicaoexame004.php?iProntuario=" + $F('sd24_i_codigo');
  var oJanela = window.open(sUrl,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);
}

</script>
</html>