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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_db_documentotemplatepadrao_classe.php"));
$cldb_documentotemplatepadrao = new cl_db_documentotemplatepadrao();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	aLinha = new Array();
</script>
</head>
<?php 
//$db81_instit = db_getsession('DB_instit');
$resArquivos = $cldb_documentotemplatepadrao->sql_record($cldb_documentotemplatepadrao->sql_query(null,'db81_descricao,db81_nomearquivo',null));
$iNumRows = $cldb_documentotemplatepadrao->numrows;
if ($iNumRows > 0) {
  echo "<script type=\"text/javascript\">\n";
	for($i=0; $i < $iNumRows; $i++){
    
		$oArquivo = db_utils::fieldsMemory($resArquivos,$i);
		$sArquivo = basename($oArquivo->db81_nomearquivo);
		
		echo "  aLinha[{$i}] = new Array('{$sArquivo}',\n";
		echo "           				         '{$oArquivo->db81_descricao}',\n";
		echo " \"<a download href='$oArquivo->db81_nomearquivo' >Download</a>\")\n";
	}
  echo "</script>\n";
}
?>    
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_frmArquivos(aLinha);">
<form name='form1'>
	<table width="790" style="padding-top:25px;" align="center">
		<tr>
			<td>
				<fieldset>
					<legend><b>Arquivos Templates Padrão</b></legend>
					<div id="frmArquivos">
					</div>
				</fieldset>
			</td>
		</tr>
	</table>
</form>
<?
 //db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>

<script type="text/javascript">

	function js_frmArquivos(aDados){
		oDBGridArquivos = new DBGrid('exporta');
		oDBGridArquivos.nameInstance = 'oDBGridArquivos';
		//oDBGridArquivos.aAligns = new Array('left','left','center');
		oDBGridArquivos.setHeader(new Array('Arquivo','Descrição','Ação'));
		oDBGridArquivos.setHeight(330);
		oDBGridArquivos.aAligns = new Array('left','left','center');
		oDBGridArquivos.show($('frmArquivos'));
		
		oDBGridArquivos.clearAll(true);
		for(i=0;i < aDados.length; i++){
			oDBGridArquivos.addRow(aDados[i]);
		}
		oDBGridArquivos.renderRows();
	}
</script>
</body>
</html>