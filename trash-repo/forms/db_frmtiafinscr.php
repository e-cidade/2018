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
$cltiafinscr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y90_codtiaf");
$clrotulo->label("q02_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty94_codtiaf?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Ly94_codtiaf,"js_pesquisay94_codtiaf(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y94_codtiaf',10,$Iy94_codtiaf,true,'text',$db_opcao," onchange='js_pesquisay94_codtiaf(false);'")
?>
       <?
db_input('y90_codtiaf',10,$Iy90_codtiaf,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty94_inscr?>">
       <?
       db_ancora(@$Ly94_inscr,"js_pesquisay94_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y94_inscr',8,$Iy94_inscr,true,'text',$db_opcao," onchange='js_pesquisay94_inscr(false);'")
?>
       <?
db_input('q02_numcgm',10,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay94_codtiaf(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?funcao_js=parent.js_mostratiaf1|y90_codtiaf|y90_codtiaf','Pesquisa',true);
  }else{
     if(document.form1.y94_codtiaf.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tiaf','func_tiaf.php?pesquisa_chave='+document.form1.y94_codtiaf.value+'&funcao_js=parent.js_mostratiaf','Pesquisa',false);
     }else{
       document.form1.y90_codtiaf.value = ''; 
     }
  }
}
function js_mostratiaf(chave,erro){
  document.form1.y90_codtiaf.value = chave; 
  if(erro==true){ 
    document.form1.y94_codtiaf.focus(); 
    document.form1.y94_codtiaf.value = ''; 
  }
}
function js_mostratiaf1(chave1,chave2){
  document.form1.y94_codtiaf.value = chave1;
  document.form1.y90_codtiaf.value = chave2;
  db_iframe_tiaf.hide();
}
function js_pesquisay94_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true);
  }else{
     if(document.form1.y94_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.y94_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.q02_numcgm.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.y94_inscr.focus(); 
    document.form1.y94_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.y94_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tiafinscr','func_tiafinscr.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tiafinscr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>