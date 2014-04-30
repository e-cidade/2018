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
include("libs/db_utils.php");
include("fpdf151/scpdf.php");
db_postmemory($HTTP_SERVER_VARS);

$instit = db_getsession("DB_instit");
$sqlinst = "select * from db_config where codigo = ".db_getsession("DB_instit");
db_fieldsmemory(db_query($sqlinst),0,true);

$head3 = 'Relação de Notificações';
$pdf = new SCPDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
//$pdf->AddPage();

if (isset($notifparc)){

	$sql= " select k55_matric   as j01_matric,
					          k56_inscr	   as q02_inscr,
					          k57_numcgm   as z01_numcgm,
					          case when k55_matric is not null
					               then (select lpad(z01_numcgm,6,0)||' '||z01_nome
					                     from proprietario_nome
					                     where j01_matric = k55_matric limit 1)
					               else case when k56_inscr is not null
					                         then (select lpad(q02_numcgm,6,0)||' '||z01_nome
					                               from empresa
					                               where q02_inscr = k56_inscr limit 1)
					                    else (select lpad(z01_numcgm,6,0)||' '||z01_nome
					                          from cgm
					                          where z01_numcgm = k57_numcgm limit 1)
					                    end
					           end as z01_nome,
					           z01_ender,
					           z01_numero,
					           z01_compl,
					           sum(k43_vlrcor+k43_vlrjur+k43_vlrmul-k43_vlrdes) as valor
					   from notidebitosreg
					        left  join notimatric  on k43_notifica   = k55_notifica
					        left  join notiinscr   on k43_notifica   = k56_notifica
					        left  join notinumcgm  on k43_notifica   = k57_notifica
					        inner join cgm 		   on cgm.z01_numcgm = k57_numcgm
					  where k43_notifica = {$notifica}	     
					   group by k43_notifica,
					        k55_matric,
					        k56_inscr,
					        k57_numcgm,
					        z01_nome, 
					        z01_ender,
					        z01_numero,
					        z01_compl";

     $result      = db_query($sql) or die($sql);
     $oNotifParc  = db_utils::fieldsMemory($result,0);
     
		if (isset($oNotifParc->j01_matric) && trim($oNotifParc->j01_matric) != ""){
			
			$k60_tipo = "M";
		    $xtipo    = 'Matrícula';
		    $xcodigo  = 'matric';
		    $xcodigo1 = 'j01_matric';
		    $xxcodigo1 = 'k55_matric';
		    $xxmatric = ' inner join notimatric on matric = k55_matric ';
		    $xxcodigo = 'k55_notifica';

		} else if(isset($oNotifParc->q02_inscr) && trim($oNotifParc->q02_inscr) != ""){
			
			$k60_tipo = "I";
		    $xtipo    = 'Inscrição';
		    $xcodigo  = 'inscr';
		    $xcodigo1 = 'q02_inscr';
		    $xxcodigo1 = 'k56_inscr';
		    $xxmatric = ' inner join notiinscr on inscr = k56_inscr ';
		    $xxcodigo = 'k56_notifica';
		
		} else {
			$k60_tipo = "N";
		    $xtipo    = 'Numcgm';
		    $xcodigo  = 'numcgm';
		    $xcodigo1 = 'z01_numcgm';
		    $xxcodigo1 = 'k57_numcgm';
		    $xxmatric = ' inner join notinumcgm on numcgm = k57_numcgm ';
		    $xxcodigo = 'k57_notifica';
		    
		}
  
	
} else {

if ( $lista == '' ) {
   $sMsg = _M('tributario.notificacoes.cai2_emitenotif004.lista_nao_encontrada');
   db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   exit; 
}

$sql = "select * from lista where k60_codigo = $lista and k60_instit = $instit";
$result = db_query($sql);
db_fieldsmemory($result,0);
//$tipos = '31';

if ($k60_tipo == 'M'){
    $xtipo    = 'Matrícula';
    $xcodigo  = 'matric';
    $xcodigo1 = 'j01_matric';
    $xxmatric = ' inner join notimatric on matric = k55_matric ';
    $xxcodigo = 'k55_notifica';
    if (isset($campo)){
       if ($tipo == 2){
           $contr = 'and matric in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
           $contr = 'and matric not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}elseif($k60_tipo == 'I'){
    $xtipo    = 'Inscrição';
    $xcodigo  = 'inscr';
    $xcodigo1 = 'q02_inscr';
    $xxmatric = ' inner join notiinscr on matric = k56_inscr ';
    $xxcodigo = 'k56_notifica';
    if (isset($campo)){
       if ($tipo == 2){
           $contr = 'and inscr in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
           $contr = 'and inscr not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}elseif($k60_tipo == 'N'){
    $xtipo    = 'Numcgm';
    $xcodigo  = 'numcgm';
    $xcodigo1 = 'z01_numcgm';
    $xxmatric = ' inner join notinumcgm on matric = k57_numcgm ';
    $xxcodigo = 'k57_notifica';
    if (isset($campo)){
       if ($tipo == 2){
           $contr = 'and numcgm in ('.str_replace("-",", ",$campo).') ';
       }elseif ($tipo == 3){
           $contr = 'and numcgm not in ('.str_replace("-",", ",$campo).') ';
       }
    }else{
       $contr = '';
    }
}
$sql = "select $xxcodigo as notifica,$xcodigo1,z01_numcgm,z01_nome,z01_ender,z01_numero,z01_compl,sum(valor_vencidos) as xvalor
          from 
          (select distinct $xcodigo as $xcodigo1,
               $xxcodigo,
               " . ($xcodigo ==  "numcgm"?"":"z01_numcgm,") .
               "z01_nome,
               z01_ender,z01_numero,z01_compl,
               valor_vencidos
           from (select distinct k61_numpre,k61_codigo,k60_datadeb 
	            from listadeb 
		        inner join lista on k60_codigo = k61_codigo and k60_instit = $instit) as a 
	            inner join devedores b on a.k61_numpre = b.numpre and b.data = a.k60_datadeb
                $xxmatric $contr
                inner join cgm on z01_numcgm = b.numcgm
        where k61_codigo = $lista ) as y
        group by $xxcodigo,$xcodigo1,z01_numcgm,z01_nome,z01_ender,z01_numero,z01_compl
        order by z01_nome
        ";

$result = db_query($sql);
if (pg_numrows($result) == 0){
  
   $oParms = new stdClass();
   $oParms->sLista = $lista;
   $sMsg = _M('tributario.notificacoes.cai2_emitenotif004.nao_existe_notificacoes', $oParms);
   db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
   exit;
}
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
   $pdf->Image('imagens/files/'.$logo,5,$linha,12);
   $pdf->SetFont('Arial','b',12);
   $pdf->text(30,$linha+5,$nomeinst);
   $pdf->text(50,$linha+12,"Notificação : ".db_formatar($notifica,'s','0',5,'e'));
   $pdf->SetFont('Arial','',10);
   $pdf->text(10,$linha+25,"Destinatário : ".$z01_nome);
   $pdf->text(10,$linha+30,"Matrícula N".chr(176)."  ".$$xcodigo1);
   $pdf->text(10,$linha+35,"Endereço : ".$z01_ender.", ".$z01_numero."  ".$z01_compl);
   $pdf->SetFont('Arial','B',10);
   $pdf->text(10,$linha+45,"NOME LEGÍVEL : ...................................................................................");
   $pdf->SetFont('Arial','',10);
   $pdf->text(10,$linha+55,"______/______/_________");
   $pdf->text(65,$linha+55,"_________________________________________");
   $pdf->text(150,$linha+55,"_________________________");
   $pdf->text(12,$linha+60,"DATA DE ENTREGA");
   $pdf->text(75,$linha+60,"ASSINATURA DO DESTINATÁRIO");
   $pdf->text(165,$linha+60,"FUNC. DA ETC");
   $linha += 76;
}
$pdf->Output();