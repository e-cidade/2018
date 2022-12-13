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

require_once("fpdf151/pdf.php");
require_once("fpdf151/assinatura.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");

$oGet     = db_utils::postMemory($_GET);
$rsInstit = db_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

if (pg_num_rows($rsInstit) == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe Instituição RPPS.');
} else {
  $oInstit  = db_utils::fieldsMemory($rsInstit,0);
}

$oBalancoPatrimonial = new BalancoPatrimonialRPPS(db_getsession("DB_anousu"), 135, $oGet->periodo);
$aLinhas             = $oBalancoPatrimonial->getDados();

$head2 =  $oInstit->nomeinst;
$head3 = "BALANÇO PATRIMONIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
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
$iAltura = 4;

$pdf->addpage();
$pdf->setfont('arial', 'b', 6);
$pdf->cell(0, $iAltura, "ART. 105 DA LEI 4.320/1964.","T",1,"L",0);
$pdf->cell(65, $iAltura, "ATIVO", "TBR", 0, "C");
$pdf->cell(30, $iAltura," R$", "TBR", 0, "C");
$pdf->cell(65, $iAltura, "PASSIVO", "TBL", 0, "C");
$pdf->cell(30, $iAltura, "R$", "TBL", 1, "C");

$iAlturaInicioRegistros    = $pdf->getY();
$aLinhasComBordasEspeciais = array(21 => 'T',
                                   51 => 'T',
                                   33 => 'TB',
                                   58 => 'TB',
                                   34 => 'T',
                                   63 => 'T',
                                   41 => 'TB',
                                   70 => 'TB'
                                   );

$iAlturaInicioPassivoPermanente = 0;
$iAlturaInicioPatrimonioLiquido = 0;
foreach ($aLinhas as $iLinha => $oLinha) {

  $sBordaValor = 'R';
  if ($oLinha->ordem >= 42) {

    if ($oLinha->ordem == 42) {
      $pdf->setY($iAlturaInicioRegistros);
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

  /**
   * na linha 34, o relatorio tem um espasçamento entre o total do "ativo real" e compensado
   */
  if ($iLinha == 34) {
    $pdf->SetY($pdf->getY() + $iAltura * 4);
  }
  $pdf->cell(65, $iAltura, relatorioContabil::getIdentacao($oLinha->nivel).$oLinha->descricao, $sBordaValor, 0, "L");
  $pdf->cell(30, $iAltura, db_formatar($oLinha->vlrexatual, 'f'), $sBordaValor, 1, "R");

  /**
   * A despesa possui menos linhas que o bloco das Receitas. para alinhas o layout corretamente, devemos guardar a
   * altura que foi escrito a ultima linha da Receita
   */
  if ($iLinha == 20) {
    $iAlturaInicioPassivoPermanente = $pdf->getY();
  }

  if ($iLinha == 32) {
    $iAlturaInicioPatrimonioLiquido = $pdf->getY();
  }

  if ($iLinha == 50) {
    $pdf->setY($iAlturaInicioPassivoPermanente);
  }

  if ($iLinha == 57) {
    $pdf->setY($iAlturaInicioPatrimonioLiquido);
  }
}

$pdf->Line(75, $iAlturaInicioRegistros, 75, $pdf->getY());
$pdf->Line(170, $iAlturaInicioRegistros, 170, $pdf->getY());

$oBalancoPatrimonial->getRelatorioContabil()->getNotaExplicativa($pdf, $oGet->periodo);
$pdf->SetFont('arial', '', 7);
$pdf->ln();
$oBalancoPatrimonial->getRelatorioContabil()->assinatura($pdf, 'BG');

$pdf->Output();