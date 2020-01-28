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
$clregencia->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed57_i_codigo");
$clrotulo->label("ed12_i_codigo");

$sCamposTurma = "ed57_i_base as base, ed31_c_contrfreq as frequencia, ed29_i_ensino as codensino, ed31_c_descr as nomebase";
$sSqlTurma    = $clturma->sql_query("", $sCamposTurma, "", " ed57_i_codigo = $ed59_i_turma");
$result       = $clturma->sql_record($sSqlTurma);

if($clturma->numrows>0){
 db_fieldsmemory($result,0);
}

$db_botao1 = false;
$glob      = false;

if (isset($opcao) && $opcao == "alterar") {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao1 = true;

  if ($frequencia == "G") {

    $result2 = $clbasediscglob->sql_record($clbasediscglob->sql_query_file("","ed89_i_disciplina",""," ed89_i_codigo = $base"));
    db_fieldsmemory($result2,0);

    if ($ed89_i_disciplina == $ed59_i_disciplina) {
      $glob = true;
    } else {
      $glob = false;
    }
  }
  if ($ed59_c_freqglob == "INDIVIDUAL") {
    $ed59_c_freqglob = "I";
  } else if ($ed59_c_freqglob == "GLOBALIZADA (F)") {
    $ed59_c_freqglob = "F";
  } else if ($ed59_c_freqglob == "GLOBALIZADA (FA)") {
    $ed59_c_freqglob = "FA";
  } else {
    $ed59_c_freqglob = "A";
  }
} else if(isset($opcao) && $opcao == "excluir" || isset($db_opcao) && $db_opcao == 3) {

  $db_botao1 = true;
  $db_opcao  = 3;
  $db_opcao1 = 3;

  if ($frequencia == "G") {

    $result2 = $clbasediscglob->sql_record($clbasediscglob->sql_query_file("","ed89_i_disciplina",""," ed89_i_codigo = $base"));
    db_fieldsmemory($result2,0);
    if ($ed89_i_disciplina == $ed59_i_disciplina) {
      $glob = true;
    } else {
      $glob = false;
    }
  }

  if ($ed59_c_freqglob == "INDIVIDUAL") {
    $ed59_c_freqglob = "I";
  } else if ($ed59_c_freqglob == "GLOBALIZADA (F)") {
    $ed59_c_freqglob = "F";
  } else if ($ed59_c_freqglob == "GLOBALIZADA (FA)") {
    $ed59_c_freqglob = "FA";
  } else {
    $ed59_c_freqglob = "A";
  }
} else {

  if(isset($alterar)){

    $db_opcao  = 2;
    $db_botao1 = true;
  } else {

    $db_opcao  = 1;
    $db_opcao1 = 1;
  }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
 <?db_input('ed59_i_codigo',15,$Ied59_i_codigo,true,'hidden',3,"")?>
 <tr>
  <td nowrap title="<?=@$Ted59_i_turma?>">
   <?db_ancora(@$Led59_i_turma,"",3);?>
  </td>
  <td colspan="2">
   <?db_input('ed59_i_turma',15,$Ied59_i_turma,true,'text',3,"")?>
   <?db_input('ed57_c_descr',20,@$Ied57_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted59_i_serie?>">
   <?db_ancora(@$Led59_i_serie,"",3);?>
  </td>
  <td colspan="2">
   <?db_input('ed59_i_serie',15,$Ied59_i_serie,true,'text',3,"")?>
   <?db_input('ed11_c_descr',20,@$Ied11_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted59_i_disciplina?>">
   <?db_ancora(@$Led59_i_disciplina,"js_pesquisaed59_i_disciplina(true);",$db_opcao);?>
  </td>
  <td colspan="2">
   <?db_input('ed59_i_disciplina',15,$Ied59_i_disciplina,true,'text',3," onchange='js_pesquisaed59_i_disciplina(false);'")?>
   <?db_input('ed232_c_descr',40,@$Ied232_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted59_i_qtdperiodo?>">
   <?=@$Led59_i_qtdperiodo?>
  </td>
  <td width="125">
   <?db_input('ed59_i_qtdperiodo',10,$Ied59_i_qtdperiodo,true,'text',$db_opcao,"")?>
   &nbsp;&nbsp;&nbsp;
  </td>
  <td align="left">
   <?=@$Led59_c_condicao?>
   <?
   $x = array('OB'=>'OBRIGATÓRIA','OP'=>'OPCIONAL');
   db_select('ed59_c_condicao',$x,true,$db_opcao,"onchange='js_lancarHistorico(this.value)'");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted59_c_freqglob?>">
   <?=@$Led59_c_freqglob?>
  </td>
  <td>
   <?
   if($frequencia=="I"){
    $x = array('I'=>'INDIVIDUAL');
   }elseif($frequencia=="G" && $glob==true){
    $x = array('F'=>'GLOBALIZADA (F)','FA'=>'GLOBALIZADA (FA)');
   }elseif($frequencia=="G" && $glob==false && isset($opcao)){
    $x = array('A'=>'TRATADA');
   }elseif($frequencia=="G" && !isset($opcao)){
    $x = array('F'=>'GLOBALIZADA (F)','FA'=>'GLOBALIZADA (FA)','A'=>'TRATADA');
   }
   db_select('ed59_c_freqglob',$x,true,$db_opcao,"");
   ?>
  </td>

   <td align="left" nowrap title="<?=@$Ted59_lancarhistorico?>"><div id="tdLancarHistorico" style="display:
     <?php echo (isset($ed59_c_condicao) && $ed59_c_condicao != 'OB') ? 'block' : 'none'; ?>">
     <?=@$Led59_lancarhistorico?>
     <?php
       $aOpcoes = array('f'=>'NÃO', 't'=>'SIM');
       db_select('ed59_lancarhistorico',$aOpcoes,true,$db_opcao,"", 'ed59_lancarhistorico');
     ?>
       </div>
   </td>

 </tr>

</table>
<input name="frequencia" type="hidden" value="<?=@$frequencia?>">
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit"
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
       onclick="<?=($db_opcao==1?"":($db_opcao==2||$db_opcao==22?"":"return confirm('ATENÇÃO: A exclusão da disciplina na turma apagará todos os registros de todos alunos no Diário de Classe, referente a esta disciplina. Confirmar Exclusão?')"))?>"
       <?=($db_botao==false?"disabled":"")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<input id='atualizaPelaBase'
       name="atualizar"
       type="button"
       value="Atualizar pela Base"
       onclick="return js_validaDocente()"
       <?=($db_opcao!=1?"disabled":"")?>>
<input id='ordenarDisciplinas' name="ordenar" type="button" value="Ordenar Disciplinas" onclick="js_ordena();">
<?
if(isset($opcao) && $opcao=="alterar" && $glob==true && $frequencia =="G"){
?><script>document.form1.ed59_c_condicao.disabled = true;</script><?
}
?>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $campos ="distinct ed59_i_codigo,
             ed59_i_disciplina,
             ed232_c_descr,
             ed59_i_qtdperiodo,
             ed59_i_serie,
             ed11_c_descr,
             ed293_descr as ed232_areaconhecimento,
             ed59_c_condicao,
             ed59_i_ordenacao,
             case
              when ed59_c_freqglob = 'I'
               then 'INDIVIDUAL'
              when ed59_c_freqglob = 'F'
               then 'GLOBALIZADA (F)'
              when ed59_c_freqglob = 'FA'
               then 'GLOBALIZADA (FA)' else 'TRATADA'
             end as ed59_c_freqglob,
             ed59_lancarhistorico
            ";
   $chavepri = array("ed59_i_codigo"          => @$ed59_i_codigo,
                     "ed59_i_serie"           => @$ed59_i_serie,
                     "ed11_c_descr"           => @$ed11_c_descr,
                     "ed59_i_disciplina"      => @$ed59_i_disciplina,
                     "ed232_c_descr"          => @$ed232_c_descr,
                     "ed59_i_qtdperiodo"      => @$ed59_i_qtdperiodo,
                     "ed59_c_condicao"        => @$ed59_c_condicao,
                     "ed59_i_ordenacao"       => @$ed59_i_ordenacao,
                     "ed59_c_freqglob"        => @$ed59_c_freqglob,
                     "ed232_areaconhecimento" => @$ed232_areaconhecimento,
                     "ed59_lancarhistorico"   => @$ed59_lancarhistorico
                    );
   $cliframe_alterar_excluir->chavepri      = $chavepri;
   $sWhereObrigatorias                      = " ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $ed59_i_serie AND ed59_c_condicao = 'OB'";
   $cliframe_alterar_excluir->sql           = $clregencia->sql_query_censo("", $campos, "ed59_i_ordenacao", $sWhereObrigatorias);
   $sCamposObrigatorias                     = "ed59_i_codigo,ed232_c_descr,ed11_c_descr,ed59_i_qtdperiodo,ed59_c_freqglob,ed232_areaconhecimento";
   $cliframe_alterar_excluir->campos        = $sCamposObrigatorias;
   $cliframe_alterar_excluir->legenda       = "DISCIPLINAS OBRIGATÓRIAS (BASE CURRICULAR: $nomebase - Etapa $ed11_c_descr)";
   $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec    = "#DEB887";
   $cliframe_alterar_excluir->textocorpo    = "#444444";
   $cliframe_alterar_excluir->fundocabec    = "#444444";
   $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
   $cliframe_alterar_excluir->iframe_height = "165";
   $cliframe_alterar_excluir->iframe_width  = "100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario    = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
 <tr>
  <td valign="top">
  <?
   $cliframe_alterar_excluir->chavepri      = $chavepri;
   $sWhereOpcionais                         = " ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $ed59_i_serie AND ed59_c_condicao = 'OP'";
   $cliframe_alterar_excluir->sql           = $clregencia->sql_query_censo("", $campos, "ed59_i_ordenacao", $sWhereOpcionais);
   $cliframe_alterar_excluir->campos        = "ed59_i_codigo,ed232_c_descr,ed11_c_descr,ed59_i_qtdperiodo,ed59_c_freqglob";
   $cliframe_alterar_excluir->legenda       = "DISCIPLINAS OPCIONAIS (EXTRACURRICULARES)";
   $cliframe_alterar_excluir->msg_vazio     = "Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec    = "#DEB887";
   $cliframe_alterar_excluir->textocorpo    = "#444444";
   $cliframe_alterar_excluir->fundocabec    = "#444444";
   $cliframe_alterar_excluir->fundocorpo    = "#eaeaea";
   $cliframe_alterar_excluir->iframe_height = "110";
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

  document.getElementById('tdLancarHistorico').style.display = 'none';
  document.getElementById('ed59_lancarhistorico').value      = 'f';

  if (sMatricula == 'OP') {
    document.getElementById('tdLancarHistorico').style.display = 'block';
  }
}

var iTipoTurma = <?=$oGet->tipoturma?>;

if (iTipoTurma == 6) {

  $('atualizaPelaBase').style.display = "none";
  $('ordenarDisciplinas').style.display = "none";
}

function js_pesquisaed59_i_disciplina(mostra){
 if(mostra==true){
  <?if($frequencia=="I"){?>
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinaregencia.php?codensino=<?=@$codensino?>&disciplinas=<?=@$disc_cad?>&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas da Base Curricular',true);
  <?}else{?>
   js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinaregencia2.php?codensino=<?=@$codensino?>&disciplinas=<?=@$disc_cad?>&funcao_js=parent.js_mostradisciplina1|ed12_i_codigo|ed232_c_descr','Pesquisa de Disciplinas da Base Curricular',true);
  <?}?>
 }else{
  if(document.form1.ed59_i_disciplina.value != ''){
   <?if($frequencia=="I"){?>
    js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinaregencia.php?codensino=<?=@$codensino?>&disciplinas=<?=@$disc_cad?>&pesquisa_chave='+document.form1.ed59_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
   <?}else{?>
    js_OpenJanelaIframe('','db_iframe_disciplina','func_disciplinaregencia2.php?codensino=<?=@$codensino?>&disciplinas=<?=@$disc_cad?>&pesquisa_chave='+document.form1.ed59_i_disciplina.value+'&funcao_js=parent.js_mostradisciplina','Pesquisa',false);
   <?}?>
  }else{
   document.form1.ed232_c_descr.value = '';
  }
 }
}
function js_mostradisciplina(chave,chave1,chave2,erro){
 document.form1.ed232_c_descr.value = chave;
 if(erro==true){
  document.form1.ed59_i_disciplina.focus();
  document.form1.ed59_i_disciplina.value = '';
 }
}
function js_mostradisciplina1(chave1,chave2,chave3,chave4,chave5){
 document.form1.ed59_i_disciplina.value = chave1;
 document.form1.ed232_c_descr.value = chave2;
 db_iframe_disciplina.hide();
}
function js_ordena(){
 js_OpenJanelaIframe('','db_iframe_ordenar','edu1_regbaseordrdisciplina001.php?turma='+document.form1.ed59_i_turma.value+'&serie='+document.form1.ed59_i_serie.value,'Ordenar Disciplinas',true,0,15,screen.availWidth-50,screen.availHeight);
}

function js_validaDocente() {

  var oParametro    = new Object();
  oParametro.exec   = 'validarRegente';
  oParametro.iTurma = $('ed59_i_turma').value;
  oParametro.iEtapa = $('ed59_i_serie').value;

  var oAjax = new Ajax.Request(
                               'edu4_regente.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaValidaDocente
                               }
                              );
}

function js_retornaValidaDocente(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemDocenteAusente) {

    var sMsg  = "Existe docente com ausência e substituto cadastrado. Para poder atualizar as disciplinas pela base";
    sMsg     += ", é necessário primeiramente excluir os vínculos dos substitutos.";
    alert(sMsg);
    return false;
  } else {

    var sConfirma  = "O sistema irá atualizar as disciplinas desta turma\nconforme a base curricular da mesma ";
    sConfirma     += "(<?=$nomebase?> - <?=$ed11_c_descr?>).\n Confirmar atualização?";

    if (confirm(sConfirma)) {

      location.href = "edu1_regencia001.php?ed59_i_turma="+$('ed59_i_turma').value
                                         +"&ed57_c_descr="+$('ed57_c_descr').value
                                         +"&ed59_i_serie="+$('ed59_i_serie').value
                                         +"&ed11_c_descr="+$('ed11_c_descr').value
                                         +"&tipoturma=1"
                                         +"&atualizar";
      return true;
    }
  }
  return true;
}
</script>