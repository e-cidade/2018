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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);

$oDaotfd_agendamentoprestadora = db_utils::getdao('tfd_agendamentoprestadora');
$oDaotfd_agendasaida           = db_utils::getdao('tfd_agendasaida');

$db_opcao = 1;
$db_botao = true;



if(isset($incluir)) {

  db_inicio_transacao();
  $oDaotfd_agendamentoprestadora->tf16_i_login       = db_getsession('DB_id_usuario');
  $oDaotfd_agendamentoprestadora->tf16_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaotfd_agendamentoprestadora->tf16_c_horasistema = date('H:i');
  $oDaotfd_agendamentoprestadora->incluir($tf16_i_codigo);
  db_fim_transacao($oDaotfd_agendamentoprestadora->erro_status == '0' ? true : false);

}

if(isset($alterar)) {

  $db_opcao = 2;

  // bloco que verifica se já foi agendada a saída para o pedido de tfd.
  // Se sim, o agendamento com a prestadora não pode ser modificado
  $sSql = $oDaotfd_agendasaida->sql_query2(null, '*', null, " tf17_i_pedidotfd = $tf16_i_pedidotfd");
  $rs = $oDaotfd_agendasaida->sql_record($sSql);
  if($oDaotfd_agendasaida->numrows > 0) {

    $sMsg = 'Este pedido de TFD já possui saída agendada. Para alterar o agendamento'.
            ' da prestadora você deve primeiro excluir o agendamento da saída.';

  	$oDaotfd_agendamentoprestadora->erro_status = '0';
    $oDaotfd_agendamentoprestadora->erro_msg    = $sMsg;

  } else {

    db_inicio_transacao();
    $oDaotfd_agendamentoprestadora->alterar($tf16_i_codigo);
    db_fim_transacao($oDaotfd_agendamentoprestadora->erro_status == '0' ? true : false);

  }

}

if(!isset($incluir) && !isset($alterar)) {

  $sCampos  = 'tfd_agendamentoprestadora.*, tf10_i_prestadora, tf10_i_centralagend ';
  $sSql     = $oDaotfd_agendamentoprestadora->sql_query(null, $sCampos, null,
                                                    " tf16_i_pedidotfd = $tf16_i_pedidotfd");

  $rs = $oDaotfd_agendamentoprestadora->sql_record($sSql);
  if($oDaotfd_agendamentoprestadora->numrows > 0) {

    $db_opcao = 2;
    db_fieldsmemory($rs, 0);

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
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
db_app::load("prototype.js, webseller.js, strings.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 80%;'> <legend><b>Agendamento com a Prestadora</b></legend>
	      <?
        require_once("forms/db_frmtfd_agendamentoprestadora.php");
	      ?>
        </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf16_i_prestcentralagend",true,1,"tf16_i_prestcentralagend",true);
js_init();
</script>
<?
if(isset($incluir) || isset($alterar)) {

  if($oDaotfd_agendamentoprestadora->erro_status == '0') {

    $oDaotfd_agendamentoprestadora->erro(true, false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

  } else {

    $oDaotfd_agendamentoprestadora->erro(true, false);
    db_redireciona('tfd4_tfd_agendamentoprestadora001.php?tf16_i_pedidotfd='.
                   $tf16_i_pedidotfd.'&tf01_i_cgsund=\''.
                   '+document.getElementById(\'tf01_i_cgsund\').value+\'&z01_v_nome='.$z01_v_nome);

  }

}
?>