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

require_once ("fpdf151/pdf.php");
require_once ("libs/db_sql.php");
require_once ("libs/db_utils.php");
require_once ("classes/db_pcorcam_classe.php");
require_once ("classes/db_pcorcamforne_classe.php");
require_once ("classes/db_pcorcamitem_classe.php");
require_once ("classes/db_pcorcamval_classe.php");
require_once ("classes/db_liclicita_classe.php");
require_once ("classes/db_pcorcamdescla_classe.php");
require_once ("classes/db_pcorcamtroca_classe.php");
require_once ("classes/db_liclicitemanu_classe.php");


require_once ("integracao_externa/ged/GerenciadorEletronicoDocumento.model.php");
require_once ("integracao_externa/ged/GerenciadorEletronicoDocumentoConfiguracao.model.php");
require_once ("libs/exceptions/BusinessException.php");

$oGet = db_utils::postMemory($_GET);
$oConfiguracaoGed = GerenciadorEletronicoDocumentoConfiguracao::getInstance();
if ($oConfiguracaoGed->utilizaGED()) {

  if (empty($oGet->pc20_codorc)) {

    $sMsgErro  = "O parâmetro para utilização do GED (Gerenciador Eletrônico de Documentos) está ativado.<br><br>";
    $sMsgErro .= "Neste não é possível informar interválos de códigos ou datas.<br><br>";
    db_redireciona("db_erros.php?fechar=true&db_erro={$sMsgErro}");
    exit;
  }
}


$imp_vlrun = "S";
$imp_vlrtotal = "S";

$clpcorcam = new cl_pcorcam();
$clpcorcamforne = new cl_pcorcamforne();
$clpcorcamitem = new cl_pcorcamitem();
$clpcorcamval = new cl_pcorcamval();
$clliclicita = new cl_liclicita();
$clpcorcamdescla = new cl_pcorcamdescla();
$clpcorcamtroca = new cl_pcorcamtroca();
$clliclicitemanu = new cl_liclicitemanu();

$clrotulo = new rotulocampo();
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);
//echo $HTTP_SERVER_VARS['QUERY_STRING'];

$sOrigem = "";

if (isset($tipoOrcamento) && !empty($tipoOrcamento)) {
  $sOrigem = $oGet->tipoOrcamento;
}

$sInner = "";

if ($sOrigem == "solicitacao") {
  $sSqlMater = $clpcorcamitem->sql_query_pcmatersol(null, "pc11_codigo, pc11_numero, pc11_seq, pc22_codorc, 1 as l20_tipojulg", null, "pc20_codorc=$pc20_codorc"); 
  $sInner = "inner join pcorcamitemsol          on pcorcamitemsol.pc29_orcamitem  = pcorcamtroca.pc25_orcamitem
             inner join solicitem               on solicitem.pc11_codigo          = pcorcamitemsol.pc29_solicitem";

} else if ($sOrigem == "processo") {
  $sSqlMater = $clpcorcamitem->sql_query_pcmaterproc(null, "pc81_codproc, pc11_seq,pc22_codorc, 1 as l20_tipojulg", null, "pc20_codorc=$pc20_codorc");
  $sInner = "inner join pcorcamitemproc          on pcorcamitemproc.pc31_orcamitem  = pcorcamtroca.pc25_orcamitem
	           inner join pcprocitem               on pcprocitem.pc81_codprocitem     = pcorcamitemproc.pc31_pcprocitem
	           inner join solicitem                on solicitem.pc11_codigo           = pcprocitem.pc81_solicitem";

}

$result_info = $clpcorcamitem->sql_record($sSqlMater);
if ($clpcorcamitem->numrows > 0) {
  db_fieldsmemory($result_info, 0);
}

if (isset($imp_troca) && $imp_troca == "S") {
  
  if ($sOrigem == "solicitacao") {
    $sWhere = "  pc11_numero =  $pc11_numero  ";
  }
  
  if ($sOrigem == "processo") {
    $sWhere = "  pc81_codproc =  $pc81_codproc  ";
  }
  
  $sql_troca = "select pc01_codmater, pc01_descrmater, pc25_motivo, nome_julgado, nome_trocado
                from (select distinct on(pc25_orcamitem) pc25_orcamitem, pc25_codtroca, pc01_codmater, pc01_descrmater,
                                                         pc25_motivo, cgm.z01_nome as nome_julgado,
                                                         cgm_ant.z01_nome as nome_trocado
                      from pcorcamtroca
                           inner join pcorcamjulg              on pcorcamjulg.pc24_orcamitem      = pcorcamtroca.pc25_orcamitem
                           inner join pcorcamforne             on pcorcamforne.pc21_orcamforne    = pcorcamjulg.pc24_orcamforne
                           inner join cgm                      on cgm.z01_numcgm                  = pcorcamforne.pc21_numcgm
                           $sInner
                           inner join solicitempcmater         on solicitempcmater.pc16_solicitem = solicitem.pc11_codigo
                           inner join pcmater                  on pcmater.pc01_codmater           = solicitempcmater.pc16_codmater
                           left  join pcorcamforne as forneant on forneant.pc21_orcamforne        = pcorcamtroca.pc25_forneant
                           left  join cgm as cgm_ant           on cgm_ant.z01_numcgm              = forneant.pc21_numcgm
                           left  join pcorcamval               on pcorcamval.pc23_orcamitem       = pcorcamjulg.pc24_orcamitem
                           left  join pcorcamdescla            on pcorcamdescla.pc32_orcamitem    = pcorcamval.pc23_orcamitem and
                                                                  pcorcamdescla.pc32_orcamforne   = pcorcamval.pc23_orcamforne
                      where $sWhere and
                            pc24_pontuacao   = 1              and
                            pc32_orcamitem  is null           and
                            pc32_orcamforne is null
                      order by pc25_orcamitem desc, pc25_codtroca desc) as x
                order by pc01_descrmater";

  //echo $sql_troca; exit;
  $res_troca = $clpcorcamtroca->sql_record($sql_troca);
}

$rsOrcamento = db_query($sSqlMater);
db_fieldsmemory($rsOrcamento, 0);

$head3 = "Orçamento: " . @$pc22_codorc;

if ($sOrigem == "solicitacao") {
  $head5 = "Solicitação: $pc11_numero";
} else if ($sOrigem == "processo") {
  $head5 = "Processo de Compra: $pc81_codproc";
}

$orcamento = @$pc22_codorc;

if ($modelo == 1) {

  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 1  -----------------------------------------------------------------------------------------------------------------//


  $sSqlFornecedores = $clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento");
  $result_forne = $clpcorcamforne->sql_record($sSqlFornecedores);
  $numrows_forne = $clpcorcamforne->numrows;
  if ($numrows_forne == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Fornecedores cadastrados.');
  }
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);
  $troca = 1;
  $alt = 6;
  $total = 0;
  $p = 0;
  $max_itens = 0;
  $max = false;
  $quant_imp = 0;
  for($x = 0; $x < $numrows_forne; $x ++) {
    db_fieldsmemory($result_forne, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {

      if ($pdf->gety() > $pdf->h - 30 || $max == false) {
        $pdf->addpage('L');
        if ($max) {

          $max_itens -= 11;
        }
      }

      $p = 0;
      $pdf->setfont('arial', 'b', 9);
      $pdf->cell(60, $alt, "Fornecedor", 1, 0, "C", 1);

      if ($sOrigem == "solicitacao") {
        $sSqlMater = $clpcorcamitem->sql_query_pcmatersol(null, "distinct pc11_seq,
                                                                          pc22_orcamitem, pc01_descrmater",
                                                                          "pc11_seq", "pc22_codorc=$orcamento");
      } else if ($sOrigem == "processo") {
        $sSqlMater = $clpcorcamitem->sql_query_pcmaterproc(null, "distinct pc11_seq, pc22_orcamitem,
                                                                  pc01_descrmater",
                                                                 "pc11_seq", "pc22_codorc=$orcamento");
      }

      $result_itens = $clpcorcamitem->sql_record($sSqlMater);

      $numrows_itens = $clpcorcamitem->numrows;
      if ($numrows_itens > $max_itens + 11) {

        $max_itens = $max_itens + 11;
        $max = true;

      } else {

        $max = false;
        $max_itens = $numrows_itens;

      }

      $t = 0;
      for($w = $quant_imp; $w < $max_itens; $w ++) {

        db_fieldsmemory($result_itens, $w);
        if ($w == ($max_itens - 1)) {
          $t = 1;
        }
        $pdf->cell(20, $alt, $pc11_seq, 1, $t, "C", 1);
      }
      $troca = 0;
    }
    $alt = 4;
    $pdf->setfont('arial', '', 7);
    $pdf->cell(60, $alt, $z01_numcgm ." - ". substr($z01_nome, 0, 40), 0, 0, "L", $p);
    $t = 0;
    $cont_quant = 0;
    for($w = $quant_imp; $w < $max_itens; $w ++) {
      db_fieldsmemory($result_itens, $w);
      $pdf->setfont('arial', '', 7);
      if ($w == ($max_itens - 1)) {
        $t = 1;
      }

      $sSqlJulg = $clpcorcamval->sql_query_julg(null, null, "pc23_valor,pc24_pontuacao", null,
                                                "pc23_orcamforne=$pc21_orcamforne and pc23_orcamitem=$pc22_orcamitem");
      $result_valor = $clpcorcamval->sql_record($sSqlJulg);

      if ($clpcorcamval->numrows > 0) {
        db_fieldsmemory($result_valor, 0);
        if ($pc24_pontuacao == 1) {
          $pdf->setfont('arial', 'b', 8);
        }
        $pdf->cell(20, $alt, db_formatar(@$pc23_valor, 'f'), 0, $t, "R", $p);
      } else {
        $pdf->cell(20, $alt, "0,00", 0, $t, "R", $p);
      }
      $cont_quant ++;
    }
    if ($x == $numrows_forne - 1 && $max == true) {
      $quant_imp = $cont_quant + $quant_imp;
    }
    if ($x == $numrows_forne - 1 && $max == true) {

      $x = - 1;
      $troca = 1;
      $total = 0;
      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(280, $alt, ' ', "T", 1, "L", 0);
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
    $total ++;
  }
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(60 + 20 * $cont_quant, $alt, 'TOTAL DE FORNECEDORES  :  ' . $numrows_forne, "T", 1, "L", 0);
  $pdf->ln();

  if ($sOrigem == "solicitacao") {
    $sSqlMater = $clpcorcamitem->sql_query_pcmatersol(null,
                                                        "distinct pc11_seq,pc22_orcamitem,pc01_descrmater,pc11_resum",
                                                        "pc11_seq", "pc22_codorc=$orcamento");
  } else if ($sOrigem == "processo") {
    $sSqlMater = $clpcorcamitem->sql_query_pcmaterproc(null,
                                                        "distinct pc11_seq,pc22_orcamitem,pc01_descrmater,pc11_resum",
                                                        "pc11_seq", "pc22_codorc=$orcamento");
  }

  $result_itens = $clpcorcamitem->sql_record($sSqlMater);
  $numrows_itens = $clpcorcamitem->numrows;
  $p = 0;
  $troca = 1;
  $alt = 4;
  $valor_tot = 0;
  for($x = 0; $x < $numrows_itens; $x ++) {
    db_fieldsmemory($result_itens, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage('L');
      }
      $pdf->setfont('arial', 'b', 8);
      $pdf->cell(20, $alt, "ITEM", 1, 0, "C", 1);
      $pdf->cell(100, $alt, "MATERIAL", 1, 0, "C", 1);
      $pdf->cell(25, $alt, "VALOR MÉDIO", 1, 0, "C", 1);
      $pdf->cell(25, $alt, "VALOR", 1, 1, "C", 1);
      $p = 0;
      $troca = 0;
    }
    $pdf->setfont('arial', '', 7);
    $pdf->cell(20, $alt, $pc11_seq, 0, 0, "C", $p);
    $pdf->cell(100, $alt, substr($pc01_descrmater . " - " . $pc11_resum, 0, 65), 0, 0, "L", $p);

    $sSqlJulg = $clpcorcamval->sql_query_julg(null,
                                              null,
                                              "pc23_valor,
                                              (select coalesce(sum(val.pc23_valor),0)/case when count(val.*) > 0 then count(val.*) else 1 end
                                                                             from pcorcamval val where val.pc23_orcamitem = {$pc22_orcamitem}) as valor_medio,

                                              pc24_pontuacao", null,
                                              "pc23_orcamitem=$pc22_orcamitem and pc24_pontuacao=1");
    $result_valor = $clpcorcamval->sql_record($sSqlJulg);

    if ($clpcorcamval->numrows > 0) {

      db_fieldsmemory($result_valor, 0);
      if ($pc24_pontuacao == 1) {
        $valor_tot += $pc23_valor;
      }

      $pdf->cell(25, $alt, db_formatar($valor_medio, 'f'), 0, 0, "R", $p);
      $pdf->cell(25, $alt, db_formatar($pc23_valor, 'f'), 0, 1, "R", $p);

    } else {

      $pdf->cell(25, $alt, db_formatar(0, 'f'), 0, 0, "R", $p);
      $pdf->cell(25, $alt, db_formatar(0, 'f'), 0, 1, "R", $p);
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
  }
  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(145, $alt, 'TOTAL :', "T", 0, "R", 0);
  $pdf->cell(25, $alt, db_formatar(@$valor_tot, 'f'), "T", 1, "R", 0);

} else if ($modelo == 2) {

  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//
  //-----------------------------  MODELO 2  -----------------------------------------------------------------------------------------------------------------//


  if ($sOrigem == "solicitacao") {
    $sSqlItens = $clpcorcamitem->sql_query_pcmatersol(null, "distinct pc22_orcamitem,
		                                                         pc01_descrmater,
		                                                         pc11_resum,
		                                                         pc11_quant,
		                                                         m61_descr,
		                                                         (select coalesce(sum(val.pc23_valor),0)/case when count(val.*) > 0 then count(val.*) else 1 end
                                                                from pcorcamval val
                                                               where val.pc23_orcamitem = pc22_orcamitem) as valor_medio,
		                                                         pc11_seq", "pc11_seq", "pc22_codorc=$orcamento");
  } else if ($sOrigem == "processo") {
    $sSqlItens = $clpcorcamitem->sql_query_pcmaterproc(null, "distinct pc22_orcamitem,
                                                             pc01_descrmater,
                                                             pc11_resum,
                                                             pc11_quant,
                                                             m61_descr,
                                                             (select coalesce(sum(val.pc23_valor),0)/case when count(val.*) > 0 then count(val.*) else 1 end
                                                                from pcorcamval val
                                                               where val.pc23_orcamitem = pc22_orcamitem) as valor_medio,
                                                             pc11_seq", "pc11_seq", "pc22_codorc=$orcamento");
  }

  $result_itens = $clpcorcamitem->sql_record($sSqlItens);

  $numrows_itens = $clpcorcamitem->numrows;
  if ($numrows_itens == 0) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Não existem itens cadastrados.');
  }
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setdrawcolor(0);
  $pdf->setfont('arial', 'b', 9);
  $troca = 1;
  $alt = 6;
  $total = 0;
  $p = 0;
  $max_forne = 0;
  $max = false;
  $quant_imp = 0;
  $valor_total = 0;

  $arr_subtotganhoun = array ();
  $arr_subtotcotadoun = array ();

  $arr_subtotganhovlr = array ();
  $arr_subtotcotadovlr = array ();

  $arr_totalganho = array ();
  $arr_totalcotado = array ();

  $result_forne = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento"));
  $numrows_forne = $clpcorcamforne->numrows;

  for($i = 0; $i < $numrows_forne; $i ++) {
    db_fieldsmemory($result_forne, $i);

    $arr_subtotganhoun [$i] = 0;
    $arr_subtotcotadoun [$i] = 0;

    $arr_subtotganhovlr [$i] = 0;
    $arr_subtotcotadovlr [$i] = 0;

    $arr_totalganho [$pc21_orcamforne] = 0;
    $arr_totalcotado [$pc21_orcamforne] = 0;
  }

  $total_quant = 0;
  for($i = 0; $i < $numrows_itens; $i ++) {
    db_fieldsmemory($result_itens, $i);

    $total_quant += $pc11_quant;
  }

  for($x = 0; $x < $numrows_itens; $x ++) {
    db_fieldsmemory($result_itens, $x);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0) {
      if ($pdf->gety() > $pdf->h - 30 || $max == false) {
        $pdf->addpage('L');
      }
      $p = 0;

      $alt = 6;
      $pdf->setfont('arial', 'b', 9);
      $pdf->cell(15, $alt, "Item", 1, 0, "C", 1);
      $pdf->cell(60, $alt, "Descr. Produto", 1, 0, "C", 1);
      $pdf->cell(20, $alt, "Unidade", 1, 0, "C", 1);
      $pdf->cell(15, $alt, "Vlr. Med.", 1, 0, "C", 1);
      $pdf->cell(15, $alt, "Quant.", 1, 0, "C", 1);

      $sSqlFornecedores = $clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento");
      $result_forne = $clpcorcamforne->sql_record($sSqlFornecedores);

      $numrows_forne = $clpcorcamforne->numrows;
      if ($troca != 0) {
        if ($numrows_forne > $max_forne + 2) {
          $max_forne = $max_forne + 2;
          $max = true;
        } else {
          $max = false;
          $max_forne = $numrows_forne;
        }
      }
      $t = 0;
      for($w = $quant_imp; $w < $max_forne; $w ++) {
        db_fieldsmemory($result_forne, $w);
        if ($pdf->gety() > $pdf->h - 30) {
          $t = 1;
        }

        if ($w == ($max_forne - 1)) {
          $t = 1;
        }

        if ($imp_vlrun == "S") {
          $pdf->cell(20, $alt, "Vlr. Un.", 1, 0, "C", 1);
        }

        $pdf->cell(60, $alt, substr($z01_nome, 0, 25) . "(" . ($w + 1) . ")", 1, $t, "C", 1);
      }
      $troca = 0;
    }
    $alt = 4;
    $pdf->setfont('arial', '', 7);
    $pdf->cell(15, $alt, $pc11_seq, 1, 0, "C", 0);
    $pdf->cell(60, $alt, substr($pc01_descrmater . " - " . $pc11_resum, 0, 38), 1, 0, "L", 0);
    $pdf->cell(20, $alt, $m61_descr, 1, 0, "C", 0);
    $pdf->cell(15, $alt, db_formatar($valor_medio, 'f'), 1, 0, "C", 0);
    $pdf->cell(15, $alt, $pc11_quant, 1, 0, "C", 0);

    $t = 0;
    $cont_quant = 0;
    for($w = $quant_imp; $w < $max_forne; $w ++) {
      db_fieldsmemory($result_forne, $w);
      $pdf->setfont('arial', '', 7);
      if ($w == ($max_forne - 1)) {
        $t = 1;
      }

      $sSqlJulg = $clpcorcamval->sql_query_julg(null, null,
                                                "pc23_valor,pc23_vlrun,pc24_pontuacao", null,
                                                "pc23_orcamforne=$pc21_orcamforne and pc23_orcamitem=$pc22_orcamitem");

      $result_valor = $clpcorcamval->sql_record($sSqlJulg);
      if ($clpcorcamval->numrows > 0) {
        db_fieldsmemory($result_valor, 0);
        if ($pc24_pontuacao == 1) {
          $pdf->setfont('arial', 'b', 8);
          $fundo = 1;
          $arr_subtotganhoun [$w] += $pc23_vlrun;
          $arr_subtotganhovlr [$w] += $pc23_valor;
          $arr_totalganho [$pc21_orcamforne] += $pc23_valor;
        } else {
          $fundo = 0;
        }

        $arr_subtotcotadoun [$w] += $pc23_vlrun;
        $arr_subtotcotadovlr [$w] += $pc23_valor;
        $arr_totalcotado [$pc21_orcamforne] += $pc23_valor;

        if ($imp_vlrun == "S") {
          $pdf->cell(20, $alt, db_formatar(@$pc23_vlrun, "f"), 1, 0, "R", $fundo);
        }

        $pdf->cell(60, $alt, db_formatar(@$pc23_valor, 'f'), 1, $t, "R", $fundo);

        if ($imp_vlrtotal == "S") {
          if (isset($arr_valor [$w]) && trim(@$arr_valor [$w]) != "") {
            $arr_valor [$w] += @$pc23_valor;
          }

          $valor_total += $pc23_valor;
        }
      } else {
        if ($imp_vlrun == "S") {
          $pdf->cell(20, $alt, "0,00", 1, 0, "R", 0);
        }
        $pdf->cell(60, $alt, "0,00", 1, $t, "R", 0);
      }

      $cont_quant ++;
    }

    if ($x == $numrows_itens - 1 && $max == true) {
      $quant_imp = $cont_quant + $quant_imp;
      $x = - 1;
      $troca = 1;
      $total = 0;

      $pdf->setfont('arial', 'b', 8);

      $pdf->cell(125, $alt, db_formatar($total_quant, "f"), 1, 0, "R", 0);
      $pdf->cell(160, $alt, "", 1, 1, "R", 0);

      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      // Impressao de 2 em 2 fornecedores, separados por subtotal ganho e cotado, incluindo valores unitarios e total
      if ($w == 2) { // posicao 0 e 1 dos arrays de subtotais
        $ind = 0;
      } else {
        $ind = $w - 2; // posicoes 2 em diante dos arrays de subtotais, sempre de 2 em 2
      }

      for($xx = $ind; $xx < $w; $xx ++) {
        if (($xx % 2) == 0) {
          $tam = 145;
          $msg = "SUBTOTAL GANHO ";
        } else {
          $tam = 20;
          $msg = "";
        }

        if (($xx + 1) >= $w) {
          $br = 1;
        } else {
          $br = 0;
        }

        $pdf->cell($tam, $alt, $msg . db_formatar($arr_subtotganhoun [$xx], "f"), 1, 0, "R", 0);
        $pdf->cell(60, $alt, db_formatar($arr_subtotganhovlr [$xx], "f"), 1, $br, "R", 0);
      }

      if ($w == 2) {
        $ind = 0;
      } else {
        $ind = $w - 2;
      }

      for($xx = $ind; $xx < $w; $xx ++) {
        if (($xx % 2) == 0) {
          $tam = 145;
          $msg = "SUBTOTAL COTADO ";
        } else {
          $tam = 20;
          $msg = "";
        }

        if (($xx + 1) >= $w) {
          $br = 1;
        } else {
          $br = 0;
        }

        $pdf->cell($tam, $alt, $msg . db_formatar($arr_subtotcotadoun [$xx], "f"), 1, 0, "R", 0);
        $pdf->cell(60, $alt, db_formatar($arr_subtotcotadovlr [$xx], "f"), 1, $br, "R", 0);
      }
      /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      $pdf->ln();
    }

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }
    $total ++;
  }

  $pdf->setfont('arial', 'b', 8);

  // Ficou pendente valores a serem impressos
  if ($quant_imp < $max_forne) {
    $pdf->cell(125, $alt, "QUANT. TOTAL " . db_formatar($total_quant, "f"), 1, 0, "R", 0);
    $pdf->cell(80, $alt, "", 1, 1, "R", 0);

    $pdf->cell(145, $alt, "SUBTOTAL GANHO " . db_formatar($arr_subtotganhoun [$quant_imp], "f"), 1, 0, "R", 0);
    $pdf->cell(60, $alt, db_formatar($arr_subtotganhovlr [$quant_imp], "f"), 1, 1, "R", 0);

    $pdf->cell(145, $alt, "SUBTOTAL COTADO " . db_formatar($arr_subtotcotadoun [$quant_imp], "f"), 1, 0, "R", 0);
    $pdf->cell(60, $alt, db_formatar($arr_subtotcotadovlr [$quant_imp], "f"), 1, 1, "R", 0);

    $pdf->ln();
  }

  if ($pdf->gety() > $pdf->h - 30) {
    $pdf->addpage("L");
  }

  $pdf->cell(65, $alt, "FORNECEDOR(ES)", 1, 0, "L", 1);
  $pdf->cell(30, $alt, "VALOR GANHO", 1, 0, "R", 1);
  $pdf->cell(30, $alt, "VALOR COTADO", 1, 1, "R", 1);

  $total_ganho = 0;
  $total_cotado = 0;

  $sSqlFornecedores = $clpcorcamforne->sql_query(null, "*", null, "pc21_codorc=$orcamento");
  $result_forne = $clpcorcamforne->sql_record($sSqlFornecedores);

  $numrows_forne = $clpcorcamforne->numrows;
  for($i = 0; $i < $numrows_forne; $i ++) {
    db_fieldsmemory($result_forne, $i);
    $cont = $i;
    $cont ++;

    $pdf->cell(65, $alt, $z01_numcgm." - ".substr($z01_nome, 0, 25) . " (" . $cont . ")", 0, 0, "L", $p);
    $pdf->cell(30, $alt, db_formatar($arr_totalganho [$pc21_orcamforne], "f"), 0, 0, "R", $p);
    $pdf->cell(30, $alt, db_formatar($arr_totalcotado [$pc21_orcamforne], "f"), 0, 1, "R", $p);

    if ($p == 0) {
      $p = 1;
    } else {
      $p = 0;
    }

    $total_ganho += $arr_totalganho [$pc21_orcamforne];
    $total_cotado += $arr_totalcotado [$pc21_orcamforne];
  }
  if ($numrows_forne > 0) {
    $pdf->cell(125, 1, "", "T", 1, "R", 0);
    $pdf->cell(95, $alt, "TOTAIS " . db_formatar($total_ganho, "f"), 0, 0, "R", 0);
    $pdf->cell(30, $alt, db_formatar($total_cotado, "f"), 0, 1, "R", 0);
  }

  $pdf->ln();

  if ($imp_vlrtotal == "S") {
    if ($pdf->gety() + 17 > $pdf->h - 30) {
      $pdf->AddPage("L");
      $pdf->cell(20, $alt * 2, "", 0, 1, "L", 0);
    }

    $pdf->cell(60, $alt, "TOTAL GERAL " . db_formatar($valor_total, "f"), 0, 1, "R", 0);
  }
} else {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo não foi selecionado.');
}

$pdf->setfont('arial', '', 8);

if (isset($imp_troca) && $imp_troca == "S") {
  if ($clpcorcamtroca->numrows > 0) {
    $pdf->ln();
    $tam = 280;
    if ($l20_tipojulg != 1) {
      $tam = 270;
    }

    if ($pdf->gety() > $pdf->h - 50) {
      $pdf->addpage("L");
    }

    $pdf->cell($tam, $alt, "TROCA FORNECEDOR", 1, 1, "C", 1);

    if ($l20_tipojulg == 1) {
      $pdf->cell(10, $alt, "Item", 1, 0, "C", 1);
      $pdf->cell(40, $alt, "Descrição material", 1, 0, "C", 1);
    }

    if ($l20_tipojulg != 1) {
      $pdf->cell(40, $alt, "Descrição lote", 1, 0, "C", 1);
    }

    $pdf->cell(60, $alt, "Fornecedor substituto", 1, 0, "C", 1);
    $pdf->cell(60, $alt, "Fornecedor substituido", 1, 0, "C", 1);
    $pdf->cell(110, $alt, "Justificativa", 1, 1, "C", 1);

    $p = 0;
    $lote = "";
    for($i = 0; $i < $clpcorcamtroca->numrows; $i ++) {
      db_fieldsmemory($res_troca, $i);
      if ($pdf->gety() > $pdf->h - 30) {
        $pdf->addpage("L");
      }

      if ($lote != "") {
        if ($lote == $l04_descricao) {
          continue;
        }
      }

      if ($l20_tipojulg == 1) {
        $pdf->cell(10, $alt, $pc11_seq, 0, 0, "C", $p);
        $pdf->cell(40, $alt, substr($pc01_codmater . " - " . $pc01_descrmater, 0, 23), 0, 0, "L", $p);
      }

      if ($l20_tipojulg != 1) {
        $pdf->cell(40, $alt, $l04_descricao, 0, 0, "L", $p);
      }

      $pdf->cell(60, $alt, substr(@$nome_julgado, 0, 30), 0, 0, "L", $p);
      $pdf->cell(60, $alt, substr($nome_trocado, 0, 30), 0, 0, "L", $p);
      $pdf->multicell(110, $alt, $pc25_motivo, 0, "J", $p);

      if ($p == 0) {
        $p = 1;
      } else {
        $p = 0;
      }
    }
  }
}



if ($oConfiguracaoGed->utilizaGED()) {

  try {

    $sTipoDocumento = GerenciadorEletronicoDocumentoConfiguracao::MAPA_PROPOSTA;

    $oGerenciador = new GerenciadorEletronicoDocumento();
    $oGerenciador->setLocalizacaoOrigem("tmp/");
    $oGerenciador->setNomeArquivo("{$sTipoDocumento}_{$orcamento}.pdf");

    $oStdDadosGED        = new stdClass();
    $oStdDadosGED->nome  = $sTipoDocumento;
    $oStdDadosGED->tipo  = "NUMERO";
    $oStdDadosGED->valor = $orcamento;

    $pdf->Output("tmp/{$sTipoDocumento}_{$orcamento}.pdf");

    $oGerenciador->moverArquivo(array($oStdDadosGED));


  } catch (Exception $eErro) {
    db_redireciona("db_erros.php?fechar=true&db_erro=".$eErro->getMessage());
  }
} else {
  $pdf->Output();
}

?>