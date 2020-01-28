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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcorcamitem_classe.php");
require_once("classes/db_pcorcamjulg_classe.php");
require_once("classes/db_pcorcamval_classe.php");
require_once("classes/db_liclicitemlote_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_empparametro_classe.php");

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$clpcorcamitem    = new cl_pcorcamitem;
$clpcorcamjulg    = new cl_pcorcamjulg;
$clpcorcamval     = new cl_pcorcamval;
$clliclicitemlote = new cl_liclicitemlote;
$clliclicita      = new cl_liclicita;
$clempparametro   = new cl_empparametro;

$db_opcao  = 1;
$db_tranca = 1;
$db_botao  = true;
$erro      = false;
$erro_msg  = "";
$numrows_pcorcamitem = 0;
$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_numdec"));
if ($clempparametro->numrows > 0) {

  db_fieldsmemory($res_empparametro,0);
  if (trim($e30_numdec) == "" || $e30_numdec == 0) {
    $numdec = 2;
  } else {
    $numdec = $e30_numdec;
  }
} else {
  $numdec = 2;
}


if(isset($orcamento) && trim($orcamento)!="") {

  /**
   * Buscamos as licitacoes referentes ao orcamento
   */
  $sCamposItensLicitacao = "distinct l20_tipojulg, pc22_orcamitem, pc21_orcamforne, l20_usaregistropreco, l21_codliclicita,";
  $sCamposItensLicitacao .= "l20_formacontroleregistropreco";
  $sWhereItensLicitacao  = "pc22_codorc={$orcamento} and pc23_orcamitem is not null and pc32_orcamitem is null";
  $sSqlItensLicitacao    = $clpcorcamitem->sql_query_pcmaterlic(null, 
                                                                $sCamposItensLicitacao, 
                                                                "pc21_orcamforne, pc22_orcamitem",
                                                                $sWhereItensLicitacao
                                                               );
  $result_pcorcamitem  = $clpcorcamitem->sql_record($sSqlItensLicitacao);
  $numrows_pcorcamitem = $clpcorcamitem->numrows;
  
  if ($clpcorcamitem->numrows > 0) {

    db_fieldsmemory($result_pcorcamitem,0);

    //verifica se a licitacao é global, todos itens tem que estarem lançados no minimo para um fornecedor
    if ($l20_tipojulg == 2) {
      
      $prob                = true;
      $verifica_itens      = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_itens($orcamento,"pc22_orcamitem as item"));
      $total_item          = $clpcorcamitem->numrows;
      $verifica_itens_julg = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_verifica_global($orcamento));
      
      for ($i = 0; $i < $clpcorcamitem->numrows; $i++) {
        
        db_fieldsmemory($verifica_itens,$i);
      
        for ($j = 0; $j < $clpcorcamjulg->numrows; $j++) {
          
          db_fieldsmemory($verifica_itens_julg,$j);
          if ( $pc23_orcamitem == $item && $pc23_orcamitem != null) {
            
            if (isset($itensforne[$pc21_numcgm])) {
              $itensforne[$pc21_numcgm] += 1;
            } else {
              $itensforne[$pc21_numcgm] = 1;
            }
            
            if ($total_item == $itensforne[$pc21_numcgm]) {
              $prob = false;
            }
          }  
        }
      }
      
      if ($clpcorcamjulg->numrows > 0 ) {
          
        if ($prob == true) {
          
          db_msgbox("Existem itens sem valores lançados na licitação. Por ser uma licitação do tipo global,pelo menos um fornecedor deverá ter todos os itens com valor lançado.");
          echo "<script>parent.document.form1.confirmar.disabled = true;</script>";
          exit;
        }
      }  
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////       
    // Verifica se já existe julgamento para licitacao
    $sql_julg  = "select distinct pc24_orcamitem, pc24_orcamforne                                                    \n";
    $sql_julg .= "  from pcorcamjulg                                                                                 \n";
    $sql_julg .= "       left  join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamjulg.pc24_orcamitem     \n";
    $sql_julg .= "       inner join liclicitem     on liclicitem.l21_codigo         = pcorcamitemlic.pc26_liclicitem \n";
    $sql_julg .= "       left  join pcorcamval     on pcorcamval.pc23_orcamitem     = pcorcamjulg.pc24_orcamitem     \n";
    $sql_julg .= "       left  join pcorcamdescla  on pcorcamdescla.pc32_orcamitem  = pcorcamval.pc23_orcamitem      \n";
    $sql_julg .= "                                and pcorcamdescla.pc32_orcamforne = pcorcamval.pc23_orcamforne     \n";
    $sql_julg .= " where l21_situacao = 0                                                                            \n";
    $sql_julg .= "   and pc32_orcamitem is null                                                                      \n";
    $sql_julg .= "   and pc32_orcamforne is null                                                                     \n";
    $sql_julg .= "   and l21_codliclicita = $l21_codliclicita                                                        \n";
    $res_julg  = $clliclicitemlote->sql_record($sql_julg);
    
    if ($clliclicitemlote->numrows > 0) {
      
      $numrows   = $clliclicitemlote->numrows;
      $db_tranca = 1;
      $flag_julg = true;
    } else {
      
      $db_tranca = 3;
      $flag_julg = false;
    }
   
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////       
    // Itens cotados por Lote
    $sql_lote   = "select count(l04_descricao) as tot_itens,                                              \n";
    $sql_lote  .= "       l04_descricao                                                                   \n";
    $sql_lote  .= "  from (                                                                               \n";
    $sql_lote  .= "        select pc23_orcamitem,                                                         \n";
    $sql_lote  .= "               pc32_orcamforne,                                                        \n";
    $sql_lote  .= "               l04_descricao,                                                          \n";
    $sql_lote  .= "               pc22_orcamitem                                                          \n";
    $sql_lote  .= "          from pcorcamitem                                                             \n";
    $sql_lote  .= "               inner join pcorcamitemlic on pc26_orcamitem  = pc22_orcamitem           \n";
    $sql_lote  .= "               inner join liclicitemlote on l04_liclicitem  = pc26_liclicitem          \n";
    $sql_lote  .= "               inner join liclicitem     on l21_codigo      = pc26_liclicitem          \n";
    $sql_lote  .= "               left  join pcorcamval     on pc23_orcamitem  = pc22_orcamitem           \n";
    $sql_lote  .= "               left  join pcorcamdescla  on pc32_orcamitem  = pc23_orcamitem           \n";
    $sql_lote  .= "                                        and pc32_orcamforne = pc23_orcamforne          \n";
    $sql_lote  .= "         where l21_situacao = 0                                                        \n";
    $sql_lote  .= "           and pc32_orcamitem is null                                                  \n";
    $sql_lote  .= "           and pc32_orcamforne is null                                                 \n";
    $sql_lote  .= "           and l21_codliclicita = $l21_codliclicita                                    \n";
    $sql_lote  .= "           and pc23_valor > 0                                                          \n";
    $sql_lote  .= "         group by pc23_orcamitem, pc32_orcamforne, l04_descricao, pc22_orcamitem) as x \n";
    $sql_lote  .= "group by l04_descricao, pc32_orcamforne                                                \n";
    $sql_lote  .= "order by l04_descricao, pc32_orcamforne                                                \n";
    $res_lote   = $clliclicitemlote->sql_record($sql_lote);

    if ($clliclicitemlote->numrows > 0) {
      
      $numrows_lote = $clliclicitemlote->numrows;
    
      for ($i = 0; $i < $numrows_lote; $i++) {
        
        db_fieldsmemory($res_lote,$i);
        $vetor_lote[$l04_descricao] = $tot_itens;
      }
    }
    
    // Traz quantidade de itens para verificar se fornecedor foi cotado em todos os itens da licitacao
    $sql_itens  = "select count(l04_descricao) as itens_cotados,                                          \n";
    $sql_itens .= "       pc23_orcamforne,                                                                \n";
    $sql_itens .= "       l04_descricao                                                                   \n";
    $sql_itens .= "  from (                                                                               \n";
    $sql_itens .= "        select pc23_orcamitem,                                                         \n";
    $sql_itens .= "               pc23_orcamforne,                                                        \n";
    $sql_itens .= "               l04_descricao,                                                          \n";
    $sql_itens .= "               pc22_orcamitem                                                          \n";
    $sql_itens .= "          from pcorcamitem                                                             \n";
    $sql_itens .= "               inner join pcorcamitemlic on pc26_orcamitem  = pc22_orcamitem           \n";
    $sql_itens .= "               inner join liclicitemlote on l04_liclicitem  = pc26_liclicitem          \n";
    $sql_itens .= "               inner join liclicitem     on l21_codigo      = pc26_liclicitem          \n";
    $sql_itens .= "               left  join pcorcamval     on pc23_orcamitem  = pc22_orcamitem           \n";
    $sql_itens .= "               left  join pcorcamdescla  on pc32_orcamitem  = pc23_orcamitem           \n";
    $sql_itens .= "                                        and pc32_orcamforne = pc23_orcamforne          \n";
    $sql_itens .= "         where l21_situacao = 0                                                        \n";
    $sql_itens .= "           and pc32_orcamitem is null                                                  \n";
    $sql_itens .= "           and pc32_orcamforne is null                                                 \n";
    $sql_itens .= "           and pc23_orcamitem is not null                                              \n";
    $sql_itens .= "           and pc23_orcamforne is not null                                             \n";
    $sql_itens .= "           and l21_codliclicita = $l21_codliclicita                                    \n";
    $sql_itens .= "         group by pc23_orcamitem, pc23_orcamforne, l04_descricao, pc22_orcamitem) as x \n";
    $sql_itens .= "group by l04_descricao,pc23_orcamforne                                                 \n";
    $sql_itens .= "order by l04_descricao,pc23_orcamforne                                                 \n";
    $res_itens  = $clliclicitemlote->sql_record($sql_itens);
    
    $numrows      = $clliclicitemlote->numrows;
    $retira_forne = array(array());
    $lote_antes   = "";
    $linha        = 0;
    $tot_itens    = 0;
    $orcamforne   = 0;
    $i            = 0;

    if ($clliclicitemlote->numrows > 0 && $flag_julg == false) {
      
      db_fieldsmemory($res_itens,$i);
      $lote_antes = $l04_descricao;
      $tot_itens  = $itens_cotados;
      $orcamforne = $pc23_orcamforne;
      $i++;
      
      do {
        
        if ($i >= $numrows) {
          break;
        }
        
        db_fieldsmemory($res_itens,$i);

        if ($lote_antes == $l04_descricao) {

          if ($tot_itens > $itens_cotados) {
            
            $retira_forne[$linha]["orcamforne"] = trim($pc23_orcamforne);
            $retira_forne[$linha]["lote"]       = trim($l04_descricao);
            $linha++;
          } else {
            
            if (count($vetor_lote) > 0 && trim($vetor_lote[$l04_descricao]) != "") {
              
              if ($tot_itens < trim($vetor_lote[$l04_descricao])) {
                
                $encontrar = false;
                for ($ii = 0; $ii < sizeof($retira_forne); $ii++) {
                  
                  if (isset($retira_forne[$ii]["orcamforne"]) && $retira_forne[$ii]["lote"]) {

                    if (@$retira_forne[$ii]["orcamforne"] == trim(@$orcamforne) &&
                        @$retira_forne[$ii]["lote"]       == trim(@$lote_antes)) {
                      
                      $encontrar = true;
                      break;
                    }
                  }
                }

                if ($encontrar == false) {

                  $retira_forne[$linha]["orcamforne"] = trim($orcamforne);
                  $retira_forne[$linha]["lote"]       = trim($lote_antes);
                  $linha++;
                }

              }
            }
          }
        } else {
          
          $lote_antes = $l04_descricao;
          $tot_itens  = $itens_cotados;
          $orcamforne = $pc23_orcamforne;
        }
        $i++;
      } while ($i < $numrows);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////      
    // Traz o valor menor por lote dos fornecedores
    $sql_valor  = "select l04_descricao, pc23_orcamforne, sum(pc23_valor) as pc23_valor  \n";
    $sql_valor .= "  from pcorcamval                                                     \n";
    $sql_valor .= "       inner join pcorcamitemlic on pc26_orcamitem  = pc23_orcamitem  \n";
    $sql_valor .= "       inner join liclicitemlote on l04_liclicitem  = pc26_liclicitem \n";
    $sql_valor .= "       inner join liclicitem     on l21_codigo      = pc26_liclicitem \n";
    $sql_valor .= "       left  join pcorcamdescla  on pc32_orcamitem  = pc23_orcamitem  \n";
    $sql_valor .= "                                and pc32_orcamforne = pc23_orcamforne \n";
    $sql_valor .= " where l21_situacao = 0                                               \n";
    $sql_valor .= "   and pc32_orcamitem is null                                         \n";
    $sql_valor .= "   and pc32_orcamforne is null                                        \n";
    $sql_valor .= "   and l21_codliclicita = $l21_codliclicita                           \n";
    $sql_valor .= " group by l04_descricao, pc23_orcamforne                              \n";
    $sql_valor .= " order by l04_descricao, pc23_valor, pc23_orcamforne                  \n";
    $res_valor  = $clliclicitemlote->sql_record($sql_valor);
    
    $valor_menor   = array(array());
    $linha_valor   = 0;
    $numrows_valor = $clliclicitemlote->numrows;

    if ($clliclicitemlote->numrows > 0 && $flag_julg == false) {
      
      for ($i = 0; $i < $numrows_valor; $i++) {
        
        $flag_retira = false;
        $flag_menor  = true;
        db_fieldsmemory($res_valor,$i);
        
        for ($ii = 0; $ii < $linha; $ii++) {
          
          if ($pc23_orcamforne == $retira_forne[$ii]["orcamforne"] &&
              $l04_descricao   == $retira_forne[$ii]["lote"]) {
            
            $flag_retira = true;
            break;
          }
        }

        if ($flag_retira == false) {
          
          for($ii = 0; $ii < $linha_valor; $ii++) {
            
            if ($l04_descricao == $valor_menor[$ii]["lote"]) {
              
              $flag_menor = false;
              break;
            }
          }
    
          if ($flag_menor == true) {
            
            $valor_menor[$linha_valor]["orcamforne"] = $pc23_orcamforne;
            $valor_menor[$linha_valor]["lote"]       = $l04_descricao;

            $linha_valor++;
          }
        }
      }
    }

    if ($l20_tipojulg != 1) {
      $ordem = "l04_descricao,l21_ordem,pc23_orcamitem,pc23_orcamforne";
    } else {
      $ordem = "l21_ordem,l04_descricao,pc23_orcamitem,pc23_orcamforne";
    }
    $sCamposJulgamento  = "l21_ordem, pc81_codprocitem, pc23_orcamitem, pc23_orcamforne, pc01_codmater, pc01_descrmater";
    $sCamposJulgamento .= ", pc23_obs, z01_numcgm, z01_nome, pc23_quant, pc23_vlrun, pc23_valor, pc11_resum";
    $sCamposJulgamento .= ", l04_descricao, l20_tipojulg, pc11_vlrun as valor_registro_preco, pc23_percentualdesconto ";
    $sWhereJulgamento   = "    pc32_orcamitem is null and pc32_orcamforne is null and l21_situacao = 0 ";
    $sWhereJulgamento  .= "and l20_codigo = $l21_codliclicita ";
    $sSqlJulgamento     = $clliclicitemlote->sql_query_julgamento(null, $sCamposJulgamento, $ordem, $sWhereJulgamento);
    $res_liclicitemlote = $clliclicitemlote->sql_record($sSqlJulgamento);
    if ($clliclicitemlote->numrows > 0){
    
      $numrows_pcorcamitem = $clliclicitemlote->numrows;
      db_fieldsmemory($res_liclicitemlote,0);
      $tipojulg = $l20_tipojulg;
    } else {
      $numrows_pcorcamitem = 0;
    }
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas {

  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}

.bordas02 {

  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2">
<?
  db_input("orcamento", 10,"",true,"hidden",3);
  db_input("orcamforne",10,"",true,"hidden",3);
?>
<center>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC"> 
    <?
    if ($numrows_pcorcamitem == 0) {
      
      echo "<strong>Não existem itens para julgamento.</strong>\n";
      echo "<script>parent.document.form1.confirmar.disabled = true;</script>";
    } else {
   
      $bordas = "bordas";
      
      echo "<center>";
      echo "<table border='0' align='center'>\n";
      echo "<tr>";
      echo "  <td colspan='9' nowrap align='center' height='30'>";
      echo "    <strong>";
      echo "      <font size='3'>Julgamento de Itens da Licitação N".chr(176)." ".$l21_codliclicita."</font>";
      echo "    </strong>";
      echo " </td>";
      echo "</tr>";
      
      if ($tipojulg == 3) {
        
        echo "<tr>\n";
        echo "  <td colspan='9' nowrap class='bordas02' align='left'><strong>Lote</strong></td>\n";
        echo "</tr>\n";
      }
      echo "<tr bgcolor=''>\n";
      if ($tipojulg == 1) {
        echo "  <td nowrap class='bordas02' align='center'><strong>Trocar</strong></td>\n";
      }
      echo "  <td nowrap class='bordas02' align='center'><strong>Item</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Seq. Item</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Material</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Obs</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Fornecedor</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Quantidade.</strong></td>\n";
      if ($l20_usaregistropreco == "t" && $l20_formacontroleregistropreco == 1) {
        
        echo "  <td nowrap class='bordas02' align='center'><strong>Quant Min.</strong></td>\n";
        echo "  <td nowrap class='bordas02' align='center'><strong>Quant Max.</strong></td>\n";
        
      }
      if ($l20_formacontroleregistropreco == 2) {
        echo "  <td nowrap class='bordas02' align='center'><strong>Percentual Desconto</strong></td>\n";
      } else {
        echo "  <td nowrap class='bordas02' align='center'><strong>Valor Unit.</strong></td>\n";
      }
      echo "  <td nowrap class='bordas02' align='center'><strong>Valor Tot.</strong></td>\n";
      echo "  <td nowrap class='bordas02' align='center'><strong>Resumo</strong></td>\n";
      echo "</tr>\n";
    

      $vitens      = array(array());
      $cont_itens  = 0;
      
      $descrlote   = "";

      $flag_global = false;

      for ($i = 0; $i < $numrows_pcorcamitem; $i++) {
        
        db_fieldsmemory($res_liclicitemlote,$i);
        $flag = true;

        for ($ii = 0; $ii < $linha_valor; $ii++) {
          
          if ($valor_menor[$ii]["orcamforne"] == $pc23_orcamforne &&
              $valor_menor[$ii]["lote"]       == $l04_descricao){
            
            $flag = false;
            break;
          }
        }
        
        if ($flag_julg == true) {
          
          $sWhereOrcamJulg = "pc24_orcamitem = $pc23_orcamitem and pc24_orcamforne = $pc23_orcamforne";
          $sSqlOrcamJulg   = $clpcorcamjulg->sql_query(null, null, "distinct pc24_orcamforne", "", $sWhereOrcamJulg);
          $res_fornec      = $clpcorcamjulg->sql_record($sSqlOrcamJulg);
          if ($clpcorcamjulg->numrows > 0) {
            $flag = false;
          } else{
            $flag = true;
          }
        }

        if ($flag == false){
          
          if ($tipojulg == 3) {
            
            if ($descrlote != $l04_descricao) {
              
              $descrlote = $l04_descricao;
              echo "<tr>\n<td colspan='9' nowrap class='$bordas' align='left' height='30'><b>$l04_descricao</b>&nbsp;&nbsp;";
              db_ancora("Trocar","js_troca('$l04_descricao',$orcamento,$pc23_orcamforne);",$db_tranca);
              echo "</td>\n</tr>\n";
            }
          }

          echo "<tr>\n";

          if ($tipojulg == 1) {
            
            echo "  <td nowrap class='$bordas' align='center' title='Clique para efetuar troca de fornecedor do item'>";
            db_ancora("Trocar","js_troca('$l04_descricao',$orcamento,$pc23_orcamforne);",$db_tranca);
            echo "  </td>\n";
          }
          
          $vitens[$cont_itens]["item"]  = $pc23_orcamitem;
          $vitens[$cont_itens]["forne"] = $pc23_orcamforne;

          $cont_itens++;

          echo "  <td nowrap class='$bordas' align='center' >".$pc81_codprocitem."</td>";
          echo "  <td nowrap class='$bordas' align='center' >".$l21_ordem."</td>";
          echo "  <td class='$bordas' align='left' >  ".$pc01_codmater." - ".ucfirst(strtolower($pc01_descrmater))."</td>\n";
	        echo "  <td class='$bordas' align='left' >  ".(strlen($pc23_obs) > 0?ucfirst(strtolower($pc23_obs)):"&nbsp;")."</td>\n";
	        echo "  <td class='$bordas' align='center' >".$z01_numcgm." - ".$z01_nome;

          if ($tipojulg == 2) { 

            if ($flag_global == false) {
              echo "&nbsp;&nbsp;&nbsp;&nbsp;".db_ancora("Trocar","js_troca('$l04_descricao',$orcamento,$pc23_orcamforne);",$db_tranca);
            }
            $flag_global = true;
          }

          echo "  </td>\n";
	        echo "  <td nowrap class='$bordas' align='center' >$pc23_quant</td>\n";
          if ($l20_usaregistropreco == "t" && $l20_formacontroleregistropreco == 1) {
     
            $sSqlQuantidades = $clpcorcamitem->sql_query_pcmaterregistro($pc22_orcamitem,"pc57_quantmin,pc57_quantmax");
            $rsQuantidades   = $clpcorcamitem->sql_record($sSqlQuantidades);
            $nQuantMin = 0;
            $nQuantMax = 0;
            if ($clpcorcamitem->numrows > 0) {
              
              $oInfoRegistroPreco = db_utils::fieldsMemory($rsQuantidades, 0);
              $nQuantMax          = $oInfoRegistroPreco->pc57_quantmax;
              $nQuantMin          = $oInfoRegistroPreco->pc57_quantmin;
            }
            echo "<td class='$bordas'>{$nQuantMin}</td>";
            echo "<td class='$bordas'>{$nQuantMax}</td>";        
            
          }

          if ($l20_formacontroleregistropreco == 2) {

            $pc23_vlrun = $valor_registro_preco;
            $pc23_valor = $valor_registro_preco;
            echo "  <td nowrap class='$bordas' align='right' >".db_formatar(($pc23_percentualdesconto),"f")."%</td>\n";
          } else {
            echo "  <td nowrap class='$bordas' align='right' >R$ " . db_formatar(($pc23_vlrun), "f", " ", ' ', "e", $numdec) . "</td>\n";
          }
	        echo "  <td nowrap class='$bordas' align='right' >R$ ".db_formatar(($pc23_valor),"f"," ",' ',"e",2)."</td>\n";
	        echo "  <td class='$bordas' align='left'>".(strlen($pc11_resum) > 0?substr(ucfirst($pc11_resum),0,40):"&nbsp;")."</td>\n";
	        echo "</tr>\n";
        }
      }

      $itens = "";
      for($x = 0; $x < $cont_itens; $x++){
        $itens .= $vitens[$x]["item"].", ".$vitens[$x]["forne"].": ";
      }

      db_input("itens",500,"",true,"hidden",3);

      echo "</table>\n";
      echo "</center>";
    }
    

    if ($erro == true) {
      db_msgbox($erro_msg);
    }



    ?>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_troca(lote,orcamento,orcamforne){
  top.corpo.document.location.href = 'lic1_trocpcorcamtroca001.php?lote='+lote
                                                                +'&orcamento='+orcamento
                                                                +'&orcamforne='+orcamforne
                                                                +'&l20_codigo=<?=$l21_codliclicita?>';
}
</script>
</body>
</html>