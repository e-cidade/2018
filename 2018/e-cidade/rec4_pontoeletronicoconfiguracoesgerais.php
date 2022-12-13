<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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

$oDaPontoeletronicoconfiguracoesgerais = new cl_pontoeletronicoconfiguracoesgerais;
$oDaPontoeletronicoconfiguracoesgerais->rotulo->label();

$db_opcao                = 1;
$rh200_autorizahoraextra = 't';

$iCodigoInstituicao         = db_getsession('DB_instit');
$sSqlConfiguracoesGerais    = $oDaPontoeletronicoconfiguracoesgerais->sql_query_configuracoes(null, "rh200_instituicao = {$iCodigoInstituicao}");
$rsSqlConfiguracoesGerais   = db_query($sSqlConfiguracoesGerais);

if(!$rsSqlConfiguracoesGerais) {
  throw new DBException("Ocorreu um erro ao buscar as configurações gerais para a instituição.");
}

$iQtdeConfiguracoesGerais = pg_num_rows($rsSqlConfiguracoesGerais);

if($iQtdeConfiguracoesGerais > 0) {

  $aConfiguracoes = array(
    'rh200_tipoasse_extra50diurna'    => null,
    'rh200_tipoasse_extra75diurna'    => null,
    'rh200_tipoasse_extra100diurna'   => null,
    'rh200_tipoasse_extra50noturna'   => null,
    'rh200_tipoasse_extra75noturna'   => null,
    'rh200_tipoasse_extra100noturna'  => null,
    'rh200_tipoasse_adicionalnoturno' => null,
    'rh200_tipoasse_falta'            => null,
    'rh200_tipoasse_faltas_dsr'       => null
  );

  for ($iConfiguracoesGerais=0; $iConfiguracoesGerais < $iQtdeConfiguracoesGerais; $iConfiguracoesGerais++) {

    $oConfiguracoes = db_utils::makeFromRecord($rsSqlConfiguracoesGerais, function ($oRetorno) {
      return $oRetorno;
    }, $iConfiguracoesGerais);

    $aConfiguracoes[$oConfiguracoes->tipo] = $oConfiguracoes;

    if(!empty($oConfiguracoes->rh200_autorizahoraextra)) {
      $rh200_autorizahoraextra = $oConfiguracoes->rh200_autorizahoraextra;
    }
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra50diurna'])) {
    $rh200_tipoasse_extra50diurna               = $aConfiguracoes['rh200_tipoasse_extra50diurna']->codigo;
    $rh200_tipoasse_extra50diurna_h12_assent    = $aConfiguracoes['rh200_tipoasse_extra50diurna']->h12_assent;
    $rh200_tipoasse_extra50diurna_h12_descr     = $aConfiguracoes['rh200_tipoasse_extra50diurna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra75diurna'])) {
    $rh200_tipoasse_extra75diurna               = $aConfiguracoes['rh200_tipoasse_extra75diurna']->codigo;
    $rh200_tipoasse_extra75diurna_h12_assent    = $aConfiguracoes['rh200_tipoasse_extra75diurna']->h12_assent;
    $rh200_tipoasse_extra75diurna_h12_descr     = $aConfiguracoes['rh200_tipoasse_extra75diurna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra100diurna'])) {
    $rh200_tipoasse_extra100diurna              = $aConfiguracoes['rh200_tipoasse_extra100diurna']->codigo;
    $rh200_tipoasse_extra100diurna_h12_assent   = $aConfiguracoes['rh200_tipoasse_extra100diurna']->h12_assent;
    $rh200_tipoasse_extra100diurna_h12_descr    = $aConfiguracoes['rh200_tipoasse_extra100diurna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra50noturna'])) {
    $rh200_tipoasse_extra50noturna              = $aConfiguracoes['rh200_tipoasse_extra50noturna']->codigo;
    $rh200_tipoasse_extra50noturna_h12_assent   = $aConfiguracoes['rh200_tipoasse_extra50noturna']->h12_assent;
    $rh200_tipoasse_extra50noturna_h12_descr    = $aConfiguracoes['rh200_tipoasse_extra50noturna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra75noturna'])) {
    $rh200_tipoasse_extra75noturna              = $aConfiguracoes['rh200_tipoasse_extra75noturna']->codigo;
    $rh200_tipoasse_extra75noturna_h12_assent   = $aConfiguracoes['rh200_tipoasse_extra75noturna']->h12_assent;
    $rh200_tipoasse_extra75noturna_h12_descr    = $aConfiguracoes['rh200_tipoasse_extra75noturna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_extra100noturna'])) {
    $rh200_tipoasse_extra100noturna             = $aConfiguracoes['rh200_tipoasse_extra100noturna']->codigo;
    $rh200_tipoasse_extra100noturna_h12_assent  = $aConfiguracoes['rh200_tipoasse_extra100noturna']->h12_assent;
    $rh200_tipoasse_extra100noturna_h12_descr   = $aConfiguracoes['rh200_tipoasse_extra100noturna']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_adicionalnoturno'])) {
    $rh200_tipoasse_adicionalnoturno            = $aConfiguracoes['rh200_tipoasse_adicionalnoturno']->codigo;
    $rh200_tipoasse_adicionalnoturno_h12_assent = $aConfiguracoes['rh200_tipoasse_adicionalnoturno']->h12_assent;
    $rh200_tipoasse_adicionalnoturno_h12_descr  = $aConfiguracoes['rh200_tipoasse_adicionalnoturno']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_falta'])) {
    $rh200_tipoasse_falta                       = $aConfiguracoes['rh200_tipoasse_falta']->codigo;
    $rh200_tipoasse_falta_h12_assent            = $aConfiguracoes['rh200_tipoasse_falta']->h12_assent;
    $rh200_tipoasse_falta_h12_descr             = $aConfiguracoes['rh200_tipoasse_falta']->h12_descr;
  }

  if(!empty($aConfiguracoes['rh200_tipoasse_faltas_dsr'])) {
    $rh200_tipoasse_faltas_dsr                  = $aConfiguracoes['rh200_tipoasse_faltas_dsr']->codigo;
    $rh200_tipoasse_faltas_dsr_h12_assent       = $aConfiguracoes['rh200_tipoasse_faltas_dsr']->h12_assent;
    $rh200_tipoasse_faltas_dsr_h12_descr        = $aConfiguracoes['rh200_tipoasse_faltas_dsr']->h12_descr;
  }
}
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
  ?>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Tipos de Assentamentos de Efetividade</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra50diurna; ?>">
            <label id="lbl_rh200_tipoasse_extra50diurna" for="rh200_tipoasse_extra50diurna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra50diurna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra50diurna', 10, $Irh200_tipoasse_extra50diurna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra50diurna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra50diurna_h12_descr', 10, $Irh200_tipoasse_extra50diurna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra75diurna; ?>">
            <label id="lbl_rh200_tipoasse_extra75diurna" for="rh200_tipoasse_extra75diurna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra75diurna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra75diurna', 10, $Irh200_tipoasse_extra75diurna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra75diurna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra75diurna_h12_descr', 10, $Irh200_tipoasse_extra75diurna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra100diurna; ?>">
            <label id="lbl_rh200_tipoasse_extra100diurna" for="rh200_tipoasse_extra100diurna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra100diurna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra100diurna', 10, $Irh200_tipoasse_extra100diurna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra100diurna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra100diurna_h12_descr', 10, $Irh200_tipoasse_extra100diurna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra50noturna; ?>">
            <label id="lbl_rh200_tipoasse_extra50noturna" for="rh200_tipoasse_extra50noturna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra50noturna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra50noturna', 10, $Irh200_tipoasse_extra50noturna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra50noturna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra50noturna_h12_descr', 10, $Irh200_tipoasse_extra50noturna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra75noturna; ?>">
            <label id="lbl_rh200_tipoasse_extra75noturna" for="rh200_tipoasse_extra75noturna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra75noturna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra75noturna', 10, $Irh200_tipoasse_extra75noturna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra75noturna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra75noturna_h12_descr', 10, $Irh200_tipoasse_extra75noturna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_extra100noturna; ?>">
            <label id="lbl_rh200_tipoasse_extra100noturna" for="rh200_tipoasse_extra100noturna_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_extra100noturna; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_extra100noturna', 10, $Irh200_tipoasse_extra100noturna, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_extra100noturna_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_extra100noturna_h12_descr', 10, $Irh200_tipoasse_extra100noturna, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_adicionalnoturno; ?>">
            <label id="lbl_rh200_tipoasse_adicionalnoturno" for="rh200_tipoasse_adicionalnoturno_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_adicionalnoturno; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_adicionalnoturno', 10, $Irh200_tipoasse_adicionalnoturno, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_adicionalnoturno_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_adicionalnoturno_h12_descr', 10, $Irh200_tipoasse_adicionalnoturno, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_falta; ?>">
            <label id="lbl_rh200_tipoasse_falta" for="rh200_tipoasse_falta_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_falta; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_falta', 10, $Irh200_tipoasse_falta, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_falta_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_falta_h12_descr', 10, $Irh200_tipoasse_falta, true, "text", 3); ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Trh200_tipoasse_faltas_dsr; ?>">
            <label id="lbl_rh200_tipoasse_faltas_dsr" for="rh200_tipoasse_faltas_dsr_h12_assent">
              <a href="#"><?php echo $Lrh200_tipoasse_faltas_dsr; ?></a>
            </label>
          </td>
          <td>
            <?php db_input('rh200_tipoasse_faltas_dsr', 10, $Irh200_tipoasse_faltas_dsr, true, "hidden", 3, 'class="codigo-tipoasse"'); ?>
            <?php db_input('rh200_tipoasse_faltas_dsr_h12_assent', 10, '', true, "text", $db_opcao); ?>
            <?php db_input('rh200_tipoasse_faltas_dsr_h12_descr', 10, $Irh200_tipoasse_faltas_dsr, true, "text", 3); ?>
          </td>
        </tr>
        <?php
        ?>
      </table>
    </fieldset>

    <fieldset>
      <legend>Parâmetros</legend>

      <table class="form-container">
        <tr>
          <td class="field-size2">
            <label for="rh200_autorizahoraextra">
              <?=$Lrh200_autorizahoraextra;?>
          </td>
          <td>
            <select id="rh200_autorizahoraextra">
              <option value="t" selected>SIM</option>
              <option value="f">NÃO</option>
            </select>
          </td>
        </tr>
      </table>

    </fieldset>

    <input type="button" value="Salvar" onclick="salvarConfiguracoes()" />
  </form>
</div>
<script type="text/javascript">

  $('rh200_autorizahoraextra').value = '<?=$rh200_autorizahoraextra;?>';

  var lookupExtra50diurna    = new DBLookUp(
    $('lbl_rh200_tipoasse_extra50diurna'),
    $('rh200_tipoasse_extra50diurna_h12_assent'),
    $('rh200_tipoasse_extra50diurna_h12_descr'),
    {
      'sArquivo'              : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'                : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra50diurna').value            = '';
        $('rh200_tipoasse_extra50diurna_h12_assent').value = '';
        $('rh200_tipoasse_extra50diurna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra50diurna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra50diurna').value            = parametros.id;
        $('rh200_tipoasse_extra50diurna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra50diurna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupExtra75diurna    = new DBLookUp(
    $('lbl_rh200_tipoasse_extra75diurna'),
    $('rh200_tipoasse_extra75diurna_h12_assent'),
    $('rh200_tipoasse_extra75diurna_h12_descr'),
    {
      'sArquivo'              : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'                : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra75diurna').value            = '';
        $('rh200_tipoasse_extra75diurna_h12_assent').value = '';
        $('rh200_tipoasse_extra75diurna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra75diurna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra75diurna').value            = parametros.id;
        $('rh200_tipoasse_extra75diurna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra75diurna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupExtra100diurna   = new DBLookUp(
    $('lbl_rh200_tipoasse_extra100diurna'),
    $('rh200_tipoasse_extra100diurna_h12_assent'),
    $('rh200_tipoasse_extra100diurna_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra100diurna').value            = '';
        $('rh200_tipoasse_extra100diurna_h12_assent').value = '';
        $('rh200_tipoasse_extra100diurna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra100diurna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra100diurna').value            = parametros.id;
        $('rh200_tipoasse_extra100diurna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra100diurna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupExtra50noturna   = new DBLookUp(
    $('lbl_rh200_tipoasse_extra50noturna'),
    $('rh200_tipoasse_extra50noturna_h12_assent'),
    $('rh200_tipoasse_extra50noturna_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra50noturna').value            = '';
        $('rh200_tipoasse_extra50noturna_h12_assent').value = '';
        $('rh200_tipoasse_extra50noturna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra50noturna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra50noturna').value            = parametros.id;
        $('rh200_tipoasse_extra50noturna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra50noturna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupExtra75noturna   = new DBLookUp(
    $('lbl_rh200_tipoasse_extra75noturna'),
    $('rh200_tipoasse_extra75noturna_h12_assent'),
    $('rh200_tipoasse_extra75noturna_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra75noturna').value            = '';
        $('rh200_tipoasse_extra75noturna_h12_assent').value = '';
        $('rh200_tipoasse_extra75noturna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra75noturna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra75noturna').value            = parametros.id;
        $('rh200_tipoasse_extra75noturna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra75noturna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupExtra100_noturna = new DBLookUp(
    $('lbl_rh200_tipoasse_extra100noturna'),
    $('rh200_tipoasse_extra100noturna_h12_assent'),
    $('rh200_tipoasse_extra100noturna_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_extra100noturna').value            = '';
        $('rh200_tipoasse_extra100noturna_h12_assent').value = '';
        $('rh200_tipoasse_extra100noturna_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_extra100noturna_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_extra100noturna').value            = parametros.id;
        $('rh200_tipoasse_extra100noturna_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_extra100noturna_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupAdicionalnoturno = new DBLookUp(
    $('lbl_rh200_tipoasse_adicionalnoturno'),
    $('rh200_tipoasse_adicionalnoturno_h12_assent'),
    $('rh200_tipoasse_adicionalnoturno_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_adicionalnoturno').value            = '';
        $('rh200_tipoasse_adicionalnoturno_h12_assent').value = '';
        $('rh200_tipoasse_adicionalnoturno_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_adicionalnoturno_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_adicionalnoturno').value            = parametros.id;
        $('rh200_tipoasse_adicionalnoturno_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_adicionalnoturno_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupFalta            = new DBLookUp(
    $('lbl_rh200_tipoasse_falta'),
    $('rh200_tipoasse_falta_h12_assent'),
    $('rh200_tipoasse_falta_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_falta').value            = '';
        $('rh200_tipoasse_falta_h12_assent').value = '';
        $('rh200_tipoasse_falta_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_falta_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_falta').value            = parametros.id;
        $('rh200_tipoasse_falta_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_falta_h12_descr').value  = parametros.descricao;
      }
    }
  );

  var lookupFaltaDSR         = new DBLookUp(
    $('lbl_rh200_tipoasse_faltas_dsr'),
    $('rh200_tipoasse_faltas_dsr_h12_assent'),
    $('rh200_tipoasse_faltas_dsr_h12_descr'),
    {
      'sArquivo' : 'func_tipoasse.php',
      'aParametrosAdicionais' : ['lConfiguracoesPontoEletronico=true'],
      'aCamposAdicionais'     : ['h12_codigo', 'h12_assent', 'h12_descr'],
      'sLabel'   : 'Pesquisar Tipo de Assentamentos',
      'fCallBack'             : function () {

        $('rh200_tipoasse_faltas_dsr').value            = '';
        $('rh200_tipoasse_faltas_dsr_h12_assent').value = '';
        $('rh200_tipoasse_faltas_dsr_h12_descr').value  = '';

        if(arguments.length < 3) {
          $('rh200_tipoasse_faltas_dsr_h12_descr').value  = arguments[0];
          return;
        }

        var parametros = ajustarValoresLookup(arguments);
        $('rh200_tipoasse_faltas_dsr').value            = parametros.id;
        $('rh200_tipoasse_faltas_dsr_h12_assent').value = parametros.codigo;
        $('rh200_tipoasse_faltas_dsr_h12_descr').value  = parametros.descricao;
      }
    }
  );

  $('rh200_tipoasse_extra50diurna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_extra75diurna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_extra100diurna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_extra50noturna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_extra75noturna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_extra100noturna_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_adicionalnoturno_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_falta_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });

  $('rh200_tipoasse_faltas_dsr_h12_assent').observe('blur', function () {
    verificarPreenchimentoCodigoLookup.apply(this, arguments);
  });


  function verificarPreenchimentoCodigoLookup() {

    var input = this;
    if(input.value.trim() == '') {
      $$('.codigo-tipoasse').each(function (node, i) {
        if(node.name == input.name.substr(0, input.name.indexOf('_h12_assent'))) {
          node.value='';
        }
      })
    }
  }

  function salvarConfiguracoes () {
    AjaxRequest.create(
      'rec4_pontoeletronicoconfiguracoes.RPC.php',
      {
        'exec' : 'salvarConfiguracoesGerais',
        'rh200_tipoasse_extra50diurna'    : $F('rh200_tipoasse_extra50diurna'),
        'rh200_tipoasse_extra75diurna'    : $F('rh200_tipoasse_extra75diurna'),
        'rh200_tipoasse_extra100diurna'   : $F('rh200_tipoasse_extra100diurna'),
        'rh200_tipoasse_extra50noturna'   : $F('rh200_tipoasse_extra50noturna'),
        'rh200_tipoasse_extra75noturna'   : $F('rh200_tipoasse_extra75noturna'),
        'rh200_tipoasse_extra100noturna'  : $F('rh200_tipoasse_extra100noturna'),
        'rh200_tipoasse_adicionalnoturno' : $F('rh200_tipoasse_adicionalnoturno'),
        'rh200_tipoasse_falta'            : $F('rh200_tipoasse_falta'),
        'rh200_tipoasse_faltas_dsr'       : $F('rh200_tipoasse_faltas_dsr'),
        'rh200_autorizahoraextra'         : $F('rh200_autorizahoraextra')
      },
      function (retorno, erro) {

        if(retorno.mensagem.trim() != '') {
          alert(retorno.mensagem.urlDecode());
        }

        if(erro) {
          return;
        }
      }
    ).setMessage('Salvando configurações...').execute();
  }

  function ajustarValoresLookup (parametros) {

    var i, id, codigo, descricao;
    i = 0;

    if(parametros.length > 3) {
      i = i+2;
    }

    return {
      'id'        : parametros[i],
      'codigo'    : parametros[i+1],
      'descricao' : parametros[i+2]
    }
  }

  $('rh200_autorizahoraextra').setStyle({'width' : '50px'});
</script>
</body>
</html>
