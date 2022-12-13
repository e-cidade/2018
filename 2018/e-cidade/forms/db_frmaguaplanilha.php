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

//MODULO: agua
$claguaplanilha->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("x01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tx24_exerc?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?=@$Lx24_exerc?>
    </td>
    <td> 
<?
db_input('x24_exerc',4,$Ix24_exerc,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_mes?>">
       <?=@$Lx24_mes?>
    </td>
    <td> 
<?
db_input('x24_mes',2,$Ix24_mes,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_matric?>">
       <?
       db_ancora(@$Lx24_matric,"js_pesquisax24_matric(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('x24_matric',10,$Ix24_matric,true,'text',$db_opcao," onchange='js_pesquisax24_matric(false);'")
?>
       <?
db_input('x01_numcgm',10,$Ix01_numcgm,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_nome?>">
       <?=@$Lx24_nome?>
    </td>
    <td> 
<?
db_input('x24_nome',40,$Ix24_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_codrua?>">
       <?=@$Lx24_codrua?>
    </td>
    <td> 
<?
db_input('x24_codrua',7,$Ix24_codrua,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_nomerua?>">
       <?=@$Lx24_nomerua?>
    </td>
    <td> 
<?
db_input('x24_nomerua',40,$Ix24_nomerua,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_numero?>">
       <?=@$Lx24_numero?>
    </td>
    <td> 
<?
db_input('x24_numero',5,$Ix24_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_complemento?>">
       <?=@$Lx24_complemento?>
    </td>
    <td> 
<?
db_input('x24_complemento',10,$Ix24_complemento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_zona?>">
       <?=@$Lx24_zona?>
    </td>
    <td> 
<?
db_input('x24_zona',5,$Ix24_zona,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_rota?>">
       <?=@$Lx24_rota?>
    </td>
    <td> 
<?
db_input('x24_rota',4,$Ix24_rota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_pagina?>">
       <?=@$Lx24_pagina?>
    </td>
    <td> 
<?
db_input('x24_pagina',8,$Ix24_pagina,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tx24_nrohidro?>">
       <?=@$Lx24_nrohidro?>
    </td>
    <td> 
<?
db_input('x24_nrohidro',15,$Ix24_nrohidro,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisax24_matric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_aguabase','func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm','Pesquisa',true);
  }else{
     if(document.form1.x24_matric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_aguabase','func_aguabase.php?pesquisa_chave='+document.form1.x24_matric.value+'&funcao_js=parent.js_mostraaguabase','Pesquisa',false);
     }else{
       document.form1.x01_numcgm.value = ''; 
     }
  }
}
function js_mostraaguabase(chave,erro){
  document.form1.x01_numcgm.value = chave; 
  if(erro==true){ 
    document.form1.x24_matric.focus(); 
    document.form1.x24_matric.value = ''; 
  }
}
function js_mostraaguabase1(chave1,chave2){
  document.form1.x24_matric.value = chave1;
  document.form1.x01_numcgm.value = chave2;
  db_iframe_aguabase.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_aguaplanilha','func_aguaplanilha.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_aguaplanilha.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>