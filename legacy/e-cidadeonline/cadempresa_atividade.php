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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhcbo_classe.php");
include("classes/db_cnae_classe.php");
include("classes/db_cnaeanalitica_classe.php");
include("classes/db_dbprefcgmrhcbo_classe.php");
include("classes/db_dbempresaatividade_classe.php");
include("classes/db_dbempresaatividaderhcbo_classe.php");
include("classes/db_dbprefcgmcnae_classe.php");
include("classes/db_dbprefempresaatividadecnae_classe.php");

postmemory($HTTP_POST_VARS);
postmemory($HTTP_GET_VARS);
//parse_str($HTTP_SERVER_VARS["QUERY_STRING"])///);
$cgm = $_SESSION["dbprefcgm"];
$dbprefempresa = $_SESSION["dbprefempresa"];

//echo "atividades <br>pessoa = $pessoa <br> cpf = $cpf_cnpj <br> cgm = $cgm <br> dbprefempresa =$dbprefempresa";
$clrhcbo                  = new cl_rhcbo;
$clcnae                   = new cl_cnae;
$clcnaeanalitica          = new cl_cnaeanalitica;
$cldbprefcgmrhcbo         = new cl_dbprefcgmrhcbo;
$cldbempresaatividade     = new cl_dbempresaatividade;
$cldbempresaatividaderhcbo= new cl_dbempresaatividaderhcbo;
$cldbprefcgmcnae          = new cl_dbprefcgmcnae;
$cldbprefempresaatividadecnae = new cl_dbprefempresaatividadecnae;
$clcnaeanalitica  -> rotulo->label();
$clcnae  -> rotulo->label();
$clrhcbo -> rotulo->label();


// #################################### INCLUIR ##############################
if(isset($incluir)){
  $sqlerro = false;
  $q58_dtinc = $q58_dtinc_ano."-".$q58_dtinc_mes."-".$q58_dtinc_dia;
  if(($q58_dtinc=="--") || ($q58_dtinc_ano=="")||($q58_dtinc_mes=="")|| ($q58_dtinc_dia=="") ){
    $q58_dtinc="";
    $q58_dtinc_ano="";
    $q58_dtinc_mes="";
    $q58_dtinc_dia="";
  }
  // #################################### INCLUIR FÍSICA ##############################
  if($pessoa=='F'){
    db_query('BEGIN');
    $cldbprefcgmrhcbo -> z01_dbprefcgm = $cgm ;
    $cldbprefcgmrhcbo -> z01_hrcbo     = $rh70_sequencial ;
    $cldbprefcgmrhcbo -> incluir(null);
    if ($cldbprefcgmrhcbo->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgmrhcbo->erro_msg;
    }
    if($sqlerro == false){
	    $cldbempresaatividade -> q58_dbprefempresa = $dbprefempresa ;
	    $cldbempresaatividade -> q58_dtinc = $q58_dtinc ;
	    $cldbempresaatividade -> incluir(null);
	    if ($cldbempresaatividade->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = $cldbempresaatividade->erro_msg;
	    }
    }
    if($sqlerro == false){
	    $cldbempresaatividaderhcbo -> q59_dbempresaatividade = $cldbempresaatividade->q58_sequencial ;
	    $cldbempresaatividaderhcbo -> q59_rhcbo = $rh70_sequencial ;
	    $cldbempresaatividaderhcbo -> incluir(null);
	    if ($cldbempresaatividaderhcbo->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = $cldbempresaatividaderhcbo->erro_msg;
	    }
    }
    if($sqlerro==true){
      db_query('ROLLBACK');
    }else{
      db_query('COMMIT');
    }
  }else{
    // #################################### INCLUIR JURÍDICA ##############################
    db_query('BEGIN');
    //juridica

    $cldbprefcgmcnae -> z01_dbprefcgm     = $cgm ;
    $cldbprefcgmcnae -> z01_cnaeanalitica = $q72_sequencial ;
    $cldbprefcgmcnae -> incluir(null);
    if ($cldbprefcgmcnae->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = $cldbprefcgmcnae->erro_msg;
    }
    if($sqlerro == false){
	    $cldbempresaatividade -> q58_dbprefempresa = $dbprefempresa ;
	    $cldbempresaatividade -> q58_dtinc = $q58_dtinc ;
	    $cldbempresaatividade -> incluir(null);
	    if ($cldbempresaatividade->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = $cldbempresaatividade->erro_msg;
	    }
    }
    if($sqlerro == false){
	    $cldbprefempresaatividadecnae -> q65_dbempresaatividade = $cldbempresaatividade -> q58_sequencial ;
	    $cldbprefempresaatividadecnae -> q65_cnaeanalitica = $q72_sequencial ;
	    $cldbprefempresaatividadecnae -> incluir(null)  ;
	    if ($cldbprefempresaatividadecnae->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = $cldbprefempresaatividadecnae->erro_msg;
	    }
    }
    if($sqlerro==true){
      db_query('ROLLBACK');
    }else{
      db_query('COMMIT');
    }
  }
$q58_dtinc="";
}
// #################################### ALTERAR ##############################
if(isset($alterar)){
  $sqlerro = false;
  // #################################### ALTERAR FÍSICA ##############################
  if($pessoa=='F'){

    $sqlalterar = "
    select dbprefcgmrhcbo.z01_sequencial,q58_sequencial,q59_sequencial
  from dbempresaatividade
       inner join dbempresaatividaderhcbo on q59_dbempresaatividade = q58_sequencial
       inner join dbprefempresa           on q55_sequencial         = q58_dbprefempresa
       inner join rhcbo                   on q59_rhcbo              = rh70_sequencial
	   inner join dbprefcgm               on q55_dbprefcgm          = dbprefcgm.z01_sequencial
       inner join dbprefcgmrhcbo          on z01_dbprefcgm          = dbprefcgm.z01_sequencial
  where q55_sequencial= $dbprefempresa and q59_rhcbo = $seqant and z01_hrcbo = $seqant";
    // echo "<br>$sqlalterar";
    $resultalterar = db_query($sqlalterar);
    $linhasalterar = pg_num_rows($resultalterar);
    if($linhasalterar>0){
      db_fieldsmemory($resultalterar,0);
      $q58_dtinc = $q58_dtinc_ano."-".$q58_dtinc_mes."-".$q58_dtinc_dia;
	  if(($q58_dtinc=="--") || ($q58_dtinc_ano=="")||($q58_dtinc_mes=="")|| ($q58_dtinc_dia=="") ){
	    $q58_dtinc="";
	    $q58_dtinc_ano="";
	    $q58_dtinc_mes="";
	    $q58_dtinc_dia="";
	  }
      db_query('BEGIN');
      $cldbprefcgmrhcbo -> z01_sequencial = $z01_sequencial;
      $cldbprefcgmrhcbo -> z01_hrcbo      = $rh70_sequencial ;
      $cldbprefcgmrhcbo -> alterar($z01_sequencial);
      if ($cldbprefcgmrhcbo->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefcgmrhcbo->erro_msg;
      }

      $cldbempresaatividade -> q58_sequencial = $q58_sequencial;
      $cldbempresaatividade -> q58_dtinc      = $q58_dtinc ;
      $cldbempresaatividade ->alterar($q58_sequencial);
      if ($cldbempresaatividade->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbempresaatividade->erro_msg;
      }
       
      $cldbempresaatividaderhcbo -> q59_sequencial = $q59_sequencial;
      $cldbempresaatividaderhcbo -> q59_rhcbo      = $rh70_sequencial ;
      $cldbempresaatividaderhcbo -> alterar($q59_sequencial);
      if ($cldbempresaatividaderhcbo->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbempresaatividaderhcbo->erro_msg;
      }
      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');

      }
    }
  }else{
    // #################################### ALTERAR JURÍDICA ##############################
    $sqlalterar = "
select z01_sequencial,q58_sequencial,q65_sequencial
from  dbempresaatividade
inner join dbprefempresaatividadecnae on q65_dbempresaatividade = q58_sequencial
inner join dbprefempresa on q58_dbprefempresa            = q55_sequencial
inner join cnaeanalitica on q65_cnaeanalitica = q72_sequencial
inner join cnae          on q72_cnae = q71_sequencial
inner join dbprefcgmcnae on z01_cnaeanalitica = q72_sequencial
where q55_sequencial= $dbprefempresa and q65_cnaeanalitica= $seqant and z01_cnaeanalitica = $seqant";
    //echo "$sqlaltera";
    $resultalterar = db_query($sqlalterar);
    $linhasalterar = pg_num_rows($resultalterar);
    if($linhasalterar>0){
      db_fieldsmemory($resultalterar,0);
     
	  $q58_dtinc = $q58_dtinc_ano."-".$q58_dtinc_mes."-".$q58_dtinc_dia;
	  if(($q58_dtinc=="--") || ($q58_dtinc_ano=="")||($q58_dtinc_mes=="")|| ($q58_dtinc_dia=="") ){
	    $q58_dtinc="";
	    $q58_dtinc_ano="";
	    $q58_dtinc_mes="";
	    $q58_dtinc_dia="";
	  }
      db_query('BEGIN');

      $cldbprefcgmcnae -> z01_sequencial    = $z01_sequencial;
      $cldbprefcgmcnae -> z01_cnaeanalitica = $q72_sequencial ;
      $cldbprefcgmcnae -> alterar($z01_sequencial);
      if ($cldbprefcgmcnae->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefcgmcnae->erro_msg;
      }
      $cldbempresaatividade -> q58_sequencial = $q58_sequencial;
      $cldbempresaatividade -> q58_dtinc      = $q58_dtinc ;
      $cldbempresaatividade -> alterar($q58_sequencial);
      if ($cldbempresaatividade->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbempresaatividade->erro_msg;
      }

      $cldbprefempresaatividadecnae -> q65_sequencial    = $q65_sequencial;
      $cldbprefempresaatividadecnae -> q65_cnaeanalitica = $q72_sequencial ;
      $cldbprefempresaatividadecnae -> alterar($q65_sequencial)  ;
      if ($cldbprefempresaatividadecnae->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefempresaatividadecnae->erro_msg;
      }
      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');
      }

    }
  }
  $q58_dtinc="";
}
 // #################################### EXCLUIR ##############################
if(isset($excluir)){
  $sqlerro = false;
  
// #################################### EXCLUIR FÍSICA ##############################  
  if($pessoa=='F'){
    
    $sqlexcluir = "
  select dbprefcgmrhcbo.z01_sequencial,q58_sequencial,q59_sequencial
  from dbempresaatividade
       inner join dbempresaatividaderhcbo on q59_dbempresaatividade = q58_sequencial
       inner join dbprefempresa           on q55_sequencial         = q58_dbprefempresa
       inner join rhcbo                   on q59_rhcbo              = rh70_sequencial
	   inner join dbprefcgm               on q55_dbprefcgm          = dbprefcgm.z01_sequencial
       inner join dbprefcgmrhcbo          on z01_dbprefcgm          = dbprefcgm.z01_sequencial
  where q55_sequencial= $dbprefempresa and q59_rhcbo = $seqant and z01_hrcbo = $seqant";
    // echo "<br>$sqlexcluir";
     
    $resultexcluir = db_query($sqlexcluir);
    $linhasexcluir = pg_num_rows($resultexcluir);
    if($linhasexcluir>0){
      db_fieldsmemory($resultexcluir,0);
      
      db_query('BEGIN');
     
        $cldbprefcgmrhcbo -> z01_sequencial = $z01_sequencial;
        $cldbprefcgmrhcbo -> excluir($z01_sequencial);
        if ($cldbprefcgmrhcbo->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cldbprefcgmrhcbo->erro_msg;
        }
       
      if($sqlerro == false){
        $cldbempresaatividaderhcbo -> q59_sequencial = $q59_sequencial;
        $cldbempresaatividaderhcbo -> excluir($q59_sequencial);
        if ($cldbempresaatividaderhcbo->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cldbempresaatividaderhcbo->erro_msg;
        }
      }
      
      if($sqlerro == false){
        $cldbempresaatividade -> q58_sequencial = $q58_sequencial;
        $cldbempresaatividade ->excluir($q58_sequencial);
        if ($cldbempresaatividade->erro_status == 0) {
          $sqlerro = true;
          $erro_msg = $cldbempresaatividade->erro_msg;
        }
      }
           
      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');
     
      }
      
    }
  }else{
   // #################################### EXCLUIR JURIDICA ##############################   
	$sqlexcluir = "
	select z01_sequencial,q58_sequencial,q65_sequencial
	from  dbempresaatividade
	inner join dbprefempresaatividadecnae on q65_dbempresaatividade = q58_sequencial
	inner join dbprefempresa on q58_dbprefempresa            = q55_sequencial
	inner join cnaeanalitica on q65_cnaeanalitica = q72_sequencial
	inner join cnae          on q72_cnae = q71_sequencial
	inner join dbprefcgmcnae on z01_cnaeanalitica = q72_sequencial
	where q55_sequencial= $dbprefempresa and q65_cnaeanalitica= $seqant and z01_cnaeanalitica = $seqant"; 
	//echo "<br>$sqlexcluir";
	
	$resultexcluir = db_query($sqlexcluir);
    $linhasexcluir = pg_num_rows($resultexcluir);
    if($linhasexcluir>0){
      //db_msgbox('excluir');
      db_fieldsmemory($resultexcluir,0);

 db_query('BEGIN');

      $cldbprefcgmcnae -> z01_sequencial    = $z01_sequencial;
      $cldbprefcgmcnae -> excluir($z01_sequencial);
      if ($cldbprefcgmcnae->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefcgmcnae->erro_msg;
      }
      
      if($sqlerro == false){
	      $cldbprefempresaatividadecnae -> q65_sequencial    = $q65_sequencial;
	      $cldbprefempresaatividadecnae -> excluir($q65_sequencial)  ;
	      if ($cldbprefempresaatividadecnae->erro_status == 0) {
	        $sqlerro = true;
	        $erro_msg = $cldbprefempresaatividadecnae->erro_msg;
	      }
      }
      
      if($sqlerro == false){
	      $cldbempresaatividade -> q58_sequencial = $q58_sequencial;
	      $cldbempresaatividade -> excluir($q58_sequencial);
	      if ($cldbempresaatividade->erro_status == 0) {
	        $sqlerro = true;
	        $erro_msg = $cldbempresaatividade->erro_msg;
	      }
      }

      
      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');
      }


    }
  }
 $q58_dtinc=""; 
}

// ######################### CARREGA A TABELA ########################
if($pessoa=='F'){

  $sqlcarrega = "
select rh70_estrutural as codigo,rh70_descr as atividade,rh70_sequencial as seq, q58_dtinc as dataini
  from dbempresaatividade
       inner join dbempresaatividaderhcbo on q59_dbempresaatividade = q58_sequencial
       inner join dbprefempresa           on q55_sequencial         = q58_dbprefempresa
       inner join rhcbo                   on q59_rhcbo              = rh70_sequencial
 where q55_sequencial= $dbprefempresa";
  // echo "<br>$sqlcarrega";
}else{
  $sqlcarrega = "
select q71_estrutural as codigo,q71_descr as atividade,q72_sequencial as seq, q58_dtinc as dataini
from  dbempresaatividade
inner join dbprefempresaatividadecnae on q65_dbempresaatividade = q58_sequencial
inner join dbprefempresa on q58_dbprefempresa            = q55_sequencial
inner join cnaeanalitica on q65_cnaeanalitica = q72_sequencial
inner join cnae          on q72_cnae = q71_sequencial
where q55_sequencial= $dbprefempresa";

  //echo "<br>$sqlcarrega";
}
$resultcarrega = db_query($sqlcarrega);
$linhascarrega = pg_num_rows($resultcarrega);
if($linhascarrega>0){
  db_fieldsmemory($resultcarrega,0);
}
//die($sqlcarrega);

if(@$q58_dtinc==""){
  $sqldata = "select q55_dtinc as q58_dtinc from dbprefempresa where q55_sequencial = $dbprefempresa";
	$resultdata = db_query($sqldata);
	$linhasdata = pg_numrows($resultdata);
	if($linhasdata>0){
	  db_fieldsmemory($resultdata,0);
	  
	}

}

?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css">	<? db_estilosite(); ?></style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0	bgcolor="<?=$w01_corbody?>">
<center>
<form name="form1" method="post" action="">
<input name="pessoa"	type="hidden" value="<?=$pessoa?>"> 
<input name="seqant" type="hidden" value="">
<table width="700px" border="0" cellspacing="2" cellpadding="2"
	class="texto">
	<?$db_opcaoselect = 1;?>
	<tr>
		<td width="200px">&nbsp;</td>
		<td width="500px">&nbsp;</td>
	</tr>
	<tr>
		<td width="100px"><B>Pessoa: <?=($pessoa=='F'?'Física':'Jurídica') ?></B></td>
		<?
		if($pessoa=="F"){
		  $portefis='t';
		  echo"<td width='500px'><B>CPF ".db_formatar($cpf_cnpj,"cpf")."</B></td>
    </tr>";
		  ?>
	<tr>
		<td width="150"><?db_ancora(@$Lrh70_estrutural,"js_pesquisa_cbo(true);",$db_opcaoselect);?>
		</td>
		<td><?
		db_input("rh70_estrutural",12,$Irh70_estrutural,true,"text",$db_opcaoselect,"onchange='js_pesquisa_cbo(false);'");
		db_input("rh70_descr",60,$Irh70_descr,true,"text",3);
		db_input("rh70_sequencial",5,$Irh70_sequencial,true,"hidden",3);
		?></td>
	</tr>
	<?
}else{
  $portefis='f';
  echo"<td width='500px'><B>CNPJ: ".db_formatar($cpf_cnpj,"cnpj")."</B></td>
    </tr>";
  ?>
	<tr>
		<td width="150"><?db_ancora(@$Lq71_estrutural,"js_pesquisa_cnae(true);",$db_opcaoselect);?></td>
		<td><?
		db_input("q71_estrutural",12,$Iq71_estrutural,true,"text",$db_opcaoselect,"onchange='js_pesquisa_cnae(false);'");
		db_input("q71_descr",60,$Iq71_descr,true,"text",3);
		db_input("q72_sequencial",5,$Iq72_sequencial,true,"hidden",3);
		?></td>
	</tr>
	<?
}

if(@$q58_dtinc!=""){
		$q58_dtinc_dia = substr($q58_dtinc,8,2);
		$q58_dtinc_mes = substr($q58_dtinc,5,2);
		$q58_dtinc_ano = substr($q58_dtinc,0,4);
}

//echo "<br> dia $q58_dtinc_dia  mes $q58_dtinc_mes ano $q58_dtinc_ano";
?>

	<tr>
		<td>Data de início:</td>
		<td>
		  <? 
		    db_inputdata("q58_dtinc",@$q58_dtinc_dia,@$q58_dtinc_mes,@$q58_dtinc_ano,true,'text',1);
		  ?>
	  </td>
	</tr>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
	</tr>
	<td colspan="2" align="center"><input class='botao' type='submit'
		name='incluir' value='Incluir'></td>
	<tr>
		<td colspan="2" align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<table align="center" cellpadding="2" cellspacing="0" class="tab">
			<tr>
				<th align="center">Sequencial</th>
				<th align="center">Codigo</th>
				<th align="center">Atividade</th>
				<th align="center">Data inicial</th>
				<th align="center">Opção</th>
			</tr>
			<?

			if($linhascarrega>0){
			  for($i=0;$i<$linhascarrega;$i++){
			    db_fieldsmemory($resultcarrega,$i);
			    echo"<tr >";
			    echo"<td  align='center'  class='texto'>$seq</td>";
			    echo"<td  align='center'  class='texto'>$codigo</td>";
			    echo"<td  align='center'  class='texto'>$atividade</td>";
			    echo"<td  align='center'  class='texto'>".db_formatar($dataini,"d")."</td>";
			    echo"<td  align='center'  width='150px' >
			<input type='button' name='alterar1' value='Alterar' onClick='js_retornaDados(\"$seq\",\"$codigo\",\"$atividade\",\"$dataini\");' class='botao' >
			<input type='submit' name='excluir' value='Excluir'  onClick='document.form1.seqant.value=$seq' class='botao' >
			</td>
			</tr>";
			  }
			}
			?>
		</table>


		</td>
	</tr>

</table>
</form>
</center>
</body>
</html>
			<?
			if(isset($incluir)){
			  if($sqlerro == true){
			    db_msgbox($erro_msg);
			  }else{
			    db_msgbox("Inclusão efetuada com sucesso");
			    if($pessoa=='F'){
			      echo "<script>
							document.form1.rh70_estrutural.value='';
							document.form1.rh70_descr.value='';
							document.form1.rh70_sequencial.value='';
						 </script>"; 
			    }else{
			      echo "<script>
						    document.form1.q71_estrutural.value= '';
						    document.form1.q71_descr.value='';
						    document.form1.q72_sequencial.value='';
						</script>";     
			    }
			    echo "<script>
						//document.form1.q58_dtinc_dia.value='';
						//document.form1.q58_dtinc_mes.value='';
						//document.form1.q58_dtinc_ano.value='';
					 </script>"; 
			  }
			}
				
			if(isset($alterar)){
			  if($sqlerro == true){
			    db_msgbox($erro_msg);
			  }else{
			    db_msgbox("Alteração efetuada com sucesso");
			    if($pessoa=='F'){
			      echo "<script>
							document.form1.rh70_estrutural.value='';
							document.form1.rh70_descr.value='';
							document.form1.rh70_sequencial.value='';
						 </script>"; 
			    }else{
			      echo "<script>
						    document.form1.q71_estrutural.value= '';
						    document.form1.q71_descr.value='';
						    document.form1.q72_sequencial.value='';
						</script>";     
			    }
			    echo "<script>
						document.form1.q58_dtinc_dia.value='';
						document.form1.q58_dtinc_mes.value='';
						document.form1.q58_dtinc_ano.value='';
		        		document.form1.alterar.value = 'Incluir';
						document.form1.alterar.name  = 'incluir';		
					 </script>"; 
			     
			  }
			}
			
			if(isset($excluir)){
			  if($sqlerro == true){
			    db_msgbox($erro_msg);
			  }else{
			    db_msgbox("Exclução efetuada com sucesso");
			  }
			}
			?>
<script>
function js_retornaDados(seq,codigo,ativid,data){
var pes = document.form1.pessoa.value;
var datax = data.split('-');
		document.form1.q58_dtinc_dia.value=datax[2];
		document.form1.q58_dtinc_mes.value=datax[1];
		document.form1.q58_dtinc_ano.value=datax[0];
		document.form1.seqant.value=seq;
	if(pes=='F'){
		document.form1.rh70_estrutural.value=codigo;
		document.form1.rh70_descr.value=ativid;
		document.form1.rh70_sequencial.value=seq;
		js_pesquisa_cbo(false);
		
	}else{
		document.form1.q71_estrutural.value= codigo;
		document.form1.q71_descr.value=ativid;
		document.form1.q72_sequencial.value=seq;
		js_pesquisa_cnae(false);
		
	}

			
		document.form1.incluir.value = 'Alterar';
		document.form1.incluir.name  = 'alterar';
		
		
		
}



function js_pesquisa_cbo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cbo','func_rhcbo.php?funcao_js=parent.js_mostracbo|rh70_estrutural|rh70_descr|rh70_sequencial','Pesquisa',true);
  }else{
     if(document.form1.rh70_estrutural.value != ''){
     //alert(); 
        js_OpenJanelaIframe('','db_iframe_cbo','func_rhcbo.php?pesquisa_chave='+document.form1.rh70_estrutural.value+'&funcao_js=parent.js_mostracbo2','Pesquisa',false);
     }else{     
       document.form1.rh70_estrutural.value = ''; 
     }
  }
}
function js_mostracbo(estrutural,descr,sequencial){
    db_iframe_cbo.hide();
	document.form1.rh70_estrutural.value=estrutural;
	document.form1.rh70_descr.value=descr;
	document.form1.rh70_sequencial.value=sequencial;
 
}
function js_mostracbo2(estrutural,descr,sequencial,erro){
//alert(chave+' -- '+erro);
    document.form1.rh70_estrutural.value=estrutural;
	document.form1.rh70_descr.value=descr;
	document.form1.rh70_sequencial.value=sequencial;
 
 if(erro== true){
   document.form1.rh70_estrutural.value = '';
   document.form1.rh70_sequencial.value='';
  }
  //document.form1.submit();
  
}

function js_pesquisa_cnae(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cnae','func_cnae.php?funcao_js=parent.js_mostracnae|q71_estrutural|q71_descr|q72_sequencial','Pesquisa',true);
  }else{
     if(document.form1.q71_estrutural.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cnae','func_cnae.php?pesquisa_chave='+document.form1.q71_estrutural.value+'&funcao_js=parent.js_mostracnae2','Pesquisa',false);
     }else{
       document.form1.q71_estrutural.value = ''; 
     }
  }
}
function js_mostracnae(estrutural,descr,sequencial){
  if(sequencial == ''){
  	  alert('Selecione uma atividade do tipo "Analitico".');
  }else{
	  db_iframe_cnae.hide();
	  document.form1.q71_estrutural.value=estrutural;
	  document.form1.q71_descr.value=descr;
	  document.form1.q72_sequencial.value=sequencial;
  }

}
function js_mostracnae2(estrutural,descr,sequencial,erro){
 if(erro == false){
  if(sequencial == ''){
  	  alert('Selecione uma atividade do tipo "Analitico".');
  }else{
	  document.form1.q71_estrutural.value=estrutural;
	  document.form1.q71_descr.value=descr;
	  document.form1.q72_sequencial.value=sequencial;
  }
 }else{
      document.form1.q71_estrutural.value='';
	  document.form1.q71_descr.value = descr;
	  document.form1.q72_sequencial.value='';
 }
}
</script>