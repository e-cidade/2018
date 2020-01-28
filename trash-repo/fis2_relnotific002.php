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
include("classes/db_fiscal_classe.php");
$clfiscal = new cl_fiscal;
$clrotulo = new rotulocampo;
$clrotulo->label('y30_codnoti');
$clrotulo->label('y30_data');
$clrotulo->label('y30_prazorec');
$clrotulo->label('y30_dtvenc');
$clrotulo->label('y30_nome');
$clrotulo->label('descrdepto');
$and = "";
$where = " y30_instit = ".db_getsession('DB_instit') ;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
if ($setorfiscal != 0) {
  $where = " and y30_setor = $setorfiscal ";
  $and = "and";
}
if ($dt_ini != "--" && $dt_fin != "--"){
  $where .= " and y30_data between '$dt_ini' and '$dt_fin' ";
  $and = "and";
}elseif ($dt_ini != "--" && $dt_fin == "--"){
  $where .= " and y30_data >= '$dt_ini' ";
  $and = "and";
}elseif ($dt_ini == "--" && $dt_fin != "--"){
  $where .= " and y30_data <= '$dt_fin' ";
  $and = "and";
}
if ($dt_prazo != "--"){
  $where .= " and y30_prazorec <= '$dt_prazo' ";
  $and = "and";
}else{ 
  $where .= " and y30_data between '$dt_ini' and '$dt_fin' ";
  $and = "and";
 } 
$campos = " y30_codnoti, identifica, codigo, y30_nome, y30_data, y30_prazorec, descrdepto, nome ";
//                            die($clfiscal->sql_query_info("","$campos",$where)); 
$result = $clfiscal->sql_record($clfiscal->sql_query_info("","$campos",$where)); 
if ($clfiscal->numrows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrado registros correspondentes.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$head3 = "Relatório de Notificações por prazo";
if ($dt_ini != "--"){
  $head7 = "Período = ".db_formatar($dt_ini,'d')." à ".db_formatar($dt_fin,'d')."";
}
if ($dt_prazo != "--"){
  $head5 = "Prazo Recurso = ".db_formatar($dt_prazo,'d')."";
}
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',6);
$troca = 1;
$alt = 4;
$numRowsResult = $clfiscal->numrows;
for($x = 0; $x < $numRowsResult;$x++){
   db_fieldsmemory($result,$x); 
   if ($setorfiscal == 0){
     $head1 = "RELATÓRIO GERAL";
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',6);
      $pdf->cell(27,$alt,"CÓDIGO NOTIFICAÇÃO",1,0,"C",1);
      $pdf->cell(21,$alt,"IDENTIFICAÇÃO",1,0,"C",1);
      $pdf->cell(27,$alt,"CÓDIGO IDENTIFICAÇÃO",1,0,"C",1);
      $pdf->cell(60,$alt,"CONTRIBUINTE",1,0,"C",1);
      $pdf->cell(20,$alt,"DT NOTIFICAÇÃO",1,0,"C",1);
      $pdf->cell(20,$alt,"DT RECURSO",1,0,"C",1);
      $pdf->cell(50,$alt,"DEPARTAMENTO",1,0,"C",1);
      $pdf->cell(60,$alt,"FISCAL RESPONSAVEL",1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',6);
   $pdf->cell(25,$alt,@$y30_codnoti,0,0,"C",0); 
   $pdf->cell(25,$alt,@$identifica,0,0,"C",0); 
   $pdf->cell(25,$alt,@$codigo,0,0,"C",0); 
   $pdf->cell(60,$alt,@$y30_nome,0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar(@$y30_data,'d'),0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar(@$y30_prazorec,'d'),0,0,"C",0);
   $pdf->cell(50,$alt,@$descrdepto,0,0,"L",0);
   $pdf->cell(60,$alt,@$nome,0,1,"L",0);   
 }
$pdf->Output();
?>