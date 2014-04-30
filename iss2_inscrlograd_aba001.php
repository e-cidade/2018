<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_iptubase_classe.php");
include_once("dbforms/db_classesgenericas.php");
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<meta http-equiv="Expires" CONTENT="0">
			<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
			<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table>
	<tr>
		<td align="center">
			<form name="form1"   action="iss2_inscrlograd002.php"  method='post'> 
			<fieldset >
				<legend><strong>Filtros</strong></legend>
				<table>
					<tr>
						<td>
							<label id="label_iPessoa" for="iPessoa"><strong>Pessoa :</strong>	</label>
						</td>
						<td>
							<?
								$selPessoa = array("T"=>"Todos", "t"=>"F&iacute;sica","f"=>"Jur&iacute;dica");
								db_select('iPessoa', $selPessoa, true, 1, '');
							?>
						</td>	
					</tr>
					<tr>
						<td>
							<label id="label_iProcesso" for="iProcesso"><strong>Processo:</stong></label>
						</td>
						<td>
							<?
								$selProcesso = array("T"=>"Todos", "C"=>"Com Processo","S"=>"Sem Processo");
								db_select('iProcesso', $selProcesso, true, 1, '');
							?>	
						</td>
					</tr>
          <tr>
            <td>
              <label id="label_iSituacao" for="iSituacao"><strong>Inscrições:</stong></label>
            </td>
            <td>
              <?
                $selSituacao = array("T"=>"Todos", "A"=>"Ativas","B"=>"Baixadas");
                db_select('sSituacao', $selSituacao, true, 1, '');
              ?>  
            </td>
          </tr>
				</table>
			</fieldset>
			<br>
			<input name="processar" type="button"  id="processar" value="Processar" onclick="js_relatorio();" >
			<input type="hidden" name="logs" id="logs" /> 
			</form>
		</td>
	</tr>
</table>
</center>
	</body>
</html>
<script>
document.form1.iPessoa.style.width   = "100%";
document.form1.iProcesso.style.width = "100%";
document.form1.sSituacao.style.width = "100%";

function js_relatorio() {
	
    document.form1.logs.value = parent.iframe_aLogradouro.document.form1.logradouros.value;
var	pessoaTipo		            =	document.form1.iPessoa.value;
var	processoTipo	            =	document.form1.iProcesso.value;

//	report = window.open('iss2_inscrlograd002.php?logs='+ids+'&pessoaT='+pessoaTipo+'&processoT='+processoTipo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
//	report.moveTo(0,0);	
	
  report = window.open('','iframe_rel_logradouros','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	report.moveTo(0,0);
	
	document.form1.target = 'iframe_rel_logradouros';
	document.form1.submit();
	
}
</script>