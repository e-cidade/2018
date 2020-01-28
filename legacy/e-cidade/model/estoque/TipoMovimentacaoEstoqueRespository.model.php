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

/**
 * Repositório para o Tipo de Movimentação do Estoque
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package patrimonio
 * @subpackage material
 * @version $Revision: 1.1 $
 */
class TipoMovimentacaoEstoqueRespository {

  /**
   * Coleção com os tipos de movimentação do estoque
   * @var TipoMovimentacaoEstoque[]
   */
  private $aTipoMovimentacao = array();

  /**
   * Instancia de TipoMovimentacaoEstoqueRespository
   * @var TipoMovimentacaoEstoqueRespository
   */
  private static $oInstancia;

  /**
   * Método privado construtor
   */
  private function __construct() {}

  /**
   * Método mágico privado __clone
   */
  private function __clone() {}


  /**
   * Retorna a instancia de TipoMovimentacaoEstoqueRespository
   * @return TipoMovimentacaoEstoqueRespository
   */
  protected function getInstancia() {

    if(self::$oInstancia == null) {
      self::$oInstancia = new TipoMovimentacaoEstoqueRespository();
    }
    return self::$oInstancia;
  }

  /**
   * Retorna o tipo de movimentação do estoque de acordo com o código informado
   * @param $iCodigoMovimentacao
   * @return TipoMovimentacaoEstoque
   */
  public static function getTipoMovimentaoPorCodigo($iCodigoMovimentacao) {

    if ( ! array_key_exists($iCodigoMovimentacao, TipoMovimentacaoEstoqueRespository::getInstancia()->aTipoMovimentacao)) {
      TipoMovimentacaoEstoqueRespository::getInstancia()->aTipoMovimentacao[$iCodigoMovimentacao] = new TipoMovimentacaoEstoque($iCodigoMovimentacao);
    }
    return TipoMovimentacaoEstoqueRespository::getInstancia()->aTipoMovimentacao[$iCodigoMovimentacao];
  }
}