<?php

namespace ECidade\RecursosHumanos\ESocial\Integracao;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use \ECidade\V3\Extension\Registry;
use \ECidade\Core\Config;
use DBHttpRequest;
use Exception;

/**
 * Classe responsável pelo envio dos dados do eSocial para a API do e-cidade
 */
class ESocial
{
    /**
     * Classe para requisição HTTP
     *
     * @var DBHttpRequest
     */
    private $httpRequest;

    /**
     * Configuração da aplicação
     *
     * @var Config
     */
    private $config;

    /**
     * Recurso para envio dos dados
     *
     * @var string
     */
    private $recurso;

    /**
     * Dados a ser enviados
     *
     * @var array|\stdClass
     */
    private $dados;

    public function __construct(Config $config, $recurso)
    {
        $this->config = $config;

        $this->validaConfiguracao();

        $dadosAPI = $this->config->get('app.api');
        $httpRequest = new DBHttpRequest(Registry::get('app.config'));
        $httpRequest->addOptions(array(
            'baseUrl' => $dadosAPI['esocial']['url'] ,
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            )
        ));
        $this->httpRequest = $httpRequest;

        $httpRequest->addOptions(array(
            'headers' => array(
                'X-Access-Token' => $this->login()
            )
        ));

        $this->recurso = $recurso;
    }

    /**
     * Seta os dados a ser enviados
     *
     * @param \stdClass[] $dados
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    /**
     * Realiza a requisição enviando os dados para API
     */
    public function request()
    {
        $data = json_encode($this->dados);

        $this->httpRequest->send($this->recurso, 'POST', array(
            'body' => $data
        ));

        $result = json_decode($this->httpRequest->getBody());

        if ($this->httpRequest->getResponseCode() >= 400) {
            throw new Exception($result->message);
        }
        return $result;
    }

    /**
     * Retorna o código de resposta HTTP da requisição
     *
     * @return integer
     */
    public function getResponseCode()
    {
        return $this->httpRequest->getResponseCode();
    }

    /**
     * Valida se foi configurado o acesso a API.
     * @throws Exception
     * @return void
     */
    private function validaConfiguracao()
    {
        $dadosAPI = $this->config->get('app.api');
        if (empty($dadosAPI['esocial']['url']) ||
            empty($dadosAPI['esocial']['login']) ||
            empty($dadosAPI['esocial']['password'])) {
            throw new Exception("Entre em contato com o administrador do sistema para configurar acesso ao eSocial.");
        }
        return true;
    }

    /**
     * Efetua o login na API do eSocial
     *
     * @return string
     */
    private function login()
    {
        $dadosAPI = $this->config->get('app.api');
        unset($dadosAPI['esocial']['url']);

        $this->httpRequest->send('/auth/login', 'POST', array(
            'body' => \json_encode((object) $dadosAPI['esocial'])
        ));

        $result = json_decode($this->httpRequest->getBody());

        if (!isset($result->access_token)) {
            throw new Exception("Erro ao efetuar login na API.");
        }

        return $result->access_token;
    }
}
