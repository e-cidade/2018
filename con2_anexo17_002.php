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
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
// include("dbforms/db_relrestos.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_empempenho_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$orcparamrel = new cl_orcparamrel;
$classinatura = new cl_assinatura;


$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    } else {
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}


$head2 = "DEMONSTRATIVO DA DÍVIDA FLUTUANTE";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head4 = "ANEXO 17 - PERÍODO : ".strtoupper(db_mes($mesini))." A ".strtoupper(db_mes($mesfin));

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,150);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";

$anousu = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.$mesini.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mesfin.'-'.date('t',mktime(0,0,0,$mesfin,'01',db_getsession("DB_anousu")));

$instituicao = str_replace("-",",",$db_selinstit);

$m_restos    = $orcparamrel->sql_parametro_instit('40','0',"f",$instituicao,db_getsession("DB_anousu"));
$m_servicos  = $orcparamrel->sql_parametro_instit('40','1',"f",$instituicao,db_getsession("DB_anousu"));
$m_depositos = $orcparamrel->sql_parametro_instit('40','2',"f",$instituicao,db_getsession("DB_anousu"));
$m_outros    = $orcparamrel->sql_parametro_instit('40','3',"f",$instituicao,db_getsession("DB_anousu"));

$aOrcParametro = array_merge(
  $m_restos    ,
  $m_servicos  ,
  $m_depositos ,
  $m_outros
);

$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','true','',$aOrcParametro);

// print_r($nome[16]);
// db_criatabela($result);exit;
// sub-totais

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$alt    = 4;
$pagina = 0;

$pdf->addpage('L');
$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt*2,"TITULOS","TBR",0,"C",0);
$pdf->cell(40,$alt,"SALDO DO EXERCICIO ","TR",0,"C",0);
$pdf->cell(80,$alt,"MOVIMENTAÇÃO NO EXERCICIO","BTR",0,"C",0);
$pdf->cell(40,$alt,"SALDO PARA ","T",1,"C",0);
     
$pdf->setX(110);
$pdf->cell(40,$alt,"ANTERIOR R$","BR",0,"C",0);
$pdf->cell(40,$alt,"INSCRIÇÃO","BR",0,"C",0);
$pdf->cell(40,$alt,"BAIXA","BR",0,"C",0);
$pdf->cell(40,$alt,"O EXERCICIO SEGUINTE R$","B",1,"C",0);
$pdf->setfont('arial','',8);

// total geral
$geral_anterior=0;
$geral_inscricao=0;
$geral_baixa=0;
$geral_saldo=0;
// subtotal
$sub_anterior=0;
$sub_inscricao=0;
$sub_baixa=0;
$sub_saldo=0;
$pdf->cell(100,$alt,"RESTOS A PAGAR","R",0,"L",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","0",1,"C",0);
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);

   $v_elementos = array($estrutural,$c61_instit);

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_restos)){
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_restos); $x++){
       if ($estrutural == $m_restos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(90,$alt,"$c60_descr","R",0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_credito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_debito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),"0",1,"R",0);    
      $sub_anterior  += $saldo_anterior;
      $sub_inscricao += $saldo_anterior_credito ;
      $sub_baixa     += $saldo_anterior_debito;
      $sub_saldo     += $saldo_final;    
   }
} 
$pdf->setfont('arial','b',8);
$pdf->cell(10,$alt,"",0,0,"C",0);
$pdf->cell(90,$alt,"SUBTOTAL","R",0,"L",0);
$pdf->cell(40,$alt,db_formatar($sub_anterior,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_inscricao,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_baixa,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_saldo,'f'),"0",1,"R",0);    
$pdf->setfont('arial','',8);


$geral_anterior +=$sub_anterior ;
$geral_inscricao+=$sub_inscricao ;
$geral_baixa    +=$sub_baixa ;
$geral_saldo    +=$sub_saldo ;

$sub_anterior=0;
$sub_inscricao=0;
$sub_baixa=0;
$sub_saldo=0;
$pdf->cell(100,$alt,"SERVIÇOS DA DÍVIDA A PAGAR","R",0,"L",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","0",1,"C",0);
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   
   $v_elementos = array($estrutural,$c61_instit);

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_servicos)){
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_servicos); $x++){
       if ($estrutural == $m_servicos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(90,$alt,"$c60_descr","R",0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_credito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_debito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),"0",1,"R",0);    
      $sub_anterior  += $saldo_anterior;
      $sub_inscricao += $saldo_anterior_credito ;
      $sub_baixa     += $saldo_anterior_debito;
      $sub_saldo     += $saldo_final;
      
   }
} 
$pdf->setfont('arial','b',8);
$pdf->cell(10,$alt,"",0,0,"C",0);
$pdf->cell(90,$alt,"SUBTOTAL","R",0,"L",0);
$pdf->cell(40,$alt,db_formatar($sub_anterior,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_inscricao,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_baixa,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_saldo,'f'),"0",1,"R",0);    
$pdf->setfont('arial','',8);


$geral_anterior +=$sub_anterior ;
$geral_inscricao+=$sub_inscricao ;
$geral_baixa    +=$sub_baixa ;
$geral_saldo    +=$sub_saldo ;

$sub_anterior=0;
$sub_inscricao=0;
$sub_baixa=0;
$sub_saldo=0;
$pdf->cell(100,$alt,"DEPÓSITOS","R",0,"L",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","0",1,"C",0);
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   
   $v_elementos = array($estrutural,$c61_instit);

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_depositos)){
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_depositos); $x++){
       if ($estrutural == $m_depositos[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(90,$alt,"$c60_descr","R",0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_credito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_debito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),"0",1,"R",0);    
      $sub_anterior  += $saldo_anterior;
      $sub_inscricao += $saldo_anterior_credito ;
      $sub_baixa     += $saldo_anterior_debito;
      $sub_saldo     += $saldo_final;
     
   }
} 
$pdf->setfont('arial','b',8);
$pdf->cell(10,$alt,"",0,0,"C",0);
$pdf->cell(90,$alt,"SUBTOTAL","R",0,"L",0);
$pdf->cell(40,$alt,db_formatar($sub_anterior,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_inscricao,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_baixa,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_saldo,'f'),"0",1,"R",0);    
$pdf->setfont('arial','',8);


$geral_anterior +=$sub_anterior ;
$geral_inscricao+=$sub_inscricao ;
$geral_baixa    +=$sub_baixa ;
$geral_saldo    +=$sub_saldo ;

$sub_anterior=0;
$sub_inscricao=0;
$sub_baixa=0;
$sub_saldo=0;
$pdf->cell(100,$alt,"DÉBITOS DE TESOURARIA","R",0,"L",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","R",0,"C",0);
$pdf->cell(40,$alt,"","0",1,"C",0);
for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   
   $v_elementos = array($estrutural,$c61_instit);

   $flag_contar = false;
   if ($c61_instit != 0){
     if (in_array($v_elementos,$m_outros)){
       $flag_contar = true;
     }
   } else {
     for($x = 0; $x < count($m_outros); $x++){
       if ($estrutural == $m_outros[$x][0]){
         $flag_contar = true;
         break;
       }
     }
   }

   if ($flag_contar == true){
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(90,$alt,"$c60_descr","R",0,"L",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_credito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_anterior_debito,'f'),"R",0,"R",0);
      $pdf->cell(40,$alt,db_formatar($saldo_final,'f'),"0",1,"R",0);    
      $sub_anterior  += $saldo_anterior;
      $sub_inscricao += $saldo_anterior_credito ;
      $sub_baixa     += $saldo_anterior_debito;
      $sub_saldo     += $saldo_final;
      
   }
} 
$pdf->setfont('arial','b',8);
$pdf->cell(10,$alt,"",0,0,"C",0);
$pdf->cell(90,$alt,"SUBTOTAL","R",0,"L",0);
$pdf->cell(40,$alt,db_formatar($sub_anterior,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_inscricao,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_baixa,'f'),"R",0,"R",0);
$pdf->cell(40,$alt,db_formatar($sub_saldo,'f'),"0",1,"R",0);    
$pdf->setfont('arial','',8);

$geral_anterior +=$sub_anterior ;
$geral_inscricao+=$sub_inscricao ;
$geral_baixa    +=$sub_baixa ;
$geral_saldo    +=$sub_saldo ;

$pdf->setfont('arial','b',8);
$pdf->cell(100,$alt,"TOTAL","RTB",0,"L",0);
$pdf->cell(40,$alt,db_formatar($geral_anterior,'f'),"TBR",0,"R",0);
$pdf->cell(40,$alt,db_formatar($geral_inscricao,'f'),"TBR",0,"R",0);
$pdf->cell(40,$alt,db_formatar($geral_baixa,'f'),"TBR",0,"R",0);
$pdf->cell(40,$alt,db_formatar($geral_saldo,'f'),"TB",1,"R",0);    

$pdf->Ln(2);
$pdf->setfont('arial','',5);
notasExplicativas(&$pdf,$iCodRel,"2S",190);

$pdf->ln(14);
assinaturas(&$pdf,&$classinatura,'BG');


$pdf->Output();
   
?>