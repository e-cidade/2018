<?
require("libs/fc_sessoes.php");
require("libs/classes.class");
require("libs/funcoes.php");
require("libs/form.class");
require('fpdf151/fpdf.php');
include ("libs/jpgraph/jpgraph.php");
include ("libs/jpgraph/jpgraph_pie.php");
include ("libs/jpgraph/jpgraph_pie3d.php");

$getlogo = db_getnomelogo();
$logo    = ($getlogo==false?'':$getlogo);

$fonte = "times";
$pdf   = new FPDF("P",'mm',"A4");
$db    = new db();
$db->_DEBUG =1;
$pdf->open();
$pdf->AddPage();
$pdf->sety(15);
$pdf->setfont($fonte,'b',12);
$pdf->cell(0,5,"SECRETARIA MUNICIPAL DE ASSISTÊNCIA SOCIAL",0,1,"C");
$pdf->setfont($fonte,'b',11);
$pdf->cell(0,5,"PREFEITURA MUNICIPAL DE SAPIRANGA",0,1,"C");
$pdf->setfont($fonte,'b',10);
$pdf->cell(0,5,"ESTADO DO RIO GRANDE DO SUL",0,1,"C");
$pdf->setfont($fonte,'b',12);
$pdf->Image('libs/imagens/'.$logo,12,12,20,28);
$pdf->Text(10,45,"Relatorio de Serviços Prestados");
if ($_POST["dtfim"] != ""){
    $str = $_POST["dtini"] ." à ".$_POST["dtfim"];
}else{
    $str = $_POST["dtini"];
}
$pdf->Text(10,50,"Periodo de: ".$str);
$pdf->setxy(10,55);
$pdf->SetFillColor(230);
$pdf->cell(45,5,"Serviço",1,0,"C",1);
$pdf->cell(30,5,"Total",1,1,"C",1);
$sqlserv = "select (case when req_servico = '1' then 'Mudança'
                           when req_servico = '2' then 'Passagem'
                           when req_servico = '3' then 'Material de Construção'
                           when req_servico = '4' then 'Saúde'
                           when req_servico = '5' then 'Auxílio Funeral' end) as servico,
                           count(*) as total
              from   req_mudancas 
              where";

if ($_POST["dtfim"] != ""){
    $where = " req_dtcad between '".strformat($_POST["dtini"],"dten")."' and '".strformat($_POST["dtfim"],"dten")."'";
}else{
    $where = " req_dtcad =  '".strformat($_POST["dtini"],"dten")."'";
}
$sqlserv .= $where;
$sqlserv .= "group by servico";
$rs = $db->executa($sqlserv);
$pdf->setfont($fonte,'',8);
while($ln = $db->fetch_array($rs)){
    $pdf->cell(45,5,$ln["servico"],1,0,"L");
    $pdf->cell(30,5,$ln["total"],1,1,"R");
    $dados1[]  = $ln["total"]; 
    $legend1[] = $ln["servico"];
    
}

$pdf->setxy(10,120);
$pdf->SetFillColor(230);
$pdf->setfont($fonte,'b',12);
$pdf->cell(60,5,"Bairro",1,0,"C",1);
$pdf->cell(30,5,"Total",1,1,"C",1);
$sqlserv = "select bai_descr,
                   count(*) as total
            from   req_mudancas inner join Bairros on bai_id = req_baiid
            where";

if ($_POST["dtfim"] != ""){
    $where = " req_dtscad between '".strformat($_POST["dtini"],"dten")."' and '".strformat($_POST["dtfim"],"dten")."'";
}else{
    $where = " req_dtcad =  '".strformat($_POST["dtini"],"dten")."'";
}
$sqlserv .= $where;
$sqlserv .= "group by bai_descr";
$rs = $db->executa($sqlserv);
$pdf->setfont($fonte,'',8);
while($ln = $db->fetch_array($rs)){
    $pdf->cell(60,5,$ln["bai_descr"],1,0,"L");
    $pdf->cell(30,5,$ln["total"],1,1,"R");
    $dados[] = $ln["total"]; 
    $legend[] = $ln["bai_descr"];
}

// Create the Pie Graph.
$graph = new PieGraph(350,280,"auto");
$graph->SetShadow();
$graph->title->Set("Total Por Bairro");
$graph->title->SetFont(FF_VERDANA,FS_BOLD,10); 
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.0,0.1);
$graph->legend->SetFont(FF_VERDANA,FS_NORMAL,7); 
$graph->legend->SetFillColor("#FFFFFF@1");
$graph->img->SetAntiAliasing();
$graph->SetFrame(false);
$p1 = new PiePlot3d($dados);
$p1->SetTheme("sand");
$p1->SetEdge("white");
$p1->SetCenter(0.32);
$p1->SetAngle(45);
$p1->SetSize(88);
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,7);
$p1->SetLegends($legend);

$graph->Add($p1);
$nome = "/tmp/".mt_rand(1,3000).date("Ymd").".png";
$graph->stroke($nome);
$pdf->image($nome,110,120,95);
unlink($nome);
$graph = new PieGraph(350,250,"auto");
$graph->SetShadow();
$graph->title->Set("Total Por Serviço");
$graph->title->SetFont(FF_VERDANA,FS_BOLD,10); 
$graph->title->SetColor("darkblue");
$graph->legend->Pos(0.0,0.1);
$graph->legend->SetFont(FF_VERDANA,FS_NORMAL,7); 
$graph->legend->SetFillColor("#FFFFFF@1");
$graph->SetFrame(false);
$graph->img->SetAntiAliasing();
$p1 = new PiePlot3d($dados1);
$p1->SetTheme("sand");
$p1->SetCenter(0.32);
$p1->SetAngle(45);
$p1->SetEdge("black");
$p1->SetSize(88);
$p1->value->SetFont(FF_ARIAL,FS_NORMAL,7);
$p1->SetLegends($legend1);
$graph->Add($p1);
$nome2 = "/tmp/".mt_rand(1,3000).date("Ymd").".png";
$graph->stroke($nome2);
$pdf->image($nome2,110,50,90,70);
unlink($nome2);
$pdf->Output();
?>
