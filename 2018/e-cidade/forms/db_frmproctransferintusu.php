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
$clproctransferintusu->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p88_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tp89_codtransferint?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lp89_codtransferint,"js_pesquisap89_codtransferint(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p89_codtransferint',10,$Ip89_codtransferint,true,'text',$db_opcao," onchange='js_pesquisap89_codtransferint(false);'")
?>
       <?
db_input('p88_codigo',10,$Ip88_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp89_usuario?>">
       <?
       db_ancora(@$Lp89_usuario,"js_pesquisap89_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('p89_usuario',10,$Ip89_usuario,true,'text',$db_opcao," onchange='js_pesquisap89_usuario(false);'")
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
function js_pesquisap89_codtransferint(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_proctransferint','func_proctransferint.php?funcao_js=parent.js_mostraproctransferint1|p88_codigo|p88_codigo','Pesquisa',true);
  }else{
     if(document.form1.p89_codtransferint.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_proctransferint','func_proctransferint.php?pesquisa_chave='+document.form1.p89_codtransferint.value+'&funcao_js=parent.js_mostraproctransferint','Pesquisa',false);
     }else{
       document.form1.p88_codigo.value = ''; 
     }
  }
}
function js_mostraproctransferint(chave,erro){
  document.form1.p88_codigo.value = chave; 
  if(erro==true){ 
    document.form1.p89_codtransferint.focus(); 
    document.form1.p89_codtransferint.value = ''; 
  }
}
function js_mostraproctransferint1(chave1,chave2){
  document.form1.p89_codtransferint.value = chave1;
  document.form1.p88_codigo.value = chave2;
  db_iframe_proctransferint.hide();
}
function js_pesquisap89_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.p89_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.p89_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.p89_usuario.focus(); 
    document.form1.p89_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.p89_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_proctransferintusu','func_proctransferintusu.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_proctransferintusu.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>