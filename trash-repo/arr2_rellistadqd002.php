<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 
  require_once("fpdf151/pdf.php");
  require_once("libs/db_sql.php");
  require_once("libs/db_stdlib.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  require_once("dbforms/db_classesgenericas.php");
  require_once("libs/db_utils.php");
  require_once("classes/db_declaracaoquitacao_classe.php");

  $oGet         = db_utils::postMemory($_GET);
  
  $clDeclaracaoQuitacao = new cl_declaracaoquitacao();
  
  $dDataInicial = isset($oGet->datainicial) ? $oGet->datainicial : ''; 
  $dDataFinal   = isset($oGet->datafinal)   ? $oGet->datafinal   : '';
  $sStatus      = isset($oGet->status)      ? $oGet->status      : ''; /* todas/ativa/inativa/auto */
  $sOrigem      = isset($oGet->origem)      ? $oGet->origem      : ''; /* matric/inscr/cgm/... */
  $iExercicio   = isset($oGet->exercicio)   ? $oGet->exercicio   : ''; 
  $sTipo        = isset($oGet->tipo)        ? $oGet->tipo        : ''; /* analitico/sintetico */
  
  $sCampos = "";
  
  if ($sOrigem == 'matric') {
    $sOrigemDesc = 'Matricula';
  } elseif ($sOrigem == 'inscr') {
    $sOrigemDesc = 'Inscrição';
  } elseif ($sOrigem == 'cgm') {
    $sOrigemDesc = 'CGM Geral';
  } elseif ($sOrigem == 'somentecgm') {
    $sOrigemDesc = 'Somente CGM';
  }
  
  if ($sTipo == 'A') {
    $head7 = "Analitico";
    $sCampos  = " ar30_exercicio ,";
    $sCampos .= " ar30_data      ,";
    $sCampos .= " ar30_situacao  ,";
    
    if ($sOrigem == 'matric') {
      $sCampos .= ' ar33_matric as cod_origem ';
    } elseif ($sOrigem == 'inscr') {
      $sCampos .= ' ar35_inscr as cod_origem ';
    } elseif ($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {
      if ($sOrigem == 'somentecgm') {
        $sCampos .= ' ar34_numcgm as cod_origem ';
      } else {
        $sCampos .= ' ar34_numcgm as cod_origem ';
      }
    }
  } else {
    $head7 = "Sintético";
    $sCampos  = " ar30_exercicio,";
    $sCampos .= " ar30_data,";
    if ($sStatus == 1) {
      $sCampos .= " SUM( CASE ar30_situacao WHEN 1 THEN 1 ELSE 0 END ) AS ativas                    ";
    } else if ($sStatus == 2) {
      $sCampos .= " SUM( CASE ar30_situacao WHEN 2 THEN 1 ELSE 0 END ) AS anuladas                  ";
    } else if ($sStatus == 3) {
      $sCampos .= " SUM( CASE ar30_situacao WHEN 3 THEN 1 ELSE 0 END ) AS anuladasautomaticamente   ";
    } else {
      $sCampos .= " SUM( CASE ar30_situacao WHEN 1 THEN 1 ELSE 0 END ) AS ativas                   ,";
      $sCampos .= " SUM( CASE ar30_situacao WHEN 2 THEN 1 ELSE 0 END ) AS anuladas                 ,";
      $sCampos .= " SUM( CASE ar30_situacao WHEN 3 THEN 1 ELSE 0 END ) AS anuladasautomaticamente   ";
    }
  }

  $sSql = " SELECT {$sCampos}
              FROM declaracaoquitacao ";
    
  if ($sOrigem == 'matric') {
      
    $sSql .= " JOIN declaracaoquitacaomatric ON ar33_declaracaoquitacao = ar30_sequencial
               JOIN aguabase                 ON x01_matric              = ar33_matric
               JOIN cgm                      ON z01_numcgm              = x01_numcgm ";
    $head5 = "Matricula";
  } elseif ($sOrigem == 'inscr') {
     
    $sSql .= " JOIN declaracaoquitacaoinscr ON ar35_declaracaoquitacao = ar30_sequencial
               JOIN issbase                 ON q02_inscr               = ar35_inscr
               JOIN cgm                     ON z01_numcgm              = q02_numcgm ";
    $head5 = "Inscrição";
  } elseif ($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {
      
    if ($sOrigem == 'somentecgm') {
      
      $sAnd  = " AND ar34_somentecgm is true ";
      $head5 = "Somente CGM";
    } else {
      
      $sAnd  = " AND ar34_somentecgm is false ";
      $head5 = "CGM";
    }
      
    $sSql .= " JOIN declaracaoquitacaocgm ON ar34_declaracaoquitacao = ar30_sequencial
               JOIN cgm                   ON z01_numcgm              = ar34_numcgm $sAnd";
  }

  $sSql .= " where ar30_instit = ".db_getsession('DB_instit')." ";
    
  if ($dDataInicial && !$dDataFinal) {
    
    $sSql .= " and ar30_data > '{$dDataInicial}' ";
    $head3 = "Inicial {$dDataInicial}";
  } else if (!$dDataInicial && $dDataFinal) {
    
    $sSql .= " and ar30_data < '{$dDataFinal}' ";
    $head3 = "Final {$dDataFinal}";
  } else if ($dDataInicial && $dDataFinal) {
    
    $sSql .= " and ar30_data between '{$dDataInicial}' and '{$dDataFinal}' ";
    $head3 = "{$dDataInicial} - {$dDataFinal}";
  } else {
    $head3 = "Não Definida";
  }
    
  if ($sStatus) {
    $sSql .= " AND ar30_situacao = '{$sStatus}' ";
    
    if ($sStatus == 1) {
      
      $head4 = "Ativas";
    } else if ($sStatus == 2) {
      
      $head4 = "Anuladas";
    } else if ($sStatus == 3) {
      
      $head4 = "Anuladas Automaticamente";
    }
  }
  
  if ($iExercicio) {
    $sSql .= " AND ar30_exercicio = '{$iExercicio}' ";
  }
  
  if ($sTipo == 'S') {
    $sSql .= " GROUP BY ar30_exercicio,
                        ar30_data";
  }
  
  $sSql .= " ORDER BY ar30_exercicio,
                      ar30_data";

  if ($sTipo == 'A') {
    $sSql .= " ,ar30_sequencial";
  }  
  
  $rsDeclaracaoQuitacao = $clDeclaracaoQuitacao->sql_record($sSql);
   
  if (pg_numrows($rsDeclaracaoQuitacao) == 0) {  
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    exit;
  }
  
  $oPdf = new PDF(); 
  $oPdf->Open(); 
  $oPdf->AliasNbPages();
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  
  $head1 = "Relatório Declarações de Quitação de Débitos";
  $head2 = 'Filtros Utilizados:';
  
  $head3 = 'Data : ' . $head3;
  $head4 = 'Situação : ' . (@$head4 == "" ? "Todas" : $head4);
  $head5 = 'Origem : ' . $head5;
  $head6 = 'Exercicio : ' . (@$iExercicio == "" ? "Todos" : $iExercicio);
  $head7 = 'Tipo : ' . $head7;
  
  $iTotalAtivas                       = 0;
  $iTotalAnuladas                     = 0;
  $iTotalAnuladasAutomaticamente      = 0;
  $iTotalGeralAtivas                  = 0;
  $iTotalGeralAnuladas                = 0;
  $iTotalGeralAnuladasAutomaticamente = 0;
  $iTotalGeralAnalitico               = 0;
  $iTotalAnalitico                    = 0;
  
  $troca        = 1;
  $alt          = 4;
  $totaldata    = 0;
  $totalexerc   = 0;
  $totalog      = 0;
  $iExercicio   = "";
  $dDataGeracao = "";
  $p            = 0;
  
  for ($i = 0; $i < $clDeclaracaoQuitacao->numrows; $i++) {
    $oDeclaracoes = db_utils::fieldsMemory($rsDeclaracaoQuitacao, $i, true);
    
    if ($sTipo == 'S') { //Sintetico
      
      if ($iExercicio != $oDeclaracoes->ar30_exercicio) {
        
        if ($iExercicio != "") {
          
          $oPdf->cell(190, $alt, 'TOTAL NO EXERCICIO : ' . $totalexerc, "T", 1, "R", 0);
          $oPdf->ln();
          $oPdf->ln();
          $totalexerc = 0;
        }
         
        if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {
          
          $oPdf->addpage("P");
          $oPdf->setrightmargin(0.5);
          $troca = 0;
        }
        
        $oPdf->setfont('arial', 'b', 8);
         
        $oPdf->cell(0 , $alt, "Exercicio : $oDeclaracoes->ar30_exercicio", 0, 1, "L", 0);
        
        $oPdf->cell(48, $alt, 'Data'     , 1, 0, "C", 1);
        
        if ($sStatus == 1) {
          
          $oPdf->cell(48, $alt, 'Ativas'                  , 1, 1, "C", 1);
        } else if ($sStatus == 2) {
          
          $oPdf->cell(48, $alt, 'Anuladas'                , 1, 1, "C", 1);
        } else if ($sStatus == 3) {
        
          $oPdf->cell(48, $alt, 'Anuladas Automaticamente', 1, 1, "C", 1);
        } else {
          $oPdf->cell(48, $alt, 'Ativas'                  , 1, 0, "C", 1);
          $oPdf->cell(48, $alt, 'Anuladas'                , 1, 0, "C", 1);
          $oPdf->cell(48, $alt, 'Anuladas Automaticamente', 1, 1, "C", 1);
        }
         
        $p          = 0;
        $iExercicio = $oDeclaracoes->ar30_exercicio;
        
      }
      
      if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
        
        $oPdf->addpage("P");
        $oPdf->setrightmargin(0.5);
        $oPdf->setfont('arial', 'b', 8);
        
        $oPdf->cell(0 , $alt, "Exercicio : $oDeclaracoes->ar30_exercicio", 0, 1, "L", 0);
        
        $oPdf->cell(48, $alt, $oDeclaracoes->ar30_data    , 1, 0, "L", 0);
        
        if ($sStatus == 1) {
          
          $oPdf->cell(48, $alt, 'Ativas'                  , 1, 1, "C", 1);
        } else if ($sStatus == 2) {
        
          $oPdf->cell(48, $alt, 'Anuladas'                , 1, 1, "C", 1);
        } else if ($sStatus == 3) {
          
          $oPdf->cell(48, $alt, 'Anuladas Automaticamente', 1, 1, "C", 1);
        } else {
        
          $oPdf->cell(48, $alt, 'Ativas'                  , 1, 0, "C", 1);
          $oPdf->cell(48, $alt, 'Anuladas'                , 1, 0, "C", 1);
          $oPdf->cell(48, $alt, 'Anuladas Automaticamente', 1, 1, "L", 1);
        }
        
        $p     = 0;
        $troca = 0;
      }
      
      $oPdf->setfont('arial', '', 7);
      
      $oPdf->cell(48, $alt, $oDeclaracoes->ar30_data, 0, 0, "L", $p);
      
      if ($sStatus == 1) {
        
        $oPdf->cell(48, $alt, $oDeclaracoes->ativas, 0, 1, "L", $p);
        $iTotalAtivas += $oDeclaracoes->ativas;
      } else if ($sStatus == 2) {
        
        $oPdf->cell(48, $alt, $oDeclaracoes->anuladas, 0, 1, "L", $p);
        $iTotalAnuladas += $oDeclaracoes->anuladas;
      } else if ($sStatus == 3) {
        
        $oPdf->cell(48, $alt, $oDeclaracoes->anuladasautomaticamente, 0, 1, "L", $p);
        $iTotalAnuladasAutomaticamente += $oDeclaracoes->anuladasautomaticamente;
      } else {
        
        $oPdf->cell(48, $alt, $oDeclaracoes->ativas                 , 0, 0, "L", $p);
        $oPdf->cell(48, $alt, $oDeclaracoes->anuladas               , 0, 0, "L", $p);
        $oPdf->cell(48, $alt, $oDeclaracoes->anuladasautomaticamente, 0, 1, "L", $p);
        
        $iTotalAtivas                  += $oDeclaracoes->ativas;
        $iTotalAnuladas                += $oDeclaracoes->anuladas;
        $iTotalAnuladasAutomaticamente += $oDeclaracoes->anuladasautomaticamente;
        
      }
      $totalog++;
      
      if ($p == 0) {
        
        $p = 1;
      } else {
        
        $p = 0;
      }
      
      $totalexerc++;
      
    } else {  // ANALITICO
      
      if (($iExercicio != $oDeclaracoes->ar30_exercicio) OR ($dDataGeracao != $oDeclaracoes->ar30_data)) {
        
        if ($dDataGeracao != $oDeclaracoes->ar30_data) {
          $oPdf->cell(190, $alt, 'TOTAL NA DATA : ' . $totaldata, "T", 1, "R", 0);
          $oPdf->ln();
          $totaldata  = 0;
        }
        
        if ($iExercicio != $oDeclaracoes->ar30_exercicio) {
          
          if ($dDataGeracao == $oDeclaracoes->ar30_data) {
            
            $oPdf->cell(190, $alt, 'TOTAL NA DATA : ' . $totaldata, "T", 1, "R", 0);
            $oPdf->ln();
            $totaldata  = 0;
          }
          
          $oPdf->cell(190, $alt, 'TOTAL NO EXERCICIO : ' . $totalexerc, "T", 1, "R", 0);
          $oPdf->ln();
          $totalexerc = 0;
        }
        
        if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {
          
          $oPdf->addpage("P");
          $oPdf->setrightmargin(0.5);
          $troca = 0;
        }
        
        $oPdf->setfont('arial', 'b', 8);
        
        if ($iExercicio != $oDeclaracoes->ar30_exercicio) {
          
          $oPdf->cell(0 , $alt, "Exercicio : $oDeclaracoes->ar30_exercicio", 0, 1, "L", 0);
          $p          = 0;
          $iExercicio = $oDeclaracoes->ar30_exercicio;
        }
        
        if ($dDataGeracao != $oDeclaracoes->ar30_data) {
          
          $oPdf->cell(40 , $alt, "Data Geração : $oDeclaracoes->ar30_data" , 0, 1, "R", 0);
          $dDataGeracao = $oDeclaracoes->ar30_data;
        }
        
        $oPdf->cell(48, $alt, 'Data'     , 1, 0, "C", 1);
        $oPdf->cell(48, $alt, 'Matricula', 1, 0, "C", 1);
        $oPdf->cell(48, $alt, 'Status'   , 1, 1, "C", 1);
        
      }
      
      if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
        
        $oPdf->addpage("P");
        $oPdf->setrightmargin(0.5);
        $oPdf->setfont('arial', 'b', 8);
        
        $oPdf->cell(0 , $alt, "Exercicio : $oDeclaracoes->ar30_exercicio", 0, 1, "L", 0);
        $oPdf->cell(40, $alt, "Data Geração : $oDeclaracoes->ar30_data"  , 0, 1, "R", 0);
        
        $oPdf->cell(48, $alt, 'Data'     , 1, 0, "C", 1);
        $oPdf->cell(48, $alt, 'Matricula', 1, 0, "C", 1);
        $oPdf->cell(48, $alt, 'Status'   , 1, 1, "C", 1);
        
        $p     = 0;
        $troca = 0;
      }
      
      $oPdf->setfont('arial', '', 7);
      
      $oPdf->cell(48, $alt, $oDeclaracoes->ar30_data    , 1, 0, "L", 0);
      $oPdf->cell(48, $alt, $oDeclaracoes->cod_origem   , 1, 0, "L", 0);
      if ($oDeclaracoes->ar30_situacao == 1) {
        $sStatusDesc = "Ativa";
      } else if ($oDeclaracoes->ar30_situacao == 2) {
        $sStatusDesc = "Anulada";
      } else if ($oDeclaracoes->ar30_situacao == 3) {
        $sStatusDesc = "Anulada Automaticamente";
      }
      $oPdf->cell(48, $alt, $sStatusDesc, 1, 1, "L", 0);
      
      $totalog++;
      
      if ($p == 0) {
        
        $p = 1;
      } else {
        
        $p = 0;
      }
      $totaldata++;
      $totalexerc++;
    }
  }
  
  if ($sTipo == 'A') { //Analitico
    $oPdf->cell(190, $alt, 'TOTAL NA DATA : ' . $totaldata, "T", 1, "R", 0);
    $oPdf->ln();
  }
  
  $oPdf->cell(190, $alt, 'TOTAL NO EXERCÍCIO : ' . $totalexerc, "T", 1, "R", 0);
  $oPdf->ln();
  
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(190, $alt, 'TOTAL DE REGISTROS : ' . $totalog, "T", 0, "R", 0);
  $oPdf->Output();
  
?>