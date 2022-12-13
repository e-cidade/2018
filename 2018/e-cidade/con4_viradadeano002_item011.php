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

  //RECEITAS EXTRA-ORÇAMENTÁRIAS";
  include("classes/db_tabplan_classe.php");
  $cl_tabplan = new cl_tabplan;
  $sqldeporigem = "select * from tabplan where k02_anousu = $anoorigem ";
  $resultdeporigem = db_query($sqldeporigem);
  $linhasdeporigem = pg_num_rows($resultdeporigem);

  $sqldepdestino = "select * from tabplan where k02_anousu = $anodestino limit 1";
  $resultdepdestino = db_query($sqldepdestino);
  $linhasdepdestino = pg_num_rows($resultdepdestino);

  if (($linhasdeporigem > 0) && ($linhasdepdestino == 0 )) {
    
    $reduz = "";
    $vir   = "";
    
    for ($a=0; $linhasdeporigem>$a; $a++) {
      db_fieldsmemory($resultdeporigem,$a);
      db_atutermometro($a, $linhasdeporigem, 'termometroitem', 1, $sMensagemTermometroItem);
      $sqlexe = "select c62_reduz from conplanoexe where c62_anousu = $anodestino and c62_reduz = $k02_reduz";
      $resultexe = db_query($sqlexe);
      $linhasexe = pg_num_rows($resultexe);
      
      if ($linhasexe==0) {
        $reduz .= $vir.$k02_reduz;
        $vir    = ", ";
      } else {
        //inclui na tabplan
        $cl_tabplan->k02_anousu = $anodestino;
        $cl_tabplan->k02_codigo = $k02_codigo;
        $cl_tabplan->k02_reduz  = $k02_reduz;
        $cl_tabplan->k02_estpla = $k02_estpla;
        $cl_tabplan->incluir($k02_codigo,$anodestino);
        if ($cl_tabplan->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $cl_tabplan->erro_msg;
          break;
        }
      }
    }

    if($sqlerro==false) {
      if ($reduz != "") {
        $cldb_viradaitemlog->c35_log = "Os reduzidos($reduz) não estão presentes no cadastro de contas do exercício (tabela conplanoexe) para $anodestino, e por este motivo não foram duplicados. Para cadastra-los, acesse o menu Contabilidade/cadastro/contas de exercício/inclusão";
        $cldb_viradaitemlog->c35_codarq        = 77;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        if ($cldb_viradaitemlog->erro_status==0) {
          $sqlerro = true;
          $erro_msg = $cldb_viradaitemlog->erro_msg;
          db_msgbox($erro_msg);
        }
        
      }
    } 
  } else {
    
    if ($linhasdeporigem == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem registros na (tabplan) para ano de origem $anoorigem";
    } else if ($linhasdepdestino>0) {
      $cldb_viradaitemlog->c35_log = "Ja existem registros na (tabplan) para ano de destino $anodestino";
    }
    $cldb_viradaitemlog->c35_codarq        = 77;
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

?>