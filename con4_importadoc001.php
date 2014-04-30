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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_db_config_classe.php");
include ("classes/db_db_documento_classe.php");
include ("classes/db_db_paragrafo_classe.php");
include ("classes/db_db_docparag_classe.php");
include ("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$cldb_documento = new cl_db_documento;
$cldb_config    = new cl_db_config;
$cldb_paragrafo = new cl_db_paragrafo;
$cldb_docparag  = new cl_db_docparag;

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

if (isset ($documento) && $documento != "") {
	db_inicio_transacao();
	
	$result_doc = $cldb_documento->sql_record($cldb_documento->sql_query_file($documento));
	
	if ($cldb_documento->numrows > 0) {
		db_fieldsmemory($result_doc,0);
		
		$cldb_documento->db03_instit  = $db03_instit;
		$cldb_documento->db03_tipodoc = $db03_tipodoc;
		$cldb_documento->db03_descr   = substr($db03_descr,0,28)." (IMPORTADO)";
		$cldb_documento->incluir(null);
		
		$erro_msg = $cldb_documento->erro_msg;
		$coddoc   = $cldb_documento->db03_docum;
		$descrdoc = $cldb_documento->db03_descr;
		
		if ($cldb_documento->erro_status == 0) {
			$sqlerro = true;
		}
		
	} else {
		$sqlerro = true;
	}
	
	if ($sqlerro == false) {
		
		$result_parag  = $cldb_docparag->sql_record($cldb_docparag->sql_query($documento, null, "*", "db04_ordem"));
		$numrows_parag = $cldb_docparag->numrows;
		
		for ($w = 0; $w < $numrows_parag; $w ++) {
			db_fieldsmemory($result_parag, $w);
			$ordem = $db04_ordem;
			
			if ($sqlerro == false) {
				
				$cldb_paragrafo->db02_descr       = substr($db02_descr,0,40);
				$cldb_paragrafo->db02_texto       = @$db02_texto;
				$cldb_paragrafo->db02_alinha      = $db02_alinha;
				$cldb_paragrafo->db02_inicia      = $db02_inicia;
				$cldb_paragrafo->db02_espaca      = $db02_espaca;
				$cldb_paragrafo->db02_altura      = ($db02_altura==""?1:$db02_altura);
				$cldb_paragrafo->db02_largura     = ($db02_largura==""?1:$db02_largura);
				$cldb_paragrafo->db02_alinhamento = ($db02_alinhamento==""?"J":$db02_alinhamento);				
				$cldb_paragrafo->db02_tipo        = ($db02_tipo==""?1:$db02_tipo);
				$cldb_paragrafo->db02_instit      = $db02_instit;
				$cldb_paragrafo->incluir(null);

				$codparag = $cldb_paragrafo->db02_idparag;
				if ($cldb_paragrafo->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $cldb_paragrafo->erro_msg;
					break;
				}
				
			}
			
			if ($sqlerro == false) {
				$cldb_docparag->db04_docum   = $coddoc;
				$cldb_docparag->db04_idparag = $codparag;
				$cldb_docparag->db04_ordem   = $ordem;
				$cldb_docparag->incluir($coddoc, $codparag);
				
				if ($cldb_docparag->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $cldb_docparag->erro_msg;
					break;
				}
				
			}
		}
	}
	
	$erro='false';
	
	if ($coddoc==""||$descrdoc==""||$sqlerro==true){
		$coddoc    = 1;
		$descrdoc  = "ops";
		$erro      = "true";
		$sqlerro   = true;
	}
	
	db_fim_transacao($sqlerro);
//	db_msgbox($erro_msg);
	echo "<script>parent.js_retornaimport($coddoc,'$descrdoc','$erro');</script>";
}
/*
echo "<script>
          parent.document.formaba.parag.disabled=false;
          top.corpo.iframe_parag.location.href='con4_docparag003.php?db03_docum=$db03_docum';
          parent.mo_camada('parag');
          top.corpo.iframe_doc.location.href='con4_docparag004.php?chavepesquisa=$db03_docum';
      </script>";
*/
?>