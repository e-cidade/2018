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
$sql    = "select me09_i_codigo,me09_c_descr
           from mer_nutriente order by me09_c_descr";
$result = pg_query($sql);
$linhas = pg_num_rows($result);
$sql2   = " select me35_c_nomealimento,me35_i_codigo from mer_alimento ";
if ($item!="") {
  $sql2 .= " where me35_i_codigo = $item ";
}elseif($grupo!=""){
  $sql2 .= " where me35_i_grupoalimentar = $grupo ";	
}
$result2 = pg_query($sql2);
$linhas2 = pg_num_rows($result2);
?>
<br>
<center>
<table border='2px' width="98%" bgcolor="#cccccc" style="" cellspacing="0px" id="tabela<?=$x?>">
 <tr class='cabec'>
  <td>
   <b>Item</b>
  </td>
  <?for ($x=0;$x<$linhas;$x++) {
  	
      db_fieldsmemory($result,$x);?>
      <td>
       <b><?=$me09_c_descr?></b>
      </td>
      
  <?}?>
 </tr>
 <?
 $cor1 = "#DBDBDB";
 $cor2 = "#f3f3f3";
 $cor = ""; 
 for ($y=0;$y<$linhas2;$y++) {
 	
   if ($cor == $cor1) {
     $cor = $cor2;
   } else {
     $cor = $cor1;
   }
   db_fieldsmemory($result2,$y);?>
   <tr bgcolor="<?=$cor?>">
    <td>
     <b><?=substr($me35_c_nomealimento,0,30)?></b>
    </td>
    <?for ($x=0;$x<$linhas;$x++) {
    	
        db_fieldsmemory($result,$x);
        $sqln    = " select me08_f_quant,m61_descr from mer_infnutricional 
                      inner join mer_nutriente on me09_i_codigo = me08_i_nutriente        
                      inner join matunid on m61_codmatunid = me09_i_unidade
                   ";        
        $sqln   .= "  where me08_i_alimento=$me35_i_codigo";
        $sqln   .= "  and me08_i_nutriente=$me09_i_codigo";
        $resultn = pg_query($sqln);
        if (pg_num_rows($resultn)>0) {
        	
          db_fieldsmemory($resultn,0);
          $quantnutri=$me08_f_quant." ".$m61_descr;
          
        } else {
          $quantnutri="0";
        }
        ?>
        <td>
         <?=$quantnutri?>
        </td>
        
    <?}?>
  </tr>
  
<?}?>
</table>
</center>