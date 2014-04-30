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

require_once("classes/db_avaliacaoestruturanota_classe.php");
require_once("classes/db_avaliacaoestruturaregra_classe.php");

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);
  
  /**
   * Buscamos os dados da tabela avaliacaoestruturanota a serem importados
   */
  $oDaoAvaliacaoEstruturaNota   = db_utils::getDao("avaliacaoestruturanota");
  $sWhereAvaliacaoEstruturaNota = "ed315_ano = {$iAnoOrigem}";
  $sSqlAvaliacaoEstruturaNota   = $oDaoAvaliacaoEstruturaNota->sql_query_file(
                                                                              null,
                                                                              "*",
                                                                              null,
                                                                              $sWhereAvaliacaoEstruturaNota
                                                                             );
  $rsAvaliacaoEstruturaNota      = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);
  $iLinhasAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->numrows;
  
  if ($iLinhasAvaliacaoEstruturaNota > 0) {
    
    for ($iContadorNota = 0; $iContadorNota < $iLinhasAvaliacaoEstruturaNota; $iContadorNota++) {
      
      $oDadosEstruturaNota                = db_utils::fieldsMemory($rsAvaliacaoEstruturaNota, $iContadorNota);
      $oDaoAvaliacaoEstruturaNotaMigracao = db_utils::getDao("avaliacaoestruturanota");
      
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_escola         = $oDadosEstruturaNota->ed315_escola;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_db_estrutura   = $oDadosEstruturaNota->ed315_db_estrutura;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ativo          = $oDadosEstruturaNota->ed315_ativo;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_arredondamedia = $oDadosEstruturaNota->ed315_arredondamedia;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_observacao     = $oDadosEstruturaNota->ed315_observacao;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ano            = $iAnoDestino;
      $oDaoAvaliacaoEstruturaNotaMigracao->incluir(null);
      $iCodigoAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNotaMigracao->ed315_sequencial;
      
      if ($oDaoAvaliacaoEstruturaNotaMigracao->erro_status == "0") {
        
        $sqlerro   = true;
        $erro_msg .= $oDaoAvaliacaoEstruturaNotaMigracao->erro_msg;
      }
      
      /**
       * Buscamos os dados da tabela avaliacaoestruturaregra, que tenham alguma configuracao de nota migrada, vinculada
       */
      $oDaoAvaliacaoEstruturaRegra   = db_utils::getDao("avaliacaoestruturaregra");
      $sWhereAvaliacaoEstruturaRegra = "ed318_avaliacaoestruturanota = {$oDadosEstruturaNota->ed315_sequencial}";
      $sSqlAvaliacaoEstruturaRegra   = $oDaoAvaliacaoEstruturaRegra->sql_query_file(
                                                                                    null,
                                                                                    "*",
                                                                                    null,
                                                                                    $sWhereAvaliacaoEstruturaRegra
                                                                                   );
      $rsAvaliacaoEstruturaRegra      = $oDaoAvaliacaoEstruturaRegra->sql_record($sSqlAvaliacaoEstruturaRegra);
      $iLinhasAvaliacaoEstruturaRegra = $oDaoAvaliacaoEstruturaRegra->numrows;
      
      if ($iLinhasAvaliacaoEstruturaRegra > 0) {
        
        for ($iContadorRegra = 0; $iContadorRegra < $iLinhasAvaliacaoEstruturaRegra; $iContadorRegra++) {
          
          $oDadosEstruturaRegra                = db_utils::fieldsMemory($rsAvaliacaoEstruturaRegra, $iContadorRegra);
          $oDaoAvaliacaoEstruturaRegraMigracao = db_utils::getDao("avaliacaoestruturaregra");
          
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_avaliacaoestruturanota = $iCodigoAvaliacaoEstruturaNota;
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_regraarredondamento    = $oDadosEstruturaRegra->ed318_regraarredondamento;
          $oDaoAvaliacaoEstruturaRegraMigracao->incluir(null);
          
          if ($oDaoAvaliacaoEstruturaRegraMigracao->erro_status == "0") {
            
            $sqlerro   = true;
            $erro_msg .= $oDaoAvaliacaoEstruturaRegraMigracao->erro_msg;
          }
          
          unset($oDadosEstruturaRegra);
          unset($oDaoAvaliacaoEstruturaRegraMigracao);
        }
      } else {
    
        if ($iLinhasAvaliacaoEstruturaRegra == 0) {
          
          $cldb_viradaitemlog->c35_log  = "Não existe regra de arredondamento vinculada a configuração da nota para";
          $cldb_viradaitemlog->c35_log .= " migração para o ano de destino $iAnoDestino";
        }
        
        $cldb_viradaitemlog->c35_codarq        = 893;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        
        if ($cldb_viradaitemlog->erro_status == 0) {
          
          $sqlerro   = true;
          $erro_msg .= $cldb_viradaitemlog->erro_msg;
        }
      }
      
      unset($oDadosEstruturaNota);
      unset($oDaoAvaliacaoEstruturaNota);
    }
  } else {
    
    if ($iLinhasAvaliacaoEstruturaRegra == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem configurações de nota para migração para o ano de destino $iAnoDestino";
    }
    
    $cldb_viradaitemlog->c35_codarq        = 893;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    
    if ($cldb_viradaitemlog->erro_status == 0) {
      
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
  
  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}
?>