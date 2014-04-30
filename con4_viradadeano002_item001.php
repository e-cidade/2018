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
  $iTotPassos = 7;
  db_atutermometro(0, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  // CONFIGURAวรO DE RELATORIOS
  // orcparamelemento
  $sqldeporigem = "select * from orcparamelemento where o44_anousu= $anoorigem limit 1";
  $resultdeporigem = db_query($sqldeporigem);
  $linhasdeporigem = pg_num_rows($resultdeporigem);

  $sqldepdestino = "select * from orcparamelemento where o44_anousu=  $anodestino limit 1";
  $resultdepdestino = db_query($sqldepdestino);
  $linhasdepdestino = pg_num_rows($resultdepdestino);

  if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
    
    $sqldepartorg = "select fc_duplica_exercicio('orcparamelemento', 'o44_anousu', ".$anoorigem.",".$anodestino.",null);";
    $resultdepartorg = db_query($sqldepartorg);
    if ($resultdepartorg==true) {
      $sqlerro = false;
    } else {
      $sqlerro   = true;
      $erro_msg .= pg_last_error($resultdepartorg); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
    }
    
  } else {
    if ($linhasdeporigem == 0) {
      $cldb_viradaitemlog->c35_log           = "Nใo existem registros na (orcparamelemento) para ano de origem $anoorigem";
    } else if ($linhasdepdestino>0) {
      $cldb_viradaitemlog->c35_log           = "Ja existem registros na (orcparamelemento) para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 903;
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
  
  if($sqlerro==false) {

    // orcparamfunc
    $sqldeporigem = "select * from  orcparamfunc where o45_anousu = $anoorigem limit 1";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from  orcparamfunc where o45_anousu = $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
      
      $sqldepartorg = "select fc_duplica_exercicio('orcparamfunc', 'o45_anousu', ".$anoorigem.",".$anodestino.",null);";
      $resultdepartorg = db_query($sqldepartorg);
      if ($resultdepartorg==true) {
        $sqlerro = false;
        
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdepartorg); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = " Nใo existem registros na (orcparamfunc) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = " Ja existem registros na (orcparamfunc) para ano de destino $anodestino";
      }
      $cldb_viradaitemlog->c35_codarq        = 1770;
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

  db_atutermometro(2, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  if($sqlerro==false) {
    // orcparamnivel
    $sqldeporigem = "select * from orcparamnivel where o44_anousu = $anoorigem limit 1";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from orcparamnivel where o44_anousu = $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
      
      $sqldepartorg = "select fc_duplica_exercicio('orcparamnivel', 'o44_anousu', ".$anoorigem.",".$anodestino.",null);";
      $resultdepartorg = db_query($sqldepartorg);
      if ($resultdepartorg==true) {
        $sqlerro = false;
        
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdepartorg); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = "Nใo existem registros na (orcparamnivel) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = "Ja existem registros na (orcparamnivel) para ano de destino $anodestino";
      }
      $cldb_viradaitemlog->c35_codarq        = 1550;
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

  db_atutermometro(3, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  if($sqlerro==false) {

    // orcparamrecurso
    $sqldeporigem = "select * from orcparamrecurso where o44_anousu = $anoorigem limit 1";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from orcparamrecurso where o44_anousu = $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
      
      $sqldepartorg = "select fc_duplica_exercicio('orcparamrecurso', 'o44_anousu', ".$anoorigem.",".$anodestino.",null);";
      $resultdepartorg = db_query($sqldepartorg);
      if ($resultdepartorg==true) {
        $sqlerro = false;
          
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdepartorg); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = "Nใo existem registros na (orcparamrecurso) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = "Ja existem registros na (orcparamrecurso) para ano de destino $anodestino";
      }
      $cldb_viradaitemlog->c35_codarq        = 1416;
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


  db_atutermometro(4, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  if($sqlerro==false) {
    // orcparamsubfunc
    $sqldeporigem = "select * from orcparamsubfunc where o44_anousu = $anoorigem limit 1";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from orcparamsubfunc where o44_anousu = $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
      $sqldepartorg = "select fc_duplica_exercicio('orcparamsubfunc', 'o44_anousu', ".$anoorigem.",".$anodestino.",null);";
      $resultdepartorg = db_query($sqldepartorg);
      if ($resultdepartorg==true) {
        $sqlerro = false;
        
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdepartorg); //"Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = "Nใo existem registros na (orcparamsubfunc) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = "Ja existem registros na (orcparamsubfunc) para ano de destino $anodestino";
      }
      $cldb_viradaitemlog->c35_codarq        = 1417;
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

  db_atutermometro(5, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);

  if($sqlerro==false) {
    //orcparamrecursoval
    $sqldeporigem = "select * from orcparamrecursoval where o48_anousu = $anoorigem ";
    $resultdeporigem = db_query($sqldeporigem);
    $linhasdeporigem = pg_num_rows($resultdeporigem);

    $sqldepdestino = "select * from orcparamrecursoval where o48_anousu =  $anodestino limit 1";
    $resultdepdestino = db_query($sqldepdestino);
    $linhasdepdestino = pg_num_rows($resultdepdestino);

    if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
      
      include("classes/db_orcparamrecursoval_classe.php");
      $cl_orcparamrecursoval = new cl_orcparamrecursoval;
      for ($v=0; $linhasdeporigem > $v; $v++) {
        db_fieldsmemory($resultdeporigem,$v);
        $cl_orcparamrecursoval->o48_grupo  = $o48_grupo;
        $cl_orcparamrecursoval->o48_anousu = $anodestino;
        $cl_orcparamrecursoval->o48_codrec = $o48_codrec;
        $cl_orcparamrecursoval->o48_instit = $o48_instit;
        $cl_orcparamrecursoval->o48_valor  = $o48_valor;
        $cl_orcparamrecursoval->incluir(null);
        if ($cl_orcparamrecursoval->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $cl_orcparamrecursoval->erro_msg;
          break;
        }
      }
      //for
      
    } else {
      if ($linhasdeporigem == 0) {
        $cldb_viradaitemlog->c35_log = "Nใo existem registros na (orcparamrecursoval) para ano de origem $anoorigem";
      } else if ($linhasdepdestino>0) {
        $cldb_viradaitemlog->c35_log = "Ja existem registros na (orcparamrecursoval) para ano de destino $anodestino";
      }
      $cldb_viradaitemlog->c35_codarq        = 1645;
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

  db_atutermometro(6, $iTotPassos, 'termometroitem', 1, $sMensagemTermometroItem);
}

?>