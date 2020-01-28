<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_abatimento_classe.php");
require_once("classes/db_abatimentoarreckey_classe.php");
require_once("classes/db_abatimentorecibo_classe.php");
require_once("model/arrecadacao/abatimento/Abatimento.model.php");

$oGet         = db_utils::postMemory($_GET);
$iAbatimento  = $oGet->iAbatimento;

if (isset($oGet->sOrigem)) {
  $sOrigem = $oGet->sOrigem;
} else {
  $sOrigem = '';
}

$clAbatimento         = new cl_abatimento();
$clAbatimentoArreckey = new cl_abatimentoarreckey();
$clAbatimentoRecibo   = new cl_abatimentorecibo();

$sCamposDadosDesconto  = 'k125_sequencial, ';
$sCamposDadosDesconto .= 'k126_descricao,  ';
$sCamposDadosDesconto .= 'k125_datalanc,   ';
$sCamposDadosDesconto .= 'k125_hora,       ';
$sCamposDadosDesconto .= 'nome,            ';
$sCamposDadosDesconto .= 'k125_valor,      ';
$sCamposDadosDesconto .= 'k125_perc        ';

$sWhereDadosDesconto = 'k125_tipoabatimento = ' . Abatimento::TIPO_DESCONTO;

$sSqlDadosDesconto = $clAbatimento->sql_query($iAbatimento, $sCamposDadosDesconto, null, $sWhereDadosDesconto);
$rsDadosDesconto   = $clAbatimento->sql_record($sSqlDadosDesconto); 

if ( $clAbatimento->numrows == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
  exit;
} else {
  $oDadosDesconto = db_utils::fieldsMemory($rsDadosDesconto, 0);
}
 
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
  fieldset legend {
    font-weight:bold;
  }
  fieldset {
    margin: 10px;
  }
  td {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
  }
  table.linhaZebrada {
    width: 98%;
  }
  table.linhaZebrada tr td:nth-child(even) {
    background-color : #FFF;
    padding-left     : 5px;
  }
  table.linhaZebrada tr td:nth-child(odd) {
    font-weight:bold;
    width      :150px;
  }
</style>
<?php

  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<center>
  <table align="center">
    <tr>
      <td>
        <table width="100%">
          <tr> 
            <td> 
              <fieldset>
                <legend>Dados do Desconto</legend>
                <table class="linhaZebrada">
                  <tr><td align="right">C�digo Abatimento :  &nbsp;</td><td><?php echo $oDadosDesconto->k125_sequencial;                ?></td></tr>
                  <tr><td align="right">Tipo de Abatimento : &nbsp;</td><td><?php echo $oDadosDesconto->k126_descricao;                 ?></td></tr>
                  <tr><td align="right">Data do Lan�amento : &nbsp;</td><td><?php echo db_formatar($oDadosDesconto->k125_datalanc,'d'); ?></td></tr>
                  <tr><td align="right">Hora Lan�amento :    &nbsp;</td><td><?php echo $oDadosDesconto->k125_hora;                      ?></td></tr>
                  <tr><td align="right">Usu�rio :            &nbsp;</td><td><?php echo $oDadosDesconto->nome;                           ?></td></tr>
                  <tr><td align="right">Valor :              &nbsp;</td><td><?php echo db_formatar($oDadosDesconto->k125_valor,'f');    ?></td></tr>
                  <tr><td align="right">Percentual :         &nbsp;</td><td><?php echo $oDadosDesconto->k125_perc." %";                 ?></td></tr>
                </table>
              </fieldset>
            </td>    
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <fieldset>
          <legend>
            <b>&nbsp;ORIGEM DOS DADOS&nbsp;</b>
          </legend>
          <table align="center">
            <tr>
              <td>
                <?php
                    
                  $sCamposOrigemAbatimento  = " arreckey.k00_numpre, ";
                  $sCamposOrigemAbatimento .= " arreckey.k00_numpar, ";
                  $sCamposOrigemAbatimento .= " arreckey.k00_receit, ";
                  $sCamposOrigemAbatimento .= " arreckey.k00_hist,   ";
                  $sCamposOrigemAbatimento .= " arreckey.k00_tipo,   ";
                  $sCamposOrigemAbatimento .= " k128_valorabatido,   ";
                  $sCamposOrigemAbatimento .= " k128_correcao,       ";
                  $sCamposOrigemAbatimento .= " k128_juros,          ";
                  $sCamposOrigemAbatimento .= " k128_multa,          ";
                  $sCamposOrigemAbatimento .= " sum( k128_valorabatido + k128_correcao + k128_juros + k128_multa )  as dl_Total ";

                  $sOrdemCampos  = " arreckey.k00_numpre,"; 
                  $sOrdemCampos .= " arreckey.k00_numpar,";
                  $sOrdemCampos .= " arreckey.k00_receit ";                    
                  
                  $sWhereOrigemAbatimento  = "abatimentoarreckey.k128_abatimento = {$iAbatimento}";
                  $sWhereOrigemAbatimento .= " group by arreckey.k00_numpre, ";
                  $sWhereOrigemAbatimento .= "          arreckey.k00_numpar, ";
                  $sWhereOrigemAbatimento .= "          arreckey.k00_receit, ";
                  $sWhereOrigemAbatimento .= "          arreckey.k00_hist,   ";
                  $sWhereOrigemAbatimento .= "          arreckey.k00_tipo,   ";
                  $sWhereOrigemAbatimento .= "          k128_valorabatido,   ";
                  $sWhereOrigemAbatimento .= "          k128_correcao,       ";
                  $sWhereOrigemAbatimento .= "          k128_juros,          ";
                  $sWhereOrigemAbatimento .= "          k128_multa,          ";
                  $sWhereOrigemAbatimento .= "          tabrec.k02_descr,    ";
                  $sWhereOrigemAbatimento .= "          histcalc.k01_descr,  ";
                  $sWhereOrigemAbatimento .= "          arretipo.k00_descr   ";

                  $sSqlOrigemAbatimento   = $clAbatimentoArreckey->sql_query_buscaAbatimento($sCamposOrigemAbatimento, $sOrdemCampos, $sWhereOrigemAbatimento);
                  
                  db_lovrot($sSqlOrigemAbatimento,15);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</center>
</body>
</html>