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

//MODULO: projetos
$clobrasender->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tob07_codconstr?>">
       <?=@$Lob07_codconstr?>
    </td>
    <td> 
<?
db_input('ob07_codconstr',10,$Iob07_codconstr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_codobra?>">
       <?=@$Lob07_codobra?>
    </td>
    <td> 
<?
db_input('ob07_codobra',10,$Iob07_codobra,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_lograd?>">
       <?
       db_ancora(@$Lob07_lograd,"js_pesquisaob07_lograd(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob07_lograd',7,$Iob07_lograd,true,'text',$db_opcao," onchange='js_pesquisaob07_lograd(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_numero?>">
       <?=@$Lob07_numero?>
    </td>
    <td> 
<?
db_input('ob07_numero',10,$Iob07_numero,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_compl?>">
       <?=@$Lob07_compl?>
    </td>
    <td> 
<?
db_input('ob07_compl',20,$Iob07_compl,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_bairro?>">
       <?
       db_ancora(@$Lob07_bairro,"js_pesquisaob07_bairro(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ob07_bairro',4,$Iob07_bairro,true,'text',$db_opcao," onchange='js_pesquisaob07_bairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_areaatual?>">
       <?=@$Lob07_areaatual?>
    </td>
    <td> 
<?
db_input('ob07_areaatual',10,$Iob07_areaatual,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_unidades?>">
       <?=@$Lob07_unidades?>
    </td>
    <td> 
<?
db_input('ob07_unidades',10,$Iob07_unidades,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_pavimentos?>">
       <?=@$Lob07_pavimentos?>
    </td>
    <td> 
<?
db_input('ob07_pavimentos',10,$Iob07_pavimentos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_inicio?>">
       <?=@$Lob07_inicio?>
    </td>
    <td> 
<?
db_inputdata('ob07_inicio',@$ob07_inicio_dia,@$ob07_inicio_mes,@$ob07_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tob07_fim?>">
       <?=@$Lob07_fim?>
    </td>
    <td> 
<?
db_inputdata('ob07_fim',@$ob07_fim_dia,@$ob07_fim_mes,@$ob07_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaob07_lograd(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome','Pesquisa',true);
  }else{
     if(document.form1.ob07_lograd.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.ob07_lograd.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false);
     }else{
       document.form1.j14_nome.value = ''; 
     }
  }
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave; 
  if(erro==true){ 
    document.form1.ob07_lograd.focus(); 
    document.form1.ob07_lograd.value = ''; 
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.ob07_lograd.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisaob07_bairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
     if(document.form1.ob07_bairro.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.ob07_bairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
     }else{
       document.form1.j13_descr.value = ''; 
     }
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.ob07_bairro.focus(); 
    document.form1.ob07_bairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.ob07_bairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_obrasender','func_obrasender.php?funcao_js=parent.js_preenchepesquisa|ob07_codconstr','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_obrasender.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>