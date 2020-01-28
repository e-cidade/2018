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
 * Classe para manipula��o de promo��es
 *
 * @author   Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package  Recursos Humanos
 * @revision $Author: dbrafael.nery $
 * @version  $Revision: 1.4 $
 */

class Promocao {

  /**
   * C�digo da promo��o
   * @var integer
   */
  private $iCodigoPromocao;
  
  /**
   * C�digo da Matr�cula do servidor
   * @var integer
   */
  private $iMatriculaServidor;
  
  /**
   * Data de Inicio da Promo��o 
   * @var date
   */
  private $dtInicioPromocao;
  
  /**
   * Data de Encerramento da promo��o
   * @var date
   */
  private $dtFimPromocao;
  
  /**
   * Observ��es relacionadas a promo��o
   * @var string
   */
  private $sObservacaoPromocao;
  
  /**
   * Situa��o da Promo��o, validando se est� ativa ou n�o
   * @var boolean
   */
  private $lPromocaoAtiva;
  
  /**
   * Maximo de avalia��o que a promocao permite
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
   * @param integer $iCodigoPromocao - C�gigo da Promo��o
   */
  public  function __construct($iCodigoPromocao = null) {

    require_once('classes/db_rhpromocao_classe.php');
    $this->setParametrosRecursosHumanos();

    if ( !is_null($iCodigoPromocao) ) {

      $oDaoRHPromocao            = new cl_rhpromocao();
      $sSqlPromocao              = $oDaoRHPromocao->sql_query_file($iCodigoPromocao);
      $rsPromocao                = db_query($sSqlPromocao);

      if ( !$rsPromocao ||  ( $rsPromocao && pg_num_rows($rsPromocao) == 0 ) ) {
        throw new Exception( "Erro ao Buscar dados da Promo��o \n" . pg_last_error() );
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
   * Define o C�digo da Promo��o
   * @param integer $iCodigoPromocao
   */
  public  function setCodigoPromocao($iCodigoPromocao) {
    $this->iCodigoPromocao = $iCodigoPromocao;
  }
  
  /**
   * Retorna o c�digo da Promo��o
   * @return integer $iCodigoPromocao
   */
  public  function getCodigoPromocao() {
    return $this->iCodigoPromocao;
  }
  
  /**
   * Define o C�digo da Matr�cula do Servidor
   * @param integer $iMatriculaServidor
   */
  public  function setMatriculaServidor($iMatriculaServidor) {
    $this->iMatriculaServidor = $iMatriculaServidor;
  }
  
  /**
   * Retorna o C�digo da Matr�cula do Servidor
   * @return integer
   */
  public  function getMatriculaServidor() {
    return $this->iMatriculaServidor;
  }
  
  /**
  * Define a data de Inicio da Promo��o
  * @param date $dtInicioPromocao
  */
  public  function setDataInicioPromocao($dtInicioPromocao) {
    $this->dtInicioPromocao = $dtInicioPromocao;
  }
  
  /**
   * Retorna a data de Inicio da Promo��o
   * @return integer
   */
  public  function getDataInicioPromocao() {
    return $this->dtInicioPromocao;
  }
  
  /**
   * Define a data de Fim da Promo��o
   * @param date $dtFimPromocao
   */
  public  function setDataFimPromocao($dtFimPromocao) {
    $this->dtFimPromocao = $dtFimPromocao;
  }

  /**
   * Retorna a data de Fim da Promo��o
   * @return integer
   */
  public  function getDataFimPromocao() {
    return $this->dtFimPromocao;
  }

  /**
   * Define observa��es sobre a promo��o
   * @param strign $dtFimPromocao
   */
  public  function setObservacaoPromocao($sObservacao) {
    $this->sObservacaoPromocao = $sObservacao;
  }

  /**
   * Retorna observa��es
   * @return string
   */
  public  function getObservacaoPromocao() {
    return $this->sObservacaoPromocao;
  }

  /**
   * Define promo��o como ativa
   */
  public  function enablePromocao() {
    $this->lPromocaoAtiva = true;
  }

  /**
  * Define promo��o como inativa
  */
  public  function disablePromocao() {
    $this->lPromocaoAtiva = false;
  }

  /**
   * Retorna situa��o da promo��o
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


// M�todos funcionais
  
  /**
   * Salva os Dados da promo��o
   * 
   */
  public  function salvar() {
    
    /**
     * Valida existencia de transa�l�o ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("N�o Existe Transa��o Ativa.");
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
   * Cancela Promo��o
   */
  public  function cancelar() {
    
    if ( !db_utils::inTransaction() ) {
      throw new Exception("N�o Existe Transa��o Ativa.");
    }
    if ( empty( $this->iCodigoPromocao ) ) {
      throw new Exception("C�digo da Promo��o n�o Est� Definido");
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
     * Valida existencia de transa�l�o ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("N�o Existe Transa��o Ativa.");
    }
    /**
     * Valida se o c�digo da Promo��o esta definido
     */
    if ( empty($this->iCodigoPromocao ) ) {
      throw new Exception("C�digo da Promo��o n�o Est� Definido");
    }
    require_once('model/recursosHumanos/AvaliacaoRecursosHumanos.model.php');

  	$iAvaliacao = count($this->getAvaliacoes());
  	
  	if ($iAvaliacao >= $this->iPeriodoPromocao) {
  		throw new Exception("Todas Avali��es para esta Promo��es foram Cadastradas.");
  	}
  	$aAnoLimite    = explode("-", $this->getDataInicioPromocao());
  	$aAnoAvaliacao = explode("-", $dtAvaliacao);
  	$iAnoLimite    = $aAnoLimite[0] + $this->getPeriodoPromocao();
  	$iAnoAvaliacao = $aAnoAvaliacao[0];
  	
  	if ($iAnoAvaliacao > $iAnoLimite) {
  		
  		throw new Exception("Ano da Data da Avalia��o � Maior que o Intersticio da Promo��o.");
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
     * Valida existencia de transa�l�o ativa
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("N�o Existe Transa��o Ativa.");
    }
    /**
     * Valida se o c�digo da Promo��o esta definido
     */
    if ( empty($this->iCodigoPromocao ) ) {
      throw new Exception("C�digo da Promo��o n�o Est� Definido");
    }
    require_once ("classes/db_rhpromocaocurso_classe.php");
    
    $oDaoPromocaoCurso = new cl_rhpromocaocurso();
    $oDaoPromocaoCurso->h74_rhpromocao = $this->getCodigoPromocao();
    $oDaoPromocaoCurso->h74_rhcurso    = $iCodigoCurso;
    $oDaoPromocaoCurso->incluir(null);    
    
    return $oDaoPromocaoCurso;
  }

  /**
   * Retorna Array de instancias das avalia��es da promo��o
   * @return array
   */
  public  function getAvaliacoes() {
    
    if ( empty($this->iCodigoPromocao) ) {
      throw new Exception("C�digo da Promo��o n�o Est� Definido");
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
    * Valida existencia de transa�l�o ativa
    */
  	if ( !db_utils::inTransaction() ) {
  		throw new Exception("Remover Avalia��o: N�o Existe Transa��o Ativa.");
  	}
  	/**
  	 * Valida se o c�digo da Promo��o esta definido
  	 */
  	if ( empty($this->iCodigoPromocao ) ) {
  		throw new Exception("C�digo da Promo��o n�o Est� Definido");
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
   * Metodo para definirmos parametros para promo��o
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
   * Fechamento da promo��o
   * @param integer $iTotalPontos
   * @param array   $aAssentamentos
   * @return        boolean
   */
  public function fechamentoPromocao($iTotalPontos, $aAssentamentos){

    require_once("model/recursosHumanos/PromocaoFechamento.model.php");
    
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Fechamento Promo��o: \nN�o Existe Transa��o Ativa.");
    }
    if ( !$this->isAtiva() || empty( $this->dtFimPromocao ) ) {
    	throw new Exception("Fechamento Promo��o: \nPromo��o Fechada ou Excluida");
    } 
    
    $iTotalAvaliacoes = count( $this->getAvaliacoes() );
     
    if ( $iTotalAvaliacoes < $this->getPeriodoPromocao() ) {
    	throw new Exception("Fechamento Promo��o: \nPromo��o n�o possui quantidade minima de avalia��es.");
    }
     
    $oDaoRHPromocao                   = new cl_rhpromocao();
    
    $oDaoRHPromocao->h72_sequencial   = $this->getCodigoPromocao();
    $oDaoRHPromocao->h72_dtfinal      = $this->getDataFimPromocao();
    $oDaoRHPromocao->h72_observacao   = $this->getObservacaoPromocao();
    $oDaoRHPromocao->h72_ativo        = 'false';
    $oDaoRHPromocao->alterar($this->iCodigoPromocao);

    if ( (int)$oDaoRHPromocao->erro_status == 0 ) {
      throw new Exception("Fechamento Promo��o: \n".$oDaoRHPromocao->erro_msg);
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