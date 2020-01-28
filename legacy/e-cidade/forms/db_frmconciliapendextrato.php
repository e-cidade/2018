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
$clconciliapendextrato->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k86_extrato");
$clrotulo->label("k68_data");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk88_sequencial?>">
       <?=@$Lk88_sequencial?>
    </td>
    <td> 
<?
db_input('k88_sequencial',10,$Ik88_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk88_extratolinha?>">
       <?
       db_ancora(@$Lk88_extratolinha,"js_pesquisak88_extratolinha(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k88_extratolinha',10,$Ik88_extratolinha,true,'text',$db_opcao," onchange='js_pesquisak88_extratolinha(false);'")
?>
       <?
db_input('k86_extrato',10,$Ik86_extrato,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk88_concilia?>">
       <?
       db_ancora(@$Lk88_concilia,"js_pesquisak88_concilia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k88_concilia',10,$Ik88_concilia,true,'text',$db_opcao," onchange='js_pesquisak88_concilia(false);'")
?>
       <?
db_input('k68_data',10,$Ik68_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisak88_extratolinha(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_extratolinha','func_extratolinha.php?funcao_js=parent.js_mostraextratolinha1|k86_sequencial|k86_extrato','Pesquisa',true);
  }else{
     if(document.form1.k88_extratolinha.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_extratolinha','func_extratolinha.php?pesquisa_chave='+document.form1.k88_extratolinha.value+'&funcao_js=parent.js_mostraextratolinha','Pesquisa',false);
     }else{
       document.form1.k86_extrato.value = ''; 
     }
  }
}
function js_mostraextratolinha(chave,erro){
  document.form1.k86_extrato.value = chave; 
  if(erro==true){ 
    document.form1.k88_extratolinha.focus(); 
    document.form1.k88_extratolinha.value = ''; 
  }
}
function js_mostraextratolinha1(chave1,chave2){
  document.form1.k88_extratolinha.value = chave1;
  document.form1.k86_extrato.value = chave2;
  db_iframe_extratolinha.hide();
}
function js_pesquisak88_concilia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?funcao_js=parent.js_mostraconcilia1|k68_sequencial|k68_data','Pesquisa',true);
  }else{
     if(document.form1.k88_concilia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?pesquisa_chave='+document.form1.k88_concilia.value+'&funcao_js=parent.js_mostraconcilia','Pesquisa',false);
     }else{
       document.form1.k68_data.value = ''; 
     }
  }
}
function js_mostraconcilia(chave,erro){
  document.form1.k68_data.value = chave; 
  if(erro==true){ 
    document.form1.k88_concilia.focus(); 
    document.form1.k88_concilia.value = ''; 
  }
}
function js_mostraconcilia1(chave1,chave2){
  document.form1.k88_concilia.value = chave1;
  document.form1.k68_data.value = chave2;
  db_iframe_concilia.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conciliapendextrato','func_conciliapendextrato.php?funcao_js=parent.js_preenchepesquisa|k88_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conciliapendextrato.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>