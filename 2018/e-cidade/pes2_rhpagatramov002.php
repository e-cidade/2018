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

include("fpdf151/pdf.php");
include("libs/db_libpessoal.php");
include("classes/db_rhpagatra_classe.php");
include("classes/db_rhpagocor_classe.php");
include("classes/db_rhpessoal_classe.php");
$clrhpagatra = new cl_rhpagatra;
$clrhpagocor = new cl_rhpagocor;
$clrhpessoal = new cl_rhpessoal;
$clrhpagatra->rotulo->label();
$clrhpagocor->rotulo->label();
$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "RELATÓRIO DE PAGAMENTO DE ATRASADOS";
$head5 = "DATA DE PAGAMENTO: ".db_formatar($datai,"d");

$dbwhere = "";
if($paga == 1){
	$dbwhere = " rh61_regist is null and ";
}

$dbwheredatas = " and rh58_data = '".$datai."' ";
if(trim($dataf) != "--"){
	$head5.= " a ".db_formatar($dataf,"d");
	$dbwheredatas = " and rh58_data between '".$datai."' and '".$dataf."' ";
}

$dbwhere.= " 
             rh58_tipoocor = ".$tipo." ".$dbwheredatas."
             group by rh01_regist,
                      z01_nome,
                      z01_numcgm
           ";

$sql = $clrhpagocor->sql_query_notjustica(
                                          null,
                                          "
                                           rh01_regist,
                                           z01_nome,
                                           z01_numcgm,
                                           sum(rh58_valor) as valor
                                          ",
                                          "z01_nome",
                                          $dbwhere,
                                          true,
                                          null,
                                          null,
                                          db_anofolha(),
                                          db_mesfolha()
                                         );

$result_atrasados = $clrhpagocor->sql_record($sql);
$numrows_atrasados = $clrhpagocor->numrows;
if($numrows_atrasados == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Verifique os dados informados, nenhum pagamento encontrado.');
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$entrar = true;
$alt = 4;

$total_geral = 0;
$quant_geral = 0;

for($x=0; $x<$numrows_atrasados; $x++){
  db_fieldsmemory($result_atrasados,$x);

  if($pdf->gety() > $pdf->h - 30 || $entrar == true){
		$pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(15,$alt,$RLrh01_regist,1,0,"C",1);
    $pdf->cell(15,$alt,$RLz01_numcgm,1,0,"C",1);
    $pdf->cell(80,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(20,$alt,"Valor pago",1,1,"C",1);
    $pdf->setfont('arial','',7);
		$entrar = false;
  }
  $pdf->cell(15,$alt,$rh01_regist,1,0,"C",0);
  $pdf->cell(15,$alt,$z01_numcgm,1,0,"C",0);
  $pdf->cell(80,$alt,$z01_nome,1,0,"L",0);
  $pdf->cell(20,$alt,db_formatar($valor,"f"),1,1,"R",0);

  $quant_geral ++;
	$total_geral += $valor;

}

$pdf->ln(1);
$pdf->cell(60,$alt,"Totalização ","LTB",0,"L",1);
$pdf->cell(50,$alt,$quant_geral." funcionários pagos, saldo total de R$","TB",0,"R",1);
$pdf->cell(20,$alt,db_formatar($total_geral,"f"),"RTB",1,"R",1);

$pdf->Output();
?>