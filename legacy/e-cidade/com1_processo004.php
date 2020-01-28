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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

db_postmemory($HTTP_POST_VARS);

$clpcorcam = new cl_pcorcam;
$clpcparam = new cl_pcparam;
$db_opcao  = 1;
$db_botao  = true;
$instit    = db_getsession("DB_instit");

try {

  if (isset($incluir)) {

    db_inicio_transacao();
    $sqlerro = false;

    /**
     * Valida se todos os itens do processo de compra possuem lote
     */
    $oDaoProcessoCompra     = new cl_pcproc();
    $oDaoProcessoCompraItem = new cl_pcprocitem();

    $sSqlProcessocompraItem = $oDaoProcessoCompraItem->sql_query_item_lote(null, "count(*)", null, "pc81_codproc = {$pc80_codproc} and pc69_sequencial is null");
    $sSqlProcessoCompra     = $oDaoProcessoCompra->sql_query_file( null,
                                                                   "({$sSqlProcessocompraItem}) as quantidade, pc80_tipoprocesso",
                                                                   null,
                                                                   "pc80_codproc = {$pc80_codproc}" );

    $rsProcessoCompra = $oDaoProcessoCompra->sql_record($sSqlProcessoCompra);

    if (!$rsProcessoCompra || !pg_num_rows($rsProcessoCompra)) {
      throw new Exception("Erro ao buscar os dados do processo de compras.");
    }

    $oProcessoCompra = db_utils::fieldsMemory($rsProcessoCompra, 0);

    if ($oProcessoCompra->pc80_tipoprocesso == 2 && $oProcessoCompra->quantidade > 0) {
      throw new Exception("O Processo de Compras {$pc80_codproc} possui itens não vinculados a um lote.");
    }

    $clpcorcam->incluir($pc20_codorc);
    $pc20_codorc = $clpcorcam->pc20_codorc;

    if ($clpcorcam->erro_status == 0) {
      throw new Exception($clpcorcam->erro_msg);
    }

    $erro_msg = $clpcorcam->erro_msg;
    db_fim_transacao($sqlerro);
  }

} catch (Exception $e) {

  $erro_msg = $e->getMessage();
  $sqlerro = true;
  db_fim_transacao(true);
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
  <body class="body-default" >
    <?php
      include("forms/db_frmprocesso.php");
    ?>
  </body>
</html>
<?php

if (isset($incluir)) {
  if ($sqlerro == true) {

    db_msgbox(str_replace("\n","\\n",$erro_msg));
    if($clpcorcam->erro_campo!=""){
      echo "<script> document.form1.".$clpcorcam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpcorcam->erro_campo.".focus();</script>";
    };

    echo "<script>window.location.href = 'com1_selsolicproc001.php?op=incluir';</script>";
  }else{
    db_redireciona("com1_processo005.php?liberaaba=true&retorno=$pc20_codorc&pc80_codproc=$pc80_codproc");
  }
}

if ($db_opcao == 1) {
  echo "<script>document.form1.incluir.click();</script>";
}
?>