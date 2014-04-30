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
$clorcimpactorecmov->rotulo->label();
$clorcimpactorecmovmes->rotulo->label();
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

$result = $clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file($o69_codperiodo,"o96_anoini,o96_anofim"));
db_fieldsmemory($result,0);


if(isset($o69_proces)){
  $result99  = $clorcimpactorecmovmes->sql_record($clorcimpactorecmovmes->sql_query(null,null,"o69_exercicio,o97_mes,o97_valor","","o69_proces=$o69_proces")); 
  $numrows99 = $clorcimpactorecmovmes->numrows;
  if($numrows99 >0){ 
    for($i=0; $i<$numrows99; $i++){
      db_fieldsmemory($result99,$i);
      $x  = "o97_valor_".$o69_exercicio."_".$o97_mes;
      $$x  = number_format($o97_valor,"2",".","");
    }
  }


  $result = $clorcimpactorecmov->sql_record($clorcimpactorecmov->sql_query_file(null,"o69_sequen as o97_sequen,o69_exercicio,o69_valor","","o69_proces=$o69_proces"));
  $numrows= $clorcimpactorecmov->numrows;
  for($i=0; $i<$numrows; $i++){
    db_fieldsmemory($result,$i);
    $x  = "o97_sequen_$o69_exercicio";
    $$x  = $o97_sequen;
    
   $x  = "total_$o69_exercicio";
    $$x  = number_format($o69_valor,"2",".","");
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
  echo");";
?>
   for(var i=0; i<arr_ano.length; i++){
     soma = new Number();
     for(a=1; a<13; a++){
        val = new Number(eval("document.form1.o97_valor_"+arr_ano[i]+"_"+a+".value"));
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
       eval("document.form1.o97_valor_"+arr_ano[i]+"_"+a+".value="+valparc.toFixed(2));
     } 
   }
}

function js_verif(ano,mes){
    tot = new Number();
   for(a=1; a<13; a++){
     tot = new Number(eval("document.form1.o97_valor_"+ano+"_"+a+".value")) + new Number(tot);
   }   
   total  =  new Number(eval("document.form1.total_"+ano+".value"));
   
   if(tot.toFixed(2) > total.toFixed(2)){
     alert("Valor inválido!");
     eval("document.form1.o97_valor_"+ano+"_"+mes+".value='0.00'");
     eval("document.form1.o97_valor_"+ano+"_"+mes+".select();");
   }
   js_calcula();
}


//js_calcula();

</script>
<form name="form1" method="post" action="">
<center>

<?
db_input('o69_proces',8,$Io69_proces,true,'hidden',3);
?>



<table cellpadding='0' cellspacing='0' border='1'>
  <tr>
    <td nowrap title="<?=@$To69_exercicio?>">
       <b>Exe</b>
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
    
     $x = "o97_valor_$i";
     $$x  = "";
      
     $x = "o93_codigo_$i";
     $$x = "";

      
   if(isset($o69_proces) && $o69_proces!='' && empty($novo) && empty($incluir) && empty($alterar)){
    $result = $clorcimpactorecmovmes->sql_record($clorcimpactorecmovmes->sql_query(null,null,"*","","o69_proces=$o69_proces and o69_exercicio=$i"));
   $numrows = $clorcimpactorecmovmes->numrows;
     if($numrows>0){ 
       db_fieldsmemory($result,0);
   
	  
       $x = "o97_valor_$i";
       $$x = $o97_valor;
     }
   }
   db_input("o97_sequen_$i",8,$Io97_sequen,true,'hidden');

   $x = "o69_exercicio_$i";
   $$x = $i;
?>



  <tr>
    <td nowrap title="<?=@$To69_exercicio?>">
      <?db_input("o69_exercicio_$i",4,$Io69_exercicio,true,'text',3);?>
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
	     $valparc = $valparc -  number_format($tot-$$total,"2",".","");
	   }else{
	      $valparc +=  number_format($$total-$tot,"2",".","");
	   } 
	}
         
        $valmes = "o97_valor_".$i."_$t";
	if(empty($numrows99) || $numrows99 == 0 ){
          $$valmes = $valparc;
	}else{
          $somatot += $$valmes;
	}  	 
	
        db_input("$valmes",6,$Io97_valor,true,'text',$db_opcao,"onchange=\"js_verif('$i','$t');\"");
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
<?
 if(isset($numrows99) && $numrows99 == 0 ){
   echo "<script>document.form1.atualizar.click();</script>";
 }
?>