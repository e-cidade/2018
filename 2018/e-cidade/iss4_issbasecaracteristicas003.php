<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oGet  = db_utils::postMemory($_GET);

$oDaoCaracteristica        = db_utils::getDao('caracteristica');
$oDaoIssbaseCaracteristica = db_utils::getDao('issbasecaracteristica');
$oDaoGrupoCaracteristica   = db_utils::getDao('grupocaracteristica');


$sCampos             = "db140_sequencial          as codigo_caracteristica,                         ";
$sCampos		        .= "db140_descricao           as descricao_caracteristica,                      ";
$sCampos		        .= "db140_grupocaracteristica as codigo_grupo,                                  ";
$sCampos		        .= "db139_descricao           as descricao_grupo,                               ";
$sCampos            .= "(select true                                                                ";
$sCampos            .= "   from issbasecaracteristica                                               ";
$sCampos            .= "  where q138_caracteristica = caracteristica.db140_sequencial               ";
$sCampos            .= "    and q138_inscr          = {$oGet->q123_inscr}) as possui_caracteristica ";

$sSqlCaracteristicas = $oDaoCaracteristica->sql_query(null,
																										  $sCampos,
																										  null,
																										  "db138_sequencial = 1");

$rsCaracteristica    = $oDaoCaracteristica->sql_record($sSqlCaracteristicas);
	
$aCaracteristicas    = db_utils::getCollectionByRecord($rsCaracteristica);
	
$aGrupos = array();
	
foreach ($aCaracteristicas as $oCaracteristicas) {

	$oGrupo = new stdClass();
	$oGrupo->iCodigoGrupo     = $oCaracteristicas->codigo_grupo;
	$oGrupo->sDescricao       = $oCaracteristicas->descricao_grupo;

	$oGrupo->aCaracteristicas = array();

	$aGrupos[$oCaracteristicas->codigo_grupo] = $oGrupo;
	 
}
	
foreach ($aCaracteristicas as $oCaracteristicas) {
		
	$oCaracteristica = new stdClass();

	$oGrupo                      = $aGrupos[$oCaracteristicas->codigo_grupo];

	$oCaracteristica->iCodigo    = $oCaracteristicas->codigo_caracteristica;
	$oCaracteristica->sDescricao = $oCaracteristicas->descricao_caracteristica;

	$oGrupo->aCaracteristicas[$oCaracteristicas->codigo_caracteristica] = $oCaracteristicas;

}
	
?>

<html>
<head>
	<?php 
		db_app::load('scripts.js, estilos.css')
	?>
</head>
  <body >
  <form name="form1" id="form1" method="post">
  <div class="container">
   
  	<fieldset>
  	
  		<legend>
  		  <strong>Características NFS-e</strong>
  		</legend>
  		
  		<table class="form-container">
  		  
  		  <?php
					foreach ($aGrupos as $oGrupo) {
					
						echo "<tr> ";
						echo "  <td> ";
						echo "    <strong>$oGrupo->sDescricao:</strong>";
						echo "  </td>";

						$aComboCaracteristicas = array();
						
						echo "<td>    ";
						
						$lPossuiCaracteristica = false;
						foreach ($oGrupo->aCaracteristicas as $oCaracteristica) {

							$sChecked = '';
							$aComboCaracteristicas[$oCaracteristica->codigo_caracteristica] = $oCaracteristica->descricao_caracteristica;
							
							if ($oCaracteristica->possui_caracteristica == 't') {
							  $lPossuiCaracteristica = true;
								$sChecked = 'selected="selected"' ;
								if ($oCaracteristica->descricao_caracteristica) {
									echo "<input value='$oCaracteristica->descricao_caracteristica' readonly='readonly' style='background-color:#DEB887'/>";
								}
							}
						}
						
						if ( !$lPossuiCaracteristica ) {
							echo "<input value='' readonly='readonly' style='background-color:#DEB887'/>";
						}
						echo "<td>";
						echo "</tr>";
					}
  		  
  		  ?>
  		  
			</table>
  	
  	</fieldset>
  </div>	
	
  </form>
  </body>
</html>