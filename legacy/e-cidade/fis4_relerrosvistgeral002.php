<?
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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
$clrotulo = new rotulocampo;
db_postmemory($HTTP_SERVER_VARS);
$head3                     = "RELATORIO DE VISTORIAS NÃO CALCULADAS";

/**
 * Buscamos o retorno do cálculo de vistorias de localização
 */
$sSqlVistoriasLocalizacao  = " select y05_codvist,                                            ";
$sSqlVistoriasLocalizacao .= "        q02_inscr,                                              ";
$sSqlVistoriasLocalizacao .= "        z01_nome,                                               ";
$sSqlVistoriasLocalizacao .= "        y04_msgretorno                                          ";
$sSqlVistoriasLocalizacao .= "   from vistoriaslotevist                                       ";
$sSqlVistoriasLocalizacao .= "        left  join vistretornocalc on y04_codmsg  = y05_codmsg  ";
$sSqlVistoriasLocalizacao .= "        inner join vistorias       on y05_codvist = y70_codvist ";
$sSqlVistoriasLocalizacao .= "        inner join vistinscr       on y71_codvist = y05_codvist ";
$sSqlVistoriasLocalizacao .= "        inner join issbase         on q02_inscr   = y71_inscr   ";
$sSqlVistoriasLocalizacao .= "        inner join cgm             on z01_numcgm  = q02_numcgm  ";
$sSqlVistoriasLocalizacao .= "  where y05_codmsg not in (8, 9, 25)                            ";
$sSqlVistoriasLocalizacao .= "    and y05_vistoriaslote = $numlote                            ";
$sSqlVistoriasLocalizacao .= " 	  and y70_instit = ".db_getsession('DB_instit')                ;

$rsVistorias               = db_query($sSqlVistoriasLocalizacao);

if ( empty($rsVistorias) ) {

  echo "<script>alert('Erro ao buscar retorno do cálculo de vistorias.')</script>";
  exit;
}


$iResultadoVistorias = pg_num_rows($rsVistorias);

/**
 * Caso não haja retorno algum para localização, buscamo o retorno para as vistorias de sanitário
 */
if (empty($iResultadoVistorias)) {

  $sSqlVistoriasSanitario  = " select y05_codvist,                                            ";
  $sSqlVistoriasSanitario .= "        q02_inscr,                                              ";
  $sSqlVistoriasSanitario .= "        z01_nome,                                               ";
  $sSqlVistoriasSanitario .= "        y04_msgretorno,                                         ";
  $sSqlVistoriasSanitario .= "        y05_codmsg                                              ";
  $sSqlVistoriasSanitario .= "   from vistoriaslotevist                                       ";
  $sSqlVistoriasSanitario .= "        left  join vistretornocalc on y04_codmsg  = y05_codmsg  ";
  $sSqlVistoriasSanitario .= "        inner join vistorias       on y05_codvist = y70_codvist ";
  $sSqlVistoriasSanitario .= "        inner join vistsanitario   on y74_codvist = y70_codvist ";
  $sSqlVistoriasSanitario .= "        inner join sanitario       on y74_codsani = y80_codsani ";
  $sSqlVistoriasSanitario .= "        inner join sanitarioinscr  on y18_codsani = y80_codsani ";
  $sSqlVistoriasSanitario .= "        inner join issbase         on q02_inscr   = y18_inscr   ";
  $sSqlVistoriasSanitario .= "        inner join cgm             on z01_numcgm  = q02_numcgm  ";
  $sSqlVistoriasSanitario .= "  where y05_codmsg not in (8, 9, 25)                            ";
  $sSqlVistoriasSanitario .= "     and y05_vistoriaslote = $numlote                           ";
  $sSqlVistoriasSanitario .= "     and y70_instit = ".db_getsession('DB_instit')               ;

  $rsVistorias             = db_query($sSqlVistoriasSanitario);

  if (empty($rsVistorias)) {

    echo "<script>alert('Erro ao buscar retorno do cálculo de vistorias.')</script>";
    exit;
  }
}

$iResultadoVistorias = pg_num_rows($rsVistorias);

if ($iResultadoVistorias == 0) {

  echo "<script>alert('Não existe inscrições cadastradas para as classes selecionadas.')</script>";
  exit;
}


$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($rsVistorias);$x++)
{
   db_fieldsmemory($rsVistorias,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 )
   {
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->setfillcolor(215);
      $pdf->cell(15,$alt,"Cód Vist",1,0,"C",1);
      $pdf->cell(15,$alt,"Inscrição",1,0,"C",1);
      $pdf->cell(70,$alt,"Nome",1,0,"C",1);
      $pdf->cell(90,$alt,"Status",1,1,"C",1);
      $pdf->cell(190,1,"",0,1,"C",0);

      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if(($x%2)==0){
       $cor = 245;
   }else{
       $cor = 235;
   }
   $pdf->setfillcolor($cor);
   $pdf->cell(15,$alt,$y05_codvist,0,0,"C",1);
   $pdf->cell(15,$alt,$q02_inscr,0,0,"C",1);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",1);
   $pdf->cell(90,$alt,$y04_msgretorno,0,1,"L",1);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>