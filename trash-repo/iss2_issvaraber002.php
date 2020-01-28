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
include("classes/db_issvar_classe.php");
$clissvar = new cl_issvar;
$clrotulo = new rotulocampo;
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');
$clrotulo->label('q05_mes');
$clrotulo->label('q05_ano');
$clrotulo->label('q05_valor');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$txt_where = "";
$and = "";
if ($listaativ != "") {
  if (isset ($verativ) and $verativ == "com") {
    $txt_where .= $and." q07_ativ in  ($listaativ)";
    $and = " and ";
  } else {
    $txt_where .= $and." q07_ativ not in  ($listaativ)";
    $and = " and ";
  }
}
if ($listaclas != "") {
  if (isset ($verclas) and $verclas == "com") {
    $txt_where .= $and." q82_classe in  ($listaclas)";
    $and = " and ";
  } else {
    $txt_where .= $and." q82_classe not in  ($listaclas)";
    $and = " and ";
  }
}
if ($ano_ini!="" && $ano_fim!=""){	
  $txt_where .= $and . " cast(trim(to_char(q05_ano,'0000'))||trim(to_char(q05_mes,'00')) as integer) between ".
    str_pad($ano_ini, 4, '0', STR_PAD_LEFT) . str_pad($mes_ini, 2, '0', STR_PAD_LEFT) . " and " .
    str_pad($ano_fim, 4, '0', STR_PAD_LEFT) . str_pad($mes_fim, 2, '0', STR_PAD_LEFT);
  $and = " and ";	
}else if ($ano_ini!=""){	
  $txt_where .= $and . " cast(trim(to_char(q05_ano,'0000'))||trim(to_char(q05_mes,'00')) as integer) >= ".
    str_pad($ano_ini, 4, '0', STR_PAD_LEFT) . str_pad($mes_ini, 2, '0', STR_PAD_LEFT);
  $and = " and ";	
}else if ($ano_fim!=""){	
  $txt_where .= $and . " cast(trim(to_char(q05_ano,'0000'))||trim(to_char(q05_mes,'00')) as integer) <= ".
    str_pad($ano_ini, 4, '0', STR_PAD_LEFT) . str_pad($mes_ini, 2, '0', STR_PAD_LEFT);
  $and = " and ";
}
if ($mostra=="z"){
  $txt_where .= $and." (q05_valor = 0 and q05_vlrinf = 0) ";
  $and = " and ";	
}else if ($mostra=="l"){
  $txt_where .= $and." (q05_valor <> 0 or q05_vlrinf <> 0) ";
  $and = " and ";	
}
$result = $clissvar->sql_record($clissvar->sql_query_arretivprinc(null,"*","q02_inscr,q05_ano,q05_mes",$txt_where));
if ($clissvar->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros.');
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
$p = 0;
$inscr = "";
$val_total = 0;
$val_total_inscr = 0;
for($x = 0; $x < $clissvar->numrows;$x++){
  db_fieldsmemory($result,$x);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,substr($RLq02_inscr,0,9),1,0,"C",1);
    $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1); 
    $pdf->cell(60,$alt,"Atividade Principal",1,0,"C",1);
    $pdf->cell(8,$alt,$RLq05_mes,1,0,"C",1);
    $pdf->cell(8,$alt,$RLq05_ano,1,0,"C",1);
    $pdf->cell(15,$alt,$RLq05_valor,1,1,"C",1); 
    $troca = 0;
    $p = 0;
  }
  if ($inscr != $q02_inscr){
    $inscr=$q02_inscr;
    if ($x!=0){
      $pdf->setfont('arial','b',8);
      $pdf->cell(166,$alt,"Sub-total: ","T",0,"R",0);
      $pdf->cell(15,$alt,db_formatar($val_total_inscr,'f'),"T",1,"R",0);
      $val_total_inscr = 0;   		
    }
  }   
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$q02_inscr,0,0,"C",$p);
  $pdf->cell(70,$alt,substr($z01_nome,0,40),0,0,"L",$p);
  $pdf->cell(60,$alt,substr($q03_descr,0,35),0,0,"L",$p);
  $pdf->cell(8,$alt,$q05_mes,0,0,"C",$p);   
  $pdf->cell(8,$alt,$q05_ano,0,0,"C",$p);

  if($q05_valor==0 and $q05_vlrinf<>0) {
    $pdf->cell(15,$alt,db_formatar($q05_vlrinf,'f'),0,1,"R",$p);
  } else {
    $pdf->cell(15,$alt,db_formatar($q05_valor,'f'),0,1,"R",$p);
  }

  if($q05_valor==0 and $q05_vlrinf<>0) {
    $val_total       += $q05_vlrinf;
    $val_total_inscr += $q05_vlrinf;
  } else {
    $val_total       += $q05_valor;
    $val_total_inscr += $q05_valor;
  }

  if($p==0) {
    $p=1;
  } else {
    $p=0;
  }

  $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(166,$alt,"Sub-total: ","T",0,"R",0);
$pdf->cell(15,$alt,db_formatar($val_total_inscr,'f'),"T",1,"R",0);
$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,'TOTAL DE REGISTROS : '.$total,"T",0,"L",0);
$pdf->cell(81,$alt,'VALOR TOTAL : '.db_formatar($val_total,'f'),"T",1,"R",0);
$pdf->Output();
?>