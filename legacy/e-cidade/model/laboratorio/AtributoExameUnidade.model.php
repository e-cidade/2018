<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Unidade de Medidas dos Atributos dos exames
 */
class AtributoExameUnidade {

  /**
   * Codigo da unidade
   * @var integer
   */
  protected  $iCodigo;

  /**
   * nome da unidade
   * @var string
   */
  protected $sNome;

  /**
   * Instancia a unidade de medida
   *
   * @param integer $iCodigo Codigo d aunidade de medida
   * @throws BusinessException
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoUnidadeMedida = new cl_lab_undmedida();
      $oDadosUnidade     = db_utils::getRowFromDao($oDaoUnidadeMedida, array($iCodigo));
      if (empty($oDadosUnidade)) {
        throw new BusinessException ("Unidade de medida nao cadastrada no sistema");
      }

      $this->setCodigo($oDadosUnidade->la13_i_codigo);
      $this->setNome(trim($oDadosUnidade->la13_c_descr));
    }
  }

  /**
   * Define o codigo do Atributo
   * @param int $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o codigo da unidade
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o nome da unidade
   * @param string $sNome
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Define o nome da unidade de medida
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

}