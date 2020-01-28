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
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");

//$conn=$conn->con();

$base=db_getsession("DB_NBASE");
$ipbase="$DB_SERVIDOR";
$aborta=0;
$sql    = "select db30_codversao,db30_codrelease from db_versao order by db30_codver desc limit 1";
$result = pg_exec($sql);
$numrows= pg_numrows($result);
if ($numrows==0)
{
   #echo "\n Não existe registro na tabela db_versão. \n";
    $aborta=1;
}

if ($aborta<>1)
{  
 
 $db30_codversao = pg_result($result,0,0);
 $db30_codrelease= pg_result($result,0,1);

 $releaseatual="2.".$db30_codversao.".".$db30_codrelease."";

 $db30_codrelease= pg_result($result,0,1)+1;
 $release_nova="2.$db30_codversao.$db30_codrelease";

 $verifica=getcwd()."/release/";
 $release="dbportal-2.".$db30_codversao.".".$db30_codrelease."-linux.tar.bz2";

 $verifica=`cd $verifica;find . -name $release`;

}

?>

  <html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script>
  </script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
 
  <table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td height="430" align="center" valign="middle" bgcolor="#CCCCCC">

  <form name="form1" method="post" onsubmit="return js_verifica_campos();">

 <table width="40%" border="0" cellspacing="0" cellpadding="5"> 
  <tr>
  <td width="34%" nowrap><strong>Release atual:</strong></td>
  <td width="66%"><input name="atual" type="text" readonly  size="30" maxlength="30" value="<?echo $releaseatual ?>" ></td>
  </tr>
  <tr>
  <td nowrap><strong>Release a ser atualizada:</strong></td>

<?
  if (trim($verifica)=='')
     {
      print"<td> Release $release_nova não disponível no momento!</td>";
     }
  if ( trim($verifica)<>'')
     {
     
     db_msgbox("Atenção!Antes de começar a atualização da release verifique se existe usuários logados no sistema acessando o menu consulta e submenu usuários on-line,pois quando começar a atualização o sistema ficará inativo para os usuários.");  
      print"<td><input name=nova type=text readonly maxlength=30 size=30 value='$release_nova' > </td>";
      print"
      </tr>
      <tr>
      <td align='center' colspan='2'>";
?>   
    <input name="processa"  type="button"  value='Atualizar release' onclick= "js_processa();" >
<? print"
      </td>
      </tr>";
     }
?>

</table>
</form>
</td>
</tr>
</table>

</body>
</html>
<script>
function js_processa(){
obj = document.form1;
 js_OpenJanelaIframe('','db_iframe_relatorio','sys4_baixareleasecliente002.php?&atual='+document.form1.atual.value+'&nova='+document.form1.nova.value,'Pesquisa',true);

  }

</script>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>