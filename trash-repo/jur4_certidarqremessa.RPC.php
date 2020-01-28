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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("classes/db_listacda_classe.php");
require_once("classes/db_certdiv_classe.php");
require_once("classes/db_certter_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_inicialcert_classe.php");
require_once("classes/db_certidarqremessa_classe.php");
require_once("classes/db_certidarqretorno_classe.php");
require_once("dbforms/db_layouttxt.php");
require_once("classes/db_parjuridico_classe.php");
require_once("classes/db_cgm_classe.php");

$oJson                 = new services_json();
$oPost                 = db_utils::postMemory($_POST);
$oParam                = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\","",$oPost->json)));

$oListaCda             = new cl_listacda;
$oInicialcert          = new cl_inicialcert; 
$oArrecad              = new cl_arrecad;
$oDbConfig             = new cl_db_config; 
$oCertidArqremessa     = new cl_certidarqremessa;
$clparjuridico         = new cl_parjuridico;
$clcgm                 = new cl_cgm;

$lErro                 = false;
$lInicialAtiva         = false;

$aDadosProcessar       = array();
$aDadosProcessar       = array();
$aLinhas2Processadas   = array();
$aLinhas3Processadas   = array();
$aLinhas4Processadas   = array();
$aLinhas5Processadas   = array();

$sWhereArrecad         = "";
$sMensagem             = "";
$sNomeTxt              = '';
$iNextCertidarqremessa = '';
$sDataGeracao          = date('Y-m-d', db_getsession('DB_datausu'));
$iCodLayOut            = 97;

$oRetorno              = new stdClass();
$oRetorno->status      = 1;
$oRetorno->message     = 1;

/*
 * Funçao para validar CPF / CNPJ
 */
   function validaUFIR($data){
     $sSqlVlrInfla = "select fc_vlinf from fc_vlinf('UFIR','{$data}');";
     $rsVlrInfla   = db_query($sSqlVlrInfla);
     $nVlrInfla    = db_utils::fieldsMemory($rsVlrInfla,0)->fc_vlinf;
     return $nVlrInfla;
   }
   // VERIFICA CPF
   function validaCPF($cpf) {
      $soma = 0;
      
      if (strlen($cpf) != 11) {
         return false;
      }
      // Verifica 1º digito      
      for ($i = 0; $i < 9; $i++) {         
         $soma += (($i+1) * $cpf[$i]);
      }
      $d1 = ($soma % 11);
      
      if ($d1 == 10) {
         $d1 = 0;
      }
      $soma = 0;
      // Verifica 2º digito
      for ($i = 9, $j = 0; $i > 0; $i--, $j++) {
         $soma += ($i * $cpf[$j]);
      }
      $d2 = ($soma % 11);
      if ($d2 == 10) {
         $d2 = 0;
      }      
      if ($d1 == $cpf[9] && $d2 == $cpf[10]) {
         return true;
      }
      else {
         return false;
      }
   }
   
   // VERFICA CNPJ
   function validaCNPJ($cnpj) {
   
      if (strlen($cnpj) != 14) {
         return false;
      }   
      $soma = 0;
      $soma += ($cnpj[0] * 5);
      $soma += ($cnpj[1] * 4);
      $soma += ($cnpj[2] * 3);
      $soma += ($cnpj[3] * 2);
      $soma += ($cnpj[4] * 9);
      $soma += ($cnpj[5] * 8);
      $soma += ($cnpj[6] * 7);
      $soma += ($cnpj[7] * 6);
      $soma += ($cnpj[8] * 5);
      $soma += ($cnpj[9] * 4);
      $soma += ($cnpj[10] * 3);
      $soma += ($cnpj[11] * 2);
      
      $d1 = $soma % 11;
      $d1 = $d1 < 2 ? 0 : 11 - $d1;
      
      $soma = 0;
      $soma += ($cnpj[0] * 6);
      $soma += ($cnpj[1] * 5);
      $soma += ($cnpj[2] * 4);
      $soma += ($cnpj[3] * 3);
      $soma += ($cnpj[4] * 2);
      $soma += ($cnpj[5] * 9);
      $soma += ($cnpj[6] * 8);
      $soma += ($cnpj[7] * 7);
      $soma += ($cnpj[8] * 6);
      $soma += ($cnpj[9] * 5);
      $soma += ($cnpj[10] * 4);
      $soma += ($cnpj[11] * 3);
      $soma += ($cnpj[12] * 2);
      
      $d2 = $soma % 11;
      $d2 = $d2 < 2 ? 0 : 11 - $d2;
      
      if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
         return true;
      }
      else {
         return false;
      }
   } 
   
//=======================================================  FIM DAS VALIDAÇÕES CPF / CNPJ================================   
   





switch($oParam->exec) {

  case 'processar' :

    $iLista             = $oParam->iLista;
    $nValorTotalInicial = 0;
    $nValorTotalCertid  = 0;
    $iInicial           = null;
    $iExercCDA          = null;
    $iMenorInicial      = 0;
    $iCodMunic          = "";
    $pArquivoTxt        = "";

    /**
     * Função utilizada para calcular o valor da certidão
     * 
     */
    function fc_valorCertid($iCertid, $dDtEmiss) {
    	  
    	  $nValor = 0;
        $sSqlParcelamentos  = " select v14_parcel, ";
        $sSqlParcelamentos .= "        v07_numpre  ";
        $sSqlParcelamentos .= "   from certter";
        $sSqlParcelamentos .= "  inner join termo on v07_parcel = v14_parcel";
        $sSqlParcelamentos .= "  where v14_certid in ($iCertid) ";
        $rsParcelamentos    = db_query($sSqlParcelamentos);
        $iLinhasParcel      = pg_num_rows($rsParcelamentos);

        for ( $iIndParcel=0; $iIndParcel < $iLinhasParcel; $iIndParcel++) {

          $oParcelamento         = db_utils::fieldsMemory($rsParcelamentos,$iIndParcel);

          $rsDadosDebitoCorrigido = debitos_numpre($oParcelamento->v07_numpre, 0, 0, 
                                                   mktime(0, 0, 0, substr($dDtEmiss, 5, 2), 
                                                   substr($dDtEmiss, 8, 2), 
                                                   substr($dDtEmiss, 0, 4)), 
                                                   substr($dDtEmiss, 0, 4), 0);

          if ( $rsDadosDebitoCorrigido != false ) {

            $iLinhasDebito = pg_num_rows($rsDadosDebitoCorrigido);

            for ($iIndDebito = 0; $iIndDebito < $iLinhasDebito; $iIndDebito++) {
              $nValor += db_utils::fieldsMemory($rsDadosDebitoCorrigido,$iIndDebito)->total;
            }
          }
        }

        $sSqlDadosDebitos  = " select distinct    ";
        $sSqlDadosDebitos .= "        k00_numpre, ";
        $sSqlDadosDebitos .= "        k00_numpar  ";
        $sSqlDadosDebitos .= "   from certdiv";
        $sSqlDadosDebitos .= "        inner join divida   on certdiv.v14_coddiv = divida.v01_coddiv";
        $sSqlDadosDebitos .= "        inner join arrecad  on arrecad.k00_numpre = divida.v01_numpre ";
        $sSqlDadosDebitos .= "                           and arrecad.k00_numpar = divida.v01_numpar";
        $sSqlDadosDebitos .= "  where v14_certid in ($iCertid)";

        $rsDadosDebitos      = db_query($sSqlDadosDebitos);
        $iLinhasDadosDebitos = pg_num_rows($rsDadosDebitos); 

        for ( $iIndDadosDebitos = 0; $iIndDadosDebitos < $iLinhasDadosDebitos; $iIndDadosDebitos++ ) {

          $oDivida = db_utils::fieldsmemory($rsDadosDebitos, $iIndDadosDebitos);
          $rsDadosDebitoCorrigido = debitos_numpre($oDivida->k00_numpre, 0, 0, 
                                                   mktime(0, 0, 0, substr($dDtEmiss, 5, 2), 
                                                   substr($dDtEmiss, 8, 2), 
                                                   substr($dDtEmiss, 0, 4)), 
                                                   substr($dDtEmiss, 0, 4), 
                                                   $oDivida->k00_numpar);

          for ($iIndDebito = 0; $iIndDebito < pg_numrows($rsDadosDebitoCorrigido); $iIndDebito++) {
            $nValor += db_utils::fieldsMemory($rsDadosDebitoCorrigido, $iIndDebito)->total;
          }
        }
        return $nValor;
    }
    
   
    /**
     * Função que calcula o valor total da inicial
     *
     */
    function fc_valorInicial($iInicial) {
    	
        $nValor = 0;
    	  $sSqlCertidoesInicial = "select v51_inicial, 
    	                                  v13_certid, 
    	                                  v13_dtemis 
            	                     from inicialcert
            	                    inner join certid   on v13_certid = v51_certidao 
            	                    inner join listacda on v13_certid = v81_certid 
    	                            where v51_inicial = $iInicial ";
    	  $rsCertidoesInicial   = db_query($sSqlCertidoesInicial);
    	  for ($iCertidoesInicial = 0; $iCertidoesInicial < pg_num_rows($rsCertidoesInicial); $iCertidoesInicial++) {
    	  	 $oDadosCertid   = db_utils::fieldsMemory($rsCertidoesInicial, $iCertidoesInicial);
    	  	 $nValor        += fc_valorCertid($oDadosCertid->v13_certid, $oDadosCertid->v13_dtemis);
    	  }
    	  return $nValor;
    }
    
    try {
    
      /*
       * Verificamos se a lista informada já esta em outro arquivo
       */
      $oCertidArqremessa->sql_record($oCertidArqremessa->sql_query_file(null,"v83_sequencial","","v83_lista = {$iLista}"));
      if ($oCertidArqremessa->numrows > 0) {
        throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.lista_ja_enviada'));
      }
      
      /*
       * Verificamos se todas as cdas geradas através da lista possuem inicial gerada
       */
      $sSqlValidaCdaInicial  = " select *                                             ";
      $sSqlValidaCdaInicial .= "   from listacda                                      ";
      $sSqlValidaCdaInicial .= "  where v81_lista = {$iLista}                         ";
      $sSqlValidaCdaInicial .= "    and not exists (select 1                          "; 
      $sSqlValidaCdaInicial .= "                      from inicialcert                ";
      $sSqlValidaCdaInicial .= "                     where v51_certidao = v81_certid) ";
      $rsValidaCdaInicial = db_query($sSqlValidaCdaInicial);
      if (pg_num_rows($rsValidaCdaInicial) > 0) {
        
        $oParms = new stdClass();
        $oParam->iLista = $iLista;
        throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.cda_sem_inicial', $oParms));
      }

      db_inicio_transacao();
      
      
      $sWhere = " v81_lista = {$iLista} ";

      $sSqlDados  = " select v50_inicial,                                                                                ";
      $sSqlDados .= "        to_char(v50_data,'DDMMYYYY') as v50_data,                                                   ";
      $sSqlDados .= "        v50_data as dtufir,                                                                         ";
      $sSqlDados .= "        extract(year from v50_data) as ano_inicial,                                                 ";
      $sSqlDados .= "        extract(year from v50_data) as ano_final ,                                                  ";
      $sSqlDados .= "        v50_codlocal,                                                                               ";
      $sSqlDados .= "        v13_certid,                                                                                 ";
      $sSqlDados .= "        v13_dtemis,                                                                                 ";
      $sSqlDados .= "        v03_procedtipo,                                                                             ";
      $sSqlDados .= "        v03_procedtipogrupo,                                                                        ";
      $sSqlDados .= "        min(exerc) as exerc,                                                                        ";
      $sSqlDados .= "        dtinscr,                                                                                    ";
      $sSqlDados .= "        k00_numcgm,                                                                                 ";
      $sSqlDados .= "        k00_matric,                                                                                 ";
      $sSqlDados .= "        k00_inscr                                                                                   ";
      $sSqlDados .= "   from ( select distinct                                                                           ";
      $sSqlDados .= "                 inicial.v50_inicial,                                                               ";
      $sSqlDados .= "                 inicial.v50_data,                                                                  ";
      $sSqlDados .= "                 inicial.v50_codlocal,                                                              ";
      $sSqlDados .= "                 certid.v13_certid,                                                                 ";
      $sSqlDados .= "                 certid.v13_dtemis,                                                                 ";
      $sSqlDados .= "                 case                                                                               ";
      $sSqlDados .= "                   when v01_numcgm is not null                                                      ";
      $sSqlDados .= "                     then v01_numcgm                                                                ";
      $sSqlDados .= "                   else v07_numcgm                                                                  ";
      $sSqlDados .= "                 end as k00_numcgm,                                                                 ";
      $sSqlDados .= "                 case                                                                               ";
      $sSqlDados .= "                   when divida.v01_numpre is not null                                               ";
      $sSqlDados .= "                     then divida.v01_numpre                                                         ";
      $sSqlDados .= "                   else                                                                             ";
      $sSqlDados .= "                     case                                                                           ";
      $sSqlDados .= "                       when termo.v07_numpre is not null                                            ";
      $sSqlDados .= "                         then termo.v07_numpre                                                      ";
      $sSqlDados .= "                       else 0                                                                       ";
      $sSqlDados .= "                     end                                                                            ";
      $sSqlDados .= "                 end as k00_numpre,                                                                 ";
      $sSqlDados .= "                 case                                                                               ";
      $sSqlDados .= "                   when divida.v01_numpre is not null                                               ";
      $sSqlDados .= "                     then divida.v01_numpar                                                         ";
      $sSqlDados .= "                   else 0                                                                           ";
      $sSqlDados .= "                 end as k00_numpar,                                                                 ";
      $sSqlDados .= "                 case                                                                               ";
      $sSqlDados .= "                   when divida.v01_numpre is not null                                               "; 
      $sSqlDados .= "                     then divida.v01_exerc                                                          ";
      $sSqlDados .= "                   else extract(year from termo.v07_dtlanc)                                         ";
      $sSqlDados .= "                 end as exerc,                                                                      ";
      $sSqlDados .= "                 case                                                                               ";
      $sSqlDados .= "                   when divida.v01_numpre is not null                                               "; 
      $sSqlDados .= "                     then divida.v01_dtinsc                                                        ";
      $sSqlDados .= "                   else termo.v07_dtlanc                                                            ";
      $sSqlDados .= "                 end as dtinscr,                                                                    ";
      $sSqlDados .= "                 coalesce(v03_procedtipo,1) as v03_procedtipo,                                      ";
      $sSqlDados .= "                 coalesce(v29_sequencial,1) as v03_procedtipogrupo                                  ";      
      $sSqlDados .= "            from listacda                                                                           ";
      $sSqlDados .= "                 inner join certid         on listacda.v81_certid     = certid.v13_certid           ";
      $sSqlDados .= "                 inner join inicialcert    on listacda.v81_certid     = inicialcert.v51_certidao    ";
      $sSqlDados .= "                 inner join inicial        on inicialcert.v51_inicial = inicial.v50_inicial         ";
      $sSqlDados .= "                                          and inicial.v50_situacao    = 1                           ";
      $sSqlDados .= "                 left join certdiv         on certdiv.v14_certid      = listacda.v81_certid         ";
      $sSqlDados .= "                 left join divida          on divida.v01_coddiv       = certdiv.v14_coddiv          ";
      $sSqlDados .= "                 left join proced          on proced.v03_codigo       = divida.v01_proced           ";
      $sSqlDados .= "                 left join procedtipo      on proced.v03_procedtipo   = procedtipo.v28_sequencial      ";      
      $sSqlDados .= "                 left join procedtipogrupo on procedtipo.v28_grupo    = procedtipogrupo.v29_sequencial ";      
      $sSqlDados .= "                 left join certter         on certter.v14_certid      = listacda.v81_certid            ";
      $sSqlDados .= "                 left join termo           on termo.v07_parcel        = certter.v14_parcel          ";
      $sSqlDados .= "           where {$sWhere}                                                                          ";                   
      $sSqlDados .= "       ) as dados                                                                                   ";
      $sSqlDados .= "       left join arrematric on arrematric.k00_numpre = dados.k00_numpre                             ";
      $sSqlDados .= "       left join arreinscr  on arreinscr.k00_numpre  = dados.k00_numpre                             ";
      $sSqlDados .= " where exists ( select 1                                                                            ";
      $sSqlDados .= "                  from arrecad                                                                      ";
      $sSqlDados .= "                 where arrecad.k00_numpre = dados.k00_numpre                                        ";
      $sSqlDados .= "                   and ( case                                                                       ";
      $sSqlDados .= "                           when dados.k00_numpar <> 0                                               ";
      $sSqlDados .= "                             then arrecad.k00_numpar = dados.k00_numpar                             ";
      $sSqlDados .= "                           else true                                                                ";
      $sSqlDados .= "                         end )                                                                      ";
      $sSqlDados .= "              ) group by v50_inicial,                                                               ";
      $sSqlDados .= "                         v50_data,                                                                  ";
      $sSqlDados .= "                         v50_codlocal,                                                              ";
      $sSqlDados .= "                         v13_certid,                                                                ";
      $sSqlDados .= "                         v13_dtemis,                                                                ";
      $sSqlDados .= "                         v03_procedtipo,                                                            ";
      $sSqlDados .= "                         v03_procedtipogrupo,                                                       ";      
      $sSqlDados .= "                         k00_numcgm,                                                                ";
      $sSqlDados .= "                         k00_matric,                                                                ";
      $sSqlDados .= "                         k00_inscr,                                                                 ";
      $sSqlDados .= "                         dtufir,                                                                 ";
      $sSqlDados .= "                         dtinscr                                                                    ";
      $sSqlDados .= " order by v50_inicial,k00_numcgm,v13_certid";

      $rsDados   = $oListaCda->sql_record($sSqlDados);
      $aDados    = db_utils::getColectionByRecord($rsDados, false, false, true);
      
     // echo "<pre>";
     // print_r($aDados);
     // echo "</pre>";
     // die();
      if (count($aDados) == 0) {

        throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.nao_existe_cda')); 
      }

      /**
       * Verifica na parjuridico a configuração para o devedor principal do debito
       */
      $rsParJuridico = $clparjuridico->sql_record($clparjuridico->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit')));
      $oParJuridico  = db_utils::fieldsMemory($rsParJuridico,0);
      if ($oParJuridico->v19_envolprinciptu == "t") {
        $lPrincipal = "true";
      }else{
        $lPrincipal = "false";
      }

      /**
       * Verifica os dados da tabela db_config.
       * Campos: db21_codtj e db21_regracgmiptu
       */
      $rsDbConfig = $oDbConfig->sql_record($oDbConfig->sql_query_file(null, "db21_codtj, cgc, db21_regracgmiptu",null,"codigo = ".db_getsession("DB_instit")));
      $aDbConfig  = db_utils::fieldsMemory($rsDbConfig,0);
      if (count($aDbConfig) == 0) {
        throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.instituicao_sem_configuracao')); 
      }

      /**
       * 
       * Neste foreach buscamos os dados das dividas das certidões de débitos vinculados a listacda
       * As origens são as dividas da CDA, sendo a CDA de divida ou de parcelamento.
       * 
       * No caso de parcelamento são buscadas as divida que compõe o parcelamento.
       * 
       * Já definimos a menor inicial e a maior que estará no arquivo
       */
      $iMenorInicial      = null;
      $iMaiorInicial      = null;
      $dDataCitacao       = null;

      $aIniciais          = array();
      $aCertidoes         = array();
      $aExecutados        = array();
      $iQuantDividaAvulsa = 0;

      foreach ($aDados as $oDados) {

        /**
         * Verificamos o valor do indice UFIR
         */
        //$sDtCalculoUfir = 
        //$sSqlVlrInfla = "select fc_vlinf from fc_vlinf('UFIR','{$oDados->dtinscr}');";
        $sSqlVlrInfla = "select fc_vlinf from fc_vlinf('UFIR','{$oDados->dtufir}');";
        $rsVlrInfla   = db_query($sSqlVlrInfla);
        $nVlrInfla    = db_utils::fieldsMemory($rsVlrInfla,0)->fc_vlinf;
        
        /**
         * Verificamos as informações do Ajuízamento
         */
        if(!in_array($oDados->v13_certid,$aCertidoes)) {
          $aCertidoes[] = $oDados->v13_certid;
        }

        if ( $iMenorInicial > $oDados->v50_inicial || empty($iMenorInicial) ) {
          //$iMenorInicial = $oDados->v50_inicial."/".$oDados->ano_inicial;
          $iMenorInicial = $oDados->v50_inicial;
        }

        if ($iMaiorInicial < $oDados->v50_inicial) {
          $iMaiorInicial = $oDados->v50_inicial;
        }

        if ( $dDataCitacao > $oDados->v50_data || empty($dDataCitacao) ) {
          $dDataCitacao = $oDados->v50_data;
        }
        /*
         * FIM DA VERIFICACAO DO AJUIZAMENTO

         * Buscamos os dados do executado
         * Busca dos dados de acordo com a matricula do imóvel
         */
        if ( $oDados->k00_matric > 0 ) {

          $sSqlEnvolvidos = "select  * from fc_busca_envolvidos({$lPrincipal},{$oParJuridico->v19_envolinicialiptu},'M',{$oDados->k00_matric})";
          $rsEnvolvidos   = db_query($sSqlEnvolvidos);
          if (!$rsEnvolvidos) {
            throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.erro_um_buscar_dados'));
          }

          if ($oParJuridico->v19_envolinicialiptu == 2 && pg_num_rows($rsEnvolvidos) == 0 ) {

            $sSqlEnvolvidos  = " select j01_numcgm as rinumcgm ";
            $sSqlEnvolvidos .= "   from iptubase               ";
            $sSqlEnvolvidos .= "  where j01_matric = {$oDados->k00_matric} ";

            $rsEnvolvidos = db_query($sSqlEnvolvidos);
            if (!$rsEnvolvidos) {
              throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.erro_dois_buscar_dados'));
            }
          }

          $oEnvolvidos  = db_utils::fieldsMemory($rsEnvolvidos,0);
          $rsDadosEnvol = $clcgm->sql_record($clcgm->sql_query_file($oEnvolvidos->rinumcgm));
          $oDadosEnvol  = db_utils::fieldsMemory($rsDadosEnvol,0);

          /**
           * Buscamos os dados do executado de acordo com a inscrição
           */
        } else if ($oDados->k00_inscr > 0) {

          $sSqlEnvolvidos = "select * from fc_busca_envolvidos({$lPrincipal},{$oParJuridico->v19_envolinicialiss},'I',{$oDados->k00_inscr})";
          $rsEnvolvidos   = db_query($sSqlEnvolvidos);
          if (!$rsEnvolvidos) {
            throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.erro_tres_buscar_dados'));
          }
          $oEnvolvidos  = db_utils::fieldsMemory($rsEnvolvidos,0);
          $rsDadosEnvol = $clcgm->sql_record($clcgm->sql_query_file($oEnvolvidos->rinumcgm));
          $oDadosEnvol = db_utils::fieldsMemory($rsDadosEnvol,0);

          /**
           * Buscamos os dados do executado de acordo com o cgm
           */
        } else {

          $sSqlEnvolvidos  = " select distinct cgm.*                                     ";
          $sSqlEnvolvidos .= "   from certdiv                                            ";
          $sSqlEnvolvidos .= "        inner join divida     on v14_coddiv = v01_coddiv   ";
          $sSqlEnvolvidos .= "        inner join arrenumcgm   on v01_numpre = k00_numpre ";
          $sSqlEnvolvidos .= "        inner join cgm          on z01_numcgm = k00_numcgm ";
          $sSqlEnvolvidos .= "  where v14_certid = $oDados->v13_certid                   ";
          $sSqlEnvolvidos .= "  union                                                    ";
          $sSqlEnvolvidos .= " select distinct cgm.*                                     ";
          $sSqlEnvolvidos .= "   from certter                                            ";
          $sSqlEnvolvidos .= "        inner join termo        on v14_parcel = v07_parcel ";
          $sSqlEnvolvidos .= "        inner join arrenumcgm   on v07_numpre = k00_numpre ";
          $sSqlEnvolvidos .= "        inner join cgm          on z01_numcgm = k00_numcgm ";
          $sSqlEnvolvidos .= "  where v14_certid = $oDados->v13_certid                   ";

          $rsEnvolvidos = db_query($sSqlEnvolvidos);
          if(!$rsEnvolvidos) {
            throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.erro_quatro_buscar_dados'));
          }
          $oDadosEnvol  = db_utils::fieldsMemory($rsEnvolvidos,0);

        }

        if (!in_array($oDadosEnvol->z01_numcgm,$aExecutados)) {
          $aExecutados[] = $oDadosEnvol->z01_numcgm;
        }

        /*
         * FIM DA BUSCA DOS DADOS DO EXECDUTADO
         */

        /**
         * 
         * Buscamos o codigo do municipio
         * na tabela, cadmunictj, baseado na descrição 
         * $oDadosEnvol->z01_munic
         * 
         */
        $iCodMunic = "";
        $sMunic       = str_replace("'","",trim(strtoupper(db_translate($oDadosEnvol->z01_munic))));
        $sSqlCodMunic = "select v85_codcidade from cadmunictj where trim(to_ascii(fc_remove_acentos(v85_munic))) = '{$sMunic}'";
        $rsCodMunic   = db_query($sSqlCodMunic) ;
        $iLinhasMunic = pg_num_rows($rsCodMunic);
        if ($iLinhasMunic > 0) {
          $iCodMunic    = db_utils::fieldsMemory($rsCodMunic,0)->v85_codcidade;
        }
        
        /**
         * Verificamos o código do logradouro a ser utilizado 
         */
        $oDaoCadEnderLocal = db_utils::getDao("cadenderlocal");
        $rsCadEnder = $oDaoCadEnderLocal->sql_record($oDaoCadEnderLocal->sql_query_cgmendereco(null,"db85_ruastipo",null,"z07_numcgm = {$oDadosEnvol->z01_numcgm}"));
        if($oDaoCadEnderLocal->numrows > 0) {
          $iCodigoLogradouro = db_utils::fieldsMemory($rsCadEnder,0)->db85_ruastipo;
        } else {
          $iCodigoLogradouro = "";  
        }
        

        /**
         * Verificamos se trata-se de CPF ou CNPJ
         */
        if (strlen($oDadosEnvol->z01_cgccpf) > 11) {
          
          $sCGC = $oDadosEnvol->z01_cgccpf;
          $sCPF = "";
          /*
           * Iremos verificar se é valido o cgc
           */
           if (!validaCNPJ("{$sCGC}")) {
             $sCGC = "";
           }          
          
          
        } else {
          
          $sCPF = $oDadosEnvol->z01_cgccpf;
          $sCGC = "";
          /*
           * verificamos se é valido o cpf
           */
           if (!validaCPF("{$sCPF}")) {
             $sCPF = "";
           }          
          
        }
        
        /**
         * Buscamos os dados ref a Linha 5 - Divida Avulsa
         * São auto de infrações lançados para as dividas da CDA
         */
        if ($oDados->v03_procedtipogrupo == 4 ) {
          $sSqlAuto = "select y50_numbloco                 as auto_infracao, 
                              to_char(y50_data,'DDMMYYYY') as data_infracao,
                              descrdepto                   as orgao_aplicou_multa,
                              y50_obs                      as descricao_infracao
                           from certdiv
                           inner join divold        on certdiv.v14_coddiv        = divold.k10_coddiv
                           inner join autonumpre    on autonumpre.y17_numpre     = divold.k10_numpre
                           inner join auto          on autonumpre.y17_codauto    = auto.y50_codauto
                           inner join db_depart     on db_depart.coddepto        = auto.y50_setor
                           inner join tipofiscaliza on tipofiscaliza.y27_codtipo = auto.y50_codtipo  
                           where certdiv.v14_certid = {$oDados->v13_certid}";
          $rsAuto = db_query($sSqlAuto);
          $iLinhasAuto = pg_num_rows($rsAuto); 
          if ($iLinhasAuto > 0) {
            $oDadosAuto = db_utils::getColectionByRecord($rsAuto);
          }
        }
        /**
         * 
         * Verificamos se a Inicial ja foi processada
         * Caso já tenha sido processada somente gera as linhas 3 e 4 do contrário gera a linha
         *  
         */
         $nValorTotalInicial = fc_valorInicial($oDados->v50_inicial);
         $nValorTotalCertid  = fc_valorCertid($oDados->v13_certid, $oDados->v13_dtemis);
          
         $oDados->v50_inicial = $oDados->v50_inicial."/".$oDados->ano_inicial;
         
        if(!in_array($oDados->v50_inicial,$aLinhas2Processadas)) {
          $nValorUFIR = validaUFIR($oDados->dtufir);	
          //Linha 2
          $aIniciais[] = $oDados->v50_inicial;                  
          $oDadosLinha2                     = new stdClass();
          $oDadosLinha2->ident              = "2";
          $oDadosLinha2->fixo_2             = "2";
          $oDadosLinha2->codigo_processo    = str_pad($oDados->v50_inicial , 14, " ", STR_PAD_LEFT);
          $oDadosLinha2->data_protocolo     = str_pad($oDados->v50_data    ,  8, " ", STR_PAD_LEFT);
          $oDadosLinha2->vara               = str_pad(""                   ,  6, " ", STR_PAD_LEFT);  // campo para retorno
          $oDadosLinha2->data_distribuicao  = str_pad(""                   ,  8, " ", STR_PAD_LEFT);  // campo para retorno
          $oDadosLinha2->valor_total_causa  = str_pad(str_replace(".","",trim(db_formatar($nValorTotalInicial,"f"))), 15, " ", STR_PAD_LEFT);
          $oDadosLinha2->moeda              = "1";
          $oDadosLinha2->quantidade_ufir    = str_pad(str_replace(".","",db_formatar(($nValorTotalInicial / $nValorUFIR),"f",0,4,"e",4)) , 16, " ", STR_PAD_LEFT);
          $oDadosLinha2->numero_processo_tj = str_pad(" " , 20, " ", STR_PAD_LEFT);  // campo para retorno
          $oDadosLinha2->cartorio           = str_pad(" " ,  6, " ", STR_PAD_LEFT);  // campo para retorno
          $oDadosLinha2->mensagem           = str_pad(" " ,500, " ", STR_PAD_LEFT);

          //echo $oDadosLinha2->fixo_2.$oDadosLinha2->codigo_processo.$oDadosLinha2->data_protocolo.$oDadosLinha2->vara.$oDadosLinha2->data_distribuicao.$oDadosLinha2->valor_total_causa.$oDadosLinha2->moeda.$oDadosLinha2->quantidade_ufir.$oDadosLinha2->numero_processo_tj.$oDadosLinha2->cartorio.$oDadosLinha2->mensagem;
                    
          $aLinhas2Processadas[] = $oDados->v50_inicial; 
          $aDadosProcessar[] = $oDadosLinha2;
          
        } 

        if(!in_array("{$oDados->v50_inicial}-{$oDados->k00_numcgm}",$aLinhas3Processadas)) {
        	
          
          $sDataNasc = "";
          if (!empty($oDadosEnvol->z01_nasc)) {
            $aDataNasc    = split("-",$oDadosEnvol->z01_nasc);
            $sDataNasc    = $aDataNasc[2].$aDataNasc[1].$aDataNasc[0];
          }
                    
          //Linha 3
          $oDadosLinha3                           = new stdClass();
          $oDadosLinha3->ident                    = "3";
          $oDadosLinha3->fixo_3                   = "3";
          $oDadosLinha3->codigo_processo          = str_pad($oDados->v50_inicial                                                , 14, " ", STR_PAD_LEFT);
          $oDadosLinha3->nome_executado           = str_pad($oDadosEnvol->z01_nome                                              , 90, " ", STR_PAD_RIGHT);
          $oDadosLinha3->cpf                      = str_pad(substr(ereg_replace("[./-]","",trim($sCPF)),0,11)                   , 11, " ", STR_PAD_LEFT);
          $oDadosLinha3->cgc                      = str_pad(substr(ereg_replace("[./-]","",trim($sCGC)),0,14)                   , 14, " ", STR_PAD_LEFT);
          $oDadosLinha3->rg                       = str_pad(substr(ereg_replace("[./-]","",trim($oDadosEnvol->z01_ident)),0,12) , 12, " ", STR_PAD_LEFT);
          $oDadosLinha3->orgao_expeditor_rg       = str_pad(substr(trim($oDadosEnvol->z01_identorgao),0,12)                     , 12, " ", STR_PAD_LEFT);
          $oDadosLinha3->nome_pai                 = str_pad(substr(trim($oDadosEnvol->z01_pai),0,40)                            , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha3->nome_mae                 = str_pad(substr(trim($oDadosEnvol->z01_mae),0,40)                            , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha3->data_nascimento          = str_pad($sDataNasc                                                          ,  8, " ", STR_PAD_LEFT);
          $oDadosLinha3->naturalidade             = str_pad(substr($oDadosEnvol->z01_naturalidade,0,2)                          ,  2, " ", STR_PAD_RIGHT);
          $oDadosLinha3->codigo_logradouro        = str_pad($iCodigoLogradouro                                                  ,  2, " ", STR_PAD_LEFT);
          $oDadosLinha3->logradouro               = str_pad(substr($oDadosEnvol->z01_ender,0,60)                                , 60, " ", STR_PAD_RIGHT);
          $oDadosLinha3->numero                   = str_pad(substr($oDadosEnvol->z01_numero,0,10)                               , 10, " ", STR_PAD_LEFT);
          $oDadosLinha3->complemento              = str_pad(substr($oDadosEnvol->z01_compl,0,40)                                , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha3->bairro                   = str_pad(substr($oDadosEnvol->z01_bairro,0,40)                               , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha3->cidade                   = str_pad($iCodMunic                                                          ,  5, " ", STR_PAD_RIGHT);
          $oDadosLinha3->cep                      = str_pad(substr($oDadosEnvol->z01_cep,0,8)                                   ,  8, " ", STR_PAD_RIGHT);
          $oDadosLinha3->uf                       = str_pad(substr($oDadosEnvol->z01_uf,0,2)                                    ,  2, " ", STR_PAD_RIGHT);
          $oDadosLinha3->mensagem                 = str_pad(" "                                                                 ,500, " ", STR_PAD_RIGHT);//.chr(10).chr(13);
          
          $aLinhas3Processadas[] = $oDados->v50_inicial."-".$oDados->k00_numcgm;
          $aDadosProcessar[] = $oDadosLinha3;
          
        }

        if(!in_array("{$oDados->v50_inicial}-{$oDados->k00_numcgm}-{$oDados->v13_certid}",$aLinhas4Processadas)) {
        	
          //Linha 4
          $oDadosLinha4                               = new stdClass();
          $oDadosLinha4->ident                        = "4";
          $oDadosLinha4->fixo_4                       = "4";
          $oDadosLinha4->codigo_processo              = str_pad($oDados->v50_inicial                                             , 14," ", STR_PAD_LEFT);
          $oDadosLinha4->numero_certidao_divida_ativa = str_pad($oDados->v13_certid                                              , 13," ", STR_PAD_LEFT);
          $oDadosLinha4->ano_exercicio                = str_pad($oDados->exerc                                                   ,  4," ", STR_PAD_LEFT);
          $oDadosLinha4->valor_certidao_inicial       = str_pad(str_replace(".","",db_formatar($nValorTotalCertid,"f"))          , 15," ", STR_PAD_LEFT);
          $oDadosLinha4->moeda                        = "1";
          $oDadosLinha4->quantidade_ufir              = str_pad(str_replace(".","",substr(db_formatar($nValorTotalCertid/$nValorUFIR,"f",0,4,"e",4),0,16)), 16 ," ", STR_PAD_LEFT);
          $oDadosLinha4->natureza_divida              = str_pad($oDados->v03_procedtipo                                          , 2 , " ", STR_PAD_LEFT); // verificar com natureza da divida 
          $oDadosLinha4->inscricao_imovel             = str_pad($oDados->k00_matric                                              , 20, " ", STR_PAD_LEFT);
          $oDadosLinha4->nome_devedor                 = str_pad(substr($oDadosEnvol->z01_nome,0,90)                              , 90, " ", STR_PAD_RIGHT);
          $oDadosLinha4->codigo_tipo_logradouro       = str_pad($iCodigoLogradouro                                               ,  2, " ", STR_PAD_LEFT);
          $oDadosLinha4->logradouro                   = str_pad(substr($oDadosEnvol->z01_ender,0,60)                             , 60, " ", STR_PAD_RIGHT);
          $oDadosLinha4->numero                       = str_pad(substr($oDadosEnvol->z01_numero,0,10)                            , 10, " ", STR_PAD_LEFT);
          $oDadosLinha4->complemento                  = str_pad(substr($oDadosEnvol->z01_compl,0,40)                             , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha4->bairro                       = str_pad(substr($oDadosEnvol->z01_bairro,0,40)                            , 40, " ", STR_PAD_RIGHT);
          $oDadosLinha4->codigo_cidade                = str_pad($iCodMunic                                                       ,  5, " ", STR_PAD_LEFT);
          $oDadosLinha4->cep                          = str_pad(substr($oDadosEnvol->z01_cep,0,8)                                ,  8, " ", STR_PAD_LEFT);
          $oDadosLinha4->uf                           = str_pad(substr($oDadosEnvol->z01_uf,0,2)                                 ,  2, " ", STR_PAD_RIGHT);
          $oDadosLinha4->mensagem                     = str_pad(""                                                               ,500, " ", STR_PAD_RIGHT);//.chr(10).chr(13);
          $aLinhas4Processadas[] = $oDados->v50_inicial."-".$oDados->k00_numcgm."-".$oDados->v13_certid;
          $aDadosProcessar[] = $oDadosLinha4;
          
        }
        
        if ($oDados->v03_procedtipogrupo == 4 && $iLinhasAuto > 0) {
          
        	if(!in_array("{$oDados->v50_inicial}-{$oDados->v13_certid}-{$oDadosAuto[0]->auto_infracao}",$aLinhas5Processadas)) {   
	          //Linha 5       
	          $oDadosLinha5                               = new stdClass();
	          $oDadosLinha5->ident                        = "5";
	          $oDadosLinha5->fixo_5                       = "5";
	          $oDadosLinha5->codigo_processo              = str_pad($oDados->v50_inicial                              ,   14, " ", STR_PAD_LEFT);
	          $oDadosLinha5->numero_certidao_divida_ativa = str_pad($oDados->v13_certid                               ,   13, " ", STR_PAD_LEFT);
	          $oDadosLinha5->auto_infracao                = str_pad($oDadosAuto[0]->auto_infracao                     ,   20, " ", STR_PAD_LEFT);
	          $oDadosLinha5->data_infracao                = str_pad($oDadosAuto[0]->data_infracao                     ,    8, " ", STR_PAD_LEFT);
	          $oDadosLinha5->orgao_aplicou_multa          = str_pad(substr($oDadosAuto[0]->orgao_aplicou_multa,0,20)  ,   20, " ", STR_PAD_LEFT);
	          $oDadosLinha5->descricao_infracao           = str_pad(substr($oDadosAuto[0]->descricao_infracao,0,1000) , 1000, " ", STR_PAD_RIGHT);
	          $oDadosLinha5->mensagem                     = str_pad(" "                                               ,  500, " ", STR_PAD_RIGHT);//.chr(10).chr(13);
	          
	          $aLinhas5Processadas[] = $oDados->v50_inicial."-".$oDados->v13_certid."-".$oDadosAuto[0]->auto_infracao;
	          $aDadosProcessar[] = $oDadosLinha5;
	          
	          $iQuantDividaAvulsa++;
	        }
        }   

      }
        // buscamos o proximo sequencial da tabela certidarqremessa para seguir montando o nome do txt
        $sSqlNextCertidarqremessa = "select nextval('certidarqremessa_v83_sequencial_seq') as nextval";
        $rsNextCertidarqremessa   = db_query($sSqlNextCertidarqremessa);
        $iNextCertidarqremessa    = db_utils::fieldsMemory($rsNextCertidarqremessa, 0)->nextval;
        $sNomeTxt                 = 'execfiscal'.$aDbConfig->db21_codtj.'_'.$iNextCertidarqremessa.".txt";      
        $pArquivoTxt              = "tmp/".$sNomeTxt;

        $oLayoutTxt               = new db_layouttxt($iCodLayOut, $pArquivoTxt, null, 1, true); 

        /**
         * Declaramos para a classe de geração do arquivo os dados referentes a primeira linha do arquivo
         * LINHA 1 - Header
         */    
        $oDadosLinha1                           = new stdClass();
        $oDadosLinha1->fixo_1                   = 1;
        $oDadosLinha1->codigo_prefeitura        = str_pad($aDbConfig->db21_codtj,                   5, " ", STR_PAD_LEFT);
        $oDadosLinha1->codigo_processo_inicial  = str_pad($iMenorInicial."/".$oDados->ano_inicial, 14, " ", STR_PAD_LEFT);
        $oDadosLinha1->codigo_processo_final    = str_pad($iMaiorInicial."/".$oDados->ano_final,   14, " ", STR_PAD_LEFT);
        $oDadosLinha1->data_citacao             = str_pad($dDataCitacao,                            8, " ", STR_PAD_LEFT);
        $oDadosLinha1->quantidade_processos     = str_pad(count($aIniciais),                       10, " ", STR_PAD_LEFT);
        $oDadosLinha1->quantidade_executados    = str_pad(count($aExecutados),                     10, " ", STR_PAD_LEFT);
        $oDadosLinha1->quantidade_certidoes     = str_pad(count($aCertidoes),                      10, " ", STR_PAD_LEFT);
        $oDadosLinha1->quantidade_divida_avulsa = str_pad($iQuantDividaAvulsa,                     10, " ", STR_PAD_LEFT);
        $oDadosLinha1->cnpj_prefeitura          = str_pad($aDbConfig->cgc,                         14, " ", STR_PAD_LEFT);
        $oDadosLinha1->mensagem                 = str_pad("",                                     500, " ", STR_PAD_RIGHT);

        if( $oLayoutTxt->setByLineOfDBUtils($oDadosLinha1, 1, "1") == false ) {
          throw new Exception (_M('tributario.juridico.jur4_certidarqremessa.erro_ao_gerar_primeira_linha'));
        }
        /**
         * Montamos as informações do arquivo com as linhas 1, 2, 3 e 4
         */
        foreach ($aDadosProcessar as $oIndiceProcessar => $oValorProcessar) {
         if( $oLayoutTxt->setByLineOfDBUtils($oValorProcessar,3, $oValorProcessar->ident) == false ) { 
           throw new Exception (_M('tributario.juridico.jur4_certidarqremessa.erro_ao_gerar_arquivo'));
         } 
        } 
        
	        $oCertidArqremessa->v83_sequencial = $iNextCertidarqremessa;
	        $oCertidArqremessa->v83_nomearq    = $sNomeTxt;
	        $oCertidArqremessa->v83_lista      = $iLista;
	        $oCertidArqremessa->v83_dtgeracao  = $sDataGeracao;
	        $oCertidArqremessa->incluir($oCertidArqremessa->v83_sequencial);
          if ( $oCertidArqremessa->erro_status == "0") {
          	throw new Exception(_M('tributario.juridico.jur4_certidarqremessa.erro_ao_incluir_arquivo'));
          }

          db_fim_transacao(false);
          
      } catch (Exception $eException) {
      	
      	db_fim_transacao(true);
        $oRetorno->status      = 0;
        $oRetorno->message     = urlencode($eException->getMessage());
      }

      $oRetorno->dados    = $pArquivoTxt;
      break; 
      
//=========================================== verificamos arquivos nao processados ===================================//
      case  "naoprocessados" :

        // buscamos arquivos da certidarqremessa e que nao existam na certidarqretorno

	      $iSequencial          = $oParam->iSequencial;
	      $iCodLista            = $oParam->iCodLista;
	      $sNomeArq             = $oParam->sNomeAqruivo;
	      $aListaNaoProcessados = array();

        $sWhere = " v83_sequencial not in (select v84_certidarqremessa from certidarqretorno ) ";

        if (!empty($iSequencial)) {
	      	$sWhere .= " and v83_sequencial = {$iSequencial}";
	      }
	      if (!empty($iCodLista)) {
	      	$sWhere .= " and v83_lista        =  {$iCodLista}";
	      }
        if (!empty($sNomeArq)) {
          $sWhere .= " and v83_nomearq  ilike '%$sNomeArq%'";
        }
        
        $sSqlNaoProcessados  = "select v83_sequencial, v83_nomearq, v83_dtgeracao                                  ";
	      $sSqlNaoProcessados .= "  from certidarqremessa                                                           ";
	      $sSqlNaoProcessados .= " where $sWhere ";
	       
	      $rsNaoProcessados    = $oCertidArqremessa->sql_record($sSqlNaoProcessados);
	      $aNaoProcessados     = db_utils::getColectionByRecord($rsNaoProcessados, false, false, true);
	      foreach ($aNaoProcessados as $oDadosNaoProcessados) {
	      	
          $oDados                 = new stdClass(); 
          $oDados->v83_sequencial = $oDadosNaoProcessados->v83_sequencial;
          $oDados->v83_nomearq    = $oDadosNaoProcessados->v83_nomearq;
          $oDados->v83_dtgeracao  = db_formatar($oDadosNaoProcessados->v83_dtgeracao, "d");
          $aListaNaoProcessados[] = $oDados;	      	
	      }
        $oRetorno->dados    = $aListaNaoProcessados;
      break;  


      

//================================================== verificamos arquivos processados ===============================///
      
      case  "processados" :

        $oCertidarqRetorno   = new cl_certidarqretorno;
      	$iCodRemessa         = $oParam->iCodRemessa;
	      $iCodLista           = $oParam->iCodLista;
	      $sNomeRemessa        = $oParam->sNomeRemessa;
	      $sDataRemessa        = implode("-", array_reverse(explode("/",$oParam->sDataRemessa)));
	      $sNomeRetorno        = $oParam->sNomeRetorno;
	      $sDataRetorno        = $oParam->sDataRetorno;
	      $sDataProcessamento  = implode("-", array_reverse(explode("/",$oParam->sDataProcessamento)));
	      $iMenorCda           = 0;
	      $iMaiorCda           = 0;
	      $aListaProcessados   = array();
	      $sWereProcessados    = "1 = 1";
	      $sCamposProcessados  = " v83_sequencial, v83_lista, v83_dtgeracao, v83_nomearq,";
	      $sCamposProcessados .= " v84_sequencial, v84_nomearq, v84_dtarquivo, v84_dtprocessamento ";
	      
        if (!empty($iCodRemessa)) {
        		      
          $sWereProcessados .= " and   certidarqremessa.v83_sequencial = {$iCodRemessa}     ";
        }
        if (!empty($iCodLista)){
        	
  	      $sWereProcessados .= " and certidarqremessa.v83_lista = {$iCodLista}              ";
        }
        if (!empty($sDataRemessa)){
        	
  	      $sWereProcessados .= " and certidarqremessa.v83_dtgeracao = '{$sDataRemessa}'     ";
        }
	      if (!empty($sNomeRemessa)){
	      	
	        $sWereProcessados .= " and certidarqremessa.v83_nomearq ilike '%{$sNomeRemessa}%' ";	      	
	      }
	      if (!empty($sNomeRetorno)){
	      	
  	      $sWereProcessados .= " and certidarqretorno.v84_nomearq ilike '%{$sNomeRetorno}%' ";
	      }
	      if (!empty($sDataRetorno)){
	      	
	        $sWereProcessados .= " and certidarqretorno.v84_dtarquivo = '{$sDataRetorno}'     ";
	      }
	      if (!empty($sDataProcessamento)){
	      	
  	      $sWereProcessados .= " and certidarqretorno.v84_dtprocessamento = '{$sDataProcessamento}' ";
	      }
	      
	      $sSqlProcessados     = $oCertidarqRetorno->sql_query(null,"{$sCamposProcessados}", null, "{$sWereProcessados}");
	      $rsProcessados       = $oCertidarqRetorno->sql_record($sSqlProcessados);
	      $aProcessados        = db_utils::getColectionByRecord($rsProcessados, false, false, true);
	      foreach ($aProcessados as $oDadosProcessados) {
	      	
          $oDados                 = new stdClass(); 
          
          // dados remessa
          $oDados->v83_nomearq    = $oDadosProcessados->v83_nomearq;
          $oDados->v83_dtgeracao  = db_formatar($oDadosProcessados->v83_dtgeracao, "d");
          
          // dados arq retorno    
          $oDados->v84_sequencial = $oDadosProcessados->v84_sequencial;
          $oDados->v84_nomearq    = $oDadosProcessados->v84_nomearq;
          $oDados->v84_dtarquivo  = trim(urldecode(db_formatar($oDadosProcessados->v84_dtarquivo, "d")));
          
          /**
	         * SQL para retornar a menor e  a maior CDA da listacda
	         */
	        $sSqlDadosCda         = $oListaCda->sql_query_file(null, "min(v81_certid) as menorcda, max(v81_certid) as maiorcda",null, "v81_lista = {$oDadosProcessados->v83_lista} ");
	        $rsDadosCda           = $oListaCda->sql_record($sSqlDadosCda);
	        $oDadosCda            = db_utils::fieldsMemory($rsDadosCda,0);
	        $oDados->iCdaInicial  = $oDadosCda->menorcda;
	        $oDados->iCdaFinal    = $oDadosCda->maiorcda;
          
          
          // dados INICIAL
          $sSqlDadosInicial = "select min(v51_inicial) as inicialini, 
                                      max(v51_inicial) as inicialfim 
                                 from inicialcert 
                                inner join listacda on listacda.v81_certid = inicialcert.v51_certidao
                                where listacda.v81_lista = {$oDadosProcessados->v83_lista} ";
          $rsDadosInicial      = $oInicialcert->sql_record($sSqlDadosInicial);
          $oDadosInicial       = db_utils::fieldsMemory($rsDadosInicial,0);
          $oDados->iInicialIni = $oDadosInicial->inicialini;
          $oDados->iInicialFim = $oDadosInicial->inicialfim;
          
          $aListaProcessados[] = $oDados;	      	
	      	
	      }
	      $oRetorno->dados    = $aListaProcessados;
	      break;  
    }

    echo $oJson->encode($oRetorno);   
?>