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
require_once(modification('dbforms/db_funcoes.php'));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oDaoLabControleFisicoFinanceiro = db_utils::getdao('lab_controlefisicofinanceiro');
$db_opcao                        = 1;
$db_botao                        = false;
$iTipoControle                   = 0;

/* Verifico se algum tipo de controle físico / financeiro já foi definido. Se houver, pego qualquer um deles
   para verificar qual valor do select com as opções gerais de controle (departamento sol., lab, ...)
   eu tenho que enviar. Pego qualquer um pq se tiver um controle por departamento solicitante, por exemplo,
   os para todos os demais departamentos o controle deve ser por departamento solicitante,
   ou alguma combinação de departamento solicitante com exame, grupo de exame ou laboratório.
   Pode acontecer de um ser somente por
   departamento sol. e outro ser por exames do departamento solicitante. O que não pode é ter um controle
   por departamento e outro por laboratório, por exemplo.
   Outra coisa que não pode acontecer é ter mais de um tipo de controle para o MESMO departamento.
   Tipos de controle diferentes somente para departamentos diferentes. */
$sSql = $oDaoLabControleFisicoFinanceiro->sql_query_file(null, 'la56_i_tipocontrole');
$rs   = $oDaoLabControleFisicoFinanceiro->sql_record($sSql);
if ($oDaoLabControleFisicoFinanceiro->numrows > 0) {

  $iTipoControle = db_utils::fieldsmemory($rs, 0)->la56_i_tipocontrole;

  // Seto o tipo de controle do select, pois o valor é usado na geração do formulário
  if ($iTipoControle > 0 && $iTipoControle < 4 || $iTipoControle == 9) { // Valores de 1, 2, 3 e 9 (por depto. sol.)
    $iSelectControle = 1;
  } elseif ($iTipoControle > 3 && $iTipoControle < 7) { // Valores de 4, 5 e 6
    $iSelectControle = 2;
  } elseif ($iTipoControle == 7) {
    $iSelectControle = 3;
  } elseif ($iTipoControle == 8) {
    $iSelectControle = 4;
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
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
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
<center>
<br><br>
<table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
        <fieldset style='width: 100%;'> <legend><b>Controle Físico / Financeiro</b></legend>
          <?
          require_once(modification('forms/db_frmlab_controlefisicofinanceiro.php'));
          ?>
        </fieldset>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'),
        db_getsession('DB_anousu'), db_getsession('DB_instit')
       );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la56_i_laboratorio",true,1,"la56_i_laboratorio",true);
</script>