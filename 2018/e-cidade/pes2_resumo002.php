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
include(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$instit = db_getsession("DB_instit");

$sValoresPatronais = " select distinct r33_codtab,
                                       r33_nome,
                                       r33_ppatro
                         from inssirf
                        where r33_anousu = $ano
                          and r33_mesusu = $mes
                          and r33_codtab > 2
                          and r33_instit = $instit";

$rsValoresPatronais = db_query($sValoresPatronais);
$iNunRows           = pg_num_rows($rsValoresPatronais);

if($iNunRows > 0){
  $aValoresPatronais = db_utils::getCollectionByRecord($rsValoresPatronais);
}
$oValoresPatronais = new stdClass();
$oValoresPatronais->data[0] = new \stdClass();
$oValoresPatronais->data[0]->nome = "BASE PREV.1";
$oValoresPatronais->data[0]->valor= 0;
$oValoresPatronais->data[1] = new \stdClass();
$oValoresPatronais->data[1]->nome = "BASE PREV.2";
$oValoresPatronais->data[1]->valor= 0;
$oValoresPatronais->data[2] = new \stdClass();
$oValoresPatronais->data[2]->nome = "BASE PREV.3";
$oValoresPatronais->data[2]->valor= 0;
$oValoresPatronais->data[3] = new \stdClass();
$oValoresPatronais->data[3]->nome = "BASE PREV.4";
$oValoresPatronais->data[3]->valor= 0;

foreach ($aValoresPatronais as $oRow){
	if($oRow->r33_codtab == 3){
		$oValoresPatronais->data[0]->nome   = substr($oRow->r33_nome,0,15);
		$oValoresPatronais->data[0]->valor  = $oRow->r33_ppatro;
	}else if($oRow->r33_codtab == 4){
    $oValoresPatronais->data[1]->nome   = substr($oRow->r33_nome,0,15);
    $oValoresPatronais->data[1]->valor  = $oRow->r33_ppatro;
  }else if($oRow->r33_codtab == 5){
    $oValoresPatronais->data[2]->nome   = substr($oRow->r33_nome,0,15);
    $oValoresPatronais->data[2]->valor  = $oRow->r33_ppatro;
  }if($oRow->r33_codtab == 6){
    $oValoresPatronais->data[3]->nome   = substr($oRow->r33_nome,0,15);
    $oValoresPatronais->data[3]->valor  = $oRow->r33_ppatro;
  }
}

$lotaini = 0;
$lotafin = 999999;

if ($folha == 'r14'){
     $xarquivo = 'DE SALÁRIO';
     $arquivo = 'gerfsal';
}elseif ($folha == 'r20'){
     $xarquivo = 'DE RESCISÄO';
     $arquivo = 'gerfres';
}elseif ($folha == 'r35'){
     $xarquivo = 'DE 13o SALÁRIO';
     $arquivo = 'gerfs13';
}elseif ($folha == 'r22'){
     $xarquivo = 'DE ADIANTAMENTO';
     $arquivo = 'gerfadi';
}elseif ($folha == 'r48'){
     $xarquivo = 'COMPLEMENTAR';
     $arquivo = 'gerfcom';
}elseif ($folha == 'r93'){
     $xarquivo = 'PROVISÃO DE FÉRIAS';
     $arquivo = 'gerfprovfer';
}elseif ($folha == 'r94'){
     $xarquivo = 'PROVISÃO 13o. SALÁRIO';
     $arquivo = 'gerfprovs13';
}

db_sel_instit();

$wherepes = '';
if(isset($semest) && $semest != 0){
  $wherepes = " and r48_semest = ".$semest;
  $head6 = $xarquivo ." ($semest)";
}
if($vinc == 'a'){
  $dvinc = ' ATIVOS';
  $xvinc = " and rh30_vinculo = 'A' ";
}elseif($vinc == 'i'){
  $dvinc = ' INATIVOS';
  $xvinc = " and rh30_vinculo = 'I' ";
}elseif($vinc == 'p'){
  $dvinc = ' PENSIONISTAS';
  $xvinc = " and rh30_vinculo = 'P' " ;
}elseif($vinc == 'ip'){
  $dvinc = ' ATIVOS/PENSIONISTAS ';
  $xvinc = " and rh30_vinculo in ('P','I') ";
}else{
  $dvinc = ' GERAL';
  $xvinc = '';
}
$xxordem = "rh27_rubric";
if($com_quebra == 't'){
  if($ordem == 'a'){
    if($tipo == "l"){
      $xxordem = ' r70_descr, rh27_rubric ';
    }elseif($tipo == "r"){
      $xxordem = ' o15_descr, o15_codigo , r14_rubric ';
    }elseif($tipo == "o"){
      $xxordem = ' r70_descr, rh27_rubric ';
    }elseif($tipo == "t"){
      $xxordem = ' rh55_descr, rh55_codigo ';
    }
  }else{
    if($tipo == "l"){
      if($cgc == '88073291000199'){
        $xxordem = ' r70_estrut, rh27_descr';
      }else{
        $xxordem = ' r70_estrut, rh27_rubric';
      }
    }elseif($tipo == "s"){
      $xxordem = ' o15_codigo, r14_rubric';
    }elseif($tipo == "o"){
      $xxordem = ' r70_estrut, rh27_descr';
    }elseif($tipo == "t"){
      $xxordem = ' rh55_estrut,rh55_descr ';
    }
  }
}else{
  if($ordem == 'a'){
      $xxordem = ' rh27_descr ';
  }
}
if($regime != 0){
  $wherepes .= " and rh30_regime = ".$regime;
}
$erroajuda = "";
if($sel != 0){
  $result_sel = db_query("select r44_where , r44_descr from selecao where r44_selec = ".$sel." and r44_instit = ".$instit);
  if(pg_numrows($result_sel) > 0){
    db_fieldsmemory($result_sel, 0, 1);
    $wherepes .= " and ".$r44_where;
    $head5     = " SELEÇÃO : " . $r44_descr;
    $erroajuda = " ou seleção informada é inválida";
  }
}

if(isset($previdencia) && $previdencia != 0 ){
  if($previdencia != 5){
    $wherepes .= " and rh02_tbprev = ".$previdencia;
    $result_prev = db_query("select distinct r33_nome from inssirf where r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = $previdencia + 2 ");
    db_fieldsmemory($result_prev, 0 );
    $head8 = "PREVIDÊNCIA : ".strtoupper($r33_nome);
  }else{
    $wherepes .= " and rh02_tbprev = 0 " ;
    $head8 = "PREVIDÊNCIA : FUNCIONÁRIOS SEM PREVIDÊNCIA";
  }
}


$head1 = "RESUMO DA FOLHA DE PAGAMENTO ";
$head2 = "ARQUIVO : ".$xarquivo;
$head3 = "PERÍODO : ".$mes." / ".$ano;
$head4 = "VINCULO : ".$dvinc;




$inner_join = "";
$whereestrut = " ";
if ($tipo == "l"){
  "lti=&ltf=   flt=0101,0102";
  $whereestrut = " ";
  if(isset($flt) && $flt != "") {
	   $whereestrut .= " and r70_estrut in ('".str_replace(",","','",$flt)."') ";
     $head7 = "LOTAÇÃO : $flt";
  }elseif((isset($lti) && $lti != "" ) && (isset($ltf) && $ltf != "")){
	   $whereestrut .= " and r70_estrut between '$lti' and '$ltf' ";
     $head7 = "LOTAÇÃO : ".$lti." A ".$ltf;
	}else if(isset($lti) && $lti != ""){
	   $whereestrut .= " and r70_estrut >= '$lti' ";
     $head7 = "LOTAÇÃO : $lti A 9999";
	}else if(isset($ltf) && $ltf != ""){
	   $whereestrut .= " and r70_estrut <= '$ltf' ";
     $head7 = "LOTAÇÃO : 0  A $ltf";
	}else{
     $head7 = "LOTAÇÃO : 0  A 9999";
  }
  $inner_join =  " inner join rhlota on r70_codigo = rh02_lota
						                        and r70_instit = rh02_instit";
}elseif( $tipo == "s"){
  " rci=20&rcf=31   frc=1,31  ";
  if($com_quebra == 't'){
    $whereestrut = " where 1 = 1 ";
  }
  if(isset($frc) && $frc != "") {
	   $whereestrut .= " and o15_codigo in ($frc) ";
     $head7 = "RECURSO : $frc";
  }elseif((isset($rci) && $rci != "" ) && (isset($rcf) && $rcf != "")){
	   $whereestrut .= " and o15_codigo between $rci and $rcf ";
     $head7 = "RECURSO : $rci A $rcf";
	}else if(isset($rci) && $rci != ""){
	   $whereestrut .= " and o15_codigo >= $rci ";
     $head7 = "RECURSO : $rci A 99999";
	}else if(isset($rcf) && $rcf != ""){
	   $whereestrut .= " and o15_codigo >= $rcf ";
     $head7 = "RECURSO : 0 A $ltf";
	}else{
     $head7 = "RECURSO : 0  A 9999";
	}
  $inner_join =  " inner join rhlota       on rh02_lota   = r70_codigo
                                          and r70_instit  = rh02_instit
			             left join  rhlotavinc   on rh25_codigo = r70_codigo
									                        and rh25_anousu = $ano";
}elseif ($tipo == "o"){
  "ori=&orf=  for=2,4";
  if($com_quebra == 't'){
    $whereestrut = " where 1 = 1 ";
  }
  if(isset($for) && $for != "") {
	   $whereestrut .= " and o40_orgao in ($for) ";
     $head7 = "ORGÃOS : $for";
  }elseif((isset($ori) && $ori != "" ) && (isset($orf) && $orf != "")){
	   $whereestrut .= " and o40_orgao between $ori and $orf ";
     $head7 = "ORGÃOS : $ori A $orf";
	}else if(isset($ori) && $ori != ""){
	   $whereestrut .= " and o40_orgao >= $ori ";
     $head7 = "ORGÃOS : $ori A 9999";
	}else if(isset($orf) && $orf != ""){
	   $whereestrut .= " and o40_orgao <= $orf ";
     $head7 = "ORGÃOS : 0 A $orf";
	}else{
     $head7 = "ORGÃOS : 0  A 9999";
	}
  $inner_join =  " inner join rhlota     on r70_codigo  = rh02_lota
									                      and r70_instit  = rh02_instit
			             left join  rhlotaexe  on rh26_codigo = r70_codigo
									                      and rh26_anousu = $ano
		               left join  orcorgao   on o40_orgao   = rh26_orgao
					                              and o40_anousu  = $ano
			                                  and o40_instit  = rh02_instit ";
}elseif ($tipo == "t"){
  "lci=&lcf=   flc=13004,13006 ";
  $whereestrut = "";
  if(isset($flc) && $flc != "" ) {
	   $whereestrut .= " and rh55_estrut in ('".str_replace(",","','",$flc)."') ";
     $head7 = "LOCAL TRAB. : $flc";
  }elseif((isset($lci) && $lci != "" ) && (isset($lcf) && $lcf != "")){
	   $whereestrut .= " and rh55_estrut between '$lci' and '$lcf' ";
     $head7 = "LOCAL TRAB. : $lci A $lcf";
	 }else if(isset($lci) && $lci != ""){
	   $whereestrut .= " and rh55_estrut >= '$lci' ";
     $head7 = "LOCAL TRAB. : $lci A 0";
	 }else if(isset($lcf) && $lcf != ""){
	   $whereestrut .= " and rh55_estrut <= '$lcf' ";
     $head7 = "LOCAL TRAB. : 0 A $lcf";
	}else{
     $head7 = "LOCAL TRAB. : 0  A 9999";
	 }
  $inner_join = "  inner join  rhpeslocaltrab on rh56_seqpes = rh02_seqpes
			                                       and rh56_princ = 't'
                   inner join rhlocaltrab     on rh55_codigo = rh56_localtrab
		                                         and rh55_instit = ".$folha."_instit ";
}


if ($tipo == "g"){
   $head7 = "RESUMO GERAL";
}else{
  if($com_quebra == 'f'){
    $tipo = "g";
    $head7 = "RESUMO GERAL - ".$head7;
  }
}
if ($tipo == "l"){
  "lti=&ltf=   flt=0101,0102";
   $sql = "select r70_estrut,
                  r70_descr,
                  x.lota,
		  x.".$folha."_rubric as r14_rubric,
		  case when rh23_rubric is not null then 'e-'
                       else case when rh75_rubric is not null and e01_sequencial = 2 then 'r-'
	                         else case when rh75_rubric is not null and e01_sequencial = 3 then 'p-'
	                              else case when rh75_rubric is not null and e01_sequencial = 4 then 'd-'
                                           else ''
                                end
		                      end
		            end
		  end as emp,
		  rh27_descr,
		  x.".$folha."_pd as r14_pd,
		  x.valor,
		  x.soma,
		  x.quant
           from (select rh02_lota as lota,
					              ".$folha."_instit,
	                      ".$folha."_rubric,
			                  round(sum(".$folha."_valor),2) as valor,
			                  ".$folha."_pd,count(".$folha."_rubric) as soma,
			                  round(sum(".$folha."_quant),2) as quant
          	    from ".$arquivo."
				            inner join rhpessoal    on rh01_regist = ".$folha."_regist
                    inner join rhpessoalmov on rh02_regist = rh01_regist
			  		                               and rh02_anousu  = ".$folha."_anousu
					                                 and rh02_mesusu  = ".$folha."_mesusu
							                             and rh02_instit  = ".$folha."_instit
                    left join rhpesbanco    on rh44_seqpes  = rh02_seqpes
                    inner join rhregime     on rh02_codreg  = rh30_codreg
						                               and rh30_instit  = rh02_instit
                    $xvinc
                    inner join rhlota on r70_codigo = rh02_lota
						                         and r70_instit = rh02_instit
		    where ".$folha."_anousu = $ano
		      and ".$folha."_mesusu = $mes
					and ".$folha."_instit = ".db_getsession("DB_instit")."
		      $wherepes
          $whereestrut
		    group by ".$folha."_rubric,".$folha."_instit,lota,".$folha."_pd) as x
		 inner join rhrubricas on x.".$folha."_rubric = rh27_rubric
		                      and rh27_instit = ".db_getsession("DB_instit")."
		 left join rhlota  on r70_codigo = lota
						          and r70_instit = rh27_instit
     left join rhrubelemento        on rh23_rubric = rh27_rubric
		                   and rh23_instit = rh27_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join retencaotipocalc     on e32_sequencial = e21_retencaotipocalc
     left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo

	    order by $xxordem ";
}elseif ($tipo == "s"){
  " rci=20&rcf=31   frc=1,31  ";
   $sql = "select o15_codigo as r70_estrut,
                  o15_descr  as r70_descr,
                  x.lota,
		              x.".$folha."_rubric as r14_rubric,
		  case when rh23_rubric is not null then 'e-'
                       else case when rh75_rubric is not null and e01_sequencial = 2 then 'r-'
	                         else case when rh75_rubric is not null and e01_sequencial = 3 then 'p-'
	                              else case when rh75_rubric is not null and e01_sequencial = 4 then 'd-'
                                           else ''
                                end
		                      end
		            end
		  end as emp,
		              rh27_descr,
		              x.".$folha."_pd as r14_pd,
		              x.valor,
		              x.soma,
		              x.quant
           from (select rh25_recurso as lota,
	                      ".$folha."_rubric,
			                  round(sum(".$folha."_valor),2) as valor,
			                  ".$folha."_pd,count(".$folha."_rubric) as soma,
			                  round(sum(".$folha."_quant),2) as quant
		             from ".$arquivo."
		                    inner join rhpessoal    on rh01_regist = ".$folha."_regist
						            inner join rhpessoalmov on rh02_regist = rh01_regist
			  		                                   and rh02_anousu = ".$folha."_anousu
					                                     and rh02_mesusu = ".$folha."_mesusu
						                                   and rh02_instit = ".$folha."_instit
                        left join rhpesbanco    on rh44_seqpes  = rh02_seqpes
						            inner join rhregime     on rh02_codreg  = rh30_codreg
											                          and rh30_instit = rh02_instit
					              $xvinc
			                  inner join rhlota       on rh02_lota   = r70_codigo
                                               and r70_instit  = rh02_instit
			                  left join  rhlotavinc   on rh25_codigo = r70_codigo
												                       and rh25_anousu = $ano
		             where  ".$folha."_anousu = $ano
		                and ".$folha."_mesusu = $mes
					          and ".$folha."_instit = ".db_getsession("DB_instit")."
		                and r70_estrut between '$lotaini' and '$lotafin'
		             $wherepes
		             group by ".$folha."_rubric,lota,".$folha."_pd
								) as x
		            inner join rhrubricas          on x.".$folha."_rubric = rh27_rubric
		                                          and rh27_instit = ".db_getsession("DB_instit")."
		            left join orctiporec           on lota= o15_codigo
     left join rhrubelemento        on rh23_rubric = rh27_rubric
		                   and rh23_instit = rh27_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join retencaotipocalc     on e32_sequencial = e21_retencaotipocalc
     left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo
           $whereestrut
	         order by $xxordem ";
}elseif ($tipo == "o"){
  "ori=&orf=  for=2,4";
   $sql = "select o40_orgao as r70_estrut,
                  o40_descr  as r70_descr,
                  x.lota,
		              x.".$folha."_rubric as r14_rubric,
		  case when rh23_rubric is not null then 'e-'
                       else case when rh75_rubric is not null and e01_sequencial = 2 then 'r-'
	                         else case when rh75_rubric is not null and e01_sequencial = 3 then 'p-'
	                              else case when rh75_rubric is not null and e01_sequencial = 4 then 'd-'
                                           else ''
                                end
		                      end
		            end
		  end as emp,
		              rh27_descr,
		              x.".$folha."_pd as r14_pd,
		              x.valor,
		              x.soma,
		              x.quant
           from (select rh26_orgao as lota,
	                      ".$folha."_rubric,
									      ".$folha."_instit,
			                  round(sum(".$folha."_valor),2) as valor,
			                  ".$folha."_pd,count(".$folha."_rubric) as soma,
			                  round(sum(".$folha."_quant),2) as quant
		             from ".$arquivo."
		                  inner join rhpessoal   on rh01_regist = ".$folha."_regist
											inner join rhpessoalmov on rh02_regist = rh01_regist
			  		                                and rh02_anousu = ".$folha."_anousu
  					                                and rh02_mesusu = ".$folha."_mesusu
							                              and rh02_instit = ".$folha."_instit
                      left join rhpesbanco    on rh44_seqpes  = rh02_seqpes
						          inner join rhregime    on rh02_codreg = rh30_codreg
											                          and rh30_instit = rh02_instit
					            $xvinc
			                inner join rhlota      on r70_codigo  = rh02_lota
											                      and r70_instit  = rh02_instit
			                left join  rhlotaexe   on rh26_codigo = r70_codigo
											                      and rh26_anousu = $ano
		      where ".$folha."_anousu = $ano
		        and ".$folha."_mesusu = $mes
		    		and ".$folha."_instit = ".db_getsession("DB_instit")."
		        $wherepes
		      group by ".$folha."_rubric,lota,".$folha."_pd,".$folha."_instit) as x
		      inner join rhrubricas on x.".$folha."_rubric = rh27_rubric
			                         and rh27_instit = ".db_getsession("DB_instit")."
		      left join  orcorgao   on o40_orgao   = lota
					                     and o40_anousu  = $ano
			                         and o40_instit  = rh27_instit
     left join rhrubelemento        on rh23_rubric = rh27_rubric
		                   and rh23_instit = rh27_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join retencaotipocalc     on e32_sequencial = e21_retencaotipocalc
     left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo
      $whereestrut
	    order by $xxordem ";
}elseif ($tipo == "g"){
     $sql = "select x.".$folha."_rubric as r14_rubric,
		  case when rh23_rubric is not null then 'e-'
                       else case when rh75_rubric is not null and e01_sequencial = 2 then 'r-'
	                         else case when rh75_rubric is not null and e01_sequencial = 3 then 'p-'
	                              else case when rh75_rubric is not null and e01_sequencial = 4 then 'd-'
                                           else ''
                                end
		                      end
		            end
		  end as emp,
                    rh27_descr,
		                x.".$folha."_pd as r14_pd,
		                x.valor,
		                x.soma,
		                x.quant
							from (select ".$folha."_rubric,
							             ".$folha."_instit as instit ,
		                       round(sum(".$folha."_valor),2) as valor,
					                 ".$folha."_pd,count(".$folha."_rubric) as soma,
					                 round(sum(".$folha."_quant),2) as quant
				            from ".$arquivo."
		               		   inner join rhpessoal      on rh01_regist = ".$folha."_regist
					      				 inner join rhpessoalmov   on rh02_regist = rh01_regist
                    			  		   		            and rh02_anousu = ".$folha."_anousu
					         	                	            and rh02_mesusu = ".$folha."_mesusu
					      													        and rh02_instit = ".$folha."_instit
                         left join rhpesbanco    on rh44_seqpes  = rh02_seqpes
						             inner join rhregime       on rh02_codreg = rh30_codreg
											                          and rh30_instit = rh02_instit
					      		     $xvinc
                         $inner_join
				            where ".$folha."_anousu = $ano
				              and ".$folha."_mesusu = $mes
					      			and ".$folha."_instit = ".db_getsession("DB_instit")."
					            $wherepes
                      $whereestrut
				            group by ".$folha."_rubric,".$folha."_instit,".$folha."_pd
									 ) as x
				           inner join rhrubricas         on x.".$folha."_rubric=rh27_rubric
									                              and rh27_instit = instit
     left join rhrubelemento        on rh23_rubric = rh27_rubric
		                   and rh23_instit = rh27_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join retencaotipocalc     on e32_sequencial = e21_retencaotipocalc
     left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo
				      order by $xxordem ";
}elseif ($tipo == "t"){
  "lci=&lcf=   flc=13004,13006 ";
   $sql = "select rh55_estrut as r70_estrut,
                  rh55_descr  as r70_descr,
                  x.lota,
                  x.".$folha."_rubric as r14_rubric,
		  case when rh23_rubric is not null then 'e-'
                       else case when rh75_rubric is not null and e01_sequencial = 2 then 'r-'
	                         else case when rh75_rubric is not null and e01_sequencial = 3 then 'p-'
	                              else case when rh75_rubric is not null and e01_sequencial = 4 then 'd-'
                                           else ''
                                end
		                      end
		            end
		  end as emp,
                  rh27_descr,
                  x.".$folha."_pd as r14_pd,
                  x.valor,
                  x.soma,
                  x.quant
           from (select rh56_localtrab as lota,
	                     ".$folha."_rubric,
              		      round(sum(".$folha."_valor),2) as valor,
                        ".$folha."_pd,count(".$folha."_rubric) as soma,
                        round(sum(".$folha."_quant),2) as quant
                 from ".$arquivo."
                      inner join rhpessoal      on rh01_regist = ".$folha."_regist
                      inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                               and rh02_mesusu = ".$folha."_mesusu
                                               and rh02_regist = ".$folha."_regist
				                                       and rh02_instit = ".$folha."_instit
                      left join rhpesbanco    on rh44_seqpes  = rh02_seqpes
		                  inner join rhregime       on rh02_codreg = rh30_codreg
		                                           and rh30_instit = rh02_instit
                      $xvinc
                      inner join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
			                                         and rh56_princ = 't'
	                    inner join rhlocaltrab    on rh55_codigo = rh56_localtrab
		                                           and rh55_instit = ".$folha."_instit
                 where ".$folha."_anousu = $ano
                   and ".$folha."_mesusu = $mes
									 and ".$folha."_instit = ".db_getsession("DB_instit")."
                   $wherepes
            		   $whereestrut
                 group by ".$folha."_rubric,lota,".$folha."_pd
                )as x
		       inner join rhrubricas   on rh27_rubric = x.".$folha."_rubric
		                              and rh27_instit = ".db_getsession("DB_instit")."
		       inner join rhlocaltrab  on rh55_codigo = lota
		                              and rh55_instit = rh27_instit
     left join rhrubelemento        on rh23_rubric = rh27_rubric
		                   and rh23_instit = rh27_instit
     left join rhrubretencao        on rh75_rubric = rh27_rubric
                                   and rh75_instit = rh27_instit
     left join retencaotiporec      on e21_sequencial = rh75_retencaotiporec
     left join retencaotipocalc     on e32_sequencial = e21_retencaotipocalc
     left join retencaotiporecgrupo on e01_sequencial = e21_retencaotiporecgrupo
           order by $xxordem,".$folha."_rubric ";
}

//echo "<BR><BR> 3.1 $tipo";
//echo "<BR><BR> 3.2 xxordem --> $xxordem com_quebra --> $com_quebra sql --> $sql <br><br>";
//echo $sql ;exit;
$result = db_query($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos no período de '.$mes.' / '.$ano.$erroajuda.".");

}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->addpage();
$pdf->setfillcolor(235);
$baseprev = 0;
$baseirf  = 0;
$alt = 4;
$vencimentos = 0;
$descontos   = 0;
$empenho  = 0;
$retencao = 0;
$deducao  = 0;
$pextra   = 0;
$rub_dif  = 0;
$pdf->setfont('arial','b',8);
db_fieldsmemory($result,0);
//echo substr($r14_rubric,1,4) ;exit;

$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
$pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
$pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
$pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);

if ($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t"){
   $quebra = $lota;
   if($tipo == "s"){
     if(empty($quebra)){
       $quebra = 0;
     }
   }
   $pdf->cell(145,5,$r70_estrut." - ".$lota." - ".strtoupper($r70_descr),1,1,"L",1);
}
for($x = 0;$x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if (($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t") && $quebra != $lota){
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(60,$alt,'',"T",0,"C",0);
      $pdf->cell(20,$alt,'',"T",0,"C",0);
      $pdf->cell(20,$alt,'',"T",1,"C",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      if($tipo == "l"){
        $xand = $folha."_lotac " ;
	      $xinner = "";
      }elseif($tipo == "s"){
        $xand = " rh25_recurso ";
	      $xinner = " left join rhlotavinc on rh25_codigo = rh02_lota and rh25_anousu = ".$ano;
      }elseif($tipo == "o"){
        $xand = " rh26_orgao ";
	      $xinner = " left join rhlotaexe on rh26_codigo = rh02_lota and rh26_anousu = ".$ano;
      }
      if($tipo == "t"){
         $sqllota = "select count(distinct(".$folha."_regist))
                     from rhpeslocaltrab
                       inner join rhpessoalmov on rh02_seqpes = rhpeslocaltrab.rh56_seqpes
                       left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
		                   inner join rhregime     on rh30_codreg = rh02_codreg
		                              and rh30_instit = rh02_instit
                       inner join ".$arquivo." on ".$folha."_anousu = rh02_anousu
		                              and ".$folha."_mesusu = rh02_mesusu
		                              and ".$folha."_regist = rh02_regist
					                        and ".$folha."_instit = rh02_instit
                       $xvinc
                   where rh56_localtrab = $quebra
                   $wherepes ";

		    $xinner = " inner join rhpeslocaltrab on rh56_seqpes = rh02_seqpes		";

    		$xand = "rh56_localtrab";

		}else{
      $sqllota = "select count(distinct(".$folha."_regist))
                  from ".$arquivo."
        		   inner join rhpessoal    on rh01_regist = ".$folha."_regist
               inner join rhpessoalmov on rh02_anousu = ".$folha."_anousu
                                      and rh02_mesusu = ".$folha."_mesusu
		                                  and rh02_regist = rh01_regist
																		  and rh02_instit = ".$folha."_instit
               left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
			         $xinner
						   inner join rhregime     on rh02_codreg = rh30_codreg
											                and rh30_instit = rh02_instit
					     $xvinc
			         inner join rhlota       on rh02_lota   = r70_codigo
				                              and r70_instit  = ".$folha."_instit
		  where ".$folha."_anousu = $ano
		        and ".$folha."_mesusu = $mes
          	and ".$folha."_instit = ".db_getsession("DB_instit");
   if($tipo == "s"){
      $sqllota	.= " and $xand = $quebra ";
   }else{
      $sqllota	.= " and $xand = '$quebra' ";
   }
   $sqllota .= " and r70_estrut between '$lotaini' and '$lotafin' $wherepes ";
		}

// echo "<BR><BR> 1.0 $sqllota";exit;

      $resultlota = db_query($sqllota);
      db_fieldsmemory($resultlota,0);
      $pdf->cell(20,$alt,$count,0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'P.EXTRA   ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($pextra,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'RETENCAO  ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($retencao,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'DEDUCAO   ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($deducao,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'DIFERENCA ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($rub_dif,'f'),0,1,"R",0);
      $sqlprev = "select round(sum(prev1),2) as prev1,
                         round(sum(prev2),2) as prev2,
			                   round(sum(prev3),2) as prev3,
			                   round(sum(prev4),2) as prev4,
			                   round(sum(basefgts),2) as basefgts ,
			                   round(sum(fgts),2) as fgts
	                from (select ".$folha."_lotac,
     	               		       case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,
				                       case when rh02_tbprev = 2 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev2,
     		       		             case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		       		             case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4,
     		       		             case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as basefgts ,
     		       		             case when ".$folha."_rubric = 'R991' then round(".$folha."_valor*0.08,2) end as fgts
			            from ".$arquivo."
     		             inner join rhpessoal    on ".$folha."_regist = rh01_regist
                     inner join rhpessoalmov on rh02_anousu = ".$folha."_anousu
           		                              and rh02_mesusu = ".$folha."_mesusu
		                                        and rh02_regist = rh01_regist
												  		              and rh02_instit = ".$folha."_instit
               left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
						   inner join rhregime     on rh02_codreg = rh30_codreg
											                and rh30_instit = rh02_instit
						 $xvinc
			       $xinner
			where ".$folha."_anousu = $ano
			  and ".$folha."_mesusu = $mes
		   	and ".$folha."_instit = ".db_getsession("DB_instit");
   if($tipo == "s"){
      $sqlprev	.= " and $xand = $quebra ";
   }else{
      $sqlprev	.= " and $xand = '$quebra' ";
   }
   $sqlprev .= " and ".$folha."_rubric in ('R992','R991') $wherepes ) as x ";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992')
//echo "<BR><BR> 2.0 $sqlprev";exit;
      $resultprev = db_query($sqlprev);
//      db_criatabela($resultprev);
      db_fieldsmemory($resultprev,0);
      $pdf->ln(3);
      $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.3   :'.db_formatar($prev3,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.4   :'.db_formatar($prev4,'f'),0,1,"L",0);
      $pdf->cell(45,$alt,'BASE F.G.T.S. :'.db_formatar($basefgts,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'F.G.T.S. EMPR :'.db_formatar($fgts,'f'),0,1,"L",0);


      $vencimentos = 0;
      $descontos = 0;
      $empenho = 0;
      $pextra = 0;
      $retencao = 0;
      $baseprev = 0;
      $baseirf  = 0;
      $quebra = $lota;
      if($tipo == "s"){
        if(empty($quebra)){
          $quebra = 0;
        }
      }
      $pdf->sety(290);
   }
   if ($pdf->gety() > $pdf->h -30){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);
      if($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t" ){
        $pdf->cell(145,5,$r70_estrut." - ".$lota." - ".strtoupper($r70_descr),1,1,"L",1);
      }
   }
   $pdf->setfont('arial','',8);
   if($r14_pd != 3 ){
     $pdf->cell(15,$alt,$emp.$r14_rubric,0,0,"R",0);
     $pdf->cell(15,$alt,$soma,0,0,"R",0);
     $pdf->cell(15,$alt,$quant,0,0,"R",0);
     $pdf->cell(60,$alt,$rh27_descr,0,0,"L",0);
     if ($r14_pd == 1){
        $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,'',0,1,"R",0);
        $vencimentos += $valor;
     }else if ($r14_pd == 2) {
        $pdf->cell(20,$alt,'',0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
        $descontos += $valor;
     }
   }elseif($r14_rubric == 'R981'){
     $baseirf += $valor;
   }elseif($r14_rubric == 'R992'){
     $baseprev += $valor;
   }
   if($emp == 'e-' && $r14_rubric < 'R950' ){
     if ($r14_pd == 1) {
        $empenho += $valor;
     }else  if ($r14_pd == 2) {
        $empenho -= $valor;
     }
   }
   if($emp == 'r-' && $r14_rubric < 'R950' ){
     if ($r14_pd == 1){
        $retencao -= $valor;
     }else  if ($r14_pd == 2) {
        $retencao += $valor;
     }
   }
   if($emp == 'd-' && $r14_rubric < 'R950' ){
     if ($r14_pd == 1){
        $deducao += $valor;
     }else  if ($r14_pd == 2) {
        $deducao -= $valor;
     }
   }
   if($emp == 'p-' && $r14_rubric < 'R950' ){
     if ($r14_pd == 1) {
        $pextra += $valor;
     }else  if ($r14_pd == 2) {
        $pextra -= $valor;
     }
   }
   if($emp == '' && $r14_rubric < 'R950' ){
     if ($r14_pd == 1) {
        $rub_dif += $valor;
     }else  if ($r14_pd == 2) {
        $rub_dif -= $valor;
     }
   }
}
if ($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t"){
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(60,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",1,"C",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $dbwherelta = " r70_estrut >= '$lotaini' and r70_estrut <= '$lotafin' ";
   $xinner = " left join rhpessoalmov on rh02_anousu = ".$folha."_anousu
	                  			         and rh02_mesusu = ".$folha."_mesusu
			                             and rh02_regist = ".$folha."_regist
             											 and rh02_instit = ".db_getsession("DB_instit")."
             left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
					   inner join rhregime    on rh02_codreg = rh30_codreg
											             and rh30_instit = rh02_instit
		         inner join rhlota      on rh02_lota   = r70_codigo
					                         and r70_instit  = ".db_getsession("DB_instit");
      if($tipo == "l"){
        $xand = $folha."_lotac " ;
      }elseif($tipo == "s"){
        $xand    = " rh25_recurso ";
	      $xinner .= " left join rhlotavinc on rh25_codigo = to_number(".$folha."_lotac,'9999') and rh25_anousu = ".$ano;
      }elseif($tipo == "o"){
        $xand    = " rh26_orgao ";
	      $xinner .= " left join rhlotaexe on rh26_codigo = to_number(".$folha."_lotac,'9999') and rh26_anousu = ".$ano;
      }elseif($tipo == "t"){
        $xand    = " rh55_codigo " ;
	      $xinner .= " left join rhpeslocaltrab on rh56_seqpes = rh02_seqpes
		                                 and rh56_princ = 't'
                   left join rhlocaltrab on rh55_codigo = rh56_localtrab ";
        $dbwherelta = " 1=1 ";
	if($lotaini != "" && $lotafin != ""){
	$dbwherelta = " rh55_estrut >= '$lotaini' and rh55_estrut <= '$lotafin' ";
	 }else if($lotaini != ""){
	$dbwherelta = " rh55_estrut >= '$lotaini' ";
	 }else if($lotafin != ""){
	$dbwherelta = " rh55_estrut >= '$lotafin' ";
	 }
      }
   $sqllota = "select count(distinct(".$folha."_regist))
               from ".$arquivo."
     		       inner join rhpessoal    on ".$folha."_regist = rh01_regist
		       $xinner
					 $xvinc
     	  where ".$dbwherelta."
			   	and ".$folha."_instit = ".db_getsession("DB_instit")."
     	    and ".$folha."_anousu = $ano
     	    and ".$folha."_mesusu = $mes";
   if($tipo == "s"){
      $sqllota	.= " and $xand = $quebra ";
   }else{
      $sqllota	.= " and $xand = '$quebra' ";
   }
   $sqllota .= $wherepes;

// echo "<BR><BR> 3.0 $sqllota";
// echo "<BR><BR> 3.1 $tipo";
// exit;
   $resultlota = db_query($sqllota);
   db_fieldsmemory($resultlota,0);
   $pdf->cell(20,$alt,$count,0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'P.EXTRA   ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($pextra,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'RETENCAO  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($retencao,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'DEDUCAO  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($deducao,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'DIFERENCA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($rub_dif,'f'),0,1,"R",0);
   $sqlprev = "select round(sum(prev1),2) as prev1,
                      round(sum(prev2),2) as prev2,
     		              round(sum(prev3),2) as prev3,
     		              round(sum(prev4),2) as prev4,
     		              round(sum(basefgts),2) as basefgts ,
     		              round(sum(fgts),2) as fgts

               from (select ".$folha."_lotac,
     	                      case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,
     		                    case when rh02_tbprev = 2 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev2,
     		                    case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		                    case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4,
     		                    case when ".$folha."_rubric = 'R991' then ".$folha."_valor*0.08 end as basefgts,
     		                    case when ".$folha."_rubric = 'R991' then round(".$folha."_valor*0.08,2) end as fgts
     		from ".$arquivo."
     		       inner join rhpessoal    on ".$folha."_regist = rh01_regist
		       $xinner
					 $xvinc
     		where ".$folha."_anousu = $ano
			   	and ".$folha."_instit = ".db_getsession("DB_instit")."
     		  and ".$folha."_mesusu = $mes";
   if($tipo == "s"){
      $sqlprev	.= " and $xand = $quebra ";
   }else{
      $sqlprev	.= " and $xand = '$quebra' ";
   }
   $sqlprev .= " and ".$folha."_rubric in ('R992','R991') $wherepes ) as x ";
// echo "<BR><BR>$sqlprev";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992')
   $resultprev = db_query($sqlprev);
   db_fieldsmemory($resultprev,0);
   $pdf->ln(3);
   $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 3   :'.db_formatar($prev3,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 4   :'.db_formatar($prev4,'f'),0,1,"L",0);
   $pdf->cell(45,$alt,'BASE F.G.T.S. :'.db_formatar($basefgts,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'F.G.T.S. EMPR :'.db_formatar($fgts,'f'),0,1,"L",0);
   $vencimentos = 0;
   $descontos = 0;
   $baseprev = 0;
   $baseirf  = 0;
   $quebra = $lota;
   if($tipo == "s"){
      if(empty($quebra)){
        $quebra = 0;
      }
   }
   $pdf->sety(290);
}else{
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(60,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",1,"C",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $sqllota = "select count(distinct(".$folha."_regist))
               from ".$arquivo."
     		       inner join rhpessoal    on ".$folha."_regist = rh01_regist
                       inner join rhpessoalmov on rh02_anousu = ".$folha."_anousu
               		                      and rh02_mesusu = ".$folha."_mesusu
	                                      and rh02_regist = rh01_regist
	   			              and rh02_instit = ".$folha."_instit
                       left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
	  	       inner join rhregime     on rh02_codreg = rh30_codreg
											                          and rh30_instit = rh02_instit
					     $xvinc
               $inner_join
	             where ".$folha."_anousu = $ano
		             and ".$folha."_mesusu = $mes
				       	 and ".$folha."_instit = ".db_getsession("DB_instit")."
		           $wherepes
               $whereestrut
		 ";
		 //echo $sqllota;
   $resultlota = db_query($sqllota);
   db_fieldsmemory($resultlota,0);
   $pdf->cell(20,$alt,$count,0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'P.EXTRA   ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($pextra,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'RETENCAO  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($retencao,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'DEDUCAO   ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($deducao,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'DIFERENCA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($rub_dif,'f'),0,1,"R",0);
   $sqlprev = "select round(sum(prev1),2) as prev1,
                      round(sum(prev2),2) as prev2,
            		      round(sum(prev3),2) as prev3,
     		              round(sum(prev4),2) as prev4,
     		              round(sum(basefgts),2) as basefgts,
     		              round(sum(fgts),2) as fgts
               from (select ".$folha."_lotac,
     	               case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,
     		             case when rh02_tbprev = 2 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev2,
     		             case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		             case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4,
     		             case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as basefgts,
     		             case when ".$folha."_rubric = 'R991' then round(".$folha."_valor*0.08,2) end as fgts
     		             from ".$arquivo."
     		             inner join rhpessoal    on ".$folha."_regist = rh01_regist
                     inner join rhpessoalmov on rh02_anousu = ".$folha."_anousu
           		                              and rh02_mesusu = ".$folha."_mesusu
		                                        and rh02_regist = rh01_regist
									    				              and rh02_instit = ".$folha."_instit
                     left join  rhpesbanco   on rh02_seqpes = rh44_seqpes
	                   inner join rhregime     on rh30_codreg = rh02_codreg
                                            and rh30_instit = rh02_instit
        					   $xvinc
                     $inner_join
     								 where ".$folha."_anousu = $ano
     								   and ".$folha."_mesusu = $mes
									 		 and ".$folha."_instit = ".db_getsession("DB_instit")."
     						 		   and ".$folha."_rubric in ('R992','R991')
		                   $wherepes
                       $whereestrut
		                 ) as x
      	";
	          // ver esta caso depois
     		  //and ".$folha."_rubric in ('R990','R992')
   $resultprev = db_query($sqlprev);
   db_fieldsmemory($resultprev,0);
   $pdf->ln(3);
   $pdf->setfont('arial','',7);
   $pdf->cell(25,$alt,$oValoresPatronais->data[0]->nome.':',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($prev1,'f')),0,0,"R",0);
   $pdf->cell(25,$alt,$oValoresPatronais->data[1]->nome.':',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($prev2,'f')),0,0,"R",0);
   $pdf->cell(25,$alt,$oValoresPatronais->data[2]->nome.':',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($prev3,'f')),0,0,"R",0);
   $pdf->cell(25,$alt,$oValoresPatronais->data[3]->nome.':',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($prev4,'f')),0,1,"R",0);

   if($folha == "r35" || $folha == "r93" || $folha == "r94"){
	   $pdf->ln(3);

	   $prevPercentual1 = $prev1 * ($oValoresPatronais->data[0]->valor/100);
	   $prevPercentual2 = $prev2 * ($oValoresPatronais->data[1]->valor/100);
	   $prevPercentual3 = $prev3 * ($oValoresPatronais->data[2]->valor/100);
	   $prevPercentual4 = $prev4 * ($oValoresPatronais->data[3]->valor/100);

	   $pdf->cell(25,$alt,"PATRONAL(".$oValoresPatronais->data[0]->valor."%)".": ",0,0,"L",0);
	   $pdf->cell(20,$alt,db_formatar($prevPercentual1,'f'),0,0,"R",0);
	   $pdf->cell(25,$alt,"PATRONAL(".$oValoresPatronais->data[1]->valor."%)".": ",0,0,"L",0);
	   $pdf->cell(20,$alt,db_formatar($prevPercentual2,'f'),0,0,"R",0);
	   $pdf->cell(25,$alt,"PATRONAL(".$oValoresPatronais->data[2]->valor."%)".": ",0,0,"L",0);
	   $pdf->cell(20,$alt,db_formatar($prevPercentual3,'f'),0,0,"R",0);
	   $pdf->cell(25,$alt,"PATRONAL(".$oValoresPatronais->data[3]->valor."%)".": ",0,0,"L",0);
	   $pdf->cell(20,$alt,db_formatar($prevPercentual4,'f'),0,1,"R",0);

   }

   $pdf->ln(3);
   $pdf->cell(25,$alt,'BASE F.G.T.S. :',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($basefgts,'f')),0,0,"R",0);
   $pdf->cell(25,$alt,'F.G.T.S. EMPR :',0,0,"L",0);
   $pdf->cell(20,$alt,trim(db_formatar($fgts,'f')),0,1,"R",0);

}
$pdf->Output();
//exit;
?>