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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once("fpdf151/pdfnovo.php");
require_once("model/issqn/GeracaoArquivoSimplesNacional.model.php");

define('MENSAGENS', 'tributario.issqn.iss1_processararquivosimplesnacional.');

$oJson                = new services_json();
$oParametros          = $oJson->decode(utf8_decode(str_replace("\\", "", urldecode($_POST["json"]))));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';

try {

  switch ($oParametros->sExecucao) {

    case 'getCnae' :

      $aCnaes                        = array();
      $aCnaesInvalidos               = array();
      $oDaoArquivoSimplesImportacao  = new GeracaoArquivoSimplesNacional();

      $aCnaes = $oDaoArquivoSimplesImportacao->getCnae( $oParametros->iArquivo );

      if( empty($aCnaes) ){
        throw new BusinessException( _M( MENSAGENS . 'nenhum_cnae_encontrado' ) );
      }

      /**
       * Buscar CNAES não encontrados
       */
      $aCnaesInvalidos = $oDaoArquivoSimplesImportacao->getCnae( $oParametros->iArquivo, false );
      if( !empty($aCnaesInvalidos) ) {

        $aInvalidos = array( 'q71_estrutural' => 'Y' , 'q71_descr' => urlencode('CNAES NÃO ENCONTRADOS') );
        array_push( $aCnaes, $aInvalidos );
      }

      if( empty($aCnaes) ){
        throw new BusinessException( _M( MENSAGENS . 'nenhum_cnae_encontrado' ) );
      }

      $oRetorno->aCnaes = $aCnaes;
    break;

    case "gerar":

      $oGeracaoArquivoSimplesNacional = new GeracaoArquivoSimplesNacional();
      $oGeracaoArquivoSimplesNacional->setArquivo( $oParametros->iArquivo );

      if (!$oGeracaoArquivoSimplesNacional->isValido()) {
        throw new BusinessException(_M(MENSAGENS."preenchimento_obrigatorio"));
      }

      $oRetorno->sArquivo         = $oGeracaoArquivoSimplesNacional->gerarTxt();
      $oRetorno->sInconsistencias = $oGeracaoArquivoSimplesNacional->relatorioInconsistencias();
    break;

    case "getArquivos":

      $oRetorno->aArquivos = array();

      $oDaoArquivoSimplesImportacao = new cl_arquivosimplesimportacao();

      $sWhere = ($oParametros->lReprocessamento ? 'q64_processado = true' : '');

      $sSqlArquivoSimplesImportacao  = $oDaoArquivoSimplesImportacao->sql_query_file( null,
                                                                                      'q64_sequencial, q64_nomearquivo',
                                                                                      'q64_sequencial desc',
                                                                                      $sWhere );
      $rsDAOArquivoSimplesImportacao = $oDaoArquivoSimplesImportacao->sql_record( $sSqlArquivoSimplesImportacao );

      if ( $oDaoArquivoSimplesImportacao->numrows <= 0 ) {
        throw new BusinessException( _M( MENSAGENS . 'nenhum_arquivo_encontrado' ) );
      }

      $aArquivoSimplesImportacao = db_utils::getCollectionByRecord( $rsDAOArquivoSimplesImportacao );

      foreach ($aArquivoSimplesImportacao as $aDados ) {
        $oRetorno->aArquivos[] = array('iSequencial' => $aDados->q64_sequencial, 'sLabel' => $aDados->q64_nomearquivo);
      }
    break;

    case "getDataVencimento":

      $oDaoArquivoSimplesImportacao = new cl_arquivosimplesimportacao();

      $sSql                          = $oDaoArquivoSimplesImportacao->sql_query( $oParametros->iArquivo,
                                                                                 'q64_data, q64_datalimitevencimentos, q64_processado' );
      $rsDAOArquivoSimplesImportacao = $oDaoArquivoSimplesImportacao->sql_record( $sSql );

      if ($oDaoArquivoSimplesImportacao->numrows <= 0) {
        throw new BusinessException( _M( MENSAGENS . 'arquivo_nao_encontrado' ) );
      }

      $oArquivoSimplesImportacao = db_utils::fieldsMemory($rsDAOArquivoSimplesImportacao, 0);

      $dtData = '';

      if (!empty($oArquivoSimplesImportacao->q64_datalimitevencimentos)) {
        $oData  = new DBDate($oArquivoSimplesImportacao->q64_datalimitevencimentos);
        $dtData = $oData->convertTo(DBDate::DATA_PTBR);
      }

      $oRetorno->lProcessado = ($oArquivoSimplesImportacao->q64_processado == 't');
      $oRetorno->dtData      = $dtData;
    break;

    /**
     * Faz o processamento do arquivo quando necessário
     * Condições para ser feito o processamento:
     *   -- Estar na rotina de reprocessamento
     *   -- Não ter sido processado ainda
     */
    case 'validacaoAutomatica':

      $oDaoArquivoSimples = new cl_arquivosimplesimportacao();

      $sSql                = $oDaoArquivoSimples->sql_query( $oParametros->iArquivo,
                                                             'q64_datalimitevencimentos, q64_processado' );
      $rsDaoArquivoSimples = $oDaoArquivoSimples->sql_record( $sSql );

      if ($oDaoArquivoSimples->numrows <= 0) {
        throw new BusinessException( _M( MENSAGENS . 'arquivo_nao_encontrado' ) );
      }

      $oArquivoSimples = db_utils::fieldsMemory($rsDaoArquivoSimples, 0);

      /**
       * Verifica se não foi processado ainda ou se veio da rotina de reprocessamento
       */
      if ($oArquivoSimples->q64_processado == 'f' || $oParametros->lReprocessamento) {

        $oData  = new DBDate($oParametros->dtLimite);
        $dtData = $oData->convertTo(DBDate::DATA_EN);

        db_inicio_transacao();

        $oGeracaoArquivoSimplesNacional = new GeracaoArquivoSimplesNacional();

        $oGeracaoArquivoSimplesNacional->setArquivo( $oParametros->iArquivo );
        $oGeracaoArquivoSimplesNacional->setDataLimite( $dtData );
        $oGeracaoArquivoSimplesNacional->validacaoAutomatica();

        /**
         * Altera o arquivo que foi validado como processado e a data limite utilizada
         */
        $oDaoArquivoSimples->q64_datalimitevencimentos = $dtData;
        $oDaoArquivoSimples->q64_processado            = 't';
        $oDaoArquivoSimples->q64_sequencial            = $oParametros->iArquivo;
        $oDaoArquivoSimples->alterar( $oParametros->iArquivo );

        db_fim_transacao();
      }
    break;

    case "getEmpresas":

      $oArquivosSimples = new GeracaoArquivoSimplesNacional();
      $oArquivosSimples->setArquivo( $oParametros->iArquivo );
      $aEmpresas        = $oArquivosSimples->getEmpresasByCnae( $oParametros->sEstrutural );

      $oRetorno->aEmpresas = $aEmpresas;
    break;

    case "setAptos":

      $oArquivoSimples = new GeracaoArquivoSimplesNacional();
      $oArquivoSimples->setAptos($oParametros->oEmpresas, $oParametros->lApto);

    break;

  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);