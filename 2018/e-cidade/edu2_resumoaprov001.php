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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_matricula_classe.php");
require_once("dbforms/db_funcoes.php");

$iEscola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaCalendario.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaTurma.classe.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToggleList.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/ListaDisciplinas.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
  .DBToggleListBox .toggleListActionButons {
    margin: 9% 0 10px 6%;
  }
</style>
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" class="form-container" method="post">
      <fieldset style="min-width: 600px;">
        <legend>Relatório de Resumo de Aproveitamento</legend>
        <input type="hidden" id="iEscola" value="<?php echo $iEscola; ?>">
        <table>
          <tr>
            <td class="bold">
              Selecione o Calendário:
            </td>
            <td id="calendario"></td>
          </tr>
          <tr>
            <td class="bold">
              Selecione a Turma:
            </td>
            <td id="turma">
          </tr>
          <tr>
            <td class="bold">
              Exibir Trocas de Turma:
            </td>
            <td>
              <select id="exibirTrocaTurma" class="field-size-max">
                <option value="N">NÃO</option>
                <option value="S">SIM</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2" rel="ignore-css">
              <fieldset class="separator" style="min-width: 600px;">
                <legend class="bold">Selecione as Disciplinas:</legend>
                <div id="disciplina"></div>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" id="btnProcessar" value="Processar">
    </form>
  </div>

<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>

<script>

const MENSAGENS_RESUMO_APROVEITAMENTO = 'educacao.escola.edu2_resumoaprov001.';

var iEscola = $F('iEscola');

var oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_resumoaprov001.php');
    oDBFormCache.setElements( [ $('exibirTrocaTurma') ] );
    oDBFormCache.load();

var oCalendario = new DBViewFormularioEducacao.ListaCalendario();
    oCalendario.setEscola(iEscola);
    oCalendario.getCalendarios();

var oTurma      = new DBViewFormularioEducacao.ListaTurma();
var oDisciplina = new DBViewFormularioEducacao.ListaDisciplinas();

/**
 * Função a ser executada no load do combo do calendário
 */
var fLoadCalendario = function() {

  $('cboCalendario').className = 'field-size-max';
  oTurma.limpar();
  oDisciplina.clear();
};

/**
 * Função a ser executada ao alterar a opção no combo do calendário
 */
var fChangeCalendario = function() {

  var sCalendarioSelecionado = oCalendario.getSelecionados().iCalendario;
  oTurma.limpar();
  oDisciplina.clear();

  if( sCalendarioSelecionado != '' ) {

    oTurma.setEscola(iEscola);
    oTurma.setTipoTurmaFora([6]);
    oTurma.setCalendario(sCalendarioSelecionado);
    oTurma.getTurmas();
  }
};

/**
 * Seta as propriedades do combo da calendario
 */
oCalendario.setCallBackLoad( fLoadCalendario );
oCalendario.setOnChangeCallBack( fChangeCalendario );
oCalendario.show( $('calendario') );


/**
 * Função a ser executado no load do combo de turmas
 */
var fLoadTurma = function() {

  $('cboTurma').className = 'field-size-max';
  oDisciplina.clear();
}

/**
 * Função a ser executada ao alterar a opção do combo da turma
 */
var fChangeTurma = function() {

  var oTurmaSelecionada = oTurma.getSelecionados();

  oDisciplina.clear();

  if ( oTurmaSelecionada.codigo_turma != '' ) {
    oDisciplina.getDisciplinas(oTurmaSelecionada.codigo_turma, oTurmaSelecionada.codigo_etapa, false);
  }
}

/**
 * Seta as propriedades do combo da turma
 */
oTurma.setCallBackLoad(fLoadTurma);
oTurma.setCallbackOnChange(fChangeTurma);
oTurma.show( $('turma') );

/**
 * Mostra o toggle das disciplinas
 */
oDisciplina.show( $('disciplina') );

/**
 * Valida se os campos foram preenchidos corretamente
 */
function validaCampos() {

  /**
   * Verifica se o calendário foi selecionada
   */
  if( oCalendario.getSelecionados().iCalendario == '' ) {

    alert( _M(MENSAGENS_RESUMO_APROVEITAMENTO + "selecione_calendario") );
    return false;
  }

  /**
   * Verifica se a Turma foi selecionada
   */
  if ( oTurma.getSelecionados().codigo_turma == '') {

    alert( _M(MENSAGENS_RESUMO_APROVEITAMENTO + "selecione_turma") );
    return false;
  }

  /**
   * Verifica se ao menos uma disciplina foi selecionada.
   */
  if ( oDisciplina.getSelecionados().length == 0 ) {

    alert( _M(MENSAGENS_RESUMO_APROVEITAMENTO + "selecione_disciplina") );
    return false;
  }

  return true;
}

/**
 * Envia os dados para serem impressos
 * @return {[type]} [description]
 */
$('btnProcessar').onclick = function() {

  if ( !validaCampos() ){
    return;
  }

  var aRegencias = new Array();

  oDisciplina.getSelecionados().each(function (oDisciplina) {
    aRegencias.push(oDisciplina.iRegencia);
  });

  var sUrl         = 'edu2_resumoaprovnovo002.php';
  var sParametros  = '?iCalendario='       + oCalendario.getSelecionados().iCalendario;
      sParametros += '&iTurma='            + oTurma.getSelecionados().codigo_turma;
      sParametros += '&iEtapa='            + oTurma.getSelecionados().codigo_etapa;
      sParametros += '&aRegencias='        + aRegencias;
      sParametros += '&lExibirTrocaTurma=' + $F('exibirTrocaTurma');

  oDBFormCache.save();

  jan = window.open(
                   sUrl + sParametros,
                   '',
                   'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0'
                 );

  jan.moveTo(0,0);
};

</script>