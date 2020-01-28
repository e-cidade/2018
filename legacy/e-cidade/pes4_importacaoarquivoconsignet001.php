<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$sPosScripts = '';
$sMensagens  = "recursoshumanos.pessoal.pes4_importacaoarquivoconsignado.";

define('MENSAGENS', $sMensagens);

$oPost       = db_utils::postMemory($_POST);
$oGet        = db_utils::postMemory($_GET);
$oFiles      = db_utils::postMemory($_FILES);

$oCompetencia= DBPessoal::getCompetenciaFolha();
$iAnoFolha   = $oCompetencia->getAno();
$iMesFolha   = $oCompetencia->getMes();

include(modification("forms/db_frmimportacaoarquivoconsignet.php"));


/**
 * Valida se o ponto esta inicializado na competência.
 */
try {

  validarPontoInicializado($oCompetencia);

} catch (Exception $oException) {

  /**
   * Desabilita o botão processar.
   */
  $sPosScripts .= "$('db_opcao').disable(); ";
  $sPosScripts .= "alert('" . $oException->getMessage() . "');\n";
}

if (isset($oPost->incluir)) {

  $sPosScripts .= "js_removeObj('carregandoArquivoImportacao');";
  if ($oFiles->aArquivoMovimento['error'] != 0) {

    db_msgbox(_M("{$sMensagens}falha_importacao"));
    exit;
  }

  if ( !move_uploaded_file($oFiles->aArquivoMovimento['tmp_name'], "tmp/{$oFiles->aArquivoMovimento['name']}") ) {

    db_msgbox(_M("{$sMensagens}falha_importacao"));
    exit;
  }

  try {

    db_inicio_transacao();

    $oCompetencia    = new DBCompetencia($oPost->ano, $oPost->mes);
    $oInstituicao    = InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit'));
    $oArquivoConsignet = new ProcessamentoArquivoConsignet("tmp/{$oFiles->aArquivoMovimento['name']}", $oCompetencia, $oInstituicao);

    $oArquivoConsignet->importar();
    $sCaminho = $oArquivoConsignet->getCaminhoArquivoInconsistencias();
    if (!empty($sCaminho) ) {

      echo "<script>js_exibeRelatorioImportacao('{$oArquivoConsignet->getCaminhoArquivoInconsistencias()}');</script>";
      db_fim_transacao(false);
    }

    db_msgbox(_M("{$sMensagens}arquivo_importado"));
    db_fim_transacao(false);
  } catch(Exception $e) {
    db_fim_transacao(true);
    db_msgbox( $e->getMessage() );
  }


}
echo "<script>{$sPosScripts}</script>";

function validarPontoInicializado( DBCompetencia $oCompetencia ) {

    /**
     * Se utiliza a estrutura nova de complementar verifica
     * se existe uma folha de salário aberta.
     */
    if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() ) {

      $lFolhaAberta = FolhaPagamento::hasFolhaAberta(FolhaPagamento::TIPO_FOLHA_SALARIO, $oCompetencia);

      if (!$lFolhaAberta){
        throw new BusinessException(_M(MENSAGENS . 'salario_fechado'));
      }
      return true;
    }

    /**
     * Se não utilizar a estrutura nova de complementar
     * verifica se existe dados no pontofs para a competência,
     * se existir é porque o ponto foi inicializado.
     */
    $oDaoPontoFs = new cl_pontofs();
    $sSqlPontoFs = $oDaoPontoFs->sql_query_file ( $oCompetencia->getAno(),
                                                  $oCompetencia->getMes(),
                                                  null,
                                                  null,
                                                  "r10_rubric"
                                                );
    $rsPontoFs = db_query($sSqlPontoFs);

    if (!$rsPontoFs) {
      throw new DBException(_M(MENSAGENS . 'erro_ponto'));
    }

    if (pg_num_rows($rsPontoFs) == 0) {
      throw new BusinessException(_M(MENSAGENS . 'erro_ponto_nao_inicializado'));
    }

    return true;
  }
