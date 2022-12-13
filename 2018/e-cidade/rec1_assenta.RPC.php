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

define('MENSAGENS', 'recursoshumanos.rh.rec1_assenta.');

$oJson                = new services_json();
$oParametros          = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->iStatus    = 1;
$oRetorno->sMensagem  = '';
$aRegistros           = array();

try {

  $iCodigoAssentamento = null;
  if (!empty($oParametros->iCodigoAssentamento)){
    $iCodigoAssentamento = $oParametros->iCodigoAssentamento;
  }

  switch ($oParametros->sExec) {

    case 'getSaldoDiasDireito' :

      if( !isset( $oParametros->iCodigoPeriodoAquisitivo ) || empty($oParametros->iCodigoPeriodoAquisitivo) ){
        throw new BusinessException( _M( MENSAGENS . 'periodoaquisitivo_nao_informado' ) );
      }

      $iCodigoPeriodoAquisitivo  = $oParametros->iCodigoPeriodoAquisitivo;      
      $oDiasDireito              = PeriodoAquisitivoAssentamento::getSaldoDiasDireito($iCodigoPeriodoAquisitivo, $iCodigoAssentamento);
      
      $oRetorno->iDiasDireito    = $oDiasDireito->saldodiasdireito;
     
    break;

    case 'validaSaldoDiasDireito' :
      
      if (!isset($oParametros->iTipoAssentamento)) {
        throw new BusinessException( _M( MENSAGENS . 'assentamento_nao_informado' ) );
      }

      if( !isset( $oParametros->iCodigoPeriodoAquisitivo ) || empty($oParametros->iCodigoPeriodoAquisitivo) ){
        throw new BusinessException( _M( MENSAGENS . 'periodoaquisitivo_nao_informado' ) );
      }

      $iTipoAssentamento         = $oParametros->iTipoAssentamento;
      $iDias                     = $oParametros->iDias;
      $iCodigoPeriodoAquisitivo  = $oParametros->iCodigoPeriodoAquisitivo;
      $iSequencialAssentamento   = $oParametros->iSequencialAssentamento;
      $iDiasDireito              = 0;
      $iDiasDireito              = PeriodoAquisitivoAssentamento::validaSaldoDiasDireito($iCodigoPeriodoAquisitivo, 
                                                                                         $iTipoAssentamento, 
                                                                                         $iDias,
                                                                                         $iCodigoAssentamento,
                                                                                         $iSequencialAssentamento);

      if( $iDiasDireito == 0 ){
        throw new BusinessException( _M( MENSAGENS . 'periodoaquisitivo_sem_dias_direito' ) );
      }

      $oRetorno->lSaldo       = true;
    break;

    case 'getPeriodoAquisitivo':

      if (!isset($oParametros->iCodigoAssenta)) {
        throw new BusinessException( _M( MENSAGENS . 'assentamento_nao_informado' ) );
      }

      $oPeriodoAquisitivoAssentamento = PeriodoAquisitivoAssentamento::getPeriodoAquisitivoAssentamento( 
                             new Assentamento( $oParametros->iCodigoAssenta ), $oParametros->iMatriculaServidor);

      if (!$oPeriodoAquisitivoAssentamento) {
        return null;
      }

      $oRetorno->iCodigoPeriodoAquisitivo = $oPeriodoAquisitivoAssentamento->getPeriodoAquisitivo()->getCodigo();

    break;

    case 'validaPeriodoDiasDireito':

      $lValidaPeriodoDireito = PeriodoAquisitivoAssentamento::validaPeriodoGozo( $oParametros->iServidor,   
                                                                                 $oParametros->iTipoAssentamento, 
                                                                                 $oParametros->sDataInicial, 
                                                                                 $oParametros->sDataFinal,
                                                                                 $oParametros->iSequencialAssentamento );


    break;
  }
} catch (Exception $oErro) {

  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = $oErro->getMessage();
}

$oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
echo $oJson->encode($oRetorno);