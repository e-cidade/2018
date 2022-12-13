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

set_time_limit(0);
include("libs/db_sql.php");
include("fpdf151/pdf.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$pdf = new pdf();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELATÓRIO DE PROMITENTES";
$linha = 60;
$pdf->SetFillColor(220);
$TPagina = 40;
if(isset($promi) && $promi != ""){
  if($promi == "pp"){
    $where = " where principal > 0";
  }elseif($promi == "ps"){
    $where = " where secundario > 0";
  }elseif($promi == "ts"){
    $where = "";
  }
}
if(isset($ordem) && $ordem != ""){
  if($ordem == "mt"){
    $ordem = " order by j41_matric";
  }elseif($ordem == "qp"){
    $ordem = " order by principal";
  }elseif($ordem == "qs"){
    $ordem = " order by secundario";
  }
}
$modo = "";
if(isset($order) && $order != ""){
  if($order == "asc"){
    $modo = " asc ";
  }elseif($order == "desc"){
    $modo = " desc ";
  }
}
if(isset($contrato) && $contrato != ""){
  if($where != ""){
    $and = " and ";
  }
  if($contrato == "ts"){
    $contrato = "";
  }elseif($contrato == "f"){
    $contrato = " $and j41_promitipo = 'S' ";
  }elseif($contrato == "t"){
    $contrato = " $and j41_promitipo = 'C' ";
  }
}
$where .= $contrato;
$sql = "
select z.*, proprietario_nome.proprietario from (
  select case when x.j41_matric is null then y.j41_matric else x.j41_matric end as j41_matric,
         case when x.j41_promitipo is null then y.j41_promitipo else x.j41_promitipo end as j41_promitipo,
	 case when principal is null then 0 else principal end as principal, 
	 case when secundario is null then 0 else secundario end as secundario from 
	  (select j41_matric,j41_promitipo, count(j41_tipopro) as principal from promitente where j41_tipopro is true group by j41_matric,j41_promitipo) as x 
  full join 
	  (select j41_matric,j41_promitipo, count(j41_tipopro) as secundario from promitente where j41_tipopro is false group by j41_matric,j41_promitipo) as y 
  on x.j41_matric = y.j41_matric) as z
inner join proprietario_nome on proprietario_nome.j01_matric = z.j41_matric $where $ordem $modo  
";
$result = pg_exec($sql);
$num = pg_numrows($result);
$pdf->AddPage();
$pdf->SetFont('Arial','B',7);
$pdf->Cell(25,05,"Matrícula",1,0,"C",1);
$pdf->Cell(20,05,"Principal",1,0,"C",1);
$pdf->Cell(20,05,"Secundário",1,0,"C",1);
$pdf->Cell(100,05,"Proprietário",1,0,"C",1);
$pdf->Cell(20,05,"Contrato",1,1,"C",1);
$prin = 0;
$sec = 0;
if($resumido == 't'){
  for($s=0;$s<pg_numrows($result);$s++){
    db_fieldsmemory($result,$s);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(25,05,$j41_matric,1,0,"C",0);
    $pdf->Cell(20,05,$principal,1,0,"C",0);
    $pdf->Cell(20,05,$secundario,1,0,"C",0);
    $pdf->Cell(100,05,$proprietario,1,0,"L",0);
    $pdf->Cell(20,05,$j41_promitipo,1,1,"L",0);
    $linha += 1;
    $prin += ($principal!=0?$principal:"");
    $sec += ($secundario!=0?$secundario:"");
    if ( $pdf->GetY() > 270) {
      $pdf->AddPage();
    }
  }
}elseif($resumido == 'f'){
  for($s=0;$s<pg_numrows($result);$s++){
    db_fieldsmemory($result,$s);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(25,05,$j41_matric,1,0,"C",1);
    $pdf->Cell(20,05,$principal,1,0,"C",1);
    $pdf->Cell(20,05,$secundario,1,0,"C",1);
    $pdf->Cell(100,05,$proprietario,1,0,"L",1);
    $pdf->Cell(20,05,$j41_promitipo,1,1,"C",1);
    $resultp = pg_query("SELECT z01_nome,j41_tipopro from promitente inner join cgm on z01_numcgm = j41_numcgm where j41_matric = $j41_matric");
    for($i=0;$i<pg_numrows($resultp);$i++){
      db_fieldsmemory($resultp,$i);
      if($j41_tipopro == 't'){
        $pdf->Cell(25,05,"PRINCIPAL",1,0,"L",0);
        $pdf->Cell(160,05,($j41_tipopro=="t"?$z01_nome:""),1,1,"L",0);
      }
      if($j41_tipopro == 'f'){
        $pdf->Cell(25,05,"SECUNDÁRIO",1,0,"L",0);
        $pdf->Cell(160,05,($j41_tipopro=="f"?$z01_nome:""),1,1,"L",0);
      }
    }
    $linha += 1;
    $prin += ($principal!=0?$principal:"");
    $sec += ($secundario!=0?$secundario:"");
    if ( $pdf->GetY() > 270) {
      $pdf->AddPage();
    }
  }
}
$pdf->Cell(185,4,"TOTAIS",1,1,"C",1);
$pdf->Cell(25,05,"".$num,1,0,"C",0);
$pdf->Cell(20,05,"".$prin,1,0,"C",0);
$pdf->Cell(20,05,"".$sec,1,0,"C",0);
$pdf->Cell(120,05,"",1,1,"C",0);
$pdf->Output();
?>