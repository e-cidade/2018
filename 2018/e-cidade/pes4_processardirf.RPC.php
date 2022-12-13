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


require_once(modification("libs/db_autoload.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_libpostgres.php"));

global $db_debug;

use ECidade\RecursosHumanos\Pessoal\Arquivos\Dirf\Ano2017\Dirf as Dirf2017;

$oJson    = new services_json();
$oParam   = $oJson->decode((str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status           = 1;
$oRetorno->message          = 1;
$oRetorno->itens            = array();
$oRetorno->aListaMatriculas = array();

switch($oParam->exec) {

  case "processarDirf":

    $subpes = $oParam->iAno.'/'.db_mesfolha();
    $subini = $oParam->iAno."/01";

    try {

      db_inicio_transacao();

      if(isset($oParam->lDebug) && $oParam->lDebug) {
        $db_debug         = $oParam->lDebug;
        $oRetorno->lDebug = true;
      }

      $oDirf = new Dirf2017($oParam->iAno, $oParam->sCnpj);

      $oDirf->setDesdobramentos($oParam->aDesdobramentos);
      $oDirf->processar($oParam->lProcessaEmpenho);

      file_put_contents("tmp/LogDirf.txt", LogDirf::getLog(LogDirf::STR) );
      $oRetorno->aArquivosInconsistentes = array();

      if ($oDirf->hasInconsistencias()) {

        $aArquivosInconsistentes = $oDirf->geraArquivoInconsistencias();
        foreach ($aArquivosInconsistentes as $sArquivoInconsistente) {

          $oRetorno->aArquivosInconsistentes[] = urlencode($sArquivoInconsistente);
        }
      }

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;

  case 'gerarDirf':

    $oParam->sNomeResponsavel = db_stdClass::db_stripTagsJson($oParam->sNomeResponsavel);

    $iValor = db_formatar((int) $oParam->iValor,'p');

    // Verificação do layout anterior ao ano base de 2012
    if ($oParam->iAno >= 2015) {
      $oDirf = new Dirf2015($oParam->iAno, $oParam->sCnpj);
    } elseif ($oParam->iAno >= 2012) {
      $oDirf = new Dirf2012($oParam->iAno, $oParam->sCnpj);
    } else {

      $oDirf = new Dirf($oParam->iAno, $oParam->sCnpj);
      $oDirf->setCodigoLayout(93);
    }

    $oDirf->setValorLimite($iValor);
    $oDirf->setCodigoArquivo($oParam->sCodigoArquivo);

    $oDirf->setMatriculas($oParam->aMatriculaSelecionadas);
    $oRetorno->arquivo = $oDirf->gerarArquivo($oParam, $oParam->lProcessaEmpenho);

  break;

  case "getUnidadesCnpjInvalido":

    $oRetorno->unidades = Dirf::retornarUnidadesCnpjInvalido();

  break;

  case "getMatriculasDirf":

    $iValor = db_formatar((int) $oParam->iValor,'p');
    
    $oDirf = new Dirf($oParam->iAno, $oParam->sCnpj);
    $oDirf->setValorLimite($iValor);

    $oRetorno->aListaMatriculas = $oDirf->retornaMatriculasDirf($oParam->lProcessaEmpenho, $oParam->sAcima);

  break;

  case "verificaProcessamento":
    $oRetorno->lProcessado = false;

    if (!empty($oParam->iAno) && !empty($oParam->sFontePagadora)) {

      $oDaoRhDirfGeracao = db_utils::getDao("rhdirfgeracao");

      $sSql = $oDaoRhDirfGeracao->sql_query_file( null, 
                                                  "*", 
                                                  null,
                                                  " rh95_ano = {$oParam->iAno} and rh95_fontepagadora = '{$oParam->sFontePagadora}'" );
      $rsRhDirfGeracao = db_query($sSql);

      if ($rsRhDirfGeracao && pg_num_rows($rsRhDirfGeracao) > 0) {
        $oRetorno->lProcessado = true;
      }
    }

  break;

  case 'validarBasesRRA' :


    $oRetorno->avisarfaltabases = false;
    $oCompetenciaAtual   = DBPessoal::getCompetenciaFolha();
    $oParametros         = ParametrosPessoalRepository::getParametros($oCompetenciaAtual);
    $aBases             = array();
    if (!$oParametros->getBaseRraParcelaIsenta()) {
      $aBases[] = " - Parcela isenta do RRA";
    }

    if (!$oParametros->getBaseRraRendimentosTributaveis()) {
      $aBases[] = " - Rendimentos tributáveis do RRA";
    }

    if (!$oParametros->getBaseRraPrevidenciaSocial()) {
      $aBases[] = " - Previdência Social do RRA";
    }

    if (!$oParametros->getBaseRraPensaoAlimenticia()) {
      $aBases[] = " - Pensão Alimentícia do RRA";
    }
    if (!$oParametros->getBaseRraIrrf()) {
      $aBases[] = " - Imposto Retido RRA";
    }

    if (count($aBases) > 0) {

      $sMensagem  = "As Bases abaixo não foram configuradas. Seus valores não serão computados na DIRF.\n";
      $sMensagem .= implode(",\n", $aBases);
      $sMensagem .= "\nPara configura-las, acessar a rotina Procedimentos > Manutenção de Parâmetros > Bases Especiais.\n";
      $sMensagem .= "Deseja continuar o processamento da Dirf?";

      $oRetorno->avisarfaltabases = true;
      $oRetorno->message          = urlencode($sMensagem);

    }
    break;

}

echo $oJson->encode($oRetorno);