<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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


include("libs/db_liborcamento.php");
include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("fpdf151/assinatura.php");
include("classes/db_empresto_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_libcontabilidade.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;


$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
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
///////////////////////////////////////// Monta IN para filtro de Intiruição do SQL/////////////////////////////////////////

$sele_work = ' o.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  
////////////////////////////////////////////////////Cria Tabela de Filtro///////////////////////////////////////////////////  
/*
$xcampos = split("-",$orgaos);
$p_orgao ='';
$v='';
for($i=0;$i < sizeof($xcampos);$i++){
    $where = '';
    $virgula = ''; 
    $xxcampos = split("_",$xcampos[$i]);
    for($ii=0;$ii<sizeof($xxcampos);$ii++){
       if($ii > 0){
         $where .= $virgula.$xxcampos[$ii];
         $virgula = ', ';
       }
    }
    $p_orgao .= $v.$where;
    $v=',';

} 
*/
// echo "<br><br>".$p_orgao;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////     
   

$anousu  = db_getsession("DB_anousu");
if (!isset($perini)){
  $dt     = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
  $perini = "{$anousu}-01-01";
  $perfin = $dt[1];
}

$dataini = $perini;
$datafin = $perfin;
$xagrupa = "Órgão";
$grupoini = 1;
$grupofin = 3;
$where    = "";

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head2 = "INSTITUIÇÕES : ".$descr_inst;
$head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTARIA";
$head4 = "DEMONSTRATIVO DOS RESTOS A PAGAR POR PODER E ÓRGÃO";
$head5 = "ANEXO (9) EXERCÍCIO: ".db_getsession("DB_anousu");
$head7 = "PERÍODO : ".strtoupper(db_mes(substr($perini,5,2)))." A ".strtoupper(db_mes(substr($perfin,5,2)));

/////////////////////////////////////////////////     SQL    /////////////////////////////////////////////////////////////
$instit = ' e60_instit in ('.str_replace('-',', ',$db_selinstit).') ';

//$where = " and o58_orgao in ($p_orgao) ";
$order = '';
$sqlperiodo = $clempresto->sql_rp2(db_getsession("DB_anousu"), $instit, $perini, $perfin, $where,$order);

// db_criatabela(pg_exec($sqlperiodo));
// exit;

$sqlperiodo = "
  select * from (
     select    
	 e60_instit,
	 nomeinst,
	 o58_orgao,
         o40_descr,
	 /*e60_anousu, */
	 sum(case when e60_anousu < ($anousu - 1) then
           e91_vlrliq-e91_vlrpag	      
	 else 
	   0
	 end ) as  inscricao_ant,
         sum(case when e60_anousu = ($anousu - 1) then
           e91_vlrliq-e91_vlrpag	      
	 else 
	   0
	 end ) as  valor_processado,	 
         sum(coalesce(e91_vlremp,0)) as e91_vlremp, 
         sum(coalesce(e91_vlranu,0)) as e91_vlranu,
         sum(coalesce(e91_vlrliq,0)) as e91_vlrliq,
         sum(coalesce(e91_vlrpag,0)) as e91_vlrpag,
         sum(coalesce(vlranu,0)) as vlranu,
         sum(coalesce(canc_proc,0)) as canc_proc,
         sum(coalesce(canc_nproc,0)) as canc_nproc,
         sum(coalesce(vlrliq,0)) as vlrliq,
         sum(coalesce(vlrpag,0)) as vlrpag
     from ($sqlperiodo) as x
     group by e60_instit,nomeinst,o58_orgao,o40_descr
   ) as foo 
    order by e60_instit,o58_orgao
  ";

$result = pg_query($sqlperiodo);

// db_criatabela($result);
// exit;

/////////////////////////////////////////////// Abertura de PDF ////////////////////////////////////////////////////////////

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$alt = 4;
/////////////////////////////////////////////// Cabeçalho  /////////////////////////////////////////////////////////////

$pdf->addpage();
$pdf->setfont('arial','',4);
$pdf->ln(2);

$pdf->cell(01,$alt,'LRF, Art. 52, Inciso V - Anexo IX',"B",0,"L",0);
$pdf->cell(190,$alt,'R$ Unidades',"B",1,"R",0);
$pdf->setfont('arial','',6);
$pdf->cell(60,$alt,"",0,0,"C",0);
$pdf->cell(75,$alt,"RESTOS A PAGAR PROCESSADOS","LRTB",0,"C",0);
$pdf->cell(60,$alt,"RESTOS A PAGAR NÃO PROCESSADOS","TB",1,"C",0);

$pdf->cell(60,$alt,"",0,0,"C",0);
$pdf->cell(30,$alt,"Inscritos","TBLR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"Inscritos","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","L",1,"C",0);

$pdf->cell(60,$alt,"PODER/ÓRGÃO",0,0,"C",0);
$pdf->cell(15,$alt,"Em","LR",0,"C",0);
$pdf->cell(15,$alt,"Em 31 de","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"em 31 de","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","LR",0,"C",0);
$pdf->cell(15,$alt,"","L",1,"C",0);

$pdf->cell(60,$alt,"",0,0,"C",0);
$pdf->cell(15,$alt,"Exercícios","LR",0,"C",0);
$pdf->cell(15,$alt,"dezembro de","LR",0,"C",0);
$pdf->cell(15,$alt,"Cancelados","LR",0,"C",0);
$pdf->cell(15,$alt,"Pagos","LR",0,"C",0);
$pdf->cell(15,$alt,"A Pagar","LR",0,"C",0);
$pdf->cell(15,$alt,"dezembro de","LR",0,"C",0);
$pdf->cell(15,$alt,"Cancelados","LR",0,"C",0);
$pdf->cell(15,$alt,"Pagos","LR",0,"C",0);
$pdf->cell(15,$alt,"A Pagar","L",1,"C",0);

$pdf->cell(60,$alt,"","B",0,"C",0);
$pdf->cell(15,$alt,"Anteriores","LRB",0,"C",0);
$pdf->cell(15,$alt,db_getsession("DB_anousu") - 1,"LRB",0,"C",0);
$pdf->cell(15,$alt,"","LRB",0,"C",0);
$pdf->cell(15,$alt,"","LRB",0,"C",0);
$pdf->cell(15,$alt,"","LRB",0,"C",0);
$pdf->cell(15,$alt,db_getsession("DB_anousu") - 1,"LRB",0,"C",0);
$pdf->cell(15,$alt,"","LRB",0,"C",0);
$pdf->cell(15,$alt,"","LRB",0,"C",0);
$pdf->cell(15,$alt,"","LB",1,"C",0);

$i = 0;
$soma1  = 0;
$soma2  = 0;
$soma3  = 0;
$soma4  = 0;
$soma5  = 0;
$soma6  = 0;
$soma7  = 0;
$soma8  = 0;
$soma9  = 0;
$soma10 = 0;
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$instit= '';

// totais processados
$tot_01 =0;
$tot_02 =0;
$tot_03 =0;
$tot_04 =0;
$tot_05 =0;
$tot_06 =0;
$tot_07 =0;
$tot_08 =0;
$tot_09 =0;

//
$a_pagar_processado     = 0;
$a_pagar_nao_processado = 0;

for ($x=0;$x<pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);


     if ($instit != $e60_instit){
         $instit = $e60_instit;
	 
         $pdf->setfont('arial','b',6);
         $pdf->cell(60,$alt,$nomeinst, "0", 0, "L", 0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         // não processados
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"LR",0,"R",0);
         $pdf->cell(15,$alt,'',"L",1,"R",0);
         $pdf->setfont('arial','',6);
     }
     // -----------------------------------------------------    
     // valores de inscrição
     $valor_nao_processado = $e91_vlremp-$e91_vlranu-$e91_vlrliq;
     
     // calculo dos pagtos
     // todos valores pagos são processados
     /*
     if ($vlrpag > ($valor_processado +  $inscricao_ant + $vlrliq )){
        // pagamento nao processado
	$pago_processado     = $vlrliq;
	$pago_nao_processado = $vlrpag - $vlrliq; 
     } else {
        // pagamento processado
        $pago_processado     = $vlrpag;
	$pago_nao_processado = 0;
     }  
     if ($pago_processado > ($inscricao_ant + $valor_processado)) {
	 $pago_nao_processado = $pago_processado - ($inscricao_ant + $valor_processado);
         $pago_processado     = $inscricao_ant + $valor_processado;
     }  
     $a_pagar_processado     = ($valor_processado+$inscricao_ant) - $pago_processado;
     $a_pagar_nao_processado = $valor_nao_processado-$vlranu-$pago_nao_processado;
     */

     $a_pagar_processado     = ($valor_processado+$inscricao_ant) - $canc_proc - $vlrpag;
     $a_pagar_nao_processado = $valor_nao_processado - $canc_nproc;


     // ----------------------------------------------------- 

     $pdf->cell(60,$alt,$o58_orgao .'-'.substr($o40_descr,0,42), "R", 0, "L", 0);
     // anterior ao exercicio de inscrição
     $pdf->cell(15,$alt,db_formatar($inscricao_ant,'f'),"LR",0,"R",0);      // processados
     $pdf->cell(15,$alt,db_formatar($valor_processado,'f'),"R",0,"R",0);    // o cancelamento sempre ocorre com os não liquidados     // porque sempre ocorre o estorno de liquidação para depois o estorno de rp
     
     
     $pago_nao_processado = 0;
     if($a_pagar_processado < 0){
       $a_pagar_processado *= -1;
       $pago_nao_processado = $a_pagar_processado ;
       $vlrpag = $vlrpag - $a_pagar_processado;
       $a_pagar_processado = 0; 
     }
     
     $valor_novo =  $valor_nao_processado-$canc_nproc-$pago_nao_processado;
     
     if( ($valor_novo) < 0 ){;
       $canc_proc = $canc_proc + (($valor_novo) * -1 );
       $a_pagar_processado = ($a_pagar_processado - (($valor_novo) * -1 ));
      
       $canc_nproc = ( $canc_nproc - (($valor_novo) * -1 ));
       $valor_novo = 0;
     
     }
     
     $pdf->cell(15,$alt,db_formatar($canc_proc,'f'),"R",0,"R",0);
     
     
     
     
     $pdf->cell(15,$alt,db_formatar($vlrpag,'f'),"R",0,"R",0);
     $pdf->cell(15,$alt,db_formatar($a_pagar_processado,'f'),"R",0,"R",0);     // não processados
     
     $pdf->cell(15,$alt,db_formatar($valor_nao_processado,'f'),"R",0,"R",0);
     $pdf->cell(15,$alt,db_formatar($canc_nproc,'f'),"R",0,"R",0);
     $pdf->cell(15,$alt,db_formatar($pago_nao_processado,'f'),"R",0,"R",0);
     
     $pdf->cell(15,$alt,db_formatar($valor_novo,'f'),"0",1,"R",0);
     
     $pdf->setfont('arial','',6);
     $i++; 


     $tot_01 += $inscricao_ant; 
     $tot_02 += $valor_processado ;
     $tot_03 += $canc_proc ;
     $tot_04 += $vlrpag ;
     $tot_05 += $a_pagar_processado ;
     // totais não processados
     $tot_06 += $valor_nao_processado;
     $tot_07 += $canc_nproc;
     $tot_08 += $pago_nao_processado;
     $tot_09 += $valor_nao_processado-$canc_nproc-$pago_nao_processado;


}

// totais
$pdf->setfont('arial','b',6);
$pdf->cell(60,$alt,'TOTAL', "RTB", 0, "L", 0);
$pdf->cell(15,$alt,db_formatar($tot_01,'f'),"LRBT",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_02,'f'),"LRTB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_03,'f'),"LTRB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_04,'f'),"LRTB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_05,'f'),"LTRB",0,"R",0);
 // não processados
$pdf->cell(15,$alt,db_formatar($tot_06,'f'),"TLRB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_07,'f'),"LRTB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_08,'f'),"LRTB",0,"R",0);
$pdf->cell(15,$alt,db_formatar($tot_09,'f'),"LTB",1,"R",0);
 
$pdf->ln();

notasExplicativas(&$pdf, 28, "{$periodo}", 190);

$pdf->ln();

assinaturas(&$pdf,&$classinatura,'LRF');



$pdf->Output();


?>