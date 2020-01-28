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

use ECidade\Configuracao\Formulario\Repository\Formulario;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/JSON.php"));

$oJson        = JSON::create();
$oParametros  = $oJson->parse(str_replace("\\", "", $_POST["json"]));

$oRetorno     = new stdClass();
$oRetorno->iStatus   = "1";
$oRetorno->erro      = false;
$oRetorno->sMensagem = "";

/**
 * Caminho das mensagens do programa
 */

try {
    switch ($oParametros->exec) {
        case 'getLinhasDoLayout':
              $iCodigoLayout   = (int)$oParametros->codigo_layout;
              $oDaoLayoutLinha = new cl_db_layoutlinha();

              $sSqlLinha        = $oDaoLayoutLinha->sql_query_file(null, "*", "db51_codigo", "db51_layouttxt={$iCodigoLayout}");
              $rsLinhas         = db_query($sSqlLinha);
            if (!$rsLinhas) {
                throw new DBException("Erro ao pesquisar dados da linha do layout");
            }
              $oRetorno->linhas = db_utils::getCollectionByRecord($rsLinhas);
            break;

        case 'getCamposDaLinha':
              $iCodigoLinha    = (int)$oParametros->codigo_linha;
              $oDaoLayoutCampos = new cl_db_layoutcampos();

              $sSqlCampo        = $oDaoLayoutCampos->sql_query_file(null, "db52_codigo, db52_descr", "db52_codigo", "db52_layoutlinha={$iCodigoLinha}");
              $rsCampos         = db_query($sSqlCampo);
            if (!$rsCampos) {
                throw new DBException("Erro ao pesquisar dados dos campos do layout");
            }
              $oRetorno->campos = db_utils::getCollectionByRecord($rsCampos);
            break;

        case 'getFormulariosESocial':
            $oRetorno->formularios = array();

            $aFormularios = Formulario::getByVersaoAtual();
            foreach ($aFormularios as $oFormulario) {
                if (trim($oFormulario->getCarga()) == '') {
                    continue;
                }

                $oFormularioRetorno                = new \stdClass();
                $oFormularioRetorno->codigo        = $oFormulario->getCodigo();
                $oFormularioRetorno->nome          = $oFormulario->getNome();
                $oFormularioRetorno->identificador = $oFormulario->getIdentificador();
                $oRetorno->formularios[]           = $oFormularioRetorno;
            }
            break;
    }
} catch (Exception $eErro) {
    $oRetorno->status   = 2;
    $oRetorno->erro     = true;
    $oRetorno->mensagem = $eErro->getMessage();
    db_fim_transacao(true);
}
echo $oJson->stringify($oRetorno);
