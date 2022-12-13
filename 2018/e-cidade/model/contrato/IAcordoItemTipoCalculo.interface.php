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
 * Interface que ser� implementada nas classes de c�lculo de um acordo
 * @author  matheus.felini@dbseller.com.br
 * @package contrato
 * @version $Revision: 1.3 $
 */
interface IAcordoItemTipoCalculo {

  /**
   * Seta valor na propriedade Data Inicial
   * @param date $dtDataInicial
   */
  public function setDataInicial($dtDataInicial);

  /**
   * Seta valor na propriedade Data Final
   * @param date $dtDataFinal
   */
  public function setDataFinal($dtDataFinal);

  /**
   * Seta valor na propriedade Quantidade 
   * @param integer $iQuantidade
   */
  public function setQuantidade($iQuantidade);

  /**
   * Seta valor na propriedade Valor Total
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal);
  
  /**
   * Recebe um array com todos os periodos do item
   * @param array $aPeriodosItem
   */
  public function setPeriodosItem($aPeriodosItem);

  /**
   * Efetua o c�lculo
   * @param $o Object
   * 
   * O par�metro � necess�rio em determinados tipo de c�lculo.
   * Em cada classe que implementa essa interface � descrito o
   * caso de uso da assinatura desse m�todo.
   */
  public function calcular($iAcordo, $oParametro = null);
}
?>