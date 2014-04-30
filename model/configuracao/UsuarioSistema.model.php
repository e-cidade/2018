<?php
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

/**
 * Classe de modelo para usuários do sistema
 * @package configuracao
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version $Revision: 1.5 $
 * @revision $Author: dbrenan $
 */
class UsuarioSistema {

  /**
   * Id do usuario
   * @var integer
   */
  protected $iIdUsuario;

  /**
   * Nome do Usuario
   * @var string
   */
  protected $sNome;

  /**
   * Login do Usuario
   * @var string
   */
  protected $sLogin;

  /**
   * Senha do Usuario
   * @var string
   */
  protected $sSenha;

  /**
   * Atividade do Usuario
   * @var bool
   */
  protected $lAtivo;

  /**
   * Email do Ususario
   * @var string
   */
  protected $sEmail;

  /**
   * Valida se é um usario externo
   * @var bool
   */
  protected $lUsuarioExterno;

  /**
   * Valida se o Usuario é administrador do Sistema
   * @var unknown_type
   */
  protected $lAdministrador;

  /**
   * Construtor da Classe
   */
  public function __construct( $iIdUsuario = null, $sLoginUsuario = null) {

    if ( !empty($iIdUsuario) || !empty($sLoginUsuario)) {

      $oDaoDBUsuarios = db_utils::getDao('db_usuarios');
      $sWhereUsuario  = "id_usuario = {$iIdUsuario}";
      if (!empty($sLoginUsuario)) {
        $sWhereUsuario = "login = '{$sLoginUsuario}'";
      }
      
      $sSqlUsuario = $oDaoDBUsuarios->sql_query_file(null, "*", null, $sWhereUsuario);
      $rsUsuario   = $oDaoDBUsuarios->sql_record($sSqlUsuario);

      if ( $oDaoDBUsuarios->numrows == 0 ) {
        throw new DBException("Usuário não localizado.");
      }

      $oUsuario = db_utils::fieldsMemory($rsUsuario, 0);

      $this->setNome       ($oUsuario->nome         );
      $this->setIdUsuario  ($oUsuario->id_usuario   );
      $this->setLogin      ($oUsuario->login        );
      $this->setSenha      ($oUsuario->senha        );
      $this->setEmail      ($oUsuario->usuarioativo );
      $this->ativo         ($oUsuario->email        );
      $this->usuarioExterno($oUsuario->usuext       );
      $this->administrador ($oUsuario->administrador);
    }

  }

  /**
   * Retorna o Codigo(id) do Usuario
   * @return integer
   */
  public function getIdUsuario() {
    return $this->iIdUsuario;
  }
  /**
   * Define o código do usuario
   * @param integer $iIdUsuario
   */
  public function setIdUsuario($iIdUsuario){
    $this->iIdUsuario = $iIdUsuario;
  }

  /**
   * Define o Login do Usuario
   * @return string
   */
  public function getLogin(){
    return $this->sLogin;
  }

  /**
   * Define o Login do Usuario
   * @param string $sLogin
   */
  public function setLogin($sLogin){
    $this->sLogin = $sLogin;
  }

  /**
   * Define o Nome do Usuario
   * @return string
   */
  public function getNome(){
    return $this->sNome;
  }

  /**
   * Define o Nome do Usuario
   * @param string $sNome
   */
  public function setNome($sNome){
    $this->sNome = $sNome;
  }

  /**
   * Retorna a Senha do Usuario
   * @return string
   */
  public function getSenha(){
    return $this->sSenha;
  }

  /**
   * Define a senha do Usuario
   * @param string $sSenha
   */
  public function setSenha($sSenha){
    $this->sSenha = $sSenha;
  }

  /**
   * Retorna o email do usuario
   * @return string
   */
  public function getEmail(){
    return $this->sEmail;
  }

  /**
   * Define o email do usuario
   * @param string $sEmail
   */
  public function setEmail($sEmail){
    $this->sEmail = $sEmail;
  }

  /**
   * Valida se um usuario esta ativo
   * @return boolean
   */
  public function isAtivo(){
    return $this->lAtivo;
  }

  /**
   * Define o status "ativo" do usuario
   * @param boolean $lAtivo
   */
  public function ativo($lAtivo){
    $this->lAtivo = $lAtivo;
  }

  /**
   * Executa a definição se o usuario é externo ou não
   * @param boolean $lUsuarioExterno
   */
  public function usuarioExterno($lUsuarioExterno){
    $this->lUsuarioExterno = $lUsuarioExterno;
  }

  /**
   * Valida se o Usuario é um usuario externo
   * @return boolean
   */
  public function isUsuarioExterno(){
    return $this->lUsuarioExterno;
  }

  /**
   * Define o tipo de usuario, se ele é administrador ou não
   * @param boolean $lAdministrador
   */
  public function administrador($lAdministrador){
    $this->lAdministrador = $lAdministrador;
  }

  /**
   * Validas se o usuário é um administrador
   * @return boolean
   */
  public function isAdministrador(){
    return $this->lAdministrador;
  }

  /**
   * Metodo que autentica a senha de um usuário no sistema.
   * @param string $sSenhaUsuario
   */
  public function autenticar($sSenhaUsuario) {
    
    if ($sSenhaUsuario == md5(~$this->sSenha)) {
      return true;
    }
    return false;
  }
  

  /**
   * Salva os dados do usuario
   */
  public function salvar() {

    $oDaoUsuarioSistema = db_utils::getDao('db_usuarios');
    $oDaoUsuarioSistema->id_usuario    = $this->getIdUsuario();
    $oDaoUsuarioSistema->nome          = $this->getNome();
    $oDaoUsuarioSistema->login         = $this->getLogin();
    $oDaoUsuarioSistema->senha         = $this->getSenha();
    $oDaoUsuarioSistema->usuarioativo  = $this->isAtivo();
    $oDaoUsuarioSistema->email         = $this->getEmail();
    $oDaoUsuarioSistema->usuext        = $this->isUsuarioExterno();
    $oDaoUsuarioSistema->administrador = $this->isAdministrador();
    
    if ($this->getIdUsuario() == "") {
      $oDaoUsuarioSistema->incluir(null);
    } else {
      $oDaoUsuarioSistema->alterar($this->getIdUsuario());
    }
    
    if ($oDaoUsuarioSistema->erro_status == 0) {
      throw new Exception("Não foi possível salvar os dados do usuário.\n\n{$oDaoUsuarioSistema->erro_msg}");
    }
    return true;
  }

  /**
   * Método criado para carregar o objeto sem utilizar as classes.
   * Deve ser utilizado somente no Webservice de autenticacao
   * @param  string $sLoginUsuario
   * @throws DBException
   * @return UsuarioSistema
   */
  public function getUsuarioByLogin($sLoginUsuario) {

    $sSqlBuscaDadosUsuario = "select * from configuracoes.db_usuarios where login = '{$sLoginUsuario}'";
    $rsExecutaBusca        = db_query($sSqlBuscaDadosUsuario);
    $iLinhasBusca          = pg_num_rows($rsExecutaBusca);

    if ($iLinhasBusca == 0) {
      throw new Exception("Usuário não localizado.");
    }
    $oDadoUsuario = pg_fetch_object($rsExecutaBusca, 0);
    $this->setNome       ($oDadoUsuario->nome         );
    $this->setIdUsuario  ($oDadoUsuario->id_usuario   );
    $this->setLogin      ($oDadoUsuario->login        );
    $this->setSenha      ($oDadoUsuario->senha        );
    $this->setEmail      ($oDadoUsuario->usuarioativo );
    $this->ativo         ($oDadoUsuario->email        );
    $this->usuarioExterno($oDadoUsuario->usuext       );
    $this->administrador ($oDadoUsuario->administrador);
    return true;
  }

  /**
   * Retorna uma instância de preferências do usuário
   * @return PreferenciaUsuario [description]
   */
  public function getPreferenciasUsuario() {
    
    require_once('model/configuracao/PreferenciaUsuario.model.php');
    $oPreferenciaUsuario = new PreferenciaUsuario($this);
    return $oPreferenciaUsuario;
  }
}