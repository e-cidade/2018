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
$clossoariopart->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("z01_nome");
$clrotulo->label("p58_codproc");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm02_i_codigo?>">
       <?=@$Lcm02_i_codigo?>
    </td>
    <td> 
<?
db_input('cm02_i_codigo',10,$Icm02_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm02_i_processo?>">
       <?
       db_ancora(@$Lcm02_i_processo,"js_pesquisacm02_i_processo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm02_i_processo',7,$Icm02_i_processo,true,'text',$db_opcao," onchange='js_pesquisacm02_i_processo(false);'")
?>
       <?
db_input('p58_codproc',10,$Ip58_codproc,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm02_i_proprietario?>">
       <?
       db_ancora(@$Lcm02_i_proprietario,"js_pesquisacm02_i_proprietario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm02_i_proprietario',7,$Icm02_i_proprietario,true,'text',$db_opcao," onchange='js_pesquisacm02_i_proprietario(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm02_c_quadra?>">
       <?=@$Lcm02_c_quadra?>
    </td>
    <td> 
<?
db_input('cm02_c_quadra',3,$Icm02_c_quadra,true,'text',$db_opcao,"")
?>
       <?=@$Lcm02_i_lote?>
<?
db_input('cm02_i_lote',10,$Icm02_i_lote,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm02_f_metragem?>">
     <b>Metragem</b>
    </td>
    <td> 
     <?db_input('cm02_f_metragem1',10,$Icm02_f_metragem1,true,'text',$db_opcao,"")?>x
     <?db_input('cm02_c_metragem2',10,$Icm02_c_metragem2,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm02_d_aquisicao?>">
       <?=@$Lcm02_d_aquisicao?>
    </td>
    <td> 
<?
db_inputdata('cm02_d_aquisicao',@$cm02_d_aquisicao_dia,@$cm02_d_aquisicao_mes,@$cm02_d_aquisicao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<?if(@$antigo==""){?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}?>
</form>
<script>
function js_pesquisacm02_i_proprietario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm02_i_proprietario.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.cm02_i_proprietario.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.cm02_i_proprietario.focus(); 
    document.form1.cm02_i_proprietario.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.cm02_i_proprietario.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisacm02_i_processo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_protprocesso','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_codproc','Pesquisa',true);
  }else{
     if(document.form1.cm02_i_processo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_protprocesso','func_protprocesso.php?pesquisa_chave='+document.form1.cm02_i_processo.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
     }else{
       document.form1.p58_codproc.value = ''; 
     }
  }
}
function js_mostraprotprocesso(chave,erro){
  document.form1.p58_codproc.value = chave; 
  if(erro==true){ 
    document.form1.cm02_i_processo.focus(); 
    document.form1.cm02_i_processo.value = ''; 
  }
}
function js_mostraprotprocesso1(chave1,chave2){
  document.form1.cm02_i_processo.value = chave1;
  document.form1.p58_codproc.value = chave2;
  db_iframe_protprocesso.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_ossoariopart','func_ossoariopart.php?funcao_js=parent.js_preenchepesquisa|cm02_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ossoariopart.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_imprime(){
 jan = window.open('cem2_jazigo001.php?cod=<?=$cm03_i_codigo?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>