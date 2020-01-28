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

require_once("libs/exceptions/ParameterException.php");
require_once("model/issqn/alvara/MovimentacaoAlvara.model.php");

/**
 * Fabrica de instancias de movimentacoes de alvara
 * 
 * @abstract
 * @package ISSQN
 * @subpackage ALVARA
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
abstract class MovimentacaoAlvaraFactory {

  /**
   * Retorna instancia da movimentacao pelo seu codigo
   *
   * @param integer $iCodigo
   * @static
   * @access public
   * @return mixed
   */
  public static function getInstanciaPeloCodigo( $iCodigo ) {

    $oDaoIssMovAlvara       = db_utils::getDao("issmovalvara");
    $sSqlMovimentacaoAlvara = $oDaoIssMovAlvara->sql_query_file($iCodigo, 'q120_sequencial, q120_isstipomovalvara');
    $rsMovimentacaoAlvara   = db_query( $sSqlMovimentacaoAlvara );
    
    if ( !$rsMovimentacaoAlvara ) {
      throw new DBException("Erro ao Buscar dados da Movimentação pelo código: {$iCodigo}.\nErro Técnico" . pg_last_error());
    }
    
    if ( pg_num_rows($rsMovimentacaoAlvara) == 0 ) {
      throw new BusinessException("Nenhuma movimentação encontrada com o código: {$iCodigo}"); 
    } 

    $oDadosMovimentacao = db_utils::fieldsMemory($rsMovimentacaoAlvara, 0);
    $oMovimentacao      = MovimentacaoAlvaraFactory::getInstancia($oDadosMovimentacao->q120_isstipomovalvara, $oDadosMovimentacao->q120_sequencial);

    return $oMovimentacao;
  }

  /**
   * Retorna instancia da momiventacao pelo tipo
   *
   * @param integer $iTipoMovimentacao - tipo de movimentacao
   * @param integer $iCodigo           - sequencial da movimentacao
   * @static
   * @access public
   * @return mixed
   */
  public static function getInstancia( $iTipoMovimentacao, $iCodigo = null ) {
    
     switch ( $iTipoMovimentacao ) {

       case MovimentacaoAlvara::TIPO_LIBERACAO :
         
         require_once 'model/issqn/alvara/LiberacaoAlvara.model.php';
         $oMovimentacao = new LiberacaoAlvara($iCodigo);

       break;

       case MovimentacaoAlvara::TIPO_BAIXA :

         require_once 'model/issqn/alvara/BaixaAlvara.model.php';
         $oMovimentacao = new BaixaAlvara($iCodigo);

       break;

       case MovimentacaoAlvara::TIPO_CANCELAMENTO_LIBERACAO :
       case MovimentacaoAlvara::TIPO_CANCELAMENTO_BAIXA :
       case MovimentacaoAlvara::TIPO_CANCELAMENTO_RENOVACAO :
       case MovimentacaoAlvara::TIPO_CANCELAMENTO :

         require_once 'model/issqn/alvara/CancelamentoAlvara.model.php';
         $oMovimentacao = new CancelamentoAlvara($iCodigo);

       break;

       case MovimentacaoAlvara::TIPO_RENOVACAO :

         require_once 'model/issqn/alvara/RenovacaoAlvara.model.php';
         $oMovimentacao = new RenovacaoAlvara($iCodigo);

       break;

       case MovimentacaoAlvara::TIPO_TRANSFORMACAO :

         require_once 'model/issqn/alvara/TransformacaoAlvara.model.php';
         $oMovimentacao = new TransformacaoAlvara($iCodigo);

       break;
       
       default:
         throw new ParameterException("Tipo de movimentação de alvará inválida: " . $iTipoMovimentacao);
       break;

     }    

     $oMovimentacao->setTipoMovimentacao($iTipoMovimentacao);

     return $oMovimentacao;
  }

}