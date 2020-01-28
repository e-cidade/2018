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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");

$oGet       = db_utils::postMemory($_GET);
$oPost      = db_utils::postMemory($_POST);

$oDaoCfpess = new cl_cfpess();

define('MENSAGEM', 'recursoshumanos.pessoal.pes4_basesespeciais.');
/**
 *  Campos utilizados nas telas
 */
$aCampos = array_merge(
  $aCamposRRA = array(
    'r11_baserrarendimentostributaveis',
    'r11_baserraprevidenciasocial',
    'r11_baserrapensaoalimenticia',
    'r11_baserrairrf',
    'r11_baserraparcelaisenta',
  ),
  $aCamposFG = array(
    'r11_basefgintegral',
    'r11_basefgparcial',
  )
);

$sIDBases                     = " data='r08_codigo' ";
$sIDDescricao                 = " data='r08_descr' ";

$sSqlCfpess    = $oDaoCfpess->sql_query_file (DBPessoal::getAnoFolha(),DBPessoal::getMesFolha(), db_getsession('DB_instit'), implode(', ', $aCampos));
$rsCfpess      = db_query($sSqlCfpess);

if (!$rsCfpess) {
  $sMensagemErro = _M(MENSAGEM . 'erro_busca');
}

db_fieldsmemory($rsCfpess, 0);

try {

  $oIntituicao  = InstituicaoRepository::getInstituicaoSessao();
  $oCompetencia = DBPessoal::getCompetenciaFolha();
  $oRotulo      = new rotulocampo();

  /**
   * Preenchimento com os valores da tela
   */
  foreach($aCampos as $campo) {

    $oRotulo->label($campo);//Preenche as globais

    if (!empty(${$campo})) {
      ${"label_".$campo} = BaseRepository::getBase(${$campo}, $oCompetencia, $oIntituicao)->getNome();//Cria a variável com o nome label do campo
    }
  }

} catch (Exception $eException) {
  db_msgbox($eException->getMessage());
}

?>
<html>
  <head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load(array("estilos.css", "scripts.js", "strings.js", "prototype.js", "DBLookUp.widget.js"));
    ?>
  </head>
  <body>
    <form class="container" name="alterar" method="post">
      <fieldset>
        <legend>
          Configuração de Bases Especiais RRA
        </legend>
        <table class="form-container">
          <?php

          foreach($aCamposRRA as $campo) {

            echo "<tr>";
            echo "  <td>";
            echo "    <a href='#' id='ancora_{$campo}'>";
            echo "      {${"L".$campo}}";
            echo "    </a>";
            echo "  </td>";
            echo "  <td>";
            db_input($campo,          10, ${"I".$campo}, true, 'text', 1, $sIDBases);
            db_input("label_".$campo, 40,             0, true, 'text', 3, $sIDDescricao);
            echo "  </td>";
            echo "</tr>";
          }

          ?>
        </table>
      </fieldset>

      <fieldset>
        <legend>
          Configuração de Bases do SIPREV
        </legend>
        <table class="form-container">
          <?php

          foreach($aCamposFG as $campo) {

            echo "<tr>";
            echo "  <td>";
            echo "    <a href='#' id='ancora_{$campo}'>";
            echo "      {${"L".$campo}}";
            echo "    </a>";
            echo "  </td>";
            echo "  <td>";
            db_input($campo,          4,  0, true, 'text', 1, $sIDBases);
            db_input("label_".$campo, 40,  0, true, 'text', 3, $sIDDescricao);
            echo "  </td>";
            echo "</tr>";
          }

          ?>
        </table>
      </fieldset>
      <input value="Processar" name="alterar" id="processar" type="submit" />
    </form>

    <?php db_menu(); ?>

    <script>

      (function() {

        const MENSAGEM = "recursoshumanos.pessoal.pes4_basesespeciais.";

        var aCamposRRA = [
          'r11_baserrarendimentostributaveis',
          'r11_baserraprevidenciasocial',
          'r11_baserrapensaoalimenticia',
          'r11_baserrairrf',
          'r11_baserraparcelaisenta',
        ];
        var aCamposFG = [
          'r11_basefgintegral',
          'r11_basefgparcial',
        ];

        var aCampos                      = aCamposRRA.concat(aCamposFG);


        var sCampoRendimentosTributaveis = 'r11_baserrarendimentostributaveis',
            sCampoPrevidenciaSocial      = 'r11_baserraprevidenciasocial',
            sCampoPensaoAlimenticia      = 'r11_baserrapensaoalimenticia',
            sCampoIRRF                   = 'r11_baserrairrf';
            sCampoParcelaDeducao         = 'r11_baserraparcelaisenta';

        var oOptions                     = {
                                             "sArquivo"     : "func_bases.php",
                                             "sLabel"       : "Pesquisar Base",
                                             "sObjetoLookUp": "db_iframe_bases"
                                           };
        var aLookUps = {};

        for (var campo of aCampos) {

          aLookUps[campo] = new DBLookUp(
            $('ancora_' + campo),
            $(campo),
            $("label_"+campo),
            oOptions
          );

        }

        $('processar').observe("click", function(event){

          if ( !$F(sCampoRendimentosTributaveis) ) {

            alert(_M(MENSAGEM + "informe_rendimentos_tributaveis"));
            event.preventDefault();
            event.stopPropagation();
            return;
          }
          if ( !$F(sCampoPrevidenciaSocial) ) {

            alert(_M(MENSAGEM + "informe_previdencia"));
            event.preventDefault();
            event.stopPropagation();
            return;
          }
          if ( !$F(sCampoPensaoAlimenticia) ) {

            alert(_M(MENSAGEM + "informe_pensao"));
            event.preventDefault();
            event.stopPropagation();
            return;
          }
          if ( !$F(sCampoIRRF) ) {

            alert(_M(MENSAGEM + "informe_irrf"));
            event.preventDefault();
            event.stopPropagation();
            return;
          }
          if ( !$F(sCampoParcelaDeducao) ) {

            alert(_M(MENSAGEM + "informe_parcela_isenta"));
            event.preventDefault();
            event.stopPropagation();
            return;
          }

        });

      })();
    </script>
  </body>
</html>

<?php
try {

  if (isset($sMensagemErro)) {
    new DBException($sMensagemErro);
  }

  if (isset($oPost->alterar)) {

    $sBaseRRARendimentosTributaveis = $oPost->r11_baserrarendimentostributaveis;
    $sBaseRRAPrevidenciaSocial      = $oPost->r11_baserraprevidenciasocial;
    $sBaseRRAPensaoAlimenticia      = $oPost->r11_baserrapensaoalimenticia;
    $sBaseRRAImpostoDeRenda         = $oPost->r11_baserrairrf;
    $sBaseRRAParcelaIsenta          = $oPost->r11_baserraparcelaisenta;

    if (empty($sBaseRRARendimentosTributaveis)) {
      new BusinessException(_M(MENSAGEM . 'informe_rendimentos_tributaveis'));
    }

    if (empty($sBaseRRAPrevidenciaSocial)) {
      new BusinessException(_M(MENSAGEM . 'informe_previdencia'));
    }

    if (empty($sBaseRRAPensaoAlimenticia)) {
      new BusinessException(_M(MENSAGEM . 'informe_pensao'));
    }

    if (empty($sBaseRRAImpostoDeRenda)) {
      new BusinessException(_M(MENSAGEM . 'informe_irrf'));
    }

    if (empty($sBaseRRAParcelaIsenta)) {
      new BusinessException(_M(MENSAGEM . 'informe_parcela_isenta'));
    }

    $oDaoCfpess->r11_baserrarendimentostributaveis = $sBaseRRARendimentosTributaveis;
    $oDaoCfpess->r11_baserraprevidenciasocial      = $sBaseRRAPrevidenciaSocial;
    $oDaoCfpess->r11_baserrapensaoalimenticia      = $sBaseRRAPensaoAlimenticia;
    $oDaoCfpess->r11_baserrairrf                   = $sBaseRRAImpostoDeRenda;
    $oDaoCfpess->r11_baserraparcelaisenta          = $sBaseRRAParcelaIsenta;
    $oDaoCfpess->r11_anousu                        = DBPessoal::getAnoFolha();
    $oDaoCfpess->r11_mesusu                        = DBPessoal::getMesFolha();
    $oDaoCfpess->r11_instit                        = db_getsession('DB_instit');
    $oDaoCfpess->r11_basefgintegral                = $oPost->r11_basefgintegral;
    $oDaoCfpess->r11_basefgparcial                 = $oPost->r11_basefgparcial;

    $oDaoCfpess->alterar(DBPessoal::getAnoFolha(), DBPessoal::getMEsFolha(), db_getsession('DB_instit'));

    if ($oDaoCfpess->erro_status == '0') {
      new DBException(_M(MENSAGEM . 'erro_salvar'));
    }

    db_msgbox(_M(MENSAGEM . 'sucesso'));

    db_redireciona('pes4_basesespeciais001.php');
  }
} catch (Exception $eException) {
  db_msgbox( $eException->getMessage());
}
