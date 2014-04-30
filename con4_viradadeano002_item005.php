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

// Para garantir que nao houve erros em outros itens
if ($sqlerro == false) {

  //PARÂMETROS AGUA";  
  $oDaoAguaConsumo    = db_utils::getDao("aguaconsumo");
  $oDaoAguaConsumoRec = db_utils::getDao("aguaconsumorec");

  $sqlconsumo    = $oDaoAguaConsumo->sql_query_file(null, "*", "x19_codconsumo", "x19_exerc = {$anoorigem}");
  $resultconsumo = $oDaoAguaConsumo->sql_record($sqlconsumo);
  if ($oDaoAguaConsumo->numrows == 0) {
    
    $cldb_viradaitemlog->c35_log           = "Sem registros para efetuar virada ";
    $cldb_viradaitemlog->c35_codarq        = 1441;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status == 0) {
      
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  } else {
    
    $total_reg = $oDaoAguaConsumo->numrows;
    
    for ($x = 0; $x < $total_reg; $x++) {
      
      db_fieldsmemory($resultconsumo, $x);
      db_atutermometro($x, $total_reg, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 1/2)");
      
      if ($sqlerro == false) {
        
        $oDaoAguaConsumo->x19_exerc       = $anodestino;
        $oDaoAguaConsumo->x19_areaini     = $x19_areaini;
        $oDaoAguaConsumo->x19_areafim     = $x19_areafim;
        $oDaoAguaConsumo->x19_caract      = $x19_caract;
        $oDaoAguaConsumo->x19_conspadrao  = $x19_conspadrao;
        $oDaoAguaConsumo->x19_descr       = "{$x19_descr}";
        $oDaoAguaConsumo->x19_ativo       = ($x19_ativo=='t'?'true':'false');
        $oDaoAguaConsumo->x19_zona        = $x19_zona;
        $oDaoAguaConsumo->incluir(null);
        if ($oDaoAguaConsumo->erro_status == 0) {
          
          $sqlerro   = true;
          $erro_msg .= $oDaoAguaConsumo->erro_msg;
          break;
        }
        
        $nextaguaconsumo = $oDaoAguaConsumo->x19_codconsumo;
      }
      
      $sqlaguaconsumorec = $oDaoAguaConsumoRec->sql_query_file($x19_codconsumo, null, "*", null, "");
      $resultconsumorec  = $oDaoAguaConsumoRec->sql_record($sqlaguaconsumorec);
      $rowsconsumorec    = $oDaoAguaConsumoRec->numrows;
      
      if ($rowsconsumorec > 0) {
        
        $percentual = 0;
        for ($y = 0; $y < $rowsconsumorec; $y++) {
          
          db_fieldsmemory($resultconsumorec, $y);
          db_atutermometro($y, $rowsconsumorec, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 2/2)");

          $x20_valor = $percentual <> 0 ? round($x20_valor + ($x20_valor * ($percentual/100)), 2) : $x20_valor;

          if ($sqlerro == false) {
            
            $oDaoAguaConsumoRec->x20_codconsumo     = $nextaguaconsumo;
            $oDaoAguaConsumoRec->x20_codconsumotipo = $x20_codconsumotipo;
            $oDaoAguaConsumoRec->x20_valor          = $x20_valor; 
            $oDaoAguaConsumoRec->incluir($oDaoAguaConsumoRec->x20_codconsumo, $oDaoAguaConsumoRec->x20_codconsumotipo);
            if ($oDaoAguaConsumoRec->erro_status == 0) {
              
              $sqlerro   = true;
              $erro_msg .= $oDaoAguaConsumoRec->erro_msg;
              break;
            }
          }
        }
      }
      
      if ($sqlerro == true) {
        break;
      }
    }
  }
}
?>