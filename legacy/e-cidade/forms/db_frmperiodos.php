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
$clperiodos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed28_i_ano");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted23_i_codigo?>">
       <?=@$Led23_i_codigo?>
    </td>
    <td> 
<?
db_input('ed23_i_codigo',5,$Ied23_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted23_i_anoletivo?>">
       <?
       db_ancora(@$Led23_i_anoletivo,"js_pesquisaed23_i_anoletivo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed23_i_anoletivo',4,$Ied23_i_anoletivo,true,'text',$db_opcao," onchange='js_pesquisaed23_i_anoletivo(false);'")
?>
       <?
db_input('ed28_i_ano',4,$Ied28_i_ano,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted23_c_nome?>">
       <?=@$Led23_c_nome?>
    </td>
    <td> 
<?
db_input('ed23_c_nome',30,$Ied23_c_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted23_d_inicio?>">
       <?=@$Led23_d_inicio?>
    </td>
    <td> 
<?
db_inputdata('ed23_d_inicio',@$ed23_d_inicio_dia,@$ed23_d_inicio_mes,@$ed23_d_inicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted23_d_fim?>">
       <?=@$Led23_d_fim?>
    </td>
    <td> 
<?
db_inputdata('ed23_d_fim',@$ed23_d_fim_dia,@$ed23_d_fim_mes,@$ed23_d_fim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted23_c_encerrado?>">
       <?=@$Led23_c_encerrado?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Não');
db_select('ed23_c_encerrado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed23_i_anoletivo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_anoletivo','func_anoletivo.php?funcao_js=parent.js_mostraanoletivo1|ed28_i_codigo|ed28_i_ano','Pesquisa',true);
  }else{
     if(document.form1.ed23_i_anoletivo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_anoletivo','func_anoletivo.php?pesquisa_chave='+document.form1.ed23_i_anoletivo.value+'&funcao_js=parent.js_mostraanoletivo','Pesquisa',false);
     }else{
       document.form1.ed28_i_ano.value = ''; 
     }
  }
}
function js_mostraanoletivo(chave,erro){
  document.form1.ed28_i_ano.value = chave; 
  if(erro==true){ 
    document.form1.ed23_i_anoletivo.focus(); 
    document.form1.ed23_i_anoletivo.value = ''; 
  }
}
function js_mostraanoletivo1(chave1,chave2){
  document.form1.ed23_i_anoletivo.value = chave1;
  document.form1.ed28_i_ano.value = chave2;
  db_iframe_anoletivo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_periodos','func_periodos.php?funcao_js=parent.js_preenchepesquisa|ed23_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_periodos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>