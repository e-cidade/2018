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

/**
 * Programa de integracao com WEBISS
 * Para primeira execução popular a Tabela issbaseintegracaoexterna com as inscrições da tabela issbase
 */


/**
 *  A variável iParamLog define o tipo de log que deve ser gerado :
 *  0 -  Imprime log na tela e no arquivo
 *  1 - Imprime log somente da tela
 *  2 - Imprime log somente no arquivo
 */

$iParamLog = 0;

if ( $iParamLog == 1 ) {
  $sArquivoLog = null;
} else {
  $sArquivoLog = "integracao_externa/webiss/log/processamento_WebISS".date("Ymd_His").".log";
}


// Declarando variáveis necessárias para que a inclusão das bibliotecas não retorne mensagens
$HTTP_SERVER_VARS['HTTP_HOST']      = '';
$HTTP_SERVER_VARS['PHP_SELF']       = '';
$HTTP_SERVER_VARS["HTTP_REFERER"]   = '';
$HTTP_POST_VARS                     = array();
$HTTP_GET_VARS                      = array();

define('DB_BIBLIOT','');

require_once('integracao_externa/webiss/libs/dbportal.constants.php');
require_once(DB_MODEL."model/configuracao/TraceLog.model.php");
require_once('integracao_externa/webiss/libs/db_conecta.php');
require_once('integracao_externa/webiss/libs/databaseVersioning.php');
require_once('integracao_externa/webiss/libs/SQLBaseIntegracao.php');

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_sql.php");

require_once(DB_LIBS . "std/label/rotulo.php");
require_once(DB_LIBS . "std/label/RotuloDB.php");

require_once("fpdf151/fpdf.php");
require_once("fpdf151/impcarne.php");
@require_once("fpdf151/scpdf.php");

require_once("model/regraEmissao.model.php");
require_once("model/recibo.model.php");
require_once("model/dataManager.php");

require_once("classes/db_recibopaga_classe.php");
require_once("classes/db_cancdebitos_classe.php");
require_once("classes/db_cancdebitosproc_classe.php");
require_once("classes/db_cancdebitosreg_classe.php");
require_once("classes/db_cancdebitosprocreg_classe.php");
require_once("classes/db_cancdebitosconcarpeculiar_classe.php");
require_once("classes/db_cancdebitosprocconcarpeculiar_classe.php");
require_once("classes/db_arrecant_classe.php");
require_once("classes/db_arrenumcgm_classe.php");
require_once("classes/db_arrebanco_classe.php");
require_once("classes/db_numpref_classe.php");
require_once("classes/db_arrehist_classe.php");
require_once("classes/db_issvar_classe.php");
require_once("classes/db_issvarsemmov_classe.php");
require_once("classes/db_issvarsemmovreg_classe.php");
require_once("classes/db_issplan_classe.php");
require_once("classes/db_issplannumpre_classe.php");

require_once("libs/exceptions/ParameterException.php");
require_once("libs/exceptions/BusinessException.php");
require_once("libs/exceptions/DBException.php");


require_once("model/cancelamentoDebitos.model.php");
$oCancelaDebito = new cancelamentoDebitos();

require_once("classes/db_arreinscr_classe.php");
$clarreinscr = new cl_arreinscr();

require_once("classes/db_arrecad_classe.php");
$clarrecad = new cl_arrecad();

db_putsession("DB_instit", 1);
db_putsession("DB_anousu", 2012);

$lErro      = false;
$dtDataHoje = date("Y-m-d");
$iAnoUsu 	  = date("Y");

/**
 *  Inicia sessão e transação
 */
$conn = $connOrigem;
db_query($conn ,"BEGIN;");
db_query($conn ,"select fc_startsession();");
db_query($connDestino,"BEGIN;");


try {

	/**
	 * Validando se pagamento parcial esta ativado
	 */
	$oDaoNumpref = new cl_numpref();
	$rsNumpref   = $oDaoNumpref->sql_record($oDaoNumpref->sql_query_file(db_getsession('DB_anousu'),db_getsession('DB_instit')));

	if (!$rsNumpref || $oDaoNumpref->numrows == 0) {
		throw new Exception('Não foi configurado paramêtros da arrecadação para o ano informado');
	}

	$lPagamentoParcial = db_utils::fieldsMemory($rsNumpref, 0)->k03_pgtoparcial;

	if($lPagamentoParcial == 'f') {
		throw new Exception('Pagamento parcial não ativado para a instituição. Nâo é possível prosseguir com o procedimento.');
	}

  /**
   *  Verifica se existem atualizações de base de dados
   *  e as aplica na mesma
   */
	$rsUpgradeDatabase = upgradeDatabase($connDestino,'integracao_externa/webiss/');

	if (!$rsUpgradeDatabase) {
		throw new Exception("Falha ao atualizar base de dados!");
	}

	$sSqlInstit  = " select fc_putsession('DB_instit',( select codigo                        ";
  $sSqlInstit .= "                                      from db_config                     ";
  $sSqlInstit .= "                                      where prefeitura is true limit 1)::text) ";
	$rsInstit    = db_query($conn,$sSqlInstit);

	if ( !$rsInstit ) {
		throw new Exception('Instituição não definida!');
	} else {

		$sSqlConsultaInstit = "select fc_getsession('DB_instit') as instit ";
		$rsConsultaInstit   = db_query($conn,$sSqlConsultaInstit);

	  db_putsession('DB_instit',db_utils::fieldsMemory($rsConsultaInstit,0)->instit);
	}

	db_putsession('DB_acessado'  ,'1');
	db_putsession('DB_datausu'   ,time());
	db_putsession('DB_anousu'    ,date('Y',time()));
	db_putsession('DB_id_usuario','1');


  $oRegraEmissao     = new regraEmissao(null,18,db_getsession('DB_instit'),$dtDataHoje);

  $sSqlBancoAgencia  = " select case                                                                                                          ";
  $sSqlBancoAgencia .= "          when bcocob.db89_db_bancos is not null then bcocob.db89_db_bancos                                           ";
  $sSqlBancoAgencia .= "          else bcoarr.db89_db_bancos                                                                                  ";
  $sSqlBancoAgencia .= "        end as banco,                                                                                                 ";
  $sSqlBancoAgencia .= "        case                                                                                                          ";
  $sSqlBancoAgencia .= "          when bcocob.db89_codagencia is not null then bcocob.db89_codagencia                                         ";
  $sSqlBancoAgencia .= "          else bcoarr.db89_codagencia                                                                                 ";
  $sSqlBancoAgencia .= "        end as agencia                                                                                                ";
  $sSqlBancoAgencia .= "   from cadconvenio                                                                                                   ";
  $sSqlBancoAgencia .= "        left join conveniocobranca    on conveniocobranca.ar13_cadconvenio    = cadconvenio.ar11_sequencial           ";
  $sSqlBancoAgencia .= "        left join bancoagencia bcocob on bcocob.db89_sequencial               = conveniocobranca.ar13_bancoagencia    ";
  $sSqlBancoAgencia .= "        left join convenioarrecadacao on convenioarrecadacao.ar14_cadconvenio = cadconvenio.ar11_sequencial           ";
  $sSqlBancoAgencia .= "        left join bancoagencia bcoarr on bcoarr.db89_sequencial               = convenioarrecadacao.ar14_bancoagencia ";
  $sSqlBancoAgencia .= "  where cadconvenio.ar11_sequencial = ".$oRegraEmissao->getConvenio();

  $rsBancoAgencia    = db_query($conn,$sSqlBancoAgencia);

  if ( !$rsBancoAgencia || pg_num_rows($rsBancoAgencia) == 0 ) {
    throw new Exception("Convênio não configurado!");
  }

  $oBancoAgencia     = db_utils::fieldsMemory($rsBancoAgencia,0);



	/**
	 *  A integração dos dados é feita por COPY deixando o script muito mais rápido do que quando
	 *  trabalhado com INSERT. Para facilitar a integração é utilizado a classe tableDataManager
	 *  que através dos métodos insertValues() e peresist() faz todo o trabalho de inclusão no COPY
	 */

	$iLoteEmpresa = 500;

	$oIntegraCadConfig          = new tableDataManager($connDestino,'integra_cad_config'          ,'sequencial',true,1);
	$oIntegraReciboBaixa        = new tableDataManager($connDestino,'integra_recibo_baixa'        ,'sequencial',true,1);
	$oIntegraReciboBaixaDetalhe = new tableDataManager($connDestino,'integra_recibo_baixa_detalhe','sequencial',true,1);
	$oIntegraCadEmpresa    	    = new tableDataManager($connDestino,'integra_cad_empresa'       	,'sequencial',true,$iLoteEmpresa);
	$oIntegraCadAtividade  	    = new tableDataManager($connDestino,'integra_cad_atividade'     	,'sequencial',true,500);
	$oIntegraCadSocio      	    = new tableDataManager($connDestino,'integra_cad_socio'	       	  ,'sequencial',true,500);
	$oIntegraCadGrafica    	    = new tableDataManager($connDestino,'integra_cad_grafica' 	   	  ,'sequencial',true,500);
	$oIntegraCadEscritorio 	    = new tableDataManager($connDestino,'integra_cad_escritorio'	    ,'sequencial',true,500);
	$oIntegraCadReceita 	      = new tableDataManager($connDestino,'integra_cad_receita'	  	    ,'sequencial',true,500);
	$oIntegraCadInflator 	      = new tableDataManager($connDestino,'integra_cad_inflat'		      ,'sequencial',true,500);
	$oIntegraCadInflatorDetalhe = new tableDataManager($connDestino,'integra_cad_inflat_detalhe'  ,'sequencial',true,500);
	$oIntegraCadJM				      = new tableDataManager($connDestino,'integra_cad_jm'      			  ,'sequencial',true,500);
	$oIntegraRecJM				      = new tableDataManager($connDestino,'integra_rec_jm'			        ,'sequencial',true,500);
	$oIntegraEmpresaAtividade   = new tableDataManager($connDestino,'integra_empresa_atividade'   ,'sequencial',true,500);
	$oIntegraEmpresaSocio 	    = new tableDataManager($connDestino,'integra_empresa_socio'	      ,'sequencial',true,500);
	$oIntegraEmpresaSimples     = new tableDataManager($connDestino,'integra_empresa_simples'	    ,'sequencial',true,500);
	$oIntegraEmpresaEscritorio  = new tableDataManager($connDestino,'integra_empresa_escritorio'  ,'sequencial',true,500);
	$oIntegraEmpresaAidof	      = new tableDataManager($connDestino,'integra_empresa_aidof' 	    ,'sequencial',true,500);
	$oIntegraEmpresaEstimativa  = new tableDataManager($connDestino,'integra_empresa_estimativa'  ,'sequencial',true,500);
	$oIntegraEstimativaDetalhe  = new tableDataManager($connDestino,'integra_estimativa_detalhe'  ,'sequencial',true,500);


  /**
   *  Consulta Código do IBGE que será utilizado em todas tabelas
   */
  $sSqlConfig  = " select db_cepmunic.db10_codibge,                                                             ";
  $sSqlConfig .= "        db_config.munic                                                                       ";
  $sSqlConfig .= "   from db_config                                                                             ";
  $sSqlConfig .= "        inner join db_cepmunic on trim(db_cepmunic.db10_munic) = trim(upper(db_config.munic)) ";
  $sSqlConfig .= "  where db_config.prefeitura is true                                                          ";
  $sSqlConfig .= "    and trim(db_cepmunic.db10_codibge::text) != ''                                                  ";

  $rsConfig    = db_query($conn,$sSqlConfig) or die("Erro:{$sSqlConfig}");


  if ( pg_num_rows($rsConfig) > 0 ) {

    $oConfig  = db_utils::fieldsMemory($rsConfig,0);
    $iCodIBGE = $oConfig->db10_codibge;
    $sMunic   = $oConfig->munic;
  } else {
    throw new Exception("ERRO-1: Código do IBGE não cadastrado!");
  }

  /**
   *  Consulta todas os cadastro de empresas eventuais da base de destino
   */
  db_logTitulo(" IMPORTA CADASTRO EVENTUAIS",$sArquivoLog,$iParamLog);


  $sSqlIntegraCadastro = " select sequencial,
                                  nome,
                                  cpf_cnpj,
                                  cidade,
                                  logradouro,
                                  numero,
                                  complemento,
                                  bairro,
                                  estado,
                                  cep,
                                  ddd_fone||telefone as telefone,
                                  ddd_fax||fax       as fax,
                                  email,
                                  inscricao_estadual
                             from integra_cadastro
                            where processado is false";

  $rsIntegraCadastro      = db_query($connDestino,$sSqlIntegraCadastro);
  $iLinhasIntegraCadastro = pg_num_rows($rsIntegraCadastro);

  if ( $iLinhasIntegraCadastro > 0 ) {

  	db_log("Total de Registros Encontrados : {$iLinhasIntegraCadastro}",$sArquivoLog,$iParamLog);
  	db_log("\n",$sArquivoLog,1);

  	for ( $iInd=0; $iInd < $iLinhasIntegraCadastro; $iInd++ ) {

      $oIntegraCadastro = db_utils::fieldsMemory($rsIntegraCadastro,$iInd);

      logProcessamento($iInd,$iLinhasIntegraCadastro,$iParamLog);

      $sSqlVerificaCgm = " select z01_nome   as nome,
                                  z01_cgccpf as cpf_cnpj,
                                  z01_munic  as cidade,
                                  z01_ender  as logradouro,
                                  z01_numero as numero,
                                  z01_compl  as complemento,
                                  z01_bairro as bairro,
                                  z01_uf     as estado,
                                  z01_cep    as cep,
                                  z01_telef  as telefone,
                                  z01_fax    as fax,
                                  z01_email  as email,
                                  z01_incest as inscricao_estadual
                             from cgm
                            where z01_cgccpf = '$oIntegraCadastro->cpf_cnpj'";

      $rsVerificaCgm  = db_query($conn,$sSqlVerificaCgm);

      if (pg_num_rows($rsVerificaCgm) > 0) {

        $sSqlAlteraIntegraCadastro = " update integra_cadastro
		                                      set processado = true
		                                    where sequencial = {$oIntegraCadastro->sequencial}";

		    $rsAlteraIntegraCadastro   = db_query($connDestino,$sSqlAlteraIntegraCadastro);

		    if (!$rsAlteraIntegraCadastro) {
		      throw new Exception("ERRO-55: ".pg_last_error($connDestino)." ".$sSqlAlteraIntegraCadastro);
		    }

        $sMsgLog  = "CNPJ {$oIntegraCadastro->cpf_cnpj} não incluído no sistema ";
        $sMsgLog .= "Motivo: CGM com CPF/CNPJ já existente !";
        db_log($sMsgLog,$sArquivoLog,2);

        continue;
      }

      $sNumero           = trim($oIntegraCadastro->numero);
      $sComplemento      = $oIntegraCadastro->complemento;
      $lOutrosCaracteres = preg_match("/[^\d]/", $sNumero);

      if ( $lOutrosCaracteres ) {

        $sComplemento = $sNumero . " " . $sComplemento;
        $sNumero      = 0;
      }

      $sSqlInsereCgm = " insert into cgm ( z01_numcgm,
                                           z01_nome,
                                           z01_cgccpf,
                                           z01_munic,
                                           z01_ender,
                                           z01_numero,
                                           z01_compl,
                                           z01_bairro,
                                           z01_uf,
                                           z01_cep,
                                           z01_telef,
                                           z01_fax,
                                           z01_email,
                                           z01_incest
                                         ) values (
                                           nextval('cgm_z01_numcgm_seq'),
																					 ".dbValida(pg_escape_string(substr($oIntegraCadastro->nome              ,0,40)),'string').",
																			     ".dbValida(pg_escape_string($oIntegraCadastro->cpf_cnpj                       ),'string').",
																					 ".dbValida(pg_escape_string(substr($oIntegraCadastro->cidade            ,0,40)),'string').",
																					 ".dbValida(pg_escape_string($oIntegraCadastro->logradouro                     ),'string').",
																					 ".dbValida(pg_escape_string($sNumero                                          ),'int'   ).",
																					 ".dbValida(pg_escape_string(substr($sComplemento                        ,0,20)),'string').",
																					 ".dbValida(pg_escape_string(substr($oIntegraCadastro->bairro            ,0,40)),'string').",
																					 ".dbValida(pg_escape_string($oIntegraCadastro->estado                         ),'string').",
																					 ".dbValida(pg_escape_string($oIntegraCadastro->cep                            ),'string').",
																					 ".dbValida(pg_escape_string(substr($oIntegraCadastro->telefone          ,0,12)),'string').",
																					 ".dbValida(pg_escape_string($oIntegraCadastro->fax                            ),'string').",
																					 ".dbValida(pg_escape_string($oIntegraCadastro->email                          ),'string').",
																					 ".dbValida(pg_escape_string(substr($oIntegraCadastro->inscricao_estadual,0,15)),'string')."
																				);";

      $rsInsereCgm = db_query($conn,$sSqlInsereCgm);

      if (!$rsInsereCgm) {
      	throw new Exception("ERRO-54: ".pg_last_error($conn)." ".$sSqlInsereCgm);
      }


	  	$sSqlAlteraIntegraCadastro = " update integra_cadastro
	  	                                  set processado = true
	  	                                where sequencial = {$oIntegraCadastro->sequencial}";

      $rsAlteraIntegraCadastro   = db_query($connDestino,$sSqlAlteraIntegraCadastro);

  	  if (!$rsAlteraIntegraCadastro) {
        throw new Exception("ERRO-55: ".pg_last_error($connDestino)." ".$sSqlAlteraIntegraCadastro);
      }
  	}
  } else {
 	  db_log("Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
  }

	/**
	 *  Consulta todas as receitas da base de origem
	 */
	db_logTitulo(" PROCESSA RECEITAS ",$sArquivoLog,$iParamLog);

	$sSqlReceitaPrefeitura  = " select k02_codigo as codigo,   ";
	$sSqlReceitaPrefeitura .= "        k02_descr  as descricao ";
	$sSqlReceitaPrefeitura .= "   from tabrec  				         ";

	$rsReceitaPrefeitura      = db_query($conn,$sSqlReceitaPrefeitura);
	$iLinhasReceitaPrefeitura = pg_num_rows($rsReceitaPrefeitura);

	if ( $iLinhasReceitaPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasReceitaPrefeitura}",$sArquivoLog,$iParamLog);
    db_log("\n",$sArquivoLog,1);

		for ( $iInd=0; $iInd < $iLinhasReceitaPrefeitura; $iInd++ ) {

			$oReceitaPrefeitura = db_utils::fieldsMemory($rsReceitaPrefeitura,$iInd);

		  /**
		   *  Verifica se o registro já existe na base de destino apartir do
		   *  código da receita utilizado na base de origem
		   */
		  $sSqlReceitaDestino  = " select * 									                   ";
		  $sSqlReceitaDestino .= "   from integra_cad_receita					           ";
		  $sSqlReceitaDestino .= "  where codigo = {$oReceitaPrefeitura->codigo} ";
		  $sSqlReceitaDestino .= "  order by sequencial desc limit 1			       ";

		  $rsReceitaDestino    = db_query($connDestino,$sSqlReceitaDestino);

		  logProcessamento($iInd,$iLinhasReceitaPrefeitura,$iParamLog);

		  /**
		   *  Caso exista algum registro na base de destino então é comparado os dados
		   *  com a base de origem através da função hasDiffObject
		   */
		  if ( pg_num_rows($rsReceitaDestino) > 0 ) {

		    $oReceitaDestino = db_utils::fieldsMemory($rsReceitaDestino,0);

		    if ( !hasDiffObject($oReceitaPrefeitura,$oReceitaDestino) ) {
		      continue;
		    }
		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oReceitaPrefeitura->munic_ibge  = $iCodIBGE;
		  $oReceitaPrefeitura->dataimp     = $dtDataHoje;
		  $oReceitaPrefeitura->horaimp     = db_hora();
		  $oReceitaPrefeitura->processado	 = "f";
		  $oReceitaPrefeitura->tipo 		   = "";

		  /**
		   *  Atribui o valores da base de origem ao objeto tableDataManager
		   */
		  $oIntegraCadReceita->setByLineOfDBUtils($oReceitaPrefeitura);


		  try {
		    $oIntegraCadReceita->insertValue();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-2: {$eException->getMessage()}");
		  }

		}

		try {
		  $oIntegraCadReceita->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-3: {$eException->getMessage()}");
		}

	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}


  /**
   *  Define os dados da tabela integra_cad_config
   */
  db_logTitulo(" IMPORTA CONFIGURAÇÕES",$sArquivoLog,$iParamLog);

  $lInsereConfig = true;

  $sSqlParIssqn             = " select k02_codigo as cod_rec_iss,                                   ";
  $sSqlParIssqn            .= "        k02_recjur as cod_rec_jur,                                   ";
  $sSqlParIssqn            .= "        k02_recmul as cod_rec_mult                                   ";
  $sSqlParIssqn            .= "   from parissqn                                                     ";
  $sSqlParIssqn            .= "        inner join tabrec on tabrec.k02_codigo = parissqn.q60_receit ";

  $rsParIssqn               = db_query($conn,$sSqlParIssqn);

  $oDaoDBConfPlan           = db_utils::getDao("db_confplan");
  $sSqlConfiguracaoPlanilha = $oDaoDBConfPlan->sql_query_file();
  $rsConfiguracaoPlanilha   = $oDaoDBConfPlan->sql_record($sSqlConfiguracaoPlanilha);

  if ( $oDaoDBConfPlan->erro_status == "0" ) {
    throw new Exception( $oDaoDBConfPlan->erro_msg );
  }


  if ( pg_num_rows($rsParIssqn) > 0 ) {
    $oParIssqn = db_utils::fieldsMemory($rsParIssqn,0);
  } else {
    throw new Exception('Parâmetros do ISS não configurado!');
  }

  $sSqlNumConvenio  = " select ar16_convenio   ";
  $sSqlNumConvenio .= "   from cadarrecadacao  ";

  $rsNumConvenio    = db_query($conn,$sSqlNumConvenio);

  if ( pg_num_rows($rsNumConvenio) > 0 ) {
    $sNumConvenio = db_utils::fieldsMemory($rsNumConvenio,0)->ar16_convenio;
  } else {
    throw new Exception('Parâmetros do convênio arrecadação não configurados!');
  }

  $iReceitaDebitoPrestador = $oParIssqn->cod_rec_iss;
  $iReceitaDebitoTomador   = db_utils::fieldsMemory($rsConfiguracaoPlanilha,0)->w10_receit;

  $oCadConfig->cod_rec_iss          = $iReceitaDebitoPrestador;
  $oCadConfig->cod_rec_jur          = $oParIssqn->cod_rec_jur;
  $oCadConfig->cod_rec_mult         = $oParIssqn->cod_rec_mult;
  $oCadConfig->cod_rec_iss_retido   = $iReceitaDebitoTomador;
  $oCadConfig->num_convenio         = $sNumConvenio;

  $sSqlIntegraCadConfig  = " select *                          ";
  $sSqlIntegraCadConfig .= "   from integra_cad_config         ";
  $sSqlIntegraCadConfig .= "  order by sequencial desc limit 1 ";

  $rsIntegraCadConfig    = db_query($connDestino,$sSqlIntegraCadConfig);

  if ( pg_num_rows($rsIntegraCadConfig) > 0 ) {

    $oCadConfigDestino = db_utils::fieldsMemory($rsIntegraCadConfig,0);

    if ( !hasDiffObject($oCadConfig,$oCadConfigDestino) ) {
      $lInsereConfig = false;
    }

  }


  if ( $lInsereConfig ) {

    $oCadConfig->tipo_convenio          = '0';
    $oCadConfig->faixa_inicial_numdoc   = 7000000;
    $oCadConfig->faixa_final_numdoc     = 7999999;
    $oCadConfig->faixa_inicial_numbanco = 1;
    $oCadConfig->faixa_final_numbanco   = 9999999;
    $oCadConfig->munic_ibge             = $iCodIBGE;
    $oCadConfig->dataimp                = $dtDataHoje;
    $oCadConfig->horaimp                = db_hora();
    $oCadConfig->processado             = "f";

    /**
     *  Atribui o valores da base de origem ao objeto tableDataManager
     */
    $oIntegraCadConfig->setByLineOfDBUtils($oCadConfig);


    try {
      $oIntegraCadConfig->insertValue();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    try {
      $oIntegraCadConfig->persist();
    } catch ( Exception $eException ) {
      throw new Exception("ERRO-0: {$eException->getMessage()}");
    }

    db_log("Incluído nova configuração",$sArquivoLog,$iParamLog);

  } else {
    db_log("Nenhum registro alterado!",$sArquivoLog,$iParamLog);
  }


	db_logTitulo(" PROCESSA INFLATOR ",$sArquivoLog,$iParamLog);

	$sSqlInflatorPrefeitura  = " select i01_codigo as sigla,	     ";
	$sSqlInflatorPrefeitura .= "        i01_descr  as descricao,   ";
	$sSqlInflatorPrefeitura .= "        i01_dm     as tipo_lancam, ";
	$sSqlInflatorPrefeitura .= "        coalesce(i01_tipo,'0') as tipo_calc  ";
	$sSqlInflatorPrefeitura .= "   from inflan  					  	               ";

	$rsInflatorPrefeitura      = db_query($conn,$sSqlInflatorPrefeitura);
	$iLinhasInflatorPrefeitura = pg_num_rows($rsInflatorPrefeitura);
	$aListaInflator 		       = array();

	if ( $iLinhasInflatorPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasInflatorPrefeitura}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

		for ( $iInd=0; $iInd < $iLinhasInflatorPrefeitura; $iInd++ ) {

			$oInflatorPrefeitura = db_utils::fieldsMemory($rsInflatorPrefeitura,$iInd);

		  /**
		   *  Verifica se já existe o registro na base de destino apartir do
		   *  sigla do inflator utilizado na base de origem
		   */
		  $sSqlInflatorDestino  = " select * 									                    ";
		  $sSqlInflatorDestino .= "   from integra_cad_inflat					            ";
		  $sSqlInflatorDestino .= "  where sigla = '{$oInflatorPrefeitura->sigla}'";
		  $sSqlInflatorDestino .= "  order by sequencial desc limit 1			        ";

		  $rsInflatorDestino    = db_query($connDestino,$sSqlInflatorDestino);

		  logProcessamento($iInd,$iLinhasInflatorPrefeitura,$iParamLog);

		  /**
		   *  Caso exista algum registro na base de destino então é comparado os dados
		   *  com a base de origem através da função hasDiffObject
		   */
		  if ( pg_num_rows($rsInflatorDestino) > 0 ) {

		    $oInflatorDestino = db_utils::fieldsMemory($rsInflatorDestino,0);

		    if ( !hasDiffObject($oInflatorPrefeitura,$oInflatorDestino) ) {
		      continue;
		    }

		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oInflatorPrefeitura->munic_ibge    = $iCodIBGE;
		  $oInflatorPrefeitura->dataimp       = $dtDataHoje;
		  $oInflatorPrefeitura->horaimp       = db_hora();
		  $oInflatorPrefeitura->processado	  = "f";
		  $oInflatorPrefeitura->tipo_atualiza = "0";

		  /**
		   *  Atribui o valores da base de origem ao objeto tableDataManager
		   */
		  $oIntegraCadInflator->setByLineOfDBUtils($oInflatorPrefeitura);


		  try {
		    $iCodInflator = $oIntegraCadInflator->insertValue();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-4: {$eException->getMessage()}");
		  }

		  /**
		   *  Como a integração é feita com COPY é necessário guardar o código gerado
		   *  em um array de objetos para posteriormente popular as tabelas de ligação
		   */
		  $oInflator = new stdClass();
		  $oInflator->iCodInflatorOrigem  = $oInflatorPrefeitura->sigla;
		  $oInflator->iCodInflatorDestino = $iCodInflator;
		  $aListaInflator[] = $oInflator;

		}

		try {
		  $oIntegraCadInflator->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-5: {$eException->getMessage()}");
		}



		foreach ( $aListaInflator as $oInflator ) {

		  $sSqlInflatorDetalhePrefeitura  = " select i02_data  as data, 						                ";
		  $sSqlInflatorDetalhePrefeitura .= "        i02_valor as valor 						                ";
		  $sSqlInflatorDetalhePrefeitura .= "   from infla 			  							                    ";
		  $sSqlInflatorDetalhePrefeitura .= "  where i02_codigo = '{$oInflator->iCodInflatorOrigem}'";

		  $rsInflatorDetalhePrefeitura      = db_query($conn,$sSqlInflatorDetalhePrefeitura);
	    $iLinhasInflatorDetalhePrefeitura = pg_num_rows($rsInflatorDetalhePrefeitura);

	    for ( $iInd=0; $iInd < $iLinhasInflatorDetalhePrefeitura; $iInd++ ) {

	    	$oInflatorDetalhePrefeitura = db_utils::fieldsMemory($rsInflatorDetalhePrefeitura,$iInd);

		    $sSqlInflatorDetalheDestino  = " select * 									 			                               ";
		    $sSqlInflatorDetalheDestino .= "   from integra_cad_inflat_detalhe	  			  			             ";
		    $sSqlInflatorDetalheDestino .= "  where integra_cad_inflat = '{$oInflator->iCodInflatorDestino}' ";
		  	$sSqlInflatorDetalheDestino .= "  	and data			   = '{$oInflatorDetalhePrefeitura->data}'     ";
		    $sSqlInflatorDetalheDestino .= "  order by sequencial desc limit 1			 	   			               ";

		    $rsInflatorDetalheDestino    = db_query($connDestino,$sSqlInflatorDetalheDestino);

		    /**
		     *  Caso exista algum registro na base de destino então é comparado os dados
		     *  com a base de origem através da função hasDiffObject
		     */
		    if ( pg_num_rows($rsInflatorDetalheDestino) > 0 ) {

		      $oInflatorDetalheDestino = db_utils::fieldsMemory($rsInflatorDetalheDestino,0);

		      if ( !hasDiffObject($oInflatorDetalhePrefeitura,$oInflatorDetalheDestino) ) {
		        continue;
		      }

		    }

		    /**
		     *  Define todas propriedades que não retornam do SQL
		     */
		    $oInflatorDetalhePrefeitura->integra_cad_inflat = $oInflator->iCodInflatorDestino;
		    $oInflatorDetalhePrefeitura->munic_ibge 	      = $iCodIBGE;
		    $oInflatorDetalhePrefeitura->dataimp      	    = $dtDataHoje;
		    $oInflatorDetalhePrefeitura->horaimp       	    = db_hora();
		    $oInflatorDetalhePrefeitura->processado	  	    = "f";

		    /**
		     *  Atribui o valores da base de origem ao objeto tableDataManager
		     */
		    $oIntegraCadInflatorDetalhe->setByLineOfDBUtils($oInflatorDetalhePrefeitura);

		    try {
		      $oIntegraCadInflatorDetalhe->insertValue();
		    } catch ( Exception $eException ) {
		    	throw new Exception("ERRO-6: {$eException->getMessage()}");
		    }

		  }

		  try {
		    $oIntegraCadInflatorDetalhe->persist();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-7: {$eException->getMessage()}");
		  }
		  $sSqlJMPrefeitura  = " select k02_codjm  as codigo_jm, 		   		                        \n";
		  $sSqlJMPrefeitura .= "        k02_juros  as juros, 			   		                          \n";
		  $sSqlJMPrefeitura .= "        k02_jurdia as jurdia, 			   		                        \n";
		  $sSqlJMPrefeitura .= "        k02_limmul as limite_multa_diaria, 	                      \n";
		  $sSqlJMPrefeitura .= "        k02_sabdom as sabdom, 			   		                        \n";
		  $sSqlJMPrefeitura .= "        k02_corven as corr_venc,		   		                        \n";
		  $sSqlJMPrefeitura .= "        (select k140_multa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "          limit 1 offset 0) as multa_1,                           \n";
		  $sSqlJMPrefeitura .= "        (select k140_multa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "        limit 1 offset 1) as multa_2,                             \n";
		  $sSqlJMPrefeitura .= "        (select k140_multa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "          limit 1 offset 2) as multa_3,                           \n";
		  $sSqlJMPrefeitura .= "        (select k140_faixa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "          limit 1 offset 0) as multa_faixa_1,                     \n";
		  $sSqlJMPrefeitura .= "        (select k140_faixa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "          limit 1 offset 1) as multa_faixa_2,                     \n";
		  $sSqlJMPrefeitura .= "        (select k140_faixa                                        \n";
		  $sSqlJMPrefeitura .= "           from tabrecjmmulta                                     \n";
		  $sSqlJMPrefeitura .= "          where tabrecjmmulta.k140_tabrecjm = tabrecjm.k02_codjm  \n";
		  $sSqlJMPrefeitura .= "          order by k140_multa                                     \n";
		  $sSqlJMPrefeitura .= "          limit 1 offset 2) as multa_faixa_3                      \n";
		  $sSqlJMPrefeitura .= "   from tabrecjm  						  		                              \n";
		  $sSqlJMPrefeitura .= "  where k02_corr = '{$oInflator->iCodInflatorOrigem}'             \n";


		  $rsJMPrefeitura      = db_query($conn,$sSqlJMPrefeitura);
	    $iLinhasJMPrefeitura = pg_num_rows($rsJMPrefeitura);
		  $aListaJM 	         = array();


		  for ( $iInd=0; $iInd < $iLinhasJMPrefeitura; $iInd++ ) {

		  	$oJMPrefeitura = db_utils::fieldsMemory($rsJMPrefeitura,$iInd);

		    /**
		     *  Verifica se já existe o registro na base de destino apartir do
		     *  código de juro e multa utilizado na base de origem
		     */
		    $sSqlJMDestino  = " select * 									                     ";
		    $sSqlJMDestino .= "   from integra_cad_jm		   	  			           ";
		    $sSqlJMDestino .= "  where codigo_jm = {$oJMPrefeitura->codigo_jm} ";
		    $sSqlJMDestino .= "  order by sequencial desc limit 1			         ";

		    $rsJMDestino    = db_query($connDestino,$sSqlJMDestino);

		    /**
		     *  Caso exista algum registro na base de destino então é comparado os dados
		     *  com a base de origem através da função hasDiffObject
		     */
		    if ( pg_num_rows($rsJMDestino) > 0 ) {

		      $oJMDestino = db_utils::fieldsMemory($rsJMDestino,0);

		      if ( !hasDiffObject($oJMPrefeitura,$oJMDestino) ) {
		        continue;
		      }

		    }

		    /**
		     *  Define todas propriedades que não retornam do SQL
		     */
		    $oJMPrefeitura->integra_cad_inflat = $oInflator->iCodInflatorDestino;
		    $oJMPrefeitura->munic_ibge     	   = $iCodIBGE;
		    $oJMPrefeitura->dataimp       	   = $dtDataHoje;
		    $oJMPrefeitura->horaimp      	     = db_hora();
		    $oJMPrefeitura->multa_diaria 	     = "0";
		    $oJMPrefeitura->processado	  	   = "f";

		    /**
		     *  Atribui o valores da base de origem ao objeto tableDataManager
		     */
		    $oIntegraCadJM->setByLineOfDBUtils($oJMPrefeitura);


		    try {
		      $iCodJM = $oIntegraCadJM->insertValue();
		    } catch ( Exception $eException ) {
          throw new Exception("ERRO-8: {$eException->getMessage()}");
		    }


		    /**
		     *  Como a integração é feita com COPY é necessário guardar o código gerado
		     *  em um array de objetos para posteriormente popular as tabelas de ligação
		     */
				$oJM = new stdClass();
				$oJM->iCodJMOrigem  = $oJMPrefeitura->codigo_jm;
				$oJM->iCodJMDestino = $iCodJM;
				$aListaJM[] = $oJM;

		  }

		  try {
		    $oIntegraCadJM->persist();
		  } catch ( Exception $eException ) {
        throw new Exception("ERRO-9: {$eException->getMessage()}");
		  }

		  foreach ( $aListaJM as $oJM ) {

			 	$sSqlRecJMPrefeitura  = " select k04_receit as codigo_receita,    ";
				$sSqlRecJMPrefeitura .= " 		   k04_dtini  as data_inicial, 	    ";
				$sSqlRecJMPrefeitura .= "        k04_dtfim  as data_final 		    ";
				$sSqlRecJMPrefeitura .= "   from tabrecregrasjm 				          ";
				$sSqlRecJMPrefeitura .= "  where k04_codjm	= {$oJM->iCodJMOrigem}";


				$rsRecJMPrefeitura      = db_query($conn,$sSqlRecJMPrefeitura);
				$iLinhasRecJMPrefeitura = pg_num_rows($rsRecJMPrefeitura);

				for ( $iInd=0; $iInd < $iLinhasRecJMPrefeitura; $iInd++ ) {

				  $oRecJMPrefeitura = db_utils::fieldsMemory($rsRecJMPrefeitura,$iInd);

			   /**
			    *  Consulta o código da receita na base de destino apartir do código
			    *  da receita da base de origem
			    */
				  $sSqlRecDestino    = " select sequencial 			  	   					             ";
				  $sSqlRecDestino   .= "   from integra_cad_receita  	  					           ";
				  $sSqlRecDestino   .= "  where codigo = {$oRecJMPrefeitura->codigo_receita} ";
				  $sSqlRecDestino   .= "  order by sequencial desc limit 1 					         ";

				  $rsRecDestino 	 = db_query($connDestino,$sSqlRecDestino);

				  if ( pg_num_rows($rsRecDestino) > 0 )  {
					  $iCodReceitaDestino = db_utils::fieldsMemory($rsRecDestino,0)->sequencial;
				  } else {
				  	continue;
				  }

				  $sSqlRecJMDestino  = " select * 									   		                  ";
				  $sSqlRecJMDestino .= "   from integra_rec_jm		   	  					          ";
				  $sSqlRecJMDestino .= "  where integra_cad_receita = {$iCodReceitaDestino} ";
				  $sSqlRecJMDestino .= "    and integra_cad_jm  		= {$oJM->iCodJMDestino} ";
				  $sSqlRecJMDestino .= "  order by sequencial desc limit 1			   	       	";

				  $rsRecJMDestino    = db_query($connDestino,$sSqlRecJMDestino);

				  /**
				   *  Caso exista algum registro na base de destino então é comparado os dados
				   *  com a base de origem através da função hasDiffObject
				   */
				  if ( pg_num_rows($rsRecJMDestino) > 0 ) {

				    $oRecJMDestino = db_utils::fieldsMemory($rsRecJMDestino,0);

				    if ( !hasDiffObject($oRecJMPrefeitura,$oRecJMDestino) ) {
				      continue;
				    }

				  }

				  /**
				   *  Define todas propriedades que não retornam do SQL
				   */
				  $oRecJMPrefeitura->integra_cad_receita = $iCodReceitaDestino;
				  $oRecJMPrefeitura->integra_cad_jm	    = $oJM->iCodJMDestino;
				  $oRecJMPrefeitura->munic_ibge   	   	  = $iCodIBGE;
				  $oRecJMPrefeitura->dataimp       	    = $dtDataHoje;
				  $oRecJMPrefeitura->horaimp      	      = db_hora();
				  $oRecJMPrefeitura->multa_diaria 	      = "";
				  $oRecJMPrefeitura->processado	  	    = "f";

				  /**
				   *  Atribui o valores da base de origem ao objeto tableDataManager
				   */
				  $oIntegraRecJM->setByLineOfDBUtils($oRecJMPrefeitura);

				  try {
				    $oIntegraRecJM->insertValue();
				  } catch ( Exception $eException ) {
            throw new Exception("ERRO-10: {$eException->getMessage()}");
				  }

				}

				try {
				  $oIntegraRecJM->persist();
				} catch ( Exception $eException ) {
					throw new Exception("ERRO-11: {$eException->getMessage()}");
				}

		  }

		}

		unset($aListaJM);
		unset($aListaInflator);


	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}

	/**
	 *  Consulta todas as atividades da base de origem
	 */
	db_logTitulo(" PROCESSA ATIVIDADES ",$sArquivoLog,$iParamLog);

	$aListaAlteracaoAtiv = array();

	$sSqlAtivPrefeitura  = "   select distinct					 				   	   									   		                                  ";
	$sSqlAtivPrefeitura .= "          q07_ativ        			  as atividade,  	   									   		                        ";
	$sSqlAtivPrefeitura .= "          q03_descr       			  as descricao,		   									   		                        ";
	$sSqlAtivPrefeitura .= "          q71_estrutural  			  as codigo_cnae,      									   	                     	  ";
	$sSqlAtivPrefeitura .= "          q71_descr       			  as descricao_cnae,   									   		                      ";
	$sSqlAtivPrefeitura .= "          coalesce(min(q81_valexe),0) as aliqiss	       									   		                    ";
	$sSqlAtivPrefeitura .= "     from tabativ 										   									   		                                    ";
	$sSqlAtivPrefeitura .= "          inner join ativid     	  on ativid.q03_ativ  	   = tabativ.q07_ativ			          		  ";
	$sSqlAtivPrefeitura .= "          left  join ativtipo   	  on ativtipo.q80_ativ     = ativid.q03_ativ					            ";
	$sSqlAtivPrefeitura .= "          left  join tipcalc     	  on tipcalc.q81_codigo    = ativtipo.q80_tipcal				          ";
	$sSqlAtivPrefeitura .= "                                   and tipcalc.q81_tipo      = 1                     			          ";
	$sSqlAtivPrefeitura .= "	        left  join cadcalc        on cadcalc.q85_codigo    = tipcalc.q81_cadcalc				          ";
	$sSqlAtivPrefeitura .= "   					            			     and cadcalc.q85_var is true									                    ";
	$sSqlAtivPrefeitura .= "          left  join atividcnae 	  on atividcnae.q74_ativid        = ativid.q03_ativ					      ";
	$sSqlAtivPrefeitura .= "          left  join cnaeanalitica  on cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica  ";
	$sSqlAtivPrefeitura .= "          left  join cnae		  	    on cnae.q71_sequencial          = cnaeanalitica.q72_cnae			  ";
	$sSqlAtivPrefeitura .= " group by q07_ativ,																					                                        ";
	$sSqlAtivPrefeitura .= " 		      q03_descr,																				                                        ";
	$sSqlAtivPrefeitura .= " 		      q71_estrutural,																			                                      ";
	$sSqlAtivPrefeitura .= " 		      q71_descr 																				                                        ";

	$rsAtivPrefeitura      = db_query($conn,$sSqlAtivPrefeitura);
	$aListaAtivPrefeitura  = db_utils::getCollectionByRecord($rsAtivPrefeitura);
	$iLinhasAtivPrefeitura = count($aListaAtivPrefeitura);

	if ( $iLinhasAtivPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasAtivPrefeitura}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

		foreach ( $aListaAtivPrefeitura as $iInd => $oAtivPrefeitura ) {

		  $lAteracao = false;

		  /**
		   *  Verifica se já existe o registro na base de destino apartir do
		   *  código da atividade utilizado na base de origem
		   */
		  $sSqlAtivDestino  = " select * 										 ";
		  $sSqlAtivDestino .= "   from integra_cad_atividade					 ";
		  $sSqlAtivDestino .= "  where atividade = {$oAtivPrefeitura->atividade} ";
		  $sSqlAtivDestino .= "  order by sequencial desc limit 1				 ";

		  $rsAtivDestino    = db_query($connDestino,$sSqlAtivDestino);

		  logProcessamento($iInd,$iLinhasAtivPrefeitura,$iParamLog);

		  /**
		   *  Caso exista na base de destino então é comparado todos os dados
		   *  com a base de origem
		   */
		  if ( pg_num_rows($rsAtivDestino) > 0 ) {

		    $oAtivDestino = db_utils::fieldsMemory($rsAtivDestino,0);


		    if ( hasDiffObject($oAtivPrefeitura,$oAtivDestino) ) {
		      $lAteracao = true;
		    } else {
		      continue;
		    }

		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oAtivPrefeitura->munic_ibge    = $iCodIBGE;
		  $oAtivPrefeitura->dataimp       = $dtDataHoje;
		  $oAtivPrefeitura->horaimp       = db_hora();
		  $oAtivPrefeitura->codigo_116    = "";
		  $oAtivPrefeitura->descricao_116 = "";
		  $oAtivPrefeitura->processado	   = "f";

		  /**
		   *  Atribui o valores da base de origem ao objeto tableDataManager
		   */
		  $oIntegraCadAtividade->setByLineOfDBUtils($oAtivPrefeitura);


		  try {
		    $iCodAtividade = $oIntegraCadAtividade->insertValue();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-12: {$eException->getMessage()}");
		  }


		  /**
		   *  Como a integração é feita com COPY é necessário guardar o código gerado
		   *  em um array de objetos para posteriormente popular as tabelas de ligação
		   */
		  if ( $lAteracao ) {
		  	$oAtividade = new stdClass();
		  	$oAtividade->iAtivOld  = $oAtivDestino->sequencial;
		  	$oAtividade->iAtivNew  = $iCodAtividade;
		  	$aListaAlteracaoAtiv[] = $oAtividade;

		  }

		}

		try {
		  $oIntegraCadAtividade->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-13: {$eException->getMessage()}");
		}


		/**
		 *  Caso exista alguma alteração então é acertado a chave
		 *  com todas tabelas de ligação
		 */
		if ( count($aListaAlteracaoAtiv) > 0 ) {

		  foreach ( $aListaAlteracaoAtiv as $oAtividade ) {

			  $sSqlAlteraEmpAtiv  = " update integra_empresa_atividade 					             ";
		    $sSqlAlteraEmpAtiv .= "    set integra_cad_atividade = {$oAtividade->iAtivNew} ";
		    $sSqlAlteraEmpAtiv .= "  where integra_cad_atividade = {$oAtividade->iAtivOld} ";

	      if ( !db_query($connDestino,$sSqlAlteraEmpAtiv) ){
	      	throw new Exception("ERRO-14: ".pg_last_error($connDestino)."\n\n {$sSqlAlteraEmpAtiv}");
	      }

		  }

		}

		unset($aListaAlteracaoAtiv);

	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}


	db_logTitulo(" PROCESSA SOCIOS ",$sArquivoLog,$iParamLog);

	$aListaAlteracaoSocio = array();

	$sSqlSocioPrefeitura  = " select distinct on (socios.q95_numcgm)           ";
	$sSqlSocioPrefeitura .= "        q95_numcgm as codigo_socio, 	             ";
	$sSqlSocioPrefeitura .= "        z01_cgccpf as cpf_cnpj,                   ";
	$sSqlSocioPrefeitura .= "        z01_nome   as nome_socio, 			           ";
	$sSqlSocioPrefeitura .= "        j88_sigla  as tipo_logradouro,	         	 ";
	$sSqlSocioPrefeitura .= "        z01_numero as numero, 			 	             ";
	$sSqlSocioPrefeitura .= "        z01_compl  as complemento, 			         ";
	$sSqlSocioPrefeitura .= "        z01_cep    as cep, 					             ";
	$sSqlSocioPrefeitura .= "        z01_uf     as estado, 				             ";
	$sSqlSocioPrefeitura .= "        z01_telef  as telefone, 		               ";
	$sSqlSocioPrefeitura .= "        substr(z01_fax,0,15) as fax,	             ";
	$sSqlSocioPrefeitura .= "        case
		                                 when ruas.j14_nome is not null then ruas.j14_nome
		                                 else cgm.z01_ender
		                               end as logradouro,                                                          ";
	$sSqlSocioPrefeitura .= "        case
	                                   when bairro.j13_descr is not null then bairro.j13_descr
	                                   else cgm.z01_bairro
	                                 end as bairro,                                                              ";
	$sSqlSocioPrefeitura .= "        case
	                                   when db_cgmruas.j14_codigo is not null then '$sMunic'
	                                   else cgm.z01_munic
	                                 end as cidade,                                                              ";
	$sSqlSocioPrefeitura .= "        z01_email  as email 			                                                   ";
	$sSqlSocioPrefeitura .= "   from socios																                                       ";
	$sSqlSocioPrefeitura .= " 		   inner join cgm           on cgm.z01_numcgm          = socios.q95_numcgm	   ";
	$sSqlSocioPrefeitura .= "		     left  join issbase       on issbase.q02_numcgm      = cgm.z01_numcgm		     ";
	$sSqlSocioPrefeitura .= "		     left  join db_cgmruas    on db_cgmruas.z01_numcgm   = cgm.z01_numcgm		     ";
	$sSqlSocioPrefeitura .= "		     left  join ruas          on ruas.j14_codigo         = db_cgmruas.j14_codigo ";
	$sSqlSocioPrefeitura .= "		     left  join ruastipo      on ruastipo.j88_codigo     = ruas.j14_tipo		     ";
	$sSqlSocioPrefeitura .= "        left  join db_cgmbairro on db_cgmbairro.z01_numcgm = cgm.z01_numcgm        ";
	$sSqlSocioPrefeitura .= "        left  join bairro        on bairro.j13_codi         = db_cgmbairro.j13_codi ";

	$rsSocioPrefeitura      = db_query($conn,$sSqlSocioPrefeitura);
	$iLinhasSocioPrefeitura = pg_num_rows($rsSocioPrefeitura);

	if ( $iLinhasSocioPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasSocioPrefeitura}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

		for ( $iInd=0; $iInd < $iLinhasSocioPrefeitura; $iInd++ ) {

			$oSocioPrefeitura = db_utils::fieldsMemory($rsSocioPrefeitura,$iInd);

		  $lAteracao = false;

      if ( !validaCpfCnpj($oSocioPrefeitura->cpf_cnpj)){
        $oSocioPrefeitura->cpf_cnpj    = "0";
        $sMsgLog  = "Sócio código :{$oSocioPrefeitura->codigo_socio} ";
        $sMsgLog .= "CNPJ : {$oSocioPrefeitura->cpf_cnpj} inválido!  ";
        db_log($sMsgLog,$sArquivoLog,2);
      }


		  /**
		   *  Verifica se já existe o registro na base de destino apartir do
		   *  código do sócio utilizado na base de origem
		   */
		  $sSqlSocioDestino  = " select * 						         	                             ";
		  $sSqlSocioDestino .= "   from integra_cad_socio		                               	 ";
		  $sSqlSocioDestino .= "  where codigo_socio = ".trim($oSocioPrefeitura->codigo_socio);
		  $sSqlSocioDestino .= "  order by sequencial desc limit 1	                         ";

		  $rsSocioDestino    = db_query($connDestino,$sSqlSocioDestino);

		  logProcessamento($iInd,$iLinhasSocioPrefeitura,$iParamLog);

		  /**
		   *  Caso exista algum registro na base de destino então é comparado os dados
		   *  com a base de origem através da função hasDiffObject
		   */
		  if ( pg_num_rows($rsSocioDestino) > 0 ) {

		    $oSocioDestino = db_utils::fieldsMemory($rsSocioDestino,0);

		    if ( hasDiffObject($oSocioPrefeitura,$oSocioDestino) ) {
		      $lAteracao = true;
		    } else {
		      continue;
		    }

		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oSocioPrefeitura->munic_ibge    = $iCodIBGE;
		  $oSocioPrefeitura->dataimp       = $dtDataHoje;
		  $oSocioPrefeitura->horaimp       = db_hora();
		  $oSocioPrefeitura->processado	   = "f";
		  $oSocioPrefeitura->ddd_fone      = '';
		  $oSocioPrefeitura->ramal         = '';
		  $oSocioPrefeitura->ddd_fax       = '';

		  /**
		   *  Atribui o valores da base de origem ao objeto tableDataManager
		   */
		  $oIntegraCadSocio->setByLineOfDBUtils($oSocioPrefeitura);


		  try {
		    $iCodSocio = $oIntegraCadSocio->insertValue();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-15: {$eException->getMessage()}");
		  }

		  /**
		   *  Como a integração é feita com COPY é necessário guardar o código gerado
		   *  em um array de objetos para posteriormente popular as tabelas de ligação
		   */
		  if ( $lAteracao ) {
		  	$oSocio = new stdClass();
		  	$oSocio->iSocioOld  = $oSocioDestino->sequencial;
		  	$oSocio->iSocioNew  = $iCodSocio;
		  	$aListaAlteracaoSocio[] = $oSocio;
		  }

		}

		try {
		  $oIntegraCadSocio->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-16: {$eException->getMessage()}");
		}

		/**
		 *  Caso exista alguma alteração então é acertado a chave
		 *  com todas tabelas de ligação
		 */
		if ( count($aListaAlteracaoSocio) > 0 ) {

		  foreach ( $aListaAlteracaoSocio as $oSocio ) {

			  $sSqlAlteraEmpSocio  = " update integra_empresa_socio 					 ";
		    $sSqlAlteraEmpSocio .= "    set integra_cad_socio = {$oSocio->iSocioNew} ";
		    $sSqlAlteraEmpSocio .= "  where integra_cad_socio = {$oSocio->iSocioOld} ";

		    if ( !db_query($connDestino,$sSqlAlteraEmpSocio) ){
          throw new Exception("ERRO-17: ".pg_last_error($connDestino)."".$sSqlAlteraEmpSocio);
	      }
		  }
		}

		unset($aListaAlteracaoSocio);

	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}



	db_logTitulo(" PROCESSA GRAFICAS ",$sArquivoLog,$iParamLog);

	$aListaAlteracaoGrafica = array();

	$sSqlGraficaPrefeitura  = " select issbase.q02_inscr  as inscricao,                                       ";
	$sSqlGraficaPrefeitura .= "  	     cgm.z01_numcgm     as codigo_grafica,                                  ";
	$sSqlGraficaPrefeitura .= "  	     cgm.z01_cgccpf     as cpf_cnpj,                                        ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_telef      as telefone,                                        ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_incest     as inscricao_estadual,                              ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_nome       as nome_grafica,                                    ";
	$sSqlGraficaPrefeitura .= "        substr(cgm.z01_fax,0,15) as fax,                                       ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_email      as email,                                           ";
	$sSqlGraficaPrefeitura .= "        case
	                                     when bairro.j13_descr is not null then bairro.j13_descr
	                                     else cgm.z01_bairro
	                                   end as bairro,                                                         ";
	$sSqlGraficaPrefeitura .= "        case
	                                     when db_cgmruas.j14_codigo is not null then '$sMunic'
	                                     else cgm.z01_munic
	                                   end as cidade,                                                         ";
	$sSqlGraficaPrefeitura .= "        case                                                                   ";
	$sSqlGraficaPrefeitura .= "           when j88_sigla is not null then j88_sigla                           ";
	$sSqlGraficaPrefeitura .= "           else null                                                           ";
	$sSqlGraficaPrefeitura .= "        end as tipo_logradouro,                                                ";
	$sSqlGraficaPrefeitura .= "        case                                                                   ";
	$sSqlGraficaPrefeitura .= "           when ruas.j14_nome is not null then ruas.j14_nome                   ";
	$sSqlGraficaPrefeitura .= "           else cgm.z01_ender                                                  ";
	$sSqlGraficaPrefeitura .= "        end as logradouro,                                                     ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_numero	  as numero,                                            ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_compl	  as complemento,                                       ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_cep    	as cep,                                               ";
	$sSqlGraficaPrefeitura .= "        cgm.z01_uf       as estado                                             ";
	$sSqlGraficaPrefeitura .= "   from graficas                                                               ";
	$sSqlGraficaPrefeitura .= "        inner join cgm        on cgm.z01_numcgm        = graficas.y20_grafica  ";
	$sSqlGraficaPrefeitura .= "        left  join issbase    on issbase.q02_numcgm    = cgm.z01_numcgm        ";
	$sSqlGraficaPrefeitura .= "                             and ( issbase.q02_dtbaix    is null               ";
	$sSqlGraficaPrefeitura .= "                              or   issbase.q02_dtbaix  < current_date )        ";
	$sSqlGraficaPrefeitura .= "        left  join db_cgmruas on db_cgmruas.z01_numcgm = cgm.z01_numcgm	   	  ";
	$sSqlGraficaPrefeitura .= "        left  join ruas       on ruas.j14_codigo       = db_cgmruas.j14_codigo ";
	$sSqlGraficaPrefeitura .= "        left  join ruastipo   on ruastipo.j88_codigo   = ruas.j14_tipo 		    ";
	$sSqlGraficaPrefeitura .= "        left  join db_cgmbairro on db_cgmbairro.z01_numcgm = cgm.z01_numcgm         ";
	$sSqlGraficaPrefeitura .= "        left  join bairro        on bairro.j13_codi         = db_cgmbairro.j13_codi ";

	$rsGraficaPrefeitura      = db_query($conn,$sSqlGraficaPrefeitura);
	$aListaGraficaPrefeitura  = db_utils::getCollectionByRecord($rsGraficaPrefeitura);
	$iLinhasGraficaPrefeitura = count($aListaGraficaPrefeitura);

	if ( $iLinhasGraficaPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasGraficaPrefeitura}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

		foreach ( $aListaGraficaPrefeitura as $iInd => $oGraficaPrefeitura ) {

		  $lAteracao = false;

      if ( !validaCpfCnpj($oGraficaPrefeitura->cpf_cnpj)){
        $oGraficaPrefeitura->cpf_cnpj  = "0";
        $sMsgLog  = "Gráfica código :{$oGraficaPrefeitura->codigo_grafica} ";
        $sMsgLog .= "CNPJ : {$oGraficaPrefeitura->cpf_cnpj} inválido! ";
        db_log($sMsgLog,$sArquivoLog,2);
      }

		  /**
		   *  Verifica se já existe o registro na base de destino apartir do
		   *  código da grafica utilizado na base de origem
		   */
		  $sSqlGraficaDestino  = " select * 										   		                           ";
		  $sSqlGraficaDestino .= " 	 from integra_cad_grafica			 			   		                   ";
		  $sSqlGraficaDestino .= " 	where codigo_grafica = {$oGraficaPrefeitura->codigo_grafica} ";
		  $sSqlGraficaDestino .= "  order by sequencial desc limit 1					   	               ";

		  $rsGraficaDestino    = db_query($connDestino,$sSqlGraficaDestino);

		  logProcessamento($iInd,$iLinhasGraficaPrefeitura,$iParamLog);

		  /**
		   *  Caso exista algum registro na base de destino então é comparado os dados
		   *  com a base de origem através da função hasDiffObject
		   */
		  if ( pg_num_rows($rsGraficaDestino) > 0 ) {

		    $oGraficaDestino = db_utils::fieldsMemory($rsGraficaDestino,0);

		    if ( hasDiffObject($oGraficaPrefeitura,$oGraficaDestino) ) {
		    } else {
		      continue;
		    }

		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oGraficaPrefeitura->munic_ibge  = $iCodIBGE;
		  $oGraficaPrefeitura->dataimp     = $dtDataHoje;
		  $oGraficaPrefeitura->horaimp     = db_hora();
		  $oGraficaPrefeitura->processado	 = "f";
		  $oGraficaPrefeitura->ddd_fone    = '';
		  $oGraficaPrefeitura->ddd_fax     = '';

		  /**
		   *  Atribui o valores da base de origem ao objeto tableDataManager
		   */
		  $oIntegraCadGrafica->setByLineOfDBUtils($oGraficaPrefeitura);


		  try {
		    $iCodGrafica = $oIntegraCadGrafica->insertValue();
		  } catch ( Exception $eException ) {
		  	throw new Exception("ERRO-18: {$eException->getMessage()}");
		  }

		  /**
		   *  Como a integração é feita com COPY é necessário guardar o código gerado
		   *  em um array de objetos para posteriormente popular as tabelas de ligação
		   */
		  if ( $lAteracao ) {
		  	$oGrafica = new stdClass();
		  	$oGrafica->iGraficaOld  = $oGraficaDestino->sequencial;
		  	$oGrafica->iGraficaNew  = $iCodGrafica;
		  	$aListaAlteracaoGrafica[] = $oGrafica;
		  }

		}

		try {
		  $oIntegraCadGrafica->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-19: {$eException->getMessage()}");
		}

		/**
		 *  Caso exista alguma alteração então é acertado a chave
		 *  com todas tabelas de ligação
		 */
		if ( count($aListaAlteracaoGrafica) > 0 ) {

		  foreach ( $aListaAlteracaoGrafica as $oGrafica ) {

	  		$sSqlAlteraEmpGrafica  = " update integra_empresa_aidof   					 	           ";
		    $sSqlAlteraEmpGrafica .= "    set integra_cad_grafica = {$oGrafica->iGraficaNew} ";
		    $sSqlAlteraEmpGrafica .= "  where integra_cad_grafica = {$oGrafica->iGraficaOld} ";

		    if ( !db_query($connDestino,$sSqlAlteraEmpGrafica) ){
		    	throw new Exception("ERRO-20: ".pg_last_error($connDestino)." ".$sSqlAlteraEmpGrafica);
		    }

		  }

		}

		unset($aListaAlteracaoGrafica);

	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}


	db_logTitulo(" PROCESSA ESCRITORIOS ",$sArquivoLog,$iParamLog);

	$aListaAlteracaoEscritorio = array();

	$sSqlEscritorioPrefeitura    = "select issbase.q02_inscr  as inscricao,                                            ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_numcgm     as codigo_escritorio,                                    ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_cgccpf     as cpf_cnpj,                                             ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_nome       as nome_escritorio,                                      ";
	$sSqlEscritorioPrefeitura   .= "       issbase.q02_dtinic as data_abertura,                                        ";
	$sSqlEscritorioPrefeitura   .= "       issbase.q02_dtbaix as data_encerramento,                                    ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
	$sSqlEscritorioPrefeitura   .= "       case                                                                        ";
	$sSqlEscritorioPrefeitura   .= "         when issbase.q02_dtbaix is null then 'A'                                  ";
	$sSqlEscritorioPrefeitura   .= "         else 'E'                                                                  ";
	$sSqlEscritorioPrefeitura   .= "       end as status_empresa,                                                      ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
  $sSqlEscritorioPrefeitura   .= "       j88_sigla          as tipo_logradouro,                                      ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
  $sSqlEscritorioPrefeitura   .= "       case                                                                        ";
  $sSqlEscritorioPrefeitura   .= "         when ruas.j14_nome is not null then ruas.j14_nome                         ";
  $sSqlEscritorioPrefeitura   .= "         else cgm.z01_ender                                                        ";
  $sSqlEscritorioPrefeitura   .= "       end as logradouro,                                                          ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
  $sSqlEscritorioPrefeitura   .= "       case                                                                        ";
  $sSqlEscritorioPrefeitura   .= "         when bairro.j13_descr is not null then bairro.j13_descr                   ";
  $sSqlEscritorioPrefeitura   .= "         else cgm.z01_bairro                                                       ";
  $sSqlEscritorioPrefeitura   .= "       end as bairro,                                                              ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
  $sSqlEscritorioPrefeitura   .= "       case                                                                        ";
  $sSqlEscritorioPrefeitura   .= "         when db_cgmruas.j14_codigo is not null then '$sMunic'                     ";
  $sSqlEscritorioPrefeitura   .= "         else cgm.z01_munic                                                        ";
  $sSqlEscritorioPrefeitura   .= "       end as cidade,                                                              ";
  $sSqlEscritorioPrefeitura   .= "                                                                                   ";
  $sSqlEscritorioPrefeitura   .= "       cgm.z01_numero     as numero,                                               ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_compl      as complemento,                                          ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_cep        as cep,                                                  ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_uf         as estado,                                               ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_telef      as telefone,                                             ";
	$sSqlEscritorioPrefeitura   .= "       substr(cgm.z01_fax,0,15) as fax,                                            ";
	$sSqlEscritorioPrefeitura   .= "       cgm.z01_email      as email,                                                ";
	$sSqlEscritorioPrefeitura   .= "       ( select count(*)  													                               ";
	$sSqlEscritorioPrefeitura   .= "       	 from issbase x  													                                 ";
	$sSqlEscritorioPrefeitura   .= "       	where x.q02_numcgm = cgm.z01_numcgm) as numreg	                           ";
	$sSqlEscritorioPrefeitura   .= "  from cadescrito                                                                  ";
	$sSqlEscritorioPrefeitura   .= "       inner join cgm           on cgm.z01_numcgm = cadescrito.q86_numcgm          ";
	$sSqlEscritorioPrefeitura   .= "       left  join issbase       on issbase.q02_numcgm = cgm.z01_numcgm             ";
	$sSqlEscritorioPrefeitura   .= "       left  join db_cgmruas    on db_cgmruas.z01_numcgm = cadescrito.q86_numcgm   ";
	$sSqlEscritorioPrefeitura   .= "       left  join ruas          on ruas.j14_codigo       = db_cgmruas.j14_codigo   ";
	$sSqlEscritorioPrefeitura   .= "       left  join ruastipo      on ruastipo.j88_codigo   = ruas.j14_tipo           ";
	$sSqlEscritorioPrefeitura   .= "       left  join db_cgmbairro  on db_cgmbairro.z01_numcgm = cgm.z01_numcgm        ";
	$sSqlEscritorioPrefeitura   .= "       left  join bairro        on bairro.j13_codi         = db_cgmbairro.j13_codi ";

	$rsEscritorioPrefeitura      = db_query($conn,$sSqlEscritorioPrefeitura);
	$aListaEscritorioPrefeitura  = db_utils::getCollectionByRecord($rsEscritorioPrefeitura);
	$iLinhasEscritorioPrefeitura = count($aListaEscritorioPrefeitura);

	if ( $iLinhasEscritorioPrefeitura > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasEscritorioPrefeitura}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

		foreach ( $aListaEscritorioPrefeitura as $iInd => $oEscritorioPrefeitura ) {

		  $lAteracao = false;

		  if ( $oEscritorioPrefeitura->numreg > 1 ) {
		  	$sMsgLog  = "Escritório não processado! CGM ligado a mais de uma empresa. ";
		  	$sMsgLog .= "CGM: $oEscritorioPrefeitura->codigo_escritorio ";
		  	db_log($sMsgLog,$sArquivoLog,2);
		  	continue;
		  }

      if ( !validaCpfCnpj($oEscritorioPrefeitura->cpf_cnpj)){
        $oEscritorioPrefeitura->cpf_cnpj  = "0";
        $sMsgLog  = "Escritório código :{$oEscritorioPrefeitura->codigo_escritorio} ";
        $sMsgLog .= "CNPJ : {$oEscritorioPrefeitura->cpf_cnpj} inválido! ";
        db_log($sMsgLog,$sArquivoLog,2);
      }

		  /**
		   *  Verifica se já existe o registro na base de destino apartir do
		   *  código do escritório utilizado na base de origem
		   */
		  $sSqlEscritorioDestino  = " select * 										  ";
		  $sSqlEscritorioDestino .= "   from integra_cad_escritorio	";
		  $sSqlEscritorioDestino .= "  where codigo_escritorio = ".trim($oEscritorioPrefeitura->codigo_escritorio);

		  if ( trim($oEscritorioPrefeitura->inscricao) != ''  ){
		    $sSqlEscritorioDestino .= "    and inscricao = {$oEscritorioPrefeitura->inscricao}";
		  } else {
		  	$sSqlEscritorioDestino .= "    and inscricao is null ";
		  }

		  $sSqlEscritorioDestino .= "  order by sequencial desc limit 1	";
		  $rsEscritorioDestino    = db_query($connDestino,$sSqlEscritorioDestino);

		  logProcessamento($iInd,$iLinhasEscritorioPrefeitura,$iParamLog);

		  /**
		   *  Caso exista algum registro na base de destino então é comparado os dados
		   *  com a base de origem através da função hasDiffObject
		   */
		  if ( pg_num_rows($rsEscritorioDestino) > 0 ) {

		    $oEscritorioDestino = db_utils::fieldsMemory($rsEscritorioDestino,0);

		    if ( hasDiffObject($oEscritorioPrefeitura,$oEscritorioDestino) ) {
		      $lAteracao = true;
		    } else {
		      continue;
		    }

		  }

		  /**
		   *  Define todas propriedades que não retornam do SQL
		   */
		  $oEscritorioPrefeitura->munic_ibge  = $iCodIBGE;
		  $oEscritorioPrefeitura->dataimp     = $dtDataHoje;
		  $oEscritorioPrefeitura->horaimp     = db_hora();
		  $oEscritorioPrefeitura->processado	= "f";
		  $oEscritorioPrefeitura->ddd_fone    = '';
		  $oEscritorioPrefeitura->ddd_fax     = '';
		  $oEscritorioPrefeitura->ramal       = '';

	    /**
	     *  Atribui o valores da base de origem ao objeto tableDataManager
	     */
	    $oIntegraCadEscritorio->setByLineOfDBUtils($oEscritorioPrefeitura);

		  try {
		    $iCodEscritorio = $oIntegraCadEscritorio->insertValue();
		  } catch ( Exception $eException ) {
  	    throw new Exception("ERRO-21: {$eException->getMessage()}");
		  }

		  /**
		   *  Como a integração é feita com COPY é necessário guardar o código gerado
		   *  em um array de objetos para posteriormente popular as tabelas de ligação
		   */
		  if ( $lAteracao ) {
		  	$oEscritorio = new stdClass();
		  	$oEscritorio->iEscritorioOld = $oEscritorioDestino->sequencial;
		  	$oEscritorio->iEscritorioNew = $iCodEscritorio;
		  	$aListaAlteracaoEscritorio[] = $oEscritorio;
		  }

		}

		try {
		  $oIntegraCadEscritorio->persist();
		} catch ( Exception $eException ) {
			throw new Exception("ERRO-22: {$eException->getMessage()}");
		}

		/**
		 *  Caso exista alguma alteração então é acertado a chave
		 *  com todas tabelas de ligação
		 */
		if ( count($aListaAlteracaoEscritorio) > 0 ) {

		  foreach ( $aListaAlteracaoEscritorio as $oEscritorio ) {

			  $sSqlAlteraEmpEscritorio  = " update integra_empresa_escritorio 					              		 ";
		    $sSqlAlteraEmpEscritorio .= "    set integra_cad_escritorio = {$oEscritorio->iEscritorioNew} ";
		    $sSqlAlteraEmpEscritorio .= "  where integra_cad_escritorio = {$oEscritorio->iEscritorioOld} ";

		    if ( !db_query($connDestino,$sSqlAlteraEmpEscritorio) ){
		    	throw new Exception("ERRO-23: ".pg_last_error($connDestino)." ".$sSqlAlteraEmpEscritorio);
	      }

		  }

		}

		unset($aListaAlteracaoEscritorio);

	} else {
	  db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
	}



	db_logTitulo(" PROCESSA EMPRESAS ",$sArquivoLog,$iParamLog);

	/**
	 *  Consulta empresas da base de origem
	 */
	$sSqlDadosEmpresa  = " select distinct on (issbase.q02_inscr)								 		                       ";
	$sSqlDadosEmpresa .= "        cgm.z01_numcgm as cgm,											 	                           ";
	$sSqlDadosEmpresa .= "        case 															 		                                   ";
	$sSqlDadosEmpresa .= "          when db_cgmcgc.z01_cgc is not null then 'J' 				 		               ";
	$sSqlDadosEmpresa .= "          else case  													 		                               ";
	$sSqlDadosEmpresa .= "             	   when db_cgmcpf.z01_cpf is not null then 'F' 			 		           ";
	$sSqlDadosEmpresa .= "             	   else null  											 		                           ";
	$sSqlDadosEmpresa .= "               end  													 		                               ";
	$sSqlDadosEmpresa .= "        end as tipo_empresa, 											 		                           ";
	$sSqlDadosEmpresa .= "        case  														 		                                   ";
	$sSqlDadosEmpresa .= "          when db_cgmcgc.z01_cgc is not null then z01_cgc 			 		             ";
	$sSqlDadosEmpresa .= "          else case 	 												 		                               ";
	$sSqlDadosEmpresa .= "          	     when db_cgmcpf.z01_cpf is not null then z01_cpf 	 		   	       ";
	$sSqlDadosEmpresa .= "          	     else cgm.z01_cgccpf						 		                             ";
	$sSqlDadosEmpresa .= "               end															                                 ";
	$sSqlDadosEmpresa .= "        end as cpf_cnpj, 														                             ";
	$sSqlDadosEmpresa .= "        case  																                                   ";
	$sSqlDadosEmpresa .= "          when issbase.q02_dtbaix is null then 'A' 							                 ";
	$sSqlDadosEmpresa .= "          else 'E' 															                                 ";
	$sSqlDadosEmpresa .= "        end as status_empresa, 												                           ";
	$sSqlDadosEmpresa .= "        issbase.q02_inscr  as inscricao, 										                     ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when issbase.q02_dtcada is not null then issbase.q02_dtcada                             ";
  $sSqlDadosEmpresa .= "          when issbase.q02_dtinic is not null then issbase.q02_dtinic                             ";
  $sSqlDadosEmpresa .= "          else ( select min(q07_datain)                                                           ";
  $sSqlDadosEmpresa .= "                   from tabativ t                                                                 ";
  $sSqlDadosEmpresa .= "                  where t.q07_inscr = issbase.q02_inscr )                                         ";
  $sSqlDadosEmpresa .= "        end as data_abertura,                                                                     ";
	$sSqlDadosEmpresa .= "        issbase.q02_dtbaix as data_encerramento, 								                 ";
	$sSqlDadosEmpresa .= "        cgm.z01_telef      as telefone, 										                     ";
	$sSqlDadosEmpresa .= "        cgm.z01_incest     as inscricao_estadual, 							                 ";
	$sSqlDadosEmpresa .= "        cgm.z01_nome       as nome_empresa, 									                   ";
	$sSqlDadosEmpresa .= "        cgm.z01_nomefanta  as nome_fantasia, 									                   ";
	$sSqlDadosEmpresa .= "        substr(cgm.z01_fax,0,15) as fax,								                         ";
	$sSqlDadosEmpresa .= "        cgm.z01_email      as email, 											                       ";
	$sSqlDadosEmpresa .= "        q14_proces         as num_processo, 									                   ";
	$sSqlDadosEmpresa .= "        j88_sigla          as tipo_logradouro, 								                   ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when ruas.j14_nome is not null then ruas.j14_nome                                       ";
  $sSqlDadosEmpresa .= "          else cgm.z01_ender                                                                      ";
  $sSqlDadosEmpresa .= "        end as logradouro,                                                                        ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when issruas.q02_numero is not null then issruas.q02_numero                             ";
  $sSqlDadosEmpresa .= "          else cgm.z01_numero                                                                     ";
  $sSqlDadosEmpresa .= "        end as numero,                                                                            ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when issruas.q02_compl is not null then substr(issruas.q02_compl,1,30)                  ";
  $sSqlDadosEmpresa .= "          else cgm.z01_compl                                                                      ";
  $sSqlDadosEmpresa .= "        end as complemento,                                                                       ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when bairro.j13_descr is not null then bairro.j13_descr                                 ";
  $sSqlDadosEmpresa .= "          else cgm.z01_bairro                                                                     ";
  $sSqlDadosEmpresa .= "        end as bairro,                                                                            ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when issruas.z01_cep is not null then issruas.z01_cep                                   ";
  $sSqlDadosEmpresa .= "          else cgm.z01_cep                                                                        ";
  $sSqlDadosEmpresa .= "        end as cep,                                                                               ";
  $sSqlDadosEmpresa .= "        case                                                                                      ";
  $sSqlDadosEmpresa .= "          when issruas.j14_codigo is not null then '$sMunic'                                      ";
  $sSqlDadosEmpresa .= "          else cgm.z01_munic                                                                      ";
  $sSqlDadosEmpresa .= "        end as cidade,                                                                            ";
  $sSqlDadosEmpresa .= "                                                                                                  ";
	$sSqlDadosEmpresa .= "        cgm.z01_uf         as estado, 										                       ";
	$sSqlDadosEmpresa .= "        coalesce(q30_area,0) as area,											                       ";
	$sSqlDadosEmpresa .= "        login, 																                                   ";
	$sSqlDadosEmpresa .= "        senha 																                                   ";
  $sSqlDadosEmpresa .= "   from issbaseintegracaoexterna                                                                  ";
  $sSqlDadosEmpresa .= "        inner join issbase     on issbase.q02_inscr      = issbaseintegracaoexterna.q135_inscr    ";
	$sSqlDadosEmpresa .= "        inner join cgm         on issbase.q02_numcgm     = cgm.z01_numcgm 	     ";
	$sSqlDadosEmpresa .= "        left  join issprocesso on issprocesso.q14_inscr  = issbase.q02_inscr     ";
	$sSqlDadosEmpresa .= "        left  join db_cgmcgc   on db_cgmcgc.z01_numcgm   = cgm.z01_numcgm 	     ";
	$sSqlDadosEmpresa .= "        left  join db_cgmcpf   on db_cgmcpf.z01_numcgm   = cgm.z01_numcgm 	     ";
	$sSqlDadosEmpresa .= "        left  join issruas     on issruas.q02_inscr      = issbase.q02_inscr 	   ";
	$sSqlDadosEmpresa .= "        left  join ruas        on issruas.j14_codigo     = ruas.j14_codigo 	     ";
	$sSqlDadosEmpresa .= "        left  join ruastipo    on ruastipo.j88_codigo    = ruas.j14_codigo	     ";
	$sSqlDadosEmpresa .= "        left  join issbairro   on issbairro.q13_inscr    = issbase.q02_inscr 	   ";
	$sSqlDadosEmpresa .= "        left  join bairro      on bairro.j13_codi        = issbairro.q13_bairro  ";
	$sSqlDadosEmpresa .= "        left  join issquant    on issquant.q30_inscr     = issbase.q02_inscr 	   ";
	$sSqlDadosEmpresa .= "        					          	and issquant.q30_anousu    = {$iAnoUsu}			       ";
	$sSqlDadosEmpresa .= "        left  join tabativ	   on tabativ.q07_inscr      = issbase.q02_inscr     ";
	$sSqlDadosEmpresa .= "      					              and tabativ.q07_databx is not null 	               ";
	$sSqlDadosEmpresa .= "        left  join db_usuacgm  on db_usuacgm.cgmlogin    = issbase.q02_numcgm    ";
	$sSqlDadosEmpresa .= "        left  join db_usuarios on db_usuarios.id_usuario = db_usuacgm.id_usuario ";

  $rsDadosEmpresa 	   = db_query($conn,$sSqlDadosEmpresa) or die("Erro:{$sSqlDadosEmpresa}");
	$iLinhasDadosEmpresa = pg_num_rows($rsDadosEmpresa);
	$aListaEmpresa       = array();

  if ( $iLinhasDadosEmpresa > 0 ) {

		db_log("Total de Registros Encontrados : {$iLinhasDadosEmpresa}",$sArquivoLog,$iParamLog);
		db_log("\n",$sArquivoLog,1);

	  for ( $iInd=0; $iInd < $iLinhasDadosEmpresa; $iInd++ ) {

	  	$oDadosEmpresa = db_utils::fieldsMemory($rsDadosEmpresa,$iInd);
 	  	$lAteracao     = false;
			/**
			 *  Regime da Empresa
			 */
			$sRegimeEmpresa = "N";

			$sSqlRegime  = " select q01_cadcal, 													                             ";
			$sSqlRegime .= "        q38_categoria  as isscadsimples, 								                   ";
			$sSqlRegime .= "        q39_sequencial as isscadsimplesbaixa, 							               ";
			$sSqlRegime .= "        q33_codigo     as varfix, 										                     ";
      $sSqlRegime .= "        q34_codigo     as varfixval,                                       ";
      $sSqlRegime .= "        q34_ano        as ano_valorfixado                                  ";
			$sSqlRegime .= "   from isscalc  														                               ";
			$sSqlRegime .= "        left join isscadsimples      on q38_inscr         = q01_inscr      ";
			$sSqlRegime .= "        left join isscadsimplesbaixa on q39_isscadsimples = q38_sequencial ";
			$sSqlRegime .= "        left join varfix             on q33_inscr         = q01_inscr 	   ";
			$sSqlRegime .= "        left join varfixval          on q33_codigo        = q34_codigo     ";
			$sSqlRegime .= "  where q01_anousu = extract(year from current_date)  					           ";
			$sSqlRegime .= "    and q01_inscr = {$oDadosEmpresa->inscricao}						                 ";

			$rsRegimeEmpresa = db_query($conn, $sSqlRegime);

			logProcessamento($iInd,$iLinhasDadosEmpresa,$iParamLog);

			if ( pg_num_rows($rsRegimeEmpresa) > 0) {

			  $oRegimeEmpresa = db_utils::fieldsMemory($rsRegimeEmpresa, 0);

			  if ( $oRegimeEmpresa->q01_cadcal == 2 ) {

			  	$sRegimeEmpresa = 'F';

			  } else if ( $oRegimeEmpresa->q01_cadcal == 3 ) {

			    $sRegimeEmpresa = 'A';

          if ( $iAnoUsu ==  $oRegimeEmpresa->ano_valorfixado)  {
			      $sRegimeEmpresa = 'T';
			    }

			  }

			}

			$sSqlTipcalc  = " select count(*) as quant ";
			$sSqlTipcalc .= " from tabativ ";
			$sSqlTipcalc .= " inner join ativid on ativid.q03_ativ = tabativ.q07_ativ ";
			$sSqlTipcalc .= " inner join ativtipo on ativtipo.q80_ativ = ativid.q03_ativ ";
			$sSqlTipcalc .= " inner join tipcalc on tipcalc.q81_codigo = ativtipo.q80_tipcal and tipcalc.q81_tipo = 1 ";
			$sSqlTipcalc .= " inner join cadcalc on cadcalc.q85_codigo = tipcalc.q81_cadcalc and cadcalc.q85_var is true ";
			$sSqlTipcalc .= " where (     tabativ.q07_databx is null ";
			$sSqlTipcalc .= "         and (  q07_datafi is null or q07_datafi >= '{$dtDataHoje}' ) ";
			$sSqlTipcalc .= "       ) and tabativ.q07_inscr = {$oDadosEmpresa->inscricao} ";
			$rsTipcalc   = db_query($conn, $sSqlTipcalc) or die($sSqlTipcalc);
			$oTipCalc    = db_utils::fieldsMemory($rsTipcalc, 0);

			if ( $oTipCalc->quant > 0 ) {
			  $sRegimeEmpresa = 'A';
			}

			$oDadosEmpresa->regime_empresa = $sRegimeEmpresa;

			$sSqlListaAtividades  = " select *                                                                  ";
      $sSqlListaAtividades .= "   from tabativ                                                            ";
      $sSqlListaAtividades .= "  where q07_inscr = {$oDadosEmpresa->inscricao}                            ";
      $sSqlListaAtividades .= "    and q07_datafi is not null and q07_datafi > '{$dtDataHoje}'            ";
      $sSqlListaAtividades .= "    and not exists ( select *                                              ";
      $sSqlListaAtividades .= "                       from tabativ tbativ                                 ";
      $sSqlListaAtividades .= "                      where tbativ.q07_inscr = {$oDadosEmpresa->inscricao} ";
      $sSqlListaAtividades .= "                        and q07_datafi is null )                           ";

      $rsListaAtividades      = db_query($conn,$sSqlListaAtividades);
      $iLinhasListaAtividades = pg_num_rows($rsListaAtividades);

      if ( $iLinhasListaAtividades > 0 ) {
        $sTipoInscricao = 'E';
      } else {
      	$sTipoInscricao = 'P';
      }

		  $oDadosEmpresa->tipo_inscricao = $sTipoInscricao;

      if ( !validaCpfCnpj($oDadosEmpresa->cpf_cnpj)){
        $oDadosEmpresa->cpf_cnpj = "0";
        $sMsgLog  = "Empresa de Inscrição {$oDadosEmpresa->inscricao} ";
        $sMsgLog .= "CNPJ : {$oDadosEmpresa->cpf_cnpj} inválido! ";
        db_log($sMsgLog,$sArquivoLog,2);
      }

			/**
			 *  Verifica se já existe o registro na base de destino apartir da
			 *  inscrição utilizada na base de origem
			 */
			$sSqlEmpresaDestino  = " select * 						  	 			                ";
			$sSqlEmpresaDestino .= "   from integra_cad_empresa 	  	 			        ";
			$sSqlEmpresaDestino .= "  where inscricao = {$oDadosEmpresa->inscricao} ";
			$sSqlEmpresaDestino .= "  order by sequencial desc limit 1			      	";

	    $rsEmpresaDestino    = db_query($connDestino,$sSqlEmpresaDestino);

	    /**
	     *  Caso exista algum registro na base de destino então é comparado os dados
	     *  com a base de origem através da função hasDiffObject
	     */
	    if ( pg_num_rows($rsEmpresaDestino) > 0 ) {

			  $oEmpresaDestino = db_utils::fieldsMemory($rsEmpresaDestino,0);

			  if ( hasDiffObject($oDadosEmpresa,$oEmpresaDestino) ) {
          $lAteracao = true;
			  }

			  unset($oEmpresaDestino);

		  }

		  if (!$lAteracao) {

		  	$aListaAtivNew = array();
		  	$aListaAtivOld = array();

		  	$sSqlValidaAtividadeNew = "select q07_ativ
		  	                          from tabativ
		  	                         where q07_inscr = {$oDadosEmpresa->inscricao} ";

		  	$rsValidaAtividadeNew      = db_query($conn,$sSqlValidaAtividadeNew);
		  	$iLinhasValidaAtividadeNew = pg_num_rows($rsValidaAtividadeNew);

		  	if ($iLinhasValidaAtividadeNew > 0) {
		  		for ($iIndValAtiv=0; $iIndValAtiv < $iLinhasValidaAtividadeNew; $iIndValAtiv++) {
		  			$aListaAtivNew[] = db_utils::fieldsMemory($rsValidaAtividadeNew,$iIndValAtiv)->q07_ativ;
		  		}
		  	}

        $sSqlValidaAtividadeOld = "select distinct(integra_cad_atividade.atividade)
                                     from integra_empresa_atividade
                                          inner join integra_cad_atividade on integra_cad_atividade.sequencial = integra_empresa_atividade.integra_cad_atividade
                                    where integra_empresa_atividade.integra_cad_empresa = ( select sequencial
								                                                                              from integra_cad_empresa
								                                                                             where inscricao = {$oDadosEmpresa->inscricao}
								                                                                             order by sequencial desc limit 1 )";

        $rsValidaAtividadeOld      = db_query($connDestino,$sSqlValidaAtividadeOld);
        $iLinhasValidaAtividadeOld = pg_num_rows($rsValidaAtividadeOld);

        if ($iLinhasValidaAtividadeOld > 0) {
          for ($iIndValAtiv=0; $iIndValAtiv < $iLinhasValidaAtividadeOld; $iIndValAtiv++) {
            $aListaAtivOld[] = db_utils::fieldsMemory($rsValidaAtividadeOld,$iIndValAtiv)->atividade;
          }
        }

		  	if ( count($aListaAtivOld) != count($aListaAtivNew)) {
		  		$lAteracao = true;
		  	} else {

		  		foreach ( $aListaAtivNew as $iCodAtivNew ) {

		  			$lVerificaAtiv = true;

			  		foreach ( $aListaAtivOld as $iCodAtivOld ) {
		  				if ( $iCodAtivNew == $iCodAtivOld) {
		  					$lVerificaAtiv = false;
		  				}
		  			}

			  		if ($lVerificaAtiv) {
			  			$lAteracao = true;
			  		}
		  		}
		  	}
		  }

		  if (!$lAteracao) {

        $aListaSocioNew = array();
        $aListaSocioOld = array();

        $sSqlValidaSocioNew = "select q95_numcgm
                                  from socios
                                 where q95_cgmpri = {$oDadosEmpresa->cgm} ";

        $rsValidaSocioNew      = db_query($conn,$sSqlValidaSocioNew);
        $iLinhasValidaSocioNew = pg_num_rows($rsValidaSocioNew);

        if ($iLinhasValidaSocioNew > 0) {
          for ($iIndValSocio=0; $iIndValSocio < $iLinhasValidaSocioNew; $iIndValSocio++) {
            $aListaSocioNew[] = db_utils::fieldsMemory($rsValidaSocioNew,$iIndValSocio)->q95_numcgm;
          }
        }

        $sSqlValidaSocioOld = " select distinct(integra_cad_socio.codigo_socio)
                                  from integra_empresa_socio
                                       inner join integra_cad_socio   on integra_cad_socio.sequencial = integra_empresa_socio.integra_cad_socio
                                 where integra_empresa_socio.integra_cad_empresa = ( select sequencial
                                                                                       from integra_cad_empresa
                                                                                      where inscricao = {$oDadosEmpresa->inscricao}
                                                                                   order by sequencial desc limit 1 )";


        $rsValidaSocioOld      = db_query($connDestino,$sSqlValidaSocioOld);
        $iLinhasValidaSocioOld = pg_num_rows($rsValidaSocioOld);

        if ($iLinhasValidaSocioOld > 0) {
          for ($iIndValSocio=0; $iIndValSocio < $iLinhasValidaSocioOld; $iIndValSocio++) {
            $aListaSocioOld[] = db_utils::fieldsMemory($rsValidaSocioOld,$iIndValSocio)->codigo_socio;
          }
        }

        if ( count($aListaSocioOld) != count($aListaSocioNew)) {
          $lAteracao = true;
        } else {

          foreach ( $aListaSocioNew as $iCodSocioNew ) {

            $lVerificaSocio = true;

            foreach ( $aListaSocioOld as $iCodSocioOld ) {
              if ( $iCodSocioNew == $iCodSocioOld) {
                $lVerificaSocio = false;
              }
            }

            if ($lVerificaSocio) {
              $lAteracao = true;
            }
          }
        }
		  }


		    /**
		     *  Define todas propriedades que não retornam do SQL
		     */
		    $oDadosEmpresa->munic_ibge             = $iCodIBGE;
		    $oDadosEmpresa->dataimp                = $dtDataHoje;
		    $oDadosEmpresa->horaimp                = db_hora();
		    $oDadosEmpresa->processado			       = "f";
		    $oDadosEmpresa->enquadramento_empresa  = '';
		    $oDadosEmpresa->classificacao          = '';
		    $oDadosEmpresa->ddd_fone               = '';
		    $oDadosEmpresa->ramal                  = '';
		    $oDadosEmpresa->ddd_fax                = '';
		    $oDadosEmpresa->logotipo               = '';
		    $oDadosEmpresa->area_total             = $oDadosEmpresa->area;
		    $oDadosEmpresa->area_ocupada           = $oDadosEmpresa->area;


		 		/**
			   *  Atribui o valores da base de origem ao objeto tableDataManager
			   */
				$oIntegraCadEmpresa->setByLineOfDBUtils($oDadosEmpresa);


		    try {
		      $iCodEmpresa = $oIntegraCadEmpresa->insertValue();
		    } catch ( Exception $eException ) {
		    	throw new Exception("ERRO-24: {$eException->getMessage()}");
		    }

		    /**
		     *  Como a integração é feita com COPY é necessário guardar o código gerado
		     *  em um array de objetos para posteriormente popular as tabelas de ligação
		     */
		    $oEmpresa = new stdClass();
		    $oEmpresa->iCodEmpresa = $iCodEmpresa;
		    $oEmpresa->iInscricao  = $oDadosEmpresa->inscricao;
		    $oEmpresa->iCgm		     = $oDadosEmpresa->cgm;
		    $aListaEmpresa[]       = $oEmpresa;

				unset($oRegimeEmpresa);
				unset($oDadosEmpresa);
				unset($oEmpresa);


			/**
			 *
			 *  Inicia o processamento dos dados das empresas que estão no COPY
			 *
			 */

	    if ( count($aListaEmpresa) == $iLoteEmpresa || ($iInd+1) == $iLinhasDadosEmpresa ) {

	    	if ( ($iInd+1) == $iLinhasDadosEmpresa ) {
				  try {
				    $oIntegraCadEmpresa->persist();
				  } catch ( Exception $eException ) {
   	        throw new Exception("ERRO-25: {$eException->getMessage()}");
				  }
	    	}

		    foreach ( $aListaEmpresa as $iIndEmp => $oEmpresa ) {

			    $sSqlAtivEmpPrefeitura  = " select q07_ativ as atividade,                                         ";
			    $sSqlAtivEmpPrefeitura .= "        case                                                           ";
			    $sSqlAtivEmpPrefeitura .= "          when q88_inscr is not null then 1                            ";
			    $sSqlAtivEmpPrefeitura .= "          else 2                                                       ";
			    $sSqlAtivEmpPrefeitura .= "        end      as atividade_principal,                               ";
			    $sSqlAtivEmpPrefeitura .= "        q07_datain as datainicio,                                      ";
			    $sSqlAtivEmpPrefeitura .= "        q07_datafi as datafim,                                         ";
			    $sSqlAtivEmpPrefeitura .= "        extract ( year from q07_datain ) as exercicio                  ";
			    $sSqlAtivEmpPrefeitura .= "   from tabativ                                                        ";
			    $sSqlAtivEmpPrefeitura .= "        left join ativprinc on ativprinc.q88_inscr = tabativ.q07_inscr ";
			    $sSqlAtivEmpPrefeitura .= "                           and ativprinc.q88_seq   = tabativ.q07_seq   ";
			    $sSqlAtivEmpPrefeitura .= "  where tabativ.q07_inscr = {$oEmpresa->iInscricao}                    ";

			    $rsAtivEmpPrefeitura     = db_query($conn,$sSqlAtivEmpPrefeitura);
			    $aListaAtivEmpPrefeitura = db_utils::getCollectionByRecord($rsAtivEmpPrefeitura);

			    if ( count($aListaAtivEmpPrefeitura) > 0 ) {

			      foreach ( $aListaAtivEmpPrefeitura as $oAtivEmpPrefeitura ) {

			        $sSqlAtivDestino  = " select sequencial                                   ";
			        $sSqlAtivDestino .= "   from integra_cad_atividade                        ";
			        $sSqlAtivDestino .= "  where atividade = {$oAtivEmpPrefeitura->atividade} ";
			        $sSqlAtivDestino .= "  order by sequencial desc limit 1                   ";

			        $rsAtivDestino        = db_query($connDestino,$sSqlAtivDestino);

			        if ( pg_num_rows($rsAtivDestino) == 0 )  {
			          continue;
			        }

			        $iCodAtividadeDestino = db_utils::fieldsMemory($rsAtivDestino,0)->sequencial;

			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oAtivEmpPrefeitura->integra_cad_empresa   = $oEmpresa->iCodEmpresa;
			        $oAtivEmpPrefeitura->integra_cad_atividade = $iCodAtividadeDestino;
			        $oAtivEmpPrefeitura->munic_ibge            = $iCodIBGE;
			        $oAtivEmpPrefeitura->dataimp               = $dtDataHoje;
			        $oAtivEmpPrefeitura->horaimp               = db_hora();
			        $oAtivEmpPrefeitura->processado            = "f";

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaAtividade->setByLineOfDBUtils($oAtivEmpPrefeitura);


			        try {
			          $iCodEmpAtividade = $oIntegraEmpresaAtividade->insertValue();
			        } catch ( Exception $eException ) {
			        	throw new Exception("ERRO-26: {$eException->getMessage()}");
			        }

			      }

			      unset($aListaAtivEmpPrefeitura);

			      try {
			        $oIntegraEmpresaAtividade->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-27: {$eException->getMessage()}");
			      }

			    }


			    $sSqlSocioEmpPrefeitura  = " select q95_numcgm as codigo_socio,           ";
			    $sSqlSocioEmpPrefeitura .= "    q95_perc   as percentual                  ";
			    $sSqlSocioEmpPrefeitura .= "   from socios                                ";
			    $sSqlSocioEmpPrefeitura .= "  where socios.q95_cgmpri = {$oEmpresa->iCgm} ";

			    $rsSocioEmpPrefeitura     = db_query($conn,$sSqlSocioEmpPrefeitura);
			    $aListaSocioEmpPrefeitura = db_utils::getCollectionByRecord($rsSocioEmpPrefeitura);

			    if ( count($aListaSocioEmpPrefeitura) > 0 ) {

			      foreach ( $aListaSocioEmpPrefeitura as $oSocioEmpPrefeitura ) {

			        $sSqlSocioDestino  = " select sequencial                                          ";
			        $sSqlSocioDestino .= "   from integra_cad_socio                                   ";
			        $sSqlSocioDestino .= "  where codigo_socio = {$oSocioEmpPrefeitura->codigo_socio} ";
			        $sSqlSocioDestino .= "  order by sequencial desc limit 1                          ";

			        $rsSocioDestino    = db_query($connDestino,$sSqlSocioDestino);

			        if ( pg_num_rows($rsSocioDestino) == 0 ) {
			          continue;
			        }

			        $iCodSocioDestino  = db_utils::fieldsMemory($rsSocioDestino,0)->sequencial;


			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oSocioEmpPrefeitura->integra_cad_empresa  = $oEmpresa->iCodEmpresa;
			        $oSocioEmpPrefeitura->integra_cad_socio     = $iCodSocioDestino;
			        $oSocioEmpPrefeitura->munic_ibge           = $iCodIBGE;
			        $oSocioEmpPrefeitura->datainicial          = '';
			        $oSocioEmpPrefeitura->datafinal            = '';
			        $oSocioEmpPrefeitura->dataimp              = $dtDataHoje;
			        $oSocioEmpPrefeitura->horaimp              = db_hora();
			        $oSocioEmpPrefeitura->processado            = "f";

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaSocio->setByLineOfDBUtils($oSocioEmpPrefeitura);

			        try {
			          $iCodEmpSocio = $oIntegraEmpresaSocio->insertValue();
			        } catch ( Exception $eException ) {
                throw new Exception("ERRO-28: {$eException->getMessage()}");
			        }

			      }

			      unset($aListaSocioEmpPrefeitura);

			      try {
		          $oIntegraEmpresaSocio->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-29: {$eException->getMessage()}");
			      }

			    }


			    $sSqlSimplesEmpPrefeitura  = " select isscadsimples.q38_categoria    as tipo,                                                             ";
			    $sSqlSimplesEmpPrefeitura .= "        isscadsimples.q38_dtinicial    as datainicial,                                                      ";
			    $sSqlSimplesEmpPrefeitura .= "        isscadsimplesbaixa.q39_dtbaixa as datafinal                                                         ";
			    $sSqlSimplesEmpPrefeitura .= "   from isscadsimples                                                                                       ";
			    $sSqlSimplesEmpPrefeitura .= "        left join isscadsimplesbaixa on isscadsimplesbaixa.q39_isscadsimples = isscadsimples.q38_sequencial ";
			    $sSqlSimplesEmpPrefeitura .= "  where isscadsimples.q38_inscr = {$oEmpresa->iInscricao}                                                   ";

			    $rsSimplesEmpPrefeitura     = db_query($conn,$sSqlSimplesEmpPrefeitura);
			    $aListaSimplesEmpPrefeitura = db_utils::getCollectionByRecord($rsSimplesEmpPrefeitura);

			    if ( count($aListaSimplesEmpPrefeitura) > 0 ) {

			      foreach ($aListaSimplesEmpPrefeitura as $oSimplesEmpPrefeitura ) {

			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oSimplesEmpPrefeitura->integra_cad_empresa = $oEmpresa->iCodEmpresa;
			        $oSimplesEmpPrefeitura->munic_ibge          = $iCodIBGE;
			        $oSimplesEmpPrefeitura->dataimp             = $dtDataHoje;
			        $oSimplesEmpPrefeitura->horaimp             = db_hora();
			        $oSimplesEmpPrefeitura->processado           = "f";

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaSimples->setByLineOfDBUtils($oSimplesEmpPrefeitura);

			        try {
			          $iCodEmpSimples = $oIntegraEmpresaSimples->insertValue();
			        } catch ( Exception $eException ) {
			        	throw new Exception("ERRO-30: {$eException->getMessage()}");
			        }

			      }

			      unset($aListaSimplesEmpPrefeitura);

			      try {
			        $oIntegraEmpresaSimples->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-31: {$eException->getMessage()}");
			      }

			    }

			    $sSqlEscritoEmpPrefeitura   = " select escrito.q10_numcgm as codigo_escritorio,    ";
			    $sSqlEscritoEmpPrefeitura  .= "        escrito.q10_dtini  as datainicial,          ";
			    $sSqlEscritoEmpPrefeitura  .= "        escrito.q10_dtini  as datafinal             ";
			    $sSqlEscritoEmpPrefeitura  .= "   from escrito                                     ";
			    $sSqlEscritoEmpPrefeitura  .= "  where escrito.q10_inscr = {$oEmpresa->iInscricao} ";

			    $rsEscritoEmpPrefeitura     = db_query($conn,$sSqlEscritoEmpPrefeitura);
			    $aListaEscritoEmpPrefeitura = db_utils::getCollectionByRecord($rsEscritoEmpPrefeitura);

			    if ( count($aListaEscritoEmpPrefeitura) > 0 ) {

			      foreach ( $aListaEscritoEmpPrefeitura as $oEscritoEmpPrefeitura ) {

			        $sSqlEscritoDestino  = " select sequencial                                                      ";
			        $sSqlEscritoDestino .= "   from integra_cad_escritorio                                          ";
			        $sSqlEscritoDestino .= "  where codigo_escritorio = {$oEscritoEmpPrefeitura->codigo_escritorio} ";
			        $sSqlEscritoDestino .= "  order by sequencial desc limit 1                                      ";

			        $rsEscritoDestino      = db_query($connDestino,$sSqlEscritoDestino);

			        if ( pg_num_rows($rsEscritoDestino) == 0 ) {
			          continue;
			        }

			        $iCodEscritorioDestino = db_utils::fieldsMemory($rsEscritoDestino,0)->sequencial;

			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oEscritoEmpPrefeitura->integra_cad_empresa    = $oEmpresa->iCodEmpresa;
			        $oEscritoEmpPrefeitura->integra_cad_escritorio = $iCodEscritorioDestino;
			        $oEscritoEmpPrefeitura->munic_ibge             = $iCodIBGE;
			        $oEscritoEmpPrefeitura->dataimp                = $dtDataHoje;
			        $oEscritoEmpPrefeitura->horaimp                = db_hora();
			        $oEscritoEmpPrefeitura->processado             = "f";

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaEscritorio->setByLineOfDBUtils($oEscritoEmpPrefeitura);

			        try {
			          $iCodEmpEscritorio = $oIntegraEmpresaEscritorio->insertValue();
			        } catch ( Exception $eException ) {
			        	throw new Exception("ERRO-32: {$eException->getMessage()}");
			        }
			      }

			      unset($aListaEscritoEmpPrefeitura);

			      try {
			        $oIntegraEmpresaEscritorio->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-33: {$eException->getMessage()}");
			      }

			    }

			    $sSqlAidofEmpPrefeitura  = " select y08_codigo   as aidof,                                      ";
			    $sSqlAidofEmpPrefeitura .= "        y08_numcgm   as codigo_grafica,                             ";
			    $sSqlAidofEmpPrefeitura .= "        q09_nota     as tipo_docum,                                 ";
			    $sSqlAidofEmpPrefeitura .= "        y08_notain   as num_inicial,                                ";
			    $sSqlAidofEmpPrefeitura .= "        y08_notafi   as num_final,                                  ";
			    $sSqlAidofEmpPrefeitura .= "        y08_quantsol as qtd_solicitada,                             ";
			    $sSqlAidofEmpPrefeitura .= "        y08_quantlib as qtd_liberada,                               ";
			    $sSqlAidofEmpPrefeitura .= "        y08_dtlanc   as data_liberacao,                             ";
			    $sSqlAidofEmpPrefeitura .= "        y08_obs      as observacao                                  ";
			    $sSqlAidofEmpPrefeitura .= "   from aidof                                                       ";
			    $sSqlAidofEmpPrefeitura .= "        left join notasiss on notasiss.q09_codigo = aidof.y08_nota  ";
			    $sSqlAidofEmpPrefeitura .= "  where aidof.y08_inscr = {$oEmpresa->iInscricao}                   ";


			    $rsAidofEmpPrefeitura     = db_query($conn,$sSqlAidofEmpPrefeitura);
			    $aListaAidofEmpPrefeitura = db_utils::getCollectionByRecord($rsAidofEmpPrefeitura);

			    if ( count($aListaAidofEmpPrefeitura) > 0 ) {

			      foreach ( $aListaAidofEmpPrefeitura as $oAidofEmpPrefeitura ) {

			        $sSqlAidofDestino  = " select sequencial                                              ";
			        $sSqlAidofDestino .= "   from integra_cad_grafica                                     ";
			        $sSqlAidofDestino .= "  where codigo_grafica = {$oAidofEmpPrefeitura->codigo_grafica} ";
			        $sSqlAidofDestino .= "  order by sequencial desc limit 1                              ";

			        $rsAidofDestino    = db_query($connDestino,$sSqlAidofDestino);

			        if ( pg_num_rows($rsAidofDestino) == 0 ) {
			          continue;
			        }

			        $iCodAidofDestino = db_utils::fieldsMemory($rsAidofDestino,0)->sequencial;

			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oAidofEmpPrefeitura->integra_cad_empresa  = $oEmpresa->iCodEmpresa;
			        $oAidofEmpPrefeitura->integra_cad_grafica  = $iCodAidofDestino;
			        $oAidofEmpPrefeitura->munic_ibge           = $iCodIBGE;
			        $oAidofEmpPrefeitura->dataimp              = $dtDataHoje;
			        $oAidofEmpPrefeitura->horaimp              = db_hora();
			        $oAidofEmpPrefeitura->processado            = "f";
			        $oAidofEmpPrefeitura->serie                = '';
			        $oAidofEmpPrefeitura->num_vias             = '';
			        $oAidofEmpPrefeitura->validade             = '';

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaAidof->setByLineOfDBUtils($oAidofEmpPrefeitura);

			        try {
			          $iCodEmpAidof = $oIntegraEmpresaAidof->insertValue();
			        } catch ( Exception $eException ) {
			        	throw new Exception("ERRO-34: {$eException->getMessage()}");
			        }

			      }

			      unset($aListaAidofEmpPrefeitura);

			      try {
			        $oIntegraEmpresaAidof->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-35: {$eException->getMessage()}");
			      }

			    }

			    $aListaEstimativa = array();

			    $sSqlEstimativaEmpPrefeitura   = " select q33_codigo       as codigo_estimativa,                            ";
			    $sSqlEstimativaEmpPrefeitura  .= "      upper(q33_tiporeg) as tiporegime,                                   ";
			    $sSqlEstimativaEmpPrefeitura  .= "        q33_data         as datainicial,                                  ";
			    $sSqlEstimativaEmpPrefeitura  .= "        q36_processo     as processo,                                     ";
			    $sSqlEstimativaEmpPrefeitura  .= "        q33_obs          as observacao                                    ";
			    $sSqlEstimativaEmpPrefeitura  .= "   from varfix                                                            ";
			    $sSqlEstimativaEmpPrefeitura  .= "        left join varfixproc on varfixproc.q36_varfix = varfix.q33_codigo ";
			    $sSqlEstimativaEmpPrefeitura  .= "  where varfix.q33_inscr = {$oEmpresa->iInscricao}                        ";

			    $rsEstimativaEmpPrefeitura     = db_query($conn,$sSqlEstimativaEmpPrefeitura);
			    $aListaEstimativaEmpPrefeitura = db_utils::getCollectionByRecord($rsEstimativaEmpPrefeitura);

			    if ( count($aListaEstimativaEmpPrefeitura) > 0 ) {

			      foreach ( $aListaEstimativaEmpPrefeitura as $oEstimativaEmpPrefeitura ) {

			        /**
			         *  Define todas propriedades que não retornam do SQL
			         */
			        $oEstimativaEmpPrefeitura->integra_cad_empresa  = $oEmpresa->iCodEmpresa;
			        $oEstimativaEmpPrefeitura->munic_ibge           = $iCodIBGE;
			        $oEstimativaEmpPrefeitura->datafinal            = '';
			        $oEstimativaEmpPrefeitura->dataimp              = $dtDataHoje;
			        $oEstimativaEmpPrefeitura->horaimp              = db_hora();
			        $oEstimativaEmpPrefeitura->processado           = "f";

			        /**
			         *  Atribui o valores da base de origem ao objeto tableDataManager
			         */
			        $oIntegraEmpresaEstimativa->setByLineOfDBUtils($oEstimativaEmpPrefeitura);

			        try {
			          $iCodEmpEstimativa = $oIntegraEmpresaEstimativa->insertValue();
			        } catch ( Exception $eException ) {
			        	throw new Exception("ERRO-36: {$eException->getMessage()}");
			        }

			        /**
			         *  Como a integração é feita com COPY é necessário guardar o código gerado
			         *  em um array de objetos para posteriormente popular as tabelas de ligação
			         */
			        $oEstimativa = new stdClass();
			        $oEstimativa->iCodEstimativaOrigem  = $oEstimativaEmpPrefeitura->codigo_estimativa;
			        $oEstimativa->iCodEstimativaDestino = $iCodEmpEstimativa;
			        $aListaEstimativa[] = $oEstimativa;

			      }

			      unset($aListaEstimativaEmpPrefeitura);

			      try {
			        $oIntegraEmpresaEstimativa->persist();
			      } catch ( Exception $eException ) {
			      	throw new Exception("ERRO-37: {$eException->getMessage()}");
			      }

			      foreach ( $aListaEstimativa as $oEstimativa ) {

			        $sSqlEstimativaDetalhePrefeitura  = " select q34_ano    as ano_competencia,            ";
			        $sSqlEstimativaDetalhePrefeitura .= "        q34_mes    as mes_competencia,            ";
			        $sSqlEstimativaDetalhePrefeitura .= "        q34_numpar as parcela,                    ";
			        $sSqlEstimativaDetalhePrefeitura .= "        q34_dtval  as data_vencimento             ";
			        $sSqlEstimativaDetalhePrefeitura .= "   from varfixval                                 ";
			        $sSqlEstimativaDetalhePrefeitura .= "  where q34_codigo = {$oEstimativa->iCodEstimativaOrigem} ";

			        $rsEstimativaDetalhePrefeitura     = db_query($conn,$sSqlEstimativaDetalhePrefeitura);
			        $aListaEstimativaDetalhePrefeitura = db_utils::getCollectionByRecord($rsEstimativaDetalhePrefeitura);

			        if ( count($aListaEstimativaDetalhePrefeitura) > 0 ) {

			          foreach ( $aListaEstimativaDetalhePrefeitura as $oEstimativaDetalhePrefeitura ) {

			            /**
			             *  Define todas propriedades que não retornam do SQL
			             */
			            $oEstimativaDetalhePrefeitura->integra_empresa_estimativa = $oEstimativa->iCodEstimativaDestino;
			            $oEstimativaDetalhePrefeitura->integra_cad_empresa       = $oEmpresa->iCodEmpresa;
			            $oEstimativaDetalhePrefeitura->munic_ibge                = $iCodIBGE;
			            $oEstimativaDetalhePrefeitura->dataimp                   = $dtDataHoje;
			            $oEstimativaDetalhePrefeitura->horaimp                   = db_hora();
			            $oEstimativaDetalhePrefeitura->processado                = "f";
			            $oEstimativaDetalhePrefeitura->receita_presumida         = '';
			            $oEstimativaDetalhePrefeitura->imposto_presumido         = '';

			            /**
			             *  Atribui o valores da base de origem ao objeto tableDataManager
			             */
			            $oIntegraEstimativaDetalhe->setByLineOfDBUtils($oEstimativaDetalhePrefeitura);

			            try {
			              $iCodEstimativaDetalhe = $oIntegraEstimativaDetalhe->insertValue();
			            } catch ( Exception $eException ) {
			            	throw new Exception("ERRO-38: {$eException->getMessage()}");
			            }

			          }

			          unset($aListaEstimativaDetalhePrefeitura);

			          try {
			            $oIntegraEstimativaDetalhe->persist();
			          } catch ( Exception $eException ) {
			          	throw new Exception("ERRO-39: {$eException->getMessage()}");
			          }
			        }
			      }

			      unset($aListaEstimativa);

			    }
			  }

			  $aListaEmpresa = array();

	    }
			/******************* Fim Processamento **************************/
	  }


  } else {
    db_log(" Nenhum registro encontrado !",$sArquivoLog,$iParamLog);
  }

  /**
   * inclui programa para geração dos débitos e rebcibos
   */
  include 'integracao_externa/webiss/iss4_processarecibo.php';

} catch (Exception $eException) {

  $lErro = true;
  db_log( $eException->getMessage(), $sArquivoLog, $iParamLog);

}

if ( $lErro ) {

  db_logTitulo(" FIM PROCESSAMENTO COM ERRO",$sArquivoLog,$iParamLog);
  db_query($conn, "ROLLBACK;");
  db_query($connDestino,"ROLLBACK;");
} else {

  db_log("Limpando dados da Tabela issbaseintegracaoexterna",$sArquivoLog,$iParamLog);
  db_query($conn, "TRUNCATE issbaseintegracaoexterna");

  db_logTitulo(" FIM PROCESSAMENTO ",$sArquivoLog,$iParamLog);
  db_query($conn, "COMMIT;");
  db_query($connDestino,"COMMIT;");
}


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

function validaCpfCnpj($sCpfCnpj="") {

	$lRetorno = true;

	if ( trim($sCpfCnpj) == "" ) {

		$lRetorno = false;

	} else {

		$iLength = strlen(trim($sCpfCnpj));

		if ( $iLength == '14') {
			if ( validaCnpj($sCpfCnpj) != 0 ) {
        $lRetorno = false;
			}
		} else if ( $iLength == '11' ) {
			if ( validaCpf($sCpfCnpj) != 0 ) {
				$lRetorno = false;
			}
		} else {
			$lRetorno = false;
		}

	}

	return $lRetorno;

}

  /**
   *  0 em caso de sucesso
   *  1 em caso de cpf errado
   *  2 em caso de cpf não numérico ou se o tamanho não estiver certo.
   */
  function validaCpf($cpf){

    if((!is_numeric($cpf)) or (strlen($cpf) <> 11)){
      return 2;
    } else {
      if ( ($cpf == '11111111111') || ($cpf == '22222222222') ||
      ($cpf == '33333333333') || ($cpf == '44444444444') ||
      ($cpf == '55555555555') || ($cpf == '66666666666') ||
      ($cpf == '77777777777') || ($cpf == '88888888888') ||
      ($cpf == '99999999999') || ($cpf == '00000000000') )
      {
        return 1;
      } else {
        $cpf_dv = substr($cpf, 9,2);
      }
    }

    for($i=0; $i<=8; $i++){
      $digito[$i] = substr($cpf, $i,1);
    }

    $posicao = 10;
    $soma    = 0;

    for($i=0; $i<=8; $i++){
      $soma = $soma + $digito[$i] * $posicao;
      $posicao = $posicao - 1;
    }

    $digito[9] = $soma % 11;

    if($digito[9] < 2) {
      $digito[9] = 0;
    } else {
      $digito[9] = 11 - $digito[9];
    }

    $posicao = 11;
    $soma = 0;

    for ($i=0; $i<=9; $i++){
      $soma = $soma + $digito[$i] * $posicao;
      $posicao = $posicao - 1;
    }

    $digito[10] = $soma % 11;
    if ($digito[10] < 2){
      $digito[10] = 0;
    } else {
      $digito[10] = 11 - $digito[10];
    }

    $dv = $digito[9] * 10 + $digito[10];

    if ($dv != $cpf_dv) {
      return 1;
    } else {
      return 0;
    }

  }


  /**
   *  0 em caso de sucesso
   *  1 em caso de cnpj errado
   *  2 em caso de cnpj não numérico ou se o tamanho não estiver certo.
   *
   */
  function validaCnpj($cnpj) {

    if ((!is_numeric($cnpj)) or (strlen($cnpj) <> 14)) {
      return 2;
    } else {
      $i = 0;

      while ($i < 14) {
	      $cnpj_d[$i] = substr($cnpj,$i,1);
	      $i++;
      }
      $dv_ori = $cnpj[12] . $cnpj[13];
      $soma1 = 0;
      $soma1 = $soma1 + ($cnpj[0] * 5);
      $soma1 = $soma1 + ($cnpj[1] * 4);
      $soma1 = $soma1 + ($cnpj[2] * 3);
      $soma1 = $soma1 + ($cnpj[3] * 2);
      $soma1 = $soma1 + ($cnpj[4] * 9);
      $soma1 = $soma1 + ($cnpj[5] * 8);
      $soma1 = $soma1 + ($cnpj[6] * 7);
      $soma1 = $soma1 + ($cnpj[7] * 6);
      $soma1 = $soma1 + ($cnpj[8] * 5);
      $soma1 = $soma1 + ($cnpj[9] * 4);
      $soma1 = $soma1 + ($cnpj[10] * 3);
      $soma1 = $soma1 + ($cnpj[11] * 2);
      $rest1 = $soma1 % 11;

      if ($rest1 < 2) {
        $dv1 = 0;
      } else {
        $dv1 = 11 - $rest1;
      }
      $soma2 = 0;
      $soma2 = $soma2 + ($cnpj[0] * 6);
      $soma2 = $soma2 + ($cnpj[1] * 5);
      $soma2 = $soma2 + ($cnpj[2] * 4);
      $soma2 = $soma2 + ($cnpj[3] * 3);
      $soma2 = $soma2 + ($cnpj[4] * 2);
      $soma2 = $soma2 + ($cnpj[5] * 9);
      $soma2 = $soma2 + ($cnpj[6] * 8);
      $soma2 = $soma2 + ($cnpj[7] * 7);
      $soma2 = $soma2 + ($cnpj[8] * 6);
      $soma2 = $soma2 + ($cnpj[9] * 5);
      $soma2 = $soma2 + ($cnpj[10] * 4);
      $soma2 = $soma2 + ($cnpj[11] * 3);
      $soma2 = $soma2 + ($dv1 * 2);
      $rest2 = $soma2 % 11;

      if ($rest2 < 2) {
        $dv2 = 0;
      } else {
        $dv2 = 11 - $rest2;
      }

      $dv_calc = $dv1 . $dv2;

      if ($dv_ori == $dv_calc) {
        return 0;
      } else {
        return 1;
      }
    }
  }


  function dbValida($sValor, $sTipo) {

	  $aValorDefault = array ( 'int'   => "null",
	                            'date'  => "null",
	                            'string'=> "''" );
	  if (trim($sValor) != '') {
	    if ($sTipo == 'int') {
	      return $sValor;
	    } else {
	      $sValor = "'".$sValor."'";
	      return $sValor;
	    }
	  } else {
	    return $aValorDefault[$sTipo];
	  }
	}


?>
