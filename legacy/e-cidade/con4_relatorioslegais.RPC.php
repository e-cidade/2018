<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_liborcamento.php"));

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

try {

  switch($oParam->exec) {

    case 'getVariaveis':

      $oVariaveis                    = new stdClass();
      $oVariaveis->campos_relatorios = array();
      $oVariaveis->colunas_linha     = array();
      switch ($oParam->iOrigemDados) {

        case RelatoriosLegaisBase::TIPO_CALCULO_DESPESA :

          $oVariaveis->campos_relatorios = RelatoriosLegaisBase::$aCamposDespesa;
         break;

        case RelatoriosLegaisBase::TIPO_CALCULO_RECEITA:

          $oVariaveis->campos_relatorios = RelatoriosLegaisBase::$aCamposReceita;
          break;

        case RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO:

          $oVariaveis->campos_relatorios = RelatoriosLegaisBase::$aCamposVerificacao;
          break;

        case RelatoriosLegaisBase::TIPO_CALCULO_RESTO:

          $oVariaveis->campos_relatorios = RelatoriosLegaisBase::$aCamposRestoPagar;
          break;
      }

      /**
       * Buscamos todas as variaveis que são as Colunas Cadastradas na linha do Relatorio
       */
      $oLinhaRelatorio = new linhaRelatorioContabil($oParam->iCodigoRelatorio, $oParam->iCodigoLinha);
      foreach ($oLinhaRelatorio->getCols() as $oColuna) {
        if (!in_array($oColuna->o115_nomecoluna, $oVariaveis->colunas_linha)) {
          $oVariaveis->colunas_linha[] = $oColuna->o115_nomecoluna;
        }
      }
      $oRetorno->oListaVariaveis = $oVariaveis;
      break;

    case 'getRelatorios' :

      $oDaoOrcParamRel = new cl_orcparamrel();

      $sWhere = '';
      if (!empty($oParam->iTipo)) {
        $sWhere = " o42_orcparamrelgrupo = {$oParam->iTipo} ";
      }
      $oRetorno->aRelatorios = array();
      $sSqlRelatorios        = $oDaoOrcParamRel->sql_query_file(null, "*", 'o42_codparrel', $sWhere);
      $rsRelatorios          = $oDaoOrcParamRel->sql_record($sSqlRelatorios);
      if ($rsRelatorios) {

         for ($iRelatorio = 0; $iRelatorio < $oDaoOrcParamRel->numrows; $iRelatorio++) {

           $oDadosRelatorio = db_utils::fieldsMemory($rsRelatorios, $iRelatorio);

           $oStdRelatorio          = new stdClass();
           $oStdRelatorio->iCodigo = $oDadosRelatorio->o42_codparrel;
           $oStdRelatorio->sNome   = urlencode($oDadosRelatorio->o42_descrrel);
           unset($oDadosRelatorio);
           $oRetorno->aRelatorios[] = $oStdRelatorio;
         }
      }
      break;

    case 'getPeriodosDoRelatorio':

      $oRetorno->aPeriodos = array();
      $oRelatorio = new relatorioContabil($oParam->iCodigo, false);
      $aPeriodos  = $oRelatorio->getPeriodos();
      foreach ($aPeriodos as $oPeriodo) {

        $oStdPeriodo             = new stdClass();
        $oStdPeriodo->iCodigo    = $oPeriodo->o114_sequencial;
        $oStdPeriodo->sDescricao = urlencode($oPeriodo->o114_descricao);
        $oRetorno->aPeriodos[]   = $oStdPeriodo;
      }
      break;


    case 'processarConferencia':

      $oConsistentencia = new ConsistenciaContabil(db_getsession("DB_anousu"),
                                                   $oParam->iCodigoRelatorio,
                                                   $oParam->iCodigoPeriodo
                                                  );

      $oConsistentencia->setInstituicoes(implode(",", $oParam->aInstituicoes));
      $aLinhas           = $oConsistentencia->getDados();
      $oRetorno->arquivo = urlencode($oConsistentencia->gerarCSV());

      foreach ($aLinhas as $oLinha) {

        $oLinha->descricao = urlencode($oLinha->descricao);
        foreach ($oLinha->colunas as $oColuna) {
          $oColuna->descricao = urlencode($oColuna->descricao);
        }
      }

      $oRetorno->aLinhasConsistencia = $aLinhas;
      break;

    case "exportarRelatorio" :

      db_inicio_transacao();

      $iCodigoRelatorio   = $oParam->iCodigoRelatorio;
      $oExportarRelatorio = new ExportacaoRelatorioLegal($iCodigoRelatorio);
      $sCaminhoArquivo    = $oExportarRelatorio->exportar();
      if (!empty($sCaminhoArquivo)) {

        $oRetorno->sCaminho = urlencode($sCaminhoArquivo);
        $oRetorno->message  = "Relatório exportado com sucesso.";
      }

      db_fim_transacao();

    break;

    case "importarRelatorio" :

      db_inicio_transacao();

      $iCodigoRelatorio = $oParam->iCodigoRelatorio;
      $sCaminhoArquivo  = $oParam->sCaminhoArquivo;
      $oImportacaoRelatorio = new ImportacaoRelatorioLegal($iCodigoRelatorio, $sCaminhoArquivo  );
      if ($oImportacaoRelatorio->importar()) {
        $oRetorno->message  = "Relatório importado com sucesso.";
      }

      $oRetorno->iCodigoRelatorio = $oImportacaoRelatorio->getCodigoRelatorio();

      db_fim_transacao();

    break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status = 2;
  $oRetorno->message = $oErro->getMessage();
}

$oRetorno->message  = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);
