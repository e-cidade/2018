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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");


$oRotulo = new rotulocampo();
$oRotulo->label('ed47_i_codigo');
$oRotulo->label('ed47_v_nome');




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
  <div class='container'>
  <form name="form1" >
    <fieldset>
      <legend>Consulta Aluno</legend>

      <table>
        <tr>
          <td>
            <?php
              db_ancora("Aluno", 'pesquisaAluno(true)', 1, "");
            ?>
          </td>
          <td>
            <?php
              db_input('ed47_i_codigo',10, $Ied47_i_codigo, true, 'text', 1, "onchange='pesquisaAluno(false);'");
              db_input('ed47_v_nome',  40, $Ied47_v_nome,   true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" name="pesquisa" value="Pesquisar" id='pesquisa'>
  </form>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

const EDU3_CONSULTAALUNO = 'educacao.escola.edu3_consultaaluno.';
$('pesquisa').disabled   = true;

function pesquisaAluno( lMostra ) {

  var sUrl = "func_pesquisaaluno.php";

  if ( lMostra ) {

    sUrl += '?funcao_js=parent.retornoAluno|ed47_i_codigo|ed47_v_nome'
    js_OpenJanelaIframe('', 'db_iframe_pesquisaaluno', sUrl, 'Pesquisa Aluno', true);

  } else if( $F('ed47_i_codigo') != '' ) {

    sUrl += '?funcao_js=parent.retornoAluno';
    sUrl += '&pesquisa_chave=' + $F('ed47_i_codigo');
    js_OpenJanelaIframe('', 'db_iframe_pesquisaaluno', sUrl, 'Pesquisa Aluno', false);

  } else {

    $('ed47_i_codigo').value = '';
    $('ed47_v_nome').value   = '';
    $('pesquisa').disabled  = true;
  }
};

function retornoAluno () {

  if ( typeof(arguments[1]) == 'boolean' ) {

    $('pesquisa').disabled = false;
    $('ed47_v_nome').value  = arguments[0];
    if ( arguments[1] ) {

      $('ed47_i_codigo').value = '';
      $('pesquisa').disabled  = true;
    }
  } else {

    $('ed47_i_codigo').value = arguments[0];
    $('ed47_v_nome').value   = arguments[1];
    $('pesquisa').disabled  = false;
    db_iframe_pesquisaaluno.hide();
  }

}

$('pesquisa').observe( 'click', function(){

  if ( $F('ed47_i_codigo') == '' ) {

    alert( _M( EDU3_CONSULTAALUNO + "informe_aluno" ) );
    return;
  }
  var sJanela = 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ';
  var sUrl    = "edu3_consultaaluno002.php?iAluno=" + $F('ed47_i_codigo') + '&sNome='+$F('ed47_v_nome');


  var iTop    = 20;
  var iLeft   = 5;
  var iHeight = screen.availHeight-210;
  var iWidth  = screen.availWidth-35;

  var sNomeJanela = 'Consulta Aluno - Progressão Parcial';
  js_OpenJanelaIframe('', 'db_iframe_consulta', sUrl, sNomeJanela, true, iTop, iLeft, iWidth, iHeight);
});

</script>