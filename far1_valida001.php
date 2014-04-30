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
include("classes/db_far_controlemed_classe.php");
include("classes/db_far_controle_classe.php");
include("classes/db_far_retirada_classe.php");
include("classes/db_far_retiradaitens_classe.php");
include("classes/db_far_parametros_classe.php");
include("dbforms/db_funcoes.php");
$clfar_controlemed = new cl_far_controlemed;
$clfar_controle = new cl_far_controle;
$clfar_retirada = new cl_far_retirada;
$clfar_retiradaitens = new cl_far_retiradaitens;
$clfar_parametros = new cl_far_parametros;
$hoje= date("Y-m-d",db_getsession("DB_datausu"));

function subtrairmargem($fa10_d_dataini,$fa10_i_margem){
     $fa10_d_dataini  = explode("-",$fa10_d_dataini );
     $dia = (int)$fa10_d_dataini [2];
      $mes = (int)$fa10_d_dataini [1];
      $ano = (int)$fa10_d_dataini [0];
      $fa10_d_dataini = date ("Y-m-d",mktime (0,0,0,$mes,$dia-$fa10_i_margem,$ano));
       return $fa10_d_dataini ;
}
function somarDias($fa10_d_dataini ,$fa10_i_prazo){
     $fa10_d_dataini  = explode("-",$fa10_d_dataini);
     $dia = (int)$fa10_d_dataini [2];
      $mes = (int)$fa10_d_dataini [1];
      $ano = (int)$fa10_d_dataini [0];
      $fa10_d_dataini = date ("Y-m-d",mktime (0,0,0,$mes,$dia+$fa10_i_prazo,$ano));
       return $fa10_d_dataini ;
}

function db_datedif($pmktime=null,$smktime=null, $tipo='d'){
  if($tipo == 'd'){
    return pg_result(pg_query("select '$pmktime'::date - '$smktime'::date as d"),0,'d');
//    return ceil((( mktime(0,0,0,substr($pmktime,5,2),substr($pmktime,8,2),substr($pmktime,0,4)) -
  //                  mktime(0,0,0,substr($smktime,5,2),substr($smktime,8,2),substr($smktime,0,4)))/86400));
  }else if($tipo == 'm'){
    return 0;
  }else if($tipo == 'y'){
    return 0;
  }else{
    return ceil((( mktime(0,0,0,substr($pmktime,5,2),substr($pmktime,8,2),substr($pmktime,0,4)) -
                    mktime(0,0,0,substr($smktime,5,2),substr($smktime,8,2),substr($smktime,0,4)))/86400));
  }

}

$result9=$clfar_parametros->sql_record($clfar_parametros->sql_query("","*","","")); 
if($clfar_parametros->numrows>0){
   db_fieldsmemory($result9,0);
}

if($fa02_c_medcontrolado == 3 || $fa02_c_medcontrolado == 2){

$result=$clfar_controlemed->sql_record($clfar_controlemed->sql_query("","fa11_t_obs,fa10_i_quantidade,fa10_i_programa,fa10_i_prazo,fa10_d_dataini,fa10_i_margem,fa10_i_codigo,fa10_i_medicamento,fa11_i_cgsund","","fa10_d_dataini<= '$hoje' and fa11_i_cgsund=$fa04_i_cgsund and fa10_i_medicamento= $fa06_i_matersaude"));
if($clfar_controlemed->numrows>0){
   db_fieldsmemory($result,0);
   if($fa10_d_datafim == "" ){
      $fa10_d_datafim = $hoje;
   }
 $data_ini = $fa10_d_dataini;
 $dataini  = $fa10_d_dataini;
 $calculo_margem = 0;
// db_msgbox(" qtde_freq --> $qtde_freq fa10_d_dataini --> $fa10_d_dataini data_ --> $data_");
 if($hoje >= $fa10_d_dataini){
   $qtde_dias = db_datedif($hoje,$fa10_d_dataini);
   $qtde_freq = (int)($qtde_dias/$fa10_i_prazo);
   $resto_freq = $qtde_dias%$fa10_i_prazo;
   if($resto_freq == 0 && $qtde_freq > 0){
     $qtde_freq--;
   }
   if($qtde_freq > 0){
     $dataini = somarDias ($fa10_d_dataini,($qtde_freq * $fa10_i_prazo));
     if($dataini == $hoje){
       $fa10_d_dataini = $dataini;
     }else{  
       $fa10_d_dataini = somarDias ($fa10_d_dataini,(($qtde_freq * $fa10_i_prazo)+1));
     }
   }
 }
 $data_ = somarDias ($dataini,$fa10_i_prazo);
// db_msgbox(" 2 qtde_freq --> $qtde_freq fa10_d_dataini --> $fa10_d_dataini data_ --> $data_ dataini --> $dataini fa10_i_prazo -->$fa10_i_prazo");
 while($fa10_d_dataini <= $hoje){
//  db_msgbox(" 0 data_ -->$data_  datamargem_ant --> $datamargem_ant datamargem --> $datamargem fa10_d_dataini --> $fa10_d_dataini");
   if($fa10_i_margem > 0 ){
     $datamargem_ant = subtrairmargem ($fa10_d_dataini,($fa10_i_margem));
     $datamargem = subtrairmargem ($data_,($fa10_i_margem-1));
   }else{
     $datamargem_ant = $fa10_d_dataini;
     $datamargem = $data_;
   }
//  db_msgbox(" 1 data_ -->$data_  datamargem_ant --> $datamargem_ant datamargem --> $datamargem fa10_d_dataini --> $fa10_d_dataini");
//  db_msgbox("	 if( $hoje>=$fa10_d_dataini && $hoje<=$data_){	");
//  exit;
	 if( $hoje>=$fa10_d_dataini && $hoje<=$data_){	

	    $result2=$clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query("","sum(fa06_f_quant) as fa06_f_quant","","fa04_d_data between '$datamargem_ant' and '$data_' and fa04_i_cgsund=$fa11_i_cgsund and fa06_i_matersaude= $fa10_i_medicamento and trim(fa06_t_controlado)='$fa02_c_medcontrolado'"));
      if($clfar_retiradaitens->numrows>0){
        db_fieldsmemory($result2,0);
      }else{
        $fa06_f_quant=0;  
      }
      $calculo = $fa10_i_quantidade - ($fa06_f_quant + $calculo_margem) ;
      
//      db_msgbox("periodo --> $data_ e calculo -->  $calculo = $fa10_i_quantidade - ($fa06_f_quant + $calculo_margem) ; ");

//	    db_msgbox(" if($hoje>=$datamargem && $hoje<=$data_ ){	");
	    if($hoje>=$datamargem && $hoje<=$data_ && $fa10_i_margem > 0){	
         if($calculo<=0){
//        db_msgbox(" 1 fa10_d_dataini --> $fa10_d_dataini data_ --> $data_  datamargem --> $datamargem");  
            $fa10_d_dataini= somarDias( $data_, 1 );		
//         db_msgbox(" 2 fa10_d_dataini --> $fa10_d_dataini data_ --> $data_  datamargem --> $datamargem");  
           $data_ = somarDias ($data_,$fa10_i_prazo);
           $datamargem = subtrairmargem ($data_,($fa10_i_margem-1));
//         db_msgbox(" 3 fa10_d_dataini --> $fa10_d_dataini data_ --> $$data_  datamargem --> $datamargem");  
           if($calculo==0){
              $calculo = $fa10_i_quantidade;
              $fa06_f_quant = 0 ;
           }else{
              $calculo_margem = $calculo * -1;
	            $result2=$clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query("","sum(fa06_f_quant) as fa06_f_quant","","fa04_d_data between '$fa10_d_dataini' and '$data_' and fa04_i_cgsund=$fa11_i_cgsund and fa06_i_matersaude= $fa10_i_medicamento and trim(fa06_t_controlado)='$fa02_c_medcontrolado'"));
              if($clfar_retiradaitens->numrows>0){
                db_fieldsmemory($result2,0);
              }else{
                $fa06_f_quant=0;  
              }
              $calculo = $fa10_i_quantidade - ($fa06_f_quant + $calculo_margem) ;
//              db_msgbox(" 34 calculo --> $calculo = $fa10_i_quantidade - ($fa06_f_quant + $calculo_margem) ;");
              $fa06_f_quant = $calculo_margem ;
              if($calculo<=0){
	               if($fa02_c_medcontrolado == 3){
                    ?>
                      <script>
                        parent.document.form1.fa10_quantidade.disabled=true;
                        parent.document.form1.fa06_f_quant.readOnly=true;            
                        parent.document.form1.fa10_quantidade.value=0;
                      </script>
                    <?      
                 }
                 $calculo = 0;
                 db_msgbox("Podera retirar o medicamento a partir ".db_formatar($datamargem,"d")); 
              } //else
           }   
//          db_msgbox("1 data_ -->$data_  datamargem --> $datamargem");
         }
      }else{
         if($calculo<=0){
	          if($fa02_c_medcontrolado == 3){
            ?>
              <script>
                parent.document.form1.fa10_quantidade.disabled=true;
                parent.document.form1.fa06_f_quant.readOnly=true;            
                parent.document.form1.fa10_quantidade.value=0;
              </script>
            <?      
	          }
            $calculo = 0;
            if($fa10_i_margem > 0){
              db_msgbox("Podera retirar o medicamento a partir ".db_formatar($datamargem,"d")); 
            }else{  
              db_msgbox("Podera retirar o medicamento a partir ".db_formatar(somarDias( $data_, 1 ),"d")); 
            }
         } //else
      }
        ?>
        <script>                    
        parent.document.form1.datamargem1.value="<?=$fa10_d_dataini?>";
        </script>                    
        <?

     ?>
        <script>                    
        parent.document.form1.fa10_quantidade.value= <?=$calculo?>;
        parent.document.form1.data_1.value="<?=$data_?>";
        parent.document.form1.quant.value=<?=$fa10_i_quantidade?>;
        parent.document.form1.quantretirada.value="<?=$fa06_f_quant?>";
        parent.document.form1.fa06_f_quant.value= <?=$calculo?>
        </script>       
     <?   
  }else{
     if($fa10_i_margem > 0){
	      $result2=$clfar_retiradaitens->sql_record($clfar_retiradaitens->sql_query("","sum(fa06_f_quant) as fa06_f_quant","","fa04_d_data between '$fa10_d_dataini' and '$datamargem' and fa04_i_cgsund=$fa11_i_cgsund and fa06_i_matersaude= $fa10_i_medicamento and trim(fa06_t_controlado)=$fa02_c_medcontrolado"));
        if($clfar_retiradaitens->numrows>0){
          db_fieldsmemory($result2,0);
          $calculo_margem = $fa10_i_quantidade - ($fa06_f_quant+$calculo_margem);
//        db_msgbox(" 2 periodo --> $data_ calculo_margem --> $calculo_margem = $fa10_i_quantidade - $fa06_f_quant ");
          if($calculo_margem < 0){
             $calculo_margem = $calculo_margem * -1 ;
          }else{
             $calculo_margem = 0;
          }  
//        db_msgbox(" 2.1 periodo --> $data_ calculo_margem --> $calculo_margem ");
        } 
     }   
  }//if verificacao data com hoje	
  $fa10_d_dataini= somarDias( $data_, 1 );		
  $data_ = somarDias ($data_,$fa10_i_prazo);
 } //while primeiro
}

}
?>