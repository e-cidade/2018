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

//MODULO: juridico
$clprocessoforonumcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("v70_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv75_sequencial?>">
       <?=@$Lv75_sequencial?>
    </td>
    <td> 
<?
db_input('v75_sequencial',10,$Iv75_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv75_seqprocforo?>">
       <?
       db_ancora(@$Lv75_seqprocforo,"js_pesquisav75_seqprocforo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v75_seqprocforo',10,$Iv75_seqprocforo,true,'text',$db_opcao," onchange='js_pesquisav75_seqprocforo(false);'")
?>
       <?
db_input('v70_sequencial',10,$Iv70_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv75_numcgm?>">
       <?
       db_ancora(@$Lv75_numcgm,"js_pesquisav75_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v75_numcgm',10,$Iv75_numcgm,true,'text',$db_opcao," onchange='js_pesquisav75_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav75_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.v75_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.v75_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.v75_numcgm.focus(); 
    document.form1.v75_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.v75_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisav75_seqprocforo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','func_processoforo.php?funcao_js=parent.js_mostraprocessoforo1|v70_sequencial|v70_sequencial','Pesquisa',true);
  }else{
     if(document.form1.v75_seqprocforo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','func_processoforo.php?pesquisa_chave='+document.form1.v75_seqprocforo.value+'&funcao_js=parent.js_mostraprocessoforo','Pesquisa',false);
     }else{
       document.form1.v70_sequencial.value = ''; 
     }
  }
}
function js_mostraprocessoforo(chave,erro){
  document.form1.v70_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.v75_seqprocforo.focus(); 
    document.form1.v75_seqprocforo.value = ''; 
  }
}
function js_mostraprocessoforo1(chave1,chave2){
  document.form1.v75_seqprocforo.value = chave1;
  document.form1.v70_sequencial.value = chave2;
  db_iframe_processoforo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_processoforonumcgm','func_processoforonumcgm.php?funcao_js=parent.js_preenchepesquisa|v75_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_processoforonumcgm.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>