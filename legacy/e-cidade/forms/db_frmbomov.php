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

//MODULO: teleatend
$clbomov->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bo01_obs");
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbo04_codmov?>">
       <?=@$Lbo04_codmov?>
    </td>
    <td> 
<?
db_input('bo04_codmov',9,$Ibo04_codmov,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_codbo?>">
       <?
       db_ancora(@$Lbo04_codbo,"js_pesquisabo04_codbo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo04_codbo',6,$Ibo04_codbo,true,'text',$db_opcao," onchange='js_pesquisabo04_codbo(false);'")
?>
       <?
db_input('bo01_obs',200,$Ibo01_obs,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_datamov?>">
       <?=@$Lbo04_datamov?>
    </td>
    <td> 
<?
db_inputdata('bo04_datamov',@$bo04_datamov_dia,@$bo04_datamov_mes,@$bo04_datamov_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_coddepto_ori?>">
       <?
       db_ancora(@$Lbo04_coddepto_ori,"js_pesquisabo04_coddepto_ori(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo04_coddepto_ori',5,$Ibo04_coddepto_ori,true,'text',$db_opcao," onchange='js_pesquisabo04_coddepto_ori(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_coddepto_dest?>">
       <?=@$Lbo04_coddepto_dest?>
    </td>
    <td> 
<?
db_input('bo04_coddepto_dest',5,$Ibo04_coddepto_dest,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_entrada?>">
       <?=@$Lbo04_entrada?>
    </td>
    <td> 
<?
db_input('bo04_entrada',3,$Ibo04_entrada,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo04_saida?>">
       <?=@$Lbo04_saida?>
    </td>
    <td> 
<?
db_input('bo04_saida',3,$Ibo04_saida,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisabo04_codbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bo','func_bo.php?funcao_js=parent.js_mostrabo1|bo01_codbo|bo01_obs','Pesquisa',true);
  }else{
     if(document.form1.bo04_codbo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bo','func_bo.php?pesquisa_chave='+document.form1.bo04_codbo.value+'&funcao_js=parent.js_mostrabo','Pesquisa',false);
     }else{
       document.form1.bo01_obs.value = ''; 
     }
  }
}
function js_mostrabo(chave,erro){
  document.form1.bo01_obs.value = chave; 
  if(erro==true){ 
    document.form1.bo04_codbo.focus(); 
    document.form1.bo04_codbo.value = ''; 
  }
}
function js_mostrabo1(chave1,chave2){
  document.form1.bo04_codbo.value = chave1;
  document.form1.bo01_obs.value = chave2;
  db_iframe_bo.hide();
}
function js_pesquisabo04_coddepto_ori(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.bo04_coddepto_ori.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.bo04_coddepto_ori.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.bo04_coddepto_ori.focus(); 
    document.form1.bo04_coddepto_ori.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.bo04_coddepto_ori.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bomov','func_bomov.php?funcao_js=parent.js_preenchepesquisa|bo04_codmov','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bomov.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>