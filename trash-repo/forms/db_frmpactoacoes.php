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
$clpactoacoes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o74_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To79_sequencial?>">
       <?=@$Lo79_sequencial?>
    </td>
    <td> 
<?
db_input('o79_sequencial',10,$Io79_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To79_pactoplano?>">
       <?
       db_ancora(@$Lo79_pactoplano,"js_pesquisao79_pactoplano(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o79_pactoplano',10,$Io79_pactoplano,true,'text',$db_opcao," onchange='js_pesquisao79_pactoplano(false);'")
?>
       <?
db_input('o74_descricao',46,$Io74_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To79_descricao?>">
       <?=@$Lo79_descricao?>
    </td>
    <td> 
<?
db_input('o79_descricao',60,$Io79_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To79_obs?>">
       <?=@$Lo79_obs?>
    </td>
    <td> 
<?
db_textarea('o79_obs',5,58,$Io79_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao79_pactoplano(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pactoplano','func_pactoplano.php?funcao_js=parent.js_mostrapactoplano1|o74_sequencial|o74_descricao','Pesquisa',true);
  }else{
     if(document.form1.o79_pactoplano.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pactoplano','func_pactoplano.php?pesquisa_chave='+document.form1.o79_pactoplano.value+'&funcao_js=parent.js_mostrapactoplano','Pesquisa',false);
     }else{
       document.form1.o74_descricao.value = ''; 
     }
  }
}
function js_mostrapactoplano(chave,erro){
  document.form1.o74_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o79_pactoplano.focus(); 
    document.form1.o79_pactoplano.value = ''; 
  }
}
function js_mostrapactoplano1(chave1,chave2){
  document.form1.o79_pactoplano.value = chave1;
  document.form1.o74_descricao.value = chave2;
  db_iframe_pactoplano.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pactoacoes','func_pactoacoes.php?funcao_js=parent.js_preenchepesquisa|o79_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pactoacoes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>