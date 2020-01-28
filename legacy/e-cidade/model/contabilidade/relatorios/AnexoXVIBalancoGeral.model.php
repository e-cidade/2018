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

require_once ("model/contabilidade/relatorios/RelatoriosLegaisBase.model.php");

/**
 * classe para controle dos valores do Anexo XVI do balanço Geral
 * @package    contabilidade
 * @subpackage relatorios
 * @author Iuri Guncthnigg
 * 
 */

class AnexoXVIBalancoGeral extends RelatoriosLegaisBase  {
  
  
  /**
   * @param integer $iAnoUsu ano de emissao do relatorio
   * @param integer $iCodigoRelatorio codigo do relatorio
   * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
   */
  function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
     parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  /**
   * retorna os dados da classe em forma de objeto.
   * o objeto de retorno tera a seguinte forma:
   * 
   * @return array - Colecao de stdClass
   */
  public function getDados() {
  	
     $aRetorno        = array();
     $oLinhaRelatorio = new linhaRelatorioContabil($this->iCodigoRelatorio, 1);
     $oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
     $aValoresColunasLinhas = $oLinhaRelatorio->getValoresColunasInstituicoes(null, null,
                                                                              $this->getInstituicoes(), 
                                                                              $this->iAnoUsu);
     foreach($aValoresColunasLinhas as $oValor) {
       
       $oRegistro                             = new stdClass();
       $oRegistro->lei                        = $oValor->colunas[0]->o117_valor;
       $oRegistro->quantidadedata             = $oValor->colunas[1]->o117_valor;
       $oRegistro->valoremissao               = $oValor->colunas[2]->o117_valor;
       $oRegistro->saldoanterior              = $oValor->colunas[3]->o117_valor;
       $oRegistro->correcaomonetaria          = $oValor->colunas[4]->o117_valor;
       $oRegistro->resgate                    = $oValor->colunas[5]->o117_valor;
       $oRegistro->quantidadeproximoexercicio = $oValor->colunas[6]->o117_valor;
       $oRegistro->valorproximoexercicio      = $oValor->colunas[7]->o117_valor;
       array_push($aRetorno, $oRegistro);
     }
     return $aRetorno;    
  }
}