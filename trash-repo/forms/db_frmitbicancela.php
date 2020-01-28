<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: itbi
$clitbicancela->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("it01_guia");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tit16_guia?>">
       <?
       db_ancora(@$Lit16_guia,"js_pesquisait16_guia(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('it16_guia',10,$Iit16_guia,true,'text',$db_opcao," onchange='js_pesquisait16_guia(false);'")
?>
       <?
//db_input('it01_guia',10,$Iit01_guia,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit16_data?>">
       <?=@$Lit16_data?>
    </td>
    <td> 
<?
if($db_opcao == 1){
  $it16_data_dia = date("d",db_getsession("DB_datausu"));
  $it16_data_mes = date("m",db_getsession("DB_datausu"));
  $it16_data_ano = date("Y",db_getsession("DB_datausu"));
} 
db_inputdata('it16_data',@$it16_data_dia,@$it16_data_mes,@$it16_data_ano,true,'text',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tit16_obs?>">
       <?=@$Lit16_obs?>
    </td>
    <td> 
<?
db_textarea('it16_obs',3,50,$Iit16_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Cancelar":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</form>
<script>
function js_pesquisait16_guia(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbinaocancelado.php?funcao_js=parent.js_mostraitbi1|it01_guia|it01_guia&lcancelaitbi=cancela','Pesquisa',true);
  }else{
     if(document.form1.it16_guia.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_itbi','func_itbinaocancelado.php?pesquisa_chave='+document.form1.it16_guia.value+'&funcao_js=parent.js_mostraitbi&lcancelaitbi=cancela','Pesquisa',false);
     }else{
       document.form1.it01_guia.value = ''; 
     }
  }
}
function js_mostraitbi(chave,erro){
  document.form1.it16_guia.value = chave; 
  if(erro==true){ 
    document.form1.it16_guia.focus(); 
    document.form1.it16_guia.value = ''; 
  }
}
function js_mostraitbi1(chave1,chave2){
  document.form1.it16_guia.value = chave1;
  db_iframe_itbi.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itbicancela','func_itbicancela.php?funcao_js=parent.js_preenchepesquisa|it16_guia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itbicancela.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
js_pesquisait16_guia(true)
</script>