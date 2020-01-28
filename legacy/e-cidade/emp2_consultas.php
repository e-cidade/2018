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
include("dbforms/db_funcoes.php");
include("classes/db_empempenho_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_empempaut_classe.php");
include("classes/db_empemphist_classe.php");
include("classes/db_emphist_classe.php");
include("classes/db_empempitem_classe.php");
include("classes/db_conlancam_classe.php");
include("classes/db_conlancamemp_classe.php");
include("classes/db_empnotaele_classe.php");
include("classes/db_empnota_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_empagemov_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempempenho   = new cl_empempenho;
$clorcdotacao   = new cl_orcdotacao;
$clempempaut    = new cl_empempaut;
$clempemphist   = new cl_empemphist;
$clemphist      = new cl_emphist;
$clempempitem   = new cl_empempitem;
$clconlancam    = new cl_conlancam;
$clconlancamemp = new cl_conlancamemp;
$clempnotaele   = new cl_empnotaele;
$clempnota      = new cl_empnota;
$clpagordem     = new cl_pagordem;
$clpagordemele  = new cl_pagordemele;
$clempagemov    = new cl_empagemov;
$clrotulo       = new rotulocampo;

$clempempenho->rotulo->label();
$clempempaut->rotulo->label();
$clempemphist->rotulo->label();
$clemphist->rotulo->label();
$clempempitem->rotulo->label();
$clconlancam->rotulo->label();
$clempnotaele->rotulo->label();
$clempnota->rotulo->label();
$clpagordemele->rotulo->label();
$clempagemov->rotulo->label();

$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("z01_nome");
$clrotulo->label("o58_estrutdespesa");
$clrotulo->label("e60_vlrempenho");
$clrotulo->label("e63_codhist");
$clrotulo->label("e41_descr");
$clrotulo->label("e54_anousu");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("c53_descr");
$clrotulo->label("e69_data");
$clrotulo->label("e50_data");
$clrotulo->label("e50_codord");
$clrotulo->label("e81_codage");
$clrotulo->label("e81_codmov");
$clrotulo->label("e82_codord");
$clrotulo->label("e81_valor");
$clrotulo->label("e86_cheque");
$clrotulo->label("e87_dataproc");
$clrotulo->label("e76_codret");
$clrotulo->label("e92_descrerro");

if (isset($e60_numemp) and $e60_numemp !=""){
//  die($clempempenho->sql_query($e60_numemp,"o58_estrutdespesa"));
  $res = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp));
  if ($clempempenho->numrows > 0 ) {   
    db_fieldsmemory($res,0);
  //-----
//    die($clempempaut->sql_query_file($e60_numemp));
    $ra=$clempempaut->sql_record($clempempaut->sql_query_file($e60_numemp));
    if ($clempempaut->numrows > 0){
      db_fieldsmemory($ra,0);
    }	
    //------
//    die($clempemphist->sql_query($e60_numemp));
    $rhist=$clempemphist->sql_record($clempemphist->sql_query($e60_numemp));
    if ($clempemphist->numrows > 0){
      db_fieldsmemory($rhist,0);
  }      													           }
}
$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total  = 0;
$total2 = 0;
$total3 = 0;
$total4 = 0;
$troca = 1;
$alt = 4;

$head3 = "EMPENHO";
$head5 = "DATA EMISSÃO: ".db_formatar($e60_emiss,'d')." VENCIMENTO: ".db_formatar($e60_vencim,'d');
$head6 = "NÚMERO: $e60_numemp";
$head7 = "EMPENHO: $e60_codemp";

$pdf->addpage();
$pdf->setfont('arial','b',8);
$pdf->cell(90,$alt,'DADOS DO EMPENHO',0,1,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(30,$alt,$RLe61_autori,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,@$e61_autori,0,1,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(30,$alt,$RLe60_destin,0,0,"L",0);    
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,$e60_destin,0,1,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(30,$alt,$RLe60_numcgm,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,$e60_numcgm." - ".$z01_nome ,0,1,"L",0);

if (isset($e60_coddot) and ($e60_coddot !="")) {
   $sql= $clorcdotacao->sql_query($e60_anousu,$e60_coddot,"o56_elemento,o56_descr,fc_estruturaldotacao(o58_anousu,o58_coddot) as o58_estrutdespesa, o15_descr");
   $res = $clorcdotacao->sql_record($sql);
   if ($clorcdotacao->numrows >0 ){ 
       db_fieldsmemory($res,0);
   } 
}

$pdf->setfont('arial','b',7);
$pdf->cell(30,$alt,$RLe60_coddot,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,$e60_coddot,0,1,"L",0);
$pdf->cell(30,$alt,'',0,0,"L",0);
$pdf->cell(0,$alt,$o58_estrutdespesa,0,1,"L",0);
$pdf->cell(30,$alt,'',0,0,"L",0);
$pdf->cell(0,$alt,$o56_elemento." - ".$o56_descr,0,1,"L",0);
$pdf->cell(30,$alt,'',0,0,"L",0);
$pdf->cell(0,$alt,$o15_descr,0,1,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,$RLe60_vlremp,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlremp,'f'),0,0,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,$RLe60_vlranu,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlranu+0,'f'),0,1,"L",0);


$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,$RLe60_vlrliq,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlrliq,'f'),0,0,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,$RLe60_vlrpag,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlrpag,'f'),0,1,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,"Saldo empenhado a pagar",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag,'f'),0,0,"L",0);

$pdf->setfont('arial','b',7);
$pdf->cell(40,$alt,"Saldo liquidado a pagar",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(40,$alt,db_formatar($e60_vlrliq - $e60_vlrpag,'f'),0,1,"L",0);



$pdf->setfont('arial','b',7);
$pdf->cell(30,$alt,$RLe60_codtipo,0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(0,$alt,$e60_codtipo." - ".$e41_descr,0,1,"L",0);
$pdf->ln(5);
if(isset($e63_codhist) && trim($e63_codhist)!=""){
  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLe63_codhist,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$e63_codhist,0,1,"L",0);
  $pdf->cell(30,$alt,'',0,0,"L",0);
  $pdf->cell(0,$alt,$e41_descr,0,1,"L",0);
}

if(isset($e60_resumo) && trim($e60_resumo)!=""){
  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLe60_resumo,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->multicell(0,$alt,$e60_resumo,0,1,"L",0);
}

// Carlos, alterado aqui, 11-04-2005 
//if (isset($e61_autori) &&($e61_autori!=0) && ($e61_autori!="")){
  $result_empempitem=$clempempitem->sql_record(
       $clempempitem->sql_query(@$e60_numemp,"","e62_item,pc01_descrmater,e62_codele,e62_descr,e62_quant,e62_vlrun,e62_vltot,o56_elemento as o56_elemento_itens,o56_descr"));

  $numrows_empempitem=$clempempitem->numrows;
//} else {
//  $numrows_empempitem=0;
//}  
//-- 4nd
if (isset($sItens)) {

  $pdf->setfont('arial','b',8);
  $pdf->cell(90,$alt,'ITENS',0,1,"L",0);
  for($i = 0; $i<$numrows_empempitem; $i++) {

    db_fieldsmemory($result_empempitem,$i);
    if($pdf->gety() > $pdf->h - 30 || $troca!=0) {

      if($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage();
      }

      $pdf->setfont('arial','b',8);
      $pdf->cell(17,$alt,"Material",1,0,"C",1);
      $pdf->cell(17,$alt,"Elemento",1,0,"C",1);
      $pdf->cell(17,$alt,$RLe62_quant,1,0,"C",1);
      $pdf->cell(19,$alt,$RLe62_vlrun,1,0,"C",1);
      $pdf->cell(17,$alt,$RLe62_vltot,1,0,"C",1);
      $pdf->cell(18,$alt,"Elemento",1,0,"C",1);
      $pdf->cell(90,$alt,"Descrição elemento",1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc01_descrmater,1,1,"C",1);
      $pdf->cell(255,$alt,"Descrição",1,1,"C",1);	  
      $troca = 0;

    }
    $pdf->setfont('arial','',6);
    $pdf->cell(17,$alt,$e62_item,"T",0,"C",0);
    $pdf->cell(17,$alt,$e62_codele,"T",0,"C",0);
    $pdf->cell(17,$alt,$e62_quant,"T",0,"C",0);
    $pdf->cell(19,$alt,$e62_vlrun,"T",0,"R",0);
    $pdf->cell(17,$alt,db_formatar($e62_vltot,'f'),"T",0,"R",0);
    $pdf->cell(18,$alt,$o56_elemento_itens,"T",0,"C",0);
    $pdf->cell(90,$alt,$o56_descr,"T",0,"L",0);
    $pdf->multicell(60,4,substr($pc01_descrmater,0,55),"T","L",0);
    $pdf->cell(255,$alt,$e62_descr,"T",1,"L",0);	
    $total++;

  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(255,$alt,'TOTAL DE ITENS DESTE EMPENHO :  '.$total,"T",1,"L",0);
  $pdf->ln(5);
}
if (isset($sLancamentos)) {

  $sql = " select c70_codlan,
                  c70_data, 
                  c53_descr,
                  c70_valor 
             from conlancamemp
                  inner join conlancam on c70_codlan = c75_codlan
                  inner join conlancamdoc on c71_codlan = c70_codlan
                  inner join conhistdoc on c53_coddoc = c71_coddoc
            where c75_numemp=$e60_numemp
            order by c70_data ";

  $result_lancamentos=$clconlancamemp->sql_record($sql);
  $numrows_lancamentos=$clconlancamemp->numrows;
  $pdf->setfont('arial','b',8);
  if($numrows_lancamentos == 0) {
    $pdf->cell(0, $alt, 'EMPENHO SEM LANÇAMENTOS',0,1,"L",0);
  } else {

    $pdf->cell(0,$alt,'LANÇAMENTOS',0,1,"L",0);
    for($i = 0; $i < $numrows_lancamentos; $i++) {
      db_fieldsmemory($result_lancamentos,$i);
      if($pdf->gety() > $pdf->h - 30 || $troca!=0 || $i==0) {
        if($pdf->gety() > $pdf->h - 30) {
          $pdf->addpage();
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell(30,$alt,$RLc70_codlan,1,0,"C",1);
        $pdf->cell(30,$alt,$RLc70_data,1,0,"C",1);
        $pdf->cell(65,$alt,$RLc53_descr,1,0,"C",1);
        $pdf->cell(30,$alt,$RLc70_valor,1,1,"C",1);
        $troca = 0;
      }
      $pdf->setfont('arial','',6);
      $pdf->cell(30,$alt,$c70_codlan,"T",0,"C",0);
      $pdf->cell(30,$alt,db_formatar($c70_data,'d'),"T",0,"C",0);
      $pdf->cell(65,$alt,$c53_descr,"T",0,"L",0);
      $pdf->cell(30,$alt,db_formatar($c70_valor,'f'),"T",1,"R",0);
      $total2++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(155,$alt,'TOTAL DE LANÇAMENTOS DESTE EMPENHO :  '.$total2,"T",1,"L",0);
  }
  $pdf->ln(5);
}
if (isset($sNotas)) {
  //die($clempnota->sql_query_file(null,"*",'',"e69_numemp=$e60_numemp")); 
  $sSqlNotasLiq  = "select e69_codnota, ";
  $sSqlNotasLiq .= "       e69_numero,  ";
  $sSqlNotasLiq .= "       e69_dtnota,  ";
  $sSqlNotasLiq .= "       e70_valor,   ";
  $sSqlNotasLiq .= "       e70_vlrliq,  ";
  $sSqlNotasLiq .= "       e70_vlranu,  ";
  $sSqlNotasLiq .= "       e53_vlrpag   ";
  $sSqlNotasLiq .= "  from empnota      ";
  $sSqlNotasLiq .= "       inner join empnotaele   on e70_codnota = e69_codnota ";
  $sSqlNotasLiq .= "       left  join pagordemnota on e70_codnota = e71_codnota ";
  $sSqlNotasLiq .= "                               and e71_anulado is false      ";
  $sSqlNotasLiq .= "       left  join pagordemele  on e71_codord  = e53_codord  ";
  $sSqlNotasLiq .= " where e69_numemp = {$e60_numemp}";
  $sSqlNotasLiq .= " order by  e69_dtnota";
  $result_notas  = $clempnota->sql_record($sSqlNotasLiq); 
  $numrows_notas = $clempnota->numrows;
  $tot2_valor  = 0;
  $tot2_vlrliq = 0;          
  $tot2_vlranu = 0;
  $tot2_vlrpag = 0;
  if($numrows_notas==0){
    $pdf->cell(0,$alt,'EMPENHO SEM NOTAS',0,1,"L",0);
  }else{
    $pdf->cell(0,$alt,'NOTAS',0,1,"L",0);
    for($i = 0; $i<$numrows_notas; $i++){
      db_fieldsmemory($result_notas,$i);
      if($pdf->gety() > $pdf->h - 30 || $troca!=0 || $i==0){
        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage();
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell(30,$alt,$RLe70_codnota,1,0,"C",1);
        $pdf->cell(25,$alt,$RLe69_numero,1,0,"C",1);
        $pdf->cell(25,$alt,$RLe70_valor,1,0,"C",1);
        $pdf->cell(25,$alt,"Liquidado",1,0,"C",1);
        $pdf->cell(25,$alt,"Anulado",1,0,"C",1);
        $pdf->cell(25,$alt,"pago",1,0,"C",1);
        $pdf->cell(25,$alt,$RLe69_dtnota,1,1,"C",1);
        $troca = 0;
      } 
      $pdf->setfont('arial','',6);
      $pdf->cell(30,$alt,$e69_codnota,0,0,"C",0);
      $pdf->cell(25,$alt,$e69_numero,0,0,"C",0);
      $pdf->cell(25,$alt,db_formatar($e70_valor,"f"),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($e70_vlrliq,"f"),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($e70_vlranu,"f"),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($e53_vlrpag,'f'),0,0,"R",0);
      $pdf->cell(25,$alt,db_formatar($e69_dtnota,'d'),0,1,"C",0);
      $tot2_valor  += $e70_valor ;
      $tot2_vlrliq += $e70_vlrliq;          
      $tot2_vlranu += $e70_vlranu;
      $tot2_vlrpag += $e53_vlrpag;
      $total3++;    
      }
    }
    $pdf->cell(55,$alt,"Total","T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tot2_valor,"f"),"T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tot2_vlrliq,"f"),"T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tot2_vlranu,"f"),"T",0,"R",1);
    $pdf->cell(25,$alt,db_formatar($tot2_vlrpag,"f"),"T",0,"R",1);
    $pdf->cell(25,$alt,"","TB",1,"C",1);
    $pdf->setfont('arial','b',8);
    $pdf->cell(155,$alt,'TOTAL DE NOTAS DESTE EMPENHO :  '.$total3,"T",1,"L",0);
    $pdf->ln(5);
}
if (isset($sAgenda)) {
  $sql = $clempagemov->sql_query_consemp(null,"
             e81_codage,
             e80_data,
             e81_codmov,
             e82_codord,
             e83_descr,
             e81_valor,
             e90_codgera,
             case when e96_descr = 'DIN' then 'DINHEIRO'
                  else case when e96_descr = 'CHE' then 'CHEQUE'
                       else case when e96_descr = 'TRA' then 'TRANSMISSÃO'
                            else case when e86_codmov is not null and e86_cheque <> '0' then 'CHEQUE'
                                 else '...'
                            end
                       end
                 end
             end as e96_descr
             ,
             e86_cheque,
             case when e86_codmov is not null and e86_cheque <> '0' then e86_data 
                  else e87_dataproc 
             end as e87_dataproc,
             e76_codret,
             case when e86_codmov is not null and e86_cheque <> '0' and round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2) = 0 then 'MOVIMENTO PAGO'
                  else case when e86_codmov is not null and e86_cheque <> '0' and round(e53_valor,2)-round(e53_vlranu,2)-round(e53_vlrpag,2) > 0 then 'A PAGAR'
                       else e92_descrerro
                  end
             end as e92_descrerro","
             e81_codage,
             e81_codmov","
             e60_numemp=$e60_numemp"
             );
             // die($sql);
  $result_agenda = $clempagemov->sql_record($sql);
  $numrows_agenda = $clempagemov->numrows;
  $arquivoant = "";
  if($numrows_agenda == 0){
    $pdf->cell(0,$alt,'EMPENHO SEM AGENDAMENTO',0,1,"L",0);
  }else{
    $pdf->cell(0,$alt,'AGENDAMENTOS',0,1,"L",0);
    for($i = 0; $i<$numrows_agenda; $i++){
      db_fieldsmemory($result_agenda,$i);
      if($pdf->gety() > $pdf->h - 30 || $troca!=0 || $i==0){
        if($pdf->gety() > $pdf->h - 30){
	      $pdf->addpage();
        }

        $pdf->setfont('arial','b',8);
        $pdf->cell(15,$alt,$RLe81_codage,1,0,"C",1);
        $pdf->cell(15,$alt,$RLe81_codmov,1,0,"C",1);
        $pdf->cell(15,$alt,$RLe82_codord,1,0,"C",1);
        $pdf->cell(60,$alt,"Conta Pagadora",1,0,"C",1);
        $pdf->cell(20,$alt,$RLe81_valor,1,0,"C",1);
        $pdf->cell(15,$alt,"Arquivo",1,0,"C",1);
        $pdf->cell(20,$alt,"Forma",1,0,"C",1);
        $pdf->cell(15,$alt,$RLe86_cheque,1,0,"C",1);
        $pdf->cell(15,$alt,"Autoriza",1,0,"C",1);
        $pdf->cell(15,$alt,"Retorno",1,0,"C",1);
        $pdf->cell(60,$alt,$RLe92_descrerro,1,1,"C",1);
        $troca = 0;
      } 

      $pdf->setfont('arial','',6);
      $pdf->cell(15,$alt,$e81_codage,0,0,"C",0);
      $pdf->cell(15,$alt,$e81_codmov,0,0,"C",0);
      $pdf->cell(15,$alt,$e82_codord,0,0,"C",0);
      $pdf->cell(60,$alt,$e83_descr ,0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($e81_valor,"f"),0,0,"R",0);
      $pdf->cell(15,$alt,$e90_codgera,0,0,"C",0);
      $pdf->cell(20,$alt,$e96_descr,0,0,"L",0);
      $pdf->cell(15,$alt,$e86_cheque,0,0,"C",0);
      $pdf->cell(15,$alt,db_formatar($e87_dataproc,"d"),0,0,"C",0);
      $pdf->cell(15,$alt,$e76_codret,0,0,"C",0);
      $pdf->cell(60,$alt,$e92_descrerro,0,1,"L",0);
      $total4++;
    }
  }
}  
$pdf->Output();
?>