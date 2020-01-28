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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('classes/db_lab_controlefisicofinanceiro_classe.php'));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('dbforms/db_classesgenericas.php'));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oDaoLabControleFisicoFinanceiro = new cl_lab_controlefisicofinanceiro;
$db_opcao                        = 1;
$db_opcao2                       = 2;
$db_botao                        = false;
$dHoje                           = date("Y-m-d",db_getsession("DB_datausu"));
if (!isset($iOperacao)) {
  $iOperacao = 1;
} else {
  $db_opcao2 = $db_opcao = $iOperacao;
}

if ($iOperacao == 2 && isset($la56_i_codigo) && !empty($la56_i_codigo)) { // Usado na rotina de alteracao

  // Carrego os dados do controle
  $oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');
  $sSql                            = $oDaoLabControleFisicoFinanceiro->sql_query_controle($la56_i_codigo);
  $rs                              = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
  if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {
    db_fieldsmemory($rs, 0);
  }

  // Verifico se pode ser alterado por completo ou somente a data final
  $oDaoLabRequiItem = db_utils::getdao('lab_requiitem');
  $sSql             = $oDaoLabRequiItem->sql_query_file(null, '*', '', "la21_d_data >= '$la56_d_ini'");
  $rs               = $oDaoLabRequiItem->sql_record($sSql);

  if ($oDaoLabRequiItem->numrows > 0) {
    $db_opcao = 3; // Bloqueia os campos para edição, menos a data de fim da validade
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
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
        <fieldset style='width: 90%;'> <legend><b>Dados do tipo de controle</b></legend>
          <?
          require_once(modification('forms/db_frmlab_controlefisicofinanceiro2.php'));
          ?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
<?
/*
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'),
        db_getsession('DB_anousu'), db_getsession('DB_instit')
       );
*/
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la56_i_laboratorio",true,1,"la56_i_laboratorio",true);
</script>