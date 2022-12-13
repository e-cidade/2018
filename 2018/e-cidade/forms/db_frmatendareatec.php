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

//MODULO: atendimento
$clatendareatec->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at25_descr");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tat27_sequencial?>">
       <?=@$Lat27_sequencial?>
    </td>
    <td> 
<?
db_input('at27_sequencial',10,$Iat27_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat27_atendcadarea?>">
       <?
       db_ancora(@$Lat27_atendcadarea,"js_pesquisaat27_atendcadarea(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at27_atendcadarea',10,$Iat27_atendcadarea,true,'text',$db_opcao," onchange='js_pesquisaat27_atendcadarea(false);'")
?>
       <?
db_input('at25_descr',30,$Iat25_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat27_usuarios?>">
       <?
       db_ancora(@$Lat27_usuarios,"js_pesquisaat27_usuarios(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('at27_usuarios',10,$Iat27_usuarios,true,'text',$db_opcao," onchange='js_pesquisaat27_usuarios(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaat27_atendcadarea(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_atendcadarea','func_atendcadarea.php?funcao_js=parent.js_mostraatendcadarea1|at26_sequencial|at25_descr','Pesquisa',true);
  }else{
     if(document.form1.at27_atendcadarea.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_atendcadarea','func_atendcadarea.php?pesquisa_chave='+document.form1.at27_atendcadarea.value+'&funcao_js=parent.js_mostraatendcadarea','Pesquisa',false);
     }else{
       document.form1.at25_descr.value = ''; 
     }
  }
}
function js_mostraatendcadarea(chave,erro){
  document.form1.at25_descr.value = chave; 
  if(erro==true){ 
    document.form1.at27_atendcadarea.focus(); 
    document.form1.at27_atendcadarea.value = ''; 
  }
}
function js_mostraatendcadarea1(chave1,chave2){
  document.form1.at27_atendcadarea.value = chave1;
  document.form1.at25_descr.value = chave2;
  db_iframe_atendcadarea.hide();
}
function js_pesquisaat27_usuarios(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.at27_usuarios.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.at27_usuarios.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.at27_usuarios.focus(); 
    document.form1.at27_usuarios.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.at27_usuarios.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atendareatec','func_atendareatec.php?funcao_js=parent.js_preenchepesquisa|at27_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_atendareatec.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>