<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Interface para calculo de retencoes
 */
interface iCalculoRetencao {

  /**
   * Seta o valor da Deducao
   * @param $nValorDeducao
   */ 
  function setDeducao($nValorDeducao);
  
  /**
   * Realiza  calculo da retencao
   *
   */
  function calcularRetencao();
  
  /**
   * Calcula a base de calculo
   *
   */
  function calculaBasedeCalculo();
  
  /**
   * Define o valor da aliquota a ser retido.
   *
   * @param float $nValorAliquota
   */
  function setAliquota($nValorAliquota);
  
  /**
   * Retorna o valor da Aliquota
   */
  function getAliquota();
  
  /**
   * Retorna o valor da base de calculo
   */
  function getValorBaseCalculo();
  
  /**
   * Seta o valor da base de calculo
   *
   * @param float $nValorBaseCalculo valor da base de calculo
   */
   
  function setBaseCalculo($nValorBaseCalculo);
  
  /**
   * Define o valor da nota
   *
   * @param float $nValorNota valor da nota a ser contabilizado na retencao.
   */
  function setValorNota($nValorNota);
  
  /**
   * Define a data base para calculo das retencoes;
   *
   * @param string $dtDataBase data base para caculo formato dd/mm/YYY
   */
  function setDataBase($dtDataBase);
  
  /**
   * Define  o Codigo dos Movimentos
   *
   * @param unknown_type $aCodigosMovimentos
   */
  function setCodigoMovimentos($aCodigosMovimentos);
  
}

?>