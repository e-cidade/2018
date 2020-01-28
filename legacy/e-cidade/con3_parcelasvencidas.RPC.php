<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));

$oRetorno->status  = 1;

$sDataUsu  = "";
$sDataVenc = "";

switch($oParam->exec) {
    
  /*
   * Consulta valor total das parcelas vencidas
   */
  case "getSomaParcelasVencidas":
      
      try {
      	
        $nValorLancado         = 0;
        $nValorLancadoCorr     = 0;
        $nValorLancadoJuro     = 0;
        $nValorLancadoMulta    = 0;
        $nValorLancadoDesconto = 0;
        $nValorLancadoTotal    = 0;
        
        $nValorDebito          = 0;
        $nValorDebitoCor       = 0;
        $nValorDebitoJuro      = 0;
        $nValorDebitoMulta     = 0;
        $nValorDebitoDesconto  = 0;
        $nValorDebitoTotal     = 0;
      	
        $iNumpre              = 0;
      	$sDataUsu             = date("Y-m-d",$oParam->data);
        foreach ($oParam->numpres as $oNumpres) {
        	
          $rsSqlDebitosNumpre = debitos_numpre($oNumpres, 0, $oParam->tipodebito, $oParam->data,
                                              db_getsession("DB_anousu"), "", "", "", "", true);
          $iNumRowsDebitos    = pg_num_rows($rsSqlDebitosNumpre);
          
          if ($oNumpres != $iNumpre) {
          	
          	$iNumpre = $oNumpres;
	          for ($xInd = 0; $xInd < $iNumRowsDebitos; $xInd++) {
	            
	            $oDebitos  = db_utils::fieldsMemory($rsSqlDebitosNumpre,$xInd);
	            $sDataUsu  = str_replace("-","",$sDataUsu);
	            $sDataVenc = str_replace("-","",$oDebitos->k00_dtvenc);
	            
	            
	            $nValorLancado         += $oDebitos->vlrhis;
	            $nValorLancadoCorr     += $oDebitos->vlrcor;
	            $nValorLancadoJuro     += $oDebitos->vlrjuros;
	            $nValorLancadoMulta    += $oDebitos->vlrmulta;
	            $nValorLancadoDesconto += $oDebitos->vlrdesconto;
	            $nValorLancadoTotal    += $oDebitos->total;
	            if ($sDataVenc < $sDataUsu) {
	                
	              $nValorDebito         += $oDebitos->vlrhis;
	              $nValorDebitoCor      += $oDebitos->vlrcor;
	              $nValorDebitoJuro     += $oDebitos->vlrjuros;
	              $nValorDebitoMulta    += $oDebitos->vlrmulta;
	              $nValorDebitoDesconto += $oDebitos->vlrdesconto;
	              $nValorDebitoTotal    += $oDebitos->total;
	            }
	          }
          }
          
        }

        $oRetorno->valorlancado             = db_formatar($nValorLancado,'f');
        $oRetorno->valorlancadocorr         = db_formatar($nValorLancadoCorr,'f');
        $oRetorno->valorlancadojuro         = db_formatar($nValorLancadoJuro,'f');
        $oRetorno->valorlancadomulta        = db_formatar($nValorLancadoMulta,'f');
        $oRetorno->valorlancadodesconto     = db_formatar($nValorLancadoDesconto,'f');
        $oRetorno->valorlancadototal        = db_formatar($nValorLancadoTotal,'f');
        
        $oRetorno->valordebito              = db_formatar($nValorDebito,'f');
        $oRetorno->valorcor                 = db_formatar($nValorDebitoCor,'f');
        $oRetorno->valorjuro                = db_formatar($nValorDebitoJuro,'f');
        $oRetorno->valormulta               = db_formatar($nValorDebitoMulta,'f');
        $oRetorno->valordesconto            = db_formatar($nValorDebitoDesconto,'f');
        $oRetorno->valortotal               = db_formatar($nValorDebitoTotal,'f');
        
        $oRetorno->somavalorlancado         = db_formatar(($nValorLancado - $nValorDebito),'f');
        $oRetorno->somavalorlancadocorr     = db_formatar(($nValorLancadoCorr - $nValorDebitoCor),'f');
        $oRetorno->somavalorlancadojuro     = db_formatar(($nValorLancadoJuro - $nValorDebitoJuro),'f');
        $oRetorno->somavalorlancadomulta    = db_formatar(($nValorLancadoMulta - $nValorDebitoMulta),'f');
        $oRetorno->somavalorlancadodesconto = db_formatar(($nValorLancadoDesconto - $nValorDebitoDesconto),'f');
        $oRetorno->somavalorlancadototal    = db_formatar(($nValorLancadoTotal - $nValorDebitoTotal),'f');
      	
      } catch (Exception $eExeption){
        
        $oRetorno->status = 2;
        $oRetorno->erro   = urlencode(str_replace("\\n", "\n", $eExeption->getMessage()));
      }
      
      break;

}

echo $oJson->encode($oRetorno);   
?>