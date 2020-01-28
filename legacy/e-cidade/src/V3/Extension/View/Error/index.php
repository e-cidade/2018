<?php 
// temporario ate houver a incorporacao da versao 3 com a 2
$this->document->setCharset('ISO-8859-1');
$this->response->setCharset('ISO-8859-1');
mb_internal_encoding('ISO-8859-1');

$this->request->get()->set('db_erro', $this->htmlMessage);
$this->request->get()->set('pagina_retorno', $this->request->server()->get('REDIRECT_URL'));

require(ECIDADE_PATH . "db_erros.php");
