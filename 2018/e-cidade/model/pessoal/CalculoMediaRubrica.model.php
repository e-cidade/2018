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

require_once('interfaces/ICalculoMediaRubrica.interface.php');

/**
 * Classe para manipuação de rubricas
 *
 * @author   Alberto Ferri Neto alberto@dbseller.com.br
 * @author   Everton Heckler everton.heckler@dbseller.com.br
 * @package  Pessoal
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.10 $
 */

class CalculoMediaRubrica {
  
  /**
   * 0 - SEM MÉDIA
   * 1 - VALOR INTEGRAL  (QUANDO TIVER NO FIXO)
   * 2 - VALOR INTEGRAL(EXISTINDO OU NÃO NO FIXO)
   * 3 - PROP. NUM DE MESES (EXISTINDO NO PONTO)
   * 4 - PROP. DE MESES (EXIST. OU NÃO NO PONTO)
   * 5 - MEDIA POR QUANTIDADE (EXIST. NO PONTO)
   * 6 - QUANTIDADE (EXIST. OU NÃO NO PONTO)
   * 7 - EXISTINDO INTEGRAL, NÃO EXIST.NUM MESES
   * 8 - MÉDIA  DE VALORES
   * 9 - MÉDIA OCORRÊNCIA DE VALORES/QTD
   */  
  const SEM_MEDIA                                = 0; 
  const MEDIA_VALOR_INTEGRAL_EXISTINDO_FIXO      = 1;
  const MEDIA_VALOR_INTEGRAL                     = 2;
  const PROPORCIONAL_NUMERO_MESES_EXISTINDO_FIXO = 3;
  const PROPORCIONAL_MESES                       = 4;
  const MEDIA_POR_QUANTIDADE_EXISTINDO_FIXO      = 5;
  const MEDIA_POR_QUANTIDADE                     = 6;
  const EXISTINDO_INTEGRAL                       = 7; /* NÃO EXISTINDO NUMERO DE MESES */
  const MEDIA_DE_VALORES                         = 8;
  const MEDIA_OCORRENCIA_VALORES                 = 9; /* QUANTIDADE */

  const TIPO_CALCULO_FERIAS                      = 'ferias'; 
  const TIPO_CALCULO_13o                         = '13o'; 

  /**
   * Instancia do algoritimo de calculo da média de rubrica 
   */
  private $oCalculoMedia = null;
 
  /**
   * Método construtor da classe CalculoMediaRubrica
   * @param Servidor $oServidor
   * @param Rubrica $oRubrica
   * @param DBDate $oDataInicial
   * @param DBDate $oDataFinal
   * @param integer $iTipoMedia
   * @throws BusinessException
   */
  public function __construct ( Servidor $oServidor, Rubrica $oRubrica, DBDate $oDataInicial, DBDate $oDataFinal, $sTipoCalculo ) {

    $iTipoMedia = ($sTipoCalculo == CalculoMediaRubrica::TIPO_CALCULO_FERIAS) ? $oRubrica->getMediaFerias() : $oRubrica->getMedia13oSalario();

    switch ($iTipoMedia) {
      
      case CalculoMediaRubrica::SEM_MEDIA:
        $this->setInstancia(new CalculoMediaRubricaSemMedia($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::MEDIA_VALOR_INTEGRAL_EXISTINDO_FIXO:
        $this->setInstancia(new CalculoMediaRubricaValorIntegralFixo($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::MEDIA_VALOR_INTEGRAL :
        $this->setInstancia(new CalculoMediaRubricaValorIntegral($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::PROPORCIONAL_NUMERO_MESES_EXISTINDO_FIXO :
        $this->setInstancia(new CalculoMediaRubricaProporcionalMesesFixo($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::PROPORCIONAL_MESES : 
        $this->setInstancia(new CalculoMediaRubricaProporcionalMeses($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::MEDIA_POR_QUANTIDADE_EXISTINDO_FIXO :
        $this->setInstancia(new CalculoMediaRubricaQuantidadeFixo($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      case CalculoMediaRubrica::MEDIA_POR_QUANTIDADE :
        $this->setInstancia(new CalculoMediaRubricaPorQuantidade($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;
        
      case CalculoMediaRubrica::EXISTINDO_INTEGRAL : 
        $this->setInstancia(new CalculoMediaRubricaExistindoIntegral($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;
        
      case CalculoMediaRubrica::MEDIA_DE_VALORES : 
        $this->setInstancia(new CalculoMediaRubricaMediaValores($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;
        
      case CalculoMediaRubrica::MEDIA_OCORRENCIA_VALORES :
        $this->setInstancia(new CalculoMediaRubricaOcorrenciaValores($oServidor, $oRubrica, $oDataInicial, $oDataFinal));
      break;

      default :
        throw new BusinessException('Tipo de média não informada ou inválida.');
      break;   
    }
  }
  
  /**
   * metodo que chama o metodo de calculo de cada classe 
   * @see ICalculoMediaRubrica::calcular()
   */
  public function calcular() {
    return $this->oCalculoMedia->calcular();
  }
    
  /**
   * Define a Instancia do Cálculo a ser efetuado 
   * @var $oCalculoMediaRubrica ICalculoMediaRubrica
   */
  private function setInstancia(ICalculoMediaRubrica $oCalculoMediaRubrica) {    
    $this->oCalculoMedia = $oCalculoMediaRubrica;    
  }

  /**
   * Retorn o valor calculado 
   */
  public function getValorCalculado() {
    return $this->oCalculoMedia->getValorCalculado();
  }

  /**
   * Retorn a quantidade calculada 
   */
  public function getQuantidadeCalculada() {
    return $this->oCalculoMedia->getQuantidadeCalculada();
  }

  /**
   * Valida se nao existe media de Calculo 
   * 
   * @access public
   * @return void
   */
  public function isSemMediaCalculo() {
    return is_object( $this->oCalculoMedia ) ? true : false;
  }
 
 /**
   * Retorna rubrica 
   * 
   * @access public
   * @return Rubrica
   */
  public function getRubrica() {
    return  $this->oCalculoMedia->getRubrica();
  }  
}