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
$clmural->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed02_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted20_i_codigo?>">
       <?=@$Led20_i_codigo?>
    </td>
    <td> 
<?
db_input('ed20_i_codigo',5,$Ied20_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted20_i_escola?>">
       <?
       db_ancora(@$Led20_i_escola,"js_pesquisaed20_i_escola(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed20_i_escola',5,$Ied20_i_escola,true,'text',$db_opcao," onchange='js_pesquisaed20_i_escola(false);'")
?>
       <?
db_input('z01_nome',40,$z01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted20_c_tipo?>">
       <?=@$Led20_c_tipo?>
    </td>
    <td> 
<?
$x = array('AGENDA DE PROVAS'=>'AGENDA DE PROVAS','AVISO'=>'AVISO','EVENTO'=>'EVENTO');
db_select('ed20_c_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted20_d_data?>">
       <?=@$Led20_d_data?>
    </td>
    <td> 
<?
db_inputdata('ed20_d_data',@$ed20_d_data_dia,@$ed20_d_data_mes,@$ed20_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted20_c_assunto?>">
       <?=@$Led20_c_assunto?>
    </td>
    <td> 
<?
db_input('ed20_c_assunto',50,$Ied20_c_assunto,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted20_t_descr?>">
       <?=@$Led20_t_descr?>
    </td>
    <td> 
<?
db_textarea('ed20_t_descr',3,40,$Ied20_t_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed20_i_escola(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?funcao_js=parent.js_mostraescolas1|ed02_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed20_i_escola.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_escolas','func_escolas.php?pesquisa_chave='+document.form1.ed20_i_escola.value+'&funcao_js=parent.js_mostraescolas','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraescolas(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){ 
    document.form1.ed20_i_escola.focus(); 
    document.form1.ed20_i_escola.value = ''; 
  }
}
function js_mostraescolas1(chave1,chave2){
  document.form1.ed20_i_escola.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_escolas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_mural','func_mural.php?funcao_js=parent.js_preenchepesquisa|ed20_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_mural.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>