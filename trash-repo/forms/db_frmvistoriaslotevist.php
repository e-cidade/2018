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

//MODULO: fiscal
$clvistoriaslotevist->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y06_usuario");
$clrotulo->label("y70_id_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty05_vistoriaslotevist?>">
       <?=@$Ly05_vistoriaslotevist?>
    </td>
    <td> 
<?
db_input('y05_vistoriaslotevist',10,$Iy05_vistoriaslotevist,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty05_vistoriaslote?>">
       <?
       db_ancora(@$Ly05_vistoriaslote,"js_pesquisay05_vistoriaslote(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y05_vistoriaslote',10,$Iy05_vistoriaslote,true,'text',$db_opcao," onchange='js_pesquisay05_vistoriaslote(false);'")
?>
       <?
db_input('y06_usuario',10,$Iy06_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty05_codvist?>">
       <?
       db_ancora(@$Ly05_codvist,"js_pesquisay05_codvist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y05_codvist',10,$Iy05_codvist,true,'text',$db_opcao," onchange='js_pesquisay05_codvist(false);'")
?>
       <?
db_input('y70_id_usuario',10,$Iy70_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty05_codmsg?>">
       <?=@$Ly05_codmsg?>
    </td>
    <td> 
<?
db_input('y05_codmsg',10,$Iy05_codmsg,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay05_vistoriaslote(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistoriaslote','func_vistoriaslote.php?funcao_js=parent.js_mostravistoriaslote1|y06_vistoriaslote|y06_usuario','Pesquisa',true);
  }else{
     if(document.form1.y05_vistoriaslote.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vistoriaslote','func_vistoriaslote.php?pesquisa_chave='+document.form1.y05_vistoriaslote.value+'&funcao_js=parent.js_mostravistoriaslote','Pesquisa',false);
     }else{
       document.form1.y06_usuario.value = ''; 
     }
  }
}
function js_mostravistoriaslote(chave,erro){
  document.form1.y06_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y05_vistoriaslote.focus(); 
    document.form1.y05_vistoriaslote.value = ''; 
  }
}
function js_mostravistoriaslote1(chave1,chave2){
  document.form1.y05_vistoriaslote.value = chave1;
  document.form1.y06_usuario.value = chave2;
  db_iframe_vistoriaslote.hide();
}
function js_pesquisay05_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_id_usuario','Pesquisa',true);
  }else{
     if(document.form1.y05_codvist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y05_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
     }else{
       document.form1.y70_id_usuario.value = ''; 
     }
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y05_codvist.focus(); 
    document.form1.y05_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y05_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vistoriaslotevist','func_vistoriaslotevist.php?funcao_js=parent.js_preenchepesquisa|y05_vistoriaslotevist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vistoriaslotevist.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>