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
$clrenovacoes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("cm01_d_falecimento");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<?if(!isset($cm07_i_sepultamento)){?>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm07_i_sepultamento?>">
       <?
       db_ancora(@$Lcm07_i_sepultamento,"js_pesquisacm07_i_sepultamento(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('cm07_i_sepultamento',10,$Icm07_i_sepultamento,true,'text',3," onchange='js_pesquisacm07_i_sepultamento(false);'")
?>
       <?
db_input('nome',50,$Icm01_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
   <td colspan="2" align="center"><input type="submit" value="Processar" name="processar"></td>
  </tr>
</table>
<?}else{?>
<fieldset style="width: 70%">
<legend>Dados</legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm07_i_sepultamento?>">
      <?db_ancora(@$Lcm07_i_sepultamento,"js_pesquisacm07_i_sepultamento(true);",3);?>
    </td>
    <td>
      <?db_input('cm07_i_sepultamento',10,@$Icm07_i_sepultamento,true,'text',3," onchange='js_pesquisacm07_i_sepultamento(false);'")?>
      <?db_input('nome',50,$Icm01_i_codigo,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm01_d_falecimento?>">
      <?=@$Lcm01_d_falecimento?>
    </td>
    <td>
      <?db_input('cm01_d_falecimento',10,$Icm01_d_falecimento,true,'text',3,'')?>
    </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tcm07_i_codigo?>">
       <?=@$Lcm07_i_codigo?>
    </td>
    <td> 
      <?db_input('cm07_i_codigo',10,@$Icm07_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm07_d_ultima?>">
       <?=@$Lcm07_d_ultima?>
    </td>
    <td>
     <?db_inputdata('cm07_d_ultima',@$cm07_d_ultima_dia,@$cm07_d_ultima_mes,@$cm07_d_ultima_ano,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm07_d_vencimento?>">
       <?=@$Lcm07_d_vencimento?>
    </td>
    <td>
     <?db_inputdata('cm07_d_vencimento2',@$cm07_d_vencimento_dia,@$cm07_d_vencimento_mes,@$cm07_d_vencimento_ano,true,'text',3,"")?>
    </td>
  </tr>
</table>
</fieldset>
<br>
<fieldset style="width: 70%">
<legend>Renovação</legend>
 <table>
  <tr>
    <td nowrap title="<?=@$Tcm07_i_renovante?>">
      <?db_ancora(@$Lcm07_i_renovante,"js_pesquisacm07_i_renovante(true);",$db_opcao);?>
    </td>
    <td> 
      <?db_input('cm07_i_renovante',10,$Icm07_i_renovante,true,'text',$db_opcao," onchange='js_pesquisacm07_i_renovante(false);'")?>
      <?db_input('z01_renovante',40,@$z01_renovante,true,'text',3,'')?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm07_c_motivo?>">
       <?=@$Lcm07_c_motivo?>
    </td>
    <td> 
     <?db_input('cm07_c_motivo',40,$Icm07_c_motivo,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm07_d_vencimento?>">
       <?=@$Lcm07_d_vencimento?>
    </td>
    <td> 
<?
db_inputdata('cm07_d_vencimento',"","","",true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<tr><td coilspan="2" align="center"><input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> ></td></tr>
</table>
</fieldset>
<?}?>
</form>
</center>
<script>
function js_pesquisacm07_i_sepultamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm07_i_sepultamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?pesquisa_chave='+document.form1.cm07_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostrasepultamentos(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){ 
    document.form1.cm07_i_sepultamento.focus(); 
    document.form1.cm07_i_sepultamento.value = ''; 
  }
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.cm07_i_sepultamento.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_sepultamentos.hide();
}
function js_pesquisacm07_i_renovante(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm07_i_renovante.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm07_i_renovante.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  if(document.form1.cm07_i_renovante.value == document.form1.cm07_i_sepultamento.value){
  	alert('Aviso!\nCgm informado para o renovante é o mesmo para o Sepultamento!');
	erro=true;
  }		
  document.form1.z01_renovante.value = chave; 
  if(erro==true){ 
    document.form1.cm07_i_renovante.focus(); 
    document.form1.cm07_i_renovante.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  if(chave1 == document.form1.cm07_i_sepultamento.value){
  	alert('Aviso!\nCgm informado para o renovante é o mesmo para o Sepultamento!');
	return false;
  }			
  document.form1.cm07_i_renovante.value = chave1;
  document.form1.z01_renovante.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_renovacoes','func_renovacoes.php?funcao_js=parent.js_preenchepesquisa|cm07_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_renovacoes.hide();
  <?
  if($db_opcao !=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>