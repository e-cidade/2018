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

define( "CAMINHO_MENSAGENS_RPC", "configuracao.configuracao.sys4_itensmenus." );

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson               = new services_json();
$oParam              = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno            = new stdClass();
$oRetorno->iStatus   = 1;
$oRetorno->sMensagem = '';

try {

  db_inicio_transacao();

  switch ($oParam->sExecucao) {

    case "getItens":

      $oMenu = new DBMenu( db_getsession("DB_modulo"),
                           db_getsession("DB_id_usuario"),
                           db_getsession("DB_anousu"),
                           db_getsession("DB_instit") );

      $oRetorno   = $oMenu->buscaMenu($oParam->sConteudo);

    break;

    /**
     * *****************************************************************************************
     * Salva um item do menu
     * @param integer $oParam->iCodigo           - Código do menu (pode vir vazio)
     *        string  $oParam->sDescricao        - Descrição do menu
     *        string  $oParam->sAjuda            - Ajuda relacionada ao menu
     *        string  $oParam->sFuncao           - Função executada ao acessar o menu
     *        string  $oParam->sDescricaoTecnica - Descrição técnica relacionada ao menu
     *        string  $oParam->sLiberadoCliente  - Caracter que informa se o menu está liberado
     *                ....... 't'
     *                ....... 'f'
     *        string  $oParam->sManutencao       - Por padrão, recebe o valor 1
     *        integer $oParam->iItemAtivo        - Situação do item
     *        boolean $oParam->lModulo           - Controla se é um módulo
     *
     * Parâmetros Opcionais
     *        string $oParam->sNomeModulo      - Nome do módulo
     *        string $oParam->sDescricaoModulo - Descrição do módulo
     *        string $oParam->sImagemModulo    - Caminho da imagem do módulo
     * *****************************************************************************************
     */
    case 'salvarItemMenu':

      if ( empty( $oParam->sDescricao ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_descricao" ) );
      }

      if ( empty( $oParam->sAjuda ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_ajuda" ) );
      }

      if ( empty( $oParam->sDescricaoTecnica ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_descricao_tecnica" ) );
      }

      $oMenuSistema = new MenuSistema( $oParam->iCodigo );
      $oMenuSistema->setDescricao( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDescricao ) );
      $oMenuSistema->setAjuda( db_stdClass::normalizeStringJsonEscapeString( $oParam->sAjuda ) );
      $oMenuSistema->setFuncao( db_stdClass::normalizeStringJsonEscapeString( $oParam->sFuncao ) );
      $oMenuSistema->setDescricaoTecnica( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDescricaoTecnica ) );
      $oMenuSistema->setLiberadoCliente( db_stdClass::normalizeStringJsonEscapeString( $oParam->sLiberadoCliente ) );
      $oMenuSistema->setManutencao( $oParam->sManutencao );
      $oMenuSistema->setItemAtivo( $oParam->iItemAtivo );
      $oMenuSistema->salvar();

      /**
       * Caso tenha sida aberta a opção do módulo, salva o mesmo
       */
      if ( isset( $oParam->lModulo ) && $oParam->lModulo ) {

        $oModulo = new ModuloSistema( $oMenuSistema->getCodigo() );

        if ( $oMenuSistema->getModulo() != null && $oMenuSistema->getModulo() instanceof ModuloSistema ) {
          $oModulo = $oMenuSistema->getModulo();
        }

        $oModulo->setNome( db_stdClass::normalizeStringJsonEscapeString( $oParam->sNomeModulo ) );
        $oModulo->setDescricao( db_stdClass::normalizeStringJsonEscapeString( $oParam->sDescricaoModulo ) );
        $oModulo->setImagem( db_stdClass::normalizeStringJsonEscapeString( $oParam->sImagemModulo ) );
        $oModulo->setTemExercicio( 't' );
        $oModulo->salvar( $oMenuSistema->getCodigo() );
      }

      $oRetorno->iMenu     = $oMenuSistema->getCodigo();
      $oRetorno->sMensagem = urlencode( _M( CAMINHO_MENSAGENS_RPC . "salvo_com_sucesso" ) );

      DBMenu::limpaCache();

      break;

    /**
     * ************************************************************************
     * Retorna os dados de um menu que seja módulo
     * @param integer $oParam->iCodigo - Id do item do módulo a ser pesquisado
     * ************************************************************************
     */
    case 'buscaItemMenuModulo':

      if ( !isset( $oParam->iCodigo ) || empty( $oParam->iCodigo ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_item" ) );
      }

      $oRetorno->lModulo = false;
      $oModuloSistema    = new ModuloSistema( $oParam->iCodigo );

      if ( $oModuloSistema->getCodigo() != null ) {

        $oRetorno->lModulo     = true;
        $oRetorno->iCodigo     = $oModuloSistema->getCodigo();
        $oRetorno->sNome       = urlencode( $oModuloSistema->getNome() );
        $oRetorno->mDescricao  = urlencode( $oModuloSistema->getDescricao() );
        $oRetorno->sImagem     = urlencode( $oModuloSistema->getImagem() );
        $oRetorno->sNomeManual = urlencode( $oModuloSistema->getNomeManual() );
      }

      break;

    /**
     * ****************************************************************************************************
     * Exclui um item de menu. Remove das tabelas db_itensmenu e da tabela db_modulos, caso exista registro
     * @param integer $oParam->iCodigo - Id do item do módulo a ser excluído
     * ****************************************************************************************************
     */
    case 'excluirItemMenu':

      if ( !isset( $oParam->iCodigo ) || empty( $oParam->iCodigo ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_item" ) );
      }

      $oMenuSistema = new MenuSistema( $oParam->iCodigo );
      $oMenuSistema->excluir();

      $oRetorno->sMensagem = urlencode( _M( CAMINHO_MENSAGENS_RPC . "excluido_com_sucesso" ) );

      DBMenu::limpaCache();

      break;

    /**
     * *****************************
     * Retorna os módulos existentes
     * @return array
     * *****************************
     */
    case 'buscaModulos':

      $oRetorno->aModulos = array();
      $aModulos           = ModuloSistemaCollection::buscaModulos();

      foreach ( $aModulos as $oModuloSistema ) {

        $oDadosModulo                   = new stdClass();
        $oDadosModulo->iCodigo          = $oModuloSistema->getCodigo();
        $oDadosModulo->sNome            = urlencode( $oModuloSistema->getNome() );
        $oDadosModulo->mDescricao       = urlencode( $oModuloSistema->getDescricao() );
        $oDadosModulo->sImagem          = urlencode( $oModuloSistema->getImagem() );
        $oDadosModulo->lTemExercicio    = $oModuloSistema->temExercicio();
        $oDadosModulo->sNomeManual      = urlencode( $oModuloSistema->getNomeManual() );

        $oRetorno->aModulos[] = $oDadosModulo;
      }

      break;

    /**
     * ***********************************
     * Retorna os dados de um item de menu
     * @return stdClass
     * ***********************************
     */
    case 'buscaItemMenu':

      if ( !isset( $oParam->iCodigo ) || empty( $oParam->iCodigo ) ) {
        throw new BusinessException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_item" ) );
      }

      $oMenuSistema                = new MenuSistema( $oParam->iCodigo );
      $oRetorno->iCodigo           = $oMenuSistema->getCodigo();
      $oRetorno->sDescricao        = urlencode( $oMenuSistema->getDescricao() );
      $oRetorno->mAjuda            = urlencode( $oMenuSistema->getAjuda() );
      $oRetorno->sFuncao           = urlencode( $oMenuSistema->getFuncao() );
      $oRetorno->mDescricaoTecnica = urlencode( $oMenuSistema->getDescricaoTecnica() );
      $oRetorno->sLiberadoCliente  = urlencode( $oMenuSistema->liberadoCliente() ? 't' : 'f' );
      $oRetorno->iItemAtivo        = $oMenuSistema->getItemAtivo();
      $oRetorno->sManutencao       = urlencode( $oMenuSistema->getManutencao() );
      $oRetorno->aMenusVinculados  = $oMenuSistema->menusVinculados();

      break;

    /**
     * *********************************************************************************
     * Busca os menus filhos de um menu selecionado
     * @param integer $oParam->iModulo - Código do módulo
     * @return array aMenus - Array de stdClass contendo as informações dos menus filhos
     *               ...... stdClass
     *               ............... integer iCodigo    - Código do menu
     *               ............... string  sDescricao - Descrição do menu
     *               ............... array   aFilhos    - Menus filhos
     * *********************************************************************************
     */
    case 'buscaMenusFilhos':

      if ( !isset( $oParam->iModulo ) || empty( $oParam->iModulo ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_modulo" ) );
      }

      $oRetorno->aMenus = array();
      $aMenusPrincipais = array();
      $oModuloSistema   = new ModuloSistema( $oParam->iModulo );

      /**
       * Percorre os menus principais do módulo.
       * $oRetorno->aMenus recebe como retorno os dados da função getDadosMenu, que monta as informações recursivamente
       */
      foreach( $oModuloSistema->getItensMenuPrincipais() as $oMenuPrincipal ) {
        $oRetorno->aMenus[] = getDadosMenu( $oMenuPrincipal->id_item, $oModuloSistema );
      }

      break;

    /**
     * ********************************************************************************************************
     * Salva os vínculos que o menu terá
     * @param integer $oParam->iModulo          - Código do módulo selecionado
     *        integer $oParam->iMenu            - Código do item de menu selecionado
     *        array   $oParam->aNosSelecionados - Contém o código dos menus que o item de menu estará vinculado
     * ********************************************************************************************************
     */
    case 'salvarVinculo':

      if ( !isset( $oParam->iModulo ) || empty( $oParam->iModulo ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_modulo" ) );
      }

      if ( !isset( $oParam->iMenu ) || empty( $oParam->iMenu ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_item" ) );
      }

      $oMenuSistema   = new MenuSistema( $oParam->iMenu );
      $oModuloSistema = new ModuloSistema( $oParam->iModulo );
      $oMenuSistema->salvarVinculo( $oModuloSistema, $oParam->aNosSelecionados );

      $oRetorno->sMensagem = urlencode( _M( CAMINHO_MENSAGENS_RPC . "vinculo_salvo" ) );

      DBMenu::limpaCache();

      break;

    /**
     * *************************************************************************
     * Retorna o código dos menus que o item de menu possui vínculo em um modulo
     * @param integer $oParam->iModulo - Código do módulo selecionado
     *        integer $oParam->iMenu   - Código do item de menu selecionado
     * @return array
     * *************************************************************************
     */
    case 'menusVinculados':

      if ( !isset( $oParam->iModulo ) || empty( $oParam->iModulo ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_modulo" ) );
      }

      if ( !isset( $oParam->iMenu ) || empty( $oParam->iMenu ) ) {
        throw new ParameterException( _M( CAMINHO_MENSAGENS_RPC . "informar_id_item" ) );
      }

      $oMenuSistema     = new MenuSistema( $oParam->iMenu );
      $oModuloSistema   = new ModuloSistema( $oParam->iModulo );
      $oRetorno->aMenus = $oMenuSistema->menusVinculados( $oModuloSistema );

      break;
  }

  db_fim_transacao();
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus   = 2;
  $oRetorno->sMensagem = urlencode( str_replace( "\\n", "\n", $eErro->getMessage() ) );
}

/**
 * Organiza os dados do menu recursivamente, verificando se um Menu possui filho, chamando a função novamente
 * @param integer       $iCodigoItemMenu - Código do menu principal
 * @param ModuloSistema $oModuloSistema  - Instância de ModuloSistema
 * @return stdClass
 */
function getDadosMenu( $iCodigoItemMenu, $oModuloSistema ) {

  $oItemMenu            = new MenuSistema( $iCodigoItemMenu );
  $oRetorno             = new stdClass();
  $oRetorno->iCodigo    = $oItemMenu->getCodigo();
  $oRetorno->sDescricao = urlencode( $oItemMenu->getDescricao() );
  $oRetorno->aFilhos    = array();

  foreach( $oItemMenu->getItensFilho( $oModuloSistema ) as $oItemFilho ) {
    $oRetorno->aFilhos[] = getDadosMenu( $oItemFilho->getCodigo(), $oModuloSistema );
  }

  return $oRetorno;
}

echo $oJson->encode($oRetorno);
?>