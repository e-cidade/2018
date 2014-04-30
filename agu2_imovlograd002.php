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
include("classes/db_aguabase_classe.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postmemory($_GET);

$sComSem          = (isset($oGet->comsem) and $oGet->comsem != '') ? $oGet->comsem : '';

$listaLog         = (isset($oGet->listalog) and $oGet->listalog != '') ? $oGet->listalog : '';

$listaBairro      = (isset($oGet->listabairro) and $oGet->listabairro != '') ? $oGet->listabairro : '';

$listaZona        = (isset($oGet->listazona) and $oGet->listazona != '') ? $oGet->listazona : '';

$listaZonaEntrega = (isset($oGet->listazonaentrega) and $oGet->listazonaentrega != '') ? $oGet->listazonaentrega : '';

$dataInicial      = (isset($oGet->datainicial) and $oGet->datainicial != '') ? $oGet->datainicial : '';

$dataFinal        = (isset($oGet->datafinal) and $oGet->datafinal != '') ? $oGet->datafinal : '';

$claguabase = new cl_aguabase;
$claguabase->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('x11_complemento');
$clrotulo->label('x04_nrohidro');
$clrotulo->label('z01_nome');
$clrotulo->label('j13_descr');
$clrotulo->label('j50_descr');
$clrotulo->label('j85_descr');
$clrotulo->label('x04_dtinst');

$where = " (fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true or aguahidromatric.x04_codhidrometro is null) and (x11_tipo = 'P' or x11_matric is null)";

$head = "";

if(isset($listaLog) and ($listaLog != '')) 
{
  $where   .= " and x01_codrua $sComSem in ($listaLog) ";
  $head    .= "Logradouro\n";  
}
if(isset($listaBairro) and ($listaBairro != '')) {
  $where .= " and x01_codbairro $sComSem in ($listaBairro) ";
  $head  .= "Bairro\n";
}
if(isset($listaZona) and ($listaZona != '')) {
  $where .= " and x01_zona $sComSem in ($listaZona) ";
  $head  .= "Zona Fiscal\n";
}
if(isset($listaZonaEntrega) and ($listaZonaEntrega != '')) {
  $where .= " and x01_entrega $sComSem in ($listaZonaEntrega) ";
  $head  .= "Zona de Entrega\n";
}

if(($dataInicial != '') && ($dataFinal != '')) {
  $where .= " and x04_dtinst between '$dataInicial' and '$dataFinal' ";
}

$head2 = "RELATÓRIO DE IMOVEIS FILTRADOS POR: ";
$head3 = $head;



$campos  = "x01_matric, j14_nome, x01_numero, x01_letra, x11_complemento, x04_nrohidro, ";
$campos .= "case when x11_codconstr is not null then 'Predial' 
		   else 'Territorial' 
		   end as j31_descr, ";
$campos .= "j13_descr, j50_descr, j85_descr, x01_codrua, x04_dtinst ";

$orderBy = "j14_nome, x01_numero, x01_letra, x11_complemento";

$sqlaguabase = "select $campos
                  from aguabase
                 inner join ruas               on j14_codigo    = x01_codrua
                 inner join bairro             on j13_codi      = x01_codbairro
                  left join aguaconstr         on x11_matric    = x01_matric and x11_tipo = 'P' 
                  left join aguaconstrcar      on x12_codconstr = x11_codconstr
                  left join caracter           on x12_codigo    = j31_codigo
                 inner join aguahidromatric    on x04_matric    = x01_matric 
                  left join zonas              on j50_zona      = x01_zona
                  left join iptucadzonaentrega on j85_codigo    = x01_entrega 
                  where $where
               order by $orderBy";

$result = $claguabase->sql_record($sqlaguabase);

if ($claguabase->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$pdf = new PDF();

$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$total   = 0;
$troca   = 1;
$alt     = 4;
$total   = 0;
$totalog = 0;
$codrua  = "";
$p=0;

for($x = 0; $x < $claguabase->numrows; $x++) {
  db_fieldsmemory($result, $x, true);
  if ($codrua != $x01_codrua){
    if ($codrua!=""){
      
      $pdf->cell(280, $alt, 'TOTAL DE MATRICULAS : '.$total, "T", 1, "R", 0);
      $pdf->ln();
      $pdf->ln();
      $total = 0;
      
    }
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      
      $pdf->addpage("L");
      $pdf->setrightmargin(0.5);
      $troca = 0;
      
    }
    $pdf->setfont('arial', 'b', 7);
   
    $pdf->cell(0, $alt, $RLx01_codrua.": $x01_codrua - $j14_nome", 0, 1, "L", 0);
    
    $pdf->cell(15, $alt, $RLx01_matric, 1, 0, "C", 1);
    
    $pdf->cell(15, $alt, $RLx01_numero, 1, 0, "C", 1);
    
    $pdf->cell(25, $alt, $RLx11_complemento, 1, 0, "C", 1);
    
    $pdf->cell(30, $alt, "Nº Hidrometro", 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, "Tipo", 1, 0, "C", 1);
    
    $pdf->cell(35, $alt, $RLj13_descr, 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, $RLj50_descr, 1, 0, "C", 1);
    
    $pdf->cell(50, $alt, $RLj85_descr, 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, $RLx04_dtinst, 1, 0, "C", 1);
    
    $pdf->cell(50, $alt, "Observação", 1, 1, "C", 1);
    
    $p = 0;
    
    $codrua = $x01_codrua;
    
    $totalog++;
  }
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    
    $pdf->addpage("L");
    
    $pdf->setrightmargin(0.5);
    
    $pdf->setfont('arial', 'b', 7);

    $pdf->cell(0, $alt, $RLx01_codrua.": $x01_codrua - $j14_nome", 0, 1, "L", 0);
    
    $pdf->cell(15, $alt, $RLx01_matric, 1, 0, "C", 1);
    
    $pdf->cell(15, $alt, $RLx01_numero, 1, 0, "C", 1);
    
    $pdf->cell(25, $alt, $RLx11_complemento, 1, 0, "C", 1);
    
    $pdf->cell(30, $alt, $RLx04_nrohidro, 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, "Tipo", 1, 0, "C", 1);
    
    $pdf->cell(35, $alt, $RLj13_descr, 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, $RLj50_descr, 1, 0, "C", 1);
    
    $pdf->cell(50, $alt, $RLj85_descr, 1, 0, "C", 1);
    
    $pdf->cell(20, $alt, $RLx04_dtinst, 1, 0, "C", 1);
    
    $pdf->cell(50, $alt, "Observação", 1, 1, "C", 1);
    
    $p     = 0;
    $troca = 0;
  }
  
  $pdf->setfont('arial', '', 6);
  
  $pdf->cell(15, $alt, @$x01_matric, 0, 0, "C", $p);
  
  $pdf->cell(15, $alt, @$x01_numero.($x01_letra != ''?"-$x01_letra":""), 0, 0, "C", $p);
  
  $pdf->cell(25, $alt, @$x11_complemento, 0, 0, "C", $p);
  
  $pdf->cell(30, $alt, @$x04_nrohidro, 0, 0, "C", $p);
  
  $pdf->cell(20, $alt, substr(@$j31_descr, 0, 35), 0, 0, "L", $p);
  
  $pdf->cell(35, $alt,  $j13_descr, 0, 0, "C", $p);
  
  $pdf->cell(20, $alt, $j50_descr, 0, 0, "C", $p);
  
  $pdf->cell(50, $alt, $j85_descr, 0, 0, "C", $p);
  
  $pdf->cell(20, $alt, $x04_dtinst, 0, 0, "C", $p);
  
  $pdf->cell(50, $alt, "", 0, 1, "L", $p);
  
  if ($p == 0) {
   	$p = 1;
  }else {
   	$p = 0;
  }
  $total++;
  
}
$pdf->cell(280, $alt, 'TOTAL DE MATRICULAS : '.$total, "T", 1, "R", 0);
$pdf->ln();

$pdf->setfont('arial', 'b', 8);
$pdf->cell(280, $alt, 'TOTAL DE LOGRADOUROS  :  '.$totalog, "T", 0, "L", 0);
$pdf->Output();
?>