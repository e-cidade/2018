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

$db_opcao = 1;

$cliframe_seleciona = new cl_iframe_seleciona;

$sql = "select j14_codigo, j14_nome from ruas";

?>

<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
	<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<form name="form1" action="#" method="post">
<?
$cliframe_seleciona->sql 								= $sql;
$cliframe_seleciona->campos 						= "j14_codigo, j14_nome";
$cliframe_seleciona->mostra_totalizador		= "N";
$cliframe_seleciona->posicao_totalizador 		= "A";
$cliframe_seleciona->legenda 						= "Seleciona Logradouros";
$cliframe_seleciona->textocabec 					= "darkblue";
$cliframe_seleciona->textocorpo 					= "black";
$cliframe_seleciona->fundocabec 					= "#aacccc";
$cliframe_seleciona->fundocorpo 					= "#ccddcc";
$cliframe_seleciona->iframe_height 				= '400px';
$cliframe_seleciona->iframe_width 				= '100%';
$cliframe_seleciona->iframe_nome 				= "logradouro";
$cliframe_seleciona->chaves 							= "j14_codigo";
$cliframe_seleciona->marcador 					= true;
$cliframe_seleciona->dbscript                        = "onClick='parent.js_mandadados(this.value, this.checked);'";
$cliframe_seleciona->js_marcador                 = "parent.js_enviadosH();";
$cliframe_seleciona->iframe_seleciona($db_opcao);

?>

<input type="hidden" name="logradouros" id="logradouros" />
</form>

</body>		
</html>
<script type="text/javascript">
	function js_mandadados(logradouro, statusChk){
		var log = document.getElementById('logradouros');	
		if (statusChk == false) {
			js_enviadosH();
		}
		else {
			if (log.value != '') {
				log.value += ",";
			}
			log.value += logradouro;
		}
	}
	
	function js_enviadosH(){
		var aListaChk = logradouro.document.getElementsByTagName('input');
		var log = document.getElementById('logradouros');
		log.value='';
		for(i=0;i<aListaChk.length;i++) {
			if(aListaChk[i].type=='checkbox'){
				if(aListaChk[i].checked){
					if (log.value != '') {
						log.value += ",";
					}
					log.value +=aListaChk[i].value;
				}
			}
		}
	}
	
</script>