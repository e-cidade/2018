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
include("fpdf151/pdf.php");
include("classes/db_notificacao_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$head2 = 'Posição das Notificões ';
$clnotificacao = new cl_notificacao;

$aOpcoesSituacao  = explode("|",$situacao);

foreach($aOpcoesSituacao as $i => $sOpcao ) {
	switch ($sOpcao) {
		case 'parcialmente_pago':
			$aTipo[] = 'PARCIALMENTE PAGO';
			break;
		case 'totalmente_pago':
		    $aTipo[] = 'TOTALMENTE PAGO';
			break;
		case 'totalmente_debito':
		    $aTipo[] = 'TOTALMENTE EM DÉBITO';
			break;
		case 'parcelamento_anulado':
			$aTipo[] = 'PARCELAMENTO ANULADO';
			break;
		case 'parcialmente_cancelado':
			$aTipo[] = 'PARCIALMENTE CANCELADO';
			break;
		case 'totalmente_cancelado':
			$aTipo[] = 'TOTALMENTE CANCELADO';
			break;
		case 'reparc_totalmente_debito':
			$aTipo[] = 'REPARCELADO E TOTALMENTE EM DÉBITO';
			break;
		case 'reparc_parcialmente_pago':
			$aTipo[] = 'REPARCELADO E PARCIALMENTE PAGO';
			break;
		case 'reparc_totalmente_pago':
			$aTipo[] = 'REPARCELADO E TOTALMENTE PAGO';
			break;
	  case 'parc_totalmente_debito':
			$aTipo[] = 'PARCELADO E TOTALMENTE EM DÉBITO';
			break;
		case 'parc_parcialmente_pago':
			$aTipo[] = 'PARCELADO E PARCIALMENTE PAGO';
			break;
		case 'parc_totalmente_pago':
			$aTipo[] = 'PARCELADO E TOTALMENTE PAGO';
			break;
		case 'aju_totalmente_debito':
			$aTipo[] = 'DÉBITO AJUIZADO E TOTALMENTE EM DÉBITO';
			break;
		case 'aju_parcialmente_pago':
			$aTipo[] = 'DÉBITO AJUIZADO E PARCIALMENTE PAGO';
			break;
		case 'aju_totalmente_pago':
			$aTipo[] = 'DÉBITO AJUIZADO E TOTALMENTE PAGO';
			break;
		case 'aju_parcelado_debito':
			$aTipo[] = 'DÉBITO AJUIZADO E PARCIALMENTE EM DÉBITO';
			break;
		case 'cda_sem_inicial':
			$aTipo[] = 'CDA SEM INICIAL';
		break;
	}
}

$iInstit = db_getsession("DB_instit");
$sWhere  = " where 1=1 ";

if ( isset($campo) ) {
  $sWhere .= " and k63_codigo in (".str_replace('-',' ,',$campo).")";
}

$sSqlNotiDebitos  = " select distinct 									                                                        ";
$sSqlNotiDebitos .= "				 notidebitos.k53_notifica,                                                                  ";
$sSqlNotiDebitos .= "				 lista.k60_tipo, 					                                                        ";
$sSqlNotiDebitos .= "				 lista.k60_codigo					                                                        ";
$sSqlNotiDebitos .= "		from notidebitos							                                                        ";
$sSqlNotiDebitos .= "				 inner join notificacao   on notidebitos.k53_notifica   = notificacao.k50_notifica 			";
$sSqlNotiDebitos .= "										 and notificacao.k50_instit 		= ".db_getsession("DB_instit")."";
$sSqlNotiDebitos .= "				 inner join listanotifica on listanotifica.k63_notifica = notificacao.k50_notifica 			";
$sSqlNotiDebitos .= "				 inner join lista         on listanotifica.k63_codigo   = lista.k60_codigo 					";
$sSqlNotiDebitos .= "										 and lista.k60_instit = $iInstit									";
$sSqlNotiDebitos .= "				$sWhere 																					";
$sSqlNotiDebitos .= "order by lista.k60_codigo, 																				";
$sSqlNotiDebitos .= "				  notidebitos.k53_notifica																	";

$rsNotiDebitos = db_query($sSqlNotiDebitos) or die($sSqlNotiDebitos);


if (pg_num_rows($rsNotiDebitos) == 0) {
  
  $sMsg = _M('tributario.notificacoes.cai2_posnotif002.sem_registros');
  db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
  exit; 
}


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);

db_fieldsmemory($rsNotiDebitos, 0);

$totalportipo = array();
$lista 				= $k60_codigo;
$impcab 			= true;

$linhasNotiDebitos = pg_num_rows($rsNotiDebitos);

for($x=0;$x < $linhasNotiDebitos;$x++) {
  db_fieldsmemory($rsNotiDebitos, $x);

    $sSqlNotificacao = $clnotificacao->sql_query_nome(null,"k50_notifica = $k53_notifica and k50_instit = $iInstit");
	$rsNotificacao 	 = $clnotificacao->sql_record($sSqlNotificacao);

	
	if ($clnotificacao->numrows == 0) {
    die("erro ao executar sql: " . $clnotificacao->sql_query_nome(null,"k50_notifica = $k53_notifica and k50_instit = $iInstit"));
  }
  
	db_fieldsmemory($rsNotificacao,0);

  
if(($pdf->gety() > $pdf->h - 30) or $impcab == true) {
    $pdf->addpage();
  
	$pdf->setfont('arial',"B",7);
    $pdf->cell(10,5,'NOTIF'				  ,1,0,"L",1);
    $pdf->cell(80,5,'NOME/RAZÃO SOCIAL'   ,1,0,"L",1);
    $pdf->cell(20,5,'MAT/INSCR' 	   	  ,1,0,"L",1);
    $pdf->cell(60,5,'TIPO'				  ,1,0,"L",1);
    $pdf->cell(20,5,'LISTA'				  ,1,1,"L",1);
	$pdf->setfont('arial',"",7);
		
	$impcab = false;
  }

  if ($k60_tipo == "M") {
		$xmat = "Matr: $k55_matric";
	} elseif ($k60_tipo == "I") {
		$xmat = "Inscr: $k56_inscr";
	} elseif ($k60_tipo == "N") {
		$xmat = "CGM: $k57_numcgm";
	}

 	 $tipo = "";

	 if ( $tipo == "" ) {

		    $sql_parcialpago = "select * from (
												select	(select count(*) from arrecad  where arrecad.k00_numpre  = notidebitos.k53_numpre and arrecad.k00_numpar  = notidebitos.k53_numpar) as arrecad,
														(select count(*) from arrepaga where arrepaga.k00_numpre = notidebitos.k53_numpre and arrepaga.k00_numpar = notidebitos.k53_numpar) as arrepaga
								   				   from notidebitos
														where k53_notifica = $k53_notifica
											  ) as x
														where arrecad > 0 and arrepaga > 0";
			$result_parcialpago = db_query($sql_parcialpago) or die($sql_parcialpago);
			
		 if ( pg_numrows($result_parcialpago) > 0 ) {
			 $tipo = "PARCIALMENTE PAGO";
		 }

	 }
	 if ( $tipo == "") {
		 
		 $sql_totaldevendo = "	select * from (
															select	(select count(*) from arrecad  where arrecad.k00_numpre  = notidebitos.k53_numpre and arrecad.k00_numpar = notidebitos.k53_numpar)  as arrecad,
																	(select count(*) from arrepaga where arrepaga.k00_numpre = notidebitos.k53_numpre and arrepaga.k00_numpar =notidebitos.k53_numpar)  as arrepaga,
	 																(select count(*) from arrecant where arrecant.k00_numpre = notidebitos.k53_numpre and arrecant.k00_numpar = notidebitos.k53_numpar) as arrecant
															from notidebitos
															where k53_notifica = $k53_notifica) as x
															where arrecad > 0 and arrepaga = 0 and arrecant = 0";
		 $result_total_devendo = db_query($sql_totaldevendo) or die($sql_totaldevendo);
		 if (pg_numrows($result_total_devendo) > 0) {
			 $tipo = "TOTALMENTE EM DÉBITO";
			 $ltotalmente_debito;
		 }
   
	 }

	 if ( $tipo == "") {

		 $sql_totalpago = "	select * from (
													select	(select count(*) from arrepaga where arrepaga.k00_numpre  = notidebitos.k53_numpre and arrepaga.k00_numpar = notidebitos.k53_numpar) as arrepaga,
															(select count(*) from arrecad where arrecad.k00_numpre  = notidebitos.k53_numpre and arrecad.k00_numpar = notidebitos.k53_numpar) as arrecad
													from notidebitos
													where k53_notifica = $k53_notifica) as x
												where arrecad = 0 and arrepaga > 0";
		 $result_totalpago = db_query($sql_totalpago) or die($sql_totalpago);
		 if (pg_numrows($result_totalpago) > 0) {
			 $tipo = "TOTALMENTE PAGO";
			 $ltotalmente_pago;
		 }

	 }

	 if ( $tipo == "" ) {

		 $sql_parcialcancelado = "select * from (
																select	(select count(*) from arrecant where arrecant.k00_numpre  = notidebitos.k53_numpre and arrecant.k00_numpar= notidebitos.k53_numpar ) as arrecant,
																		(select count(*) from arrepaga where arrepaga.k00_numpre  = notidebitos.k53_numpre and arrepaga.k00_numpar = notidebitos.k53_numpar) as arrepaga,
																		(select count(*) from arrecad where arrecad.k00_numpre    = notidebitos.k53_numpre and arrecad.k00_numpar = notidebitos.k53_numpar)  as arrecad
																from notidebitos
																where k53_notifica = $k53_notifica) as x
																where arrecant > 0 and arrepaga = 0 and arrecad > 0
																";
		 $result_parcialcancelado = db_query($sql_parcialcancelado) or die($sql_parcialcancelado);
		 if (pg_numrows($result_parcialcancelado) > 0) {
			 $tipo = "PARCIALMENTE CANCELADO";
		 	 $lparcialmente_cancelado;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_totalcancelado = "select * from (
															select (select count(*) from arrecant where arrecant.k00_numpre  = notidebitos.k53_numpre and arrecant.k00_numpar = notidebitos.k53_numpar) as arrecant,
																		 (select count(*) from arrepaga where arrepaga.k00_numpre  = notidebitos.k53_numpre and arrepaga.k00_numpar = notidebitos.k53_numpar) as arrepaga,
																		 (select count(*) from arrecad where arrecad.k00_numpre  = notidebitos.k53_numpre and arrecad.k00_numpar = notidebitos.k53_numpar) as arrecad
															from notidebitos
															where k53_notifica = $k53_notifica) as x
															where arrecant > 0 and arrepaga = 0 and arrecad = 0";
		 $result_totalcancelado = db_query($sql_totalcancelado) or die($sql_totalcancelado);
		 if (pg_numrows($result_totalcancelado) > 0) {
			 $tipo = "TOTALMENTE CANCELADO";
		 $ltotalmente_cancelado;
		 }

	 }
	 
	 if ( $tipo == "") {

		 $sql_reparcelado = "	select * from (
														select	(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga,
															    (select count(*) from arrecad  where arrecad.k00_numpre = termoorigem.v07_numpre) as arrecad,
																(select count(*) from arrecant where arrecant.k00_numpre = termoorigem.v07_numpre) as arrecant
														from notidebitos
														inner join divida						 on divida.v01_numpre   = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
														inner join termodiv					 on termodiv.coddiv = divida.v01_coddiv
														inner join termo             on termo.v07_parcel = termodiv.parcel
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica) as x
													where arrepaga = 0 and arrecad > 0 and arrecant = 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			$lreparc_totalmente_debito; 
		 	$tipo = "REPARCELADO E TOTALMENTE EM DÉBITO";
		 }

	 }

	 if ( $tipo == "") {

		 $sql_reparcelado = "	select * from 
														(
														select	(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga,
															    (select count(*) from arrecad where arrecad.k00_numpre = termoorigem.v07_numpre) as arrecad
														from notidebitos
														inner join divida						 on divida.v01_numpre   = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
														inner join termodiv					 on termodiv.coddiv = divida.v01_coddiv
														inner join termo             on termo.v07_parcel = termodiv.parcel
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica
														) as x
													where arrepaga > 0 and arrecad > 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			$lreparc_parcialmente_pago;
		 	$tipo = "REPARCELADO E PARCIALMENTE PAGO";
		 }

	 }

	 if ( $tipo == "") {

		 $sql_reparcelado = "	select * from (
														select	(select count(*) from arrecad where arrecad.k00_numpre   = termoorigem.v07_numpre) as arrecad,
																(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga
														from notidebitos
														inner join divida						 on divida.v01_numpre   = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
														inner join termodiv					 on termodiv.coddiv = divida.v01_coddiv
														inner join termo             on termo.v07_parcel = termodiv.parcel
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica) as x
													where arrecad = 0 and arrepaga > 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			$lreparc_totalmente_pago; 
		 	$tipo = "REPARCELADO E TOTALMENTE PAGO";
		 }

	 }

   //
   // termo reparcelado->termo...termoreparc
	 //
	 
		if ( $tipo == "") {

		 $sql_reparcelado = "	select * from (
														select	(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga,
																		(select count(*) from arrecad where arrecad.k00_numpre = termoorigem.v07_numpre) as arrecad,
																		(select count(*) from arrecant where arrecant.k00_numpre = termoorigem.v07_numpre) as arrecant
														from notidebitos
														inner join termo             on termo.v07_numpre    = notidebitos.k53_numpre
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica) as x
													where arrepaga = 0 and arrecad > 0 and arrecant = 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			$lreparc_totalmente_debito; 
		 	$tipo = "REPARCELADO E TOTALMENTE EM DÉBITO";
		 }

	 }

	 if ( $tipo == "") {

		 $sql_reparcelado = "	select * from (
														select	(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga,
																		(select count(*) from arrecad where arrecad.k00_numpre = termoorigem.v07_numpre) as arrecad
														from notidebitos
														inner join termo             on termo.v07_numpre    = notidebitos.k53_numpre
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica) as x
													where arrepaga > 0 and arrecad > 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			 $tipo = "REPARCELADO E PARCIALMENTE PAGO";
			 $lreparc_parcialmente_pago;
		 }

	 }

	 if ( $tipo == "") {	

		 $sql_reparcelado = "	select * from (
														select	(select count(*) from arrecad where arrecad.k00_numpre = termoorigem.v07_numpre) as arrecad,
																		(select count(*) from arrepaga where arrepaga.k00_numpre = termoorigem.v07_numpre) as arrepaga
														from notidebitos
														inner join termo             on termo.v07_numpre    = notidebitos.k53_numpre
														inner join termoreparc       on termoreparc.v08_parcelorigem = termo.v07_parcel
														inner join termo termoorigem on termoorigem.v07_parcel = termoreparc.v08_parcel
														where k53_notifica = $k53_notifica) as x
													where arrecad = 0 and arrepaga > 0";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			 $tipo = "REPARCELADO E TOTALMENTE PAGO";
		 	 $lreparc_totalmente_pago;
		 }

	 }

   //
   // termo anulado
	 //
	 
	 if ( $tipo == "") {

		 $sql_reparcelado = "	select * from notidebitos
														inner join termo             on termo.v07_numpre    = notidebitos.k53_numpre
														where k53_notifica = $k53_notifica and termo.v07_situacao = 2";
		 $result_reparcelado = db_query($sql_reparcelado) or die($sql_reparcelado);
		 if (pg_numrows($result_reparcelado) > 0) {
			 $tipo = "PARCELAMENTO ANULADO";
		 	 $lparcelamento_anulado;
		 }

	 }


	 if ( $tipo == "" ) {

		 $sql_parcelado_sit = "	select * from (
															select	(select count(*) from arrepaga where arrepaga.k00_numpre = termo.v07_numpre) as arrepaga,
																			(select count(*) from arrecant where arrecant.k00_numpre = termo.v07_numpre) as arrecant,
																			(select count(*) from arrecad where arrecad.k00_numpre = termo.v07_numpre) as arrecad
															from notidebitos
															inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
															inner join termodiv on divida.v01_coddiv = termodiv.coddiv
															inner join termo    on termo.v07_parcel = termodiv.parcel
															where k53_notifica = $k53_notifica and termo.v07_situacao = 1) as x
														where arrepaga = 0 and arrecant = 0 and arrecad > 0";
		 $result_parcelado_sit = db_query($sql_parcelado_sit) or die($sql_parcelado_sit);
		 if (pg_numrows($result_parcelado_sit) > 0) {
			 $tipo = "PARCELADO E TOTALMENTE EM DÉBITO";
		 	 $lparc_totalmente_debito;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_parcelado_sit = "	select * from (
															select	(select count(*) from arrepaga where arrepaga.k00_numpre = termo.v07_numpre) as arrepaga,
																			(select count(*) from arrecad where arrecad.k00_numpre = termo.v07_numpre) as arrecad
															from notidebitos
															inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
															inner join termodiv on divida.v01_coddiv = termodiv.coddiv
															inner join termo    on termo.v07_parcel = termodiv.parcel
															where k53_notifica = $k53_notifica and termo.v07_situacao = 1) as x
														where arrepaga > 0 and arrecad > 0";
		 $result_parcelado_sit = db_query($sql_parcelado_sit) or die($sql_parcelado_sit);
		 if (pg_numrows($result_parcelado_sit) > 0) {
			 $tipo = "PARCELADO E PARCIALMENTE PAGO";
		 	$lparc_parcialmente_pago;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_parcelado_sit = "	select * from (
															select	(select count(*) from arrecad where arrecad.k00_numpre = termo.v07_numpre) as arrecad,
																			(select count(*) from arrepaga where arrepaga.k00_numpre = termo.v07_numpre) as arrepaga
															from notidebitos
															inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
															inner join termodiv on divida.v01_coddiv = termodiv.coddiv
															inner join termo    on termo.v07_parcel = termodiv.parcel
															where k53_notifica = $k53_notifica and termo.v07_situacao = 1) as x
														where arrecad = 0 and arrepaga > 0";
		 $result_parcelado_sit = db_query($sql_parcelado_sit) or die($sql_parcelado_sit);
		 if (pg_numrows($result_parcelado_sit) > 0) {
			 $tipo = "PARCELADO E TOTALMENTE PAGO";
		 	 $lparc_totalmente_pago;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_cdasemajuizado_sit = "	select * from (
															select case when v51_certidao is not null then 1 else 0 end as inicial
															from notidebitos
															inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
															inner join certdiv on divida.v01_coddiv = certdiv.v14_coddiv
															left  join inicialcert on v51_certidao = v14_certid
															where k53_notifica = $k53_notifica) as x
														where inicial = 0";
		 $result_cdasemajuizado_sit = db_query($sql_cdasemajuizado_sit) or die($sql_cdasemajuizado_sit);
		 if (pg_numrows($result_cdasemajuizado_sit) > 0) {
			 $tipo = "CDA EMITIDA SEM INICIAL";
		 	 $lcda_sem_inicial;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_cdasemajuizado_sit = "		select * from (
																			select	(select count(*) from arrecad where arrecad.k00_numpre = v59_numpre) as arrecad,
																							(select count(*) from arrecant where arrecant.k00_numpre = v59_numpre) as arrecant
																			from notidebitos
																			inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
																			inner join certdiv on divida.v01_coddiv = certdiv.v14_coddiv
																			inner join inicialcert on v51_certidao = v14_certid
																			inner join inicialnumpre on v51_inicial = v59_inicial
																			where k53_notifica = $k53_notifica) as x
																		where arrecad > 0 and arrecant = 0";
		 $result_cdasemajuizado_sit = db_query($sql_cdasemajuizado_sit) or die($sql_cdasemajuizado_sit);
		 if (pg_numrows($result_cdasemajuizado_sit) > 0) {
			 $tipo = "DÉBITO AJUIZADO E TOTALMENTE EM DÉBITO";
		 	 $laju_totalmente_debito;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_cdasemajuizado_sit = "		select * from (
																			select	(select count(*) from arrecad where arrecad.k00_numpre = v59_numpre) as arrecad,
																							(select count(*) from arrecant where arrecant.k00_numpre = v59_numpre) as arrecant,
																							(select count(*) from arrepaga where arrepaga.k00_numpre = v59_numpre) as arrepaga
																			from notidebitos
																			inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
																			inner join certdiv on divida.v01_coddiv = certdiv.v14_coddiv
																			inner join inicialcert on v51_certidao = v14_certid
																			inner join inicialnumpre on v51_inicial = v59_inicial
																			where k53_notifica = $k53_notifica) as x
																		where arrecad > 0 and arrecant > 0 and arrepaga > 0";
		 $result_cdasemajuizado_sit = db_query($sql_cdasemajuizado_sit) or die($sql_cdasemajuizado_sit);
		 if (pg_numrows($result_cdasemajuizado_sit) > 0) {
			 $tipo = "DÉBITO AJUIZADO E PARCIALMENTE PAGO";
		 	 $laju_parcialmente_pago;
		 }

	 }
	 
	 if ( $tipo == "") {

		 $sql_cdasemajuizado_sit = "		select * from (
																			select	(select count(*) from arrecad where arrecad.k00_numpre = v59_numpre) as arrecad,
																							(select count(*) from arrecant where arrecant.k00_numpre = v59_numpre) as arrecant,
																							(select count(*) from arrepaga where arrepaga.k00_numpre = v59_numpre) as arrepaga
																			from notidebitos
																			inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
																			inner join certdiv on divida.v01_coddiv = certdiv.v14_coddiv
																			inner join inicialcert on v51_certidao = v14_certid
																			inner join inicialnumpre on v51_inicial = v59_inicial
																			where k53_notifica = $k53_notifica) as x
																		where arrecad = 0 and arrecant > 0 and arrepaga > 0";
		 $result_cdasemajuizado_sit = db_query($sql_cdasemajuizado_sit) or die($sql_cdasemajuizado_sit);
		 if (pg_numrows($result_cdasemajuizado_sit) > 0) {
			 $tipo = "DÉBITO AJUIZADO E TOTALMENTE PAGO";
		 	 $laju_totalmente_pago;
		 }

	 }

	 if ( $tipo == "") {

		 $sql_cdasemajuizado_sit = "		select * from (
																			select count(*) as termoini
																			from notidebitos
																			inner join divida on divida.v01_numpre = notidebitos.k53_numpre and divida.v01_numpar = notidebitos.k53_numpar
																			inner join certdiv				on divida.v01_coddiv = certdiv.v14_coddiv
																			inner join inicialcert		on inicialcert.v51_certidao = certdiv.v14_certid
																			inner join inicialnumpre	on inicialcert.v51_inicial = inicialnumpre.v59_inicial
																			inner join termoini				on termoini.inicial = inicialcert.v51_inicial
																			where k53_notifica = $k53_notifica) as x
																		where termoini > 0";
		 $result_cdasemajuizado_sit = db_query($sql_cdasemajuizado_sit) or die($sql_cdasemajuizado_sit);
		 if (pg_numrows($result_cdasemajuizado_sit) > 0) {
			 $tipo = "DÉBITO AJUIZADO E PARCELADO";
		 	 $laju_parcelado_debito;
		 }

	 }

	if ($tipo != "") {

	  foreach ( $aTipo as $sChave ) { 

	  	if ( $sChave == $tipo ) {
	  	 
	  	  $pdf->cell(10,5,$k50_notifica,0,0,"L",0);
	  	  $pdf->cell(80,5,$z01_nome    ,0,0,"L",0);
	  	  $pdf->cell(20,5,$xmat	       ,0,0,"L",0);
	  	  $pdf->cell(60,5,$tipo		   ,0,0,"L",0);
	  	  $pdf->cell(20,5,$k60_codigo  ,0,1,"L",0);
	
		  if (!isset($totalportipo[$tipo][0])) {
			$totalportipo[$tipo][0] = 1;
		  } else {
			$totalportipo[$tipo][0] += 1;
		  }
	  	}
	  }
	}

	if ($x < $linhasNotiDebitos - 1) {
		$k60_codigo_next = pg_result($rsNotiDebitos, $x + 1, "k60_codigo");
	}else{
	  $k60_codigo_next ="";
	}

  if (($lista != $k60_codigo_next) or ($x == ($linhasNotiDebitos - 1)) ) {
		
		$pdf->ln(5);

		$pdf->cell(60,5,"SITUAÇÃO",1,0,"L",1);
		$pdf->cell(20,5,"QUANTIDADE"  ,1,1,"L",1);

		$total_quant=0;
		foreach ($totalportipo as $k => $v) {
			$pdf->cell(60,5,$k    ,0,0,"L",0);
			$pdf->cell(20,5,$v[0] ,0,1,"R",0);
			$total_quant+=$v[0];
		}
		$pdf->cell(60,5,"TOTAL"     ,1,0,"L",1);
		$pdf->cell(20,5,$total_quant,1,1,"R",1);

		if ($lista != $k60_codigo_next) {
			$impcab = true;
			$lista = $k60_codigo_next;
			$totalportipo = array();
		}

	}

}

$pdf->Output();

?>