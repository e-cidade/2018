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
$cltermovistlanc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y91_datatermo");
$clrotulo->label("y70_id_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty92_termovist?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Ly92_termovist,"js_pesquisay92_termovist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y92_termovist',10,$Iy92_termovist,true,'text',$db_opcao," onchange='js_pesquisay92_termovist(false);'")
?>
       <?
db_input('y91_datatermo',8,$Iy91_datatermo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty92_codvist?>">
       <?
       db_ancora(@$Ly92_codvist,"js_pesquisay92_codvist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y92_codvist',10,$Iy92_codvist,true,'text',$db_opcao," onchange='js_pesquisay92_codvist(false);'")
?>
       <?
db_input('y70_id_usuario',10,$Iy70_id_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay92_termovist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_termovist','func_termovist.php?funcao_js=parent.js_mostratermovist1|y91_termovist|y91_datatermo','Pesquisa',true);
  }else{
     if(document.form1.y92_termovist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_termovist','func_termovist.php?pesquisa_chave='+document.form1.y92_termovist.value+'&funcao_js=parent.js_mostratermovist','Pesquisa',false);
     }else{
       document.form1.y91_datatermo.value = ''; 
     }
  }
}
function js_mostratermovist(chave,erro){
  document.form1.y91_datatermo.value = chave; 
  if(erro==true){ 
    document.form1.y92_termovist.focus(); 
    document.form1.y92_termovist.value = ''; 
  }
}
function js_mostratermovist1(chave1,chave2){
  document.form1.y92_termovist.value = chave1;
  document.form1.y91_datatermo.value = chave2;
  db_iframe_termovist.hide();
}
function js_pesquisay92_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_id_usuario','Pesquisa',true);
  }else{
     if(document.form1.y92_codvist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y92_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
     }else{
       document.form1.y70_id_usuario.value = ''; 
     }
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_id_usuario.value = chave; 
  if(erro==true){ 
    document.form1.y92_codvist.focus(); 
    document.form1.y92_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y92_codvist.value = chave1;
  document.form1.y70_id_usuario.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_termovistlanc','func_termovistlanc.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_termovistlanc.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>