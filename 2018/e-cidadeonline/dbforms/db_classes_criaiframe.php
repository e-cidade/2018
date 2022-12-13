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
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
$sql=base64_decode($sql);
$campos=base64_decode($campos);
$msg_vazio=base64_decode($msg_vazio);
$quais_chaves = split("#",$quais_chaves);
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
       font-size: 10px;
       color: <?=$textocabec?>;
       background-color:<?=$fundocabec?>;       
       border-color: darkblue;
       }
.corpo {
       text-align: center;
       font-size: 9px;
       color: <?=$textocorpo?>;
       background-color:<?=$fundocorpo?>;       
       }

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_retorna(qtipo,<? $virgula = "";
  reset($quais_chaves);
  for($ww=0;$ww<sizeof($quais_chaves);$ww++){
    echo $virgula."par_$ww";
    $virgula = ",";
    next($quais_chaves);
  }
  ?>){
  var opcao = parent.document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","opcao");
  opcao.setAttribute("value",qtipo);
  parent.document.form1.appendChild(opcao);
 
  <?
  reset($quais_chaves);
  for($ww=0;$ww<sizeof($quais_chaves);$ww++){
    ?>
    var chavepri = parent.document.createElement("input");
    chavepri.setAttribute("type","hidden");
    chavepri.setAttribute("name","<?=$quais_chaves[$ww]?>");
    chavepri.setAttribute("value",par_<?=$ww?>);
    parent.document.form1.appendChild(chavepri);
    <?
    next($quais_chaves);
  }
  ?>	
  parent.document.form1.submit();
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post">
  <table border="0" width="750" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
  <tr>
    <td align="center" valign="top">
      <table border='1' width="100%" bgcolor="#cccccc">
<?
       $result=db_query($sql);
        $numrows=pg_numrows($result);
       $numcols=pg_numfields($result);
       if($db_opcao=="Incluir"){
          $db_opcao=1;
       }else if($db_opcao=="Alterar"){
          $db_opcao=2;
       }else if($db_opcao=="Excluir"){ 
          $db_opcao=3;
       }
       if((($db_opcao==33  || $db_opcao==1) && $numrows>0) || (($db_opcao==33 || $db_opcao==3 || $db_opcao==2) && $numrows>1)){ 
	  $matriz_campos=split(",",$campos); 
          $numcolunas=sizeof($matriz_campos);
          echo "   <tr class='cabec'>";
	  for($w=0; $w<$numcolunas; $w++){
	    $campo=str_replace(" ","",$matriz_campos[$w]);
            $clrotulo->label($campo);
	    $Tlabel="T$campo";
	    $Llabel="L$campo";
	    echo "   <td class='cabec' ".($cabecnowrap=="true"?"nowrap":"")." title='".$$Tlabel."'>".str_replace(":","",$$Llabel)."</td>\n";
	  }  
          echo  "    <td class='cabec' title='Alterar ou Excluir'><b>Opções</b></td>";
          echo "   </tr>"; 	   
          $cabec=true;
       }elseif(!$numrows>0){
          echo $msg_vazio;

       } 	 
       if(isset($cabec) && $cabec==true){
         for($i=0; $i<$numrows; $i++){
           db_fieldsmemory($result,$i,true);
           echo "   <tr>";
	   $naomostra = false;
	   for($w=0; $w<$numcolunas; $w++){
	     $campo = trim(pg_fieldname($result,$w));
	     for($ww=1;$ww<sizeof($quais_chaves);$ww++){
	       $valorchave = "x_".$quais_chaves[$ww];
	       $nomechave = $quais_chaves[$ww];
	       $valorchave = $$valorchave;
	       if($valorchave!=null && $valorchave!=""){
		 if($valorchave == $$campo && $nomechave==$campo && ($db_opcao==2 || $db_opcao==22 || $db_opcao==3 || $db_opcao==33)){
		   $naomostra = true;
		 }
	       }
	     }
	   }
	   if($naomostra==true){
	     continue;
	   }
	   for($w=0; $w<$numcolunas; $w++){
 	     $campo=strtolower(trim($matriz_campos[$w]));
	     echo "   <td ".($corponowrap=="true"?"nowrap":"")." class='corpo'>".$$campo."&nbsp;</td>";
	     if($w+1==$numcolunas){
	       if($db_opcao==33){
  	         echo "<td class='corpo'><span >&nbsp;A&nbsp;</span>&nbsp;&nbsp;&nbsp;<span class='x'>&nbsp;E&nbsp;</span></td>\n";
	       }else{
    	         $coluna = "<td class='corpo'>";
       	         $coluna.= "<a title='ALTERAR CONTEÚDO DA LINHA' href='#' onclick='js_retorna(\"alterar\"";
	         $virgula = ",";
	         for($ww=0;$ww<sizeof($quais_chaves);$ww++){
	           $coluna .= $virgula."\"".$$quais_chaves[$ww]."\"";
                 }
	         $coluna.= ");return false;'>&nbsp;A&nbsp;</a>\n";
	         $coluna.="&nbsp;&nbsp;&nbsp;"; 
     	         $coluna.="<a title='EXCLUIR CONTEÚDO DA LINHA' href='#' onclick='js_retorna(\"excluir\"";
	         $virgula = ",";
	         for($ww=0;$ww<sizeof($quais_chaves);$ww++){
	           $coluna .= $virgula."\"".$$quais_chaves[$ww]."\"";
	         }
	         $coluna .= ");return false;'>&nbsp;E&nbsp;</a>";
	         echo $coluna."\n";
	       }  
	     }
	   } 
           echo "   </tr>";
         }	       
       }
?>    </table>
    </td>
  </tr>  
  </table>
</body>  
</html>