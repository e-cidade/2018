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
class Avaliacao {

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

		 
		$this->dInclusao       = date("Y-m-d", db_getsession("DB_datausu"));
		$this->iUsuario        = db_getsession('DB_id_usuario');
		$this->iCodigoPromocao = $iCodigoPromocao;
		 
		/**
		 * Caso não esteja vazio, busca o codigo da avaliacao no banco e persisste os dados da classe
		 */
		if (!empty($iCodigoAvaliacao)) {
			 
			require_once ("classes/db_rhavaliacao_classe.php");
				
			$oDaoRHAvaliacao    = new cl_rhavaliacao();
			$sSqlDadosAvaliacao = $oDaoRHAvaliacao->sql_query_file($iCodigoAvaliacao);
			$rsDadosAvaliacao   = db_query($sSqlDadosAvaliacao);
				
			if ( !$rsDadosAvaliacao ||  ( $rsDadosAvaliacao && pg_num_rows($rsDadosAvaliacao) == 0 ) ) {
				throw new Exception( "Erro ao Buscar dados da Avaliacao \n" . pg_last_error() );
			}

			$oAvaliacao            = db_utils::fieldsMemory($rsDadosAvaliacao, 0);
			$this->dInclusao       = $oAvaliacao->h72_sequencial;
			$this->dDataAvaliacao  = $oAvaliacao->h72_regist;
			$this->sObservacao     = $oAvaliacao->h72_dtinicial;
			$this->iUsuario        = $oAvaliacao->h72_dtfinal;
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
  	//if ($oDaoRHAvaliacao->numrows > 0) {
  		
  		//throw new ErrorException("Já Existe Avaliação Para este Ano");
  	//}
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


  public function salvarAvaliacao(){
  	
  	if (!db_utils::inTransaction()) {
  		throw new Exception("Não Existe transação ativa.");
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
        
      throw new ErrorException($oRhavaliacao->erro_msg);
    }
    $this->iCodigoAvaliacao = $oRhavaliacao->h73_sequencial; 	
  	
  }

  public function salvarTipoAvaliacao($iTipoAvaliacao, $iPontuacao) {
  	
  	require_once("classes/db_rhavaliacaotipoavaliacao_classe.php");
  	$oRhavaliacaotipoavaliacao = new cl_rhavaliacaotipoavaliacao();
  	$oRhavaliacaotipoavaliacao->h76_rhtipoavaliacao = $iTipoAvaliacao;
  	$oRhavaliacaotipoavaliacao->h76_rhavaliacao     = $this->iCodigoAvaliacao;
  	$oRhavaliacaotipoavaliacao->h76_pontos          = $iPontuacao;
  	$oRhavaliacaotipoavaliacao->incluir(null);
  	if ($oRhavaliacaotipoavaliacao->erro_status == 0) {
  		throw new ErrorException($oRhavaliacaotipoavaliacao->erro_msg);
  	}
  }
  
  public function salvarCurso($iCurso){
  	
  	/*
  	 * 1° curso, promocao.                rhpromocaocurso    
  	 * 2º promocaocurso do 1º, avaliacao  rhpromocaocursosavaliacao 
  	 */
  	require_once("classes/db_rhpromocaocurso_classe.php");
  	require_once("classes/db_rhpromocaocursosavaliacao_classe.php");
  	$oDaoRhpromocaocurso           = new cl_rhpromocaocurso();
  	$oDaoRhpromocaocursosavaliacao = new cl_rhpromocaocursosavaliacao(); 
  	
  	$oDaoRhpromocaocurso->h74_rhcurso    = $iCurso;
  	$oDaoRhpromocaocurso->h74_rhpromocao = $this->iCodigoPromocao;
  	$oDaoRhpromocaocurso->incluir(null);
  	if ($oDaoRhpromocaocurso->erro_status == 0) {
  		throw new ErrorException($oDaoRhpromocaocurso->erro_msg);
  	}
  	
  	$oDaoRhpromocaocursosavaliacao->h75_rhavaliacao     = $this->iCodigoAvaliacao;
  	$oDaoRhpromocaocursosavaliacao->h75_rhpromocaocurso = $oDaoRhpromocaocurso->h74_sequencial;
  	$oDaoRhpromocaocursosavaliacao->incluir(null);
    if ($oDaoRhpromocaocursosavaliacao->erro_status == 0) {
      throw new ErrorException($oDaoRhpromocaocursosavaliacao->erro_msg);
    }  	
  }
  


}