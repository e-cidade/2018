<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
$sCampos = "ed74_i_diario,ed47_v_nome,ed74_c_resultadofreq, ed74_c_resultadoaprov ";
$sWhere  = " ed95_i_regencia = $regencia AND ed74_c_resultadofinal = 'R' AND ed95_c_encerrado = 'N'";

$sSql = $cldiariofinal->sql_query("", $sCampos, "to_ascii(ed47_v_nome)", $sWhere);

$result3 = $cldiariofinal->sql_record($sSql);
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
     for($t=0;$t<$cldiariofinal->numrows;$t++) {

       db_fieldsmemory($result3,$t);;
       $lBloquearReclassificacao = 'false';
       if ($ed74_c_resultadofreq == 'R') {
         $lBloquearReclassificacao = 'true';
       }

       $lBloquearAprovadoConselho = 'false';
       if ($ed74_c_resultadoaprov == 'R') {
         $lBloquearAprovadoConselho = 'true';
       }

       echo "<option value='$ed74_i_diario' reclassificado='{$lBloquearReclassificacao}' conselho='{$lBloquearAprovadoConselho}' >$ed47_v_nome</option>";
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
  $sSqlAprovTipo         = $oDaoAprovConselhoTipo->sql_query_file(null, "*", "ed122_sequencial");
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
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
        type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
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

  $('ed253_aprovconselhotipo').disabled = false;
  if ( $F('ed253_i_diario') == '' ) {
    $('ed253_aprovconselhotipo').disabled = true;
  }

  /**
   * Verica se deve bloquear a opção
   * RECLASSIFICAÇÃO POR BAIXA FREQUÊNCIA
   */
  $('ed253_aprovconselhotipo').value = 1;
  $('ed253_aprovconselhotipo').options[1].disabled = true;
  var oSelectAlunos = $('ed253_i_diario');
  if (oSelectAlunos.options[oSelectAlunos.selectedIndex].getAttribute('reclassificado') == 'true') {
    $('ed253_aprovconselhotipo').options[1].disabled = false;
  }

  /**
   * Verica se deve bloquear a opção
   * APROVADO PELO CONSELHO
   */
  $('ed253_aprovconselhotipo').options[0].disabled = false;
  if (oSelectAlunos.options[oSelectAlunos.selectedIndex].getAttribute('conselho') == 'false') {

    $('ed253_aprovconselhotipo').options[0].disabled = true;
    $('ed253_aprovconselhotipo').value = 2;
  }
}
$('ed253_aprovconselhotipo').disabled = true;
</script>