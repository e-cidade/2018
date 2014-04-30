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
include("libs/db_liborcamento.php");
include("libs/db_utils.php");
include("libs/db_libcontabilidade.php");
include("classes/db_empresto_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempresto = new cl_empresto;

$aux = explode("-",$_GET["recid"]);

if ($_GET["recid"] == "") {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum recurso selecionado!');
}

$pdf_cabecalho = true;

if ($formato == 'p' && $orientacao == 'r') {
	
	$pdf = new PDF("P", "mm", "A4"); 
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
  
}else if ($formato == 'p' && $orientacao == 'p') {
	
	$pdf = new PDF("L", "mm", "A4"); 
  $pdf->Open(); // abre o relatorio
  $pdf->AliasNbPages(); // gera alias para as paginas
} else {
	
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
<tr>
<td width="100%" align="center">&nbsp;</td>
</tr>
<tr>
<td width="100%"  align="center">
</td>
</tr>
</table>
<form name="form1">
</form>
</body>
</html> 
<?

  $nomedoarquivo  = "tmp/saldocontabil.csv";
  $arqnomes       = $nomedoarquivo."# Download do Arquivo - ".$nomedoarquivo."|";
  $clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);

  fputs($clabre_arquivo->arquivo,"CODIGO DO RECURSO;");
  fputs($clabre_arquivo->arquivo,"DESCRICAO DO RECURSO;");
  fputs($clabre_arquivo->arquivo,"SALDO BANCOS CFE. BOLETIM CAIXA;");
  fputs($clabre_arquivo->arquivo,"RESTOS A PAGAR;");
  fputs($clabre_arquivo->arquivo,"EMPENHOS LIQUIDADOS A PAGAR;");
  fputs($clabre_arquivo->arquivo,"SUB-TOTAL;");
  fputs($clabre_arquivo->arquivo,"EXTRAORÇAMENTÁRIOS A PAGAR;");
  fputs($clabre_arquivo->arquivo,"SALDO COMPROMETIDO;");
  fputs($clabre_arquivo->arquivo,"SALDO LICITAÇÃO COMPROMETIDO;");
  fputs($clabre_arquivo->arquivo,"SUB-TOTAL;");
  fputs($clabre_arquivo->arquivo,"EMPENHOS NÃO LIQ. A PAGAR;");
  fputs($clabre_arquivo->arquivo,"SALDO CONTÁBIL;");
  fputs($clabre_arquivo->arquivo,"RECEITA PREVISTA PARA O ANO;");
  fputs($clabre_arquivo->arquivo,"ARRECADADO ATÉ A DATA;");
  fputs($clabre_arquivo->arquivo,"POR ARRECADAR;");
  fputs($clabre_arquivo->arquivo,"\n");
  
}

for ($cod = 0;$cod < count($aux);$cod++){
	
  $dataini = db_getsession("DB_anousu")."-01-01";
  $anousu  = db_getsession("DB_anousu");
  $datafin = $data;
  //$recurso = 40;
  $sql = 'select * from orctiporec where o15_codigo = '.$aux[$cod];
  $result = pg_query($sql) or die($sql);

  db_fieldsmemory($result,0);

  $head2 = 'SALDO CONTÁBIL';
  $head4 = 'DATA : '.db_formatar($datafin,'d');
  if ($formato == 'p' && $orientacao == 'r') {
  	$head6 = 'RECURSO : '.$aux[$cod].' - '.$o15_descr;
  } else if ($formato == 'p' && $orientacao == 'p') {
  	$head6 = 'RECURSO : Selecionados';
  }
  
  $head8 = 'ANO : '.$anousu;

  ////////// saldo dos bancos por recurso
  $sql = "select sum(atual) as saldo
	          from (select substr(fc_saltessaldo,41,13)::numeric as atual
	                  from (select fc_saltessaldo(c61_reduz,'".$datafin."','".$datafin."',null," . db_getsession("DB_instit") . ") 
	                          from saltes 
	                         inner join conplanoexe   on c62_anousu = ".db_getsession("DB_anousu")." and c62_reduz = k13_conta
	                         inner join conplanoreduz on c61_anousu = ".db_getsession("DB_anousu")." 
	                                                 and c61_reduz  = c62_reduz  
	                                                 and c61_instit = ".db_getsession("DB_instit")."
	                         inner join conplano      on c61_codcon = c60_codcon and c61_anousu=c60_anousu
	                         inner join orctiporec    on o15_codigo = c61_codigo
	                                                 and c61_codigo = ".$aux[$cod]."
	                                                 and c60_codsis in(5,6)
	                                                 and c60_anousu = ".db_getsession("DB_anousu")."
	                                                 and c61_instit = ".db_getsession('DB_instit')."
	                       ) as x
	               ) as xx ";
	  
  //die($sql);
  
  $result = pg_query($sql) or die($sql);
  if(pg_numrows($result) > 0){
  	
    db_fieldsmemory($result,0);
    $saldo_bancos = $saldo;
    
  }else{
    $saldo_bancos = 0;
  }

  db_fieldsmemory($result,0);

  /*
   * Restos a Pagar 
   */
  
  $sele_work         = " e60_instit = ".db_getsession("DB_instit");
  $sele_work1        = " and e91_recurso  = ".$aux[$cod];
  $sql_where_externo = " where $sele_work";
  $sql_order         = "";
  $dtini             = db_getsession("DB_anousu")."-01-01";
  $dtfim             = $data;
  $sql               = $clempresto->sql_rp_novo($anousu,$sele_work,$dtini,$dtfim,$sele_work1,$sql_where_externo,$sql_order);
  $result            = pg_query($sql) or die($sql);
  
  if (pg_numrows($result) > 0) {
  	
    $taliq                   = 0;
    $tliq                    = 0;
    $tvlranu                 = 0;
    $tvlrpag                 = 0;
    $total_aliquidar_finais  = 0;
    $total_liquidados_finais = 0;
    $total_geral_finais      = 0;
    
    for ($xx = 0; $xx < pg_numrows($result); $xx++) {
    	
      db_fieldsmemory($result,$xx);
      
      $liquidado_anterior      = ($e91_vlremp - $e91_vlranu - $e91_vlrliq) + ($e91_vlrliq - $e91_vlrpag);
      $apagargeral             = ($liquidado_anterior -$vlranu - $vlrpag - $vlrpagnproc);
      $aliquidargeral          = $e91_vlremp - (($e91_vlranu + $vlranu) + ($vlrliq + $e91_vlrliq - $vlranuliq));
      $liquidados              = ($apagargeral-$aliquidargeral);
      
      $total_aliquidar_finais  = $total_aliquidar_finais + $aliquidargeral;
      $total_liquidados_finais = $total_liquidados_finais + abs($liquidados);
      $total_geral_finais      = ($total_geral_finais + $apagargeral);

      $taliq                  += $e91_vlremp - $e91_vlranu - $e91_vlrliq;
      $tliq                   += $e91_vlrliq - $e91_vlrpag;
      $tvlranu                += $vlranu;
      $tvlrpag                += $vlrpag;
    }
    
//    $restos = ($taliq + $tliq) - $tvlranu - $tvlrpag;
    $restos = ($total_geral_finais);
  }else{
  	
    $emp    = 0;
    $anu    = 0;
    $pag    = 0;
    $restos = 0;
  }
  /**
   * Valores de Empenhos (despesa)
   */

  $sele_work = " o58_instit = ".db_getsession("DB_instit")." and o58_codigo = ".$aux[$cod];

  $result = db_dotacaosaldo(8,3,4,true,$sele_work,$anousu,$dtini,$dtfim);

  if (pg_numrows($result) > 0) {
  	
    db_fieldsmemory($result,0);
    $naoliquidado            = $atual_a_pagar;
    $atual_a_pagar_liquidado = $liquidado_acumulado - $pago_acumulado;
    $saldo_dotacao_atual     = $atual_menos_reservado;
    
  } else {
  	
    $naoliquidado            = 0;
    $atual_a_pagar_liquidado = 0;
    $saldo_dotacao_atual     = 0;
    
  }

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  // EXTRA ORÇAMENTARIO A PAGAR - BAL. VERIFICACAO
//  $sele_work  = " c61_instit    = ".db_getsession("DB_instit");
//  if ($aux[$cod] == 1) {
//     $sele_work .= " and (c61_codigo = {$aux[$cod]} or c61_codigo = 8001)";
//  }else{  
//    $sele_work .= "and c61_codigo = {$aux[$cod]}";
//  }  
//  $result    = db_planocontassaldo_matriz($anousu,$dtini,$dtfim,false,$sele_work,"","true","false",""); 
//  @pg_exec("drop table work_pl"); 
//
//  //saldo extraordinario a pagar.;
//  $nSaldoExtraPagar = 0;
//  if (pg_num_rows($result) > 0){
//    for($xx = 0; $xx < pg_numrows($result); $xx++) {
//      
//      db_fieldsmemory($result,$xx);
//      if (substr($estrutural,0,3) == "211" && $c61_codcon != 0) {
//        if ($sinal_final == "D"){
//          $saldo_final *= -1;
//        } 
//        $nSaldoExtraPagar += $saldo_final;
//      }  
//    }
  //}

$sSqlExtra        = " select sum(valor_extra) as valor_extra_final
			                  from (select (select coalesce(rnsaldofinal,0) from fc_saltessaldoextra(k02_codigo,'{$datafin}'::date, {$aux[$cod]})) as valor_extra
			                          from tabplan
			                         inner join conplanoreduz      on c61_reduz         = k02_reduz
			                                                      and c61_anousu        = k02_anousu
			                         inner join conplano           on c61_codcon        = c60_codcon
			                                                      and c61_anousu        = c60_anousu
			                          left join conplanoconta      on c63_codcon        = conplano.c60_codcon 
			                                                      and c63_anousu        = c60_anousu
			                          left join db_bancos          on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)
			                         where c61_instit  = ".db_getsession("DB_instit")." 
			                           and k02_anousu  = ".db_getsession("DB_anousu")."
			                       ) as x";
                         
$rsSaldoExtra     = db_query($sSqlExtra);
$nSaldoExtraPagar = db_utils::fieldsMemory($rsSaldoExtra,0)->valor_extra_final;
  /**
   * Saldo Comprometido
   */
  
$sql              = "select sum(case when o83_codres is not null then o80_valor else 0 end ) as reservadoaut,
                            sum(case when o82_codres is not null then o80_valor else 0 end)  as reservadosol 
                       from (select o80_valor,o83_codres,o82_codres
                               from orcreserva 
                               left join orcreservaaut on orcreserva.o80_codres = orcreservaaut.o83_codres
                               left join orcreservasol on orcreserva.o80_codres = orcreservasol.o82_codres
                              inner join orcdotacao    on orcdotacao.o58_coddot = o80_coddot 
                                                      and orcdotacao.o58_anousu = o80_anousu
                              where orcdotacao.o58_instit = ".db_getsession("DB_instit")." 
                                and orcdotacao.o58_anousu = ".db_getsession("DB_anousu")." 
                                and orcdotacao.o58_codigo = ".$aux[$cod]." 
                            ) as x";

  $result         = pg_query($sql);
  $reservado      = 0;
  if (pg_numrows($result) > 0) {
  	
    db_fieldsmemory($result,0);
    $reservado = $reservadoaut + $reservadosol;
    
  }
  
	$sql    = "select sum(case when o83_codres is not null then o80_valor else 0 end) as reservadoaut_periodo,
		                sum(case when o82_codres is not null then o80_valor else 0 end) as reservadosol_periodo 
		           from (select o80_valor,o83_codres,o82_codres
		                   from orcreserva 
		                   left join orcreservaaut on orcreserva.o80_codres = orcreservaaut.o83_codres
		                   left join orcreservasol on orcreserva.o80_codres = orcreservasol.o82_codres
		                  inner join orcdotacao    on orcdotacao.o58_coddot = o80_coddot 
		                                          and orcdotacao.o58_anousu = o80_anousu
		                  where orcdotacao.o58_instit = ".db_getsession("DB_instit")." 
		                    and orcdotacao.o58_anousu = ".db_getsession("DB_anousu")." 
		                    and orcdotacao.o58_codigo = ".$aux[$cod]."
		                    and o80_dtlanc <= '$datafin'
		                ) as x";
		                
  $result = pg_query($sql);
  $reservado_periodo = 0;
  if (pg_numrows($result) > 0){
  	
    db_fieldsmemory($result,0);
    $reservado_periodo = $reservadoaut_periodo + $reservadosol_periodo;
  }  


  $reservado_lic = 0;
  /**
   * Valores da Receita
   */
  $sele_work                 = " o70_codigo = ".$aux[$cod];
  $nSaldoInicial             = 0;
  $nSaldoArrecadadoAcumulado = 0;
  $nSaldoAArrecadar          = 0;
  
  $result = db_receitasaldo(11,3,3,true,$sele_work,$anousu,$dataini,$datafin);
  //db_criatabela($result);
  if (pg_num_rows($result) > 0){
    
    for ($i = 0; $i < pg_num_rows($result); $i++) {
    	
      db_fieldsmemory($result,$i);
      if ($o70_codrec == 0) {
        
        $nSaldoInicial             += $saldo_inicial;
        $nSaldoArrecadadoAcumulado += $saldo_arrecadado_acumulado;
        $nSaldoAArrecadar          += $saldo_a_arrecadar;           
        
      }  
    }
  }

  //Valor das reservas do exercício para a instituição
  $VlrReserva = pg_result(pg_query("select round(sum(o80_valor),2) 
                                      from orcreserva 
	     						                   inner join orcdotacao on o58_anousu = o80_anousu 
                                                          and o58_coddot = o80_coddot 
                                     where orcdotacao.o58_codigo = ".$aux[$cod]."
									                     and o80_anousu = ".db_getsession("DB_anousu")." 
                                       and o58_instit in (".db_getsession("DB_instit").")"),0,0); 

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$saldo_dotacao_atual = $saldo_dotacao_atual + ($VlrReserva - $reservado);
$subtotal1           = $saldo_bancos - $restos - $atual_a_pagar_liquidado;
$saldo_contabil      = $subtotal1 - $nSaldoExtraPagar - $naoliquidado - $reservado;

if ($formato == 'p' && $orientacao == 'r') {
	
  $pdf->SetFont('Courier','',7);
  $pdf->SetTextColor(0,0,0);
  $pdf->setfillcolor(235);
  $preenc = 0;
  $linha = 1;
  $bordat = 0;
  $pdf->AddPage();
  $pdf->SetFont('Arial','',9);
  $pdf->ln(5);

  $pdf->Cell(130,5,"SALDO BANCOS CFE. BOLETIM CAIXA :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($saldo_bancos,'f'),$bordat,1,"R",$preenc);
  $pdf->Cell(130,5,"RESTOS A PAGAR :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($restos,'f'),$bordat,1,"R",$preenc);
  $pdf->Cell(130,5,"EMPENHOS LIQUIDADOS A PAGAR :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($atual_a_pagar_liquidado,'f'),$bordat,1,"R",$preenc);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(130,5,"SUB-TOTAL :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($subtotal1,'f'),'T',1,"R",$preenc);
  $pdf->SetFont('Arial','',9);
  $pdf->ln(3);
  $pdf->Cell(130,5,"EXTRAORÇAMENTÁRIOS A PAGAR :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($nSaldoExtraPagar,'f'),$bordat,1,"R",$preenc);
  $pdf->Cell(130,5,"SALDO COMPROMETIDO :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($reservado_periodo,'f'),$bordat,1,"R",$preenc);
  $pdf->SetFont('Arial','',9);
  $pdf->ln(3);
  $pdf->Cell(130,5,"EMPENHOS NÃO LIQ. A PAGAR :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($naoliquidado,'f'),$bordat,1,"R",$preenc);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(130,5,"SALDO CONTÁBIL :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($saldo_contabil,'f'),"T",1,"R",$preenc);
  $pdf->SetFont('Arial','',9);
  $pdf->ln(6);
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(130,5,"CONCLUSÃO :",$bordat,0,"L",$preenc,0);
  $pdf->SetFont('Arial','',9);
  $pdf->ln(6);
  $pdf->Cell(130,5,"RECEITA PREVISTA PARA O ANO :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($nSaldoInicial,'f'),$bordat,1,"R",$preenc);
  $pdf->Cell(130,5,"ARRECADADO ATÉ A DATA :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($nSaldoArrecadadoAcumulado,'f'),$bordat,1,"R",$preenc);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(130,5,"POR ARRECADAR :",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($nSaldoAArrecadar,'f'),"T",1,"R",$preenc);
  $pdf->SetFont('Arial','',9);
  $pdf->Cell(130,5,"SALDO ORÇAMENTÁRIO:",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar($saldo_dotacao_atual,'f'),$bordat,1,"R",$preenc);
  $pdf->SetFont('Arial','B',9);
  $pdf->Cell(130,5,"RESULTADO:",$bordat,0,"L",$preenc,0,'.');
  $pdf->Cell(25,5,db_formatar(($saldo_contabil+$nSaldoAArrecadar)- $saldo_dotacao_atual,'f'),$bordat,1,"R",$preenc);
  $pdf->ln(3);
  $pdf->SetFont('Arial','',9);
  
} else if ($formato == 'p' && $orientacao == 'p') {
	
	$pdf->SetAutoPageBreak(false);
	//$pdf->h;
	if ($pdf->GetY() > $pdf->h - 25 || $pdf_cabecalho == true) {
		
		$pdf_cabecalho = false;  
		$pdf->SetFont('Courier','',7);
	  $pdf->SetTextColor(0,0,0);
	  $pdf->setfillcolor(235);
	  $preenc = 0;
	  $linha = 1;
	  $bordat = 0;
	  $pdf->AddPage();
	  $pdf->SetFont('Arial','',9);
	  $pdf->ln(5);
		//cabeçalho
		$pdf->Cell(30,5,"",'TR',0,"C",0);
		$pdf->Cell(152,5,"COMPOSIÇÃO DO SALDO CONTÁBIL",'TR',0,"C",0);
		$pdf->Cell(57,5,"RECEITA",'TR',0,"C",0);
		$pdf->Cell(38,5,"RESULTADO",'T',1,"C",0);		
		$pdf->SetFont('Arial','',7);
		//Cabeçalho linha 2
		$pdf->Cell(30,5,"",'R',0,"C",0);
		$pdf->Cell(19,5,"Saldo",'TR',0,"C",0);
		$pdf->Cell(19,5,"Saldo",'TR',0,"C",0);
		$pdf->Cell(19,5,"Empenhos",'TR',0,"C",0);
		$pdf->Cell(19,5,"",'TR',0,"C",0);
		$pdf->Cell(19,5,"Extraorça-",'TR',0,"C",0);
		$pdf->Cell(19,5,"Saldo",'TR',0,"C",0);
		$pdf->Cell(19,5,"Empenhos não",'TR',0,"C",0);
		$pdf->Cell(19,5,"Saldo",'TR',0,"C",0);
		$pdf->Cell(19,5,"Previsão",'TR',0,"C",0);
		$pdf->Cell(19,5,"Arrecadado",'TR',0,"C",0);
		$pdf->Cell(19,5,"",'TR',0,"C",0);
		$pdf->Cell(19,5,"Saldo",'TR',0,"C",0);
		$pdf->Cell(19,5,"",'T',1,"C",0);
		//Cabeçalho linha 3
		$pdf->Cell(30,5,"RECURSOS",'R',0,"C",0);
		$pdf->Cell(19,5,"bancário",'R',0,"C",0);
		$pdf->Cell(19,5,"restos",'R',0,"C",0);
		$pdf->Cell(19,5,"liquidados",'R',0,"C",0);
		$pdf->Cell(19,5,"subtotal",'R',0,"C",0);
		$pdf->Cell(19,5,"mentários",'R',0,"C",0);
		$pdf->Cell(19,5,"comprometido",'R',0,"C",0);
		$pdf->Cell(19,5,"liquidados a",'R',0,"C",0);
		$pdf->Cell(19,5,"contábil",'R',0,"C",0);
		$pdf->Cell(19,5,"para o",'R',0,"C",0);
		$pdf->Cell(19,5,"até a data",'R',0,"C",0);
		$pdf->Cell(19,5,"A Arrecadar",'R',0,"C",0);
		$pdf->Cell(19,5,"Orçamentário",'R',0,"C",0);
		$pdf->Cell(19,5,"Resultado",'',1,"C",0);
		//Cabeçalho linha 4
		$pdf->Cell(30,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"a pagar",'BR',0,"C",0);
		$pdf->Cell(19,5,"a pagar",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"a pagar",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"pagar",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"ano",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BR',0,"C",0);
		$pdf->Cell(19,5,"",'BLR',0,"C",0);
		$pdf->Cell(19,5,"",'BLR',0,"C",0);
		$pdf->Cell(19,5,"",'BL',1,"C",0);
	}
		//linha 5 dados
	$Y   = $pdf->GetY();
	$X   = $pdf->GetX();
	$X1  = $X + 30;
	$pdf->MultiCell(30,5,$o15_descr,'TR',"L");
	$Y1  = $pdf->GetY();
	$pdf->SetXY($X1,$Y);
	$pdf->Cell(19,5,db_formatar($saldo_bancos,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($restos,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($atual_a_pagar_liquidado,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($subtotal1,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($nSaldoExtraPagar,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($reservado_periodo,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($naoliquidado,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($saldo_contabil,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($nSaldoInicial,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($nSaldoArrecadadoAcumulado,'f'),'TR',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
	$pdf->Cell(19,5,db_formatar($nSaldoAArrecadar,'f'),'T',0,"R",0);
	$X1 +=19;
	$pdf->Line($X1,$Y,$X1,$Y1);
  $pdf->Cell(19,5,db_formatar($saldo_dotacao_atual,'f'),'T',0,"R",0);
  $X1 +=19;
  $pdf->Line($X1,$Y,$X1,$Y1);
  $pdf->Cell(19,5,db_formatar(($saldo_contabil+$nSaldoAArrecadar)- $saldo_dotacao_atual,'f'),'T',1,"R",0);
  $pdf->SetY($Y1);
	$pdf->Line($X,$Y1,287,$Y1);
	
	//$pdf->Ln();
} else {

       fputs($clabre_arquivo->arquivo,$aux[$cod] . ";");
       fputs($clabre_arquivo->arquivo,$o15_descr . ";");

       fputs($clabre_arquivo->arquivo,db_formatar($saldo_bancos,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($restos,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($atual_a_pagar_liquidado,'f') . ";");

       fputs($clabre_arquivo->arquivo,db_formatar($subtotal1,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar(0,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($reservado_periodo,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar(0,'f') . ";");

       fputs($clabre_arquivo->arquivo,db_formatar($subtotal2,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($naoliquidado,'f') . ";");

       fputs($clabre_arquivo->arquivo,db_formatar($saldo_contabil,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($saldo_inicial,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($saldo_arrecadado_acumulado,'f') . ";");
       fputs($clabre_arquivo->arquivo,db_formatar($saldo_a_arrecadar,'f') . ";");
       fputs($clabre_arquivo->arquivo,"\n");

  }

  pg_query("drop table work_receita");

}

if ($formato == 'p') {
  $pdf->Output();
} else {
	
  fclose($clabre_arquivo->arquivo);
  echo "<script>";
  echo "  listagem = '$arqnomes';";
  echo "  parent.js_montarlista(listagem,'form1');";
  echo "  parent.db_iframe_geratxt.hide();";
  echo "</script>";
}
?>