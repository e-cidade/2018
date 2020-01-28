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

require_once 'model/ambulatorial/ICompetenciaSaude.interface.php';

/**
 * Atendimentos por Competencia do ambulatorio
 * @package ambulatorial
 * @author  Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.3 $
 */
class CompetenciaAtendimento implements ICompetenciaSaude {

  /**
   * C�digo interno do fechamento
   * @var integer
   */
  private $iCodigo;

  /**
   * Hora do fechamento (H:i) 
   * @var string
   */
  private $sHora;
  
  /**
   * Descri��o informada ao realizar fechamento 
   * @var string
   */
  private $sDescricao;
  
  /**
   * Tipo do fechamento
   * @var string
   */
  private $sTipo;
  
  /**
   * Usu�rio que realizou procedimento
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;
  
  /**
   * Dia que foi realizado procedimento
   * @var DBDate
   */
  private $oDataInclusao;
  
  /**
   * Per�odo inicial 
   * @var DBDate
   */
  private $oPeriodoInicial;
  
  /**
   * Per�odo Final
   * @var DBDate
   */
  private $oPeriodoFinal;
  
  /**
   * Compet�ncia
   * @var DBCompetencia
   */
  private $oCompetencia;
  
  /**
   * Financiamento 
   * @var FinanciamentoSaude
   */
  private $oFinanciamentoSaude;
  
  /**
   * Procedimentos realizado para $oCompetencia
   * @var array
   */
  private $aProcedimentos = array();

  /**
   * Cole��o de filtros que ser�o usados no where do sql que buscar� os procedimentos
   * @var array
   */
  private $aFiltrosProcedimentos = array(); 
  
  /**
   * Construtor
   * @param integer $iCodigo
   */
  public function __construct($iCodigo) {
  	
    if (!empty($iCodigo)) {
    	
      $oDaoFechamento = new cl_sau_fechamento();
      $sSqlFechamento = $oDaoFechamento->sql_query_file($iCodigo);
      $rsFechamento   = $oDaoFechamento->sql_record($sSqlFechamento);
      $iLinhas        = $oDaoFechamento->numrows;
      
      if ($iLinhas == 0) {
        throw new ParameterException(_M("saude.ambulatorial.CompetenciaAtendimento.fechamento_competencia_nao_encontrado"));
      }
      
      $oDadosFechamento = db_utils::fieldsMemory($rsFechamento, 0);
      
      $this->iCodigo             = $oDadosFechamento->sd97_i_codigo;
      $this->sHora               = $oDadosFechamento->sd97_c_hora;
      $this->sDescricao          = $oDadosFechamento->sd97_c_descricao;
      $this->sTipo               = $oDadosFechamento->sd97_c_tipo;
      $this->oUsuarioSistema     = new UsuarioSistema($oDadosFechamento->sd97_i_login);
      $this->oDataInclusao       = new DBDate($oDadosFechamento->sd97_d_dataini);
      $this->oPeriodoInicial     = new DBDate($oDadosFechamento->sd97_d_data);
      $this->oPeriodoFinal       = new DBDate($oDadosFechamento->sd97_d_datafim);
      $this->oCompetencia        = new DBCompetencia($oDadosFechamento->sd97_i_compano, $oDadosFechamento->sd97_i_compmes);
      $this->oFinanciamentoSaude = FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($oDadosFechamento->sd97_i_financiamento);
      
    }
    
  }
  
  /**
   * Retorna o c�digo do fechamento
   * @return number
   */
  public function getCodigo() {
    
  	return $this->iCodigo;
  }
  
  /**
   * Retorna a hora de processamento
   * @return string
   */
  public function getHora() {
    
    return $this->sHora;
  }
  
  /**
   * Retorna a descri��o
   * @return string
   */
  public function getDescricao() {
    
    return $this->sDescricao;
  }
  
  /**
   * Retorna o tipo
   * @return string
   */
  public function getTipo() {
    
    return $this->sTipo;
  }
  
  /**
   * Retorna o usu�rio que realizou o cadastro
   * @return UsuarioSistema
   */
  public function getUsuario() {
    
    return $this->oUsuarioSistema;
  }
  
  /**
   * Retorna a data de processamento
   * @return DBDate
   */
  public function getDataInclusao() {
    
    return $this->oDataInclusao;
  }
  
  /**
   * Retorna per�odo inicial
   * @return DBDate
   */
  public function getPeriodoInicial() {
    
    return $this->oPeriodoInicial;
  }
  
  /**
   * Retorna per�odo final
   * @return DBDate
   */
  public function getPeriodoFinal() {
    
    return $this->oPeriodoFinal;
  }
  
  /**
   * Retorna uma instancia de Compet�ncia
   * @return DBCompetencia
   */
  public function getCompetencia() {
  
    return $this->oCompetencia;
  }
  
  /**
   * 
   * @return FinanciamentoSaude
   */
  public function getFinanciamentoSaude() {
    
    return $this->oFinanciamentoSaude;
  }
  

  /**
   * Retorna os procedimentos da compet�ncia
   * @see ICompetenciaSaude::getProcedimentos()
   * @return stdClass com os procedimentos do fechamento
   */
  public function getProcedimentos() {
  	
    $sWhere = "sau_fechapront.sd98_i_fechamento = {$this->getCodigo()}";
    if (count($this->aFiltrosProcedimentos) > 0) {
      $sWhere .= " and " . implode(" and ", $this->aFiltrosProcedimentos);
    }
    
    $oDaoFechaArquivo  = new cl_sau_fecharquivo();
    $sSqlProcedimentos = $oDaoFechaArquivo->sql_query_programas($sWhere);
    $rsProcedimentos   = $oDaoFechaArquivo->sql_record($sSqlProcedimentos);
    $iLinhas           = $oDaoFechaArquivo->numrows;
    if ($iLinhas == 0) {
      throw new BusinessException(_M("saude.ambulatorial.CompetenciaAtendimento.nenhum_procedimento_encontrado"));      
    }
    
    $this->aProcedimentos = db_utils::getCollectionByRecord($rsProcedimentos);
    return $this->aProcedimentos; 
    
  }
  
  /**
   * Adiciona um filtro para ser usado no where do sql que buscar� os procedimentos 
   * @param string $sFiltro filtro para ser usado na busca dos procedimentos
   */
  public function adicionaFiltroBuscaProcedimentos($sFiltro) {
  	
    $this->aFiltrosProcedimentos[] = $sFiltro;
  }
  
}