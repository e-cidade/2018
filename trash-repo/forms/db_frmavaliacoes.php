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
$clavaliacoes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed27_c_nome");
$clrotulo->label("ed23_c_nome");
$clrotulo->label("ed05_c_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted13_i_codigo?>">
       <?=@$Led13_i_codigo?>
    </td>
    <td> 
<?
db_input('ed13_i_codigo',10,$Ied13_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_i_disciplinas?>">
       <?
       db_ancora(@$Led13_i_disciplinas,"js_pesquisaed13_i_disciplinas(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed13_i_disciplinas',5,$Ied13_i_disciplinas,true,'text',$db_opcao," onchange='js_pesquisaed13_i_disciplinas(false);'")
?>
       <?
db_input('ed27_c_nome',50,$Ied27_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_i_periodo?>">
       <?
       db_ancora(@$Led13_i_periodo,"js_pesquisaed13_i_periodo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed13_i_periodo',5,$Ied13_i_periodo,true,'text',$db_opcao," onchange='js_pesquisaed13_i_periodo(false);'")
?>
       <?
db_input('ed23_c_nome',30,$Ied23_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_i_turma?>">
       <?
       db_ancora(@$Led13_i_turma,"js_pesquisaed13_i_turma(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed13_i_turma',10,$Ied13_i_turma,true,'text',$db_opcao," onchange='js_pesquisaed13_i_turma(false);'")
?>
       <?
db_input('ed05_c_nome',40,$Ied05_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_d_data?>">
       <?=@$Led13_d_data?>
    </td>
    <td> 
<?
db_inputdata('ed13_d_data',@$ed13_d_data_dia,@$ed13_d_data_mes,@$ed13_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_f_valor?>">
       <?=@$Led13_f_valor?>
    </td>
    <td> 
<?
db_input('ed13_f_valor',5,$Ied13_f_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted13_c_descr?>">
       <?=@$Led13_c_descr?>
    </td>
    <td> 
<?
db_input('ed13_c_descr',50,$Ied13_c_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed13_i_disciplinas(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disciplinas','func_disciplinas.php?funcao_js=parent.js_mostradisciplinas1|ed27_i_codigo|ed27_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed13_i_disciplinas.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disciplinas','func_disciplinas.php?pesquisa_chave='+document.form1.ed13_i_disciplinas.value+'&funcao_js=parent.js_mostradisciplinas','Pesquisa',false);
     }else{
       document.form1.ed27_c_nome.value = ''; 
     }
  }
}
function js_mostradisciplinas(chave,erro){
  document.form1.ed27_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed13_i_disciplinas.focus(); 
    document.form1.ed13_i_disciplinas.value = ''; 
  }
}
function js_mostradisciplinas1(chave1,chave2){
  document.form1.ed13_i_disciplinas.value = chave1;
  document.form1.ed27_c_nome.value = chave2;
  db_iframe_disciplinas.hide();
}
function js_pesquisaed13_i_periodo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_periodos','func_periodos.php?funcao_js=parent.js_mostraperiodos1|ed23_i_codigo|ed23_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed13_i_periodo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_periodos','func_periodos.php?pesquisa_chave='+document.form1.ed13_i_periodo.value+'&funcao_js=parent.js_mostraperiodos','Pesquisa',false);
     }else{
       document.form1.ed23_c_nome.value = ''; 
     }
  }
}
function js_mostraperiodos(chave,erro){
  document.form1.ed23_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed13_i_periodo.focus(); 
    document.form1.ed13_i_periodo.value = ''; 
  }
}
function js_mostraperiodos1(chave1,chave2){
  document.form1.ed13_i_periodo.value = chave1;
  document.form1.ed23_c_nome.value = chave2;
  db_iframe_periodos.hide();
}
function js_pesquisaed13_i_turma(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_turmas','func_turmas.php?funcao_js=parent.js_mostraturmas1|ed05_i_codigo|ed05_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed13_i_turma.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_turmas','func_turmas.php?pesquisa_chave='+document.form1.ed13_i_turma.value+'&funcao_js=parent.js_mostraturmas','Pesquisa',false);
     }else{
       document.form1.ed05_c_nome.value = ''; 
     }
  }
}
function js_mostraturmas(chave,erro){
  document.form1.ed05_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed13_i_turma.focus(); 
    document.form1.ed13_i_turma.value = ''; 
  }
}
function js_mostraturmas1(chave1,chave2){
  document.form1.ed13_i_turma.value = chave1;
  document.form1.ed05_c_nome.value = chave2;
  db_iframe_turmas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_avaliacoes','func_avaliacoes.php?funcao_js=parent.js_preenchepesquisa|ed13_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_avaliacoes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>