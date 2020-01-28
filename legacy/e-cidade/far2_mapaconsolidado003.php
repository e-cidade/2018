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

//MODULO: educação
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_stdlibwebseller.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_modelolivro_classe.php");
include("classes/db_far_fechalivro_classe.php");
include("classes/db_far_farmacia_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_modelolivro = new cl_far_modelolivro;
$clfar_fechalivro = new cl_far_fechalivro;
$clfar_farmacia = new cl_far_farmacia;
$clfar_fechalivro->rotulo->label();
$fa26_i_login   = DB_getsession("DB_id_usuario");
$ano=date('Y');


  $colname_v = "-1";
if (isset($_POST['lv'])) {
  $colname_v = (get_magic_quotes_gpc()) ? $_POST['lv'] : addslashes($_POST['lv']);
}  
$sql = "SELECT * FROM far_modelolivro WHERE fa16_i_codigo = '$lv' ";
$qr = pg_query($sql) ;



if(pg_num_rows($qr) == 0){
   echo  '<option value="0">Não</option>';
   
}else{
while($ln = pg_fetch_assoc($qr)){
    if($ln['fa16_i_periodo']==1){ //dia
      for($x=1;$x<=31;$x++){
       echo '<option value="1D">'.$x.'</option>';
      }
     }elseif($ln['fa16_i_periodo']==2){ //semana
        $v=date('W');
       for($b=1;$b<=$v;$b++){
         echo '<option value="2">'.$b.'</option>';
       }
     }elseif($ln['fa16_i_periodo']==3){//quinzenal
       $x[1]="Primeira";$x[2]="Segunda";
       for($b=1;$b<=2;$b++){
         echo '<option value="3Q">'.$x[$b].'</option>';
      }
     }elseif($ln['fa16_i_periodo']==4){ //mensal
     for($x=1;$x<=12;$x++){
       echo '<option value="mes">'.db_mes($x).'</option>';
      }
     }elseif($ln['fa16_i_periodo']==5){ //bimestre
      /*$x[1]="Primeiro";$x[2]="Segundo";$x[3]="Terceiro";$x[4]="Quarto";
      $x[5]="Quinto"; $x[6]="Sexto"; 
     for($b=1;$b<=6;$b++){            
       echo '<option value="'.$ln['fa16_i_codigo'].'">'.$x[$b].'</option>';
      }  */

       $x=data_farmacia($ano,'1B');    
       $y=data_farmacia($ano,'2B');     
       $t=data_farmacia($ano,'3B');
       $q=data_farmacia($ano,'4B');    
       $e=data_farmacia($ano,'5B');
       $w=data_farmacia($ano,'6B');
       echo '<option value="1B">'.db_formatar($x[0],'d').'&nbsp;ate&nbsp;'.db_formatar($x[1],'d').'</option>';
       echo '<option value="2B">'.db_formatar($y[0],'d').'&nbsp;ate&nbsp;'.db_formatar($y[1],'d').'</option>';    
       echo '<option value="3B">'.db_formatar($t[0],'d').'&nbsp;ate&nbsp;'.db_formatar($t[1],'d').'</option>';
       echo '<option value="4B">'.db_formatar($q[0],'d').'&nbsp;ate&nbsp;'.db_formatar($q[1],'d').'</option>';
       echo '<option value="5B">'.db_formatar($e[0],'d').'&nbsp;ate&nbsp;'.db_formatar($e[1],'d').'</option>';
       echo '<option value="6B">'.db_formatar($w[0],'d').'&nbsp;ate&nbsp;'.db_formatar($w[1],'d').'</option>';
     }elseif($ln['fa16_i_periodo']==6){ ///trimestre
      /*$x[1]="Primeiro";$x[2]="Segundo";$x[3]="Terceiro";$x[4]="Quarto";
     for($b=1;$b<=4;$b++){
       echo '<option value="'.$ln['fa16_i_codigo'].'">'.$x[$b].'</option>';
      }*/
       $x=data_farmacia($ano,'1T');    
       $y=data_farmacia($ano,'2T');     
       $t=data_farmacia($ano,'3T');
       $q=data_farmacia($ano,'4T');    
       echo '<option value="1T">'.db_formatar($x[0],'d').'&nbsp;ate&nbsp;'.db_formatar($x[1],'d').'</option>';
       echo '<option value="2T">'.db_formatar($y[0],'d').'&nbsp;ate&nbsp;'.db_formatar($y[1],'d').'</option>';    
       echo '<option value="3T">'.db_formatar($t[0],'d').'&nbsp;ate&nbsp;'.db_formatar($t[1],'d').'</option>';
       echo '<option value="4T">'.db_formatar($q[0],'d').'&nbsp;ate&nbsp;'.db_formatar($q[1],'d').'</option>';
     }elseif($ln['fa16_i_periodo']==7){ //quadrimestre
     //$x[1]="Primeiro";$x[2]="Segundo";$x[3]="Terceiro";
     //for($b=1;$b<=3;$b++){
       //echo '<option value="'.$ln['fa16_i_codigo'].'">'.$x[$b].'</option>';
      //}  
       $x=data_farmacia($ano,'1Q');    
       $y=data_farmacia($ano,'2Q');     
       $t=data_farmacia($ano,'3Q');    
       echo '<option value="1Q">'.db_formatar($x[0],'d').'&nbsp;ate&nbsp;'.db_formatar($x[1],'d').'</option>';
       echo '<option value="2Q">'.db_formatar($y[0],'d').'&nbsp;ate&nbsp;'.db_formatar($y[1],'d').'</option>';    
       echo '<option value="3Q">'.db_formatar($t[0],'d').'&nbsp;ate&nbsp;'.db_formatar($t[1],'d').'</option>';
     }elseif($ln['fa16_i_periodo']==8){ //smestral 
       $x=data_farmacia($ano,'1S');    
       $y=data_farmacia($ano,'2S');     
       echo '<option value="1S">'.db_formatar($x[0],'d').'&nbsp;ate&nbsp;'.db_formatar($x[1],'d').'</option>';
       echo '<option value="2S">'.db_formatar($y[0],'d').'&nbsp;ate&nbsp;'.db_formatar($y[1],'d').'</option>';
       
       }elseif($ln['fa16_i_periodo']==9){  //anual
        $x=$ano;
       echo '<option value="1">'.$x.'</option>';
      
     }
    
      //echo '<option value="'.$ln['fa16_i_codigo'].'">'.$ln['fa16_i_periodo'].'</option>';
   }
}
 
?>