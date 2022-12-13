<?php
/**
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

namespace ECidade\WebService;

abstract class DBSoapServer
{
  private $soapServer;

  public function __construct($wsdl = null, $options = array())
  {
    /**
     * Desabilita o cache do .wsdl
     */
    ini_set('soap.wsdl_cache_enabled', '0');

    $this->soapServer = new \SoapServer($wsdl, $options);
  }

  public function setClass($class)
  {
    $this->soapServer->setClass($class);
  }

  public function addFunction($class)
  {
    $this->soapServer->addFunction($class);
  }

  public function handle()
  {
    $this->soapServer->handle();
  }

  public function getUrl($page = "")
  {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http';
    $port     = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] ? ":".$_SERVER['SERVER_PORT'] : "";
    return sprintf("%s://%s%s%s%s%s%s", $protocol, $_SERVER['SERVER_NAME'], $port, ECIDADE_REQUEST_ROOT ,$_SERVER['REQUEST_URI'], "/", $page);
  }
}
