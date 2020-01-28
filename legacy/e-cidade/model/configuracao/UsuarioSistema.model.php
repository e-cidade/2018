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

/**
 * Classe de modelo para usuários do sistema
 * @package configuracao
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @version $Revision: 1.28 $
 * @revision $Author: dbvitor $
 */
class UsuarioSistema {

  /**
   * Caminho para o arquivo JSON contendo as mensagens utilizadas na função _M
   */
  const MENSAGENS = 'configuracao.configuracao.UsuarioSistema.';

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
   * Situação do Usuario
   * @var integer
   * 0 - Inativo
   * 1 - Ativo
   * 2 - Bloqueado
   * 3 - Aguardando ativação
   */
  protected $iSituacaoUsuario;

  /**
   * Email do Usuario
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
   * @var bool
   */
  protected $lAdministrador;

  /**
   * Data do Token de ativação do usuário
   * @var string
   */
  protected $dDataToken;

  /**
   * Armazena todas as instituições que o usuário tem permissão
   * @var Instituicao[]
   */
  protected $aInstituicoes = array();

  /**
   * Departamentos que o usuario tem permissao
   * @var DBDepartamento[]
   */
  protected $aDepartamentos = array();


  /**
   * Cgm Vinculado ao usuário
   * @var CgmJuridico|CgmFisico
   */
  protected $oCgm = null;

  /**
   * Define se o CGM vinculado ao usuário
   * preencheu os dados para o e-Social
   */
  protected $lPreencheuEsocial = false;

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
      $this->setEmail      ($oUsuario->email        );
      $this->ativo         ($oUsuario->usuarioativo );
      $this->usuarioExterno($oUsuario->usuext       );
      $this->administrador ($oUsuario->administrador);
      $this->setDataToken  (isset($oUsuario->datatoken) ? $oUsuario->datatoken : '');
    }

  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iIdUsuario;
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

    if( $this->iSituacaoUsuario == 1 ){
      return true;
    }
    return false;
  }

  /**
   * Define o status "ativo" do usuario
   * @param integer $iSituacaoUsuario
   */
  public function ativo($iSituacaoUsuario){
    $this->iSituacaoUsuario = $iSituacaoUsuario;
  }

  /**
   * Retorna a situação do usuário
   * @return integer
   */
  public function getStatusUsuario(){
    return $this->iSituacaoUsuario;
  }

  /**
   * Define a data do token do Usuario
   * @param string $dDataToken
   */
  public function setDataToken($dDataToken){
    $this->dDataToken = $dDataToken;
  }

  /**
   * Retorna a data do token do Usuario
   * @return string
   */
  public function getDataToken(){
    return $this->dDataToken;
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
   * @return boolean
   */
  public function autenticar($sSenhaUsuario) {

    if (Encriptacao::hash($sSenhaUsuario) == ($this->sSenha)) {
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
    $oDaoUsuarioSistema->datatoken     = $this->getDataToken();

    if ($this->getIdUsuario() == "") {
      $oDaoUsuarioSistema->incluir(null);
    } else {
      $oDaoUsuarioSistema->alterar($this->getIdUsuario());
    }



    if ($oDaoUsuarioSistema->erro_status == 0) {
      throw new Exception( _M( UsuarioSistema::MENSAGENS . 'erro_salvar_usuario' ) );
    }
    return true;
  }

  /**
   * Método criado para carregar o objeto sem utilizar as classes.
   * Deve ser utilizado somente no Webservice de autenticacao
   *
   * @param  string $sLoginUsuario
   * @throws Exception
   * @return UsuarioSistema
   */
  public function getUsuarioByLogin($sLoginUsuario) {

    $sSqlBuscaDadosUsuario = "select * from configuracoes.db_usuarios where login = '{$sLoginUsuario}'";
    $rsExecutaBusca        = db_query($sSqlBuscaDadosUsuario);
    $iLinhasBusca          = pg_num_rows($rsExecutaBusca);

    if ($iLinhasBusca == 0) {
      throw new Exception( _M( UsuarioSistema::MENSAGENS . 'usuario_nao_localizado' ) );
    }
    $oDadoUsuario = pg_fetch_object($rsExecutaBusca, 0);
    $this->setNome       ($oDadoUsuario->nome         );
    $this->setIdUsuario  ($oDadoUsuario->id_usuario   );
    $this->setLogin      ($oDadoUsuario->login        );
    $this->setSenha      ($oDadoUsuario->senha        );
    $this->setEmail      ($oDadoUsuario->email        );
    $this->ativo         ($oDadoUsuario->usuarioativo );
    $this->usuarioExterno($oDadoUsuario->usuext       );
    $this->administrador ($oDadoUsuario->administrador);
    $this->setDataToken  ($oDadoUsuario->datatoken    );

    return true;
  }

  /**
   * Retorna uma instância de preferências do usuário
   * @return PreferenciaUsuario [description]
   */
  public function getPreferenciasUsuario() {

    require_once(modification('model/configuracao/PreferenciaUsuario.model.php'));
    $oPreferenciaUsuario = new PreferenciaUsuario($this);
    return $oPreferenciaUsuario;
  }

  /**
   * Retorna um array de instituições a qual o usuário tem acesso.
   * @return Instituicao[]
   */
  public function getInstituicoes () {

    $this->carregarInstituicoes();
    return $this->aInstituicoes;
  }

  /**
   * Método responsável por carregar as instituições do usuário
   * @return bool
   * @throws BusinessException
   */
  private function carregarInstituicoes() {

    if (count($this->aInstituicoes) == 0) {

      $oDaoUsuarioInstituicao = db_utils::getDao('db_userinst');
      $sSqlBuscaInstituicao   = $oDaoUsuarioInstituicao->sql_query_file(null,
                                                                        null,
                                                                        "id_instit",
                                                                        1,
                                                                        "id_usuario = {$this->iIdUsuario}");
      $rsBuscaInstituicao     = $oDaoUsuarioInstituicao->sql_record($sSqlBuscaInstituicao);

      if ($oDaoUsuarioInstituicao->erro_status == "0") {
        throw new BusinessException( _M( UsuarioSistema::MENSAGENS . 'nenhuma_instituicao_usuario' ) );
      }

      for ($iLinhaInstituicao = 0; $iLinhaInstituicao < $oDaoUsuarioInstituicao->numrows; $iLinhaInstituicao++) {

        $iCodigoInstituicao = db_utils::fieldsMemory($rsBuscaInstituicao, $iLinhaInstituicao)->id_instit;
        $this->aInstituicoes[$iCodigoInstituicao] = InstituicaoRepository::getInstituicaoByCodigo($iCodigoInstituicao);
      }
    }
    return true;
  }

  /**
   * Departamentos onde o usuario tem permisao
   *
   * @return DBDepartamento[]
   */
  public function getDepartamentos() {

    if (empty($this->aDepartamentos)) {

      $oDaodb_depusu = new cl_db_depusu();
      $sSqlDepartamentos = $oDaodb_depusu->sql_query_file($this->getCodigo(), 'coddepto');
      $rsDepartamentos = db_query($sSqlDepartamentos);

      if (!$rsDepartamentos) {
        throw new Exception( _M( UsuarioSistema::MENSAGENS . 'erro_departamentos_usuario' ) );
      }

      $iDepartamentos = pg_num_rows($rsDepartamentos);
      $aDepartamentos = array();

      for($iIndice = 0; $iIndice < $iDepartamentos; $iIndice++) {

        $iCodigoDepartamento = db_utils::fieldsMemory($rsDepartamentos, $iIndice)->coddepto;
        $aDepartamentos[]    = DBDepartamentoRepository::getDBDepartamentoByCodigo($iCodigoDepartamento);
      }

      $this->aDepartamentos = $aDepartamentos;
    }

    return $this->aDepartamentos;
  }
  /**
   * Retorna token de ativação do usuário
   * @return string
   */
  public function getToken(){
  	return Encriptacao::hash( $this->getDataToken() . $this->getIdUsuario() );
  }

  /**
   * Valida token de ativação do usuário
   * @throws Exception
   * @return boolean
   */
  public function validaToken( $sToken = null ){

    if( $sToken != $this->getToken() ){
      throw new Exception( _M( UsuarioSistema::MENSAGENS . 'token_invalido' ) );
    }
    return true;
  }

  /**
   * Valida primeiro acesso do usuário
   * @param  string $sToken token de ativação do usuário
   * @throws Exception
   * @return boolean
   */
  public function validaPrimeiroAcesso( $sToken = null ){

  	if( $this->getStatusUsuario() != 3  ){
  		throw new Exception( _M( UsuarioSistema::MENSAGENS . 'situacao_invalida_ativacao' ) );
  	}

  	if( $this->getSenha() != '67a74306b06d0c01624fe0d0249a570f4d093747' ){
  		throw new Exception( _M( UsuarioSistema::MENSAGENS . 'senha_padrao_invalida' ) );
  	}

  	if( $this->isAdministrador() == 1 ){
  		throw new Exception( _M( UsuarioSistema::MENSAGENS . 'usuario_administrador' ) );
  	}

  	if( $this->isUsuarioExterno() == 1 ){
  		throw new Exception( _M( UsuarioSistema::MENSAGENS . 'usuario_perfil_invalido' ) );
  	}

  	if( !empty($sToken) ){

  		if( $sToken != $this->getToken() ){
  			throw new Exception( _M( UsuarioSistema::MENSAGENS . 'token_invalido' ) );
  		}

      $oPreferenciaCliente = new PreferenciaCliente();

      if ( strtotime($this->getDataToken() . " + {$oPreferenciaCliente->getDiasExpiraToken()} days") < time())  {
        throw new Exception( _M( UsuarioSistema::MENSAGENS . 'token_expirado') );
      }
  	}

  	return true;
  }

  /**
   * Envio token de ativação de login
   * @throws Exception
   * @return boolean
   */
  public function enviarAtivacaoSenha() {

    require_once(modification("libs/smtp.class.php"));

    $oSmtp          = new Smtp();
    $oDaoUsuaCgm    = db_utils::getDao("db_usuacgm");
    $oDaoDbUserinst = db_utils::getDao("db_userinst");

    $rsCgm          = $oDaoUsuaCgm->sql_record( $oDaoUsuaCgm->sql_query($this->iIdUsuario, "z01_email, z01_nasc, z01_nome", null) );
    $rsDbInstit     = $oDaoDbUserinst->sql_record( $oDaoDbUserinst->sql_instit( "email, prefeitura",
                                                                                "prefeitura desc",
                                                                                "id_usuario = {$this->iIdUsuario}"
                                                                                . " and length(email) > 0",
                                                                                1) );

    if (!$rsDbInstit) {
      return false;
    }

    $oCgm        = db_utils::fieldsMemory($rsCgm, 0);
    $oDbUserinst = db_utils::fieldsMemory($rsDbInstit, 0);

    $sToken = $this->getToken();
    $sUrl   = 'http';

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
      $sUrl .= 's';
    }

    $sUrl .= "://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $sLink = dirname($sUrl) . "/primeiroAcesso.php" . "?_=" . $sToken;

    $sMensagem  = "Prezado(a), \n\n";
    $sMensagem .= "Se você esta recebendo este e-mail é porque foi criada uma conta vinculada a este junto ao E-Cidade,\n";
    $sMensagem .= "Para ativá-la clique no link abaixo:\n";
    $sMensagem .= $sLink . "\n\n";
    $sMensagem .= "Caso você não tenha solicitado, favor ignorar este e-mail.";

    $lEnviado = $oSmtp->Send($oCgm->z01_email, $oDbUserinst->email, "Ativação de Login E-Cidade", $sMensagem);

    return true;
  }

  /**
   * Retorna o Cgm do usuario
   * @return CgmFisico|CgmJuridico
   */
  public function getCGM() {

    if (!empty($this->oCgm)) {
      return $this->oCgm;
    }

    $oDaoUSuarioCGM = new cl_db_usuacgm();
    $sSqlDadosCgm   = $oDaoUSuarioCGM->sql_query_file($this->getCodigo());
    $rsDadosCgm     = $oDaoUSuarioCGM->sql_record($sSqlDadosCgm);
    if (!$rsDadosCgm) {
      return false;
    }
    $this->oCgm = CgmFactory::getInstanceByCgm(db_utils::fieldsMemory($rsDadosCgm, 0)->cgmlogin);
    return $this->oCgm;
  }

  /**
   * Retorna se o CGM vinculado ao usuário
   * preencheu o cadastro do e-Social
   */
  public function isAtualizadoEsocial() {

    $oCgm = $this->getCGM();

    if($oCgm instanceof CgmFisico) {

      if($oCgm->preencheuEsocial()) {
        $this->lPreencheuEsocial = true;
      }
    }
    
    if ( $this->lPreencheuEsocial ) {
      return true;
    }
    
    return false;
  }
  
  public function   isAtualizado() {
  
    if ( empty($this->sEmail) ) {
      return false;
    }

    /*
     * Valida email
     */ 
    $hostNamePattern = '(?:[_\p{L}0-9][-_\p{L}0-9]*\.)*(?:[\p{L}0-9][-\p{L}0-9]{0,62})\.(?:(?:[a-z]{2}\.)?[a-z]{2,})';
    $regex = '/^[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[\p{L}0-9!#$%&\'*+\/=?^_`{|}~-]+)*@' . $hostNamePattern . '$/ui';
    $return = (bool) preg_match($regex, $this->sEmail);

    return $return;
  }

}