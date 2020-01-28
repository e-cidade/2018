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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empagegera_classe.php"));
require_once(modification("classes/db_empagemovconta_classe.php"));

$clempagegera = new cl_empagegera;
$clempagemovconta = new cl_empagemovconta;
$clrotulo = new rotulocampo;
$classinatura = new cl_assinatura;
$clempagegera->rotulo->label();
$clrotulo->label("e81_codmov");
$clrotulo->label("e82_codord");
$clrotulo->label("e81_valor");
$clrotulo->label("e81_numemp");
$clrotulo->label("pc63_banco");
$clrotulo->label("pc63_agencia");
$clrotulo->label("pc63_conta");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_cgccpf");
db_postmemory($_POST);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$HEAD3 = "RELATÓRIO DE ARQUIVOS GERADOS";
$HEAD5 = @$e87_codgera;
$HEAD6 = @$e87_descgera;
$e87_codgera = @$e87_codgera;
$xtipo = '';
if(isset($e83_codtipo) && $e83_codtipo!="0"){
  $HEAD3 = "TIPO";
  $HEAD5 = @$e83_codtipo;
  $HEAD6 = @$e83_codtipodescr;
  $xtipo = ' and e85_codtipo = '.$e83_codtipo;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$alt = 4;

$head1 = $HEAD3;
$head3 = "ARQUIVO :  ".$HEAD5.' - '.$HEAD6 ;

$db_where ='';
$db_where = ' e80_instit = ' . db_getsession("DB_instit");
if(isset($e87_codgera) && trim($e87_codgera)!=""){
  $db_where = ' empagegera.e87_codgera in ('.$e87_codgera.')';
}

$sqlOrdem = "
  select  distinct
	  e90_codgera,
	  e90_codmov,
	  e87_data,
	  e87_dataproc,
	  c63_banco,
	  c63_agencia,
	  coalesce(c63_dvagencia,'0') as c63_dvagencia,
	  c63_conta,
	  coalesce(c63_dvconta,'0') as c63_dvconta,
	  pc63_agencia::varchar,
      coalesce(pc63_agencia_dig,'0') as pc63_agencia_dig,
	  pc63_conta::varchar,
	  pc63_contabanco::varchar,
      coalesce(pc63_conta_dig,'0') as pc63_conta_dig,
	  translate(to_char(round(e81_valor- coalesce(fc_valorretencaomov(e81_codmov,false),0),2),'99999999999.99'),'.','') as valor,
	  e81_valor- coalesce(fc_valorretencaomov(e81_codmov,false),0) as valorori,
	  case when  pc63_banco = c63_banco then '01' else '03' end as  lanc,
	  coalesce(pc63_banco,'000') as pc63_banco,
	  e83_convenio as convenio,
	  z01_numcgm as z01_numcgm,
	  substr(z01_nome,1,40) as z01_nome,
	  case when pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then length(trim(z01_cgccpf)) else length(trim(pc63_cnpjcpf)) end as tam,
	  case when  pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null then z01_cgccpf else pc63_cnpjcpf end as cnpj,
	  e88_codmov as cancelado,
	  z01_ender,
	  z01_numero,
	  z01_compl,
	  z01_bairro,
	  z01_munic,
	  z01_cep,
	  z01_uf,
	  empagetipo.*,
	  e85_codtipo,
	  e81_valor,
	  e81_codmov,
	  e81_numemp,
	  e87_dataproc as dataprocessa,
	  e87_hora,
	  pc63_dataconf,
	  e60_codemp,
	  e82_codord,
	  fc_valorretencaomov(e81_codmov, false) as vlrretencao
  from empageconfgera
	  inner join empagegera on e90_codgera=e87_codgera
	  inner join empagemov on e90_codmov = e81_codmov
      inner join empage  on  empage.e80_codage = empagemov.e81_codage
	  inner join empempenho on e60_numemp = e81_numemp
	  inner join empagepag on e81_codmov = e85_codmov
	  inner join empagetipo on e85_codtipo = e83_codtipo
	  inner join empord on e81_codmov = e82_codmov
	  left join empageslip on e81_codmov = e89_codmov
	  inner join conplanoreduz on e83_conta = c61_reduz and c61_anousu = ".db_getsession("DB_anousu")."
	  inner join conplanoconta on c63_codcon = c61_codcon and c63_anousu = c61_anousu
	  left join slip on slip.k17_codigo = e89_codigo
	  left join slipnum on slipnum.k17_codigo = slip.k17_codigo
	  left join empageconfcanc on e88_codmov = e90_codmov
	  left join empagemovconta on e90_codmov = e98_codmov
	  left join pcfornecon on pc63_contabanco = e98_contabanco
	  left join cgm on z01_numcgm = pc63_numcgm
 where e80_instit = " . db_getsession("DB_instit") . " and  $db_where
	 ";
$sqlSlip = "
  select  distinct
	  e90_codgera,
	  e90_codmov,
	  e87_data,
	  e87_dataproc,
	  conplanoconta.c63_banco,
	  conplanoconta.c63_agencia,
	  coalesce(conplanoconta.c63_dvagencia,'0') as c63_dvagencia,
	  conplanoconta.c63_conta,
	  coalesce(conplanoconta.c63_dvconta,'0') as c63_dvconta,

    (case when pc63_agencia is null then descrconta.c63_agencia
     else pc63_agencia end )::varchar as pc63_agencia,

    coalesce((case when pc63_agencia_dig is null then descrconta.c63_dvagencia
     else pc63_agencia_dig end ),'0')::varchar as pc63_agencia_dig,

	  (case when pc63_conta is null then descrconta.c63_conta
       else pc63_conta end )::varchar as pc63_conta,

    null as pc63_contabanco,

    coalesce((case when pc63_conta_dig is null then descrconta.c63_dvconta
     else pc63_conta_dig end ),'0')::varchar as pc63_conta_dig,

	  translate(to_char(round(e81_valor - coalesce(fc_valorretencaomov(e81_codmov,false),0),2),'99999999999.99'),'.','') as valor,
	  e81_valor - coalesce(fc_valorretencaomov(e81_codmov,false),0) as valorori,
	  case when  (pc63_banco = conplanoconta.c63_banco or descrconta.c63_banco = conplanoconta.c63_banco)
	       then '01' else '03' end as  lanc,

	  coalesce(pc63_banco, descrconta.c63_banco) as pc63_banco,

	  e83_convenio as convenio,
	  case when cgm.z01_numcgm is null then cgmslip.z01_numcgm else cgm.z01_numcgm end as z01_numcgm,
	  substr(case when cgm.z01_nome is null then cgmslip.z01_nome else cgm.z01_nome end,1,40) as z01_nome,
	  case when  pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is null
	       then length(trim( case when cgm.z01_cgccpf is null then cgmslip.z01_cgccpf else cgm.z01_cgccpf end))
	       else length(trim(pc63_cnpjcpf)) end as tam,
	  case when  pc63_cnpjcpf = '0' or trim(pc63_cnpjcpf) = '' or pc63_cnpjcpf is
	       null then
	         ( case when cgm.z01_cgccpf is null then cgmslip.z01_cgccpf else cgm.z01_cgccpf end)
	       else pc63_cnpjcpf end as cnpj,
	  e88_codmov as cancelado,
	  case when cgm.z01_ender is null then cgmslip.z01_ender else cgm.z01_ender end as z01_ender,
	  case when cgm.z01_numero is null then cgmslip.z01_numero else cgm.z01_numero end as z01_numero,
	  case when cgm.z01_compl is null then cgmslip.z01_compl else cgm.z01_compl end as z01_compl,
	  case when cgm.z01_bairro is null then cgmslip.z01_bairro else cgm.z01_bairro end as z01_bairro,
	  case when cgm.z01_munic is null then cgmslip.z01_munic else cgm.z01_munic end as z01_munic,
	  case when cgm.z01_cep is null then cgmslip.z01_cep else cgm.z01_cep end as z01_cep,
	  case when cgm.z01_uf is null then cgmslip.z01_uf else cgm.z01_uf end as z01_uf,
	  empagetipo.*,
	  e85_codtipo,
	  e81_valor,
	  e81_codmov,
	  e81_numemp,
	  e87_dataproc as dataprocessa,
	  e87_hora,
	  pc63_dataconf,
	  'slip' as e60_codemp,
	  e89_codigo as e82_codord,
	  0 as  vlrretencao
  from empageconfgera
       inner join empagegera               on e90_codgera        = e87_codgera
       inner join empagemov                on e90_codmov         = e81_codmov
       inner join empage                   on empage.e80_codage  = empagemov.e81_codage
       inner join empagepag                on e81_codmov         = e85_codmov
       inner join empagetipo               on e85_codtipo        = e83_codtipo
       inner join empageslip               on e81_codmov         = e89_codmov
       inner join conplanoreduz            on c61_reduz          = e83_conta
                                          and c61_anousu         = ".db_getsession("DB_anousu")."
       inner join conplanoconta            on c63_codcon         = c61_codcon
                                          and c63_anousu         = c61_anousu
       inner join slip                     on slip.k17_codigo    = e89_codigo
       inner join slipnum                  on slipnum.k17_codigo = slip.k17_codigo

       left join empageconfcanc            on e88_codmov         = e90_codmov
       left join empagemovconta            on e90_codmov         = e98_codmov
       left join pcfornecon                on pc63_contabanco    = e98_contabanco
       left join cgm                       on z01_numcgm         = pc63_numcgm
       left join cgm cgmslip               on cgmslip.z01_numcgm = slipnum.k17_numcgm

       left join conplanoreduz cre         on cre.c61_reduz      = k17_debito
                                          and cre.c61_anousu     = ".db_getsession("DB_anousu")."

       left join conplano concre           on concre.c60_codcon  = cre.c61_codcon
                                          and concre.c60_anousu  = cre.c61_anousu

       left join conplanoconta descrconta  on concre.c60_codcon  = descrconta.c63_codcon
                                          and concre.c60_anousu  = descrconta.c63_anousu

  where e80_instit = " . db_getsession("DB_instit") . " and  $db_where
  order by e85_codtipo,z01_nome,pc63_banco,pc63_agencia";
$sqlMov = $sqlOrdem." union ".$sqlSlip;

//die($sqlMov);
$result_empagegera = $clempagegera->sql_record($sqlMov);
echo pg_last_error();
$numrows_empagegera = $clempagegera->numrows;
if($numrows_empagegera==0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado.");
}
db_fieldsmemory($result_empagegera,0);


//$oEmpenhoFinanceiro = EmpenhoFinanceiro::getInstanceByCodigo($e60_codemp, db_getsession('DB_anousu'));
$dtPagamento = null;
if (!empty($e87_codgera)) {
  $oConlancamEmp = new cl_conlancamemp();
  $sWhere  = " c75_numemp = {$e81_numemp} and e87_codgera = {$e87_codgera} ";
  $sWhere .= " and c53_tipo   = 30 order by c75_codlan desc limit 1";
  $sSqlLancamentoEmpenho = $oConlancamEmp->sql_query_arquivo_lancamento(null, 'c75_data', null, $sWhere);
  $rsBuscaLancamento = db_query($sSqlLancamentoEmpenho);
  $dtPagamento = $dataprocessa;
  if ($rsBuscaLancamento) {
    $dtPagamento = db_utils::fieldsMemory($rsBuscaLancamento, 0)->c75_data;
  }
}


$head5 = "GERAÇÃO  :  ". db_formatar($e87_data,"d").' AS '.$e87_hora.' HS';
if (!empty($dtPagamento)) {
  $head6 = "PAGAMENTO:  ".db_formatar($dtPagamento,"d");
}


// seleciona o nome do banco
$sql = "select db90_descr from db_bancos where trim(db90_codban)= '$c63_banco'";
$rbanco = db_query($sql);
if (pg_numrows($rbanco) > 0 ){
  db_fieldsmemory($rbanco,0);
}

if($c63_banco == '041'){
  $head7 = 'BANCO : 041 - BANRISUL';
}elseif($c63_banco == '001'){
  $head7 = 'BANCO : 001 - BANCO DO BRASIL';
}else{
  $head7 = 'BANCO ('.$c63_banco.'): '.$db90_descr;
}
$head9 = "** - Contas já usadas em arquivos ou conferidas";

//$head8 = 'AGENDA : '.$e81_codage;

$pdf->addpage("L");
$xvalor    = 0;
$xvaltotal = 0;
$xbanco    = '';
$ant_codgera = "";
$total_geral =0;

$soma_dep = 0;
$soma_doc = 0;
$soma_ted = 0;
$tota_dep = 0;
$tota_doc = 0;
$tota_ted = 0;

$nTotalBruto = 0;
$nTotalRetencoes = 0;

for($i =0 ; $i < $numrows_empagegera;$i++) {

  db_fieldsmemory($result_empagegera,$i);
  $e81_valor -= $vlrretencao;

  $pdf->setfont('arial','b',8);
  if($pdf->gety() > $pdf->h - 30 || $i==0){
    if($pdf->gety() > $pdf->h - 30){
      $pdf->cell(260,0.1,"","T",1,"L",0);
      $pdf->addpage("L");
    }
    $pdf->cell(15,$alt,"ARQUIVO",1,0,"C",1);
    $pdf->cell(260,$alt,"DESCRIÇÃO",1,1,"C",1);
    $pdf->cell(15,$alt, 'Nº Emp.',1,0,"C",0);
    $pdf->cell(15,$alt,"OP/Slip",1,0,"C",0);
    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",0);
    $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",0);
    $pdf->cell(25,$alt,$RLz01_cgccpf,1,0,"C",0);
    $pdf->cell(20,$alt,$RLe81_valor,1,0,"C",0);
    $pdf->cell(20,$alt,"Retenção",1,0,"C",0);
    $pdf->cell(15,$alt,"Cod.Pgto.",1,0,"C",0);
    $pdf->cell(15,$alt,$RLpc63_banco,1,0,"C",0);
    $pdf->cell(15,$alt,$RLpc63_agencia,1,0,"C",0);
    $pdf->cell(20,$alt,$RLpc63_conta,1,0,"C",0);
    $pdf->cell(20,$alt,$RLe81_codmov,1,0,"C",0);
    $pdf->cell(20,$alt,$RLe81_numemp,1,1,"C",0);
  }
  if($ant_codgera!=$e85_codtipo.'-'.$e87_codgera){

    if($i !=0){
      $pdf->cell(130,$alt,'DEP',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_dep,'f'),1,0,"R",1);
      $pdf->cell(125,$alt,'',1,1,"C",1);

      $pdf->cell(130,$alt,'DOC',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_doc,'f'),1,0,"R",1);
      $pdf->cell(125,$alt,'',1,1,"C",1);

      $pdf->cell(130,$alt,'TED',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($soma_ted,'f'),1,0,"R",1);
      $pdf->cell(125,$alt,'',1,1,"C",1);

      $pdf->cell(130,$alt,'Total Banco',1,0,"C",1);
      $pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
      $pdf->cell(125,$alt,'',1,1,"C",1);
      $soma_dep = 0;
      $soma_doc = 0;
      $soma_ted = 0;

      $pdf->ln(3);
    }
    $pdf->ln(3);
    $pdf->cell(15,$alt,$e85_codtipo,1,0,"C",1);
    $pdf->cell(220,$alt,$e83_descr.'   ('.$e87_codgera.'-'.$e87_descgera.')'." - CONTA $e83_conta","LTB",0,"L",1);
    $pdf->cell(40,$alt,"COVÊNIO - $e83_convenio","RTB",1,"L",1);
    $xtotal = 0;
    $ant_codgera=$e85_codtipo.'-'.$e87_codgera;
  }

  $lPagamentoBradesco = false;
  if (!empty($e87_codgera)) {

    $oDaoNumeroPagFor   = new cl_pagfornumeracao();
    $sSqlBuscaNumero    = $oDaoNumeroPagFor->sql_query_file(null, "*", null, "o152_empagegera = {$e87_codgera}");
    $rsBuscaNumero      = db_query($sSqlBuscaNumero);
    $lPagamentoBradesco = false;
    if ($rsBuscaNumero && pg_num_rows($rsBuscaNumero) == 1) {
      $codpgto            = "TED";
      $lPagamentoBradesco = true;
    }
  }

  if ( $pc63_banco == $c63_banco ) {

    $codpgto   = "DEP";
    $soma_dep += $e81_valor;
    $tota_dep += $e81_valor;

  } else {

    if ($e81_valor < 3000 && !$lPagamentoBradesco){
      $codpgto   = "DOC";
      $soma_doc += $e81_valor;
      $tota_doc += $e81_valor;
    } else {
      $codpgto   = "TED";
      $soma_ted += $e81_valor;
      $tota_ted += $e81_valor;
    }
  }




  if(trim($pc63_agencia_dig)!=""){
    $pc63_agencia_dig = "-".$pc63_agencia_dig;
  }
  if(trim($pc63_conta_dig)!=""){
    $pc63_conta_dig = "-".$pc63_conta_dig;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(15,$alt,$e60_codemp,1,0,"C",0);
  $pdf->cell(15,$alt,$e82_codord,1,0,"C",0);
  $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);

  $asteriscos = "";
  $sWhereContaBanco = '';
  if (!empty($pc63_contabanco)) {
    $sWhereContaBanco = " pc63_contabanco={$pc63_contabanco} and ";
  }
  $result_asteriscos = $clempagemovconta->sql_record($clempagemovconta->sql_query_conta(null,"pc63_contabanco","","{$sWhereContaBanco} e90_codmov is not null"));
  if($clempagemovconta->numrows > 0 || $pc63_dataconf!=""){
    $asteriscos = "** ";
  }

  $pdf->cell(60,$alt,$asteriscos.$z01_nome,1,0,"L",0);
  $pdf->cell(25,$alt,$cnpj,1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($e81_valor,'f'),1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($vlrretencao,'f'),1,0,"R",0);
  $pdf->cell(15,$alt,$codpgto,1,0,"C",0);

  $pdf->cell(15,$alt,$pc63_banco,1,0,"C",0);
  $pdf->cell(15,$alt,$pc63_agencia.$pc63_agencia_dig,1,0,"R",0);
  $pdf->cell(20,$alt,$pc63_conta.$pc63_conta_dig,1,0,"R",0);

  $pdf->cell(20,$alt,$e81_codmov,1,0,"C",0);
  $pdf->cell(20,$alt,$e81_numemp,1,1,"C",0);

  $total++;
  $xtotal    += $e81_valor;
  $xvaltotal += $e81_valor;

  $nTotalRetencoes += $vlrretencao;
}

$nTotalBruto = $xvaltotal + $nTotalRetencoes;

$pdf->setfont('arial','b',8);

$pdf->cell(130,$alt,'DEP',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($soma_dep,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->cell(130,$alt,'DOC',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($soma_doc,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->cell(130,$alt,'TED',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($soma_ted,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->cell(130,$alt,'Total Banco',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($xtotal,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->cell(130,$alt,'Valor total retenções',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($nTotalRetencoes,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->cell(130,$alt,'Valor total bruto',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($nTotalBruto,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$pdf->ln(2);
$pdf->cell(130,$alt,'Total Geral',1,0,"C",1);
$pdf->cell(20,$alt,db_formatar($xvaltotal,'f'),1,0,"R",1);
$pdf->cell(125,$alt,'',1,1,"C",1);

$tes =  "______________________________";
$pref =  "______________________________";
$largura = ( $pdf->w ) / 2;
$pdf->ln(10);
$pos = $pdf->gety();


$pdf->text(40,$pdf->h - 14,'______________________________',0,4);
$pdf->text(57,$pdf->h - 11,'Prefeito',0,4);
$pdf->text(120,$pdf->h - 14,'______________________________',0,4);
$pdf->text(129,$pdf->h - 11,'Secretário da Fazenda',0,4);
$pdf->text(200,$pdf->h - 14,'______________________________',0,4);
$pdf->text(217,$pdf->h - 11,'Tesoureiro',0,4);
$pdf->Output();
