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
$clorcimpactovalmov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o23_anoexe");
$clrotulo->label("o25_codele");
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("o67_codigo");
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
       campo_valor =  obj.elements[i].name.substr(0,9);//o64_valor
       ano         =  obj.elements[i].name.substr(10);//o64_valor
      if(campo_valor == "o64_valor"){
	valor = obj.elements[i].value;
	if(valor != ''){
	    recurso = eval("document.form1.o67_codigo_"+ano+".value;");
	    if(recurso == ''){
	      alert("Preencha o recurso para o  ano de "+ano+"." );
              return false;
	    }else{
	      liberado = true;
	    }
	}
      }
    } 
  }
  if(liberado == false){
    alert("Informe os campos Valor e  Recurso pelo menos de um ano.");
    return false;
  }
  return true;
}
</script>
<form name="form1" method="post" action="">
<center>

<?
db_input('o64_codimpmov',8,$Io64_codimpmov,true,'hidden',3);
db_input('o64_proces',8,$Io64_proces,true,'hidden',3);
?>

<?
      $result = $clorcimpactomov->sql_record($clorcimpactomov->sql_query_compl($o64_codimpmov,"o63_codperiodo,o96_anoini,o96_anofim")); 
      db_fieldsmemory($result,0);
?>
<table cellpadding='0' cellspacing='0'>
  <tr> 
    <td colspan='2' align='left'>
      <b>Tipo:</b>
      	
<?    
  if(isset($tipo) && $tipo=='I' && empty($chave_nova)){
    echo "<font  color='darkblue'><b>Impacto</b></font>";
  }else if(isset($tipo) && $tipo=='P' && empty($chave_nova)){
    echo "<font  color='darkblue'><b>Previsão</b></font>";
    
  }


  if(isset($tipo) && $tipo=='I' && empty($chave_nova)){
    $result03  = $clorcimpactomovpai->sql_record($clorcimpactomovpai->sql_query(null,null,"sum(o64_valor) as valor,o64_exercicio as exercicio","o64_exercicio","o86_codimpmovfilho=$o64_codimpmov group by o64_exercicio"));
    $numrows03 = $clorcimpactomovpai->numrows;
  }else if(isset($tipo) && $tipo=='P' && empty($chave_nova)){
    $result03  = $clorcimpactomovimp->sql_record($clorcimpactomovimp->sql_query_compl(null,null,"sum(o91_valor) as valor,o91_exercicio as exercicio","o91_exercicio","o68_codimpmov=$o64_codimpmov group by o91_exercicio"));
    $numrows03 = $clorcimpactomovimp->numrows;
  }  
    for($i=0; $i<$numrows03; $i++){
       db_fieldsmemory($result03,$i,1);
       $c = "valor_$exercicio";
       $$c = $valor;
    }
?>	
    </td> 	
  </tr>	
<?
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
    
     $x  = "o64_codseqimpmov_$i";
     $$x  = "";
    
     $x = "o64_valor_$i";
     $$x  = "";
      
     $x = "o56_elemento_$i";
     $$x = "";
     
     
     $x = "o67_codigo_$i";
     $$x = "";

      
   if(isset($o64_proces) && $o64_proces!='' && empty($novo) && empty($incluir) && empty($alterar)){
    $result = $clorcimpactovalmov->sql_record($clorcimpactovalmov->sql_query_file(null,"*","","o64_proces=$o64_proces and o64_exercicio=$i"));
    $numrows = $clorcimpactovalmov->numrows;
     if($numrows>0){ 
       db_fieldsmemory($result,0);
   
       $x  = "o64_codseqimpmov_$i";
       $$x  = $o64_codseqimpmov;
	  
       $x = "o64_valor_$i";
       $$x = $o64_valor;
       
   
       //retorna os dados do orcimpactotiporec 
       $result = $clorcimpactomovtiporec->sql_record($clorcimpactomovtiporec->sql_query_file($o64_codseqimpmov));
       if($clorcimpactomovtiporec->numrows>0){
         db_fieldsmemory($result,0);
         $x = "o67_codigo_$i";
         $$x = $o67_codigo;
       }  	 
       
       //retorna os dados do orcimpactovaele 
       $result = $clorcimpactovalmovele->sql_record($clorcimpactovalmovele->sql_query($o64_codseqimpmov,'',"o56_elemento"));
       if($clorcimpactovalmovele->numrows>0){
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
db_input("o64_codseqimpmov_$i",8,$Io64_codseqimpmov,true,'hidden',1);
?>
  
  <fieldset>
<table border="0" cellpadding='0' cellspacing='0'>
  <tr>
    <td nowrap title="<?=@$To64_exercicio?>">
       <?=@$Lo64_exercicio?>
    </td>
    <td> 
<?
$x = "o64_exercicio_$i";
if(empty($$x)){
  $$x = $i;
}
db_input("o64_exercicio_$i",4,$Io64_exercicio,true,'text',3)
?>
    </td>
<?
   if($numrows03>0){
?>        
    <td nowrap title="<?=@$To64_valor?>">
      <b>Previsão</b>
    </td>
    <td> 
<?
db_input("valor_$i",8,$Io64_valor,true,'text',3);
?>
    </td>

<?     
   }  
?>
    
    <td nowrap title="<?=@$To64_valor?>">
       <?=@$Lo64_valor?>
    </td>
    <td> 
<?
db_input("o64_valor_$i",8,$Io64_valor,true,'text',$db_opcao,($i == $o96_anoini?"onchange='js_valor();'":""));
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
    <td nowrap title="<?=@$To67_codigo?>">
       <?
       db_ancora(@$Lo67_codigo,"js_codigo_$i(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$x = "o67_codigo_$i";
if(empty($$x)){
  $$x = 1;
}  
db_input("o67_codigo_$i",4,$Io67_codigo,true,'text',$db_opcao," onchange='js_codigo_$i(false);'")
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
 <table cellpadding='0' cellspacing='0'> 
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("o64_codseqimpmov"=>@$o64_codseqimpmov,"o64_proces"=>@$o64_proces);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clorcimpactovalmov->sql_query_dad(null,"o64_codseqimpmov,o64_codimpmov,o64_exercicio,o64_valor,o64_proces,o56_elemento,o15_codigo","","o64_codimpmov =$o64_codimpmov");
	 $cliframe_alterar_excluir->campos  ="o64_codseqimpmov,o64_codimpmov,o64_exercicio,o64_valor,o64_proces,o56_elemento,o15_codigo";
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
for($i=$o96_anoini; $i<= $o96_anofim; $i++){
  //rotina  para repetir os valores digitado na primeira linha para os campos abaixo
  if($i == $o96_anoini){
      
      echo "function js_valor(){\n";
        for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
          echo "document.form1.o64_valor_$c.value = document.form1.o64_valor_$i.value;\n";
        }  
      echo "}";  
  }
  //final
?>



//elemento
function js_elemento_<?=$i?>(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactovalmov','db_iframe_orcelemento','func_orcelemento_orcs.php?funcao_js=parent.js_mostraorcelemento1_<?=$i?>|o56_elemento|o56_descr','Pesquisa',true,'0','1','775','390');
  }else{
    elemento = document.form1.o56_elemento_<?=$i?>.value;
    if(elemento != ''){
      while(elemento.length<7){
	elemento = elemento+"0";
      }
       document.form1.o56_elemento_<?=$i?>.value=elemento;
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactovalmov','db_iframe_orcelemento','func_orcelemento_orcs.php?pesquisa_chave='+document.form1.o56_elemento_<?=$i?>.value+'&funcao_js=parent.js_mostraorcelemento_<?=$i?>','Pesquisa',false);
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
    js_OpenJanelaIframe('top.corpo.iframe_orcimpactovalmov','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1_<?=$i?>|o15_codigo|o15_descr','Pesquisa',true,'0','1','775','390');
  }else{
    if( document.form1.o67_codigo_<?=$i?>.value != ''){
      js_OpenJanelaIframe('top.corpo.iframe_orcimpactovalmov','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o67_codigo_<?=$i?>.value+'&funcao_js=parent.js_mostraorctiporec_<?=$i?>','Pesquisa',false);
     }       
  }
}
function js_mostraorctiporec_<?=$i?>(chave,erro){
//  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o67_codigo_<?=$i?>.focus(); 
    document.form1.o67_codigo_<?=$i?>.value = ''; 
    return false;
  }
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o67_codigo_$c.value = document.form1.o67_codigo_$i.value ;";
     }  
  }
  ?>
}
function js_mostraorctiporec1_<?=$i?>(chave1,chave2){
  document.form1.o67_codigo_<?=$i?>.value = chave1;
//  document.form1.o15_descr.value = chave2;
  <?
  if($i == $o96_anoini){
     for($c=$o96_anoini+1; $c<= $o96_anofim; $c++){
        echo "document.form1.o67_codigo_$c.value = document.form1.o67_codigo_$i.value ;";
     }  
  }
  ?>
  db_iframe_orctiporec.hide();
}



<?}?>
</script>