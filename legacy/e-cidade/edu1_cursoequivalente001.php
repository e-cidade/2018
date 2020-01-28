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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body class="body-default">

<div class="container">
  <form>
    <fieldset>
      <legend>Equivalência entre Cursos</legend>
      <table class="form-container">
        <tr>
          <td class="field-size2">Curso:</td>
          <td>
            <select id="cboCurso">
              <option value="">Selecione...</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div id="lancadorCursos"></div>
          </td>
        </tr>
      </table>
    </fieldset>
    <input id="btnSalvar" type="button" value="Salvar" onclick="salvar()" />
  </form>
</div>

</body>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script type="text/javascript">

var sArquivoMensagem = "educacao.secretariaeducacao.edu1_cursoequivalente001.";

var oLancadorCursos                = new DBLancador("oLancadorCursos");
    oLancadorCursos.iGridHeight    = 100;
    oLancadorCursos.sTextoFieldset = 'Curso(s) Equivalente(s)';
    oLancadorCursos.setLabelAncora("Curso Equivalente:");
    oLancadorCursos.setNomeInstancia("oLancadorCursos");
    oLancadorCursos.setHabilitado(false);
    oLancadorCursos.show($("lancadorCursos"));

(function() {

  new AjaxRequest( 'edu4_cursoequivalencia.RPC.php', {'exec':'buscarCursos'}, function ( oRetorno, lErro ) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return false;
    }

    var oCboCursos = $('cboCurso');
    for ( var oCurso of oRetorno.aCursos) {
      oCboCursos.add(new Option(oCurso.sCurso, oCurso.iCurso));
    }
  }).setMessage( _M( sArquivoMensagem + "buscando_cursos") ).execute();

})();

$('cboCurso').observe('change', function() {

    oLancadorCursos.clearAll();

    if ( empty($F('cboCurso')) ) {

      oLancadorCursos.setHabilitado(false);
      oLancadorCursos.show($("lancadorCursos"));
      return false;
    }

    oLancadorCursos.setHabilitado(true);
    oLancadorCursos.show($("lancadorCursos"));

    buscaCursosEquivalentes();
});

function buscaCursosEquivalentes() {

  var oParametros = {'exec':'buscarCursosEquivalentes', 'iCurso' : $F('cboCurso')};
  new AjaxRequest( 'edu4_cursoequivalencia.RPC.php', oParametros, function ( oRetorno, lErro ) {

    if ( lErro ) {

      alert(oRetorno.sMessage);
      return false;
    }

    oLancadorCursos.setHabilitado(true);
    oLancadorCursos.show($("lancadorCursos"));

    var aCursosVinculados = [$F('cboCurso')];

    for ( var oCursoEquivalente of oRetorno.aCursosEquivalentes) {

      aCursosVinculados.push(oCursoEquivalente.iCurso);
      oLancadorCursos.adicionarRegistro(oCursoEquivalente.iCurso, oCursoEquivalente.sCurso);
    }

    oLancadorCursos.setParametrosPesquisa("func_cursoedu.php",
                                   ["ed29_i_codigo", "ed29_c_descr"],
                                   'aCursosVinculados=' + aCursosVinculados
                                  );
  }).setMessage( _M( sArquivoMensagem + "buscando_cursos_equivalentes") ).execute();
}

function salvar() {

  if ( empty($F('cboCurso')) ) {

    alert( _M( sArquivoMensagem + "informe_curso") );
    return false;
  }

  var aCursosEquivalentes = [];

  for ( var oCursoEquivalente of oLancadorCursos.getRegistros() ) {
    aCursosEquivalentes.push(oCursoEquivalente.sCodigo);
  }

  var oParametros = {
    'exec'                : 'salvar',
    'iCurso'              : $F('cboCurso'),
    'aCursosEquivalentes' : aCursosEquivalentes
  };

  new AjaxRequest( 'edu4_cursoequivalencia.RPC.php', oParametros, function ( oRetorno, lErro ) {

    alert(oRetorno.sMessage);
    $('cboCurso').value = '';
    oLancadorCursos.clearAll();
    oLancadorCursos.setHabilitado(false);
    oLancadorCursos.show($("lancadorCursos"));
  }).setMessage( _M( sArquivoMensagem + "salvando_cursos") ).execute();

}
</script>