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

include("libs/db_utils.php");
include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_orcparamrelnota_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_empempenho_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;


$iUsuInstit           = db_getsession('DB_instit');

$sSqlInstituicaoRPPS  = "select codigo ";
$sSqlInstituicaoRPPS .= "  from db_config ";
$sSqlInstituicaoRPPS .= " where (db21_tipoinstit in (5,6) or prefeitura is true ) ";
$sSqlInstituicaoRPPS .= "   and codigo = {$iUsuInstit}";
$rsUsuInstit          = db_query($sSqlInstituicaoRPPS);
$iNumRowsUsuInstit    = pg_num_rows($rsUsuInstit);
$iCodigo              = pg_result($rsUsuInstit,0);

if($iNumRowsUsuInstit == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=O usuário deve ser da instituição RPPS ou Prefeitura para visualizar o relatório');
}

$rsInstit = pg_query(" select codigo,nomeinst from db_config where db21_tipoinstit in (5,6) ");

$oInstit  = db_utils::fieldsMemory($rsInstit,0);

$head2 =  $oInstit->nomeinst;

$head3 = "BALANÇO PATRIMONIAL DO REGIME PRÓPRIO DE PREVIDÊNCIA SOCIAL";
if($mes == 1){
$head4 = "JANEIRO DE ".db_getsession("DB_anousu");
}else{
$head4 = "JANEIRO A ".strtoupper(db_mes($mes))." DE ".db_getsession("DB_anousu");
}
$anousu  = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.'01'.'-'.'01';
$datafin = db_getsession("DB_anousu").'-'.$mes.'-'.date('t',mktime(0,0,0,$mes,'01',db_getsession("DB_anousu")));

$iInstit=$oInstit->codigo;
$v_passivo_lucros_prejuizos_ac =0;
$aDefictDiminui="";

$aVariacoesPatrimonias = db_varPatrimoniaisRpps($anousu,$dataini,$datafin,$iInstit);
$aDefict=$aVariacoesPatrimonias['TotaisAtivo']['DeficitPatrimonial'];
$aSuperavit=$aVariacoesPatrimonias['TotaisPassivo']['SuperavitPatrimonial'];

if (trim($aDefict) <> "-") {
  
   $aDefictDiminui                ="-";
   $v_passivo_lucros_prejuizos_ac = $aDefict;
}
if (trim($aSuperavit) <> "-") {
  
  $aDefictDiminui                = "";
  $v_passivo_lucros_prejuizos_ac = $aSuperavit;
}

db_query('drop table work_pl');

$somatorio_receita_ini      = 0;
$somatorio_receita_exec     = 0;

//---INICIO


$orcparamrel     = new cl_orcparamrel;
$clorcparamrelnota = new cl_orcparamrelnota;
$classinatura = new cl_assinatura;
$clempresto   = new cl_empresto;
/*
 *  se ativar debuga, o relatorio ira montar uma tabela html
 *  com os parametros abertos
 */
$debuga = "false"; 

// pesquisa notas explicativas
$res = $clorcparamrelnota->sql_record($clorcparamrelnota->sql_query("3",db_getsession("DB_anousu"),"o42_nota"));
if ($clorcparamrelnota->numrows > 0 ){
    db_fieldsmemory($res,0);
}


$where = "c61_instit = ".$oInstit->codigo;

$result = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','true');
//db_criatabela($result);


// Somadores Ativo
   //-Financeiro

$v_ativo_financeiro            =0;

$v_ativo_disponivel            =0;

$v_ativo_caixa                 =0; 
$v_ativo_banco_movimento       =0;


$v_ativo_creditos_circulacao   =0;

$v_ativo_credito_receber       =0;
$v_ativo_devedores_ent_ag      =0;
$v_ativo_adiant_concedido      =0;
$v_ativo_dep_real_curto_prazo  =0;
$v_ativo_val_trans_real        =0;


$v_ativo_bens_val_circulacao   =0;
$v_ativo_titulos_valores       =0;

$v_ativo_invest_rpps           =0;

$v_ativo_invest_seg_rend_fixa  =0;
$v_ativo_invest_seg_rend_var   =0;
$v_ativo_invest_seg_imov       =0;
$v_ativo_titulos_val_imob      =0;
$v_ativo_taxa_administ_rpps    =0;
$v_ativo_emprest_rec_prev_rec  =0;
$v_ativo_provisao_perda_invest =0;

//-permanente (não financeiro)
$v_ativo_permanente_nao_financ =0;
$v_ativo_bens_valor_circula    =0;
$v_ativo_estoque               =0;
$v_ativo_valor_pend_curto_prazo=0;
$v_ativo_custo_desp_pagos_antec=0;
$v_ativo_divida_ativa          =0;
$v_ativo_cred_inscr_div_ativa  =0;
$v_ativo_real_longo_prazo      =0;
$v_ativo_dep_real_longo_prazo  =0;
$v_ativo_cred_real_logo_prazo  =0;
$v_ativo_permanente            =0;
$v_ativo_imobilizado           =0;

//-ativo real
$v_ativo_real                  =0;

//-compensado
$v_ativo_compensado            =0;
$v_ativo_exec_orc_receita      =0;
$v_ativo_fixa_orc_despesa      =0;
$v_ativo_exec_prog_financeira  =0;
$v_ativo_desp_div_estados_munic=0;
$v_ativo_exec_restos_pagar     =0;
$v_ativo_compens_ativas_diver  =0;

//-total
$v_ativo_total  =0;


//passivo
//-financeiro
$v_passivo_financeiro          =0;
$v_passivo_depositos           =0;
$v_passivo_consignacoes        =0;
$v_passivo_recursos_uniao      =0;
$v_passivo_depos_diversas_orig =0;
$v_passivo_obrigacoes_circula  =0;
$v_passivo_obrigacoes_pagar    =0;
$v_passivo_credores_entidade_ag=0;
$v_passivo_valor_transito_exig =0;

//-permanente (não financeiro)
$v_passivo_dep_exig_long_prazo =0;
$v_passivo_recurso_vinculado   =0;

$v_passivo_obr_exig_long_prazo =0;

$v_passivo_obr_legal_tributaria=0;
$v_passivo_obr_pagar           =0;
$v_passivo_prov_matematica_prev=0;

//-passivo real
$v_passivo_real                =0;
$v_passivo_patrimonio_liquido  =0;

$v_passivo_patrimonio_capital  =0;
$v_passivo_reservas            =0;

//-compensado
$v_passivo_compensado          =0;

$v_passivo_prev_orc_receita    =0;
$v_passivo_exec_orc_despesa    =0;
$v_passivo_exec_prog_financeira=0;
$v_ativo_desp_div_estados_munic=0;
$v_passivo_exec_restos_pagar   =0;
$v_passivo_compens_ativas_diver=0;

//fim ativo-passivo
$v_ativo_total                 =0;
$v_passivo_total               =0;


if ($debuga=="true"){
   echo "<table border=1 align=center>";
} 

//-------- variável 

//db_criatabela($result);exit;
for ($i = 0; $i < pg_num_rows($result); $i++) {
   
  db_fieldsmemory($result,$i);
  //****ativo****//
  $negativo = "-";
  
  //-conta caixa
  if ($estrutural == "111110000000000"){
       $v_ativo_caixa = $saldo_final;
       if (strtoupper($sinal_final)=="C"){
       $v_ativo_caixa=$negativo.$v_ativo_caixa;
       }
   }
  
  //-conta banco movimento
  if ($estrutural == "111120000000000"){
       $v_ativo_banco_movimento = $saldo_final;
       if (strtoupper($sinal_final)=="C"){
         $v_ativo_banco_movimento=$negativo.$v_ativo_banco_movimento;
          }
  
   }
  
  //-créditos a receber 
  if ($estrutural == "112100000000000"){
      $v_ativo_credito_receber = $saldo_final; 
      if (strtoupper($sinal_final)=="C"){
          $v_ativo_credito_receber=$negativo.$v_ativo_credito_receber;
      }
  
  }
  
  //-devedores-ent e ag
  if ($estrutural == "112200000000000"){
      $v_ativo_devedores_ent_ag = $saldo_final;
      if (strtoupper($sinal_final)=="C"){
          $v_ativo_devedores_ent_ag=$negativo.$v_ativo_devedores_ent_ag;
       }
  
  }
  
  //-adiantamentos concedidos
  if ($estrutural == "112400000000000"){
      $v_ativo_adiant_concedido = $saldo_final;
      if (strtoupper($sinal_final)=="C"){
          $v_ativo_adiant_concedido=$negativo.$v_ativo_adiant_concedido;
        }
  
  
  }
  
  //-depósitos realizáveis a curto prazo
  if ($estrutural == "112500000000000"){
      $v_ativo_dep_real_curto_prazo = $saldo_final;
      if (strtoupper($sinal_final)=="C"){
         $v_ativo_dep_real_curto_prazo =$negativo.$v_ativo_dep_real_curto_prazo;
      }
  
  
  }
  
  //-valores em trânsito realizáveis
  if ($estrutural == "112600000000000"){
      $v_ativo_val_trans_real = $saldo_final;
      if (strtoupper($sinal_final)=="C"){
          $v_ativo_val_trans_real =$negativo.$v_ativo_val_trans_real;
        }
  
  }
  
  //-títulos e valores
  if ($estrutural == "113200000000000"){
      $v_ativo_titulos_valores = $saldo_final;
      if (strtoupper($sinal_final)=="C"){
          $v_ativo_titulos_valores =$negativo.$v_ativo_titulos_valores;
        }
  
  
  
  }
  
  //-investimento no segmento renda fixa
  
  
  /*
   * bloco de alteração somente se o ano maior 2010
   * se retornar o numero das contas novas 1111401 ou 1111406
   * que deverá ser demonstrada na linha  'Investimentos no Segmento de Renda Fixa'
   */
  if ($anousu >= 2010) {
    
    if ($estrutural == "111140100000000") {
    
      if (strtoupper($sinal_final) == "C") { 
        $saldo_final *= -1;     
      }
      $v_ativo_invest_seg_rend_fixa += $saldo_final;
    } 
  } else {
  	
    if ($estrutural == "115100000000000") {
  
  	  if (strtoupper($sinal_final) == "C") {
  	    $saldo_final *= -1;     
  	  }
  	  $v_ativo_invest_seg_rend_fixa += $saldo_final;
  	   
  	}
  }
  
  
  
  if ($anousu >= 2010) {
  //-investimento no segmento renda variável
    if ($estrutural == "111140200000000") {
        $v_ativo_invest_seg_rend_var = $saldo_final;
        if (strtoupper($sinal_final) == "C") {
            $v_ativo_invest_seg_rend_var =$negativo.$v_ativo_invest_seg_rend_var;
          }
    
    
    } 
    
  } else {
  
    if ($estrutural == "115200000000000") {
        $v_ativo_invest_seg_rend_var = $saldo_final;
        if (strtoupper($sinal_final) == "C"){
            $v_ativo_invest_seg_rend_var = $negativo.$v_ativo_invest_seg_rend_var;
          }
    }
  
  }
  if ($anousu >= 2010) {
  //-investimentos segmento imóveis
    if ($estrutural == "111140300000000") {
        $v_ativo_invest_seg_imov = $saldo_final;
        if (strtoupper($sinal_final) == "C"){
            $v_ativo_invest_seg_imov = $negativo.$v_ativo_invest_seg_imov;
           }
    }
  } else {  
    if ($estrutural == "115300000000000") {
        $v_ativo_invest_seg_imov = $saldo_final;
        if (strtoupper($sinal_final) == "C") {
            $v_ativo_invest_seg_imov = $negativo.$v_ativo_invest_seg_imov;
           }
    }
  }
  
  if ($anousu >= 2010) {
  //-títulos e valores imobiliários
    if ( ($estrutural == "111140400000000") || ($estrutural == "111140500000000") ) {
        $v_ativo_titulos_val_imob = $saldo_final;
       if (strtoupper($sinal_final) == "C") {
           $v_ativo_titulos_val_imob = $negativo.$v_ativo_titulos_val_imob;
          }
    }
  } else {  
    if ($estrutural == "115400000000000") {
        $v_ativo_titulos_val_imob = $saldo_final;
       if (strtoupper($sinal_final) == "C") {
           $v_ativo_titulos_val_imob = $negativo.$v_ativo_titulos_val_imob;
          }
    }
  
  }
  
  
  if ($anousu >= 2010) {
  //-investimentos taxa administração rpps
      if ($estrutural == "111140600000000") {
           $v_ativo_taxa_administ_rpps = $saldo_final;
            if (strtoupper($sinal_final) == "C") {
              $v_ativo_taxa_administ_rpps = $negativo.$v_ativo_taxa_administ_rpps;
             }
      }
  } else {
      
      if ($estrutural == "115500000000000") {
           $v_ativo_taxa_administ_rpps = $saldo_final;
            if (strtoupper($sinal_final) == "C") {
              $v_ativo_taxa_administ_rpps = $negativo.$v_ativo_taxa_administ_rpps;
             }
      }
  }
  
  if ($anousu >= 2010) {
  //-empréstimo recursos previdenciários a receber
      if ($estrutural == "115000000000000") {
          $v_ativo_emprest_rec_prev_rec = $saldo_final;
          if (strtoupper($sinal_final) == "C") {
               $v_ativo_emprest_rec_prev_rec = $negativo.$v_ativo_emprest_rec_prev_rec;
            }
      }
  } else {  
      if ($estrutural == "115600000000000") {
          $v_ativo_emprest_rec_prev_rec = $saldo_final;
          if (strtoupper($sinal_final) == "C") {
               $v_ativo_emprest_rec_prev_rec = $negativo.$v_ativo_emprest_rec_prev_rec;
            }
      }
  }
  
  if ($anousu >= 2010) {
  //-provisão para perdas em investimentos
      if ($estrutural == "111149900000000") {
          $v_ativo_provisao_perda_invest = $saldo_final;
          if (strtoupper($sinal_final) == "C"){
             $v_ativo_provisao_perda_invest = $negativo.$v_ativo_provisao_perda_invest;
             }
      }
  } else {
      
      if ($estrutural == "115800000000000") {
          $v_ativo_provisao_perda_invest = $saldo_final;
          if (strtoupper($sinal_final) == "C"){
             $v_ativo_provisao_perda_invest = $negativo.$v_ativo_provisao_perda_invest;
             }
      }
  
  }
  
  //-ativo estoque
  if ($estrutural == "113100000000000") {
      $v_ativo_estoque = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_estoque = $negativo.$v_ativo_estoque;
        }
  }
  
  //-custos e despesas pagos antecipadamente
  if ($estrutural == "114100000000000") {
      $v_ativo_custo_desp_pagos_antec = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_custo_desp_pagos_antec = $negativo.$v_ativo_custo_desp_pagos_antec;
        }
  
  
  }
  
  //-créditos inscritos em dívida ativa
  if ($estrutural == "116100000000000") {
      $v_ativo_cred_inscr_div_ativa = $saldo_final;
      if (strtoupper($sinal_final) == "C") {
          $v_ativo_cred_inscr_div_ativa = $negativo.$v_ativo_cred_inscr_div_ativa;
         }
  
  
  }
  
  //-depósitos realizáveis longo prazo
  if ($estrutural == "121000000000000") {
      $v_ativo_dep_real_longo_prazo = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_dep_real_longo_prazo = $negativo.$v_ativo_dep_real_longo_prazo;
       }
  
  }
  
  //-créditos realizáveis a longo prazo
  if ($estrutural == "122000000000000") {
      $v_ativo_cred_real_logo_prazo = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_cred_real_logo_prazo = $negativo.$v_ativo_cred_real_logo_prazo;
         }
  
  
  }
  
  //-imobilizado
  if ($estrutural == "142000000000000") {
      $v_ativo_imobilizado  = $saldo_final;
      if (strtoupper($sinal_final) == "C") {
          $v_ativo_imobilizado = $negativo.$v_ativo_imobilizado;
        }
  }
  
  //-execução orçamentária da receita
  if ($estrutural == "191000000000000") {
      $v_ativo_exec_orc_receita = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_exec_orc_receita = $negativo.$v_ativo_exec_orc_receita;
       }
  
  }
  
  //-fixação orçamentária da despesa
  if ($estrutural == "192000000000000") {
      $v_ativo_fixa_orc_despesa = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
           $v_ativo_fixa_orc_despesa = $negativo.$v_ativo_fixa_orc_despesa;
       }
  
  
  }
  
  //-execução da programação financeira
  if ($estrutural == "193000000000000") {
      $v_ativo_exec_prog_financeira = $saldo_final;
      if (strtoupper($sinal_final) == "C") {
          $v_ativo_exec_prog_financeira = $negativo.$v_ativo_exec_prog_financeira;
      }
  
  
  }
  
  //-despesas e dívidas dos estado e municípios
  if ($estrutural == "194000000000000") {
      $v_ativo_desp_div_estados_munic = $saldo_final;
      if (strtoupper($sinal_final) == "C") {
          $v_ativo_desp_div_estados_munic = $negativo.$v_ativo_desp_div_estados_munic;
        }
  
  
  }
  
  //-execução de restos a pagar
  if ($estrutural == "195000000000000") {
      $v_ativo_exec_restos_pagar = $saldo_final;
      if (strtoupper($sinal_final) == "C"){
          $v_ativo_exec_restos_pagar = $negativo.$v_ativo_exec_restos_pagar;
       }
  
  
  }
  
  //-conpensações ativas diversas
  if ($estrutural == "199000000000000") {
      $v_ativo_compens_ativas_diver = $saldo_final;
      if (strtoupper($sinal_final) == "C") {
          $v_ativo_compens_ativas_diver = $negativo.$v_ativo_compens_ativas_diver;
         }
                     
  }
  
  
  
  //****passivo****//
  
  
  //-consignações
  if ($estrutural == "211100000000000") {
      $v_passivo_consignacoes = $saldo_final;
      if (strtoupper($sinal_final) == "D"){
          $v_passivo_consignacoes = $negativo.$v_passivo_consignacoes;
       }
  }
  
  //-recursos da união
  if ($estrutural == "211200000000000") {
      $v_passivo_recursos_uniao = $saldo_final;
      if (strtoupper($sinal_final) == "D") {
          $v_passivo_recursos_uniao = $negativo.$v_passivo_recursos_uniao;
       }
  
  }
  
  //-depósitos diversas origens
  if ($estrutural == "211400000000000"){
      $v_passivo_depos_diversas_orig = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_depos_diversas_orig =$negativo.$v_passivo_depos_diversas_orig;
       }
  
  
  }
  
  //-obrigações a pagar
  if ($estrutural == "212100000000000"){
      $v_passivo_obrigacoes_pagar = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_obrigacoes_pagar =$negativo.$v_passivo_obrigacoes_pagar;
       }
  
  }
  
  //-credores - entidades e agentes
  if ($estrutural == "212200000000000"){
      $v_passivo_credores_entidade_ag = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_credores_entidade_ag =$negativo.$v_passivo_credores_entidade_ag;
       }
  
  
  
  }
  
  //-valores em trânsito exigíveis
  if ($estrutural == "212600000000000"){
      $v_passivo_valor_transito_exig = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_valor_transito_exig =$negativo.$v_passivo_valor_transito_exig;
       }
  
  }
  
  //-recursos vinculados
  if ($estrutural == "221200000000000"){
      $v_passivo_recurso_vinculado = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
         $v_passivo_recurso_vinculado =$negativo.$v_passivo_recurso_vinculado;
        }
  
  }
  
  //-obrigações legais e tributárias
  if ($estrutural == "222300000000000"){
      $v_passivo_obr_legal_tributaria = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_obr_legal_tributaria =$negativo.$v_passivo_obr_legal_tributaria;
         }
  
  }
  
  //-obrigações a pagar
  if ($estrutural == "222400000000000"){
      $v_passivo_obr_pagar = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_obr_pagar =$negativo.$v_passivo_obr_pagar;
      }
  }
  
  //-provisões matemáticas previdenciárias
  if ($estrutural == "222500000000000"){
      $v_passivo_prov_matematica_prev = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_prov_matematica_prev =$negativo.$v_passivo_prov_matematica_prev;
       }
                    
  }
  
  //-patrimônio capital
  /**
   * caso o mes de emissão do relatório nao seja dezembro, devemos usar a conta 2.4.1.1 para apurar 
   * o valor da linha "patrimonio capital", nao está no fechamento contábil
   */
  $sEstruturalPatrimonioLiquido   = "240000000000000";
  if ($mes == 12) {
    $sEstruturalPatrimonioLiquido = "241000000000000";
  }
  if ($estrutural == $sEstruturalPatrimonioLiquido) {
    
    $v_passivo_patrimonio_capital = $saldo_final;
    if (strtoupper($sinal_final) == "D") {
        $v_passivo_patrimonio_capital = $negativo.$v_passivo_patrimonio_capital;
      }
  
  }
  
  //-reservas
  if ($estrutural == "242000000000000"){
      $v_passivo_reservas = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_reservas =$negativo.$v_passivo_reservas;
       }
  
  }
  
  
  //-previsão orçamentária da receita
  if ($estrutural == "291000000000000"){
      $v_passivo_prev_orc_receita = $saldo_final;
       if (strtoupper($sinal_final)=="D"){
           $v_passivo_prev_orc_receita =$negativo.$v_passivo_prev_orc_receita;
          }
  
  }
  
  //-execução orçamentária da despesa
  if ($estrutural == "292000000000000"){
      $v_passivo_exec_orc_despesa = $saldo_final;
       if (strtoupper($sinal_final)=="D"){
           $v_passivo_exec_orc_despesa =$negativo.$v_passivo_exec_orc_despesa;
        }
  }
  
  //-execução programação financeira
  if ($estrutural == "293000000000000"){
      $v_passivo_exec_prog_financeira = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_exec_prog_financeira =$negativo.$v_passivo_exec_prog_financeira;
        }
  
  }
  
  //-despesas e dívidas de estados e municípios
  if ($estrutural == "294000000000000"){
      $v_passivo_desp_div_estados_munic = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_desp_div_estados_munic =$negativo.$v_passivo_desp_div_estados_munic;
       }
  }
  
  //-execução de restos a pagar
  if ($estrutural == "295000000000000"){
      $v_passivo_exec_restos_pagar = $saldo_final;
       if (strtoupper($sinal_final)=="D"){
            $v_passivo_exec_restos_pagar =$negativo.$v_passivo_exec_restos_pagar;
        }
                      
  }
  
  //compensações passiva diversas
  if ($estrutural == "299000000000000"){
      $v_passivo_compens_ativas_diver = $saldo_final;
      if (strtoupper($sinal_final)=="D"){
          $v_passivo_compens_ativas_diver =$negativo.$v_passivo_compens_ativas_diver;
       }
  
  }


}
//---- /////////////////////////////// --------------------------------------
//**ativo**// 
$v_ativo_disponivel            = $v_ativo_caixa+$v_ativo_banco_movimento;

$v_ativo_creditos_circulacao   = $v_ativo_credito_receber  + $v_ativo_devedores_ent_ag     + 
                                 $v_ativo_adiant_concedido + $v_ativo_dep_real_curto_prazo + 
                                 $v_ativo_val_trans_real;

$v_ativo_bens_val_circulacao   = $v_ativo_titulos_valores;

$v_ativo_invest_rpps           = $v_ativo_invest_seg_rend_fixa + $v_ativo_invest_seg_rend_var  + 
                                 $v_ativo_invest_seg_imov      + $v_ativo_titulos_val_imob     + 
                                 $v_ativo_taxa_administ_rpps   + $v_ativo_emprest_rec_prev_rec + 
                                 $v_ativo_provisao_perda_invest;

//financeiro
$v_ativo_financeiro=$v_ativo_disponivel+$v_ativo_creditos_circulacao+$v_ativo_bens_val_circulacao+$v_ativo_invest_rpps;


$v_ativo_bens_valor_circula    = $v_ativo_estoque;

$v_ativo_valor_pend_curto_prazo= $v_ativo_custo_desp_pagos_antec;

$v_ativo_divida_ativa          = $v_ativo_cred_inscr_div_ativa;

$v_ativo_real_longo_prazo      = $v_ativo_dep_real_longo_prazo+$v_ativo_cred_real_logo_prazo;

$v_ativo_permanente            = $v_ativo_imobilizado;

$v_ativo_permanente_nao_financ = $v_ativo_bens_valor_circula + $v_ativo_valor_pend_curto_prazo + 
                                 $v_ativo_divida_ativa       + $v_ativo_real_longo_prazo       + $v_ativo_permanente;

//ativo real
$v_ativo_real                  = $v_ativo_financeiro + $v_ativo_permanente_nao_financ; 


//compensado
$v_ativo_compensado            = $v_ativo_exec_orc_receita     + $v_ativo_fixa_orc_despesa + 
                                 $v_ativo_exec_prog_financeira + $v_ativo_desp_div_estados_munic + 
                                 $v_ativo_exec_restos_pagar    + $v_ativo_compens_ativas_diver;

//**passivo**//


//passivo
//-financeiro

$v_passivo_depositos           = $v_passivo_consignacoes+$v_passivo_recursos_uniao+$v_passivo_depos_diversas_orig;

$v_passivo_obrigacoes_circula  = $v_passivo_obrigacoes_pagar+$v_passivo_credores_entidade_ag+$v_passivo_valor_transito_exig;

$v_passivo_financeiro          = $v_passivo_depositos+$v_passivo_obrigacoes_circula;


//-permanente (não financeiro)


$v_passivo_dep_exig_long_prazo = $v_passivo_recurso_vinculado;

$v_passivo_obr_exig_long_prazo = $v_passivo_obr_legal_tributaria+$v_passivo_obr_pagar+$v_passivo_prov_matematica_prev;

$v_passivo_permanente_nao_financ = $v_passivo_dep_exig_long_prazo+$v_passivo_obr_exig_long_prazo;

$v_passivo_lucros_prejuizos_ac = ($aDefictDiminui.$v_passivo_lucros_prejuizos_ac) - $v_passivo_reservas;



if ($mes == 12) {
  $v_passivo_patrimonio_capital = $v_passivo_patrimonio_capital + ( $v_passivo_lucros_prejuizos_ac * -1 );
}
//-passivo real
$v_passivo_real                = $v_passivo_financeiro+$v_passivo_permanente_nao_financ;

//$v_passivo_patrimonio_liquido  = $v_passivo_patrimonio_capital+$v_passivo_reservas+($aDefictDiminui.$v_passivo_lucros_prejuizos_ac);
$v_passivo_patrimonio_liquido  = $v_passivo_patrimonio_capital+$v_passivo_reservas+($v_passivo_lucros_prejuizos_ac);

//-compensado
$v_passivo_compensado          =   $v_passivo_prev_orc_receita     + $v_passivo_exec_orc_despesa+
                                   $v_passivo_exec_prog_financeira + $v_ativo_desp_div_estados_munic+ 
                                   $v_passivo_exec_restos_pagar    + $v_passivo_compens_ativas_diver;

//totais
$v_ativo_total                 = $v_ativo_real+$v_ativo_compensado;
$v_passivo_total               = $v_passivo_real+$v_passivo_patrimonio_liquido+$v_passivo_compensado;


$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
//$pdf->setfont('arial','b',7);
$alt   = 3;
$pagina = 1;

$pdf->addpage();

$pdf->setfont('arial','',5);
$pdf->cell(23,$alt-2,"Art. 105 DA LEI 4.320/1964",'0',0,"C",0);
$pdf->ln(2);



$pdf->line(85,$pdf->getY(),85,235);
$pdf->line(105,$pdf->getY(),105,235);
$pdf->line(180,$pdf->getY(),180,235);


$pdf->line(10,122,200,122);
$pdf->line(10,186,200,186);
$pdf->line(10,190,200,190);
$pdf->line(10,207,200,207);


$pdf->setfont('arial','b',7);
$pdf->cell(75,$alt,"A T I V O",'TB',0,"C",1);
$pdf->cell(20,$alt,"R$",'1',0,"C",1);
$pdf->cell(75,$alt,"P A S S I V O",'1',0,"C",1);
$pdf->cell(20,$alt,"R$",'TBL',0,"C",1);
$pdf->ln(5);

$pdf->setx(10);
$pdf->cell(75,$alt,'FINANCEIRO',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_financeiro,'f'),0,0,"R",0);
$pdf->cell(75,$alt,'FINANCEIRO',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_financeiro,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"DISPONÍVEL",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_disponivel,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);

$pdf->setx(107);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"DEPÓSITOS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_depositos,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Caixa",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_caixa,'f'),0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Consignações",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_consignacoes,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Bancos Conta Movimento",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_banco_movimento ,'f'),0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Recursos da União",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_recursos_uniao,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(110);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Depósitos de Diversas Origens",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_depos_diversas_orig,'f'),0,0,"R",0);
$pdf->ln(5);


$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"CRÉDITOS EM CIRCULAÇÃO",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_creditos_circulacao,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"OBRIGAÇÕES EM CIRCULAÇÃO",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_obrigacoes_circula,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);


$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Créditos a Receber",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_credito_receber,'f'),0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Obrigações a Pagar",0,0,"L",0);

$pdf->cell(20,$alt,db_formatar($v_passivo_obrigacoes_pagar,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Devedores - Entidades e Agentes",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_devedores_ent_ag,'f'),0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Credores - Entidades e Agentes",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_credores_entidade_ag,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Adiantamentos Concedidos",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_adiant_concedido,'f'),0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Valores em Trânsito Exigíveis",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_valor_transito_exig,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->cell(70,$alt,"Depósitos Realizáveis a Curto Prazo",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_dep_real_curto_prazo,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->cell(70,$alt,"Valores em Trânsito Realizáveis",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_valor_transito_exig,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"BENS E VALORES EM CIRCULAÇÃO",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_bens_val_circulacao,'f'),0,0,"R",0);

$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Títulos e Valores",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_titulos_valores,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);



$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"INVESTIMENTOS DO RPPS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_invest_rpps ,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Investimentos no Segmento de Renda Fixa",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_invest_seg_rend_fixa,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Investimentos no Segmento de Renda Variável",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_invest_seg_rend_var,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Investimentos no Segmento Imóveis",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_invest_seg_imov,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Títulos e valores Mobiliarios",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_titulos_val_imob,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Investimentos com a Taxa de Administração do RPPS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_taxa_administ_rpps,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Empréstimos com Recursos Previdenciários a receber",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_emprest_rec_prev_rec,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"(-) Provisão para Perdas em Investimentos",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_provisao_perda_invest,'f'),0,0,"R",0);
$pdf->ln(5);


$pdf->setfont('arial','b',7);
$pdf->cell(75,$alt,'PERMANENTE (NÃO FINANCEIRO)',0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_permanente_nao_financ,'f'),0,0,"R",0);
$pdf->cell(75,$alt,'PERMANENTE (NÃO FINANCEIRO)',0,0,"L",0);
$pdf->setfont('arial','B',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_permanente_nao_financ,'f'),0,0,"C",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(75,$alt,"BENS E VALORES EM CIRCULAÇÃO",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_bens_valor_circula,'f'),0,0,"C",0);
$pdf->setfont('arial','',7);

$pdf->setx(107);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"DEPÓSITOS EXIGÍVEIS A LONGO PRAZO",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_dep_exig_long_prazo,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Estoques",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_estoque,'f') ,0,0,"R",0);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Recursos Vinculados",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_recurso_vinculado,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"VALORES PENDENTES A CURTO PRAZO",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_valor_pend_curto_prazo,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"OBRIGAÇÕES EXIGÍVEIS A LONGO PRAZO",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_obr_exig_long_prazo,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Custos e Despesas Pagos Antecipadamente",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_custo_desp_pagos_antec,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(5,$alt,"",0,0,"R",0);
$pdf->cell(70,$alt,"Obrigações Legais e Tributárias",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_obr_legal_tributaria,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(110);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Obrigações a Pagar",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_obr_pagar,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(110);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Provisões Matemáticas Previdenciárias",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_prov_matematica_prev,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"DIVIDA ATIVA",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_divida_ativa ,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Créditos Inscritos em Divida Ativa",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_cred_inscr_div_ativa,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"REALIZÁVEIS A LONGO PRAZO",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_real_longo_prazo,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Depósitos Realizáveis a Longo Prazo",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_dep_real_longo_prazo,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Créditos Realizáveis a Longo Prazo",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_cred_real_logo_prazo,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(12);
$pdf->setfont('arial','U',7);
$pdf->cell(73,$alt,"PERMANENTE",0,0,"L",0);
$pdf->setfont('arial','U',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_permanente,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);


$pdf->setx(15);
$pdf->setfont('arial','',7);
$pdf->cell(70,$alt,"Imobilizado",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_ativo_imobilizado,'f') ,0,0,"R",0);
$pdf->ln(5);

$pdf->setfont('arial','',7);
$pdf->cell(75,$alt,'ATIVO REAL',0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_real,'f'),0,0,"R",0);
$pdf->cell(75,$alt,'PASSIVO REAL',0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_real,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(105);
$pdf->setfont('arial','B',7);
$pdf->cell(75,$alt,"PATRIMÔNIO LÍQUIDO",0,0,"L",0);
$pdf->setfont('arial','B',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_patrimonio_liquido,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);

$pdf->setx(107);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"PATRIMÔNIO CAPITAL",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_patrimonio_capital ,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(107);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"RESERVAS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_reservas,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(107);
$pdf->setfont('arial','',7);
$pdf->cell(74,$alt,"LUCROS OU PREJUÍZOS ACUMULADOS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(1,$alt,"",0,0,"R",0);
$pdf->cell(18,$alt,db_formatar($v_passivo_lucros_prejuizos_ac,'f'),0,0,"R",0);
//$pdf->cell(18,$alt,db_formatar($aDefictDiminui.$v_passivo_lucros_prejuizos_ac,'f'),0,0,"R",0);
//echo $aDefictDiminui.$v_passivo_lucros_prejuizos_ac; die();
$pdf->cell(1,$alt,"",0,0,"R",0);
$pdf->ln(5);

$pdf->setx(10);
$pdf->setfont('arial','B',7);
$pdf->cell(75,$alt,'COMPENSADO',0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->setfont('arial','B',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_compensado,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->setx(105);
$pdf->setfont('arial','B',7);
$pdf->cell(75,$alt,'COMPENSADO',0,0,"L",0);
$pdf->setfont('arial','B',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_compensado,'f'),0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->ln(5);


$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"EXECUÇÃO ORÇAMENTÁRIA DA RECEITA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_exec_orc_receita,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"PREVISÃO ORÇAMENTÁRIA DA RECEITA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_prev_orc_receita,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"FIXAÇÃO ORÇAMENTÁRIA DA DESPESA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_fixa_orc_despesa,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"EXECUÇÃO ORCAMENTÁRIAS DA DESPESA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_exec_orc_despesa,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"EXECUÇÃO DA PROGRAMAÇÃO FINANCEIRA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_exec_prog_financeira,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"EXECUÇÃO DA PROGRAMAÇÃO FINANCEIRA",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_exec_prog_financeira,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"DESPESAS E DÍVIDAS DOS ESTADOS E MUNICÍPIOS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_desp_div_estados_munic,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"DESPESAS E DÍVIDAS DE ESTADOS E MUNICÍPIOS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_desp_div_estados_munic,'f'),0,0,"R",0);
$pdf->ln(3);


$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"EXECUÇÃO DE RESTOS A PAGAR",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_exec_restos_pagar,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"EXECUÇÃO DE RESTOS A PAGAR",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_passivo_exec_restos_pagar,'f'),0,0,"R",0);
$pdf->ln(3);

$pdf->setx(13);
$pdf->setfont('arial','',7);
$pdf->cell(72,$alt,"COMPENSAÇÕES ATIVAS DIVERSAS",0,0,"L",0);
$pdf->setfont('arial','',7);
$pdf->cell(20,$alt,db_formatar($v_ativo_compens_ativas_diver,'f'),0,0,"R",0);
$pdf->cell(2,$alt,"",0,0,"R",0);
$pdf->setfont('arial','',7);
$pdf->cell(73,$alt,"COMPENSAÇÕES PASSIVAS DIVERSAS",0,0,"L",0);
$pdf->cell(20,$alt,db_formatar($v_passivo_compens_ativas_diver,'f'),0,0,"R",0);
$pdf->ln(5);

$pdf->setx(10);
$pdf->setfont('arial','b',7);
$pdf->cell(75,$alt,'TOTAL','TB',0,"L",1);
$pdf->setx(85);
$pdf->cell(20,$alt,db_formatar($v_ativo_total,'f'),'1',0,"R",1);

$pdf->setx(105);
$pdf->setfont('arial','B',7);
$pdf->cell(75,$alt,'TOTAL','1',0,"L",1);
$pdf->cell(20,$alt,db_formatar($v_passivo_total,'f'),'TBL',0,"R",1);
$pdf->ln(3);



//$periodo = db_retorna_periodo($mes,"B");
notasExplicativas(&$pdf,56,($mes>9?$mes:"0".$mes),190);

$pdf->ln(15);

// assinaturas
assinaturas(&$pdf,&$classinatura,'BG');

function anexo14_retorna_saldo($saldo, $sinal, $grupo) {
  if ($grupo == "A" and $sinal == "C") {
    $saldo = $saldo *-1;
  } elseif ($grupo == "P" and $sinal == "D") {
    $saldo = $saldo *-1;
  }
  return $saldo;
}


$pdf->Output();
   
?>