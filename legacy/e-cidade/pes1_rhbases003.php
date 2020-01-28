<?
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

  require(modification("libs/db_stdlib.php"));
  require(modification("libs/db_conecta.php"));
  include(modification("libs/db_sessoes.php"));
  include(modification("libs/db_usuariosonline.php"));
  include(modification("classes/db_bases_classe.php"));
  include(modification("classes/db_basesr_classe.php"));
  include(modification("dbforms/db_funcoes.php"));

  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  db_postmemory($HTTP_POST_VARS);

  $clbases  = new cl_bases;
  $clbasesr = new cl_basesr;
  $db_botao = false;
  $db_opcao = 33;
  $anousu = db_anofolha();
  $mesusu = db_mesfolha();

  if (isset($excluir)) {

    db_inicio_transacao();

    $db_opcao = 3;
    $sqlerro  = false;

    $clbasesr->excluir($anousu, $mesusu, $r08_codigo, null, db_getsession("DB_instit"));

    if ($clbasesr->erro_status == 0) {
      $erro_msg = $clbasesr->erro_msg;
      $sqlerro  = true;
      $db_botao = true;
    }

    if ($sqlerro == false) {

      $clbases->excluir($anousu, $mesusu, $r08_codigo, db_getsession("DB_instit"));

      if ($clbases->erro_status == 0) {
        $erro_msg = $clbases->erro_msg;
        $sqlerro  = true;
        $db_botao = true;
      }
    }

    // <!-- ContratosPADRS: tipo de base excluir -->
      if (!$sqlerro && !empty($tipobase)) {
          $oDaoTipoBaseVinculo = new cl_padrstipobasevinculo();

          if (!$sqlerro) {
              $oDaoTipoBaseVinculo->excluir(null, "base_codigo = '{$r08_codigo}' AND base_ano = {$anousu} AND base_mes = {$mesusu} AND base_instit = ".db_getsession("DB_instit"));
              if ($oDaoTipoBaseVinculo->erro_status == "0") {
                  $sqlerro = true;
                  $erro_msg = "Não foi possível excluir o tipo da base.";
              }
          }
      }

    db_fim_transacao($sqlerro);

  } else if(isset($chavepesquisa)) {

    $db_opcao = 3;
    $result   = $clbases->sql_record($clbases->sql_query($anousu, $mesusu, $chavepesquisa, db_getsession("DB_instit")));

    db_fieldsmemory($result, 0);

    $db_botao = true;
  }
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
          <td width="140">&nbsp;</td>
      </tr>
    </table>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
          <center>
	          <?
	            include(modification("forms/db_frmrhbases.php"));
	          ?>
          </center>
	      </td>
      </tr>
    </table>
    <?
      db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
              db_getsession("DB_anousu")    , db_getsession("DB_instit"));
    ?>
  </body>
</html>
<?
  if (isset($excluir)) {
    if ($clbases->erro_status == "0") {
      $clbases->erro(true, false);
    } else {
      $clbases->erro(true, true);
    }
  }

  if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
?>
