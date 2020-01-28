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
include("classes/db_socios_classe.php");
include("classes/db_cgm_classe.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
$clsocios = new cl_socios;
$clcgm = new cl_cgm;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_munic");
$clrotulo->label("q95_perc");
//parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<style>
a:hover {text-decoration:none;
         color: black;
         font-weight: bold; 
        }
a:visited {text-decoration:none;
           color: black;
           font-weight: bold; 
          }
a:active {
          color: black;
          font-weight: bold; 
         }  
.x {
  background-color:#999999;       
   }
.dados {
       border:none;
       text-align: center;
       font-size: 10px;
       color: darkblue;
       }
.dados2 {
       text-align: center;
       font-size: 9px;
       }
</style>
<script>
function js_op(obj){
  arr=obj.id.split('_');
  if(arr[0]=="a"){
    var tipo="al";
  }else{
    var tipo="ex";
  }  
  parent.location.href='iss1_socios004.php?op='+tipo+'&q07_inscr='+arr[1]+'&q95_cgmpri='+arr[2]+'&q95_numcgm='+arr[3];
  
}
</script>
<html>
  <form name="form1" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="dados">
  <tr>
    <td align="center" valign="top">
      <table border='1' width="100%">
<?    
     if(isset($q95_cgmpri)){
       $result22=$clsocios->sql_record($clsocios->sql_query_file($q95_cgmpri,"","q95_numcgm,q95_perc"));
       $numrows22=$clsocios->numrows;
       if($numrows22>0){
	 if($numrows22>=1 && $cgmnops=="ok" || $numrows22>1 && $cgmnops!="ok"){
          echo " 
           <tr class='dados'>
	     <td title='$Tz01_numcgm'>".str_replace(":","",$Lz01_numcgm)."</td>
	     <td title='$Tz01_nome'>".str_replace(":","",$Lz01_nome)."</td>
	     <td title='$Tz01_munic'>".str_replace(":","",$Lz01_munic)."</td>
	     <td title='$Tq95_perc'>".str_replace(":","",$Lq95_perc)."</td>
	     <td title='Alterar ou Excluir'><b>Opções</b></td>
	   </tr>
         "; 	   
	}  
       }else{
         echo "Nenhuma sócio cadastrado.";  
       }   
       for($i=0; $i<$numrows22; $i++){
         db_fieldsmemory($result22,$i);
	 if($q95_numcgm!=$cgmnops){
  	   $result23=$clcgm->sql_record($clcgm->sql_query_file($q95_numcgm,"z01_numcgm,z01_nome,z01_munic"));
  	   db_fieldsmemory($result23,0);
           echo"
             <tr class='dados2'>
	      <td title='$Tz01_numcgm'>$z01_numcgm</td>
	      <td title='$Tz01_nome'>$z01_nome</td>
	      <td title='$Tz01_munic'>$z01_munic</td>
	      <td title='$Tq95_perc'>$q95_perc</td>
	      ";
	      if(isset($db_opcaoal)){
	        echo "<td  ><span class='x' >&nbsp;A&nbsp;</span>&nbsp;&nbsp;&nbsp;<span  class='x'>&nbsp;E&nbsp;</span></td>";
              }else{
	        echo "<td  ><span id='a_".$q07_inscr."_".$q95_cgmpri."_".$z01_numcgm."' class='x' onclick='js_op(this);'><a title='ALTERAR SÓCIO' href='#'>&nbsp;A&nbsp;</a></span>&nbsp;&nbsp;&nbsp;<span id='e_".$q07_inscr."_".$q95_cgmpri."_".$z01_numcgm."' onclick='js_op(this);' class='x'><a title='EXCLUIR SÓCIO' href='#'>&nbsp;E&nbsp;</a></span></td>";
              }  		
	      echo "</tr>"; 	   
         }
       }  	 
     }else{
       echo "Nenhuma sócio cadastrado.";  
     } 
?>    </table>
    </td>
  </tr>  
  </table>
</html>  
</html>