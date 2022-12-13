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

// assinaturas dos balanços da 4320

$pdf->setfont('arial','',6);
$controle =  "______________________________"."\n"."Controle Interno";
$sec      =  "______________________________"."\n"."Secretaria da Fazenda";
$cont     =  "______________________________"."\n"."Contadoria";
$pref     =  "______________________________"."\n"."Prefeito";

$ass_pref     = $classinatura->assinatura(1000,$pref);
$ass_sec      = $classinatura->assinatura(1002,$sec);
$ass_cont     = $classinatura->assinatura(1005,$cont);
$ass_controle = $classinatura->assinatura(1009,$controle);

if( $pdf->gety() > ( $pdf->h - 35 ) )
   $pdf->addpage($pdf->CurOrientation);

$largura = ( $pdf->w ) / 3;

$pos = $pdf->gety();
$pdf->multicell($largura*1,3,ucwords($ass_pref),0,"C",0,0);

$pdf->setxy($largura,$pos);
$pdf->multicell($largura,3,ucwords($ass_sec),0,"C",0,0);

$pdf->setxy($largura*2,$pos);
$pdf->multicell($largura,3,ucwords($ass_cont),0,"C",0,0);

// $pdf->Ln(10);
// $pdf->setxy($largura,$pos);
// $pos = $pdf->gety();

// $pdf->multicell($largura*3,3,$ass_cont,0,"C",0,0);


// $pdf->setxy($largura,$pos);
// $pdf->multicell($largura,2,$ass_controle,0,"C",0,0);

?>