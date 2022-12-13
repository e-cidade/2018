<?php

class DBTooltipAvisoEsocial extends DBTooltipAviso {

  /**
   * Representa a instância da classe.
   *
   * @var DBTooltipAviso
   */
  private static $oInstance;

  /**
   * Renderiza um aviso
   *
   * @return String
   */
  public function render() {

    $lAtualizado = $this->isUsuarioAtualizado();

    if ($lAtualizado) {
      return;
    }

    $lEsocialVazio = false;

    $sHtml  = '<div class="db-tooltip-aviso-button  tooltip-esocial">                                                                      '.PHP_EOL;
    $sHtml .= '  <div class="tooltip-close" onclick="fecharDBTooltip(this);">&#215;</div> '.PHP_EOL;
    $sHtml .= '  <script type="text/javascript">                                                                                          '.PHP_EOL;
    $sHtml .= '    require("scripts/widgets/windowAux.widget.js");                                                                        '.PHP_EOL;
    $sHtml .= "    function modalPreenchimentoEsocial() {                                                                                 ".PHP_EOL;
    $sHtml .= "      var iTop, iLeft, conteudo;                                                                                           ".PHP_EOL;
    $sHtml .= "      iTop      = 50;                                                                                                      ".PHP_EOL;
    $sHtml .= "      iLeft     = window.innerWidth / 2 - 512;                                                                             ".PHP_EOL;
    $sHtml .= "      conteudo = '<header class=\"DBMessageBoard\">                                                               ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '  <div class=\"help\">                                                                          ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '    <br/>O <strong>eSocial</strong> é um projeto do governo federal que visa unificar o envio de';       ".PHP_EOL;
    $sHtml .= "      conteudo+= ' informações pelo                                                                               ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= ' empregador em relação aos seus empregados. A partir de 2018 seu envio será obrigatório.        ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= 'Os dados solicitados abaixo serão enviados ao seu setor de RH para posterior envio,             ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= ' é de extrema importância que o seu cadastro esteja atualizado.                                 ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '  </div>                                                                                        ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '</header>                                                                                       ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '                                                                                                ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '<div id=\"formulario_esocial\">                                                                 ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '  <iframe id=\"frame_formulario_esocial\" src=\"eso4_preenchimento001.php?iframe=true\" width=\"100%\" height=\"90%\">'; ".PHP_EOL;
    $sHtml .= "      conteudo+= '  </iframe>                                                                                     ';       ".PHP_EOL;
    $sHtml .= "      conteudo+= '</div>                                                                                          ';       ".PHP_EOL;
    $sHtml .= "      window.windowLiberacao = new windowAux('esocial', 'Preenchimento do eSocial', 1024, 600);                            ".PHP_EOL;
    $sHtml .= "      window.windowLiberacao.setShutDownFunction(window.windowLiberacao.destroy.bind(window.windowLiberacao));             ".PHP_EOL;
    $sHtml .= "      window.windowLiberacao.setContent(conteudo);                                                                         ".PHP_EOL;
    $sHtml .= "      window.windowLiberacao.show(iTop, iLeft, true);                                                                      ".PHP_EOL;
    $sHtml .= "    }                                                                                                                      ".PHP_EOL;

    if($lEsocialVazio){
      $sHtml .= ' modalPreenchimentoEsocial();                                                                                            '.PHP_EOL;
    }

    $sHtml .= '  </script>                                                                                                                '.PHP_EOL;
    $sHtml .= '  <a href="javascript:modalPreenchimentoEsocial()" id="botao_esocial">eSocial</a>                                          '.PHP_EOL;
    $sHtml .= '</div>                                                                                                                     '.PHP_EOL;

    return $sHtml;
  }

  /**
   * Verifica se deve mostrar ou não o aviso
   */
  public function isUsuarioAtualizado() {

    /**
     * Caso usuário não tenha preenchido o e-Social
     * deve mostrar o botão de aviso
     */
    if(!isset($_SESSION['DB_atualiza_esocial'])) {

      $_SESSION['DB_atualiza_esocial'] = false;
      $oUsuarioLogado                  = UsuarioSistemaRepository::getPorCodigo($_SESSION["DB_id_usuario"]);

      if (!$oUsuarioLogado->isAtualizadoEsocial()) {
        $_SESSION['DB_atualiza_esocial'] = true;
      }
    }

    if($_SESSION['DB_atualiza_esocial'] === true) {
      return false;
    }

    return true;
  }

  /**
   * Retorna uma instancia da classe para renderizar um aviso
   */
  public static function getInstance($sLabel, $sAction) {

    if (self::$oInstance == null) {
      self::$oInstance = new DBTooltipAvisoEsocial($sLabel, $sAction);
    }

    return self::$oInstance;
  }


  public function usuarioEsocialVazio() {

    if(!isset($_SESSION['DB_esocial_vazio'])) {

      $oDaoAvaliacaogruporespostamatricula = new cl_avaliacaogruporespostarhpessoal();
      $sSql                                = $oDaoAvaliacaogruporespostamatricula->verificaPreenchimento(db_getsession("DB_id_usuario"));
      $rsAvaliacaogruporespostamatricula   = db_query($sSql);

      if(!$rsAvaliacaogruporespostamatricula){
        return false;
      }

      $_SESSION['DB_esocial_vazio']   = false;

      if(count(pg_num_rows($rsAvaliacaogruporespostamatricula)) == 0) {
        $_SESSION['DB_esocial_vazio'] = true;
      }
    }

    if($_SESSION['DB_esocial_vazio'] === true) {
      return true;
    }

    return false;
  }

}