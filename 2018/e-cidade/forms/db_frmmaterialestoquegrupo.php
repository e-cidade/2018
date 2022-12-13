<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: material
$clmaterialestoquegrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db121_sequencial");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tm65_sequencial?>">
       <?=@$Lm65_sequencial?>
    </td>
    <td> 
<?
db_input('m65_sequencial',10,$Im65_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm65_db_estruturavalor?>">
       <?
       db_ancora(@$Lm65_db_estruturavalor,"js_pesquisam65_db_estruturavalor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('m65_db_estruturavalor',10,$Im65_db_estruturavalor,true,'text',$db_opcao," onchange='js_pesquisam65_db_estruturavalor(false);'")
?>
       <?
db_input('db121_sequencial',10,$Idb121_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tm65_ativo?>">
       <?=@$Lm65_ativo?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('m65_ativo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisam65_db_estruturavalor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_estruturavalor','func_db_estruturavalor.php?funcao_js=parent.js_mostradb_estruturavalor1|db121_sequencial|db121_sequencial','Pesquisa',true);
  }else{
     if(document.form1.m65_db_estruturavalor.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_estruturavalor','func_db_estruturavalor.php?pesquisa_chave='+document.form1.m65_db_estruturavalor.value+'&funcao_js=parent.js_mostradb_estruturavalor','Pesquisa',false);
     }else{
       document.form1.db121_sequencial.value = ''; 
     }
  }
}
function js_mostradb_estruturavalor(chave,erro){
  document.form1.db121_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.m65_db_estruturavalor.focus(); 
    document.form1.m65_db_estruturavalor.value = ''; 
  }
}
function js_mostradb_estruturavalor1(chave1,chave2){
  document.form1.m65_db_estruturavalor.value = chave1;
  document.form1.db121_sequencial.value = chave2;
  db_iframe_db_estruturavalor.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_materialestoquegrupo','func_materialestoquegrupo.php?funcao_js=parent.js_preenchepesquisa|m65_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_materialestoquegrupo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>