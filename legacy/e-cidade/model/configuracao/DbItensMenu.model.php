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

define( "CAMINHO_MENSAGENS_ITENS", "configuracao.configuracao.DbItensMenu." );

/**
 * Classe para controle de a��es referente a db_itensmenu
 * @author  F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbItensMenu {
  
  /**
   * C�digo do item de menu
   * @var integer
   */
  private $iIdItem = null;
  
  /**
   * Descri��o do item
   * @var string
   */
  private $sDescricao;
  
  /**
   * Ajuda (campo help) relacionada ao item do menu
   * @var memo
   */
  private $mAjuda;
  
  /**
   * Fun��o executada ao acessar o menu
   * @var string
   */
  private $sFuncao;
  
  /**
   * @var integer
   */
  private $iItemAtivo;
  
  /**
   * 
   * @var string
   */
  private $sManutencao;
  
  /**
   * Informa��es relacionadas ao menu
   * @var memo
   */
  private $mDescricaoTecnica;
  
  /**
   * Controla se o menu est� liberado para o cliente
   * @var boolean
   */
  private $lLiberadoCliente = false;
  
  /**
   * Inst�ncia de DbModulos
   * @var DbModulos
   */
  private $oDbModulo = null;
  
  /**
   * Array com com os menus filho
   * @var array
   */
  private $aItensFilho = array();
  
  /**
   * Construtor da classe. Recebe o c�digo como par�metro, que pode ser null
   * @param integer $iIdItem
   * @throws DBException
   */
  public function __construct( $iIdItem = null ) {
    
    if ( empty( $iIdItem ) ) {
      return;
    }
    
    $oDaoDbItensMenu = new cl_db_itensmenu();
    $sSqlDbItensMenu = $oDaoDbItensMenu->sql_query_file( $iIdItem );
    $rsDbItensMenu   = db_query( $sSqlDbItensMenu );
    
    if ( !$rsDbItensMenu ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbItensMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_buscar_item" ) );
    }
    
    if ( pg_num_rows( $rsDbItensMenu ) == 0 ) {
      return;
    }
    
    /**
     * Armazena o retorno da query na vari�vel $oDadosRetorno e seta os dados de cada campo
     */
    $oDadosRetorno           = db_utils::fieldsMemory( $rsDbItensMenu, 0 );
    $this->iIdItem           = $iIdItem;
    $this->sDescricao        = $oDadosRetorno->descricao;
    $this->mAjuda            = $oDadosRetorno->help;
    $this->sFuncao           = $oDadosRetorno->funcao;
    $this->iItemAtivo        = $oDadosRetorno->itemativo;
    $this->sManutencao       = $oDadosRetorno->manutencao;
    $this->mDescricaoTecnica = $oDadosRetorno->desctec;
    $this->lLiberadoCliente  = $oDadosRetorno->libcliente == 't' ? true : false;
  }
  
  /**
   * Retorna o c�digo do menu
   * @return integer
   */
  public function getIdItem() {
    return $this->iIdItem;
  }
  
  /**
   * Seta o c�digo do menu
   * @param integer $iIdItem
   */
  public function setIdItem( $iIdItem ) {
    $this->iIdItem = $iIdItem;
  }
  
  /**
   * Retorna a descri��o do menu
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta a descri��o do menu
   * @param string $sDescricao
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a ajuda do menu
   * @return memo
   */
  public function getAjuda() {
    return $this->mAjuda;
  }
  
  /**
   * Seta as informa��es da ajuda
   * @param memo $mAjuda
   */
  public function setAjuda( $mAjuda ) {
    $this->mAjuda = $mAjuda;
  }
  
  /**
   * Retorna o arquivo da fun��o do menu
   * @return string
   */
  public function getFuncao() {
    return $this->sFuncao;
  }
  
  /**
   * Seta o arquivo de fun��o do menu
   * @param string $sFuncao
   */
  public function setFuncao( $sFuncao ) {
    $this->sFuncao = $sFuncao;
  }
  
  /**
   * Retorna se o item est� ativo
   */
  public function getItemAtivo() {
    return $this->iItemAtivo;
  }
  
  /**
   * Seta se o item est� ativo
   * @param integer $iItemAtivo
   */
  public function setItemAtivo( $iItemAtivo ) {
    $this->iItemAtivo = $iItemAtivo;
  }
  
  /**
   * Retorna o valor do campo manuten��o
   * @return string
   */
  public function getManutencao() {
    return $this->sManutencao;
  }
  
  /**
   * Seta o valor do campo manuten��o
   * @param string $sManutencao
   */
  public function setManutencao( $sManutencao ) {
    $this->sManutencao = $sManutencao;
  }
  
  /**
   * Retorna a descri��o t�cnica do menu
   * @return memo
   */
  public function getDescricaoTecnica() {
    return $this->mDescricaoTecnica;
  }
  
  /**
   * Seta a descri��o t�cnica do menu
   * @param menu $mDescricaoTecnica
   */
  public function setDescricaoTecnica( $mDescricaoTecnica ) {
    $this->mDescricaoTecnica = $mDescricaoTecnica;
  }
  
  /**
   * Valida se o menu est� liberado para o cliente
   * @return boolean
   */
  public function liberadoCliente() {
    return $this->lLiberadoCliente;
  }
  
  /**
   * Seta se o menu est� liberado para o cliente
   * @param string $sLiberadoCliente
   */
  public function setLiberadoCliente( $sLiberadoCliente ) {
    $this->lLiberadoCliente = $sLiberadoCliente == 't' ? true : false;
  }
  
  /**
   * Salva os dados do menu
   * @throws DBException
   */
  public function salvar() {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "sem_transacao" ) );
    }
    
    $oDaoDbItensMenu             = new cl_db_itensmenu();
    $oDaoDbItensMenu->descricao  = $this->getDescricao();
    $oDaoDbItensMenu->help       = $this->getAjuda();
    $oDaoDbItensMenu->funcao     = $this->getFuncao();
    $oDaoDbItensMenu->itemativo  = $this->getItemAtivo();
    $oDaoDbItensMenu->manutencao = $this->getManutencao();
    $oDaoDbItensMenu->desctec    = $this->getDescricaoTecnica();
    $oDaoDbItensMenu->libcliente = $this->liberadoCliente() ? 'true' : 'false';
    
    if ( $this->getIdItem() != null ) {
      
      $oDaoDbItensMenu->id_item = $this->getIdItem();
      $oDaoDbItensMenu->alterar( $this->getIdItem() );
    } else {
      
      $oDaoDbItensMenu->incluir( null );
      $this->iIdItem = $oDaoDbItensMenu->id_item;
    }
    
    if ( $oDaoDbItensMenu->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbItensMenu->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_salvar_dados" ) );
    }
  }
  
  /**
   * Retorna uma inst�ncia de DbModulos caso o menu seja um m�dulo. Caso contr�rio, retorna null
   * 
   * @throws BusinessException
   * @return DbModulos
   */
  public function getModulo() {
    
    if ( $this->getIdItem() == null ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbItensMenu->erro_msg;
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    $this->oDbModulo = new DbModulos( $this->getIdItem() );
    
    if ( $this->oDbModulo->getIdItem() != null ) {
      return $this->oDbModulo;
    }
    
    return null;
  }
  
  /**
   * Exclui o registro da tabela db_itensmenu. Verifica tamb�m se o item � um m�dulo, excluindo o registro de db_modulos
   * 
   * @throws DBException
   * @throws BusinessException
   */
  public function excluir() {
    
    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "sem_transacao" ) );
    }
    
    if ( $this->getIdItem() == null ) {
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    /**
     * Verifica se existe a inst�ncia de DbModulos, excluindo o registro
     */
    if ( $this->getModulo() != null && $this->getModulo() instanceof DbModulos ) {
      $this->getModulo()->excluir();
    }
    
    $oDaoDbItensMenu = new cl_db_itensmenu();
    $oDaoDbItensMenu->excluir( $this->getIdItem() );
    
    if ( $oDaoDbItensMenu->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbItensMenu->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_excluir_dados" ) );
    }
  }
  
  /**
   * Busca os itens filho de um item do menu a partir do seu id_item e o m�dulo desejado
   * 
   * @param  DbModulos $oDbModulo
   * @throws BusinessException
   * @throws DBException
   * @return DbItensMenu[]
   */
  public function getItensFilho( DbModulos $oDbModulo ) {
    
    if ( $this->getIdItem() == null ) {
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    $oDaoDbMenu   = new cl_db_menu();
    $sWhereDbMenu = " m.id_item = {$this->getIdItem()} AND m.modulo = {$oDbModulo->getIdItem()}";
    $sSqlDbMenu   = $oDaoDbMenu->sql_query_menus( null, "m.id_item_filho", "m.menusequencia", $sWhereDbMenu );
    $rsDbMenu     = db_query( $sSqlDbMenu );
    
    if ( !$rsDbMenu ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_buscar_itens_filho" ) );
    }

    $iLinhasDbMenu = pg_num_rows( $rsDbMenu );
    if ( $iLinhasDbMenu == 0 ) {
      return $this->aItensFilho;
    }
    
    for ( $iContador = 0; $iContador < $iLinhasDbMenu; $iContador++ ) {
      
      $iIdItemFilho        = db_utils::fieldsMemory( $rsDbMenu, $iContador )->id_item_filho;
      $oDbItensMenu        = new DbItensMenu( $iIdItemFilho );
      $this->aItensFilho[] = $oDbItensMenu;
    }
    
    return $this->aItensFilho;
  }
} 
?>