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

define("ARQUIVO_MENSAGEM_HISTORICO_ETAPA", "educacao.escola.HistoricoEtapa.");
 /**
  * Classe para controle das etapas do historico
  * @author Iuri Guntchnigg;
  * @package Educacao;
  */
 abstract class HistoricoEtapa {

   /**
    * Tipo de Etapa
    */
   const ETAPA_REDE      = 1;
   const ETAPA_FORA_REDE = 2;

  /**
   * Codigo da Etapa cursada
   */
  protected $iCodigoEtapa;

  /**
   * Etapa Cursada
   * @var Etapa
   */
  protected $oEtapa;

  /**
   * Ano em que a etapa foi cursada
   */
  protected $iAnoCurso;

  /**
   * Resultado final na etapa
   * @var string
   */
  protected $sResultadoFinal;

  /**
   * Define a escola em que a etapa foi cursada
   * Objeto deve implementar interface escola
   * @var IEscola
   */
  protected $oEscola;

  protected $sResultadoAno;

  protected $sSituacaoEtapa;

  /**
   * Carga horaria total da Etapa;
   * @var integer
   */
  protected $iCargaHoraria;

  /**
   * Dias letivos da etapa
   * @var integer
   */
  protected $iDiasLetivos;

  /**
   * Nota/Conceito minimo para Aprovacao
   * @var String
   */
  protected $iMininoAprovacao;


  /**
   * Justificava para quando a conclusao da etapa foi
   * realizada por algum amparo legal.
   * @var integer
   */
  protected $iJustificativa;


  /**
   * Turmma em que concluiu a etapa
   * @var string
   */
  protected $sTurma;


  /**
   * Disciplinas da Etapa
   * @var Array
   */
  protected $aDisciplinas = array();

  /**
   * Codigo do historico em que a etapa est� vinculada
   * @var integer
   */
  protected $iCodigoHistorico;

  /**
   * Indica se o lancamento do historico foi automatica (true) ou manual (false)
   * @var boolean
   */
  protected $lLancamentoAutomatico;

  /**
   * Termo informado pelo usu�rio ao lan�ar o hist�rico
   * @var string
   */
  protected $sTermoFinal;

  /**
   * Observa��o lan�ado no hist�rico da etapa
   * @var text
   */
  protected $sObservacao;

  /**
   *
   */
  function __construct($iCodigoEtapa = null) {

  }
  /**
   * @return unknown
   */
  public function getAnoCurso() {
    return $this->iAnoCurso;
  }
  /**
   * @param integer $iAnoCurso
   */
  public function setAnoCurso($iAnoCurso) {
    $this->iAnoCurso = $iAnoCurso;
  }

  /**
   * @return integer
   */
  public function getCargaHoraria() {
    return $this->iCargaHoraria;
  }

  /**
   * @param integer $iCargaHoraria
   */
  public function setCargaHoraria($iCargaHoraria) {
    $this->iCargaHoraria = $iCargaHoraria;
  }

  /**
   * @return unknown
   */
  public function getCodigoEtapa() {
    return $this->iCodigoEtapa;
  }

  /**
   * @return integer
   */
  public function getDiasLetivos() {
    return $this->iDiasLetivos;
  }

  /**
   * @param integer $iDiasLetivos
   */
  public function setDiasLetivos($iDiasLetivos) {
    $this->iDiasLetivos = $iDiasLetivos;
  }

  /**
   * @return integer
   */
  public function getJustificativa() {

    return $this->iJustificativa;
  }

  /**
   * @param integer $iJustificativa
   */
  public function setJustificativa($iJustificativa) {
    $this->iJustificativa = $iJustificativa;
  }

  /**
   * @return String
   */
  public function getMininoParaAprovacao() {

    return $this->iMininoAprovacao;
  }

  /**
   * @param String $iMininoAprovacao
   */
  public function setMininoParaAprovacao($iMininoAprovacao) {

    $this->iMininoAprovacao = $iMininoAprovacao;
  }

  /**
   * @return IEscola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * @param IEscola $oEscola
   */
  public function setEscola(IEscola $oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * @return Etapa
   */
  public function getEtapa() {
    return $this->oEtapa;
  }

  /**
   * @param Etapa $oEtapa
   */
  public function setEtapa(Etapa $oEtapa) {
    $this->oEtapa = $oEtapa;
  }

  /**
   * @return unknown
   */
  public function getResultadoAno() {
    return $this->sResultadoAno;
  }

  /**
   * @param unknown_type $sResultadoAno
   */
  public function setResultadoAno($sResultadoAno) {
    $this->sResultadoAno = $sResultadoAno;
  }

  /**
   * @return string
   */
  public function getResultadoFinal() {
    return $this->sResultadoFinal;
  }

  /**
   * @param string $sResultadoFinal
   */
  public function setResultadoFinal($sResultadoFinal) {
    $this->sResultadoFinal = $sResultadoFinal;
  }

  /**
   *
   * @return string
   */
  public function getSituacaoEtapa() {
    return $this->sSituacaoEtapa;
  }

  /**
   * @param string $sSituacaoEtapa
   */
  public function setSituacaoEtapa($sSituacaoEtapa) {
    $this->sSituacaoEtapa = $sSituacaoEtapa;
  }
  /**
   * @return string
   */
  public function getTurma() {
    return $this->sTurma;
  }

  /**
   * @param string $sTurma
   */
  public function setTurma($sTurma) {
    $this->sTurma = $sTurma;
  }

  /**
   * Retorna as disciplinas da turma
   * @return DisciplinaHistoricoRede|DisciplinaHistoricoForaRede Colecao de disciplina
   */
  public abstract function getDisciplinas();

 /**
   * Remove a disciplina da Etapa.
   * @param integer $iCodigoDeLancamentoDisciplina C�digo de lancamento da Disciplina.
   */
  public function removerDisciplina($iCodigoDeLancamentoDisciplina) {

    if (empty($iCodigoDeLancamentoDisciplina)) {
      throw new ParameterException("Codigo da Disciplina deve ser informado.");
    }
    $iIndiceRemover = null;
    $oDisciplina     = $this->getDisciplinaByCodigoDeLancamento($iCodigoDeLancamentoDisciplina);
    foreach ($this->aDisciplinas as $iIndice => $oDisciplinaIndice) {
      if ($oDisciplinaIndice->getCodigo() == $iCodigoDeLancamentoDisciplina) {

        $iIndiceRemover = $iIndice;
        break;
      }
    }

    $oDisciplina->remover();
    unset($oDisciplina);
    array_splice($this->aDisciplinas, $iIndice, 1);
  }

  /**
   * Retorna se o lancamento do historico foi automatico, ou seja, gerado ao encerrar as avaliacoes da turma
   * @return boolean
   */
  public function isLancamentoAutomatico() {
    return $this->lLancamentoAutomatico;
  }

  /**
   * Seta se o lancamento foi automatico, ou seja, gerado ao encerrar as avaliacoes da turma
   * @param boolean $lLancamentoAutomatico
   */
  public function setLancamentoAutomatico($lLancamentoAutomatico) {
    $this->lLancamentoAutomatico = $lLancamentoAutomatico;
  }

  /**
   * Define o termo final informado pelo usu�rio
   * @param string $sTermoFinal
   */
  public function setTermoFinal($sTermoFinal) {

    $this->sTermoFinal = $sTermoFinal;
  }

  /**
   * Retorna o termo final informado pelo usu�rio
   * @return string
   */
  public function getTermoFinal() {
    return $this->sTermoFinal;
  }

  /**
   * "Constr�i" uma etapa pelo tipo
   *
   *  @param  $sTipoEtapa   - Define se o Tipo da Etapa Deve ser de Dentro ou fora da Rede.
   *  @param  $iCodigoEtapa - Codiigo da Etapa(Sequencial da Tabela, quando o Tipo de Etapa for na REDE(ed62_i_codigo)
   *                          ou "Fora da Rede"(ed99_i_codigo)
   *  @return HitoricoEtapa
   */
  public static function getInstanciaPeloTipo( $sTipoEtapa, $iCodigoEtapa = null ) {

     switch ( $sTipoEtapa ) {
       case HistoricoEtapa::ETAPA_REDE :
         return new HistoricoEtapaRede($iCodigoEtapa);
       break;
       case HistoricoEtapa::ETAPA_FORA_REDE :
         return new HistoricoEtapaForaRede($iCodigoEtapa);
       break;
       default:
         throw new ParameterException("Tipo de Etapa n�o Informada.");
       break;
     }
     return;
  }

  /**
   *  Define o Percentual de Frequencia Cursado.
   *  @return number
   */
  public function setPercentualFrequencia( $nPercentualFrequencia ) {

    $this->nPercentualFrequencia = $nPercentualFrequencia;
    return;
  }

  /**
   *  Retorna o Percentual de Frequencia Cursado.
   *  @return number
   */
  public function getPercentualFrequencia() {
    return $this->nPercentualFrequencia;
  }

  /**
   * Retorna a ultima etapa do hist�rico cursada pelo aluno
   * @param Aluno $oAluno
   * @throws DBException
   * @return Ambigous <HitoricoEtapa, void, HistoricoEtapaRede, HistoricoEtapaForaRede>
   */
  public static function getUltimaEtapaAluno( Aluno $oAluno ) {

    $oDaoHistorico  = new cl_historico();
    $sSqlEtapaAluno = $oDaoHistorico->sql_query_etapasHistoricoPorAluno($oAluno->getCodigoAluno(),
                                                                        "fora_rede, codigo",
                                                                        "anoref desc limit 1");
    $rsEtapaAluno   = db_query($sSqlEtapaAluno);

    if ( !$rsEtapaAluno ) {
      throw new DBException(_M(ARQUIVO_MENSAGEM_HISTORICO_ETAPA."erro_query_busca_ultima_etapa"));
    }

    if ( pg_num_rows($rsEtapaAluno) ) {
      return null;
    }

    $oUltimaEtapaCurso = db_utils::fieldsMemory($rsEtapaAluno, 0);

    $iTipoEtapa = $oUltimaEtapaCurso->fora_rede == 't' ? HistoricoEtapa::ETAPA_FORA_REDE : HistoricoEtapa::ETAPA_REDE;
    $iCodigo    = $oUltimaEtapaCurso->codigo;
    return HistoricoEtapa::getInstanciaPeloTipo($iTipoEtapa, $iCodigo);
  }

  /**
   * Verifica ultima etapa cursada no hist�rico
   *
   * @todo refatorar l�gica para reutilizar a mensagem
   *
   * @param Aluno $oAluno              Aluno
   * @param Etapa $oEtapa              Etapa a ser valid�do se j� consta no hist�rico
   * @param array $aCodigoEtapasTurma  c�digos das etapas da turma
   * @return boolean
   */
  public static function verificaUltimoRegistroHistorico(Aluno $oAluno, Etapa $oEtapa, $aCodigoEtapasTurma) {

    $oUltimaEtapaCursada = HistoricoEtapa::getUltimaEtapaAluno($oAluno);

    $oMensagem               = new stdClass();
    $oMensagem->aluno        = $oAluno->getNome();
    $oMensagem->etapa        = $oEtapa->getNome();
    $oMensagem->ensino       = $oEtapa->getEnsino()->getNome();
    $oMensagem->ensino_abrev = $oEtapa->getEnsino()->getAbreviatura();
    $aMensagemEquivalencia   = array();

    if ( $oUltimaEtapaCursada ) {//@TASK Ver !empty

      switch ($oUltimaEtapaCursada->getResultadoFinal()) {

      	case 'R':

      	  $oMensagem->situacao = "REPROVADO";
      	  if ($oUltimaEtapaCursada->getEtapa()->getCodigo() != $oEtapa->getCodigo()) {

      	    $aEtapasEquivalente = $oUltimaEtapaCursada->getEtapa()->buscaEtapaEquivalente();
      	    $lTemEquivalencia   = false;

      	    $sMsg  = "-> {$oUltimaEtapaCursada->getEtapa()->getNome()} (";
      	    $sMsg .= $oUltimaEtapaCursada->getEtapa()->getEnsino()->getNome();
      	    $sMsg .= " - {$oUltimaEtapaCursada->getEtapa()->getEnsino()->getAbreviatura()}";

      	    $aMensagemEquivalencia[] = $sMsg;
      	    foreach ($aEtapasEquivalente as $oEtapaEquivalente) {

      	      if ($oEtapaEquivalente->getCodigo() == $oEtapa->getCodigo()) {

      	        $lTemEquivalencia = true;
      	        continue;  // Antigamente a rotina utilizava um break
      	      } else {

      	        $sMsg  = "{$oEtapaEquivalente->getEtapa()->getNome()} (";
      	        $sMsg .= $oEtapaEquivalente->getEtapa()->getEnsino()->getNome();
      	        $sMsg .= " - {$oEtapaEquivalente->getEtapa()->getEnsino()->getAbreviatura()}";
      	        $aMensagemEquivalencia[] = $sMsg;
      	      }
      	    }

      	    if (!$lTemEquivalencia) {

      	      $oMensagem->etapas_equivalentes = implode("\n", $aMensagemEquivalencia);
      	      throw new BusinessException(_M(ARQUIVO_MENSAGEM_HISTORICO_ETAPA."etapas_relacionadas_etapa_cursada", $oMensagem));
      	    }
      	  }
      	  break;
      	case 'A':

      	  $oMensagem->situacao = "APROVADO";
      	  $aEtapasPosteriores  = EtapaRepository::getEtapasPosteriores($oUltimaEtapaCursada);

      	  if (count($aEtapasPosteriores) == 0) {
      	    break;
      	  }
      	  $oProximaEtapa = $aEtapasPosteriores[0];
      	  unset($aEtapasPosteriores);

      	  if ($oProximaEtapa->getCodigo() != $oEtapa->getCodigo()) {

      	    $aEtapasEquivalente = $oUltimaEtapaCursada->getEtapa()->buscaEtapaEquivalente();
      	    $lTemEquivalencia   = false;

      	    $sMsg  = "-> {$oUltimaEtapaCursada->getEtapa()->getNome()} (";
      	    $sMsg .= $oUltimaEtapaCursada->getEtapa()->getEnsino()->getNome();
      	    $sMsg .= " - {$oUltimaEtapaCursada->getEtapa()->getEnsino()->getAbreviatura()}";

      	    $aMensagemEquivalencia[] = $sMsg;
      	    foreach ($aEtapasEquivalente as $oEtapaEquivalente) {

      	      if ($oEtapaEquivalente->getCodigo() == $oEtapa->getCodigo()) {

      	        $lTemEquivalencia = true;
      	        continue;  // Antigamente a rotina utilizava um break
      	      } else {

      	        $sMsg  = "{$oEtapaEquivalente->getEtapa()->getNome()} (";
      	        $sMsg .= $oEtapaEquivalente->getEtapa()->getEnsino()->getNome();
      	        $sMsg .= " - {$oEtapaEquivalente->getEtapa()->getEnsino()->getAbreviatura()}";
      	        $aMensagemEquivalencia[] = $sMsg;
      	      }
      	    }

      	    if (!$lTemEquivalencia) {

      	      $oMensagem->etapas_equivalentes = implode("\n", $aMensagemEquivalencia);
      	      throw new BusinessException(_M(ARQUIVO_MENSAGEM_HISTORICO_ETAPA."etapas_relacionadas_etapa_cursada", $oMensagem));
      	    }
      	  }

      	  break;
      	case 'P':

      	  $oMensagem->situacao = "APROVADO PARCIAL";

      	  if (!in_array($oUltimaEtapaCursada->getEtapa()->getCodigo(), $aCodigoEtapasTurma)) {

      	    $aEtapasEquivalente = $oUltimaEtapaCursada->getEtapa()->buscaEtapaEquivalente();
      	    $lTemEquivalencia   = false;

      	    $sMsg  = "-> {$oUltimaEtapaCursada->getEtapa()->getNome()} (";
      	    $sMsg .= $oUltimaEtapaCursada->getEtapa()->getEnsino()->getNome();
      	    $sMsg .= " - {$oUltimaEtapaCursada->getEtapa()->getEnsino()->getAbreviatura()}";

      	    $aMensagemEquivalencia[] = $sMsg;
      	    foreach ($aEtapasEquivalente as $oEtapaEquivalente) {

      	      if ($oEtapaEquivalente->getCodigo() == $oEtapa->getCodigo()) {

      	        $lTemEquivalencia = true;
      	        continue;  // Antigamente a rotina utilizava um break
      	      } else {

      	        $sMsg  = "{$oEtapaEquivalente->getEtapa()->getNome()} (";
      	        $sMsg .= $oEtapaEquivalente->getEtapa()->getEnsino()->getNome();
      	        $sMsg .= " - {$oEtapaEquivalente->getEtapa()->getEnsino()->getAbreviatura()}";
      	        $aMensagemEquivalencia[] = $sMsg;
      	      }
      	    }

      	    if (!$lTemEquivalencia) {

      	      $oMensagem->etapas_equivalentes = implode("\n", $aMensagemEquivalencia);
      	      throw new BusinessException(_M(ARQUIVO_MENSAGEM_HISTORICO_ETAPA."etapas_relacionadas_etapa_cursada", $oMensagem));
      	    }

      	  }
      	  break;
      }
    }

    return true;
  }

  /**
   * Define uma observa��o para o hist�rico
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {

    $this->sObservacao = $sObservacao;
  }

  /**
   * Retorna a observa��o do hist�rico
   * @return string
   */
  public function getObservacao() {

    return $this->sObservacao;
  }
}