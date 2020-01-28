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

// Para garantir que nao houve erros em outros itens

require_once ('classes/db_protprocessonumeracao_classe.php');
$cl_protprocessonumeracao = new cl_protprocessonumeracao();

if($sqlerro==false) {

	$iTotPassos = 2;
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // PARÂMETROS PROTOCOLO
  $sqlorigem = "select * from protprocessonumeracao where p07_ano = $anoorigem limit 1";
  $resultorigem = db_query($sqlorigem);
  $linhasorigem = pg_num_rows($resultorigem);

  $sqldestino = "select * from protprocessonumeracao where p07_ano = $anodestino limit 1";
  $resultdestino = db_query($sqldestino);
  $linhasdestino = pg_num_rows($resultdestino);

  $sqlparamglobal = "select * from protparamglobal";
  $resultparamglobal = db_query($sqlparamglobal);
  $linhasparamglobal = pg_num_rows($resultparamglobal);

  if (($linhasorigem > 0) && ($linhasdestino == 0 ) and ($linhasparamglobal > 0) ) {
    db_fieldsmemory($resultparamglobal,0);

		$sqlprotprocessonumeracao = $cl_protprocessonumeracao->sql_query_file("","*","","p07_ano = {$anoorigem}");
		$rsprotprocessonumeracao  = $cl_protprocessonumeracao->sql_record($sqlprotprocessonumeracao);
		$iTotalLinhaInsert = pg_num_rows($rsprotprocessonumeracao);
		for ($iLinhaInsert = 0; $iLinhaInsert < $iTotalLinhaInsert;  $iLinhaInsert++ ) {
		  db_fieldsmemory($rsprotprocessonumeracao, $iLinhaInsert);

		  $cl_protprocessonumeracao->p07_instit = $p07_instit;
		  $cl_protprocessonumeracao->p07_ano    = $anodestino;
      if ($p06_tipo == 1) { // geral
		    $p07_proximonumero++;
      } elseif ($p06_tipo == 2) { // por ano
		    $p07_proximonumero = 1;
      }
      $cl_protprocessonumeracao->p07_proximonumero = $p07_proximonumero;
		  $cl_protprocessonumeracao->incluir(null);
			if ($cl_protprocessonumeracao->erro_status == 0) {
	      $sqlerro   = true;
	      $erro_msg .= $cl_protprocessonumeracao->erro_msg;
	    }

		}

  } else {
    if ($linhasorigem == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem dados de parametros de protocolo para o exercicio $anoorigem";
    } elseif ($linhasdestino >0) {
      $cldb_viradaitemlog->c35_log = "Ja existem dados de parametros de protocolo para ano de destino $anodestino";
    } elseif ($linhasparamglobal == 0) {
      $cldb_viradaitemlog->c35_log = "Parametros globais de numeracao nao definidos";
    }
    $cldb_viradaitemlog->c35_codarq        = 3216;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
  
  db_atutermometro(1, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

}

?>