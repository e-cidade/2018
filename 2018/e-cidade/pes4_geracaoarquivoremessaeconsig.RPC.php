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
 * @author $Author: dbluma $
 * @version $Revision: 1.11 $
 * 
 */

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");  

define("ARQUIVO_MENSAGEM", "recursoshumanos.pessoal.pes4_geracaoarquivoretornoeconsig.");

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
    case "retornarCompetencia":
      
      $oCompetencia = DBPessoal::getCompetenciaFolha();

      if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        $oCompetencia = $oCompetencia->getCompetenciaAnterior();
      }

      $oRetorno->iAno = $oCompetencia->getAno();
      $oRetorno->iMes = $oCompetencia->getMes();
      break;
  
    /**
     * Processa o retorno do arquivo.
     */
    case "processamentoRetornoArquivo" :
      
      $iAno              = $oParam->iAno;
      $iMes              = $oParam->iMes;
      $oCompetencia      = new DBCompetencia($iAno, $iMes);
      $oCompetenciaAtual = DBPessoal::getCompetenciaFolha();
      $oInstituicao      = InstituicaoRepository::getInstituicaoSessao();

      /**
       * Verifica se a competência informada é maior que a competência atual da folha.
       */
      if ($oCompetencia->comparar($oCompetenciaAtual, DBCompetencia::COMPARACAO_MAIOR)) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM .  "competencia_informada_ultrapassada"));
      }
      
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        
        /**
         * Verifica se existe uma folha salário na competência informada.
         */
        $iSequencial =  FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SALARIO, null, $oCompetencia);
        if (!$iSequencial) {
          throw new BusinessException(_M(ARQUIVO_MENSAGEM . "nenhum_registro_encontrado"));
        }
        
        /**
         * Valida se a folha de salário está aberta
         */
        $oFolhaPagamentoSalario = new FolhaPagamentoSalario($iSequencial);
        if ($oFolhaPagamentoSalario->isAberto()) {
          throw new BusinessException(_M(ARQUIVO_MENSAGEM . "folha_salario_aberta"));
        }
      } else {
        
        /**
         * Verifica se a competência informada é igual a competência atual da folha.
         */
        if ( $oCompetenciaAtual->comparar($oCompetencia, DBCompetencia::COMPARACAO_IGUAL) ) {
          throw new BusinessException(_M(ARQUIVO_MENSAGEM . "folha_nao_virada"));
        }
      }

      /**
       * Verifica se possui um arquivo importado nesta comepetência.
       */
      $lPossuiArquivo = ArquivoEConsigRepository::hasArquivoCompetencia($oCompetencia, $oInstituicao);
      if (!$lPossuiArquivo) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM . "arquivo_nao_importado"));
      }

      /**
       * Apos passar por todas as validações instanciar a geração de arquivo de retorno.
       */
      $oArquivoRetorno = new GeracaoArquivoRetornoEConsig($oInstituicao, $oCompetencia);
      $oArquivoRetorno->processar();
      $oRetorno->sArquivo = $oArquivoRetorno->getCaminhoArquivo();
      break;
  }
  
  db_fim_transacao();
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);