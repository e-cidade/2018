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
include("classes/db_empagepag_classe.php");

$clempagepag = new cl_empagepag;

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('e60_codemp');
$clrotulo->label('e60_anousu');
$clrotulo->label('e83_conta');
$clrotulo->label('e83_descr');
$clrotulo->label('k13_descr');
$clrotulo->label('e86_cheque');
$clrotulo->label('e86_data');
$clrotulo->label('e82_codord');
$clrotulo->label('e81_valor');

db_postmemory($HTTP_POST_VARS);


if(isset($dtini_ano) && $dtini_ano!=""){
  $dtini = "$dtini_ano-$dtini_mes-$dtini_dia";
}else{
  $dtini = date("Y-m-d",db_getsession("DB_datausu"));
}

if(isset($dtfim_ano) && $dtfim_ano!=""){
  $dtfim = "$dtfim_ano-$dtfim_mes-$dtfim_dia";
}else{
  $dtfim = date("Y-m-d",db_getsession("DB_datausu"));
}

$dbwhere   =" e88_data between '$dtini' and '$dtfim' ";
$dbwhere .= "and e80_instit=".db_getsession("DB_instit"); 

if(isset($lista)){
  $listagem = "";
  $virgulas = "";
  for($i=0;$i<sizeof($lista);$i++){
  	$listagem .= $virgulas.$lista[$i];
  	$virgulas  = ",";
  }
  if(trim($listagem) != ""){
  	$in = " in ";
  	if($ver == "sem"){
  	  $in = " not in ";
  	}
    $dbwhere .= " and e83_codtipo $in ($listagem) ";
  }
}
if ($e83_codtipo != 0) {
  $dbwhere .= " and e83_codtipo = {$e83_codtipo}";
}
// die($dbwhere);
$alt="5";
$pdf = new PDF("L"); 
$pdf->Open(); 
$pdf->SetAutoPageBreak(false);
$pdf->AliasNbPages(); 
$head2 = "CHEQUES CANCELADOS";
$head3 = "Data:".db_formatar($dtini,"d")."  à  ".db_formatar($dtfim,"d");
$pri=true;

$pago    = '0.00';
$estorno = '0.00';

$sql_cancelados="select distinct e88_data,
                        e93_cheque as e86_cheque,
                        e93_valor as e81_valor,
	                    e60_codemp,
		                e60_anousu,
		                e86_codmov,
		                e81_codmov,
		                e91_ativo,
		                e82_codord,
		                e93_codcheque,
		                e83_conta,
		                e83_descr,
		                z01_nome,
		                k13_descr,
		                e89_codigo,
		                k12_data
   	               from empageconfchecanc
  	               left join empageconfcanc   on e88_codmov  = empageconfchecanc.e93_codmov  
  	               left join empagemov        on e81_codmov  = empageconfchecanc.e93_codmov
                   left join empage           on e80_codage  = empagemov.e81_codage		
				   				 left join empageconf       on e86_codmov  = e88_codmov
				   				 left join empageconfche	  on e86_codmov  = empageconfche.e91_codmov 
				   				                           and e91_ativo is false			   				  
	                 left join empagepag        on e85_codmov  = empageconfchecanc.e93_codmov
                   left join empagetipo       on e83_codtipo = e85_codtipo				   				   
	                 left join corconf          on k12_codmov  = e93_codcheque and corconf.k12_ativo is true
	                 left join saltes           on k13_conta   = e83_conta
	                 left join empempenho       on e60_numemp  = empagemov.e81_numemp
	                 left join empord           on e82_codmov  = empagemov.e81_codmov
	                 left outer join cgm        on z01_Numcgm  = e60_numcgm
	                 left outer join empageslip on e89_codmov  = e81_codmov
                   left join empagemovforma   on e97_codmov  = e81_codmov
                  where $dbwhere 
                    and (e91_ativo is false or e91_codcheque is null)
                  order by e88_data,
				           e93_cheque,
						   e60_codemp,
						   e89_codigo";
$result05  = $clempagepag->sql_record($sql_cancelados);
$numrows05 = $clempagepag->numrows; 
if ($numrows05 == 0 ){
  db_redireciona('db_erros.php?fechar=true&db_erro=Registros não localizados ! ');
}
$codcheque = 0;
$totcheque = 0;
$vlrtot    = 0;
$pagina    = 0;

for ($c = 0;$c < $numrows05;$c++){
    db_fieldsmemory($result05,$c);
	
    if ( $pdf->gety() > $pdf->h -20 || $pagina==0 ){    
       $pagina = 1;
       $pdf->addpage();
       $pdf->setfillcolor(235);
       $pdf->setfont('arial','b',7);
       
       $pdf->cell(20,4,'Cancelamento',1,0,"C",1);	

       $pdf->cell(20,4,'Seq.',1,0,"C",1);
       
       $pdf->cell(15,4,$RLe86_cheque,1,0,"C",1);
       
       
       
       $pdf->cell(12,4,"Emp.",1,0,"C",1);
       $pdf->cell(80,4,$RLe83_conta,1,0,"C",1);
       $pdf->cell(12,4,$RLe60_anousu,1,0,"C",1);
       $pdf->cell(10,4,$RLe82_codord,1,0,"C",1);
       $pdf->cell(15,4,$RLe81_valor,1,0,"C",1);
       $pdf->cell(60,4,$RLz01_nome,1,0,"C",1);
       $pdf->cell(10,4,"SLIP",1,0,"C",1);
       $pdf->cell(17,4,"Autenticação",1,1,"C",1);
       $pdf->ln(2);
    }   
    $pdf->setfont('arial','',6);
    

    if ($codcheque != $e86_cheque){
      
       $codcheque = $e86_cheque;
       $totcheque ++;

       $pdf->cell(20,4,db_formatar($e88_data,'d'),1,0,"C",0);	 

       $pdf->cell(20,4, $e93_codcheque,1,0,"R",0);
       
       $pdf->cell(15,4,$e86_cheque,1,0,"R",0);
       
    } else {      
       
       //$pdf->cell(35,4,' ',1,0,"L",0);
       //$pdf->cell(20,4,'sqe',1,0,"C",0);
       
       $pdf->cell(20,4,' ',1,0,"L",0);
       $pdf->cell(20,4,$e93_codcheque,1,0,"R",0);
       $pdf->cell(15,4,' ',1,0,"L",0);
       
       
    }  
    
    
    $pdf->cell(12,4,$e60_codemp,1,0,"R",0);
    $pdf->cell(80,4,$e83_conta." - ".$e83_descr,1,0,"L",0);
    $pdf->cell(12,4,$e60_anousu,1,0,"C",0);
    $pdf->cell(10,4,$e82_codord,1,0,"R",0);
    $pdf->cell(15,4,db_formatar($e81_valor,'f'),1,0,"R",0);
    $pdf->cell(60,4,$z01_nome,1,0,"L",0);
    $pdf->cell(10,4,$e89_codigo,1,0,"C",0);
    $pdf->cell(17,4,db_formatar($k12_data,"d"),1,1,"C",0);
    //$pdf->cell(17,4,$e93_codcheque,1,1,"C",0);
    
    
    $vlrtot += $e81_valor;
} 

$pdf->ln();


$pdf->cell(172,4," Total de Cheques: $totcheque             Total Cancelado: ".db_formatar($vlrtot,'f'),0,0,"R",0);


$pdf->Output();

?>