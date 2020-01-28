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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_mer_desperdicio_classe.php");
include("classes/db_mer_cardapioitem_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$escola = db_getsession("DB_coddepto");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
</style>
</head>
<?
//select tipos de nutrientes
$sql    ="select me09_i_codigo,me09_c_descr from mer_nutriente order by me09_c_descr";
$result = pg_query($sql);
$linhas = pg_num_rows($result);
$sql1   = " select distinct me01_i_codigo,me01_c_nome,me01_i_percapita,me01_f_versao from mer_cardapio ";
$str    = "";
$sep    = " where ";
if (isset($refeicao)) {
	
  if ($refeicao!="0") {
  	 
    $str.= $sep." me01_i_codigo=$refeicao";
    $sep = " and ";
    
  }
}

if (isset($data)) {
	
  $dat = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
  $str.= $sep." exists(select * from mer_cardapiodia where me12_i_cardapio = me01_i_codigo and me12_d_data='$dat') ";
  $sep = " and ";
  
}

if (isset($cardapio)) {
	
  if ($cardapio!="0") {
  	 
    $str .= $sep." me01_i_tipocardapio=$cardapio";  
    $sep  = " and ";
    
  }
}
$sql1    .= $str;
$result1  = pg_query($sql1);
$linhas1  = pg_num_rows($result1);
?>
<br>
<center>
<table border='3px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px" id="tabela<?=$x?>">
<?       
//for percorre todods os cardapios retornados
for ($z=0;$z<$linhas1;$z++) {
	
  db_fieldsmemory($result1,$z);         
  //select dos itens do cardapio
  $sql2     = " select me35_i_codigo,me35_c_nomealimento from mer_cardapioitem ";
  $sql2    .= "       inner join mer_alimento on me35_i_codigo=me07_i_alimento ";
  $sql2    .= "       where me07_i_cardapio=$me01_i_codigo";
  $result2  = pg_query($sql2);
  $linhas2  = pg_num_rows($result2);           
  $sqltp    = " select me03_c_tipo from mer_cardapiotipo ";
  $sqltp    .= "        inner join mer_tprefeicao on me21_i_tprefeicao = me03_i_codigo ";
  $sqltp    .= "        where me21_i_cardapio=$me01_i_codigo"; 
  $resulttp = pg_query($sqltp);
  $linhastp = pg_num_rows($resulttp);
  $sep      = "";
  $tipo     = "";
  for ($tp=0;$tp<$linhastp;$tp++) {
  	
    db_fieldsmemory($resulttp,$tp);
    $tipo = $tipo.$sep.$me03_c_tipo;
    $sep  = ", ";
    
  }
  $cos = $linhas+2;?>	        
  <tr class='cabec'>	           
	<td colspan="<?=$cos?>"><b><?=$me01_c_nome?> - V.<?=$me01_f_versao?></b></td>
	  <tr>
	    <tr class='cabec'>
	      <td colspan="<?=$cos?>"><?=$tipo?></td>
	        <tr>
	          <tr class='cabec'>    
	            <td> 
	              <b>Item</b>
	             </td>
	             <td>
	               <b>Quantidade por item</b>
	             </td>
	            <?for ($x=0;$x<$linhas;$x++) {
	             	
	                $tvalor[$x]  = 0;
	                $valor[$x]   = 0;
	                $unidade[$x] ="";
	                db_fieldsmemory($result,$x);?>
	                <td>
	                 <b><?=$me09_c_descr?></b>
	                </td>
	            <?}
	              $tvalor[$x]  = 0;
	              $valor[$x]   = 0;
	              $unidade[$x] =""; ?>
	          </tr>
	         <?
	         $cor1 = "#DBDBDB";
             $cor2 = "#f3f3f3";
             $cor  = ""; 
	         for ($y=0;$y<$linhas2;$y++) {
	         	
	           if ($cor == $cor1) {
                 $cor = $cor2;
               } else {
                 $cor = $cor1;
               }
               db_fieldsmemory($result2,$y);?>
	       	 <tr bgcolor="<?=$cor?>">    
	          <td> 
	           <b><?=$me35_c_nomealimento?></b>
	          </td>
	          <td>
	           <?
	           $sqli    = "select me07_f_quantidade,m61_descr from mer_cardapio "; 
	           $sqli   .= "                inner join mer_cardapioitem on me07_i_cardapio=me01_i_codigo "; 
	           $sqli   .= "                inner join matunid on m61_codmatunid=me07_i_unidade ";
	           $sqli   .= "          where me07_i_alimento=$me35_i_codigo and me07_i_cardapio=$me01_i_codigo";
	           $resulti = pg_query($sqli);
	           $linhasi = pg_num_rows($resulti);
	           if ($linhasi>0) {
	           	
	             db_fieldsmemory($resulti,0);
	             $quantitem = $me07_f_quantidade." ".$m61_descr;
	             
	           } else {
	             $quantitem="--";
	           }?>   
	           <?=$quantitem?>
	          </td>
	         <?for ($x=0;$x<$linhas;$x++){
	         	
	             db_fieldsmemory($result,$x);
	             $sqln    = " select me08_f_quant,m61_descr from mer_infnutricional ";
                 $sqln   .= "          inner join mer_nutriente on me09_i_codigo=me08_i_nutriente ";
                 $sqln   .= "          inner join matunid on m61_codmatunid=me09_i_unidade ";
	             $sqln   .= "            where me08_i_alimento=$me35_i_codigo ";
	             $sqln   .= "             and me08_i_nutriente=$me09_i_codigo";	               
	             $resultn = pg_query($sqln);
	             if (pg_num_rows($resultn)>0){
	             	
	              db_fieldsmemory($resultn,0);
	              if ($y==0){
	              	
	               	$valor[1+$x]   = $me08_f_quant*$me07_f_quantidade;
	               	$unidade[1+$x] = $m61_descr;
	               	
	              } else {
	              	
	               	$valor[1+$x]   = $valor[1+$x]+$me08_f_quant*$me07_f_quantidade;
	               	$unidade[1+$x] = $m61_descr;
	               	 
	              }
	              $quantnutri=($me08_f_quant*$me07_f_quantidade)." ".$m61_descr;
	             } else {
	               $quantnutri="0";
	             }?>
	             <td>
	              <?=$quantnutri?>
	             </td>   
	             
	         <?}?>	          
	        </tr>  
	       <?}?>
            <tr bgcolor="<?=$cor?>">
	         <td><b>Total Nutrientes </b></td>
	         <td>&nbsp;</td>
            <?
             for ($x=1;$x<=$linhas;$x++) {
             	
               $tvalor[$x]    = $tvalor[$x]+$valor[$x];
               $tunidade[$x]  = $unidade[$x];?>
	           <td><?="$valor[$x] $unidade[$x]"?></td>
	                      
	       <?}?>
	        </tr>
	        <td><b>Total Nutrientes por aluno </b></td>
	        <td>Percapita=<?=$me01_i_percapita?></td>
            <?
             for ($x=1;$x<=$linhas;$x++) {
             	
               $valor[$x] = $valor[$x]/$me01_i_percapita?>
	           <td><?="$valor[$x] $unidade[$x]"?></td>
	           <?$valor[$x] = 0;
               $unidade[$x] = "";
               
             }?>	  
<?
}
if ($linhas1!=0) {?>  
  <tr class='cabec'>
    <td colspan="<?=$cos?>"> Total geral de nutrientes no cardapio </td>
  </tr>
  <tr bgcolor="<?=$cor?>">
	<td><b>Total Nutrientes </b></td>
	<td>&nbsp;</td>
<?for ($x=1;$x<=$linhas;$x++) {?>
	 <td><?="$tvalor[$x] $tunidade[$x]"?></td>
<?
  }          
} else {?>
  <tr class='cabec'>
  <td><center>Não foi encontrado nenhum cardapio</center></td>
<?
}?>  
  </tr>              
 </table>         
</center>