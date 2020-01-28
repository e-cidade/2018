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

use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

if (!isset($arqinclude)) {

  require_once(modification("fpdf151/pdf.php"));
  require_once(modification("fpdf151/assinatura.php"));
  require_once(modification("libs/db_sql.php"));
  require_once(modification("libs/db_utils.php"));
  require_once(modification("libs/db_libcontabilidade.php"));
  require_once(modification("libs/db_liborcamento.php"));
  require_once(modification("classes/db_orcparamrel_classe.php"));
  require_once(modification("dbforms/db_funcoes.php"));
  require_once(modification("classes/db_conrelinfo_classe.php"));
  require_once(modification("classes/db_db_config_classe.php"));
  require_once(modification("classes/db_orcparamrelnota_classe.php"));
  require_once(modification("classes/db_orcparamelemento_classe.php"));
  require_once(modification("model/linhaRelatorioContabil.model.php"));
  require_once(modification("model/relatorioContabil.model.php"));

  $classinatura      = new cl_assinatura;
  $orcparamrel       = new cl_orcparamrel;
  $clconrelinfo      = new cl_conrelinfo;
  $cldb_config       = new cl_db_config;
  $clorcparamrelnota = new cl_orcparamrelnota;
  $clorcparamelemento = new cl_orcparamelemento();

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  db_postmemory($HTTP_SERVER_VARS);

}

$xinstit    = db_getsession("DB_instit");
$resultinst = db_query("select codigo,nomeinst,munic,nomeinstabrev, uf from db_config where codigo = {$xinstit}");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  }else{
    $descr_inst .= $xvirg.$nomeinst;
  }

  $xvirg = ', ';
}
$iCodigoRelatorio = 81;
$tipo_emissao='periodo';

$iExercAnt  = (db_getsession('DB_anousu')-1);
$iExercicio = (db_getsession('DB_anousu'));

  // Exclui elementos referente ao exercício anterior;
duplicaReceitaaCorrenteLiquida($iExercicio, $iCodigoRelatorio);
if (!isset($arqinclude)) {

  $oDaoPeriodo    = db_utils::getDao("periodo");
  $anousu         = db_getsession("DB_anousu");
  $anousu_ant     = db_getsession("DB_anousu")-1;
  $sSqlPeriodo    = $oDaoPeriodo->sql_query($periodo);
  $iCodigoPeriodo = $periodo;
  $oPeriodo       =  db_utils::fieldsMemory($oDaoPeriodo->sql_record($sSqlPeriodo),0);
  $sSiglaPeriodo  = $oPeriodo->o114_sigla;
  $periodo_selecionado = $sSiglaPeriodo;
  $dt_ini= $anousu.'-01-01'; // data inicial do período
  if ($periodo < 17) {

    $dt               = data_periodo($anousu, $sSiglaPeriodo);
    $dt_fin= $dt[1]; // data final do período
    $dt = data_periodo($anousu_ant, $sSiglaPeriodo); // no dbforms/db_funcoes.php
    $dt_ini_ant= $dt[0];                      // data inicial do período
    $dt_fin_ant= $dt[1];
  } else {


    $iUltimoDiaMes         = cal_days_in_month(CAL_GREGORIAN, $oPeriodo->o114_mesfinal, $anousu);
    $iUltimoDiaMesAnterior = cal_days_in_month(CAL_GREGORIAN, $oPeriodo->o114_mesfinal, $anousu_ant);
    $dt_fin                = "{$anousu}-{$oPeriodo->o114_mesfinal}-$iUltimoDiaMes";
    $dt_ini_ant            = "01-01-{$anousu_ant}";                      // data inicial do período
    $dt_fin_ant            = "{$anousu_ant}-{$oPeriodo->o114_mesfinal}-$iUltimoDiaMesAnterior";
    $dt[0]                 = $dt_ini;
    $dt[1]                 = $dt_fin;
  }            // data inicial do período
  // $texto = $dt['texto'];
  // $txtper = $dt['periodo'];

                     // data final do período

  $bimestre = substr($sSiglaPeriodo,0,1); // bimestre do exercicio atual

}
// caso tenha datas manuais selecionada , sobrescrevo as variaveis acima
if ($dtini!=''&&$dtfin!='') {

  $bimestre_anterior = 13;
  $tipo_emissao='datas';

  $dt_ini = $dtini;
  $dt_fin = $dtfin;

  $dt     = explode("-",$dt_ini);
  $mes    = $dt[1];

  // 1 Bimestre
  if ($mes >= 1 && $mes <= 2){
    $bimestre = 1;
  }elseif ($mes >= 3  && $mes <= 4 ){  // 2 Bimestre
    $bimestre = 2;
  }elseif ($mes >= 5  && $mes <= 6 ){  // 3 Bimestre
    $bimestre = 3;
  }elseif ($mes >= 7  && $mes <= 8 ){  // 4 Bimestre
    $bimestre = 4;
  }elseif ($mes >= 9  && $mes <= 10){  // 5 Bimestre
    $bimestre = 5;
  }elseif ($mes >= 11 && $mes <= 12){  // 6 Bimestre
    $bimestre = 6;
  }

  $dt = explode('-',$dt_fin);
  $dt_ini_ant = $anousu_ant.'-'.$dt[1].'-'.$dt[2];
  $dt = explode('-',$dt_fin);
  $dt_fin_ant = $anousu_ant.'-'.$dt[1].'-'.$dt[2];

}

$aDataInicial        = explode("-", $dt[0]);
$aDataFinal          = explode("-", $dt[1]);
$iMesInicialAtual    = 1;
$iMesFinalAtual      = $aDataFinal[1];
$iMesInicialAnterior = ($aDataFinal[1]+1);
$iMesFinalAnterior   = 12;
// calculo do mes inicial que sera considerado no exercicio anterior
if ($iCodigoPeriodo == 11 || $iCodigoPeriodo == 28) {

  $iMesInicialAtual    = 1;
  $iMesFinalAtual      = 12;
  $iMesInicialAnterior = 13;
  $iMesFinalAnterior   = 13;
}
$bimestre_anterior = ($bimestre*2)+1;

if (!isset($arqinclude)){

  $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($xinstit);

  $head2 = DemonstrativoFiscal::getEnteFederativo( $oInstituicao);


  if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
    $head2 .= "\n" . $oInstituicao->getDescricao();
  }

  $head3 = "RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTARIA";
  $head4 = "DEMONSTRATIVO DA RECEITA CORRENTE LIQUIDA";
  $head5 = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL ";

  if ($flag_abrev == false){
    if (strlen($descr_inst) > 42){
      $descr_inst = substr($descr_inst,0,100);
    }
  }

  if ($tipo_emissao!='datas'){
    $dtd1   = explode('-',$dt_ini);
    $dtd2   = explode('-',$dt_fin);
    $dt1    = "$dtd1[2]/$dtd1[1]/$dtd1[0]";
    $dt2    = "$dtd2[2]/$dtd2[1]/$dtd2[0]";
    $txt    = mb_strtoupper(db_mes('01'));
    if ($iMesInicialAnterior < 13) {

      $txt    = mb_strtoupper(db_mes($iMesInicialAnterior));
      $txt   .= "  / ".$anousu_ant;
    } else {
      $txt   .= "  / ".$anousu;
    }
    $dt     = explode("-",$dt_fin);
    $txt   .= " A ".mb_strtoupper(db_mes($dt[1]));
    $txt   .= "  / ".$anousu;

  }else {
    $dtd1  = explode('-',$dt_ini);
    $dtd2  = explode('-',$dt_fin);
    $dt1   = "$dtd1[2]/$dtd1[1]/$dtd1[0]";
    $dt2   = "$dtd2[2]/$dtd2[1]/$dtd2[0]";
    $head5 = "EMISSÃO POR DATAS";
    $head6 = 'Periodo:  '.$dt1.' à '.$dt2;
  }

  switch($bimestre) {

    case 1:
      $txt   = mb_strtoupper(db_mes('3'))."/".($anousu-1)." À ".mb_strtoupper(db_mes('2'))."/".$anousu;
      break;
    case 2:
      $txt   = mb_strtoupper(db_mes('5'))."/".($anousu-1)." À ".mb_strtoupper(db_mes('4'))."/".$anousu;
      break;
    case 3:
      $txt   = mb_strtoupper(db_mes('7'))."/".($anousu-1)." À ".mb_strtoupper(db_mes('6'))."/".$anousu;
      break;
    case 4:
      $txt   = mb_strtoupper(db_mes('9'))."/".($anousu-1)." À ".mb_strtoupper(db_mes('8'))."/".$anousu;
      break;
    case 5:
      $txt   = mb_strtoupper(db_mes('11'))."/".($anousu-1)." À ".mb_strtoupper(db_mes('10'))."/".$anousu;
      break;
    case 6:
      $txt   = mb_strtoupper(db_mes('1'))."/".($anousu)." À ".mb_strtoupper(db_mes('12'))."/".($anousu);
      break;
  }

  $head6 = "$txt";

}
for ($iLinha = 1; $iLinha <= 22; $iLinha++) {

  $param[$iLinha] = new linhaRelatorioContabil($iCodigoRelatorio, $iLinha);
  $param[$iLinha]->oParametroAnterior = $param[$iLinha]->getParametros($anousu - 1);
  $param[$iLinha]->oParametroAtual    = $param[$iLinha]->getParametros($anousu);
}
// linha,coluna
$rec[1][0]  = "    IPTU";
$rec[2][0]  = "    ISS";
$rec[3][0]  = "    ITBI";
$rec[4][0]  = "    IRRF";
$rec[5][0]  = "    Outras Receitas Tributárias";
$rec[6][0]  = "  Receita de Contribuições";
$rec[7][0]  = "  Receita Patrimonial";
$rec[8][0]  = "  Receita Agropecuária";
$rec[9][0]  = "  Receita Industrial";
$rec[10][0]  = "  Receita de Serviços";
$rec[11][0] = "    Cota-Parte do FPM";
$rec[12][0] = "    Cota-Parte do ICMS";
$rec[13][0] = "    Cota-Parte do IPVA";
//$rec[12][0] = "    Cota-Parte do IPVA";
$rec[14][0] = "    Cota-Parte do ITR";
$rec[15][0] = "    Transferências da LC 87/1996";
$rec[16][0] = "    Transferências da LC 61/1989";
$rec[17][0] = "    Transferências do FUNDEB";
$rec[18][0] = "    Outras Transferências Correntes";
$rec[19][0] = "  Outras Receitas Correntes";

// - Deduções
$rec[20][0] = "    Contrib. para o Plano de Previdência do Servidor";
$rec[21][0] = "    Compensação Financ. entre Regimes Previd.";
$rec[22][0] = "    Deduções da Receitas para Formação do FUNDEB";

// monta receita do bimestre do exercicio anterior, quando for o caso
$dt1 = ($anousu - 1).'-01-01';
$dt2 = ($anousu - 1).'-12-31';

$todasinstit="";
$result_db_config = $cldb_config->sql_record($cldb_config->sql_query_file(null,"codigo"));
for ($xinstit=0; $xinstit < $cldb_config->numrows; $xinstit++) {
  db_fieldsmemory($result_db_config, $xinstit);
  $todasinstit.=$codigo . ($xinstit==$cldb_config->numrows-1?"":",");
}
//echo "{$dt1} -- {$dt2}<br>";
$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->anousu = ($anousu - 1);
$clreceita_saldo_mes->dtini = $dt1;
$clreceita_saldo_mes->dtfim = $dt2;
$clreceita_saldo_mes->usa_datas = 'sim';
//$clreceita_saldo_mes->instit = "".str_replace('-',', ',$db_selinstit)." ";
$clreceita_saldo_mes->instit = $todasinstit;
$clreceita_saldo_mes->sql_record();
//db_criatabela($clreceita_saldo_mes->result);exit;
db_query("drop table work_plano");
  // 18 é a quantidade de parametros (linhas existentes nos parametros)
for ($i = 0; $i < $clreceita_saldo_mes->numrows; $i++) {

  for ( $p = 1; $p <= 22; $p++) {

    $oReceita       = db_utils::fieldsmemory($clreceita_saldo_mes->result, $i);
    $oParametro     = $param[$p]->oParametroAnterior;
    foreach ($oParametro->contas as $oEstrutural) {
    $oRetornoVerificacao = $param[$p]->match($oEstrutural ,$oParametro->orcamento, $oReceita, 1);
    if ($oRetornoVerificacao->match) {

      if ($oRetornoVerificacao->exclusao) {

        $oReceita->janeiro   *= -1;
        $oReceita->fevereiro *= -1;
        $oReceita->marco     *= -1;
        $oReceita->abril     *= -1;
        $oReceita->maio      *= -1;
        $oReceita->junho     *= -1;
        $oReceita->julho     *= -1;
        $oReceita->agosto    *= -1;
        $oReceita->setembro  *= -1;
        $oReceita->outubro   *= -1;
        $oReceita->novembro  *= -1;
        $oReceita->dezembro  *= -1;
        $oReceita->o70_valor *= -1;
        $oReceita->adicional *= -1;
      }
      if ($p == 22 ) {

        $oReceita->janeiro   *= -1;
        $oReceita->fevereiro *= -1;
        $oReceita->marco     *= -1;
        $oReceita->abril     *= -1;
        $oReceita->maio      *= -1;
        $oReceita->junho     *= -1;
        $oReceita->julho     *= -1;
        $oReceita->agosto    *= -1;
        $oReceita->setembro  *= -1;
        $oReceita->outubro   *= -1;
        $oReceita->novembro  *= -1;
        $oReceita->dezembro  *= -1;
        $oReceita->o70_valor *= -1;
        $oReceita->adicional *= -1;

      }
      if (!isset($rec[$p][1]))  $rec[$p][1]  = $oReceita->janeiro;    else $rec[$p][1]  += $oReceita->janeiro;
      if (!isset($rec[$p][2]))  $rec[$p][2]  = $oReceita->fevereiro;  else $rec[$p][2]  += $oReceita->fevereiro;
      if (!isset($rec[$p][3]))  $rec[$p][3]  = $oReceita->marco;      else $rec[$p][3]  += $oReceita->marco;
      if (!isset($rec[$p][4]))  $rec[$p][4]  = $oReceita->abril;      else $rec[$p][4]  += $oReceita->abril;
      if (!isset($rec[$p][5]))  $rec[$p][5]  = $oReceita->maio;       else $rec[$p][5]  += $oReceita->maio;
      if (!isset($rec[$p][6]))  $rec[$p][6]  = $oReceita->junho;      else $rec[$p][6]  += $oReceita->junho;
      if (!isset($rec[$p][7]))  $rec[$p][7]  = $oReceita->julho;      else $rec[$p][7]  += $oReceita->julho;
      if (!isset($rec[$p][8]))  $rec[$p][8]  = $oReceita->agosto;     else $rec[$p][8]  += $oReceita->agosto;
      if (!isset($rec[$p][9]))  $rec[$p][9]  = $oReceita->setembro;   else $rec[$p][9]  += $oReceita->setembro;
      if (!isset($rec[$p][10])) $rec[$p][10] = $oReceita->outubro;    else $rec[$p][10] += $oReceita->outubro;
      if (!isset($rec[$p][11])) $rec[$p][11] = $oReceita->novembro;   else $rec[$p][11] += $oReceita->novembro;
      if (!isset($rec[$p][12])) $rec[$p][12] = $oReceita->dezembro;   else $rec[$p][12] += $oReceita->dezembro;

      // matriz de totalizador do exercicio anterior
      if ($p <= 19) {

        // Trec da linha 0 (zero) contem o total da arrecadação da receita corrente
        if (!isset($Trec[0][1]))  $Trec[0][1]  = $oReceita->janeiro;    else $Trec[0][1]  += $oReceita->janeiro;
        if (!isset($Trec[0][2]))  $Trec[0][2]  = $oReceita->fevereiro;  else $Trec[0][2]  += $oReceita->fevereiro;
        if (!isset($Trec[0][3]))  $Trec[0][3]  = $oReceita->marco;      else $Trec[0][3]  += $oReceita->marco;
        if (!isset($Trec[0][4]))  $Trec[0][4]  = $oReceita->abril;      else $Trec[0][4]  += $oReceita->abril;
        if (!isset($Trec[0][5]))  $Trec[0][5]  = $oReceita->maio;       else $Trec[0][5]  += $oReceita->maio;
        if (!isset($Trec[0][6]))  $Trec[0][6]  = $oReceita->junho;      else $Trec[0][6]  += $oReceita->junho;
        if (!isset($Trec[0][7]))  $Trec[0][7]  = $oReceita->julho;      else $Trec[0][7]  += $oReceita->julho;
        if (!isset($Trec[0][8]))  $Trec[0][8]  = $oReceita->agosto;     else $Trec[0][8]  += $oReceita->agosto;
        if (!isset($Trec[0][9]))  $Trec[0][9]  = $oReceita->setembro;   else $Trec[0][9]  += $oReceita->setembro;
        if (!isset($Trec[0][10])) $Trec[0][10] = $oReceita->outubro;    else $Trec[0][10] += $oReceita->outubro;
        if (!isset($Trec[0][11])) $Trec[0][11] = $oReceita->novembro;   else $Trec[0][11] += $oReceita->novembro;
        if (!isset($Trec[0][12])) $Trec[0][12] = $oReceita->dezembro;   else $Trec[0][12] += $oReceita->dezembro;

      } else {
        // Trec da linha 1 contem o total da dedução da receita corrente
        if (db_conplano_grupo($anousu - 1, substr($oReceita->o57_fonte, 0, 3)."%", 9001) == true) {  // 497 e 917

          if (!isset($Trec[1][1]))  $Trec[1][1]  = ($oReceita->janeiro);   else {$Trec[1][1]  += ($oReceita->janeiro);}
          if (!isset($Trec[1][2]))  $Trec[1][2]  = ($oReceita->fevereiro); else {$Trec[1][2]  += ($oReceita->fevereiro);}
          if (!isset($Trec[1][3]))  $Trec[1][3]  = ($oReceita->marco);     else {$Trec[1][3]  += ($oReceita->marco);}
          if (!isset($Trec[1][4]))  $Trec[1][4]  = ($oReceita->abril);     else {$Trec[1][4]  += ($oReceita->abril);}
          if (!isset($Trec[1][5]))  $Trec[1][5]  = ($oReceita->maio);      else {$Trec[1][5]  += ($oReceita->maio);}
          if (!isset($Trec[1][6]))  $Trec[1][6]  = ($oReceita->junho);     else {$Trec[1][6]  += ($oReceita->junho);}
          if (!isset($Trec[1][7]))  $Trec[1][7]  = ($oReceita->julho);     else {$Trec[1][7]  += ($oReceita->julho);}
          if (!isset($Trec[1][8]))  $Trec[1][8]  = ($oReceita->agosto);    else {$Trec[1][8]  += ($oReceita->agosto);}
          if (!isset($Trec[1][9]))  $Trec[1][9]  = ($oReceita->setembro);  else {$Trec[1][9]  += ($oReceita->setembro);}
          if (!isset($Trec[1][10])) $Trec[1][10] = ($oReceita->outubro);   else {$Trec[1][10] += ($oReceita->outubro);}
          if (!isset($Trec[1][11])) $Trec[1][11] = ($oReceita->novembro);  else {$Trec[1][11] += ($oReceita->novembro);}
          if (!isset($Trec[1][12])) $Trec[1][12] = ($oReceita->dezembro);  else {$Trec[1][12] += ($oReceita->dezembro);}
        } else {
          if (!isset($Trec[1][1]))  $Trec[1][1] = ($oReceita->janeiro);    else $Trec[1][1] += ($oReceita->janeiro);
          if (!isset($Trec[1][2]))  $Trec[1][2] = ($oReceita->fevereiro);  else $Trec[1][2] += ($oReceita->fevereiro);
          if (!isset($Trec[1][3]))  $Trec[1][3] = ($oReceita->marco);      else $Trec[1][3] += ($oReceita->marco);
          if (!isset($Trec[1][4]))  $Trec[1][4] = ($oReceita->abril);      else $Trec[1][4] += ($oReceita->abril);
          if (!isset($Trec[1][5]))  $Trec[1][5] = ($oReceita->maio);       else $Trec[1][5] += ($oReceita->maio);
          if (!isset($Trec[1][6]))  $Trec[1][6] = ($oReceita->junho);      else $Trec[1][6] += ($oReceita->junho);
          if (!isset($Trec[1][7]))  $Trec[1][7] = ($oReceita->julho);      else $Trec[1][7] += ($oReceita->julho);
          if (!isset($Trec[1][8]))  $Trec[1][8] = ($oReceita->agosto);     else $Trec[1][8] += ($oReceita->agosto);
          if (!isset($Trec[1][9]))  $Trec[1][9] = ($oReceita->setembro);   else $Trec[1][9] += ($oReceita->setembro);
          if (!isset($Trec[1][10])) $Trec[1][10]= ($oReceita->outubro);    else $Trec[1][10] += ($oReceita->outubro);
          if (!isset($Trec[1][11])) $Trec[1][11]= ($oReceita->novembro);   else $Trec[1][11] += ($oReceita->novembro);
          if (!isset($Trec[1][12])) $Trec[1][12]= ($oReceita->dezembro);  else $Trec[1][12] += ($oReceita->dezembro);
        }
      }
    }
  }
  }
}

// --------------------------------------------------------
// monta receita do bimestre escolhido
$clreceita_saldo_mes = new cl_receita_saldo_mes;
$clreceita_saldo_mes->anousu = $anousu ;
$clreceita_saldo_mes->dtini = $dt_ini;
$clreceita_saldo_mes->dtfim = $dt_fin;
$clreceita_saldo_mes->usa_datas = 'sim';
//$clreceita_saldo_mes->instit = "".str_replace('-',', ',$db_selinstit)." ";
$clreceita_saldo_mes->instit = $todasinstit;
$clreceita_saldo_mes->sql_record();
//echo $clreceita_saldo_mes->sql; exit;
//db_criatabela($clreceita_saldo_mes->result);
//exit;
db_query("drop table work_plano");
for ($p = 1; $p <= 22; $p++) {
  // 18 é a quantidade de parametros ou linhas existentes nos parametros
  for ($i=0;$i<$clreceita_saldo_mes->numrows;$i++) {

    $oReceita       = db_utils::fieldsmemory($clreceita_saldo_mes->result, $i);
    $oParametro     = $param[$p]->oParametroAtual;
    foreach ($oParametro->contas as $oEstrutural) {

      $oRetornoVerificacao = $param[$p]->match($oEstrutural ,$oParametro->orcamento, $oReceita, 1);
      if ($oRetornoVerificacao->match) {

        if ($oRetornoVerificacao->exclusao) {

          $oReceita->janeiro   *= -1;
          $oReceita->fevereiro *= -1;
          $oReceita->marco     *= -1;
          $oReceita->abril     *= -1;
          $oReceita->maio      *= -1;
          $oReceita->junho     *= -1;
          $oReceita->julho     *= -1;
          $oReceita->agosto    *= -1;
          $oReceita->setembro  *= -1;
          $oReceita->outubro   *= -1;
          $oReceita->novembro  *= -1;
          $oReceita->dezembro  *= -1;
          $oReceita->o70_valor *= -1;
          $oReceita->adicional *= -1;
        }

        if ($p == 22 ) {

          $oReceita->janeiro   *= -1;
          $oReceita->fevereiro *= -1;
          $oReceita->marco     *= -1;
          $oReceita->abril     *= -1;
          $oReceita->maio      *= -1;
          $oReceita->junho     *= -1;
          $oReceita->julho     *= -1;
          $oReceita->agosto    *= -1;
          $oReceita->setembro  *= -1;
          $oReceita->outubro   *= -1;
          $oReceita->novembro  *= -1;
          $oReceita->dezembro  *= -1;
          $oReceita->o70_valor *= -1;
          $oReceita->adicional *= -1;

        }
        if (!isset($recB[$p][1]))  $recB[$p][1]  = $oReceita->janeiro;   else $recB[$p][1]  += $oReceita->janeiro;
        if (!isset($recB[$p][2]))  $recB[$p][2]  = $oReceita->fevereiro; else $recB[$p][2]  += $oReceita->fevereiro;
        if (!isset($recB[$p][3]))  $recB[$p][3]  = $oReceita->marco;     else $recB[$p][3]  += $oReceita->marco;
        if (!isset($recB[$p][4]))  $recB[$p][4]  = $oReceita->abril;     else $recB[$p][4]  += $oReceita->abril;
        if (!isset($recB[$p][5]))  $recB[$p][5]  = $oReceita->maio;      else $recB[$p][5]  += $oReceita->maio;
        if (!isset($recB[$p][6]))  $recB[$p][6]  = $oReceita->junho;     else $recB[$p][6]  += $oReceita->junho;
        if (!isset($recB[$p][7]))  $recB[$p][7]  = $oReceita->julho;     else $recB[$p][7]  += $oReceita->julho;
        if (!isset($recB[$p][8]))  $recB[$p][8]  = $oReceita->agosto;    else $recB[$p][8]  += $oReceita->agosto;
        if (!isset($recB[$p][9]))  $recB[$p][9]  = $oReceita->setembro;  else $recB[$p][9]  += $oReceita->setembro;
        if (!isset($recB[$p][10])) $recB[$p][10] = $oReceita->outubro;   else $recB[$p][10] += $oReceita->outubro;
        if (!isset($recB[$p][11])) $recB[$p][11] = $oReceita->novembro;  else $recB[$p][11] += $oReceita->novembro;
        if (!isset($recB[$p][12])) $recB[$p][12] = $oReceita->dezembro;  else $recB[$p][12] += $oReceita->dezembro;
        // a coluna 13 ira guardar a previsao
        if (!isset($recB[$p][13])){
           $recB[$p][13]= ($oReceita->o70_valor+$oReceita->adicional);
        }  else {
          $recB[$p][13]+= ($oReceita->o70_valor+$oReceita->adicional);
        }

        /*
           chamamos de "recB" esta segunda matriz, porque "rec" é foi a primeira matriz criada
           que ira quardar os dados do exercicio-1, e este "recB" ira guardar dados do exercicio atual
         */
        if ($p <= 19) {

          // Trec da linha 0 (zero) contem o total da arrecadação da receita corrente
          if (!isset($TrecB[0][1]))  $TrecB[0][1]  = $oReceita->janeiro;   else $TrecB[0][1]     += $oReceita->janeiro;
          if (!isset($TrecB[0][2]))  $TrecB[0][2]  = $oReceita->fevereiro; else $TrecB[0][2]     += $oReceita->fevereiro;
          if (!isset($TrecB[0][3]))  $TrecB[0][3]  = $oReceita->marco;     else $TrecB[0][3]     += $oReceita->marco;
          if (!isset($TrecB[0][4]))  $TrecB[0][4]  = $oReceita->abril;     else $TrecB[0][4]     += $oReceita->abril;
          if (!isset($TrecB[0][5]))  $TrecB[0][5]  = $oReceita->maio;      else $TrecB[0][5]     += $oReceita->maio;
          if (!isset($TrecB[0][6]))  $TrecB[0][6]  = $oReceita->junho;     else $TrecB[0][6]     += $oReceita->junho;
          if (!isset($TrecB[0][7]))  $TrecB[0][7]  = $oReceita->julho;     else $TrecB[0][7]     += $oReceita->julho;
          if (!isset($TrecB[0][8]))  $TrecB[0][8]  = $oReceita->agosto;    else $TrecB[0][8]     += $oReceita->agosto;
          if (!isset($TrecB[0][9]))  $TrecB[0][9]  = $oReceita->setembro;  else $TrecB[0][9]     += $oReceita->setembro;
          if (!isset($TrecB[0][10])) $TrecB[0][10] = $oReceita->outubro;   else $TrecB[0][10]    += $oReceita->outubro;
          if (!isset($TrecB[0][11])) $TrecB[0][11] = $oReceita->novembro;  else $TrecB[0][11]    += $oReceita->novembro;
          if (!isset($TrecB[0][12])) $TrecB[0][12] = $oReceita->dezembro;  else $TrecB[0][12]    += $oReceita->dezembro;
          if (!isset($TrecB[0][13])) {
            $TrecB[0][13] = ($oReceita->o70_valor + $oReceita->adicional);
          } else  {
            $TrecB[0][13] += ($oReceita->o70_valor + $oReceita->adicional);
          }
        } else {
          // Trec da linha 1 contem o total da dedução da receita corrente
          if (db_conplano_grupo($anousu,substr($oReceita->o57_fonte, 0,3)."%",9001) == true){  // 497 e 917
            if (!isset($TrecB[1][1]))  $TrecB[1][1]= ($oReceita->janeiro);    else $TrecB[1][1] += ($oReceita->janeiro);
            if (!isset($TrecB[1][2]))  $TrecB[1][2]= ($oReceita->fevereiro);  else $TrecB[1][2] += ($oReceita->fevereiro);
            if (!isset($TrecB[1][3]))  $TrecB[1][3]= ($oReceita->marco);      else $TrecB[1][3] += ($oReceita->marco);
            if (!isset($TrecB[1][4]))  $TrecB[1][4]= ($oReceita->abril);      else $TrecB[1][4] += ($oReceita->abril);
            if (!isset($TrecB[1][5]))  $TrecB[1][5]= ($oReceita->maio);       else $TrecB[1][5] += ($oReceita->maio);
            if (!isset($TrecB[1][6]))  $TrecB[1][6]= ($oReceita->junho);      else $TrecB[1][6] += ($oReceita->junho);
            if (!isset($TrecB[1][7]))  $TrecB[1][7]= ($oReceita->julho);      else $TrecB[1][7] += ($oReceita->julho);
            if (!isset($TrecB[1][8]))  $TrecB[1][8]= ($oReceita->agosto);     else $TrecB[1][8] += ($oReceita->agosto);
            if (!isset($TrecB[1][9]))  $TrecB[1][9]= ($oReceita->setembro);   else $TrecB[1][9] += ($oReceita->setembro);
            if (!isset($TrecB[1][10])) $TrecB[1][10]= ($oReceita->outubro);   else $TrecB[1][10]+= ($oReceita->outubro);
            if (!isset($TrecB[1][11])) $TrecB[1][11]= ($oReceita->novembro);  else $TrecB[1][11]+= ($oReceita->novembro);
            if (!isset($TrecB[1][12])) $TrecB[1][12]= ($oReceita->dezembro);  else $TrecB[1][12]+= ($oReceita->dezembro);
            if (!isset($TrecB[1][13])) {
              $TrecB[1][13]= ($oReceita->o70_valor+$oReceita->adicional);
            } else {
              $TrecB[1][13]+= ($oReceita->o70_valor+$oReceita->adicional);
            }
          } else {
            if (!isset($TrecB[1][1]))  $TrecB[1][1]  = ($oReceita->janeiro);    else $TrecB[1][1]  += ($oReceita->janeiro);
            if (!isset($TrecB[1][2]))  $TrecB[1][2]  = ($oReceita->fevereiro);  else $TrecB[1][2]  += ($oReceita->fevereiro);
            if (!isset($TrecB[1][3]))  $TrecB[1][3]  = ($oReceita->marco);      else $TrecB[1][3]  += ($oReceita->marco);
            if (!isset($TrecB[1][4]))  $TrecB[1][4]  = ($oReceita->abril);      else $TrecB[1][4]  += ($oReceita->abril);
            if (!isset($TrecB[1][5]))  $TrecB[1][5]  = ($oReceita->maio);       else $TrecB[1][5]  += ($oReceita->maio);
            if (!isset($TrecB[1][6]))  $TrecB[1][6]  = ($oReceita->junho);      else $TrecB[1][6]  += ($oReceita->junho);
            if (!isset($TrecB[1][7]))  $TrecB[1][7]  = ($oReceita->julho);      else $TrecB[1][7]  += ($oReceita->julho);
            if (!isset($TrecB[1][8]))  $TrecB[1][8]  = ($oReceita->agosto);     else $TrecB[1][8]  += ($oReceita->agosto);
            if (!isset($TrecB[1][9]))  $TrecB[1][9]  = ($oReceita->setembro);   else $TrecB[1][9]  += ($oReceita->setembro);
            if (!isset($TrecB[1][10])) $TrecB[1][10] = ($oReceita->outubro);   else $TrecB[1][10] += ($oReceita->outubro);
            if (!isset($TrecB[1][11])) $TrecB[1][11] = ($oReceita->novembro);  else $TrecB[1][11] += ($oReceita->novembro);
            if (!isset($TrecB[1][12])) $TrecB[1][12] = ($oReceita->dezembro);  else $TrecB[1][12] += ($oReceita->dezembro);
            if (!isset($TrecB[1][13])) {
              $TrecB[1][13] = ($oReceita->o70_valor+$oReceita->adicional);
            } else {
              $TrecB[1][13]+= ($oReceita->o70_valor+$oReceita->adicional);
            }
          }
        }
      }
    }
  }
}
// -----------
// ------------------------------
// somadores avulsos
$tot_rec_trib = array(); //zera matriz
for ($x=0;$x<=13;$x++){
  $tot_rec_trib[0][$x]=0;
}
for ($x = 1; $x <= 5; $x++) {

  for ($y=$iMesInicialAnterior; $y <= 12; $y++) {
    if (isset($rec[$x][$y])){
      $tot_rec_trib[0][$y] += $rec[$x][$y];
    } else
      $tot_rec_trib[0][$y] += 0;
  }
  // procura valores do exercicio atual
  for ($y = 1; $y <= $iMesFinalAtual; $y++) {

    if (isset($recB[$x][$y])) {
      $tot_rec_trib[0][$y] += $recB[$x][$y];
    } else {
      $tot_rec_trib[0][$y] += 0;
    }
  }
  // listamos a previsão
  if (isset($recB[$x][13])) {
    $tot_rec_trib[0][13] += $recB[$x][13];
  }else{
    $tot_rec_trib[0][13] += 0;
  }
}

//
$tot_transf = array(); //zera matriz

for ($x = 0; $x <= 13; $x++) {
  $tot_transf[0][$x]=0;
}
for ($x = 11;$x <= 18; $x++) {

  for ($y = $iMesInicialAnterior; $y <= 12;$y++) {

    if (isset($rec[$x][$y])) {
      $tot_transf[0][$y] += $rec[$x][$y];
    } else
      $tot_transf[0][$y] += 0;
  }
  // procura valores do exercicio atual
  for ($y=1; $y <= $iMesFinalAtual; $y++) {

    if (isset($recB[$x][$y])) {
      $tot_transf[0][$y] += $recB[$x][$y];
    } else
      $tot_transf[0][$y] += 0;
  }

  // listamos a previsão
  if (isset($recB[$x][13])) {
    $tot_transf[0][13] += $recB[$x][13];
  } else {
    $tot_transf[0][13] += 0;
  }
}

// uma matriz para facilitar a impresso dos nomes dos meses no relatorio
$mes_dresc[1]	= 'Jan';
$mes_dresc[2]	= 'Fev';
$mes_dresc[3]	= 'Mar';
$mes_dresc[4]	= 'Abr';
$mes_dresc[5]	= 'Mai';
$mes_dresc[6]	= 'Jun';
$mes_dresc[7]	= 'Jul';
$mes_dresc[8]	= 'Ago';
$mes_dresc[9]	= 'Set';
$mes_dresc[10]	= 'Out';
$mes_dresc[11]	= 'Nov';
$mes_dresc[12]	= 'Dez';

if (!isset($arqinclude)){

  //-------------------------------------------------------------------------------------------------
  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','',6);
  $alt            = 4;
  $pagina         = 1;
  $cl = 16;
  $tp = 'B';
  $ta ='TBRL';

  $pdf->addpage("L");

  $pdf->cell(60,$alt,"RREO - Anexo III (LRF, Art. 53, inciso I)","b",0,"L",0);
  $pdf->cell(($cl*14),$alt,"R$ 1,00","b",1,"R",0);
  $pdf->cell(60,$alt,"ESPECIFICAÇÃO",'RT',0,"C",0);
  $pdf->cell(($cl*12),$alt,"EVOLUÇÃO DA RECEITA REALIZADA NOS ÚLTIMOS 12 MESES",'RTB',0,"C",0);
  $pdf->cell($cl,$alt,"TOTAL",'RT',0,"C",0);
  $pdf->cell($cl,$alt,"PREVISAO",'T',0,"C",0);
  $pdf->ln();
  $pdf->cell(60,$alt,"",'BR',0,"C",0);
  if ($bimestre != 6 && $bimestre != "D") {
   $ano = $anousu-1;
  } else {
   $ano = $anousu;
  }

  if ($iMesFinalAnterior !=13) {
    /*
     * lista meses do periodo anterior ( exercicio anterior )
     */
    for ($x= $iMesInicialAnterior; $x <= 12; $x++) {

      $pdf->cell($cl, $alt, $mes_dresc[$x]."/".$ano, 'TBR', 0, "C", 0);
 	    if ($mes_dresc[$x] == "Dez" ) {
	  	  $ano++;
	    }
    }
  }

  // meses do exercicio atual
  for ($x = 1;$x <= $iMesFinalAtual; $x++) {

  	$pdf->cell($cl, $alt, $mes_dresc[$x]."/".$ano, 'TBR', 0, "C", 0);

  	if ($mes_dresc[$x] == "Dez" ) {
	   $ano++;
 	  }
  }

  $pdf->cell($cl, $alt, "ULT 12 MESES", 'BR', 0, "C", 0);
  $pdf->cell($cl, $alt, "ATUAL EXERC", 'B', 0, "C", 0);
  $pdf->ln();

  $total = 0; // esse total é sempre calculado para cada linha
  // imprime as linhas/valores do exercicio anterior
  for ($x = 1; $x <= 22; $x++) { // 18 é a qtd de parametros existentes

    // ----------------------------------
    if ($x == 1) {
      // aqui imprime a linha com os totalizadores
      $pdf->setfont('arial', 'b' ,6);
      $pdf->cell(60, $alt, "RECEITAS CORRENTES(I)", 'R', 0, "L", 0);
      $total = 0;
      for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

        if (isset($Trec[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($Trec[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $Trec[0][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      for ($y = 1; $y <= $iMesFinalAtual; $y++) {

        if (isset($TrecB[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($TrecB[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $TrecB[0][$y];
        }else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      $pdf->cell($cl ,$alt, db_formatar($total, 'f'), 'R', 0, "R", 0);
      if (isset($TrecB[0][13])) {
        $pdf->cell($cl, $alt, db_formatar($TrecB[0][13], 'f'), 0, 0, "R", 0);
      }else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 0, 0, "R", 0);
      }
      $pdf->ln();
      //-------------------------------
      $pdf->setfont('arial', '', 6);
      $pdf->cell(60, $alt, " Receita Tributária", 'R', 0, "L", 0);
      $total = 0;
      for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

        if (isset($tot_rec_trib[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($tot_rec_trib[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $tot_rec_trib[0][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      for ($y = 1; $y <= $iMesFinalAtual; $y++) {

        if (isset($tot_rec_trib[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($tot_rec_trib[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $tot_rec_trib[0][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      // listamos o total das 12 colunas
      $pdf->cell($cl, $alt, db_formatar($total, 'f'), 'R', 0, "R", 0);

      // listamos a previsão
      if (isset($tot_rec_trib[0][13])) {
        $pdf->cell($cl, $alt, db_formatar($tot_rec_trib[0][13],'f'), 0, 0, "R", 0);
      } else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 0, 0, "R", 0);
      }
      $pdf->ln();

    }
    //-------------------------------
    if ($x == 11) {

      $pdf->setfont('arial', '', 6);
      $pdf->cell(60, $alt, " Transferências Correntes", 'R', 0, "L", 0);
      $total = 0;
      for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

        if (isset($tot_transf[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($tot_transf[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $tot_transf[0][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      for ($y = 1; $y <= $iMesFinalAtual; $y++) {

        if (isset($tot_transf[0][$y])) {

          $pdf->cell($cl, $alt, db_formatar($tot_transf[0][$y], 'f'), 'R', 0, "R", 0);
          $total += $tot_transf[0][$y];

        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      // listamos o total das 12 colunas
      $pdf->cell($cl, $alt, db_formatar($total, 'f'), 'R', 0, "R", 0);

      // listamos a previsão
      if (isset($tot_transf[0][13])) {
        $pdf->cell($cl, $alt, db_formatar($tot_transf[0][13], 'f'), 0, 0, "R", 0);
      } else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 0, 0, "R", 0);
      }
      $pdf->ln();
    }
    // ----------------------------------
    if ($x == 20) {

      // aqui imprime a linha com os totalizadores das deduções
      $pdf->setfont('arial', 'b', 6);
      $pdf->cell(60 ,$alt, "DEDUÇÕES(II)", 'R', 0, "L", 0);
      $total = 0;
      for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

        if (isset($Trec[1][$y])) {

          $pdf->cell($cl, $alt, db_formatar($Trec[1][$y], 'f'), 'R', 0, "R", 0);
          $total += $Trec[1][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      for ($y = 1;$y <= $iMesFinalAtual; $y++) {

        if (isset($TrecB[1][$y])) {

          $pdf->cell($cl, $alt, db_formatar(($TrecB[1][$y]), 'f'), 'R', 0, "R", 0);
          $total += $TrecB[1][$y];
        } else {
          $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
        }
      }
      $pdf->cell($cl, $alt, db_formatar(($total), 'f'), 'R', 0, "R", 0);
      if (isset($TrecB[1][13])) {
        $pdf->cell($cl, $alt, db_formatar(($TrecB[1][13]), 'f'), 0, 0, "R", 0);
      } else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 0, 0, "R", 0);
      }
      $pdf->ln();
    }

    // ----------------------------------
    // imprime os nomes das linhas
    $pdf->setfont('arial','',6);
    $pdf->cell(60, $alt, $rec[$x][0], 'R', 0, "L", 0);
    // Procura valores do exercicio anterior (anousu -1) e imprime
    $total = 0;
    for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

      if (isset($rec[$x][$y])) {
        //ivertemos o sinal para apresentação;
        if ($x == 22) {
          $rec[$x][$y] *= -1;
        }
        $pdf->cell($cl, $alt, db_formatar($rec[$x][$y], 'f'), 'R', 0, "R", 0);
        $total += $rec[$x][$y];
      } else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
      }
    }
    // procura valores do exercicio atual
    for ($y = 1; $y <= $iMesFinalAtual; $y++) {

      if (isset($recB[$x][$y])) {

        if ($x == 22) {
          $recB[$x][$y] *= -1;
        }
        $pdf->cell($cl, $alt, db_formatar($recB[$x][$y], 'f'), 'R', 0, "R", 0);
        $total += $recB[$x][$y];
      } else {
        $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'R', 0, "R", 0);
      }
    }
    // listamos o total das 12 colunas
    $pdf->cell($cl, $alt, db_formatar($total, 'f'), 'R', 0, "R", 0);

    // listamos a previsão

    if (isset($recB[$x][13])) {
      if ($x == 22) {
        $recB[$x][13] *= -1;
      }
      $pdf->cell($cl, $alt, db_formatar($recB[$x][13], 'f'), 0, 0, "R", 0);
    } else {
      $pdf->cell($cl, $alt, db_formatar(0, 'f'), 0, 0, "R", 0);
    }
    $pdf->ln();

  }//endfor

  // aqui imprime a linha com os totalizadores das deduções

  $pdf->setfont('arial', 'b', 6);
  $pdf->cell(60, $alt, "RECEITA CORRENTE LÍQUIDA (III) = (I-II)", 'RBT', 0, "L", 0);
  $total = 0;

  for ($y = $iMesInicialAnterior; $y <= 12; $y++) {

    if (isset($Trec[0][$y])) {

      if(isset($Trec[1][$y])) {

        $pdf->cell($cl, $alt, db_formatar($Trec["0"]["$y"] - $Trec["1"]["$y"], 'f'), 'RBT', 0, "R", 0);
        $total += $Trec["0"]["$y"] - $Trec["1"]["$y"];
      } else {

        $pdf->cell($cl, $alt, db_formatar($Trec[0][$y], 'f'), 'RBT', 0, "R", 0);
        $total += $Trec[0][$y];
      }
    } else {
      $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'RBT', 0, "R", 0);
    }
  }
  // bimestre do exercício corrente
  for ($y = 1; $y <= $iMesFinalAtual; $y++) {

    if (isset($TrecB[0][$y])) {

      if (isset($TrecB[1][$y])) {

        $pdf->cell($cl, $alt, db_formatar($TrecB[0][$y] - $TrecB[1][$y], 'f'), 'RTB', 0, "R", 0);
        $total += $TrecB[0][$y] - $TrecB[1][$y];
      }else {

        $pdf->cell($cl, $alt, db_formatar($TrecB[0][$y], 'f'), 'RTB', 0, "R", 0);
        $total += $TrecB[0][$y];
      }
    } else {
      $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'TRB', 0, "R", 0);
    }
    //		echo "$y: " . $TrecB[0][$y] . "<br>";
  }

  $pdf->cell($cl, $alt, db_formatar($total, 'f'), 'TRB', 0, "R", 0);
  if (isset($TrecB[0][13])) {

    if (isset($TrecB[1][13])) {
      $pdf->cell($cl, $alt, db_formatar($TrecB[0][13] - $TrecB[1][13], 'f'), 'TB', 0, "R", 0);
    } else {
      $pdf->cell($cl, $alt, db_formatar($TrecB[0][13], 'f'), 'TB', 0, "R", 0);
    }
  }else {
    $pdf->cell($cl, $alt, db_formatar(0, 'f'), 'TB', 0, "R", 0);
  }

  $pdf->ln();
  // ----------------------------------------------------------------
  $oRelatorio = new relatorioContabil($iCodigoRelatorio, false);
  $oRelatorio->getNotaExplicativa($pdf, $iCodigoPeriodo,280);
  $pdf->ln(15);
  assinaturas($pdf, $classinatura,'LRF');
  $pdf->Output();

}

?>
