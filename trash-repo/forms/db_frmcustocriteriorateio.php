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

//MODULO: Custos
$clcustocriteriorateio->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("m61_descr");
      if($db_opcao==1){
 	   $db_action="cus1_custocriteriorateio004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="Cus1_custocriteriorateio005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="Cus1_custocriteriorateio006.php";
      }  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<table>
<tr>
<td>
<fieldset>
<legend><b>Cadastro de Critérios</b></legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcc08_sequencial?>">
       <?=@$Lcc08_sequencial?>
    </td>
    <td> 
<?
db_input('cc08_sequencial',10,$Icc08_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc08_matunid?>">
       <?
       db_ancora(@$Lcc08_matunid,"js_pesquisacc08_matunid(true);",$db_opcao);
       ?>
    </td>
    <td> 
     <?
    db_input('cc08_matunid',10,$Icc08_matunid,true,'text',$db_opcao," onchange='js_pesquisacc08_matunid(false);'");
    db_input('m61_descr',39,$Im61_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc08_descricao?>">
       <?=@$Lcc08_descricao?>
    </td>
    <td> 
    <?
    db_input('cc08_descricao',53,$Icc08_descricao,true,'text',$db_opcao,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc08_obs?>">
       <?=@$Lcc08_obs?>
    </td>
    <td> 
   <?
   db_textarea('cc08_obs',8, 51,$Icc08_obs,true,'text',$db_opcao,"")
  ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcc08_ativo?>">
       <?=@$Lcc08_ativo?>
    </td>
    <td> 
   <?
   $x = array("f"=>"NAO","t"=>"SIM");
   db_select('cc08_ativo',$x,true,$db_opcao,"");
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
function js_pesquisacc08_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateio','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.cc08_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateio','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.cc08_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false,'0','1','775','390');
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.cc08_instit.focus(); 
    document.form1.cc08_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.cc08_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisacc08_matunid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateio',
                        'db_iframe_matunid',
                        'func_matunid.php?funcao_js=parent.js_mostramatunid1|m61_codmatunid|m61_descr',
                        'Pesquisa',
                        true,'0','1',
                        (document.body.scrollWidth-10),
                        (document.body.scrollHeight-80));
  }else{
     if(document.form1.cc08_matunid.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateio',
                            'db_iframe_matunid',
                            'func_matunid.php?pesquisa_chave='+document.form1.cc08_matunid.value+'&funcao_js=parent.js_mostramatunid',
                            'Consulta de Unidades',
                            false,
                            '0',
                            '1',
                             (document.body.scrollWidth-10),
                             (document.body.scrollHeight-80));
     }else{
       document.form1.m61_descr.value = ''; 
     }
  }
}
function js_mostramatunid(chave,erro){
  document.form1.m61_descr.value = chave; 
  if(erro==true){ 
    document.form1.cc08_matunid.focus(); 
    document.form1.cc08_matunid.value = ''; 
  }
}
function js_mostramatunid1(chave1,chave2){
  document.form1.cc08_matunid.value = chave1;
  document.form1.m61_descr.value = chave2;
  db_iframe_matunid.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_custocriteriorateio',
                      'db_iframe_custocriteriorateio',
                      'func_custocriteriorateio.php?funcao_js=parent.js_preenchepesquisa|cc08_sequencial',
                      'Critérios de Rateios ',
                      true,'0','1',
                      (document.body.scrollWidth-10),
                      (document.body.scrollHeight-80));
}
function js_preenchepesquisa(chave){
  db_iframe_custocriteriorateio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>