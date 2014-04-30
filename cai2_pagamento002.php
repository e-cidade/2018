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

//include("libs/db_stdlib.php");
//    echo 'ala pucha';
include("fpdf151/pdf.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$where    = "";
$and      = "";
$br       = "";
$MSGhead3 = "PAGAMENTO POR CONTA/PERÍODO";
$MSGhead5 = "TODAS AS CONTAS";
$MSGhead6 = "PERÍODO NÃO INFORMADO";
$msg_ERRO = "";
if(isset($conta) && trim($conta)!="" && $conta!=0){
  $result_contsaltes = pg_query("select k13_descr from saltes where k13_conta=$conta");
  if(pg_numrows($result)>0 ){
    db_fieldsmemory($result_contsaltes,0);
  }
  $msg_ERRO = $br." - Conta: ".$conta." (".$k13_descr.") ";  
  $MSGhead5 = "Conta: ".$conta." (".$k13_descr.")";  
  $where .= " k00_conta=".$conta." ";  
  $and    = " and ";
  $br     = "<br>";
}
$DATAi = false;
$DATAf = false;
if(isset($datai) && trim($datai)!=""){
  $DATAi = true;
}
if(isset($dataf) && trim($dataf)!=""){
  $DATAf = true;
}
//die("$MSGhead5");

if($DATAi==true && $DATAf==true){
  $msg_ERRO .= $br." - Período entre ".db_formatar($datai,"d")." e ".db_formatar($dataf,"d")." ";
  $MSGhead6 = "Período entre ".db_formatar($datai,"d")." e ".db_formatar($dataf,"d");
  $where .= $and." k00_dtpaga between '".$datai."' and '".$dataf."' ";
  $and    = " and ";  
  $br     = "<br>";
}else if($DATAi==true){
  $msg_ERRO .= $br." - Período posterior a ".db_formatar($datai,"d")." ";
  $MSGhead6 = "Período posterior a ".db_formatar($datai,"d");
  $where .= $and." k00_dtpaga >= '".$datai."' ";
  $and    = " and ";
  $br     = "<br>";
}else if($DATAf==true){
  $msg_ERRO .= $br." - Período anterior a ".db_formatar($dataf,"d")." ";
  $MSGhead6 = "Período anterior a ".db_formatar($dataf,"d");
  $where .= $and." k00_dtpaga <= '".$dataf."' ";
  $and    = " and ";
  $br     = "<br>";
}
//die($where);

$sql = "
        select arrepaga.k00_conta,saltes.k13_descr,sum(arrepaga.k00_valor) as k00_valor
        from arrepaga
             inner join saltes on saltes.k13_conta = arrepaga.k00_conta
        where 1=1 $and $where
	group by arrepaga.k00_conta,saltes.k13_descr
     ";
//die($sql);
$result_somaval = pg_query($sql);
$numrows = pg_numrows($result_somaval);
if($numrows == 0){
  db_redireciona("db_erros.php?fechar=true&db_erro=Não existem valores com os seguintes dados informados:<br> $msg_ERRO");
}
//db_fieldsmemory($result_somaval,0);

$head3 = $MSGhead3;
$head5 = $MSGhead5;
$head6 = $MSGhead6;


$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);
$pdf->SetFont('Arial','B',6);

$total = 0;

for($i=0;$i<$numrows;$i++){
   db_fieldsmemory($result_somaval,$i);
   if($pdf->gety() > ($pdf->h - 30) || $i == 0){
     if($pdf->gety() > ($pdf->h - 30)){
       $pdf->addpage();
     }
     $pdf->SetFont('Arial','B',6);
     $pdf->Cell(15,4,"CONTA",1,0,"C",1);
     $pdf->Cell(60,4,"DESCRIÇÃO",1,0,"C",1);
     $pdf->Cell(5,4,"","LTB",0,"C",1);
     $pdf->Cell(20,4,"VALOR","RTB",1,"C",1);
     $pdf->SetFont('Arial','',6);
   }

   $pdf->Cell(15,4,$k00_conta,1,0,"C",0);
   $pdf->Cell(60,4,$k13_descr,1,0,"L",0);
   $pdf->Cell(5,4,"R$","LTB",0,"C",0);
   $pdf->Cell(20,4,db_formatar($k00_valor,"f"),"RTB",1,"R",0);
   $total++;
}
$pdf->SetFont('Arial','B',6);
$pdf->Cell(100,4,"Total  : ".$total." Registros",0,1,"L",0);

$pdf->Output();
?>