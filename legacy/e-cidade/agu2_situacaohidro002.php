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
  
  require_once "fpdf151/pdf.php";
  require_once "libs/db_conecta.php";
  require_once "libs/db_sql.php";
  
  $oGet = db_utils::postMemory($_GET);

  
  $claguacortematmov = new cl_aguacortematmov();

  if (isset($oGet->situacaomeses)) {
    
    $sWhereAnd   = '';
    $sWhereMeses = '';
    $sWhereCount = '';
    
    $aSituacoes = explode(':', $oGet->situacaomeses);
    
    foreach ($aSituacoes as $aSituacao) {
      
      $aQuantSituacaoMes    = explode(',', $aSituacao);
      $aQuantSituacaoMes[1] = $aQuantSituacaoMes[1] - 1;
      
      $dMeses = date('Y,m', strtotime("-{$aQuantSituacaoMes[1]} months"));
      $dAtual = date('Y,m');
     
      $sWhereMeses .= $sWhereAnd . "(x21_situacao = {$aQuantSituacaoMes[0]} AND (x21_exerc, x21_mes) ";
      $sWhereMeses .= "BETWEEN ({$dMeses}) AND ({$dAtual})) \n";
      
      $sWhereAnd    = ' OR ';
      
      $aQuantSituacaoMes[1] = $aQuantSituacaoMes[1] + 1;
      $sWhereCount .= " WHEN (x.x21_situacao = {$aQuantSituacaoMes[0]} AND x.count = {$aQuantSituacaoMes[1]}) THEN TRUE \n";
    }
  }
  
  $sSql  = "SELECT distinct                                                                                       \n";
  $sSql .= "       CASE WHEN prom.z01_nome IS NOT NULL                                                            \n";
  $sSql .= "         THEN prom.z01_nome                                                                           \n";
  $sSql .= "         ELSE prop.z01_nome                                                                           \n";
  $sSql .= "       END AS z01_nome,                                                                               \n";
  $sSql .= "       j14_nome       , x01_numero    , x11_complemento, x01_letra,                                   \n";
  $sSql .= "       x04_nrohidro   , x04_qtddigito , x01_matric     ,                                              \n";
  $sSql .= "       x21_exerc      , x21_mes       , x21_leitura    , j13_descr,                                   \n";
  $sSql .= "       j88_sigla      , x17_descr     , count AS contador                                             \n";
  $sSql .= "  FROM (SELECT x21_codhidrometro,                                                                     \n";
  $sSql .= "               x21_situacao,                                                                          \n";
  $sSql .= "               count(1)                                                                               \n";
  $sSql .= "          FROM agualeitura                                                                            \n";
  $sSql .= "         WHERE {$sWhereMeses}                                                                         \n";
  $sSql .= "         GROUP BY x21_codhidrometro,                                                                  \n";
  $sSql .= "                  x21_situacao) as x                                                                  \n";
  $sSql .= "       INNER JOIN aguahidromatric               ON x04_codhidrometro = x.x21_codhidrometro            \n";
  $sSql .= "                                               AND fc_agua_hidrometroativo(x04_codhidrometro) IS TRUE \n";
  $sSql .= "       LEFT  JOIN aguaconstr                    ON x11_matric            = x04_matric                 \n";
  $sSql .= "                                               AND x11_tipo              = 'P'                        \n";
  $sSql .= "       INNER JOIN aguahidromatricultimaleitura  ON x09_codhidrometro     = x04_codhidrometro          \n";
  $sSql .= "       INNER JOIN agualeitura as ultima         ON ultima.x21_codleitura = x09_codleitura             \n";
  $sSql .= "       INNER JOIN aguabase                      ON x01_matric            = x04_matric                 \n";
  $sSql .= "       INNER JOIN aguasitleitura                ON x17_codigo            = x.x21_situacao             \n";
  $sSql .= "       INNER JOIN cgm prop                      ON prop.z01_numcgm       = x01_numcgm                 \n";
  $sSql .= "       LEFT  JOIN cgm prom                      ON prom.z01_numcgm       = x01_promit                 \n";
  $sSql .= "       INNER JOIN bairro                        ON j13_codi              = x01_codbairro              \n";
  $sSql .= "       INNER JOIN ruas                          ON j14_codigo            = x01_codrua                 \n";
  $sSql .= "       INNER JOIN ruastipo                      ON j14_tipo              = j88_codigo                 \n";
  $sSql .= "       LEFT  JOIN aguacortemat                  ON x41_matric            = x01_matric                 \n";
  $sSql .= "       LEFT  JOIN aguacortematmov               ON x42_codsituacao       = x41_codcortemat            \n";
  $sSql .= "       LEFT  JOIN aguacorte                     ON x40_codcorte          = x41_codcorte               \n";
  $sSql .= " WHERE CASE {$sWhereCount}                                                                            \n";
  $sSql .= "            ELSE FALSE END                                                                            \n";
 
  if (isset($oGet->listabairro)) {
    
    $sSql  .= " AND bairro.j13_codi in ({$oGet->listabairro}) \n";
    $head4  = "Bairro(s): " . $oGet->listabairro;
  }
  
  if (isset($oGet->filtro)) {
    
    if ($oGet->filtro == 2) { //COM SIT CORTE
      
      $sSql  .= " AND x42_codsituacao IS not NULL \n";
      $head6  = "Filtro: COM situação de corte";
      
    } else if ($oGet->filtro == 3) { //SEM SIT CORTE
      
      $sSql  .= " AND x42_codsituacao IS NULL \n";
      $head6  = "Filtro: SEM situação de corte";
      
    } else {
      $head6  = "Filtro: Todas";
    }
  }
  
  $sOrderBy = ' x17_descr ';
  
  if (isset($oGet->ordenacao)) {
    
    $head5 = 'Ordenação: ';
    
    switch ($oGet->ordenacao) {
      
      case 1:
        
        $sOrderBy .= ',x01_matric';
        $head5    .= 'Matricula';
        break;
      case 2:
        
        $sOrderBy .= ',j13_descr, j14_nome, x01_numero';
        $head5    .= 'Bairro/Logradouro/Numero';
        break;
      case 3:
        
        $sOrderBy .= ',x21_exerc, x21_mes';
        $head5    .= 'Exercício/Mês';
        break;
      case 4:
        
        $sOrderBy .= ',x21_leitura';
        $head5    .= 'Leitura';
        break;
    }
    
    $sSql .= ' ORDER BY ' . $sOrderBy;
  }
  
  $result = db_query($sSql);
  $linhas = pg_num_rows($result);
  
  if ($linhas == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
  
  $head2 = 'Relatório de Situação de Hidrômetros';
  
  $pdf = new PDF(); 
  
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);
  
  $total = 0;
  $troca = 1;
  $alt   = 4;
  $total = 0;
  $p     = 0;
  
  for ($x = 0; $x < $linhas; $x++) {
    
    db_fieldsmemory($result, $x);
    
    $sLimit = 'x42_data desc, x42_codmov DESC LIMIT 1';
    
    $sSqlSitCorte = $claguacortematmov->sql_query(null, 'x42_codsituacao', $sLimit, "x41_matric = {$x01_matric}");
    $rsSitCorte   = $claguacortematmov->sql_record($sSqlSitCorte);
    
    if (($oGet->filtro == 1) || ($oGet->filtro == 2)) {
      
      if ($claguacortematmov->numrows > 0) { 
        
        db_fieldsmemory($rsSitCorte, 0);
       
      } else {
        
        if ($oGet->filtro == 2) {   
          continue;  
        } else {
          $x42_codsituacao = '';
        }
      }
    } else {
      
      if ($claguacortematmov->numrows > 0) {
        continue;
      }
    }
    
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 || $sDescrSituacao != $x17_descr) {
      
      $pdf->addpage('L');
      $pdf->setfont('arial', 'b', 8);
      
      $sDescricaoSituacao = "SITUAÇÃO: {$x17_descr}  -  OCORRÊNCIAS:  {$contador}";
      
      $pdf->cell(70, $alt, $sDescricaoSituacao, 0, 0, 'L', 1);
      $pdf->cell(155, $alt, ""          , 0, 0, 'C', 0);
      $pdf->cell(35, $alt, "Última Leitura", 1, 1, 'C', 1);
      
      $pdf->cell(20, $alt, "Matricula"     , 1, 0, 'C', 1);
      $pdf->cell(70, $alt, "Proprietário"  , 1, 0, 'C', 1);
      $pdf->cell(70, $alt, "Endereço"      , 1, 0, 'C', 1);
      $pdf->cell(15, $alt, "Número"        , 1, 0, 'C', 1);
      $pdf->cell(25, $alt, "Complemento"   , 1, 0, 'C', 1);
      $pdf->cell(25, $alt, "Nº Hidrômetro" , 1, 0, 'C', 1);
      $pdf->cell(10, $alt, "Ano"           , 1, 0, 'C', 1);
      $pdf->cell(10, $alt, "Mês"           , 1, 0, 'C', 1);
      $pdf->cell(15, $alt, "Leitura"       , 1, 0, 'C', 1);
      $pdf->cell(20, $alt, "Sit Corte"     , 1, 1, 'C', 1);
      
      $troca = 0;
      $p     = 0;
      $sDescrSituacao = $x17_descr;
    }
    
    $pdf->setfont('arial', '', 7);
       
    $sEndereco = 'B. ' . $j13_descr  . ' - ' . $j88_sigla . '. ' . $j14_nome;
    
    $pdf->cell(20, $alt, $x01_matric , 0, 0, 'C', $p);
    $pdf->cell(70, $alt, substr($z01_nome, 0, 45)   , 0, 0, 'L', $p);
    $pdf->cell(70, $alt, substr($sEndereco, 0, 45)  , 0, 0, 'L', $p);
    
    $letra  = trim($x01_letra);
    $numero = $x01_numero . (empty($letra) ? "" : "/" . $letra);
    
    $pdf->cell(15, $alt, $numero,                         0, 0, 'R', $p);
    $pdf->cell(25, $alt, substr($x11_complemento, 0, 15), 0, 0, 'L', $p);
    $pdf->cell(25, $alt, $x04_nrohidro,                   0, 0, 'L', $p);
    $pdf->cell(10, $alt, $x21_exerc,                      0, 0, 'C', $p);
    $pdf->cell(10, $alt, $x21_mes,                        0, 0, 'C', $p);
    $pdf->cell(15, $alt, $x21_leitura,                    0, 0, 'C', $p);
    $pdf->cell(20, $alt, @$x42_codsituacao,                0, 1, 'C', $p);
    
    if ($p == 0) {
      $p = 1;
    } else { 
      $p = 0;  
    }
    
    $total++;
  }
  
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(280, $alt, 'TOTAL DE REGISTROS : ' . $total, 'T', 0, 'L', 0);
  $pdf->Output();
?>