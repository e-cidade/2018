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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_mer_restriitem_classe.php");
include("classes/db_mer_restricaointolerancia_classe.php");
include("classes/db_matricula_classe.php");
$clmer_restriitem = new cl_mer_restriitem;
$clmatricula = new cl_matricula;
$clmer_restricaointolerancia = new cl_mer_restricaointolerancia;
$escola = db_getsession("DB_coddepto");
$aluno1 = @$aluno;
$campos = "        turma.ed57_c_descr,serie.ed11_c_descr,calendario.ed52_c_descr,cursoedu.ed29_c_descr,ed60_i_numaluno,";
$campos .= "        ed47_v_nome,ed47_i_codigo,ed60_c_situacao,ed60_i_codigo,ed60_d_datamatricula ";
$result = $clmatricula->sql_record($clmatricula->sql_query("",$campos,""," ed60_i_codigo = $aluno1 "));
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 font-weight: bold;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 10;
}
</style>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form2" method="post" action="">
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<tr>
 <td align="center" valign="top">
  <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
   <tr class='cabec'>
    <td align='center' colspan='8'>
    <?if($clmatricula->numrows>0){?>
      Turma: <?=pg_result($result,0,"ed57_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
      Etapa: <?=pg_result($result,0,"ed11_c_descr")?>&nbsp;&nbsp;&nbsp;&nbsp;
      Calendário: <?=pg_result($result,0,"ed52_c_descr")?><br>
      Ensino: <?=pg_result($result,0,"ed29_c_descr")?>      
    </td>
   </tr>
   <tr><td height='2' colspan='8' bgcolor='#444444'></td></tr>
   <tr bgcolor="#DBDBDB" align="center">
    <td width="5%"><b>Código</b></td>
    <td><b>Aluno</b></td>    
    <td><b>Turma</b></td>
    <td><b>Etapa</b></td>
    <td><b>Restrição</b></td>
   </tr>
   <?}else{
         echo"<table width='100%' bgcolor='#CCCCCC'><tr><td class='aluno' align='center'><font size=2 ><b>Escolha um aluno!</b></font></td></tr></table>";
      }?>
   <?   
   for($c=0;$c<$clmatricula->numrows;$c++){
    db_fieldsmemory($result,$c);
    $inf_ant  = explode("|",RFanterior($ed60_i_codigo));
    $turmaant = $inf_ant[0];
    $rfant    = $inf_ant[1];  
    $sCampos  = "mer_alimento.me35_c_nomealimento,alimento.me35_c_nomealimento as itemsub,me24_i_codigo";  
    $result1  = $clmer_restriitem->sql_record(
                                              $clmer_restriitem->sql_query("",
                                                                           $sCampos,
                                                                           "",
                                                                           "me24_i_aluno = $ed47_i_codigo"
                                                                          )
                                             );                                            
    if ($clmer_restriitem->numrows>0) {
      $cor = "#FFFACD"; 
    } else {
      $cor="#f3f3f3";
    }
    ?>
    <tr bgcolor="<?=$cor?>">
     <td class="aluno"><?=$ed47_i_codigo?></td>
     <td class="aluno" align="center"><?=$ed47_v_nome?></td>
     <td class="aluno" align="center"><?=$ed57_c_descr?></td>
     <td class="aluno" align="center"><?=$ed11_c_descr?></td>
     <td colspan="2">
     <table border ="0" width="100%">
     <?if($clmer_restriitem->numrows>0){?>
         <tr>         
         <td  class="aluno" align="center"><b>Alimento </b></td>
         <td colspan="2" class='aluno' align='center'><b>Alimento Substituto</b></td> 
         </tr>
         <?}else{?>
         <tr>         
         <td class="aluno" align="center"><b>Sem Restrição</b></td>
         <td class='aluno' align='center'><b></b></td> 
         </tr>
         
         <?}?>
     <?for($d=0;$d<$clmer_restriitem->numrows;$d++){
        db_fieldsmemory($result1,$d);     
        ?>
        
         <tr>         
         <td class="aluno" align="left" width= "40%"><?=$me35_c_nomealimento?></td>
         <td colspan="2" class='aluno' align='right' width= "40%"><?=$itemsub?></td> 
         </tr>
          
        <?}
        $result3 = $clmer_restricaointolerancia->sql_record($clmer_restricaointolerancia->sql_query("",
                                                                                            "*",
                                                                                            "",
                                                                                            "me34_i_restricao = $me24_i_codigo"
                                                                                            )
                                                   );                                                   
        db_fieldsmemory($result3,0);?>
        <tr bgcolor="#DBDBDB">         
         <td colspan="2"  align="center" >Intolerância</td>
         </tr>
      <?for ($v=0; $v < $clmer_restricaointolerancia->numrows; $v++) {
          if ($me34_i_restricao == $me24_i_codigo) { 
            db_fieldsmemory($result3,$v); ?>     
            <tr>         
              <td colspan="2" class="aluno" align="center" width= "100%"><?=$me33_c_descr?></td>
            </tr>
        <?}?>         
      <?}?>      
     </table>
    </td>
    </tr>
    <?
   }
   ?>
  </table>
 </td>
</tr>
</table>
</body>
</html>