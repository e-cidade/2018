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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
</head>
<body class='body-default'>
  <div class='container'>

    <form>
      <fieldset>
        <legend>Ativar / Desativar Procedimento de Avaliação</legend>
        <table class="form-container">
          <tr>
            <td><label for="ed40_i_codigo"><a href="#" id='aconraProcedimento'> Procedimento:</a></label></td>
            <td>
              <input type="text" name="ed40_i_codigo" id="ed40_i_codigo" class="field-size2" />
              <input type="text" name="ed40_c_descr"  id="ed40_c_descr"  class="field-size6 readonly" disabled="disabled" />
            </td>
          </tr>
          <tr>
            <td><label for="desativado">Situação:</label></td>
            <td>
              <select id='desativado'>
              <option value=""></option>
              <option value="f">Ativo</option>
              <option value="t">Desativado</option>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" />
    </form>
  </div>
  <?php
    db_menu();
  ?>
</body>
<script type="text/javascript">

var MSG = "educacao.escola.edu1_desativarprocedimento001.";

$('ed40_i_codigo').addEventListener('change', function(event){

  if ( event.target.value == '' ) {
    $('desativado').value = '';
  }
});

var oLookUpProcedimento = new DBLookUp( $('aconraProcedimento'), $('ed40_i_codigo'), $('ed40_c_descr'),  {
  sArquivo              : 'func_procedimentosituacao.php',
  sLabel                : 'Pesquisa de Procedimentos',
  sObjetoLookUp         : 'db_iframe_procedimentosituacao',
  aCamposAdicionais     : ['db_ed40_desativado']
});


oLookUpProcedimento.setCallBack('onClick',  defineSituacaoAtual);
oLookUpProcedimento.setCallBack('onChange', changeLoockUp);

function changeLoockUp(lErro, aCampos) {

  $('desativado').value = '';
  if ( lErro ) {
    return;
  }
  defineSituacaoAtual(aCampos);
}


function defineSituacaoAtual(aCampos) {

  $('desativado').value = aCampos[2];
}

$('btnSalvar').addEventListener('click', function() {

  if ( empty($F('ed40_i_codigo')) ) {

    alert(_M(MSG + "informe_procedimento"));
    return;
  }
  if ( empty($F('desativado')) ) {

    alert(_M(MSG + "informe_situacao"));
    return;
  }

  if ( $F('desativado') == 't' && !confirm(_M(MSG + "mgs_confirme_desativar")) ) {
    return;
  }

  var paramentros = {
    'exec'          : 'alterarSituacaoProcedimento',
    'iProcedimento' : $F('ed40_i_codigo'),
    'lDesativar'    : $F('desativado') == 't'
  }

  new AjaxRequest('edu4_procedimentoavaliacao.RPC.php', paramentros, function(oRetorno, lErro) {

    alert( oRetorno.sMessage.urlDecode() );
    if (lErro) {
      return;
    }
    limpar();
  }).setMessage( _M(MSG + 'alterando_situacao') ).execute();
});

function limpar() {

  $('ed40_i_codigo').value = '';
  $('ed40_c_descr').value  = '';
  $('desativado').value    = '';
}

</script>
</html>