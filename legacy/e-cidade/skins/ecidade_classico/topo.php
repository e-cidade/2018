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
<style type="text/css">
  <!--
  .arial {
    font-family: Arial, Helvetica, sans-serif;
      text-decoration: none;
  }
  a:hover {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    font-weight: bold;
    color: #FF0000;
      text-decoration: underline;
  }
  -->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_abrir(obj) {
    var jan = window.open('con3_usuonline113.php?id_usuario='+document.getElementById('Hid_usuario').value+'&usuario='+document.getElementById('Husuario').value+'&hora='+document.getElementById('Hhora').value+'&verfusuario=1','','height=500,width=400,scrollbars=0');document.getElementById('sol').style.visibility = 'hidden';
   //jan.focus();
  }

  function js_criaDIV() {
    var camada = top.topo.document.createElement("DIV");
    camada.setAttribute("id","info");
    camada.setAttribute("align","center");  
    camada.style.backgroundColor = "#FFFF99";
    camada.style.layerBackgroundColor = "#FFFF99";
    camada.style.position = "absolute";
    camada.style.left = "450px";
    camada.style.top = "6px";
    camada.style.zIndex = "1000";
    camada.style.visibility = 'visible';
    camada.style.width = "300px";
    camada.style.height = "20px";
    camada.innerHTML = '<table border="1" cellspacing="0" cellpadding="0"><tr><td style="font-size:10px;" bgcolor="#C4C400" nowrap><strong>Nome:</strong></td><td  style="font-size:10px" bgcolor="#C4C400" nowrap><?=@pg_result($result,0,0)?>&nbsp;</td><td  style="font-size:10px" bgcolor="#C4C400" nowrap><strong>Login:</strong></td><td  bgcolor="#C4C400" style="font-size:10px" nowrap><?=@pg_result($result,0,1)?>&nbsp;</td></tr><tr><td  style="font-size:10px" bgcolor="#C4C400" nowrap><strong>Base de Dados:&nbsp;</strong></td><td  style="font-size:10px"  bgcolor="#C4C400" nowrap>' + document.getElementById('auxAcesso').value + '&nbsp;</td><td  style="font-size:10px" bgcolor="#C4C400" nowrap><strong>Servidor:</strong></td><td  style="font-size:10px"  bgcolor="#C4C400" nowrap><?=$DB_SERVIDOR?>&nbsp;</td></tr><tr><td  style="font-size:10px" bgcolor="#C4C400" nowrap><strong>IP:</strong></td><td  style="font-size:10px"  bgcolor="#C4C400" nowrap><?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?>&nbsp;</td><td  style="font-size:10px" bgcolor="#C4C400" nowrap><strong>Local:</strong></td><td  style="font-size:10px" bgcolor="#C4C400" nowrap><?=$HTTP_SERVER_VARS['PHP_SELF'];?></td></tr></table>';
    top.topo.document.body.appendChild(camada);
  }
  
  function js_remDIV() {
    if(top.topo.document.getElementById("info"))
      top.topo.document.body.removeChild(top.topo.document.getElementById("info"));
  }
</script>
<body bgcolor=#CCCCCC style='margin-top:0px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <input type="hidden" id="auxAcesso" value="<?php echo $DB_BASE; ?>">
  <table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">
    <tr align="left" valign="bottom" bgcolor="#5786B2" class="arial"> 
      <td align="center" valign="top"> 
        <table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td height="45" valign="top" id="infoConfig" style="color:white;font-size:10px">&nbsp;</td>
          </tr>
          <tr>
            <td valign="bottom" id='menuTopo' nowrap style="color:white;font-size:13px;font-weight: bold;"> 
              <a href="instit.php" style="text-decoration:none;color:white" target="corpo">Instituições</a> &nbsp;&nbsp;&nbsp;&nbsp;
              <a href="area.php" style="text-decoration:none;color:white" target="corpo">Áreas</a> &nbsp;&nbsp;&nbsp;&nbsp;
              <a href="corpo.php?link='modulos'" style="text-decoration:none;color:white" target="corpo">M&oacute;dulos</a> &nbsp;&nbsp;&nbsp;&nbsp;
              <a href="acesso.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" style="text-decoration:none;color:white" target="corpo">Preferências</a>&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="#" onClick="if(!confirm('Quer realmente sair do sistema?')){ return false ; }else{ parent.window.close(); }" style="text-decoration:none;color:white" target="_top">Fechar</a>
            </td>
          </tr>
        </table>
      </td>
      <td width="38"><img src="imagens/3_O.gif" width="38" height="60"> </td>
      <td width="241"><a href="" id="linkprefa"><img src="imagens/4_O.gif" width="241" height="60" border="0"></a> </td>
      <td width="51"><img src="imagens/5_O.gif" width="51" height="60"> </td>
      <td width="186" bgcolor="#272645">
        <img src="imagens/6_O.jpg" width="146" height="60" ondblclick="<?php if ($lPermiteRotinaEspecial === true) { echo "js_direcionarUsuarioRotinaEspecial();"; } else { echo ""; } ?>" />
      </td>
    </tr>
  </table>
  <input type="hidden" id="Hid_usuario">
  <input type="hidden" id="Husuario">
  <input type="hidden" id="Hhora">
  <div align="center" id="sol" style="position:absolute; left:450px; top:11px; width:180px; height:45px; z-index:1; background-color: #00FFFF; border: 1px none #000000; visibility: hidden;">
    <br>
    <a href='' id="msg_sol" class="arial" onclick="js_abrir();return false">  
      Solicita conversa
    </a>
  </div>
  <iframe frameborder="0" src="topo2.php" style="position:absolute; left:1px; top:1px; width:0px; height:0px; z-index:0; visibility: hidden;"></iframe>
</body>