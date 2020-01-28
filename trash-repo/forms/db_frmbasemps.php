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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clbasemps->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed31_i_codigo");
$clrotulo->label("ed11_i_codigo");
$clrotulo->label("ed12_i_codigo");
$db_botao1 = false;

if (isset($opcao) && $opcao == "alterar" && $discglob != 0) {

  $db_opcao1       = 3;
  $db_opcao2       = 1;
  $db_opcao        = 2;
  $db_botao1       = true;
  $ed34_c_condicao = $ed34_c_condicao == "OPCIONAL" ? "OP" : "OB";
} else if (isset($opcao) && $opcao == "alterar" && $discglob == 0) {

  $db_opcao1       = 3;
  $db_opcao2       = 1;
  $db_opcao        = 2;
  $db_botao1       = true;
  $ed34_c_condicao = $ed34_c_condicao == "OPCIONAL" ? "OP" : "OB";
} else if (isset($opcao) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {

  $db_opcao1 = 3;
  $db_opcao2 = 3;
  $db_botao1 = true;
  $db_opcao  = 3;
  $ed34_c_condicao = $ed34_c_condicao == "OPCIONAL" ? "OP" : "OB";
} else {

  if (isset($alterar)) {

    $db_opcao  = 2;
    $db_botao1 = true;
  } else {
    $db_opcao = 1;
  }

  $db_opcao1 = 1;
  $db_opcao2 = 1;
}
$sql1 = "SELECT ed34_i_disciplina as discjacad
           FROM basemps
          WHERE ed34_i_base = $ed34_i_base AND ed34_i_serie = $ed34_i_serie
       ";
$result1 = db_query($sql1);
$linhas1 = pg_num_rows($result1);

if ($linhas1 > 0) {

  $sep      = "";
  $disc_cad = "";
  for ($c = 0; $c < $linhas1; $c++) {

    db_fieldsmemory($result1,$c);
    $disc_cad .= $sep.$discjacad;
    $sep       = ",";
  }
} else {
  $disc_cad = 0;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <tr>
  <td nowrap title="<?=@$Ted34_i_serie?>">
   <?db_ancora(@$Led34_i_serie,"",3);?>
  </td>
  <td>
   <?db_input('ed34_i_serie',15,$Ied34_i_serie,true,'text',3,"")?>
   <?db_input('ed11_c_descr',40,@$Ied11_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted34_i_disciplina?>">
   <?db_ancora(@$Led34_i_disciplina,"js_pesquisaed34_i_disciplina(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed34_i_disciplina',15,$Ied34_i_disciplina,true,'text',$db_opcao1," onchange='js_pesquisaed34_i_disciplina(false);'")?>
   <?db_input('ed232_c_descr',40,@$Ied232_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted34_i_qtdperiodo?>">
   <?=@$Led34_i_qtdperiodo?>
  </td>
  <td>
   <?db_input('ed34_i_qtdperiodo',10,$Ied34_i_qtdperiodo,true,'text',$db_opcao2,"")?>
   <?//=@$Led34_i_chtotal?>
   <?db_input('ed34_i_chtotal',10,$Ied34_i_chtotal,true,'text',$db_opcao," style=\"visibility:hidden\"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted34_c_condicao?>">
   <?=@$Led34_c_condicao?>
  </td>
  <td>
   <?
   if(isset($ed34_i_qtdperiodo) && $ed34_i_qtdperiodo>0 && $discglob!=0){
    $x = array('OB'=>'OBRIGATÓRIA');
   }else{
    $x = array('OB'=>'OBRIGATÓRIA','OP'=>'OPCIONAL');
   }
   db_select('ed34_c_condicao',$x,true,$db_opcao,"onchange='js_lancarHistorico(this.value)'");
   ?>
  </td>
 </tr>
 <tr id="trLancarHistorico" style="display:
     <?php echo (isset($ed34_c_condicao) && $ed34_c_condicao != 'OB') ? 'table-row' : 'none'; ?>">
   <td nowrap title="<?=@$Ted34_lancarhistorico?>">
     <?=@$Led34_lancarhistorico?>
   </td>
   <td>
     <?php
       $aOpcoes = array('f'=>'NÃO', 't'=>'SIM');
       db_select('ed34_lancarhistorico',$aOpcoes,true,$db_opcao,"", 'ed34_lancarhistorico');
     ?>
   </td>
 </tr>
</table>
<input name="ed34_i_codigo" type="hidden" value="<?=@$ed34_i_codigo?>">
<input name="ed34_i_base" type="hidden" value="<?=@$ed34_i_base?>">
<input name="curso" type="hidden" value="<?=$curso?>">
<input name="discglob" type="hidden" value="<?=$discglob?>">
<input name="qtdper" type="hidden" value="<?=$qtdper?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit"
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?>>
<input name="ordenar" type="button" value="Ordenar Disciplinas" onclick="js_abrir();">
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> ><br>


<table width="90%">
 <tr>
  <td valign="top">
  <?
   $campos  = "ed34_i_codigo, ed34_i_base, ed31_c_descr, ed34_i_serie, ed11_c_descr, ed34_i_disciplina, ed232_c_descr";
   $campos .= ", ed34_i_qtdperiodo, ed34_lancarhistorico, ed34_i_chtotal, ed293_descr as ed232_areaconhecimento";
   $campos .= ", case when ed34_c_condicao='OB' then 'OBRIGATÒRIA' else 'OPCIONAL' end as ed34_c_condicao";
   $chavepri= array("ed34_i_codigo"        => @$ed34_i_codigo,
                    "ed31_c_descr"         => @$ed31_c_descr,
                    "ed34_i_disciplina"    => @$ed34_i_disciplina,
                    "ed232_c_descr"        => @$ed232_c_descr,
                    "ed34_i_serie"         => @$ed34_i_serie,
                    "ed11_c_descr"         => @$ed11_c_descr,
                    "ed34_i_qtdperiodo"    => @$ed34_i_qtdperiodo,
                    "ed34_i_chtotal"       => @$ed34_i_chtotal,
                    "ed34_c_condicao"      => @$ed34_c_condicao,
                    "ed34_lancarhistorico" => @$ed34_lancarhistorico);

   $cliframe_alterar_excluir->chavepri      = $chavepri;
   $sWhere = " ed34_i_base = $ed34_i_base AND ed34_i_serie = $ed34_i_serie";
   @$cliframe_alterar_excluir->sql          = $clbasemps->sql_query_areaconhecimento("", $campos, "ed34_i_ordenacao", $sWhere);
   $cliframe_alterar_excluir->campos        = "ed232_c_descr,ed34_i_qtdperiodo,ed34_c_condicao,ed232_areaconhecimento";
   $cliframe_alterar_excluir->labels        = "ed34_i_disciplina,ed34_i_qtdperiodo,ed34_c_condicao,ed293_descr";
   $cliframe_alterar_excluir->legenda       = "Registros";
   $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec    = "#DEB887";
   $cliframe_alterar_excluir->textocorpo    = "#444444";
   $cliframe_alterar_excluir->fundocabec    = "#444444";
   $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
   $cliframe_alterar_excluir->iframe_height = "160";
   $cliframe_alterar_excluir->iframe_width  = "100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario    = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script>
function js_lancarHistorico(sMatricula) {

  document.getElementById('trLancarHistorico').style.display = 'none';
  document.getElementById('ed34_lancarhistorico').value      = 'f';

  if (sMatricula == 'OP') {
    document.getElementById('trLancarHistorico').style.display = 'table-row';
  }
}
function js_pesquisaed34_i_disciplina(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?disciplinas=<?=$disc_cad?>&curso=<?=$curso?>&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas',true);
 }else{
  if(document.form1.ed34_i_disciplina.value != ''){
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplina.php?disciplinas=<?=$disc_cad?>&curso=<?=$curso?>&pesquisa_chave='+document.form1.ed34_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
  }else{
   document.form1.ed232_c_descr.value = '';
  }
 }
}
function js_mostradisciplina(chave,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed34_i_disciplina.focus();
  document.form1.ed34_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2){
 document.form1.ed34_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 if(chave1==<?=$discglob?>){
  document.form1.ed34_i_qtdperiodo.value = <?=$qtdper?>;
 }else{
  document.form1.ed34_i_qtdperiodo.value = 0;
 }
 db_iframe_disciplina.hide();
}
function js_abrir(){
  js_OpenJanelaIframe('','db_iframe_ordenar','edu1_baseordenardisciplina001.php?base='+document.form1.ed34_i_base.value+'&serie='+document.form1.ed34_i_serie.value+'&curso='+document.form1.curso.value+'&qtdper='+document.form1.qtdper.value+'&discglob='+document.form1.discglob.value+'&ed11_c_descr='+document.form1.ed11_c_descr.value+'&ed31_c_descr=<?=$ed31_c_descr?>','Ordenar Disciplinas ',true,60,400,400,230);
}
</script>