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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();
$oRotulo->label('bi06_seq');
$oRotulo->label('bi06_titulo');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class='body-default'>
<?php
  MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");
  db_menu();
?>
  <div class='container'>
  <form name="form1" >
    <fieldset>
      <legend>Consulta Acervo</legend>

      <table>
        <tr>
          <td>
            <label for="bi06_seq">
              <?php
                db_ancora("Acervo:", 'pesquisaAcervo(true)', 1, "");
              ?>
            </label>
          </td>
          <td>
            <?php
              db_input('bi06_seq',     10, $Ibi06_seq,    true, 'text', 1, "onchange='pesquisaAcervo(false);'");
              db_input('bi06_titulo',  40, $Ibi06_titulo, true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="pesquisa" value="Pesquisar" id='pesquisa'>
  </form>
  </div>

</body>
</html>
<script>

$('pesquisa').disabled = true;

function pesquisaAcervo(lMostra) {

   var sUrl = "func_acervo.php";

  if ( lMostra ) {

    sUrl += '?funcao_js=parent.js_preenchePesquisa|bi06_seq|bi06_titulo'
    js_OpenJanelaIframe('', 'db_iframe_acervo', sUrl, 'Pesquisa Aluno', true);

  } else if( $F('bi06_seq') != '' ) {

    sUrl += '?funcao_js=parent.js_preenchePesquisa2';
    sUrl += '&pesquisa_chave=' + $F('bi06_seq');
    js_OpenJanelaIframe('', 'db_iframe_acervo', sUrl, 'Pesquisa Aluno', false);

  } else {

    $('bi06_seq').value    = '';
    $('bi06_titulo').value = '';
    $('pesquisa').disabled = true;
  }

}

function js_preenchePesquisa( iAcervo, sTitulo ) {

  db_iframe_acervo.hide();

  $('bi06_seq').value    = iAcervo;
  $('bi06_titulo').value = sTitulo;
  $('pesquisa').disabled = false;
}


function js_preenchePesquisa2( sTitulo, lErro ) {

  db_iframe_acervo.hide();
  $('bi06_titulo').value = sTitulo;
  $('pesquisa').disabled = false;

  if ( lErro ) {

    $('bi06_seq').value    = '';
    $('pesquisa').disabled = true;
  }
}

$('pesquisa').observe( 'click', function(){

  if ( $F('bi06_seq') == '' ) {

    alert( _M( EDU3_CONSULTAALUNO + "informe_aluno" ) );
    return;
  }
  var sJanela = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
  var sUrl    = "bib3_consultaacervo001.php?iAcervo=" + $F('bi06_seq') + '&sTitulo='+$F('bi06_titulo');


  var iTop    = 20;
  var iLeft   = 5;
  var iHeight = screen.availHeight-210;
  var iWidth  = screen.availWidth-35;

  var sNomeJanela = 'Consulta Acervo';
  js_OpenJanelaIframe('', 'db_iframe_consulta', sUrl, sNomeJanela, true, iTop, iLeft, iWidth, iHeight);
});
</script>
