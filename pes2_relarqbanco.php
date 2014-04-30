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
include("classes/db_folha_classe.php");
$clfolha = new cl_folha;
$clfolha->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$result=pg_exec($sql);
if (pg_numrows($result)==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$head3=$rh34_descr;
$numrows = pg_numrows($result);
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$valor=0;
for($x = 0; $x < $numrows;$x++){
  db_fieldsmemory($result,$x);
  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(40,$alt,$RLr38_regist,1,0,"C",1);
    $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
    $pdf->cell(20,$alt,$RLr38_agenc,1,0,"C",1);
    $pdf->cell(30,$alt,$RLr38_conta,1,0,"C",1); 
    $pdf->cell(30,$alt,$RLr38_liq,1,1,"C",1); 
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(40,$alt,"$r38_regist",0,0,"C",0);
  $pdf->cell(60,$alt,"$z01_nome",0,0,"L",0);
  $pdf->cell(20,$alt,"$r38_agenc",0,0,"R",0);
  $pdf->cell(30,$alt,"$r38_conta",0,0,"R",0);
  $pdf->cell(30,$alt,db_formatar($r38_liq,'f'),0,1,"R",0);
  $total++;
  $valor+=$r38_liq;
}
$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'Total de Registrs : '.$total,"T",0,"R",0);
$pdf->cell(75,$alt,'Vaor Total : '.$total,"T",0,"R",0);
$pdf->Output();
?>