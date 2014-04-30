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

//MODULO: pessoal
$clrharqbanco->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db90_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh34_codarq?>">
      <?=@$Lrh34_codarq?>
    </td>
    <td> 
      <?
      db_input('rh34_codarq',6,$Irh34_codarq,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_descr?>">
      <?=@$Lrh34_descr?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_descr',53,$Irh34_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_where?>">
      <?=@$Lrh34_where?>
    </td>
    <td colspan="3">
      <?
      db_input('rh34_where',53,$Irh34_where,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_codban?>">
      <?
      db_ancora(@$Lrh34_codban,"js_pesquisarh34_codban(true);",$db_opcao);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('rh34_codban',10,$Irh34_codban,true,'text',$db_opcao," onchange='js_pesquisarh34_codban(false);'")
      ?>
      <?
      db_input('db90_descr',40,$Idb90_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_agencia?>">
      <?=@$Lrh34_agencia?>
    </td>
    <td> 
      <?
      db_input('rh34_agencia',5,$Irh34_agencia,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvagencia?>" align="right">
      <?=@$Lrh34_dvagencia?>
    </td>
    <td> 
      <?
      db_input('rh34_dvagencia',2,$Irh34_dvagencia,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_conta?>">
      <?=@$Lrh34_conta?>
    </td>
    <td> 
      <?
      db_input('rh34_conta',15,$Irh34_conta,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_dvconta?>" align="right">
      <?=@$Lrh34_dvconta?>
    </td>
    <td> 
      <?
      db_input('rh34_dvconta',2,$Irh34_dvconta,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_convenio?>">
      <?=@$Lrh34_convenio?>
    </td>
    <td> 
      <?
      db_input('rh34_convenio',20,$Irh34_convenio,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh34_sequencial?>">
      <?=@$Lrh34_sequencial?>
    </td>
    <td> 
      <?
      db_input('rh34_sequencial',15,$Irh34_sequencial,true,'text',$db_opcao,"")
      ?>
    </td>
    <td nowrap title="<?=@$Trh34_ativo?>" align="right">
      <?=@$Lrh34_ativo?>
    </td>
    <td> 
      <?
      if(!isset($rh34_ativo)){
	$rh34_ativo = "t";
      }
      $x = array("f"=>"NAO","t"=>"SIM");
      db_select('rh34_ativo',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh34_codban(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?funcao_js=parent.js_mostradb_bancos1|db90_codban|db90_descr','Pesquisa',true);
  }else{
    if(document.form1.rh34_codban.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_db_bancos','func_db_bancos.php?pesquisa_chave='+document.form1.rh34_codban.value+'&funcao_js=parent.js_mostradb_bancos','Pesquisa',false);
    }else{
      document.form1.db90_descr.value = ''; 
    }
  }
}
function js_mostradb_bancos(chave,erro){
  document.form1.db90_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh34_codban.focus(); 
    document.form1.rh34_codban.value = ''; 
  }
}
function js_mostradb_bancos1(chave1,chave2){
  document.form1.rh34_codban.value = chave1;
  document.form1.db90_descr.value = chave2;
  db_iframe_db_bancos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rharqbanco','func_rharqbanco.php?funcao_js=parent.js_preenchepesquisa|rh34_codarq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rharqbanco.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>