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
 * Model para tratar assinatura dos contratos
 * 
 * @package Contratos
 */
require_once("model/AcordoMovimentacao.model.php");
class AcordoAssinatura extends AcordoMovimentacao {

  /**
   * Tipo da Movimentaзгo
   *
   * @var integer
   */
	protected $iTipo               = 2;
	
  /**
   * Data do Movimento
   *
   * @var string
   */
  protected $dtMovimento         = '';
	
  /**
   * Cуdigo do Movimento de Cancelamento
   *
   * @var integer
   */
	protected $iCodigoCancelamento = 13;

  /**
   * Mйtodo construtor
   * 
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
  	
  	parent::__construct($iCodigo);
  }
  
  /**
   * Seta o tipo de acordo para a assinatura, alterado para protected para nao poder atribuir um novo valor
   * 
   * @param integer $iTipo
   */
  public function setTipo($iTipo) {
  	
  	$this->iTipo = 2;
  }
  
  /**
   * Seta a data da movimentaзгo
   * 
   * @param string $dtMovimento
   * @return AcordoAssinatura
   */
  public function setDataMovimento($dtMovimento = '') {

    $this->dtMovimento = $dtMovimento;
    return $this;
  }
  
  /**
   * Persiste os dados da Acordo Movimentacao na base de dados
   *
   * @return AcordoAssinatura
   */
  public function save() {
    
    parent::save();
    $iCodigoAcordo = $this->getAcordo();
    
    $oDaoAcordo                      = db_utils::getDao("acordo");
    $oDaoAcordo->ac16_sequencial     = $iCodigoAcordo;
    $oDaoAcordo->ac16_dataassinatura = $this->dtMovimento;
    $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
    if ($oDaoAcordo->erro_status == 0) {
      throw new Exception($oDaoAcordo->erro_msg);	
    }

    return $this;
  }
  
  /**
   * Cancela o movimento
   *
   * @return AcordoAssinatura
   */
  public function cancelar() {
  	
    parent::cancelar();
    $iCodigoAcordo = $this->getAcordo();
    
    $oDaoAcordo                      = db_utils::getDao("acordo");
    $oDaoAcordo->ac16_sequencial     = $iCodigoAcordo;
    $oDaoAcordo->ac16_dataassinatura = $this->dtMovimento;
    $oDaoAcordo->alterar($oDaoAcordo->ac16_sequencial);
    if ($oDaoAcordo->erro_status == 0) {
      throw new Exception($oDaoAcordo->erro_msg); 
    }

    return $this;
  }
}
?>