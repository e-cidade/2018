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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_cgm_classe.php");
include("classes/db_empempenho_classe.php");
$clcgm = new cl_cgm;
$clempempenho = new cl_empempenho;

db_postmemory($_GET);

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$sql_seq = "";
if (@$numcgm!="") {
 
  if (isset($nota_fiscal) && $nota_fiscal != 'null') {
  
    $sql_seq = " and empnota.e69_numero = {$nota_fiscal} ";

  } elseif (@$tipo_consulta=="abertos" && @$tipo_consulta != 'null') {
   //ver abertos
   $sql_seq = " and empempenho.e60_vlrpag = 0 ";
  }
} 

$result  = $clempempenho->sql_record($clempempenho->sql_query_notas("","*","empempenho.e60_numemp DESC","empempenho.e60_numcgm = ".@$numcgm." ".@$sql_seq));

if($clempempenho->numrows == 0){
 ?>
 <script>
  alert("Você não possui Empenho para gerar o relatório.");
  window.close();
 </script>
 <?
 exit;
}
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head2 = "Relatório de Empenhos por CGM";
$head3 = "CGM:".$numcgm;
$pri = true;
$p = 0;
$flt_vlrcorr = 0;
$flt_juro    = 0;
$flt_multa   = 0;
$flt_desconto= 0;
for($x=0; $x < $clempempenho->numrows; $x++){
    db_fieldsmemory($result,$x);

    if (  ($pdf->gety() > $pdf->h -30)  || $pri==true ){
        $pdf->addpage();
        $pdf->setfillcolor(235);
        $pdf->setfont('arial','b',7);
        $pdf->cell(19,4,"Empenho",1,0,"C",1);
        $pdf->cell(19,4,"Dotação",1,0,"C",1);
        $pdf->cell(20,4,"Emissão",1,0,"C",1);
        $pdf->cell(19,4,"Ordem",1,0,"C",1);
        $pdf->cell(19,4,"Nº Lic.",1,0,"C",1);
        $pdf->cell(19,4,"NF",1,0,"C",1);
        $pdf->cell(19,4,"Valor Emp.",1,0,"C",1);
        $pdf->cell(19,4,"Valor Liq.",1,0,"C",1);
        $pdf->cell(19,4,"Valor Pago",1,0,"C",1);
        $pdf->cell(19,4,"Valor Anul.",1,1,"C",1);
        $pri = false;
    }
    
    $pdf->setfont('arial','',7);
    $pdf->cell(19,4,$e60_numemp,0,0,"R",$p);
    $pdf->cell(19,4,$e60_coddot,0,0,"R",$p);
    $pdf->cell(20,4,db_formatar($e60_emiss,"d"),0,0,"C",$p);
    $pdf->cell(19,4,$e60_codcom,0,0,"C",$p);
    $pdf->cell(19,4,$e60_numerol,0,0,"R",$p);
    $pdf->cell(19,4,$e69_numero,0,0,"R",$p);
    $pdf->cell(19,4,number_format($e60_vlremp, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(19,4,number_format($e60_vlrliq, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(19,4,number_format($e60_vlrpag, 2, ",", "." ),0,0,"R",$p);
    $pdf->cell(19,4,number_format($e60_vlranu, 2, ",", "." ),0,1,"R",$p);
    if($p == 0){
        $p = 1;
    }else{
        $p = 0;
    }
}

$pdf->Output();
?>