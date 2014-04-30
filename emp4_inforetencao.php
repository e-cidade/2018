<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_retencaotiporec_classe.php");
require("model/agendaPagamento.model.php");
require("model/retencaoNota.model.php");
require("classes/empenho.php");

$oGet = db_utils::postMemory($_GET);
$clrotulo        = new rotulocampo;
$clrotulo->label("e69_numero");
$clrotulo->label("e69_codnota");
$clrotulo->label("e50_codord");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_nome");
$clrotulo->label("e70_valor");
$clrotulo->label("e70_vlrliq");
$clrotulo->label("e70_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e21_sequencial");
$clrotulo->label("e21_descricao");
$clrotulo->label("e21_aliquota");
$clrotulo->label("e23_valorbase");
$clrotulo->label("e23_deducao");
$clrotulo->label("e23_valorretencao");
$sMes      = date("m",db_getsession("DB_datausu"));
$sAno      = date("Y",db_getsession("DB_datausu"));
 if (isset($oGet->iTipoCalculo)) {

   if ($oGet->iTipoCalculo == 1) {
     
     $sSqlNotas  = "select e50_codord, ";
     $sSqlNotas .= "       e50_data,   ";
     $sSqlNotas .= "       e69_numero, ";
     $sSqlNotas .= "       e69_codnota, ";
     $sSqlNotas .= "       e53_valor,  ";
     $sSqlNotas .= "       e53_vlrpag,  ";
     $sSqlNotas .= "       fc_valorretencaonota(e50_codord) as valorretencao  ";
     $sSqlNotas .= "  from pagordem    ";
     $sSqlNotas .= "       inner join pagordemele  on e53_codord  = e50_codord  ";
     $sSqlNotas .= "       inner join pagordemnota on e53_codord  = e71_codord  ";
     $sSqlNotas .= "       inner join empnota      on e71_codnota = e69_codnota ";
     $sSqlNotas .= " where e50_codord = {$oGet->sValorFiltro}                   ";
          

   } else if ($oGet->iTipoCalculo == 2) {
     
     $sSqlNotas  = "select distinct e50_codord,";
     $sSqlNotas .= "       e50_data,   ";
     $sSqlNotas .= "       e69_numero, ";
     $sSqlNotas .= "       e69_codnota, ";
     $sSqlNotas .= "       e53_valor,  ";
     $sSqlNotas .= "       fc_valorretencaonota(e50_codord) as valorretencao,  ";
     $sSqlNotas .= "       e53_vlrpag ";
     $sSqlNotas .= "  from pagordem   ";
     $sSqlNotas .= "       inner join pagordemele  on  e53_codord = e50_codord";
     $sSqlNotas .= "       inner join empempenho   on  e50_numemp = e60_numemp";
     $sSqlNotas .= "       inner join cgm          on  e60_numcgm = z01_numcgm";
     $sSqlNotas .= "       inner join pagordemnota on e53_codord  = e71_codord  ";
     $sSqlNotas .= "       inner join empnota      on e71_codnota = e69_codnota ";
     $sSqlNotas .= "       left outer join conlancamord on  c80_codord = e50_codord";
     $sSqlNotas .= "       left  join pagordemconta    on e49_codord           = e50_codord  ";
     $sSqlNotas .= "       left  join cgm cgmordem     on e49_numcgm           = cgmordem.z01_numcgm  ";
     $sSqlNotas .= "   where (case when e49_numcgm is null then cgm.z01_cgccpf = '{$oGet->sValorFiltro}'  ";
     $sSqlNotas .= "        else cgmordem.z01_cgccpf = '{$oGet->sValorFiltro}' end)                      ";
     $sSqlNotas .= "  and ((extract(year from c80_data) = {$sAno}";
     $sSqlNotas .= "      and extract (month from c80_data) = {$sMes})";
     $sSqlNotas .= "  or (extract( year from e50_data) = {$sAno}";
     $sSqlNotas .= "      and extract (month from e50_data) = {$sMes})";
     $sSqlNotas .= "     or e69_codnota = {$oGet->iCodNota})";
     
   }
   $oDaoPagOrdem = db_utils::getDao("pagordem");
   $rsNotas      = $oDaoPagOrdem->sql_record($sSqlNotas);
   if ($oDaoPagOrdem->numrows > 0) {
     $aNotas = db_utils::getColectionByRecord($rsNotas);
   }
 }
 $oRetencao->aMovimentos = array();
 if (isset($_SESSION["retencaoNota{$oGet->iCodNota}"][$oGet->iCodRetencao])) {
   $oRetencao = $_SESSION["retencaoNota{$oGet->iCodNota}"][$oGet->iCodRetencao];
 }
 /**
  * Pesquisamos o tipo de calculo da retencao.
  * usamos como regra para definir quais movimentos deve fazer parte da base de calculo.
  *  - tipo 1 a 4 (IR e INSS)- Sempre vira marcado o movimento atual, e o movimentos pagos.
  *  - tipo 5 - Outros (ISS) - Marcamos apenas o valor do movimento.
  */
 $oDaoRetencaoTiporec = new cl_retencaotiporec;
 $sSqlTipoCalculo     = $oDaoRetencaoTiporec->sql_query_file($oGet->iCodRetencao,"e21_retencaotipocalc");
 $rsTipoCalculo       = $oDaoRetencaoTiporec->sql_record($sSqlTipoCalculo);
 $iTipoCalculo        = db_utils::fieldsMemory($rsTipoCalculo, 0)->e21_retencaotipocalc;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top: 10px">
  <center>
    <form name='form1'>
    <table width="80%">
    <tr><td>
    <fieldset>
    <table cellspacing="0" cellpadding="0" style='width: 100%; background-color:white;border-collapse: collapse'>
    <tr>
      <td class='table_header'>Mov</td>
      <td class='table_header'>Nota</td>
      <td class='table_header'>Valor</td>
      <td class='table_header'>Data Movimento</td>
      <td class='table_header'>Valor Base</td>
      <td class='table_header'>Valor Retido</td>
      <td class='table_header' width='5%'>&nbsp;</td>
    </tr>
      <?
      $sJoin    = " left join empagenotasordem  on e81_codmov         = e43_empagemov   ";
      $sJoin   .= " left join empageordem       on e43_ordempagamento = e42_sequencial  ";
      if (isset($aNotas)) {
        
        $nValorPago        = 0;
        $nValorAcrescentar = 0;
        $nValorNota        = 0;
        for ($iNota = 0; $iNota < count($aNotas); $iNota++) {
          
          //$nValorRetencao = 0;
          $oAgenda = new agendaPagamento();
          $sWhere  = " e50_codord= {$aNotas[$iNota]->e50_codord} ";
          $sWhere .= " and e71_anulado is false";
          $sWhere .= " and (k12_data is null ";
          $sWhere .= " or (extract (year from k12_data) = {$sAno}  ";
          $sWhere .= "     and extract (month from k12_data) = {$sMes})) ";
          $sWhere .= " and (e81_cancelado is null and case when k12_data is not null then k105_corgrupotipo = 1 else true end)";
          $aMovimentos = $oAgenda->getMovimentosAgenda($sWhere, $sJoin, false, false);
          for ($iMov = 0; $iMov < count($aMovimentos); $iMov++) {
            
            
            $oRetencaoNota = new retencaoNota($aNotas[$iNota]->e69_codnota);
            /*
             * Verificamos se o movimento já possui retencoes para o mes corrente, ou se já foi usado em outra retencao
             */
            $lRecolhido = false;
            if ($aMovimentos[$iMov]->k12_data != "") {
              
              $lRecolhido = true;
            }
            $oRetencaoMovimento = $oRetencaoNota->getRetencoesByMovimento($aMovimentos[$iMov]->e81_codmov,
                                                                          $oGet->iCodRetencao,
                                                                          $lRecolhido,true);

            $sChecked       = "";
            $nValorRetencao = 0;
            /**
             * Retencao já usada como base em outro calculo.
             * Não podemos usar esse movimento como base de calculo novamente.
             */
            if ($oRetencaoMovimento && $oGet->iTipoCalculo == 1) {
              
              $sChecked       = " checked disabled ";
              //$nValorRetencao = $oRetencaoMovimento->e23_valorretencao;
              
            }
            if ($oRetencaoMovimento) {
              $nValorRetencao = $oRetencaoMovimento->e23_valorretencao;
            }
            
            if (is_object($oRetencao->aMovimentos) && in_array($aMovimentos[$iMov]->e81_codmov, $oRetencao->aMovimentos)) {
              
              $sChecked = " checked ";
              $nValorAcrescentar += $aMovimentos[$iMov]->e81_valor;
              
            }
            if ($aMovimentos[$iMov]->k12_data != "") {
              $nValorPago+= $aMovimentos[$iMov]->e81_valor;
              echo "  <tr style='background-color: #FFFFCC'>";
              
            } else {
              echo "<tr>";
            }
            echo "  <td style='text-align:center;border-right:1px solid black' id='iCodMov'>{$aMovimentos[$iMov]->e81_codmov}</td>";
            echo "  <td style='text-align:center;border-right:1px solid black'>{$aNotas[$iNota]->e69_numero}</td>";
            echo "  <td style='text-align:right;border-right:1px solid black'>{$aMovimentos[$iMov]->e81_valor}</td>";
            echo "  <td style='text-align:center;border-right:1px solid black'>".db_formatar($aMovimentos[$iMov]->e80_data,"d")."</td>";
            if ($aMovimentos[$iMov]->k12_data != "" && $iTipoCalculo != 5) {
              
              echo "  <td id='valorpago{$aMovimentos[$iMov]->e81_codmov}'";
              echo "   style='text-align:right;border-right:1px solid black'>{$aMovimentos[$iMov]->e81_valor}</td>";
              $sChecked = " checked disabled ";
              
            } else {
              
              $nValor = $aMovimentos[$iMov]->e81_valor;
              if ($aMovimentos[$iMov]->e43_valor > 0) {
                $nValor = $aMovimentos[$iMov]->e43_valor;
              }
              echo "  <td id='valorpago{$aMovimentos[$iMov]->e81_codmov}'"; 
              echo "      style='text-align:right;border-right:1px solid black'>{$nValor}</td>";
            }
            if ($aMovimentos[$iMov]->e81_codmov == $oGet->iCodMov) {
              
              $nValorAcrescentar += $aMovimentos[$iMov]->e81_valor;
              $nValorNota         = $aMovimentos[$iMov]->e81_valor;
              $sChecked = " checked disabled ";
              
            }
            
            echo "<td style='text-align:right;border-right:1px solid black'>{$nValorRetencao}</td>";
            echo "  <td class=''>";
            echo "     <input class='retencao' type='checkbox' onclick='calculaValor(this.checked,this.value,{$nValorRetencao})'";
            echo "             value='{$aMovimentos[$iMov]->e81_codmov}' {$sChecked} style='height:12px'>";
            echo "  </td>";
            echo "</tr>\n";
          }
        }
        echo "<tr ><td style='border-top:1px solid black' colspan='2'><b>Valor da Nota</b></td>";
        echo "    <td colspan='2' style='border-top:1px solid black;text-align:right' id='valorNota'>{$nValorNota}</td>";
        echo "    <td style='border-top:1px solid black;border-left:1px solid black' ><b>Acrescentar</b></td>";
        echo "    <td colspan='2' style='border-top:1px solid black;text-align:right' id='valorTotal'>".($nValorAcrescentar)."</td>";
        echo "</tr>";
        echo "<tr>";
        echo "    <td style='border-top:1px solid black' colspan='2'><b>Valores Pagos no mes</b></td>";
        echo "    <td colspan='2' style='border-top:1px solid black;text-align:right' id='valorAcrescentar'>{$nValorPago}</td>";
        echo "    <td colspan='3' style='border-top:1px solid black;'>&nbsp;</td>";
        echo "</tr>";
        echo "<tr>";
        echo "    <td style='border-top:1px solid black' colspan='2'><b>Base Utilizada</b></td>";
        echo "    <td colspan='2' style='border-top:1px solid black;text-align:right' id='valorbase'>".($nValorPago+$nValorNota)."</td>";
        echo " <td style='border-top:1px solid black' colspan='3'>&nbsp;</td></tr>";
      }
      ?>
    </table>
    </fieldset>
    </td>
    </tr>
    </table>
    <input type='button' onclick='js_setValorBase()' value='Confirmar'>
    </form>
    <?

      if ($oGet->iTipoCalculo == 2) {
      
        echo "** O cálculo de retenção para pessoa física, considera como padrão os valores pagos dentro do mês.<br>";
        echo "Caso necessário, podem ser acrescentadas à base de cálculo as demais notas disponíveis nesta tela.";
      
      }
    ?>
    
    <div style='text-align:left'>
       <fieldset><legend><b>Legenda</b></legend>
       <span style='background-color: #FFFFCC'>&nbsp;&nbsp;</span> Valores Pagos<br>
       <span style='background-color: white'>&nbsp;&nbsp;</span> Movimentos a pagar
       </fieldset>
    </div>
   </center>
    </body>
</html>
<script>

  function calculaValor(lSomar, iChave, nJaRetido) {
  
    var nValor      = new Number($('valorpago'+iChave).innerHTML);
    var nValorTotal = new Number($('valorTotal').innerHTML); 
    var nValorBase  = new Number($('valorbase').innerHTML); 
    if (lSomar) {
      
      nValorTotal  = nValorTotal+nValor;
      nValorBase  += nValor;
      
    } else {
    
      nValorTotal  = nValorTotal-nValor;
      nValorBase  -= nValor;
      
    }
    $('valorTotal').innerHTML = nValorTotal.toFixed(2);  
    $('valorbase').innerHTML  = nValorBase.toFixed(2);  
  } 
  
  function js_setValorBase() {
  
    var aBaseDeCalculo = new Array();
    var aMov = js_getElementbyClass(form1,"retencao");
    for ( var i = 0; i < aMov.length; i++) {
    
      if (aMov[i].checked && !aMov[i].disabled) {
        aBaseDeCalculo.push(aMov[i].value);
      }
    }
    parent.setBaseDeCaculo(aBaseDeCalculo, <?=$oGet->iCodRetencao?>);  
    parent.$('e23_valorbase').value = $('valorTotal').innerHTML;
    parent.$('valorpagar').value    = $('valorTotal').innerHTML;
    parent.js_calculaRetencao();
    parent.db_iframe_inforetencao.hide();
  
  }
</script>