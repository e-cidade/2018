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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
  <form name="form1" method="post" action="">
    <table style="padding-top:23px;">
    <tr>
      <td>
				<fieldset>
				  <legend align="center">
				  	<b>Configurar SQL:</b>
				  </legend>
				  <table align="center">
				    <tr>
				  	  <td>
								<?
                  db_textarea('sql',20,100,"",true,"text",1,"");
								?>
					    </td>
				    </tr>
				  </table>
				</fieldset>
      </td>
    </tr>
    <tr align="center">
      <td>
      	<input name="voltar"   type="button" value="Voltar"       onClick="js_voltar();"/>
      	<input name="Apagar"   type="button" value="Apagar"       onClick="js_apagar();"/>
      	<input name="executar" type="button" value="Executar SQL" onClick="js_executar();"/>
      	<input name="enviar"   type="button" value="Enviar"       onClick="js_enviar();"/>
      </td>
    </tr>
  </table>
  </form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  function js_enviar(){

    var sSql = document.form1.sql.value.trim();

    if ( sSql == '' ) {
      alert('Nenhum SQL informado!');
      return false;
    } else {

	    var sUrl       = 'sys4_consultaviewRPC.php';
	    var sQuery     = 'sql='+encodeURIComponent(btoa(sSql));
	        sQuery    += '&tipo=incluirConsulta';
	    var oAjax      = new Ajax.Request( sUrl, {
	                                               method: 'post',
	                                               parameters: sQuery,
	                                               onComplete: js_retornoEnvio
	                                             }
	                                      );
    }

  }

  function js_retornoEnvio(oAjax){

    var aRetorno = eval("("+oAjax.responseText+")");

    if ( aRetorno.erro ) {
      alert(aRetorno.msg.urlDecode());
    } else {
      document.location.href = 'sys4_confrelatorio001.php?lSql=true';
    }
  }

  function js_voltar(){
    location.href = 'sys4_escolhetiporel001.php';
  }

  function js_apagar(){
    document.form1.sql.value = '';
  }

  function js_executar(){

    var sSql = document.form1.sql.value.trim();

    if ( sSql == '' ) {
       alert('Nenhum SQL informado!');
       return false;
    }


    js_divCarregando('Aguarde...','msgBox');

    var sUrl       = 'sys4_executasqlRPC.php';
    var sQuery     = 'sql='+encodeURIComponent(btoa(sSql));
    var oAjax      = new Ajax.Request( sUrl, {
                                               method: 'post',
                                               parameters: sQuery,
                                               onComplete: js_retornoExecucao
                                             }
                                      );
  }

  function js_retornoExecucao(oAjax){

    js_removeObj('msgBox');

    var aRetorno = eval("("+oAjax.responseText+")");

    if ( aRetorno.erro ) {
      alert(aRetorno.msg.urlDecode());
	  } else {
      js_OpenJanelaIframe('','db_iframe_sql','sys4_listaretornosql001.php','Retorno Consulta',true);
	  }

  }

</script>
