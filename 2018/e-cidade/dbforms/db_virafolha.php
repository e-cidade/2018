<?php
/**
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
?>
<html>
<head>
  <title>Virada Anual</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="../estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
</head>
<body>
<?php
set_time_limit(0);
require_once(modification("./libs/db_stdlib.php"));
require_once(modification("./libs/db_conecta.php"));
require_once(modification("./libs/db_sessoes.php"));
require_once(modification("./libs/db_usuariosonline.php"));
require_once(modification("./classes/db_padroes_classe.php"));
require_once(modification("./classes/db_cargo_classe.php"));
require_once(modification("./classes/db_cfpess_classe.php"));
require_once(modification("./classes/db_cedulas_classe.php"));
require_once(modification("./classes/db_pessoal_classe.php"));
require_once(modification("./classes/db_codmovsefip_classe.php"));
require_once(modification("./classes/db_movcasadassefip_classe.php"));
require_once(modification("./classes/db_afasta_classe.php"));
require_once(modification("./classes/db_cadferia_classe.php"));
require_once(modification("./classes/db_cheques_classe.php"));
require_once(modification("./classes/db_desconto_classe.php"));
require_once(modification("./classes/db_rubricas_classe.php"));
require_once(modification("./classes/db_pesdiver_classe.php"));
require_once(modification("./classes/db_bases_classe.php"));
require_once(modification("./classes/db_basesr_classe.php"));
require_once(modification("./classes/db_efetiv_classe.php"));
require_once(modification("./classes/db_historic_classe.php"));
require_once(modification("./classes/db_eventos_classe.php"));
require_once(modification("./classes/db_folhaemp_classe.php"));
require_once(modification("./classes/db_inssirf_classe.php"));
require_once(modification("./classes/db_landesc_classe.php"));
require_once(modification("./classes/db_lotativ_classe.php"));
require_once(modification("./classes/db_pensao_classe.php"));
require_once(modification("./classes/db_pensaocontabancaria_classe.php"));
require_once(modification("./classes/db_pontofa_classe.php"));
require_once(modification("./classes/db_pontofx_classe.php"));
require_once(modification("./classes/db_depend_classe.php"));
require_once(modification("./classes/db_rhdepend_classe.php"));
require_once(modification("./classes/db_progress_classe.php"));
require_once(modification("./classes/db_rescisao_classe.php"));
require_once(modification("./classes/db_reposic_classe.php"));
require_once(modification("./classes/db_rhpessoalmov_classe.php"));
require_once(modification("./classes/db_rhpesbanco_classe.php"));
require_once(modification("./classes/db_rhpespadrao_classe.php"));
require_once(modification("./classes/db_rhpesrescisao_classe.php"));
require_once(modification("./classes/db_rhinssoutros_classe.php"));
require_once(modification("./classes/db_rhpesponto_classe.php"));
require_once(modification("./classes/db_rhpesprogres_classe.php"));
require_once(modification("./classes/db_rhpeslocaltrab_classe.php"));
require_once(modification("./classes/db_gerfsal_classe.php"));
require_once(modification("./classes/db_gerffer_classe.php"));
require_once(modification("./classes/db_gerfs13_classe.php"));
require_once(modification("./classes/db_rhvisavalecad_classe.php"));
require_once(modification("./classes/db_rhpesrubcalc_classe.php"));
require_once(modification("./classes/db_rhpescargo_classe.php"));
require_once(modification("./classes/db_funcao_classe.php"));
require_once(modification("./classes/db_vtfempr_classe.php"));
require_once(modification("./classes/db_vtffunc_classe.php"));
require_once(modification("./classes/db_vtfdias_classe.php"));
require_once(modification("./classes/db_pensaoretencao_classe.php"));
require_once(modification("./dbforms/db_funcoes.php"));
require_once(modification("./libs/db_libpessoal.php"));
require_once(modification("./classes/db_rhpessoalmovcontabancaria_classe.php"));
require_once(modification("./libs/db_utils.php"));
require_once(modification("./libs/db_app.utils.php"));

$clpadroes                   = new cl_padroes;
$clcargo                     = new cl_cargo;
$clcfpess                    = new cl_cfpess;
$clcedulas                   = new cl_cedulas;
$clpessoal                   = new cl_pessoal;
$clcodmovsefip               = new cl_codmovsefip;
$clmovcasadassefip           = new cl_movcasadassefip;
$clafasta                    = new cl_afasta;
$clcadferia                  = new cl_cadferia;
$clcheques                   = new cl_cheques;
$cldesconto                  = new cl_desconto;
$clrubricas                  = new cl_rubricas;
$clpesdiver                  = new cl_pesdiver;
$clbases                     = new cl_bases;
$clbasesr                    = new cl_basesr;
$clefetiv                    = new cl_efetiv;
$clhistoric                  = new cl_historic;
$cleventos                   = new cl_eventos;
$clfolhaemp                  = new cl_folhaemp;
$clinssirf                   = new cl_inssirf;
$cllandesc                   = new cl_landesc;
$cllotativ                   = new cl_lotativ;
$clpensao                    = new cl_pensao;
$clpensaocontabancaria       = new cl_pensaocontabancaria;
$clpontofa                   = new cl_pontofa;
$clpontofx                   = new cl_pontofx;
$cldepend                    = new cl_depend;
$clrhdepend                  = new cl_rhdepend;
$clprogress                  = new cl_progress;
$clrescisao                  = new cl_rescisao;
$clreposic                   = new cl_reposic;
$clrhpessoalmov              = new cl_rhpessoalmov;
$clrhpesbanco                = new cl_rhpesbanco;
$clrhpespadrao               = new cl_rhpespadrao;
$clrhpesrescisao             = new cl_rhpesrescisao;
$clrhinssoutros              = new cl_rhinssoutros;
$clrhpesponto                = new cl_rhpesponto;
$clrhpesprogres              = new cl_rhpesprogres;
$clrhpeslocaltrab            = new cl_rhpeslocaltrab;
$clrhpessoalmovcontabancaria = new cl_rhpessoalmovcontabancaria;
$clafastaassenta             = new cl_afastaassenta;
$clgerfsal = new cl_gerfsal;
$clgerffer = new cl_gerffer;
$clgerfs13 = new cl_gerfs13;
$clrhvisavalecad = new cl_rhvisavalecad;
$clrhpescargo = new cl_rhpescargo;
$clfuncao = new cl_funcao;
$clvtfempr = new cl_vtfempr;
$clvtffunc = new cl_vtffunc;
$clvtfdias = new cl_vtfdias;
$clpensaoretencao = new cl_pensaoretencao;
$clrhpesrubcalc = new cl_rhpesrubcalc;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$periodoini1 = $dataii_dia."/".$dataii_mes."/".$dataii_ano;
$periodoini2 = $dataif_dia."/".$dataif_mes."/".$dataif_ano;
$periodofim1 = $datafi_dia."/".$datafi_mes."/".$datafi_ano;
$periodofim2 = $dataff_dia."/".$dataff_mes."/".$dataff_ano;


$sqlerro = false;

if (!isset($desprocess)) {

  db_inicio_transacao();
  if($sqlerro == false){

    $sWhere  = "r66_anousu = {$dataii_ano} and r66_mesusu = {$dataii_mes} and ";
    $sWhere .= " not exists (select 1 from codmovsefip v where v.r66_anousu = {$datafi_ano} and v.r66_mesusu = {$datafi_mes} and v.r66_codigo = codmovsefip.r66_codigo)";
    $sql1 = $clcodmovsefip->sql_query_file (null, null,null,"*", null, $sWhere);
    $retorno = db_incluir_sql($sql1,$clcodmovsefip,"codmovsefip");

    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 7";
      //}
    }
  }

  if ($sqlerro == false) {

    $sWhere  = " r67_anousu = {$dataii_ano} and r67_mesusu = {$dataii_mes} and ";
    $sWhere .= " not exists (select 1 from movcasadassefip v where v.r67_anousu = {$datafi_ano} and v.r67_mesusu = {$datafi_mes}";
    $sWhere .= "and v.r67_afast = movcasadassefip.r67_afast and v.r67_reto = movcasadassefip.r67_reto)";
    $sSqlSefip = $clmovcasadassefip->sql_query_file (null, null, null,null, "*", null, $sWhere);
    $retorno = db_incluir_sql($sSqlSefip, $clmovcasadassefip,"movcasadassefip");
    if ($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
    }
  }

  if ($sqlerro == false) {

    $sWhereCargo  = " r65_anousu = {$dataii_ano} and r65_mesusu= {$dataii_mes} ";
    $sWhereCargo .= " and not exists (select 1 from cargo v where v.r65_anousu = {$datafi_ano} and v.r65_mesusu = {$datafi_mes} and v.r65_cargo = cargo.r65_cargo)";
    $sql1 = $clcargo->sql_query_file(null, null, null, "*", null, $sWhereCargo);
    $retorno = db_incluir_sql($sql1,$clcargo,"cargo");
    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
    }
  }

  db_fim_transacao($sqlerro);
}


//FUNçaO PARA INCLUSO NAS TABELAS.
  // $sql1    = SQL de onde vem os dados para virada de ms da folha.
  // $table   = Varivel em que foram instanciados os mtodos da classe.
  // $trigger = Nome da tabela para desabilitar as triggers.

  // RETORNOS.
  // 1 - Quando no existem dados para serem includos.
  // 2 - Incluses OK.
  // Ou retorno de mensagem de erro na incluso ou que fechamento j foi efetuado.
  function db_incluir_sql($sql1,$table,$trigger) {

    echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">".$trigger."</font>');</script>";
    flush();

    global $clrhpesbanco;
    global $clrhpespadrao;
    global $clrhpesrescisao;

    global $dataii_dia;
    global $dataii_mes;
    global $dataii_ano;

    global $dataif_dia;
    global $dataif_mes;
    global $dataif_ano;

    global $datafi_dia;
    global $datafi_mes;
    global $datafi_ano;

    global $dataff_dia;
    global $dataff_mes;
    global $dataff_ano;

    global $periodoini1;
    global $periodoini2;
    global $periodofim1;
    global $periodofim2;

    $mes = $datafi_mes;
    $ano = $datafi_ano;

    $datai = $datafi_ano."-".$datafi_mes."-".$datafi_dia;
    $dataf = $dataff_ano."-".$dataff_mes."-".$dataff_dia;


    // Testa se existem dados para serem includos.


    $result_tabela1  = $table->sql_record($sql1);
    $numrows_tabela1 = $table->numrows;
    if ($numrows_tabela1 == 0) {
      return '1';
    }

    // FOR que busca nome e seta o valor dos campos.
    // Aqui, ele setar os campos para buscar no select de incluso.
    // $campos  = Varivel que receber campos e / ou valores.
    // $virgula = Variavel que receber uma ','.
    // $colunas = Quantidade de colunas que o select retornou.
    $campos  = "";
    $virgula = "";
    $colunas = pg_num_fields($result_tabela1);

    for($ii=0; $ii<$colunas; $ii++){
      $dcoluna = pg_fieldname($result_tabela1, $ii);   // Nome do campo corrente.
      // Testa se o campo  o anousu, se for, seta o ano do novo perodo da folha.
      if(strpos($dcoluna,"anousu") || (strpos($dcoluna,"ano") && $trigger == "baserhcadregime")){
        $dcoluna = $ano;
        // Testa se o campo  o mesusu, se for, seta o ms do novo perodo da folha.
      }else if(strpos($dcoluna,"mesusu") || (strpos($dcoluna,"mes") && $trigger == "baserhcadregime")){
        $dcoluna = $mes;
        // Testa para campos do CFPESS, ao incluir a data inicial e a data final.
      }else if($dcoluna == "r11_datai"){
        $dcoluna = "'".$datai."'";
      }else if($dcoluna == "r11_dataf"){
        $dcoluna = "'".$dataf."'";
        // Testa se  a tabela rhpessoalmov para pegar a sequencia do seqpes.
      }else if($dcoluna == "rh02_seqpes"){
        $dcoluna = "nextval('rhpessoalmov_rh02_seqpes_seq')";
        // Testa se  a tabela rhpesbanco, rhpesrescisao, rhpespadrao, rhpescargo, inssoutros, rhpesprogres, rhpesponto e rhpeslocaltrab para pegar o novo seqpes.
      }else if($dcoluna == "rh44_seqpes" || $dcoluna == "rh03_seqpes" || $dcoluna == "rh05_seqpes" || $dcoluna == "rh20_seqpes"
        || $dcoluna == "rh51_seqpes" || $dcoluna == "rh06_seqpes" || $dcoluna == "rh07_seqpes" || $dcoluna == "rh56_seqpes" || $dcoluna == "rh138_rhpessoalmov"){
        $dcoluna = "a.rh02_seqpes";
        // Testa se  a tabela rhpessoalmovcontabancaria.
      }else if($dcoluna == "rh138_sequencial"){
        $dcoluna = "nextval('rhpessoalmovcontabancaria_rh138_sequencial_seq')";
        // Testa se  a tabela pensaoncontabancaria.
      }else if($dcoluna == "r52_sequencial"){
        $dcoluna = "nextval('pensao_r52_sequencial_seq')";
      }else if($dcoluna == "rh139_sequencial"){
        $dcoluna = "nextval('pensaocontabancaria_rh139_sequencial_seq')";
        // Testa se  a tabela rhvisavalecad.
      }else if($dcoluna == "rh49_codigo"){
        $dcoluna = "nextval('rhvisavalecad_rh49_codigo_seq')";
        // Testa se  a tabela inssirf.
      }else if($dcoluna == "r33_codigo"){
        /** Deixa uma flag na string para dar replace depois **/
        $dcoluna = "#CHAVE#";

        // Testa se  a tabela afasta.
      }else if($dcoluna == "r45_codigo"){
        $dcoluna = "nextval('afasta_r45_codigo_seq')";
        // Testa se  a tabela rhpeslocaltrab
      }else if($dcoluna == "rh56_seq"){
        $dcoluna = "nextval('rhpeslocaltrab_rh56_seq_seq')";
      } else if ($dcoluna == "rh77_sequencial") {
        $dcoluna = "nextval('pensaoretencao_rh77_sequencial_seq')";
      } else if ($dcoluna == "rh158_sequencial") {
        $dcoluna = "nextval('baserhcadregime_rh158_sequencial_seq')";
      } else if ($dcoluna == "rh165_sequencial") {
        $dcoluna = "nextval('tipoassefinanceiro_rh165_sequencial_seq')";
      } else if ($dcoluna == "rh167_sequencial") {
        $dcoluna = "nextval('tipoasseexterno_rh167_sequencial_seq')";
      }
      $campos.= $virgula.$dcoluna;
      $virgula = ",";
    }

    // SQL de insero.
    if($trigger == "rhpesbanco"){

      $sqlcampos = str_replace("rhpesbanco.*",$campos,$sql1);;
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpesbanco.*",$campos,$sql1);

    }else if($trigger == "rhpespadrao"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpespadrao.*",$campos,$sql1);
    }else if($trigger == "rhpesrescisao"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpesrescisao.*",$campos,$sql1);
    }else if($trigger == "rhpescargo"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpescargo.*",$campos,$sql1);
    }else if($trigger == "rhinssoutros"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhinssoutros.*",$campos,$sql1);
    }else if($trigger == "rhpesponto"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpesponto.*",$campos,$sql1);
    }else if($trigger == "rhpesprogres"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpesprogres.*",$campos,$sql1);
    }else if($trigger == "rhpeslocaltrab"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpeslocaltrab.*",$campos,$sql1);
    }else if($trigger == "pontofx"){
      $sql_insert = "insert into ".$trigger." ".str_replace("pontofx.*",$campos,$sql1);
    }else if($trigger == "rhdepend"){
      $sql_insert = "insert into ".$trigger." ".$sql1;
    }else if($trigger == "pensaoretencao"){
      $sql_insert = "insert into ".$trigger." ".str_replace("pensaoretencao.*",$campos,$sql1);
    }else if($trigger == "rhpessoalmovcontabancaria"){
      $sql_insert = "insert into ".$trigger." ".str_replace("rhpessoalmovcontabancaria.*",$campos,$sql1);
    } else if($trigger == "pensaocontabancaria") {
      $sql_insert = "insert into ".$trigger." ".str_replace("pensaocontabancaria.*",$campos,$sql1);
    } else if($trigger == "inssirf") {

      $sql_insert = "";

      $rsInssirf = @db_query($sql1);
      foreach (db_utils::getCollectionByRecord($rsInssirf) as $oInssirf) {

        $iCodigoAntigo = $oInssirf->r33_codigo;
        $nextval       = "select nextval('inssirf_r33_codigo_seq') as chave";
        $rsNextval     = @db_query($nextval);
        $oDados        = db_utils::fieldsMemory($rsNextval,0);
        $dcoluna       = $oDados->chave;
        $iSequencial   = $oDados->chave;
        $nCampos       = str_replace("#CHAVE#", $iSequencial, $campos);

        $sql_insert .= "insert into inssirf select {$nCampos} from inssirf where r33_codigo = {$iCodigoAntigo} and r33_instit = {$oInssirf->r33_instit};\n";
        $sql_insert .= "insert into regimeprevidenciainssirf ";
        $sql_insert .= "(select nextval('regimeprevidenciainssirf_rh129_sequencial_seq'), rh129_regimeprevidencia, {$iSequencial}, {$oInssirf->r33_instit}";
        $sql_insert .= "   from regimeprevidenciainssirf where rh129_codigo = $iCodigoAntigo and rh129_instit = {$oInssirf->r33_instit});\n";

        $sql_insert .= "insert into atolegalprevidenciainssirf ";
        $sql_insert .= "(select nextval('atolegalprevidenciainssirf_rh180_sequencial_seq'), {$oInssirf->r33_instit}, {$iSequencial}, rh180_atolegal,";
        $sql_insert .= "        rh180_numero, rh180_ano, rh180_datapublicacao, rh180_datainiciovigencia";
        $sql_insert .= "   from atolegalprevidenciainssirf where rh180_inssirf = {$iCodigoAntigo} and rh180_instituicao = {$oInssirf->r33_instit});\n";
      }
    } else {
      $sql_insert = "insert into ".$trigger." ".str_replace("*",$campos,$sql1);
    }

    // Desativa triggers.
    //echo("<BR><BR>ALTER TABLE $trigger DISABLE TRIGGER ALL;");
    //$des_trigger = @db_query("ALTER TABLE $trigger DISABLE TRIGGER ALL;");

    // Testa se ocorreu erro ao desativar triggers.
    //echo "<br> 0 - " . pg_last_error();
    //if($des_trigger == false){
    //  return "Erro ao desativar triggers da tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
   // }
    // Executa SQL.
    $res_insert = @db_query($sql_insert, null, "");


    // Testa se deu erro ao inserir.
    if ($res_insert == false) {
      return "Erro ao incluir na tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\n".str_replace("\n","",@pg_last_error());
    }
    // Ativa triggers.
    //echo("<BR><BR>ALTER TABLE $trigger ENABLE TRIGGER ALL;");
    //$ati_trigger = @db_query("ALTER TABLE $trigger ENABLE TRIGGER ALL;");

    // Testa se ocorreu erro ao ativar triggers.
    //echo "<br> 0 - " . pg_last_error();
    //if($ati_trigger == false){
    //  return "Erro ao ativar triggers da tabela ".$trigger.".\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
    //}

    // Retorna que incluses foram efetuadas com sucesso.
    return '2';
  }



db_inicio_transacao();
if (UTILIZAR_NOVO_CADASTRO_FERIAS and !isset($desprocess)) {

  require_once(modification("./model/pessoal/ferias/PeriodoAquisitivoFerias.model.php"));
  db_utils::getDao('rhferias', true);

  $oDaoRhFerias = new cl_rhferias();

  $dDataPeriodoAquisitivoInicial   = "$dataii_ano-$dataii_mes-$dataii_dia";
  $dDataPeriodoAquisitivoFinal     = "$dataif_ano-$dataif_mes-$dataif_dia";

  /**
   * Retorna os servidores sem período aquisitivo, ou com período aquisitivo terminando no ano/mês do fechamento da folha
   */
  $sSqlServidoresPeriodoAquisitivo = $oDaoRhFerias->sql_queryAlteracaoPeriodoAquisitivoServidores(new DBDate($dDataPeriodoAquisitivoInicial), new DBDate($dDataPeriodoAquisitivoFinal));
  $rsServidoresPeriodoAquisitivo   = db_query($sSqlServidoresPeriodoAquisitivo);
  $aServidoresPeriodoAquisitivo    = db_utils::getCollectionByRecord($rsServidoresPeriodoAquisitivo);

  try {

    foreach ($aServidoresPeriodoAquisitivo as $oServidorPeriodoAquisitivo) {

      if (!empty($oServidorPeriodoAquisitivo->ultimo_periodo_aquisitivo)) {

        /**
         * Caso o servidor ja tenha algum período aquisitivo cadastrado...
         * É instanciado o último período aquisitivo registrado para o servidor
         * A data de ínicio do novo período aquisitivo será a data final do último período registrado, acrescido mais 1 dia
         * A data final do novo período aquisitivo será a data final do período aquisitivo anterior + 1 dia, acrescido do tempo definido pelo regime do servidor
         * É registrado o novo período aquisitivo
         */
        $oPeriodoAquisitivoFeriasAnterior = new PeriodoAquisitivoFerias($oServidorPeriodoAquisitivo->ultimo_periodo_aquisitivo);
        $oPeriodoAquisitivoFeriasNovo     = new PeriodoAquisitivoFerias();
        $oPeriodoAquisitivoFeriasNovo->setDataInicial( clone $oPeriodoAquisitivoFeriasAnterior->getDataFinal());
        $oPeriodoAquisitivoFeriasNovo->getDataInicial()->modificarIntervalo('+1 day');
        $oPeriodoAquisitivoFeriasAnterior->getDataFinal()->modificarIntervalo("+{$oServidorPeriodoAquisitivo->meses_periodo_aquisitivo} months");
        $oPeriodoAquisitivoFeriasNovo->setDataFinal($oPeriodoAquisitivoFeriasAnterior->getDataFinal());
        $oPeriodoAquisitivoFeriasNovo->setServidor($oPeriodoAquisitivoFeriasAnterior->getServidor());
        $oPeriodoAquisitivoFeriasNovo->setDiasDireito($oServidorPeriodoAquisitivo->dias_periodo_gozo);
        $oPeriodoAquisitivoFeriasNovo->setFaltasPeriodoAquisitivo(0);
        $oPeriodoAquisitivoFeriasNovo->salvar();

      } else {

        /**
         * Caso o servidor não tenha nenhum período aquisitivo...
         * ...a data de admissão será a data do primeiro período aquisitivo.
         * A data final séra a data de admissão, acrescido do tempo definido pelo regime, em meses.
         * Os dias de gozo tbem estão definidos pelo regime, mas pode ser alterado posteriormente
         */

        $iAnoAtual             = $dataii_ano;
        $iMesPeriodoAquisitivo = date("m", strtotime($oServidorPeriodoAquisitivo->data_admissao));
        $iDiaPeriodoAquisitivo = date("d", strtotime($oServidorPeriodoAquisitivo->data_admissao));

        try {
          $oDataInicial = new DBDate($iAnoAtual . "-" . $iMesPeriodoAquisitivo . "-" . $iDiaPeriodoAquisitivo);
        } catch (ParameterException $e) {
          $oDataInicial = new DBDate($iAnoAtual . "-" . $iMesPeriodoAquisitivo . "-" . ($iDiaPeriodoAquisitivo-1));
        }

        $oDataFinal   = clone $oDataInicial;
        $oDataFinal->modificarIntervalo("+{$oServidorPeriodoAquisitivo->meses_periodo_aquisitivo} months");

        $oPeriodoAquisitivoFerias = new PeriodoAquisitivoFerias();
        $oPeriodoAquisitivoFerias->setServidor( ServidorRepository::getInstanciaByCodigo($oServidorPeriodoAquisitivo->matricula, $oDataInicial->getAno(), $oDataInicial->getMes() ) );
        $oPeriodoAquisitivoFerias->setDataInicial($oDataInicial);
        $oPeriodoAquisitivoFerias->setDataFinal($oDataFinal);
        $oPeriodoAquisitivoFerias->setDiasDireito($oServidorPeriodoAquisitivo->dias_periodo_gozo);
        $oPeriodoAquisitivoFerias->setFaltasPeriodoAquisitivo(0);
        $oPeriodoAquisitivoFerias->salvar();

      }

    }

  } catch (Exception $oException) {
    $sqlerro = true;
    $retorno = $oException->getMessage();
  }

}

if(isset($desprocess) && $desprocess == "true") {

  /**
   * Verifica se ja foi gerado empenhos da folha para a competência atual,
   * se existir a folha não pode ser desprocessada.
   *
   * Adicionado join com outras tabelas para buscar por instituicao
   */
  $oDaoRhEmpenhoFolhaRhEmpRubrica    = new cl_rhempenhofolharhemprubrica();
  $sWhereRhEmpenhoFolhaRhEmpRubrica  = "     rh72_anousu = {$dataii_ano}";
  $sWhereRhEmpenhoFolhaRhEmpRubrica .= " and rh72_mesusu = {$dataii_mes}";
  $sWhereRhEmpenhoFolhaRhEmpRubrica .= " and rh73_instit = ". db_getsession("DB_instit");
  $sSqlRhEmpenhoFolhaRhEmpRubrica    = $oDaoRhEmpenhoFolhaRhEmpRubrica->sql_query(null, "distinct rh72_sequencial", null, $sWhereRhEmpenhoFolhaRhEmpRubrica);
  $rsRhEmpenhoFolhaRhEmpRubrica      = db_query($sSqlRhEmpenhoFolhaRhEmpRubrica);

  if (pg_num_rows($rsRhEmpenhoFolhaRhEmpRubrica) > 0) {

    $sqlerro = true;
    $retorno = "Não foi possível desprocessar o fechamento da folha, pois o mesmo possui empenhos gerados.";
  }

  /**
   * Realiza a exclusao de todas as folhas.
   */
  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    try {

      $oCompetencia        = new DBCompetencia($dataii_ano, $dataii_mes);

      $oDaoRhHistoricoPonto = new cl_rhhistoricoponto();
      $oDaoRhHistoricoPonto->excluir(null, "rh144_folhapagamento in (select rh141_sequencial from rhfolhapagamento where rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes})");

      if ($oDaoRhHistoricoPonto->erro_status == "0") {
        throw new DBException( _M(FolhaPagamento::MENSAGENS . "erro_excluir_historico_ponto"));
      }

      $oDaoRhHistoricoCalculo = new cl_rhhistoricocalculo();
      $oDaoRhHistoricoCalculo->excluir(null, "rh143_folhapagamento in (select rh141_sequencial from rhfolhapagamento where rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes})");

      if ($oDaoRhHistoricoCalculo->erro_status == "0") {
        throw new DBException( _M(FolhaPagamento::MENSAGENS . "erro_excluir_historico_calculo"));
      }

      $oDaoRhHistoricoPensao = new cl_rhhistoricopensao();
      $oDaoRhHistoricoPensao->excluir(null, "rh145_rhfolhapagamento in (select rh141_sequencial from rhfolhapagamento where rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes})");

      if ($oDaoRhHistoricoPensao->erro_status == "0") {
        throw new DBException("Não foi possivel excluir o histórico de pensões");
      }

      $oDaoFolhaPagamentoGeracao = new cl_folhapagamentogeracao();
      $oDaoFolhaPagamentoGeracao->excluir(null, "rh146_folhapagamento in (select rh141_sequencial from rhfolhapagamento where rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes})");

      if ($oDaoFolhaPagamentoGeracao->erro_status == "0") {
        throw new DBException( "Não foi possível excluir a geração da folha de pagamento" . $oDaoFolhaPagamentoGeracao->erro_msg);
      }

      $oDaoAssentamentoLoteRegistroPonto = new cl_assentaloteregistroponto();
      $oDaoAssentamentoLoteRegistroPonto->excluir(null, "rh160_loteregistroponto in (select rh155_sequencial from loteregistroponto where rh155_ano = {$dataii_ano} and rh155_mes = {$dataii_mes})");

      if ($oDaoAssentamentoLoteRegistroPonto->erro_status == "0") {
        throw new DBException( "Não foi possível excluir o vínculo do registroponto com a folha apgamento." . $oDaoLoteRegistroPonto->erro_msg);
      }

      $oDaoLoteRegistroPonto = new cl_loteregistropontorhfolhapagamento();
      $oDaoLoteRegistroPonto->excluir(null, "rh162_rhfolhapagamento in (select rh141_sequencial from rhfolhapagamento where rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes})");

      if ($oDaoLoteRegistroPonto->erro_status == "0") {
        throw new DBException( "Não foi possível excluir o vínculo do registroponto com a folha apgamento." . $oDaoLoteRegistroPonto->erro_msg);
      }

      $oDaoFolhaPagamento = new cl_rhfolhapagamento;
      $oDaoFolhaPagamento->excluir(null, "rh141_anousu = {$dataii_ano} and rh141_mesusu = {$dataii_mes}");

      if ($oDaoFolhaPagamento->erro_status == "0"){
        throw new DBException( _M(FolhaPagamento::MENSAGENS . "erro_excluir") . $oDaoFolhaPagamento->erro_msg);
      }

    } catch (Exception $oException) {

      $retorno = $oException->getMessage();
      $sqlerro = true;
    }
  }

  $arr_tabelas_exclui = Array(
    "AFASTA","AJUSTEIR","BASESR","BASES","CADFERIA","CALFOLHA","CEDULAS","CFPESS",
    "CHEQUES","DEPEND","DESCONTO","PESDIVER","EFETIV","FOLHAEMP","GERFSAL",
    "GERFFER","GERFS13","GERFADI","GERFFX","GERFRES","GERFCOM","HISTORIC","INSSIRF",
    "IPE","LANDESC","LOTATIV","PENSAORETENCAO","PENSAO","PONTOFX","PONTOFS","PONTOFA","PONTOF13","PONTOFR",
    "PONTOFE","PONTOCOM","VTFDIAS","VTFFUNC ","PREVIDEN","REPOSIC","PESSOAL",
    "PROGRESS","PADROES","CARGO","FUNCAO","RUBRICAS","RESCISAO","MOVCASADASSEFIP",
    "TIPOASSEEXTERNO", "CODMOVSEFIP","VTFEMPR","RHPESSOALMOV","RHVISAVALECAD", "TIPOASSEFINANCEIRO"
  );
  $arr_siglass_exclui = Array(
    "r45","r61","r09","r08","r30","r51","r05","r11","r12","r03","r27","r07","r57",
    "r42","r14","r31","r35","r22","r53","r20","r48","r25","r33","r36","r28","r41","rh77","r52",
    "r90","r10","r21","r34","r19","r29","r47","r63","r17","r60","r64","r01","r24",
    "r02","r65","r37","r06","r59","r67","rh167","r66","r16","rh02","rh49","rh165"
  );
  $exist_virada = false;
  $result_exist_virada = $clcfpess->sql_record($clcfpess->sql_query_file(null,null,null,"*",null,"r11_instit<>".db_getsession("DB_instit")." and r11_anousu=$dataii_ano and r11_mesusu=$dataii_mes"));
  if ($clcfpess->numrows>0){
    $exist_virada = true;
  }

    if (!$sqlerro){

        /* Modification PADRS cancela virada mensal da folha */

        for($i=0; $i<count($arr_tabelas_exclui); $i++){

            if ($sqlerro) {
                break;
            }

      $table = $arr_tabelas_exclui[$i];
      $sigla = $arr_siglass_exclui[$i];
      if(db_at(strtoupper($sigla),"R61_R51_R05_R12_R03_R27_R57_R42_R25_R28_R41_R52_R63_R17_R60_R64_R65_R37_R66_R67_") !='0' && $exist_virada == true){
        continue;
      }

      if($table == "RHPESSOALMOV"){
        $result_max_min = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query_file(null,null,"max(rh02_seqpes) as maximo,min(rh02_seqpes) as minimo","","rh02_instit = ".db_getsession("DB_instit")." and rh02_anousu = ".$dataii_ano." and rh02_mesusu = ".$dataii_mes));
        db_fieldsmemory($result_max_min, 0);
        if(trim($minimo) != "" && trim($maximo) != ""){

          $retorno = $clrhpesbanco->excluir(null,"rh44_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh44_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpesbanco->erro_status==0 && $result_rhpesbanco == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESBANCO</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESBANCO.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          /**
           * Exclui da tabela rhpessoalmovcontabancaria
           */
          $retorno = $clrhpessoalmovcontabancaria->excluir(null, "rh138_rhpessoalmov between {$minimo} and {$maximo} and rh138_instit = " . db_getsession("DB_instit"));
          if ($clrhpessoalmovcontabancaria->erro_status == 0 && $result_rhpessoalmovcontabancaria == false) {
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">PHPESSOALMOVCONTABANCARIA</font>');</script>";
            $retorno = "Erro ao excluir na tabela PHPESSOALMOVCONTABANCARIA.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpespadrao = $clrhpespadrao->excluir(null,"rh03_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh03_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpespadrao->erro_status==0 && $result_rhpespadrao == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESPADRAO</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESPADRAO.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpesrescisao = $clrhpesrescisao->excluir(null,"rh05_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh05_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpesrescisao->erro_status==0 && $result_rhpesrescisao == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESRESCISAO</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESRESCISAO.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhinssoutros = $clrhinssoutros->excluir(null,"rh51_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh51_seqpes) = ".db_getsession("DB_instit"));
          if($clrhinssoutros->erro_status==0 && $result_rhinssoutros == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHINSSOUTROS</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHINSSOUTROS.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpesponto = $clrhpesponto->excluir(null,"rh06_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh06_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpesponto->erro_status==0 && $result_rhpesponto == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESPONTO</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESPONTO.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpesprogres = $clrhpesprogres->excluir(null,"rh07_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh07_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpesprogres->erro_status==0 && $result_rhpesprogres == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESPROGRES</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESPROGRES.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpescargo = $clrhpescargo->excluir(null,"rh20_instit = ".db_getsession("DB_instit")." and rh20_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh20_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpescargo->erro_status==0 && $result_rhpescargo == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESCARGO</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESCARGO.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }

          $result_rhpeslocaltrab = $clrhpeslocaltrab->excluir(null,"rh56_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh56_seqpes) = ".db_getsession("DB_instit"));
          if($clrhpeslocaltrab->erro_status==0 && $result_rhpeslocaltrab == false){
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESLOCALTRAB</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESLOCALTRAB.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }


          $result_rhpesrubcalc = $clrhpesrubcalc->excluir(null,"rh65_seqpes between ".$minimo." and ".$maximo." and fc_instit_seqpes(rh65_seqpes) = ".db_getsession("DB_instit"));

          if ($clrhpesrubcalc->erro_status==0 && $result_rhpesrubcalc == false) {
            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHPESRUBCALC</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHPESRUBCALC.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }
        }
      }

      flush();
      echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">".$table."</font>');</script>";
      $ano_mes = $dataii_ano."/".$dataii_mes;
      $sigla .= "_";
      $where = bb_condicaosubpesproc($sigla,$ano_mes);
      if ($table != 'VTFEMPR' && $table != "PENSAORETENCAO" && $table != "CADFERIA" && $table != "AFASTA") {

        if ($table == "INSSIRF") {

          $sql_exc = "delete from regimeprevidenciainssirf where rh129_codigo IN (select r33_codigo from inssirf $where);";
          $res_exc = db_query($sql_exc, null, "");

          $sSqlAtoPrevidencia = "delete from atolegalprevidenciainssirf where rh180_inssirf IN (select r33_codigo from inssirf $where);";
          $res_exc            = db_query($sSqlAtoPrevidencia, null, "");
        }

        if ($table == 'PENSAO') {

          $result_rhpensaocontabancaria = $clpensaocontabancaria->excluir(null, "rh139_anousu = $dataii_ano  and  rh139_mesusu = $dataii_mes");

          if (!$result_rhpensaocontabancaria) {

            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">PENSAOCONTABANCARIA</font>');</script>";
            $retorno = "Erro ao excluir na tabela PENSAOCONTABANCARIA.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
            $sqlerro = true;
            break;
          }
        }

        if ($table == "BASES") {

          $clbasesrhcadregime = new cl_baserhcadregime();
          $rsBasesRhCadRegime = $clbasesrhcadregime->excluir(null, "rh158_ano = $dataii_ano and rh158_mes = $dataii_mes and rh158_instit = " . DB_getsession("DB_instit"));

          if(!$rsBasesRhCadRegime) {

            flush();
            echo "<script>parent.js_mostrardiv(true,'Aguarde, processando tabela <font color=\"red\">RHCADREGIME</font>');</script>";
            $retorno = "Erro ao excluir na tabela RHCADREGIME.\\n\\nCancelamento da folha cancelado.";
            $sqlerro = true;
            break;
          }

        }

        $sql_exc = "delete from  $table  $where";

        $res_exc = db_query($sql_exc, null, "");

        if($res_exc == false){

          //  die($sql_exc);
          $retorno = "Erro ao excluir na tabela ".$table.".\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
          $sqlerro = true;
          break;
        }

      } else if ($table == "PENSAORETENCAO") {

        $sql_exc10   = "delete from pensaoretencao ";
        $sql_exc10  .= " where  rh77_anousu = {$dataii_ano}    ";
        $sql_exc10  .= "   and  rh77_mesusu = {$dataii_mes}      ";
        $sql_exc10  .= "   and  rh77_regist in ( select rh02_regist ";
        $sql_exc10  .= "                           from rhpessoalmov ";
        $sql_exc10  .= "                          where rh02_anousu = {$dataii_ano}";
        $sql_exc10  .= "                            and rh02_mesusu = {$dataii_mes} ";
        $sql_exc10  .= "                            and rh02_instit = ".db_getsession("DB_instit").")";
        $res_exc10 = db_query($sql_exc10, null, "");
        if($res_exc10 == false){

          $retorno = pg_last_error()."\\nErro ao excluir na tabela pensaoretencao.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
          $sqlerro = true;
          break;

        }

      } else if ($table == "CADFERIA") {

        $sSqlDeletaCadFeria  = "delete from cadferia";
        $sSqlDeletaCadFeria .= " where r30_anousu = {$dataii_ano} ";
        $sSqlDeletaCadFeria .= "   and r30_mesusu = {$dataii_mes} ";
        $sSqlDeletaCadFeria .= "   and r30_regist in ( select rh02_regist ";
        $sSqlDeletaCadFeria .= "                         from rhpessoalmov ";
        $sSqlDeletaCadFeria .= "                        where rh02_anousu = {$dataii_ano} ";
        $sSqlDeletaCadFeria .= "                          and rh02_mesusu = {$dataii_mes} ";
        $sSqlDeletaCadFeria .= "                          and rh02_instit = ".db_getsession("DB_instit").")";
        $rsDeletaCadFeria    = db_query($sSqlDeletaCadFeria, null, "");
        if ($rsDeletaCadFeria == false) {

          $retorno = pg_last_error()."\\nErro ao excluir na tabela cadferia.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
          $sqlerro = true;
          break;
        }
      } else if ($table == "AFASTA") {

        $sSqlDeleteAfastaAssenta  = " delete                                                  ";
        $sSqlDeleteAfastaAssenta .= "   from afastaassenta                                    ";
        $sSqlDeleteAfastaAssenta .= "  where h81_afasta in (select r45_codigo                 ";
        $sSqlDeleteAfastaAssenta .= "                         from afasta                     ";
        $sSqlDeleteAfastaAssenta .= "                        where r45_anousu = {$dataii_ano} ";
        $sSqlDeleteAfastaAssenta .= "                          and r45_mesusu = {$dataii_mes} ";
        $sSqlDeleteAfastaAssenta .= "                      )                                  ";
        $rsDeleteAfastaAssenta    = db_query($sSqlDeleteAfastaAssenta);
        if(!$rsDeleteAfastaAssenta) {

          $retorno = pg_last_error()."\\nErro ao excluir na tabela afastaassenta.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
          $sqlerro = true;
          break;
        }

        $sSqlDeletaAfasta  = "delete from afasta";
        $sSqlDeletaAfasta .= " where r45_anousu = {$dataii_ano} ";
        $sSqlDeletaAfasta .= "   and r45_mesusu = {$dataii_mes} ";
        $sSqlDeletaAfasta .= "   and r45_regist in ( select rh02_regist ";
        $sSqlDeletaAfasta .= "                         from rhpessoalmov ";
        $sSqlDeletaAfasta .= "                        where rh02_anousu = {$dataii_ano}";
        $sSqlDeletaAfasta .= "                          and rh02_mesusu = {$dataii_mes} ";
        $sSqlDeletaAfasta .= "                          and rh02_instit = ".db_getsession("DB_instit").")";
        $rsDeletaAfasta    = db_query($sSqlDeletaAfasta, null, "");
        if (!$rsDeletaAfasta) {

          $retorno = pg_last_error()."\\nErro ao excluir na tabela afasta.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
          $sqlerro = true;
          break;
        }

      } else {



        $sql_dias = "select *
          from vtffunc
          inner join rhpessoalmov on rh02_anousu = r17_anousu
          and rh02_mesusu = r17_mesusu
          and rh02_regist = r17_regist
          where rh02_anousu = $dataii_ano
          and rh02_mesusu = $dataii_mes
          and rh02_instit = ".db_getsession("DB_instit")."
          limit 1";
$res_dias = db_query("$sql_dias");
if(pg_numrows($res_dias) > 0){
  $sql_exc1 = "delete from vtfdias
    where r63_anousu = $dataii_ano
    and r63_mesusu = $dataii_mes
    and r63_regist in ( select rh02_regist
    from rhpessoalmov
    where rh02_anousu =  $dataii_ano
    and rh02_mesusu = $dataii_mes
    and rh02_instit = ".db_getsession("DB_instit")."
  )";
$res_exc1 = db_query($sql_exc1, null, "");
if($res_exc1 == false){
  //die($sql_exc);
  $retorno = "Erro ao excluir na tabela VTFDIAS.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
  $sqlerro = true;
  break;
}

$sql_exc2 = "delete from vtffunc
  where r17_anousu = $dataii_ano
  and r17_mesusu = $dataii_mes
  and r17_regist in ( select rh02_regist
  from rhpessoalmov
  where rh02_anousu =  $dataii_ano
  and rh02_mesusu = $dataii_mes
  and rh02_instit = ".db_getsession("DB_instit")."
)";
$res_exc2 = db_query($sql_exc2, null, "");
if($res_exc2 == false){
  //die($sql_exc);
  $retorno = "Erro ao excluir na tabela VTFFUNC.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
  $sqlerro = true;
  break;
}
}

$sql_exc = "delete from  $table  $where";

$res_exc = db_query($sql_exc, null, "");
//echo "<BR> $res_exc";
if($res_exc == false){
  //  die($sql_exc);
  $retorno = "Erro ao excluir na tabela ".$table.".\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
  $sqlerro = true;
  break;
}

      }

    }
  }


  /**
   * Remove os lotes dos registros do ponto, conforme a instituição e a competência da folha.
   */
  if ($sqlerro == false) {

    echo "<script>parent.js_mostrardiv(true,'Removendo os <font color=\"red\">lotes dos registros do ponto</font>');</script>";

    $oDaoLoteRegistroPonto  = new cl_loteregistroponto();
    $oInstituicao           = InstituicaoRepository::getInstituicaoSessao();

    $sWhere                 = "    rh155_instit = {$oInstituicao->getCodigo()} ";
    $sWhere                .= "and rh155_ano    = {$dataii_ano}    ";
    $sWhere                .= "and rh155_mes    = {$dataii_mes}    ";
    $sSqlLoteRegistroPonto  =  $oDaoLoteRegistroPonto->sql_query_file(null, "rh155_sequencial", null, $sWhere);
    $rsLoteRegistroPonto    = db_query($sSqlLoteRegistroPonto);

    if (!$rsLoteRegistroPonto) {

      $retorno = "Erro ao excluir os lotes dos registros do ponto.\\n\\nCancelamento da folha cancelado.\\nPeríodo: ".$periodoini1." a ".$periodoini2.".";
      $sqlerro = true;
    }

    for ($iIndice = 0; $iIndice < pg_num_rows($rsLoteRegistroPonto); $iIndice++) {

      try {

        $iSequencial        = db_utils::fieldsMemory($rsLoteRegistroPonto, $iIndice)->rh155_sequencial;
        $oLoteRegistroPonto = LoteRegistrosPontoRepository::getInstanceByCodigo($iSequencial);
        LoteRegistrosPontoRepository::remover($oLoteRegistroPonto);

      } catch (Exception $ex) {

        $retorno = $ex->getMessage();
        $sqlerro = true;
        break;
      }
    }
  }

  flush();
  if($sqlerro == true){
    echo "<script>parent.js_mostrardiv(true,'Erro no processamento');</script>";
    db_msgbox($retorno);
  }else{
    echo "<script>parent.js_mostrardiv(true,'Processamento concluído com sucesso');</script>";
    db_msgbox("Cancelamento da folha efetuado com sucesso.\\n\\nPeríodo cancelado: ".$periodoini1." a ".$periodoini2.".\\nPeríodo atual: ".$periodofim1." a ".$periodofim2."");
  }

}else{

  /**
   *
   */
  /**
   * Realiza o fechamento da folha salário, complementar e suplementar se as mesmas se encontram abertas.
   */
  if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    try {

      $oCompetencia        = new DBCompetencia($dataii_ano, $dataii_mes);
      $lFolhaSalarioAberta = FolhaPagamentoSalario::hasFolhaAberta($oCompetencia);

      if ($lFolhaSalarioAberta) {

        $iCodigoFolhaSalario    = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SALARIO, true, $oCompetencia);
        $oFolhaPagamentoSalario = new FolhaPagamentoSalario($iCodigoFolhaSalario);
        $oFolhaPagamentoSalario->fechar();
      }

      $lFolhaSuplementarAberta = FolhaPagamentoSuplementar::hasFolhaAberta($oCompetencia);

      if ($lFolhaSuplementarAberta) {

        $iCodigoFolhaSuplementar      = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR, true, $oCompetencia);
        $oFolhaPagamentoSuplementar   = new FolhaPagamentoSuplementar($iCodigoFolhaSuplementar);
        $oFolhaPagamentoSuplementar->fechar();
      }

      $lFolhaSalarioComplementar = FolhaPagamentoComplementar::hasFolhaAberta($oCompetencia);

      if ($lFolhaSalarioComplementar) {

        $iCodigoFolhaComplementar    = FolhaPagamento::getCodigoFolha(FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR, true, $oCompetencia);
        $oFolhaPagamentoComplementar = new FolhaPagamentoComplementar($iCodigoFolhaComplementar);
        $oFolhaPagamentoComplementar->fechar();
      }
    } catch (Exception $oException) {

      $retorno = $oException->getMessage();
      $sqlerro = true;
    }
  }

  // Clcula segundo perodo de frias.
  function db_ferias($registro){
    global $clcadferia;

    global $dataii_dia;
    global $dataii_mes;
    global $dataii_ano;

    global $dataif_dia;
    global $dataif_mes;
    global $dataif_ano;

    global $datafi_dia;
    global $datafi_mes;
    global $datafi_ano;

    global $dataff_dia;
    global $dataff_mes;
    global $dataff_ano;

    global $periodoini1;
    global $periodoini2;
    global $periodofim1;
    global $periodofim2;

    $mes = $datafi_mes;
    $ano = $datafi_ano;

    $anomes = $ano.$mes;

    $result_dados = $clcadferia->sql_record($clcadferia->sql_query_file(null,"*","","r30_anousu = ".$ano." and r30_mesusu = ".$mes." and r30_regist = ".$registro));
    if($clcadferia->numrows > 0){
      db_fieldsmemory($result_dados, 0,0,1);
      $pagt_1_periodo = substr($r30_proc1,0,4).substr($r30_proc1,5,2);
      $pagt_2_periodo = substr($r30_proc2,0,4).substr($r30_proc2,6,2);
      $diff_1_pagamen = $r30_proc1d;
      $diff_2_pagamen = $r30_proc2d;

      $F019 = $r30_dias1;
      $F020 = $r30_abono;
      $inici_1_periodo = $r30_per1i;
      $final_1_periodo = $r30_per1f;
      $inici_2_periodo = $r30_per2i;
      $final_2_periodo = $r30_per2f;

      $numdias = db_dias_mes($ano,$mes);

      $arr_data1 = split("-",$inici_1_periodo);
      $arr_data2 = split("-",$final_1_periodo);

      $anomesi = $arr_data1[1].$arr_data1[2];
      $anomesf = $arr_data2[1].$arr_data2[2];

    }
  }

  // VARIVEIS FIXAS:
  // $sql1 = SQL de onde vem os dados para virada de ms da folha.
  // $dataii_ano = Ano corrente da folha.
  // $dataii_mes = Ms corrente da folha.
  // $datafi_ano = Ano novo da folha.
  // $datafi_mes = Ms novo da folha.
  // $retorno    = Retorna: 1, se no existem dados para incluir.
  //                        2, se incluses foram efetuadas com sucesso.
  //                        Mensagens informando erros.
  // $sqlerro    = True, caso tenha ocorrido erro em alguma incluso.

  $mes = $datafi_mes;
  $ano = $datafi_ano;
  //$sqlerro = false;
  $instit = db_getsession("DB_instit");
  if($sqlerro == false){
    $sql1 = $clpadroes->sql_query_file ($dataii_ano,$dataii_mes,null,null,$instit);
    $retorno = db_incluir_sql($sql1,$clpadroes,"padroes");
    //echo "<br> 0.2 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 2";
    }
  }

  if($sqlerro == false){

    $sql1 = $clcfpess->sql_query_file ($dataii_ano,$dataii_mes,$instit);
    $retorno = db_incluir_sql($sql1,$clcfpess,"cfpess");
    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
    }
  }

  if ($sqlerro == false) {

    $result_test = $clcedulas->sql_record($clcedulas->sql_query_file (null,"*","","r05_anousu = ".$ano." and r05_mesusu = ".$mes));
    if ($clcedulas->numrows==0){
      $sql1 = $clcedulas->sql_query_file (null,"*","","r05_anousu = ".$dataii_ano." and r05_mesusu = ".$dataii_mes);
      $retorno = db_incluir_sql($sql1,$clcedulas,"cedulas");
      //echo "<br> 0.5 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 5";
      }
    }
  }

  if ($sqlerro == false) {

    $sql1 = $clpessoal->sql_query_file (null,null,null,"*",null,"r01_anousu = $dataii_ano and r01_mesusu = $dataii_mes and r01_instit = $instit ");
    $retorno = db_incluir_sql($sql1,$clpessoal,"pessoal");
    //echo "<br> 0.6 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 6";
    }
  }



  if ($sqlerro == false) {

    $sWhereAfasta  = "     r45_anousu = ".$ano;
    $sWhereAfasta .= " and r45_mesusu = ".$mes;
    $sWhereAfasta .= " and exists (select 1 ";
    $sWhereAfasta .= "              from rhpessoal ";
    $sWhereAfasta .= "             where rh01_regist = r45_regist ";
    $sWhereAfasta .= "               and rh01_instit = ".db_getsession("DB_instit").")";
    $result_test = $clafasta->sql_record($clafasta->sql_query_file (null,"*","",$sWhereAfasta));

    if ($clafasta->numrows==0) {

      // Desativa triggers.
      //echo("<BR><BR>ALTER TABLE $trigger DISABLE TRIGGER ALL;");
      // $des_trigger = @db_query("ALTER TABLE afasta DISABLE TRIGGER ALL;");

      // Testa se ocorreu erro ao desativar triggers.
      //echo "<br> 0 - " . pg_last_error();
      //if($des_trigger == false){
     //   $retorno = "Erro ao desativar triggers da tabela afasta.\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
     //   $sqlerro = true;
     // }

      /**
       * Pegamos os afastamentos com assentamentos vínculados para mantermos os novos vínculos após a virada mensal
       */
      $sWhereAfastamentoAssentamento  = "     r45_anousu = ".$dataii_ano;
      $sWhereAfastamentoAssentamento .= " and r45_mesusu = ".$dataii_mes;
      $sWhereAfastamentoAssentamento .= " and exists (select 1 ";
      $sWhereAfastamentoAssentamento .= "              from rhpessoal ";
      $sWhereAfastamentoAssentamento .= "             where rh01_regist = r45_regist ";
      $sWhereAfastamentoAssentamento .= "               and rh01_instit = ".db_getsession("DB_instit").")";
      $sSqlAfastamentoAssentamento    = $clafasta->sql_query_left_join_assenta(null, "afastaassenta.*, afasta.*", null, $sWhereAfastamentoAssentamento);

      $rsAfastamentoAssentamento      = db_query($sSqlAfastamentoAssentamento);

      if($rsAfastamentoAssentamento && pg_num_rows($rsAfastamentoAssentamento) > 0) {

        /**
         * Percorremos a lista dos afastamentos para incluir um novo registro
         * para a próxima competência na tabela afasta e também na tabela afastaassenta
         */
        for ($nIndAfastamentoAssentamento=0; $nIndAfastamentoAssentamento < pg_num_rows($rsAfastamentoAssentamento) ; $nIndAfastamentoAssentamento++) {

          $oStdAfastamentoAssentamento = db_utils::fieldsMemory($rsAfastamentoAssentamento, $nIndAfastamentoAssentamento);

          $oDaoAfastamentoNovaCompetencia             = new cl_afasta;
          $oDaoAfastamentoNovaCompetencia->r45_anousu = $ano;
          $oDaoAfastamentoNovaCompetencia->r45_mesusu = $mes;
          $oDaoAfastamentoNovaCompetencia->r45_regist = $oStdAfastamentoAssentamento->r45_regist;
          $oDaoAfastamentoNovaCompetencia->r45_dtafas = $oStdAfastamentoAssentamento->r45_dtafas;
          $oDaoAfastamentoNovaCompetencia->r45_dtreto = $oStdAfastamentoAssentamento->r45_dtreto;
          $oDaoAfastamentoNovaCompetencia->r45_situac = $oStdAfastamentoAssentamento->r45_situac;
          $oDaoAfastamentoNovaCompetencia->r45_dtlanc = $oStdAfastamentoAssentamento->r45_dtlanc;
          $oDaoAfastamentoNovaCompetencia->r45_codafa = (!$oStdAfastamentoAssentamento->r45_codafa) ? ' ' : $oStdAfastamentoAssentamento->r45_codafa;
          $oDaoAfastamentoNovaCompetencia->r45_codret = (!$oStdAfastamentoAssentamento->r45_codret) ? ' ' : $oStdAfastamentoAssentamento->r45_codret;
          $oDaoAfastamentoNovaCompetencia->r45_obs    = $oStdAfastamentoAssentamento->r45_obs;

          $oDaoAfastamentoNovaCompetencia->incluir(null);

          if($oDaoAfastamentoNovaCompetencia->erro_status == "0") {
            $retorno = "Erro ao incluir na tabela afasta.\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\n".str_replace("\n","",@pg_last_error());
            $sqlerro = true;
          }

          /**
           * Se houver vínculo entre afastamento e assentamento cria um novo para a nova competência
           */
          if($sqlerro != true && !empty($oStdAfastamentoAssentamento->h81_afasta)) {

            $oDaoAfastaAssenta              = new cl_afastaassenta;
            $oDaoAfastaAssenta->h81_assenta = $oStdAfastamentoAssentamento->h81_assenta;
            $oDaoAfastaAssenta->h81_afasta  = $oDaoAfastamentoNovaCompetencia->r45_codigo;
            $oDaoAfastaAssenta->incluir();

            if($oDaoAfastaAssenta->erro_status == "0") {
              $retorno = "Erro ao incluir na tabela afastaassenta.\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\n".str_replace("\n","",@pg_last_error());
              $sqlerro = true;
            }
          }
        }
      }

      // Ativa triggers.
      //echo("<BR><BR>ALTER TABLE $trigger ENABLE TRIGGER ALL;");
      //$ati_trigger = @db_query("ALTER TABLE afasta ENABLE TRIGGER ALL;");

      // Testa se ocorreu erro ao ativar triggers.
      // echo "<br> 0 - " . pg_last_error();
      //if($ati_trigger == false){
      //  $retorno = "Erro ao ativar triggers da tabela afasta.\\n\\nFechamento da folha cancelado.\\nPerodo: ".$periodoini1." a ".$periodoini2.".\\n\\nContate o suporte.";
      //  $sqlerro = true;
      //}
    }

  }

  if($sqlerro == false){

    /**
     * @todo rotina que valida a cadferia deverá ser retirada quando o novo de cadastro de férias estiver estável.
     */
    $sWhereCadFeria  = "     r30_anousu = ".$ano;
    $sWhereCadFeria .= " and r30_mesusu = ".$mes;
    $sWhereCadFeria .= " and exists (select 1 ";
    $sWhereCadFeria .= "               from rhpessoal ";
    $sWhereCadFeria .= "              where rh01_regist = r30_regist ";
    $sWhereCadFeria .= "                and rh01_instit = ".db_getsession("DB_instit").")";
    $result_test = $clcadferia->sql_record($clcadferia->sql_query_file (null,"*","",$sWhereCadFeria));

    if ($clcadferia->numrows==0) {

      $sWhereCadFeria  = "     r30_anousu = ".$dataii_ano;
      $sWhereCadFeria .= " and r30_mesusu = ".$dataii_mes;
      $sWhereCadFeria .= " and exists (select 1 ";
      $sWhereCadFeria .= "               from rhpessoal ";
      $sWhereCadFeria .= "              where rh01_regist = r30_regist ";
      $sWhereCadFeria .= "                and rh01_instit = ".db_getsession("DB_instit").")";
      $sql1 = $clcadferia->sql_query_file (null,"*","",$sWhereCadFeria);
      $retorno = db_incluir_sql($sql1,$clcadferia,"cadferia");
      //echo "<br> 0.10 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 10";
      }

    }

  }

  if ($sqlerro == false) {

    $result_test = $clcheques->sql_record($clcheques->sql_query_file (null,"*","","r12_anousu = ".$ano." and r12_mesusu = ".$mes));
    if ($clcheques->numrows==0){

      $sql1 = $clcheques->sql_query_file (null,"*","","r12_anousu = ".$dataii_ano." and r12_mesusu = ".$dataii_mes);
      $retorno = db_incluir_sql($sql1,$clcheques,"cheques");
      //echo "<br> 0.11 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 11";
      }
    }
  }

  if($sqlerro == false){
    $result_test = $cldesconto->sql_record($cldesconto->sql_query_file ($ano,$mes,null));
    if ($cldesconto->numrows==0){
      $sql1 = $cldesconto->sql_query_file ($dataii_ano,$dataii_mes,null);
      $retorno = db_incluir_sql($sql1,$cldesconto,"desconto");
      //echo "<br> 0.12 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 12";
      }
    }
  }

  if($sqlerro == false){
    $sql1 = $clrubricas->sql_query_file ($dataii_ano,$dataii_mes,null,"*",null,"r06_anousu=$dataii_ano and r06_mesusu=$dataii_mes and r06_instit=$instit");
    $retorno = db_incluir_sql($sql1,$clrubricas,"rubricas");
    //echo "<br> 0.13 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 13";
    }
  }

  if ($sqlerro == false) {

    $sWhereFuncao  = " r37_anousu = {$dataii_ano} and r37_mesusu= {$dataii_mes} ";
    $sWhereFuncao .= " and not exists (select 1 from funcao v where v.r37_anousu = {$datafi_ano} and v.r37_mesusu = {$datafi_mes} and v.r37_codigo = funcao.r37_codigo)";
    $result_test = $clfuncao->sql_record($clfuncao->sql_query_file ($ano,$mes,null));
    if ($clfuncao->numrows==0){
      $sql1 = $clfuncao->sql_query_file ($dataii_ano,$dataii_mes,null);
      $retorno = db_incluir_sql($sql1,$clfuncao,"funcao");
      //echo "<br> 0.14 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 14";
      }
    }
  }

  if($sqlerro == false){
    $sql1 = $clvtfempr->sql_query_file ($dataii_ano,$dataii_mes,null,$instit);
    $retorno = db_incluir_sql($sql1,$clvtfempr,"vtfempr");
    //echo "<br> 0.15 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 15";
    }
  }

  if($sqlerro == false){
    $sql1 = $clpesdiver->sql_query_file ($dataii_ano,$dataii_mes,null,$instit);
    $retorno = db_incluir_sql($sql1,$clpesdiver,"pesdiver");
    //echo "<br> 0.16 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 16";
    }
  }

  if($sqlerro == false){
    $sql1 = $clbases->sql_query_file ($dataii_ano,$dataii_mes,null,$instit);
    $retorno = db_incluir_sql($sql1,$clbases,"bases");
    //echo "<br> 0.17 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 17";
    }
  }

  /* Modification PADRS - virada mensal basesvinculo */


  if($sqlerro == false){
    $sql1 = $clbasesr->sql_query_file ($dataii_ano,$dataii_mes,null,null,$instit);
    $retorno = db_incluir_sql($sql1,$clbasesr,"basesr");
    //echo "<br> 0.18 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 18";
    }
  }

  if($sqlerro == false){
    $sWhereEfetiv = "r57_anousu = {$ano} and r57_mesusu = {$mes} and r57_instit = {$instit}";
    $result_test = $clefetiv->sql_record($clefetiv->sql_query_file (null,null,null,null,"*",null,$sWhereEfetiv));
    if ($clefetiv->numrows==0){

      $sWhereEfetiv = "r57_anousu = {$dataii_ano} and r57_mesusu = {$dataii_mes} and r57_instit = {$instit}";
      $sql1 = $clefetiv->sql_query_file (null,null,null,null,"*",null,$sWhereEfetiv);
      $retorno = db_incluir_sql($sql1,$clefetiv,"efetiv");
      //echo "<br> 0.19 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 19";
      }
    }
  }


  if($sqlerro == false){
    $result_test = $clfolhaemp->sql_record($clfolhaemp->sql_query_file (null,"*","","r42_anousu = ".$ano." and r42_mesusu = ".$mes));
    if ($clfolhaemp->numrows==0){
      $sql1 = $clfolhaemp->sql_query_file (null,"*","","r42_anousu = ".$dataii_ano." and r42_mesusu = ".$dataii_mes);
      $retorno = db_incluir_sql($sql1,$clfolhaemp,"folhaemp");
      //echo "<br> 0.22 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 22";
      }
    }
  }

  if($sqlerro == false){
    $sql1 = $clinssirf->sql_query_file (null,null,"*","","r33_instit=$instit and  r33_anousu=".$dataii_ano." and r33_mesusu=".$dataii_mes);
    $retorno = db_incluir_sql($sql1,$clinssirf,"inssirf");
    //echo "<br> 0.23 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 23";
    }
  }

  if($sqlerro == false){
    $reuslt_test = $cllandesc->sql_record($cllandesc->sql_query_file (null,"*","","r28_anousu = ".$ano." and r28_mesusu = ".$mes));
    if ($cllandesc->numrows==0){
      $sql1 = $cllandesc->sql_query_file (null,"*","","r28_anousu = ".$dataii_ano." and r28_mesusu = ".$dataii_mes);
      $retorno = db_incluir_sql($sql1,$cllandesc,"landesc");
      //echo "<br> 0.24 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 24";
      }
    }
  }

  if($sqlerro == false){
    $Reuslt_test = $cllotativ->sql_record($cllotativ->sql_query_file ($ano,$mes,null));
    if ($cllotativ->numrows==0){
      $sql1 = $cllotativ->sql_query_file ($dataii_ano,$dataii_mes,null);
      $retorno = db_incluir_sql($sql1,$cllotativ,"lotativ");
      //echo "<br> 0.25 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 25";
      }
    }
  }

  if($sqlerro == false){

    $sWherePensao  = "     r52_anousu = ".$ano;
    $sWherePensao .= " and r52_mesusu = ".$mes;
    $sWherePensao .= " and exists (select 1 ";
    $sWherePensao .= "               from rhpessoal ";
    $sWherePensao .= "              where rh01_regist = r52_regist ";
    $sWherePensao .= "                and rh01_instit = ".db_getsession("DB_instit").")";
    $result_test   = db_query($clpensao->sql_query_file (null,null,null,null,"*",null,$sWherePensao));

    if ($result_test && pg_num_rows($result_test) == 0) {

      $sWherePensao  = "     r52_anousu = ".$dataii_ano;
      $sWherePensao .= " and r52_mesusu = ".$dataii_mes;
      $sWherePensao .= " and exists (select 1 ";
      $sWherePensao .= "               from rhpessoal ";
      $sWherePensao .= "              where rh01_regist = r52_regist ";
      $sWherePensao .= "                and rh01_instit = ".db_getsession("DB_instit").")";



      $sql1 = $clpensao->sql_query_file(null,null,null,null,"*",null,$sWherePensao);

      $retorno = db_incluir_sql($sql1, $clpensao, "pensao");

      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
      }

      /**
       * Busca dados para inserir na tabela pensaocontabancaria.
       */
    }
  }

  if ($sqlerro == false) {


    $sWherePensaoContaBancaria  = "rh139_anousu = $ano and rh139_mesusu = $mes";
    $sWherePensaoContaBancaria .= " and not exists (select 1 ";
    $sWherePensaoContaBancaria .= "               from pensaocontabancaria as pc ";
    $sWherePensaoContaBancaria .= "              where
      pc.rh139_anousu = $dataii_ano and pc.rh139_mesusu = $dataii_mes
      and pensaocontabancaria.rh139_anousu = pc.rh139_anousu
      and pensaocontabancaria.rh139_mesusu = pc.rh139_mesusu
      and pensaocontabancaria.rh139_regist = pc.rh139_regist
      and pensaocontabancaria.rh139_numcgm = pc.rh139_numcgm )         ";
$sWherePensaoContaBancaria .= " and exists (select 1 ";
$sWherePensaoContaBancaria .= "               from pensao as p ";
$sWherePensaoContaBancaria .= "              where p.r52_anousu = $dataii_ano
  and p.r52_mesusu = $dataii_mes
  and pensaocontabancaria.rh139_anousu = p.r52_anousu
  and pensaocontabancaria.rh139_mesusu = p.r52_mesusu
  and pensaocontabancaria.rh139_regist = p.r52_regist
  and pensaocontabancaria.rh139_numcgm = p.r52_numcgm )         ";
$sqlPensaoContaBancaria = $clpensaocontabancaria->sql_query_file (null,'pensaocontabancaria.*',null, $sWherePensaoContaBancaria);
$retorno = db_incluir_sql($sqlPensaoContaBancaria,$clpensaocontabancaria,"pensaocontabancaria");

if($retorno != '1' && $retorno != '2'){
  $sqlerro = true;
}
  }



  if($sqlerro == false){
    $sql1 = $clpontofa->sql_query_file (null,null,null,null,"* ",""," r21_anousu = $dataii_ano and r21_mesusu = $dataii_mes and r21_instit = $instit  ");
    $retorno = db_incluir_sql($sql1,$clpontofa,"pontofa");
    //echo "<br> 0.27 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 27";
    }
  }



  if($sqlerro == false){
    $sql1 = $clpontofx->sql_query_rescis (null,null,null,null," pontofx.* ",""," r90_anousu = $dataii_ano and r90_mesusu = $dataii_mes and rh05_recis is null and r90_instit = $instit "  );
    $retorno = db_incluir_sql($sql1,$clpontofx,"pontofx");
    //echo "<br> 0.28 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 28";
    }
  }

  if($sqlerro == false){
    $result_test = $cldepend->sql_record($cldepend->sql_query_file (null, null, null,null," * ", "", "r03_anousu = $ano and r03_mesusu = $mes "));
    if ($cldepend->numrows==0){
      $sql1 = $cldepend->sql_query_file (null, null, null,null," * ", "", "r03_anousu = $dataii_ano and r03_mesusu = $dataii_mes ");
      $retorno = db_incluir_sql($sql1,$cldepend,"depend");
      //echo "<BR> sql1 --> $sql1";
      //echo "<br> 0.29 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 29";
      }
    }
  }

  if($sqlerro == false){
    $sql1 = $clprogress->sql_query_file ($dataii_ano,$dataii_mes,null,null,null,$instit);
    $retorno = db_incluir_sql($sql1,$clprogress,"progress");
    //echo "<br> 0.30- " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 30";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrescisao->sql_query_file ($dataii_ano,$dataii_mes,null,null,null,null,$instit);
    $retorno = db_incluir_sql($sql1,$clrescisao,"rescisao");
    //echo "<br> 0.31 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 31";
    }
  }

  if($sqlerro == false){
    $result_test = $clreposic->sql_record($clreposic->sql_query_file ($ano,$mes,null,null));
    if ($clreposic->numrows==0){
      $sql1 = $clreposic->sql_query_file ($dataii_ano,$dataii_mes,null,null);
      $retorno = db_incluir_sql($sql1,$clreposic,"reposic");
      //echo "<br> 0.32 - " . pg_last_error();
      if($retorno != '1' && $retorno != '2'){
        $sqlerro = true;
        //echo "<BR> erro 32";
      }
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhpessoalmov->sql_query_file (null,null,"*","","rh02_instit = $instit and  rh02_anousu = ".$dataii_ano." and rh02_mesusu = ".$dataii_mes);

    $retorno = db_incluir_sql($sql1,$clrhpessoalmov,"rhpessoalmov");
    //echo "<br> 0.33 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 33";
    }
  }

  if ($sqlerro == false){

    $sql1 = $clrhpessoalmovcontabancaria->sql_query_retorno(null,"rhpessoalmovcontabancaria.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1, $clrhpessoalmovcontabancaria, "rhpessoalmovcontabancaria");

    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
    }
  }

  if($sqlerro == false){

    $sWhere = "and not exists (select 1 from rhpesbanco d where d.rh44_seqpes = a.rh02_seqpes)";
    $sql1 = $clrhpesbanco->sql_query_retorno(null,"rhpesbanco.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes." $sWhere",$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpesbanco,"rhpesbanco");
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 34";
    }
  }



  if($sqlerro == false){
    $sql1 = $clrhpespadrao->sql_query_retorno(null,"rhpespadrao.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpespadrao,"rhpespadrao");
    //echo "<br> 0.35 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 35";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhpesrescisao->sql_query_retorno(null,"rhpesrescisao.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpesrescisao,"rhpesrescisao");
    //echo "<br> 0.36 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 36";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhinssoutros->sql_query_retorno(null,"rhinssoutros.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhinssoutros,"rhinssoutros");
    //echo "<br> 0.37 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 37";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhpesponto->sql_query_retorno(null,"rhpesponto.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpesponto,"rhpesponto");
    //echo "<br> 0.38 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 38";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhpescargo->sql_query_retorno(null,"rhpescargo.*","","rh20_instit = $instit and  rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpescargo,"rhpescargo");
    //echo "<br> 0.39 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 39";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhpeslocaltrab->sql_query_retorno(null,"rhpeslocaltrab.*",""," rhpessoalmov.rh02_instit = $instit and rhpessoalmov.rh02_anousu=".$dataii_ano." and rhpessoalmov.rh02_mesusu=".$dataii_mes,$datafi_ano,$datafi_mes);
    $retorno = db_incluir_sql($sql1,$clrhpeslocaltrab,"rhpeslocaltrab");
    //echo "<br> 0.40 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 40";
    }
  }

  if($sqlerro == false){
    $sql1 = $clrhvisavalecad->sql_query_file (null,"*","","rh49_instit = $instit and rh49_anousu = ".$dataii_ano." and rh49_mesusu = ".$dataii_mes);
    $retorno = db_incluir_sql($sql1,$clrhvisavalecad,"rhvisavalecad");

    //echo "<br> 0.41 - " . pg_last_error();

    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 41";
    }
  }

  if ($sqlerro == false){

    $sql1 = $clpensaoretencao->sql_query_retorno(null,"pensaoretencao.*","","
      rhpessoalmov.rh02_instit = $instit
      and rhpessoalmov.rh02_anousu=".$dataii_ano."
      and rhpessoalmov.rh02_mesusu=".$dataii_mes);
    $retorno = db_incluir_sql($sql1,$clpensaoretencao,"pensaoretencao");
    //echo "<br> 0.40 - " . pg_last_error();
    if($retorno != '1' && $retorno != '2'){
      $sqlerro = true;
      //echo "<BR> erro 40";
    }
  }

  if($sqlerro == false) {
    echo "<script>parent.js_mostrardiv(true,'Atualizando dados da tabela <font color=\"red\">pessoal</font>');</script>";
    //echo "<BR> ".$clgerfsal->sql_query_seleciona(null,null,null,null,"r14_regist, r14_rubric, r14_valor","r14_regist,r14_rubric","r14_anousu = ".$dataii_ano." and r14_mesusu = ".$dataii_mes." and (r14_rubric = 'R926' or r14_rubric = 'R928')");
    $result_gerf = $clgerfsal->sql_record($clgerfsal->sql_query_seleciona(null,null,null,null,"r14_regist, r14_rubric, r14_valor","r14_regist,r14_rubric","r14_anousu = ".$dataii_ano." and r14_mesusu = ".$dataii_mes." and (r14_rubric = 'R926' or r14_rubric = 'R928') and r14_instit = ".db_getsession("DB_instit")));
    $arr_valores = Array();
    $arr_rubrica = Array();
    for($i=0; $i < $clgerfsal->numrows; $i++){
      db_fieldsmemory($result_gerf, $i);
      //echo "<BR> ".$clrhpessoalmov->sql_query_file(null,null,"rh02_seqpes","","rh02_instit = ".db_getsession("DB_instit")." and rh02_anousu = ".$dataif_ano." and rh02_mesusu = ".$dataff_mes." and rh02_regist = ".$r14_regist);
      $result_max_min = $clrhpessoalmov->sql_record($clrhpessoalmov->sql_query_file(null,null,"rh02_seqpes","","rh02_instit = ".db_getsession("DB_instit")." and rh02_anousu = ".$dataff_ano." and rh02_mesusu = ".$dataff_mes." and rh02_regist = ".$r14_regist));
      db_fieldsmemory($result_max_min, 0);
      if(!isset($arr_valores[$rh02_seqpes])){
        $arr_valores[$rh02_seqpes] = 0;
      }
      $arr_valores[$rh02_seqpes]+= $r14_valor;
      $arr_rubrica[$rh02_seqpes] = $r14_rubric;
    }
    reset($arr_valores);
    for($i=0; $i<count($arr_valores); $i++){
      $seqpes = key($arr_valores);
      $rmais = substr($arr_rubrica[$seqpes], 3, 1) + 1;
      $clrhpesrubcalc->rh65_seqpes = $seqpes;
      $clrhpesrubcalc->rh65_rubric = "R92".$rmais;
      $clrhpesrubcalc->rh65_valor  = $arr_valores[$seqpes];
      $clrhpesrubcalc->incluir(null);

      next($arr_valores);
    }
  }

  /**
   * Realiza a exclusao dos dados na tabela rhpreponto
   */
  if ($sqlerro == false){

    echo "<script>parent.js_mostrardiv(true,'Atualizando dados da tabela <font color=\"red\">rhpreponto</font>');</script>";

    $oDaoRhPrePonto = new cl_rhpreponto();
    $oDaoRhPrePonto->excluir(null, "rh149_instit = {$instit} and rh149_sequencial not in (select rh156_rhpreponto from rhprepontoloteregistroponto)");

    if ($oDaoRhPrePonto->erro_status == "0") {

      $retorno = $oDaoRhPrePonto->erro_msg;
      $sqlerro = true;
    }
  }

  $result_cfpe = $clcfpess->sql_record($clcfpess->sql_query_file($dataii_ano,$dataii_mes,$instit));
  if($clcfpess->numrows > 0){
    db_fieldsmemory($result_cfpe, 0);
  }

  if ($sqlerro == false) {
    flush();
    echo "<script>parent.js_mostrardiv(true,'Atualizando dados da tabela <font color=\"red\">Pensao</font>');</script>";
    $update = "update pensao
      set r52_valor  = 0,
      r52_valcom = 0,
      r52_valres = 0,
      r52_valfer = 0,
      r52_val13  = 0
      from rhpessoalmov
      where r52_anousu = rh02_anousu
      and r52_mesusu = rh02_mesusu
      and r52_regist = rh02_regist
      and rh02_instit = ".$instit."
      and r52_anousu = ".$datafi_ano."
      and r52_mesusu = ".$datafi_mes;

    //echo "<BR><BR> $update";
    $execut = db_query($update);
    if($execut == false){
      $retorno = "Erro ao atualizar tabela pensao.";
      $sqlerro = true;
      // echo "<BR> erro 44";exit;
    }
  }

  if ($sqlerro == false) {
    flush();

    $oDaoBaserhcadregime = new cl_baserhcadregime();

    $sWhereBaseRhCadRegime  = "    rh158_instit = {$instit}     ";
    $sWhereBaseRhCadRegime .= "and rh158_ano    = {$dataii_ano} ";
    $sWhereBaseRhCadRegime .= "and rh158_mes    = {$dataii_mes} ";

    $sql = $oDaoBaserhcadregime->sql_query_file(null,"*","",$sWhereBaseRhCadRegime);

    $retorno = db_incluir_sql($sql,$oDaoBaserhcadregime,"baserhcadregime");

    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
      //echo "<BR> erro 40";
    }
  }

  if ($sqlerro == false) {
    flush();

    $oDaoTipoAsseFinanceiro = new cl_tipoassefinanceiro();

    $sWhereTipoAsseFinanceiro  = "    rh165_instit = {$instit}     ";
    $sWhereTipoAsseFinanceiro .= "and rh165_anousu = {$dataii_ano} ";
    $sWhereTipoAsseFinanceiro .= "and rh165_mesusu = {$dataii_mes} ";

    $sql = $oDaoTipoAsseFinanceiro->sql_query_file(null,"*","",$sWhereTipoAsseFinanceiro);

    $retorno = db_incluir_sql($sql,$oDaoTipoAsseFinanceiro,"tipoassefinanceiro");

    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
      //echo "<BR> erro 40";
    }
  }

  if ($sqlerro == false) {

    flush();
    $oDaoTipoAsseExterno = new cl_tipoasseexterno();

    $sWhereTipoAsseExterno  = "    rh167_instit = {$instit}     ";
    $sWhereTipoAsseExterno .= "and rh167_anousu = {$dataii_ano} ";
    $sWhereTipoAsseExterno .= "and rh167_mesusu = {$dataii_mes} ";

    $sql = $oDaoTipoAsseExterno->sql_query_file(null,"*","",$sWhereTipoAsseExterno);

    $retorno = db_incluir_sql($sql,$oDaoTipoAsseExterno,"tipoasseexterno");

    if ($retorno != '1' && $retorno != '2') {
      $sqlerro = true;
    }
  }
  flush();
  if($sqlerro == true){
    echo "<script>parent.js_mostrardiv(true,'Erro no processamento');</script>";
    db_msgbox($retorno);
  }else{
    $retorno_cfpess = db_sel_cfpess($dataif_ano, $dataif_mes,$instit);

    if ($retorno_cfpess === false) {
      $sqlerro = true;
      //echo "<BR> erro 45";
      $retorno = "Ocorreram problemas no fechamento. Contate o suporte.";
    } else {
      echo "<script>parent.js_mostrardiv(true,'Processamento concluído com sucesso');</script>";
      db_msgbox("Fechamento da folha efetuado com sucesso.\\n\\nPeríodo anterior: ".$periodoini1." a ".$periodoini2.".\\nPeríodo atual: ".$periodofim1." a ".$periodofim2."");
    }
  }
}

db_fim_transacao($sqlerro);

flush();

echo "<script>parent.js_mostrardiv(false,'');</script>";
if (isset($desprocess) && $desprocess == "true"){
  echo "<script>parent.location.href = '../pes1_virafolha003.php'</script>";
}else{
  echo "<script>parent.location.href = '../pes1_virafolha001.php'</script>";
}
?>
</body>
</html>
