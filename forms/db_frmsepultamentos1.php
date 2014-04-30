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

//MODULO: cemiterio
$clsepultamentos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("cm17_i_funeraria");
$clrotulo->label("cm18_i_hospital");
$clrotulo->label("cm14_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("cm04_c_descr");
?>
<form name="form1" method="post" action="">
<fieldset>
<table border="0">
  <tr>
    <td nowrap colspan="2">
      <?=@$Lcm01_c_livro?>
      <?db_input('cm01_c_livro',6,$Icm01_c_livro,true,'text',$db_opcao,"")?>
      <?=@$Lcm01_i_folha?>
      <?db_input('cm01_i_folha',10,$Icm01_i_folha,true,'text',$db_opcao,"")?>
      <?=@$Lcm01_i_registro?>
      <?db_input('cm01_i_registro',10,$Icm01_i_registro,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_i_medico?>">
       <?
       db_ancora(@$Lcm01_i_medico,"js_pesquisacm01_i_medico(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm01_i_medico',10,$Icm01_i_medico,true,'text',$db_opcao," onchange='js_pesquisacm01_i_medico(false);'")
?>
       <?
db_input('cm32_nome',40,@$cm32_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_i_causa?>">
       <?
       db_ancora(@$Lcm01_i_causa,"js_pesquisacm01_i_causa(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm01_i_causa',10,$Icm01_i_causa,true,'text',$db_opcao," onchange='js_pesquisacm01_i_causa(false);'")
?>
       <?
db_input('cm04_c_descr',40,$Icm04_c_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_c_local?>">
       <?=@$Lcm01_c_local?>
    </td>
    <td>
<?
db_input('cm01_c_local',40,$Icm01_c_local,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_c_cartorio?>">
       <?=@$Lcm01_c_cartorio?>
    </td>
    <td>
<?
db_input('cm01_c_cartorio',40,$Icm01_c_cartorio,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_i_hospital?>">
       <?
       db_ancora(@$Lcm01_i_hospital,"js_pesquisacm01_i_hospital(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm01_i_hospital',10,$Icm01_i_hospital,true,'text',$db_opcao," onchange='js_pesquisacm01_i_hospital(false);'")
?>
       <?
db_input('nome_hospital',40,$Icm18_i_hospital,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_i_funeraria?>">
       <?
       db_ancora(@$Lcm01_i_funeraria,"js_pesquisacm01_i_funeraria(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm01_i_funeraria',10,$Icm01_i_funeraria,true,'text',$db_opcao," onchange='js_pesquisacm01_i_funeraria(false);'")
?>
       <?
db_input('nome_funeraria',40,$Icm17_i_funeraria,true,'text',3,'')
       ?>
    </td>
  </tr>
 </table>
 </fieldset>
 <fieldset>
 <legend>Declarante</legend>
 <table>
  <tr>
    <td nowrap title="<?=@$Tcm07_i_renovante?>">
       <?
       db_ancora("<b>Declarante:</b>","js_pesquisacm01_i_declarante(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm01_i_declarante',10,$Icm01_i_declarante,true,'text',$db_opcao," onchange='js_pesquisacm01_i_declarante(false);'")
?>
       <?
db_input('nome_declarante',40,@$nome_declarante,true,'text',3,'')
       ?>
    </td>
  </tr>
<?if($db_opcao == 1){?>
    <tr>
    <td nowrap title="<?=@$Tcm07_d_vencimento?>">
       <b>Vencimento:</b>
    </td>
    <td>
<?
if(@$cm07_d_vencimento_dia == ""){
  $cm07_d_vencimento_dia = substr($cm01_d_falecimento,8,2);
}
if(@$cm07_d_vencimento_mes == ""){
   $cm07_d_vencimento_mes = substr($cm01_d_falecimento,5,2);
}
if(@$cm07_d_vencimento_ano == ""){
    $cm07_d_vencimento_ano = substr($cm01_d_falecimento,0,4)+5;
}


db_inputdata('cm07_d_vencimento',@$cm07_d_vencimento_dia,@$cm07_d_vencimento_mes,@$cm07_d_vencimento_ano,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <?}?>
  </table>
 </fieldset>
 <center>
<?if(!isset($sepultamento)){?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" -->
<?}?>
</center>
</form>
<script>
function js_pesquisacm01_i_funeraria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_funerarias','func_funerarias.php?funcao_js=parent.js_mostrafunerarias1|cm17_i_funeraria|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm01_i_funeraria.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_funerarias','func_funerarias.php?pesquisa_chave='+document.form1.cm01_i_funeraria.value+'&funcao_js=parent.js_mostrafunerarias','Pesquisa',false);
     }else{
       document.form1.nome_funeraria.value = '';
     }
  }
}
function js_mostrafunerarias(chave,erro){
  document.form1.nome_funeraria.value = chave;
  if(erro==true){
    document.form1.cm01_i_funeraria.focus();
    document.form1.cm01_i_funeraria.value = '';
  }
}
function js_mostrafunerarias1(chave1,chave2){
  document.form1.cm01_i_funeraria.value = chave1;
  document.form1.nome_funeraria.value = chave2;
  db_iframe_funerarias.hide();
}
function js_pesquisacm01_i_hospital(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_hospitais','func_hospitais.php?funcao_js=parent.js_mostrahospitais1|cm18_i_hospital|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm01_i_hospital.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_hospitais','func_hospitais.php?pesquisa_chave='+document.form1.cm01_i_hospital.value+'&funcao_js=parent.js_mostrahospitais','Pesquisa',false);
     }else{
       document.form1.nome_hospital.value = '';
     }
  }
}
function js_mostrahospitais(chave,erro){
  document.form1.nome_hospital.value = chave;
  if(erro==true){
    document.form1.cm01_i_hospital.focus();
    document.form1.cm01_i_hospital.value = '';
  }
}
function js_mostrahospitais1(chave1,chave2){
  document.form1.cm01_i_hospital.value = chave1;
  document.form1.nome_hospital.value = chave2;
  db_iframe_hospitais.hide();
}

function js_pesquisacm01_i_medico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_medicos','func_legista.php?funcao_js=parent.js_mostramedicos1|cm32_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm01_i_medico.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_medicos','func_legista.php?pesquisa_chave='+document.form1.cm01_i_medico.value+'&funcao_js=parent.js_mostramedicos','Pesquisa',false);
     }else{
       document.form1.cm32_nome.value = '';
     }
  }
}
function js_mostramedicos(chave,erro){
  document.form1.cm32_nome.value = chave;
  if(erro==true){
    document.form1.cm01_i_medico.focus();
    document.form1.cm01_i_medico.value = '';
  }
}
function js_mostramedicos1(chave1,chave2){
  document.form1.cm01_i_medico.value = chave1;
  document.form1.cm32_nome.value = chave2;
  db_iframe_medicos.hide();
}
function js_pesquisacm01_i_causa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_causa','func_causa.php?funcao_js=parent.js_mostracausa1|cm04_i_codigo|cm04_c_descr','Pesquisa',true);
  }else{
     if(document.form1.cm01_i_causa.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_causa','func_causa.php?pesquisa_chave='+document.form1.cm01_i_causa.value+'&funcao_js=parent.js_mostracausa','Pesquisa',false);
     }else{
       document.form1.cm04_c_descr.value = '';
     }
  }
}
function js_mostracausa(chave,erro){
  document.form1.cm04_c_descr.value = chave;
  if(erro==true){
    document.form1.cm01_i_causa.focus();
    document.form1.cm01_i_causa.value = '';
  }
}
function js_mostracausa1(chave1,chave2){
  document.form1.cm01_i_causa.value = chave1;
  document.form1.cm04_c_descr.value = chave2;
  db_iframe_causa.hide();
}

function js_pesquisacm01_i_declarante(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostradeclarante1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm01_i_declarante.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm01_i_declarante.value+'&funcao_js=parent.js_mostradeclarante','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostradeclarante(erro,chave){
  if(document.form1.cm01_i_declarante.value == <?=$cm01_i_codigo?>){
  	alert('Aviso!\n\nCgm informado para o declarante é o mesmo para o Sepultamento!');
	erro=true;
  }	
  document.form1.nome_declarante.value = chave;
  if(erro==true){
    document.form1.cm01_i_declarante.focus();
    document.form1.cm01_i_declarante.value = '';
    document.form1.nome_declarante.value = '';
  }
}
function js_mostradeclarante1(chave1,chave2){
  if(chave1 == <?=$cm01_i_codigo?> ){
  	alert('Aviso!\n\nCgm informado para o declarante é o mesmo para o Sepultamento!');
	return false;
  }		
  document.form1.cm01_i_declarante.value = chave1;
  document.form1.nome_declarante.value = chave2;
  db_iframe_cgm.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_a2','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_preenchepesquisa|cm01_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sepultamentos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>