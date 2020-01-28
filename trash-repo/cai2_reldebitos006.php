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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_projmelhorias_classe.php");
$clprojmelhorias = new cl_projmelhorias;

$clrotulo = new rotulocampo;
$clrotulo->label('vlrhis');
$clrotulo->label('vlrcor');
$clrotulo->label('multa');
$clrotulo->label('juros');
$clrotulo->label('desconto');
$clrotulo->label('k01_descr');
$clrotulo->label('k02_descr');
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere = "";
$dtini =  $dtini_ano."-".$dtini_mes."-".$dtini_dia; // operacao
$dtfim =  $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;
$dataini =  $dataini_ano."-".$dataini_mes."-".$dataini_dia; // vencimento
$datafim =  $datafim_ano."-".$datafim_mes."-".$datafim_dia;
$instit = db_getsession("DB_instit");

$dtoper = "";
$and = "";
if($dtini != "--" && $dtfim == "--"){
  $dbwhere .= " k22_dtoper >= '$dtini' ";
  $and = " and ";
}elseif($dtini != "--" && $dtfim != "--"){
  $dbwhere .= " k22_dtoper >= '$dtini' and k22_dtoper <= '$dtfim' ";
  $and = " and ";
}
$data = "";
if($dataini != "--" && $datafim == "--"){
  $dbwhere .= " $and k22_dtvenc >= '$dataini' ";
  $and = " and ";
}elseif($dataini != "--" && $datafim != "--"){
  $dbwhere .= " $and k22_dtvenc >= '$dataini' and k22_dtvenc <= '$datafim' ";
  $and = " and ";
}
//data
$sql="select k22_data from debitos where k22_instit = $instit order by k22_data desc limit 1";
$result=pg_query($sql);
db_fieldsmemory($result,0);
$dbwhere .= " $and k22_data = '$k22_data'";
//$dbwhere.=" and  dtoper = '2004-10-06'";
   

//origem 
if($origem=="matricula"){
  $dbwhere.=" and coalesce(k22_matric,0) <> 0 "; 
  $origem_descr="Matrícula";
}else if($origem=="inscricao"){
  $dbwhere.=" and coalesce(k22_inscr,0) <> 0"; 
  $origem_descr="Inscrição";
}else if($origem=="cgm"){
  $dbwhere.=" and coalesce(k22_numcgm,0) <> 0"; 
  $origem_descr="Numcgm";
}

//tipo de receitas... tabrec
if($receitas != ""){
  if($selerec=='S'){
    $dbwhere.="  and k22_receit in (".$receitas.")  ";
  }else{
    $dbwhere.="  and k22_receit not in (".$receitas.")  ";
  }  
}
//historico de calculos... histcalc
if($historico != ""){
  if($selehist=='S'){
    $dbwhere.="  and k22_hist in (".$historico.")  ";
  }else{
    $dbwhere.="  and k22_hist not in (".$historico.")  ";
  }  
}

//tipo de debitos... arretipo
if($debitos != ""){
  if($seledeb=='S'){
    $dbwhere.="  and k22_tipo in (".$debitos.")  ";
  }else{
    $dbwhere.="  and k22_tipo not in (".$debitos.")  ";
  }  
}

//limite
if($registros != ""){
  $limite =" limit $registros ";
}else{
  $limite = "";
}

if($origem=="matricula"){
  $sql= "select k22_data,
                k02_descr,
                k00_descr,
                codig,
                vlrcor,
                vlrhis, 
                juros, 
                multa, 
                desconto, 
                total,
                proprietario_nome.z01_nome
	 	       from ( select k22_data,
	 	                     k02_descr,
	 	                     k00_descr,
	 	                     k22_matric as codig,
	 	                     round(sum(k22_vlrcor),2) as vlrcor,
	 	                     round(sum(k22_vlrhis),2) as vlrhis, 
	 	                     round(sum(k22_juros),2) as juros, 
	 	                     round(sum(k22_multa),2) as multa, 
	 	                     round(sum(k22_desconto),2) as desconto, 
	 	                     round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
				            from debitos 
					         inner join tabrec on k22_receit = k02_codigo 
					         inner join arretipo on k00_tipo = k22_tipo  
					         where $dbwhere  
					           and k22_instit = $instit
				           group by k22_matric,k02_descr,k00_descr,k22_data) as x 
          inner join proprietario_nome on x.codig=proprietario_nome.j01_matric
          order by $order $ordem, codig $ordem $limite
         ";
          
}else if($origem=="inscricao"){
  $sql= "
         select distinct k02_descr,
                k00_descr,
                codig,
                empresa.z01_nome,
                vlrcor, 
                vlrhis, 
                juros, 
                multa, 
                desconto, 
                total
	 	       from ( select k02_descr,
	 	                     k00_descr,
	 	                     k22_inscr as codig, 
	 	                     round(sum(k22_vlrcor),2) as vlrcor, 
	 	                     round(sum(k22_vlrhis),2) as vlrhis, 
	 	                     round(sum(k22_juros),2) as juros, 
	 	                     round(sum(k22_multa),2) as multa, 
	 	                     round(sum(k22_desconto),2) as desconto, 
	 	                     round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
				            from debitos 
					         inner join tabrec on k22_receit = k02_codigo 
					         inner join arretipo on k00_tipo = k22_tipo 
					         where $dbwhere
					           and k22_instit = $instit
 				           group by k22_inscr,k02_descr,k00_descr,k22_data) as x 
          inner join empresa on x.codig=empresa.q02_inscr
          order by $order $ordem,codig $ordem $limite
         ";
          
}else if($origem=="cgm"){
  $sql= "
         select k22_data,
                k02_descr,
                k00_descr,
                codig,
                vlrcor,
                vlrhis, 
                juros, 
                multa, 
                desconto, 
                total,
                cgm.z01_nome
	 	       from (select k22_data,
			                  k02_descr,
			                  k00_descr,
			                  k22_numcgm as codig, 
			                  round(sum(k22_vlrcor),2) as vlrcor, 
			                  round(sum(k22_vlrhis),2) as vlrhis, 
			                  round(sum(k22_juros),2) as juros, 
			                  round(sum(k22_multa),2) as multa, 
			                  round(sum(k22_desconto),2) as desconto, 
			                  round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
		               from debitos 
					        inner join tabrec on k22_receit = k02_codigo 
				          inner join arretipo on k00_tipo = k22_tipo  
					        where $dbwhere  and k22_instit = $instit
				          group by k22_numcgm,k02_descr,k00_descr,k22_data) as x 
          inner join cgm on x.codig=cgm.z01_numcgm
          order by $order $ordem,codig $ordem $limite";
}

if($modelo!="completo"){
  $sql = "select codig, z01_nome, round(sum(vlrcor),2) as vlrcor, round(sum(vlrhis),2) as vlrhis, round(sum(juros),2) as juros, round(sum(multa),2) as multa, round(sum(desconto),2) as desconto, round(sum(total),2) as total from ($sql) as x group by codig, z01_nome order by $order $ordem $limite";
}

$result = @pg_query($sql);
if($result){
 $numrows = pg_numrows($result);
}else{
 $numrows ='0';
}

$alt="5";
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head2 = "RELATÓRIO TOTAL DE DÉBITOS";
$head3 = "Data de emissão: ".date("d-m-Y",db_getsession("DB_datausu"));
$head4 = "Posição em: ". db_formatar($k22_data,'d') . " - " . ((int) $registros == 0?"TODOS OS REGISTROS":"SOMENTE OS $registros PRIMEIROS REGISTROS");
if ($dtini == "--" and $dtfim == "--") {
  $head5 = "SEM DATA DE OPERAÇÃO ESPECIFICADA";
} else {
  $head5 = "Data de operação: " . db_formatar($dtini,"d") . " a " . db_formatar($dtfim,"d");
}
if ($dataini == "--" and $datafim == "--") {
  $head6 = "SEM DATA DE VENCIMENTO ESPECIFICADA";
} else {
  $head6 = "Data de vencimento: " . db_formatar($dataini,"d") . " a " . db_formatar($datafim,"d");
}

$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',10);

$pdf->cell(20,$alt,$origem_descr,1,0,"C",1);
$pdf->cell(70,$alt,'Nome',1,0,"C",1);
if($modelo=="completo"){
  $pdf->cell(28,$alt,"Procedência",1,0,"C",1);
  $pdf->cell(40,$alt,"Tipo de débito",1,0,"C",1);
}
$pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
$pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
$pdf->cell(15,$alt,"Multa",1,0,"C",1);
$pdf->cell(15,$alt,"Juros",1,0,"C",1);
$pdf->cell(23,$alt,"Total",1,0,"C",1);
$pdf->setfont('arial','',7);
$pdf->ln();
$arr_receit=array();

for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);

  if ($pdf->gety() > $pdf->h -45) {
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);

      $pdf->cell(20,$alt,$origem_descr,1,0,"C",1);
      $pdf->cell(70,$alt,'Nome',1,0,"C",1);
      if($modelo=="completo"){
        $pdf->cell(28,$alt,"Procedência",1,0,"C",1);
        $pdf->cell(40,$alt,"Tipo de débito",1,0,"C",1);
      }
      $pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
      $pdf->cell(15,$alt,"Multa",1,0,"C",1);
      $pdf->cell(15,$alt,"Juros",1,0,"C",1);
      $pdf->cell(23,$alt,"Total",1,0,"C",1);
      $pdf->setfont('arial','',7);
      $pdf->ln();
  }  
  $pdf->cell(20,$alt,$codig,1,0,"C",0);
  $pdf->cell(70,$alt,$z01_nome,1,0,"L",0);
  if($modelo=="completo"){
    $pdf->cell(28,$alt,$k02_descr,1,0,"C",0);
    $pdf->cell(40,$alt,$k00_descr,1,0,"C",0);
  }
  $pdf->cell(33,$alt,db_formatar($vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($vlrcor,"f"),1,0,"C",0);
  $pdf->cell(15,$alt,db_formatar($multa,"f"),1,0,"C",0);
  $pdf->cell(15,$alt,db_formatar($juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($total,"f"),1,0,"C",0);
  $pdf->ln();

  //total
}
$pdf->ln();
$pdf->cell(60,3,"Total de registros: $numrows",0,0,"L",0);

//TOTALIZAÇÃO
/*********************************************receita**************************************/
//receita

  $sql="
       select x.*, tabrec.k02_drecei 
             from (
 	          select k22_receit, round(sum(k22_vlrcor),2) as vlrcor, round(sum(k22_vlrhis),2) as vlrhis, round(sum(k22_juros),2) as juros, round(sum(k22_multa),2) as multa, round(sum(k22_desconto),2) as desconto, round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
		              from debitos where $dbwhere  and k22_instit = $instit group by k22_receit
 	      ) as x 
	        inner join tabrec on x.k22_receit = tabrec.k02_codigo $limite;
   ";
   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   }  
$pdf->AddPage("L");
$pdf->setfillcolor(235);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(253,10,'TOTALIZAÇÃO POR TIPO DE RECEITA',1,1,"C",1);
$pdf->setfont('arial','b',10);
$pdf->cell(23,$alt,"Receita",1,0,"C",1);
$pdf->cell(80,$alt,"Descrição",1,0,"C",1);
$pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
$pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
$pdf->cell(19,$alt,"Multa",1,0,"C",1);
$pdf->cell(19,$alt,"Juros",1,0,"C",1);
$pdf->cell(23,$alt,"Desconto",1,0,"C",1);
$pdf->cell(23,$alt,"Total",1,0,"C",1);
$pdf->setfont('arial','',8);
$pdf->ln();
$arr_receit=array();

$tot_vlrhis="";
$tot_vlrcor="";
$tot_multa="";
$tot_juros="";
$tot_desconto="";
$tot_total="";
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h -45  ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);
      $pdf->cell(23,$alt,"Receita",1,0,"C",1);
      $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
      $pdf->cell(19,$alt,"Multa",1,0,"C",1);
      $pdf->cell(19,$alt,"Juros",1,0,"C",1);
      $pdf->cell(23,$alt,"Desconto",1,0,"C",1);
      $pdf->cell(23,$alt,"Total",1,0,"C",1);
      $pdf->setfont('arial','',8);
      $pdf->ln();
  }  
  $pdf->cell(23,$alt,$k22_receit,1,0,"C",0);
  $pdf->cell(80,$alt,$k02_drecei,1,0,"L",0);
  $pdf->cell(33,$alt,db_formatar($vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($total,"f"),1,0,"C",0);
  $pdf->ln();

      $tot_vlrhis  += $vlrhis;  
      $tot_vlrcor  += $vlrcor; 
      $tot_multa   += $multa ;  
      $tot_juros   += $juros ;  
      $tot_desconto+= $desconto;
      $tot_total   += $total;   
}
  $pdf->setfont('arial','B',10);
  $pdf->cell(103,$alt,"TOTAL",1,0,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(33,$alt,db_formatar($tot_vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($tot_vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_total,"f"),1,0,"C",0);
/****************************************final de receita****************************************************/
/*********************************************tipo de DEBITO.. arretipo**************************************/
//receita

  $sql="
       select x.*, cadtipo.k03_descr
             from (
 	          select k03_tipo, round(sum(k22_vlrcor),2) as vlrcor, round(sum(k22_vlrhis),2) as vlrhis, round(sum(k22_juros),2) as juros, round(sum(k22_multa),2) as multa, round(sum(k22_desconto),2) as desconto, round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
		              from debitos 
			      inner join arretipo on k22_tipo = arretipo.k00_tipo
			      where $dbwhere  and k22_instit = $instit group by k03_tipo
 	      ) as x 
	        inner join cadtipo on x.k03_tipo = cadtipo.k03_tipo $limite;
  ";
  
   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   }  

   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   }  
$pdf->AddPage("L");
$pdf->ln(10);
$pdf->setfillcolor(235);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(253,10,'TOTALIZAÇÃO POR GRUPO DE DÉBITO',1,1,"C",1);
$pdf->setfont('arial','b',10);
$pdf->cell(23,$alt,"Débito",1,0,"C",1);
$pdf->cell(80,$alt,"Descrição",1,0,"C",1);
$pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
$pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
$pdf->cell(19,$alt,"Multa",1,0,"C",1);
$pdf->cell(19,$alt,"Juros",1,0,"C",1);
$pdf->cell(23,$alt,"Desconto",1,0,"C",1);
$pdf->cell(23,$alt,"Total",1,0,"C",1);
$pdf->setfont('arial','',8);
$pdf->ln();
$arr_receit=array();

$tot_vlrhis="";
$tot_vlrcor="";
$tot_multa="";
$tot_juros="";
$tot_desconto="";
$tot_total="";
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h -45  ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);
      $pdf->cell(23,$alt,"Débito",1,0,"C",1);
      $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
      $pdf->cell(19,$alt,"Multa",1,0,"C",1);
      $pdf->cell(19,$alt,"Juros",1,0,"C",1);
      $pdf->cell(23,$alt,"Desconto",1,0,"C",1);
      $pdf->cell(23,$alt,"Total",1,0,"C",1);
      $pdf->setfont('arial','',8);
      $pdf->ln();
  }  
  $pdf->cell(23,$alt,$k03_tipo,1,0,"C",0);
  $pdf->cell(80,$alt,$k03_descr,1,0,"L",0);
  $pdf->cell(33,$alt,db_formatar($vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($total,"f"),1,0,"C",0);
  $pdf->ln();

      $tot_vlrhis  += $vlrhis;  
      $tot_vlrcor  += $vlrcor; 
      $tot_multa   += $multa ;  
      $tot_juros   += $juros ;  
      $tot_desconto+= $desconto;
      $tot_total   += $total;   
}
  $pdf->setfont('arial','B',10);
  $pdf->cell(103,$alt,"TOTAL",1,0,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(33,$alt,db_formatar($tot_vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($tot_vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_total,"f"),1,0,"C",0);

/****************************************final de receita****************************************************/
/*********************************************tipo de DEBITO.. arretipo**************************************/
//receita

  $sql="
       select x.*, arretipo.k00_descr,arretipo.k00_tipo
             from (
 	          select k22_tipo, round(sum(k22_vlrcor),2) as vlrcor, round(sum(k22_vlrhis),2) as vlrhis, round(sum(k22_juros),2) as juros, round(sum(k22_multa),2) as multa, round(sum(k22_desconto),2) as desconto, round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
		              from debitos 
			      where $dbwhere   and k22_instit = $instit group by k22_tipo
 	      ) as x 
	        inner join arretipo on x.k22_tipo = arretipo.k00_tipo $limite;
  ";
  
   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   }  

   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   }
//die($sql);
$pdf->AddPage("L");
$pdf->ln(10);
$pdf->setfillcolor(235);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(253,10,'TOTALIZAÇÃO POR TIPO DE DÉBITO',1,1,"C",1);
$pdf->setfont('arial','b',10);
$pdf->cell(23,$alt,"Débito",1,0,"C",1);
$pdf->cell(80,$alt,"Descrição",1,0,"C",1);
$pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
$pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
$pdf->cell(19,$alt,"Multa",1,0,"C",1);
$pdf->cell(19,$alt,"Juros",1,0,"C",1);
$pdf->cell(23,$alt,"Desconto",1,0,"C",1);
$pdf->cell(23,$alt,"Total",1,0,"C",1);
$pdf->setfont('arial','',8);
$pdf->ln();
$arr_receit=array();

$tot_vlrhis="";
$tot_vlrcor="";
$tot_multa="";
$tot_juros="";
$tot_desconto="";
$tot_total="";
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h -45  ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);
      $pdf->cell(23,$alt,"Débito",1,0,"C",1);
      $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
      $pdf->cell(19,$alt,"Multa",1,0,"C",1);
      $pdf->cell(19,$alt,"Juros",1,0,"C",1);
      $pdf->cell(23,$alt,"Desconto",1,0,"C",1);
      $pdf->cell(23,$alt,"Total",1,0,"C",1);
      $pdf->setfont('arial','',8);
      $pdf->ln();
  }  
  $pdf->cell(23,$alt,$k00_tipo,1,0,"C",0);
  $pdf->cell(80,$alt,$k00_descr,1,0,"L",0);
  $pdf->cell(33,$alt,db_formatar($vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($total,"f"),1,0,"C",0);
  $pdf->ln();

      $tot_vlrhis  += $vlrhis;  
      $tot_vlrcor  += $vlrcor; 
      $tot_multa   += $multa ;  
      $tot_juros   += $juros ;  
      $tot_desconto+= $desconto;
      $tot_total   += $total;   
}
  $pdf->setfont('arial','B',10);
  $pdf->cell(103,$alt,"TOTAL",1,0,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(33,$alt,db_formatar($tot_vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($tot_vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_total,"f"),1,0,"C",0);

/****************************************final de receita****************************************************/
/*********************************************receita**************************************/
//receita

  $sql="
       select x.*, histcalc.k01_descr 
             from (
 	          select k22_hist, round(sum(k22_vlrcor),2) as vlrcor, round(sum(k22_vlrhis),2) as vlrhis, round(sum(k22_juros),2) as juros, round(sum(k22_multa),2) as multa, round(sum(k22_desconto),2) as desconto, round((sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)),2) as total
		              from debitos where $dbwhere   and k22_instit = $instit group by k22_hist
 	      ) as x 
	        inner join histcalc on x.k22_hist = histcalc.k01_codigo $limite;
  ";
  
   $result = @pg_query($sql);
   if($result){
     $numrows = pg_numrows($result);
   }else{
     $numrows ='0';
   } 
$pdf->AddPage("L");
$pdf->ln(10);
$pdf->setfillcolor(235);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(253,10,'TOTALIZAÇÃO POR HISTÓRICO DE CÁLCULO',1,1,"C",1);
$pdf->setfont('arial','b',10);
$pdf->cell(23,$alt,"Histórico",1,0,"C",1);
$pdf->cell(80,$alt,"Descrição",1,0,"C",1);
$pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
$pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
$pdf->cell(19,$alt,"Multa",1,0,"C",1);
$pdf->cell(19,$alt,"Juros",1,0,"C",1);
$pdf->cell(23,$alt,"Desconto",1,0,"C",1);
$pdf->cell(23,$alt,"Total",1,0,"C",1);
$pdf->setfont('arial','',8);
$pdf->ln();
$arr_receit=array();

$tot_vlrhis="";
$tot_vlrcor="";
$tot_multa="";
$tot_juros="";
$tot_desconto="";
$tot_total="";
for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i);
  if ($pdf->gety() > $pdf->h -45  ){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $pdf->setfont('arial','b',10);
      $pdf->cell(23,$alt,"Histórico",1,0,"C",1);
      $pdf->cell(80,$alt,"Descrição",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor histórico",1,0,"C",1);
      $pdf->cell(33,$alt,"Valor corrigido",1,0,"C",1);
      $pdf->cell(19,$alt,"Multa",1,0,"C",1);
      $pdf->cell(19,$alt,"Juros",1,0,"C",1);
      $pdf->cell(23,$alt,"Desconto",1,0,"C",1);
      $pdf->cell(23,$alt,"Total",1,0,"C",1);
      $pdf->setfont('arial','',8);
      $pdf->ln();
  }  
  $pdf->cell(23,$alt,$k22_hist,1,0,"C",0);
  $pdf->cell(80,$alt,$k01_descr,1,0,"L",0);
  $pdf->cell(33,$alt,db_formatar($vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($total,"f"),1,0,"C",0);
  $pdf->ln();

      $tot_vlrhis  += $vlrhis;  
      $tot_vlrcor  += $vlrcor; 
      $tot_multa   += $multa ;  
      $tot_juros   += $juros ;  
      $tot_desconto+= $desconto;
      $tot_total   += $total;   
}
  $pdf->setfont('arial','B',10);
  $pdf->cell(103,$alt,"TOTAL",1,0,"C",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(33,$alt,db_formatar($tot_vlrhis,"f"),1,0,"C",0);
  $pdf->cell(33,$alt,db_formatar($tot_vlrcor,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_multa,"f"),1,0,"C",0);
  $pdf->cell(19,$alt,db_formatar($tot_juros,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_desconto,"f"),1,0,"C",0);
  $pdf->cell(23,$alt,db_formatar($tot_total,"f"),1,0,"C",0);
/****************************************final de histcalc****************************************************/
/*final das totalizações*/

/***************************************propriedades do relatorio**************************************************/
$pdf->addpage("L");
$pdf->Ln(10);
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(235);
$pdf->MultiCell(253,05,"PROPRIEDADES DO RELATÓRIO",1,"C",1);
$pdf->Ln(5);
$pdf->SetFillColor(255);
$pdf->SetFont('Arial','',7);

/*historico de calculos*/
if($historico != ""){
  $cod='';
  $vir='';
  $consta=($selehist=='S'?"SOMENTE HISTÓRICO DE CÁLCULO(S)-> ":" SEM HISTÓRICO DE CÁLCULO(S)-> "); 
  $result = pg_query("select k01_descr from histcalc where k01_codigo in ($historico)");
  for($x=0;$x<pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    $cod .= $vir.$k01_descr;
    $vir=", ";
  }
  $pdf->MultiCell(253,05,$consta.$cod,1,"L");
}else{
  $pdf->MultiCell(253,05,"TODOS OS TIPOS DE CÁLCULO",1,"L");
}

/**tipos de receitas*/
if($receitas != ""){
  $cod='';
  $vir='';
  $consta=($selerec=='S'?"SOMENTE O(S) TIPO(S) DE RECEITA(S)-> ":" SEM O(S) TIPO(S) DE RECEITA(S)-> "); 
  $result = pg_query("select k02_descr from tabrec where k02_codigo in ($receitas)");
  for($x=0;$x<pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    $cod .= $vir.$k02_descr;
    $vir=", ";
  }
  $pdf->MultiCell(253,05,$consta.$cod,1,"L");
}else{
  $pdf->MultiCell(253,05,"TODOS OS TIPOS DE RECEITA",1,"L");
}

/*tipos de debitos*/
if($debitos != ""){
  $cod='';
  $vir='';
  $consta=($seledeb=='S'?"SOMENTE O(S) TIPO(S) DE DÉBITO(S)-> ":" SEM O(S) TIPO(S) DE DÉBITO(S)-> "); 
  $result = pg_query("select k00_descr from arretipo where k00_tipo in ($debitos)");
  for($x=0;$x<pg_numrows($result);$x++){
    db_fieldsmemory($result,$x);
    $cod .= $vir.$k00_descr;
    $vir=", ";
  }
  $pdf->MultiCell(253,05,$consta.$cod,1,"L");
}else{
  $pdf->MultiCell(253,05,"TODOS OS TIPOS DE DÉBITOS",1,"L");
}

//Período
$prop="";
if(isset($dataini) && $dataini!="--"){
  $prop .= "VENCIMENTO A PARTIR DE ".db_formatar($dataini,"d");
}
if(isset($datafim) && $datafim!="--"){
  $t=($prop==""?"VENCIMENTO ":"  ");
  $prop .= $t." ATÉ ".db_formatar($datafim,"d");
}
if($prop!=""){
  $pdf->MultiCell(253,05,$prop,1,"L");
}   

//origem 
if($origem=="matricula"){
  $pdf->MultiCell(253,05,"DÉBITOS ORIGINADOS POR MATRÍCULA",1,"L");
}else if($origem=="inscricao"){
  $pdf->MultiCell(253,05,"DÉBITOS ORIGINADOS POR INSCRIÇÃO",1,"L");
}else if($origem=="cgm"){
  $pdf->MultiCell(253,05,"DÉBITOS ORIGINADOS POR NUMCGM",1,"L");
}


//ordem
if($ordem!="0"  ){
  if($ordem=='asc'){
    $tex02=" ASCENCENTE ";
  }else{
    $tex02=" DESCENDENTE ";
  }
  
  if($order=="vlrcor"){
     $tex01=" VALOR CORRIGIDO ";
  }else if($order=="z01_nome"){
     $tex01=" NOME ";
  }else if($order=="k22_data"){
     $tex01=" DATA DE VENCIMENTO ";
  }else if($order=="total"){
     $tex01=" VALOR TOTAL ";
  }  
  $pdf->MultiCell(253,05,"ORDENADO POR $tex01 DE FORMA $tex02",1,"L");
}


//limite
if(isset($registros)){
  $pdf->MultiCell(253,05,"LIMITE DE REGISTROS $registros",1,"L");
}else{
  $pdf->MultiCell(253,05,"SEM LIMITE DE REGISTROS",1,"L");
}
/*************fim das propriedades****************************************************************************/  
//echo $sql;
//die();
//include("fpdf151/geraarquivo.php");

$pdf->Output();

?>