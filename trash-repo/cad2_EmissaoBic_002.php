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
  //include("libs/db_sessoes.php");
  include("libs/db_usuariosonline.php");  
  include("classes/db_classecadastro.php");
 db_postmemory($HTTP_POST_VARS);

//$lista = $HTTP_POST_VARS["lista"];
//parse_str($HTTP_POST_VARS["session"]);


  $indice_L = 0;
  $indice_B = 0;
  $indice_S = 0;
  $indice_SQ = 0;
  $indice_I = 0;
  $indice_C = 0;

  for ($i=0;$i<sizeof($lista);$i++){
    $item = split("#",$lista[$i]);
	$primeiraLetra = $item[0];
    if ($primeiraLetra == "L"){
      $codigos_L[$indice_L] = $item[1];
      $indice_L++;
	}else if($primeiraLetra == "B"){
      $codigos_B[$indice_B] = $item[1];
      $indice_B++;
	}else if($primeiraLetra == "I"){
      $codigos_I[$indice_I] = $item[1];
      $indice_I++;
	}else if($primeiraLetra == "C"){
      $codigos_C[$indice_C] = $item[1];
      $indice_C++;
	}else if($primeiraLetra == "S"){
      $codigos_S[$indice_S] = $item[1];
      $indice_S++;
	}else if($primeiraLetra == "SQ"){
      $codigos_SQS[$indice_SQ] = $item[1];
      $codigos_SQQ[$indice_SQ] = $item[2];
      $indice_SQ++;
	}
  }




  $indice_paramtero = 0;
  $parametro[0] = "";
  if(isset($codigos_L)){
   
    for($i=0;$i<sizeof($codigos_L);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
      $sql = $clsqlamatriculas->sqlmatriculas_ruas($codigos_L[$i]);
	  //$sql .= " limit 5";
	  //echo " $sql";
      $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }
  if(isset($codigos_B)){
    for($i=0;$i<sizeof($codigos_B);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
      $sql = $clsqlamatriculas->sqlmatriculas_bairros($codigos_B[$i]);
	  $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }
  if(isset($codigos_I)){
    for($i=0;$i<sizeof($codigos_I);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
      $sql = $clsqlamatriculas->sqlmatriculas_imobiliaria($codigos_I[$i]);
	  $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }
  if(isset($codigos_C)){
    for($i=0;$i<sizeof($codigos_C);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
      $sql = $clsqlamatriculas->sqlmatriculas_nome($codigos_C[$i]);
	  $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }
  if(isset($codigos_S)){
    for($i=0;$i<sizeof($codigos_S);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
      $sql = $clsqlamatriculas->sqlmatriculas_setor($codigos_S[$i]);
	  $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }
  if(isset($codigos_SQS)){
    for($i=0;$i<sizeof($codigos_SQS);$i++){
      $clsqlamatriculas = new cl_sqlmatriculas;
	  $sql = $clsqlamatriculas->sqlmatriculas_setorQuadra($codigos_SQS[$i],$codigos_SQQ[$i]);
	  $result = pg_exec($sql);
	  for ($num=0;$num<pg_numrows($result);$num++){
        if (in_array(pg_result($result,$num,"j01_matric"),$parametro) == false){
		  $parametro[$indice_paramtero] = pg_result($result,$num,"j01_matric");
		  $indice_paramtero++;
		}
	  }
	}
  }

if ($parametro[0]!=""){
  sort($parametro);
  $mat = "";
  for ($t=0;$t<sizeof($parametro);$t++){
    $mat .= $parametro[$t]."_";
  }
 // echo "parametro  =$mat "; 
  if ($origem == "Emitir BICS"){
    
 //print_r($parametro);
    echo "<script> 
    var par = '".$mat."';
    jan =window.open('cad3_conscadastro_impressao.php?par='+par,'width=500,height=500,scrollbars=1,resisable=1'); 
    </script>";
    
	// include("cad3_conscadastro_impressao.php");
	 
						 /*
						 include("fpdf151/pdf.php");
					include("libs/db_libtributario.php");
					db_postmemory($HTTP_SERVER_VARS);
					
					
					$pdf = new PDF();
					$pdf->Open();
					$pdf->AliasNbPages();
					//require("libs/db_conectapdf.php");
					db_emitebic($parametro,&$pdf, $tipo,$geracalculo);
					$pdf->Output();
					*/

  }else if($origem == "Emitir Relatorio"){
    echo "<script> 
    var par = '".$mat."';
    jan =window.open('cad3_conscadastro_relatorio.php?par='+par,'width=500,height=500,scrollbars=1,resisable=1'); 
    </script>";
	 //include("cad3_conscadastro_relatorio.php");
  }
 }else {
   $db_erro = "Nenhuma matr�cula foi encontrada na sua pesquisa.";
   include("db_erros.php");
 }

 ?>