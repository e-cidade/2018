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


// pesquisa a conta mae da receita

$tipo_mesini = 1;
$tipo_mesfim = 1;


$tipo_impressao = 1;
// 1 = orcamento
// 2 = balanco
$tipo_nivel = 7;
// 1 = orgao
// 2 = unidade
// 3 = funcao
// 4 = subfuncao
// 5 = programa
// 6 = projeto/atividade
// 7 = elemento 
$tipo_filtra = 1;
// 1 = somente o nivel
// 0 = até o nivel

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$head3 = "PROGRAMA DE TRABALHO POR ORGAO E UNIDADE ";
$head4 = "ANEXO (6) EXERCICIO: ".db_getsession("DB_anousu");

// funcao para gerar work

pg_exec("begin");

  
$sql = "create temp table work as 
	select ";
$sql2 = "";
$sql3 = "";

if($tipo_nivel < 3 or ($tipo_nivel > 1 && $tipo_filtra == 0)){
 $sql2 = " o58_orgao ";
 $sql3 = " o58_orgao ";
 $descr = " o40_descr ";
 $leftj = "left  outer join orcorgao   o on o40_anousu = ".db_getsession("DB_anousu")." and o.o40_orgao = w.o58_orgao";
}
if($tipo_nivel==2 or ($tipo_nivel > 2 && $tipo_filtra == 0)){
 $sql2 .= ",";
 $sql3 .= " || ";
 $sql2 .= " o58_unidade ";
 $sql3 .= " o58_unidade ";
 $descr = " o41_descr ";
 $leftj = "left  outer join orcunidade u on o41_anousu = ".db_getsession("DB_anousu")." and u.o41_orgao = w.o58_orgao and u.o41_unidade = w.o58_unidade";
}
if($tipo_nivel==3 or ($tipo_nivel > 3 && $tipo_filtra == 0)){
 if($tipo_filtra==0){
   $sql2 .= ",";
   $sql3 .= " || ";
 }
 $sql2 .= " o58_funcao ";
 $sql3 .= " o58_funcao ";
 $descr = " o52_descr ";
 $leftj = " left  outer join orcfuncao  f on o52_funcao = w.o58_funcao ";
}
if($tipo_nivel==4 or ($tipo_nivel > 4 && $tipo_filtra == 0)){
 if($tipo_filtra==0){
   $sql2 .= ",";
   $sql3 .= " || ";
 }
 $sql2 .= " o58_subfuncao ";
 $sql3 .= " o58_subfuncao ";
 $descr = " o53_descr ";
 $leftj = " left  outer join orcsubfuncao s on o53_subfuncao = w.o58_subfuncao ";
}
if($tipo_nivel==5 or ($tipo_nivel > 5 && $tipo_filtra == 0)){
 if($tipo_filtra==0){
   $sql2 .= ",";
   $sql3 .= " || ";
 }
 $sql2 .= " o58_programa ";
 $sql3 .= " o58_programa ";
 $descr = " o54_descr ";
 $leftj	= " left  outer join orcprograma  p on o54_anousu = ".db_getsession("DB_anousu")." and o54_programa = w.o58_programa ";
}
if($tipo_nivel==6 or ($tipo_nivel > 6 && $tipo_filtra == 0)){
  if($tipo_filtra==0){
   $sql2 .= ",";
   $sql3 .= " || ";
 }
 $sql2 .= " o58_projativ ";
 $sql3 .= " o58_projativ ";
 $descr = " o55_descr ";
 $leftj = "left  outer join orcprojativ a on o55_anousu = ".db_getsession("DB_anousu")." and o55_projativ = w.o58_projativ";
}
if($tipo_nivel==7 or ($tipo_nivel > 7 && $tipo_filtra == 0)){
 if($tipo_filtra==0){
   $sql2 .= ",";
   $sql3 .= " || ";
 }
 $sql2 .= " e.o56_elemento ";
 $sql3 .= " w.o56_elemento ";
 $descr = " o56_descr ";
 $leftj = "left  outer join orcelemento e on e.o56_elemento = w.o56_elemento and e.o56_anousu = w.o58_anousu ";
}
$sql .= $sql2 ."
           ,sum(o58_valor) as o58_valor
        from orcdotacao d
	     inner join orcelemento e on d.o58_codele = e.o56_codele and d.o58_anousu = e.o56_anousu
        where o58_anousu = ".db_getsession("DB_anousu")." 
	group by ";
$sql .= $sql2;

$result = pg_exec($sql);

$sql = "select ".$sql3." as codigo ,".$descr." as descr,o58_valor
                   from work w
                        $leftj
		   order by ".$sql2;

$result = pg_exec($sql);

//db_criatabela($result);
//exit;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$pagina = 1;
for($i=0;$i<pg_numrows($result);$i++){

  db_fieldsmemory($result,$i);

  if($pdf->gety()>$pdf->h-30 || $pagina ==1){
    $pagina = 0;
    $pdf->addpage();
    $pdf->setfont('arial','b',8);

    $pdf->cell(60,$alt,"CODIGO",1,0,"L",0);
    $pdf->cell(80,$alt,"ESPECIFICACAO",1,0,"L",0);
    $pdf->cell(55,$alt,"VALOR ORÇADO",1,1,"R",0);

  }
 
  $pdf->cell(60,$alt,$codigo,0,0,"L",0);
  $pdf->cell(80,$alt,$descr,0,0,"L",0);
  $pdf->cell(55,$alt,db_formatar($o58_valor,'f'),0,1,"R",0);

}
$pdf->Output();

pg_exec("commit");

?>