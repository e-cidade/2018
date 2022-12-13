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
include("classes/db_unidademedicos_classe.php");
$cl_unidademedicos = new cl_unidademedicos;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
if(isset($Processar)){

 $sql = "select * from unidademedicos
          inner join unidades on unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade
          inner join medicos on medicos.sd03_i_id = unidademedicos.sd04_i_medico
          inner join cgm as cgm1 on cgm1.z01_numcgm = sd03_i_codigo
          where sd04_i_unidade = $unidade";
 $result = $cl_unidademedicos -> sql_record($sql);
 if($cl_unidademedicos->numrows<>0){
 ?>
 <table width="100%" border="1" cellpadding="1" cellspacing="0">
 <tr bgcolor="#cccccc">
  <td><b>Código</b></td>
  <td><b>Nome</b></td>
 </tr>
 <?
 //for
 for($i=0;$i<$cl_unidademedicos->numrows;$i++){
 db_fieldsmemory($result,$i);
 ?>
 <tr>
  <td><?=$sd04_i_medico?></td><td><?=$z01_nome?></td>
 </tr>
 <?
 }
 }else{
  echo "<center><br><br><font color='red'>Nenhum registro encontrado!</font></center>";
 }
}else{
echo "<center><br><br>Informe a Unidade e clique em <b>Processar</b>...</center>";
}
?>
</table>
</body>
</html>