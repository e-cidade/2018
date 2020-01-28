<?php
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("classes/db_veicretirada_classe.php");

$clveicretirada = new cl_veicretirada;
$funcao_js      = "carregaDetalheRetirada|ve60_codigo";

/*
 * Recupera as informações passadas por GET para o objeto $oGet e efetua a busca
 * de retiradas e exibe na db_lovrot
 */
$oGet               = db_utils::postMemory($_GET, false);
$sCampos            = "distinct ve60_codigo, descrdepto as dl_Departamento_Solicitante, ve60_datasaida, ";
$sCampos           .= "ve60_horasaida, z01_nome as dl_Motorista, ve60_passageiro, ve60_destino, ve60_medidasaida";
$sWhere             = "veicretirada.ve60_veiculo = $oGet->veiculo";
$sSqlBuscaRetiradas = $clveicretirada->sql_query_info(null, $sCampos, "ve60_datasaida", $sWhere);
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
  function carregaDetalheRetirada(iCodRetirada) {
    
    var sUrl    = "vei3_veicretirada002.php?codigo="+iCodRetirada;
    var iWidth  = parent.document.body.scrollWidth-100;
    var iHeight = parent.document.body.scrollHeight-100;
    js_OpenJanelaIframe('parent','func_veiculo_detalhes', sUrl,'Detalhes Retirada',true, '0', 0, iWidth, iHeight);
  }
</script>
</head>
  <body>
    <center>
      <fieldset>
        <?db_lovrot($sSqlBuscaRetiradas, 15, "()", "%", $funcao_js);?>
      </fieldset>
    </center>
  </body>
</html>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
