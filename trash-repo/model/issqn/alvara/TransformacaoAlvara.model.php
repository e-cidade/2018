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

require_once 'model/issqn/alvara/MovimentacaoAlvara.model.php';

/**
 * Transformacao de alvara
 * 
 * @uses MovimentacaoAlvara
 * @package ISSQN
 * @subpakage ALVARA
 * @author Rafael Nery      <rafael.nery@dbseller.com.br> 
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class TransformacaoAlvara extends MovimentacaoAlvara {

  private $iTipoTranformacao ;
  /**
   * Processar
   * - incluir movimentacao de alvara do tipo transformacao
   *
   * @access public
   * @return void
   */
  public function processar() {

    parent::salvar();

    $oDaoIssAlvara = db_utils::getDao("issalvara");
    //@TODO Implementar a persistencia dos dados no Alvara.model
    $oDaoIssAlvara->q123_isstipoalvara = $this->iTipoTransformacao;
    $oDaoIssAlvara->q123_sequencial    = $this->getAlvara()->getCodigo();      
    $oDaoIssAlvara->alterar($this->getAlvara()->getCodigo());

    if ($oDaoIssAlvara->erro_status == "0") {
      throw new Exception($oDaoIssAlvara->erro_msg);
    }
    return true;
  }
   
  /**
   * Define o Tipo de Alvara a ser transformado.
   *
   * @param  integer $iTipoTranformacao
   * @access public
   * @return void
   */
  function setTipoTransformacao( $iTipoTranformacao ) {

    $this->iTipoTransformacao = $iTipoTranformacao;
    return;
  }

}