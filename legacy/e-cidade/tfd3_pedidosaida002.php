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


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$sCampos    = " tf16_d_dataagendamento,                 ";
$sCampos   .= " tf16_c_horaagendamento,                 ";
$sCampos   .= " tf17_d_datasaida,                       ";
$sCampos   .= " tf17_tiposaida,                         ";
$sCampos   .= " tf17_c_horasaida,                       ";
$sCampos   .= " tf17_c_localsaida,                      ";
$sCampos   .= " tf18_d_dataretorno,                     ";
$sCampos   .= " tf18_c_horaretorno,                     ";
$sCampos   .= " tf18_i_veiculo,                         ";
$sCampos   .= " ve01_placa,                             ";
$sCampos   .= " tf18_i_motorista,                       ";
$sCampos   .= " cgm_motorista.z01_nome AS nome_motorista";
$sWhere     = " tf01_i_codigo = {$oGet->iPedido} ";
$oDaoPedido = new cl_tfd_pedidotfd();
$sSqlPedido = $oDaoPedido->sql_query_pedido_saida( null, $sCampos, "", $sWhere );
$rsPedido   = db_query( $sSqlPedido );

$oSaida                   = new stdClass();
$oSaida->dtAgendamento    = "";
$oSaida->sHoraAgendamento = "";
$oSaida->dtSaida          = "";
$oSaida->sHoraSaida       = "";
$oSaida->sLocalSaida      = "";
$oSaida->dtRetorno        = "";
$oSaida->sHoraRetorno     = "";
$oSaida->iVeiculo         = "";
$oSaida->sPlaca           = "";
$oSaida->sMotorista       = "";

$oSaida->nValorPassagens  = "";
$oSaida->sTipoSaida       = "";

$sClasseOculta            = "passagem";
$sClasseVisivel           = "veiculo";
if ($rsPedido && pg_num_rows($rsPedido) > 0) {

  $oDados = db_utils::fieldsMemory($rsPedido, 0);

  $oSaida->dtAgendamento    = db_formatar($oDados->tf16_d_dataagendamento, 'd');
  $oSaida->sHoraAgendamento = $oDados->tf16_c_horaagendamento;
  $oSaida->dtSaida          = db_formatar($oDados->tf17_d_datasaida, 'd');
  $oSaida->sHoraSaida       = $oDados->tf17_c_horasaida;
  $oSaida->sLocalSaida      = $oDados->tf17_c_localsaida;
  $oSaida->dtRetorno        = db_formatar( $oDados->tf18_d_dataretorno, 'd');
  $oSaida->sHoraRetorno     = $oDados->tf18_c_horaretorno; 
  $oSaida->iVeiculo         = $oDados->tf18_i_veiculo;
  $oSaida->sPlaca           = $oDados->ve01_placa;

  $oSaida->sTipoSaida       = $oDados->tf17_tiposaida == "2" ? "Passagem" : "Veículo";
  $sClasseOculta            = $oDados->tf17_tiposaida == "2" ? "veiculo"  : "passagem";
  $sClasseVisivel           = $oDados->tf17_tiposaida == "2" ? "passagem" : "veiculo";

  if (!empty($oDados->nome_motorista)) {
    $oSaida->sMotorista = "{$oDados->tf18_i_motorista} - {$oDados->nome_motorista} ";
  }

}
$assets = array("estilos.css","scripts.js","strings.js","prototype.js");
?>
<html>
  <head>
    <title>DBSeller Informática</title>
    <meta http-equiv="Expires" CONTENT="0">
    <?php 
    /**
     * Carregamento dos Assets
     */ 
    db_app::load($assets);

    echo <<<HTML
    <style>
      td {
        padding-left: 5px !important;
      }
      td.field {
        background-color:#FFF; 
        border: 1px solid #CCC;
      }
      .{$sClasseOculta} {
        display: none;          
      }
      .{$sClasseVisivel} {
        display: inherit !important;          
      }
    </style>
HTML;
    ?>
  </head>
  <body class='body-default'>

    <div class="subcontainer">
      <fieldset >
        <legend>Dados da Saída</legend>

        <table class="form-container">
          
          <tr class="veiculo passagem">
            <td class="bold">Tipo de Saída:</td>
            <td colspan='3' class="field">
              <?= $oSaida->sTipoSaida ?>
            </td>
          </tr>

          <tr class="veiculo passagem">
            <td class="bold">Data Agendamento:</td>
            <td class="field-size3 field" >
              <?= $oSaida->dtAgendamento ?>
            </td>
            <td class="bold">Hora Agendamento:</td>
            <td class="field-size2 field" >
              <?= $oSaida->sHoraAgendamento ?>
            </td>
          </tr>
          <tr class="veiculo passagem">
            <td class="bold">Data de Saída:</td>
            <td class="field-size3 field">
              <?= $oSaida->dtSaida ?>
            </td>
            <td class="bold">Hora de Saída:</td>
            <td class="field-size2 field">
              <?= $oSaida->sHoraSaida ?>
            </td>
          </tr>
          <tr class="veiculo">
            <td class="bold">Data de Retorno:</td>
            <td class="field-size3  field">
              <?= $oSaida->dtRetorno ?>
            </td>
            <td class="bold">Hora de Retorno:</td>
            <td class="field-size2  field">
              <?= $oSaida->sHoraRetorno ?>
            </td>
          </tr>
          <tr class="veiculo passagem">
            <td class="bold">Local de Saída:</td>
            <td colspan='3' class="field">
              <?= $oSaida->sLocalSaida ?>
            </td>
          </tr>
          <tr class="veiculo">
            <td class="bold">Veículo:</td>
            <td colspan='3' class="field">
              <?= $oSaida->sPlaca ?>
            </td>
          </tr>
          <tr class="veiculo">
            <td class="bold">Motorista:</td>
            <td colspan='3' class="field">
              <?= $oSaida->sMotorista  ?>
            </td>
          </tr>
          
        </table>
      </fieldset>
    </div>


  </body>
</html>
