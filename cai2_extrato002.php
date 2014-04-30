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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


$head2 = "EXTRATO BANCÁRIO";
$head4 = "PERÍODO : ".db_formatar(@$datai,"d")." A ".db_formatar(@$dataf,"d");

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

/// CONTAS MOVIMENTO

/*
 * 
 * Tabela temporária para guardar os registros das movimentações das contas escolhidas para geraçõ do relatório
 *  
 */
$sql1 = "create temporary table w_contasmovimento( k13_reduz      integer,
             	                                    k13_descr      varchar(40),
												    c60_estrut     varchar(15),
												    c60_codsis     integer,
												    c63_conta      varchar(50),
												    fc_saltessaldo varchar(54) ); 
		  create index w_contasmovimento_ind1 on w_contasmovimento(k13_reduz,k13_descr,c60_codsis,c63_conta);
		  create index w_contasmovimento_ind2 on w_contasmovimento(k13_reduz,k13_descr,c60_estrut);
		  create index w_contasmovimento_ind3 on w_contasmovimento(k13_reduz,k13_descr,fc_saltessaldo);";

/*
 * 
 * Sql que grava os registros das movimentações das contas na tabela temporaria
 * 
 */
$sql2 = "insert into w_contasmovimento select k13_reduz,
             	                              k13_descr,
											  c60_estrut,
											  c60_codsis,
											  c63_conta,
											  fc_saltessaldo(k13_reduz,'".$datai."','".$dataf."',null," . db_getsession("DB_instit") . ")
										 from saltes
									    inner join conplanoexe   on k13_reduz   = c62_reduz
										                        and c62_anousu  = ".db_getsession('DB_anousu')."
 										inner join conplanoreduz on c61_anousu  = c62_anousu 
										                        and c61_reduz   = c62_reduz 
																and c61_instit  = " . db_getsession("DB_instit") . "
	            						inner join conplano      on c60_codcon  = c61_codcon 
										                        and c60_anousu  = c61_anousu
	             						left  join conplanoconta on c60_codcon  = c63_codcon 
										                        and c63_anousu =c60_anousu ";
if($conta > 0) {
	$sql2 .= " where c61_reduz = $conta ";
}	             

/*
 *  Sql que retornar as informações para o relatório
 * 
 */
$sql3 ="select k13_reduz,
               k13_descr,
               c60_estrut,
               c60_codsis,
	           c63_conta,
	           substr(fc_saltessaldo,2,13)::float8 as anterior,
	           substr(fc_saltessaldo,15,13)::float8 as debitado ,
	           substr(fc_saltessaldo,28,13)::float8 as creditado,
	           substr(fc_saltessaldo,41,13)::float8 as atual
	      from w_contasmovimento ";
// verifica se é pra selecionar somente as contas com movimeto
if ($somente_contas_com_movimento=='s'){
   $sql3 .=" where ( substr(fc_saltessaldo,15,13)::float8 > 0 or substr(fc_saltessaldo,28,13)::float8 > 0)  ";
}
$sql3 .= " order by substr(k13_descr,1,3),k13_reduz ";

/*
echo " Begin; ";
echo $sql1."<br><br>";
echo $sql2.";<br><br>";
echo $sql3.";<br><br>";
exit;
*/

pg_query($sql1) or die ("Erro gerando tabela temporaria");
pg_query($sql2) or die ("Erro incluindo registros na tabela temporaria");
$resultcontasmovimento = pg_query($sql3);
if(pg_numrows($resultcontasmovimento) == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados neste periodo.');
}

/*
if($conta <= 0) {
	$vet_contas = array();

	for($i=0; $i < pg_numrows($resultcontasmovimento); $i++) {
		db_fieldsmemory($resultcontasmovimento,$i);
		$vet_contas[$i][1] = $c63_conta;
		$vet_contas[$i][2] = $c63_conta;
		$vet_contas[$i][3] = $c63_conta;
	}
}
$saldo_inicial = 0;
*/

/*
for ($i = 0; $i < pg_numrows($resultcontasmovimento); $i++){
      db_fieldsmemory($resultcontasmovimento, $i);

      $saldo_inicial += $anterior;
}
*/

db_fieldsmemory($resultcontasmovimento,0);
//db_criatabela($resultcontasmovimento);

$QuebraPagina = 10;
$total_deb    = 0;
$total_cre    = 0;
$pdf->SetFont('Arial','',7);

$pdf->SetTextColor(0,0,0);
$pdf->setfillcolor(235);

$StrPad1 = 20;
$StrPad2 = 26;

$pre           = 0;
$alt = 5 ; //altura da celula
$numero_pagina=0; // o contador de hp do pdf começa em 1

$numrows = pg_numrows($resultcontasmovimento);
for($linha=0;$linha<$numrows;$linha++){
    db_fieldsmemory($resultcontasmovimento,$linha);   
 
    // escreve a conta e a descrição + saldo inicial
    $pdf->Cell(120,$alt,"CONTA: $k13_reduz - $k13_descr ",'B',0,"L",0);
    $pdf->Cell(30,$alt,"SALDO ANTERIOR",'B',0,"L",0);

    // para contas bancárias, saldo positivo = debito, negativos indica debito
    if ($anterior > 0 ){
        $pdf->Cell(20,$alt,db_formatar($anterior,'f'),'B',0,"R",0);
	$pdf->Cell(20,$alt,'','B',0,"R",0);
    } else {   
        $pdf->Cell(20,$alt,'','B',0,"R",0);
        $pdf->Cell(20,$alt,db_formatar($anterior,'f'),'B',0,"R",0);
    }	   
    $pdf->Ln();
    
    // imprime head interno, se for primeira pagina somente
    // if ($numero_pagina != $pdf->PageNo()){

        $pdf->Cell(15,$alt,'DATA',0,0,"C",0);	      
        $pdf->Cell(40,$alt,'HISTORICO',0,0,"L",0);
        $pdf->Cell(20,$alt,"CHEQUE",0,0,"L",0);
        $pdf->Cell(10,$alt,"CAIXA",0,0,"L",0);
        $pdf->Cell(65,$alt,'CREDOR',0,0,"L",0);     
        $pdf->Cell(20,$alt,'DEBITO',0,0,"R",0);
        $pdf->Cell(20,$alt,'CREDITO',0,0,"R",0);        	
        $pdf->Ln();            
	$numero_pagina = $pdf->PageNo();
    // }

    // lista movimentos da conta

if($imprime_analitico=='s'){
    
    $sql = "
select * from (

select corrente.k12_id as caixa,
       coremp.k12_data as data,
       0 as valor_debito,
       sum(corrente.k12_valor) as valor_credito,
       ('Pagto Empenho ('||to_char(count(*),'99')||')')::text as tipo_movimentacao,
       0 as receita,
       corhist.k12_histcor::text as historico,
       coremp.k12_cheque::text as cheque,
       'Pagamento com Cheque'::text  as credor
from coremp  
     inner join corrente on coremp.k12_id = corrente.k12_id and 
                            coremp.k12_data = corrente.k12_data and 
                            coremp.k12_autent = corrente.k12_autent 
     inner join empempenho on e60_numemp = k12_empen 
     inner join cgm on z01_numcgm = e60_numcgm 
     left join corhist on  corhist.k12_id     = corrente.k12_id    and
                           corhist.k12_data   = corrente.k12_data  and 
	   	                   corhist.k12_autent = corrente.k12_autent                    					 
where corrente.k12_conta = $k13_reduz  and
	  corrente.k12_data between '".$datai."'  and '".$dataf."'  and
	  corrente.k12_instit = ".db_getsession("DB_instit")."
  and k12_cheque <> 0
group by corrente.k12_id,coremp.k12_data,corhist.k12_histcor,coremp.k12_cheque 

) as x

union all

select  * from (
select
           corrente.k12_id as caixa,
           corrente.k12_data as data,
		   0    as valor_debito,
		   corrente.k12_valor as valor_credito,
	       ('Pgto. Emp. '||e60_codemp||'/'||e60_anousu)::text as tipo_movimentacao,
           0 as receita,
		   corhist.k12_histcor::text as historico,
		   coremp.k12_cheque::text as cheque,
		   z01_nome::text as credor
from coremp  
     inner join corrente on coremp.k12_id = corrente.k12_id and 
                            coremp.k12_data = corrente.k12_data and 
                            coremp.k12_autent = corrente.k12_autent 
     inner join empempenho on e60_numemp = k12_empen 
     inner join cgm on z01_numcgm = e60_numcgm 
     left join corhist on  corhist.k12_id     = corrente.k12_id    and
                           corhist.k12_data   = corrente.k12_data  and 
	   	                   corhist.k12_autent = corrente.k12_autent                    					 

where corrente.k12_conta = $k13_reduz  and
	  corrente.k12_data between '".$datai."'  and '".$dataf."'  and
	  corrente.k12_instit = ".db_getsession("DB_instit")."
and k12_cheque = 0
) as x

";



$sql  .= "

union all

select 
   caixa,
   data,
   valor_debito,
   valor_credito,
   tipo_movimentacao,
   receita,
   historico,
   cheque,
   credor
   from ( 
         select  caixa,
                 data,
                 sum(valor_debito) as valor_debito,
                 valor_credito,
                 tipo_movimentacao::text,
                 receita,
                 historico::text,
                 cheque::text,
                 credor::text   
     from (
           select  
	          corrente.k12_id as caixa,
                  corrente.k12_data as data,
                  sum(cornump.k12_valor) as valor_debito,
                  0 as valor_credito,
                  case when k12_numpre = 0 then 'Baixa de Banco' else 'Recibo Pagto' end::text as tipo_movimentacao,
                  0 as receita,
		          case when k12_numpre = 0 then 'Data Retorno: '||to_char(dtretorno,'DD-MM-YYYY')||'    Data Arquivo: '||to_char(dtarquivo,'DD-MM-YYYY') else 'Historico:'||coalesce(corhist.k12_histcor,'.')end::text as historico,
                  ''::text as cheque,
                  case when k12_numpre = 0 then 'Arquivo:'||disarq.arqret else  (select z01_nome
                   from arrepaga
                        inner join cgm on z01_numcgm = k00_numcgm
                   where k00_numpre=cornump.k12_numpre 
                   limit 1 
	              ) end::text as credor  
           from corrente
                inner join cornump on cornump.k12_id     = corrente.k12_id    and
                                      cornump.k12_data   = corrente.k12_data   and
                                      cornump.k12_autent = corrente.k12_autent                   
                left join corhist on   corhist.k12_id     = corrente.k12_id    and
                                       corhist.k12_data   = corrente.k12_data  and 
                                       corhist.k12_autent = corrente.k12_autent 
                left join corcla on    corcla.k12_id     = corrente.k12_id    and
                                       corcla.k12_data   = corrente.k12_data  and 
                                       corcla.k12_autent = corrente.k12_autent
                left join discla on    discla.codcla     = corcla.k12_codcla
                left join disarq on    disarq.codret     = discla.codret 
                
	     where corrente.k12_conta = $k13_reduz  and
	           (corrente.k12_data between '".$datai."'  and '".$dataf."')  and
		   corrente.k12_instit = ".db_getsession("DB_instit")."  
             group by corrente.k12_id,tipo_movimentacao,cornump.k12_numpre,corhist.k12_histcor,disarq.dtarquivo,disarq.dtretorno,corrente.k12_data,valor_credito,receita,cheque,credor
     ) as x   
       group by caixa,
                data,
                valor_credito,
                tipo_movimentacao,
                historico,
                receita,
                cheque,
                credor   
 ) as xx

";








}else{    
    $sql = "  /* empenhos- despesa orçamentaria */
              select
           corrente.k12_id as caixa,
           corrente.k12_data as data,
		   0                  as valor_debito,
		   corrente.k12_valor as valor_credito,
	       'Pgto. Emp. '||e60_codemp||'/'||e60_anousu::text||' OP: '||coremp.k12_codord::text as tipo_movimentacao,
           0 as receita,
		   corhist.k12_histcor::text as historico,
		   coremp.k12_cheque::text as cheque,
		   z01_nome::text as credor
             from corrente
                   inner join coremp on coremp.k12_id     = corrente.k12_id     and
		                         coremp.k12_data   = corrente.k12_data   and
				         coremp.k12_autent = corrente.k12_autent                   
                   inner join empempenho on e60_numemp = coremp.k12_empen
		   inner join cgm on z01_numcgm = e60_numcgm		   

		   /*
		    se habilitar o left abaixo e o empenho tiver mais de um cheque 
		    os registros ficam duplicados
		   left join empord on e82_codord = coremp.k12_codord
		   left join empageconfche on e91_codcheque = e82_codmov
		   												and e91_ativo is true
		   */
		  
	           left join corhist on  corhist.k12_id     = corrente.k12_id    and
		                         corhist.k12_data   = corrente.k12_data  and 
					 corhist.k12_autent = corrente.k12_autent                    					 
	     where corrente.k12_conta = $k13_reduz  and
	           corrente.k12_data between '".$datai."'  and '".$dataf."'  and
		   corrente.k12_instit = ".db_getsession("DB_instit")."

             /* receitas */
        ";


     $sql .= "   	     
             union all

	     select 
           caixa,
		   data,
		   valor_debito,
		   valor_credito,
		   tipo_movimentacao,
		   receita,
		   historico,
		   cheque,
		   credor
	     from ( 
	     select 
	           caixa,
		   data,
		   sum(valor_debito) as valor_debito,
		   valor_credito,
		   tipo_movimentacao::text,
		   receita,
		   historico::text,
		   cheque::text,
		   credor::text		   
	     from (
             select
	           corrente.k12_id as caixa,
                   corrente.k12_data as data,
		   cornump.k12_valor as valor_debito,
		   0 as valor_credito,
	           ('Recibo '||k12_numpre||'-'||k12_numpar)::text|| 
               case when e20_pagordem is not null then '  OP:'||e20_pagordem else '' end 
	            as tipo_movimentacao,
		   cornump.k12_receit as receita,
		   ('RECEITA: '||tabrec.k02_drecei||',Historico:'||coalesce(corhist.k12_histcor,'.'))::text as historico,
		   null::text as cheque,
		   (select z01_nome::text
		    from arrepaga
		        inner join cgm on z01_numcgm = k00_numcgm
		    where k00_numpre=cornump.k12_numpre 	
		    limit 1 ) as credor  
             from corrente
                   inner join cornump on cornump.k12_id     = corrente.k12_id     and
		                       cornump.k12_data   = corrente.k12_data   and
				       cornump.k12_autent = corrente.k12_autent                   
                   inner join tabrec on tabrec.k02_codigo = cornump.k12_receit
              left join corgrupocorrente on corrente.k12_id    = k105_id      
                                         and corrente.k12_autent = k105_autent and corrente.k12_data = k105_data
              left join retencaocorgrupocorrente     on e47_corgrupocorrente  = k105_sequencial
              left join retencaoreceitas             on e47_retencaoreceita   = e23_sequencial 
              left join retencaopagordem             on e23_retencaopagordem  = e20_sequencial     
		   /*
		   left  join arrenumcgm on k00_numpre = cornump.k12_numpre 
                   left join cgm on k00_numcgm = z01_numcgm					 
                   */  
	           left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                          corhist.k12_data   = corrente.k12_data  and 
					  corhist.k12_autent = corrente.k12_autent 
	     where corrente.k12_conta = $k13_reduz  and
	           (corrente.k12_data between '".$datai."'  and '".$dataf."')  and
		   corrente.k12_instit = ".db_getsession("DB_instit")."  

              ) as x   
		group by 
		           caixa,
			   data,
			   valor_credito,
			   tipo_movimentacao,
			   historico,
			   receita,
			   cheque,
			   credor		   
             ) as xx
        ";


}



      $sql .= "		                
	     union all	     
	     /* transferencias a debito - entradas*/
	     select 
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		   corrente.k12_valor as valor_debito,
		   0 as valor_credito,
		   'Slip '||k12_codigo::text as tipo_movimentacao,
		   0 as receita,
		   slip.k17_texto::text as historico,
		   e91_cheque::text as cheque,
		   z01_nome::text as credor
	     from corlanc
	           inner join corrente on corrente.k12_id  = corlanc.k12_id    and
		                          corrente.k12_data  = corlanc.k12_data  and
					  corrente.k12_autent = corlanc.k12_autent

           inner join slip on slip.k17_codigo = corlanc.k12_codigo
		   left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		   left join cgm on slipnum.k17_numcgm = z01_numcgm
	           
                  		   
		   left join corconf on corconf.k12_id = corlanc.k12_id 				and
		                        corconf.k12_data = corlanc.k12_data 		and
														corconf.k12_autent = corlanc.k12_autent and
														corconf.k12_ativo is true
                   left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov
																						and corconf.k12_ativo is true
																						and empageconfche.e91_ativo is true				                    
		    
	           left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                          corhist.k12_data   = corrente.k12_data  and 
					  corhist.k12_autent = corrente.k12_autent 
	     where corlanc.k12_conta = $k13_reduz  and
	           corlanc.k12_data between '".$datai."'  and '".$dataf."'  
	      
	     union all 

";

if($imprime_analitico=='s'){


$sql .= "
	     /* transferencias a credito - saidas */

	     select * from (
         select 
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		       0                  as valor_debito,
		       sum(corrente.k12_valor) as valor_credito,
		       'Pagto. Slip '::text as tipo_movimentacao,
		       0 as receita,
		       ''::text as historico,
		       e91_cheque::text as cheque,
		       'Pagamento Slip'::text as credor

	     from corrente
	          inner join corlanc on  corrente.k12_id     = corlanc.k12_id    and
		                             corrente.k12_data   = corlanc.k12_data  and
				                     corrente.k12_autent = corlanc.k12_autent
			  inner join slip on slip.k17_codigo = corlanc.k12_codigo
		      left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		      left join cgm on slipnum.k17_numcgm = z01_numcgm
		      left join corconf on corconf.k12_id = corlanc.k12_id and
		                           corconf.k12_data = corlanc.k12_data and
				                   		 corconf.k12_autent = corlanc.k12_autent and
				                   		 corconf.k12_ativo is true
              left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov and 
              													 corconf.k12_ativo is true
              													 and empageconfche.e91_ativo is true		    
              left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                             corhist.k12_data   = corrente.k12_data  and 
				                     corhist.k12_autent = corrente.k12_autent 
	     where corrente.k12_conta = $k13_reduz  and
	           corrente.k12_data between '".$datai."'  and '".$dataf."'
           and e91_cheque is not null
         group by 	     
	           corrente.k12_id ,
	           corlanc.k12_data,
               e91_cheque
         ) as x
         union all
         
         select * from (
	     select 
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		   0                  as valor_debito,
		   corrente.k12_valor as valor_credito,
		   'Slip '||k12_codigo::text as tipo_movimentacao,
		   0 as receita,
		   slip.k17_texto::text as historico,
		   e91_cheque::text as cheque,
		   z01_nome::text as credor
	     from corrente
	           inner join corlanc on  corrente.k12_id     = corlanc.k12_id    and
		                          corrente.k12_data   = corlanc.k12_data  and
					  corrente.k12_autent = corlanc.k12_autent
					  
                   inner join slip on slip.k17_codigo = corlanc.k12_codigo
		   left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		   left join cgm on slipnum.k17_numcgm = z01_numcgm
		   
                   left join corconf on corconf.k12_id = corlanc.k12_id 				and
		                        						corconf.k12_data = corlanc.k12_data 		and
																				corconf.k12_autent = corlanc.k12_autent and
																				corconf.k12_ativo is true
                   left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov and 	
                   														corconf.k12_ativo is true
                   														and empageconfche.e91_ativo is true																	                 

	           left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                          corhist.k12_data   = corrente.k12_data  and 
					  corhist.k12_autent = corrente.k12_autent 
	     where corrente.k12_conta = $k13_reduz  and
	           corrente.k12_data between '".$datai."'  and '".$dataf."'  
           and e91_cheque is null

         ) as x 

	     order by data, caixa, cheque
	     
         
           ";
       
}else{


$sql .= "

	     select 
	           corrente.k12_id as caixa,
	           corlanc.k12_data as data,
		   0                  as valor_debito,
		   corrente.k12_valor as valor_credito,
		   'Slip '||k12_codigo::text as tipo_movimentacao,
		   0 as receita,
		   slip.k17_texto::text as historico,
		   e91_cheque::text as cheque,
		   z01_nome::text as credor
	     from corrente
	           inner join corlanc on  corrente.k12_id     = corlanc.k12_id    and
		                          corrente.k12_data   = corlanc.k12_data  and
					  corrente.k12_autent = corlanc.k12_autent
					  
                   inner join slip on slip.k17_codigo = corlanc.k12_codigo
		   left join slipnum on slipnum.k17_codigo = slip.k17_codigo
		   left join cgm on slipnum.k17_numcgm = z01_numcgm
		   
                   left join 	corconf on corconf.k12_id = corlanc.k12_id 	and
		                         	corconf.k12_data = corlanc.k12_data 				and
															corconf.k12_autent = corlanc.k12_autent 		and
															corconf.k12_ativo is true
                   left join empageconfche on empageconfche.e91_codcheque = corconf.k12_codmov and 
                   														corconf.k12_ativo is true                
																							and empageconfche.e91_ativo is true	
	           left join corhist on   corhist.k12_id     = corrente.k12_id    and
		                          corhist.k12_data   = corrente.k12_data  and 
					  corhist.k12_autent = corrente.k12_autent 
	     where corrente.k12_conta = $k13_reduz  and
	           corrente.k12_data between '".$datai."'  and '".$dataf."'  
	
	     order by data, caixa, cheque
	     
         
           ";
       

}
       
//	   echo $sql; exit;
    $resmovimentacao = pg_exec($sql);    
    $quebra_data = '';
    $saldo_dia_debito  = 0;
    $saldo_dia_credito = 0;
    
    if (pg_numrows($resmovimentacao)>0){         
         for  ($i=0;$i < pg_numrows($resmovimentacao);$i++){ 
	      db_fieldsmemory($resmovimentacao,$i);

	      // controla quebra de saldo por dia 
	      if ($quebra_data!=$data && $quebra_data!=''  && $totalizador_diario=='s'){
                   // 
                   $pdf->Cell(20,$alt,"",0,0,"L",0);
		   $pdf->Cell(130,$alt,'TOTAL DIA',0,0,"L",0,'','.'); 
		   $pdf->Cell(20,$alt,db_formatar($saldo_dia_debito,'f' ),0,0,"R",0);
                   $pdf->Cell(20,$alt,db_formatar($saldo_dia_credito,'f'),0,0,"R",0);        	
		   $pdf->Ln();
		   // calcula saldo a debito ou credito
		   $saldo_dia_final_debito  = 0;
		   $saldo_dia_final_credito = 0;
		   if ($saldo_dia_debito > $saldo_dia_credito)
		      $saldo_dia_final_debito   = $saldo_dia_debito  - $saldo_dia_credito;
		   else
		      $saldo_dia_final_credito  = $saldo_dia_credito - $saldo_dia_debito;
		   
                   $pdf->Cell(20,$alt,"",0,0,"L",0);
		   $pdf->Cell(130,$alt,'SALDO DO DIA',0,0,"L",0,'','.'); 
		   if ($saldo_dia_debito > $saldo_dia_credito){
		       $pdf->Cell(20,$alt,db_formatar($saldo_dia_final_debito,'f' ),0,0,"R",0);
		   } else {   
		       $pdf->Cell(20,$alt,'',0,0,"R",0);		       
                       $pdf->Cell(20,$alt,db_formatar($saldo_dia_final_credito,'f'),0,0,"R",0);        	
                   }		       
		   $pdf->Ln();

		   $saldo_dia_debito  = 0;
		   $saldo_dia_credito = 0;
              }	

              $pdf->Cell(15,$alt,db_formatar($data,'d'),0,0,"L",0);	      
              $pdf->Cell(40,$alt,$tipo_movimentacao,0,0,"L",0);
	      if ($receita > 0){		 
		 // selecina reduzido da receita no plano de contas
		 
		 $sql = "
                    select c61_reduz
		    from taborc 
		       inner join orcreceita on o70_codrec=taborc.k02_codrec and o70_anousu=k02_anousu and o70_instit=".db_getsession("DB_instit")."
		       inner join conplanoreduz on c61_codcon=o70_codfon and c61_instit=o70_instit and c61_anousu=o70_anousu		      
		    where  k02_codigo = $receita
		       and k02_anousu = ".db_getsession("DB_anousu")."
		    union   
	            select c61_reduz
		    from tabplan 
		        inner join conplanoreduz on c61_reduz=k02_reduz and c61_instit=".db_getsession("DB_instit")." and c61_anousu=k02_anousu		      
		    where k02_codigo = $receita
		      and k02_anousu = ".db_getsession("DB_anousu")."		   
	               ";
		 $res_rec = pg_query($sql);
		 $c61_reduz ="";
		 if (pg_numrows($res_rec)>0){
		     db_fieldsmemory($res_rec,0);
                 } 
                 $pdf->Cell(20,$alt,"Rec $receita($c61_reduz)",0,0,"L",0);
	      } else {	
                 $pdf->Cell(20,$alt,$cheque,0,0,"L",0);
	      }	
	      $pdf->Cell(10,$alt,$caixa,0,0,"C",0);
              $pdf->Cell(65,$alt,$credor,0,0,"L",0);

	      if ($valor_debito ==0 &&  $valor_credito != 0 ){
                   $pdf->Cell(20,$alt,'',0,0,"R",0);
                   $pdf->Cell(20,$alt,db_formatar($valor_credito,'f'),0,0,"R",0);        	
	      } elseif ($valor_credito== 0 && $valor_debito != 0){	
		   $pdf->Cell(20,$alt,db_formatar($valor_debito,'f'),0,0,"R",0);        	
                   $pdf->Cell(20,$alt,'',0,0,"R",0);		
	      } else {
                   $pdf->Cell(20,$alt,db_formatar($valor_debito,'f'),0,0,"R",0);        	
                   $pdf->Cell(20,$alt,db_formatar($valor_credito,'f'),0,0,"R",0);
	      }	
              $pdf->Ln();            
	      // emite historico
	      if ($historico!=""  && $imprime_historico=='s' ){
              	$pdf->Cell(20,$alt,"",0,0,"L",0);
	      	$pdf->multicell(170,$alt,$historico,0,'L',0,0); 	  
              }     
	      // soma acumuladores diarios
              $saldo_dia_debito  += $valor_debito;
	      $saldo_dia_credito += $valor_credito;

              $quebra_data = $data;

	      // imprime head interno, se for primeira pagina somente	      
	      if ($pdf->gety() > $pdf->h - 50){
	           // if ($numero_pagina != $pdf->PageNo()){
		   $pdf->addPage();

                   // escreve a conta e a descrição + saldo inicial
                   $pdf->Cell(120,$alt,"CONTA: $k13_reduz - $k13_descr ",'B',1,"L",0);

	           $pdf->Cell(15,$alt,'DATA',0,0,"C",0);	      
		   $pdf->Cell(40,$alt,'HISTORICO',0,0,"L",0);
		   $pdf->Cell(20,$alt,"CHEQUE",0,0,"L",0);
		   $pdf->Cell(10,$alt,"CAIXA",0,0,"L",0);
	           $pdf->Cell(65,$alt,'CREDOR',0,0,"L",0);     
		   $pdf->Cell(20,$alt,'DEBITO',0,0,"R",0);
		   $pdf->Cell(20,$alt,'CREDITO',0,0,"R",0);        	
	           $pdf->Ln();            
		   $numero_pagina = $pdf->PageNo();
	      }
             



         }
    }


    if ($totalizador_diario=='s'){
                   // 
                   $pdf->Cell(20,$alt,"",0,0,"L",0);
		   $pdf->Cell(130,$alt,'TOTAL DIA',0,0,"L",0,'','.'); 
		   $pdf->Cell(20,$alt,db_formatar($saldo_dia_debito,'f' ),0,0,"R",0);
                   $pdf->Cell(20,$alt,db_formatar($saldo_dia_credito,'f'),0,0,"R",0);        	
		   $pdf->Ln();
		   // calcula saldo a debito ou credito
		   $saldo_dia_final_debito  = 0;
		   $saldo_dia_final_credito = 0;
		   if ($saldo_dia_debito > $saldo_dia_credito)
		      $saldo_dia_final_debito   = $saldo_dia_debito  - $saldo_dia_credito;
		   else
		      $saldo_dia_final_credito  = $saldo_dia_credito - $saldo_dia_debito;
		   
                   $pdf->Cell(20,$alt,"",0,0,"L",0);
		   $pdf->Cell(130,$alt,'SALDO DO DIA',0,0,"L",0,'','.'); 
		   if ($saldo_dia_debito > $saldo_dia_credito){
		       $pdf->Cell(20,$alt,db_formatar($saldo_dia_final_debito,'f' ),0,0,"R",0);
		   } else {   
		       $pdf->Cell(20,$alt,'',0,0,"R",0);		       
                       $pdf->Cell(20,$alt,db_formatar($saldo_dia_final_credito,'f'),0,0,"R",0);        	
                   }		       
		   $pdf->Ln();


    }
 

    
    // apos listar os movimentos lista saldo final+ debito + credito  
    $pdf->Cell(20,$alt,"  ",'T',0,"L",0);
    $pdf->Cell(130,$alt,"MOVIMENTAÇÃO ",'T',0,"L",0);
    $pdf->Cell(20,$alt,db_formatar($debitado,'f'),'T',0,"R",0);
    $pdf->Cell(20,$alt,db_formatar($creditado,'f'),'T',0,"R",0);
    $pdf->Ln();

    $pdf->Cell(20,$alt,"",0,0,"L",0);
    $pdf->Cell(130,$alt,"SALDO FINAL",0,0,"L",0);
    if ($atual > 0 ){
        $pdf->Cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
	$pdf->Cell(20,$alt,'',0,0,"R",0);
    } else {   
        $pdf->Cell(20,$alt,'',0,0,"R",0);
        $pdf->Cell(20,$alt,db_formatar($atual,'f'),0,0,"R",0);
    }	   
    
    $pdf->Ln();
    $pdf->Ln();

    
} 

$pdf->Output();

?>