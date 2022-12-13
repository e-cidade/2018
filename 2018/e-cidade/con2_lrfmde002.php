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

if (!isset($arqinclude)) {
  // se este arquivo no esta incluido por outro
  
  include(modification("fpdf151/pdf.php"));
  include(modification("fpdf151/assinatura.php"));
  include(modification("libs/db_sql.php"));
  include(modification("libs/db_liborcamento.php"));
  include(modification("libs/db_libcontabilidade.php"));
  include(modification("libs/db_libtxt.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("classes/db_conrelinfo_classe.php"));
  include(modification("classes/db_orcparamrel_classe.php"));
  
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);
  
  $classinatura = new cl_assinatura;
  $orcparamrel = new cl_orcparamrel;
  $clconrelinfo = new cl_conrelinfo;
  
  $anousu = db_getsession("DB_anousu");
  $dt = datas_bimestre($bimestre,$anousu);
  // no dbforms/db_funcoes.php
  $dt_ini= $dt[0];
  // data inicial do perodo
  $dt_fin= $dt[1];
  // data final do perodo
  
}

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------
// PARAMETROS
for ($linha=1; $linha<=18; $linha++) {
  $m_receita[$linha]['estrut']       = $orcparamrel->sql_parametro('12',$linha);
  //
  //	$m_receita[$linha]['exclusao']       = $orcparamrel->sql_parametro('12',$linha,'t');
  //
  $m_receita[$linha]['nivel']          = $orcparamrel->sql_nivel('12',$linha);
  //
  $m_receita[$linha]['recurso']     = $orcparamrel->sql_recurso('12',$linha);
  //
  $m_receita[$linha]['inicial']        = 0 ;
  $m_receita[$linha]['atualizada'] = 0 ;
  $m_receita[$linha]['bimestre']   = 0 ;
  $m_receita[$linha]['exercicio']   = 0 ;
  // ate o bimestre
}
for ($linha=19; $linha<=26; $linha++) {
  $m_despesa[$linha]['estrut']        = $orcparamrel->sql_parametro('12',$linha);
  //
  
  $m_despesa[$linha]['nivel']         = $orcparamrel->sql_nivel('12',$linha);
  //
  $m_despesa[$linha]['nivelexclusao'] = $orcparamrel->sql_nivelexclusao('12',$linha);
  //
  
  $m_despesa[$linha]['funcao']     = $orcparamrel->sql_funcao('12',$linha);
  //
  $m_despesa[$linha]['subfunc']    = $orcparamrel->sql_subfunc('12',$linha);
  //
  $m_despesa[$linha]['recurso']    = $orcparamrel->sql_recurso('12',$linha);
  //
  $m_despesa[$linha]['inicial']    = 0 ;
  $m_despesa[$linha]['atualizada'] = 0 ;
  $m_despesa[$linha]['bimestre']   = 0 ;
  $m_despesa[$linha]['exercicio']  = 0 ;
  // ate o bimestre
}

$m_reserva_mde['estrut']    = $orcparamrel->sql_parametro('12','27');
//
$m_reserva_mde['subfunc'] = $orcparamrel->sql_subfunc('12','27');
//
$m_reserva_mde['recurso'] = $orcparamrel->sql_recurso('12','27');
//
$m_reserva_mde['valor']      = 0;

$m_reserva_fundef['estrut']    = $orcparamrel->sql_parametro('12','28');
//
$m_reserva_fundef['subfunc'] = $orcparamrel->sql_subfunc('12','28');
//
$m_reserva_fundef['recurso'] = $orcparamrel->sql_recurso('12','28');
//
$m_reserva_fundef['valor']      = 0;

$m_interferencia_mde['estrut']    = $orcparamrel->sql_parametro('12','29');
//
$m_interferencia_mde['periodo']   = 0;
$m_interferencia_mde['valor']     = 0;

$m_interferencia_fundef['estrut']   = $orcparamrel->sql_parametro('12','30');
//
$m_interferencia_fundef['periodo']  = 0;
$m_interferencia_fundef['valor']    = 0;

$m_saldo_financeiro_fundef['estrut']               = $orcparamrel->sql_parametro('12','31');
//
$m_saldo_financeiro_fundef['valor_inscricao'] = 0;
$m_saldo_financeiro_fundef['valor_atual']       = 0;


$m_inscricao_rp_mde['estrut']   = $orcparamrel->sql_parametro('12','32');
//
$m_inscricao_rp_mde['subfunc']  = $orcparamrel->sql_subfunc('12','32');
//
$m_inscricao_rp_mde['recurso']  = $orcparamrel->sql_recurso('12','32');
//
$m_inscricao_rp_mde['valor']    = 0;

$m_inscricao_rp_fundef['estrut']  = $orcparamrel->sql_parametro('12','33');
//
$m_inscricao_rp_fundef['subfunc'] = $orcparamrel->sql_subfunc('12','33');
//
$m_inscricao_rp_fundef['recurso'] = $orcparamrel->sql_recurso('12','33');
//
$m_inscricao_rp_fundef['valor']   = 0;

$m_cancelamento_rp_mde['estrut']   = $orcparamrel->sql_parametro('12','34');
//
$m_cancelamento_rp_mde['subfunc']  = $orcparamrel->sql_subfunc('12','34');
//
$m_cancelamento_rp_mde['recurso']  = $orcparamrel->sql_recurso('12','34');
//
$m_cancelamento_rp_mde['valor']    = 0;

$m_cancelamento_rp_fundef['estrut']  = $orcparamrel->sql_parametro('12','35');
//
$m_cancelamento_rp_fundef['subfunc'] = $orcparamrel->sql_subfunc('12','35');
//
$m_cancelamento_rp_fundef['recurso'] = $orcparamrel->sql_recurso('12','35');
//
$m_cancelamento_rp_fundef['valor']   = 0;

// ---------------------------------------------------------------------------------------------------------------------------------------------------------------
//  tela do relatorio
$receita  = array();
$receita[1]['txt']  = "RECEITA RESULTANTE DE IMPOSTOS (I)";
$receita[2]['txt']  = "	Receita de Impostos";
$receita[3]['txt']  = "  		Impostos";
$receita[4]['txt']  = "		Divida Ativa dos Impostos";
$receita[5]['txt']  = "		Multas, Juros de Mora e Outros Encargos de Impostos e da Dvida Ativa de Impostos";
$receita[6]['txt']  = "Receitas de Transferencias Constitucionais e Legais";
$receita[7]['txt']  = "		Cota-Parte FPM (85%)";
$receita[8]['txt']  = "		Transferencia Financeira ICMS-Desonerao - L.C. n 87/96 (85%)";
$receita[9]['txt']  = "		Cota-Parte ICMS (85%)";
$receita[10]['txt'] = "		Cota-Parte IPI-Exportao(85%)";
$receita[11]['txt'] = "		Parcela das Trasnferencias destinada  Formao do FUNDEF(II)";
$receita[12]['txt'] = "		Cota-Prte ITR (100%)";
$receita[13]['txt'] = "		Cota-Parte IOF-Ouro(100%)";
$receita[14]['txt'] = "		Cota-Parte IPVA (100%)";
$receita[15]['txt'] = "RECEITAS VINCULADAS AO ENSINO (III)";
$receita[16]['txt'] = "	Transferencias Multigovernamentais do FUNDEF (IV)";
$receita[17]['txt'] = "		Transferencias de Recursos do FUNDEF (V)";
$receita[18]['txt'] = "		Complementao da Unio ao FUNDEF";
$receita[19]['txt'] = "Transferencias do FNDE";
$receita[20]['txt'] = "		Transferencias do Salrio-Educao";
$receita[21]['txt'] = "		Outras Transferencias do FNDE";
$receita[22]['txt'] = "Transferencias de Convnios destinadas a Programas de Educao";
$receita[23]['txt'] = "Receita de Operaes de Crdito destinadas  Educao";
$receita[24]['txt'] = "Outras Receitas Destinadas  Educao";
for ($linha=1; $linha<=24; $linha++) {
  $receita[$linha]['inicial']        = 0 ;
  $receita[$linha]['atualizada'] = 0 ;
  $receita[$linha]['bimestre']   = 0 ;
  $receita[$linha]['exercicio']   = 0 ;
  // ate o bimestre
}
$despesa  = array();
$despesa[1]['txt']    = "VINCULADAS S RECEITAS RESULTANTES DE IMPOSTOS";
$despesa[2]['txt']    = "		Despesas com Ensino Funamental (VII)";
$despesa[3]['txt']    = "		Despesas com Educao Infantil em creches e Pr-Escolas (VIII)";
$despesa[4]['txt']    = "		Outras Despesas com Ensino";
$despesa[5]['txt']    = "VINCULADAS AO FUNDEF, NO ENSINO FUNDAMENTAL (IX)";
$despesa[6]['txt']    = "		Pagamento dos Profissionais do Magistrio do Ensino Fundamental (X)";
$despesa[7]['txt']    = "		Outras Despesas no Ensino Fundamental";
$despesa[8]['txt']    = "VINCULADAS  CONTRIBUIO SOCIAL DO SALARIO-EDUCAO";
$despesa[9]['txt']    = "FINANCIADAS COM RECURSOS DE OPERAES DE CREDITO";
$despesa[10]['txt']  = "FINANCIADAS COM OUTROS RECURSOS DESTINADAS  EDUCAO";
for ($linha=1; $linha<=10; $linha++) {
  $despesa[$linha]['inicial']        = 0 ;
  $despesa[$linha]['atualizada'] = 0 ;
  $despesa[$linha]['bimestre']   = 0 ;
  $despesa[$linha]['exercicio']   = 0 ;
  // ate o bimestre
  
}





// ---------------------------------------------------------------------------------------------------------------------------------------------------------------
// RECORDSETS

$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
$result = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
//db_criatabela($result);
//exit;

for ($i=0; $i<pg_numrows($result); $i++) {
  db_fieldsmemory($result,$i);
  $estrutural = $o57_fonte;
  $v_recurso  = $o70_codigo;
  
  for ($linha=1; $linha<=18; $linha++) {
    
    // adio de valores nas linhas
    if (in_array($estrutural,$m_receita[$linha]['estrut'])) {
      
      if (count($m_receita[$linha]['recurso'])==0 ||   in_array($v_recurso, $m_receita[$linha]['recurso'])) {
        
        if ($linha==8) {
          // contador disse que a sequencia 8
          // taz sempre o positivo
          //
          $m_receita[$linha]['inicial']     += abs($saldo_inicial);
          $m_receita[$linha]['atualizada']  += abs($saldo_inicial_prevadic);
          $m_receita[$linha]['bimestre']    += abs($saldo_arrecadado) ;
          $m_receita[$linha]['exercicio']   += abs($saldo_arrecadado_acumulado);
        } else {
          $m_receita[$linha]['inicial']     += $saldo_inicial;
          $m_receita[$linha]['atualizada']  += $saldo_inicial_prevadic;
          $m_receita[$linha]['bimestre']    += $saldo_arrecadado ;
          $m_receita[$linha]['exercicio']   += $saldo_arrecadado_acumulado;
        }
        
      }
    }
    /*
    // exclusao de parametros, excluso de valores
    if (in_array($estrutural,$m_receita[$linha]['exclusao'])) {
      
      if (count($m_receita[$linha]['recurso'])==0 ||   in_array($v_recurso, $m_receita[$linha]['recurso'])) {
        
        $m_receita[$linha]['inicial']        -= $saldo_inicial;
        $m_receita[$linha]['atualizada'] -= $saldo_inicial_prevadic;
        $m_receita[$linha]['bimestre']   -= $saldo_arrecadado ;
        $m_receita[$linha]['exercicio']   -= $saldo_arrecadado_acumulado;
        
      }
    }
    */
    
  }
}
for ($col=1; $col<=4; $col++) {
  $pcol =array(1=>'inicial',2=>'atualizada',3=>'bimestre',4=>'exercicio');
  
  $receita[2][$pcol[$col]]   = $m_receita[1][$pcol[$col]]+$m_receita[2][$pcol[$col]]+$m_receita[3][$pcol[$col]];
  $receita[3][$pcol[$col]]   = $m_receita[1][$pcol[$col]];
  $receita[4][$pcol[$col]]   = $m_receita[2][$pcol[$col]];
  $receita[5][$pcol[$col]]   = $m_receita[3][$pcol[$col]];
  $receita[6][$pcol[$col]]   = $m_receita[4][$pcol[$col]]+$m_receita[5][$pcol[$col]]+$m_receita[6][$pcol[$col]]+$m_receita[7][$pcol[$col]]+$m_receita[9][$pcol[$col]]+$m_receita[10][$pcol[$col]]+$m_receita[11][$pcol[$col]];
  
  $receita[7][$pcol[$col]]   = $m_receita[4][$pcol[$col]];
  $receita[8][$pcol[$col]]   = $m_receita[5][$pcol[$col]];
  $receita[9][$pcol[$col]]   = $m_receita[6][$pcol[$col]];
  $receita[10][$pcol[$col]]  = $m_receita[7][$pcol[$col]];
  $receita[11][$pcol[$col]]  = $m_receita[8][$pcol[$col]];
  $receita[12][$pcol[$col]]  = $m_receita[9][$pcol[$col]];
  $receita[13][$pcol[$col]]  = $m_receita[10][$pcol[$col]];
  $receita[14][$pcol[$col]]  = $m_receita[11][$pcol[$col]];
  
  $receita[15][$pcol[$col]]  = $m_receita[12][$pcol[$col]]+$m_receita[13][$pcol[$col]]+$m_receita[14][$pcol[$col]]+$m_receita[15][$pcol[$col]]+$m_receita[16][$pcol[$col]]+$m_receita[17][$pcol[$col]]+$m_receita[18][$pcol[$col]];
  $receita[16][$pcol[$col]]  = $m_receita[12][$pcol[$col]]+$m_receita[13][$pcol[$col]];
  $receita[17][$pcol[$col]]  = $m_receita[12][$pcol[$col]];
  $receita[18][$pcol[$col]]  = $m_receita[13][$pcol[$col]];
  $receita[19][$pcol[$col]]  = $m_receita[14][$pcol[$col]]+$m_receita[15][$pcol[$col]];
  $receita[20][$pcol[$col]]  = $m_receita[14][$pcol[$col]];
  $receita[21][$pcol[$col]]  = $m_receita[15][$pcol[$col]];
  $receita[22][$pcol[$col]]  = $m_receita[16][$pcol[$col]];
  $receita[23][$pcol[$col]]  = $m_receita[17][$pcol[$col]];
  $receita[24][$pcol[$col]]  = $m_receita[18][$pcol[$col]];
  $receita[1][$pcol[$col]]   = $receita[2][$pcol[$col]] +$receita[6][$pcol[$col]];
  
}


// despesa
$sele_work = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
$result_despesa = db_dotacaosaldo(8,2, 3, true, $sele_work, $anousu, $dt_ini, $dt_fin);

for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
  db_fieldsmemory($result_despesa, $i);
  
  for ($linha=19; $linha<=26; $linha++) {
    $nivel        = $m_despesa[$linha]['nivel'];
    $estrutural   = $o58_elemento.'00';
    $estrutural   = substr($estrutural,0,$nivel);
    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);
    $v_funcao     = $o58_funcao;
    $v_subfuncao  = $o58_subfuncao;
    $v_recurso    = $o58_codigo;
    
    // adio de parametros
    // if (count($m_despesa[$linha]['estrut'])==0  ||  in_array($v_estrutural, $m_despesa[$linha]['estrut'])) {
      if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])) {
        if (count($m_despesa[$linha]['funcao']) == 0 || in_array($v_funcao, $m_despesa[$linha]['funcao'])) {
          if (count($m_despesa[$linha]['subfunc']) == 0 || in_array($v_subfuncao, $m_despesa[$linha]['subfunc'])) {
            if (count($m_despesa[$linha]['recurso'])==0 || in_array($v_recurso, $m_despesa[$linha]['recurso'])) {
              
              $m_despesa[$linha]['inicial']     += $dot_ini;
              $m_despesa[$linha]['atualizada']   += $dot_ini + ($suplementado_acumulado - $reduzido_acumulado);
              $m_despesa[$linha]['bimestre'] += $liquidado;
              $m_despesa[$linha]['exercicio'] += $liquidado_acumulado;
              
            }
          }
        }
      }
    }
    // end for
    
    // RESERVA MDE
    if (count($m_reserva_mde['estrut'])==0  ||  in_array($v_estrutural, $m_reserva_mde['estrut'])) {
      
      if (count($m_reserva_mde['subfunc'])==0  ||  in_array($v_subfuncao, $m_reserva_mde['subfunc'])) {
        
        if (count($m_reserva_mde['recurso'])==0 ||   in_array($v_recurso, $m_reserva_mde['recurso'])) {
          
          $m_reserva_mde['valor']     += $dot_ini;
        }
      }
    }
    // RESERVA FUNDEF
    if (count($m_reserva_fundef['estrut'])==0  ||  in_array($v_estrutural, $m_reserva_fundef['estrut'])) {
      
      if (count($m_reserva_fundef['subfunc'])==0  ||  in_array($v_subfuncao, $m_reserva_fundef['subfunc'])) {
        
        if (count($m_reserva_fundef['recurso'])==0 ||   in_array($v_recurso, $m_reserva_fundef['recurso'])) {
          
          $m_reserva_fundef['valor']     += $dot_ini;
        }
      }
    }
    
  }
  // end loop
  
  $despesa[1]['txt']    = "VINCULADAS S RECEITAS RESULTANTES DE IMPOSTOS";
  $despesa[2]['txt']    = "		Despesas com Ensino Funamental (VII)";
  $despesa[3]['txt']    = "		Despesas com Educao Infantil em creches e Pr-Escolas (VIII)";
  $despesa[4]['txt']    = "		Outras Despesas com Ensino";
  $despesa[5]['txt']    = "VINCULADAS AO FUNDEF, NO ENSINO FUNDAMENTAL (IX)";
  $despesa[6]['txt']    = "		Pagamento dos Profissionais do Magistrio do Ensino Fundamental (X)";
  $despesa[7]['txt']    = "		Outras Despesas no Ensino Fundamental";
  $despesa[8]['txt']    = "VINCULADAS  CONTRIBUIO SOCIAL DO SALARIO-EDUCAO";
  $despesa[9]['txt']    = "FINANCIADAS COM RECURSOS DE OPERAES DE CRDITO";
  $despesa[10]['txt']   = "FINANCIADAS COM OUTROS RECURSOS DESTINADAS  EDUCAO";
  
  for ($col=1; $col<=4; $col++) {
    $pcol =array(1=>'inicial',2=>'atualizada',3=>'bimestre',4=>'exercicio');
    
    $despesa[1][$pcol[$col]]   = $m_despesa[19][$pcol[$col]]+$m_despesa[20][$pcol[$col]]+$m_despesa[21][$pcol[$col]];
    $despesa[2][$pcol[$col]]   = $m_despesa[19][$pcol[$col]];
    $despesa[3][$pcol[$col]]   = $m_despesa[20][$pcol[$col]];
    $despesa[4][$pcol[$col]]   = $m_despesa[21][$pcol[$col]];
    $despesa[5][$pcol[$col]]   = $m_despesa[22][$pcol[$col]]+$m_despesa[23][$pcol[$col]];
    $despesa[6][$pcol[$col]]   = $m_despesa[22][$pcol[$col]];
    $despesa[7][$pcol[$col]]   = $m_despesa[23][$pcol[$col]];
    $despesa[8][$pcol[$col]]   = $m_despesa[24][$pcol[$col]];
    $despesa[9][$pcol[$col]]   = $m_despesa[25][$pcol[$col]];
    $despesa[10][$pcol[$col]]  = $m_despesa[26][$pcol[$col]];
    
  }
  
  // BAL_VER
  $result_bal = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
  for ($i=0; $i<pg_numrows($result_bal); $i++) {
    db_fieldsmemory($result_bal,$i);
    
    if (in_array($estrutural,$m_interferencia_mde['estrut'] )) {
      $m_interferencia_mde['periodo']    += $saldo_anterior_debito-$saldo_anterior_credito;
      $m_interferencia_mde['valor']      += $saldo_final;
      // ate o bimestre
    }
    
    if (in_array($estrutural,$m_interferencia_fundef['estrut'])) {
      $m_interferencia_fundef['periodo'] += $saldo_anterior_debito-$saldo_anterior_credito;
      $m_interferencia_fundef['valor']     += $saldo_final;
    }
    
    if (in_array($estrutural,$m_saldo_financeiro_fundef['estrut'])) {
      $m_saldo_financeiro_fundef['valor_inscricao']  +=  $saldo_anterior;
      $m_saldo_financeiro_fundef['valor_atual']      +=  $saldo_final;
    }
    /*
    if (in_array($estrutural,$m_inscricao_rp_mde['estrut'])) {
      $m_inscricao_rp_mde['valor'] += $saldo_final;
    }
    
    if (in_array($estrutural,$m_inscricao_rp_fundef['estrut'])) {
      $m_inscricao_rp_fundef['valor'] += $saldo_final ;
    }
    
    if (in_array($estrutural,$m_cancelamento_rp_mde['estrut'])) {
      $m_cancelamento_rp_mde['valor'] += $saldo_final;
    }
    
    if (in_array($estrutural,$m_cancelamento_rp_fundef['estrut'])) {
      $m_cancelamento_rp_fundef['valor'] += $saldo_final ;
    }
    */
    
  }
  
  
  // subfuncao
  $m_desp_subfuncao['subfunc'] = $orcparamrel->sql_subfunc('12','36');
  //
  $m_desp_subfuncao['recurso'] = $orcparamrel->sql_recurso('12','36');
  //
  
  $v_subfunc = '0';
  $v_codigo  = '0';
  $sp= '';
  foreach($m_desp_subfuncao['subfunc'] as $registro){
    $v_subfunc .= $sp.$registro;
    $sp =',';
  }
  $sp='';
  foreach($m_desp_subfuncao['recurso'] as $registro){
    $v_codigo .= $sp.$registro;
    $sp =',';
  }
  
  $result_subfunc = db_dotacaosaldo(4,3,2,true," o58_subfuncao in ($v_subfunc) and o58_codigo in ($v_codigo) and o58_instit in (".str_replace('-',', ',$db_selinstit)." ) ",$anousu,$dt_ini,$dt_fin);
  
  // saldo dos rps inscritos e cancelados do mde e fundef
  $v_subfunc = '0';
  $v_codigo  = '0';
  $sp= '';
  foreach($m_inscricao_rp_mde['subfunc'] as $registro){
    $v_subfunc .= $sp.$registro;
    $sp =',';
  }
  $sp='';
  foreach($m_inscricao_rp_mde['recurso'] as $registro){
    $v_codigo .= $sp.$registro;
    $sp =',';
  }
  $db_filtro = ' in ('.str_replace('-',', ',$db_selinstit).')';
  $result_rp_mde = db_rpsaldo($anousu,
  $db_filtro,
  $anousu.'-01-01',
  $dt_fin,
  " o58_codigo in (".$v_codigo.") and o58_subfuncao in (".$v_subfunc.") and vlranu > 0 ");
  
  // ------------------------------------------------------
  $v_subfunc = '0';
  $v_codigo  = '0';
  $sp= '';
  foreach($m_inscricao_rp_fundef['subfunc'] as $registro){
    $v_subfunc .= $sp.$registro;
    $sp =',';
  }
  $sp='';
  foreach($m_inscricao_rp_fundef['recurso'] as $registro){
    $v_codigo .= $sp.$registro;
    $sp =',';
  }
  $result_rp_fundef = db_rpsaldo($anousu,
  $db_filtro,
  $anousu.'-01-01',
  $dt_fin,
  " o58_codigo in (".$v_codigo.")  and o58_subfuncao in (".$v_subfunc.") and vlranu > 0 ");
  
  
  
  // db_criatabela($result_rp_mde);
  // db_criatabela($result_rp_fundef);
  
  // ---------------------------------------------------------------------------------------------------------------------------------------------------------------
  
  // VARIAVEIS
  $GANHO_COMPLEM_FUNDEF  = 0;
  $DESP_ENS_FUNDAMENTAL  = 0;
  // Despesa com ensino fundamental
  $DESP_ENS_INFANTIL     = 0;
  $DESP_VINC_SUPERAVIT   = 0;
  $COMPENSACAO_RP_MDE    = 0;
  // conpensao rp do mde
  $COMPENSACAO_RP_FUNDEF = 0;
  // compensao rp ensino fundamental
  $RP_MDE_MINIMA  = 0;
  $RP_MDE_APURADA = 0;
  $RP_MDE_INSCRITO= 0;
  $RP_FUNDEF_MINIMA  = 0;
  $RP_FUNDEF_APURADA = 0;
  $RP_FUNDEF_INSCRITO= 0;
  
  $res = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(12,str_replace('-',',',$db_selinstit)));
  
  if ($clconrelinfo->numrows > 0 ) {
    for ($x=0; $x < $clconrelinfo->numrows; $x++) {
      db_fieldsmemory($res,$x);
      if ($c83_codigo ==1 ) {
        $GANHO_COMPLEM_FUNDEF  = $c83_informacao;
      } else if ($c83_codigo ==2 ) {
        $DESP_ENS_FUNDAMENTAL  = $c83_informacao;
      } else if ($c83_codigo ==3 ) {
        $DESP_ENS_INFANTIL  = $c83_informacao;
      } else if ($c83_codigo ==4 ) {
        $DESP_VINC_SUPERAVIT  = $c83_informacao;
      } else if ($c83_codigo ==5 ) {
        $COMPENSACAO_RP_MDE  = $c83_informacao;
      } else if ($c83_codigo ==6 ) {
        $COMPENSACAO_RP_FUNDEF  = $c83_informacao;
      } else if ($c83_codigo ==251 ) {
        $RP_MDE_MINIMA   = $c83_informacao;
      } else if ($c83_codigo ==252 ) {
        $RP_MDE_APURADA  = $c83_informacao;
      } else if ($c83_codigo ==253 ) {
        $RP_MDE_INSCRITO  = $c83_informacao;
      } else if ($c83_codigo ==254 ) {
        $RP_FUNDEF_MINIMA  = $c83_informacao;
      } else if ($c83_codigo ==255 ) {
        $RP_FUNDEF_APURADA  = $c83_informacao;
      } else if ($c83_codigo ==256 ) {
        $RP_FUNDEF_INSCRITO = $c83_informacao;
      }
    }
  }
  //-------------------------------------------------------------------------------------------------
  // totalizadores dos relatorios
  $somador_I_inicial     = $receita[1]['inicial'];
  $somador_I_atualizada  = $receita[1]['atualizada'];
  $somador_I_nobimestre  = $receita[1]['bimestre'];
  $somador_I_atebimestre = $receita[1]['exercicio'];
  
  
  $somador_II_inicial           = $receita[11]['inicial'];
  $somador_II_atualizada    = $receita[11]['atualizada'];
  $somador_II_nobimestre  = $receita[11]['bimestre'];
  $somador_II_atebimestre = $receita[11]['exercicio'];
  
  
  $somador_III_inicial           = $receita[15]['inicial'];
  $somador_III_atualizada    = $receita[15]['atualizada'];
  $somador_III_nobimestre  = $receita[15]['bimestre'];
  $somador_III_atebimestre = $receita[15]['exercicio'];
  
  $somador_IV_inicial     = $receita[16]['inicial'];
  $somador_IV_atualizada  = $receita[16]['atualizada'];
  $somador_IV_nobimestre  = $receita[16]['bimestre'];
  $somador_IV_atebimestre = $receita[16]['exercicio'];
  
  $somador_V_inicial     = $receita[17]['inicial'];
  $somador_V_atualizada  = $receita[17]['atualizada'];
  $somador_V_nobimestre  = $receita[17]['bimestre'];
  $somador_V_atebimestre = $receita[17]['exercicio'];
  
  $somador_VI_inicial     = $somador_I_inicial +$somador_III_inicial -$somador_II_inicial ;
  $somador_VI_atualizada  = $somador_I_atualizada+ $somador_III_atualizada - $somador_II_atualizada ;
  $somador_VI_nobimestre  = $somador_I_nobimestre +$somador_III_nobimestre -$somador_II_nobimestre;
  $somador_VI_atebimestre = $somador_I_atebimestre + $somador_III_atebimestre - $somador_II_atebimestre;
  
  // adiciona interferencia
  
  $despesa[1]['bimestre']   = $despesa[1]['bimestre'] + $m_interferencia_mde['periodo'];
  $despesa[1]['exercicio']  = $despesa[1]['exercicio']+ $m_interferencia_mde['valor'] ;
  
  $despesa[2]['bimestre']   = $despesa[2]['bimestre'] + $m_interferencia_mde['periodo'];
  $despesa[2]['exercicio']  = $despesa[2]['exercicio']+ $m_interferencia_mde['valor'] ;
  
  
  $somador_VII_inicial     = $despesa[2]['inicial'];
  $somador_VII_atualizada  = $despesa[2]['atualizada'];
  $somador_VII_nobimestre  = $despesa[2]['bimestre'] ;
  $somador_VII_atebimestre = $despesa[2]['exercicio'];
  
  
  
  $somador_VIII_inicial     = $despesa[3]['inicial'];
  $somador_VIII_atualizada  = $despesa[3]['atualizada'];
  $somador_VIII_nobimestre  = $despesa[3]['bimestre'];
  $somador_VIII_atebimestre = $despesa[3]['exercicio'];
  
  $somador_IX_inicial           = $despesa[5]['inicial'];
  $somador_IX_atualizada    = $despesa[5]['atualizada'];
  $somador_IX_nobimestre  = $despesa[5]['bimestre'];
  $somador_IX_atebimestre = $despesa[5]['exercicio'];
  
  // adiciona interferencia
  $despesa[5]['bimestre']  = $despesa[5]['bimestre'] + $m_interferencia_fundef['periodo'];
  $despesa[5]['exercicio'] = $despesa[5]['exercicio']+ $m_interferencia_fundef['valor'];
  
  
  $despesa[6]['bimestre']  = $despesa[6]['bimestre'] + $m_interferencia_fundef['periodo'];
  $despesa[6]['exercicio'] = $despesa[6]['exercicio']+ $m_interferencia_fundef['valor'];
  
  $somador_X_inicial     = $despesa[6]['inicial'];
  $somador_X_atualizada  = $despesa[6]['atualizada'];
  $somador_X_nobimestre  = $despesa[6]['bimestre'] ;
  $somador_X_atebimestre = $despesa[6]['exercicio'];
  
  
  $somador_XI_inicial           = $despesa[1]['inicial'] + $despesa[5]['inicial']+$despesa[8]['inicial']+$despesa[9]['inicial']+$despesa[10]['inicial'];
  $somador_XI_atualizada    = $despesa[1]['atualizada'] + $despesa[5]['atualizada']+$despesa[8]['atualizada']+$despesa[9]['atualizada']+$despesa[10]['atualizada'];
  $somador_XI_nobimestre  = $despesa[1]['bimestre'] + $despesa[5]['bimestre']+$despesa[8]['bimestre']+$despesa[9]['bimestre']+$despesa[10]['bimestre'];
  $somador_XI_atebimestre = $despesa[1]['exercicio'] + $despesa[5]['exercicio']+$despesa[8]['exercicio']+$despesa[9]['exercicio']+$despesa[10]['exercicio'];
  
  $somador_XII_valor    = 0;
  // valores
  $somador_XIII_valor    = 0;
  //
  $somador_XIV_valor    = 0;
  //
  $somador_XV_valor    = 0;
  //
  $somador_XVI_valor    = 0;
  //
  $somador_XVII_valor    = 0;
  //
  $somador_XVIII_valor    = 0;
  //
  $somador_XIX_valor    = 0;
  //
  
  //--------------------------------------------------------------------------------------------------
  // RecordSets
  /*
  $sql_dotacao = db_dotacaosaldo(8,1,4,true,' o58_instit in('.str_replace('-',', ',$db_selinstit).') ',$anousu,$dt_ini,$dt_fin,'8','0',true);
  $sql = " select(o58_elemento||'00') as o58_elemento,
  o56_descr,
  dot_ini,
  atual,
  suplementado_acumulado,
  reduzido_acumulado,
  liquidado,
  liquidado_acumulado,
  conplano.*,
  o58_codigo as recurso,
  o58_codigo,
  o58_funcao,
  o58_subfuncao,
  o53_descr
  from($sql_dotacao) as x
  inner join conplano on c60_anousu = $anousu and substr(conplano.c60_estrut,1,13)=x.o58_elemento
  ";
  $result_desp = db_query($sql);
  
  
  
  
  
  $result_bal = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
  //db_criatabela($result_bal);
  //exit;
  // nao e usada
  
  //placeholder111//placeholder111//placeholder111//
  */
  // recorset usados para controle de rp inscritos em exec.anteriores vinculados a educao
  // o unico valor usado  o valor cancelado em exercicio atual
  
  // exit;
  /*
  $INTERFERENCIA_MDE = 0;
  $INTERFERENCIA_FUNDEF= 0;
  $INTERFERENCIA_FUNDEF_DEMAIS = 0;
  @db_query("drop table work_pl");
  $result_bal_mde = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
  for ($i=0; $i<pg_numrows($result_bal_mde); $i++) {
    db_fieldsmemory($result_bal_mde,$i);
    if (in_array($estrutural,$desp_ef)) {
      $INTERFERENCIA_MDE += $saldo_final ;
    }
    if (in_array($estrutural,$desp_pgt)) {
      $INTERFERENCIA_FUNDEF += $saldo_final ;
    }
    if (in_array($estrutural,$desp_pgto)) {
      $INTERFERENCIA_FUNDEF_DEMAIS += $saldo_final ;
    }
  }
  @db_query("drop table work_pl");
  $data_inicial = $anousu."-01-01";
  $result_bal_acumulado = db_planocontassaldo_matriz($anousu,$data_inicial,$dt_fin,false,' c61_instit in ('.str_replace('-',', ',$db_selinstit)   .' ) ');
  $INTERFERENCIA_MDE_AC = 0;
  $INTERFERENCIA_FUNDEF_AC= 0;
  $INTERFERENCIA_FUNDEF_DEMAIS_AC =0;
  for ($i=0; $i<pg_numrows($result_bal_acumulado); $i++) {
    db_fieldsmemory($result_bal_acumulado,$i);
    if (in_array($estrutural,$desp_ef)) {
      $INTERFERENCIA_MDE_AC += $saldo_final ;
    }
    if (in_array($estrutural,$desp_pgt)) {
      $INTERFERENCIA_FUNDEF_AC += $saldo_final ;
    }
    if (in_array($estrutural,$desp_pgto)) {
      $INTERFERENCIA_FUNDEF_DEMAIS_AC += $saldo_final;
    }
  }
  
  */
  //--------------------------------------------------------------------------------------------------
  
  // se arquivo no for incluido por outro relatorio
  
  if (!isset($arqinclude)) {
    // se este arquivo no esta incluido por outro
    
    $tipo_mesini = 1;
    $tipo_mesfim = 1;
    $perini = $dt_ini;
    $perfin = $dt_fin;
    
    $xinstit = split("-",$db_selinstit);
    $resultinst = db_query("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
    $descr_inst = '';
    $xvirg = '';
    $flag_abrev = false;
    for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
      db_fieldsmemory($resultinst,$xins);
      if (strlen(trim($nomeinstabrev)) > 0) {
        $descr_inst .= $xvirg.$nomeinstabrev;
        $flag_abrev  = true;
      } else {
        $descr_inst .= $xvirg.$nomeinst;
      }
      
      $xvirg = ', ';
    }
    
    if ($flag_abrev == false) {
      if (strlen($descr_inst) > 42) {
        $descr_inst = substr($descr_inst,0,100);
      }
    }
    
    $head1 = $descr_inst;
    $head2 = "RELATRIO RESUMIDO DA EXECUO ORAMENTRIA";
    $head3 = "DEMONSTRATIVO DE RECEITAS E DESPESAS COM DESENVOLVIMENTO E MANUTENO DO ENSINO - MDE";
    $head4 = "ORAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
    $txt = strtoupper(db_mes('01'));
    $dt  = split("-",$dt_fin);
    $txt.= "  ".strtoupper(db_mes($dt[1]))." $anousu/BIMESTRE ";
    ;
    $dt  = split("-",$dt_ini);
    $txt.= strtoupper(db_mes($dt[1]))."-";
    $dt  = split("-",$dt_fin);
    $txt.= strtoupper(db_mes($dt[1]));
    $head5 = "$txt";
    
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $total = 0;
    $pdf->setfillcolor(235);
    $troca = 1;
    $alt = 4;
    
    $pagina = 1;
    $tottotal = 0;
    $pagina = 0;
    $n1 =5;
    $n2=10;
    
    $pdf->addpage();
    $pdf->setfont('arial','',6);
    $pdf->cell(90,$alt,"Lei 9.394/96, Atr. 72 - Anexo X",0,0,"L",0);
    $pdf->cell(100,$alt,"R$",0,1,"R",0);
    
    
    $pdf->setfont('arial','',6);
    $pdf->cell(90,($alt*2),"RECEITAS",'TBR',0,"L",0);
    $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
    $pdf->cell(20,($alt*2),"ATUALIZADA(a)",1,0,"C",0);
    $pdf->cell(60,($alt),"RECEITAS ATUALIZADAS",'TB',1,"C",0);
    //br
    $pdf->setX(140);
    $pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
    $pdf->cell(20,$alt,"At o Bimestre(b)",1,0,"C",0);
    $pdf->cell(20,$alt,"% (b/a)",'TB',0,"C",0);
    $pdf->ln();
    
    
    for ($linha=1; $linha<=24; $linha++) {
      $pdf->cell(90,$alt,$receita[$linha]['txt'],'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($receita[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($receita[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($receita[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($receita[$linha]['exercicio'],'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(($receita[$linha]['exercicio']*100)/$receita[$linha]['atualizada'],'f'),0,0,"R",0);
      $pdf->Ln();
    }
    
    //---
    $somador_VI_inicial           = $receita[1]['inicial']+ $receita[15]['inicial'] - $receita[11]['inicial'] ;
    $somador_VI_atualizada    = $receita[1]['atualizada']+$receita[15]['atualizada']- $receita[11]['atualizada'];
    $somador_VI_nobimestre  = $receita[1]['bimestre'] + $receita[15]['bimestre']  - $receita[11]['bimestre'];
    $somador_VI_atebimestre = $receita[1]['exercicio'] + $receita[15]['exercicio']  - $receita[11]['exercicio'];
    
    $pdf->setfont('arial','',6);
    $pdf->cell(90,$alt,"TOTAL DAS RECEITAS (VI) = (I+III-II)",'TBR',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($somador_VI_inicial,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_VI_atualizada,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_VI_nobimestre,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_VI_atebimestre,'f'),'TBR',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($somador_VI_atebimestre*100)/$somador_VI_atualizada,'f'),'TB',0,"R",0);
    $pdf->Ln();
    
    
    
    // header das despesas
    $pdf->Ln(3);
    $pdf->setfont('arial','',6);
    $pdf->cell(90,($alt*2),"DESPESAS COM ENSINO POR VINCULAO",'TBR',0,"L",0);
    $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
    $pdf->cell(20,($alt*2),"ATUALIZADA(c)",1,0,"C",0);
    $pdf->cell(60,($alt),"DESPESAS LIQUIDADAS",'TB',1,"C",0);
    //br
    $pdf->setX(140);
    $pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
    $pdf->cell(20,$alt,"At o Bimestre(d)",1,0,"C",0);
    $pdf->cell(20,$alt,"% (d/c)",'TB',0,"C",0);
    $pdf->ln();
    
    
    for ($linha=1; $linha<=10; $linha++) {
      $pdf->cell(90,$alt,$despesa[$linha]['txt'],'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($despesa[$linha]['inicial'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($despesa[$linha]['atualizada'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($despesa[$linha]['bimestre'],'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($despesa[$linha]['exercicio'],'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(($despesa[$linha]['exercicio']*100)/$despesa[$linha]['atualizada'],'f'),0,0,"R",0);
      $pdf->Ln();
    }
    $pdf->cell(90,$alt,"TOTAL DAS DESPESAS COM ENSINO (XI) ",'TBR',0,"l",0);
    $pdf->cell(20,$alt,db_formatar($somador_XI_inicial,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_XI_atualizada,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_XI_nobimestre,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($somador_XI_atebimestre,'f'),'TBR',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($somador_XI_atebimestre*100/$somador_XI_atualizada),'f'),'TB',0,"R",0);
    $pdf->ln();
    
    
    
    //-----------------------------------
    
    $ganho_fundef = 0;
    if ($somador_II_atebimestre > $somador_IV_atebimestre ) {
      $somador_XII_valor =  $somador_II_atebimestre - $somador_IV_atebimestre ;
    } else {
      $ganho_fundef =  $somador_IV_atebimestre - $somador_II_atebimestre ;
    }
    $pdf->Ln(3);
    $pdf->setfont('arial','',6);
    $pdf->cell(150,$alt,'PERDA/GANHO NAS TRANSFERNCIAS DO FUNDEF','TBR',0,"C",0);
    $pdf->cell(40,$alt,'VALOR','TB',1,"R",0);
    $pdf->cell(150,$alt,'[se II > IV] = Perda nas transferncias do FUNDEF (XII)',0,0,"L",0);
    $pdf->cell(40,$alt,db_formatar($somador_XII_valor,'f'),'L',1,"R",0);
    $pdf->cell(150,$alt,'[se II < IV] = Ganho nas transferncias do FUNDEF','B',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($ganho_fundef,'f'),'BL',1,"R",0);
    
    // -----------------------------------------------------------------------
    $pdf->Ln(3);
    $pdf->setfont('arial','',6);
    $pdf->cell(150,$alt,'DEDUES DA DESPESA','TBR',0,"C",0);
    $pdf->cell(40,$alt,'VALOR','TB',1,"R",0);
    
    //placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//
    // se houver ganho nas transferencias para o funde, e a variavel estiver zerado, sera usado o ganho
    // o pad deduz o ganho total
    
    if ($ganho_fundef > 0  && $GANHO_COMPLEM_FUNDEF+0==0) {
      $GANHO_COMPLEM_FUNDEF = $ganho_fundef;
    }
    if (( $GANHO_COMPLEM_FUNDEF+0) > 0) {
      $GANHO_COMPLEM_FUNDEF = $GANHO_COMPLEM_FUNDEF+0;
      
    }
    
    $pdf->cell(150,$alt,'PARCELA DO GANHO/COMPLEMENTAO DO FUNDEF APLICADA NO EXERCCIO (XIII)',0,0,"L",0);
    $pdf->cell(40,$alt,db_formatar($GANHO_COMPLEM_FUNDEF,'f'),'L',1,"R",0);
    $somador_XIII_valor += $GANHO_COMPLEM_FUNDEF;
    $somador_XVI_valor += $GANHO_COMPLEM_FUNDEF;
    
    $pdf->cell(150,$alt,'RESTOS A PAGAR INSCRITOS NO EXERCCIO SEM DISPONIBILIDADE FINANCEIRA VINCULADA DE RECURSOS PRPRIOS',0,0,"L",0);
    $pdf->cell(40,$alt,'','L',1,"R",0);
    
    $pdf->setX(20);
    $pdf->cell(140,$alt,'Despesas com Ensino Fundamental (XIV)',0,0,"L",0);
    $pdf->cell(40,$alt,db_formatar($DESP_ENS_FUNDAMENTAL,'f'),'L',1,"R",0);
    $somador_XIV_valor += $DESP_ENS_FUNDAMENTAL;
    $somador_XVI_valor +=$DESP_ENS_FUNDAMENTAL;
    
    $pdf->setX(20);
    $pdf->cell(140,$alt,'Despesas com Educao infantil em Creches e Pr-Escolas',0,0,"L",0);
    $pdf->cell(40,$alt,db_formatar($DESP_ENS_INFANTIL,'f'),'L',1,"R",0);
    $somador_XVI_valor += $DESP_ENS_INFANTIL;
    
    $pdf->cell(150,$alt,'DESPESAS VINCULADAS AO SUPERVIT FINANCEIRO DO GANHO/COMPLEMENTAO DO FUNDEF DO EXERCCIO ANTERIOR (XV)',0,0,"L",0);
    $pdf->cell(40,$alt,db_formatar($DESP_VINC_SUPERAVIT,'f'),'L',1,"R",0);
    $somador_XV_valor += $DESP_VINC_SUPERAVIT;
    $somador_XVI_valor += $DESP_VINC_SUPERAVIT;
    
    $pdf->cell(150,$alt,'TOTAL (XVI)','TRB',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($somador_XVI_valor,'f'),'TB',1,"R",0);
    $pdf->ln();
    
    
    // page-break
    
    $pdf->cell(150,$alt,'Continua na pgina 2',0,0,"L",0);
    $pdf->addpage();
    
    $pdf->cell(150,$alt,'Continuao da pgina 1',0,0,"L",0);
    $pdf->Ln();
    
    
    $pdf->Ln(3);
    $pdf->setfont('arial','',6);
    $pdf->cell(90,($alt),"CONTROLE DE RESTOS A PAGAR ",'TR',0,"C",0);
    $pdf->cell(20,($alt*2),"Mnima (e)",1,0,"C",0);
    $pdf->cell(20,($alt*2),"Apurada(f)",1,0,"C",0);
    $pdf->cell(60,($alt),"RESTOS A PAGAR",'TB',1,"C",0);
    //br
    $pdf->cell(90,($alt),"VINCULADOS A EDUCAO",'B',0,"C",0);
    $pdf->setX(140);
    $pdf->cell(40,$alt,"Inscritos em 31/12/".($anousu-1),1,0,"C",0);
    $pdf->cell(20,$alt,"Cancelados em $anousu",'TB',0,"C",0);
    $pdf->ln();
    
    $pdf->cell(90,$alt,'RP DE DESPESAS COM MANUTENO E DESENVOLVIMENTO DO ENSINO','0',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($RP_MDE_MINIMA,'f'),'RL',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($RP_MDE_APURADA,'f'),'RL',0,"R",0);
    $pdf->cell(40,$alt,db_formatar($m_inscricao_rp_mde['valor'],'f'),'RL',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_cancelamento_rp_mde['valor'],'f'),'L',1,"R",0);
    
    $pdf->cell(90,$alt,'RP DE DESPESAS COM ENSINO FUNDAMENTAL',0,0,"L",0);
    $pdf->cell(20,$alt,db_formatar($RP_FUNDEF_MINIMA,'f'),'RL',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($RP_FUNDEF_APURADA,'f'),'RL',0,"R",0);
    $pdf->cell(40,$alt,db_formatar($m_inscricao_rp_fundef['valor'],'f'),'RL',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($m_cancelamento_rp_fundef['valor'],'f'),'L',1,"R",0);
    
    // compensao de RP
    $pdf->cell(150,$alt,"COMPENSAO DE RESTOS A PAGAR CANCELADOS EM ".$anousu,'TR',0,"C",0);
    $pdf->cell(20,$alt,'VALOR','TB',0,"L",0);
    $pdf->cell(20,$alt,0,'TB',1,"R",0);
    // --
    $pdf->cell(150,$alt,'MANUTENO E DESENVOLVIMENTO DO ENSINO (XVII) ','TR',0,"L",0);
    @$pdf->cell(40,$alt,db_formatar($COMPENSACAO_RP_MDE,'f'),'0',1,"R",0);
    $somador_XVII_valor = $COMPENSACAO_RP_MDE;
    
    $pdf->cell(150,$alt,'ENSINO FUNCAMENTAL (XVIII)','BR',0,"L",0);
    @$pdf->cell(40,$alt,db_formatar($COMPENSACAO_RP_FUNDEF,'f'),'B',1,"R",0);
    $somador_XVIII_valor = $COMPENSACAO_RP_FUNDEF;
    
    //--------------------------------------------
    // total das despesas consideradas para find do limite
    // VII+VIII+IXI+XII)-XVI]
    $somador_XIX_valor =
    ($somador_VII_atebimestre
    + $somador_VIII_atebimestre
    + $somador_IX_atebimestre
    + $somador_XII_valor ) - $somador_XVI_valor ;
    $pdf->Ln(3);
    $pdf->cell(150,$alt,'TOTAL DAS DESPESAS CONSIDERADAS PARA FINS DE LIMITE CONSTITUCIONAL (XIX) = [(VII+VIII+IX+XII)-XVI]','TBR',0,"L",0);
    $pdf->cell(40,$alt,db_formatar($somador_XIX_valor,'f'),'TB',1,"R",0);
    
    ///
    @$total_A = (($somador_XIX_valor-$somador_XVII_valor)/$somador_I_atebimestre) * 100;
    @$total_B = (((($somador_VII_atebimestre +$somador_IX_atebimestre +$somador_XII_atebimestre)-($somador_XIII_valor+$somador_XIV_valor+$somador_XV_valor+$somador_XVIII_valor)))/($somador_I_atebimestre *0.25)) * 100;
    // @$total_C =  ($somador_X_atebimestre / $somador_IV_atebimestre) * 100;
    @$total_C =  ($somador_X_atebimestre *100 )/ $somador_IV_atebimestre;
    
    $pdf->Ln(3);
    $pdf->cell(170,$alt,'TABELA DE CUMPRIMENTO DOS LIMITES CONSTITUCIONAIS','TBR',0,"L",0);
    $pdf->cell(20,$alt,'%','TB',1,"C",0);
    $pdf->cell(170,$alt,'MNIMO DE 25% DAS RECEITAS RESULTANTES DE IMPOSTOS NA MANUTENO E DESENVOLVIMENTO DO ENSINO [(XIX-XVII)/I]','R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($total_A,'f'),'0',1,"R",0);
    $pdf->cell(170,$alt,'Caput do artigo 212 da CF/88','R',0,"L",0);
    $pdf->cell(20,$alt,'','0',1,"C",0);
    
    $pdf->cell(170,$alt,'MNIMO DE 60% DOS RECURSOS COM MDE NO ENSINO FUNDAMENTAL [(VII+IX+XII)-(XIII+XIV+XV+XVIII)]/(I x 0,25) ','R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($total_B,'f'),'0',1,"R",0);
    $pdf->cell(170,$alt,'Caput do artigo 60 do ADCT da CF/88','R',0,"L",0);
    $pdf->cell(20,$alt,'','0',1,"C",0);
    
    $pdf->cell(170,$alt,'MNIMO DE 60% DO FUNDEF NA REMUNERAO DO MAGISTRIO ENSINO FUNDAMENTAL (X/IV) ','R',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($total_C,'f'),'0',1,"R",0);
    $pdf->cell(170,$alt,'paragrafo 5, do artigo 60 do ADCT da CF/88','BR',0,"L",0);
    $pdf->cell(20,$alt,'','B',1,"C",0);
    
    //placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111///
    //placeholder111// saldo financeiro do fundef
    
    $pdf->Ln(3);
    
    $pdf->cell(90,($alt*2),'SALDO FINANCEIRO DO FUNDEF','TBR',0,"L",0);
    $pdf->cell(60,$alt,"Em 31/dez/".($anousu-1),'TBR',0,"C",0);
    $pdf->cell(40,$alt,"At o Bimestre",'TB',1,"C",0);
    $pdf->setX(100);
    $pdf->cell(60,$alt,db_formatar($m_saldo_financeiro_fundef['valor_inscricao'],'f'),'TBR',0,"R",0);
    $pdf->cell(40,$alt,db_formatar($m_saldo_financeiro_fundef['valor_atual'],'f'),'TB',1,"R",0);
    
    /// lista despe
    $pdf->Ln(3);
    $pdf->setfont('arial','',6);
    $pdf->cell(90,($alt),"DESPESAS COM MANUTENO E DESENVOLVIMENTO DO ENSINO",'T',0,"C",0);
    $pdf->cell(20,($alt*2),"INICIAL",1,0,"C",0);
    $pdf->cell(20,($alt*2),"ATUALIZADA(h)",1,0,"C",0);
    $pdf->cell(60,($alt),"DESPESAS LIQUIDADAS",'TB',1,"C",0);
    //br
    $pdf->cell(90,($alt),"POR SUBFUNO",'B',0,"C",0);
    $pdf->setX(140);
    $pdf->cell(20,$alt,"No Bimestre",1,0,"C",0);
    $pdf->cell(20,$alt,"At o Bimestre(i)",1,0,"C",0);
    $pdf->cell(20,$alt,"% (i/h)",'TB',0,"C",0);
    $pdf->ln();
    
    
    //
    // lista despesas por subfuno
    // lista ensino fundamental + reserva de contingencia
    // lista a educao infantil
    $tot_dot_ini=0;
    $tot_dot_atual=0;
    $tot_dot_liquidado=0;
    $tot_dot_liquidado_acumulado=0;
    
    for ($i=0; $i< pg_numrows($result_subfunc); $i++) {
      db_fieldsmemory($result_subfunc,$i);
      
      $vatual = $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
      $atual = $dot_ini + ($suplementado_acumulado-$reduzido_acumulado);
      $pdf->cell(90,$alt,"$o53_descr",'R',0,"L",0);
      $pdf->cell(20,$alt,db_formatar($dot_ini ,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($vatual  ,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar($liquidado,'f'),'R',0,"R",0);
      $pdf->cell(20,$alt,db_formatar(($liquidado_acumulado ),'f'),'R',0,"R",0);
      @$pdf->cell(20,$alt,db_formatar(((($liquidado_acumulado )*100)/($atual)),'f'),'0',0,"R",0);
      $pdf->Ln();
      
      $tot_dot_ini       += $dot_ini;
      $tot_dot_atual     += $vatual;
      $tot_dot_liquidado += $liquidado;
      $tot_dot_liquidado_acumulado += $liquidado_acumulado;
      
      
    }
    $pdf->cell(90,$alt,"TOTAL DAS DESPESAS COM ENSINO",'TBR',0,"L",0);
    $pdf->cell(20,$alt,db_formatar($tot_dot_ini ,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($tot_dot_atual, 'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($tot_dot_liquidado ,'f'),'TBR',0,"R",0);
    $pdf->cell(20,$alt,db_formatar($tot_dot_liquidado_acumulado,'f'),'TBR',0,"R",0);
    @$pdf->cell(20,$alt,db_formatar(($tot_dot_liquidado_acumulado *100)/$tot_dot_atual,'f'),'TB',0,"R",0);
    $pdf->Ln();
    
    
    //placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//placeholder111//
    $pdf->cell(90,$alt,"FONTE: Contabilidade",0,0,"L",0);
    
    //assinaturas
    $pdf->Ln(30);
    
    assinaturas($pdf, $classinatura,'LRF');
    
    
    
    $pdf->Output();
    
  }
  // end isset(arqinclude)
  

?>
