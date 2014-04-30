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

//MODULO: contabilidade
$clconplanoconta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c60_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc63_codcon?>">
       <?
       db_ancora(@$Lc63_codcon,"js_pesquisac63_codcon(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c63_codcon',6,$Ic63_codcon,true,'text',$db_opcao," onchange='js_pesquisac63_codcon(false);'")
?>
       <?
db_input('c60_descr',50,$Ic60_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_banco?>">
       <?=@$Lc63_banco?>
    </td>
    <td> 
<?
db_input('c63_banco',5,$Ic63_banco,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_agencia?>">
       <?=@$Lc63_agencia?>
    </td>
    <td> 
<?
db_input('c63_agencia',5,$Ic63_agencia,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc63_conta?>">
       <?=@$Lc63_conta?>
    </td>
    <td> 
<?
db_input('c63_conta',50,$Ic63_conta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac63_codcon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?funcao_js=parent.js_mostraconplano1|c60_codcon|c60_descr','Pesquisa',true);
  }else{
     if(document.form1.c63_codcon.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_conplano','func_conplano.php?pesquisa_chave='+document.form1.c63_codcon.value+'&funcao_js=parent.js_mostraconplano','Pesquisa',false);
     }else{
       document.form1.c60_descr.value = ''; 
     }
  }
}
function js_mostraconplano(chave,erro){
  document.form1.c60_descr.value = chave; 
  if(erro==true){ 
    document.form1.c63_codcon.focus(); 
    document.form1.c63_codcon.value = ''; 
  }
}
function js_mostraconplano1(chave1,chave2){
  document.form1.c63_codcon.value = chave1;
  document.form1.c60_descr.value = chave2;
  db_iframe_conplano.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_conplanoconta','func_conplanoconta.php?funcao_js=parent.js_preenchepesquisa|c63_codcon','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_conplanoconta.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>