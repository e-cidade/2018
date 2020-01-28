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

//MODULO: veiculos
$clveicparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
$clrotulo->label("ve20_descr");
$clrotulo->label("ve30_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?//=@$Tve50_codigo?>">
       <?//=@$Lve50_codigo?>
    </td>
    <td> 
<?
db_input('ve50_codigo',10,$Ive50_codigo,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?//=@$Tve50_instit?>">
       <?
       //db_ancora(@$Lve50_instit,"js_pesquisave50_instit(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$ve50_instit=db_getsession("DB_instit");
db_input('ve50_instit',2,$Ive50_instit,true,'hidden',$db_opcao," onchange='js_pesquisave50_instit(false);'")
?>
       <?
//db_input('nomeinst',80,$Inomeinst,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve50_veiccadtipo?>">
       <?
       db_ancora(@$Lve50_veiccadtipo,"js_pesquisave50_veiccadtipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve50_veiccadtipo',10,$Ive50_veiccadtipo,true,'text',$db_opcao," onchange='js_pesquisave50_veiccadtipo(false);'")
?>
       <?
db_input('ve20_descr',40,$Ive20_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve50_veiccadcategcnh?>">
       <?
       db_ancora(@$Lve50_veiccadcategcnh,"js_pesquisave50_veiccadcategcnh(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ve50_veiccadcategcnh',10,$Ive50_veiccadcategcnh,true,'text',$db_opcao," onchange='js_pesquisave50_veiccadcategcnh(false);'")
?>
       <?
db_input('ve30_descr',40,$Ive30_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve50_integrapatri?>">
       <?=@$Lve50_integrapatri?>
    </td>
    <td> 
<?
$x = array('1'=>'Sim','0'=>'Não');
db_select('ve50_integrapatri',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve50_postoproprio?>">
       <?=@$Lve50_postoproprio?>
    </td>
    <td> 
<?
$x = array('1'=>'Sim','0'=>'Não','3'=>'Ambos');
db_select('ve50_postoproprio',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tve50_integrapessoal?>"><?=@$Lve50_integrapessoal?></td>
    <td>
<?
$x = array("1"=>"integracao com funcionarios ativos","2"=>"integracao com CGM");
db_select("ve50_integrapessoal",$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisave50_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.ve50_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.ve50_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.ve50_instit.focus(); 
    document.form1.ve50_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.ve50_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisave50_veiccadtipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?funcao_js=parent.js_mostraveiccadtipo1|ve20_codigo|ve20_descr','Pesquisa',true);
  }else{
     if(document.form1.ve50_veiccadtipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadtipo','func_veiccadtipo.php?pesquisa_chave='+document.form1.ve50_veiccadtipo.value+'&funcao_js=parent.js_mostraveiccadtipo','Pesquisa',false);
     }else{
       document.form1.ve20_descr.value = ''; 
     }
  }
}
function js_mostraveiccadtipo(chave,erro){
  document.form1.ve20_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve50_veiccadtipo.focus(); 
    document.form1.ve50_veiccadtipo.value = ''; 
  }
}
function js_mostraveiccadtipo1(chave1,chave2){
  document.form1.ve50_veiccadtipo.value = chave1;
  document.form1.ve20_descr.value = chave2;
  db_iframe_veiccadtipo.hide();
}
function js_pesquisave50_veiccadcategcnh(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcategcnh','func_veiccadcategcnh.php?funcao_js=parent.js_mostraveiccadcategcnh1|ve30_codigo|ve30_descr','Pesquisa',true);
  }else{
     if(document.form1.ve50_veiccadcategcnh.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_veiccadcategcnh','func_veiccadcategcnh.php?pesquisa_chave='+document.form1.ve50_veiccadcategcnh.value+'&funcao_js=parent.js_mostraveiccadcategcnh','Pesquisa',false);
     }else{
       document.form1.ve30_descr.value = ''; 
     }
  }
}
function js_mostraveiccadcategcnh(chave,erro){
  document.form1.ve30_descr.value = chave; 
  if(erro==true){ 
    document.form1.ve50_veiccadcategcnh.focus(); 
    document.form1.ve50_veiccadcategcnh.value = ''; 
  }
}
function js_mostraveiccadcategcnh1(chave1,chave2){
  document.form1.ve50_veiccadcategcnh.value = chave1;
  document.form1.ve30_descr.value = chave2;
  db_iframe_veiccadcategcnh.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_veicparam','func_veicparam.php?funcao_js=parent.js_preenchepesquisa|ve50_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_veicparam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>