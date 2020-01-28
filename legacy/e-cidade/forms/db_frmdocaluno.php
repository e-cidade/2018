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
$cldocaluno->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed02_i_codigo");
if (isset($opcao) && $opcao == "alterar") {
	
  $db_opcao  = 2;
  $db_botao1 = true;
  
} elseif (isset($opcao) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {
	
  $db_botao1 = true;
  $db_opcao  = 3;
 
} else {
	
  if (isset($alterar)) {
  	
    $db_opcao  = 2;
    $db_botao1 = true;
    
  } else {
    $db_opcao = 1;
  }
}
$ed49_i_escola = db_getsession("DB_coddepto");
$result1 = $clescola->sql_record($clescola->sql_query_file("","ed18_c_nome",""," ed18_i_codigo = $ed49_i_escola"));
db_fieldsmemory($result1,0);
if ($ed49_i_aluno != "") {
	
  $sql4 = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $ed49_i_aluno";
  $query4 = pg_query($sql4);
  $linhas4 = pg_num_rows($query4);
  if ($linhas4 == 0) {
    $db_botao = true;
  } else if (db_getsession("DB_coddepto") != pg_result($query4,0,0)) {
    $db_botao = false;
  } else {
    $db_botao = true;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted49_i_codigo?>">
   <?=@$Led49_i_codigo?>
  </td>
  <td>
   <?db_input('ed49_i_codigo',20,$Ied49_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted49_i_escola?>">
   <?db_ancora(@$Led49_i_escola,"",3);
   ?>
  </td>
  <td>
   <?db_input('ed49_i_escola',20,$Ied49_i_escola,true,'text',3,"")?>
   <?db_input('ed18_c_nome',40,@$Ied18_c_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted49_i_aluno?>">
   <?db_ancora(@$Led49_i_aluno,"",3);
   ?>
  </td>
  <td>
   <?db_input('ed49_i_aluno',20,$Ied49_i_aluno,true,'text',3,"")?>
   <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted49_i_documentacao?>">
   <?db_ancora(@$Led49_i_documentacao,"js_pesquisaed49_i_documentacao(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed49_i_documentacao',20,$Ied49_i_documentacao,true,'text',$db_opcao,
              " onchange='js_pesquisaed49_i_documentacao(false);'")
   ?>
   <?db_input('ed02_c_descr',40,@$Ied02_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted49_t_obs?>">
   <?=@$Led49_t_obs?>
  </td>
  <td>
   <?db_textarea('ed49_t_obs',2,50,$Ied49_t_obs,true,'text',$db_opcao,"")?>
  </td>
 </tr>
</table>
<input name="ed49_i_aluno" type="hidden" value="<?=@$ed49_i_aluno?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=(@$db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri                                = array("ed49_i_codigo"=>@$ed49_i_codigo,
                                                    "ed49_i_aluno"=>@$ed49_i_aluno,
                                                    "ed49_i_documentacao"=>@$ed49_i_documentacao,
                                                    "ed02_c_descr"=>@$ed02_c_descr,
                                                    "ed49_t_obs"=>@$ed49_t_obs
                                                   );
   $cliframe_alterar_excluir->chavepri      =$chavepri;
   @$cliframe_alterar_excluir->sql          = $cldocaluno->sql_query("","*","","ed49_i_aluno = $ed49_i_aluno");
   $cliframe_alterar_excluir->campos        = "ed18_c_nome,ed02_c_descr,ed49_t_obs";
   $cliframe_alterar_excluir->legenda       = "Registros";
   $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec    = "#DEB887";
   $cliframe_alterar_excluir->textocorpo    = "#444444";
   $cliframe_alterar_excluir->fundocabec    = "#444444";
   $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
   $cliframe_alterar_excluir->iframe_height = "100";
   $cliframe_alterar_excluir->iframe_width  = "100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   if ($linhas4 == 0) {
     $cliframe_alterar_excluir->opcoes = 1;
   } else if (db_getsession("DB_coddepto") != pg_result($query4,0,0)) {
     $cliframe_alterar_excluir->opcoes = 4;
   }
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_pesquisaed49_i_documentacao(mostra) {
	
 if (mostra == true){
	 
  js_OpenJanelaIframe('',
		              'db_iframe_documentacao',
		              'func_documentacao.php?documentos=<?=$doc_cad?>'+
		              '&funcao_js=parent.js_mostradocumentacao1|ed02_i_codigo|ed02_c_descr',
		              'Pesquisa Documentação',
		              true
		             );
 } else {
	 
  if (document.form1.ed49_i_documentacao.value != '') {
	  
   js_OpenJanelaIframe('',
		               'db_iframe_documentacao',
		               'func_documentacao.php?documentos=<?=$doc_cad?>'+
		               '&pesquisa_chave='+document.form1.ed49_i_documentacao.value+
		               '&funcao_js=parent.js_mostradocumentacao',
		               'Pesquisa',
		               false
		              );
   
  } else {
    document.form1.ed02_c_descr.value = '';
  }
 }
}

function js_mostradocumentacao(chave,erro) {
	
 document.form1.ed02_c_descr.value = chave;
 if (erro == true) {
	 
  document.form1.ed49_i_documentacao.focus();
  document.form1.ed49_i_documentacao.value = '';
  
 }
}

function js_mostradocumentacao1(chave1,chave2) {
	
  document.form1.ed49_i_documentacao.value = chave1;
  document.form1.ed02_c_descr.value = chave2;
  db_iframe_documentacao.hide();
  
}
</script>