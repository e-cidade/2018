<?
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
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));
require_once(modification("classes/db_pagordem_classe.php"));
require_once(modification("classes/db_pagordemele_classe.php"));
require_once(modification("classes/db_pagordemnota_classe.php"));
require_once(modification("classes/db_pagordemval_classe.php"));
require_once(modification("classes/db_pagordemrec_classe.php"));
require_once(modification("classes/db_pagordemtiporec_classe.php"));
require_once(modification("classes/db_empnota_classe.php"));
require_once(modification("classes/db_empnotaele_classe.php"));
require_once(modification("classes/db_tabrec_classe.php"));
require_once(modification("classes/db_conplanoreduz_classe.php"));
require_once(modification("classes/db_conlancam_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_conlancamele_classe.php"));
require_once(modification("classes/db_conlancamnota_classe.php"));
require_once(modification("classes/db_conlancamcgm_classe.php"));
require_once(modification("classes/db_conlancamdot_classe.php"));
require_once(modification("classes/db_conlancamval_classe.php"));
require_once(modification("classes/db_conlancamlr_classe.php"));
require_once(modification("classes/db_conlancamcompl_classe.php"));
require_once(modification("classes/db_conlancamord_classe.php"));
require_once(modification("classes/empenho.php"));

$clempnota         = new cl_empnota;
$clempnotaele      = new cl_empnotaele;
$clpagordem        = new cl_pagordem;
$clpagordemele     = new cl_pagordemele;
$clpagordemnota    = new cl_pagordemnota;
$clpagordemval     = new cl_pagordemval;
$clpagordemrec     = new cl_pagordemrec;
$clempempenho      = new cl_empempenho;
$clempelemento     = new cl_empelemento;
$clorcdotacao      = new cl_orcdotacao;
$clorctiporec      = new cl_orctiporec;
$cltabrec          = new cl_tabrec;
$clconplanoreduz   = new cl_conplanoreduz;
$cltranslan        = new cl_translan;
$clconlancam       = new cl_conlancam;
$clconlancamemp    = new cl_conlancamemp;
$clconlancamdoc    = new cl_conlancamdoc;
$clconlancamele    = new cl_conlancamele;
$clconlancamnota   = new cl_conlancamnota;
$clconlancamcgm    = new cl_conlancamcgm;
$clconlancamdot    = new cl_conlancamdot;
$clconlancamval    = new cl_conlancamval;
$clconlancamlr     = new cl_conlancamlr;
$clconlancamcompl  = new cl_conlancamcompl;
$clconlancamord    = new cl_conlancamord;
$clpagordemtiporec = new cl_pagordemtiporec;
$clempenho         = new empenho; // rotinas para liquidação de empenho

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$op           = 1;
$db_opcao     = 22;
$db_botao     = false;
$tela_estorno = false;
if(isset($numemp)){

  $db_opcao = 1;
  $db_botao = true;
  //  echo "<br>{$numemp}";
}
?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js, dbmessageBoard.widget.js");
    db_app::load("classes/DBViewNotasPendentes.classe.js, widgets/windowAux.widget.js, datagrid.widget.js, AjaxRequest.js");
    ?>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  <body class="container" style="margin-top:30px;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <?
        include(modification("forms/db_frmliquida.php"));
        ?>
      </td>
    </tr>
  </table>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
  </html>
<?

if(isset($confirmar_emitir)){
  if($sqlerro==false|| isset($codop) && $codop!=""){
    echo "<script>
              js_emitir(".$codop.");
           </script>";
  }
}
if ($db_opcao==22) {
  echo "<script>document.form1.pesquisar.click();</script>";
} else {
  echo "<script>js_pesquisa({$numemp});</script>";
}
?>