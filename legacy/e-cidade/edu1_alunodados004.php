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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aluno_classe.php");
include("classes/db_alunoalt_classe.php");
include("classes/db_alunoaltcampos_classe.php");
include("classes/db_alunoaltconf_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claluno = new cl_aluno;
$clalunoalt = new cl_alunoalt;
$clalunoaltcampos = new cl_alunoaltcampos;
$clalunoaltconf = new cl_alunoaltconf;
$claluno->rotulo->label();
if(isset($confirmar)){
 $ed277_i_alunoalt = explode(",",$ed277_i_alunoalt);
 for($r=0;$r<count($ed277_i_alunoalt);$r++){
  $clalunoaltconf->ed277_i_usuario = db_getsession("DB_id_usuario");
  $clalunoaltconf->ed277_i_data = time();
  $clalunoaltconf->ed277_i_alunoalt = $ed277_i_alunoalt[$r];
  $clalunoaltconf->incluir(null);
 }
 ?>
 <script>
  parent.db_iframe_alunoalterado.hide();
  parent.location.href = "edu1_alunodados002.php?chavepesquisa=<?=$aluno?>";
 </script>
 <?
 exit;
}
$result = pg_query("SELECT ed47_v_nome FROM aluno WHERE ed47_i_codigo = $aluno");
$nomealuno = trim(pg_result($result,0,0));
function NomeUsuario($usuario){
 if($usuario!=""){
  $result = pg_query("SELECT nome FROM db_usuarios WHERE id_usuario = $usuario");
  return trim(pg_result($result,0,0));
 }else{
  return "";
 }
}
$array_modulo = array("1"=>"ESCOLA","2"=>"BIBLIOTECA");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <center>
   <br>
   <fieldset style="width:95%"><legend><b>Alterações realizadas no cadastro do aluno <?=$aluno." - ".$nomealuno?></b></legend>
    <table width="100%" border="1" cellspacing="0" cellpading="1">
    <?
    $codigo_alunoalt = "";
    $sep = "";
    $sql3 = "SELECT *
             FROM alunoalt
             WHERE ed275_i_aluno = $aluno
             AND ed275_i_usuario != ".db_getsession("DB_id_usuario")."
             AND not exists(select * from alunoaltconf
                            where ed277_i_alunoalt = ed275_i_codigo
                            and ed277_i_usuario = ".db_getsession("DB_id_usuario")."
                           )
             ORDER BY ed275_i_data DESC
            ";
    $result3 = pg_query($sql3);
    for($x=0;$x<pg_num_rows($result3);$x++){
     db_fieldsmemory($result3,$x);
     $codigo_alunoalt .= $sep.$ed275_i_codigo;
     $sep = ",";
     ?>
     <tr>
      <td colspan="4">
       <table cellspacing="0" width="100%">
        <tr style="color:#DEB887;background:#444444">
         <td width="50%">
          Usuário:
          <b><?=$ed275_i_usuario." - ".NomeUsuario($ed275_i_usuario)?></b>
         </td>
         <td width="25%">
          Módulo utilizado: <b><?=$array_modulo[$ed275_i_modulo]?></b>
         </td>
         <td width="25%">
          Data/Hora: <b><?=date("d/m/Y  H:i:s",$ed275_i_data)?></b>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     <tr>
      <td width="5%" bgcolor="#f3f3f3">&nbsp;</td>
      <td bgcolor="#999999"><b>Campo Alterado</b></td>
      <td bgcolor="#999999"><b>Conteúdo Anterior</b></td>
      <td bgcolor="#999999"><b>Conteúdo Após Alteração</b></td>
     </tr>
     <?
     $result4 = $clalunoaltcampos->sql_record($clalunoaltcampos->sql_query("","ed276_c_campo,ed276_c_contant,ed276_c_contatual",""," ed276_i_alunoalt = $ed275_i_codigo"));
     for($y=0;$y<$clalunoaltcampos->numrows;$y++){
      db_fieldsmemory($result4,$y);
      $label_aluno = "L".$ed276_c_campo;
      ?>
      <tr bgcolor="#f3f3f3">
       <td>&nbsp;</td>
       <td><?=$$label_aluno?></td>
       <td><?=$ed276_c_contant==""?"&nbsp;":$ed276_c_contant?></td>
       <td><?=$ed276_c_contatual==""?"&nbsp;":$ed276_c_contatual?></td>
      </tr>
      <?
     }
    }
    ?>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
 <tr>
  <td align="center">
   <br>
   <input type="submit" value="Confirmar Leitura" name="confirmar">
   <input type="hidden" value="<?=$codigo_alunoalt?>" name="ed277_i_alunoalt">
   <input type="hidden" value="<?=$aluno?>" name="aluno">
  </td>
 </tr>
</table>
</form>
</body>
</html>