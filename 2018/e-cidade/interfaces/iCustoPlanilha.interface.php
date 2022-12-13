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
 *Interface pra  processamento de planilhas de custos
 */
interface iCustoPlanilha {
  
  /**
   * Processa dos dados da planilha d
   *
   * @param integer $iMesBase mes base para processamento
   * @param integer $iAnoBase ano base para  processamento
   */
  function processarDados($iMesBase, $iAnoBase);
  
  
  /**
   * Define filtros para os metodos de retorno
   *
   * @param string $sWhere string com filtro para  os metodos de retorno de informacoes
   * @return void
   */
  function setFilter($sWhere);
  
  /**
   * Retorna os custos da planilha
   *
   */
  function getCustos();
  
  /**
   * Adiciona um custo a planilha
   *
   * @param custoPlanilhaLinha $oCusto custo
   */
  function addCusto(custoPlanilhaLinha $oCusto); 
}

?>