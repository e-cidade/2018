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

//MODULO: issqn
$clisszona->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j50_descr");
$clrotulo->label("q02_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq35_inscr?>">
       <?
       db_ancora(@$Lq35_inscr,"js_pesquisaq35_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q35_inscr',8,$Iq35_inscr,true,'text',$db_opcao," onchange='js_pesquisaq35_inscr(false);'")
?>
       <?
db_input('q02_numcgm',10,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq35_zona?>">
       <?
       db_ancora(@$Lq35_zona,"js_pesquisaq35_zona(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q35_zona',15,$Iq35_zona,true,'text',$db_opcao," onchange='js_pesquisaq35_zona(false);'")
?>
       <?
db_input('j50_descr',40,$Ij50_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq35_zona(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_zonas','func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr','Pesquisa',true);
  }else{
     if(document.form1.q35_zona.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_zonas','func_zonas.php?pesquisa_chave='+document.form1.q35_zona.value+'&funcao_js=parent.js_mostrazonas','Pesquisa',false);
     }else{
       document.form1.j50_descr.value = ''; 
     }
  }
}
function js_mostrazonas(chave,erro){
  document.form1.j50_descr.value = chave; 
  if(erro==true){ 
    document.form1.q35_zona.focus(); 
    document.form1.q35_zona.value = ''; 
  }
}
function js_mostrazonas1(chave1,chave2){
  document.form1.q35_zona.value = chave1;
  document.form1.j50_descr.value = chave2;
  db_iframe_zonas.hide();
}
function js_pesquisaq35_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true);
  }else{
     if(document.form1.q35_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q35_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.q02_numcgm.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.q35_inscr.focus(); 
    document.form1.q35_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.q35_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_isszona','func_isszona.php?funcao_js=parent.js_preenchepesquisa|q35_inscr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isszona.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>