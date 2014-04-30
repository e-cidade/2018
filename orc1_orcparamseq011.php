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
include("classes/db_orcparamelemento_classe.php");

$clorcparamelemento = new cl_orcparamelemento;
$auxiliar = new cl_orcparamelemento;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//--- traz todos os elementos
$sql = "select c60_estrut,c60_codcon,c52_descrred,c60_descr
        from conplano 
	    inner join consistema on c52_codsis = conplano.c60_codsis
	where c60_anousu = ".db_getsession("DB_anousu")."
	order by c60_estrut
        ";
if (isset($rel) && $rel!="" && isset($seq) && $seq!=""){
$sql = "select distinct c60_codcon, c60_estrut,c52_descrred,c60_descr,o44_codele
        from conplano 
	      inner join consistema on c52_codsis = conplano.c60_codsis	    
	      left outer join orcparamelemento on o44_codparrel = $rel 
	                                      and o44_sequencia = $seq
	                                      and o44_anousu = c60_anousu
	                                   	  and o44_instit =".db_getsession("DB_instit")  ." 		
					                      and o44_codele = conplano.c60_codcon
        where c60_anousu=".db_getsession("DB_anousu")."
	    order by c60_estrut
        ";
} 

// caso essa tela fique em branco na alteração,
// verificar se a tabela orcparamelemento possui todos os campos

$result=$clorcparamelemento->sql_record($sql);
//db_criatabela($result);

//--
 
?>
<html>
<head>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body>
<Center>
<form name="form1" method="post" action="">
<?
  $clrotulo = new rotulocampo;
  $clrotulo->label("c60_estrut");
  $clrotulo->label("c60_codcon");
  $clrotulo->label("c52_descrred");
  $clrotulo->label("c60_descr");

  $numrows = $clorcparamelemento->numrows;
     
  echo "<table border=\"1\">\n";
  echo "<div id=dd style=\"position:absolute;top:0px\"> <tr bgcolor=\"#6699cc\"><th>$RLc60_estrut</th><th>Tipo</td><th>Sistema</th><th>Recurso</th><th>$RLc60_descr</th></tr></div>\n";
  for($i = 0;$i < $numrows;$i++) {
    db_fieldsmemory($result,$i);
	   
    $espaco ="";	  
    $estrutural =$c60_estrut;
    if(substr($estrutural,1,14)     == '00000000000000'){
     	$espaco="";
    }elseif(substr($estrutural,2,13)== '0000000000000'){
   	    $espaco="&nbsp;&nbsp;";
    }elseif(substr($estrutural,3,12)== '000000000000'){
   	    $espaco="&nbsp;&nbsp;&nbsp;&nbsp;";
    }elseif(substr($estrutural,4,11) == '00000000000'){
   	    $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
    }elseif(substr($estrutural,5,10) == '0000000000'){
    	$espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }elseif(substr($estrutural,7,8)  == '00000000'){
    	$espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
    }elseif(substr($estrutural,9,6)  == '000000'){
    	$espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
    }elseif(substr($estrutural,11,4) == '0000'){
 	    $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
   }
   // ve se é analitica
   $rres = $auxiliar->sql_record(
           "select c61_reduz,c61_codigo as recurso " .
           "from conplanoreduz " .
           "where c61_codcon=$c60_codcon
              and c61_anousu =".db_getsession("DB_anousu")."           
              and c61_instit = ".db_getsession("DB_instit"));
   if ($auxiliar->numrows > 0 ){
   	   db_fieldsmemory($rres,0);
       $tipo ="A";
       $cor= "#CFFFFFF";
   }else{
       $tipo ="S";
       $cor="red";
       $recurso="";
   }   
      echo "<tr>
          <td nowrap bgcolor='$cor' > 
	    <input type=\"checkbox\" name=\"chaves\" value=\"$c60_codcon\" ";
	    if (isset($o44_codele) && $o44_codele !="")
	        echo " checked ";
            echo ">$espaco $c60_estrut</td>";
	    echo "<td>$tipo</td>";  
            if ($c52_descrred =='P'){
	      echo "<td><font color=red>$c52_descrred </font></td>";
	    } else {
              echo "<td>$c52_descrred </td>";
	    }  
	 echo "<td>".@$recurso."</td>";  
     echo " <td>$c60_descr </td>
	  </tr> ";
    
  }
  echo "</table>\n";
?>
</form>
</center>
</body>
</html>