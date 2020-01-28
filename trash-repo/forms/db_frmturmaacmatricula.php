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

//MODULO: Escola
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clturmaacmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed268_i_numvagas");
$clrotulo->label("ed268_i_nummatr");
$clrotulo->label("ed269_aluno");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
$no_vagas = false;
$result4 = $clturmaac->sql_record($clturmaac->sql_query_file("","ed268_i_numvagas,ed268_i_nummatr","","ed268_i_codigo = $ed269_i_turmaac"));
if($clturmaac->numrows>0){
 db_fieldsmemory($result4,0);
 if($ed268_i_nummatr>=$ed268_i_numvagas && $db_opcao==1){
  $db_botao = false;
  $no_vagas = true;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted269_i_codigo?>">
   <?=@$Led269_i_codigo?>
  </td>
  <td>
   <?db_input('ed269_i_codigo',10,$Ied269_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted269_i_turmaac?>">
   <?db_ancora(@$Led269_i_turmaac,"",3);?>
  </td>
  <td>
   <?db_input('ed269_i_turmaac',10,$Ied269_i_turmaac,true,'text',3,"")?>
   <?db_input('ed268_c_descr',50,@$Ied268_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td>
   <?=@$Led268_i_numvagas?>
  </td>
  <td>
   <?db_input('ed268_i_numvagas',10,$Ied268_i_numvagas,true,'text',3,"")?>
   <?=@$Led268_i_nummatr?>
   <?db_input('ed268_i_nummatr',10,$Ied268_i_nummatr,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted269_aluno?>">
   <?db_ancora("<b>Código do Aluno:</b>", "js_pesquisaed269_i_matricula(true);", $db_opcao);?>
  </td>
  <td>
    <?db_input('ed269_aluno',10,$Ied269_aluno,true,'text',$db_opcao," onchange='js_pesquisaed269_i_matricula(false);'")?>
    <?db_input('ed47_v_nome',50,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width='100%'>
 <tr>
  <td valign="top">
  <?
   $campos1 = "distinct turmaacmatricula.ed269_aluno,
               turmaacmatricula.ed269_i_codigo,
               turmaacmatricula.ed269_i_turmaac,
               turmaacmatricula.ed269_d_data,
               turmaac.ed268_c_descr,
               aluno.ed47_v_nome
              ";
   $chavepri= array("ed269_i_codigo"=>@$ed269_i_codigo,"ed269_i_turmaac"=>@$ed269_i_turmaac,
                    "ed269_aluno"=>@$ed269_aluno, "ed268_c_descr"=>@$ed268_c_descr,"ed47_v_nome"=>@$ed47_v_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clturmaacmatricula->sql_query("",$campos1,"ed47_v_nome"," ed269_i_turmaac = $ed269_i_turmaac");

   $cliframe_alterar_excluir->campos  ="ed269_aluno, ed47_v_nome, ed269_d_data, ed268_c_descr";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->opcoes = 3;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisaed269_i_matricula(mostra){

  if (mostra) {

    js_OpenJanelaIframe('',
                        'db_iframe_matricula',
                        'func_matriculaturmaac.php?ed268_i_tipoatend=<?=$ed268_i_tipoatend?>'
                                                +'&calendario=<?=$ed268_i_calendario?>'
                                                +'&codigo_turma='+$F('ed269_i_turmaac')
                                                +'&funcao_js=parent.js_mostramatricula1|ed47_i_codigo|ed47_v_nome',
                        'Pesquisa',
                        true);
  } else {

    if(document.form1.ed269_aluno.value != ''){

      js_OpenJanelaIframe('',
                          'db_iframe_matricula',
                          'func_matriculaturmaac.php?ed268_i_tipoatend=<?=$ed268_i_tipoatend?>'
                                                  +'&calendario=<?=$ed268_i_calendario?>'
                                                  +'&pesquisa_chave='+document.form1.ed269_aluno.value
                                                  +'&funcao_js=parent.js_mostramatricula',
                          'Pesquisa',
                          false);
    }else{
      document.form1.ed47_v_nome.value = '';
    }
  }
}

function js_mostramatricula(nome, codigo, situacao, erro, matricula) {

  document.form1.ed47_v_nome.value = nome;
  document.form1.ed269_aluno.value = codigo;
  if (erro) {

    document.form1.ed269_aluno.focus();
    document.form1.ed269_aluno.value = '';
    document.form1.ed269_aluno.value   = '';
  }
}
function js_mostramatricula1(chave1,chave2, matricula) {

  document.form1.ed269_aluno.value = chave1;
  document.form1.ed269_aluno.value   = chave1;
  document.form1.ed47_v_nome.value   = chave2;
  db_iframe_matricula.hide();
}
<?if($no_vagas==true){?>
 alert("Turma sem vagas disponíveis!");
<?}?>
</script>