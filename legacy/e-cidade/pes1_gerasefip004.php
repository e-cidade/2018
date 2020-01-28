<?
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libgertxtfolha.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sql.php"));

require_once(modification("classes/db_codmovsefip_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_cfpess_classe.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));
require_once(modification("classes/db_rhpessoalmov_classe.php"));
require_once(modification("classes/db_rhpesdoc_classe.php"));
require_once(modification("classes/db_rhpesrescisao_classe.php"));
require_once(modification("classes/db_rescisao_classe.php"));
require_once(modification("classes/db_afasta_classe.php"));
require_once(modification("classes/db_inssirf_classe.php"));
require_once(modification("classes/db_rhlota_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

global $conn;
//db_postmemory($HTTP_POST_VARS);

$clcodmovsefip   = new cl_codmovsefip;
$cldb_config     = new cl_db_config;
$clcfpess        = new cl_cfpess;
$clrhpessoal     = new cl_rhpessoal;
$clrhpessoalmov  = new cl_rhpessoalmov;
$clrhpesdoc      = new cl_rhpesdoc;
$clrhpesrescisao = new cl_rhpesrescisao;
$clrescisao      = new cl_rescisao;
$clafasta        = new cl_afasta;
$clinssirf       = new cl_inssirf;
$cllayout_SEFIP  = new cl_layout_SEFIP;
// kill($cllayout_SEFIP);
$clrhlota        = new cl_rhlota;
$db_opcao        = 1;
$db_botao        = true;
$sFgts           = "1";
$iInstit         = db_getsession("DB_instit");
$aListaGerados   = array();
$aListaSemPIS    = array();
$lErro           = false;

if ( isset($oPost->gerar) ) {

  $iAnoUsu = db_formatar($oPost->anousu,"s","0",4,"e",0);
  $iMesUsu = db_formatar($oPost->mesusu,"s","0",2,"e",0);

  try {

    db_inicio_transacao();

    if ( $oPost->r70_numcgm == 0 ) {

      $rsDBConfig   = $cldb_config->sql_record($cldb_config->sql_query_file($iInstit));
      $sWhereRHLota = "";

    } else {

      $sCamposConfig = " z01_cgccpf as cgc,
      z01_nome   as nomeinst,
      z01_ender  as ender,
      z01_bairro as bairro,
      z01_cep    as cep,
      z01_munic  as munic,
      z01_uf     as uf,
      z01_telef  as fone,
      z01_email  as email";

      $sWhereConfig  = "     r70_numcgm = {$oPost->r70_numcgm} ";
      $sWhereConfig .= " and r70_instit = {$iInstit}            ";

      $sSqlDbConfig  = $clrhlota->sql_query_lota_cgm(null,
          $sCamposConfig,
          null,
          $sWhereConfig);
      $rsDBConfig    = $clrhlota->sql_record($sSqlDbConfig);
      $sWhereRHLota  = " and rh02_lota in ( select r70_codigo
      from rhlota
      where r70_instit = {$iInstit}
      and r70_numcgm = {$oPost->r70_numcgm}) ";
    }

    if ( $oPost->r70_numcgm == 0 ) {
      if( $cldb_config->numrows == 0 ) {
        throw new Exception("ERRO: Instituição não encontrada. Arquivo não poderá gerado.");
      }
    } else {
      if($clrhlota->numrows == 0){
        throw new Exception("ERRO: CGM não encontrado. Arquivo não poderá gerado.");
      }
    }

    $sSqlCfPessPrev = $clcfpess->sql_query_file(db_anofolha(),db_mesfolha(),db_getsession("DB_instit"), "r11_mes13" );
    $rsCfPessPrev   = $clcfpess->sql_record($sSqlCfPessPrev);

    if ( $clcfpess->numrows > 0 ) {

      $sMesPag13 = db_utils::fieldsMemory($rsCfPessPrev,0)->r11_mes13;
    } else {
      $sMesPag13 = 12;
    }

    $lMes13 = false;

    if( $iMesUsu == 13 ){

      $iMesUsu = $sMesPag13;
      $lMes13  = true;
    }

    $rsCfPess = $clcfpess->sql_record($clcfpess->sql_query_file($iAnoUsu,$iMesUsu,db_getsession("DB_instit")));

    if ( $clcfpess->numrows == 0 ) {

      throw new Exception("ERRO: Configuração da folha não encontrada para o Ano/Mês (".$iAnoUsu."/".$iMesUsu."). Arquivo não poderá ser gerado.");

    } else {
      $oConfig = db_utils::fieldsMemory($rsDBConfig,0);
      $oCfPess = db_utils::fieldsMemory($rsCfPess,0);

      $iMesAnt = (int)$iMesUsu - 1;
      $iAnoAnt = (int)$iAnoUsu;

      if ( $iMesAnt == 0 ) {

        $iMesAnt = 12;
        $iAnoAnt-= 1;
      }

      $iAnoAnt = db_formatar($iAnoAnt,"s","0",4,"e",0);
      $iMesAnt = db_formatar($iMesAnt,"s","0",2,"e",0);

      $sWherePrev  = "     r33_anousu = {$iAnoUsu}                 ";
      $sWherePrev .= " and r33_mesusu = {$iMesUsu}                 ";
      $sWherePrev .= " and r33_codtab = {$oCfPess->r11_tbprev} + 2 ";
      $sWherePrev .= " and r33_instit = ".db_getsession('DB_instit');

      $sSqlPrev    = $clinssirf->sql_query_file( null,
          null,
          "r33_rubmat",
          "r33_nome limit 1",
          $sWherePrev );

      $rsPrev = $clinssirf->sql_record($sSqlPrev);

      $oPrev  = db_utils::fieldsMemory($rsPrev,0);

      $clgera_sql_folha = new cl_gera_sql_folha;
      $clgera_sql_folha->usar_res  = true;
      $clgera_sql_folha->usar_doc  = true;
      $clgera_sql_folha->usar_cgm  = true;
      $clgera_sql_folha->usar_fgt  = true;
      $clgera_sql_folha->usar_fun  = true;
      $clgera_sql_folha->usar_ins  = true;
      $clgera_sql_folha->usar_tpc  = true;
      $clgera_sql_folha->usar_atv  = true;
      $clgera_sql_folha->inner_ins = false;
      $clgera_sql_folha->inner_doc = false;
      $clgera_sql_folha->inner_fgt = false;
      $clgera_sql_folha->inner_atv = false;


      $sWhereRescisao  = "      rh02_anousu = {$iAnoAnt}  ";
      $sWhereRescisao .= "  and rh02_mesusu = {$iMesAnt}  ";
      $sWhereRescisao .= "  and rh02_regist = rh01_regist ";
      $sWhereRescisao .= "  and rh02_instit = {$iInstit}  ";
      $sWhereRescisao .= "  and rh05_recis is not null    ";

      $sWhereMatriculasSelecionadas = '';
      if (isset($matriculasselecionadas) && !empty($matriculasselecionadas)) {
        $sWhereMatriculasSelecionadas = "and rh01_regist in({$matriculasselecionadas})";
      }

      $sSubSqlRescisao = $clrhpessoalmov->sql_query_rescisao(null,"rh02_regist",null,$sWhereRescisao);
      $sCampos   = "rh01_regist,
      h13_tpcont,
      z01_numcgm,
      z01_nome,
      rh01_admiss,
      rh16_ctps_n,
      rh16_ctps_s,
      z01_ender,
      z01_bairro,
      z01_cep,
      z01_munic,
      z01_uf,
      rh05_recis,
      rh02_ocorre,
      rh51_basefo,
      rh51_descfo,
      rh51_b13fo,
      rh51_d13fo,
      rh51_ocorre,
      rh16_pis,
      rh15_data,
      rh01_nasc,
      rh37_cbo,
      rh30_regime,
      rh05_causa,
      rh05_caub,
      1 as tipo,
      cast(0 as varchar) as codigoautonomo";

      $sCamposAutonomos = " z01_numcgm as rh01_regist,
      '13' as h13_tpcont,
      z01_numcgm,
      z01_nome,
      null as rh01_admiss,
      null as rh16_ctps_n,
      null as rh16_ctps_s,
      z01_ender,
      z01_bairro,
      z01_cep,
      z01_munic,
      z01_uf,
      null as rh05_recis,
      null as rh02_ocorre,
      null as rh51_basefo,
      null as rh51_descfo,
      null as rh51_b13fo,
      null as rh51_d13fo,
      null as rh51_ocorre,
      cast(z01_pis as varchar) as rh16_pis,
      null as rh15_data,
      z01_nasc as rh01_nasc,
      rh70_estrutural as rh37_cbo,
      null as rh30_regime,
      null as rh05_causa,
      null as rh05_caub,
      2 as tipo,
      ( select array_to_string(array_accum(rh89_sequencial), ',')
      from rhautonomolanc as subquery
      where subquery.rh89_numcgm = z01_numcgm
      and rh89_mesusu = $iMesUsu
      and rh89_anousu = $iAnoUsu ) as codigoautonomo ";

      $sSqlDados = $clgera_sql_folha->gerador_sql( "",
          $iAnoUsu,
          $iMesUsu,
          null,
          null,
          " {$sCampos} ",
          "",
          "      rh02_tbprev in ({$oPost->checkboxes})
          and (   rh05_recis is null
          or (     rh05_recis is not null
          and (
          (     cast(extract(year  from rh05_recis) as integer) = {$iAnoUsu}
      and cast(extract(month from rh05_recis) as integer) = {$iMesUsu}
      )
      or (     cast(extract(year  from rh05_recis) as integer) = {$iAnoAnt}
      and cast(extract(month from rh05_recis) as integer) = {$iMesAnt}
      and rh01_regist not in ({$sSubSqlRescisao})
      )
      )
      )
      )
      {$sWhereRHLota}
      {$sWhereMatriculasSelecionadas}
      ", $iInstit);

      $sSqlAutonomos  = "select {$sCamposAutonomos}                             ";
      $sSqlAutonomos .= "  from rhautonomolanc                                  ";
      $sSqlAutonomos .= " inner join cgm       on rh89_numcgm     = z01_numcgm  ";
      $sSqlAutonomos .= "  left join cgmfisico on z04_numcgm      = rh89_numcgm ";
      $sSqlAutonomos .= "  left join rhcbo     on rh70_sequencial = z04_rhcbo   ";
      $sSqlAutonomos .= " where rh89_anousu = {$iAnoUsu}                        ";
      $sSqlAutonomos .= "   and rh89_mesusu = {$iMesUsu}                        ";
      $sSqlAutonomos .= " order by rh16_pis asc, rh01_admiss asc                ";

      $rsDados = $clrhpessoal->sql_record($sSqlDados." union $sSqlAutonomos");

      if ( $clrhpessoal->numrows == 0 ) {
        throw new Exception("Nenhum registro encontrado no Ano/Mês ({$iAnoUsu}/{$iMesUsu}). Arquivo não poderá ser gerado.");
      } else {

        /**
         * incluimos a geracao do arquivo
         */
        require_once(modification("classes/db_rhsefip_classe.php"));
        $oSefip = new cl_rhsefip;
        $oSefip->rh90_anousu   = $iAnoUsu;
        $oSefip->rh90_mesusu   = $iMesUsu;
        $oSefip->rh90_ativa    = "true";
        $oSefip->rh90_compfim  = "";
        $oSefip->rh90_compini  = "";
        $oSefip->rh90_arquivo  = "";
        if (isset($oPost->gerarcompensacao) && $oPost->gerarcompensacao == 1) {

          $oSefip->rh90_compfim   = "$oPost->anocompefinal/$oPost->mescompefinal";
          $oSefip->rh90_compini   = "$oPost->anocompeinicial/$oPost->mescompeinicial";
          $oSefip->rh90_valorcomp = $oPost->valorcompensacao;
        }
        $oSefip->rh90_datagera   = date("Y-m-d", db_getsession("DB_datausu"));
        $oSefip->rh90_horagera   = db_hora();
        $oSefip->rh90_id_usuario = db_getsession("DB_id_usuario");
        $oSefip->rh90_instit     = db_getsession("DB_instit");
        $oSefip->incluir(null);
        if ($oSefip->erro_status == 0) {
          throw new Exception("Erro ao Gerar Sefip:{$oSefip->erro_msg}");
        }
        $cllayout_SEFIP->nomearq = "/tmp/SEFIP.RE";

        $clgera_sql_folha->inicio_rh = false;
        $clgera_sql_folha->usar_pes  = false;
        $clgera_sql_folha->usar_res  = false;
        $clgera_sql_folha->usar_doc  = false;
        $clgera_sql_folha->usar_cgm  = false;
        $clgera_sql_folha->usar_fgt  = false;
        $clgera_sql_folha->usar_fun  = false;
        $clgera_sql_folha->usar_ins  = false;
        $clgera_sql_folha->usar_tpc  = false;
        $clgera_sql_folha->usar_atv  = false;

        $nTotalSalFamilia     = 0;
        $nTotalSalMaternidade = 0;

        if ( !$lMes13 ) {
          $aSiglas = array("r14","r48","r35","r20");
        } else {
          $aSiglas = array("r35");
        }

        $sRubricaInssSal  = "R9".db_formatar((($oCfPess->r11_tbprev * 3) - 2),"s","0",2,"e",0);
        $sRubricaInssS13  = "R9".db_formatar((($oCfPess->r11_tbprev * 3) - 1),"s","0",2,"e",0);
        $sRubricaInssFer  = "R9".db_formatar((($oCfPess->r11_tbprev * 3))    ,"s","0",2,"e",0);

        if ( trim($oCfPess->r11_rubdec) != "" ){
          $sRubricaAdiantamento = ",'".$oCfPess->r11_rubdec."'";
        } else {
          $sRubricaAdiantamento = '';
        }

        $aSalarioMaternidade = Array();
        $aSalarioFamilia     = Array();
        $aBaseFGTS           = Array();
        $aBaseFGTS13         = Array();
        $aFGTS13             = Array();
        $aFGTS               = Array();
        $aBaseINSS           = Array();
        $aBaseINSS13         = Array();
        $aBaseDescINSS       = Array();
        $aDescINSS           = Array();
        $aDescINSS13         = Array();
        $aBaseINSSR990       = Array();
        $aComplemento13      = Array();

        for ( $i=0; $i < $clrhpessoal->numrows; $i++ ){

          $oPessoal = db_utils::fieldsMemory($rsDados, $i);
          if ($oPessoal->tipo == 2) {
            $oPessoal->rh01_regist = $oPessoal->z01_numcgm;
          }
          $nSalarioMaternidade = 0;
          $nSalarioFamilia     = 0;
          $nBaseFGTS           = 0;
          $nBaseFGTS13         = 0;
          $nFGTS13             = 0;
          $nFGTS               = 0;
          $nBaseINSS           = 0;
          $nBaseINSS13         = 0;
          $nBaseDescINSS       = 0;
          $nDescINSS           = 0;
          $nDescINSS13         = 0;
          $nBaseINSSR990       = 0;
          $nDescFolha          = 0;
          /**
           * apenas calculamos as rubricas para funcionários da prefeitura
           */
          if ($oPessoal->tipo == 1) {

            for ( $in=0; $in < count($aSiglas); $in++) {

              $sSqlGer = $clgera_sql_folha->gerador_sql( $aSiglas[$in],
                  $iAnoUsu,
                  $iMesUsu,
                  $oPessoal->rh01_regist,
                  null,
                  " #s#_valor  as valor,
                  #s#_rubric as rubri ",
                  "",
                  " #s#_rubric in ('R993',
                  'R919',
                  'R921',
                  'R985',
                  'R986',
                  'R987',
                  'R990',
                  'R991',
                  'R996',
                  '{$sRubricaInssSal}',
                  '{$sRubricaInssFer}',
                  '{$sRubricaInssS13}',
                  '{$oPrev->r33_rubmat}'
                  {$sRubricaAdiantamento}) ",
                  $iInstit
                  );
                  $rsDadosGer = db_query($sSqlGer);

                  $iLinhasGer = pg_num_rows($rsDadosGer);

                  for ( $im=0 ; $im < $iLinhasGer; $im++ ) {

                    $oDadosGer = db_utils::fieldsMemory($rsDadosGer, $im);

                    if ( in_array($oDadosGer->rubri,array('R919','R921')) ) {

                      $nTotalSalFamilia += $oDadosGer->valor;
                      $nSalarioFamilia  += $oDadosGer->valor;
                    }

                    if ( $oDadosGer->rubri == 'R991' ) {

                      $nBaseFGTS += $oDadosGer->valor;

                      if ($aSiglas[$in] == "r35"){

                        $nBaseFGTS13 += $oDadosGer->valor;
                        $nBaseFGTS   -= $oDadosGer->valor;

                      } else if($aSiglas[$in] == "r10"){

                        $nFGTS13 += $oDadosGer->valor;
                      }

                    }

                    if ( $oDadosGer->rubri == "R996" && $aSiglas[$in] != "r35" ) {
                      $nBaseFGTS += $oDadosGer->valor;
                    }

                    if ( $oDadosGer->rubri == $sRubricaAdiantamento && $aSiglas[$in] != "r20" && $aSiglas[$in] != "r35" ) {
                      $nBaseFGTS   -= $oDadosGer->valor;
                      $nBaseFGTS13 += $oDadosGer->valor;
                    }

                    if (( $oDadosGer->rubri == "R985" || $oDadosGer->rubri == "R987") && $aSiglas[$in] != "r35" ) {
                      $nBaseINSS     += $oDadosGer->valor;
                      $nBaseDescINSS += $oDadosGer->valor;
                    }

                    if ( $oDadosGer->rubri == "R986" ) {

                      if ( $aSiglas[$in] == "r35" ){

                        if ( $lMes13 ) {
                          $nBaseINSS13 += $oDadosGer->valor;
                        } else {
                          $nBaseINSS13 += 0;
                        }
                      } else {
                        $nBaseINSS13 += $oDadosGer->valor;
                      }

                      if ( $aSiglas[$in] != "r35" and $aSiglas[$in] != "r20" && $oDadosGer->valor > 0) {
                        $aComplemento13[$oPessoal->rh01_regist] = "1";
                      }
                    }

                    if(($oDadosGer->rubri == $sRubricaInssSal || $oDadosGer->rubri == $sRubricaInssFer) && $aSiglas[$in] != "r35"){
                      $nDescINSS += $oDadosGer->valor;
                    }

                    if ( !$lMes13 && $oDadosGer->rubri == $sRubricaInssS13 && $aSiglas[$in] != "r35") {
                      $nDescINSS   += $oDadosGer->valor;
                    } else if( $lMes13 && $oDadosGer->rubri == $sRubricaInssS13) {
                      $nDescINSS13 += $oDadosGer->valor;
                    }

                    if( $oDadosGer->rubri == $oPrev->r33_rubmat){
                      $nSalarioMaternidade  += $oDadosGer->valor;
                      $nTotalSalMaternidade += $oDadosGer->valor;
                    }

                    if($oDadosGer->rubri == "R990"){
                      if($aSiglas[$in] == "r10"){
                        $baseinssr990 += $oDadosGer->valor;
                      }
                    }

                    if($oDadosGer->rubri == "R993"){
                      if($aSiglas[$in] == "r14" || $aSiglas[$in] == "r20" || $aSiglas[$in] == "r35" || $aSiglas[$in] == "r48"){
                        $nDescFolha += $oDadosGer->valor;
                      }
                    }
                  }
            }
          } else if ($oPessoal->tipo == 2) {

            $sSqlValoresAutonomos  = "select rh89_sequencial,
            rh89_valorserv, ";
            $sSqlValoresAutonomos .= "       rh89_valorretinss ";
            $sSqlValoresAutonomos .= "  from rhautonomolanc ";
            $sSqlValoresAutonomos .= " where rh89_sequencial in ({$oPessoal->codigoautonomo}) ";
            $rsValoresAutonomos    = db_query($sSqlValoresAutonomos);

            $nBaseINSS   = 0;
            $nDescINSS   = 0;
            $nDescFolha  = 0;

            if (pg_num_rows($rsValoresAutonomos) > 0) {

              foreach ( db_utils::getCollectionByRecord($rsValoresAutonomos) as $oDadosAutonomos){

                $nBaseINSS  += $oDadosAutonomos->rh89_valorserv;
                $nDescINSS  += $oDadosAutonomos->rh89_valorretinss;
                $nDescFolha += $oDadosAutonomos->rh89_valorretinss;

                /**
                 * inclui o autono como gerado
                 */
                $oDaoAutonomo = db_utils::getDao("rhsefiprhautonomolanc");
                $oDaoAutonomo->rh92_rhautonomolanc = $oDadosAutonomos->rh89_sequencial;
                $oDaoAutonomo->rh92_rhsefip        = $oSefip->rh90_sequencial;
                $oDaoAutonomo->incluir(null);
                /**
                 * Alteramos o registro do autonomo como processado
                 */
                $oDaoAutonomoLancamento = db_utils::getDao("rhautonomolanc");
                $oDaoAutonomoLancamento->rh89_sequencial = $oDadosAutonomos->rh89_sequencial;
                $oDaoAutonomoLancamento->rh89_processado = "true";
                $oDaoAutonomoLancamento->alterar($oDadosAutonomos->rh89_sequencial);
                unset($oDadosAutonomos);
              }
            }
          }

          $aSalarioMaternidade[$oPessoal->rh01_regist] = $nSalarioMaternidade;
          $aSalarioFamilia    [$oPessoal->rh01_regist] = $nSalarioFamilia;
          $aBaseFGTS          [$oPessoal->rh01_regist] = $nBaseFGTS;
          $aBaseFGTS13        [$oPessoal->rh01_regist] = $nBaseFGTS13;
          $aFGTS13            [$oPessoal->rh01_regist] = $nFGTS13;
          $aFGTS              [$oPessoal->rh01_regist] = $nFGTS;
          $aBaseINSS          [$oPessoal->rh01_regist] = $nBaseINSS;
          $aBaseINSS13        [$oPessoal->rh01_regist] = $nBaseINSS13;
          $aBaseDescINSS      [$oPessoal->rh01_regist] = $nBaseDescINSS;
          $aDescINSS          [$oPessoal->rh01_regist] = $nDescINSS;
          $aDescINSS13        [$oPessoal->rh01_regist] = $nDescINSS13;
          $aBaseINSSR990      [$oPessoal->rh01_regist] = $nBaseINSSR990;
          $aDescFolha         [$oPessoal->rh01_regist] = $nDescFolha;

          /*
           * Altera o inicio da linha do header para 00 quando há recolhimento de FGTS do contrário inicia com 01
          */
          if ($nBaseFGTS != 0 || $nBaseFGTS13 != 0 || $nFGTS13 != 0 || $nFGTS != 0) {
            $sFgts = " ";
          }

        }


        $cllayout_SEFIP->SFPRegistro00_056_069 = $oConfig->cgc;
        $cllayout_SEFIP->SFPRegistro00_070_099 = $oConfig->nomeinst;
        $cllayout_SEFIP->SFPRegistro00_100_119 = $oPost->contato;
        $cllayout_SEFIP->SFPRegistro00_120_169 = $oConfig->ender;
        $cllayout_SEFIP->SFPRegistro00_170_189 = $oConfig->bairro;
        $cllayout_SEFIP->SFPRegistro00_190_197 = $oConfig->cep;
        $cllayout_SEFIP->SFPRegistro00_198_217 = $oConfig->munic;
        $cllayout_SEFIP->SFPRegistro00_218_219 = $oConfig->uf;
        $cllayout_SEFIP->SFPRegistro00_220_231 = @$oPost->fone;
        $cllayout_SEFIP->SFPRegistro00_232_291 = $oConfig->email;

        if ( $lMes13 ) {
          $cllayout_SEFIP->SFPRegistro00_292_297 = $iAnoUsu."13";
        } else {
          $cllayout_SEFIP->SFPRegistro00_292_297 = $iAnoUsu.$iMesUsu;
        }

        $cllayout_SEFIP->SFPRegistro00_298_300 = $oPost->codrec;

        if ( $lMes13 ) {

          $cllayout_SEFIP->SFPRegistro00_301_301 = ' ';
          $cllayout_SEFIP->SFPRegistro00_302_302 = "1";
        } else {

          $cllayout_SEFIP->SFPRegistro00_301_301 = $oPost->indrecfgts;
          $cllayout_SEFIP->SFPRegistro00_302_302 = $sFgts;
        }



        $cllayout_SEFIP->SFPRegistro00_303_310 = db_formatar($oPost->dtrecfgts_dia,"s",(trim($oPost->dtrecfgts_dia)==""?" ":"0"),2,"e",0)."-".db_formatar($oPost->dtrecfgts_mes,"s",(trim($oPost->dtrecfgts_mes)==""?" ":"0"),2,"e",0)."-".db_formatar($oPost->dtrecfgts_ano,"s",(trim($oPost->dtrecfgts_ano)==""?" ":"0"),4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_311_311 = $indrecinss;
        $cllayout_SEFIP->SFPRegistro00_312_319 = db_formatar($oPost->dtrecinss_dia,"s",(trim($oPost->dtrecinss_dia)==""?" ":"0"),2,"e",0)."-".db_formatar($oPost->dtrecinss_mes,"s",(trim($oPost->dtrecinss_mes)==""?" ":"0"),2,"e",0)."-".db_formatar($oPost->dtrecinss_ano,"s",(trim($oPost->dtrecinss_ano)==""?" ":"0"),4,"e",0);
        $cllayout_SEFIP->SFPRegistro00_320_326 = $oPost->indatrasoinss;
        $cllayout_SEFIP->SFPRegistro00_328_341 = $oConfig->cgc;
        $cllayout_SEFIP->geraRegist00SFP();

        if($iAnoUsu < 2009){
          $sAlteraCnae = 'N';
        } else {
          $sAlteraCnae = $oPost->alteracnae;
        }

        $cllayout_SEFIP->SFPRegistro10_004_017 = $oConfig->cgc;
        $cllayout_SEFIP->SFPRegistro10_054_093 = $oConfig->nomeinst;
        $cllayout_SEFIP->SFPRegistro10_094_143 = $oConfig->ender;
        $cllayout_SEFIP->SFPRegistro10_144_163 = $oConfig->bairro;
        $cllayout_SEFIP->SFPRegistro10_164_171 = $oConfig->cep;
        $cllayout_SEFIP->SFPRegistro10_172_191 = $oConfig->munic;
        $cllayout_SEFIP->SFPRegistro10_192_193 = $oConfig->uf;
        $cllayout_SEFIP->SFPRegistro10_194_205 = $fone;
        $cllayout_SEFIP->SFPRegistro10_206_206 = $oPost->alteraender;
        $cllayout_SEFIP->SFPRegistro10_207_213 = $oPost->cnae;
        $cllayout_SEFIP->SFPRegistro10_214_214 = $sAlteraCnae;
        $cllayout_SEFIP->SFPRegistro10_215_216 = str_pad(trim($oPost->aliqsat),2,"0",STR_PAD_RIGHT);
        $cllayout_SEFIP->SFPRegistro10_219_221 = $oCfPess->r11_cdfpas;
        $cllayout_SEFIP->SFPRegistro10_222_225 = $oPost->codterceiro;
        $cllayout_SEFIP->SFPRegistro10_226_229 = $oPost->codgps;
        $cllayout_SEFIP->SFPRegistro10_235_249 = $nTotalSalFamilia;     // Total geral do salario familia
        $cllayout_SEFIP->SFPRegistro10_250_264 = $nTotalSalMaternidade; // Total geral do salario maternidade

        $cllayout_SEFIP->geraRegist10SFP();

        if (isset($oPost->gerarcompensacao) && $oPost->gerarcompensacao == 1) {

          $cllayout_SEFIP->SFPRegistro12_147_161 = $oPost->valorcompensacao;
          $cllayout_SEFIP->SFPRegistro12_004_017 = $oConfig->cgc;
          $cllayout_SEFIP->SFPRegistro12_162_167 = $oPost->anocompeinicial.$oPost->mescompeinicial;
          $cllayout_SEFIP->SFPRegistro12_168_173 = $oPost->anocompefinal.$oPost->mescompefinal;
          $cllayout_SEFIP->geraRegist12SFP();
        }
        for( $i=0; $i < $clrhpessoal->numrows; $i++) {

          $oPessoal = db_utils::fieldsMemory($rsDados, $i);
          if ($oPessoal->tipo == 2) {
            $oPessoal->rh01_regist = $oPessoal->z01_numcgm;
          }
          if((int)$oPessoal->rh16_pis > 0 && $alteraender == 'S'){

            if( $aSalarioFamilia[$oPessoal->rh01_regist] > 0 || $aBaseFGTS    [$oPessoal->rh01_regist] > 0 || $aBaseFGTS13[$oPessoal->rh01_regist] > 0 ||
                $aFGTS13        [$oPessoal->rh01_regist] > 0 || $aFGTS        [$oPessoal->rh01_regist] > 0 || $aBaseINSS  [$oPessoal->rh01_regist] > 0 ||
                $aBaseINSS13    [$oPessoal->rh01_regist] > 0 || $aBaseDescINSS[$oPessoal->rh01_regist] > 0 || $aDescINSS  [$oPessoal->rh01_regist] > 0 ||
                $aDescINSS13    [$oPessoal->rh01_regist] > 0 || $aBaseINSSR990[$oPessoal->rh01_regist] > 0) {

              $cllayout_SEFIP->SFPRegistro14_004_017 = $oConfig->cgc;
              $cllayout_SEFIP->SFPRegistro14_054_064 = $oPessoal->rh16_pis;
              $cllayout_SEFIP->SFPRegistro14_065_072 = db_formatar($oPessoal->rh01_admiss,"d");
              $cllayout_SEFIP->SFPRegistro14_073_074 = $oPessoal->h13_tpcont;
              $cllayout_SEFIP->SFPRegistro14_075_144 = $oPessoal->z01_nome;
              $cllayout_SEFIP->SFPRegistro14_145_151 = $oPessoal->rh16_ctps_n;
              $cllayout_SEFIP->SFPRegistro14_152_156 = $oPessoal->rh16_ctps_s;
              $cllayout_SEFIP->SFPRegistro14_157_206 = $oPessoal->z01_ender;
              $cllayout_SEFIP->SFPRegistro14_207_226 = $oPessoal->z01_bairro;
              $cllayout_SEFIP->SFPRegistro14_227_234 = $oPessoal->z01_cep;
              $cllayout_SEFIP->SFPRegistro14_235_254 = $oPessoal->z01_munic;
              $cllayout_SEFIP->SFPRegistro14_255_256 = $oPessoal->z01_uf;

              $aListaGerados[$oPessoal->rh01_regist]['Nome']            = $oPessoal->z01_nome;
              $aListaGerados[$oPessoal->rh01_regist]['TipoContrato']    = $oPessoal->h13_tpcont;
              $aListaGerados[$oPessoal->rh01_regist]['DescPrevidencia'] = $aDescINSS[$oPessoal->rh01_regist];
              $aListaGerados[$oPessoal->rh01_regist]['Desc13']          = $aDescINSS13[$oPessoal->rh01_regist];
              $aListaGerados[$oPessoal->rh01_regist]['DescFolha']       = $aDescFolha[$oPessoal->rh01_regist];


              $cllayout_SEFIP->geraRegist14SFP();
            }
          } else {
          }
        }

        for ( $i=0; $i < $clrhpessoal->numrows; $i++ ){

          $oPessoal = db_utils::fieldsmemory($rsDados, $i);
          if ($oPessoal->tipo == 2) {
            $oPessoal->rh01_regist = $oPessoal->z01_numcgm;
          }
          if((int)$oPessoal->rh16_pis > 0 ){

            $remuneracao13 = 0;

            if($aSalarioFamilia[$oPessoal->rh01_regist] > 0 || $aBaseFGTS[$oPessoal->rh01_regist]     > 0 || $aBaseFGTS13[$oPessoal->rh01_regist] > 0 ||
              $aFGTS13[$oPessoal->rh01_regist]     > 0 || $aFGTS[$oPessoal->rh01_regist]          > 0 || $aBaseINSS[$oPessoal->rh01_regist]   > 0 ||
              $aBaseINSS13[$oPessoal->rh01_regist] > 0 || $aBaseDescINSS[$oPessoal->rh01_regist] > 0 || $aDescINSS[$oPessoal->rh01_regist]    > 0 ||
              $aDescINSS13[$oPessoal->rh01_regist] > 0  || $aBaseINSSR990[$oPessoal->rh01_regist] > 0){

              if ($oPessoal->h13_tpcont >= 12) {
                if($iMesUsu == 12 && trim($oPessoal->rh05_recis) != "" ){

                  $remuneracaosem13 = $aBaseINSS[$oPessoal->rh01_regist];
                  $remuneracao13 = 0;
                }else{

                  $remuneracaosem13 = $aBaseINSS[$oPessoal->rh01_regist];
                  $remuneracao13 = $aBaseINSS13[$oPessoal->rh01_regist];
                }
              } else {

                $remuneracaosem13 = $aBaseFGTS[$oPessoal->rh01_regist];
                $remuneracao13 = $aBaseFGTS13[$oPessoal->rh01_regist];
              }

              if(( $lMes13 && $remuneracao13 == 0 && $aBaseINSS13[$oPessoal->rh01_regist] == 0) || ($lMes13 && $oPessoal->h13_tpcont == 13) ){
                continue;
              }

              if(trim($oPessoal->rh05_recis) != "" && $codrec == "115"){

                if($oPessoal->h13_tpcont < 12){

                  if($remuneracaosem13 > 0){
                    $remuneracaosem13 -= $aBaseINSS13[$oPessoal->rh01_regist];
                  }else{
                    $remuneracaosem13 = $aBaseINSS13[$oPessoal->rh01_regist];
                  }

                  if($remuneracaosem13 < 0){
                    $remuneracaosem13 = 0;
                  }
                }
              }else{
                if($lMes13){
                  $remuneracaosem13 = 0;
                }
              }

              if((trim($oPessoal->rh05_recis) == "" && $lMes13) || (trim($oPessoal->rh05_recis) != "" && !$lMes13)){
                $remuneracao13 = $aBaseINSS13[$oPessoal->rh01_regist];
                if($lMes13){
                  $remuneracao13 = 0;
                }
              }

              $valorrescis = 0;

              $recis_dia = '';
              $recis_mes = '';
              $recis_ano = '';

              if(trim($oPessoal->rh05_recis) != ""){
                $recis_dia = (int) db_subdata($oPessoal->rh05_recis,"d");
                $recis_mes = (int) db_subdata($oPessoal->rh05_recis,"m");
                $recis_ano = (int) db_subdata($oPessoal->rh05_recis,"a");
              }

              if(empty($valorrescis) && !empty($remuneracao13) && $oPessoal->rh05_recis != '') {
                $valorrescis = $remuneracao13;
              }

              if(trim($oPessoal->rh05_recis) != "" || $lMes13){
                if($recis_ano == (int)$iAnoUsu && $recis_mes == (int)$iMesUsu){
                  $valorrescis = $aBaseINSS13[$oPessoal->rh01_regist];
                  if($aBaseINSS13[$oPessoal->rh01_regist] == 0 ){
                    $valorrescis = 0.01;
                  }
                }
                if($lMes13){
                  $valorrescis = $aBaseINSS13[$oPessoal->rh01_regist];
                  $remuneracao13    = 0;
                  $remuneracaosem13 = 0;
                }
              }

              $ocorrencia = trim($oPessoal->rh02_ocorre);
              if(trim($oPessoal->rh02_ocorre) == ""){
                $ocorrencia = "  ";
              }
              if((int)($iAnoUsu.$iMesUsu) > 200306 && ($oPessoal->rh51_basefo > 0 ||
                  $oPessoal->rh51_descfo > 0 || $oPessoal->rh51_b13fo > 0 || $oPessoal->rh51_d13fo > 0)) {
                if(trim($oPessoal->rh51_ocorre) != ""){
                  $ocorrencia = $oPessoal->rh51_ocorre;
                }
              }

              $mpis = false;
              $cont_pis = 0;
              for($in=0; $in<count($aSiglas); $in++){
                $result_pesdoc = $clrhpesdoc->sql_record($clrhpesdoc->sql_query_gerfs(null,
                    "distinct rh16_regist",
                    "",
                    " #s#_anousu = ".$iAnoUsu.
                    " and #s#_mesusu = ".$iMesUsu.
                    " and #s#_instit = ".db_getsession("DB_instit").
                    " and rh02_tbprev in (".$checkboxes.") ",
                    $aSiglas[$in],$oPessoal->rh16_pis));
                if(($cont_pis == 0 || $aSiglas[$in] == "r20") && $clrhpesdoc->numrows > 0){
                  $cont_pis ++;
                }
                if($clrhpesdoc->numrows > 1 || $cont_pis > 1){
                  $mpis = true;
                  break;
                }
              }

              if($mpis == true){
                if(trim($ocorrencia) == "" || (int)$ocorrencia == 1){
                  $ocorrencia = "05";
                }else if((int)$ocorrencia == 2){
                  $ocorrencia = "06";
                }else if((int)$ocorrencia == 3){
                  $ocorrencia = "07";
                }else if((int)$ocorrencia == 4){
                  $ocorrencia = "08";
                }
              }

              $subpes = $iAnoUsu."/".$iMesUsu;
              $situacao_funcionario = 0;
              if ($oPessoal->tipo == 1) {
                $situacao_funcionario = situacao_funcionario($oPessoal->rh01_regist);
              }

              $desconto_seguro = 0;
              if((int)$ocorrencia >= 5){
                if($lMes13){
                  $desconto_seguro = $aDescINSS13[$oPessoal->rh01_regist];
                }else{
                  $desconto_seguro = $aDescINSS[$oPessoal->rh01_regist];
                }
              }

              $xctps_d = str_repeat(" ",8);
              $xctps_n = str_repeat(" ",7);
              $xctps_s = str_repeat(" ",5);

              $stringcategoriactps = "-01-02-03-04-06-07-26";
              $posicaocategoria = strpos($stringcategoriactps,$oPessoal->h13_tpcont);

              if($posicaocategoria !== false){
                $xctps_n = db_formatar($oPessoal->rh16_ctps_n,"s","0",7,"e",0);
                $xctps_s = db_formatar($oPessoal->rh16_ctps_s,"s","0",5,"e",0);
                if(trim($oPessoal->rh15_data) != ""){
                  $xctps_d = db_formatar($oPessoal->rh15_data,"d");
                }
              }

              $data_admiss = str_repeat(" ",8);
              $stringcategoriaadmiss = "-01-03-04-05-06-07-11-12-19-20-21-26";
              $posicaocategoria = strpos($stringcategoriaadmiss,$oPessoal->h13_tpcont);
              if($posicaocategoria !== false){
                $data_admiss = db_formatar($oPessoal->rh01_admiss,"d");
              }

              $stringcategoriaregist = "-06-13-14-15-16-17-18-22-23-24-25";
              $posicaocategoria = strpos($stringcategoriaregist,$oPessoal->h13_tpcont);
              if($posicaocategoria !== false){
                $iRegist = str_repeat(" ",11);
              } else {
                $iRegist = $oPessoal->rh01_regist;
              }
              if ($oPessoal->tipo == 2) {
                $iRegist = str_repeat(" ",11);
              }
              $data_nasc = str_repeat(" ",8);
              $stringcategorianasc = "-01-02-03-04-05-06-07-12-19-20-21-26";
              $posicaocategoria = strpos($stringcategorianasc,$oPessoal->h13_tpcont);
              if($posicaocategoria !== false){
                $data_nasc = db_formatar($oPessoal->rh01_nasc,"d");
              }

              $cllayout_SEFIP->SFPRegistro30_004_017 = $oConfig->cgc;
              $cllayout_SEFIP->SFPRegistro30_033_043 = $oPessoal->rh16_pis;
              $cllayout_SEFIP->SFPRegistro30_044_051 = $data_admiss;
              $cllayout_SEFIP->SFPRegistro30_052_053 = $oPessoal->h13_tpcont;
              $cllayout_SEFIP->SFPRegistro30_054_123 = $oPessoal->z01_nome;
              $cllayout_SEFIP->SFPRegistro30_124_134 = $iRegist;
              $cllayout_SEFIP->SFPRegistro30_135_141 = $xctps_n;
              $cllayout_SEFIP->SFPRegistro30_142_146 = $xctps_s;
              $cllayout_SEFIP->SFPRegistro30_147_154 = $xctps_d;
              $cllayout_SEFIP->SFPRegistro30_155_162 = $data_nasc;
              $cllayout_SEFIP->SFPRegistro30_163_167 = $oPessoal->rh37_cbo;

              $cllayout_SEFIP->SFPRegistro30_168_182 = $remuneracaosem13;
              $cllayout_SEFIP->SFPRegistro30_183_197 = $remuneracao13;
              $cllayout_SEFIP->SFPRegistro30_200_201 = $ocorrencia;
              $cllayout_SEFIP->SFPRegistro30_202_216 = $desconto_seguro;
              $cllayout_SEFIP->SFPRegistro30_217_231 = (($situacao_funcionario == 3 || $situacao_funcionario == 4)?$aBaseINSS[$oPessoal->rh01_regist]:0);
              $cllayout_SEFIP->SFPRegistro30_232_246 = $valorrescis;

              $aListaGerados[$oPessoal->rh01_regist]['Nome']            = $oPessoal->z01_nome;
              $aListaGerados[$oPessoal->rh01_regist]['TipoContrato']    = $oPessoal->h13_tpcont;
              $aListaGerados[$oPessoal->rh01_regist]['BasePrevidencia'] = $remuneracaosem13;
              $aListaGerados[$oPessoal->rh01_regist]['DescPrevidencia'] = $desconto_seguro;
              $aListaGerados[$oPessoal->rh01_regist]['Base13']          = $remuneracao13;
              $aListaGerados[$oPessoal->rh01_regist]['DescFolha']       = $aDescFolha[$oPessoal->rh01_regist];

              if ($oPessoal->h13_tpcont == '01' ) {
                $aListaGerados[$oPessoal->rh01_regist]['FGTS'] = $remuneracaosem13;
              }

              $cllayout_SEFIP->geraRegist30SFP();

              if(!$lMes13 && $oPessoal->tipo == 1) {

                $codmov = "";
                $result_afasta = $clafasta->sql_record($clafasta->sql_query_file(null,
                    "*","r45_regist, r45_dtafas desc",
                    "r45_anousu = ".$iAnoUsu."
                    and r45_mesusu = ".$iMesUsu."
                    and r45_regist = ".$oPessoal->rh01_regist)
                );
                $numrows_afasta = $clafasta->numrows;
                for($in=0; $in<$numrows_afasta; $in++){
                  db_fieldsmemory($result_afasta, $in);

                  // (mes_afas  = mes e  ano_afas =  ano)
                  // (ano_afas  < ano e (dat_reto =  null ou  mes_reto >= mes   e  ano_reto >= ano))
                  // (mes_afas <= ano e  ano_afas <= ano   e (dat_reto  = null ou (mes_reto >= mes e ano_reto >= ano)))


                  if(
                      ((int)db_subdata($r45_dtafas,"m") == (int)$iMesUsu && (int)db_subdata($r45_dtafas,"a") == (int)$iAnoUsu) ||
                      ((int)db_subdata($r45_dtafas,"a") <  (int)$iAnoUsu && (trim($r45_dtreto) == "" || ((int)db_subdata($r45_dtreto,"m") >= (int)$iMesUsu && (int)db_subdata($r45_dtreto,"a") >= (int)$iAnoUsu))) ||
                      (((int)db_subdata($r45_dtafas,"m") <= (int)$iMesUsu && (int)db_subdata($r45_dtafas,"a") <= (int)$iAnoUsu) && (trim($r45_dtreto) == "" || ((int)db_subdata($r45_dtreto,"m") >= (int)$iMesUsu && (int)db_subdata($r45_dtreto,"a") >= (int)$iAnoUsu))) ||
                      ((int)db_subdata($r45_dtreto,"a") > (int)$iAnoUsu)
                  ){
                    $situacao = $r45_situac;
                    $dataafasta = ($situacao == 3 || $situacao == 6 || $situacao == 8)?date("Y-m-d",mktime(0,0,0,db_subdata($r45_dtafas,"m"), db_subdata($r45_dtafas,"d") - 15, db_subdata($r45_dtafas,"a"))):$r45_dtafas;
                    $dataretorno = $r45_dtreto;

                    $dataini = $dataafasta;
                    $datafim = $dataretorno;
                    if($situacao == 3 || $situacao == 6 || $situacao == 8){
                      $dataini = $r45_dtafas;
                    }

                    if(db_subdata($dataafasta,"m") <= $iMesUsu && db_subdata($dataafasta,"a") == $iAnoUsu){
                      $datamov = $dataafasta;
                      $codmov  = $r45_codafa;
                    }

                    if((db_subdata($dataafasta,"m") < $iMesUsu && db_subdata($dataafasta,"a") == $iAnoUsu) || db_subdata($dataafasta,"a") < $iAnoUsu){
                      $result_codmovsefip = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_codigo,r66_mensal","","r66_anousu = ".$iAnoUsu." and r66_mesusu = ".$iMesUsu." and trim(r66_codigo) = '".$r45_codafa."' and r66_mensal = 't'"));
                      if($clcodmovsefip->numrows > 0){
                        db_fieldsmemory($result_codmovsefip, 0);
                        $datamov = $dataafasta;
                        $codmov  = $r45_codafa;
                      }else if(db_subdata($dataretorno,"m") < $iMesUsu && db_subdata($dataretorno,"a") < $iAnoUsu && trim($r45_codret) != ""){
                        $datamov = $dataafasta;
                        $codmov  = $r45_codafa;
                      }else{
                        $iMesAnt = $iMesUsu - 1;
                        $iAnoAnt = $iAnoUsu;
                        if($iMesAnt == 0){
                          $iMesAnt = 12;
                          $iAnoAnt-= 1;
                        }
                        if(db_subdata($dataafasta,"m") == $iMesAnt && db_subdata($dataafasta,"a") == $iAnoAnt){
                          $result_afasta_ant = $clafasta->sql_record($clafasta->sql_query_file(null,"*","","r45_anousu = ".$iAnoAnt." and r45_mesusu = ".$iMesAnt." and r45_regist = ".$oPessoal->rh01_regist." and r45_dtafas = '".$r45_dtafas."' and r45_situac = ".$r45_situac));
                          if($clafasta->numrows > 0){
                            $datamov = $dataafasta;
                            $codmov  = $r45_codafa;
                          }
                        }
                      }
                    }

                    $temreg = false;

                    if(db_subdata($dataretorno,"m") == $iMesUsu && db_subdata($dataretorno,"a") == $iAnoUsu){
                      $datamov = $dataretorno;
                      $codmov  = $r45_codret;
                      $temreg  = true;
                    }

                    $indfgts = "";
                    $result_ifgts = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_ifgtsc,r66_ifgtse","","r66_anousu = ".$iAnoUsu." and r66_mesusu = ".$iMesUsu." and trim(r66_codigo) = '".$r45_codafa."'"));
                    if($clcodmovsefip->numrows > 0){
                      db_fieldsmemory($result_ifgts,0);
                      if($oPessoal->rh30_regime == 2){
                        $indfgts = $r66_ifgtsc;
                      }else{
                        $indfgts = $r66_ifgtse;
                      }
                    }
                    if ($temreg){

                      $cllayout_SEFIP->SFPRegistro32_004_017 = $oConfig->cgc;
                      $cllayout_SEFIP->SFPRegistro32_033_043 = $oPessoal->rh16_pis;
                      $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($oPessoal->rh01_admiss,'d');
                      $cllayout_SEFIP->SFPRegistro32_052_053 = $oPessoal->h13_tpcont;
                      $cllayout_SEFIP->SFPRegistro32_054_123 = $oPessoal->z01_nome;
                      $cllayout_SEFIP->SFPRegistro32_124_125 = $r45_codafa;
                      $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($dataafasta,'d');
                      $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;


                      $cllayout_SEFIP->geraRegist32SFP();



                    }

                    $cllayout_SEFIP->SFPRegistro32_004_017 = $oConfig->cgc;
                    $cllayout_SEFIP->SFPRegistro32_033_043 = $oPessoal->rh16_pis;
                    $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($oPessoal->rh01_admiss,'d');
                    $cllayout_SEFIP->SFPRegistro32_052_053 = $oPessoal->h13_tpcont;
                    $cllayout_SEFIP->SFPRegistro32_054_123 = $oPessoal->z01_nome;
                    $cllayout_SEFIP->SFPRegistro32_124_125 = $codmov;
                    $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($datamov,'d');
                    $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;



                    $aListaGerados[$oPessoal->rh01_regist]['Nome']            = $oPessoal->z01_nome;
                    $aListaGerados[$oPessoal->rh01_regist]['TipoContrato']    = $oPessoal->h13_tpcont;

                    if ( $temreg ) {
                      $aListaGerados[$oPessoal->rh01_regist]['CodAfastamento']  = $r45_codafa;
                      $aListaGerados[$oPessoal->rh01_regist]['DataAfastamento'] = db_formatar($dataafasta,'d');
                      $aListaGerados[$oPessoal->rh01_regist]['CodRetorno']      = $codmov;
                      $aListaGerados[$oPessoal->rh01_regist]['DataRetorno']     = db_formatar($datamov,'d');
                    } else {
                      $aListaGerados[$oPessoal->rh01_regist]['CodAfastamento']  = $codmov;
                      $aListaGerados[$oPessoal->rh01_regist]['DataAfastamento'] = db_formatar($datamov,'d');
                    }

                    $cllayout_SEFIP->geraRegist32SFP();

                  }
                }
              }
              $codmov = "";
              if(trim($oPessoal->rh05_recis) != "" && !$lMes13 ){
                $result_dadosrescisao = $clrescisao->sql_record($clrescisao->sql_query_file($iAnoUsu,
                    $iMesUsu,
                    $oPessoal->rh30_regime,
                    $oPessoal->rh05_causa,
                    $oPessoal->rh05_caub,null,
                    db_getsession("DB_instit"),"r59_movsef")
                );
                if($clrescisao->numrows > 0){
                  db_fieldsmemory($result_dadosrescisao, 0);
                  $codmov = $r59_movsef;
                }

                $indfgts = "";
                $result_ifgts = $clcodmovsefip->sql_record($clcodmovsefip->sql_query_file(null,null,null,"r66_ifgtsc,r66_ifgtse","","r66_anousu = ".$iAnoUsu." and r66_mesusu = ".$iMesUsu." and trim(r66_codigo) = '".$codmov."'"));
                if($clcodmovsefip->numrows > 0){
                  db_fieldsmemory($result_ifgts,0);
                  if($oPessoal->rh30_regime == 2){
                    $indfgts = $r66_ifgtsc;
                  }else{
                    $indfgts = $r66_ifgtse;
                  }
                }

                $cllayout_SEFIP->SFPRegistro32_004_017 = $oConfig->cgc;
                $cllayout_SEFIP->SFPRegistro32_033_043 = $oPessoal->rh16_pis;
                $cllayout_SEFIP->SFPRegistro32_044_051 = db_formatar($oPessoal->rh01_admiss,"d");
                $cllayout_SEFIP->SFPRegistro32_052_053 = $oPessoal->h13_tpcont;
                $cllayout_SEFIP->SFPRegistro32_054_123 = $oPessoal->z01_nome;
                $cllayout_SEFIP->SFPRegistro32_124_125 = $codmov;
                $cllayout_SEFIP->SFPRegistro32_126_133 = db_formatar($oPessoal->rh05_recis,"d");
                $cllayout_SEFIP->SFPRegistro32_134_134 = $indfgts;

                $aListaGerados[$oPessoal->rh01_regist]['Nome']            = $oPessoal->z01_nome;
                $aListaGerados[$oPessoal->rh01_regist]['TipoContrato']    = $oPessoal->h13_tpcont;
                $aListaGerados[$oPessoal->rh01_regist]['CodAfastamento']  = $codmov;
                $aListaGerados[$oPessoal->rh01_regist]['DataAfastamento'] = db_formatar($oPessoal->rh05_recis,"d");

                $cllayout_SEFIP->geraRegist32SFP();
              }
            }
          } else {
            $aListaSemPIS[$oPessoal->rh01_regist]['Nome']         = $oPessoal->z01_nome;
            $aListaSemPIS[$oPessoal->rh01_regist]['TipoContrato'] = $oPessoal->h13_tpcont;
          }
        }
        $cllayout_SEFIP->geraRegist90SFP();
        $cllayout_SEFIP->gera();
      }
      /**
       * gravamos o arquivo da sefip
       */

      $rArquivo      = fopen($cllayout_SEFIP->nomearq, "rb");
      $rDadosArquivo = fread($rArquivo, filesize($cllayout_SEFIP->nomearq));
      fclose($rArquivo);

      $oOidBanco     = pg_lo_create();
      $oSefip->rh90_arquivo = $oOidBanco;
      $oSefip->rh90_sequencial = $oSefip->rh90_sequencial;
      $oSefip->alterar($oSefip->rh90_sequencial);

      if ( $oSefip->erro_status == 0 ) {
        throw new Exception("Erro ao gravar arquivo Sefip:{$oSefip->erro_msg}");
      }


      $oObjetoBanco = pg_lo_open($conn, $oOidBanco, "w");
      pg_lo_write($oObjetoBanco, $rDadosArquivo);
      pg_lo_close($oObjetoBanco);
    }

    if ( count($aListaGerados) > 0 ) {

      $head2 = "Relatório de Conferêcia SEFIP      ";
      $head3 = "Competência : {$iMesUsu} / {$iAnoUsu}";

      $pdf = new PDF();
      $pdf->Open();
      $pdf->AliasNbPages();
      $pdf->setfillcolor(235);
      $pdf->AddPage("L");

      $iAlt   = 5;
      $iLista = 1;
      $lPrimeiraPagina = true;


      $nBasePrevidencia = 0;
      $nDescPrevidencia = 0;
      $nBase13          = 0;
      $nDesc13          = 0;
      $nFGTS            = 0;
      $iContRegist      = 0;
      $nDescFolha       = 0;

      foreach ( $aListaGerados as $iMatric => $aValores ){

        if ($pdf->gety()>$pdf->h-30 || $lPrimeiraPagina ){
          $pdf->setfont('arial','b',8);
          $pdf->Cell(22,$iAlt,"Matrícula"         ,1,0,"C",1);
          $pdf->Cell(71,$iAlt,"Nome"              ,1,0,"C",1);
          $pdf->Cell(19,$iAlt,"Tipo Contrato"     ,1,0,"C",1);
          $pdf->Cell(18,$iAlt,"Cód. Afasta."      ,1,0,"C",1);
          $pdf->Cell(17,$iAlt,"Data Afasta."      ,1,0,"C",1);
          $pdf->Cell(18,$iAlt,"Cód. Retorno"      ,1,0,"C",1);
          $pdf->Cell(18,$iAlt,"Data Retorno"      ,1,0,"C",1);
          $pdf->Cell(17,$iAlt,"Base Prev."        ,1,0,"C",1);
          $pdf->Cell(16,$iAlt,"Desc. Prev."       ,1,0,"C",1);
          $pdf->Cell(15,$iAlt,"Base 13º"          ,1,0,"C",1);
          $pdf->Cell(15,$iAlt,"Desc. 13º"         ,1,0,"C",1);
          $pdf->Cell(16,$iAlt,"FGTS"              ,1,0,"C",1);
          $pdf->Cell(18,$iAlt,"Desc Folha"        ,1,1,"C",1);
          $pdf->setfont('arial','',8);

          $lPrimeiraPagina = false;
          $iLista = 1;
        }

        if ( !isset($aValores['CodAfastamento']) ) {
          $aValores['CodAfastamento'] = '';
        }
        if ( !isset($aValores['DataAfastamento']) ) {
          $aValores['DataAfastamento'] = '';
        }
        if ( !isset($aValores['CodRetorno']) ) {
          $aValores['CodRetorno'] = '';
        }
        if ( !isset($aValores['DataRetorno']) ) {
          $aValores['DataRetorno'] = '';
        }
        if ( !isset($aValores['BasePrevidencia']) ) {
          $aValores['BasePrevidencia'] = '';
        }
        if ( !isset($aValores['DescPrevidencia']) ) {
          $aValores['DescPrevidencia'] = '';
        }
        if ( !isset($aValores['Base13']) ) {
          $aValores['Base13'] = '';
        }
        if ( !isset($aValores['Desc13']) ) {
          $aValores['Desc13'] = '';
        }
        if ( !isset($aValores['FGTS']) ) {
          $aValores['FGTS'] = '';
        }

        if ( !isset($aValores['DescFolha']) ) {
          $aValores['DescFolha'] = '';
        }

        if ( $iLista == 1 ) {
          $iLista = 0;
        } else {
          $iLista = 1;
        }

        $pdf->Cell(22,$iAlt,$iMatric                                     ,0,0,'C',$iLista);
        $pdf->Cell(71,$iAlt,$aValores['Nome']                            ,0,0,'L',$iLista);
        $pdf->Cell(19,$iAlt,$aValores['TipoContrato']                    ,0,0,'C',$iLista);
        $pdf->Cell(18,$iAlt,$aValores['CodAfastamento']                  ,0,0,'C',$iLista);
        $pdf->Cell(17,$iAlt,$aValores['DataAfastamento']                 ,0,0,'C',$iLista);
        $pdf->Cell(18,$iAlt,$aValores['CodRetorno']                      ,0,0,'C',$iLista);
        $pdf->Cell(18,$iAlt,$aValores['DataRetorno']                     ,0,0,'C',$iLista);
        $pdf->Cell(17,$iAlt,db_formatar($aValores['BasePrevidencia'],'f'),0,0,'R',$iLista);
        $pdf->Cell(16,$iAlt,db_formatar($aValores['DescPrevidencia'],'f'),0,0,'R',$iLista);
        $pdf->Cell(15,$iAlt,db_formatar($aValores['Base13'],'f')         ,0,0,'R',$iLista);
        $pdf->Cell(15,$iAlt,db_formatar($aValores['Desc13'],'f')         ,0,0,'R',$iLista);
        $pdf->Cell(16,$iAlt,db_formatar($aValores['FGTS'],'f')           ,0,0,'R',$iLista);
        $pdf->Cell(18,$iAlt,db_formatar($aValores['DescFolha'],'f')      ,0,1,'C',$iLista);

        $nBasePrevidencia += $aValores['BasePrevidencia'];
        $nDescPrevidencia += $aValores['DescPrevidencia'];
        $nBase13          += $aValores['Base13'];
        $nDesc13          += $aValores['Desc13'];
        $nFGTS            += $aValores['FGTS'];
        $nDescFolha       += $aValores['DescFolha'];
        $iContRegist++;

      }

      $pdf->setfont('arial','b',8);
      $pdf->Cell(5  ,$iAlt,""                                   ,'T',0,'L',0);
      $pdf->Cell(131,$iAlt,"TOTAL DE REGISTROS : {$iContRegist}",'T',0,'L',0);
      $pdf->Cell(36 ,$iAlt,'TOTAIS:'                            ,'T',0,'R',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nBasePrevidencia,'f')   ,'T',0,'R',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nDescPrevidencia,'f')   ,'T',0,'R',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nBase13,'f')            ,'T',0,'R',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nDesc13,'f')            ,'T',0,'C',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nFGTS,'f')              ,'T',0,'R',0);
      $pdf->Cell(18 ,$iAlt,db_formatar($nDescFolha,'f')         ,'T',1,'R',0);
      $pdf->setfont('arial','',8);

      $sArquivoConferencia = "tmp/lista_conferencia_sefip_".date('His').".pdf";
      $pdf->Output($sArquivoConferencia,false,true);

    }


    if ( count($aListaSemPIS) > 0 ) {

      $head2 = "Relatório de Funcionários sem PIS";
      $head3 = "Competência : {$iMesUsu} / {$iAnoUsu}";

      $pdf1 = new PDF();
      $pdf1->Open();
      $pdf1->AliasNbPages();
      $pdf1->setfillcolor(235);
      $pdf1->AddPage();

      $iAlt   = 5;
      $iLista = 1;
      $lPrimeiraPagina   = true;
      $iContRegistSemPIS = 0;

      foreach ( $aListaSemPIS as $iMatric => $aValores ){

        if ($pdf1->gety()>$pdf1->h-30 || $lPrimeiraPagina ){
          $pdf1->setfont('arial','b',8);
          $pdf1->Cell(30 ,$iAlt,"Matrícula"         ,1,0,"C",1);
          $pdf1->Cell(120,$iAlt,"Nome"              ,1,0,"C",1);
          $pdf1->Cell(40 ,$iAlt,"Tipo de Contrato"  ,1,1,"C",1);
          $pdf1->setfont('arial','',8);
          $iLista = 1;
          $lPrimeiraPagina = false;
        }

        if ( $iLista == 1 ) {
          $iLista = 0;
        } else {
          $iLista = 1;
        }

        $pdf1->Cell(30 ,$iAlt,$iMatric                 ,0,0,'C',$iLista);
        $pdf1->Cell(120,$iAlt,$aValores['Nome']        ,0,0,'L',$iLista);
        $pdf1->Cell(40 ,$iAlt,$aValores['TipoContrato'],0,1,'C',$iLista);

        $iContRegistSemPIS++;

      }

      $pdf1->setfont('arial','b',8);
      $pdf1->Cell(5,$iAlt,""                                         ,'T',0,'L',0);
      $pdf1->Cell(0,$iAlt,"TOTAL DE REGISTROS : {$iContRegistSemPIS}",'T',1,'L',0);
      $pdf1->setfont('arial','',8);


      $sArquivoSemPis = "tmp/lista_funcionarios_sem_PIS".date('His').".pdf";
      $pdf1->Output($sArquivoSemPis,false,true);

    }


    db_fim_transacao();
  } catch ( Exception $eException ) {

    $lErro = true;
    $sMsg  = $eException->getMessage();
    db_fim_transacao(true);
  }

} else {

  $iAnoUsu = $oGet->iAnoUsu;
  $iMesUsu = $oGet->iMesUsu;
}

$clrotulo = new rotulocampo;
$clcodmovsefip->rotulo->label();
$clrotulo->label("z01_nome");

$sAtualizaMatriculas = "";
if (isset($oGet->iTipoProcessamento) && $oGet->iTipoProcessamento == 2) {
  $sAtualizaMatriculas = "js_atualizaMatriculas();";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js, strings.js,arrays.js, prototype.js,datagrid.widget.js");
db_app::load("widgets/windowAux.widget.js, widgets/dbmessageBoard.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_alteraCnae();">
  <form name="form1" method="post" action="pes1_gerasefip004.php">
    <?
    db_input('matriculasselecionadas', 10, 1, true, 'hidden', 3);
    ?>
    <table align="center" style="padding-top: 25px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>RECOLHIMENTO</b>
            </legend>
            <table width="100%">
              <tr>
                <td nowrap align="right" title="Código do recolhimento"><b>Código:</b>
                </td>
                <td><?
                $codrec = "115";
                db_input('codrec',10,1,true,'text',3,"")
                ?>
                </td>
                <td nowrap align="right" title="Ano / Mês de competência"><b>Ano / Mês:</b>
                </td>
                <td nowrap><?
                $anousu = $iAnoUsu;
                db_input('r66_anousu',4,$Ir66_anousu,true,'text',3,"onchange='js_controla_anomes(\"a\");'","anousu");
                ?> <b>/</b> <?
                $mesusu = $iMesUsu;
                db_input('r66_mesusu',2,$Ir66_mesusu,true,'text',3,"onchange='js_controla_anomes(\"m\");'","mesusu");
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Índice recolhimento FGTS"><b>Índice FGTS:</b>
                </td>
                <td><?
                $indrecfgts  = 1;
                $aIndRecFGTS = array("0"=>"Nenhum",
                    "1"=>"GFIP no prazo",
                    "2"=>"GFIP em atraso");

                db_select('indrecfgts',$aIndRecFGTS,true,1,"onchange='js_verindices(\"dtrecfgts\",this.value, false);'");
                ?>
                </td>
                <td nowrap align="right" title="Data recolhimento FGTS"><b>Data FGTS:</b>
                </td>
                <td><?
                db_inputdata("dtrecfgts", @$dtrecfgts_dia, @$dtrecfgts_mes, @$dtrecfgts_ano, true, 'text',1);
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Índice recolhimento INSS"><b>Índice INSS:</b>
                </td>
                <td><?
                $indrecinss  = 1;
                $aIndRecINSS = array("0"=>"Não gera GPS",
                    "1"=>"GPS no prazo",
                    "2"=>"GPS em atraso");

                db_select('indrecinss',$aIndRecINSS,true,1,"onchange='js_verindices(\"dtrecinss\",this.value, true);'");
                ?>
                </td>
                <td nowrap align="right" title="Data recolhimento INSS"><b>Data INSS:</b>
                </td>
                <td><?
                db_inputdata("dtrecinss", @$dtrecinss_dia, @$dtrecinss_mes, @$dtrecinss_ano, true, 'text',1);
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Índice recolhimento atraso INSS"><b>Atraso INSS:</b>
                </td>
                <td><?
                db_input('indatrasoinss',10,1,true,'text',1,"","")
                ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <fieldset>
            <legend>
              <b>CONTATO</b>
            </legend>
            <table>
              <tr>
                <td nowrap align="right" title="Nome do contato"><b>Nome:</b>
                </td>
                <td><?
                db_input('z01_nome',40,$Iz01_nome,true,'text',1,"","contato")
                ?>
                </td>
                <td nowrap align="right" title="Fone"><b>Fone:</b>
                </td>
                <td><?
                db_input('fone',10,1,true,'text',1,"","")
                ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <fieldset>
            <legend>
              <b>MAIS DADOS</b>
            </legend>
            <table>
              <tr>
                <td nowrap align="right" title="Alteração de endereço"><b>Alteração de endereço:</b>
                </td>
                <td><?
                $alteraender = "N";
                $arr_alteraender = array("S"=>"Sim","N"=>"Não");
                db_select('alteraender',$arr_alteraender,true,1,"");
                ?>
                </td>
                <td nowrap align="right" title="Alteração de CNAE"><b>Alteração de CNAE:</b>
                </td>
                <td><?
                $alteracnae = "P";
                $arr_alteracnae = array("S"=>"Sim","N"=>"Não","A"=>"Alt. Preponderante","P"=>"Não Alt. Preponderante");
                db_select('alteracnae',$arr_alteracnae,true,1,"");
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Código de terceiros"><b>Código de terceiros:</b>
                </td>
                <td><?
                $codterceiro = "0000";
                db_input('codterceiro',10,1,true,'text',1,"","")
                ?>
                </td>
                <td nowrap align="right" title="Código CNAE fiscal"><b>Código CNAE fiscal:</b>
                </td>
                <td><?
                $cnae = "8411600";
                db_input('cnae',10,1,true,'text',1,"","")
                ?>
                </td>
              </tr>
              <tr>
                <td nowrap align="right" title="Aliquota SAT"><b>Aliquota SAT:</b>
                </td>
                <td><?
                db_input('aliqsat',2,1,true,'text',1,"","")
                ?>
                </td>
                <td nowrap align="right" title="Código GPS"><b>Código GPS:</b>
                </td>
                <td><?
                $codgps = "2402";
                db_input('codgps',10,1,true,'text',1,"","")
                ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td align="center">
          <fieldset>
            <legend>
              <b>TABELAS DE PREVIDÊNCIA</b>
            </legend>
            <table width="100%">
              <?
              db_sel_cfpess(db_anofolha(), db_mesfolha(), "r11_tbprev , r11_mes13 ");

              $iMesAnt = $iMesUsu;

              if($iMesUsu > 12){
                $iMesUsu =  $r11_mes13;
              }

              $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null,null," distinct (cast(r33_codtab as integer) - 2) as r33_codtab,r33_nome","r33_codtab","r33_codtab between 3 and 6 and r33_mesusu=$iMesUsu and r33_anousu=$iAnoUsu and r33_instit = ".db_getsession('DB_instit')));
              $iMesUsu = $iMesAnt;

              for( $i=0, $cont = 1; $i<$clinssirf->numrows; $i++){

                db_fieldsmemory($result_tbprev, $i);

                if(($i % 2) == 0 || $i == 0){
                  echo "<tr>";
                }

                echo " <td nowrap align='center' title='".$r33_nome."' width='10%'>
                <input name='tab_".$r33_codtab."' value='".$r33_codtab."' onchange='{$sAtualizaMatriculas}' type='checkbox' ".(($r33_codtab == $r11_tbprev)?" checked ":"").">
                </td>
                <td nowrap align='left' title='".$r33_nome."' width='40%'>
                <b>".$r33_nome."</b>
                </td>
                ";

                if($cont == 2 || ($i + 1) == $clinssirf->numrows){
                  echo "</tr>";
                  $cont = 0;
                }
                $cont ++;
              }

              db_input('checkboxes',10,1,true,'hidden',1,"","");
              ?>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Compensação</b>
            </legend>
            <table>
              <tr>
                <td><b>Gerar Compensação:</b>
                </td>
                <td><?
                $gerarcompensacao = 2;
                db_select("gerarcompensacao", array(1 => "Sim", 2 => "Não"), true, 1, "onchange='js_liberarCompensacao()'");
                ?>
                </td>
                <td><b>Valor da Compensação:</b>
                </td>
                <td><?
                db_input('valorcompensacao',4,4,true,'text',1,"","")
                ?>
                </td>
              </tr>
              <tr>
                <td><b>Competência Inicial ( Mês / Ano ) :</b>
                </td>
                <td><?
                db_input('mescompeinicial',2,1,true,'text',1,"","");
                ?> / <?
                db_input('anocompeinicial',4,1,true,'text',1,"","");
                ?>
                </td>
                <td><b>Competência Final ( Mês / Ano ) :</b>
                </td>
                <td><?
                db_input('mescompefinal',2,1,true,'text',1,"","");
                ?> / <?
                db_input('anocompefinal',4,1,true,'text',1,"","");
                ?>
                </td>
              </tr>
            </table>

        </td>
      </tr>
      <tr>
        <td align="center">
          <fieldset>
            <legend>
              <b>CNPJ</b>
            </legend>
            <table>
              <tr>
                <td nowrap align="right" title="CNPJ"><b>CNPJ:</b>
                </td>
                <td><?
                $sql = "select distinct z01_numcgm,
                z01_cgccpf||'-'||z01_nome as z01_nome
                from rhlota
                inner join cgm on rhlota.r70_numcgm = cgm.z01_numcgm
                where r70_instit = {$iInstit}";

                $result = db_query($sql);
                db_selectrecord("r70_numcgm", $result, true, @$db_opcao, "", "", "", "0", $sAtualizaMatriculas, "2");
                ?></td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr align="center">
        <td><input name="gerar" type="submit" id="gerar" value="Gerar SEFIP" onblur='js_tabulacaoforms("form1","anousu",true,1,"anousu",true);'
          onclick='return js_verificacampos();'> <input type="button" id="btnVoltar" name="btnVoltar" value="Voltar" onclick="js_voltar();">
        </td>
      </tr>
    </table>
  </form>
</body>
<?
if( isset($oPost->gerar) ){

  if ($lErro) {

    db_msgbox($sMsg);
    echo "<script>$('gerar').show();</script>";
  } else {

    echo "<script>$('gerar').hide();</script>";
    $sNomeArquivos  = "/tmp/SEFIP.RE#Arquivo para envio SEFIP";
    if ( count($aListaGerados) > 0 ) {
      $sNomeArquivos .= "|{$sArquivoConferencia}#Relatório de Conferência";
    }

    if ( count($aListaSemPIS) > 0 ) {
      $sNomeArquivos .= "|{$sArquivoSemPis}#Relatório de Funcionário sem PIS";
    }

    echo "<script>
    var sLista = '{$sNomeArquivos}';
    (window.CurrentWindow || parent.CurrentWindow).corpo.js_montarlista(sLista,'form1');
    </script>";

  }
}

if (isset($oGet->iTipoProcessamento) && $oGet->iTipoProcessamento == 2) {

  echo "<script>";
  echo "  parent.document.formaba.gerasefip.disabled    = false;";
  echo "  parent.document.formaba.selecionados.disabled = false;";
  echo "  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_selecionados.location.href   = 'pes1_gerasefipselecionados001.php';";
  echo "  parent.mo_camada('selecionados');";
  echo "</script>\n";
}
?>
<script>
function js_verificacampos() {

  var retorno = true;
  if (document.form1.anousu.value == "") {

    alert("Informe o ano de competência.");
    document.form1.anousu.focus();
    retorno = false;
  } else if (document.form1.mesusu.value == "") {

    alert("Informe o mês de competência.");
    document.form1.mesusu.focus();
    retorno = false;
  } else if (document.form1.contato.value == "") {

    alert("Informe o nome do contato.");
    document.form1.contato.focus();
    retorno = false;
  } else if (document.form1.fone.value == "") {

    alert("Informe o fone de contato.");
    document.form1.fone.focus();
    retorno = false;
  } else {

    if (document.form1.gerarcompensacao.value == 1) {

      var sAnoCompInicial = document.form1.anocompeinicial.value;
      var sMesCompInicial = document.form1.mescompeinicial.value;
      var sAnoCompFinal   = document.form1.anocompefinal.value;
      var sMesCompFinal   = document.form1.mescompefinal.value;

      if (sAnoCompInicial == '' || sMesCompInicial == '') {

        alert('Competência Inicial da Compensação não informada!');
        retorno = false;
      }

      if (sAnoCompFinal == '' || sMesCompFinal == '') {

        alert('Competência Final da Compensação não informada!');
        retorno = false;
      }

      var sDataCompGeracao = new Date(document.form1.anousu.value+'-'+document.form1.mesusu.value+'-01');
      var sDataCompInicial = new Date(sAnoCompInicial+'-'+sMesCompInicial+'-01');
      var sDataCompFinal   = new Date(sAnoCompFinal+'-'+sMesCompFinal+'-01');

      if (sDataCompInicial > sDataCompFinal) {

        alert('Competência inicial da compensação não pode ser maior que competência da final!');
        retorno = false;
      }

      if (sDataCompInicial > sDataCompGeracao || sDataCompFinal > sDataCompGeracao) {

        alert('Competência da Compensação não pode ser maior que competência da geração!');
        retorno = false;
      }
    }

    var aCheckBoxes = js_pesquisaPrevidenciaSelecionada();
    if (aCheckBoxes == "") {

      alert("Selecione uma tabela de previdência.");
      retorno = false;
    }
  }

  if (retorno) {

    parent.document.formaba.gerasefip.disabled    = true;
    parent.document.formaba.selecionados.disabled = true;
    $('gerar').hide();

    retorno = true;
  }

  return retorno;
}

function js_verindices(campo,valor,indatrasoinss){
  if(valor == 1){
    eval("document.form1."+campo+"_dia.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_mes.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_ano.style.backgroundColor='#DEB887';");
    eval("document.form1."+campo+"_dia.readOnly = true;");
    eval("document.form1."+campo+"_mes.readOnly = true;");
    eval("document.form1."+campo+"_ano.readOnly = true;");
    eval("document.form1.dtjs_"+campo+".disabled = true;");
    if(indatrasoinss){
      document.form1.indatrasoinss.readOnly = true;
      document.form1.indatrasoinss.style.backgroundColor='#DEB887';
    }
    js_tabulacaoforms("form1","anousu",false,1,"anousu",false);
  }else{
    eval("document.form1."+campo+"_dia.style.backgroundColor='';");
    eval("document.form1."+campo+"_mes.style.backgroundColor='';");
    eval("document.form1."+campo+"_ano.style.backgroundColor='';");
    eval("document.form1."+campo+"_dia.readOnly = false;");
    eval("document.form1."+campo+"_mes.readOnly = false;");
    eval("document.form1."+campo+"_ano.readOnly = false;");
    eval("document.form1.dtjs_"+campo+".disabled = false;");
    if(indatrasoinss){
      document.form1.indatrasoinss.readOnly = false;
      document.form1.indatrasoinss.style.backgroundColor='';
    }
    js_tabulacaoforms("form1",campo+"_dia",true,1,campo+"_dia",true);
  }
}
function js_controla_anomes(opcao){
  anodig = new Number(document.form1.anousu.value);
  mesdig = new Number(document.form1.mesusu.value);
  anofol = new Number("<?=db_anofolha()?>");
  mesfol = new Number("<?=db_mesfolha()?>");
  erro = 0;
  if(mesdig > 13){
    alert("Usuário:\n\nMês inválido. Verifique.");
    erro ++;
  }else{
    if((anodig.valueOf() > anofol.valueOf()) || (anodig.valueOf() == anofol.valueOf() && mesdig.valueOf() > mesfol.valueOf() && mesdig != 13)){
      alert("Usuário:\n\nAno/Mês digitado maior que o corrente da folha. Verifique.");
      erro ++;
    }
  }
  if(erro > 0){
    if(opcao == "a"){
      document.form1.anousu.value = "";
      document.form1.anousu.focus();
    }else{
      document.form1.mesusu.value = "";
      document.form1.mesusu.focus();
    }
  }
  document.form1.submit();
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_codmovsefip','func_codmovsefip.php?funcao_js=parent.js_preenchepesquisa|r66_anousu|r66_mesusu|r66_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_codmovsefip.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
  js_verindices("dtrecfgts",1, false);
  js_verindices("dtrecinss",1, true);

function js_liberarCompensacao() {

  var lReadOnly = true;
  var bgcolor   = 'rgb(222, 184, 135)'
  if ($F('gerarcompensacao') == '1') {

    lReadOnly = false;
    bgcolor   = "white";
  }
  $('valorcompensacao').style.backgroundColor = bgcolor;
  $('valorcompensacao').readOnly              = lReadOnly;

  $('mescompeinicial').readOnly              = lReadOnly;
  $('mescompeinicial').style.backgroundColor = bgcolor;
  $('anocompeinicial').style.backgroundColor = bgcolor;
  $('anocompeinicial').readOnly              = lReadOnly;


  $('mescompefinal').readOnly              = lReadOnly;
  $('mescompefinal').style.backgroundColor = bgcolor;
  $('anocompefinal').readOnly              = lReadOnly;
  $('anocompefinal').style.backgroundColor = bgcolor;
}

js_liberarCompensacao();

/**
 * Função executada no ONLOAD da página
 * Altera o valor do campo alteracnae para N caso o MES seja 13
 */
function js_alteraCnae() {

  if ($('mesusu').value == 13) {
    $('alteracnae').value = "N";
  }
}

function js_pesquisaPrevidenciaSelecionada() {

  var aCheckBoxes = "";
  var virgula     = "";
  for (i = 0; i < document.form1.length; i++) {

    if (document.form1.elements[i].type == 'checkbox') {

      if (document.form1.elements[i].checked == true) {

        aCheckBoxes += virgula+document.form1.elements[i].value;
        virgula      = ",";
      }
    }
  }

  $('checkboxes').value = aCheckBoxes;
  return aCheckBoxes;
}

function js_atualizaMatriculas() {
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_selecionados.js_montaGrid();
}

function js_voltar() {
  (window.CurrentWindow || parent.CurrentWindow).corpo.location.href = 'pes1_gerasefip001.php';
}
</script>
</html>