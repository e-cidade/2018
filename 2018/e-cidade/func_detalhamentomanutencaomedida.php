<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "classes/db_veicmanutencaomedida_classe.php";

$oDaoVeicManutencaoMedida = new cl_veicmanutencaomedida;
$funcao_js                = "";

/*
 * Recupera as informa��es passadas por GET para o objeto $oGet e efetua a busca
 * de retiradas e exibe na db_lovrot
 */
$oGet                  = db_utils::postMemory($_GET, false);
$sCampos               = "ve66_sequencial, ve66_data, ve66_motivo as \"dl_Motivo_Troca\", ve66_ativo, ";
$sCampos              .= "ve67_motivo as \"dl_Motivo_Cancelamento\", ve67_data as \"dl_Data_Cancelamento\",";
$sCampos              .= "usuario_medida.id_usuario ||' - '|| usuario_medida.nome as \"dl_Usuario_Inclusao\",";
$sCampos              .= "usuario_medidacanc.id_usuario ||' - '|| usuario_medidacanc.nome as \"dl_Usuario_Cancela\"";
$sWhere                = "ve66_veiculo = {$oGet->veiculo}";
$sSqlBuscaManutencoes  = $oDaoVeicManutencaoMedida->sql_query_manutencoes(null, $sCampos, "", $sWhere);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
  <body>
    <center>
      <fieldset>
        <?db_lovrot($sSqlBuscaManutencoes, 15, "()", "%", $funcao_js);?>
      </fieldset>
    </center>
  </body>
</html>