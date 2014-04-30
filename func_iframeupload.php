<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

$oGet     = db_utils::postMemory($_GET);
$oPost    = db_utils::postMemory($_POST); 

$clrotulo = new rotulocampo;
$lErro    = false;

if (isset($_FILES[$oGet->idcampo])) {
  /**
   *  Nome do novo arquivo.
   */
  $sNomeArquivo    = $_FILES[$oGet->idcampo]["name"];
  
  /*
   * Nome do arquivo temporário gerado no ./tmp.
   */
  $sNomeArquivoTmp = $_FILES[$oGet->idcampo]["tmp_name"];

  /*
   * Seta o nome do arquivo destino do upload.
   */
  $sArquivoDestino = "/tmp/retorno{$sNomeArquivo}";
    
  /*
   * Faz um upload do arquivo para o local especificado.
   */
  if (copy($sNomeArquivoTmp, $sArquivoDestino)) {
    
      echo "aqui,,as djklf jasdkjfasd<br>";
      $sNomeArquivoProcessar = $sArquivoDestino;  
      echo $sNomeArquivo."<br>";
  } else {

    unlink($sNomeArquivoTmp);
    $sMsgErro = 'Erro ao enviar arquivo!';
    $lErro    = true;
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id='uploadIframeBoxClone'><?=@$sNomeArquivoProcessar;?></div>
</body>
</html>
<script>
<? 
if (isset($sMsgErro) && $lErro) {
  
  echo "parent.$('{$oGet->idcampo}').value = '';\n";
  echo "alert('{$sMsgErro}');\n";
  echo "parent.js_endloading();\n";
} else {
  
  /**
   * Clona form de origem para enviar o arquivo selecionado no iframe parent.
   */
  if (isset($oGet->clone) && !isset($sNomeArquivoProcessar)) {
    
    echo "var cloneFormulario = '{$oGet->clone}';\n";
    echo "if (parent.$(cloneFormulario)) {\n";
    echo "  var oFormClone = parent.$(cloneFormulario).cloneNode(true);\n";
    echo "  $('uploadIframeBoxClone').appendChild(oFormClone);\n";
    echo "  oFormClone.submit();\n";
    echo "}\n";
  }
  
  /**
   * Verifica se imagem foi copiada com sucesso, sem erros então preenche o preview da imagem.
   */
  if (isset($sNomeArquivoProcessar)) {
      
    if (!$lErro) {
      echo "parent.$oGet->function('$sNomeArquivoProcessar');\n";
    }
  
    echo "parent.js_endloading();\n";
  }
}
?>
</script>