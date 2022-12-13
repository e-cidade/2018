<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$HTTP_SERVER_VARS["PHP_SELF"]  = "";
$HTTP_SERVER_VARS['HTTP_HOST'] = 'localhost';

require_once(modification("libs/db_stdlib.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

db_app::import("exceptions.*");

try{

  $oTaskManager = TaskManager::getInstance();

  if ( !$oTaskManager->iniciarServico() ) {

    echo "Serviço esta sendo Executado: ";
    echo " \n  Processo    - ".$oTaskManager->getPIDProcesso();
    echo " \n  Data Inicio - ".$oTaskManager->getDataInicio() . " desde as " . $oTaskManager->getHoraInicio();
  }
} catch( BusinessException $eErroRegraNegocio) {
  echo  "Erro Sistema : " . $eErroRegraNegocio->getMessage();
} catch( DBException       $eErroDataBase) {
  echo  "Erro no Banco: " . $eErroDataBase->getMessage();
}
echo "\n\n\n";