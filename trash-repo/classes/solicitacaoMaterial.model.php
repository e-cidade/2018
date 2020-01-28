<?php
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
 * Modelo para controle das solicitações de transferência de material
 */
class SolicitacaoMaterial {

  /**
   * Codigo da solicitação
   *
   * @var unknown_type
   */
  
  
  private $icodSol = null;


  /**
   * 
   * Coleção dos itens da solicitação
   *
   * @var object
   */
  private $oDadosSolicitacao = null;

  /**
   * Objeto com as propriedades da solicitação
   *
   * @var object
   */
  public $oItensSolicitacao = null;

  /**
   * Se e aplicado urlencode nas strings
   *
   * @var boolean
   */
  private $lEncode = false;
  /**
   * Metodo Construtor
   */
  function __construct($iCodSol) {

    $this->icodSol = $iCodSol;
  
  }

  /**
   * @return integer
   */
  public function getIcodSol() {

    return $this->icodSol;
    
  }
  
  function setEncode($lEncode) {

    $this->lEncode = $lEncode;
    
  }
  
  function getEncode() {

    return $this->lEncode;
    
  }
  /**
   * seta a propriedade oDadosSolicitacao os dados da solicitação
   * @return object false em caso de erro
   */
  function getDados() {
  	
    $oDaoMatSolicitacao = db_utils::getDao("matpedido");
    $sSqlMatPedido      = $oDaoMatSolicitacao->sql_query_almox($this->icodSol);
    $rsMatPedido        = $oDaoMatSolicitacao->sql_record($sSqlMatPedido);
    
    if ($oDaoMatSolicitacao->numrows == 1) {
    	
      $this->oDadosSolicitacao = db_utils::fieldsMemory($rsMatPedido, 0, false, false, $this->getEncode());
      return true;
      
    } else {
      return false;
    }
  }
  /**
   * Retorna os items da solicitação
   *
   * @param integer [$iCodItem]Código do item na solicitação se passado, busca apenas o item.
   * @return array|object
   */
  public function getItens($iCodItem = null) {
  	
    $sWhere = '';
    if ($iCodItem != '') {
      $sWhere = " and m98_sequencial = {$iCodItem}";
    }
    $oDaoSolicitacaoItens = db_utils::getDao("matpedidoitem");
    $sCampos              = " distinct a.m98_sequencial,a.m98_matmater,matmater.m60_descr,m61_descr,a.m98_quant, ";
    $sCampos             .= "            m70_quant as qtdeestoque, ";
    $sCampos             .= "  (select coalesce(sum(m82_quant),0) "; 
    $sCampos             .= "          from matestoqueinimei as matinimei ";
    $sCampos             .= "          inner join matestoqueinimeimatpedidoitem as q on q.m99_matestoqueinimei = ";
    $sCampos             .= "                     matinimei.m82_codigo ";
    $sCampos             .= "          where q.m99_matpedidoitem = a.m98_sequencial ) as totalAtendido, ";
    $sCampos             .= "          coalesce(a.m98_quant ";
    $sCampos             .= "                   - ";
    $sCampos             .= "         (select coalesce(sum(m82_quant),0) "; 
    $sCampos             .= "                  from matestoqueinimei as matinimei ";
    $sCampos             .= "                  inner join matestoqueinimeimatpedidoitem as q on q.m99_matestoqueinimei = ";
    $sCampos             .= "                             matinimei.m82_codigo ";
    $sCampos             .= "                  where  q.m99_matpedidoitem = a.m98_sequencial) ";
    $sCampos             .= "                          - ";
    $sCampos             .= "                         (select coalesce (sum(m103_quantanulada),0) "; 
    $sCampos             .= "                                 from matanulitem  ";
    $sCampos             .= "                                 inner join matanulitempedido on matanulitempedido.m101_matanulitem = ";
    $sCampos             .= "                                            matanulitem.m103_codigo "; 
    $sCampos             .= "                                 inner join matpedidoitem on matpedidoitem.m98_sequencial= ";
    $sCampos             .= "                                            matanulitempedido.m101_matpedidoitem "; 
    $sCampos             .= "                                 where  m101_matpedidoitem = a.m98_sequencial) ";
    $sCampos             .= "               ,0) as qtdpendente,     ";
    $sCampos             .= "             (select coalesce(sum(m103_quantanulada),0) ";
    $sCampos             .= "                     from matanulitem  ";
    $sCampos             .= "                     inner join matanulitempedido on  ";
    $sCampos             .= "                                matanulitempedido.m101_matanulitem = matanulitem.m103_codigo "; 
    $sCampos             .= "                     inner join matpedidoitem on matpedidoitem.m98_sequencial =  ";
    $sCampos             .= "                                matanulitempedido.m101_matpedidoitem "; 
    $sCampos             .= "                     where  m101_matpedidoitem = a.m98_sequencial) as quantanulada";
    $sGroupBy             = " group by a.m98_sequencial, a.m98_matmater,matmater.m60_descr,m61_descr,a.m98_quant, ";
    $sGroupBy            .= " totalAtendido,m70_quant,m99_matestoqueinimei";   
    $sSqlsolItens         = $oDaoSolicitacaoItens->sql_query_estoque(null,  
                                                                     $sCampos,
                                                                     null,
                                                                     "matpedidoitem.m98_matpedido = " . 
                                                                     $this->getIcodSol(). 
                                                                     " {$sWhere} $sGroupBy");                                                            
    $rsSolItem            = $oDaoSolicitacaoItens->sql_record($sSqlsolItens);
 
    $aItensSolicitacao = array ();
    
    if ($oDaoSolicitacaoItens->numrows>0) {	
    
      for ($iInd = 0; $iInd < $oDaoSolicitacaoItens->numrows; $iInd ++) {        
        $aItensSolicitacao [] = db_utils::fieldsMemory($rsSolItem, $iInd, false, false, $this->getEncode());		
      }
      if ($iCodItem != '') {
        return $aItensSolicitacao [0];		
      } else {
        return $aItensSolicitacao;    
      }
      
    } else {    	
      return false;
    }
  
  }
  
  public function getInfo() {

  return $this->oDadosSolicitacao;
    
  }
}

?>
