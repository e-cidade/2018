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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libsys.php"));

# Include AgataAPI class
include_once modification("dbagata/classes/core/AgataAPI.class");

$objGet  = db_utils::postmemory($_GET);

ini_set("error_reporting","E_ALL & ~NOTICE");

# Instantiate AgataAPI
$clagata = new cl_dbagata("issqn/iss4_relinconsistenciassimples.agt");

$api = $clagata->api;

$api->setParameter('$head1', "Lista das Inconsistências Geradas");
$api->setParameter('$head2', "Arquivo: ".$objGet->q17_nomearq);
$api->setParameter('$head3', "");
$api->setParameter('$head4', "");
$api->setParameter('$head5', "");
$api->setParameter('$head6', "");
$api->setParameter('$cod_arquivo', $objGet->q17_sequencial);
$api->setParameter('$cod_tipo', $objGet->q49_tipo);

//teste de modificacao de um order by
$xml = $api->getReport();
$xml["Report"]["DataSet"]["Query"]["OrderBy"]  = "issarqsimplesreg.q23_seqreg asc";
if ($objGet->q49_tipo == 3){
   $xml["Report"]["DataSet"]["Query"]["Where"] = "q23_issarqsimples=".$objGet->q17_sequencial;
}
$api->setReport($xml);
$api->setLayout('dbseller2');

$ok = $api->generateReport();
if (!$ok){
  echo $api->getError();
}else{
  db_redireciona($clagata->arquivo);
}