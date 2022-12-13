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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_medicos_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoMedicos            = new cl_medicos;
$oDaoSauMedicosForaRede = db_utils::getdao('sau_medicosforarede');
$oDaoCgm                = new cl_cgm;
$db_opcao               = 22;
$db_botao               = false;

if (isset($alterar)) {

  db_inicio_transacao();

  $db_opcao = 2;
  $sSql = $oDaoMedicos->sql_query_file("","*","","sd03_i_cgm = $sd03_i_cgm and sd03_i_codigo <> $sd03_i_codigo");
  $oDaoMedicos->sql_record($sSql);
  if ($oDaoMedicos->numrows == 0) {
    $oDaoMedicos->alterar($sd03_i_codigo);
  } else {

    $oDaoMedicos->erro_msg    = ' O CGM escolhido ja foi utilizado! ';
    $oDaoMedicos->erro_status = '0';

  }
  db_fim_transacao($oDaoMedicos->erro_status == '0' ? true : false);

} elseif (isset($chavepesquisa)) {

  $db_opcao = 2;
  $sSql     = $oDaoMedicos->sql_query_cgm_fora_rede(null, '*', '', ' sd03_i_codigo = '.$chavepesquisa);
  $rs       = $oDaoMedicos->sql_record($sSql);
  db_fieldsmemory($rs, 0);
  $db_botao = true;

  if ($sd03_i_tipo == 1) { // Somente libero as outras abas para medicos da rede interna
?>
    <script>

      parent.document.formaba.a2.disabled = false;
      parent.document.formaba.a3.disabled = false;
      parent.document.formaba.a4.disabled = false;
      parent.document.formaba.a5.disabled = false;
      parent.document.formaba.a6.disabled = false;
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href   = 'pro1_cgmdoc001.php?z02_i_cgm=<?=$sd03_i_cgm?>&z01_nome=<?=$z01_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href   = 'sau1_unidademedicos001.php?sd04_i_medico=<?=$sd03_i_codigo?>'+
                                            '&z01_nome=<?=$z01_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href   = 'sau1_undmedhorario006.php?sd04_i_medico=<?=$sd03_i_codigo?>'+
                                            '&z01_nome=<?=$z01_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href   = 'sau1_undmedhorario007.php?sd04_i_medico=<?=$sd03_i_codigo?>'+
                                            '&z01_nome=<?=$z01_nome?>';
      (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href   = 'sau1_sau_procedmedagendamento001.php?sd04_i_medico=<?=$sd03_i_codigo?>'+
                                            '&z01_nome=<?=$z01_nome?>';
    </script>
<?
  } else {
?>
    <script>
      parent.document.formaba.a2.disabled = true;
      parent.document.formaba.a3.disabled = true;
      parent.document.formaba.a4.disabled = true;
      parent.document.formaba.a5.disabled = true;
      parent.document.formaba.a6.disabled = true;
    </script>
<?
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
      <br>
      <center>
        <?
        require_once(modification("forms/db_frmmedicos.php"));
        ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (isset($alterar)) {

  if ($oDaoMedicos->erro_status == '0') {

    $oDaoMedicos->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoMedicos->erro_campo != '') {

      echo "<script> document.form1.".$oDaoMedicos->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoMedicos->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoMedicos->erro(true, false);
    $sGet = '';
    if (isset($lBotao) && $lBotao == 'true') {
      $sGet = '&lBotao=true';
    }
    db_redireciona("sau1_medicos002.php?chavepesquisa=$sd03_i_codigo$sGet");

  }

}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1", "sd03_i_cgm", true, 1, "sd03_i_cgm", true);
</script>