<?php
  /*
   *     E-cidade Software Publico para Gestao Municipal                
   *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

  include("fpdf151/pdf.php");
  include("libs/db_sql.php");
  $clrotulo = new rotulocampo;
  $clrotulo->label('');
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  
  $where = "";
  $and   = "";
  
  if ($ano != "") {
    
    $where .= $and . " x21_exerc = {$ano} ";
    $and    = " and ";
    $head3  = " Ano: {$ano}";
  }
  
  if ($mes != "") {
    
    $where .= $and . " x21_mes = {$mes} ";
    $and    = " and ";
    $head4  = " Mês: {$mes}";
  }
  
  if ($exc_ini != "" && $exc_fim != "") {
    
    $where .= $and . " x21_excesso between {$exc_ini} and {$exc_fim} ";
    $and    = " and ";
    $head5  = "Excesso: {$exc_ini} a {$exc_fim} ";
    
  } else if ($exc_ini != "") {
    
    $where .= $and . " x21_excesso >= {$exc_ini} ";
    $and    = " and ";
    $head5  = "Excesso apartir de: {$exc_ini} ";
    
  } else if ($exc_fim != "") {
    
    $where .= $and . " x21_excesso <= {$exc_fim} ";
    $and    = " and ";
    $head5  = "Excesso ate: {$exc_fim} ";
  } 
  
  if ($where != "") {
    $where = " where " . $where . 'and x21_status = 1';
  }
  
  $head6    = "Ordem: Bairro/Logradouro/Numero/Letra";
  $order_by = "order by bairro.j13_descr, j14_nome, x01_numero, x01_letra";
  
  $head2 = "Relatório de Consumo/Excesso";
  
  $sSql  = " select x04_matric,                                                                       ";
  $sSql .= "        x21_dtleitura,                                                                    ";
  $sSql .= "        j13_descr,                                                                        ";
  $sSql .= "        x04_nrohidro,                                                                     ";
  $sSql .= "        fc_agua_leituraanterior(x04_matric, x21_codleitura) as x21_leitura_ant,           ";
  $sSql .= "        x21_leitura,                                                                      ";
  $sSql .= "        x21_consumo,                                                                      ";
  $sSql .= "        x21_excesso,                                                                      ";
  $sSql .= "        x01_codrua,                                                                       ";
  $sSql .= "        j14_nome,                                                                         ";
  $sSql .= "        x01_numero,                                                                       ";
  $sSql .= "        x11_complemento,                                                                  "; 
  $sSql .= "        x01_letra                                                                         ";
  $sSql .= "   from agualeitura                                                                       ";
  $sSql .= "        inner join aguahidromatric on x04_codhidrometro          = x21_codhidrometro      ";
  $sSql .= "        inner join aguabase        on aguahidromatric.x04_matric = aguabase.x01_matric    ";
  $sSql .= "         left join aguaconstr      on aguaconstr.x11_matric      = aguabase.x01_matric    "; 
  $sSql .= "                                  and x11_tipo                   = 'P'                    ";
  $sSql .= "        inner join bairro          on bairro.j13_codi            = aguabase.x01_codbairro ";
  $sSql .= "        inner join ruas            on ruas.j14_codigo            = aguabase.x01_codrua    "; 
  $sSql .= " {$where} {$order_by}";
  
  $rsDados = db_query($sSql);
  $numrows = pg_numrows($rsDados);
  
  if ($numrows == 0) {             
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
  
  $pdf = new PDF(); 
  
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);
  
  $total = 0;
  $alt   = 4;
  $total = 0;
  $p     = 0;
  
  for ($iInd = 0; $iInd < $numrows; $iInd++) {
    
    db_fieldsmemory($rsDados, $iInd);
    
    if ($pdf->gety() > $pdf->h - 30 || $iInd == 0) {
      
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      
      $pdf->cell(20, $alt, "Matricula"     , 1, 0, "C", 1);
      $pdf->cell(50, $alt, "Bairro"        , 1, 0, "C", 1); 
      $pdf->cell(85, $alt, "Logradouro"    , 1, 0, "C", 1);
      $pdf->cell(10, $alt, "Letra"         , 1, 0, "C", 1);
      $pdf->cell(20, $alt, "Data Leitura"  , 1, 0, "C", 1);      
      $pdf->cell(25, $alt, "Nro Hidrometro", 1, 0, "C", 1);
      $pdf->cell(18, $alt, "L. Anterior"   , 1, 0, "C", 1);
      $pdf->cell(18, $alt, "L. Atual"      , 1, 0, "C", 1);
      $pdf->cell(15, $alt, "Consumo"       , 1, 0, "C", 1);       
      $pdf->cell(15, $alt, "Excesso"       , 1, 1, "C", 1);
      $p = 0;
    }
    
    $pdf->setfont('arial', '', 7);   
    
    $pdf->cell(20, $alt, $x04_matric                                     , 0, 0, "C", $p);
    $pdf->cell(50, $alt, $j13_descr                                      , 0, 0, "L", $p);
    $pdf->cell(85, $alt, "{$j14_nome} - {$x01_numero}/{$x11_complemento}", 0, 0, "L", $p);
    $pdf->cell(10, $alt, $x01_letra                                      , 0, 0, "L", $p);
    $pdf->cell(20, $alt, db_formatar($x21_dtleitura, 'd')                , 0, 0, "C", $p);
    $pdf->cell(25, $alt, $x04_nrohidro                                   , 0, 0, "C", $p);
    $pdf->cell(18, $alt, $x21_leitura_ant                                , 0, 0, "C", $p);   
    $pdf->cell(18, $alt, $x21_leitura                                    , 0, 0, "C", $p);
    $pdf->cell(15, $alt, $x21_consumo                                    , 0, 0, "C", $p);
    $pdf->cell(15, $alt, $x21_excesso                                    , 0, 1, "C", $p);
    
    if ($p == 0) { 
      $p = 1; 
    } else {
      $p = 0;
    }
      
    $total++;
  }
  
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(191, $alt, 'TOTAL DE REGISTROS : ' . $total, "T", 0, "L", 0);
  $pdf->Output();
?>