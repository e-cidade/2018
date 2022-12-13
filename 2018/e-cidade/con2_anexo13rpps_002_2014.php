<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_orcparamseq_classe.php");

$orcparamrel   = new cl_orcparamrel;
$classinatura  = new cl_assinatura;
$clempresto    = new cl_empresto;
$clorcparamseq = new cl_orcparamseq;

$oGet     = db_utils::postMemory($_GET);
$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Instituição RPPS.');
} else {
	$oInstit  = db_utils::fieldsMemory($rsInstit,0);
}

$oBalancoFinanceiro = new BalancoFinanceiroRPPS(db_getsession("DB_anousu"), 134, $oGet->periodo);
$aLinhas            = $oBalancoFinanceiro->getDados();

$head2 =  $oInstit->nomeinst;
$head3 = "BALANÇO FINANCEIRO DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($oGet->periodo == 17){
	$head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{

  $oDaoPeriodo = new cl_periodo();
  $sSqlPeriodo = $oDaoPeriodo->sql_query_file($oGet->periodo);
  $rsPeriodo   = $oDaoPeriodo->sql_record($sSqlPeriodo);
  if (!$rsPeriodo) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Período informado não cadastrado no sistema.');
  }
  $oPeriodo =db_utils::fieldsMemory($rsPeriodo, 0);
	$head4 = "JANEIRO A ".strtoupper($oPeriodo->o114_descricao." DE ".db_getsession("DB_anousu"));
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt            = 4;
$pagina         = 1;

$pdf->addpage();
$pdf->setfont('arial','b',6);
$pdf->cell(0,$alt,"ART. 103 DA LEI 4.320/1964.","T",1,"L",0);
$pdf->cell(95, $alt,"RECEITA", "TBR", 0, "C");
$pdf->cell(95, $alt, "DESPESA", "TBL", 1, "C");
$pdf->cell(65, $alt,"TÍTULOS", "TBR", 0, "C");
$pdf->cell(30, $alt,"R$", "TBL", 0, "C");
$pdf->cell(65, $alt,"TÍTULOS", "TBL", 0, "C");
$pdf->cell(30, $alt,"R$", "TBL", 1, "C");

$iAlturaCabecalho   = $pdf->getY();
$iAlturaInicioExtra = 0;

$aLinhasComBordasEspeciais = array(
                                    7 => 'T',
                                   16 => 'T',
                                   10 => 'TB',
                                   11 => 'TB',
                                   12 => 'TB',
                                   19 => 'TB',
                                   20 => 'TB',
                                   21 => 'TB',
                                  );

foreach ($aLinhas as $iLinha =>  $oLinha) {

  $sBordaValor = 'R';
  if ($oLinha->ordem > 12) {

    if ($oLinha->ordem == 13) {
      $pdf->setY($iAlturaCabecalho);
    }
    $sBordaValor = 'L';
    $pdf->SetX(105);
  }

  if (isset($aLinhasComBordasEspeciais[$iLinha])) {
    $sBordaValor .= $aLinhasComBordasEspeciais[$iLinha];
  }
  $pdf->SetFont('arial', '', 6);
  if ($oLinha->totalizar) {
    $pdf->SetFont('arial', 'b', 6);
  }

  $pdf->cell(65, $alt, relatorioContabil::getIdentacao($oLinha->nivel).$oLinha->descricao, $sBordaValor, 0, "L");
  $pdf->cell(30, $alt, db_formatar($oLinha->vlrexatual, 'f'), $sBordaValor, 1, "R");

  if ($iLinha == 6) {
    $iAlturaInicioExtra = $pdf->getY();
  }
  if ($iLinha == 15) {
    $pdf->setY($iAlturaInicioExtra);
  }
}

$pdf->Line(170, $iAlturaCabecalho, 170, $pdf->getY());
$oBalancoFinanceiro->getRelatorioContabil()->getNotaExplicativa($pdf, $oGet->periodo);
$pdf->SetY($pdf->GetY() + 10);
$pdf->SetFont('arial', '', 7);
$oBalancoFinanceiro->getRelatorioContabil()->assinatura($pdf, 'BG');
$pdf->Output();
?>