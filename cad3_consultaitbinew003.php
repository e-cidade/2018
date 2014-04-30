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
include("dbforms/db_funcoes.php");
include("classes/db_itbi_classe.php");
include("classes/db_itbinome_classe.php");
include("classes/db_itbimatric_classe.php");
include("classes/db_itbiavalia_classe.php");
include("classes/db_itbicgm_classe.php");
include("classes/db_itbilogin_classe.php");
include("classes/db_itburbano_classe.php");
include("classes/db_cgm_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
//echo $HTTP_SERVER_VARS["QUERY_STRING"];
//exit;

$clitbi       = new cl_itbi;
$clitbinome   = new cl_itbinome;
$clitbimatric = new cl_itbimatric;
$clitbiavalia = new cl_itbiavalia;
$clitbicgm    = new cl_itbicgm;
$clitburbano  = new cl_itburbano;
$clitbilogin  = new cl_itbilogin;
$clcgm        = new cl_cgm;
$clrotulo     = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j14_nome");
$clrotulo->label("j13_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<style>

table.tab1 td {
  border:0px solid #000000;
  background-color: #FFFFFF;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  color: #000000;
}
table.tab1 th {
  border:0px solid #000000;
  align: right;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 11px;
  color: #000000;
}
</style>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<?
if(isset($it01_guia) && trim($it01_guia) != "" ){
  $campos = " * ";
  $sql  = " select $campos from itbi ";
  $sql .= "        left  join itbiavalia      on it01_guia   = it14_guia ";
  $sql .= "        left  join itbidadosimovel on it01_guia   = it22_itbi ";
  $sql .= "        inner join itbitransacao   on it04_codigo = it01_tipotransacao ";
  $sql .= "        left  join itburbano       on it01_guia   = it05_guia ";
  $sql .= "        left  join itbimatric      on it01_guia   = it06_guia ";
  $sql .= "        left  join iptuant         on j40_matric  = it06_matric ";
  $sql .= " where it01_guia = $it01_guia ";
// die($sql);
// echo $sql."<br>";
  $result = $clitbi->sql_record($sql);
//  echo "numrows -- ".$clitbi->numrows;    
  if($clitbi->numrows > 0){
    db_fieldsmemory($result,0);
  }else{
//    echo "<script>parent.db_iframe_itbi.hide();</script>";
    echo "<script>parent.db_iframe_consulta.hide();</script>";
    echo "<script>parent.alert('Matrícula Inválida');</script>";
    exit;
  }
  $db_opcao = 3;  
  $db_botao=false;
  include("forms/db_frmitbiconsulta.php");
  echo "<script>document.form1.db_opcao.type='hidden'</script>";
  echo "<script>document.form1.pesquisar.type='hidden'</script>";
}
?>
</body>
</html>