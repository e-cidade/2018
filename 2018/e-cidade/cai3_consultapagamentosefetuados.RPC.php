<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/JSON.php';  

$oJson                  = new services_json();
$oRetorno               = new stdClass();

$oParametros            = $oJson->decode(str_replace("\\","",$_POST["json"])); 
$oRetorno->iStatus      = 1;
$oRetorno->sMensagem    = '';

try {
	
  switch ($oParametros->exec) {

		/**
		 * --------------------------------------------------------------------------------------------
		 * PRORROGAÇÕES DE VENCIMENTO EFETUADOS
		 * --------------------------------------------------------------------------------------------
		 */
		case 'getProrrogacoes' :

			if ( $oParametros->iNumpre == 0 ) {
				break;
			}

			$oArrevenc         = db_utils::getDao('arrevenc');
			$sCampos           = " k00_dtini,                                                                   ";
			$sCampos          .= " k00_dtfim, ( (                                                               ";
			$sCampos          .= " 	case                                                                        ";
			$sCampos          .= " 		when k00_dtfim is null                                                    ";
			$sCampos          .= " 			then current_date                                                       ";
			$sCampos          .= " 		else k00_dtfim                                                            ";
      $sCampos          .= "  end ) + 1 ) - k00_dtini as dia                                              ";
			$sWhere            = " k00_numpre = {$oParametros->iNumpre} and k00_numpar = {$oParametros->iNumpar}";
			$sSqlProrrogacoes  = $oArrevenc->sql_query(null, $sCampos, 'k00_dtini', $sWhere);
			$rsProrrogacoes    = $oArrevenc->sql_record($sSqlProrrogacoes);

			if ($oArrevenc->numrows > 0 && $rsProrrogacoes) { 
				$oRetorno->aProrrogacoes = db_utils::getCollectionByRecord($rsProrrogacoes, 0, false, false, true);
			} 

		break;

		/**
		 * --------------------------------------------------------------------------------------------
		 * Dados da baixa 
		 * --------------------------------------------------------------------------------------------
		 */
		case 'getDadosBaixa' :
    
			$oDisbanco      = db_utils::getDao('disbanco');
			$sSqlDadosBaixa = $oDisbanco->sql_queryDadosBaixa( $oParametros->iNumpre, $oParametros->iNumpar );
			$rsDadosBaixa   = $oDisbanco->sql_record( $sSqlDadosBaixa );

			if ( $oDisbanco->numrows > 0 ) {			
				$oRetorno->aDadosBaixa = db_utils::getCollectionByRecord( $rsDadosBaixa, false, false, true );
			}

		break;

		/**
		 * --------------------------------------------------------------------------------------------
		 * Históricos 
		 * --------------------------------------------------------------------------------------------
		 */
		case 'getHistoricos' :

			$oArrehist      = db_utils::getDao('arrehist');
			$sCampos        = 'k01_descr, k00_dtoper, k00_hora, nome, k00_histtxt';
			$sWhere         = "k00_numpre = $oParametros->iNumpre "; 

			/**
			 * Possui parcelas  
			 */
			if ( $oParametros->iNumpar > 0 ) {
			  
				$sWhere      .= "and k00_numpar = (case when exists (select 1 
                                                                    from recibo 
                                                              where k00_numpre =  8438925
                                                              union
                                                              select 1 
                                                                from recibopaga 
                                                               where k00_numnov = 8438925)
                                                     then 0
                                                else {$oParametros->iNumpar}
								                       		 END
                                          )  "; 
			}
			
			$sSqlHistoricos = $oArrehist->sql_query( null, $sCampos, "k00_dtoper", $sWhere ); 
			
			$rsHistoricos   = $oArrehist->sql_record( $sSqlHistoricos );

			if ( $oArrehist->numrows > 0 ) {
				$oRetorno->aHistoricos = db_utils::getCollectionByRecord($rsHistoricos, 0, false, false, true);
			}

		break;

    /**
		 * --------------------------------------------------------------------------------------------
     * Lançamentos efetuados
		 * --------------------------------------------------------------------------------------------
     */
    case 'getLancamentosEfetuados' :

			$oDisbanco                = db_utils::getDao('disbanco');
			$sSqlLancamentosEfetuados = $oDisbanco->sql_queryLancamentosEfetuados( $oParametros->iNumpre );
			$rsLancamentosEfetuados   = $oDisbanco->sql_record( $sSqlLancamentosEfetuados );

			if ( $oDisbanco->numrows > 0 ) {
				$oRetorno->aLancamentosEfetuados = db_utils::getCollectionByRecord($rsLancamentosEfetuados, 0, false, false, true);
			}

    break;

    default:
      throw new Exception("Nenhuma Opção Definida");
    break;
  }

  $oRetorno->sMensagem = urlencode($oRetorno->sMensagem);
  echo $oJson->encode($oRetorno);
  
} catch (Exception $eErro){
  
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}

?>