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
?>
<link href="skins/estilos.php?file=style.css" rel="stylesheet" type="text/css">
<link href="skins/estilos.php?file=buttonColors.css" rel="stylesheet" type="text/css">
<script>

  function js_abrir(obj) {
    var jan = window.open('con3_usuonline113.php?id_usuario='+document.getElementById('Hid_usuario').value+'&usuario='+document.getElementById('Husuario').value+'&hora='+document.getElementById('Hhora').value+'&verfusuario=1','','height=500,width=400,scrollbars=0');document.getElementById('sol').style.visibility = 'hidden';
   //jan.focus();
  }

  function js_criaDIV() {
    var camada = top.topo.document.createElement("DIV");
    camada.setAttribute("id","info");
    camada.setAttribute("align","center");  
    camada.style.backgroundColor = "#F9F9F9";
    camada.style.layerBackgroundColor = "#F9F9F9";
    camada.style.position = "absolute";
    
    camada.style.display = 'table';
    camada.style.padding = '2px 10px';

    camada.style.right = "35px";
    camada.style.top = "7px";
    camada.style.zIndex = "1000";
    camada.style.visibility = 'visible';
    camada.style.width = "375px";
    camada.style.height = "20px";
    camada.innerHTML =   '<table cellspacing="0" cellpadding="0">'
                       + '  <tr>'
                       + '    <td nowrap><strong>Nome:</strong></td>'
                       + '    <td nowrap><?=@pg_result($result,0,0)?>&nbsp;</td>'
                       + '    <td nowrap><strong>Login:</strong></td>'
                       + '    <td nowrap><?=@pg_result($result,0,1)?>&nbsp;</td>'
                       + '  </tr>'
                       + '  <tr>'
                       + '    <td nowrap><strong>Base de Dados:&nbsp;</strong></td>'
                       + '    <td nowrap>' + document.getElementById('auxAcesso').value + '&nbsp;</td>'
                       + '    <td nowrap><strong>Servidor:</strong></td>'
                       + '    <td nowrap><?=$DB_SERVIDOR?>&nbsp;</td>'
                       + '  </tr>'
                       + '  <tr>'
                       + '    <td nowrap><strong>IP:</strong></td>'
                       + '    <td nowrap><?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?>&nbsp;</td>'
                       + '    <td nowrap><strong>Local:</strong></td>'
                       + '    <td nowrap><?=$HTTP_SERVER_VARS['PHP_SELF'];?></td>'
                       + '  </tr>'
                       + '</table>'
                       + '<span class="baixo"></span>';
    top.topo.document.body.appendChild(camada);

    var arrow = top.topo.document.createElement("DIV");
    arrow.setAttribute("id","arrow");
    arrow.setAttribute("class","arrow");  
    arrow.innerHTML = '&nbsp;';
    top.topo.document.body.appendChild(arrow);
  }
  function js_remDIV() {
    if(top.topo.document.getElementById("info")){
      top.topo.document.body.removeChild(top.topo.document.getElementById("info"));
      top.topo.document.body.removeChild(top.topo.document.getElementById("arrow"));
    }
  }
</script>

<style type="text/css">
  .arrow{
    width:0;
    height:0;
    border:7px solid transparent;
    display:block;

    position: absolute;
    top: 49px;
    right: 50px;
    z-index: 10000;
    border-top-color:#FFF;
  }
</style>

<body>
  <input type="hidden" id="auxAcesso" value="<?=$DB_BASE?>">

  <section>
      <header>
          <div id="bTopo">
              <span id="infoConfig">&nbsp;</span>
              | 
              <a href="#" onClick="if(!confirm('Quer realmente sair do sistema?')){ return false ; }else{ parent.window.close(); }" target="_top" class="button small blueStrong">Sair</a>
          </div>
          <div id="logo">
            <img src="skins/img.php?file=e-cidade.png"/>
          </div>
          <div id="right">
            <div id="menuTopo">
              <div id="menuInfo">
                <nav>
                    <ul>
                      <li><a href="instit.php" target="corpo"><i class="icon-inst"></i><br>Institui&ccedil;&otilde;es</a></li>
                      <li><a href="area.php" target="corpo"><i class="icon-areas"></i><br>&Aacute;reas</a></li>
                      <li><a href="corpo.php?link='modulos'" target="corpo"><i class="icon-modulos"></i><br>M&oacute;dulos</a></li>
                      <li><a href="acesso.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" target="corpo"><i class="icon-infos"></i><br>Preferências</a></li>
                    </ul>
                  </nav>
              </div>
            </div>
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