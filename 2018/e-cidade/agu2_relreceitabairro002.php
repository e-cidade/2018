<?
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
include("libs/db_utils.php");
include("classes/db_aguabase_classe.php");

$oGet          = db_utils::postMemory($_GET);

$ano           = (isset($oGet->ano)          and $oGet->ano          != '') ? $oGet->ano          : '';
$mes           = (isset($oGet->mes)          and $oGet->mes          != '') ? $oGet->mes          : '';
$listaBairros  = (isset($oGet->listabairro)  and $oGet->listabairro  != '') ? $oGet->listabairro  : '';
$listaReceitas = (isset($oGet->listareceita) and $oGet->listareceita != '') ? $oGet->listareceita : '';

$claguabase    = new cl_aguabase();
$objPDF        = new PDF();

$clrotulo      = new rotulocampo;
$clrotulo->label('x22_exerc');
$clrotulo->label('x22_mes');

$sWhereData = "";

if(($ano != '') and ($mes != '')) {

  $sWhereData = " and x22_exerc = {$ano} and x22_mes = {$mes} ";

}

$sWhereBairros = "";

if(isset($listaBairros) and ($listaBairros != '')) {
  
  $sWhereBairros = " and j13_codi in ($listaBairros) ";
  
}

$sWhereReceitas = "";

if(isset($listaReceitas) and ($listaReceitas != '')) {
  
  $sWhereReceitas = " and x25_receit in ($listaReceitas) ";
  
}

$sql  = '     select x22_exerc                                   as "ano"        ,              ';
$sql .= '            x22_mes                                     as "mes"        ,              ';
$sql .= '            j13_codi                                    as "codi_bairro",              ';
$sql .= '            j13_descr                                   as "desc_bairro",              ';
$sql .= '            k02_descr                                   as "receita"    ,              ';
$sql .= '            count( distinct x01_matric )                as "matriculas" ,              ';
$sql .= '            round( sum( coalesce( x23_valor, 0 ) ), 2 ) as "faturado"   ,              ';
$sql .= '            round( sum( coalesce( k00_valor, 0 ) ), 2 ) as "arrecadado"                ';
$sql .= '       from aguabase                                                                   ';
$sql .= ' inner join bairro           on j13_codi            = x01_codbairro                    ';
$sql .= $sWhereBairros;
$sql .= ' inner join aguacalc         on x22_matric          = x01_matric                       ';
$sql .= $sWhereData;
$sql .= ' inner join aguacalcval      on x23_codcalc         = x22_codcalc                      ';
$sql .= ' inner join aguaconsumotipo  on x25_codconsumotipo  = x23_codconsumotipo               ';
$sql .= $sWhereReceitas;
$sql .= ' inner join tabrec           on k02_codigo          = x25_receit                       ';
$sql .= '  left join arrepaga         on k00_numpre          = x22_numpre                       ';
$sql .= '                            and k00_receit          = x25_receit                       ';
$sql .= '   group by x22_exerc,                                                                 ';
$sql .= '            x22_mes  ,                                                                 ';
$sql .= '            j13_codi ,                                                                 ';
$sql .= '            j13_descr,                                                                 ';
$sql .= '            k02_descr                                                                  ';
$sql .= '   order by x22_exerc,                                                                 ';
$sql .= '            x22_mes  ,                                                                 ';
$sql .= '            j13_codi ,                                                                 ';
$sql .= '            j13_descr,                                                                 ';
$sql .= '            k02_descr desc                                                             ';

$rsaguabase = $claguabase->sql_record($sql);

if($claguabase->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.");
  exit();
}

$head3 = "RELATÓRIO DE RECEITAS POR BAIRRO NO DAEB ";

$objPDF->Open(); 
$objPDF->AliasNbPages();

$iTotal = 0;

$objPDF->setfillcolor(235);
$objPDF->setfont('arial','b',8);

$iTroca = 1;
$iPrenc = 0;
$iAlt   = 4;
$iTotal = 0;

for($i = 0; $i < $claguabase->numrows; $i++) {
  
  $oAguaBase = db_utils::fieldsMemory($rsaguabase, $i, true);
  
  if ($objPDF->gety() > $objPDF->h - 30 || $iTroca != 0 ){
  	
    $objPDF->addpage("L");
    $objPDF->setfont("arial", "b", 8);
    $objPDF->cell(20, $iAlt, $RLx22_exerc  , 1, 0, "C", 1);
    $objPDF->cell(20, $iAlt, $RLx22_mes    , 1, 0, "C", 1); 
    $objPDF->cell(60, $iAlt, "Bairro"      , 1, 0, "C", 1);
    $objPDF->cell(60, $iAlt, "Receita"     , 1, 0, "C", 1);
    $objPDF->cell(30, $iAlt, "Matrículas"  , 1, 0, "C", 1); 
    $objPDF->cell(45, $iAlt, "Faturado"    , 1, 0, "C", 1);
    $objPDF->cell(45, $iAlt, "Arrecadado"  , 1, 1, "C", 1);
    
    $iTroca = 0;
    $iPrenc = 0;
  }
  
  $objPDF->setfont("arial", "", 7);
  $objPDF->cell(20, $iAlt, $oAguaBase->ano                                             , 0, 0, "C", $iPrenc);
  $objPDF->cell(20, $iAlt, $oAguaBase->mes                                             , 0, 0, "C", $iPrenc); 
  $objPDF->cell(60, $iAlt, $oAguaBase->desc_bairro                                     , 0, 0, "L", $iPrenc);
  $objPDF->cell(60, $iAlt, $oAguaBase->receita                                         , 0, 0, "L", $iPrenc);
  $objPDF->cell(30, $iAlt, number_format( $oAguaBase->matriculas, 0, ',', '.' )        , 0, 0, "R", $iPrenc); 
  $objPDF->cell(45, $iAlt, 'R$ '.number_format( $oAguaBase->faturado  , 2, ',', '.' )  , 0, 0, "R", $iPrenc);
  $objPDF->cell(45, $iAlt, 'R$ '.number_format( $oAguaBase->arrecadado, 2, ',', '.' )  , 0, 1, "R", $iPrenc);  
  
  $iTotalMatriculas += $oAguaBase->matriculas;
  $iTotalFaturado   += $oAguaBase->faturado;
  $iTotalArrecadado += $oAguaBase->arrecadado;
  $iTotal++;
  $iPrenc = ( $iPrenc == 0 ? 1 : 0 );
     
}

$objPDF->setfont("arial", "b", 8);
$objPDF->cell(160, $iAlt, "TOTAL DE REGISTROS  :  ".$iTotal, "T", 0, "L", 0);
$objPDF->cell(30 , $iAlt, number_format( $iTotalMatriculas, 0, ',', '.' )        , "T", 0, "R", 0);
$objPDF->cell(45 , $iAlt, 'R$ '.number_format( $iTotalFaturado  , 2, ',', '.' )  , "T", 0, "R", 0);
$objPDF->cell(45 , $iAlt, 'R$ '.number_format( $iTotalArrecadado, 2, ',', '.' )  , "T", 1, "R", 0);

$objPDF->Output();


?>