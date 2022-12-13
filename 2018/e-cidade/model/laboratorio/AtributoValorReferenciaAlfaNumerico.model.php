<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
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