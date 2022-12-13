<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

$oDaPontoeletronicoconfiguracoeslotacao = new cl_pontoeletronicoconfiguracoeslotacao;
$oRotulo = new Rotulo('pontoeletronicoconfiguracoeslotacao');
$oRotulo->label('rh195_lotacao');
$oRotulo->label('rh195_tolerancia');
$oRotulo->label('rh195_hora_extra_50');
$oRotulo->label('rh195_hora_extra_75');
$oRotulo->label('rh195_hora_extra_100');
$oRotulo->label('rh195_supervisor');

$db_opcao = 1;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js");
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
  db_app::load("widgets/DBLookUp.widget.js");
  db_app::load("widgets/DBInputHora.widget.js");
  ?>
  <style type="text/css">
  #obsTolerancia {
    font-style: italic;
    padding: 3px;
    background: #FFF;
    border-radius: 2px;
  }
  </style>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Tolerância e Horas extras</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?php echo $Trh195_lotacao; ?>">
            <label id="lbl_rh195_lotacao" for="rh195_lotacao">
              <a href="#"><?php echo $Lrh195_lotacao; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh195_lotacao',     10, $Irh195_lotacao, true, "text", $db_opcao, 'class="field-size2" data="r70_codigo"'); ?>
            <?php db_input('descricao_lotacao', 30, $Irh195_lotacao, true, "text", 3,         'class="field-size8" data="r70_descr"'); ?>
          </td>
        </tr>
        
        <tr>
          <td nowrap title="<?php echo $Trh195_tolerancia; ?>">
            <label id="lbl_rh195_tolerancia" for="rh195_tolerancia"><?php echo $Lrh195_tolerancia; ?></label>
          </td>
          <td>
            <?php db_input('rh195_tolerancia', 10, $Irh195_tolerancia, true, "text", $db_opcao, 'class="field-size2"'); ?>
            <span id="obsTolerancia">Preencher em minutos</span>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh195_hora_extra_50; ?>">
            <label id="lbl_rh195_hora_extra_50" for="rh195_hora_extra_50"><?php echo $Lrh195_hora_extra_50; ?></label>
          </td>
          <td><?php db_input('rh195_hora_extra_50', 10, $Irh195_hora_extra_50, true, "text", $db_opcao, 'class="field-size2"'); ?></td>
        </tr>
        
        <tr>
          <td nowrap title="<?php echo $Trh195_hora_extra_75; ?>">
            <label id="lbl_rh195_hora_extra_75" for="rh195_hora_extra_75"><?php echo $Lrh195_hora_extra_75; ?></label>
          </td>
          <td>
            <?php db_input('rh195_hora_extra_75', 10, $Irh195_hora_extra_75, true, "text", $db_opcao, 'class="field-size2"'); ?>
            <span id="obsTolerancia">Consideradas a partir do fechamento das H.E. 50%</span>
          </td>
        </tr>
        
        <tr>
          <td nowrap title="<?php echo $Trh195_hora_extra_100; ?>">
            <label id="lbl_rh195_hora_extra_100" for="rh195_hora_extra_100"><?php echo $Lrh195_hora_extra_100; ?></label>
          </td>
          <td>
            <?php db_input('rh195_hora_extra_100', 10, $Irh195_hora_extra_100, true, "text", $db_opcao, 'class="field-size2"'); ?>
            <span id="obsTolerancia">Consideradas a partir do fechamento das H.E. 50% e H.E. 75%</span>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh195_supervisor; ?>">
            <label id="lbl_rh195_supervisor" for="rh195_supervisor">
              <a href="#"><?php echo $Lrh195_supervisor; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh195_supervisor', 10, $Irh195_supervisor, true, "text", $db_opcao, 'data="rh01_regist" class="field-size2"'); ?>
            <?php db_input('nome_supervisor', 30, $Irh195_supervisor, true, "text", 3, 'data="z01_nome" class="field-size8"'); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Nova" id="nova" />
    <input type="button" value="Salvar"    onclick="salvarConfiguracoes()" />
    <input type="button" value="Pesquisar" onclick="pesquisaVinculos()" />
  </form>
</div>
<script type="text/javascript">

var inputHoraExtra50  = new DBInputHora($('rh195_hora_extra_50'));
var inputHoraExtra75  = new DBInputHora($('rh195_hora_extra_75'));
var inputHoraExtra100 = new DBInputHora($('rh195_hora_extra_100'));

var lookupLotacao  = new DBLookUp(
  $('lbl_rh195_lotacao'),
  $('rh195_lotacao'),
  $('descricao_lotacao'),
  {
    'sArquivo'    : 'func_rhlota.php',
    'sLabel'      : 'Pesquisar Lotações',
    fCallBack     : function () {
      carregarConfiguracoes();
    }
  }
);

var lookupSupervisor  = new DBLookUp(
  $('lbl_rh195_supervisor'),
  $('rh195_supervisor'),
  $('nome_supervisor'),
  {
    'sArquivo'   : 'func_rhpessoal.php',
    'sLabel'     : 'Pesquisar Servidores'
  }
);

$('rh195_lotacao').observe('blur', function () {
  if(this.value.trim() == '') {
    limpar();
  }
});

$('nova').observe('click', function() {

  $('rh195_lotacao').value     = '';
  $('descricao_lotacao').value = '';

  limpar();
});

function limpar () {

  $('rh195_tolerancia').value      = '';
  $('rh195_hora_extra_50').value   = '';
  $('rh195_hora_extra_75').value   = '';
  $('rh195_hora_extra_100').value  = '';
  $('rh195_supervisor').value      = '';
  $('nome_supervisor').value       = '';
  return;
}

function carregarConfiguracoes () {
  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      exec           : 'getConfiguracoesLotacaoPorLotacao',
      iCodigoLotacao : $F('rh195_lotacao')
    },
    function (retorno, erro) {
      
      if(retorno.mensagem) {
        alert(retorno.mensagem);
      }

      if(erro) {
        return;
      }

      $('rh195_tolerancia').value     = retorno.configuracoes.rh195_tolerancia;
      $('rh195_hora_extra_50').value  = retorno.configuracoes.rh195_hora_extra_50;
      $('rh195_hora_extra_75').value  = retorno.configuracoes.rh195_hora_extra_75;
      $('rh195_hora_extra_100').value = retorno.configuracoes.rh195_hora_extra_100;
      $('rh195_supervisor').value     = retorno.configuracoes.rh195_supervisor;
      $('nome_supervisor').value      = retorno.configuracoes.nome_supervisor;
    }
  ).setMessage('Buscando configurações...').execute();
}

function salvarConfiguracoes () {

  if($('rh195_lotacao').value.trim() == '') {
    alert('Informe a Lotação.');
    return;
  }

  if($('rh195_tolerancia').value.trim() == '') {
    alert('Informe a Tolerância.');
    return;
  }

  if($('rh195_tolerancia').value > 10) {
    alert('Tolerância não pode ser maior que 10 minutos.');
    return;
  }


  if($('rh195_hora_extra_50').value.trim() == '') {
    alert('Informe o tempo para Horas Extra 50%.');
    return;
  }

  if($('rh195_supervisor').value.trim() == '') {
    alert('Informe o supervisor.');
    return;
  }


  AjaxRequest.create(
    'rec4_pontoeletronicoconfiguracoes.RPC.php',
    {
      exec                  : 'salvarConfiguracoesLotacao',
      rh195_lotacao         : $F('rh195_lotacao'),
      rh195_tolerancia      : $F('rh195_tolerancia'),
      rh195_hora_extra_50   : $F('rh195_hora_extra_50'),
      rh195_hora_extra_75   : $F('rh195_hora_extra_75'),
      rh195_hora_extra_100  : $F('rh195_hora_extra_100'),
      rh195_supervisor      : $F('rh195_supervisor')
    },
    function (retorno, erro) {
      
      if(retorno.mensagem) {
        alert(retorno.mensagem);
      }

      if(erro) {
        return;
      }
    }
  ).setMessage('Salvando configurações...').execute();
}

function pesquisaVinculos() {

  var sUrl  = 'func_pontoeletronicoconfiguracoeslotacao.php?funcao_js=parent.retornoPesquisaVinculos';
      sUrl += '|r70_codigo|r70_descr';

  js_OpenJanelaIframe(
    '',
    'db_iframe_pontoeletronicoconfiguracoeslotacao',
    sUrl,
    'Pesquisa Lotações',
    true
  );
}

function retornoPesquisaVinculos(iCodigoLotacao, sDescricaoLotacao) {

  db_iframe_pontoeletronicoconfiguracoeslotacao.hide();

  $('rh195_lotacao').value     = iCodigoLotacao;
  $('descricao_lotacao').value = sDescricaoLotacao;

  carregarConfiguracoes();
}
</script>
</body>
</html>
