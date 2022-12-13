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
require_once "classes/db_veicabast_classe.php";

$clveicabast = new cl_veicabast;
$funcao_js   = "carregaDetalheAbastecimento|ve70_codigo";

/*
 * Recupera as informa��es passadas por GET para o objeto $oGet e efetua a busca
 * de abastecimentos e exibe na db_lovrot
 */
$oGet                    = db_utils::postMemory($_GET, false);
$sCampos                 = "ve70_codigo#ve70_dtabast#ve70_litros#ve70_valor#ve70_medida";
$sWhere                  = "veicabast.ve70_veiculos = $oGet->veiculo";
$sSqlBuscaAbastecimentos = $clveicabast->sql_query_info(null, $sCampos, 've70_dtabast', $sWhere);
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
  function carregaDetalheAbastecimento(iCodAbastecimento) {
    
    var sUrl    = "vei3_veicabast002.php?codigo="+iCodAbastecimento;
    var iWidth  = parent.document.body.scrollWidth-100;
    var iHeight = parent.document.body.scrollHeight-100;
    js_OpenJanelaIframe('parent','func_veiculo_detalhes', sUrl,'Detalhes Abastecimento',true, '0', 0, iWidth, iHeight);
  }
</script>
</head>

<body>
  <center>
    <fieldset>
      <?db_lovrot($sSqlBuscaAbastecimentos, 15, "()", "%", $funcao_js)?>
    </fieldset>
  </center>
</body>
</html>