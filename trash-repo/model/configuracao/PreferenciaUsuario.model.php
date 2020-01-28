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
   * Classe resposável pela manipulação das preferências do usuário.
   * @package configuracao
   * @author Renan Melo <renan@dbseller.com.br>
   */
  class PreferenciaUsuario{

    /**
     * Caminho onde o arquivo deve ser salvo.
     * @var String
     */
    const CAMINHO_ARQUIVO = 'cache/preferencias/';

    /**
     * Nome do arquivo a ser salvo
     * @var String
     */
    private $sNomeArquivo;

    /**
     * Define a ordenação que deve ser utilizado nos menus
     * -Sequencial
     * -Alfabética
     * @var String
     */
    private $sOrdenacao;

    /**
     * Instância da classe UsuarioSistema
     * @var UsuarioSistema
     */
    private $oUsuarioSistema;

    /**
     * Define se a busca por menus deve ser exibida ou não.
     * @var String
     */
    private $sExibeBusca;

    /**
     * Caminhos para o arquivo JSON contendo as mensagens utilizadas na função _M 
     */
    const MENSAGENS   = 'configuracao.configuracao.preferenciaUsuario.';

    /**
     * Função construtura, recebe como parametro uma instância de UsuarioSistema e 
     * realiza o LazyLoad carregando as preferências do usuário
     * @param UsuarioSistema $oUsuarioSistema [description]
     */
    function __construct(UsuarioSistema $oUsuarioSistema){
      
      $this->oUsuarioSistema = $oUsuarioSistema;
      $this->sNomeArquivo    = $this->oUsuarioSistema->getLogin() . '.json';
      $this->sOrdencao       = 'sequencial';
      $this->sExibeBusca     = '0';

      if (!file_exists(PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo)) {
       return false;
      }

      $sPreferencias     = file_get_contents(PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo);
      $oPreferencias     = json_decode($sPreferencias);
      $this->sOrdencao   = $oPreferencias->ordenacao;
      $this->sExibeBusca = $oPreferencias->busca;

      return true;
    }

    /**
     * Define a ordenação utilizada nos menus
     * @param String $sOrdencao
     */
    public function setOrdenacao($sOrdencao){
      $this->sOrdencao = $sOrdencao;
    }

    /**
     * Retorna a ordenação que deve ser utilizada nos menus
     * @return String
     */
    public function getOrdenacao(){
      return $this->sOrdencao;
    }

    public function setExibeBusca($sBusca){
      $this->sExibeBusca = $sBusca;
    }

    public function getExibeBusca(){
      return $this->sExibeBusca;
    }

    /**
     * Salva o arquivo [login_usuario].json contendo as preferências.
     * @return boolean
     */
    public function salvar(){

      $sPreferencias = $this->toJSON();

      if (!file_exists(PreferenciaUsuario::CAMINHO_ARQUIVO)) {
        mkdir(PreferenciaUsuario::CAMINHO_ARQUIVO, 0777, TRUE);
      }

      if (!is_writable(PreferenciaUsuario::CAMINHO_ARQUIVO)) {
        throw new Exception(_M(PreferenciaUsuario::MENSAGENS . 'erro_salvar'));
      }

      $oHandle = fopen(PreferenciaUsuario::CAMINHO_ARQUIVO . $this->sNomeArquivo, 'w');
      fwrite($oHandle, $sPreferencias);
      fclose($oHandle);

      if (!$oHandle) {
        throw new Exception(_M(PreferenciaUsuario::MENSAGENS . 'erro_salvar'));
      }

      db_putsession("DB_preferencias_usuario", base64_encode(serialize($this)));

      return true;
    }

    /**
     * Converte um objeto com as preferências do usuario 
     * para uma String JSON
     * @return String
     */
    private function toJSON(){

      $oPreferencias = new stdClass();
      $oPreferencias->ordenacao = $this->sOrdencao;
      $oPreferencias->busca     = $this->sExibeBusca;

      return  json_encode($oPreferencias);
    }
  }