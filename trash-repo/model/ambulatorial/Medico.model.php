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

define("URL_MENSAGEM_MEDICO", "saude.ambulatorial.Medico.");

/**
 * M�dico
 * 
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package ambulatorial
 * @version $Revision: 1.1 $
 */
class Medico {

  
  private $iCodigo;
  private $sNome;
  private $sCRM;
  private $lRede;
  
  public function __construct($iCodigo = null) {
  	
    if (!empty($iCodigo)) {
    	
      $oDaoMedico = new cl_medicos();
      $sSqlMedico = $oDaoMedico->sql_query_medicos(null, " codigo_medico = {$iCodigo} ");
      $rsMedico   = $oDaoMedico->sql_record($sSqlMedico);
      
      if ($oDaoMedico->numrows == 0) {
        throw new BusinessException(_M(URL_MENSAGEM_MEDICO."medico_nao_encontrado"));
      }
      $oDados = db_utils::fieldsMemory($rsMedico, 0);
      
      $this->iCodigo = $oDados->codigo_medico;
      $this->sNome   = $oDados->nome;
      $this->sCRM    = $oDados->crm;
      $this->lRede   = $oDados->medico_rede == 't';
    }  
    
  }
 
  
  /**
   * Retorna codigo
   * @return codigo
   */
  public function getCodigo () {
    return $this->$iCodigo; 
  }
  
  /**
   * Define Nome do m�dico
   * @param string $sNome
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna Nome do m�dico
   * @return string $sNome
   */
  public function getNome () {
    return $this->sNome; 
  }

  /**
   * Define crm do m�dico
   * @param string $sCRM
   */
  public function setCRM ($sCRM) {
    $this->sCRM = $sCRM;
  }
  
  /**
   * Retorna crm do m�dico
   * @return string $sCRM
   */
  public function getCRM () {
    return $this->sCRM; 
  }

  /**
   * Define se o m�dico � da rede   
   * @param boolean $lRede
   */
  public function setRede ($lRede) {
    $this->lRede = $lRede;
  }
  
  /**
   * Verifica se o m�dico � da rede  
   * @return boolean $lRede
   */
  public function isMedicoRede() {
    return $this->lRede; 
  }
  
}