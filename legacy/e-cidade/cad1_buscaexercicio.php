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
include("dbforms/db_funcoes.php");

//echo "calc= $forma  j01_matric= $j01_matric exec = $exec";
//if(isset($exec)){
  
//  $sqlperc = "select j18_perccorrepadrao from cfiptu where j18_anousu=$exec ";

  $sqlperc = "select j18_perccorrepadrao from cfiptu where j18_anousu = ".db_getsession('DB_anousu');
  $resultperc = pg_query($sqlperc);
  $linhasperc = pg_num_rows($resultperc);
  
  if($linhasperc>0){
    
    db_fieldsmemory($resultperc,0);
    echo "<script>   
            parent.document.form1.percentual.value = $j18_perccorrepadrao;            
       //     parent.db_iframe_exerc.hide();       
          </script> ";
//    exit;

  }
      
//}

if(!isset($exec)){
  
$sql = " select distinct 
                j23_anousu 
           from iptucalc 
          where j23_matric = $j01_matric 
            and j23_anousu <> ".db_getsession('DB_anousu')." 
          order by j23_anousu desc ";
          
$result = pg_query($sql);
$linhas = pg_num_rows($result);
if($linhas>0){
  $xx  = "";  
  $anos= "";
  
  for($i=0;$i<$linhas;$i++){
    
    db_fieldsmemory($result,$i);
    $anos .= $xx.$j23_anousu;
    $xx= "X"; 
 
/*  if($i==0 && isset($exec) ){
      $sqlperc = "select j18_perccorrepadrao from cfiptu where j18_anousu = ".db_getsession('DB_anousu');
      $resultperc = pg_query($sqlperc);
      $linhasperc = pg_num_rows($resultperc);
      if($linhasperc>0){
        db_fieldsmemory($resultperc,0);
        echo "<script>
                parent.document.form1.percentual.value = $j18_perccorrepadrao;
              </script> ";
      }     
    } */
   
  }
  
//  echo "ano = $anos";
  echo "<script>
          parent.js_addSelectFromStr('$anos');
     //     parent.db_iframe_exerc.hide();
        </script> ";
}
}
//exit;
//parent.db_iframe_exerc.hide();

?>