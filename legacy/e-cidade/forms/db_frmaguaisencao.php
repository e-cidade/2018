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

//MODULO: agua
$claguaisencao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("x29_descr");
      if($db_opcao==1){
 	   $db_action="agu1_aguaisencao004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="agu1_aguaisencao005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="agu1_aguaisencao006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx10_codisencao?>">
       <?=@$Lx10_codisencao?>
    </td>
    <td> 
<?
db_input('x10_codisencao',5,$Ix10_codisencao,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_codisencaotipo?>">
       <?
       db_ancora(@$Lx10_codisencaotipo,"js_pesquisax10_codisencaotipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x10_codisencaotipo',5,$Ix10_codisencaotipo,true,'text',$db_opcao," onchange='js_pesquisax10_codisencaotipo(false);'")
?>
       <?
db_input('x29_descr',40,$Ix29_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_matric?>">
       <?
       db_ancora(@$Lx10_matric,"js_pesquisax10_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x10_matric',10,$Ix10_matric,true,'text',$db_opcao," onchange='js_pesquisax10_matric(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_obs?>">
       <?=@$Lx10_obs?>
    </td>
    <td> 
<?
db_textarea('x10_obs',3,40,$Ix10_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_dtini?>">
       <?=@$Lx10_dtini?>
    </td>
    <td> 
<?
db_inputdata('x10_dtini',@$x10_dtini_dia,@$x10_dtini_mes,@$x10_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_dtfim?>">
       <?=@$Lx10_dtfim?>
    </td>
    <td> 
<?
db_inputdata('x10_dtfim',@$x10_dtfim_dia,@$x10_dtfim_mes,@$x10_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx10_processo?>">
       <?=@$Lx10_processo?>
    </td>
    <td> 
<?
db_input('x10_processo',5,$Ix10_processo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisax10_matric(mostra){
  if(mostra==true){
    //js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|z01_nome','Pesquisa',true,'0','1','775','390');
    js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|z01_nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.x10_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x10_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.x10_matric.focus(); 
    document.form1.x10_matric.value = ''; 
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x10_matric.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_aguabase.hide();
}
function js_pesquisax10_codisencaotipo(mostra){
  if(mostra==true){
    //js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguaisencaotipo','func_aguaisencaotipo.php?funcao_js=parent.js_mostraaguaisencaotipo1|x29_codisencaotipo|x29_descr','Pesquisa',true,'0','1','775','390');
    js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguaisencaotipo','func_aguaisencaotipo.php?funcao_js=parent.js_mostraaguaisencaotipo1|x29_codisencaotipo|x29_descr','Pesquisa',true,'0','1');
  }else{
     if(document.form1.x10_codisencaotipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguaisencaotipo','func_aguaisencaotipo.php?pesquisa_chave='+document.form1.x10_codisencaotipo.value+'&funcao_js=parent.js_mostraaguaisencaotipo','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.x29_descr.value = ''; 
     }
  }
}
function js_mostraaguaisencaotipo(chave,erro){
  document.form1.x29_descr.value = chave; 
  if(erro==true){ 
    document.form1.x10_codisencaotipo.focus(); 
    document.form1.x10_codisencaotipo.value = ''; 
  }
}
function js_mostraaguaisencaotipo1(chave1,chave2){
  document.form1.x10_codisencaotipo.value = chave1;
  document.form1.x29_descr.value = chave2;
  db_iframe_aguaisencaotipo.hide();
}
function js_pesquisa(){
  //js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguaisencao','func_aguaisencao.php?funcao_js=parent.js_preenchepesquisa|x10_codisencao','Pesquisa',true,'0','1','775','390');
  js_OpenJanelaIframe('top.corpo.iframe_aguaisencao','db_iframe_aguaisencao','func_aguaisencao.php?funcao_js=parent.js_preenchepesquisa|x10_codisencao','Pesquisa',true,'0','1');
}
function js_preenchepesquisa(chave){
  db_iframe_aguaisencao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>