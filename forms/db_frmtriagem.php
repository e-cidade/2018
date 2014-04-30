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

//MODULO: saude
$clprontuarios->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd15_c_descr");
$clrotulo->label("sd70_c_cid");
$clrotulo->label("sd70_c_nome");
$clrotulo->label("sd92_c_nome");
$clrotulo->label("sd03_i_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("rh70_sequencial");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");
$clrotulo->label("sd04_i_cbo");
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">

 <tr>
    <td nowrap title="<?=@$Tsd24_i_codigo?>">
       <?=@$Lsd24_i_codigo?>
    </td>
    <td colspan="4">
      <?
        db_input('sd24_i_codigo',10,$Isd24_i_codigo,true,'text',3);
        db_input('sd24_i_unidade',10,$Isd24_i_unidade,true,'hidden',3);
      ?>
    </td>
  </tr>
  <!-- codigo paciente -->
  <tr>
    <td nowrap title="<?=@$Tz01_i_cgsund?>">
       <?=@$Lz01_i_cgsund?>
    </td>
    <td colspan="3">
      <?
        db_input('z01_i_cgsund',10,@$Iz01_i_cgsund,true,'text',3,''); echo"&nbsp;$Lz01_v_nome &nbsp;";db_input('z01_v_nome',40,@$Iz01_v_nome,true,'text',3,'');
      ?>
    </td>
  </tr>
  <!-- MOTIVO -->
  <tr>
    <td nowrap title="<?=@$Tsd24_v_motivo?>">
       <?=@$Lsd24_v_motivo?>
    </td>
    <td colspan="3">
      <?
        db_input('sd24_v_motivo',77,$Isd24_v_motivo,true,'text',$db_opcao,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd24_v_pressao?>">
       <?=@$Lsd24_v_pressao?>
    </td>
    <td>
      <?db_input('sd24_v_pressao',10,$Isd24_v_pressao,true,'text',$db_opcao)?>
    </td>
    <td align="right" >
       <?=@$Lsd24_f_peso?>
      <?db_input('sd24_f_peso',9,$Isd24_f_peso,true,'text',$db_opcao)?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tsd24_f_temperatura?>">
       <?=@$Lsd24_f_temperatura?>
    </td>
    <td colspan="4">
      <?db_input('sd24_f_temperatura',10,$Isd24_f_temperatura,true,'text',$db_opcao)?>
    </td>
  </tr>
  <tr>
      <td><b>Procedimentos <br> nao Faturaveis:</b></td>
      <td colspan="2"><select name="procedimentos" id="procedimentos" value="0">
                             <option Value="0">Selecione:::</option>
                             <?
                             $sql="select * from sau_procsemfatura";
                             $result=pg_query($sql);
                             $linhas=pg_num_rows($result);
                             for($x=0;$x<$linhas;$x++){
                                db_fieldsmemory($result,$x);
                                echo"<option value=\"$s146_i_codigo\">$s146_c_cod - $s146_c_descr</option>";
                             }?>
                      </select><input type="button" name="lanc" id="lanc" value="Lancar" onclick="js_lanc();"><br>
                      <select name="boxproc" id="boxproc" size="5" value="" ondblclick="js_delete();" style="width: 300">
                         <option value="0"><?for($x=1;$x<51;$x++){echo"&nbsp;";}?></option>
                      </select><br><font size="1.px">*Click duas vezes para deletar</font>
      <td>
  </tr>
  <!-- PROFISSIONAL -->
  <tr>
    <td nowrap title="<?=@$Tsd03_i_codigo?>">
       <?
       db_ancora(@$Lsd03_i_codigo,"js_pesquisasd03_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
       <?
          db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao," onchange='js_pesquisasd03_i_codigo(false);'")
       ?>
    </td>
    <td>
       <?
          db_input('profissional',63,@$profissional,true,'text',3,'')
       ?>
    </td>
  </tr>
  <!-- CBO -->
       <tr>
         <td nowrap title="<?=@$Tsd04_i_cbo?>">
            <?
            db_ancora(@$Lsd04_i_cbo,"js_pesquisasd04_i_cbo(true);",$db_opcao);
            ?>
         </td>
         <td>
          <?
          db_input('sd24_i_profissional',10,$Isd24_i_profissional,true,'hidden',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
          db_input('rh70_sequencial',10,$Irh70_sequencial,true,'hidden',$db_opcao,"");
          db_input('rh70_estrutural',10,$Irh70_estrutural,true,'text',$db_opcao," onchange='js_pesquisasd04_i_cbo(false);'");
          ?>
         </td>
         <td>
          <?
          db_input('rh70_descr',63,$Irh70_descr,true,'text',3,'');
          ?>
         </td>
       </tr>
  </table>
  </center>
<p>
<input name="pesquisar" type="button" id="pesquisar" value="Triagem" onclick="js_pesquisa02();" >
<input name="proceguir" type="submit" id="proseguir" value="Prosseguir" onclick="return js_carregaproc();">
<input name="emitir" type="button" value="Emitir FAA" onclick="js_emitirfaatriagem()">
<input type="hidden" value="" name="listaproc">
</form>
<script>
//carregar campos
<?
  if (isset($proc)) {
     
     $quant=count($proc);
     if($quant>0){
        echo"document.form1.boxproc.remove(0);";
     }
     for($x=0;$x<$quant;$x++){
        $vet=$proc[$x];
        echo"document.form1.boxproc.add(new Option('".$vet[1]." - ".$vet[2]."','".$vet[0]."'),null);";
     }

  }
?>

function js_emitirfaatriagem(){
  if(document.form1.sd24_i_codigo.value==""){
    alert('Pesquise uma FAA');
  }else{
    query = 'chave_sd29_i_prontuario='+document.form1.sd24_i_codigo.value;
    jan = window.open('<?=modeloFaa($oSauConfig->s103_i_modelofaa)?>?'+query,
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
    js_limpa();
  }
}

function js_pesquisasd24_i_siasih(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_siasih','func_sau_siasih.php?funcao_js=parent.js_mostrasd24_i_siasih1|sd92_i_codigo|sd92_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd24_i_profissional.value != ''){
        js_OpenJanelaIframe('','db_iframe_sau_siasih','func_sau_siasih.php?pesquisa_chave='+document.form1.sd24_i_siasih.value+'&funcao_js=parent.js_mostrasd24_i_siasih','Pesquisa',false);
     }else{
       document.form1.sd24_i_profissional.value = '';
     }
  }
}

function js_mostrasd24_i_siasih(chave, erro){
  document.form1.sd92_c_nome.value = chave;
  if(erro==true){ 
    document.form1.sd24_i_siasih.focus();
  }
}

function js_mostrasd24_i_siasih1(chave1,chave2){
  document.form1.sd24_i_siasih.value = chave1;
  document.form1.sd92_c_nome.value = chave2;
  db_iframe_sau_siasih.hide();
}


function js_pesquisasd03_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?funcao_js=parent.js_mostramedicos1|sd03_i_codigo|z01_nome&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',true);
  }else{
     if(document.form1.sd03_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_medicos','func_medicos.php?pesquisa_chave='+document.form1.sd03_i_codigo.value+'&funcao_js=parent.js_mostramedicos&chave_sd06_i_unidade='+document.form1.sd24_i_unidade.value,'Pesquisa',false);
     }else{
       document.form1.profissional.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.profissional.value = chave;
  if(erro==true){
    document.form1.sd03_i_codigo.focus();
    document.form1.sd03_i_codigo.value = '';
  }
  js_pesquisasd04_i_cbo(true);
}
function js_mostramedicos1(chave1,chave2){
  document.form1.sd03_i_codigo.value = chave1;
  document.form1.profissional.value = chave2;
  db_iframe_medicos.hide();
  js_pesquisasd04_i_cbo(true);
}


function js_pesquisasd70_c_cid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_cid','func_sau_cid.php?funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome','Pesquisa',true);
  }else{
     if(document.form1.sd70_c_cid.value != ''){
        //js_OpenJanelaIframe('','db_iframe_sau_cid','func_sau_cid.php?pesquisa_chave='+document.form1.sd70_c_cid.value+'&funcao_js=parent.js_mostrasd70_c_cid','Pesquisa',false);
        document.form1.sd24_i_cid.value = '';
        document.form1.sd70_c_nome.value = '';
        js_OpenJanelaIframe('','db_iframe_sau_cid','func_sau_cid.php?chave_sd70_c_cid='+document.form1.sd70_c_cid.value+'&funcao_js=parent.js_mostrasd70_c_cid1|sd70_i_codigo|sd70_c_cid|sd70_c_nome','Pesquisa',false);
        
     }else{
       document.form1.sd24_i_cid.value = '';
       document.form1.sd70_c_cid.value = '';
     }
  }
}
function js_mostrasd70_c_cid(chave, erro){
  document.form1.sd70_c_nome.value = chave;
  if(erro==true){
    document.form1.sd70_c_cid.focus();
    document.form1.sd70_c_cid.value = '';
  }
}
function js_mostrasd70_c_cid1(chave1,chave2,chave3){
  document.form1.sd24_i_cid.value = chave1;
  document.form1.sd70_c_cid.value = chave2;
  document.form1.sd70_c_nome.value = chave3;
  db_iframe_sau_cid.hide();
}

function js_emitirfaa(chave_sd29_i_prontuario){
  query = 'chave_sd29_i_prontuario='+chave_sd29_i_prontuario;
  jan = window.open('sau2_emitirfaa002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_pesquisasd04_i_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|rh70_sequencial&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_especmedico','func_especmedico.php?chave_rh70_estrutural='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostrarhcbo1|sd27_i_codigo|rh70_estrutural|rh70_descr|rh70_estrutural&chave_sd04_i_unidade='+document.form1.sd24_i_unidade.value+'&chave_sd04_i_medico='+document.form1.sd03_i_codigo.value,'Pesquisa',false);
        document.form1.rh70_estrutural.value = '';
        document.form1.rh70_descr.value = '';
     }else{
       document.form1.rh70_estrutural.value = '';
     }
  }
}
function js_mostrarhcbo(erro,chave1, chave2, chave3,chave4){
  document.form1.rh70_descr.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.sd24_i_profissional.value = chave3;
  document.form1.rh70_sequencial.value = chave4;
  if(erro==true){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2,chave3,chave4){
  document.form1.sd24_i_profissional.value = chave1;
  document.form1.rh70_estrutural.value = chave2;
  document.form1.rh70_descr.value = chave3;
  document.form1.rh70_sequencial.value = chave4;
  db_iframe_especmedico.hide();

  if(chave2=''){
    document.form1.rh70_estrutural.focus(); 
    document.form1.rh70_estrutural.value = ''; 
  }  
}

function js_pesquisa02(){
  js_OpenJanelaIframe('','db_iframe_triagem','func_triagem.php?funcao_js=parent.js_preenchepesquisa|sd24_i_codigo','Pesquisa',true);
}

function js_preenchepesquisa(chave){
  db_iframe_triagem.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisaprontuario='+chave";
  ?>
}
function js_lanc(){
    var F=document.form1;
    var Tam1=F.boxproc.length;
    if(F.procedimentos.options[F.procedimentos.selectedIndex].value=='0'){
       alert('Selecione um Procedimento!');
       return false;
    }
    if(Tam1==1){
       if(F.boxproc.options[0].value=='0'){
          F.boxproc.options[0].text=F.procedimentos.options[F.procedimentos.selectedIndex].text;
          F.boxproc.options[0].value=F.procedimentos.options[F.procedimentos.selectedIndex].value;
          return false;
       }
    }
    for(x=0;x<Tam1;x++){
       if(F.procedimentos.options[F.procedimentos.selectedIndex].value==F.boxproc.options[x].value){
           alert('Procedimento ja selecionado!');
           return false;
       }
    }
    F.boxproc.add(new Option(F.procedimentos.options[F.procedimentos.selectedIndex].text,
                             F.procedimentos.options[F.procedimentos.selectedIndex].value),null);
}
</script>
<script>
function js_delete(){
    var F=document.form1;
    if(F.boxproc.options[F.boxproc.selectedIndex].value=='0'){
       return false;
    }
    if(confirm('Excluir prcedimento:'+F.boxproc.options[F.boxproc.selectedIndex].text+'?')){
       F.boxproc.remove(F.boxproc.selectedIndex);   
       var Tam1=F.boxproc.length;
       if(Tam1==0){
          F.boxproc.add(new Option('','0'),null);
       }
    }
}
function js_carregaproc(){
   var F=document.form1;
   str="";
   F.listaproc.value="";
   var tam=F.boxproc.length;
   sep="";
   for(x=0;x<tam;x++){
      if(F.boxproc.options[x].value!='0'){
         str=str+sep+F.boxproc.options[x].value;
         sep=",";
      }
   }
   F.listaproc.value=str;
   return true;
}
</script>