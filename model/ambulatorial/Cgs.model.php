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

/**
 * Classe para controle geral dos pacientes da
 * area DB:Saude
 * @package Ambulatorial
 * @version $Revision: 1.2 $
 */
class Cgs {
  
  /**
   * Codigo do cgs
   * @var integer
   */  
  protected $iCodigo;
  
  /**
   * Nome do CGS
   * @var string
   */
  protected $sNome;
  
  /**
   * Instancia um novo CGs
   */
  function __construct($iCgs = null) {

    if (!empty($iCgs)) {
      
      $oDaoCgs      = db_utils::getDao("cgs_und");
      $sSqlDadosCGS = $oDaoCgs->sql_query_file($iCgs);
      $rsDadosCGS   = $oDaoCgs->sql_record($sSqlDadosCGS);
      if ($oDaoCgs->numrows > 0) {

        $oDadosCGS = db_utils::fieldsMemory($rsDadosCGS, 0);
        $this->setCodigo($iCgs);
        $this->setNome($oDadosCGS->z01_v_nome);
      }
    }  
  }
  /**
   * Retorna o codigo de cadastro do paciente
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Código do paciente
   * @param integer $iCodigo
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna o nome do paciente
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  
  /**
   * seta o nome do paciente
   * @param string $sNome define o nome do paciente
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
}
?>