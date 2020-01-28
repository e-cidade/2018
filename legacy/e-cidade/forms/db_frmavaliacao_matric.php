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
$clavaliacao_matric->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed13_d_data");
$clrotulo->label("ed09_c_situacao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted29_i_codigo?>">
       <?=@$Led29_i_codigo?>
    </td>
    <td> 
<?
db_input('ed29_i_codigo',5,$Ied29_i_codigo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted29_i_avaliacao?>">
       <?
       db_ancora(@$Led29_i_avaliacao,"js_pesquisaed29_i_avaliacao(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed29_i_avaliacao',5,$Ied29_i_avaliacao,true,'text',$db_opcao," onchange='js_pesquisaed29_i_avaliacao(false);'")
?>
       <?
db_input('ed13_d_data',10,$Ied13_d_data,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted29_i_matricula?>">
       <?
       db_ancora(@$Led29_i_matricula,"js_pesquisaed29_i_matricula(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed29_i_matricula',5,$Ied29_i_matricula,true,'text',$db_opcao," onchange='js_pesquisaed29_i_matricula(false);'")
?>
       <?
db_input('ed09_c_situacao',20,$Ied09_c_situacao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted29_f_nota?>">
       <?=@$Led29_f_nota?>
    </td>
    <td> 
<?
db_input('ed29_f_nota',5,$Ied29_f_nota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted29_l_presente?>">
       <?=@$Led29_l_presente?>
    </td>
    <td> 
<?
$x = array('t'=>'Sim','f'=>'Não');
db_select('ed29_l_presente',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted29_c_descr?>">
       <?=@$Led29_c_descr?>
    </td>
    <td> 
<?
db_input('ed29_c_descr',50,$Ied29_c_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed29_i_avaliacao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_avaliacoes','func_avaliacoes.php?funcao_js=parent.js_mostraavaliacoes1|ed13_i_codigo|ed13_d_data','Pesquisa',true);
  }else{
     if(document.form1.ed29_i_avaliacao.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_avaliacoes','func_avaliacoes.php?pesquisa_chave='+document.form1.ed29_i_avaliacao.value+'&funcao_js=parent.js_mostraavaliacoes','Pesquisa',false);
     }else{
       document.form1.ed13_d_data.value = ''; 
     }
  }
}
function js_mostraavaliacoes(chave,erro){
  document.form1.ed13_d_data.value = chave; 
  if(erro==true){ 
    document.form1.ed29_i_avaliacao.focus(); 
    document.form1.ed29_i_avaliacao.value = ''; 
  }
}
function js_mostraavaliacoes1(chave1,chave2){
  document.form1.ed29_i_avaliacao.value = chave1;
  document.form1.ed13_d_data.value = chave2;
  db_iframe_avaliacoes.hide();
}
function js_pesquisaed29_i_matricula(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?funcao_js=parent.js_mostramatriculas1|ed09_i_codigo|ed09_c_situacao','Pesquisa',true);
  }else{
     if(document.form1.ed29_i_matricula.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?pesquisa_chave='+document.form1.ed29_i_matricula.value+'&funcao_js=parent.js_mostramatriculas','Pesquisa',false);
     }else{
       document.form1.ed09_c_situacao.value = ''; 
     }
  }
}
function js_mostramatriculas(chave,erro){
  document.form1.ed09_c_situacao.value = chave; 
  if(erro==true){ 
    document.form1.ed29_i_matricula.focus(); 
    document.form1.ed29_i_matricula.value = ''; 
  }
}
function js_mostramatriculas1(chave1,chave2){
  document.form1.ed29_i_matricula.value = chave1;
  document.form1.ed09_c_situacao.value = chave2;
  db_iframe_matriculas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_avaliacao_matric','func_avaliacao_matric.php?funcao_js=parent.js_preenchepesquisa|ed29_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_avaliacao_matric.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>