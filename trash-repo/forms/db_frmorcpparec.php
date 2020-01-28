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
$clorcpparec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("c58_descr");
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
      if(campo_valor == "o27_valor"){
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

$result = $clorcppalei->sql_record($clorcppalei->sql_query_file($o27_codleippa,"o21_anoini,o21_anofim"));
db_fieldsmemory($result,0);
?>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To27_codleippa?>" colspan='2' align='left'>
       <?=@$Lo27_codleippa?>

<?
db_input('o27_codleippa',8,0,true,'text',3);
db_input("o27_proces",8,0,true,'hidden',1);
$testado ='ok';//variavel criada para testar no programa de entrada...
db_input("testado",8,0,true,'hidden',1);
?>
    </td>
  </tr>
<?
for($i=$o21_anoini; $i<= $o21_anofim; $i++){
    
     $x  = "o27_sequen_$i";
     $$x  = "";
    
     $x = "o27_valor_$i";
     $$x  = "";
      
     $x = "o57_fonte_$i";
     $$x = "";
     
     $x = "o27_obs_$i";
     $$x = "";

     $x = "o27_perc_$i";
     $$x = "";
      
   if(isset($o27_proces) && $o27_proces !='' && empty($novo) && empty($incluir) && empty($alterar) && empty($excluir) ){


     
    $result01 = $clorcpparec->sql_record($clorcpparec->sql_query("","*","o27_exercicio","o27_proces = $o27_proces and  o27_codleippa=$o27_codleippa and o27_exercicio=$i"));
    $numrows01 = $clorcpparec->numrows;
    if($numrows01>0){ 
       db_fieldsmemory($result01,0);
   
       $x  = "o27_sequen_$i";
       $$x  = $o27_sequen;
	  
       $x = "o27_valor_$i";
       $$x = $o27_valor;
       
       $x = "o27_obs_$i";
       $$x = $o27_obs;
       
       $x = "o27_perc_$i";
       $$x = $o27_perc;

       $x = "o27_concarpeculiar_$i";
       $$x = $o27_concarpeculiar;

       $x = "c58_descr_$i";
       $$x = $c58_descr;
   
       //retorna os dados do orcfontes
       $result01 = $clorcfontes->sql_record($clorcfontes->sql_query_file($o27_codfon,db_getsession("DB_anousu")));
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
db_input("o27_sequen_$i",8,$Io27_sequen,true,'hidden',1);
?>
  
  <fieldset>
<table border="0" cellpadding='0' cellspacing='0' width='100%'>
  <tr>
    <td nowrap title="<?=@$To27_exercicio?>">
       <?=@$Lo27_exercicio?>
    </td>
    <td> 
<?
$x = "o27_exercicio_$i";
if(empty($$x)){
  $$x = $i;
}
db_input("o27_exercicio_$i",4,$Io27_exercicio,true,'text',3)
?>
    </td>
    <td nowrap title="<?=@$To27_valor?>">
       <?=@$Lo27_valor?>
    </td>
    <td> 
<?
db_input("o27_valor_$i",8,$Io27_valor,true,'text',$db_opcao,($i == $o21_anoini?"onchange='js_valor();'":""));
?>
    </td>
    <td nowrap title="<?=@$To27_perc?>">
       <?=@$Lo27_perc?>
    </td>
    <td> 
<?
db_input("o27_perc_$i",8,$Io27_perc,true,'text',$db_opcao,($i == $o21_anoini?"onchange='js_perc();'":"onchange='js_calcula_perc();'"));
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
  </tr>
<?
  $xx = "o27_exercicio_$i";
  if (empty($$xx)){
    $$xx = $i;
  }
?>
  <tr>
    <td nowrap title="<?=@$To27_concarpeculiar?>"><?
       $x = "o27_concarpeculiar_".$i;
       $y = "c58_descr_".$i;

       if ($$xx > 2007){
         $tranca = $db_opcao;
       } else {
         $tranca = 3;
       }

       db_ancora(@$Lo27_concarpeculiar,"js_pesquisao27_concarpeculiar_$i(true,$i);",$tranca);
    ?></td>
    <td nowrap colspan="7">
    <?
      db_input($x,10,$Io27_concarpeculiar,true,"text",$tranca,"onChange='js_pesquisao27_concarpeculiar_$i(false,$i);'");
      db_input($y,50,0,true,"text",3);
    ?>
    </td>
  </tr>
  <tr>
    <td> 
	   <?=$Lo27_obs?>
    </td>
    <td colspan="7" align='left'>
       <?
         $x = "o27_obs_$i";
	 db_textarea($x,1,80,$Io27_obs,true,'text',$db_opcao);
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
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
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
  
  <?for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){?>
        valano = new Number("<?=($c-1)?>" );
        valor = new Number(eval("document.form1.o27_valor_"+valano+".value;"));
        perc  = new Number(eval("document.form1.o27_perc_<?=$c?>.value;"));
        valperc = ((valor*perc)/100)+valor;
        eval("document.form1.o27_valor_<?=$c?>.value="+valperc.toFixed(2)+";");
  <?}?>  
}  

<?
for($i=$o21_anoini; $i<= $o21_anofim; $i++){
  //rotina  para repetir os valores digitado na primeira linha para os campos abaixo
  
  if($i == $o21_anoini){
        echo "function js_valor(){\n";
           echo "if(document.form1.o27_valor_$i.value!=''){\n"; 
           for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
             echo "document.form1.o27_valor_$c.value = document.form1.o27_valor_$i.value;\n";
           }  
	   echo "}\n";
           echo "js_calcula_perc();\n";
        echo "}\n";  
	
      echo "function js_perc(){\n";
        echo "if(document.form1.o27_perc_$i.value!=''){\n"; 
        for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
          echo "document.form1.o27_perc_$c.value = document.form1.o27_perc_$i.value;\n";
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
    js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostrafonte1_<?=$i?>|o57_fonte|o57_descr','Pesquisa',true,'0','1');
  }else{
    fonte = document.form1.o57_fonte_<?=$i?>.value;
    if(fonte != ''){
      while(fonte.length<15){
	fonte = fonte+"0";
      }
       document.form1.o57_fonte_<?=$i?>.value=fonte;
      js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_orcfontes','func_orcfontes.php?pesquisa_chave='+document.form1.o57_fonte_<?=$i?>.value+'&funcao_js=parent.js_mostrafonte_<?=$i?>','Pesquisa',false);
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
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o57_fonte_$c.value = document.form1.o57_fonte_$i.value;";
     }  
  }
  ?>
  
}


function js_mostrafonte1_<?=$i?>(chave1,chave2){
document.form1.o57_fonte_<?=$i?>.value = chave1;
  <?
  if($i == $o21_anoini){
     for($c=$o21_anoini+1; $c<= $o21_anofim; $c++){
        echo "document.form1.o57_fonte_$c.value = chave1;";
     }  
  }
  ?>
  db_iframe_orcfontes.hide();
}
function js_pesquisao27_concarpeculiar_<?=$i?>(mostra,ano){
  var obj_o27_concarpeculiar = eval("document.form1.o27_concarpeculiar_"+ano);
  var obj_c58_descr          = eval("document.form1.c58_descr_"+ano);

  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1_<?=$i?>|c58_sequencial|c58_descr&filtro=receita','Pesquisa',true,'0','1');
  }else{
     if(obj_o27_concarpeculiar.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_caponcarpeculiar','func_concarpeculiar.php?pesquisa_chave='+obj_o27_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar_<?=$i?>&filtro=receita','Pesquisa',false);
     }else{
       obj_c58_descr.value = ''; 
     }
  }
}
function js_mostraconcarpeculiar_<?=$i?>(chave,erro){
  document.form1.c58_descr_<?=$i?>.value = chave; 
  if(erro==true){ 
    document.form1.o27_concarpeculiar_<?=$i?>.focus(); 
    document.form1.o27_concarpeculiar_<?=$i?>.value = ''; 
  }
}
function js_mostraconcarpeculiar1_<?=$i?>(chave1,chave2){
  document.form1.o27_concarpeculiar_<?=$i?>.value = chave1;
  document.form1.c58_descr_<?=$i?>.value          = chave2;
  db_iframe_concarpeculiar.hide();
}
<?}?>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_orcpparec','db_iframe_orcppalei','func_orcppalei.php?funcao_js=parent.js_preenchepesquisa|o21_codleippa','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
   document.form1.o27_codleippa.value=chave;
   document.form1.submit();
}
</script>