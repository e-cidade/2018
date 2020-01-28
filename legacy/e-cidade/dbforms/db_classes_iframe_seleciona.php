<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
$clrotulo = new rotulocampo;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(file_exists(base64_decode($arquivo))){
  include(base64_decode($arquivo));
}else{
  echo "

     <script>
         parent.document.form1.submit();
     </script>
  ";
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

<script>
function js_marca(obj){

   var oColInput = document.getElementsByTagName('input');

   for( i=0; i < oColInput.length; i++ ) {

		 oInput = oColInput[i];

     if( oInput.type == 'checkbox' && oInput.disabled == false ) {

      if( oInput.checked ){
        oInput.checked = false;
      }else {
        oInput.checked = true;
      }
     }
   }
<?
    if(isset($js_marcador)){
       echo str_replace(";","",$js_marcador).";";
    }
?>
   return false;
}
</script>
<script>

//alert(document.getElementById('eu').innerHTML);
</script>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <form name="form1" method="post">
  <center>
<?
if(isset($sql) && $sql!=""){
  $sql=base64_decode($sql);
  $campos=base64_decode($campos);
  $msg_vazio=base64_decode($msg_vazio);
}

if(!isset($mostra_totalizador)||trim($mostra_totalizador)==""||$mostra_totalizador==null){
    $mostra_totalizador = "N";
}

if(!isset($posicao_totalizador)||trim($posicao_totalizador)==""||$posicao_totalizador==null){
    $posicao_totalizador = "A";
}
?>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
<?
if($mostra_totalizador=="S"){
    if($posicao_totalizador=="A"){
        if(isset($sql) && $sql!=""){
            $result  = db_query($sql);
            $numrows = pg_numrows($result);
            if($numrows > 0){
	              $matriz_campos = split(",",$campos);
                $numcolunas    = sizeof($matriz_campos);
?>
           <tr>
              <td nowrap align="left" colspan="<?=$numcolunas++?>"><b>Total de registros:&nbsp;&nbsp;<?=$numrows?></b></td>
           </tr>
<?
            }
        }
    }
}
?>
  <tr>
    <td  valign="top">
      <table border='1' width="100%" bgcolor="#cccccc" id="tabela_seleciona">
<?
if(isset($sql_disabled) && $sql_disabled!=""){
  $sql_disabled=base64_decode($sql_disabled);
  $result03=db_query($sql_disabled);
  $numrows03=pg_num_rows($result03);
}
if(isset($sql_marca) && $sql_marca!=""){
  $sql_marca=base64_decode($sql_marca);
  $result02=db_query($sql_marca);
  $numrows02=pg_num_rows($result02);
}


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
           /*funcao a ser executada apos clicar no marcador*/
 	  $js='';

          if($db_opcao!=3 && $marcador==true){
            echo "     <td align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>";
          }else{
            echo "     <td align='center'>&nbsp</td>";
          }
	  for($w=0; $w<$numcolunas; $w++){
	    $campo=str_replace(" ","",$matriz_campos[$w]);
      $clrotulo->label($campo);
	    $Tlabel="T$campo";
	    $Llabel="L$campo";

	    if(substr($campo,0,3) == "db_"){
	      $nomcampo = ucfirst(substr($campo,3));
	      $$Tlabel   = ucfirst(substr($campo,3));
	    }else{
	      $nomcampo = $$Llabel;
	    }
	      echo " <td class='cabec' ".($corponowrap=="true"?"nowrap":"")." title='".$$Tlabel."'>".str_replace(":","",$nomcampo)."</td>\n";
	  }
          echo "   </tr>";
          $cabec=true;
       }elseif(!$numrows>0){
          echo $msg_vazio;
       }


       //rotina para marcar os campos
       if(isset($cabec) && $cabec==true){
         $matriz02=split(",",$chaves);
         for($i=0; $i<$numrows; $i++){
           db_fieldsmemory($result,$i,true);
           $checa="";
           if(isset($sql_marca) && $sql_marca!=""){
             for($s=0;$s<$numrows02;$s++){
               for($w=0; $w<sizeof($matriz02); $w++){
                 $campo=pg_result($result02,$s,$matriz02[$w]);
	//	 echo "<br>".$matriz02[$w]."--".$campo."--".$$matriz02[$w].">>>>>>>>>>>>>>";
                 if($campo==$$matriz02[$w]){
                   $checa=" checked ";
                   $s=$numrows02;
		   break;
                 }else{
                   $checa="";
  		   break;
                 }
               }
             }
	     //  echo "---finalcampo--<br>";
  	   }
	   //-----------final
           if(isset($ckd)){
	       $checa=" checked ";
	   }

	   //rotina para desabilitar os campos
           $pode="";
	   $cr='';
           if(isset($sql_disabled) && $sql_disabled!=""){
             for($s=0;$s<$numrows03;$s++){
               for($w=0; $w<sizeof($matriz02); $w++){
                 $campo=pg_result($result03,$s,$matriz02[$w]);
                 if($campo==$$matriz02[$w]){
                   $pode = " disabled   ";
	           $cr= " style=\"background-color:#DEB887\"";
                   $s=$numrows03;
		   break;
                 }else{
                   $pode="";
  		   break;
                 }
               }
             }
	     //  echo "---finalcampo--<br>";
  	   }
           //------------
	   if($db_opcao==33 || $db_opcao==22){
                   $pode = " disabled   ";
	   }

           $li=$i+1;
           echo "   <tr id='linha_$li' >";
           if($db_opcao!=3){
	     $matris=split(",",$chaves);
	     $valor='';
	     $esp='';
	     for($t=0; $t<count($matris); $t++){
	       $rr=$matris[$t];
	       $valor.=$esp.$$rr;
	       $esp='_';
	     }

	     if(isset($dbscript)){
               echo "<td $cr align='left'><input id='CHECK_$li' name='CHECK_$li' type='checkbox' value='$valor' $checa $dbscript $pode></td>";
	     }else{
               echo "<td $cr align='left'><input id='CHECK_$li' name='CHECK_$li' type='checkbox' value='$valor'  $checa $pode></td>";
	     }
           }else{
             echo "<td $cr align='left'><input disabled id='CHECK_$li' name='CHECK_$li' type='checkbox' $checa $pode ></td>";
           }
	   for($w=0; $w<$numcolunas; $w++){
 	     $campo=strtolower(trim($matriz_campos[$w]));
	     @$Tlabel="T$campo";
  	     $Llabel="L$campo";

	    if(substr($campo,0,3) == "db_"){
	      $nomcampo = ucfirst(substr($campo,3));
	    }else{
	      $nomcampo = $$Llabel;
	    }




	     if($$campo=="t"){
                $$campo="Sim";
	     }else if($$campo=="f"){
                $$campo="Não";
	     }

	     echo "   <td $cr id='".$campo."_".$li."' title='".@$$Tlabel."' ".($corponowrap=="true"?"nowrap":"")." class='corpo'>".stripslashes($$campo)."&nbsp;</td>";
	    if(isset($input_hidden) && $input_hidden==true){
             echo "   <input id='in_".$campo."_".$li."' name='in_".$campo."_".$li."' type='hidden'  value='".stripslashes($$campo)."'>";
	    }
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
<?
if($mostra_totalizador=="S"){
    if($posicao_totalizador=="B"){
        if(isset($sql) && $sql!=""){
            $result  = db_query($sql);
            $numrows = pg_numrows($result);
            if($numrows > 0){
	              $matriz_campos = split(",",$campos);
                $numcolunas    = sizeof($matriz_campos);
?>
           <tr>
              <td nowrap align="left" colspan="<?=$numcolunas++?>"><b>Total de registros:&nbsp;&nbsp;<?=$numrows?></b></td>
           </tr>
<?
           }
        }
    }
}
?>
  </table>
  </center>
  </form>
</body>
</html>
<?
 $retorno = @unlink(base64_decode($arquivo));
 if($retorno==false){
   echo "<blink>Carregando...</blink>";
 }
?>