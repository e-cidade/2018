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

use \ECidade\Core\Config as AppConfig;

/**
 * @package std
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br>
 */
class DBHttpRequest
{
    /**
     * @var array
     */
    private $options = array();

    /**
     * @var AppConfig
     */
    private $config;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $responseHeaders = array();

    /**
     * @param AppConfig $config
     */
    public function __construct(AppConfig $config)
    {
        $this->config = $config;
        $proxy = $this->config->get('app.proxy');

        $this->options = array(
            'baseUrl' => null,
            'headers' => array(),
            'method' => 'GET',
            'body' > null,
            'timeout' => 60,
        );

        if (!empty($proxy['tcp'])) {
            $this->options['proxy'] = 'tcp://' . $proxy['tcp'];
        }
    }

    public function addOptions($options = array())
    {
        $this->options = DBArray::merge($this->options, $options);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    public function getResponseCode()
    {
        if (!isset($this->responseHeaders['response_code'])) {
            throw new \Exception('Requisição não executada.');
        }
        return $this->responseHeaders['response_code'];
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     * @return boolean
     */
    public function send($url, $method = 'GET', array $options = array())
    {
        $options = DBArray::merge($this->options, $options);
        $this->body = $this->__sendFileWrapper($options['baseUrl'] . $url, $method, $options);
        return true;
    }

    /**
     * @param string $url
     * @param string $method
     * @param array $options
     */
    private function __sendFileWrapper($url, $method = 'GET', array $options = array())
    {
        $contextOptions = array(
            'http' => array(
            'ignore_errors' => true,
            'method' => $method,
            'timeout' => $options['timeout'],
            ),
        );

        if (!empty($options['proxy'])) {
            $contextOptions['http']['proxy'] = $options['proxy'];
        }

        if (!empty($options['headers'])) {
            $contextOptions['http']['header'] = $this->headersToString();
        }

        if (!empty($options['body'])) {
            $contextOptions['http']['content'] = $options['body'];
        }

        $context = stream_context_create($contextOptions);
        $result = @file_get_contents($url, false, $context);

        if (!empty($http_response_header)) {
            $this->responseHeaders = $this->parseResponseHeaders($http_response_header);
        }

        if ($result === false) {
            $error = error_get_last();
            throw new BusinessException("Erro ao executar requisição:\nRetorno: " . $error['message']);
        }

        return $result;
    }

    private function headersToString()
    {
        $headers = '';

        foreach ($this->options['headers'] as $header => $value) {
            $headers .= $header . ': ' . $value . "\r\n";
        }

        return $headers;
    }


    function parseResponseHeaders( $headers )
    {
        $head = array();
        foreach( $headers as $k=>$v )
        {
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) )
                $head[ trim($t[0]) ] = trim( $t[1] );
            else
            {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                    $head['response_code'] = intval($out[1]);
            }
        }
        return $head;
    }
}
