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

  require_once ("libs/db_sql.php");
  require_once ("fpdf151/pdf.php");
  require_once ("classes/db_arrecad_classe.php");
  require_once ("classes/db_termo_classe.php");
  
  $clarrecad = new cl_arrecad;
  $cltermo = new cl_termo;
  $cltermo->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label('v07_parcel');
  $clrotulo->label('z01_numcgm');
  $clrotulo->label('z01_nome');
  $clrotulo->label('k00_tipo');
  $clrotulo->label('k00_numpre');
  $clrotulo->label('k00_numtot');
  $clrotulo->label('k00_descr');
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  
  db_postmemory($HTTP_SERVER_VARS);
  
  $datausu = date("Y/m/d", db_getsession("DB_datausu"));
  
  $where_int = "";
  $where     = "";
  $and       = "";

  if ($listatipo != "") {
    
    if (isset ($vertipo) and $vertipo == "com") {
      
      $where = $where." $and arretipo.k00_tipo in  ($listatipo)";
      $and   = " and ";
    } else {
      
      $where = $where." $and arretipo.k00_tipo not in  ($listatipo)";
      $and   = " and ";
    }
  }
  
  if ($listaregra != "") {
    
    if (isset ($verregra) and $verregra == "com") {
      
      $where = $where." $and termo.v07_desconto  in  ($listaregra)";
      $and   = " and ";
    } else {
      
      $where = $where." $and termo.v07_desconto not in  ($listaregra)";
      $and   = " and ";
    }
  }
  
  if (($data != "--") && ($data1 != "--")) {
    
    $where = $where." $and termo.v07_dtlanc  between '$data' and '$data1'  ";
    $and   = " and ";
    $data  = db_formatar($data, "d");
    $data1 = db_formatar($data1, "d");
    $info  = "De $data até $data1.";
  } else if ($data != "--") {
    
    $where = $where." $and termo.v07_dtlanc >= '$data'  ";
    $and = " and ";
    $data = db_formatar($data, "d");
    $info = "Apartir de $data.";
  } else if ($data1 != "--") {
    
    $where = $where."$and termo.v07_dtlanc <= '$data1'   ";
    $and = " and ";
    $data1 = db_formatar($data1, "d");
    $info = "Até $data1.";
  }
  
  if ($considera=="C"){
    
    $between = " between  ";
    $oprini = " >= ";  
    $oprfim = " <= ";
  }else if ($considera=="D"){
    
    $between = " not between  ";
    $oprini = " <= ";  
    $oprfim = " >= ";
  }
  
  if (($numini != "") && ($numfim != "")) {
    
    $where .= $and." arrecad.k00_numpar {$between} {$numini} and {$numfim} ";
    $and    = " and ";
    $head6  = "NUMERO DA PARCELA EM ATRASO: {$numini} a {$numfim}";
  } else if ($numini != "") {
    
    $where .= $and . " arrecad.k00_numpar {$oprini} {$numini} ";
    $and    = " and ";
    $head6  = "NUMERO DA PARCELA EM ATRASO - inicial: $numini";
  } else if ($numfim != "") {
    
    $where .= $and . " arrecad.k00_numpar {$oprfim} {$numini} ";
    $and    = " and ";
    $head6  = "NUMERO DA PARCELA EM ATRASO - final: {$numfim}";
  } else {
    
    $head6 = "NUMERO DA PARCELA EM ATRASO: não especificado";
  }
  
  if (!empty($vencimentoini) && !empty($vencimentofim)) {
    
    $where .= "and arrecad.k00_dtvenc BETWEEN '{$vencimentoini}' and '{$vencimentofim}'";
    $head7  = "VENCIMENTO: " . date("d/m/Y", strtotime($vencimentoini)) . " ATÉ ". date("d/m/Y", strtotime($vencimentofim));
  } else if (!empty($vencimentoini)) {
    
    $where .= "and arrecad.k00_dtvenc >= '{$vencimentoini}'";
    $head7  = "VENCIMENTO: INICIAL " . date("d/m/Y", strtotime($vencimentoini));
  } else if (!empty($vencimentofim)) {
    
    $where .= "and arrecad.k00_dtvenc <= '{$vencimentofim}' ";
    $head7  = "VENCIMENTO: FINAL " . date("d/m/Y", strtotime($vencimentofim));
  }
  
  if (empty($head7) && ($numini != "" or $numfim != "")) {
    $where .= "and arrecad.k00_dtvenc < '" . date("Y-m-d", db_getsession("DB_datausu")) . "'";
  }
  
  $where_int .= $where;
  
  if (($quantini != "") && ($quantfim != "")) {
    
    $where_int .= " {$and} arrecad.k00_dtvenc < '" . date("Y-m-d", db_getsession("DB_datausu")) . "'";
    $where     .= $and . " x.numpar between {$quantini} and {$quantfim} ";
    $and        = " and ";
    $head5      = "QUANTIDADE DE PARCELAS EM ATRASO: $quantini a $quantfim";
  } else if ($quantini != "") {
    
    $where_int .= " {$and} arrecad.k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."'";
    $where     .= $and." x.numpar >= $quantini ";
    $and        = " and ";
    $head5      = "QUANTIDADE DE PARCELAS EM ATRASO - inicial: $quantini";
  } else if ($quantfim != "") {
    
    $where_int .= " $and arrecad.k00_dtvenc < '".date("Y-m-d", db_getsession("DB_datausu"))."'";
    $where     .= $and." x.numpar <= $quantfim ";
    $and        = " and ";
    $head5      = "QUANTIDADE DE PARCELAS EM ATRASO - final: $quantfim";
  } else {
     
    $head5      = "QUANTIDADE DE PARCELAS EM ATRASO: nao especificado";
  }
  if ($where!=""){
    $where = " where ".$where;
  }
  if ($where_int != "") { 
   
    $where_int = " where ".$where_int;
  }
  
  $ordenacao = "v07_numpre";
  
  if ($ordem == "NP") {
   
    $ordenacao = "v07_numpre, k00_numpar";
  } elseif ($ordem == "DTPV") {
   
    $ordenacao = "menor_data_venc";
  } elseif ($ordem == "NOME") {
   
    $ordenacao = "z01_nome";
  }
  
  $sql  = " select distinct                                                                                         \n";
  $sql .= "        v07_parcel, v07_numpre, v07_numcgm,                                                              \n";
  $sql .= "        k00_numpar, v07_dtlanc, k00_dtvenc,                                                              \n";
  $sql .= "        k00_tipo  , k00_descr , z01_nome  ,                                                              \n";
  $sql .= "        z01_telef , v07_totpar, k00_numtot,                                                              \n";
  $sql .= "        v07_valor , fc_calcula, menor_data_venc                                                          \n";
  $sql .= "   from ( select termo.v07_parcel  , termo.v07_numpre  , termo.v07_numcgm  ,                             \n";
  $sql .= "                 arrecad.k00_numpar, termo.v07_dtlanc  , arrecad.k00_dtvenc,                             \n";
  $sql .= "                 arrecad.k00_tipo  , arretipo.k00_descr, cgm.z01_nome      ,                             \n";
  $sql .= "                 cgm.z01_telef     , termo.v07_totpar  , termo.v07_valor   ,                             \n";
  $sql .= "                 arrecad.k00_numtot, menor_data_venc   ,                                                 \n";
  $sql .= "                 fc_calcula(termo.v07_numpre, arrecad.k00_numpar, 0,                                     \n";
  $sql .= "                            current_date, current_date," . db_getsession("DB_anousu") . ")               \n";
  $sql .= "            from ( select arrecad.k00_numpre,                                                            \n";
  $sql .= "                          count(distinct arrecad.k00_numpar) as numpar,                                  \n";
  $sql .= "                          min(k00_dtvenc) as menor_data_venc                                             \n";
  $sql .= "                     from termo                                                                          \n";
  $sql .= "                          inner join arrecad  on arrecad.k00_numpre = termo.v07_numpre                   \n";
  $sql .= "                                             and termo.v07_instit   = " . db_getsession('DB_instit') . " \n";
  $sql .= "                          inner join arretipo on arrecad.k00_tipo   = arretipo.k00_tipo                  \n";
  $sql .= "                     {$where_int}                                                                        \n";
  $sql .= "                     group by arrecad.k00_numpre                                                         \n";
  $sql .= "                 ) as x                                                                                  \n";
  $sql .= "                 inner join termo    on x.k00_numpre       = termo.v07_numpre                            \n";
  $sql .= "                                    and termo.v07_instit   = " . db_getsession('DB_instit') . "          \n";
  $sql .= "                 inner join arrecad  on arrecad.k00_numpre = termo.v07_numpre                            \n";
  $sql .= "                 inner join arretipo on arrecad.k00_tipo   = arretipo.k00_tipo                           \n";
  $sql .= "                 inner join cgm      on termo.v07_numcgm   = cgm.z01_numcgm                              \n";
  $sql .= "            {$where}                                                                                     \n";
  $sql .= "        ) as y                                                                                           \n";
  $sql .= "  order by {$ordenacao}                                                                                  \n";
  
  $head2 = 'RELATÓRIO DA POSIÇÃO DOS PARCELAMENTOS';
  $head3 = @$info;
  $head4 = "ORDEM: ";
  if ($ordem == "NP") {
   
    $head4 .= "numpre/parcelamento ";
  } elseif ($ordem == "DTPV") {
   
    $head4 .= "data vcto primeira parcela";
  } elseif ($ordem == "NOME") {
   
    $head4 .= "nome do contribuinte";
  }
  
  $result = db_query($sql);
  if (pg_numrows($result) == 0) {
   
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
  
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliASNbPages();
  $pdf->SetTextColor(0, 0, 0);
  $pdf->SetFillColor(220);
  $pdf->SetFont('Arial', 'B', 7);
  $pag = 1;
  
  $totalparcvenc  = "0";
  $totalparcaber  = 0;
  $totalparcpag   = 0;
  $totalvenc      = "0";
  $totalaber      = 0;
  $totalpag       = 0;
  $totalparc      = 0;
  $totalval       = 0;
  $totaldiv       = 0;
  $totalreg       = 0;
  $totvalor       = 0;
  $parc_venc      = 0;
  $parc_dia       = 0;
  $valor_dia      = 0;
  $b              = 0;
  $numpre         = 0;
  $par_pag        = "0";
  $par_aber       = "0";
  $par_venc       = "0";
  $val_pag        = "0";
  $val_venc       = "0";
  $val_aber       = "0";
  $total          = 0;
  $vencido        = false;
  $pre            = 0;
  $k00_dtvenc_one = "";
  
  $aDados = array();
  
  for ($x = 0; $x < pg_numrows($result); $x ++) {
    
    db_fieldsmemory($result, $x);
    
    $vlrhis      = (float) substr($fc_calcula,  1, 13);
    $vlrcor      = (float) substr($fc_calcula, 14, 13);
    $vlrjuros    = (float) substr($fc_calcula, 27, 13);
    $vlrmulta    = (float) substr($fc_calcula, 40, 13);
    $vlrdesconto = (float) substr($fc_calcula, 53, 13);
    
    $total = $vlrcor + $vlrjuros + $vlrmulta - $vlrdesconto;
        
    $numpre = $v07_numpre;
    
    if ($x == 0) {
      $numpre_ant = $numpre;
    }
    
    if (($numpre == $numpre_ant && pg_numrows($result) > 1) and !( $x == pg_numrows($result) - 1) ) {
      
      if ($k00_dtvenc < date('Y-m-d', db_getsession("DB_datausu"))) {
        
        $par_venc += 1;
        $val_venc += $total;
        $vencido   = true;
        
        if ($k00_dtvenc_one == "") {
         
          $k00_dtvenc_one = $menor_data_venc;
        }
      
      } else {
        
        $valor_dia += $total;
        $par_aber++;
        $val_aber  += $total;
      }
      
      $v07_parcel_ant = $v07_parcel;
      $k00_tipo_ant   = $k00_tipo;
      $k00_descr_ant  = $k00_descr;
      $z01_nome_ant   = $z01_nome;
      $z01_telef_ant  = $z01_telef;
      $v07_numcgm_ant = $v07_numcgm;
      $v07_dtlanc_ant = $v07_dtlanc;
      $k00_numtot_ant = $k00_numtot;
      $v07_valor_ant  = $v07_valor;
      
      continue;
    } else {
      
      if (pg_numrows($result) == 1) {
        
        $v07_parcel_ant = $v07_parcel;
        $k00_tipo_ant   = $k00_tipo;
        $k00_descr_ant  = $k00_descr;
        $z01_nome_ant   = $z01_nome;
        $z01_telef_ant  = $z01_telef;
        $v07_numcgm_ant = $v07_numcgm;
        $v07_dtlanc_ant = $v07_dtlanc;
        $k00_numtot_ant = $k00_numtot;
        $v07_valor_ant  = $v07_valor;
      }  
      
      $result_pag = db_query("select distinct(arrecant.k00_numpar) as par_pag 
                               from arrecant
                                    inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre 
                                                       and arrepaga.k00_numpar = arrecant.k00_numpar
                              where arrecant.k00_numpre = {$numpre_ant}");
      
      if (pg_numrows($result_pag) > 0) {
        
        $par_pag = pg_numrows($result_pag);
      }
      
      $result_pag = db_query("select sum(arrepaga.k00_valor) as val_pag 
                                from arrecant
                                     inner join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre 
                                                        and arrepaga.k00_numpar = arrecant.k00_numpar 
                                                        and arrepaga.k00_receit = arrecant.k00_receit
                               where arrecant.k00_numpre = {$numpre_ant}");
      
      if (pg_numrows($result_pag) > 0) {
        
        db_fieldsmemory($result_pag, 0);
      }
      
      $result_processoforo = db_query("select array_to_string(array_accum(distinct v70_codforo),', ') as processoforo
                                         from divida.termo
                                              inner join divida.termoini              on v07_parcel       = parcel
                                              inner join juridico.processoforoinicial on inicial          = v71_inicial
                                              inner join juridico.processoforo        on v71_processoforo = v70_sequencial
                                        where termo.v07_numpre = {$numpre_ant}");
      
      if (pg_numrows($result_processoforo) > 0) {
        
        db_fieldsmemory($result_processoforo, 0);
      } else {
       
        $processoforo = "";
      }
  
      if ($pre == 0) {
        
        $pre = 1;
      } else {
        
        if ($pre == 1) {
          
          $pre = 0;
        }
      }
      
      if (strpos($grafico, "R") > 0) {
        
        /**
         * Busca Nome do Contribuinte
         */
        
        $result_contrib = db_query("select k00_matric,k00_inscr,k00_numcgm as cgm_contrib,z01_nome as contrib 
                                      from arrenumcgm 
                                           left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre 
                                           left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre 
                                           left join cgm        on k00_numcgm            = z01_numcgm
                                     where arrenumcgm.k00_numpre = {$numpre_ant}");
        
        if (pg_numrows($result_contrib) > 0) {
          
          db_fieldsmemory($result_contrib, 0);
          
          if ($k00_matric != "") {
            
            $result_propri = db_query("select z01_cgmpri as cgm_contrib, z01_nome as contrib
                                         from proprietario
                                        where j01_matric = {$k00_matric}");
            
            db_fieldsmemory($result_propri, 0);
          } else {
            
            if ($k00_inscr != "") {
              
              $result_empre = db_query("select q02_numcgm as cgm_contrib,z01_nome as contrib
                                          from empresa
                                         where q02_inscr= {$k00_inscr}");
              
              db_fieldsmemory($result_empre, 0);
            }
          }
        }
        
        if ($ordem == "NP") {
          
          $chave = $v07_parcel_ant;
        } elseif ($ordem == "DTPV") {
          
          $chave = $k00_dtvenc_one.$v07_parcel_ant;
        } elseif ($ordem == "NOME") {
          
          $chave = $contrib."-".$numpre_ant;
          
        }
        
        $origem = "";
        if ($k00_matric != "") {
          $origem = "M-" . $k00_matric;
        }
        if ($k00_inscr != "") {
          if ( $origem != "" ) {
            $origem .= " - ";
          }
          $origem .= "I-" . $k00_inscr;
        }
        if ( $origem == "" ) {
          $origem = "CGM: " . $cgm_contrib;
        }

        $aDados[$chave] = array($v07_parcel_ant, $v07_dtlanc_ant, $cgm_contrib , $contrib      , $v07_numcgm_ant,
                                $z01_nome_ant  , $z01_telef_ant , $k00_tipo_ant, $k00_descr_ant, $k00_dtvenc_one,
                                $k00_numtot_ant, $par_pag       , $par_aber    , $par_venc     , $v07_valor_ant ,
                                $val_pag       , $val_aber      , $val_venc    , $processoforo , $origem);
      }
      
      $totalparcvenc += $par_venc;
      $totalparcpag  += $par_pag;
      $totalparcaber += $par_aber;
      $totalvenc     += $val_venc;
      $totalpag      += $val_pag;
      $totalaber     += $val_aber;
      $totalparc     += $k00_numtot_ant;
      $totalval      += $v07_valor_ant;
      $totalreg      += 1;
      
      if ($vencido == true) {
        
        $parc_venc ++;
      } else {
        
        $parc_dia ++;
      }
      
      $vencido = false;
      
      $par_pag        = "0";
      $par_aber       = "0";
      $par_venc       = "0";
      $val_pag        = "0";
      $val_venc       = "0";
      $val_aber       = "0";
      $k00_dtvenc_one = "";
      
      if ($menor_data_venc < date('Y-m-d', db_getsession("DB_datausu"))) {
        
        $par_venc += 1;
        $val_venc += $total;
        
        if ($k00_dtvenc_one == ""){
          $k00_dtvenc_one = $menor_data_venc;
        }
        
      } else {
        
        $valor_dia += $total;
        $par_aber++;
        $val_aber  += $total;
      }
      
      $numpre_ant     = $numpre;
      $v07_parcel_ant = $v07_parcel;
      $k00_tipo_ant   = $k00_tipo;
      $k00_descr_ant  = $k00_descr;
      $z01_nome_ant   = $z01_nome;
      $z01_telef_ant  = $z01_telef;
      $v07_numcgm_ant = $v07_numcgm;
      $v07_dtlanc_ant = $v07_dtlanc;
      $k00_numtot_ant = $k00_numtot;
      $v07_valor_ant  = $v07_valor;
    }
  }

  foreach ($aDados as $aDados2) {
    
    if (strpos($grafico, "R") > 0) {
      
      if (($pdf->gety() > $pdf->h - 30) || $pag == 1) {
        
        $pdf->addpage("L");
        
        $pdf->SetFont('Arial', 'B', 7);
        
        $pdf->Cell(18, 5, "PARCEL"                 , 1, 0, "C", 1);
        $pdf->Cell(18, 5, "DATA LANC"              , 1, 0, "C", 1);
        $pdf->Cell(70, 5, "CONTRIBUINTE"           , 1, 0, "C", 1);
        $pdf->Cell(65, 5, 'RESPONSAVEL'            , 1, 0, "C", 1);
        $pdf->Cell(22, 5, 'FONE RESP'              , 1, 0, "C", 1);
        $pdf->Cell(30, 5, 'ORIGEM'                , 1, 0, "C", 1);
        $pdf->Cell(50, 5, strtoupper($RLk00_descr) , 1, 1, "C", 1);
        $pdf->Cell(50, 5, 'PROCESSO FORO'          , 1, 0, "C", 1);
        $pdf->Cell(23, 5, 'DT 1º PARC VENC'        , 1, 0, "C", 1);
        $pdf->Cell(20, 5, 'TOTAL PARC.'            , 1, 0, "C", 1);
        $pdf->Cell(20, 5, 'PARC. PAGAS'            , 1, 0, "C", 1);
        $pdf->Cell(20, 5, 'PARC. Ñ VENC'           , 1, 0, "C", 1);
        $pdf->Cell(20, 5, 'PARC. VENC'             , 1, 0, "C", 1);
        $pdf->Cell(30, 5, 'VLR PARCELAMENTO'       , 1, 0, "C", 1);
        $pdf->Cell(30, 5, 'VLR PAGO'               , 1, 0, "C", 1);
        $pdf->Cell(30, 5, 'VLR ABERTO'             , 1, 0, "C", 1);
        $pdf->Cell(30, 5, 'VLR VENCIDO'            , 1, 1, "C", 1);
        
        $pag = 0;
        $pre = 1;
      }
    }
    
    if ($pre == 0) {
      
      $pre = 1;
    } else {
      
      if ($pre == 1) {
        
        $pre = 0;
      }
    }
    
    if (strpos($grafico, "R") > 0) {
      
      /**
       * Busca Nome do Contribuinte
       */
      
      $pdf->SetFont('Arial', '', 7);
      
      $pdf->Cell(18, 5, $aDados2[0]                       , $b, 0, "C", $pre);
      $pdf->Cell(18, 5, db_formatar($aDados2[1], 'd')     , $b, 0, "C", $pre);
      $pdf->Cell(70, 5, $aDados2[2] . "-" . @$aDados2[3]  , $b, 0, "L", $pre);
      $pdf->Cell(65, 5, $aDados2[4] . "-" . @$aDados2[5]  , $b, 0, "L", $pre);
      $pdf->Cell(22, 5, $aDados2[6]                       , $b, 0, "L", $pre);
      $pdf->Cell(30, 5, $aDados2[19]                      , $b, 0, "L", $pre);
      $pdf->Cell(50, 5, @$aDados2[7] . '-' . @ $aDados2[8], $b, 1, "L", $pre);
      $pdf->Cell(50, 5, substr($aDados2[18], 1, 40)       , $b, 0, "L", $pre);
      $pdf->Cell(23, 5, db_formatar($aDados2[9], 'd')     ,  0, 0, "C", $pre);
      $pdf->Cell(20, 5, @$aDados2[10]                     , $b, 0, "C", $pre);
      $pdf->Cell(20, 5, @$aDados2[11]                     , $b, 0, "C", $pre);
      $pdf->Cell(20, 5, $aDados2[12]                      , $b, 0, "C", $pre);
      $pdf->Cell(20, 5, @$aDados2[13]                     , $b, 0, "C", $pre);
      $pdf->Cell(30, 5, db_formatar(@$aDados2[14], 'f')   , $b, 0, "R", $pre);
      $pdf->Cell(30, 5, db_formatar(@$aDados2[15], 'f')   , $b, 0, "R", $pre);
      $pdf->Cell(30, 5, db_formatar(@$aDados2[16], 'f')   , $b, 0, "R", $pre);
      $pdf->Cell(30, 5, db_formatar(@$aDados2[17], 'f')   , $b, 1, "R", $pre);     
    }
  }
  
  if ($totalreg == 0) {
    
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
  }
  
  if (strpos($grafico, "R") > 0) {
    
    $pdf->SetFont('Arial', 'B', 7);
    
    $pdf->addpage("L");
    
    $pdf->Cell(270, 7, 'TOTALIZADOR', 'T', 1, "C", 1);
    $pdf->Cell(90 , 7, 'QUANTIDADES',   1, 0, "C", 0);
    $pdf->Cell(90 , 7, 'PAGAMENTO'  ,   1, 0, "C", 0);
    $pdf->Cell(90 , 7, 'ABERTO'     ,   1, 1, "C", 0);
    
    $pdf->Cell(60, 7, 'TOTAL DE PARCELAMENTOS : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalreg                  , 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'PARCELAS PAGAS : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalparcpag      , 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'PARCELAS VENCIDAS : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalparcvenc        , 1, 1, "R", 0);
    
    $pdf->Cell(60, 7, 'TOTAL DE PARCELAS : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalparc            , 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'TOTAL PAGO : '           , 1, 0, "R", 0);
    $pdf->Cell(30, 7, db_formatar($totalpag,'f'), 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'PARCELAS NÃO VENCIDAS : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalparcaber            , 1, 1, "R", 0);
      
    $pdf->Cell(60, 7, 'VLR TOTAL DOS PARCELAMENTOS (ORIGEM) :', 1, 0, "R", 0);
    $pdf->Cell(30, 7, db_formatar($totalval,'f')              , 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, '', 1, 0, "R", 0);
    $pdf->Cell(30, 7, '', 1, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'TOTAL DAS PARCELAS EM ABERTO : ', 1, 0, "R", 0);
    $pdf->Cell(30, 7, $totalparcaber+$totalparcvenc    , 1, 1, "R", 0);
    
    $pdf->Cell(60, 7, '', 0, 0, "R", 0);
    $pdf->Cell(30, 7, '', 0, 0, "R", 0);
    $pdf->Cell(60, 7, '', 0, 0, "R", 0);
    $pdf->Cell(30, 7, '', 0, 0, "R", 0);
    
    $pdf->Cell(60, 7, 'TOTAL EM ABERTO: '                      , 1, 0, "R", 0);
    $pdf->Cell(30, 7, db_formatar($totalaber + $totalvenc, 'f'), 1, 1, "R", 0);
    
    $pdf->Ln(5);
  }
  
  if (strpos($grafico, "G") > 0) {
    
    $data  = array ();
    $data1 = array ();
    $data2 = array ();
    $data3 = array ();
    $data4 = array ();
    $col   = array ();
    $cor   = 240;
    
    if ($parc_dia != 0 || $parc_venc != 0) {
      
      $cor -= 20;
      
      if ($cor < 80) {
        
        $cor = 248;
      }
      
      $col[0] = array ($cor, $cor, $cor);
      $cor   -= 20;
      
      if ($cor < 80) {
        $cor = 248;
      }
      
      $col[1] = array ($cor, $cor, $cor);
      
      $pdf->addpage("L");
      
      $pdf->SetFont('Arial', 'BI', 15);
      
      $pdf->Cell(0, 15, 'Estatisticas das Parcelas(Quantidade)', 0, 1, "C", 0);
      
      $pdf->SetFont('Arial', 'BIU', 10);
      
      $data4["Pagas"]     = $totalparcpag;
      $data4["Em aberto"] = $totalparcaber + $totalparcvenc;
      
      $pdf->SetFont('Arial', '', 6);
      
      $valX = $pdf->GetX();
      $valY = $pdf->GetY();
      
      $pdf->SetXY(10, $valY + 10);
      
      $pdf->BarDiagram(200, 50, $data4, '%l - %v - (%p)');
      
      $pdf->SetXY($valX, $valY + 40);
      
      $pdf->ln(20);
      
      $pdf->SetFont('Arial', 'BI', 15);
      
      $pdf->Cell(0, 15, 'Estatisticas das Parcelas(Valor)', 0, 1, "C", 0);
      
      $pdf->SetFont('Arial', 'BIU', 10);
      
      $data4["Pagas"]     = $totalpag;
      $data4["Em aberto"] = $totalaber + $totalvenc;
      
      $pdf->SetFont('Arial', '', 6);
      
      $valX = $pdf->GetX();
      $valY = $pdf->GetY();
      
      $pdf->SetXY(10, $valY + 10);
      
      $pdf->BarDiagram(200, 50, $data4, '%l - %v - (%p)');
      
      $pdf->SetXY($valX, $valY + 40);
    }
  }
  
  $pdf->Output();
?>