<?php
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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$clrotulo = new rotulocampo;
$clrotulo->label("ed52_c_descr");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed248_t_obs");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed29_c_descr");
$clrotulo->label("ed52_d_inicio");
$clrotulo->label("ed52_d_fim");

$clrotulo->label("dataModificacao");
$clrotulo->label("dataModificacao_dia");
$clrotulo->label("dataModificacao_mes");
$clrotulo->label("dataModificacao_ano");

$oDataSistema = new DBDate(date('Y-m-d', db_getsession('DB_datausu')));

$sDataSistema    = $oDataSistema->convertTo(DBDate::DATA_PTBR);
$sDataSistemaDia = $oDataSistema->getDia();
$sDataSistemaMes = $oDataSistema->getMes();
$sDataSistemaAno = $oDataSistema->getAno();


$oDaoTurma = new cl_turma();
$oDaoAluno = new cl_matricula();
$oDaoTurma->rotulo->label();
$oDaoAluno->rotulo->label();

if (isset($oGet->pesquisaTurma)) {
  
  $sCampos  = " ed57_i_codigo, ed57_c_descr, ed52_c_descr, ed29_c_descr, ed15_c_nome, ed52_d_inicio, ed52_d_fim, ";
  $sCampos .= " fc_nomeetapaturma(ed57_i_codigo) as nometapa, fc_codetapaturma(ed57_i_codigo) as codetapa";
  $sWhere   = "ed57_i_codigo = {$oGet->pesquisaTurma}";
  $sSql     = $oDaoTurma->sql_query("", $sCampos, "", $sWhere);

  $rsDadosTurma = $oDaoTurma->sql_record($sSql);
  db_fieldsmemory($rsDadosTurma, 0);
}


?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/dates.js"></script>
    <script type="text/javascript" src="scripts/classes/educacao/DBViewFormularioEducacao.classe.js"></script>
    <script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body style="background-color: #ccc;">
  <div class="container">
    <form>
      <fieldset>

        <legend>Alterar Turno Matricula Ed. Infantil</legend>        
        <fieldset class='separator'>
          <legend>Turma</legend>
          <table class="form-container">
            <tr>
              <td nowrap="nowrap" class="field-size3" title="<?=$Ted57_i_codigo?>">
                <?db_ancora( "Turma:", "js_pesquisaTurma();", 1);?>
              </td>
              <td nowrap="nowrap">
              <?php
                db_input( 'ed57_i_codigo', 10, '', true, 'text', 3 );
                db_input( 'ed57_c_descr',  46, '', true, 'text', 3 );
              ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap">
                <?=$Led57_i_calendario?>
              </td>
              <td nowrap="nowrap">
                <?
                  db_input( 'ed52_c_descr',  60, '', true, 'text', 3 );
                  db_input( 'ed52_d_inicio', 60, '', true, 'hidden', 3 );
                  db_input( 'ed52_d_fim',    60, '', true, 'hidden', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" title="<?=$Ted31_i_curso?>">
                <?=$Led31_i_curso?>
              </td>
              <td nowrap="nowrap">
                <?db_input( 'ed29_c_descr', 60, '', true, 'text', 3 );?>
              </td>
            </tr>
            <tr id='linhaEtapa'>
              <td nowrap="nowrap">
                <?=$Led223_i_serie?>
              </td>
              <td nowrap="nowrap">
                <?db_input( 'nometapa', 30, '', true, 'text', 3 );?>
                <?=$Led57_i_turno?>
                <?db_input( 'ed15_c_nome', 20, '', true, 'text', 3 );?>
              </td>
            </tr>
          </table>  
        </fieldset>


        <fieldset class='separator' id='dadosAluno'>
          <legend>Alunos</legend>

          <table class="form-container">
            <tr>
              <td nowrap="nowrap" class="field-size3" title="<?=$Ted60_i_aluno?>">
                <?db_ancora( "Aluno:", "js_pesquisaAluno(true);", 1 );?>
              </td>
              <td nowrap="nowrap" colspan="3">
                <?php
                  db_input( 'ed60_i_aluno', 10, $Ied60_i_aluno, true, 'text', 1, " onchange='js_pesquisaAluno(false);'" );
                  db_input( 'ed47_v_nome',  46, $Ied47_v_nome, true, 'text', 3 );
                ?>
              </td>
            </tr>
              
              <tr>
                <td nowrap="nowrap">
                  <?=$Led60_d_datamatricula?>
                </td>
                <td nowrap="nowrap" colspan="3">
                  <?php
                    db_inputdata( 'ed60_d_datamatricula', '', '', '', true, 'text', 3 );
                  ?>
                </td> 
              </tr>
              <tr id='linhaDadosMatricula'>
                <td nowrap="nowrap" title="<?=$Ted60_matricula?>">
                  <?=$Led60_matricula?>
                </td>
                <td nowrap="nowrap">
                  <?db_input( 'ed60_matricula', 10, $Ied60_matricula, true, 'text', 3 );?>
                </td>
                <td nowrap="nowrap" title="<?=$Ted60_c_situacao?>">
                  <b>Situação:</b>
                </td>
                <td  nowrap="nowrap">
                  <?db_input( 'ed60_c_situacao', 35, '', true, 'text', 3 );?>
                </td>
              </tr >

              <!--
                Aqui sera colocado via javascript os dados para seleção do turno de referencia do aluno.
              -->
              
              <tr>
                <td>Data de modificação:</td>
                <td colspan="3">
                  <?db_inputdata( 'dataModificacao', '', '', '', true, 'text', 1 );?>
                </td>
              </tr>
            </table>
        </fieldset>
      </fieldset>
      <input type="button" value='Salvar' name='salvar' id='salvarAlteracaoTurno'>
    </form>     
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script type="text/javascript">

const MSG_AVISO = 'educacao.escola.edu4_alteraturnomatricula001.';
var oGet        = js_urlToObject();
var oTurmaTurno = null;

(function () {

  $('dadosAluno').style.display = 'none';
})();

if (oGet.pesquisaTurma && oGet.pesquisaTurma != "") {

  oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente($('linhaEtapa') , oGet.pesquisaTurma);
  oTurmaTurno.escondeLinhasTurnoTurma();
  oTurmaTurno.show();
  $('dadosAluno').style.display = '';

  $('dataModificacao').value     = '<?=$sDataSistema;?>';
  $('dataModificacao_dia').value = '<?=$sDataSistemaDia;?>';
  $('dataModificacao_mes').value = '<?=$sDataSistemaMes;?>';
  $('dataModificacao_ano').value = '<?=$sDataSistemaAno;?>';

}
  
  
function js_pesquisaTurma() {

  var sUrl  = 'func_turmainfantilturnointegral.php?funcao_js=parent.js_preencheDadosTurma|ed57_i_codigo';
      sUrl += '&lEnsinoInfantil=true';
  js_OpenJanelaIframe('', 'db_iframe_turmainfantilturnointegral', sUrl, 'Pesquisa Turmas Infantil', true);
}

function js_preencheDadosTurma(iTurma) {

  db_iframe_turmainfantilturnointegral.hide();
  location.href = 'edu4_alteraturnomatricula001.php?pesquisaTurma='+iTurma;
}

function js_pesquisaAluno(lMostra) {

  if ($F('ed57_i_codigo') == '') {

    alert( _M(MSG_AVISO + 'informe_turma') );
    $('ed60_i_aluno').value                  = '';
    $('ed57_i_codigo').style.backgroundColor = '#99A9AE';
    $('ed57_i_codigo').focus();
    return;
  }

  var sUrl  = 'func_matricula.php?excluir=yes';
      sUrl += '&turma='+$F('ed57_i_codigo');
      sUrl += '&lSituacaoLabel=true';

  if (lMostra) {

    sUrl += '&funcao_js=parent.js_mostraAluno|ed47_v_nome|ed60_matricula'
    sUrl += '|dl_Situação|ed60_d_datamatricula|ed60_d_datasaida|ed60_i_aluno',

    js_OpenJanelaIframe( '', 'db_iframe_aluno', sUrl, 'Pesquisa Alunos', true );
  } else if ( $F('ed60_i_aluno') != '' ) {

    sUrl += '&pesquisa_chave='+$F('ed60_i_aluno');
    sUrl += '&funcao_js=parent.js_mostraAluno';
    js_OpenJanelaIframe( '', 'db_iframe_aluno', sUrl, 'Pesquisa Alunos', false );
  } else {
    js_limpaDadosMatricula();
  }
  
}

/**
 * Retorno dos dados do aluno
 * arguments[0] = Nome do aluno
 * arguments[1] = Matricula ed60_matricula
 * arguments[2] = Situação da Matricula
 * arguments[3] = Data da Matrícula
 * arguments[4] = Data da Saída
 * arguments[5] = Código do Aluno ou Boolean se pesquisado pelo código do aluno
 */
function js_mostraAluno () {

  db_iframe_aluno.hide();
  
  if ( typeof arguments[5] == 'boolean' && arguments[5]) {

    js_limpaDadosMatricula();
    $('ed47_v_nome').value = arguments[0];    
    return;
  }

  $('ed47_v_nome').value          = arguments[0];
  $('ed60_matricula').value       = arguments[1];
  $('ed60_c_situacao').value      = arguments[2];
  $('ed60_d_datamatricula').value = js_formatar(arguments[3], 'd');
  
  if ( typeof arguments[5] != 'boolean') {
    $('ed60_i_aluno').value = arguments[5];
  }

  js_buscaTurnoVinculado();
}

function js_limpaDadosMatricula () {
  
  $('ed60_i_aluno').value         = '';
  $('ed47_v_nome').value          = '';
  $('ed60_matricula').value       = '';
  $('ed60_c_situacao').value      = '';
  $('ed60_d_datamatricula').value = '';
  $('turnoMatricula').innerHTML   = '';

}

function js_buscaTurnoVinculado () {
  
  var oParametros = {};
  oParametros.exec = 'getTurnoVinculado';
  oParametros.iAluno = $F('ed60_i_aluno');
  
  var oRequest = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoTurnoVinculado;

  js_divCarregando( _M( MSG_AVISO + "aguarde_buscando_turno_vinculado"), "msgBoxA" );
  
  new Ajax.Request('edu4_matricula.RPC.php', oRequest);

}

/**
 * Este array contém os turnos onde o aluno originalmente esta matriculado, 
 * é usado para validar o controle das vagas da turma
 */
var aTurnoAlunoVinculo = [];

function js_retornoTurnoVinculado( oAjax ) {

  js_removeObj('msgBoxA');
  var oRetorno = eval( "(" + oAjax.responseText + ")" );

  if ( oRetorno.iStatus == 2 ) {

    alert( _M( MSG_AVISO + "erro_buscar_turno_vinculado" ) );
    return;
  }

  oTurmaTurno.oLinhaCheck.style.display = '';
  oTurmaTurno.oLinhaCheck.id = 'linhaTurnoMatriculaAluno';

  /**
   * Pegamos a segunda coluna do tr e aplicamos um colspan para alinhar o form
   */
  oTurmaTurno.oLinhaCheck.children[1].setAttribute('colspan', '3');
  $('linhaDadosMatricula').parentNode.insertBefore(oTurmaTurno.oLinhaCheck, $('linhaDadosMatricula').nextSibling);

  $$('.TurmaTurnoReferente').each( function(oElement) {
    oElement.checked = false;
  });
  
  oRetorno.aTurnos.each( function (oTurno) {

    aTurnoAlunoVinculo.push(oTurno.ed336_turnoreferente);
    $('check_turno'+oTurno.ed336_turnoreferente).checked = true;
  });
}


function validaSalvar (argument) {
  
  if ( $F('ed60_i_aluno') == '' ) {

    alert( _M(MSG_AVISO+"erro_informe_um_aluno") );
    return false;
  }

  var dtCalendarioInicio = js_formatar($F('ed52_d_inicio'), 'd');
  var dtCalendarioFim    = js_formatar($F('ed52_d_fim'), 'd');
  var dataModificacao    = $F('dataModificacao_ano')+'-'+$F('dataModificacao_mes')+'-'+$F('dataModificacao_dia'); 

  if ( !js_validaIntervaloData(dataModificacao, $F('ed52_d_inicio'), $F('ed52_d_fim')) ) {

    alert( _M(MSG_AVISO + "erro_data_modificacao", { dtInicio : dtCalendarioInicio, dtFim : dtCalendarioFim }) );
    return false;
  }
  return true;
}

$('salvarAlteracaoTurno').observe('click', function() {
  
  if ( !validaSalvar() ) {
    return;
  };
  
  /**
   * Buscando os valores dos turnos de referência selecionados, validando se tem vagas
   */
  var aTurnoReferenteSelecionado = [];
  var lErro                      = false;
  $$('.TurmaTurnoReferente:checked').each( function(oElement) {
 
    if ( !aTurnoAlunoVinculo.in_array(oElement.value) ) {

      if ( parseInt($F('disponiveis'+oElement.value)) <= 0) {

        var sTurnoReferente = oTurmaTurno.aLegendaTurno[oElement.value];
        alert( _M(MSG_AVISO+"erro_turno_sem_vagas", {sTurno : sTurnoReferente}) );
        oElement.checked = false;
        lErro            = true;
        throw $break;
      }
    }

    aTurnoReferenteSelecionado.push(oElement.value);
  });

  if( lErro ) {
    return;
  }

  if ( aTurnoReferenteSelecionado.length == 0 ) {

    alert( _M(MSG_AVISO+"nenhum_turno_selecionado") );
    return;
  }

  var oParametros                  = {};
  oParametros.exec                 = 'alterarTurnoMatricula';
  oParametros.iAluno               = $F('ed60_i_aluno');
  oParametros.iTurma               = $F('ed57_i_codigo');
  oParametros.aTurmaTurnoReferente = aTurnoReferenteSelecionado;
  
  var oRequest = {};
  oRequest.method       = 'post';
  oRequest.asynchronous = false;
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = js_retornoSalvar;

  js_divCarregando( _M( MSG_AVISO + "aguarde_salvando_alteracao_turno"), "msgBoxB" );
  
  new Ajax.Request('edu4_matricula.RPC.php', oRequest);
});

function js_retornoSalvar ( oAjax ) {
  
  js_removeObj('msgBoxB');
  var oRetorno = eval( "(" + oAjax.responseText + ")");

  alert ( oRetorno.sMessage.urlDecode() );
  if (oRetorno.iStatus == 1) {
    location.href = 'edu4_alteraturnomatricula001.php?pesquisaTurma='+$F('ed57_i_codigo');
  }
}

</script>