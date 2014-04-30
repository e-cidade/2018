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
$clmodcarne->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k00_descr");
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk05_sequencial?>">
       <b>Código :</b>
    </td>
    <td> 
	<?
	db_input('k05_sequencial',5,$Ik05_sequencial,true,'text',$opcaoseq,"")
	?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk05_codigo?>">
       <?
       db_ancora(@$Lk05_codigo,"js_pesquisak05_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k05_codigo',5,$Ik05_codigo,true,'text',$db_opcao," onchange='js_pesquisak05_codigo(false);'")
?>
       <?
db_input('nomeinst',50,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk05_tipo?>">
       <?
       db_ancora(@$Lk05_tipo,"js_pesquisak05_tipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k05_tipo',5,$Ik05_tipo,true,'text',$db_opcao," onchange='js_pesquisak05_tipo(false);'")
?>
       <?
db_input('k00_descr',50,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk05_modcarne?>">
       <?=@$Lk05_modcarne?>
    </td>
    <td> 
<?
$x = array('1'=>'Carne febraban 1','30'=>'Carne febraban 2');
db_select('k05_modcarne',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<!-- <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" > -->
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisak05_tipo(true);" >
</form>
<script>
function js_pesquisak05_tipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
     if(document.form1.k05_tipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k05_tipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k05_tipo.focus(); 
    document.form1.k05_tipo.value = ''; 
  }
  
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k05_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
  document.form1.submit();
}
function js_pesquisak05_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.k05_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.k05_codigo.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.k05_codigo.focus(); 
    document.form1.k05_codigo.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.k05_codigo.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_modcarne','func_modcarne.php?funcao_js=parent.js_preenchepesquisa|k05_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_modcarne.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>