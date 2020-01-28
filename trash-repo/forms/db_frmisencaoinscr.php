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

//MODULO: tributario
$clisencaoinscr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v10_isencaotipo");
$clrotulo->label("q02_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv16_sequencial?>">
       <?=@$Lv16_sequencial?>
    </td>
    <td> 
<?
db_input('v16_sequencial',10,$Iv16_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv16_isencao?>">
       <?
       db_ancora(@$Lv16_isencao,"js_pesquisav16_isencao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v16_isencao',10,$Iv16_isencao,true,'text',$db_opcao," onchange='js_pesquisav16_isencao(false);'")
?>
       <?
db_input('v10_isencaotipo',10,$Iv10_isencaotipo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv16_inscr?>">
       <?
       db_ancora(@$Lv16_inscr,"js_pesquisav16_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v16_inscr',8,$Iv16_inscr,true,'text',$db_opcao," onchange='js_pesquisav16_inscr(false);'")
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
function js_pesquisav16_isencao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?funcao_js=parent.js_mostraisencao1|v10_sequencial|v10_isencaotipo','Pesquisa',true);
  }else{
     if(document.form1.v16_isencao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_isencao','func_isencao.php?pesquisa_chave='+document.form1.v16_isencao.value+'&funcao_js=parent.js_mostraisencao','Pesquisa',false);
     }else{
       document.form1.v10_isencaotipo.value = ''; 
     }
  }
}
function js_mostraisencao(chave,erro){
  document.form1.v10_isencaotipo.value = chave; 
  if(erro==true){ 
    document.form1.v16_isencao.focus(); 
    document.form1.v16_isencao.value = ''; 
  }
}
function js_mostraisencao1(chave1,chave2){
  document.form1.v16_isencao.value = chave1;
  document.form1.v10_isencaotipo.value = chave2;
  db_iframe_isencao.hide();
}
function js_pesquisav16_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true);
  }else{
     if(document.form1.v16_inscr.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.v16_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.q02_numcgm.value = ''; 
     }
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.v16_inscr.focus(); 
    document.form1.v16_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.v16_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_isencaoinscr','func_isencaoinscr.php?funcao_js=parent.js_preenchepesquisa|v16_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isencaoinscr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>