<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
$oDaoTransfEscolaFora->rotulo->label();
$oClRotulo = new rotulocampo;
$oClRotulo->label("ed47_i_codigo");
$oClRotulo->label("ed18_i_codigo");
$oClRotulo->label("ed82_i_codigo");
$oClRotulo->label("nome");
?>
<form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Transferência para outras escolas</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Ted104_i_codigo?>">
          <?=@$Led104_i_codigo?>
        </td>
        <td>
          <? db_input('ed104_i_codigo', 15, $Ied104_i_codigo, true, 'text', 3, "") ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted104_i_aluno?>">
          <? db_ancora(@$Led104_i_aluno, "js_pesquisaed104_i_aluno(true);", $db_opcao); ?>
        </td>
        <td>
          <? db_input('ed104_i_aluno', 15, $Ied104_i_aluno, true, 'text', 3, "") ?>
          <? db_input('ed47_v_nome', 50, @$Ied47_v_nome, true, 'text', 3, '') ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <br>
          <fieldset>
            <legend>Dados da Matrícula</legend>
            <table>
              <tr>
                <td>
                  <b>Matrícula Atual:</b>
                </td>
                <td>
                  <? db_input( 'matricula', 40, @$matricula, true, 'text', 3 ); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Etapa / Turma Atual:</b>
                </td>
                <td>
                  <?php 
                    db_input( 'turma',      40, @$turma,      true, 'text',   3 );
                    db_input( 'base',       40, @$base,       true, 'hidden', 3 );
                    db_input( 'calendario', 40, @$calendario, true, 'hidden', 3 );
                    db_input( 'concluida',  40, @$concluida,  true, 'hidden', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Situação Atual:</b>
                </td>
                <td>
                  <? db_input( 'situacao', 20, @$situacao, true, 'text', 3 ); ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Data Matrícula:</b>
                </td>
                <td>
                  <?php
                    db_input( 'datamatricula', 10, @$datamatricula, true, 'text',   3 );
                    db_input( 'datamodif',     10, @$datamodif,     true, 'hidden', 3 );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Calendário Atual:</b>
                </td>
                <td>
                  <? db_input( 'caldescr', 20, @$situacao, true, 'text', 3 ); ?>
                  <b>Início:</b>
                  <? db_input( 'ed52_d_inicio', 10, @$ed52_d_inicio, true, 'text', 3 ); ?>
                  <b>Final:</b>
                  <? db_input( 'ed52_d_fim', 10, @$ed52_d_fim, true, 'text', 3 ); ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted104_i_escolaorigem?>">
          <? db_ancora(@$Led104_i_escolaorigem, "", 3); ?>
        </td>
        <td>
          <?php
            db_input( 'ed104_i_escolaorigem', 15, $Ied104_i_escolaorigem, true, 'text', 3 );
            db_input( 'ed18_c_nome',          50, @$Ied18_c_nome,         true, 'text', 3 );
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted104_i_escoladestino?>">
          <? db_ancora( @$Led104_i_escoladestino, "js_pesquisaed104_i_escoladestino(true);", $db_opcao ); ?>
        </td>
        <td>
          <? db_input( 'ed104_i_escoladestino', 15, $Ied104_i_escoladestino, true, 'text',
                      $db_opcao, " onchange='js_pesquisaed104_i_escoladestino(false);'" );
          ?>
          <? db_input( 'ed82_c_nome', 50, @$Ied82_c_nome, true, 'text', 3 ); ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted104_t_obs?>" colspan="2">
          <fieldset class="separator">
            <legend><?=@$Led104_t_obs?></legend>
            <? db_textarea( 'ed104_t_obs', 4, 63, $Ied104_t_obs, true, 'text', $db_opcao ); ?>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Ted104_d_data?>">
          <?=@$Led104_d_data?>
        </td>
        <td>
          <? db_inputdata( 'ed104_d_data', @$ed104_d_data_dia, @$ed104_d_data_mes,
                          @$ed104_d_data_ano, true, 'text', $db_opcao );
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Emissor:</b>
        </td>
        <td>
          <?=Assinatura(db_getsession("DB_coddepto"))?> (Informação para a Guia de Transferência)
        </td>
      </tr>
      <tr>
        <?
           $sSqlObs     = $oDaoObsTransferencia->sql_query("",
                                                           "ed283_c_bolsafamilia,ed283_t_mensagem",
                                                           "",
                                                           " ed283_i_escola = $iEscola"
                                                          );
           $rsResultObs = $oDaoObsTransferencia->sql_record($sSqlObs);
  
           if ($oDaoObsTransferencia->numrows>0) {
             $obs = db_utils::fieldsmemory($rsResultObs, 0)->ed283_t_mensagem;
           }
        ?>
        <td>
          <b>Bolsa Família:</b>
        </td>
        <td>
          <?
            $aOpBolFamilia = array("1" => "NÃO", "2" => "SIM");
            db_select( 'ed283_c_bolsafamilia', $aOpBolFamilia, true, @$db_opcao );
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend>Observação Geral:</legend>
            <? db_textarea( 'obs', 3, 40, @$obs, true, 'text', @$db_opcao ); ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>" 
         type="submit" id="db_opcao" value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 
                                                ?"Alterar" : "Excluir"))?>" <?=($db_botao == false ? "disabled"
                                                : "") ?> onclick="return js_submit()" <?=isset($incluir) ? 
                                                "style='visibility:hidden;'" : "" ?>>
  <input name='novaEscola' type='button' id='novaEscola' value='Nova Escola' onclick='js_incluirNovaEscola()' />
</form>
<script>

function js_pesquisaed104_i_aluno(mostra) {

  if (mostra == true) {

    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aluno',
                        'func_alunotransffora.php?funcao_js=parent.js_getDadosMatricula|ed47_i_codigo|ed47_v_nome',
                        'Pesquisa de Alunos',true
                       );
  } else {

    if (document.form1.ed104_i_aluno.value != '') {

      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_aluno',
                          'func_alunotransffora.php?pesquisa_chave='+document.form1.ed104_i_aluno.value+
                          '&funcao_js=parent.js_mostraaluno','Pesquisa',false
                         );
    } else {
      document.form1.ed47_v_nome.value = '';
    }
  }
}

function js_getDadosMatricula(iAluno, sAlunoNome) {

    db_iframe_aluno.hide();
    
    var oParam               = new Object();

    oParam.exec              = 'getDadosUltimaMatriculaAluno';
    oParam.iAluno            = iAluno;

    $('ed104_i_aluno').value = iAluno;
    $('ed47_v_nome').value   = sAlunoNome;

    oParam.iEscola           = <?=$ed104_i_escolaorigem?>;
    
    sUrl = 'edu4_escola.RPC.php';
    js_webajax(oParam, 'js_retornoGetDadosMatricula', sUrl);
}

function js_retornoGetDadosMatricula(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    $('matricula').value     = oRetorno.ed60_i_codigo;
    $('turma').value         = oRetorno.ed11_c_descr.urlDecode()+" / "+oRetorno.ed57_c_descr.urlDecode();
    $('base').value          = oRetorno.ed57_i_base;
    $('calendario').value    = oRetorno.ed57_i_calendario;
    $('situacao').value      = oRetorno.ed60_c_situacao.urlDecode();
    $('datamatricula').value = oRetorno.ed60_d_datamatricula;
    $('datamodif').value     = oRetorno.ed60_d_datamodif;
    $('caldescr').value      = oRetorno.ed52_c_descr.urlDecode();
    $('ed52_d_inicio').value = oRetorno.ed52_d_inicio;
    $('ed52_d_fim').value    = oRetorno.ed52_d_fim;
    $('concluida').value     = oRetorno.ed60_c_concluida.urlDecode();
  }
}

function js_pesquisaed104_i_escoladestino(mostra) {
 
  if(mostra == true) {
  
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_escolaproc',
                        'func_escolaproc.php?funcao_js=parent.js_mostraescolaproc1|ed82_i_codigo|ed82_c_nome',
                        'Pesquisa de Escolas de Procedência',true
                       );
  } else {
    
    if(document.form1.ed104_i_escoladestino.value != ''){
      
      js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_escolaproc',
                          'func_escolaproc.php?pesquisa_chave='+$('ed104_i_escoladestino').value+
                          '&funcao_js=parent.js_mostraescolaproc|ed82_i_codigo|ed82_c_nome','Pesquisa',false
                         );
    } else {
      document.form1.ed82_c_nome.value = '';
    }
  }
}

function js_mostraescolaproc(ed82_i_codigo, ed82_c_nome){
  
  if (   ed82_i_codigo == undefined 
      || ed82_i_codigo == ""
      || ed82_c_nome == undefined
      || ed82_c_nome == "") {
 
    alert(_M('educacao.escola.db_frmtransfescolafora.escola_nao_encontrada_efetue_busca'));
    $('ed104_i_escoladestino').value = "";
    $('ed104_i_escoladestino').focus();
    return false;
  } else {
  
    db_iframe_escolaproc.hide();
    $('ed104_i_escoladestino').value = ed82_i_codigo;
    $('ed82_c_nome').value           = ed82_c_nome;
  }
}

function js_mostraescolaproc1(chave1, chave2) {
 
  document.form1.ed104_i_escoladestino.value = chave1;
  document.form1.ed82_c_nome.value = chave2;
  db_iframe_escolaproc.hide();
}

function js_novaescola(){
  
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_novaescola','edu1_escolaprocnova001.php',
                      'Nova Escola de Procedência',true
                     );
}

<?

  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }

?>

function js_checaEscolaExiste() {

  var iEscola    = $('ed104_i_escoladestino').value;

  var oParam     = new Object();
  oParam.exec    = "getEscolaForaTransferencia";
  oParam.iEscola = iEscola;

  sUrl           = "edu4_escola.RPC.php";

  js_webajax(oParam, 'js_retornoChecaEscola', sUrl);
}

function js_retornoChecaEscola(oRetorno) {

  oRetorno = eval("("+oRetorno.responseText+")");

  if (oRetorno.iStatus != 1) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  } else {

    if (oRetorno.lAchou) {
      return true;
    } else {
      return false;
    }
  }
}

function js_submit() {

  if (js_checaEscolaExiste() == false) {

    alert(_M('educacao.escola.db_frmtransfescolafora.escola_destinao_nao_encontrada'));
    return false;
    $('ed104_i_escoladestino').focus();
  } else if ($('ed104_i_aluno').value == "") {

    alert(_M('educacao.escola.db_frmtransfescolafora.selecione_aluno'));
    return false;
  } else if ($('ed104_i_escoladestino').value == "") {

	  alert(_M('educacao.escola.db_frmtransfescolafora.digite_pesquise_codigo_escola_destino'));
	  document.form1.ed104_i_escoladestino.focus();
	  document.form1.ed104_i_escoladestino.style.backgroundColor='#99A9AE';
	  return false;
  } else if (document.form1.ed104_d_data.value == "") {

    alert(_M('educacao.escola.db_frmtransfescolafora.informe_data_transferencia'));
    document.form1.ed104_d_data.focus();
    document.form1.ed104_d_data.style.backgroundColor='#99A9AE';
    return false;
  } else {

    datamat = document.form1.datamatricula.value;
    datatransf = document.form1.ed104_d_data_ano.value+"-"+
                 document.form1.ed104_d_data_mes.value+"-"+document.form1.ed104_d_data_dia.value;
    if (document.form1.concluida.value != "S") {
   
      if (document.form1.ed52_d_inicio.value != "") {
    
        dataini = document.form1.ed52_d_inicio.value.substr(6, 4)+"-"+
                  document.form1.ed52_d_inicio.value.substr(3, 2)+"-"+
                  document.form1.ed52_d_inicio.value.substr(0, 2);
        datafim = document.form1.ed52_d_fim.value.substr(6, 4)+"-"+
                  document.form1.ed52_d_fim.value.substr(3, 2)+"-"+
                  document.form1.ed52_d_fim.value.substr(0, 2);
        check = js_validata(datatransf, dataini, datafim);
        
        if (check == false) {

          data_ini = dataini.substr(8, 2)+"/"+dataini.substr(5, 2)+"/"+dataini.substr(0, 4);
          data_fim = datafim.substr(8, 2)+"/"+datafim.substr(5, 2)+"/"+datafim.substr(0, 4);
          alert(_M('educacao.escola.db_frmtransfescolafora.data_transferencia_fora_periodo', {"iDataInicio" : data_ini, "iDataFinal" : data_fim}));
          document.form1.ed104_d_data.focus();
          document.form1.ed104_d_data.style.backgroundColor='#99A9AE';
          return false;
        }
      }
    }
    
    datatransf  = datatransf.substr(0,4)+''+datatransf.substr(5,2)+''+datatransf.substr(8,2);
    if (datamat != "") {

      datamat  = datamat.substr(6,4)+''+datamat.substr(3,2)+''+datamat.substr(0,2);
      if (parseInt(datamat)>parseInt(datatransf)) {

        alert(_M('educacao.escola.db_frmtransfescolafora.data_transferencia_menor_data_matricula'));
        document.form1.ed104_d_data.focus();
        document.form1.ed104_d_data.style.backgroundColor='#99A9AE';
        return false;
      }
    }
  }
  
  document.form1.db_opcao.style.visibility = "hidden";
  return true;
}

/**
 * Abre uma nova janela com o cadastro de escola procedencia
 */
function js_incluirNovaEscola() {

  js_OpenJanelaIframe(
                       'CurrentWindow.corpo',
                       'db_iframe_escolaprocedencia',
                       'edu1_escolaproc001.php?lOrigemTransferencia=true',
                       'Escola Procedência',
                       true
                     );
}

$("ed104_i_codigo").addClassName("field-size2");
$("ed104_i_aluno").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size7");
$("matricula").addClassName("field-size9");
$("turma").addClassName("field-size9");
$("situacao").addClassName("field-size9");
$("datamatricula").addClassName("field-size2");
$("caldescr").addClassName("field-size2");
$("ed52_d_inicio").addClassName("field-size2");
$("ed104_i_escolaorigem").addClassName("field-size2");
$("ed18_c_nome").addClassName("field-size7");
$("ed104_i_escoladestino").addClassName("field-size2");
$("ed82_c_nome").addClassName("field-size7");
$("ed104_i_escolaorigem").addClassName("field-size2");
$("ed52_d_fim").addClassName("field-size2");
$("ed104_d_data").addClassName("field-size2");
$("ed283_c_bolsafamilia").setAttribute("rel","ignore-css");
$("ed283_c_bolsafamilia").addClassName("field-size2");

</script>