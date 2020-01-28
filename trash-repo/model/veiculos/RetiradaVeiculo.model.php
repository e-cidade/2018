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


class RetiradaVeiculo {

  protected $iCodigoRetirada;

  protected $nMedidaRetirada;
  
  protected $dtRetirada;
  
  protected $tHoraRetirada;
  
  protected $sDestino;
  
  protected $iDepartamento;
  
  protected $iUsuario;
  
  protected $dtDataInclusao;
  
  protected $tHoraInclusao;
  
  protected $iMotorista;
  
  public function __construct($iRetirada) {
    
    if (!empty($iRetirada)) {

      
    }
  }
  /**
   * @return data da Inclusão da Retirada
   */
  public function getDataInclusao() {

    return $this->dtDataInclusao;
  }
  
  
  /**
   * @return string com a data que foi realizada a Retirada
   */
  public function getDataRetirada() {
    return $this->dtRetirada;
  }
  
  /**
   * define a data que foi realizada a Retirada
   * @param string $dtRetirada
   */
  public function setDtRetirada($dtRetirada) {
    $this->dtRetirada = $dtRetirada;
  }
  
  /**
   * @return Retorna o codigo da Retirada
   */
  public function getCodigoRetirada() {
    return $this->iCodigoRetirada;
  }
  
  /**
   * Departamento da Retirada
   * @return unknown
   */
  public function getDepartamento() {

    return $this->iDepartamento;
  }
  
  /**
   * Retorna o Codigo do motorista da Retirada
   * @return Codigo do Motorista
   */
  public function getMotorista() {

    return $this->iMotorista;
  }
  
  /**
   * @param unknown_type $iMotorista
   */
  public function setMotorista($iMotorista) {

    $this->iMotorista = $iMotorista;
  }
  
  /**
   * Retorna com qual quilometragem/horas de uso o veiculo estava na Hora da retirada
   * @return Medida da retirada
   */
  public function getMedidaRetirada() {
    return $this->nMedidaRetirada;
  }
  
  /**
   * @param unknown_type $nMedidaRetirada
   */
  public function setMedidaRetirada($nMedidaRetirada) {

    $this->nMedidaRetirada = $nMedidaRetirada;
  }
  
  /**
   * @return unknown
   */
  public function getDestino() {

    return $this->sDestino;
  }
  
  /**
   * @param unknown_type $sDestino
   */
  public function setDestino($sDestino) {

    $this->sDestino = $sDestino;
  }
  
  /**
   * @return unknown
   */
  public function getHoraRetirada() {

    return $this->tHoraRetirada;
  }
  
  /**
   * @param unknown_type $tHoraRetirada
   */
  public function setHoraRetirada($tHoraRetirada) {

    $this->tHoraRetirada = $tHoraRetirada;
  }

}

?>