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

require_once("libs/db_stdlib.php");
require_once("std/db_stdClass.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_contranslr_classe.php");
require_once("classes/db_conplano_classe.php");
require_once("classes/db_conplanoexe_classe.php");
require_once("classes/db_conplanosis_classe.php");
require_once("classes/db_conplanoconta_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("classes/db_orcfontes_classe.php");
require_once("classes/db_orcelemento_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_conplano.php");
require_once("classes/db_conparametro_classe.php");
require_once("classes/db_db_config_classe.php");
require_once("classes/db_conplanogrupo_classe.php");
require_once("classes/db_conplanocontabancaria_classe.php");
require_once("classes/db_conplanoref_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clestrutura_sistema     = new cl_estrutura_sistema;
$clorcfontes             = new cl_orcfontes;
$clorcelemento           = new cl_orcelemento;
$clconparametro          = new cl_conparametro;
$clconplanoreduz         = new cl_conplanoreduz;
$clconplanoconta         = new cl_conplanoconta;
$clconplanosis           = new cl_conplanosis;
$clconplano              = new cl_conplano;
$clconplanoexe           = new cl_conplanoexe;
$cldb_config             = new cl_db_config;
$clconplanoref           = new cl_conplanoref;
$db_conplano             = new db_conplano;
$clconplanogrupo         = new cl_conplanogrupo;
$clconplanocontabancaria = new cl_conplanocontabancaria;

$db_opcao = 1;
$db_botao = true;
$anousu   = db_getsession("DB_anousu");

//////////////////////////

  $sSqlConParametro = " select c90_utilcontabancaria from conparametro";
  $rsConParametro   = db_query($sSqlConParametro);
  $oConParametro    = db_utils::fieldsMemory($rsConParametro,0);

  if ( $oConParametro->c90_utilcontabancaria == 't' ) {
    $lContaBancaria = true;
  } else {
    $lContaBancaria = false;
  }

/////////////////////////


if (isset($importar) && $importar == true) {

  $sqlerro = false;
  db_inicio_transacao();

  $codigo  = str_replace(".", "", $c90_estrutcontabil);
  $sSqlImporta = $clconplano->sql_query_file("", "",
                                     "c60_codcon,
                                      c60_anousu as anousu_ant,
                                      c60_descr,
                                      c60_finali,
                                      c60_codsis,c60_codcla",
                                      "c60_anousu desc limit 1",
                                     "c60_estrut='{$codigo}'");
  $result  = $clconplano->sql_record($sSqlImporta);

  $numrows = $clconplano->numrows;

  $sSqlMaxConplano  = "select max(c60_anousu) as c60_anousu from conplano";
  $rsSqlMaxConplano = $clconplano->sql_record($sSqlMaxConplano);
  $iNumRowsAno      = $clconplano->numrows;
  $iMaxAno          = db_getsession("DB_anousu");
  if ($iNumRowsAno > 0) {

    $oAno    = db_utils::fieldsmemory($rsSqlMaxConplano, 0);
    $iMaxAno = $oAno->c60_anousu;

  }
  if ($numrows > 0) {


    db_fieldsmemory($result, 0);
    $c60_codcon = $c60_codcon;
    /**
     * Pegamos  o ultimo ano, e incluimos/alteramos as informações
     */
    for ($iAno = $anousu; $iAno <= $iMaxAno; $iAno++) {

      $clconplano->c60_anousu                  = $iAno;
      $clconplano->c60_estrut                  = $codigo;
      $clconplano->c60_descr                   = $c60_descr;
      $clconplano->c60_finali                  = $c60_finali;
      $clconplano->c60_codsis                  = $c60_codsis;
      $clconplano->c60_codcla                  = $c60_codcla;
      $clconplano->c60_consistemaconta         = "0";
      $clconplano->c60_identificadorfinanceiro = "N";
      $clconplano->c60_naturezasaldo           = "3";

      /**
       * Verificamos se a conta já está cadastrada no plano de contas no ano
       */
      $sWhere                 = "c60_estrut ='{$codigo}' and c60_anousu = {$iAno}";
      $sSqlVerificaConplano   = $clconplano->sql_query_file(null, null,"*", null, $sWhere);
      $rsVerificaConplano     = $clconplano->sql_record($sSqlVerificaConplano);
      if ($clconplano->numrows > 0) {

        $oContaVerificada = db_utils::fieldsMemory($rsVerificaConplano, 0);
        /**
         * Verificamos se os codcons sao iguais,
         * caso forem diferentes, abortamos a inclusão e informamos o usuário
         */
        if ($c60_codcon != $oContaVerificada->c60_codcon) {

          $sqlerro   = true;
          $erro_msg  = "a Conta {$c60_codcon} - {$oContaVerificada->c60_codcon} {$oContaVerificada->c60_estrut} está cadastrada em {$iAno} possui codigo de conta diferente da conta ";
          $erro_msg .= "usada como base da importação.\\nA importação será cancelada";
          break;

        } else {

          $clconplano->c60_codcon = $c60_codcon;
          $clconplano->alterar($c60_codcon, $iAno);

        }
      } else {

        $clconplano->c60_codcon = $c60_codcon;
        $clconplano->incluir($c60_codcon, $iAno);

      }

      if ($clconplano->erro_status == 0) {

        $erro_msg = $clconplano->erro_msg;
        $sqlerro = true;
      }

      if ( $lContaBancaria ) {

	      $sWhereConplanoContaBancaria  = "    c56_codcon = {$c60_codcon} ";
	      $sWhereConplanoContaBancaria .= "and c56_anousu = {$anousu_ant} ";
	      $sSqlConplanoContaBancaria    = $clconplanocontabancaria->sql_query_file(null,"*",null,$sWhereConplanoContaBancaria);
	      $rsConplanoContaBancaria      = $clconplanocontabancaria->sql_record($sSqlConplanoContaBancaria);

	      if ( $clconplanocontabancaria->numrows > 0 ) {

	      	$oConplanoContaBancaria = db_utils::fieldsMemory($rsConplanoContaBancaria,0);

		      $sWhereConplanoContaBancariaAtual  = "    c56_codcon = {$c60_codcon} ";
		      $sWhereConplanoContaBancariaAtual .= "and c56_anousu = {$iAno}       ";
		      $sSqlConplanoContaBancariaAtual    = $clconplanocontabancaria->sql_query_file(null,"*",null,$sWhereConplanoContaBancariaAtual);
		      $rsConplanoContaBancariaAtual      = $clconplanocontabancaria->sql_record($sSqlConplanoContaBancariaAtual);

		      if ( $clconplanocontabancaria->numrows > 0 ) {

		      	$oConplanoContaBancariaAtual = db_utils::fieldsMemory($rsConplanoContaBancariaAtual,0);

		      	$clconplanocontabancaria->c56_sequencial    = $oConplanoContaBancariaAtual->c56_sequencial;
		      	$clconplanocontabancaria->c56_contabancaria = $oConplanoContaBancaria->c56_contabancaria;
	      	  $clconplanocontabancaria->alterar($oConplanoContaBancariaAtual->c56_sequencial);

		      } else {

	          $clconplanocontabancaria->c56_anousu        = $iAno;
	          $clconplanocontabancaria->c56_codcon        = $c60_codcon;
	          $clconplanocontabancaria->c56_contabancaria = $oConplanoContaBancaria->c56_contabancaria;
	          $clconplanocontabancaria->incluir(null);

		      }

		      $erro_msg = $clconplanocontabancaria->erro_msg;
		      if ( $clconplanocontabancaria->erro_status == 0 ) {
		      	$sqlerro = true;
		      }

	      }

      } else {

	      $sSqlConplanoConta = $clconplanoconta->sql_query_file($c60_codcon, $anousu_ant);
	      $rsConplanoConta   = $clconplanoconta->sql_record($sSqlConplanoConta);

	      if ($clconplanoconta->numrows > 0) {

          db_fieldsmemory($rsConplanoConta, 0);
          $clconplanoconta->c63_banco         = $c63_banco;
	        $clconplanoconta->c63_agencia       = $c63_agencia;
	        $clconplanoconta->c63_conta         = $c63_conta;
	        $clconplanoconta->c63_identificador = $c63_identificador;
	        $clconplanoconta->c63_dvagencia     = $c63_dvagencia;
	        $clconplanoconta->c63_dvconta       = $c63_dvconta;
	        $clconplanoconta->c63_codcon = $c60_codcon;
	        $clconplanoconta->c63_anousu = $iAno;
	        $clconplanoconta->c63_codigooperacao = "".str_pad($c63_codigooperacao,4,"0",STR_PAD_LEFT)."";

	        $sSqlVerificaConplanoConta = $clconplanoconta->sql_query_file($c60_codcon, $iAno,"*");
	        $rsVerificaConplanoConta   = $clconplanoconta->sql_record($sSqlVerificaConplanoConta);

	        if ($clconplanoconta->numrows > 0) {
	          $clconplanoconta->alterar($c60_codcon, $iAno);
	        } else {
            $clconplanoconta->incluir($c60_codcon, $iAno);
	        }

	        $erro_msg = $clconplanoconta->erro_msg;
	        if ($clconplanoconta->erro_status == 0) {
	          $sqlerro = true;
	        }
	      }
      }

      if ($sqlerro == false) {

         $arr_tipo = array("orcelemento" => "3", "orcfontes" => array("4","9"));
         if (substr($codigo, 0, 1) == $arr_tipo["orcelemento"]) {

           $clorcelemento->o56_codele   = $c60_codcon;
           $clorcelemento->o56_anousu   = $iAno;
           $clorcelemento->o56_elemento = substr($codigo, 0, 13);
           $clorcelemento->o56_descr    = $c60_descr;
           $clorcelemento->o56_finali   = $c60_finali;
           $clorcelemento->o56_orcado   = 'true';
           /**
            * Verificamos se o elemento já nao existe
            */

           $sSqlVerificaElemento = $clorcelemento->sql_query_file($c60_codcon, $iAno);
           $rsVerificaElemento   = $clorcelemento->sql_record($sSqlVerificaElemento);
           if ($clorcelemento->numrows > 0) {
             $clorcelemento->alterar($c60_codcon, $iAno);
           } else {
             $clorcelemento->incluir($c60_codcon, $iAno);
           }
           if ($clorcelemento->erro_status == 0) {

             $sqlerro  = true;
             $erro_msg = $clorcelemento->erro_msg;

           }

         } else if (in_array(substr($codigo, 0, 1),$arr_tipo["orcfontes"])) {

           $clorcfontes->o57_codfon = $c60_codcon;
           $clorcfontes->o57_anousu = $iAno;
           $clorcfontes->o57_fonte  = $codigo;
           $clorcfontes->o57_descr  = $c60_descr;
           $clorcfontes->o57_finali = $c60_finali;
           /**
            * Verificamos se a fonte de receita já nao existe
            */

           $sSqlVerificaReceita = $clorcfontes->sql_query_file($c60_codcon, $iAno);
           $rsVerificaReceita   = $clorcfontes->sql_record($sSqlVerificaReceita);
           if ($clorcfontes->numrows > 0) {
             $clorcfontes->alterar($c60_codcon, $iAno);
           } else {
             $clorcfontes->incluir($c60_codcon, $iAno);
           }
           if ($clorcfontes->erro_status == 0) {

             $sqlerro = true;
             $erro_msg = $clorcfontes->erro_msg;
          }
        }
      } // fim do 2 sqlerro

      // Importa os grupos de conta se houver
      if ($sqlerro == false) {

        $sSqlConplanoGrupo  = $clconplanogrupo->sql_query_file(null, "c21_congrupo",
                                                                  null,
                                                                  "c21_codcon = $c60_codcon
                                                                  and c21_anousu = $anousu_ant");
        $rsConplanogrupo    = $clconplanogrupo->sql_record($sSqlConplanoGrupo);
        $iTotalGrupos       = $clconplanogrupo->numrows;
        if ($iTotalGrupos > 0) {

          for ($x = 0; $x < $iTotalGrupos; $x++) {

            db_fieldsmemory($res_conplanogrupo,$x);
            $clconplanogrupo->c21_codcon   = $c60_codcon;
            $clconplanogrupo->c21_anousu   = $iAno;
            $clconplanogrupo->c21_congrupo = $c21_congrupo;

            /**
             * Verificamos se o grupo já  foi incluso no ano
             */
            $sWhereConPlanoGrupo       = "c21_codcon = {$c60_codcon} and c21_anousu, {$iAno}";
            $sWhereConPlanoGrupo      .=  "and c21_congrupo = {$c21_congrupo}";

            $sSqlVerificaConplanoGrupo = $clconplanogrupo->sql_query_file(null,"*",null, $sWhereConPlanoGrupo);
            if ($clconplanogrupo->numrows == 0) {

              $clconplanogrupo->incluir(null);
              if ($clconplanogrupo->erro_status == 0){

                $sqlerro = true;
                $erro_msg = $clconplanogrupo->erro_msg;
                break;

              }
            }
          }
        }
      }

// Importa reduzidos se houver
      if ($sqlerro == false) {

        $tipo = "sintetica";
        $sSqlReduzidos = $clconplanoreduz->sql_query_file(null,
                                                          null,
                                                          "c61_reduz,
                                                          c61_instit,
                                                          c61_codigo,
                                                          c61_contrapartida",
                                                          null,
                                                          "c61_codcon     = {$c60_codcon}
                                                           and c61_anousu = {$anousu_ant}"
                                                           );
        $rsReduzidos = $clconplanoreduz->sql_record($sSqlReduzidos);
        if ($clconplanoreduz->numrows > 0) {

          $tipo = "analitica";
          $iTotalConplanoReduz = $clconplanoreduz->numrows;
          for ($iReduz = 0; $iReduz < $iTotalConplanoReduz; $iReduz++) {

            db_fieldsmemory($rsReduzidos, $iReduz);

            /**
             * Verificamos se a conta existe no ano
             */
            $sWhereConPlanoReduz = "c61_reduz = {$c61_reduz} and c61_anousu = {$iAno} and c61_instit = {$c61_instit}";
            $sSqlVerificaReduz   = $clconplanoreduz->sql_query_file(null,
                                                             null,
                                                             "*",
                                                             null,
                                                             $sWhereConPlanoReduz
                                                             );
            $rsVerificaReduz = $clconplanoreduz->sql_record($sSqlVerificaReduz);
            $iNumRows = $clconplanoreduz->numrows;
            if ($clconplanoreduz->numrows == 0) {

              $clconplanoreduz->c61_codcon        = $c60_codcon;
              $clconplanoreduz->c61_reduz         = $c61_reduz;
              $clconplanoreduz->c61_anousu        = $iAno;
              $clconplanoreduz->c61_instit        = $c61_instit;
              $clconplanoreduz->c61_codigo        = $c61_codigo;
              $clconplanoreduz->c61_contrapartida = $c61_contrapartida;


              $clconplanoreduz->incluir($c61_reduz, $iAno);
              if ($clconplanoreduz->erro_status == 0){
                $erro_msg = $clconplanoreduz->erro_msg;
                $sqlerro  = true;
              }

              if ($sqlerro == false){

                $clconplanoexe->c62_anousu = $iAno;
                $clconplanoexe->c62_reduz  = $c61_reduz;
                $clconplanoexe->c62_codrec = $c61_codigo;
                $clconplanoexe->c62_vlrcre = '0';
                $clconplanoexe->c62_vlrdeb = '0';

                $clconplanoexe->incluir($iAno,$c61_reduz);

                if ($clconplanoexe->erro_status == 0){
                  $erro_msg = $clconplanoexe->erro_msg;
                  $sqlerro  = true;
                }
              }

            }
            if ($sqlerro == false && $tipo == "analitica" && $c61_contrapartida > 0) {

              $db_conplano->evento($c61_reduz, $c61_contrapartida, $iAno, $c61_instit);
              $c60_codcon = $clconplanoreduz->c61_codcon;
            }
          }
        }
      } // fim do 3 sqlerro
    }
  } else {  // fim if ($numrows > 0)
    $sqlerro = true;
  }

  unset($atualizar);
  //$sqlerro = true;
  db_fim_transacao($sqlerro);

  if ($sqlerro == false){
    $msg = ".";
    if ($tipo == "analitica"){
      $msg = ", liberando aba Reduzidos";
    }

    db_msgbox("Importação feita com sucesso".$msg);
    db_redireciona("con1_conplano022.php?chavepesquisa=$c60_codcon&tipo=$tipo");
  }
}

if (isset($atualizar)) {
  $codigo = str_replace(".", "", $c90_estrutcontabil);

  //**************************************************************
  //rotina que verifica se e estrutural já não existe
  $result  = $clconplano->sql_record($clconplano->sql_query_file("","","c60_anousu as anousu_ant", "", "c60_estrut='$codigo' "));
  $numrows = $clconplano->numrows;
  if ($numrows > 0) {
    // estrutural já existe no plano de contas
    db_fieldsmemory($result,$numrows-1);

    if ($anousu_ant == $anousu){
      db_msgbox("Este estrutural $codigo ja existe no plano de contas (Exercício $anousu_ant)!");
    } else {
      echo "<script>\n";
      echo "var retorna = confirm('Esta conta já existe no exercício de $anousu_ant. Deseja importar os dados?');\n";
      echo "if (retorna == false) {\n";
      echo "  document.location.href='con1_conplano011.php'";
      echo "} else {\n";
      echo "  document.location.href='con1_conplano011.php?c90_estrutcontabil=$c90_estrutcontabil&importar=true'";
      echo "}\n";
      echo "</script>\n";
    }
  }
}

if (isset($incluir)) {

	$sqlerro = false;
  $codigo = str_replace(".", "", $c90_estrutcontabil);
  // $c61_instit = db_getsession("DB_instit");

  //**************************************************************
  //rotina que verifica se e estrutural já não existe
  $result  = $clconplano->sql_record($clconplano->sql_query_file("","","c60_anousu as anousu_ant", "", "c60_estrut='$codigo' "));
  $numrows = $clconplano->numrows;
  if ($numrows > 0) {
    db_fieldsmemory($result,$numrows-1);
    // estrutural já existe no plano de contas
    if ($anousu_ant < $anousu){
      $erro_msg = "Este estrutural $codigo ja existe no plano de contas (Exercício $anousu_ant)! ";
      $sqlerro = true;
    }
  } else {
    if ($clconplano->db_verifica_conplano($codigo,$anousu) == false) {
      $erro_msg = $clconplano->erro_msg;
      $sqlerro = true;
      $focar = "c90_estrutcontabil";
    } else {
      /*rotina que verifica se a conta é analitica ou nao*/
      $nivel = db_le_mae_conplano($codigo, true);
      if ($nivel != 1) {
        $mae = db_le_mae_conplano($codigo, false);
        $result = $clconplano->sql_record($clconplano->sql_query_file("","","c60_codcon as c60_codcon_mae", "", "c60_anousu=$anousu and c60_estrut='$mae'"));
        db_fieldsmemory($result, 0);
        $result = $clconplanoreduz->sql_record($clconplanoreduz->sql_query_file(null,null, "*", '', "c61_anousu=$anousu and c61_codcon=$c60_codcon_mae"));
        if ($clconplanoreduz->numrows > 0) {
          $erro_msg = "Conta superior $mae é analítica!\\n Inclusão não permitida!";
          $sqlerro = true;
          $focar = "c90_estrutcontabil";
        }
      }
      //*********************************************
      //{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{{
      /*fim*/
      db_inicio_transacao();
      if ($sqlerro == false) {
        $clconplano->c60_anousu = $anousu;
        $clconplano->c60_estrut = $codigo;
        if (substr($codigo,0,1) == "3"||substr($codigo,0,1) == "4") {
          $clconplano->c60_codsis = 1;
        }
        $clconplano->c60_consistemaconta         = "0";
        $clconplano->c60_identificadorfinanceiro = "N";
        $clconplano->c60_naturezasaldo           = "3";
        $clconplano->incluir(null,$clconplano->c60_anousu);

        if ($clconplano->erro_status == 0) {
          $sqlerro = true;
        } else {
          $c60_codcon = $clconplano->c60_codcon;
        }
        $erro_msg = $clconplano->erro_msg;

        if ($sqlerro == false) {
          $sql_conplano = "select max(c60_anousu) as c60_anousu from conplano";
          $res_conplano = $clconplano->sql_record($sql_conplano);
          $numrows      = $clconplano->numrows;

          if ($numrows > 0) {
            db_fieldsmemory($res_conplano,0);

            $contador = $c60_anousu - $anousu;
            for ($i=0; $i < $contador; $i++) {

              $iAnoContador           = ($anousu + $i) + 1;
              $clconplano->c60_anousu = $iAnoContador;
              $clconplano->c60_estrut = $codigo;

              if (substr($codigo,0,1) == "3"||substr($codigo,0,1) == "4") {
                $clconplano->c60_codsis = 1;
              } else {
                if (trim(@$c60_codsis) != "") {
                  $clconplano->c60_codsis = $c60_codsis;
                }
              }

              $sTabela = "conplano";
              if ($iAnoContador >= 2013 && db_stdClass::possuiPCASPAtivo()) {
                $sTabela = "conplanoorcamento";
              }

              $sql_conplano_posterior = "select c60_anousu as anousu_posterior
                                           from {$sTabela}
                                          where c60_codcon = {$c60_codcon}
                                            and c60_anousu = {$iAnoContador}";
              $res_conplano_posterior = $clconplano->sql_record($sql_conplano_posterior);

              if ($clconplano->numrows == 0){

                if ($iAnoContador <= 2012 || !db_stdClass::possuiPCASPAtivo()) {

                  $clconplano->incluir($c60_codcon,$iAnoContador);
                  if ($clconplano->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $clconplano->erro_msg;
                    break;
                  }
                } else {

                  /**
                   * Incluimos na conplanoorcamento caso o ano do FOR seja >= 2013
                   */
                  $oDaoConPlanoOrcamento = db_utils::getDao('conplanoorcamento');
                  $oDaoConPlanoOrcamento->c60_codcon                  = null;
                  $oDaoConPlanoOrcamento->c60_anousu                  = $iAnoContador;
                  $oDaoConPlanoOrcamento->c60_estrut                  = $clconplano->c60_estrut;
                  $oDaoConPlanoOrcamento->c60_descr                   = $clconplano->c60_descr;
                  $oDaoConPlanoOrcamento->c60_finali                  = $clconplano->c60_finali;
                  $oDaoConPlanoOrcamento->c60_codsis                  = $clconplano->c60_codsis;
                  $oDaoConPlanoOrcamento->c60_codcla                  = $clconplano->c60_codcla;
                  $oDaoConPlanoOrcamento->c60_consistemaconta         = $clconplano->c60_consistemaconta;
                  $oDaoConPlanoOrcamento->c60_identificadorfinanceiro = $clconplano->c60_identificadorfinanceiro;
                  $oDaoConPlanoOrcamento->c60_naturezasaldo           = $clconplano->c60_naturezasaldo;
                  $oDaoConPlanoOrcamento->c60_funcao                  = $clconplano->c60_funcao;
                  $oDaoConPlanoOrcamento->incluir(null, $iAnoContador);
                  if ($oDaoConPlanoOrcamento->erro_status == 0) {
                    $sqlerro  = true;
                    $erro_msg = $oDaoConPlanoOrcamento->erro_msg;
                    break;
                  }


                }
              }
            }
          }
        }
      }


      if ($sqlerro == false) {

					if ( $lContaBancaria ) {

			      if ( isset($c56_contabancaria) && trim($c56_contabancaria) != '' ) {

			        $clconplanocontabancaria->c56_codcon        = $c60_codcon;
			        $clconplanocontabancaria->c56_anousu        = $anousu;
			        $clconplanocontabancaria->c56_contabancaria = $c56_contabancaria;
			        $clconplanocontabancaria->incluir(null);

			        $erro_msg = $clconplanocontabancaria->erro_msg;

			        if ( $clconplanocontabancaria->erro_status == 0 ) {
			          $sqlerro = true;
			        }

			        if ( !$sqlerro ){

			          for ($i=0; $i < $contador; $i++) {

			            $clconplanocontabancaria->c56_codcon        = $c60_codcon;
			            $clconplanocontabancaria->c56_anousu        = ($anousu + $i) + 1;
			            $clconplanocontabancaria->c56_contabancaria = $c56_contabancaria;
			            $clconplanocontabancaria->incluir(null);

			            $erro_msg = $clconplanocontabancaria->erro_msg;

			            if ( $clconplanocontabancaria->erro_status == 0 ) {
			              $sqlerro = true;
			            }
			          }
			        }
			      }
					} else {

			      // inclusão na conplanoconta [contas bancárias]
			      if (isset($c63_banco) && $c63_banco != "" || isset($c63_agencia) && $c63_agencia != "" || isset($c63_conta) && $c63_conta != "") {
			        $clconplanoconta->c63_anousu        = $anousu;
			        $clconplanoconta->c63_banco         = $c63_banco;
			        $clconplanoconta->c63_agencia       = $c63_agencia;
			        $clconplanoconta->c63_conta         = $c63_conta;
			        $clconplanoconta->c63_identificador = $c63_identificador;
			        $clconplanoconta->incluir($c60_codcon,$clconplanoconta->c63_anousu);
			        $erro_msg = $clconplanoconta->erro_msg;
			        // $clconplanoconta->erro(true,false);
			        if ($clconplanoconta->erro_status == 0) {
			          $sqlerro = true;
			        }
			        if ($sqlerro == false){
			          for ($i=0; $i < $contador; $i++) {
			            $clconplanoconta->c63_banco         = $c63_banco;
			            $clconplanoconta->c63_agencia       = $c63_agencia;
			            $clconplanoconta->c63_conta         = $c63_conta;
			            $clconplanoconta->c63_identificador = $c63_identificador;

			            $clconplanoconta->incluir($c60_codcon,(($anousu + $i) + 1));
			            $erro_msg = $clconplanoconta->erro_msg;
			            if ($clconplanoconta->erro_status == 0) {
			              $sqlerro = true;
			              break;
			            }
			          }
			        }
			      }
					}
      }

      // ----------- * ---------------------
      if ($sqlerro == false) {
        //rotina que verifica quando é para incluir no orcelemento ou no orcfontes
        $sql_conplano = "select max(c60_anousu) as c60_anousu from conplano";
        $res_conplano = $clconplano->sql_record($sql_conplano);
        $numrows      = $clconplano->numrows;
        if ($numrows > 0) {
          db_fieldsmemory($res_conplano,0);
          $contador = $c60_anousu - $anousu;
        }

        $arr_tipo     = array("orcelemento" => "3", "orcfontes" => array("4","9"));

        if (substr($codigo, 0, 1) == $arr_tipo["orcelemento"]) {
          $clorcelemento->o56_codele   = $c60_codcon;
          $clorcelemento->o56_anousu   = $anousu;
          $clorcelemento->o56_elemento = substr($codigo, 0, 13);
          $clorcelemento->o56_descr    = $c60_descr;
          $clorcelemento->o56_finali   = $c60_finali;
          $clorcelemento->o56_orcado   = 'true';

          $clorcelemento->incluir($c60_codcon,$anousu);
          if ($clorcelemento->erro_status == 0) {
            $sqlerro  = true;
            $erro_msg = $clorcelemento->erro_msg;
          }
          if ($sqlerro == false) {
            for ($i=0; $i < $contador; $i++) {
              $clorcelemento->o56_codele   = $c60_codcon;
              $clorcelemento->o56_anousu   = ($anousu + $i) + 1;
              $clorcelemento->o56_elemento = substr($codigo, 0, 13);
              $clorcelemento->o56_descr    = $c60_descr;
              $clorcelemento->o56_finali   = $c60_finali;
              $clorcelemento->o56_orcado   = 'true';

              $clorcelemento->incluir($c60_codcon,$clorcelemento->o56_anousu);
              if ($clorcelemento->erro_status == 0) {
                $sqlerro  = true;
                $erro_msg = $clorcelemento->erro_msg;
                break;
              }
            }
          }
        } else if (in_array(substr($codigo, 0, 1), $arr_tipo["orcfontes"])) {
          $clorcfontes->o57_codfon = $c60_codcon;
          $clorcfontes->o57_anousu = $anousu;
          $clorcfontes->o57_fonte  = $codigo;
          $clorcfontes->o57_descr  = $c60_descr;
          $clorcfontes->o57_finali = $c60_finali;

          $clorcfontes->incluir($c60_codcon,$anousu);
          if ($clorcfontes->erro_status == 0) {
            $sqlerro = true;
            $erro_msg = $clorcfontes->erro_msg;
          }
          if ($sqlerro == false) {
            for ($i=0; $i < $contador; $i++) {
              $clorcfontes->o57_codfon = $c60_codcon;
              $clorcfontes->o57_anousu = ($anousu + $i) + 1;
              $clorcfontes->o57_fonte  = $codigo;
              $clorcfontes->o57_descr  = $c60_descr;
              $clorcfontes->o57_finali = $c60_finali;

              $clorcfontes->incluir($c60_codcon,$clorcfontes->o57_anousu);
              if ($clorcfontes->erro_status == 0) {
                $sqlerro = true;
                $erro_msg = $clorcfontes->erro_msg;
                break;
              }
            }
          }
        }
      }

      // db_msgbox($sqlerro);
      //$sqlerro = true; // teste de erro
      db_fim_transacao($sqlerro);
      if ($sqlerro == false && $tipo == "analitica") {
        // se conta analitica, redireciona para guia de alteração, possibilitando incluir
        db_msgbox("Inclusao com sucesso, liberando aba Reduzidos ");
        db_redireciona("con1_conplano022.php?chavepesquisa=$c60_codcon&tipo=$tipo");
      }
    }
  }
}
// end incluir
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    <br>
      <?
			if (USE_PCASP && db_getsession("DB_anousu") >= 2013) {

			  $sMensagem  = "Esta rotina está desabilitada para o ano de 2013.\\n";
			  $sMensagem .= "Para cadastrar uma nova conta acesse o menu:\\n\\n";
			  $sMensagem .= "Contabilidade > Cadastros > Plano de Contas (PCASP)";
			  db_msgbox($sMensagem);
			  $db_botao = false;
			  echo "<script>
                parent.document.formaba.reduzido.disabled ='true';
  	      </script>";
			}
        include ("forms/db_frmconplano.php");
      ?>
    </center>
	</td>
  </tr>
</table>

</body>
</html>
<?
if (isset ($incluir) || (isset($importar) && $importar == true)) {
	if (isset ($perg_msg)) {
		echo "<script>";
		echo "  retorna = confirm('$perg_msg');\n";
		echo "   if(retorna == true){\n";
		echo "      obj=document.createElement('input');\n
						 obj.setAttribute('name','novo_reduz');\n
						 obj.setAttribute('type','hidden');\n
						 obj.setAttribute('value','true');\n
						 document.form1.appendChild(obj);\n
						 document.form1.incluir.click();\n
			      }\n
			      ";
		echo "</script>";

	} else {
		if ($sqlerro == true) {
			db_msgbox($erro_msg);
			if ($clconplano->erro_campo != "") {
				echo "<script> document.form1.".$clconplano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
				echo "<script> document.form1.".$clconplano->erro_campo.".focus();</script>";
			} else
				if ($clconplanoreduz->erro_campo != "") {
					echo "<script> document.form1.".$clconplanoreduz->erro_campo.".style.backgroundColor='#99A9AE';</script>";
					echo "<script> document.form1.".$clconplanoreduz->erro_campo.".focus();</script>";
				}
		} else {
			db_msgbox($erro_msg);
    	echo "<script>
              top.corpo.iframe_conta.location.href = 'con1_conplano011.php';
              top.corpo.document.formaba.grupos.style.visibility='visible';
		          top.corpo.iframe_grupos.disable='false';
              top.corpo.iframe_grupos.location.href = 'con1_congrupo004.php?c21_anousu=$anousu&c21_codcon=$c60_codcon';
              parent.mo_camada('grupos');
		        </script>";
		}
	}
}
?>