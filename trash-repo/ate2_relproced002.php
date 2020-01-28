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


require ('fpdf151/pdf.php');
include("classes/db_db_sysmodulo_classe.php");
$total2 = 0;
$cl_db_sysmodulo = new cl_db_sysmodulo;

db_postmemory($HTTP_POST_VARS);

//echo "rel 2 ......<br> data = $data  <br> data1 = $data1 ";
$pdf = new PDF(); // abre a classe
$head1 = "RELATÓRIO DE ATENDIMENTOS  PROCEDIMENTOS";
$head2 = "PERÍODO: ".db_formatar($data,'d')." à ".db_formatar($data1,'d');
$Letra = 'arial';
$pdf->SetFont($Letra,'B',8);
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage("L"); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(235);

// MOSTIVOS
$sqlmot= "select  distinct at54_nome,at54_sequencial
from atenditem
inner join atendimento on at02_codatend = at05_codatend
inner join atenditemsyscadproced on at29_atenditem = at05_seq 
inner join atenditemmotivo on at34_atenditem = at05_seq
inner join atenditemmod on at22_codatend=at02_codatend and at22_atenditem = at05_seq     
inner join db_syscadproced on codproced = at29_syscadproced
inner join tarefacadmotivo on at54_sequencial = at34_tarefacadmotivo
where at02_datafim<='$data1' and at02_datafim>='$data'
order by  at54_nome";
$resultmot= pg_query($sqlmot) or die($sqlmot);
$linhasmot=pg_num_rows($resultmot);

$pdf->Cell(65,6,"MÓDULOS",0,0,"C",1);

if ($linhasmot > 0) {
  $colunas = (195/$linhasmot);
  for($m=0;$m < $linhasmot;$m++) {
    db_fieldsmemory($resultmot,$m);
    $pdf->Cell($colunas,6,$at54_nome,0,0,"C",1);
  }
}
$pdf->Cell(20,6,"TOTAL",0,1,"C",1);

// MODULOS
$sqlmod = "select distinct nomemod as nome_modulo,db_sysmodulo.codmod as id_item
from atenditem
inner join atendimento on at02_codatend = at05_codatend
inner join atenditemsyscadproced on at29_atenditem = at05_seq 
inner join atenditemmod on at22_codatend=at02_codatend and at22_atenditem = at05_seq     
inner join db_syscadproced on codproced = at29_syscadproced
inner join db_sysmodulo on at22_modulo = db_sysmodulo.codmod
where at02_datafim<='$data1' and at02_datafim>='$data'
order by nomemod ";
$resultmodulo = $cl_db_sysmodulo->sql_record($sqlmod);
$linhas =  $cl_db_sysmodulo->numrows;

$totalprocedgeral = array();
$colunas = (195/$linhasmot);
for($m=0;$m < $linhasmot;$m++) {
	db_fieldsmemory($resultmot,$m);
	$totalprocedgeral[$at54_sequencial]=0;
}

if($linhas >0){
  
  for($x=0;$x < $linhas;$x++) {
    db_fieldsmemory($resultmodulo,$x);
    
    $posicao = $pdf->getY();
    $pdf->SetFont($Letra,'B',9);
    $pdf->SetFillColor(225);

		if ($tipo == "a") {
			$pdf->Cell(280,6," ",0,1,"L",0);
			$pdf->Cell(65,6,$nome_modulo,0,0,"L",1);
		}

		$totalproced = array();
		$colunas = (195/$linhasmot);
		for($m=0;$m < $linhasmot;$m++) {
			db_fieldsmemory($resultmot,$m);
		  if ($tipo == "a") {
			  $pdf->Cell($colunas,6,$at54_nome,0,0,"C",1);
			}
			$totalproced[$at54_sequencial]=0;
		}
		if ($tipo == "a") {
		  $pdf->Cell(20,6,"TOTAL",0,1,"C",1);
		}

    // PROCEDIMENTOS
    $sqlproced = "select distinct on (descrproced) at02_dataini,at02_datafim,at05_codatend,at05_seq,codproced ,descrproced,at22_modulo
    from atenditem
    inner join atendimento on at02_codatend = at05_codatend
    inner join atenditemsyscadproced on at29_atenditem = at05_seq 
    inner join atenditemmod on at22_codatend=at02_codatend and at22_atenditem = at05_seq     
    inner join db_syscadproced on codproced = at29_syscadproced
    where at22_modulo = $id_item
    and at02_datafim<='$data1' and at02_datafim>='$data'
    order by  descrproced";
    
    $resultproced = pg_query($sqlproced);
    $linhasproced = pg_num_rows($resultproced);
    if ($linhasproced>0){
      $total1 = 0 ;
      for($p=0;$p < $linhasproced;$p++) {
        db_fieldsmemory($resultproced,$p);
        
				if ($tipo == "a") {
					$pdf->SetFont($Letra,'',9);
					$pdf->Cell(65,6,substr($descrproced,0,30),1,0,"L",0);
				}
        
        // OS MOTIVOS
        $sqlmot1= "select  distinct at54_nome,at54_sequencial
        from atenditem
        inner join atendimento on at02_codatend = at05_codatend
        inner join atenditemsyscadproced on at29_atenditem = at05_seq 
        inner join atenditemmotivo on at34_atenditem = at05_seq
        inner join atenditemmod on at22_codatend=at02_codatend and at22_atenditem = at05_seq     
        inner join db_syscadproced on codproced = at29_syscadproced
        inner join tarefacadmotivo on at54_sequencial = at34_tarefacadmotivo
        where at02_datafim<='$data1' and at02_datafim>='$data'
        order by  at54_nome";
        $resultmot1= pg_query($sqlmot1) or die($sqlmot1);
        $linhasmot1=pg_num_rows($resultmot1);
				
        $quant = 0;
        $total = 0;
				
        if ($linhasmot1 > 0) {
					
          for($i=0;$i < $linhasmot1;$i++) {
            db_fieldsmemory($resultmot1,$i);
            
            // PEGAR A QUANTIDADE DE CADA PROCEDIMENTO
            $sqlmoti= "
            select count(*) as quant
            from atenditem
            inner join atendimento on at02_codatend = at05_codatend
            inner join atenditemsyscadproced on at29_atenditem = at05_seq 
            inner join atenditemmotivo on at34_atenditem = at05_seq
            inner join atenditemmod on at22_codatend=at02_codatend and at22_atenditem = at05_seq     
            inner join db_syscadproced on codproced = at29_syscadproced
            inner join tarefacadmotivo on at54_sequencial = at34_tarefacadmotivo
            where at02_datafim<='$data1' and at02_datafim>='$data'
            and at54_sequencial = $at54_sequencial 
            and codproced = $codproced";
            $resultmoti=pg_query($sqlmoti);
            db_fieldsmemory($resultmoti,0);
            //die($sqlmot);
						if ($tipo == "a") {
              $pdf->Cell($colunas,6,$quant,1,0,"C",0);
						}
            $total += $quant;
            //$quant += $quant;

            $totalproced[$at54_sequencial]+=$quant;
            $totalprocedgeral[$at54_sequencial]+=$quant;
						
          }
          
          $total1 += $total;
				  if ($tipo == "a") {
            $pdf->Cell(20,6,db_formatar($total, 's'),1,1,"R",0);
					}
          
        }
        
        $total2 += $total;
        
      }
      
			if ($tipo == "a") {
				$pdf->Cell(65,6,"",1,0,"C",0);
			} else {
				$pdf->Cell(65,6,$nome_modulo,1,0,"L",0);
			}

			$colunas = (195/$linhasmot);
			for($m=0;$m < $linhasmot;$m++) {
				db_fieldsmemory($resultmot,$m);
				$pdf->Cell($colunas,6,db_formatar($totalproced[$at54_sequencial], 's'),1,0,"C",0);
			}
      $pdf->Cell(20,6,db_formatar($total1, 's'),1,1,"R",0);
			
    }
    
  }
  
	$pdf->ln();

  $pdf->Cell(65,6,"TOTAL GERAL: ",1,0,"R",0);

	$colunas = (195/$linhasmot);
	for($m=0;$m < $linhasmot;$m++) {
		db_fieldsmemory($resultmot,$m);
		$pdf->Cell($colunas,6,db_formatar($totalprocedgeral[$at54_sequencial], 's'),1,0,"C",0);
	}
  $pdf->Cell(20,6,db_formatar($total2, 's'),1,1,"R",0);
  
}

$pdf->output();

?>