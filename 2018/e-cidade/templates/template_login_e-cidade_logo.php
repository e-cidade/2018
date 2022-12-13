<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
<html>
  <head>
    <title>Tela de acesso para e-cidade</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">

    .body{
      background-color:#EEEEEE;
      background-repeat:no-repeat;
      background-align:top;
      font-family:verdana  
    }

    #box {
      border: 1px outset rgb(153, 153, 153); 
      width: 480px;
      margin:5% auto 0 auto;
      background-color:#EEEEE2;
      padding:2px
    }

    #tbl_principal{
      text-align: left; 
      left: 346px; 
      top: 240px;
      height: 79px;
      font-size:11pt;
      font-family:verdana
    }

    #tderro{
      text-align:center;
      color:red;
      font-weight: bold;  
    }
    
    .logo {
      border: none;
    }
    .logo:hover {
      border: none;
    }
    .logo-img{
      border: none;
      
    }
    input {
       height: 19px;
    } 
    
    
    </style>
  </head>
<body background="#EEEEEE"  onload="js_addevent()">
<br>
<div id='box' style="" align="center" >
  <img class='logo-img' src="imagens/ecidade/logo.png"  style="width: 482px; height:100px; margin-bottom: 12px;">  
  <img class='logo-img' src="imagens/ecidade/e_cidade_chave_login.png">
  <img class="logo-img" src="imagens/ecidade/e_cidade_login.jpg" title="Entre na comunidade e-Cidade no Portal do Software Público." 
       onclick="window.open('http://www.softwarepublico.gov.br/ver-comunidade?community_id=15315976','_Blank','')"/>
  <img class="logo-img" src="imagens/ecidade/twitter-ico2.png" title="Siga-nos no Twitter" alt="Icone do Twitter"
       style="width: 20px; height:20px; margin-bottom: 12px;" onclick="window.open('http://twitter.com/#!/e_cidade','_Blank','')"/>
<br/>
<form method="post" name="form1">
<table id="tbl_principal" border="0" cellpadding="2" cellspacing="2">
  <tbody>    
    <tr>    
      <td colspan="4" style='text-align:center'><br>
      </td>
    </tr>
      <?
        if (isset($DB_CONEXAO)){
      ?>
      
         <input id="servidor" name="servidor"  type="hidden" value="<?=@$servidor?>" size="40">
         <input id="port"     name="port"      type="hidden" value="<?=@$port?>"     size="40">  
         <input id="user"     name="user"      type="hidden" value="<?=@$user?>"     size="40">  
         <input id="senh"     name="senh"      type="hidden" value="<?=@$senh?>"     size="40">  
         <input id="base"     name="base"      type="hidden" value="<?=@$base?>"     size="40">  

      <tr>  
        <td><b>Servidor:</b></td>
        <td>
          <select name='serv' id="serv" style="width:337">
            <option name='condicaoservidor' value=''>Selecione um servidor</option>");
            <?
              for( $iInd = 0; $iInd < count( $DB_CONEXAO ); $iInd++){
            ?>
                <option name='condicaoservidor' value='<?=$iInd?>'>
                  <?=$DB_CONEXAO[$iInd]["SERVIDOR"].":".$DB_CONEXAO[$iInd]["PORTA"] ?>
                </option>");
            <?
              }
            ?>
          </select>
        </td> 
      </tr> 
      <tr>
       <tr>  
        <td><b>Base de Dados:</b></td>
        <td>
          <input type="text"   name="basename" id="basename" size=45 onclick="this.value=''">
          <input type="hidden" name="idbasename" id="idbasename">
        </td>
      </tr> 
      <?
        }
      ?>
    
    <tr>
      <td style="vertical-align: top;">
        <b>Login:</b><br>
      </td>
      <td style="vertical-align: top;">
        <input size="25" name="login" id='usu_login'><br>
      </td>
    </tr>
    <tr>
      <td style="vertical-align: top;">
        <b>Senha:</b><br>
      </td>
      <td style="vertical-align: top;">
        <input  size="25" name="senha" id='usu_senha' type="password"><br>
      </td>
    </tr>
    <tr align="right">
      <td colspan="2" rowspan="1" style="vertical-align: top;">
        <button  name="btnlogar" id='btnlogar' type="button" style='width:80px'>
          <img src='imagens/gtk_ok.png'>
          Ok
        </button><br>
      </td>
    </tr>
    <tr id='erro' style='display:none'>
      <td id='tderro' colspan=2 >
      </td>
    </tr>  
    </form>
  </tbody>
</table>
<span id="testaLogin" style="font-weight: bold;">&nbsp;&nbsp;&nbsp; </span><br>
<hr>

<table >
<tr>
  <td rowspan=4>
      <img  src="imagens/logo_dbseller_o_cinza.gif" alt="Logo DB_Seller"  />
    </a>
  </td>
  
</tr>
<tr align="center">
  <td><strong>
  DBSeller Serviços de Informática Ltda
  </strong></td>
</tr>
<tr align="center">
  <td>
  <a href='http://www.dbseller.com.br'>www.dbseller.com.br</a>
  </td>
</tr>
<tr align="center">
  <td><strong>
  Porto Alegre - RS - Brasil
  </strong></td>
</tr>
<tr>
  <td align="right">
  <img class="logo-img" src="imagens/ecidade/facebook-ico1.png" title="Conheça nossa página no Facebook" 
       style="width: 20px; height:20px;" onclick="window.open('http://www.facebook.com/?ref=home#!/pages/DBSeller/168429383219644','_Blank','');" />
  <img class="logo-img" src="imagens/ecidade/twitter-ico2.png" title="Siga-nos no Twitter" alt="Icone do Twitter"
       style="width: 20px; height20px; margin-right:16px;" onclick="window.open('http://twitter.com/#!/DBSeller','_Blank','')" />
  </td>
</tr>

</table> 
</div>
<script type="text/javascript">

if ($('servidor')) {

  oAutoComplete = new dbAutoComplete($('basename'),'BuscaBase.RPC.php');
  oAutoComplete.setTxtFieldId(document.getElementById('idbasename'));
  oAutoComplete.show();
  
  oAutoComplete.setQueryStringFunction(
    function () {
      var sQuery  = 'string='+$F('basename');
      if ( document.form1.serv.value != '' ) {
        sQuery += '&servidor='+document.form1.serv.value; 
      }    
      return sQuery;
    }  
  );
  
  oAutoComplete.setCallBackFunction(
    function(id,label) {
        aId      = id.urlDecode().split(':');
        aDados   = label.urlDecode().split(':');
        
        $('basename').value = label;
        $('servidor').value = aId[0];  
        $('port').value     = aId[1];    
        $('user').value     = aId[2];
        $('senh').value     = tagString(aId[3]);
        $('base').value     = aId[4];
    }
  );
  
}

function js_acessar_dbportal() {
  
  $('testaLogin').innerHTML = '';

  var sLogin                = $F('usu_login');
  var sSenha                = calcMD5($F('usu_senha'));
  var wname                 = 'wname' + Math.floor(Math.random() * 10000);
  var sQuery                = "";
  
  $('usu_senha').value      = "";
  $('usu_login').value      = "";

  if ($('servidor') && $('servidor').value != ""){
    sQuery += "&servidor="+$F('servidor');
    sQuery += "&base="+$F('base');
    sQuery += "&user="+$F('user');
    sQuery += "&port="+$F('port');
    sQuery += "&senha="+$F('senh');
  }
  var sUrl  = 'abrir.php?estenaoserveparanada=1&DB_login='+sLogin+'&DB_senha='+sSenha+sQuery;
  var jan   = window.open(sUrl,wname,'width=1,height=1');
  
}

function js_addevent_prj() {

  if ($('servidor')) {
  
    $('servidor').observe('change', function(event){
      $('form1').submit();
    });  
  }
  
  if ($('gerar')) {

    $('gerar').observe('click', function(event){
      js_mostrarelatorio();
    });     
  }
  
  $('btnlogar').observe('click', function(event){
    js_acessar_dbportal();
  });  

  $('usu_senha').observe('keyup', function(event){
    js_logaComTeclaEnter(event);
  });  
  
  document.form1.usu_login.focus();
  js_verifica_cookie();
}
</script>

</body>
</html>