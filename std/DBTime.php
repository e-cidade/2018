<?php
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


abstract class DBTime {
	
  /**
   * Verifica as datas informadas se sobrepõe. Aconselhado utilizar sempre db_strtotime()
   * @param  integer $iDtIni1
   * @param  integer $iDtFim1
   * @param  integer $iDtIni2
   * @param  integer $iDtFim2
   * @return boolean TRUE em caso de ERRO
   */
	static function db_DataOverlaps($iDtIni1, $iDtFim1, $iDtIni2, $iDtFim2) {

		if ( ($iDtIni1 >= $iDtIni2 && $iDtIni1 < $iDtFim2) || ($iDtFim1 > $iDtIni2 && $iDtFim1 < $iDtFim2) ) {
			return true;
		} else {
			return false;
		}
	}	
	
	static function verifIntervalo ($iDtIni, $iDtFim, $qtd) {
		
		$Y = date("Y", $iDtFim);
    $m = date("m", $iDtFim); 
    $d = date("d", $iDtFim);
    $H = date("H", $iDtFim);
    $i = date("i", $iDtFim);
    
    $dif = ((($iDtFim - $iDtIni)/60)/60);
		
    for ($j = $iDtIni; $j <= $iDtFim; $j=$j+3600) {
			
			$h = date("H", $j); 
			
			if ($h == 12 && ($dif == $qtd)) {
				
				return mktime($H+1, $i, 0, $m, $d, $Y);
			}
		}
		
		return $iDtFim;
	}
	
	static function verifData ($iData) {
		
		return DBTime::verifDiaValido(DBTime::verifHoraValida(DBTime::verifMeioDia($iData)));
	}
	
	static function verifMeioDia ($iData) {
		
		$Y = date("Y",$iData);
		$m = date("m",$iData); 
		$d = date("d",$iData);
		$H = date("H",$iData);
		$i = date("i",$iData);
		
		if ($H == "12") {
			
			$H++;
			
			return mktime($H, $i, 0, $m, $d, $Y);			
		} else {
			return $iData;
		}
	}
	
	static function verifHoraValida ($iData) {
		
		$Y = date("Y",$iData);
    $m = date("m",$iData); 
    $d = date("d",$iData);
    $H = date("H",$iData);
    $i = date("i",$iData);
    $s = 0;
    
    if ($H >= 18) {
      
    	$iDtDif = mktime(18, 0, 0, $m, $d, $Y);
    	$iDif   = ($iData - $iDtDif);
    	
    	$H   = 8;
    	$i   = 30;
    	$s  += $iDif; 
      $d++;
      
      return mktime($H, $i, $s, $m, $d, $Y);
    } else {
    	return $iData;
    }    
	}
	
	static function verifDiaValido ($iData) {
		
		$Y = date("Y",$iData);
    $m = date("m",$iData); 
    $d = date("d",$iData);
    $H = date("H",$iData);
    $i = date("i",$iData);
    
    $sSql    = cl_calend::sql_query(null, "*", "", "k13_data = '{$Y}-{$m}-{$d}'");
    $rsDia   = db_query($sSql);
    
    if (pg_num_rows($rsDia) > 0) {
    	
    	$dia = date('D', $iData);
    	
    	switch ($dia) {
    		
    		case "Sun":
    			$d++;
    			break;
    			    			
    		case "Sat":
    			$d += 2;
    			break;
    			    			
    		case "Fri":
    			$d += 3;    			
    			
    	}
    	
    	return DBTime::verifDiaValido(mktime($H, $i, 0, $m, $d, $Y));
    	
    } else {
    	
    	return $iData;
    }
	}	
}

?>