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
if($sqlerro==false) {

  // CALENDARIO DE SABADOS/DOMINGOS/FERIADOS
  include_once("classes/db_calend_classe.php");

  $clcalend = new cl_calend;

  $linhascalend = 0;

  // PROCESSA SABADOS/DOMINGOS
  for ($i = 1; $i <= 12; $i++) {
    db_atutermometro($i-1, 12, 'termometroitem', 1, $sMensagemTermometroItem . " Passo (1/2)");

    $totdia = date("t", mktime(0, 0, 0, $i, 1, $anodestino));
    for ($j = 1; $j <= $totdia; $j++) {
      $data = mktime(0, 0, 0, $i, $j, $anodestino);
      if (date("w", $data) == "0" || date("w", $data) == "6") {
        $result = $clcalend->sql_record($clcalend->sql_query_file(date("Y-m-d",$data)));
        if ($clcalend->numrows == 0) {
          $clcalend->incluir(date("Y-m-d",$data));
          if ($clcalend->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $clcalend->erro_msg;
            break;
          }
          $linhascalend++;
        }
      }
    }
    if($sqlerro==true) {
      break;
    }

  }

  // PROCESSA FERIADOS NACIONAIS
  if($sqlerro==false) {
    $aFeriados = array();
    $aFeriados[] = "01/01";
    $aFeriados[] = "21/04";
    $aFeriados[] = "01/05";
    $aFeriados[] = "07/09";
    $aFeriados[] = "12/10";
    $aFeriados[] = "15/11";
    $aFeriados[] = "25/12";

    $iCountFeriados = sizeof($aFeriados);
    for ($i = 0; $i < $iCountFeriados; $i++) {
      db_atutermometro($i, $iCountFeriados, 'termometroitem', 1, $sMensagemTermometroItem . " Passo (2/2)");
      list($dia, $mes) = split("/", $aFeriados[$i]); 
      $data  = "{$anodestino}-{$mes}-{$dia}";
      $result = $clcalend->sql_record($clcalend->sql_query_file($data));
      if ($clcalend->numrows == 0) {
        $clcalend->incluir($data);
        if ($clcalend->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $clcalend->erro_msg;
          break;
        }
        $linhascalend++;
      }
    }
  }

  if($sqlerro==false) {
    if($linhascalend == 0) {
      $cldb_viradaitemlog->c35_log           = "Sábados, Domingos e Feriados Nacionais já processados para o exercicio $anodestino";
      $cldb_viradaitemlog->c35_codarq        = 86;
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);
      if ($cldb_viradaitemlog->erro_status==0) {
        $sqlerro   = true;
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
      }
    }
  }

}

?>