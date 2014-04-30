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
 * Interface para as formulas de cсlculo de depreciaчуo
 * @author iuri@dbseller.com.br, matheus.fellini@dbseller.com.br
 * @package patrimonio
 * @subpackage depreciacao
 * @version $Revision: 1.2 $ 
 */
interface IFormulaCalculo {

  /**
   * define o ano de calculo da formula
   * @param integer $iAno ano que deverс ser calculo a depreciacao
   */
  public function setQuantidadeAnosCalculados($iAno);
  
  
  /**
   * Define o valor residual
   * @param float $nValorResidual valor residual do bem 
   */
  public function setValorResidual($nValorResidual = 0); 
  
  /**
   * Define o valor atual do bem 
   * @param float $nValorAtual
   */
  public function setValorAtual($nValorAtual = 0);
  
  /**
   * Define o valor de Aquisiчуo do bem no calculo
   * @param float $nValorAquisicao valor da aquisiчуo
   */
  public function setValorAquisicao($nValorAquisicao);
  
  /**
   * Define o valor da depreciaчуo utilizado quando o calculo for manual;
   * @param float $nValorDepreciacao
   */
  public function setPercentualDepreciacao($nPercentualDepreciacao = 0);
  
  /**
   * Vida util da formula
   * @param integer $iVidaUtil tempo de vida util do bem (em anos)
   */
  public function setVidaUtil($iVidaUtil);
  
  /**
   * Retorna o percentual depreciado
   * @return float 
   */
  public function getPercentualDepreciado();
  
  /**
   * Retorna o valor depreciado
   * @return float
   */
  public function getValorDepreciado();
    
  /**
   * Realiza o calculo da Depreciaчуo, 
   * aplicando a formula de cada calculo.
   */
  public function calcular();
   
  
}

?>