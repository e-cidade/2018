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
include("classes/db_cursoedu_classe.php");
include("classes/db_base_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clcurso = new cl_curso;
$clbase = new cl_base;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
a:link {
          color: #444444;
          font-weight: bold;
          text-decoration: none;
         }
a:hover {
          color: #FF9900;
          text-decoration: none;
        }
a:visited {
           color: #444444;
           font-weight: bold;
           text-decoration: none;
          }
a:active {
          color: #444444;
          font-weight: bold;
          text-decoration: none;
         }
.cabec {
       text-align: left;
       font-size: 10;
       color: #DEB887;
       background-color:#444444;
       border:1px solid #CCCCCC;
       }
.cabec1 {
       text-align: left;
       font-size: 10;
       color: #FF9900;
       background-color:#EAEAEA;
       }
.curso {
       font-size: 10;
       background-color:#CCCCCC;
       }
.base {
       font-size: 9;
       background-color:#EAEAEA;
       }

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Cursos e Bases Curriculares vinculadas a escola <?=$ed18_c_nome?></b></legend>
   <table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
   <tr>
    <td align="center" valign="top">
     <font face="tahoma">
     <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
      <tr class='cabec'>
       <td><b>Cursos</b></td>
      </tr>
      <tr>
       <td>
        <table border='0' width="100%" bgcolor="#cccccc" cellspacing="0px">
              <?
              $escola = db_getsession("DB_coddepto");
              $result = $clcurso->sql_record($clcurso->sql_query_cursoescola("","*","ed29_c_descr"," ed71_i_escola = $escola"));
              if($clcurso->numrows>0){
               for($c=0;$c<$clcurso->numrows;$c++){
                db_fieldsmemory($result,$c);
                //echo "<tr><td style=\"border:1px solid #555555;\" class='curso' align='left'><a href=\"javascript:parent.location.href='edu1_cursoabas002.php?chavepesquisa=$ed29_i_codigo'\" title='Editar Curso'>".$ed29_c_descr."</a></td></tr>";
                echo "<tr><td style=\"border:1px solid #555555;\" class='curso' align='left'>$ed29_c_descr</td></tr>";
                $result1 = $clbase->sql_record($clbase->sql_query_base("","*","ed31_c_descr"," ed31_i_curso = $ed29_i_codigo AND ed77_i_escola = $escola"));
                echo "<tr><td class='base' align='center'>";
                echo "<table border='0' width='90%' cellspacing='0px'>";
                echo "<tr><td class='cabec1'><b>Bases:</b></td></tr>";
                if($clbase->numrows>0){
                 for($t=0;$t<$clbase->numrows;$t++){
                  db_fieldsmemory($result1,$t);
                  //echo "<tr><td class='base'><a href=\"javascript:parent.location.href='edu1_baseabas002.php?chavepesquisa=$ed31_i_codigo'\" title='Editar Base Curricular'>".$ed31_c_descr."</a></td></tr>";
                  echo "<tr><td class='base'>$ed31_c_descr</td></tr>";
                 }
                }else{
                 echo "<tr><td class='base'>SEM BASES</td></tr>";
                }
                echo "</table>";
                echo "</td></tr>";
               }
              }else{
               ?>
               <table border='0px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
                <tr class='base'>
                 <td>Nenhum curso cadastrado.</td>
                </tr>
               </table>
               <?
              }
              ?>
        </table>
       </td>
      </tr>
     </table>
     </font>
    </td>
   </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>