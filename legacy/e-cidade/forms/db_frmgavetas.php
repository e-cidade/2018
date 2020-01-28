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
$clgavetas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm26_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm27_i_codigo?>">
       <?=@$Lcm27_i_codigo?>
    </td>
    <td> 
<?
db_input('cm27_i_codigo',10,$Icm27_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm27_i_restogaveta?>">
       <?
       db_ancora(@$Lcm27_i_restogaveta,"js_pesquisacm27_i_restogaveta(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('cm27_i_restogaveta',10,$Icm27_i_restogaveta,true,'text',$db_opcao," onchange='js_pesquisacm27_i_restogaveta(false);'")
?>
       <?
db_input('cm26_i_codigo',10,$Icm26_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm27_d_exumprevista?>">
       <?=@$Lcm27_d_exumprevista?>
    </td>
    <td> 
<?
db_inputdata('cm27_d_exumprevista',@$cm27_d_exumprevista_dia,@$cm27_d_exumprevista_mes,@$cm27_d_exumprevista_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm27_d_exumfeita?>">
       <?=@$Lcm27_d_exumfeita?>
    </td>
    <td> 
<?
db_inputdata('cm27_d_exumfeita',@$cm27_d_exumfeita_dia,@$cm27_d_exumfeita_mes,@$cm27_d_exumfeita_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm27_c_ossoario?>">
       <?=@$Lcm27_c_ossoario?>
    </td>
    <td> 
<?
db_input('cm27_c_ossoario',1,$Icm27_c_ossoario,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm27_i_gaveta?>">
       <?=@$Lcm27_i_gaveta?>
    </td>
    <td> 
<?
db_input('cm27_i_gaveta',10,$Icm27_i_gaveta,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm27_i_restogaveta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_restosgavetas','func_restosgavetas.php?funcao_js=parent.js_mostrarestosgavetas1|cm26_i_codigo|cm26_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.cm27_i_restogaveta.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_restosgavetas','func_restosgavetas.php?pesquisa_chave='+document.form1.cm27_i_restogaveta.value+'&funcao_js=parent.js_mostrarestosgavetas','Pesquisa',false);
     }else{
       document.form1.cm26_i_codigo.value = ''; 
     }
  }
}
function js_mostrarestosgavetas(chave,erro){
  document.form1.cm26_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.cm27_i_restogaveta.focus(); 
    document.form1.cm27_i_restogaveta.value = ''; 
  }
}
function js_mostrarestosgavetas1(chave1,chave2){
  document.form1.cm27_i_restogaveta.value = chave1;
  document.form1.cm26_i_codigo.value = chave2;
  db_iframe_restosgavetas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_gavetas','func_gavetas.php?funcao_js=parent.js_preenchepesquisa|cm27_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_gavetas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>