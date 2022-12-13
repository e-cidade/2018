<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: Laboratório
$clrotulo = new rotulocampo;
$clrotulo->label("la10_i_codigo");
$cllab_exame->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tla08_i_codigo?>">
       <?=@$Lla08_i_codigo?>
    </td>
    <td> 
<?
db_input('la08_i_codigo',10,$Ila08_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla08_c_sigla?>">
       <?=@$Lla08_c_sigla?>
    </td>
    <td> 
<?
db_input('la08_c_sigla',10,$Ila08_c_sigla,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla08_c_descr?>">
       <?=@$Lla08_c_descr?>
    </td>
    <td> 
<?
db_input('la08_c_descr',50,$Ila08_c_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla08_i_ativo?>">
       <?=$Lla08_i_ativo?>
    </td>
    <td> 
       <?
        $aX = array('1'=>'ATIVO','2'=>'DESATIVADO');
        db_select('la08_i_ativo', $aX, true, $db_opcao, "");
       ?>
    </td>
  </tr>
  <tr>
    <td colspan='2' nowrap>
      <fieldset style='width: 55%; display: inline;'> <legend><b>Idades</b></legend>
        <table width='100%'> 
          <tr>     
            <td nowrap title="<?=@$Tla08_i_idademin?>">
              <?=@$Lla08_i_idademin?>
            </td>
            <td nowrap> 
              <?
              db_input('la08_i_idademin',10,$Ila08_i_idademin,true,'text',$db_opcao,"");
              $aX = array('3'=>'ANOS', '2'=>'MESES', '1'=>'DIAS');
              db_select('la08_i_undidadeini', $aX, true, $db_opcao, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tla08_i_idademax?>">
              <?=@$Lla08_i_idademax?>
            </td>
            <td nowrap> 
              <?
              db_input('la08_i_idademax',10,$Ila08_i_idademax,true,'text',$db_opcao,"");
              $aX = array('3'=>'ANOS', '2'=>'MESES', '1'=>'DIAS');
              db_select('la08_i_undidadefim', $aX, true, $db_opcao, '');
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

      <fieldset style='width: 45%; display: inline;'> <legend><b>Validade</b></legend>
        <table width='100%'> 
          <tr>
            <td nowrap title="<?=@$Tla08_d_inicio?>">
              <?=@$Lla08_d_inicio?>
            </td>
            <td> 
              <?
              db_inputdata('la08_d_inicio',@$la08_d_inicio_dia,@$la08_d_inicio_mes,@$la08_d_inicio_ano,true,"text",$db_opcao,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tla08_d_fim?>">
              <?=@$Lla08_d_fim?>
            </td>
            <td> 
              <?
              db_inputdata('la08_d_fim',@$la08_d_fim_dia,@$la08_d_fim_mes,@$la08_d_fim_ano,true,'text',$db_opcao,"onchange=\"js_validaData();\"","","","parent.js_validaData();")
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla08_i_sexo?>">
       <?=@$Lla08_i_sexo?>
    </td>
    <td> 
     <input type="checkbox" id="chk_masc" name="chk_masc" value="1" <?=$db_opcao == 3 ? 'disabled ' : ''?> onchange=""  <?=(@$la08_i_sexo==1)||(@$la08_i_sexo==3)?'checked':''?> >Masculino
     <input type="checkbox" id="chk_fem" name="chk_fem" value="2" <?=$db_opcao == 3 ? 'disabled ' : ''?>  onchange=""  <?=(@$la08_i_sexo==2)||(@$la08_i_sexo==3)?'checked':''?>>Feminino
     <!--
     <input type="checkbox" name="chk_amb" value="4" onchange="js_sexo(<?=$db_opcao?>, this)" <?=$db_opcao != 1?'disabled1':''?> <?=@$la08_i_sexo==4?'checked':''?> >Ambos<br>
     -->
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla08_i_dias?>">
       <?=@$Lla08_i_dias?>
    </td>
    <td> 
<?
db_input('la08_i_dias',10,$Ila08_i_dias,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tla08_i_gerar?>">
       <?=@$Lla08_i_gerar?>
    </td>
    <td> 
<input type="checkbox" name="chk_mapa" id="chk_mapa" value="1" <?=$db_opcao == 3 ? 'disabled ' : ''?>onchange="" <?=((@$la08_i_gerar==1)||(@$la08_i_gerar==3)||(@$la08_i_gerar==5)||(@$la08_i_gerar==7))?'checked':''?>> Mapa
<input type="checkbox" name="chk_etiqueta1" id="chk_etiqueta1" value="2"  <?=$db_opcao == 3 ? 'disabled ' : ''?> onchange="" <?=((@$la08_i_gerar==2)||(@$la08_i_gerar==3)||(@$la08_i_gerar==6)||(@$la08_i_gerar==7))?'checked':''?> > Etiqueta na coleta
<input type="checkbox" name="chk_etiqueta2" id="chk_etiqueta2" value="4" <?=$db_opcao == 3 ? 'disabled ' : ''?> onchange=""  <?=((@$la08_i_gerar==4)||(@$la08_i_gerar==5)||(@$la08_i_gerar==6)||(@$la08_i_gerar==7))?'checked':''?> > Etiqueta na triagem
       
<?
//db_input('la08_i_gerar',10,$Ila08_i_gerar,true,'text',$db_opcao,"")
?>
    </td>
  </tr>

   <tr>
      <td>
          <?db_ancora(@$Lla10_i_codigo,"js_pesquisala18_i_sinonima(true);",$db_opcao);?>  
      </td>
      <td colspan="2">
                <? $rResult=pg_query("select la10_i_codigo as chave, la10_c_descr as descricao from lab_sinonima");?>
                   <? /*<select name="sinonimia" >
                         <?for($x=0;$x<pg_num_rows($rResult);$x++){
                              db_fieldsmemory($rResult,$x);
                              echo"<option value=\"$chave\"> $descricao </option>";
                          }
                         ?>
                       </select> */ ?>
                   <?db_input('la18_i_sinonima',5,"",true,'text',$db_opcao," onchange='js_pesquisala18_i_sinonima(false);'onFocus=\"nextfield='la08_c_sigla'\"")?>
                   <?db_input('la10_c_descr',35,"",true,'text',$db_opcao,'')?>
                <input type="button" name="lanc" id="lanc" value="Lancar" <?=$db_opcao == 3 ? 'disabled ' : ''?> onclick="js_lanc();"><br>
                <select name="boxsinonimia" id="boxsinonimia" size="5" value="" ondblclick="js_delete();" style="width: 300" <?=$db_opcao == 3 ? 'disabled ' : ''?>>
                </select><br><font size="1.px">*Click duas vezes para deletar</font>
                <input name="str_sinonimia" id="str_sinonimia" value="" type="hidden" >
                <input name="str_sinonimia2" id="str_sinonimia2" value="" type="hidden" > 
                <?
                   if(isset($aSinonimia)){
                      if(count($aSinonimia>0)){
                          echo"<script>";
                          for($x=0;$x<count($aSinonimia);$x++){
                              echo"document.form1.boxsinonimia.add(new Option('".$aSinonimia[$x][2]."','".$aSinonimia[$x][1]."'),null);  ";
                          }
                          echo"</script>";
                      }
                   }
                ?>
      <td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> 
       onclick="return js_montastr()">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
<script>
if(document.form1.la08_c_sigla.value==''){
	   document.form1.la08_c_sigla.focus();
	}
document.onkeydown = function(evt) {
	if (evt.keyCode == 13 ) {
			eval(" document.getElementById('"+nextfield+"').focus()" );
			return false;
		
	}else if( evt.keyCode == 39 && valor_types ){
		eval(" document.getElementById('"+nextfield+"').focus()" );
	}
}

// Autocomplete do medicamento
oAutoComplete = new dbAutoComplete(document.form1.la10_c_descr,'lab1_la_exame.RPC.php');
oAutoComplete.setTxtFieldId(document.getElementById('la18_i_sinonima'));
oAutoComplete.show();


function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_lab_exame','func_lab_exame.php?funcao_js=parent.js_preenchepesquisa|la08_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_lab_exame.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_montastr() {

     F=document.form1;

     F.str_sinonimia.value = '';
     F.str_sinonimia2.value = '';
     
     if(!js_validaIdades()) {
       return false;
     }
     
     if(!F.chk_masc.checked && !F.chk_fem.checked) {

       alert('Selecione pelo menos uma opção de sexo');
       return false;

     }

     if(!F.chk_mapa.checked && !F.chk_etiqueta1.checked && !F.chk_etiqueta2.checked) {

       alert('Selecione pelo menos uma opção de geração');
       return false;

     }

     var Tam1=F.boxsinonimia.length;
     if(Tam1==0){
        alert('Selecione uma sinonimia!');
        return false;
     }
     sep='';
     for(x=0;x<Tam1;x++){
       F.str_sinonimia.value += sep+F.boxsinonimia.options[x].value;
       F.str_sinonimia2.value += sep+F.boxsinonimia.options[x].text;
       sep=',';
     }

     return true
}

/*function js_validaData() {
	
  if(document.form1.la08_d_inicio.value != ''  && document.form1.la08_d_fim.value != '' ) {

    aIni = document.form1.la08_d_inicio.value.split('/');
    aFim = document.form1.la08_d_fim.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

  	if(dFim < dIni) {
		
      alert("Data final não pode ser menor que a data inicial.");
			document.form1.la08_d_fim.value = '';
      return false;

		}
	  return true;

  } else {

    alert('Preencha as datas de validade.');
    return false

  }

}*/
function js_validaIdades() {

  oF = document.form1;

  if(oF.la08_i_idademin.value == ''  || oF.la08_i_idademax.value == '') {

    alert('Preencha os campos de idade.');
    return false
  }

  if(isNaN(oF.la08_i_idademin.value) || isNaN(oF.la08_i_idademax.value) || oF.la08_i_idademin.value < 0 || oF.la08_i_idademax.value < 0) {

    alert('Preencha corretamente os campos de idade.');
    return false
  }

  iNdiasmin = parseInt(oF.la08_i_idademin.value);
  iNdiasmax = parseInt(oF.la08_i_idademax.value);
   
  if(parseInt(oF.la08_i_undidadeini.value) == 2) {
    iNdiasmin *= 30;
  }

  if(parseInt(oF.la08_i_undidadeini.value) == 3) {
    iNdiasmin *= 365;
  }

  if(parseInt(oF.la08_i_undidadefim.value) == 2) {
    iNdiasmax *= 30;
  }

  if(parseInt(oF.la08_i_undidadefim.value) == 3) {
    iNdiasmax *= 365;
  }

  if(iNdiasmax < iNdiasmin) {

    alert('A idade maxima nao pode ser menor que a idade minima.');
    return false;
  }
 
  return true;

}


function js_lanc(){
    var F=document.form1;
    var Tam1=F.boxsinonimia.length;
    if(F.la10_c_descr.value==''){
       alert('Selecione uma sinonimia!');
       return false;
    }
    if(F.la18_i_sinonima.value!=''){
       for(x=0;x<Tam1;x++){
           if(F.la18_i_sinonima.value==F.boxsinonimia.options[x].value){
              alert('Sinonimia ja selecionado!');
              return false;
           }
       }
       cod=F.la18_i_sinonima.value;
    }else{
       cod=0;
    }
    F.boxsinonimia.add(new Option(F.la10_c_descr.value,cod),null);
    F.la18_i_sinonima.value='';
    F.la10_c_descr.value='';
}
function js_delete(){
    var F=document.form1;
    if(confirm('Excluir Sinonimia:'+F.boxsinonimia.options[F.boxsinonimia.selectedIndex].text+'?')){
       F.boxsinonimia.remove(F.boxsinonimia.selectedIndex);
    }
}

//Lookup sinonimia
   function js_pesquisala18_i_sinonima(mostra){
      F=document.form1;
      if(mostra==true){
          js_OpenJanelaIframe('','db_iframe_lab_sinonima','func_lab_sinonima.php?funcao_js=parent.js_mostrasinonima1|la10_i_codigo|la10_c_descr','Pesquisa',true);
      }else{ 
          if(F.la18_i_sinonima.value != ''){
             js_OpenJanelaIframe('','db_iframe_lab_sinonima','func_lab_sinonima.php?pesquisa_chave='+F.la18_i_sinonima.value+'&funcao_js=parent.js_mostrasinonima','Pesquisa',false)
          }else{
             F.la10_c_descr.value = '';
          }
      }
   }   
   function js_mostrasinonima(chave,erro){
      document.form1.la10_c_descr.value = chave;
      if(erro==true){
         document.form1.la18_i_sinonima.focus();
         document.form1.la18_i_sinonima.value = '';
      }
   }
   function js_mostrasinonima1(chave1,chave2){
      document.form1.la18_i_sinonima.value = chave1;
      document.form1.la10_c_descr.value = chave2;
      db_iframe_lab_sinonima.hide();
   } 


   function js_validaData() {
   	data=false;
   		  if(document.form1.la08_d_fim.value != ""  && document.form1.la08_d_inicio.value != "" ){
   				if(document.form1.la08_d_fim.value < document.form1.la08_d_inicio.value){
   					alert("Data final menor que a data inicial");
   					document.form1.la08_d_fim.value = "";
   				    document.form1.la08_d_fim_dia.value = "";
   				    document.form1.la08_d_fim_mes.value = "";
   		        	document.form1.la08_d_fim_ano.value = "";
   		    		data=false;
   				}	
   				
   		  }
   		  return data;
   		}
</script>