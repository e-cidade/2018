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
$claprovconselho->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed95_i_codigo");
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("nome");
?>
<form name="form1" method="post" action="">
<center>
<?
$result3 = $cldiariofinal->sql_record($cldiariofinal->sql_query("",
                                                                "ed74_i_diario,ed47_v_nome,ed74_c_resultadofreq ",
                                                                "to_ascii(ed47_v_nome)",
                                                                " ed95_i_regencia = $regencia AND ed74_c_resultadofinal = 'R' AND ed95_c_encerrado = 'N'"));
if($cldiariofinal->numrows>0){
 ?>
 <table border="0">
  <?db_input('ed253_i_codigo',20,$Ied253_i_codigo,true,'hidden',$db_opcao,"")?>
  <tr>
   <td>
    <b>Alunos Reprovados:</b>
   </td>
   <td>
    <select id='ed253_i_diario' onchange='js_bloqueiaReeclassificacao()' name="ed253_i_diario">
     <option value=""></option>
     <?
     for($t=0;$t<$cldiariofinal->numrows;$t++){
      db_fieldsmemory($result3,$t);;
      $lBloquearReclassificacao = 'false';
      if ($ed74_c_resultadofreq == 'R') {
        $lBloquearReclassificacao = 'true';
      }
      echo "<option value='$ed74_i_diario' reclassificado='{$lBloquearReclassificacao}'>$ed47_v_nome</option>";
     }
     ?>
    </select>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted253_t_obs?>">
    <?=@$Led253_t_obs?>
   </td>
   <td>
    <?db_textarea('ed253_t_obs',3,60,$Ied253_t_obs,true,'text',$db_opcao,"")?>
   </td>
  </tr>
  <tr>
   <td nowrap title="<?=@$Ted253_i_rechumano?>">
    <?db_ancora(@$Led253_i_rechumano,"js_pesquisaed253_i_rechumano(true);",$db_opcao);?>
   </td>
   <td>
    <?db_input('ed253_i_rechumano',20,$Ied253_i_rechumano,true,'hidden',3,'')?>
    <?db_input('identificacao',20,@$Iidentificacao,true,'text',3,'')?>
    <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
   </td>
  </tr>
  <?php
  $oDaoAprovConselhoTipo = db_utils::getDao("aprovconselhotipo");
  $sSqlAprovTipo         = $oDaoAprovConselhoTipo->sql_query_file();
  $rsAprovConselhoTipo   = $oDaoAprovConselhoTipo->sql_record($sSqlAprovTipo);
  $aTipos = array();
  for ($i = 0; $i < $oDaoAprovConselhoTipo->numrows; $i++) {

    $oDados = db_utils::fieldsmemory($rsAprovConselhoTipo, $i);
    $aTipos[$oDados->ed122_sequencial] = $oDados->ed122_descricao;
  }
  ?>
   <tr>
     <td>
      <b>Forma de Aprovação:</b>
     </td>
     <td>
     <?php
      db_select("ed253_aprovconselhotipo", $aTipos, true, 1);
     ?>
     </td>
   </tr>
 </table>
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?}else{?>
 <table border="0">
  <?db_input('ed253_i_codigo',20,$Ied253_i_codigo,true,'hidden',$db_opcao,"")?>
  <tr>
   <td>
    <b>Nenhum aluno não encerrado tem a situação de Reprovado nesta disciplina.</b>
   </td>
  </tr>
 </table>
<?}?>
</center>
</form>
<script>
function js_pesquisaed253_i_rechumano(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?funcao_js=parent.js_mostrarechumano1|ed20_i_codigo|z01_nome|dl_identificacao','Pesquisa de Recursos Humanos',true);
 }else{
  if(document.form1.ed253_i_rechumano.value != ''){
   js_OpenJanelaIframe('','db_iframe_rechumano','func_rechumano.php?pesquisa_chave='+document.form1.ed253_i_rechumano.value+'&funcao_js=parent.js_mostrarechumano','Pesquisa',false);
  }
 }
}
function js_mostrarechumano1(chave1,chave2,chave3){
 document.form1.ed253_i_rechumano.value = chave1;
 document.form1.z01_nome.value = chave2;
 document.form1.identificacao.value = chave3;
 db_iframe_rechumano.hide();
}

function js_bloqueiaReeclassificacao(lBloquear) {

   $('ed253_aprovconselhotipo').options[1].disabled = true;
   var oSelectAlunos = $('ed253_i_diario');
   if (oSelectAlunos.options[oSelectAlunos.selectedIndex].getAttribute('reclassificado') == 'true') {
     $('ed253_aprovconselhotipo').options[1].disabled = false;
   }
}

$('ed253_aprovconselhotipo').options[1].disabled = true;
</script>