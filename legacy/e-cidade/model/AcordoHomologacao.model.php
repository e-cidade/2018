<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * Model extends metodos e propriedades do model AcordoMovimentacao
 * 
 * @package Contratos
 */

require_once("model/AcordoMovimentacao.model.php");
class AcordoHomologacao extends AcordoMovimentacao {

  /**
   * Tipo da Movimentaзгo
   *
   * @var integer
   */
	protected $iTipo               = 11;
	
  /**
   * Cуdigo do Movimento de Cancelamento
   *
   * @var integer
   */
	protected $iCodigoCancelamento = 12;

  /**
   * Mйtodo construtor
   * 
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
  	
  	parent::__construct($iCodigo);
  }
  
  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @return AcordoHomologacao
   */
  public function save() {
  	
  	parent::save();
  	$oDaoAcordoMovimentacao = db_utils::getDao("acordomovimentacao");
  	$oDaoAcordo             = db_utils::getDao("acordo");
    /**
     * Acerta movimentacao corrente para alterar um movimento anterior
     */
    $sCampos                    = "ac10_sequencial, ac10_acordomovimentacaotipo, ";
    $sCampos                   .= "ac10_acordo, ac09_acordosituacao              ";
    $sWhere                     = "ac10_sequencial = {$this->iCodigo}            ";
    $sOrderBy                   = "ac10_sequencial desc limit 1                  ";
    $sSqlAcordoMovimentacao     = $oDaoAcordoMovimentacao->sql_query_acertaracordo(null, $sCampos, $sOrderBy, $sWhere);
    $rsSqlAcordoMovimentacao    = db_query($sSqlAcordoMovimentacao);
    $iNumRowsAcordoMovimentacao = pg_num_rows($rsSqlAcordoMovimentacao);
    if ($iNumRowsAcordoMovimentacao > 0) {
      
      /**
       * Altera situacao do movimento
       */
      $oAcordoMovimentacao             = db_utils::fieldsMemory($rsSqlAcordoMovimentacao, 0);
      $oDaoAcordo->ac16_sequencial     = $oAcordoMovimentacao->ac10_acordo;
      $oDaoAcordo->ac16_acordosituacao = $oAcordoMovimentacao->ac09_acordosituacao;
      $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
      if ($oDaoAcordo->erro_status == 0) {
        throw new Exception($oDaoAcordo->erro_msg);
      }
    }
  	return $this;
  }
  
  /**
   * Seta o tipo de acordo para a movimentaзгo, alterado para protected para nao poder atribuir um novo valor
   * 
   * @param integer $iTipo
   */
  public  function setTipo($iTipo) {
  	$this->iTipo = 11;
  }
  
  /**
   * Cancela o movimento
   *
   * @return AcordoHomologacao
   */
  public function cancelar() {
  	
  	parent::cancelar();
  	return $this;
  }
}
?>