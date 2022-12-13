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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//echo "area =$area <br> sani =$codsani <br>opcao= $opcao<br>seq=$seq<br>";
$sql = "select * from sanitario where y80_codsani=$codsani";
$result = pg_query($sql);
$linhas = pg_num_rows($result);
if($linhas>0){
  db_fieldsmemory($result,0);
  if($opcao==1){
     $sqlinc= "select * from saniatividade where y83_codsani = $codsani";
     $opcao='Incluir';
  }else{
     $sqlinc= "select * from saniatividade where y83_codsani = $codsani and y83_seq <> $seq";
     $opcao='Alterar';     
  }
  $resultinc = pg_query($sqlinc);
  $linhasinc = pg_num_rows($resultinc);
  if($linhasinc>0){
    $areat="";
    for($i=0;$i<$linhasinc;$i++){
      db_fieldsmemory($resultinc,$i);
      $areat += $y83_area;
    }
    $areaatual = $areat + $area;
    if($areaatual>$y80_area){
      db_msgbox("A soma das áreas das atividades não pode exceder o valor total da área liberada no sanitário." );
      echo "<script> parent.document.form1.y83_area.value=''; </script>";
    }else{
      echo"<script> parent.js_submet('".$opcao."');</script>";
    }
  }else{
      if($area>$y80_area){
        db_msgbox("A soma das áreas das atividades não pode exceder o valor total da área liberada no sanitário." );
        echo "<script> parent.document.form1.y83_area.value=''; </script>";
      }else{
        echo"<script> parent.js_submet('".$opcao."');</script>";

      }
  }
  
}
?>