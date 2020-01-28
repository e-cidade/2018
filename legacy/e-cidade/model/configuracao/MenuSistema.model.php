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

define( "CAMINHO_MENSAGENS_ITENS", "configuracao.configuracao.MenuSistema." );

/**
 * Classe para controle de ações referente a db_itensmenu
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package configuracao
 *
 */
class MenuSistema {
  
  /**
   * Código do item de menu
   * @var integer
   */
  private $iCodigo = null;
  
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
   * Instância de ModuloSistema
   * @var ModuloSistema
   */
  private $ModuloSistema = null;
  
  /**
   * Array com com os menus filho
   * @var array
   */
  private $aItensFilho = array();
  
  /**
   * Construtor da classe. Recebe o código como parâmetro, que pode ser null
   * @param integer $iCodigo
   * @throws DBException
   */
  public function __construct( $iCodigo = null ) {
    
    if ( empty( $iCodigo ) ) {
      return;
    }
    
    $oDaoMenuSistema = new cl_db_itensmenu();
    $sSqlMenuSistema = $oDaoMenuSistema->sql_query_file( $iCodigo );
    $rsMenuSistema   = db_query( $sSqlMenuSistema );
    
    if ( !$rsMenuSistema ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsMenuSistema );
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_buscar_item" ) );
    }
    
    if ( pg_num_rows( $rsMenuSistema ) == 0 ) {
      return;
    }
    
    /**
     * Armazena o retorno da query na variável $oDadosRetorno e seta os dados de cada campo
     */
    $oDadosRetorno           = db_utils::fieldsMemory( $rsMenuSistema, 0 );
    $this->iCodigo           = $iCodigo;
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
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Seta o código do menu
   * @param integer $iCodigo
   */
  public function setCodigo( $iCodigo ) {
    $this->iCodigo = $iCodigo;
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
    
    $oDaoMenuSistema             = new cl_db_itensmenu();
    $oDaoMenuSistema->descricao  = $this->getDescricao();
    $oDaoMenuSistema->help       = $this->getAjuda();
    $oDaoMenuSistema->funcao     = $this->getFuncao();
    $oDaoMenuSistema->itemativo  = $this->getItemAtivo();
    $oDaoMenuSistema->manutencao = $this->getManutencao();
    $oDaoMenuSistema->desctec    = $this->getDescricaoTecnica();
    $oDaoMenuSistema->libcliente = $this->liberadoCliente() ? 'true' : 'false';
    
    if ( $this->getCodigo() != null ) {
      
      $oDaoMenuSistema->id_item = $this->getCodigo();
      $oDaoMenuSistema->alterar( $this->getCodigo() );
    } else {
      
      $oDaoMenuSistema->incluir( null );
      $this->iCodigo = $oDaoMenuSistema->id_item;
    }
    
    if ( $oDaoMenuSistema->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoMenuSistema->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_salvar_dados" ) );
    }
  }
  
  /**
   * Retorna uma instância de ModuloSistema caso o menu seja um módulo. Caso contrário, retorna null
   * 
   * @throws BusinessException
   * @return ModuloSistema
   */
  public function getModulo() {
    
    if ( $this->getCodigo() == null ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoMenuSistema->erro_msg;
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    $this->ModuloSistema = new ModuloSistema( $this->getCodigo() );
    
    if ( $this->ModuloSistema->getCodigo() != null ) {
      return $this->ModuloSistema;
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
    
    if ( $this->getCodigo() == null ) {
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    /**
     * Verifica se existe a instância de ModuloSistema, excluindo o registro
     */
    if ( $this->getModulo() != null && $this->getModulo() instanceof ModuloSistema ) {
      $this->getModulo()->excluir();
    }
    
    $oDaoMenuSistema = new cl_db_itensmenu();
    $oDaoMenuSistema->excluir( $this->getCodigo() );
    
    if ( $oDaoMenuSistema->erro_status == "0" ) {
      
      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoMenuSistema->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_excluir_dados" ) );
    }
  }
  
  /**
   * Busca os itens filho de um item do menu a partir do seu id_item e o módulo desejado
   * 
   * @param  ModuloSistema $ModuloSistema
   * @throws BusinessException
   * @throws DBException
   * @return MenuSistema[]
   */
  public function getItensFilho( ModuloSistema $ModuloSistema ) {
    
    if ( $this->getCodigo() == null ) {
      throw new BusinessException( _M( CAMINHO_MENSAGENS_ITENS . "id_nao_setado" ) );
    }
    
    $oDaoDbMenu   = new cl_db_menu();
    $sWhereDbMenu = " m.id_item = {$this->getCodigo()} AND m.modulo = {$ModuloSistema->getCodigo()}";
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
      
      $iCodigoFilho        = db_utils::fieldsMemory( $rsDbMenu, $iContador )->id_item_filho;
      $oMenuSistema        = new MenuSistema( $iCodigoFilho );
      $this->aItensFilho[] = $oMenuSistema;
    }
    
    return $this->aItensFilho;
  }

  /**
   * Salva os vínculos de um item de menu
   * @param ModuloSistema $oModuloSistema
   * @param array $aMenusVinculo
   * @throws DBException
   */
  public function salvarVinculo( ModuloSistema $oModuloSistema, array $aMenusVinculo ) {

    /**
     * Primeiramente, excluir os vínculos do menu
     */
    $oDaoDbMenu = new cl_db_menu();
    $oDaoDbMenu->excluir( null, "id_item_filho = {$this->getCodigo()} AND modulo = {$oModuloSistema->getCodigo()}" );

    if ( $oDaoDbMenu->erro_status == "0" ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = $oDaoDbMenu->erro_msg;
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_excluir_vinculos" ) );
    }


    /**
     * Percorre os menus vinculados, para nova inclusão
     */
    $iMenuSequencia = 0;
    foreach ( $aMenusVinculo as $iMenuVinculo ) {

      $sSqlDbMenu = $oDaoDbMenu->sql_query_file( null, "max( menusequencia ) as sequencia", null, "id_item = {$iMenuVinculo}" );
      $rsDbMenu   = db_query( $sSqlDbMenu );

      if ( pg_num_rows( $rsDbMenu ) > 0 ) {
        $iMenuSequencia = db_utils::fieldsMemory( $rsDbMenu, 0 )->sequencia;
      }

      $oDaoDbMenu->id_item       = $iMenuVinculo;
      $oDaoDbMenu->id_item_filho = $this->getCodigo();
      $oDaoDbMenu->menusequencia = $iMenuSequencia + 1;
      $oDaoDbMenu->modulo        = $oModuloSistema->getCodigo();
      $oDaoDbMenu->incluir( null );

      if ( $oDaoDbMenu->erro_status == "0" ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = $oDaoDbMenu->erro_msg;
        throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_incluir_vinculos" ) );
      }
    }
  }

  /**
   * Retorna os menus que o item de menu está vinculado
   * @param ModuloSistema $oModuloSistema
   * @return array
   * @throws DBException
   */
  public function menusVinculados( ModuloSistema $oModuloSistema = null ) {

    $aMenus       = array();
    $oDaoDbMenu   = new cl_db_menu();
    $sWhereDbMenu = "id_item_filho = {$this->getCodigo()}";

    if ( !empty( $oModuloSistema ) ) {
      $sWhereDbMenu .= " AND modulo = {$oModuloSistema->getCodigo()}";
    }

    $sSqlDbMenu = $oDaoDbMenu->sql_query_file( null, "id_item, modulo", null, $sWhereDbMenu );
    $rsDbMenu   = db_query( $sSqlDbMenu );

    if ( !$rsDbMenu ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsDbMenu );
      throw new DBException( _M( CAMINHO_MENSAGENS_ITENS . "erro_buscar_menus_vinculados" ) );
    }

    $iTotalLinhas = pg_num_rows( $rsDbMenu );
    for ( $iContador = 0; $iContador < $iTotalLinhas; $iContador++ ) {

      $oDadosVinculo          = new stdClass();
      $oRetornoDbMenu         = db_utils::fieldsMemory( $rsDbMenu, $iContador );
      $oDadosVinculo->iMenu   = $oRetornoDbMenu->id_item;
      $oDadosVinculo->iModulo = $oRetornoDbMenu->modulo;

      $aMenus[] = $oDadosVinculo;
    }

    return $aMenus;
  }
}
?>