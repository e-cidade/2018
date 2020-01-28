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

//MODULO: protocolo
$clprocandamintusu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p78_sequencial");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp79_codandamint?>">
       <?
       db_ancora(@$Lp79_codandamint,"js_pesquisap79_codandamint(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p79_codandamint',10,$Ip79_codandamint,true,'text',$db_opcao," onchange='js_pesquisap79_codandamint(false);'")
?>
       <?
db_input('p78_sequencial',10,$Ip78_sequencial,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp79_usuario?>">
       <?
       db_ancora(@$Lp79_usuario,"js_pesquisap79_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p79_usuario',10,$Ip79_usuario,true,'text',$db_opcao," onchange='js_pesquisap79_usuario(false);'")
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
function js_pesquisap79_codandamint(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_procandamint','func_procandamint.php?funcao_js=parent.js_mostraprocandamint1|p78_sequencial|p78_sequencial','Pesquisa',true);
  }else{
     if(document.form1.p79_codandamint.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_procandamint','func_procandamint.php?pesquisa_chave='+document.form1.p79_codandamint.value+'&funcao_js=parent.js_mostraprocandamint','Pesquisa',false);
     }else{
       document.form1.p78_sequencial.value = ''; 
     }
  }
}
function js_mostraprocandamint(chave,erro){
  document.form1.p78_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.p79_codandamint.focus(); 
    document.form1.p79_codandamint.value = ''; 
  }
}
function js_mostraprocandamint1(chave1,chave2){
  document.form1.p79_codandamint.value = chave1;
  document.form1.p78_sequencial.value = chave2;
  db_iframe_procandamint.hide();
}
function js_pesquisap79_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.p79_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.p79_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.p79_usuario.focus(); 
    document.form1.p79_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p79_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_procandamintusu','func_procandamintusu.php?funcao_js=parent.js_preenchepesquisa|p79_codandamint','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_procandamintusu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>