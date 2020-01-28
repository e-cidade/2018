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

include("libs/db_sql.php");
include("fpdf151/scpdf.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if ( $d01_codedi == null ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Código do edital não preenchido!');
}
$pdf = new SCPDF();
$largura = 6;
$dbwhere=" and  d41_pgtopref='f' ";
$result = pg_exec("select munic,db12_extenso
	               from db_config 
                   inner join db_uf on db12_uf = uf
	               where codigo = ".db_getsession('DB_instit'));
db_fieldsmemory($result,0);
if ( pg_numrows($result) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Instituição não cadastrada!');
}
$resultedital = pg_query("select * from projmelhorias inner join editalproj on d10_codigo = d40_codigo inner join edital on d01_codedi = d10_codedi where d10_codedi = $d01_codedi");
if ( pg_numrows($resultedital) == 0 ) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registros encontrado!');
}
db_fieldsmemory($resultedital,0);
$head1 = "EDITAL:$d01_numero - DATA:".db_formatar($d01_data,"d");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$sql = "select nomeinst,bairro,cgc,ender,upper(munic) as munic,uf,telef,email,url,logo,db12_extenso
		from db_config 
		inner join db_uf on db12_uf = uf
		where codigo = ".db_getsession("DB_instit");
$result05 = pg_query($sql);
global $nomeinst;
global $ender;
global $munic;
global $cgc;
global $bairro;
global $uf;
global $logo;
//echo $sql;
db_fieldsmemory($result05,0);
/// seta a margem esquerda que veio do relatorio
$S = $pdf->lMargin;
$pdf->SetLeftMargin(10);
$Letra = 'Times';
$posini = ($pdf->w/2)-12;
$pdf->Image("imagens/files/".$logo,$posini,8,24);
//$pdf->Image('imagens/files/'.$logo,2,3,30);
$pdf->Ln(35);
$pdf->SetFont($Letra,'',10);
$pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
$pdf->SetFont($Letra,'B',13);
$pdf->MultiCell(0,6,$nomeinst,0,"C",0);
$pdf->SetFont($Letra,'B',12);
$pdf->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
$pdf->Ln(10);
$pdf->SetFont($Letra,'',7);
    $pdf->line(10,$pdf->h-12,290,$pdf->h-12);
    $pdf->text(10,$pdf->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
//    $pdf->text(90,$pdf->h-8,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"));
    $pdf->text(170,$pdf->h-8,'Página '.$pdf->PageNo().' de {nb}',0,1,'R');
    $pdf->SetFont($Letra,'B',12);
$tot = 0;
for($d=0;$d<pg_numrows($resultedital);$d++){
  db_fieldsmemory($resultedital,$d);
  $pdf->SetFont('Arial','',8);
  $pdf->SetFillColor(235);
  $sql1="select ruas.j14_nome, 
                case when ruas.j14_tipo = 2 then 'RUA' 
                     else case when ruas.j14_tipo = 3 
                               then 'AVENIDA' 
                               else 'TRAVESSA' 
                           end 
                end as j14_tipo, 
                z01_nome, 
                z01_ender, 
                cgm.z01_telef, 
                d40_trecho 
           from projmelhorias 
          inner join ruas on ruas.j14_codigo = projmelhorias.d40_codlog 
           left outer join projmelhoriasresp on projmelhoriasresp.d42_codigo = projmelhorias.d40_codigo 
           left outer join cgm on cgm.z01_numcgm = projmelhoriasresp.d42_numcgm 
          where d40_codigo = $d40_codigo";
  $result3 = pg_exec($sql1);
  db_fieldsmemory($result3,0);
  $sql="select
	    distinct proprietario,d41_pgtopref, j39_numero, z01_nome, j01_matric, j40_refant, d41_testada, d41_eixo, d41_obs
	    from projmelhoriasmatric
	    inner join proprietario on proprietario.j01_matric = projmelhoriasmatric.d41_matric
	    where d41_codigo = $d40_codigo $dbwhere order by j40_refant";
  $result = pg_exec($sql);
  if ( pg_numrows($result) == 0 ) {
   continue; 
   // db_redireciona('db_erros.php?fechar=true&db_erro=Lista nao cadastrada!');
  }
  $t="0";
  $f="0";
  $pdf->ln(5);
  $pdf->SetFont('Arial','B',11);
  $pdf->Cell(190,6,"ANEXO II - ".$j14_tipo."  ".$j14_nome,1,1,"C",1);
  $numrows03=pg_numrows($result);
  $pdf->SetFont('Arial','',8);
  $pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
  $pdf->Cell(30,$largura,'NUMERO',1,0,"C",1);
  $pdf->Cell(25,$largura,'MATRIC',1,0,"C",1);
  $pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
  $pdf->Cell(15,$largura,'TESTADA',1,0,"C",1);
  $pdf->Cell(15,$largura,'EIXO **',1,0,"C",1);
  $pdf->Cell(15,$largura,'TOTAL',1,1,"C",1);
  for($s=0;$s<$numrows03;$s++){
    db_fieldsmemory($result,$s);
    $pdf->Cell(70,$largura,$proprietario,1,0,"L",0);
    $pdf->Cell(30,$largura,$j39_numero,1,0,"L",0);
    $pdf->Cell(25,$largura,$j01_matric,1,0,"L",0);
    $pdf->Cell(20,$largura,$j40_refant,1,0,"L",0);
    $pdf->Cell(15,$largura,db_formatar($d41_testada,'f',' ',10),1,0,"L",0);
    $pdf->Cell(15,$largura,db_formatar($d41_eixo,'f',' ',10),1,0,"L",0);
    $pdf->Cell(15,$largura,db_formatar($d41_testada+$d41_eixo,'f',' ',10),1,1,"L",0);
    if ( $pdf->GetY() > 250) {
      $pdf->AddPage();
$sql = "select nomeinst,bairro,cgc,ender,upper(munic) as munic,uf,telef,email,url,logo,db12_extenso
		from db_config 
		inner join db_uf on db12_uf = uf
		where codigo = ".db_getsession("DB_instit");
$result05 = pg_query($sql);
global $nomeinst;
global $ender;
global $munic;
global $cgc;
global $bairro;
global $uf;
global $logo;
//echo $sql;
db_fieldsmemory($result05,0);
/// seta a margem esquerda que veio do relatorio
$S = $pdf->lMargin;
$pdf->SetLeftMargin(10);
$Letra = 'Times';
$posini = ($pdf->w/2)-12;
$pdf->Image("imagens/files/".$logo,$posini,8,24);
//$pdf->Image('imagens/files/'.$logo,2,3,30);
$pdf->Ln(35);
$pdf->SetFont($Letra,'',10);
$pdf->MultiCell(0,4,$db12_extenso,0,"C",0);
$pdf->SetFont($Letra,'B',13);
$pdf->MultiCell(0,6,$nomeinst,0,"C",0);
$pdf->SetFont($Letra,'B',12);
$pdf->MultiCell(0,4,@$GLOBALS["head1"],0,"C",0);
$pdf->Ln(10);
$pdf->SetFont($Letra,'',7);
    $pdf->line(10,$pdf->h-12,290,$pdf->h-12);
    $pdf->text(10,$pdf->h-8,'Base: '.@$GLOBALS["DB_NBASE"]);
//    $pdf->text(90,$pdf->h-8,$nome.'     Emissor: '.@$GLOBALS["DB_login"].'     Exercício: '.db_getsession("DB_anousu").'    Data: '.date("d-m-Y",db_getsession("DB_datausu"))." - ".date("H:i:s"));
    $pdf->text(170,$pdf->h-8,'Página '.$pdf->PageNo().' de {nb}',0,1,'R');
    $pdf->SetFont($Letra,'B',12);
      $pdf->SetFont('Arial','B',11);
      $pdf->Cell(190,6,"ANEXO II - ".$j14_tipo."  ".$j14_nome,1,1,"C",1);
      $pdf->SetFont('Arial','',8);
      $pdf->Cell(70,$largura,'PROPRIETÁRIO',1,0,"C",1);
      $pdf->Cell(30,$largura,'NUMERO',1,0,"C",1);
      $pdf->Cell(25,$largura,'MATRIC',1,0,"C",1);
      $pdf->Cell(20,$largura,'REFER *',1,0,"C",1);
      $pdf->Cell(15,$largura,'TESTADA',1,0,"C",1);
      $pdf->Cell(15,$largura,'EIXO **',1,0,"C",1);
      $pdf->Cell(15,$largura,'TOTAL',1,1,"C",1);
    }
  }
  $pdf->Cell(190,6,"Total: ".$numrows03,1,1,"L",1);
  $pdf->SetFont('Arial','B',7);
  $pdf->text(10,280,'* refere-se respectivamente às informações: zona fiscal, setor, quadra, lote e sublote. ** refere-se à parte da calçada até a metade da esquina.');
  $tot += $numrows03;
}
$pdf->ln(5);	
$pdf->SetFont('Arial','',8);
$pdf->Cell(190,$largura,"Total de registros: $tot",1,0,"L",0);
$pdf->Output();
?>