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
include("libs/db_sql.php");
include("classes/db_issbase_classe.php");
$clissbase = new cl_issbase;
$clrotulo = new rotulocampo;
$clissbase->rotulo->label();
$clrotulo->label('z01_nome');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where         = "";
$descr_process = "TODAS INSCRIÇÕES";
$campos        = "q02_inscr,q02_numcgm,z01_nome,q02_dtbaix"; 
if($process == "N") {
  $where = " and q02_dtbaix is null ";
  $descr_process = "INSCRIÇÕES NÃO BAIXADAS";
  $campos        = "q02_inscr,q02_numcgm,z01_nome"; 
}else if($process == "S"){
  $where = " and q02_dtbaix is not null ";
  $descr_process = "INSCRIÇÕES BAIXADAS";
}

$head4 = "INSCRIÇÕES SEM ATIVIDADE PRINCIPAL CONFIGURADA";
$head6= $descr_process;

$sql = "select ".$campos." from issbase a left join ativprinc b on a.q02_inscr=b.q88_inscr inner join cgm c on a.q02_numcgm=c.z01_numcgm where q88_inscr is null ".$where;
//die($sql);
$result = $clissbase->sql_record($sql);
if($clissbase->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cadastros de '.$descr_process.' sem atividade principal configurada.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$tot = 130;
$lin = 1;
$bor = "R";
for($i=0;$i<$clissbase->numrows;$i++){
  db_fieldsmemory($result,$i,true);
  if(isset($q02_dtbaix) && $i==0){    
    $lin = 0;
    $tot+= 20;
    $bor = 0;
  }
  if($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->cell($tot,0.1,'',"T",0,"L",0);
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(25,$alt,$RLq02_inscr,1,0,"C",1);
    $pdf->cell(25,$alt,$RLq02_numcgm,1,0,"C",1);
    $pdf->cell(80,$alt,$RLz01_nome,1,$lin,"C",1);
    if($lin == 0){
      $pdf->cell(20,$alt,$RLq02_dtbaix,1,1,"C",1);
    }
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(25,$alt,$q02_inscr,"L",0,"C",0);
  $pdf->cell(25,$alt,$q02_numcgm,0,0,"C",0);
  $pdf->cell(80,$alt,$z01_nome,$bor,$lin,"L",0);
  if($lin == 0){
    $pdf->cell(20,$alt,$q02_dtbaix,"R",1,"C",0);    
  }
  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell($tot,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>