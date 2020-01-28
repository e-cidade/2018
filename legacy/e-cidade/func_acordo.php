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
$lAtivo      = '';
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clacordo = new cl_acordo;
$clacordo->rotulo->label();
$iInstituicaoSessao = db_getsession('DB_instit');
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">

</head>
<body style="background-color: #CCCCCC">

<div class="container">
  <form name="form2" method="post" action="" >
    <fieldset>
      <legend class="bold">Filtros</legend>

      <table border="0" align="center" cellspacing="0">
        <tr>
          <td width="4%" align="left" nowrap title="<?php echo $Tac16_sequencial; ?>">
            <?php echo $Lac16_sequencial; ?>
          </td>
          <td width="96%" align="left" nowrap>
            <?php
            db_input("ac16_sequencial",10,$Iac16_sequencial,true,"text",4,"","chave_ac16_sequencial");
            ?>
          </td>
        </tr>

        <tr>
          <td width="4%" align="left" nowrap title="<?php echo $Tac16_numeroacordo; ?>" class="bold">
            Número do Acordo:
          </td>
          <td width="96%" align="left" nowrap>
            <?php db_input("ac16_numeroacordo", 10, 0, true, "text", 4); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo @$Tac16_acordogrupo; ?>" class="bold" align="">
            <?php
            db_ancora("Grupo:", "js_pesquisaac16_acordogrupo(true);", 1);
            ?>
          </td>
          <td>
            <?php
            db_input('ac16_acordogrupo', 10, $Iac16_acordogrupo, true, 'text', 1, "onchange='js_pesquisaac16_acordogrupo(false);'");
            db_input('ac02_descricao', 30, "", true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>

    </fieldset>
    <p align="center">
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="reset" id="limpar" value="Limpar" >
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_acordo.hide();">
    </p>

  </form>
</div>

<fieldset style="width: 98%">
  <legend class="bold">Registros</legend>



  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td align="center" valign="top">
        <?php

        $sWhere  = " 1 = 1 ";

        $sWhere .= " and ac16_instit = {$iInstituicaoSessao} ";
        if (!isset($lNovoDetalhe)) {

          if (!isset($lDepartamento)) {

            $sDepartamentos = "( ac16_coddepto = ".db_getsession("DB_coddepto"). " or ac16_deptoresponsavel = ".db_getsession("DB_coddepto")." )";
            $sWhere .= " and {$sDepartamentos} ";
          }

          if (isset($iTipoFiltro)) {
            $sWhere .= " and ac16_acordosituacao in ({$iTipoFiltro})";
          }

          if ( !empty($lLancamento) ) {
            $sWhere .= " and exists (select 1 from conlancamacordo where c87_acordo = ac16_sequencial limit 1) ";
          }

          if ( isset($lNaoTrazerLicitacaoNaoGeraDespesa) ) {

            $sSqlLicitacao  = " select 1 ";
            $sSqlLicitacao .= "   from acordoliclicitem ";
            $sSqlLicitacao .= "   join liclicitem on liclicitem.l21_codigo = acordoliclicitem.ac24_liclicitem ";
            $sSqlLicitacao .= "   join liclicita  on liclicita.l20_codigo  = liclicitem.l21_codliclicita ";
            $sSqlLicitacao .= "  where acordoliclicitem.ac24_acordoitem = acordoitem.ac20_sequencial ";
            $sSqlLicitacao .= "    and l20_tipo = 2";

            $sWhere        .= " and ( ( ac16_origem = 2 and not exists({$sSqlLicitacao})) or ac16_origem IN(1, 6) )";
          }

          /**
           * Caso tenha sido setado $lComExecucao como false, buscamos os acordos que nao tiveram item executado
           */
          if (isset($lComExecucao) && $lComExecucao == 'false') {
//            $sWhere .= " and not exists (select 1 from acordoitemexecutado where ac29_acordoitem = acordoitem.ac20_sequencial)";
          }

          if (isset($lAtivo)) {

            if ($lAtivo == 1) {
              $sWhere .= " and ac17_ativo is true";
            } else if ($lAtivo == 2) {
              $sWhere .= " and ac17_ativo is false";
            }
          }

          if (isset($lGeraAutorizacao) && $lGeraAutorizacao == "true") {
            $sWhere .= " and ac16_origem in(1, 2, 6) ";
          }

          if (isset($sListaOrigens) && !empty($sListaOrigens)) {
            $sWhere .= " and ac16_origem in({$sListaOrigens}) ";
          }
        }

        if (!isset($pesquisa_chave)) {

          if (isset($campos) == false) {

            if (file_exists("funcoes/db_func_acordo.php") == true) {

              $campos  = "distinct acordo.ac16_sequencial, ";
              $campos .= "(ac16_numeroacordo || '/' || ac16_anousu)::varchar as ac16_numeroacordo, ";
              $campos .= "ac17_descricao as dl_Situação, ";
              $campos .= "acordo.ac16_coddepto, ";
              $campos .= "descrdepto, ";
              $campos .= "codigo, ";
              $campos .= "nomeinst, ";
              $campos .= "acordo.ac16_numero, ";
              $campos .= "acordo.ac16_dataassinatura, ";
              $campos .= "acordo.ac16_contratado, ";
              $campos .= "acordo.ac16_datainicio, ";
              $campos .= "acordo.ac16_datafim, ";
              $campos .= "acordo.ac16_resumoobjeto::text, ";
              $campos .= "ac28_descricao as dl_Origem";

            } else {
              $campos = "acordo.*";
            }
          }

          /**
           * Numero e ano do acordo - separados por '/', caso nao for informado ano, pega da sessao
           */
          if (!empty($ac16_numeroacordo)) {

            $aNumeroAcordo = explode('/', $ac16_numeroacordo);
            $iNumero = $aNumeroAcordo[0];
            $iAno    = !empty($aNumeroAcordo[1]) ? $aNumeroAcordo[1] : db_getsession("DB_anousu");
            $sWhere  = "ac16_numeroacordo = {$iNumero} and ac16_anousu = {$iAno} and ac16_instit = {$iInstituicaoSessao} and {$sDepartamentos}";
          }

          if (isset($chave_ac16_sequencial) && (trim($chave_ac16_sequencial)!="")) {

            $sql = $clacordo->sql_query_acordoitemexecutado(null, $campos,"ac16_sequencial",
                                                            "ac16_sequencial = {$chave_ac16_sequencial} and $sWhere and ac16_instit = {$iInstituicaoSessao}");

          } else if (isset($ac16_acordogrupo) && (trim($ac16_acordogrupo)!="")) {

            $sql = $clacordo->sql_query_acordoitemexecutado("",$campos,"ac16_sequencial",
                                                            "ac16_acordogrupo = '{$ac16_acordogrupo}' and {$sWhere} and ac16_instit = {$iInstituicaoSessao}");
          } else {
            $sql = $clacordo->sql_query_acordoitemexecutado("",$campos,"ac16_sequencial", $sWhere . " and ac16_instit = {$iInstituicaoSessao} " );
          }

          $repassa = array();

          if (isset($chave_ac16_sequencial)) {
            $repassa = array("chave_ac16_sequencial"=>$chave_ac16_sequencial,"chave_ac16_sequencial"=>$chave_ac16_sequencial);
          }

          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        } else {


          if ($pesquisa_chave != null && $pesquisa_chave != "") {

            $sSqlBuscaAcordo = $clacordo->sql_query_acordoitemexecutado(null,
                                                                        "*",
                                                                        null,
                                                                        "ac16_sequencial = {$pesquisa_chave}
                                                                       and {$sWhere}");


            $result = $clacordo->sql_record($sSqlBuscaAcordo);

            if ($clacordo->numrows != 0) {

              db_fieldsmemory($result,0);
              if (isset($descricao) && $descricao == 'true') {
                echo "<script>".$funcao_js."($ac16_sequencial,'$ac16_resumoobjeto',false);</script>";
              } else {
                echo "<script>".$funcao_js."('$ac16_sequencial',false);</script>";
              }
            } else {

              if (isset($descricao) && $descricao == 'true') {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            }
          } else {

            if (isset($descricao) && $descricao == 'true') {
              echo "<script>".$funcao_js."('','',false);</script>";
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
            }
          }
        }
        ?>
      </td>
    </tr>
  </table>
</fieldset>
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
        'db_iframe_acordogrupo',
        sUrl,
        'Pesquisa de Grupo de Acordo',
        true,
        '0');

    } else {

      if ($('ac16_acordogrupo').value != '') {

        js_OpenJanelaIframe('',
          'db_iframe_acordogrupo',
          'func_acordogrupo.php?pesquisa_chave='+$('ac16_acordogrupo').value+
          '&funcao_js=parent.js_mostraacordogrupo',
          'Pesquisa de Grupo de Acordo',
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

    db_iframe_acordogrupo.hide();
  }
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
