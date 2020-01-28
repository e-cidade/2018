<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//echo ($HTTP_SERVER_VARS['QUERY_STRING']);exit;
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_empnota_classe.php");
include("classes/db_empnotaord_classe.php");
include("classes/db_empnotaele_classe.php");
include("classes/db_db_usuarios_classe.php");
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_matordemitement_classe.php");
include("classes/db_matestoque_classe.php");
include("classes/db_matestoqueitem_classe.php");
include("classes/db_matestoqueitemnota_classe.php");
include("classes/db_matestoqueitemoc_classe.php");
include("classes/db_matmater_classe.php");
include("classes/db_matmaterunisai_classe.php");
include("classes/db_transmater_classe.php");
include("classes/db_matestoqueini_classe.php");
include("classes/db_matestoqueinimei_classe.php");
include("classes/db_matestoqueitemunid_classe.php");
include("classes/db_matparam_classe.php");
$clmatparam = new cl_matparam;
$clusuarios = new cl_db_usuarios;
$clempnota = new cl_empnota; 
$clempnotaord = new cl_empnotaord;
$clempnotaele = new cl_empnotaele;
$clmatordemitem = new cl_matordemitem;
$clmatordemitement = new cl_matordemitement;
$clmatordem = new cl_matordem;
$clmatestoque = new cl_matestoque;
$clmatestoqueitem = new cl_matestoqueitem;
$clmatestoqueitemnota = new cl_matestoqueitemnota;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatmater = new cl_matmater;
$clmatmaterunisai = new cl_matmaterunisai;
$cltransmater = new cl_transmater;
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueinimei = new cl_matestoqueinimei;
$clmatestoqueitemunid = new cl_matestoqueitemunid;
$clempnota->rotulo->label();
$clempnotaele->rotulo->label();
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

/////// verifica se a ordem de compra já foi anulada
$result_or = $clmatordem->sql_record($clmatordem->sql_query_numemp($chave_m51_codordem,"m51_codordem, m53_data ","m51_codordem"," m53_codordem is not null and m51_codordem = $m51_codordem "));

if($clmatordem->numrows > 0){
  //echo "<br> clmatordem->numrows --> ".$clmatordem->numrows;
    echo "<script>alert('Ordem de Compra: ".$m51_codordem." Já Anulada!');</script>";
      echo "<script>location.href='mat1_entraordcom001.php';</script>";
}
/////////////


$valornota=0;
$valor_notaele=0;
if (isset($confirma)){
  db_inicio_transacao();
  
  $result_depto = $clmatordem->sql_record($clmatordem->sql_query_file(null,"m51_depto",null,"m51_codordem=$m51_codordem"));
  $e69_dtrecebe = "$e69_dtrecebe_ano-$e69_dtrecebe_mes-$e69_dtrecebe_dia";

  $sqlerro      = false;
  $erro_msg     = "";
  $arr_eles     = array();
  $valornota    = array();
  $numempenho   = array();
  $cdnota       = array();

  $clmatestoqueini->m80_data     = date('Y-m-d',db_getsession("DB_datausu"));
  $clmatestoqueini->m80_hora     = date('H:i:s');
  $clmatestoqueini->m80_coddepto = $m51_depto;
  $clmatestoqueini->m80_login    = db_getsession("DB_id_usuario");
  $clmatestoqueini->m80_codtipo  = '12';
  $clmatestoqueini->m80_obs      = $m80_obs;
//	echo "inserindo matestoqueini<br>";
  $clmatestoqueini->incluir(null);
  if ($clmatestoqueini->erro_status==0){
    $sqlerro=true;
    $erro_msg=$clmatestoqueini->erro_msg;
    //db_msgbox('1');
  }else{
    $codini=$clmatestoqueini->m80_codigo;
  }
  $erro_msg = $clmatestoqueini->erro_msg;

  $dados    = split("quant_","$valores");
  $cods     = split("coditem_","$codmatmater");
  $vlitem   = split("valor","$val");
  $qmult    = split("qntmul_",$valmul);
  $unidad   = split("codunid_","$codunidade");

  for ($i=1; $i<count($dados); $i++){
    if ($sqlerro==false){
      $quamul          = split("_",$qmult[$i]);
      $quant_mult      = db_formatar($quamul[1], 'p');
      $numero          = split("_",$dados[$i]);
      $codigosmat      = split("_",$cods[$i]);
      $codmatmater     = $codigosmat[1];
      $codele          = $numero[0];
      $numemp          = $numero[1];
      $codmatordemitem = $numero[2];
      $quanti          = db_formatar($numero[4], 'p');
      $valitem         = split("_",$vlitem[$i]);
      $valorquant      = $valitem[2];
      $valor_item      = "";

      for($x=0; $x < strlen($valorquant); $x++){
        if(is_numeric($valorquant[$x]) || $valorquant[$x]==","){
          $valor_item .= $valorquant[$x];
        }
      }
      
			//echo("xxx: $valorquant<br>");
      //$valorquant = $valor_item;

      if ($quanti != 0){

        //if (strpos(trim($valorquant),',')!=""){
        //  $valorquant=str_replace('.','',$valorquant);
        //  $valorquant=str_replace(',','.',$valorquant);
        //}
        $valor_notaele+=$valorquant;
        if(empty($numempenho[$numemp])){
          $numempenho[$numemp] = 0;
        }
        $numempenho[$numemp]+=$numempenho[$numemp];
        if(empty($valornota[$codele."_".$numemp])){
          $valornota[$codele."_".$numemp] = 0;
        }
        $valornota[$codele."_".$numemp]+=$valorquant;
        if(empty($arr_eles[$codele."_".$numemp])){
          $arr_eles[$codele."_".$numemp] = 0;
        }
        
        $arr_eles[$codele."_".$numemp] =  $valornota[$codele."_".$numemp];
        $result_itensentra=$clmatordemitement->sql_record($clmatordemitement->sql_query(null,"*",null,"m54_codmatordemitem=$codmatordemitem"));
        for($xy=0;$xy<$clmatordemitement->numrows;$xy++){
          db_fieldsmemory($result_itensentra,$xy);
          $valorquant=$m54_valor_unitario*$m54_quantidade;
          $quanti=$m54_quantidade;
          //if (strpos(trim($valorquant),',')!=""){
          //  $valorquant=str_replace('.','',$valorquant);
          //  $valorquant=str_replace(',','.',$valorquant);
          //}
          $valor_notaele+=$valorquant;
          if(empty($numempenho[$numemp])){
            $numempenho[$numemp] = 0;
          }
          $numempenho[$numemp]+=$numempenho[$numemp];
          if(empty($valornota[$codele."_".$numemp])){
            $valornota[$codele."_".$numemp] = 0;
          }
          $valornota[$codele."_".$numemp]+=$valorquant;
          if(empty($arr_eles[$codele."_".$numemp])){
            $arr_eles[$codele."_".$numemp] = 0;
          }
          $arr_eles[$codele."_".$numemp] =  $valornota[$codele."_".$numemp];
        }
      }else{
        $result_itensentra=$clmatordemitement->sql_record($clmatordemitement->sql_query(null,"*",null,"m54_codmatordemitem=$codmatordemitem"));
        for($xy=0;$xy<$clmatordemitement->numrows;$xy++){
          db_fieldsmemory($result_itensentra,$xy);
          $valorquant=$m54_valor_unitario*$m54_quantidade;
          $quanti=$m54_quantidade;
          //if (strpos(trim($valorquant),',')!=""){
          //  $valorquant=str_replace('.','',$valorquant);
          //  $valorquant=str_replace(',','.',$valorquant);
          //}
          $valor_notaele+=$valorquant;
          if(empty($numempenho[$numemp])){
            $numempenho[$numemp] = 0;
          }
          $numempenho[$numemp]+=$numempenho[$numemp];
          if(empty($valornota[$codele."_".$numemp])){
            $valornota[$codele."_".$numemp] = 0;
          }
          $valornota[$codele."_".$numemp]+=$valorquant;
          if(empty($arr_eles[$codele."_".$numemp])){
            $arr_eles[$codele."_".$numemp] = 0;
          }
          $arr_eles[$codele."_".$numemp] =  $valornota[$codele."_".$numemp];
        }
      }
    }
  }
  $datahj = date("Y-m-d",db_getsession("DB_datausu"));
  reset($numempenho);
	
  $resultado = $clmatparam->sql_record($clmatparam->sql_query_file(null,"m90_liqentoc"));
  if ($clmatparam->numrows > 0){
    db_fieldsmemory($resultado,0); 
  }

  if ($m90_liqentoc=="f"){
    $gravanota = "true";
  }

	if ($gravanota == "true") {

		for ($x=0;$x<sizeof($numempenho);$x++){
			$num_emp=key($numempenho);
			if ($sqlerro==false){
				$clempnota->e69_numero     = $e69_numero; 
				$clempnota->e69_numemp     = $num_emp ; 
				$clempnota->e69_id_usuario = $e69_id_usuario; 
				$clempnota->e69_dtnota     = $datahj ; 
				$clempnota->e69_dtrecebe   = $e69_dtrecebe ;
				if (isset($e69_codnota) && $e69_codnota!=""){
					$clempnota->incluir($e69_codnota);
				}	else {
					$clempnota->incluir("");
				} 
				$erro_msg = $clempnota->erro_msg;
				if ($clempnota->erro_status==0){
					//  	db_msgbox('2');
					$sqlerro=true;
					break;
				} 
			}

			if ($sqlerro==false){
				$codnota=$clempnota->e69_codnota;
				if(empty($cdnota[$numemp])){
					$cdnota[$numemp] = 0;
				}
				$cdnota[$numemp]=$codnota;
				$clempnotaord->incluir($codnota,$m51_codordem);
				if ($clempnotaord->erro_status==0){
					$erro_msg = $clempnotaord->erro_msg;
					//  db_msgbox('3');
					$sqlerro=true;
					break;
				}
			} 
			reset($arr_eles);
			for($y=0;$y<sizeof($arr_eles);$y++){ 
				$codele_numemp = key($arr_eles);
				$arr_emp       = split("_",$codele_numemp);
				$codelem       = $arr_emp[0];
				$numemp1       = $arr_emp[1];
				if ($numemp1==$num_emp){
					if ($sqlerro==false){
						$clempnotaele->e70_valor  = $valornota[$codelem."_".$numemp1]; 
						$clempnotaele->e70_vlranu = "0"; 
						$clempnotaele->e70_vlrliq = "0"; 
						$clempnotaele->incluir($codnota,$codelem);
						if ($clempnotaele->erro_status==0){
							$sqlerro=true;
							$erro_msg = $clempnotaele->erro_msg;
							//    db_msgbox('4');
						}
					}
				}
				next($arr_eles);
			}
			next($numempenho);
		}

	}

  for ($i=1; $i<sizeof($dados); $i++){
    if ($sqlerro==false){
      $numero          = split("_",$dados[$i]);
      $codigosmat      = split("_",$cods[$i]);
      $codmatmater     = $codigosmat[1];
      $codele          = $numero[0];
      $numemp          = $numero[1];
      $codmatordemitem = $numero[2];
      $quanti          = $numero[4];

			//echo "reg: $i - quanti: $quanti - " . $vlitem[$i] . "<br>";
      $valitem    = split("_",$vlitem[$i]);
      $valorquant = $valitem[2];
      $quamul     = split("_",$qmult[$i]);
      $quant_mult = db_formatar($quamul[1], 'p');
      $quanti_ant = db_formatar($quanti,'p');
      $quanti     = $quanti*$quant_mult;
      $unid       = split("_",$unidad[$i]);
      $codi_unid  = $unid[1];
      $tam        = strlen($codi_unid);
      $tam        = $tam-1;
      $codi_unid  = substr($codi_unid,0,$tam);
      
      $valor_item = "";
      for($x=0; $x < strlen($valorquant); $x++){
        if(is_numeric($valorquant[$x])||$valorquant[$x]==","){
          $valor_item .= $valorquant[$x];
        }
      }

      //$valorquant = $valor_item;
      
      db_fieldsmemory($result_depto,0);
      if ($quanti!=0) { // significa que o usuario deixou um item com valores preenchidos...

				if (gettype(strpos($valorquant, "val")) == "integer") {
					$sqlerro  = true;
					$erro_msg = "Lançamentos parciais inconsistentes! Verifique!";
					break;
				}
				
        if ($codmatmater=="" and $sqlerro == false){
          $result_newmater=$clmatordemitem->sql_record(
          $clmatordemitem->sql_query( $codmatordemitem, "pc01_codmater,pc01_descrmater,pc01_complmater") );
          db_fieldsmemory($result_newmater,0);
          if ($sqlerro==false){   
            $result_resum=$clmatordemitem->sql_record( $clmatordemitem->sql_query_servico($codmatordemitem,"e62_descr") );
            if($clmatordemitem->numrows>0){
              db_fieldsmemory($result_resum,0);
            }
            //$e62_descr=addslashes($e62_descr);
            //$pc01_descrmater=addslashes($pc01_descrmater);
            $e62_descr       = str_replace(chr(10), " ", $e62_descr);
            $pc01_descrmater = str_replace(chr(10), " ",$pc01_descrmater);
            $descr_newmater  = $pc01_descrmater." ".@$e62_descr;
            $descr_newmater  = addslashes($descr_newmater);
            $descr_newmater  = str_replace(chr(10), " ", $descr_newmater);
            $clmatmater->m60_ativo = 't';
            $clmatmater->m60_descr = substr($descr_newmater,0,80);
            $clmatmater->m60_codmatunid =1; 
            $clmatmater->m60_quantent = 1;
            $clmatmater->m60_codant = "";
            $clmatmater->incluir(null);
            if ($clmatmater->erro_status==0){
              $erro_msg=$clmatmater->erro_msg;
              $sqlerro=true;
              //db_msgbox('5');
            }
            $codmatmater=$clmatmater->m60_codmater;
          }
          if ($sqlerro==false){    
            $clmatmaterunisai->incluir($codmatmater,1);
            if ($clmatmaterunisai->erro_status==0){
              $erro_msg=$clmatmaterunisai->erro_msg;
              $sqlerro=true;
              //db_msgbox('6');
            }
          }
          if ($sqlerro==false){    
            $cltransmater->m63_codpcmater=$pc01_codmater;
            $cltransmater->m63_codmatmater=$codmatmater;
            $cltransmater->incluir();
            if ($cltransmater->erro_status==0){
              $erro_msg=$cltransmater->erro_msg;
              $sqlerro=true;
              //db_msgbox('7');
            }
          }
        }
        //if (strpos(trim($valorquant),',')!=""){
        //  $valorquant=str_replace('.','',$valorquant);
        //  $valorquant=str_replace(',','.',$valorquant);
        //}
        $valor_notaele      += $valorquant;
        $result              = $clmatestoque->sql_record($clmatestoque->sql_query_file("","*","","$m51_depto=m70_coddepto and $codmatmater=m70_codmatmater"));
        $matestoque_numrows  = $clmatestoque->numrows;
        if ($sqlerro==false){
          if ($matestoque_numrows==0){
            $clmatestoque->m70_codmatmater = $codmatmater;
            $clmatestoque->m70_coddepto    = $m51_depto;
            $clmatestoque->m70_quant       = "$quanti";
            //if (strpos(trim($valorquant),',')!=""){
            //  $valorquant=str_replace('.','',$valorquant);
            //  $valorquant=str_replace(',','.',$valorquant);
            //} 
            $clmatestoque->m70_valor       = "$valorquant";
            $clmatestoque->incluir(null);
						//echo "inserindo matestoque 1 - " . $clmatestoque->erro_status . "<br>";
            if ($clmatestoque->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clmatestoque->erro_msg;
              //db_msgbox('8');
              break;
            }
          }else{
            db_fieldsmemory($result,0); 
            //if (strpos(trim($valorquant),',')!=""){
            //  $valorquant=str_replace('.','',$valorquant);
            //  $valorquant=str_replace(',','.',$valorquant);
            //}
            $m70_quant = $m70_quant + $quanti;
            $m70_valor = $m70_valor + $valorquant;
            $clmatestoque->m70_codigo      = $m70_codigo;
            $clmatestoque->m70_codmatmater = $m70_codmatmater;
            $clmatestoque->m70_coddepto    = $m70_coddepto;
            $clmatestoque->m70_quant       = "$m70_quant" ;
            //if (strpos(trim($m70_valor),',')!=""){
            //  $m70_valor=str_replace('.','',$m70_valor);
            //  $m70_valor=str_replace(',','.',$m70_valor);
            //}
            $clmatestoque->m70_valor = db_formatar("$m70_valor",'p');
            $clmatestoque->alterar($m70_codigo); 
            if ($clmatestoque->erro_status==0){
              $sqlerro=true;
              //db_msgbox('9');
              $erro_msg=$clmatestoque->erro_msg;
              break;
            }
          }
        }
        $codestoque=$clmatestoque->m70_codigo;
//       db_msgbox("Codestoque: $codestoque");
//      die();
        if ($sqlerro==false){
          $clmatestoqueitem->m71_codmatestoque = $codestoque;
          $clmatestoqueitem->m71_data          = $e69_dtrecebe;
          $clmatestoqueitem->m71_quant         = "$quanti";
          $clmatestoqueitem->m71_quantatend    = '0';
          //if (strpos(trim($valorquant),',')!=""){
          //  $valorquant=str_replace('.','',$valorquant);
          //  $valorquant=str_replace(',','.',$valorquant);
          //}
          $clmatestoqueitem->m71_valor         = db_formatar("$valorquant",'p');
//					echo("xxx: " . $clmatestoqueitem->m71_valor . "<br>");
          $clmatestoqueitem->incluir(null);
	        //echo "inserindo matestoqueitem 1<br>";
          if ($clmatestoqueitem->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clmatestoqueitem->erro_msg;
            //db_msgbox('10');
          }
          $codigoestoitem = $clmatestoqueitem->m71_codlanc;
        }	

//        db_msgbox("Codestoqueitem: $codigoestoitem");
//        die();
				if ($sqlerro==false){
					if (isset($codigoestoitem)&&$codigoestoitem!=""){	
						$clmatestoqueinimei->m82_matestoqueini  = @$codini;
						$clmatestoqueinimei->m82_matestoqueitem = @$codigoestoitem;
						$clmatestoqueinimei->m82_quant          = "$quanti";
						$clmatestoqueinimei->incluir(null);
						//echo "inserindo matestoqueinimei<br>";
						if ($clmatestoqueinimei->erro_status==0){
							$sqlerro=true;
							$erro_msg=$clmatestoqueinimei->erro_msg;
							//db_msgbox('25');
						}
					}
				}
        if ($sqlerro==false){    
          $clmatestoqueitemunid->m75_codmatestoqueitem = $codigoestoitem;	
          $clmatestoqueitemunid->m75_codmatunid        = $codi_unid;	
          $clmatestoqueitemunid->m75_quant             = "$quanti_ant";	
          $clmatestoqueitemunid->m75_quantmult         = "$quant_mult";	
          $clmatestoqueitemunid->incluir($codigoestoitem);
          if ($clmatestoqueitemunid->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clmatestoqueitemunid->erro_msg;
            //db_msgbox('11');
          }
        }
//        db_msgbox("Codmatordemitem: $codmatordemitem");
        //die();
        if ($sqlerro==false){
          $clmatestoqueitemoc->incluir($codigoestoitem,$codmatordemitem);
          if ($clmatestoqueitemoc->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clmatestoqueitemoc->erro_msg;
            //db_msgbox('12');
          }
        }
        //db_msgbox("Codmatestoqueitemoc: ".$clmatestoqueitemoc->m73_codmatestoqueitem . "-".$clmatestoqueitemoc->m73_codmatordemitem);
        //die();
        if ($sqlerro==false){    
          if ($gravanota == "true"){ 
            $codigonota=$codnota;
            $clmatestoqueitemnota->incluir($codigoestoitem,$codigonota);
            if ($clmatestoqueitemnota->erro_status==0){
              $erro_msg=$clmatestoqueitemnota->erro_msg;
              $sqlerro=true;
              //db_msgbox('13');
            }
          }
        }

        $result_itensentra  = $clmatordemitement->sql_record($clmatordemitement->sql_query(null,"*",null,"m54_codmatordemitem=$codmatordemitem"));
        $matordemitement_numrows = $clmatordemitement->numrows;
        //db_msgbox( $matordemitement_numrows );
//        die();

        if ($matordemitement_numrows!=0){
          for($xy=0;$xy<$matordemitement_numrows;$xy++){
            db_fieldsmemory($result_itensentra,$xy);
            $codmatmater = $m54_codmatmater;
            $valorquant  = $m54_valor_unitario*$m54_quantidade;
            $quanti      = $m54_quantidade*$m54_quantmulti;
            //if (strpos(trim($valorquant),',')!=""){
            //  $valorquant=str_replace('.','',$valorquant);
            //  $valorquant=str_replace(',','.',$valorquant);
            //}
            $valor_notaele += $valorquant;
            $result         = $clmatestoque->sql_record($clmatestoque->sql_query_file("","*","","$m51_depto=m70_coddepto and $codmatmater=m70_codmatmater"));
            if ($clmatestoque->numrows==0){
              $clmatestoque->m70_codmatmater = $codmatmater;
              $clmatestoque->m70_coddepto    = $m51_depto;
              $clmatestoque->m70_quant       = "$quanti";
              //if (strpos(trim($valorquant),',')!=""){
              //  $valorquant=str_replace('.','',$valorquant);
              //  $valorquant=str_replace(',','.',$valorquant);
              //}
              $clmatestoque->m70_valor       = db_formatar("$valorquant",'p');
              $clmatestoque->incluir(null);
						  //echo "inserindo matestoque 2 - " . $clmatestoque->erro_status . "<br>";
              if ($clmatestoque->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clmatestoque->erro_msg;
                break;
                //db_msgbox('14');
              }
            }else{
              db_fieldsmemory($result,0); 
              $m70_quant = $m70_quant + $quanti;
              //if (strpos(trim($valorquant),',')!=""){
              //  $valorquant=str_replace('.','',$valorquant);
              //  $valorquant=str_replace(',','.',$valorquant);
              //}
              $m70_valor = $m70_valor + $valorquant;
              $clmatestoque->m70_codigo      = $m70_codigo;
              $clmatestoque->m70_codmatmater = $m70_codmatmater;
              $clmatestoque->m70_coddepto    = $m70_coddepto;
              $clmatestoque->m70_quant       = "$m70_quant" ;
              $clmatestoque->m70_valor       = "$m70_valor";
              $clmatestoque->alterar($m70_codigo); 
              if ($clmatestoque->erro_status==0){
                $sqlerro=true;
                //db_msgbox('15');
                $erro_msg=$clmatestoque->erro_msg;
                break;
              }
            }
            $codestoque=$clmatestoque->m70_codigo;
            if ($sqlerro==false){    
              $clmatestoqueitem->m71_codmatestoque = $codestoque;
              $clmatestoqueitem->m71_data          = $e69_dtrecebe;
              $clmatestoqueitem->m71_quant         = "$quanti";
              $clmatestoqueitem->m71_quantatend    = '0';
              //if (strpos(trim($valorquant),',')!=""){
              //  $valorquant=str_replace('.','',$valorquant);
              //  $valorquant=str_replace(',','.',$valorquant);
              //}
              $clmatestoqueitem->m71_valor         = "$valorquant";
//					    echo("yyy: " . $clmatestoqueitem->m71_valor . "<br>");
              $clmatestoqueitem->incluir(null);
							//echo "inserindo matestoqueitem 2<br>";
              if ($clmatestoqueitem->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clmatestoqueitem->erro_msg;
                //db_msgbox('16');
              }
              $codigoestoitem = $clmatestoqueitem->m71_codlanc;
            }
            if ($sqlerro==false){    
              $clmatestoqueitemunid->m75_codmatestoqueitem = $codigoestoitem;	
              $clmatestoqueitemunid->m75_codmatunid        = $m54_codmatunid;	
              $clmatestoqueitemunid->m75_quant             = "$m54_quantidade";	
              $clmatestoqueitemunid->m75_quantmult         = "$m54_quantmulti";	
              $clmatestoqueitemunid->incluir($codigoestoitem);
              if ($clmatestoqueitemunid->erro_status==0){
                $sqlerro=true;
                $erro_msg=$clmatestoqueitemunid->erro_msg;
                // db_msgbox('17');
              }
            }
            if ($sqlerro==false){    
              $clmatestoqueitemoc->incluir($codigoestoitem,$codmatordemitem);
              if ($clmatestoqueitemoc->erro_status==0){
                $erro_msg=$clmatestoqueitemoc->erro_msg;
                $sqlerro=true;
                //db_msgbox('18');
              }
            }
            if ($sqlerro==false){    
              if ($gravanota == "true"){
                $codigonota=$codnota;
                $clmatestoqueitemnota->incluir($codigoestoitem,$codigonota);
                if ($clmatestoqueitemnota->erro_status==0){
                  $sqlerro=true;
                  $erro_msg=$clmatestoqueitemnota->erro_msg;
                  //db_msgbox('19');
                }
              }
            }
						if ($sqlerro==false){
							if (isset($codigoestoitem)&&$codigoestoitem!=""){	
								$clmatestoqueinimei->m82_matestoqueini=@$codini;
								$clmatestoqueinimei->m82_matestoqueitem=@$codigoestoitem;
								$clmatestoqueinimei->m82_quant="$quanti";
								$clmatestoqueinimei->incluir(null);
								//echo "inserindo matestoqueinimei<br>";
								if ($clmatestoqueinimei->erro_status==0){
									$sqlerro=true;
									$erro_msg=$clmatestoqueinimei->erro_msg;
									//db_msgbox('25');
								}
							}
						}
          }
        }
      }else{
        $result_itensentra=$clmatordemitement->sql_record($clmatordemitement->sql_query(null,"*",null,"m54_codmatordemitem=$codmatordemitem"));
        $matordemitement_numrows=$clmatordemitement->numrows;
        for($xy=0; $xy<$matordemitement_numrows; $xy++){
          db_fieldsmemory($result_itensentra,$xy);
					//echo "ppppppppppppp $xy<br>";
          $codmatmater = $m54_codmatmater;
          $valorquant  = $m54_valor_unitario*$m54_quantidade;
          $quanti      = $m54_quantidade*$m54_quantmulti;
          //if (strpos(trim($valorquant),',')!=""){
          //  $valorquant=str_replace('.','',$valorquant);
          //  $valorquant=str_replace(',','.',$valorquant);
          //}
          $valor_notaele += $valorquant;
          $result         = $clmatestoque->sql_record($clmatestoque->sql_query_file("","*","","$m51_depto=m70_coddepto and $codmatmater=m70_codmatmater"));
          if ($clmatestoque->numrows==0){
            $clmatestoque->m70_codmatmater = $codmatmater;
            $clmatestoque->m70_coddepto    = $m51_depto;
            $clmatestoque->m70_quant       = "$quanti";
            $clmatestoque->m70_valor       = db_formatar("$valorquant",'p');
            $clmatestoque->incluir(null);
						//echo "inserindo matestoque 3 - " . $clmatestoque->erro_status . "<br>";
            if ($clmatestoque->erro_status==0){
              $erro_msg=$clmatestoque->erro_msg;
              $sqlerro=true;
              break;
              //db_msgbox('20');
            }
          }else{
            db_fieldsmemory($result,0); 
            $m70_quant = $m70_quant + $quanti;
            //if (strpos(trim($valorquant),',')!=""){
            //  $valorquant=str_replace('.','',$valorquant);
            //  $valorquant=str_replace(',','.',$valorquant);
            //}
            $m70_valor = $m70_valor + $valorquant;
            $clmatestoque->m70_codigo      = $m70_codigo;
            $clmatestoque->m70_codmatmater = $m70_codmatmater;
            $clmatestoque->m70_coddepto    = $m70_coddepto;
            $clmatestoque->m70_quant       = "$m70_quant" ;
            $clmatestoque->m70_valor       = "$m70_valor";
            $clmatestoque->alterar($m70_codigo); 
            if ($clmatestoque->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clmatestoque->erro_msg;
              //db_msgbox('21');
              break;
            }
          }
          $codestoque=$clmatestoque->m70_codigo;
          if ($sqlerro==false){    
            $clmatestoqueitem->m71_codmatestoque = $codestoque;
            $clmatestoqueitem->m71_data          = $e69_dtrecebe;
            $clmatestoqueitem->m71_quant         = "$quanti";
            $clmatestoqueitem->m71_quantatend    = '0';
            $clmatestoqueitem->m71_valor         = "$valorquant";
//					  echo("zzz: " . $clmatestoqueitem->m71_valor . "<br>");
            $clmatestoqueitem->incluir(null);
						//echo "inserindo matestoqueitem 3<br>";
            if ($clmatestoqueitem->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clmatestoqueitem->erro_msg;
              //db_msgbox('22');
            }
            $codigoestoitem = $clmatestoqueitem->m71_codlanc;
          }  
          if ($sqlerro==false){    
            $clmatestoqueitemunid->m75_codmatestoqueitem=$codigoestoitem;	
            $clmatestoqueitemunid->m75_codmatunid=$m54_codmatunid;	
            $clmatestoqueitemunid->m75_quant="$m54_quantidade";	
            $clmatestoqueitemunid->m75_quantmult="$m54_quantmulti";	
            $clmatestoqueitemunid->incluir($codigoestoitem);
            if ($clmatestoqueitemunid->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clmatestoqueitemunid->erro_msg;
              //db_msgbox('23');
            }
          }
          if ($sqlerro==false){    
            $clmatestoqueitemoc->incluir($codigoestoitem,$codmatordemitem);
            if ($clmatestoqueitemoc->erro_status==0){
              $sqlerro=true;
              $erro_msg=$clmatestoqueitemoc->erro_msg;
              //  db_msgbox('24');
            }
          }
          if ($sqlerro==false){    
            if ($gravanota == "true"){
              $codigonota=$codnota;
              $clmatestoqueitemnota->incluir($codigoestoitem,$codigonota);
              if ($clmatestoqueitemnota->erro_status==0){
                $erro_msg=$clmatestoqueitemnota->erro_msg;
                $sqlerro=true;
                //db_msgbox('24');
              }
            }
          }
					//echo "teste - $codigoestoitem<br>";
					if ($sqlerro==false){
						if (isset($codigoestoitem)&&$codigoestoitem!=""){	
							$clmatestoqueinimei->m82_matestoqueini=@$codini;
							$clmatestoqueinimei->m82_matestoqueitem=@$codigoestoitem;
							$clmatestoqueinimei->m82_quant="$quanti";
							$clmatestoqueinimei->incluir(null);
							//echo "inserindo matestoqueinimei<br>";
							if ($clmatestoqueinimei->erro_status==0){
								$sqlerro=true;
								$erro_msg=$clmatestoqueinimei->erro_msg;
								//db_msgbox('25');
							}
						}
					}
        }
      }
    }
  }

  //exit;

  if ($erro_msg==""){
    $sqlerro=true;
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr> 
<td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
<center>
<?
include("forms/db_frmentraordcom.php");
?>
</center>
</td>
</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<?
if (isset($confirma)){
  if (strlen($erro_msg)>0){
    db_msgbox($erro_msg);
  }

  if($clmatestoque->erro_campo!=""){
    echo "<script> document.form1.".$clempnota->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$clempnota->erro_campo.".focus();</script>";
  }else{ 
    if ($gravanota=="false"){
         $erro_msg = $clmatestoqueinimei->erro_msg;
         db_msgbox($erro_msg);
    }
    $sql="delete from matordemitement";
    $result_deleta=pg_exec($sql);
    echo"<script>top.corpo.location.href='mat1_entraordcom001.php';</script>";
  }
}
?>
</body>
</html>