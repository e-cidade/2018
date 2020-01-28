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
 * Referencias para Atributos de Exames Fixos
 * Class AtributoValorReferenciaFixo
 */
class AtributoValorReferenciaAlfaNumerico {

  private $iTamanho = 0;

  private $iCodigo = null;

  protected $aItensSelecionaveis = array();

  public function __construct($iCodigo = '') {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return null
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iTamanho
   */
  public function setTamanho($iTamanho) {
    $this->iTamanho = $iTamanho;
  }

  /**
   * @return int
   */
  public function getTamanho() {
    return $this->iTamanho;
  }


  /**
   * @return AtributoValorReferenciaSelecionavel[]
   */
  public function getReferenciasSelecionaveis() {

    if (count($this->aItensSelecionaveis) == 0) {

      $oDaoReferenciaSelecionavel = new cl_lab_valorrefselgrupo();
      $sSqlReferencia             = $oDaoReferenciaSelecionavel->sql_query(
                                                                           null, "*",
                                                                           null, "la51_i_referencia={$this->getCodigo()}"
                                                                          );
      $rsReferencia = $oDaoReferenciaSelecionavel->sql_record($sSqlReferencia);
      $aItens       = db_utils::getCollectionByRecord($rsReferencia);
      foreach ($aItens as $oItem) {
        $this->aItensSelecionaveis[] = new AtributoValorReferenciaSelecionavel($oItem->la51_i_valorrefsel,
                                                                               $oItem->la28_c_descr
                                                                              );
      }
    }
    return $this->aItensSelecionaveis;
  }

}