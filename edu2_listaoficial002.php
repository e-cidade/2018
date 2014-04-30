<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("fpdf151/pdfwebseller.php");
require_once("libs/db_utils.php");
require_once("classes/db_matricula_classe.php");
require_once("classes/db_calendario_classe.php");
require_once("classes/db_edu_parametros_classe.php");
require_once("classes/db_regenteconselho_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_escola_classe.php");

$oDaoMatricula       = db_utils::getdao('matricula');
$oDaoCalendario      = db_utils::getdao('calendario');
$oDaoEduParametros   = db_utils::getdao('edu_parametros');
$oDaoRegenteConselho = db_utils::getdao('regenteconselho');
$oDaoTurma           = db_utils::getdao('turma');
$oDaoEscola          = db_utils::getdao('escola');

$iEscola             = db_getsession("DB_coddepto");

$sCampos  = "distinct                                                              \n";
$sCampos .= "ed57_i_codigo, ed57_c_descr, ed29_i_codigo,                           \n";
$sCampos .= "ed29_c_descr, ed52_c_descr, ed11_c_descr, ed15_c_nome, ed223_i_serie  \n";

$sSqlTurmaSerie      = $oDaoTurma->sql_query_turmaserie("", 
                                                        $sCampos, 
                                                        "ed57_c_descr", 
                                                        " ed220_i_codigo in ($turmas)"
                                                       );
$rsTurmaSerie        = $oDaoTurma->sql_record($sSqlTurmaSerie);

if ($oDaoTurma->numrows == 0) { ?>
  
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

<?
  exit;
}

$sSqlCalendario    = $oDaoCalendario->sql_query("",
                                                "ed52_i_ano as ano_calendario",
                                                "",
                                                " ed52_i_codigo = $codcalendario"
                                               );
$rsCalendario      = $oDaoCalendario->sql_record($sSqlCalendario);
$oDadosCalendario  = db_utils::fieldsmemory($rsCalendario, 0);

$sSqlEduParametros = $oDaoEduParametros->sql_query("",
                                                   "ed233_c_database",
                                                   "",
                                                   " ed233_i_escola = $iEscola"
                                                  );
$rsEduParametros   = $oDaoEduParametros->sql_record($sSqlEduParametros);

if ($oDaoEduParametros->numrows > 0) {
 
  $oDadosEduParametros = db_utils::fieldsmemory($rsEduParametros, 0);
 
  if (!strstr($oDadosEduParametros->ed233_c_database, "/")) {

  ?>

  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
            deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2)<br><br>
            Valor atual do parâmetro: 
            <?=trim($oDadosEduParametros->ed233_c_database) == "" ? "Não informado" : 
                    $oDadosEduParametros->ed233_c_database 
            ?> <br><br></b>
          <input type='button' value='Fechar' onclick='window.close()'>
        </font>
      </td>
    </tr>
  </table>
  
  <?

    exit;
 
  }

  $database  = explode("/", $oDadosEduParametros->ed233_c_database);
  $dia_database = $database[0];
  $mes_database = $database[1];
  
  if (@!checkdate($mes_database, $dia_database, $oDadosCalendario->ano_calendario)) {
  
  ?>
  
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Parâmetro Data Base para Cálculo da Idade (Procedimentos->Parâmetros)<br>
            deve estar no formato dd/mm ou d/m (Exemplo: 02/02 ou 2/2) e deve ser uma data válida.<br><br>
            Valor atual do parâmetro: <?=$oDadosEduParametros->ed233_c_database?><br>
            Data Base para Cálculo Idade: 
            <?=$dia_database."/".$mes_database."/".$oDadosCalendario->ano_calendario?> 
            (Data Inválida) <br><br></b>
          <input type='button' value='Fechar' onclick='window.close()'>
        </font>
      </td>
    </tr>
  </table>
  
  <?
  
    exit;
 
  }  

  $databasecalc = $oDadosCalendario->ano_calendario."-".(strlen($mes_database) == 1 ? "0" . 
                  $mes_database : $mes_database)."-".(strlen($dia_database) == 1 ? "0" . 
                  $dia_database : $dia_database);

} else {
  $databasecalc = $oDadosCalendario->ano_calendario."-12-31";
}

$campos = str_replace(chr(92), "", $campos);
$campos = str_replace("fc_idade()", "fc_idade(ed47_d_nasc,'$databasecalc'::date)", $campos);
$campos = str_replace("fc_idade_mes()", "fc_idade_anomesdia(ed47_d_nasc,'$databasecalc')", $campos);
$campos = str_replace("fc_idade_dia()", "fc_idade_anomesdia(ed47_d_nasc,'$databasecalc')", $campos);

$oPdf   = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$aMeses           = array("JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ");
$aCamposCabecalho = explode("|", $cabecalho);
$aCamposLargura   = explode("|", $colunas);
$aCamposAlinha    = explode("|", $alinhamento);
$iLinhas          = $oDaoTurma->numrows;

for ($iContFor = 0; $iContFor < $iLinhas; $iContFor++) {

  $oDadosTurmaSerie    = db_utils::fieldsmemory($rsTurmaSerie, $iContFor);

  $sSqlRegenteConselho = $oDaoRegenteConselho->sql_query("",
                                                         "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome 
                                                          else cgmcgm.z01_nome end as regente",
                                                         "",
                                                         " ed235_i_turma = $oDadosTurmaSerie->ed57_i_codigo "
                                                        );
  $rsRegenteConselho   = $oDaoRegenteConselho->sql_record($sSqlRegenteConselho);

  if($oDaoRegenteConselho->numrows > 0) {

    db_fieldsmemory($rsRegenteConselho,0);
  
  } else {
    $regente = "";
  }

  $oPdf->setfillcolor(223);
  $head1 = $titulorel == "" ? "LISTA OFICIAL DAS TURMAS" : $titulorel;
  $head2 = "Turma: $oDadosTurmaSerie->ed57_c_descr";
  $head3 = "Curso: $oDadosTurmaSerie->ed29_i_codigo - $oDadosTurmaSerie->ed29_c_descr";
  $head4 = "Calendário: $oDadosTurmaSerie->ed52_c_descr";
  $head5 = "Etapa: $oDadosTurmaSerie->ed11_c_descr";
  $head6 = "Turno: $oDadosTurmaSerie->ed15_c_nome";
  
  if ($nomeregente == "S") {
    $head7 = "Regente: $regente";
  } else {
    $head7 = "";
  }

  $oPdf->addpage($orientacao);
  $oPdf->ln(5);
  $oPdf->setfont('arial', 'b', $tamfonte);
  $somacampos = 0;

  for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

    if ($iContFor1 == (count($aCamposCabecalho)-1)) {
      $next = 1;
    } else {
      $next = 0;
    }

    if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

      for ($iContFor2 = 0; $iContFor2 < 12; $iContFor2++) {

        if ($iContFor2 < 11) {

          $next_mes = 0;
        
        } else {
          $next_mes = $next;
        }

        $oPdf->cell($aCamposLargura[$iContFor1]/12, 4, $aMeses[$iContFor2], 1, $next_mes, "C", 0);
      
      }

    } else {
      $oPdf->cell($aCamposLargura[$iContFor1], 4, $aCamposCabecalho[$iContFor1], 1, $next, "C", 0);
    }
    
    $somacampos += $aCamposLargura[$iContFor1];
  
  }

  $condicao = "";
  if ($active == "SIM") {
    $condicao=" AND ed60_c_situacao = 'MATRICULADO' ";
  }
  
  if ($trocaTurma == 1) {
    $condicao .= " AND ed60_c_situacao != 'TROCA DE TURMA' ";
  }
  
  $sSqlMatricula = $oDaoMatricula->sql_query_naturalidade_aluno("",
                                                                $campos,
                                                                $ordenacao.",ed60_c_ativa",
                                                                " ed60_i_turma = $oDadosTurmaSerie->ed57_i_codigo 
                                                                AND ed221_i_serie = $oDadosTurmaSerie->ed223_i_serie $condicao"
                                                               );
  $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);
  $iLinha2       = $oDaoMatricula->numrows;
  
  if ($iLinha2 == 0) {
    die($sql2." ->>>>".pg_errormessage());
  }
  
  $limite = $orientacao == "P" ? 55 : 34;
  $cont = 0;

  for ($iContFor3 = 0; $iContFor3 < $iLinha2; $iContFor3++) {
    
    for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

      if ($iContFor1 == (count($aCamposCabecalho)-1)) {
        $next = 1;
      } else {
        $next = 0;
      }

      if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

        for ($iContFor2 = 1; $iContFor2 <= 12; $iContFor2++) {

          if ($iContFor2 < 12) {
            $next_mes = 0;
          } else {
            $next_mes = $next;
          }
          
          $oPdf->cell($aCamposLargura[$iContFor1]/12, 4, "", 1, $next_mes, "C", 0);
        
        }

      } else if (pg_field_name($rsMatricula, $iContFor1) == "ed47_certidaomatricula") {

          $iMatricula = pg_result($rsMatricula, $iContFor3, $iContFor1);
          $sMatricula = substr($iMatricula, 0, 6)." ".substr($iMatricula, 6, 2)." ".
                        substr($iMatricula, 8, 2)." ".substr($iMatricula, 10, 4)." ".
                        substr($iMatricula, 14, 1)." ".substr($iMatricula, 15, 5)." ".
                        substr($iMatricula, 20, 3)." ".substr($iMatricula, 23, 7)." ".
                        substr($iMatricula, 30, 2);
          $oPdf->cell($aCamposLargura[$iContFor1], 4, $sMatricula, 1, $next, $aCamposAlinha[$iContFor1], 0);

        } else if (pg_field_name($rsMatricula, $iContFor1) == "anomes") {

          $sMes = pg_result($rsMatricula, $iContFor3, $iContFor1);
          $aMes = explode(",",$sMes);
          $iMes = str_replace("meses"," ",$aMes[1]);
          $oPdf->cell($aCamposLargura[$iContFor1], 4, $iMes, 1, $next, $aCamposAlinha[$iContFor1], 0);

        } else if (pg_field_name($rsMatricula, $iContFor1) == "idadedia") {

          $sDia = pg_result($rsMatricula, $iContFor3, $iContFor1);      
          $aDia = explode(",",$sDia);
          $iDia = str_replace("dias"," ",$aDia[2]);
          $oPdf->cell($aCamposLargura[$iContFor1], 4, $iDia, 1, $next, $aCamposAlinha[$iContFor1], 0);

        }   else {
          
          $oPdf->cell($aCamposLargura[$iContFor1], 4, 
                      (pg_field_type($rsMatricula, $iContFor1) == "date" ? 
                      db_formatar(pg_result($rsMatricula, $iContFor3, $iContFor1), 'd') : 
                      pg_result($rsMatricula, $iContFor3, $iContFor1)), 1, $next, $aCamposAlinha[$iContFor1], 0
                     );

        }
        
      }  

    
    if ($limite == $cont) {
      
      $oPdf->cell($somacampos, 4, "* Aluno repetindo a Etapa", 1, 1, "L", 0);
      $oPdf->line(10, 44, $somacampos + 10, 44);
      $oPdf->addpage($orientacao);
      $oPdf->ln(5);
      $oPdf->setfont('arial', 'b', $tamfonte);
      
      for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

        if ($iContFor1 == (count($aCamposCabecalho)-1)) {
          $next = 1;
        } else {
          $next = 0;
        }

        if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

          for ($iContFor2 = 0; $iContFor2 < 12; $iContFor2++) {

            if ($iContFor2 < 11) {
              $next_mes = 0;
            } else {
              $next_mes = $next;
            }
            $oPdf->cell($aCamposLargura[$iContFor1]/12, 4, $aMeses[$iContFor2], 1, $next_mes, "C", 0);
          }
          
        } else {
          $oPdf->cell($aCamposLargura[$iContFor1], 4, $aCamposCabecalho[$iContFor1], 1, $next, "C", 0);
        }
        
      }

      $cont = -1;
  
    }

    $cont++;
 
  }

  $comeco = $cont-1;
  
  for ($iContFor3 = $comeco; $iContFor3 < $limite; $iContFor3++) {

    for ($iContFor1 = 0; $iContFor1 < count($aCamposCabecalho); $iContFor1++) {

      if ($iContFor1 == (count($aCamposCabecalho)-1)) {
        $next = 1;
      } else {
        $next = 0;
      }
      
      if (trim($aCamposCabecalho[$iContFor1]) == "Meses") {

        for ($iContFor2 = 1; $iContFor2 <= 12; $iContFor2++) {

          if ($iContFor2 < 12) {
            $next_mes = 0;
          } else {
            $next_mes = $next;
          }

          $oPdf->cell($aCamposLargura[$iContFor1]/12, 4, "", "LR", $next_mes, "C", 0);

        }
      } else {
        $oPdf->cell($aCamposLargura[$iContFor1], 4, "", "LR", $next, "C", 0);
      }
    }
  }

  $oPdf->cell($somacampos, 5, "* Aluno repetindo a Etapa", 1, 1, "L", 0);
  $oPdf->line(10, 44, $somacampos + 10, 44);

}

$oPdf->Output();

?>