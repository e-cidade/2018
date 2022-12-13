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
require_once(modification("libs/db_conn.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$conn = @pg_connect(  "host={$DB_SERVIDOR} "
                     ."dbname={$DB_BASE} "
                     ."port={$DB_PORTA} "
                     ."user={$DB_USUARIO} "
                     ."password={$DB_SENHA}" );

if ( !$conn ) {

  echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
  exit;
}

pg_query("select fc_startsession();");

$oJson              = new services_json();
$oParametros        = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

define('MENSAGEM','configuracao.configuracao.primeiroAcesso.');

try {

  switch ($oParametros->exec) {

    /**
     * "Métodos" do RPC:
     *
     *  - "validaParametros" -> Valida os campos para verificar se é o primeiro acesso do cidadão
     *  Chama método validaPrimeiroAcesso sem validação de data
     *
     *      Retorna o usuário e o login
     *
     *  - "alteraSenha" ->
     *     Campos Recebidos            - Senha (md5 JS)
     *                                 - Usuário
     *      Deve validar se pode alterar o usuário.
     */
    case "alteraSenha":

      if( empty($oParametros->senha) ){
        throw new BusinessException( _M( MENSAGEM . 'senha_obrigatorio' ) );
      }

      if( empty($oParametros->id_usuario) ){
        throw new BusinessException( _M( MENSAGEM . 'idusuario_obrigatorio' ) );
      }

      /**
       * Valida se usuário é apto para setar senha
       */
      $oUsuarioSistema  = new UsuarioSistema( $oParametros->id_usuario );

      if ( $oUsuarioSistema->validaPrimeiroAcesso() ) {
        /**
         * Seta senha nova e altera situação do usuario para ativo
         */
        db_putsession("DB_desativar_account", true);

        db_inicio_transacao();

        $oUsuarioSistema->setSenha( Encriptacao::hash($oParametros->senha) );
        $oUsuarioSistema->ativo( 1 );
        $oUsuarioSistema->salvar();

        db_fim_transacao(false);

        $oRetorno->sMessage = urlencode(  _M( MENSAGEM . 'usuario_ativado' ) );
      }

    break;

    case "validaParametros":

      /**
       * Quando token não informado deve ser efetuada a validação dos campos
       */
      if( empty($oParametros->cpf) ){
        throw new BusinessException( _M( MENSAGEM . 'cpf_obrigatorio' ) );
      }

      if( empty($oParametros->datanascimento) ){
        throw new BusinessException( _M( MENSAGEM . 'datanascimento_obrigatorio' ) );
      }

      $oData = new DBDate($oParametros->datanascimento);

      if( empty($oParametros->email) ){
        throw new BusinessException( _M( MENSAGEM . 'email_obrigatorio' ) );
      }

      $sWhere  = "     cgm.z01_email  = '{$oParametros->email}'          ";
      $sWhere .= " and cgm.z01_cgccpf = '{$oParametros->cpf}'            ";
      $sWhere .= " and cgm.z01_nasc   = '{$oParametros->datanascimento}' ";

      $oDaoDBUsuaCgm = db_utils::getDao('db_usuacgm');
      $sSqlDBUsuaCgm = $oDaoDBUsuaCgm->sql_query( null, '*', null, $sWhere );
      $rsDBUsuaCgm   = db_query($sSqlDBUsuaCgm);

      if( !$rsDBUsuaCgm ){
        throw new Exception( _M( MENSAGEM . 'erro_busca_dbusuacgm' ) );
      }

      if( pg_num_rows($rsDBUsuaCgm) == 0 ) {
        throw new Exception( _M( MENSAGEM . 'cgm_nao_encontrado' ) );
      }

      $aUsuariosCgm = db_utils::getCollectionByRecord($rsDBUsuaCgm);

      foreach ( $aUsuariosCgm as $oUsuariosCgm ) {

        /**
         * Buscamos por usuario para encontrar um token valido
         */
        $oUsuarioSistema  = new UsuarioSistema( $oUsuariosCgm->id_usuario );

        try {

          $oUsuarioSistema->validaPrimeiroAcesso();
          $dDataToken    = $oUsuarioSistema->getToken();
        } catch(Exception $eErro) {
          continue;
        }
      }

      if( empty($dDataToken) ){
        throw new Exception( _M( MENSAGEM . 'usuario_nao_encontrado' ) );
      }

      $oRetorno->dDataToken = $dDataToken;

    break;
  }

} catch (Exception $eErro){

  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode( $eErro->getMessage() );
}

$oRetorno->sMessage = utf8_encode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);