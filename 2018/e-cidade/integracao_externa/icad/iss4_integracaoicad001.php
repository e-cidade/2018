<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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
 *  A variável iParamLog define o tipo de log que deve ser gerado :
 *  0 - Imprime log na tela e no arquivo
 *  1 - Imprime log somente da tela
 *  2 - Imprime log somente no arquivo
 *  @todo receber via args
 */
$iParamLog   = 0;
$sArquivoLog = null;
if ( $iParamLog != 1 ) {
  $sArquivoLog = "log/processamento_icad_".date("Ymd_His").".log";
}

// Declarando variáveis necessárias para que a inclusão das bibliotecas não retorne mensagens
$HTTP_SERVER_VARS["HTTP_HOST"]    = '';
$HTTP_SERVER_VARS["PHP_SELF"]     = '';
$HTTP_SERVER_VARS["HTTP_REFERER"] = '';
$HTTP_POST_VARS                   = array();
$HTTP_GET_VARS                    = array();

require_once("libs/db_libconversao.php");
require_once("libs/dbportal.constants.php");
require_once("libs/db_conecta.php");

require_once(DB_MODEL . "model/configuracao/TraceLog.model.php");
require_once(DB_LIBS  . "libs/db_stdlib.php");
require_once(DB_LIBS  . "libs/db_utils.php");
require_once(DB_LIBS  . "libs/db_sql.php");
require_once(DB_LIBS  . "std/label/rotulo.php");
require_once(DB_LIBS  . "std/label/RotuloDB.php");
require_once(DB_MODEL . "model/dataManager.php");

$iDataHoraIntegracao = time();
$aCamposEspecificos  = array( "cod_cliente" => $ConfigINI["ClienteIcad"],
                              "timestamp"   => date('Y-m-d H:i:s', $iDataHoraIntegracao),
                              "controle"    => '' );

/**
 * Definimos as Tabelas que sofreram carga
 */
$aTabelasImportacao = array();

/**
 * Zonas
 */
$aTabelasImportacao["zonas"]->sAliasesOrigem       = "Zonas";
$aTabelasImportacao["zonas"]->sTabelaDestino       = "tb_inter_zona_icad";
$aTabelasImportacao["zonas"]->aCamposEspecificos   = $aCamposEspecificos;

$aTabelasImportacao["zonas"]->sSqlDadosOrigem      = "   select j50_zona  as zona_cod_zona, ";
$aTabelasImportacao["zonas"]->sSqlDadosOrigem     .= "          j50_descr as zona_desc      ";
$aTabelasImportacao["zonas"]->sSqlDadosOrigem     .= "     from zonas                       ";
$aTabelasImportacao["zonas"]->sSqlDadosOrigem     .= " order by j50_zona                    ";

$aTabelasImportacao["zonas"]->sSqlDadosDestino     = " select zona_cod_zona,     ";
$aTabelasImportacao["zonas"]->sSqlDadosDestino    .= "        zona_desc          ";
$aTabelasImportacao["zonas"]->sSqlDadosDestino    .= "   from tb_inter_zona_icad ";

$aTabelasImportacao["zonas"]->aCamposWhereDestino  = array( "zona_cod_zona" => false );

/**
 * Matriculas por Zona
 */
$aTabelasImportacao["matriculaPorZona"]->sAliasesOrigem       = "Matricula por Zona";
$aTabelasImportacao["matriculaPorZona"]->sTabelaDestino       = "tb_inter_imob_zona_icad";
$aTabelasImportacao["matriculaPorZona"]->aCamposEspecificos   = $aCamposEspecificos;

$aTabelasImportacao["matriculaPorZona"]->sSqlDadosOrigem      = "   select j01_matric as imob_num_cadastro,         ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosOrigem     .= "          j34_zona   as zona_cod_zona              ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosOrigem     .= "     from iptubase                                 ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosOrigem     .= "          inner join lote on j01_idbql = j34_idbql ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosOrigem     .= " order by j01_matric                               ";

$aTabelasImportacao["matriculaPorZona"]->sSqlDadosDestino     = " select imob_num_cadastro,      ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosDestino    .= "        zona_cod_zona           ";
$aTabelasImportacao["matriculaPorZona"]->sSqlDadosDestino    .= "   from tb_inter_imob_zona_icad ";

$aTabelasImportacao["matriculaPorZona"]->aCamposWhereDestino  = array( "imob_num_cadastro" => true );

/**
 * Logradouros
 */
$aTabelasImportacao["logradouros"]->sAliasesOrigem       = "Logradouros";
$aTabelasImportacao["logradouros"]->sTabelaDestino       = "tb_inter_logr_bairro_icad";

$aCamposEspecificos["co_instrucao"]                      = "04";
$aTabelasImportacao["logradouros"]->aCamposEspecificos   = $aCamposEspecificos;

$aTabelasImportacao["logradouros"]->sSqlDadosOrigem      = "   select distinct                                         ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          null       as titl_abr_tit,                      ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j88_sigla  as tipo_abr_tip,                      ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j14_codigo as logr_cod_logradouro,               ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j14_nome   as logr_nom_logradouro,               ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j13_codi   as bair_cod_bairro,                   ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j13_descr  as bair_nom_bairro,                   ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          j29_cep    as loca_cep,                          ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          ''         as cod_bairro_pref                    ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "     from ruas                                             ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          inner join ruastipo   on j88_codigo = j14_tipo   ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          left  join ruasbairro on j16_lograd = j14_codigo ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          left  join bairro     on j16_bairro = j13_codi   ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= "          left  join ruascep    on j29_codigo = j14_codigo ";
$aTabelasImportacao["logradouros"]->sSqlDadosOrigem     .= " order by j14_codigo                                       ";

$aTabelasImportacao["logradouros"]->sSqlDadosDestino     = " select tipo_abr_tip,             ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        null as titl_abr_tit,     ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        logr_cod_logradouro,      ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        logr_nom_logradouro,      ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        bair_cod_bairro,          ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        bair_nom_bairro,          ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        loca_cep,                 ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "        ''   as cod_bairro_pref   ";
$aTabelasImportacao["logradouros"]->sSqlDadosDestino    .= "   from tb_inter_logr_bairro_icad ";

$aTabelasImportacao["logradouros"]->aCamposWhereDestino  = array( "logr_cod_logradouro" => true,
                                                                  "bair_cod_bairro"     => true );

/**
 * Matrículas
 */
$aTabelasImportacao["matriculas"]->sAliasesOrigem     = "Matriculas";
$aTabelasImportacao["matriculas"]->sTabelaDestino     = "tb_inter_imobiliario_icad";

$aCamposEspecificos["co_instrucao"]                   = "03";
$aTabelasImportacao["matriculas"]->aCamposEspecificos = $aCamposEspecificos;

$aTabelasImportacao["matriculas"]->sSqlDadosOrigem  = "   select imob_num_cadastro,                                                                         ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          imob_num_cadastro as imob_num_imobiliario,                                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as titl_abr_tit,                                                         ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          tipo_abr_tip,                                                                              ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          logr_cod_logradouro,                                                                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          logr_nom_logradouro,                                                                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          loca_cep,                                                                                  ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          bair_cod_bairro,                                                                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          bair_nom_bairro,                                                                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          imob_num_imovel,                                                                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as num_face,                                                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          quadra,                                                                                    ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          lote,                                                                                      ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null as bloco,                                                                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          mtq_construida,                                                                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          mt_area,                                                                                   ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          imob_dat_morto,                                                                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as fracao_ideal,                                                         ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          ''                as zona_cod_zona,                                                        ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          ''                as zona_desc,                                                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as parametro,                                                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as folha,                                                                ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          ''                as unidade,                                                              ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as sub_unidade,                                                          ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          null              as mt_face_linear                                                        ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "     from (select *,                                                                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j88_sigla  as tipo_abr_tip,                                                        ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j14_codigo as logr_cod_logradouro,                                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j14_nome   as logr_nom_logradouro,                                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j29_cep    as loca_cep,                                                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j13_codi   as bair_cod_bairro,                                                     ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                  j13_descr  as bair_nom_bairro                                                      ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "             from (select distinct j01_matric           as imob_num_cadastro,                        ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j39_numero           as imob_num_imovel,                          ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j36_face             as num_face,                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j34_quadra           as quadra,                                   ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j34_lote             as lote,                                     ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   (select round(sum(subiptuconstr.j39_area), 2)                     ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                      from iptuconstr subiptuconstr                                  ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                     where subiptuconstr.j39_matric = j01_matric                     ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                       and subiptuconstr.j39_dtdemo is null) as mtq_construida,      ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   round(j34_area, 2)   as mt_area,                                  ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   round(j01_fracao, 2) as fracao_ideal,                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j01_baixa            as imob_dat_morto,                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j36_codigo,                                                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   j34_bairro                                                        ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                              from iptubase                                                          ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   left join iptuconstr on j01_matric = j39_matric                   ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                                       and j39_idprinc is true                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                                       and j39_dtdemo  is null                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   left join lote       on j01_idbql  = j34_idbql                    ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   left join testada    on j01_idbql  = j36_idbql                    ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                   inner join testpri   on j49_idbql  = j36_idbql                    ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "                                                       and j49_face   = j36_face ) as sub_matriculas ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          inner join ruas     on j14_codigo = j36_codigo                                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          inner join ruastipo on j14_tipo   = j88_codigo                                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          left  join ruascep  on j14_codigo = j29_codigo                                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= "          left  join bairro   on j34_bairro = j13_codi ) as sub_ruas                                 ";
$aTabelasImportacao["matriculas"]->sSqlDadosOrigem .= " order by imob_num_cadastro                                                                          ";

$aTabelasImportacao["matriculas"]->sSqlDadosDestino  = " select imob_num_cadastro,                         ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        imob_num_cadastro as imob_num_imobiliario, ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as titl_abr_tit,         ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        tipo_abr_tip,                              ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        logr_cod_logradouro,                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        logr_nom_logradouro,                       ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        loca_cep,                                  ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        bair_cod_bairro,                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        bair_nom_bairro,                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        imob_num_imovel,                           ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        quadra,                                    ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        lote,                                      ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null as bloco,                             ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        mtq_construida,                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        mt_area,                                   ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        imob_dat_morto,                            ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as num_face,             ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as fracao_ideal,         ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as zona_cod_zona,        ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as zona_desc,            ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as parametro,            ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as folha,                ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        ''                as unidade,              ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as sub_unidade,          ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "        null              as mt_face_linear        ";
$aTabelasImportacao["matriculas"]->sSqlDadosDestino .= "   from tb_inter_imobiliario_icad                  ";

$aTabelasImportacao["matriculas"]->aCamposWhereDestino  = array( "imob_num_cadastro" => true );

/**
 *  Inicia sessão e transação
 */
db_query($connOrigem , "select fc_startsession();");
db_query($connDestino, "begin;");

/**
 * Bloco de Tratamento da Integração
 */
try {

	$sSqlInstit  = " select fc_putsession('DB_instit',( select codigo                             ";
  $sSqlInstit .= "                                      from db_config                          ";
  $sSqlInstit .= "                                     where prefeitura is true limit 1)::text) ";
	$rsInstit    = db_query($connOrigem, $sSqlInstit);

	if ( !$rsInstit ) {
		throw new Exception("Instituição não definida.");
	}

	$sSqlConsultaInstit = "select fc_getsession('DB_instit') as instit ";
	$rsConsultaInstit   = db_query($connOrigem, $sSqlConsultaInstit);

  db_putsession("DB_instit",     db_utils::fieldsMemory($rsConsultaInstit,0)->instit);
	db_putsession("DB_acessado",   "1");
	db_putsession("DB_datausu",    $iDataHoraIntegracao);
	db_putsession("DB_anousu",     date("Y", $iDataHoraIntegracao));
	db_putsession("DB_id_usuario", "1");

  /**
   * Processa Carga por Tabela
   */
  foreach ($aTabelasImportacao as $oTabelaImportacao) {

     $oDataManager  = new tableDataManager($connDestino, $oTabelaImportacao->sTabelaDestino, null, true, 500);

     db_logTitulo(" Processa {$oTabelaImportacao->sAliasesOrigem} ", $sArquivoLog, $iParamLog);

     $rsTabelaOrigem           = db_query( $connOrigem, $oTabelaImportacao->sSqlDadosOrigem );
     $iTotalLinhasOrigem       = pg_num_rows($rsTabelaOrigem);
     $iTotalRegistrosIncluidos = 0;

     if ( $iTotalLinhasOrigem > 0 ) {

       db_log("Total de Registros Encontrados: {$iTotalLinhasOrigem}", $sArquivoLog, $iParamLog);
       db_log("\n", $sArquivoLog, true);

        $oDadosOrigem = db_utils::getCollectionByRecord($rsTabelaOrigem);

        foreach ($oDadosOrigem as $iIndice => $oRegistroOrigem) {

          /**
           *  Verifica se o registro já existe na base de destino apartir da chave utilizada na base de origem
           */
          $sSqlDadosDestino = $oTabelaImportacao->sSqlDadosDestino;

          /**
           * Monta o where array de campos do where
           */
          $aStringWhere  = array();
          foreach ($oTabelaImportacao->aCamposWhereDestino as $sCampo => $lIsInteger) {

            $sWhereDestino     = " {$sCampo} = '{$oRegistroOrigem->$sCampo}' ";
            if( $lIsInteger ){

              /**
               * Verificamos se o campo eh nulo para alterar para is null
               */
              $sWhereDestino   = " {$sCampo} = {$oRegistroOrigem->$sCampo} ";
              if( trim($oRegistroOrigem->$sCampo) == '' ){
                $sWhereDestino = " {$sCampo} is null ";
              }
            }

            $aStringWhere[] = $sWhereDestino;
          }
          $sWhereDestino = implode(" and ", $aStringWhere);

          /**
           * Concatena o where a query de destino
           */
          $sSqlDadosDestino .= " where " . $sWhereDestino;

          /**
           * Na query destino o padrao é ordenacao pelo ultimo registro
           */
          $sSqlDadosDestino .= " order by timestamp desc ";
          $sSqlDadosDestino .= "    limit 1              ";

          $rsTabelaDestino   = db_query( $connDestino, $sSqlDadosDestino );

          logProcessamento($iIndice, $iTotalLinhasOrigem, $iParamLog);

          $lNaoPersistirRegistro = false;
          if ( pg_num_rows($rsTabelaDestino) > 0 ) {

            $oRegistroDestino = db_utils::fieldsMemory($rsTabelaDestino, 0);
            if ( !hasDiffObject($oRegistroOrigem, $oRegistroDestino) ) {
              $lNaoPersistirRegistro = true;
            }
          }

          /**
           * Avança para proximo registro da origem
           */
          if( $lNaoPersistirRegistro ){
            continue;
          }

          /**
           *  Define campos especificos
           */
          foreach ($oTabelaImportacao->aCamposEspecificos as $sChave => $sValorCampo) {
            $oRegistroOrigem->$sChave = $sValorCampo;
          }

          /**
           *  Atribui o valores da base de origem ao objeto tableDataManager de Destino
           */
          $oDataManager->setByLineOfDBUtils($oRegistroOrigem);
          try {

            $oDataManager->insertValue();
            $iTotalRegistrosIncluidos++;
          } catch ( Exception $eException ) {
            throw new Exception("Erro inserir {$oTabelaImportacao->sAliasesOrigem}: {$eException->getMessage()}");
          }
        }

        db_log("\n", $sArquivoLog, $iParamLog, true, false);
        db_log("Quantidade de registros a serem persistidos: {$iTotalRegistrosIncluidos}", $sArquivoLog, $iParamLog, true, false);

        try {
          $oDataManager->persist();
        } catch ( Exception $eException ) {
          throw new Exception("Erro persistir {$oTabelaImportacao->sAliasesOrigem}: {$eException->getMessage()}");
        }

      } else {
        db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
      }
  }

  db_query($connDestino,"commit;");
} catch (Exception $eException) {

  db_log($eException->getMessage(), $sArquivoLog, $iParamLog);
  db_logTitulo("Fim de processamento com Erro", $sArquivoLog, $iParamLog);
  db_query($connDestino, "rollback;");
}

db_logTitulo(" Fim de processamento ",$sArquivoLog,$iParamLog);