<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label("ed60_i_aluno");
$oRotulo->label("ed47_v_nome");

$oDataSistema          = new DBDate( date( "Y-m-d", db_getsession('DB_datausu') ) );
$dataClassificacao_dia = $oDataSistema->getDia();
$dataClassificacao_mes = $oDataSistema->getMes();
$dataClassificacao_ano = $oDataSistema->getAno();
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
    db_app::load("estilos.css, DBFormularios.css, grid.style.css");
  ?>
  <script src="scripts/classes/educacao/escola/LancamentoDisciplinaReclassificacao.classe.js"></script>
</head>
<body bgcolor="#cccccc" >
  <div class= 'container'>
    <form  action="">
      <fieldset>
        <legend>Reclassificação</legend>
        <table class='form-container'>
          <tr>
            <td nowrap='nowrap' class='bold'>
              <?db_ancora($Led60_i_aluno, "js_pesquisaAluno(true);", 1);?>
            </td>
            <td nowrap='nowrap'>
              <?db_input('ed60_i_aluno',  10, $Ied60_i_aluno, true, 'text', 1," onchange='js_pesquisaAluno(false);'");
                db_input('ed60_i_codigo', 10, '', true, 'hidden', 3, '');
                db_input('ed47_v_nome',   50, $Ied47_v_nome, true, 'text', 3, '');?>
            </td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'>Turma de Origem:</td>
            <td nowrap='nowrap'>
              <?db_input('turmaOrigem', 45, '', true, 'text', 3);
                db_input('etapaOrigem', 15, '', true, 'text', 3);?>
            </td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'>Data de Matrícula:</td>
            <td nowrap='nowrap'>
              <?db_input('dataMatricula', 10, '', true, 'text', 3)?>
            </td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'>
            <?db_ancora('Turma de Destino:', "js_pesquisaTurmaDestino(true);", 1);?>
            </td>
            <td nowrap='nowrap'>
              <?db_input('sTurmaDestino', 45, '', true, 'text', 3);
                db_input('sEtapaDestino', 15, '', true, 'text', 3);
                db_input('iTurmaDestino', 10, '', true, 'hidden', 3);
                db_input('iEtapaDestino', 10, '', true, 'hidden', 3);?>
            </td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'>Data:</td>
            <td nowrap='nowrap'>
              <?db_inputdata('dataClassificacao', $dataClassificacao_dia, $dataClassificacao_mes, 
                             $dataClassificacao_ano, true, 'text', 1, "")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'>Registrar Avaliação:</td>
            <td nowrap='nowrap'>
              <select id='registraAvaliacao'>
                <option value="f" selected="selected">Não</option>
                <option value="t">Sim</option>
              </select>
            </td>
          </tr>
          <tr id='gradeAvaliacao' style="display: none;">
            <td nowrap='nowrap' id='ctnLancador' colspan="2"></td>
          </tr>
          <tr>
            <td nowrap='nowrap' class='bold'></td>
            <td nowrap='nowrap'></td>
          </tr>
        </table>
        <fieldset>
          <legend>Observação:</legend>
          <?php 
            db_textarea('observacao', 4, 75, '', true, 'text', 1, "");
          ?>
        </fieldset>
      </fieldset>
      <input type="button" value='Processar' id='processar' name='processar'>
    </form>
  
  </div>
</body>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script type="text/javascript">

// var oDiaAtual = new Date();
const URLRPC  = "edu4_classificacao.RPC.php";

const URL_EDU04_RECLASSIFICACAO001 = "educacao.escola.edu04reclassicacao001.";

/**
 * Array de disciplina, foi montado como array multdimensional para manter compatbilidade com DBLancador
 * aDisciplinasDestino[][]
 * aDisciplinasDestino[0][0] = codigo
 * aDisciplinasDestino[0][1] = descricao 
 */
var aDisciplinasDestino = new Array();
var oDadosAlunoOrigem   = {};

var oLancador = null;

/**
 * Pesquisa os alunos com matriculas ativas
 */
function js_pesquisaAluno(lMostra) {

  aDisciplinasDestino = new Array();
  js_limpaTurmaDestino();
  var sUrl = 'func_alunoavanco.php?';
  
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostraAluno1|ed60_i_codigo|ed60_i_aluno|ed47_v_nome';    
    js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', sUrl, 'Pesquisa Aluno', true);

  } else {

    if ($F('ed60_i_aluno') != '') {

      sUrl += 'pesquisa_chave='+$F('ed60_i_aluno');
      sUrl += '&funcao_js=parent.js_mostraAluno';
      js_OpenJanelaIframe('top.corpo', 'db_iframe_aluno', sUrl, 'Pesquisa Aluno', false);
    } else {
      js_limpaDadosAluno();
    }
  }
}

function js_mostraAluno1(iMatricula, iCodigoAluno, sNome) {

  $('ed60_i_codigo').value = iMatricula;
  $('ed60_i_aluno').value  = iCodigoAluno;
  $('ed47_v_nome').value   = sNome;
  db_iframe_aluno.hide();
  js_buscaDadosMatricula(iMatricula);
}

/**
 * Retorno  
 * arguments[0] : Nome aluno
 * arguments[1] : Código turma
 * arguments[2] : Nome Turma
 * arguments[3] : Nome etapa
 * arguments[4] : Código etapa
 * arguments[5] : data matricula
 * arguments[6] : data de inicio calendário
 * arguments[7] : data final calendário
 * arguments[8] : data de matrícula
 * arguments[9] : código matrícula
 * arguments[10]: Erro
 */
function js_mostraAluno(sNome, iCodigoTurma , chave3, chave4, chave5, chave6, chave7, chave8, iMatricula, lErro) {

  $('ed60_i_codigo').value = iMatricula;
  $('ed47_v_nome').value   = sNome;
  
  js_limpaTurmaDestino();

  if (lErro) {
    
    js_limpaDadosAluno();
    return false;    
  }
  js_buscaDadosMatricula(iMatricula);
}

/**
 * Busca os dados da turma de destino
 */
function js_pesquisaTurmaDestino() {

  if ($F('ed60_i_aluno') == '') {

    alert(_M(URL_EDU04_RECLASSIFICACAO001+"selecione_aluno"));
    return false;      
  }
  var sUrl = 'func_classificacaoaluno.php?';
  sUrl += 'codigo_aluno='+$F('ed60_i_aluno');
  sUrl += '&funcao_js=parent.js_mostraTurmaDestino|ed57_i_codigo|ed57_c_descr|ed11_i_codigo|ed11_c_descr';
  js_OpenJanelaIframe('', 'db_iframe_classificacaoaluno', sUrl, 'Pesquisa turma de destino', true);
}

function js_mostraTurmaDestino(iTurma, sTurma, iEtapa, sEtapa) {

  $('sTurmaDestino').value = sTurma;
  $('sEtapaDestino').value = sEtapa;
  $('iTurmaDestino').value = iTurma;
  $('iEtapaDestino').value = iEtapa;

  db_iframe_classificacaoaluno.hide();
}
 
function js_limpaDadosAluno() {

  $('ed60_i_aluno').value  = "";  
  $('ed60_i_codigo').value = "";
  $('ed47_v_nome').value   = "";
  $('turmaOrigem').value   = "";
  $('etapaOrigem').value   = "";
  $('dataMatricula').value = "";
}

function js_limpaTurmaDestino() {

  $('sTurmaDestino').value     = "";
  $('sEtapaDestino').value     = "";
  $('iTurmaDestino').value     = "";
  $('iEtapaDestino').value     = "";
  $('registraAvaliacao').value = 'f';
  
}

/**
 * Busca os dados da Matricula
 */ 
function js_buscaDadosMatricula(iMatricula) {
  
  var oParametros        = {};
  oParametros.exec       = 'getDadosOrigemAluno';
  oParametros.iMatricula = iMatricula;

  var oRequest        = {};
  oRequest.method     = 'post';  
  oRequest.parameters = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete = js_retornoDadosMatricula; 

  js_divCarregando(_M(URL_EDU04_RECLASSIFICACAO001+"aguarde_buscando_dados_aluno"), "msgBox");
  new Ajax.Request(URLRPC, oRequest);
}

function js_retornoDadosMatricula(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval('(' + oAjax.responseText + ')');

  oDadosAlunoOrigem        = oRetorno.oDadosAluno;
  $('turmaOrigem').value   = oRetorno.oDadosAluno.turma_descricao.urlDecode();
  $('etapaOrigem').value   = oRetorno.oDadosAluno.etapa_descricao.urlDecode();
  $('dataMatricula').value = oRetorno.oDadosAluno.data_matricula;

  js_buscaDisciplinasTurmaOrigem();
} 

/**
 * @todo Devemos implementar os dados da grid
 */
$('registraAvaliacao').observe('change', function() {

  $('gradeAvaliacao').style.display = 'none'; 
  
  if ($F('registraAvaliacao') == 't') {

    if ($F('ed60_i_aluno') == '') {

      alert(_M(URL_EDU04_RECLASSIFICACAO001+"selecione_aluno"));
      $('registraAvaliacao').value = 'f';
      return false;      
    }
    $('gradeAvaliacao').style.display = 'table-row';
    
  }
});

/**
 * Busca as disciplinas da turma de Origem
 */
function js_buscaDisciplinasTurmaOrigem() {

  var oParametros    = {};
  oParametros.exec   = 'getDisciplinaTurmaOrigem';
  oParametros.iTurma = oDadosAlunoOrigem.turma_codigo;
  oParametros.iEtapa = oDadosAlunoOrigem.etapa_codigo;

  var oRequest        = {};
  oRequest.method     = 'post';  
  oRequest.parameters = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete = js_retornoDisciplinasTurmaOrigem; 

  js_divCarregando("Aguarde...", "msgBoxB");
  new Ajax.Request(URLRPC, oRequest);
}

function js_retornoDisciplinasTurmaOrigem(oAjax) {

  js_removeObj('msgBoxB');
  var oRetorno = eval('(' + oAjax.responseText + ')');

  oRetorno.aDisciplinas.each( function (oDisciplina) {
    
    var aDisciplina = [oDisciplina.iCodigo, oDisciplina.nome.urlDecode()];
    aDisciplinasDestino.push(aDisciplina);
  });
  
  /**
   * Limpa dados dos campos digitados
   */

  oLancador = new DBViewFormularioEducacao.LancamentoDisciplinaReclassificacao('disciplinaAvaliacao');
  //var oLancador = new DBLancador('disciplinaAvaliacao');
  oLancador.setNomeInstancia('oLancador');  
  oLancador.setLabelAncora('Componente Curricular:');   
  oLancador.setTextoFieldset("Resultado da Avaliação");
  var aCampos = ['ed12_i_codigo','ed232_c_descr'];
  oLancador.setParametrosPesquisa('func_disciplinascurso.php', aCampos, "iEtapa="+oDadosAlunoOrigem.etapa_codigo);
  /**
   * 
   */
  $('gradeAvaliacao').style.display = 'table-row';
  oLancador.show($('ctnLancador')); 
  $('gradeAvaliacao').style.display = 'none';
 //
  oLancador.carregarRegistros(aDisciplinasDestino);

};

/**
 * Processa os dados da reclassificação
 */
$('processar').observe('click', function () {

  var aAvaliacao = new Array();
  
  if ($F('registraAvaliacao') == 't') {
  
    var aAvaliacaoSelecionada = oLancador.getDisciplinas();

    if (aAvaliacaoSelecionada.length == 0 ) {
      
      alert(_M(URL_EDU04_RECLASSIFICACAO001+"resultado_avaliacao_vazio"));
      return false;
    }
    aAvaliacaoSelecionada.each( function (oComponente) {

      oComponente.sAvaliacao = encodeURIComponent(tagString(oComponente.sAvaliacao));
      aAvaliacao.push(oComponente);
    });
    
  }

  if ($F('iTurmaDestino') == "") {

    alert(_M(URL_EDU04_RECLASSIFICACAO001+"turma_destino_nao_informada"));
    return false;
  }
  
  var oParametros             = {};
  oParametros.exec            = 'processar';
  oParametros.iAluno          = $F('ed60_i_aluno');
  oParametros.iMatriculaAtual = $F('ed60_i_codigo');
  oParametros.iTurmaDestino   = $F('iTurmaDestino');
  oParametros.iEtapaDestino   = $F('iEtapaDestino');
  oParametros.data            = $F('dataClassificacao');
  oParametros.sTipo           = 'R';
  oParametros.aAvaliacao      = aAvaliacao;
  oParametros.sObservavcao    = encodeURIComponent(tagString($F('observacao')));
  
  var oRequest        = {};
  oRequest.method     = 'post';  
  oRequest.parameters = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete = js_retornoProcessar; 
  
  js_divCarregando(_M(URL_EDU04_RECLASSIFICACAO001+"aguarde_salvando"), "msgBoxC");
  new Ajax.Request(URLRPC, oRequest);
  
});


function js_retornoProcessar(oAjax) {

  js_removeObj('msgBoxC');
  var oRetorno = eval('(' + oAjax.responseText + ')');
  
  if (oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  } else {

    alert(_M(URL_EDU04_RECLASSIFICACAO001+"salvo_sucesso"));
    location.reload(true);    
  }
  
};

/**
 * Função executada ao iniciar 
 */
(function () {

  js_limpaDadosAluno();
  js_limpaTurmaDestino();

  aDisciplinasDestino = new Array();
  
})();
</script>
</html>