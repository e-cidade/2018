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
include("/tmp/par_cria_tabela.php");
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($sql)){
$sql=base64_decode($sql);
}
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style>
.cabec {
       text-align: center;
       font-size: <?=$tamfontecabec?>;
       color: <?=$textocabec?>;
       background-color:<?=$fundocabec?>;       
       border-color: darkblue;
       }
td{
       font-size: <?=$tamfontecorpo?>;
       text-align: center;
       color: <?=$textocorpo?>;
       background-color:<?=$fundocorpo?>;       
       }

</style>
</head>
<body bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="parent.x();">
<center>
<form name="form1" method="post" action="">		   
  <table  border="1" cellpadding="3" cellspacing="0" id="tab">
    <tr bgcolor="#BDC6BD"> 
    <?
      $colunas= split("#",$quais_colunas);
      for($i=0; $i<sizeof($colunas); $i++){
        $coluna="x_".$colunas[$i];
        echo "<th class='cabec' width=\"\" id='w' align=\"\" nowrap ><small>".str_replace(":","",$$coluna)."</small></th>";
      } 	
        echo "<th class='cabec'  title='Alterar ou Excluir'><b><small>Opções</small></b></td>";
      
    ?>
    </tr>
    <?
     if(isset($sql) && $sql!=""){ 
       $coluna="";
       $virgula="";
       $colunas= split("#",$quais_colunas);
       $totcol=sizeof($colunas);
       for($i=0; $i<$totcol; $i++){
         $coluna.=$virgula.$colunas[$i];
         $virgula=",";
       } 
       $result90= db_query($sql);
       $numrows90= @pg_numrows($result90);  
         if($numrows90!=false && $numrows90>0){
            for($i=0; $i<$numrows90; $i++){
              db_fieldsmemory($result90,$i,true);
              $coluna="";
              $virgula="";  
              echo "<tr id='id_$i'>";
              for($s=0; $s<$totcol; $s++){
                $nomcol=$colunas[$s]; 
                echo "<td id='idcol_$s'>".($GLOBALS[$nomcol]==""?"&nbsp;":"")."".$GLOBALS[$nomcol]."</td>";
              }
              if(empty($db_opcao) || $db_opcao==1 || $db_opcao==2){  
              echo "<td>
                       <a title='ALTERAR CONTEÚDO DA LINHA' href='' onclick=\"parent.js_alterarlinhas($i);return false;\">&nbsp;A&nbsp;</a>
                       <a title='EXCLUIR CONTEÚDO DA LINHA' href='' onclick=\"parent.js_excluirlinhas($i);return false;\">&nbsp;E&nbsp;</a> 
                    </td>"; 
              }else{
              echo "<td>
                       <a title='ALTERAR CONTEÚDO DA LINHA' href='' onclick=\"return false;\">&nbsp;A&nbsp;</a>
                       <a title='EXCLUIR CONTEÚDO DA LINHA' href='' onclick=\"return false;\">&nbsp;E&nbsp;</a> 
                    </td>"; 
              }
              echo "</tr>";
            }
            echo "<input name='conta_linha' value='$i' type='hidden' >";
          } 
        }
       
    ?> 
  </table>
  </form>
</center>			
</body>
</html>