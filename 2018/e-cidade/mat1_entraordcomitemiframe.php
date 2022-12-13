<?
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_matordem_classe.php");
include ("classes/db_matordemitem_classe.php");
include ("classes/db_matordemitement_classe.php");
include ("classes/db_matestoqueitemoc_classe.php");
include ("classes/db_matestoqueitem_classe.php");
include ("classes/db_transmater_classe.php");
include ("classes/db_matunid_classe.php");
include ("classes/db_solicitem_classe.php");
include ("classes/db_empparametro_classe.php");
include ("dbforms/db_funcoes.php");

$clmatestoqueitem   = new cl_matestoqueitem;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatordemitem     = new cl_matordemitem;
$clmatordemitement  = new cl_matordemitement;
$clmatordem         = new cl_matordem;
$cltransmater       = new cl_transmater;
$clmatunid          = new cl_matunid;
$clsolicitem        = new cl_solicitem;
$clempparametro     = new cl_empparametro;

$clmatordemitem->rotulo->label();
$clmatordem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");
$clrotulo->label("e70_valor");
    
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$vlitement = 0;
$result_vlitement = $clmatordemitement->sql_record($clmatordemitement->sql_query_file());
if ($clmatordemitement->numrows != 0) {
  for ($y = 0; $y < $clmatordemitement->numrows; $y ++) {
    db_fieldsmemory($result_vlitement, 0);
    $vlitement += $m54_quantidade * $m54_valor_unitario;
  }
}

$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec"));
if ($clempparametro->numrows > 0){
  db_fieldsmemory($res_empparametro,0);
  $numdec = $e30_numdec;
} else {
  $numdec = 2;
}
$numdec = 2;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?//$cor="#999999"?>
.bordas{
  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
  /*         border: 2px solid #cccccc;
  border-top-color: <?=$cor?>;
  border-right-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  background-color: #999999;
  */	 
}
<?//$cor="999999"?>
.bordas_corp{
  /*       border: 1px solid #cccccc;
  border-right-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  */
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="parent.js_calcalancar();" > 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="left" valign="top" bgcolor="#CCCCCC"> 
<form name='form1' >
<center>
<table border='0' >   

<?



$conitens = 0;
$errosomaquant = "";

db_input('m51_codordem', 5, "", true, 'hidden', 3);

if (isset ($m51_codordem) && $m51_codordem != "") {
  $result = $clmatordemitem->sql_record($clmatordemitem->sql_query_servico(null, "*", "m52_codlanc", "m52_codordem=$m51_codordem"));
  $numrows = $clmatordemitem->numrows;
  if ($numrows > 0) {
    echo "<tr class='bordas'>
    <td class='bordas' align='center'><b><small>$RLe60_codemp</small></b></td>
    <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
    <td class='bordas' align='center'><b><small>$RLe62_descr</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_valor</small></b></td>
    <td class='bordas' align='center'><b><small>Valor Total</small></b></td>
    <td class='bordas' align='center'><b><small>$RLm52_quant</small></b></td>
    <td class='bordas' align='center'><b><small>Recebido</small></b></td>
    <td class='bordas' align='center'><b><small>Valor Rec.</small></b></td>
    <td class='bordas' align='center'><b><small>Unidade de Entrada</small></b></td>
    <td class='bordas' align='center'><b><small>Quant. Unid.</small></b></td>
    <td class='bordas' align='center'><b><small>Item de Entrada</small></b></td>
    <td class='bordas' align='center'><b><small>Receber</small></b></td>
    <td class='bordas' align='center'><b><small>Incluir Item de Entrada Novo</small></b></td>";
  } else
  echo "<b>Nenhum registro encontrado...</b>";
  echo "</tr>";
  for ($i = 0; $i < $numrows; $i ++) {
    db_fieldsmemory($result, $i);
    
    $e62_descr = str_replace(chr(10), " ", $e62_descr);
    
    $valortotal = db_formatar($m52_valor, 'p');
    $m52_valor  =  db_formatar($m52_valor, 'p');
    $m52_quant  =  db_formatar($m52_quant, 'p');
    $valoruni = db_formatar($m52_valor / $m52_quant, 'p');
    
    $result1 = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query_file(null, null, "*", "", "m73_codmatordemitem=$m52_codlanc"));
    $numrows1 = $clmatestoqueitemoc->numrows;
    if ($clmatestoqueitemoc->numrows != 0) {
      $valor_estval = 0;
      $quant_estitem = 0;
      for ($w = 0; $w < $numrows1; $w ++) {
        db_fieldsmemory($result1, $w);
        $result_busca_codestoque = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query_unid("", "m75_quant as  m71_quant,m71_valor, m71_codmatestoque as codestoque", "", "m71_codlanc=$m73_codmatestoqueitem"));
        db_fieldsmemory($result_busca_codestoque, 0);
        $quant_estitem += db_formatar($m71_quant, 'p');
        $valor_estval += db_formatar($m71_valor, 'p');
      }
      if ($pc01_servico == "f") {
        $quantidade = $m52_quant - $quant_estitem;
        $vlto = $m52_valor - $valor_estval;
      } else {
        $quantidade = 0;
        $vlto = $m52_valor - $valor_estval;
      }
      
      if (($quantidade == 0) && ($vlto == 0)) {
        $errosomaquant ++;
      } else {
        $quant_lanc = "";
        $result_lancaitens = $clmatordemitement->sql_record($clmatordemitement->sql_query_file(null, '*', null, "m54_codmatordemitem=$m52_codlanc"));
        if ($clmatordemitement->numrows != 0) {
          for ($y = 0; $y < $clmatordemitement->numrows; $y ++) {
            db_fieldsmemory($result_lancaitens, $y);
            $quant_lanc += $m54_quantidade;
          }
          $quantidade = $quantidade - $quant_lanc;
          $vlto = $quantidade * $valoruni;
        }
        if ($quantidade != 0 || $clmatordemitement->numrows != 0) {
          $conitens ++;
          $valor  = "total_$i";
          $$valor = db_formatar($vlto,"p");
          
          echo "<tr>
          <td class='bordas_corp' align='center'><small><a onClick=\"js_consemp($e60_numemp);\" id=\"cons_emp\" href=#>$e60_codemp</a></small></td>
          <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
          <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr, 0, 30)."&nbsp;</small></td>
          <td class='bordas_corp' align='right'><b><small>".db_formatar($valoruni, 'p', ' ', 4)."</small></b></td>";
          echo "    <td class='bordas_corp' align='right'><b><small>";
          db_input("total_$i", 10, 0, true, 'text', 3);
          echo "</small></b></td>";
          
          if ($pc01_servico == "f") {
            $val = "valor_$i";
            $quant = "quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
            if (isset ($zera)) {
              $$val = db_formatar('0', 'p');
              $$quant = '0';
            } else {
              $$quant = trim($quantidade);
              $$val = trim(db_formatar($vlto, 'p'));
            }
            echo "<td class='bordas_corp' align='center'><small>" . db_formatar($quantidade, 'p') . "</small></td>
            <td class='bordas_corp' align='center'><small>";
            db_input("quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i", 10, 0, true, 'text', 1, "onchange='js_verifica($quantidade,this.value,this.name," . db_formatar($valoruni, 'p') . ",$i);' ondblclick='js_zera(this.name,$i);' ");
            echo "</small></td>";
            echo "<td class='bordas_corp' align='center'><small>";
            $js_script = "onChange=js_recalcula('valor_".$i."','total_".$i."');";
            db_input("valor_$i", 10, 0, true, 'text', 1, $js_script);
            echo "</small></td>";
          } else {
            $val = "valor_$i";
            $quant = "quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
            if (isset ($zera)) {
              $$val = db_formatar('0', 'p');
              $$quant = '0';
            } else {
              $$quant = trim($m52_quant);
              $$val = trim(db_formatar($valortotal, 'p'));
            }
            $quantidade = db_formatar($m52_quant, 'p');
            echo "<td class='bordas_corp' align='center'><small>$quantidade</small></td>
            <td class='bordas_corp' align='center'><small>";
            db_input("quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i", 10, 0, true, 'text', 3);
            echo "</small></td>";
            echo " <td class='bordas_corp' align='center'><small>";
            $js_script = "onChange=js_recalcula('valor_".$i."','total_".$i."');";
            db_input("valor_$i", 10, 0, true, 'text', 1, $js_script);
            echo "</small></td>";
          }
          $q = "controle_$i";
          $$q = $m52_quant;
          db_input("controle_$i", 10, 0, true, 'hidden', 3);
          echo "<td class='bordas_corp' align='left' nowrap ><small>";
          
          $result_unid = $clmatunid->sql_record($clmatunid->sql_query_file(null, "case when m61_usaquant is true then to_char(m61_codmatunid,'99999') || 't' else to_char(m61_codmatunid,'99999') || 'f' end as m61_codmatunid, m61_abrev", "m61_codmatunid"));
          $couni = "codunid_$i";
          $$couni = '1f';
          $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_solunid(null, "pc17_unid,m61_usaquant,pc17_quant", null, "e62_sequen=$e62_sequen"));
          if ($clsolicitem->numrows > 0) {
            db_fieldsmemory($result_solicitem, 0);
            $$couni = $pc17_unid.$m61_usaquant;
          }
          echo " <select onChange='js_unid(this.value,$i);'  name='codunid_$i' id='codunid_$i'>";
          for ($y = 0; $y < $clmatunid->numrows; $y ++) {
            db_fieldsmemory($result_unid, $y);
            echo "<option value=\"$m61_codmatunid\" ". (isset ($couni) ? ($$couni == $m61_codmatunid ? "selected" : "") : "").">$m61_abrev</option>\n";
          }
          echo " </select>";
          //   db_selectrecord("codunid_$i",$result_unid,true,1,"onchange='js_unid(this.value,$i);'","","","","js_unid(this.value,$i);",1);
          echo "</small></td>";
          $mult = "qntmul_$i";
          $$mult = 1;
          if (isset ($pc17_quant) && $pc17_quant != "") {
            $$mult = $pc17_quant;
          }
          echo "<td class='bordas_corp' align='left' nowrap ><small>";
          db_input("qntmul_$i", 6, 0, true, 'text', 1);
          echo "</small></td>";
          if ($clmatunid->numrows > 0) {
            db_fieldsmemory($result_unid, 0);
            $tam = strlen($m61_codmatunid);
            $tam = $tam -1;
            if (substr($m61_codmatunid, $tam, 1) == 'f') {
              echo "<script>eval(\"document.form1.qntmul_\"+$i+\".disabled=true\");</script>";
            }
          }
          $disab = "";
          echo " <td class='bordas_corp' align='left' nowrap ><small>";
          $result_itens = $cltransmater->sql_record($cltransmater->sql_query(null, "m63_codmatmater,m60_descr", null, "m60_ativo is true and m63_codpcmater=$pc01_codmater and m63_codmatmater not in (select m54_codmatmater from matordemitement where m54_codmatordemitem=$m52_codlanc) "));
          if ($cltransmater->numrows > 0) {
            db_selectrecord("coditem_$i", $result_itens, true, 1, "");
            $disab = "";
          } else {
            $disab = "disabled";
            db_input("coditem_$i", 10, "", true, "hidden", 3);
            echo "<input name='escolhe' type='button' value='Escolher' onclick='js_escolhemater($pc01_codmater);' >";
          }
          echo " </small></td>";
          echo " <td class='bordas_corp' align='center' nowrap ><small>
          <input name='lanc' type='button' value='Lançar' $disab  onclick='js_lanca($e62_codele,$m52_valor,$valoruni,$m52_numemp,$m52_codlanc,$i,$pc01_codmater);' >
          ";
          $e62_descr = addslashes($e62_descr);
          $e62_descr = str_replace(chr(10), " ", $e62_descr);
          echo " </small></td>";
          $pc01_descrmater = addslashes($pc01_descrmater);
          $pc01_descrmater = str_replace(chr(10), " ", $pc01_descrmater);
          echo " <td class='bordas_corp' align='center' nowrap ><small>
          <input name='Incluir' type='button' value='Incluir' onclick='js_novomatmater($pc01_codmater,$e62_numemp,$e62_sequen);' >
          ";
          echo " </small></td>
          </tr> ";
          if ($clmatordemitement->numrows != 0) {
            $result_lancaitens = $clmatordemitement->sql_record($clmatordemitement->sql_query(null, '*', null, "m54_codmatordemitem=$m52_codlanc"));
            for ($y = 0; $y < $clmatordemitement->numrows; $y ++) {
              db_fieldsmemory($result_lancaitens, $y);
              
              $vltot = $m54_valor_unitario * $m54_quantidade;
              $conitens ++;
              echo "<tr>";
              echo "
              <td class='bordas_corp' align='center'><small><a onClick=\"js_consemp($e60_numemp);\" id=\"cons_emp\" href=#>$e60_codemp</a>".
              //db_ancora($e60_codemp,"js_consemp($e60_numemp)",1).
              " </small></td>
              <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
              <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr, 0, 30)."&nbsp;</small></td>
              <td class='bordas_corp' align='right'><small>".db_formatar($m54_valor_unitario, 'p', ' ', 4)."</small></td>
              <td class='bordas_corp' align='right'><small>".db_formatar($vltot, 'p')."</small></td>
              <td class='bordas_corp' align='center'><small>$m54_quantidade</small></td>
              <td class='bordas_corp' align='center' nowrap ><b> $m54_quantidade </b></td>
              <td class='bordas_corp' align='center' nowrap ><b> ".db_formatar($vltot, 'p')." </b> </td>";
              $v = "val_$i";
              $$v = "$vltot";
              db_input("val_$i", 10, '', true, 'hidden', 3);
              echo "
              <td class='bordas_corp' align='center' nowrap ><b> $m61_descr</b> </td>
              <td class='bordas_corp' align='center' nowrap ><b> $m54_quantmulti </b></td>
              <td class='bordas_corp' align='center' nowrap ><b> $m60_descr</b> </td>
              <td class='bordas_corp' align='center'  nowrap colspan='2' >
              <input name='excluir' type='button' value='Excluir' onclick='js_excluilanc($m54_sequencial);' >
              </td>
              ";
              echo "</tr>";
            }
          }
        }
      }
    } else {
      $quant_lanc = "";
      $result_lancaitens = $clmatordemitement->sql_record($clmatordemitement->sql_query_file(null, '*', null, "m54_codmatordemitem=$m52_codlanc"));
      if ($clmatordemitement->numrows != 0) {
        for ($y = 0; $y < $clmatordemitement->numrows; $y ++) {
          db_fieldsmemory($result_lancaitens, $y);
          $quant_lanc += $m54_quantidade;
        }
        $m52_quant = $m52_quant - $quant_lanc;
        $valortotal = $m52_quant * $valoruni;
      }
      $valortotal = db_formatar($valortotal,"p");
      if ($m52_quant != 0 || $clmatordemitement->numrows != 0) {
        $conitens ++;
        $valor  = "total_$i";
        $$valor = db_formatar($valortotal,"p");
        
        echo "<tr>	    
        <td class='bordas_corp' align='center'><small><a onClick=\"js_consemp($e60_numemp);\" id=\"cons_emp\" href=#>$e60_codemp</a></small></td>
        <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
        <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr, 0, 30)."&nbsp;</small></td>
        <td class='bordas_corp' align='right'><b><small>".db_formatar($valoruni, 'p', ' ', 4)."</small></b></td>";
        echo "  <td class='bordas_corp' align='right'><b><small>";
        db_input("total_$i", 10, 0, true, 'text', 3);
        $m52_quant = db_formatar($m52_quant, 'p');
        echo "</small></b></td>
        <td class='bordas_corp' align='center'><small>$m52_quant</small></td>";
        if ($pc01_servico == "f") {
          $val = "valor_$i";
          $quant = "quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
          if (isset ($zera)) {
            $$val = db_formatar('0', 'p');
            $$quant = '0';
          } else {
            $$val = trim(db_formatar($valortotal, 'p'));
            $$quant = trim($m52_quant);
          }
          echo "<td class='bordas_corp' align='center'><small>";
          db_input("quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i", 10, 0, true, 'text', 1, "onchange='js_verifica($m52_quant,this.value,this.name,$valoruni,$i);' ondblclick='js_zera(this.name,\"$i\");'");
          echo "</small></td>";
          echo "<td class='bordas_corp' align='center'><small>";
          $js_script = "onChange=js_recalcula('valor_".$i."','total_".$i."');";
          db_input("valor_$i", 10, 0 , true, 'text', 1, $js_script); // Alterado por Tarcisio
          echo "</small></td>";
          ////aqui
        } else {
          $val = "valor_$i";
          $quant = "quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
          if (isset ($zera)) {
            $$val = db_formatar('0', 'p');
            $$quant = '0';
          } else {
            $$val = trim(db_formatar($valortotal, 'p'));
            $$quant = trim($m52_quant);
          }
          echo " <td class='bordas_corp' align='center'><small>";
          db_input("quant_$e62_codele"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i", 10, 0, true, 'text', 3);
          echo "</small></td>";
          echo " <td class='bordas_corp' align='center'><small>";
          $js_script = "onChange=js_recalcula('valor_".$i."','total_".$i."');";
          db_input("valor_$i", 10, 0, true, 'text', 1, $js_script);
          echo "</small></td>";
        }
        $q = "controle_$i";
        $$q = $m52_quant;
        db_input("controle_$i", 10, 0, true, 'hidden', 3);
        echo "<td class='bordas_corp' align='left' nowrap ><small>";
        $result_unid = $clmatunid->sql_record($clmatunid->sql_query_file(null, "case when m61_usaquant is true then to_char(m61_codmatunid,'99999') || 't' else to_char(m61_codmatunid,'99999') || 'f' end as m61_codmatunid, m61_abrev", "m61_codmatunid"));
        $couni = "codunid_$i";
        $$couni = '1f';
        $result_solicitem = $clsolicitem->sql_record($clsolicitem->sql_query_solunid(null, "pc17_unid,m61_usaquant,pc17_quant", null, "e62_sequen=$e62_sequen"));
        if ($clsolicitem->numrows > 0) {
          db_fieldsmemory($result_solicitem, 0);
          $$couni = $pc17_unid.$m61_usaquant;
        }
        echo " <select onChange='js_unid(this.value,$i);'  name='codunid_$i' id='codunid_$i'>";
        for ($y = 0; $y < $clmatunid->numrows; $y ++) {
          db_fieldsmemory($result_unid, $y);
          echo "<option value=\"$m61_codmatunid\" ". (isset ($couni) ? ($$couni == $m61_codmatunid ? "selected" : "") : "").">$m61_abrev</option>\n";
        }
        echo " </select>";
        //db_selectrecord("codunid_$i",$result_unid,true,1,"onchange='js_unid(this.value,$i);'","","","","js_unid(this.value,$i);",1);
        echo "</small></td>";
        echo "<td class='bordas_corp' align='left' nowrap ><small>";
        $mult = "qntmul_$i";
        $$mult = 1;
        if (isset ($pc17_quant) && $pc17_quant != "") {
          $$mult = $pc17_quant;
        }
        db_input("qntmul_$i", 6, 0, true, 'text', 1);
        if ($clmatunid->numrows > 0) {
          db_fieldsmemory($result_unid, 0);
          $tam = strlen($m61_codmatunid);
          $tam = $tam -1;
          if (isset ($pc17_quant) && $pc17_quant != "") {
            if ($m61_usaquant == 'f') {
              echo "<script>eval(\"document.form1.qntmul_\"+$i+\".disabled=true\");</script>";
            }
          } else
          if (substr($m61_codmatunid, $tam, 1) == 'f') {
            echo "<script>eval(\"document.form1.qntmul_\"+$i+\".disabled=true\");</script>";
          }
        }
        echo "</small></td>";
        $disab = "";
        echo "<td class='bordas_corp' align='left' nowrap ><small>";
        $result_itens = $cltransmater->sql_record($cltransmater->sql_query(null, "m63_codmatmater,m60_descr", null, "m60_ativo is true and m63_codpcmater=$pc01_codmater and m63_codmatmater not in (select m54_codmatmater from matordemitement where m54_codmatordemitem=$m52_codlanc) "));
        if ($cltransmater->numrows > 0) {
          db_selectrecord("coditem_$i", $result_itens, true, 1, "");
          $disab = "";
        } else {
          $disab = "disabled";
          db_input("coditem_$i", 10, "", true, "hidden", 3);
          echo "<input name='escolhe' type='button' value='Escolher' onclick='js_escolhemater($pc01_codmater);' >";
        }
        
        echo "</small></td>";
        echo " <td class='bordas_corp' align='center' nowrap ><small>
        <input name='lanc' type='button' value='Lançar' $disab  onclick='js_lanca($e62_codele,$m52_valor,$valoruni,$m52_numemp,$m52_codlanc,$i,$pc01_codmater);' >
        ";
        
        $pc01_descrmater = addslashes($pc01_descrmater);
        $pc01_descrmater = str_replace(chr(10), " ", $pc01_descrmater);
        $e62_descr = addslashes($e62_descr);
        $e62_descr = str_replace(chr(10), " ", $e62_descr);
        echo " </small></td>";
        ?>
        <td class='bordas_corp' align='center' nowrap ><small>
        <input name='Incluir' type='button' value='Incluir' onclick='js_novomatmater(<?=@$pc01_codmater?>,<?=@$e62_numemp?>,<?=@$e62_sequen?>);' >	       
        <?
        
        
        
        echo " </small></td>
        </tr> ";
        if ($clmatordemitement->numrows != 0) {
          $result_lancaitens = $clmatordemitement->sql_record($clmatordemitement->sql_query(null, '*', null, "m54_codmatordemitem=$m52_codlanc"));
          for ($y = 0; $y < $clmatordemitement->numrows; $y ++) {
            db_fieldsmemory($result_lancaitens, $y);
            
            $vltot = db_formatar($m54_valor_unitario * $m54_quantidade, 'p');
            $m54_valor_unitario = db_formatar($m54_valor_unitario, 'p');
            $m54_quantidade = db_formatar($m54_quantidade, 'p');
            $conitens ++;
            echo "<tr>";
            echo "
            <td class='bordas_corp' align='center'><small><a onClick=\"js_consemp($e60_numemp);\" id=\"cons_emp\" href=#>$e60_codemp</a>".
            //db_ancora($e60_codemp,"js_consemp($e60_numemp)",1).
            " </small></td>
            <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
            <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr, 0, 30)."&nbsp;</small></td>
            <td class='bordas_corp' align='right'><small>".db_formatar($m54_valor_unitario, 'p', ' ', 4)."</small></td>
            <td class='bordas_corp' align='right'><small>".db_formatar($vltot, 'p')."</small></td>
            <td class='bordas_corp' align='center'><small> $m54_quantidade </small></td>
            <td class='bordas_corp' align='center' nowrap ><b> $m54_quantidade </b></td>
            <td class='bordas_corp' align='center' nowrap ><b> ".db_formatar($vltot, 'p')." </b> </td>";
            $v = "val_$i";
            $$v = "$vltot";
            db_input("val_$i", 10, '', true, 'hidden', 3);
            echo "
            <td class='bordas_corp' align='center' nowrap ><b> $m61_descr</b> </td>
            <td class='bordas_corp' align='center' nowrap ><b> $m54_quantmulti </b></td>
            <td class='bordas_corp' align='center' nowrap ><b> $m60_descr</b> </td>
            <td class='bordas_corp' align='center'  nowrap colspan='2' >
            <input name='excluir' type='button' value='Excluir' onclick='js_excluilanc($m54_sequencial);' >
            </td>
            ";
            echo "</tr>";
          }
        }
      }
    }
  }
  if ($conitens == 0) {
    db_msgbox('Já foi dada entrada para todos os itens!!');
    echo "<script>parent.document.form1.voltar.click()</script>";
  }
}
?>    
</table>
</form> 
</center>
</td>
</tr>
</table>
<script>
//-----------------------------------------------------------
function js_recalcula(chave1,chave2){
  
  var valor   = eval("document.form1."+chave1+".value");
  
  if (valor.search(",")!=-1){
    valor=valor.replace(",",".");
    eval("document.form1."+chave1+".value="+valor);
  }
  
  var valor   = new Number(valor);
  
  total       = new Number(eval("document.form1."+chave2+".value"));
  
  //val         = eval("document.form1."+chave1+".value");
  campo_total = eval("document.form1."+chave2);
  
  if (isNaN(parseFloat(valor))){
    alert("Verifique o valor.");
    return false;
  }
  
  if (valor <= 0){
    alert("Valor Recebido dever ser maior que zero!");
    return false;
  }else{
    campo_total.value = valor;
    parent.js_calcalancar();
  }
}
//-----------------------------------------------------------
function js_consemp(numemp){
  js_OpenJanelaIframe('top.corpo','db_iframe_empempenho001','func_empempenho001.php?e60_numemp='+numemp,'Pesquisa',true);
}
//-----------------------------------------------------------
function js_verifica(max,quan,nome,valoruni,contador){

  if (isNaN(parseFloat(quan))){
    alert("Verifique a quantidade.");
    return false;
  }
  
  if (quan.search(",")!=-1){
    quan=quan.replace(",",".");
    eval("document.form1."+nome+".value="+quan);
  }
  
  if (max<quan){
    alert("Informe uma quantidade valida!!");
    eval("document.form1."+nome+".value='';");
    eval("document.form1."+nome+".focus();");
  }else{
    eval("document.form1.controle_"+contador+".value='"+quan+"'");
    /*i=nome.split("_");
    pos=i[4];*/   
		//var xn = new Number(55.15*1.5);
		//alert(xn.toPrecision(3));
    quant=new Number(quan);
    valor=new Number(valoruni);
		//alert(quant);
		//alert(valor);
    valortot=quant*valor;
		//alert(valortot);
    eval("document.form1.valor_"+contador+".value=valortot.toFixed(2)");
    parent.js_calcalancar();
    eval("document.form1.total_"+contador+".value=valortot.toFixed(2)");
  }
}
//-----------------------------------------------------------
function js_zera(nome,i){
  eval("document.form1."+nome+".value='0'");
  eval("document.form1.valor_"+i+".value='0'");
}
//-----------------------------------------------------------
function js_lanca(codele,valor,valoruni,numemp,matordemitem,i,codpcmater){
  //  quant=eval("document.form1.controle_"+i+".value");
  quant=eval("document.form1.quant_"+codele+"_"+numemp+"_"+matordemitem+"_"+i+".value");
  codmatmater=eval("document.form1.coditem_"+i+".value");
  quant_multi=eval("document.form1.qntmul_"+i+".value");  
  valorunitario=eval("document.form1.valor_"+i+".value");
  valorunitario=valorunitario/quant;
  codunid=eval("document.form1.codunid_"+i+".value");  
  cont=codunid.length;
  cont=new Number(cont);
  cont=cont-1;
  codunid=codunid.substring('0',cont);
  
  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','mat1_lancaitens.php?incluir=incluir&codmatordemitem='+matordemitem+'&quantidade='+quant+'&codmatmater='+codmatmater+'&codpcmater='+codpcmater+'&valor_unitario='+valorunitario+'&codunid='+codunid+'&quant_multi='+quant_multi,'Pesquisa',false);
  
  // Versao antiga
  //  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','../mat1_lancaitens.php?codmatordemitem='+matordemitem+'&quantidade='+quant+'&codmatmater='+codmatmater+'&codpcmater='+codpcmater+'&valor_unitario='+valoruni,'Pesquisa',true);
  //
}
//-----------------------------------------------------------
function js_novomatmater(cod,numemp,sequen){
  js_OpenJanelaIframe('top.corpo','iframe_material','mat1_matmater011.php?m63_codpcmater='+cod+'&numemp='+numemp+'&sequen='+sequen,'Incluir Item de Entrada Novo',true);
}
//-----------------------------------------------------------
function js_excluilanc(codent){
  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','mat1_lancaitens.php?codent='+codent+'&excluir=excluir','Pesquisa',false,'0','0','0','0');
}
//-----------------------------------------------------------
function js_unid(value,pos){
  cont=value.length;
  cont=new Number(cont);
  cont=cont-1;
  uso=value.substring(cont);
  cod=value.substring('0',cont);
  if (uso=='f'){
    eval("document.form1.qntmul_"+pos+".value='1';");
    eval("document.form1.qntmul_"+pos+".disabled=true;");
  }else{
    eval("document.form1.qntmul_"+pos+".disabled=false;");
  }
}
//-----------------------------------------------------------
function js_escolhemater(codpcmater){
  js_OpenJanelaIframe('top.corpo','db_iframe_matmater','func_matmaterentoc.php?codpcmater='+codpcmater+'&funcao_js=js_mostramatmater|m60_codmater','Pesquisa',true);
}
function js_retor(){
  parent.db_iframe_matmater.hide();
  document.form1.submit(); 
}
//-----------------------------------------------------------
</script>
</body>
</html>