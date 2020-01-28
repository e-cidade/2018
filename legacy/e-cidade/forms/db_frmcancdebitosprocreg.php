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

//MODULO: caixa
$clcancdebitosprocreg->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k23_codigo");
$clrotulo->label("k21_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk24_sequencia?>">
       <?=@$Lk24_sequencia?>
    </td>
    <td> 
<?
db_input('k24_sequencia',15,$Ik24_sequencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_codigo?>">
       <?
       db_ancora(@$Lk24_codigo,"js_pesquisak24_codigo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k24_codigo',15,$Ik24_codigo,true,'text',$db_opcao," onchange='js_pesquisak24_codigo(false);'")
?>
       <?
db_input('k23_codigo',10,$Ik23_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_cancdebitosreg?>">
       <?
       db_ancora(@$Lk24_cancdebitosreg,"js_pesquisak24_cancdebitosreg(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k24_cancdebitosreg',15,$Ik24_cancdebitosreg,true,'text',$db_opcao," onchange='js_pesquisak24_cancdebitosreg(false);'")
?>
       <?
db_input('k21_codigo',10,$Ik21_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_vlrhis?>">
       <?=@$Lk24_vlrhis?>
    </td>
    <td> 
<?
db_input('k24_vlrhis',999999,$Ik24_vlrhis,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_vlrcor?>">
       <?=@$Lk24_vlrcor?>
    </td>
    <td> 
<?
db_input('k24_vlrcor',999999,$Ik24_vlrcor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_juros?>">
       <?=@$Lk24_juros?>
    </td>
    <td> 
<?
db_input('k24_juros',999999,$Ik24_juros,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_multa?>">
       <?=@$Lk24_multa?>
    </td>
    <td> 
<?
db_input('k24_multa',999999,$Ik24_multa,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk24_desconto?>">
       <?=@$Lk24_desconto?>
    </td>
    <td> 
<?
db_input('k24_desconto',999999,$Ik24_desconto,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak24_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitosproc','func_cancdebitosproc.php?funcao_js=parent.js_mostracancdebitosproc1|k23_codigo|k23_codigo','Pesquisa',true);
  }else{
     if(document.form1.k24_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitosproc','func_cancdebitosproc.php?pesquisa_chave='+document.form1.k24_codigo.value+'&funcao_js=parent.js_mostracancdebitosproc','Pesquisa',false);
     }else{
       document.form1.k23_codigo.value = ''; 
     }
  }
}
function js_mostracancdebitosproc(chave,erro){
  document.form1.k23_codigo.value = chave; 
  if(erro==true){ 
    document.form1.k24_codigo.focus(); 
    document.form1.k24_codigo.value = ''; 
  }
}
function js_mostracancdebitosproc1(chave1,chave2){
  document.form1.k24_codigo.value = chave1;
  document.form1.k23_codigo.value = chave2;
  db_iframe_cancdebitosproc.hide();
}
function js_pesquisak24_cancdebitosreg(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitosreg','func_cancdebitosreg.php?funcao_js=parent.js_mostracancdebitosreg1|k21_sequencia|k21_codigo','Pesquisa',true);
  }else{
     if(document.form1.k24_cancdebitosreg.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitosreg','func_cancdebitosreg.php?pesquisa_chave='+document.form1.k24_cancdebitosreg.value+'&funcao_js=parent.js_mostracancdebitosreg','Pesquisa',false);
     }else{
       document.form1.k21_codigo.value = ''; 
     }
  }
}
function js_mostracancdebitosreg(chave,erro){
  document.form1.k21_codigo.value = chave; 
  if(erro==true){ 
    document.form1.k24_cancdebitosreg.focus(); 
    document.form1.k24_cancdebitosreg.value = ''; 
  }
}
function js_mostracancdebitosreg1(chave1,chave2){
  document.form1.k24_cancdebitosreg.value = chave1;
  document.form1.k21_codigo.value = chave2;
  db_iframe_cancdebitosreg.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitosprocreg','func_cancdebitosprocreg.php?funcao_js=parent.js_preenchepesquisa|k24_sequencia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cancdebitosprocreg.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>