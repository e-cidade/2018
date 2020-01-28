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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($_POST);

$oDaotfd_situacaopedidotfd = db_utils::getdao('tfd_situacaopedidotfd');
$oDaotfd_situacaotfd       = db_utils::getdao('tfd_situacaotfd');
$oDaotfd_pedidotfd         = db_utils::getdao('tfd_pedidotfd');
$oDaoPassageiroVeiculo     = new cl_tfd_passageiroveiculo();

$db_opcao = 1;
$db_botao = true;

$lErro = false;


if(isset($incluir)) {

  db_inicio_transacao();
  $oDaotfd_situacaopedidotfd->tf28_i_login = db_getsession('DB_id_usuario');
  $oDaotfd_situacaopedidotfd->tf28_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
  $oDaotfd_situacaopedidotfd->tf28_c_horasistema = date('H:i');
  $oDaotfd_situacaopedidotfd->incluir(null);

  if ($oDaotfd_situacaopedidotfd->erro_status == 0) {
    $lErro = true;
  }

  if ( !$lErro ) {

    $sWhereRemoverVinculo  = " tf19_i_pedidotfd = {$tf28_i_pedidotfd} ";
    $oDaoPassageiroVeiculo->excluir( null, $sWhereRemoverVinculo );

    if ($oDaoPassageiroVeiculo->erro_status == 0) {
      $lErro = true;
    }
  }

  db_fim_transacao($lErro);

}

if($operacao == 1) {
  $sLabelDesistencia = 'Desistência';
} else {
  $sLabelDesistencia = 'Cancelamento de Desistência';
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 75%;'> <legend><b><?=$sLabelDesistencia?> de Pedido de TFD</b></legend>
	      <?
        require_once("forms/db_frmtfd_desistencia.php");
        ?>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","tf28_i_situacao",true,1,"tf28_i_situacao",true);
</script>
<?
if(isset($incluir)) {

  if ($oDaotfd_situacaopedidotfd->erro_status == '0') {

    $oDaotfd_situacaopedidotfd->erro(true, false);

  } else if ( $oDaoPassageiroVeiculo->erro_status == 0 ) {

    $oDaoPassageiroVeiculo->erro(true, false);
  } else {

    $oDaotfd_situacaopedidotfd->erro(true, false);
    echo "<script>

       sChave = 'tf01_i_pedidotfd={$tf28_i_pedidotfd}';

       jan    = window.open('tfd2_protocolopedidotfd002.php?'+sChave, '',
                            'width='+(screen.availWidth - 5)+',height='+
                            (screen.availHeight - 40)+',scrollbars=1,location=0 '
                           );
       jan.moveTo(0, 0);
       </script>
    ";

    db_redireciona('tfd4_desistencia001.php?operacao='.$operacao);

  }

}