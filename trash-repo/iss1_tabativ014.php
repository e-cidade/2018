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
include("classes/db_tabativ_classe.php");
include("classes/db_ativprinc_classe.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
$cltabativ = new cl_tabativ;
$clativprinc = new cl_ativprinc;
$cltabativ->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q03_descr");
$clrotulo->label("q11_tipcalc");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
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
function js_ex(obj){
  arr=obj.id.split('_');
  if(document.form1.principal){
   if(document.form1.principal.value==arr[1]){
     alert("Para excluir esta atividade, selecione outra como principal. ");
     return false;
   }
  }
  parent.location.href='iss1_tabativ004.php?op=ex&inscr='+arr[2]+'&seq='+arr[1];
  
}
function js_al(obj){
  arr=obj.id.split('_');
  if(document.form1.principal){
    if(document.form1.principal.value==arr[1]){
      parent.location.href='iss1_tabativ004.php?pods=nops&op=al&inscr='+arr[2]+'&seq='+arr[1];
      return true;
    }
  }
  parent.location.href='iss1_tabativ004.php?op=al&inscr='+arr[2]+'&seq='+arr[1];
}
</script>
<html>
  <form name="form1" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" class="dados">
  <tr>
    <td align="center" valign="top">
      <table border='1' width="100%">
<?    
     if(isset($q07_inscr)){
       $result21=$clativprinc->sql_record($clativprinc->sql_query_file($q07_inscr,"q88_seq as xq88_seq"));
       if($clativprinc->numrows>0){
	 db_fieldsmemory($result21,0);
       }else{
	  $xq88_seq="";
       }
       $result18=$cltabativ->sql_record($cltabativ->sql_query_atividade_inscr($q07_inscr));
       $numrows18=$cltabativ->numrows;
       if(empty($seqno) && $numrows18>0 || isset($seqno) && $numrows18>1 ){ 
          echo " 
           <tr class='dados'>
	     <td  title='$Tq07_inscr'>".str_replace(":","",$Lq07_inscr)."</td>
	     <td title='$Tq07_seq'>".str_replace(":","",$Lq07_seq)."</td>
	     <td title='$Tq03_descr'>".str_replace(":","",$Lq03_descr)."</td>
	     <td title='$Tq07_datain'>".str_replace(":","",$Lq07_datain)."</td>
	     <td title='$Tq07_datafi'>".str_replace(":","",$Lq07_datafi)."</td>
	     <td title='$Tq07_databx'>".str_replace(":","",$Lq07_databx)."</td>
	     <td title='$Tq07_perman'>".str_replace(":","",$Lq07_perman)."</td>
	     <td title='$Tq07_quant'>".str_replace(":","",$Lq07_quant)."</td>
	     <td title='$Tq11_tipcalc'>".str_replace(":","",$Lq11_tipcalc)."</td>
	     <td title='Alterar ou Excluir'><b>Opções</b></td>
	   </tr>
          "; 	   
       }elseif($numrows18>0){
       }else{
          echo "Nenhuma atividade cadastrada.";  
       } 	  
       $colo="";
       $ast='';
       for($i=0; $i<$numrows18; $i++){
         db_fieldsmemory($result18,$i);
	 if(isset($seqno) && $seqno==$q07_seq){
	   continue;
	 }
	 if($xq88_seq==$q07_seq){
	   $colo =" style='font-weight: bold';";
	   $ast="*";
	   $principal=$q07_seq;
           db_input('principal',40,10,true,'hidden',1);
	 }
         echo"
           <tr class='dados2' $colo>
	     <td title='$Tq07_inscr'>$ast$q07_inscr</td>
	     <td title='$Tq07_seq'>$q07_seq</td>
	     <td title='$Tq03_descr'>$q03_descr</td>
	     <td title='$Tq07_datain'>$q07_datain&nbsp;</td>
	     <td title='$Tq07_datafi'>$q07_datafi&nbsp;</td>
	     <td title='$Tq07_databx'>$q07_databx&nbsp;</td>
	     <td title='$Tq07_perman'>".($q07_perman=='t'?'Permantente':'Provisório')."</td>
	     <td title='$Tq07_quant'>$q07_quant&nbsp;</td>
	     <td title='$Tq11_tipcalc'>$q81_descr&nbsp;</td>";
	      if(isset($db_opcaoal)){
	        echo "<td  ><span class='x' >&nbsp;A&nbsp;</span>&nbsp;&nbsp;&nbsp;<span  class='x'>&nbsp;E&nbsp;</span></td>";
              }else{
  	        echo "<td><span id='a_".$q07_seq."_".$q07_inscr."' class='x' onclick='js_al(this);'><a title='ALTERAR ATIVIDADE' href='#'>&nbsp;A&nbsp;</a></span>&nbsp;&nbsp;&nbsp;<span id='e_".$q07_seq."_".$q07_inscr."' onclick='js_ex(this);' class='x'><a title='EXCLUIR ATIVIDADE' href='#'>&nbsp;E&nbsp;</a></span></td>";
              }  		
	    echo " </tr>";
	 $colo='';
	 $ast='';
       }
     }else{
        echo "Nenhuma atividade cadastrada.";  
     }
?>    </table>
    </td>
  </tr>  
  </table>
</html>  
</html>