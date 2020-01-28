<?php
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotuloCampo = new rotulocampo();

$oRotuloCampo->label("h12_assent");
$oRotuloCampo->label("h12_descricao");
$oRotuloCampo->label("h12_descr");

$oPost                  = db_utils::postMemory($_POST);
$sMensagem              = '';
$oDaoFeriasConfiguracao = new cl_rhferiasconfiguracao();
$oDaoFeriasConfiguracao->rotulo->label();

if (isset($oPost->btnSalvar)) {

  try {

    db_inicio_transacao();
    $oDaoFeriasConfiguracao->excluir(null);

    if ($oDaoFeriasConfiguracao->erro_status == 0) {
      throw new BusinessException($oDaoFeriasConfiguracao->erro_msg);
    }

    $oDaoFeriasConfiguracao->rh168_tipoassentamentoabono  = $oPost->rh168_tipoassentamentoabono;
    $oDaoFeriasConfiguracao->rh168_tipoassentamentoferias = $oPost->rh168_tipoassentamentoferias;
    $oDaoFeriasConfiguracao->incluir(null);
    $sMensagem = $oDaoFeriasConfiguracao->erro_msg;
    if ($oDaoFeriasConfiguracao->erro_status == 0) {
      throw new BusinessException($oDaoFeriasConfiguracao->erro_msg);
    }
    db_fim_transacao(false);
  } catch (BusinessException $oErro) {

    db_fim_transacao(true);
    $sMensagem = $oErro->getMessage();
  }
}

$sCampos  = "rh168_tipoassentamentoferias, assentamento_ferias.h12_assent as h12_assent_ferias, ";
$sCampos .= "assentamento_ferias.h12_descr as h12_descricao_ferias, ";
$sCampos .= "rh168_tipoassentamentoabono, assentamento_abono.h12_assent as h12_assent_abono, ";
$sCampos .= "assentamento_abono.h12_descr as h12_descricao_abono,";
$sCampos .= "rh168_ultimoperiodoaquisitivo";

$sSqlTiposAssentamento = $oDaoFeriasConfiguracao->sql_query_tipos(null, $sCampos);
$rsDadosTipoFerias     = $oDaoFeriasConfiguracao->sql_record($sSqlTiposAssentamento);

if ($oDaoFeriasConfiguracao->numrows > 0) {
  db_fieldsmemory($rsDadosTipoFerias, 0);
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("strings.js, prototype.js, estilos.css, ");
    ?>
  </head>
  <body>
    <form id="frmTipoAssentamentoFerias" name="frmTipoAssentamentoFerias" method="post">
      <div class="container">
        <fieldset>
          <legend>Configurações de Férias</legend>
          <table>
            <tr>
              <td>
                <label for="h12_assent_ferias">
                  <?php db_ancora($Lrh168_tipoassentamentoferias, "js_pesquisa_assentamento(true, 'ferias')", 1);?>
                </label>
              </td>
              <td>

                <?php
                  db_input("rh168_tipoassentamentoferias", 10, $Irh168_tipoassentamentoferias, true, 'hidden');
                  db_input("h12_assent", 10, $Ih12_assent, true, 'text', 1, "onchange='js_pesquisa_assentamento(false, \"ferias\")'", "h12_assent_ferias");
                  db_input("h12_descricao_ferias", 30, $Ih12_descr, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="h12_assent_abono">
                  <?php db_ancora($Lrh168_tipoassentamentoabono, "js_pesquisa_assentamento(true, 'abono')", 1);?>
                </label>
              </td>
              <td>
                <?php
                db_input("rh168_tipoassentamentoabono", 10, $Irh168_tipoassentamentoabono, true, 'hidden');
                db_input("h12_assent", 10, $Ih12_assent, true, 'text', 1, "onchange='js_pesquisa_assentamento(false, \"abono\")'", "h12_assent_abono");
                db_input("h12_descricao_abono", 30, $Ih12_descr, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for="rh168_ultimoperiodoaquisitivo"><?=$Lrh168_ultimoperiodoaquisitivo?></label>
              </td>
              <td>

                <?php
                  $aOpcoes = array('f' => 'Não', 't' =>'Sim');
                  db_select("rh168_ultimoperiodoaquisitivo", $aOpcoes, true, 2);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input type="submit" value="Salvar" id="btnSalvar" name="btnSalvar" onclick="return validarFormulario()">
      </div>
    </form>
  </body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>

  function js_pesquisa_assentamento(lMostrarJanela, sTipo) {



    $('btnSalvar').disabled = true;

    /**
     * Variaveis em escopo Global!
     * @type {string}
     */
    sCampoCodigo        = 'rh168_tipoassentamento'+sTipo;
    sCampoDescricao     = 'h12_descricao_'+sTipo;
    sCampoCodigoUsuario = 'h12_assent_'+sTipo;

    if (lMostrarJanela) {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_tipoasse',
                          'func_tipoasse.php?funcao_js=parent.preencherPesquisa|h12_codigo|h12_assent|h12_descr',
                          'Pesquisa Tipo de Assentamentos', true );
    } else {

      if ($F(sCampoCodigoUsuario)!= '') {

        js_OpenJanelaIframe('CurrentWindow.corpo',
                            'db_iframe_tipoasse',
                            'func_tipoasse.php?chave_assent='+$F(sCampoCodigoUsuario)+'&funcao_js=parent.preencherPesquisa',
                            'Pesquisa Tipo de Assentamentos', false );
      } else {

        $('btnSalvar').disabled  = false;
        $(sCampoCodigo).value    = '';
        $(sCampoDescricao).value = '';

      }
    }
  }

  function preencherPesquisa() {

    $('btnSalvar').disabled  = false;
    db_iframe_tipoasse.hide();
    if (typeof(arguments[2]) == 'boolean') {

      $(sCampoDescricao).value = arguments[1];
      if (arguments[2]) {

        $(sCampoCodigo).value        = '';
        $(sCampoCodigoUsuario).value = '';
        $(sCampoCodigoUsuario).focus();
        return false;
      }
      $(sCampoCodigo).value = arguments[3];
      return;
    }
    $(sCampoCodigo).value        = arguments[0];
    $(sCampoCodigoUsuario).value = arguments[1];
    $(sCampoDescricao).value     = arguments[2];
  }

  function validarFormulario() {

    var sCodigoAssentamentoFerias = $F('h12_assent_ferias');
    var sCodigoAssentamentoAbono  = $F('h12_assent_abono');
    if (empty(sCodigoAssentamentoFerias)) {

      alert(_M('recursoshumanos.rh.rec4_parametrosassentamentoferias001.assentamento_ferias_nao_informado'));
      return false;
    }

    if (empty(sCodigoAssentamentoAbono)) {

      alert(_M('recursoshumanos.rh.rec4_parametrosassentamentoferias001.assentamento_abono_nao_informado'));
      return false;
    }
    return true;
  }
</script>
<?php
if (!empty($sMensagem)) {
  db_msgbox($sMensagem);
}