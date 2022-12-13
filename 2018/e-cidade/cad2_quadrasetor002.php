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

require_once("fpdf151/pdf.php");
require_once("libs/db_sql.php");

$clrotulo = new rotulocampo;
$clrotulo->label('j34_setor');
$clrotulo->label('j34_quadra');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$setor_arr = split(",",$setor);
$sVirgula  = "";
$sSetores  = "";

for($i=0;$i<count($setor_arr);$i++){

   $sSetores .= $sVirgula . "'" . $setor_arr[$i] . "'";
   $sVirgula  = ",";
}

$result = db_query("select j34_setor,j34_quadra  from lote  where j34_setor in ($sSetores)");
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem quadras cadastrados.');
}

$head3 = "QUADRAS POR LOTE";
$head5 = "ORDEM POR SETOR";

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$iTotal   = 0;
$iTroca   = 1;
$iAltura  = 4;
$quadras  = "";
$codigo   = "";
$sVirgula = "";

for($x = 0; $x < pg_numrows($result);$x++){

   db_fieldsmemory($result,$x);

   if ($oPdf->gety() > $oPdf->h - 30 || $iTroca != 0 ){

      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      $oPdf->cell(60,$iAltura,$RLj34_setor,1,0,"C",1);
      $oPdf->cell(0,$iAltura,$RLj34_quadra,1,1,"C",1);

      $iTroca = 0;
   }

   if( $j34_setor != $codigo ){

     $oPdf->setfont('arial','',7);
     $oPdf->cell(60,$iAltura,$j34_setor,0,0,"C",0);
     $sVirgula = "";
     $iTotal++;
     $res = db_query("select distinct j34_quadra from lote where j34_setor = '$j34_setor' order by j34_quadra");

     for($y=0;$y<pg_numrows($res);$y++){

       db_fieldsmemory($res,$y);
       $quadras .= $sVirgula . $j34_quadra;
       $sVirgula = ", ";
     }

     $oPdf->multicell(0,$iAltura,$quadras,0,"J",0);
     $oPdf->cell(0,$iAltura,"","T",1,"C",0);
     $codigo  = $j34_setor;
     $quadras = "";
   }
}

$oPdf->setfont('arial','b',8);
$oPdf->cell(0,$iAltura,'TOTAL DE REGISTROS: '.$iTotal,"T",0,"L",0);

$oPdf->Output();