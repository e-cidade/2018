<?
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
 
require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

$oPdf  = new PDF();

$oGet  = db_utils::postMemory($_GET);

/**
 * chaves separadas por virgula. ex: '1, 2, 3'
 */
$sZonaFiscal     = isset($oGet->zonafiscal)     ? $oGet->zonafiscal     : '';
$sZonaEntrega    = isset($oGet->zonaentrega)    ? $oGet->zonaentrega    : '';
$sLogradouro     = isset($oGet->logradouro)     ? $oGet->logradouro     : '';
$sBairro         = isset($oGet->bairro)         ? $oGet->bairro         : '';

$iConsumoInicial = isset($oGet->consumoinicial) ? $oGet->consumoinicial : '';
$iConsumoFinal   = isset($oGet->consumofinal)   ? $oGet->consumofinal   : '';

$dDataInicial    = isset($oGet->datainicial)    ? $oGet->datainicial    : '';
$dDataFinal      = isset($oGet->datafinal)      ? $oGet->datafinal      : '';

$sOrdenar        = isset($oGet->ordenar)        ? $oGet->ordenar        : '';

$sSql = "
select distinct 
       aguabase.x01_matric                    as matricula, 
       cgm.z01_nome                           as nome, 
       ruas.j14_nome                          as logradouro,  
       aguabase.x01_numero                    as numero,
       aguabase.x01_letra                     as letra,
       aguahidromatric2.x04_nrohidro          as numerohidrometro,
       aguahidromatric2.x04_dtinst            as datainstalacao,
       SUM(agualeitura.x21_consumo)           as consumototal, 
       ROUND(AVG(agualeitura.x21_consumo), 2) as consumomedio
  from aguabase
 inner join aguahidromatric                     on x04_matric                    = aguabase.x01_matric
 inner join agualeitura                         on agualeitura.x21_codhidrometro = x04_codhidrometro
 inner join cgm                                 on cgm.z01_numcgm                = aguabase.x01_numcgm
 inner join ruas                                on ruas.j14_codigo               = aguabase.x01_codrua
 inner join aguahidromatric as aguahidromatric2 on aguahidromatric2.x04_matric   = aguabase.x01_matric ";
 
$sWhere = " where aguahidromatric2.x04_codhidrometro = (select x04_codhidrometro 
                                                   from aguahidromatric 
                                                  where x04_matric = aguabase.x01_matric 
                                                  order by x04_dtinst desc limit 1) ";
 
if($sZonaFiscal != '') {
  
  $sWhere .= " and aguabase.x01_zona IN ($sZonaFiscal) ";
  
}
 
if($sZonaEntrega != '') {
  
  $sWhere .= " and aguabase.x01_entrega IN ($sZonaEntrega) ";
  
}
 
if($sLogradouro != '') {
  
  $sWhere .= " and aguabase.x01_codrua IN ($sLogradouro) ";
  
}
 
if($sBairro != '') {
  
  $sWhere .= " and aguabase.x01_codbairro IN ($sBairro)";
  
}
					
if($dDataInicial != '' and $dDataFinal != '') {
  
  $sWhere .= " and agualeitura.x21_dtleitura between '$dDataInicial' and '$dDataFinal' ";
  
} elseif ($dDataInicial != '' and $dDataFinal == '') {
  
  $sWhere .= "and agualeitura.x21_dtleitura >= '$dDataInicial' ";
  
} elseif ($dDataInicial == '' and $dDataFinal != '') {
  
  $sWhere .= "and agualeitura.x21_dtleitura <= '$dDataFinal' ";
  
}
					  
$sGroupBy = "group by 
                     aguabase.x01_matric, 
                     cgm.z01_nome, 
                     ruas.j14_nome, 
                     aguabase.x01_numero,
                     aguabase.x01_letra, 
                     aguahidromatric2.x04_nrohidro, 
                     aguahidromatric2.x04_dtinst ";

$sHaving = '';

if($iConsumoInicial != '' and $iConsumoFinal != '') {
  
  $sHaving = "having AVG(agualeitura.x21_consumo) between $iConsumoInicial and $iConsumoFinal ";
  
} elseif($iConsumoInicial != '' and $iConsumoFinal == '') {
  
  $sHaving = "having AVG(agualeitura.x21_consumo) > $iConsumoInicial ";
  
} elseif($iConsumoInicial == '' and $iConsumoFinal != '') {
  
  $sHaving = "having AVG(agualeitura.x21_consumo) < $iConsumoFinal ";
  
}

$head1 = 'Filtros Utilizados:';
$head2 = 'Zona Fiscal:  ' . ($sZonaFiscal     != '' ? $sZonaFiscal     : 'Todas');
$head3 = 'Zona Entrega: ' . ($sZonaEntrega    != '' ? $sZonaEntrega    : 'Todas');
$head4 = 'Logradouro: '   . ($sLogradouro     != '' ? $sLogradouro     : 'Todos');
$head5 = 'Bairro: '       . ($sBairro         != '' ? $sBairro         : 'Todos');
$head6 = 'Consumo: '      . ($iConsumoInicial != '' ? $iConsumoInicial : '0')          . ' até ' . ($iConsumoFinal != '' ? $iConsumoFinal : '9999999');
$head7 = 'Período: '      . ($dDataInicial    != '' ? $dDataInicial    : '00/00/0000') . ' até ' . ($dDataFinal    != '' ? $dDataFinal    : '00/00/0000');  



$sSql = "select * from ($sSql $sWhere $sGroupBy $sHaving ) as x order by $sOrdenar ";

$rSql = pg_query($sSql);
        
$oPdf->Open();
$oPdf->AliasNBPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);
$oPdf->DefOrientation = "L";

$troca = 1;
$alt   = 4;
$total = 0;

for($i = 0; $i < pg_numrows($rSql); $i++) {
  
  $oSql = db_utils::fieldsMemory($rSql, $i, true);
  
  if ($oPdf->gety() > $oPdf->h - 30 || $troca != 0 ){
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(20, $alt, 'Matricula'       , 1, 0, "C", 1);
      $oPdf->cell(60, $alt, 'Nome'            , 1, 0, "C", 1); 
      $oPdf->cell(60, $alt, 'Logradouro'      , 1, 0, "C", 1);
      $oPdf->cell(25, $alt, 'Hidrometro'      , 1, 0, "C", 1);
      $oPdf->cell(25, $alt, 'Data Instalação' , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Cons Médio'      , 1, 0, "C", 1);
      $oPdf->cell(20, $alt, 'Cons Total'      , 1, 0, "C", 1);
      $oPdf->cell(50, $alt, 'Observações'     , 1, 1, "C", 1);  
      $troca = 0;
      $p=0;
   }
   
   $oPdf->setfont('arial','',7);
   
   $oPdf->cell(20, $alt, $oSql->matricula       , 0, 0, "C", $p);
   $oPdf->cell(60, $alt, $oSql->nome            , 0, 0, "L", $p);
   $oPdf->cell(60, $alt, $oSql->logradouro . ' - ' . $oSql->numero . ' ' . ($oSql->letra != '' ? "($oSql->letra)" : ''), 0, 0, "L", $p);
   $oPdf->cell(25, $alt, $oSql->numerohidrometro, 0, 0, "C", $p);
   $oPdf->cell(25, $alt, $oSql->datainstalacao  , 0, 0, "C", $p);
   $oPdf->cell(20, $alt, $oSql->consumomedio    , 0, 0, "C", $p);
   $oPdf->cell(20, $alt, $oSql->consumototal    , 0, 0, "C", $p);
   $oPdf->cell(50, $alt, ''                     , 0, 1, "C", $p);
   
   if($p == 0) 
     $p = 1;
   else 
     $p = 0;
   
   $total++;
      
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$oPdf->Output();