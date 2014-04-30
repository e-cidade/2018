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

//MODULO: Laboratï¿½rio
$cllab_valorreferencia->rotulo->label();
$cllab_tiporeferenciaalfa->rotulo->label();
$cllab_tiporeferenciaalnumerico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("la27_i_codigo");
$clrotulo->label("la28_i_codigo");
$clrotulo->label("la13_c_descr");
$clrotulo->label("la25_c_descr");
$clrotulo->label("la51_i_valorrefsel");
?>
<fieldset style='width: 75%;' ><legend><b>Valor de Referencia</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla27_i_codigo?>">
       <?=@$Lla27_i_codigo?>
    </td>
    <td> 
<?
db_input('la27_i_codigo',10,$Ila27_i_codigo,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla27_i_unidade?>">
       <?
       db_ancora(@$Lla27_i_unidade,"js_pesquisala27_i_unidade(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la27_i_unidade',10,$Ila27_i_unidade,true,'text',$db_opcao," onchange='js_pesquisala27_i_unidade(false);'");
?>
       <?
db_input('la13_c_descr',40,$Ila13_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla27_i_atributo?>">
       <?
       db_ancora(@$Lla27_i_atributo,"js_pesquisala27_i_atributo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('la27_i_atributo',10,$Ila27_i_atributo,true,'text',$db_opcao," onchange='js_pesquisala27_i_atributo(false);'")
?>
       <?
db_input('la25_c_descr',40,$Ila25_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
      <td>
          <b><strong>Tipo do valor<strong></b>
      </td>
      <td>
          <?$aTipos= Array("0"=>"Selecione:::","1"=>"Alfanumerico","2"=>"Numerico");
             db_select("iTipo",$aTipos,"",$db_opcao,"onchange=\"js_trocatipo(this.value);\"");?>
      </td>
  </tr>
 </table>
 
 <!-- Tabela Tipo de referencial alpha -->
<fieldset style='width: 75%;'><legend><b>Alfanumerico<b></legend>
<table id="alfa" name="alfa" style="display:none" border="0">
  <tr>
    <td nowrap title="<?=@$Tla29_c_fixo?>">
       <?=$Lla29_i_fixo?>
    </td>
        <td>
        <?db_input('marcado',1,@$Imarcado,true,'checkbox',$db_opcao,"onclick='js_bloqueia(this.value)'");?></td>
    <td> 
<?
db_input('la29_i_codigo',10,$Ila29_i_codigo,true,'hidden',$db_opcao,"");
db_input('la29_i_fixo',10,$Ila29_i_fixo,true,'text',1,"onchange=\"js_validaTamanho();\"","","","parent.js_validaTamanho();");
?>
    </td>

  </tr>
     <tr>
      <td>
          <?=$Lla51_i_valorrefsel?>
      </td>
      <td colspan="2">
               <?
                $rResult=$cllab_valorreferenciasel->sql_record($cllab_valorreferenciasel->sql_query(""," la28_i_codigo as chave, la28_c_descr as descricao","",""));
                $aReferencialsel = array();
                for($x=0;$x<$cllab_valorreferenciasel->numrows;$x++){
                   db_fieldsmemory($rResult,$x);
                   $aReferencialsel[$chave] = $descricao;
                }
                db_select("la28_i_codigo",$aReferencialsel,$Ila28_i_codigo,$db_opcao,"");
                ?>
                <input type="button" name="lanc" id="lanc" value="Lancar" <?=$db_opcao == 3 ? 'disabled ' : ''?> onclick="js_lanc();"><br>
                <select name="boxValorRefSel" id="boxValorRefSel" size="5" value="" ondblclick="js_delete();" style="width: 300" <?=$db_opcao == 3 ? 'disabled ' : ''?>>
                </select><br><font size="1.px">*Click duas vezes para deletar</font>
                <input name="str_valorRefSel" id="str_ValorRefSel" value="" type="hidden" >
                <?
                   if(isset($aValorRefSel)){
                      if(count($aValorRefSel>0)){
                          echo"<script>";
                          for($x=0;$x<count($aValorRefSel);$x++){
                              echo"document.form1.boxValorRefSel.add(new Option('".$aValorRefSel[$x][2]."','".$aValorRefSel[$x][1]."'),null);  ";
                          }
                          echo"</script>";
                      }
                   }
                ?>
      <td>
  </tr>
  </table>
  </fieldset>

 <!-- Tabela Tipo de referencial numerico -->
<br>
<fieldset style='width: 75%;'><legend><b>Numerico</b></legend>
<table name="numerico" id="numerico" style="display:none" border="0">
  <tr>
    <td nowrap title="<?=@$Tla30_f_normalmin?>">
       <?=@$Lla30_f_normalmin?>
    </td>
    <td>
<?
db_input('la30_i_codigo',5,$Ila30_i_codigo,true,'hidden',$db_opcao,"");
db_input('la30_f_normalmin',5,$Ila30_f_normalmin,true,'text',$db_opcao,"onchange=\"js_validaNormal();\"","","","parent.js_validaNormal();");
?>
    </td>
    <td nowrap title="<?=@$Tla30_f_normalmax?>">
       <?=@$Lla30_f_normalmax?>
<?
db_input('la30_f_normalmax',5,$Ila30_f_normalmax,true,'text',$db_opcao,"onchange=\"js_validaNormal();\"","","","parent.js_validaNormal();")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_f_absurdomin?>">
       <?=@$Lla30_f_absurdomin?>
    </td>
    <td> 
<?
db_input('la30_f_absurdomin',5,$Ila30_f_absurdomin,true,'text',$db_opcao,"onchange=\"js_validaAbsurdo();\"","","","parent.js_validaAbsurdo();")
?>
    </td>
    <td nowrap title="<?=@$Tla30_f_absurdomax?>">
       <?=@$Lla30_f_absurdomax?>
<?
db_input('la30_f_absurdomax',5,$Ila30_f_absurdomax,true,'text',$db_opcao,"onchange=\"js_validaAbsurdo();\"","","","parent.js_validaAbsurdo();")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla30_c_calculavel?>">
       <?=@$Lla30_c_calculavel?>
    </td>
    <td colspan="2"> 
<?
db_input('la30_c_calculavel',50,$Ila30_c_calculavel,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
 
 </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> 
       onclick="return js_valida()">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
</fieldset>
<script>
document.form1.la29_i_fixo.disabled=true;
F = document.form1;
<?if(isset($la27_i_codigo)){
	echo("js_trocatipo(F.iTipo.value)");
}?>

function js_valida(){
	if(F.iTipo.value=='0'){
	   alert('Selecione um tipo!');
       return false;
    }
	F.str_ValorRefSel.value
	if((F.la29_i_fixo.value=='')||(F.la29_i_fixo.value=='0')){
	   var Tam1=F.boxValorRefSel.length;
       sep='';
       for(x=0;x<Tam1;x++){
           F.str_ValorRefSel.value += sep+F.boxValorRefSel.options[x].value;
           sep=',';
       }
    }
    return true;
}

function js_lanc(){
    var F=document.form1;
    var Tam1=F.boxValorRefSel.length;
    if(F.la28_i_codigo.value==''){
       alert('Selecione um referencial!');
       return false;
    }
    for(x=0;x<Tam1;x++){
       if(F.la28_i_codigo.value==F.boxValorRefSel.options[x].value){
          alert('Referencial ja selecionado!');
          return false;
       }
    }
    F.boxValorRefSel.add(new Option(F.la28_i_codigo.options[F.la28_i_codigo.selectedIndex].text,F.la28_i_codigo.value),null);
}
function js_delete(){
    var F=document.form1;
    if(confirm('Excluir Sinonimia:'+F.boxValorRefSel.options[F.boxValorRefSel.selectedIndex].text+'?')){
       F.boxValorRefSel.remove(F.boxValorRefSel.selectedIndex);
    }
}

function js_trocatipo(tipo){
    var alfa = document.getElementById('alfa');
    var numerico = document.getElementById('numerico');
    if(tipo==1){
        alfa.style.display='';
        numerico.style.display='none';
    }else if(tipo==2){
        alfa.style.display='none';
        numerico.style.display='';
    }else{
        alfa.style.display='none';
        numerico.style.display='none';
    }
}

// Lookup's
function js_pesquisala27_i_unidade(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?funcao_js=parent.js_mostralab_undmedida1|la13_i_codigo|la13_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la27_i_unidade.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_undmedida','func_lab_undmedida.php?pesquisa_chave='+document.form1.la27_i_unidade.value+'&funcao_js=parent.js_mostralab_undmedida','Pesquisa',false);
     }else{
       document.form1.la13_c_descr.value = ''; 
     }
  }
}
function js_mostralab_undmedida(chave,erro){
  document.form1.la13_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la27_i_unidade.focus(); 
    document.form1.la27_i_unidade.value = ''; 
  }
}
function js_mostralab_undmedida1(chave1,chave2){
  document.form1.la27_i_unidade.value = chave1;
  document.form1.la13_c_descr.value = chave2;
  db_iframe_lab_undmedida.hide();
}
function js_pesquisala27_i_atributo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&funcao_js=parent.js_mostralab_atributo1|la25_i_codigo|la25_c_descr','Pesquisa',true);
  }else{
     if(document.form1.la27_i_atributo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_atributo','func_lab_atributo.php?analitico=1&pesquisa_chave='+document.form1.la27_i_atributo.value+'&funcao_js=parent.js_mostralab_atributo','Pesquisa',false);
     }else{
       document.form1.la25_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_atributo(chave,erro){
  document.form1.la25_c_descr.value = chave; 
  if(erro==true){ 
    document.form1.la27_i_atributo.focus(); 
    document.form1.la27_i_atributo.value = ''; 
  }
}
function js_mostralab_atributo1(chave1,chave2){
  document.form1.la27_i_atributo.value = chave1;
  document.form1.la25_c_descr.value = chave2;
  db_iframe_lab_atributo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_lab_valorreferencia','func_lab_valorreferencia.php?funcao_js=parent.js_preenchepesquisa|la27_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_valorreferencia.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_validaNormal() {
normal=false;
  if(document.form1.la30_f_normalmax.value != ""  && document.form1.la30_f_normalmin.value != "" ){
    if(parseInt(F.la30_f_normalmax.value,10) < parseInt(F.la30_f_normalmin.value,10)){
   	  alert("Valor Normal Máximo menor que Valor Normal Mínimo");
   	  document.form1.la30_f_normalmax.value = "";
   	  normal=false;
    }   				
  }
  return normal;
}

function js_validaAbsurdo() {
  absurdo=false;
  if(document.form1.la30_f_absurdomax.value != ""  && document.form1.la30_f_absurdomin.value != "" ){
      if(parseInt(F.la30_f_absurdomax.value,10) < parseInt(F.la30_f_absurdomin.value,10)){
  	      alert("Valor Absurdo Máximo menor que Valor Absurdo Mínimo");
	      document.form1.la30_f_absurdomax.value = "";
	      absurdo=false;
	  }   				
  }
  return absurdo;
}


function js_bloqueia(valor){
	
  if (document.getElementById("marcado").checked==true) {	 
	document.getElementById("la29_i_fixo").disabled=false;
	document.getElementById("la29_i_fixo").readonly=false;	 	

  } else {
	document.getElementById("la29_i_fixo").value='';	
	document.getElementById("la29_i_fixo").disabled=true;	
  }
}

function js_validaTamanho() {
	tamanho=false;
	  if(document.form1.la29_i_fixo.value != ""){
		if(document.form1.la29_i_fixo.value == 0 || document.form1.la29_i_fixo.value > 100 ){
		  alert("Valor Fixo tem que ser maior que 1 e menor igual a 100");
		  document.form1.la29_i_fixo.value = "";
		  tamanho=false;
		}  
					
	  }
	  return tamanho;
	}
</script>