<?
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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
if(isset($fechar)){
  $retorno = 'window.close()';
}else{
  if(isset($pagina_retorno)){
    $retorno = "location.href='".$pagina_retorno."'";
  }else{
    $retorno = "";
  }
}
?>
<html>
<head>
<title>Erro</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body>
<center>

<table width="85%" border="1" cellpadding="0" cellspacing="0">
  <tr> 
    <td align="center"><font color="#FF0000" size="3" face="Arial, Helvetica, sans-serif">Notifica&ccedil;&atilde;o do Sistema:</font></td>
  </tr>
  <tr> 
    <td height="56" align="center"><font size="2" face="Arial, Helvetica, sans-serif"><br>
    <?
	echo @$db_erro;
	?>
     </font></td>
  </tr>
  <tr>
      <td align="center"> 
        <input name="retorna" type="button" id="retorna" onclick="<?=$retorno?>" value="Retornar"></td>
  </tr>
</table>
</center>
</body>
</html>