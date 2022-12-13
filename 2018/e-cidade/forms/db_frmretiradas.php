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
$clretiradas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<fieldset>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm08_i_codigo?>">
       <?=@$Lcm08_i_codigo?>
    </td>
    <td> 
<?
db_input('cm08_i_sepultamento',10,$Icm08_i_sepultamento,true,'hidden',$db_opcao,"");
db_input('cm08_i_codigo',10,$Icm08_i_codigo,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
<!--  <tr>
    <td nowrap title="<?=@$Tcm08_i_sepultamento?>">
       <?
       db_ancora(@$Lcm08_i_sepultamento,"js_pesquisacm08_i_sepultamento(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm08_i_sepultamento',10,$Icm08_i_sepultamento,true,'text',$db_opcao," onchange='js_pesquisacm08_i_sepultamento(false);'")
?>
       <?
db_input('cm01_i_codigo',10,$Icm01_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
-->
  <tr>
    <td nowrap title="<?=@$Tcm08_i_retirante?>">
       <?
       db_ancora(@$Lcm08_i_retirante,"js_pesquisacm08_i_retirante(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm08_i_retirante',10,$Icm08_i_retirante,true,'text',$db_opcao," onchange='js_pesquisacm08_i_retirante(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm08_c_parentesco?>">
       <?=@$Lcm08_c_parentesco?>
    </td>
    <td> 
<?
db_input('cm08_c_parentesco',25,$Icm08_c_parentesco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm08_c_causa?>">
       <?=@$Lcm08_c_causa?>
    </td>
    <td> 
<?
db_input('cm08_c_causa',100,$Icm08_c_causa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm08_c_destino?>">
       <?=@$Lcm08_c_destino?>
    </td>
    <td> 
<?
db_input('cm08_c_destino',100,$Icm08_c_destino,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm08_d_retirada?>">
       <?=@$Lcm08_d_retirada?>
    </td>
    <td> 
<?
db_inputdata('cm08_d_retirada',@$cm08_d_retirada_dia,@$cm08_d_retirada_mes,@$cm08_d_retirada_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm08_t_obs?>">
       <?=@$Lcm08_t_obs?>
    </td>
    <td> 
<?
db_textarea('cm08_t_obs',5,40,$Icm08_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
</fieldset>
  <center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisacm08_i_sepultamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|cm01_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.cm08_i_sepultamento.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?pesquisa_chave='+document.form1.cm08_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos','Pesquisa',false);
     }else{
       document.form1.cm01_i_codigo.value = ''; 
     }
  }
}
function js_mostrasepultamentos(chave,erro){
  document.form1.cm01_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.cm08_i_sepultamento.focus(); 
    document.form1.cm08_i_sepultamento.value = ''; 
  }
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.cm08_i_sepultamento.value = chave1;
  document.form1.cm01_i_codigo.value = chave2;
  db_iframe_sepultamentos.hide();
}
function js_pesquisacm08_i_retirante(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm08_i_retirante.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm08_i_retirante.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.cm08_i_retirante.focus(); 
    document.form1.cm08_i_retirante.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.cm08_i_retirante.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_retiradas','func_retiradas.php?funcao_js=parent.js_preenchepesquisa|cm08_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_retiradas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>