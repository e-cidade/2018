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

//MODULO: Ambulatorial
$clsau_atributoexames->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("m61_descr");
?>
<form name="form1" method="post" action="">
<center>
<table style="margin-top: 10px;">
<tr>
<td>
<fieldset>
<legend><b>Atributos de Exames</b></legend>


<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts131_i_codigo?>">
       <?=@$Ls131_i_codigo?>
    </td>
    <td> 
<?
db_input('s131_i_codigo',10,$Is131_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts131_matunid?>">
       <?
       db_ancora(@$Ls131_matunid,"js_pesquisas131_matunid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('s131_matunid',10,$Is131_matunid,true,'text',$db_opcao," onchange='js_pesquisas131_matunid(false);'")
?>
       <?
db_input('m61_descr',40,$Im61_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts131_c_descricao?>">
       <?=@$Ls131_c_descricao?>
    </td>
    <td> 
<?
db_input('s131_c_descricao',54,$Is131_c_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
</fieldset>
</td>
</tr>
</table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisas131_matunid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr','Pesquisa',true);
  }else{
     if(document.form1.s131_matunid.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matunid','func_matunid.php?pesquisa_chave='+document.form1.s131_matunid.value+'&funcao_js=parent.js_mostramatunid','Pesquisa',false);
     }else{
       document.form1.m61_descr.value = ''; 
     }
  }
}
function js_mostramatunid(chave,erro){
  document.form1.m61_descr.value = chave; 
  if(erro==true){ 
    document.form1.s131_matunid.focus(); 
    document.form1.s131_matunid.value = ''; 
  }
}
function js_mostramatunid1(chave1,chave2){
  document.form1.s131_matunid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_sau_atributoexames','func_sau_atributoexames.php?funcao_js=parent.js_preenchepesquisa|s131_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_atributoexames.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>