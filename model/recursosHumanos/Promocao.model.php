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
 * Classe para manipulação de promoções
 *
 * @author   Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package  Recursos Humanos
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.4 $
 */

class Promocao {

  /**
   * Código da promoção
   * @var integer
   */
  private $iCodigoPromocao;
  
  /**
   * Código da Matrícula do servidor
   * @var integer
   */
  private $iMatriculaServidor;
  
  /**
   * Data de Inicio da Promoção 
   * @var date
   */
  private $dtInicioPromocao;
  
  /**
   * Data de Encerramento da promoção
   * @var date
   */
  private $dtFimPromocao;
  
  /**
   * Observções relacionadas a promoção
   * @var string
   */
  private $sObservacaoPromocao;
  
  /**
   * Situação da Promoção, validando se está ativa ou não
   * @var boolean
   */
  private $lPromocaoAtiva;
  
  /**
   * Maximo de avaliação que a promocao permite
   *
   * @var integer
   */
  private $iPeriodoPromocao;
  
  /**
   * Minimo de Pontos para atingir a meta da promocao
   *
   * @var integer
   */
  private $iPontuacaoMinima;
  
  /**
   * Construtor da Classe
   * @param integer $iCodigoPromocao - Cógigo da Promoção
   */
  public  function __construct($iCodigoPromocao = null) {

    require_once('classes/db_rhpromocao_classe.php');
    $this->setParametrosRecursosHumanos();

    if ( !is_null($iCodigoPromocao) ) {

      $oDaoRHPromocao            = new cl_rhpromocao();
      $sSqlPromocao              = $oDaoRHPromocao->sql_query_file($iCodigoPromocao);
      $rsPromocao                = db_query($sSqlPromocao);

      if ( !$rsPromocao ||  ( $rsPromocao && pg_num_rows($rsPromocao) == 0 ) ) {
        throw new Exception( "Erro ao Buscar dados da Promoção \n" . pg_last_error() );
      }

      $oPromocao                 = db_utils::fieldsMemory($rsPromocao, 0);
      $this->iCodigoPromocao     = $oPromocao->h72_sequencial;
      $this->iMatriculaServidor  = $oPromocao->h72_regist;
      $this->dtInicioPromocao    = $oPromocao->h72_dtinicial;
      $this->dtFimPromocao       = $oPromocao->h72_dtfinal;
      $this->sObservacaoPromocao = $oPromocao->h72_observacao;
      $this->lPromocaoAtiva      = $oPromocao->h72_ativo == "t" ? true : false;
    }
    
  }
  
  
  //Setters e Getters

  /**
   * Define o Código da Promoção
   * @param integer $iCodigoPromocao
   */
  public  function setCodigoPromocao($iCodigoPromocao) {
    $this->iCodigoPromocao = $iCodigoPromocao;
  }
  
  /**
   * Retorna o código da Promoção
   * @return integer $iCodigoPromocao
   */
  public  function getCodigoPromocao() {
    return $this->iCodigoPromocao;
  }
  
  /**
   * Define o Código da Matrícula do Servidor
   * @param integer $iMatriculaServidor
   */
  public  function setMatriculaServidor($iMatriculaServidor) {
    $this->iMatriculaServidor = $iMatriculaServidor;
  }
  
  /**
   * Retorna o Código da Matrícula do Servidor
   * @return integer
   */
  public  function getMatriculaServidor() {
    return $this->iMatriculaServidor;
  }
  
  /**
  * Define a data de Inicio da Promoção
  * @param date $dtInicioPromocao
  */
  public  function setDataInicioPromocao($dtInicioPromocao) {
    $this->dtInicioPromocao = $dtInicioPromocao;
  }
  
  /**
   * Retorna a data de Inicio da Promoção
   * @return integer
   */
  public  function getDataInicioPromocao() {
    return $this->dtInicioPromocao;
  }
  
  /**
   * Define a data de Fim da Promoção
   * @param date $dtFimPromocao
   */
  public  function setDataFimPromocao($dtFimPromocao) {
    $this->dtFimPromocao = $dtFimPromocao;
  }

  /**
   * Retorna a data de Fim da Promoção
   * @return integer
   */
  public  function getDataFimPromocao() {
    return $this->dtFimPromocao;
  }

  /**
   * Define observações sobre a promoção
   * @param strign $dtFimPromocao
   */
  public  function setObservacaoPromocao($sObservacao) {
    $this->sObservacaoPromocao = $sObservacao;
  }

  /**
   * Retorna observações
   * @return string
   */
  public  function getObservacaoPromocao() {
    return $this->sObservacaoPromocao;
  }

  /**
   * Define promoção como ativa
   */
  public  function enablePromocao() {
    $this->lPromocaoAtiva = true;
  }

  /**
  * Define promoção como inativa
  */
  public  function disablePromocao() {
    $this->lPromocaoAtiva = false;
  }

  /**
   * Retorna situação da promoção
   * @return boolean
   */
  public  function isAtiva() {
    return $this->lPromocaoAtiva;
  }
  
  /**
   * Retornamos o maximo de avaliacao por promocao
   *
   * @return integer
   */
  public function getPeriodoPromocao() {
  	return $this->iPeriodoPromocao;
  }
  
  /**
   * retornamos o minimo de pontuacao necessaria
   *
   * @return integer
   */
  public function getPontuacaoMinima(){
    return $this->iPontuacaoMinima;  	
  }


// Métodos funcionais
  
  /**
   * Salva os Dados da promoção
   * 
   */
  public  function salvar() {
    
    /**
     * Valida existencia de transaçlão ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Não Existe Transação Ativa.");
    }
    
    $oDaoRHPromocao                   = new cl_rhpromocao();
    $oDaoRHPromocao->h72_regist     	= $this->getMatriculaServidor();
    $oDaoRHPromocao->h72_dtinicial    = $this->getDataInicioPromocao();
    $oDaoRHPromocao->h72_ativo        = $this->isAtiva() ? "t" : "f";
    $oDaoRHPromocao->incluir(null);
    
    if ( (int)$oDaoRHPromocao->erro_status == 0 ) {
      throw new Exception($oDaoRHPromocao->erro_msg);
    }
    $this->setCodigoPromocao($oDaoRHPromocao->h72_sequencial);
    return true;
  }
  
  /**
   * Cancela Promoção
   */
  public  function cancelar() {
    
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Não Existe Transação Ativa.");
    }
    if ( empty( $this->iCodigoPromocao ) ) {
      throw new Exception("Código da Promoção não Está Definido");
    }
    
    $oDaoRHPromocao                     = new cl_rhpromocao();
    $oDaoRHPromocao->h72_sequencial     = $this->getCodigoPromocao();
    $oDaoRHPromocao->h72_ativo          = 'false';
    $oDaoRHPromocao->alterar($this->getCodigoPromocao());
     
    if ($oDaoRHPromocao->erro_status == '0') {
      throw new Exception( $oDaoRHPromocao->erro_msg );
    }
    return true;
  }
  
  /**
   * Retorna nova instancia da avaliacao
   * @return AvaliacaoRecursosHumanos
   */
  public  function adicionarAvaliacao($dtAvaliacao, $sObservacao) {
    
     
    /**
     * Valida existencia de transaçlão ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Não Existe Transação Ativa.");
    }
    /**
     * Valida se o código da Promoção esta definido
     */
    if ( empty($this->iCodigoPromocao ) ) {
      throw new Exception("Código da Promoção não Está Definido");
    }
    require_once('model/recursosHumanos/AvaliacaoRecursosHumanos.model.php');

  	$iAvaliacao = count($this->getAvaliacoes());
  	
  	if ($iAvaliacao >= $this->iPeriodoPromocao) {
  		throw new Exception("Todas Avalições para esta Promoções foram Cadastradas.");
  	}
  	$aAnoLimite    = explode("-", $this->getDataInicioPromocao());
  	$aAnoAvaliacao = explode("-", $dtAvaliacao);
  	$iAnoLimite    = $aAnoLimite[0] + $this->getPeriodoPromocao();
  	$iAnoAvaliacao = $aAnoAvaliacao[0];
  	
  	if ($iAnoAvaliacao > $iAnoLimite) {
  		
  		throw new Exception("Ano da Data da Avaliação é Maior que o Intersticio da Promoção.");
  	}
  	
    $oAvaliacao = new AvaliacaoRecursosHumanos( $this->getCodigoPromocao() );
    $oAvaliacao->setDataAvaliacao($dtAvaliacao);
    $oAvaliacao->setObservacao($sObservacao);
    $oAvaliacao->salvar();
    return $oAvaliacao;
  }
  
  /**
   * Adiciona Curso a Promocao  
   * @param integer $iCodigoCurso
   * @return cl_rhpromocaocurso
   */
  public  function adicionarCurso($iCodigoCurso) {

    /**
     * Valida existencia de transaçlão ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Não Existe Transação Ativa.");
    }
    /**
     * Valida se o código da Promoção esta definido
     */
    if ( empty($this->iCodigoPromocao ) ) {
      throw new Exception("Código da Promoção não Está Definido");
    }
    require_once ("classes/db_rhpromocaocurso_classe.php");
    
    $oDaoPromocaoCurso = new cl_rhpromocaocurso();
    $oDaoPromocaoCurso->h74_rhpromocao = $this->getCodigoPromocao();
    $oDaoPromocaoCurso->h74_rhcurso    = $iCodigoCurso;
    $oDaoPromocaoCurso->incluir(null);    
    
    return $oDaoPromocaoCurso;
  }

  /**
   * Retorna Array de instancias das avaliações da promoção
   * @return array
   */
  public  function getAvaliacoes() {
    
    if ( empty($this->iCodigoPromocao) ) {
      throw new Exception("Código da Promoção não Está Definido");
    }
    
    require_once ("classes/db_rhavaliacao_classe.php");
    require_once ("model/recursosHumanos/AvaliacaoRecursosHumanos.model.php");
    
    $oDaoRHAvaliacao = new cl_rhavaliacao();
    $sSqlAvaliacoes  = $oDaoRHAvaliacao->sql_query_file(null, "h73_sequencial", null," h73_rhpromocao = {$this->getCodigoPromocao()}");
    $rsAvaliacoes    = $oDaoRHAvaliacao->sql_record($sSqlAvaliacoes);
    
    if ( (int)$oDaoRHAvaliacao->erro_status == 0 && !empty($oDaoRHAvaliacao->erro_banco) ) {
      throw new Exception ($oDaoRHAvaliacao->erro_msg);
    }
    
    $aAvaliacoes = array();
    
    if ( $oDaoRHAvaliacao->numrows > 0) {
      
      foreach ( db_utils::getCollectionByRecord($rsAvaliacoes, false, false, true) as $oAvaliacoes ) {
        $aAvaliacoes[] = new AvaliacaoRecursosHumanos($this->getCodigoPromocao(), $oAvaliacoes->h73_sequencial);
      }
    }
    return $aAvaliacoes;
  }
  
  public function removerAvaliacao($iAvaliacoesCancelar) {
  	 
   /**
    * Valida existencia de transaçlão ativa
    */
  	if ( !db_utils::inTransaction() ) {
  		throw new Exception("Remover Avaliação: Não Existe Transação Ativa.");
  	}
  	/**
  	 * Valida se o código da Promoção esta definido
  	 */
  	if ( empty($this->iCodigoPromocao ) ) {
  		throw new Exception("Código da Promoção não Está Definido");
  	}
  	require_once('model/recursosHumanos/AvaliacaoRecursosHumanos.model.php');

  	$oAvaliacao         = new AvaliacaoRecursosHumanos( $this->getCodigoPromocao(), $iAvaliacoesCancelar );
  	$oAvaliacaoExclusao = $oAvaliacao->remover();
  	if (count($oAvaliacaoExclusao->aCursosExcluidos) > 0) {
  	  $this->removerPromocaoCurso($oAvaliacaoExclusao->aCursosExcluidos);
  	}
  	
  }
  
  public function removerPromocaoCurso($aRhPromocaoCurso){
  	
	  	$sListaExclusao = implode(", ", $aRhPromocaoCurso);
	  	require_once("classes/db_rhpromocaocurso_classe.php");
	  	$oDaoRhpromocaocurso = new cl_rhpromocaocurso();
	  	$oDaoRhpromocaocurso->excluir(null, "h74_sequencial in  ({$sListaExclusao})");
	  	if ($oDaoRhpromocaocurso->erro_status == 0) {
	      throw new Exception("Remover Promocao Curso: " . $oDaoRhpromocaocurso->erro_msg);		
	  	}
	  	
	  	return true;
  	
  }
  
  /**
   * Metodo para definirmos parametros para promoção
   * maxima de avaliacoes
   * pontuacao minima para atingir as metas
   */
  public function setParametrosRecursosHumanos(){
  	
  	require_once("classes/db_rhparam_classe.php");
  	$oDaoRhParam = new cl_rhparam();
  	$sSqlParametros = $oDaoRhParam->sql_query_file(null, "h36_intersticio, h36_pontuacaominpromocao", null, null);
  	$rsParametros   = $oDaoRhParam->sql_record($sSqlParametros);
  	$oParametros    = db_utils::fieldsMemory($rsParametros, 0);
  	
  	$this->iPeriodoPromocao = $oParametros->h36_intersticio;
  	$this->iPontuacaoMinima = $oParametros->h36_pontuacaominpromocao;

  }

  /**
   * Fechamento da promoção
   * @param integer $iTotalPontos
   * @param array   $aAssentamentos
   * @return        boolean
   */
  public function fechamentoPromocao($iTotalPontos, $aAssentamentos){

    require_once("model/recursosHumanos/PromocaoFechamento.model.php");
    
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Fechamento Promoção: \nNão Existe Transação Ativa.");
    }
    if ( !$this->isAtiva() || empty( $this->dtFimPromocao ) ) {
    	throw new Exception("Fechamento Promoção: \nPromoção Fechada ou Excluida");
    } 
    
    $iTotalAvaliacoes = count( $this->getAvaliacoes() );
     
    if ( $iTotalAvaliacoes < $this->getPeriodoPromocao() ) {
    	throw new Exception("Fechamento Promoção: \nPromoção não possui quantidade minima de avaliações.");
    }
     
    $oDaoRHPromocao                   = new cl_rhpromocao();
    
    $oDaoRHPromocao->h72_sequencial   = $this->getCodigoPromocao();
    $oDaoRHPromocao->h72_dtfinal      = $this->getDataFimPromocao();
    $oDaoRHPromocao->h72_observacao   = $this->getObservacaoPromocao();
    $oDaoRHPromocao->h72_ativo        = 'false';
    $oDaoRHPromocao->alterar($this->iCodigoPromocao);

    if ( (int)$oDaoRHPromocao->erro_status == 0 ) {
      throw new Exception("Fechamento Promoção: \n".$oDaoRHPromocao->erro_msg);
    }

    $oFechamentoPromocao = new PromocaoFechamento();
    $oFechamentoPromocao->setCodigoPromocao( $this->getCodigoPromocao() );
    $oFechamentoPromocao->setPontuacao( $iTotalPontos );
    $oFechamentoPromocao->salvar();
    
    foreach ( $aAssentamentos as $iCodigoAssentamento ) {
      $oFechamentoPromocao->adicionarAssentamento($iCodigoAssentamento);
    }
    
    return $oFechamentoPromocao;
  }
}