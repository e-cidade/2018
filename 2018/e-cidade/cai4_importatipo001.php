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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("classes/db_arretipo_classe.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clarretipo = new cl_arretipo;
$db_opcao = 1;
$db_botao = true;
$sqlerro = false;
if (isset ($tipo) && $tipo != "") {
	db_inicio_transacao();
	$Result = $clarretipo->sql_record($clarretipo->sql_query_file($tipo));
	$NumRows = $clarretipo->numrows;
	db_fieldsmemory($Result,0);
	$Result_max = $clarretipo->sql_record($clarretipo->sql_query_file(null,"max(k00_tipo) as max"));
	db_fieldsmemory($Result_max,0);
	$codigo = $max+1; 		
	$clarretipo->k00_codbco=$k00_codbco;
	$clarretipo->k00_codage=$k00_codage;
	$clarretipo->k00_descr=$k00_descr."(Import)";
	$clarretipo->k00_emrec=$k00_emrec;
	if ($k00_agnum=="t"){		
	  $clarretipo->k00_agnum="1";
	}elseif ($k00_agnum=="f"){
	  $clarretipo->k00_agnum="0";
	}		
	if ($k00_agpar=="t"){		
	  $clarretipo->k00_agpar="1";
	}elseif ($k00_agpar=="f"){
	  $clarretipo->k00_agpar="0";	  
	}			
	$clarretipo->k00_hist1="$k00_hist1 ";
	$clarretipo->k00_hist2="$k00_hist2 ";
	$clarretipo->k00_hist3="$k00_hist3 ";
	$clarretipo->k00_hist4="$k00_hist4 ";
	$clarretipo->k00_hist5="$k00_hist5 ";
	$clarretipo->k00_hist6="$k00_hist6 ";
	$clarretipo->k00_hist7="$k00_hist7 ";
	$clarretipo->k00_hist8="$k00_hist8 ";
	$clarretipo->k00_txban=$k00_txban;
	$clarretipo->k00_rectx=$k00_rectx;
	$clarretipo->codmodelo=$codmodelo;
	$clarretipo->k00_impval=$k00_impval;	
	$clarretipo->k03_tipo=$k03_tipo;
	$clarretipo->incluir($codigo);
	if ($clarretipo->erro_status==0){
		$sqlerro=true;
		$erro_msg=$clarretipo->erro_msg;
		db_msgbox($erro_msg);		
	}
	$codtipo=$clarretipo->k00_tipo;
	$descrtipo=$clarretipo->k00_descr;	
	$erro='false';
	if ($codtipo==""||$descrtipo==""||$sqlerro==true){
		$codtipo=1;
		$descrtipo="ops";
		$erro="true";
		$sqlerro=true;
	}	
	db_fim_transacao($sqlerro);
	echo "<script>parent.js_retornaimport($codtipo,'$descrtipo','$erro');</script>";
}
?>