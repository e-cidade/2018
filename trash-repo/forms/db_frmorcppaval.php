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
$clorcppaval->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o23_anoexe");
$clrotulo->label("o25_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o26_codigo");
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
?>
<script>
function js_verificar(){
  obj = document.form1;
  liberado = 0;
  exercicio = "";
  for(i=0; i<obj.elements.length; i++){
    if(obj.elements[i].type == 'text'){
       campo_valor =  obj.elements[i].name.substr(0,9);//o24_valor
       ano         =  obj.elements[i].name.substr(10);//o24_valor
      if(campo_valor == "o24_valor"){
	valor = obj.elements[i].value;
	valor1= new Number(valor);
	if(valor != '' && valor1!=0){
	    recurso = eval("document.form1.o26_codigo_"+ano+".value;");
	    if(recurso == ''){
	      exercicio = ano;
	      liberado = 0;
	      break;
	    }else{
	      liberado++;
	    }
        }else{
	  eval("document.form1.o26_codigo_"+ano+".value = 1");
	  eval("document.form1.o24_valor_"+ano+".value  = 0");
	  eval("document.form1.o24_quantmed_"+ano+".value  = 0");	  
	  eval("document.form1.o56_elemento_"+ano+".value  = 0");
	  liberado++;
	}
      }
    } 
  }

  if(liberado < 3){
    <?
    if($db_opcao!=3 && $db_opcao!=33){
      echo "alert('Informe o campo Recurso do exercício '+exercicio);";
      echo "eval('document.form1.o26_codigo_'+exercicio+'.focus()');";
      echo "return false;";
    }
    ?>
  }
  return true;
}
</script>
<form name="form1" method="post" action="">
<center>

<?
db_input('o24_codppa',8,$Io24_codppa,true,'hidden',3);

db_input('o24_proces',8,$Io24_proces,true,'hidden',3);
?>

<?

$result = $clorcppa->sql_record($clorcppa->sql_query_compl($o24_codppa,"o23_codleippa,o21_anoini,o21_anofim"));
db_fieldsmemory($result,0);
?>
<table>
<?
for($i=$o21_anoini; $i<= $o21_anofim; $i++){
    
     $x  = "o24_codseqppa_$i";
     $$x  = "";
    
     $x = "o24_valor_$i";
     $$x  = "";
      
     $x = "o56_elemento_$i";
     $$x = "";
     
     $x = "o24_quantmed_$i";
     $$x = "";
     
     $x = "o26_codigo_$i";
     $$x = "";

      
   if(isset($o24_proces) && $o24_proces!='' && empty($novo) && empty($incluir) && empty($alterar)){
    $result = $clorcppaval->sql_record($clorcppaval->sql_query_file(null,"*","","o24_proces=$o24_proces and o24_exercicio=$i and o24_codppa=$o24_codppa"));
    $numrows = $clorcppaval->numrows;
     if($numrows>0){
       db_fieldsmemory($result,0);
   
       $x  = "o24_codseqppa_$i";
       $$x  = $o24_codseqppa;
	  
       $x = "o24_valor_$i";
       $$x = $o24_valor;
       
       $x = "o24_quantmed_$i";
       $$x = $o24_quantmed;
   
       //retorna os dados do orcppatiporec 
       $result = $clorcppatiporec->sql_record($clorcppatiporec->sql_query_file($o24_codseqppa));
       if($clorcppatiporec->numrows>0){
         db_fieldsmemory($result,0);
         $x = "o26_codigo_$i";
         $$x = $o26_codigo;
       }  	 
       
       //retorna os dados do orcppavaele 
       $result = $clorcppavalele->sql_record($clorcppavalele->sql_query($o24_codseqppa,'',"o56_elemento"));
       if($clorcppavalele->numrows>0){
         db_fieldsmemory($result,0);
         $x = "o56_elemento_$i";
         $$x = $o56_elemento;
       }  	 
       
     }
     
   }

?>
<tr>
  <td>
<?
db_input("o24_codseqppa_$i",8,$Io24_codseqppa,true,'hidden',1);
?>
  
  <fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To24_exercicio?>">
       <?=@$Lo24_exercicio?>
    </td>
    <td> 
<?
$x = "o24_exercicio_$i";
if(empty($$x)){
  $$x = $i;
}
db_input("o24_exercicio_$i",4,$Io24_exercicio,true,'text',3)
?>
    </td>
    <td nowrap title="<?=@$To24_valor?>">
       <?=@$Lo24_valor?>
    </td>
    <td> 
<?
db_input("o24_valor_$i",8,$Io24_valor,true,'text',$db_opcao,($i == $o21_anoini?"onchange='js_valor();js_formataro24_valor();'":"onchange='js_formataro24_valor();'"));
?>
    </td>
    <td nowrap title="<?=@$To24_quantmed?>">
       <?=@$Lo24_quantmed?>
    </td>
    <td> 
<?
db_input("o24_quantmed_$i",10,$Io24_quantmed,true,'text',$db_opcao,($i == $o21_anoini?"onchange='js_quant();'":""),"",($db_opcao!=3?"#E6E4F1":""));
?>
    </td>
    <td nowrap title="<?=@$To56_elemento?>">
       <?
       db_ancora(@$Lo56_elemento,"js_elemento_$i(true);",$db_opcao);
       ?>
    </td>
    <td> 
    <?
       $x = "o56_elemento_$i";
    ?>
    <input type="text"  value="<?=@$$x?>" <?=($db_opcao==3?"readOnly style='background-color:#DEB887;'":"")?>  name="o56_elemento_<?=$i?>" size="9" maxlength='7'  onKeyUp="js_ValidaCampos(this,1,'','','',event);" onKeyDown="return js_controla_tecla_enter(this,event);"    onchange='js_elemento_<?=$i?>(false);'>
    
    </td>
    <td nowrap title="<?=@$To26_codigo?>">
       <?
       db_ancora(@$Lo26_codigo,"js_codigo_$i(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$x = "o26_codigo_$i";
if(empty($$x)){
  $$x = 1;
}  
db_input("o26_codigo_$i",4,$Io26_codigo,true,'text',$db_opcao," onchange='js_codigo_$i(false);'")
?>
       <?
//db_input('o56_descr',30,$Io56_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  </table>

  </fieldset>
  </td>
</tr> 
<?
}
?>

  </table>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificar();"  >
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("o24_codseqppa"=>@$o24_codseqppa,"o24_proces"=>@$o24_proces);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clorcppaval->sql_query_dad(null,"o24_codseqppa,o24_codppa,o24_exercicio,o24_valor,o24_quantmed,o24_proces,o56_elemento,o15_codigo","o24_exercicio","o24_codppa =$o24_codppa");
	 $cliframe_alterar_excluir->campos  ="o24_codseqppa,o24_codppa,o24_exercicio,o24_valor,o24_quantmed,o24_proces,o56_elemento,o15_codigo";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="160";
	 $cliframe_alterar_excluir->iframe_width ="700";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
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
for($i=$o21_anoini; $i<= $o21_anofim; $i++){
  //rotina  para repetir os valores digitado na primeira linha para os campos abaixo
  if($i == $o21_anoini){
      echo "function js_quant(){\n";
        for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
	  echo "if(document.form1.o24_quantmed_$c.value=='' || document.form1.o24_quantmed_$c.value==0){";
          echo "  document.form1.o24_quantmed_$c.value = document.form1.o24_quantmed_$i.value;\n";
	  echo "}";
        }  
      echo "}";  
      
      echo "function js_valor(){\n";
        for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
          echo "valor= new Number(document.form1.o24_valor_$c.value);";
          echo "if(document.form1.o24_valor_$c.value!='' && valor == 0){\n";
          echo "  document.form1.o24_valor_$c.value = document.form1.o24_valor_$i.value;\n";
	  echo "}";
        }  
      echo "}";  
  }
  //final
?>



//elemento
function js_elemento_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppaval','db_iframe_orcelemento','func_orcelemento_orcs.php?funcao_js=parent.js_mostraorcelemento1_<?=$i?>|o56_elemento|o56_descr','Pesquisa',true,'0','1','775','390');
  }else{
    elemento = document.form1.o56_elemento_<?=$i?>.value;
    if(elemento != ''){
      while(elemento.length<7){
	elemento = elemento+"0";
      }
       document.form1.o56_elemento_<?=$i?>.value=elemento;
      js_OpenJanelaIframe('top.corpo.iframe_orcppaval','db_iframe_orcelemento','func_orcelemento_orcs.php?pesquisa_chave='+document.form1.o56_elemento_<?=$i?>.value+'&funcao_js=parent.js_mostraorcelemento_<?=$i?>','Pesquisa',false);
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
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o56_elemento_$c.value = document.form1.o56_elemento_$i.value;";
     }  
  }
  ?>
  
}

function js_formataro24_valor(){
  <?
  for($c=$o21_anoini; $c<= $o21_anofim; $c++){
    echo "valor= new Number(document.form1.o24_valor_$c.value);";
    echo "if(document.form1.o24_valor_$c.value!='' || valor == 0){\n";
    echo "  valorecebe = new Number(document.form1.o24_valor_$c.value);\n";
    echo "  document.form1.o24_valor_$c.value = valorecebe.toFixed(2);\n";
    echo "}\n";
  } 
  ?>  
}

function js_mostraorcelemento1_<?=$i?>(chave1,chave2){
  document.form1.o56_elemento_<?=$i?>.value = chave1;
  <?
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o56_elemento_$c.value = chave1;";
     }  
  }
  ?>
  db_iframe_orcelemento.hide();
}
//recurso
function js_codigo_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcppaval','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1_<?=$i?>|o15_codigo|o15_descr','Pesquisa',true,'0','1','775','390');
  }else{
    if( document.form1.o26_codigo_<?=$i?>.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_orcppaval','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o26_codigo_<?=$i?>.value+'&funcao_js=parent.js_mostraorctiporec_<?=$i?>','Pesquisa',false);
     }       
  }
}
function js_mostraorctiporec_<?=$i?>(chave,erro){
//  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o26_codigo_<?=$i?>.focus(); 
    document.form1.o26_codigo_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o26_codigo_$c.value = document.form1.o26_codigo_$i.value ;";
     }  
  }
  ?>
}
function js_mostraorctiporec1_<?=$i?>(chave1,chave2){
  document.form1.o26_codigo_<?=$i?>.value = chave1;
//  document.form1.o15_descr.value = chave2;
  <?
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o26_codigo_$c.value = document.form1.o26_codigo_$i.value ;";
     }  
  }
  ?>
  db_iframe_orctiporec.hide();
}
<?}?>
function js_formatarvaloresinicio(){
  <?
  for($c=$o21_anoini;$c<=$o21_anofim;$c++){
     echo "if(document.form1.o24_valor_$c.value!=''){";
     echo "  valorecebe = new Number(document.form1.o24_valor_$c.value);\n";
     echo "  document.form1.o24_valor_$c.value = valorecebe.toFixed(2);\n";
     echo "}";
  } 
  ?>
}

js_formatarvaloresinicio();
</script>