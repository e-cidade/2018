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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once("libs/JSON.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("classes/db_empageconfgera_classe.php");

$oGet                = db_utils::postMemory($_GET);
$oDaoEmpAgeConfGera  = new cl_empageconfgera;

$iCodarq  = $oGet->iCodGera;
$iInstit  = db_getsession('DB_instit');
$dEmissao = date("d/m/Y", db_getsession("DB_datausu"));

$sCampos  = " e81_codmov,                                 ";
$sCampos .= " e83_codtipo as codtipo,                     ";
$sCampos .= " e83_descr,                                  ";
$sCampos .= " case                                        ";
$sCampos .= "   when e60_emiss is null                    ";
$sCampos .= "     then k17_data                           ";
$sCampos .= "   else e60_emiss                            ";
$sCampos .= " end,                                        ";
$sCampos .= " case                                        ";
$sCampos .= "   when e60_codemp is null                   ";
$sCampos .= "     then 'slip'                             ";
$sCampos .= "   else e60_codemp                           ";
$sCampos .= " end as e60_codemp,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when e82_codord is null                   ";
$sCampos .= "     then slip.k17_codigo                    ";
$sCampos .= "   else e82_codord                           ";
$sCampos .= " end as e82_codord,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when trim(a.z01_numcgm::text) is not null       ";
$sCampos .= "     then a.z01_numcgm                       ";
$sCampos .= "   when trim(cgmslip.z01_numcgm::text) is not null ";
$sCampos .= "     then cgmslip.z01_numcgm                 ";
$sCampos .= "   else cgm.z01_numcgm                       ";
$sCampos .= " end as z01_numcgm,                          ";
$sCampos .= " case                                        ";
$sCampos .= "   when trim(a.z01_nome) is not null         ";
$sCampos .= "     then a.z01_nome                         ";
$sCampos .= "   when trim(cgmslip.z01_nome) is not null   ";
$sCampos .= "     then cgmslip.z01_nome                   ";
$sCampos .= "   else cgm.z01_nome                         ";
$sCampos .= " end as z01_nome,                            ";
$sCampos .= " e81_valor                                   ";

$sOrdem  = " e83_codtipo, ";
$sOrdem .= " a.z01_nome,  ";
$sOrdem .= " cgm.z01_nome ";

$sWhere  = " e90_codgera = {$iCodarq} and         ";
$sWhere .= " e80_instit  = {$iInstit} and         ";
$sWhere .= " empageconfgera.e90_cancelado is true ";
/*
 * condição vinda do sql de cancelamento
 * desnecessaria para o relatorio de movimentos cancelados
 */
//$sWhere .= " e75_codret is null       and         ";

$sSql           = $oDaoEmpAgeConfGera->sql_query_arqcanc(null, null, $sCampos, $sOrdem, $sWhere );
$rsArquivo      = $oDaoEmpAgeConfGera->sql_record($sSql);

if ($oDaoEmpAgeConfGera->erro_status == "0") {

  $sMsgErro = "Não foi possível localizar os movimentos do arquivo {$iCodarq}.";
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
  exit;
}

$aDadosArquivos = db_utils::getCollectionByRecord($rsArquivo);

$oPdf = new PDF("L");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 6);

$iAlturalinha = 4;
$iFonte       = 6;

$head1  = "RELATÓRIO DE ARQUIVOS CANCELADOS";
$head3  = "ARQUIVO de FILTRO: {$iCodarq}";
$head5  = "DATA EMISSÃO: {$dEmissao}";

$oPdf->AddPage("L");

imprimirCabecalho($oPdf, $iAlturalinha, true);

foreach ($aDadosArquivos as $oIndiceDados => $oValorDados) {


      $dDataEmissao = db_formatar($oValorDados->e60_emiss, 'd');
      $nValor       = db_formatar($oValorDados->e81_valor , 'f');

      $oPdf->cell(20,  $iAlturalinha, $oValorDados->e81_codmov, "TBR",  0, "R", 0);
      $oPdf->cell(20,  $iAlturalinha, $oValorDados->e60_codemp, "LTBR", 0, "R", 0);
      $oPdf->cell(25,  $iAlturalinha, $oValorDados->e82_codord, "TBL",  0, "R", 0);
      $oPdf->cell(75,  $iAlturalinha, $oValorDados->z01_nome,   "TBL",  0, "L", 0);
      $oPdf->cell(30,  $iAlturalinha, $dDataEmissao,            "TBLR", 0, "C", 0);
      $oPdf->cell(30,  $iAlturalinha, $nValor,                  "TBLR", 0, "R", 0);
      $oPdf->cell(80,  $iAlturalinha, $oValorDados->e83_descr,  "LTB",  1, "L", 0);

      imprimirCabecalho($oPdf, $iAlturalinha, false);
}
$oPdf->output();


function imprimirCabecalho($oPdf, $iAlturalinha, $lImprime) {

  if ( $oPdf->GetY() > $oPdf->h - 25 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', 6);

    if ( !$lImprime ) {

      $oPdf->AddPage("L");
    }

      $oPdf->setfont('arial','b',6);
      $oPdf->cell(20,  $iAlturalinha, "MOVIMENTO",      "TBR",  0, "C", 1);
      $oPdf->cell(20,  $iAlturalinha, "EMPENHO",        "LTBR", 0, "C", 1);
      $oPdf->cell(25,  $iAlturalinha, "ORDEM / SLIP",   "TBL",  0, "C", 1);
      $oPdf->cell(75,  $iAlturalinha, "NOME",           "TBL",  0, "C", 1);
      $oPdf->cell(30,  $iAlturalinha, "DATA EMISSÃO",   "TBLR", 0, "C", 1);
      $oPdf->cell(30,  $iAlturalinha, "VALOR",          "TBLR", 0, "C", 1);
      $oPdf->cell(80,  $iAlturalinha, "CONTA PAGADORA", "LTB",  1, "C", 1);

  }
}

?>