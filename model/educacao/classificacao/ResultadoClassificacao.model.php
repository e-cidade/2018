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


define("URL_MENSAGEM_RESULTADOCLASSIFICACAO", "educacao.escola.ResultadoClassificacao.");

/**
 * Esta classe contem o resultado de uma classificação/reclassificação realizada por um aluno
 * 
 * @package educacao
 * @subpackage classificacao
 * @author Andrio Costa <andrio.costa@dbseller.com>
 * @version $Revision: 1.2 $
 * 
 */
final class ResultadoClassificacao {
	
  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Disciplina avaliada
   * @var Disciplina
   */
  private $oDisciplina;
  
  /**
   * Resultado lançado para disciplina
   * @var string
   */
  private $sResultado;
  
  /**
   * Cria a instancia de um ResultadoClassificacao
   * @param unknown $iCodigo
   * @throws ParameterException
   */
  public function __construct($iCodigo = null) {
  	
    if (!empty($iCodigo)) {
    	
      $oDaoAvaliacao = new cl_avaliacaoclassificacao();
      $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file($iCodigo);
      $rsAvcaliacao  = $oDaoAvaliacao->sql_record($sSqlAvaliacao);
      
      if ($oDaoAvaliacao->numrows == 0) {
      	throw new ParameterException(_M(URL_MENSAGEM_RESULTADOCLASSIFICACAO."codigo_nao_encontrado"));
      }

      $this->iCodigo     = $oDadoAvaliacao->ed335_sequencial;
      $this->oDisciplina = DisciplinaRepository::getDisciplinaByCodigo($oDadoAvaliacao->ed335_disciplina);
      $this->sResultado  = $oDadoAvaliacao->ed335_avaliacao;
    }
  }

  /**
   * Retorna a disciplina
   * @return Disciplina
   */
  public function getDisciplina() {
  	
    return $this->oDisciplina;    
  }
  
  /**
   * Define a disciplina avaliada
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina(Disciplina $oDisciplina) {
  	
    $this->oDisciplina = $oDisciplina;  
  }
  
  /**
   * Retorna a avaliacão lançada 
   * @return String
   */
  public function getResultado() {
  	
    return $this->sResultado;
  }
  
  /**
   * Define um resultado para avaliacao
   * @param string $sResultado
   */
  public function setResultado($sResultado) {
  	
    $this->sResultado = $sResultado;
  }
  
  /**
   * Salva a avaliação de uma disciplina
   * @param ClassificacaoAluno $oClassificacaoAluno
   * @throws BusinessException
   * @throws DBException
   * @return boolean  
   */
  public function salvar(ClassificacaoAluno $oClassificacaoAluno) {
  	
    if (!db_utils::inTransaction()) {
    	throw new BusinessException(_M(URL_MENSAGEM_RESULTADOCLASSIFICACAO."sem_transacao_ativa"));
    }
    $oMsgErro = new stdClass();
    
    $oDaoAvaliacao = new cl_avaliacaoclassificacao();
    $oDaoAvaliacao->ed335_sequencial = null;
    $oDaoAvaliacao->ed335_avaliacao  = $this->sResultado;
    $oDaoAvaliacao->ed335_disciplina = $this->oDisciplina->getCodigoDisciplina();
    $oDaoAvaliacao->ed335_trocaserie = $oClassificacaoAluno->getCodigo();
    $oDaoAvaliacao->incluir(null);
    
    if ($oDaoAvaliacao->erro_status == 0) {
    	
      $oMsgErro->disciplina = $this->oDisciplina->getNomeDisciplina();
      $oMsgErro->erro_banco = str_replace('\\n', "\n", $oDaoAvaliacao->erro_sql);
      throw new DBException(_M(URL_MENSAGEM_RESULTADOCLASSIFICACAO."erro_incluir", $oMsgErro));
    }
    return true;
  }
}