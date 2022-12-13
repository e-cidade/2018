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

session_start();
$_SESSION["DB_itemmenu_acessado"] = "0";

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification('model/configuracao/SkinService.service.php'));

session_unregister("DB_instit");
session_unregister("DB_Area");

$sDataSistema   = date('Y-m-d', db_getsession("DB_datausu", false) ?: time());
$iUsuarioLogado = db_getsession("DB_id_usuario");


/* 
 * Atualizacao dos dados do usuario
 */
$oUsuario = new UsuarioSistema($iUsuarioLogado);

/**
 * Caso usuário não esteja atualizado ele não tenha cancelado a atualizacao do cadastro
 * 
 */
if ( !$oUsuario->isAtualizado() && ( !isset($_SESSION['DB_atualiza_cadastro']) || $_SESSION['DB_atualiza_cadastro'] === true ) ) {
  db_redireciona('con4_atualizacadastro001.php');
  exit;
}


if (session_is_registered("DB_uol_hora")) {

  db_query("update db_usuariosonline
               set uol_arquivo = '',
                   uol_modulo = 'Selecionando Instituição',
                   uol_inativo = ".time()."
             where uol_id = ".db_getsession("DB_id_usuario")."
               and uol_ip = '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."'
               and uol_hora = ".db_getsession("DB_uol_hora")) or die("Erro(26) atualizando db_usuariosonline");

  
  $sSqlInstit =  "select c.codigo, 
  		                  c.nomeinst, 
  		                  c.figura, db21_tipoinstit
                   from db_config c
             inner join db_userinst u on u.id_instit = c.codigo
                  where c.db21_ativo = 1 
  		              and (c.db21_datalimite is null or  c.db21_datalimite > '$sDataSistema')
  		              and u.id_usuario =  $iUsuarioLogado
               order by c.prefeitura desc, c.codigo" ;
   
  
  if (db_getsession("DB_id_usuario") == "1" || db_getsession('DB_administrador') == "1") {
  	
  	$sSqlInstit = "   select codigo, 
  	                         nomeinst, 
  	                         figura, 
  	                         db21_tipoinstit 
  	                    from db_config
  	                   where (db21_datalimite is null or db21_datalimite <  '$sDataSistema') 
  	                order by prefeitura desc, codigo";
  	
  }  	
  	
  $rsInstituicoes = db_query( $sSqlInstit);
}
?>
<html>
  <?php

    $oDBReleaseNote = new DBReleaseNoteSistema(db_getsession('DB_id_usuario'), "nota_geral_01");
    $tem_atualizacoes = $oDBReleaseNote->check();

    $oSkin = new SkinService();
    include(modification( $oSkin->getPathFile("instit.php")) );

    if ($tem_atualizacoes) {

      $sScriptChangelog  = "<script src=\"scripts/classes/configuracao/DBViewReleaseNote.classe.js\" type=\"text/javascript\"></script>\n";
      $sScriptChangelog .= "<script type=\"text/javascript\">\n";
      $sScriptChangelog .= " var oDBReleaseNote = new DBViewReleaseNote(null, true); \n";
      $sScriptChangelog .= " oDBReleaseNote.show();                                  \n";
      $sScriptChangelog .= "</script>";

      echo $sScriptChangelog;

    }

  ?>
</html>
