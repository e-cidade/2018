<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_db_permissao_classe.php");
require_once ("libs/exceptions/DBException.php");

$cldb_permissao = new cl_db_permissao;
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$sUsuario      = "";
$vir           = "";
$lTemPermissao = false;
$lSqlErro      = false;
$oGet          = db_utils::postMemory($_GET);

if (isset($lista) && $lista != "") {

	db_inicio_transacao();
	
  $iItem = split("_",$lista);
  for ($i = 0; $i < sizeof($iItem); $i++) {

  	$sNomeUsuario            = '';
  	$aDadosPermissoesOrigem  = array();
  	$aDadosPermissoesDestino = array();
  	
    if (($iItem[$i] != "") && ($lSqlErro == false)) {
    	
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
			$sSqlPermissoesDestino .= "where id_usuario = {$iItem[$i]} and anousu = {$anodestino}";
			
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
			$sSqlPermissoesOrigem .= "where db_permissao.id_usuario = {$iItem[$i]} and anousu = {$anoorigem}";
			
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
	      	$oDaoDbPermissao->anousu         = $anodestino;
	      	$oDaoDbPermissao->id_instit      = $oDadosPermissao->id_instit;
	      	$oDaoDbPermissao->id_modulo      = $oDadosPermissao->id_modulo;
	      	$oDaoDbPermissao->incluir(
	      		                       	$oDadosPermissao->id_usuario, 
	      		                       	$oDadosPermissao->id_item, 
	      		                       	$anodestino, 
	      		                       	$oDadosPermissao->id_instit, 
	      		                       	$oDadosPermissao->id_modulo
	      		                       );
	      	
	      	if ( $oDaoDbPermissao->erro_status == "0" ) {
	      		
	      		$lSqlErro = true;
	      		throw new DBException($oDaoDbPermissao->erro_msg);
	      	}
      	}
      } else {
      	
      	/**
      	 * Caso existam permissoes cadastradas no ano de destino para o usuario, verificamos a ação a ser executada de 
      	 * acordo com a opção escolhida
      	 */
      	switch ( $oGet->acao ) {
      		
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
		      				                                  	$anodestino, 
		      				                                  	$oDadosPermissoesDestino->id_instit, 
		      				                                  	$oDadosPermissoesDestino->id_modulo
		      				                                  );
		      			
		      			if ( $oDaoExcluiPermissaoDestino->erro_status == "0" ) {
		      				
		      				$lSqlErro = true;
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
      					$oDaoInclusaoPermissoes->anousu         = $anodestino;
      					$oDaoInclusaoPermissoes->id_instit      = $oDadosPermissaoOrigem->id_instit;
      					$oDaoInclusaoPermissoes->id_modulo      = $oDadosPermissaoOrigem->id_modulo;
      					$oDaoInclusaoPermissoes->incluir(
      						                              	$oDadosPermissaoOrigem->id_usuario,
      						                              	$oDadosPermissaoOrigem->id_item,
      						                              	$anodestino,
      						                              	$oDadosPermissaoOrigem->id_instit,
      						                              	$oDadosPermissaoOrigem->id_modulo
      						                              );
      					
      					if ( $oDaoInclusaoPermissoes->erro_status == "0" ) {
      						
      						$lSqlErro = true;
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
		      			$oDaoInclusaoPermissoes->anousu         = $anodestino;
		      			$oDaoInclusaoPermissoes->id_instit      = $oPermissaoInclusao->id_instit;
		      			$oDaoInclusaoPermissoes->id_modulo      = $oPermissaoInclusao->id_modulo;
		      			$oDaoInclusaoPermissoes->incluir(
		      			                              		$oPermissaoInclusao->id_usuario,
		      			                              		$oPermissaoInclusao->id_item,
		      			                              		$anodestino,
		      			                              		$oPermissaoInclusao->id_instit,
		      			                              		$oPermissaoInclusao->id_modulo
		      			                                );
		      			
		      			if ( $oDaoInclusaoPermissoes->erro_status == "0" ) {
		      				
		      				$lSqlErro = true;
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
      if ($duplicaperfil == "S") {
      
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
          $sqlduplicaperfil .= "  where db_permissao.id_usuario in({$perfil}) and anousu = {$anoorigem} ";
          $sqlduplicaperfil .= "  and not exists (select * from db_permissao where id_usuario = {$iItem[$i]} and anousu = {$anodestino})";

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
              $cldb_permissao->anousu         = $anodestino;
              $cldb_permissao->id_instit      = $id_instit;
              $cldb_permissao->id_modulo      = $id_modulo;
              $cldb_permissao->incluir($iItem[$i], $id_item, $anodestino, $id_instit, $id_modulo);
      
              if ($cldb_permissao->erro_status == 0) {
      
                $lSqlErro = true;
                $erro_msg = $cldb_permissao->erro_msg;
                db_msgbox($erro_msg);
              }
            }
          }
        }
      }
    }
  }
  
  if ($lTemPermissao == true) {
  	
  	$sMensagem  = " O(s) usuário(s) {$sUsuario} já tem permissões cadastradas no exercício de {$anodestino}, portanto, ";
  	$sMensagem .= "não tiveram suas permissões duplicadas. É aconselhavel acessar o exercício de {$anodestino} ";
  	$sMensagem .= "e revisar as permissões deste(s) usuário(s). Caso não estejam corretas, remova-as e repita este ";
  	$sMensagem .= "procedimento de duplicação. Caso contrário, ignore este alerta.";
    db_msgbox($sMensagem);
  } else if ($lSqlErro == false) {
  	
    db_msgbox("Processo concluido com sucesso.");
    echo "<script>parent.document.form1.submit();</script>";
  }
  
  db_fim_transacao();
}
?>