<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

if (!isset($arqinclude)){
  // se este arquivo no esta incluido por outro

  include(modification("fpdf151/pdf.php"));
  require_once(modification("libs/db_utils.php"));
  include(modification("fpdf151/assinatura.php"));
  include(modification("libs/db_sql.php"));
  include(modification("libs/db_liborcamento.php"));
  include(modification("libs/db_libcontabilidade.php"));
  include(modification("libs/db_libtxt.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("model/linhaRelatorioContabil.model.php"));
  include(modification("model/relatorioContabil.model.php"));
  include(modification("classes/db_periodo_classe.php"));
  include(modification("classes/db_db_config_classe.php"));
  include(modification("classes/db_conrelinfo_classe.php"));
  include(modification("classes/db_conrelvalor_classe.php"));
  include(modification("classes/db_orcparamrel_classe.php"));
  include(modification("classes/db_empresto_classe.php"));

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

  $classinatura         = new cl_assinatura;
  $orcparamrel          = new cl_orcparamrel;
  $clconrelinfo         = new cl_conrelinfo;
  $clconrelvalor        = new cl_conrelvalor;
  $clempresto           = new cl_empresto;
  $iCodigoPeriodo  = $periodo;


  // no dbforms/db_funcoes.php
  // data final do periodo
}
  $cldb_config          = new cl_db_config;
  $anousu     = db_getsession("DB_anousu");
  $instit     = db_getsession("DB_instit");
  $anousu_ant = db_getsession("DB_anousu") -1;
  $oDaoPeriodo          = new cl_periodo;
  $sSqlPeriodo   = $oDaoPeriodo->sql_query($periodo);
  $sSiglaPeriodo = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
  $periodo_selecionado = $sSiglaPeriodo;
  $oRelatorioContabil   = new relatorioContabil(87, false);
  $aLinhas              = $oRelatorioContabil->getLinhasRelatorio(false);
  $dt     = data_periodo($anousu,$sSiglaPeriodo);
  
  $dt_ini = $dt[0];
  // data inicial do periodo
  $dt_fin = $dt[1];
  $periodo = $dt["periodo"];
  $texto   = $dt["texto"];
  $dt_ini_ant = $anousu_ant."-01-01";
  $dt_fin_ant = $anousu_ant."-12-31";
  $aLinhasRelatorio     = array();
  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "TOTAL DE ATIVOS";
  $oLinha->valores      = array();
  $oLinha->pdfBorda     = "T";
  $aLinhasRelatorio[]   = $oLinha;
  $aLinhasRelatorio[]   = $aLinhas[1];
  $aLinhasRelatorio[]   = $aLinhas[2];
  $aLinhasRelatorio[]   = $aLinhas[3];

  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "TOTAL DE PASSIVOS(I)";
  $oLinha->valores      = array();
  $oLinha->pdfBorda     = "T";
  $aLinhasRelatorio[]   = $oLinha;
  $aLinhasRelatorio[]   = $aLinhas[4];
  $aLinhasRelatorio[]   = $aLinhas[5];
  $aLinhasRelatorio[]   = $aLinhas[6];
  $aLinhasRelatorio[]   = $aLinhas[7];

  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "SALDO LÍQUIDO DE PASSIVOS DE PPP(III) = (I-II)";
  $oLinha->valores      = array();
  $aLinhasRelatorio[]   = $oLinha;

  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "PASSIVOS CONTINGENTES";
  $oLinha->total1       = "";
  $oLinha->total2       = "";
  $oLinha->total3       = "";
  $oLinha->total4       = "";
  $oLinha->pdfBorda     = "T";

  $aLinhasRelatorio[]   = $oLinha;
  $aLinhasRelatorio[]   = $aLinhas[8];
  $aLinhasRelatorio[]   = $aLinhas[9];
  $aLinhasRelatorio[]   = $aLinhas[10];
  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "ATIVOS CONTINGENTES";
  $oLinha->total1       = "";
  $oLinha->total2       = "";
  $oLinha->total3       = "";
  $oLinha->total4       = "";
  $oLinha->pdfBorda     = "T";
  $aLinhasRelatorio[]   = $oLinha;
  $aLinhasRelatorio[]   = $aLinhas[11];
  $aLinhasRelatorio[]   = $aLinhas[12];
  $aLinhasRelatorio[]   = $aLinhas[13];
  $aLinhasRelatorio[]   = $aLinhas[14];

  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "TOTAL DE DESPESAS";
  $aLinhasRelatorio[]   = $oLinha;
  $aLinhasRelatorio[]   = $aLinhas[15];

  $oLinha               = new stdClass();
  $oLinha->o69_labelrel = "TOTAL DAS DESPESAS / RCL(%) (VI) = (IV)/(V)";
  $aLinhasRelatorio[]   = $oLinha;

  for ($iInd = 1; $iInd < 4; $iInd++) {

    $oLinha = $aLinhasRelatorio[$iInd];
  	$oLinha->valores = array();
  	$oLinhaSoma = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
    foreach ($aLinhaSoma as $oColuna) {

    	foreach ($oColuna->colunas as $key => $oValor) {
        isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                        $oLinha->valores[$key] = $oValor->o117_valor;

    	}
    	isset($oLinha->valores["total"]) ? $oLinha->valores["total"] += $oLinha->valores[1] + $oLinha->valores[3] :
    	                                   $oLinha->valores["total"] = $oLinha->valores[1] + $oLinha->valores[3];
    }
    $aLinhasRelatorio[$iInd] = $oLinha;
    foreach ($oLinha->valores as $key=>$value){

      isset($aLinhasRelatorio[0]->valores[$key]) ? $aLinhasRelatorio[0]->valores[$key] += $value :
                                                   $aLinhasRelatorio[0]->valores[$key] = $value;
    }
  }

  //1º Total  linha c = a+b
  $aLinhasRelatorio[0]->valores["total"] = $aLinhasRelatorio[0]->valores[1] + $aLinhasRelatorio[0]->valores[3];

  for ($iInd = 5; $iInd < 8; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
   foreach ($aLinhaSoma as $oColuna) {

      foreach ($oColuna->colunas as $key => $oValor) {

        isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                       $oLinha->valores[$key] = $oValor->o117_valor;

      }
      isset($oLinha->valores["total"]) ? $oLinha->valores["total"] += $oLinha->valores[1] + $oLinha->valores[3] :
                                         $oLinha->valores["total"] = $oLinha->valores[1] + $oLinha->valores[3];
    }
    $aLinhasRelatorio[$iInd] = $oLinha;
    foreach ($oLinha->valores as $key => $value) {

      isset($aLinhasRelatorio[4]->valores[$key]) ? $aLinhasRelatorio[4]->valores[$key] += $value :
                                                   $aLinhasRelatorio[4]->valores[$key] = $value;
    }
  }
  //2º Total  linha c = a+b
  $aLinhasRelatorio[4]->valores["total"] = $aLinhasRelatorio[4]->valores[1] + $aLinhasRelatorio[4]->valores[3];

  for ($iInd = 8; $iInd < 9; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
   foreach ($aLinhaSoma as $oColuna) {

      foreach ($oColuna->colunas as $key => $oValor) {

        isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                        $oLinha->valores[$key] = $oValor->o117_valor;

      }

      isset($oLinha->valores["total"]) ? $oLinha->valores["total"] += $oLinha->valores[1] + $oLinha->valores[3] :
                                         $oLinha->valores["total"]  = $oLinha->valores[1] + $oLinha->valores[3];
    }
    $aLinhasRelatorio[$iInd] = $oLinha;
  }

  //SALDO LÍQUIDO DE PASSIVOS DE PPP(III) = (I-II)
  $aLinhasRelatorio[9]->valores[1]       = $aLinhasRelatorio[4]->valores[1] - $aLinhasRelatorio[8]->valores[1];
  $aLinhasRelatorio[9]->valores[2]       = $aLinhasRelatorio[4]->valores[2] - $aLinhasRelatorio[8]->valores[2];
  $aLinhasRelatorio[9]->valores[3]       = $aLinhasRelatorio[4]->valores[3] - $aLinhasRelatorio[8]->valores[3];
  $aLinhasRelatorio[9]->valores["total"] = $aLinhasRelatorio[4]->valores["total"] - $aLinhasRelatorio[8]->valores["total"];

  for ($iInd = 11; $iInd < 14; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
    foreach ($aLinhaSoma as $oColuna) {

      foreach ($oColuna->colunas as $key => $oValor){

        isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                        $oLinha->valores[$key] = $oValor->o117_valor;

      }
      isset($oLinha->valores["total"]) ? $oLinha->valores["total"] += $oLinha->valores[1] + $oLinha->valores[3] :
                                         $oLinha->valores["total"]  = $oLinha->valores[1] + $oLinha->valores[3];
    }
    $aLinhasRelatorio[$iInd] = $oLinha;
    foreach ($oLinha->valores as $key => $value) {

      isset($aLinhasRelatorio[10]->valores[$key]) ? $aLinhasRelatorio[10]->valores[$key] += $value :
                                                    $aLinhasRelatorio[10]->valores[$key]  = $value;
    }
  }
  //3º Total  linha c = a+b PASSIVOS CONTIGENTES
  $aLinhasRelatorio[10]->valores["total"] = $aLinhasRelatorio[10]->valores[1] + $aLinhasRelatorio[10]->valores[3];

  for ($iInd = 15; $iInd < 17; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
   foreach ($aLinhaSoma as $oColuna) {

     foreach ($oColuna->colunas as $key => $oValor) {

       isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                       $oLinha->valores[$key]  = $oValor->o117_valor;

      }
      isset($oLinha->valores["total"]) ? $oLinha->valores["total"] += $oLinha->valores[1] + $oLinha->valores[3] :
                                         $oLinha->valores["total"]  = $oLinha->valores[1] + $oLinha->valores[3];
    }

    $aLinhasRelatorio[$iInd] = $oLinha;

    foreach ($oLinha->valores as $key => $value) {
      isset($aLinhasRelatorio[14]->valores[$key]) ? $aLinhasRelatorio[14]->valores[$key] += $value :
                                                    $aLinhasRelatorio[14]->valores[$key]  = $value;
    }
  }

  //Processa Linhas DESPESAS DE PPP
  for ($iInd = 17; $iInd < 19; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);
   foreach ($aLinhaSoma as $oColuna) {

      foreach ($oColuna->colunas as $key => $oValor) {

        isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                        $oLinha->valores[$key]  = $oValor->o117_valor;

      }
    }
    $aLinhasRelatorio[$iInd] = $oLinha;

    //Calcula TOTAL DE DESPESAS
    foreach ($oLinha->valores as $key => $value) {

      isset($aLinhasRelatorio[19]->valores[$key]) ? $aLinhasRelatorio[19]->valores[$key] += $value :
                                                    $aLinhasRelatorio[19]->valores[$key]  = $value;
    }
  }
  //Processa RECEITA CORRENTE LÍQUIDA (RCL)
  for ($iInd = 20; $iInd < 21; $iInd++) {

    $oLinha          = $aLinhasRelatorio[$iInd];
    $oLinha->valores = array();
    $oLinhaSoma      = new linhaRelatorioContabil(87, $oLinha->o69_codseq);
    $oLinhaSoma->setPeriodo($iCodigoPeriodo);
    $aLinhaSoma      = $oLinhaSoma->getValoresSomadosColunas($instit, $anousu);

   foreach ($aLinhaSoma as $oColuna) {

      foreach ($oColuna->colunas as $key => $oValor) {

       isset($oLinha->valores[$key]) ? $oLinha->valores[$key] += $oValor->o117_valor :
                                       $oLinha->valores[$key]  = $oValor->o117_valor;

      }
    }
    $aLinhasRelatorio[$iInd] = $oLinha;
  }
  if (!isset($lInResumido)) {

    $todasinstit="";
    $nValorRCLPeriodo  = 0;
    $nValorRCLAnterior = 0;
    $result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
    for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
      db_fieldsmemory($result_db_config, $xinstit);
      $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
    }
    duplicaReceitaaCorrenteLiquida($anousu,   81);
    duplicaReceitaaCorrenteLiquida($anousu-1, 59);
    $dDataPeriodo = "01-01-".$anousu;
    $aValorRCLAnterior = calcula_rcl2($anousu_ant, $dt_ini_ant, $dt_fin_ant, $todasinstit, true, 59);
    

    $nValorRCLPeriodo  += calcula_rcl2($anousu, $dDataPeriodo, $dt_fin, $todasinstit, false, 81);
    $nValorRCLPeriodo  += calcula_rcl2($anousu_ant, $dt_ini_ant, $dt_fin_ant, $todasinstit, false, 81,$dt_fin);
    $aLinhasRelatorio[20]->valores[1] += array_sum($aValorRCLAnterior);
    $aLinhasRelatorio[20]->valores[2] += $nValorRCLPeriodo;
  }
  //Calcula TOTAL DAS DESPESAS / RCL(%)
  foreach ($aLinhasRelatorio[20]->valores as $key => $value) {

    if($value != 0){
  	  $aLinhasRelatorio[21]->valores[$key] = ($aLinhasRelatorio[19]->valores[$key] / $value) * 100;
  	} else {
  		$aLinhasRelatorio[21]->valores[$key] = 0;
  	}
  }

if (!isset($arqinclude)) {

	$resultinst = db_query("select munic, uf from db_config where codigo in ({$instit}) ");
	$descr_inst = '';
	db_fieldsmemory($resultinst, 0);
	$descr_inst = $munic;

//$vdt_fin = split("-", $dt_fin);

  $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

  $aInstituicoes = explode(",", $instit);

  if (count($aInstituicoes) == 1) {

    $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
    $head2 = DemonstrativoFiscal::getEnteFederativo($oInstituicao);

    if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
      $head2 .= "\n" . $oInstituicao->getDescricao();
    }
  }else{
    $head2 = DemonstrativoFiscal::getEnteFederativo($oPrefeitura);
  }

  $head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4 = "DEMONSTRATIVO DAS PARCERIAS PÚBLICO-PRIVADAS";

  $dados  = data_periodo($anousu,$periodo_selecionado);
  $perini = split("-",$dados[0]);
  $perfin = split("-",$dados[1]);

  $txtper = strtoupper($dados["periodo"]);
  $mesini = db_mes($perini[1],1);
  $mesfin = db_mes($perfin[1],1);
  $head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  $head6 = "JANEIRO A ".$mesfin."/".$anousu." - ".$txtper." ".$mesini."-".$mesfin;

  $pdf   = new PDF();
  $pdf->Open();
  $pdf->setAutoPageBreak(false);
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);

  $alt    = 4;

  $pdf->addpage();

  $pdf->setfont('arial','',6);
  $pdf->cell(90,$alt,"RREO - ANEXO 13 (Lei n".chr(176)." 11.079, de 30.12.2004, arts, 22, 25 e 28)",0,0,"L",0);
  $pdf->cell(100,$alt,"R$ 1,00",0,1 ,"R",0);
  $pdf->cell(190,($alt-2)," "       ,"T",1,"L",0);
  $pdf->setfont('arial','B',6);

  //$pdf->setfont('arial','',6);

// Cabecalho de receitas
  $pdf->cell(80,($alt*4),"ESPECIFICAÇÃO"      ,'T',0,"C",1);
  $pdf->cell(30,$alt,"SALDO TOTAL EM 31 DE"   ,'LT' ,0,"C",1);
  $pdf->cell(60,$alt,"REGISTROS EFETUADOS EM" ,'LT' ,0,"C",1);
  $pdf->cell(20,$alt,"SALDO TOTAL"            ,'LT' ,1,"C",1);
  //BR
  $pdf->setX(90);
  $pdf->cell(30,$alt,"DEZEMBRO DO"            ,'L' ,0,"C",1);
  $pdf->cell(60,$alt,$anousu            ,'L' ,0,"C",1);
  $pdf->cell(20,$alt,""                       ,'LB' ,1,"C",1);

  $pdf->setX(90);
  $pdf->cell(30,$alt,"EXERCICIO ANTERIOR"     ,'L' ,0,"C",1);
  $pdf->cell(30,$alt,"No bimestre"            ,'LT' ,0,"C",1);
  $pdf->cell(30,$alt,"Até o bimestre"         ,'LT' ,0,"C",1);
  $pdf->cell(20,$alt,""                       ,'L' ,1,"C",1);

  $pdf->setX(90);
  $pdf->cell(30,$alt,"(a)"                    ,'L',0,"C",1);
  $pdf->cell(30,$alt,""                       ,'L',0,"C",1);
  $pdf->cell(30,$alt,"(b)"                    ,'L',0,"C",1);
  $pdf->cell(20,$alt,"(c)=(a+b)"              ,'L',1,"C",1);

  $pdf->setfont('arial','',6);
  for ($iInd = 0; $iInd < 9; $iInd++) {

  	$borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda : "";
    if ($iInd == 8){
      $borda .= "T";
    }
  	if($iInd == 0 or $iInd == 4){
  		$pdf->cell(80,$alt,$aLinhasRelatorio[$iInd]->o69_labelrel,$borda,0,"L",0);
  	}else{
      $pdf->cell(80,$alt,"   ".$aLinhasRelatorio[$iInd]->o69_labelrel,$borda,0,"L",0);
  	}

    $borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda."L" : "L";
    if ($iInd == 8){
      $borda .= "T";
    }
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[1],'f'),$borda,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[2],'f'),$borda,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[3],'f'),$borda,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores["total"],'f'),$borda,1,"R",0);
  }

  $borda = 'TLB';
  $pdf->cell(80,$alt,"SALDO LÍQUIDO DE PASSIVOS DE PPP(III) = (I-II)" ,'TB',0,"L",1);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[9]->valores[1],'f'),$borda,0,"R",1);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[9]->valores[2],'f'),$borda,0,"R",1);
  $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[9]->valores[3],'f'),$borda,0,"R",1);
  $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[9]->valores["total"],'f'),$borda,1,"R",1);

  for ($iInd = 10; $iInd < 17; $iInd++) {

    $borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda : "";
    $pdf->cell(80,$alt,"  ".$aLinhasRelatorio[$iInd]->o69_labelrel,$borda,0,"L",0);
    $borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda."L" : "L";
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[1],'f'),$borda,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[2],'f'),$borda,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores[3],'f'),$borda,0,"R",0);
    $pdf->cell(20,$alt,db_formatar($aLinhasRelatorio[$iInd]->valores["total"],'f'),$borda,1,"R",0);
  }

  $pdf->cell(190,$alt,""                       ,"T",1,"C",0);
  $pdf->cell(36,($alt*3),"DESPESAS DE PPP" ,'T',0,"C",1);
  $pdf->setX(46);
  $pdf->cell(14,($alt*1.5),"EXERCÍCIO"     ,'LT',0,"C",1);
  $pdf->cell(14,($alt),"EXERCÍCIO"         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+1         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+2         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+3         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+4         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+5         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+6         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+7         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+8         ,'LT',0,"C",1);
  $pdf->cell(14,($alt*3),$anousu+9         ,'LT',0,"C",1);

  $haltura = $pdf->GetY();

  $pdf->sety($haltura+($alt));
  $pdf->setX(46);
  $pdf->cell(14,$alt,""       ,'L',0,"CR",1);
  $pdf->cell(14,$alt,"CORRENTE"       ,'LR',0,"C",1);

  $haltura = $pdf->GetY();

  $pdf->sety($haltura+($alt));
  $pdf->setX(46);
  $pdf->cell(14,$alt,"ANTERIOR"       ,'LR',0,"C",1);
  $pdf->cell(14,$alt,$anousu           ,'LR',0,"C",1);

  $pdf->Ln();
  for ($iInd = 17; $iInd < 19; $iInd++) {

    $borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda : "T";
    $pdf->setfont('arial','',6);
    $pdf->cell(36,$alt,$aLinhasRelatorio[$iInd]->o69_labelrel, $borda, 0, "L", 0);
    $pdf->setfont('arial','',5);
    $borda = isset($aLinhasRelatorio[$iInd]->pdfBorda) ? $aLinhasRelatorio[$iInd]->pdfBorda."L" : "LT";
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[1],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[2],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[3],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[4],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[5],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[6],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[7],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[8],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[9],  'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[10], 'f'), $borda, 0, "R", 0);
    $pdf->cell(14,$alt, db_formatar($aLinhasRelatorio[$iInd]->valores[11], 'f'), $borda, 1, "R", 0);
  }

  $pdf->setfont('arial','',6);
  $pdf->cell(36,$alt,"TOTAL DE DESPESAS" ,'T',0,"L",1);
  $pdf->setfont('arial','',5);
  foreach ($aLinhasRelatorio[19]->valores as $value) {
  	$pdf->cell(14,$alt,db_formatar($value,'f'),'LT',0,"R",1);
  }


  $pdf->Ln();

  $pdf->setfont('arial','',5);
  $pdf->cell(36,$alt,$aLinhasRelatorio[20]->o69_labelrel ,'T',0,"L",0);
  $pdf->setfont('arial','',5);
  foreach ($aLinhasRelatorio[20]->valores as $value){
    $pdf->cell(14,$alt,db_formatar($value,'f'),'LT',0,"R",0);
  }

  $pdf->Ln();
  $pdf->setfont('arial','', 5);
  $iAlturaAnterior  = $pdf->GetY();
  $pdf->MultiCell(36, 2,"TOTAL DAS DESPESAS / RCL(%) (VI) = (IV)/(V)" ,'TB',"L", 1);
  $pdf->setfont('arial','',5);
  $pdf->SetXY(46, $iAlturaAnterior);
  foreach ($aLinhasRelatorio[21]->valores as $value){
    $pdf->cell(14, $alt, db_formatar($value,'f'),'LTB',0,"R",1);
  }

  $pdf->Ln(5);

  $oRelatorioContabil->getNotaExplicativa($pdf, $iCodigoPeriodo);
  //notasExplicativas(&$pdf, 87, "{$periodo_selecionado}", 190);

// Assinaturas
  $pdf->Ln(30);

  assinaturas($pdf,$classinatura,'LRF');

  $pdf->Output();
}
?>
