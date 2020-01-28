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

require_once(modification("classes/db_empresto_classe.php"));
if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

  require_once(modification("libs/db_liborcamento.php"));
  require_once(modification("libs/db_libcontabilidade.php"));
  require_once(modification("fpdf151/pdf.php"));
  require_once(modification("libs/db_sql.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("fpdf151/assinatura.php"));

  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_orcparamrelnota_classe.php"));
  require_once(modification("model/relatorioContabil.model.php"));

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($_POST);

  $classinatura = new cl_assinatura;
  $clorcparamrelnota = new cl_orcparamrelnota;

}

$clempresto   = new cl_empresto;
if (!isset($arqinclude)) { // se este arquivo no esta incluido por outro

  $xinstit    = split("-",$db_selinstit);
  $rsMunic    = db_query("select munic, uf, codigo,nomeinst,nomeinstabrev from db_config where prefeitura is true");
  $oMunic     = db_utils::fieldsMemory($rsMunic,0);
  $descr_inst = '';
  $xvirg      = '';
  $flag_abrev = false;
  ///////////////////////////////////////// Monta IN para filtro de Intiruio do SQL/////////////////////////////////////////
  $sele_work = ' o.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';

  ////////////////////////////////////////////////////Cria Tabela de Filtro///////////////////////////////////////////////////
  // echo "<br><br>".$p_orgao;
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $anousu    = db_getsession("DB_anousu");
  $oDaoPeriodo    = db_utils::getDao("periodo");
  $iCodigoPeriodo = $periodo;
  $sSqlPeriodo    = $oDaoPeriodo->sql_query($periodo);
  $sSiglaPeriodo  = db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0)->o114_sigla;
  $periodo        = $sSiglaPeriodo;
  $dt             = data_periodo($anousu,$sSiglaPeriodo);
  $dt_ini    = "{$anousu}-01-01";
  $dt_fin    = $dt[1];
  $xagrupa   = "rgo";
  $grupoini  = 1;
  $grupofin  = 3;

  if ($flag_abrev == false){
    if (strlen($descr_inst) > 42){
      $descr_inst = substr($descr_inst,0,100);
    }
  }
  $bimestre = substr($periodo,0,1); // bimestre do exercicio atual

  $head2  = "MUNICÍPIO DE {$oMunic->munic} - {$oMunic->uf}";
  $head3  = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
  $head4  = "DEMONSTRATIVO DOS RESTOS A PAGAR POR PODER E ÓRGÃO";
  $head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
  $txt    = strtoupper(db_mes('01'));
  $dt     = split("-",$dt_fin);
  $txt   .= " A ".strtoupper(db_mes($dt[1]));
  $txt   .= "  / ".$anousu;
  switch($bimestre){

      case 1:
        $txt   .= " - BIMESTRE ".strtoupper(db_mes('01'))." - ".strtoupper(db_mes('02'));
      break;
      case 2:
        $txt   .= " - BIMESTRE ".mb_strtoupper(db_mes('03'))." - ".strtoupper(db_mes('04'));
      break;
      case 3:
        $txt   .= " - BIMESTRE ".strtoupper(db_mes('05'))." - ".strtoupper(db_mes('06'));
      break;
      case 4:
        $txt   .= " - BIMESTRE ".strtoupper(db_mes('07'))." - ".strtoupper(db_mes('08'));
      break;
      case 5:
        $txt   .= " - BIMESTRE ".strtoupper(db_mes('09'))." - ".strtoupper(db_mes('10'));
      break;
      case 6:
        $txt   .= " - BIMESTRE ".strtoupper(db_mes('11'))." - ".strtoupper(db_mes('12'));
      break;
}
$head6 = "$txt";


  $instit = ' e60_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  $where  = " ";
  $order  = '';
  $perini = $dt_ini;
  $perfin = $dt_fin;

}else{

  $perini = $dt_ini;
  $perfin = $dt_fin;
  $where  = "";
  $order  = "";
  $instit = $db_filtro;

}

$Param_NotaLiquidacao = pg_result(db_query("select e30_notaliquidacao from empparametro where e39_anousu = ".db_getsession("DB_anousu")),0,0);

$sqlperiodo = $clempresto->sql_rp2(db_getsession("DB_anousu"), $instit, $perini, $perfin, $where,$order);

$sSQLPeriodo  = " select * from (";
$sSQLPeriodo .= "                select e60_instit,";
$sSQLPeriodo .= "                       nomeinst,";
$sSQLPeriodo .= "                       o58_orgao,";
$sSQLPeriodo .= "                       o40_descr,";
$sSQLPeriodo .= "                       db21_tipoinstit,";
$sSQLPeriodo .= "                       sum(case when e60_anousu < ({$anousu} - 1) then";
$sSQLPeriodo .= "                                e91_vlrliq-e91_vlrpag	";
$sSQLPeriodo .= "                            else 0 end ) as  inscricao_ant,";
$sSQLPeriodo .= "                       sum(case when e60_anousu = ({$anousu} - 1) then";
$sSQLPeriodo .= "                                e91_vlrliq-e91_vlrpag";
$sSQLPeriodo .= "                           else 0 end ) as  valor_processado,";
$sSQLPeriodo .= "                       sum(case when e60_anousu < ({$anousu} - 1) then";
$sSQLPeriodo .= "                                e91_vlremp-e91_vlranu-e91_vlrliq";
$sSQLPeriodo .= "                           else 0 end ) as  valor_nao_processado_ant,";
$sSQLPeriodo .= "                       sum(case when e60_anousu = ({$anousu} - 1) then";
$sSQLPeriodo .= "                                e91_vlremp-e91_vlranu-e91_vlrliq";
$sSQLPeriodo .= "                           else 0 end ) as  valor_nao_processado,";
$sSQLPeriodo .= "                       sum(coalesce(e91_vlremp,0)) as e91_vlremp,";
$sSQLPeriodo .= "                       sum(coalesce(e91_vlranu,0)) as e91_vlranu,";
$sSQLPeriodo .= "                       sum(coalesce(e91_vlrliq,0)) as e91_vlrliq,";
$sSQLPeriodo .= "                       sum(coalesce(e91_vlrpag,0)) as e91_vlrpag,";
$sSQLPeriodo .= "                       sum(coalesce(vlranu,0)) as vlranu,";
$sSQLPeriodo .= "                       sum(coalesce(canc_proc,0)) as canc_proc,";
$sSQLPeriodo .= "                       sum(coalesce(canc_nproc,0)) as canc_nproc,";
$sSQLPeriodo .= "                       sum(coalesce(vlrliq,0)) as vlrliq,";
$sSQLPeriodo .= "                       sum(coalesce(vlrpag,0)) as vlrpag,";
$sSQLPeriodo .= "                      sum(coalesce(vlrpagnproc,0)) as vlrpagnproc";
$sSQLPeriodo .= "                  from ({$sqlperiodo}) as x";

$sSQLPeriodo .= "                 where substr(o56_elemento,4,2) != '91'";
$sSQLPeriodo .= "                 group by e60_instit,";
$sSQLPeriodo .= "                          nomeinst,";
$sSQLPeriodo .= "                          db21_tipoinstit,";
$sSQLPeriodo .= "                          o58_orgao,";
$sSQLPeriodo .= "                          o40_descr";
$sSQLPeriodo .= "               ) as foo";
$sSQLPeriodo .= "        order by db21_tipoinstit,o58_orgao";

//die($sSQLPeriodo);

$result = db_query($sSQLPeriodo) or die("Erro:".$sSQLPeriodo);

/*RPS de receita intra-Orçamentaria*/
$sqlPeriodoIntra  = "select *";
$sqlPeriodoIntra .= "  from (";
$sqlPeriodoIntra .= "        select e60_instit,";
$sqlPeriodoIntra .= "               nomeinst,";
$sqlPeriodoIntra .= "               db21_tipoinstit,";
$sqlPeriodoIntra .= "               o58_orgao,";
$sqlPeriodoIntra .= "               o40_descr,";
$sqlPeriodoIntra .= "               sum(case when e60_anousu < ({$anousu} - 1) then";
$sqlPeriodoIntra .= "                             e91_vlrliq-e91_vlrpag";
$sqlPeriodoIntra .= "                   else 0 end ) as  inscricao_ant,";
$sqlPeriodoIntra .= "               sum(case when e60_anousu = ({$anousu} - 1) then";
$sqlPeriodoIntra .= "                             e91_vlrliq-e91_vlrpag";
$sqlPeriodoIntra .= "               else 0 end ) as  valor_processado,";
$sqlPeriodoIntra .= "               sum(case when e60_anousu < ({$anousu} - 1) then";
$sqlPeriodoIntra .= "                             e91_vlremp-e91_vlranu-e91_vlrliq";
$sqlPeriodoIntra .= "                   else 0 end ) as  valor_nao_processado_ant,";
$sqlPeriodoIntra .= "              sum(case when e60_anousu = ({$anousu} - 1) then";
$sqlPeriodoIntra .= "                            e91_vlremp-e91_vlranu-e91_vlrliq";
$sqlPeriodoIntra .= "               else 0 end ) as  valor_nao_processado,";
$sqlPeriodoIntra .= "               sum(coalesce(e91_vlremp,0)) as e91_vlremp,";
$sqlPeriodoIntra .= "               sum(coalesce(e91_vlranu,0)) as e91_vlranu,";
$sqlPeriodoIntra .= "               sum(coalesce(e91_vlrliq,0)) as e91_vlrliq,";
$sqlPeriodoIntra .= "               sum(coalesce(e91_vlrpag,0)) as e91_vlrpag,";
$sqlPeriodoIntra .= "               sum(coalesce(vlranu,0)) as vlranu,";
$sqlPeriodoIntra .= "               sum(coalesce(canc_proc,0)) as canc_proc,";
$sqlPeriodoIntra .= "               sum(coalesce(canc_nproc,0)) as canc_nproc,";
$sqlPeriodoIntra .= "               sum(coalesce(vlrliq,0)) as vlrliq,";
$sqlPeriodoIntra .= "               sum(coalesce(vlrpag,0)) as vlrpag,";
$sqlPeriodoIntra .= "               sum(coalesce(vlrpagnproc,0)) as vlrpagnproc";
$sqlPeriodoIntra .= "          from ({$sqlperiodo}) as x";
$sqlPeriodoIntra .= "         where substr(o56_elemento,4,2) = '91'";
$sqlPeriodoIntra .= "         group by e60_instit,db21_tipoinstit,nomeinst,o58_orgao,o40_descr";
$sqlPeriodoIntra .= "       ) as foo ";
$sqlPeriodoIntra .= "  order by db21_tipoinstit,o58_orgao";
$result_intra     = db_query($sqlPeriodoIntra) or die($sqlPeriodoIntra);

// db_criatabela($result);
// exit;

/////////////////////////////////////////////// Abertura de PDF ////////////////////////////////////////////////////////////
/* aqui calculamos os totais para cada instituicoes e o total geral das receitas nao intra-orçamentarias
** para as instituicoes criamos um array com os valores, onde a chave
** e o codigo da instituicao, e um para de chave, valor para cada coluna do relatorio.
*/
$nTotNintraInscricaoAnterior           = 0;
$nTotNintraInscricaoAnteriorProcessado = 0;
$nTotNintraCancelado                   = 0;
$nTotNintraPago                        = 0;
$nTotNintraApagar                      = 0;
//valores dos RPS nao Processados.
$nTotNintraNPanterior                  = 0;
$nTotNintraNPDez                       = 0;
$nTotNintraNPCancelados                = 0;
$nTotNintraNPPagos                     = 0;
$nTotNintraNPAPAgar                    = 0;
$nTotApagarNProc                       = 0;
$nTotalLiquidadoNaoProcessado          = 0;

for ($iInd = 0; $iInd < pg_num_rows($result); $iInd++){

   $oTotais         = db_utils::fieldsMemory($result, $iInd);
   $e60_instit      = $oTotais->e60_instit;
   $nTotApagarNproc = ($oTotais->valor_nao_processado_ant+$oTotais->valor_nao_processado) - $oTotais->canc_nproc;
   $nTotApagarProc  = ($oTotais->valor_processado + $oTotais->inscricao_ant) - $oTotais->canc_proc - $oTotais->vlrpag;
   if($nTotApagarProc < 0 && $Param_NotaLiquidacao == "") {

     $nTotApagarProc *= -1;
     //$pago_nao_processado = $nTotApagarProc;
     $oTotais->vlrpag     = $oTotais->vlrpag - $nTotApagarProc;
     $nTotApagarProc      = 0;
   }
   $valor_novo =  ($oTotais->valor_nao_processado+$oTotais->valor_nao_processado_ant)-$oTotais->canc_nproc-$oTotais->vlrpagnproc;
   if( ($valor_novo) < 0 ){;
     /*
      * Retirado a soma do valor cancelado processado com o valor da variável valor_novo
      * pois estava gerando diferença com o relatório de Execução de Restos à Pagar
      */
     $oTotais->canc_proc = $oTotais->canc_proc;// + (($valor_novo) * -1 );
     $nTotApagarProc     = ($nTotApagarProc - (($valor_novo) * -1 ));

     $oTotais->canc_nproc = ( $oTotais->canc_nproc );// - (($valor_novo) * -1 ));
     $valor_novo = 0;
   }
   $nTotNintraInscricaoAnterior           += $oTotais->inscricao_ant;
   $nTotNintraInscricaoAnteriorProcessado += $oTotais->valor_processado;
   $nTotNintraCancelado                   += $oTotais->canc_proc;
   $nTotNintraPago                        += $oTotais->vlrpag;
   $nTotNintraApagar                      += $nTotApagarProc;

   //valores dos RPS nao Processados.
   $nTotNintraNPanterior                  += $oTotais->valor_nao_processado_ant;
   $nTotNintraNPDez                       += $oTotais->valor_nao_processado;
   $nTotNintraNPCancelados                += $oTotais->canc_nproc;
   $nTotNintraNPPagos                     += $oTotais->vlrpagnproc;
   $nTotNintraNPAPAgar                    += $valor_novo;
   $nTotalLiquidadoNaoProcessado          += $oTotais->vlrliq;

   if (isset($aTotInstit[$e60_instit])){

       $aTotInstit[$e60_instit][0]  += $oTotais->inscricao_ant;
       $aTotInstit[$e60_instit][1]  += $oTotais->valor_processado;
       $aTotInstit[$e60_instit][2]  += $oTotais->canc_proc;
       $aTotInstit[$e60_instit][3]  += $oTotais->vlrpag;
       $aTotInstit[$e60_instit][4]  += $nTotApagarProc;
       $aTotInstit[$e60_instit][5]  += $oTotais->valor_nao_processado_ant;
       $aTotInstit[$e60_instit][6]  += $oTotais->valor_nao_processado;
       $aTotInstit[$e60_instit][7]  += $oTotais->canc_nproc;
       $aTotInstit[$e60_instit][8]  += $oTotais->vlrpagnproc;
       $aTotInstit[$e60_instit][9]  += $valor_novo;
       $aTotInstit[$e60_instit][10] += $oTotais->vlrliq;

   }else{

       $aTotInstit[$e60_instit][0] = $oTotais->inscricao_ant;
       $aTotInstit[$e60_instit][1] = $oTotais->valor_processado;
       $aTotInstit[$e60_instit][2] = $oTotais->canc_proc;
       $aTotInstit[$e60_instit][3] = $oTotais->vlrpag;
       $aTotInstit[$e60_instit][4] = $nTotApagarProc;
       $aTotInstit[$e60_instit][5] = $oTotais->valor_nao_processado_ant;
       $aTotInstit[$e60_instit][6] = $oTotais->valor_nao_processado;
       $aTotInstit[$e60_instit][7] = $oTotais->canc_nproc;
       $aTotInstit[$e60_instit][8] = $oTotais->vlrpagnproc;
       $aTotInstit[$e60_instit][9] = $valor_novo;
       $aTotInstit[$e60_instit][10] = $oTotais->vlrliq;
   }
}

/* aqui calculamos os totais para cada instituicoes e o total geral das receitas  intra-orçamentarias
** para as instituicoes criamos um array com os valores, onde a chave
** e o codigo da instituicao, e um para de chave, valor para cada coluna do relatorio.
*/
$nTotintraInscricaoAnterior           = 0;
$nTotintraInscricaoAnteriorProcessado = 0;
$nTotintraCancelado                   = 0;
$nTotintraPago                        = 0;
$nTotintraApagar                      = 0;
//valores dos RPS nao Processados.
$nTotintraNPanterior                  = 0;
$nTotintraNPDez                       = 0;
$nTotintraNPCancelados                = 0;
$nTotintraNPPagos                     = 0;
$nTotintraNPAPAgar                    = 0;
$nTotalIntraValorLiquidado            = 0;
$aTotInstitIntra = array();
for ($iInd = 0; $iInd < pg_num_rows($result_intra); $iInd++){

   $pago_nao_processado = 0;

   $oTotais         = db_utils::fieldsMemory($result_intra, $iInd);
   $e60_instit      = $oTotais->e60_instit;
   $nTotApagarNproc = ($oTotais->valor_nao_processado_ant+$oTotais->valor_nao_processado) - $oTotais->canc_nproc;
   $nTotApagarProc  = ($oTotais->valor_processado + $oTotais->inscricao_ant) - $oTotais->canc_proc - $oTotais->vlrpag;
   if($nTotApagarProc < 0 && $Param_NotaLiquidacao == ""){
     $nTotApagarProc *= -1;
     $pago_nao_processado = $nTotApagarProc ;
     $oTotais->vlrpag     = $oTotais->vlrpag - $nTotApagarProc;
     $nTotApagarProc      = 0;
   }
   $valor_novo =  ($oTotais->valor_nao_processado_ant+$oTotais->valor_nao_processado)-$oTotais->canc_nproc-$oTotais->vlrpagnproc;
   if( ($valor_novo) < 0 ){;

     $oTotais->canc_proc = $oTotais->canc_proc + (($valor_novo) * -1 );
     $nTotApagarProc     = ($nTotApagarProc - (($valor_novo) * -1 ));

     $oTotais->canc_nproc = ( $oTotais->canc_nproc - (($valor_novo) * -1 ));
     $valor_novo = 0;
   }
   $nTotintraInscricaoAnterior           += $oTotais->inscricao_ant;
   $nTotintraInscricaoAnteriorProcessado += $oTotais->valor_processado;
   $nTotintraCancelado                   += $oTotais->canc_proc;
   $nTotintraPago                        += $oTotais->vlrpag;
   $nTotintraApagar                      += $nTotApagarProc;

   //valores dos RPS nao Processados.
   $nTotintraNPanterior                  += $oTotais->valor_nao_processado_ant;
   $nTotintraNPDez                       += $oTotais->valor_nao_processado;
   $nTotintraNPCancelados                += $oTotais->canc_nproc;
   $nTotintraNPPagos                     += $oTotais->vlrpagnproc;
   $nTotintraNPAPAgar                    += $valor_novo;
   $nTotalIntraValorLiquidado            += $oTotais->vlrliq;

   if (isset($aTotInstitIntra[$e60_instit])){

       $aTotInstitIntra[$e60_instit][0] += $oTotais->inscricao_ant;
       $aTotInstitIntra[$e60_instit][1] += $oTotais->valor_processado;
       $aTotInstitIntra[$e60_instit][2] += $oTotais->canc_proc;
       $aTotInstitIntra[$e60_instit][3] += $oTotais->vlrpag;
       $aTotInstitIntra[$e60_instit][4] += $nTotApagarProc;
       $aTotInstitIntra[$e60_instit][5] += $oTotais->valor_nao_processado_ant;
       $aTotInstitIntra[$e60_instit][6] += $oTotais->valor_nao_processado;
       $aTotInstitIntra[$e60_instit][7] += $oTotais->canc_nproc;
       $aTotInstitIntra[$e60_instit][8] += $oTotais->vlrpagnproc;
       $aTotInstitIntra[$e60_instit][9] += $valor_novo;
       $aTotInstitIntra[$e60_instit][10] += $oTotais->vlrliq;

   }else{

       $aTotInstitIntra[$e60_instit][0] = $oTotais->inscricao_ant;
       $aTotInstitIntra[$e60_instit][1] = $oTotais->valor_processado;
       $aTotInstitIntra[$e60_instit][2] = $oTotais->canc_proc;
       $aTotInstitIntra[$e60_instit][3] = $oTotais->vlrpag;
       $aTotInstitIntra[$e60_instit][4] = $nTotApagarProc;
       $aTotInstitIntra[$e60_instit][5] = $oTotais->valor_nao_processado_ant;
       $aTotInstitIntra[$e60_instit][6] = $oTotais->valor_nao_processado;
       $aTotInstitIntra[$e60_instit][7] = $oTotais->canc_nproc;
       $aTotInstitIntra[$e60_instit][8] = $oTotais->vlrpagnproc;
       $aTotInstitIntra[$e60_instit][9] = $valor_novo;
       $aTotInstitIntra[$e60_instit][10] = $oTotais->vlrliq;
   }
}
//if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

  $pdf = new PDF("L");
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->SetAutoPageBreak(false);
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',7);
  $alt = 4;
  /////////////////////////////////////////////// Cabealho  /////////////////////////////////////////////////////////////

  $pdf->addpage();
  $pdf->cell(01,$alt,'RREO - ANEXO 7 (LRF, art. 53, inciso V)',"B",0,"L",0);
  $pdf->cell(279,$alt,'R$ 1,00',"B",1,"R",0);
  writeHeader($pdf, $alt);
  $pdf->setfont('arial','',4);


//}

$i       = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$instit  = '';
// totais processados
$tot_01 = 0;
$tot_02 = 0;
$tot_03 = 0;
$tot_04 = 0;
$tot_05 = 0;
$tot_06 = 0;
$tot_07 = 0;
$tot_08 = 0;
$tot_09 = 0;
$tot_10 = 0; //total de RPS, nao processados anos anteriores
$tot_11 = 0;

//
$a_pagar_processado     = 0;
$a_pagar_nao_processado = 0;
//db_criatabela($result);exit;


// Usado no simplificado dos RESTOS A PAGAR /////////////////////////////////////////////////////////////////////////////////

  // Poder Executivo
  // processados
  $tot_restos_pc_insc_ant_exec      = 0;
  $tot_restos_pc_inscritos_exec     = 0;
  $tot_restos_pc_cancelados_exec    = 0;
  $tot_restos_pc_pagos_exec         = 0;
  $tot_restos_pc_saldo_exec         = 0;

  // nao processados
  $tot_restos_naopc_insc_ant_exec   = 0;
  $tot_restos_naopc_inscritos_exec  = 0;
  $tot_restos_naopc_cancelados_exec = 0;
  $tot_restos_naopc_pagos_exec      = 0;
  $tot_restos_naopc_saldo_exec      = 0;
//////////////////////////////////////////////////////////////////////

  // Poder Legislativo
  // processados
  $tot_restos_pc_insc_ant_legal      = 0;
  $tot_restos_pc_inscritos_legal     = 0;
  $tot_restos_pc_cancelados_legal    = 0;
  $tot_restos_pc_pagos_legal         = 0;
  $tot_restos_pc_saldo_legal         = 0;

  // nao processados
  $tot_restos_naopc_insc_ant_legal   = 0;
  $tot_restos_naopc_inscritos_legal  = 0;
  $tot_restos_naopc_cancelados_legal = 0;
  $tot_restos_naopc_pagos_legal      = 0;
  $tot_restos_naopc_saldo_legal      = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$pdf->setfont('arial','',6);
$posicao_exceto_intra = $pdf->getY();
$pdf->cell(62,$alt,'RESTOS A PAGAR (EXCETO INTRA-ORÇAMENTÁRIOS)(I)', "0", 0, "L", 0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraInscricaoAnterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraInscricaoAnteriorProcessado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraPago),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraCancelado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraApagar),'f'),"LR",0,"R",0);
// no processados
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPanterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPDez),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotalLiquidadoNaoProcessado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPPagos),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPCancelados),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPAPAgar),'f'),"L", 0,"RL",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotNintraNPAPAgar) + abs($nTotNintraApagar),'f'), "L",1,"R",0);
$pdf->setfont('arial','',6);
//db_criatabela($result);exit;
for ($x=0;$x<pg_numrows($result);$x++){

  if ($pdf->getY() > $pdf->h - 20){

      $pdf->cell(280,$alt,"Continua (".($pdf->pageNo()+1)."/{nb})",0,0,"R");
      $pdf->setfont('arial','b',7);
      $alt = 4;
      $pdf->addpage();
      writeHeader($pdf, $alt);
      $pdf->cell(280,$alt,"Continuação","B",0,"R");
      $pdf->setfont('arial','',4);

  }
  extract((array) db_utils::fieldsMemory($result, $x));

  if ($instit != $e60_instit) {
    $instit = $e60_instit;

    if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro
      $pdf->setfont('arial','b',6);
      if ($db21_tipoinstit == 1){
          $sPoder = '  PODER EXECUTIVO';
      }else if ($db21_tipoinstit == 2){
          $sPoder = '  PODER LEGISLATIVO';
      }else{
         $sPoder = "  {$nomeinst}";
      }
      $pdf->cell(62,$alt,$sPoder, "0", 0, "L", 0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][0]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][1]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][3]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][2]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][4]),'f'),"LR",0,"R",0);
      // no processados
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][5]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][6]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][10]), 'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][8]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][7]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][9]),'f'),"L",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstit[$instit][9]) +abs($aTotInstit[$instit][4]) ,'f'),"L",1,"R",0);
      $pdf->setfont('arial','',6);


    }
// Usado no simplificado dos RESTOS A PAGAR /////////////////////////////////////////////////////////////////////////////////
  if ($db21_tipoinstit == 1 || $db21_tipoinstit != 2) {    // Totais do PODER EXECUTIVO e RPPS
    $tot_restos_pc_insc_ant_exec      += abs($aTotInstit[$instit][0]);
    $tot_restos_pc_inscritos_exec     += abs($aTotInstit[$instit][1]);
    $tot_restos_pc_cancelados_exec    += abs($aTotInstit[$instit][2]);
    $tot_restos_pc_pagos_exec         += abs($aTotInstit[$instit][3]);
    $tot_restos_pc_saldo_exec         += abs($aTotInstit[$instit][4]);

    $tot_restos_naopc_insc_ant_exec   += abs($aTotInstit[$instit][5]);
    $tot_restos_naopc_inscritos_exec  += abs($aTotInstit[$instit][6]);
    $tot_restos_naopc_cancelados_exec += abs($aTotInstit[$instit][7]);
    $tot_restos_naopc_pagos_exec      += abs($aTotInstit[$instit][8]);
    $tot_restos_naopc_saldo_exec      += abs($aTotInstit[$instit][9]);
  }

  if ($db21_tipoinstit == 2) {    // Totais do PODER LEGISLATIVO
    $tot_restos_pc_insc_ant_legal      += abs($aTotInstit[$instit][0]);
    $tot_restos_pc_inscritos_legal     += abs($aTotInstit[$instit][1]);
    $tot_restos_pc_cancelados_legal    += abs($aTotInstit[$instit][2]);
    $tot_restos_pc_pagos_legal         += abs($aTotInstit[$instit][3]);
    $tot_restos_pc_saldo_legal         += abs($aTotInstit[$instit][4]);

    $tot_restos_naopc_insc_ant_legal   += abs($aTotInstit[$instit][5]);
    $tot_restos_naopc_inscritos_legal  += abs($aTotInstit[$instit][6]);
    $tot_restos_naopc_cancelados_legal += abs($aTotInstit[$instit][7]);
    $tot_restos_naopc_pagos_legal      += abs($aTotInstit[$instit][8]);
    $tot_restos_naopc_saldo_legal      += abs($aTotInstit[$instit][9]);
  }
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  }
  $a_pagar_processado     = ($valor_processado+$inscricao_ant) - $canc_proc - $vlrpag;
  $a_pagar_nao_processado = $vlrpagnproc; #$valor_nao_processado - $canc_nproc;

  // -----------------------------------------------------
  if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

    $pdf->cell(62, $alt,'     '.$o58_orgao .'-'.substr($o40_descr,0,40), "R", 0, "L", 0);
    // anterior ao exercicio de inscrio
    $pdf->cell(18, $alt,db_formatar($inscricao_ant,'f'),"LR",0,"R",0);      // processados
    $pdf->cell(18, $alt,db_formatar($valor_processado,'f'),"R",0,"R",0);    // o cancelamento sempre ocorre com os no liquidados, porque sempre ocorre o estorno de liquidao para depois o estorno de rp
  }

  $pago_nao_processado = 0;

  if($a_pagar_processado < 0 && $Param_NotaLiquidacao == ""){
    $a_pagar_processado *= -1;
    $pago_nao_processado = $a_pagar_processado ;
    $vlrpag              = $vlrpag - $a_pagar_processado;
    $a_pagar_processado  = 0;
  }

  $pago_nao_processado = $vlrpagnproc;

  $valor_novo =  ($valor_nao_processado_ant+$valor_nao_processado)-$canc_nproc-$pago_nao_processado;
  //echo $valor_novo."<br>";
  if( ($valor_novo) < 0 ){
     /*
      * Retirado a soma do valor cancelado pois estava duplicando os valores causando inconsistência com o relatório de Execução de Restos à pagar
      */
    //$canc_proc = $canc_proc + (($valor_novo) * -1 );
    $a_pagar_processado = ($a_pagar_processado - (($valor_novo) * -1 ));

    //$canc_nproc = ( $canc_nproc - (($valor_novo) * -1 ));
    $valor_novo = 0;
  }

  if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro
    //echo $canc_proc." - ".$canc_nproc."<br>";

    $pdf->cell(18,$alt,db_formatar(abs($vlrpag),'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($canc_proc),'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($a_pagar_processado),'f'),"R",0,"R",0);     // no processados

    $pdf->cell(18,$alt,db_formatar(abs($valor_nao_processado_ant),'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($valor_nao_processado),'f'),"R",0,"R",0);
    //$pdf->cell(20,$alt,db_formatar(abs($pago_nao_processado),'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($vlrliq),'f'),"LR",0,"R",0);
    $pdf->cell(18,$alt,db_formatar($vlrpagnproc, 'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($canc_nproc),'f'),"R",0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($valor_novo),'f'),"0", 0,"R",0);
    $pdf->cell(18,$alt,db_formatar(abs($valor_novo) + abs($a_pagar_processado),'f'),"L",1,"R",0);
    $pdf->setfont('arial','',6);

  }
  $i++;
//  if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

    $tot_01 += $inscricao_ant;
    $tot_02 += $valor_processado ;
    $tot_03 += $canc_proc ;
    $tot_04 += $vlrpag ;
    $tot_05 += $a_pagar_processado ;
    // totais no processados
    $tot_06 += $valor_nao_processado;
    $tot_07 += $canc_nproc;
    $tot_08 += $vlrpagnproc;

    // $tot_09 += ($valor_nao_processado_ant+$valor_nao_processado)-$canc_nproc-$vlrpagnproc;
    $tot_10 += $valor_nao_processado_ant; //Valores nao processados dos anos anteriores
    $tot_11 += $vlrliq;
//  }

}

if (!isset($arqinclude)) { // se este arquivo no esta incluido por outro

  // totais exceto intra
  $pdf->setfont('arial','',6);
  $posicao = $pdf->getY();
  $pdf->setY($posicao_exceto_intra);
	$pdf->ln();
  $instit = 0;
  $pdf->setY($posicao);

}

//db_criatabela($result_intra);exit;

$pdf->cell(62,$alt,'RESTOS A PAGAR (INTRA-ORÇAMENTÁRIOS)(II)', "0", 0, "L", 0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnteriorProcessado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraPago),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraCancelado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraApagar),'f'),"LR",0,"R",0);
// no processados
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPanterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPDez),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotalIntraValorLiquidado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPPagos),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPCancelados),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar),'f'),"L", 0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar) + abs($nTotintraApagar),'f'),"L",1,"R",0);
$pdf->Line(10, $pdf->GetY(), 288, $pdf->GetY());

$pdf->cell(62,$alt,'TOTAL (III) = (I + II)', "RTB", 0, "L", 0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraInscricaoAnterior) + abs($nTotNintraInscricaoAnterior),'f'),"LRBT",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraInscricaoAnteriorProcessado) + abs($nTotNintraInscricaoAnteriorProcessado),'f'),"LRTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraPago) + abs($nTotNintraPago),'f'),"LRTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraCancelado) + abs($nTotNintraCancelado),'f'),"LTRB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraApagar) + abs($nTotNintraApagar),'f'),"LTRB",0,"R",0);
// nao processados
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPanterior) + abs($nTotNintraNPanterior),'f'),"TLRB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPDez) + abs($nTotNintraNPDez),'f'),"TLRB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotalIntraValorLiquidado) + abs($nTotalLiquidadoNaoProcessado),'f'),"LTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPPagos + $nTotNintraNPPagos),'f'),"LRTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPCancelados) + abs($nTotNintraNPCancelados),'f'),"LRTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPAPAgar) + abs($nTotNintraNPAPAgar),'f'),"LTB",0,"R",0);
$pdf->cell(18, $alt,db_formatar(abs($nTotintraNPAPAgar) + abs($nTotNintraNPAPAgar) + abs($nTotintraApagar) + abs($nTotNintraApagar) ,'f'),"LTB",1,"R",0);
$pdf->setfont('arial','',6);

$pdf->ln();

writeHeader($pdf, $alt);
$pdf->cell(62,$alt,'RESTOS A PAGAR (INTRA-ORÇAMENTÁRIOS)(II)', "0", 0, "L", 0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnteriorProcessado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraPago),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraCancelado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraApagar),'f'),"LR",0,"R",0);
// no processados
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPanterior),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPDez),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotalIntraValorLiquidado),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPPagos),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPCancelados),'f'),"LR",0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar),'f'),"L", 0,"R",0);
$pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar) + abs($nTotintraApagar),'f'),"L",1,"R",0);
$pdf->setfont('arial','',6);

$aInstituicoes = array();
for ($x=0;$x<pg_numrows($result_intra);$x++) {

  extract((array) db_utils::fieldsMemory($result_intra, $x));

  $oResultadoIntra = db_utils::fieldsMemory($result_intra,$x);
  if ($pdf->getY() > $pdf->h - 30) {

      $pdf->cell(280, $alt,"Continua(".($pdf->pageNo()+1)."/{nb})","T",0,"R");
      $pdf->setfont('arial','b',7);
      $alt = 4;
      $pdf->addpage();
      $pdf->ln();
      $pdf->cell(280, $alt, "Continuação","B", 1, "R");
      writeHeader($pdf, $alt);
      $pdf->setfont('arial','',4);
  }

  if (!isset($aInstituicoes[$oResultadoIntra->db21_tipoinstit])) {
    $aInstituicoes[$oResultadoIntra->db21_tipoinstit] = array();
  }

  if (!in_array($e60_instit, $aInstituicoes[$oResultadoIntra->db21_tipoinstit])) {
    $aInstituicoes[$oResultadoIntra->db21_tipoinstit][] = $e60_instit;
  }
  if ($instit != $e60_instit){
    $instit = $e60_instit;

    if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

      if ($db21_tipoinstit == 1){
          $sPoder = '  PODER EXECUTIVO';
      }else if ($db21_tipoinstit == 2){
          $sPoder = '  PODER LEGISLATIVO';
      }else{
         $sPoder = "  {$nomeinst}";
      }
      $pdf->setfont('arial','b',6);
      $pdf->cell(62,$alt,$sPoder, "0", 0, "L", 0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][0]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][1]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][3]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][2]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][4]),'f'),"LR",0,"R",0);
      // no processados
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][5]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][6]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][10]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][8]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][7]),'f'),"LR",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][9]),'f'),"L",0,"R",0);
      $pdf->cell(18,$alt,db_formatar(abs($aTotInstitIntra[$instit][9]) + abs($aTotInstitIntra[$instit][4]),'f'),"L",1,"R",0);
      $pdf->setfont('arial','',6);
      $posicao_exceto_intra = $pdf->getY();

    }
  }
  // -----------------------------------------------------
  // valores de inscriçao
  //$valor_nao_processado = $e91_vlremp-$e91_vlranu-$e91_vlrliq;

  $a_pagar_processado     = ($valor_processado+$inscricao_ant) - $canc_proc - $vlrpag;
  $a_pagar_nao_processado = $vlrpagnproc;#($valor_nao_processado_ant+$valor_nao_processado) - $canc_nproc;

  // -----------------------------------------------------

  $pago_nao_processado = 0;
  if($a_pagar_processado < 0 && $Param_NotaLiquidacao == ""){

    $a_pagar_processado *= -1;
    $pago_nao_processado = $a_pagar_processado ;
    $vlrpag = $vlrpag - $a_pagar_processado;
    $a_pagar_processado = 0;

  }

  $valor_novo =  ($valor_nao_processado_ant+$valor_nao_processado)-$canc_nproc-$pago_nao_processado;

  if( ($valor_novo) < 0 ){
     /*
      * Retirado a soma do valor cancelado pois estava duplicando os valores causando inconsistência com o relatório de Execução de Restos à pagar
      */
    //$canc_proc = $canc_proc + (($valor_novo) * -1 );
    $a_pagar_processado = ($a_pagar_processado - (($valor_novo) * -1 ));

    //$canc_nproc = ( $canc_nproc - (($valor_novo) * -1 ));
    $valor_novo = 0;

  }

  if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro

    $pdf->setfont('arial','',6);

   // $pdf->setY($posicao);

    $pdf->cell(62,$alt,'  '.$o58_orgao .'-'.substr($o40_descr,0,40), "R", 0, "L", 0);
    $pdf->cell(18, $alt,db_formatar($inscricao_ant,'f'),"LR",0,"R",0);      // processados

  }

  if (!isset($arqinclude)){ // se este arquivo no esta incluido por outro
    $pdf->cell(18, $alt,db_formatar($valor_processado,'f'),"R",0,"R",0);    // o cancelamento sempre ocorre com os no liquidados     // porque sempre ocorre o estorno de liquidao para depois o estorno de rp
  }

  $pago_nao_processado = 0;
  if($a_pagar_processado < 0 && $Param_NotaLiquidacao == ""){
    $a_pagar_processado *= -1;
    $pago_nao_processado = $a_pagar_processado ;
    $vlrpag = $vlrpag - $a_pagar_processado;
    $a_pagar_processado = 0;
  }
  $pago_nao_processado = $vlrpagnproc;
  $valor_novo =  $valor_nao_processado-$canc_nproc-$pago_nao_processado;

  if( ($valor_novo) < 0 ){
     /*
      * Retirado a soma do valor cancelado pois estava duplicando os valores causando inconsistência com o relatório de Execução de Restos à pagar
      */
    //$canc_proc = $canc_proc + (($valor_novo) * -1 );
    $a_pagar_processado = ($a_pagar_processado - (($valor_novo) * -1 ));

    //$canc_nproc = ( $canc_nproc - (($valor_novo) * -1 ));
    $valor_novo = 0;

  }

  if (!isset($arqinclude)) { // se este arquivo no esta incluido por outro
    $pdf->cell(18, $alt,db_formatar(abs($vlrpag),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($canc_proc),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($a_pagar_processado),'f'),"R",0,"R",0);
    // no processados
    $pdf->cell(18, $alt,db_formatar(abs($valor_nao_processado_ant),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($valor_nao_processado),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($vlrliq),'f'),"LR",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($pago_nao_processado),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($canc_nproc),'f'),"R",0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($valor_novo),'f'),"0", 0,"R",0);
    $pdf->cell(18, $alt,db_formatar(abs($valor_novo) + abs($a_pagar_processado),'f'), "L",1,"R",0);
    $pdf->setfont('arial','',6);
  }
  $i++;

  if (!isset($arqinclude)){
   // se este arquivo no esta incluido por outro
    $tot_01 += $inscricao_ant;
    $tot_02 += $valor_processado ;
    $tot_03 += $canc_proc ;
    $tot_04 += $vlrpag ;
    $tot_05 += $a_pagar_processado ;
    // totais no processados
    $tot_06 += $valor_nao_processado;
    $tot_07 += $canc_nproc;
    $tot_08 += $vlrpagnproc;
    $tot_09 += $valor_nao_processado-$canc_nproc-$vlrpagnproc;
    $tot_10 += $valor_nao_processado_ant;
    $tot_11 += $vlrliq;
  }
}

foreach ($aInstituicoes as $tipoInstituicao => $aInstituicao) {

  foreach ($aInstituicao as $instit) {
    // Usado no simplificado dos RESTOS A PAGAR /////////////////////////////////////////////////////////////////////////////////
    if ($tipoInstituicao == 1 || $tipoInstituicao != 2) {    // Totais do PODER EXECUTIVO e RPPS
      $tot_restos_pc_insc_ant_exec      += abs($aTotInstitIntra[$instit][0]);
      $tot_restos_pc_inscritos_exec     += abs($aTotInstitIntra[$instit][1]);
      $tot_restos_pc_cancelados_exec    += abs($aTotInstitIntra[$instit][2]);
      $tot_restos_pc_pagos_exec         += abs($aTotInstitIntra[$instit][3]);
      $tot_restos_pc_saldo_exec         += abs($aTotInstitIntra[$instit][4]);

      $tot_restos_naopc_insc_ant_exec   += abs($aTotInstitIntra[$instit][5]);
      $tot_restos_naopc_inscritos_exec  += abs($aTotInstitIntra[$instit][6]);
      $tot_restos_naopc_cancelados_exec += abs($aTotInstitIntra[$instit][7]);
      $tot_restos_naopc_pagos_exec      += abs($aTotInstitIntra[$instit][8]);
      $tot_restos_naopc_saldo_exec      += abs($aTotInstitIntra[$instit][9]);

    }

    if ($tipoInstituicao == 2) {    // Totais do PODER LEGISLATIVO
      $tot_restos_pc_insc_ant_legal      += abs($aTotInstitIntra[$instit][0]);
      $tot_restos_pc_inscritos_legal     += abs($aTotInstitIntra[$instit][1]);
      $tot_restos_pc_cancelados_legal    += abs($aTotInstitIntra[$instit][2]);
      $tot_restos_pc_pagos_legal         += abs($aTotInstitIntra[$instit][3]);
      $tot_restos_pc_saldo_legal         += abs($aTotInstitIntra[$instit][4]);

      $tot_restos_naopc_insc_ant_legal   += abs($aTotInstitIntra[$instit][5]);
      $tot_restos_naopc_inscritos_legal  += abs($aTotInstitIntra[$instit][6]);
      $tot_restos_naopc_cancelados_legal += abs($aTotInstitIntra[$instit][7]);
      $tot_restos_naopc_pagos_legal      += abs($aTotInstitIntra[$instit][8]);
      $tot_restos_naopc_saldo_legal      += abs($aTotInstitIntra[$instit][9]);
    }

  }

}

/**
 * Se o total de "TOTAL (III) = (I + II)" é igual ao somatório de:
 *   RESTOS A PAGAR (EXCETO INTRA-ORÇAMENTÁRIOS)(I) + RESTOS A PAGAR (INTRA-ORÇAMENTÁRIOS)(II)
 *   somei as duas colunas ao invés de ficar somando dentor do laço
 */
if (!isset($arqinclude)) { // se este arquivo no esta incluido por outro

  $pdf->cell(62,$alt,'RESTOS A PAGAR (INTRA-ORÇAMENTÁRIOS)(II)', "B", 0, "L", 0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnterior),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraInscricaoAnteriorProcessado),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraPago),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraCancelado),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraApagar),'f'),"BLR",0,"R",0);
// no processados
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPanterior),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPDez),'f'),"LRB",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotalIntraValorLiquidado),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPPagos),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPCancelados),'f'),"BLR",0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar),'f'),"BL", 0,"R",0);
  $pdf->cell(18,$alt,db_formatar(abs($nTotintraNPAPAgar) + abs($nTotintraApagar),'f'),"BL",1,"R",0);
  $pdf->setfont('arial','',6);

   if ($pdf->pageNo() > 1){
     $pdf->cell(280,$alt,"(".$pdf->pageNo()."/{nb})",0,1,"R");
  }

  $oRelatorio  = new relatorioContabil(97, false);
  $oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo, 185);
  $pdf->ln(17);
  assinaturas($pdf,$classinatura,'LRF');

  $pdf->Output();

}

function writeHeader(&$pdf, $alt) {

  $pdf->setfont('arial','',5);
  $pdf->cell(62,$alt,"", "T", 0, "C",0);
  $pdf->cell(90,$alt,"RESTOS A PAGAR PROCESSADOS E NÃO PROCESSADOS LIQUIDADOS EM EXERCÍCIOS ANTERIORES","LRTB",0,"C",0);
  $pdf->cell(126,$alt,"RESTOS A PAGAR NÃO PROCESSADOS","TB",1,"C",0);
  $pdf->setfont('arial','', 6);
  $pdf->cell(62,$alt,"", "0",0,"C",0);
  $pdf->cell(36,$alt,"Inscritos","TBLR",0,"C",0);
  $pdf->cell(18, $alt,"","LR",0,"C",0);
  $pdf->cell(18, $alt,"","LR",0,"C",0);
  $pdf->cell(18, $alt,"","LR",0,"C",0);
  $pdf->cell(36,$alt,"Inscritos","TBLR",0,"C",0);
  $pdf->cell(18, $alt,"", "LR",0,"C",0);
  $pdf->cell(18, $alt,"", "LR",0,"C",0);
  $pdf->cell(18, $alt,"", "LR",0,"C",0);
  $pdf->cell(18, $alt,"", "TRL",0,"C",0);
  $pdf->cell(18, $alt,"", "TL",1,"C",0);

  $pdf->cell(62,$alt,"PODER/ÓRGÃO","",0,"C",0);
  $pdf->cell(18 ,$alt,"Em","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"Em 31 de","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"Em","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"Em 31 de","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"","LR",0,"C",0);
  $pdf->cell(18 ,$alt,"Saldo","L",1,"C",0);

  $pdf->cell(62, $alt,"",0,0,"C",0);
  $pdf->cell(18, $alt,"Exercícios","LR",0,"C",0);
  $pdf->cell(18, $alt,"dezembro de","LR",0,"C",0);
  $pdf->cell(18, $alt,"Pagos","LR",0,"C",0);
  $pdf->cell(18, $alt,"Cancelados","LR",0,"C",0);
  $pdf->cell(18, $alt,"Saldo","LR",0,"C",0);
  $pdf->cell(18, $alt,"Exercícios","LR",0,"C",0);
  $pdf->cell(18, $alt,"dezembro de","LR",0,"C",0);
  $pdf->cell(18, $alt,"Liquidados","LR",0,"C",0);
  $pdf->cell(18, $alt,"Pagos","LR",0,"C",0);
  $pdf->cell(18, $alt,"Cancelados","LR",0,"C",0);
  $pdf->cell(18, $alt,"Saldo","LR", 0,"C",0);
  $pdf->cell(18, $alt,"Total","L", 1,"C",0);

  $pdf->cell(62, $alt,"","B",0,"C",0);
  $pdf->cell(18, $alt,"Anteriores","LRB",0,"C",0);
  $pdf->cell(18, $alt,db_getsession("DB_anousu") - 1,"LRB",0,"C",0);
  $pdf->cell(18, $alt,"","LRB",0,"C",0);
  $pdf->cell(18, $alt,"","LRB",0,"C",0);
  $pdf->cell(18, $alt,"(a)","LRB",0,"C",0);
  $pdf->cell(18, $alt,"Anteriores","LRB",0,"C",0);
  $pdf->cell(18, $alt,db_getsession("DB_anousu") - 1,"LRB",0,"C",0);
  $pdf->cell(18, $alt,"","LRB",0,"C",0);
  $pdf->cell(18, $alt,"","LRB",0,"C",0);
  $pdf->cell(18, $alt,"","LRB",0,"C",0);
  $pdf->cell(18, $alt,"(b)","LB",0,"C",0);
  $pdf->cell(18, $alt,"(a + b)","LB",1,"C",0);

}
?>