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
 
require_once ("interfaces/ILancamentoAuxiliar.interface.php");
require_once ("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php");

/**
 * Model que executa os lancamentos auxiliares para restos a pagar nao processados
 * @author     Bruno Silva
 * @package    contabilidade
 * @subpackage lancamento
 * @version    1.0 $
 */   
class LancamentoAuxiliarInscricaoRestosAPagarNaoProcessado extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {
  
  /**
   * chave para conlancaminscrestosapagarnaoprocessados
   * @var integer
   */
  private $iInscricaoRestosAPagarNaoProcessados;
  
  /**
   * C�digo do Historico
   * @var integer
   */
  private $iHistorico;
  
  /**
   * Valor Total do Lan�amento
   * @var double
   */
  private $nValorTotal;
    
  /**
   * metodo que executao lancamento
   * lancar registros em: 
   *   - conlancaminscrestosapagarnaoprocessados
   *   - conlancamcompl
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {
    
    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoInscricaoRestosAPagarNaoProcessados();
    
    return true;
  }
  
  /**
   * Salva vinculo entre as inscricoes de restos a pagar nao processados com os lancamentos da contabilidade
   * @throws DBException - erro de sql ao incluir na tabela conlancaminscrestosapagarnaoprocessados
   */
  public function salvarVinculoInscricaoRestosAPagarNaoProcessados() {

    $oDaoConLancamInscricaoRestosAPagarNaoProcessados = db_utils::getDao('conlancaminscrestosapagarnaoprocessados');
    $oDaoConLancamInscricaoRestosAPagarNaoProcessados->c108_inscricaorestosapagarnaoprocessados = $this->getInscricaoRestosAPagarNaoProcessados();
    $oDaoConLancamInscricaoRestosAPagarNaoProcessados->c108_codlan                              = $this->iCodigoLancamento;
    $oDaoConLancamInscricaoRestosAPagarNaoProcessados->c108_sequencial                          = null;
    $oDaoConLancamInscricaoRestosAPagarNaoProcessados->incluir(null);
    
    if ($oDaoConLancamInscricaoRestosAPagarNaoProcessados->erro_status == "0") {
      
      $sErroQuery = "N�o foi poss�vel salvar o v�nculo da inscri��o de restos a pagar n�o processados com o lan�amento da contabilidade.";
      throw new DBException($sErroQuery);
    }
    
    return true;
  }

  /**
   * Define o codigo da chave com  tabela conlancaminscrestosapagarnaoprocessados
   * @param integer $iAberturaExercicioOrcamento
   */
  public function setInscricaoRestosAPagarNaoProcessados($iInscricaoRestosAPagarNaoProcessados) {
    $this->iInscricaoRestosAPagarNaoProcessados = $iInscricaoRestosAPagarNaoProcessados;
  }
  
  /**
   * Retorna o codigo da chave com  tabela conlancaminscrestosapagarnaoprocessados
   * @return integer $iAberturaExercicioOrcamento
   */
  public function getInscricaoRestosAPagarNaoProcessados() {
    return $this->iInscricaoRestosAPagarNaoProcessados;
  }
  
  /**
   * Define o valor total
   * @param float $nValorTotal
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;
  }
  
  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal(){
    return $this->nValorTotal;
  }
  
  /**
   * Retorna o hist�rico da opera��o
   */
  public function getHistorico(){
    return $this->iHistorico;
  }
  
  /**
   * Seta o hist�rico da opera��o
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico){
    $this->iHistorico = $iHistorico;    
  }
  
}