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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));

$oRotulo = new rotulocampo;
$oRotulo->label("ed18_i_codigo");
$oRotulo->label("ed18_c_nome");
$oRotulo->label("ed31_i_codigo");
$oRotulo->label("ed31_c_descr");
$oRotulo->label("ed11_i_codigo");
$oRotulo->label("ed11_c_descr");
$oRotulo->label("ed15_i_codigo");
$oRotulo->label("ed15_c_nome");
$oRotulo->label("ed52_i_codigo");
$oRotulo->label("ed52_c_descr");
$oRotulo->label("ed52_d_inicio");
$oRotulo->label("ed52_d_fim");

$ed18_i_codigo = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");

$dDataAtual = explode('-', date('Y-m-d', time()));
$iDia       = $dDataAtual[2];
$iMes       = $dDataAtual[1];
$iAno       = $dDataAtual[0];

?>

<html>
 <head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script type="text/javascript" src="scripts/dates.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style type="text/css">
    table tr td:nth-child(odd) {
      width: 100px;
    }
  </style>
 </head>

 <body class="body-default">

    <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>

    <div class="container">
      <fieldset>
        <legend>Atestado de Vaga (Outras Escolas)</legend>

        <fieldset style="border:none;">
          <table class="form-container" >
            <tr>
              <td nowrap>
                <label for="aluno" >Aluno(a):</label>
              </td>
              <td>
                <input type="text" id="aluno" class="field-size-max"  />
              </td>
            </tr>
          </table>
        </fieldset>

        <fieldset class="separator">
          <legend>Dados do Destino</legend>
          <table class="form-container">
            <tr>
              <td nowrap title="<?=$Ted18_i_codigo?>">
                <label for="ed18_i_codigo">Escola:</label>
              </td>
              <td>
                <?php
                  db_input( 'ed18_i_codigo',  15, $Ied18_i_codigo, true, 'text', 3 );
                  db_input( 'ed18_c_nome',    50, $Ied18_c_nome,   true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Ted31_i_codigo?>">
                <a href="#" id="ancoraBaseCurricular">Base Curricular:</a>
              </td>
              <td>
                <?php
                  db_input( 'ed31_i_codigo', 15, $Ied31_i_codigo,  true, 'text', 1 );
                  db_input( 'ed31_c_descr',  50, $Ied31_c_descr,   true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="iCurso">Curso:</label>
              </td>
              <td>
                <input type="text" id='iCurso' name="iCurso" disabled="disabled" />
                <input type="text" id='sCurso' name="sCurso" disabled="disabled" />
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Ted11_i_codigo?>">
                <a href="#" id="ancoraEtapa">Etapa:</a>
              </td>
              <td>
                <?php
                  db_input( 'ed11_i_codigo', 15, $Ied11_i_codigo, true, 'text', 3 );
                  db_input( 'ed11_c_descr',  50, $Ied11_c_descr,  true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Ted15_i_codigo?>">

                <a href="#" id="ancoraTurno">Turno:</a>

              </td>
              <td>
                <?php
                  db_input( 'ed15_i_codigo', 15, $Ied15_i_codigo, true, 'text', 1 );
                  db_input( 'ed15_c_nome',   50, $Ied15_c_nome,   true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=$Ted52_i_codigo?>">
                <a href="#" id="ancoraCalendario">Calendário:</a>
              </td>
              <td>
                <?php
                  db_input( 'ed52_i_codigo', 15, $Ied52_i_codigo, true, 'text', 3 );
                  db_input( 'ed52_c_descr',  50, $Ied52_c_descr,  true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="ed52_d_inicio">
                  <?=$Led52_d_inicio?>
                </label>
              </td>
              <td>
                <?php db_input( 'ed52_d_inicio', 10, $Ied52_d_inicio, true, 'text', 3 ); ?>
                <label for="ed52_d_fim">
                  <?=$Led52_d_fim;?>
                </label>
                <?php
                  db_input( 'ed52_d_fim', 10, $Ied52_d_fim, true, 'text', 3 );
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="dtAtestado">Data do Atestado:</label>
              </td>
              <td>
                <?php
                  db_inputdata( 'dtAtestado', $iDia, $iMes, $iAno, true, 'text', 1 );
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset class="separator">
                  <legend>Observações</legend>
                  <textarea id="sObservacao"></textarea>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input type="button" id="btnImprimir" value="Imprimir" />
    </div>
 </body>
</html>
<?php
db_menu(
         db_getsession("DB_id_usuario"),
         db_getsession("DB_modulo"),
         db_getsession("DB_anousu"),
         db_getsession("DB_instit")
       );
?>
<script>

var sArquivoMensagem = "educacao.escola.edu4_atestadovagafora001.";

$('aluno').addEventListener('input', function(event) {
  js_ValidaCampos(this, 2, 'Nome do Aluno', 'f', 't', event);
});

$('aluno').addEventListener('blur', function(event) {
  js_ValidaMaiusculo(this, 't', event);
});

$('ed31_i_codigo').addEventListener( 'change', function() {

  if ( empty($F('ed31_i_codigo')) ) {
    limparCampos();
  }
});

var oLookUpBase = new DBLookUp( $('ancoraBaseCurricular'), $('ed31_i_codigo'), $('ed31_c_descr'), {
  sArquivo: 'func_basenova.php',
  sLabel: 'Pesquisa de Bases Curriculares',
  sObjetoLookUp: 'db_iframe_base',
  aCamposAdicionais: ['db_ed29_i_codigo', 'ed29_c_descr']
});

oLookUpBase.setCallBack('onClick', function(aCampos) {

  limparCampos();
  $('iCurso').value = aCampos[2];
  $('sCurso').value = aCampos[3];
});

oLookUpBase.setCallBack('onChange', function(lErro, aCampos) {

  limparCampos();
  if ( lErro ) {

    $('iCurso').value = '';
    $('sCurso').value = '';
    return false;
  }

  $('iCurso').value = aCampos[1];
  $('sCurso').value = aCampos[2];
});

$('ancoraTurno').addEventListener('click', function(event) {

  if ( !verificaSeBaseFoiInformada() )  {

    event.preventDefault();
    event.stopImmediatePropagation();
    return false;
  }

  oLookUpTurno.setParametrosAdicionais(['curso='+$F('iCurso')]);
});

$('ed15_i_codigo').addEventListener('change', function(event) {

  if ( !verificaSeBaseFoiInformada() )  {

    event.preventDefault();
    event.stopImmediatePropagation();
    $('ed15_i_codigo').value = '';
    return false;
  }

  oLookUpTurno.setParametrosAdicionais(['curso='+$F('iCurso')]);
});

var oLookUpTurno = new DBLookUp( $('ancoraTurno'), $('ed15_i_codigo'), $('ed15_c_nome'), {
  sArquivo: 'func_turnoturma.php',
  sLabel: 'Pesquisa de Turnos',
  sObjetoLookUp: 'db_iframe_turno'
});

$('ancoraEtapa').addEventListener('click', function(event) {

  if ( !verificaSeBaseFoiInformada() )  {

    event.preventDefault();
    event.stopImmediatePropagation();
    return false;
  }

  oLookUpEtapa.setParametrosAdicionais(['base='+$F('ed31_i_codigo')]);
});

var oLookUpEtapa = new DBLookUp( $('ancoraEtapa'), $('ed11_i_codigo'), $('ed11_c_descr'), {
  sArquivo: 'func_seriesbase.php',
  sLabel: 'Pesquisa de Etapas',
  sObjetoLookUp: 'db_iframe_etapa'
});

$('ancoraCalendario').addEventListener( 'click', function(event) {

  if ( empty('ed18_i_codigo') )  {

    alert("Informe a Escola.");
    event.preventDefault();
    event.stopImmediatePropagation();
    return false;
  }

  oLookUpCalendario.setParametrosAdicionais( ['iEscola='+$F('ed18_i_codigo')] );
});

var oLookUpCalendario = new DBLookUp ( $('ancoraCalendario'), $('ed52_i_codigo'), $('ed52_c_descr'), {
  sArquivo: 'func_calendariobase.php',
  sLabel: 'Pesquisa de Calendários',
  sObjetoLookUp: 'db_iframe_calendario',
  aCamposAdicionais: ['ed52_d_inicio', 'ed52_d_fim']
});

oLookUpCalendario.setCallBack('onClick', function(aCampos) {

  $('ed52_d_inicio').value = aCampos[2].substr(8,2) + "/" + aCampos[2].substr(5,2) + "/" + aCampos[2].substr(0,4);
  $('ed52_d_fim').value    = aCampos[3].substr(8,2) + "/" + aCampos[3].substr(5,2) + "/" + aCampos[3].substr(0,4);
});

function verificaSeBaseFoiInformada() {

  if ( $F('iCurso') == '' ) {

    alert( _M(sArquivoMensagem + 'informe_base_curricular') );
    return false;
  }
  return true;
}


$('btnImprimir').onclick = function() {

  if ( !validaCamposObrigatorios() ) {
    return;
  }

  var sParametros = '?sAluno='       + btoa($F('aluno').trim());
      sParametros += '&sEscola='     + btoa($F('ed18_c_nome').trim());
      sParametros += '&sEtapa='      + btoa($F('ed11_c_descr').trim());
      sParametros += '&sCurso='      + btoa($F('sCurso').trim());
      sParametros += '&sTurno='      + btoa($F('ed15_c_nome').trim());
      sParametros += '&sData='       + btoa($F('dtAtestado'));
      sParametros += '&sObservacao=' + btoa($F('sObservacao').trim());

  var oJanela = window.open( 'edu4_atestadovagafora002.php' + sParametros, '', 'scrollbars=1, location=0');
      oJanela.moveTo(0,0);
}

function validaCamposObrigatorios() {

  if ( empty($F('aluno')) ) {

    alert( _M(sArquivoMensagem + 'informe_aluno') );
    return false;
  }

  if ( empty($F('ed18_c_nome')) ) {

    alert( _M(sArquivoMensagem + 'informe_escola') );
    return false;
  }

  if ( empty($F('sCurso')) ) {

    alert( _M(sArquivoMensagem + 'informe_curso') );
    return false;
  }

  if ( empty($F('ed11_c_descr')) ) {

    alert( _M(sArquivoMensagem + 'informe_etapa') );
    return false;
  }

  if ( empty($F('ed15_c_nome')) ) {

    alert( _M(sArquivoMensagem + 'informe_turno') );
    return false;
  }

  if ( empty($F('ed52_i_codigo')) ) {

    alert( _M(sArquivoMensagem + 'informe_calendario') );
    return false;
  }

  if ( empty($F('dtAtestado')) ) {

    alert( _M(sArquivoMensagem + 'informe_data_atestado') );
    return false;
  }

  var dtInicial  = $F('ed52_d_inicio').substr(6,4) + "-" + $F('ed52_d_inicio').substr(3,2) + "-" + $F('ed52_d_inicio').substr(0,2);
  var dtFinal    = $F('ed52_d_fim').substr(6,4)    + "-" + $F('ed52_d_fim').substr(3,2)    + "-" + $F('ed52_d_fim').substr(0,2);
  var dtAtestado = $F('dtAtestado').substr(6,4)    + "-" + $F('dtAtestado').substr(3,2)    + "-" + $F('dtAtestado').substr(0,2);

  if ( !js_validaIntervaloData( dtAtestado, dtInicial, dtFinal ) ) {

    alert( _M(sArquivoMensagem + 'atestado_fora_periodo', { dtInicial : $F('ed52_d_inicio'), dtFinal : $F('ed52_d_fim') }) );
    return;
  }

  return true;
}


function limparCampos() {

  $('iCurso').value = '';
  $('sCurso').value = '';

  $('ed11_i_codigo').value = '';
  $('ed11_c_descr').value  = '';

  $('ed15_i_codigo').value = '';
  $('ed15_c_nome').value   = '';
}

$('ed18_i_codigo').addClassName("field-size2");
$('ed52_i_codigo').addClassName("field-size2");
$('ed52_d_inicio').addClassName("field-size2");
$('ed52_d_fim').addClassName("field-size2");
$('ed18_c_nome').addClassName("field-size8");
$('ed52_c_descr').addClassName("field-size8");
$('ed11_i_codigo').addClassName("field-size2");
$('ed11_c_descr').addClassName("field-size8");
$('iCurso').addClassName("field-size2 readonly");
$('sCurso').addClassName("field-size8 readonly");
$("dtAtestado").addClassName("field-size2");
$("sObservacao").setStyle({"text-transform" : 'uppercase'});
</script>
