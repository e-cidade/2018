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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_orcparamseq_classe.php");

// include ("dbforms/db_relrestos.php");

$orcparamrel   = new cl_orcparamrel;
$classinatura  = new cl_assinatura;
$clempresto    = new cl_empresto;
$clorcparamseq = new cl_orcparamseq;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo $HTTP_SERVER_VARS["QUERY_STRING"]; exit;

// db_postmemory($HTTP_SERVER_VARS,2);exit;

if ($mesfin =='0') {// em javascript o mes 12 é 0
  $mesfin=12;
}
//variavei
$instituicao = str_replace("-",",",$db_selinstit);

$m_interferencias_ativas       = $orcparamrel->sql_parametro_instit("11","0","f",$instituicao,db_getsession("DB_anousu"));
$m_interferencias_passivas     = $orcparamrel->sql_parametro_instit("11","1","f",$instituicao,db_getsession("DB_anousu"));
$m_acrescimos_patrimoniais     = $orcparamrel->sql_parametro_instit("11","2","f",$instituicao,db_getsession("DB_anousu"));
$m_decrescimos_patrimoniais    = $orcparamrel->sql_parametro_instit("11","3","f",$instituicao,db_getsession("DB_anousu"));
$m_receita_orcamentaria        = $orcparamrel->sql_parametro_instit("11","4","f",$instituicao,db_getsession("DB_anousu"));
$m_receita_orcamentaria_outras = $orcparamrel->sql_parametro_instit("11","5","f",$instituicao,db_getsession("DB_anousu"));

$m_caixa                    = $orcparamrel->sql_parametro_instit("11","6","f",$instituicao,db_getsession("DB_anousu"));
$m_bancos_e_correspondentes = $orcparamrel->sql_parametro_instit("11","7","f",$instituicao,db_getsession("DB_anousu"));

$m_exatores   = $orcparamrel->sql_parametro_instit('11','8',"f",$instituicao,db_getsession("DB_anousu"));
$m_vinculados = $orcparamrel->sql_parametro_instit('11','9',"f",$instituicao,db_getsession("DB_anousu"));

$somador_receita = 0;
$somador_despesa = 0;

$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0) {
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
    $descr_inst .= $xvirg.$nomeinst;
  }
  $xvirg = ', ';
}
$head3 = "BALANÇO FINANCEIRO";
$head4 = "EXERCÍCIO ".db_getsession("DB_anousu");

if ($flag_abrev == false) {
  if (strlen($descr_inst) > 42) {
    $descr_inst = substr($descr_inst,0,150);
  }
}

$head5 = "INSTITUIÇÕES : ".$descr_inst;
$head6 = "ANEXO 13 - PERÍODO : ".strtoupper(db_mes($mesini))." A ".strtoupper(db_mes($mesfin));
$where = " c61_instit in (".str_replace('-',', ',$db_selinstit).") ";

$anousu = db_getsession("DB_anousu");
$dataini = db_getsession("DB_anousu").'-'.$mesini.'-01';
$datafin = db_getsession("DB_anousu").'-'.$mesfin.'-'.date('t',mktime(0,0,0,$mesfin,'01',db_getsession("DB_anousu")));


$db_filtro = ' o70_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
    $result_receita = db_receitasaldo(3,1,3,true,$db_filtro,$anousu,$dataini,$datafin);


    $sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
    $result_despesa = db_dotacaosaldo(3,3,2,true,$sele_work,$anousu,$dataini,$datafin);

    $aOrcParametro = array_merge(
      $m_interferencias_ativas       ,
      $m_interferencias_passivas     ,
      $m_acrescimos_patrimoniais     ,
      $m_decrescimos_patrimoniais    ,
      $m_receita_orcamentaria        ,
      $m_receita_orcamentaria_outras ,
      $m_caixa                    ,
      $m_bancos_e_correspondentes ,
      $m_exatores   ,
      $m_vinculados 
      );

    $result_balancete = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,$where,'','true','false','',$aOrcParametro);

$result_receita_rp = db_dotacaosaldo(7,3,2,true,$sele_work,$anousu,$dataini,$datafin);
// db_criatabela($result_receita);
//db_criatabela($result_despesa_rp);
// db_criatabela($result_balancete);
// exit;

// a função sql_rp
$db_filtro = ' e60_instit in (' . str_replace('-',', ',$db_selinstit) . ')';
    $sele_work1 = '';
    // caso queira algum recurso colocar aqui
    $sqlperiodo = $clempresto->sql_rp(db_getsession("DB_anousu"), $db_filtro, $dataini, $datafin, $sele_work1);
    $result_despesa_rp=pg_exec($sqlperiodo);


    //-- receita orcamentaria
    //--
    $ma1_cont = 0;
    $ma1_descr =  array();
    $ma1_valor =  array();
    $mb1_cont = 0;
    $mb1_descr =  array();
    $mb1_valor =  array();
    $total_receitas = 0;
    $anousu = db_getsession("DB_anousu");

    ///////////////////////////////-------------------------------------------//
    for ($i=0; $i<pg_numrows($result_receita); $i++) {
    db_fieldsmemory($result_receita,$i);
    if ($o57_descr == '') {
      continue;
    }
    $nivel = db_le_mae_sistema($o57_fonte,true);
    if ($nivel > 2) {
      $espaco = '    ';
    } else {
      $espaco = '';
    }
    $ma1_descr[$ma1_cont] = $espaco.$o57_descr;
    $ma1_valor[$ma1_cont] = $saldo_arrecadado_acumulado;
    $ma1_cont ++;
    //   if($o57_fonte == '400000000000000' || $o57_fonte == "900000000000000"){ // o recordset já vem com total nesse estrutural
    if (db_conplano_grupo($anousu,$o57_fonte,9004) == true) {
      $somador_receita += $saldo_arrecadado_acumulado;
      $total_receitas  = $somador_receita;
    }
    }
    // -- preenche matriz com despesa orçamentaria
    for ($i=0; $i<pg_numrows($result_despesa); $i++) {
      db_fieldsmemory($result_despesa,$i);
      //if ($o57_descr == '') {
      //  continue;
      $mb1_descr[$mb1_cont] = '     '.$o52_descr;
      $mb1_valor[$mb1_cont] = ($empenhado-$anulado); // Empenhado liquido
      $mb1_cont ++;
      $somador_despesa += ($empenhado- $anulado);
    }
    //------ interferencias ----------
    $cont_a=0;
    $cont_b=0;
    $ma_interf_descr = array();
    $ma_interf_valor = array();
    $mb_interf_descr = array();
    $mb_interf_valor = array();

    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_interferencias_ativas)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_interferencias_ativas); $x++){
          if ($estrutural == $m_interferencias_ativas[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        $ma_interf_descr[$cont_a] = $c60_descr;
        $ma_interf_valor[$cont_a] = $saldo_final;
        $cont_a ++;
        $somador_receita += $saldo_final;
      }

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_interferencias_passivas)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_interferencias_passivas); $x++){
          if ($estrutural == $m_interferencias_passivas[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        $mb_interf_descr[$cont_b] = $c60_descr;
        $mb_interf_valor[$cont_b] = $saldo_final;
        $cont_b ++;
        $somador_despesa += $saldo_final;
      }
    }

    //------------------------------------//-------------------------------
    $ma2_cont = 0;
    $ma2_descr =  array();
    $ma2_valor =  array();
    $mb2_cont = 0;
    $mb2_descr =  array();
    $mb2_valor =  array();
    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_acrescimos_patrimoniais)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_acrescimos_patrimoniais); $x++){
          if ($estrutural == $m_acrescimos_patrimoniais[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        $ma2_descr[$ma2_cont] = $c60_descr;
        $ma2_valor[$ma2_cont] = $saldo_final;
        $ma2_cont ++;
        $somador_receita += $saldo_final;
      }

      $flag_contar = false;
      //if ($instit != 0) {
      //  if (in_array($v_elementos,$m_decrescimos_patrimoniais)) {
      //    $flag_contar = true;
      //  }
      //} else {
        for($x = 0; $x < count($m_decrescimos_patrimoniais); $x++){
          if ($estrutural == $m_decrescimos_patrimoniais[$x][0] || in_array($v_elementos,$m_decrescimos_patrimoniais) ) {
            $flag_contar = true;
            break;
          }
        }
      //}

      if ($flag_contar == true) {
        $mb2_descr[$mb2_cont] = $c60_descr;
        $mb2_valor[$mb2_cont] = $saldo_final;
        $mb2_cont ++;
        $somador_despesa += $saldo_final;
      }
    }
    //------------------------------------//-------------------------------
    // receitas e despesas extra -orçamentárias
    //#//
    //# modelo [p/c]: publicação/conferencia
    $ma3_cont = 0;
    $ma3_descr =  array();
    $ma3_valor =  array();
    $mb3_cont = 0;
    $mb3_descr =  array();
    $mb3_valor =  array();
    $ma3_vlr_rp=0;
    // restos a pagar
    $mb3_vlr_rp=0;
    $ma3_vlr_div=0;
    // serviços da dívida
    $mb3_vlr_div=0;
    $ma3_vlr_dep=0;
    // depositos
    $mb3_vlr_dep=0;
    $ma3_vlr_op=0;
    // outras operações
    $mb3_vlr_op=0;
    if ($modelo=='p') {
      // receita extras e serviços da dívida
      for ($i=0; $i<pg_numrows($result_receita_rp); $i++) {
        db_fieldsmemory($result_receita_rp,$i);
        if (substr($o58_elemento,0,3)=='332'    ||    substr($o58_elemento,0,3)=='346'  ) {
          $ma3_vlr_div+=$atual_a_pagar + $atual_a_pagar_liquidado;
          // serviços da dívida
        } else {
          $ma3_vlr_rp +=$atual_a_pagar + $atual_a_pagar_liquidado;
        }
      }
      // despesas extras e serviços da dívida
      for ($i=0; $i<pg_numrows($result_despesa_rp); $i++) {
        db_fieldsmemory($result_despesa_rp,$i);
        if (substr($o56_elemento,0,3)=='332'    ||    substr($o56_elemento,0,3)=='346'  ) {
          $mb3_vlr_div+=$vlrpag +$vlranu;
          // serviços da dívida
        } else {
          $mb3_vlr_rp +=$vlrpag +$vlranu;
          // restos a pagar
        }
      }
      $aInstit    = explode("-",$db_selinstit);
      $iTotInstit = count($aInstit);
      for ($iInd = 0; $iInd < $iTotInstit; $iInd++) {

        @pg_query("drop table work_pl");
        $rsOutros = db_planocontassaldo_matriz(db_getsession("DB_anousu"),$dataini,$datafin,false,"c61_instit={$aInstit[$iInd]}",'','true','false','',$aOrcParametro);
        for ($i = 0; $i < pg_numrows($rsOutros); $i++) {

          db_fieldsmemory($rsOutros,$i);
          
          $estrutural  = $estrutural;
          $instit      = $c61_instit;
          $v_elementos = array($estrutural,$instit);

          $flag_contar = false;
          if ($instit != 0) {
            if (in_array($v_elementos,$m_receita_orcamentaria)) {
              $flag_contar = true;
            }
          } else {
            for($x = 0; $x < count($m_receita_orcamentaria); $x++){
              if ($estrutural == $m_receita_orcamentaria[$x][0]) {
                $flag_contar = true;
                break;
              }
            }
          }

          if ($flag_contar == true) {
            $ma3_vlr_dep += $saldo_anterior_credito;
            // depositos
          }

          $flag_contar = false;
          if ($instit != 0) {
            if (in_array($v_elementos,$m_receita_orcamentaria_outras)) {
              $flag_contar = true;
            }
          } else {
            for($x = 0; $x < count($m_receita_orcamentaria_outras); $x++){
              if ($estrutural == $m_receita_orcamentaria_outras[$x][0]) {
                $flag_contar = true;
                break;
              }
            }
          }

          if ($flag_contar == true) {
            $ma3_vlr_op+=$saldo_anterior_credito;
            // outras operações
          }

          // parte da despesa extra-orçamentária
          $flag_contar = false;
          if ($instit != 0) {
            if (in_array($v_elementos,$m_receita_orcamentaria)) {
              $flag_contar = true;
            }
          } else {
            for($x = 0; $x < count($m_receita_orcamentaria); $x++){
              if ($estrutural == $m_receita_orcamentaria[$x][0]) {
                $flag_contar = true;
                break;
              }
            }
          }

          if ($flag_contar == true) {
            $mb3_vlr_dep+=$saldo_anterior_debito;
          }

          $flag_contar = false;
          if ($instit != 0) {
            if (in_array($v_elementos,$m_receita_orcamentaria_outras)) {
              $flag_contar = true;
            }
          } else {
            for($x = 0; $x < count($m_receita_orcamentaria_outras); $x++){
              if ($estrutural == $m_receita_orcamentaria_outras[$x][0]) {
                $flag_contar = true;
                break;
              }
            }
          }

          if ($flag_contar == true) {
            $mb3_vlr_op+=$saldo_anterior_debito;
            //outras operações
          }
        }
      }
      $ma3_descr[$ma3_cont] = "RESTOS A PAGAR";
      $ma3_valor[$ma3_cont]  =$ma3_vlr_rp;
      $ma3_cont ++;
      $ma3_descr[$ma3_cont] = "SERVIÇO DA DÍVIDA A PAGAR";
      $ma3_valor[$ma3_cont]  =$ma3_vlr_div;
      $ma3_cont ++;
      $ma3_descr[$ma3_cont] = "DEPÓSITOS";
      $ma3_valor[$ma3_cont]  =$ma3_vlr_dep;
      $ma3_cont ++;
      $ma3_descr[$ma3_cont] = "OUTRAS OPERAÇÕES";
      $ma3_valor[$ma3_cont]  =$ma3_vlr_op;

      $mb3_descr[$mb3_cont] = "RESTOS A PAGAR";
      $mb3_valor[$mb3_cont]  =$mb3_vlr_rp;
      $mb3_cont ++;
      $mb3_descr[$mb3_cont] = "SERVIÇO DA DÍVIDA A PAGAR";
      $mb3_valor[$mb3_cont]  =$mb3_vlr_div;
      $mb3_cont ++;
      $mb3_descr[$mb3_cont] = "DEPÓSITOS";
      $mb3_valor[$mb3_cont]  =$mb3_vlr_dep;
      $mb3_cont ++;
      $mb3_descr[$mb3_cont] = "OUTRAS OPERAÇÕES";
      $mb3_valor[$mb3_cont] = $mb3_vlr_op;

      $somador_receita +=    $ma3_vlr_rp+ $ma3_vlr_div+ $ma3_vlr_dep +$ma3_vlr_op;
      $somador_despesa +=   $mb3_vlr_rp+ $mb3_vlr_div+ $mb3_vlr_dep +$mb3_vlr_op;
    } else {
      // expande as contas de receta/despesa extra
      for ($i=0; $i<pg_numrows($result_balancete); $i++) {
        db_fieldsmemory($result_balancete,$i);

        $estrutural  = $estrutural;
        $instit      = $c61_instit;
        $v_elementos = array($estrutural,$instit);

        $flag_contar = false;
        if ($instit != 0) {
          if (in_array($v_elementos,$m_receita_orcamentaria)) {
            $flag_contar = true;
          }
        } else {
          for($x = 0; $x < count($m_receita_orcamentaria); $x++){
            if ($estrutural == $m_receita_orcamentaria[$x][0]) {
              $flag_contar = true;
              break;
            }
          }
        }

        if ($flag_contar == true) {
          $ma3_descr[$ma3_cont] = $estrutural."-".$c60_descr;
          $ma3_valor[$ma3_cont] = $saldo_anterior_credito;
          $ma3_cont ++;
          $somador_receita += $saldo_anterior_credito;
        }

        $flag_contar = false;
        if ($instit != 0) {
          if (in_array($v_elementos,$m_despesa_orcamentaria)) {
            $flag_contar = true;
          }
        } else {
          for($x = 0; $x < count($m_despesa_orcamentaria); $x++){
            if ($estrutural == $m_despesa_orcamentaria[$x][0]) {
              $flag_contar = true;
              break;
            }
          }
        }

        if ($flag_contar == true) {
          $mb3_descr[$mb3_cont] = $estrutural."-".$c60_descr;
          $mb3_valor[$mb3_cont] = $saldo_anterior_debito;
          $mb3_cont ++;
          $somador_despesa += $saldo_anterior_debito;
        }
      }
      // -- adiciona no final
      for ($i=0; $i<pg_numrows($result_balancete); $i++) {
        db_fieldsmemory($result_balancete,$i);

        $estrutural  = $estrutural;
        $instit      = $c61_instit;
        $v_elementos = array($estrutural,$instit);

        $flag_contar = false;
        if ($instit != 0) {
          if (in_array($v_elementos,$m_receita_orcamentaria_outras)) {
            $flag_contar = true;
          }
        } else {
          for($x = 0; $x < count($m_receita_orcamentaria_outras); $x++){
            if ($estrutural == $m_receita_orcamentaria_outras[$x][0]) {
              $flag_contar = true;
              break;
            }
          }
        }

        if ($flag_contar == true) {
          $ma3_descr[$ma3_cont] = $estrutural."-".$c60_descr;
          $ma3_valor[$ma3_cont] = $saldo_anterior_credito;
          $ma3_cont ++;
          $somador_receita += $saldo_anterior_credito;
        }

        $flag_contar = false;
        if ($instit != 0) {
          if (in_array($v_elementos,$m_despesa_orcamentaria_outras)) {
            $flag_contar = true;
          }
        } else {
          for($x = 0; $x < count($m_despesa_orcamentaria_outras); $x++){
            if ($estrutural == $m_despesa_orcamentaria_outras[$x][0]) {
              $flag_contar = true;
              break;
            }
          }
        }

        if ($flag_contar == true) {
          $mb3_descr[$mb3_cont] = $estrutural."-".$c60_descr;
          $mb3_valor[$mb3_cont] = $saldo_anterior_debito;
          $mb3_cont ++;
          $somador_despesa += $saldo_anterior_debito;
        }
      }
    }

    //------------------------------------//-------------------------------
    $ma7_cont  = 0;
    $ma7_descr = array();
    $ma7_valor = array();
    $mb7_cont  = 0;
    $mb7_descr = array();
    $mb7_valor = array();

    $ma7_valor[$ma7_cont] = 0;
    $mb7_valor[$mb7_cont] = 0;

    $res_orcparamseq = $clorcparamseq->sql_record($clorcparamseq->sql_query_file(11,6,"o69_descr"));
    if ($clorcparamseq->numrows > 0) {
      db_fieldsmemory($res_orcparamseq,0);
    }

    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_caixa)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_caixa); $x++){
          if ($estrutural == $m_caixa[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        if ($ma7_cont == 0) {
          if (isset($o69_descr) && trim($o69_descr) != "") {
            $ma7_descr[$ma7_cont] = $o69_descr;
          } else {
            $ma7_descr[$ma7_cont] = $c60_descr;
          }

          $ma7_cont++;
          $mb7_cont++;
        }

        $ma7_valor[0]    += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");
        $somador_receita += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");

        $mb7_descr[0]     = $ma7_descr[0];
        $mb7_valor[0]    += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
        $somador_despesa += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
      }
    }

    if ($mb7_cont == 0) {
      if (isset($o69_descr) && trim($o69_descr) != "") {
        $ma7_descr[$ma7_cont] = $o69_descr;
      } else {
        $ma7_descr[$ma7_cont] = $c60_descr;
      }

      $ma7_valor[$ma7_cont] = 0;

      $mb7_descr[$mb7_cont] = $ma7_descr[$ma7_cont];
      $mb7_valor[$mb7_cont] = 0;

      $mb7_cont++;
      $ma7_cont++;
    }
    //------------------------------------//-------------------------------
    $ma6_cont  = 0;
    $ma6_descr = array();
    $ma6_valor = array();
    $mb6_cont  = 0;
    $mb6_descr = array();
    $mb6_valor = array();

    $ma6_valor[$ma6_cont] = 0;
    $mb6_valor[$mb6_cont] = 0;

    $res_orcparamseq = $clorcparamseq->sql_record($clorcparamseq->sql_query_file(11,7,"o69_descr"));
    if ($clorcparamseq->numrows > 0) {
      db_fieldsmemory($res_orcparamseq,0);
    }

    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_bancos_e_correspondentes)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_bancos_e_correspondentes); $x++){
          if ($estrutural == $m_bancos_e_correspondentes[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        if ($ma6_cont == 0) {
          if (isset($o69_descr) && trim($o69_descr) != "") {
            $ma6_descr[$ma6_cont] = $o69_descr;
          } else {
            $ma6_descr[$ma6_cont] = $c60_descr;
          }

          $ma6_cont++;
          $mb6_cont++;
        }

        $ma6_valor[0]    += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");
        $somador_receita += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");

        $mb6_descr[0]     = $ma6_descr[0];
        $mb6_valor[0]    += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
        $somador_despesa += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
      }
    }

    if ($mb6_cont == 0) {
      if (isset($o69_descr) && trim($o69_descr) != "") {
        $ma6_descr[$ma6_cont] = $o69_descr;
      } else {
        $ma6_descr[$ma6_cont] = $c60_descr;
      }

      $ma6_valor[$ma6_cont] = 0;

      $mb6_descr[$mb6_cont] = $ma6_descr[$ma6_cont];
      $mb6_valor[$mb6_cont] = 0;

      $mb6_cont++;
      $ma6_cont++;
    }
    //------------------------------------//-------------------------------
    $ma4_cont  = 0;
    $ma4_descr = array();
    $ma4_valor = array();
    $mb4_cont  = 0;
    $mb4_descr = array();
    $mb4_valor = array();

    $ma4_valor[$ma4_cont] = 0;
    $mb4_valor[$mb4_cont] = 0;

    $res_orcparamseq = $clorcparamseq->sql_record($clorcparamseq->sql_query_file(11,8,"o69_descr"));
    if ($clorcparamseq->numrows > 0) {
      db_fieldsmemory($res_orcparamseq,0);
    }

    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_exatores)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_exatores); $x++){
          if ($estrutural == $m_exatores[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        if ($ma4_cont == 0) {
          if (isset($o69_descr) && trim($o69_descr) != "") {
            $ma4_descr[$ma4_cont] = $o69_descr;
          } else {
            $ma4_descr[$ma4_cont] = $c60_descr;
          }

          $ma4_cont++;
          $mb4_cont++;
        }

        $ma4_valor[0]    += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");
        $somador_receita += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");

        $mb4_descr[0]     = $ma4_descr[0];
        $mb4_valor[0]    += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
        $somador_despesa += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
      }
    }

    if ($mb4_cont == 0) {
      $ma4_descr[$ma4_cont] = $o69_descr;
      $ma4_valor[$ma4_cont] = 0;

      $mb4_descr[$mb4_cont] = $o69_descr;
      $mb4_valor[$mb4_cont] = 0;

      $mb4_cont++;
      $ma4_cont++;
    }
    //------------------------------------//-------------------------------

    $ma5_cont  = 0;
    $ma5_descr = array();
    $ma5_valor = array();
    $mb5_cont  = 0;
    $mb5_descr = array();
    $mb5_valor = array();

    $ma5_valor[$ma5_cont] = 0;
    $mb5_valor[$mb5_cont] = 0;

    $res_orcparamseq = $clorcparamseq->sql_record($clorcparamseq->sql_query_file(11,9,"o69_descr"));
    if ($clorcparamseq->numrows > 0) {
      db_fieldsmemory($res_orcparamseq,0);
    }

    for ($i=0; $i<pg_numrows($result_balancete); $i++) {
      db_fieldsmemory($result_balancete,$i);

      $estrutural  = $estrutural;
      $instit      = $c61_instit;
      $v_elementos = array($estrutural,$instit);

      $flag_contar = false;
      if ($instit != 0) {
        if (in_array($v_elementos,$m_vinculados)) {
          $flag_contar = true;
        }
      } else {
        for($x = 0; $x < count($m_vinculados); $x++){
          if ($estrutural == $m_vinculados[$x][0]) {
            $flag_contar = true;
            break;
          }
        }
      }

      if ($flag_contar == true) {
        if ($ma5_cont == 0) {
          if (isset($o69_descr) && trim($o69_descr) != "") {
            $ma5_descr[$ma5_cont] = $o69_descr;
          } else {
            $ma5_descr[$ma5_cont] = $c60_descr;
          }

          $ma5_cont++;
          $mb5_cont++;
        }

        $ma5_valor[0]    += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");
        $somador_receita += anexo13_retorna_saldo($saldo_anterior,$sinal_anterior,"A");

        $mb5_descr[0]     = $ma5_descr[0];
        $mb5_valor[0]    += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
        $somador_despesa += anexo13_retorna_saldo($saldo_final,$sinal_final,"A");
      }
    }

    if ($mb5_cont == 0) {
      $ma5_descr[$ma5_cont] = $o69_descr;
      $ma5_valor[$ma5_cont] = 0;

      $mb5_descr[$mb5_cont] = $o69_descr;
      $mb5_valor[$mb5_cont] = 0;

      $mb5_cont++;
      $ma5_cont++;
    }
    //------------------------------------//-------------------------------
    /*
       print_r($ma1_descr);
       print_r($ma1_valor);
       print_r($mb1_descr);
       print_r($mb1_valor);
       exit;
     */

    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->setfillcolor(235);
    $pdf->setfont('arial','b',7);
    $alt            = 4;
    $pagina         = 1;

    $pdf->addpage();
    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"R E C E I T A S",0,0,"C",0);
    $pdf->cell(95,$alt,"D E S P E S A S",0,1,"C",0);
    $pdf->ln(3);

    $numreg = (sizeof($ma1_descr)>sizeof($mb1_descr)?sizeof($ma1_descr):sizeof($mb1_descr));
    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"RECEITA ORÇAMENTÁRIA",0,0,"L",0);
    $pdf->cell(95,$alt,"DESPESA ORÇAMENTÁRIA",0,1,"L",0);
    $pdf->setfont('arial','',6);
    $tot_interno_a=$total_receitas;
    $tot_interno_b=0;
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma1_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma1_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma1_valor[$i]+0,'f'),0,0,"R",0);
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb1_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb1_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb1_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb1_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }
    $pdf->setfont('arial','',6);
    // imprime total interno
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_a,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_b,'f'),0,1,"R",0);
    $pdf->Ln(4);


    // interferencias
    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"INTERFERENCIAS ATIVAS",0,0,"L",0);
    $pdf->cell(95,$alt,"INTERFERENCIAS PASSIVAS",0,1,"L",0);
    $pdf->setfont('arial','',6);
    $numreg = (sizeof($ma_interf_descr)>sizeof($mb_interf_descr)?sizeof($ma_interf_descr):sizeof($mb_interf_descr));
    $tot_interno_a=0;
    $tot_interno_b=0;
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma_interf_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma_interf_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma_interf_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma_interf_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb_interf_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb_interf_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb_interf_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb_interf_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }
    $pdf->setfont('arial','',6);
    // imprime total interno
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_a,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_b,'f'),0,1,"R",0);
    $pdf->Ln(4);
    // ---------------------------------------------


    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"ACRESCIMOS PATRIMONIAIS",0,0,"L",0);
    $pdf->cell(95,$alt,"DECRESCIMOS PATRIMONIAIS",0,1,"L",0);
    $pdf->setfont('arial','',6);
    $numreg = (sizeof($ma2_descr)>sizeof($mb2_descr)?sizeof($ma2_descr):sizeof($mb2_descr));
    $tot_interno_a=0;
    $tot_interno_b=0;
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma2_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma2_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma2_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma2_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb2_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb2_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb2_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb2_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }
    $pdf->setfont('arial','',6);
    // imprime total interno
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_a,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_b,'f'),0,1,"R",0);
    $pdf->Ln(4);


    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"RECEITA EXTRA-ORÇAMENTARIA",0,0,"L",0);
    $pdf->cell(95,$alt,"DESPESA EXTRA-ORÇAMENTARIA",0,1,"L",0);
    $pdf->setfont('arial','',6);
    $numreg = (sizeof($ma3_descr)>sizeof($mb3_descr)?sizeof($ma3_descr):sizeof($mb3_descr));
    $tot_interno_a=0;
    $tot_interno_b=0;
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma3_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma3_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma3_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma3_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb3_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb3_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb3_valor[$i],'f'),0,0,"R",0);
        $tot_interno_b += $mb3_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }
    $pdf->setfont('arial','',6);
    // imprime total interno
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_a,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_b,'f'),0,1,"R",0);
    $pdf->Ln(4);



    $pdf->setfont('arial','b',8);
    $pdf->cell(95,$alt,"SALDO DO EXERCÍCIO ANTERIOR",0,0,"L",0);
    $pdf->cell(95,$alt,"SALDO PARA O EXERCÍCIO SEGUINTE",0,1,"L",0);
    $pdf->setfont('arial','',6);

    $tot_interno_a=0;
    $tot_interno_b=0;

    // CAIXA
    $numreg = (sizeof($ma7_descr)>sizeof($mb7_descr)?sizeof($ma7_descr):sizeof($mb7_descr));
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma7_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma7_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma7_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma7_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb7_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb7_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb7_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb7_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }

    // BANCOS E CORRESPONDENTES
    $numreg = (sizeof($ma6_descr)>sizeof($mb6_descr)?sizeof($ma6_descr):sizeof($mb6_descr));
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma6_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma6_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma6_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma6_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb6_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb6_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb6_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb6_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }

    // EXATORES
    $numreg = (sizeof($ma4_descr)>sizeof($mb4_descr)?sizeof($ma4_descr):sizeof($mb4_descr));
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma4_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma4_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma4_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma4_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb4_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb4_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb4_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb4_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }

    // VINCULADOS EM C/C BANCARIAS
    $numreg = (sizeof($ma5_descr)>sizeof($mb5_descr)?sizeof($ma5_descr):sizeof($mb5_descr));
    for ($i=0; $i<$numreg; $i++) {
      if (isset($ma5_descr[$i]) ) {
        $pdf->cell(70,$alt,$ma5_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($ma5_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_a += $ma5_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      if (isset($mb5_descr[$i]) ) {
        $pdf->cell(70,$alt,$mb5_descr[$i],0,0,"L",0,'','.');
        $pdf->cell(25,$alt,db_formatar($mb5_valor[$i]+0,'f'),0,0,"R",0);
        $tot_interno_b += $mb5_valor[$i];
      } else {
        $pdf->cell(95,$alt,"",0,0,"L",0);
      }
      $pdf->setxy($pdf->lMargin,$pdf->gety()+$alt);
    }

    $pdf->setfont('arial','',6);
    // imprime total interno
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_a,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'SUBTOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($tot_interno_b,'f'),0,1,"R",0);
    $pdf->Ln(4);

    $pdf->setfont('arial','b',8);
    $pdf->cell(70,$alt,'TOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($somador_receita,'f'),0,0,"R",0);
    $pdf->cell(70,$alt,'TOTAL',0,0,"L",0,'','.');
    $pdf->cell(25,$alt,db_formatar($somador_despesa,'f'),0,1,"R",0);

		$pdf->Ln(2);
		$pdf->setfont('arial','',5);
		notasExplicativas(&$pdf,$iCodRel,"2S",190);    

    $pdf->Ln(25);
    $pdf->setfont('arial','',8);    
    assinaturas(&$pdf,&$classinatura,'BG');

    //include("fpdf151/geraarquivo.php");

    function anexo13_retorna_saldo($saldo, $sinal, $grupo) {
      if ($grupo == "A" and $sinal == "C") {
        $saldo = $saldo *-1;
      } elseif ($grupo == "P" and $sinal == "D") {
        $saldo = $saldo *-1;
      }
      return $saldo;
    }

    $pdf->Output();


    ?>