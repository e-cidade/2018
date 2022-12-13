<?php
/**
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("classes/db_rharquivossiprev_classe.php"));
require_once(modification("fpdf151/fpdf.php"));

$oJson             = new services_json();
$oParam            = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$_POST["json"])));

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$lErro             = false;
$sMensagem         = "";
$oRhArquivossiprev = new cl_rharquivossiprev();

switch($oParam->exec) {

  case 'Lista' :

    try {

      $oRetorno->dados      = array();
      $sSqlRhArquivossiprev = $oRhArquivossiprev->sql_query("","*","rh94_sequencial ASC","");
      $rsRhArquivossiprev   = $oRhArquivossiprev->sql_record($sSqlRhArquivossiprev);

      if(!$rsRhArquivossiprev) {
        throw new DBException('Erro ao buscar os arquivos do SIPREV.');
      }

      $aArquivos       = db_utils::getCollectionByRecord($rsRhArquivossiprev, null, null, true);
      $oRetorno->dados = $aArquivos;

      $oDaoRhParam    = new cl_rhparam();
      $sWhereRhParam  = '     h36_tempocontribuicaorgps is not null';
      $sWhereRhParam .= ' AND h36_tempocontribuicaorpps is not null';
      $sWhereRhParam .= ' AND h36_temposficticios is not null';
      $sWhereRhParam .= ' AND h36_temposemcontribuicao is not null';
      $sSqlRhParam    = $oDaoRhParam->sql_query_file(db_getsession('DB_coddepto'), '1', null, $sWhereRhParam);
      $rsRhParam      = db_query($sSqlRhParam);

      if(!$rsRhParam) {
        throw new DBException('Erro ao validar os parâmetros do RH.');
      }

      $oRetorno->lConfigurouAssentamentos = pg_num_rows($rsRhParam) > 0 ? true : false;
    } catch (Exception $oErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    }

    break;

  case 'Gerar' :

    try {

      $sArquivoGerado       = "SIPREV";
      $iMesInicial          = $oParam->iMesinicial;
      $iAnoInicial          = $oParam->iAnoinicial;
      $iMesFinal            = $oParam->iMesinicial;
      $iAnoFinal            = $oParam->iAnoinicial;
      $sArquivos            = $oParam->sListaArquivos;
      $iUnidadeGestora      = $oParam->iUnidadeGestora;
      $iTipoAto             = $oParam->iTipoAto;
      $iNumeroAto           = $oParam->iNumeroAto;
      $iAnoAto              = $oParam->iAnoAto;
      $dDataAto             = $oParam->dDataAto;
      $cRepresentante       = $oParam->cRepresentante;

      $oGeradorXML          = new ArquivoSiprevEscritorXML();
      $otxtLogger           = fopen("tmp/SIPREV.log", "w");
      $sSqlSipreveEscolhido = $oRhArquivossiprev->sql_query("","*","","rh94_sequencial in ({$sArquivos})");
      $rsSipreveEscolhido   = db_query($sSqlSipreveEscolhido);

      if ( !$rsSipreveEscolhido ) {
        throw new Exception("Erro ao pesquisar arquivo do SiPrev.");
      }

      $aClasses = db_utils::getCollectionByRecord($rsSipreveEscolhido);

      foreach ($aClasses as $iIndiceClasses => $sValorClasses) {

        $sClasse = $sValorClasses->rh94_nomeclasse;
        $oClasse = new $sClasse;
        $oClasse->setCompetenciaInicial($iAnoInicial, $iMesInicial);
        $oClasse->setCompetenciaFinal($iAnoFinal, $iMesFinal);
        $oClasse->setUnidadeGestora($iUnidadeGestora);
        $oClasse->setTipoAto($iTipoAto);
        $oClasse->setNumeroAto($iNumeroAto);
        $oClasse->setAnoAto($iAnoAto);
        $oClasse->setDataAto($dDataAto);
        $oClasse->setRepresentante($cRepresentante);
        $oClasse->setTXTLogger($otxtLogger);

        try {
          /*
           * Cria o XML para cada arquivo selecionado
           */
          $oGeradorXML->adicionarArquivo($oGeradorXML->criarArquivo($oClasse), $oClasse->getNomeArquivo());
        } catch (Exception $eErro) {
          throw new BusinessException("Arquivo: {$oClasse->getNomeArquivo()} retornou com erro:{$eErro->getMessage()}");
        }
      }

      /*
       * Cria o zip  com os arquivos selecionados]
       */
      $sArquivo = $oGeradorXML->zip($sArquivoGerado);

      if(!empty($sArquivo)) {

        $oGeradorXML->adicionarArquivo("tmp/{$sArquivoGerado}.log", "{$sArquivoGerado}.log");
        $oGeradorXML->adicionarArquivo("tmp/{$sArquivoGerado}.zip", "{$sArquivoGerado}.zip");
      }

      $oRetorno->itens  = $oGeradorXML->getListaArquivos();
      fclose($otxtLogger);

      $oRetorno->dados  = $aClasses;
      $aInconsistencias = array_filter(ArquivoSiprevBase::$aErrosProcessamento, function($erros) {
        return $erros;
      });

      $oRetorno->lTemInconsistencias = false;

      if(count($aInconsistencias) > 0) {

        $oRetorno->lTemInconsistencias = true;

        $oRelatorioInconsistencias = new RelatorioErrosSIPREV(ArquivoSiprevBase::$aErrosProcessamento);
        $oRelatorioInconsistencias->criar();
      }
    } catch ( Exception $eErro ) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}

echo $oJson->encode($oRetorno);