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
include("classes/db_restosgavetas_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrestosgavetas = new cl_restosgavetas;

$clrotulo = new rotulocampo;

$clrotulo->label("cm27_i_gaveta");
$clrotulo->label("cm26_i_sepultamento");
$clrotulo->label("z01_nome");
$clrotulo->label("cm26_d_entrada");
$clrotulo->label("cm27_d_exumprevista");
$clrotulo->label("cm27_d_exumfeita");
$clrotulo->label("cm27_c_ossoario");


if(isset($chavepesquisa)){
   $result = $clrestosgavetas->sql_record("select cm27_i_gaveta,
                                                  cm26_i_sepultamento,
                                                  z01_nome,
                                                  to_char(cm26_d_entrada,'dd/mm/yyyy') as cm26_d_entrada,
                                                  to_char(cm27_d_exumprevista,'dd/mm/yyyy') as cm27_d_exumprevista,
                                                  to_char(cm27_d_exumfeita,'dd/mm/yyyy') as cm27_d_exumfeita,
                                                  cm27_c_ossoario
                                             from restosgavetas
                                             left join gavetas       on cm27_i_restogaveta = cm26_i_codigo
                                            inner join sepultamentos on cm01_i_codigo = cm26_i_sepultamento
                                            inner join cgm           on z01_numcgm = cm26_i_sepultamento
                                            where cm26_i_ossoariojazigo=$chavepesquisa");
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
<br>
<table border="1" cellspacing="0" cellpadding="0">
  <tr>
   
   <? if(tipo == 'J'){ ?>
     <td><?=$Lcm27_i_gaveta?></td>
   <?}?>  
   
     <td><?=$Lcm26_i_sepultamento?></td>
     <td><?=$Lz01_nome?></td>
     <td><?=$Lcm26_d_entrada?></td>
   
   <? if(tipo == 'J'){ ?>
     <td><?=$Lcm27_d_exumprevista?></td>
     <td><?=$Lcm27_d_exumfeita?></td>
   <?}?>

     <td><?=$Lcm27_c_ossoario?></td>
  </tr>
  
  <?
  for( $x=0; $x<$clrestosgavetas->numrows; $x++ ){
    db_fieldsmemory( $result, $x );
    ?>
       <tr>

         <? if(tipo == 'J'){ ?>
          <td><?=$cm27_i_gaveta?></td>
	 <?}?>

          <td><?=$cm26_i_sepultamento?></td>
          <td><?=$z01_nome?></td>
          <td><?=$cm26_d_entrada?></td>
          

         <? if(tipo == 'J'){ ?>
	  <td><?=$cm27_d_exumprevista?></td>
          <td><?=$cm27_d_exumfeita?></td>
         <?}?>
	 
	 <td><?=$cm27_c_ossoario=='N'?'NÃO':'SIM'?></td>

       </tr>
    <?
  }
  ?>
</table>
</body>
</html>
<?