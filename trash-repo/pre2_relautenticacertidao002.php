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
include("libs/db_utils.php");

$oGet         = db_utils::postMemory($_GET,0);
$id_usuario   = db_getsession('DB_id_usuario');

if (isset($oGet->dtini) && isset($oGet->dtfim)) {
  $sDataini = $oGet->dtini;
  $sDatafim = $oGet->dtfim;
  
  if ($sDataini != "--" && $sDatafim != "--") {
  	$sWhere   = " w18_dtvalidacao between '{$sDataini}' and '{$sDatafim}' ";
  	$head6  = "Data de Inicio: ".db_formatar($sDataini,'d')."  a ".db_formatar($sDatafim,'d');
  } else {
  	$sWhere   = "1=1";
  	$head6  = "";
  }
}

if (isset($oGet->ordem) && $oGet->ordem != "") {
   if($oGet->ordem == 'data') {
  	 $sOrder = "w18_dtvalidacao";
  } else if ($oGet->ordem == 'origem') {  
  	$sOrder = "p47_matric,p48_inscr,p49_numcgm";
  }
}

$head2  = "RELATÓRIO DE LOGS";
$head4  = "AUTENTICAÇÃO CERTIDÃO";
$head8  = "Ordenar por: ".$oGet->ordem;

if (isset($id_usuario)) {

  $sqlCertidaoAutentica  = " select *                                                                                         ";
  $sqlCertidaoAutentica .= "    from certidaovalidaonline                                                                     ";
  $sqlCertidaoAutentica .= "         left  join certidaovalidaonlinecert    on w18_sequencial  = w19_certidaovalidaonline     "; 
  $sqlCertidaoAutentica .= "         left  join certidaovalidaonlineusuario on w18_sequencial  = w20_certidaovalidaonline     "; 
  $sqlCertidaoAutentica .= "         left  join certidaomatric              on w19_certidao    = p47_sequencial               ";
  $sqlCertidaoAutentica .= "         left  join certidaoinscr               on w19_certidao    = p48_sequencial               ";
  $sqlCertidaoAutentica .= "         left  join certidaocgm                 on w19_certidao    = p49_sequencial               ";
  $sqlCertidaoAutentica .= "    where {$sWhere}                                                                               "; 
  $sqlCertidaoAutentica .= " order by {$sOrder}                                                                               ";

  $rsCertidaoAutentica  = pg_query($sqlCertidaoAutentica);
  $iCertidaoAutentica   = pg_num_rows($rsCertidaoAutentica);
  
  if ($iCertidaoAutentica == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não foram encontrados registros para esse(s) filtro(s).');
  }

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();  
  $pdf->setfillcolor(235);

  $total = 0;
  $troca = 1;
  $alt   = 4;
  $total = 0;
  
  for ($x = 0; $x < $iCertidaoAutentica; $x++) {
    $oLogsAutCert = db_utils::fieldsMemory($rsCertidaoAutentica,$x);
    
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
    	
      $pdf->addpage();
      $pdf->setfont('arial','b',5);
      $pdf->cell(20,$alt,'Data do Acesso',1,0,"C",1);
      $pdf->cell(10,$alt,"Hora",1,0,"C",1);
      $pdf->cell(15,$alt,'IP',1,0,"C",1);
      $pdf->cell(10,$alt,'Login',1,0,"C",1);
      $pdf->cell(20,$alt,'Certidão',1,0,"C",1);
      $pdf->cell(20,$alt,'Origem',1,0,"C",1);
      $pdf->cell(30,$alt,'Código da Origem',1,0,"C",1);
      $pdf->cell(55,$alt,'Código Digitado',1,0,"C",1);
      $pdf->cell(15,$alt,'Status',1,1,"C",1); 
      $troca = 0;
    }
    
      
    $pdf->setfont('arial','',5);
    $pdf->cell(20,$alt,$oLogsAutCert->w18_dtvalidacao,0,0,"L",0);
    $pdf->cell(10,$alt,$oLogsAutCert->w18_hora,0,0,"L",0);
    $pdf->cell(15,$alt,$oLogsAutCert->w18_ip,0,0,"L",0);
    $pdf->cell(10,$alt,$oLogsAutCert->w20_id_usuario,0,0,"L",0);
    $pdf->cell(20,$alt,$oLogsAutCert->w19_certidao,0,0,"L",0);
      
    if ($oLogsAutCert->p47_matric != "") {
       $pdf->cell(20,$alt,'MATRICULA',0,0,"L",0);
       $pdf->cell(30,$alt,$oLogsAutCert->p47_matric,0,0,"L",0);
    } else if ($oLogsAutCert->p48_inscr != "") {
       $pdf->cell(20,$alt,'INSCRIÇÃO',0,0,"L",0);
       $pdf->cell(30,$alt,$oLogsAutCert->p48_inscr,0,0,"L",0);
    } else if ($oLogsAutCert->p49_numcgm != "") {
       $pdf->cell(20,$alt,'CGM',0,0,"L",0);
       $pdf->cell(30,$alt,$oLogsAutCert->p49_numcgm,0,0,"L",0);
    } else {
       $pdf->cell(20,$alt,'',0,0,"L",0);
       $pdf->cell(30,$alt,'',0,0,"L",0);    	
    }
     
    $pdf->cell(55,$alt,$oLogsAutCert->w18_codigovalidacao,0,0,"L",0);
      
    if ($oLogsAutCert->w18_status == 1) {
       $pdf->cell(15,$alt,'VENCIDA',0,1,"L",0);      	
    } else if ($oLogsAutCert->w18_status == 2) {
       $pdf->cell(15,$alt,'NÃO VENCIDA',0,1,"L",0);
    } else if ($oLogsAutCert->w18_status == 3) {
       $pdf->cell(15,$alt,'INVÁLIDA',0,1,"L",0);
    }
     
    $total++;    
  }
  
  $pdf->cell(195,$alt,'',0,1,0,0);
  
  $pdf->setfont('arial','b',6);
  $pdf->cell(195,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
}

$pdf->Output();
?>