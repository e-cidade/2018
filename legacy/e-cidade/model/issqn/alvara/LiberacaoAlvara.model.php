<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("model/issqn/alvara/MovimentacaoAlvara.model.php"));

/**
 * Transformacao de alvara do tipo liberação
 * 
 * @uses MovimentacaoAlvara
 * @package ISSQN 
 * @package ALVARA
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class LiberacaoAlvara extends MovimentacaoAlvara { 
  
  /**
   * Processar
   * - Inclui movimentacao e atualiza tipo do alvara
   *
   * @access public
   * @return void
   */
  public function processar() {

    parent::salvar();
    $this->atualizaTipoAlvara();
  }
  
  /**
   * atualiza tipo do alvara
   *
   * @access public
   * @return void
   */
  public function atualizaTipoAlvara() {

    $oIssAlvara = db_utils::getDao('issalvara');
    $oIssAlvara->q123_sequencial    = $this->getAlvara()->getCodigo();
    $oIssAlvara->q123_isstipoalvara = $this->getAlvara()->getTipoAlvara();
    $oIssAlvara->q123_situacao      = 1;
    $oIssAlvara->alterar($this->getAlvara()->getCodigo());

    if ($oIssAlvara->erro_status == "0") {
      throw new Exception($oIssAlvara->erro_msg);
    }

  }
  
}