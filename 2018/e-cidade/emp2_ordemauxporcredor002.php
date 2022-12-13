<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_utils.php");
require_once ("classes/db_empageordemcgm_classe.php");
require_once ("classes/db_empagenotasordem_classe.php");

$clEmpAgeOrdemCgm = new cl_empageordemcgm();
$clEmpAgeNotasOrdem    = new cl_empagenotasordem();

//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oGet = db_utils::postMemory($HTTP_GET_VARS);

$sWhereCredorCgm  = "e42_sequencial = $oGet->e42_sequencial ";
$sCamposCredorCgm = " z01_numcgm, z01_nome, z01_ender, z01_munic, z01_cgccpf,e94_historico,e42_dtpagamento ";
//echo $clEmpAgeOrdemCgm->sql_query(null,$sCamposCredorCgm,null,$sWhereCredorCgm);
$rsCredorCgm = $clEmpAgeOrdemCgm->sql_record($clEmpAgeOrdemCgm->sql_query(null,$sCamposCredorCgm,null,$sWhereCredorCgm)); 

if(pg_num_rows($rsCredorCgm) > 0){
	
	$oCredor = db_utils::fieldsMemory($rsCredorCgm,0);
	
}else{
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para o relatório.');
}

$sSqlCredorConta  = "select pc63_conta,      ";
$sSqlCredorConta .= "       pc63_conta_dig,  "; 
$sSqlCredorConta .= "       pc63_agencia,    "; 
$sSqlCredorConta .= "       pc63_agencia_dig,"; 
$sSqlCredorConta .= "       pc63_banco"; 
$sSqlCredorConta .= "  from pcfornecon       ";
$sSqlCredorConta .= "       inner join pcforneconpad on pc64_contabanco = pc63_contabanco ";
$sSqlCredorConta .= " where pc63_numcgm = ".$oCredor->z01_numcgm;
//echo $sSqlCredorConta;
$rsCredorConta = db_query($sSqlCredorConta);
if(pg_num_rows($rsCredorConta) > 0){
	$oCredorConta = db_utils::fieldsMemory($rsCredorConta,0);
}else{
	$oCredorConta = new stdClass();
	$oCredorConta->pc63_conta = "";
	$oCredorConta->pc63_conta_dig = "";
	$oCredorConta->pc63_agencia = "";
	$oCredorConta->pc63_agencia_dig = "";
	$oCredorConta->pc63_banco = "";	
}

$sCamposPagamentos  = "e50_codord,e50_data,e60_codemp,e60_anousu,e69_numero,o56_elemento,o56_descr,e53_valor,e81_valor,e60_vlremp";
$sCamposPagamentos .= ",fc_valorretencaomov(e81_codmov,false) as valorretencao ";
$sWherePagamentos   = "e42_sequencial = ".$oGet->e42_sequencial;
$sqlPagamentos      = $clEmpAgeNotasOrdem->sql_query_empenho(null,$sCamposPagamentos,null,$sWherePagamentos);

$rsPagamentos = $clEmpAgeNotasOrdem->sql_record($sqlPagamentos);
if($rsPagamentos !== false){
  $iNumPagamentos = pg_num_rows($rsPagamentos);
}else{
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para o relatório.');
}



$iNroViaOrd = 1;
$sqlNroViaOrd = "select e30_nroviaord from empparametro where e39_anousu = ".db_getsession("DB_anousu");
$rsqlNroViaOrd = db_query($sqlNroViaOrd);
if($rsqlNroViaOrd !== false && pg_num_rows($rsqlNroViaOrd)>0){
	$oEmpPar = db_utils::fieldsMemory($rsqlNroViaOrd,0);
	$iNroViaOrd = $oEmpPar->e30_nroviaord;
}

$alt="4";
$pdf = new PDF();
$pdf->SetAutoPageBreak(0); 
$pdf->Open(); 
$pdf->SetMargins(5,5,5);
$pdf->AliasNbPages(); 
for($iNroVias = 0; $iNroVias < $iNroViaOrd; $iNroVias++){	
	
	$oTotal = new stdClass();
	$oTotal->totTotalOP      = 0;
	$oTotal->totVlrMov       = 0;
	$oTotal->totRet          = 0;
	$oTotal->totVlrLiq       = 0;
	$oTotal->totGeralTotalOP = 0;
	$oTotal->totGeralVlrMov  = 0;
	$oTotal->totGeralRet     = 0;
	$oTotal->totGeralVlrLiq  = 0;
	$iPagina = 0;
	
	$head2 = "Ordem de pagamento auxiliar nº ".$oGet->e42_sequencial;
	$head3 = "Data de Pagamento:".db_formatar($oCredor->e42_dtpagamento,'d');
	$head4 = "Número de Movimentos:".$iNumPagamentos;
	
	//Adiciona a primeira pagina cria retangulo com bordas arrendodadas
	//Imprime dados do Credor so na primeira pagina do relatorio
	$pdf->addpage("P");
	$pdf->setfillcolor(235);
	$pdf->setfont('arial','b',9);
	$pdf->cell(180,4,"Dados do Credor",0,1,"L",0);
	$pdf->setfont('arial','',7);
	$iPosX = $pdf->GetX();
	$iPosY = $pdf->GetY();
	$pdf->RoundedRect($iPosX,$iPosY,202,20,2);
	$pdf->SetXY($iPosX,$iPosY+2);
	$pdf->setfont('arial','b',7);
	$pdf->cell(30,4,"Cgm:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(100,4,$oCredor->z01_numcgm,0,0,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(20,4,"CNPJ/CPF:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$sFormato = 'cpf';
	if($oCredor->z01_cgccpf > 11){
		$sFormato = 'cnpj';
	}
	$pdf->cell(40,4,db_formatar($oCredor->z01_cgccpf,$sFormato),0,1,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(30,4,"Nome/Razão Social:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(100,4,$oCredor->z01_nome,0,0,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(20,4,"Banco:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(40,4,$oCredorConta->pc63_banco,0,1,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(30,4,"Endereço:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(100,4,$oCredor->z01_ender,0,0,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(20,4,"Agência:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(40,4,$oCredorConta->pc63_agencia."-".$oCredorConta->pc63_agencia_dig,0,1,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(30,4,"Município:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(100,4,$oCredor->z01_munic,0,0,"L",0);
	$pdf->setfont('arial','b',7);
	$pdf->cell(20,4,"Conta Corrente:",0,0,"L",0);
	$pdf->setfont('arial','',7);
	$pdf->cell(40,4,$oCredorConta->pc63_conta."-".$oCredorConta->pc63_conta_dig,0,1,"L",0);
	//Fim da impressao dos dados do credor da primeira pagina
	$pdf->ln(4);
	$pdf->setfont('arial','b',9);
	$pdf->cell(180,$alt,"Detalhamento dos Pagamentos",0,1,"L",0);
	
	imprimeCabecalho($pdf,$alt);
	
	$iPosX1 = $pdf->GetX();
	$iPosY1 = $pdf->GetY();
	
	$pdf->setfont('arial','',7);
	
	$bgcolor = 0;
	
	for ($i = 0; $i < $iNumPagamentos; $i++) {
		
		$oPagamento = db_utils::fieldsMemory($rsPagamentos,$i);
		
		//Verifico se deu estouro de pagina para adicionar nova pagina
		//inserindo os totais e o novo cabeçalho
		if ($iPagina > 0 && ($pdf->gety() > $pdf->h -25) || $iPagina == 1) {
			
			$iPagina++;
			if (($iPagina-1) > 1 ) {

				
	      $iPosX2 = $pdf->GetX();
	      $iPosY2 = $pdf->GetY();
	      $pdf->SetXY($iPosX1,$iPosY1+20);
	      //$pdf->Line($iPosX1,$iPosY1,$iPosX2,$iPosY2);
	      $pdf->Line($iPosX1+10,$iPosY1,$iPosX2+10,$iPosY2);
	      $pdf->Line($iPosX1+30,$iPosY1,$iPosX2+30,$iPosY2);
	      $pdf->Line($iPosX1+45,$iPosY1,$iPosX2+45,$iPosY2);
	      $pdf->Line($iPosX1+60,$iPosY1,$iPosX2+60,$iPosY2);
	      $pdf->Line($iPosX1+80,$iPosY1,$iPosX2+80,$iPosY2);
	      //$pdf->Line($iPosX1+40,$iPosY1,$iPosX2+40,$iPosY2);
	      $pdf->Line($iPosX1+120,$iPosY1,$iPosX2+120,$iPosY2);
	      $pdf->Line($iPosX1+140,$iPosY1,$iPosX2+140,$iPosY2);
	      $pdf->Line($iPosX1+162,$iPosY1,$iPosX2+162,$iPosY2);
	      $pdf->Line($iPosX1+182,$iPosY1,$iPosX2+182,$iPosY2);
	      //$pdf->Line($iPosX1+202,$iPosY1,$iPosX2+202,$iPosY2);
				$pdf->SetXY($iPosX2,$iPosY2);	
				imprimeTotais($pdf,$alt,$oTotal);
				$pdf->cell(200,4,"página ".($iPagina-1)." continua na página ".($iPagina)."",0,1,"C",0);
			}
			$pdf->addpage("P");
			imprimeCabecalho($pdf,$alt);
			$iPosX1 = $pdf->GetX();
      $iPosY1 = $pdf->GetY();
			//$iPagina++;
		}
		
		//Impressão dos dados do corpo do relatorio
		
		$pdf->setfont('arial','',7);
		$pdf->cell(10 ,4, $oPagamento->e50_codord               , "",0, "R", $bgcolor);
		$pdf->cell(20 ,4, db_formatar($oPagamento->e50_data,'d'), "",0, "C", $bgcolor);
		$pdf->cell(15 ,4, $oPagamento->e60_codemp."/".$oPagamento->e60_anousu, "", 0, "R", $bgcolor);
		$pdf->cell(15 ,4, substr($oPagamento->e69_numero,0,7)   , "",0, "L", $bgcolor);
		$pdf->cell(20 ,4, $oPagamento->o56_elemento             , "",0, "C", $bgcolor);
		$pdf->cell(40 ,4, substr($oPagamento->o56_descr,0,23)   , "",0, "L", $bgcolor);
		$pdf->cell(20 ,4, db_formatar($oPagamento->e53_valor    , 'f'), "", 0, "R", $bgcolor);
		$pdf->cell(22 ,4, db_formatar($oPagamento->e81_valor    , 'f'), "", 0, "R", $bgcolor);
		$pdf->cell(20 ,4, db_formatar($oPagamento->valorretencao, 'f'), "", 0, "R", $bgcolor);
		$vlrLiquido = $oPagamento->e81_valor - $oPagamento->valorretencao;
		$pdf->cell(20 ,4, db_formatar($vlrLiquido, 'f'),"",1,"R",$bgcolor);
		//Fim da impressão dos dados do relatório	
		//Acumula o total parcial por pagina
		$oTotal->totTotalOP      += $oPagamento->e53_valor;
	  $oTotal->totVlrMov       += $oPagamento->e81_valor;
	  $oTotal->totRet          += $oPagamento->valorretencao;
	  $oTotal->totVlrLiq       += $vlrLiquido;	
		
		//Verifico se o indice é igual a 20 estourou a primeira pagina
		if ($i == 20 || ($i+1 == $iNumPagamentos && $iNumPagamentos < 20)) {
			
			$iPagina++;
			
			$pdf->SetXY($iPosX1,$iPosY1+84);
			$iPosX2 = $pdf->GetX();
			$iPosY2 = $pdf->GetY();
			//$pdf->Line($iPosX1,$iPosY1,$iPosX2,$iPosY2);
			$pdf->Line($iPosX1+10,$iPosY1,$iPosX2+10,$iPosY2);
			$pdf->Line($iPosX1+30,$iPosY1,$iPosX2+30,$iPosY2);
			$pdf->Line($iPosX1+45,$iPosY1,$iPosX2+45,$iPosY2);
			$pdf->Line($iPosX1+60,$iPosY1,$iPosX2+60,$iPosY2);
			$pdf->Line($iPosX1+80,$iPosY1,$iPosX2+80,$iPosY2);
			//$pdf->Line($iPosX1+40,$iPosY1,$iPosX2+40,$iPosY2);
			$pdf->Line($iPosX1+120,$iPosY1,$iPosX2+120,$iPosY2);
			$pdf->Line($iPosX1+140,$iPosY1,$iPosX2+140,$iPosY2);
			$pdf->Line($iPosX1+162,$iPosY1,$iPosX2+162,$iPosY2);
			$pdf->Line($iPosX1+182,$iPosY1,$iPosX2+182,$iPosY2);
			//$pdf->Line($iPosX1+202,$iPosY1,$iPosX2+202,$iPosY2);
			
			imprimeTotais($pdf,$alt,$oTotal);
			$pdf->setfont('arial','',8);
			if($iNumPagamentos > 20){
			  $pdf->cell(200,4,"página ".($iPagina)." continua na página ".($iPagina+1)."",0,1,"C",0);
			}
		//Imprime historico somente na primeira página
			$pdf->setfont('arial','b',8);
			$pdf->SetY(170);
			$pdf->cell(180,4,"Histórico",0,1,"L",0);
			$pdf->setfont('arial','',7);
			$iPosX = $pdf->GetX();
			$iPosY = $pdf->GetY();
			$pdf->RoundedRect($iPosX,$iPosY,202,30,2);
			$pdf->SetXY($iPosX,$iPosY+2);
					
			$pdf->MultiCell(200,4,substr($oCredor->e94_historico,0,840));
			//Imprime Assinaturas somente na primeira página
			$xcol = 5;
			$xlin = 15;
			
			$oAssinatura = new stdClass();
			$oAssinatura->emissao = $oCredor->e42_dtpagamento;
			
			$iInstituicao = db_getsession("DB_instit");
			$total_emp    = $oPagamento->e60_vlremp;
			
			$sqlparag  = "select munic,db02_texto ";
			$sqlparag .= "  from db_documento ";
			$sqlparag .= "       inner join db_docparag  on db03_docum   = db04_docum ";
			$sqlparag .= "       inner join db_tipodoc   on db08_codigo  = db03_tipodoc ";
			$sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
			$sqlparag .= "       inner join db_config    on db03_instit  = codigo ";
			$sqlparag .= " where db03_tipodoc = 1500 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
			$resparag = db_query($sqlparag);
			
			
			//$emissao = $oPagamento->e42_dtpagamento;
			
			if (@pg_num_rows($resparag) > 0) {
			 
			  $oTexto = db_utils::fieldsmemory($resparag,0);
			  $sTexto = str_replace('$this->municpref', '$municpref', $oTexto->db02_texto);
			  $sTexto = str_replace('$this->objpdf->', '$pdf->', $sTexto);
			  $sTexto = str_replace('$this->', '$oAssinatura->', $sTexto);
			  $municpref = $oTexto->munic;  
			  eval($sTexto);
			} else {
			      
			  $sqlparagpadrao  = "select munic,db61_texto ";
			  $sqlparagpadrao .= "  from db_documentopadrao ";
			  $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
			  $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
			  $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
			  $sqlparagpadrao .= "       inner join db_config          on db03_instit   = codigo ";
			  $sqlparagpadrao .= " where db60_tipodoc = 1500 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";
			    
			  $resparagpadrao = db_query($sqlparagpadrao);
			  if (@pg_num_rows($resparagpadrao) > 0) {
			    
			  	$oTexto = db_utils::fieldsmemory($resparagpadrao,0);
			    $sTexto = str_replace('$this->municpref', '$municpref', $oTexto->db61_texto);
			    $sTexto = str_replace('$this->objpdf->', '$pdf->', $sTexto);
			    $sTexto = str_replace('$this->', '$oAssinatura->', $sTexto);
			    $municpref = $oTexto->munic;
			    eval($sTexto);
			    
			  }
			}
			$iPosX3 = 188;
  		$iPosY3 = $pdf->h;
  		$pdf->Text($iPosX3, $iPosY3-7, ($iNroVias+1)."º via");
		}//Fechamento do if($i==20)
		
	}
	
	if($iNumPagamentos > 21){
		imprimeTotais($pdf,$alt,$oTotal);
		if($iPagina > 0){
		  imprimeTotalGeral($pdf,$alt,$oTotal);
	  }
	}
}
$pdf->Output();

function imprimeTotais($pdf,$alt,$oTotal){
	
  $pdf->setfont('arial','b',7);
  //$pdf->Ln(3);
  $pdf->cell(120,4,"Totais"          ,"TB" ,0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totTotalOP,'f'),"TBL",0,"R",1);
  $pdf->cell(22 ,4,db_formatar($oTotal->totVlrMov ,'f'),"TBL",0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totRet    ,'f'),"TBL",0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totVlrLiq ,'f'),"TBL",1,"R",1);

  //Acumula o parcial por página 
	$oTotal->totGeralTotalOP += $oTotal->totTotalOP;
	$oTotal->totGeralVlrMov  += $oTotal->totVlrMov ;
	$oTotal->totGeralRet     += $oTotal->totRet    ;
	$oTotal->totGeralVlrLiq  += $oTotal->totVlrLiq ;
	//Zera totais parciais por pagina
	$oTotal->totTotalOP      = 0;
  $oTotal->totVlrMov       = 0;
  $oTotal->totRet          = 0;
  $oTotal->totVlrLiq       = 0;
  
}

function imprimeTotalGeral($pdf,$alt,$oTotal){
  
  $pdf->setfont('arial','b',7);
  //$pdf->Ln(1);
  $pdf->cell(120,4,"Total Geral"          ,"TB" ,0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totGeralTotalOP,'f'),"TBL",0,"R",1);
  $pdf->cell(22 ,4,db_formatar($oTotal->totGeralVlrMov ,'f'),"TBL",0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totGeralRet    ,'f'),"TBL",0,"R",1);
  $pdf->cell(20 ,4,db_formatar($oTotal->totGeralVlrLiq ,'f'),"TBL",1,"R",1);
  
}

function imprimeCabecalho($pdf,$alt){
  
  $pdf->setfont('arial','b',7);
  $pdf->cell(120,$alt,"Dados sobre os pagamentos" ,"TB" ,0,"C",1);
  $pdf->cell(82 ,$alt,"Valores dos Pagamentos"    ,"TBL" ,1,"C",1);
  
  
  $pdf->cell(10 ,$alt,"Ordem"           ,"TB" ,0,"C",1);
  $pdf->cell(20 ,$alt,"Emissão da OP"   ,"TBL",0,"C",1);
  $pdf->cell(15 ,$alt,"Empenho"         ,"TBL",0,"C",1);
  $pdf->cell(15 ,$alt,"Nota Fiscal"     ,"TBL",0,"C",1);
  $pdf->cell(20 ,$alt,"Elemento"        ,"TBL",0,"C",1);
  $pdf->cell(40 ,$alt,"Descrição"       ,"TBL",0,"C",1);
  $pdf->cell(20 ,$alt,"Total da OP"     ,"TBL",0,"C",1);
  $pdf->cell(22 ,$alt,"Vlr do Movimento","TBL",0,"C",1);
  $pdf->cell(20 ,$alt,"Retenções"       ,"TBL",0,"C",1);
  $pdf->cell(20 ,$alt,"Vlr. Líquido"    ,"TBL",1,"C",1);
}
?>