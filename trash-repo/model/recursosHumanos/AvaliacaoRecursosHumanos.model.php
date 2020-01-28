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
 * Classe para manipulação de avaliações da Promoção
 *
 * @author   Rafael Serpa Nery rafael.nery@dbseller.com.br
 * @package  Recursos Humanos
 * @revision $Author: dbrafael.lopes $
 * @version  $Revision: 1.1 $
 */
class AvaliacaoRecursosHumanos {

  private $iCodigoPromocao;

  private $iCodigoAvaliacao;

  /**
   * Data da inclusão da avaliação
   * @var string_date
   */
  private $dInclusao;

  /**
   * Codigo do usuario que incluiu a avaliação
   * @var integer
   */
  private $iUsuario;

  /**
   * Data de registro da avaliacao
   * @var string_date
   */
  private $dDataAvaliacao;

  /**
   * Observações relacionadas a avaliacao
   * @var unknown_type
   */
  private $sObservacao;

  /**
   * Funcao Construtora da classe
   * @param integer $iCodigoPromocao
   * @param integer $iCodigoAvaliacao
   */


  public function __construct($iCodigoPromocao, $iCodigoAvaliacao = null) {

     
    $this->iCodigoPromocao = $iCodigoPromocao;
    $this->dInclusao       = date("Y-m-d", db_getsession("DB_datausu"));
    $this->iUsuario        = db_getsession('DB_id_usuario');
     
    /**
     * Caso não esteja vazio, busca o codigo da avaliacao no banco e persisste os dados da classe
     */
    if (!empty($iCodigoAvaliacao)) {

      require_once ("classes/db_rhavaliacao_classe.php");

      $oDaoRHAvaliacao    = new cl_rhavaliacao();
      $sSqlDadosAvaliacao = $oDaoRHAvaliacao->sql_query_file($iCodigoAvaliacao);
      $rsDadosAvaliacao   = db_query($sSqlDadosAvaliacao);

      if ( !$rsDadosAvaliacao ||  ( $rsDadosAvaliacao && pg_num_rows($rsDadosAvaliacao) == 0 ) ) {
        throw new Exception( "AvaliacaoRecursosHumanos: Erro ao Buscar dados da Avaliacao \n" . pg_last_error() );
      }

      $oAvaliacao            = db_utils::fieldsMemory($rsDadosAvaliacao, 0);
      $this->iCodigoAvaliacao= $oAvaliacao->h73_sequencial;
      
      $this->dInclusao       = $oAvaliacao->h73_dtinclusao;
      $this->dDataAvaliacao  = $oAvaliacao->h73_dtavaliacao;
      $this->sObservacao     = $oAvaliacao->h73_observacao;
      $this->iUsuario        = $oAvaliacao->h73_usuario;
    }
     

  }

  public function setCodigoAvaliacao($iCodigoAvaliacao){
    $this->iCodigoAvaliacao = $iCodigoAvaliacao;
  }
  public function getCodigoAvaliacao(){
    return $this->iCodigoAvaliacao;
  }

  public function setUsuario($iUsuario){
    $this->iUsuario = $iUsuario;
  }

  public function getUsuario(){
    return $this->iUsuario;
  }

  public function setDataAvaliacao($dDataAvaliacao){
     
    require_once ("classes/db_rhavaliacao_classe.php");
    $oDaoRHAvaliacao   = new cl_rhavaliacao();
    $aAnoAvaliacao     = explode("-", $dDataAvaliacao);
    $iAnoAvaliacao     = $aAnoAvaliacao[0];
    $sWhere            = "h73_rhpromocao = {$this->iCodigoPromocao} and extract(year from h73_dtavaliacao) = {$iAnoAvaliacao} ";
    $sSqlDataAvaliacao = $oDaoRHAvaliacao->sql_query(null, "*", null, $sWhere);
    $rsDataAvaliacao   = $oDaoRHAvaliacao->sql_record($sSqlDataAvaliacao);
     
    if ($oDaoRHAvaliacao->numrows > 0) {

      throw new Exception("Definir data da Avaliação: Já Existe Avaliação Para este Ano");
    }
    $this->dDataAvaliacao = $dDataAvaliacao;
  }

  public function getDataAvaliacao(){
    return $this->dDataAvaliacao;
  }

  public function setObservacao($sObservacao){
    $this->sObservacao = $sObservacao;
  }
  public function getObservacao(){
    return $this->sObservacao;
  }

  /**
   * Salva os Dados da avaliacao
   */
  public function salvar(){
     
    /**
     * Valida se existe transação no banco de dados
     */
    if (!db_utils::inTransaction()) {
      throw new Exception("Salvar dados: Não Existe transação ativa.");
    }
     
    require_once("classes/db_rhavaliacao_classe.php");

    $oRhavaliacao                  = new cl_rhavaliacao();
    $oRhavaliacao->h73_rhpromocao  = $this->iCodigoPromocao;
    $oRhavaliacao->h73_dtavaliacao = $this->getDataAvaliacao();
    $oRhavaliacao->h73_dtinclusao  = $this->dInclusao;
    $oRhavaliacao->h73_usuario     = $this->iUsuario;
    $oRhavaliacao->h73_observacao  = $this->getObservacao();

    $oRhavaliacao->incluir(null);
    if ($oRhavaliacao->erro_status == 0) {

      throw new Exception("Salvar dados: ".$oRhavaliacao->erro_msg);
    }
    $this->iCodigoAvaliacao = $oRhavaliacao->h73_sequencial;
     
  }

  
  /**
   * Liga Tipo de Avaliacao a Avaliacao, onde uma avaliacao pode ter varios tipos de avaliacao
   * @param integer $iTipoAvaliacao
   * @param integer $iPontuacao
   * @return cl_rhavaliacaotipoavaliacao
   */
  public function adicionarTipoAvaliacao($iTipoAvaliacao, $iPontuacao) {
     
    /**
     * Valida se existe transação no banco de dados
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Adicionar Tipos de Avaliação: Não Existe transação ativa.");
    }

    require_once("classes/db_rhavaliacaotipoavaliacao_classe.php");
     
    $oRhavaliacaotipoavaliacao = new cl_rhavaliacaotipoavaliacao();
    $oRhavaliacaotipoavaliacao->h76_rhtipoavaliacao = $iTipoAvaliacao;
    $oRhavaliacaotipoavaliacao->h76_rhavaliacao     = $this->iCodigoAvaliacao;
    $oRhavaliacaotipoavaliacao->h76_pontos          = $iPontuacao;
    $oRhavaliacaotipoavaliacao->incluir(null);
    
    if ($oRhavaliacaotipoavaliacao->erro_status == 0) {
      throw new Exception("Adicionar Tipos de Avaliação: \n".$oRhavaliacaotipoavaliacao->erro_msg);
    }
    return $oRhavaliacaotipoavaliacao;
  }

  /**
   * liga um curso a avaliacao
   * @param integer $iCurso
   * return cl_rhpromocaocursosavaliacao
   */
  public function adicionarCurso(cl_rhpromocaocurso $oPromocaoCurso) {
    /**
     * Valida se existe transação no banco de dados
     */
    if ( !db_utils::inTransaction() ) {
      throw new Exception("Adicionar Curso: Não Existe transação ativa.");
    }
    
    require_once ("classes/db_rhpromocaocursosavaliacao_classe.php");
    
    $oDaoRHPromocaoCursosAvaliacao = new cl_rhpromocaocursosavaliacao();
    $oDaoRHPromocaoCursosAvaliacao->h75_rhpromocaocurso = $oPromocaoCurso->h74_sequencial;
    $oDaoRHPromocaoCursosAvaliacao->h75_rhavaliacao     = $this->getCodigoAvaliacao();
    $oDaoRHPromocaoCursosAvaliacao->incluir(null);
    
    if ($oDaoRHPromocaoCursosAvaliacao->erro_status == 0) {
      throw new Exception("Adicionar Curso: {$oDaoRHPromocaoCursosAvaliacao->erro_msg}");
    }
    return $oDaoRHPromocaoCursosAvaliacao;
  }
  
  
  
  /**
   * metodo para remover avaliacoes
   */
  public function remover() {
  	
  	require_once("classes/db_rhavaliacao_classe.php");
    $oDaoRhavaliacao = new cl_rhavaliacao();
    
    $aCursosExcluidos = $this->removerCursoAvaliacao();
    $this->removerTipoAvaliacao();
    
    $oDaoRhavaliacao->excluir(null, "h73_sequencial = {$this->iCodigoAvaliacao}");
    if ($oDaoRhavaliacao->erro_status == 0) {
      throw new Exception("Remover Avaliacao: " . $oDaoRhavaliacao->erro_msg);
    } 	
    
    $oRetornoExclusao = new stdClass();
    $oRetornoExclusao->aCursosExcluidos = $aCursosExcluidos;
    
  	return $oRetornoExclusao;
  }
  
  
  /**
   * Metodo que remove registros da tabela rhpromocaocursosavaliacao para podermos excluir a avaliacao
   *
   * @return unknown
   */
  public function removerCursoAvaliacao() {
  	
  	require_once("classes/db_rhpromocaocursosavaliacao_classe.php");
  	$oDaoRhpromocaocursosavaliacao = new cl_rhpromocaocursosavaliacao;
  	
  	$sSqlCursos  = $oDaoRhpromocaocursosavaliacao->sql_query_file(null, 
  	                                                              "h75_rhpromocaocurso", 
  	                                                              null, 
  	                                                              "h75_rhavaliacao = {$this->iCodigoAvaliacao}");
  	$rsCursos    = $oDaoRhpromocaocursosavaliacao->sql_record($sSqlCursos);
  	
  	$aRetorno    = array();

  	$aCursos     = db_utils::getColectionByRecord($rsCursos);
  	
  	foreach ($aCursos as $oCursos) {
  		$aRetorno[] = $oCursos->h75_rhpromocaocurso;
  	}
  	
	  $oDaoRhpromocaocursosavaliacao->excluir(null, "h75_rhavaliacao = {$this->iCodigoAvaliacao}");
	  if ($oDaoRhpromocaocursosavaliacao->erro_status == 0) {
	    throw new Exception("Remover Curso Avaliação: " . $oDaoRhpromocaocursosavaliacao->erro_msg);
	  }

  	return $aRetorno;
  }
  
  /**
   * Metodo que remove registros da rhavaliacaotipoavaliacao para excluir avaliacao
   *
   * @return true
   */
  public function removerTipoAvaliacao() {
  	
  	require_once("classes/db_rhavaliacaotipoavaliacao_classe.php");
  	$oDaoRhAvaliacaotipoavaliacao  = new cl_rhavaliacaotipoavaliacao;
  	
    $oDaoRhAvaliacaotipoavaliacao->excluir(null, "h76_rhavaliacao = {$this->iCodigoAvaliacao}");
    if ($oDaoRhAvaliacaotipoavaliacao->erro_status == 0) {
      throw new Exception("Remover Tipo Avaliacao: " . $oDaoRhAvaliacaotipoavaliacao->erro_msg);
    }  	
    
    return true;
  }
  
  
  
}