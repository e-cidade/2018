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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$oDaoSolicitaAnulada  = new cl_solicitaanulada();
$sSqlBuscaSolicitacao = $oDaoSolicitaAnulada->sql_query_file(null, "*", null, "pc67_solicita = {$oGet->pc10_numero}");
$rsBuscaSolicitacao   = $oDaoSolicitaAnulada->sql_record($sSqlBuscaSolicitacao);
if ($oDaoSolicitaAnulada->numrows == 0) {
  echo "<p align='center'><b>Dados na anulação não encontrados.</b></p>";exit;
}

$oStdAnulacao = db_utils::fieldsMemory($rsBuscaSolicitacao, 0);

$oUsuario = UsuarioSistemaRepository::getPorCodigo($oStdAnulacao->pc67_usuario);
$oData = new DBDate($oStdAnulacao->pc67_data);
?>
<!--pc67_sequencial            -->
<!--pc67_usuario               -->
<!--pc67_data                  -->
<!--pc67_hora                  -->
<!--pc67_solicita              -->
<!--pc67_motivo                -->
<!--pc67_processoadministrativo-->



<html>
<head>
<title>Dados do Cadastro de Veículos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
  <style type='text/css'>
    .valores {background-color:#FFFFFF}
  </style>
</head>
<body style="background-color: #CCCCCC; ">
  <div class="container">
    <fieldset style="width: 600px">
      <legend class="bold">Dados da Anulação</legend>
      <table>
        <tr>
          <td class="bold" style="width: 10%">Usuário:</td>
          <td class="valores" style="90%">
            <?php echo $oUsuario->getCodigo()." - ".$oUsuario->getNome(); ?>
          </td>
        </tr>
        <tr>
          <td nowrap class="bold" style="width: 10%">Data / Hora:</td>
          <td class="valores" style="90%">
            <?php echo $oData->getDate(DBDate::DATA_PTBR) ." - ".$oStdAnulacao->pc67_hora; ?>
          </td>
        </tr>
        <tr>
          <td nowrap class="bold" style="width: 10%">Processo Admnistrativo:</td>
          <td class="valores" style="90%">
            <?php echo $oStdAnulacao->pc67_processoadministrativo; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset style="width: 96%">
              <legend class="bold">Motivo</legend>
              <div style="width: 100%; height: 150px; background-color: #FFFFFF">
                <?php echo nl2br($oStdAnulacao->pc67_motivo);?>
              </div>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>

  </div>
</body>
</html>