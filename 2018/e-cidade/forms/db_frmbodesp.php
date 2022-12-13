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
$clbodesp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("bo01_obs");
$clrotulo->label("descrdepto");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tbo05_cod_desp?>">
       <?=@$Lbo05_cod_desp?>
    </td>
    <td> 
<?
db_input('bo05_cod_desp',9,$Ibo05_cod_desp,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo05_codbo?>">
       <?
       db_ancora(@$Lbo05_codbo,"js_pesquisabo05_codbo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo05_codbo',9,$Ibo05_codbo,true,'text',$db_opcao," onchange='js_pesquisabo05_codbo(false);'")
?>
       <?
/*db_input('bo01_obs',200,$Ibo01_obs,true,'text',3,'')*/
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo05_datadesp?>">
       <?=@$Lbo05_datadesp?>
    </td>
    <td> 
<?
db_inputdata('bo05_datadesp',@$bo05_datadesp_dia,@$bo05_datadesp_mes,@$bo05_datadesp_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo05_coddepto_ori?>">
       <?=@$Lbo05_coddepto_ori?>
    </td>
    <td> 
<?
db_input('bo05_coddepto_ori',5,$Ibo05_coddepto_ori,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo05_coddepto_dest?>">
       <?
       db_ancora(@$Lbo05_coddepto_dest,"js_pesquisabo05_coddepto_dest(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('bo05_coddepto_dest',5,$Ibo05_coddepto_dest,true,'text',$db_opcao," onchange='js_pesquisabo05_coddepto_dest(false);'")
?>
       <?
db_input('descrdepto',40,$Idescrdepto,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbo05_despacho?>">
       <?=@$Lbo05_despacho?>
    </td>
    <td> 
<?
db_textarea('bo05_despacho',5,80,$Ibo05_despacho,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisabo05_codbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bo','func_bo.php?funcao_js=parent.js_mostrabo1|bo01_codbo|bo01_obs','Pesquisa',true);
  }else{
     if(document.form1.bo05_codbo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_bo','func_bo.php?pesquisa_chave='+document.form1.bo05_codbo.value+'&funcao_js=parent.js_mostrabo','Pesquisa',false);
     }else{
       document.form1.bo01_obs.value = ''; 
     }
  }
}
function js_mostrabo(chave,erro){
  document.form1.bo01_obs.value = chave; 
  if(erro==true){ 
    document.form1.bo05_codbo.focus(); 
    document.form1.bo05_codbo.value = ''; 
  }
}
function js_mostrabo1(chave1,chave2){
  document.form1.bo05_codbo.value = chave1;
  document.form1.bo01_obs.value = chave2;
  db_iframe_bo.hide();
}
function js_pesquisabo05_coddepto_dest(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?funcao_js=parent.js_mostradb_depart1|coddepto|descrdepto','Pesquisa',true);
  }else{
     if(document.form1.bo05_coddepto_dest.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_depart','func_db_depart.php?pesquisa_chave='+document.form1.bo05_coddepto_dest.value+'&funcao_js=parent.js_mostradb_depart','Pesquisa',false);
     }else{
       document.form1.descrdepto.value = ''; 
     }
  }
}
function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.bo05_coddepto_dest.focus(); 
    document.form1.bo05_coddepto_dest.value = ''; 
  }
}
function js_mostradb_depart1(chave1,chave2){
  document.form1.bo05_coddepto_dest.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe_db_depart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_bodesp','func_bodesp.php?funcao_js=parent.js_preenchepesquisa|bo05_cod_desp','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_bodesp.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>