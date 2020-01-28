<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
include("fpdf151/pdf.php");

$head3 = "RELATORIO  DA RECEITA ";
$dt = split("-",$data);

$head5 =  "DATA SOLICITADA : $dt[2]/$dt[1]/$dt[0] ";  

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage('L');
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',9);
$alt = 4;

$anousu  = db_getsession("DB_anousu");
$instit  = db_getsession("DB_instit");

$sql = "select o57_fonte as estrutural,
               c74_codrec as receita,
  	       o57_descr as descricao,
    	       c53_tipo as codigo , 
               c53_descr as tipo, 
               c70_codlan as codlan,
               c74_data as data,
	       case when fc_conplano_grupo($anousu,substr(o57_fonte,1,2)||'%',9000) is true then 
	            round(c70_valor*-1,2)
		else 
		    round(c70_valor,2)
	       end as valor,
               c69_debito as debito,
               c69_credito as credito
from conlancamrec 
     inner join orcreceita on c74_anousu = $anousu and o70_codrec=c74_codrec
     inner join conlancam on c70_codlan=c74_codlan
     inner join conlancamval on c69_codlan=conlancam.c70_codlan
     inner join conlancamdoc on c71_codlan=c70_codlan
     inner join conhistdoc on c53_coddoc =conlancamdoc.c71_coddoc
     inner join orcfontes on o57_codfon = o70_codfon and o57_anousu = o70_anousu
     inner join conplanoreduz on  c61_anousu = $anousu and c61_instit= $instit  and (c61_reduz = c69_debito or c61_reduz = c69_credito)
     inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu
      
where c74_data ='$data' and c60_codsis in (5,6)
order by c70_codlan 
	        ";

$result = pg_exec(analiseQueryPlanoOrcamento($sql));
if (pg_numrows($result) == 0 ){
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado");
} 


$total_arrecadado=0;
$total_estornado= 0;
 
// db_criatabela($result);

$imprime=true ;
for($i=0;$i<pg_numrows($result);$i++){
  db_fieldsmemory($result,$i );
	
  if($pdf->gety()>$pdf->h-30 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',6);
      $imprime=true; 

  }  
  if ($imprime == true ){
    $pdf->cell(40,$alt,"COD.LANC",0,0,"R",0);
    $pdf->cell(30,$alt,"CONTA",0,0,"C",0);
    $pdf->cell(15,$alt,"RECEITA ",0,0,"L",0);
    $pdf->cell(100,$alt,"DESCRICAO",0,0,"L",0);
    $pdf->cell(30,$alt,"ARRECADADO",0,0,"R",0);
    $pdf->cell(30,$alt,"ESTORNADO",0,1,"R",0);
    $pdf->Ln(3) ; 
    $imprime=false;
 }
    $pdf->setFont('arial','B',10);
    $pdf->cell(40,$alt,$codlan,0,0,"R",0);
    if ($codigo == 100 ){ 
       $arrecadado = $valor;
       $estornado = 0;   
       $pdf->cell(30,$alt,"$debito ",0,0,"C",0);
    } else {
       $arrecadado = 0;
       $estornado = $valor;  
       $pdf->cell(30,$alt,"$credito",0,0,"C",0);
    } 

    $pdf->cell(15,$alt,"$receita",0,0,"L",0);
    $pdf->cell(100,$alt,"$descricao",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($arrecadado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($estornado,'f'),0,1,"R",0); 
    
    $total_arrecadado = $total_arrecadado + $arrecadado;
    $total_estornado  = $total_estornado  + $estornado;

}
    $pdf->Ln(3);
    $pdf->cell(30,$alt,"TOTAL",'B',0,"R",0);  
    $pdf->cell(155,$alt," ",'B',0,"L",0);  
    $pdf->cell(30,$alt,db_formatar($total_arrecadado,'f'),'B',0,"R",0);
    $pdf->cell(30,$alt,db_formatar($total_estornado,'f'),'B',1,"R",0);  


// ;i; $pdf->ln(3);


$pdf->Output();


?>