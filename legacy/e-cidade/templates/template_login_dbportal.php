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
?>
<html>
<head>
<title>Tela de acesso para DBPortal</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.botao {

  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  font-weight: bold;
  color: #000000;
  background-color: #FF0000;

}
-->
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_addevent();">
<form name="form1" id="form1" method="post"> 
  <table width="790" height="430" border="0" cellpadding="0" cellspacing="0"> 
    <tr> 
      <td width="203" height="437" valign="top" bgcolor="#7F7F7F">
        <img src="imagens/imagem3d_o.jpg" width="155" height="434">
      </td> 
      <td width="279" valign="middle" bgcolor="#7F7F7F">
        
        <table width="100%" height="257" border="0" cellpadding="5" cellspacing="0"> 
          <tr>  
            <td height="140" align="center">
              <img src="imagens/consultor_o.gif" width="199" height="104">
            </td> 
          </tr> 
					<?
					  if (isset($DB_CONEXAO)){
			    ?>
             <input id="user"     name="user"      type="hidden" value="<?=@$user?>"     size="40">  
             <input id="senh"     name="senh"      type="hidden" value="<?=@$senh?>"     size="40">  
             <input id="port"     name="port"      type="hidden" value="<?=@$port?>"     size="40">  
             <input id="servidor" name="servidor"  type="hidden" value="<?=@$servidor?>" size="40">  
          <tr>  
            <td>Servidor:<br>
              <select name='serv' id="serv">
                <option name='condicaoservidor' value=''>Selecione um servidor</option>");
                <?
                  for( $iInd = 0; $iInd < count( $DB_CONEXAO ); $iInd++){
                ?>
                    <option name='condicaoservidor' value='<?=@$iInd?>'>
                      <?=$DB_CONEXAO[$iInd]["SERVIDOR"].":".$DB_CONEXAO[$iInd]["PORTA"] ?>
                    </option>");
                <?
                  }
                ?>
              </select>
            </td> 
          </tr> 
          <tr>  
            <td>Bases:<br> 
              <select name='base' id="base">
                <option name='condicaobase' value=''>Selecione uma base</option>
                   
                <?
                  if ( isset($servidor) && $servidor != "" ) {
                    
                  	echo "<script>document.form1.serv.value = '{$serv}';</script>";
                    for ( $xInd = 0; $xInd < $numrows_bases; $xInd++ ) {
                      $datname = pg_result($result_bases,$xInd,"datname");
                ?>
                <option name='condicaobase' value='<?=@$datname?>'><?=@$datname?> </option>");
                <?
                    }   
                  }
                 ?>
              </select>
            </td>
          </tr> 
          <?
            }
          ?>
          <tr>  
            <td>Login:<br> 
              <input name="usu_login" id="usu_login" type="text" size="40"> 
            </td> 
          </tr> 
          <tr>  
            <td>Senha:<br> 
              <input name="usu_senha" id="usu_senha" type="password" size="40"> 
            </td> 
          </tr> 
          <tr>  
            <td height="33" align="right">
              <input name="btnlogar" type="button" class="botao" id="btnlogar" value="Acessar">
            </td> 
          </tr> 
          <tr>  
            <td height="33" align="center" valign="middle" 
                style="font-family: Arial, Helvetica, sans-serif;font-size: 15px;font-weight: bold;;color:red" 
                id="testaLogin">&nbsp;
            </td> 
          </tr> 
        </table>
      </td> 
      <td width="271" valign="top">
        <table width="100%" height="371" border="0" cellpadding="0" cellspacing="0"> 
          <tr> 
            <td height="240" align="center" bgcolor="#0C2E60">
              <font size="4" color="white">Indique o nome do relatório a ser impresso:<br><br></font> 
                <input name="arquivo" id="arquivo" value="" type="text" size="40"><br><br><br> 
                <input name="gerar" id="gerar" value="Gera Relatório" type="button"> 
            </td> 
          </tr> 
          <tr> 
            <td height="200" align="center" >
              <a href="http://www.dbseller.com.br">
                <img border="none" src="imagens/logo_dbseller_o.gif" width="181" height="62">
              </a>
            </td> 
          </tr> 
        </table>
      </td> 
    </tr> 
  </table>
</form>
</body>
</html>