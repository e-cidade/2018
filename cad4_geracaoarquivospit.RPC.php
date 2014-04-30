<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/JSON.php");

$oJson       = new services_json();
$oParametros = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno    = new stdClass();

$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

define('MENSAGENS', 'tributario.cadastro.cad4_geracaoarquivospit.');

try{

  switch ($oParametros->sExecucao) {

    case 'gerarArquivos':

      require_once('model/cadastro/GeracaoArquivoPit.model.php');
      require_once('model/cadastro/GeracaoRelatorioInconsistenciasPit.model.php');

      $oRelatorioInconsistencias = new GeracaoRelatorioInconsistenciasPit();
      $aPeriodos                 = $oParametros->aSemestre;
      $aArquivos                 = $oParametros->aArquivos;
      $iAno                      = $oParametros->sExercicio;
      $aCaminhosArquivos         = array();
      $aArquivosInconsistencias  = array();
      $iPeriodo  				         = $aPeriodos[0];

      /**
       * Pega o ultimo periodo, para os arquivos de IPTU e Logradouros,
       * caso seja selecionado os 2 periodos.
       */
      if( sizeof($aPeriodos) > 1 ){
        $iPeriodo = $aPeriodos[1];
      }

      /**
       * Percorre todos os arquivos selecionados,
       * inserindo os arquivos gerados no array $aArquivos
       */
      foreach ($aArquivos as $iArquivo) {

        /**
         * Inicializa o array de Erros
         */
        $aErros = array();

        /**
         * Se for IPTU ou Logradouros, executa apenas uma vez a geração de arquivos,
         * senão gera um arquivo para cada semestre
         */
        if ( $iArquivo == GeracaoArquivoPit::IPTU || $iArquivo == GeracaoArquivoPit::LOGRADOUROS ) {

            $oGeracaoArquivo = GeracaoArquivoPit::getInstanceByArquivo($iArquivo, $iAno, $iPeriodo);
            $sCaminho        = $oGeracaoArquivo->geraArquivo();
            $aErros          = $oGeracaoArquivo->getErros();

            if ( $sCaminho ) {
              $aCaminhosArquivos[] = $sCaminho;
            }
        } else {

          foreach ($aPeriodos as $iPeriodo) {

             $oGeracaoArquivo = GeracaoArquivoPit::getInstanceByArquivo($iArquivo, $iAno, $iPeriodo);
             $sCaminho        = $oGeracaoArquivo->geraArquivo();
             $aErros          = array_merge( $aErros, $oGeracaoArquivo->getErros() );

             if ( $sCaminho ){
              $aCaminhosArquivos[] = $sCaminho;
            }
          }
        }

        if ( sizeof($aErros) > 0) {
          $aArquivosInconsistencias[] = $oRelatorioInconsistencias->gerar($aErros, $iArquivo, $iAno, $aPeriodos);
        }
      }

      /**
       * Verifica se foi gerado pelo menos 1 arquivo pit ou algum arquivo de inconsistências,
       * se foi, retorna o array com os arquivos senão retorna mensagem de erro.
       */
      if (empty($aCaminhosArquivos) && empty($aArquivosInconsistencias)) {
        throw new BusinessException( _M( MENSAGENS . "nenhum_arquivo" ) );
      }

      $oRetorno->aArquivos        = $aCaminhosArquivos;
      $oRetorno->aInconsistencias = $aArquivosInconsistencias;

    break;

    default:
      throw new ParameterException( _M( MENSAGENS . "opcao_invalida" ) );
    break;
  }

}catch(Exception $oErro){

  $oRetorno->iStatus = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode( $oRetorno->sMensagem );
echo $oJson->encode($oRetorno);