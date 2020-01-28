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
 * Classe para manipuação de rubricas por tipo de média proporcional ao número de meses 
 * Pode existir ou não no ponto
 *
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbalberto $
 * @version  $Revision: 1.5 $
 */
class CalculoMediaRubricaProporcionalMeses implements ICalculoMediaRubrica{
  /**
   * Instancia do objeto Servidor
   * @var Servidor
   */
  private $oServidor;
  
  /**
   * Instancia do objeto Rubrica
   * @var Rubrica
   */
  private $oRubrica;
  
  /**
   * Data inicial do período aquisitivo/específico
   * @var DBDate
   */
  private $oDataInicial;
  
  /**
   * Data final do período aquisitivo/específico
   * @var DBDate
   */
  private $oDataFinal;
  
  /**
   * Ano de periodo da folha
   * @var integer
   */
  private $iAnoFolha;
  
  /**
   * Mes de periodo da folha
   * @var integer
   */
  private $iMesFolha;
  
  /**
   * Valor da media
   * @var numeric
   */
  private $nValor = 0;
  
  /**
   * Quantidade da media
   * @var numeric
   */
  private $nQuantidade = 0;
  
  /**
   * Método construtor da classe
   * @param Servidor $oServidor
   * @param Rubrica $oRubrica
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   */
  public function __construct ( Servidor $oServidor, Rubrica $oRubrica, DBDate $oDataInicial, DBDate $oDataFinal ) {
  
    $this->oServidor    = $oServidor;
    $this->oRubrica     = $oRubrica;
    $this->oDataInicial = $oDataInicial;
    $this->oDataFinal   = $oDataFinal;
  
    $this->setAnoFolha(db_anofolha());
    $this->setMesFolha(db_mesfolha());
  
  }

  /**
   * Retorna o ano atual da folha
   *
   * @return integer
   */
  public function getAnoFolha () {
    return $this->iAnoFolha;
  }
  
  /**
   * Define o ano atual da folha
   * @param integer $iAnoFolha
   */
  public function setAnoFolha ($iAnoFolha) {
    $this->iAnoFolha = $iAnoFolha;
  }
  
  /**
   * Retorna o mes atual da folha
   * @return integer
   */
  public function getMesFolha () {
    return $this->iMesFolha;
  }
  
  /**
   * Define o mês atual da folha
   * @param integer $iMesFolha
   */
  public function setMesFolha ($iMesFolha) {
    $this->iMesFolha = $iMesFolha;
  }  
  
  /**
   * Instancia de um objeto Servidor
   * @param Servidor $oServidor
   */
  public function setServidor ($oServidor) {
    $this->oServidor = $oServidor;
  }
  
  /**
   * Retorna a instancia de um objeto Servidor
   * @return Servidor
   */
  public function getServidor () {
    return $this->oServidor;
  }
  
  /**
   * Instancia de um objeto Rubrica
   * @param Servidor $oRubrica
   */
  public function setRubrica ($oRubrica) {
    $this->oRubrica = $oRubrica;
  }
  
  /**
   * Retorna a instancia de um objeto Rubrica
   * @return Servidor
   */
  public function getRubrica () {
    return $this->oRubrica;
  }
  
  /**
   * Retorna o resultado do calculo
   * @see ICalculoMediaRubrica::calcular()
   */
  public function calcular() {
    
    $lCalculou    = false;
    
    $aCompetencia = array_reverse( retornaCompetenciasByPeriodo( $this->oDataInicial, $this->oDataFinal ) );
    
    if (count($aCompetencia) == 0) {
      return false;
    }

    $nValorOcorrencia        = 0;
    $nQuantidadeOcorrencia   = 0;
    
    foreach( $aCompetencia as $oCompetencia ) {
      
      /**
       * GERFSAL
       */
      $oCalculoFolhaSalario = new CalculoFolhaSalario($this->oServidor);
    
      $aMovimentacoesFolhaSalario = $oCalculoFolhaSalario->getMovimentacoes(null, $this->oRubrica->getCodigo());
    
      if ( count ($aMovimentacoesFolhaSalario) > 0) {
        
        $nValorOcorrencia        += $aMovimentacoesFolhaSalario[0]->nValor;
        $nQuantidadeOcorrencia   += round($aMovimentacoesFolhaSalario[0]->nQuantidade / $this->oRubrica->getQuantidadeValor());
        
      } else {
      
        $oCalculoFolhaComplementar = new CalculoFolhaComplementar($this->oServidor);
      
        $aMovimentacoesFolhaComplementar = $oCalculoFolhaComplementar->getMovimentacoes(null, $this->oRubrica->getCodigo());
      
        if ( count ($aMovimentacoesFolhaComplementar) > 0) {
        
          $nValorOcorrencia      += $aMovimentacoesFolhaComplementar[0]->nValor;

          //Ocorrências: Total de ocorrências encontradas, divido pela quantidade padrão da rubrica, arredondando para cima. Ex.: 1,5 = 2 
          
          $nQuantidadeOcorrencia += round($aMovimentacoesFolhaComplementar[0]->nQuantidade / $this->oRubrica->getQuantidadeValor());
        
        }
        
      }
      
    }
    //Fórmula: Quantidade padrão da rubrica, dividindo pelo número de meses do período aquisitivo, vezes o total de ocorrencias encontradas
    
    $this->nValor      = $nValorOcorrencia;
    $this->nQuantidade = ($this->oRubrica->getQuantidadeValor() / count($aCompetencia)) * $nQuantidadeOcorrencia;
      
    return true;
    
  }

  /**
   * Retorn o valor calculado 
   */
  public function getValorCalculado() {
    return $this->nValor;    
  }

  /**
   * Retorn a quantidade calculada 
   */
  public function getQuantidadeCalculada() {
    return $this->nQuantidade;
  }
  
}