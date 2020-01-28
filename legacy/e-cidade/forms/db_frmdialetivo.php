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

//MODULO: educação
$cldialetivo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed32_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted04_i_escola?>">
   <?db_ancora(@$Led04_i_escola,"",3);?>
   <?db_input('ed04_i_escola',10,$Ied04_i_escola,true,'text',3,"")?>
  </td>
  <td>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td width="30%"></td>
  <td>
   <?
   $escola = db_getsession("DB_coddepto");
   $result = $cldiasemana->sql_record($cldiasemana->sql_query_letivo("","*","ed32_i_codigo"," ed04_i_escola = $ed04_i_escola"));
   if($cldiasemana->numrows==0){
    $result = $cldiasemana->sql_record($cldiasemana->sql_query("","*","ed32_i_codigo",""));
   }
   for($x=0;$x<$cldiasemana->numrows;$x++){
    db_fieldsmemory($result,$x);
    if(isset($ed04_c_letivo) && $ed04_c_letivo=="S"){
     $check = "checked";
    }else{
     $check = "";
    }
    ?>
    <input type="checkbox" name="<?=$ed32_i_codigo?>" value="ativo" <?=$check?>> <?=$ed32_c_descr?><br>
    <?
   }
   ?>
  </td>
 </tr>
</table>
</center>
<input name="gravar" type="submit" value="Gravar">
<input name="quant" type="hidden" value="<?=@$linhas?>">
</form>
<script>
function js_pesquisaed04_i_escola(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?funcao_js=parent.js_mostraescola1|ed18_i_codigo|ed18_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed04_i_escola.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escola','func_escola.php?pesquisa_chave='+document.form1.ed04_i_escola.value+'&funcao_js=parent.js_mostraescola','Pesquisa',false);
     }else{
       document.form1.ed18_i_codigo.value = ''; 
     }
  }
}
function js_mostraescola(chave,erro){
  document.form1.ed18_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed04_i_escola.focus(); 
    document.form1.ed04_i_escola.value = ''; 
  }
}
function js_mostraescola1(chave1,chave2){
  document.form1.ed04_i_escola.value = chave1;
  document.form1.ed18_i_codigo.value = chave2;
  db_iframe_escola.hide();
}
function js_pesquisaed04_i_diasemana(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_diasemana','func_diasemana.php?funcao_js=parent.js_mostradiasemana1|ed32_i_codigo|ed32_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed04_i_diasemana.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_diasemana','func_diasemana.php?pesquisa_chave='+document.form1.ed04_i_diasemana.value+'&funcao_js=parent.js_mostradiasemana','Pesquisa',false);
     }else{
       document.form1.ed32_i_codigo.value = ''; 
     }
  }
}
function js_mostradiasemana(chave,erro){
  document.form1.ed32_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed04_i_diasemana.focus(); 
    document.form1.ed04_i_diasemana.value = ''; 
  }
}
function js_mostradiasemana1(chave1,chave2){
  document.form1.ed04_i_diasemana.value = chave1;
  document.form1.ed32_i_codigo.value = chave2;
  db_iframe_diasemana.hide();
}
function js_pesquisa(){
 js_OpenJanelaIframe('top.corpo','db_iframe_dialetivo','func_dialetivo.php?funcao_js=parent.js_preenchepesquisa|ed04_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_dialetivo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>