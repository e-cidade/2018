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
$clchamadas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed27_c_nome");
$clrotulo->label("ed09_c_situacao");
?>
<form name="form1" method="post" action="">
<center>
<br><br><br>
<table border="0">
  <tr>
    <td nowrap title="Turma">
       <?
       db_ancora("<b>Turma:</b>","js_pesquisaed05_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed05_i_codigo',5,$ed05_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed05_i_codigo(false);'")
?>
       <?
db_input('ed05_c_nome',40,$ed05_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted22_i_disciplina?>">
       <?
       db_ancora(@$Led22_i_disciplina,"js_pesquisaed22_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed22_i_disciplina',5,$Ied22_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed22_i_disciplina(false);'")
?>
       <?
db_input('ed27_c_nome',40,$Ied27_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
    <tr>
    <td nowrap title="<?=@$Ted22_d_data?>">
       <?=@$Led22_d_data?>
    </td>
    <td>
<?
db_inputdata('ed22_d_data',@$ed22_d_data_dia,@$ed22_d_data_mes,@$ed22_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
   <td colspan="2"><input type="button" value="Processar" onclick="js_valida()"></td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_valida(){
 if(document.form1.ed05_i_codigo.value==""){
  alert("Escolha a Série!");
  document.form1.ed05_i_codigo.focus();
  return false;
 }
 if(document.form1.ed22_i_disciplina.value==""){
  alert("Escolha a Disciplina!");
  document.form1.ed22_i_disciplina.focus();
  return false;
 }
 if(document.form1.ed22_d_data_dia.value=="" || document.form1.ed22_d_data_mes.value=="" || document.form1.ed22_d_data_ano.value==""){
  alert("Preencha a Data corretamente!");
  document.form1.ed22_d_data_dia.focus();
  return false;
 }else{
  parent.document.formaba.a2.disabled=false;
  top.corpo.iframe_a2.location.href='edu3_chamadas002.php?turma='+document.form1.ed05_i_codigo.value+'&nometurma='+document.form1.ed05_c_nome.value+'&disciplina='+document.form1.ed22_i_disciplina.value+'&nomedisciplina='+document.form1.ed27_c_nome.value+'&data='+document.form1.ed22_d_data_ano.value+'-'+document.form1.ed22_d_data_mes.value+'-'+document.form1.ed22_d_data_dia.value;
  parent.mo_camada('a2');
 }
}
function js_pesquisaed05_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_turmas','func_turmas.php?funcao_js=parent.js_mostraturma1|ed05_i_codigo|ed05_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed05_i_codigo.valu1e != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_turmas','func_turmas.php?pesquisa_chave='+document.form1.ed05_i_codigo.value+'&funcao_js=parent.js_mostraturma','Pesquisa',false);
     }else{
       document.form1.ed05_c_nome.value = '';
     }
  }
}
function js_mostraturma(chave,erro){
  document.form1.ed05_c_nome.value = chave;
  if(erro==true){
    document.form1.ed05_i_codigo.focus();
    document.form1.ed05_i_codigo.value = '';
  }
}
function js_mostraturma1(chave1,chave2){
  document.form1.ed05_i_codigo.value = chave1;
  document.form1.ed05_c_nome.value = chave2;
  db_iframe_turmas.hide();
}

function js_pesquisaed22_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_disciplinas','func_disciplinas.php?funcao_js=parent.js_mostradisciplinas1|ed27_i_codigo|ed27_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed22_i_disciplina.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_disciplinas','func_disciplinas.php?pesquisa_chave='+document.form1.ed22_i_disciplina.value+'&funcao_js=parent.js_mostradisciplinas','Pesquisa',false);
     }else{
       document.form1.ed27_c_nome.value = ''; 
     }
  }
}
function js_mostradisciplinas(chave,erro){
  document.form1.ed27_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.ed22_i_disciplina.focus(); 
    document.form1.ed22_i_disciplina.value = ''; 
  }
}
function js_mostradisciplinas1(chave1,chave2){
  document.form1.ed22_i_disciplina.value = chave1;
  document.form1.ed27_c_nome.value = chave2;
  db_iframe_disciplinas.hide();
}
function js_pesquisaed22_i_matricula(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?funcao_js=parent.js_mostramatriculas1|ed09_i_codigo|ed09_c_situacao','Pesquisa',true);
  }else{
     if(document.form1.ed22_i_matricula.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_matriculas','func_matriculas.php?pesquisa_chave='+document.form1.ed22_i_matricula.value+'&funcao_js=parent.js_mostramatriculas','Pesquisa',false);
     }else{
       document.form1.ed09_c_situacao.value = ''; 
     }
  }
}
function js_mostramatriculas(chave,erro){
  document.form1.ed09_c_situacao.value = chave; 
  if(erro==true){ 
    document.form1.ed22_i_matricula.focus(); 
    document.form1.ed22_i_matricula.value = ''; 
  }
}
function js_mostramatriculas1(chave1,chave2){
  document.form1.ed22_i_matricula.value = chave1;
  document.form1.ed09_c_situacao.value = chave2;
  db_iframe_matriculas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_chamadas','func_chamadas.php?funcao_js=parent.js_preenchepesquisa|ed22_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_chamadas.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>