<?
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

require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("fpdf151/pdf.php");

$oGet             = db_utils::postMemory($_GET);

$dDataDebitos     = $oGet->dataDebitos;
$sListaExercicios = $oGet->sExercicios;

$sAte              = '';
$sTituloExercicios = '';
$aListaExercicios  = explode(",",$sListaExercicios);
$iNroExercicios    = count($aListaExercicios);

foreach ( $aListaExercicios as $iInd => $iExercicio ) {
	
  if ( $iInd == 0 ) {
  	$sTituloExercicios .= $iExercicio;
  } else {
  	$iExercicioAnterior = $aListaExercicios[$iInd-1]; 
  	if ( $iExercicio == ($iExercicioAnterior+1) ) {
  		$sAte = " até {$iExercicio}";
  		if ( $iNroExercicios == ($iInd+1) ) {
  			$sTituloExercicios .= $sAte;
  		}
  	} else {
  		$sTituloExercicios .= "{$sAte}, {$iExercicio}";
      $sAte = "";
  	}
  }
	
}

$aResumos          = array();
$aResumoCurtoPrazo = array();
$aResumoLongoPrazo = array();

$aAgrupador['proced']      = 'v01_proced';
$aAgrupador['receita']     = 'receit';
$aAgrupador['tipo_proced'] = 'v03_tributaria';
$aAgrupador['tipo_debito'] = 'k03_tipo';

// Consulta os débitos pagos de 3 anos anteriores aos exercícios selecionados

$aDataDebitos       = explode("-", $dDataDebitos);

$oDaoDivida         = db_utils::getDao('divida');

$sSqlDebitosPagos   = $oDaoDivida->sql_queryDebitosAnteriores($aDataDebitos[0]);
$rsDebitosPagos     = $oDaoDivida->sql_record($sSqlDebitosPagos);
$iNroDebitosPagos   = pg_num_rows($rsDebitosPagos);

for ( $iInd = 0; $iInd < $iNroDebitosPagos; $iInd++ ) {
	
	$oDebitosPagos = db_utils::fieldsMemory($rsDebitosPagos, $iInd);
	
	if ( isset($aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]) ) {
		$aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]['nTotal'] += $oDebitosPagos->total;
	} else {
		$aDebitosPagos[$oDebitosPagos->k03_tipo][$oDebitosPagos->v01_proced]['nTotal']  = $oDebitosPagos->total;
	}
	
  foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {
    if ( isset($aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]) ) {
      $aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal'] += $oDebitosPagos->total;
    } else {
      $aResumoDebitosPagos[$sDescrAgrupa][$oDebitosPagos->$sCampo]['nTotal']  = $oDebitosPagos->total;
    }
  }	
	
}

$sSqlResumoGeral    = $oDaoDivida->sql_queryProcessamentoResumoGeralDivida($dDataDebitos, $sListaExercicios);
$rsResumoGeral      = $oDaoDivida->sql_record($sSqlResumoGeral);
$iLinhasResumoGeral = $oDaoDivida->numrows;
 
$aLongoPrazo = array();
$aCurtoPrazo = array();

for ( $iInd=0; $iInd < $iLinhasResumoGeral; $iInd++ ) {
	
	$oResumo = db_utils::fieldsMemory($rsResumoGeral,$iInd);
	
	$dtDataLimite = ($oResumo->v01_exerc + 1)."-12-31";
	
	if (  in_array($oResumo->k03_tipo,array(5,15,18)) || ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc > $dtDataLimite ) ) {
	  
		if ( isset($aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]) ) {
			$aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrHist']   += $oResumo->vlrhis;
		  $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrCorr']   += $oResumo->vlrcor;
		  $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nMulta']     += $oResumo->multa;
		  $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nJuros']     += $oResumo->juros;
		  $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nTotal']     += $oResumo->total;
		} else {
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['iTipoProced'] = $oResumo->v03_tributaria;
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrHist']    = $oResumo->vlrhis;
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrCorr']    = $oResumo->vlrcor;
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nMulta']      = $oResumo->multa;
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nJuros']      = $oResumo->juros;
      $aLongoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nTotal']      = $oResumo->total;			
		}
	  
	} else if ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc <= $dtDataLimite ) {
		
		if ( isset($aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]) ) {
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrHist']   += $oResumo->vlrhis;
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrCorr']   += $oResumo->vlrcor;
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nMulta']     += $oResumo->multa;
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nJuros']     += $oResumo->juros;
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nTotal']     += $oResumo->total;
		} else {
			$aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['iTipoProced'] = $oResumo->v03_tributaria;
      $aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrHist']    = $oResumo->vlrhis;
      $aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nVlrCorr']    = $oResumo->vlrcor;
      $aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nMulta']      = $oResumo->multa;
      $aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nJuros']      = $oResumo->juros;
      $aCurtoPrazo[$oResumo->k03_tipo][$oResumo->v01_proced]['nTotal']      = $oResumo->total;			
		}
  			
	}
	
	
	$aDescrTipo[$oResumo->k03_tipo]             = $oResumo->descrtipo;                          
	$aDescrProced[$oResumo->v01_proced]         = $oResumo->descrproced;
	$aDescrTipoProced[$oResumo->v03_tributaria] = $oResumo->descrtipoproced;
	
}


foreach ( $aLongoPrazo as $iTipoDebito => $aDadosLongoPrazo ) {
	foreach ( $aDadosLongoPrazo as $iProcedencia =>$aValoresLongoPrazo) {
		if ( isset($aDebitosPagos[$iTipoDebito][$iProcedencia])) {

			$nTotalPago   = $aDebitosPagos[$iTipoDebito][$iProcedencia]['nTotal'];
      $nTotalPago   = round(( ($nTotalPago/3) * 2 ),2); 
      $nTotalProced = $aValoresLongoPrazo['nTotal'];

      // Percentual que será subtraído do logon prazo e incluído no longo prazo
      $nPercentual  = round(( ($nTotalPago*100) / $nTotalProced ),2);
      
      $nValorHist  = ( ($aValoresLongoPrazo['nVlrHist']/100) * $nPercentual );
      $nValorCorr  = ( ($aValoresLongoPrazo['nVlrCorr']/100) * $nPercentual );
      $nValorMulta = ( ($aValoresLongoPrazo['nMulta']/100) * $nPercentual );
      $nValorJuros = ( ($aValoresLongoPrazo['nJuros']/100) * $nPercentual );
      $nValorTotal = ( ($aValoresLongoPrazo['nTotal']/100) * $nPercentual );
			
      if ( $nValorTotal < $aValoresLongoPrazo['nTotal'] ) {
      	
	      $aLongoPrazo[$iTipoDebito][$iProcedencia]['nVlrHist'] -= $nValorHist;
	      $aLongoPrazo[$iTipoDebito][$iProcedencia]['nVlrCorr'] -= $nValorCorr;
	      $aLongoPrazo[$iTipoDebito][$iProcedencia]['nMulta']   -= $nValorMulta;
	      $aLongoPrazo[$iTipoDebito][$iProcedencia]['nJuros']   -= $nValorJuros;
	      $aLongoPrazo[$iTipoDebito][$iProcedencia]['nTotal']   -= $nValorTotal;

	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['iTipoProced'] = $aValoresLongoPrazo['iTipoProced'];
	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nVlrHist']    = $nValorHist;
	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nVlrCorr']    = $nValorCorr;
	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nMulta']      = $nValorMulta;
	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nJuros']      = $nValorJuros;
	      $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nTotal']      = $nValorTotal;	      
	      
      } else {
      	
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['iTipoProced'] = $aValoresLongoPrazo['iTipoProced'];
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nVlrHist']    = $aValoresLongoPrazo['nVlrHist'];
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nVlrCorr']    = $aValoresLongoPrazo['nVlrCorr'];
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nMulta']      = $aValoresLongoPrazo['nMulta'];
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nJuros']      = $aValoresLongoPrazo['nJuros'];
        $aCurtoPrazo[$iTipoDebito][$iProcedencia]['nTotal']      = $aValoresLongoPrazo['nTotal'];      	
      	
        unset($aLongoPrazo[$iTipoDebito][$iProcedencia]);      	
      }
		}
	}
}

// Remove Tipo de Débito sem valor
foreach ( $aLongoPrazo as $iTipoDebito => $aDadosLongoPrazo ) {
	if ( count($aDadosLongoPrazo) == 0 ) {
		unset($aLongoPrazo[$iTipoDebito]);
	}
}

for ( $iInd=0; $iInd < $iLinhasResumoGeral; $iInd++ ) {
  
  $oResumo = db_utils::fieldsMemory($rsResumoGeral,$iInd);
  
  $dtDataLimite = ($oResumo->v01_exerc + 1)."-12-31";

  foreach ( $aAgrupador as $sDescrAgrupa => $sCampo ) {
  	
	  $aDescrTipo[$oResumo->k03_tipo]             = $oResumo->descrtipo;                          
	  $aDescrProced[$oResumo->v01_proced]         = $oResumo->descrproced;
	  $aDescrTipoProced[$oResumo->v03_tributaria] = $oResumo->descrtipoproced;
  
    if ( $sDescrAgrupa == 'proced' ) {
      $sDescricao = $oResumo->descrproced;
    } else if ( $sDescrAgrupa == 'tipo_proced' ) {
      $sDescricao = $oResumo->descrtipoproced;
    } else if ( $sDescrAgrupa == 'receita' ) {
      $sDescricao = $oResumo->descrreceit;
    } else {
      $sDescricao = $oResumo->descrtipo;
    }  	
  	
    if (  in_array($oResumo->k03_tipo,array(5,15,18)) || ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc > $dtDataLimite ) ) {
      
      if ( isset($aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]) ) {
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']   += $oResumo->vlrhis;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']   += $oResumo->vlrcor;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']     += $oResumo->multa;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']     += $oResumo->juros;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']     += $oResumo->total;
      } else {
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['sDescricao']  = $sDescricao;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']    = $oResumo->vlrhis;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']    = $oResumo->vlrcor;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']      = $oResumo->multa;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']      = $oResumo->juros;
        $aResumoLongoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']      = $oResumo->total;      
      }
      
    } else if ( in_array($oResumo->k03_tipo,array(6,13)) && $oResumo->dtvenc <= $dtDataLimite ) {
      
      if ( isset($aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]) ) {
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']   += $oResumo->vlrhis;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']   += $oResumo->vlrcor;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']     += $oResumo->multa;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']     += $oResumo->juros;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']     += $oResumo->total;
      } else {
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['sDescricao']  = $sDescricao;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']    = $oResumo->vlrhis;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']    = $oResumo->vlrcor;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']      = $oResumo->multa;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']      = $oResumo->juros;
        $aResumoCurtoPrazo[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']      = $oResumo->total; 
      }
          
    }
    
    if ( isset($aResumos[$sDescrAgrupa][$oResumo->$sCampo]) ) {
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist'] += $oResumo->vlrhis;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr'] += $oResumo->vlrcor;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']   += $oResumo->multa;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']   += $oResumo->juros;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']   += $oResumo->total;
    } else {
    	$aResumos[$sDescrAgrupa][$oResumo->$sCampo]['sDescricao'] = $sDescricao;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nVlrHist']   = $oResumo->vlrhis;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nVlrCorr']   = $oResumo->vlrcor;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nMulta']     = $oResumo->multa;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nJuros']     = $oResumo->juros;
      $aResumos[$sDescrAgrupa][$oResumo->$sCampo]['nTotal']     = $oResumo->total;    
    }
  }
}

foreach ( $aResumoLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {
  
  foreach ( $aDadosLongoPrazo as $sCampoAgrupa =>$aValoresLongoPrazo) {
    
    if ( isset($aResumoDebitosPagos[$sTipoAgrupa][$sCampoAgrupa])) {

      $nTotalPago   = $aResumoDebitosPagos[$sTipoAgrupa][$sCampoAgrupa]['nTotal'];
      $nTotalPago   = round(( ($nTotalPago/3) * 2 ),2); 
      $nTotalProced = $aValoresLongoPrazo['nTotal'];

      // Percentual que será subtraído do logon prazo e incluído no longo prazo
      $nPercentual  = round(( ($nTotalPago*100) / $nTotalProced ),2);
      
      $nValorHist  = ( ($aValoresLongoPrazo['nVlrHist']/100) * $nPercentual );
      $nValorCorr  = ( ($aValoresLongoPrazo['nVlrCorr']/100) * $nPercentual );
      $nValorMulta = ( ($aValoresLongoPrazo['nMulta']/100) * $nPercentual );
      $nValorJuros = ( ($aValoresLongoPrazo['nJuros']/100) * $nPercentual );
      $nValorTotal = ( ($aValoresLongoPrazo['nTotal']/100) * $nPercentual );
      
      if ( $nValorTotal < $aValoresLongoPrazo['nTotal'] ) {
        
        $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']  -= $nValorHist;
        $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']  -= $nValorCorr;
        $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']    -= $nValorMulta;
        $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']    -= $nValorJuros;
        $aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']    -= $nValorTotal;
        
        if ( isset($aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   += $nValorHist;
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   += $nValorCorr;
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     += $nValorMulta;
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     += $nValorJuros;
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     += $nValorTotal;        
        } else {
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['sDescricao'] = $aValoresLongoPrazo['sDescricao'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   = $nValorHist;
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   = $nValorCorr;
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     = $nValorMulta;
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     = $nValorJuros;
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     = $nValorTotal;        	
        }
        
      } else {
        
        if ( isset($aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]) ) {
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   += $aValoresLongoPrazo['nVlrHist'];
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   += $aValoresLongoPrazo['nVlrCorr'];
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     += $aValoresLongoPrazo['nMulta'];
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     += $aValoresLongoPrazo['nJuros'];
	        $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     += $aValoresLongoPrazo['nTotal'];
        } else {
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['sDescricao'] = $aValoresLongoPrazo['sDescricao'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrHist']   = $aValoresLongoPrazo['nVlrHist'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nVlrCorr']   = $aValoresLongoPrazo['nVlrCorr'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nMulta']     = $aValoresLongoPrazo['nMulta'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nJuros']     = $aValoresLongoPrazo['nJuros'];
          $aResumoCurtoPrazo[$sTipoAgrupa][$sCampoAgrupa]['nTotal']     = $aValoresLongoPrazo['nTotal'];	      	         
	      }
        
        unset($aResumoLongoPrazo[$sTipoAgrupa][$sCampoAgrupa]);      
         
      }
    }
  }
}


foreach ( $aResumoLongoPrazo as $sTipoAgrupa => $aDadosLongoPrazo ) {
  if ( count($aDadosLongoPrazo) == 0 ) {
    unset($aResumoLongoPrazo[$sTipoAgrupa]);
  }
}


$head2 = 'RESUMO GERAL DA DÍVIDA';
$head3 = "Exercicíos Selecionados : {$sTituloExercicios} ";
$head4 = "Cálculo na Data : ".db_formatar($dDataDebitos,'d');
$head5 = '';

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetTextColor(0,0,0);
$oPdf->SetFillColor(220);

$iFonte = 7;
$iAlt   = 5 ;

$nTotalGeralHist  = 0;
$nTotalGeralMulta = 0;
$nTotalGeralJuros = 0;
$nTotalGeral      = 0;

ksort($aCurtoPrazo);
ksort($aLongoPrazo);

$oPdf->AddPage();

$oPdf->SetFont('Arial','B',$iFonte+3);
$oPdf->Cell(100,$iAlt,'Dívida Ativa de Curto Prazo',0,1,'L',0);
$oPdf->SetFont('Arial','B',$iFonte);

foreach ( $aCurtoPrazo as $iTipoDebito => $aDadosCurtoPrazo ) {
  
  $nTotalHist  = 0;
  $nTotalMulta = 0;
  $nTotalJuros = 0;
  $nTotal      = 0; 
  $aResumoProcedencia = array();
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(50,$iAlt,$iTipoDebito." - ".$aDescrTipo[$iTipoDebito],0,1,'L',0);

  $oPdf->SetX(20);
  $oPdf->Cell(45,$iAlt,'Tipo de Procedência'     ,0,0,'C',1);
  $oPdf->Cell(55,$iAlt,'Descrição da Procedência',0,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Histórico'           ,0,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Multa'               ,0,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Juros'               ,0,0,'C',1); 
  $oPdf->Cell(20,$iAlt,'Total'                   ,0,1,'C',1);
  
  foreach ( $aDadosCurtoPrazo as $iProcedencia => $aValoresCurtoPrazo ) {
    
    $oPdf->SetFont('Arial','',$iFonte);
    
    $oPdf->SetX(20);
    $oPdf->Cell(45,$iAlt,$aDescrTipoProced[$aValoresCurtoPrazo['iTipoProced']]   ,0,0,'L',0);
    $oPdf->Cell(55,$iAlt,$aDescrProced[$iProcedencia]                     ,0,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nVlrHist']        ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nMulta']          ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nJuros']          ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nTotal']          ,'f'),0,1,'R',0);    

    $nTotalHist  += $aValoresCurtoPrazo['nVlrHist'];
    $nTotalMulta += $aValoresCurtoPrazo['nMulta'];
    $nTotalJuros += $aValoresCurtoPrazo['nJuros'];
    $nTotal      += $aValoresCurtoPrazo['nTotal'];
    
    $iTipoProcedencia = $aValoresCurtoPrazo['iTipoProced'];
    
    if ( isset($aResumoProcedencia[$iTipoProcedencia]) ) {
      $aResumoProcedencia[$iTipoProcedencia]['nVlrHist'] += $aValoresCurtoPrazo['nVlrHist']; 
      $aResumoProcedencia[$iTipoProcedencia]['nMulta']   += $aValoresCurtoPrazo['nMulta']; 
      $aResumoProcedencia[$iTipoProcedencia]['nJuros']   += $aValoresCurtoPrazo['nJuros']; 
      $aResumoProcedencia[$iTipoProcedencia]['nTotal']   += $aValoresCurtoPrazo['nTotal']; 
    } else {
      $aResumoProcedencia[$iTipoProcedencia]['nVlrHist'] = $aValoresCurtoPrazo['nVlrHist']; 
      $aResumoProcedencia[$iTipoProcedencia]['nMulta']   = $aValoresCurtoPrazo['nMulta']; 
      $aResumoProcedencia[$iTipoProcedencia]['nJuros']   = $aValoresCurtoPrazo['nJuros']; 
      $aResumoProcedencia[$iTipoProcedencia]['nTotal']   = $aValoresCurtoPrazo['nTotal'];      
    }
    
  }
  
  $oPdf->SetX(20);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(100,$iAlt,''                          ,'T',0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHist ,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMulta,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJuros,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotal     ,'f'),'T',1,'R',0);
  
  $nTotalGeralHist  += $nTotalHist;
  $nTotalGeralMulta += $nTotalMulta;
  $nTotalGeralJuros += $nTotalJuros;
  $nTotalGeral      += $nTotal;
         
  $nTotalHist  = 0;
  $nTotalMulta = 0;
  $nTotalJuros = 0;
  $nTotal      = 0;  
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(50,$iAlt,'Resumo por Tipo de Procedência',0,1,'L',0);

  foreach ( $aResumoProcedencia as $iTipoProcedencia => $aValores ) {
     
    $oPdf->SetFont('Arial','',$iFonte);
    $oPdf->SetX(20);
    $oPdf->Cell(45,$iAlt,$aDescrTipoProced[$iTipoProcedencia]  ,0,0,'L',0);
    $oPdf->Cell(55,$iAlt,''                                    ,0,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nVlrHist'],'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nMulta']  ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nJuros']  ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nTotal']  ,'f'),0,1,'R',0);
    
    $nTotalHist  += $aValores['nVlrHist'];
    $nTotalMulta += $aValores['nMulta'];
    $nTotalJuros += $aValores['nJuros'];
    $nTotal      += $aValores['nTotal'];    
    
  }
  
  $oPdf->SetX(20);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(100,$iAlt,''                          ,'T',0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHist ,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMulta,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJuros,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotal     ,'f'),'T',1,'R',0);  
  
}

if ( $oGet->sQuebra == 's' ) {
  $oPdf->AddPage();	
}

$oPdf->Ln($iAlt*2);

$oPdf->SetFont('Arial','B',$iFonte+3);
$oPdf->Cell(100,$iAlt,'Dívida Ativa de Longo Prazo',0,1,'L',0);
$oPdf->SetFont('Arial','B',$iFonte);

foreach ( $aLongoPrazo as $iTipoDebito => $aDadosLongoPrazo ) {
	
	$nTotalHist  = 0;
	$nTotalMulta = 0;
	$nTotalJuros = 0;
	$nTotal      = 0;	
	$aResumoProcedencia = array();
	
	$oPdf->SetFont('Arial','B',$iFonte);
	$oPdf->Cell(50,$iAlt,$iTipoDebito." - ".$aDescrTipo[$iTipoDebito],0,1,'L',0);

	$oPdf->SetX(20);
	$oPdf->Cell(45,$iAlt,'Tipo de Procedência'     ,0,0,'C',1);
	$oPdf->Cell(55,$iAlt,'Descrição da Procedência',0,0,'C',1);
	$oPdf->Cell(20,$iAlt,'Vlr Histórico'           ,0,0,'C',1);
	$oPdf->Cell(20,$iAlt,'Vlr Multa'               ,0,0,'C',1);
	$oPdf->Cell(20,$iAlt,'Vlr Juros'               ,0,0,'C',1); 
	$oPdf->Cell(20,$iAlt,'Total'                   ,0,1,'C',1);
	
	foreach ( $aDadosLongoPrazo as $iProcedencia => $aValoresLongoPrazo ) {
		
    $oPdf->SetFont('Arial','',$iFonte);
    
    $oPdf->SetX(20);
	  $oPdf->Cell(45,$iAlt,$aDescrTipoProced[$aValoresLongoPrazo['iTipoProced']]   ,0,0,'L',0);
	  $oPdf->Cell(55,$iAlt,$aDescrProced[$iProcedencia]                     ,0,0,'L',0);
	  $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nVlrHist']        ,'f'),0,0,'R',0);
	  $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nMulta']          ,'f'),0,0,'R',0);
	  $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nJuros']          ,'f'),0,0,'R',0);
	  $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nTotal']          ,'f'),0,1,'R',0);    

	  $nTotalHist  += $aValoresLongoPrazo['nVlrHist'];
	  $nTotalMulta += $aValoresLongoPrazo['nMulta'];
	  $nTotalJuros += $aValoresLongoPrazo['nJuros'];
	  $nTotal      += $aValoresLongoPrazo['nTotal'];
	  
	  $iTipoProcedencia = $aValoresLongoPrazo['iTipoProced'];
	  
	  if ( isset($aResumoProcedencia[$iTipoProcedencia]) ) {
		  $aResumoProcedencia[$iTipoProcedencia]['nVlrHist'] += $aValoresLongoPrazo['nVlrHist']; 
		  $aResumoProcedencia[$iTipoProcedencia]['nMulta']   += $aValoresLongoPrazo['nMulta']; 
		  $aResumoProcedencia[$iTipoProcedencia]['nJuros']   += $aValoresLongoPrazo['nJuros']; 
		  $aResumoProcedencia[$iTipoProcedencia]['nTotal']   += $aValoresLongoPrazo['nTotal']; 
	  } else {
      $aResumoProcedencia[$iTipoProcedencia]['nVlrHist'] = $aValoresLongoPrazo['nVlrHist']; 
      $aResumoProcedencia[$iTipoProcedencia]['nMulta']   = $aValoresLongoPrazo['nMulta']; 
      $aResumoProcedencia[$iTipoProcedencia]['nJuros']   = $aValoresLongoPrazo['nJuros']; 
      $aResumoProcedencia[$iTipoProcedencia]['nTotal']   = $aValoresLongoPrazo['nTotal']; 	  	
	  }
		
	}
	
  $oPdf->SetX(20);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(100,$iAlt,''                          ,'T',0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHist ,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMulta,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJuros,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotal     ,'f'),'T',1,'R',0);
  
  $nTotalGeralHist  += $nTotalHist;
  $nTotalGeralMulta += $nTotalMulta;
  $nTotalGeralJuros += $nTotalJuros;
  $nTotalGeral      += $nTotal;     
  
  $nTotalHist  = 0;
  $nTotalMulta = 0;
  $nTotalJuros = 0;
  $nTotal      = 0;  
  
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(50,$iAlt,'Resumo por Tipo de Procedência',0,1,'L',0);

  foreach ( $aResumoProcedencia as $iTipoProcedencia => $aValores ) {
  	 
  	$oPdf->SetFont('Arial','',$iFonte);
    $oPdf->SetX(20);
    $oPdf->Cell(45,$iAlt,$aDescrTipoProced[$iTipoProcedencia]  ,0,0,'L',0);
    $oPdf->Cell(55,$iAlt,''                                    ,0,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nVlrHist'],'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nMulta']  ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nJuros']  ,'f'),0,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValores['nTotal']  ,'f'),0,1,'R',0);
    
    $nTotalHist  += $aValores['nVlrHist'];
    $nTotalMulta += $aValores['nMulta'];
    $nTotalJuros += $aValores['nJuros'];
    $nTotal      += $aValores['nTotal'];    
  	
  }
  
  $oPdf->SetX(20);
  $oPdf->SetFont('Arial','B',$iFonte);
  $oPdf->Cell(100,$iAlt,''                          ,'T',0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHist ,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMulta,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJuros,'f'),'T',0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotal     ,'f'),'T',1,'R',0);  
  
}

$oPdf->Ln();
$oPdf->SetFont('Arial','B',$iFonte+1);
$oPdf->Cell(110,$iAlt,'Total Geral de Dívida'          ,0,0,'L',0);
$oPdf->Cell(20,$iAlt,db_formatar($nTotalGeralHist ,'f'),0,0,'R',0);
$oPdf->Cell(20,$iAlt,db_formatar($nTotalGeralMulta,'f'),0,0,'R',0);
$oPdf->Cell(20,$iAlt,db_formatar($nTotalGeralJuros,'f'),0,0,'R',0);
$oPdf->Cell(20,$iAlt,db_formatar($nTotalGeral     ,'f'),0,1,'R',0);




$oPdf->Ln(6);
    
$iAlt = 3;
$oPdf->SetFont('Arial','B',($iFonte-1));
$oPdf->Cell(160,3,"RESUMO POR CURTO E LONGO PRAZO"  ,1,1,"C",0);

foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {

  $nTotalHistCurto  = 0;
  $nTotalCorrCurto  = 0;
  $nTotalMultaCurto = 0;
  $nTotalJurosCurto = 0;
  $nTotalCurto      = 0; 
  
  if ( $sTipoAgrupa == "proced" ) {
    $sTituloAgrupa = "Procedência";
  } else if ( $sTipoAgrupa == "receita" ) {
    $sTituloAgrupa = "Receita";
  } else if ( $sTipoAgrupa == "tipo_proced" ) {
    $sTituloAgrupa = "Tipo de Procedência";
  } else {
    $sTituloAgrupa = "Tipo de Débito";
  }
  
  $aTotalGeral = array();
  
  if ( isset($aResumoCurtoPrazo[$sTipoAgrupa]) ) {

    if ( $oPdf->gety() > $oPdf->h - 30  ){
      $oPdf->addpage();
    }
    
    $oPdf->SetFont('Arial','B',($iFonte-1));
    $oPdf->Cell(160,$iAlt,"Resumo por {$sTituloAgrupa} CURTO PRAZO",1,1,'L',1);
    $oPdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
    $oPdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Histórico' ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Multa'     ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Juros'     ,1,0,'C',1); 
    $oPdf->Cell(20,$iAlt,'Total'         ,1,1,'C',1);  
    
    foreach ( $aResumoCurtoPrazo[$sTipoAgrupa] as $sValorTipo => $aValoresCurtoPrazo ) {
   
      $oPdf->SetFont('Arial','',(($iFonte-1)-1));
  
      $oPdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
      $oPdf->Cell(50,$iAlt,$aValoresCurtoPrazo['sDescricao']                       ,1,0,'L',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nVlrHist']        ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nVlrCorr']        ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nMulta']          ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nJuros']          ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresCurtoPrazo['nTotal']          ,'f'),1,1,'R',0);    
    
      $nTotalHistCurto  += $aValoresCurtoPrazo['nVlrHist'];
      $nTotalCorrCurto  += $aValoresCurtoPrazo['nVlrCorr'];
      $nTotalMultaCurto += $aValoresCurtoPrazo['nMulta'];
      $nTotalJurosCurto += $aValoresCurtoPrazo['nJuros'];
      $nTotalCurto      += $aValoresCurtoPrazo['nTotal'];

      
      if ( isset($aTotalGeral[$sValorTipo]) ) {
        $aTotalGeral[$sValorTipo]['nVlrHist'] += $aValoresCurtoPrazo['nVlrHist'];
        $aTotalGeral[$sValorTipo]['nVlrCorr'] += $aValoresCurtoPrazo['nVlrCorr'];
        $aTotalGeral[$sValorTipo]['nMulta']   += $aValoresCurtoPrazo['nMulta'];
        $aTotalGeral[$sValorTipo]['nJuros']   += $aValoresCurtoPrazo['nJuros'];
        $aTotalGeral[$sValorTipo]['nTotal']   += $aValoresCurtoPrazo['nTotal'];
      } else {
      	$aTotalGeral[$sValorTipo]['sDescricao'] = $aValoresCurtoPrazo['sDescricao'];
        $aTotalGeral[$sValorTipo]['nVlrHist']   = $aValoresCurtoPrazo['nVlrHist'];
        $aTotalGeral[$sValorTipo]['nVlrCorr']   = $aValoresCurtoPrazo['nVlrCorr'];
        $aTotalGeral[$sValorTipo]['nMulta']     = $aValoresCurtoPrazo['nMulta'];
        $aTotalGeral[$sValorTipo]['nJuros']     = $aValoresCurtoPrazo['nJuros'];
        $aTotalGeral[$sValorTipo]['nTotal']     = $aValoresCurtoPrazo['nTotal'];        
      }
      
    } 
  
    $oPdf->SetFont('Arial','B',($iFonte-1));
    $oPdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
    $oPdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalHistCurto ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalCorrCurto,'f'), 1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalMultaCurto,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalJurosCurto,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalCurto     ,'f'),1,1,'R',0);
    
    $oPdf->Ln(6);
  
  }
  
  $nTotalHistLongo  = 0;
  $nTotalCorrLongo  = 0;
  $nTotalMultaLongo = 0;
  $nTotalJurosLongo = 0;
  $nTotalLongo      = 0;

  if ( isset($aResumoLongoPrazo[$sTipoAgrupa]) ) {
    
    $oPdf->SetFont('Arial','B',($iFonte-1));
    $oPdf->Cell(160,$iAlt,"Resumo por {$sTituloAgrupa} LONGO PRAZO",1,1,'L',1);
    $oPdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
    $oPdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Histórico' ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Multa'     ,1,0,'C',1);
    $oPdf->Cell(20,$iAlt,'Vlr Juros'     ,1,0,'C',1); 
    $oPdf->Cell(20,$iAlt,'Total'         ,1,1,'C',1);  
    
    foreach ( $aResumoLongoPrazo[$sTipoAgrupa] as $sValorTipo => $aValoresLongoPrazo ) {
   
      $oPdf->SetFont('Arial','',($iFonte-1));
  
      $oPdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
      $oPdf->Cell(50,$iAlt,$aValoresLongoPrazo['sDescricao']                       ,1,0,'L',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nVlrHist']        ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nVlrCorr']        ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nMulta']          ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nJuros']          ,'f'),1,0,'R',0);
      $oPdf->Cell(20,$iAlt,db_formatar($aValoresLongoPrazo['nTotal']          ,'f'),1,1,'R',0);    
    
      $nTotalHistLongo  += $aValoresLongoPrazo['nVlrHist'];
      $nTotalCorrLongo  += $aValoresLongoPrazo['nVlrCorr'];
      $nTotalMultaLongo += $aValoresLongoPrazo['nMulta'];
      $nTotalJurosLongo += $aValoresLongoPrazo['nJuros'];
      $nTotalLongo      += $aValoresLongoPrazo['nTotal'];

      if ( isset($aTotalGeral[$sValorTipo]) ) {
        $aTotalGeral[$sValorTipo]['nVlrHist']   += $aValoresLongoPrazo['nVlrHist'];
        $aTotalGeral[$sValorTipo]['nVlrCorr']   += $aValoresLongoPrazo['nVlrCorr'];
        $aTotalGeral[$sValorTipo]['nMulta']     += $aValoresLongoPrazo['nMulta'];
        $aTotalGeral[$sValorTipo]['nJuros']     += $aValoresLongoPrazo['nJuros'];
        $aTotalGeral[$sValorTipo]['nTotal']     += $aValoresLongoPrazo['nTotal'];
      } else {
      	$aTotalGeral[$sValorTipo]['sDescricao'] = $aValoresLongoPrazo['sDescricao'];
        $aTotalGeral[$sValorTipo]['nVlrHist']   = $aValoresLongoPrazo['nVlrHist'];
        $aTotalGeral[$sValorTipo]['nVlrCorr']   = $aValoresLongoPrazo['nVlrCorr'];
        $aTotalGeral[$sValorTipo]['nMulta']     = $aValoresLongoPrazo['nMulta'];
        $aTotalGeral[$sValorTipo]['nJuros']     = $aValoresLongoPrazo['nJuros'];
        $aTotalGeral[$sValorTipo]['nTotal']     = $aValoresLongoPrazo['nTotal'];        
      }     
      
    } 
  
    $oPdf->SetFont('Arial','B',($iFonte-1));
    $oPdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
    $oPdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalHistLongo ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalCorrLongo,'f'), 1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalMultaLongo,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalJurosLongo,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($nTotalLongo     ,'f'),1,1,'R',0);
    
    $oPdf->Ln(6);
  
  }

  $nTotalHistGeral  = 0;
  $nTotalCorrGeral  = 0;
  $nTotalMultaGeral = 0;
  $nTotalJurosGeral = 0;
  $nTotalGeral      = 0;  
  
  $oPdf->SetFont('Arial','B',($iFonte-1));
  $oPdf->Cell(160,$iAlt,"Resumo por {$sTituloAgrupa} CURTO E LONGO PRAZO",1,1,'L',1);
  $oPdf->Cell(10,$iAlt,'Código'        ,1,0,'C',1);
  $oPdf->Cell(50,$iAlt,'Descrição'     ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Histórico' ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Corrigido' ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Multa'     ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Juros'     ,1,0,'C',1); 
  $oPdf->Cell(20,$iAlt,'Total'         ,1,1,'C',1);  

  foreach ( $aTotalGeral as $sValorTipo => $aValoresTotalGeral ) {
   
    $oPdf->SetFont('Arial','',($iFonte-1));
      
    $oPdf->Cell(10,$iAlt,$sValorTipo                                             ,1,0,'C',0);
    $oPdf->Cell(50,$iAlt,$aValoresTotalGeral['sDescricao']                       ,1,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresTotalGeral['nVlrHist']        ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresTotalGeral['nVlrCorr']        ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresTotalGeral['nMulta']          ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresTotalGeral['nJuros']          ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresTotalGeral['nTotal']          ,'f'),1,1,'R',0);    
  
    $nTotalHistGeral  += $aValoresTotalGeral['nVlrHist'];
    $nTotalCorrGeral  += $aValoresTotalGeral['nVlrCorr'];
    $nTotalMultaGeral += $aValoresTotalGeral['nMulta'];
    $nTotalJurosGeral += $aValoresTotalGeral['nJuros'];
    $nTotalGeral      += $aValoresTotalGeral['nTotal'];
    
  }

  
  $oPdf->SetFont('Arial','B',($iFonte-1));
  $oPdf->Cell(10,$iAlt,'Total:'                          ,1,0,'R',0);
  $oPdf->Cell(50,$iAlt,''                                ,1,0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHistGeral ,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalCorrGeral,'f'), 1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMultaGeral,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJurosGeral,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalGeral     ,'f'),1,1,'R',0);
    
  $oPdf->Ln(6);  

  
}


foreach ( $aAgrupador as $sTipoAgrupa => $sCampo ) {
  
  $nTotalHistResumo  = 0;
  $nTotalCorrResumo  = 0;
  $nTotalMultaResumo = 0;
  $nTotalJurosResumo = 0;
  $nTotalResumo      = 0;  
  
  if ( $sTipoAgrupa == "proced" ) {
    $sTituloAgrupa = "Procedência";
  } else if ( $sTipoAgrupa == "receita" ) {
    $sTituloAgrupa = "Receita";
  } else if ( $sTipoAgrupa == "tipo_proced" ) {
    $sTituloAgrupa = "Tipo de Procedência";
  } else {
    $sTituloAgrupa = "Tipo de Débito";
  }  
  
  $oPdf->SetFont('Arial','B',($iFonte-1));
  $oPdf->Cell(160,$iAlt,"Resumo por {$sTituloAgrupa}",1,1,'L',1);
  $oPdf->Cell(10,$iAlt,'Código'                      ,1,0,'C',1);
  $oPdf->Cell(50,$iAlt,'Descrição'                   ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Histórico'               ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Corrigido'               ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Multa'                   ,1,0,'C',1);
  $oPdf->Cell(20,$iAlt,'Vlr Juros'                   ,1,0,'C',1); 
  $oPdf->Cell(20,$iAlt,'Total'                       ,1,1,'C',1);  
  
  foreach ( $aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo ) {

    $oPdf->SetFont('Arial','',($iFonte-1));
    $oPdf->Cell(10,$iAlt,$iCodResumo                                 ,1,0,'C',0);
    $oPdf->Cell(50,$iAlt,$aValoresResumo['sDescricao']               ,1,0,'L',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nVlrHist'],'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nVlrCorr'],'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nMulta']  ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nJuros']  ,'f'),1,0,'R',0);
    $oPdf->Cell(20,$iAlt,db_formatar($aValoresResumo['nTotal']  ,'f'),1,1,'R',0);    
  
    $nTotalHistResumo  += $aValoresResumo['nVlrHist'];
    $nTotalCorrResumo  += $aValoresResumo['nVlrCorr'];
    $nTotalMultaResumo += $aValoresResumo['nMulta'];
    $nTotalJurosResumo += $aValoresResumo['nJuros'];
    $nTotalResumo      += $aValoresResumo['nTotal'];    
  
  }
  
  $oPdf->SetFont('Arial','B',($iFonte-1));
  $oPdf->Cell(10,$iAlt,'Total:'                           ,1,0,'R',0);
  $oPdf->Cell(50,$iAlt,''                                 ,1,0,'L',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalHistResumo ,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalCorrResumo ,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalMultaResumo,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalJurosResumo,'f'),1,0,'R',0);
  $oPdf->Cell(20,$iAlt,db_formatar($nTotalResumo     ,'f'),1,1,'R',0);
    
  $oPdf->Ln(6);    
  
}

$oPdf->Output();

?>