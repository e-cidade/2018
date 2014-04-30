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
 * Procedimento de Avaliacao
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class ProcedimentoAvaliacao {

  /**
   * Codigo sequencial do Procedimento de Avaliacao
   * @var integer
   */
  private $iCodigo;

  /**
   * Descricao do Procedimento de Avaliacao
   * @var string
   */
  private $sDescricao;

  /**
   * Valor inteiro para calculo de frequencia minima do aluno
   * @var integer
   */
  private $iPercentualFrequencia;

  /**
   * Regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @var integer
   */
  private $iFormaCalculoFrequencia;

  /**
   * Instancia da Forma de Avaliacao
   * @var FormaAvaliacao
   */
  private $oFormaAvaliacao;

  /**
   * Array com os elementos que compõe o Procedimento de Avaliacao
   * @var array
   */
  private $aElementos;

  /**
   * @param integer $iCodigoProcedimento
   */
  public function __construct($iCodigoProcedimento = null) {

    if (!empty($iCodigoProcedimento)) {

      $oDaoProcedimento = db_utils::getDao('procedimento');
      $sSqlProcedimento = $oDaoProcedimento->sql_query_file($iCodigoProcedimento);
      $rsProcedimento   = $oDaoProcedimento->sql_record($sSqlProcedimento);

      if ($oDaoProcedimento->numrows >0) {

        $oProcedimento                 = db_utils::fieldsMemory($rsProcedimento, 0);
        $this->iCodigo                 = $oProcedimento->ed40_i_codigo;
        $this->sDescricao              = $oProcedimento->ed40_c_descr;
        $this->iPercentualFrequencia   = $oProcedimento->ed40_i_percfreq;
        $this->iFormaCalculoFrequencia = $oProcedimento->ed40_i_calcfreq;
        $this->oFormaAvaliacao         = new FormaAvaliacao($oProcedimento->ed40_i_formaavaliacao);
      }
    }
  }

  /**
   * retorna o codigo sequencial do Procedimento de Avaliacao
   * @return integer
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * atribui uma descricao ao Procedimento de Avaliacao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }

  /**
   * retorna uma descricao ao Procedimento de Avaliacao
   * @return string
   */
  public function getDescricao() {

    return $this->sDescricao;
  }

  /**
   * atribui um valor inteiro para calculo de frequencia minima do aluno
   * @param integer $iPercentualFrequencia
   */
  public function setPercentualFrequencia($iPercentualFrequencia) {

    $this->iPercentualFrequencia = $iPercentualFrequencia;
  }

  /**
   * retorna um valor inteiro para calculo de frequencia minima do aluno
   * @return integer
   */
  public function getPercentualFrequencia() {

    return $this->iPercentualFrequencia;
  }

  /**
   * Atribui uma regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @param integer $iFormaCalculoFrequencia
   */
  public function setFormaCalculoFrequencia($iFormaCalculoFrequencia) {

    $this->iFormaCalculoFrequencia = $iFormaCalculoFrequencia;
  }

  /**
   * Retorna uma regra para calcular a frequencia
   * 1 = calculo de frequencia individual por disciplina
   * 2 = calculo de frequencia geral
   * @return integer
   */
  public function getFormaCalculoFrequencia() {

    return $this->iFormaCalculoFrequencia;
  }

  /**
   * Atribui uma forma de Avaliacao para o Procedimento de Avaliacao
   * @param FormaAvaliacao $oFormaAvaliacao
   */
  public function setFormaAvaliacao(FormaAvaliacao $oFormaAvaliacao) {

    $this->oFormaAvaliacao = $oFormaAvaliacao;
  }

  /**
   * Retorna uma forma de Avaliacao para o Procedimento de Avaliacao
   * @return FormaAvaliacao
   */
  public function getFormaAvaliacao() {

    return $this->oFormaAvaliacao;
  }

  /**
   * Busca os elementos de avaliacao do procedimento
   * @throws BusinessException
   * @return AvaliacaoPeriodica|ResultadoAvaliacao
   */
  public function getElementos() {

    if (count($this->aElementos) == 0 && !empty($this->iCodigo)) {

      $oDaoElementos  = db_utils::getDao('procedimento');
      $sWhereProcAval = " {$this->getCodigo()} ";
      $sWhereProcRes  = " {$this->getCodigo()} ";
      $sSqlElementos  = $oDaoElementos->sql_query_procedimentoavaliacao($sWhereProcAval, $sWhereProcRes);
      $rsElementos    = $oDaoElementos->sql_record($sSqlElementos);
      $iTotalLinhas   = $oDaoElementos->numrows;

      if ($iTotalLinhas > 0) {

        for($iContador = 0; $iContador < $iTotalLinhas; $iContador++) {

          $oElementos = db_utils::fieldsMemory($rsElementos, $iContador);

          switch ($oElementos->tipo) {

            case 'A':

              $this->aElementos[$oElementos->sequencia] = new AvaliacaoPeriodica($oElementos->codigo_elemento);
              break;

            case 'R':

              $this->aElementos[$oElementos->sequencia] = new ResultadoAvaliacao($oElementos->codigo_elemento);
              break;

            default:
              throw new BusinessException("Nenhum elemento encontrado para o procedimento.");
          }
        }
      }
    }
    return $this->aElementos;
  }

  /**
   * Verifica se os procedimentos de avaliacao sao equivalentes
   * @param ProcedimentoAvaliacao $oProcedimentoAvaliacaoEquivalente
   * @return boolean
   */
  public function temEquivalencia(ProcedimentoAvaliacao $oProcedimentoAvaliacaoEquivalente) {

    if ($this->getCodigo() == $oProcedimentoAvaliacaoEquivalente->getCodigo()) {
      return true;
    }

    /**
     * verifcamos se todos os resultados sao compativeis, isto é, possuem o mesmo resultado e forma de avaliacao para
     * resultados
     */
    $aResultados                        = $this->getResultados();
    $aResultadosProcedimentoEquivalente = $oProcedimentoAvaliacaoEquivalente->getResultados();
    $iResultadoEquivalentes             = 0;

    foreach ($aResultados as $oResultadoAvaliacao) {

      $oFormaDeAvaliacao = $oResultadoAvaliacao->getFormaDeAvaliacao();
      $oTipoDeResultado  = $oResultadoAvaliacao->getTipoResultado();
      foreach ($aResultadosProcedimentoEquivalente as $oResultadoEquivalente) {

        if ($oTipoDeResultado->getCodigo() == $oResultadoEquivalente->getTipoResultado()->getCodigo() &&
            $oFormaDeAvaliacao->getCodigo() == $oResultadoEquivalente->getFormaDeAvaliacao()->getCodigo()) {
          $iResultadoEquivalentes++;
        }
      }
    }

    /**
     * verifcamos se todos as avaliacoes periodicas sao compativeis, isto é, possuem o mesmo periodo de avaliacao
     * e forma de avaliacao
     */
    $aAvaliacoes                        = $this->getAvaliacoes();
    $aAvaliacoesProcedimentoEquivalente = $oProcedimentoAvaliacaoEquivalente->getAvaliacoes();
    $iTotalAvaliaçõesEquivalentes       = 0;
    foreach ($aAvaliacoes as $oAvaliacao) {

      $oFormaDeAvaliacao = $oAvaliacao->getFormaDeAvaliacao();
      $oPeriodo          = $oAvaliacao->getPeriodoAvaliacao();
      foreach ($aAvaliacoesProcedimentoEquivalente as $oAvaliacaoEquivalente) {

        if ($oFormaDeAvaliacao->getCodigo() == $oAvaliacaoEquivalente->getCodigo() &&
            $oPeriodo->getCodigo() == $oAvaliacao->getCodigo()) {
         $iTotalAvaliaçõesEquivalentes++;
        }
      }
    }

    if (count($aResultados) != $iResultadoEquivalentes && count($aAvaliacoes) != $iTotalAvaliaçõesEquivalentes) {
      return false;
    }
    return true;
  }

  /**
   * Retorna um array com os elementos que são Resultados
   * @return ResultadoAvaliacao - coleção de ResultadoAvaliacao
   */
  public function getResultados() {

    $aElementos = array();

    foreach ($this->getElementos() as $oElemento) {

      if ($oElemento->isResultado()) {
        $aElementos[] = $oElemento;
      }
    }

    return $aElementos;
  }

  /**
   * Retorna um array com os elementos que são AvaliacaoPeriodica
   * @return AvaliacaoPeriodica - coleção de AvaliacaoPeriodica
   */
  public function getAvaliacoes() {

    $aElementos = array();

    foreach ($this->getElementos() as $oElemento) {

      if (!$oElemento->isResultado()) {
        $aElementos[] = $oElemento;
      }
    }

    return $aElementos;
  }
}