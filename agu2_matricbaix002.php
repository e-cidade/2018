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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("classes/db_aguabasebaixa_classe.php");

$oGet = db_utils::postMemory($_GET);

$dDataInicial      = (isset($oGet->datainicial)      and $oGet->datainicial      != '') ? $oGet->datainicial      : '';
$dDataFinal        = (isset($oGet->datafinal)        and $oGet->datafinal        != '') ? $oGet->datafinal        : '';
$listaBairros      = (isset($oGet->listabairro)      and $oGet->listabairro      != '') ? $oGet->listabairro      : '';
$listaZonasEntrega = (isset($oGet->listazonaentrega) and $oGet->listazonaentrega != '') ? $oGet->listazonaentrega : '';

$claguabasebaixa = new cl_aguabasebaixa();
$objPDF          = new PDF();

$clrotulo = new rotulocampo;
$clrotulo->label('x01_matric');
$clrotulo->label('j14_nome');
$clrotulo->label('x01_numero');
$clrotulo->label('x01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('x08_data');
$clrotulo->label('j13_descr');

if($oGet->orderby == 1) {
  
  $orderBy = "x01_matric";
  $sTipoOrdem = "Matrícula";
  
}elseif($oGet->orderby == 2) {
  
  $orderBy = "x01_numcgm";
  $sTipoOrdem = "Número CGM";
  
}elseif($oGet->orderby == 3) {
  
  $orderBy = "z01_nome";
  $sTipoOrdem = "Nome";
  
}elseif($oGet->orderby == 4) {
  
  $orderBy = "x08_data";
  $sTipoOrdem = "Data Baixa";
  
}
elseif($oGet->orderby == 5) {
  
  $orderBy = "j14_nome, x01_numero";
  $sTipoOrdem = "Logradouro";
  
}

$sWhere = "";
if(($dDataInicial != '') and ($dDataFinal != '')) {

  $sWhere = "x08_data between '$dDataInicial' and '$dDataFinal' ";

}

if(isset($listaBairros) and ($listaBairros != '')) {
  
  $sWhere .= " and x01_codbairro IN ($listaBairros) ";
  
}

if(isset($listaZonasEntrega) and ($listaZonasEntrega != '')) {
  
  $sWhere .= " and x01_entrega IN ($listaZonasEntrega) ";
  
}

$rsaguabasebaixa = $claguabasebaixa->sql_record($claguabasebaixa->sql_query(null, "x01_matric, j14_nome, x01_numero, x01_numcgm, z01_nome, j13_descr, x08_data", $orderBy, $sWhere));

if($claguabasebaixa->numrows == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.");
  exit();
}

$head3 = "CADASTRO DE MATRÍCULAS BAIXADAS NO DAEB ";
$head5 = "ORDENADO POR $sTipoOrdem";

$objPDF->Open(); 
$objPDF->AliasNbPages();

$iTotal = 0;

$objPDF->setfillcolor(235);
$objPDF->setfont('arial','b',8);

$iTroca = 1;
$iPrenc = 0;
$iAlt   = 4;
$iTotal = 0;

for($i = 0; $i < $claguabasebaixa->numrows; $i++) {
  
  $oAguaBaseBaixa = db_utils::fieldsMemory($rsaguabasebaixa, $i, true);
  
  if ($objPDF->gety() > $objPDF->h - 30 || $iTroca != 0 ){
      $objPDF->addpage("L");
      $objPDF->setfont("arial", "b", 8);
      $objPDF->cell(20, $iAlt, $RLx01_matric , 1, 0, "C", 1);
      $objPDF->cell(65, $iAlt, $RLz01_nome   , 1, 0, "L", 1); 
      $objPDF->cell(65, $iAlt, $RLj14_nome   , 1, 0, "C", 1);
      $objPDF->cell(20, $iAlt, 'Data Baixa' , 1, 0, "C", 1);
      $objPDF->cell(55, $iAlt, $RLj13_descr  , 1, 0, "C", 1); 
 
      $objPDF->cell(55, $iAlt, 'Observações'   , 1, 1, "C", 1); 

      $iTroca = 0;
      $iPrenc = 1;
  }
  
  if ($iPrenc == 0) {
    
    $iPrenc = 1;
    
  }else {
    
    $iPrenc = 0;
    
  }
    
  $objPDF->setfont("arial", "", 7);
  $objPDF->cell(20, $iAlt, $oAguaBaseBaixa->x01_matric, 0, 0, "C", $iPrenc);
  $objPDF->cell(65, $iAlt, $oAguaBaseBaixa->z01_nome  , 0, 0, "L", $iPrenc); 
  $objPDF->cell(65, $iAlt, $oAguaBaseBaixa->j14_nome.' - '.$oAguaBaseBaixa->x01_numero, 0, 0, "L", $iPrenc);
  $objPDF->cell(20, $iAlt, $oAguaBaseBaixa->x08_data, 0, 0, "C", $iPrenc); 
  $objPDF->cell(55, $iAlt, $oAguaBaseBaixa->j13_descr, 0, 0, "L", $iPrenc);  
 
  $objPDF->cell(55, $iAlt, '' , 0, 1, "C", $iPrenc); 
  
  $iTotal++;
  
}

$objPDF->setfont("arial", "b", 8);
$objPDF->cell(280, $iAlt, "TOTAL DE REGISTROS  :  ".$iTotal, "T", 0, "L", 0);

$objPDF->Output();


?>