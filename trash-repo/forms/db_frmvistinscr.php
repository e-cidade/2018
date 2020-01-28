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
$clvistinscr->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_data");
$clrotulo->label("q02_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty71_codvist?>">
       <?
       db_ancora(@$Ly71_codvist,"js_pesquisay71_codvist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y71_codvist',10,$Iy71_codvist,true,'text',$db_opcao," onchange='js_pesquisay71_codvist(false);'")
?>
       <?
db_input('y70_data',10,$Iy70_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty71_inscr?>">
       <?
       db_ancora(@$Ly71_inscr,"js_pesquisay71_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y71_inscr',10,$Iy71_inscr,true,'text',$db_opcao," onchange='js_pesquisay71_inscr(false);'")
?>
       <?
db_input('q02_numcgm',6,$Iq02_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay71_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y71_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_data.value = chave; 
  if(erro==true){ 
    document.form1.y71_codvist.focus(); 
    document.form1.y71_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y71_codvist.value = chave1;
  document.form1.y70_data.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisay71_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|q02_numcgm','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.y71_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
  }
}
function js_mostraissbase(chave,erro){
  document.form1.q02_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.y71_inscr.focus(); 
    document.form1.y71_inscr.value = ''; 
  }
}
function js_mostraissbase1(chave1,chave2){
  document.form1.y71_inscr.value = chave1;
  document.form1.q02_numcgm.value = chave2;
  db_iframe_issbase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vistinscr','func_vistinscr.php?funcao_js=parent.js_preenchepesquisa|y71_codvist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vistinscr.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>