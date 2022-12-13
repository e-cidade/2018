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
include("classes/db_coremp_classe.php");

$clcoremp = new cl_coremp;

$clrotulo = new rotulocampo;
$clrotulo->label('e60_numemp ');
$clrotulo->label('e60_codemp ');
$clrotulo->label('e60_numcgm ');
$clrotulo->label('z01_nome   ');
$clrotulo->label('e60_vlremp ');
$clrotulo->label('k12_valor  ');
$clrotulo->label('k12_cheque ');
$clrotulo->label('e60_anousu ');
$clrotulo->label('k12_empen');
$clrotulo->label('k12_data');
$clrotulo->label('k12_autent');
$clrotulo->label('k13_conta');
$clrotulo->label('k13_descr');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$order_by = "order by tipo, credito, k12_data, k12_autent";

$head3    = "Pagamento de Despesa Extra-Orçamentária ";

$where3   = "";
$where    = "where 1=1";

if ($imp_hist == "S"){
     $flag_hist = true;
}

if ($imp_hist == "N"){
     $flag_hist = false;
}

if ($cod!="") {
   $where3 = " and corrente.k12_instit = " . db_getsession("DB_instit") . " and corrente.k12_conta = $cod";
}

$result1 = pg_query("select max(coremp.k12_data) as maior, min(coremp.k12_data) as menor from coremp 
			   inner join corrente on corrente.k12_id = coremp.k12_id and corrente.k12_data=coremp.k12_data  and corrente.k12_autent= coremp.k12_autent
			   inner join saltes on saltes.k13_conta = corrente.k12_conta $where and corrente.k12_instit = " . db_getsession("DB_instit") . " $where3 ");

db_fieldsmemory($result1,0,true);

if (($data!="--")&&($data1!="--")) {
    $where ="where corrente.k12_data  between '$data' and '$data1'   ";  
    $data = db_formatar($data,'d');
    $data1 = db_formatar($data1,'d');
    $head5 = "Periodo de $data ate $data1";
    }else if ($data!="--"){
	$where="where corrente.k12_data >= '$data'     ";
        $data = db_formatar($data,'d');
	$head5 = "Periodo apartir de $data  ";
	}else if ($data1!="--"){
	   $where="where corrente.k12_data <= '$data1'      ";
           $data1 = db_formatar($data1,'d');
	   $head5 = "Periodo ate $data1 ";
	   }else $head5 = "Periodo de $menor ate $maior";

$anousu = db_getsession("DB_anousu");

$sql = "
select  
       k12_id,
       k12_autent,
       k12_data,
       k12_valor,
       case when (h.c60_codsis = 6 and f.c60_codsis = 6) then 'tran'
            when (h.c60_codsis = 6 and f.c60_codsis = 5) then 'tran'
            when (h.c60_codsis = 5 and f.c60_codsis = 6) then 'tran'
       else 'desp' end as  tipo,
       k12_empen,
       k12_codord,
       k12_cheque,
       entrou as debito,
       f.c60_descr as descr_debito,
       f.c60_codsis as sis_debito,
       saiu as credito,
       h.c60_descr as descr_credito,
       h.c60_codsis as sis_credito,
       sl     as k17_codigo,
       corhi  as k12_histcor,
       sl_txt as k17_texto
from 
(select 
       k12_id,
       k12_autent,
       k12_data,
       k12_valor,
       tipo,
       k12_empen,
       k12_codord,
       k12_cheque,
       corlanc as entrou,
       corrente as saiu,
       slp as sl,
       corh as corhi,
       slp_txt as sl_txt
from 
    (select *, 
            case when coalesce(corl_saltes,0) = 0
                   then 'desp'
	           else 'tran'
	    	     end as tipo
    from 
        (select corrente.k12_id,
                corrente.k12_autent,
                corrente.k12_data,
                corrente.k12_valor,
                corrente.k12_conta as corrente,
                c.k13_conta as corr_saltes,
                b.k12_conta as corlanc,
                d.k13_conta as corl_saltes,
				p.k12_empen,
				p.k12_codord,
				p.k12_cheque,
        slip.k17_codigo as slp,
        corhist.k12_histcor as corh,
        slip.k17_texto as slp_txt
         from corrente  
              inner join corlanc b on corrente.k12_id     = b.k12_id     and 
                                      corrente.k12_autent = b.k12_autent and 
                                      corrente.k12_data   = b.k12_data
              inner join slip      on slip.k17_codigo     = b.k12_codigo
              left  join corhist   on corhist.k12_id      = b.k12_id     and
                                      corhist.k12_data    = b.k12_data   and
                                      corhist.k12_autent  = b.k12_autent
              left join coremp p  on corrente.k12_id = p.k12_id 
                                  and corrente.k12_autent=p.k12_autent 
		           	  and corrente.k12_data = p.k12_data
              left join saltes c   on c.k13_conta = corrente.k12_conta
              left join saltes d   on d.k13_conta = b.k12_conta
	 $where and corrente.k12_instit = " . db_getsession("DB_instit") . " $where3 ) as x 
     ) as xx
     ) as xxx
	    inner join conplanoexe   e on entrou = e.c62_reduz
	                              and e.c62_anousu = ".db_getsession('DB_anousu')."
	    inner join conplanoreduz i on e.c62_reduz = i.c61_reduz and i.c61_anousu=".db_getsession("DB_anousu")." and  
                                                        i.c61_instit = " . db_getsession("DB_instit") . "
	    inner join conplano      f on i.c61_codcon = f.c60_codcon and i.c61_anousu = f.c60_anousu 
	    inner join conplanoexe   g on saiu = g.c62_reduz
	                                    and g.c62_anousu = ".db_getsession('DB_anousu')."

	    inner join conplanoreduz j on g.c62_reduz = j.c61_reduz and j.c61_anousu=".db_getsession("DB_anousu")."
	    inner join conplano      h on j.c61_codcon = h.c60_codcon and j.c61_anousu = h.c60_anousu
	    
      $order_by";
/*
$sql="select      coremp.k12_empen, 
                           e60_numemp,
			   e60_codemp, 
		           e60_numcgm, 
		           z01_nome,   
		           k12_valor,  
		           k12_cheque, 
		           e60_anousu,
			   coremp.k12_autent,
			   coremp.k12_data,
			   k13_conta,
			   k13_descr,
			   case when e60_anousu < $anousu then 'RP' else 'Emp' end as tipo
		    from coremp 
                           inner join empempenho on e60_numemp = k12_empen
			   inner join corrente on corrente.k12_id = coremp.k12_id and corrente.k12_data=coremp.k12_data  and corrente.k12_autent= coremp.k12_autent
			   inner join cgm on z01_numcgm = e60_numcgm
			   inner join saltes on saltes.k13_conta = corrente.k12_conta
                    $where $where3
		    $order_by      ";
*/
//echo $sql; exit;
//echo $sql;
//exit;
$result = pg_query($sql);
//db_criatabela($result); exit;
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Pagamentos de Despesa Extra-Orçamentária.');

}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca       = 1;
$alt         = 4;
$total_valor = 0;
$total_geral = 0;
$total_desp  = 0;
$total_tran  = 0;
$credito_ant = 0;
$p           = 0;
$imp_tran    = true;
$imp_desp    = true;

for($x = 0; $x < pg_numrows($result); $x++){
   db_fieldsmemory($result,$x,true);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);

      $pdf->cell(15, $alt,'Conta',                 1,0,"C",1);
      $pdf->cell(215,$alt,'Descrição',             1,1,"C",1);

      $pdf->cell(20,$alt,'Data Autent',            1,0,"C",1);
      $pdf->cell(15,$alt,'Cod.Aut.',               1,0,"C",1);

      $pdf->cell(15,$alt,'Conta',                  1,0,"C",1);
      $pdf->cell(80,$alt,'Descrição',              1,0,"C",1);

      $pdf->cell(20,$alt,'Slip',                   1,0,"C",1);
      $pdf->cell(20,$alt,'Cheque',                 1,0,"C",1);
      $pdf->cell(20,$alt,'Empenho',                1,0,"C",1);
      $pdf->cell(20,$alt,'Ordem',                  1,0,"C",1);
      $pdf->cell(20,$alt,'Valor',                  1,1,"C",1);

      if ($flag_hist == true){
           $pdf->cell(230,$alt,'Histórico',        1,1,"C",1);
      }
      
      $troca = 0;
   }

   if ($credito_ant != $credito){
        if ($credito_ant > 0){
             $pdf->setfont('arial','b',8);
             $pdf->cell(230,($alt+2),"SUBTOTAL: ".db_formatar($total_valor,"f"), "T",1,"R",$p);
             $total_valor = 0;
        }

        if ($tipo == "tran" && $imp_tran == true){
             $pdf->setfont('arial','b',8);
             $pdf->cell(230,($alt+2),"TOTAL DE DESPESAS EXTRA-ORÇAMENTÁRIAS: ".db_formatar($total_desp,"f"), "T",1,"R",$p);

             $pdf->setfont('arial','b',10);
             $pdf->cell(230,($alt+4),'TRANSFERÊNCIAS BANCÁRIAS',"TB",1,"L",$p);
             $imp_tran   = false;
             $total_desp = 0;
        }

        if ($tipo == "desp" && $imp_desp == true){
             $pdf->cell(230,($alt+4),'PAGAMENTOS DE DESPESAS EXTRA-ORÇAMENTÁRIAS',"TB",1,"L",$p);
             $imp_desp = false;
        }

        $pdf->setfont('arial','b',9);
        $pdf->cell(15, ($alt+2),$credito,          "T",0,"C",$p);
        $pdf->cell(215,($alt+2),$descr_credito,    "T",1,"L",$p);

        $credito_ant = $credito;
   }

   if ($tipo == "tran" && $imp_tran == true){
        $pdf->setfont('arial','b',8);
        $pdf->cell(230,($alt+2),"SUBTOTAL: ".db_formatar($total_valor,"f"), "TB",1,"R",$p);

        $pdf->cell(230,($alt+2),"TOTAL DE DESPESAS EXTRA-ORÇAMENTÁRIAS: ".db_formatar($total_desp,"f"), "TB",1,"R",$p);

        $pdf->setfont('arial','b',10);
        $pdf->cell(230,($alt+4),'TRANSFERÊNCIAS BANCÁRIAS',"B",1,"L",$p);

        $imp_tran    = false;
        $total_desp  = 0;
        $total_valor = 0;
   }

   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$k12_data,                   0,0,"C",$p);
   $pdf->cell(15,$alt,$k12_autent,                 0,0,"C",$p);
   
   $pdf->cell(15,$alt,$debito,                     0,0,"C",$p);
   $pdf->cell(80,$alt,$descr_debito,               0,0,"L",$p);

   $pdf->cell(20,$alt,$k17_codigo,                 0,0,"C",$p);
   $pdf->cell(20,$alt,$k12_cheque,                 0,0,"C",$p);
   $pdf->cell(20,$alt,$k12_empen,                  0,0,"C",$p);
   $pdf->cell(20,$alt,$k12_codord,                 0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($k12_valor,"f"), 0,1,"R",$p);

   $pdf->setfont('arial','b',8);

   if ($flag_hist == true){
        if (strlen($k12_histcor) > 0){
             $pdf->multicell(230,$alt,$k12_histcor,     0,1,"L",$p);
        } else if (strlen($k17_texto) > 0){
             $pdf->multicell(230,$alt,$k17_texto,       0,1,"L",$p);
        }
   }
   
   $total_valor += $k12_valor;
   $total_geral += $k12_valor;

   if ($tipo == "desp"){
        $total_desp += $k12_valor;
   }

   if ($tipo == "tran"){
        $total_tran += $k12_valor;
   }

   if ($p == 0){
        $p = 1;
   } else {
        $p = 0;
   }
}

if ($total_valor > 0){
     $pdf->cell(230,($alt+2),"SUBTOTAL: ".db_formatar($total_valor,"f"), "T",1,"R",$p);
}

if ($total_tran > 0){
     $pdf->cell(230,($alt+2),"TOTAL DAS TRANSFERÊNCIAS BANCÁRIAS: ".db_formatar($total_tran,"f"), "T",1,"R",$p);
}

if ($total_geral > 0){
     $pdf->cell(230,$alt,"","T",1,"L",0);
     $pdf->cell(230,$alt,"TOTAL GERAL: ".db_formatar($total_geral,"f"),"TB",1,"R",$p);
}

$pdf->Output();
   
?>