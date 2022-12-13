<?php

class DBTooltipAvisoQuestionario extends DBTooltipAviso {

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
  public function renderQuestionario($iIdItem=null, $iModulo, $iTop) {

    $sHtml         = "";

    $oQuestionario = new AvaliacaoQuestionarioInterno();
    $aAvaliacao    = $oQuestionario->getQuestionarios($iIdItem, $iModulo, true);


    if(!empty($aAvaliacao)){

      $sScript  = "";
      $sScript .= '  <script type="text/javascript">                                                                              '.PHP_EOL;
      $sScript .= '    require("scripts/widgets/windowAux.widget.js");                                                            '.PHP_EOL;
      $sScript .= '    var aAvaliacao = new Array();                                                                              '.PHP_EOL;
      $sScript .= '    require("scripts/widgets/windowAux.widget.js");                                                            '.PHP_EOL;
      $sScript .= "    function modalPreenchimentoQuestionario(iAvaliacao) {                                                      ".PHP_EOL;
      $sScript .= "      var iTop, iLeft, conteudo;                                                                               ".PHP_EOL;
      $sScript .= "      iTop     = 50;                                                                                           ".PHP_EOL;
      $sScript .= "      iLeft    = window.innerWidth / 2 - 512;                                                                  ".PHP_EOL;
      $sScript .= "      conteudo = '<header class=\"DBMessageBoard\">';                                                          ".PHP_EOL;
      $sScript .= "      conteudo+= '  <div class=\"help\">';                                                                     ".PHP_EOL;
      $sScript .= "      conteudo+= '    <br/><strong>'+aAvaliacao[iAvaliacao].descricao+'</strong>';                             ".PHP_EOL;
      $sScript .= "      conteudo+= '    <br/><p>'+aAvaliacao[iAvaliacao].observacao+'</p>';                                      ".PHP_EOL;
      $sScript .= "      conteudo+= '  </div>';                                                                                   ".PHP_EOL;
      $sScript .= "      conteudo+= '</header>';                                                                                  ".PHP_EOL;
      $sScript .= "      conteudo+= '<div id=\"formulario_questionario\">';                                                       ".PHP_EOL;
      $sScript .= "      conteudo+= '  <iframe id=\"frame_formulario_questionario\" src=\"con4_preenchimentoquestionario001.php?avaliacao='+iAvaliacao+'&iframe=true\" width=\"100%\" height=\"90%\">'; ".PHP_EOL;
      $sScript .= "      conteudo+= '  </iframe>';                                                                                ".PHP_EOL;
      $sScript .= "      conteudo+= '</div>';                                                                                     ".PHP_EOL;
      $sScript .= "      window.windowLiberacao = new windowAux('questionario', 'Colabore com o desenvolvimento do e-cidade', 1024, 600);      ".PHP_EOL;
      $sScript .= "      window.windowLiberacao.setShutDownFunction(window.windowLiberacao.destroy.bind(window.windowLiberacao)); ".PHP_EOL;
      $sScript .= "      window.windowLiberacao.setContent(conteudo);                                                             ".PHP_EOL;
      $sScript .= "      window.windowLiberacao.show(iTop, iLeft, true);                                                          ".PHP_EOL;
      $sScript .= "    }                                                                                                          ".PHP_EOL;
      $sScript .= "    function menuQuestionario(){                                                                               ".PHP_EOL;
      $sScript .= "       var x = document.getElementById('listaquestionario');                                                   ".PHP_EOL;
      $sScript .= "       if(x.className=='questionario'){                                                                        ".PHP_EOL;
      $sScript .= "         x.className = 'questionario animate-in';                                                              ".PHP_EOL;
      $sScript .= "         x.style.left = '0px';                                                                                 ".PHP_EOL;
      $sScript .= "         x.parentNode.style.display = 'block';                                                                 ".PHP_EOL;
      $sScript .= "       } else if (x.className=='questionario animate-in') {                                                    ".PHP_EOL;
      $sScript .= "         x.className = 'questionario animate-out';                                                             ".PHP_EOL;
      $sScript .= "         x.style.left = '150%';                                                                                ".PHP_EOL;
      $sScript .= "         x.parentNode.style.display = 'none';                                                                  ".PHP_EOL;
      $sScript .= "       } else {                                                                                                ".PHP_EOL;
      $sScript .= "         x.className = 'questionario animate-in';                                                              ".PHP_EOL;
      $sScript .= "         x.style.left = '0px';                                                                                 ".PHP_EOL;
      $sScript .= "         x.parentNode.style.display = 'block';                                                                 ".PHP_EOL;
      $sScript .= "       }                                                                                                       ".PHP_EOL;
      $sScript .= "    }                                                                                                          ".PHP_EOL;

      if($iTop != 0){
        $sHtml .= '<div id="questionarios" class="db-tooltip-aviso-button tooltip-esocial"  style="margin-right: 5px;">'.PHP_EOL;
      } else {
        $sHtml .= '<div id="questionarios" class="db-tooltip-aviso-button tooltip-esocial" >'.PHP_EOL;
      }
      $sHtml .= '  <div class="tooltip-close" onclick="fecharDBTooltip(this);">&#215;</div> '     .PHP_EOL;

      $sHtml .= '<a href="javascript:menuQuestionario()" id="exibe_questionario">Colabore</a>'.PHP_EOL;
      $sHtml .= '</div>                                                                          '.PHP_EOL;
      $sHtml .= '<div style="display:none">                                                      '.PHP_EOL;
      $sHtml .= '  <ul id="listaquestionario" class="questionario">                              '.PHP_EOL;

      foreach ($aAvaliacao as $oAvaliacaoQuestionario) {

        $oAvaliacao = new Avaliacao($oAvaliacaoQuestionario->avaliacao);
        $sDesricao = $oAvaliacao->getObservacao();
        $sDesricao = preg_replace('/\r?\n|\r/','<br/>', $sDesricao);

        $sHtml .= "<li>                                                                            ".PHP_EOL;
        $sHtml .= '  <div class="db-tooltip-aviso-button tooltip-esocial" style="margin-top:0px;"> '.PHP_EOL;
        $sHtml .= '    <a href="javascript:modalPreenchimentoQuestionario('.$oAvaliacaoQuestionario->avaliacao.')" id="botao_questionario-'.$oAvaliacaoQuestionario->avaliacao.'">'.$oAvaliacao->getDescricao().'</a>'.PHP_EOL;
        $sHtml .= '  </div>                                                                        '.PHP_EOL;
        $sHtml .= "</li>                                                                           ".PHP_EOL;
        $sScript .= '  var avaliacao = new Object();                                      '.PHP_EOL;
        $sScript .= '  avaliacao.descricao  = "' . $oAvaliacao->getDescricao() . '";      '.PHP_EOL;
        $sScript .= '  avaliacao.observacao = "' . $sDesricao . '";                       '.PHP_EOL;
        $sScript .= '  avaliacao.avaliacao = '   . $oAvaliacaoQuestionario->avaliacao . ';'.PHP_EOL;
        $sScript .= '  aAvaliacao[' . $oAvaliacaoQuestionario->avaliacao . '] = avaliacao;'.PHP_EOL;
      }
      $sHtml   .= "</ul>";
      $sHtml   .= "</div>";

      $sScript .= '  </script>'.PHP_EOL;
      $sHtml   .= $sScript . '</div>';
    }

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
  public static function getInstanceQuestionario($sLabel, $sAction, $iIdItem) {

    if (self::$oInstance == null) {
      self::$oInstance = new DBTooltipAvisoQuestionario($sLabel, $sAction, $iIdItem);
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