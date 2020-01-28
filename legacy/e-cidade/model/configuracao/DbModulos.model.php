<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

define( "CAMINHO_MENSAGENS_MODULOS", "configuracao.configuracao.DbModulos." );

/**
 * Classe para controle das a��es de db_modulos
 * @author  F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbModulos {
  
  /**
   * C�digo do m�dulo
   * @var integer
   */
  private $iIdItem = null;
  
  /**
   * Nome do m�dulo
   * @var string
   */
  private $sNome;
  
  /**
   * Descri��o do m�dulo
   * @var memo
   */
  private $mDescricao;
  
  /**
   * Caminho da imagem do m�dulo
   * @var string
   */
  private $sImagem;
  
  /**
   * Exerc�cio do m�dulo
   * @var boolean
   */
  private $lTemExercicio = true;
  
  /**
   * Caminho onde se encontra o manual do m�dulo
   * @var string
   */
  private $sNomeManual;
  
  /**
   * Array com os itens de menu que fazem parte do m�dulo
   * @var array
   */
  private $aItensMenuVinculados = array();
  
  /**
   * Construtor da classe. Recebe o id_item como par�metro, que pode ser null
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
   * Retorna o id_item do m�dulo
   * @return integer
   */
  public function getIdItem() {
    return $this->iIdItem;
  }
  
  /**
   * Seta o id_item do m�dulo
   * @param integer $iIdItem
   */
  public function setIdItem( $iIdItem ) {
    $this->iIdItem = $iIdItem;
  }
  
  /**
   * Retorna o nome do m�dulo
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * Seta o nome do m�dulo
   * @param string $sNome
   */
  public function setNome( $sNome ) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna a descri��o do m�dulo
   * @return memo
   */
  public function getDescricao() {
    return $this->mDescricao;
  }
  
  /**
   * Seta a descri��o do m�dulo
   * @param memo $sDescricao
   */
  public function setDescricao( $mDescricao ) {
    $this->mDescricao = $mDescricao;
  }
  
  /**
   * Retorna o caminho da imagem do m�dulo
   * @return string
   */
  public function getImagem() {
    return $this->sImagem;
  }
  
  /**
   * Seta o caminho da imagem do m�dulo
   * @param string $sImagem
   */
  public function setImagem( $sImagem ) {
    $this->sImagem = $sImagem;
  }
  
  /**
   * Controle o exerc�cio do m�dulo
   */
  public function temExercicio() {
    return $this->lTemExercicio;
  }
  
  /**
   * Seta se o m�dulo tem exerc�cio
   * @param string $sTemExercicio
   */
  public function setTemExercicio( $sTemExercicio ) {
    $this->lTemExercicio = $sTemExercicio == 't' ? true : false;
  }
  
  /**
   * Retorna o caminho do manual do m�dulo
   * @return string
   */
  public function getNomeManual() {
    return $this->sNomeManual;
  }
  
  /**
   * Seta o caminho do manual do m�dulo
   * @param string $sNomeManual
   */
  public function setNomeManual( $sNomeManual ) {
    $this->sNomeManual = $sNomeManual;
  }
  
  /**
   * Salva os dados do m�dulo. Recebe um id_item por par�metro, caso n�o exista o m�dulo
   * @param integer $iIdItem - id_item do m�dulo a ser salvo
   * 
   * @throws DBException
   */
  public function salvar( $iIdItem ) {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( "Sem Transa��o com o banco de dados ativa." );
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
   * Retorna a inst�ncia de DbItensMenu dos menus vinculados ao m�dulo
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