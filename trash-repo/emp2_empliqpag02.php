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
require_once("libs/db_liborcamento.php");
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
db_postmemory($_POST);

//db_postmemory($HTTP_SERVER_VARS,2);exit;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clselorcdotacao = new cl_selorcdotacao();
$clorcelemento   = new cl_orcelemento;
$clemphist       = new cl_emphist;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdoc  = new cl_conlancamdoc;
$clempempenho    = new cl_empempenho;
$clcgm           = new cl_cgm;
$clorctiporec    = new cl_orctiporec;
$clorcdotacao    = new cl_orcdotacao;
$clorcorgao      = new cl_orcorgao;
$clempemphist    = new cl_empemphist;
$clempempitem    = new cl_empempitem;
$clrotulo        = new rotulocampo;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();

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
  $xtipo .= '10,11';
  $tem_outro = true;
  $xxtipo = 'EMPENHADO';
}
if($liq == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '20,21'; 
  $tem_outro = true;
  $xxtipo = $xxtipo.$virg.' LIQUIDADO';
}
if($pag == 'true'){
  if($tem_outro == true){
    $xtipo .= ',';
    $virg = ',';
  }
  $xtipo .= '30,31';
  $xxtipo = $xxtipo.$virg.'PAGO';  
}
$xtipo .= ')';
//echo 'tem outro'.$tem_outro;
//echo $xtipo;exit;
//if($tem_outro == false )
//  $xtipo = '';
//echo $xtipo;exit;

  $filtro = " 1 = 1 ";
if(isset($o50_estrutdespesa) && $o50_estrutdespesa!=""){
   $matriz=split('\.',$o50_estrutdespesa);
   for($i=0; $i<count($matriz); $i++) {
     switch($i){
       case 0://orgao
    	$o40_orgao = $matriz[$i];
    	break;
       case 1://unidade
    	$o41_unidade = $matriz[$i];
    	break;
       case 2://funcao
    	$o52_funcao = $matriz[$i];
    	break;
       case 3://subfuncao	
    	$o53_subfuncao = $matriz[$i];
    	break;
       case 4://programa
    	$o54_programa = $matriz[$i];
    	break;
       case 5://projativ
    	$o55_projativ = $matriz[$i];
    	break;
       case 6://elemento de despesa	
    	$o56_elemento = $matriz[$i];
    	break;
       case 7://tipo de  recurso
    	$o58_codigo = $matriz[$i];
    	break;
       case 8://contra recurso
    	$o61_codigo = $matriz[$i];
    	break;
     } 
   }
} 

   if(!empty($o40_orgao)){
     $filtro .= " and o58_orgao = $o40_orgao ";
   }
   if(!empty($o41_unidade)){
     if($filtro!="")
     $filtro .= " and o58_unidade = $o41_unidade ";
   }
   if(!empty($o52_funcao)){
     $filtro .= " and o58_funcao = $o52_funcao ";
   }
   if(!empty($o53_subfuncao)){
     $filtro .= " and o58_subfuncao = $o53_subfuncao ";
   }
   if(!empty($o54_programa)){
     $filtro .= " and o58_programa = $o54_programa ";
   }
   if(!empty($o55_projativ)){
     $filtro .= " and o58_projativ = $o55_projativ ";
   }
   if(!empty($o56_elemento)){
     $filtro .= " and o56_elemento = '$o56_elemento' ";
   }
   if(!empty($o58_codigo)){
     $filtro .= " and o58_codigo = $o58_codigo ";
   }
   if(!empty($o61_codigo)){
     $filtro .= " and o61_codigo = $o61_codigo ";
   }
   if(!empty($o58_coddot)){
     $filtro .= " and o58_coddot = $o58_coddot ";
   }

//$dataini  = '2005-06-01'; 
//$datafin  = '2005-06-03';
$dataesp2    = '2005-05-30';
$mostralan   = "m";  // mostra lancamentos
$tipo = "a"; // sempre analitico
$instits= "(".db_getsession("DB_instit").")";
//$com_mov = 's';
//$mostraritem = "m";


if($ordem == 'e'){
  $xordem = " e60_numemp, c70_codlan ";
  $head7 = 'ORDEM : EMPENHO';
}elseif($ordem == 'd'){
  $xordem = ' e60_emiss, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : DATA DE EMPENHO';
}elseif($ordem == 'l'){
  $xordem = ' c70_data, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : DATA DE LANÇAMENTO';
}elseif($ordem == 't'){
  $xordem = ' c53_descr, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : TIPO';
}elseif($ordem == 'v'){
  $xordem = ' c70_valor, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : VALOR';
}elseif($ordem == 'c'){
  $xordem = ' z01_nome, e60_numemp, c70_codlan';
  $head7 = 'ORDEM : CREDOR';
}


$head1 = 'RELATÓRIO DE EMPENHOS';
$head3 = $xxtipo;
$head5 = 'PERÍODO : '.db_formatar($dataini,'d').' A '.db_formatar($datafin,'d');

if($rp == 'n'){
 $xtipo .= ' and e60_numemp not in (select e91_numemp from empresto where e91_anousu = '.db_getsession("DB_anousu").') '; 
}

$sqlperiodo = "
select 	empempenho.e60_numemp::integer as e60_numemp,
	e60_resumo,
	e60_destin,
	e60_codemp,
	e60_emiss,
	e60_numcgm,
	z01_nome,
	z01_cgccpf,
	z01_munic,
	e60_vlremp,
	e60_vlranu,
	e60_vlrliq,
	e63_codhist,
	e40_descr,
	e60_vlrpag,
	e60_anousu,
	e60_coddot,
	o58_coddot,
	o58_orgao,
	o40_orgao,
	o40_descr,
	o58_unidade,
	o41_descr,
	o15_codigo,
	o15_descr,
	fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,
	e60_codcom,
	pc50_descr, 
	c70_valor,
	c70_data,
  c70_codlan,
	c53_tipo,
	c53_descr,
	e91_numemp
  from  empempenho 
        inner join conlancamemp 	on c75_numemp = empempenho.e60_numemp
        inner join conlancam		on c70_codlan = c75_codlan 
        inner join conlancamdoc 	on c71_codlan = c70_codlan
        inner join conhistdoc 		on c53_coddoc = c71_coddoc 
	inner join cgm 			on cgm.z01_numcgm 		= empempenho.e60_numcgm 
	inner join db_config 		on db_config.codigo 		= empempenho.e60_instit 
	inner join orcdotacao 		on orcdotacao.o58_anousu 	= empempenho.e60_anousu 
					and orcdotacao.o58_coddot = empempenho.e60_coddot 
	inner join emptipo 		on emptipo.e41_codtipo 		= empempenho.e60_codtipo 
	inner join db_config as a 	on a.codigo 			= orcdotacao.o58_instit 
	inner join orctiporec 		on orctiporec.o15_codigo 	= orcdotacao.o58_codigo 
	inner join orcfuncao 		on orcfuncao.o52_funcao 	= orcdotacao.o58_funcao 
	inner join orcsubfuncao 	on orcsubfuncao.o53_subfuncao 	= orcdotacao.o58_subfuncao 
	inner join orcprograma 		on orcprograma.o54_anousu 	= orcdotacao.o58_anousu 
					and orcprograma.o54_programa = orcdotacao.o58_programa 
	inner join orcelemento 		on orcelemento.o56_codele 	= orcdotacao.o58_codele 
	                               and orcelemento.o56_anousu       = orcdotacao.o58_anousu
	inner join orcprojativ 		on orcprojativ.o55_anousu 	= orcdotacao.o58_anousu 
					and orcprojativ.o55_projativ = orcdotacao.o58_projativ 
	inner join orcorgao 		on orcorgao.o40_anousu 		= orcdotacao.o58_anousu 
					and orcorgao.o40_orgao = orcdotacao.o58_orgao 
	inner join orcunidade 		on orcunidade.o41_anousu 	= orcdotacao.o58_anousu 
					and orcunidade.o41_orgao = orcdotacao.o58_orgao 
					and orcunidade.o41_unidade = orcdotacao.o58_unidade 
	left join  empemphist 		on empemphist.e63_numemp = empempenho.e60_numemp 
	left join  emphist 		on emphist.e40_codhist = empemphist.e63_codhist 
	inner join pctipocompra 	on pctipocompra.pc50_codcom = empempenho.e60_codcom
	left join empresto		on e60_numemp = e91_numemp 
					and ".db_getsession("DB_anousu")." = e91_anousu
where $xtipo $where_credor
  and c70_data between '$dataini' and '$datafin'
  and $filtro and e60_instit in $instits
order by $xordem

";

//     echo $sqlperiodo;exit;
     $res=$clempempenho->sql_record($sqlperiodo);
//db_criatabela($res);
     $rows=$clempempenho->numrows; 
if($rows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique os dados escolhidos! Não foi retornado nenhum resultado.');
}
//////////////////////////////////////////////////////////////////////

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
$total_liq      = 0;
$total_pag      = 0;

for ($x = 0; $x < $rows; $x++) {
	
  db_fieldsmemory($res, $x);
  // testa novapagina 
  if (( $pdf->gety() > $pdf->h - 40) || $x == 0){
     $troca = 0;
     $pdf->addpage(); 
     $pdf->SetFont('Arial','B',7);	 
    
     $pdf->Cell(15,$tam,"EMP",1,0,"C",1);
     $pdf->Cell(15,$tam,"DT.EMP.",1,0,"C",1);	 
     $pdf->Cell(55,$tam,"DOTAÇÃO",1,0,"C",1);
     $pdf->Cell(15,$tam,"DT.MOV.",1,0,"C",1);
     $pdf->Cell(40,$tam,"TIP0",1,0,"C",1);
     $pdf->Cell(20,$tam,"VALOR",1,0,"C",1);
     $pdf->Cell(0,$tam,'TOTAL/PARCIAL',1,1,"C",1);
     $pdf->cell(15,$tam,"CREDOR ",1,0,"C",1);
     $pdf->cell(0,$tam,"NOME",1,1,"C",1);
     if($com_mov == 's'){
       $pdf->Cell(0,$tam,"HISTÓRICO",1,1,"C",1);
     }
     if($mostraritem == 'm'){
       $pdf->Cell(10,$tam,"ITEM",1,0,"C",1);
       $pdf->Cell(75,$tam,"DESCRIÇÃO",1,0,"C",1);
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
  $pdf->Cell(40,$tam,$c53_descr,0,0,"C",$pre);
  $pdf->Cell(20,$tam,db_formatar($c70_valor,'f'),0,0,"C",$pre);
  if($c53_tipo == 20 || $c53_tipo == 21){
     $pdf->Cell(0,$tam,((($e60_vlremp - $e60_vlranu) == $c70_valor)?'TOTAL':'PARCIAL'),0,1,"C",$pre);
  }else{
     $pdf->Cell(0,$tam,'',0,1,"C",$pre);
  }
  $pdf->Cell(0,$tam,'CREDOR : '.$e60_numcgm.' - '.$z01_nome,0,1,"L",$pre);
  if($com_mov == 's'){
    $pdf->multiCell(0,$tam,'HISTÓRICO : '.$e60_resumo,0,"L",$pre);
    if($e60_destin != ''){
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
        $pdf->Cell(75,$tam,substr($pc01_descrmater,0,45),0,0,"L",$preenche);
        $pdf->Cell(10,$tam,db_formatar($e62_quant,'f'),0,0,"R",$preenche);
//        $pdf->Cell(20,$tam,db_formatar($e62_vltot,'f'),0,0,"R",$preenche);
        $pdf->multicell(0,$tam,substr($e62_descr,0,70),0,"L",$preenche);
      }
  }
  $pdf->cell(0,1,'',"B",1,"C",0);																																    
  if($c53_tipo == 10 || $c53_tipo == 11){
    if($c53_tipo == 10){
      $total_emp += $c70_valor;
    }else{
      $total_emp -= $c70_valor;
    }
  }elseif($c53_tipo == 20 || $c53_tipo == 21){
    if($c53_tipo == 20){
       $total_liq += $c70_valor;
    }else{
       $total_liq -= $c70_valor;
    }
  }elseif($c53_tipo == 30 || $c53_tipo == 31){
    if($c53_tipo == 30){
       $total_pag += $c70_valor;
    }else{
       $total_pag -= $c70_valor;
    }
  }
}
$pdf->SetFont('Arial','B',9);
if($total_emp > 0){
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(55,$tam,'TOTAL EMPENHADO',0,0,"L",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(40,$tam,'',0,0,"C",0);
   $pdf->Cell(20,$tam,db_formatar($total_emp,'f'),0,0,"C",0);
   $pdf->multiCell(0,$tam,'',0,"L",0);
}
if($total_liq > 0){
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(55,$tam,'TOTAL LIQUIDADO',0,0,"L",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(40,$tam,'',0,0,"C",0);
   $pdf->Cell(20,$tam,db_formatar($total_liq,'f'),0,0,"C",0);
   $pdf->multiCell(0,$tam,'',0,"L",0);
}
if($total_pag > 0){
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(55,$tam,'TOTAL PAGO',0,0,"L",0);
   $pdf->Cell(15,$tam,'',0,0,"C",0);
   $pdf->Cell(40,$tam,'',0,0,"C",0);
   $pdf->Cell(20,$tam,db_formatar($total_pag,'f'),0,0,"C",0);
   $pdf->multiCell(0,$tam,'',0,"L",0);
}

$pdf->output();

?>