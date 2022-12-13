<?php
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_retencaoreceitas_classe.php"));

$oJson       = new Services_JSON();
$oParametros = $oJson->decode(str_replace("\\","",$_GET["sFiltros"]));

$sWhere = "e60_instit = ".db_getsession("DB_instit");
if ($oParametros->iPagamento == 'p') {
  $sHeaderTipo = "Pagamento";
  $sWhere .= " and corrente.k12_estorn is false ";
} else {
  $sHeaderTipo = "Liquidacao";
}

if ($oParametros->datainicial != "" && $oParametros->datafinal == "") {

   $dataInicial  = implode("-", array_reverse(explode("/", $oParametros->datainicial)));
   if ($oParametros->iPagamento == 'l') {
     $sWhere      .= " and retencao.e23_dtcalculo = '{$dataInicial}'";
   } else {
    $sWhere      .= " and corrente.k12_data = '{$dataInicial}'";
   }
   $sHeaderData  = "{$oParametros->datainicial} a {$oParametros->datainicial}";

} else if ($oParametros->datainicial != "" && $oParametros->datafinal != "") {

  $dataInicial = implode("-", array_reverse(explode("/", $oParametros->datainicial)));
  $dataFinal   = implode("-", array_reverse(explode("/", $oParametros->datafinal)));
  if ($oParametros->iPagamento == 'l') {
    $sWhere     .= "and retencao.e23_dtcalculo between '{$dataInicial}' and '{$dataFinal}'";
  } else {
   $sWhere     .= "and corrente.k12_data between '{$dataInicial}' and '{$dataFinal}'";
  }
  $sHeaderData  = "{$oParametros->datainicial} a {$oParametros->datafinal}";

}

if ($oParametros->iOrdemIni != "" && $oParametros->iOrdemFim == ""){
   $sWhere      .= " and e50_codord = '{$oParametros->iOrdemIni}'";
} else if ($oParametros->iOrdemIni != "" && $oParametros->iOrdemFim != "") {
  $sWhere     .= "and e50_codord between '{$oParametros->iOrdemIni}' and '{$oParametros->iOrdemFim}'";
}

if ($oParametros->sRecursos != "") {
  $sWhere .= " and o58_codigo in ({$oParametros->sRecursos})";
}

if ($oParametros->sCredores != "") {
  $sWhere .= " and e60_numcgm in ({$oParametros->sCredores})";
}

if ($oParametros->sRetencoes != "") {
  $sWhere .= " and e23_retencaotiporec in ({$oParametros->sRetencoes})";
}

if ($oParametros->sContas != "") {
   $sWhere .= " and corrente.k12_conta in({$oParametros->sContas})";
}

if ($oParametros->iPagamento == "p") {
  $sWhere .= " and e23_recolhido is true ";
}

$sHeaderOps  = "Todas";
if ($oParametros->sOps == "p") {
  $sHeaderOps = "Pagas";
  $sWhere .= " and e23_recolhido is true ";
} else if ($oParametros->sOps == "np") {
  $sHeaderOps = "Nao Pagas";
  $sWhere .= " and e23_recolhido is false ";
}

$sSqlRetencoes  = "select e23_sequencial,     ";
$sSqlRetencoes .= "       corrente.k12_data,  ";
$sSqlRetencoes .= "       case when corrente.k12_conta is null then 0  else corrente.k12_conta end as k12_conta,";
$sSqlRetencoes .= "       e21_sequencial,     ";
$sSqlRetencoes .= "       case when c60_descr is null then 'Sem conta'  else c60_descr end as c60_descr,";
$sSqlRetencoes .= "       e50_codord,         ";
$sSqlRetencoes .= "       e60_codemp,         ";
$sSqlRetencoes .= "       e60_anousu,         ";
$sSqlRetencoes .= "       c61_reduz,          ";
$sSqlRetencoes .= "       o58_codigo,         ";
$sSqlRetencoes .= "       case when k02_descr is null then 'Sem Conta' else k02_descr end,         ";
$sSqlRetencoes .= "       k02_codigo,         ";
$sSqlRetencoes .= "       e53_valor,          ";
$sSqlRetencoes .= "       e69_numero,         ";
$sSqlRetencoes .= "       e69_dtnota,         ";
$sSqlRetencoes .= "       case when e49_numcgm is null then cgm.z01_nome else cgmordem.z01_nome end as z01_nome,  ";
$sSqlRetencoes .= "       case when e49_numcgm is null then cgm.z01_numcgm else cgmordem.z01_numcgm end as z01_numcgm,  ";
$sSqlRetencoes .= "       case when e49_numcgm is null then cgm.z01_cgccpf else cgmordem.z01_cgccpf end as z01_cgccpf,  ";
$sSqlRetencoes .= "       case when e49_numcgm is null then cgm.z01_pis else cgmordem.z01_pis end as z01_pis, ";
$sSqlRetencoes .= "       case when e49_numcgm is null then ( select rh70_estrutural || ' - ' || rh70_descr from protocolo.cgmfisico inner join pessoal.rhcbo on z04_rhcbo = rh70_sequencial where z04_numcgm = cgm.z01_numcgm order by z04_sequencial limit 1 ) else ( select rh70_estrutural || ' - ' || rh70_descr from protocolo.cgmfisico inner join pessoal.rhcbo on z04_rhcbo = rh70_sequencial where z04_numcgm = cgmordem.z01_numcgm order by z04_sequencial limit 1 ) end as z01_cbo, ";
$sSqlRetencoes .= "       (select * from (SELECT k17_codigo  ";
$sSqlRetencoes .= "          from slip        ";
$sSqlRetencoes .= "               inner join slipempagemovslips on k17_codigo          = k108_slip        ";
$sSqlRetencoes .= "               inner join empagemovslips     on k108_empagemovslips = k107_sequencial  ";
$sSqlRetencoes .= "               inner join retencaoempagemov  on e27_empagemov       = k107_empagemov   ";
$sSqlRetencoes .= "               inner join retencaoreceitas   on e27_retencaoreceitas = retencaoreceitas.e23_sequencial  ";
$sSqlRetencoes .= "               inner join retencaotiporec    on e21_sequencial  = retencaoreceitas.e23_retencaotiporec  ";
$sSqlRetencoes .= "               inner join tabrec             on e21_receita     = k02_codigo           ";
$sSqlRetencoes .= "               left  join saltesextra        on k17_credito     =  k109_saltes  ";
$sSqlRetencoes .= "         where e27_retencaoreceitas = retencao.e23_sequencial ";
$sSqlRetencoes .= "           and (case when k109_contaextra is null then true";
$sSqlRetencoes .= "                     when k109_contaextra is not null and k02_tipo != 'O' then false  else true end )";
$sSqlRetencoes .= "         group by k107_empagemov,k17_codigo ";
$sSqlRetencoes .= "        having sum(k107_valor) > 0 ) as x limit 1)  as k17_codigo,";
$sSqlRetencoes .= "       e23_valorretencao   ";
$sSqlRetencoes .= " from  retencaoreceitas retencao   ";
$sSqlRetencoes .= "       inner join retencaopagordem on e20_sequencial = e23_retencaopagordem ";
$sSqlRetencoes .= "       inner join pagordem         on e50_codord     = e20_pagordem         ";
$sSqlRetencoes .= "       left join pagordemconta    on e49_codord     = e20_pagordem         ";
$sSqlRetencoes .= "       inner join pagordemele      on e50_codord     = e53_codord           ";
$sSqlRetencoes .= "       inner join empempenho       on e60_numemp     = e50_numemp           ";
$sSqlRetencoes .= "       inner join orcdotacao       on e60_coddot     = o58_coddot           ";
$sSqlRetencoes .= "                                  and e60_anousu     = o58_anousu           ";
$sSqlRetencoes .= "       inner join cgm              on e60_numcgm     = cgm.z01_numcgm       ";
$sSqlRetencoes .= "       left join cgm  cgmordem          on e49_numcgm     = cgmordem.z01_numcgm  ";
$sSqlRetencoes .= "       inner join pagordemnota     on e71_codord     = e50_codord           ";
$sSqlRetencoes .= "                                  and e71_anulado is false                  ";
$sSqlRetencoes .= "       inner join empnota          on e71_codnota    = e69_codnota          ";
$sSqlRetencoes .= "       inner join retencaotiporec  on e21_sequencial = e23_retencaotiporec  ";
$sSqlRetencoes .= "       inner join tabrec           on e21_receita    = k02_codigo           ";
$sSqlRetencoes .= "       left join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial       ";
$sSqlRetencoes .= "       left join corgrupocorrente         on k105_sequencial     = e47_corgrupocorrente ";
$sSqlRetencoes .= "       left join corrente                 on k105_id             = corrente.k12_id      ";
$sSqlRetencoes .= "                                          and k105_autent         = corrente.k12_autent  ";
$sSqlRetencoes .= "                                          and k105_data           = corrente.k12_data    ";
$sSqlRetencoes .= "       left join conplanoreduz            on corrente.k12_conta  = c61_reduz            ";
$sSqlRetencoes .= "                                          and c61_anousu          = ".db_getsession("DB_anousu");
$sSqlRetencoes .= "       left join conplano                 on c60_codcon          = c61_codcon           ";
$sSqlRetencoes .= "                                          and c60_anousu          = c61_anousu           ";
$sSqlRetencoes .= " where e23_ativo is true ";
$sSqlRetencoes .= "   and {$sWhere}              ";

$sValorCompararQuebra = "";
$sCampoQuebrar        = "";
$sNomeQuebra          = "";
$sHeaderQuebra        = "Nenhum";
if ($oParametros->order == 1) {

  $sCampoOrdenar = "e20_sequencial";
  $sHeaderOrdem  = "Númerico";

} else {

  $sCampoOrdenar = "e20_descricao";
  $sHeaderOrdem  = "Descrição";
}
if ($oParametros->group == 2) {

  $sCampoQuebrar = "k12_conta";
  $sNomeQuebra   = "c60_descr";
  $sHeaderQuebra = "Conta";

  if ($oParametros->order == 1) {
    $sCampoOrdenar = "k12_conta";
  } else {
    $sCampoOrdenar = "c60_descr";
  }

} else if ($oParametros->group == 3) {

  //Quebra por credor
  $sCampoQuebrar = "z01_numcgm";
  $sNomeQuebra   = "z01_nome";
  $sHeaderQuebra = "Credor";
  if ($oParametros->order == 1) {
    $sCampoOrdenar = "z01_numcgm";
  } else {
    $sCampoOrdenar = "z01_nome";
  }

}

$sSqlRetencoes   .= " order by {$sCampoOrdenar}";
$rsRetencoes      = db_query($sSqlRetencoes);
$iTotalRetencoes  = pg_num_rows($rsRetencoes);
if ($iTotalRetencoes == 0 || !$rsRetencoes) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Não foram encontradas retenções.");
}

$aRetencoes          = array();
$iTotalRetencoes     = pg_num_rows($rsRetencoes);

for ($i = 0; $i < $iTotalRetencoes; $i++) {

   $oRetencao = db_utils::fieldsMemory($rsRetencoes, $i);

   if ( strlen(trim($oRetencao->z01_cgccpf)) == 11 ) {
     $cCnpjCpf = db_formatar($oRetencao->z01_cgccpf,"cpf");
   } elseif ( strlen(trim($oRetencao->z01_cgccpf)) == 14 ) {
     $cCnpjCpf = db_formatar($oRetencao->z01_cgccpf,"cnpj");
   } else {
     $cCnpjCpf = $oRetencao->z01_cgccpf;
   }
   $cPis = $oRetencao->z01_pis;
   $cCbo = $oRetencao->z01_cbo;

   if ($oParametros->group != 1) {

     if ($sValorCompararQuebra == $oRetencao->$sCampoQuebrar) {

       $aRetencoes[$oRetencao->$sCampoQuebrar]->total    += $oRetencao->e23_valorretencao;
       $aRetencoes[$oRetencao->$sCampoQuebrar]->total_op += $oRetencao->e53_valor;
       $aRetencoes[$oRetencao->$sCampoQuebrar]->itens[]   = $oRetencao;

     } else {

       if ($oParametros->group == 3) {
         $aRetencoes[$oRetencao->$sCampoQuebrar]->texto    = $oRetencao->$sCampoQuebrar." - ".$oRetencao->$sNomeQuebra . " - CPF/CNPJ: $cCnpjCpf - PIS: $cPis " . (strlen(trim($cCbo)) == 0?"":" - CBO: $cCbo");
       } else {
         $aRetencoes[$oRetencao->$sCampoQuebrar]->texto    = $oRetencao->$sCampoQuebrar." - ".$oRetencao->$sNomeQuebra;
       }

       $aRetencoes[$oRetencao->$sCampoQuebrar]->total    = $oRetencao->e23_valorretencao;
       $aRetencoes[$oRetencao->$sCampoQuebrar]->total_op = $oRetencao->e53_valor;
       $aRetencoes[$oRetencao->$sCampoQuebrar]->itens[]  = $oRetencao;

     }
     $sValorCompararQuebra = $oRetencao->$sCampoQuebrar;

   } else {

     $aRetencoes[0]->texto    = "";
     if (isset($aRetencoes[0]->total)) {

       $aRetencoes[0]->total    += $oRetencao->e23_valorretencao;
       $aRetencoes[0]->total_op += $oRetencao->e53_valor;
     } else {

       $aRetencoes[0]->total    = $oRetencao->e23_valorretencao;
       $aRetencoes[0]->total_op = $oRetencao->e53_valor;
     }
     $aRetencoes[0]->itens[]  = $oRetencao;
   }
}

$oPdf  = new PDF("L","mm","A4");
$oPdf->Open();
$oPdf->SetAutoPageBreak(false);
$oPdf->AliasNbPages();
$oPdf->SetFillColor(240);

$head2           = "Relatório de Retenções";
$head3           = "Data  : {$sHeaderData}";
$head4           = "Quebra: {$sHeaderQuebra}";
$head5           = "Ordem : {$sHeaderOrdem}";
$head6           = "Tipo : {$sHeaderTipo}";
$head7           = "OPs : {$sHeaderOps}";

$sFonte          = "Arial";
$lEscreverHeader = true;
$lAddPage        = false;
$nTamanhoTotalCelulas = 255;
$nTotalRetencoes = 0;
$nTotalOrdemPagamento = 0;

$oPdf->AddPage();

$iTamCell  = 0;
$iTamFonte = 5;
if ($oParametros->group == 2) {

  $iTamCell  = (39/5);
  $iTamFonte = 6;
} else if ($oParametros->group == 3) {

  $iTamCell  = (56/5);
  $iTamFonte = 6;
}

foreach ($aRetencoes as $oQuebra) {

  $oPdf->SetFont($sFonte, "b",$iTamFonte+2);
  $lEscreverHeader = true;
  foreach ($oQuebra->itens as $oRetencaoAtiva) {

    if ($oPdf->Gety() > $oPdf->h - 27 || $lEscreverHeader) {

      if ($oPdf->Gety() > $oPdf->h - 27) {
        $oPdf->AddPage();
      }

      if ($oQuebra->texto != "") {
        $oPdf->cell(0,5, $oQuebra->texto,0,1);
      }

      $oPdf->SetFont($sFonte, "b",$iTamFonte+1);
      $oPdf->cell(10+$iTamCell,5,"Retenção",1,0,"C",1);

      if ($oParametros->group != 2) {
        $oPdf->cell(39,5,"Conta",1,0,"C",1);
      }

      $oPdf->cell(10+$iTamCell,5,"OP",1,0,"C",1);
      $oPdf->cell(15+$iTamCell,5,"Empenho",1,0,"C",1);
      $oPdf->cell(12,5,"Recurso",1,0,"C",1);
      $oPdf->cell(23+$iTamCell,5,"Receita",1,0,"C",1);
      $oPdf->cell(15+$iTamCell,5,"Valor OP",1,0,"C",1);
      $oPdf->cell(18,5,"Nota",1,0,"C",1);
      $oPdf->cell(20,5,"Data Nota",1,0,"C",1);
      $oPdf->cell(20,5,"Data Rec.",1,0,"C",1);

      if ($oParametros->group != 3) {
        $oPdf->cell(56,5,"Credor",1,0,"C",1);
      }

      $oPdf->cell(17,5,"Slip",1,0,"C",1);
      $oPdf->cell(25,5,"Valor Ret.",1,1,"C",1);

      $lEscreverHeader = false;

    }

    $oPdf->SetFont($sFonte, "",$iTamFonte);
    $oPdf->cell(10+$iTamCell,5,$oRetencaoAtiva->e21_sequencial,"TBR",0,"R",0);

    if ($oParametros->group != 2) {
      $oPdf->cell(39,5,substr("{$oRetencaoAtiva->k12_conta} - {$oRetencaoAtiva->c60_descr}",0,30),"TBR",0,"L");
    }

    $oPdf->cell(10+$iTamCell,5,$oRetencaoAtiva->e50_codord,"TBR",0,"R");
    $oPdf->cell(15+$iTamCell,5,"{$oRetencaoAtiva->e60_codemp}/{$oRetencaoAtiva->e60_anousu}","TBR",0,"R");
    $oPdf->cell(12,5,$oRetencaoAtiva->o58_codigo,"TBR",0,"R");
    $oPdf->cell(23+$iTamCell,5,substr("{$oRetencaoAtiva->k02_codigo} - {$oRetencaoAtiva->k02_descr}", 0, 20),"TBR",0,"L");
    $oPdf->cell(15+$iTamCell,5,db_formatar($oRetencaoAtiva->e53_valor,"f"), "TBR", 0, "R");
    $oPdf->cell(18,5,substr($oRetencaoAtiva->e69_numero,0,14), "TBR",0,"R");
    $oPdf->cell(20,5, db_formatar($oRetencaoAtiva->e69_dtnota,"d"), "TBR", 0, "C");
    $oPdf->cell(20,5, db_formatar($oRetencaoAtiva->k12_data,"d"), "TBR", 0, "C");

    if ($oParametros->group != 3) {
      $oPdf->cell(56,5,substr("{$oRetencaoAtiva->z01_numcgm} - {$oRetencaoAtiva->z01_nome}", 0, 40),"TBR", 0, "L");
    }

    $oPdf->cell(17,5, $oRetencaoAtiva->k17_codigo, "TBR",0,"R");
    $oPdf->cell(25,5,db_formatar($oRetencaoAtiva->e23_valorretencao,"f"),"TBL",1,"R");
  }

  $oPdf->SetFont($sFonte, "b",$iTamFonte);
  $oPdf->cell(109, 5, 'Total da OP:', "TBR", 0, "R");
  $oPdf->cell(15, 5, db_formatar($oQuebra->total_op, "f"), "TBR", 0, "R");
  $oPdf->cell(131,5,"Total da Retenção:","TBR",0,"R");
  $oPdf->cell(25, 5,db_formatar($oQuebra->total,"f"),"TBL",1,"R");
  $nTotalRetencoes      += $oQuebra->total;
  $nTotalOrdemPagamento += $oQuebra->total_op;

}
$oPdf->SetFont($sFonte, "b",$iTamFonte);
$oPdf->cell(109, 5, 'Total Geral OP:', "TBR", 0, "R");
$oPdf->cell(15, 5, db_formatar($nTotalOrdemPagamento, "f"), "TBR", 0, "R");
$oPdf->cell(131,5,"Total Geral:","TBR",0,"R");
$oPdf->cell(25, 5,db_formatar($nTotalRetencoes,"f"),"TBL",1,"R");
$oPdf->Output();
