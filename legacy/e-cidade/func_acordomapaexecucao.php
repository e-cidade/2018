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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_acordo_classe.php"));
define('EXECUCAO_CONTRATO_FINANCEIRA', 1);
define('EXECUCAO_FINANCEIRA_EMPENHO', 2);
$oGet = db_utils::postMemory($_GET);
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoAcordo = new cl_acordo;
$oDaoAcordo->rotulo->label();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js");
db_app::load("dbmessageBoard.widget.js, prototype.js, contratos.classe.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <form name="form2" method="post" action="" >
        <table border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Tac16_sequencial; ?>">
              <?php echo $Lac16_sequencial; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php db_input("ac16_sequencial",10,$Iac16_sequencial,true,"text",4,"","chave_ac16_sequencial"); ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Tac16_numeroacordo; ?>">
              <?php echo $Lac16_numeroacordo; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php db_input("ac16_numeroacordo", 10, 0, true, "text", 4); ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo @$Tac16_acordogrupo; ?>">
              <?php db_ancora(@$Lac16_acordogrupo, "js_pesquisaac16_acordogrupo(true);", 1); ?>
            </td>
            <td>
              <?php
                db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', 1, "onchange='js_pesquisaac16_acordogrupo(false);'");
                db_input('ac02_descricao', 30, "", true, 'text', 3);
              ?>
            </td>
          </tr>

          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordo.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $aWhere = array();

      if (!empty($pesquisa_chave)) {
        $aWhere[] = 'ac16_sequencial = ' . $pesquisa_chave;
      }

      if (!empty($chave_ac16_sequencial)) {
        $aWhere[] = 'ac16_sequencial = ' . $chave_ac16_sequencial;
      }

      if (!empty($ac16_acordogrupo)) {
        $aWhere[] = 'ac16_acordogrupo = ' . $ac16_acordogrupo;
      }

      $sNomeMetodo = 'sql_queryDadosMapaExecucao';
      if ( ! empty($oGet->execucao) && $oGet->execucao == EXECUCAO_FINANCEIRA_EMPENHO) {
        $sNomeMetodo = 'sql_query_movimentacao_empenho';
      }

      /**
       * Numero e ano do acordo - separados por '/', caso nao for informado ano, pega da sessao
       */
      if (!empty($ac16_numeroacordo)) {

        $aNumeroAcordo = explode('/', $ac16_numeroacordo);
        $iNumero = $aNumeroAcordo[0];
        $iAno = !empty($aNumeroAcordo[1]) ? $aNumeroAcordo[1] : db_getsession("DB_anousu");

        $aWhere[] = "ac16_numeroacordo = $iNumero";
        $aWhere[] = "ac16_anousu = $iAno";
      }

      $aWhere[] = 'ac16_instit = ' . db_getsession('DB_instit');
      $sWhere = implode(' and ', $aWhere);

      /**
       * Exibe grid para escolha do acordo
       * - usado ao clicar no ancora
       */
      if (empty($pesquisa_chave)) {

        $sCampos  = "distinct acordo.ac16_sequencial, ";
        $sCampos .= "(ac16_numeroacordo || '/' || ac16_anousu)::varchar as ac16_numeroacordo, ";
        $sCampos .= "ac17_descricao as dl_Situação, ";
        $sCampos .= "acordo.ac16_coddepto, ";
        $sCampos .= "descrdepto, ";
        $sCampos .= "acordo.ac16_numero, ";
        $sCampos .= "acordo.ac16_dataassinatura, ";
        $sCampos .= "acordo.ac16_contratado, ";
        $sCampos .= "acordo.ac16_datainicio, ";
        $sCampos .= "acordo.ac16_datafim, ";
        $sCampos .= "acordo.ac16_resumoobjeto::text,";
        $sCampos .= "ac28_descricao as dl_Origem";

        $repassa = array();
        $sSql = $oDaoAcordo->$sNomeMetodo(null, $sCampos, 'ac16_sequencial', $sWhere);

        if (isset($chave_ac16_sequencial)) {
          $repassa = array("chave_ac16_sequencial"=>$chave_ac16_sequencial,"chave_ac16_sequencial"=>$chave_ac16_sequencial);
        }

        db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      }

      /**
       * Pesquisa pelo código do acordo
       * - usado ao disparar evento change do input
       */
      else {

        $sSqlAcordo = $oDaoAcordo->$sNomeMetodo(null, "*", null, $sWhere);
        $rsAcordo   = $oDaoAcordo->sql_record($sSqlAcordo);

        if ($oDaoAcordo->numrows > 0) {

          db_fieldsmemory($rsAcordo, 0);
          echo "<script>".$funcao_js."('$ac16_resumoobjeto', false);</script>";

        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?php
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>

/**
 * Formata numero / ano de um elemento html
 *
 * @param {object} elemento
 * @returns {void}
 */
function js_formatarNumeroAno(elemento) {

  /**
   * Formata a cada tecla digitada
   *
   * @param {object} elemento
   * @returns {boolean}
   */
  elemento.onkeypress = function(event) {

    var iTecla = event.keyCode ? event.keyCode : event.charCode;
    var sTecla = String.fromCharCode(iTecla);

    /**
     * Nao permite por 2x '/' ou como primeiro caracter
     */
    if (sTecla == '/') {

      if (this.value.indexOf('/') !== -1 || this.value == '') {
        return false;
      }
    }

    return js_mask(event, "0-9|/");
  };
}

js_formatarNumeroAno(document.getElementById('ac16_numeroacordo'));

js_tabulacaoforms("form2","chave_ac16_sequencial",true,1,"chave_ac16_sequencial",true);

function js_pesquisaac16_acordogrupo(mostra) {

	  if (mostra == true) {

	    var sUrl = 'func_acordogrupo.php?funcao_js=parent.js_mostraacordogrupo1|ac02_sequencial|ac02_descricao';
	    js_OpenJanelaIframe('',
	                        'db_iframe_pesquisagrupo',
	                        sUrl,
	                        'Pesquisar Grupos de Acordo',
	                        true,
	                        '0');
	  } else {

	    if ($('ac16_acordogrupo').value != '') {

	      js_OpenJanelaIframe('',
	                          'db_iframe_pesquisagrupo',
	                          'func_acordogrupo.php?pesquisa_chave='+$('ac16_acordogrupo').value+
	                          '&funcao_js=parent.js_mostraacordogrupo',
	                          'Pesquisar Grupos de Acordo',
	                          false,
	                          '0');
	     } else {
	       $('ac02_sequencial').value = '';
	     }
	  }
	}

	function js_mostraacordogrupo(chave,erro) {

	  $('ac02_descricao').value = chave;
	  if (erro == true) {

	    $('ac16_acordogrupo').focus();
	    $('ac16_acordogrupo').value = '';
	  }
	}

	function js_mostraacordogrupo1(chave1,chave2) {

	  $('ac16_acordogrupo').value = chave1;
	  $('ac02_descricao').value   = chave2;
	  $('ac16_acordogrupo').focus();

	  db_iframe_pesquisagrupo.hide();
	}
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
