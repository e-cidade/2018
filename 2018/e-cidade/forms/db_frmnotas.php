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

//MODULO: educa��o
$clnotas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed09_c_situacao");
?>
<form name="form1" method="post" action="">
<center>
<br><br><br>
<fieldset style="width:450"><legend><b>Notas Por Avalia��o</b></legend>
<table border="0">
  <tr>
    <td nowrap title="Avalia��o">
       <?
       db_ancora("<b>Avalia��o:</b>","js_pesquisaed13_i_codigo(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('ed13_i_codigo',5,$ed13_i_codigo,true,'text',$db_opcao," onchange='js_pesquisaed13_i_codigo(false);'")
?>
       <?
db_input('ed13_c_descr',40,$ed13_c_descr,true,'text',3,'')
       ?>
    </td>
  <tr>
   <td colspan="2"><input type="button" value="Processar" onclick="js_valida()"></td>
  </tr>
  </table>
</fieldset>

  </center>
</form>
<script>
function js_valida(){
 if(document.form1.ed13_i_codigo.value==""){
  alert("Escolha a Avalia��o!");
  document.form1.ed13_i_codigo.focus();
  return false;
 }else{
  parent.document.formaba.a2.disabled=false;
  top.corpo.iframe_a2.location.href='edu1_notas002.php?ed13_i_codigo='+document.form1.ed13_i_codigo.value;
  parent.mo_camada('a2');
 }
}

function js_valida2(){
 if(document.form1.ed27_i_codigo.value==""){
  alert("Escolha a Disciplina!");
  document.form1.ed27_i_codigo.focus();
  return false;
 }
 if(document.form1.ed23_i_codigo.value==""){
  alert("Escolha o Per�odo!");
  document.form1.ed23_i_codigo.focus();
  return false;
 }
 if(document.form1.ed05_i_codigo.value==""){
  alert("Escolha a Turma!");
  document.form1.ed05_i_codigo.focus();
  return false;
 }else{
  parent.document.formaba.a2.disabled=false;
  top.corpo.iframe_a2.location.href='edu1_notas003.php?ed27_i_codigo='+document.form1.ed27_i_codigo.value+"&ed23_i_codigo="+document.form1.ed23_i_codigo.value+"&ed05_i_codigo="+document.form1.ed05_i_codigo.value;
  parent.mo_camada('a2');
 }
}
//avalia��es
function js_pesquisaed13_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_avaliacoes','func_avaliacoes.php?funcao_js=parent.js_mostraavaliacoes1|ed13_i_codigo|ed13_c_descr','Pesquisa',true);
  }else{
     if(document.form1.ed13_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_avaliacoes','func_avaliacoes.php?pesquisa_chave='+document.form1.ed13_i_codigo.value+'&funcao_js=parent.js_mostraavaliacoes','Pesquisa',false);
     }else{
       document.form1.ed13_c_descr.value = '';
     }
  }
}
function js_mostraavaliacoes(chave,erro){
  document.form1.ed13_c_descr.value = chave;
  if(erro==true){
    document.form1.ed13_i_codigo.focus();
    document.form1.ed13_i_codigo.value = '';
  }
}
function js_mostraavaliacoes1(chave1,chave2){
  document.form1.ed13_i_codigo.value = chave1;
  document.form1.ed13_c_descr.value = chave2;
  db_iframe_avaliacoes.hide();
}
//disciplina
function js_pesquisaed27_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_disciplinas','func_disciplinas.php?funcao_js=parent.js_mostradisciplinas1|ed27_i_codigo|ed27_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed27_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_discplinas','func_disciplinas.php?pesquisa_chave='+document.form1.ed27_i_codigo.value+'&funcao_js=parent.js_mostradisciplinas','Pesquisa',false);
     }else{
       document.form1.ed27_c_nome.value = '';
     }
  }
}
function js_mostradisciplinas(chave,erro){
  document.form1.ed27_c_nome.value = chave;
  if(erro==true){
    document.form1.ed27_i_codigo.focus();
    document.form1.ed27_i_codigo.value = '';
  }
}
function js_mostradisciplinas1(chave1,chave2){
  document.form1.ed27_i_codigo.value = chave1;
  document.form1.ed27_c_nome.value = chave2;
  db_iframe_disciplinas.hide();
}
//periodo
function js_pesquisaed23_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_periodos','func_periodos.php?funcao_js=parent.js_mostraperiodos1|ed23_i_codigo|ed23_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed23_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_periodos','func_periodos.php?pesquisa_chave='+document.form1.ed23_i_codigo.value+'&funcao_js=parent.js_mostraperiodos','Pesquisa',false);
     }else{
       document.form1.ed23_c_nome.value = '';
     }
  }
}
function js_mostraperiodos(chave,erro){
  document.form1.ed23_c_nome.value = chave;
  if(erro==true){
    document.form1.ed23_i_codigo.focus();
    document.form1.ed23_i_codigo.value = '';
  }
}
function js_mostraperiodos1(chave1,chave2){
  document.form1.ed23_i_codigo.value = chave1;
  document.form1.ed23_c_nome.value = chave2;
  db_iframe_periodos.hide();
}
//turma
function js_pesquisaed05_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_turmas','func_turmas.php?funcao_js=parent.js_mostraturmas1|ed05_i_codigo|ed05_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed05_i_codigo.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_turmas','func_turmas.php?pesquisa_chave='+document.form1.ed05_i_codigo.value+'&funcao_js=parent.js_mostraturmas','Pesquisa',false);
     }else{
       document.form1.ed05_c_nome.value = '';
     }
  }
}
function js_mostraturmas(chave,erro){
  document.form1.ed05_c_nome.value = chave;
  if(erro==true){
    document.form1.ed05_i_codigo.focus();
    document.form1.ed05_i_codigo.value = '';
  }
}
function js_mostraturmas1(chave1,chave2){
  document.form1.ed05_i_codigo.value = chave1;
  document.form1.ed05_c_nome.value = chave2;
  db_iframe_turmas.hide();
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