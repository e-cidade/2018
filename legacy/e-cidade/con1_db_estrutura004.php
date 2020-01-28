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
include("classes/db_db_estrutura_classe.php");
include("classes/db_db_estruturanivel_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cldb_estrutura = new cl_db_estrutura;
$cldb_estruturanivel = new cl_db_estruturanivel;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  $sqlerro=false;
  db_inicio_transacao();
  //rotina para incluir na tabela db_estrutura
    $cldb_estrutura->db77_estrut = $db77_estrut;
    $cldb_estrutura->db77_descr  = $db77_descr;
    $cldb_estrutura->incluir($db77_codestrut);
    if($cldb_estrutura->erro_status==0){
      $sqlerro=true;
      $erro_msg= $cldb_estrutura->erro_msg;
    }else{
      $ok_msg= $cldb_estrutura->erro_msg;
    }
    $db77_codestrut=$cldb_estrutura->db77_codestrut;
  //final

  //rotina pra incluir na tabela db_estruturanivel
    $matriz = split("\.",$db77_estrut);
    $tam = sizeof($matriz);
    $ini=0;
    for($i=0; $i<$tam; $i++){
       $tamanho = strlen($matriz[$i]); 
       $cldb_estruturanivel->db78_codestrut = $db77_codestrut;
       $cldb_estruturanivel->db78_tamanho   = $tamanho;
       $cldb_estruturanivel->db78_inicio    = "$ini";
       $cldb_estruturanivel->db78_nivel     = "$i";
       $cldb_estruturanivel->db78_descr     = "NÍVEL $i";
       $cldb_estruturanivel->incluir($db77_codestrut,"$i");
       if($cldb_estruturanivel->erro_status==0){
	  $sqlerro=true;
       }
       $erro_msg = $cldb_estruturanivel->erro_msg;
       $ini=$ini+$tamanho;
    }
  //final
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_estrutura.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($incluir)){
  if($cldb_estrutura->erro_status=="0"){
    db_msgbox($erro_msg);
    if($cldb_estrutura->erro_campo!=""){
      echo "<script> document.form1.".$cldb_estrutura->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_estrutura->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($ok_msg);
    echo "
          <script>
	      parent.document.formaba.db_estruturanivel.disabled=false;\n
	      top.corpo.iframe_db_estruturanivel.location.href='con1_db_estruturanivel001.php?db78_codestrut=$db77_codestrut';\n
              parent.mo_camada('db_estruturanivel');
	      location.href='con1_db_estrutura006.php?chavepesquisa=$db77_codestrut';
          </script>
    ";
  }
}
?>