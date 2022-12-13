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
 * Dados das Disciplinas cursadas
 * @author Iuri Guntchnigg
 * @package Educacao
 */
abstract class DisciplinaHistorico {

  /**
   * Codigo da Disciplina
   * @var integer
   */
  protected $iCodigo;

  /**
   * Disciplina
   * @var Disciplina
   */
  protected $oDisciplina;

  /**
   * Codigo da Etapa
   * @var integer
   */
  protected $iEtapaCursada;

  /**
   * Justificativa quando
   * a disciplina for amparada.
   * @var integer
   */
  protected $iJustificativa;

  /**
   * Carga horaria da Disciplina
   * @var integer
   */
  protected $iCargaHoraria;

  /**
   * Resultado obtido na disciplina
   * @var string
   */
  protected $sResultadoObtido;

  /**
   * Situacao da Disciplina
   * @var string
   */
  protected $sSituacaoDisciplina;

  /**
   * Tipo do Resultado Obtido
   * @var integer
   */
  protected $sTipoResultado;

  /**
   * Ordenaçao da disciplina dentro da Etapa;
   * @var integer
   */
  protected $iOrdem;

  /**
   * Retorna o Resultado Final;
   */
  protected $sResultadoFinal;

  /**
   * Termo final da disciplina
   */
  protected $sTermoFinal;

  /**
   * Indica se o lancamento foi automatico ou manual
   * @var boolean
   */
  protected $lLancamentoAutomatico;

  /**
   * Indica se disciplina é opcional
   * @var bool
   */
  protected $lOpcional;

  /**
   * Indica se a disciplina é da base comum nacional ou da parte diversificada
   * @var bool
   */
  protected $lBaseComum;

  /**
   *
   */
  abstract function __construct($iCodigo);


  abstract function salvar($iCodigoEtapa = null);

  /**
   * Retorna a carga horaria cursada na disciplina
   * @return integer
   */
  public function getCargaHoraria() {
    return $this->iCargaHoraria;
  }

  /**
   * Define a carga horaria da disciplina
   * @param integer $iCargaHoraria
   */
  public function setCargaHoraria($iCargaHoraria) {
    $this->iCargaHoraria = $iCargaHoraria;
  }

  /**
   * Retorna o codigo da disciplina dentro da Etapa
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a justificativa do amparo legal;
   * @return integer
   */
  public function getJustificativa() {
    return $this->iJustificativa;
  }

  /**
   * Define o amparo legal da Discplina
   * @param integer $iJustificativa
   */
  public function setJustificativa($iJustificativa) {

    $this->iJustificativa = $iJustificativa;
  }

  /**
   * Define a ordem na disciplina dentro da etapa
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Retorna a Disciplina
   * @param integer $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna a Disciplina que foi cursada
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina($oDisciplina) {
    $this->oDisciplina = $oDisciplina;
  }

  /**
   * Retorna o resultado atingido na disciplina
   * @return string
   */
  public function getResultadoObtido() {
    return $this->sResultadoObtido;
  }

  /**
   * Define o resultado obtido na disciplina
   * @param string $sResultadoObtido
   */
  public function setResultadoObtido($sResultadoObtido) {
    $this->sResultadoObtido = $sResultadoObtido;
  }

  /**
   * Retorna a situacao da discplina
   * @return string
   */
  public function getSituacaoDisciplina() {
    return $this->sSituacaoDisciplina;
  }

  /**
   * Define a situacao da Disciplina
   * @param string $sSituacaoDisciplina
   */
  public function setSituacaoDisciplina($sSituacaoDisciplina) {
    $this->sSituacaoDisciplina = $sSituacaoDisciplina;
  }

  /**
   * @return integer
   */
  public function getTipoResultado() {
    return $this->sTipoResultado;
  }
  /**
   * @param integer $sTipoResultado
   */
  public function setTipoResultado($sTipoResultado) {
    $this->sTipoResultado = $sTipoResultado;
  }
  /**
   * @return integer
   */
  public function getResultadoFinal() {
    return $this->sResultadoFinal;
  }

  /**
   * @param integer $sTipoResultado
   */
  public function setResultadoFinal($sResultadoFinal) {
    $this->sResultadoFinal = $sResultadoFinal;
  }

  /**
   * Retorna o termo final
   * @return string
   */
  public function getTermoFinal() {
    return $this->sTermoFinal;
  }

  /**
   * Seta o termo final da disciplina no bistorico
   * @param string $sTermoFinal
   */
  public function setTermoFinal($sTermoFinal) {
    $this->sTermoFinal = $sTermoFinal;
  }

  /**
   * Retorna se o lancamento foi automatico (gerado pelo sistema ao encerrar avaliacoes)
   * @return boolean
   */
  public function isLancamentoAutomatico() {
    return $this->lLancamentoAutomatico;
  }

  /**
   * Seta se a forma de lancamento foi automatico (gerado pelo sistema ao encerrar avaliacoes)
   * @param boolean $lLancamentoAutomatico
   */
  public function setLancamentoAutomatico($lLancamentoAutomatico) {
    $this->lLancamentoAutomatico = $lLancamentoAutomatico;
  }

  /**
   * Seta se disciplina é opcional
   * @param boolean $lOpcional
   */
  public function setOpcional($lOpcional) {
    $this->lOpcional = $lOpcional;
  }

  /**
   * Retorna se disciplina é opcional
   * @return boolean
   */
  public function isOpcional() {
    return $this->lOpcional;
  }

  abstract function remover();

  /**
   * Seta se a disciplina faz parte da Base Comum Nacional ou da Parte Diversificada
   * @param boolean $lBaseComum
   */
  public function setBaseComum( $lBaseComum ) {
    $this->lBaseComum = $lBaseComum;
  }

  /**
   * Retorna se disciplina é da Base Comum Nacional ou Parte Diversificada
   * @return boolean [description]
   */
  public function isBaseComum() {
    return $this->lBaseComum;
  }
}
?>