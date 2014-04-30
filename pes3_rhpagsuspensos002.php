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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("libs/db_utils.php");
include("classes/db_rhsuspensaopag_classe.php");

$oGet   = db_utils::postMemory($_GET);

$cl_rhsuspensaopag = new cl_rhsuspensaopag();

switch ($oGet->sTipoFolha){ 
  case 'gerfsal':
     $sSulfixoTitulo = 'SALÁRIO';
     $sSigla         = 'r14';
  break;
  case 'gerfres':
     $sSulfixoTitulo = 'RESCISÄO';
     $sSigla         = 'r20';
  break;
  case 'gerfs13':
     $sSulfixoTitulo = '13o SALÁRIO';
     $sSigla         = 'r35';
  break;
  case 'gerfadi':
     $sSulfixoTitulo = 'ADIANTAMENTO';
     $sSigla         = 'r22';
  break;
  case 'gerfcom':
     $sSulfixoTitulo = 'COMPLEMENTAR';
     $sSigla         = 'r48';
  break;
  case 'gerfprovfer':
     $sSulfixoTitulo = 'PROVISÃO DE FÉRIAS';
     $sSigla         = 'r93';
  break;
  case 'gerfprovs13':
     $sSulfixoTitulo = 'PROVISÃO 13o. SALÁRIO';
     $sSigla         = 'r94';
  break;
}

$sSqlSuspensao     = "select                                                                   ";
$sSqlSuspensao    .= "rh101_regist,                                                            ";
$sSqlSuspensao    .= "z01_nome,                                                                ";
$sSqlSuspensao    .= "rh02_seqpes,                                                             ";
$sSqlSuspensao    .= "r70_descr,                                                               ";
$sSqlSuspensao    .= "rh37_descr,                                                              ";
$sSqlSuspensao    .= "(select sum({$sSigla}_valor) from {$oGet->sTipoFolha}                    ";
$sSqlSuspensao    .= "       where {$sSigla}_pd = 1                                            ";
$sSqlSuspensao    .= "        and  {$sSigla}_anousu =".db_anofolha();
$sSqlSuspensao    .= "        and  {$sSigla}_mesusu =".db_mesfolha();
$sSqlSuspensao    .= "        and  {$sSigla}_regist = rhsuspensaopag.rh101_regist              ";
$sSqlSuspensao    .= "        and  {$sSigla}_rubric < 'R950'                                   ";
$sSqlSuspensao    .= ") as vlrBruto,";
$sSqlSuspensao    .= "(select sum({$sSigla}_valor) from {$oGet->sTipoFolha}                    ";
$sSqlSuspensao    .= "      where  {$sSigla}_pd = 2                                            ";
$sSqlSuspensao    .= "        and  {$sSigla}_anousu =".db_anofolha();
$sSqlSuspensao    .= "        and  {$sSigla}_mesusu =".db_mesfolha();
$sSqlSuspensao    .= "        and  {$sSigla}_regist = rhsuspensaopag.rh101_regist              ";
$sSqlSuspensao    .= "        and  {$sSigla}_rubric < 'R950'                                   ";
$sSqlSuspensao    .= ") as vlrDesconto                                                         ";
$sSqlSuspensao    .= "from rhsuspensaopag                                                      ";
$sSqlSuspensao    .= "    inner join rhpessoal    on rh01_regist  = rh101_regist               ";
$sSqlSuspensao    .= "    inner join cgm          on rh01_numcgm  = z01_numcgm                 ";
$sSqlSuspensao    .= "    inner join rhpessoalmov on rh02_regist  = rh101_regist               ";
$sSqlSuspensao    .= "                           and rh02_anousu  = ".db_anofolha();
$sSqlSuspensao    .= "                           and rh02_mesusu  = ".db_mesfolha();
$sSqlSuspensao    .= "                           and rh02_instit  = ".db_getsession('DB_instit');
$sSqlSuspensao    .= "    left join rhlota        on rh02_lota    = r70_codigo                 ";
  
$sSqlSuspensao    .= "    left join rhpescargo    on rh02_seqpes  = rh20_seqpes                ";
$sSqlSuspensao    .= "    left join rhfuncao      on rh02_funcao  = rh37_funcao                ";
$sSqlSuspensao    .= "                           and rh37_instit  = ".db_getsession('DB_instit');
$sSqlSuspensao    .= "WHERE rh101_dtdesativacao is null";


$rsRhSuspensao     = $cl_rhsuspensaopag->sql_record($sSqlSuspensao);
$isuspensaonumRows = $cl_rhsuspensaopag->numrows;
$aSuspensoes       = db_utils::getColectionByRecord($rsRhSuspensao);

$pdf    = new PDF(); 
$head3  = "RELATÓRIO DE PAGAMENTOS SUSPENSOS";
$head5  = "Tipo de Folha: ".$sSulfixoTitulo;
$pdf   -> Open(); 
$pdf   -> AddPage('L');
$pdf   -> AliasNbPages(); 
$pdf   -> setfillcolor(235);

cabecalhoPagina($pdf);

foreach ($aSuspensoes as $oSuspensao) {
  
	if ($pdf->GetY() > $pdf->h - 25){

		$pdf->AddPage();
    cabecalhoPagina($pdf);
  }   
  $pdf->Cell(15, 5, $oSuspensao->rh101_regist,1,0,'C',0,"","");
  $pdf->Cell(86, 5, $oSuspensao->z01_nome,1,0,'L',0,"","");
  $pdf->Cell(60, 5, $oSuspensao->rh37_descr,1,0,'L',0,"","");
  $pdf->Cell(64, 5, $oSuspensao->r70_descr,1,0,'L',0,"","");
  $pdf->Cell(18, 5, db_formatar($oSuspensao->vlrbruto,"f"),1,0,'R',0,"","");
  $pdf->Cell(18, 5, db_formatar($oSuspensao->vlrdesconto,"f"),1,0,'R',0,"","");
  $pdf->Cell(18, 5, db_formatar($oSuspensao->vlrbruto - $oSuspensao->vlrdesconto,"f"),1,1,'R',0,"","");

}
$pdf->Output();


function cabecalhoPagina(&$pdf) {
  
  $pdf->SetFont('','BI');
  $pdf->Cell(15, 4, "Matrícula",1,0,'C',1,"","");
  $pdf->Cell(86, 4, "Nome",1,0,'C',1,"","");
  $pdf->Cell(60, 4, "Cargo",1,0,'C',1,"","");
  $pdf->Cell(64, 4, "Lotação",1,0,'C',1,"","");
  $pdf->Cell(18, 4, "Valor Bruto",1,0,'C',1,"","");
  $pdf->Cell(18, 4, "Descontos",1,0,'C',1,"","");
  $pdf->Cell(18, 4, "Total Líquido",1,1,'C',1,"","");
  $pdf->SetFont('','');
}
?>