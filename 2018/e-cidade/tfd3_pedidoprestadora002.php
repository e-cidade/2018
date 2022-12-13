<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$sWhere   = " tf16_i_pedidotfd = {$oGet->iPedido} ";
$sCampos  = " tf16_d_dataagendamento, tf16_c_horaagendamento, ";
$sCampos .= " tfd_centralagendamento.tf09_i_codigo as cod_central, ";
$sCampos .= " tfd_prestadora.tf25_i_codigo         as cod_prestadora, ";
$sCampos .= " cgm_prestadora.z01_nome              as cgm_prestadora, ";
$sCampos .= " cgm_central.z01_nome                 as cgm_central ";

$oDaoPrestadora = new cl_tfd_agendamentoprestadora();
$sSqlPrestadora = $oDaoPrestadora->sql_query_prestadora(null, $sCampos, null, $sWhere);
$rsPrestadora   = db_query($sSqlPrestadora);

$oDadosPrestadora                   = new stdClass();
$oDadosPrestadora->sCentral         = null;
$oDadosPrestadora->sPrestadora      = null;
$oDadosPrestadora->dtAgendamento    = null;
$oDadosPrestadora->sHoraAgendamento = null;


if ( $rsPrestadora && pg_num_rows($rsPrestadora) > 0 ) {

  $oDados = db_utils::fieldsMemory($rsPrestadora, 0);

  $sData = "";
  if ( !empty($oDados->tf16_d_dataagendamento) ) {


    $oData = new DBDate($oDados->tf16_d_dataagendamento);
    $sData = $oData->convertTo(DBDate::DATA_PTBR);
  }

  $oDadosPrestadora->sCentral         = "{$oDados->cod_central} - {$oDados->cgm_central}";
  $oDadosPrestadora->sPrestadora      = "{$oDados->cod_prestadora} - {$oDados->cgm_prestadora}";
  $oDadosPrestadora->dtAgendamento    = $sData;
  $oDadosPrestadora->sHoraAgendamento = $oDados->tf16_c_horaagendamento;

}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body class='body-default'>

<div class="subcontainer">
  <fieldset >
    <legend>Dados da Prestadora</legend>
    <table class="form-container">
      <tr>
        <td class="bold">Central:</td>
        <td style="background-color:#FFF; border: 1px solid #CCC;" >
          <?= $oDadosPrestadora->sCentral ?>
        </td>
      </tr>
      <tr>
        <td  class="bold">Prestadora:</td>
        <td class="field-size9" style="background-color:#FFF; border: 1px solid #CCC;">
          <?= $oDadosPrestadora->sPrestadora ?>
        </td>
      </tr>
      <tr>
        <td  class="bold">Data Cons./Exame:</td>
        <td class="field-size9" style="background-color:#FFF; border: 1px solid #CCC;">
          <?= $oDadosPrestadora->dtAgendamento ?>
        </td>
      </tr>
      <tr>
        <td  class="bold">Hora:</td>
        <td  class="field-size9" style="background-color:#FFF; border: 1px solid #CCC;">
          <?= $oDadosPrestadora->sHoraAgendamento  ?>
        </td>
      </tr>
    </table>
  </fieldset>
</div>
</body>
<script type="text/javascript">


</script>
</html>