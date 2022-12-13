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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("classes/db_liclicita_classe.php"));
require_once(modification("classes/db_liclicitasituacao_classe.php"));
require_once(modification("classes/db_liclicitem_classe.php"));
require_once(modification("classes/db_empautitem_classe.php"));
require_once(modification("classes/db_pcorcamjulg_classe.php"));
require_once(modification("model/licitacao.model.php"));

$clliclicita         = new cl_liclicita;
$clliclicitasituacao = new cl_liclicitasituacao;
$clliclicitem        = new cl_liclicitem;
$clempautitem        = new cl_empautitem;
$clpcorcamjulg       = new cl_pcorcamjulg;
$clrotulo            = new rotulocampo;

$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);
$sWhere = "";
$sAnd   = "";
if (($data != "--") && ($data1 != "--")) {

  $sWhere .= $sAnd." l20_datacria  between '$data' and '$data1' ";
  $data = db_formatar($data, "d");
  $data1 = db_formatar($data1, "d");
  $info = "De $data até $data1.";
  $sAnd = " and ";
} else if ($data != "--") {

  $sWhere .= $sAnd." l20_datacria >= '$data'  ";
  $data = db_formatar($data, "d");
  $info = "Apartir de $data.";
  $sAnd = " and ";
} else if ($data1 != "--") {

  $sWhere .= $sAnd." l20_datacria <= '$data1'   ";
  $data1 = db_formatar($data1, "d");
  $info = "Até $data1.";
  $sAnd = " and ";
}
if ($l20_codigo != "") {

  $sWhere .= $sAnd." l20_codigo=$l20_codigo ";
  $sAnd = " and ";
}
if ($l20_numero!="") {

  $sWhere .= $sAnd." l20_numero=$l20_numero ";
  $sAnd = " and ";
  $info1 = "Numero:".$l20_numero;
}
if ($l03_codigo != "") {

  $sWhere .= $sAnd." l20_codtipocom=$l03_codigo ";
  $sAnd = " and ";
  if ( $l03_descr!="") {
    $info2 = "Modalidade:".$l03_codigo."-".$l03_descr;
  }
}
if ($situac != '') {

  $in = $selec == "S"?" in ":" not in";
  $sWhere .= $sAnd ." l20_licsituacao $in ($situac)";
  $sAnd = " and ";
}

$sWhere        .= $sAnd." l20_instit = ".db_getsession("DB_instit");
$sSqlLicLicita  = $clliclicita->sql_query(null,"*","l20_codtipocom,l20_numero,l20_anousu",$sWhere);

$result         = $clliclicita->sql_record($sSqlLicLicita);
$numrows        = $clliclicita->numrows;

if ($numrows == 0) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Não existe registro cadastrado.');
  exit;
}

$head2 = "Relatório de Licitação";
$head3 = @$info;
$head4 = @$info1;
$head5 = @$info2;
$pdf   = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca       = 1;
$alt         = 4;
$total       = 0;
$p           = 0;
$valortot    = 0;
$muda        = 0;
$mostraAndam = $mostramov;
$oInfoLog    = array();
for ($i = 0; $i < $numrows; $i++) {

  db_fieldsmemory($result,$i);

  if (empty($l20_procadmin)) {

    $oDAOLiclicitaproc    = db_utils::getDao("liclicitaproc");
    $sSqlProcessoSistema  = $oDAOLiclicitaproc->sql_query(null,"*", null, "l34_liclicita = {$l20_codigo}");
    $rsProcessoSistema    = $oDAOLiclicitaproc->sql_record($sSqlProcessoSistema);

    if ($oDAOLiclicitaproc->numrows == 1) {

      $oLiclicitaproc = db_utils::fieldsMemory($rsProcessoSistema, 0);
      $l20_procadmin  = substr($oLiclicitaproc->p58_numero ."/". $oLiclicitaproc->p58_ano . " - " . $oLiclicitaproc->p51_descr , 0, 120);
    }
  }

  $oLicitacao = new licitacao($l20_codigo);
  if ($l20_licsituacao == 3) {
    $oInfoLog = $oLicitacao->getInfoLog();
  }
  if ($mostra == 'n') {

    if ($pdf->gety() > $pdf->h - 30 || $muda == 0) {

      $pdf->addpage();
      $muda = 1;
    }
  } else {
    $pdf->addpage();
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Código Sequencial:',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_codigo,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Edital :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$l20_edital,0,0,"L",0);


  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Tipo de Compra :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_codtipocom.' - '.$l03_descr,0,0,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Número :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$l20_numero,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Data Publicação :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,db_formatar($l20_dtpublic,'d'),0,0,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Data Abertura :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,db_formatar($l20_dataaber,'d'),0,0,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(60,$alt,'Hora Abertura :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$l20_horaaber,0,1,"L",0);


  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Situação :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(30,$alt,$l08_descr,0,0,"L",0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Usuário :',0,0,"R",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(60,$alt,$l20_id_usucria.' - '.$nome,0,1,"L",0);

  $pdf->setfont('arial', 'b', 8);
  $pdf->cell(30, $alt, 'Proc. Administrativo:', 0, 0, "R", 0);
  $pdf->setfont('arial', '', 7);
  $pdf->cell(60, $alt, $l20_procadmin, 0, 1, "L", 0);

  $pdf->setfont('arial','b',8);
  $pdf->cell(30,$alt,'Objeto :',0,0,"R",0);
  $pdf->setfont('arial','b',8);
  $pdf->multicell(150,$alt,$l20_objeto,0,"L",0);

  $result_sec=$clliclicitem->sql_record($clliclicitem->sql_query_orc(null,"distinct o40_descr",null,"l21_codliclicita = $l20_codigo"));
  if ($l20_licsituacao == 3) {

    $clliclicitem->numrows = count($oInfoLog->secretarias);
  }
  if ($clliclicitem->numrows>0) {

    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Secretaria(s) :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    for ($z = 0; $z < $clliclicitem->numrows; $z++) {

      if ($l20_licsituacao != 3 ) {
        db_fieldsmemory($result_sec,$z);
      } else {
        $o40_descr = utf8_decode($oInfoLog->secretarias->secretaria[$i]);
      }
      if ($z != 0) {
        $pdf->cell(30,$alt,"",0,0,"R",0);
      }
      $pdf->cell(150,$alt,$o40_descr,0,1,"L",0);
    }
  }
  $pdf->cell(190,$alt,'','T',1,"L",0);
  $result_dataaut=$clempautitem->sql_record($clempautitem->sql_query_lic(null,null,"distinct e54_emiss,e54_autori","e54_autori","l20_codigo=$l20_codigo"));
  if ($clempautitem->numrows>0) {

    db_fieldsmemory($result_dataaut,0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Data Adjudicação :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(60,$alt,db_formatar($e54_emiss,'d'),0,1,"L",0);
  }
  $result_orcam     = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_adjudicacao(null, null, "distinct z01_numcgm,z01_nome,pcorcam.pc20_dtate,pcorcam.pc20_hrate", "z01_nome", "l20_codigo=$l20_codigo and pc24_pontuacao=1 and pc10_instit=".db_getsession("DB_instit")));
  $numrows_orcam    = $clpcorcamjulg->numrows;
  $result_valorcam  = $clpcorcamjulg->sql_record($clpcorcamjulg->sql_query_adjudicacao(null, null, "sum(pc23_valor)as valor_adj", "", "l20_codigo=$l20_codigo and pc24_pontuacao=1 and pc10_instit=".db_getsession("DB_instit")));
  $numrows_valorcam = $clpcorcamjulg->numrows;
  if ($numrows_orcam > 0) {

    db_fieldsmemory($result_valorcam,0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Valor Adjudicado :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(60,$alt,db_formatar($valor_adj,'f'),0,0,"L",0);
    $pdf->setfont('arial','b',8);
    $pdf->cell(30,$alt,'Empresa(s) Vencedora(s) :',0,0,"R",0);
    $pdf->setfont('arial','',7);
    for ($z = 0; $z < $numrows_orcam; $z++) {

      db_fieldsmemory($result_orcam,$z);
      if ($z != 0) {
        $pdf->cell(30,$alt,"",0,0,"L",0);;
      }
      $pdf->cell(60,$alt,$z01_nome,0,1,"L",0);

    }
    $pdf->cell(190,$alt,'','T',1,"L",0);
  } else {
    $pdf->cell(190,$alt,'ADJUDICAÇÃO  NÃO REALIZADA','TB',1,"L",0);
  }
  if ($mostra == 's') {

    $troca = 1;
    $sSql  = $clliclicitem->sql_query_inf(null,"distinct l21_ordem,l21_codigo,pc11_numero,
                                                pc11_codigo,pc11_quant,
                                                pc11_seq, pc11_vlrun,
                                                pc11_resum,pc01_codmater,
                                                pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,
                                                m61_usaquant,
                                                null as z01_nome,
                                                null as z01_numcgm",
                                               "l21_ordem","l21_codliclicita=$l20_codigo");

    /**
     * Situações em que devem ser apresentado o valor julgado do item, ao invés do valor da solicitação da compra
     */
    $aSituacoes = array(
      SituacaoLicitacao::SITUACAO_JULGADA,
      SituacaoLicitacao::SITUACAO_ADJUDICADA,
      SituacaoLicitacao::SITUACAO_HOMOLOGADA
    );

    if (in_array($l20_licsituacao, $aSituacoes)) {

      $sSql = "select
                 liclicitem.l21_ordem,
                 pcmater.pc01_codmater,
                 pcmater.pc01_descrmater,
                 pcorcamval.pc23_vlrun as pc11_vlrun,
                 pcorcamval.pc23_quant as pc11_quant,
                 pcorcamval.pc23_valor,
                 solicitem.pc11_resum,
                 matunid.m61_descr,
                 matunid.m61_usaquant,
                 solicitemunid.pc17_unid,
                 solicitemunid.pc17_quant,
                 z01_nome,
                 z01_numcgm
                 from liclicita
                      inner join liclicitem       on liclicita.l20_codigo           = liclicitem.l21_codliclicita
                      inner join pcorcamitemlic   on liclicitem.l21_codigo          = pcorcamitemlic.pc26_liclicitem
                      inner join pcorcamval       on pcorcamitemlic.pc26_orcamitem  = pcorcamval.pc23_orcamitem
                      inner join pcorcamforne     on pcorcamval.pc23_orcamforne     = pcorcamforne.pc21_orcamforne
                      inner join pcorcamjulg      on pcorcamitemlic.pc26_orcamitem  = pcorcamjulg.pc24_orcamitem
                                                 and pcorcamforne.pc21_orcamforne   = pcorcamjulg.pc24_orcamforne
                                                 and pcorcamjulg.pc24_pontuacao     = 1
                      inner join pcprocitem       on liclicitem.l21_codpcprocitem   = pcprocitem.pc81_codprocitem
                      inner join solicitem        on pcprocitem.pc81_solicitem      = solicitem.pc11_codigo
                      inner join solicitempcmater on solicitem.pc11_codigo          = solicitempcmater.pc16_solicitem
                      inner join pcmater          on solicitempcmater.pc16_codmater = pcmater.pc01_codmater
                      left  join solicitemunid    on solicitem.pc11_codigo          = solicitemunid.pc17_codigo
                      left  join matunid          on solicitemunid.pc17_unid        = matunid.m61_codmatunid
                      inner join cgm              on pcorcamforne.pc21_numcgm       = cgm.z01_numcgm
                 where liclicita.l20_codigo = {$l20_codigo}
                 order by liclicitem.l21_ordem";
    }

    $result_itens = $clliclicitem->sql_record($sSql);
    if ($l20_licsituacao == 3) {
      $clliclicitem->numrows = count($oInfoLog->item);
    }
    $aItens = array();
    if ($clliclicitem->numrows > 0) {
      for ($w = 0; $w < $clliclicitem->numrows; $w++) {

        if ($l20_licsituacao != 3 ) {
          $oItem = db_utils::fieldsMemory($result_itens, $w);
        } else {

          $oItem                  = new stdClass();
          $oItem->l21_ordem       = utf8_decode($oInfoLog->item[$w]->l21_ordem);
          $oItem->pc01_codmater   = utf8_decode($oInfoLog->item[$w]->pc01_codmater);
          $oItem->pc01_descrmater = utf8_decode($oInfoLog->item[$w]->pc01_descrmater);
          $oItem->pc11_quant      = utf8_decode($oInfoLog->item[$w]->pc11_quant);
          $oItem->pc11_vlrun      = utf8_decode($oInfoLog->item[$w]->pc11_vlrun);
          $oItem->pc01_servico    = utf8_decode($oInfoLog->item[$w]->pc01_servico);
          $oItem->m61_descr       = utf8_decode($oInfoLog->item[$w]->m61_descr);
          $oItem->m61_usaquant    = utf8_decode($oInfoLog->item[$w]->m61_usaquant);
          $oItem->pc17_quant      = utf8_decode($oInfoLog->item[$w]->pc17_quant);
          $oItem->pc11_resum      = utf8_decode($oInfoLog->item[$w]->pc11_resum);
          $oItem->z01_nome        = '';
          $oItem->z01_numcgm      = '';
        }
        if (!isset($aItens[$oItem->z01_numcgm])) {

          $oFornecedor         = new stdClass();
          $oFornecedor->numcgm = $oItem->z01_numcgm;
          $oFornecedor->nome   = $oItem->z01_nome;
          $oFornecedor->itens  = array();
          $aItens[$oItem->z01_numcgm] = $oFornecedor;
        }
        $aItens[$oItem->z01_numcgm]->itens[] = $oItem;
      }

      foreach ($aItens as $oFornecedor) {

        if ($oFornecedor->nome != "") {

          $pdf->setfont('arial','b',8);
          $pdf->cell(100, $alt, "$oFornecedor->nome", 0, 1, "L");
          $troca = 1;
        }
        foreach ($oFornecedor->itens as $oItem) {

          if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

            if ($pdf->gety() > $pdf->h - 30) {
              $pdf->addpage();
            }
            $pdf->setfont('arial','b',8);
            $pdf->cell(10,$alt,"Item",1,0,"C",1);
            $pdf->cell(20,$alt,'Cod. Material',1,0,"C",1);
            $pdf->cell(50,$alt,'Descrição Material',1,0,"C",1);
            $pdf->cell(15,$alt,'Quant.',1,0,"C",1);
            $pdf->cell(15,$alt,'Valor Unit.',1,0,"C",1);
            $pdf->cell(15,$alt,'Valor Tot.',1,0,"C",1);
            $pdf->cell(35,$alt,'Referência',1,0,"C",1);
            $pdf->cell(35,$alt,'Resumo',1,1,"C",1);
            $p     = 0;
            $troca = 0;
          }
          $pdf->setfont('arial','',7);
          $pdf->cell(10,$alt,$oItem->l21_ordem,0,0,"C",$p);
          $pdf->cell(20,$alt,$oItem->pc01_codmater,0,0,"C",$p);
          $pdf->cell(50,$alt,ucfirst(mb_strtolower(substr($oItem->pc01_descrmater,0,40))),0,0,"L",$p);
          $pdf->cell(15,$alt,$oItem->pc11_quant,0,0,"C",$p);
          $pdf->cell(15,$alt,db_formatar($oItem->pc11_vlrun,"f"),0,0,"R",$p);
          $pdf->cell(15,$alt,db_formatar(($oItem->pc11_vlrun*$oItem->pc11_quant),"f"),0,0,"R",$p);
          if ((isset($oItem->pc01_servico) && (trim($oItem->pc01_servico) == "f" || trim($oItem->pc01_servico) == "")) || !isset($oItem->pc01_servico)) {

            $unid = trim(substr($oItem->m61_descr,0,10));
            if ($oItem->m61_usaquant=="t") {
              $unid .= " ($oItem->pc17_quant UNIDADES)";
            }
          } else {
            $unid = "SERVIÇO";
          }
          $pdf->cell(35,$alt,"$unid",0,0,"C",$p);
          $pdf->multicell(35,$alt,substr($oItem->pc11_resum,0,35),0,"L",$p);
          if ($p == 0) {
            $p = 1;
          } else {
            $p = 0;
          }
          $total++;
          $valortot += $oItem->pc11_vlrun * $oItem->pc11_quant;
        }
      }
      $pdf->cell(140,$alt,'Total de Registros: ' . $total,'T',0,"R",0);
      $pdf->cell(50,$alt,'Valor Total: ' . db_formatar($valortot, 'f'),'T',1,"R",0);
    }
    $total = 0;
    $troca = 1;
    $valortot = 0;

    $result_dot = $clliclicitem->sql_record($clliclicitem->sql_query_inf(null,"distinct fc_estruturaldotacao(pc13_anousu,pc13_coddot) as estrutural ",null,"l21_codliclicita=$l20_codigo"));
    if ($l20_licsituacao == 3) {
      $clliclicitem->numrows = count($oInfoLog->elementos);
    }
    if ($clliclicitem->numrows > 0) {

      for($w = 0; $w < $clliclicitem->numrows; $w++) {

        if ($l20_licsituacao != 3) {
          db_fieldsmemory($result_dot,$w);
        } else {
          $estrutural  = utf8_decode($oInfoLog->elementos->elemento[$w]);
        }
        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

          $pdf->setfont('arial','b',8);
          $pdf->cell(80,$alt,'Estrutural',1,1,"C",1);
          $p     = 0;
          $troca = 0;
        }
        $pdf->setfont('arial','',7);
        $pdf->cell(80,$alt,$estrutural,0,1,"C",$p);
        if ($p == 0) {
          $p = 1;
        } else {
          $p = 0;
        }
        $total++;
      }
      $pdf->cell(80,$alt,'Total de Registros: '.$total,'T',1,"R",0);
      $pdf->ln(3);

    }
  }
  if ($mostraAndam) {

    $rsAndam = $clliclicitasituacao->sql_record($clliclicitasituacao->sql_query('','*',"l11_data,l11_sequencial","l11_liclicita = $l20_codigo "));
    if ($clliclicitasituacao->numrows > 0) {

      $pdf->setfont('arial','b',8);
      $pdf->cell(100,$alt,'Usuário',1,0,"C",1);
      $pdf->cell(30,$alt,'Situação',1,0,"C",1);
      $pdf->cell(20,$alt,'Data',1,0,"C",1);
      $pdf->cell(20,$alt,'Hora',1,1,"C",1);
      $pdf->cell(170,$alt,'Observações',1,1,"C",1);
      $pdf->setfont('arial','',8);
      for ($k = 0; $k < $clliclicitasituacao->numrows; $k++) {

        if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {
          if ($pdf->gety() > $pdf->h - 30) {
            $pdf->addpage();
          }
          $pdf->setfont('arial','b',8);
          $pdf->cell(100,$alt,'Usuário',1,0,"C",1);
          $pdf->cell(30,$alt,'Situação',1,0,"C",1);
          $pdf->cell(20,$alt,'Data',1,0,"C",1);
          $pdf->cell(20,$alt,'Hora',1,1,"C",1);
          $pdf->cell(170,$alt,'Observações',1,1,"C",1);
          $pdf->setfont('arial','',8);
          $troca = 0;
        }

        db_fieldsmemory($rsAndam,$k);
        $pdf->cell(100,$alt,$nome,'B',0,"L");
        $pdf->cell(30,$alt,$l08_descr,'B',0,"L");
        $pdf->cell(20,$alt,db_formatar($l11_data,'d'),'B',0,"C");
        $pdf->cell(20,$alt,$l11_hora,"B",1,"C");
        $pdf->cell(170,$alt,$l11_obs,"B",1,"L");

      }

    }
  }

  if ($listarcotacao == 's') {

    $pdf->setfont('arial', 'b', 9);
    $pdf->cell(190, $alt, "Lista de Cotações", "B", 1);
    $sSqlCotacoes =  $clliclicitem->sql_query_inf(null,"distinct l21_ordem,l21_codigo,pc11_numero,
                                                pc11_codigo,
                                                pc11_quant,
                                                pc11_seq,
                                                pc23_vlrun,
                                                pc23_valor,
                                                pc11_resum,
                                                pc01_codmater,
                                                pc01_descrmater,
                                                z01_nome,
                                                z01_numcgm",
                                               "z01_nome,
                                               l21_ordem",
                                               "l21_codliclicita=$l20_codigo
                                                and pc23_orcamforne is not null"
                                               );

    $aItensFornecedor = array();
    $rsItens          = $clliclicitem->sql_record($sSqlCotacoes);

    if ($rsItens) {
       for ($iItem = 0; $iItem < $clliclicitem->numrows; $iItem++) {

         $oItem = db_utils::fieldsMemory($rsItens, $iItem);
         if (!isset($aItensFornecedor[$oItem->z01_numcgm])) {

           $oFornecedor         = new stdClass();
           $oFornecedor->numcgm = $oItem->z01_numcgm;
           $oFornecedor->nome   = $oItem->z01_nome;
           $oFornecedor->itens  = array();
           $aItensFornecedor[$oItem->z01_numcgm] = $oFornecedor;
         }
         $aItensFornecedor[$oItem->z01_numcgm]->itens[] = $oItem;
       }
    }

    foreach ($aItensFornecedor as $oFornecedor) {

      $lMostraCabecalhoCotacoes = true;

      $pdf->setfont('arial','',8);
      $pdf->cell(100, $alt, $oFornecedor->nome, 0, 1, "L");

      foreach ($oFornecedor->itens as $oItem) {

        if ($pdf->gety() > $pdf->h - 30 || $lMostraCabecalhoCotacoes) {

          if ($pdf->gety() > $pdf->h - 30) {
            $pdf->addpage();
          }

          $pdf->setfont('arial', 'b', 8);
          $pdf->cell(10, $alt, 'Ordem', 1, 0, "C", 1);
          $pdf->cell(70, $alt, 'Item', 1, 0, "C", 1);
          $pdf->cell(90, $alt, 'Resumo', 1, 0, "C", 1);
          $pdf->cell(20, $alt, 'Valor Total' , 1, 1, "C", 1);
          $pdf->setfont('arial' , '', 6);
          $lMostraCabecalhoCotacoes = false;
        }
        $pdf->cell(10, $alt, $oItem->l21_ordem, 0, 0, "R");
        $pdf->cell(70, $alt, mb_strtolower($oItem->pc01_descrmater), 0, 0, "L");
        $pdf->cell(90, $alt, substr($oItem->pc11_resum, 0, 70), 0, 0, "L");
        $pdf->cell(20, $alt, db_formatar($oItem->pc23_valor, 'f'), 0, 1, "R");
      }
    }
  }
}
$pdf->Output();