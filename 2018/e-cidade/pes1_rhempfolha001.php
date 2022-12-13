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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhempfolha_classe.php");
include("classes/db_rhrubelementoprinc_classe.php");
include("classes/db_rhlotaexe_classe.php");
include("classes/db_rhlotavinc_classe.php");
include("classes/db_rhlotavincele_classe.php");
include("classes/db_rhlotavincativ_classe.php");
include("classes/db_rhlotavincrec_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
include("classes/db_db_config_classe.php");
include("libs/db_sql.php");
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clrhlotaexe = new cl_rhlotaexe;
$clrhempfolha = new cl_rhempfolha;
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavincele = new cl_rhlotavincele;
$clrhlotavincativ = new cl_rhlotavincativ;
$clrhlotavincrec = new cl_rhlotavincrec;
$clorcdotacao = new cl_orcdotacao;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$cldb_config = new cl_db_config;
$clgeradorsql = new cl_gera_sql_folha;
db_postmemory($HTTP_POST_VARS);

$passa = false;
if(isset($confirma) || isset($gera)){
	if (!isset($rh40_sequencia)||$rh40_sequencia==""){
		$rh40_sequencia = '0';
	}
  $ano = $DBtxt23;
  $mes = $DBtxt25;
  $sequencia = '';
  $rh40_tipo = 'n';
  if($ponto == 's'){
    $arquivo = 'gerfsal';
    $sigla   = 'r14_';
    $siglaarq= 'r14';
  }elseif($ponto == 'c'){
    $sequencia = " and r48_semest = $rh40_sequencia ";
    $arquivo = 'gerfcom';
    $sigla   = 'r48_';
    $siglaarq= 'r48';
  }elseif($ponto == 'a'){
    $arquivo = 'gerfadi';
    $sigla   = 'r22_';
    $siglaarq= 'r22';
  }elseif($ponto == 'r'){
    $arquivo = 'gerfres';
    $sigla   = 'r20_';
    $siglaarq= 'r20';
  }elseif($ponto == 'd'){
    $arquivo = 'gerfs13';
    $sigla   = 'r35_';
    $siglaarq= 'r35';
  }elseif($ponto == 'f'){
    $arquivo = 'gerffer';
    $sigla   = 'r31_';
    $siglaarq= 'r31';
  }

  $res_config = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit")));
  //echo "<BR><BR>".($cldb_config->sql_query_file(db_getsession("DB_instit")));
  if($cldb_config->numrows > 0){
  	db_fieldsmemory($res_config, 0);
  }

  if(!isset($confirma)){
	  $result_confirma = $clrhempfolha->sql_record($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,null,"*","","rh40_anousu=$ano and rh40_mesusu=$mes and rh40_sequencia = $rh40_sequencia and rh40_siglaarq='$siglaarq' and rh40_instit = ".db_getsession("DB_instit")));
	  // echo "<BR><BR>".($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,null,"*","","rh40_anousu=$ano and rh40_mesusu=$mes and rh40_tipo='$rh40_tipo' and rh40_siglaarq='$siglaarq'"));
	  if($clrhempfolha->numrows>0){
	    $passa = true;
	  }else{
	    $confirma = "confirma";
	  }
  }
  
  $sqlerro = false;
  if(isset($confirma)){
    db_inicio_transacao();
    $clrhempfolha->excluir(null,null,null,null,null,null,null,null,null,null,"rh40_anousu=$ano and rh40_mesusu=$mes and rh40_sequencia=$rh40_sequencia and rh40_siglaarq='$siglaarq' and rh40_instit = ".db_getsession("DB_instit"));
    $erro_msg = $clrhempfolha->erro_msg;
    if($clrhempfolha->erro_status==0){
      $sqlerro=true;
    }
    if($sqlerro==false){
			$clgeradorsql->inicio_rh= false;
			$clgeradorsql->usar_pes = true;
			$clgeradorsql->usar_doc = true;
		  $clgeradorsql->usar_cgm = true;
		  $clgeradorsql->usar_atv = true;
		  $clgeradorsql->usar_rel = true;

	    $sql = $clgeradorsql->gerador_sql(
	                                            $siglaarq,
	                                            $ano,
	                                            $mes,
	                                            "",
	                                            "",
	                                            $sigla."rubric as rubric,".
				  	                                  $sigla."regist as regist,
				 	                                    rh30_vinculo as vinculo,
				                                      rh02_tbprev as previdencia,".
					                                    $sigla."pd as pd,".
					                                    $sigla."quant as quant,
					                                    rh02_lota as lotacao,".
					                                    $sigla."valor as valor,".
					                                    $sigla."anousu as anousu,".
					                                    $sigla."mesusu as mesusu,
					                                    rh23_codele as elemento",
					                                    $sigla."regist",$sigla."pd != 3 and rh23_codele is not null $sequencia ",db_getsession("DB_instit")
	                                     );
      //die($sql);

      $ano_exercicio = $ano;
      //$ano_exercicio = 2008;

	    $result  = pg_exec($sql);
      $numrows = pg_numrows($result);
      $sqlerro = false;

      $arr_rubprinc = Array();
      $arr_lotacexe = Array();
      $arr_lotacatv = Array();

      $barran = "";
      $erro_msg = "";
      $erro_msg_setado = false;

	    $result_parametro = $clorcparametro->sql_record($clorcparametro->sql_query_file($ano_exercicio,"o50_subelem"));
		  if($clorcparametro->numrows == 0 || $result_parametro == false){
		  	$erro_msg = "Configure os parâmetros do orçamento.";
		  	$sqlerro = true;
		  }else{
		    db_fieldsmemory($result_parametro,0);
		  }

      if($numrows > 0 && $sqlerro == false){
      	
	      for($i=0;$i<$numrows;$i++){
					db_fieldsmemory($result,$i);
					flush();
				

				  	if($erro_msg_setado == true){
				  		$erro_msg_setado = false;
				  		$erro_msg = "";
				  	}				
				
				
				
					  // Buscar orgao e unidade
					  $result_orcunidad = $clrhlotaexe->sql_record($clrhlotaexe->sql_query_file($ano_exercicio,$lotacao,"$i as mostra, rh26_orgao as orgao,rh26_unidade as unidade"));
					  //echo "<BR><BR>".($clrhlotaexe->sql_query_file($anousu,$lotacao,"rh26_orgao as orgao,rh26_unidade as unidade"));
					  if($clrhlotaexe->numrows==0){
              if(!isset($arr_lotacexe[$lotacao])){
              	$arr_lotacexe[$lotacao] = $lotacao;
						  	$erro_msg.= $barran."Verifique órgão e unidade da lotação \"".$lotacao."\".";
						  	$barran  = "\\n";
						  	$sqlerro = true;
					    }
					  }else{
					  	db_fieldsmemory($result_orcunidad,0);
					  }
					  /////////////////////////
				
				
				
				
					  // Buscar recurso e proj. ativ.
					  $result_projvinrec = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file(null,"$i as mostra, rh25_codlotavinc as lotavinc,rh25_projativ as projativ,rh25_recurso as recurso","","rh25_codigo=$lotacao and rh25_vinculo='$vinculo' and rh25_anousu=$ano_exercicio"));
					  //echo "<BR><BR>".($clrhlotavinc->sql_query_file(null,"rh25_codlotavinc as lotavinc,rh25_projativ as projativ,rh25_recurso as recurso","","rh25_codigo=$lotacao and rh25_vinculo='$vinculo' and rh25_anousu=$anousu"));
					  if($clrhlotavinc->numrows==0){
				      if(!isset($arr_lotacatv[$lotacao])){
				      	$arr_lotacatv[$lotacao] = $lotacao;
						  	$erro_msg.= $barran."Verifique recurso e projeto atividade da lotação \"".$lotacao."\".";
						  	$barran  = "\\n";
						  	$sqlerro = true;
				      }
					  }else{
					    db_fieldsmemory($result_projvinrec,0);
					  }
					  /////////////////////////

            if($sqlerro == true){
            	continue;
            }

            $erro_msg_setado = true;


					  //////////////////////////////////////////////////////////////////
					  // Verificar se:
					  //   1) Se lotavinc = rh28_codlotavinc e elemento = rh28_codele
					  //   ** Se forem diferentes:
					  //      O elemento a ser gravado na tabela rhempfolha sera o $elemento
					  //      O projeto atividade a ser gravado na tabela rhempfolha sera o $projativ
					  //   ** Se forem iguais:
					  //      O elemento a ser gravado na tabela rhempfolha sera o $elementonovo
					  //
					  //      E depois?
					  //      Testa se existe algum registro na tabela rhlotavincativ em que o 
					  //      rh28_codlotavinc = lotavinc e rh28_codelenov = elementonovo
					  //      ** Se tiver algum registro, o projeto atividade a ser gravado na tabela 
					  //         rhempfolha sera o $projativnovo
					  //      ** Caso contrario, o projeto atividade a ser gravado na tabela 
					  //         rhempfolha será $projativ
					  //      Testa também se existe algum registro na tabela rhlotavincrec em que o
					  //      rh43_codlotavinc = lotavinc e rh43_codelenov = elementonovo
					  //      ** Se tiver algum registro, o recurso a ser gravado na tabela rhempfolha
					  //         será o $recurso
					  //      ** Caso contrário, o recurso a ser gravado na tabela rhempfolha será o
					  //         $recursonovo 
					  $result_testanovos = $clrhlotavincele->sql_record($clrhlotavincele->sql_query_file($lotavinc,$elemento,"$i as mostra, rh28_codelenov as elementonovo"));
					  //echo "<BR><BR>".($clrhlotavincele->sql_query_file($lotavinc,$elemento,"rh28_codelenov as elementonovo"));
					  if($clrhlotavincele->numrows>0){
					    db_fieldsmemory($result_testanovos,0);
					    // A variável elemento recebera o valor do novo elemento
					    $elemento = $elementonovo;
					    // echo "<BR><BR>".($clrhlotavincativ->sql_query_file(null,null,"rh39_projativ as projativnovo","","rh39_codlotavinc=$lotavinc and rh39_codelenov=$elementonovo and rh39_anousu=$anousu"));
					    $result_novoprojativ = $clrhlotavincativ->sql_record($clrhlotavincativ->sql_query_file(null,null,"$i as mostra, rh39_projativ as projativnovo","","rh39_codlotavinc=$lotavinc and rh39_codelenov=$elementonovo and rh39_anousu=$ano_exercicio"));
					    if($clrhlotavincativ->numrows>0){
					      db_fieldsmemory($result_novoprojativ,0);
					      // A variavel projativ recebera o valor do novo projeto atividade
					      $projativ = $projativnovo;
					    }
					    $result_novorecurso = $clrhlotavincrec->sql_record($clrhlotavincrec->sql_query_file(null,null,"$i as mostra, rh43_recurso as recursonovo","","rh43_codlotavinc=$lotavinc and rh43_codelenov=$elementonovo "));
					    if($clrhlotavincrec->numrows>0){
					      db_fieldsmemory($result_novorecurso,0);
					      // A variavel recurso receberá o valor do novo recurso
					      $recurso = $recursonovo;
					    }
					  }
					  /////////////////////////
	
	
	
	
		        $where_param = "";
					  if($o50_subelem=="f"){
					    // Buscar elemento
					    $result_elemento = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"$i as mostra, substr(o56_elemento,1,7)||'000000' as elemen","","o56_codele=$elemento and o56_anousu=".db_getsession("DB_anousu")));
					    if($clorcelemento->numrows>0){
					      db_fieldsmemory($result_elemento,0);
					      $where_param = " and o56_elemento='$elemen' ";
					    }
					    /////////////////////////
					  }else{
					    $where_param = " and o58_codele=$elemento ";
					  }
	
	
	
					  //// específica para Sapiranga ver depois como acertar via sistema 03/10/2005
		        if(trim(strtoupper($munic)) == "SAPIRANGA" ){
		 			    if($rubric == '0032' || $rubric == '2032' || $rubric == '4032' || $rubric == '1910'){
					      if($ano_exercicio > 2008){
				 		      $orgao = 2;
					        $unidade = 1;
					        $projativ = 2034;
					        $recurso  = 1;
					      }
					    }
		 			    if($rubric == '0039' || $rubric == '2039'){
					      if($ano_exercicio == 2005){
				 		      $orgao = 7;
					        $unidade = 2;
					        $projativ = 2084;
					        $recurso  = 30;
					      }elseif($ano_exercicio == 2006){
				 		      $orgao = 7;
					        $unidade = 2;
					        $projativ = 2088;
					        $recurso  = 30;
					      }
					    }
	
					    if($rubric == '0054' || $rubric == '0150' ||$rubric == '2054' || $rubric == '2150'){
					      if($ano_exercicio == 2005){
				 		      $orgao    = 8;
					        $unidade  = 1;
					        $projativ = 2113;
					        $recurso  = 1155;
					      }elseif($ano_exercicio == 2006){
				 		      $orgao    = 8;
					        $unidade  = 1;
					        $projativ = 2074;
					        $recurso  = 1155;
					      }else{
				 		      $orgao    = 8;
					        $unidade  = 1;
					        $projativ = 2074;
					        $recurso  = 4840;
					      }
					    }
	
					    if($rubric == '0033' 
					    || $rubric == '0034' 
					    || $rubric == '1911' 
					    || $rubric == '1912' 
					    || $rubric == '2033' 
					    || $rubric == '2034' 
					    || $rubric == '4033' 
					    || $rubric == '4034' 
                ){
				        if($ano_exercicio == 2005){
					        $orgao    = 3;
					        $unidade  = 1;
					        $projativ = 2035;
					        $recurso  = 1;
					      }else{
					        $orgao    = 3;
					        $unidade  = 1;
					        $projativ = 2032;
					        $recurso  = 1;
                                                //$elemento = 875;
	       				}
					    }
            }elseif(trim(strtoupper($munic)) == "GUAIBA" ){
              if($ano_exercicio == 2006){
                if($elemento == 868 || $elemento == 869){
                  if( $orgao != 7){
                    $orgao = 4;
                    $unidade = 1;
                    $projativ = 2015;
                    $recurso  = 1;
                  }else{
                    $orgao = 7;
                    $unidade = 2;
                    $projativ = 2080;
                    $recurso  = 30;
                  }
                }
              }
            }elseif(trim(strtoupper($munic)) == "ALEGRETE" ){
             // if($ano_exercicio == 2009){
              	
					      if($rubric == '0247' || $rubric == '0248' || $rubric == '4247' || $rubric == '4248' ){
					      	
                  $orgao    = 4;
                  $unidade  = 2;
                  $projativ = 2052;
                  $recurso  = 50;
                  
                }else if($orgao == 9 && $unidade == 1 && $projativ == 2094 && $recurso == 4510){
                	
                	if($rubric == '0005' || $rubric == '0006' || $rubric == '0066' || $rubric == '0067' || 
                		 $rubric == '0073' || $rubric == '0075' || $rubric == '0076' || $rubric == '0077' ||
                		 $rubric == '0079' || $rubric == '0096' || $rubric == '0160' || $rubric == '0168' ||
                		 $rubric == '0169' || $rubric == '0170' || $rubric == '0173' || $rubric == '0177' ||
                		 $rubric == '0188' || $rubric == '0249'){
                		
                	$orgao    = 9;
                  $unidade  = 2;
                  $projativ = 2091;
                  $recurso  = 40;
                  
                	}
                }
              //}
				    }


					  ////////////////////////////////////////////////////////
	
	
					  // Buscar dotacao
					  $result_dotacao = $clorcdotacao->sql_record($clorcdotacao->sql_query_ele(null,null,"$i as mostra, o58_coddot as dotacao","","o58_anousu=$ano_exercicio and o58_orgao=$orgao and o58_unidade=$unidade and o58_projativ=$projativ $where_param and o58_codigo=$recurso"));
//					  echo "<BR><BR>".($clorcdotacao->sql_query_ele(null,null,"$i as mostra, o58_coddot as dotacao","","o58_anousu=$anousu and o58_orgao=$orgao and o58_unidade=$unidade and o58_projativ=$projativ $where_param and o58_codigo=$recurso"));
					  //echo "<BR><BR>".($clorcdotacao->sql_query_ele(null,null,"o58_coddot as dotacao","","o58_anousu=$anousu and o58_orgao=$orgao and o58_unidade=$unidade and o58_projativ=$projativ $where_param and o58_codigo=$recurso"));
					  if($clorcdotacao->numrows > 0){
					    db_fieldsmemory($result_dotacao,0);
					  }else{
					    $dotacao = '0';
					  }
					  /////////////////////////
	
	
	
					  $incluioualtera = $clrhempfolha->sql_query_file(
																											      null,null,null,null,null,null,null,null,null,null,
																											      "$i as mostra, rh40_provento as provento,rh40_desconto as desconto",
																												    "",
																												    "
								       rh40_anousu    = $anousu 
								   and rh40_mesusu    = $mesusu
							           and rh40_orgao     = $orgao
								   and rh40_unidade   = $unidade
								   and rh40_projativ  = $projativ
								   and rh40_recurso   = $recurso
								   and rh40_codele    = $elemento
								   and rh40_rubric    = '$rubric'
								   and rh40_siglaarq  = '$siglaarq'
								   and rh40_sequencia = $rh40_sequencia
								   and rh40_instit    = ".db_getsession("DB_instit")."
																											      "
																												   );
	     			// echo "<BR><BR>".$incluioualtera;
					  $result_incluioualtera = $clrhempfolha->sql_record($incluioualtera);
					  $numrows_incluioualtera = $clrhempfolha->numrows;

					  if($numrows_incluioualtera == 0){
					    // Incluir dados na tabela rhempfolha
					    $provento = 0;
					    $desconto = 0;
					    if($pd==2){
					      $desconto = $valor;
					    }else{
					      $provento = $valor;
					    }
					    $clrhempfolha->rh40_sequencia = "$rh40_sequencia";
					    $clrhempfolha->rh40_provento = "$provento";
					    $clrhempfolha->rh40_desconto = "$desconto";
					    $clrhempfolha->rh40_tipo     = strtolower($rh40_tipo); 
					    $clrhempfolha->rh40_tabprev  = "0";
					    $clrhempfolha->rh40_coddot   = "$dotacao"; 
					    $clrhempfolha->rh40_instit   = db_getsession("DB_instit");
					    $clrhempfolha->incluir(@$anousu,@$mesusu,$orgao,$unidade,$projativ,$recurso,$elemento,$rubric,$siglaarq,db_getsession("DB_instit"));	    
					    $erro_msg = $clrhempfolha->erro_msg;
					    if($clrhempfolha->erro_status==0){
					      $sqlerro=true;
					      break;
					    }
					    /////////////////////////
					  }else if($numrows_incluioualtera>0){
					    db_fieldsmemory($result_incluioualtera,0);
					    if($pd==2){
					      $desconto += $valor;
					    }else{
					      $provento += $valor;
					    }
					    $clrhempfolha->rh40_provento = "$provento";
					    $clrhempfolha->rh40_desconto = "$desconto";
					    $clrhempfolha->rh40_siglaarq = $siglaarq;
					    $clrhempfolha->rh40_tipo     = strtolower($rh40_tipo); 
					    $clrhempfolha->rh40_tabprev  = "0";
					    $clrhempfolha->rh40_coddot   = "$dotacao"; 
					    $clrhempfolha->rh40_anousu   = $anousu;
					    $clrhempfolha->rh40_mesusu   = $mesusu;
					    $clrhempfolha->rh40_orgao    = $orgao;
					    $clrhempfolha->rh40_unidade  = $unidade;
					    $clrhempfolha->rh40_projativ = $projativ;
					    $clrhempfolha->rh40_recurso  = $recurso;
					    $clrhempfolha->rh40_codele   = $elemento;
					    $clrhempfolha->rh40_rubric   = $rubric;
					    $clrhempfolha->rh40_sequencia= $rh40_sequencia;
					    $clrhempfolha->rh40_instit   = db_getsession("DB_instit");
					    $clrhempfolha->alterar($anousu,$mesusu,$orgao,$unidade,$projativ,$recurso,$elemento,$rubric,$siglaarq,db_getsession("DB_instit"));
					    $erro_msg = $clrhempfolha->erro_msg;
					    if($clrhempfolha->erro_status==0){
					      $sqlerro=true;
					      break;
					    }
					  }
	      }
      }else{
      	$erro_msg = "Nenhum registro encontrado para $ano / $mes";
      	$sqlerro = true;
      }
      if(count($arr_rubprinc) > 0 || count($arr_lotacexe) > 0 || count($arr_lotacatv) > 0){
      	$erro_msg = "Dados não encontrados, verifique: \\n\\n".$erro_msg;
      }
      //$sqlerro=true;
	    db_fim_transacao($sqlerro);
    }
  }
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center">
  <tr>
    <td>
    <?
    include("forms/db_frmrhempfolha.php");
    ?>
    </td>
  </tr>
  <tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if(isset($gera) || isset($confirma)){
  if($passa == true && !isset($confirma)){
  echo "
  <script>
    if(confirm('Empenhos já gerados para este período.\\nReprocessar?')){
      obj=document.createElement('input');
      obj.setAttribute('name','confirma');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value','confirma');
      document.form1.appendChild(obj);
      document.form1.DBtxt25.value = '$mes';      
      document.form1.submit();
    }
  </script>
  ";
  }
  if(isset($confirma) && isset($erro_msg)){
    db_msgbox($erro_msg);
  }
}
?>