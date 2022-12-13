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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
$clrotulo = new rotulocampo;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
include(base64_decode($arquivo));
if(isset($sql) && $sql!=""){ 
  $sql=base64_decode($sql);
  $campos=base64_decode($campos);
  $msg_vazio=base64_decode($msg_vazio);
}
if(isset($sql_marca) && $sql_marca!=""){ 
  $sql_marca=base64_decode($sql_marca);
  $result02=db_query($sql_marca);
  $numrows02=pg_numrows($result02);
}
?>
<html>
<head>
<style>
a:hover {
          color:blue;
        }
a:visited {text-decoration:;
           color: black;
           font-weight: bold; 
          }
a:active {
          color: black;
          font-weight: bold; 
         }  

.cabec {
       text-align: center;
       font-size: <?=$tamfontecabec?>;
       color: <?=$textocabec?>;
       background-color:<?=$fundocabec?>;       
       border-color: darkblue;
       }
.corpo {
       text-align: center;
       font-size: <?=$tamfontecorpo?>;
       color: <?=$textocorpo?>;
       background-color:<?=$fundocorpo?>;       
       }

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}

</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post">
  <center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr>
    <td  valign="top">
      <table border='1' width="100%" bgcolor="#cccccc" id="tabela_seleciona">
<?
if(isset($sql) && $sql!=""){ 
       $result=db_query($sql);
       $numrows=pg_numrows($result);
       $numcols=pg_numfields($result);
       if($db_opcao=="Incluir"){
       } 
       if($db_opcao=="Incluir"){
          $db_opcao=1;
       }else if($db_opcao=="Alterar"){
          $db_opcao=2;
       }else if($db_opcao=="Excluir"){ 
          $db_opcao=3;
       }
       if($numrows>0){ 
	  $matriz_campos=split(",",$campos); 
          $numcolunas=sizeof($matriz_campos);
          echo "   <tr class='cabec'>";
          if($db_opcao!=3){  
            echo "     <td align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>";
          }else{
            echo "     <td align='center'><a  title='Inverte Marcação' href='' onclick='return false;'>M</a></td>";
          }
	  for($w=0; $w<$numcolunas; $w++){
	    $campo=str_replace(" ","",$matriz_campos[$w]);
            $clrotulo->label($campo);
	    $Tlabel="T$campo";
	    $Llabel="L$campo";
	      echo "   <td class='cabec' ".($corponowrap=="true"?"nowrap":"")." title='".$$Tlabel."'>".str_replace(":","",$$Llabel)."</td>\n";
	  }  
          echo "   </tr>"; 	   
          $cabec=true;
       }elseif(!$numrows>0){
          echo $msg_vazio;
       } 	 
       if(isset($cabec) && $cabec==true){
         $matriz02=split(",",$chaves);       
         for($i=0; $i<$numrows; $i++){
           db_fieldsmemory($result,$i,true);
           $checa="";
           if(isset($sql_marca) && $sql_marca!=""){ 
             for($s=0;$s<$numrows02;$s++){
               for($w=0; $w<sizeof($matriz02); $w++){
                 $campo=pg_result($result02,$s,$matriz02[$w]);   
                 if($campo==$$matriz02[$w]){
                   $checa=" checked ";  
                 }else{
                   $checa=""; 
                   $s=$numrows02;
  		   break;  
                 } 
               }
             }  
  	   }  
           if(isset($ckd)){
	       $checa=" checked ";
	   } 
 

           $li=$i+1; 
           echo "   <tr id='linha_$li'>";
           if($db_opcao!=3){  
             echo "<td align='left'><input id='CHECK_$li' name='CHECK_$li' type='checkbox' $checa></td>";
           }else{
             echo "<td align='left'><input disabled id='CHECK_$li' name='CHECK_$li' type='checkbox' $checa ></td>";
           }
	   for($w=0; $w<$numcolunas; $w++){
 	     $campo=strtolower(trim($matriz_campos[$w]));
	     $Tlabel="T$campo";
  	     $Llabel="L$campo";
                
	     echo "   <td id='".$campo."_".$li."' title='".$$Tlabel."' ".($corponowrap=="true"?"nowrap":"")." class='corpo'>".$$campo."&nbsp;</td>";
	     if($w+1==$numcolunas){
	       if($db_opcao==33){
	       }  
	     }
	   } 
           echo "   </tr>";
         }	       
       }
}
?>    </table>
    </td>
  </tr>  
  </table>
  </center>
  </form>
</body>  
</html>
<?
unlink(base64_decode($arquivo)) or die('Erro');
?>