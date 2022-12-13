<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clprocessoforo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v73_sequencial");
$clrotulo->label("nome");
$clrotulo->label("v53_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("v82_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv70_sequencial?>">
       <?=@$Lv70_sequencial?>
    </td>
    <td> 
<?
db_input('v70_sequencial',10,$Iv70_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_codforo?>">
       <?=@$Lv70_codforo?>
    </td>
    <td> 
<?
db_input('v70_codforo',30,$Iv70_codforo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_processoforomov?>">
       <?
       db_ancora(@$Lv70_processoforomov,"js_pesquisav70_processoforomov(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v70_processoforomov',10,$Iv70_processoforomov,true,'text',$db_opcao," onchange='js_pesquisav70_processoforomov(false);'")
?>
       <?
db_input('v73_sequencial',10,$Iv73_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_id_usuario?>">
       <?
       db_ancora(@$Lv70_id_usuario,"js_pesquisav70_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v70_id_usuario',10,$Iv70_id_usuario,true,'text',$db_opcao," onchange='js_pesquisav70_id_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_vara?>">
       <?
       db_ancora(@$Lv70_vara,"js_pesquisav70_vara(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v70_vara',10,$Iv70_vara,true,'text',$db_opcao," onchange='js_pesquisav70_vara(false);'")
?>
       <?
db_input('v53_descr',40,$Iv53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_data?>">
       <?=@$Lv70_data?>
    </td>
    <td> 
<?
db_inputdata('v70_data',@$v70_data_dia,@$v70_data_mes,@$v70_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_valorinicial?>">
       <?=@$Lv70_valorinicial?>
    </td>
    <td> 
<?
db_input('v70_valorinicial',10,$Iv70_valorinicial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_observacao?>">
       <?=@$Lv70_observacao?>
    </td>
    <td> 
<?
db_textarea('v70_observacao',0,0,$Iv70_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_anulado?>">
       <?=@$Lv70_anulado?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('v70_anulado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_instit?>">
       <?
       db_ancora(@$Lv70_instit,"js_pesquisav70_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v70_instit',10,$Iv70_instit,true,'text',$db_opcao," onchange='js_pesquisav70_instit(false);'")
?>
       <?
db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv70_cartorio?>">
       <?
       db_ancora(@$Lv70_cartorio,"js_pesquisav70_cartorio(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v70_cartorio',10,$Iv70_cartorio,true,'text',$db_opcao," onchange='js_pesquisav70_cartorio(false);'")
?>
       <?
db_input('v82_sequencial',10,$Iv82_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav70_processoforomov(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_processoforomov','func_processoforomov.php?funcao_js=parent.js_mostraprocessoforomov1|v73_sequencial|v73_sequencial','Pesquisa',true);
  }else{
     if(document.form1.v70_processoforomov.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_processoforomov','func_processoforomov.php?pesquisa_chave='+document.form1.v70_processoforomov.value+'&funcao_js=parent.js_mostraprocessoforomov','Pesquisa',false);
     }else{
       document.form1.v73_sequencial.value = ''; 
     }
  }
}
function js_mostraprocessoforomov(chave,erro){
  document.form1.v73_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.v70_processoforomov.focus(); 
    document.form1.v70_processoforomov.value = ''; 
  }
}
function js_mostraprocessoforomov1(chave1,chave2){
  document.form1.v70_processoforomov.value = chave1;
  document.form1.v73_sequencial.value = chave2;
  db_iframe_processoforomov.hide();
}
function js_pesquisav70_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.v70_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.v70_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.v70_id_usuario.focus(); 
    document.form1.v70_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.v70_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisav70_vara(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vara','func_vara.php?funcao_js=parent.js_mostravara1|v53_codvara|v53_descr','Pesquisa',true);
  }else{
     if(document.form1.v70_vara.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vara','func_vara.php?pesquisa_chave='+document.form1.v70_vara.value+'&funcao_js=parent.js_mostravara','Pesquisa',false);
     }else{
       document.form1.v53_descr.value = ''; 
     }
  }
}
function js_mostravara(chave,erro){
  document.form1.v53_descr.value = chave; 
  if(erro==true){ 
    document.form1.v70_vara.focus(); 
    document.form1.v70_vara.value = ''; 
  }
}
function js_mostravara1(chave1,chave2){
  document.form1.v70_vara.value = chave1;
  document.form1.v53_descr.value = chave2;
  db_iframe_vara.hide();
}
function js_pesquisav70_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.v70_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.v70_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.v70_instit.focus(); 
    document.form1.v70_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.v70_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisav70_cartorio(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cartorio','func_cartorio.php?funcao_js=parent.js_mostracartorio1|v82_sequencial|v82_sequencial','Pesquisa',true);
  }else{
     if(document.form1.v70_cartorio.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cartorio','func_cartorio.php?pesquisa_chave='+document.form1.v70_cartorio.value+'&funcao_js=parent.js_mostracartorio','Pesquisa',false);
     }else{
       document.form1.v82_sequencial.value = ''; 
     }
  }
}
function js_mostracartorio(chave,erro){
  document.form1.v82_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.v70_cartorio.focus(); 
    document.form1.v70_cartorio.value = ''; 
  }
}
function js_mostracartorio1(chave1,chave2){
  document.form1.v70_cartorio.value = chave1;
  document.form1.v82_sequencial.value = chave2;
  db_iframe_cartorio.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_processoforo','func_processoforo.php?funcao_js=parent.js_preenchepesquisa|v70_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_processoforo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>