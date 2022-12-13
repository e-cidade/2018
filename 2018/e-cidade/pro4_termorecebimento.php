<?php

/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once("fpdf151/scpdf.php");
require_once("classes/db_db_config_classe.php");

$cldb_config = new cl_db_config;
$result33    = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), "*"));
if ($cldb_config->numrows > 0) {
  db_fieldsmemory($result33, 0);
} else {
  $nomeinst = '';
}
$sqlpara = "select pd.descrdepto as pdepto,
                      p62_coddepto,
                      p63_codtran,
                      pu.nome as pusu,
                      instip.nomeinst as instipara,
                      dd.descrdepto as ddepto,
                      p62_coddeptorec,
                      instid.nomeinst as instide,
                      du.nome as dusu,
                      to_char(p58_dtproc,'YYYY') as anoproc,
                      to_char(p62_dttran,'DD/MM/YYYY') as dttran,
                      p62_hora,
                      p58_numero,
                      p58_ano,
                      p58_codproc,
                      case when ov09_protprocesso::varchar is null then ''
                        else ov01_numero||' / '||ov01_anousu
                      end as atendimento
                 from proctransferproc inner join proctransfer on p63_codtran     = p62_codtran
                      inner join protprocesso                  on p63_codproc     = p58_codproc
                      inner join db_depart pd                  on pd.coddepto     =  p62_coddeptorec
                      inner join db_config as instip           on pd.instit       = instip.codigo
                      inner join db_depart dd                  on dd.coddepto     =  p62_coddepto
                      inner join db_config as instid           on dd.instit       = instid.codigo
                      left join db_usuarios pu                 on pu.id_usuario   = p62_id_usorec
                      left join db_usuarios du                 on du.id_usuario   = p62_id_usuario
                      left  join processoouvidoria             on p58_codproc     = ov09_protprocesso
                      left  join ouvidoriaatendimento          on ov01_sequencial = ov09_ouvidoriaatendimento

                where p63_codtran = $codtran ";

$sqlproc = "select p63_codproc,
                       z01_nome,
                       p51_descr,
                       p58_obs,
                       to_char(p58_dtproc,'YYYY') as anoproc,
                       to_char(p58_dtproc,'DD/MM/YYYY') as dtproc,
                       p58_numero,
                       p58_ano
                  from proctransferproc
                       inner join protprocesso on p63_codproc = p58_codproc
                       inner join cgm on p58_numcgm = z01_numcgm
                       inner join tipoproc on p58_codigo = p51_codigo
                 where p63_codtran = $codtran";
$rspara = db_query($sqlpara);

if (pg_numrows($rspara) == 0) {

  db_redireciona('db_erros.php?fechar=true&db_erro=Sem processos nesta transferência!');
  exit;
}

$iLinhasPorPagina = 15;
$rsproc           = db_query($sqlproc);
$nNumeroLinhas    = pg_num_rows($rsproc);
$imptit           = 0;
$iNumeroPaginas   = ceil($nNumeroLinhas / $iLinhasPorPagina);
$pdf              = new scpdf();
$pdf->open();

for ($multiplo = 1; $multiplo <= $iNumeroPaginas; $multiplo++) {

  $pdf->AddPage();
  $pdf->AliasNbPages();
  $pdf->settopmargin(1);
  $pdf->line(2, 148.5, 208, 148.5);
  $xlin = 20;
  $xcol = 4;

  $getlogo = db_getnomelogo();
  $logo    = ($getlogo == false ? '' : $getlogo);

  db_fieldsmemory($rspara, 0);
  $multiplofor = (($multiplo - 1) * 15);
  for ($i = 0; $i < 2; $i++) {

    $pdf->setfillcolor(245);
    $pdf->roundedrect($xcol - 2, $xlin - 18, 206, 144.5, 2, 'DF', '1234');
    $pdf->setfillcolor(255, 255, 255);
    $pdf->Setfont('Arial', 'B', 11);
    /*
     * pega o ano da transferencia para colocar ao lado do numero do processo
     * antes vinha com o ano de criação do processo.
     */
    $aAnoTransferencia = explode("/", $dttran);
    $iAnoTransferencia = $aAnoTransferencia[2];

    $pdf->text(120, $xlin - 13, 'Termo de Recebimento nº ' . db_formatar($p63_codtran, "s", "0", 5, "e") . " / " . $iAnoTransferencia);
    if ($nNumeroLinhas == 1) {
      $pdf->text(120, $xlin - 9, "Processo nº $p58_numero / $p58_ano");
    }
    if ($atendimento != "") {
      $pdf->text(120, $xlin - 5, 'Atendimento nº ' . $atendimento);
    }

    $pdf->Image('imagens/files/' . $logo, 15, $xlin - 17, 12);
    $pdf->Setfont('Arial', 'B', 9);

    $pdf->text(40, $xlin - 15, '' . $nomeinst);
    $pdf->Setfont('Arial', '', 9);
    $pdf->text(40, $xlin - 11, '' . $ender);
    $pdf->text(40, $xlin - 8, '' . $munic);
    $pdf->text(40, $xlin - 5, '' . $telef);
    $pdf->text(40, $xlin - 2, '' . $url);
    $pdf->Roundedrect($xcol, $xlin + 1, $xcol + 93, 20, 2, 'DF', '1234');
    $pdf->Setfont('Arial', 'B', 8);
    $pdf->text($xcol + 2, $xlin + 4, 'De:');
    $pdf->Setfont('Arial', '', 8);
    $pdf->text($xcol + 2, $xlin + 7, 'Nome :');
    $pdf->text($xcol + 25, $xlin + 7, $dusu);
    $pdf->text($xcol + 2, $xlin + 11, 'Instituição:');
    $pdf->text($xcol + 25, $xlin + 11, $instide);
    $pdf->text($xcol + 2, $xlin + 15, 'Departamento:');
    $pdf->text($xcol + 25, $xlin + 15, "$p62_coddepto - $ddepto ");
    $pdf->text($xcol + 2, $xlin + 19, 'Data :');
    $pdf->text($xcol + 17, $xlin + 19, $dttran);
    $pdf->text($xcol + 40, $xlin + 19, 'Hora: ' . $p62_hora);
    $pdf->Setfont('Arial', 'B', 8);
    $pdf->Roundedrect($xcol + 100, $xlin + 1, 102, 20, 2, 'DF', '1234');
    $pdf->text($xcol + 102, $xlin + 4, 'Para:');
    $pdf->Setfont('Arial', '', 8);
    $pdf->text($xcol + 102, $xlin + 7, 'Nome:');
    $pdf->text($xcol + 124, $xlin + 7, $pusu);
    $pdf->text($xcol + 102, $xlin + 11, 'Instituição:');
    $pdf->text($xcol + 124, $xlin + 11, $instipara);
    $pdf->text($xcol + 102, $xlin + 15, 'Departamento:');
    $pdf->text($xcol + 124, $xlin + 15, "$p62_coddeptorec - $pdepto");
    $pdf->Setfont('Arial', 'B', 8);
    $pdf->text($xcol + 2, $xlin + 25, "Atraves deste faço entregue os seguintes processos abaixo relacionados:");
    $pdf->Roundedrect($xcol, $xlin + 30, 202, 60, 2, 'DF', '1234');
    $pdf->sety($xlin + 30);
    $maiscol = 0;
    $pdf->Setfont('Arial', 'B', 8);
    $yy = $pdf->gety();
    $pdf->setx($xcol + 3);
    $pdf->cell(20, 3, 'Protocolo', 0, 0, "R", 0);
    $pdf->cell(75, 3, 'Requerente', 0, 0, "L", 0);
    $pdf->cell(50, 3, 'Descrição', 0, 0, "L", 0);
    $pdf->cell(75, 3, 'Tipo', 0, 1, "L", 0);
    $pdf->Setfont('Arial', '', 7);

    for ($ii = $multiplofor; $ii < ($multiplo * 15); $ii++) {

      if ($ii >= pg_numrows($rsproc)) {
        break;
      }

      db_fieldsmemory($rsproc, $ii);
      $pdf->setx($xcol + 3);

      $sNumeroProtocolo = $p58_numero . "/" . $p58_ano;
      if ($p58_numero == "") {
        $sNumeroProtocolo = "";
      }

      $pdf->cell(20, 3, $sNumeroProtocolo, 0, 0, "R", 0);
      $pdf->cell(75, 3, $z01_nome, 0, 0, "L", 0);
      $pdf->cell(50, 3, substr($p58_obs, 0, 30), 0, 0, "L", 0);
      $pdf->cell(75, 3, substr($p51_descr, 0, 35), 0, 1, "L", 0);
      if ($ii == $nNumeroLinhas - 1) {
        break;
      }
    }
    $pdf->Setfont('Arial', '', 6);
    $pdf->setxy(10, $xlin + 100);
    $pdf->multicell(75, 2, str_repeat(".", 75) . "\n" . $dusu, 0, 'C');
    $pdf->setxy(125, $xlin + 100);
    if ($pusu == "") {
      $pdf->multicell(75, 2, str_repeat(".", 75) . "\n" . 'Responsável pelo Departamento', 0, 'C');
    } else {
      $pdf->multicell(75, 2, str_repeat(".", 75) . "\n" . $pusu, 0, 'C');
    }
    $pdf->Setfont('Arial', '', 10);
    $pdf->text(150, $xlin + 115, 'Recebido em : __/__/___');
    $xlin = 169;
  }
}

$pdf->output();
