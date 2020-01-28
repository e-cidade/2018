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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

/// 
$sql ="	
    select  k17_codigo,
            corlanc.k12_data,
            k17_debito,
	    rdeb.o15_codigo as debito_recurso,
	    rdeb.o15_descr as debito_descr,
	    k17_credito ,
	    rcred.o15_codigo as credito_recurso,
	    rcred.o15_descr  as credito_descr,
        case when corrente.k12_estorn = true 
		     then k17_valor*-1
             else k17_valor 
		end as k17_valor,
	    k17_instit
    from slip
         /* para transferencia, as duas contas deve estar no saltes */
         inner join saltes sdeb on sdeb.k13_conta=slip.k17_debito
         inner join saltes scre on scre.k13_conta=slip.k17_credito
         inner join corlanc on k12_codigo=k17_codigo
         inner join corrente on corrente.k12_id     = corlanc.k12_id and corrente.k12_data = corlanc.k12_data and corrente.k12_autent = corlanc.k12_autent     		 
         inner join conplanoreduz rd on rd.c61_reduz=k17_debito and rd.c61_anousu=to_char(corlanc.k12_data,'YYYY')::integer
         inner join orctiporec rdeb on rdeb.o15_codigo = rd.c61_codigo
         inner join conplanoreduz rc on rc.c61_reduz=k17_credito and rc.c61_anousu=to_char(corlanc.k12_data,'YYYY')::integer
         inner join orctiporec rcred on rcred.o15_codigo = rc.c61_codigo
    where slip.k17_instit = ".db_getsession("DB_instit")."
      and corlanc.k12_data between '".$datai."'  and '".$dataf."'
      and rdeb.o15_codigo <> rcred.o15_codigo
      ";
$sql .= " and  ( rdeb.o15_codigo=$recurso or rcred.o15_codigo=$recurso ) ";

$sql .="  order by rdeb.o15_codigo,rcred.o15_codigo,corlanc.k12_data  ";

$result = pg_query($sql);
$rows   = pg_numrows($result);
if(pg_numrows($result) == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados neste periodo.');
}
// echo $sql;
// db_criatabela($result);
// exit;


$head2 = "TRANSFERENCIA POR RECURSO";
$head4 = "PERÍODO : ".db_formatar(@$datai,"d")." A ".db_formatar(@$dataf,"d");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial','',7);
$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);

$alt=4;

$tot_debito    = 0;
$tot_credito   = 0;

$nome_recurso   = '';
$quebra_recurso = '';

for($linha=0;$linha<$rows;$linha++){
    db_fieldsmemory($result,$linha);   

    if ($quebra_recurso=='' || ($quebra_recurso != $debito_recurso && $quebra_recurso != $credito_recurso )){
       
       if ($recurso == $debito_recurso){
	   $quebra_recurso = $debito_recurso;
	   $nome_recurso   = $debito_descr;
       } else {
	   $quebra_recurso = $credito_recurso;
	   $nome_recurso   = $credito_descr;
       }
       $pdf->Ln();
       $pdf->Cell(120,$alt,"RECURSO: $quebra_recurso - $nome_recurso ",'0',1,"L",0);//<BR>      
       // escreve a conta e a descrição + saldo inicial
       $pdf->Cell(30,$alt,"COD.TRANSF",'T',0,"C",1);
       $pdf->Cell(20,$alt,"DATA",'T',0,"C",1);
       $pdf->Cell(80,$alt,"RECURSO",'T',0,"L",1);
       $pdf->Cell(25,$alt,"ENTRADAS",'T',0,"R",1); //<BR>
       $pdf->Cell(25,$alt,"SAIDAS",'T',1,"R",1); //<BR>

       $tot_debito    = 0;
       $tot_credito   = 0;

    }   
    if ($recurso == $debito_recurso){
	   $contra_partida = $credito_recurso;
	   $contra_nome    = $credito_descr;
    } else {
	   $contra_partida = $debito_recurso;
	   $contra_nome    = $debito_descr;
    }
    $pdf->Cell(30,$alt,$k17_codigo,'0','L',"C",0);
    $pdf->Cell(20,$alt,db_formatar($k12_data,'d'),'0',0,"C",0);
    $pdf->Cell(80,$alt,$contra_partida.'-'.$contra_nome,'0',0,"L",0);

    if($k17_valor < 0){
     $pdf->SetTextColor(255,0,0); 
	 $valor = '(-)'.trim(db_formatar(substr($k17_valor,1),'f'));
    }else{
     $valor = db_formatar($k17_valor,'f');
	 $pdf->SetTextColor(0,0,0); 
    }

    if ($recurso == $debito_recurso ){
       
       $pdf->Cell(25,$alt,$valor,0,1,"R",0);
       $tot_debito    += $k17_valor; // entradas
       $pdf->SetTextColor(0,0,0);

    }else {
       $pdf->Cell(25,$alt,'','0',0,"R",0);
       $pdf->Cell(25,$alt,$valor,'0',1,"R",0);
       $tot_credito   +=$k17_valor ;
       $pdf->SetTextColor(0,0,0);	   
    }
 
} 

$pdf->setX(115); 
$pdf->Cell(25,$alt,"TOTAL",'0',0,"L",1);
$pdf->Cell(25,$alt,db_formatar($tot_debito,'f'),'0',0,"R",1);//<BR>
$pdf->Cell(25,$alt,db_formatar($tot_credito,'f'),'0',1,"R",1);

$pdf->SetTextColor(255,0,0); 
$pdf->Cell(150,10,"** Valores negativos correspondem a estornos",'0',1,"L",0);
$pdf->SetTextColor(0,0,0);

$pdf->Output();


?>