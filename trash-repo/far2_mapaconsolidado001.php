<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_modelolivro_classe.php");
include("classes/db_far_fechalivro_classe.php");
include("classes/db_far_farmacia_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_modelolivro = new cl_far_modelolivro;
$clfar_fechalivro  = new cl_far_fechalivro;
$clfar_farmacia    = new cl_far_farmacia;
$clrotulo          = new rotulocampo;
$fa26_i_login      = DB_getsession("DB_id_usuario");
$iAno              = date("Y");
$clrotulo->label('fa01_i_codmater');
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
    <center>
      <br>
      <br>  
      <fieldset style="width:30%; margin-top:40px; padding:30px;"  align="center">
        <legend><b>Relatórios de Mapa Consolidado - MCPM</b></legend>
        <table  align="center">
          <form name="form1" method="post" action="" >   
            <tr>
              <td nowrap align='right'>
                <b>Escolha um Período:</b>
              </td>
              <td nowrap>
	            <?
                  $aPeriodos = array("0"=>"Escolha um Trimestre",
                                     "1T"=>"Primeiro Trimestre",
                                     "2T"=>"Segundo Trimestre",
                                     "3T"=>"Terceiro Trimestre",
                                     "4T"=>"Quarto Trimestre"
                                    );
                  db_select("data", $aPeriodos, "", "", "", "", "");
	            ?>
	          </td>
            </tr>
            <tr>
              <td align="right">
                <b>Ano:</b>
              </td>
              <td>             
                 <? db_input('iAno', 5, $Ifa01_i_codmater, true, 'text', 1, '', '', '', '', 4); ?>
              </td>
            </tr>
          </table>
        </fieldset>
     	<br>
	    <center>
	      <input  name="gerar" id="gerar" type="button" value="Gerar" onclick="js_emite();">
	    </center>
      </form>
    </center>
    <?
      db_menu(db_getsession("DB_id_usuario"),
              db_getsession("DB_modulo"),
              db_getsession("DB_anousu"),
              db_getsession("DB_instit")
             );
    ?>
  </body>
</html>
<script>

function js_validadados() {

  var F = document.form1;
  if (F.data.value == '0') {

    alert("Selecione um Periodo!");
    return false;

  }
  if (F.iAno.value.trim() == "") {

	alert("Preencha o campo Ano!");
	F.iAno.value = "";
    F.iAno.focus();
    return false;

  }
  if (parseInt(F.iAno.value) < 1900 || parseInt(F.iAno.value) > parseInt(new Date().getFullYear())) {

    alert("Valor inválido no campo Ano!");
    F.iAno.value = "";
	F.iAno.focus();
	return false;

  }
  return true;
      
}

function js_emite() {

  if (!js_validadados()) {
    return false;
  } 
  jan = window.open('far2_mapaconsolidado002.php?semestre='+document.form1.data.value+
		            '&ano='+document.form1.iAno.value,'','width='+(screen.availWidth-5)+
		            ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
		           );
  jan.moveTo(0,0);
  
}
</script>