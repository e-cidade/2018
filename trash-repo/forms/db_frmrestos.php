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

//MODULO: cemiterio
$clrestos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("cm02_i_proprietario");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm12_i_codigo?>">
       <?=@$Lcm12_i_codigo?>
    </td>
    <td> 
<?
db_input('cm12_i_codigo',10,$Icm12_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm12_i_ossoariopart?>">
       <?
       db_ancora(@$Lcm12_i_ossoariopart,"js_pesquisacm12_i_ossoariopart(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm12_i_ossoariopart',10,$Icm12_i_ossoariopart,true,'text',$db_opcao," onchange='js_pesquisacm12_i_ossoariopart(false);'")
?>
       <?
db_input('proprietario',50,$proprietario,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm12_i_sepultamento?>">
       <?
       db_ancora(@$Lcm12_i_sepultamento,"js_pesquisacm12_i_sepultamento(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('cm12_i_sepultamento',10,$Icm12_i_sepultamento,true,'text',3," onchange='js_pesquisacm12_i_sepultamento(false);'")
?>
       <?
db_input('nome_sepultamento',50,$nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm12_d_entrada?>">
       <?=@$Lcm12_d_entrada?>
    </td>
    <td> 
<?
db_inputdata('cm12_d_entrada',@$cm12_d_entrada_dia,@$cm12_d_entrada_mes,@$cm12_d_entrada_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<?if($antigo != "X"){?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?}?>
</form>
<script>
function js_pesquisacm12_i_ossoariopart(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo<?if(!isset($antigo)){?>.iframe_a3<?}else{?>.iframe_a4<?}?>','db_iframe_ossoariopart','func_ossoariopart.php?funcao_js=parent.js_mostraossoariopart1|cm02_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm12_i_ossoariopart.value != ''){ 
        js_OpenJanelaIframe('top.corpo<?if(!isset($antigo)){?>.iframe_a3<?}else{?>.iframe_a4<?}?>','db_iframe_ossoariopart','func_ossoariopart.php?pesquisa_chave='+document.form1.cm12_i_ossoariopart.value+'&funcao_js=parent.js_mostraossoariopart','Pesquisa',false);
     }else{
       document.form1.proprietario.value = '';
     }
  }
}
function js_mostraossoariopart(chave,erro){
  document.form1.proprietario.value = chave;
  if(erro==true){ 
    document.form1.cm12_i_ossoariopart.focus(); 
    document.form1.cm12_i_ossoariopart.value = ''; 
  }
}
function js_mostraossoariopart1(chave1,chave2){
  document.form1.cm12_i_ossoariopart.value = chave1;
  document.form1.proprietario.value = chave2;
  db_iframe_ossoariopart.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_restos','func_restos.php?funcao_js=parent.js_preenchepesquisa|cm12_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_restos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>