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
require("fpdf151/pdf.php");
include("classes/db_iptuconstr_classe.php");
include("classes/db_iptuconstrdemo_classe.php");
db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$where = " where ";
$total = 0;
$and = "";
$alt = 5;
$borda = 0;
$corfundo = "";


if($tiporel=='1'){

  $head3 = 'Tipo : Data do Arquivo';

$sql = "

select k00_receit,k02_estorc,k02_drecei,round(sum(vlrrec),2) as k00_valor 
from disarq 
     inner join disbanco on disarq.codret = disbanco.codret 
     inner join disrec on disrec.idret = disbanco.idret 
     inner join tabrec on tabrec.k02_codigo = k00_receit 
     inner join taborc on taborc.k02_codigo = k00_receit  and taborc.k02_anousu = ".db_getsession("DB_anousu")."
where autent is false 
  and dtarquivo between '".$datai."' and '".$dataf."' 
group by k00_receit,k02_drecei,k02_estorc
order by k02_estorc
			 ";
}else{
	$head3 = 'Tipo : Data Processamento';
$sql = "

select k00_receit,k02_estorc,k02_drecei,round(sum(vlrrec),2) as k00_valor
from disarq
     inner join disbanco on disarq.codret = disbanco.codret
     inner join disrec on disrec.idret = disbanco.idret
     inner join tabrec on tabrec.k02_codigo = k00_receit
     inner join taborc on taborc.k02_codigo = k00_receit  and taborc.k02_anousu = ".db_getsession("DB_anousu")."
where autent is false
  and dtretorno between '".$datai."' and '".$dataf."'
group by k00_receit,k02_drecei,k02_estorc
order by k02_estorc
                         ";


}
if($agrupa==1){
$sql = "select k02_estorc,k02_drecei,round(sum(k00_valor),2) as k00_valor
        from ($sql) as x 
        group by  k02_estorc,k02_drecei";
}
//die($sql);
$rsResult = pg_query($sql); 
$numrows  = pg_num_rows($rsResult);
if ($numrows == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem valores a serem listados para o filtro selecionado.');
    exit;
}

$head4 = "Periodo : ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
$head5 = "Total da receita Processada/Arrecadada";

$pdf = new pdf();
$pdf->SetFillColor(255);
$pdf->Open();
$pdf->AliasNbPages();

for($i = 0; $i < $numrows;$i++){
  db_fieldsmemory($rsResult,$i);

  if($agrupa==1){
    $k00_receit = 0;
  }

  if ($pdf->gety() > $pdf->h - 30 || $i==0){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',9);
      $pdf->SetFillColor(210);
      $pdf->cell(15,$alt+1,"Receita",1,0,"C");
      $pdf->cell(40,$alt+1,"Estrutural",1,0,"L");
      $pdf->cell(70,$alt+1,"Descricao",1,0,"L");
      $pdf->cell(75,$alt+1,"Valor Arrecadado",1,1,"R");
      $pdf->cell(70,3,"",0,1,"C",0);
   }
   $pdf->setfont('arial','',8);
   
   $pdf->cell(15,$alt+1,$k00_receit,0,0,"C");
   $pdf->cell(40,$alt+1,$k02_estorc,0,0,"L");
   $pdf->cell(70,$alt+1,$k02_drecei,0,0,"L");
   $pdf->cell(75,$alt+1,db_formatar($k00_valor,'f'),0,1,"R");

   $total += $k00_valor;

}
$pdf->setfont('arial','b',8);
$pdf->cell(200,$alt,"TOTAL GERAL : ".db_formatar($total,'f'),'T',0,"R",0);
$pdf->output();
?>