<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

define( "CAMINHO_MENSAGENS_MODULOS", "configuracao.configuracao.DbModulos." );

/**
 * Classe para controle das ações de db_modulos
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbModulos {
  
  /**
   * Código do módulo
   * @var integer
   */
  private $iIdItem = null;
  
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
   * @param integer $iIdItem
   * @throws DBException
   */
  public function __construct( $iIdItem = null ) {
    
    if ( empty( $iIdItem ) ) {
      return;
    }
    
    $oDaoDbModulos = new cl_db_modulos();
    $sSqlDbModulos = $oDaoDbModulos->sql_query_file( $iIdItem );
    $rsDbModulos   = db_query( $sSqlDbModulos );
    
    if ( !$rsDbModulos ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbModulos );
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "erro_buscar_modulo", $oMensagem ) );
    }

    if ( pg_num_rows( $rsDbModulos ) == 0 ) {
      return;
    }
    
    $oDadosRetorno       = db_utils::fieldsMemory( $rsDbModulos, 0 );
    $this->iIdItem       = $iIdItem;
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
  public function getIdItem() {
    return $this->iIdItem;
  }
  
  /**
   * Seta o id_item do módulo
   * @param integer $iIdItem
   */
  public function setIdItem( $iIdItem ) {
    $this->iIdItem = $iIdItem;
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
   * @param integer $iIdItem - id_item do módulo a ser salvo
   * 
   * @throws DBException
   */
  public function salvar( $iIdItem ) {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( "Sem Transação com o banco de dados ativa." );
    }
    
    $oDaoDbModulos               = new cl_db_modulos();
    $oDaoDbModulos->nome_modulo  = $this->getNome();
    $oDaoDbModulos->descr_modulo = $this->getDescricao();
    $oDaoDbModulos->imagem       = $this->getImagem();
    $oDaoDbModulos->temexerc     = $this->temExercicio() ? 'true' : 'false';
    $oDaoDbModulos->nome_manual  = $this->getNomeManual();
    
    if ( $this->getIdItem() != null ) {
    
      $oDaoDbModulos->id_item = $this->getIdItem();
      $oDaoDbModulos->alterar( $this->getIdItem() );
    } else {
    
      $oDaoDbModulos->incluir( $iIdItem );
      $this->iIdItem = $oDaoDbModulos->id_item;
    }
    
    if ( $oDaoDbModulos->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbModulos->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "erro_salvar_modulo", $oMensagem ) );
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
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "sem_transacao" ) );
    }
    
    if ( $this->getIdItem() == null ) {
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "id_nao_setado" ) );
    }
    
    $oDaoDbModulos = new cl_db_modulos();
    $oDaoDbModulos->excluir( $this->getIdItem() );
    
    if ( $oDaoDbModulos->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbModulos->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "erro_excluir_modulo", $oMensagem ) );
    }
  }
  
  /**
   * Retorna a instância de DbItensMenu dos menus vinculados ao módulo
   * 
   * @return DbItensMenu[]
   * @throws DBException
   */
  public function getItensMenuVinculados() {
    
    $oDaoDbMenu   = new cl_db_menu();
    $sWhereDbMenu = "modulo = {$this->getIdItem()}";
    $sSqlDbMenu   = $oDaoDbMenu->sql_query_file( null, "distinct id_item", null, $sWhereDbMenu );
    $rsDbMenu     = db_query( $sSqlDbMenu );
    
    if ( !$rsDbMenu  ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_MODULOS . "erro_buscar_db_menu", $oMensagem ) );
    }
    
    $iLinhasDbMenu = pg_num_rows( $rsDbMenu );
    if ( $iLinhasDbMenu > 0 ) {
      
      for ( $iContador = 0; $iContador < $iLinhasDbMenu; $iContador++ ) {
        
        $iIdItemDbMenu                = db_utils::fieldsMemory( $rsDbMenu, $iContador )->id_item;
        $oDbItensMenu                 = new DbItensMenu( $iIdItemDbMenu );
        $this->aItensMenuVinculados[] = $oDbItensMenu;
      }
    }
    
    return $this->aItensMenuVinculados;
  }
} 
?>