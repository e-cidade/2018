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
 
if (!isset($arqinclude)){ // se este arquivo não esta incluido por outro
 set_time_limit(0);
 include ("fpdf151/pdf.php");
 include ("fpdf151/assinatura.php");
 include ("libs/db_sql.php");
 include ("libs/db_libcontabilidade.php");
 include ("libs/db_liborcamento.php");
 include ("libs/db_libtxt.php");
 include ("dbforms/db_funcoes.php");
 include ("classes/db_conrelinfo_classe.php");
 include ("classes/db_orcparamrel_classe.php");
 
 //$cldesdobramento = new cl_desdobramento;
 
 $classinatura = new cl_assinatura;
 $orcparamrel = new cl_orcparamrel;
 $clconrelinfo = new cl_conrelinfo;
 
 parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
 
 $anousu  = db_getsession("DB_anousu");
 $dt = data_periodo($anousu,$periodo); // no dbforms/db_funcoes.php
 $dt_ini= $dt[0]; // data inicial do período
 $dt_fin= $dt[1]; // data final do período
 $texto = $dt['texto'];
 $txtper = $dt['periodo'];

}

//  tela do relatorio
$recita1[0] = "RECEITA LÍQUIDA DE IMPOSTOS E TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS (I)";
$recita1[1] = "  Impostos";
$recita1[2] = "  Multas, Juros de Mora e Divida Ativa dos Impostos";
$recita1[3] = "  Receita de Transferencias Constitucionais e Legais";
$recita1[4] = "    Da União";
$recita1[5] = "    Do Estado";
$recita1[6] = "TRANSFERENCIAS DE RECURSOS DO SISTEMA ÚNICO DE SAÚDE - SUS (II)";
$recita1[7] = "  Da União para o Município";
$recita1[8] = "  Do Estado para o Município";
$recita1[9] = "  Demais Municípios para o Município";
$recita1[10] = "  Outras Receitas do SUS";
$recita1[11] = "RECEITAS DE OPERAÇÕES DE CRÉDITO VNCULADAS À SAÚDE (III)";
$recita1[12] = "OUTRAS RECEITAS ORÇAMENTÁRIAS";
$recita1[13] = "(-) DEDUÇÃO PARA O FUNDEF";

$despesa[0] = "DESPESAS CORRENTES";
$despesa[1] = "  Pessoal e Encargos Sociais";
$despesa[2] = "  Juros e Encargos da Dívida";
$despesa[3] = "  Outras Despesas Correntes";
$despesa[4] = "DESPESAS DE CAPITAL";
$despesa[5] = "  Investimentos";
$despesa[6] = "  Inversões Financeiras";
$despesa[7] = "  Amortização da Dívida";

$despesa2[0] = "DESPESAS COM SAÚDE";
$despesa2[1] = "(-)DESPESAS COM INATIVOS E PENCIONISTAS";
$despesa2[2] = "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE";
$despesa2[3] = "  Recursos de Transferênncias do Sistema Único de Saúde - SUS";
$despesa2[4] = "  Recursos de Operações de Crédito";
$despesa2[5] = "  Outros Recursos";
$despesa2[6] = "(-)RP INSCRITOS NO EXERCÍCIO EM DISPONIBILIDADE FINANCEIRA VINCULADA DE ";
$despesa2[7] = "RECURSOS PRÓPRIO";

$subfuncao[0] = "Atenção Básica";
$subfuncao[1] = "Assistência Hospitalar e Ambulatorial";
$subfuncao[2] = "Suporte Profilático e Terapêutico";
$subfuncao[3] = "Vigilância Sanitária";
$subfuncao[4] = "Vigilância Epidemiológica";
$subfuncao[5] = "Alimentação e Nutrição ";
$subfuncao[6] = "Outras Subfunções";

$contas[0] = "(-)DESPESAS COM INATIVOS E PENCIONISTAS";
$contas[1] = "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE";
$contas[2] = "  Recursos de Transferênncias do Sistema Único de Saúde - SUS";
$contas[3] = "  Recursos de Operações de Crédito";
$contas[4] = "  Outros Recursos";


///////////////////////////////////////////////////////////////////
// 13 linhas de receita
$rec['0'] = $orcparamrel->sql_parametro('10', '0'); // coluna somada
$rec['1'] = $orcparamrel->sql_parametro('10', '1');
$rec['2'] = $orcparamrel->sql_parametro('10', '2');
$rec['3'] = $orcparamrel->sql_parametro('10', '3');
$rec['4'] = $orcparamrel->sql_parametro('10', '4');
$rec['5'] = $orcparamrel->sql_parametro('10', '5');
$rec['6'] = $orcparamrel->sql_parametro('10', '6'); // coluna somada
$rec['7'] = $orcparamrel->sql_parametro('10', '7');
$rec['8'] = $orcparamrel->sql_parametro('10', '8');
$rec['9'] = $orcparamrel->sql_parametro('10', '9');
$rec[10] = $orcparamrel->sql_parametro('10', '10');
$rec[11] = $orcparamrel->sql_parametro('10', '11');
$rec[12] = $orcparamrel->sql_parametro('10', '12');
$rec[13] = $orcparamrel->sql_parametro('10', '13');


$desp['1']['estrut']      = $orcparamrel->sql_parametro('10', '14');
$desp['1']['exclusao']  = $orcparamrel->sql_parametro('10', '14','t');
$desp['1']['nivel']   = $orcparamrel->sql_nivel('10', '14');
$desp['1']['subfunc'] = $orcparamrel->sql_subfunc('10', '14');
$desp['1']['recurso'] = $orcparamrel->sql_recurso('10', '14');

$desp['2']['estrut']  	 = $orcparamrel->sql_parametro('10', '16');
$desp['2']['exclusao']  = $orcparamrel->sql_parametro('10', '16','t');
$desp['2']['nivel']     	 = $orcparamrel->sql_nivel('10', '16');
$desp['2']['subfunc'] = $orcparamrel->sql_subfunc('10', '16');
$desp['2']['recurso'] = $orcparamrel->sql_recurso('10', '16');

$desp['3']['estrut']  	 = $orcparamrel->sql_parametro('10', '17');
$desp['3']['exclusao']	 = $orcparamrel->sql_parametro('10', '17','t');
$desp['3']['nivel']     	 = $orcparamrel->sql_nivel('10', '17');
$desp['3']['subfunc'] = $orcparamrel->sql_subfunc('10', '17');
$desp['3']['recurso'] = $orcparamrel->sql_recurso('10', '17');

$desp['4']['estrut']  	 = $orcparamrel->sql_parametro('10', '19');
$desp['4']['exclusao']	 = $orcparamrel->sql_parametro('10', '19','t');
$desp['4']['nivel']     	 = $orcparamrel->sql_nivel('10', '19');
$desp['4']['subfunc'] = $orcparamrel->sql_subfunc('10', '19');
$desp['4']['recurso'] = $orcparamrel->sql_recurso('10', '19');

$desp['5']['estrut']  	 = $orcparamrel->sql_parametro('10', '20');
$desp['5']['exclusao']  = $orcparamrel->sql_parametro('10', '20','t');
$desp['5']['nivel']     	 = $orcparamrel->sql_nivel('10', '20');
$desp['5']['subfunc'] = $orcparamrel->sql_subfunc('10', '20');
$desp['5']['recurso'] = $orcparamrel->sql_recurso('10', '20');

$desp['6']['estrut']  	 = $orcparamrel->sql_parametro('10', '21');
$desp['6']['exclusao']	 = $orcparamrel->sql_parametro('10', '21','t');
$desp['6']['nivel']     	 = $orcparamrel->sql_nivel('10', '21');
$desp['6']['subfunc'] = $orcparamrel->sql_subfunc('10', '21');
$desp['6']['recurso'] = $orcparamrel->sql_recurso('10', '21');


$M_INTERFERENCIA['estrut'] = $orcparamrel->sql_parametro('10', '15'); // usado como interfenrecia de pessoal e encargos
$M_INTERFERENCIA['valor'] = 0;

// (-) Depesas proprias
$desp_p[1]['estrut']   = $orcparamrel->sql_parametro('10', '23');
$desp_p[1]['exclusao']   = $orcparamrel->sql_parametro('10', '23','t');
$desp_p[1]['nivel']      = $orcparamrel->sql_nivel('10', '23');
$desp_p[1]['subfunc'] = $orcparamrel->sql_subfunc('10', '23');
$desp_p[1]['recurso']  = $orcparamrel->sql_recurso('10', '23');

$desp_p[2]['estrut']    = $orcparamrel->sql_parametro('10', '24');
$desp_p[2]['exclusao']= $orcparamrel->sql_parametro('10', '24','t');
$desp_p[2]['nivel']       = $orcparamrel->sql_nivel('10', '24');
$desp_p[2]['subfunc']  = $orcparamrel->sql_subfunc('10', '24');
$desp_p[2]['recurso']  = $orcparamrel->sql_recurso('10', '24');

$desp_p[3]['estrut']    = $orcparamrel->sql_parametro('10', '25');
$desp_p[3]['exclusao']= $orcparamrel->sql_parametro('10', '25','t');
$desp_p[3]['nivel']      = $orcparamrel->sql_nivel('10', '25');
$desp_p[3]['subfunc'] = $orcparamrel->sql_subfunc('10', '25');
$desp_p[3]['recurso']  = $orcparamrel->sql_recurso('10', '25');

$desp_p[4]['estrut']    = $orcparamrel->sql_parametro('10', '26');
$desp_p[4]['exclusao']    = $orcparamrel->sql_parametro('10', '26','t');
$desp_p[4]['nivel']      = $orcparamrel->sql_nivel('10', '26');
$desp_p[4]['subfunc'] = $orcparamrel->sql_subfunc('10', '26');
$desp_p[4]['recurso'] = $orcparamrel->sql_recurso('10', '26');

$desp_p[5]['estrut']   = $orcparamrel->sql_parametro('10', '27');
$desp_p[5]['exclusao']   = $orcparamrel->sql_parametro('10', '27','t');
$desp_p[5]['nivel']      = $orcparamrel->sql_nivel('10', '27');
$desp_p[5]['subfunc']= $orcparamrel->sql_subfunc('10', '27');
$desp_p[5]['recurso'] = $orcparamrel->sql_recurso('10', '27');

for ($linha=1;$linha<=5;$linha++){
	$desp_p[$linha]['previni']    =0;
	$desp_p[$linha]['prevatu']  = 0;
	$desp_p[$linha]['bimestre']=0;
}


// -------------------------------------------------------------------

$w_instit = str_replace('-', ', ', $db_selinstit);
$res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores('10', $w_instit));
$VARIAVEL_MINIMA = 0;
$VARIAVEL_APURADA = 0;
$VARIAVEL_COMPENSACAO = 0;
if ($clconrelinfo->numrows > 0) {
	for ($x = 0; $x < $clconrelinfo->numrows; $x ++) {
		db_fieldsmemory($res, $x);
		if ($c83_codigo == 248) {
			$VARIAVEL_MINIMA = $c83_informacao;
		}
		if ($c83_codigo == 249) {
			$VARIAVEL_APURADA = $c83_informacao;
		}
		if ($c83_codigo == 250) {
			$VARIAVEL_COMPENSACAO = $c83_informacao;
		}
	}
}

//////// RecordSets
$sele_work = ' o58_funcao=10 and o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
$result_despesa = db_dotacaosaldo(8,2, 3, true, $sele_work, $anousu, $dt_ini, $dt_fin);
// teste

$sele_work = ' o58_codigo = 40 and o58_funcao=99 and o58_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
$result_despesa_reserva = db_dotacaosaldo(8, 2, 4, true, $sele_work, $anousu, $dt_ini, $dt_fin);

$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result_rec = db_receitasaldo(11, 1, 3, true, $db_filtro, $anousu, $dt_ini, $dt_fin);
@ pg_exec("drop table work_receita");


// saldo dos rps inscritos e cancelados do mde e fundef
$m_rp[1]['subfunc']= $orcparamrel->sql_subfunc('10', '28');
$m_rp[1]['recurso'] = $orcparamrel->sql_recurso('10', '28');

$v_subfunc = '0';
$v_codigo  = '0';
$sp= '';
foreach($m_rp[1]['subfunc'] as $registro){
   $v_subfunc .= $sp.$registro;
   $sp =',';
}
$sp='';
foreach($m_rp[1]['recurso'] as $registro){
   $v_codigo .= $sp.$registro;
   $sp =',';
}
$w_instit = '  in ('.str_replace('-', ', ', $db_selinstit).') ';
$result_rpsaldo = db_rpsaldo($anousu, $w_instit, $anousu.'-01-01',$dt_fin," o58_subfuncao in ($v_subfunc) and o58_codigo in($v_codigo) ");

// db_inicio_transacao();
$result_func = db_dotacaosaldo(4, 3, 3, true, ' o58_funcao=10 and o58_instit in ('.str_replace('-', ', ', $db_selinstit).')', $anousu, $dt_ini, $dt_fin);

$M_INTERFERENCIA['valor'] = 0;
@pg_exec("drop table work_pl");
$result_bal_mde = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
for($i=0;$i<pg_numrows($result_bal_mde);$i++){
  db_fieldsmemory($result_bal_mde,$i);  
  if (in_array($estrutural,$M_INTERFERENCIA['estrut'])){
      $M_INTERFERENCIA['valor'] += $saldo_final ;
  }   
}
$RESERVA_ASPS = 0;
for($i=0;$i<pg_numrows($result_despesa_reserva);$i++){
  db_fieldsmemory($result_despesa_reserva,$i);  
  $RESERVA_ASPS += $dot_ini;
}

//-------------------------------------------------RECEITAS-----------------------------------------

$total_rec_ini    = 0;
$total_rec_atu    = 0;
$total_rec_atebim = 0;
for ($i = 0; $i < 14; $i ++) {
	$receitas_previni[$i] = 0;
	$receitas_prevatu[$i] = 0;
	$receitas_atebime[$i] = 0;
	$receitas_nobimes[$i] = 0;
}
for ($p=0;$p<=5;$p++){
   for ($i = 0; $i < pg_numrows($result_rec); $i ++) {
		db_fieldsmemory($result_rec, $i);
		$estrutural = $o57_fonte;
		if (in_array($estrutural, $rec[$p])) {
			$receitas_previni[$p] += $saldo_inicial;
			$receitas_prevatu[$p] += $saldo_inicial_prevadic;
			$receitas_atebime[$p] += $saldo_arrecadado_acumulado;
			$receitas_nobimes[$p] += $saldo_arrecadado;
		}
   }
}
// totaliza linha "RECEITAS DE TRANSF CONSTIT E LEGAIS
$receitas_previni[3] = $receitas_previni[4] + $receitas_previni[5];
$receitas_prevatu[3] = $receitas_prevatu[4] + $receitas_prevatu[5];
$receitas_atebime[3] = $receitas_atebime[4] + $receitas_atebime[5];
$receitas_nobimes[3] = $receitas_nobimes[4] + $receitas_nobimes[5];

// totaliza linha "RECEITA LIQUIDA DE IMPOSTOS..."
$receitas_previni["0"] = $receitas_previni[1] + $receitas_previni[2] + $receitas_previni[3];
$receitas_prevatu["0"] = $receitas_prevatu[1] + $receitas_prevatu[2] + $receitas_prevatu[3];
$receitas_atebime["0"] = $receitas_atebime[1] + $receitas_atebime[2] + $receitas_atebime[3];
$receitas_nobimes["0"] = $receitas_nobimes[1] + $receitas_nobimes[2] + $receitas_nobimes[3];

for ($p=6;$p<=13;$p++){
   for ($i = 0; $i < pg_numrows($result_rec); $i ++) {
	db_fieldsmemory($result_rec, $i);
	$estrutural = $o57_fonte;
	if (in_array($estrutural, $rec[$p])) {
		$receitas_previni[$p] += $saldo_inicial;
		$receitas_prevatu[$p] += $saldo_inicial_prevadic;
		$receitas_atebime[$p] += $saldo_arrecadado_acumulado;
		$receitas_nobimes[$p] += $saldo_arrecadado;
	}
   }
}
// totaliza linha "TRANSFERENCIAS DE RECURSOS..."
for ($i = 7; $i < 11; $i ++) {
	$receitas_previni[6] += $receitas_previni[$i];
	$receitas_prevatu[6] += $receitas_prevatu[$i];
	$receitas_atebime[6] += $receitas_atebime[$i];
	$receitas_nobimes[6] += $receitas_nobimes[$i];
}
//------------------------------------------------- Despesas -----------------------------------------
for ($x=1;$x<=6;$x++){
	$desp[$x]['previni']    =0;
	$desp[$x]['prevatu']  = 0;
	$desp[$x]['bimestre']=0;
}

//db_criatabela($result_despesa);exit;
for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
	db_fieldsmemory($result_despesa, $i);
     	
     for ($linha=1;$linha<=6;$linha++){
     		
		$nivel      = $desp[$linha]['nivel'];

		$estrutural = $o58_elemento.'00';
    	$estrutural = substr($estrutural,0,$nivel);
    	$v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
	
		$v_subfuncao = $o58_subfuncao;
		$v_recurso     = $o58_codigo;
	 	
	    if (in_array($v_estrutural, $desp[$linha]['estrut'])){
				
		  	if ( count($desp[$linha]['subfunc'])==0  ||  in_array($v_subfuncao, $desp[$linha]['subfunc'])){
		
		    	    if (  count($desp[$linha]['recurso'])==0 ||   in_array($v_recurso, $desp[$linha]['recurso'])){

							$desp[$linha]['previni']     += $dot_ini; 
							$desp[$linha]['prevatu']   += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
							$desp[$linha]['bimestre'] += $liquidado_acumulado;  
		        				           		 	           
                	}// endif
		  	} //end if    
    	}// end if
    	
    	// exclusao de parametros
    	if (in_array($v_estrutural, $desp[$linha]['exclusao'])){
				
		  	if ( count($desp[$linha]['subfunc'])==0  ||  in_array($v_subfuncao, $desp[$linha]['subfunc'])){
		
		    	    if (  count($desp[$linha]['recurso'])==0 ||   in_array($v_recurso, $desp[$linha]['recurso'])){

							$desp[$linha]['previni']     -= $dot_ini; 
							$desp[$linha]['prevatu']   -= $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
							$desp[$linha]['bimestre'] -= $liquidado_acumulado;  
		        				           		 	           
                	}// endif
		  	} //end if    
    	}// end if
    	
    	
    	
    	
    	
     } //end for
 	  	  	  
}


// DESPESAS PROPRIAS COM AÇOES E SERV. PUBLICOS DE SAUDE

for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
	db_fieldsmemory($result_despesa, $i);
     	
     for ($linha=1;$linha<=5;$linha++){
     		
		$nivel      = $desp_p[$linha]['nivel'];

		$estrutural = $o58_elemento.'00';
    	$estrutural = substr($estrutural,0,$nivel);
    	$v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
	
		$v_subfuncao = $o58_subfuncao;
		$v_recurso     = $o58_codigo;
	 	
	    // if (count($desp_p[$linha]['estrut'])==0  ||  in_array($v_estrutural, $desp_p[$linha]['estrut'])){
	    // é necessario selecionar ao menos o estrutural
	    if ( in_array($v_estrutural, $desp_p[$linha]['estrut'])){
				
		  	if ( count($desp_p[$linha]['subfunc'])==0  ||  in_array($v_subfuncao, $desp_p[$linha]['subfunc'])){
		
		    	    if (  count($desp_p[$linha]['recurso'])==0 ||   in_array($v_recurso, $desp_p[$linha]['recurso'])){

							$desp_p[$linha]['previni']     += $dot_ini; 
							$desp_p[$linha]['prevatu']   += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
							$desp_p[$linha]['bimestre'] += $liquidado_acumulado;  
		        				           		 	           
                	}// endif
		  	} //end if    
    	}// end if
    	
    	// exclusao de parametros
    	if ( in_array($v_estrutural, $desp_p[$linha]['exclusao'])){
				
		  	if ( count($desp_p[$linha]['subfunc'])==0  ||  in_array($v_subfuncao, $desp_p[$linha]['subfunc'])){
		
		    	    if (  count($desp_p[$linha]['recurso'])==0 ||   in_array($v_recurso, $desp_p[$linha]['recurso'])){

							$desp_p[$linha]['previni']     -= $dot_ini; 
							$desp_p[$linha]['prevatu']   -= $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
							$desp_p[$linha]['bimestre'] -= $liquidado_acumulado;  
		        				           		 	           
                	}// endif
		  	} //end if    
    	}// end if
    	
    	
    	
    	
    	
    	
     } //end for			 	           	
 	  
}// end for

//------------------------------------funcao e subfuncao------------------------------------------------------------------------
$total_acum = 0;
$total_ini = 0;
$total_atu = 0;

$sub301_dotini = 0;
$sub301_atuali = 0;
$sub301_atebim = 0;

$sub304_dotini = 0;
$sub304_atuali = 0;
$sub304_atebim = 0;

$sub305_dotini = 0;
$sub305_atuali = 0;
$sub305_atebim = 0;

$sub301_dotini = 0;
$sub301_atuali = 0;
$sub301_atebim = 0;

$sub302_dotini = 0;
$sub302_atuali = 0;
$sub302_atebim = 0;

$sub303_dotini = 0;
$sub303_atuali = 0;
$sub303_atebim = 0;

$sub304_dotini = 0;
$sub304_atuali = 0;
$sub304_atebim = 0;

$sub305_dotini = 0;
$sub305_atuali = 0;
$sub305_atebim = 0;

$sub306_dotini = 0;
$sub306_atuali = 0;
$sub306_atebim = 0;

$subout_dotini = 0;
$subout_atuali = 0;
$subout_atebim = 0;

for ($i = 0; $i < pg_numrows($result_func); $i ++) {
	db_fieldsmemory($result_func, $i);
	/*
 	   $total_acum += $liquidado_acumulado;
 	    $total_ini += $dot_ini;
 	    $total_atu += $dot_ini + $suplementado;	
      */	    
	if ($o58_subfuncao == 301) {
   	        $sub301_dotini += $dot_ini + $RESERVA_ASPS;
	 	$sub301_atuali += $dot_ini + $RESERVA_ASPS + ($suplementado_acumulado - $reduzido_acumulado);
	 	$sub301_atebim += $liquidado_acumulado + $M_INTERFERENCIA['valor'];
		
                $total_acum += $liquidado_acumulado +  $M_INTERFERENCIA['valor'];
  	        $total_ini += $dot_ini + $RESERVA_ASPS;
 	        $total_atu += $dot_ini + $RESERVA_ASPS + ($suplementado_acumulado - $reduzido_acumulado);	
		continue;
	}
	if ($o58_subfuncao == 302) {
		$sub302_dotini += $dot_ini;
		$sub302_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
		$sub302_atebim += $liquidado_acumulado;

                $total_acum += $liquidado_acumulado ;
  	        $total_ini += $dot_ini; 
 	        $total_atu += $dot_ini  + ($suplementado_acumulado - $reduzido_acumulado);

		
		continue;
	}
	if ($o58_subfuncao == 303) {
		$sub303_dotini += $dot_ini;
		$sub303_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
		$sub303_atebim += $liquidado_acumulado;
		
		$total_acum += $liquidado_acumulado ;
  	        $total_ini += $dot_ini ;
 	        $total_atu += $dot_ini  + ($suplementado_acumulado - $reduzido_acumulado);	

		continue;
	}
	if ($o58_subfuncao == 304) {
		$sub304_dotini += $dot_ini;
		$sub304_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
		$sub304_atebim += $liquidado_acumulado;
  		$total_acum += $liquidado_acumulado ;
  	        $total_ini += $dot_ini ;
 	        $total_atu += $dot_ini  + ($suplementado_acumulado - $reduzido_acumulado);

		continue;
	}
	if ($o58_subfuncao == 305) {
		$sub305_dotini += $dot_ini;
		$sub305_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
		$sub305_atebim += $liquidado_acumulado;
		$total_acum += $liquidado_acumulado ;
  	        $total_ini += $dot_ini ;
 	        $total_atu += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);

		continue;
	}
	if ($o58_subfuncao == 306) {
		$sub306_dotini += $dot_ini;
		$sub306_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
		$sub306_atebim += $liquidado_acumulado;
		$total_acum += $liquidado_acumulado ;
  	        $total_ini += $dot_ini ;
 	        $total_atu += $dot_ini  + ($suplementado_acumulado - $reduzido_acumulado);

		continue;
	} 
	$subout_dotini += $dot_ini;
	$subout_atuali += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
	$subout_atebim += $liquidado_acumulado;
	
	$total_acum += $liquidado_acumulado ;
	$total_ini += $dot_ini ;
	$total_atu += $dot_ini  + ($suplementado_acumulado - $reduzido_acumulado);

}


/////////////////////////////////////////////////////////////////////////////////
$n1 = 5;
$n2 = 10;


 // end se incluido em outro arquivo
if (!isset($arqinclude)){  

 $xinstit = split("-", $db_selinstit);
 $resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
 $descr_inst = '';
 $xvirg = '';
 $flag_abrev = false;
 for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
 	db_fieldsmemory($resultinst, $xins);
  if (strlen(trim($nomeinstabrev)) > 0){
 	     $descr_inst .= $xvirg.$nomeinstabrev;
       $flag_abrev  = true;
  } else{
 	     $descr_inst .= $xvirg.$nomeinst;
  }

 	$xvirg = ', ';
 }

 if ($flag_abrev == false){
      if (strlen($descr_inst) > 42){
           $descr_inst = substr($descr_inst,0,100);
      }
 }

 $head1 = $descr_inst;
 $head2 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
 $head3 = "DEMONSTRATIVO DA RECEITA DE IMPOSTOS E DAS DESPESAS PRÓPRIAS COM SAÚDE";
 $head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
 $head5 = $texto.'/'.$anousu;
 
 $pdf = new PDF();
 $pdf->Open();
 $pdf->AliasNbPages();
 $pdf->setfillcolor(235);
 $pdf->addpage();
 $alt = 3;
 $pdf->setfont('arial', '', 6);

 // RECEITAS
 $pdf->setX(10);
 $pdf->cell(170, $alt, "ADCT, art. 77 - Anexo XVI", "B", 0, "L", 0);
 $pdf->cell(20, $alt, "R$ milhares", "B", 1, "R", 0);

 $pdf->cell(110, $alt, "", "T", 0, "R", 0);
 $pdf->cell(20, $alt, "PREVISÃO", "TRL", 0, "C", 0);
 $pdf->cell(20, $alt, "PREVISÃO", "TRL", 0, "C", 0);
 $pdf->cell(40, $alt, "RECEITAS RELIZADAS", "TL", 1, "C", 0);

 $pdf->cell(110, $alt, "RECEITAS", "B", 0, "C", 0);
 $pdf->cell(20, $alt, "INICIAL", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "ATUALIZADA (a)", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "Até o ".$txtper." (b)", "TBL", 0, "C", 0);
 $pdf->cell(20, $alt, "% (b/a)", "LTB", 1, "C", 0);
 $alt = 3;


 for ($i = 0; $i < 14; $i ++) {
	$pdf->cell(110, $alt, $recita1["$i"], "", 0, "L", 0);
	$pdf->cell(20, $alt, db_formatar($receitas_previni["$i"], 'f'), "L", 0, "R", 0);
	$pdf->cell(20, $alt, db_formatar($receitas_prevatu["$i"], 'f'), "L", 0, "R", 0);
	$pdf->cell(20, $alt, db_formatar($receitas_atebime["$i"], 'f'), "L", 0, "R", 0);
	if ($receitas_prevatu["$i"] != 0)
		$pdf->cell(20, $alt, db_formatar((($receitas_atebime["$i"] / $receitas_prevatu["$i"]) * 100), 'f'), "L", 1, "R", 0);
	else
		$pdf->cell(20, $alt, db_formatar(0, 'f'), "L", 1, "R", 0);
	   
 }
 $total_previni = $receitas_previni["0"] + $receitas_previni["6"] + $receitas_previni["11"] + $receitas_previni["12"] + $receitas_previni["13"];
 $total_prevatu = $receitas_prevatu["0"] + $receitas_prevatu["6"] + $receitas_prevatu["11"] + $receitas_prevatu["12"] + $receitas_prevatu["13"] ;
 $total_atebime = $receitas_atebime["0"] + $receitas_atebime["6"] + $receitas_atebime["11"] + $receitas_atebime["12"] + $receitas_atebime["13"] ;
 
 $total_nobimes = " - ";
 $pdf->cell(110, $alt, "TOTAL", "TB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($total_previni, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_prevatu, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_atebime, 'f'), "TBL", 0, "R", 0);
 if ($total_prevatu>0){
   $pdf->cell(20, $alt, db_formatar(($total_atebime / $total_prevatu)*100, 'f'), "TBL", 0, "R", 0);
 }else{
   $pdf->cell(20, $alt, db_formatar(0, 'f'), "TBL", 0, "R", 0);
 }
 // $pdf->cell(20, $alt, db_formatar($total_nobimes, 'f'), "LTB", 1, "R", 0);

 $total_I_atebim = $receitas_atebime["0"];

 // ------------------------  despesas -------------------------- 

 $pdf->ln(4);

 $pdf->cell(110, $alt, "DESPESA COM SAÚDE", "T", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(40, $alt, "DESPESAS LIQUIDADAS", "TL", 1, "C", 0);

 $pdf->cell(110, $alt, "(Por Grupo de Natureza da Despesa)", "B", 0, "C", 0);
 $pdf->cell(20, $alt, "INICIAL", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "ATUALIZADA (c)", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "Até o ".$txtper." (d)", "TBL", 0, "C", 0);
 $pdf->cell(20, $alt, "% (d/c)", "LTB", 1, "C", 0);

 $desp[1]['bimestre'] += $M_INTERFERENCIA['valor'];

 $pdf->cell(110, $alt, "DESPESAS CORRENTES", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['previni'] +$desp['2']['previni']+$desp['3']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['prevatu']+$desp['2']['prevatu']+$desp['3']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['bimestre']+$desp['2']['bimestre']+$desp['3']['bimestre'],'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['1']['bimestre']+$desp['2']['bimestre']+$desp['3']['bimestre'])*100/ ($desp['1']['prevatu']+$desp['2']['prevatu']+$desp['3']['prevatu']),'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[1], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['1']['bimestre'], 'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['1']['bimestre']) * 100 / $desp['1']['prevatu'], 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[2], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['2']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['2']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['2']['bimestre'], 'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['2']['bimestre']) * 100 / $desp['2']['prevatu'], 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[3], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['3']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['3']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['3']['bimestre'], 'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['3']['bimestre']) * 100 / $desp['3']['prevatu'], 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, "DESPESAS DE CAPITAL", "0", 0, "L", 0);
 $pdf->cell(20, $alt,db_formatar($desp['4']['previni']+$desp['5']['previni']+$desp['6']['previni'],'f')   ,"L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp['4']['prevatu']+$desp['5']['prevatu']+$desp['6']['prevatu'] ,'f')     , "L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp['4']['bimestre']+$desp['5']['bimestre']+$desp['6']['bimestre'],'f')   , "L", 0, "R", 0);
 @$pdf->cell(20, $alt,db_formatar(($desp['4']['bimestre']+$desp['5']['bimestre']+$desp['6']['bimestre'])*100 /($desp['4']['prevatu']+$desp['5']['prevatu']+$desp['6']['prevatu']) ,'f'),'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[5], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['4']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['4']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['4']['bimestre'], 'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['4']['bimestre']) * 100 / $desp['4']['prevatu'], 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[6], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['5']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['5']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['5']['bimestre'], 'f'), "L", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($desp['5']['bimestre']) * 100 / $desp['5']['prevatu'], 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, $despesa[7], "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp['6']['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['6']['prevatu'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp['6']['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar(($desp['6']['bimestre']) * 100 / $desp['6']['prevatu'], 'f'), 'L', 1, "R", 0);
 
 
 $total_IV_ini    = $desp['1']['previni'] + $desp['2']['previni']+$desp['3']['previni']+$desp['4']['previni']+$desp['5']['previni']+$desp['6']['previni']; 
 $total_IV_atu    = $desp['1']['prevatu']+$desp['2']['prevatu']+$desp['3']['prevatu']+$desp['4']['prevatu']+$desp['5']['prevatu']+$desp['6']['prevatu'];
 $total_IV_atebim = $desp['1']['bimestre']+$desp['2']['bimestre']+$desp['3']['bimestre']+$desp['4']['bimestre']+$desp['5']['bimestre']+$desp['6']['bimestre'];
 
 $pdf->cell(110, $alt, "TOTAL (IV)", "TB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_ini, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_atu, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_atebim, 'f'), "TBL", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($total_IV_atebim * 100 / $total_IV_atu, 'f'), "LTB", 1, "R", 0);
 
 // ------------------------------- * --------------------------- * ------------------------------- *  --------------
 
 $pdf->ln(4);
 
 $pdf->cell(110, $alt, "", "T", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(40, $alt, "DESPESAS LIQUIDADAS", "TL", 1, "C", 0);
 
 $pdf->cell(110, $alt, "DESPESAS PRÓPRIA COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE", "B", 0, "C", 0);
 $pdf->cell(20, $alt, "INICIAL", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "ATUALIZADA", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "Até o ".$txtper." (e)", "TBL", 0, "C", 0);
 $pdf->cell(20, $alt, "% (e/despesas com saúde)", "LTB", 1, "C", 0);
 
 
 $pdf->cell(110, $alt, "DESPESAS COM SAÚDE", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_ini, 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_atu, 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_IV_atebim, 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($total_IV_atebim * 100 / $total_IV_atebim, 'f'), "L", 1, "R", 0);
 
 $pdf->cell(110, $alt, "(-) DESPESAS COM INATIVOS E PENSIONISTAS", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($$desp_p[1]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 // caso a linha abaixo seja zerada,  o manual diz que os valores devem ser pegos do quadro da receita
 // RRO , 4 Ed, pg 278
 
 if ($desp_p[2]['previni']==0 &&  $desp_p[2]['prevatu']==0 && $desp_p[2]['bimestre']==0){
  	$desp_p[2]['previni']	  = $receitas_previni[6];
  	$desp_p[2]['prevatu']    =$receitas_prevatu[6];
 	$desp_p[2]['bimestre']  =$receitas_atebime[6];	
 }	
 if ($desp_p[3]['previni']==0 &&  $desp_p[3]['prevatu']==0 && $desp_p[3]['bimestre']==0){
  	$desp_p[3]['previni']	  = $receitas_previni[11];
  	$desp_p[3]['prevatu']    =$receitas_prevatu[11];
 	$desp_p[3]['bimestre']  =$receitas_atebime[11];	
 }	
 
 $pdf->cell(110, $alt, "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE", "0", 0, "L", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['previni']+$desp_p[3]['previni']+$desp_p[4]['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['prevatu'] +$desp_p[3]['prevatu'] +$desp_p[4]['prevatu'],'f') , "L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'],'f') , "L", 0, "R", 0);
 @$pdf->cell(20, $alt,db_formatar(($desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'])*100/$total_IV_atebim  ,'f') , 'L', 1, "R", 0);
 
 $pdf->cell(110, $alt, espaco($n1)."Recursos de Transferências do Sistema Único de Saúde - SUS", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[2]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 $pdf->cell(110, $alt, espaco($n1)."Recursos de Operações de Crédito", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[3]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 $pdf->cell(110, $alt, espaco($n1)."Outros Recursos", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[4]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 
 $pdf->cell(110, $alt, "(-)RP INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA", "0", 0, "L", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', 'L', 1, "R", 0);
 
 
 $total_V_ini        = 0+ $total_IV_ini         - ($desp_p[1]['previni'] + $desp_p[2]['previni']+$desp_p[3]['previni']+$desp_p[4]['previni']) ;
 $total_V_atu      = 0+ $total_IV_atu       - ($desp_p[1]['prevatu']  +$desp_p[2]['prevatu'] +$desp_p[3]['prevatu'] +$desp_p[4]['prevatu'] )  ;
 $total_V_atebim = 0+ $total_IV_atebim - ($desp_p[1]['bimestre'] + $desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'] ) ;
 
 
 $pdf->cell(110, $alt, "TOTAL DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE(V)", "TB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($total_V_ini, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_V_atu, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_V_atebim, 'f'), "TBL", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(($total_V_atebim * 100) / $total_IV_atebim, 'f'), "LTB", 1, "R", 0);
 
 $pdf->Ln(4);
 
 
 
 
 //-------------------------------------RESTOS APAGAR--revisar com leandro----------------------------------------------------------
 $valor = 0;
 $soma_vlr_inscritos = 0;
 $soma_vlr_cancelados = 0;
 
 for ($i = 0; $i < pg_numrows($result_rpsaldo); $i ++) {
 	db_fieldsmemory($result_rpsaldo, $i);
 	if ($e60_anousu == ($anousu -1)) {
 		$soma_vlr_inscritos = $anterior_a_liquidar + $anterior_liquidado;
 	}
 	$soma_vlr_cancelados += $vlranu;
 }
 
 $pdf->cell(90, $alt, "CONTROLE DE RESTOS A PAGAR INSCRITOS EM EXERCÍCIOS ", "RT", 0, "C", 0);
 $pdf->cell(20, $alt, "Aplicação", "T", 0, "C", 0);
 $pdf->cell(20, $alt, "Aplicação", "TRL", 0, "C", 0);
 $pdf->cell(60, $alt, "RESTOS A PAGAR", "BTL", 1, "C", 0);
 
 $pdf->cell(90, $alt, "ANTERIORES VINCULADOS A SAÚDE", "", 0, "C", 0);
 $pdf->cell(20, $alt, "Miníma em", "L", 0, "C", 0);
 $pdf->cell(20, $alt, "Apurada em", "L", 0, "C", 0);
 $pdf->cell(40, $alt, "Inscritos em 31 de dezembro de ", "L", 0, "C", 0);
 $pdf->cell(20, $alt, "Cancelados em ", "L", 1, "C", 0);

 $pdf->cell(90, $alt, "", "B", 0, "C", 0);
 $pdf->cell(20, $alt, ($anousu -1)." (f)", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, ($anousu -1)." (g)", "BL", 0, "C", 0);
 $pdf->cell(40, $alt, ($anousu -1), "BL", 0, "C", 0);
 $pdf->cell(20, $alt, $anousu." (h)", "LB", 1, "C", 0);
 
 $pdf->cell(90, $alt, "RP DE DESPESA PRÓPRIAS COM AÇÕES E SERVIÇOS", "R", 0, "L", 0);
 $pdf->cell(20, $alt, "", "L", 0, "C", 0);
 $pdf->cell(20, $alt, "", "L", 0, "C", 0);
 $pdf->cell(40, $alt, "", "L", 0, "C", 0);
 $pdf->cell(20, $alt, "", "L", 1, "C", 0);
 
 $pdf->cell(90, $alt, "PÚBLICOS DE SAÚDE", "RB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($VARIAVEL_MINIMA, 'f'), "BL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($VARIAVEL_APURADA, 'f'), "BL", 0, "R", 0);
 $pdf->cell(40, $alt, db_formatar($soma_vlr_inscritos, 'f'), "BL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($soma_vlr_cancelados, 'f'), "LB", 1, "R", 0);
 
 $pdf->cell(170, $alt, "COMPENSAÇÃO DE RESTOS A PAGAR CANCELADOS EM $anousu (VI)", "TRB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($VARIAVEL_COMPENSACAO, 'f'), "TLB", 1, "R", 0);
 
 $pdf->Ln(2);
 
 @$t_participacao = (($total_V_atebim - $VARIAVEL_COMPENSACAO)/$total_I_atebim)*100;
 
 $pdf->cell(170, $alt, "PARTICIPAÇÃO DAS DESPESAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE NA RECEITA LIQUIDA DE IMPOSTOS E TRANSFERÊNCIAS", "TR", 0, "L", 0);
 $pdf->cell(20, $alt, "", "TL", 1, "R", 0);
 $pdf->cell(170, $alt, "CONSTITUCIONAIS E LEGAIS - LIMITES CONSTITUCIONAL <%> [(V - VI) / I]", "RB", 0, "L", 0);
 @ $pdf->cell(20, $alt, db_formatar($t_participacao, 'f'), "LB", 1, "R", 0);
 
 //-----------------------------------DESPESA POR SUBFUNÇAO----------------------------------------------------------------
 $pdf->Ln(2);
 
 $pdf->cell(110, $alt, "DESPESA COM SAÚDE", "T", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(20, $alt, "DOTAÇÃO", "TRL", 0, "C", 0);
 $pdf->cell(40, $alt, "DESPESA LIQUIDADAS", "TL", 1, "C", 0);
 
 $pdf->cell(110, $alt, "(Por Subfunção)", "B", 0, "C", 0);
 $pdf->cell(20, $alt, "INICIAL", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "ATUALIZADA", "BL", 0, "C", 0);
 $pdf->cell(20, $alt, "Até o ".$txtper." (i)", "TBL", 0, "C", 0);
 $pdf->cell(20, $alt, "% i /(total i)", "LTB", 1, "C", 0);
 
 for ($i = 0; $i < 7; $i ++) {
 	  if ($i == 0) {
 		   $pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub301_dotini,'f'), "L", 0, "R", 0);
 	           $pdf->cell(20, $alt, db_formatar($sub301_atuali,'f'), "L", 0, "R", 0);
 		   $pdf->cell(20, $alt, db_formatar(($sub301_atebim), 'f'), "L", 0, "R", 0);
 		   @$pdf->cell(20, $alt, db_formatar(((($sub301_atebim) / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 		   continue;
 	 }
 	 if ($i == 1) {
 		   $pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub302_dotini, 'f'), "L", 0, "R", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub302_atuali, 'f'), "L", 0, "R", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub302_atebim, 'f'), "L", 0, "R", 0);
 		  @$pdf->cell(20, $alt, db_formatar((($sub302_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 		   continue;
 	 }
 	 if ($i == 2) {
 		   $pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub303_dotini, 'f'), "L", 0, "R", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub303_atuali, 'f'), "L", 0, "R", 0);
 		   $pdf->cell(20, $alt, db_formatar($sub303_atebim, 'f'), "L", 0, "R", 0);
 		  @$pdf->cell(20, $alt, db_formatar((($sub303_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 		   continue;
 	 }
 	if ($i == 3) {
 		$pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		$pdf->cell(20, $alt, db_formatar($sub304_dotini, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub304_atuali, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub304_atebim, 'f'), "L", 0, "R", 0);
 		@ $pdf->cell(20, $alt, db_formatar((($sub304_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 		continue;
 	}
 	if ($i == 4) {
 		$pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		$pdf->cell(20, $alt, db_formatar($sub305_dotini, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub305_atuali, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub305_atebim, 'f'), "L", 0, "R", 0);
 		@ $pdf->cell(20, $alt, db_formatar((($sub305_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 		continue;
 	}
 	if ($i == 5) {
 		$pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		$pdf->cell(20, $alt, db_formatar($sub306_dotini, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub306_atuali, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($sub306_atebim, 'f'), "L", 0, "R", 0);
 		@ $pdf->cell(20, $alt, db_formatar((($sub306_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
    continue;
  }
  if ($i == 6){
 		$pdf->cell(110, $alt, $subfuncao[$i], "", 0, "L", 0);
 		$pdf->cell(20, $alt, db_formatar($subout_dotini, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($subout_atuali, 'f'), "L", 0, "R", 0);
 		$pdf->cell(20, $alt, db_formatar($subout_atebim, 'f'), "L", 0, "R", 0);
 		@ $pdf->cell(20, $alt, db_formatar((($subout_atebim / $total_acum) * 100), 'f'), "L", 1, "R", 0);
 	}
 }
 
 $pdf->cell(110, $alt, "TOTAL", "TB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($total_ini, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_atu, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($total_acum, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, " - ", "LTB", 1, "R", 0);
 
 //------------------------------------------------
 
 // quadro DESPESAS COM SAUDE (POR SUBFUNCAO) ultimo quadro do relatorio
 
 
 $pdf->cell(110, $alt, "(-) DESPESAS COM INATIVOS E PENSIONISTAS", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[1]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($$desp_p[1]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 // caso a linha abaixo seja zerada,  o manual diz que os valores devem ser pegos do quadro da receita
 // RRO , 4 Ed, pg 278
 
 if ($desp_p[2]['previni']==0 &&  $desp_p[2]['prevatu']==0 && $desp_p[2]['bimestre']==0){
  	$desp_p[2]['previni']	  = $receitas_previni[6];
  	$desp_p[2]['prevatu']    =$receitas_prevatu[6];
 	$desp_p[2]['bimestre']  =$receitas_atebime[6];	
 }	
 if ($desp_p[3]['previni']==0 &&  $desp_p[3]['prevatu']==0 && $desp_p[3]['bimestre']==0){
  	$desp_p[3]['previni']	  = $receitas_previni[11];
  	$desp_p[3]['prevatu']    =$receitas_prevatu[11];
 	$desp_p[3]['bimestre']  =$receitas_atebime[11];	
 }	
 
 $pdf->cell(110, $alt, "(-)DESPESAS CUSTEADAS COM OUTROS RECURSOS DESTINADOS À SAÚDE", "0", 0, "L", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['previni']+$desp_p[3]['previni']+$desp_p[4]['previni'],'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['prevatu'] +$desp_p[3]['prevatu'] +$desp_p[4]['prevatu'],'f') , "L", 0, "R", 0);
 $pdf->cell(20, $alt,db_formatar($desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'],'f') , "L", 0, "R", 0);
 @$pdf->cell(20, $alt,db_formatar(($desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'])*100/$total_IV_atebim  ,'f') , 'L', 1, "R", 0);
 
 $pdf->cell(110, $alt, espaco($n1)."Recursos de Transferências do Sistema Único de Saúde - SUS", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[2]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[2]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 $pdf->cell(110, $alt, espaco($n1)."Recursos de Operações de Crédito", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[3]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[3]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);

 $pdf->cell(110, $alt, espaco($n1)."Outros Recursos", "0", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['previni'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['prevatu'], 'f'), "L", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar($desp_p[4]['bimestre'], 'f'), "L", 0, "R", 0);
 @ $pdf->cell(20, $alt, db_formatar($desp_p[4]['bimestre'] * 100 / $total_IV_atebim, 'f'), 'L', 1, "R", 0);
 
 
 $pdf->cell(110, $alt, "(-)RP INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA", "0", 0, "L", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', "L", 0, "R", 0);
 $pdf->cell(20, $alt, '-', 'L', 1, "R", 0);
 
 
 $total01 =  ($desp_p[1]['previni'] + $desp_p[2]['previni']+$desp_p[3]['previni']+$desp_p[4]['previni']) ;
 $total02 =  ($desp_p[1]['prevatu']  +$desp_p[2]['prevatu'] +$desp_p[3]['prevatu'] +$desp_p[4]['prevatu'] )  ;
 $total03 =  ($desp_p[1]['bimestre'] + $desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'] ) ;
 
 
 $pdf->cell(110, $alt, "TOTAL DAS DESPESAS PRÓPRIAS COM AÇÕES E SERVIÇOS PÚBLICOS DE SAÚDE(V)", "TB", 0, "L", 0);
 $pdf->cell(20, $alt, db_formatar( $total_ini       - $total01, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar( $total_atu     - $total02, 'f'), "TBL", 0, "R", 0);
 $pdf->cell(20, $alt, db_formatar(  $total_acum - $total03, 'f'), "TBL", 0, "R", 0);
 @$pdf->cell(20, $alt, db_formatar(100, 'f'), "LTB", 1, "R", 0);
 
 
 
 $pdf->cell(40, $alt, "FONTE : Contabilidade ", '0', 0, "L", 0);
 
 //assinaturas
 $pdf->Ln(15);
 
 assinaturas(&$pdf,&$classinatura,'LRF');

 $pdf->Output();

} // end arqinclude
 

?>