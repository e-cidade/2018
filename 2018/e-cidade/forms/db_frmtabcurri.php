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

//MODULO: recursoshumanos
$cltabcurri->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("h02_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Th01_codigo?>">
       <?=@$Lh01_codigo?>
    </td>
    <td> 
<?
db_input('h01_codigo',5,$Ih01_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th01_cgmentid?>">
       <?
       db_ancora(@$Lh01_cgmentid,"js_pesquisah01_cgmentid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h01_cgmentid',6,$Ih01_cgmentid,true,'text',$db_opcao," onchange='js_pesquisah01_cgmentid(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th01_descr?>">
       <?=@$Lh01_descr?>
    </td>
    <td> 
<?
db_input('h01_descr',80,$Ih01_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th01_detalh?>">
       <?=@$Lh01_detalh?>
    </td>
    <td> 
<?
db_textarea('h01_detalh',5,80,$Ih01_detalh,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th01_codtipo?>">
       <?
       db_ancora(@$Lh01_codtipo,"js_pesquisah01_codtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('h01_codtipo',5,$Ih01_codtipo,true,'text',$db_opcao," onchange='js_pesquisah01_codtipo(false);'")
?>
       <?
db_input('h02_descr',60,$Ih02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th01_cargahor?>">
       <?=@$Lh01_cargahor?>
    </td>
    <td> 
<?
db_input('h01_cargahor',10,$Ih01_cargahor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisah01_cgmentid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.h01_cgmentid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.h01_cgmentid.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.h01_cgmentid.focus(); 
    document.form1.h01_cgmentid.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.h01_cgmentid.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisah01_codtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tabcurritipo','func_tabcurritipo.php?funcao_js=parent.js_mostratabcurritipo1|h02_codigo|h02_descr','Pesquisa',true);
  }else{
     if(document.form1.h01_codtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tabcurritipo','func_tabcurritipo.php?pesquisa_chave='+document.form1.h01_codtipo.value+'&funcao_js=parent.js_mostratabcurritipo','Pesquisa',false);
     }else{
       document.form1.h02_descr.value = ''; 
     }
  }
}
function js_mostratabcurritipo(chave,erro){
  document.form1.h02_descr.value = chave; 
  if(erro==true){ 
    document.form1.h01_codtipo.focus(); 
    document.form1.h01_codtipo.value = ''; 
  }
}
function js_mostratabcurritipo1(chave1,chave2){
  document.form1.h01_codtipo.value = chave1;
  document.form1.h02_descr.value = chave2;
  db_iframe_tabcurritipo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tabcurri','func_tabcurri.php?funcao_js=parent.js_preenchepesquisa|h01_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tabcurri.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>