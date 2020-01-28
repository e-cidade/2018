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


interface ICalculoMediaRubrica {
  
  
  /**
   * Método construtor do cálculo da média da rúbrica
   * @param Servidor $oServidor
   * @param Rubrica $oRubrica
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @param integer $iTipoMedia
   * @throws BusinessException
   */
  public function __construct ( Servidor $oServidor, Rubrica $oRubrica, DBDate $oDataInicial, DBDate $oDataFinal);
  
  /**
   * Retorna o valor da média calculado dependendo do tipo de média
   */
  public function calcular();
  
  /**
   * Retorna o valor calculado 
   */
  public function getValorCalculado();

  /**
   * Retorna a quantidade calculada 
   */
  public function getQuantidadeCalculada();
  
  /**
   * Retorna a Rubrica utilizada no Cálculo
   */
  public function getRubrica();
}