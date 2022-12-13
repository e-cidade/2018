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

//MODULO: configuracoes
$cldb_usupermemp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db20_anousu");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb21_codperm?>">
       <?
       db_ancora(@$Ldb21_codperm,"js_pesquisadb21_codperm(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db21_codperm',6,$Idb21_codperm,true,'text',$db_opcao," onchange='js_pesquisadb21_codperm(false);'")
?>
       <?
db_input('db20_anousu',4,$Idb20_anousu,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb21_id_usuario?>">
       <?
       db_ancora(@$Ldb21_id_usuario,"js_pesquisadb21_id_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db21_id_usuario',5,$Idb21_id_usuario,true,'text',$db_opcao," onchange='js_pesquisadb21_id_usuario(false);'")
?>
       <?
db_input('nome',20,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisadb21_codperm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_permemp','func_db_permemp.php?funcao_js=parent.js_mostradb_permemp1|db20_codperm|db20_anousu','Pesquisa',true);
  }else{
     if(document.form1.db21_codperm.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_permemp','func_db_permemp.php?pesquisa_chave='+document.form1.db21_codperm.value+'&funcao_js=parent.js_mostradb_permemp','Pesquisa',false);
     }else{
       document.form1.db20_anousu.value = ''; 
     }
  }
}
function js_mostradb_permemp(chave,erro){
  document.form1.db20_anousu.value = chave; 
  if(erro==true){ 
    document.form1.db21_codperm.focus(); 
    document.form1.db21_codperm.value = ''; 
  }
}
function js_mostradb_permemp1(chave1,chave2){
  document.form1.db21_codperm.value = chave1;
  document.form1.db20_anousu.value = chave2;
  db_iframe_db_permemp.hide();
}
function js_pesquisadb21_id_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.db21_id_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.db21_id_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.db21_id_usuario.focus(); 
    document.form1.db21_id_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.db21_id_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_usupermemp','func_db_usupermemp.php?funcao_js=parent.js_preenchepesquisa|db21_codperm|db21_id_usuario','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_db_usupermemp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>