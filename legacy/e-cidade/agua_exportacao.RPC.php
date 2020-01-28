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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_libdocumento.php"));

use \ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo\Leituras;
use \ECidade\Tributario\Agua\Coletor\Importacao\Importacao;
use \ECidade\Tributario\Agua\Coletor\Exportacao\Exportacao;
use \ECidade\Tributario\Agua\Coletor\Exportacao\Processamento;
use \ECidade\Tributario\Agua\Coletor\Exportacao\Arquivo;

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;

$iCodigoInstituicao = db_getsession("DB_instit");
$iCodigoUsuario     = db_getsession("DB_id_usuario");

function gerarArquivoLayout(Arquivo\Arquivo $oArquivo) {

  return Exportacao::gerarArquivoLayout(
    $oArquivo->getCodigoLayout(),
    $oArquivo->getNomeArquivo()
  );
}

function processarExportacao($iCodigoExportacao) {

  $oProcessamento = new Processamento();
  $oProcessamento->setCodigoExportacao($iCodigoExportacao);

  $oArquivoCategorias = new Arquivo\CategoriasConsumo();
  $oProcessamento->adicionarArquivo($oArquivoCategorias);

  $oArquivoEconomias = new Arquivo\Economias();
  $oProcessamento->adicionarArquivo($oArquivoEconomias);

  $oArquivoIsencoes = new Arquivo\Isencoes();
  $oProcessamento->adicionarArquivo($oArquivoIsencoes);

  $oArquivoSituacoes = new Arquivo\SituacoesLeitura();
  $oProcessamento->adicionarArquivo($oArquivoSituacoes);

  $oArquivoLeiturista = new Arquivo\Leiturista();
  $oProcessamento->adicionarArquivo($oArquivoLeiturista);

  $oArquivoLeituras = new Arquivo\Leituras();
  $oArquivoLeituras->setCodigoExportacao($iCodigoExportacao);
  $oProcessamento->adicionarArquivo($oArquivoLeituras);

  $aArquivos = $oProcessamento->processar();

  $oLayoutCategorias = gerarArquivoLayout($oArquivoCategorias);
  $oLayoutEconomias  = gerarArquivoLayout($oArquivoEconomias);
  $oLayoutIsencoes   = gerarArquivoLayout($oArquivoIsencoes);
  $oLayoutSituacoes  = gerarArquivoLayout($oArquivoSituacoes);
  $oLayoutLeiturista = gerarArquivoLayout($oArquivoLeiturista);
  $oLayoutLeituras   = gerarArquivoLayout($oArquivoLeituras);

  $aArquivos[] = $oLayoutCategorias;
  $aArquivos[] = $oLayoutEconomias;
  $aArquivos[] = $oLayoutIsencoes;
  $aArquivos[] = $oLayoutSituacoes;
  $aArquivos[] = $oLayoutLeiturista;
  $aArquivos[] = $oLayoutLeituras;

  return $aArquivos;
}

switch($oParam->exec) {

  case 'processarExportacao':

    try {

      db_inicio_transacao();

      $oExportacao = new Exportacao();
      $oDataAtual  = new DateTime();

      $oExportacao->setAno((integer) $oParam->iAno);
      $oExportacao->setMes((integer) $oParam->iMes);
      $oExportacao->setRotas(explode(',', $oParam->sRotas));
      $oExportacao->setRuas(explode(',', $oParam->sRuas));
      $oExportacao->setHoraAtual($oDataAtual->format('H:i'));
      $oExportacao->setDataAtual(new DBDate($oDataAtual->format('Y-m-d')));

      $oExportacao->setCodigoLeiturista((integer) $oParam->iCodigoLeiturista);
      $oExportacao->setCodigoInstituicao((integer) $iCodigoInstituicao);
      $oExportacao->setCodigoColetor((integer) $oParam->iCodigoColetor);
      $oExportacao->setCodigoUsuario((integer) $iCodigoUsuario);
      $oExportacao->salvar();

      $aArquivos = processarExportacao($oExportacao->getCodigo());

      $oRetorno->aArquivos         = array();
      $oRetorno->iCodigoExportacao = $oExportacao->getCodigo();

      foreach ($aArquivos as $oArquivo) {

        $oRetorno->aArquivos[] = (object) array(
          'nome' => urlencode($oArquivo->getBaseName()),
          'link' => urlencode($oArquivo->getFilePath())
        );
      }

      $oRetorno->message = urlencode('Exportação Concluída.');
      $oRetorno->erro = false;

      db_fim_transacao();

    } catch (Exception $oException) {

      db_fim_transacao($lErro = true);

      $oRetorno->message = urlencode($oException->getMessage());
      $oRetorno->erro = $lErro;
    }

    echo $oJson->encode($oRetorno);

    break;

    case 'reprocessarExportacao':

	    try {

	      db_inicio_transacao();

	    	$iCodigoExportacao = (integer) $oParam->iCodigoExportacao;
	      $aArquivos         = processarExportacao($iCodigoExportacao);
	      $aArquivosRetorno  = array();

	      foreach ($aArquivos as $oArquivo) {

	      	$oStdArquivo = new stdClass;
	      	$oStdArquivo->sNome = urlencode($oArquivo->getFileName());
	      	$oStdArquivo->sCaminho = urlencode($oArquivo->getFilePath());
	      	$aArquivosRetorno[] = $oStdArquivo;
	      }

	      $oRetorno->aArquivos = $aArquivosRetorno;
	      $oRetorno->message   = urlencode('Reprocessamento Concluído.');
	      $oRetorno->erro      = false;

	      db_fim_transacao();

	  	} catch (Exception $oException) {

        db_fim_transacao($lErro = true);

				$oRetorno->erro    = $lErro;
				$oRetorno->message = urlencode($oException->getMessage());
	  	}

	  	echo $oJson->encode($oRetorno);

    break;

  case 'importar':

    try {

      $oZip = new ZipArchive;
      $iCodigoExportacao = (int) $oParam->iCodigoExportacao;

      if (empty($_FILES['arquivo_importacao'])) {
        throw new Exception('Arquivo não informado.');
      }

      if ($_FILES['arquivo_importacao']['error'] != UPLOAD_ERR_OK) {
        throw new Exception('Não foi possível processar o arquivo.');
      }

      if (!$oZip->open($_FILES['arquivo_importacao']['tmp_name'])) {
        throw new Exception('Não foi possível ler o arquivo.');
      }

      $sCaminhoExtracao = 'tmp/agua/importacao_' . $iCodigoExportacao . '_' . time();
      if (!$oZip->extractTo($sCaminhoExtracao)) {
        throw new Exception('Não foi possível descompactar o arquivo.');
      }

      $oDiretorio = new DirectoryIterator($sCaminhoExtracao);
      $iQuantidadeArquivos = 0;
      foreach ($oDiretorio as $oConteudoDiretorio) {

        if ($oConteudoDiretorio->isFile() && !$oConteudoDiretorio->isDot()) {

          $sCaminhoArquivo = $oConteudoDiretorio->getPathname();
          $iQuantidadeArquivos++;
        }
      }

      if ($iQuantidadeArquivos > 1) {
        throw new \BusinessException('Pacote inválido. Mais de um arquivo encontrado dentro do pacote.');
      }

      if ($iQuantidadeArquivos === 0) {
        throw new \BusinessException('Pacote inválido. Nenhum arquivo encontrado dentro do pacote.');
      }

      db_inicio_transacao();

      $oImportacao = new Importacao;
      $oImportacao->setCodigoExportacao($oParam->iCodigoExportacao);
      $oImportacao->setCodigoUsuario($iCodigoUsuario);
      $oImportacao->setCaminhoArquivo($sCaminhoArquivo);
      $oImportacao->processar();

      db_fim_transacao($lErro = false);

      $oRetorno->erro    = false;
      $oRetorno->message = urlencode('Importação realizada com sucesso.');

    } catch (Exception $oException) {

      db_fim_transacao($lErro = true);

      $oRetorno->erro    = $lErro;
      $oRetorno->message = urlencode($oException->getMessage());
    }

    echo $oJson->encode($oRetorno);
    break;

	case 'getDadosExportacao':

		include(modification('classes/db_aguacoletorexporta_classe.php'));
		include(modification('classes/db_aguacoletorexportadados_classe.php'));

		try{

			$oDaoAguaColetorExporta = new cl_aguacoletorexporta();
			$sCampos = "x49_sequencial, x49_aguacoletor, x46_descricao, x49_instit, x49_anousu, x49_mesusu, x49_situacao, x49_db_layouttxt";
			$sSqlAguaColetorExporta = $oDaoAguaColetorExporta->sql_query(null, "*", "x49_sequencial", "x49_sequencial = $oParam->codExportacao");
			$rsAguaColetorExporta   = $oDaoAguaColetorExporta->sql_record($sSqlAguaColetorExporta);

			if($oDaoAguaColetorExporta->numrows > 0) {

				$oAguaColetorExporta        = db_utils::fieldsMemory($rsAguaColetorExporta, 0);
				$oRetorno->x49_sequencial   = $oAguaColetorExporta->x49_sequencial;
				$oRetorno->x49_aguacoletor  = $oAguaColetorExporta->x49_aguacoletor;
				$oRetorno->x46_descricao    = $oAguaColetorExporta->x46_descricao;
				$oRetorno->x49_instit       = $oAguaColetorExporta->x49_instit;
				$oRetorno->x49_anousu       = $oAguaColetorExporta->x49_anousu;
				$oRetorno->x49_mesusu       = $oAguaColetorExporta->x49_mesusu;
        $oRetorno->x49_situacao     = $oAguaColetorExporta->x49_situacao;
				$oRetorno->lLayoutTarifa    = $oAguaColetorExporta->x49_db_layouttxt == Leituras::CODIGO_LAYOUT;
			}

			$oDaoAguaColetorExportaDados = new cl_aguacoletorexportadados();

			$sCampos = "x50_rota, x06_descr, x50_codlogradouro, x50_nomelogradouro, x07_nroini, x07_nrofim, count(distinct x50_sequencial)";
			$sSqlAguaColetorExportaDados = $oDaoAguaColetorExportaDados->sql_query_dados(null, $sCampos, "x50_rota", "x50_aguacoletorexporta = $oAguaColetorExporta->x49_sequencial group by x50_rota, x06_descr, x50_codlogradouro, x50_nomelogradouro, x07_nroini, x07_nrofim");
			$rsAguaColetorExportaDados   = $oDaoAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);
			if($oDaoAguaColetorExportaDados->numrows > 0) {

				for($i = 0; $i < $oDaoAguaColetorExportaDados->numrows; $i++) {
					$oRetorno->aRotasRuas[] = db_utils::fieldsMemory($rsAguaColetorExportaDados, $i);
				}
			}

		} catch (Exception $oErro) {

			$oRetorno->status  = 2;
			$oRetorno->message = urlencode($oErro->getMessage());

		}

		echo $oJson->encode($oRetorno);

		break;

	case 'vericaRotaRuaSituacao':
    include(modification('classes/db_aguacoletorexporta_classe.php'));
    include(modification('classes/db_aguacoletorexportadados_classe.php'));

    try {

    	$oDaoAguaColetorExportaDados = new cl_aguacoletorexportadados();
    	$sWhere                      = "x49_anousu = $oParam->anousu and x49_mesusu = $oParam->mesusu and x50_rota = $oParam->rota and x50_codlogradouro = $oParam->logradouro and x49_situacao  = 1";
    	$sSqlAguaColetorExportaDados = $oDaoAguaColetorExportaDados->sql_query_dados(null, "count(*)", null, $sWhere);
    	$rsAguaColetorExportaDados   = $oDaoAguaColetorExportaDados->sql_record($sSqlAguaColetorExportaDados);
    	$oAguaColetorExportaDados    = db_utils::fieldsMemory($rsAguaColetorExportaDados, 0);
      $oRetorno->count             = $oAguaColetorExportaDados->count;

      $oDaoAguaColetorExportaDadosLeitura = db_utils::getDao('aguacoletorexportadadosleitura');

      $sWhere = "    b.x49_anousu        = {$oParam->anousu}
                 and b.x49_mesusu        = {$oParam->mesusu}
                 and aguacoletorexportadados.x50_rota          = {$oParam->rota}
                 and aguacoletorexportadados.x50_codlogradouro = {$oParam->logradouro}
                 and x21_tipo            = 3
                 and x21_status          = 1";

      $sSqlAguaColetorExportaDadosLeitura = $oDaoAguaColetorExportaDadosLeitura->sql_query(null, 'count(*)', null, $sWhere);
      $rsAguaColetorExportaDadosLeitura   = $oDaoAguaColetorExportaDadosLeitura->sql_record($sSqlAguaColetorExportaDadosLeitura);
      $oAguaColetorExportaDadosLeitura    = db_utils::fieldsMemory($rsAguaColetorExportaDadosLeitura, 0);

      $oRetorno->iQteLeiturasLog = $oAguaColetorExportaDadosLeitura->count;


    }catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }

    echo $oJson->encode($oRetorno);

		break;
}
