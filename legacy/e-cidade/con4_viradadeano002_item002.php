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
  $iTotPassos = 2;
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // DEPARTAMENTO
  $sqldeporigem = "select * from db_departorg where db01_anousu = $anoorigem limit 1";
  $resultdeporigem = db_query($sqldeporigem);
  $linhasdeporigem = pg_num_rows($resultdeporigem);

  $sqldepdestino = "select * from db_departorg where db01_anousu = $anodestino limit 1";
  $resultdepdestino = db_query($sqldepdestino);
  $linhasdepdestino = pg_num_rows($resultdepdestino);

  if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
    
    $sqldepartorg = "select fc_duplica_exercicio('db_departorg', 'db01_anousu', ".$anoorigem.",".$anodestino.",null);";
    $resultdepartorg = db_query($sqldepartorg);
    if ($resultdepartorg==true) {
      $sqlerro = false;
    } else {
      $sqlerro = true;
      $erro_msg = "Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
    }
    
  } else {
    if ($linhasdeporigem == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem departamentos para ano de origem $anoorigem";
    } else if ($linhasdepdestino>0) {
      $cldb_viradaitemlog->c35_log = "Ja existem departamentos para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 507;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro = true;
      $erro_msg = $cldb_viradaitemlog->erro_msg;
    }
    
  }

  db_atutermometro(1, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);
}

?>