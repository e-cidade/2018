<?PHP
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_empage_classe.php"));
require_once(modification("classes/db_empagedadosret_classe.php"));
require_once(modification("classes/db_empagedadosretmov_classe.php"));
require_once(modification("classes/db_errobanco_classe.php"));
require_once(modification("classes/db_empagetipo_classe.php"));
$clempage = new cl_empage;
$clempagedadosret = new cl_empagedadosret;
$clempagedadosretmov = new cl_empagedadosretmov;
$clerrobanco= new cl_errobanco;
$clrotulo = new rotulocampo;
$clempage->rotulo->label();
$clempagedadosret->rotulo->label();
$clempagetipo = new cl_empagetipo();
$clempagedadosretmov->rotulo->label();
$clerrobanco->rotulo->label();
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e82_codord");
$clrotulo->label("e60_codemp");

parse_str($_SERVER['QUERY_STRING']);

if (isset($iCodigoGeracao) && !empty($iCodigoGeracao)) {

	$oDaoEmpAgeDadosRet = db_utils::getDao('empagedadosret');
	$sSqlDadosRet       = $oDaoEmpAgeDadosRet->sql_query(null, 'e75_codret', 'e75_codret desc', "e75_codgera = {$iCodigoGeracao}");
	$rsBuscaDadosRet    = $oDaoEmpAgeDadosRet->sql_record($sSqlDadosRet);
	if ($oDaoEmpAgeDadosRet->numrows == 0) {
		db_redireciona("db_erros.php?fechar=true&db_erro=Retorno $retorno não encontrado.");
	}
	$retorno = db_utils::fieldsMemory($rsBuscaDadosRet, 0)->e75_codret;
}
$pdf = new PDF("L");
$pdf->Open();
$pdf->SetAutoPageBreak(false, 1);
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;

$bEmiteRelatorio = false;

for ($iFiltro = 0; $iFiltro < 2; $iFiltro++) {

  if ($iFiltro == 0) {
     $sProcessa = "true";
   } else {
     $sProcessa = 'false';
   }
  $dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e75_codret in ({$retorno}) and e92_processa={$sProcessa}";

  if (isset($ordem)){
    if ( $ordem == 't' ){

      $dbwhere .= " and (case when e82_codmov is not null then corempagemov.k12_codmov is not null ";
      $dbwhere .= "           when e89_codmov is not null then slip.k17_situacao = 2 end)";
      $head9 = "Somente processados";
    } elseif ($ordem == 'f') {

      $dbwhere .= " and (case when e82_codmov is not null then corempagemov.k12_codmov is null ";
      $dbwhere .= "           when e89_codmov is not null then slip.k17_situacao <> 2 end)";
      $head9 = "Somente não processados";
    } elseif ($ordem == 0) {
      $head9 = "Mostrar: Todos";
     }
  }

  if (isset($sCodmovProcessados) && $sCodmovProcessados != ''){
     $dbwhere .= " and empagemov.e81_codmov in $sCodmovProcessados ";
  }

  if (isset($lCancelado) && $lCancelado == '0') {

    $dbwhere .= " and empageconfgera.e90_cancelado is false ";
  }

  $sSqlEmpAge = $clempage->sql_query_pagam(null,
                                           "distinct
                                            e53_valor,
                                            e53_vlranu,
                                            e53_vlrpag,
                                            e87_codgera,
                                            e87_descgera,
                                            e87_data,
                                            e87_hora,
                                            e83_descr,
                                            e83_codtipo,
                                            e83_conta,
                                            pc63_conta,
                                            pc63_dataconf,
                                            pc63_conta_dig,
                                            pc63_agencia,
                                            pc63_agencia_dig,
                                            e75_arquivoret,
                                            e76_lote,
                                            e76_movlote,
                                            e76_dataefet,
                                            e76_valorefet,
                                            e81_codmov,
                                            e60_codemp,
                                            e60_anousu,
                                            e60_instit,
                                            e89_codigo,
                                            e82_codord,
                                            e86_codmov,
                                            e92_processa,
                                            e92_coderro,
                                            e92_descrerro,
                                            case when a.z01_numcgm is not null then a.z01_numcgm
                                                 when cgmslip.z01_numcgm is not null then cgmslip.z01_numcgm
                                             else cgm.z01_numcgm
                                              end as z01_numcgm,
                                             case when a.z01_nome <> '' then a.z01_nome
                                                  when cgmslip.z01_nome <> '' then cgmslip.z01_nome
                                                  else cgm.z01_nome
                                              end as z01_nome,
                                             case when a.z01_cgccpf<>''
                                                 then a.z01_cgccpf
                                                 when cgmslip.z01_cgccpf<>''
                                                 then cgmslip.z01_cgccpf
                                                 else cgm.z01_cgccpf
                                             end as z01_cgccpf,
                                             e81_valor,
                                             e83_codtipo,
                                             e83_descr",
                                            "e92_processa desc,
                                             e83_codtipo,
                                             z01_nome,
                                             e76_lote,
                                             e76_movlote,
                                             e82_codord",
                                             $dbwhere);

  $result_retorno = $clempage->sql_record($sSqlEmpAge);
  $numrows_retorno = $clempage->numrows;
  if ($numrows_retorno == 0) {
    continue;
  }else{
    $bEmiteRelatorio = true;
  }
  db_fieldsmemory($result_retorno,0);

  if (!empty($e60_codemp)) {
    try {
      $oEmpenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($e60_codemp, $e60_anousu, InstituicaoRepository::getInstituicaoByCodigo($e60_instit));
    } catch (Exception $e) {
      db_redireciona("db_erros.php?fechar=true&db_erro={$e->getMessage()}.");
      exit;
    }
  }

  $dtPagamento = null;
  if (!empty($e60_codemp)) {
    $oConlancamEmp = new cl_conlancamemp();
    $sWhere        = " c75_numemp = {$oEmpenhoFinanceiro->getNumero()} ";
    $sWhere .= " and c53_tipo   = 30 order by c75_codlan desc limit 1";
    $sSqlLancamentoEmpenho = $oConlancamEmp->sql_query_documentos(null, 'c75_data', null, $sWhere);
    $rsBuscaLancamento     = db_query($sSqlLancamentoEmpenho);
    if ($rsBuscaLancamento) {
      $dtPagamento = db_utils::fieldsMemory($rsBuscaLancamento, 0)->c75_data;
    }
  }

  $head3 = "BAIXA DE PAGAMENTOS POR TRANSMISSÃO" ;
  $head5 = "Arquivo: ".db_formatar($e87_codgera,'s','0',5,'e',0).' - '.$e87_descgera;

  $p = 1;
  $alt = 4;
  $pagadora = "";
  $lAddPage = false;
  $arr_valconta = Array();
  $arr_valmovis = Array();
  $arr_valtconta = 0;
  $arr_valtmovis = 0;
  $aBaixasEfetuadas = array();
  $aInconsistencias = array();

  for( $i = 0; $i < $numrows_retorno; $i++) {

    $oRetorno = db_utils::fieldsMemory($result_retorno,$i);

    if(!isset($arr_valmovis[$oRetorno->e83_codtipo])){
      $arr_valmovis[$oRetorno->e83_codtipo] = 0;
    }
    if(!isset($arr_valconta[$oRetorno->e83_codtipo])){
      $arr_valconta[$oRetorno->e83_codtipo] = 0;
    }

    /**
     * Somente soma quando nao for erro/aviso
     * 00 - CREDITO EFETUADO
     */
    if ( $oRetorno->e92_coderro == '00'  || $oRetorno->e92_coderro == 'BW') {

      $arr_valmovis[$oRetorno->e83_codtipo] += $oRetorno->e81_valor;
      $arr_valconta[$oRetorno->e83_codtipo] += $oRetorno->e76_valorefet;

      $arr_valtmovis += $oRetorno->e81_valor;
      $arr_valtconta += $oRetorno->e76_valorefet;
    }

  }

  if (empty($dtPagamento)) {
    $dtPagamento = $oRetorno->e76_dataefet;
  }

  $head6 = "Data Movimento:  " . db_formatar($oRetorno->e87_data,"d");
  $head7 = "Data Baixa:  "     . db_formatar($dtPagamento,"d");
  $head8 = "Valor Total:  "    . trim(db_formatar($arr_valtconta,"f"));

  if ($sProcessa == "true") {

      $pdf->AddPage();
      $pdf->setfont('arial','b',8);
      $pdf->Cell(270,$alt,"CRÉDITOS EFETUADOS", "B",1,"C");

    } else if ($sProcessa == "false") {

      $pdf->AddPage();
      $pdf->Cell(270, $alt, "INCONSISTÊNCIAS", "B",1,"C");
  }
  for($i=0;$i<$numrows_retorno;$i++) {

    if($pdf->gety() > $pdf->h - 30){
        $pdf->AddPage();
    }
    db_fieldsmemory($result_retorno,$i);

    if ($e89_codigo != "") {

      $e60_codemp = "slip";
      $e82_codord = $e89_codigo;

    }
     /**
     * verifica a conta pagadora da linha
     */
    $sSqlDadosConta = $clempagetipo->sql_query_conplanoconta($e83_codtipo);
    $rsDadosConta   = $clempagetipo->sql_record($sSqlDadosConta);
    if ($clempagetipo->numrows > 0) {


      $oDadosConta = db_utils::fieldsMemory($rsDadosConta, 0);
      if ($oDadosConta->c63_banco == '104' && $e92_coderro == "BD") {
        $e92_descrerro = "CREDITO EFETUADO COM SUCESSO";
      }
    }
    if($pagadora!=$e83_codtipo) {

      if($pagadora != "") {

        $pdf->setfont('arial','b',6);
        $pdf->cell(210,$alt,'Total de Registros  :  '.$total,"TR",0,"R",1);
        $pdf->cell(25,$alt,'Valor R$:',"TR",0,"R",1);
        $pdf->cell(40,$alt,db_formatar( $arr_valconta[$pagadora],"f"),"T",0,"R",1);
        $pdf->ln(3.5);

      }
      $pagadora = $e83_codtipo;

      $total = 0;
      $pdf->setfont('arial','b',8);
      $pdf->cell(215,$alt,$e83_codtipo .' - '. $e83_descr." - CONTA: $e83_conta",0,1,"L",0);
      $pdf->cell(15,$alt,"Empenho",1,0,"C",1);
      $pdf->cell(15,$alt,"OP/Slip",1,0,"C",1);
      $pdf->cell(20,$alt,"Credor",1,0,"C",1);
      $pdf->cell(25,$alt,"CPF/CNPJ",1,0,"C",1);
      $pdf->cell(75,$alt,"Nome",1,0,"C",1);
      $pdf->cell(25,$alt,"Valor R$",1,0,"C",1);
      $pdf->cell(100,$alt,"Mensagem de Retorno",1,1,"C",1);

    }

    $pdf->setfont('arial','',6);
    $pdf->cell(15,$alt, $e60_codemp,0,0,"C",0);
    $pdf->cell(15,$alt, $e82_codord,0,0,"C",0);
    $pdf->cell(20,$alt, $z01_numcgm,0,0,"C",0);
    if ( strlen(trim($z01_cgccpf)) == 14 ) {
      $pdf->cell(25,$alt, db_formatar($z01_cgccpf,"cnpj"),0,0,"L",0);
	  } else if (strlen(trim($z01_cgccpf)) == 11){
      $pdf->cell(25,$alt, db_formatar($z01_cgccpf,"cpf"),0,0,"L",0);
	  }
    $pdf->cell(75,$alt, substr($z01_nome, 0, 65),0,0,"L",0);
    $pdf->cell(25,$alt, db_formatar($e76_valorefet,"f"),0,0,"R",0);
    $pdf->cell(100,$alt, substr($e92_descrerro, 0, 100),0,1,"L",0);
    $total++;

  }

  $pdf->setfont('arial','b',6);
  $pdf->setfont('arial','b',6);
  $pdf->cell(210,$alt,'Total de Registros  :  '.$total,"TR",0,"R",1);
  $pdf->cell(25,$alt,'Valor R$:',"TR",0,"R",1);
  $pdf->cell(40,$alt,db_formatar( $arr_valconta[$pagadora],"f"),"T",1,"R",1);
  $pdf->setfont('arial','b',8);

  $pdf->cell(210,$alt,"Total geral ",1,0,"R",1);
  $pdf->cell(65,$alt,db_formatar($arr_valtconta,"f"),"TBR",1,"R",1);
}

if ($bEmiteRelatorio == false) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Retorno $retorno não encontrado.");
}

$pdf->Output();