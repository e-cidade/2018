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