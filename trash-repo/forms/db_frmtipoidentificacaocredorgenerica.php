<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
$cltipoidentificacaocredorgenerica->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c24_descricao");
?>
<form name="form1" method="post" action="">
<center>
<fieldset style="width: 650px;">
  <legend><b>Inscri��o Gen�rica</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc25_sequencial?>">
       <?=@$Lc25_sequencial?>
    </td>
    <td> 
<?
db_input('c25_sequencial',4,$Ic25_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc25_tipoidentificacaocredor?>">
       <?
       db_ancora(@$Lc25_tipoidentificacaocredor,"js_pesquisac25_tipoidentificacaocredor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('c25_tipoidentificacaocredor',4,$Ic25_tipoidentificacaocredor,true,'text',$db_opcao," onchange='js_pesquisac25_tipoidentificacaocredor(false);'")
?>
       <?
db_input('c24_descricao',60,$Ic24_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc25_descricao?>">
       <?=@$Lc25_descricao?>
    </td>
    <td> 
<?
db_input('c25_descricao',68,$Ic25_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
</fieldset>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisac25_tipoidentificacaocredor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoidentificacaocredor','func_tipoidentificacaocredor.php?lCadastroGenerico=true&funcao_js=parent.js_mostratipoidentificacaocredor1|c24_sequencial|c24_descricao','Pesquisa',true);
  }else{
     if(document.form1.c25_tipoidentificacaocredor.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_tipoidentificacaocredor','func_tipoidentificacaocredor.php?lCadastroGenerico=true&pesquisa_chave='+document.form1.c25_tipoidentificacaocredor.value+'&funcao_js=parent.js_mostratipoidentificacaocredor','Pesquisa',false);
     }else{
       document.form1.c24_descricao.value = ''; 
     }
  }
}
function js_mostratipoidentificacaocredor(chave,erro){
  document.form1.c24_descricao.value = chave; 
  if(erro==true){ 
    document.form1.c25_tipoidentificacaocredor.focus(); 
    document.form1.c25_tipoidentificacaocredor.value = ''; 
  }
}
function js_mostratipoidentificacaocredor1(chave1,chave2){
  document.form1.c25_tipoidentificacaocredor.value = chave1;
  document.form1.c24_descricao.value = chave2;
  db_iframe_tipoidentificacaocredor.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_tipoidentificacaocredorgenerica','func_tipoidentificacaocredorgenerica.php?lCadastroGenerico=true&funcao_js=parent.js_preenchepesquisa|c25_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_tipoidentificacaocredorgenerica.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>