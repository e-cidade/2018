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
require_once(modification("libs/db_sql.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_empagemov_classe.php"));
require_once(modification("classes/db_empagemovconta_classe.php"));
require_once(modification("classes/db_db_bancos_classe.php"));
require_once(modification("classes/db_empageslip_classe.php"));

$clempagemov = new cl_empagemov;
$clempagemovconta = new cl_empagemovconta;
$cldb_bancos = new cl_db_bancos;
$clempageslip     = new cl_empageslip;
$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("e80_codage");
$clrotulo->label("e81_codmov");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e96_codigo");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$oGet = db_utils::postMemory($_GET);

$order_by = "e83_codtipo, e83_conta, ";

if($ordem == "a") {
  $desc_ordem = "Alfabética";
  $order_by .= "z01_nome";
}else if($ordem == "b"){
  $desc_ordem = "Numérica";
  $order_by .= "z01_numcgm";
}else{
  $desc_ordem = "Recurso";
  $order_by .= "o15_codigo";
}


$result_bancos = $cldb_bancos->sql_record($cldb_bancos->sql_query_file($db_banco));
if($cldb_bancos->numrows==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Banco não encontrado.');
}
db_fieldsmemory($result_bancos,0);
$head3 = "RELATÓRIO DE ARQUIVOS A GERAR";
$head5 = "$db90_descr";
$head6 = "ORDEM: $desc_ordem";
$head9 = "** - Contas já usadas em arquivos ou conferidas";

$oInstit   = db_stdClass::getDadosInstit();

$dbwhere  = " e80_instit = " .db_getsession("DB_instit") . " and e97_codforma = 3";
$dbwhere .= " and (e90_codmov is null or e90_cancelado is true) ";
$dbwhere .= " and (case ";
$dbwhere .= "       when e90_codgera is not null";
$dbwhere .= "         then e90_codgera = (select max(e90_codgera)";
$dbwhere .= "                               from empageconfgera confgera";
$dbwhere .= "                              where confgera.e90_codmov = empageconfgera.e90_codmov)";
$dbwhere .= "       else ";
$dbwhere .= "         true ";
$dbwhere .= "      end )";

if(isset($db_banco) && trim($db_banco)!=""){
  $dbwhere .= " and conplanoconta.c63_banco='$db_banco' ";
}

if ( !empty($oGet->sCNPJContaBancaria) && $oGet->sCNPJContaBancaria != 0) {

  $dbwhere .= " and contabancaria.db83_identificador = '{$oGet->sCNPJContaBancaria}' ";
  $head7 = "CNPJ: $oGet->sCNPJContaBancaria";
}

$sWhereOrdem   = "    ((round(e53_valor, 2)-round(e53_vlranu,2)-round(e53_vlrpag,2)) > 0 ";
$sWhereOrdem  .= " and (round(e60_vlremp,2)-round(e60_vlranu,2)-round(e60_vlrpag,2)) > 0)";

$sWhereOrdem  .= " and not exists( select 1 from empageconfgera confvalidar where confvalidar.e90_codmov  = e81_codmov and e90_cancelado is false)";

$sql    = $clempagemov->sql_query_txt(null,"distinct pc63_conta,
                                                     pc63_agencia,
                                                     pc63_banco as banco,
                                                     pc63_contabanco,
                                                     e80_codage,
                                                     pc63_dataconf,
                                                     e50_codord,
                                                     e50_data,
                                                     e82_codord,
                                                     o15_codigo,
                                                     o15_descr,
                                                     e81_codmov,
                                                     e83_codtipo,
                                                     e83_conta,
                                                     e83_descr,
                                                     e60_emiss,
                                                     e60_numemp,
                                                     e60_codemp,
                                                     z01_numcgm,
                                                     z01_nome,
                                                     pc63_cnpjcpf as z01_cgccpf,
                                                     e81_valor,
                                                     fc_valorretencaomov(e81_codmov,false) as vlrretencao,
                                                     1 as tipo",""," $dbwhere and $sWhereOrdem ");

$sqlSlips  = $clempageslip->sql_query_txtbanco(null,"
                                                     (case when pc63_conta is null then descrconta.c63_conta||'/'||descrconta.c63_dvconta
                                                           else pc63_conta end ) as pc63_conta,
                                                     (case when pc63_agencia is null then descrconta.c63_agencia||'/'||descrconta.c63_dvagencia
                                                           else pc63_agencia end ) as pc63_agencia,
                                                     (case when pc63_banco is null then descrconta.c63_banco
                                                           else pc63_banco end ) as banco,
                                                     null as p63_contabanco,
                                                     e80_codage,
                                                     pc63_dataconf,
                                                     s.k17_codigo,
                                                     k17_data,
                                                     e89_codigo,
                                                     pag.c61_codigo as o15_codigo,
                                                     orctiporec.o15_descr,
                                                     e81_codmov,
                                                     e83_codtipo,
                                                     e83_conta,
                                                     e83_descr,
                                                     k17_data,
                                                     0 as e60_numemp,
                                                     'slip' as e60_codemp,
                                                    (case when z01_numcgm is  not null then z01_numcgm
                                                      else {$oInstit->z01_numcgm} end)  as z01_numcgm,
                                                    (case when z01_nome is  not null then z01_nome
                                                       else '{$oInstit->z01_nome}' end) as z01_nome,
                                                     (case when z01_cgccpf is  not null then z01_cgccpf
                                                       else '{$oInstit->z01_cgccpf}' end) as z01_cgccpf,
                                                     e81_valor ,
                                                     0 as vlrretencao,
                                                     2 as tipo",""," $dbwhere");
$sql .=  " union ".$sqlSlips;

$sql = "select * from ({$sql}) as txtbanco order by {$order_by}";

$result = $clempagemov->sql_record($sql);
$numrows= $clempagemov->numrows;

if($clempagemov->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.');
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$cor = 1;

$antigaconta = "";
$valorconta  = 0;

$valdep = 0;
$valdoc = 0;
$valted = 0;
$valtot = 0;

$totdep = 0;
$totdoc = 0;
$totted = 0;

$nTotalBruto = 0;
$nTotalRetencoes = 0;

for($x=0;$x<$numrows;$x++){

  db_fieldsmemory($result,$x);
  $sSqlBuscaMovimentos = $clempagemovconta->sql_query_conta($e81_codmov,"pc63_banco as banco,pc63_agencia as agencia,pc63_agencia_dig as digito,pc63_conta as conta,pc63_conta_dig as digitoc,pc63_cnpjcpf ");
  $result_movconta = $clempagemovconta->sql_record($sSqlBuscaMovimentos);

  $numrows_movconta = $clempagemovconta->numrows;
  if($numrows_movconta>0){
    db_fieldsmemory($result_movconta,0);
    if(trim($digito)!=""){
      $digito = "-$digito";
    }
    if(trim($digitoc)!=""){
      $digitoc = "-$digitoc";
    }
  }

  if(trim($db_banco)==trim($banco)){
    $codigopagamento = "DEP";
  }else if($e81_valor<5000){
    $codigopagamento = "DOC";
  }else{
    $codigopagamento = "TED";
  }

  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage("L");
    $pdf->setfont('arial','b',8);
    $pdf->cell( 80,$alt,"Instituição",1,0,"C",1);
    $pdf->cell(195,$alt,"Fornecedor",1,1,"C",1);

    $pdf->cell(17,$alt, 'Nº Empenho', 1,0,"C",1);
    $pdf->cell(15,$alt,"OP/Slip",1,0,"C",1);
    $pdf->cell(30,$alt,"Recurso",1,0,"C",1);
    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
    $pdf->cell(68,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(25,$alt,$RLz01_cgccpf,1,0,"C",1);
    $pdf->cell(15,$alt,"Cód.Pgto.",1,0,"C",1);
    $pdf->cell(10,$alt,"Banco",1,0,"C",1);
    $pdf->cell(20,$alt,"Agência",1,0,"C",1);
    $pdf->cell(20,$alt,"Conta",1,0,"C",1);
    $pdf->cell(20,$alt,"Retenção",1,0,"C",1);
    $pdf->cell(20,$alt,"Valor a pagar",1,1,"C",1);
    $total = 0;
    $troca = 0;
    $cor = 1;
  }

  if($antigaconta != $e83_codtipo){
    if($antigaconta!=""){
      $pdf->setfont('arial','b',8);
      $pdf->cell(250,$alt,"Valor conta","T",0,"R",0);
      $pdf->cell( 25,$alt,db_formatar($valorconta,"f"),"T",1,"R",0);
      $pdf->cell(250,$alt,"Valor DEP",0,0,"R",0);
      $pdf->cell( 25,$alt,db_formatar($valdep,"f"),0,1,"R",0);
      $pdf->cell(250,$alt,"Valor DOC",0,0,"R",0);
      $pdf->cell( 25,$alt,db_formatar($valdoc,"f"),0,1,"R",0);
      $pdf->cell(250,$alt,"Valor TED","B",0,"R",0);
      $pdf->cell( 25,$alt,db_formatar($valted,"f"),"B",1,"R",0);
      $valdep = 0;
      $valdoc = 0;
      $valted = 0;
    }
    $valorconta = 0;
    $pdf->ln(3);
    $pdf->cell(275,$alt,$e83_codtipo." - ".$e83_descr." - CONTA: $e83_conta",1,1,"L",1);
    $antigaconta = $e83_codtipo;
    $cor = 1;
  }

  if($cor==1){
    $cor=0;
  }else{
    $cor=1;
  }

  $asteriscos = "";

  if ($pc63_contabanco != "") {

    $sSqlBuscaContaFornecedor = $clempagemovconta->sql_query_conta(null,"pc63_contabanco","",
                               "pc63_contabanco=$pc63_contabanco and e90_codmov is not null");
    $result_asteriscos = $clempagemovconta->sql_record($sSqlBuscaContaFornecedor);

    if($clempagemovconta->numrows > 0 || $pc63_dataconf!=""){

      $asteriscos = "** ";
    } else {

      $agencia = $pc63_agencia;
      $conta   = $pc63_conta;
    }
  } else {

    $agencia = $pc63_agencia;
    $conta   = $pc63_conta;
  }

  $e81_valor  = $e81_valor - $vlrretencao;
  $pdf->setfont('arial','',7);
  $pdf->cell(15,$alt,$e60_numemp==0?"Slip":$e60_numemp,0,0,"C",$cor);
  $pdf->cell(15,$alt,$e50_codord,0,0,"C",$cor);
  $pdf->cell(30,$alt,substr($o15_codigo.'-'.$o15_descr,0,20),0,0,"L",$cor);
  $pdf->cell(15,$alt,$z01_numcgm,0,0,"C",$cor);
  $pdf->cell(70,$alt,$asteriscos.$z01_nome,0,0,"L",$cor);
  $pdf->cell(25,$alt,$z01_cgccpf,0,0,"R",$cor);
  $pdf->cell(15,$alt,$codigopagamento,0,0,"C",$cor);
  $pdf->cell(10,$alt,$banco,0,0,"C",$cor);
  @$pdf->cell(20,$alt,$agencia.$digito,0,0,"R",$cor);
  @$pdf->cell(20,$alt,$conta.$digitoc,0,0,"R",$cor);
  @$pdf->cell(20,$alt,db_formatar($vlrretencao,"f"),0,0,"R",$cor);
  $pdf->cell(20,$alt,db_formatar($e81_valor,"f"),0,1,"R",$cor);
  $total++;
  $valorconta+= $e81_valor;
  if(trim($db_banco)==trim($banco)){
    $valdep += $e81_valor;
    $totdep += $e81_valor;
  }else if($e81_valor<5000){
    $valdoc += $e81_valor;
    $totdoc += $e81_valor;
  }else{
    $valted += $e81_valor;
    $totted += $e81_valor;
  }

  $valtot          += $e81_valor;
  $nTotalRetencoes += $vlrretencao;
}

$nTotalBruto = $valtot + $nTotalRetencoes;

$pdf->setfont('arial','b',8);
$pdf->cell(250,$alt,"Valor conta","T",0,"R",0);
$pdf->cell( 25,$alt,db_formatar($valorconta,"f"),"T",1,"R",0);
$pdf->cell(250,$alt,"Valor DEP",0,0,"R",0);
$pdf->cell( 25,$alt,db_formatar($valdep,"f"),0,1,"R",0);
$pdf->cell(250,$alt,"Valor DOC",0,0,"R",0);
$pdf->cell( 25,$alt,db_formatar($valdoc,"f"),0,1,"R",0);
$pdf->cell(250,$alt,"Valor TED","B",0,"R",0);
$pdf->cell( 25,$alt,db_formatar($valted,"f"),"B",1,"R",0);

$pdf->ln(2);
$pdf->cell(250,$alt,"Total DEP",0,0,"R",0);
$pdf->cell( 25,$alt,db_formatar($totdep,"f"),0,1,"R",0);
$pdf->cell(250,$alt,"Total DOC",0,0,"R",0);
$pdf->cell( 25,$alt,db_formatar($totdoc,"f"),0,1,"R",0);
$pdf->cell(250,$alt,"Total TED","B",0,"R",0);
$pdf->cell( 25,$alt,db_formatar($totted,"f"),"B",1,"R",0);

$pdf->cell(250,$alt,"Valor total conta","TB",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($valtot,"f"),"TB",1,"R",1);

$pdf->cell(250,$alt,"Valor total retenções","TB",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($nTotalRetencoes,"f"),"TB",1,"R",1);

$pdf->cell(250,$alt,"Valor total bruto","TB",0,"R",1);
$pdf->cell( 25,$alt,db_formatar($nTotalBruto,"f"),"TB",1,"R",1);

$pdf->Output();
