<?php
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

  $oGet = db_utils::postmemory($_GET);

  switch($oGet->selordem){
    case "d":
      $orderby = "order by k31_data, k30_numcgm, k00_matric, k00_inscr, k30_numpre, k30_numpar, k30_receit";
      $headOrdem = "ORDENADO POR DATA";
    break;
    case "c":
      $orderby = "order by k30_numcgm, k00_matric, k00_inscr, k30_numpre, k30_numpar, k31_data, k30_receit";
      $headOrdem = "ORDENADO POR CGM";
    break;
    case "m":
      $orderby = "order by k00_matric, k30_numcgm, k00_inscr, k30_numpre, k30_numpar, k31_data, k30_receit";
      $headOrdem = "ORDENADO POR MATRÍCULA";
    break;
    case "i":
      $orderby = "order by k00_inscr, k30_numcgm, k00_matric, k30_numpre, k30_numpar, k31_data, k30_receit";
      $headOrdem = "ORDENADO POR INSCRIÇÃO";
    break;
  }

  $where = "";
  if($oGet->z01_numcgm){

    $sInnerArrenumcgm = " inner join arrenumcgm on arrenumcgm.k00_numpre = arreprescr.k30_numpre ";
    $where = " and arrenumcgm.k00_numcgm = " . $oGet->z01_numcgm;
  }

  if($oGet->j01_matric){
    $where .= "and k00_matric = ".$oGet->j01_matric;
  }
  if($oGet->q02_inscr){
    $where .= "and k00_inscr  = ".$oGet->q02_inscr;
  }

  if($oGet->anulada == "s"){
    $where .= "and k30_anulado  = 't'";
  } else if ($oGet->anulada == "n"){
    $where .= "and k30_anulado  = 'f'";
  }

	$aCabecalho       = array();
  $alt              = 5;
  $fonte            = 8;
  $iList            = 1;
  $codPrescr        = null;
  $aCabecalho       = array();
  $aTotalMatric     = array();
  $aTotalInscr      = array();
  $aTotalCgm        = array();
  $aTotalExerc      = array();
  $aTotalOrigem     = array();
  $iNroMatric       = 0;
  $iTotalParcial    = 0;
  $iNroInscr        = 0;
  $iNroCgm          = 0;
  $SubTotalVlrHist  = 0;
  $SubTotalVlrCorr  = 0;
  $SubTotalVlrMulta = 0;
  $SubTotalVlrJuros = 0;
  $SubTotal         = 0;
  $GeralVlrHist     = 0;
  $GeralVlrCorr     = 0;
  $GeralVlrMulta    = 0;
  $GeralVlrJuros    = 0;
  $GeralTotal       = 0;

  $head2 = "RELATÓRIO DE DÍVIDA PRESCRITA";
  $head3 = "DE ".db_formatar($oGet->datai,"d")." À ".db_formatar($oGet->dataf,"d");
  $head4 = $headOrdem;
  ($oGet->seltipo=="c"?$head5="TIPO: COMPLETO":$head5="TIPO: RESUMIDO");

  $sqlPrescr  = "  select  distinct                                                                            ";
  $sqlPrescr .= "          k30_numcgm,                                                                         ";
  $sqlPrescr .= "          z01_nome,                                                                           ";
  $sqlPrescr .= "          extract(year from k30_dtoper) as k30_dtoper,                                        ";
  $sqlPrescr .= "          v01_exerc,                                                                          ";
  $sqlPrescr .= "          k00_matric,                                                                         ";
  $sqlPrescr .= "          k00_inscr,                                                                          ";
  $sqlPrescr .= "          k31_data,                                                                           ";
  $sqlPrescr .= "          k30_valor,                                                                          ";
  $sqlPrescr .= "          k30_vlrcorr,                                                                        ";
  $sqlPrescr .= "          k30_multa,                                                                          ";
  $sqlPrescr .= "          k30_vlrjuros,                                                                       ";
  $sqlPrescr .= "          k30_dtvenc,                                                                         ";
  $sqlPrescr .= "          (k30_vlrcorr+k30_multa+k30_vlrjuros) as total,                                      ";
  $sqlPrescr .= "          k30_numpre,                                                                         ";
  $sqlPrescr .= "          k30_numpar,                                                                         ";
  $sqlPrescr .= "          k30_receit,                                                                         ";
  $sqlPrescr .= "          k02_descr,                                                                          ";
  $sqlPrescr .= "          k31_codigo,                                                                         ";
  $sqlPrescr .= "          k31_obs,                                                                            ";
  $sqlPrescr .= "          k02_descr,                                                                          ";
  $sqlPrescr .= "          v03_codigo,                                                                         ";
  $sqlPrescr .= "          v03_descr,                                                                          ";
  $sqlPrescr .= "          v03_tributaria,                                                                     ";
  $sqlPrescr .= "          v07_descricao,                                                                      ";
  $sqlPrescr .= "          k03_tipo,                                                                           ";
  $sqlPrescr .= "          k00_descr,                                                                          ";
  $sqlPrescr .= "          k30_anulado,                                                                        ";
  $sqlPrescr .= "          login,                                                                              ";
  $sqlPrescr .= "          ( select k120_data
                               from prescricaoanulareg
                                    inner join prescricaoanula on k120_sequencial = k121_prescricaoanula
                              where k121_arreprescr = arreprescr.k30_sequencial ) as k120_data                 ";
  $sqlPrescr .= "    from arreprescr                                                                           ";
  $sqlPrescr .= "         inner join prescricao         on k31_codigo            = k30_prescricao              ";
  $sqlPrescr .= "                                      and k31_instit            = ".db_getsession('DB_instit');

  if($oGet->z01_numcgm){
    $sqlPrescr .= $sInnerArrenumcgm;
  }

  $sqlPrescr .= "         inner join arretipo           on k00_tipo              = k30_tipo                    ";
  $sqlPrescr .= "         inner join divida             on v01_numpre            = arreprescr.k30_numpre       ";
  $sqlPrescr .= "                                      and v01_numpar            = arreprescr.k30_numpar       ";
  $sqlPrescr .= "         inner join proced             on v01_proced            = v03_codigo                  ";
  $sqlPrescr .= "         inner join tabrec             on k02_codigo            = k30_receit                  ";
  $sqlPrescr .= "         inner join cgm                on z01_numcgm            = k30_numcgm                  ";
  $sqlPrescr .= "         inner join db_usuarios        on id_usuario            = k31_usuario                 ";
  $sqlPrescr .= "         inner join tipoproced         on v07_sequencial        = v03_tributaria              ";
  $sqlPrescr .= "         left  join arreinscr          on arreinscr.k00_numpre  = k30_numpre                  ";
  $sqlPrescr .= "         left  join arrematric         on arrematric.k00_numpre = k30_numpre                  ";
  $sqlPrescr .= "   where k31_data between '".$oGet->datai."' and '".$oGet->dataf."'                           ";
  $sqlPrescr .= "     and arreprescr.k30_sequencial = ( select max(a.k30_sequencial) as seq
                                                          from arreprescr a
                                                         where a.k30_numpre = arreprescr.k30_numpre
                                                           and a.k30_numpar = arreprescr.k30_numpar
                                                           and a.k30_receit = arreprescr.k30_receit )          ";
  $sqlPrescr .= " $where   ";
  $sqlPrescr .= " $orderby ";

	$rsPrescr    = db_query($sqlPrescr) or die($sqlPrescr);
	$iRowsPrescr = pg_num_rows($rsPrescr);

	$aResumos = array();
	$aAgrupaResumo['proced']      = 'v03_codigo';
	$aAgrupaResumo['receita']     = 'k30_receit';
	$aAgrupaResumo['tipo_proced'] = 'v03_tributaria';
	$aAgrupaResumo['tipo_debito'] = 'k03_tipo';

	$pdf = new PDF();
	$pdf->Open();
	$pdf->AliasNbPages();
	$pdf->setfillcolor(235);
	$pdf->addpage("L");

	if($iRowsPrescr == 0){

	  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
	  exit;
  }

	for( $iInd=0; $iInd < $iRowsPrescr; $iInd++ ) {

		$oPrescr = db_utils::fieldsMemory($rsPrescr,$iInd);

    foreach ( $aAgrupaResumo as $sDescrAgrupa => $sCampo ) {

    	if ( $sDescrAgrupa == 'proced' ) {
    		$sDescricao = $oPrescr->v03_descr;
    	} else if ( $sDescrAgrupa == 'tipo_proced' ) {
    		$sDescricao = $oPrescr->v07_descricao;
   		} else if ( $sDescrAgrupa == 'receita' ) {
   			$sDescricao = $oPrescr->k02_descr;
    	} else {
    		$sDescricao = $oPrescr->k00_descr;
    	}

      if ( isset($aResumos[$sDescrAgrupa][$oPrescr->$sCampo]) ) {
	      $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nVlrHist'] += $oPrescr->k30_valor;
	      $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nVlrCorr'] += $oPrescr->k30_vlrcorr;
	      $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nMulta']   += $oPrescr->k30_multa;
	      $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nJuros']   += $oPrescr->k30_vlrjuros;
	      $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nTotal']   += $oPrescr->total;
      } else {
      	$aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['sDescricao'] = $sDescricao;
        $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nVlrHist']   = $oPrescr->k30_valor;
        $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nVlrCorr']   = $oPrescr->k30_vlrcorr;
        $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nMulta']     = $oPrescr->k30_multa;
        $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nJuros']     = $oPrescr->k30_vlrjuros;
        $aResumos[$sDescrAgrupa][$oPrescr->$sCampo]['nTotal']     = $oPrescr->total;
      }

    }

		if($oGet->seltipo == "c"){

			if( in_array( array($oPrescr->k30_numcgm,$oPrescr->k00_matric,$oPrescr->k00_inscr), $aCabecalho) ) {
				$lImprimeCab = false;
			  $lImprimeSubTotal = false;
			}else{
				$aCabecalho[0] = array( $oPrescr->k30_numcgm, $oPrescr->k00_matric, $oPrescr->k00_inscr );
				$lImprimeCab = true;
			  $lImprimeSubTotal = true;
			}

		  if($oGet->selhist == "s" &&  $codPrescr != $oPrescr->k31_codigo && $iInd !=0 ){
				$pdf->setfont('arial','i',7);
				$pdf->cell(280,$alt,"Histórico : ".$obsPrescr ,0,1,"L",($iList==0?$iList=1:$iList=0));
				$pdf->setfont('arial','',$fonte);
      }

			if( $lImprimeSubTotal == true && $iInd != 0 ){

				$pdf->ln(2);
				$pdf->setfont('arial','b',$fonte);
				$pdf->cell(20,$alt,"TOTAL : ",0,0,"L",0);
				$pdf->cell(25,$alt,db_formatar(	$SubTotalVlrHist	,"f"),0,0,"R",0);
				$pdf->cell(25,$alt,db_formatar(	$SubTotalVlrCorr	,"f"),0,0,"R",0);
				$pdf->cell(25,$alt,db_formatar(	$SubTotalVlrMulta ,"f"),0,0,"R",0);
				$pdf->cell(25,$alt,db_formatar(	$SubTotalVlrJuros ,"f"),0,0,"R",0);
				$pdf->cell(25,$alt,db_formatar(	$SubTotal				  ,"f"),0,1,"R",0);

				$pdf->cell(30,$alt,"TOTAL DE REGISTROS:  ",0,0,"L",0);
				$pdf->cell(25,$alt,$iTotalParcial,0,0,"C",0);
				$pdf->setfont('arial','',$fonte);
        $pdf->ln();

        $iTotalParcial = 0;

        if(trim($oPrescr->k00_inscr) != "" ) {
        	if(isset($aTotalOrigem["INSCRIÇÃO"])){
						 $aTotalOrigem["INSCRIÇÃO"]['valor' ] += $SubTotalVlrHist;
						 $aTotalOrigem["INSCRIÇÃO"]['vlrcor'] += $SubTotalVlrCorr;
						 $aTotalOrigem["INSCRIÇÃO"]['multa' ] += $SubTotalVlrMulta;
						 $aTotalOrigem["INSCRIÇÃO"]['juros' ] += $SubTotalVlrJuros;
						 $aTotalOrigem["INSCRIÇÃO"]['total' ] += $SubTotal;
					}else{
						 $aTotalOrigem["INSCRIÇÃO"]['valor' ]  = $SubTotalVlrHist;
						 $aTotalOrigem["INSCRIÇÃO"]['vlrcor']  = $SubTotalVlrCorr;
						 $aTotalOrigem["INSCRIÇÃO"]['multa' ]  = $SubTotalVlrMulta;
						 $aTotalOrigem["INSCRIÇÃO"]['juros' ]  = $SubTotalVlrJuros;
						 $aTotalOrigem["INSCRIÇÃO"]['total' ]  = $SubTotal;
					}
        }else if(trim($oPrescr->k00_matric) != "") {
        	if(isset($aTotalOrigem["MATRÍCULA"])){
						 $aTotalOrigem["MATRÍCULA"]['valor' ] += $SubTotalVlrHist;
						 $aTotalOrigem["MATRÍCULA"]['vlrcor'] += $SubTotalVlrCorr;
						 $aTotalOrigem["MATRÍCULA"]['multa' ] += $SubTotalVlrMulta;
						 $aTotalOrigem["MATRÍCULA"]['juros' ] += $SubTotalVlrJuros;
						 $aTotalOrigem["MATRÍCULA"]['total' ] += $SubTotal;
					}else{
						 $aTotalOrigem["MATRÍCULA"]['valor' ]  = $SubTotalVlrHist;
						 $aTotalOrigem["MATRÍCULA"]['vlrcor']  = $SubTotalVlrCorr;
						 $aTotalOrigem["MATRÍCULA"]['multa' ]  = $SubTotalVlrMulta;
						 $aTotalOrigem["MATRÍCULA"]['juros' ]  = $SubTotalVlrJuros;
						 $aTotalOrigem["MATRÍCULA"]['total' ]  = $SubTotal;
					}
        }else{
        	if(isset($aTotalOrigem["CGM"])){
						 $aTotalOrigem["CGM"]['valor' ] += $SubTotalVlrHist;
						 $aTotalOrigem["CGM"]['vlrcor'] += $SubTotalVlrCorr;
						 $aTotalOrigem["CGM"]['multa' ] += $SubTotalVlrMulta;
						 $aTotalOrigem["CGM"]['juros' ] += $SubTotalVlrJuros;
						 $aTotalOrigem["CGM"]['total' ] += $SubTotal;
					}else{
						 $aTotalOrigem["CGM"]['valor' ]  = $SubTotalVlrHist;
						 $aTotalOrigem["CGM"]['vlrcor']  = $SubTotalVlrCorr;
						 $aTotalOrigem["CGM"]['multa' ]  = $SubTotalVlrMulta;
						 $aTotalOrigem["CGM"]['juros' ]  = $SubTotalVlrJuros;
						 $aTotalOrigem["CGM"]['total' ]  = $SubTotal;
					}
        }

				$SubTotalVlrHist	 = 0;
        $SubTotalVlrCorr	 = 0;
        $SubTotalVlrMulta  = 0;
        $SubTotalVlrJuros  = 0;
        $SubTotal				   = 0;

				if ($iInd == $iRowsPrescr) {

					$pdf->ln();
					$pdf->setfont('arial','b',8);
					$pdf->cell(20,$alt,"GERAL : "												,0,0,"L",1);
					$pdf->cell(25,$alt,db_formatar(	$GeralVlrHist	 ,"f"),0,0,"R",1);
					$pdf->cell(25,$alt,db_formatar(	$GeralVlrCorr	 ,"f"),0,0,"R",1);
					$pdf->cell(25,$alt,db_formatar(	$GeralVlrMulta ,"f"),0,0,"R",1);
					$pdf->cell(25,$alt,db_formatar(	$GeralVlrJuros ,"f"),0,0,"R",1);
					$pdf->cell(25,$alt,db_formatar(	$GeralTotal		 ,"f"),0,1,"R",1);
					$pdf->cell(50,$alt,"TOTAL GERAL DE REGISTROS: "			,0,0,"L",0);
					$pdf->cell(10,$alt,$iRowsPrescr											,0,0,"C",0);
					$pdf->ln();

					$pdf->cell(60,$alt,"","T",0,"C",0);
				  $pdf->cell(140,$alt,"TOTAL POR EXERCÍCIO","T",0,"C",0);
				  $pdf->cell(0,$alt,""					 ,"T",1,"C",0);
					$pdf->cell(80,$alt,""				 	 ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Exercício" ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Vlr Hist"	 ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Vlr Corr"  ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Multa"     ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Juros"     ,"T",0,"C",1);
					$pdf->cell(20,$alt,"Total"     ,"T",0,"C",1);
					$pdf->cell(0,$alt,""           ,"T",1,"C",1);
					$pdf->ln(4);
					$pdf->setfont('arial','',8);

					$ValorExerc  = 0;
		    	$VlrCorExerc = 0;
		      $MultaExerc  = 0;
		      $JurosExerc  = 0;
		      $TotalExerc  = 0;

					foreach ($aTotalExerc as $key=> $aExercicio) {

						$pdf->cell(80,$alt,""				 	 ,0,0,"C",0);
						$pdf->cell(20,$alt,strtoupper($key),0,0,"C",0);
						$pdf->cell(20,$alt,db_formatar($aExercicio['valor' ],"f"),0,0,"R",0);
						$pdf->cell(20,$alt,db_formatar($aExercicio['vlrcor'],"f"),0,0,"R",0);
						$pdf->cell(20,$alt,db_formatar($aExercicio['multa' ],"f"),0,0,"R",0);
						$pdf->cell(20,$alt,db_formatar($aExercicio['juros' ],"f"),0,0,"R",0);
						$pdf->cell(20,$alt,db_formatar($aExercicio['total' ],"f"),0,1,"R",0);

						$ValorExerc  += $aExercicio['valor' ];
		    	  $VlrCorExerc += $aExercicio['vlrcor'];
		      	$MultaExerc  += $aExercicio['multa' ];
		      	$JurosExerc  += $aExercicio['juros' ];
		      	$TotalExerc  += $aExercicio['total' ];


						if ($pdf->gety() > $pdf->h - 30) {

							$pdf->setfont('arial','b',8);
							$pdf->cell(60,$alt,"","T",0,"C",0);
						  $pdf->cell(140,$alt,"TOTAL POR EXERCÍCIO","T",0,"C",0);
						  $pdf->cell(0,$alt,""					 ,"T",1,"C",0);
							$pdf->cell(80,$alt,""				 	 ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Exercício" ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Vlr Hist"	 ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Vlr Corr"  ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Multa"     ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Juros"     ,"T",0,"C",1);
							$pdf->cell(20,$alt,"Total"     ,"T",0,"C",1);
							$pdf->cell(0,$alt,""           ,"T",1,"C",1);
							$pdf->ln(2);
							$pdf->setfont('arial','',8);

						}
					}
					$pdf->setx(55);
					$pdf->cell(55,$alt,"",0,0,"R",0);
					$pdf->cell(20,$alt,db_formatar($ValorExerc ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($VlrCorExerc,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($MultaExerc ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($JurosExerc ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($TotalExerc ,"f"),"T",1,"R",0);

				$pdf->ln();
				$pdf->setfont('arial','b',8);
				$pdf->cell(60,$alt,"","T",0,"C",0);
			  $pdf->cell(140,$alt,"TOTAL POR ORIGEM","T",0,"C",0);
			  $pdf->cell(0,$alt,""					 ,"T",1,"C",0);
				$pdf->cell(50,$alt,""				 	 ,"T",0,"C",1);
				$pdf->cell(50,$alt,"Origem" ,"T",0,"C",1);
				$pdf->cell(20,$alt,"Vlr Hist"	 ,"T",0,"C",1);
				$pdf->cell(20,$alt,"Vlr Corr"  ,"T",0,"C",1);
				$pdf->cell(20,$alt,"Multa"     ,"T",0,"C",1);
				$pdf->cell(20,$alt,"Juros"     ,"T",0,"C",1);
				$pdf->cell(20,$alt,"Total"     ,"T",0,"C",1);
				$pdf->cell(0,$alt,""           ,"T",1,"C",1);
				$pdf->ln(2);
				$pdf->setfont('arial','',8);

				$ValorOrig  = 0;
		    $VlrCorOrig = 0;
		    $MultaOrig  = 0;
		    $JurosOrig  = 0;
		    $TotalOrig  = 0;

				foreach($aTotalOrigem as $key=>$aOrigem){
					$pdf->cell(50,$alt,""				 	 ,0,0,"C",0);
					$pdf->cell(50,$alt,strtoupper($key),0,0,"L",0);
					$pdf->cell(20,$alt,db_formatar($aOrigem['valor' ],"f"),0,0,"R",0);
					$pdf->cell(20,$alt,db_formatar($aOrigem['vlrcor'],"f"),0,0,"R",0);
					$pdf->cell(20,$alt,db_formatar($aOrigem['multa' ],"f"),0,0,"R",0);
					$pdf->cell(20,$alt,db_formatar($aOrigem['juros' ],"f"),0,0,"R",0);
					$pdf->cell(20,$alt,db_formatar($aOrigem['total' ],"f"),0,1,"R",0);

					$ValorOrig  += $aOrigem['valor' ];
		    	$VlrCorOrig += $aOrigem['vlrcor'];
		      $MultaOrig  += $aOrigem['multa' ];
		      $JurosOrig  += $aOrigem['juros' ];
		      $TotalOrig  += $aOrigem['total' ];

					if($pdf->gety() > $pdf->h - 30){
						$pdf->addpage("L");
						$pdf->setfont('arial','b',8);
						$pdf->cell(60,$alt,"","T",0,"C",0);
					  $pdf->cell(140,$alt,"TOTAL POR ORIGEM","T",0,"C",0);
					  $pdf->cell(0,$alt,""					 ,"T",1,"C",0);
						$pdf->cell(50,$alt,""				 	 ,"T",0,"C",1);
						$pdf->cell(50,$alt,"Origem" ,"T",0,"C",1);
						$pdf->cell(20,$alt,"Vlr Hist"	 ,"T",0,"C",1);
						$pdf->cell(20,$alt,"Vlr Corr"  ,"T",0,"C",1);
						$pdf->cell(20,$alt,"Multa"     ,"T",0,"C",1);
						$pdf->cell(20,$alt,"Juros"     ,"T",0,"C",1);
						$pdf->cell(20,$alt,"Total"     ,"T",0,"C",1);
						$pdf->cell(0,$alt,""           ,"T",1,"C",1);
						$pdf->ln(2);
						$pdf->setfont('arial','',8);
					}
				}

					$pdf->setx(55);
					$pdf->cell(55,"",0,1,"R",0);
					$pdf->cell(20,$alt,db_formatar($ValorOrig ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($VlrCorOrig,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($MultaOrig ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($JurosOrig ,"f"),"T",0,"R",0);
					$pdf->cell(20,$alt,db_formatar($TotalOrig ,"f"),"T",1,"R",0);

					$pdf->setfont('arial','',$fonte);
          $pdf->ln();
					continue;
				}
			}

			if($lImprimeCab == true){

				$pdf->ln();
				$pdf->setfont('arial','b',$fonte);
				$pdf->cell(20,$alt,"CGM"                  ,1,0,"C",1);
				$pdf->cell(80,$alt,"Nome/ Razão Social"   ,1,0,"C",1);
				$pdf->cell(20,$alt,"Matrícula"            ,1,0,"C",1);
				$pdf->cell(30,$alt,"Inscrição"            ,1,0,"C",1);
				$pdf->cell(20,$alt,"Dt Prescr"   					,1,1,"C",1);

				$pdf->setfont('arial','',$fonte);
				$pdf->cell(20,$alt,$oPrescr->k30_numcgm ,0,0,"C",0);
				$pdf->cell(80,$alt,$oPrescr->z01_nome   ,0,0,"L",0);
				$pdf->cell(20,$alt,$oPrescr->k00_matric ,0,0,"C",0);
				$pdf->cell(30,$alt,$oPrescr->k00_inscr  ,0,0,"C",0);
				$pdf->cell(20,$alt,db_formatar($oPrescr->k31_data,"d")		 ,0,1,"C",0);
			}

		  if($pdf->gety() > $pdf->h - 30 || $lImprimeCab == true ){
				if($pdf->gety() > $pdf->h - 30){
					$pdf->addpage("L");
				}
				$pdf->setfont('arial','b',$fonte);
				$pdf->cell(20,$alt,"Dt Venc"	   ,1,0,"C",1);
				$pdf->cell(20,$alt,"Vlr Hist"	   ,1,0,"C",1);
				$pdf->cell(20,$alt,"Vlr Corr"    ,1,0,"C",1);
				$pdf->cell(20,$alt,"Multa"       ,1,0,"C",1);
				$pdf->cell(20,$alt,"Juros"       ,1,0,"C",1);
				$pdf->cell(25,$alt,"Total"       ,1,0,"C",1);
				$pdf->cell(20,$alt,"Numpre"      ,1,0,"C",1);
				$pdf->cell(15,$alt,"Numpar"      ,1,0,"C",1);
				$pdf->cell(15,$alt,"Exercicio"   ,1,0,"C",1);
				$pdf->cell(45,$alt,"Procedência" ,1,0,"C",1);
				$pdf->cell(15,$alt,"Receita"     ,1,0,"C",1);
        $pdf->cell(25,$alt,"Login"       ,1,0,"C",1);
        $pdf->cell(20,$alt,"Anulada"     ,1,1,"C",1);
        $iList = 1;
      }

		 ($iList == 0?$iList=1:$iList=0);

			$pdf->setfont('arial','',$fonte);
			$pdf->cell(20,$alt,db_formatar($oPrescr->k30_dtvenc,"d")	 ,0,0,"C",$iList);
			$pdf->cell(20,$alt,db_formatar($oPrescr->k30_valor,"f")	 	 ,0,0,"R",$iList);
			$pdf->cell(20,$alt,db_formatar($oPrescr->k30_vlrcorr,"f")  ,0,0,"R",$iList);
			$pdf->cell(20,$alt,db_formatar($oPrescr->k30_multa,"f")	 	 ,0,0,"R",$iList);
			$pdf->cell(20,$alt,db_formatar($oPrescr->k30_vlrjuros,"f") ,0,0,"R",$iList);
			$pdf->cell(25,$alt,db_formatar($oPrescr->total,"f")				 ,0,0,"R",$iList);
			$pdf->cell(20,$alt,$oPrescr->k30_numpre                    ,0,0,"C",$iList);
			$pdf->cell(15,$alt,$oPrescr->k30_numpar                    ,0,0,"C",$iList);
			$pdf->cell(15,$alt,$oPrescr->v01_exerc                     ,0,0,"C",$iList);
			$pdf->cell(45,$alt,$oPrescr->v03_codigo ." - ". substr($oPrescr->v03_descr,0,30),0,0,"L",$iList);
			$pdf->cell(15,$alt,$oPrescr->k30_receit                    ,0,0,"C",$iList);
      $pdf->cell(25,$alt,$oPrescr->login                         ,0,0,"C",$iList);
      $pdf->cell(20,$alt,($oPrescr->k30_anulado == "f" ? "Não" : db_formatar($oPrescr->k120_data,'d')) ,0,1,"C",$iList);
			$iTotalParcial++;
	 }

		if($iInd == $iRowsPrescr){
			continue;
		}

		$SubTotalVlrHist		+= $oPrescr->k30_valor;
    $SubTotalVlrCorr		+= $oPrescr->k30_vlrcorr;
    $SubTotalVlrMulta	  += $oPrescr->k30_multa;
    $SubTotalVlrJuros	  += $oPrescr->k30_vlrjuros;
    $SubTotal				    += $oPrescr->total;

    $GeralVlrHist		+= $oPrescr->k30_valor;
    $GeralVlrCorr		+= $oPrescr->k30_vlrcorr;
    $GeralVlrMulta	+= $oPrescr->k30_multa;
    $GeralVlrJuros	+= $oPrescr->k30_vlrjuros;
    $GeralTotal  	  += $oPrescr->total;

		if(isset($aTotalExerc[$oPrescr->k30_dtoper])){

			 $aTotalExerc[$oPrescr->k30_dtoper]['valor' ] += $oPrescr->k30_valor;
			 $aTotalExerc[$oPrescr->k30_dtoper]['vlrcor'] += $oPrescr->k30_vlrcorr;
			 $aTotalExerc[$oPrescr->k30_dtoper]['multa' ] += $oPrescr->k30_multa;
			 $aTotalExerc[$oPrescr->k30_dtoper]['juros' ] += $oPrescr->k30_vlrjuros;
			 $aTotalExerc[$oPrescr->k30_dtoper]['total' ] += $oPrescr->total;
		}else{
			 $aTotalExerc[$oPrescr->k30_dtoper]['valor' ]  = $oPrescr->k30_valor;
			 $aTotalExerc[$oPrescr->k30_dtoper]['vlrcor']  = $oPrescr->k30_vlrcorr;
			 $aTotalExerc[$oPrescr->k30_dtoper]['multa' ]  = $oPrescr->k30_multa;
			 $aTotalExerc[$oPrescr->k30_dtoper]['juros' ]  = $oPrescr->k30_vlrjuros;
			 $aTotalExerc[$oPrescr->k30_dtoper]['total' ]  = $oPrescr->total;
		}

    if( !in_array($oPrescr->k00_matric, $aTotalMatric) && $oPrescr->k00_matric != ""){
			 $aTotalMatric[$iNroMatric] = $oPrescr->k00_matric;
			 $iNroMatric++;
		}
    if( !in_array($oPrescr->k00_inscr, $aTotalInscr) && $oPrescr->k00_inscr != ""){
			 $aTotalInscr[$iNroInscr] = $oPrescr->k00_inscr;
			 $iNroInscr++;
		}
    if( !in_array($oPrescr->k30_numcgm, $aTotalCgm) && $oPrescr->k00_matric == "" && $oPrescr->k00_inscr == ""){
			 $aTotalCgm[$iNroCgm] = $oPrescr->k30_numcgm;
			 $iNroCgm++;
		}

		if($codPrescr != $oPrescr->k31_codigo){
			 $obsPrescr = $oPrescr->k31_obs;
			 $codPrescr = $oPrescr->k31_codigo;
		}
	}

	if ( $iRowsPrescr > 0 &&  $oGet->seltipo == "c" ) {
  	$pdf->cell(280,$alt,"Histórico : ".$obsPrescr ,0,1,"L",($iList==0?$iList=1:$iList=0));
	}

  $iNroReg = $iRowsPrescr;

  if($oGet->seltipo == "c"){
		$pdf->addpage("L");
	}

	$pdf->sety(35);
	$pdf->setfont('arial','b',$fonte);
	$pdf->cell(30,$alt,"TOTAL DE REGISTROS  : ",0,1,"L",0);
	$pdf->cell(30,$alt,"TOTAL DE MATRÍCULAS : ",0,1,"L",0);
	$pdf->cell(30,$alt,"TOTAL DE INSCRIÇÕES : ",0,1,"L",0);
	$pdf->cell(30,$alt,"TOTAL SOMENTE CGM   : ",0,1,"L",0);
  $pdf->ln();

  $pdf->sety(35);
	$pdf->setfont('arial','',$fonte);
  $pdf->cell(45,$alt,$iNroReg		 ,0,1,"R",0);
	$pdf->cell(45,$alt,$iNroMatric ,0,1,"R",0);
  $pdf->cell(45,$alt,$iNroInscr	 ,0,1,"R",0);
  $pdf->cell(45,$alt,$iNroCgm    ,0,1,"R",0);
  $pdf->ln(2);

	$pdf->setfont('arial','b',$fonte);
  $pdf->cell(60,$alt,"","T",0,"C",0);
  $pdf->cell(140,$alt,"TOTAL DE DÍVIDAS PRESCRITAS","T",0,"C",0);
  $pdf->cell(0,$alt,"","T",1,"C",0);
	$pdf->cell(100,$alt,""				 ,"T",0,"C",1);
	$pdf->cell(20,$alt,"Vlr Hist"	 ,"T",0,"C",1);
	$pdf->cell(20,$alt,"Vlr Corr"  ,"T",0,"C",1);
	$pdf->cell(20,$alt,"Multa"     ,"T",0,"C",1);
	$pdf->cell(20,$alt,"Juros"     ,"T",0,"C",1);
	$pdf->cell(20,$alt,"Total"     ,"T",0,"C",1);
	$pdf->cell(0,$alt,""           ,"T",1,"C",1);
	$pdf->ln(2);
  $pdf->setx(70);
  $pdf->cell(40,$alt,"TOTAL GERAL : ",0,0,"L",0);
  $pdf->cell(20,$alt,db_formatar(	$GeralVlrHist	 ,"f"),0,0,"R",0);
	$pdf->cell(20,$alt,db_formatar(	$GeralVlrCorr	 ,"f"),0,0,"R",0);
	$pdf->cell(20,$alt,db_formatar(	$GeralVlrMulta ,"f"),0,0,"R",0);
	$pdf->cell(20,$alt,db_formatar(	$GeralVlrJuros ,"f"),0,0,"R",0);
	$pdf->cell(20,$alt,db_formatar(	$GeralTotal		 ,"f"),0,1,"R",0);
	$pdf->ln();

	$pdf->AddPage("L");

	$pdf->Ln(6);


foreach ( $aAgrupaResumo as $sTipoAgrupa => $sCampo ) {

  $nTotalHistResumo  = 0;
  $nTotalCorrResumo  = 0;
  $nTotalMultaResumo = 0;
  $nTotalJurosResumo = 0;
  $nTotalResumo      = 0;

  if ( $sTipoAgrupa == "proced" ) {
    $sTituloAgrupa = "Procedência";
  } else if ( $sTipoAgrupa == "receita" ) {
    $sTituloAgrupa = "Receita";
  } else if ( $sTipoAgrupa == "tipo_proced" ) {
    $sTituloAgrupa = "Tipo de Procedência";
  } else {
    $sTituloAgrupa = "Tipo de Débito";
  }

  $pdf->SetFont('Arial','B',$fonte);
  $pdf->Cell(165,$alt,"Resumo por {$sTituloAgrupa}",1,1,'L',1);
  $pdf->Cell(15,$alt,'Código'                      ,1,0,'C',1);
  $pdf->Cell(50,$alt,'Descrição'                   ,1,0,'C',1);
  $pdf->Cell(20,$alt,'Vlr Histórico'               ,1,0,'C',1);
  $pdf->Cell(20,$alt,'Vlr Corrigido'               ,1,0,'C',1);
  $pdf->Cell(20,$alt,'Vlr Multa'                   ,1,0,'C',1);
  $pdf->Cell(20,$alt,'Vlr Juros'                   ,1,0,'C',1);
  $pdf->Cell(20,$alt,'Total'                       ,1,1,'C',1);

  foreach ( $aResumos[$sTipoAgrupa] as $iCodResumo => $aValoresResumo ) {

    $pdf->SetFont('Arial','',$fonte);
    $pdf->Cell(15,$alt,$iCodResumo                                 ,1,0,'C',0);
    $pdf->Cell(50,$alt,$aValoresResumo['sDescricao']               ,1,0,'L',0);
    $pdf->Cell(20,$alt,db_formatar($aValoresResumo['nVlrHist'],'f'),1,0,'R',0);
    $pdf->Cell(20,$alt,db_formatar($aValoresResumo['nVlrCorr'],'f'),1,0,'R',0);
    $pdf->Cell(20,$alt,db_formatar($aValoresResumo['nMulta']  ,'f'),1,0,'R',0);
    $pdf->Cell(20,$alt,db_formatar($aValoresResumo['nJuros']  ,'f'),1,0,'R',0);
    $pdf->Cell(20,$alt,db_formatar($aValoresResumo['nTotal']  ,'f'),1,1,'R',0);

    $nTotalHistResumo  += $aValoresResumo['nVlrHist'];
    $nTotalCorrResumo  += $aValoresResumo['nVlrCorr'];
    $nTotalMultaResumo += $aValoresResumo['nMulta'];
    $nTotalJurosResumo += $aValoresResumo['nJuros'];
    $nTotalResumo      += $aValoresResumo['nTotal'];

  }

  $pdf->SetFont('Arial','B',$fonte);
  $pdf->Cell(15,$alt,'Total:'                           ,1,0,'R',0);
  $pdf->Cell(50,$alt,''                                 ,1,0,'L',0);
  $pdf->Cell(20,$alt,db_formatar($nTotalHistResumo ,'f'),1,0,'R',0);
  $pdf->Cell(20,$alt,db_formatar($nTotalCorrResumo ,'f'),1,0,'R',0);
  $pdf->Cell(20,$alt,db_formatar($nTotalMultaResumo,'f'),1,0,'R',0);
  $pdf->Cell(20,$alt,db_formatar($nTotalJurosResumo,'f'),1,0,'R',0);
  $pdf->Cell(20,$alt,db_formatar($nTotalResumo     ,'f'),1,1,'R',0);

  $pdf->Ln(3);
}

$pdf->Output();