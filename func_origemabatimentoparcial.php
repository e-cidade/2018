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

$rsDadosAbatimento = $clAbatimento->sql_record($clAbatimento->sql_query($iAbatimento));

if ( $clAbatimento->numrows == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
  exit;
} else {
  $oAbatimento = db_utils::fieldsMemory($rsDadosAbatimento,0);
}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
            <td align="center">
              <b style="font-size:13px">
                <?php echo" DADOS DO ".strtoupper($oAbatimento->k126_descricao); ?>
              </b>
            </td>
          </tr>
          <tr>
            <td>
              <table border="1" cellspacing="0" bgcolor="#999999" align="center" width="100%">
                <tr><td align="right">Código Abatimento :  &nbsp;</td><td><?php echo $oAbatimento->k125_sequencial;                ?></td></tr>
                <tr><td align="right">Tipo de Abatimento : &nbsp;</td><td><?php echo $oAbatimento->k126_descricao;                 ?></td></tr>
                <tr><td align="right">Data do Lançamento : &nbsp;</td><td><?php echo db_formatar($oAbatimento->k125_datalanc,'d'); ?></td></tr>
                <tr><td align="right">Hora Lançamento :    &nbsp;</td><td><?php echo $oAbatimento->k125_hora;                      ?></td></tr>
                <tr><td align="right">Usuário :            &nbsp;</td><td><?php echo $oAbatimento->nome;                           ?></td></tr>
                <tr><td align="right">Valor :              &nbsp;</td><td><?php echo db_formatar($oAbatimento->k125_valor,'f');    ?></td></tr>
                <tr><td align="right">Percentual :         &nbsp;</td><td><?php echo $oAbatimento->k125_perc." %";                 ?></td></tr>
              </table>
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


                  if ( $sOrigem == 'recibo' ) {

                    $sCamposOrigemAbatimento  = " recibo.k00_numpre,    ";
                    $sCamposOrigemAbatimento .= " recibo.k00_numpar,    ";
                    $sCamposOrigemAbatimento .= " recibo.k00_receit,    ";
                    $sCamposOrigemAbatimento .= " tabrec.k02_descr,     ";
                    $sCamposOrigemAbatimento .= " recibo.k00_hist,      ";
                    $sCamposOrigemAbatimento .= " histcalc.k01_descr,   ";
                    $sCamposOrigemAbatimento .= " recibo.k00_tipo,      ";
                    $sCamposOrigemAbatimento .= " arretipo.k00_descr    ";

                    $sOrdemCampos             = " recibo.k00_numpre,    ";
                    $sOrdemCampos            .= " recibo.k00_numpar,    ";
                    $sOrdemCampos            .= " recibo.k00_receit     ";

                    $sWhereOrigemAbatimento   = "abatimentorecibo.k127_abatimento = {$iAbatimento} ";
                    $sSqlOrigemAbatimento     = $clAbatimentoRecibo->sql_query_dados_recibo(null,$sCamposOrigemAbatimento,$sOrdemCampos,$sWhereOrigemAbatimento);

                  } else {

                    $sCamposOrigemAbatimento  = " arreckey.k00_numpre,  ";
                    $sCamposOrigemAbatimento .= " arreckey.k00_numpar,  ";
                    $sCamposOrigemAbatimento .= " arreckey.k00_receit,  ";
                    $sCamposOrigemAbatimento .= " tabrec.k02_descr,     ";
                    $sCamposOrigemAbatimento .= " arreckey.k00_hist,    ";
                    $sCamposOrigemAbatimento .= " histcalc.k01_descr,   ";
                    $sCamposOrigemAbatimento .= " arreckey.k00_tipo,    ";
                    $sCamposOrigemAbatimento .= " arretipo.k00_descr    ";

                    $sOrdemCampos             = " arreckey.k00_numpre,  ";
                    $sOrdemCampos            .= " arreckey.k00_numpar,  ";
                    $sOrdemCampos            .= " arreckey.k00_receit   ";

                    $sWhereOrigemAbatimento   = "abatimentoarreckey.k128_abatimento = {$iAbatimento}";
                    $sSqlOrigemAbatimento     = $clAbatimentoArreckey->sql_query(null,$sCamposOrigemAbatimento,$sOrdemCampos,$sWhereOrigemAbatimento);

                  }


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