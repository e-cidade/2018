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
include("classes/db_tabativ_classe.php");
$cltabativ = new cl_tabativ;
$clrotulo = new rotulocampo;
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($q07_inscr) && $q07_inscr!=""){ 
  $sql = $cltabativ->sql_query_atividade_inscr($q07_inscr,"*","q07_seq","q07_inscr = $q07_inscr and q07_databx is null");
  $msg_vazio='<b>Nenhum registro encontrado</b>';
  $chaves="q88_inscr,q07_seq";
  $campos= "q07_inscr,q07_seq,q88_inscr,q07_ativ,q03_descr,q07_datain,q07_datafi,q07_databx,q07_perman,q07_quant,q11_tipcalc, q81_descr";
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
       font-size: 10;
       color: darkblue;
       background-color:#aacccc;       
       border-color: darkblue;
       }
.corpo {
       text-align: center;
       font-size: 9;
       color: black;
       background-color:#ccddcc;       
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
function js_trocapri(seq){
  sequencia.document.form1.seq.value=seq;
  sequencia.document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post" action="iss1_tabativbaixaiframe.php">
  <center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr>
    <td  valign="top">
      <iframe  frameborder="0" name="sequencia"   leftmargin="0" topmargin="0" src="iss1_tabativbaixaiframe02.php?q07_inscr=<?=$q07_inscr?>" height="1" width="1" style="visibility:hidden">
              </iframe>
      <table border='1' width="100%" bgcolor="#cccccc" id="tabela_seleciona">
<?
if(isset($sql) && $sql!=""){ 
       $result=pg_query($sql);
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
	    echo "   <td class='cabec' title='".$$Tlabel."'>".str_replace(":","",$$Llabel)."</td>\n";
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

           $li=$i+1; 
           echo "   <tr id='linha_$li'>";
           if($db_opcao!=3){  
             echo "<td align='left'><input id='CHECK_$li' name='CHECK_$li' type='checkbox' ></td>";
           }else{
             echo "<td align='left'><input disabled id='CHECK_$li' name='CHECK_$li' type='checkbox' ></td>";
           }
	   for($w=0; $w<$numcolunas; $w++){
 	     $campo=strtolower(trim($matriz_campos[$w]));
	     $Tlabel="T$campo";
  	     $Llabel="L$campo";
             if($campo=="q88_inscr"){
               $checa=""; 
               if($$campo=="*"){
                  $checa="checked";
               }  
               echo "<td class='corpo' align='center'><input  id='q88_inscr_$li' value='$q07_seq'  name='principal' type='radio' $checa onclick='js_trocapri($q07_seq)' ></td>";
             }else{   
	       echo "   <td id='".$campo."_".$li."' title='".$$Tlabel."' class='corpo'>".$$campo."&nbsp;</td>";
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