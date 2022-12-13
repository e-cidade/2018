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

include("libs/db_utils.php");
include("fpdf151/pdf.php");

$clrotulo = new rotulocampo;
$clrotulo->label('x01_matric');
$clrotulo->label('x04_nrohidro');
$clrotulo->label('x04_codhidrometro');
$clrotulo->label('x04_dtinst');
$clrotulo->label('x01_codrua');
$clrotulo->label('j88_sigla');
$clrotulo->label('j14_nome');
$clrotulo->label('x01_numero');
$clrotulo->label('x01_orientacao');
$clrotulo->label('x01_entrega');
$clrotulo->label('x21_leitura');
$clrotulo->label('x42_leitura');
$clrotulo->label('x43_descr');

$oGet = db_utils::postMemory($_GET);

$iIdListaCorte = $oGet->corte;
$sOrdenar      = $oGet->ordenar;

if ($sOrdenar == 'matricula') {
  
  $orderby = 'x01_matric';
  $sOrdenadopor = 'Matrícula';
  
} else if ($sOrdenar == 'logradouro') {
  
  $orderby = 'j14_nome';
  $sOrdenadopor = 'Logradouro';
  
} else if ($sOrdenar == 'leituracorte') {
  
  $orderby = 'x42_leitura';
  $sOrdenadopor = 'Leitura do Corte';
  
} else if($sOrdenar == 'leituraleiturista') {
  
  $orderby = 'x21_leitura';
  
} else if($sOrdenar == 'data') {
  
  $orderby = 'x04_dtinst';
  $sOrdenadopor = 'Data de Instalação';
}


$sSql  = " SELECT x01_matric, x04_codhidrometro, x04_dtinst, \n";
$sSql .= "        x04_nrohidro, x01_codrua, j88_sigla, j14_nome, \n";
$sSql .= "        x01_numero, x01_orientacao, x01_entrega, \n";
$sSql .= "        x21_leitura, x42_leitura, x43_descr \n";
$sSql .= "   FROM aguacortemat \n";
$sSql .= "  INNER JOIN aguabase          ON x01_matric        = x41_matric \n";
$sSql .= "  INNER JOIN vw_leitura        ON x04_matric        = x41_matric \n";
$sSql .= "  INNER JOIN aguacortematmov   ON x42_codcortemat   = x41_codcortemat \n";
$sSql .= "  INNER JOIN aguacortesituacao ON x43_codsituacao   = fc_agua_ultimasituacaocorte(x01_matric, {$iIdListaCorte}) \n";
$sSql .= "   LEFT JOIN ruas              ON j14_codigo        = x01_codrua \n";
$sSql .= "   LEFT JOIN ruastipo          ON x01_codrua        = j88_codigo \n";
$sSql .= "  WHERE x41_codcorte   = {$iIdListaCorte} \n";
$sSql .= "    AND x21_codleitura = ( SELECT x21_codleitura \n";
$sSql .= "                             FROM vw_leitura \n";
$sSql .= "                            WHERE x04_matric = x41_matric \n";
$sSql .= "                            ORDER BY x21_exerc DESC, \n";
$sSql .= "                                     x21_mes DESC \n";
$sSql .= "                            LIMIT 1 ) \n";
$sSql .= "    AND x42_codmov     = ( SELECT x42_codmov \n";
$sSql .= "                             FROM aguacortematmov \n";
$sSql .= "                            WHERE x42_codcortemat = x41_codcortemat \n";
$sSql .= "                              AND x42_leitura     < vw_leitura.x21_leitura \n";
$sSql .= "                              AND x42_leitura     <> 0 \n";
$sSql .= "                            LIMIT 1) \n";

if (!empty($oGet->listalog)) {
  
  $sSql .= "    AND x01_codrua IN ({$oGet->listalog}) \n";
  
  if (strlen($oGet->listalog) > 38) {
    $head5 = substr($oGet->listalog, 0,38) . '...';
  } else {
    $head5 = $oGet->listalog;
  }
  
  $head5 = 'Logradouro: ' . $head5; 
}

if (!empty($oGet->listazonaentrega)) {
  
  $sSql .= "    AND x01_entrega IN ({$oGet->listazonaentrega}) \n";

  if (strlen($oGet->listazonaentrega) > 33) {
    $head7 = substr($oGet->listazonaentrega, 0,33) . '...';
  } else {
    $head7 = $oGet->listazonaentrega;
  }
  
  $head7 = 'Zona de Entrega: ' . $head7;
}

if (!empty($oGet->listazona)) {
  
  $sSql .= "    AND x01_zona IN ({$oGet->listazona}) \n";
  
  if (strlen($oGet->listazona) > 37) {
    $head6 = substr($oGet->listazona, 0,37) . '...';
  } else {
    $head6 = $oGet->listazona;
  }
  
  $head6 = 'Zona Fiscal: ' . $head6;
}

if (!empty($oGet->situacao)) {
  
  $sSql .= "    AND x43_codsituacao IN ({$oGet->situacao}) \n";
  
  $oDaoAguaCorteSituacao = new cl_aguacortesituacao();
  $sSqlSituacaoCorte  = $oDaoAguaCorteSituacao->sql_query($oGet->situacao, 'x43_descr');
  $rsSituacaoCorte    = $oDaoAguaCorteSituacao->sql_record($sSqlSituacaoCorte);
  $sSituacaoDescricao = db_utils::fieldsMemory($rsSituacaoCorte, 0)->x43_descr;
  
  $head4 = 'Situação: ' . $oGet->situacao . ' - ' . $sSituacaoDescricao;
}

$sSql .= "  GROUP BY x01_matric, x04_codhidrometro, x04_dtinst,     \n";
$sSql .= "           x04_nrohidro, x01_codrua, j88_sigla, j14_nome, \n";
$sSql .= "           x01_numero, x01_orientacao, x01_entrega,       \n";
$sSql .= "           x21_leitura, x42_leitura, x43_descr            \n";
$sSql .= "  ORDER BY {$orderby}                                     \n";

$rSql = db_query($sSql);

if (pg_num_rows($rSql) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem matriculas.');
}

$head1 = 'Relatório Leitura/Corte Relação Leitura/Leiturista';
$head3 = 'Lista de Corte: ' . $iIdListaCorte;
$head8 = 'Ordenação:  ' . $sOrdenadopor;

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();
$oPDF->setfillcolor(235);
$oPDF->setfont('arial', 'b', 7);
$total   = 0;
$troca   = 1;
$alt     = 4;
$totalog = 0;
$p       = 0;

for ($i = 0; $i < pg_num_rows($rSql); $i++) {
  
  $oSql = db_utils::fieldsMemory($rSql, $i, true);
   
  if ($oPDF->gety() > $oPDF->h - 30 || $troca != 0 ) {
    
    $oPDF->addpage("L");
    $oPDF->setfont('arial','b',8);
    $oPDF->cell(30, $alt, $RLx01_matric       , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, $RLx04_dtinst       , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, $RLx04_nrohidro     , 1, 0, "C", 1);
    $oPDF->cell(80, $alt, $RLj14_nome         , 1, 0, "C", 1);
    $oPDF->cell(20, $alt, $RLx01_entrega      , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Leitura'           , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Leitura Corte'     , 1, 0, "C", 1);
    $oPDF->cell(30, $alt, 'Situação'          , 1, 1, "C", 1); 
    
    $troca = 0;
    $p     = 0;
  }
   
  $oPDF->setfont('arial','',7);   
  
  $oPDF->cell(30, $alt, $oSql->x01_matric       , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x04_dtinst       , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x04_nrohidro     , 0, 0, "C", $p);
  
  $oPDF->cell(80, $alt, $oSql->j14_nome   . ', ' . 
                        $oSql->x01_numero . ' - ' . 
                        $oSql->x01_orientacao   , 0, 0, "L", $p);
  
  $oPDF->cell(20, $alt, $oSql->x01_entrega      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x21_leitura      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x42_leitura      , 0, 0, "C", $p);
  $oPDF->cell(30, $alt, $oSql->x43_descr        , 0, 1, "L", $p);
  
  $p = $p == 0 ? 1 : 0; 
      
  $total++;
}

$oPDF->setfont('arial','b',8);
$oPDF->cell(280, $alt, 'TOTAL DE REGISTROS : ' . $total, "T", 0, "L", 0);
$oPDF->Output();

?>