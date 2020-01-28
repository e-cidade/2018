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

//MODULO: orcamento
$clorcparamelemento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
$clrotulo->label("o56_elemento");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To44_anousu?>">
       <?=@$Lo44_anousu?>
    </td>
    <td> 
<?
$o44_anousu = db_getsession('DB_anousu');
db_input('o44_anousu',4,$Io44_anousu,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To44_codparrel?>">
       <?
       db_ancora(@$Lo44_codparrel,"js_pesquisao44_codparrel(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o44_codparrel',8,$Io44_codparrel,true,'text',$db_opcao," onchange='js_pesquisao44_codparrel(false);'")
?>
       <?
db_input('o42_descrrel',40,$Io42_descrrel,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To44_codele?>">
       <?
       db_ancora(@$Lo44_codele,"js_pesquisao44_codele(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o44_codele',6,$Io44_codele,true,'text',$db_opcao," onchange='js_pesquisao44_codele(false);'")
?>
       <?
db_input('o56_elemento',13,$Io56_elemento,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao44_codparrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true);
  }else{
     if(document.form1.o44_codparrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o44_codparrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o44_codparrel.focus(); 
    document.form1.o44_codparrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o44_codparrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
}
function js_pesquisao44_codele(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento','Pesquisa',true);
  }else{
     if(document.form1.o44_codele.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcelemento','func_orcelemento.php?pesquisa_chave='+document.form1.o44_codele.value+'&funcao_js=parent.js_mostraorcelemento','Pesquisa',false);
     }else{
       document.form1.o56_elemento.value = ''; 
     }
  }
}
function js_mostraorcelemento(chave,erro){
  document.form1.o56_elemento.value = chave; 
  if(erro==true){ 
    document.form1.o44_codele.focus(); 
    document.form1.o44_codele.value = ''; 
  }
}
function js_mostraorcelemento1(chave1,chave2){
  document.form1.o44_codele.value = chave1;
  document.form1.o56_elemento.value = chave2;
  db_iframe_orcelemento.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcparamelemento','func_orcparamelemento.php?funcao_js=parent.js_preenchepesquisa|o44_codparrel|o44_codele|o44_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_orcparamelemento.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>