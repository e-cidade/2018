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
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_utils.php");

$classinatura = new cl_assinatura;
db_postmemory($HTTP_GET_VARS);

$instituicao = str_replace('-',',',$db_selinstit);

$iAnoUsu              = db_getsession("DB_anousu");
$iPaginasGeradas      = 1 ;
$iPaginaGeradaInicial = 1 ;
$iPaginaGeradaFinal   = 0 ;
$iQuebraPag           = 500;
$sNomeArquivos        = '';

if (strlen($data_ini < 9 )|| strlen($data_fim < 9))  {
   $data_ini="";
   $data_fim="";
}

$sSql  = "select c69_codlan,                                                          ";
$sSql .= "       c69_data,                                                            ";
$sSql .= "       c69_debito,                                                          ";
$sSql .= "       ca.c60_descr as debito_descr,                                        ";
$sSql .= "       c69_credito,                                                         ";
$sSql .= "       cb.c60_descr as credito_descr,                                       ";
$sSql .= "       c69_valor,                                                           ";
$sSql .= "       c69_codhist,                                                         ";
$sSql .= "       c50_descr ,                                                          ";
$sSql .= "       c72_complem                                                          ";
$sSql .= "  from conlancamval                                                         ";
$sSql .= "       inner join conplanoreduz ra on ra.c61_anousu = {$iAnoUsu}            ";
$sSql .= "                                  and ra.c61_reduz  = c69_debito            ";
$sSql .= "                                  and ra.c61_instit in ( {$instituicao} )   ";
$sSql .= "       inner join conplano ca      on ca.c60_codcon = ra.c61_codcon         ";
$sSql .= "                                  and ca.c60_anousu = ra.c61_anousu         ";
$sSql .= "       inner join conplanoreduz rb on rb.c61_anousu = {$iAnoUsu}            ";
$sSql .= "                                  and rb.c61_reduz  = c69_credito           ";
$sSql .= "                                  and rb.c61_instit in ( {$instituicao} )   ";
$sSql .= "      inner join conplano cb       on cb.c60_codcon = rb.c61_codcon         ";
$sSql .= "                                  and cb.c60_anousu = rb.c61_anousu         ";
$sSql .= "      left outer join conhist  on c50_codhist       = c69_codhist           ";
$sSql .= "      left join conlancamcompl on c72_codlan        = c69_codlan            ";
$sSql .= "where c69_data between '{$data_ini}' and '{$data_fim}'                      ";
$sSql .= "order by c69_data, c69_codlan                                               ";

$rsLancamentos     = db_query($sSql);
$iQtdLancamentos   = pg_numrows($rsLancamentos);
if ($rsLancamentos==false){
 db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado');     
}
   
$sSqlInstituicoes  = "select codigo,   "; 
$sSqlInstituicoes .= "       nomeinst  ";
$sSqlInstituicoes .= "  from db_config "; 
$sSqlInstituicoes .= " where codigo in (".str_replace('-',', ',$db_selinstit).")";
$rsInstituicoes = db_query($sSqlInstituicoes);

$sDescricaoInstituicoes = '';
$sVirgula = '';
for($xins = 0; $xins < pg_numrows($rsInstituicoes); $xins++){
    $oDadosInstit = db_utils::fieldsMemory($rsInstituicoes,$xins);
    $sDescricaoInstituicoes .= $sVirgula.$oDadosInstit->nomeinst ;
    $sVirgula = ',';
}				 
      
$head2 = "LIVRO DIÁRIO";
$head3 = "PERÍODO : ".db_formatar($data_ini,'d')." a ".db_formatar($data_fim,'d');
$head4 = $sDescricaoInstituicoes; 
$head5 = "";
$head6 = "";
////////////////////// pegar o nome das instituições
$head7 = "";
$head8 = "";
$head9 = "";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->AddPage("L");
$oPdf->SetAutoPageBreak(false);
$oPdf->SetFillColor(235);
$oPdf->setY(35);
$oPdf->setX(5);

if (isset($pagina_ini) && trim(@$pagina_ini)==""){
     $pagina_ini = 1;
}

$oPdf->SetStartPage($pagina_ini);

$iTotal    = 0;
$iSubTotal = 0;
$dData     = "";
$lImprime  = true;

for ($iInd=0;$iInd <$iQtdLancamentos;$iInd++) {    
  $oDadosLancamento = db_utils::fieldsMemory($rsLancamentos,$iInd);
       
  if ($iPaginasGeradas >= $iQuebraPag) {
    
      $iPaginaGeradaFinal        = $oPdf->PageNo();
      $sArquivo       = "tmp/livro_diario_de_".$iPaginaGeradaInicial."_ate_".$iPaginaGeradaFinal."_".date('dmYHis').".pdf";
      $sNomeArquivos .= $sArquivo."#Download do livro diários da página ".$iPaginaGeradaInicial." ate ".$iPaginaGeradaFinal."|";
      $iPaginaGeradaInicial = $oPdf->PageNo()+1;
      $oPdf->Output($sArquivo, false, true);
      unset($oPdf);
      
      $oPdf = new PDF();
      $oPdf->Open();
      $oPdf->AliasNbPages();
      $oPdf->AddPage("L");
      
      $oPdf->SetFillColor(235);
      $oPdf->setY(35);
      $oPdf->setX(5);
      
      
      $iPaginasGeradas = 1;
      
      $oPdf->SetStartPage($iPaginaGeradaInicial);
      
      $oPdf->setX(10);
      $oPdf->Cell(20,4,'CODLANC',"B",0,"R",0);
      $oPdf->Cell(20,4,'DATA',"B",0,"C",0);
      $oPdf->Cell(40,4,'HIST',"B",0,"L",0);
      $oPdf->Cell(20,4,'C.DEBITO',"B",0,"R",0);
      $oPdf->Cell(55,4,'DESCR',"B",0,"L",0);
      $oPdf->Cell(20,4,'C.CREDITO',"B",0,"R",0);
      $oPdf->Cell(55,4,'DESCR',"B",0,"L",0);
      $oPdf->Cell(30,4,'VALOR',"B",1,"R",0);
      $lImprime = false;
      $iPreenchimentoCelula = 1;
      
  }
    
  if ($oPdf->gety() > $oPdf->h - 35 ) {  //testa quebra pagina
    
    $oPdf->addpage("L");
    $iPaginasGeradas++;
    $lImprime=true;
  }
     
  if ($lImprime == true){ //header 
     $oPdf->setX(10);
     $oPdf->Cell(20,4,'CODLANC',"B",0,"R",0);
     $oPdf->Cell(20,4,'DATA',"B",0,"C",0);
	   $oPdf->Cell(40,4,'HIST',"B",0,"L",0);
     $oPdf->Cell(20,4,'C.DEBITO',"B",0,"R",0);
     $oPdf->Cell(55,4,'DESCR',"B",0,"L",0);
     $oPdf->Cell(20,4,'C.CREDITO',"B",0,"R",0);
     $oPdf->Cell(55,4,'DESCR',"B",0,"L",0);
     $oPdf->Cell(30,4,'VALOR',"B",1,"R",0);
	   $lImprime = false;
	   $iPreenchimentoCelula = 1;
  } 

  if ($iPreenchimentoCelula == 1) {
	   $iPreenchimentoCelula = 0;
   } else { 
	   $iPreenchimentoCelula = 1;
   }  

	 if ($dData=="") {
	   
	   $dData      = $oDadosLancamento->c69_data;
     $iSubTotal += $oDadosLancamento->c69_valor;
     
	 } else {
	           
	    if ($oDadosLancamento->c69_data == $dData) {
	    	$iSubTotal += $oDadosLancamento->c69_valor;
	    } else {
	      
		    $oPdf->SetFont('arial','B',9);    
		    $oPdf->setX(240);
		    $oPdf->Cell(30,4,"SUBTOTAL     ".db_formatar($iSubTotal, "f"),"0",1,"R",$iPreenchimentoCelula);
		    
		    $dData     = $oDadosLancamento->c69_data; 
		    $iSubTotal = 0;
	      $iSubTotal += $oDadosLancamento->c69_valor;
	      
	    }
	    
	 }
	     
   $oPdf->SetFont('arial','',7);
   $oPdf->setX(10);
   
   $oPdf->Cell(20, 4, $oDadosLancamento->c69_codlan                , "0", 0, "R", $iPreenchimentoCelula);
   $oPdf->Cell(20, 4, db_formatar($oDadosLancamento->c69_data,"d") , "0", 0, "C", $iPreenchimentoCelula);
   $oPdf->Cell(40, 4, $oDadosLancamento->c50_descr                 , "0", 0, "L", $iPreenchimentoCelula);
   $oPdf->Cell(20, 4, $oDadosLancamento->c69_debito                , "0", 0, "R", $iPreenchimentoCelula);
   $oPdf->Cell(55, 4, substr($oDadosLancamento->debito_descr,0,40) , "0", 0, "L", $iPreenchimentoCelula);
   $oPdf->Cell(20, 4, $oDadosLancamento->c69_credito               , "0", 0, "R", $iPreenchimentoCelula);
   $oPdf->Cell(55, 4, substr($oDadosLancamento->credito_descr,0,40), "0", 0, "L", $iPreenchimentoCelula);
   $oPdf->Cell(30, 4, db_formatar($oDadosLancamento->c69_valor,"f"), "0", 1, "R", $iPreenchimentoCelula);
	
	 if ($oDadosLancamento->c72_complem != ''){
     $oPdf->multicell(260,4,"Complemento :  ".$oDadosLancamento->c72_complem, 0, "L", $iPreenchimentoCelula);
	 }	
	  
	 $iTotal += $oDadosLancamento->c69_valor;
}

if ($iSubTotal > 0) {
	   
  $oPdf->SetFont('arial','B',9);    
  $iPosX = $oPdf->getx();
  $oPdf->setX(240);
  $oPdf->Cell(30,4,"SUBTOTAL     ".db_formatar($iSubTotal, "f"),"0",1,"R",$iPreenchimentoCelula);
  $oPdf->setX($iPosX);
  
  $dData     = $oDadosLancamento->c69_data; 
  $iSubTotal = 0;
  
}  
	
$oPdf->ln();

$oPdf->SetFont('arial','B',9);    
$iPosX = $oPdf->getx();

$oPdf->setX(240);
$oPdf->Cell(30,4,"TOTAL     ".db_formatar($iTotal, "f"),"0",1,"R",0);

$oPdf->setX($iPosX);
$oPdf->Cell(40,4,"Total de Registros         ".$iQtdLancamentos,"0",0,"L",0);

$oPdf->ln(14);

assinaturas($oPdf,$classinatura,'BG', true, false);

$iPaginaGeradaFinal = $oPdf->PageNo();

$sArquivo       = "tmp/livro_diario_de_".$iPaginaGeradaInicial."_ate_".$iPaginaGeradaFinal."_".date('dmYHis').".pdf";
$sNomeArquivos .= $sArquivo."#Download do livro diários da página ".$iPaginaGeradaInicial." ate ".$iPaginaGeradaFinal."|";
$oPdf->Output($sArquivo, false, true);

echo "<script>";
echo "  listagem = '$sNomeArquivos';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";
?>