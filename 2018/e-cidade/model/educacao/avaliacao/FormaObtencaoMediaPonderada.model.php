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
 * Calculo da media ponderada
 * calcula a m�dia ponderada das avaliacoes
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.14 $
 */
require_once(modification("model/educacao/avaliacao/FormaObtencao.model.php"));
require_once(modification("model/educacao/avaliacao/iFormaObtencao.interface.php"));
class FormaObtencaoMediaPonderada extends FormaObtencao implements IFormaObtencao {


  /**
   * Define as notas que ira ser usaddo no calculo
   * Dever� ser instancias de AvaliacaoAproveitamento
   *
   * @see IFormaObtencao::processarResultado()
   * @param array $aAproveitamentos
   * @throws ParameterException
   * @return \ValorAproveitamentoNota
   */
  public function processarResultado( $aAproveitamentos, $iAno ) {

    $mAproveitamento = '';
    $iTotalPeriodos  = 0;
    $aNotasPeriodos  = $this->getElementosParaCalculo( $aAproveitamentos, $iAno );
    $aElementos      = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();

    foreach ($aNotasPeriodos as $oNotaDoAproveitamento) {

      /**
       * avaliacoes amparadas nao devem contar na media
       */
      if ($oNotaDoAproveitamento->isAmparado()) {
        continue;
      }

      $oElemento              = $aElementos[$oNotaDoAproveitamento->getOrdemSequencia()];
      $nAproveitamentoPeriodo = $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento();
      if (!$this->isCalculoNotaParcial() && $oElemento->isObrigatorio() &&  $nAproveitamentoPeriodo === "") {

        $mAproveitamento = '';
        $iTotalPeriodos  = 0;
        break;
      }

      if ($nAproveitamentoPeriodo !== "") {

        $mAproveitamento += $oNotaDoAproveitamento->getValorAproveitamento()->getAproveitamento() * $oElemento->getPeso();
        $iTotalPeriodos  += $oElemento->getPeso();
      }
    }

    if ($iTotalPeriodos > 0) {
      $mAproveitamento = ArredondamentoNota::arredondar( $mAproveitamento / $iTotalPeriodos, $iAno );
    }

    if (is_object($mAproveitamento)) {
      throw new ParameterException("Erro no c�lculo da m�dia pondera {mAproveitamento � um objeto {$mAproveitamento->getAproveitamento}}");
    }

    /**
     * Devolvemos as notas Originais
     */
    $this->acertaNotasSubstituidasParaCalculo($aNotasPeriodos);
    return new ValorAproveitamentoNota($mAproveitamento);
  }

  /**
   * Calcula a nota projetada
   *
   * @param AvaliacaoAproveitamento[] $aElementosAvaliacoes
   * @return string
   */
  public function calcularNotaProjetada(array $aElementosAvaliacoes) {

    $nMinimoAprovacao = $this->oResultadoAvaliacao->getAproveitamentoMinimo();
    if (count($aElementosAvaliacoes) == 0) {
      return $nMinimoAprovacao;
    }

    $nSomaElementos   = 0;
    $nSomaDosPesos    = 0;
    $aElementos       = $this->getResultadoAvaliacao()->getElementosComposicaoResultado();
    foreach ($aElementos as $oElementosCalculo) {
      $nSomaDosPesos  += $oElementosCalculo->getPeso();
    }

    $nSomaPesosDasNotasInformadas = 0;
    foreach ($aElementosAvaliacoes as $oElementoAvaliacao) {

      $oElemento       = $aElementos[$oElementoAvaliacao->getOrdemSequencia()];
      $nSomaElementos += $oElementoAvaliacao->getValorAproveitamento()->getAproveitamento() * $oElemento->getPeso();

      $nSomaPesosDasNotasInformadas += $oElemento->getPeso();
    }

    $nValorMinimo   = $nMinimoAprovacao * $nSomaDosPesos;
    $nNotaProjetada = ($nValorMinimo - $nSomaElementos) / ($nSomaDosPesos - $nSomaPesosDasNotasInformadas);
    return $nNotaProjetada < 0 ? '' : $nNotaProjetada;
  }
}