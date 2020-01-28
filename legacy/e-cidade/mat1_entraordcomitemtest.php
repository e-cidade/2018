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
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_matordemitement_classe.php");
include("classes/db_matestoqueitemoc_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_transmater_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatordemitem = new cl_matordemitem;
$clmatordemitement = new cl_matordemitement;
$clmatordem  = new cl_matordem;
$cltransmater= new cl_transmater;

$clmatordemitem->rotulo->label();
$clmatordem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
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
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='a=1'> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <center>
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
   if (isset($m51_codordem) && $m51_codordem!= "") {
     $result=$clmatordemitem->sql_record($clmatordemitem->sql_query_servico(null,"*","","m52_codordem=$m51_codordem"));
     $numrows = $clmatordemitem->numrows;
     if($numrows>0){
       echo "<tr class='bordas'>
	     <td class='bordas' align='center'><b><small>$RLe60_codemp</small></b></td>
	     <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
             <td class='bordas' align='center'><b><small>$RLm52_valor</small></b></td>
             <td class='bordas' align='center'><b><small>Valor Total</small></b></td>
	     <td class='bordas' align='center'><b><small>$RLm52_quant</small></b></td>
             <td class='bordas' align='center'><b><small>Recebido</small></b></td>
             <td class='bordas' align='center'><b><small>Valor Rec.</small></b></td>
	     <td class='bordas' align='center'><b><small>Item de Entrada</small></b></td>
	     <td class='bordas' align='center'><b><small>Receber</small></b></td>
	     <td class='bordas' align='center'><b><small>Incluir Item de Entrada Novo</small></b></td>";
     }else echo"<b>Nenhum registro encontrado...</b>";
       echo "</tr>";
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
         $valortotal=$m52_valor;
         $valoruni=$m52_valor/$m52_quant;
         $result1 = $clmatestoqueitemoc->sql_record($clmatestoqueitemoc->sql_query_file(null,null,"*","","m73_codmatordemitem=$m52_codlanc"));
         if ($clmatestoqueitemoc->numrows!=0){
           db_fieldsmemory($result1,0);
           $result_busca_codestoque = $clmatestoqueitem->sql_record($clmatestoqueitem->sql_query_file("","m71_quant,m71_valor, m71_codmatestoque as codestoque","","m71_codlanc=$m73_codmatestoqueitem"));
           db_fieldsmemory($result_busca_codestoque,0);
           if ($pc01_servico=="f"){
             $quantidade=$m52_quant-$m71_quant;
             $vlto=$m52_valor-$m71_valor;
           }else{
             $quantidade=0;
	     $vlto=$m52_valor-$m71_valor;
	   }
           if (($quantidade==0)&&($vlto==0)){
	     $errosomaquant++;
  	   }else{
             $quant_lanc="";
	     $result_lancaitens=$clmatordemitement->sql_record($clmatordemitement->sql_query_file(null,'*',null,"m54_codmatordemitem=$m52_codlanc"));
	     if ($clmatordemitement->numrows!=0){
	       for ($y=0;$y<$clmatordemitement->numrows;$y++){
	         db_fieldsmemory($result_lancaitens,$y);
		 $quant_lanc+=$m54_quantidade;
	       }
	       $quantidade=$quantidade-$quant_lanc;
	       $vlto=$quantidade*$valoruni;
	     }
	     echo "<tr>	    
   	           <td class='bordas_corp' align='center'><small>$e60_codemp </small></td>
	           <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
                   <td class='bordas_corp' align='right'><b><small>".db_formatar($valoruni,'f')."</small></b></td>
                   <td class='bordas_corp' align='right'><b><small>".db_formatar($vlto,'f')."</small></b></td>";
	     if ($pc01_servico=="f"){
               echo "<td class='bordas_corp' align='center'><small>$quantidade</small></td>
	             <td class='bordas_corp' align='center'><small>";
    	       db_input("quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i",6,0,true,'text',1,"onchange='js_verifica($quantidade,this.value,this.name,$valoruni);'");
	       echo "</small></td>";
	       echo "<td class='bordas_corp' align='center'><small>";
	       db_input("valor_$i",6,0,true,'text',3);
               echo "</small></td>";
             }else{
               $quant="quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
               $$quant=$m52_quant;
               $quantidade=$m52_quant;
               echo "<td class='bordas_corp' align='center'><small>$quantidade</small></td>
  	             <td class='bordas_corp' align='center'><small>";
	       db_input("quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i",6,0,true,'text',3);
  	       echo "</small></td>";
	       echo" <td class='bordas_corp' align='center'><small>";
	       db_input("valor_$i",6,0,true,'text',1);
	       echo "</small></td>";
	     }
	     db_input("controle_$i",10,0,true,'hidden',3);
	     echo" <td class='bordas_corp' align='center' nowrap ><small>";
	     $result_itens=$cltransmater->sql_record($cltransmater->sql_query(null,"m63_codmatmater,m60_descr",null,"m63_codpcmater=$pc01_codmater"));
	     db_selectrecord("itement_$i",$result_itens,true,1,"");
	     echo " </small></td>";
	     echo" <td class='bordas_corp' align='center' nowrap ><small>
	       <input name='lanc' type='button' value='Lançar' onclick='js_lanca($e62_codele,$m52_valor,$m52_numemp,$m52_codlanc,$i,$pc01_codmater);' >
	     ";
	    
	     echo " </small></td>";
	     echo" <td class='bordas_corp' align='center' nowrap ><small>
	          <input name='Incluir' type='button' value='Incluir' onclick='js_novomatmater($pc01_codmater,\"$pc01_descrmater\");' >
	     ";
	     echo " </small></td>
  	            </tr> ";
	   }
	   }else{
             $quant_lanc="";
	     $result_lancaitens=$clmatordemitement->sql_record($clmatordemitement->sql_query_file(null,'*',null,"m54_codmatordemitem=$m52_codlanc"));
	     if ($clmatordemitement->numrows!=0){
	       for ($y=0;$y<$clmatordemitement->numrows;$y++){
	         db_fieldsmemory($result_lancaitens,$y);
		 $quant_lanc+=$m54_quantidade;
	       }
	       $m52_quant=$m52_quant-$quant_lanc;
	       $valortotal=$m52_quant*$valoruni;
	     }
	     echo "<tr>	    
   	           <td class='bordas_corp' align='center'><small>$e60_codemp </small></td>
	           <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
                   <td class='bordas_corp' align='right'><b><small>".db_formatar($valoruni,'f')."</small></b></td>
                   <td class='bordas_corp' align='right'><b><small>".db_formatar($valortotal,'f')."</small></b></td>
	           <td class='bordas_corp' align='center'><small>$m52_quant</small></td>";
	     if ($pc01_servico=="f"){
	       echo "<td class='bordas_corp' align='center'><small>";
	       db_input("quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i",6,0,true,'text',1,"onchange='js_verifica($m52_quant,this.value,this.name,$valoruni,$i);'");
	       echo "</small></td>";
	       echo "<td class='bordas_corp' align='center'><small>";
	       db_input("valor_$i",6,0,true,'text',3);
	       echo "</small></td>";
	     }else{
               $quant="quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i";
               $$quant=$m52_quant;
	       echo" <td class='bordas_corp' align='center'><small>";
	       db_input("quant_$e62_codele"."_"."$m52_valor"."_"."$m52_numemp"."_".$m52_codlanc."_"."$i",6,0,true,'text',3);
	       echo "</small></td>";
	       echo" <td class='bordas_corp' align='center'><small>";
	       db_input("valor_$i",6,0,true,'text',1);
	       echo "</small></td>";
	     }
	     db_input("controle_$i",10,0,true,'hidden',3);
   	     echo "<td class='bordas_corp' align='center' nowrap ><small>";
	     $result_itens=$cltransmater->sql_record($cltransmater->sql_query(null,"m63_codmatmater,m60_descr",null,"m63_codpcmater=$pc01_codmater"));
	     db_selectrecord("itement_$i",$result_itens,true,1,"");
  	     echo "</small></td>";
	     echo" <td class='bordas_corp' align='center' nowrap ><small>
	       <input name='lanc' type='button' value='Lançar' onclick='js_lanca($e62_codele,$m52_valor,$valoruni,$m52_numemp,$m52_codlanc,$i,$pc01_codmater);' >
	     ";
	    
	     echo " </small></td>";
	     echo" <td class='bordas_corp' align='center' nowrap ><small>
	          <input name='Incluir' type='button' value='Incluir' onclick='js_novomatmater($pc01_codmater,\"$pc01_descrmater\");' >
	     ";
	     echo " </small></td>
  	            </tr> ";
	     if ($clmatordemitement->numrows!=0){
               $result_lancaitens=$clmatordemitement->sql_record($clmatordemitement->sql_query(null,'*',null,"m54_codmatordemitem=$m52_codlanc"));
	       for ($y=0;$y<$clmatordemitement->numrows;$y++){
	         db_fieldsmemory($result_lancaitens,$y);
		 $vltot=$m54_valor_unitario*$m54_quantidade;
	         echo "<tr>";
	         echo "
   	               <td class='bordas_corp' align='center'><small>$e60_codemp </small></td>
	               <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($pc01_descrmater,0,20)."&nbsp;</small></td>
                       <td class='bordas_corp' align='right'><small>".db_formatar($m54_valor_unitario,'f')."</small></td>
                       <td class='bordas_corp' align='right'><small>".db_formatar($vltot,'f')."</small></td>
	               <td class='bordas_corp' align='center'><small>$m54_quantidade</small></td>
		       <td class='bordas_corp' align='center' nowrap ><b> $m54_quantidade </b></td>
		       <td class='bordas_corp' align='center' nowrap ><b> ".db_formatar($m54_valor_unitario,'f')." </b> </td>
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
 
?>    
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
//-----------------------------------------------------------
function js_verifica(max,quan,nome,valoruni,contador){
 if (max<quan){
   alert("Informe uma quantidade valida!!");
   eval("document.form1."+nome+".value='';");
   eval("document.form1."+nome+".focus();");
 }else{
   eval("document.form1.controle_"+contador+".value="+quan);
   i=nome.split("_");
   pos=i[5];
   quant=new Number(quan);
   valor=new Number(valoruni);
   alert(valor);
   valortot=quant*valor;
   valortot=new Number(valortot);
   eval("document.form1.valor_"+pos+".value=valortot.toFixed(4)");
 }
}
//-----------------------------------------------------------
function js_lanca(codele,valor,valoruni,numemp,matordemitem,i,codpcmater){
  quant=eval("document.form1.controle_"+i+".value");
  codmatmater=eval("document.form1.itement_"+i+".value");
  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','../mat1_lancaitens.php?incluir=incluir&codmatordemitem='+matordemitem+'&quantidade='+quant+'&codmatmater='+codmatmater+'&codpcmater='+codpcmater+'&valor_unitario='+valoruni,'Pesquisa',false,'0','0','0','0');
//  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','../mat1_lancaitens.php?codmatordemitem='+matordemitem+'&quantidade='+quant+'&codmatmater='+codmatmater+'&codpcmater='+codpcmater+'&valor_unitario='+valoruni,'Pesquisa',true);
  
}
//-----------------------------------------------------------
function js_novomatmater(cod,descr){
  js_OpenJanelaIframe('top.corpo','iframe_material','../mat1_matmater011.php?m63_codpcmater='+cod+'&pc01_descrmater='+descr,'Incluir Item de Entrada Novo',true);
}
//-----------------------------------------------------------
function js_excluilanc(codent){
  js_OpenJanelaIframe('top.corpo','db_iframe_lanca','../mat1_lancaitens.php?codent='+codent+'&excluir=excluir','Pesquisa',false,'0','0','0','0');
  
}
//-----------------------------------------------------------
/*
function js_pesquisa_matmater(mostra,campo){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matmater','../func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true);
  }else{
     nome = campo.name;
     codigo = campo.value;
     if(nome != ''){ 
        js_OpenJanelaIframe('','db_iframe_matmater','../func_matmater.php?pesquisa_chave='+codigo+'&funcao_js=parent.js_mostramatmater','Pesquisa',false,'0','0','0','0');
     }else{
       eval("document.form1."+nome+".value")= ''; 
     }
  }
}

function js_mostramatmater(chave,erro){
   i=nome.split("_");
   pos=i[1];
   eval("document.form1.descr_"+pos+".value=chave;"); 
  if(erro==true){ 
    eval("document.form1."+nome+".value='';"); 
    eval("document.form1."+nome+".focus();"); 
  }
}
function js_mostramatmater1(chave1,chave2){
   eval("document.form1."+nome+".value") = chave1;  
   top.corpo.db_iframe_matmater.hide();
}
*/
//------------------------------------------------------
</script>
</body>
</html>