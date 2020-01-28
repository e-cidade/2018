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

//MODULO: caixa
$clmodcarnepadraogeral->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("k47_descr");
$clrotulo->label("k46_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk48_sequencial?>">
       <?=@$Lk48_sequencial?>
    </td>
    <td> 
<?
db_input('k48_sequencial',10,$Ik48_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk48_instit?>">
       <?
       db_ancora(@$Lk48_instit,"js_pesquisak48_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k48_instit',2,$Ik48_instit,true,'text',$db_opcao," onchange='js_pesquisak48_instit(false);'")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk48_cadmodcarne?>">
       <?
       db_ancora(@$Lk48_cadmodcarne,"js_pesquisak48_cadmodcarne(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k48_cadmodcarne',10,$Ik48_cadmodcarne,true,'text',$db_opcao," onchange='js_pesquisak48_cadmodcarne(false);'")
?>
       <?
db_input('k47_descr',40,$Ik47_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk48_cadtipomod?>">
       <?
       db_ancora(@$Lk48_cadtipomod,"js_pesquisak48_cadtipomod(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k48_cadtipomod',10,$Ik48_cadtipomod,true,'text',$db_opcao," onchange='js_pesquisak48_cadtipomod(false);'")
?>
       <?
db_input('k46_descr',40,$Ik46_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk48_dataini?>">
       <?=@$Lk48_dataini?>
    </td>
    <td> 
<?
db_inputdata('k48_dataini',@$k48_dataini_dia,@$k48_dataini_mes,@$k48_dataini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk48_datafim?>">
       <?=@$Lk48_datafim?>
    </td>
    <td> 
<?
db_inputdata('k48_datafim',@$k48_datafim_dia,@$k48_datafim_mes,@$k48_datafim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak48_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k48_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k48_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k48_instit.focus(); 
    document.form1.k48_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k48_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisak48_cadmodcarne(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadmodcarne','func_cadmodcarne.php?funcao_js=parent.js_mostracadmodcarne1|k47_sequencial|k47_descr','Pesquisa',true);
  }else{
     if(document.form1.k48_cadmodcarne.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadmodcarne','func_cadmodcarne.php?pesquisa_chave='+document.form1.k48_cadmodcarne.value+'&funcao_js=parent.js_mostracadmodcarne','Pesquisa',false);
     }else{
       document.form1.k47_descr.value = ''; 
     }
  }
}
function js_mostracadmodcarne(chave,erro){
  document.form1.k47_descr.value = chave; 
  if(erro==true){ 
    document.form1.k48_cadmodcarne.focus(); 
    document.form1.k48_cadmodcarne.value = ''; 
  }
}
function js_mostracadmodcarne1(chave1,chave2){
  document.form1.k48_cadmodcarne.value = chave1;
  document.form1.k47_descr.value = chave2;
  db_iframe_cadmodcarne.hide();
}
function js_pesquisak48_cadtipomod(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cadtipomod','func_cadtipomod.php?funcao_js=parent.js_mostracadtipomod1|k46_sequencial|k46_descr','Pesquisa',true);
  }else{
     if(document.form1.k48_cadtipomod.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cadtipomod','func_cadtipomod.php?pesquisa_chave='+document.form1.k48_cadtipomod.value+'&funcao_js=parent.js_mostracadtipomod','Pesquisa',false);
     }else{
       document.form1.k46_descr.value = ''; 
     }
  }
}
function js_mostracadtipomod(chave,erro){
  document.form1.k46_descr.value = chave; 
  if(erro==true){ 
    document.form1.k48_cadtipomod.focus(); 
    document.form1.k48_cadtipomod.value = ''; 
  }
}
function js_mostracadtipomod1(chave1,chave2){
  document.form1.k48_cadtipomod.value = chave1;
  document.form1.k46_descr.value = chave2;
  db_iframe_cadtipomod.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_modcarnepadraogeral','func_modcarnepadraogeral.php?funcao_js=parent.js_preenchepesquisa|k48_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_modcarnepadraogeral.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>