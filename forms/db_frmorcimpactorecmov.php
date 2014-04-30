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
$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("o69_codigo");
//if(isset($incluir) && $sqlerro == false){
//  $db_opcao=2;
//}else 
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
  liberado = false;
  for(i=0; i<obj.elements.length; i++){
    if(obj.elements[i].type == 'text'){
       campo_valor =  obj.elements[i].name.substr(0,9);//o24_valor
       ano         =  obj.elements[i].name.substr(10);//o24_valor
      if(campo_valor == "o69_valor"){
	valor = obj.elements[i].value;
	if(valor != ''){
	    recurso = eval("document.form1.o57_fonte_"+ano+".value;");
	    if(recurso == ''){
	      alert("Preencha a receita para o  ano "+ano+"." );
              return false;
	    }else{
	      liberado = true;
	    }
	}
      }
    } 
  }
  if(liberado == false){
    alert("Informe os campos Valor e  Receita pelo menos de um ano.");
    return false;
  }
  return true;
}
</script>
<form name="form1" method="post" action="">
<center>


<?

$result = $clorcimpactoperiodo->sql_record($clorcimpactoperiodo->sql_query_file($o69_codperiodo,"o96_anoini,o96_anofim"));
db_fieldsmemory($result,0);
?>
<table>
  <tr>
    <td nowrap title="<?=@$To69_codperiodo?>" colspan='2' align='left'>
       <?=@$Lo69_codperiodo?>

<?
db_input('o69_codperiodo',8,0,true,'text',3);
$testado ='ok';//variavel criada para testar no programa de entrada...
db_input("testado",8,0,true,'hidden',1);
echo "<b>Processo:</b>";
db_input("o69_proces",8,0,true,'text',3);
echo "<b>Impacto:</b>";
db_input('o63_codimpger',4,0,true,'text',3);
?>
    </td>
  </tr>
<?
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
    
     $x  = "o69_sequen_$i";
     $$x  = "";
    
     $x = "o69_valor_$i";
     $$x  = "";
      
     $x = "o57_fonte_$i";
     $$x = "";
     
     $x = "o69_codigo_$i";
     $$x = "";
     
     $x = "o69_obs_$i";
     $$x = "";

     $x = "o69_perc_$i";
     $$x = "";
      
   //echo "if(isset($o69_proces) && $o69_proces !='' && empty($novo) && empty($incluir) && empty($alterar) && empty($excluir) ){";
   if(isset($o69_proces) && $o69_proces !='' && empty($novo) && empty($incluir) && empty($alterar) && empty($excluir) ){

     
    $result01 = $clorcimpactorecmov->sql_record($clorcimpactorecmov->sql_query_file("","*","o69_exercicio","o69_proces = $o69_proces and o69_codperiodo=$o69_codperiodo and o69_exercicio=$i"));
    $numrows01 = $clorcimpactorecmov->numrows;
    if($numrows01>0){ 
       db_fieldsmemory($result01,0);
   
       $x  = "o69_sequen_$i";
       $$x  = $o69_sequen;
	  
       $x = "o69_valor_$i";
       $$x = $o69_valor;
       
       $x = "o69_obs_$i";
       $$x = $o69_obs;
       
       $x = "o69_perc_$i";
       $$x = $o69_perc;
       
       $x = "o69_codigo_$i";
       $$x = $o69_codigo;
   
       //retorna os dados do orcfontes
       $result01 = $clorcfontes->sql_record($clorcfontes->sql_query_file($o69_codfon,db_getsession("DB_anousu")));
       if($clorcfontes->numrows>0){
         db_fieldsmemory($result01,0);
         $x = "o57_fonte_$i";
         $$x = $o57_fonte;
       }  	 
     }
     
   }

?>
<tr>
  <td>
<?
db_input("o69_sequen_$i",8,$Io69_sequen,true,'hidden',1);
?>
  
  <fieldset>
<table border="0" cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td nowrap title="<?=@$To69_exercicio?>">
       <?=@$Lo69_exercicio?>
    </td>
    <td> 
<?
$x = "o69_exercicio_$i";
if(empty($$x)){
  $$x = $i;
}
db_input("o69_exercicio_$i",4,$Io69_exercicio,true,'text',3);

?>
    </td>
    <td nowrap title="<?=@$To69_valor?>">
       <?=@$Lo69_valor?>
    </td>
    <td> 
<?
db_input("o69_valor_$i",8,$Io69_valor,true,'text',$db_opcao,($i == $o96_anoini?"onchange='js_valor();'":""));
?>
    </td>
    <td nowrap title="<?=@$To69_perc?>">
       <?=@$Lo69_perc?>
    </td>
    <td> 
<?
db_input("o69_perc_$i",8,$Io69_perc,true,'text',$db_opcao,($i == $o96_anoini?"onchange='js_perc();'":"onchange='js_calcula_perc();'"));
?>
    </td>
    <td nowrap title="<?=@$To57_fonte?>">
       <?
       db_ancora(@$Lo57_fonte,"js_fonte_$i(true);",$db_opcao);
       ?>
    </td>
    <td align='left' width='38%'> 
    <?
       $x = "o57_fonte_$i";
    ?>
    <input type="text"  value="<?=@$$x?>" <?=($db_opcao==3?"readOnly style='background-color:#DEB887;'":"")?>  name="o57_fonte_<?=$i?>" size="19" maxlength='15'  onKeyUp="js_ValidaCampos(this,1,'','','',event);" onKeyDown="return js_controla_tecla_enter(this,event);"    onchange='js_fonte_<?=$i?>(false);'>
    
    </td>
    <td nowrap title="<?=@$To69_codigo?>">
       <?
       db_ancora(@$Lo69_codigo,"js_codigo_$i(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$x = "o69_codigo_$i";
if(empty($$x)){
  $$x = 1;
}  
db_input("o69_codigo_$i",4,$Io69_codigo,true,'text',$db_opcao," onchange='js_codigo_$i(false);'")
?>
       <?
//db_input('o56_descr',30,$Io56_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td> 
	   <?=$Lo69_obs?>
    </td>
    <td colspan="9" align='left'>
       <?
         $x = "o69_obs_$i";
	 db_textarea($x,1,90,$Io69_obs,true,'text',$db_opcao);
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
function js_calcula_perc(){
  
  <?for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){?>
        valano = new Number("<?=($c-1)?>" );
        valor = new Number(eval("document.form1.o69_valor_"+valano+".value;"));
        perc  = new Number(eval("document.form1.o69_perc_<?=$c?>.value;"));
        valperc = ((valor*perc)/100)+valor;
        eval("document.form1.o69_valor_<?=$c?>.value="+valperc.toFixed(2)+";");
  <?}?>  
}  

<?
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
  //rotina  para repetir os valores digitado na primeira linha para os campos abaixo
  
  if($i == $o96_anoini){
        echo "function js_valor(){\n";
           echo "if(document.form1.o69_valor_$i.value!=''){\n"; 
           for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
             echo "document.form1.o69_valor_$c.value = document.form1.o69_valor_$i.value;\n";
           }  
	   echo "}\n";
           echo "js_calcula_perc();\n";
        echo "}\n";  
	
      echo "function js_perc(){\n";
        echo "if(document.form1.o69_perc_$i.value!=''){\n"; 
        for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
          echo "document.form1.o69_perc_$c.value = document.form1.o69_perc_$i.value;\n";
        }  
        echo "}\n";
        echo "js_calcula_perc();\n";
      echo "}\n";  
  }
  //final
?>
	


//elemento
function js_fonte_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactorecmov','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostrafonte1_<?=$i?>|o57_fonte|o57_descr','Pesquisa',true,'0','1','775','390');
  }else{
    fonte = document.form1.o57_fonte_<?=$i?>.value;
    if(fonte != ''){
      while(fonte.length<15){
	fonte = fonte+"0";
      }
       document.form1.o57_fonte_<?=$i?>.value=fonte;
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactorecmov','db_iframe_orcfontes','func_orcfontes.php?pesquisa_chave='+document.form1.o57_fonte_<?=$i?>.value+'&funcao_js=parent.js_mostrafonte_<?=$i?>','Pesquisa',false);
    }      
  }
}
function js_mostrafonte_<?=$i?>(chave,erro){
//  document.form1.o56_descr.value = chave; 
  if(erro==true){ 
    document.form1.o57_fonte_<?=$i?>.focus(); 
    document.form1.o57_fonte_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o57_fonte_$c.value = document.form1.o57_fonte_$i.value;";
     }  
  }
  ?>
  
}


function js_mostrafonte1_<?=$i?>(chave1,chave2){
document.form1.o57_fonte_<?=$i?>.value = chave1;
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o57_fonte_$c.value = chave1;";
     }  
  }
  ?>
  db_iframe_orcfontes.hide();
}
//-------------------------------------------------------------------------------------------------
//recurso
function js_codigo_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactorecmov','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1_<?=$i?>|o15_codigo|o15_descr','Pesquisa',true,'0','1','775','390');
  }else{
    if( document.form1.o69_codigo_<?=$i?>.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactorecmov','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o69_codigo_<?=$i?>.value+'&funcao_js=parent.js_mostraorctiporec_<?=$i?>','Pesquisa',false);
     }       
  }
}
function js_mostraorctiporec_<?=$i?>(chave,erro){
//  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o69_codigo_<?=$i?>.focus(); 
    document.form1.o69_codigo_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o69_codigo_$c.value = document.form1.o69_codigo_$i.value ;";
     }  
  }
  ?>
}
function js_mostraorctiporec1_<?=$i?>(chave1,chave2){
  document.form1.o69_codigo_<?=$i?>.value = chave1;
//  document.form1.o15_descr.value = chave2;
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o69_codigo_$c.value = document.form1.o69_codigo_$i.value ;";
     }  
  }
  ?>
  db_iframe_orctiporec.hide();
}

<?}?>





function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcimpactorecmov','db_iframe_orcimpactoperiodo','func_orcimpactoperiodo.php?funcao_js=parent.js_preenchepesquisa|o96_codperiodo','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
   document.form1.o69_codperiodo.value=chave;
   document.form1.submit();
}
</script>