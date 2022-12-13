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
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("model/dbLayoutLinha.model.php"));
require_once(modification("model/dbLayoutReader.model.php"));
require_once(modification("std/DBString.php"));
require_once(modification("std/DBNumber.php"));
require_once(modification("model/educacao/censo/IExportacaoCenso.interface.php"));
db_app::import("educacao.censo.DadosCenso");
db_app::import("educacao.censo.*");

$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {

  case 'processarArquivoCenso':

    try {

      $oCenso     = ExportacaoCensoFactory::getInstanceByAno($oParam->iAno);
      $sDataCenso = implode("-", array_reverse(explode("/", $oParam->dtCenso)));
      $oCenso->setDataCenso($sDataCenso);

      $sArquivoRetorno        = $oCenso->escreverArquivo();
      $oRetorno->sNomeArquivo = urlencode("tmp/{$sArquivoRetorno}");
    }  catch (Exception $eErro) {

      $oRetorno->status          = 2;
      $oRetorno->message         = urlencode($eErro->getMessage());
      $oRetorno->sNomeArquivoLog = "";
      if ( !empty($oCenso) ) {
        $oRetorno->sNomeArquivoLog = urlencode($oCenso->getNomeArquivoLog());
      }
    }
    break;

  case 'validarDadosArquivo':

    try {

      $iEscola = db_getsession("DB_coddepto");
      if (strtolower(substr($oParam->nomearquivo, strlen($oParam->nomearquivo) - 3, 3)) != 'txt') {
        throw new Exception('Arquivo deve ser um arquivo com extensão .txt');
      }

      if (strpos($oParam->nomearquivo, 'censo') === false ) {

        $sErroMensagem  = 'Arquivo  informado nao é válido.\n';
        $sErroMensagem .= 'Apenas arquivos gerados pelo sistema ou retornados pelo censo poderão ser verificados.';
        throw new Exception($sErroMensagem);
      }
      $oRetorno->sNomeArquivo = basename($oParam->nomearquivo);

      $aNomeArquivo = explode("_", $oParam->nomearquivo);
      $iAno         = str_replace('.txt', '', $aNomeArquivo[3]);

      $iCodigoLayout  = 199;
      switch ($iAno) {

        case 2015:
          $iCodigoLayout = 226;
          break;
        case 2016:
          $iCodigoLayout = 255;
          break;
      }

      $oLayoutReader = new DBLayoutReader($iCodigoLayout, $oParam->nomearquivo, true, false);
      $oLayoutReader->processarArquivo(0, true, true);
      $oRetorno->arquivo = new stdClass();
      $oRetorno->arquivo->aEscolas     = array();
      $oRetorno->arquivo->aTurmas      = array();
      $oRetorno->arquivo->aDocentes    = array();
      $oRetorno->arquivo->aAluno       = array();
      foreach ($oLayoutReader->getLines() as $iInd => $oArquivo) {

        if ($oArquivo->tipo_registro == '00' || $oArquivo->tipo_registro == '10') {

          if ($oArquivo->tipo_registro == '00') {

            $oEscola            = new stdClass();
            $oEscola->nome      = $oArquivo->nome = urlEncode($oArquivo->nome_escola);
            $oEscola->dados[$oArquivo->tipo_registro] = array();
            $oRetorno->arquivo->aEscolas[]            = $oEscola;
          }
          $aPropriedadesLinha = $oArquivo->getProperties();
          foreach ($aPropriedadesLinha as $aPropriedade) {

            $sValor = isset($oArquivo->$aPropriedade[4])?urlencode($oArquivo->$aPropriedade[4]):'';
            $oEscola->dados[$oArquivo->tipo_registro][] = array(urlencode($aPropriedade[6]),
                                                                $sValor,
                                                                urlencode($aPropriedade[7])
                                                                );
          }
        }

        if ($oArquivo->tipo_registro == 20) {

          $aPropriedadesLinha = $oArquivo->getProperties();
          $oTurma                                   = new stdClass();
          $oTurma->nome                             = $oArquivo->nome = urlEncode($oArquivo->nome_turma);
          $oTurma->dados[$oArquivo->tipo_registro]  = array();
          $oRetorno->arquivo->aTurmas[]             = $oTurma;
          foreach ($aPropriedadesLinha as $aPropriedade) {

            $sValor = isset($oArquivo->$aPropriedade[4])?urlencode($oArquivo->$aPropriedade[4]):'';
            $oTurma->dados[$oArquivo->tipo_registro][] = array(urlencode($aPropriedade[6]),
                                                               $sValor,
                                                               urlencode($aPropriedade[7]));
          }
        }

        if (in_array($oArquivo->tipo_registro, array(30, 40, 50, 51))) {

          if ($oArquivo->tipo_registro == 30) {

            $oDocente                                   = new stdClass();
            $oDocente->nome                             = $oArquivo->nome = urlEncode($oArquivo->nome_completo);
            $oDocente->codigo                           = $oArquivo->codigo_docente_entidade_escola;
            $oDocente->dados[$oArquivo->tipo_registro]  = array();
            $oRetorno->arquivo->aDocentes[]             = $oDocente;
          }
          if ($oArquivo->tipo_registro != 30) {

            $iCodigoDocente = $oArquivo->codigo_docente_entidade_escola;
            $oDocente = null;
            foreach ($oRetorno->arquivo->aDocentes as $oDocentePesquisa) {

              if ($iCodigoDocente == $oDocentePesquisa->codigo) {

                $oDocente = $oDocentePesquisa;
                break;
              }
            }
            unset($oDocentePesquisa);
          }
          $aPropriedadesLinha = $oArquivo->getProperties();
          foreach ($aPropriedadesLinha as $aPropriedade) {

            $sValor = isset($oArquivo->$aPropriedade[4])?urlencode($oArquivo->$aPropriedade[4]):'';
            $oDocente->dados[$oArquivo->tipo_registro][] = array(urlencode($aPropriedade[6]),
                                                                 $sValor,
                                                                 urlencode($aPropriedade[7])
                                                                );
          }
        }

        if (in_array($oArquivo->tipo_registro, array(60, 70, 80))) {

          if ($oArquivo->tipo_registro == 60) {

            $oAluno                                   = new stdClass();
            $oAluno->nome                             = $oArquivo->nome = urlEncode($oArquivo->nome_completo);
            $oAluno->codigo                           = $oArquivo->identificacao_unica_aluno;
            $oAluno->dados[$oArquivo->tipo_registro]  = array();
            $oRetorno->arquivo->aAlunos[]             = $oAluno;
          }
          if ($oArquivo->tipo_registro != 60) {

            $iCodigoAluno = $oArquivo->identificacao_unica_aluno;
            $oAluno = null;
            foreach ($oRetorno->arquivo->aAlunos as $oAlunoPesquisa) {

              if ($iCodigoAluno == $oAlunoPesquisa->codigo) {

                $oAluno = $oAlunoPesquisa;
                break;
              }
            }
            unset($oAlunoPesquisa);
          }
          $aPropriedadesLinha = $oArquivo->getProperties();


          /**
           * Como o registro é usado como index quando aluno tem mais de um registro 80 no arquivo,
           * é incrementado o registro 80 só para visualização.
           */
          if ( $oArquivo->tipo_registro == 80 && array_key_exists($oArquivo->tipo_registro, $oAluno->dados) ) {
            $oArquivo->tipo_registro = max(array_keys($oAluno->dados)) +1;
          }

          foreach ($aPropriedadesLinha as $aPropriedade) {

            $aPropriedade[7] = str_replace(array("\n", "\r"), array("<br>", ""), $aPropriedade[7]);
            $sValor = isset($oArquivo->$aPropriedade[4])?urlencode($oArquivo->$aPropriedade[4]):'';

            $oAluno->dados[$oArquivo->tipo_registro][] = array(urlencode($aPropriedade[6]), $sValor, urlencode($aPropriedade[7]));
          }
        }
      }

    } catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case 'censoMultiEtapa':


    /**
     * Etapas do censo de 2014
     */
    $sWhere  = ' ed266_i_codigo in (3,12,13,22,23,24,51,56,58) and ed266_ano = 2014 ';

    // para ano de 2015 as etapas do censo mudaram
    if ($oParam->iAnoCalendario > 2014) {
      $sWhere  = ' ed266_i_codigo in (3, 12, 13, 22, 23, 24, 72, 56) and ed266_ano = 2015 ';
    }

    $sCampos = ' ed266_c_descr, ed266_i_codigo';

    $oDaoCensoEtapa = new cl_censoetapa();
    $sSqlCensoEtapa = $oDaoCensoEtapa->sql_query_file(null, null, $sCampos, $sCampos, $sWhere);
    $rsCensoEtapa   = $oDaoCensoEtapa->sql_record($sSqlCensoEtapa);
    $iLinhas        = $oDaoCensoEtapa->numrows;

    $oRetorno->aCensoEtapa = array();
    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $oDados                  = db_utils::fieldsMemory($rsCensoEtapa, $i);
      $oCensoEtapa             = new stdClass();
      $oCensoEtapa->sEtapa     = urlencode( $oDados->ed266_c_descr );
      $oCensoEtapa->iCodigo    = $oDados->ed266_i_codigo;
      $oRetorno->aCensoEtapa[] = $oCensoEtapa;
    }

    break;
}
echo $oJson->encode($oRetorno);
?>
