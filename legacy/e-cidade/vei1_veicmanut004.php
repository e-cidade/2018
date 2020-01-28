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

$clveiculos          = new cl_veiculos();
$clveicmanut         = new cl_veicmanut();
$clveicmanutoficina  = new cl_veicmanutoficina();
$clveicmanutretirada = new cl_veicmanutretirada();
$clveicretirada      = new cl_veicretirada();

db_postmemory($_POST);

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

if (isset($incluir)) {

  $sHora = db_hora();

  if ($sqlerro == false) {

    db_inicio_transacao();
    $clveicmanut->ve62_usuario = db_getsession("DB_id_usuario");
    $clveicmanut->ve62_data    = date("Y-m-d",db_getsession("DB_datausu"));
    $clveicmanut->ve62_numero  = VeiculoManutencao::getProximoNumero(db_getsession("DB_anousu"));
    $clveicmanut->ve62_anousu  = db_getsession("DB_anousu");

    if (!empty($ve65_veicretirada)) {

      $clveicmanut->ve62_veicmotoristas = null;
      $ve62_veicmotoristas              = null;
    }

    if (empty($ve62_veicmotoristas)) {
      $clveicmanut->ve62_veicmotoristas = "null";
    }

    $lFksValidas = true;
    if (!empty($ve62_veicmotoristas) && $ve62_veicmotoristas !== "null") {

      $sSqlFk = "select ve05_codigo from veicmotoristas where ve05_codigo = {$ve62_veicmotoristas} ";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        unset($ve62_veicmotoristas);
        unset($descricao_motorista);
        $erro_msg    = "O valor informado para o campo Motorista é inválido.";
        $lFksValidas = false;
      }
    }
    if (!empty($ve62_veiccadtiposervico)) {

      $sSqlFk = "select ve28_codigo from veiccadtiposervico where ve28_codigo = {$ve62_veiccadtiposervico} ";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        unset($ve62_veiccadtiposervico);
        unset($ve28_descr);
        $erro_msg    = "O valor informado para o campo Tipo de Serviço é inválido.";
        $lFksValidas = false;
      }
    }
    if (!empty($ve62_veiculos)) {

      $sSqlFk = "select ve01_codigo from veiculos where ve01_codigo = {$ve62_veiculos} ";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        unset($ve62_veiculos);
        unset($ve01_placa);
        $erro_msg    = "O valor informado para o campo Veículo é inválido.";
        $lFksValidas = false;
      }
    }
    if (!empty($ve66_veiccadoficinas)) {

      $sSqlFk = "select ve27_codigo from veiccadoficinas where ve27_codigo = {$ve66_veiccadoficinas} ";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        unset($ve66_veiccadoficinas);
        unset($z01_nome);
        $erro_msg    = "O valor informado para o campo Oficina é inválido.";
        $lFksValidas = false;
      }
    }
    if (!empty($ve65_veicretirada)) {

      $sSqlFk = "select ve60_codigo from veicretirada where ve60_codigo = {$ve65_veicretirada} ";
      $rsFk   = db_query($sSqlFk);
      if (pg_num_rows($rsFk) == 0) {

        unset($ve65_veicretirada);
        unset($ve60_codigo);
        $erro_msg    = "O valor informado para o campo Retirada é inválido.";
        $lFksValidas = false;
      }
    }

    if ($lFksValidas) {
      $clveicmanut->incluir(null);
    }

    if (!empty($clveicmanut->erro_msg) && $clveicmanut->erro_status == 0) {

      $sqlerro  = true;
      $erro_msg = $clveicmanut->erro_msg;
    }

    if (!empty($clveicmanut->erro_msg)) {
      $erro_msg = $clveicmanut->erro_msg;
    }

    if ($lFksValidas && $sqlerro == false) {

      if (isset($ve66_veiccadoficinas) && $ve66_veiccadoficinas != "") {

        $clveicmanutoficina->ve66_veicmanut = $clveicmanut->ve62_codigo;
        $clveicmanutoficina->incluir(null);

        if ($clveicmanutoficina->erro_status == "0") {

          $erro_msg = $clveicmanutoficina->erro_msg;
          $sqlerro  = true;
        }
      }
    }

    if ($lFksValidas && $sqlerro == false) {

      if (isset($ve65_veicretirada) && $ve65_veicretirada != "") {

        $clveicmanutretirada->ve65_veicmanut = $clveicmanut->ve62_codigo;
        $clveicmanutretirada->incluir(null);

        if ($clveicmanutretirada->erro_status == "0") {

          $erro_msg = $clveicmanutretirada->erro_msg;
          $sqlerro  = true;
        }
      }
    }

    db_fim_transacao($sqlerro);
  }

  if ($lFksValidas) {
    $ve62_codigo = $clveicmanut->ve62_codigo;
  }
  $db_opcao    = 1;
  $db_botao    = true;
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <?php include "forms/db_frmveicmanut.php"; ?>
  </body>
</html>
<?php

if (isset($incluir)) {

  if ($sqlerro == true) {

    db_msgbox($erro_msg);
    if ($clveicmanut->erro_campo != "") {

      echo "<script> document.form1.".$clveicmanut->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clveicmanut->erro_campo.".focus();</script>";
    };
  } else {

    db_msgbox($erro_msg);
    if ($lFksValidas) {
      db_redireciona("vei1_veicmanut005.php?liberaaba=true&chavepesquisa={$ve62_codigo}");
    }
  }
}