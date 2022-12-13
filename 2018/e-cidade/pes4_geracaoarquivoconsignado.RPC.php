<?php
/*
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
 */


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("model/pessoal/arquivos/consignet/GeracaoArquivoConsignet.model.php");


define('ARQUIVO_MENSAGEM', 'recursoshumanos.pessoal.pes4_geracaoarquivoretornoconsignado.');

$oJson                = new services_json();
$oParametros          = $oJson->decode(utf8_decode(str_replace("\\", "", urldecode($_POST["json"]))));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';

try {

  switch ($oParametros->sExecucao) {

    case 'gerarArquivoMargemConsignet' :

      $oDaoArquivoConsignet = new GeracaoArquivoConsignet( $oParametros->iAnoUsu, $oParametros->iMesUsu );
      $sArquivoConsignet    = $oDaoArquivoConsignet->gerarArquivoMargem();

      if ( $sArquivoConsignet == 'competencia_informada_ultrapassada') {
        throw new BusinessException( _M( ARQUIVO_MENSAGEM . 'competencia_informada_ultrapassada' ) );
      }

      if( empty($sArquivoConsignet) ){
        throw new BusinessException( _M( ARQUIVO_MENSAGEM . 'erro_gerar_arquivo' ) );
      }

      $oRetorno->sArquivoConsignet = urlencode($sArquivoConsignet);
      $aArquivo                    = explode('/', $sArquivoConsignet);
      $oRetorno->sNomeArquivo      = urlencode($aArquivo[sizeof($aArquivo)-1]);
    break;

    case 'processamentoRetornoArquivoConsignet' :

      $iAno              = $oParametros->iAno;
      $iMes              = $oParametros->iMes;
      $oCompetencia      = new DBCompetencia($iAno, $iMes);
      $oCompetenciaAtual = DBPessoal::getCompetenciaFolha();
      $oInstituicao      = InstituicaoRepository::getInstituicaoSessao();

      /**
       * Verifica se a competência informada é maior que a competência atual da folha.
       */
      if ($oCompetencia->comparar($oCompetenciaAtual, DBCompetencia::COMPARACAO_MAIOR)) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM . "competencia_informada_ultrapassada"));
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
      $lPossuiArquivo = ArquivoConsignetRepository::hasArquivoCompetencia($oCompetencia, $oInstituicao);
      if (!$lPossuiArquivo) {
        throw new BusinessException(_M(ARQUIVO_MENSAGEM . "arquivo_nao_importado"));
      }

      /**
       * Apos passar por todas as validações instanciar a geração de arquivo de retorno.
       */
      $oArquivoRetorno = new GeracaoArquivoRetornoConsignet($oInstituicao, $oCompetencia);
      $oArquivoRetorno->processar();
      $oRetorno->sArquivo = urlencode($oArquivoRetorno->getCaminhoArquivo());
    break;

    case 'retornarCompetencia' :

      $oCompetencia = DBPessoal::getCompetenciaFolha();

      if (!DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
        $oCompetencia = $oCompetencia->getCompetenciaAnterior();
      }

      $oRetorno->iAno = $oCompetencia->getAno();
      $oRetorno->iMes = $oCompetencia->getMes();
    break;

  }

} catch (Exception $oErro) {

  $oRetorno->iStatus   = 0;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);
