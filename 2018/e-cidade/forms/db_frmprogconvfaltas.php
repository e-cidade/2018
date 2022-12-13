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
$clprogconvfaltas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed127_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted128_i_codigo?>">
       <?=@$Led128_i_codigo?>
    </td>
    <td> 
<?
db_input('ed128_i_codigo',10,$Ied128_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted128_i_progconvres?>">
       <?
       db_ancora(@$Led128_i_progconvres,"js_pesquisaed128_i_progconvres(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed128_i_progconvres',10,$Ied128_i_progconvres,true,'text',$db_opcao," onchange='js_pesquisaed128_i_progconvres(false);'")
?>
       <?
db_input('ed127_i_codigo',10,$Ied127_i_codigo,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted128_c_abonada?>">
       <?=@$Led128_c_abonada?>
    </td>
    <td> 
<?
db_input('ed128_c_abonada',1,$Ied128_c_abonada,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted128_t_obs?>">
       <?=@$Led128_t_obs?>
    </td>
    <td> 
<?
db_textarea('ed128_t_obs',0,0,$Ied128_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted128_d_data?>">
       <?=@$Led128_d_data?>
    </td>
    <td> 
<?
db_inputdata('ed128_d_data',@$ed128_d_data_dia,@$ed128_d_data_mes,@$ed128_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted128_c_numfono?>">
       <?=@$Led128_c_numfono?>
    </td>
    <td> 
<?
db_input('ed128_c_numfono',20,$Ied128_c_numfono,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed128_i_progconvres(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_progconvocacaores','func_progconvocacaores.php?funcao_js=parent.js_mostraprogconvocacaores1|ed127_i_codigo|ed127_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.ed128_i_progconvres.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_progconvocacaores','func_progconvocacaores.php?pesquisa_chave='+document.form1.ed128_i_progconvres.value+'&funcao_js=parent.js_mostraprogconvocacaores','Pesquisa',false);
     }else{
       document.form1.ed127_i_codigo.value = ''; 
     }
  }
}
function js_mostraprogconvocacaores(chave,erro){
  document.form1.ed127_i_codigo.value = chave; 
  if(erro==true){ 
    document.form1.ed128_i_progconvres.focus(); 
    document.form1.ed128_i_progconvres.value = ''; 
  }
}
function js_mostraprogconvocacaores1(chave1,chave2){
  document.form1.ed128_i_progconvres.value = chave1;
  document.form1.ed127_i_codigo.value = chave2;
  db_iframe_progconvocacaores.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_progconvfaltas','func_progconvfaltas.php?funcao_js=parent.js_preenchepesquisa|ed128_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_progconvfaltas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>