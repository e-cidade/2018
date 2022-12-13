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
include("libs/db_sql.php");

$sql="select v50_inicial from inicial";
$result=pg_exec($sql);
for ($i=0;$i<pg_numrows($result);$i++){
	db_fieldsmemory($result,$i);
	$sql_mov="select max(v56_codmov)as codmov from inicialmov where v56_inicial = $v50_inicial";
	$result_mov=pg_exec($sql_mov);
	if (pg_numrows($result_mov)>0){
	   db_fieldsmemory($result_mov,0);
	   if ($codmov!=""){
	   $sql_altini="update inicial set v50_codmov = $codmov where v50_inicial = $v50_inicial ";
	   $result_altini=pg_exec($sql_altini);
	   }
	}
}
echo "<script>location.href='jur4_quitini001.php';</script>";
//----------------------------------------------------------------------------------------------------------------------
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
//----------------------------------------------------------------------------------------------------------------------
?>