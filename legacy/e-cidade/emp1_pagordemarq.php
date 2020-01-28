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


  $clpagordem->e50_numemp     = $e50_numemp;
  $clpagordem->e50_data       = date("Y-m-d",db_getsession("DB_datausu"));
  $clpagordem->e50_obs        = $e50_obs;
  $clpagordem->e50_id_usuario = db_getsession("DB_id_usuario");
  $clpagordem->e50_hora       = db_hora();
  $clpagordem->e50_anousu     = db_getsession("DB_anousu");
  $clpagordem->incluir(null);
  if($clpagordem->erro_status==0){
    $sqlerro=true;
    $erro_msg = $clpagordem->erro_msg;
  }else{
    $e50_codord =  $clpagordem->e50_codord ;
    $ok_msg = $clpagordem->erro_msg;
  }  
  
  if($sqlerro==false){
    $arr_dados = split("#",$dados);
    $tam = count($arr_dados);
    for($i=0; $i<$tam; $i++){
       $arr_ele = split("-",$arr_dados[$i]);
       $elemento     =  $arr_ele[0]; 
	   if(isset($chaves) && $chaves!=''){
	     $vlrord       =  '0.00'; 
	     $vlrord_nota  =  $arr_ele[1];
	   }else{
	     $vlrord       =  $arr_ele[1];
	     $vlrord_nota  =  '0.00';
	   }  
       //==================================================
       //rotina que pega os valores de pagordemele
	   $result02  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"e60_numemp,sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e50_numemp and e53_codele=$elemento group by e60_numemp")); 
	   if($clpagordemele->numrows>0){
		 db_fieldsmemory($result02,0);	
	   }else{ 
		 $tot_vlrpag  = '0.00';
		 $tot_vlranu = '0.00';
		 $tot_valor  = '0.00';
       }	
       //==============================================

	   //=================
	   //dados do empelemento
       $result09 = $clempelemento->sql_record($clempelemento->sql_query_file($e50_numemp,$elemento,"sum(e64_vlrliq) as total_vlrliq ,sum(e64_vlrpag) as total_vlrpag,sum(e64_vlranu) as total_vlranu"));
       db_fieldsmemory($result09,0);
       //=============
       //rotina que traz os dados do empnotaele
       $sql = $clempnotaele->sql_query_ordem(null,null,"e71_codnota,e70_codele,e71_anulado,sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota","","e69_numemp=$e50_numemp and e70_codele=$elemento and e70_vlrliq <> 0 and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') ) group  by e70_codele,e71_anulado,e71_codnota "); 
	   $result65 = $clempnotaele->sql_record($sql);
	   if($clempnotaele->numrows>0){
	     db_fieldsmemory($result65,0);  	
	   }else{
	     $tot_valor_nota = '0.00';
	     $tot_vlrliq_nota = '0.00';
	     $tot_vlranu_nota = '0.00';
	     $e71_codnota='';
	   }  	 
	   //==================================================================================
       //valor disponivel com notas
       $saldo_nota = ($tot_valor_nota-$tot_vlranu_nota) ;

       //valor disponivel sem notas
	   if($e71_codnota==''){
	     $jadenota = '0';
	   }else{
	     $jadenota = $saldo_nota;
	   }
       $vlrdis = number_format( ( ($total_vlrliq-$total_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag) - $jadenota ),"2",".","");
 
       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%5 
	   //testa valores com notas..
       if($vlrord_nota>$saldo_nota){
	     $sqlerro=true;
	     $erro_msg = " Valor da nota  $vlrord_nota do elemento $elemento não está disponivel. Verifique!";
	     //die();
	     break;
	   } 

       //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	   //	verifica os valores sem notas 
       if(trim($vlrord)+1 > ($vlrdis)+1){
	     $sqlerro=true;
	     $erro_msg = " Valor $vlrord do elemento $elemento não está disponivel. Verifique!";
	     break;
	   }  
       //=================== 

       if($sqlerro==false){
	     $valor = number_format($vlrord_nota + $vlrord,"2",".","");
	     if($valor=='' || $valor==0){
	       $valor='0';
	     }   
	     $clpagordemele->e53_codord  = $e50_codord;
	     $clpagordemele->e53_codele  = $arr_ele[0];//$arr[0] contem o elemento
	     $clpagordemele->e53_valor  = $valor;
	     $clpagordemele->e53_vlranu = '0.00' ;
	     $clpagordemele->e53_vlrpag = '0.00' ;
	     $clpagordemele->incluir($e50_codord,$arr_ele[0]);
	     $erro_msg=$clpagordemele->erro_msg;
	     if($clpagordemele->erro_status==0){
		   $sqlerro=true;
		   break;
	     }
       }  
     } 	  

     //rotina pega as notas marcadas para atualizar os valores liquidados da notas
     if($sqlerro==false && isset($chaves) && $chaves!=''){
	   $arr_notas = split("#",$chaves);
       $tam = count($arr_notas);
	   for($i=0; $i<$tam; $i++){
	     $nota = $arr_notas[$i];
                
		 $clpagordemnota->sql_record($clpagordemnota->sql_query_file(null,null,"e71_codord",'',"e71_anulado='f' and e71_codord=$e50_codord and e71_codnota=$nota"));
         if($clpagordemnota->numrows>0){
		   $erro_msg = "Nota $nota já incluida!";
           $sqlerro=true;
		 } 
	     if($sqlerro==false){ 
		  $clpagordemnota->e71_codord  = $e50_codord;
		  $clpagordemnota->e71_codnota = $nota;
		  $clpagordemnota->e71_anulado = "false";
		  $clpagordemnota->incluir($e50_codord,$nota);
		  $erro_msg=$clpagordemnota->erro_msg;
		  if($clpagordemnota->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
		}  
	  }
    }	
  }
?>