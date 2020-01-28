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

//MODULO: empenho
$clpagordemele->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e50_numemp");
$clrotulo->label("o56_elemento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te53_codord?>">
       <?
       db_ancora(@$Le53_codord,"js_pesquisae53_codord(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e53_codord',6,$Ie53_codord,true,'text',$db_opcao," onchange='js_pesquisae53_codord(false);'")
?>
       <?
db_input('e50_numemp',8,$Ie50_numemp,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te53_codele?>">
       <?
       db_ancora(@$Le53_codele,"js_pesquisae53_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('e53_codele',6,$Ie53_codele,true,'text',$db_opcao," onchange='js_pesquisae53_codele(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te53_valor?>">
       <?=@$Le53_valor?>
    </td>
    <td> 
<?
db_input('e53_valor',15,$Ie53_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te53_vlranu?>">
       <?=@$Le53_vlranu?>
    </td>
    <td> 
<?
db_input('e53_vlranu',15,$Ie53_vlranu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te53_vlrpag?>">
       <?=@$Le53_vlrpag?>
    </td>
    <td> 
<?
db_input('e53_vlrpag',15,$Ie53_vlrpag,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisae53_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord|e50_numemp','Pesquisa',true);
  }else{
     if(document.form1.e53_codord.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.e53_codord.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
     }else{
       document.form1.e50_numemp.value = ''; 
     }
  }
}
function js_mostrapagordem(chave,erro){
  document.form1.e50_numemp.value = chave; 
  if(erro==true){ 
    document.form1.e53_codord.focus(); 
    document.form1.e53_codord.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e53_codord.value = chave1;
  document.form1.e50_numemp.value = chave2;
  db_iframe_pagordem.hide();
}
function js_pesquisae53_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true);
  }else{
     if(document.form1.e53_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.e53_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.e53_codele.focus(); 
    document.form1.e53_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.e53_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pagordemele','func_pagordemele.php?funcao_js=parent.js_preenchepesquisa|e53_codord|e53_codele','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_pagordemele.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>