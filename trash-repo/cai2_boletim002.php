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
include("classes/db_boletim_classe.php");
$clrotulo = new rotulocampo;
$clboletim = new cl_boletim;
$clrotulo->label('k11_data'); //metodo que pega o label do campo da tabela indicado
$clrotulo->label('k11_instit');
$clrotulo->label('k11_numbol');
$clrotulo->label('k11_lanca');
$clrotulo->label('k11_anousu');
$clrotulo->label('k11_libera');
$clrotulo->label('nomeinst');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

//verifica se as datas estão setadas para montar o where

$where = '';
if($status == 'p'){
  $where = " and k11_lanca = 't'";
  $head5 = "BOLETINS LANÇADOS ENTRE ".db_formatar("$datai", 'd')." E ".db_formatar("$dataf", 'd');
}elseif($status == 'n'){
  $where = " and (k11_lanca = 'f' or k11_lanca is null) ";
  $head5 = "BOLETINS NÃO LANÇADOS ENTRE ".db_formatar("$datai", 'd')." E ".db_formatar("$dataf", 'd');
} elseif($status == 't'){
  $head5 = "TODOS BOLETINS ENTRE ".db_formatar("$datai", 'd')." E ".db_formatar("$dataf", 'd');
}

$wheremov = '';
if ($registrossemmov == "n") {
  $wheremov = " and movimento != 'Sem movimento'";
}

$anousu=db_getsession("DB_anousu");
$sql ="select distinct 
              x_data as k11_data,
              x_instit as k11_instit,
              x_instit as k12_instit,
              k11_numbol,
              case when k11_libera is null then false else k11_libera end as k11_libera,
              case when k11_lanca  is null then false else k11_lanca  end as k11_lanca,
              x_anousu as k11_anousu,
              nomeinst, 
	            case when boletim.k11_libera='t' 
	                 then 'Liberado'
	                 else 'Não Liberado'
	            end as status,
	            case when boletim.k11_lanca='t' 
	                 then 'Processado'
	                 else 'Não Processado'
	            end  as processamento,
              case when corrente.k12_data is null 
                   then 'Sem movimento'
                   else 'Com movimento'
               end as movimento  
         from ( select distinct k11_data as x_data, k11_instit as x_instit, extract (year from k11_data) as x_anousu from boletim  where k11_data between '$datai' and '$dataf' and extract(year from k11_data) = '$anousu' $where and k11_instit in (".str_replace('-',', ',$db_selinstit).")
                union
                select distinct k12_data, k12_instit, extract (year from k12_data) as x_anousu from corrente where k12_data between '$datai' and '$dataf' and extract(year from k12_data) = '$anousu' and k12_instit in (".str_replace('-',', ',$db_selinstit).")
              ) as x
         left join boletim on k11_data = x_data and k11_instit = x_instit
         inner join db_config on codigo = x_instit
         left join corrente on x_instit = corrente.k12_instit 
                           and x_data   = corrente.k12_data
/*                           
--        where k11_data between '$datai' and '$dataf' 
--          and extract(year from k11_data) = '$anousu'
--	        and k11_instit in (".str_replace('-',', ',$db_selinstit).") 
*/
";

$sql = "select * from ($sql) as x where 1=1 $where $wheremov order by k11_instit, k11_data";

//echo "$sql";exit;
$result = pg_query($sql);

$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro= Informação não encontrada ! ');
}

$sql_descr_instit = "select array_to_string ( array_accum(codigo || '-' ||nomeinstabrev),', ') from ( select codigo, nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") group by codigo order by codigo) as x";
$rs_descr_instit = pg_query($sql_descr_instit) or die($sql_descr_instit);
$descr_instit = pg_result($rs_descr_instit,0,0);

$head4 = "INSTITUICOES: $descr_instit";
$head3 = "RELATÓRIO DE BOLETINS";

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$totalporinstit = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','',8);
$troca = 1;
$alt = 4;
$pdf->addpage();

$instit = 0;

$nomeinstituicao=null;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x,true);
/*
echo "k12_instit:$k12_instit<br>";
  echo "instit:$instit<br>";
  echo "<br>----------<br>";
*/
//   if ($k12_instit !=$instit )
   if ($nomeinst!=$nomeinstituicao){

     if ($nomeinstituicao != null){
       $pdf->Ln(4);
       $pdf->cell(0,$alt,'TOTAL DE REGISTROS DA INSTITUICAO: '.$totalporinstit,"T",0,"L",0);
       $pdf->Ln(4);
     }

     $totalporinstit = 0;
     $nomeinstituicao=$nomeinst;

     $pdf->Ln(2);
     $pdf->setfont('arial','B',8);
     $pdf->cell(20,$alt,$nomeinst,0,1,"L",0);
     $pdf->setfont('arial','',8);
     $pdf->Ln(2);
           
     $pdf->setX(70);
     $pdf->cell(20,$alt,"BOLETIM",'B',0,"L",0);
     $pdf->cell(30,$alt,"STATUS",'B',0,"L",0);
     $pdf->cell(30,$alt,"PROCESSAMENTO",'B',0,"L",0);
     $pdf->cell(30,$alt,"MOVIMENTO",'B',1,"L",0);

   }
   
   $pdf->setX(70);
   $pdf->cell(20,$alt,$k11_data,0,0,"L",0);
   $pdf->cell(30,$alt,$status,0,0,"L",0);
   $pdf->cell(30,$alt,$processamento,0,0,"L",0);
   $pdf->cell(30,$alt,$movimento,0,1,"L",0);
   $total++;
   $totalporinstit++;

}

$pdf->Ln(4);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS DA INSTITUICAO: '.$totalporinstit,"T",0,"L",0);
$pdf->Ln(4);

$pdf->Ln(4);
$pdf->cell(0,$alt,'TOTAL GERAL DE REGISTROS: '.$total,"T",0,"L",0);

$pdf->Output();
   
?>