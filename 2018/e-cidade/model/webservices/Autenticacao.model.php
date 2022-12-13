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

require_once(modification("libs/db_stdlib.php" ));
db_app::import('exceptions.*');

/**
 * Autenticação
 * Responsável por validar a conexão com o webservice,
 * considerando metodos protegidos ou não.
 *
 * @package WebServices
 * @author Everton Heckler <everton.heckler@dbseller.com.br>
 */

class Autenticacao {

  /**
   * Metodos que serão exigidos autenticação de usuário
   * @var array
   */
  private static $aMetodosProtegidos =
    array(
      'geraDebitoIssContribribuinte', 'reemitirGuia', 'gerarGuiaPrestador', 'gerarGuiaTomador',
      'gerarPlanilhaRetencao', 'anularNotaPlanilhaRetencao', 'anularPlanilhaRetencao',
      'CancelamentoISSQNVariavel', 'lancarPlanilhaRetencao', 'processamentoArquivoDMS',
      'excluirCgm', 'EmpresaFotoPrincipal', 'gerarCgmExterno', 'tornarEscritorioContabil',
      'tornarGrafica', 'TransparenciaGerarDados', 'TransparenciaRemoverDados', 'TransparenciaSituacaoDados'
    );

  /**
   * Responsável por validar a conexão do webservice
   *
   * @param string $sMetodo
   * @return boolean
   */
  public static function validaConexao($sMetodo) {

    $sMensagem  = 'Sistema não possui permissão para acessar o E-Cidade. ';
    $sMensagem .= 'Favor Entrar em contato com o suporte!';

    try {

      /* valida o ip */
      if (empty($_SERVER['REMOTE_ADDR'])) {
        throw new Exception($sMensagem);
      }

      /* verifica se o metodo é protegido caso contrario retorna */
      if (!in_array($sMetodo, self::$aMetodosProtegidos)) {
        return true;
      }

      /* se o metodo é protegido valida se veio o user */
      if (empty($_SERVER['PHP_AUTH_USER'])) {
        throw new Exception($sMensagem);
      }

      $sSqlValidaUsuario  = " select db46_id_usuario,                                               ";
      $sSqlValidaUsuario .= "        db46_dtinicio,                                                 ";
      $sSqlValidaUsuario .= "        db46_horaini,                                                  ";
      $sSqlValidaUsuario .= "        db46_datafinal,                                                ";
      $sSqlValidaUsuario .= "        db46_horafinal                                                 ";
      $sSqlValidaUsuario .= "   from db_sysregrasacessoip                                           ";
      $sSqlValidaUsuario .= "        inner join db_sysregrasacesso on db46_idacesso = db48_idacesso ";
      $sSqlValidaUsuario .= "  where db48_ip = '{$_SERVER['REMOTE_ADDR']}'                          ";

      $rsResult   = db_query($sSqlValidaUsuario);
      if( !$rsResult ){
        throw new Exception($sMensagem);
      }

      if( pg_num_rows($rsResult) == 0 ){
        throw new Exception($sMensagem);
      }

      $oResultado = db_utils::fieldsMemory($rsResult, 0);
      if (md5($oResultado->db46_id_usuario) != $_SERVER['PHP_AUTH_USER']) {
        throw new Exception($sMensagem);
      }

      return true;
    } catch (Exception $oErro) {
      throw new Exception($oErro->getMessage());
    }
  }
}