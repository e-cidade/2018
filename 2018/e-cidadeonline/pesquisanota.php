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

include ("libs/db_conecta.php");
include ("libs/db_stdlib.php");
include ("libs/db_sql.php");
include ("classes/db_issplanit_classe.php");
$cl_issplanit = new cl_issplanit;
//die($cl_issplanit->sql_query_file(null,"*", null, "q21_cnpj= '$cnpj' and q21_nota = '$nota' and q21_serie='$serie'"));
$result = $cl_issplanit->sql_record($cl_issplanit->sql_query_file(null, "*", null, "q21_cnpj= '$cnpj' and q21_nota = '$nota' and q21_serie='$serie' and q21_status = 1"));
if ($cl_issplanit->numrows > 0) {
	echo " <script> alert('CNPJ, Nota e S�rie ja lan�adas');
  	parent.js_notaexiste()
 	 </script>";
} 
?>