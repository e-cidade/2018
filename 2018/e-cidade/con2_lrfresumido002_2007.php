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

set_time_limit(7000000000);
include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_conrelinfo_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_conrelvalor_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$classinatura  = new cl_assinatura;
$orcparamrel   = new cl_orcparamrel;
$clconrelinfo  = new cl_conrelinfo;
$clempresto    = new cl_empresto;
$clconrelvalor = new cl_conrelvalor;

$instituicao  = str_replace("-",",",$db_selinstit);
$instit_rpps  ='';

if ($emite_rec_desp==1||$emite_proj==1){
  // seleciona instituio do RPPS
  $sql         = "select codigo  from db_config where db21_tipoinstit in (5,6) ";
  $resultinst  = pg_exec($sql);
  $xvirg       = '';
  for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
    db_fieldsmemory($resultinst,$xins);
    $instit_rpps .= $xvirg.$codigo; // salva insituio
    $xvirg        = ', ';		  
  }
}

$anousu = db_getsession("DB_anousu");
$dados  = data_periodo($anousu,$bimestre); // no dbforms/db_funcoes.php
$dt_ini = $dados[0]; // data inicial do perodo
$dt_fin = $dados[1]; // data final do perodo

$anousu_ant = $anousu-1;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// BALANCO ORCAMENTARIO - RECEITAS
if ($emite_balorc_rec==1){
  $total_inicial        = 0;
  $total_atualizada     = 0;
  $total_nobim          = 0;
  $total_atebim         = 0;
  $total_deficit        = 0;
  $total_saldo_ant      = 0;
  
  $m_impostos           = $orcparamrel->sql_parametro('15','1',"f",$instituicao);
  $m_taxas              = $orcparamrel->sql_parametro('15','2',"f",$instituicao);
  $m_melhorias          = $orcparamrel->sql_parametro('15','3',"f",$instituicao);
  $m_sociais            = $orcparamrel->sql_parametro('15','4',"f",$instituicao);
  $m_economicas         = $orcparamrel->sql_parametro('15','5',"f",$instituicao);
  $m_imobiliarias       = $orcparamrel->sql_parametro('15','6',"f",$instituicao);
  $m_valmobiliarias     = $orcparamrel->sql_parametro('15','7',"f",$instituicao);
  $m_permissoes         = $orcparamrel->sql_parametro('15','8',"f",$instituicao);
  $m_patrimoniais       = $orcparamrel->sql_parametro('15','9',"f",$instituicao);
  $m_vegetal            = $orcparamrel->sql_parametro('15','10',"f",$instituicao);
  $m_animal             = $orcparamrel->sql_parametro('15','11',"f",$instituicao);
  $m_agropecuarias      = $orcparamrel->sql_parametro('15','12',"f",$instituicao);
  $m_mineral            = $orcparamrel->sql_parametro('15','13',"f",$instituicao);
  $m_transformacao      = $orcparamrel->sql_parametro('15','14',"f",$instituicao);
  $m_construcao         = $orcparamrel->sql_parametro('15','15',"f",$instituicao);
  $m_servicos           = $orcparamrel->sql_parametro('15','16',"f",$instituicao);
  $m_intragovernamental = $orcparamrel->sql_parametro('15','17',"f",$instituicao);
  $m_privadas           = $orcparamrel->sql_parametro('15','18',"f",$instituicao);
  $m_transf_exterior    = $orcparamrel->sql_parametro('15','19',"f",$instituicao);
  $m_transf_pessoas     = $orcparamrel->sql_parametro('15','20',"f",$instituicao);
  $m_transf_convenios   = $orcparamrel->sql_parametro('15','21',"f",$instituicao);
  $m_multas             = $orcparamrel->sql_parametro('15','22',"f",$instituicao);
  $m_indenizacao        = $orcparamrel->sql_parametro('15','23',"f",$instituicao);
  $m_divida_ativa       = $orcparamrel->sql_parametro('15','24',"f",$instituicao);
  $m_correntes_diversas = $orcparamrel->sql_parametro('15','25',"f",$instituicao);
  $m_oper_internas      = $orcparamrel->sql_parametro('15','26',"f",$instituicao);
  $m_oper_externas      = $orcparamrel->sql_parametro('15','27',"f",$instituicao);
  $m_bens_moveis        = $orcparamrel->sql_parametro('15','28',"f",$instituicao);
  $m_bens_imoveis       = $orcparamrel->sql_parametro('15','29',"f",$instituicao);
  $m_emprestimos        = $orcparamrel->sql_parametro('15','30',"f",$instituicao);
  $m_transf_capital_intragovernamentais = $orcparamrel->sql_parametro('15','31',"f",$instituicao);
  $m_transf_capital_privadas            = $orcparamrel->sql_parametro('15','32',"f",$instituicao);
  $m_transf_capital_exterior            = $orcparamrel->sql_parametro('15','33',"f",$instituicao);
  $m_transf_capital_pessoas             = $orcparamrel->sql_parametro('15','34',"f",$instituicao);
  $m_transf_capital_convenios           = $orcparamrel->sql_parametro('15','35',"f",$instituicao);
  $m_outras_social                      = $orcparamrel->sql_parametro('15','36',"f",$instituicao);
  $m_outras_disponibilidades            = $orcparamrel->sql_parametro('15','37',"f",$instituicao);
  $m_outras_diversas                    = $orcparamrel->sql_parametro('15','38',"f",$instituicao);
  $m_oper_int_mobiliaria                = $orcparamrel->sql_parametro('15','39',"f",$instituicao); //operaes de credito/refinanciamento
  $m_oper_int_outras                    = $orcparamrel->sql_parametro('15','40',"f",$instituicao);
  $m_oper_ext_mobiliaria                = $orcparamrel->sql_parametro('15','41',"f",$instituicao);
  $m_oper_ext_outras                    = $orcparamrel->sql_parametro('15','42',"f",$instituicao);
  
  $m_saldo_anterior['estrut'] = $orcparamrel->sql_parametro('15','43',"f",$instituicao); // contas do compensado que demonstram superavit de exercicios anteriores (creditos reabertos )
  
  $m_intraorcamentarias = $orcparamrel->sql_parametro('15','44',"f",$instituicao); // receitas intraorcamentarias
  
  // RECEITAS
  $db_filtro  = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
  $result_rec = db_receitasaldo(4,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
  @pg_exec("drop table work_receita");
  for($i = 0;$i < pg_numrows($result_rec); $i++) {
    db_fieldsmemory($result_rec,$i);
    $estrutural = $o57_fonte;
    if (in_array($estrutural,$m_impostos)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_taxas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_melhorias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_sociais)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_economicas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_imobiliarias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_valmobiliarias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_permissoes)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_patrimoniais)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_vegetal)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_animal)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_agropecuarias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_mineral)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transformacao)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_construcao)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_servicos)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_intragovernamental)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_privadas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_exterior)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_pessoas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_convenios)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_multas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_indenizacao)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_divida_ativa)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_correntes_diversas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_internas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_externas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_bens_moveis)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_bens_imoveis)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_emprestimos)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_capital_intragovernamentais)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_capital_privadas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_capital_exterior)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_capital_pessoas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_transf_capital_convenios)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_outras_social)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_outras_disponibilidades)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_outras_diversas)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_int_mobiliaria)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_int_outras)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_ext_mobiliaria)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_oper_ext_outras)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
    if (in_array($estrutural,$m_intraorcamentarias)){
      $total_inicial    += $saldo_inicial;
      $total_atualizada += $saldo_inicial_prevadic;
      $total_nobim  += $saldo_arrecadado;
      $total_atebim += $saldo_arrecadado_acumulado;
    }
  }  
  
  // SALDO ANTERIOR
  $db_filtro  = ' c61_instit in (' . str_replace('-',', ',$db_selinstit) . ' ) ';
  $result_bal = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$db_filtro);
  @pg_exec("drop table work_pl");
  for($i = 0; $i < pg_numrows($result_bal); $i++){
    db_fieldsmemory($result_bal,$i);  
    if (in_array($estrutural,$m_saldo_anterior['estrut'])){
      $total_saldo_ant += $saldo_final;
    }   
  }
  // BALANCO ORCAMENTARIO - DESPESAS
  $total_inicial_desp    = 0;
  $total_adicional       = 0;
  $total_atualizada_desp = 0;
  $total_emp_nobim       = 0;
  $total_emp_atebim      = 0;
  $total_liq_nobim       = 0;
  $total_liq_atebim      = 0;
  
  $desp_pessoal        = $orcparamrel->sql_parametro('15','45',"f",$instituicao); 
  $desp_juros          = $orcparamrel->sql_parametro('15','46',"f",$instituicao); 
  $desp_outras         = $orcparamrel->sql_parametro('15','47',"f",$instituicao); 
  $desp_investimentos  = $orcparamrel->sql_parametro('15','48',"f",$instituicao); 
  $desp_inversoes      = $orcparamrel->sql_parametro('15','49',"f",$instituicao); 
  $desp_amortizacao    = $orcparamrel->sql_parametro('15','50',"f",$instituicao); 
  $desp_reserva        = $orcparamrel->sql_parametro('15','51',"f",$instituicao); 
  $desp_int_mobiliaria = $orcparamrel->sql_parametro('15','52',"f",$instituicao); 
  $desp_int_outras     = $orcparamrel->sql_parametro('15','53',"f",$instituicao); 
  $desp_ext_mobiliaria = $orcparamrel->sql_parametro('15','54',"f",$instituicao); 
  $desp_ext_outras     = $orcparamrel->sql_parametro('15','55',"f",$instituicao); 
  
  $sele_work   = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
  $result_desp = db_dotacaosaldo(7,1,4,true,$sele_work,$anousu,$dt_ini,$dt_fin);
  
  for($i = 0; $i < pg_numrows($result_desp); $i++){
    db_fieldsmemory($result_desp,$i);
    $estrutural = $o58_elemento;
    if (strlen($o58_elemento) < 15) {
      $estrutural .= "00";
    }
    if (substr($estrutural,0,3)=='331') {
      // if (in_array($estrutural,$desp_pessoal)){
        $total_inicial_desp += $dot_ini;
        $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
        $total_emp_nobim    += $empenhado  - $anulado;
        $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
        $total_liq_nobim    += $liquidado;
        $total_liq_atebim   += $liquidado_acumulado;
      }
      if (substr($estrutural,0,3)=='332') {
        // if (in_array($estrutural,$desp_juros)){
          $total_inicial_desp += $dot_ini;
          $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
          $total_emp_nobim    += $empenhado  - $anulado;
          $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
          $total_liq_nobim    += $liquidado;
          $total_liq_atebim   += $liquidado_acumulado;
        }
        if (substr($estrutural,0,3)=='333') {
          // if (in_array($estrutural,$desp_outras)){
            $total_inicial_desp += $dot_ini;
            $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
            $total_emp_nobim    += $empenhado  - $anulado;
            $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
            $total_liq_nobim    += $liquidado;
            $total_liq_atebim   += $liquidado_acumulado;
          }
          if (substr($estrutural,0,3)=='344') {
            // if (in_array($estrutural,$desp_investimentos)){
              $total_inicial_desp += $dot_ini;
              $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
              $total_emp_nobim    += $empenhado  - $anulado;
              $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
              $total_liq_nobim    += $liquidado;
              $total_liq_atebim   += $liquidado_acumulado;
            }
            if (substr($estrutural,0,3)=='345') {
              // if (in_array($estrutural,$desp_inversoes)){
                $total_inicial_desp += $dot_ini;
                $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                $total_emp_nobim    += $empenhado  - $anulado;
                $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                $total_liq_nobim    += $liquidado;
                $total_liq_atebim   += $liquidado_acumulado;
              }
              if (substr($estrutural,0,3)=='346') {
                // if (in_array($estrutural,$desp_amortizacao)){
                  $total_inicial_desp += $dot_ini;
                  $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                  $total_emp_nobim    += $empenhado  - $anulado;
                  $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                  $total_liq_nobim    += $liquidado;
                  $total_liq_atebim   += $liquidado_acumulado;
                }
                if ((substr($estrutural,0,3)=='399') || (substr($estrutural,0,3)=='377'))  {
                  // if (in_array($estrutural,$desp_reserva)){
                    $total_inicial_desp += $dot_ini;
                    $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                    $total_emp_nobim    += $empenhado  - $anulado;
                    $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                    $total_liq_nobim    += $liquidado;
                    $total_liq_atebim   += $liquidado_acumulado;
                  }
                  if (in_array($estrutural,$desp_int_mobiliaria)){
                    $total_inicial_desp += $dot_ini;
                    $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                    $total_emp_nobim    += $empenhado  - $anulado;
                    $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                    $total_liq_nobim    += $liquidado;
                    $total_liq_atebim   += $liquidado_acumulado;
                  }
                  if (in_array($estrutural,$desp_int_outras)){
                    $total_inicial_desp += $dot_ini;
                    $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                    $total_emp_nobim    += $empenhado  - $anulado;
                    $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                    $total_liq_nobim    += $liquidado;
                    $total_liq_atebim   += $liquidado_acumulado;
                  }
                  if (in_array($estrutural,$desp_ext_mobiliaria)){
                    $total_inicial_desp += $dot_ini;
                    $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                    $total_emp_nobim    += $empenhado  - $anulado;
                    $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                    $total_liq_nobim    += $liquidado;
                    $total_liq_atebim   += $liquidado_acumulado;
                  }
                  if (in_array($estrutural,$desp_ext_outras)){
                    $total_inicial_desp += $dot_ini;
                    $total_adicional    += $suplementado_acumulado - $reduzido_acumulado;
                    $total_emp_nobim    += $empenhado  - $anulado;
                    $total_emp_atebim   += $empenhado_acumulado  - $anulado_acumulado;
                    $total_liq_nobim    += $liquidado;
                    $total_liq_atebim   += $liquidado_acumulado;
                  }
                }  
                
                $total_atualizada_desp = $total_inicial_desp + $total_adicional;
              }
              // DESPESAS POR FUNCAO/SUBFUNCAO
              if ($emite_desp_funcsub==1){
                /*
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                */
                
                
                
                $total_emp_nobim     = 0;
                $total_emp_atebim    = 0;
                $total_liq_nobim     = 0;
                $total_liq_atebim    = 0;
                
                $sele_work      = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
                $result_funcsub = db_dotacaosaldo(7,3,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,8,0,false,1,false,2,3);
                
                for($i = 0; $i < pg_numrows($result_funcsub); $i++){
                  db_fieldsmemory($result_funcsub,$i);
                  $total_emp_nobim  += $empenhado  - $anulado;
                  $total_emp_atebim += $empenhado_acumulado  - $anulado_acumulado;
                  $total_liq_nobim  += $liquidado;
                  $total_liq_atebim += $liquidado_acumulado;
                }  
              }

              // RECEITA CORRENTE LIQUIDA - RCL
              if ($emite_rcl==1||$emite_ppp==1){     
                
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");     
                @pg_query("rollback"); 
                // se o ano atual  bissexto deve subtrair 366 somente se a data for superior a 28/02/200X
                $dt = split('-',$dt_fin);  // mktime -- (mes,dia,ano)
                $dt_ini_ant = date('Y-m-d',mktime(0,0,0,$dt[1],$dt[2]+1,$anousu_ant));
                $dt_fin_ant = $anousu_ant.'-12-31';  
                
                $total_rcl  = 0;
                $total_rcl  = calcula_rcl($anousu,$anousu.'-01-01',$dt_fin,$db_selinstit);
                // db_criatabela($total_rcl);exit; 
                @pg_query("drop table work_plano");
                @pg_query("commit");
                
                $total_rcl += calcula_rcl($anousu_ant,$dt_ini_ant,$dt_fin_ant,$db_selinstit);
                @pg_query("drop table work_plano");
                @pg_query("commit");
                // $total_rcl = 0;
                
              }
              // RECEITAS/DESPESAS DO RPPS
              if ($emite_rec_desp==1){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                $arqinclude = true;
                
                include((db_getsession("DB_anousu")<2007?"con2_lrfrecdesprpps002.php":"con2_lrfrecdesprpps002_2007.php"));
                
                $total_rpps_rec_nobim   = $total_rec_bimestre;
                $total_rpps_rec_atebim  = $total_rec_exercicio;
                $total_rpps_desp_nobim  = $total_desp_bimestre; 
                $total_rpps_desp_atebim = $total_desp_exercicio;
                
                // resultado
                $res_rpps_prev_nobim         = $total_rec_bimestre - $total_desp_bimestre; 
                $res_rpps_prev_atebim   = $total_rec_exercicio - $total_desp_exercicio;
                
                unset($arqinclude);
                
              }
              // RESULTADOS NOMINAL/PRIMARIO
              if ($emite_resultado){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                $arqinclude = true;
                
                $META_NOMINAL = 0;    
                $dt_ini = db_getsession("DB_anousu").'-01-01';
                
                $periodo = $bimestre;
                
                include("con2_lrfnominal002.php");    
                
                //$tot1 = ($somador_III_bim  + $somador_IV_bim) -$somador_V_bim;
                //$tot2 = ($somador_III_ant + $somador_IV_ant) - $somador_V_ant;
                //$total_nominal = bcsub($tot1,$tot2);
                
                $tot_bi  = (($somador_III_bim  + $somador_IV_bim) - $somador_V_bim );
                $tot_ant = (($somador_III_ant + $somador_IV_ant) - $somador_V_ant);
                
                //echo("somador_III_bim: $somador_III_bim - somador_IV_bim:  $somador_IV_bim - somador_V_bim: $somador_V_bim<br>");
                //echo("somador_III_ant: $somador_III_ant - somador_IV_ant: $somador_IV_ant - somador_V_ant: $somador_V_ant<br>");
                //exit;
                
                //		$total_nominal = ($tot_bi < 0 and $tot_ant < 0?((abs($tot_bi)-abs($tot_ant))*-1):($tot_bi-$tot_ant));
                
                //		$total_nominal = $tot_ant-$tot_bi;
                $total_nominal = $tot_bi - $tot_ant;
                
                unset($arqinclude);
                
                // RESULTADO PRIMARIO
                $arqinclude = true;
                
                $META_PRIMARIA = 0;
                include("con2_lrfprimario002.php");
                
                $result_info = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(17,str_replace('-',',',$db_selinstit)));
                if ($clconrelinfo->numrows > 0 ){
                  db_fieldsmemory($result_info,0);
                  $META_PRIMARIA = $c83_informacao;
                } 
                
                $total_primario = (($receita_primaria_total[3]) - ($m_desp[1][3]+$m_desp[3][3]+$m_desp[4][3]+$m_desp[7][3]+$m_desp[9][3]+$m_desp[10][3]));
                
                unset($arqinclude);
                
              }
              
              
              // RESTOS A PAGAR
              if(1 == 2){
                if ($emite_rp==1){
                  @pg_query("drop table work_receita"); 
                  @pg_query("drop table work_pl");
                  @pg_query("drop table work_pl_estrut");
                  @pg_query("drop table work_pl_estrutmae");
                  
                  
                  $total_proc_insc       = 0;
                  $total_proc_canc       = 0;
                  $total_proc_pgto       = 0;
                  $total_proc_a_pagar    = 0;
                  
                  $total_naoproc_insc    = 0;
                  $total_naoproc_canc    = 0;
                  $total_naoproc_pgto    = 0;
                  $total_naoproc_a_pagar = 0;
                  
                  $db_filtro  = ' e60_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
                  $sqlperiodo = $clempresto->sql_rp2($anousu,$db_filtro,$anousu.'-01-01',$dt_fin);
                  
                  $sqlperiodo = "select e60_instit, nomeinst, o58_orgao, o40_descr, 
                  sum(case when e60_anousu < $anousu_ant 
                  then e91_vlrliq-e91_vlrpag 
                  else 0 end ) as  inscricao_ant,
                  sum(case when e60_anousu = $anousu_ant 
                  then e91_vlrliq-e91_vlrpag	     
                  else 0 end ) as  valor_processado,	 
                  sum(coalesce(e91_vlremp,0)) as e91_vlremp, 
                  sum(coalesce(e91_vlranu,0)) as e91_vlranu,
                  sum(coalesce(e91_vlrliq,0)) as e91_vlrliq,
                  sum(coalesce(e91_vlrpag,0)) as e91_vlrpag,
                  sum(coalesce(vlranu,0))     as vlranu,
                  sum(coalesce(vlrliq,0))     as  vlrliq,
                  sum(coalesce(vlrpag,0))     as vlrpag
                  from ($sqlperiodo) as x
                  group by e60_instit, nomeinst, o58_orgao, o40_descr";
                  
                  $resultado_rp = pg_query($sqlperiodo);
                  
                  // TOTAIS PROCESSADOS
                  $tot_01 =0;
                  $tot_02 =0;
                  $tot_03 =0;
                  $tot_04 =0;
                  $tot_05 =0;
                  // TOTAIS NAO PROCESSADOS
                  $tot_06 =0;
                  $tot_07 =0;
                  $tot_08 =0;
                  $tot_09 =0;
                  
                  $a_pagar_processado     = 0;
                  $a_pagar_nao_processado = 0;
                  
                  for($i = 0; $i < pg_numrows($resultado_rp); $i++){
                    db_fieldsmemory($resultado_rp, $i);
                    
                    $pago_processado = $vlrpag;     
                    $pago_nao_processado = 0; 
                    
                    // valores de inscrio
                    $valor_nao_processado = $e91_vlremp-$e91_vlranu-$e91_vlrliq;
                    
                    if ($vlrpag > $valor_processado){
                      $pago_nao_processado = $vlrpag - $valor_processado;
                      $pago_processado     = $valor_processado; 
                    } 
                    
                    $a_pagar_processado     = ($valor_processado+$inscricao_ant) - $pago_processado;
                    $a_pagar_nao_processado = $valor_nao_processado-$vlranu-$pago_nao_processado;
                    
                    $tot_01 += $inscricao_ant; 
                    $tot_02 += $valor_processado ;
                    $tot_03 += 0 ;
                    $tot_04 += $pago_processado ;
                    $tot_05 += $a_pagar_processado ;
                    // totais no processados
                    $tot_06 += $valor_nao_processado;
                    $tot_07 += $vlranu;
                    $tot_08 += $pago_nao_processado;
                    $tot_09 += $a_pagar_nao_processado;
                  }
                  
                  $total_proc_insc       = $tot_01 + $tot_02;
                  $total_proc_canc       = $tot_03;
                  $total_proc_pgto       = $tot_04;
                  $total_proc_a_pagar    = $tot_05;
                  
                  $total_naoproc_insc    = $tot_06;
                  $total_naoproc_canc    = $tot_07;
                  $total_naoproc_pgto    = $tot_08;
                  $total_naoproc_a_pagar = $tot_09;
                }
                
              }else{
                
                $arqinclude = true;
                
                $db_filtro  = ' e60_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
                
                include("con2_lrfdemonstrativorp002_2007.php");
                
                // totais do poder executivo
                $total_proc_insc       = $pgeral_inscricao_anterior + $pgeral_inscricao_processado;
                $total_proc_canc       = $pgeral_calcelado;
                $total_proc_pgto       = $pgeral_pago;
                $total_proc_a_pagar    = $pgeral_a_pagar;
                
                $total_naoproc_insc    = $npgeral_inscricao_anterior + $npgeral_inscricao_processado;;
                $total_naoproc_canc    = $npgeral_calcelado;
                $total_naoproc_pgto    = $npgeral_pago;
                $total_naoproc_a_pagar = $npgeral_a_pagar;
                
                // totais do poder legislativo
                $ltotal_proc_insc       = $lpgeral_inscricao_anterior + $lpgeral_inscricao_processado;
                $ltotal_proc_canc       = $lpgeral_calcelado;
                $ltotal_proc_pgto       = $lpgeral_pago;
                $ltotal_proc_a_pagar    = $lpgeral_a_pagar;
                
                $ltotal_naoproc_insc    = $lnpgeral_inscricao_anterior + $lnpgeral_inscricao_processado;;
                $ltotal_naoproc_canc    = $lnpgeral_calcelado;
                $ltotal_naoproc_pgto    = $lnpgeral_pago;
                $ltotal_naoproc_a_pagar = $lnpgeral_a_pagar;
                
                
              }
              
              
              // MDE
              if ($emite_mde==1) {
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                
                $arqinclude = true;
                // Rotina nova do Anexo X MDE -> FUNDEB
                if ($bimestre == "4B" || $bimestre == "5B" || $bimestre == "6B"){
                  include("con2_lrfmdefundeb002.php");

                  $total_A = 0;
                  $total_B = 0;
                  $total_C = 0;

                  $perc_A  = 0;
                  $perc_B  = 0;
                  $perc_C  = 0;

                  $somador30_valor      = 0;
                  $somador31_valor      = 0;
                  $somador3_atebimestre = 0;
                
                  $somador30_valor = ($receita[44]["exercicio"] - $receita[36]["exercicio"]); 

                  $res_variaveis = $clconrelinfo->sql_record($clconrelinfo->sql_query_file(null,"c83_codigo","c83_codigo","c83_codrel = 31 and c83_anousu = ".db_getsession("DB_anousu")));
                  if ($clconrelinfo->numrows > 0){
                    for($i = 0; $i < $clconrelinfo->numrows; $i++){
                      db_fieldsmemory($res_variaveis,$i);
                      $res_valor = $clconrelvalor->sql_record($clconrelvalor->sql_query_file(null,null,null,"coalesce(trim(c83_informacao)::float8,0) as c83_informacao",null,"c83_codigo = $c83_codigo and c83_periodo = '$bimestre' and c83_instit in (".str_replace("-",",",$db_selinstit).")"));
                      if ($clconrelvalor->numrows > 0){
                        db_fieldsmemory($res_valor,0);
                        $valor = $c83_informacao;  
                      } else {
                        $valor = 0;
                      }

                      $somador30_valor += $valor;
                    }
                  }
  
                  $somador30_valor += $m_restos_mde["cancelado"] + $m_aplicacao_fundeb["valor"];

                  $somador3_atebimestre = $receita[1]["exercicio"]  + $receita[22]["exercicio"];
                  $somador31_valor      = @((($despesa[7]["exercicio"] + $despesa[10]["exercicio"]) - $somador30_valor)/$somador3_atebimestre)*100;

                  @$total_A = ((($m_despesa[42]["exercicio"] + $m_despesa[43]["exercicio"]) +
                                ($m_despesa[44]["exercicio"] + $m_despesa[45]["exercicio"])) - $somador30_valor); 
                  @$perc_A  = $somador31_valor; 
                } else {
                  include("con2_lrfmde002.php"); 
                
                  $somador_VI_inicial     = $receita[1]['inicial']+ $receita[15]['inicial'] - $receita[11]['inicial'] ;
                  $somador_VI_atualizada  = $receita[1]['atualizada']+$receita[15]['atualizada']- $receita[11]['atualizada'];
                  $somador_VI_nobimestre  = $receita[1]['bimestre'] + $receita[15]['bimestre']  - $receita[11]['bimestre'];
                  $somador_VI_atebimestre = $receita[1]['exercicio'] + $receita[15]['exercicio']  - $receita[11]['exercicio'];
                
                  $ganho_fundef = 0;
                  if ($somador_II_atebimestre > $somador_IV_atebimestre ){
                    $somador_XII_valor =  $somador_II_atebimestre - $somador_IV_atebimestre ;
                  } else {
                    $ganho_fundef =  $somador_IV_atebimestre - $somador_II_atebimestre ;
                  }  
                
                  if ($ganho_fundef > 0  && $GANHO_COMPLEM_FUNDEF+0==0) {	
                    $GANHO_COMPLEM_FUNDEF = $ganho_fundef;	     
                  }
                  
                  if (( $GANHO_COMPLEM_FUNDEF+0) > 0){
                    $GANHO_COMPLEM_FUNDEF = $GANHO_COMPLEM_FUNDEF+0;
                  }
                
                  $somador_XIII_valor += $GANHO_COMPLEM_FUNDEF;
                  $somador_XVI_valor  += $GANHO_COMPLEM_FUNDEF;
                
                  $somador_XIV_valor  += $DESP_ENS_FUNDAMENTAL;
                  $somador_XVI_valor  += $DESP_ENS_FUNDAMENTAL;
                
                  $somador_XVI_valor  += $DESP_ENS_INFANTIL;
                
                  $somador_XV_valor   += $DESP_VINC_SUPERAVIT;
                  $somador_XVI_valor  += $DESP_VINC_SUPERAVIT;
                
                  $somador_XVII_valor  = $COMPENSACAO_RP_MDE;
                  $somador_XVIII_valor = $COMPENSACAO_RP_FUNDEF;
                  $somador_XIX_valor   = ($somador_VII_atebimestre + $somador_VIII_atebimestre + $somador_IX_atebimestre +
                                          $somador_XII_valor) - $somador_XVI_valor;
                
                  @$total_A = (($somador_XIX_valor-$somador_XVII_valor)); 
                  @$total_B = (((($somador_VII_atebimestre +$somador_IX_atebimestre +$somador_XII_atebimestre)-($somador_XIII_valor+$somador_XIV_valor+$somador_XV_valor+$somador_XVIII_valor))));
                  @$total_C = $somador_X_atebimestre ;
                
                  @$perc_A = (($somador_XIX_valor-$somador_XVII_valor)/$somador_I_atebimestre) * 100;
                  @$perc_B = (((($somador_VII_atebimestre +$somador_IX_atebimestre +$somador_XII_atebimestre)-($somador_XIII_valor+$somador_XIV_valor+$somador_XV_valor+$somador_XVIII_valor)))/($somador_I_atebimestre *0.25)) * 100;
                  @$perc_C =  ($somador_X_atebimestre *100 )/ $somador_IV_atebimestre;
                
                  unset($arqinclude);
                }            
                
              }
              // OPERACOES DE CREDITO E DESPESAS DE CAPITAL
              if ($emite_oper==1){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                $total_oper     = 0;
                $saldo_oper     = 0;
                $total_despesa  = 0;
                $saldo_despesa  = 0;
                
                $m_operacoes[0] = $orcparamrel->sql_parametro('6','0');
                $m_operacoes[1] = $orcparamrel->sql_parametro('6','1');
                $m_operacoes[2] = $orcparamrel->sql_parametro('6','2');
                
                $sele_work      = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
                $result_oper    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
                @pg_exec("drop table work_receita");
                
                $sele_work      = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
                $result_desp    = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);
                
                
                for($i = 0; $i < pg_numrows($result_oper); $i++){
                  db_fieldsmemory($result_oper, $i);
                  $estrutural = $o57_fonte;
                  if (in_array($estrutural, $m_operacoes)){
                    $total_oper += $saldo_arrecadado_acumulado;
                    $saldo_oper += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
                  }
                }
                
                for($i = 0; $i < pg_numrows($result_desp); $i++){
                  db_fieldsmemory($result_desp,$i);
                  $estrutural = $o58_elemento;
                  if (substr($estrutural,0,3)=='334'){
                    $total_despesa += $liquidado_acumulado;
                    $saldo_despesa += $liquidado_acumulado - $dot_ini;
                  }
                }  
              }
              // PROJECAO ATUARIAL DO RPPS
              if ($emite_proj==1){
								
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                $total_rec          = 0;
                $total_rec_patronal = 0;
                $total_desp         = 0;
                $total_res_rpps     = 0;
                $total_rep_rpps     = 0;
                
                $RPPS_10_EXERCICIO_PATRONAL = 0;
                $RPPS_20_EXERCICIO_PATRONAL = 0;
                $RPPS_35_EXERCICIO_PATRONAL = 0;
                
                $RPPS_10_EXERCICIO_RECEITAS = 0;
                $RPPS_20_EXERCICIO_RECEITAS = 0;
                $RPPS_35_EXERCICIO_RECEITAS = 0;
                
                $RPPS_10_EXERCICIO_DESPESAS = 0;
                $RPPS_20_EXERCICIO_DESPESAS = 0;
                $RPPS_35_EXERCICIO_DESPESAS = 0;
                
                $RPPS_10_EXERCICIO_RESULTADO = 0;
                $RPPS_20_EXERCICIO_RESULTADO = 0;
                $RPPS_35_EXERCICIO_RESULTADO = 0;
                
                $RPPS_10_EXERCICIO_REPASSE   = 0;
                $RPPS_20_EXERCICIO_REPASSE   = 0;
                $RPPS_35_EXERCICIO_REPASSE   = 0;
                
                $clconrelinfo = new cl_conrelinfo;
                $result_info  = $clconrelinfo->sql_record($clconrelinfo->sql_query_valores(18,str_replace('-',',',$db_selinstit)));
                
                if ($clconrelinfo->numrows > 0){
                  for ($i = 0; $i < $clconrelinfo->numrows; $i++){
                    db_fieldsmemory($result_info, $i);
                    
                    if ($c83_codigo==273){
                      $RPPS_10_EXERCICIO_PATRONAL = $c83_informacao;
                    }
                    if ($c83_codigo==274){
                      $RPPS_20_EXERCICIO_PATRONAL = $c83_informacao;
                    }
                    if ($c83_codigo==275){
                      $RPPS_35_EXERCICIO_PATRONAL = $c83_informacao;
                    }
                    if ($c83_codigo==276){
                      $RPPS_10_EXERCICIO_RECEITAS = $c83_informacao;
                    }
                    if ($c83_codigo==277){
                      $RPPS_20_EXERCICIO_RECEITAS = $c83_informacao;
                    }
                    if ($c83_codigo==278){
                      $RPPS_35_EXERCICIO_RECEITAS = $c83_informacao;
                    }
                    if ($c83_codigo==279){
                      $RPPS_10_EXERCICIO_DESPESAS = $c83_informacao;
                    }
                    if ($c83_codigo==280){
                      $RPPS_20_EXERCICIO_DESPESAS = $c83_informacao;
                    }
                    if ($c83_codigo==281){
                      $RPPS_35_EXERCICIO_DESPESAS = $c83_informacao;
                    }
                    if ($c83_codigo==282){
                      $RPPS_10_EXERCICIO_RESULTADO = $c83_informacao;
                    }
                    if ($c83_codigo==283){
                      $RPPS_20_EXERCICIO_RESULTADO = $c83_informacao;
                    }
                    if ($c83_codigo==284){
                      $RPPS_35_EXERCICIO_RESULTADO = $c83_informacao;
                    }
                    if ($c83_codigo==285){
                      $RPPS_10_EXERCICIO_REPASSE   = $c83_informacao;
                    }
                    if ($c83_codigo==286){
                      $RPPS_20_EXERCICIO_REPASSE   = $c83_informacao;
                    }
                    if ($c83_codigo==287){
                      $RPPS_35_EXERCICIO_REPASSE   = $c83_informacao;
                    }
                  }
                }
                
                for ($linha=1;$linha<=28;$linha++){	
                  $m_receita[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha); 
                }	
                
                for ($linha=29;$linha<=39;$linha++){	
                  $m_despesa[$linha]['estrut'] = $orcparamrel->sql_parametro('42',$linha);
                  $m_despesa[$linha]['nivel']  = $orcparamrel->sql_nivel('42',$linha);
                }
                
                $db_filtro  = " o70_instit in (".$instit_rpps.")";
                
                // Exercicio Atual
                $result_rec = db_receitasaldo(11,1,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
                @pg_exec("drop table work_receita");
                
                $sele_work = ' c61_instit in ('.$instit_rpps.')';
                
                // Exercicio Atual
                $result_res_rep = db_planocontassaldo_matriz($anousu,$dt_ini,$dt_fin,false,$sele_work); 
                @pg_exec("drop table work_pl"); 
                
                $db_filtro = "o58_instit in (".$instit_rpps.") ";
                
                // Exercicio Atual
                $result_despesa = db_dotacaosaldo(8,2,3,true,$db_filtro,$anousu,$dt_ini,$dt_fin);
                
                for ($i=0; $i < pg_numrows($result_rec); $i++){
                  db_fieldsmemory($result_rec,$i);
                  $estrutural = $o57_fonte;
                  
                  for ($linha=1;$linha < 15;$linha++){
                    if (in_array($estrutural,$m_receita[$linha]['estrut'])){
                      $total_rec += $saldo_arrecadado;
                    }
                    if (substr($estrutural,0,7)=="6121701"){
                      $total_rec_patronal += $saldo_arrecadado;
                    }
                  }
                }             
                
                for ($i=0; $i < pg_numrows($result_res_rep); $i++) {
                  db_fieldsmemory($result_res_rep,$i);
                  
                  for ($linha=15;$linha<=28;$linha++){
                    if (substr($estrutural,0,1)=="6"){ // RESULTADOS(6) 
                      if (in_array($estrutural,$m_receita[$linha]['estrut'])){
                        $total_rec += $saldo_final;
                      }
                      if (substr($estrutural,0,6)=="612117"){
                        $total_rep_rpps += $saldo_final;
                      }
                    }
                  }
                }
                
                for ($i = 0; $i < pg_numrows($result_despesa); $i ++) {
                  db_fieldsmemory($result_despesa, $i);
                  
                  for ($linha=29;$linha<=39;$linha++){
                    $nivel        = $m_despesa[$linha]['nivel'];
                    $estrutural   = $o58_elemento.'00';
                    $estrutural   = substr($estrutural,0,$nivel);
                    $v_estrutural = str_pad($estrutural, 15, "0", STR_PAD_RIGHT);	
                    
                    if (in_array($v_estrutural, $m_despesa[$linha]['estrut'])){
                      $total_desp += $liquidado;
                    }
                  }     
                }
                
                $total_res_rpps = $total_rec - $total_desp;
              }
              // RECEITA DA ALIENACAO DE ATIVOS E APLICACAO DOS RECURSOS 
              if ($emite_alienacao==1){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                
                $total_alien   = 0;
                $saldo_alien   = 0;
                $total_recurso = 0;
                $saldo_recurso = 0;
                
                $sele_work     = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
                $result_rec    = db_receitasaldo(11,1,3,true,$sele_work,$anousu,$dt_ini,$dt_fin,false);
                @pg_exec("drop table work_receita");
                
                for($i = 0; $i < pg_numrows($result_rec); $i++){
                  db_fieldsmemory($result_rec, $i);
                  $estrutural = $o57_fonte;
                  if (substr($estrutural,0,3)=="422"){
                    $total_alien += $saldo_arrecadado_acumulado;
                    $saldo_alien += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
                  }
                  if (substr($estrutural,0,3)=="413"){
                    $total_recurso += $saldo_arrecadado_acumulado;
                    $saldo_recurso += $saldo_arrecadado_acumulado - $saldo_prevadic_acum;
                  }
                }
              }
              // DESPESAS COM SAUDE
              if ($emite_saude==1){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                
                $MINIMO_APLICAVEL_NO_EXERCICIO_SAUDE = 0;
                $arqinclude = true;
                include("con2_lrfimpostossaude002.php"); 
                
                $total_atebime = $receitas_atebime["0"] + $receitas_atebime["6"] + $receitas_atebime["11"] + $receitas_atebime["12"] + $receitas_atebime["13"] ;
                
                $desp[1]['bimestre'] += $M_INTERFERENCIA['valor'];
                
                $total_IV_atebim = $desp['1']['bimestre']+$desp['2']['bimestre']+$desp['3']['bimestre']+$desp['4']['bimestre']+$desp['5']['bimestre']+$desp['6']['bimestre'];
                
                $total_V_atebim = 0+ $total_IV_atebim - ($desp_p[1]['bimestre'] + $desp_p[2]['bimestre']+$desp_p[3]['bimestre']+$desp_p[4]['bimestre'] ) ;
                
                $MINIMO_APLICAVEL_NO_EXERCICIO_SAUDE = 15;    // sempre foi 25 %
                $total_saude                         = $total_V_atebim - $VARIAVEL_COMPENSACAO;
                @$perc_aplic_atebim                   = (($total_V_atebim - $VARIAVEL_COMPENSACAO)/$receitas_atebime["0"])*100;
                
                unset($arqinclude);
                
              }
              // DESPESA DE PPP
              if ($emite_ppp==1){
                @pg_query("drop table work_receita"); 
                @pg_query("drop table work_pl");
                @pg_query("drop table work_pl_estrut");
                @pg_query("drop table work_pl_estrutmae");
                
                
                $total_despesa  = 0;
                
                $sele_work      = 'o58_instit in ('.str_replace('-', ', ', $db_selinstit).')   ';
                $result_despesa = db_dotacaosaldo(8,2,3,true,$sele_work,$anousu,$dt_ini,$dt_fin);
                
                for ($i = 0; $i < pg_numrows($result_despesa); $i++) {
                  db_fieldsmemory($result_despesa, $i);
                  $estrutural = $o58_elemento;
                  
                  if (strlen($estrutural) < 15){
                    $estrutural .= "00";
                  }
                  
                  if (substr($estrutural,0,2) == "33"){
                    $total_despesa += $liquidado_acumulado;
                  }
                }
                
                $perc_apurado = ($total_despesa/$total_rcl) * 100;
              }
              //////////////////////////////// Impresso do PDF /////////////////////////////////
              $xinstit = split("-",$db_selinstit);
              $resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
              $descr_inst = '';
              $xvirg = '';
              $flag_abrev = false;
              for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
                db_fieldsmemory($resultinst,$xins);
                if (strlen(trim($nomeinstabrev)) > 0){
                     $descr_inst .= $xvirg.$nomeinstabrev;
                     $flag_abrev  = true;
                }else{
                     $descr_inst .= $xvirg.$nomeinst;
                }

                $xvirg = ', ';
              }
  
              if ($flag_abrev == false){
                   if (strlen($descr_inst) > 42){
                        $descr_inst = substr($descr_inst,0,100);
                   }
              }
              
              $head2 = $descr_inst;
              $head3 = "DEMONSTRATIVO SIMPLIFICADO DO RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA";
              $head4 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
              $mes   = split("-",$dt_ini); 
              $txt   = strtoupper(db_mes($mes[1]));
              $dt    = split("-",$dt_fin);
              $txt  .= "  ".strtoupper(db_mes($dt[1]))." $anousu ";;
              $head5 = "$txt";
              
              $pdf = new PDF(); 
              $pdf->Open(); 
              $pdf->AliasNbPages(); 
              $pdf->setfillcolor(235);
              $pdf->setfont('arial','',7);
              $alt     = 4;
              $num_rel = 0;
              
              $pdf->addpage();
              
              $pdf->setfont('arial','',7);
              $pdf->setX(10);
              $pdf->cell(170,$alt,"LRF, Art. 48 - Anexo XVII","B",0,"L",0);
              $pdf->cell(20,$alt,"R$ Unidades","B",1,"R",0);
              
              //------------------------------------------------------------------
              // BALANCO ORCAMENTARIO - RECEITAS
              if ($emite_balorc_rec==1){
                $num_rel++;
                
                $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO - RECEITAS","BT",0,"C",0);
                $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
                $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
                
                $pdf->cell(90,$alt,'Previsão Inicial da Receita','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_inicial,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_inicial,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Previsão Atualizada da Receita','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_atualizada,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_atualizada,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Receitas Realizadas','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_nobim,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_atebim,'f'),'0',1,"R",0);
                // DEFICIT
                $total_rec_realizadas  =  $total_atebim;
                $total_desp_liquidadas =  $total_liq_atebim;
                
                $pos_deficit = $pdf->getY();
                $pdf->cell(90,$alt,'Déficit Orçamentário','R',0,"L",0);
                if ($total_desp_liquidadas > $total_rec_realizadas){
                  $pdf->cell(50,$alt,'-','R',0,"R",0);
                  $pdf->cell(50,$alt,db_formatar($total_desp_liquidadas-$total_rec_realizadas,"f"),'0',1,"R",0);
                }else{
                  $pdf->cell(50,$alt,'-','R',0,"R",0);
                  $pdf->cell(50,$alt,'-','0',1,"R",0);
                }
                // SALDO ANTERIOR
                $pdf->cell(90,$alt,'Saldo de Exercícios Anteriores','R',0,"L",0);
                $pdf->cell(50,$alt,"-",'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_saldo_ant,"f"),'0',1,"R",0);
              }
              // BALANCO ORCAMENTARIO - DESPESAS
              if ($emite_balorc_desp==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"BALANÇO ORÇAMENTÁRIO - DESPESAS","BT",0,"C",0);
                $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
                $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
                
                $pdf->cell(90,$alt,'Dotacão Inicial','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_inicial_desp,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_inicial_desp,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Dotação Atualizada','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_atualizada_desp,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_atualizada_desp,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Despesas Empenhadas','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_emp_nobim,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_emp_atebim,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Despesas Liquidadas','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
                //   SUPERAVIT
                $pos_superavit = $pdf->getY();
                $pdf->cell(90,$alt,'Superávit Orçamentário','R',0,"L",0);
                if ($total_desp_liquidadas < $total_rec_realizadas){
                  $pdf->cell(50,$alt,'-','R',0,"R",0);
                  $pdf->cell(50,$alt,db_formatar($total_rec_realizadas-$total_desp_liquidadas,"f"),'0',1,"R",0);
                }else{
                  $pdf->cell(50,$alt,'-','R',0,"R",0);
                  $pdf->cell(50,$alt,'-','0',1,"R",0);
                }
              }
              // DESPESAS POR FUNCAO/SUBFUNCAO
              if ($emite_desp_funcsub==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"DESPESAS POR FUNÇÃO/SUBFUNÇÃO","BT",0,"C",0);
                $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
                $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
                
                $pdf->cell(90,$alt,'Despesas Empenhadas','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_emp_nobim,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_emp_atebim,'f'),'0',1,"R",0);
                
                $pdf->cell(90,$alt,'Despesas Liquidadas','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_liq_nobim,'f'),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_liq_atebim,'f'),'0',1,"R",0);
              }
              // RECEITA CORRENTE LIQUIDA - RCL
              if ($emite_rcl==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"RECEITA CORRENTE LÍQUIDA - RCL","BT",0,"C",0);
                $pdf->cell(50,$alt,"","BTRL",0,"C",0);
                $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
                
                $pdf->cell(90,$alt,'Receita Corrente Líquida','R',0,"L",0);
                $pdf->cell(50,$alt,"",'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_rcl,'f'),'0',1,"R",0);
              }
              // RECEITAS/DESPESAS DO RPPS
              if ($emite_rec_desp==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"RECEITAS/DESPESAS DOS REGIMES DE PREVIDÊNCIA","BT",0,"C",0);
                $pdf->cell(50,$alt,"No ".$dados["periodo"],"BTRL",0,"C",0);
                $pdf->cell(50,$alt,"Até o ".$dados["periodo"],"BT",1,"C",0);
                
                $pdf->cell(90,$alt,'Regime Geral de Previdência Social','R',0,"L",0);
                $pdf->cell(50,$alt,"",'R',0,"L",0);
                $pdf->cell(50,$alt,"",0,1,"L",0);
                $pdf->cell(90,$alt,'  Receitas Previdenciárias (I)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,'  Despesas Previdenciárias (II)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,'  Resultado Previdenciário (I-II)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar(0,"f"),0,1,"R",0);
                
                $pdf->cell(90,$alt,'Regime Próprio de Previdência Social dos Servidores Públicos','R',0,"L",0);
                $pdf->cell(50,$alt,"",'R',0,"L",0);
                $pdf->cell(50,$alt,"",0,1,"L",0);
                $pdf->cell(90,$alt,'  Receitas Previdenciárias (III)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_rpps_rec_nobim,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_rpps_rec_atebim,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,'  Despesas Previdenciárias (IV)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_rpps_desp_nobim,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($total_rpps_desp_atebim,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,'  Resultado Previdenciário (III-IV)','R',0,"L",0);
                $pdf->cell(50,$alt,db_formatar($res_rpps_prev_nobim,"f"),'R',0,"R",0);
                $pdf->cell(50,$alt,db_formatar($res_rpps_prev_atebim,"f"),0,1,"R",0);
              }
              // RESULTADOS NOMINAL/PRIMARIO
              if ($emite_resultado==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"","T","C",0);
                $pdf->cell(25,$alt,"Meta Fixada no","TRL",0,"C",0);
                $pdf->cell(25,$alt,"Resultado Apurado","TRL",0,"C",0);
                $pdf->cell(50,$alt,"% em Relação à Meta","T",1,"C",0);
                $pdf->cell(90,$alt,"RESULTADOS NOMINAL E PRIMÃRIO","R",0,"C",0);
                $pdf->cell(25,$alt,"AMF da LDO","RL",0,"C",0);
                $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"RL",0,"C",0);
                $pdf->cell(50,$alt,"",0,1,"C",0);
                $pdf->cell(90,$alt,"","B","C",0);
                $pdf->cell(25,$alt,"(a)","BRL",0,"C",0);
                $pdf->cell(25,$alt,"(b)","BRL",0,"C",0);
                $pdf->cell(50,$alt,"(b/a)","B",1,"C",0);
                $pdf->cell(90,$alt,"Resultado Nominal","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($META_NOMINAL,"f"),"BRL",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($total_nominal,"f"),"BRL",0,"R",0);
                if ($META_NOMINAL==0) {
                  $perc_meta_nom = 0;
                }else{
                  $perc_meta_nom = $total_nominal/$META_NOMINAL;
                }
                $pdf->cell(50,$alt,db_formatar($perc_meta_nom,"f"),"B",1,"R",0);
                $pdf->cell(90,$alt,"Resultado Primário","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($META_PRIMARIA,"f"),"BRL",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($total_primario,"f"),"BRL",0,"R",0);
                if ($META_PRIMARIA==0) {
                  $perc_meta_prim = 0;
                }else{
                  $perc_meta_prim = $total_primario/$META_PRIMARIA;
                }
                $pdf->cell(50,$alt,db_formatar($perc_meta_prim,"f"),0,1,"R",0);
              }
              // RESTOS A PAGAR
              if ($emite_rp==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"MOVIMENTAÇÃO DOS RESTOS A PAGAR","TR",0,"C",0);
                $pdf->cell(25,$alt,"Inscrição","TRL",0,"C",0);
                $pdf->cell(25,$alt,"Cancelamento","TRL",0,"C",0);
                $pdf->cell(25,$alt,"Pagamento","TRL",0,"C",0);
                $pdf->cell(25,$alt,"Saldo","TL",1,"C",0);
                $pdf->cell(90,$alt,"","BR",0,"C",0);
                $pdf->cell(25,$alt,"","BRL",0,"C",0);
                $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
                $pdf->cell(25,$alt,"Até o ".$dados["periodo"],"BRL",0,"C",0);
                $pdf->cell(25,$alt,"a Pagar","BL",1,"C",0);
                $pdf->cell(90,$alt,"POR PODER","R",0,"L",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"","L",1,"C",0);
                $pdf->cell(90,$alt,"   RESTOS A PAGAR PROCESSADOS","R",0,"L",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"","L",1,"C",0);
                $pdf->cell(90,$alt,"     Poder Executivo","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
                $pdf->cell(25,$alt,db_formatar($total_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
                $pdf->cell(25,$alt,db_formatar($total_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
                $pdf->cell(25,$alt,db_formatar($total_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 
                
                $pdf->cell(90,$alt,"     Poder Legislativo","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($ltotal_proc_insc,"f"),"RL",0,"R",0);    // Inscricao
                $pdf->cell(25,$alt,db_formatar($ltotal_proc_canc,"f"),"RL",0,"R",0);    // Cancelados
                $pdf->cell(25,$alt,db_formatar($ltotal_proc_pgto,"f"),"R",0,"R",0);     // Pagamentos
                $pdf->cell(25,$alt,db_formatar($ltotal_proc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 
                
                
                $pdf->cell(90,$alt,"   RESTOS A PAGAR NÃO-PROCESSADOS","R",0,"L",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","RL",0,"C",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"","L",1,"C",0);
                $pdf->cell(90,$alt,"     Poder Executivo","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
                $pdf->cell(25,$alt,db_formatar($total_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
                $pdf->cell(25,$alt,db_formatar($total_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
                $pdf->cell(25,$alt,db_formatar($total_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 
                
                $pdf->cell(90,$alt,"     Poder Legislativo","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_insc,"f"),"RL",0,"R",0);    // Inscricao
                $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_canc,"f"),"RL",0,"R",0);    // Cancelados
                $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_pgto,"f"),"R",0,"R",0);     // Pagamentos
                $pdf->cell(25,$alt,db_formatar($ltotal_naoproc_a_pagar,"f"),"L",1,"R",0);  // a Pagar 
                $pdf->cell(90,$alt,"TOTAL","TR",0,"L",0);
                $pdf->cell(25,$alt,db_formatar(($total_proc_insc+$total_naoproc_insc+$ltotal_proc_insc+$ltotal_naoproc_insc),"f"),"TRL",0,"R",0);      // Total de Inscricoes
                $pdf->cell(25,$alt,db_formatar(($total_proc_canc+$total_naoproc_canc+$ltotal_proc_canc+$ltotal_naoproc_canc),"f"),"TRL",0,"R",0);      // Total de Cancelados
                $pdf->cell(25,$alt,db_formatar(($total_proc_pgto+$total_naoproc_pgto+$ltotal_proc_pgto+$ltotal_naoproc_pgto),"f"),"TR",0,"R",0);       // Total de Pagamentos
                $pdf->cell(25,$alt,db_formatar(($total_proc_a_pagar+$total_naoproc_a_pagar+$ltotal_proc_a_pagar+$ltotal_naoproc_a_pagar),"f"),"T",1,"R",0);  // Total de Saldo a Pagar
              }
              // MDE
              if ($emite_mde==1) {
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $fonte = 6;
                $pdf->cell(90,$alt,"","TR",0,"L",0);
                $pdf->cell(25,$alt,"Valor apurado","TR",0,"C",0);
                $pdf->cell(75,$alt,"Limites Constitucionais Anuais","TB",1,"C",0);
                $pdf->cell(90,$alt,"DESPESAS COM MANUTENÇÃO E DESENVOLVIMENTO DO ENSINO - MDE","R",0,"C",0);
                $pdf->cell(25,$alt,"At o ".$dados["periodo"],"R",0,"C",0);
                $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
                $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],"T",1,"C",0);
                $pdf->cell(90,$alt,"","BR",0,"L",0);
                $pdf->cell(25,$alt,"","BR",0,"L",0);
                $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
                $pdf->cell(50,$alt,"","B",1,"C",0);
                $pdf->setfont('arial','',$fonte);
                $pdf->cell(90,$alt,"Mínimo Anual de <18% / 25%> das Receitas de Impostos no MDE","R",0,"L",0);
                $pdf->setfont('arial','',($fonte+2));
                $pdf->cell(25,$alt,db_formatar($total_A,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,"<18% / 25%>","R",0,"C",0);
                $pdf->cell(50,$alt,db_formatar($perc_A,"f"),0,1,"R",0);
                $pdf->setfont('arial','',$fonte);
                
                if ($bimestre == "4B" || $bimestre == "5B" || $bimestre == "6B"){
                  $imprimir = false;
                } else {
                  $imprimir = true;
                }

                if ($imprimir == true) {
                  $pdf->cell(90,$alt,"Mínimo Anual de 60% das Despesas com MDE no Ensino Fundamental","R",0,"L",0);
                  $pdf->setfont('arial','',($fonte+2));
                  $pdf->cell(25,$alt,db_formatar($total_B,"f"),"R",0,"R",0);
                  $pdf->cell(25,$alt,"60%","R",0,"C",0);
                  $pdf->cell(50,$alt,db_formatar($perc_B,"f"),0,1,"R",0);
                  $pdf->setfont('arial','',$fonte);
                  $pdf->cell(90,$alt,"Mínimo Anual de 60% do FUNDEF na Remuneração dos Professores do Ensino Fundamental","BR",0,"L",0);
                  $pdf->setfont('arial','',($fonte+2));
                  $pdf->cell(25,$alt,db_formatar($total_C,"f"),"BR",0,"R",0);
                  $pdf->cell(25,$alt,"60%","BR",0,"C",0);
                  $pdf->cell(50,$alt,db_formatar($perc_C,"f"),"B",1,"R",0);
                }
              }
              // OPERACOES DE CREDITO E DESPESAS DE CAPITAL
              if ($emite_oper==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"RECEITAS DE OPERAÇÕES DE CRÉDITO E DESPESAS DE CAPITAL","TBR",0,"C",0);
                $pdf->cell(50,$alt,"Valor Apurado At o ".$dados["periodo"],"TBR",0,"C",0);
                $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);
                $pdf->cell(90,$alt,"Receita de Operação de Crédito","R",0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_oper,"f"),"R",0,"R",0);
                $pdf->cell(50,$alt,db_formatar($saldo_oper,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"Despesa de Capital Líquida","BR",0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_despesa,"f"),"BR",0,"R",0);
                $pdf->cell(50,$alt,db_formatar($saldo_despesa,"f"),"B",1,"R",0);
              }
              // PROJECAO ATUARIAL DO RPPS
              if ($emite_proj==1){
                if ($num_rel > 0){
                  if ($num_rel > 7){
                    $pdf->cell(190,($alt+2),"Continução na página 2",0,1,"L",0);
                    $pdf->addpage();
                    $pdf->cell(190,($alt+2),"Continução da página 1",0,1,"L",0);
                  } else{
                    $pdf->cell(190,$alt,"","TB",1,"C",0);
                  }
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"PROJEÇÃO ATUARIAL DOS REGIMES DE PREVIDÊNCIA","TBR",0,"C",0);
                $pdf->cell(25,$alt,"Exercício","TBR",0,"C",0);
                $pdf->cell(25,$alt,"10 Exercício","TBR",0,"C",0);
                $pdf->cell(25,$alt,"20 Exercício","TBR",0,"C",0);
                $pdf->cell(25,$alt,"35 Exercício","TB",1,"C",0);
                $pdf->cell(90,$alt,"Regime Próprio de Previdência Social dos Servidores Públicos","R",0,"L",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"","R",0,"C",0);
                $pdf->cell(25,$alt,"",0,1,"C",0);
                $pdf->cell(90,$alt,"    Receita da Contribuição Patronal (III)","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_rec_patronal,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_PATRONAL,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_PATRONAL,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_PATRONAL,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"    Receitas Previdenciárias (IV)","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_rec,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_RECEITAS,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_RECEITAS,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_RECEITAS,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"    Despesas Previdenciárias (V)","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_desp,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_DESPESAS,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_DESPESAS,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_DESPESAS,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"    Resultado Previdenciário (IV - V)","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_res_rpps,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_RESULTADO,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_RESULTADO,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_RESULTADO,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"    Repasse Recebido para Cobertura de Déficit do RPPS (VI)","R",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_rep_rpps,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_10_EXERCICIO_REPASSE,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_20_EXERCICIO_REPASSE,"f"),"R",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($RPPS_35_EXERCICIO_REPASSE,"f"),0,1,"R",0);
              }
              // RECEITA DA ALIENACAO DE ATIVOS E APLICACAO DOS RECURSOS 
              if ($emite_alienacao==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"RECEITA DA ALIENAÇÃO DE ATIVOS E APLICAÇÃO DOS RECURSOS","TBR",0,"C",0);
                $pdf->cell(50,$alt,"Valor Apurado Até o ".$dados["periodo"],"TBR",0,"C",0);
                $pdf->cell(50,$alt,"Saldo a Realizar","TB",1,"C",0);
                $pdf->cell(90,$alt,"Receita de Capital Resultante da Alienação de Ativos","R",0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_alien,"f"),"R",0,"R",0);
                $pdf->cell(50,$alt,db_formatar($saldo_alien,"f"),0,1,"R",0);
                $pdf->cell(90,$alt,"Aplicação dos Recursos da Alienação de Ativos","BR",0,"L",0);
                $pdf->cell(50,$alt,db_formatar($total_recurso,"f"),"BR",0,"R",0);
                $pdf->cell(50,$alt,db_formatar($saldo_recurso,"f"),"B",1,"R",0);
              }
              // DESPESAS COM SAUDE
              if ($emite_saude==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90,$alt,"","TR",0,"C",0);
                $pdf->cell(25,$alt,"Valor apurado","R",0,"C",0);
                $pdf->cell(75,$alt,"Limite Constitucional Anual","B",1,"C",0);
                $pdf->cell(90,$alt,"DESPESAS COM AES E SERVIÇOS PÚBLICOS DE SAÚDE","R",0,"C",0);
                $pdf->cell(25,$alt,"At o ".$dados["periodo"],"R",0,"C",0);
                $pdf->cell(25,$alt,"% Mínimo a","R",0,"C",0);
                $pdf->cell(50,$alt,"% Aplicado Até o ".$dados["periodo"],0,1,"C",0);
                $pdf->cell(90,$alt,"","BR",0,"C",0);
                $pdf->cell(25,$alt,"","BR",0,"C",0);
                $pdf->cell(25,$alt,"Aplicar no Exercício","BR",0,"C",0);
                $pdf->cell(50,$alt,"","B",1,"C",0);
                $pdf->cell(90,$alt,"Despesas Próprias com Ações e Serviços Públicos de Saúde","BR",0,"L",0);
                $pdf->cell(25,$alt,db_formatar($total_saude,"f"),"BR",0,"R",0);
                $pdf->cell(25,$alt,db_formatar($MINIMO_APLICAVEL_NO_EXERCICIO_SAUDE,"f"),"BR",0,"R",0);
                $pdf->cell(50,$alt,db_formatar($perc_aplic_atebim,"f"),"B",1,"R",0);
              }
              // DESPESA DE PPP
              if ($emite_ppp==1){
                if ($num_rel > 0){
                  $pdf->cell(190,$alt,"","TB",1,"C",0);
                }
                
                $num_rel++;
                
                $pdf->cell(90, $alt,"DESPESAS DE CARÁCTER CONTINUADO DERIVADAS DE","TR",0,"C",0);
                $pdf->cell(100,$alt,"VALOR APURADO NO EXERCÍCIO CORRENTE","T",1,"C",0);
                $pdf->cell(90, $alt,"PPP'S CONTRATADAS","BR",0,"C",0);
                $pdf->cell(100,$alt,"","B",1,"C",0);
                $pdf->cell(90, $alt,"Total das Despesas/RCL (%)","BR",0,"L",0);
                $pdf->cell(100,$alt,db_formatar($perc_apurado,"f"),"B",1,"R",0);
              }
              ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
              $pdf->setfont('arial','b',7);
              $pdf->cell(190,$alt,"FONTE: Contabilidade","TB",0,"L",0);
              
              $pdf->ln(20);
              
              assinaturas(&$pdf,&$classinatura,'LRF');
              
              $pdf->Output();
              ?>