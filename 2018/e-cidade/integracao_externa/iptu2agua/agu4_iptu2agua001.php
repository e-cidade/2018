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

$HTTP_SESSION_VARS['DB_acessado']   = 1;
$HTTP_SESSION_VARS['DB_datausu']    = time();
$HTTP_SESSION_VARS['DB_anousu']     = date('Y',time());
$HTTP_SESSION_VARS['DB_id_usuario'] = 1;

/**
 *  A variável iParamLog define o tipo de log que deve ser gerado :
 *  0 - Imprime log na tela e no arquivo
 *  1 - Imprime log somente da tela
 *  2 - Imprime log somente no arquivo
 */
$iParamLog = 0;

if ( $iParamLog == 1 ) {
  $sArquivoLog = null;
} else {
  $sArquivoLog = "log/processamento_agua".date("Ymd_His").".log";
}
require_once('libs/dbportal.constants.php');
require_once('libs/db_conecta.php');

require_once(DB_LIBS ."libs/db_stdlib.php");
require_once(DB_LIBS ."libs/db_utils.php");

require_once(DB_LIBS . "std/label/rotulo.php");
require_once(DB_LIBS . "std/label/RotuloDB.php");

require_once(DB_CLASSES."classes/db_iptubase_classe.php");
require_once(DB_CLASSES."classes/db_iptuconstr_classe.php");
require_once(DB_CLASSES."classes/db_carconstr_classe.php");
require_once(DB_CLASSES."classes/db_aguabase_classe.php");
require_once(DB_CLASSES."classes/db_aguabasebaixa_classe.php");
require_once(DB_CLASSES."classes/db_aguabasecar_classe.php");
require_once(DB_CLASSES."classes/db_aguaconf_classe.php");
require_once(DB_CLASSES."classes/db_aguaconstr_classe.php");
require_once(DB_CLASSES."classes/db_aguaconstrcar_classe.php");

$oDaoIptuBase   = new cl_iptubase();
$oDaoIptuConstr = new cl_iptuconstr();
$oDaoCarConstr  = new cl_carconstr();

$oDaoAguaBase      = new cl_aguabase();
$oDaoAguaBaseBaixa = new cl_aguabasebaixa();
$oDaoAguaBaseCar   = new cl_aguabasecar();
$oDaoAguaConf      = new cl_aguaconf();
$oDaoAguaConstr    = new cl_aguaconstr();
$oDaoAguaConstrCar = new cl_aguaconstrcar();

$lErro      = false;
$dtDataHoje = date("Y-m-d");
$iAnoUsu 	  = date("Y");

/**
 *  Inicia sessão e transação
 */
db_query($pConexao ,"SELECT fc_startsession();");
db_query($pConexao ,"BEGIN;");

/**
 *  Verifica se existem atualizações de base de dados
 *  e as aplica na mesma
 */

try {

	$sSqlInstit  = " SELECT fc_putsession('DB_instit',( SELECT codigo ";
  $sSqlInstit .= "                                      FROM db_config ";
  $sSqlInstit .= "                                     WHERE prefeitura IS TRUE LIMIT 1)::text) ";
	$rsInstit    = db_query($pConexao,$sSqlInstit);

	if ( !$rsInstit ) {
		throw new Exception('Instituição não definida!');
	} else {

		$sSqlConsultaInstit = "select fc_getsession('DB_instit') as instit ";
		$rsConsultaInstit   = db_query($pConexao,$sSqlConsultaInstit);
		$HTTP_SESSION_VARS['DB_instit'] = db_utils::fieldsMemory($rsConsultaInstit,0)->instit;
	}

  /**
   *  Importacao dos registros da IPTUBASE e IPTUCONSTR para AGUABASE e AGUACONSTR
   */
  db_logTitulo(" IMPORTA MATRICULAS DO IPTU PARA MODULO AGUA", $sArquivoLog, $iParamLog);

  $sSqlIptuSemAgua  = $oDaoIptuBase->sql_query_file(null, "*", "j01_matric", "not exists (select 1 from aguabase where x01_matric = j01_matric)");
  $rsIptuSemAgua = $oDaoIptuBase->sql_record($sSqlIptuSemAgua);
  $iLinhasIptuSemAgua = $oDaoIptuBase->numrows;

  if ( $iLinhasIptuSemAgua == 0 ) {

    db_log("Nao existem matriculas para serem importadas!", $sArquivoLog, 0);

  } else {

    for( $indx = 0; $indx < $iLinhasIptuSemAgua; $indx++) {
      $oIptubase = db_utils::fieldsMemory($rsIptuSemAgua, $indx);

      logProcessamento($indx, $iLinhasIptuSemAgua, $iParamLog);

      $sSqlProprietario = "SELECT * FROM proprietario WHERE j01_matric = {$oIptubase->j01_matric}";
      $rsProprietario = db_query($pConexao, $sSqlProprietario);
      if ( pg_numrows($rsProprietario) == 0 ) {
        throw new Exception("Não foram encontradas informações para importação da Matricula {$oIptubase->j01_matric}!");
      }
      $oProprietario = db_utils::fieldsMemory($rsProprietario, 0);


      /**
       *  Insere em AguaBase
       */
      $oDaoAguaBase->x01_matric        = $oProprietario->j01_matric;
      $oDaoAguaBase->x01_codrua        = $oProprietario->codpri;
      $oDaoAguaBase->x01_codbairro     = $oProprietario->j34_bairro;
      $oDaoAguaBase->x01_numcgm        = $oProprietario->z01_cgmpri;
      $oDaoAguaBase->x01_promit        = $oProprietario->z01_numcgm;
      $oDaoAguaBase->x01_distrito      = 1;
      $oDaoAguaBase->x01_zona          = $oProprietario->j34_zona;
      $oDaoAguaBase->x01_quadra        = $oProprietario->j34_quadra;
      $oDaoAguaBase->x01_numero        = $oProprietario->j43_numimo;
      $oDaoAguaBase->x01_orientacao    = '';
      $oDaoAguaBase->x01_rota          = 1;
      $oDaoAguaBase->x01_dtcadastro    = date('Y-m-d');
      $oDaoAguaBase->x01_qtdponto      = 1;
      $oDaoAguaBase->x01_obs           = 'Matricula incluida pelo script agu4_iptu2agua001.php em '.date('Y-m-d').' '.date('H:i:s');
      $oDaoAguaBase->x01_multiplicador = 't';
      $oDaoAguaBase->x01_qtdeconomia   = 1;
      $oDaoAguaBase->x01_entrega       = 1;
      $oDaoAguaBase->x01_letra         = '';

      $oDaoAguaBase->incluir( $oProprietario->j01_matric );

      if ( $oDaoAguaBase->erro_status == '0' ) {
        throw new Exception("ERRO-01: Erro ao incluir aguabase para matricula {$oProprietario->j01_matric}, {$oDaoAguaBase->erro_msg}");
      }

      /**
       *  Insere Construcoes
       */
      $sSqlIptuConstr  = "SELECT j39_matric, SUM(COALESCE(j39_area, 0)) as j39_area ";
      $sSqlIptuConstr .= "  FROM iptuconstr ";
      $sSqlIptuConstr .= " WHERE j39_matric = {$oProprietario->j01_matric} ";
      $sSqlIptuConstr .= "   AND j39_dtdemo IS NULL ";
      $sSqlIptuConstr .= " GROUP BY j39_matric ";

      $rsIptuConstr = $oDaoIptuConstr->sql_record($sSqlIptuConstr);
      $iLinhasIptuConstr = $oDaoIptuConstr->numrows;

      if ( $iLinhasIptuConstr > 0 ) {

        $oIptuConstr = db_utils::fieldsMemory($rsIptuConstr, 0);

        $oDaoAguaConstr->x11_matric      = $oIptuConstr->j39_matric;
        $oDaoAguaConstr->x11_area        = $oIptuConstr->j39_area;
        $oDaoAguaConstr->x11_pavimento   = 1;
        $oDaoAguaConstr->x11_numero      = 1;
        $oDaoAguaConstr->x11_qtdfamilia  = 1;
        $oDaoAguaConstr->x11_qtdpessoas  = 1;
        $oDaoAguaConstr->x11_complemento = '';
        $oDaoAguaConstr->x11_tipo        = 'P';

        $oDaoAguaConstr->incluir( null );

        if ( $oDaoAguaConstr->erro_status == '0' ) {
          throw new Exception("ERRO-02: Erro ao incluir aguaconstr para matricula {$oProprietario->j01_matric}, {$oDaoAguaConstr->erro_msg}");
        }

      }

      /**
       *  Verifica Maior Caracteristica do Grupo 76 das Construcoes para Inserir da AguaConstrCar
       */
      $sSqlCarConstr  = "SELECT j48_caract ";
      $sSqlCarConstr .= "  FROM carconstr ";
      $sSqlCarConstr .= "       INNER JOIN caracter ON j31_codigo = j48_caract ";
      $sSqlCarConstr .= " WHERE j48_matric = {$oProprietario->j01_matric} ";
      $sSqlCarConstr .= "   AND j31_grupo  = 76 ";
      $sSqlCarConstr .= " ORDER BY j48_caract DESC ";
      $sSqlCarConstr .= " LIMIT 1 ";

      $rsCarConstr = $oDaoCarConstr->sql_record($sSqlCarConstr);
      $iLinhasCarConstr = $oDaoCarConstr->numrows;

      if ( $iLinhasCarConstr > 0 ) {

        $oCarConstr = db_utils::fieldsMemory($rsCarConstr, 0);

        $oDaoAguaConstrCar->x12_codconstr = $oDaoAguaConstr->x11_codconstr;
        $oDaoAguaConstrCar->x12_codigo    = $oCarConstr->j48_caract;

        $oDaoAguaConstrCar->incluir( $oCarConstr->j48_caract, $oDaoAguaConstr->x11_codconstr );

        if ( $oDaoAguaConstrCar->erro_status == '0' ) {
          throw new Exception("ERRO-03: Erro ao incluir aguaconstrcar para matricula {$oProprietario->j01_matric}, construcao {$oDaoAguaConstr->x11_codconstr}, caracteristica {$oCarConstr->j48_caract}, {$oDaoAguaConstrCar->erro_msg}");
        }

      }

      /**
       *  Inclui Caracteristica de Agua Ligada (do Parametro - aguaconf.x18_caragua) qdo tiver alguma caracteristica do grupo 76
       *  ou de Sem Agua (do Parametro - aguaconf.x18_carsemagua) qdo não tiver
       */
      $sSqlCarAgua = "SELECT x18_caragua, x18_carsemagua FROM aguaconf WHERE x18_anousu = {$iAnoUsu}";
      $rsCarAgua = $oDaoAguaConf->sql_record($sSqlCarAgua);
      $iLinhasCarAgua = $oDaoAguaConf->numrows;

      if ( $iLinhasCarAgua < 1 ) {
        throw new Exception("ERRO-04: Configurações não encontradas do Módulo Água para o ano {$iAnoUsu}");
      }

      $oCarAgua = db_utils::fieldsMemory($rsCarAgua, 0);

      /**
       *  Verifica Se tem Caracteristicas 76, 77 ou 78 do Grupo 65 para Gerar AGUA LIGADA
       */
      $sSqlCarConstr  = "SELECT j48_caract                                     ";
      $sSqlCarConstr .= "  FROM carconstr                                      ";
      $sSqlCarConstr .= "       INNER JOIN caracter ON j31_codigo = j48_caract ";
      $sSqlCarConstr .= " WHERE j48_matric = {$oProprietario->j01_matric}      ";
      $sSqlCarConstr .= "   AND j31_grupo  = 65                                ";
      $sSqlCarConstr .= "   AND j31_codigo IN (76, 77, 78)                     ";
      $rsCarConstr = $oDaoCarConstr->sql_record($sSqlCarConstr);
      $iLinhasCarConstr = $oDaoCarConstr->numrows;

      if ( $iLinhasCarConstr > 0 ) {
        $iCaracteristica = $oCarAgua->x18_caragua;
      } else {
        $iCaracteristica = $oCarAgua->x18_carsemagua;
      }

      $oDaoAguaBaseCar->x30_codigo = $iCaracteristica;
      $oDaoAguaBaseCar->x30_matric = $oProprietario->j01_matric;

      $oDaoAguaBaseCar->incluir($oProprietario->j01_matric, $iCaracteristica);

      if ( $oDaoAguaBaseCar->erro_status == '0' ) {
        throw new Exception("ERRO-05: Erro ao incluir aguabasecar para matricula {$oProprietario->j01_matric}, caracteristica {$oCarAgua->x18_caragua}, {$oDaoAguaBaseCar->erro_msg}");
      }

    }

  }


  /**
   *  Importacao dos registros da IPTUBAIXA e IPTUBAIXAPROC para AGUABASEBAIXA
   */
  db_logTitulo(" IMPORTA BAIXAS DE MATRICULAS DO IPTU PARA MODULO AGUA", $sArquivoLog, $iParamLog);

  $sSqlIptuBaixa  = "SELECT * ";
  $sSqlIptuBaixa .= "  FROM iptubaixa ";
  $sSqlIptuBaixa .= "       LEFT JOIN iptubaixaproc ON j03_matric = j02_matric ";
  $sSqlIptuBaixa .= " WHERE NOT EXISTS (SELECT 1 FROM aguabasebaixa WHERE x08_matric = j02_matric) ";
  $sSqlIptuBaixa .= "   AND EXISTS (SELECT 1 FROM aguabase WHERE x01_matric = j02_matric) ";
  $rsIptuBaixa = db_query($pConexao, $sSqlIptuBaixa);
  $iLinhasIptuBaixa = pg_numrows($rsIptuBaixa);


  if ( $iLinhasIptuBaixa == 0 ) {

    db_log("Nao existem baixas de matriculas para serem importadas!", $sArquivoLog, 0);

  } else {

    for ( $indx = 0; $indx < $iLinhasIptuBaixa; $indx++ ) {
      $oIptuBaixa = db_utils::fieldsMemory($rsIptuBaixa, $indx);

      logProcessamento($indx, $iLinhasIptuBaixa, $iParamLog);

      $oDaoAguaBaseBaixa->x08_matric   = $oIptuBaixa->j02_matric;
      $oDaoAguaBaseBaixa->x08_data     = $oIptuBaixa->j02_dtbaixa;
      $oDaoAguaBaseBaixa->x08_obs      = $oIptuBaixa->j02_motivo;
      $oDaoAguaBaseBaixa->x08_obs     .= "\nData: {$oIptuBaixa->j02_data} Hora: {$oIptuBaixa->j02_hora}";
      if ( !empty($oIptuBaixa->j03_codproc) ) {
        $oDaoAguaBaseBaixa->x08_obs     .= "\nProcesso Protocolo: {$oIptuBaixa->j03_codproc}";
      }
      $oDaoAguaBaseBaixa->x08_usuario  = $oIptuBaixa->j02_usuario;

      $oDaoAguaBaseBaixa->incluir( $oIptuBaixa->j02_matric );

      if ( $oDaoAguaBaseBaixa->erro_status == '0' ) {
        throw new Exception("ERRO-03: Erro ao incluir aguabasebaixa para matricula {$oIptuBaixa->j02_matric}, {$oDaoAguaBaseBaixa->erro_msg}");
      }
    }
  }

} catch (Exception $eException) {

	$lErro = true;
  db_log($eException->getMessage(),$sArquivoLog,$iParamLog);

}

if ( $lErro ) {

	db_logTitulo(" FIM PROCESSAMENTO COM ERRO",$sArquivoLog,$iParamLog);
  db_query($pConexao, "ROLLBACK;");
} else {

	db_logTitulo(" FIM PROCESSAMENTO ",$sArquivoLog,$iParamLog);
  db_query($pConexao, "COMMIT;");                     
}

db_log("\n", $sArquivoLog, 0);



function db_log($sLog = "", $sArquivo = "", $iTipo = 0, $lLogDataHora = true, $lQuebraAntes = true) {

  $aDataHora 	= getdate();
  $sQuebraAntes = $lQuebraAntes ? "\n" : "";

  if ($lLogDataHora) {
    $sOutputLog = sprintf("%s[%02d/%02d/%04d %02d:%02d:%02d] %s", $sQuebraAntes, $aDataHora ["mday"], $aDataHora ["mon"], $aDataHora ["year"], $aDataHora ["hours"], $aDataHora ["minutes"], $aDataHora ["seconds"], $sLog);
  } else {
    $sOutputLog = sprintf("%s%s", $sQuebraAntes, $sLog);
  }

  // Se habilitado saida na tela...
  if ($iTipo == 0 or $iTipo == 1) {
    echo $sOutputLog;
  }

  // Se habilitado saida para arquivo...
  if ($iTipo == 0 or $iTipo == 2) {
    if (! empty($sArquivo)) {
      $fd = fopen($sArquivo, "a+");
      if ($fd) {
        fwrite($fd, $sOutputLog);
        fclose($fd);
      }
    }
  }

  return $aDataHora;

}


/**
 * Função que exibe na tela a quantidade de registros processados
 * e a quandidade de memória utilizada
 *
 * @param integer $iInd 		 Indice da linha que está sendo processada
 * @param integer $iTotalLinhas  Total de linhas a processar
 * @param integer $iParamLog     Caso seja passado true é exibido na tela
 */
function logProcessamento($iInd,$iTotalLinhas,$iParamLog){

  $nPercentual = round((($iInd + 1) / $iTotalLinhas) * 100, 2);
  $nMemScript  = (float)round( (memory_get_usage()/1024 ) / 1024,2);
  $sMemScript  = $nMemScript ." Mb";
  $sMsg        = "".($iInd+1)." de {$iTotalLinhas} Processando {$nPercentual} %"." Total de memoria utilizada : {$sMemScript} ";
  $sMsg        = str_pad($sMsg,100," ",STR_PAD_RIGHT);
  db_log($sMsg."\r",null,$iParamLog,true,false);

}


/**
 * Imprime o título do log
 *
 * @param string  $sTitulo
 * @param boolean $iParamLog  Caso seja passado true é exibido na tela
 */
function db_logTitulo($sTitulo="",$sArquivoLog="",$iParamLog=0) {

  db_log("",$sArquivoLog,$iParamLog);
  db_log("//".str_pad($sTitulo,85,"-",STR_PAD_BOTH)."//",$sArquivoLog,$iParamLog);
  db_log("",$sArquivoLog,$iParamLog);
  db_log("",$sArquivoLog,$iParamLog);

}


/**
 * Verifica de existe alguma diferença entre os dois objetos apartir das
 * propriedades do primeiro objeto passado por parâmetro
 *
 * @param  objetc $oObject1
 * @param  object $oObject2
 * @return boolean
 */
function hasDiffObject($oObject1,$oObject2){

  $aPropriedades = get_object_vars($oObject1);
  $lDiff 	  	   = false;


  foreach ( $aPropriedades as $sNome => $sValor ) {

  	if ( isset($oObject1->$sNome) && isset($oObject2->$sNome) ){
		  if ( $oObject1->$sNome != $oObject2->$sNome ) {
	      $lDiff = true;
   	  }
  	}

  }

  return $lDiff;

}