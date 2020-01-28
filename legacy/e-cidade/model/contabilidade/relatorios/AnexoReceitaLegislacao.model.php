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
 * A Classe retorna uma lista de receitas e a legislaзгo da mesma
 *
 */
class AnexoReceitaLegislacao extends RelatoriosLegaisBase {
  
  /**
   * Mйtodo Construtor
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
  }
  
  /**
   * Busca os dados necessбrios do relatуrio
   *
   * @return Array
   */
  public function getDados() {
    
    $sDataInicial     = "{$this->iAnoUsu}-01-01";
    $sDataFinal       = "{$this->iAnoUsu}-12-31";
    $sWhereReceita    = " o70_instit in ( {$this->getInstituicoes()} ) ";
    $sSqlReceita      = db_receitasaldo(11, 1, 3, true, 
                                        $sWhereReceita, 
                                        $this->iAnoUsu, 
                                        $sDataInicial, 
                                        $sDataFinal, true);
   
   /**
    * Adiciona a Query a busca pela Legislaзгo 
    */
   $sSQlReceitaLegislacao  = "Select receita.*, o57_finali                                                  ";
   $sSQlReceitaLegislacao .= "  from ({$sSqlReceita}) as receita                                            ";                                         
   $sSQlReceitaLegislacao .= "       inner join orcfontes  on orcfontes.o57_fonte  = receita.o57_fonte      ";
   $sSQlReceitaLegislacao .= "                            and orcfontes.o57_anousu = {$this->iAnoUsu}       ";
   
   $rsReceita = db_query($sSQlReceitaLegislacao); 
   $aReceita  = db_utils::getCollectionByRecord($rsReceita);

   $aReceitaRetorno = array();
   
   /**
    * Popula o Array de retorno com as variбveis necessбrias
    */
   foreach ($aReceita as $ind => $oReceitaLinha) {
    
     $oReceitaRetorno = new stdClass();
     
     $oReceitaRetorno->estrutural    = $oReceitaLinha->o57_fonte;
     $oReceitaRetorno->descricao     = $oReceitaLinha->o57_descr;
     $oReceitaRetorno->valorEstimado = $oReceitaLinha->saldo_inicial;
     $oReceitaRetorno->valorEstimado = $oReceitaLinha->saldo_inicial;
     $oReceitaRetorno->legislacao    = $oReceitaLinha->o57_finali;
     $oReceitaRetorno->codigoReceita = $oReceitaLinha->o70_codrec;
     

     $aReceitaRetorno[] =$oReceitaRetorno;
     
   }
   unset ($aReceita);
   return $aReceitaRetorno;
  }
}


?>