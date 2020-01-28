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
$clvistsanitario->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y70_data");
$clrotulo->label("y80_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ty74_codvist?>">
       <?
       db_ancora(@$Ly74_codvist,"js_pesquisay74_codvist(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y74_codvist',10,$Iy74_codvist,true,'text',$db_opcao," onchange='js_pesquisay74_codvist(false);'")
?>
       <?
db_input('y70_data',10,$Iy70_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty74_codsani?>">
       <?
       db_ancora(@$Ly74_codsani,"js_pesquisay74_codsani(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('y74_codsani',10,$Iy74_codsani,true,'text',$db_opcao," onchange='js_pesquisay74_codsani(false);'")
?>
       <?
db_input('y80_numcgm',10,$Iy80_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisay74_codvist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?funcao_js=parent.js_mostravistorias1|y70_codvist|y70_data','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_vistorias','func_vistorias.php?pesquisa_chave='+document.form1.y74_codvist.value+'&funcao_js=parent.js_mostravistorias','Pesquisa',false);
  }
}
function js_mostravistorias(chave,erro){
  document.form1.y70_data.value = chave; 
  if(erro==true){ 
    document.form1.y74_codvist.focus(); 
    document.form1.y74_codvist.value = ''; 
  }
}
function js_mostravistorias1(chave1,chave2){
  document.form1.y74_codvist.value = chave1;
  document.form1.y70_data.value = chave2;
  db_iframe_vistorias.hide();
}
function js_pesquisay74_codsani(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_mostrasanitario1|y80_codsani|y80_numcgm','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y74_codsani.value+'&funcao_js=parent.js_mostrasanitario','Pesquisa',false);
  }
}
function js_mostrasanitario(chave,erro){
  document.form1.y80_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.y74_codsani.focus(); 
    document.form1.y74_codsani.value = ''; 
  }
}
function js_mostrasanitario1(chave1,chave2){
  document.form1.y74_codsani.value = chave1;
  document.form1.y80_numcgm.value = chave2;
  db_iframe_sanitario.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_vistsanitario','func_vistsanitario.php?funcao_js=parent.js_preenchepesquisa|y74_codvist','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_vistsanitario.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
</script>