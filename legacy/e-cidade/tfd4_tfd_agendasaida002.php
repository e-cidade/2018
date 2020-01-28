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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oDaoTfdPedidoTfd = new cl_tfd_pedidotfd();
$db_opcao         = 1;

db_postmemory( $_POST );

$iPedido = null;

if( isset( $pedPrestadora ) ) {
  $iPedido = $pedPrestadora;
} else if( isset( $Pedidos ) ) {
  $iPedido = $Pedidos;
}

if( !empty( $iPedido ) ) {

  $sCampos  = " tf10_i_centralagend, cgmcentral.z01_nome as z01_nome, cgmprest.z01_nome as z01_nome2, ";
  $sCampos .= " tf16_i_prestcentralagend, tf10_i_prestadora, tf10_i_centralagend, tf09_i_numcgm, ";
  $sCampos .= " cgmprest.z01_ender||', n° '||cgmprest.z01_numero||cgmprest.z01_compl||' - '";
  $sCampos .= "||cgmprest.z01_bairro||' - '||cgmprest.z01_munic||' - '||cgmprest.z01_uf ";
  $sCampos .= " as sEnderecoPrestadora ";
  $sSql     = $oDaoTfdPedidoTfd->sql_query_pedido_novo( $iPedido, $sCampos, null, '' );
  $rs       = $oDaoTfdPedidoTfd->sql_record( $sSql );

  if ($oDaoTfdPedidoTfd->numrows > 0) {

    $db_opcao = 2;
    db_fieldsmemory( $rs, 0 );
  }
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load(" prototype.js, scripts.js, datagrid.widget.js, strings.js, webseller.js ");
    db_app::load(" grid.style.css, estilos.css ");
    ?>
  </head>
  <body bgcolor="#CCCCCC" >
    <?php
    require_once("forms/db_frmtfd_agendaprestsaidaconjunto.php");
    ?>
  </body>
</html>