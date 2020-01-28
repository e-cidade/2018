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
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_pcorcamjulg_classe.php");
include("classes/db_pcorcamtroca_classe.php");
include("classes/db_empparametro_classe.php");
$clpcorcamitem = new cl_pcorcamitem;
$clpcorcamval = new cl_pcorcamval;
$clpcorcamjulg = new cl_pcorcamjulg;
$clpcorcamtroca = new cl_pcorcamtroca;
$clempparametro = new cl_empparametro;
$clrotulo = new rotulocampo;

$clpcorcamjulg->rotulo->label();
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("pc23_quant");
$clrotulo->label("pc23_vlrun");
$clrotulo->label("pc23_valor");
$clrotulo->label("pc23_obs");
db_postmemory($HTTP_POST_VARS);

$result_orcamitem = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_relmotivo(null,null,"distinct on (pc22_orcamitem) pc22_orcamitem,pc29_orcamitem,pc31_orcamitem","pc22_orcamitem","pc20_codorc = ".$orcam));
$numrows          = $clpcorcamjulg->numrows;
if($numrows == 0){
	db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum item encontrado com os dados selecionados.<br><br>Verifique se existem valores lançados para o orçamento ".$orcam.".");
}

$result_casadec = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec as casadec"));
if($clempparametro->numrows > 0){
  db_fieldsmemory($result_casadec,0);
}

db_fieldsmemory($result_orcamitem, 0);

$head2 = "Relatório de motivos para troca";
if(trim($pc29_orcamitem) != ""){
	$head4 = "Orçamento de solicitação de compras";
}else if(trim($pc31_orcamitem) != ""){
	$head4 = "Orçamento de processo de compras";
}
$head5 = "Número do orçamento: ".$orcam;

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

for($i=1; $i <= $numrows; $i++){
  if(($pdf->gety() > $pdf->h - 30) || ($troca != 0)){
    $pdf->setfont('arial','b',8);
		$pdf->cell(20,$alt,"Legenda:",0,0,"L",0);
		$pdf->cell(05,$alt,"",0,0,"L",0);

		$pdf->setfont('arial','ib',7);
		$pdf->cell(10,$alt,"Texto",0,0,"L",0);
    $pdf->setfont('arial','',8);
		$pdf->cell(50,$alt,"- Fornecedor com menor valor cotado",0,0,"L",0);
		$pdf->cell(10,$alt,"",0,0,"L",0);
		$pdf->setfont('arial','',7);
		$pdf->cell(10,$alt,"Texto",0,0,"L",0);
    $pdf->setfont('arial','',8);
		$pdf->cell(00,$alt,"Fornecedor atual",0,1,"L",0);

    $pdf->addpage();

    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,"Sequencial",1,0,"C",1);
    $pdf->cell(20,$alt,$RLpc01_codmater,1,0,"C",1);
    $pdf->cell(00,$alt,$RLpc01_descrmater,1,1,"C",1);

	  $pdf->cell(17,$alt,$RLz01_numcgm,1,0,"C",1);
	  $pdf->cell(60,$alt,$RLz01_nome  ,1,0,"C",1);
	  $pdf->cell(17,$alt,"Quantidade",1,0,"C",1);
	  $pdf->cell(17,$alt,"Val. Unit.",1,0,"C",1);
	  $pdf->cell(17,$alt,"Val. Total.",1,0,"C",1);
	  $pdf->MultiCell(00,$alt,"Observação",1,"C",1);

    $pdf->cell(00,$alt,"MOTIVOS PARA TROCAS",1,1,"L",1);
    $troca = 0;
  }

  $result_mostratroc = $clpcorcamtroca->sql_record($clpcorcamtroca->sql_query(null,"pc25_codtroca,pc25_motivo","pc25_codtroca","pc25_orcamitem=".$pc22_orcamitem));
  $numrows_troca     = $clpcorcamtroca->numrows;
  if ($numrows_troca > 0){
       $result_menorpreco  = $clpcorcamval->sql_record($clpcorcamval->sql_query_julg(null,null,"pc23_valor,pc23_quant,pc23_obs,pc23_vlrun,z01_numcgm,z01_nome","pc23_valor","pc23_orcamitem = ".$pc22_orcamitem." and pc24_orcamitem is not null"));
       $result_forneatual  = $clpcorcamval->sql_record($clpcorcamval->sql_query_julg(null,null,"pc23_valor,pc23_quant,pc23_obs,pc23_vlrun,z01_numcgm,z01_nome","","pc23_orcamitem = ".$pc22_orcamitem." and pc24_pontuacao=1"));

       if (trim($pc29_orcamitem) != "") {
            $result_pcmatersol = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmatersol($pc22_orcamitem,"pc11_codigo as codigo,pc01_codmater,pc01_descrmater"));
            if ($clpcorcamitem->numrows > 0) {
                 db_fieldsmemory($result_pcmatersol,0);
            }
       } elseif (trim($pc31_orcamitem) != "") {
            $result_pcmaterproc = $clpcorcamitem->sql_record($clpcorcamitem->sql_query_pcmaterproc($pc22_orcamitem,"pc81_codprocitem as codigo,pc01_codmater,pc01_descrmater"));
            if ($clpcorcamitem->numrows > 0) {
                 db_fieldsmemory($result_pcmaterproc,0);
            }
       }

       $pdf->cell(20,$alt,$codigo,0,0,"C",0);
       $pdf->cell(20,$alt,$pc01_codmater,0,0,"C",0);
       $pdf->cell(00,$alt,$pc01_descrmater,0,1,"L",0);

       db_fieldsmemory($result_menorpreco, 0);

       $pdf->setfont('arial','ib',7);
       $pdf->cell(17,$alt,$z01_numcgm,0,0,"C",0);
       $pdf->cell(60,$alt,$z01_nome  ,0,0,"L",0);
       $pdf->cell(17,$alt,db_formatar($pc23_quant,"f"),0,0,"R",0);
       $pdf->cell(17,$alt,db_formatar($pc23_vlrun,'v'," ",$casadec),0,0,"R",0);
       $pdf->cell(17,$alt,db_formatar($pc23_valor,"f"),0,0,"R",0);
       $pdf->MultiCell(00,$alt,$pc23_obs,0,"J",0);

       db_fieldsmemory($result_forneatual, 0);
   
       $pdf->setfont('arial','',7);
       $pdf->cell(17,$alt,$z01_numcgm,0,0,"C",0);
       $pdf->cell(60,$alt,$z01_nome  ,0,0,"L",0);
       $pdf->cell(17,$alt,db_formatar($pc23_quant,"f"),0,0,"R",0);
       $pdf->cell(17,$alt,db_formatar($pc23_vlrun,'v'," ",$casadec),0,0,"R",0);
       $pdf->cell(17,$alt,db_formatar($pc23_valor,"f"),0,0,"R",0);
       $pdf->MultiCell(00,$alt,$pc23_obs,0,"J",0);

       $pdf->setfont('arial','b',6);
       for ($ii=0; $ii < $numrows_troca; $ii++) {
             db_fieldsmemory($result_mostratroc, $ii);
             $pdf->MultiCell(00,$alt,db_formatar(($ii+1),'s','0',2,'e',0)." - ".$pc25_motivo,0,"J",0);
       }
       $pdf->ln(4);
  }
 
  if ($i < $numrows){ 
       db_fieldsmemory($result_orcamitem,$i);
  }
}
$pdf->setfont('arial','b',8);
$pdf->cell(20,$alt,"Legenda:",0,0,"L",0);
$pdf->cell(05,$alt,"",0,0,"L",0);

$pdf->setfont('arial','ib',7);
$pdf->cell(10,$alt,"Texto",0,0,"C",0);
$pdf->setfont('arial','',8);
$pdf->cell(00,$alt,"- Fornecedor com menor valor cotado",0,1,"L",0);

$pdf->cell(25,$alt,"",0,0,"L",0);

$pdf->setfont('arial','',7);
$pdf->cell(10,$alt,"Texto",0,0,"C",0);
$pdf->setfont('arial','',8);
$pdf->cell(00,$alt,"- Fornecedor atual",0,1,"L",0);
$pdf->Output();
?>