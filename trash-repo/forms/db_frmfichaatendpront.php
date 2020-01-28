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
//echo
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

?>
<form name="form1" method="post" action="">
<center>
<table>
	<tr>
		<td>
  			<fieldset><legend><b>Triagem</b></legend>
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
  
  <!-- MOTIVO -->
  <tr>
    <td nowrap title="<?=@$Tsd24_v_motivo?>">
       <?=@$Lsd24_v_motivo?>
    </td>
    <td colspan="3">
      <?
        db_input('sd24_v_motivo',74,$Isd24_v_motivo,true,'text',$db_opcao,'')
      ?>
    </td>
  </tr>
  
  <!-- MOTIVO SI/SIH
  <tr>
    <td nowrap title="<?=@$Tsd24_i_siasih?>" valign="middle" align="top">
       <?
       db_ancora(@$Lsd24_i_siasih,"js_pesquisasd24_i_siasih(true);",$db_opcao);
       ?>
    </td>
    <td valign="middle" align="top">
      <?
        //db_input('sd24_i_cid',10,$Isd24_i_cid,true,'hidden',$db_opcao);
        db_input('sd24_i_siasih',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd24_i_siasih(false);'")
      ?>
    </td>  
    <td>
      <?
        //db_input('sd70_c_nome',60,$Isd70_c_nome,true,'text',3,'')
         db_textarea('sd92_c_nome',1,57,@$Isd92_c_nome,true,'text',3,"")
      ?>
    </td>
  </tr>
  -->


  <tr>
    <td nowrap title="<?=@$Tsd24_v_pressao?>">
       <?=@$Lsd24_v_pressao?>
    </td>
    <td>
      <?db_input('sd24_v_pressao',10,$Isd24_v_pressao,true,'text',$db_opcao)?>
    </td>
    <td align="right" >
       <?=@$Lsd24_f_peso?>
      <?db_input('sd24_f_peso',10,$Isd24_f_peso,true,'text',$db_opcao)?>
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
  <!-- PROFISSIONAL 
  <tr>
    <td nowrap title="<?=@$Tsd24_i_profissional?>">
       <?
       db_ancora(@$Lsd24_i_profissional,"js_pesquisamedicos(true);",$db_opcao);
       ?>
    </td>
    <td>
      <?
        db_input('sd24_i_profissional',10,$Isd24_i_profissional,true,'text',$db_opcao,"onchange='js_pesquisamedicos(false);'");
        db_input('sd03_i_codigo',10,$Isd03_i_codigo,true,'text',$db_opcao,"onchange='js_pesquisamedicos(false);'")
      ?>
    </td>
    <td>
      <?
        db_input('z01_nome',60,@$Iz01_nome,true,'text',3,'')
      ?>
    </td>
  </tr>
  -->
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
          db_input('profissional',60,@$profissional,true,'text',3,'')
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
          db_input('rh70_descr',60,$Irh70_descr,true,'text',3,'');
          ?>
         </td>
       </tr>

  

  <!-- DIAGNOSTICO 
  <tr>
    <td nowrap title="<?=@$Tsd24_t_diagnostico?>">
       <?=@$Lsd24_t_diagnostico?>
    </td>
    <td colspan="3">
      <?
         $sd24_t_diagnostico=!isset($sd24_t_diagnostico)?' ':$sd24_t_diagnostico;
         db_textarea('sd24_t_diagnostico',1,74,@$sd24_t_diagnostico,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

  <!-- CID 
  <tr>
    <td nowrap title="<?=@$Tsd70_c_cid?>" valign="top" align="top">
       <?
       db_ancora(@$Lsd70_c_cid,"js_pesquisasd70_c_cid(true);",$db_opcao);
       ?>
    </td>
    <td valign="top" align="top">
      <?
        db_input('sd24_i_cid',10,$Isd24_i_cid,true,'hidden',$db_opcao,"");
        db_input('sd70_c_cid',10,$Isd70_c_cid,true,'text',$db_opcao,"onchange='js_pesquisasd70_c_cid(false);'")
      ?>
    </td>  
    <td>
      <?
        //db_input('sd70_c_nome',60,$Isd70_c_nome,true,'text',3,'')
         db_textarea('sd70_c_nome',1,57,@$Isd70_c_nome,true,'text',3,"")
      ?>
    </td>
  </tr>
-->  
  </table>
			</fieldset>
		</td>
	</tr>
</table>
  </center>
<p>

<!-- input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> -->
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="Prosseguir"  <?=($db_botao==false?"disabled":"")?> >
<input name="limpartriagem" type="button" id="limpartriagem" value="Limpar Triagem" onclick="js_limpartriagem(<?=$chavepesquisaprontuario?>)">
<input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_reload(<?=$chavepesquisaprontuario?>)">
<input name="emitir" type="button" value="Emitir FAA" onclick="js_emitirfaa(<?=$chavepesquisaprontuario?>)">

</form>
<script>

function js_limpartriagem(){
	if( confirm("Limpar todas informações?") ) {
		obj = document.form1;
		for( i=0; i<obj.length; i++ ){
			if( obj.elements[i].type == "text" ){
				obj.elements[i].value = "";
			}
		}
		obj.sd24_i_profissional.value = "";
		obj.rh70_sequencial.value = "";
		obj.incluir.click();
	}
}

function js_reload(){
	parent.mo_camada('a1');	
	parent.iframe_a1.location.href='sau4_fichaatendabas001.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'	
}


function js_emitirfaa(chave_sd29_i_prontuario){
  if(chave_sd29_i_prontuario != ''){
    query = 'chave_sd29_i_prontuario='+chave_sd29_i_prontuario;
    jan = window.open('<?=modeloFaa($oSauConfig->s103_i_modelofaa)?>?'+query,
    	                '',
    	                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
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
       document.form1.sd24_i_profissional.value='';
       document.form1.rh70_sequencial.value='';
       document.form1.rh70_estrutural.value='';
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

</script>