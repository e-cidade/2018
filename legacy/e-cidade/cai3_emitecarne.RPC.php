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

/**
 * @fileoverview Controla Geração de Recibos/Carnes conforme convenio.
 * @version $Revision: 1.114 $
 */
require_once(modification('dbforms/db_funcoes.php'));

require_once(modification('libs/db_conn.php'));
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_sql.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification("std/DBDate.php"));
require_once(modification("std/DBNumber.php"));

require_once(modification("model/recibo.model.php"));

require_once(modification("classes/db_arrecad_classe.php"));
require_once(modification("classes/db_arretipo_classe.php"));
require_once(modification("classes/db_inicialnumpre_classe.php"));
require_once(modification("classes/db_numpref_classe.php"));
require_once(modification("classes/db_processoforo_classe.php"));
require_once(modification("classes/db_processoforopartilha_classe.php"));
require_once(modification("classes/db_processoforopartilhacusta_classe.php"));
require_once(modification("classes/db_recibopaga_classe.php"));
require_once(modification("classes/db_parjuridico_classe.php"));

require_once(modification("ext/php/adodb-time.inc.php"));

use \ECidade\Tributario\Arrecadacao\CobrancaRegistrada\CobrancaRegistrada;
use  ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\Repository;
use  ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Arquivo\IncluiBoleto;
use  ECidade\Tributario\Arrecadacao\CobrancaRegistrada\Webservice\CEF\Manutencao;
use \ECidade\Tributario\Arrecadacao\Custas\Processamento as ProcessamentoCustas;
use ECidade\Tributario\Arrecadacao\Custas\Custas;

db_app::import("exceptions/*");
$oDaoReciboPaga         = new cl_recibopaga();
$oJson                  = new services_json();

/**
 * Alterado pois estava estourando o tamanho do objeto
 */
//$oParam               = $oJson->decode(str_replace("\\", "", $_POST["json"]) );
$oParam                 = $oJson->decode(str_replace("\\", "", $_POST["json"]) );

$oRetorno               = new stdClass();
$oRetorno->status       = 1;
$oRetorno->message      = '';
$oDaoNumpref            = new cl_numpref();
$oDaoArretipo           = new cl_arretipo();
$oDaoParJuridico        = new cl_parjuridico();
$oDataSessao            = new DBDate( date("Y-m-d", db_getsession("DB_datausu")) );

$sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
$rs_cgc = db_query($sql_cgc);

$oConfig = new stdClass();
$oConfig->db21_codcli = pg_result($rs_cgc,0,1);

/**
 * Busca parâmetros do tributario
 * para validar reemissao de recibo
 */
$sSqlParametros         = $oDaoNumpref->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit'));
$rsSqlParametros        = $oDaoNumpref->sql_record($sSqlParametros);

if ( $rsSqlParametros && pg_num_rows($rsSqlParametros) ) {

  $oParametrosTributario = db_utils::fieldsMemory($rsSqlParametros,0);
  if ($oParametrosTributario->k03_reemissaorecibo == 't') {
    $lConfReemissaoRecibo = true;
  } else {
    $lConfReemissaoRecibo = false;
  }
} else {
  $oRetorno->status       = 2;
  $oRetorno->message      = 'Erro ao selecionar parâmetros do tributário.';
}

/**
 * Busca parametros da tabela parjuridico
 */
$sSqlParJuridico   = "select * from parjuridico where v19_anousu = ".db_getsession("DB_anousu");
$rsParJuridico     = $oDaoParJuridico->sql_record($sSqlParJuridico);
if($rsParJuridico && pg_num_rows($rsParJuridico) > 0) {
  $oParJuridico = db_utils::fieldsMemory($rsParJuridico,0);
} else {
	$oRetorno->status       = 2;
  $oRetorno->message      = 'Erro ao selecionar parâmetros do jurídico.';
}

/**
 * Valida data de vencimento
 */
$tsDataOperacao = strtotime(date("Y-m-d", db_getsession("DB_datausu") ));
$DB_DATACALC    = $tsDataOperacao;

if (isset($oParam->oDadosForm->k00_dtoper) ) {
  $dVencimento  = implode("-",array_reverse(explode("/",$oParam->oDadosForm->k00_dtoper)));
} else {
  $dVencimento  = date("Y-m-d", db_getsession("DB_datausu") );
}
$dtOperacao     = date("Y-m-d", db_getsession("DB_datausu") );


if ( isset($oParam->oDadosForm) && ($oParam->oDadosForm->k03_tipo == 5 or $oParam->oDadosForm->k03_tipo == 18 ) and $oConfig->db21_codcli == 19985 ) {

  $db_datausu = date("Y-m-d",db_getsession("DB_datausu"));
  $sUltimoDiaMenos5   = "select ultimo_dia - '5 day'::interval as ultimo_dia_menos_5 from ( select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia from ( select '$db_datausu'::date + '1 month'::interval as proximo_mes ) as x ) as y";
  $rsUltimoDiaMenos5 = db_query($sUltimoDiaMenos5);
  $oUltimoDiaMenos5  = db_utils::fieldsMemory($rsUltimoDiaMenos5,0);
  $db_datausu_dia = substr($db_datausu,8,2);
  $oUltimoDiaMenos5->dia = substr( $oUltimoDiaMenos5->ultimo_dia_menos_5,8,2);
  if ( $db_datausu_dia > $oUltimoDiaMenos5->dia ) {
    $iSomaDia = 2;
  } else {
    $iSomaDia = 1;
  }

  $sUltimoDia  = "select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia from ( select '$db_datausu'::date + '$iSomaDia month'::interval as proximo_mes ) as x";
  $rsUltimoDia = db_query($sUltimoDia);
  $oUltimoDia  = db_utils::fieldsMemory($rsUltimoDia,0);
  $db_datausu  = $oUltimoDia->ultimo_dia;

  $db_datausu_mes = substr($db_datausu,5,2);
  $db_datausu_dia = substr($db_datausu,8,2);
  $db_datausu_ano = substr($db_datausu,0,4);
  $dtValidacao = $db_datausu;
  $DB_DATACALC = adodb_mktime(0,0,0,$db_datausu_mes,$db_datausu_dia,$db_datausu_ano);
}


switch ($oParam->exec) {

  case "validaRecibo":
  /**
   * Retornando mensagem cadastradas no arretipo
   */
  $sSqlArretipo           = $oDaoArretipo->sql_query($oParam->oDadosForm->tipo_debito, "*","");
  $rsSqlArretipo          = $oDaoArretipo->sql_record($sSqlArretipo);
  $oArretipo              = db_utils::fieldsMemory($rsSqlArretipo, 0);

  $oRetorno->iConfirm = 0;
  $aStrBusca     = array();


  /**
   * Valida o campo data de vencimento
   */
  if (empty($oParam->oDadosForm->k00_dtoper)) {

    $oRetorno->status   = 2;
    $oRetorno->message  = "Campo Data Pagamento é de preenchimento obrigatório!";

  }

  $oDadosNumpre  = retornaDebitosSelecionados($oParam);
  if ($lConfReemissaoRecibo){

    /**
     * Separa os dados do Formulário
     */

    foreach ($oDadosNumpre->aDadosChecks as $aDadosFormulario) {

      $aStrBusca[] = "(recibopaga.k00_numpre = {$aDadosFormulario['Numpre']} and recibopaga.k00_numpar = {$aDadosFormulario['Numpar']})";

      $sNumpreHash = str_pad($aDadosFormulario['Numpre'],10,"0",STR_PAD_LEFT);
      $sNumparHash = str_pad($aDadosFormulario['Numpar'], 3,"0",STR_PAD_LEFT);
      $aStrHash[]  = $sNumpreHash.$sNumparHash;
      sort($aStrHash);
    }

    $sWhereValida       = implode(" or ", array_unique($aStrBusca) );

    $sSqlValidaEmissao = "select distinct                                                                                        \n";
    $sSqlValidaEmissao.= "        k00_numnov,                                                                                    \n";
    $sSqlValidaEmissao.= "        k00_dtpaga,                                                                                    \n";
    $sSqlValidaEmissao.= "        ( (select count(distinct cast(r.k00_numpre as varchar) || cast(r.k00_numpar as varchar) )      \n";
    $sSqlValidaEmissao.= "             from recibopaga r                                                                         \n";
    $sSqlValidaEmissao.= "            where r.k00_numnov = x.k00_numnov ) -                                                      \n";
    $sSqlValidaEmissao.= "          (select count(distinct cast(a.k00_numpre as varchar) || cast(a.k00_numpar as varchar) )      \n";
    $sSqlValidaEmissao.= "             from recibopaga r                                                                         \n";
    $sSqlValidaEmissao.= "                  inner join arrecad a  on a.k00_numpre = r.k00_numpre                                 \n";
    $sSqlValidaEmissao.= "                                       and a.k00_numpar = r.k00_numpar                                 \n";
    $sSqlValidaEmissao.= "            where r.k00_numnov = x.k00_numnov )                                                        \n";
    $sSqlValidaEmissao.= "         ) as diferenca_arrecad                                                                        \n";
    $sSqlValidaEmissao.= "    from recibopaga x                                                                                  \n";
    $sSqlValidaEmissao.= "         inner join recibopagaboleto on k138_numnov = k00_numnov                                       \n";
    $sSqlValidaEmissao.= "   where k00_numnov in (select k00_numnov                                                              \n";
    $sSqlValidaEmissao.= "                          from recibopaga                                                              \n";
    $sSqlValidaEmissao.= "                         where ($sWhereValida)                                                         \n";
    $sSqlValidaEmissao.= "                       )                                                                               \n";
    $sSqlValidaEmissao.= "      and not exists (select 1                                                                         \n";
    $sSqlValidaEmissao.= "                        from cancrecibopaga                                                            \n";
    $sSqlValidaEmissao.= "                       where cancrecibopaga.k134_numnov = x.k00_numnov)                                \n";
    $sSqlValidaEmissao.= "      and k00_dtpaga >= '{$dtOperacao}'                                                                \n";
    $sSqlValidaEmissao.= "                                                                                                       \n";
    $sSqlValidaEmissao.= "      and (  select array_to_string( array_accum(distinct lpad(k00_numpre,10,'0')||lpad(k00_numpar,3,'0')), '') \n";
    $sSqlValidaEmissao.= "               from ( select k00_numpre,k00_numpar                                                     \n";
    $sSqlValidaEmissao.= "                        from recibopaga                                                                \n";
    $sSqlValidaEmissao.= "                       where recibopaga.k00_numnov = x.k00_numnov                                      \n";
    $sSqlValidaEmissao.= "                       order by k00_numpre,k00_numpar ) as z ) = '".implode("",$aStrHash)."'           \n";
    $rsSqlValidaEmissao = db_query($sSqlValidaEmissao);

    if (pg_num_rows($rsSqlValidaEmissao) == 0) {
      $oRetorno->message = "Nenhum Recibo encontrado na Tabela Recibopaga";

    } else {

      $aNumnovValidacao   = db_utils::getCollectionByRecord($rsSqlValidaEmissao);

      $oRetorno->iConfirm = 0;
      $lEncontrouValido = false;
      $lEncontrouIgual  = false;
      /**
       * Valida se encontrou recibo valido ou igual ao que esta sendo emitido.
       */
      foreach ($aNumnovValidacao as $oValidaRecibo) {

        if ($oValidaRecibo->diferenca_arrecad == 0 && $oValidaRecibo->k00_dtpaga != $dVencimento) {
          $lEncontrouValido = true;
        }
        if ($oValidaRecibo->diferenca_arrecad == 0 && $oValidaRecibo->k00_dtpaga == $dVencimento) {
          $lEncontrouIgual   = true;
        }
      }

      if ($lEncontrouValido) {

        $oRetorno->iConfirm = 1;
        $oRetorno->message  = "Existem boletos válidos emitidos, Deseja emitir um novo boleto?"; //Com confirmação
      }
      if ($lEncontrouIgual) {
        $oRetorno->iConfirm = 0;
        $oRetorno->message  = "Encoutrou recibo com mesmo NUMPRE, NUMPAR, e DATA VENCIMENTO iguais.";// Sem confirmação";
      }

    }

  } else {
    $oRetorno->iConfirm = 0;
  }
  $oRetorno->aNumpresForm = array_unique($oDadosNumpre->aValidaNumpre);

  if($oArretipo->k03_tipo == 13 && $oParJuridico->v19_partilha == 't') {

    $oRetorno->aNumpresForm = array_unique($oDadosNumpre->aValidaNumpre);

    if( count($oRetorno->aNumpresForm) > 1) {
      $oRetorno->status   = 2;
      $oRetorno->message  = "Parcelamentos com mais de um processo do foro. Favor emitir um recibo por debito";
    }
  }

  break;
  case "geraRecibo_Carne":

    try {

      $aRecibosComCustasEmitidos = array();
      /*
       * Caso atributo $oParam->oDadosForm->geracarne for != "" eh uma emissao de carne
       *   Caso contrario eh uma emissao de recibo
       */
      $lEmissaoCarne  = false;
      $lEmissaoRecibo = false;

      if (isset($oParam->oDadosForm->geracarne) && $oParam->oDadosForm->geracarne != ""){
        $lEmissaoCarne = true;
      }else{
        $lEmissaoRecibo = true;
      }

      /**
       * ValidaNumpre  * Retornando mensagem cadastradas no arretipo
       */
      $sSqlArretipo           = $oDaoArretipo->sql_query($oParam->oDadosForm->tipo_debito, "*","");
      $rsSqlArretipo          = $oDaoArretipo->sql_record($sSqlArretipo);
      $oArretipo              = db_utils::fieldsMemory($rsSqlArretipo, 0);

      $aTipoInicial[0]               = 18;
      $aTipoInicial[1]               = 12;
      $aTipoInicial[2]               = 13;

      $iTipoModeloRecibo             = 2;       //Cadtipomod
      $oRetorno->aSessoesRecibo      = array();
      $oRetorno->aSessoesCarne       = array();
      $oRetorno->recibos_emitidos    = array();

      $lForcaVencimento              = $oParam->oDadosForm->forcarvencimento == "true" ? true : false;

      $oDaoProcessoForo              = new cl_processoforo();
      $oDaoProcessoForoPartilha      = new cl_processoforopartilha();
      $oDaoProcessoForoPartilhaCusta = new cl_processoforopartilhacusta();

      $oRetorno->aSessoes            = array();

      $oDebitosFormulario            = retornaDebitosSelecionados($oParam, $oArretipo->k00_tipoagrup);
      $aChecks                       = $oDebitosFormulario->aDadosChecks;
      $aDadosForm                    = $oDebitosFormulario->aOutrosDados;
      $aIniciais                     = $oDebitosFormulario->aIniciais;
      $aRecibopaga_numnov            = array();
      $sGeraCarne                    = $oDebitosFormulario->sGeraCarne;
      $lCarne                        = empty($sGeraCarne) ? true : false;

        $aNumpresFormulario = $oDebitosFormulario->aValidaNumpre;

        /* Modelos de impressao padrao da CGF */
        $iTipoModeloRecibo = 2;
        $iTipoModeloCarne = 1;

        if ($lConfReemissaoRecibo) {

            $aNumpresOrigem = array();

        }

        /**
         * Validacao se vai existir emissao de custas para o recibo
         */
        $oProcessamentoCustas = new ProcessamentoCustas($oDebitosFormulario, $oArretipo->k03_tipo);
        $lReciboTemCustas = $oProcessamentoCustas->validaUsoDeCustas();

        if ($lReciboTemCustas) {
            $iTipoModeloRecibo = Custas::TIPO_MODELO_RECIBO;
            $iTipoModeloCarne  = Custas::TIPO_MODELO_CARNE;
        }

        $oRetorno->iTipoModeloRecibo = $iTipoModeloRecibo;
      $oRetorno->iTipoModeloCarne  = $iTipoModeloCarne;
      /*********************************/

      $oRetorno->iMaximoParcelasGeral = $oDebitosFormulario->iMaxParc;
      $oRetorno->iMinimoParcelasGeral = $oDebitosFormulario->iMinParc;

      if ($oParJuridico->v19_partilha == "t" && $lEmissaoCarne && $oArretipo->k03_tipo == 18 ) {
        $iTipoModeloReciboQuery = "{$iTipoModeloCarne}, {$iTipoModeloRecibo}";
      } else if ($lEmissaoRecibo) {
        $iTipoModeloReciboQuery = "{$iTipoModeloRecibo}";
      } else if ($lEmissaoCarne) {
        $iTipoModeloReciboQuery = "{$iTipoModeloCarne}";
      }else {
        $oRetorno->iStatus   = 2;
        $oRetorno->sMensagem = "Tipo de regra de emissão não encontrado";
      }

      $sDataHoje        = date("Y-m-d", db_getsession("DB_datausu") );
      $iInstit          = db_getsession("DB_instit");

      $sSqlRegraEmissao  = "select *                                                                                                                      \n ";
      $sSqlRegraEmissao .= "  from (select min(k48_sequencial) as k48_sequencial,                                                                         \n ";
      $sSqlRegraEmissao .= "               k49_tipo,                                                                                                      \n ";
      $sSqlRegraEmissao .= "               k36_ip,                                                                                                        \n ";
      $sSqlRegraEmissao .= "               k48_parcini,                                                                                                   \n ";
      $sSqlRegraEmissao .= "               k48_parcfim,                                                                                                   \n ";
      $sSqlRegraEmissao .= "               k48_cadconvenio, k48_cadtipomod,                                                                               \n ";
      $sSqlRegraEmissao .= "               ar11_cadtipoconvenio, k03_tipo                                                                                 \n ";
      $sSqlRegraEmissao .= "          from modcarnepadrao                                                                                                 \n ";
      $sSqlRegraEmissao .= "               left  join modcarnepadraotipo on modcarnepadraotipo.k49_modcarnepadrao = modcarnepadrao.k48_sequencial         \n ";
      $sSqlRegraEmissao .= "               left  join modcarneexcessao   on modcarneexcessao.k36_modcarnepadrao   = modcarnepadrao.k48_sequencial         \n ";
      $sSqlRegraEmissao .= "               inner join cadconvenio        on cadconvenio.ar11_sequencial           = modcarnepadrao.k48_cadconvenio        \n ";
      $sSqlRegraEmissao .= "               left  join arretipo           on modcarnepadraotipo.k49_tipo           = arretipo.k00_tipo                     \n ";
      $sSqlRegraEmissao .= "         where '$sDataHoje' between  k48_dataini and k48_datafim                                                              \n ";
      $sSqlRegraEmissao .= "           and k48_instit     = {$iInstit}                                                                                    \n ";
      $sSqlRegraEmissao .= "           and ( case                                                                                                         \n ";
      $sSqlRegraEmissao .= "                   when modcarnepadraotipo.k49_modcarnepadrao is not null then                                                \n ";
      $sSqlRegraEmissao .= "                     modcarnepadraotipo.k49_tipo = {$oParam->oDadosForm->tipo_debito}                                         \n ";
      $sSqlRegraEmissao .= "                   else true                                                                                                  \n ";
      $sSqlRegraEmissao .= "                 end )                                                                                                        \n ";
      $sSqlRegraEmissao .= "           and ( case                                                                                                         \n ";
      $sSqlRegraEmissao .= "                   when modcarneexcessao.k36_modcarnepadrao is not null then                                                  \n ";
      $sSqlRegraEmissao .= "                     modcarneexcessao.k36_ip = '".db_getsession('DB_ip')."'                                                   \n ";
      $sSqlRegraEmissao .= "                   else true                                                                                                  \n ";
      $sSqlRegraEmissao .= "                 end )                                                                                                        \n ";
      $sSqlRegraEmissao .= "           and (                                                                                                              \n ";
      $sSqlRegraEmissao .= "                {$oRetorno->iMaximoParcelasGeral} between k48_parcini and k48_parcfim                                         \n ";
      $sSqlRegraEmissao .= "                 or                                                                                                           \n ";
      $sSqlRegraEmissao .= "                {$oRetorno->iMinimoParcelasGeral} between k48_parcini and k48_parcfim                                         \n ";
      $sSqlRegraEmissao .= "               )                                                                                                              \n ";
      $sSqlRegraEmissao .= "           and k48_cadtipomod in ({$iTipoModeloReciboQuery})                                                                    \n ";
      $sSqlRegraEmissao .= "         group by k49_tipo, k36_ip, k48_parcini, k48_parcfim, k48_cadconvenio, ar11_cadtipoconvenio, k03_tipo, k48_cadtipomod \n ";
      $sSqlRegraEmissao .= "       ) as x                                                                    \n ";
      $rsSqlRegraEmissao = db_query($sSqlRegraEmissao);
      $iRowsRegraEmissao = pg_numrows($rsSqlRegraEmissao);

      /**
       * Valida se existe alguma regra de emissao cadastrada no sistema
       */
      if ($iRowsRegraEmissao > 0) {

        $aRegrasEmissao           = db_utils::getCollectionByRecord($rsSqlRegraEmissao);
        $aRegrasEmissaoEspecifica = array();
        $aRegrasEmissaoGeral      = array();

        /**
         * Separa as regras de emissao se são regras gerais e regras especificas para tipo de débito
         */
        foreach ($aRegrasEmissao as $iIndiceRegra => $oRegraEmissao) {
          if ($oRegraEmissao->k49_tipo != "" || $oRegraEmissao->k36_ip != "") {
            $aRegrasEmissaoEspecifica[] = $oRegraEmissao;
          } else {
            $aRegrasEmissaoGeral[] = $oRegraEmissao;
          }
        }

        if (count($aRegrasEmissaoEspecifica) > 0 ) {
          $aRegrasEmissao = $aRegrasEmissaoEspecifica;
        } else {
            $aRegrasEmissao = $aRegrasEmissaoGeral;
        }
          /**
         * Percorre as regras selecionadas
         */
        $aDadosCompletos = array();

        foreach ($aRegrasEmissao as $iIndiceRegra => $oRegraEmissao) {

          /**
           * valida se tipo de convenio é refente cobrança
           */
          if ($oRegraEmissao->ar11_cadtipoconvenio == 7) {
            $lCobrancaRegistrada = true;
          } else {
            $lCobrancaRegistrada = false;
          }

          /**
           * Instancia novo recibo para ser gerado ou não
           */
          if ($lCobrancaRegistrada || $sGeraCarne == "") {
            $oRecibo = new recibo(2, null, 1);
          }
          $aRecibos[$oRegraEmissao->k48_sequencial] = "";


          /**
           * Cria array com os debitos selecionados para serem comparados posteriormente
           */
          foreach ($aChecks as $iInd => $aVal) {

            if ( ($aVal["Numpar"] >= $oRegraEmissao->k48_parcini) && ($aVal["Numpar"] <= $oRegraEmissao->k48_parcfim) ) {

              $aRecibos[$oRegraEmissao->k48_sequencial][]         =  "(k00_numpre in({$aVal['Numpre']}) and k00_numpar = {$aVal['Numpar']})";
              $aCompara[$oRegraEmissao->k48_sequencial][]         = $aVal['Numpre'].str_pad($aVal['Numpar'], 3, 0, STR_PAD_LEFT);

              $aNumparCompara[$oRegraEmissao->k48_sequencial][]   = $aVal['Numpar'];
              $aNumpreCompara[$oRegraEmissao->k48_sequencial][]   = $aVal['Numpre'];
              $aNumpres_emissao[$oRegraEmissao->k48_sequencial][] = array($aVal['Numpre'], $aVal['Numpar']);

            }
          }

          $aNumpresRecibo  = array();
          $aParcelasRecibo = array();

          /**
           * Percorre os debitos selecionados
           */

          foreach ($aChecks as $iIndice => $aValores) {

            $aNumpresRecibo[]   = $aValores["Numpre"];
          	$aParcelasRecibo[]  = $aValores["Numpar"];
            /**
             * Valida se as parcelas da regra de emissao conferem com as parcelas do numpre
             */
            if ( ($aValores["Numpar"] >= $oRegraEmissao->k48_parcini) && ($aValores["Numpar"] <= $oRegraEmissao->k48_parcfim) ) {
              /**
               * Se for emissao de recibo, adiciona numpre ao recibo caso contrário apenas seta parâmetro para emissao de carne
               */
              if ( ( $lCobrancaRegistrada || empty($sGeraCarne)) &&  $oRegraEmissao->k48_cadtipomod == $iTipoModeloRecibo ) {
                /**
                 * Valida o processamento do desconto por parcela
                 */
                if ($oParam->oDadosForm->processarDescontoRecibo == 'true') {

                  $iTotalRegistros = $oDebitosFormulario->oReciboDesconto->iTotalRegistros;
                  $nValorDesconto  = reciboDesconto(
                                                    $aValores["Numpre"],
                                                    $aValores["Numpar"],
                                                    $oDebitosFormulario->oReciboDesconto->iTipoDebito,
                                                    $oDebitosFormulario->oReciboDesconto->iTipoDebito,
                                                    $oDebitosFormulario->oReciboDesconto->sWhereLoteador,
                                                    $oDebitosFormulario->oReciboDesconto->iTotalSelecionados,
                                                    $iTotalRegistros,
                                                    @$oParam->oDadosForm->ver_matric,
                                                    @$oParam->oDadosForm->ver_inscr
                                                   );
                } else {
                  $nValorDesconto  = 0;
                }
                $oRetorno->nValorDesconto = $nValorDesconto;
               /**
                * Adiciona numpre e numpar ao recibo
                */
                $oRecibo->setDescontoReciboWeb($aValores["Numpre"], $aValores["Numpar"], $nValorDesconto);
                $oRecibo->addNumpre($aValores["Numpre"], $aValores["Numpar"]);
                /**
                 * Se o parametro estiver habilitado
                 * lista os recibos válidos emtidos
                 */
                if ($lConfReemissaoRecibo) {

                  $sSqlRecibosEmitidos = " select distinct k00_numnov                                                   \n";
                  $sSqlRecibosEmitidos.= "   from recibopaga                                                            \n";
                  $sSqlRecibosEmitidos.= "  where k00_dtpaga >= '{$dtOperacao}'                                         \n";
                  $sSqlRecibosEmitidos.= "    and k00_numpre =  {$aValores["Numpre"]}                                   \n";
                  $sSqlRecibosEmitidos.= "    and k00_numpar =  {$aValores["Numpar"]}                                   \n";
                  $sSqlRecibosEmitidos.= "    and not exists (select 1                                                  \n";
                  $sSqlRecibosEmitidos.= "                      from cancrecibopaga                                     \n";
                  $sSqlRecibosEmitidos.= "                     where cancrecibopaga.k134_numnov = recibopaga.k00_numnov)\n";

                  $rsSqlRecibosEmitidos   = $oDaoReciboPaga->sql_record($sSqlRecibosEmitidos);
                  $aRecibosEmitidos       = db_utils::getCollectionByRecord($rsSqlRecibosEmitidos);
                  foreach ($aRecibosEmitidos as $oReciboEmitido) {
                    $aRecibopaga_numnov[] = $oReciboEmitido->k00_numnov;
                  }
                }
              } else {
                $aDadosCarne[$iIndiceRegra]["geracarne"]   = $sGeraCarne;
              }

              /**
               * Cria array de dados que serão gravados na sessao
               * Tambem cria um array das parcelas para serem utilizadas valores maximos e minimos da regra de emissao
               */
              $aDadosCarne[$iIndiceRegra]["numpres_emissao"][] = array($aValores["Numpre"], $aValores["Numpar"]);
              $aDadosCarne[$iIndiceRegra]["convenio"]          = $oRegraEmissao->ar11_cadtipoconvenio;
              $aDadosCarne[$iIndiceRegra][$iIndice]            = $aValores["valor"];
              $aParcelasSeparadas[$iIndiceRegra][]             = $aValores["Numpar"];

            }
          }


          /**
           * Valida os se existem recibos emitidos para o numpre e numpar selecionados
           * depois cria array de numpres e parcelas por numnov
           */
          $sSqlNumNov      = " select distinct array_to_string(array_accum(distinct k00_numnov), ',') as k00_numnov   ";
          $sSqlNumNov     .= "   from recibopaga                                                                     ";
          $sSqlNumNov     .= "  where k00_dtpaga = '".$dVencimento."'                                  ";
          $sSqlNumNov     .= "    and (".implode(" or ", $aRecibos[$oRegraEmissao->k48_sequencial]).")                   ";
          $sSqlNumNov     .= "    and not exists (select 1 from cancrecibopaga where cancrecibopaga.k134_numnov = recibopaga.k00_numnov) ";
          $rsSqlNumNov     = db_query($sSqlNumNov);
          $sNumNov         = db_utils::fieldsMemory($rsSqlNumNov, 0)->k00_numnov;

          $iDiferenca      = array();
          foreach(explode(",", $sNumNov) as $sNumNovEmitido) {
            if ($sNumNovEmitido != ""  && $lConfReemissaoRecibo) {
              $aRecibopaga_numnov[] = $sNumNovEmitido;
            }
          }
          if ($sNumNov != "") {

            $sSqlNumpreNumpar  = " select k00_numnov, array_to_string(array_accum(distinct (k00_numpre||lpad(k00_numpar, 3, 0) )), '|') as numpre_numpar  ";
            $sSqlNumpreNumpar .= "   from recibopaga                                                                                                 ";
            $sSqlNumpreNumpar .= "  where k00_numnov in ({$sNumNov})                                                                                 ";
            $sSqlNumpreNumpar .= "    and not exists (select 1 from cancrecibopaga where cancrecibopaga.k134_numnov = recibopaga.k00_numnov)         ";
            $sSqlNumpreNumpar .= "  group by k00_numnov order by k00_numnov;                                                                         ";
            $rsSqlNumpreNumpar = db_query($sSqlNumpreNumpar);
            $aNumpreNumpar     = db_utils::getCollectionByRecord($rsSqlNumpreNumpar);

            foreach ($aNumpreNumpar as $oNumpreNumpar) {
              /**
               * Compara o array de debitos e parcelas selecionadas e debitos e parcelas emitidas
               * Caso exista caso para comparação apenas faz reemissao do recibo
               * Caso algum numpre ou numpar esteja faltanto ou sobrando emite um novo recibo.
               */
              $aComparaBanco     = explode("|", $oNumpreNumpar->numpre_numpar);
              $iDiferenca[]      = count(array_diff($aCompara[$oRegraEmissao->k48_sequencial], $aComparaBanco) ) +
                                   count(array_diff($aComparaBanco, $aCompara[$oRegraEmissao->k48_sequencial]) );

            }
          }
          if ( (!in_array(0, $iDiferenca) && ($lCobrancaRegistrada || $sGeraCarne == "") && $oParam->lNovoRecibo) || ($sGeraCarne == "" && !$lConfReemissaoRecibo ) ) {

            /**
             * Valida se foram vinculados debitos ao recibo criado,
             * se verdadeiro tenta gerar o recibo
             */

           if (count($oRecibo->getDebitosRecibo() ) > 0) {
            try {

	      db_inicio_transacao();

              $dDataAtual = $dVencimento;

              /**
               * Valida vencimentos do recibo
               */
              if (!$lForcaVencimento) {

              	/**
              	 *
              	 * Caso a data do sistema foi maior que as datas de vencimento, significa que parcelas estão vencidas e
              	 * a data de vencimento será a data do sistema. Caso a data do sistema for menor que a data de vencimento
              	 * a data de vencimento será a menor dentre as das parcelas selecionadas
              	 */

              	$sSqlVenc  = "select case                                                   ";
              	$sSqlVenc .= "         when min(k00_dtvenc) <= '{$sDataHoje}'::date         ";
              	$sSqlVenc .= "           then '{$sDataHoje}'::date                          ";
              	$sSqlVenc .= "         else min(k00_dtvenc)                                 ";
              	$sSqlVenc .= "       end as k00_dtvenc                                      ";
              	$sSqlVenc .= "from arrecad                                                  ";
              	$sSqlVenc .= "where k00_numpre in (".implode(", ", $aNumpresRecibo).")      ";
              	$sSqlVenc .= "  and k00_numpar in (".implode(", ", $aParcelasRecibo).")     ";

                $rsVencimento = db_query($sSqlVenc);
                $dtDataVenc   = db_utils::fieldsMemory($rsVencimento, 0)->k00_dtvenc;

                if ( db_strtotime($dtDataVenc) > db_strtotime($dVencimento) || $dVencimento == "" ) {
                  $dVencimento = $dtDataVenc;
                }

                if ( ( $oParam->oDadosForm->k03_tipo == 5 or $oParam->oDadosForm->k03_tipo == 18 ) and $oConfig->db21_codcli == 19985 ) {

                  $db_datausu = date("Y-m-d",db_getsession("DB_datausu"));

                  $sUltimoDiaMenos5   = "select ultimo_dia - '5 day'::interval as ultimo_dia_menos_5 ";
                  $sUltimoDiaMenos5  .= "  from ( select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia ";
                  $sUltimoDiaMenos5  .= "           from ( select '$db_datausu'::date + '1 month'::interval as proximo_mes ) as x ) as y ";
                  $rsUltimoDiaMenos5 = db_query($sUltimoDiaMenos5);
                  $oUltimoDiaMenos5  = db_utils::fieldsMemory($rsUltimoDiaMenos5,0);

                  $db_datausu_dia = substr($db_datausu,8,2);
                  $oUltimoDiaMenos5->dia = substr( $oUltimoDiaMenos5->ultimo_dia_menos_5,8,2);

                  if ( $db_datausu_dia > $oUltimoDiaMenos5->dia ) {
                    $iSomaDia = 2;
                  } else {
                    $iSomaDia = 1;
                  }

                  $sUltimoDia   = "select ( substr(proximo_mes::text,1,7) || '-01'::text)::date - '1 day'::interval as ultimo_dia ";
                  $sUltimoDia  .= "  from ( select '$db_datausu'::date + '$iSomaDia month'::interval as proximo_mes ) as x";
                  $rsUltimoDia = db_query($sUltimoDia);
                  $oUltimoDia  = db_utils::fieldsMemory($rsUltimoDia,0);
                  $db_datausu  = $oUltimoDia->ultimo_dia;

                  $db_datausu_mes = substr($db_datausu,5,2);
                  $db_datausu_dia = substr($db_datausu,8,2);
                  $db_datausu_ano = substr($db_datausu,0,4);
                  $dVencimento = $db_datausu;
                }
              }

              if (db_strtotime($dVencimento) > db_strtotime(db_getsession('DB_anousu')."-12-31")) {
                $dVencimento = db_getsession('DB_anousu')."-12-31";
              }

              if ( !empty($oRegraEmissao->ar13_sequencial) ) {
                 $iCodigoConvenioCobranca = $oRegraEmissao->ar13_sequencial;
              } else {
                 $iCodigoConvenioCobranca = 0;
              }

              $lConvenioCobrancaValido = CobrancaRegistrada::validaConvenioCobranca($oRegraEmissao->k48_cadconvenio);

              $oRecibo->setNumBco              ($iCodigoConvenioCobranca);
              $oRecibo->setDataRecibo          ($dtOperacao);

              $oRecibo->setDataVencimentoRecibo($dVencimento);
              $oRecibo->setExercicioRecibo     (substr($dVencimento, 0, 4) );

                /*  NOTE: processamento de custas */
                $oProcessamentoCustas->setRecibo($oRecibo);
                $oProcessamentoCustas->setRegraEmissao($oRegraEmissao);
                $lProcessamentoCustas = $oProcessamentoCustas->processar();
                if ($lProcessamentoCustas) {
                    $oRecibo = $oProcessamentoCustas->getRecibo();
                } else {
                    $oRetorno->iTipoModeloRecibo = 2;
                    $oRetorno->iTipoModeloCarne  = 1;
                }

                $oRecibo->emiteRecibo($lConvenioCobrancaValido);
                if ($lConvenioCobrancaValido && !CobrancaRegistrada::utilizaIntegracaoWebService($oRegraEmissao->k48_cadconvenio)) {
                  CobrancaRegistrada::adicionarRecibo($oRecibo, $oRegraEmissao->k48_cadconvenio);
                }

                $k03_numnov           = $oRecibo->getNumpreRecibo();
                $aRecibopaga_numnov[] = $k03_numnov;

                /**
                 * Atualiza numnov das partilhas de custas caso tenha sido processada uma custa
                 */
                if ($lProcessamentoCustas) {
                    $oProcessamentoCustas->atualizarCodigoDoReciboNasCustas($k03_numnov);
                }

                if (in_array($oRegraEmissao->k03_tipo, $aTipoInicial) && $oRegraEmissao->ar11_cadtipoconvenio == 7) {

                /**
                 * Valida se existem processos do foro e se não existem custas vinculadas a ele.
                 */

                $nValorTotalCustas = $oProcessamentoCustas->getValorTotalCustas();

                /**
                 *  Geração do Convenio de Cobrança
                 */

                db_app::import('convenio');
                $nValorRecibo           = $oRecibo->getTotalRecibo() + $nValorTotalCustas;
                $sValorCodigoBarras     = str_pad( number_format( $nValorRecibo, 2, "", "" ), 11, "0", STR_PAD_LEFT);

                $oConvenio              = new convenio($oRegraEmissao->k48_cadconvenio,
                                                       $oRecibo->getNumpreRecibo(),
                                                       0,
                                                       $nValorRecibo,
                                                       $sValorCodigoBarras,
                                                       $oRecibo->getDataRecibo(),
                                                       $oArretipo->k00_tercdigrecnormal);


              }//Validação regra de emissao com as parcelas do numpre e numpar
              db_fim_transacao(false);
            } catch ( Exception $eException ) {

              db_fim_transacao(true);
              $oRetorno->status  = 2;
              $oRetorno->message = $eException->getMessage();
            }
           }
          }
          /**
           * Mescla os dados do array com os dados especificos e os com dados que devem ficar em
           * todos os arrays
           */
          $aDadosCompletos[$iIndiceRegra]             = array_merge($aDadosCarne[$iIndiceRegra], $aDadosForm );
          $aDadosCompletos[$iIndiceRegra]['iParcIni'] = min($aParcelasSeparadas[$iIndiceRegra]);
          $aDadosCompletos[$iIndiceRegra]['iParcFim'] = max($aParcelasSeparadas[$iIndiceRegra]);

          /**
           * Define o nome das sessoes
           */
          if (($lCobrancaRegistrada || $sGeraCarne == "") && $oRegraEmissao->k48_cadtipomod == $iTipoModeloRecibo) {

            $aDadosCompletos[$iIndiceRegra]["iModeloRecibo"] = $iTipoModeloRecibo;
            db_putsession("RequestRecibo".$iIndiceRegra, $aDadosCompletos[$iIndiceRegra]);
            $oRetorno->aSessoesRecibo[] = "RequestRecibo".$iIndiceRegra;

          } elseif ( ( !$lCobrancaRegistrada && $sGeraCarne != "") && $oRegraEmissao->k48_cadtipomod == $iTipoModeloCarne && $oParam->lNovoRecibo ) {

            db_putsession("RequestCarne".$iIndiceRegra, $aDadosCompletos[$iIndiceRegra]);
            $oRetorno->aSessoesCarne[] = "RequestCarne".$iIndiceRegra;

          }
        } //FOREACH QUE PERCORRE REGRAS DE EMISSAO

      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = 'Erro: Nenhuma regra cadastrada para este tipo de débito \nVerifique Parâmetros.';
      }
      $oRetorno->recibos_emitidos  = array_unique($aRecibopaga_numnov);

    } catch (Exception $e) {
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($e->getMessage());
    }
    break;

  case "getDadosRecibo":
    /**
     * Percorre os Dados da Sessão indicada no POST
     */
    $aSqlUnion  = array();
    foreach ($oParam->aSessoesRecibo as $sSessao) {
      /**
       * Percorre os numpres e numpar gravados na sessão
       */
      $oRetorno->sSessao = $sSessao;

      $oDados = db_utils::postMemory($_SESSION[$sSessao]);
      $sWhere = array();
      foreach($oDados->numpres_emissao as $aNumpreRecibo) {
        $sWhere[] = " (k00_numpre = {$aNumpreRecibo[0]} and k00_numpar = {$aNumpreRecibo[1]}) ";
      }


      if ( isset($sWhere) && count($sWhere) > 0 ) {
      //  $sWhere = array_unique($sWhere);
        $dtHoje              = date("Y-m-d", db_getsession("DB_datausu") );

        $sSqlRecibosValidos  ="  select (select array_to_string(array_accum(distinct k00_numpre), ',')             \n";
        $sSqlRecibosValidos .="            from recibopaga x                                                       \n";
        $sSqlRecibosValidos .="           where x.k00_numnov = recibopaga.k00_numnov) as k00_numpre,               \n";
        $sSqlRecibosValidos .="         (select array_to_string(array_accum(distinct k00_numpar), ',')             \n";
        $sSqlRecibosValidos .="            from recibopaga x                                                       \n";
        $sSqlRecibosValidos .="           where x.k00_numnov = recibopaga.k00_numnov) as k00_numpar,               \n";
        $sSqlRecibosValidos .="         (select sum(k00_valor)                                                     \n";
        $sSqlRecibosValidos .="            from recibopaga x                                                       \n";
        $sSqlRecibosValidos .="           where x.k00_numnov = recibopaga.k00_numnov) as k00_valor,                \n";
        $sSqlRecibosValidos .="         k00_dtoper,                                                                \n";
        $sSqlRecibosValidos .="         k00_dtpaga,                                                                \n";
        $sSqlRecibosValidos .="         k00_numnov,                                                                \n";
        $sSqlRecibosValidos .="         '{$dtHoje}'  as data_hoje,                                                 \n";
        $sSqlRecibosValidos .="         '{$sSessao}'    as sessao,                                                 \n";
        $sSqlRecibosValidos .="         k134_sequencial as cancelado,                                              \n";
        $sSqlRecibosValidos .="         k134_motivo     as cancelado_motivo,                                       \n";
        $sSqlRecibosValidos .="         ( (select count(distinct cast(r.k00_numpre as varchar) || cast(r.k00_numpar as varchar) )                       \n";
        $sSqlRecibosValidos .="              from recibopaga r                                                     \n";
        $sSqlRecibosValidos .="             where r.k00_numnov = recibopaga.k00_numnov ) -                         \n";
        $sSqlRecibosValidos .="           (select count(distinct cast(a.k00_numpre as varchar) || cast(a.k00_numpar as varchar) )                       \n";
        $sSqlRecibosValidos .="              from recibopaga r                                                     \n";
        $sSqlRecibosValidos .="                   inner join arrecad a on a.k00_numpre = r.k00_numpre              \n";
        $sSqlRecibosValidos .="                                       and a.k00_numpar = r.k00_numpar              \n";
        $sSqlRecibosValidos .="             where r.k00_numnov = recibopaga.k00_numnov )                           \n";
        $sSqlRecibosValidos .="         ) as diferenca_arrecad                                                     \n";
        $sSqlRecibosValidos .="    from recibopaga                                                                 \n";
        $sSqlRecibosValidos .="    left join cancrecibopaga on cancrecibopaga.k134_numnov = recibopaga.k00_numnov  \n";
        $sSqlRecibosValidos .="   where (".implode(" or ", $sWhere).")                                             \n";
        $sSqlRecibosValidos .="group by k00_dtoper,                                                                \n";
        $sSqlRecibosValidos .="         k00_dtpaga,                                                                \n";
        $sSqlRecibosValidos .="         k00_numnov,                                                                \n";
        $sSqlRecibosValidos .="         k134_sequencial,                                                           \n";
        $sSqlRecibosValidos .="         k134_motivo                                                                \n";
        $aSqlUnion[] = $sSqlRecibosValidos;
      }
    }
    if (count($aSqlUnion) > 0) {

      $sSqlRecibosValidos = " select * from (".implode("\n union \n", $aSqlUnion).") as x order by k00_numnov desc";
      $rsSqlRecibosValidos = $oDaoReciboPaga->sql_record($sSqlRecibosValidos);

      if ($oDaoReciboPaga->numrows > 0) {

        $aRecibosValidos     = db_utils::getCollectionByRecord($rsSqlRecibosValidos);
        foreach($aRecibosValidos as $oRecibos) {
          $oRetorno->aRecibos[]  = $oRecibos;

        }

      } else {
        $oRetorno->status  = 2;
          $oRetorno->message = $oDaoReciboPaga->erro_msg;
      }
    }
  break;

  case "getDadosCarne":

    /**
     * Percorre os Dados da Sessão indicada no POST
     */
    foreach ($oParam->aSessoesCarne as $sSessao) {
      /**
       * Percorre os numpres e numpar gravados na sessão
       */
      $oRetorno->sSessao = $sSessao;

      $oDados = db_utils::postMemory($_SESSION[$sSessao]);

      foreach($oDados->numpres_emissao as $aNumpreRecibo) {

        $sWhere[] = " (k00_numpre = {$aNumpreRecibo[0]} and k00_numpar = {$aNumpreRecibo[1]}) ";

      }
    }

    if (isset($sWhere) && count($sWhere) > 0) {

     $oDaoArrecad          = new cl_arrecad();

     $sSqlCarnes  = " select array_to_string(array_accum(distinct k00_numpre), ', ') as k00_numpre,                   ";
     $sSqlCarnes .= "       array_to_string(array_accum(distinct k00_numpar), ', ') as k00_numpar,                    ";
     $sSqlCarnes .= "       k00_numcgm, {$oParam->dtVencimento} as k00_dtpaga,                                        ";
     $sSqlCarnes .= "       min(k00_dtoper) as k00_dtoper,                                                            ";
     $sSqlCarnes .= "       sum(vlr_total)  as k00_valor                                                              ";
     $sSqlCarnes .= "  from (                                                                                         ";
     $sSqlCarnes .= "       select distinct                                                                           ";
     $sSqlCarnes .= "              *,                                                                                 ";
     $sSqlCarnes .= "              substr( fc_calcula, 2 , 13)::float8  as vlr_historico,                             ";
     $sSqlCarnes .= "              substr( fc_calcula, 15, 13)::float8  as vlr_corrigido,                             ";
     $sSqlCarnes .= "              substr( fc_calcula, 28, 13)::float8  as vlr_juros,                                 ";
     $sSqlCarnes .= "              substr( fc_calcula, 41, 13)::float8  as vlr_multa,                                 ";
     $sSqlCarnes .= "              substr( fc_calcula, 54, 13)::float8  as vlr_desconto,                              ";
     $sSqlCarnes .= "              (substr(fc_calcula, 15, 13)::float8+                                               ";
     $sSqlCarnes .= "              substr( fc_calcula, 28, 13)::float8+                                               ";
     $sSqlCarnes .= "              substr( fc_calcula, 41, 13)::float8-                                               ";
     $sSqlCarnes .= "              substr( fc_calcula, 54, 13)::float8) as vlr_total                                  ";
     $sSqlCarnes .= "         from (                                                                                  ";
     $sSqlCarnes .= "               select *,                                                                         ";
     $sSqlCarnes .= "                      fc_calcula(k00_numpre,                                                     ";
     $sSqlCarnes .= "                                 k00_numpar,                                                     ";
     $sSqlCarnes .= "                                 k00_receit,                                                     ";
     $sSqlCarnes .= "                                 '".date("Y-m-d", db_getsession('DB_datausu') )."',              ";
     $sSqlCarnes .= "                                 '$oParam->dtVencimento',                                        ";
     $sSqlCarnes .= "                                 ".substr($oParam->dtVencimento, 0, 4)."                         ";
     $sSqlCarnes .= "                                )                                                                ";
     $sSqlCarnes .= "                 from arrecad                                                                    ";
     $sSqlCarnes .= "                where ".implode(" or ", $sWhere)."                                               ";
     $sSqlCarnes .= "              ) as x                                                                             ";
     $sSqlCarnes .= "       ) as y                                                                                    ";
     $sSqlCarnes .= " where ".implode(" or ", $sWhere)."                                                              ";
     $sSqlCarnes .= " group by k00_numcgm;                                                                            ";

     $rsSqlRegistrosCarnes = $oDaoArrecad->sql_record($sSqlCarnes);

      if ($oDaoArrecad->numrows > 0) {

        $aRegistrosCarnes   = db_utils::getCollectionByRecord($rsSqlRegistrosCarnes);
        $oRetorno->aCarnes = $aRegistrosCarnes;

      } else {

        $oRetorno->status  = 2;
        $oRetorno->message = $oDaoArrecad->erro_msg;
      }

    }

    break;

    case "ValidaCancelaReciboPaga" :

      $oDaoPartilhaArquivoReg = db_utils::getDao("partilhaarquivoreg");
      $sSqlPartilhaArquivo = $oDaoPartilhaArquivoReg->sql_query(null, "partilhaarquivo.*", null, "processoforopartilhacusta.v77_numnov = {$oParam->numnov} and v78_tipoarq = 1");
      $rsPartilhaArquivoReg = $oDaoPartilhaArquivoReg->sql_record($sSqlPartilhaArquivo);
      if ($oDaoPartilhaArquivoReg->numrows > 0) {
        $oDadosPartilhaArquivo = db_utils::fieldsMemory($rsPartilhaArquivoReg, 0);

        $oRetorno->status  = 2;
        $oRetorno->message  = "Recibo não poderá ser cancelado pois foi enviado para cobrança registrada para o banco \\n\\n";
        $oRetorno->message .= "Arquivo: {$oDadosPartilhaArquivo->v78_sequencial} - {$oDadosPartilhaArquivo->v78_nomearq} ";
        $oRetorno->message .= "em ".db_formatar($oDadosPartilhaArquivo->v78_dtgeracao, "f");
      }

      break;

    case "CancelaReciboPaga" :

      try {
        db_inicio_transacao();
        $oRetorno->status = 1;
        $oDaoCancReciboPaga = db_utils::getDao("cancrecibopaga");

        db_app::import('recibo');

        $oRecibo = new recibo(null,null,null,$oParam->numnov);
        $oRecibo->cancelar($oParam->motivo);

        $oRetorno->message = "Cancelado com Sucesso";
        db_fim_transacao(false);
      } catch (Exception $eErro) {

        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = $eErro->getMessage();
      }
    break;

}

$oRetorno->message = urlencode($oRetorno->message);
echo $oJson->encode($oRetorno);







/**
 * Retorna os Débitos  selecionados no formulário da CGF
 * Com minimo e maximo de parcelas array com combinação de numpre, numpar e receita e string de retorno
 * @param object   $oFormulario - Objeto contentdo os dados do Formulário da CGF
 * @param integer  $iTipoAgrupamento - Tipo de Agrupamento do Tipo de Débito
 */
function retornaDebitosSelecionados($oFormulario,$iTipoAgrupamento = null) {

  $aChecks            = array();
  $aParcelas          = array();
  $sGeraCarne         = "";
  $iI                 = 0;
  $sRecibos           = '';
  $aInicial           = array();
  $iTotalSelecionados = 0;
  $aObjDebitos        = array();
  /**
   * Valida se é uma inicial do Foro
   *
   */
    if (isset($oFormulario->oDadosForm->inicial) ) {


      foreach ($oFormulario->oDadosForm as $sChave => $sValor) {

        if ( stripos(" ".$sChave, "CHECK") ) {
          $iTotalSelecionados++;
          $aInicial[]   = $sValor;

         $sSqlInicial  = " select distinct                                                              ";
         $sSqlInicial .= "        arrecad.k00_numpre,                                                   ";
         $sSqlInicial .= "         arrecad.k00_numpar                                                   ";
         $sSqlInicial .= "    from inicialnumpre                                                        ";
         $sSqlInicial .= "          inner join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre ";
         $sSqlInicial .= "  where v59_inicial in (".implode(", ", $aInicial).");                          ";
         $rsSqlInicial = db_query($sSqlInicial);
         $aIniciais  = db_utils::getCollectionByRecord($rsSqlInicial);

         foreach ($aIniciais as $oInicial) {
            $aParcelas[]          = $oInicial->k00_numpar;
            $sValores             = "N". $oInicial->k00_numpre."P".$oInicial->k00_numpar."R0";
            $aChecks["CHECK".$iI] = array("Numpre"=>$oInicial->k00_numpre, "Numpar"=>$oInicial->k00_numpar, "Receita"=>"0", "valor"=>$sValores);

            /**
            * Cria array com os numpres e numpar dos débitos
            */
            $oNumpreNumpar          = new stdClass();
            $oNumpreNumpar->iNumpre = $oInicial->k00_numpre;
            $oNumpreNumpar->iNumpar = $oInicial->k00_numpar;
            $aObjDebitos[]          = $oNumpreNumpar;


            $aNumpreValidacao[]   = $oInicial->k00_numpre;
            $aNumparValidacao[]   = $oInicial->k00_numpar;

            $iI++;
          }
        } else {
          /**
           * Formata string Gera Carne
           */
          if ($sChave == "geracarne") {
            $sGeraCarne = $sValor;
          } else {

            if (is_array($sValor) ) {
              $aDadosForm[$sChave] = $sValor[0];
            } else {
              $aDadosForm[$sChave] = $sValor;
            }
          }
        }
      }
    } else {

      foreach ($oFormulario->oDadosForm as $sChave => $sValor) {

        if ( stripos(" ".$sChave, "CHECK") ) {

          $aNumpre             = split("N", $sValor);
          foreach ($aNumpre as $iIndiceNumpre => $sNumpres) {

            if ($sNumpres == "") {
             continue;
            }
            if($sNumpres != "") {
              $iTotalSelecionados++;
            }
            $aParcela               = split("P", $sNumpres);
            $iNumpre                = $aParcela[0];
            $aSliceParcela          = split("R", $aParcela[1]);
            $iNumpar                = (int)$aSliceParcela[0];
            $iReceita               = (int)$aSliceParcela[1];

            $aParcelas[]            = $iNumpar;
            $aChecks["CHECK".$iI]   = array("Numpre"=>$iNumpre, "Numpar"=>$iNumpar, "Receita"=>$iReceita, "valor"=>"N".$sNumpres);

            /**
             * Cria array com os numpres e numpar dos débitos
             */
            $oNumpreNumpar          = new stdClass();
            $oNumpreNumpar->iNumpre = $iNumpre;
            $oNumpreNumpar->iNumpar = $iNumpar;
            $aObjDebitos[]          = $oNumpreNumpar;

            $aNumpreValidacao[]     = $iNumpre;
            $aNumparValidacao[]     = $iNumpar;
            $sRecibos              .= " or (k00_numpre = {$iNumpre} and k00_numpar = {$iNumpar})" ;
            $iI++;
          }
        } else {
          /**
           * Formata string Gera Carne
           */
          if ($sChave == "geracarne") {
            $sGeraCarne = $sValor;
          } elseif ($sChave == "numpre_unica") {

            $aDadosForm[$sChave]    = $sValor;
            if(!empty($oFormulario->oDadosForm->numpre_unica)){

              $aParcelas[]            = 0;
              $aChecks["CHECKU".$iI]   = array("Numpre"=>$oFormulario->oDadosForm->numpre_unica, "Numpar"=>0, "Receita"=>0, "valor"=>"N0");

              $oNumpreNumpar          = new stdClass();
              $oNumpreNumpar->iNumpre = $oFormulario->oDadosForm->numpre_unica;
              $oNumpreNumpar->iNumpar = 0;
              $aObjDebitos[]          = $oNumpreNumpar;

              $aNumpreValidacao[]     = $oFormulario->oDadosForm->numpre_unica;
              $aNumparValidacao[]     = '0';
              $sRecibos              .= " or (k00_numpre = {$oFormulario->oDadosForm->numpre_unica} and k00_numpar = {0})";
            }
          } else {

            if (is_array($sValor) ) {
              $aDadosForm[$sChave] = $sValor[0];
            } else {
              $aDadosForm[$sChave] = $sValor;
            }
          }
        }
      }
    }
  /**
   * Caso o agrupamento do débito seja do tipo 2 (Parcial)
   * Adiciona os débitos do arrecad ao recibo.
   */

  if ($iTipoAgrupamento == 2 && empty($oFormulario->oDadosForm->geracarne)) {

    $oParametroConsulta = new stdClass();

    if(!empty($aDadosForm['ver_matric']) ) {

      $sTabela    = "arrematric";
      $sConsulta  = "k00_matric = {$aDadosForm['ver_matric']}";

    } elseif (!empty($aDadosForm['ver_inscr']) ) {

      $sTabela    = "arreinscr";
      $sConsulta  = "k00_inscr  = {$aDadosForm['ver_inscr']}";

    } elseif (!empty($aDadosForm['ver_numcgm']) ) {

      $sTabela    = "arrenumcgm";
      $sConsulta  = "k00_numcgm = {$aDadosForm['ver_numcgm']}";
    } else {
      return false;
    }
    $aDebitosAgrupados = retornaDebitosAgrupados($aObjDebitos,
                                                 $oFormulario->oDadosForm->tipo_debito,
                                                 $sTabela,
                                                 $sConsulta);
    foreach ($aDebitosAgrupados as $oDebitosAgrupados) {

      $iI++;
      $oDebitosAgrupados->iNumpre;

      $aChecks["CHECK".$iI]   = array("Numpre" => $oDebitosAgrupados->iNumpre,
                                      "Numpar" => $oDebitosAgrupados->iNumpar,
                                      "Receita"=> $oDebitosAgrupados->iReceit,
                                      "valor"  => "N".$oDebitosAgrupados->iNumpre.
                                                  "P".$oDebitosAgrupados->iNumpar.
                                                  "R".$oDebitosAgrupados->iReceit
      );

      /**
       * Cria array com os numpres e numpar dos débitos
       */
      $oNumpreNumpar          = new stdClass();
      $oNumpreNumpar->iNumpre = $oDebitosAgrupados->iNumpre;
      $oNumpreNumpar->iNumpar = $oDebitosAgrupados->iNumpre;
      $aObjDebitos[]          = $oNumpreNumpar;

      $aNumpreValidacao[]     = $oDebitosAgrupados->iNumpre;
      $aNumparValidacao[]     = $oDebitosAgrupados->iNumpar;
      $sRecibos              .= " or (k00_numpre = {$oDebitosAgrupados->iNumpre} and k00_numpar = {$oDebitosAgrupados->iNumpar})" ;
    }
  }



  /**
   * Valida loteador quando matricula não estiver setada
   */

  $lLoteador = false;

  if (!empty($oFormulario->oDadosForm->ver_numcgm) and empty($oFormulario->oDadosForm->ver_matric) ) {

    $sSqlLoteador = "  select *                                                                   ";
    $sSqlLoteador.= "    from loteam                                                              ";
    $sSqlLoteador.= "         left join loteamcgm  on loteamcgm.j120_loteam = loteam.j34_loteam   ";
    $sSqlLoteador.= "   where j120_cgm = {$oFormulario->oDadosForm->ver_numcgm}                   ";

    $rsSqlLoteador = db_query($sSqlLoteador) or die($sSqlLoteador);
    if (pg_numrows($rsSqlLoteador) > 0) {
      $lLoteador = true;
    }

  }

  $sWhereLoteador = " and k40_forma <> 3";

  if ($lLoteador == true) {
    $sWhereLoteador = " and k40_forma = 3";
  }

  $oRetornoFuncao                                      = new stdClass();
  $oRetornoFuncao->aDadosChecks                        = $aChecks;
  $oRetornoFuncao->aOutrosDados                        = $aDadosForm;
  $oRetornoFuncao->aIniciais                           = $aInicial;
  $oRetornoFuncao->iMaxParc                            = max($aParcelas);
  $oRetornoFuncao->iMinParc                            = min($aParcelas);
  $oRetornoFuncao->sGeraCarne                          = $sGeraCarne;
  $oRetornoFuncao->aValidaNumpre                       = $aNumpreValidacao;
  /**
   * Variaveis para chamada da função
   * recibodesconto
   */
  $oRetornoFuncao->oReciboDesconto                     = new stdClass();
  $oRetornoFuncao->oReciboDesconto->iTipoDebito        = $oFormulario->oDadosForm->tipo_debito;
  $oRetornoFuncao->oReciboDesconto->iTotalSelecionados = $iTotalSelecionados;
  $oRetornoFuncao->oReciboDesconto->sWhereLoteador     = $sWhereLoteador;
  $oRetornoFuncao->oReciboDesconto->iTotalRegistros    = $oFormulario->oDadosForm->totregistros;

  return $oRetornoFuncao;
}

/**
 * Retorna regra de desconto referente ao numpre e numpar de um débito
 * @param integer $numpre                  - Numpre do débito
 * @param integer $numpar                  - Parcela do débito
 * @param integer $tipo                    - Tipo de débito(arretipo)
 * @param integer $tipo_debito             - Tipo de débito(arretipo)?
 * @param string  $whereloteador           - Filtro quando houver loteador
 * @param integer $totalregistrospassados  - Total de parcelas selecionadas na CGF
 * @param integer $totregistros            - Variável da CGF "$totregistros"
 * @return integer $regraDesconto           - Inteiro indicando a regra de desconto que deve ser aplicada ao débito e parcela
 *                                            declarada.
 */
function reciboDesconto2($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros) {

  // desconto
  global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc;

  $cadtipoparc = 0;

  $sqlvenc = "select k00_dtvenc
                from arrecad
               where k00_numpre = $numpre
                 and k00_numpar = $numpar";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  if (pg_numrows($resultvenc) == 0) {
    return 0;
  }
  db_fieldsmemory($resultvenc, 0);


  $dDataUsu = date("Y-m-d", db_getsession("DB_datausu") );

  $sqltipoparc = "select k40_codigo,
                         k40_todasmarc,
                         cadtipoparc
                    from tipoparc
                         inner join cadtipoparc    on cadtipoparc     = k40_codigo
                         inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                   where maxparc = 1
                     and '{$dDataUsu}' >= k40_dtini
                     and '{$dDataUsu}' <= k40_dtfim
                     and k41_arretipo   = $tipo $whereloteador
                     and '$k00_dtvenc' >= k41_vencini
                     and '$k00_dtvenc' <= k41_vencfim ";

  $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
  if (pg_numrows($resulttipoparc) > 0) {
    db_fieldsmemory($resulttipoparc, 0);
  } else {

    $sqltipoparc = "select k40_codigo,
                           k40_todasmarc,
                           cadtipoparc
                      from tipoparc
                           inner join cadtipoparc on cadtipoparc = k40_codigo
                           inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                     where maxparc = 1
                       and k41_arretipo = $tipo
                       and '{$dDataUsu}' >= k40_dtini
                       and '{$dDataUsu}' <= k40_dtfim
    $whereloteador
                       and '$k00_dtvenc' >= k41_vencini
                       and '$k00_dtvenc' <= k41_vencfim ";

    $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);

    if (pg_numrows($resulttipoparc) == 1) {
      db_fieldsmemory($resulttipoparc, 0);
    } else {

      $k40_todasmarc = false;
    }
  }

  $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
  $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
  $passar = false;

  if (pg_numrows($resulttipoparcdeb) == 0) {
    $passar = true;
  } else {

    $sqltipoparcdeb = "select k40_codigo, k40_todasmarc
                         from cadtipoparcdeb
                              inner join cadtipoparc on k40_codigo = k41_cadtipoparc
                        where k41_cadtipoparc = $cadtipoparc and
                       k41_arretipo = $tipo_debito $whereloteador and
                       '$k00_dtvenc' >= k41_vencini and
                       '$k00_dtvenc' <= k41_vencfim ";
    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    if (pg_numrows($resulttipoparcdeb) > 0) {
      $passar = true;
    }
  }

  if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
    $desconto = 0;
  } else {
    $desconto = $k40_codigo;
  }

  return $desconto;

}

function recibodesconto($numpre, $numpar, $tipo, $tipo_debito, $whereloteador, $totalregistrospassados, $totregistros, $ver_matric, $ver_inscr) {

  $sql_cgc = "select cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit");
  $rs_cgc = db_query($sql_cgc);
  $oConfig = new stdClass();
  $oConfig->cgc         = pg_result($rs_cgc,0,0);
  $oConfig->db21_codcli = pg_result($rs_cgc,0,1);

  /* testa se está em dia com IPTU */
  $iTemDesconto = 1;
  //die("ver_matric: $ver_matric - cgc: $oConfig->cgc");

  if ( ( (int) @$ver_matric > 0 or (int) @$ver_inscr > 0 ) and $oConfig->db21_codcli == 19985 and false ) { // marica/rj

    $sIptuAberto  = "";
    $sIptuAberto .= " select count(distinct k00_numpar) from ( ";
    $sIptuAberto .= " select arrecad.k00_numpre, arrecad.k00_numpar, arrejustreg.k28_numpre, k27_dias, ";
    $sIptuAberto .= "        max(k27_data) as k27_data ";
    $sIptuAberto .= " from caixa.arrecad ";
    $sIptuAberto .= " inner join caixa.arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
    if ( (int) @$ver_matric > 0 ) {
      $sIptuAberto .= " inner join caixa.arrematric on arrecad.k00_numpre = arrematric.k00_numpre ";
    } else {
      $sIptuAberto .= " inner join caixa.arreinscr on arrecad.k00_numpre = arreinscr.k00_numpre ";
    }
    $sIptuAberto .= " left join ( select k28_sequencia,k28_arrejust,k28_numpre,k28_numpar,k27_dias,k27_data ";
		$sIptuAberto .= "							from ( select max(k28_sequencia) as k28_sequencia, ";
		$sIptuAberto .= "							              max(k28_arrejust) as k28_arrejust, ";
		$sIptuAberto .= "														k28_numpar, ";
		$sIptuAberto .= "														k28_numpre ";
		$sIptuAberto .= "											from arrejustreg ";
		$sIptuAberto .= "											group by k28_numpre, ";
    $sIptuAberto .= "    													k28_numpar ";
		$sIptuAberto .= "									) as subarrejust ";
		$sIptuAberto .= "									inner join arrejust on arrejust.k27_sequencia = subarrejust.k28_arrejust ";
		$sIptuAberto .= "						) as arrejustreg on arrejustreg.k28_numpre = arrecad.k00_numpre ";
		$sIptuAberto .= "						                and arrejustreg.k28_numpar = arrecad.k00_numpar ";
    if ( (int) @$ver_matric > 0 ) {
      $sIptuAberto .= " where arrecad.k00_tipo = 1 and k00_matric = $ver_matric ";
    } else {
      $sIptuAberto .= " where arrecad.k00_tipo = 2 and k00_inscr = $ver_inscr ";
    }
    $sIptuAberto .= " group by arrecad.k00_numpre, arrecad.k00_numpar, arrejustreg.k28_numpre, k27_dias ";
    $sIptuAberto .= " ) as x ";
    $sIptuAberto .= " where case when k28_numpre is not null then case when ( k27_data + k27_dias >= current_date ) then false else true end else true end ";

    $rsIptuAberto = db_query($sIptuAberto) or die($sIptuAberto);
    if ( pg_numrows($rsIptuAberto) > 0 ) {
      $iQuantAberto = pg_result($rsIptuAberto,0,0);
      if ( (int) @$ver_matric > 0 ) {
        $iParcTesta = 2;
      } else {
        $iParcTesta = 0;
      }

      if ( $iQuantAberto > $iParcTesta ) {
        $iTemDesconto = 0;
      }
    }
  }
  // desconto
  global $k00_dtvenc, $k40_codigo, $k40_todasmarc, $cadtipoparc, $k00_dtoper;

  $cadtipoparc = 0;

  $sqlvenc = "select k00_dtvenc, k00_dtoper
							  from arrecad
							 where k00_numpre = $numpre
							   and k00_numpar = $numpar";
  $resultvenc = db_query($sqlvenc) or die($sqlvenc);
  if (pg_numrows($resultvenc) == 0) {
    return 0;
  }
  db_fieldsmemory($resultvenc, 0);

  $dDataUsu = date("Y-m-d",db_getsession("DB_datausu"));

  /**
   * Alterado query para quando for cliente osório e regra 18 (REFIM)
   * deve ser utilizado a data de operação para validação da regra e não
   * a data de vencimento do débito
   */
  $sSqlWhereRegra = " '$k00_dtvenc' >= k41_vencini and '$k00_dtvenc' <= k41_vencfim ";
  $sqltipoparc = "select k40_codigo,
	                       k40_todasmarc,
	                       cadtipoparc
                  	from tipoparc
                         inner join cadtipoparc    on cadtipoparc     = k40_codigo
                         inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
		               where maxparc = 1
		                 and '{$dDataUsu}' >= k40_dtini
		                 and '{$dDataUsu}' <= k40_dtfim
		                 and k41_arretipo   = $tipo
                     $whereloteador
		                 and $sSqlWhereRegra ";

  $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
  if (pg_numrows($resulttipoparc) > 0) {
    db_fieldsmemory($resulttipoparc,0);
  } else {

    /**
     * Alterado query para quando for cliente osório e regra 18 (REFIM)
     * deve ser utilizado a data de operação para validação da regra e não
     * a data de vencimento do débito
     */
    $sSqlWhereRegra = " '$k00_dtvenc' >= k41_vencini and '$k00_dtvenc' <= k41_vencfim ";
    $sqltipoparc = "select k40_codigo,
		                       k40_todasmarc,
		                       cadtipoparc
                  		from tipoparc
                        	 inner join cadtipoparc on cadtipoparc = k40_codigo
                        	 inner join cadtipoparcdeb on k41_cadtipoparc = cadtipoparc
                     where maxparc = 1
                       and k41_arretipo = $tipo

                       and '{$dDataUsu}' >= k40_dtini
                       and '{$dDataUsu}' <= k40_dtfim
                           $whereloteador
                       and $sSqlWhereRegra";

                       $resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);

                       if (pg_numrows($resulttipoparc) == 1) {
                         db_fieldsmemory($resulttipoparc,0);
                       } else {
                         $k40_todasmarc = false;
                       }
  }

  $sqltipoparcdeb = "select * from cadtipoparcdeb limit 1";
  $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
  $passar = false;

  if (pg_numrows($resulttipoparcdeb) == 0) {
    $passar = true;
  } else {

    /**
     * Alterado query para quando for cliente osório e regra 18 (REFIM)
     * deve ser utilizado a data de operação para validação da regra e não
     * a data de vencimento do débito
     */
    $sSqlWhereRegra = " '$k00_dtvenc' >= k41_vencini and '$k00_dtvenc' <= k41_vencfim ";
    $sqltipoparcdeb = "select k40_codigo, k40_todasmarc
											   from cadtipoparcdeb
											        inner join cadtipoparc on k40_codigo = k41_cadtipoparc
											  where k41_cadtipoparc = $cadtipoparc
                          and k41_arretipo = $tipo_debito
                              $whereloteador
                          and $sSqlWhereRegra";

    $resulttipoparcdeb = db_query($sqltipoparcdeb) or die($sqltipoparcdeb);
    if (pg_numrows($resulttipoparcdeb) > 0) {
      $passar = true;
    }
  }

  if (pg_numrows($resulttipoparc) == 0 or ($k40_todasmarc == 't'?$totalregistrospassados <> $totregistros:false) or $passar == false) {
    $desconto = 0;
  } else {
    $desconto = $k40_codigo;
  }

  return $desconto;
}

/**
 * Quando houver agrupamento de débitos verifica no arrecad os débitos RETORNANDO NUMPRE E NUMPAR dos relacionados.
 * @param array  $aNumpre_Numpar
 */
function retornaDebitosAgrupados($aNumpre_Numpar, $iTipoDebito, $sTabela, $sWhere){


  $sTabela        = $sTabela;
  $sCampoPesquisa = $sWhere;
  $aRetorno       = array();

  $aWhere         = array();

  foreach ($aNumpre_Numpar as $oDebitosSelecionados) {
    $aWhere[]= "(k00_numpre = {$oDebitosSelecionados->iNumpre} and k00_numpar = {$oDebitosSelecionados->iNumpar}) \n";
  }

  if (count($aNumpre_Numpar) > 0) {

    $sSqlDataVencimentoDebitos  = "select distinct k00_descr                as descr_arretipo,        \n";
    $sSqlDataVencimentoDebitos .= "       extract  (months from k00_dtvenc) as mes_agrupa,            \n";
    $sSqlDataVencimentoDebitos .= "       extract  (year   from k00_dtvenc) as ano_agrupa             \n";
    $sSqlDataVencimentoDebitos .= "  from arrecad                                                     \n";
    $sSqlDataVencimentoDebitos .= "       inner join arretipo on arrecad.k00_tipo = arretipo.k00_tipo \n";
    $sSqlDataVencimentoDebitos .= " where ".implode(" or ", $aWhere)."                                \n";

    $rsDataVencimentoDebitos    = db_query($sSqlDataVencimentoDebitos);

    if($rsDataVencimentoDebitos && pg_num_rows($rsDataVencimentoDebitos) > 0) {

      $aObjDataVencimentoDebitos = db_utils::getCollectionByRecord($rsDataVencimentoDebitos);

      foreach ($aObjDataVencimentoDebitos as $oTipoDebito) {

        $sSqlDebitosAgrupar = " select distinct                                                                                     \n";
        $sSqlDebitosAgrupar.= "        arrecad.k00_numpre as numpre,                                                                \n";
        $sSqlDebitosAgrupar.= "        arrecad.k00_numpar as numpar,                                                                \n";
        $sSqlDebitosAgrupar.= "        arrecad.k00_receit as receita                                                                \n";
        $sSqlDebitosAgrupar.= "   from (select {$sTabela}.*                                                                         \n";
        $sSqlDebitosAgrupar.= "           from {$sTabela}                                                                           \n";
        $sSqlDebitosAgrupar.= "                inner join arreinstit  on arreinstit.k00_numpre = {$sTabela}.k00_numpre              \n";
        $sSqlDebitosAgrupar.= "                                      and arreinstit.k00_instit = ".db_getsession("DB_instit")."     \n";
        $sSqlDebitosAgrupar.= "          where {$sTabela}.{$sCampoPesquisa}                                                         \n";
        $sSqlDebitosAgrupar.= "        ) as {$sTabela}                                                                              \n";
        $sSqlDebitosAgrupar.= "                                                                                                     \n";
        $sSqlDebitosAgrupar.= "        inner join arrecad  on arrecad.k00_numpre =  {$sTabela}.k00_numpre                           \n";
        $sSqlDebitosAgrupar.= "                           and arrecad.k00_tipo <> {$iTipoDebito}                                    \n";
        $sSqlDebitosAgrupar.= "                           and extract (months from arrecad.k00_dtvenc) = {$oTipoDebito->mes_agrupa} \n";
        $sSqlDebitosAgrupar.= "                           and extract (years  from arrecad.k00_dtvenc) = {$oTipoDebito->ano_agrupa} \n";
        $sSqlDebitosAgrupar.= "  where not exists (select arrenaoagrupa.k00_numpre                                                  \n";
        $sSqlDebitosAgrupar.= "                      from arrenaoagrupa                                                             \n";
        $sSqlDebitosAgrupar.= "                     where arrenaoagrupa.k00_numpre = {$sTabela}.k00_numpre)                         \n";


        $rsDebitosAgrupar   = db_query($sSqlDebitosAgrupar);

        if($rsDebitosAgrupar && pg_num_rows($rsDebitosAgrupar) > 0 ) {

          $aDebitosAgrupar  = db_utils::getCollectionByRecord($rsDebitosAgrupar);

          foreach($aDebitosAgrupar as $oDebitosAgrupar) {

            $oDadosRetorno          = new stdClass();
            $oDadosRetorno->iNumpre = $oDebitosAgrupar->numpre;
            $oDadosRetorno->iNumpar = $oDebitosAgrupar->numpar;
            $oDadosRetorno->iReceit = $oDebitosAgrupar->receita;
            $aRetorno[]    = $oDadosRetorno;
          }
        }
      }
    } else {
      return $aRetorno;
    }
  } else {
    return $aRetorno;
  }
  return $aRetorno;
}
