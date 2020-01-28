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


include ("fpdf151/pdf.php");
include ("fpdf151/assinatura.php");
include ("libs/db_sql.php");
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("dbforms/db_funcoes.php");
include ("dbforms/db_relatorio_recurso.php");
// include ("dbforms/db_relrestos.php");
include ("classes/db_orctiporec_classe.php");
include ("classes/db_empresto_classe.php");

$classinatura = new cl_assinatura;
$clorctiporec = new cl_orctiporec;
$clempresto   = new cl_empresto;


parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$anousu = db_getsession("DB_anousu");
$anousu_ant = $anousu-1;
$data_ini = $anousu."-01-01";

$xinstit = split("-", $db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinst;
	$xvirg = ', ';
}

// cria uma tabela temporaria para receber os dados
pg_exec("begin");
$sql = " create temp table work_rel ( 
                        recurso integer, 
                        recurso_descr varchar(35),
					 	res_anterior  float8,
                        liquidado float8,
						nao_liquidado float8,  
						rp_anterior float8,
						rp_atual float8, /* exercicio imediatamente anterior ao atual */
						receita float8,
						despesa float8,
						caixa  float8,
						banco float8,
						resultado float8)
			";
$res = pg_exec($sql);                      



/*  RPs
 *  ------------------------------------------------------------------------------
 *  selece para retornar saldo dos rps
 */
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
		sum(case when e60_anousu = $anousu_ant then saldo_rp  else    0 end ) as rp_anterior,
		sum(case when e60_anousu < $anousu_ant then saldo_rp  else    0 end ) as rp_atual
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
$result = pg_exec($sql);
for ($x = 0; $x < pg_numrows($result); $x ++) {	
	  db_fieldsmemory($result, $x);
	  $sql = "select * from work_rel where recurso = $o15_codigo";
	  $rr = pg_exec($sql);
	  if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						            set rp_anterior = coalesce(".$rp_anterior.",0)  , 
										  rp_atual     = coalesce(".$rp_atual.",0)
						 where recurso = $o15_codigo
					  ";
			pg_exec($sql);
	  } else {
			$sql = " insert into work_rel  (recurso, recurso_descr, rp_anterior,rp_atual)
						 values ($o15_codigo, '".substr($o15_descr,0,30)."',".round($rp_anterior,2)." , ".round($rp_atual,2) ." ) 
					   ";
			pg_exec($sql);
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
$result_baldesp = pg_exec($sql_baldesp);
if (pg_numrows($result_baldesp) > 0) {
	for ($x = 0; $x < pg_numrows($result_baldesp); $x ++) {
	 	 db_fieldsmemory($result_baldesp, $x);

         $sql = "select * from work_rel where recurso = $o15_codigo";
	     $rr = pg_exec($sql);
	     if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						 set liquidado         = coalesce(".$liquidado.",0)  , 
							  nao_liquidado  = coalesce(".($empenhado - $liquidado).",0)
						 where recurso = $o15_codigo
					  ";
		 	pg_exec($sql);
	     } else {
		  	$sql = " insert into work_rel  (recurso, recurso_descr, liquidado, nao_liquidado)
						 values ($o15_codigo, '".substr($o15_descr,0,30)."',".round($liquidado,2)." , ".round(($empenhado-$liquidado),2) ." ) 
					   ";
			pg_exec($sql);
	     }
	}
}	


// atualiza saldo_bancario e saldo_caixa
$sql_where = "";
if ($recurso > 0) {
	$sql_where = ' and c61_codigo  = '.$recurso;
}
$sql="select
                  c61_codigo,
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
			    inner join conplanoexe on c62_anousu = ".$anousu."  and c62_reduz = k13_conta
                inner join conplanoreduz on c62_reduz = c61_reduz  and c61_anousu=".db_getsession("DB_anousu")."                                                      
				inner join conplano on c61_codcon = c60_codcon and c61_anousu = c60_anousu
				inner join orctiporec on o15_codigo = c61_codigo
				left join conplanoconta on c63_codcon = conplano.c60_codcon and c63_anousu=c60_anousu
				left join db_bancos on trim(db90_codban) = conplanoconta.c63_banco::varchar(10)  
			where  c60_codsis in (5,6)
                       $sql_where and c61_instit  in  (".str_replace('-', ', ', $db_selinstit).")                
          ) as x
                 group by c61_codigo,c60_codsis,o15_descr		                      
		 ";
$result_contas = pg_exec($sql);
$nrows = pg_numrows($result_contas);
if (pg_numrows($result_contas) > 0) {
	for ($x = 0; $x < pg_numrows($result_contas); $x ++) {
		db_fieldsmemory($result_contas, $x);
		$sql = "select * from work_rel where recurso = $c61_codigo";
		$rr = pg_exec($sql);
		if (pg_numrows($rr) > 0) {
			if ($c60_codsis == 5)
				$sql = "update work_rel 
				            set caixa = ".$final." , 
							      recurso_descr='".$o15_descr."'											  
							where recurso = ".$c61_codigo;
			else
				$sql = " update work_rel 
							 set banco= ".$final."    ,
								   recurso_descr='".$o15_descr."'				  						  
				 			where recurso =".$c61_codigo;
			pg_exec($sql);
		} else {
			if ($c60_codsis == 5)
				$sql = " insert into work_rel  (recurso, recurso_descr, caixa)  
							 values ($c61_codigo, '".substr($o15_descr,0,35)."', $final )  ";
			else
				$sql = " insert into work_rel  (recurso, recurso_descr, banco )
							 values ($c61_codigo, '".substr($o15_descr,0,30)."', $final  )  ";
			pg_exec($sql);
		}
	}
}

// receita e despesa extra
// seleciona todas as contas do sistema financeiro, onde qualquer movimento a credito = receita
// e qualquer movimento a debito=despesa
$where = "";
if ($recurso > 0) {
	$where = ' and c61_codigo  = '.$recurso;
}
$where = '    c60_codsis=7  and c61_instit  in ('.str_replace('-', ', ', $db_selinstit).')  '.$where;

$sql_bver = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$anousu.'-01-01',$data_limite,true,$where);
$sql_bver = "select 
                            c61_codigo,
							sum(saldo_anterior_debito) as saldo_anterior_debito,
							sum(saldo_anterior_credito) as saldo_anterior_credito
				    from (".$sql_bver.") as xxx
                        inner join orctiporec on o15_codigo = c61_codigo
					where c61_reduz >0   
				    group by c61_codigo
				   ";				   
$result_bver = pg_exec($sql_bver);
if (pg_numrows($result_bver) > 0) {
	for ($x = 0; $x < pg_numrows($result_bver); $x ++) {
	 	 db_fieldsmemory($result_bver, $x);

         $sql = "select * from work_rel where recurso = $c61_codigo";
	     $rr = pg_exec($sql);
	     if (pg_numrows($rr) > 0) {
			$sql = " update work_rel
						 set receita     = coalesce(".($saldo_anterior_credito).",0)  , 
							  despesa  = coalesce(".($saldo_anterior_debito).",0)
						 where recurso = $c61_codigo
					  ";
		 	pg_exec($sql);
	     } else {
		  	$sql = " insert into work_rel  (recurso, recurso_descr, receita, despesa)
						 values ($o15_codigo, '".substr($o15_descr,0,30)."',".round($saldo_anterior_credito,2)." , ".round($saldo_anterior_debito,2) ." ) 
					   ";
			pg_exec($sql);
	     }	    
	     
	}
}	

//  ------------------------------------------------------ //
$res = pg_query("select *  from work_rel order by recurso");
// db_criatabela($res);exit;

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

$alt	 	= 4;
$tam	= 22;
$troca  = 1;

$t1 = 0;
$t2 = 0;
$t3 = 0;
$t4 = 0;
$t5 = 0;
$t6 = 0;	
$t7 = 0;
$t8 = 0;
$t9 = 0;
$t10 = 0;


$pdf->setfont('arial', '', 7);

for ($x = 0; $x < $rows; $x ++) {
	db_fieldsmemory($res, $x);

	
	  if ($pdf->gety() > $pdf->h - 40 || $troca == 1) {			
			$troca = 0;
			/*
			 * 		header das colunas
			 * 		-------------------------------------------------
		 	*/
			$pdf->addPage('L');
			
			$pdf->cell(55, $alt, "INFORMAÇÕES DO RECURSO", 'TBR', 0, "C", 1);
			$pdf->cell($tam, $alt, "RESULTADO", '1', 0, "C", 1);
			$pdf->cell($tam * 2, $alt, "SALDO A PAGAR DO EXERCICIO", '1', 0, "C", 1);
			$pdf->cell($tam * 2, $alt, ' SALDO DE RESTOS A PAGAR ', '1', 0, "C", 1);
			$pdf->cell($tam * 2, $alt, "EXTRA-ORÇAMENTARIA", '1', 0, "C", 1);
			$pdf->cell($tam * 2, $alt, "SALDO DA TESOURARIA", '1', 0, "C", 1);
			$pdf->cell($tam+5, $alt, "RESULTADO", 'TB', 0, 'C', 1);
			$pdf->Ln();
			$pdf->cell(10, $alt, "REC", 'TBR', 0, "R", 0);
			$pdf->cell(45, $alt, "DESCRIÇÃO", '1', 0, "L", 0);
			$pdf->cell($tam, $alt, "EX-ANTERIOR", '1', 0, "L", 0);
			$pdf->cell($tam, $alt, "LIQUIDADO", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "NÃO LIQUIDADO", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "EX-ANTERIORES", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "EX DE 2005", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "RECEITA", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "DESPESA", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "CAIXA", '1', 0, "C", 0);
			$pdf->cell($tam, $alt, "BANCOS", '1', 0, "C", 0);
			$pdf->cell($tam+5, $alt, "DEFICIT/SUPERAVIT", 'TB', 0, "C", 0);
		
			$pdf->Ln();

	
	}
	//$pdf->setfont('arial', '', 5);
    $resultado = $caixa+$banco+$receita - ( $liquidado+$nao_liquidado+$rp_anterior+$rp_atual+$despesa ); 

	$pdf->cell(10, $alt, $recurso, 0, 0, "R", 0);
	$pdf->cell(45, $alt, $recurso_descr, 0, 0, "L", 0);
	$pdf->cell($tam, $alt, db_formatar(0,'f'), 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($liquidado,'f')   , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($nao_liquidado,'f')  , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($rp_anterior,'f')  , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($rp_atual,'f')   , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($receita,'f')   , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($despesa,'f')  , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($caixa,'f')   , 0, 0, "R", 0);
	$pdf->cell($tam, $alt, db_formatar($banco,'f')   , 0, 0, "R", 0);
	$pdf->cell($tam+5, $alt, db_formatar($resultado,'f')   , 0, 0, "R", 0);
	$pdf->Ln();
	
	$t1 += 0;
	$t2 +=$liquidado;
	$t3 +=$nao_liquidado;
	$t4 += $rp_anterior;
	$t5 += $rp_atual;
	$t6 += $receita;	
	$t7 += $despesa;
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
$pdf->cell($tam, $alt, db_formatar($t6,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t7,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t8,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam, $alt, db_formatar($t9,'f'), 'TB', 0, "R", 0);
$pdf->cell($tam+5, $alt, db_formatar($t10,'f')   , 'TB', 0, "R", 0);
$pdf->Ln();

pg_exec("commit");


$pdf->Output();

?>