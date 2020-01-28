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


/**
 * Interface para executar os lanзamentos auxiliares de um Evento Contabil
 * @author Andrio Costa / Matheus Felini
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.1 $ 
 */
interface ILancamentoAuxiliar { 
  
  /**
   * Executa Lanзamentos Auxiliares para cada evento
   * @param integer $iCodigoLancamento
   * @param date $dtLancamento
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento);
  
  /**
   * Seta o valor total do evento
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal);
  
  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal();
  
  /**
   * Retorna o histуrico da operaзгo
   */
  public function getHistorico();
  
  /**
   * Seta o histуrico da operaзгo
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico);
  
  /**
   * Retorna a observaзгo do histуrico da operaзгo
   */
  public function getObservacaoHistorico();
  
  /**
   * Seta a observaзгo do histуrico da operaзгo
   * @param string $sObservacaoHistorico
   */
  public function setObservacaoHistorico($sObservacaoHistorico);
  
}