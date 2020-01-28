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

//MODULO: orcamento
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clorcimpactoval->rotulo->label();
$clorcimpactovalmes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o23_anoexe");
$clrotulo->label("o25_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o93_codigo");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
} 




$result = $clorcimpacto->sql_record($clorcimpacto->sql_query_compl($o92_codimp,"o90_codperiodo,o96_anoini,o96_anofim"));
db_fieldsmemory($result,0);


if(isset($o91_proces)){

  $result99  = $clorcimpactovalmes->sql_record($clorcimpactovalmes->sql_query(null,null,"o91_exercicio,o92_mes,o92_valor","","o91_proces=$o91_proces")); 
  $numrows99 = $clorcimpactovalmes->numrows;
  if($numrows99 >0){ 
    for($i=0; $i<$numrows99; $i++){
      db_fieldsmemory($result99,$i);
      $x  = "o91_valor_".$o91_exercicio."_".$o92_mes;
      $$x  = number_format($o92_valor,"2",".","");
    }
  }

  //---------------------------------------------------
    $result = $clorcimpactoval->sql_record($clorcimpactoval->sql_query_file(null,"o91_codseqimp,o91_exercicio,o91_valor","","o91_proces=$o91_proces"));
    $numrows= $clorcimpactoval->numrows;
    for($i=0; $i<$numrows; $i++){
      db_fieldsmemory($result,$i);
      $x  = "o91_codseqimp_$o91_exercicio";
      $$x  = $o91_codseqimp;
      
      $x  = "total_$o91_exercicio";
      $$x  = number_format($o91_valor,"2",".","");
    }
  
} 



?>
<script>
function js_verificar(){
  obj = document.form1;
  return true;
}
function js_calcula(){
<?  
  echo "arr_ano = new Array(";
  $sep = '';
  for($i=$o96_anoini; $i<= $o96_anofim; $i++){
   echo  $sep."'".$i."'";
   $sep = ",";
  }
  echo");\n";
?>
   for(var i=0; i<arr_ano.length; i++){
     soma = new Number();
     for(a=1; a<13; a++){
        val = new Number(eval("document.form1.o91_valor_"+arr_ano[i]+"_"+a+".value"));
        soma =  val + new Number(soma);
     } 
     eval("document.form1.somatot_"+arr_ano[i]+".value="+soma.toFixed(2));
     resto = new Number(eval("new Number(document.form1.total_"+arr_ano[i]+".value) - new Number(document.form1.somatot_"+arr_ano[i]+".value)"));
     eval("document.form1.resto_"+arr_ano[i]+".value= "+resto.toFixed(2));
   }
}
function js_divide(){
<?  
  echo "arr_ano = new Array(";
  $sep = '';
  for($i=$o96_anoini; $i<= $o96_anofim; $i++){
   echo  $sep."'".$i."'";
   $sep = ",";
  }
  echo");";
?>
   for(var i=0; i<arr_ano.length; i++){
     tot =  new Number();
     valor  =  new Number(eval("document.form1.total_"+arr_ano[i]+".value"));
     valparc = new Number(valor/12);
     for(a=1; a<13; a++){
       var t =valparc.toFixed(2);
       tot = new Number(t) + new Number(tot); 
       tot =  tot.toFixed(2);
       if(a==12){
	 if(valor>tot){
	   resto = valor-tot;
   	   valparc = new Number(valparc+resto);
	 }else{
	   resto = tot-valor;
   	   valparc = new Number(valparc+resto);
	 } 
       }
       eval("document.form1.o91_valor_"+arr_ano[i]+"_"+a+".value="+valparc.toFixed(2));
     } 
   }
}

function js_verif(ano,mes){
    tot = new Number();
   for(a=1; a<13; a++){
     tot = new Number(eval("document.form1.o91_valor_"+ano+"_"+a+".value")) + new Number(tot);
   }   
   total  =  new Number(eval("document.form1.total_"+ano+".value"));
   
   if(tot>total){
     alert("Valor inválido!");
     eval("document.form1.o91_valor_"+ano+"_"+mes+".value='0.00'");
     eval("document.form1.o91_valor_"+ano+"_"+mes+".select();");
   }
   
js_calcula();
}



</script>
<form name="form1" method="post" action="">
<center>

<?
db_input('o92_codimp',8,$Io91_codimp,true,'hidden',3);
db_input('o91_proces',8,$Io91_proces,true,'hidden',3);
?>



<table cellpadding='0' cellspacing='0' border='1'>
  <tr>
    <td nowrap title="<?=@$To91_exercicio?>">
       <b>Exerc</b>
    </td>
<?
    $arr_mes = array("1"=>"JAN","2"=>"FEV","3"=>"MAR","4"=>"ABR","5"=>"MAI","6"=>"JUN","7"=>"JUL","8"=>"AGO","9"=>"SET","10"=>"OUT","11"=>"NOV","12"=>"DEZ");
    for($t=1; $t<count($arr_mes)+1; $t++){   
      echo "<td nowrap align='center'><b>";
      echo  $arr_mes[$t];
      echo"</b></td>";
    }
?>    
    <td nowrap title="">
       <b>Soma</b>
    </td>
    <td nowrap title="">
       <b>Total</b>
    </td>
    <td nowrap title="">
       <b>Resto</b>
    </td>
  </tr>
<?
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
    
     $x = "o91_valor_$i";
     $$x  = "";
      
     $x = "o56_elemento_$i";
     $$x = "";
     
     $x = "o91_quantmed_$i";
     $$x = "";
     
     $x = "o93_codigo_$i";
     $$x = "";

      
   if(isset($o91_proces) && $o91_proces!='' && empty($novo) && empty($incluir) && empty($alterar)){
    $result = $clorcimpactoval->sql_record($clorcimpactoval->sql_query_file(null,"*","","o91_proces=$o91_proces and o91_exercicio=$i"));
    $numrows = $clorcimpactoval->numrows;
     if($numrows>0){ 
       db_fieldsmemory($result,0);
   
	  
       $x = "o92_valor_$i";
       $$x = $o91_valor;
     }
   }
   db_input("o91_codseqimp_$i",8,$Io91_codseqimp,true,'hidden');

   $x = "o91_exercicio_$i";
   $$x = $i;
?>



  <tr>
    <td nowrap title="<?=@$To91_exercicio?>">
      <?db_input("o91_exercicio_$i",4,$Io91_exercicio,true,'text',3);?>
    </td>
<?
    
    $total = "total_$i";  
   
    $tot=0; 
    $valparc = number_format($$total/12,"2",".","");
    $somatot = 0;
    for($t=1; $t<count($arr_mes)+1; $t++){   
      echo "<td nowrap>";

          
	$tot += $valparc;

	if($t==12 && $tot != $$total){
           if($tot>$$total){
	      $valparc +=  number_format($tot-$$total,"2",".","");
	   }else{
	      $valparc +=  number_format($$total-$tot,"2",".","");
	   } 
	}
        
           $valmes = "o91_valor_".$i."_$t";
	   
	if(empty($numrows99) || $numrows99 == 0 ){
          $$valmes = $valparc;
        }else{
	  $somatot += $$valmes;
	}  	 
	
        db_input("$valmes",5,$Io92_valor,true,'text',$db_opcao,"onchange=\"js_verif('$i','$t');\"");
      echo"</td>";
    }
      
      $soma  = "somatot_$i";  
      $$soma = number_format($somatot,"2",".","");
      
      $resto  = "resto_$i";  
      $$resto = number_format($$total - $somatot,"2",".","");

    
?>
    <td nowrap title="">
        <?db_input("$soma",8,0,true,'text',3);?>
    </td>
    <td nowrap title="">
        <?db_input("$total",8,0,true,'text',3);?>
    </td>
    <td nowrap title="">
        <?db_input("$resto",8,0,true,'text',3);?>
    </td>
  </tr>

<?
}
?>

  </table>
 <input name="atualizar" type="submit" id="db_opcao" value="Atualizar" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificar();"  >
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}

<?
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
  //rotina  para repetir os valores digitado na primeira linha para os campos abaixo
  if($i == $o96_anoini){
      echo "function js_quant(){\n";
        for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
          echo "document.form1.o91_quantmed_$c.value = document.form1.o91_quantmed_$i.value;\n";
        }  
      echo "}";  
      
      echo "function js_valor(){\n";
        for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
          echo "document.form1.o91_valor_$c.value = document.form1.o91_valor_$i.value;\n";
        }  
      echo "}";  
  }
  //final
?>



//elemento
function js_elemento_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactoval','db_iframe_orcelemento','func_orcelemento_orcs.php?funcao_js=parent.js_mostraorcelemento1_<?=$i?>|o56_elemento|o56_descr','Pesquisa',true,'0','1','775','390');
  }else{
    elemento = document.form1.o56_elemento_<?=$i?>.value;
    if(elemento != ''){
      while(elemento.length<7){
	elemento = elemento+"0";
      }
       document.form1.o56_elemento_<?=$i?>.value=elemento;
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactoval','db_iframe_orcelemento','func_orcelemento_orcs.php?pesquisa_chave='+document.form1.o56_elemento_<?=$i?>.value+'&funcao_js=parent.js_mostraorcelemento_<?=$i?>','Pesquisa',false);
    }      
  }
}
function js_mostraorcelemento_<?=$i?>(chave,erro){
//  document.form1.o56_descr.value = chave; 
  if(erro==true){ 
    document.form1.o56_elemento_<?=$i?>.focus(); 
    document.form1.o56_elemento_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o56_elemento_$c.value = document.form1.o56_elemento_$i.value;";
     }  
  }
  ?>
  
}


function js_mostraorcelemento1_<?=$i?>(chave1,chave2){
  document.form1.o56_elemento_<?=$i?>.value = chave1;
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o56_elemento_$c.value = chave1;";
     }  
  }
  ?>
  db_iframe_orcelemento.hide();
}
//recurso
function js_codigo_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactoval','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1_<?=$i?>|o15_codigo|o15_descr','Pesquisa',true,'0','1','775','390');
  }else{
    if( document.form1.o93_codigo_<?=$i?>.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactoval','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o93_codigo_<?=$i?>.value+'&funcao_js=parent.js_mostraorctiporec_<?=$i?>','Pesquisa',false);
     }       
  }
}
function js_mostraorctiporec_<?=$i?>(chave,erro){
//  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o93_codigo_<?=$i?>.focus(); 
    document.form1.o93_codigo_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o93_codigo_$c.value = document.form1.o93_codigo_$i.value ;";
     }  
  }
  ?>
}
function js_mostraorctiporec1_<?=$i?>(chave1,chave2){
  document.form1.o93_codigo_<?=$i?>.value = chave1;
//  document.form1.o15_descr.value = chave2;
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o93_codigo_$c.value = document.form1.o93_codigo_$i.value ;";
     }  
  }
  ?>
  db_iframe_orctiporec.hide();
}



<?
 }
  
 if(isset($numrows99) && $numrows99 == 0 ){
   echo "document.form1.atualizar.click();";
 } 



?>
</script>