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
require_once(modification("fpdf151/assinatura.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification('libs/db_utils.php'));

db_postmemory($_GET);
db_postmemory($HTTP_GET_VARS);

/**
 * Quando o relatório for uma reemissão, busca o código sequencial da tabela concilia.
 */
if (isset($lReemissao) && $lReemissao) {

  $sSqlCodConcilia  = "select k68_sequencial ";
  $sSqlCodConcilia .= "  from concilia";
  $sSqlCodConcilia .= " where k68_contabancaria = {$iConta} ";
  $sSqlCodConcilia .= "   and k68_data = '{$sDataConciliacao}'";

  $rsCodigoConcilia = db_query($sSqlCodConcilia);
  if (pg_num_rows($rsCodigoConcilia) > 0){
    $concilia = db_utils::fieldsMemory($rsCodigoConcilia, 0)->k68_sequencial;
  }
}

$clconcilia             = new cl_concilia();
$clextrato              = new cl_extrato();
$clconciliapendcorrente = new cl_conciliapendcorrente();
$clconciliapendextrato  = new cl_conciliapendextrato();
$clsaltes               = new cl_saltes();
$classinatura           = new cl_assinatura;

db_sel_instit();
// nao esquecer de tirar essas variaveis estao aqui so para nao dar pau no teste
$banco='';
$saldoextrato=0;
//-------------------------------------------------------

$sqlDadosConcilia  = " select * from concilia ";
$sqlDadosConcilia .= "        inner join conciliastatus on k68_conciliastatus = k95_sequencial";
$sqlDadosConcilia .= "  where k68_sequencial = $concilia ";
$rsDadosConcilia   = $clconcilia->sql_record($sqlDadosConcilia);

if($clconcilia->numrows > 0){
  db_fieldsmemory($rsDadosConcilia,0);
}

if ( substr($k68_data,0,4) <= 2012 and false) {

  $sqlTotalExtrato  = " select k97_saldofinal as  saldoextrato         ";
  $sqlTotalExtrato .= "   from extratosaldo                            ";
  $sqlTotalExtrato .= "  where k97_contabancaria  = $k68_contabancaria ";
  $sqlTotalExtrato .= "    and k97_dtsaldofinal  <= '{$k68_data}'      ";
  $sqlTotalExtrato .= "  order by k97_dtsaldofinal desc limit 1        ";

} else {

  $sqlData  = " select k97_dtsaldofinal ";
  $sqlData .= " from extratosaldo a ";
  $sqlData .= " where a.k97_contabancaria = $k68_contabancaria and a.k97_dtsaldofinal <= '{$k68_data}' ";
  $sqlData .= " order by a.k97_dtsaldofinal desc, a.k97_extrato desc limit 1";

  $sqlTotalExtrato  = " select k97_saldofinal as saldoextrato         ";
  $sqlTotalExtrato .= " from extratosaldo                             ";
  $sqlTotalExtrato .= " where k97_contabancaria  = $k68_contabancaria ";
  $sqlTotalExtrato .= " and k97_dtsaldofinal   = ( $sqlData )         ";
  $sqlTotalExtrato .= " order by k97_dtsaldofinal desc, k97_extrato desc limit 1 ";

}
//  die($sqlTotalExtrato);
$rsTotalExtrato   = $clextrato->sql_record($sqlTotalExtrato);

if ($clextrato->numrows > 0) {
  db_fieldsmemory($rsTotalExtrato,0);
}

$sqlDadosConcilia = " select count(*) as quantidade from concilia where k68_contabancaria = (select k68_contabancaria from concilia where k68_sequencial = $concilia)";
$rsDadosConcilia  = $clconcilia->sql_record($sqlDadosConcilia);

if ($clconcilia->numrows > 0) {

  db_fieldsmemory($rsDadosConcilia,0);
  if ($quantidade > 2 && $k95_fechada == 'f' ) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Salve a conciliacao antes de emitir o relatorio.');
  }
}

$sqlDadosConta  = " select distinct ";
$sqlDadosConta .= "        db90_codban     as banco, ";
$sqlDadosConta .= "        db83_sequencial as reduzido,";
$sqlDadosConta .= "        db83_descricao  as descricao,";
$sqlDadosConta .= "        db83_conta||'-'||db83_dvconta as conta,";
$sqlDadosConta .= "        db89_codagencia||'-'||db89_digito as agencia,";
$sqlDadosConta .= "        ( select array_to_string( array_accum(distinct c61_reduz),', ') from conplanocontabancaria inner join conplanoreduz on c61_anousu = c56_anousu and c61_codcon = c56_codcon where c56_contabancaria = {$k68_contabancaria} and c56_anousu = " . db_getsession('DB_anousu') . " ) as reduzido_contabil ";
$sqlDadosConta .= "   from contabancaria ";
$sqlDadosConta .= "        inner join bancoagencia on bancoagencia.db89_sequencial = contabancaria.db83_bancoagencia ";
$sqlDadosConta .= "        inner join db_bancos    on db_bancos.db90_codban        = bancoagencia.db89_db_bancos ";
$sqlDadosConta .= "  where contabancaria.db83_sequencial = {$k68_contabancaria} ";
$rsDadosConta   = db_query($sqlDadosConta);
if($rsDadosConta && pg_num_rows($rsDadosConta) > 0){
  db_fieldsmemory($rsDadosConta,0);
}

$head1 = $nomeinst;
$head2 = "DEMONSTRATIVO DA CONCILIAÇÃO BANCÁRIA ";
$head3 = "PERÍODO ATÉ : ".db_formatar($k68_data,'d');
$data = $k68_data;
if (isset($lReemissao) && $lReemissao) {

  if (isset($datausuario) && $datausuario != "") {
    $head3 = "PERÍODO ATÉ : {$datausuario}";
    $data = $datausuario;
  }
}
if ($analitico == 't'){
  $head5 = "ANALÍTICO";
}else{
  $head5 = "SINTÉTICO";
}

$troca                  = 1;
$total                  = 0;
$totalPendenciasCaixa   = 0;
$totalPendenciasExtrato = 0;
$alt                    = 4;
$fonte                  = 'arial';

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$pdf->setfont($fonte,'b',8);
$pdf->addpage();

// dados da conta bancaria
$pdf->cell(189,$alt,"DADOS DA CONTA BANCÁRIA ",0,1,"L",0);
$pdf->ln(1);

$pdf->setfont($fonte,'b',8);
$pdf->cell(25,$alt,"BANCO : ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->cell(35,$alt,$banco,0,0,"L",0);
$pdf->setfont($fonte,'b',8);
$pdf->cell(25,$alt,"SEQ. CONTA : ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->cell(75,$alt,(isset($reduzido) ? $reduzido : null),0,1,"L",0);

$pdf->setfont($fonte,'b',8);
$pdf->cell(25,$alt,"AGÊNCIA : ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->cell(35,$alt,(isset($agencia) ? $agencia : null),0,0,"L",0);
$pdf->setfont($fonte,'b',8);
$pdf->cell(40,$alt,"REDUZIDO CONTABIL: ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->multicell(60,$alt,(isset($reduzido_contabil) ? $reduzido_contabil : null),0,1,"L",0);

$pdf->setfont($fonte,'b',8);
$pdf->cell(25,$alt,"CONTA : ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->cell(35,$alt,(isset($conta) ? $conta : null),0,0,"L",0);
$pdf->setfont($fonte,'b',8);
$pdf->cell(25,$alt,"DESCRIÇÃO : ",0,0,"L",0);
$pdf->setfont($fonte,'',8);
$pdf->cell(75,$alt,(isset($descricao) ? $descricao : null),0,1,"L",0);

$pdf->ln(1);
$pdf->setfont($fonte,'b',8);
$pdf->cell(189,$alt,"SALDO CONFORME EXTRATO BANCÁRIO : ".db_formatar($saldoextrato,'f'),'BT',1,"R",1);
$pdf->setfont($fonte,'',8);
$pdf->ln(4);

// DADOS DAS PENDENCIAS DO CAIXA
$pdf->setfont($fonte,'b',8);
$pdf->cell(189,$alt,"(-) MOVIMENTOS PENDENTES NA TESOURARIA ",'BT',1,"C",0);

// ESSE SQL TRAS AS PENDENCIAS DO CAIXA
$sqlPendenciascaixa  = "  select max(case                                         ";
$sqlPendenciascaixa .= "           when richeque       is not null            "; // O relatório considere como cheque pendente
$sqlPendenciascaixa .= "            and richeque <> 0                          "; // somente quando o valor estiver a CRÉDITO na tesouraria.
$sqlPendenciascaixa .= "            and rivalorcredito <> 0                    "; //
$sqlPendenciascaixa .= "           then 'cheque'                              "; //
$sqlPendenciascaixa .= "           when rnvalordebito  is not null            "; // Caso o cheque não esteja a CRÉDITO na tesouraria
$sqlPendenciascaixa .= "            and rnvalordebito <> 0                     "; // O registro é considerado como:
$sqlPendenciascaixa .= "             or richeque is not null                  "; // (-) Pendencias contabilizadas a debito
$sqlPendenciascaixa .= "            and richeque <> 0                          "; //
$sqlPendenciascaixa .= "            and rnvalordebito <> 0                     "; //
$sqlPendenciascaixa .= "           then 'debito'                              "; //
$sqlPendenciascaixa .= "           when rivalorcredito is not null            "; //
$sqlPendenciascaixa .= "            and rivalorcredito <> 0                    "; //
$sqlPendenciascaixa .= "           then 'credito'                             "; //
$sqlPendenciascaixa .= "         end) as tipo,                                 ";
$sqlPendenciascaixa .= "         ricaixa,                                     ";
$sqlPendenciascaixa .= "         riautent,                                    ";
$sqlPendenciascaixa .= "         ridata,                                      ";
$sqlPendenciascaixa .= "         (select e60_codemp||'/'||e60_anousu
                                      from empempenho
                                     where e60_numemp = riempenho ) as riempenho,                                   ";
$sqlPendenciascaixa .= "         riordem,                                     ";
$sqlPendenciascaixa .= "         riplanilha,                                  ";
$sqlPendenciascaixa .= "         rislip,                                      ";
$sqlPendenciascaixa .= "         richeque as cheque,                          ";
$sqlPendenciascaixa .= "         max(case                                         ";
$sqlPendenciascaixa .= "           when rnvalordebito  is not null            ";
$sqlPendenciascaixa .= "            and rnvalordebito <> 0                     ";
$sqlPendenciascaixa .= "           then 'D'                                   ";
$sqlPendenciascaixa .= "           else 'C'                                   ";
$sqlPendenciascaixa .= "         end) as tipomov,                              ";
$sqlPendenciascaixa .= "         sum(case                                         ";
$sqlPendenciascaixa .= "           when rnvalordebito  is not null            ";
$sqlPendenciascaixa .= "            and rnvalordebito <> 0                     ";
$sqlPendenciascaixa .= "           then rnvalordebito                         ";
$sqlPendenciascaixa .= "           else rivalorcredito                        ";
$sqlPendenciascaixa .= "         end) as valor,                                 ";
$sqlPendenciascaixa .= "        k89_justificativa                             ";
$sqlPendenciascaixa .= "    from conciliapendcorrente                         ";
$sqlPendenciascaixa .= "         inner join fc_extratocaixa(".db_getsession('DB_instit').",".$k68_contabancaria.",null,null,false ) on ricaixa  = k89_id ";
$sqlPendenciascaixa .= "                                                                                                           and riautent = k89_autent ";
$sqlPendenciascaixa .= "                                                                                                           and ridata   = k89_data ";
$sqlPendenciascaixa .= "   where k89_concilia = $concilia ";
$sqlPendenciascaixa .= "     and not exists (select 1
                                                 from corgrupocorrente
                                                where k105_autent = k89_autent
                                                  and k105_id     = k89_id
                                                  and k105_data   = k89_data
                                                  and k105_corgrupotipo in (2,3,5,6)
                                                  and extract(year from k105_data) <= 2012 )  ";

$sqlPendenciascaixa .= "    group by ricaixa, riautent, ridata, riempenho, riordem, riplanilha,
                                       rislip, richeque,k89_justificativa";
$sqlPendenciascaixa .= "   order by tipo,ridata,ricaixa,riautent ";
$rsConciliaCorrente  = $clconciliapendcorrente->sql_record($sqlPendenciascaixa);
$intNumRows          = $clconciliapendcorrente->numrows;

if ($analitico == 't') {

  $pdf->cell(21,$alt,"DATA"     ,'BT',0,"C",0);
  $pdf->cell(21,$alt,"CAIXA"    ,1,0,"C",0);
  $pdf->cell(21,$alt,"AUTENT"   ,1,0,"C",0);
  $pdf->cell(21,$alt,"EMPENHO"  ,1,0,"C",0);
  $pdf->cell(21,$alt,"ORDEM"    ,1,0,"C",0);
  $pdf->cell(21,$alt,"CHEQUE"   ,1,0,"C",0);
  $pdf->cell(21,$alt,"PLANILHA" ,1,0,"C",0);
  $pdf->cell(21,$alt,"SLIP"     ,1,0,"C",0);
  $pdf->cell(21,$alt,"VALOR"    ,'BT',1,"C",0);
}

$subTotalCaixaCheque  = 0;
$subTotalCaixaDebito  = 0;
$subTotalCaixaCredito = 0;
$subTotalCaixa        = 0;
$tipoant              = '';
$primeira             = true;

for($i = 0; $i < $intNumRows ;$i++){

  db_fieldsmemory($rsConciliaCorrente,$i);

  if ( $tipoant != $tipo && $primeira == false) {

    $pdf->setfont($fonte,'b',8);
    $pdf->cell(189,$alt,"Sub total :".db_formatar($subTotalCaixa,'f'),0,1,"R",0);
    $pdf->setfont($fonte,'',7);
    $subTotalCaixa = 0;
  }

  $primeira = false;
  if ( $tipoant != $tipo ) {

    $tipoant = $tipo;

    if ($tipo == 'cheque') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt,"(+) Cheques emitidos e não apresentados ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    } else if($tipo == 'debito') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt,"(-) Pendências contabilizadas a débito ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    } else if($tipo == 'credito') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt,"(+) Pendências contabilizadas a crédito ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    }
  }

  if ($tipo == 'debito') {
    $valor = ($valor * -1);
  } else {
    $valor = abs($valor);
  }

  if($analitico == 't'){

    if ($pdf->gety() > $pdf->h - 30 ) {

      $pdf->addpage();
      $pdf->setfont($fonte,'b',8);
      $pdf->cell(21,$alt,"DATA"     ,'BT',0,"C",0); //1
      $pdf->cell(21,$alt,"CAIXA"    ,'1' ,0,"C",0); //2
      $pdf->cell(21,$alt,"AUTENT"   ,'1' ,0,"C",0); //3
      $pdf->cell(21,$alt,"EMPENHO"  ,'1' ,0,"C",0); //4
      $pdf->cell(21,$alt,"ORDEM"    ,'1' ,0,"C",0); //5
      $pdf->cell(21,$alt,"CHEQUE"   ,'1' ,0,"C",0); //6
      $pdf->cell(21,$alt,"PLANILHA" ,'1' ,0,"C",0); //7
      $pdf->cell(21,$alt,"SLIP"     ,'1' ,0,"C",0); //8
      $pdf->cell(21,$alt,"VALOR"    ,'BT',1,"C",0); //9
    }

    $pdf->setfont($fonte,'',7);
    $pdf->cell(21,$alt,db_formatar($ridata,'d')        ,0,0,"C",0);
    $pdf->cell(21,$alt,$ricaixa                        ,0,0,"C",0);
    $pdf->cell(21,$alt,$riautent                       ,0,0,"C",0);
    $pdf->cell(21,$alt,($riempenho==0?'':$riempenho)   ,0,0,"C",0);//4
    $pdf->cell(21,$alt,($riordem  ==0?'':$riordem)     ,0,0,"C",0);//5
    $pdf->cell(21,$alt,$cheque                         ,0,0,"C",0);
    $pdf->cell(21,$alt,($riplanilha==0?'':$riplanilha) ,0,0,"C",0);//7
    $pdf->cell(21,$alt,($rislip    ==0?'':$rislip)     ,0,0,"C",0);//8
    $pdf->cell(21,$alt,db_formatar($valor,'f')         ,0,1,"R",0);

    /**
     * Imprime a justificativa
     */
    if (!empty($k89_justificativa) && (isset($justificativa) && $justificativa == 1)) {
      $pdf->setfont($fonte,'',6);
      $pdf->MultiCell(190, $alt, str_replace("\n", " ", $k89_justificativa));
      $pdf->cell(190, 0, '', 0, 1);
    }
  }

  $subTotalCaixa        += $valor;
  $totalPendenciasCaixa += $valor;
}

$pdf->setfont($fonte,'b',8);
$pdf->cell(189,$alt,"Sub total :".db_formatar($subTotalCaixa,'f'),0,1,"R",0);
$pdf->setfont($fonte,'',7);
$subTotalCaixa = 0;

$pdf->ln(2);
$pdf->setfont($fonte,'b',8);
$pdf->cell(189,$alt,"RESULTADO DOS MOVIMENTOS : ".db_formatar($totalPendenciasCaixa,'f'),'BT',1,"R",0);
$pdf->ln(4);

// DADOS DAS PENDENCIAS DO EXTRATO

$pdf->cell(189,$alt,"(+) MOVIMENTOS PENDENTES NO EXTRATO BANCÁRIO ",'BT',1,"C",0);

// ESSE SQL TRAS AS PENDENCIAS DO EXTRATO
$sqlPendenciasExtrato  = "  select k86_data, ";
$sqlPendenciasExtrato .= "         k86_documento, ";
$sqlPendenciasExtrato .= "         k86_historico, ";
$sqlPendenciasExtrato .= "         k86_valor, ";
$sqlPendenciasExtrato .= "         k86_tipo, ";
$sqlPendenciasExtrato .= "         case ";
$sqlPendenciasExtrato .= "           when k85_tipoinclusao = 2 then 'outros'";
$sqlPendenciasExtrato .= "           when k86_tipo = 'D' then 'debito' ";
$sqlPendenciasExtrato .= "           when k86_tipo = 'C' then 'credito' ";
$sqlPendenciasExtrato .= "         end as tipo, ";
$sqlPendenciasExtrato .= "         k88_justificativa ";
$sqlPendenciasExtrato .= "    from conciliapendextrato ";
$sqlPendenciasExtrato .= "         inner join extratolinha on k86_sequencial = k88_extratolinha ";
$sqlPendenciasExtrato .= "         inner join extrato      on k85_sequencial = k86_extrato ";
$sqlPendenciasExtrato .= "   where k88_concilia = $concilia order by tipo,k86_data ";
$rsConciliaExtrato     = $clconciliapendextrato->sql_record($sqlPendenciasExtrato);
$intNumRows            = $clconciliapendextrato->numrows;

if ($analitico == 't') {

  $pdf->setfont($fonte,'b',8);
  $pdf->cell(21, $alt,"DATA"      ,'BT',0,"C",0);
  $pdf->cell(40, $alt,"DOCUMENTO" ,1,0,"C",0);
  $pdf->cell(107,$alt,"HISTÓRICO" ,1,0,"C",0);
  $pdf->cell(21, $alt,"VALOR"     ,'BT',1,"C",0);
}

$tipoant         = '';
$subTotalExtrato = 0;
$primeira        = true;

for ($i = 0; $i < $intNumRows ;$i++) {

  db_fieldsmemory($rsConciliaExtrato,$i);
  if ( $tipoant != $tipo && $primeira == false) {

    $pdf->setfont($fonte,'b',8);
    $pdf->cell(189,$alt,"Sub total :".db_formatar($subTotalExtrato,'f'),0,1,"R",0);
    $pdf->setfont($fonte,'',7);
    $subTotalExtrato = 0;

  }

  $primeira = false;
  if ( $tipoant != $tipo ) {

    $tipoant = $tipo;

    if ($tipo == 'debito') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt,"(+) Débitos em C/C não contabilizados  ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    } else if($tipo == 'credito') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt,"(-) Créditos em C/C não contabilizados  ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    } else if($tipo == 'outros') {

      $pdf->setfont($fonte,'b',8);
      $pdf->cell(189,$alt," Outros valores ",0,1,"L",0);
      $pdf->setfont($fonte,'',7);
    }
  }

  if($k86_tipo == 'C'){
    $k86_valor = ($k86_valor * -1);
  }

  if ($analitico == 't') {

    if ($pdf->gety() > $pdf->h - 30 ) {

      $pdf->addpage();
      $pdf->setfont($fonte,'b',8);
      $pdf->cell(21, $alt,"DATA"      ,'BT',0,"C",0);
      $pdf->cell(40, $alt,"DOCUMENTO" ,1,0,"C",0);
      $pdf->cell(107,$alt,"HISTÓRICO" ,1,0,"C",0);
      $pdf->cell(21, $alt,"VALOR"     ,'BT',1,"C",0);
    }

    $pdf->setfont($fonte,'',7);
    $pdf->cell(21, $alt,db_formatar($k86_data,'d') ,0,0,"C",0);
    $pdf->cell(40, $alt,$k86_documento             ,0,0,"C",0);
    $pdf->cell(107,$alt,$k86_historico             ,0,0,"L",0);
    $pdf->cell(21, $alt,db_formatar($k86_valor,'f'),0,1,"R",0);

    /**
     * Imprime a justificativa
     */
    if (!empty($k88_justificativa) && (isset($justificativa) && $justificativa == 1)) {
      $pdf->setfont($fonte,'',6);
      $pdf->MultiCell(190, $alt, str_replace("\n", " ", $k88_justificativa), 0);
      $pdf->cell(190, 0, '', 0, 1);
    }
  }
  $subTotalExtrato += $k86_valor;
  $totalPendenciasExtrato += $k86_valor;
}

$pdf->setfont($fonte,'b',8);
$pdf->cell(189,$alt,"Sub total :".db_formatar($subTotalExtrato,'f'),0,1,"R",0);
$pdf->setfont($fonte,'',7);
$subTotalExtrato = 0;

$pdf->ln(2);
$pdf->setfont('arial','b',8);
$pdf->cell(189,$alt,"RESULTADO DOS MOVIMENTOS : ".db_formatar($totalPendenciasExtrato,'f'),'BT',1,"R",0);

$pdf->ln(4);
$pdf->setfont('arial','b',8);

$nTotContaCaixa = 0;

$sSqlReduz  = " select distinct c61_reduz ";
$sSqlReduz .= "   from contabancaria ";
$sSqlReduz .= "        inner join conplanocontabancaria on conplanocontabancaria.c56_contabancaria = contabancaria.db83_sequencial ";
$sSqlReduz .= "        inner join conplanoreduz         on conplanoreduz.c61_codcon = conplanocontabancaria.c56_codcon ";
$sSqlReduz .= "                                        and conplanoreduz.c61_anousu = conplanocontabancaria.c56_anousu ";
$sSqlReduz .= "                                        and conplanoreduz.c61_anousu = ".db_getsession('DB_anousu');
$sSqlReduz .= "                                        and conplanoreduz.c61_instit = ".db_getsession('DB_instit');
$sSqlReduz .= "  where contabancaria.db83_sequencial = {$k68_contabancaria} ";
$rsReduz    = db_query($sSqlReduz);

if( $rsReduz && pg_num_rows($rsReduz) > 0 ) {

  for ($i = 0; $i <  pg_num_rows($rsReduz); $i++) {

    db_fieldsmemory($rsReduz,$i);

    $sqlSaldoContaCaixa = "select substr(fc_saltessaldo(".$c61_reduz.",'".$k68_data."','".$k68_data."',null,".db_getsession('DB_instit')."),41,13)::float as saldocontacaixa";
    $rsSaldoContaCaixa  = $clsaltes->sql_record($sqlSaldoContaCaixa);

    if ($clsaltes->numrows > 0) {
      db_fieldsmemory($rsSaldoContaCaixa,0);
      $nTotContaCaixa   += $saldocontacaixa;
    }
  }
}

$saldoConciliacao = ( (float)$saldoextrato - (float)$totalPendenciasCaixa + (float)$totalPendenciasExtrato );
$saldoConciliacao = round($saldoConciliacao, 2);
$nTotContaCaixa   = round($nTotContaCaixa, 2);
$diferenca        = round(($saldoConciliacao - $nTotContaCaixa), 2);

$pdf->cell(189,$alt," SALDO DA CONTA BANCÁRIA EM TESOURARIA : ".db_formatar($nTotContaCaixa,'f')  ,'BT',1,"R",1);
$pdf->cell(189,$alt," SALDO DA CONCILIACAO : ".                 db_formatar($saldoConciliacao,'f') ,'BT',1,"R",1);
$pdf->cell(189,$alt," DIFERENCA : ".                            db_formatar($diferenca,'f')        ,'BT',1,"R",1);

$sContador   =  "_________________________________________"."\n"."Contador";
$sSecretario =  "_________________________________________"."\n"."Secretaria da Fazenda";
$sAssCont    = $classinatura->assinatura(1005,$sContador,"",$data);
$sAssSecr    = $classinatura->assinatura(1004,$sSecretario,"",$data);

$pdf->ln(10);
$pdf->setfont($fonte,'b',6);

if ($pdf->getAvailHeight() < 30) {

  $pdf->addPage();
  $pdf->setY(50);
}

$iPosY = $pdf->gety();

$pdf->SetXY(30, $iPosY);
$pdf->multicell(60,2, $sAssCont, 0, "C", 0, 0);

$pdf->SetXY(30+110,$iPosY);
$pdf->multicell(60, 2, $sAssSecr, 0, "C", 0, 0);
$pdf->output();
