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
include("libs/db_utils.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_cgs_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_prontuarios_ext_classe.php");
include("classes/db_cgsfatorderisco_classe.php");

include("dbforms/db_funcoes.php");


db_postmemory($HTTP_POST_VARS);

$clcgs_und         = new cl_cgs_und;
$clprontuarios     = new cl_prontuarios_ext;
$clcgsfatorderisco = new cl_cgsfatorderisco;

if( isset($chavepesquisaprontuario) && (int)$chavepesquisaprontuario != 0 ){
	$result_prontuario = $clprontuarios->sql_record($clprontuarios->sql_query_ext($chavepesquisaprontuario));
	$obj_prontuario    = db_utils::fieldsMemory($result_prontuario,0);
	$chavepesquisacgs  = $obj_prontuario->sd24_i_numcgs;
}
if( isset($chavepesquisacgs) && (int)$chavepesquisacgs != 0){
	
	$result = $clcgs_und->sql_record($clcgs_und->sql_query($chavepesquisacgs));
	db_fieldsmemory($result,0);
	
	?>
		<table border="1" width="100%" height="100%">
			<tr>
				<td>
					<select name="cgsfatorderisco" id="cgsfatorderisco" size="10" onclick="js_desabexc()"     style="font-size:9px;width:400px;height:120px" multiple disable>
					<?
					$result_cgsfatorderisco = pg_query( $clcgsfatorderisco->sql_query(null,"*","s105_v_descricao","s106_i_cgs = $chavepesquisacgs") );
					if( pg_numrows($result_cgsfatorderisco) > 0 ){
						for($i=0; $i < pg_numrows($result_cgsfatorderisco); $i++ ){
							$obj_cgsfatorderisco = db_utils::fieldsMemory($result_cgsfatorderisco,$i);
							echo "<option value={$obj_cgsfatorderisco->s105_i_codigo}>{$obj_cgsfatorderisco->s105_v_descricao}</option>";
						}
					}
					?>
					</select>			
				</td>
				
			</tr>
		</table>
	<?
}
?>