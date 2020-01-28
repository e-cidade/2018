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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_matordem_classe.php");
include("../classes/db_matordemitem_classe.php");
include("../dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$errosomaquant=0;
$passou=false;

$clmatordemitem = new cl_matordemitem;
$clmatordem  = new cl_matordem;

$clrotulo = new rotulocampo;
$clmatordem->rotulo->label();
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e62_item");
$clrotulo->label("e62_descr");
$clrotulo->label("e62_sequen");
$clrotulo->label("e62_quant");
$clrotulo->label("pc01_descrmater");
$quant="";
$soma=1;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<link href="../estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled == false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}


</script>

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
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="left" valign="top" bgcolor="#CCCCCC"> 
<form name='form1' method='post'>
<center>
      
<table border='0' cellspacing="0" cellpadding="0" style='border:2px inset white' width='100%' bgcolor="white">   
<?
 $where="";
 $where1="";
 
 if (isset($e60_numemp)){
   $where = "and e60_numemp = $e60_numemp";
 }

 if (isset($e60_codemp)){
   $where1 = "and e60_codemp = $e60_codemp";
 }
  
 if ((isset($e60_numcgm) && $e60_numcgm!= "") && (isset($erro)&& $erro=='false')){

   $sSQLemp  = "select e60_numemp, ";
   $sSQLemp .= "       e60_codemp, ";
   $sSQLemp .= "       e62_item, ";
   $sSQLemp .= "       pc01_descrmater, ";
   $sSQLemp .= "       e62_sequen, ";
   $sSQLemp .= "	     e62_descr, ";
   $sSQLemp .= "	     e62_vlrun, ";
   $sSQLemp .= "	     pc01_servico,";
   $sSQLemp .= "	     (select rnsaldoitem  from  fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_quant,";
   $sSQLemp .= "	     (select round(rnsaldovalor,2) from fc_saldoitensempenho(e60_numemp, e62_sequencial)) as e62_vltot";
   $sSQLemp .= "  from empempenho ";
   $sSQLemp .= "       inner join empempitem on e62_numemp       = e60_numemp ";
   $sSQLemp .= "       inner join pcmater    on pc01_codmater    = e62_item";
   $sSQLemp .= "       inner join pcsubgrupo on pc04_codsubgrupo = pc01_codsubgrupo";
   $sSQLemp .= "       inner join pctipo     on pc05_codtipo     = pc04_codtipo";
   $sSQLemp .= " where e60_numcgm = {$e60_numcgm} {$where} {$where1}";
   $sSQLemp .="  order by e60_numemp";
   $result   = pg_query($sSQLemp);
   $numrows  = pg_num_rows($result);
   
   if($numrows>0){
     echo "<tr class=''>";
     echo "<td class='table_header' title='Inverte marcação' align='center'>";
     echo "   <a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>";
     echo "<td class='table_header' align='center'><b><small>{$RLe60_codemp}</small></b></td>";
     echo "<td class='table_header' align='center'><b><small>$RLe60_numemp</small></b></td>";
     echo "<td class='table_header' align='center'><b><small>$RLe62_item</small></b></td>";
     echo "<td class='table_header' align='center'><b><small>$RLpc01_descrmater</small></b></td>";
     echo "<td class='table_header' align='center'><b><small>$RLe62_sequen</small></b></td>";
	   echo "<td class='table_header' align='center'><b><small>$RLe62_descr</small></b></td>";
	   echo "<td class='table_header' align='center'><b><small>$RLe62_quant</small></b></td>";
	   echo "<td class='table_header' align='center'><b><small>Valor Total</small></b></td>";
	   echo "<td class='table_header' align='center'><b><small>Quantidade</small></b></td>";
	   echo "<td class='table_header' align='center'><b><small>Valor</small></b></td>";
   }else if ($numrows == 0){
     echo "<b>Nenhum registro encontrado...</b>";
   }
   echo "</tr>";
	 
   for($i=0; $i<$numrows; $i++){
     
     $disabled = null;
     db_fieldsmemory($result,$i,true);
     if ($e62_vltot == 0  && $e62_quant == 0){
         $disabled =  " disabled ";
     }
     echo "<tr>	    
	         <td class='linhagrid' title='Inverte a marcação' align='center'><input type='checkbox' {$disabled} name='CHECK_$e60_numemp"."_"."$e62_sequen"."' id='CHECK_".$e60_numemp."_"."$e62_sequen"."'></td>
           <td class='linhagrid' align='center'><small>$e60_codemp </small></td>
   	       <td class='linhagrid' align='center'><small>$e60_numemp </small></td>
	         <td class='linhagrid' align='center'><small>$e62_item  </small></td>		    
	         <td class='linhagrid' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
	         <td class='linhagrid' align='center'><small>$e62_sequen</small></td>
           <td class='linhagrid' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr,0,20)."&nbsp;</small></td>";
     $valoruni = $e62_vlrun;	     
     $quant    = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
     $$quant   = $e62_quant;
     $val      = "valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
     $$val     = $e62_vltot;
     echo"<td class='linhagrid' align='center'>$e62_quant</td>
		      <td class='linhagrid' align='center'>$e62_vltot</td>";
     if ($pc01_servico=='f'){

         echo"<td class='linhagrid' align='center'>";
	       db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i",6,0,true,
         'text',1,"onkeyPress='return js_teclas(event)' onchange='js_verifica($e62_quant,this.value,this.name,$valoruni,$e60_numemp,$e62_sequen);'"
          ,'','','text-align:right');
         echo "</td>
	             <td class='linhagrid' align='center'>";
         db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen",6,0,true,'text',3,
         "onkeyPress='return js_teclas(event)'",'','','text-align:right');
         echo "</td>";
         echo "</tr> ";
     } else {
       
	       $quan="quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
         $$quan=$e62_quant;
         echo"<td class='linhagrid' align='center'><small>";
         db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i",6,0,true,'text',3);
         echo "</small></td>
              <td class='linhagrid' align='center'><small>";
         db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen",6,0,true,'text',1,'','','','text-align:right');
         echo "</small></td>";
         echo "</tr> ";
      }
      $quantidade=$e62_quant;
      $valorresta=$e62_vltot;
      $quant="quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
      $val="valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
  /*    if ($pc01_servico=="f"){
  
         $$val=$valorresta;
         $$quant=$quantidade;
         echo "<td class='linhagrid' align='center'><small>$quantidade</small></td>
    	         <td class='linhagrid' align='center'><small>$valorresta</small></td>
               <td class='linhagrid' align='center'><small>";
        	db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i",6,0,true,'text',1,
                   "onchange='js_verifica($quantidade,this.value,this.name,$valoruni,$e60_numemp,$e62_sequen);'");
        	echo "</small></td>
	             <td class='linhagrid' align='center'><small>";
         	db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen",6,0,true,'text',3);
	        echo "</small></td>";
          echo "</tr> ";
      }else{

          $quant="quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
          $val="valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
          $$quant=$e62_quant;
        	$valo=$e62_vltot;
         	$$val=db_formatar($valo,'f');
        	$valorrestante=db_formatar($$val,'f');
          echo "<td class='linhagrid' align='center'><small>$e62_quant</small></td>
    	          <td class='linhagrid' align='center'><small>$valorrestante</small></td>
                <td class='linhagrid' align='center'><small>";
        	db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i",6,0,true,'text',3);
        	echo "</small></td>
	             <td class='linhagrid' align='center'><small>";
         	db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen",10,0,true,'text',1,'','','','text-align:right');
         	echo "</small></td>";
          echo "</tr> ";
      }
      */
    }
  }
  if (($errosomaquant==$numrows)&&($numrows!=0)){
    echo "<script>location.href='db_frmmatordemitem.php?erro=true&numrows=$numrows&e60_numcgm=$e60_numcgm'</script>";
  }
?>
 </table>
     </form> 
     </center>
    </td>
  </tr>
</table>
<script>
function js_verifica(max,quan,nome,valoruni,numemp,sequen){
 if (max<quan){
   alert("Informe uma quantidade valida!!");
   eval("document.form1."+nome+".value='';");
   eval("document.form1."+nome+".focus();");
 }else{
   i=nome.split("_");
   pos=i[3];
   quant=new Number(quan);
   valor=new Number(valoruni);
   valortot=quant*valor;
   eval("document.form1.valor_"+pos+"_"+numemp+"_"+sequen+".value=valortot.toFixed(2)");
 }
}
function js_verificaval(max,quan,nome,valoruni,numemp,sequen){
 if (max<quan){
   alert("Informe uma quantidade valida!!");
   eval("document.form1."+nome+".value='';");
   eval("document.form1."+nome+".focus();");
 }else{
   i=nome.split("_");
   pos=i[3];
   quant=new Number(quan);
   valor=new Number(valoruni);
   valortot=quant*valor;
   eval("document.form1.quant_"+pos+"_"+numemp+"_"+sequen+".value=valortot.toFixed(2)");
 }
}
</script>
</body>
</html>