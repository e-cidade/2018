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
 * Procedimentos de avaliacao para uma Etapa(Serie) da Turma
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class EtapaTurma {

  /**
   * Etapa (Serie)
   * @var Etapa
   */
  private $oEtapa;
  
  /**
   * Procedimento de Avalicao
   * @var ProcedimentoAvaliacao
   */
  private $oProcedimentoAvaliacao;
  
  /**
   * Valida se a etapa da turma tem aprovação automática
   * @var boolean
   */
  private $lAprovacaoAutomatica = false;
  
  public function __construct(Etapa $oEtapa, ProcedimentoAvaliacao $oProcedimentoAvaliacao) {
    
    $this->oEtapa                 = $oEtapa;
    $this->oProcedimentoAvaliacao = $oProcedimentoAvaliacao;
  }
  
  /**
   * Retorna uma instancia de Etapa
   * @return Etapa
   */
  public function getEtapa() { 
    return $this->oEtapa; 
  }

  /**
   * Retorna uma instancia de ProcedimentoAvaliacao
   * @return ProcedimentoAvaliacao
   */
  public function getProcedimentoAvaliacao() {
    return $this->oProcedimentoAvaliacao;
  }
  
  /**
   * Seta se a etapa da turma tem aprovação automática
   * @param boolean $lAprovacaoAutomatica
   */
  public function setAprovacaoAutomatica( $lAprovacaoAutomatica ) {
    $this->lAprovacaoAutomatica = $lAprovacaoAutomatica;
  }
  
  /**
   * Retorna se a etapa da turma tem aprovação automática
   * @return boolean
   */
  public function temAprovacaoAutomatica() {
    return $this->lAprovacaoAutomatica;
  }
}