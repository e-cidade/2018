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

require_once ('classes/db_habitparametro_classe.php');
$cl_habitparametro = new cl_habitparametro();

if($sqlerro==false) {

	$iTotPassos = 2;
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // PARÂMETROS EDUCACAO
  $sqlorigem = "select * from habitparametro where ht16_anousu =  $anoorigem limit 1";
  $resultorigem = db_query($sqlorigem);
  $linhasorigem = pg_num_rows($resultorigem);

  $sqldestino = "select * from habitparametro where ht16_anousu = $anodestino limit 1";
  $resultdestino = db_query($sqldestino);
  $linhasdestino = pg_num_rows($resultdestino);

  if (($linhasorigem > 0) && ($linhasdestino == 0 )) {
    
		$sqlhabitparametro = $cl_habitparametro->sql_query_file("","*","","ht16_anousu = {$anoorigem}");
		$rshabitparametro  = $cl_habitparametro->sql_record($sqlhabitparametro);
		$iTotalLinhaInsert = pg_num_rows($rshabitparametro);
		for ($iLinhaInsert = 0; $iLinhaInsert < $iTotalLinhaInsert;  $iLinhaInsert++ ) {
		  db_fieldsmemory($rshabitparametro, $iLinhaInsert);

		  $cl_habitparametro->ht16_avaliacao            = $ht16_avaliacao;
		  $cl_habitparametro->ht16_receitapadrao        = $ht16_receitapadrao;
		  $cl_habitparametro->ht16_qtdparcelaspagamento = $ht16_qtdparcelaspagamento;
		  $cl_habitparametro->ht16_diaspadraopagamento  = $ht16_diaspadraopagamento;
		  $cl_habitparametro->ht16_mesescarencia        = $ht16_mesescarencia;
		  $cl_habitparametro->ht16_anousu               = $anodestino;
		  $cl_habitparametro->incluir($anodestino);
			if ($cl_habitparametro->erro_status == 0) {
	      $sqlerro   = true;
	      $erro_msg .= $cl_habitparametro->erro_msg;
	    }

		}

    $sqlhabitparametro = "select fc_duplica_exercicio('habitparametro', 'ht16_anousu', ".$anoorigem.",".$anodestino.",null);";
    
  } else {
    if ($linhasorigem == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem dados de parametros de habitacao para o exercicio $anoorigem";
    }
    if ($linhasdestino >0) {
      $cldb_viradaitemlog->c35_log = "Ja existem dados de parametros de habitacao para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 3013;
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