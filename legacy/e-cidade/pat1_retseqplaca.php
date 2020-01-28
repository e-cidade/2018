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
include("classes/db_cfpatriplaca_classe.php");
include("classes/db_bensplaca_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$clcfpatriplaca = new cl_cfpatriplaca;
$clbensplaca    = new cl_bensplaca;
$result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query(db_getsession("DB_instit")));
$seq='0';
if($result!=false && $clcfpatriplaca->numrows>0){
  db_fieldsmemory($result,0);
  if (isset($classif)&&$classif!=""){
  	if ($t07_confplaca==2){
  		$classif=str_replace(".","",$classif);
  	}  	
  	  	  	
  	$sWhere = " t41_placa = '$classif' ";
  	
  	if (BensParametroPlaca::controlaPlacaPorInstituicao()) {
  	  $sWhere  .= " and t52_instit = ".db_getsession("DB_instit");
    }
  	
  	$result_ultseq = $clbensplaca->sql_record($clbensplaca->sql_query_fileLockInLine(null, "max(t41_placaseq)as max_seq",null, $sWhere));
  	if ($clbensplaca->numrows>0){
  		db_fieldsmemory($result_ultseq,0);
  		if ($max_seq!=""){  			
  			$seq=$max_seq;
  			$seq=$seq+1;
  		}else{
  			$seq="001";  			
  		} 
  		$seq=db_formatar($seq,'f','0',$t07_digseqplaca,'e',0);		
  		echo "<script>parent.js_retplaca('$classif','$seq');</script>";  	
  	}else{
  		$seq="001";
  		$seq=db_formatar($seq,'f','0',$t07_digseqplaca,'e',0);  		
  		echo "<script>parent.js_retplaca('$classif','$seq');</script>";  		
  	}
  }
}
?>