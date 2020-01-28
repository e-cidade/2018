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

//MODULO: dividaativa
$cldivimportareg->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("v02_usuario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tv04_divimporta?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora(@$Lv04_divimporta,"js_pesquisav04_divimporta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('v04_divimporta',10,$Iv04_divimporta,true,'text',$db_opcao," onchange='js_pesquisav04_divimporta(false);'")
?>
       <?
db_input('v02_usuario',10,$Iv02_usuario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tv04_coddiv?>">
       <?=@$Lv04_coddiv?>
    </td>
    <td> 
<?
db_input('v04_coddiv',10,$Iv04_coddiv,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisav04_divimporta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_divimporta','func_divimporta.php?funcao_js=parent.js_mostradivimporta1|v02_divimporta|v02_usuario','Pesquisa',true);
  }else{
     if(document.form1.v04_divimporta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_divimporta','func_divimporta.php?pesquisa_chave='+document.form1.v04_divimporta.value+'&funcao_js=parent.js_mostradivimporta','Pesquisa',false);
     }else{
       document.form1.v02_usuario.value = ''; 
     }
  }
}
function js_mostradivimporta(chave,erro){
  document.form1.v02_usuario.value = chave; 
  if(erro==true){ 
    document.form1.v04_divimporta.focus(); 
    document.form1.v04_divimporta.value = ''; 
  }
}
function js_mostradivimporta1(chave1,chave2){
  document.form1.v04_divimporta.value = chave1;
  document.form1.v02_usuario.value = chave2;
  db_iframe_divimporta.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_divimportareg','func_divimportareg.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_divimportareg.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>