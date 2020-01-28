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
 * Modelo para controle das solicita��es de transfer�ncia de material
 */
class SolicitacaoMaterial {
	
	/**
	 * Codigo da solicita��o
	 *
	 * @var unknown_type
	 */
	
	private $icodSol = null;
	
	/**
	 * 
	 * Cole��o dos itens da solicita��o
	 *
	 * @var object
	 */
	private $oDadosSolicitacao = null;
	
	/**
	 * Objeto com as propriedades da solicita��o
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
	 * seta a propriedade oDadosSolicitacao os dados da solicita��o
	 * @return object false em caso de erro
	 */
	function getDados() {
		$oDaoMatSolicitacao = db_utils::getDao ( "matpedido" );
		$sSqlMatPedido = $oDaoMatSolicitacao->sql_query_almox ( $this->icodSol );
		$rsMatPedido = $oDaoMatSolicitacao->sql_record ( $sSqlMatPedido );
		if ($oDaoMatSolicitacao->numrows == 1) {
			$this->oDadosSolicitacao = db_utils::fieldsMemory ( $rsMatPedido, 0, false, false, $this->getEncode () );
			return true;
		} else {
			return false;
		}
	}
	/**
	 * Retorna os items da solicita��o
	 *
	 * @param integer [$iCodItem]C�digo do item na solicita��o se passado, busca apenas o item.
	 * @return array|object
	 */
	public function getItens($iCodItem = null, $lMotivo = true) {
		
		$sWhere = '';
		if ($iCodItem != '') {
			$sWhere = " and m98_sequencial = {$iCodItem}";
		}
		
		$oDaoSolicitacaoItens = db_utils::getDao ( "matpedidoitem" );
		$sCampos = "   distinct m98_sequencial,m98_matmater,matmater.m60_descr,m61_descr,m98_quant,m70_quant as qtdeestoque, ";
		$sCampos .= "  (select coalesce(sum(m82_quant),0) from matestoqueinimei as matinimei ";
		$sCampos .= "    inner join matestoqueinimeimatpedidoitem as q on q.m99_matestoqueinimei = matinimei.m82_codigo ";
		$sCampos .= "   where q.m99_matpedidoitem = m98_sequencial ) as totalAtendido,";
		$sCampos .= "  coalesce(m98_quant  ";
		$sCampos .= "                    - ";
		$sCampos .= "           (select coalesce(sum(m82_quant),0) ";
		$sCampos .= "            from matestoqueinimei as matinimei ";
		$sCampos .= "             inner join matestoqueinimeimatpedidoitem as q on q.m99_matestoqueinimei = matinimei.m82_codigo ";
		$sCampos .= "            where q.m99_matpedidoitem = m98_sequencial) ";
		$sCampos .= "           - ";
		$sCampos .= "           (select coalesce (sum(m103_quantanulada),0) ";
		$sCampos .= "            from matanulitem ";
		$sCampos .= "            inner join matanulitempedido on matanulitempedido.m101_matanulitem =matanulitem.m103_codigo ";
		$sCampos .= "           where  m101_matpedidoitem = m98_sequencial) ";
		$sCampos .= "          ,0) as qtdpendente, ";
		$sCampos .= "  (select coalesce(sum(m103_quantanulada),0) ";
		$sCampos .= "   from matanulitem ";
		$sCampos .= "    inner join matanulitempedido on matanulitempedido.m101_matanulitem =matanulitem.m103_codigo ";
		$sCampos .= "   where  m101_matpedidoitem = m98_sequencial) as qtdanulada ";
		$sGroupBy = " group by m98_sequencial, m98_matmater,matmater.m60_descr,m61_descr,m98_quant,totalAtendido,m70_quant,m99_matestoqueinimei ";
		$sSqlsolItens = $oDaoSolicitacaoItens->sql_query_estoque ( null, $sCampos, null, " m70_coddepto = m91_depto and matpedidoitem.m98_matpedido = " . $this->getIcodSol () . " {$sWhere} $sGroupBy" );
		//die("SQL: $sSqlsolItens");
		$rsSolItem = $oDaoSolicitacaoItens->sql_record ( $sSqlsolItens );
		
		$aItensSolicitacao = array ();
		
		if ($oDaoSolicitacaoItens->numrows > 0) {
			
			for($iInd = 0; $iInd < $oDaoSolicitacaoItens->numrows; $iInd ++) {
				
				$aItensSolicitacao [] = db_utils::fieldsMemory ( $rsSolItem, $iInd, false, false, $this->getEncode () );
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