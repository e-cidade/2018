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
?>
<br>
     <table border="1">
      <?
    	   if(!isset($contribs)){
             db_fieldsmemory($result01,0);
	     $contribs=$d02_contri;
	   }  
	  $result09 = $cleditalrua->sql_record($cleditalrua->sql_query($contribs,"d01_numero,d01_descr"));
	  db_fieldsmemory($result09,0); 
      ?>
        <tr>
	  <td colspan="2">
	  <?
	   echo  "<b>$legenda </b>";
	  ?> 
	  </td>
	</tr>
        <tr>
	  <td colspan="2">
	  <?
	   echo  "<b>Edital numero $d01_numero. $d01_descr </b>";
	  ?> 
	  </td>
	</tr>
        <tr>
          <td align="center"><b>Contribuições</b></td>
          <td align="center"><b>Matrículas</b> </td>
	</tr>  
	<tr>  
	  <td valign="top" align="center"> 
           <select name="contribs" size="5" onchange="js_trocacontri()">
           <?
           for($i=0; $i<$numrows01; $i++){
	     db_fieldsmemory($result01,$i);
             if($i%2==0){
	       $cor="style='background-color:#D7CC06 ;'";
	     }else{
	       $cor="style='background-color:#F8EC07 ;'";
	     }     
             echo "<option $cor ".($contribs==$d02_contri?"selected":"")." value='$d02_contri'>$d02_contri</option>\n";
            } 
           ?>
            </select>
	  </td>  
	  <td valign="top">  
	    <select name="matriculas" size="10"  onClick="js_troca(this)">
            <?
	    
            $result03=$clcontlot->sql_record($clcontlot->sql_query($contribs,"","d05_idbql"));
   	    $numrows03=$clcontlot->numrows;
            for($x=0; $x<$numrows03; $x++){
   	     db_fieldsmemory($result03,$x);
	     $result04=$cliptubase->sql_record($cliptubase->sql_query("","j01_matric,z01_nome","","j01_idbql=$d05_idbql"));
  	     $numrows04=$cliptubase->numrows;
             for($k=0; $k<$numrows04; $k++){     
  	       db_fieldsmemory($result04,$k); 
    	       $cor="";
    	       if($x%2==0){
	         $cor="style='background-color:#D7CC06 ;'";
	       }else{
	         $cor="style='background-color:#F8EC07 ;'";
	       }     
	       $j01_matric=db_formatar($j01_matric,"s","0",8,"e");
               echo "<option $cor value='$j01_matric'>$j01_matric-$z01_nome</option>\n";
             } 
 	   }  
           ?>
           </select>
	   </td>
	 </tr>  
       </table>	 
<script>
  function js_troca(obj){
    document.form1.d02_contri.value=document.form1.contribs.value;  
    for(i=0; i<obj.options.length; i++){
      if(obj.options[i].value==obj.value){
        var arr=obj.options[i].text.split("-");
        document.form1.z01_nome.value=arr[1];
        document.form1.j01_matric.value=arr[0];
      }
    }
  }  
  function js_trocacontri(){
    document.form1.submit();
  }   
</script>