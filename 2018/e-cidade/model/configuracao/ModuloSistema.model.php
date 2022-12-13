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

define( "CAMINHO_MENSAGENS_MODULO_SISTEMA", "configuracao.configuracao.ModuloSistema." );

/**
 * Classe para controle das ações de db_modulos
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class ModuloSistema {
  
  /**
   * Código do módulo
   * @var integer
   */
  private $iCodigo = null;
  
  /**
   * Nome do módulo
   * @var string
   */
  private $sNome;
  
  /**
   * Descrição do módulo
   * @var memo
   */
  private $mDescricao;
  
  /**
   * Caminho da imagem do módulo
   * @var string
   */
  private $sImagem;
  
  /**
   * Exercício do módulo
   * @var boolean
   */
  private $lTemExercicio = true;
  
  /**
   * Caminho onde se encontra o manual do módulo
   * @var string
   */
  private $sNomeManual;
  
  /**
   * Array com os itens de menu que fazem parte do módulo
   * @var array
   */
  private $aItensMenuVinculados = array();
  
  /**
   * Construtor da classe. Recebe o id_item como parâmetro, que pode ser null
   * @param integer $iCodigo
   * @throws DBException
   */
  public function __construct( $iCodigo = null ) {
    
    if ( empty( $iCodigo ) ) {
      return;
    }
    
    $oDaoModuloSistema = new cl_db_modulos();
    $sSqlModuloSistema = $oDaoModuloSistema->sql_query_file( $iCodigo );
    $rsModuloSistema   = db_query( $sSqlModuloSistema );
    
    if ( !$rsModuloSistema ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsModuloSistema );
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "erro_buscar_modulo", $oMensagem ) );
    }

    if ( pg_num_rows( $rsModuloSistema ) == 0 ) {
      return;
    }
    
    $oDadosRetorno       = db_utils::fieldsMemory( $rsModuloSistema, 0 );
    $this->iCodigo       = $iCodigo;
    $this->sNome         = $oDadosRetorno->nome_modulo;
    $this->mDescricao    = $oDadosRetorno->descr_modulo;
    $this->sImagem       = $oDadosRetorno->imagem;
    $this->lTemExercicio = $oDadosRetorno->temexerc == 't' ? true : false;
    $this->sNomeManual   = $oDadosRetorno->nome_manual;
  }
  
  /**
   * Retorna o id_item do módulo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta o id_item do módulo
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Retorna o nome do módulo
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * Seta o nome do módulo
   * @param string $sNome
   */
  public function setNome( $sNome ) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna a descrição do módulo
   * @return memo
   */
  public function getDescricao() {
    return $this->mDescricao;
  }
  
  /**
   * Seta a descrição do módulo
   * @param memo $sDescricao
   */
  public function setDescricao( $mDescricao ) {
    $this->mDescricao = $mDescricao;
  }
  
  /**
   * Retorna o caminho da imagem do módulo
   * @return string
   */
  public function getImagem() {
    return $this->sImagem;
  }
  
  /**
   * Seta o caminho da imagem do módulo
   * @param string $sImagem
   */
  public function setImagem( $sImagem ) {
    $this->sImagem = $sImagem;
  }
  
  /**
   * Controle o exercício do módulo
   */
  public function temExercicio() {
    return $this->lTemExercicio;
  }
  
  /**
   * Seta se o módulo tem exercício
   * @param string $sTemExercicio
   */
  public function setTemExercicio( $sTemExercicio ) {
    $this->lTemExercicio = $sTemExercicio == 't' ? true : false;
  }
  
  /**
   * Retorna o caminho do manual do módulo
   * @return string
   */
  public function getNomeManual() {
    return $this->sNomeManual;
  }
  
  /**
   * Seta o caminho do manual do módulo
   * @param string $sNomeManual
   */
  public function setNomeManual( $sNomeManual ) {
    $this->sNomeManual = $sNomeManual;
  }
  
  /**
   * Salva os dados do módulo. Recebe um id_item por parâmetro, caso não exista o módulo
   * @param integer $iCodigo - id_item do módulo a ser salvo
   * 
   * @throws DBException
   */
  public function salvar( $iCodigo ) {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( "Sem Transação com o banco de dados ativa." );
    }
    
    $oDaoModuloSistema               = new cl_db_modulos();
    $oDaoModuloSistema->nome_modulo  = $this->getNome();
    $oDaoModuloSistema->descr_modulo = $this->getDescricao();
    $oDaoModuloSistema->imagem       = $this->getImagem();
    $oDaoModuloSistema->temexerc     = $this->temExercicio() ? 'true' : 'false';
    $oDaoModuloSistema->nome_manual  = $this->getNomeManual();
    
    if ( $this->getCodigo() != null ) {
    
      $oDaoModuloSistema->id_item = $this->getCodigo();
      $oDaoModuloSistema->alterar( $this->getCodigo() );
    } else {
    
      $oDaoModuloSistema->incluir( $iCodigo );
      $this->iCodigo = $oDaoModuloSistema->id_item;
    }
    
    if ( $oDaoModuloSistema->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoModuloSistema->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "erro_salvar_modulo", $oMensagem ) );
    }
  }
  
  /**
   * Exclui um registro da tabela db_modulos
   * 
   * @throws DBException
   * @throws BusinessException
   */
  public function excluir() {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "sem_transacao" ) );
    }
    
    if ( $this->getCodigo() == null ) {
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "id_nao_setado" ) );
    }
    
    $oDaoModuloSistema = new cl_db_modulos();
    $oDaoModuloSistema->excluir( $this->getCodigo() );
    
    if ( $oDaoModuloSistema->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoModuloSistema->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "erro_excluir_modulo", $oMensagem ) );
    }
  }
  
  /**
   * Retorna a instância de MenuSistema dos menus vinculados ao módulo
   * 
   * @return MenuSistema[]
   * @throws DBException
   */
  public function getItensMenuVinculados() {
    
    $oDaoDbMenu   = new cl_db_menu();
    $sWhereDbMenu = "modulo = {$this->getCodigo()}";
    $sSqlDbMenu   = $oDaoDbMenu->sql_query_file( null, "distinct id_item", null, $sWhereDbMenu );
    $rsDbMenu     = db_query( $sSqlDbMenu );
    
    if ( !$rsDbMenu  ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "erro_buscar_db_menu", $oMensagem ) );
    }
    
    $iLinhasDbMenu = pg_num_rows( $rsDbMenu );
    if ( $iLinhasDbMenu > 0 ) {
      
      for ( $iContador = 0; $iContador < $iLinhasDbMenu; $iContador++ ) {
        
        $iCodigoDbMenu                = db_utils::fieldsMemory( $rsDbMenu, $iContador )->id_item;
        $oMenuSistema                 = new MenuSistema( $iCodigoDbMenu );
        $this->aItensMenuVinculados[] = $oMenuSistema;
      }
    }
    
    return $this->aItensMenuVinculados;
  }
  
  /**
   * Busca os menus principais ( CADASTROS / RELATÓRIOS / CONSULTAS / PROCEDIMENTOS ) do módulo
   * @throws DBException
   */
  public function getItensMenuPrincipais() {
    
    $aMenus          = array();
    $oDaoItemMenu    = new cl_db_itensmenu();
    $sCamposItemMenu = "db_itensmenu.id_item, db_itensmenu.descricao";
    $sWhereItemMenu  = "db_modulos.id_item = {$this->getCodigo()} and db_modulos.id_item = db_menu.id_item";
    $sSqlItemMenu    = $oDaoItemMenu->sql_query_menus_principais( null, $sCamposItemMenu, "db_itensmenu.id_item", $sWhereItemMenu );
    $rsItemMenu      = db_query( $sSqlItemMenu );
    
    if ( !$rsItemMenu  ) {
    
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsItemMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULO_SISTEMA . "erro_buscar_menus_principais", $oMensagem ) );
    }
    
    $iLinhasItemMenu = pg_num_rows( $rsItemMenu );
    if ( $iLinhasItemMenu > 0 ) {
      $aMenus = db_utils::getCollectionByRecord( $rsItemMenu );
    }
    
    return $aMenus;
  }
} 
?>