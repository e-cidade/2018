<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("libs/db_conn.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/smtp.class.php"));

$conn = @pg_connect(  "host={$DB_SERVIDOR} "
                     ."dbname={$DB_BASE} "
                     ."port={$DB_PORTA} "
                     ."user={$DB_USUARIO} "
                     ."password={$DB_SENHA}" );

if ( !$conn ) {

  echo "Contate com Administrador do Sistema! (Conexão Inválida.) <br/>Sessão terminada, feche seu navegador!\n";
  exit;
}

pg_query("select fc_startsession();");

$oGet = db_utils::postMemory($_GET);

/**
 * Retornamos a class da imagem ativa para o background
 */
$oPreferenciaEcidade = new PreferenciaEcidade();
$sClassAtiva         = $oPreferenciaEcidade->getPreferenciaTelaLogin()->sClassAtiva;

/**
 * Setamos como default o template do formulario de primeiro acesso
 */
$sFormulario = "forms/db_frmprimeiroAcesso.php";

if (isset($oGet->_)) {

  $oDaoDbUsuario = db_utils::getDao("db_usuarios");
  $rsDbUsuarios = $oDaoDbUsuario->sql_record( $oDaoDbUsuario->sql_query_file( null,
                                                                             "*",
                                                                             null,
                                                                             "usuext = 0 and usuarioativo = 3"
                                                                             . " and administrador = 0" ) );

  if (!$rsDbUsuarios) {

    $sErro = _M("configuracao.configuracao.primeiroAcesso.token_invalido");
    include(modification("forms/db_frmTemplatePrimeiroAcesso.php"));
    return false;
  }

  $lFound = true;
  for ($iCount = 0; $iCount < pg_num_rows($rsDbUsuarios); $iCount++) {

    $oUsuarioSistema = new UsuarioSistema( db_utils::fieldsMemory($rsDbUsuarios, $iCount)->id_usuario );
    $lFound = ($oUsuarioSistema->getToken() == $oGet->_);

    if ($lFound) {
      break;
    }
  }

  if (!$lFound) {

    $sErro = _M("configuracao.configuracao.primeiroAcesso.token_invalido");
    include(modification("forms/db_frmTemplatePrimeiroAcesso.php"));
    return false;
  }

  try {
    $oUsuarioSistema->validaPrimeiroAcesso($oGet->_);
  } catch (Exception $e) {

    $sErro = $e->getMessage();
    include(modification("forms/db_frmTemplatePrimeiroAcesso.php"));
    return false;
  }

  $sFormulario = "forms/db_frmprimeiroAcessoSenha.php";
  include(modification("forms/db_frmTemplatePrimeiroAcesso.php"));

} else {

  /**
   * Valida se existe um servidor de e-mail configurado
   */
  try {

    $oSmtp = new Smtp();
    header('Location: login.php');
  } catch(Exception $e) {

    include(modification("forms/db_frmTemplatePrimeiroAcesso.php"));
  }
}