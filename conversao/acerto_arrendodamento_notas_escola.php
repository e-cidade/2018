<?php
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

ini_set("include_path", ini_get("include_path").".:../");
ini_set("display_errors", "Off");
ini_set("error_reporting", "");
require_once("../libs/db_conn.php");
require_once("../libs/db_utils.php");
require_once("../libs/db_stdlib.php");
require_once("../std/DBNumber.php");
require_once("../model/educacao/ArredondamentoNota.model.php");

/**
$DB_SERVIDOR = 'dev10';
$DB_BASE     = 'educacao_canela';
$DB_PORTA    = '5432';
$DB_USUARIO  = 'postgres';
$DB_SENHA    = '';
*/
if (!($conn = @pg_connect("host = '$DB_SERVIDOR' dbname = '$DB_BASE' port = '$DB_PORTA' user = '$DB_USUARIO' password = $DB_SENHA"))) {

  echo "Erro ao conectar com a base de dados";
  exit;
}


pg_query("select fc_startsession()");
pg_query("begin");


echo "Acertando registros da tabela Diario Resultado\n";
$sSqlResultados  = "SELECT diarioresultado.*, ed95_i_escola";
$sSqlResultados  .= "  from diario";
$sSqlResultados  .= "       inner join diarioresultado on ed73_i_diario         = ed95_i_codigo";
$sSqlResultados  .= "       inner join calendario      on ed95_i_calendario     =  ed52_i_codigo";
$sSqlResultados  .= "       inner join procresultado   on ed73_i_procresultado  = ed43_i_codigo";
$sSqlResultados  .= "       inner join formaavaliacao  on ed43_i_formaavaliacao = ed37_i_codigo";
$sSqlResultados  .= " where ed52_i_ano >= 2012";
$sSqlResultados  .= "   and trim(ed37_c_tipo) = 'NOTA'";
$sSqlResultados  .= "   AND position('.' in ed73_i_valornota::varchar ) > 0 ";

pg_query("create table w_correcao_notas_alunos_resultado as {$sSqlResultados}");

$rsResultados            = pg_query($sSqlResultados);
$iTotalLinhas            = pg_num_rows($rsResultados);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado         = db_utils::fieldsMemory($rsResultados, $i);
  if ($oDadosResultado->ed95_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed95_i_escola;

  $nNota    = ArredondamentoNota::arredondar($oDadosResultado->ed73_i_valornota, 2012);
  $sUpdate  = "update diarioresultado set ed73_i_valornota = {$nNota} ";
  $sUpdate .= " where ed73_i_codigo = {$oDadosResultado->ed73_i_codigo}";

  $rsUpdateResultado = pg_query($sUpdate);
  if (!$rsUpdateResultado) {

    pg_query("rollback");
    die(pg_last_error());
  }
}

echo "\tdiarioresulado\t\t[OK]\n";


echo "Acertando registros da tabela Diario Final\n";
$sSqlDiarioFinal  = "SELECT diariofinal.*, ed95_i_escola";
$sSqlDiarioFinal .= "  from diario";
$sSqlDiarioFinal .= "       inner join diariofinal     on ed74_i_diario             = ed95_i_codigo";
$sSqlDiarioFinal .= "       inner join calendario      on ed95_i_calendario         = ed52_i_codigo";
$sSqlDiarioFinal .= "       inner join procresultado   on ed74_i_procresultadoaprov = ed43_i_codigo";
$sSqlDiarioFinal .= "       inner join formaavaliacao  on ed43_i_formaavaliacao     = ed37_i_codigo";
$sSqlDiarioFinal .= " where ed52_i_ano >= 2012";
$sSqlDiarioFinal .= "   and trim(ed37_c_tipo) = 'NOTA'";
$sSqlDiarioFinal .= "   AND position('.' in ed74_c_valoraprov) > 0";


pg_query("create table w_correcao_notas_alunos_resultadofinal as {$sSqlDiarioFinal}");

$rsResultadosFinais      = pg_query($sSqlDiarioFinal);
$iTotalLinhas            = pg_num_rows($rsResultadosFinais);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado = db_utils::fieldsMemory($rsResultadosFinais, $i);
  if ($oDadosResultado->ed95_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed95_i_escola;

  $nNota    = ArredondamentoNota::arredondar($oDadosResultado->ed74_c_valoraprov, 2012);
  $sUpdate  = "update diariofinal set ed74_c_valoraprov = '{$nNota}'";
  $sUpdate .= " where ed74_i_codigo = {$oDadosResultado->ed74_i_codigo}";

  $rsUpdateResultadoFinal = pg_query($sUpdate);
  if (!$rsUpdateResultadoFinal) {

    echo "aqui..";
    pg_query("rollback");
    die(pg_last_error());
  }
}
echo "\tdiariofinal\t\t[OK]\n";


echo "Acertando registros da tabela histmpsdisc\n";
$sSqlHistoricoDisciplinas  = "SELECT histmpsdisc.*, ed62_i_escola";
$sSqlHistoricoDisciplinas .= "  from histmpsdisc  ";
$sSqlHistoricoDisciplinas .= "       inner join historicomps on ed65_i_historicomps = ed62_i_codigo ";
$sSqlHistoricoDisciplinas .= " where ed62_i_anoref >= 2012  ";
$sSqlHistoricoDisciplinas .= "   and position('.' in ed65_t_resultobtido) > 0 ";


pg_query("create table w_correcao_notas_alunos_historico_disciplinas as {$sSqlHistoricoDisciplinas}");

$rsHistorico             = pg_query($sSqlHistoricoDisciplinas);
$iTotalLinhas            = pg_num_rows($rsHistorico);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado         = db_utils::fieldsMemory($rsHistorico, $i);
  if ($oDadosResultado->ed62_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed62_i_escola;

  $nNota    = ArredondamentoNota::arredondar($oDadosResultado->ed65_t_resultobtido, 2012);
  $sUpdate  = "update histmpsdisc set ed65_t_resultobtido = '{$nNota}'";
  $sUpdate .= " where ed65_i_codigo = {$oDadosResultado->ed65_i_codigo}";

  $rsUpdateHistorico = pg_query($sUpdate);
  if (!$rsUpdateHistorico) {

    pg_query("rollback");
    die(pg_last_error());
  }
}
echo "\thistmpsdisc\t\t[OK]\n";

/**
 * Formata os registros
 */
echo "Acertando formatacao registros da tabela Diario Resultado\n";
$sSqlResultados  = "SELECT diarioresultado.*, ed95_i_escola";
$sSqlResultados  .= "  from diario";
$sSqlResultados  .= "       inner join diarioresultado on ed73_i_diario         = ed95_i_codigo";
$sSqlResultados  .= "       inner join calendario      on ed95_i_calendario     =  ed52_i_codigo";
$sSqlResultados  .= "       inner join procresultado   on ed73_i_procresultado  = ed43_i_codigo";
$sSqlResultados  .= "       inner join formaavaliacao  on ed43_i_formaavaliacao = ed37_i_codigo";
$sSqlResultados  .= " where ed52_i_ano >= 2012";
$sSqlResultados  .= "   and trim(ed37_c_tipo) = 'NOTA'";
$sSqlResultados  .= "   and ed73_i_valornota is not null";

$rsResultados            = pg_query($sSqlResultados);
$iTotalLinhas            = pg_num_rows($rsResultados);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado         = db_utils::fieldsMemory($rsResultados, $i);
  if ($oDadosResultado->ed95_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed95_i_escola;

  $nNota    = ArredondamentoNota::formatar($oDadosResultado->ed73_i_valornota, 2012);
  $sUpdate  = "update diarioresultado set ed73_i_valornota = {$nNota} ";
  $sUpdate .= " where ed73_i_codigo = {$oDadosResultado->ed73_i_codigo}";

  $rsUpdateResultado = pg_query($sUpdate);
  if (!$rsUpdateResultado) {

    pg_query("rollback");
    die(pg_last_error());
  }
}

echo "Acertando registros da tabela Diario Final\n";
$sSqlDiarioFinal  = "SELECT diariofinal.*, ed95_i_escola";
$sSqlDiarioFinal .= "  from diario";
$sSqlDiarioFinal .= "       inner join diariofinal     on ed74_i_diario             = ed95_i_codigo";
$sSqlDiarioFinal .= "       inner join calendario      on ed95_i_calendario         = ed52_i_codigo";
$sSqlDiarioFinal .= "       inner join procresultado   on ed74_i_procresultadoaprov = ed43_i_codigo";
$sSqlDiarioFinal .= "       inner join formaavaliacao  on ed43_i_formaavaliacao     = ed37_i_codigo";
$sSqlDiarioFinal .= " where ed52_i_ano >= 2012";
$sSqlDiarioFinal .= "   and trim(ed37_c_tipo) = 'NOTA'";
$sSqlDiarioFinal .= "   and trim(ed74_c_valoraprov) <> ''";

$rsResultadosFinais      = pg_query($sSqlDiarioFinal);
$iTotalLinhas            = pg_num_rows($rsResultadosFinais);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado = db_utils::fieldsMemory($rsResultadosFinais, $i);
  if ($oDadosResultado->ed95_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed95_i_escola;

  $nNota    = ArredondamentoNota::formatar($oDadosResultado->ed74_c_valoraprov, 2012);
  $sUpdate  = "update diariofinal set ed74_c_valoraprov = '{$nNota}'";
  $sUpdate .= " where ed74_i_codigo = {$oDadosResultado->ed74_i_codigo}";

  $rsUpdateResultadoFinal = pg_query($sUpdate);
  if (!$rsUpdateResultadoFinal) {

    echo "aqui..";
    pg_query("rollback");
    die(pg_last_error());
  }
}
echo "\tformatando dados diariofinal\t\t[OK]\n";

echo "Acertando formatacao registros da tabela histmpsdisc\n";
$sSqlHistoricoDisciplinas  = "SELECT histmpsdisc.*, ed62_i_escola";
$sSqlHistoricoDisciplinas .= "  from histmpsdisc  ";
$sSqlHistoricoDisciplinas .= "       inner join historicomps on ed65_i_historicomps = ed62_i_codigo ";
$sSqlHistoricoDisciplinas .= " where ed62_i_anoref >= 2012 ";
$sSqlHistoricoDisciplinas .= "   and trim(ed65_t_resultobtido) <> ''";

$rsHistorico             = pg_query($sSqlHistoricoDisciplinas);
$iTotalLinhas            = pg_num_rows($rsHistorico);
$_SESSION["DB_coddepto"] = '';
for ($i = 0; $i < $iTotalLinhas; $i++) {

  $oDadosResultado         = db_utils::fieldsMemory($rsHistorico, $i);
  if ($oDadosResultado->ed62_i_escola != $_SESSION["DB_coddepto"]) {
    ArredondamentoNota::destroy();
  }
  $_SESSION["DB_coddepto"] = $oDadosResultado->ed62_i_escola;

  $nNota    = ArredondamentoNota::formatar($oDadosResultado->ed65_t_resultobtido, 2012);
  $sUpdate  = "update histmpsdisc set ed65_t_resultobtido = '{$nNota}'";
  $sUpdate .= " where ed65_i_codigo = {$oDadosResultado->ed65_i_codigo}";

  $rsUpdateHistorico = pg_query($sUpdate);
  if (!$rsUpdateHistorico) {

    pg_query("rollback");
    die(pg_last_error());
  }
}
echo "\thistmpsdisc\t\t[OK]\n";
pg_query("commit");