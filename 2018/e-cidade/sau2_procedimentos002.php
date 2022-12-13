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
parse_str( $_SERVER['QUERY_STRING'] );

$clprontproced = new cl_prontproced;
$clprontproced->rotulo->label();

$unidade      = str_replace( "X", ",", $unidades );
$data1        = str_replace( "X", "-", $data1 );
$data2        = str_replace( "X", "-", $data2 );
$procedimento = isset( $procedimento ) && !empty( $procedimento ) ? $procedimento : '';

if( $procedimento != "" ) {
  $sql_proc = " AND sau_procedimento.sd63_c_procedimento = '{$procedimento}'";
} else {
  $sql_proc = "";
}

$sql  = "SELECT count(*) as quantidade, ";
$sql .= "       sd63_c_procedimento, ";
$sql .= "       sd63_c_nome, ";
$sql .= "       db_depart.coddepto, ";
$sql .= "       db_depart.descrdepto ";
$sql .= "  FROM prontproced ";
$sql .= "       inner join sau_procedimento on prontproced.sd29_i_procedimento = sau_procedimento.sd63_i_codigo ";
$sql .= "       inner join prontuarios      on prontuarios.sd24_i_codigo       = prontproced.sd29_i_prontuario ";
$sql .= "       left  join db_depart        on db_depart.coddepto              = prontuarios.sd24_i_unidade ";
$sql .= " WHERE prontproced.sd29_d_data BETWEEN '{$data1}' and '{$data2}' ";
$sql .= "   AND prontuarios.sd24_i_unidade in({$unidade}) ";
$sql .= " {$sql_proc} ";
$sql .= " GROUP BY sd63_c_procedimento,sd63_c_nome,db_depart.coddepto,db_depart.descrdepto ";
$sql .= " ORDER BY db_depart.coddepto,sd63_c_nome";

$result = db_query( $sql );
$linhas = pg_num_rows( $result );

if( $linhas == 0 ) {

  echo "<table width='100%'>
          <tr>
            <td align='center'>
              <font color='#FF0000' face='arial'>
                <b>Nenhum Registro para o Relatório
                  <br>
                  <input type='button' value='Fechar' onclick='window.close()'>
                </b>
              </font>
            </td>
          </tr>
        </table>";
  exit;
}

$pdf = new PDF();
$pdf->Open(); 
$pdf->AliasNbPages(); 
$head1  = "Relatório dos Procedimentos";
$head2  = "Período:" . substr( $data1, 8, 2 ) . "/" . substr( $data1, 5, 2 ) . "/" . substr( $data1, 0, 4 ) . " A ";
$head2 .= substr( $data2, 8, 2 ) . "/" . substr( $data2, 5, 2 ) . "/" . substr( $data2, 0, 4 );

if( $procedimento != "" ) {
  $h_proc = $procedimento;
} else {
  $h_proc = "TODOS";
}

$head4 = "Procedimento: {$h_proc}";
$pdf->addpage();

$pri     = true;
$s_total = 0;
$unid    = "";
$cor1    = 0;
$cor2    = 1;
$cor     = "";

for( $i = 0; $i < $linhas; $i++ ) {

  db_fieldsmemory( $result, $i );

  if( $unid != $coddepto ) {

    $pdf->setfillcolor(180);
    $pdf->setfont( 'arial', 'b', 8 );

    $pdf->cell( 190, 4, "Unidade: {$coddepto} - {$descrdepto}", 1, 1, "L", 1 );
    $pdf->cell( 190, 1, "",                                     0, 1, "L", 0 );

    $pdf->setfont( 'arial', '', 7 );
    $pdf->setfillcolor(230);
    $unid = $coddepto;
  }

  $pdf->setfont( 'arial', 'b', 8 );
  $pdf->cell(   5, 4, "",                                                          0, 0, "L", 0);
  $pdf->cell( 160, 4, "Procedimento: {$sd63_c_procedimento} - {$sd63_c_nome}", "BTL", 0, "L", 1);
  $pdf->cell(  20, 4, "Total: {$quantidade}",                                  "BTR", 0, "L", 1);
  $pdf->cell(   5, 4, "",                                                          0, 1, "L", 0);

  $pdf->setfont( 'arial', '', 7 );
  $pdf->cell(  5, 4, "",                0, 0, "L", 0 );
  $pdf->cell( 10, 4, "",                0, 0, "L", 0 );
  $pdf->cell( 25, 4, "N° Atendimento:", 0, 0, "L", 0 );
  $pdf->cell( 20, 4, "Data:",           0, 0, "L", 0 );
  $pdf->cell( 65, 4, "Paciente:",       0, 0, "L", 0 );
  $pdf->cell( 60, 4, "Médico:",         0, 0, "L", 0 );
  $pdf->cell(  5, 4, "",                0, 1, "L", 0 );

  $s_total += $quantidade;

  $sql1  = "SELECT prontproced.sd29_d_data,";
  $sql1 .= "       case";
  $sql1 .= "         when cgm.z01_numcgm is null";
  $sql1 .= "              then cgs_und.z01_v_nome";
  $sql1 .= "              else cgm.z01_nome";
  $sql1 .= "          end as nomepaciente,";
  $sql1 .= "       cgmmedico.z01_nome as nomemedico,";
  $sql1 .= "       prontuarios.sd24_i_ano,";
  $sql1 .= "       prontuarios.sd24_i_mes,";
  $sql1 .= "       prontuarios.sd24_i_seq";
  $sql1 .= "  FROM prontproced";
  $sql1 .= "       inner join sau_procedimento on prontproced.sd29_i_procedimento = sau_procedimento.sd63_i_codigo";
  $sql1 .= "       inner join prontuarios      on prontuarios.sd24_i_codigo       = prontproced.sd29_i_prontuario";
  $sql1 .= "       inner join cgs              on cgs.z01_i_numcgs                = prontuarios.sd24_i_numcgs";
  $sql1 .= "       left  join cgs_cgm          on cgs_cgm.z01_i_cgscgm            = cgs.z01_i_numcgs";
  $sql1 .= "       left  join cgm              on cgm.z01_numcgm                  = cgs_cgm.z01_i_numcgm";
  $sql1 .= "       left  join cgs_und          on cgs_und.z01_i_cgsund            = cgs.z01_i_numcgs";
  $sql1 .= "       inner join especmedico      on especmedico.sd27_i_codigo       = sd29_i_profissional";
  $sql1 .= "       inner join unidademedicos   on sd04_i_codigo                   = especmedico.sd27_i_undmed";
  $sql1 .= "       inner join medicos          on medicos.sd03_i_codigo           = unidademedicos.sd04_i_medico";
  $sql1 .= "       inner join cgm as cgmmedico on cgmmedico.z01_numcgm            = medicos.sd03_i_cgm";
  $sql1 .= " WHERE prontproced.sd29_d_data BETWEEN '{$data1}' and '{$data2}'";
  $sql1 .= "   AND prontuarios.sd24_i_unidade in({$coddepto})";
  $sql1 .= "   AND sau_procedimento.sd63_c_procedimento = '{$sd63_c_procedimento}'";
  $sql1 .= " ORDER BY prontproced.sd29_d_data desc";

  $result1 = db_query( $sql1 );
  $linhas1 = pg_num_rows( $result1 );

  $cont = 0;
  $cor1 = 0;
  $cor2 = 1;
  $cor  = "";

  for( $x = 0; $x < $linhas1; $x++ ) {

    db_fieldsmemory( $result1, $x );

    if( $cor == $cor1 ) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }

    if( ( $pdf->gety() > $pdf->h - 30 ) ) {

      if( $cont == 0 ) {

        $pdf->setfillcolor(255);
        $pdf->rect( 10, $pdf->getY() - 8, 190, 10, 'F' );
        $pdf->setfillcolor( 200 );
      }

      $pdf->addpage();
      $pdf->setfillcolor(180);
      $pdf->setfont( 'arial', 'b', 8 );

      $pdf->cell( 190, 4, "Unidade: {$coddepto} - {$descrdepto}", 1, 1, "L", 1 );
      $pdf->cell( 190, 1, "",                                     0, 1, "L", 0 );

      $pdf->setfont( 'arial', '', 7 );
      $pdf->setfillcolor( 230 );
      $pdf->setfont( 'arial', 'b', 8 );

      $pdf->cell(   5, 4, "",                                                          0, 0, "L", 0 );
      $pdf->cell( 160, 4, "Procedimento: {$sd63_c_procedimento} - {$sd63_c_nome}", "BTL", 0, "L", 1 );
      $pdf->cell(  20, 4, "Total: {$quantidade}",                                  "BTR", 0, "L", 1 );
      $pdf->cell(   5, 4, "",                                                          0, 1, "L", 0 );

      $pdf->setfont( 'arial', '', 7 );

      $pdf->cell(  5, 4, "",                0, 0, "L", 0 );
      $pdf->cell( 10, 4, "",                0, 0, "L", 0 );
      $pdf->cell( 25, 4, "N° Atendimento:", 0, 0, "L", 0 );
      $pdf->cell( 20, 4, "Data:",           0, 0, "L", 0 );
      $pdf->cell( 65, 4, "Paciente:",       0, 0, "L", 0 );
      $pdf->cell( 60, 4, "Médico:",         0, 0, "L", 0 );
      $pdf->cell(  5, 4, "",                0, 1, "L", 0 );
    }

    $cont++;
    $pdf->setfillcolor( 240 );

    $sNumeroAtendimento  = "{$sd24_i_ano} - " . str_pad( $sd24_i_mes, 2, 0, STR_PAD_LEFT ) . " - ";
    $sNumeroAtendimento .= str_pad( $sd24_i_seq, 6, 0, STR_PAD_LEFT );

    $pdf->cell(  5, 4, "",                               0, 0, "L", 0 );
    $pdf->cell( 10, 4, $cont,                            0, 0, "L", $cor );
    $pdf->cell( 25, 4, $sNumeroAtendimento,              0, 0, "L", $cor );
    $pdf->cell( 20, 4, db_formatar( $sd29_d_data, 'd' ), 0, 0, "L", $cor );
    $pdf->cell( 65, 4, $nomepaciente,                    0, 0, "L", $cor );
    $pdf->cell( 60, 4, $nomemedico,                      0, 0, "L", $cor );
    $pdf->cell(  5, 4, "",                               0, 1, "L", 0 );
  }
}

$pdf->setfont( 'arial', 'b', 9 );
$pdf->cell( 190, 6, "Total Geral de Procedimentos: {$s_total}", 1, 1, "R", 0 );
$pdf->Output();