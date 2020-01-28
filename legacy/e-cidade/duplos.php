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

require(modification("libs/db_utils.php"));
require(modification("model/configuracao/TraceLog.model.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conn.php"));


if ( !empty($argv[2]) && $argv[2] == "SERVIDOR_MANUAL") {
 
   $DB_SERVIDOR = "";
   $DB_BASE     = "";
   $DB_PORTA    = "";
   $DB_USUARIO  = "";
   $DB_SENHA    = "";
}

// Funcao para dar Echo dos Logs - retorna o TimeStamp
function db_logduplos($sLog="") {
  //
  $aDataHora = getdate();

  $sOutputLog = sprintf("\n[%02d/%02d/%04d %02d:%02d:%02d] %s",
    $aDataHora["mday"], $aDataHora["mon"], $aDataHora["year"],
    $aDataHora["hours"], $aDataHora["minutes"], $aDataHora["seconds"],
    $sLog);
  echo $sOutputLog;

  return $aDataHora;
}

$isTeste = !empty($argv[1]) && (strtoupper($argv[1])=="TESTE");

if($isTeste) {
  db_logduplos("");
  db_logduplos(">>>>>> MODO DE TESTE. Não executará COMMIT ao final do processamento! <<<<<<");
  db_logduplos("");
}

// time utilizado para monitoria pelo zabbix
db_logduplos(time());

$aDataHoraInicial = db_logduplos("Iniciando Execucao do Duplos.php - 3 segundos... se quiser cancelar CTRL+C");
db_logduplos("Configuracoes: BASE: $DB_BASE  SERVIDOR: $DB_SERVIDOR  PORTA: $DB_PORTA  USUARIO: $DB_USUARIO");
sleep(3);

if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
  db_logduplos("Erro ao conectar com a base de dados");;
  exit(1);
}

db_logduplos("Inicializando Sessao do e-cidade na Base de Dados...");
$sqlsessao = "select fc_startsession();";
$resultsessao = db_query($conn, $sqlsessao);

$sqlsessao  = "select fc_putsession('DB_instit', cast((select codigo from db_config where prefeitura is true limit 1) as text)); \n";
$sqlsessao .= "select fc_putsession('DB_anousu', cast(extract(year from current_date) as text)); \n";
$sqlsessao .= "select fc_putsession('DB_datausu',cast(current_date as text));";
$resultsessao = db_query($conn, $sqlsessao);

db_logduplos("Dividindo agendamentos múltiplos em agendamentos individuais...");
$sqldivide  = "select fc_divide_agendamento_duplos(z10_codigo) ";
$sqldivide .= "  from cgmcorreto ";
$sqldivide .= "       join cgmerrado on z11_codigo = z10_codigo ";
$sqldivide .= " where z10_proc is false ";
$sqldivide .= " group by z10_codigo ";
$sqldivide .= "having count(*) > 1 ";
$resultdivide = db_query($conn, $sqldivide);

$mostra=0;

/**
 * Tabelas com alterações específicas
 * Chave = codarq
 */
$aTabelasRegrasEspecificas = array(
  2938 => "cgmtipoempresa"
);

$sql_correto = "select * from cgmcorreto where z10_proc is false order by z10_data, z10_hora, z10_codigo";
$result_correto = db_query($sql_correto) or die(db_logduplos("sql 1: " . pg_ErrorMessage()));

for ($record_correto=0; $record_correto < pg_numrows($result_correto); $record_correto++) {
  db_fieldsmemory($result_correto,$record_correto);
  db_logduplos("\n\n\n\n\n\n");
  db_logduplos(" processando cgm correto: " . $z10_numcgm . " - codigo: $z10_codigo - $record_correto/" . pg_numrows($result_correto) . "...");

  // Grava usuario que agendou o duplos na sessao
  $sqlsessao = "select fc_putsession('DB_id_usuario', '$z10_login');";
  $resultsessao = db_query($conn, $sqlsessao);

  $result = db_query("begin;");

  //Disabilita trigger que insere registro na promitente
  //db_query("alter table aguabase disable trigger tr_agua_atualizaiptubase");
  db_query("select fc_putsession('__status_tr_agua_atualizaiptubase', 'disable');");

  // Desabilita trigger que valida Instituicao do arrecad, pois o duplo devera
  // alterar CGM de numpres de todas instituicoes
  //db_query("alter table arrecad disable trigger all");


  $sql_errado = "select * from cgmerrado where z11_codigo = $z10_codigo";
  $result_errado = db_query($sql_errado) or die(db_logduplos("sql 2: " . pg_ErrorMessage()));

  for ($record_errado=0; $record_errado < pg_numrows($result_errado); $record_errado++) {
    db_fieldsmemory($result_errado,$record_errado);
    db_logduplos("            cgm errado.: " . $z11_numcgm . " - codigo: $z10_codigo...");
    sleep(3);

    $v_log = "";

    $v_cgmcerto  = $z10_numcgm;
    $v_cgmerrado = $z11_numcgm;

    if ($v_cgmcerto > 0) {

      $sql1  = "select db_syscampodep.codcam, ";
      $sql1 .= "       rtrim(db_sysarquivo.nomearq)::varchar(40) as nomearq, ";
      $sql1 .= "       db_sysarqcamp.codarq, ";
      $sql1 .= "       db_syscampo.nomecam ";
      $sql1 .= "  from db_syscampodep ";
      $sql1 .= "       inner join db_sysarqcamp on db_sysarqcamp.codcam = db_syscampodep.codcam ";
      $sql1 .= "       inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq ";
      $sql1 .= "       inner join db_syscampo   on db_syscampo.codcam   = db_sysarqcamp.codcam ";
      $sql1 .= " where db_syscampodep.codcampai = 216 ";
      $sql1 .= "   and db_sysarqcamp.codarq not in (736,737,511,1383,1015,1010143,2947,1382, 2362, 3012, 3901) ";
      $sql1 .= "   and db_sysarqcamp.codcam not in (5153,5159,216,7872,8213,8195,17180,16650,8205, 20656, 17041, 17042)";
      $sql1 .= "   and exists(select column_name ";
      $sql1 .= "                from information_schema.columns ";
      $sql1 .= "               where table_name  = trim(db_sysarquivo.nomearq) ";
      $sql1 .= "                 and column_name = trim(db_syscampo.nomecam)) ";
      $sql1 .= "union ";
      $sql1 .= "select db_syscampo.codcam, ";
      $sql1 .= "       rtrim(db_sysarquivo.nomearq)::varchar(40) as nomearq, ";
      $sql1 .= "       db_sysarqcamp.codarq, ";
      $sql1 .= "       db_syscampo.nomecam ";
      $sql1 .= "  from db_syscampo ";
      $sql1 .= "	     inner join db_sysarqcamp on db_syscampo.codcam = db_sysarqcamp.codcam ";
      $sql1 .= "       inner join db_sysarquivo on db_sysarquivo.codarq = db_sysarqcamp.codarq ";
      $sql1 .= " where nomecam like '%cgm%' ";
      $sql1 .= "   and db_sysarqcamp.codarq not in (736,737,511,1383,1015,1010143,2947,1382, 2362, 3012, 3901) ";
      $sql1 .= "   and db_sysarqcamp.codcam not in (5153,5159,216,7872,8213,8195,17180,16650,8205, 20656,17041, 17042 )";
      $sql1 .= "   and exists(select column_name ";
      $sql1 .= "                from information_schema.columns ";
      $sql1 .= "               where table_name  = trim(db_sysarquivo.nomearq) ";
      $sql1 .= "                 and column_name = trim(db_syscampo.nomecam)) ";

      /*
       * Verifica CGM's processados como corretos que estão hoje como errados
       * Se encontrar algum para esse processamento, altera o cgm correto do processamento para o CGM correto atual.
       */
      $sSqlCgmCorreto = "select z10_codigo as codcorreto_ant,
                                z01_numcgm as numcgmcorreto_ant,
                                z01_nome   as nomecorreto_ant
                           from cgmcorreto
                                inner join cgm       on z01_numcgm = z10_numcgm
                                inner join cgmerrado on z10_codigo = z11_codigo
                          where z10_numcgm = $v_cgmerrado";

      $rsCgmCorreto   = db_query($sSqlCgmCorreto);
      if (pg_numrows($rsCgmCorreto) > 0) {
        for ($y=0; $y < pg_numrows($rsCgmCorreto); $y++) {
          db_fieldsmemory($rsCgmCorreto,$y);
          db_logduplos("processando tabela cgmcorreto");

          $sql_corretoant = "update cgmcorreto set z10_numcgm = $v_cgmcerto where z10_codigo = $codcorreto_ant";
          $query_corretoant = db_query($sql_corretoant) or die(db_logduplos("sql 3: " . pg_ErrorMessage()));
        }
      }

      $result_campos = db_query($sql1) or die(db_logduplos("sql 4: " . pg_ErrorMessage()));

      for ($record_campos=0; $record_campos < pg_numrows($result_campos); $record_campos++) {
        db_fieldsmemory($result_campos,$record_campos);

        $v_log .= "processando tabela $nomearq";
        db_logduplos("processando tabela $nomearq - codigo: $codarq");
        
        // ver se tabela existe no banco...
        $sql2  = "select relname ";
        $sql2 .= "	from pg_class ";
        $sql2 .= "	     left join pg_index on relfilenode = indexrelid ";
        $sql2 .= " where relname not like 'pg_%' ";
        $sql2 .= "   and relkind = 'r' ";
        $sql2 .= "   and relname = '$nomearq' order by relname";
        $result_relname = db_query($sql2) or die(db_logduplos("sql 5: " . pg_ErrorMessage()));

        if (pg_numrows($result_relname)==0) {
          $v_log .= "          tabela $nomearq nao encontrada no banco...\n";
          if (($codarq == 343 or $codarq ==  959) and $mostra == 1) {
            db_logduplos("... tabela $nomearq nao encontrada no banco...");
          }
        } else {
          $v_log .= "          tabela $nomearq encontrada no banco...\n";
          db_fieldsmemory($result_relname,0);

          $v_comando = "select * from $nomearq where ";
          $v_contador = 1;

          $sql30  = "select count(*) as v_quantpk ";
          $sql30 .= "  from db_sysprikey ";
          $sql30 .= "  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam ";
          $sql30 .= "  where db_sysprikey.codarq = $codarq";
          $result_quantpk = db_query($sql30) or die(db_logduplos("sql 7: " . pg_ErrorMessage()));
          db_fieldsmemory($result_quantpk,0);

          if (($codarq == 343 or $codarq ==  959 or $codarq == 66) and $mostra == 1) {
            //	    echo $sql30 . "\n";
            //	    echo "quantpk: $v_quantpk\n";
          }

          $sql3  = "select ";
          $sql3 .= "	rtrim(db_syscampo.nomecam) as v_nomepk ";
          $sql3 .= "  from db_sysprikey ";
          $sql3 .= "  inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam ";
          $sql3 .= " where db_sysprikey.codarq = $codarq and ";
          $sql3 .= "	db_sysprikey.codcam = $codcam";

          $result_pk = db_query($sql3) or die(db_logduplos("sql 8: " . pg_ErrorMessage()));

          if (($codarq == 343 or $codarq ==  959 or $codarq == 66) and $mostra == 1) {
            //            echo "nomearq:  $nomearq - codarq: $codarq - codcam: $codcam - tamanho: " . pg_numrows($result_pk) . " - quantpk: $v_quantpk\n";
            //	    echo $sql3 . "\n";
          }


          if (pg_numrows($result_pk) > 0 and $v_quantpk >= 1) {

            db_logduplos("   11 - achou pk em " . $nomearq );

            db_fieldsmemory($result_pk,0);
            $v_comando = $v_comando . $v_nomepk . " = " . $v_cgmerrado;

            $result_comando = db_query($v_comando) or die(db_logduplos("sql 9: " . pg_ErrorMessage()));
            db_logduplos("executando comando: $v_comando - " . (pg_numrows($result_comando) > 0?"encontrou " . pg_numrows($result_comando) . " registros":"nao encontrou nenhum registro"));

            $v_log .= "executando comando: $v_comando - " . (pg_numrows($result_comando) > 0?"encontrou " . pg_numrows($result_comando) . " registros":"nao encontrou nenhum registro". "\n");

            if (pg_numrows($result_comando) > 0) {

              for ($record_conteudo=0; $record_conteudo < pg_numrows($result_comando); $record_conteudo++) {
                db_fieldsmemory($result_comando,$record_conteudo);

                $v_campos = "select * from " . $nomearq . " where " . $v_nomepk . " = " . $v_cgmcerto;

                $sql4  = "select rtrim(db_syscampo.nomecam) as nomecam_pk, conteudo as conteudo_pk ";
                $sql4 .= "	from db_sysprikey ";
                $sql4 .= "	inner join db_syscampo on db_syscampo.codcam = db_sysprikey.codcam and ";
                $sql4 .= "	db_sysprikey.codcam <> $codcam ";
                $sql4 .= "where db_sysprikey.codarq = $codarq";
                if (($codarq == 343 or $codarq ==  959 or $codarq == 66) and $mostra == 1) {
                  db_logduplos("sql4: $sql4");
                }
                $result_nomecam_pk = db_query($sql4) or die(db_logduplos("sql 10: " . pg_ErrorMessage()));

                $campos_pk = $v_cgmerrado;

                $executar = '$' . 'GLOBALS["HTTP_POST_VARS"]["' . $v_nomepk . '"]=' . $v_cgmerrado . ';';
                eval($executar);

                $v_campos2 = "";
                for ($record_nomecam_pk=0; $record_nomecam_pk < pg_numrows($result_nomecam_pk); $record_nomecam_pk++) {
                  db_fieldsmemory($result_nomecam_pk,$record_nomecam_pk);
                  $v_campos2 .= " and $nomecam_pk = " . (strpos("-".$conteudo_pk, "char") > 0?"'".$$nomecam_pk."'":$$nomecam_pk);
                  $campos_pk .= ", " . (strpos("-".$conteudo_pk, "char") > 0?"'".$$nomecam_pk."'":$$nomecam_pk);
                }

                db_logduplos("     1 - " . $v_campos . $v_campos2 );
                $result_campos2 = db_query($v_campos . $v_campos2) or die(db_logduplos("sql 1x: " . pg_ErrorMessage()));

                if (pg_numrows($result_campos2) > 0) {
                  $sql55 = "select * from $nomearq where $nomecam = $v_cgmerrado " . $v_campos2;

                  $result55 = db_query($sql55) or die(db_logduplos("sql 11: " . pg_ErrorMessage()));
                  if (pg_numrows($result55) > 0) {

                    $sql5 = "delete from $nomearq where $nomecam = $v_cgmerrado " . $v_campos2;
                    echo $nomearq."\n";
                  } else {

                    $sql5 = "";
                  }

                } else {

                  db_logduplos("       222");
                  $sql5 = "update $nomearq set $nomecam = $v_cgmcerto where $nomecam = $v_cgmerrado " . $v_campos2;

                }
                db_logduplos("sql5 = $sql5");

                switch ($nomearq) {
                case "inicial":
                  break;

                case "escrito":
                  $sqlcadescrito="select * from cadescrito where q86_numcgm = $v_cgmerrado";

                  $rescadescrito=db_query($sqlcadescrito);

                  // Se escrito nao possui cadescrito...
                  if(pg_num_rows($rescadescrito) == 0) {
                    // alterar escrito para cgmcorreto
                    $sqlaltesc = "update escrito set q10_numcgm =$v_cgmcerto  where q10_numcgm = $v_cgmerrado";
                    $resultaltesc = db_query($sqlaltesc) or die(db_logduplos("sql 12: " . pg_ErrorMessage()));
                    db_logduplos("$sqlaltesc ");
                  }
                  break;

                  //se for advog
                case "advog":

                  $sqlinicial= "select * from inicial where v50_advog= $v_cgmerrado";
                  $resultinicial = db_query($sqlinicial);
                  $linhasinicial= pg_num_rows($resultinicial);
                  // se tiver registros na inicial
                  if($linhasinicial > 0){

                    //incluir advog com cgmcorreto
                    db_logduplos("************************ ADVOG ********************");
                    db_logduplos("- advog possui inicial");
                    db_fieldsmemory($result_comando,0) ;

                    $sql = "select * from advog where v57_numcgm = $v_cgmcerto";

                    $res = db_query($sql);

                    if(pg_num_rows($res) == 0) {
                      db_logduplos("$sqladvog");
                      $sqladvog ="insert into advog (v57_numcgm,v57_oab) values ($v_cgmcerto,'CGM $v_cgmcerto')";
                      $resultadvog= db_query($sqladvog) or die(db_logduplos("sql 13: " . pg_ErrorMessage()));
                    }

                    // alterar inical para cgmcorreto
                    $sqlaltini = "update inicial set v50_advog =$v_cgmcerto  where v50_advog = $v_cgmerrado";
                    $resultaltini = db_query($sqlaltini) or die(db_logduplos("sql 14: " . pg_ErrorMessage()));
                    db_logduplos("$sqlaltini ");

                    // deletar advog com cgmerrado
                    $sqldeladvog= "delete from advog where v57_numcgm = $v_cgmerrado";
                    $resultdeladvog= db_query($sqldeladvog) or die(db_logduplos("sql 14: " . pg_ErrorMessage()));
                    db_logduplos("$sqldeladvog ");

                    // ALTERAR ADVOG PARA OAB CORRETA
                    $sqlaltadvog = "update advog set v57_oab = '$v57_oab' where v57_numcgm = $v_cgmcerto";
                    $resultaltadvog = db_query($sqlaltadvog) or die(db_logduplos("sql 16: " . pg_ErrorMessage()));
                    db_logduplos("$sqlaltadvog ");

                    db_logduplos("advog e inicial ok.......");

                  }
                  break;

                  // se for cadescrito
                case "cadescrito":

                  $sqlesc= "select * from escrito where q10_numcgm= $v_cgmerrado";
                  $resultesc = db_query($sqlesc);
                  $linhasesc= pg_num_rows($resultesc);
                  // se tiver registros na escrito
                  if($linhasesc > 0){
                    //incluir cadescrito com cgmcorreto
                    db_logduplos("************************ CADESCRITO ********************");

                    $sql="select * from cadescrito where q86_numcgm = $v_cgmcerto";
                    $res=db_query($sql);

                    if(pg_num_rows($res) == 0) {
                      db_logduplos("- cadescrito possui escrito ");
                      $sqlcadesc ="insert into cadescrito (q86_numcgm) values ($v_cgmcerto)";
                      db_logduplos("$sqlcadesc");
                      $resultcadesc= db_query($sqlcadesc) or die(db_logduplos("sql 17: " . pg_ErrorMessage()));
                    }

                    // alterar escrito para cgmcorreto
                    $sqlaltesc = "update escrito set q10_numcgm =$v_cgmcerto  where q10_numcgm = $v_cgmerrado";
                    $resultaltesc = db_query($sqlaltesc) or die(db_logduplos("sql 18: " . pg_ErrorMessage()));
                    db_logduplos("$sqlaltesc ");

                  }

                  // deletar cadescrito com cgmerrado
                  $sqldelcadesc= "delete from cadescrito where q86_numcgm = $v_cgmerrado";
                  $resultdelcadesc= db_query($sqldelcadesc) or die(db_logduplos("sql 19: " . pg_ErrorMessage()));
                  db_logduplos("$sqldelcadesc ");

                  //echo "cadescrito e escrito ok.......";
                  db_logduplos("cadescrito e escrito ok.......");

                  break;

                case "aidof":
                  db_logduplos("************************ aidof  ********************");
                  $sql = "select  * from graficas where y20_grafica = $v_cgmerrado";
                  $rs  = db_query($sql);
                  if (pg_num_rows($rs) >  0){

                    $ins = "insert into graficas select $v_cgmcerto,y20_id_usuario,y20_data
                      from graficas where y20_grafica = $v_cgmerrado";
                    $rsGraNovo = db_query($ins) or die (db_logduplos("sql 20: " . pg_ErrorMessage()));
                    db_logduplos("$ins");
                    if ($rsGraNovo){
                      $updateAidof = "update aidof set y08_numcgm = $v_cgmcerto where y08_numcgm = $v_cgmerrado;";
                      $rsAidof     = db_query($updateAidof) or die (db_logduplos("sql 21: " . pg_ErrorMessage()));

                      db_logduplos($updateAidof);
                      $deleteGraficas = "delete from  graficas where y20_grafica = $v_cgmerrado ";
                      $rsGraficas     = db_query($deleteGraficas) or die (db_logduplos("sql 22: " . pg_ErrorMessage()));
                      db_logduplos($Deletegraficas);
                    }
                  }
                  break;

                case "funerarias":

                  db_logduplos("Processando Funerarias");

                  $sSqlFunerariaSepultamentoErrado = "select * from sepultamentos where cm01_i_funeraria = $v_cgmerrado";
                  $rsFunerariaSepultamentoErrado    = db_query($sSqlFunerariaSepultamentoErrado);

                  //verifica se a funeraria possui sepultamentos ligados a ela
                  if (pg_numrows($rsFunerariaSepultamentoErrado) > 0) {

                    //verifica se há cadastro da funerária correta no sistema, se sim, altera a funeraria do sepultamento
                    //e deleta o registro da funeraria errada
                    $sSqlVerificaFuneraria = "select * from funerarias where cm17_i_funeraria = $v_cgmcerto";
                    $rsVerificaFuneraria   = db_query($sSqlVerificaFuneraria);
                    if (pg_num_rows($rsVerificaFuneraria) > 0) {

                      $sSqlFunerariaSepultamento = "update sepultamentos set cm01_i_funeraria = $v_cgmcerto where cm01_i_funeraria = $v_cgmerrado";
                      $rsFunerariaSepultamento    = db_query($sSqlFunerariaSepultamento);

                      $sSqlDelFuneraria = "delete from funerarias where cm17_i_funeraria = $v_cgmerrado";
                      $rsDelFuneraria  = db_query($sSqlDelFuneraria);

                    } else {

                      //Se não há registro da funeraria correta no sistama mas há sepultamentos ligados a funeraria errada,
                      //insere a funerária correta, altera a funeraria do sepultamento para a correta e deleta a funerária errada
                      $sSqlCadFuneraria = "insert into funerarias(cm17_i_funeraria) values($v_cgmcerto)";
                      $rsCadFuneraria  = db_query($sSqlCadFuneraria);

                      $sSqlFunerariaSepultamento = "update sepultamentos set cm01_i_funeraria = $v_cgmcerto where cm01_i_funeraria = $v_cgmerrado";
                      $rsFunerariaSepultamento    = db_query($sSqlFunerariaSepultamento);

                      $sSqlDelFuneraria = "delete from funerarias where cm17_i_funeraria = $v_cgmerrado";
                      $rsDelFuneraria  = db_query($sSqlDelFuneraria);

                    }

                  } else {

                    $sSqlVerificaFuneraria = "select * from funerarias where cm17_i_funeraria = $v_cgmcerto";
                    $rsVerificaFuneraria   = db_query($sSqlVerificaFuneraria);
                    if (pg_num_rows($rsVerificaFuneraria) == 0) {
                      $sSqlCadFuneraria = "insert into funerarias(cm17_i_funeraria) values($v_cgmcerto)";
                      $rsCadFuneraria  = db_query($sSqlCadFuneraria);
                    }

                  }

                  $sSqlDelFuneraria = "delete from funerarias where cm17_i_funeraria = $v_cgmerrado";
                  $rsDelFuneraria  = db_query($sSqlDelFuneraria);


                  break;

                case "pcforne":

                  //verificamos se o cgm correto possui cadastro como fornecedor
                  $sSqlPcForne = "select * from pcforne where pc60_numcgm = $v_cgmcerto";
                  $rsPcForne   = db_query($sSqlPcForne);

                  //verificamos se o cgm errado possui cadastro como fornecedor
                  $sSqlPcForneErrado = "select * from pcforne where pc60_numcgm = $v_cgmerrado";
                  $rsPcForneErrado   = db_query($sSqlPcForneErrado);

                  /*
                   * Primeiramente verificamos se o cgm correto possui cadastro como fronecedor, se o errado possuir
                   * e o correto não, cadastramos o correto como fornecedor
                   *
                   * Verificamos se o cgm errado possui certificado, subgrupo e movimentação, caso positivo
                   * alteramos o cgm para o correto.
                   *
                   * Após deletamos o registro de fornecedor do cgm errado.
                   *
                   */

                  if (pg_numrows($rsPcForne) == 0 && pg_numrows($rsPcForneErrado) > 0) {
                    $sSqlPcForne = "insert into pcforne (pc60_numcgm,
                      pc60_dtlanc,
                      pc60_obs,
                      pc60_bloqueado,
                      pc60_hora,
                      pc60_usuario)
                      select {$v_cgmcerto},
                      pc60_dtlanc,
                      pc60_obs,
                      pc60_bloqueado,
                      pc60_hora,
                      pc60_usuario
                      from pcforne
                      where pc60_numcgm = $v_cgmerrado";
                    $rsPcForne   = db_query($sSqlPcForne);
                  }

                  $sSqlPcForneCertif = "select * from pcfornecertif where pc74_pcforne = $v_cgmerrado";
                  $rsPcForneCertif = db_query($sSqlPcForneCertif);
                  if (pg_numrows($rsPcForneCertif) > 0) {
                    $sSqlPcForneCertif = "update pcfornecertif set pc74_pcforne = $v_cgmcerto where pc74_pcforne = $v_cgmerrado";
                    $rsPcForneCertif = db_query($sSqlPcForneCertif);
                  }

                  $sSqlPcForneSubGrupo = "select * from pcfornesubgrupo where pc76_pcforne = $v_cgmerrado";
                  $rsPcForneSubGrupo = db_query($sSqlPcForneSubGrupo);
                  if ( pg_numrows($rsPcForneSubGrupo) > 0 ){
                    $sSqlPcForneSubGrupo = "update pcfornesubgrupo set pc76_pcforne = $v_cgmcerto where pc76_pcforne = $v_cgmerrado";
                    $rsPcForneSubGrupo = db_query($sSqlPcForneSubGrupo);
                  }

                  $sSqlPcForneMov = "select * from pcfornemov where pc62_numcgm = $v_cgmerrado";
                  $rsPcForneMov = db_query($sSqlPcForneMov);
                  if ( pg_numrows($rsPcForneMov) > 0 ){
                    $sSqlPcForneMov = "update pcfornemov set pc62_numcgm = $v_cgmcerto where pc62_numcgm = $v_cgmerrado";
                    $rsPcForneMov = db_query($sSqlPcForneMov);
                  }

                  $sSqlPcForne = "delete from pcforne where pc60_numcgm = $v_cgmerrado";
                  $rsPcForne   = db_query($sSqlPcForne);
                  break;

                case "pensao" :
                  
                  $sSqlPensaoErrado = "select * from pensao where r52_numcgm = $v_cgmerrado ";
                  $rsPensaoErrado   = db_query($sSqlPensaoErrado);

                  db_logduplos("      5 - Consulta pensao pelo CGM errado: $sSqlPensaoErrado");
                  $v_log .= $sSqlPensaoErrado . "\n";

                  if ( pg_num_rows($rsPensaoErrado) > 0 ) {

                    for ( $iIndPensao = 0; $iIndPensao < pg_num_rows($rsPensaoErrado); $iIndPensao++) {

                      db_fieldsmemory($rsPensaoErrado, $iIndPensao);

                      $sSqlPensaoCorreto = "select *
                        from pensao
                        where r52_anousu = {$r52_anousu}
                        and r52_mesusu = {$r52_mesusu}
                        and r52_regist = {$r52_regist}
                        and r52_numcgm = {$v_cgmcerto}";
                      $rsPensaoCorreto = db_query($sSqlPensaoCorreto);

                      db_logduplos("      5 - Consulta pensao pelo CGM correto: $sSqlPensaoCorreto");
                      $v_log .= $sSqlPensaoCorreto . "\n";

                      if ( pg_num_rows($rsPensaoCorreto) == 0 ) {

                        /**
                         * Insere a pensão para o cgm correto com os mesmos dados da pensao errada
                         */
                        $sSqlPensaoInserir = "insert into pensao
                          (
                            r52_anousu,
                            r52_mesusu,
                            r52_regist,
                            r52_formul,
                            r52_perc,
                            r52_numcgm,
                            r52_codbco,
                            r52_codage,
                            r52_conta,
                            r52_vlrpen,
                            r52_dtincl,
                            r52_pag13,
                            r52_pagfer,
                            r52_pagcom,
                            r52_valor,
                            r52_valcom,
                            r52_val13,
                            r52_limite,
                            r52_dvagencia,
                            r52_dvconta,
                            r52_valfer,
                            r52_pagres,
                            r52_valres,
                            r52_adiantamento13,
                            r52_percadiantamento13
                          )
                          values
                          (
                            $r52_anousu,
                            $r52_mesusu,
                            $r52_regist,
                            '$r52_formul',
                            $r52_perc,
                            $v_cgmcerto,
                            '$r52_codbco',
                            '$r52_codage',
                            '$r52_conta',
                            $r52_vlrpen,
                            " . ($r52_dtincl == "null" || $r52_dtincl == "" ? "null" : "'" . $r52_dtincl . "'") . ",
                            '$r52_pag13',
                            '$r52_pagfer',
                            '$r52_pagcom',
                            $r52_valor,
                            $r52_valcom,
                            $r52_val13,
                            " . ($r52_limite == "null" || $r52_limite == "" ? "null" : "'" . $r52_limite . "'") . ",
                            '$r52_dvagencia',
                            '$r52_dvconta',
                            $r52_valfer,
                            '$r52_pagres',
                            $r52_valres,
                            '$r52_adiantamento13',
                            $r52_percadiantamento13
                          )";
                        $rsSqlPensaoInserir = db_query($sSqlPensaoInserir) or die(db_logduplos("sql 593: " . pg_ErrorMessage()));
                        db_logduplos("      5 - Inserir pensao nova para o CGM correto: $sSqlPensaoInserir");
                        $v_log .= $sSqlPensaoInserir . "\n";
                      }
                    }

                    /*
                     * Alteramos a pensao retenção para o cgm correto
                     */
                    $sSqlUpdatePensaoRetencao = " update pensaoretencao
                      set rh77_numcgm = {$v_cgmcerto}
                      where rh77_numcgm = {$v_cgmerrado}";
                    $rsUpdatePensaoRetencao   = db_query($sSqlUpdatePensaoRetencao) or die(db_logduplos("sql 606: " . pg_ErrorMessage()));

                    db_logduplos("      5 - Altera pensaoretencao do CGM errado para o CGM correto: $sSqlUpdatePensaoRetencao");
                    $v_log .= $sSqlPensaoInserir . "\n";


                    /*
                     * Excluo as pensões do cgm errado
                     */
                    $sSqlExclusaoPensao = "delete from pensao where r52_numcgm = $v_cgmerrado";
                    $rsExcluirPensao    = db_query($sSqlExclusaoPensao) or die(db_logduplos("sql 615: " . pg_ErrorMessage()));
                    db_logduplos("      5 - Excluir a pensao do CGM errado: $sSqlExclusaoPensao");
                    $v_log .= $sSqlExclusaoPensao . "\n";
                  }

                  break;

                case "cgmdoc":
                  $sSqlCgmDocCorreto = "SELECT z02_i_cgm FROM cgmdoc WHERE z02_i_cgm = $v_errado;";
                  db_logduplos("      5 - Consulta cgmdoc pelo CGM errado: $sSqlCgmDocCorreto");
                  $rsCgmDocCorreto   = db_query($sSqlCgmDocCorreto) or die(db_logduplos("sql 619: " . pg_ErrorMessage()));
                  if (pg_num_rows ($rsCgmDocCorreto) > 0) {
                    $sSqlExcluirCgmDoc = "DELETE FROM cgmdoc WHERE z02_i_cgm = $v_cgmerrado;";
                    db_logduplos ("   6 - Excluindo cgmdoc do CGM errado: $sSqlExcluirCgmDoc");
                    $rsExcluirCgmDoc   = db_query($sSqlExcluirCgmDoc) or die(db_logduplos("sql 619: " . pg_ErrorMessage()));
                  }
                  break;

                case 'agualeiturista':

                  db_logduplos("      5 - Consulta CGM errado na tabela agualeitura: {$v_cgmerrado}");

                  $sSqlAguaLeitura = "select x21_numcgm from agualeitura where x21_numcgm = {$v_cgmerrado} limit 1";
                  $rsAguaLeitura   = db_query($sSqlAguaLeitura) or die(db_logduplos("SQL pesquisa agualeitura: " . pg_ErrorMessage()));

                  if($rsAguaLeitura && pg_num_rows($rsAguaLeitura) > 0) {

                    db_logduplos("      6 - Atualiza os registros de CGM {$v_cgmerrado} na tabela agualeitura");

                    db_query("alter table agualeitura disable trigger all");

                    $sSqlTabelaBkp  = "create TEMPORARY TABLE agualeitura_bkp as ";
                    $sSqlTabelaBkp .= "          select x21_codleitura, ";
                    $sSqlTabelaBkp .= "                 x21_codhidrometro, ";
                    $sSqlTabelaBkp .= "                 x21_exerc, ";
                    $sSqlTabelaBkp .= "                 x21_mes, ";
                    $sSqlTabelaBkp .= "                 x21_situacao, ";
                    $sSqlTabelaBkp .= "                 {$v_cgmcerto}, ";
                    $sSqlTabelaBkp .= "                 x21_dtleitura, ";
                    $sSqlTabelaBkp .= "                 x21_usuario, ";
                    $sSqlTabelaBkp .= "                 x21_dtinc, ";
                    $sSqlTabelaBkp .= "                 x21_leitura, ";
                    $sSqlTabelaBkp .= "                 x21_consumo, ";
                    $sSqlTabelaBkp .= "                 x21_excesso, ";
                    $sSqlTabelaBkp .= "                 x21_virou, ";
                    $sSqlTabelaBkp .= "                 x21_tipo, ";
                    $sSqlTabelaBkp .= "                 x21_status, ";
                    $sSqlTabelaBkp .= "                 x21_saldo ";
                    $sSqlTabelaBkp .= "            from agualeitura ";
                    $sSqlTabelaBkp .= "           where x21_numcgm = {$v_cgmerrado}; ";
                    $rsTabelaBkp    = db_query($sSqlTabelaBkp) or die(db_logduplos("Criação da tabela temporária: " . pg_ErrorMessage()));

                    if($rsTabelaBkp) {

                      $sSqlDeletaAguaLeitura = "delete from agualeitura where x21_numcgm = {$v_cgmerrado}";
                      $rsDeletaAguaLeitura   = db_query($sSqlDeletaAguaLeitura) or die(db_logduplos("Delete registros agualeitura CGM errado: " . pg_ErrorMessage()));

                      if($rsDeletaAguaLeitura) {

                        $sSqlInsereAguaLeitura  = "insert into agualeitura ";
                        $sSqlInsereAguaLeitura .= "      select * ";
                        $sSqlInsereAguaLeitura .= "        from agualeitura_bkp ";
                        $rsInsereAguaLeitura    = db_query($sSqlInsereAguaLeitura) or die(db_logduplos("Inserção dos registros agualeitura CGM correto: " . pg_ErrorMessage()));

                        if($rsInsereAguaLeitura) {

                          $sSqlAtualizaAguaLeiturista  = "update agualeiturista ";
                          $sSqlAtualizaAguaLeiturista .= "   set x16_numcgm = {$v_cgmcerto} ";
                          $sSqlAtualizaAguaLeiturista .= " where x16_numcgm = {$v_cgmerrado}";
                          $rsAtualizaAguaLeiturista    = db_query($sSqlAtualizaAguaLeiturista) or die(db_logduplos("Atualização dos registros agualeiturista CGM correto: " . pg_ErrorMessage()));
                        }
                      }
                    }

                    db_query("alter table agualeitura enable trigger all");
                  }

                  break;

                  // se não for advog e nem cadescrito
                default:

                  if( substr($sql5, 0, 6) == "delete" ) {
                    //
                    db_logduplos("      5 - $sql5");
                    if ($sql5 != "") {
                      $v_log .= $sql5 . "\n";
                      $result = db_query($sql5) or die(db_logduplos("sql 23: " . pg_ErrorMessage()));
                    }

                  }


                  //echo "entrou aqui...se não for advog e nem cadescrito";
                  db_logduplos("entrou aqui...se não for advog e nem cadescrito");
                  //echo "       222\n";
                  db_logduplos("       222");
                  $sql5 = "update $nomearq set $nomecam = $v_cgmcerto where $nomecam = $v_cgmerrado " . $v_campos2;
                  //echo "      5 - $sql5\n";

                  db_logduplos("      5 - $sql5");
                  if ($sql5 != "") {
                    $v_log .= $sql5 . "\n";
                    $result = db_query($sql5) or die(db_logduplos("sql 24: " . pg_ErrorMessage()));
                  }

                  break;
                }

              }

            }

          } else {

            db_logduplos("   11 - nao achou pk em " . $nomearq );
            $sql9 = "select $nomecam from $nomearq where $nomecam = $v_cgmerrado";
            $result9 = db_query($sql9) or die(db_logduplos("sql 25: $sql9 \n" . pg_ErrorMessage()));
            $v_log .= "comando executado: $sql9 - " . (pg_numrows($result9) > 0?"encontrou " . pg_numrows($result9) . " registros":"nao encontrou nenhum registro");

            if (pg_numrows($result9) > 0) {

              switch ($nomearq) {

              case "legista":
                db_logduplos(" processando legista");

                /*
                 *  Verificamos se já existe cadastro para o cgm correto e para o cgm errado na tabela legista
                 *  Se não existir cadastro para o cgm correto mas existir para o cgm errado, cadastramos o cgm correto como legista
                 *  Alteramos o cadastro de sepultamentos para o legista do cgm correto
                 *  Excluímos o cadastro do cgm errado da tabela legista
                 */

                $sSqlLegistaCorreto = "select cm32_i_codigo,
                  cm32_i_numcgm,
                  coalesce(cm32_i_crm,0) as cm32_i_crm
                  from legista
                  where cm32_i_numcgm = $v_cgmcerto";
                $rsLegistaCorreto   = @db_query($sSqlLegistaCorreto);
                $iLegista           = @pg_result($rsLegistaCorreto,0,0);

                $sSqlLegistaErrado = "select cm32_i_codigo,
                  cm32_i_numcgm,
                  coalesce(cm32_i_crm,0) as cm32_i_crm
                  from legista
                  where cm32_i_numcgm = $v_cgmerrado";
                $rsLegistaErrado   = db_query($sSqlLegistaErrado);

                if (pg_numrows($rsLegistaCorreto) == 0 && pg_numrows($rsLegistaErrado) > 0) {

                  $iCrm     = pg_result($rsLegistaErrado,0,2);
                  $iLegista = pg_result(db_query("select nextval('cem_legista_seq')"),0,0);
                  $sSqlLegista  = "insert into legista(cm32_i_codigo,
                    cm32_i_numcgm,
                    cm32_i_crm)
                    values({$iLegista},
                {$v_cgmcerto},
                {$iCrm})";

                  $QueryLegista = db_query($sSqlLegista)    or die (db_logduplos("sql 26: $sSqlLegista" . pg_ErrorMessage()));

                }

                if ( pg_numrows($rsLegistaErrado) > 0 ) {
                  $iLegistaErrado = pg_result($rsLegistaErrado,0,0);

                  $sSqlSepultamentosLegistas = "update sepultamentos set cm01_i_medico = $iLegista where cm01_i_medico = $iLegistaErrado";
                  $QuerySepultamentoLegista  = db_query($sSqlSepultamentosLegistas);
                }

                $sSqlLegistaDel = "delete from legista where cm32_i_numcgm = $v_cgmerrado";
                $QueryLegista = db_query($sSqlLegistaDel) or die (db_logduplos("sql 27: $sSqlLegistaDel" . pg_ErrorMessage()));

                break;

              case "empageformacgm":
                db_logduplos("************************ empageformacgm  ********************");
                $sql = "select * from empageformacgm where e28_numcgm = $v_cgmcerto";
                $rs  = db_query($sql);
                if (pg_num_rows($rs) >  0){
                  $sql_Empageformacgm = "delete from empageformacgm
                    where e28_numcgm = ".$v_cgmerrado;
                } else {
                  $sql_Empageformacgm = "update empageformacgm set e28_numcgm = $v_cgmcerto
                    where e28_numcgm = ".$v_cgmerrado;
                }
                $rsEmpageformacgm = db_query($sql_Empageformacgm) or die (db_logduplos("sql 37: " . pg_ErrorMessage()));
                db_logduplos("$sql_Empageformacgm");
                break;

              case "issbase":

                $sSqlSocios = "select * from socios where q95_cgmpri = $v_cgmcerto";
                $rRsSocios  = db_query($sSqlSocios);
                if ( pg_numrows($rRsSocios) > 0) {
                  $sSqlSocios = "delete from socios where q95_cgmpri = $v_cgmcerto";
                  $rRsSocios  = db_query($sSqlSocios) or die ('Erro excluido Sócios'.pg_ErrorMessage());
                }

                $sSqlIss = "update issbase set q02_numcgm = $v_cgmcerto where q02_numcgm = " . $v_cgmerrado;
                db_logduplos("     12 - " . $sSqlIss );

                $rResultIss = db_query($sSqlIss) or die(db_logduplos("\nsql: $sSqlIss\n" . pg_ErrorMessage()));
                if (pg_affected_rows($rResultIss) == 0) {
                  db_logduplos("erro ao dar update na tabela issbase...");
                  db_logduplos("comando: $sSqlIss");
                  exit(1);
                }

                break;

              case "cgmdoc":
                $sSqlCgmDocCorreto = "SELECT z02_i_cgm FROM cgmdoc WHERE z02_i_cgm = $v_cgmerrado;";
                db_logduplos("      5 - Consulta cgmdoc pelo CGM certo: $sSqlPensaoErrado");
                $rsCgmDocCorreto   = db_query($sSqlCgmDocCorreto) or die(db_logduplos("sql 619: " . pg_ErrorMessage()));
                if (pg_num_rows ($rsCgmDocCorreto) > 0) {
                  $sSqlExcluirCgmDoc = "DELETE FROM cgmdoc WHERE z02_i_cgm = $v_cgmerrado;";
                  db_logduplos ("   6 - Excluindo cgmdoc do CGM errado: $sSqlExcluirCgmDoc");
                  $rsExcluirCgmDoc   = db_query($sSqlExcluirCgmDoc) or die(db_logduplos("sql 619: " . pg_ErrorMessage()));
                }
                break;

              case "arrecad":
                db_logduplos("      7 - Processando dados da tabela arrecad");
                $sSqlDadosArrecadInstituicao  = "select distinct ";
                $sSqlDadosArrecadInstituicao .= "       arreinstit.k00_numpre, ";
                $sSqlDadosArrecadInstituicao .= "       arreinstit.k00_instit ";
                $sSqlDadosArrecadInstituicao .= "  from arreinstit ";
                $sSqlDadosArrecadInstituicao .= "       inner join arrecad on arrecad.k00_numpre = arreinstit.k00_numpre ";
                $sSqlDadosArrecadInstituicao .= " where arrecad.k00_numcgm = {$v_cgmerrado}";
                $rsDadosArrecadInstituicao   = db_query($sSqlDadosArrecadInstituicao) or die (db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));
                for ($iInd = 0; $iInd < pg_num_rows($rsDadosArrecadInstituicao); $iInd ++) {
                  $oDadosArrecadInstituicao = db_utils::fieldsMemory($rsDadosArrecadInstituicao, $iInd);
                  db_query("select fc_putsession('DB_instit', '{$oDadosArrecadInstituicao->k00_instit}')");

                  $sql18 = "update arrecad
                    set k00_numcgm = $v_cgmcerto
                    where k00_numcgm = $v_cgmerrado
                    and k00_numpre = {$oDadosArrecadInstituicao->k00_numpre}";
                  $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

                }

                $sSqlSessao = "select fc_putsession('DB_instit', cast((select codigo from db_config where prefeitura is true limit 1) as text)); \n";
                db_query($sSqlSessao);
                break;

              case 'pensao':
                  
                  /**
                   * Consulta das pensões do cgm errado.
                   */
                  db_logduplos("Pensao: Buscando as pensoes do CGM ERRADO({$v_cgmerrado})");
                  
                  $sSqlPensaoErrado        = "select * from pensao where r52_numcgm = {$v_cgmerrado} ";
                  $rsPensaoErrado          = db_query($sSqlPensaoErrado);
                  $iQuantidadePensaoErrado = pg_num_rows($rsPensaoErrado);
              
                  $v_log .= $sSqlPensaoErrado . "\n";
                  
                  db_logduplos("Pensao: Quantidades de pensoes do cgm errado: {$iQuantidadePensaoErrado}");
                  
                  /**
                   * Insere registros de pensão para o cgm correto, caso ele não exista.
                   */
                  if ($iQuantidadePensaoErrado > 0) {
    
                    for ($iIndPensao = 0; $iIndPensao < $iQuantidadePensaoErrado; $iIndPensao++) {

                      db_fieldsmemory($rsPensaoErrado, $iIndPensao);
                      
                      /**
                       * Consulta as pensões do cgm certo.
                       */
                      db_logduplos("Pensao: Buscando as pensoes do CGM CORRETO({$v_cgmcerto})");
                      
                      $sSqlPensaoCorreto       = "select *                         ";
                      $sSqlPensaoCorreto      .= " from pensao                     ";
                      $sSqlPensaoCorreto      .= "where r52_anousu = {$r52_anousu} ";
                      $sSqlPensaoCorreto      .= "  and r52_mesusu = {$r52_mesusu} ";
                      $sSqlPensaoCorreto      .= "  and r52_regist = {$r52_regist} ";
                      $sSqlPensaoCorreto      .= "  and r52_numcgm = {$v_cgmcerto} ";
                      $rsPensaoCorreto         = db_query($sSqlPensaoCorreto);
                      $iQuantidadePensaoCerto  = pg_num_rows($rsPensaoCorreto);
                      
                      $v_log .= $sSqlPensaoCorreto . "\n";

                      db_logduplos("Pensao: Quantidades de pensoes do cgm certo: {$iQuantidadePensaoCerto}");
                      
                      /**
                       * Insere a pensão para o cgm correto com os mesmos dados da pensao do cgm errado, caso ele não exista.
                       */
                      if ($iQuantidadePensaoCerto == 0) {

                        $sSqlPensaoInserir = "insert into pensao
                          (
                            r52_anousu,
                            r52_mesusu,
                            r52_regist,
                            r52_formul,
                            r52_perc,
                            r52_numcgm,
                            r52_codbco,
                            r52_codage,
                            r52_conta,
                            r52_vlrpen,
                            r52_dtincl,
                            r52_pag13,
                            r52_pagfer,
                            r52_pagcom,
                            r52_valor,
                            r52_valcom,
                            r52_val13,
                            r52_limite,
                            r52_dvagencia,
                            r52_dvconta,
                            r52_valfer,
                            r52_pagres,
                            r52_valres,
                            r52_adiantamento13,
                            r52_percadiantamento13
                          )
                          values
                          (
                            $r52_anousu,
                            $r52_mesusu,
                            $r52_regist,
                            '$r52_formul',
                            $r52_perc,
                            $v_cgmcerto,
                            '$r52_codbco',
                            '$r52_codage',
                            '$r52_conta',
                            $r52_vlrpen,
                            " . ($r52_dtincl == "null" || $r52_dtincl == "" ? "null" : "'" . $r52_dtincl . "'") . ",
                            '$r52_pag13',
                            '$r52_pagfer',
                            '$r52_pagcom',
                            $r52_valor,
                            $r52_valcom,
                            $r52_val13,
                            " . ($r52_limite == "null" || $r52_limite == "" ? "null" : "'" . $r52_limite . "'") . ",
                            '$r52_dvagencia',
                            '$r52_dvconta',
                            " . (empty($r52_valfer) ? '0' : $r52_valfer) . ",
                            '$r52_pagres',
                            $r52_valres,
                            '$r52_adiantamento13',
                            $r52_percadiantamento13)";
                        $rsSqlPensaoInserir = db_query($sSqlPensaoInserir) or die(db_logduplos("Erro: " . pg_ErrorMessage()));
                        db_logduplos("Pensao: Inserindo a pensao nova para o CGM CORRETO({$v_cgmcerto}) na competencia({$r52_anousu}/{$r52_mesusu})");
                        $v_log .= $sSqlPensaoInserir . "\n";
                      }
                    }

                    /*
                     * Substitui os dados da pensao retenção do cgm errado para o cgm correto.
                     */
                    $sSqlUpdatePensaoRetencao = "update pensaoretencao set rh77_numcgm = {$v_cgmcerto} where rh77_numcgm = {$v_cgmerrado}";
                    $rsUpdatePensaoRetencao   = db_query($sSqlUpdatePensaoRetencao) or die(db_logduplos("Erro: " . pg_ErrorMessage()));
                    
                    db_logduplos("Pensao: Alterando a pensao retencao do CGM ERRADO para o CGM CORRETO");
                    $v_log .= $sSqlPensaoInserir . "\n";

                    db_query("select fc_putsession('DB_disable_trigger', 'true');");
                    
                    /**
                     * Armazena os dados da pensão bancária do cgm errado, logo faz a exclusão dos dados no banco de dados. 
                     */
                    $rsBuscaPensaoContaBancaria = db_query("select * from pensaocontabancaria where rh139_numcgm = {$v_cgmerrado}");
                    $aPensaoContaBancaria       = db_utils::getCollectionByRecord($rsBuscaPensaoContaBancaria);
                    $rsDeleteContaBancaria      = db_query("delete from pensaocontabancaria where rh139_numcgm = {$v_cgmerrado}");
                    
                    /**
                     * Caso exista pensão bancária do cgm errado, é salvo a nova pensão bancária com o cgm correto. 
                     */
                    foreach ($aPensaoContaBancaria as $oStdPensaoContaBancaria) {
              
                      $sSqlPensaoBancaria          = "select 1 as registros                                                       ";
                      $sSqlPensaoBancaria         .= "  from pensaocontabancaria                                                  ";
                      $sSqlPensaoBancaria         .= "where rh139_numcgm        = {$v_cgmcerto}                                   ";
                      $sSqlPensaoBancaria         .= "  and rh139_anousu        = {$oStdPensaoContaBancaria->rh139_anousu}        ";
                      $sSqlPensaoBancaria         .= "  and rh139_mesusu        = {$oStdPensaoContaBancaria->rh139_mesusu}        ";
                      $sSqlPensaoBancaria         .= "  and rh139_regist        = {$oStdPensaoContaBancaria->rh139_regist}        ";
                      $sSqlPensaoBancaria         .= "  and rh139_contabancaria = {$oStdPensaoContaBancaria->rh139_contabancaria} ";
                      $rsPensaoBancaria            = db_query($sSqlPensaoBancaria);
                      $iQuantidadesPensaoBancaria  = pg_num_rows($rsPensaoBancaria);
                      
                      if ($iQuantidadesPensaoBancaria == 0) {
                        
                        $sSqlInserirPensaoBancaria  = "insert into pensaocontabancaria values                ";
                        $sSqlInserirPensaoBancaria .= "  ( {$oStdPensaoContaBancaria->rh139_sequencial}      ";
                        $sSqlInserirPensaoBancaria .= "   ,{$oStdPensaoContaBancaria->rh139_regist}          ";
                        $sSqlInserirPensaoBancaria .= "   ,{$v_cgmcerto}                                     ";
                        $sSqlInserirPensaoBancaria .= "   ,{$oStdPensaoContaBancaria->rh139_anousu}          ";
                        $sSqlInserirPensaoBancaria .= "   ,{$oStdPensaoContaBancaria->rh139_mesusu}          ";
                        $sSqlInserirPensaoBancaria .= "   ,{$oStdPensaoContaBancaria->rh139_contabancaria}); ";
                        $rsInserirPensaoBancaria    = db_query($sSqlInserirPensaoBancaria);

                        if (!$rsInserirPensaoBancaria) {
                          db_logduplos("Erro: Erro ao incluir na tabela [pensaocontabancaria] o CGM CERTO = {$v_cgmcerto} | CGM_ERRADO = {$v_cgmerrado}. -> ".pg_last_error());
                          die();
                        }
                        
                        db_logduplos("Pensao: Alterando a pensao bancaria do CGM ERRADO para o CGM CORRETO");
                      }
                    }

                    db_query("select fc_putsession('DB_disable_trigger', 'false');");

                    /*
                     * Excluí as pensões do cgm errado.
                     */
                    $sSqlExclusaoPensao = "delete from pensao where r52_numcgm = {$v_cgmerrado}";
                    $rsExcluirPensao    = db_query($sSqlExclusaoPensao) or die(db_logduplos("sql 615: " . pg_ErrorMessage()));
                    db_logduplos("Pensao: Excluído a pensao do CGM ERRADO ({$v_cgmerrado})");
                    $v_log .= $sSqlExclusaoPensao . "\n";
                  }
                  
                  break;

              default:

                $sql9 = "update $nomearq set $nomecam = $v_cgmcerto where $nomecam = " . $v_cgmerrado;
                db_logduplos("     12 - " . $sql9 );

                $result9 = db_query($sql9) or die(db_logduplos("\nsql: $sql9\n" . pg_ErrorMessage()));
                if (pg_affected_rows($result9) == 0) {
                  db_logduplos("erro ao dar update na tabela $nomearq...");
                  db_logduplos("comando: $sql9");
                  exit(1);
                }

              }


              if ($result9 == false) {
                db_logduplos("erro: $sql9");
                exit(1);
              }
            }

          }

        }

      }

    }

    /**
     * Bloco com tabelas que não se encaixam nas regras executadas
     */
    foreach($aTabelasRegrasEspecificas as $sChave => $sTabela) {

      switch($sTabela) {

        /**
         * Remove o registro do cgm errado, evitando dados duplicados
         */
        case 'cgmtipoempresa':

          $sSqlTipoEmpresa = "delete from cgmtipoempresa where z03_numcgm = {$v_cgmerrado}";
          $rsTipoEmpresa   = db_query($sSqlTipoEmpresa) or die(db_logduplos("SQL Exclusão Tipo Empresa: " . pg_ErrorMessage()));
          db_logduplos("Tipo Empresa: Excluído o registro da tabela cgmtipoempresa do CGM ERRADO ({$v_cgmerrado})");

          break;
      }

      unset($aTabelasRegrasEspecificas[$sChave]);
    }

    $sql18 = "update issbase set q02_inscr = q02_inscr where q02_numcgm = $v_cgmcerto";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    $sql18 = "update iptubase set j01_matric = j01_matric where j01_numcgm = $v_cgmcerto";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    $sSqlDadosArrecadInstituicao  = "select distinct ";
    $sSqlDadosArrecadInstituicao .= "       arreinstit.k00_numpre, ";
    $sSqlDadosArrecadInstituicao .= "       arreinstit.k00_instit ";
    $sSqlDadosArrecadInstituicao .= "  from arreinstit ";
    $sSqlDadosArrecadInstituicao .= "       inner join arrecad on arrecad.k00_numpre = arreinstit.k00_numpre ";
    $sSqlDadosArrecadInstituicao .= " where arrecad.k00_numcgm = {$v_cgmerrado}";
    $rsDadosArrecadInstituicao   = db_query($sSqlDadosArrecadInstituicao) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));
    for ($iInd = 0; $iInd < pg_num_rows($rsDadosArrecadInstituicao); $iInd ++) {
      $oDadosArrecadInstituicao = db_utils::fieldsMemory($rsDadosArrecadInstituicao, $iInd);

      db_query("select fc_putsession('DB_instit', {$oDadosArrecadInstituicao->k00_instit})");

      $sql18 = "update arrecad
        set k00_numcgm = $v_cgmcerto
        where k00_numcgm = $v_cgmerrado
        and k00_numpre = {$oDadosArrecadInstituicao->k00_numpre}";
      $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    }

    $sSqlSessao = "select fc_putsession('DB_instit', cast((select codigo from db_config where prefeitura is true limit 1) as text)); \n";
      db_query($sSqlSessao);

    $sql18 = "update arrecad
      set k00_numcgm = $v_cgmcerto
      where k00_numcgm = $v_cgmerrado
      and not exists ( select 1 from arreinstit where arreinstit.k00_numpre = arrecad.k00_numpre)";
      $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

      $sql18 = "select k00_numpre as k00_numpre_numcgm from arrenumcgm where k00_numcgm = $v_cgmerrado";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    for ($contanumcgm = 0; $contanumcgm < pg_numrows($result18); $contanumcgm++) {
      db_fieldsmemory($result18, $contanumcgm);

      $sql118 = "select * from arrenumcgm where k00_numcgm = $v_cgmcerto and k00_numpre = $k00_numpre_numcgm";
      $result118 = db_query($sql118) or die(db_logduplos("\nsql: $sql118\n" . pg_ErrorMessage()));

      if (pg_numrows($result118) == 0) {
        $sql1118 = "update arrenumcgm set k00_numcgm = $v_cgmcerto where k00_numcgm = $v_cgmerrado and k00_numpre = $k00_numpre_numcgm";
      } else {
        $sql1118 = "delete from arrenumcgm where k00_numcgm = $v_cgmerrado and k00_numpre = $k00_numpre_numcgm";
      }
      $result1118 = db_query($sql1118) or die(db_logduplos("\nsql: $sql1118\n" . pg_ErrorMessage()));

    }

    $sql18 = "delete from db_cgmruas where z01_numcgm = $v_cgmerrado";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    $sql18 = "delete from db_cgmbairro where z01_numcgm = $v_cgmerrado";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    $sql18 = "delete from db_cgmcgc where z01_numcgm = $v_cgmerrado";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    $sql18 = "delete from db_cgmcpf where z01_numcgm = $v_cgmerrado";
    $result18 = db_query($sql18) or die(db_logduplos("\nsql: $sql18\n" . pg_ErrorMessage()));

    //************  inclui este pedaço para incluir na cgmalt ************
    $sqlcgmalt = " insert into cgmalt(
      z05_sequencia,
      z05_ufcon    , z05_uf       , z05_tipcre    , z05_telef,
      z05_telcon   , z05_telcel   , z05_profis    , z05_numero,
      z05_numcon   , z05_numcgm   , z05_nome      , z05_nacion ,
      z05_munic    , z05_muncon   , z05_login     , z05_incest ,
      z05_ident    , z05_estciv   , z05_ender     , z05_endcon,
      z05_emailc   , z05_email    , z05_cxpostal  , z05_cxposcon ,
      z05_cpf ,
      z05_compl    ,  z05_comcon  , z05_cgccpf    ,
      z05_cgc ,
      z05_cepcon   , z05_cep      , z05_celcon    , z05_cadast ,
      z05_bairro   , z05_baicon   , z05_tipo_alt  , z05_hora ,
      z05_login_alt,
      z05_data_alt ,
      z05_hora_alt ,
      z05_ultalt   , z05_mae      , z05_pai       , z05_nomefanta,
      z05_contato  , z05_sexo     , z05_nasc      , z05_fax
    )
    select
    nextval('cgmalt_z05_sequencia_seq') as z05_sequencia,
      z01_ufcon      , z01_uf         , z01_tipcre       , z01_telef ,
      z01_telcon     , z01_telcel     , z01_profis       , z01_numero,
      z01_numcon     , z01_numcgm     , z01_nome         , z01_nacion,
      z01_munic      , z01_muncon     , z01_login        , z01_incest,
      z01_ident      , z01_estciv     , z01_ender        , z01_endcon ,
      z01_emailc     , z01_email      , z01_cxpostal     ,  z01_cxposcon,
      case
        when length(trim(z01_cgccpf)) <= 11 then
        trim(z01_cgccpf)
    else ''
      end as z05_cpf,
      z01_compl      ,z01_comcon      , z01_cgccpf       ,
      case
        when length(trim(z01_cgccpf)) > 11 then
        trim(z01_cgccpf)
    else ''
      end as z05_cgc,
      z01_cepcon     , z01_cep         , z01_celcon          , z01_cadast,
      z01_bairro     , z01_baicon      , 'E' as z05_tipo_alt ,  z01_hora ,
      '$z10_login' ,
      current_date as z05_data_alt ,
      to_char(now(), 'HH24:MI') as z05_hora_alt ,
      z01_ultalt     ,  z01_mae        , z01_pai             , z01_nomefanta,
      z01_contato    , z01_sexo        , z01_nasc            , z01_fax
      from cgm
      where z01_numcgm = $v_cgmerrado";

    $v_log .= $sqlcgmalt;
    $result = db_query($sqlcgmalt) or die(db_logduplos("sql 39: $sqlcgmalt\n" . pg_ErrorMessage()));
    //echo "**** incluiu $v_cgmerrado na cgmalt ****";
    db_logduplos("**** incluiu $v_cgmerrado na cgmalt ****");
    //die("$sqlcgmalt");
    //*********************************
    $sql6 = "delete from cgm where z01_numcgm = $v_cgmerrado";
    $v_log .= $sql6;
    $result = db_query($sql6) or die(db_logduplos("sql 40: $sql6\n" . pg_ErrorMessage()));

    $v_lognew = addSlashes($v_log);
    $sql7 = "insert into cgmerradolog values ($z10_codigo, $z11_numcgm, '$v_lognew');";
    $result = db_query($sql7) or die(db_logduplos($sql7."---- sql: " . pg_ErrorMessage()));

  }
  $sql8 = "update cgmcorreto set z10_proc = true where z10_codigo = $z10_codigo";
  $result = db_query($sql8) or die(db_logduplos("sql 41: " . pg_ErrorMessage()));

  //Habilita trigger da aguabase
  db_query("select fc_putsession('__status_tr_agua_atualizaiptubase', 'enable');");

  if(!$isTeste) {
    $result = db_query("commit;");
  } else {
    db_logduplos("");
    db_logduplos(">>>>>> MODO DE TESTE. Efetuando ROLLBACK na transação! <<<<<<");
    db_logduplos("");

    $result = db_query("rollback;");
  }

}

//echo "ok...\n";
db_logduplos("");
db_logduplos("Processamento concluido com sucesso...\n");
db_logduplos("");

exit(0);


function db_fieldsmemory1($recordset,$indice,$formatar="",$mostravar=false) {
  $fm_numfields = pg_numfields($recordset);
  for ($i = 0; $i < $fm_numfields; $i++) {
    $matriz[$i] = pg_fieldname($recordset,$i);
    global $$matriz[$i];
    $aux = trim(pg_result($recordset,$indice,$matriz[$i]));
    if (!empty($formatar)) {
      switch (pg_fieldtype($recordset,$i)) {
      case "float8":
      case "float4":
      case "float":
        $$matriz[$i] = number_format($aux,2,".","");
        if ($mostravar==true) {
          echo $matriz[$i]."->".$$matriz[$i]."<br>";
        }
        break;
      case "date":
        if ($aux!="") {
          $data = split("-",$aux);
          $$matriz[$i] = $data[2]."/".$data[1]."/".$data[0];
        } else {
          $$matriz[$i] = "";
        }
        if ($mostravar==true) {
          echo $matriz[$i]."->".$$matriz[$i]."<br>";
        }
        break;
      default:
        $$matriz[$i] = $aux;
        if ($mostravar==true) {
          echo $matriz[$i]."->".$$matriz[$i]."<br>";
        }
        break;
      }
    } else switch (pg_fieldtype($recordset,$i)) {
case "date":
  $datav = split("-",$aux);
  $split_data = $matriz[$i]."_dia";
  global $$split_data;
  $$split_data =  @$datav[2];
  if ($mostravar==true) {
    echo $split_data."->".$$split_data."<br";
  }
  $split_data = $matriz[$i]."_mes";
  global $$split_data;
  $$split_data =  @$datav[1];
  if ($mostravar==true) {
    echo $split_data."->".$$split_data."<br>";
  }
  $split_data = $matriz[$i]."_ano";
  global $$split_data;
  $$split_data =  @$datav[0];
  if ($mostravar==true) {
    echo $split_data."->".$$split_data."<br>";
  }
  $$matriz[$i] = $aux;
  if ($mostravar==true) {
    echo $matriz[$i]."->".$$matriz[$i]."<br>";
  }
  break;
default:
  $$matriz[$i] = $aux;
  if ($mostravar==true) {
    echo $matriz[$i]."->".$$matriz[$i]."<br>";
  }
  break;
    }
  }
}
