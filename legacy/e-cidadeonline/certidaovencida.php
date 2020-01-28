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

$sql = parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_query("select logo from db_config limit 1");
for ($i=0;$i<(pg_numfields($sql));$i++){
 db_fieldsmemory($sql,0);
}
?>
<script>
js_verificapagina("certidaoautentica.php");
</script>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<table width="650" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#CCCCCC">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="9%">
            <div align="center"><img src="imagens/<?=$logo?>" width="54" height="70"></div>
          </td>
          <td width="88%">
            <div align="center"><font size="6" face="Arial, Helvetica, sans-serif"><b><font size="5" face="Verdana, Arial, Helvetica, sans-serif"><?=$nomeinst?></font><font face="Verdana, Arial, Helvetica, sans-serif"><br>
              </font></b><font face="Verdana, Arial, Helvetica, sans-serif"><font size="4">Secretaria
              Municipal de Finan&ccedil;as</font></font></font></div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<tr>  
  <td align="center" height="77">
   <font face='arial' size='2'><b>Certidão com o prazo de validade expirado</b><br>
                  Por Favor entre em contato com a Prefeitura</font><br>
                  <br><center>
  </td>
</tr>
</table>
</body>
</html>