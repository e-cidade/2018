<?php
require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/JSON.php');

use \ECidade\V3\Extension\Registry;
use ECidade\RecursosHumanos\ESocial\DadosESocial;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\Integracao\FormatterFactory;
use ECidade\RecursosHumanos\ESocial\Agendamento\Evento;

$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case "getEmpregadores":
            $campos = ' distinct z01_numcgm as cgm, z01_cgccpf as documento, z01_nome as nome, r70_instit as instituicao';
            $dao = new cl_rhlota();
            $sql = $dao->sql_query_lota_cgm(null, $campos, 'z01_numcgm');
            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException("Ocorreu um erro ao consultar os CGM vinculados as lotações.\nContate o suporte.");
            }

            if (pg_num_rows($rs) == 0) {
                throw new Exception("Não existe empregadores cadastrados na base.");
            }

            $oRetorno->empregadores = db_utils::getCollectionByRecord($rs);
            break;

        case "empregador":
            if (!file_exists($oParam->sPath)) {
                throw new Exception("Houve um erro ao realizar upload do arquivo. Tente novamente.");
            }

            $empregador = new \stdClass();
            $empregador->inscricao = $oParam->documento;
            $empregador->razao_social = $oParam->razao_social;
            $empregador->tipo_inscricao = strlen($oParam->documento) == 11 ? 'cpf' : 'cnpj';
            $empregador->senha = $oParam->senha;
            $empregador->certificado = base64_encode(file_get_contents($oParam->sPath));

            $exportar = new ESocial(Registry::get('app.config'), Recurso::CADASTRO_EMPREGADOR);
            $exportar->setDados(array($empregador));
            $retorno = $exportar->request();
            $oRetorno->sMessage = "Certificado enviado com sucesso.";

            unlink($oParam->sPath);
            break;

        case "agendarEmpregadorEObras":
            $dadosESocial = new DadosESocial();

            $dadosESocial->setReponsavelPeloPreenchimento($oParam->cgm);
            $dadosDoPreenchimento = $dadosESocial->getPorTipo(Tipo::EMPREGADOR);

            $formatter = FormatterFactory::get(Tipo::S1000);
            $dadosDoEmpregador = $formatter->formatar($dadosDoPreenchimento);

            $formatter = FormatterFactory::get(Tipo::S1005);
            $dadosObras = $formatter->formatar($dadosDoPreenchimento);

            $eventoFila = new Evento(TIPO::S1000, $oParam->cgm, $oParam->cgm, $dadosDoEmpregador[0]);
            $eventoFila->adicionarFila();

            $eventoFila = new Evento(TIPO::S1005, $oParam->cgm, $oParam->cgm, $dadosObras[0]);
            $eventoFila->adicionarFila();

            $oRetorno->sMessage = "Dados do empregador agendado para envio.";
            break;
    }
    db_fim_transacao(false);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);
