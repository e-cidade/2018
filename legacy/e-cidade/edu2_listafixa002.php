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

require_once(modification("fpdf151/pdfwebseller.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("classes/db_matricula_classe.php"));
require_once(modification("classes/db_alunonecessidade_classe.php"));
require_once(modification("classes/db_regenteconselho_classe.php"));
require_once(modification("classes/db_turma_classe.php"));
require_once(modification("classes/db_escola_classe.php"));
require_once(modification("classes/db_edu_parametros_classe.php"));

$clmatricula        = new cl_matricula;
$clregenteconselho  = new cl_regenteconselho;
$clturma            = new cl_turma;
$clescola           = new cl_escola;
$cledu_parametros   = new cl_edu_parametros;
$clalunonecessidade = new cl_alunonecessidade;
$escola             = db_getsession("DB_coddepto");

$sCampos  = "distinct                                   \n";
$sCampos .= "ed52_i_ano, ed57_c_descr, ed29_i_codigo,   \n";
$sCampos .= "ed29_c_descr, ed52_c_descr, ed11_c_descr,  \n";
$sCampos .= "ed15_c_nome, ed57_i_codigo, ed223_i_serie  \n";

$sSql   = $clturma->sql_query_turmaserie("", $sCampos, "ed57_c_descr", " ed220_i_codigo in ($turmas)");
$result = $clturma->sql_record($sSql);

if ($clturma->numrows == 0) {?>

  <table width='100%'>
   <tr>
    <td align='center'>
     <font color='#FF0000' face='arial'>
      <b>Nenhuma turma para o curso selecionado<br>
      <input type='button' value='Fechar' onclick='window.close()'></b>
     </font>
    </td>
   </tr>
  </table>
  <?php
  exit;

}

$ano_calendario    = pg_result($result,0,'ed52_i_ano');
$result_parametros = $cledu_parametros->sql_record($cledu_parametros->sql_query("",
                                                                                "ed233_c_database,ed233_c_limitemov",
                                                                                "",
                                                                                " ed233_i_escola = $escola"
                                                                               )
                                                  );

if ($cledu_parametros->numrows > 0) {

  db_fieldsmemory($result_parametros,0);
  if (!strstr($ed233_c_database,"/") || !strstr($ed233_c_limitemov,"/")) {

   ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetros Dia/Mês Limite da Movimentação e Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
           devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
           Valor atual do parâmetro Dia/Mês Limite da Movimentação:
           <?=trim($ed233_c_limitemov)==""?"Não informado":$ed233_c_limitemov?><br>
           Valor atual do parâmetro Data Base para Cálculo da Idade:
           <?=trim($ed233_c_database)==""?"Não informado":$ed233_c_database?><br><br></b>
          <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
   <?
   exit;

  }


  $database      = explode("/",$ed233_c_database);
  $limitemov     = explode("/",$ed233_c_limitemov);
  $dia_database  = $database[0];
  $mes_database  = $database[1];
  $dia_limitemov = $limitemov[0];
  $mes_limitemov = $limitemov[1];

  if (   !checkdate($mes_database, $dia_database, $ano_calendario)
      || !checkdate($mes_limitemov, $dia_limitemov, $ano_calendario)) {

    ?>
    <table width='100%'>
     <tr>
      <td align='center'>
       <font color='#FF0000' face='arial'>
        <b>Parâmetros Dia/Mês Limite da Movimentação e Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
           devem estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e devem ser uma data válida.<br><br>
           Valor atual do parâmetro Dia/Mês Limite da Movimentação:
           <?=trim($ed233_c_limitemov)==""?"Não informado":$ed233_c_limitemov?><br>
           Valor atual do parâmetro Data Base para Cálculo da Idade:
           <?=trim($ed233_c_database)==""?"Não informado":$ed233_c_database?><br><br>
           Data Limite da Movimentação: <?=$dia_limitemov."/".$mes_limitemov."/".$ano_calendario?>
           <?=@!checkdate($mes_limitemov,$dia_limitemov,$ano_calendario)?"(Data Inválida)":"(Data Válida)"?><br>
           Data Base para Cálculo Idade: <?=$dia_database."/".$mes_database."/".$ano_calendario?>
           <?=@!checkdate($mes_database,$dia_database,$ano_calendario)?"(Data Inválida)":"(Data Válida)"?><br><br></b>
        <input type='button' value='Fechar' onclick='window.close()'>
       </font>
      </td>
     </tr>
    </table>
    <?
    exit;

  }

  $databasecalc   = $ano_calendario."-".(strlen($mes_database)==1?"0".$mes_database:$mes_database);
  $databasecalc  .= "-".(strlen($dia_database)==1?"0".$dia_database:$dia_database);
  $datalimitemov  = $ano_calendario."-".(strlen($mes_limitemov)==1?"0".$mes_limitemov:$mes_limitemov);
  $datalimitemov .= "-".(strlen($dia_limitemov)==1?"0".$dia_limitemov:$dia_limitemov);

} else {

  $databasecalc  = $ano_calendario."-12-31";
  $datalimitemov = $ano_calendario."-01-01";

}

function Entrada($situacao,$matricula) {

  $sql  = " SELECT ed60_c_tipo ";
  $sql .= "  FROM matricula ";
  $sql .= "  WHERE ed60_i_codigo = $matricula ";
  $result = db_query($sql);
  $tipo = pg_result($result,0,0);

  $retorno = "R";
  if ($tipo == "N") {
    $retorno = "M";
  }
  return $retorno;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$linhas = $clturma->numrows;

for ($x = 0; $x < $linhas; $x++) {

  db_fieldsmemory($result,$x);
  $pdf->setfillcolor(223);
  $head1 = "LISTA OFICIAL DAS TURMAS";
  $head2 = "Turma: $ed57_c_descr";
  $head3 = "Curso: $ed29_i_codigo - $ed29_c_descr";
  $head4 = "Calendário: $ed52_c_descr";
  $head5 = "Etapa: $ed11_c_descr";
  $head6 = "Turno: $ed15_c_nome";
  $head7 = "Dia / Mês Limite Movimentação: $ed233_c_limitemov";
  $pdf->setfont('arial','b',8);
  $limite     = 55;
  $somacampos = 0;
  $cont       = 0;

  $lPrimeiraPagina = true;
  if ($active == "SIM") {
    $condicao=" AND ed60_c_situacao='MATRICULADO'";
  } else {
    $condicao = "";
  }
  $campodtsaida  = "to_char(ed60_d_datasaida,'DD/MM/YYYY') as datasaida, ed47_i_codigo, ed47_v_nome, ed47_d_nasc,";
  $campodtsaida .= " ed47_v_sexo, ed60_c_situacao, ed60_d_datamatricula, ed60_i_codigo, ed60_c_tipo,";
  $campodtsaida .= "fc_idade(ed47_d_nasc,'$databasecalc'::date) as idadealuno";
  $sWhere        = " ed60_i_turma = $ed57_i_codigo AND ed221_i_serie = $ed223_i_serie $condicao";
  $result2       = $clmatricula->sql_record($clmatricula->sql_query("",
                                                                    $campodtsaida,
                                                                    "ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa",
                                                                    $sWhere
                                                                   )
                                           );

  for ($p = 0; $p < $clmatricula->numrows; $p++) {

    db_fieldsmemory($result2, $p);

    if ($trocaTurma == 1 && $ed60_c_situacao == 'TROCA DE TURMA') {
      continue;
    }

    if ( $lPrimeiraPagina || $pdf->GetY() >= ($pdf->h -20) ) {

      imprimeCabecalho($pdf);
      $lPrimeiraPagina = false;
    }

    $pdf->setfont('arial','',8);
    $pdf->cell(15,4,$ed47_i_codigo,1,0,"C",0);
    $pdf->cell(20,4,db_formatar($ed60_d_datamatricula,'d'),1,0,"C",0);
    $pdf->cell(5,4,Entrada($ed60_c_situacao,$ed60_i_codigo),1,0,"C",0);
    $pdf->cell(20,4,$datasaida,1,0,"C",0);

    if ($datasaida != "") {

      if (trim($ed60_c_situacao)=="TRANSFERIDO REDE"){
        $sit = "TR";
      } elseif (trim($ed60_c_situacao)=="TRANSFERIDO FORA"){
        $sit = "TF";
      } elseif (trim($ed60_c_situacao)=="TROCA DE MODALIDADE") {
        $sit = "TM";
      } else if (trim($ed60_c_situacao)=="MATRICULA TRANCADA") {
        $sit = "MT";
      } else if (trim($ed60_c_situacao)=="MATRICULA INDEVIDA") {
        $sit = "MI";
      } else if (trim($ed60_c_situacao)=="MATRICULA INDEFERIDA") {
        $sit = "IN";
      } else {
         $sit = substr($ed60_c_situacao,0,1);
      }
      $pdf->cell(5,4,$sit,1,0,"C",0);
    } else {
      $pdf->cell(5,4," ",1,0,"C",0);
    }

    $pdf->cell(10,4,$ed47_v_sexo,1,0,"C",0);
    $pdf->cell(85,4,$ed47_v_nome,1,0,"L",0);
    $pdf->cell(10,4,$idadealuno,1,0,"C",0);
    $pdf->cell(20,4,db_formatar($ed47_d_nasc,'d'),1,0,"C",0);
    $result21 = $clalunonecessidade->sql_record($clalunonecessidade->sql_query("",
                                                                               "ed214_i_codigo",
                                                                               "",
                                                                               "ed214_i_aluno = $ed47_i_codigo"
                                                                              )
                                               );
    $inclusao = $clalunonecessidade->numrows>0?"*":"";
    $pdf->setfont('arial','b',10);
    $pdf->cell(5,4,$inclusao,1,1,"C",0);
    $pdf->setfont('arial','',8);

    if(trim($ed60_c_situacao)=="MATRICULADO"){
      $cont++;
    }
  }

  for ($p = $clmatricula->numrows; $p < $limite; $p++) {

    $pdf->cell(15, 4, "", 1, 0, "C", 0);
    $pdf->cell(20, 4, "", 1, 0, "C", 0);
    $pdf->cell(5,  4, "", 1, 0, "C", 0);
    $pdf->cell(20, 4, "", 1, 0, "C", 0);
    $pdf->cell(5,  4, "", 1, 0, "C", 0);
    $pdf->cell(10, 4, "", 1, 0, "C", 0);
    $pdf->cell(85, 4, "", 1, 0, "C", 0);
    $pdf->cell(10, 4, "", 1, 0, "C", 0);
    $pdf->cell(20, 4, "", 1, 0, "C", 0);
    $pdf->cell(5,  4, "", 1, 1, "C", 0);

  }

  $pdf->setfont('arial','',7);
  $pdf->cell(95,4,"ALUNOS ATIVOS: " .$cont,0,0,"L",0);
  $pdf->cell(95,4,"ID = Idade no ano  I = Aluno de Inclusão",0,1,"R",0);
  $pdf->setfont('arial','',8);
  $pdf->cell(190,4,"",0,1,"L",0);
  $sCampos = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome";
  $result3 = $clregenteconselho->sql_record($clregenteconselho->sql_query("",
                                                                          $sCampos,
                                                                          "",
                                                                          "ed235_i_turma = $ed57_i_codigo"
                                                                         )
                                           );
  if ($clregenteconselho->numrows > 0) {

    db_fieldsmemory($result3,0);
    $pdf->cell(190,4,"CONSELHEIRO: ".$z01_nome,0,1,"L",0);

  } else {
    $pdf->cell(190,4,"CONSELHEIRO: _________________________________________________________________",0,1,"L",0);
  }
}

$pdf->Output();

function imprimeCabecalho($pdf) {

  $pdf->AddPage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(15,4,"Cod",1,0,"C",0);
  $pdf->cell(20,4,"Dt.Entrada",1,0,"C",0);
  $pdf->cell(5,4,"E",1,0,"C",0);
  $pdf->cell(20,4,"Dt.Saida",1,0,"C",0);
  $pdf->cell(5,4,"S",1,0,"C",0);
  $pdf->cell(10,4,"Sexo",1,0,"C",0);
  $pdf->cell(85,4,"Alunos",1,0,"C",0);
  $pdf->cell(10,4,"ID",1,0,"C",0);
  $pdf->cell(20,4,"Dt.Nasc",1,0,"C",0);
  $pdf->cell(5,4,"I",1,1,"C",0);
  $pdf->setfont('arial','',8);
}
?>