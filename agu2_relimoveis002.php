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
  require_once("classes/db_caracter_classe.php");

  $clcaracter   = new cl_caracter();
  $oGet         = db_utils::postMemory($_GET);
  
  
  $sZonaFiscal  = isset($oGet->zonafiscal)  ? $oGet->zonafiscal  : '';
  $sZonaEntrega = isset($oGet->zonaentrega) ? $oGet->zonaentrega : '';
  $sLogradouro  = isset($oGet->logradouro)  ? $oGet->logradouro  : '';
  $sBairro      = isset($oGet->bairro)      ? $oGet->bairro      : '';
  
  $iAgua        = isset($oGet->agua)        ? $oGet->agua        : '';
  $iEsgoto      = isset($oGet->esgoto)      ? $oGet->esgoto      : '';
  
  $sSql = "
           SELECT DISTINCT
                  bairro.j13_codi              as codbairro,
                  bairro.j13_descr             AS Bairro,
                  aguabase.x01_matric          AS matricula,
                  cgm.z01_nome                 AS nome,
                  ruas.j14_nome                AS logradouro,
                  aguabase.x01_numero          AS numero,
                  aguabase.x01_orientacao      AS orientacao,
                  SUBSTR (iptucadzonaentrega.j85_descr, 0, 18) AS zonaentrega,
                  zonas.j50_descr              AS zonafiscal,
                  CASE WHEN x11_matric IS NOT NULL
                    THEN j31_descr 
                    ELSE 'Terreno' 
                   END                         AS Descricao 
             FROM aguabase
                  INNER JOIN cgm                ON cgm.z01_numcgm                = aguabase.x01_numcgm
                  INNER JOIN ruas               ON ruas.j14_codigo               = aguabase.x01_codrua
                  INNER JOIN bairro             ON bairro.j13_codi               = aguabase.x01_codbairro
                  INNER JOIN iptucadzonaentrega ON iptucadzonaentrega.j85_codigo = aguabase.x01_entrega
                  INNER JOIN zonas              ON j50_zona                      = aguabase.x01_zona
                  LEFT  JOIN aguaconstr         ON x01_matric                    = x11_matric
                  LEFT  JOIN aguaconstrcar      ON x12_codconstr                 = x11_codconstr
                  LEFT  JOIN caracter           ON j31_codigo                    = x12_codigo
                                               AND j31_grupo                     = 80";
  
  $sWhere = "";
  
  if ($sZonaFiscal != '') {
 
    $sWhere .= " aguabase.x01_zona IN ($sZonaFiscal) ";
  }
 
  if ($sZonaEntrega != '') {
    
    if ($sWhere != '') {
      $sWhere .= " and ";
    }
    $sWhere .= " aguabase.x01_entrega IN ($sZonaEntrega) ";
  }
 
  if ($sLogradouro != '') {
  
    if ($sWhere != '') {
      $sWhere .= " and ";
    }
    $sWhere .= " aguabase.x01_codrua IN ($sLogradouro) ";
  }
  
  if ($sBairro != '') {
    
    if ($sWhere != '') {
      $sWhere .= " and ";
    }
    $sWhere .= " aguabase.x01_codbairro IN ($sBairro)";
  }
  
  $head7 = "Agua: TODAS";
  
  if ($iAgua != '') {
    
    if ($sWhere != '') {
      
      $sWhere .= " and ";
    }
    
    $sWhere .= " fc_agua_existecaract(x01_matric, $iAgua) is not null ";
    
    $rscaracter = 
      $clcaracter->sql_record(
        $clcaracter->sql_query_file(null, "j31_grupo, j31_codigo, j31_descr",
                                    "j31_descr", "j31_grupo = 83 and j31_codigo = $oGet->agua"));
        
    $oCaracter  = db_utils::fieldsMemory($rscaracter, 0);
    $head7 = "Agua: $oCaracter->j31_codigo - $oCaracter->j31_descr";
  
  }
  
  $head8 = "Esgoto: TODAS";
  
  if ($iEsgoto != '') {
  
    if ($sWhere != '') {
      
      $sWhere .= " and ";
    }
    $sWhere .= " fc_agua_existecaract(x01_matric, $iEsgoto) is not null ";
    
    $rscaracter = 
      $clcaracter->sql_record(
        $clcaracter->sql_query_file(null, "j31_grupo, j31_codigo, j31_descr", "j31_descr",
                                    "j31_grupo = 82 and j31_codigo = $oGet->esgoto"));
    
    $oCaracter = db_utils::fieldsMemory($rscaracter, 0);
    $head8     = "Esgoto: $oCaracter->j31_codigo - $oCaracter->j31_descr";
    
  }
  
  if (!empty($sWhere)) {
  
    $sWhere = " where ".$sWhere;
  }
  
  $sSql = "SELECT *
             FROM ($sSql $sWhere) as x
            ORDER BY codbairro, logradouro , numero    , orientacao,
                     matricula, zonaentrega, zonafiscal ";

  $result = pg_exec($sSql);
  
  if (pg_numrows($result) == 0) {
  
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
    exit;
  }
  
  $head1 = "Relatório de Bairros por Característica";
  $head2 = 'Filtros Utilizados:';
  
  if ($sZonaFiscal != '') {
    
    if (strlen($sZonaFiscal) >= 30) {
      
      $head3 = substr($sZonaFiscal, 0, 30).'...';
    } else {
      
      $head3 = $sZonaFiscal;
    }
  } else { 
    
    $head3 = 'Todas';
  }
  
  if ($sZonaEntrega != '') {
    if (strlen($sZonaEntrega) >= 30) {
      
      $head4 = substr($sZonaEntrega, 0, 30).'...';
    } else {
      
      $head4 = $sZonaEntrega;
    }
  } else {
    
    $head4 = 'Todas';
  }
  
  if ($sLogradouro != '') {
    
    if (strlen($sLogradouro) >= 30) {
      
      $head5 = substr($sLogradouro, 0, 30).'...';
    } else {
      
      $head5 = $sLogradouro;
    }
  } else { 
    
    $head5 = 'Todas';
  }
  
  if ($sBairro != '') {
    
    if (strlen($sBairro) >= 30) {
      
      $head6 = substr($sBairro, 0, 30).'...';
    } else {
      
      $head6 = $sBairro;
    }
  } else { 
    
    $head6 = 'Todas';
  }
  
  $head3 = 'Zona Fiscal:  ' . $head3;
  $head4 = 'Zona Entrega: ' . $head4;
  $head5 = 'Logradouro: '   . $head5;
  $head6 = 'Bairro: '       . $head6;
  
  $oPdf = new PDF(); 
  $oPdf->Open(); 
  $oPdf->AliasNbPages();
  $oPdf->setfillcolor(235);
  $oPdf->setfont('arial', 'b', 8);
  $troca     = 1;
  $alt       = 4;
  $total     = 0;
  $totalog   = 0;
  $codbairro = "";
  $p = 0;
  
  for($i = 0; $i < pg_numrows($result); $i++) {
    
    $oSql = db_utils::fieldsMemory($result, $i, true);
    
    if ($codbairro != $oSql->codbairro) {
      
      if ($codbairro != "") {
        
        $oPdf->cell(190, $alt, 'TOTAL DE MATRICULAS : ' . $total, "T", 1, "R", 0);
        $oPdf->ln();
        $oPdf->ln();
        $total = 0;
      }
       
      if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ) {
        
        $oPdf->addpage("P");
        $oPdf->setrightmargin(0.5);
        $troca = 0;
      }
      
      $oPdf->setfont('arial', 'b', 8);
       
      $oPdf->cell(0 , $alt, "Bairro : $oSql->codbairro - $oSql->bairro", 0, 1, "L", 0);
      $oPdf->cell(60, $alt, 'Logradouro'   , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Matricula'    , 1, 0, "C", 1);
      $oPdf->cell(60, $alt, 'Nome'         , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Zona Fiscal'  , 1, 0, "C", 1);
      $oPdf->cell(30, $alt, 'Zona Entrega' , 1, 1, "C", 1);
       
      $p         = 0;
      $codbairro = $oSql->codbairro;
      $totalog++;
    }
     
    if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
      
      $oPdf->addpage("P");
      $oPdf->setrightmargin(0.5);
      $oPdf->setfont('arial', 'b', 8);
      
      $oPdf->cell(0,$alt,"Bairro : $oSql->codbairro - $oSql->bairro", 0, 1, "L", 0);
      $oPdf->cell(60, $alt, 'Logradouro'  , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Matricula'   , 1, 0, "C", 1);
      $oPdf->cell(60, $alt, 'Nome'        , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Zona Fiscal' , 1, 0, "C", 1);
      $oPdf->cell(30, $alt, 'Zona Entrega', 1, 1, "C", 1);
      
      $p     = 0;
      $troca = 0;
    }
    
    $oPdf->setfont('arial', '', 7);
    $oPdf->cell(60, $alt, $oSql->logradouro . ' - ' . $oSql->numero . ' ' . ((($oSql->orientacao != '-') &&
      ($oSql->orientacao != '')) ? "($oSql->orientacao)" : ''), 0, 0, "L", $p);
    $oPdf->cell(20, $alt, $oSql->matricula   , 0, 0, "C", $p);
    $oPdf->cell(60, $alt, $oSql->nome        , 0, 0, "L", $p);
    $oPdf->cell(20, $alt, $oSql->zonafiscal  , 0, 0, "C", $p);
    $oPdf->cell(30, $alt, $oSql->zonaentrega , 0, 1, "C", $p);
    
    if ($p == 0) {
     
      $p = 1;
    } else {
      
      $p = 0;
    }
    
    $total++;
  }
  
  $oPdf->cell(190, $alt, 'TOTAL DE MATRICULAS : ' . $total, "T", 1, "R", 0);
  $oPdf->ln();
  
  $oPdf->setfont('arial', 'b', 8);
  $oPdf->cell(190, $alt, 'TOTAL DE REGISTROS : ' . $totalog, "T", 0, "R", 0);
  $oPdf->Output();
  
?>