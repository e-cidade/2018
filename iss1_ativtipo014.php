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
include("classes/db_ativtipo_classe.php");
include("classes/db_tipcalc_classe.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
$clativtipo = new cl_ativtipo;
$cltipcalc = new cl_tipcalc;
$clrotulo = new rotulocampo;
$clrotulo->label("q81_codigo");
$clrotulo->label("q81_descr");
$clrotulo->label("q85_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
echo"
<script>
	parent.document.form1.atualizar.disabled=true;
</script>
";

if(isset($atualizar)){
  db_inicio_transacao();
  $result03=$clativtipo->sql_record($clativtipo->sql_query_file($q80_ativ,"","q80_tipcal"));
  if($clativtipo->numrows>0){
    $clativtipo->q80_ativ=$q80_ativ;
    $clativtipo->excluir($q80_ativ);
    if($clativtipo->erro_status=='0'){
       $sqlerro = true;
    }
  }  

  
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $clativtipo->q80_ativ=$q80_ativ;
      $clativtipo->q80_tipcal=$dados[1];
      $clativtipo->incluir($q80_ativ,$dados[1]);
      if($clativtipo->erro_status=='0'){
        $sqlerro = true;
      }
    }
    $proximo=next($vt);
  }  
  db_fim_transacao($sqlerro);
}


?>
<script>
function js_atualizar(){
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
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
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
text-align: center;
color: black;
background-color:#ccddcc;       
}
</style>
<html>
  <form name="form1" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" nowrap >
  <tr>
    <td align="center" valign="top">
<?    
     db_input('q80_ativ',8,'',true,'hidden',3);
     db_input('atualizar',8,'',true,'hidden',3);

?>     
      <table border='1' width="100%" nowrap>
<?    
     if(isset($q80_ativ)){
       $result01=$cltipcalc->sql_record($cltipcalc->sql_query("","q81_codigo,q81_descr,q85_descr","q81_cadcalc,q81_descr"));
       $numrows01=$cltipcalc->numrows;
       if($numrows01>0){ 
          echo " 
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Tq81_codigo'>".str_replace(":","",$Lq81_codigo)."</td>
	     <td class='cabec' align='center'  title='$Tq81_descr'>".str_replace(":","",$Lq81_descr)."</td>
	     <td class='cabec' align='center'  title='$Tq85_descr'>".str_replace(":","",$Lq85_descr)."</td>
	   </tr>
          "; 	   
       } 
       $result02=$clativtipo->sql_record($clativtipo->sql_query_file($q80_ativ,"","q80_tipcal"));
       $numrows02=$clativtipo->numrows;
       for($i=0; $i<$numrows01; $i++){
         db_fieldsmemory($result01,$i);
         $che="";
	 for($h=0; $h<$numrows02; $h++){
           db_fieldsmemory($result02,$h);
	   if($q80_tipcal==$q81_codigo){
	     $che="checked";
	   } 
	 }
         echo"
           <tr>
	     <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$q81_codigo' id='CHECK_".$q81_codigo."'></td>
              <td  class='corpo'  align='center' title='$Tq81_codigo'><label for='CHECK_".$q81_codigo."' style=\"cursor: hand\"><small>$q81_codigo</small></label></td>
              <td  class='corpo'  align='center' title='$Tq81_descr'><label for='CHECK_".$q81_codigo."' style=\"cursor: hand\"><small>$q81_descr</small></label></td>
              <td  class='corpo'  align='center' title='$Tq85_descr'><label style=\"cursor: hand\"><small>$q85_descr</small></label></td>
           </tr>";	        
       }
     }  
?>    </table>
    </td>
  </tr>  
  </table>
</html>  
</html>
<?
echo"
<script>
	parent.document.form1.atualizar.disabled=false;
</script>
";

if(isset($atualizar)){
  $clativtipo->erro(true,false);
  db_redireciona("iss1_ativtipo014.php?q80_ativ=$q80_ativ");
}
?>