<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orcparamrel_classe.php"));
include(modification("classes/db_empresto_classe.php"));
include(modification("classes/db_empempenho_classe.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$orcparamrel = new cl_orcparamrel;
$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;

$xinstit = split("-",$db_selinstit);
$resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    if (strlen(trim($nomeinstabrev)) > 0){
         $descr_inst .= $xvirg.$nomeinstabrev;
         $flag_abrev  = true;
    }else{
         $descr_inst .= $xvirg.$nomeinst;
    }

    $xvirg = ', ';
}


$head2 = "DEMONSTRATIVO EXTRA-ORCAMENTÁRIO";
$head3 = "EXERCÍCIO ".db_getsession("DB_anousu");
$head4 = "PERÍODO : ".strtoupper(db_mes($mesini))." A ".strtoupper(db_mes($mesfin));

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,150);
     }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
  

$where = "c60_codsis = 7 and c61_instit in (".str_replace('-',', ',$db_selinstit).") ";

$anousu = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.$mesini.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mesfin.'-'.date('t',mktime(0,0,0,$mesfin,'01',db_getsession("DB_anousu")));

$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','true');

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$alt    = 4;
$pagina = 0;

$pdf->addpage('L');
$pdf->setfont('arial','b',8);
$pdf->cell(140,$alt*2,"TITULOS","TBR",0,"C",0);
$pdf->cell(35,$alt,"SALDO DO EXERCICIO ","TR",0,"C",0);
$pdf->cell(60,$alt,"MOVIMENTAÇÃO NO EXERCICIO","BTR",0,"C",0);
$pdf->cell(40,$alt,"SALDO PARA ","T",1,"C",0);
     
$pdf->setX(150);
$pdf->cell(35,$alt,"ANTERIOR R$","BR",0,"C",0);
$pdf->cell(30,$alt,"DEBITO","BR",0,"C",0);
$pdf->cell(30,$alt,"CREDITO","BR",0,"C",0);
$pdf->cell(40,$alt,"O EXERCICIO SEGUINTE R$","B",1,"C",0);
$pdf->setfont('arial','',8);


$geral_ativo_anterior  = 0 ;
$geral_ativo_inscricao = 0 ;
$geral_ativo_baixa     = 0 ;
$geral_ativo_saldo     = 0 ;

$geral_passivo_anterior  = 0 ;
$geral_passivo_inscricao = 0 ;
$geral_passivo_baixa     = 0 ;
$geral_passivo_saldo     = 0 ;


for($i=0;$i<pg_numrows($result);$i++){
   db_fieldsmemory($result,$i);
   if ($c61_reduz==0) continue;
  
   if ($saldo_anterior==0 && $saldo_anterior_debito==0 && $saldo_anterior_credito==0 && $saldo_final==0)
      continue;
  
      $pdf->cell(10,$alt,"",0,0,"C",0);
      $pdf->cell(130,$alt,$estrutural.' - '.$c60_descr,"R",0,"L",0);
      $pdf->cell(35,$alt,db_formatar($saldo_anterior,'f'),"R",0,"R",0);
      $pdf->cell(30,$alt,db_formatar($saldo_anterior_debito,'f'),"R",0,"R",0);
      $pdf->cell(30,$alt,db_formatar($saldo_anterior_credito,'f'),"R",0,"R",0);
      $pdf->cell(30,$alt,db_formatar($saldo_final,'f'),"0",1,"R",0);
      
      if (substr($estrutural,0,1)=='1'){
      	  //  totalizador ativo
      	 $geral_ativo_anterior  += $saldo_anterior;
      	 $geral_ativo_inscricao += $saldo_anterior_debito ;
      	 $geral_ativo_baixa     += $saldo_anterior_credito;
      	 $geral_ativo_saldo     += $saldo_final;	
      	
      } else {
      	 $geral_passivo_anterior  += $saldo_anterior;
      	 $geral_passivo_inscricao += $saldo_anterior_debito ;
      	 $geral_passivo_baixa     += $saldo_anterior_credito;
      	 $geral_passivo_saldo     += $saldo_final;      	
      }	
      
} 

$pdf->setfont('arial','b',8);
$pdf->cell(140,$alt,"TOTAL","RTB",0,"L",0);
$pdf->cell(35,$alt,db_formatar($geral_passivo_anterior   - $geral_ativo_anterior,'f'),"TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($geral_ativo_inscricao + $geral_passivo_inscricao,'f'),"TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($geral_ativo_baixa +  $geral_passivo_baixa , 'f'),"TBR",0,"R",0);
$pdf->cell(30,$alt,db_formatar($geral_passivo_saldo - $geral_ativo_saldo  ,'f'),"TB",1,"R",0);    

$pdf->Ln(15);

assinaturas($pdf, $classinatura,'BG');

$pdf->Output();
   
?>
