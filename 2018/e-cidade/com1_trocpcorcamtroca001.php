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
require_once "libs/db_liborcamento.php";
require_once "dbforms/db_funcoes.php";
require_once "dbforms/db_classesgenericas.php";

$clpcorcamtroca  = new cl_pcorcamtroca();
$clpcorcamjulg   = new cl_pcorcamjulg();
$clpcorcamval    = new cl_pcorcamval();
$clrotulo        = new rotulocampo();
$oDaoPcorcamitem = new cl_pcorcamitem();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;
$sqlerro  = false;

try {

  db_inicio_transacao();

  if (isset($trocar)) {

    if (trim($pc25_motivo) == '') {
      throw new Exception("Campo motivo da troca da pontuação é de preenchimento obrigatório.");
    }

    $sSqlOrcamItem = $oDaoPcorcamitem->sql_query_pcmaterproc($pc25_orcamitem, "pc22_codorc, pc69_processocompralote");
    $rsOrcamItem   = $oDaoPcorcamitem->sql_record($sSqlOrcamItem);

    $aItensTroca = array();
    $oDadosItem  = null;

    if ($oDaoPcorcamitem->numrows) {
      $oDadosItem = db_utils::fieldsMemory($rsOrcamItem, 0);
    }

    /**
     * Verifica se o Orçamento do processo de compras é por item ou por lote
     * para alterar o fornecedor de todos os itens do lote
     */
    if (empty($oDadosItem) || empty($oDadosItem->pc69_processocompralote)) {
      $aItensTroca[] = $pc25_orcamitem;
    } else {

      $sSqlItensTroca = $oDaoPcorcamitem->sql_query_pcmaterproc( null,
                                                                 "pc22_orcamitem",
                                                                 null,
                                                                 "pc22_codorc = {$oDadosItem->pc22_codorc} and pc69_processocompralote = {$oDadosItem->pc69_processocompralote}" );
      $rsItensTroca   = $oDaoPcorcamitem->sql_record($sSqlItensTroca);

      if (!$rsItensTroca || !$oDaoPcorcamitem->numrows) {
        throw new Exception("Erro ao buscar itens do lote.");
      }

      for ($iRow = 0; $iRow < $oDaoPcorcamitem->numrows; $iRow++) {
        $aItensTroca[] = db_utils::fieldsMemory($rsItensTroca, $iRow)->pc22_orcamitem;
      }
    }

    if (empty($aItensTroca)) {
      throw new Exception("Nenhum item encontrado para realizar a troca de fornecedores.");
    }

    foreach ($aItensTroca as $iCodigoItem) {

      /**
       * Salva na tabela de troca dos fornecedores
       */
      $clpcorcamtroca->pc25_codtroca  = null;
      $clpcorcamtroca->pc25_orcamitem = $iCodigoItem;
      $clpcorcamtroca->pc25_motivo    = $pc25_motivo;
      $clpcorcamtroca->pc25_forneant  = $pc24_orcamforne_ant;
      $clpcorcamtroca->pc25_forneatu  = $pc24_orcamforne;

      $clpcorcamtroca->incluir(null);

      if ($clpcorcamtroca->erro_status == 0) {
        throw new Exception($clpcorcamtroca->erro_msg);
      }

      /**
       * Busca a pontuação do fornecedor substituto e do substituido
       */
      $aPontuacoes   = array();
      $sSqlOrcamJulg = $clpcorcamjulg->sql_query_file( null,
                                                       null,
                                                       "pc24_orcamforne as pc24_orcamforne_sql, pc24_pontuacao",
                                                       "pc24_orcamforne",
                                                       "pc24_orcamitem = {$iCodigoItem} and pc24_orcamforne in ({$pc24_orcamforne_ant}, {$pc24_orcamforne})" );
      $rsOrcamJulg   = $clpcorcamjulg->sql_record( $sSqlOrcamJulg );

      for ($iRow = 0; $iRow < $clpcorcamjulg->numrows; $iRow++) {

        $oOrcamJulg = db_utils::fieldsMemory($rsOrcamJulg, $iRow);
        $aPontuacoes[$oOrcamJulg->pc24_orcamforne_sql] = $oOrcamJulg->pc24_pontuacao;
      }

      /**
       * Faz a troca das pontuações dos fornecedores
       */
      $clpcorcamjulg->pc24_orcamitem  = $iCodigoItem;
      $clpcorcamjulg->pc24_pontuacao  = $aPontuacoes[$pc24_orcamforne_ant];
      $clpcorcamjulg->pc24_orcamforne = $pc24_orcamforne;
      $clpcorcamjulg->alterar($iCodigoItem, $pc24_orcamforne);

      if ($clpcorcamjulg->erro_status == 0) {
        throw new Exception($clpcorcamjulg->erro_msg);
      }

      $clpcorcamjulg->pc24_orcamitem  = $iCodigoItem;
      $clpcorcamjulg->pc24_pontuacao  = $aPontuacoes[$pc24_orcamforne];
      $clpcorcamjulg->pc24_orcamforne = $pc24_orcamforne_ant;
      $clpcorcamjulg->alterar($iCodigoItem, $pc24_orcamforne_ant);

      if ($clpcorcamjulg->erro_status == 0) {
        throw new Exception($clpcorcamjulg->erro_msg);
      }
    }
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);

  $erro_msg = $e->getMessage();
  $sqlerro  = true;
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
    <div class="container">
      <?php include("forms/db_frmtrocpcorcamtroca.php"); ?>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <?php

    if (isset($trocar)) {
      if ($sqlerro == true) {

        $erro_msg = str_replace("\n","\\n",$erro_msg);
        db_msgbox($erro_msg);

        if ($clpcorcamtroca->erro_campo!="") {
          echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".style.backgroundColor='#99A9AE';</script>";
          echo "<script> document.form1.".$clpcorcamtroca->erro_campo.".focus();</script>";
        }

      } else {
        echo "<script> top.corpo.location.href = 'com1_pcorcamtroca001.php?sol=$sol&pc20_codorc=$orcamento'; </script>";
      }
    }
  ?>
</html>