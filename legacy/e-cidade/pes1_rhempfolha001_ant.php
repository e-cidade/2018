<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_orcdotacao_classe.php");
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
$clrhrubelementoprinc = new cl_rhrubelementoprinc;
$clrhlotaexe = new cl_rhlotaexe;
$clrhempfolha = new cl_rhempfolha;
$clrhlotavinc = new cl_rhlotavinc;
$clrhlotavincele = new cl_rhlotavincele;
$clrhlotavincativ = new cl_rhlotavincativ;
$clorcdotacao = new cl_orcdotacao;
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
db_postmemory($HTTP_POST_VARS);

$passa = false;
if(isset($confirma) || isset($gera)){

  $ano = $DBtxt23;
  $mes = $DBtxt25;

  if($ponto == 's'){
    $arquivo = 'gerfsal';
    $sigla   = 'r14_';
    $siglaarq= 'r14';
  }elseif($ponto == 'c'){
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
  }

  $result_confirma = $clrhempfolha->sql_record($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,"*","","rh40_anousu=$ano and rh40_mesusu=$mes and rh40_tipo='$rh40_tipo' and rh40_siglaarq='$siglaarq'"));
  // echo "<BR><BR>".($clrhempfolha->sql_query_file(null,null,null,null,null,null,null,null,"*","","rh40_anousu=$ano and rh40_mesusu=$mes and rh40_tipo='$rh40_tipo' and rh40_siglaarq='$siglaarq'"));
  if($clrhempfolha->numrows>0){
    $passa = true;
  }else{
    $confirma = "confirma";
  }
  $sqlerro=false;
  if(isset($confirma)){
    db_inicio_transacao();
    $clrhempfolha->excluir(null,null,null,null,null,null,null,null,"rh40_anousu=$ano and rh40_mesusu=$mes and rh40_tipo='$rh40_tipo' and rh40_siglaarq='$siglaarq'");
    if($clrhempfolha->erro_status==0){
      $erro_msg = $clrhempfolha->erro_msg;
      $sqlerro=true;
    }
    if($sqlerro==false){
      $sql = "
	      select ".$sigla."rubric as rubric,
		     ".$sigla."regist as regist,
		     r01_tpvinc as vinculo,
		     r01_tbprev as previdencia,
		     ".$sigla."pd as pd,
		     ".$sigla."quant as quant,
		     to_number(".$sigla."lotac,'99999') as lotacao,
		     ".$sigla."valor as valor,
		     ".$sigla."anousu as anousu,
		     ".$sigla."mesusu as mesusu
	      from ".$arquivo." 
		   inner join pessoal on ".$sigla."regist = r01_regist 
				     and r01_anousu = ".$sigla."anousu 
				     and r01_mesusu = ".$sigla."mesusu 
	      where ".$sigla."anousu = $ano 
		and ".$sigla."mesusu = $mes
	     ";
      $result  = pg_exec($sql);
//      db_criatabela($result);
      $numrows = pg_numrows($result);
      $sqlerro = false;
      db_inicio_transacao();
      for($i=0;$i<$numrows;$i++){
	db_fieldsmemory($result,$i);
	if($sqlerro == false){




	  // Buscar o elemento principal da rubrica
	  $result_rubrica = $clrhrubelementoprinc->sql_record($clrhrubelementoprinc->sql_query_file($rubric,null,"rh24_codele as elemento"));
      //     echo "<BR><BR>".($clrhrubelementoprinc->sql_query_file($rubric,null,"rh24_codele as elementoprinc"));
	  if($clrhrubelementoprinc->numrows==0){
	    continue;
	  }
	  db_fieldsmemory($result_rubrica,0);
	  /////////////////////////




	  // Buscar orgao e unidade
	  $result_elemento = $clrhlotaexe->sql_record($clrhlotaexe->sql_query_file($anousu,$lotacao,"rh26_orgao as orgao,rh26_unidade as unidade"));
	  // echo "<BR><BR>".($clrhlotaexe->sql_query_file($anousu,$lotacao,"rh26_orgao as orgao,rh26_unidade as unidade"));
	  if($clrhlotaexe->numrows==0){
	    continue;
	  }
	  db_fieldsmemory($result_elemento,0);
	  /////////////////////////




	  // Buscar recurso e proj. ativ.
	  $result_projvinrec = $clrhlotavinc->sql_record($clrhlotavinc->sql_query_file(null,"rh25_codlotavinc as lotavinc,rh25_projativ as projativ,rh25_recurso as recurso","","rh25_codigo=$lotacao and rh25_vinculo='$vinculo' and rh25_anousu=$anousu"));
	  // echo "<BR><BR>".($clrhlotavinc->sql_query_file(null,"rh25_codlotavinc as lotavinc,rh25_projativ as projativ,rh25_recurso as recurso","","rh25_codigo=$lotacao and rh25_vinculo='$vinculo' and rh25_anousu=$anousu"));
	  if($clrhlotavinc->numrows==0){
	    continue;
	  }
	  db_fieldsmemory($result_projvinrec,0);
	  /////////////////////////




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
	  //      Testa se existe algum registro na tabela rhlotavincele em que o 
	  //      rh28_codlotavinc = lotavinc e rh28_codelenov = elementonovo
	  //      ** Se tiver algum registro, o projeto atividade a ser gravado na tabela rhempfolha 
	  //         sera o $projativnovo
	  //      ** Caso contrario, o projeto atividade a ser gravado na tabela rhempfolha sera 
	  //         $projativ
	  $result_testanovos = $clrhlotavincele->sql_record($clrhlotavincele->sql_query_file($lotavinc,$elemento,"rh28_codelenov as elementonovo"));
	  // echo "<BR><BR>".($clrhlotavincele->sql_query_file($lotavinc,$elemento,"rh28_codelenov as elementonovo"));
	  if($clrhlotavincele->numrows>0){
	    db_fieldsmemory($result_testanovos,0);
	    // A variável elemento recebera o valor do novo elemento
	    $elemento = $elementonovo;
	    // echo "<BR><BR>".($clrhlotavincativ->sql_query_file(null,null,"rh39_projativ as projativnovo","","rh39_codlotavinc=$lotavinc and rh39_codelenov=$elementonovo and rh39_anousu=$anousu"));
	    $result_novoprojativ = $clrhlotavincativ->sql_record($clrhlotavincativ->sql_query_file(null,null,"rh39_projativ as projativnovo","","rh39_codlotavinc=$lotavinc and rh39_codelenov=$elementonovo and rh39_anousu=$anousu"));
	    if($clrhlotavincativ->numrows>0){
	      db_fieldsmemory($result_novoprojativ,0);
	      // A variavel projativ recebera o valor do novo projeto atividade
	      $projativ = $projativnovo;
	    }
	  }
	  /////////////////////////




          $result_parametro = $clorcparametro->sql_record($clorcparametro->sql_query_file($anousu,"o50_subelem"));
	  if($clorcparametro->numrows == 0 || $result_parametro == false){
	    continue;
	  }
	  db_fieldsmemory($result_parametro,0);

          $where_param = "";
	  if($o50_subelem=="f"){
	    // Buscar elemento
	    $result_elemento = $clorcelemento->sql_record($clorcelemento->sql_query_file(null,null,"substr(o56_elemento,1,7)||'000000' as elemen",""," o56_anousu = ".db_getsession("DB_anousu")." and o56_codele=$elemento"));
	    if($clorcelemento->numrows>0){
	      db_fieldsmemory($result_elemento,0);
	      $where_param = " and o56_elemento='$elemen' ";
	    }
	    /////////////////////////
	  }else{
	    $where_param = " and o58_codele=$elemento ";
	  }


	  // Buscar dotacao
	  $result_dotacao = $clorcdotacao->sql_record($clorcdotacao->sql_query_ele(null,null,"o58_coddot as dotacao","","o58_anousu=$anousu and o58_orgao=$orgao and o58_unidade=$unidade and o58_projativ=$projativ $where_param and o58_codigo=$recurso"));
	  //echo "<BR><BR>".($clorcdotacao->sql_query_ele(null,null,"o58_coddot as dotacao","","o58_anousu=$anousu and o58_orgao=$orgao and o58_unidade=$unidade and o58_projativ=$projativ $where_param and o58_codigo=$recurso"));
	  if($clorcdotacao->numrows > 0){
	    db_fieldsmemory($result_dotacao,0);
	  }else{
	    $dotacao = '0';
	  }
	  /////////////////////////



	  $ncluioualtera = $clrhempfolha->sql_query_file(
				     null,null,null,null,null,null,null,null,
				     "rh40_provento as provento,rh40_desconto as desconto","","
					  rh40_anousu   = $anousu 
				      and rh40_mesusu   = $mesusu
				      and rh40_orgao    = $orgao
				      and rh40_unidade  = $unidade
				      and rh40_projativ = $projativ
				      and rh40_recurso  = $recurso
				      and rh40_codele   = $elemento
				      and rh40_rubric   = '$rubric'
				     "
				   );
//    echo "<BR><BR>".$ncluioualtera;
	  $result_incluioualtera = $clrhempfolha->sql_record(
				     $clrhempfolha->sql_query_file(
				     null,null,null,null,null,null,null,null,
				     "rh40_provento as provento,rh40_desconto as desconto","","
					  rh40_anousu   = $anousu 
				      and rh40_mesusu   = $mesusu
				      and rh40_orgao    = $orgao
				      and rh40_unidade  = $unidade
				      and rh40_projativ = $projativ
				      and rh40_recurso  = $recurso
				      and rh40_codele   = $elemento
				      and rh40_rubric   = '$rubric'
				     "
				     )
				   );
	  $numrows_incluioualtera = $clrhempfolha->numrows;
//	    $a++;

	  if($numrows_incluioualtera == 0){
	    // Incluir dados na tabela rhempfolha
	    $provento = 0;
	    $desconto = 0;
	    if($pd==1){
	      $provento = $valor;
	    }else{
	      $desconto = $valor;
	    }
	    $clrhempfolha->rh40_provento = "$provento";
	    $clrhempfolha->rh40_desconto = "$desconto";
	    $clrhempfolha->rh40_siglaarq = $siglaarq;
	    $clrhempfolha->rh40_tipo     = strtolower($rh40_tipo); 
	    $clrhempfolha->rh40_tabprev  = "0";
	    $clrhempfolha->rh40_coddot   = $dotacao; 
	    $clrhempfolha->incluir(@$anousu,@$mesusu,$orgao,$unidade,$projativ,$recurso,$elemento,$rubric);	    
	    $erro_msg = $clrhempfolha->erro_msg;
	    if($clrhempfolha->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	    /////////////////////////
	  }else if($numrows_incluioualtera>0){
	    db_fieldsmemory($result_incluioualtera,0);
	    if($pd==1){
	      $provento += $valor;
	    }else{
	      $desconto += $valor;
	    }
	    $clrhempfolha->rh40_provento = "$provento";
	    $clrhempfolha->rh40_desconto = "$desconto";
	    $clrhempfolha->rh40_anousu   = $anousu;
	    $clrhempfolha->rh40_mesusu   = $mesusu;
	    $clrhempfolha->rh40_orgao    = $orgao;
	    $clrhempfolha->rh40_unidade  = $unidade;
	    $clrhempfolha->rh40_projativ = $projativ;
	    $clrhempfolha->rh40_recurso  = $recurso;
	    $clrhempfolha->rh40_codele   = $elemento;
	    $clrhempfolha->rh40_rubric   = $rubric;
	    $clrhempfolha->alterar($anousu,$mesusu,$orgao,$unidade,$projativ,$recurso,$elemento,$rubric);
	    $erro_msg = $clrhempfolha->erro_msg;
	    if($clrhempfolha->erro_status==0){
	      $sqlerro=true;
	      break;
	    }
	  }


	}
      }

     //$sqlerro=true;

    // db_msgbox($a);
    }
    db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
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