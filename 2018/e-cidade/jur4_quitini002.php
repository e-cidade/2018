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

$sql="select distinct v59_inicial from (select distinct v59_inicial,k00_numpre from inicialnumpre left join arrecad on arrecad.k00_numpre = inicialnumpre.v59_numpre where arrecad.k00_numpre is null) as x ";
$result=pg_exec($sql);
for ($i=0;$i<pg_numrows($result);$i++){
	db_fieldsmemory($result,$i);
	$sql_iniquit="select * from inicial inner join inicialmov on v50_inicial = v56_inicial where  v56_codsit = 8 and v50_incial=$v59_inicial";
	$result_iniquit=pg_exec($sql_iniquit);
	if (pg_numrows($result_iniquit)==0){
	      $sql_test="select * from inicialnumpre inner join arrecad on k00_numpre = v59_numpre where v59_inicial = $v59_inicial";
	      $result_test=pg_exec($sql_test);
	      if (pg_numrows($result_test)==0){
		      $sql_codmov="select nextval('inicialmov_v56_codmov_seq')";
		      $result_codmov=pg_exec($sql_codmov);
		      db_fieldsmemory($result_codmov,0);
		      $codigo=$nextval;
		      $sql_inc="insert into inicialmov values ($codigo,$v59_inicial,8,'Inicial quitada automaticamente pelo sistema',current_date,1)";
		      $result_inc=pg_exec($sql_inc);
		      $sql_alt="update inicial set v50_codmov = $codigo where v50_inicial = $v59_inicial";
		      $result_alt=pg_exec($sql_alt);		
	      }
	}
}
echo "<script>location.href='jur4_quitini001.php';</script>";
//----------------------------------------------------------------------------------------------------------------------
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
//----------------------------------------------------------------------------------------------------------------------
?>