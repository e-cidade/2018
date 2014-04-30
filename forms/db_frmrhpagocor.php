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
$clrhpagocor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh59_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Trh58_seq?>">
       <?=@$Lrh58_seq?>
    </td>
    <td> 
<?
db_input('rh58_seq',6,$Irh58_seq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh58_tipoocor?>">
       <?
       db_ancora(@$Lrh58_tipoocor,"js_pesquisarh58_tipoocor(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('rh58_tipoocor',6,$Irh58_tipoocor,true,'text',$db_opcao," onchange='js_pesquisarh58_tipoocor(false);'")
?>
       <?
db_input('rh59_descr',40,$Irh59_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh58_valor?>">
       <?=@$Lrh58_valor?>
    </td>
    <td> 
<?
db_input('rh58_valor',15,$Irh58_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh58_obs?>">
       <?=@$Lrh58_obs?>
    </td>
    <td> 
<?
db_textarea('rh58_obs',0,0,$Irh58_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Trh58_data?>">
       <?=@$Lrh58_data?>
    </td>
    <td> 
<?
db_inputdata('rh58_data',@$rh58_data_dia,@$rh58_data_mes,@$rh58_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisarh58_tipoocor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?funcao_js=parent.js_mostrarhpagtipoocor1|rh59_codigo|rh59_descr','Pesquisa',true);
  }else{
     if(document.form1.rh58_tipoocor.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?pesquisa_chave='+document.form1.rh58_tipoocor.value+'&funcao_js=parent.js_mostrarhpagtipoocor','Pesquisa',false);
     }else{
       document.form1.rh59_descr.value = ''; 
     }
  }
}
function js_mostrarhpagtipoocor(chave,erro){
  document.form1.rh59_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh58_tipoocor.focus(); 
    document.form1.rh58_tipoocor.value = ''; 
  }
}
function js_mostrarhpagtipoocor1(chave1,chave2){
  document.form1.rh58_tipoocor.value = chave1;
  document.form1.rh59_descr.value = chave2;
  db_iframe_rhpagtipoocor.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpagocor','func_rhpagocor.php?funcao_js=parent.js_preenchepesquisa|rh58_seq','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhpagocor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>