<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


/**
 * Classe para controle de datas
 * @package std
 * @author Andrio Costa
 * @version $Revision: 1.3 $
 * @revision $Author: dbanderson $
 */
class DBDate {
	
	/**
	 * Data no formato timestamp
	 * @var integer
	 */
	private $iTimeStamp;
	
	const DATA_PTBR = "d/m/Y";
	const DATA_EN   = "Y-m-d";
	
	/**
	 * Construtor da Classe
	 * @param  $sData - Data no formato Y-m-d ou d/m/Y
	 * @throws ParameterException @see DBDate::validaData()
	 */
	public function __construct($sData) {
		
		$this->iTimeStamp = $this->validaData($sData);
	}
	
	/**
	 * Retorna a data no formato Y-m-d
	 * @return string
	 */
	public function getDate($sMascaraData = DBDate::DATA_EN) {
		
		return date($sMascaraData, $this->iTimeStamp);
	}

	/**
	 * Recebe um formato para conversão de data.
	 * Formatos aceitos: Y-m-d
	 *                   d/m/Y
	 * @param string $sFormat
	 * @throws ParameterException @see DBDate::validaData()
	 */
	public function convertTo($sFormat) {
		
		if (($sFormat != DBDate::DATA_EN) && ($sFormat != DBDate::DATA_PTBR)) {
			
			$sMsgErro  = "Formato de data inválida.\n";
			$sMsgErro .= "Formatos aceito: \"Y-m-d\" ou \"d/m/Y\"";
			throw new ParameterException($sMsgErro);
		}
		return date($sFormat, $this->iTimeStamp);
	}
	
	/**
	 * Verifica se uma data eh valida
	 * @param  string $sData
	 * @throws ParameterException - Quando Formato da Data for Invalido ou Inexistente
	 */
	protected function validaData($sData) {
		
		if (strpos($sData, "/")) {
			list($dia, $mes, $ano) = explode("/", $sData);
		} else if (strpos($sData, "-")) {
			list($ano, $mes, $dia) = explode("-", $sData);
		} else {
				
			$sMsgErro  = "Data com formato inválido. \n";
			$sMsgErro .= "Formatos aceito: \"Y-m-d\" ou \"d/m/Y\"";
			throw new ParameterException($sMsgErro);
		}
		
		if (!checkdate($mes, $dia, $ano)) {
				
			$sMsgErro .= "Data inesistente. Favor verificar";
			throw new ParameterException($sMsgErro);
		}
		$sDataValidada    = "{$ano}-{$mes}-{$dia}";
		return db_strtotime($sDataValidada);
	}
  
	/**
   * REtorna TimeStampo do Objeto
   * @return integer
   */
  public function getTimeStamp() {
    return $this->iTimeStamp;
  }
  /**
   * Retorna o Dia da Data da Instancia
   * @return integer
   */
  public function getDia() {
    return date("d", $this->iTimeStamp);
  }
  
  /**
   * Retorna a Mes da data da Instancia
   * @return integer
   */
  public function getMes() {
    return date("m", $this->iTimeStamp);
  }
  
  /**
   * Retorna o Ano da Data da Instancia
   * @return integer
   */
  public function getAno() {
    return date("Y", $this->iTimeStamp);
  }
}