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

require_once ("fpdf151/pdfwebseller.php");

$clserie      = new cl_serie;
$clcalendario = new cl_calendario;

$escola = db_getsession("DB_coddepto");
$head2  = "";
$head3  = "";
$head4  = "";

if ( $serieescolhida != 0 ) {
	
  $where   = " AND ed11_i_codigo = {$serieescolhida}";
  $result3 = $clserie->sql_record( $clserie->sql_query( "", "ed11_c_descr", "", "ed11_i_codigo = {$serieescolhida}" ) );
  db_fieldsmemory( $result3, 0 );
  $head3 = " Etapa: $ed11_c_descr";
} else {
	
  $where = "";
  $head3 = "Etapa: TODAS";
}

if ( $calendario != 0 ) {

  $sCamposCalendario = "ed52_c_descr, ed52_i_calendant as calendant";
  $sSqlCalendario    = $clcalendario->sql_query( "", $sCamposCalendario, "", "ed52_i_codigo = {$calendario}" );
  $result4           = $clcalendario->sql_record( $sSqlCalendario );
  db_fieldsmemory( $result4, 0 );

  $where_cal        = "alunocurso.ed56_i_calendario = {$calendant} AND";
  $where_calendario = "alunocurso.ed56_i_calendario = {$calendario} AND";
  $cabec            = "$ed52_c_descr";	
} else {
	
  $where_cal        = "";
  $where_calendario = "";
  $cabec            = "Calendários: TODOS";
}

$sCampos  = "ed47_i_codigo, ed47_v_nome, ed47_v_ender, ed47_c_numero, ed47_v_bairro, ed56_c_situacao, ed11_c_descr";
$sCampos .= ", ed52_c_descr";
$sql      = " SELECT {$sCampos}";
$sql     .= "   FROM alunocurso ";
$sql     .= "        inner join escola      on escola.ed18_i_codigo          = alunocurso.ed56_i_escola ";
$sql     .= "        inner join aluno       on aluno.ed47_i_codigo           = alunocurso.ed56_i_aluno ";
$sql     .= "        inner join calendario  on calendario.ed52_i_codigo      = alunocurso.ed56_i_calendario ";
$sql     .= "        inner join base        on base.ed31_i_codigo            = alunocurso.ed56_i_base ";
$sql     .= "        inner join alunopossib on alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo ";
$sql     .= "        inner join cursoedu    on cursoedu.ed29_i_codigo        = base.ed31_i_curso ";
$sql     .= "        inner join serie       on serie.ed11_i_codigo           = alunopossib.ed79_i_serie ";
$sql     .= "  WHERE alunocurso.ed56_i_escola = {$escola} ";
$sql     .= "    AND base.ed31_i_curso        = {$curso} ";
$sql     .= "    $where ";
$sql     .= "    AND (   ({$where_calendario} ";
$sql     .= "            alunocurso.ed56_c_situacao='CANDIDATO') ";
$sql     .= "         OR ($where_cal ";
$sql     .= "         ( alunocurso.ed56_c_situacao='APROVADO' OR alunocurso.ed56_c_situacao='REPETENTE'))) ";
$sql     .= "  ORDER BY ed52_i_codigo, ed56_c_situacao, ed11_i_sequencia ";
$result   = db_query( $sql );
$linhas   = pg_numrows( $result );

if ( $linhas == 0 ) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhum registro encontrado.<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?
  exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

$head1 = "RELATÓRIO DE LISTA DE CANDIDATOS";
$head2 = $cabec;

$pdf->setfillcolor(223);
$pdf->addpage('L');

$ensino = "";
$serie  = "";
$turma  = "";
$cor1   = "0";
$cor2   = "1";
$cor    = "";
$iCont  = 0;

$pdf->setfillcolor(0);
$pdf->setfillcolor(240);
$pdf->setfont( 'arial', 'b', 8 );

$pdf->cell( 20, 4, "Código Aluno", 1, 0, "L", 1 );
$pdf->cell( 60, 4, "Nome",         1, 0, "C", 1 );
$pdf->cell( 60, 4, "Endereço",     1, 0, "C", 1 );
$pdf->cell( 60, 4, "Bairro",       1, 0, "C", 1 );
$pdf->cell( 30, 4, "Situação",     1, 0, "C", 1 );
$pdf->cell( 15, 4, "Etapa",        1, 0, "C", 1 );
$pdf->cell( 30, 4, "Calendário",   1, 1, "C", 1 );

$pdf->setfillcolor(0);

for ( $c = 0; $c < $linhas; $c++ ) {
	
  db_fieldsmemory( $result, $c );

  if ( $iCont == 35 ) {
  	
    $pdf->setfillcolor(0);
    $pdf->setfillcolor(240);
    $pdf->setfont( 'arial', 'b', 8 );

    $pdf->cell( 20, 4, "Código Aluno", 1, 0, "L", 1 );
    $pdf->cell( 60, 4, "Nome",         1, 0, "C", 1 );
    $pdf->cell( 60, 4, "Endereço",     1, 0, "C", 1 );
    $pdf->cell( 60, 4, "Bairro",       1, 0, "C", 1 );
    $pdf->cell( 30, 4, "Situação",     1, 0, "C", 1 );
    $pdf->cell( 15, 4, "Etapa",        1, 0, "C", 1 );
    $pdf->cell( 30, 4, "Calendário",   1, 1, "C", 1 );

    $pdf->setfillcolor(0);
    $iCont++;
  }
 
  if ( $cor == $cor1 ) {
    $cor = $cor2;
  } else {
    $cor = $cor1;
  }

  $pdf->setfillcolor(0);
  $pdf->setfillcolor(215);
  $pdf->setfont( 'arial', '', 8 );

  $pdf->cell( 20, 4, $ed47_i_codigo,                  1, 0, "R", $cor );
  $pdf->cell( 60, 4, $ed47_v_nome,                    1, 0, "L", $cor );
  $pdf->cell( 60, 4, $ed47_v_ender."".$ed47_c_numero, 1, 0, "L", $cor );
  $pdf->cell( 60, 4, $ed47_v_bairro,                  1, 0, "L", $cor );
  $pdf->cell( 30, 4, $ed56_c_situacao,                1, 0, "L", $cor );
  $pdf->cell( 15, 4, $ed11_c_descr,                   1, 0, "L", $cor );
  $pdf->cell( 30, 4, $ed52_c_descr,                   1, 1, "L", $cor );
  $pdf->setfillcolor(0);
}

$pdf->setfillcolor(0);
$pdf->setfillcolor(240);
$pdf->setfont( 'arial', 'b', 8 );

$pdf->cell( 275, 4, "Total de candidato(s):".$cont,                   1, 1, "L", 1 );
$pdf->cell( 275, 4, "Total de vagas disponíveis:".$iVagasDisponiveis, 1, 1, "L", 1 );
$pdf->Output();
?>