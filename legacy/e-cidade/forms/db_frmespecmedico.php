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

//MODULO: saude
$clespecmedico->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh70_sequencial");
$clrotulo->label("sd04_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tsd27_i_codigo?>">
       <?=@$Lsd27_i_codigo?>
    </td>
    <td> 
<?
$x = array(''=>'');
db_select('sd27_i_codigo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_rhcbo?>">
       <?
       db_ancora(@$Lsd27_i_rhcbo,"js_pesquisasd27_i_rhcbo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$x = array(''=>'');
db_select('sd27_i_rhcbo',$x,true,$db_opcao," onchange='js_pesquisasd27_i_rhcbo(false);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_i_undmed?>">
       <?
       db_ancora(@$Lsd27_i_undmed,"js_pesquisasd27_i_undmed(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
$x = array(''=>'');
db_select('sd27_i_undmed',$x,true,$db_opcao," onchange='js_pesquisasd27_i_undmed(false);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tsd27_b_principal?>">
       <?=@$Lsd27_b_principal?>
    </td>
    <td> 
<?
$x = array(''=>'');
db_select('sd27_b_principal',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisasd27_i_rhcbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhcbo','func_rhcbo.php?funcao_js=parent.js_mostrarhcbo1|rh70_sequencial|rh70_sequencial','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_rhcbo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhcbo','func_rhcbo.php?pesquisa_chave='+document.form1.sd27_i_rhcbo.value+'&funcao_js=parent.js_mostrarhcbo','Pesquisa',false);
     }else{
       document.form1.rh70_sequencial.value = ''; 
     }
  }
}
function js_mostrarhcbo(chave,erro){
  document.form1.rh70_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.sd27_i_rhcbo.focus(); 
    document.form1.sd27_i_rhcbo.value = ''; 
  }
}
function js_mostrarhcbo1(chave1,chave2){
  document.form1.sd27_i_rhcbo.value = chave1;
  document.form1.rh70_sequencial.value = chave2;
  db_iframe_rhcbo.hide();
}
function js_pesquisasd27_i_undmed(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?funcao_js=parent.js_mostraunidademedicos1|sd04_i_codigo|sd04_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.sd27_i_undmed.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_unidademedicos','func_unidademedicos.php?pesquisa_chave='+document.form1.sd27_i_undmed.value+'&funcao_js=parent.js_mostraunidademedicos','Pesquisa',false);
     }else{
       document.form1.sd04_i_codigo.value = ''; 
     }
  }
}
function js_mostraunidademedicos(chave,erro){
  document.form1.sd04_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.sd27_i_undmed.focus(); 
    document.form1.sd27_i_undmed.value = ''; 
  }
}
function js_mostraunidademedicos1(chave1,chave2){
  document.form1.sd27_i_undmed.value = chave1;
  document.form1.sd04_i_codigo.value = chave2;
  db_iframe_unidademedicos.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_especmedico','func_especmedico.php?funcao_js=parent.js_preenchepesquisa|sd27_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_especmedico.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>