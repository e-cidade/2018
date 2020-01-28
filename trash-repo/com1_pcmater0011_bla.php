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
include("classes/db_pcsubgrupo_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_empautitem_classe.php");

$clpcsubgrupo = new cl_pcsubgrupo;
$clempautitem = new cl_empautitem;
$auxiliar     = new cl_pcsubgrupo;
$clorcparametro = new cl_orcparametro;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//---
$anousu= db_getsession("DB_anousu");
$r=$clorcparametro->sql_record($clorcparametro->sql_query($anousu,"o50_subelem",null,"" ));
if ($clorcparametro->numrows >0){
  db_fieldsmemory($r,0);
}  

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
  $clrotulo->label("o56_codele");
  $clrotulo->label("o56_elemento");
  $clrotulo->label("o56_descr");
  // parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
  // se usa subelemento ...
  // $o50_subelem='t';
  $arr = split("XX",$codele);
  if  (isset($o50_subelem) && ($o50_subelem=="t")){
      $result = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query_orcelement(null,"o56_codele, o56_descr, o56_elemento ","o56_elemento","pc04_codsubgrupo = $codsubgrupo"));
      $numrows = $clpcsubgrupo->numrows;
      echo "<table border=\"0\">\n";
      echo "<tr bgcolor=\"#6699cc\"><th>&nbsp;</th><th>$RLo56_codele</th><th>$RLo56_elemento</th><th>$RLo56_descr</th></tr>\n";
      for($i = 0;$i < $numrows;$i++) {
         db_fieldsmemory($result,$i);
	 $elemento = substr($o56_elemento,0,7);
         $sql=" select distinct(c60_codcon) as o56_codele, 
	                           c60_estrut as o56_elemento, 
		                       c60_descr as o56_descr
 	        from   conplano
  	               inner join conplanoreduz on c61_codcon= c60_codcon and c61_anousu=c60_anousu
	        where c60_estrut like '$elemento%' 
		order by c60_estrut";    
         $res=$auxiliar->sql_record($sql);
	 if ($auxiliar->numrows>0)
            for ($ix=0;$ix < $auxiliar->numrows ;$ix++){  
               db_fieldsmemory($res,$ix);	    
	       $result_trancaele = $clempautitem->sql_record($clempautitem->sql_query_autoridot(null,null,"e55_item,e55_codele","e55_item","e55_item=$codigomater and e55_codele=$o56_codele and e54_anulad is null"));
	       $trancaele = false;
	       if($clempautitem->numrows>0){
		 $trancaele = true;
	       }
	       if($trancaele==false){
		 $nada = "";
                 if($i%2==0){
		   $cor1 = "#aacccc";
		 }else{
		   $cor1 = "#ccddcc";
		 }
	       }else{
		 $nada = "<strong>***</strong> ";
		 $cor1 = "#CCFF99";
	       }
               echo "<tr bgcolor=\"$cor1\">
                     <td>
 		       <input type=\"checkbox\" name=\"o56_codele\" value=\"$o56_codele\"  ".((isset($db_opcao) && $db_opcao == 3) || $trancaele==true?" disabled ":"")."";
                        for($u=0; $u<count($arr); $u++){
			  $elem = $arr[$u];
		          if($elem  == $o56_codele){
			    echo " checked ";
			    break;
			  }
                        }      
			echo ">
		     </td>
    	             <td>$o56_codele    $nada</td>	    
	             <td>$o56_elemento</td>	    
                     <td>$o56_descr</td>
                     </tr>\n";   
             }		  
       };//end for 	     
    
  } else {

      $result = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query_orcelement(null,"o56_descr,o56_elemento,o56_codele","o56_elemento","pc04_codsubgrupo = $codsubgrupo"));
      $numrows = $clpcsubgrupo->numrows;
      echo "<table border=\"0\">\n";
      echo "<tr bgcolor=\"#6699cc\"><th>&nbsp;</th><th>$RLo56_codele</th><th>$RLo56_elemento</th><th>$RLo56_descr</th></tr>\n";
      for($i = 0;$i < $numrows;$i++) {
         db_fieldsmemory($result,$i);
         $elemento = substr($o56_elemento,0,7);
	 // echo $elemento;
         $sql=" select distinct(c60_codcon) as o56_codele, c60_estrut as o56_elemento, c60_descr as o56_descr
 	        from conplano
  	           inner join conplanoreduz on c61_codcon= c60_codcon
	        where c60_estrut like '$elemento%' 
		order by c60_estrut";    
         $res=$auxiliar->sql_record($sql);
	 if ($auxiliar->numrows>0)
            for ($ix=0;$ix < $auxiliar->numrows ;$ix++){  
               db_fieldsmemory($res,$ix);	    
	       $result_trancaele = $clempautitem->sql_record($clempautitem->sql_query_autoridot(null,null,"e55_item,e55_codele","e55_item","e55_item=$codigomater and e55_codele=$o56_codele and e54_anulad is null"));
	       $trancaele = false;
	       if($clempautitem->numrows>0){
		 $trancaele = true;
	       }
	       if($trancaele==false){
		 $nada = "";
                 if($i%2==0){
		   $cor1 = "#aacccc";
		 }else{
		   $cor1 = "#ccddcc";
		 }
	       }else{
		 $nada = "<strong>***</strong> ";
		 $cor1 = "#CCFF99";
	       }
              //       <td><input type=\"checkbox\" name=\"o56_codele\" value=\"$o56_codele\" onclick=\"parent.document.form1.pc01_codele.value = this.value\"   ".(isset($codele) && $codele == $o56_codele?" checked ":"")." ".(isset($db_opcao) && $db_opcao == 3?" disabled ":"")."></td>
               echo "<tr bgcolor=\"$cor1\">
                     <td>
 		       <input type=\"checkbox\" name=\"o56_codele\" value=\"$o56_codele\"  ".((isset($db_opcao) && $db_opcao == 3) || $trancaele==true?" disabled ":"")."";
                        for($u=0; $u<count($arr); $u++){
			  $elem = $arr[$u];
		          if($elem  == $o56_codele){
			    echo " checked ";
			    break;
			  }
                        }      
			echo ">
		     </td>
			
    	             <td>$o56_codele    $nada</td>	    
	             <td>$o56_elemento</td>	    
                     <td>$o56_descr</td>
                     </tr>\n";   
             }		  
       };//end for 	     
  }
  echo "</table>\n";
?>
</form>
</center>
</body>
</html>