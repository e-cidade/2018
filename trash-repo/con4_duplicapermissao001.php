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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$iInstituicao = db_getsession("DB_instit");
$anoorigem    = db_getsession("DB_anousu");
$anodestino   = $anoorigem+1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="">
  <center>
	  <div style='display: table;'>
		  <fieldset>
		    <legend><b>Duplicar Permiss�o de Menus</b></legend>
			  <table  border="0">
			      <tr>
			        <td width='100px'>
			          <b> Ano origem : </b>
			        </td>
			        <td>
			          <? db_input("anoorigem", 10, "", true, "text", 3)?>
			        </td>
					    <td>
					      <b> Ano destino : </b>
					    </td>
					    <td>
					      <? db_input("anodestino", 10, "", true, "text",1, "onkeypress=\"return js_mask(event, '0-9');\"")?>
					    </td>
			      </tr>
			      <tr>
					    <td nowrap>
					      <b> Duplica permissoes do perfil para usu�rio :</b>
					    </td>
					    <td colspan="3">
					      <? 
						      $aDuplicaPermissao = array("N" => "N�o", "S" => "Sim"); 
				          db_select("duplicaperfil", $aDuplicaPermissao, true, 1);
					      ?>
					    </td>
			      </tr>
			      <tr>
			        <td><b>A��o: </b></td>
			        <td colspan="3">
			          <?
			            $aAcoes = array("0" => "Padr�o", "1" => "Substituir", "2" => "Acrescentar");
			            db_select('acao', $aAcoes, true, 1);
			          ?>
			        </td>
			      </tr>
			     </table>
			     <fieldset style='border: 0px; border-top:2px groove white'>
			     	 <legend>
			     		<b>Escolha os Usu�rios para duplicar suas permiss�es</b>
			     	 </legend>
				     <table>
				     	<tr>
					      <td colspan="4">
								<?
									$sSqlUsuario  = "   select distinct db_usuarios.id_usuario,                                             ";  
									$sSqlUsuario .= "          db_usuarios.nome,                                                            ";     
									$sSqlUsuario .= "          db_usuarios.id_usuario || ' - ' ||                                           ";
									$sSqlUsuario .= "          db_usuarios.login || ' - ' ||                                                ";
									$sSqlUsuario .= "          db_usuarios.nome || ' - ' ||                                                 ";
                  $sSqlUsuario .= "            case                                                                       ";
                  $sSqlUsuario .= "              when db_usuarios.usuext = '0' then 'Interno'                             ";
                  $sSqlUsuario .= "              when db_usuarios.usuext = '1' then 'Externo'                             ";
                  $sSqlUsuario .= "              when db_usuarios.usuext = '2' then 'Perfil'                              ";
                  $sSqlUsuario .= "            end as descricao                                                           ";
									$sSqlUsuario .= "     from db_usuarios                                                                  ";
							    $sSqlUsuario .= "          left join db_permissao  on db_usuarios.id_usuario  = db_permissao.id_usuario ";
							    $sSqlUsuario .= "                                 and id_instit               = {$iInstituicao}         ";
							    $sSqlUsuario .= "                                 and anousu                  = {$anoorigem}            ";
							    $sSqlUsuario .= "          left join db_permherda  on db_permherda.id_usuario = db_usuarios.id_usuario  ";
							    $sSqlUsuario .= "    where db_usuarios.usuarioativo = 1                                                 ";
							    $sSqlUsuario .= " order by db_usuarios.nome asc                                                         ";
									$rsSqlUsuario = db_query($sSqlUsuario);
									db_multiploselect("id_usuario", "descricao", "nsel3", "ssel3", $rsSqlUsuario, array(), 8, 600, "", "", false);
								?>
							</td>
					  </tr>
				  </table>
			  </fieldset>
		  </fieldset>
		  <center>
			  <input name="consulta" type="button" value="Consulta Usu�rio" onclick="js_consulta();">
			  <input name="processa" type="button"value="Processa" onclick="js_mandadados();">
		  </center>
	  </div>
  </center>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_mandadados() {

  if (document.form1.anodestino.value != '') {
     
		vir   = "";
		lista = "";
		for (x = 0; x < document.form1.ssel3.length; x++) {
		
		  lista+=vir+document.form1.ssel3.options[x].value;
		  vir="_";
		}
		
		var anoorigem     = document.form1.anoorigem.value;
		var anodestino    = document.form1.anodestino.value; 
		var duplicaperfil = document.form1.duplicaperfil.value;
		var acao          = document.form1.acao.value;
		var sUrl          = 'con4_duplicapermissao002.php?&lista='+lista+
		                    '&anoorigem='+anoorigem+'&anodestino='+anodestino+
		                    '&duplicaperfil='+duplicaperfil+'&acao='+acao; 
		     
	  js_OpenJanelaIframe('', 'db_iframe_duplica', sUrl, 'Pesquisa', true);
	} else {
	
	  document.form1.anodestino.focus();
	  alert('Ano destino n�o informado.');
	}	 
}
 
function js_consulta() {

 	var contsel = 0;
  for (x = 0; x < document.form1.nsel3.length; x++) {
  
	  if (document.form1.nsel3.options[x].selected == true) {

		  contsel += 1;
		  if (contsel == 1) {
		    id_usuario=document.form1.nsel3.options[x].value;
		  } else if(contsel > 1) {
		    alert('Deve selecionar somente um usu�rio para a consulta.');
		    break;
		  }
		}
	}
	
	if (contsel == 0) {
	  alert('Selecione um usu�rio.');
	}
	
	if (contsel == 1) {
	
	  var sUrl = 'con4_consultausuario.php?&id_usuario='+id_usuario;
	  js_OpenJanelaIframe('', 'db_iframe_consulta', sUrl, 'Pesquisa', true);
	}
}
$('duplicaperfil').style.width ='100%';
$('acao').style.width          ='100%';
$('anoorigem').style.width     ='100%';
$('anodestino').style.width    ='100%';
$('anodestino').maxLength      = 4;
</script>