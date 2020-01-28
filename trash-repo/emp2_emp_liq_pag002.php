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

require_once("fpdf151/pdf.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_orctiporec_classe.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_orcorgao_classe.php");
require_once("classes/db_empemphist_classe.php");
require_once("classes/db_emphist_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("classes/db_conlancamemp_classe.php");
require_once("classes/db_conlancamdoc_classe.php");
require_once("classes/db_empempitem_classe.php");
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_GET);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clselorcdotacao  = new cl_selorcdotacao();
$clorcelemento    = new cl_orcelemento;
$clemphist        = new cl_emphist;
$clconlancamemp   = new cl_conlancamemp;
$clconlancamdoc   = new cl_conlancamdoc;
$clempempenho     = new cl_empempenho;
$clcgm            = new cl_cgm;
$clorctiporec     = new cl_orctiporec;
$clorcdotacao     = new cl_orcdotacao;
$clorcorgao       = new cl_orcorgao;
$clempemphist     = new cl_empemphist;
$clempempitem     = new cl_empempitem;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();
$clrotulo = new rotulocampo;

// quebra dados do filtro
$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php
// $instits= $clselorcdotacao->getInstit(); // nao usado, somente usada para carregar os parametros do filtro !
$sele_work = $clselorcdotacao->getDados(false);

//////////////////////////////////////////////////////////////////////////////////////////////////

$where_credor = '';
if($listacredor != ''){
  if($com_ou_sem == 'com'){
    $where_credor = ' and e60_numcgm in ('.$listacredor.') ';
  }else{
    $where_credor = ' and e60_numcgm not in ('.$listacredor.') ';
  }
}

$xtipo = ' c53_tipo in (';
$tem_outro = false;
$virg = '';
$xxtipo='';

if($emp == 'true'){
  $xtipo .= '10';
  $tem_outro = true;
  $xxtipo = 'EMPENHO';
}
if($estemp == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '11';
  $tem_outro = true;
  $xxtipo = 'ESTORNO DE EMPENHO';
}
if($liq == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '20';
  $tem_outro = true;
  $xxtipo = $xxtipo.$virg.' LIQUIDAÇÃO';
}
if($estliq == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '21';
  $tem_outro = true;
  $xxtipo = $xxtipo.$virg.' ESTORNO DE LIQUIDAÇÃO';
}
if($pag == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '30';
  $tem_outro = true;
  $xxtipo = $xxtipo.$virg.'PAGAMENTO';
}

if($estpag == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '31';
  $tem_outro = true;
  $xxtipo = $xxtipo.$virg.'ESTORNO DE PAGAMENTO';
}
$xtipo .= ')';

//$dataini  = '2005-06-01';
//$datafin  = '2005-06-03';
$dataesp2    = '2005-05-30';
$mostralan   = "m";  // mostra lancamentos
$tipo = "a"; // sempre analitico

//$instits= "(".db_getsession("DB_instit").")";
$instits = '  and e60_instit in  ('.str_replace('-', ', ', $db_selinstit).') '; // alteração

$tipocompra_descr ="TODOS";
if  (isset($tipocompra) &&  ($tipocompra!=0)){
	$instits .= "  and e60_codcom = ".$tipocompra;
	$res = db_query("select pc50_descr as tipocompra_descr from pctipocompra where pc50_codcom=".$tipocompra);
	db_fieldsmemory($res,0);
}


//$com_mov = 's';
//$mostraritem = "m";

if($ordem == 'e'){
  $xordem = " e60_numemp, c70_codlan ";
  $head7 = 'ORDEM : EMPENHO, TIPO:'.$tipocompra_descr;
}elseif($ordem == 'd'){
  $xordem = ' e60_emiss, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : DATA DE EMPENHO, TIPO:'.$tipocompra_descr;
}elseif($ordem == 'l'){
  $xordem = ' c70_data, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : DATA DE LANÇAMENTO, TIPO:'.$tipocompra_descr;
}elseif($ordem == 't'){
  $xordem = ' c53_descr, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : TIPO, TIPO:'.$tipocompra_descr;
}elseif($ordem == 'v'){
  $xordem = ' c70_valor, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : VALOR, TIPO:'.$tipocompra_descr;
}elseif($ordem == 'c'){
  $xordem = ' z01_nome, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : CREDOR, TIPO:'.$tipocompra_descr;
}


$head1 = 'RELATÓRIO DE EMPENHOS';
$head3 = $xxtipo;
$head5 = 'PERÍODO : '.db_formatar($dataini,'d').' A '.db_formatar($datafin,'d');

if($rp == 'n'){
 $xtipo .= ' and e60_numemp not in (select e91_numemp from empresto where e91_anousu = '.db_getsession("DB_anousu").')
             and c53_coddoc not in(31,32,33,34,35,36,37,38,1007) ';
}
if($rp == 'somente'){
 $xtipo .= ' and e60_anousu < '.db_getsession("DB_anousu").'  ';
}

$sCampos = '';
if ($oPost->sDadosFornecedor == 's') {
  $sCampos  = " z01_incest, z01_cgccpf, c66_codnota, ";
}



$sqlperiodo  = "  select empempenho.e60_numemp::integer as e60_numemp,                                             ";
$sqlperiodo .= " 	       e60_resumo,                                                                               ";
$sqlperiodo .= " 	       e60_destin,                                                                               ";
$sqlperiodo .= " 	       e60_codemp,                                                                               ";
$sqlperiodo .= " 	       e60_emiss,                                                                                ";
$sqlperiodo .= " 	       e60_numcgm,                                                                               ";
$sqlperiodo .= " 	       z01_nome,                                                                                 ";
$sqlperiodo .= " 	       z01_cgccpf,                                                                               ";
$sqlperiodo .= " 	       z01_munic,                                                                                ";
$sqlperiodo .= " 	       e60_vlremp,                                                                               ";
$sqlperiodo .= " 	       e60_vlranu,                                                                               ";
$sqlperiodo .= " 	       e60_vlrliq,                                                                               ";
$sqlperiodo .= " 	       e63_codhist,                                                                              ";
$sqlperiodo .= " 	       e40_descr,                                                                                ";
$sqlperiodo .= " 	       e60_vlrpag,                                                                               ";
$sqlperiodo .= " 	       e60_anousu,                                                                               ";
$sqlperiodo .= " 	       e60_coddot,                                                                               ";
$sqlperiodo .= " 	       o58_coddot,                                                                               ";
$sqlperiodo .= " 	       o58_orgao,                                                                                ";
$sqlperiodo .= " 	       o40_orgao,                                                                                ";
$sqlperiodo .= " 	       o40_descr,                                                                                ";
$sqlperiodo .= " 	       o58_unidade,                                                                              ";
$sqlperiodo .= " 	       o41_descr,                                                                                ";
$sqlperiodo .= " 	       o15_codigo,                                                                               ";
$sqlperiodo .= " 	       o15_descr,                                                                                ";
$sqlperiodo .= " 	       fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,                             ";
$sqlperiodo .= " 	       e60_codcom,                                                                               ";
$sqlperiodo .= " 	       pc50_descr,                                                                               ";
$sqlperiodo .= " 	       sum(c70_valor) as c70_valor,                                                              ";
$sqlperiodo .= " 	       c70_data,                                                                                 ";
$sqlperiodo .= " 	       c70_codlan,                                                                               ";
$sqlperiodo .= " 	       c53_tipo,                                                                                 ";
$sqlperiodo .= " 	       c53_descr,                                                   ";
$sqlperiodo .= " 	       {$sCampos}                                                                                ";
$sqlperiodo .= " 	       e91_numemp                                                                                ";
$sqlperiodo .= "    from empempenho                                                                                ";
$sqlperiodo .= "   inner join conlancamemp 	on c75_numemp                 = empempenho.e60_numemp                  ";
$sqlperiodo .= "   inner join conlancam		  on c70_codlan                 = c75_codlan                             ";
$sqlperiodo .= "    left join conlancamnota		  on c66_codlan             = c70_codlan                             ";
$sqlperiodo .= "   inner join conlancamdoc 	on c71_codlan                 = c70_codlan                             ";
$sqlperiodo .= "   inner join conhistdoc 		on c53_coddoc                 = c71_coddoc                             ";
$sqlperiodo .= "   inner join cgm 			      on cgm.z01_numcgm 		        = empempenho.e60_numcgm                ";
$sqlperiodo .= "   inner join db_config 		  on db_config.codigo 		      = empempenho.e60_instit                ";
$sqlperiodo .= "   inner join orcdotacao 		on orcdotacao.o58_anousu 	    = empempenho.e60_anousu                  ";
$sqlperiodo .= "                            and orcdotacao.o58_coddot      = empempenho.e60_coddot                 ";
$sqlperiodo .= "                            and orcdotacao.o58_instit      = empempenho.e60_instit                 ";
$sqlperiodo .= "   inner join emptipo 		    on emptipo.e41_codtipo 		    = empempenho.e60_codtipo               ";
$sqlperiodo .= "   inner join db_config as a on a.codigo 			            = orcdotacao.o58_instit                  ";
$sqlperiodo .= "   inner join orctiporec 		on orctiporec.o15_codigo 	    = orcdotacao.o58_codigo                  ";
$sqlperiodo .= "   inner join orcfuncao 		  on orcfuncao.o52_funcao 	    = orcdotacao.o58_funcao                ";
$sqlperiodo .= "   inner join orcsubfuncao 	on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao               ";
$sqlperiodo .= "   inner join orcprograma 		on orcprograma.o54_anousu 	  = orcdotacao.o58_anousu                ";
$sqlperiodo .= "                   		  	 and orcprograma.o54_programa   = orcdotacao.o58_programa                ";
$sqlperiodo .= "   inner join orcelemento 		on orcelemento.o56_codele 	  = orcdotacao.o58_codele                ";
$sqlperiodo .= "                            and orcdotacao.o58_anousu      = orcelemento.o56_anousu                ";
$sqlperiodo .= "   inner join orcprojativ 		on orcprojativ.o55_anousu 	  = orcdotacao.o58_anousu                ";
$sqlperiodo .= "                          	 and orcprojativ.o55_projativ   = orcdotacao.o58_projativ              ";
$sqlperiodo .= "   inner join orcorgao 		  on orcorgao.o40_anousu 		    = orcdotacao.o58_anousu                  ";
$sqlperiodo .= "                    			   and orcorgao.o40_orgao         = orcdotacao.o58_orgao                 ";
$sqlperiodo .= "   inner join orcunidade 		on orcunidade.o41_anousu      = orcdotacao.o58_anousu                  ";
$sqlperiodo .= "                            and orcunidade.o41_orgao       = orcdotacao.o58_orgao                  ";
$sqlperiodo .= "    	                       and orcunidade.o41_unidade     = orcdotacao.o58_unidade               ";
$sqlperiodo .= "   left join  empemphist 		on empemphist.e63_numemp      = empempenho.e60_numemp                  ";
$sqlperiodo .= "   left join  emphist 		    on emphist.e40_codhist        = empemphist.e63_codhist               ";
$sqlperiodo .= "   inner join pctipocompra 	on pctipocompra.pc50_codcom   = empempenho.e60_codcom                  ";
$sqlperiodo .= "   left join empresto		    on e60_numemp                 = e91_numemp                             ";
$sqlperiodo .= "                           and e60_anousu                 = e91_anousu                             ";
$sqlperiodo .= "  where $xtipo $where_credor                                                                       ";
$sqlperiodo .= "    and c70_data between '$dataini' and '$datafin'                                                 ";
$sqlperiodo .= "    and $sele_work                                                                                 ";
$sqlperiodo .= "    $instits                                                                                       ";
$sqlperiodo .= "  group by e60_numemp,                                                                             ";
$sqlperiodo .= "           e60_resumo,                                                                             ";
$sqlperiodo .= "           e60_destin,                                                                             ";
$sqlperiodo .= "           e60_codemp,                                                                             ";
$sqlperiodo .= "           e60_emiss,                                                                              ";
$sqlperiodo .= "           e60_numcgm,                                                                             ";
$sqlperiodo .= "           z01_nome,                                                                               ";
$sqlperiodo .= "           z01_cgccpf,                                                                             ";
$sqlperiodo .= "           z01_munic,                                                                              ";
$sqlperiodo .= "           e60_vlremp,                                                                             ";
$sqlperiodo .= "           e60_vlranu,                                                                             ";
$sqlperiodo .= "           e60_vlrliq,                                                                             ";
$sqlperiodo .= "           e63_codhist,                                                                            ";
$sqlperiodo .= "           e40_descr,                                                                              ";
$sqlperiodo .= "           e60_vlrpag,                                                                             ";
$sqlperiodo .= "           e60_anousu,                                                                             ";
$sqlperiodo .= "           e60_coddot,                                                                             ";
$sqlperiodo .= "           o58_coddot,                                                                             ";
$sqlperiodo .= "           o58_orgao,                                                                              ";
$sqlperiodo .= "           o40_orgao,                                                                              ";
$sqlperiodo .= "           o40_descr,                                                                              ";
$sqlperiodo .= "           o58_unidade,                                                                            ";
$sqlperiodo .= "           o41_descr,                                                                              ";
$sqlperiodo .= "           o15_codigo,                                                                             ";
$sqlperiodo .= "           o15_descr,                                                                              ";
$sqlperiodo .= "           e60_codcom,                                                                             ";
$sqlperiodo .= "           pc50_descr,                                                                             ";
$sqlperiodo .= "           c70_data,                                                                               ";
$sqlperiodo .= " 	         c70_codlan,                                                                             ";
$sqlperiodo .= "           c53_tipo,                                                                               ";
$sqlperiodo .= "           c53_descr,                                                                              ";
$sqlperiodo .= "           {$sCampos}                                                                              ";
$sqlperiodo .= "           e91_numemp                                                                              ";
$sqlperiodo .= "     order by $xordem                                                                              ";

$res=$clempempenho->sql_record($sqlperiodo);
$rows=$clempempenho->numrows;
if($rows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique os dados escolhidos! Não foi retornado nenhum resultado.');
}
//////////////////////////////////////////////////////////////////////
$resultinst = db_query("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}
$head3 = "INSTITUIÇÕES :  ".$descr_inst;


//$head3 = "Relatório de Empenhos";


$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
//$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$tam            = '04';
$imprime_header = true;
$contador       = 0;
$pre            = 0;
$total_emp      = 0;
$total_estemp   = 0;
$total_liq      = 0;
$total_estliq   = 0;
$total_pag      = 0;
$total_estpag   = 0;

$iContEmp       = 0;
$iContLiq       = 0;
$iContPag       = 0;
$iContEmpEst    = 0;
$iContLiqEst    = 0;
$iContPagEst    = 0;


for ($x=0; $x < $rows;$x++){
  db_fieldsmemory($res,$x);
  // testa novapagina
  if (( $pdf->gety() > $pdf->h - 40) || $x == 0){
     $troca = 0;
     $pdf->addpage();
     $pdf->SetFont('Arial','B',7);

     $pdf->Cell(15,$tam,"EMP",1,0,"C",1);
     $pdf->Cell(15,$tam,"DT.EMP.",1,0,"C",1);
     $pdf->Cell(55,$tam,"DOTAÇÃO",1,0,"C",1);
     $pdf->Cell(15,$tam,"DT.MOV.",1,0,"C",1);
     $pdf->Cell(45,$tam,"VALOR",1,0,"C",1);
     $pdf->Cell(45,$tam,'TOTAL/PARCIAL',1,1,"C",1);
     $pdf->cell(20,$tam,"CREDOR ",1,0,"C",1);
     $pdf->cell(85,$tam,"NOME",1,0,"C",1);
     $pdf->Cell(85,$tam,"TIP0",1,1,"C",1);

     if ($oPost->sDadosFornecedor == 's' ) {

       $pdf->cell(64,$tam,"CPF",1,0,"C",1);
       $pdf->cell(63,$tam,"INSCRIÇÃO ESTADUAL",1,0,"C",1);
       $pdf->cell(63,$tam,"NOTA.",1,1,"C",1);
     }

     if($com_mov == 's'){
       $pdf->Cell(0,$tam,"HISTÓRICO",1,1,"C",1);
     }
     if($mostraritem == 'm'){
       $pdf->Cell(10,$tam,"ITEM",1,0,"C",1);
       $pdf->Cell(100,$tam,"DESCRIÇÃO",1,0,"C",1);
       $pdf->Cell(10,$tam,"QUANT",1,0,"C",1);
       $pdf->multicell(0,$tam,"COMPLEMENTO",1,"C",1);
     }
     $pre = 1;
  }
  if($pre == 0)
    $pre = 0;
  else
    $pre = 0;
  $pdf->SetFont('Arial','',7);
  $pdf->Cell(15,$tam,$e60_codemp,0,0,"C",$pre);
  $pdf->Cell(15,$tam,db_formatar($e60_emiss,'d'),0,0,"C",$pre);
  $pdf->Cell(55,$tam,$dl_estrutural,0,0,"L",$pre);
  $pdf->Cell(15,$tam,db_formatar($c70_data,'d'),0,0,"C",$pre);
  $pdf->Cell(45,$tam,db_formatar($c70_valor,'f'),0,0,"C",$pre);
  if($c53_tipo == 20 || $c53_tipo == 21){
     $pdf->Cell(45,$tam,((($e60_vlremp - $e60_vlranu) == $c70_valor)?'TOTAL':'PARCIAL'),0,1,"C",$pre);
  }else{
     $pdf->Cell(45,$tam,'',0,1,"C",$pre);
  }
  $pdf->Cell(85,$tam,'CREDOR : '.$e60_numcgm.' - '.$z01_nome,0,0,"L",$pre);
  $pdf->Cell(85,$tam,$c53_descr,0,1,"C",$pre);

  if ($oPost->sDadosFornecedor == 's' ) {

    $sCnpjCpf = strlen($z01_cgccpf) == 11 ? db_formatar($z01_cgccpf, 'cpf') : db_formatar($z01_cgccpf, 'cnpj');
    $pdf->cell(64,$tam, "CPF / CNPJ: {$sCnpjCpf}", 0, 0, "L");
    $pdf->cell(63,$tam, "INSC. EST.: {$z01_incest}", 0, 0, "L");
    $pdf->cell(63,$tam, "NOTA: {$c66_codnota}", 0, 1, "L");
  }

  if ($com_mov == 's') {

    $pdf->multiCell(0,$tam,'HISTÓRICO : '.$e60_resumo,0,"L",$pre);
    if ($e60_destin != '') {
       $pdf->multiCell(0,$tam,'DESTINO : '.$e60_destin,0,"L",$pre);
    }
  }

  if ($mostraritem == "m") {
      $resitem=$clempempitem->sql_record($clempempitem->sql_query($e60_numemp,null,"*"));
      $rows_item = $clempempitem->numrows;
      for ($item=0; $item < $rows_item; $item++) {
        db_fieldsmemory($resitem,$item,true);
        $preenche = ($item%2==0?0:1);
//        $pdf->Cell(40,$tam,"",0,0,"R",$preenche);
        $pdf->Cell(10,$tam,"$e62_item",0,0,"R",$preenche);
        $pdf->Cell(100,$tam,substr($pc01_descrmater,0,67),0,0,"L",$preenche);
        $pdf->Cell(10,$tam,db_formatar($e62_quant,'f'),0,0,"R",$preenche);
//        $pdf->Cell(20,$tam,db_formatar($e62_vltot,'f'),0,0,"R",$preenche);
        $pdf->multicell(0,$tam,substr($e62_descr,0,200),0,"L",$preenche);
      }
  }
  $pdf->cell(0,1,'',"B",1,"C",0);
  if($c53_tipo == 10 || $c53_tipo == 11){
    if($c53_tipo == 10){
      $total_emp += $c70_valor;
      $iContEmp++;
    }else{
      $total_estemp += $c70_valor;
      $iContEmpEst++;
    }
  }elseif($c53_tipo == 20 || $c53_tipo == 21){
    if($c53_tipo == 20){
       $total_liq += $c70_valor;
       $iContLiq++;
    }else{
       $total_estliq += $c70_valor;
       $iContLiqEst++;
    }
  }elseif($c53_tipo == 30 || $c53_tipo == 31){
    if($c53_tipo == 30){
       $total_pag += $c70_valor;
       $iContPag++;
    }else{
       $total_estpag += $c70_valor;
       $iContPagEst++;
    }
  }
}
//$pdf->SetFont('Arial','B',8);

$pdf->Cell(30,$tam,'TOTAIS'    ,0,0,"L",0);
$pdf->Cell(30,$tam,'EMPENHO'   ,0,0,"R",0);
$pdf->Cell(30,$tam,'LIQUIDAÇÃO',0,0,"R",0);
$pdf->Cell(30,$tam,'PAGAMENTO' ,0,0,"R",0);
$pdf->Cell(30,$tam,'À PAGAR'   ,0,1,"R",0);


$pdf->Cell(30,$tam,'LANÇAMENTOS',0,0,"L",0);
$pdf->Cell(30,$tam,db_formatar($total_emp,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_liq,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_pag,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,"",0,1,"R",0);

$pdf->Cell(30,$tam,'ESTORNOS',0,0,"L",0);
$pdf->Cell(30,$tam,db_formatar($total_estemp,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_estliq,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_estpag,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,"",0,1,"R",0);

$pdf->Cell(30,$tam,'TOTAL',0,0,"L",0);
$pdf->Cell(30,$tam,db_formatar($total_emp-$total_estemp,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_liq-$total_estliq,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_pag-$total_estpag,"f"),0,0,"R",0);
$pdf->Cell(30,$tam,db_formatar($total_emp-$total_estemp-($total_pag-$total_estpag),"f"),0,1,"R",0);

$pdf->Cell(30,$tam,'Nº DE LANÇAMENTOS',0,0,"L",0);
$pdf->Cell(30,$tam,$iContEmp,0,0,"R",0);
$pdf->Cell(30,$tam,$iContLiq,0,0,"R",0);
$pdf->Cell(30,$tam,$iContPag,0,0,"R",0);
$pdf->Cell(30,$tam,""       ,0,1,"R",0);

$pdf->Cell(30,$tam,'Nº DE ESTORNOS',0,0,"L",0);
$pdf->Cell(30,$tam,$iContEmpEst,0,0,"R",0);
$pdf->Cell(30,$tam,$iContLiqEst,0,0,"R",0);
$pdf->Cell(30,$tam,$iContPagEst,0,0,"R",0);
$pdf->Cell(30,$tam,""          ,0,1,"R",0);

//-- imprime parametros
if (isset($imprime_filtro) && ($imprime_filtro=='sim')){
   if (($pdf->getY()+44) > 250){
       $pdf->AddPage();
   } else {
      $pdf->setY(220);
   }
   $pdf->Ln(10);
   $parametros = $clselorcdotacao->getParametros();
   $pdf->multicell(190,4,$parametros,1,1,"R",'0');
}


$pdf->output();

?>