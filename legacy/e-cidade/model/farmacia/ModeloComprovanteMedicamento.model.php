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


/**
 * Controle dos modelos de comprovante de entrega de medicamento
 *
 */
class ModeloComprovanteMedicamento {

  protected $oComprovante = null;
  
  /**
   * @var IComprovanteMedicamento
   */
  protected $oModelo      = null;
  
  protected $iPorta       = 4444;
  
  protected $sIPTerminal  = '';
  public function __construct() {

    $this->sIPTerminal = $_SERVER['REMOTE_ADDR'];
    /**
     * verificamos se existe o terminal possui impressora cadastrada
     */  
    if ($oDadosImpressora = $this->getDadosImpressora()) {

      switch ($oDadosImpressora->modelo_comprovante) {
        
        case 1:
        case 2:
        case 3:
            $this->oModelo = new ModeloComprovanteMedicamentoPadrao();
          break;
      }
      
      $this->oModelo->setIPImpressora($oDadosImpressora->ipimpressora);
      $this->oModelo->setModeloImpressora($oDadosImpressora->modelo_impressora);
    } else {
      throw new Exception('Terminal sem impressora configurada.');
    }
  }
  
  public function imprimir(ComprovanteEntregaMedicamento $oComprovante) {
    $this->oModelo->imprimir($oComprovante);
  }
  
  /**
   * Retorna os dados da impressora do comprovante
   * @return mixed
   */
  public function getDadosImpressora() {
    
    $oDaoCfAutent    = db_utils::getDao("cfautent");
    $sWhere          = "k11_ipterm                       = '{$this->sIPTerminal}'";
    $sWhere         .= " and db66_db_tipomodeloimpressao = 4";
    $sSqlImpressora  = $oDaoCfAutent->sql_query_impressora_modelo_impressao(null,
                                                                          "k11_ipimpcheque as ipimpressora, 
                                                                           k11_tipoimp as modelo_impressora, 
                                                                           db66_sequencial as modelo_comprovante", 
                                                                           null, 
                                                                          $sWhere 
                                                                          );
    $rsImpressora  = $oDaoCfAutent->sql_record($sSqlImpressora);
    if ($oDaoCfAutent->numrows == 0) {
      return false;
    } else {

      $oDados = db_utils::fieldsMemory($rsImpressora, 0);
      return $oDados;
    }
  }
}

?>