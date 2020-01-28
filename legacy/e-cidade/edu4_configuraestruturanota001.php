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

parse_str($_SERVER['QUERY_STRING']);

$oRotulo = new rotulocampo;
$oRotulo->label("ed139_sequencial");
$oRotulo->label("db77_codestrut");
$oRotulo->label("ed139_ativo");
$oRotulo->label("ed139_arredondamedia");
$oRotulo->label("db77_descr");
$oRotulo->label("ed316_sequencial");
$oRotulo->label("ed316_descricao");
$oRotulo->label("ed139_regraarredondamento");
$oRotulo->label("ed139_observacao");
$oRotulo->label("ed139_ano");

$sLabeBotao = "Incluir";
switch ($db_opcao) {
  case 2:
    $sLabeBotao = "Alterar";
    break;
  case 3:
    $sLabeBotao = "Excluir";
    break;
}

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
        <legend>Estrutural da Nota</legend>
        <table class="form-container">
          <tr>
            <td class="field-size4" title="<?=@$Ted139_db_estrutura?>" >
              <label for="db77_codestrut"><a href="#" id='aconraEstrutural'>Estrutural:</a></label>
            </td>
            <td>
              <?php
                db_input('db77_codestrut', 10, $Idb77_codestrut, true, 'text', $db_opcao);
                db_input('db77_descr',     40, $Idb77_descr,     true, 'text', 3);

                // código sequencial
                db_input('ed139_sequencial', 10, "", false, 'hidden', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Ted139_ativo?>">
              <label for="ed139_ativo"><?=$Led139_ativo?></label>
            </td>
            <td>
              <select id='ed139_ativo'>
                <option value="t">SIM</option>
                <option value="f">NÃO</option>
              </select>
            </td>
          </tr>
          <tr>
            <td title="<?=$Ted139_arredondamedia?>">
              <label for="ed139_arredondamedia"><?=$Led139_arredondamedia?></label>
            </td>
            <td>
              <select id='ed139_arredondamedia'>
                <option value="f">NÃO</option>
                <option value="t">SIM</option>
              </select>
            </td>
          </tr>

          <tr id="ctnRegraArredondamento" style="display: none">
            <td nowrap title="<?=$Ted139_regraarredondamento?>">
              <label for="ed316_sequencial"><a href="#" id='aconraRegra'>Regra de Arredondamento:</a></label>
            </td>
            <td>
              <?php
                db_input('ed316_sequencial', 10, $Ied316_sequencial, true, 'text', $db_opcao);
                db_input('ed316_descricao',  40, $Ied316_descricao,  true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Ted139_ano?>" >
              <label for="ed139_ano">Ano da Configuração:</label>
            </td>
            <td>
              <?php
                $opcaoAno = $db_opcao != 1 ? 3 : 1;
                db_input('ed139_ano', 9, $Ied139_ano, true, 'text', $opcaoAno);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted139_observacao?>" colspan="2">
              <fieldset>
                <legend><b><?=@$Led139_observacao?></b></legend>
                <?php
                  db_textarea('ed139_observacao', 5, 74, $Ied139_observacao, true, 'text', $db_opcao);
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="btnAcao"     id="btnAcao"     value="<?=$sLabeBotao?>" />
      <input type="button" name="btnPesquisa" id="btnPesquisa" value="Pesquisar" />
    </form>
  </div>
<?php
  db_menu();
?>
</body>

<script type="text/javascript">

$('ed139_ano').addClassName('field-size2');

var oGet = js_urlToObject();

var sMSG = 'educacao.secretariaeducacao.edu4_configuraestruturanota001.';
var sRPC = 'edu4_configuracaonota.RPC.php';

var oLookUpEstrutural = new DBLookUp( $('aconraEstrutural'), $('db77_codestrut'), $('db77_descr'),  {
  sArquivo      : 'func_db_estrutura.php',
  sLabel        : 'Pesquisa Estrutural',
  sObjetoLookUp : 'db_iframedb_estrutura'
});

$('ed139_arredondamedia').addEventListener('change', function() {
  liberaCamposParaInformarRegra();
});

function liberaCamposParaInformarRegra() {

  $('ctnRegraArredondamento').style.display = "none";
  $('ed316_sequencial').value = '';
  $('ed316_descricao').value  = '';

  if ( $F('ed139_arredondamedia') == 't' ) {

    $('ctnRegraArredondamento').style.display = "table-row";
  }
}

var oLookUpRegra = new DBLookUp( $('aconraRegra'), $('ed316_sequencial'), $('ed316_descricao'),  {
  sArquivo      : 'func_regraarredondamento.php',
  sLabel        : 'Pesquisa Regra de Arredondamento',
  sObjetoLookUp : 'db_iframe_regraarredondamento',
  sQueryString  : '&pesquisa=E'
});


$('btnAcao').addEventListener('click', function(){

  switch(oGet.db_opcao) {

    case '1':
    case '2':
      salvarConfiguracao();
      break;
    case '3':
      excluirConfiguracao();
    break;
  }
});


function validarDados() {

  if ( empty($F('db77_codestrut')) ) {

    alert( _M(sMSG + 'informe_estrutural_nota' ) );
    return false;
  }

  if ( empty($F('ed139_ano')) ) {

    alert( _M(sMSG + 'informe_ano' ) );
    return false;
  }

  return true;
};

function salvarConfiguracao() {

  if ( !validarDados() ) {
    return;
  }

  var paramentros = {
    exec                 : 'salvarConfiguracaoNotaSecretaria',
    iCodigo              : $F('ed139_sequencial'),
    iEstrutural          : $F('db77_codestrut'),
    lAtivo               : $F('ed139_ativo')          == 't',
    lArredondaMedia      : $F('ed139_arredondamedia') == 't',
    iRegraArredondamento : $F('ed316_sequencial'),
    sRegraArredondamento : $F('ed316_descricao'),
    sObservacao          : $F('ed139_observacao'),
    iAno                 : $F('ed139_ano')
  };

  new AjaxRequest(sRPC, paramentros, function(retorno, erro) {

    alert(retorno.sMessage);
    if (erro) {
      return;
    }

    redireciona();
  }).setMessage( _M(sMSG + 'salvando_configuracao') ).execute();
}

function excluirConfiguracao() {

  var paramentros = {
    exec    : 'excluirConfiguracaoNotaSecretaria',
    iCodigo : $F('ed139_sequencial')
  };

  new AjaxRequest(sRPC, paramentros, function(retorno, erro) {

    alert(retorno.sMessage);
    if (erro) {
      return;
    }
    redireciona();
  }).setMessage( _M(sMSG + "excluindo_configuracao") ).execute();
}

function redireciona() {
  location.href= 'edu4_configuraestruturanota001.php?db_opcao='+oGet.db_opcao;
}

$('btnPesquisa').addEventListener('click',  function() {

  var sUrl = 'func_avaliacaoestruturanotapadrao.php?funcao_js=parent.retornoPesquisa|ed139_sequencial';
  sUrl += '|db_ed139_db_estrutura|dl_estrutural|ed139_ativo|ed139_arredondamedia|db_ed139_regraarredondamento';
  sUrl += '|dl_regra_de_arredondamento|db_ed139_observacao|ed139_ano';

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_avaliacaoestruturanotapadrao', sUrl,
                      'Pesquisa Configuração da Nota', true );
});

function retornoPesquisa() {

  $('ed139_sequencial').value     = arguments[0];
  $('db77_codestrut').value       = arguments[1];
  $('db77_descr').value           = arguments[2];
  $('ed139_ativo').value          = arguments[3];
  $('ed139_arredondamedia').value = arguments[4];
  $('ed139_observacao').value     = arguments[7].replace(/\[#\]/g, "\n");;
  $('ed139_ano').value            = arguments[8];

  liberaCamposParaInformarRegra();

  $('ed316_sequencial').value     = arguments[5];
  $('ed316_descricao').value      = arguments[6];

  db_iframe_avaliacaoestruturanotapadrao.hide();
}

(function(){

  switch(oGet.db_opcao) {

    case '1':

      $('btnPesquisa').setAttribute('disabled', 'disabled');
      break;
    case '2':
      $('btnPesquisa').click();
      break;
    case '3':

      $('ed139_ativo').addClassName('readonly');
      $('ed139_arredondamedia').addClassName('readonly');
      $('ed139_ativo').setAttribute('disabled', 'disabled');
      $('ed139_arredondamedia').setAttribute('disabled', 'disabled');
      $('btnPesquisa').click();
    break;
  }
})();

</script>
</html>
