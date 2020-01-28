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

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
if(isset($HTTP_POST_VARS["pesquisar"])) {
  postmemory($HTTP_POST_VARS);
  $result = db_query("select *,to_char(datalei,'DD-MM-YYYY') as data from db_leis where upper(texto) like upper('%$lei%') and upper(ementa) like upper('$ementas%')");
  $numrows = pg_numrows($result);
  echo "
    <html>
    <head>
    <title>Consulta de Leis do Munic&iacute;pio</title>
    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
        <style>
        .links {
          font-family: Arial, Helvetica, sans-serif;
          font-size: 12px;
          font-weight: bold;
          color: red;
          text-decoration: none;
        }
         a.links:hover {
          font-family: Arial, Helvetica, sans-serif;
          font-size: 12px;
          font-weight: bold;
          color: black;
          text-decoration: underline;
        }
        </style>
    </head>
    <body leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">\n<a name=\"TOPO\">\n";
    if($numrows > 0) {
          echo "<div align=\"center\"><BR> <table border=\"1\" cellpadding=\"3\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"510\">\n";
          echo "<tr bgcolor=\"#E1FFFF\"><th>Lei No.</th><th>Data</th><th>Ementa</th>\n";
          for($i = 0;$i < $numrows;$i++) {
            echo "<tr>
                        <td><a class=\"links\" href=\"#lei$i\">".pg_result($result,$i,"numerolei")."</a></td>
                        <td>".pg_result($result,$i,"data")."</td>
                        <td>".pg_result($result,$i,"ementa")."</td>                                                                
                          </tr>\n";
          }
          echo "</table><hr width=\"100%\"></div>\n";
          for($i = 0;$i < $numrows;$i++) {
            $str = pg_result($result,$i,"documento");
                if(trim(@$lei) != "") {
                  $str = str_replace($lei,"<font style=\"background-color:red\">$lei</font>",$str);
                  $str = str_replace(strtoupper($lei),"<font style=\"background-color:red\">".strtoupper($lei)."</font>",$str);
                }
                if(trim(@$ementa) != "") {
                  $str = str_replace($ementa,"<font style=\"background-color:blue\">$ementa</font>",$str);
                  $str = str_replace(strtoupper($ementa),"<font style=\"background-color:blue\">".strtoupper($ementa)."</font>",$str);
                }
            echo "<br><br><br><a class=\"links\" href=\"#TOPO\"> << Voltar</a><a name=\"lei$i\">".$str;
          }
        } else {
          echo "<tr><td width=\"100%\" align=\"center\" valign=\"middle\" height=\"100%\"><h3>Nenhuma lei encontrada</h3><input type=\"button\" onclick=\"window.close()\" value=\"Fechar\"></td></tr></table>\n";
        }
  echo "
    </body>
    </html>\n";
} else {
  mens_help();
  db_logs("","",0,"Digita argumento de pesquisa de leis.");
  db_mensagem("leis_cab","leis_rod");
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function js_pesquisar() {
  jan = window.open('','procurar','width=770,height=490,scrollbars=1,menubar=1,resizable=1');
  jan.moveTo(10,2);
}
</script>
<style type="text/css">
<?db_estilosite();?>
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<img src="imagens/leis.gif">
<?mens_div();?>
<center>
              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
                <tr> 
                  <td height="60" align="<?=$DB_align1?>">
                    <?=$DB_mens1?>
                  </td>
                </tr>
                <tr> 
                  <td align="center" valign="middle"><!-- InstanceBeginEditable name="digita" -->
                    <form name="form1" method="post" target="procurar" onSubmit="js_pesquisar()">
                     <table width="50%" border="1" cellpadding="0" cellspacing="0" bordercolor="#FFFFFF" class="texto">
                      <tr>
                        <td height="40" align="center" valign="middle">Texto
                          a procurar nas ementas<br>                          
                          <input name="ementas" size="49">
                        </td>
                      </tr>
                      <tr>
                        <td height="40" align="center" valign="middle">Texto
                          a Procurar nas Leis<br>                        
                          <input name="lei" size="49">
                        </td>
                      </tr>
                      <tr>
                        <td height="30" align="center" valign="middle">
                          <input  class="botao" type="submit" value="Pesquisar" name="pesquisar">
                        </td>
                      </tr>
                    </table>
                    </form>
                  </td>
                </tr>
                <tr> 
                  <td height="60" align="<?=$DB_align2?>">
                    <?=$DB_mens2?>
                  </td>
                </tr>
              </table>
</center>
</body>
<!-- InstanceEnd --></html>
<?
} //fim do if(isset($HTTP_POST_VARS["pesquisar]))
?>