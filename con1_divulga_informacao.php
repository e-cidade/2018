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
include("libs/db_usuariosonline.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//$divulgacao_codigo = 1;
?>
<html>
<title>
</title>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
p {
  font-size:12px;
  color:black;
  align:justify;
}
</style>
</head>
<body>
  <table>
  <tr>
  <?
  if( $divulgacao_codigo == 'instit'){
  	
    $numrows = 0; 
  	$descricao_item = 'Selecione uma instituição clicando na imagens ou no nome da mesma.<br><br> <strong>Topo da tela:<br><br>Módulos</strong> - Seleciona um módulo <br> <strong>Instituição </strong> - Seleciona uma instituição <br> <strong>Fechar</strong> - Fecha o sistema <br> <strong>Info</strong> - Informações do usuário <br> <strong>Dicas </strong>- Esta tela';
  	
  }else if( $divulgacao_codigo == 'corpo'){
  	
    $numrows = 0; 
  	$descricao_item = 'Selecione um módulo clicando na imagens ou no nome do módulo. <br><br> <strong>Topo da tela:<br><br>Módulos</strong> - Seleciona um módulo <br> <strong>Instituição </strong> - Seleciona uma instituição <br> <strong>Fechar</strong> - Fecha o sistema <br> <strong>Info</strong> - Informações do usuário <br> <strong>Dicas </strong>- Esta tela';
  	
  }else if( $divulgacao_codigo == 'modulos'){
  	
    $numrows = 0; 
  	$descricao_item = 'Acesse o MENU do sistema clicando em CADASTRO, CONSUILTA, RELATÓRIOS ou PROCEDIMENTOS e em seguida selecione um ítem de menu e clique para acessar. Repita este procedimento para acessar novamente.<br><br> <strong>Topo da tela:<br><br>Módulos</strong> - Seleciona um módulo <br> <strong>Instituição </strong> - Seleciona uma instituição <br> <strong>Fechar</strong> - Fecha o sistema <br> <strong>Info</strong> - Informações do usuário <br> <strong>Dicas </strong>- Esta tela';

  }else{
 
    $sql = "select demodescr from db_itensmenudemonstracao where id_item = $divulgacao_codigo";
    $result = pg_exec($sql);
    $numrows = pg_numrows($result);
    if( $numrows > 0 ){
      $descricao = pg_result($result,0,0); 
    }else{
      $descricao = "";
    }

    $sql = "select desctec from db_itensmenu where id_item = $divulgacao_codigo";
    $result = pg_exec($sql);
    $descricao_item = pg_result($result,0,0); 


  }
  if($numrows>0){
  ?>
    <td align='left'  style='font-size:12px;color:blue' ><strong>Ação a Executar (<?=$divulgacao_codigo?>): 
    </td>
    <td align='right'> 
    <input type="button" name="fechar" value="Fechar" style="border:none;" onclick="parent.JANS['divulgacao_dbseller'].hide()">
    </td>
    </tr>
    <tr>
    <td colspan=2 align='justify' style='font-size:12px;color:blue' >
    <hr>
    <?
    echo $descricao;
    ?>
  <?
  }else{
  	?>
    <td colspan=2 align='right' > 
    <input type="button" name="fechar" value="Fechar" style="border:none;" onclick="parent.JANS['divulgacao_dbseller'].hide()">
  	<?
  }
  ?>
  </td>
  </tr>
  <tr>
  <td style='font-size:12px;color:blue' align='left' colspan=2>
  <hr>
  <strong>Resumo desta opção:</strong>
  <hr>
  </td>
  </tr>
  <tr>
  <td colspan=2 align='justify' style='font-size:12px;color:black'>
  <?
 
 
  echo $descricao_item;
  
  ?>
  </td>
  </tr>
  </table>
</body>
</html>