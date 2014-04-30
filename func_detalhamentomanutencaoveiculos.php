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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "classes/db_veicmanut_classe.php";

$clveicmanut = new cl_veicmanut;
$funcao_js   = "carregaDetalheManutencao|ve62_codigo"; 

/*
 * Recupera as informações passadas por GET para o objeto $oGet e efetua a busca
 * de abastecimentos e exibe na db_lovrot
 */
$oGet                 = db_utils::postMemory($_GET, false);
$sCampos              = "distinct ve62_codigo#ve62_dtmanut#ve62_vlrmobra#ve62_vlrpecas#ve62_descr#ve62_notafisc#ve62_medida";
$sWhere               = "veicmanut.ve62_veiculos = $oGet->veiculo";
$sSqlBuscaManutencoes = $clveicmanut->sql_query_info(null, $sCampos, "ve62_dtmanut", $sWhere);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript">
  function carregaDetalheManutencao(iCodManutencao) {
    
    var sUrl    = "vei3_veicmanut002.php?codigo="+iCodManutencao;
    var iWidth  = parent.document.body.scrollWidth-100;
    var iHeight = parent.document.body.scrollHeight-100;
    js_OpenJanelaIframe('parent','func_veiculo_detalhes', sUrl,'Detalhes Manutenção',true, '0', 0, iWidth, iHeight);
  }
</script>
</head>

<body>
  <center>
    <fieldset>
      <?db_lovrot($sSqlBuscaManutencoes, 15, "()", "%", $funcao_js)?>
    </fieldset>
  </center>
</body>
</html>