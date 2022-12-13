<?
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_permissao_classe.php"));
require_once(modification("libs/exceptions/DBException.php"));


$oJson    = \JSON::create();
$oRetorno = new stdClass();

$oRetorno->erro = false;
$oRetorno->sMensagem = '';

$oParametros = $oJson->parse(str_replace("\\", "", $_POST["json"]));

try {

  db_inicio_transacao();

  switch ($oParametros->sExecucao) {

    case "duplicarPermissoes":

      $cldb_permissao = new cl_db_permissao;

      $sUsuario      = "";
      $vir           = "";
      $lTemPermissao = false;

      $iItem = split("_",$oParametros->lista);
      for ($i = 0; $i < sizeof($iItem); $i++) {

      	$sNomeUsuario            = '';
      	$aDadosPermissoesOrigem  = array();
      	$aDadosPermissoesDestino = array();

        if ($iItem[$i] != "") {

        	/**
        	 * Busca o nome do usuario
        	 */
        	$oDaoUsuario   = db_utils::getDao("db_usuarios");
        	$sWhereUsuario = "id_usuario = {$iItem[$i]}";;
        	$sSqlUsuario   = $oDaoUsuario->sql_query_file(null, "nome", null, $sWhereUsuario);
        	$rsUsuario     = $oDaoUsuario->sql_record($sSqlUsuario);

        	if ( $oDaoUsuario->numrows > 0 ) {
        		$sNomeUsuario = db_utils::fieldsMemory($rsUsuario, 0)->nome;
        	}

        	/**
           * Verifica as permissoes de um usuario no ano de destino e armazena em um array
        	 */
          $sSqlPermissoesDestino = "select * ";
    			$sSqlPermissoesDestino .= " from db_permissao ";
    			$sSqlPermissoesDestino .= "where id_usuario = {$iItem[$i]} and anousu = {$oParametros->anodestino}";

          $rsPermissoesDestino     = db_query($sSqlPermissoesDestino);
          $iTotalPermissoesDestino = pg_num_rows($rsPermissoesDestino);

          if ( $iTotalPermissoesDestino > 0 ) {
          	$aDadosPermissoesDestino = db_utils::getCollectionByRecord($rsPermissoesDestino);
          }

          /**
           * Verifica as permissoes que o usuario possuia no ano de origem e armazena em um array
           */
          $sSqlPermissoesOrigem  = "select *";
          $sSqlPermissoesOrigem .= " from db_permissao ";
    			$sSqlPermissoesOrigem .= "where db_permissao.id_usuario = {$iItem[$i]} and anousu = {$oParametros->anoorigem}";

          $rsPermissoesOrigem     = db_query($sSqlPermissoesOrigem);
          $iTotalPermissoesOrigem = pg_num_rows($rsPermissoesOrigem);

          if ( $iTotalPermissoesOrigem > 0 ) {
          	$aDadosPermissoesOrigem = db_utils::getCollectionByRecord($rsPermissoesOrigem);
          }

          /**
           * Caso nao tenham sido encontradas permissoes para o usuario no ano de destino, incluimos as permissoes do ano
           * de origem
           */
          if ( $iTotalPermissoesDestino == 0 ) {

          	foreach ( $aDadosPermissoesOrigem as $oDadosPermissao ) {

          		$oDaoDbPermissao                 = db_utils::getDao("db_permissao");
    	      	$oDaoDbPermissao->id_usuario     = $oDadosPermissao->id_usuario;
    	      	$oDaoDbPermissao->id_item        = $oDadosPermissao->id_item;
    	      	$oDaoDbPermissao->permissaoativa = $oDadosPermissao->permissaoativa;
    	      	$oDaoDbPermissao->anousu         = $oParametros->anodestino;
    	      	$oDaoDbPermissao->id_instit      = $oDadosPermissao->id_instit;
    	      	$oDaoDbPermissao->id_modulo      = $oDadosPermissao->id_modulo;
              $oDaoDbPermissao->incluir(
    	      		                       	$oDadosPermissao->id_usuario,
    	      		                       	$oDadosPermissao->id_item,
    	      		                       	$oParametros->anodestino,
    	      		                       	$oDadosPermissao->id_instit,
    	      		                       	$oDadosPermissao->id_modulo
    	      		                       );

    	      	if ( $oDaoDbPermissao->erro_status == "0" ) {
    	      		throw new DBException($oDaoDbPermissao->erro_msg);
    	      	}
          	}
          } else {

          	/**
          	 * Caso existam permissoes cadastradas no ano de destino para o usuario, verificamos a ação a ser executada de
          	 * acordo com a opção escolhida
          	 */
          	switch ( $oParametros->acao ) {

          		/**
          		 * Ação 1: Padrão
          		 * Não insere nenhuma permissão
          		 */
          		case 0:

          			$sUsuario     .= $vir.$sNomeUsuario;
          			$vir           = ", ";
          			$lTemPermissao = true;
          			break;

          		/**
          		 * Ação 2: Substituir
          		 * Remove as permissões existentes para o ano de destino, de acordo com o usuario, ano, instituicao e modulo,
          		 * e insere as permissões do ano de origem
          		 */
          		case 1:

          			/**
          			 * 1º Excluimos as permissoes existentes
          			 */
          			if ( $iTotalPermissoesDestino > 0 ) {

    	      			$oDaoExcluiPermissaoDestino = db_utils::getDao("db_permissao");

    	      			foreach ( $aDadosPermissoesDestino as $oDadosPermissoesDestino ) {

    		      			$oDaoExcluiPermissaoDestino->excluir(
    		      				                                  	$oDadosPermissoesDestino->id_usuario,
    		      				                                  	null,
    		      				                                  	$oParametros->anodestino,
    		      				                                  	$oDadosPermissoesDestino->id_instit,
    		      				                                  	$oDadosPermissoesDestino->id_modulo
    		      				                                  );

    		      			if ( $oDaoExcluiPermissaoDestino->erro_status == "0" ) {

    		      				throw new DBException($oDaoExcluiPermissaoDestino->erro_msg);
    		      			}
    	      			}
          			}

          			/**
          			 * 2º Incluimos as permissoes do ano de origem
          			 */
          			if ( $iTotalPermissoesOrigem > 0 ) {

          				$oDaoInclusaoPermissoes = db_utils::getDao("db_permissao");

          				foreach ( $aDadosPermissoesOrigem as $oDadosPermissaoOrigem ) {

          					$oDaoInclusaoPermissoes->id_usuario     = $oDadosPermissaoOrigem->id_usuario;
          					$oDaoInclusaoPermissoes->id_item        = $oDadosPermissaoOrigem->id_item;
          					$oDaoInclusaoPermissoes->permissaoativa = $oDadosPermissaoOrigem->permissaoativa;
          					$oDaoInclusaoPermissoes->anousu         = $oParametros->anodestino;
          					$oDaoInclusaoPermissoes->id_instit      = $oDadosPermissaoOrigem->id_instit;
          					$oDaoInclusaoPermissoes->id_modulo      = $oDadosPermissaoOrigem->id_modulo;
          					$oDaoInclusaoPermissoes->incluir(
          						                              	$oDadosPermissaoOrigem->id_usuario,
          						                              	$oDadosPermissaoOrigem->id_item,
          						                              	$oParametros->anodestino,
          						                              	$oDadosPermissaoOrigem->id_instit,
          						                              	$oDadosPermissaoOrigem->id_modulo
          						                              );

          					if ( $oDaoInclusaoPermissoes->erro_status == "0" ) {

          						throw new DBException($oDaoInclusaoPermissoes->erro_msg);
          					}
          				}
          			}
          			break;

          		/**
          		 * Ação 3: Acrescentar
          		 * Apenas acrescenta as permissoes existentes para o ano de origem, mas que nao existem no ano de destino
          		 */
          		case 2:

          			$aPermissoesInclusao = array();
          			foreach ( $aDadosPermissoesOrigem as $oDadosPermissaoOrigem ) {

    	      			$lPermissaoExistente = false;
          				foreach ( $aDadosPermissoesDestino as $oDadosPermissoesDestino ) {

          					if ( $oDadosPermissaoOrigem->id_usuario == $oDadosPermissoesDestino->id_usuario &&
          					   	 $oDadosPermissaoOrigem->id_item    == $oDadosPermissoesDestino->id_item    &&
          					   	 $oDadosPermissaoOrigem->id_instit  == $oDadosPermissoesDestino->id_instit  &&
          					   	 $oDadosPermissaoOrigem->id_modulo  == $oDadosPermissoesDestino->id_modulo
          					   ) {
          						$lPermissaoExistente = true;
          					}
          				}
          			  if ( !$lPermissaoExistente ) {
          			  	$aPermissoesInclusao[] = $oDadosPermissaoOrigem;
          			  }
          			}

          			if ( count($aPermissoesInclusao) > 0 ) {

    		      		$oDaoInclusaoPermissoes = db_utils::getDao("db_permissao");
    	      			foreach ( $aPermissoesInclusao as $oPermissaoInclusao ) {

    		      			$oDaoInclusaoPermissoes->id_usuario     = $oPermissaoInclusao->id_usuario;
    		      			$oDaoInclusaoPermissoes->id_item        = $oPermissaoInclusao->id_item;
    		      			$oDaoInclusaoPermissoes->permissaoativa = $oPermissaoInclusao->permissaoativa;
    		      			$oDaoInclusaoPermissoes->anousu         = $oParametros->anodestino;
    		      			$oDaoInclusaoPermissoes->id_instit      = $oPermissaoInclusao->id_instit;
    		      			$oDaoInclusaoPermissoes->id_modulo      = $oPermissaoInclusao->id_modulo;
    		      			$oDaoInclusaoPermissoes->incluir(
    		      			                              		$oPermissaoInclusao->id_usuario,
    		      			                              		$oPermissaoInclusao->id_item,
    		      			                              		$oParametros->anodestino,
    		      			                              		$oPermissaoInclusao->id_instit,
    		      			                              		$oPermissaoInclusao->id_modulo
    		      			                                );

    		      			if ( $oDaoInclusaoPermissoes->erro_status == "0" ) {

    		      				throw new DBException($oDaoInclusaoPermissoes->erro_msg);
    		      			}
    	      			}
          			}
          			break;
          	}
          }

          /**
           * Verifica se as permissoes para o perfil do usuario devem ser duplicadas
           */
          if ($oParametros->duplicaperfil == "S") {

            /**
             * verifica se tem perfil cadastrado para este usuario
             * se tiver, gravar na db_permissao os itens do perfil.
             */
            $sqlperfil    = "select * from db_permherda where id_usuario = ".$iItem[$i];
            $resultperfil = db_query($sqlperfil);
            $linhasperfil = pg_num_rows($resultperfil);

            if ($linhasperfil > 0) {

              $perfil = "";
              $virg   = "";
              for ($p = 0 ; $p < $linhasperfil; $p++) {

                db_fieldsmemory($resultperfil,$p);
                $perfil .= $virg.$id_perfil;
                $virg    = ",";
              }

              $sqlduplicaperfil  = " select distinct on(id_item, permissaoativa, anousu, id_instit, id_modulo) * ";
              $sqlduplicaperfil .= "   from db_permissao ";
              $sqlduplicaperfil .= "  where db_permissao.id_usuario in ({$perfil}) and anousu = {$oParametros->anoorigem} and id_instit in ( select id_instit from db_userinst where db_userinst.id_usuario = {$iItem[$i]} ) ";
              $sqlduplicaperfil .= " and ( select count(*) from db_permissao a where a.id_usuario = {$iItem[$i]} and a.id_item = db_permissao.id_item and a.anousu = {$oParametros->anodestino} and a.id_instit = db_permissao.id_instit and a.id_modulo = db_permissao.id_modulo ) = 0 ";

              $resultduplicaperfil = db_query($sqlduplicaperfil);
              $linhasduplicaperfil = pg_num_rows($resultduplicaperfil);

              if ($linhasduplicaperfil > 0) {

                /**
                 * possui perfil cadastrado para este usuario
                 */
                for ($d = 0 ; $d < $linhasduplicaperfil; $d++) {

                  db_fieldsmemory($resultduplicaperfil,$d);

                  /**
                   * grava os itens do perfil na permissão.
                   */
                  $cldb_permissao->id_usuario     = $iItem[$i];
                  $cldb_permissao->id_item        = $id_item;
                  $cldb_permissao->permissaoativa = $permissaoativa;
                  $cldb_permissao->anousu         = $oParametros->anodestino;
                  $cldb_permissao->id_instit      = $id_instit;
                  $cldb_permissao->id_modulo      = $id_modulo;
                  $cldb_permissao->incluir($iItem[$i], $id_item, $oParametros->anodestino, $id_instit, $id_modulo);

                  if ($cldb_permissao->erro_status == 0) {

                    $erro_msg = $cldb_permissao->erro_msg;
                    db_msgbox($erro_msg);
                  }
                }

                /**
                 * Limpa o cache dos usuários do perfil
                 */

                $sSqlCache = "select distinct id_usuario from db_permherda where id_perfil in({$perfil})";
                $rsCache   = db_query($sSqlCache);

                $iNumRowsCache = pg_num_rows($rsCache);

                if ($iNumRowsCache > 0) {

                  for ($iRowCache = 0; $iRowCache < $iNumRowsCache; $iRowCache++) {

                    $oUsuarioCache = db_utils::fieldsMemory($rsCache, $iRowCache);
                    DBMenu::limpaCache($oUsuarioCache->id_usuario);
                  }
                }
              }
            }
          }

          /**
           * Limpa o cache dos menus para o usuário
           */
          DBMenu::limpaCache($iItem[$i]);
        }
      }

      if ($lTemPermissao == true) {

      	$sMensagem  = " O(s) usuário(s) {$sUsuario} já tem permissões cadastradas no exercício de {$oParametros->anodestino}, portanto, ";
      	$sMensagem .= "não tiveram suas permissões duplicadas. É aconselhavel acessar o exercício de {$oParametros->anodestino} ";
      	$sMensagem .= "e revisar as permissões deste(s) usuário(s). Caso não estejam corretas, remova-as e repita este ";
      	$sMensagem .= "procedimento de duplicação. Caso contrário, ignore este alerta.";
        $oRetorno->sMensagem = $sMensagem;
      } else  {

        $oRetorno->sMensagem = "Processo concluido com sucesso.";
      }

      break;
  }
  db_fim_transacao(false);

}catch( Exception $oErro ) {

  db_fim_transacao(true);
  $oRetorno->erro      = true;
  $oRetorno->sMensagem = $oErro->getMessage();
}

echo $oJson->stringify($oRetorno);