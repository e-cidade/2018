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

/**
 * Avaliacao por Nivel - (Conceito)
 * Retorna o maior nivel (definido pela ordem de cadastro do nivel)
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.7 $
 */
require_once(modification("model/educacao/avaliacao/FormaObtencao.model.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
class FormaObtencaoMaiorNivel extends FormaObtencao implements IFormaObtencao {

  /**
   * Define as notas que ira ser usada no calculo
   * Recebe um array de AvaliacaoAproveitamento
   * @param $aAproveitamentos
   * @param $iAno
   * @return ValorAproveitamentoNivel
   */
  public function processarResultado( $aAproveitamentos, $iAno ) {

    $mAproveitamento = new ValorAproveitamentoNivel();
    $iOrdem          = 0;
    $aElementos      = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
    /**
     * Percorremos o array de aproveitamentos buscando o maior nivel
     */
    foreach ($aAproveitamentos as $oAproveitamento) {

      if ($oAproveitamento->isAmparado()) {
        continue;
      }

      if (isset($aElementos[$oAproveitamento->getOrdemSequencia()])) {

        $oElemento              = $aElementos[$oAproveitamento->getOrdemSequencia()];
        $nAproveitamentoPeriodo = $oAproveitamento->getValorAproveitamento()->getAproveitamento();
        if (!$this->isCalculoNotaParcial() && $oElemento->isObrigatorio() && $nAproveitamentoPeriodo === "") {

          $mAproveitamento = new ValorAproveitamentoNivel();
          $iOrdem          = '';
          break;
        }
      }
      if ($oAproveitamento->getValorAproveitamento()->getOrdem() > $iOrdem
         && $oAproveitamento->getElementoAvaliacao()->getOrdemSequencia() < $this->getResultadoAvaliacao()->getOrdemSequencia()) {

        $mAproveitamento = $oAproveitamento->getValorAproveitamento();
        $iOrdem          = $oAproveitamento->getValorAproveitamento()->getOrdem();
      }
    }
    return $mAproveitamento;
  }
}