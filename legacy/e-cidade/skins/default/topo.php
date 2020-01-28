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
?>

<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="skins/estilos.php?file=dbtopo.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="scripts/scripts.js"></script>
<script>

  function js_abrir(obj) {
    var jan = window.open('con3_usuonline113.php?id_usuario='+document.getElementById('Hid_usuario').value+'&usuario='+document.getElementById('Husuario').value+'&hora='+document.getElementById('Hhora').value+'&verfusuario=1','','height=500,width=400,scrollbars=0');document.getElementById('sol').style.visibility = 'hidden';
  }

  function js_criaDIV() {

    var camada = top.topo.document.createElement("DIV");
    camada.setAttribute("id","info");
    camada.setAttribute("align","center");
    camada.style.position             = "absolute";
    camada.style.left                 = "450px";
    camada.style.top                  = "6px";
    camada.style.zIndex               = "1000";
    camada.style.visibility           = 'visible';
    camada.style.width                = "300px";
    camada.style.height               = "20px";

    var sTabelaInformacoes            = '<table cellspacing="0" cellpadding="0" id="tabelaInformacoesUsuario">                                                                                                                                                ';
        sTabelaInformacoes           += ' <tr>                                                                                                                                                                                  ';
        sTabelaInformacoes           += '      <td><strong>Nome:</strong></td>                                                                                                 ';
        sTabelaInformacoes           += '      <td><?=@pg_result($result,0,0)?>&nbsp;</td>                                                                                      ';
        sTabelaInformacoes           += '      <td><strong>Login:</strong></td>                                                                                                 ';
        sTabelaInformacoes           += '      <td><?=@pg_result($result,0,1)?>&nbsp;</td>                                                                                      ';
        sTabelaInformacoes           += ' </tr>                                                                                                                                                                                 ';
        sTabelaInformacoes           += ' <tr>                                                                                                                                                                                  ';
        sTabelaInformacoes           += '      <td><strong>Base de Dados:&nbsp;</strong></td>                                                                                   ';
        sTabelaInformacoes           += '      <td>' + document.getElementById('auxAcesso').value + '&nbsp;</td>                                                                ';
        sTabelaInformacoes           += '      <td><strong>Servidor:</strong></td>                                                                                              ';
        sTabelaInformacoes           += '      <td><?=$DB_SERVIDOR?>&nbsp;</td>                                                                                                ';
        sTabelaInformacoes           += ' </tr>                                                                                                                                                                                 ';
        sTabelaInformacoes           += ' <tr>                                                                                                                                                                                  ';
        sTabelaInformacoes           += '      <td><strong>IP:</strong></td>                                                                                                    ';
        sTabelaInformacoes           += '      <td><?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?>&nbsp;</td>  ';
        sTabelaInformacoes           += '      <td><strong>Local:</strong></td>                                                                                                 ';
        sTabelaInformacoes           += '      <td><?=$HTTP_SERVER_VARS['PHP_SELF'];?></td>                                                                                     ';
        sTabelaInformacoes           += ' </tr>                                                                                                                                                                                 ';
        sTabelaInformacoes           += '</table>                                                                                                                                                                               ';

    camada.innerHTML = sTabelaInformacoes;

    top.topo.document.body.appendChild(camada);
  }

  function js_remDIV() {

    if(top.topo.document.getElementById("info")){
      top.topo.document.body.removeChild(top.topo.document.getElementById("info"));
    }
  }

  /**
   * Reajusta iframes setando o topo para 75 ao invés de 60 como no default
   */
  var oFrameQuadroPrincipal = parent.document.getElementById("quadroprincipal");

  if(oFrameQuadroPrincipal){
    oFrameQuadroPrincipal.rows = '75,*,19';
  }

  /**
   * Função para controle de pull do topo
   */
  function js_minimizaTopo(){

    var frameQuadroPrincial = parent.document.getElementById("quadroprincipal");
    var bTopo               = top.topo.document.getElementById("bTopo");
    var controllerPull      = top.topo.document.getElementById("controllerPull");
    var logoPrefeitura      = top.topo.document.getElementById("logoPrefeitura");
    var mostraPreferencias  = top.topo.document.getElementById("mostraPreferencias");

    if( frameQuadroPrincial.rows == '15,*,19' ){

      mostraPreferencias.setAttribute('onmouseover', 'js_criaDIV()');
      mostraPreferencias.setAttribute('onmouseout', 'js_remDIV()');
      bTopo.style.visibility          = 'visible';
      logoPrefeitura.style.visibility = 'visible';
      frameQuadroPrincial.rows        = '75,*,19';
      controllerPull.className        = ''
    }else{

      mostraPreferencias.setAttribute('onmouseover', '');
      mostraPreferencias.setAttribute('onmouseout', '');
      bTopo.style.visibility          = 'hidden';
      logoPrefeitura.style.visibility = 'hidden';
      frameQuadroPrincial.rows        = '15,*,19';
      controllerPull.className        = 'maximizaTopo';
    }
  }
</script>

<body class="bodyWhiteDefault">
  <input type="hidden" id="auxAcesso" value="<?=$DB_BASE?>">

  <section>
      <header>

          <div id="pullOver">
              <a href="#" id="controllerPull" onClick="js_minimizaTopo();"><span>Controla</span></a>
          </div>

          <div id="bTopo">
              <p id="infoConfig">&nbsp;</p>
          </div>

          <div id="logoEcidade">
            <h1>e-Cidade</h1>
          </div>

          <div id="menuTopo">
            <div id="menuInfo">
                <nav>
                  <ul>
                    <li><a href="instit.php" target="corpo">Institui&ccedil;&otilde;es</a></li>
                    <li><a href="area.php" target="corpo">&Aacute;reas</a></li>
                    <li><a href="corpo.php?link='modulos'" target="corpo">M&oacute;dulos</a></li>
                    <li><a href="acesso.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" target="corpo" id="mostraPreferencias">Preferências</a></li>
                    <li><a href="#" onClick="if(!confirm('Deseja realmente sair do sistema?')){ return false ; }else{ parent.window.close(); }" style="text-decoration:none;color:white" target="_top">Fechar</a></li>
                  </ul>
                </nav>
            </div>
          </div>

          <div id="logoDBSeller" ondblclick="<?php if ($lPermiteRotinaEspecial === true) { echo "js_direcionarUsuarioRotinaEspecial();"; } else { echo ""; } ?>" >
            <h1>DBSeller</h1>
          </div>

          <div id="logoPrefeitura" class="hide">
            <a href="#" id="linkprefa">
              <h1>Prefeitura</h1>
            </a>
          </div>

      </header>
  </section>

  <input type="hidden" id="Hid_usuario">
  <input type="hidden" id="Husuario">
  <input type="hidden" id="Hhora">

  <div align="center" id="sol" style="position:absolute; left:450px; top:11px; width:180px; height:45px; z-index:1; background-color: #00FFFF; border: 1px none #000000; visibility: hidden;">
    <br><a href='' id="msg_sol" class="arial" onclick="js_abrir();return false">
    Solicita conversa
    </a>
  </div>
  <iframe frameborder="0" src="topo2.php" style="position:absolute; left:1px; top:1px; width:0px; height:0px; z-index:0; visibility: hidden;"></iframe>
</body>