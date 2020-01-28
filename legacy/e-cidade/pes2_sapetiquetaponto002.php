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

include("fpdf151/scpdf.php");
include("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//$ano = 2005;
//$mes = 8;

$sql = "
select rh02_regist as r01_regist,
       lpad(case when rh02_mesusu = 12 then 1 else rh02_mesusu + 1 end,2,0) as r01_mesusu,
       case when rh02_mesusu = 12 then rh02_anousu + 1 else rh02_anousu end as r01_anousu,
       z01_nome,
       rh01_ponto as r01_ponto,
       rh05_recis as r01_recis 
from rhpessoalmov 
     inner join rhpessoal     on rh01_regist = rh02_regist
     left join  rhpesrescisao on rh02_seqpes = rh05_seqpes 
     inner join cgm    on rh01_numcgm = z01_numcgm 
where rh01_ponto > 0 
  and rh02_anousu = $ano 
  and rh02_mesusu = $mes
	and rh02_instit = ".db_getsession("DB_instit")."
  and rh05_recis is null 
order by rh01_ponto
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}
$tamanho = array(250,410);
$pdf = new scpdf("P","mm",$tamanho);
$pdf->open();
$pdf->AddPage();
$pdf->SetAutoPageBreak("on",0);
//$pdf->s
$pdf->setx(30);
$pdf->sety(2);
//$y = 25;
//$x = 84;
$y       = 0;
$x       = 10;
$vinicio = $_GET["vini"];
$vfim    = $_GET["vfim"];
$pag     = 0;
$impnova = 0;

for ($i = 0;$i < $xxnum;$i++){
  db_fieldsmemory($result,$i);
  if ($pag == 6){
     $pag = 0;
     $y   = 0;
     $pdf->AddPage();
  }    
  if($impnova == 0){
    $impnova = 1;
    $x = 10;   
    
  }else{
    $impnova = 0;
    $x = 120;
  }
    $pdf->SetFont("arial","b",16);
    $pdf->Text(20+$x,10+$y,$r01_ponto);
    $pdf->SetFont("arial","",12);
    $pdf->Text(45+$x,10+$y,$r01_regist);
    $pdf->Text(75+$x,10+$y,'Mes/Ano :'.$r01_mesusu.'/'.$r01_anousu);
    $pdf->SetFont("arial","",10);
    $pdf->text(20+$x,16+$y,"PREFEITURA MUNICIPAL DE SAPIRANGA");
    $pdf->SetFont("arial","b",12);
    $pdf->Text(20+$x,22+$y,$z01_nome);
    $pdf->SetFont("arial","",12);
    $pdf->Text(20+$x,29+$y,"0010 - ........................");
    $pdf->Text(65+$x,29+$y,"0011 - ........................");
    $pdf->Text(20+$x,36+$y,"0080 - ........................");
    $pdf->Text(65+$x,36+$y,"0200 - ........................");
    $pdf->Text(20+$x,43+$y,"1010 - ........................");
    $pdf->Text(65+$x,43+$y,"1012 - ........................");
    $pdf->Text(20+$x,50+$y,"         - ........................");
    $pdf->Text(65+$x,50+$y,"         - ........................");
  if($impnova == 0){
    $y += 72; 
    $pag++;   
  }
}
$pdf->Output();
?>