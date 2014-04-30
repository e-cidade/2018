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

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
// trecho para relatorio
$head1 = "Texto numero 1";
$head2 = "Texto numero 2";
$head3 = "Texto numero 3";
$head4 = "Texto numero 4";
//$head5 = "Texto numero 5";
$head6 = "Texto numero 6";
$head7 = "Texto numero 7";
$head8 = "Texto numero 8";
$head9 = "Texto numero 9";
$head10 = "Texto numero 10";
include("fpdf151/pdf.php");
// trecho para relatorio
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
// Dados
  $sql = "select slip.*,z01_numcgm , z01_nome , p1.c01_descr as descr_debito, p2.c01_descr as descr_credito, c03_codigo as db_hist, c03_descr as descr_hist
		  from slip
		       left outer join slipnum on slip.k17_codigo = slipnum.k17_codigo
		       left outer join cgm on slipnum.k17_numcgm = cgm.z01_numcgm
		       left outer join plano p1 on slip.k17_debito = p1.c01_reduz and p1.c01_anousu = ".db_getsession('DB_anousu')."
		       left outer join plano p2 on slip.k17_credito = p2.c01_reduz and p2.c01_anousu = ".db_getsession('DB_anousu')."
		       left outer join hist on slip.k17_hist = hist.c03_codigo and c03_anousu = ".db_getsession('DB_anousu')."
          where slip.k17_codigo = $numslip and k17_instit = " . db_getsession('DB_instit');
$dados = pg_exec($sql);
if(pg_numrows($dados)==0){
   echo "<script>
         alert('Documento de Slip não Cadastrado.');
         window.close();
         </script>";
}

$pdf->Image("imagens/forms/cai1_slip.jpeg",0 ,38 , 210);
$pdf->SetFont('Arial','B',14);
$Y=49;
$pdf->Text(42,$Y,pg_result($dados,0,"k17_codigo"));
$pdf->Text(130,$Y,pg_result($dados,0,"k17_valor"));
$pdf->Text(83,$Y+7,"valor extenso 1");
$pdf->Text(42,$Y+11,pg_result($dados,0,"k17_data"));
$pdf->Text(83,$Y+12,"valor extenso 2");

$pdf->Text(42,$Y+28,pg_result($dados,0,"k17_debito"));
$pdf->Text(50,$Y+28,pg_result($dados,0,"descr_debito"));

$pdf->Text(42,$Y+41,pg_result($dados,0,"k17_credito"));
$pdf->Text(50,$Y+41,pg_result($dados,0,"descr_credito"));

$pdf->SetFont('Arial','B',12);
$pdf->Text(10,$Y+61,"Interessado:");
$pdf->Text(40,$Y+61,pg_result($dados,0,"z01_nome"));
$pdf->Text(10,$Y+66,"Histórico:");
$pdf->Text(40,$Y+66,pg_result($dados,0,"k17_texto"));
$pdf->Text(10,$Y+81,pg_result($dados,0,"k17_hist"));
$pdf->Text(15,$Y+81,"-");
$pdf->Text(17,$Y+81,pg_result($dados,0,"descr_hist"));

$pdf->Image("imagens/forms/cai1_slip.jpeg",0 ,158, 210);

$Y=169;
$pdf->SetFont('Arial','B',14);
$pdf->Text(42,$Y,pg_result($dados,0,"k17_codigo"));
$pdf->Text(130,$Y,pg_result($dados,0,"k17_valor"));
$pdf->Text(83,$Y+7,"valor extenso 1");
$pdf->Text(42,$Y+11,pg_result($dados,0,"k17_data"));
$pdf->Text(83,$Y+12,"valor extenso 2");

$pdf->Text(42,$Y+28,pg_result($dados,0,"k17_debito"));
$pdf->Text(50,$Y+28,pg_result($dados,0,"descr_debito"));

$pdf->Text(42,$Y+41,pg_result($dados,0,"k17_credito"));
$pdf->Text(50,$Y+41,pg_result($dados,0,"descr_credito"));

$pdf->SetFont('Arial','B',12);
$pdf->Text(10,$Y+61,"Interessado:");
$pdf->Text(40,$Y+61,pg_result($dados,0,"z01_nome"));
$pdf->Text(10,$Y+66,"Histórico:");
$pdf->Text(40,$Y+66,pg_result($dados,0,"k17_texto"));
$pdf->Text(10,$Y+81,pg_result($dados,0,"k17_hist"));
$pdf->Text(15,$Y+81,"-");
$pdf->Text(17,$Y+81,pg_result($dados,0,"descr_hist"));



// trecho para relatorio
$pdf->Output();
// Dados
?>