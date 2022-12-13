<?php

/**
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
 *
 * @author $Author: dbdiogo $
 * @version $Revision: 1.1 $
 */

require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/JSON.php';

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_econsigrelatorioimportacao.');

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->erro     = false;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();
  
  switch ($oParam->exec) {

    /**
     * Retorna a competência atual da folha
     */
    case 'retornarCompetencia':
      
      $oCompetencia = DBPessoal::getCompetenciaFolha();
      $oRetorno->iAno = $oCompetencia->getAno();
      $oRetorno->iMes = $oCompetencia->getMes();
      break;
  
    /**
     * Gera o relatório de importação
     */
    case 'gerarRelatorioImportacao':
      
      $iAno              = $oParam->iAno;
      $iMes              = $oParam->iMes;
      $oIntituicao       = new Instituicao(db_getsession('DB_instit'));
      $oCompetencia      = new DBCompetencia($iAno, $iMes);
      $oCompetenciaAtual = DBPessoal::getCompetenciaFolha();
      
      /**
       * Monta datas.
       */
      $oDate = new DBDate(
        str_replace('/', '-', $oCompetencia->getCompetencia()) . '-01'
      );

      $oDateAtual = new DBDate(
        str_replace('/', '-', $oCompetenciaAtual->getCompetencia()) . '-01'
      );

      /**
       * Verifica se a competência informada é maior que a competência atual da folha.
       */
      if ($oDate->getTimeStamp() > $oDateAtual->getTimeStamp()) {
        throw new BusinessException(_M(MENSAGENS .  'competencia_informada_ultrapassada'));
      }
         
      $oArquivo = ArquivoEConsigRepository::getUltimoArquivo($oIntituicao, $oCompetencia);
      $iOID     = $oArquivo->getRelatorio();
      
      if (empty($iOID)) {
        throw new BusinessException(_M(MENSAGENS . 'nao_existe_relatorio'));
      }
      
      $sCaminhoArquivo = 'tmp/RelatorioImportacao.pdf';
      $lEscritaArquivo = DBLargeObject::leitura($iOID, $sCaminhoArquivo);
      if (!$lEscritaArquivo) {
        throw new BusinessException(_M(MENSAGENS .  'erro_escrever_relatorio'));
      }
      
      $oRetorno->sArquivo  = urlencode($sCaminhoArquivo);
      break;
  }
  
  db_fim_transacao();
    
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);