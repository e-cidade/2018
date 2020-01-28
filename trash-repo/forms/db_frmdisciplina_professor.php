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
$cldisciplina_professor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed12_f_ch");
$clrotulo->label("ed01_i_codigo");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ted12_i_codigo?>">
       <?=@$Led12_i_codigo?>
    </td>
    <td> 
<?
db_input('ed12_i_codigo',5,$Ied12_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted12_i_professores?>">
       <?
       db_ancora(@$Led12_i_professores,"js_pesquisaed12_i_professores(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed12_i_professores',5,$Ied12_i_professores,true,'text',$db_opcao," onchange='js_pesquisaed12_i_professores(false);'")
?>
       <?
db_input('z01_nome',40,$z01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted12_i_disciplina?>">
       <?
       db_ancora(@$Led12_i_disciplina,"js_pesquisaed12_i_disciplina(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('ed12_i_disciplina',5,$Ied12_i_disciplina,true,'text',$db_opcao," onchange='js_pesquisaed12_i_disciplina(false);'")
?>
       <?
db_input('ed27_c_nome',40,$ed27_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted12_f_ch?>">
       <?=@$Led12_f_ch?>
    </td>
    <td> 
<?
db_input('ed12_f_ch',5,$Ied12_f_ch,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted12_f_frequencia?>">
       <?=@$Led12_f_frequencia?>
    </td>
    <td> 
<?
db_input('ed12_f_frequencia',3,$Ied12_f_frequencia,true,'text',$db_opcao,"")
?> <b>%</b>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaed12_i_disciplina(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_disciplina_professor','func_disciplinas.php?funcao_js=parent.js_mostradisciplina_professor1|ed27_i_codigo|ed27_c_nome','Pesquisa',true);
  }else{
     if(document.form1.ed12_i_disciplina.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_disciplina_professor','func_disciplinas.php?pesquisa_chave='+document.form1.ed12_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina_professor','Pesquisa',false);
     }else{
       document.form1.ed27_c_nome.value = '';
     }
  }
}
function js_mostradisciplina_professor(chave,erro){
  document.form1.ed27_c_nome.value = chave;
  if(erro==true){ 
    document.form1.ed12_i_disciplina.focus(); 
    document.form1.ed12_i_disciplina.value = ''; 
  }
}
function js_mostradisciplina_professor1(chave1,chave2){
  document.form1.ed12_i_disciplina.value = chave1;
  document.form1.ed27_c_nome.value = chave2;
  db_iframe_disciplina_professor.hide();
}
function js_pesquisaed12_i_professores(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_professores','func_professores.php?funcao_js=parent.js_mostraprofessores1|ed01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.ed12_i_professores.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_professores','func_professores.php?pesquisa_chave='+document.form1.ed12_i_professores.value+'&funcao_js=parent.js_mostraprofessores','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraprofessores(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){ 
    document.form1.ed12_i_professores.focus(); 
    document.form1.ed12_i_professores.value = ''; 
  }
}
function js_mostraprofessores1(chave1,chave2){
  document.form1.ed12_i_professores.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_professores.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_disciplina_professor','func_disciplina_professor.php?funcao_js=parent.js_preenchepesquisa|ed12_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_disciplina_professor.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>