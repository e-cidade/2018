<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_liclicitemlote_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clliclicitemlote = new cl_liclicitemlote;

if (isset($licitacao)&&trim($licitacao)!=""){
     $res_liclicitem     = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,"pc81_codprocitem,pc01_codmater,pc01_descrmater,pc11_quant,pc11_vlrun,l21_codigo,l04_descricao,l21_situacao","pc81_codprocitem,l04_codigo asc","l21_codliclicita = $licitacao"));
     $numrows            = $clliclicitemlote->numrows;
     $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query(null,"distinct l04_descricao","l04_descricao","l20_codigo = $licitacao"));
     $numrowslote        = $clliclicitemlote->numrows;
}

$cabec_lote = "<b>&nbsp;Lote&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>";
?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
<?$corfundo="E4F471"?>       
.bordas_corp_anul{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: <?=$corfundo?>;
       }
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="80%" align="center">
  <tr><td colspan="6">&nbsp;</td></tr>
  <tr class="bordas">
    <td nowrap class='bordas' align='center'><b>Item</b>                 </td>
    <td nowrap class='bordas' align='center'><b>Código do Material</b>   </td>
    <td nowrap class='bordas' align='center'><b>Material</b></td>
    <td nowrap class='bordas' align='center'><b>Qtde. Solicitada</b>     </td>
    <td nowrap class='bordas' align='center'><b>Vlr. unit. aprox.</b>    </td>
    <td nowrap class='bordas' align='center'><?=$cabec_lote?></td>
  </tr>
<?
    db_input("licitacao",10,"",true,"hidden",3);
    $codprocitem  = 0;
    $codlotedescr = "";
    $mostra       = true;
//    db_criatabela($res_liclicitem); exit;
//    db_criatabela($res_liclicitemlote);
    for($i = 0; $i < $numrows; $i++){
         db_fieldsmemory($res_liclicitem, $i);
         if ($codprocitem == 0){
              $codprocitem = $pc81_codprocitem;
         } else {
              if ($codprocitem == $pc81_codprocitem){
                   $mostra = false;
              } else {     
                   $codprocitem = $pc81_codprocitem;
                   $mostra      = true;
              }
         }

         if (isset($l04_descricao)&&trim($l04_descricao)!=""){
              if ($codlotedescr == ""){
                   $codlotedescr = $l04_descricao;
              } else {
                   if ($codlotedescr != $l04_descricao){
                        $codlotedescr = $l04_descricao;
                   }
              }
         } else {
              $codlotedescr = "";
         }

         if ($mostra == true){
              if ($l21_situacao == 0){  // Normal
                   $class="bordas_corp";
              }

              if ($l21_situacao == 1){  // Anulado
                   $class="bordas_corp_anul";
                   $pc01_descrmater .= "<font color='#FF0000'><b> - ANULADO</b></font>";
              }
?>
  <tr class="<?=$class?>" width="15%">
  	<td align="center" class="<?=$class?>" width="15%"><?=$pc81_codprocitem?></td>
  	<td align="center" class="<?=$class?>" width="25%"><?=$pc01_codmater?>   </td>
  	<td align="left"   class="<?=$class?>" width="30%"><?=$pc01_descrmater?> </td>
  	<td align="right"  class="<?=$class?>" width="15%"><?=$pc11_quant?>      </td>
  	<td align="right"  class="<?=$class?>" width="15%"><? echo db_formatar($pc11_vlrun,"f"); ?></td>
    <td align="center" class="<?=$class?>" width="30%">
      <select name="descricao" id="descricao" onChange="parent.js_selecionado();" <?=($l21_situacao == 1?"disabled":"")?>>
        <option value="<? echo $l21_codigo."_0"; ?>">Sem lote</option>
<?
                   for($ii = 0; $ii < $numrowslote; $ii++){
                        db_fieldsmemory($res_liclicitemlote, $ii);

                        if ($codlotedescr == $l04_descricao){
                             $selected = "SELECTED";
                        } else {
                             $selected = "";
                        }
?>
            <option value="<? echo $l21_codigo."_".$l04_descricao; ?>" <?=$selected?>><?=$l04_descricao?></option>
<?
                   }
?>
      </select>
    </td>  
<?    
         }
?>
    
  </tr>    
<?
    }

    if ($numrows == 0){
?>
  <tr><td colspan="6">&nbsp;</td></tr>
  <tr nowrap>
  	<td colspan="8" align="center"><b>Nenhum lote cadastrado</b></td>
  </tr>
<?
    }
?>
  <tr><td colspan="6">&nbsp;</td></tr>
</table>
</form>
</body>
</html>