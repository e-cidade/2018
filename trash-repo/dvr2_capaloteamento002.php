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

include("libs/db_sql.php");
include("fpdf151/scpdf.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
db_fieldsmemory(db_query($sqlinst),0,true);

$pdf = new SCPDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
//$pdf->AddPage();
if($loteam == 221){
  $lote = "LOTEAMENTO POR DO SOL";
}else{
  $lote = "LOTEAMENTO SOL NASCENTE";
}

$xmatric = '';
if($matric != '' ){
  $xmatric = ' and j01_matric in('.$matric.')';
} 

$sql = "select * from proprietario_nome where j01_matric in (   select distinct k00_matric
        							from arrecad
        							     inner join diversos on k00_numpre = dv05_numpre
                                              and dv05_instit = ".db_getsession('DB_instit')."
       								      inner join arrematric on arrematric.k00_numpre = arrecad.k00_numpre
								where dv05_procdiver = $loteam $xmatric)"
        ;
//echo $sql;exit;
$result = db_query($sql);
//db_criatabela($result);exit;
if (pg_numrows($result) == 0){
  
   $oParms = new stdClass();
   $oParms->sLista = $lista;
   $sMsg = _M('tributario.diversos.dvr2_capaloteamento002.nao_ha_notificacoes', $oParms);
   db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   exit;
}

$pdf->setfillcolor(235);
$result = db_query($sql);
$preenc = 1;
$pdf->SetFont('Arial','',8);
$linha = 0;
//for($x=0;$x < 8;$x++){
for($x=0;$x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($x%4==0){
      $pdf->addpage();
      $linha = 1;
   }
//   $pdf->rect(5,$linha,200,65);
   $pdf->setfillcolor(235);
   $pdf->RoundedRect(5,$linha,200,65,2,'DF','1234');
   $pdf->setfillcolor(255);
   $pdf->RoundedRect(167,$linha+2,35,20,2,'DF');
   $pdf->RoundedRect(132,$linha+25,70,38,2,'DF');
   $pdf->RoundedRect(7,$linha+30,120,33,2,'DF','1234');
   $pdf->RoundedRect(134,$linha+32,4,4,0,'DF');
   $pdf->SetFont('Arial','B',6);
   $pdf->text(155,$linha+29,"PARA USO DO CORREIO");
   $pdf->SetFont('Arial','',5);
   $pdf->text(140,$linha+34,"Mudou-se");
   $pdf->text(140,$linha+40.5,"Endereço Insuficiente");
   $pdf->text(140,$linha+47,"Não existe o n".chr(176)." indicado");
   $pdf->text(140,$linha+53,"Desconhecido");
   $pdf->text(140,$linha+58.5,"Recusado");
   $pdf->text(176,$linha+34,"Não procurado");
   $pdf->text(176,$linha+40.5,"Ausente");
   $pdf->text(176,$linha+47,"Falecido");
   $pdf->text(176,$linha+52,"Informação escrita pelo");
   $pdf->text(176,$linha+54,"porteiro/síndico");
   $pdf->RoundedRect(134,$linha+38,4,4,0,'DF');
   $pdf->RoundedRect(134,$linha+44,4,4,0,'DF');
   $pdf->RoundedRect(134,$linha+50,4,4,0,'DF');
   $pdf->RoundedRect(134,$linha+56,4,4,0,'DF');
   $pdf->RoundedRect(170,$linha+32,4,4,0,'DF');
   $pdf->RoundedRect(170,$linha+38,4,4,0,'DF');
   $pdf->RoundedRect(170,$linha+44,4,4,0,'DF');
   $pdf->RoundedRect(170,$linha+50,4,4,0,'DF');
   $pdf->SetFont('Arial','',5);
   $pdf->text(180,$linha+5,"CONTRATO");
   $pdf->text(172,$linha+10,"ECT/RS - PREF. SAPIRANGA");
   $pdf->text(182,$linha+15,"304/99");
   $pdf->Image('imagens/files/'.$logo,7,$linha+2,20);
   $pdf->SetFont('Arial','b',12);
   $pdf->text(30,$linha+5,$nomeinst);
   $pdf->text(50,$linha+17,$lote);
   $pdf->SetFont('Arial','B',10);
   $pdf->text(12,$linha+35,"NOME : ".$z01_nome);
   $pdf->text(12,$linha+40,"ENDEREÇO : ".$z01_ender.", ".$z01_numero."  ".$z01_compl);
   $pdf->text(12,$linha+45,"BAIRRO : ".$z01_bairro."   	CEP: ".$z01_cep."  ".$z01_compl);
   $pdf->text(12,$linha+50,"MUNICÍPIO : ".$z01_munic);
   $pdf->text(12,$linha+55,"MATRÍCULA N".chr(176)."  ".$j01_matric);
   
/* $pdf->SetFont('Arial','B',10);
   $pdf->text(10,$linha+45,"NOME LEGÍVEL : ...................................................................................");
   $pdf->SetFont('Arial','',10);
   $pdf->text(10,$linha+55,"______/______/_________");
   $pdf->text(65,$linha+55,"_________________________________________");
   $pdf->text(150,$linha+55,"_________________________");
   $pdf->text(12,$linha+60,"DATA DE ENTREGA");
   $pdf->text(75,$linha+60,"ASSINATURA DO DESTINATÁRIO");
   $pdf->text(165,$linha+60,"FUNC. DA ETC");*/
   $linha += 76;
}
$pdf->Output();