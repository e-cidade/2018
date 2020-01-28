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
include(modification("libs/db_utils.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("libs/db_libcontabilidade.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_relatorio_recurso.php"));
// include(modification("dbforms/db_relrestos.php"));
include(modification("classes/db_orctiporec_classe.php"));
include(modification("classes/db_empresto_classe.php"));

$classinatura = new cl_assinatura;
$clorctiporec = new cl_orctiporec;
$clempresto   = new cl_empresto;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


$anousu = db_getsession("DB_anousu");
$anousu_ant = $anousu-1;
$data_ini = $anousu."-01-01";


/* buscar parametro na tabela caiparametro */
$lExtraOrcamentario = false;
$sSqlParametro      = " select k29_datasaldocontasextra from caiparametro ";
$rsSqlParametro     = db_query($sSqlParametro);

if (pg_num_rows($rsSqlParametro) > 0) {

	$k29_datasaldocontasextra = db_utils::fieldsMemory($rsSqlParametro,0)->k29_datasaldocontasextra;

	if (trim($k29_datasaldocontasextra) != "") {
	 $lExtraOrcamentario = true;
	} else {
    $lExtraOrcamentario = false;
  }
}


$xinstit = split("-", $db_selinstit);
$resultinst = db_query("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}

// cria uma tabela temporaria para receber os dados
db_query("begin");
$sql = " create temp table work_rel (
                recurso             integer,
                recurso_descr       varchar(35),
		 	    res_anterior        float8,
                liquidado           float8,
			    nao_liquidado       float8,
			    rp_anterior         float8,
				rp_atual            float8, /* exercicio imediatamente anterior ao atual */
				saldo_extra_receber float8 default 0,
				saldo_extra_pagar   float8 default 0,
				caixa               float8,
				banco               float8,
				resultado           float8
				); create index work_rel_recurso_in on work_rel(recurso);";
$res = db_query($sql);

// saldo anterior - informado manualmente nos parametros
$sql_where = "";
$sp ="";
if ($recurso > 0) {
	$sql_where = ' o48_codrec  = '.$recurso;
	$sp = " and ";
}
$sql_where .= $sp.' o48_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
$sql_where .= " and o48_anousu = $anousu ";

$sql = "
 	    select
                   o48_codrec,
		   o15_descr,
                   sum(o48_valor) as valor
    	    from  orcparamrecursoval
		 inner join orctiporec on o15_codigo = o48_codrec
   	    where  $sql_where and orcparamrecursoval.o48_grupo=1
            group by o48_codrec,o15_descr
            order by o48_codrec,o15_descr
    ";
$result = db_query($sql);
for ($x = 0; $x < pg_numrows($result); $x ++) {
	  db_fieldsmemory($result, $x);
	  $sql = "select * from work_rel where recurso = $o48_codrec";
	  $rr = db_query($sql);
	  if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						            set res_anterior = coalesce(".$valor.",0)
						 where recurso = $o48_codrec
					  ";
			db_query($sql);
	  } else {
			$sql = " insert into work_rel  (recurso, recurso_descr, res_anterior)
						 values ($o48_codrec, '".substr($o15_descr,0,30)."',".round($valor,2)." )
					   ";
	  	    if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[1] - Erro ao inserir registro temporário.");
			}
	  }
}


// RPss
$sql_where = "";
if ($recurso > 0) {
	$sql_where = ' and e91_recurso  = '.$recurso;
}
$sele_work = ' e60_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
$sql_restos = $clempresto->sql_rp($anousu, $sele_work, $data_ini, $data_limite, $sql_where, " and ".$sele_work);

$sql = "
   select
 		o15_codigo,
	    o15_descr,
		sum(case when e60_anousu < $anousu_ant then saldo_rp  else    0 end ) as rp_anterior,
		sum(case when e60_anousu = $anousu_ant then saldo_rp  else    0 end ) as rp_atual
   from (
   select
       e60_anousu,
       e91_recurso as o15_codigo,
       o15_descr,
       round((sum(round(e91_vlremp,2)) - (sum(round(e91_vlranu,2))  + sum(round(vlranu,2)))
         - ( sum(round(e91_vlrpag,2)) + sum(round(vlrpag,2))  )),2) as saldo_rp
   from ($sql_restos) as x
   group by
          e60_anousu,
          e91_recurso,
          o15_descr
   ) as xx
  group by
        o15_codigo,
	    o15_descr

  ";
$result = db_query($sql);
for ($x = 0; $x < pg_numrows($result); $x ++) {
	  db_fieldsmemory($result, $x);
	  $sql = "select * from work_rel where recurso = $o15_codigo";
	  $rr = db_query($sql);
	  if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						            set rp_anterior = coalesce(".$rp_anterior.",0)  ,
										  rp_atual     = coalesce(".$rp_atual.",0)
						 where recurso = $o15_codigo
					  ";
			db_query($sql);
	  } else {
			$sql = " insert into work_rel  (recurso, recurso_descr, rp_anterior,rp_atual)
						 values ($o15_codigo, '".substr($o15_descr,0,30)."',".round($rp_anterior,2)." , ".round($rp_atual,2) ." )
					   ";
	  		if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[2] - Erro ao inserir registro temporário.");
			}
	  }
}

// seleciona saldo a pagar e nao liquidados a pagar de empenho
$sql_where = "";
if ($recurso > 0) {
	$sql_where = ' and o58_codigo  = '.$recurso;
}
$sele_work = ' w.o58_instit in ('.str_replace('-', ', ', $db_selinstit).')  '.$sql_where;
$sql_baldesp = db_dotacaosaldo(8, 1, 4, true, $sele_work, $anousu, $anousu."-01-01", $data_limite, 8, 0, true);
$sql_baldesp = "select o58_codigo as o15_codigo,
                        o15_descr,
                       (sum(empenhado_acumulado)-sum(anulado_acumulado)) as empenhado,
                        sum(liquidado_acumulado) as liquidado,
                        sum(pago_acumulado) as pago
               from ($sql_baldesp) as x
               group by o58_codigo,o15_descr
               having o58_codigo > 0
                          ";
               //die($sql_baldesp);
$result_baldesp = db_query($sql_baldesp);
if (pg_numrows($result_baldesp) > 0) {
	for ($x = 0; $x < pg_numrows($result_baldesp); $x ++) {
	 	 db_fieldsmemory($result_baldesp, $x);

         $sql = "select * from work_rel where recurso = $o15_codigo";
	     $rr = db_query($sql);
	     if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						 set liquidado         = coalesce(".($liquidado-$pago).",0)  ,
							   nao_liquidado  = coalesce(".($empenhado-$liquidado).",0)
						 where recurso = $o15_codigo
					  ";
		 	db_query($sql);
	     } else {
		  	$sql = " insert into work_rel  (recurso, recurso_descr, liquidado, nao_liquidado)
						 values ($o15_codigo, '".substr($o15_descr,0,30)."',".round(($liquidado-$pago),2)." , ".round(($empenhado-$liquidado),2) ." )
					   ";
	        if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[3] - Erro ao inserir registro temporário.");
			}
	     }
	}
}


// atualiza saldo_bancario e saldo_caixa
$sql_where = "";
if ($recurso > 0) {
	$sql_where = ' and c61_codigo  = '.$recurso;
}
$sql="select c61_codigo,
             o15_descr,
             c60_codsis,
             sum(substr(valor,43,12)::float8) as final
        from (
             select k13_conta,
                    k13_descr,
					          c61_codigo,
                    c60_codsis,
                    o15_descr,
					          fc_saltessaldo(k13_conta,'$data_limite','$data_limite', null, c61_instit)   as valor
			         from saltes
			              inner join conplanoexe on c62_anousu      = ".$anousu." and c62_reduz  = k13_conta
                    inner join conplanoreduz on c62_reduz     = c61_reduz   and c61_anousu = ".db_getsession("DB_anousu")."
				            inner join conplano on c61_codcon         = c60_codcon  and c61_anousu = c60_anousu
				            inner join orctiporec on o15_codigo       = c61_codigo
				            left  join conplanoconta on c63_codcon    = conplano.c60_codcon and c63_anousu = c60_anousu
				            left  join db_bancos on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)
			        where c60_codsis in (5,6)
                    $sql_where and c61_instit  in  (".str_replace('-', ', ', $db_selinstit).")
             ) as x
       group by c61_codigo,
                c60_codsis,
                o15_descr
		 ";
$result_contas = db_query($sql);
$nrows = pg_numrows($result_contas);
if (pg_numrows($result_contas) > 0) {
	for ($x = 0; $x < pg_numrows($result_contas); $x ++) {
		db_fieldsmemory($result_contas, $x);
		$sql = "select * from work_rel where recurso = $c61_codigo";
		$rr = db_query($sql);
		if (pg_numrows($rr) > 0) {
			if ($c60_codsis == 5)
				$sql = "update work_rel
				           set caixa         = ".$final." ,
							         recurso_descr = '".substr($o15_descr,0,30)."'
							   where recurso       = ".$c61_codigo;
			else
				$sql = " update work_rel
							      set banco= ".$final."    ,
								        recurso_descr='".substr($o15_descr,0,30)."'
				 			    where recurso =".$c61_codigo;
			db_query($sql);

		} else {
			if ($c60_codsis == 5)
				$sql = " insert into work_rel (recurso, recurso_descr, caixa)
							                 values ($c61_codigo, '".substr($o15_descr,0,35)."', $final)";
			else
				$sql = " insert into work_rel (recurso, recurso_descr, banco )
							                 values ($c61_codigo, '".substr($o15_descr,0,30)."', $final)";
		    if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[4] - Erro ao inserir registro temporário.");
			}
		}
	}
}
$sql_where      = "";
$sSqlRecurso    = "select o15_codigo, o15_descr";
$sSqlRecurso   .= "  from orctiporec ";
$sSqlRecurso   .= " ";
if ($recurso > 0) {
  $sSqlRecurso .= ' where o15_codigo  = '.$recurso;
}
$rsRecurso = db_query($sSqlRecurso);
//for ($iRec = 0; $iRec < pg_num_rows($rsRecurso); $iRec++) {
//
if (false) {

  $oRecurso  = db_utils::fieldsMemory($rsRecurso, $iRec);
  $sSqlExtra = "select sum(valor_extra) as valor_extra_final,
                       c60_estrut
                  from (select
                          (select coalesce(rnsaldofinal,0)
                            from fc_saltessaldoextra(k02_codigo,'{$data_limite}'::date,
                                                     {$oRecurso->o15_codigo})
                          ) as valor_extra,
                           c60_estrut
                          from tabplan
                               inner join conplanoreduz on c61_reduz  = k02_reduz
                                                       and c61_anousu = k02_anousu
                               inner join conplano      on c60_anousu = c61_anousu
                                                       and c60_codcon = c61_codcon
                         where c61_instit  = ".db_getsession("DB_instit")."
                           and k02_anousu = ".db_getsession("DB_anousu")."
                        ) as x
                      group by c60_estrut ";
  $rsSaldoExtra = db_query($sSqlExtra);
  $nSaldoExtraPagar = db_utils::fieldsMemory($rsSaldoExtra,0)->valor_extra_final;
  if (pg_num_rows($rsSaldoExtra) > 0) {

    for ($x = 0; $x < pg_numrows($rsSaldoExtra); $x ++) {

      db_fieldsmemory($rsSaldoExtra, $x);
      /* Pega a primeira posição do estrutural */
      $iEstrutural = substr($c60_estrut,0,1);
      $sql = "select * from work_rel where recurso = $oRecurso->o15_codigo";
      $rr = db_query($sql);
      if ($iEstrutural == 1 || $iEstrutural == 5) {
        $sCampoUpdate = "saldo_extra_receber";
      } else if ($iEstrutural == 2 || $iEstrutural == 6) {
        $sCampoUpdate = "saldo_extra_pagar";
      } else {
        continue;
      }
      if (pg_numrows($rr) > 0) {
      	/*
      	if ($iEstrutural == 1 || $iEstrutural == 5) {
      		$sCampoUpdate = "saldo_extra_receber";
      	} else if ($iEstrutural == 2 || $iEstrutural == 6) {
      		$sCampoUpdate = "saldo_extra_pagar";
      	}
        */
        $sql = "update work_rel
		                   set
		                   $sCampoUpdate = $sCampoUpdate+".$valor_extra_final." ,
		                   recurso_descr='".substr($oRecurso->o15_descr,0,30)."'
                 where recurso = ".$oRecurso->o15_codigo;

        db_query($sql);

      } else {


        $sql = " insert into work_rel  (recurso, recurso_descr ,$sCampoUpdate)
                                values ($oRecurso->o15_codigo,
                                        '".substr($oRecurso->o15_descr,0,30)."',
                                        $valor_extra_final
                                       )";
       if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[5] - Erro ao inserir registro temporário.");
		}
      }
    }
  }
}
// receita e despesa extra
// seleciona saldo do grupo 211%
//
// caso tenha sido informado valores manuais para o exercicio
// então usamos os valores informados na coluna extra orçamentária
//

$sql = "select * from orcparamrecursoval
         where o48_anousu=".db_getsession("DB_anousu")."
           and o48_grupo=2
	";
$res = db_query($sql);
if (pg_numrows($res) > 0 ){

	//seleciona os valores informados manualmente
	$where = "";
	if ($recurso > 0) {
		$where = ' and o48_codrec  = '.$recurso;
	}
        $sql = "
	         select o48_codrec,o15_descr,sum(o48_valor) as saldo_final
	         from orcparamrecursoval
		        inner join orctiporec on o15_codigo=orcparamrecursoval.o48_codrec
		 where o48_grupo=2 and o48_anousu = ".db_getsession("DB_anousu")." $where
		 group by o48_codrec,
		          o15_descr
	       ";
        $result = db_query($sql);
	// db_criatabela($result); exit;
 	if (pg_numrows($res) > 0) {
		for ($x = 0; $x < pg_numrows($result); $x ++) {
		     db_fieldsmemory($result, $x);

	             $sql = "select * from work_rel where recurso = $o48_codrec ";
		     $rr = db_query($sql);
		     if (pg_numrows($rr) > 0) {
				$sql = " update work_rel
   					        set saldo_extra_pagar = coalesce(".($saldo_final).",0)
					 where recurso = $o48_codrec
					  ";
			 	db_query($sql);
		     } else {
			  	$sql = " insert into work_rel  (recurso, recurso_descr, saldo_extra_pagar)
					 values ($o48_codrec,'".substr($o15_descr,0,30)."',".round($saldo_extra_pagar,2).")
				       ";
		      if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[6] - Erro ao inserir registro temporário.");
			  }
		     }

		}
	}
} else {

	$where = "";
	if ($recurso > 0) {
		$where = ' and c61_codigo  = '.$recurso;
	}
	$where = " c60_estrut like '21881%'  ".' and c61_instit  in ('.str_replace('-', ', ', $db_selinstit).')  '.$where;

	$sql_bver = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$anousu.'-01-01',$data_limite,true,$where);
/*	$sql_bver = "select saldo_final, estrutural
		     from (".$sql_bver.") as x
		     where x.estrutural like '200000%'
                     limit 1
                     ";*/
	$result_bver = db_query($sql_bver);
//	db_criatabela($result_bver);
  if (pg_numrows($result_bver) > 0) {
    $livre = 1 ; // somente recurso livre
    for ($x = 0; $x < pg_numrows($result_bver); $x ++) {
      db_fieldsmemory($result_bver, $x);

      $sql = "select * from work_rel where recurso = $c61_codigo";
      $rr = db_query($sql);
  //    db_criatabela($rr);
      if (pg_numrows($rr) > 0) {
        $sql = " update work_rel set saldo_extra_pagar = saldo_extra_pagar + coalesce(".($saldo_final).",0)
          where recurso = $c61_codigo
          ";
        db_query($sql);
      } else {
        $sSqlRecurso    = "select o15_descr";
        $sSqlRecurso   .= "  from orctiporec ";
        $sSqlRecurso .= ' where o15_codigo  = '.$c61_codigo;
        $rsRecurso = db_query($sSqlRecurso);
        if ($rsRecurso && pg_num_rows($rsRecurso) > 0) {

          $sDescricaoRecurso = db_utils::fieldsMemory($rsRecurso,0)->o15_descr;
          $sDescricaoRecurso = substr($sDescricaoRecurso, 0, 30);
          
          $sql = " insert into work_rel (recurso, recurso_descr, saldo_extra_pagar)
                               values ($c61_codigo, '{$sDescricaoRecurso}',".round($saldo_final,2).") ";
         if (!db_query($sql)){
				db_redireciona("db_erros.php?fechar=true&db_erro=[7] - Erro ao inserir registro temporário.");
		 }

        }
      }

    }
	}
} // end if
//exit;
//  ------------------------------------------------------ //
$res = db_query("select *  from work_rel order by recurso");
//db_criatabela($res);exit;

$rows = pg_numrows($res);
$cols = pg_numfields($res);

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ($recurso == 0) {
	$o15_descr = "TODOS";
}
$head2 = "RESULTADOS FINANCEIROS POR RECURSO";
$head3 = "RECURSO : ".$recurso." : ".$o15_descr;
$head5 = "INSTITUIÇÕES : ".$descr_inst;
$dt = split('-', $data_limite);
$head7 = 'DATA LIMITE :'."$dt[2]/$dt[1]/$dt[0]";

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);

$alt	 = 4;
$tam	 = 22;
$troca = 1;

$t1   = 0;
$t2   = 0;
$t3   = 0;
$t4   = 0;
$t5   = 0;
$t6   = 0;
$t7   = 0;
$t8   = 0;
$t9   = 0;
$t10  = 0;

$pdf->setfont('arial', '', 7);

for ($x = 0; $x < $rows; $x ++) {

	db_fieldsmemory($res, $x);

	  if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {
			$troca = 0;

			// header das colunas
			$pdf->addPage('L');

			$pdf->cell(55,       $alt, "INFORMAÇÕES DO RECURSO",     'TBR', 0, "C", 1);
			$pdf->cell($tam,     $alt, "RESULTADO",                  '1',   0, "C", 1);
			$pdf->cell($tam*2,   $alt, "SALDO A PAGAR DO EXERCICIO", '1',   0, "C", 1);
			$pdf->cell($tam*2,   $alt, ' SALDO DE RESTOS A PAGAR ',  '1',   0, "C", 1);
			if ($lExtraOrcamentario) {
			 $pdf->cell(40,      $alt, "EXTRA-ORÇAMENTARIA",         '1',   0, "C", 1);
			} else {
				$pdf->cell($tam+10,$alt, "EXTRA-ORÇAMENTARIA",         '1',   0, "C", 1);
			}
			$pdf->cell($tam*2,   $alt, "SALDO DA TESOURARIA",        '1',   0, "C", 1);
			$pdf->cell($tam+5,   $alt, "RESULTADO",                  'TB',  0, 'C', 1);
			$pdf->Ln();
			$pdf->cell(10,       $alt, "REC",                        'TBR', 0, "R", 0);
			$pdf->cell(45,       $alt, "DESCRIÇÃO",                  '1',   0, "L", 0);
			$pdf->cell($tam,     $alt, "EX-ANTERIOR",                '1',   0, "L", 0);
			$pdf->cell($tam,     $alt, "LIQUIDADO",                  '1',   0, "C", 0);
			$pdf->cell($tam,     $alt, "NÃO LIQUIDADO",              '1',   0, "C", 0);
			$pdf->cell($tam,     $alt, "EX-ANTERIORES",              '1',   0, "C", 0);
			$pdf->cell($tam,     $alt, "EX DE ".(db_getsession("DB_anousu")-1), '1', 0, "C", 0);
	    if ($lExtraOrcamentario) {
		   $pdf->cell(20,      $alt, "RECEBER" ,                    '1', 0, "C", 0);
		   $pdf->cell(20,      $alt, "PAGAR"  ,                     '1', 0, "C", 0);
		  } else {
		   $pdf->cell($tam+10,  $alt, "SALDO",                     '1',   0, "C", 0);
		  }
			$pdf->cell($tam,     $alt, "CAIXA",                      '1',   0, "C", 0);
			$pdf->cell($tam,     $alt, "BANCOS",                     '1',   0, "C", 0);
			$pdf->cell($tam+5,   $alt, "DEFICIT/SUPERAVIT",          'TB',  0, "C", 0);
			$pdf->Ln();
	}

	$saldo_extra   = $saldo_extra_receber + $saldo_extra_pagar;
//	$res_anterior  = 0;
//	$res_anterior  = $caixa + $banco;
//	$sumLiquidado  = 0;
//	$sumLiquidado  = $liquidado + $nao_liquidado + $rp_anterior + $rp_atual;
//	$liquidado     = 0;
//	$liquidado     = $sumLiquidado;

	if($res_anterior+$liquidado+$nao_liquidado+$rp_anterior+$rp_atual+$saldo_extra+$caixa+$banco+$resultado == 0){
		continue;
	}

	//$pdf->setfont('arial', '', 5);
  //$resultado = $caixa+$banco - ( $liquidado+$nao_liquidado+$rp_anterior+$rp_atual+$saldo_extra);
  $resultado = $caixa+$banco - ( $liquidado+$nao_liquidado+$rp_anterior+$rp_atual)+$saldo_extra_receber-$saldo_extra_pagar;

	$pdf->cell(10,      $alt, $recurso,                          0, 0, "R", 0);
	$pdf->cell(45,      $alt, $recurso_descr,                    0, 0, "L", 0);
	$pdf->cell($tam,    $alt, db_formatar($res_anterior, 'f'),   0, 0, "R", 0);
	$pdf->cell($tam,    $alt, db_formatar($liquidado,    'f'),   0, 0, "R", 0);
	$pdf->cell($tam,    $alt, db_formatar($nao_liquidado,'f'),   0, 0, "R", 0);
	$pdf->cell($tam,    $alt, db_formatar($rp_anterior,  'f'),   0, 0, "R", 0);
	$pdf->cell($tam,    $alt, db_formatar($rp_atual,     'f'),   0, 0, "R", 0);
	if ($lExtraOrcamentario) {
	 $pdf->cell(20, $alt, db_formatar($saldo_extra_receber,'f'), 0, 0, "R", 0);
   $pdf->cell(20, $alt, db_formatar($saldo_extra_pagar,  'f'), 0, 0, "R", 0);
	} else {
	 $pdf->cell($tam+10, $alt, db_formatar($saldo_extra,   'f'), 0, 0, "R", 0);
	}
	$pdf->cell($tam,    $alt, db_formatar($caixa,          'f'), 0, 0, "R", 0);
	$pdf->cell($tam,    $alt, db_formatar($banco,          'f'), 0, 0, "R", 0);
	$pdf->cell($tam+5,  $alt, db_formatar($resultado,      'f'), 0, 0, "R", 0);
	$pdf->Ln();

	$t1 += $res_anterior;
	$t2 +=$liquidado;
	$t3 +=$nao_liquidado;
	$t4 += $rp_anterior;
	$t5 += $rp_atual;
	if ($lExtraOrcamentario ) {
	 $t6 += $saldo_extra_receber;
	 $t7 += $saldo_extra_pagar;
	} else {
		$t6 += $saldo_extra;
	}
	$t8 += $caixa;
	$t9 += $banco;
	$t10 += $resultado;
}

// totalizador
$pdf->cell(55, $alt, "TOTAL GERAL", 'TB', 0, "C", 0);
$pdf->cell($tam, $alt, db_formatar($t1,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t2,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t3,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t4,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t5,'f'), 'TB', 0, "R", 0);
if ($lExtraOrcamentario) {
  $pdf->cell(20, $alt, db_formatar($t6,'f'), 'TB', 0, "R", 0);
  $pdf->cell(20, $alt, db_formatar($t7,'f'), 'TB', 0, "R", 0);
} else {
  $pdf->cell($tam+10, $alt, db_formatar($t6,'f'), 'TB', 0, "R", 0);
}
$pdf->cell($tam, $alt, db_formatar($t8,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t9,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam+5, $alt, db_formatar($t10,'f'), 'TB', 0, "R", 0);
$pdf->Ln();
// db_query("commit");
$pdf->Output();

?>
