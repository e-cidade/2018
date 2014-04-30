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


class BemCedente {

  /**
   * Codigo do Cecedente
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Objeto do Cedente
   * @var CgmBase
   */
  private $oCedente;
  
  /**
   * 
   */
  function __construct($iCodigoCedente) {
    $this->iCodigo = $iCodigoCedente;
  }
  
  /**
   * Retorna io Codigo do Convenio
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * @return CgmBase
   */
  public function getCedente() {
  
    if (empty($this->oCedente)) {

      $oDaoBensCadCedente = db_utils::getDao("benscadcedente");
      $sSqlDadosCgm       = $oDaoBensCadCedente->sql_query_file($this->iCodigo);
      $rsDadosCedente     = $oDaoBensCadCedente->sql_record($sSqlDadosCgm);
      if ($oDaoBensCadCedente->numrows  == 1) {
        $this->oCedente = CgmFactory::getInstanceByCgm(db_utils::fieldsMemory($rsDadosCedente, 0)->t04_numcgm);
      }
    }
    return $this->oCedente;
  }
  
  /**
   * @param CgmBase $oCedente
   */
  public function setCedente(CgmBase $oCedente) {
    $this->oCedente = $oCedente;
  }

  
  
}

?>