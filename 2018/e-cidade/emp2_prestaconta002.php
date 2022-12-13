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


require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");
require_once("classes/db_emppresta_classe.php");
require_once("classes/db_orcorgao_classe.php");
require_once("classes/db_empprestaitem_classe.php");

$clemppresta = new cl_emppresta;
$clorcorgao = new cl_orcorgao;
$clempprestaitem = new cl_empprestaitem;

$clrotulo = new rotulocampo;
$clrotulo->label('e45_data');
$clrotulo->label('e45_acerta');
$clrotulo->label('e45_conferido');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$xinstit = explode("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$info="Período Total";

$txt_where= "1=1 and e60_instit in (".str_replace("-",",",$db_selinstit).") ";

if (($data!="--")&&($data!="--")) {
  $txt_where = $txt_where." and e45_data between '$data' and '$data1'  ";
  $data=db_formatar($data,"d");
  $data1=db_formatar($data1,"d");
  $info="De $data até $data1.";
} else if ($data!="--"){
  $txt_where = $txt_where." and e45_data >= '$data'  ";
  $data=db_formatar($data,"d");
  $info="Apartir de $data.";
} else if ($data1!="--"){
  $txt_where = $txt_where." and   e45_data <= '$data1'   ";
  $data1=db_formatar($data1,"d");
  $info="Até $data1.";
}

if (isset($e60_codemp)&&$e60_codemp!=""){
  $txt_where .= " and e60_codemp='$e60_codemp' ";
  if(isset($e60_anousu)&&$e60_anousu!=""){
    $txt_where .= " and e60_anousu=$e60_anousu ";
  } else {
    $txt_where .= " and e60_anousu=".db_getsession("DB_anousu")." ";
  }
}

if (isset($e60_numcgm)&&$e60_numcgm!=""){
  $txt_where=$txt_where."and e60_numcgm=$e60_numcgm";
}

$tipoemissao = "Todos";

if (($data_lanc!="--")&&($data_lanc1!="--")) {
  $txt_where = $txt_where." and e45_conferido between '$data_lanc' and '$data_lanc1'  ";
  $data_lanc=db_formatar($data_lanc,"d");
  $data_lanc1=db_formatar($data_lanc1,"d");
  $info="Movimentos de $data_lanc até $data_lanc1.";
} else if ($data_lanc!="--"){
  $txt_where = $txt_where." and e45_conferido >= '$data_lanc'  ";
  $data_lanc=db_formatar($data_lanc,"d");
  $info="Movimento Apartir de $data_lanc.";
} else if ($data_lanc1!="--"){
  $txt_where = $txt_where." and   e45_conferido <= '$data_lanc1'   ";
  $data_lanc1=db_formatar($data_lanc1,"d");
  $info="Até $data_lanc1.";
}


if ($ordem=='a'){ // todos
}else if($ordem=='b'){
  $txt_where.=" and e45_acerta is null"; // nao acertados
  $tipoemissao = "Não acertados";

}else if($ordem=='c') {
  $txt_where.=" and e45_conferido is not null"; // conferidos
  $tipoemissao = "Conferidos";
}else if($ordem=='d'){
  $txt_where.=" and e45_acerta is not null and e45_conferido is null"; // acertados e nao conferidos
  $tipoemissao = "Acertados e Não Conferidos";
}

if (isset($o40_orgao) and $o40_orgao != ""){
  $txt_where.=" and o58_orgao = $o40_orgao ";
}


$head3 = "RELATÓRIO DE PRESTAÇÃO DE CONTAS";
$head4 = "INSTITUIÇÕES : ".$descr_inst;
$head5 = "$info";
$head6 = $tipoemissao;

/**
 * Where para trazer somente os empenhos que não estão anulados e possuem
 * no minimo um item na prestação de contas
 */
$txt_where .= " and e60_vlremp <> e60_vlranu ";
$sCampos = "distinct e60_codemp, e60_anousu, e45_data, e45_acerta, e45_conferido, e60_vlremp, o58_orgao, o58_Anousu, e45_numemp,z01_nome,e60_resumo";
$sSqlPrestacaoContas = $clemppresta->sql_query(null,$sCampos,"o58_orgao, e45_data","$txt_where");
$result=$clemppresta->sql_record($sSqlPrestacaoContas);

if ($clemppresta->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

//db_criatabela($result); exit;

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;

$orgao = 0;

$totalreg = 0;

$totaladiantamento = 0;
$totalprestacao = 0;
$totalanular = 0;

$totalgeraladiantamento = 0;
$totalgeralprestacao = 0;
$totalgeralanular = 0;

for($x = 0; $x < $clemppresta->numrows;$x++) {
   db_fieldsmemory($result,$x);

   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,'Empenho',1,0,"C",1);
      $pdf->cell(65,$alt,'Nome do Servidor',1,0,"C",1);
      $pdf->cell(20,$alt,'Dt Emissão',1,0,"C",1);
      $pdf->cell(20,$alt,'Dt Acerto',1,0,"C",1);
      $pdf->cell(20,$alt,'Dt Conferência',1,0,"C",1);
      $pdf->cell(30,$alt,'Adiantamento',1,0,"C",1);
      $pdf->cell(30,$alt,'Prestação',1,0,"C",1);
      $pdf->cell(30,$alt,'Anular de Despesa',1,0,"C",1);
      $pdf->cell(40,$alt,"Instituição",1,1,"C",1);
      $p=0;
      $troca = 0;
  }

   if ($x == $clemppresta->numrows or $orgao != $o58_orgao) {

     $resultorgao=$clorcorgao->sql_record($clorcorgao->sql_query($o58_anousu,$o58_orgao,"o40_descr",""));
     if ($clorcorgao->numrows > 0) {
       db_fieldsmemory($resultorgao,0);
     } else {
       $o40_descr = "";
     }

     if ($totalreg > 0) {
       $pdf->cell(151,$alt,"TOTAL DE REGISTROS: $totalreg","T",0,"L",0);
       $pdf->cell(035,$alt,db_formatar($totaladiantamento,'f'),"T",0,"R",0);
       $pdf->cell(036,$alt,db_formatar($totalprestacao,'f'),"T",0,"R",0);
       $pdf->cell(036,$alt,db_formatar($totalanular,'f'),"T",0,"R",0);
       $pdf->cell(050,$alt,"","T",0,"R",0);
       $pdf->ln();
     }

     if ($quebrarpagorgao == "s" and $totalreg > 0) {
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(25,$alt,'Empenho',1,0,"C",1);
      $pdf->cell(65,$alt,'Nome do Servidor',1,0,"C",1);
      $pdf->cell(20,$alt,$RLe45_data,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe45_acerta,1,0,"C",1);
      $pdf->cell(20,$alt,$RLe45_conferido,1,0,"C",1);
      $pdf->cell(30,$alt,'Adiantamento',1,0,"C",1);
      $pdf->cell(30,$alt,'Prestação',1,0,"C",1);
      $pdf->cell(30,$alt,'Anular de Despesa',1,0,"C",1);
      $pdf->cell(40,$alt,"Instituição",1,1,"C",1);
      $p=0;
      $troca = 0;
     }

     $pdf->setfont('arial','b',8);
     $pdf->ln();
     $pdf->cell(280,$alt,"ORGAO: $o58_orgao - $o40_descr","TB",0,"L",1);
     $pdf->ln();
     $orgao = $o58_orgao;

     $totalreg = 0;
     $totaladiantamento = 0;
     $totalprestacao = 0;
     $totalanular = 0;

   }

  $pdf->setfont('arial','',8);
  $pdf->cell(25,$alt,trim($e60_codemp).'/'.$e60_anousu,0,0,"C",$p);
  $pdf->cell(65,$alt,$z01_nome,0,0,"L",$p);
  $pdf->cell(20,$alt,db_formatar($e45_data,'d'),0,0,"C",$p);
  $pdf->cell(20,$alt,db_formatar($e45_acerta,'d'),0,0,"C",$p);
  $pdf->cell(20,$alt,db_formatar($e45_conferido,'d'),0,0,"C",$p);

  $valor_gasto=0;
  $result_valor=$clempprestaitem->sql_record($clempprestaitem->sql_query_file(null," e46_numemp, e46_valor",null,"e46_numemp=$e45_numemp"));
  for($y=0;$y<$clempprestaitem->numrows;$y++){
    db_fieldsmemory($result_valor,$y);
    $valor_gasto=$valor_gasto+$e46_valor;
  }
  $pdf->cell(30,$alt,db_formatar($e60_vlremp,'f'),0,0,"R",$p);
  $pdf->cell(30,$alt,db_formatar($valor_gasto,'f'),0,0,"R",$p);
  if (isset($e54_acerta) && trim(@$e54_acerta) == "" && isset($e45_conferido) && trim(@$e45_conferido) ==""){
      $anular_despesa=0;
  } else {
      $anular_despesa=$e60_vlremp-$valor_gasto;
  }

  $pdf->cell(30,$alt,db_formatar($anular_despesa,'f'),0,0,"R",$p);
  $totaladiantamento      += $e60_vlremp;
  $totalprestacao         += $valor_gasto;
  $totalanular            += $anular_despesa;
  $totalgeraladiantamento += $e60_vlremp;
  $totalgeralprestacao    += $valor_gasto;
  $totalgeralanular       += $anular_despesa;

  if (isset($nomeinstabrev) && trim(@$nomeinstabrev)!=""){
       $pdf->cell(40,$alt,$nomeinstabrev,0,1,"L",$p);
  } else {
       $pdf->cell(40,$alt,$codigo,0,1,"L",$p);
  }

  if($historico == "s"){
    $pdf->multicell(0,$alt,"HISTÓRICO : ".$e60_resumo,"B","L",$p);
    $pdf->ln(1);

  }

  if ($p==0) {
    $p=1;
  } else {
    $p=0;
  }

  $total++;
  $totalreg++;

   if ($x == $clemppresta->numrows - 1) {

     $pdf->cell(151,$alt,"TOTAL DE REGISTROS: $totalreg","T",0,"L",0);
     $pdf->cell(035,$alt,db_formatar($totaladiantamento,'f'),"T",0,"R",0);
     $pdf->cell(036,$alt,db_formatar($totalprestacao,'f'),"T",0,"R",0);
     $pdf->cell(036,$alt,db_formatar($totalanular,'f'),"T",0,"R",0);
     $pdf->cell(050,$alt,"","T",0,"R",0);
     $pdf->ln();

   }

}

$pdf->setfont('arial','b',8);
$pdf->ln(3);
$pdf->cell(151,$alt,"TOTAL GERAL DE REGISTROS: $total","T",0,"L",0);
$pdf->cell(035,$alt,db_formatar($totalgeraladiantamento,'f'),"T",0,"R",0);
$pdf->cell(036,$alt,db_formatar($totalgeralprestacao,'f'),"T",0,"R",0);
$pdf->cell(036,$alt,db_formatar($totalgeralanular,'f'),"T",0,"R",0);
$pdf->cell(050,$alt,"","T",0,"R",0);

$pdf->Output();

?>