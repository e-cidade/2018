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

define( "CAMINHO_MENSAGENS_ITENS", "configuracao.configuracao.DbItensMenu." );

/**
 * Classe para controle de ações referente a db_itensmenu
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class DbItensMenu {
  
  /**
   * Código do item de menu
   * @var integer
   */
  private $iIdItem = null;
  
  /**
   * Descrição do item
   * @var string
   */
  private $sDescricao;
  
  /**
   * Ajuda (campo help) relacionada ao item do menu
   * @var memo
   */
  private $mAjuda;
  
  /**
   * Função executada ao acessar o menu
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
   * Informações relacionadas ao menu
   * @var memo
   */
  private $mDescricaoTecnica;
  
  /**
   * Controla se o menu está liberado para o cliente
   * @var boolean
   */
  private $lLiberadoCliente = false;
  
  /**
   * Instância de DbModulos
   * @var DbModulos
   */
  private $oDbModulo = null;
  
  /**
   * Array com com os menus filho
   * @var array
   */
  private $aItensFilho = array();
  
  /**
   * Construtor da classe. Recebe o código como parâmetro, que pode ser null
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
     * Armazena o retorno da query na variável $oDadosRetorno e seta os dados de cada campo
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
   * Retorna o código do menu
   * @return integer
   */
  public function getIdItem() {
    return $this->iIdItem;
  }
  
  /**
   * Seta o código do menu
   * @param integer $iIdItem
   */
  public function setIdItem( $iIdItem ) {
    $this->iIdItem = $iIdItem;
  }
  
  /**
   * Retorna a descrição do menu
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
  
  /**
   * Seta a descrição do menu
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
   * Seta as informações da ajuda
   * @param memo $mAjuda
   */
  public function setAjuda( $mAjuda ) {
    $this->mAjuda = $mAjuda;
  }
  
  /**
   * Retorna o arquivo da função do menu
   * @return string
   */
  public function getFuncao() {
    return $this->sFuncao;
  }
  
  /**
   * Seta o arquivo de função do menu
   * @param string $sFuncao
   */
  public function setFuncao( $sFuncao ) {
    $this->sFuncao = $sFuncao;
  }
  
  /**
   * Retorna se o item está ativo
   */
  public function getItemAtivo() {
    return $this->iItemAtivo;
  }
  
  /**
   * Seta se o item está ativo
   * @param integer $iItemAtivo
   */
  public function setItemAtivo( $iItemAtivo ) {
    $this->iItemAtivo = $iItemAtivo;
  }
  
  /**
   * Retorna o valor do campo manutenção
   * @return string
   */
  public function getManutencao() {
    return $this->sManutencao;
  }
  
  /**
   * Seta o valor do campo manutenção
   * @param string $sManutencao
   */
  public function setManutencao( $sManutencao ) {
    $this->sManutencao = $sManutencao;
  }
  
  /**
   * Retorna a descrição técnica do menu
   * @return memo
   */
  public function getDescricaoTecnica() {
    return $this->mDescricaoTecnica;
  }
  
  /**
   * Seta a descrição técnica do menu
   * @param menu $mDescricaoTecnica
   */
  public function setDescricaoTecnica( $mDescricaoTecnica ) {
    $this->mDescricaoTecnica = $mDescricaoTecnica;
  }
  
  /**
   * Valida se o menu está liberado para o cliente
   * @return boolean
   */
  public function liberadoCliente() {
    return $this->lLiberadoCliente;
  }
  
  /**
   * Seta se o menu está liberado para o cliente
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
   * Retorna uma instância de DbModulos caso o menu seja um módulo. Caso contrário, retorna null
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
   * Exclui o registro da tabela db_itensmenu. Verifica também se o item é um módulo, excluindo o registro de db_modulos
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
     * Verifica se existe a instância de DbModulos, excluindo o registro
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
   * Busca os itens filho de um item do menu a partir do seu id_item e o módulo desejado
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