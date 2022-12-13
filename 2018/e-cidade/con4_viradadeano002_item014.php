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

// Para garantir que nao houve erros em outros itens
if ($sqlerro==false) {

  //TRANSAÇÕES";
  $sqldestino = "select * from contrans where c45_anousu = $anodestino limit 1";
  $resultdestino = db_query($sqldestino);
  $linhasdestino = pg_num_rows($resultdestino);

  $_where   = "";
  // Se existir contrans no destino significa que ja foi processada virada entao...
  if($linhasdestino>0) {
    // Desprocessar Transacoes de TIPO 1000-Fechamento Exercicio e 2000-Abertura Exercicio
    $_tipodoc = "1000, 2000";

    // Apaga ContransLr
    $sqldelete  = "delete ";
    $sqldelete .= "  from contranslr  ";
    $sqldelete .= " using contranslan, ";
    $sqldelete .= "       contrans, ";
    $sqldelete .= "       conhistdoc ";
    $sqldelete .= " where c47_seqtranslan = c46_seqtranslan ";
    $sqldelete .= "   and c46_seqtrans    = c45_seqtrans ";
    $sqldelete .= "   and c45_coddoc      = c53_coddoc ";
    $sqldelete .= "   and c45_anousu      = $anodestino ";
    $sqldelete .= "   and c53_tipo        in ($_tipodoc)";

    $resultdelete = db_query($sqldelete);

    if ($resultdelete==true) {
      $sqlerro = false;
    } else {
      $sqlerro   = true;
      $erro_msg .= pg_last_error($resultdelete); //echo "<br>Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
    }

    // Apaga ContransLan
    if($sqlerro == false) {
      $sqldelete  = "delete ";
      $sqldelete .= "  from contranslan ";
      $sqldelete .= " using contrans, ";
      $sqldelete .= "       conhistdoc ";
      $sqldelete .= " where c46_seqtrans = c45_seqtrans ";
      $sqldelete .= "   and c45_coddoc   = c53_coddoc ";
      $sqldelete .= "   and c45_anousu   = $anodestino ";
      $sqldelete .= "   and c53_tipo     in ($_tipodoc)";

      $resultdelete = db_query($sqldelete);

      if ($resultdelete==true) {
        $sqlerro = false;
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdelete); //echo "<br>Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
    }

    // Apaga Contrans
    if($sqlerro == false) {
      $sqldelete  = "delete ";
      $sqldelete .= "  from contrans ";
      $sqldelete .= " using conhistdoc ";
      $sqldelete .= " where c45_coddoc = c53_coddoc ";
      $sqldelete .= "   and c45_anousu = $anodestino ";
      $sqldelete .= "   and c53_tipo   in ($_tipodoc)";

      $resultdelete = db_query($sqldelete);

      if ($resultdelete==true) {
        $sqlerro = false;
      } else {
        $sqlerro   = true;
        $erro_msg .= pg_last_error($resultdelete); //echo "<br>Ocorreu um erro durante o processamento do item $c33_descricao. Processamento cancelado.";
      }
    }

    $_where = "and exists (select 1 from conhistdoc where c53_coddoc = c45_coddoc and c53_tipo in ($_tipodoc))";
    $sMensagemTermometroItem .= " [Reprocessando Transações Abertura/Encerramento Exercício]";
    $linhasdestino = 0;

    // gera log do reprocessamento
    $cldb_viradaitemlog->c35_sequencial = null;
    $cldb_viradaitemlog->c35_log = "Reprocessamento Transações Abertura/Encerramento Exercício para ano de destino $anodestino";
    $cldb_viradaitemlog->c35_codarq        =  816;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    if ($cldb_viradaitemlog->erro_status==0) {
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }

  }


  if($sqlerro==false) {

    $sqlorigem = "select * from contrans where c45_anousu = $anoorigem $_where";
    $resultorigem = db_query($sqlorigem);
    $linhasorigem = pg_num_rows($resultorigem);

    //db_criatabela($resultorigem);
    //echo "<br>$sqlorigem";
    //echo "<br>linhasorigem $linhasorigem<br>linhasdestino $linhasdestino";
    if (($linhasorigem > 0) && ($linhasdestino == 0 )) {
      // duplica contrans

      // contrans
      include(modification("classes/db_contrans_classe.php"));
      include(modification("classes/db_contranslan_classe.php"));
      include(modification("classes/db_contranslr_classe.php"));
      $cl_contrans    = new cl_contrans ;
      $cl_contranslan = new  cl_contranslan;
      $cl_contranslr  = new  cl_contranslr;

      for ($r=0; $r<$linhasorigem; $r++) {
        db_fieldsmemory($resultorigem,$r);
        db_atutermometro($r, $linhasorigem, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 1/2)");

        $cl_contrans->c45_anousu = $anodestino;
        $cl_contrans->c45_coddoc = $c45_coddoc;
        $cl_contrans->c45_instit = $c45_instit;
        $cl_contrans->incluir(null);
        if ($cl_contrans->erro_status==0) {
          $sqlerro   = true;
          $erro_msg .= $cl_contrans->erro_msg;
          break;
        }
      }


      if ($sqlerro==false) {
        //contranslan
        $sqlcontranslan  = " select * from contranslan ";
        $sqlcontranslan .= " inner join contrans on c45_seqtrans=c46_seqtrans ";
        $sqlcontranslan .= " where contrans.c45_anousu={$anoorigem} {$_where} ";
        $resultcontranslan = db_query($sqlcontranslan);
        $linhascontranslan = pg_num_rows($resultcontranslan);
        //db_criatabela($resultcontranslan);
        //die();
        for ($c=0; $c<$linhascontranslan; $c++) {
          db_fieldsmemory($resultcontranslan,$c);
          db_atutermometro($c, $linhascontranslan, 'termometroitem', 1, $sMensagemTermometroItem . " (Passo 2/2)");

          $c46_seqtrans_antigo    = $c46_seqtrans;
          $c46_seqtranslan_antigo = $c46_seqtranslan;

          $sql2  = "select c45_seqtrans ";
          $sql2 .= "  from contrans ";
          $sql2 .= " where c45_coddoc = {$c45_coddoc} ";
          $sql2 .= "   and c45_anousu = {$anodestino} ";
          $sql2 .= "   and c45_instit = {$c45_instit} ";
          $result2 = db_query($sql2);
          $linhas2 = pg_num_rows($result2);
          db_fieldsmemory($result2,0);
          $c46_seqtrans_novo = $c45_seqtrans;


          //$c46_valor       = ($c46_valor != "")?$c46_valor:0;
          //$c46_obrigatorio = (!is_null($c46_obrigatorio))?$c46_obrigatorio:'f';
          //$c46_evento      = ($c46_evento != "")?$c46_evento:0;
          //echo "<br>$c c46_valor $c46_valor c46_obrigatorio $c46_obrigatorio c46_evento $c46_evento";

          // Seta o Globals em funcao de problema no metodo AtualizaCampos da classe cl_contranslan
          //$GLOBALS["HTTP_POST_VARS"]["c46_obrigatorio"] = $c46_obrigatorio;
          $c46_obrigatorio = ($c46_obrigatorio=='t')?'true':'false';

          $cl_contranslan->c46_seqtrans    = $c46_seqtrans_novo;
          $cl_contranslan->c46_codhist     = $c46_codhist;
          $cl_contranslan->c46_obs         = "$c46_obs";
          $cl_contranslan->c46_valor       = $c46_valor;
          $cl_contranslan->c46_obrigatorio = "$c46_obrigatorio";
          $cl_contranslan->c46_descricao   = "$c46_descricao";
          $cl_contranslan->c46_ordem       = "$c46_ordem";
          $cl_contranslan->c46_evento      = $c46_evento;
          $cl_contranslan->incluir(null);
          if ($cl_contranslan->erro_status==0) {
            $sqlerro   = true;
            $erro_msg .= $cl_contranslan->erro_msg;
            break;
          }

          //contranslr
          $sqlcontranslr  = "select * from contranslr ";
          $sqlcontranslr .= "left join contranslrelemento on c114_contranslr = c47_seqtranslr ";
          $sqlcontranslr .=" where  c47_seqtranslan = $c46_seqtranslan_antigo ";
          $sqlcontranslr .= "and case when c47_debito <> 0 and c47_credito = 0 then ";
          $sqlcontranslr .= "           exists (select * from conplanoreduz where c61_anousu=$anodestino and c61_instit=c47_instit and c61_reduz=c47_debito) ";
          $sqlcontranslr .= "         when c47_debito = 0 and c47_credito <> 0 then ";
          $sqlcontranslr .= "           exists (select * from conplanoreduz where c61_anousu=$anodestino and c61_instit=c47_instit and c61_reduz=c47_credito) ";
          $sqlcontranslr .= "         when c47_debito <> 0 and c47_credito <> 0 then ";
          $sqlcontranslr .= "           exists (select * from conplanoreduz where c61_anousu=$anodestino and c61_instit=c47_instit and c61_reduz=c47_debito) and ";
          $sqlcontranslr .= "           exists (select * from conplanoreduz where c61_anousu=$anodestino and c61_instit=c47_instit and c61_reduz=c47_credito) ";
          $sqlcontranslr .= "         else ";
          $sqlcontranslr .= "           true ";
          $sqlcontranslr .= "    end ";
          $resultcontranslr = db_query($sqlcontranslr);
          $linhascontranslr = pg_num_rows($resultcontranslr);
          if ($linhascontranslr > 0) {
            for ($h=0; $h < $linhascontranslr; $h++) {

              db_fieldsmemory($resultcontranslr,$h);
              $oRegraEventoContabil = db_utils::fieldsMemory($resultcontranslr, $h);

              if ($c45_coddoc!=31 && $c45_coddoc!=32 && $c45_coddoc!=33 && $c45_coddoc!=34 && $c45_coddoc!=35 && $c45_coddoc!=36 ) {
                $c47_anousu = $anodestino;
              }

              //insert into contranslr
              $cl_contranslr->c47_seqtranslan = $cl_contranslan->c46_seqtranslan ;
              $cl_contranslr->c47_debito      = $c47_debito;
              $cl_contranslr->c47_credito     = $c47_credito;
              $cl_contranslr->c47_obs         = "$c47_obs";
              $cl_contranslr->c47_ref         = $c47_ref;
              $cl_contranslr->c47_anousu      = $c47_anousu;
              $cl_contranslr->c47_instit      = $c47_instit;
              $cl_contranslr->c47_compara     = $c47_compara;
              $cl_contranslr->c47_tiporesto   = $c47_tiporesto;
              $cl_contranslr->incluir(null);

              if ($cl_contranslr->erro_status==0) {
                $sqlerro   = true;
                $erro_msg .= $cl_contranslr->erro_msg;
                break;
              }

              if (!empty($oRegraEventoContabil->c114_sequencial)) {

                $oDaoContranslrElemento = new cl_contranslrelemento;
                $oDaoContranslrElemento->c114_contranslr = $cl_contranslr->c47_seqtranslr;
                $oDaoContranslrElemento->c114_elemento = $oRegraEventoContabil->c114_elemento;
                $oDaoContranslrElemento->incluir(null);

                if ($oDaoContranslrElemento->erro_status == 0) {

                  $sqlerro = true;
                  $erro_msg .= $oDaoContranslrElemento->erro_msg;
                }
              }

              if ($sqlerro == true) {
                break;
              }
            }
            if($sqlerro==true) {
              break;
            }
          }
        }
      }

    } else {

      if ($linhasorigem == 0) {
        $cldb_viradaitemlog->c35_log = "Não existem (contrans) para ano de origem $anoorigem";
      } else if ($linhasdestino>0) {
        $cldb_viradaitemlog->c35_log = "Ja existem  (contrans) para ano de destino $anodestino";
      }

      $cldb_viradaitemlog->c35_sequencial = null;
      $cldb_viradaitemlog->c35_codarq        =  816;
      $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
      $cldb_viradaitemlog->c35_data          = date("Y-m-d");
      $cldb_viradaitemlog->c35_hora          = date("H:i");
      $cldb_viradaitemlog->incluir(null);
      if ($cldb_viradaitemlog->erro_status==0) {
        $sqlerro   = true;
        $erro_msg .= $cldb_viradaitemlog->erro_msg;
        break;
      }
    }
  }



}
