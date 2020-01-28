<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
$clalunonecessidade->rotulo->label();
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 $ed214_d_data_dia = substr($ed214_d_data,0,2);
 $ed214_d_data_mes = substr($ed214_d_data,3,2);
 $ed214_d_data_ano = substr($ed214_d_data,6,4);
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 $ed214_d_data_dia = substr($ed214_d_data,0,2);
 $ed214_d_data_mes = substr($ed214_d_data,3,2);
 $ed214_d_data_ano = substr($ed214_d_data,6,4);
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
if($ed214_i_aluno!=""){
 $sql4 = "SELECT ed56_i_escola as cod_escola FROM alunocurso WHERE ed56_i_aluno = $ed214_i_aluno";
 $query4 = db_query($sql4);
 $linhas4 = pg_num_rows($query4);
 if($linhas4==0){
  $db_botao = true;
 }elseif(db_getsession("DB_coddepto")!=pg_result($query4,0,0)){
  $db_botao = false;
 }else{
  $db_botao = true;
 }
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="100%">
 <tr>
  <td valign="top">
   <table border="0">
    <tr>
     <td nowrap title="<?=@$Ted214_i_codigo?>">
      <?=@$Led214_i_codigo?>
     </td>
     <td>
      <?db_input('ed214_i_codigo',20,$Ied214_i_codigo,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted214_i_aluno?>">
      <?db_ancora(@$Led214_i_aluno,"",3);?>
     </td>
     <td>
      <?db_input('ed214_i_aluno',20,$Ied214_i_aluno,true,'text',3)?>
      <?db_input('ed47_v_nome',40,@$Ied47_v_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted214_i_necessidade?>">
      <?db_ancora(@$Led214_i_necessidade,"js_pesquisaed214_i_necessidade(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed214_i_necessidade',20,$Ied214_i_necessidade,true,'text',$db_opcao," onchange='js_pesquisaed214_i_necessidade(false);'")?>
      <?db_input('ed48_c_descr',30,@$Ied48_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted214_i_apoio?>">
      <?=@$Led214_i_apoio?>
     </td>
     <td>
      <?
      $x = array(""=>"","1"=>"SEM APOIO PEDAGÓGICO","2"=>"COM APOIO PEDAGÓGICO","3"=>"COM APOIO PEDAGÓGICO (OUTRO ESTABELECIMENTO)");
      db_select('ed214_i_apoio',$x,true,$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted214_i_tipo?>">
      <?=@$Led214_i_tipo?>
     </td>
     <td>
      <?
      $x = array(""=>"","1"=>"SEM DIAGNÓSTICO","2"=>"FICHA DE AVALIAÇÃO","3"=>"LAUDO TÉCNICO");
      db_select('ed214_i_tipo',$x,true,$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted214_i_escola?>">
      <?=@$Led214_i_escola?>
     </td>
     <td>
      <?db_input('ed214_i_escola',20,$Ied214_i_escola,true,'hidden',3,"")?>
      <?db_input('ed18_c_nome',60,@$Ied18_c_nome,true,'text',3,'')?>
    <tr>
     <td nowrap title="<?=@$Ted214_d_data?>">
      <?=@$Led214_d_data?>
     </td>
     <td>
      <?db_inputdata('ed214_d_data',@$ed214_d_data_dia,@$ed214_d_data_mes,@$ed214_d_data_ano,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td colspan="2">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
             type="submit"
             id="db_opcao"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
             <?=($db_botao==false?"disabled":"")?> >
      <input name="cancelar" type="submit" value="Cancelar" <?=(@$db_botao1==false?"disabled":"")?> >
      <input id="btnRecursosParaAvaliacao" name="btnRecursosParaAvaliacao" type="button" value="Recursos Para Avaliação" disabled >
      <?db_input('ed214_c_principal',3,@$Ied214_c_principal,true,'hidden',3,'')?>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <?
   $sql = "SELECT * FROM alunonecessidade
            inner join necessidade on ed48_i_codigo = ed214_i_necessidade
           WHERE ed214_i_aluno = $ed214_i_aluno
           ORDER BY ed48_c_descr";
   $result = db_query($sql);
   $linhas = pg_num_rows($result);
   if($linhas>1){
    ?>
    <b>Informe a necessidade maior:</b><br>
    <select name="principal" style="width:200px;" size="4">
     <?
     for($t=0;$t<$linhas;$t++){
      db_fieldsmemory($result,$t);
      $selected = $ed214_c_principal=="SIM"?"selected":"";
      $descricao = $ed214_c_principal=="SIM"?$ed48_c_descr." (MAIOR)":$ed48_c_descr;
      echo "<option value='$ed214_i_codigo' $selected>$descricao</option>";
     }
     ?>
    </select><br><br>
    <input name="atualizar" value="Atualizar" type="submit">
    <?
   }
   ?>
  </td>
 </tr>
</table>
<table width="100%">
 <tr>
  <td valign="top"><br>
  <?
   $chavepri= array("ed214_i_codigo"=>@$ed214_i_codigo,
                    "ed214_i_aluno"=>@$ed214_i_aluno,
                    "ed47_v_nome"=>@$ed47_v_nome,
                    "ed214_i_necessidade"=>@$ed214_i_necessidade,
                    "ed48_c_descr"=>@$ed48_c_descr,
                    "ed214_i_apoio"=>@$ed214_i_apoio,
                    "ed214_i_tipo"=>@$ed214_i_tipo,
                    "ed214_i_escola"=>@$ed214_i_escola,
                    "ed18_c_nome"=>@$ed18_c_nome,
                    "ed214_d_data"=>@$ed214_d_data,
                    "ed214_c_principal"=>@$ed214_c_principal
                    );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clalunonecessidade->sql_query("","*","ed48_c_descr"," ed214_i_aluno = $ed214_i_aluno");
   $cliframe_alterar_excluir->campos  = "ed48_i_codigo,ed48_c_descr,ed214_c_principal,ed18_c_nome,ed214_d_data";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="100";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   if($linhas4==0){
    $cliframe_alterar_excluir->opcoes = 1;
   }elseif(db_getsession("DB_coddepto")!=pg_result($query4,0,0)){
    $cliframe_alterar_excluir->opcoes = 4;
   }
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</center>
</form>
<script>
var sRpc = 'edu4_aluno.RPC.php';

$('btnRecursosParaAvaliacao').observe("click", function() {
  js_carregaWindowRecursos();
});

/**
 * Verificamos se o aluno possui alguma necessidade especial. Caso possua, habilitamos o botao Recursos Para Avaliação
 */
function js_temNecessidade() {

  var oParametro    = new Object;
  oParametro.exec   = 'temNecessidade';
  oParametro.iAluno = $F('ed214_i_aluno');

  var oAjax = new Ajax.Request(
                                sRpc,
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoTemNecessidade
                                }
                              );
}

/**
 * Retorno da verificacao se o aluno possui alguma necessidade especial
 */
function js_retornoTemNecessidade(oResponse) {

  $('btnRecursosParaAvaliacao').disabled = true;
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemNecessidade) {

    $('btnRecursosParaAvaliacao').disabled = false;
  }
}

/**
 * Carregamos a windowAux dos recursos para avaliação
 */
var oWindowRecursosEspeciais = null;
function js_carregaWindowRecursos() {

  var iTamanhoJanela = document.body.getWidth()/2.2;
  var iAlturaJanela  = document.body.getHeight()/1.1;
   oWindowRecursosEspeciais = new windowAux('wndRecursosEspeciais',
                                            'Recursos Especiais do Aluno',
                                            iTamanhoJanela,
                                            iAlturaJanela);

  var sConteudo  = "<div id='ctnRecursos' style='width: 99%'>";
      sConteudo += "  <form id='frmSalvarRecursos' name='frmSalvarRecursos' method='post'";
      sConteudo += "  </form>";
      sConteudo += "</div>";
      sConteudo += "<center>";
      sConteudo += "  <input id='btnSalvar' name='btnSalvar' type='button' value='Salvar' onClick='js_salvarRecursos()'>";
      sConteudo += "</center>";
  oWindowRecursosEspeciais.setContent(sConteudo);

  oWindowRecursosEspeciais.setShutDownFunction(function() {
    oWindowRecursosEspeciais.destroy();
  });

  var sMensagem     = 'Selecione um recurso abaixo, caso necessário.';
  var oMessageBoard = new DBMessageBoard('messageRecursosEspeciais',
                                         'Recursos Especiais do Aluno',
                                         sMensagem,
                                         oWindowRecursosEspeciais.getContentContainer()
                                        );

  oMessageBoard.show();
  oWindowRecursosEspeciais.show();

  oGridRecursos              = new DBGrid("gridRecursos");
  oGridRecursos.nameInstance = 'oGridRecursos';
  oGridRecursos.setCheckbox(0);
  oGridRecursos.setHeight(220);
  oGridRecursos.setCellAlign(new Array("center", "left"));
  oGridRecursos.setCellWidth(new Array("10%", "90%"));
  oGridRecursos.setHeader(new Array("Código", "Recurso Necessário"));

  oGridRecursos.show($('ctnRecursos'));
  js_pesquisaRecursos();
}

/**
 * Pesquisamos os recursos para avaliação do inep
 */
function js_pesquisaRecursos() {

  var oParametro    = new Object();
  oParametro.exec   = 'getRecursosAvaliacaoInep';
  oParametro.iAluno = $F('ed214_i_aluno');

  var oAjax = new Ajax.Request(
                                sRpc,
                                {
                                  method:     'post',
                                  parameters: 'json='+Object.toJSON(oParametro),
                                  onComplete: js_retornoPesquisaRecursos
                                }
                              );
}

/**
 * Retorno da pesquisa de recursos para avaliacao do inep
 */

var aNecessidadesEspeciaisAluno = [];
function js_retornoPesquisaRecursos(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  aNecessidadesEspeciaisAluno = oRetorno.aNecessidades;
  oGridRecursos.clearAll(true);
  oRetorno.aRecursosAvaliacaoInep.each(function(oLinha, iSeq) {

    var aLinha = new Array(oLinha.iCodigo, oLinha.sDescricao.urlDecode());
    var lCheck = false;

    if (oLinha.lTemRecurso) {
      lCheck = true;
    }

    oGridRecursos.addRow(aLinha, false, false, lCheck);
  });
  oGridRecursos.renderRows();
}

/**
 * Salvamos os recursos para avaliacao do inep selecionados para o aluno
 */
function js_salvarRecursos() {

  var aSelecionados = oGridRecursos.getSelection("object");
  var aRecursos     = new Array();
  var lSalvar       = true;

  aSelecionados.each(function(oRecurso, iSeq) {
    aRecursos.push(oRecurso.aCells[0].getValue());
  });

  /**
   * Sempre que aluno possuir as deficiencias, 101 - Cegueira ou 105 - Sudorcegueira, e marcar o recuso 102,
   * ele deve marcar mais de um recurso. (Para censo 2015)
   */
  var lValidou = true;
  if ( aRecursos.in_array(102) && aRecursos.length == 1) {

    aNecessidadesEspeciaisAluno.each(function( oNecessidadeAluno ) {

      if ( [101,105].in_array(oNecessidadeAluno.iCodigo) ) {

        alert('Ao ser informado AUXILIO TRANSCRICAO, deve ser selecionado mais de um Recurso Especial.');
        lValidou = false;
        return $break;
      }
    });
  }

  if ( !lValidou ) {
    return;
  }

  if (aRecursos.length == 0) {
    if (!confirm('Aluno não necessita de recursos especiais?')) {
      lSalvar = false;
    }
  }

  var iTotalRecursos = 0;
  aRecursos.each(function(iRecurso, iSeq) {

    if (iRecurso == 106 || iRecurso == 107 || iRecurso == 108) {
      iTotalRecursos++;
    }
  });

  if (iTotalRecursos > 1) {

    var sMensagem  = 'É permitido informar apenas 1 recurso entre Prova Ampliada (Fonte Tamanho 16), Prova Ampliada';
        sMensagem += ' (Fonte Tamanho 20) e Prova Ampliada (Fonte Tamanho 24).';
    alert(sMensagem);
    return false;
  }

  if (lSalvar) {

    var oParametro                   = new Object();
    oParametro.exec                  = 'salvarRecursosAvaliacao';
    oParametro.iAluno                = $F('ed214_i_aluno');
    oParametro.aRecursosSelecionados = aRecursos;

    var oAjax = new Ajax.Request(
                                 sRpc,
                                 {
                                   method:     'post',
                                   parameters: 'json='+Object.toJSON(oParametro),
                                   onComplete: js_retornoSalvarRecursos
                                 }
                                );
  }
}

/**
 * Retorno do salvar os recursos
 */
function js_retornoSalvarRecursos(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status == 1) {

    alert("Dados salvos com sucesso!");
    oWindowRecursosEspeciais.destroy();
    js_carregaWindowRecursos();
  } else {

    alert(oRetorno.message.urlDecode());
    return false;
  }
  return true;
}

function js_pesquisaed214_i_necessidade(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('', 'db_iframe_necessidade','func_necessidade.php?funcao_js='+
                        'parent.js_mostranecessidade1|ed48_i_codigo|ed48_c_descr',
                        'Pesquisa', true
                       );

  } else {

    if (document.form1.ed214_i_necessidade.value != '') {

      js_OpenJanelaIframe('', 'db_iframe_necessidade',
                          'func_necessidade.php?pesquisa_chave='+
                          document.form1.ed214_i_necessidade.value+
                          '&funcao_js=parent.js_mostranecessidade',
                          'Pesquisa', false
                         );

    } else {
      document.form1.ed48_c_descr.value  = '';
    }
  }
}

function js_mostranecessidade(sChave, lErro) {

  document.form1.ed48_c_descr.value = sChave;
  if (lErro == true) {

    document.form1.ed214_i_necessidade.focus();
    document.form1.ed214_i_necessidade.value = '';
  }
}

function js_mostranecessidade1(sChave1, sChave2){

  document.form1.ed214_i_necessidade.value = sChave1;
  document.form1.ed48_c_descr.value        = sChave2;
  db_iframe_necessidade.hide();
}

js_temNecessidade();
</script>