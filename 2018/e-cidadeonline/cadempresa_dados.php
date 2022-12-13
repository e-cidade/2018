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
include("classes/db_dbprefcgm_classe.php");
include("classes/db_dbprefempresa_classe.php");
include("classes/db_dbprefempresajuridica_classe.php");
include("classes/db_dbprefempresafisica_classe.php");
include("classes/db_rhcbo_classe.php");
include("classes/db_cnae_classe.php");
include("classes/db_cnaeanalitica_classe.php");
include("classes/db_dbprefcgmrhcbo_classe.php");
include("classes/db_dbempresaatividade_classe.php");
include("classes/db_dbempresaatividaderhcbo_classe.php");
include("classes/db_dbprefcgmcnae_classe.php");
include("classes/db_dbprefempresaatividadecnae_classe.php");
include("classes/db_dbprefempresasocios_classe.php");
postmemory($HTTP_POST_VARS);
postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$usuario = db_getsession("DB_login");
$id      = $_SESSION["id"];
$cgm     = $_SESSION["dbprefcgm"];
//echo "dados <br>pessoa = $pessoa <br> cpf = $cpf_cnpj <br> ";
$cldbprefcgm              = new cl_dbprefcgm;
$cldbprefempresa          = new cl_dbprefempresa;
$cldbprefempresajuridica  = new cl_dbprefempresajuridica;
$cldbprefempresafisica    = new cl_dbprefempresafisica;
$clrhcbo                  = new cl_rhcbo;
$clcnae                   = new cl_cnae;
$clcnaeanalitica          = new cl_cnaeanalitica;
$cldbprefcgmrhcbo         = new cl_dbprefcgmrhcbo;
$cldbempresaatividade     = new cl_dbempresaatividade;
$cldbempresaatividaderhcbo= new cl_dbempresaatividaderhcbo;
$cldbprefcgmcnae          = new cl_dbprefcgmcnae;
$cldbprefempresaatividadecnae = new cl_dbprefempresaatividadecnae;
$cldbprefempresasocios    = new cl_dbprefempresasocios;
$clcnaeanalitica  -> rotulo->label();
$clcnae           -> rotulo->label();
$clrhcbo          -> rotulo->label();
if($cgm!=""){
  $opcao=2;
}else{
  $opcao=1;
}


//echo "atividades <br>pessoa = $pessoa <br> cpf = $cpf_cnpj <br> cgm = $cgm <br>opcao= $opcao<br> id = $id";

if(isset($incluir)){
  $sqlerro = false;
  
  if($z01_numero==""){
     $sqlerro = true;
     $erro_msg = "Campo Número do imóvel não informado.";
  }
  if($z01_munic==""){
     $sqlerro = true;
     $erro_msg = "Campo Município não informado.";
  }
  if($z01_bairro==""){
     $sqlerro = true;
     $erro_msg = "Campo Bairro não informado.";
  }
  if($z01_ender==""){
     $sqlerro = true;
     $erro_msg = "Campo Logradouro não informado.";
  }
  //########################## INCLUIR #############################
  
  //db_msgbox("incluir".$pessoa);
  // inclui em ambos... PJ e PF
  $cldbprefcgm -> z01_cep     = $z01_cep;
  $cldbprefcgm -> z01_ender   = $z01_ender;
  $cldbprefcgm -> z01_numero  = $z01_numero;
  $cldbprefcgm -> z01_compl   = $z01_compl;
  $cldbprefcgm -> z01_munic   = $z01_munic;
  $cldbprefcgm -> z01_bairro  = $z01_bairro;
  $cldbprefcgm -> z01_telef   = $z01_telef;
  $cldbprefcgm -> z01_fax     = $z01_fax;
  $cldbprefcgm -> z01_telcel  = $z01_telcel;
  $cldbprefcgm -> z01_email   = $z01_email;
  $cldbprefcgm -> z01_cxpostal= $z01_cxpostal;
  $cldbprefcgm -> z01_situacao= 1 ;
  //########################## INCLUIR FISICA #############################
  if($pessoa=='F'){

    //Física
    db_query('BEGIN');
    $pessoatab='0';
    if($z01_nasc_ano!="" && $z01_nasc_meso!="" && $z01_nasc_diao!=""){
      $z01_nasc = $z01_nasc_ano."-".$z01_nasc_mes."-".$z01_nasc_dia;
    }else{
      $z01_nasc ="";
    }
    $cldbprefcgm -> z01_nome   = "$z01_nome";
    $cldbprefcgm -> z01_mae    = "$z01_mae";
    $cldbprefcgm -> z01_pai    = "$z01_pai";
    $cldbprefcgm -> z01_nasc   = $z01_nasc;
    $cldbprefcgm -> z01_estciv = $z01_estciv;
    $cldbprefcgm -> z01_nacion = $z01_nacion;
    $cldbprefcgm -> z01_cgccpf = $cpf_cnpj;
    $cldbprefcgm -> incluir_dbpref(null) ;
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = "1-".$cldbprefcgm->erro_msg;
    }

    if($sqlerro == false){

      //dbprefempresa
      //$data = date("Y-m-d");
      if($q55_dtinc_ano!="" && $q55_dtinc_mes!="" && $q55_dtinc_dia!=""){
	      $q55_dtinc = $q55_dtinc_ano."-".$q55_dtinc_mes."-".$q55_dtinc_dia;
	    }else{
	      $q55_dtinc="";
	    }
      $cldbprefempresa -> q55_dbprefcgm    = $cldbprefcgm->z01_sequencial;
      $cldbprefempresa -> q55_usuario      = $id;
      $cldbprefempresa -> q55_tipo         = $pessoatab;
      $cldbprefempresa -> q55_dtinc        = $q55_dtinc;
      $cldbprefempresa -> q55_area         = "0";
      $cldbprefempresa -> q55_funcionarios = "0";
      $cldbprefempresa -> q55_inscant      = $q55_inscant;
      $cldbprefempresa -> q55_matric       = $q55_matric;
      $cldbprefempresa -> q55_recbrutaano  = $q55_recbrutaano;
      $cldbprefempresa -> incluir(null);
      if ($cldbprefempresa->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = "2-".$cldbprefempresa->erro_msg;
      }
    }
    if($sqlerro == false){
      //dbprefemprsafisica
      $cldbprefempresafisica -> q57_dbprefempresa = $cldbprefempresa -> q55_sequencial;
      $cldbprefempresafisica -> incluir(null);
      if ($cldbprefempresafisica->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = "3-".$cldbprefempresafisica->erro_msg;
      }
    }
    /*
     //dbprefcgmrhcbo
     $cldbprefcgmrhcbo -> z01_dbprefcgm =  $cldbprefcgm-> z01_sequencial;
     $cldbprefcgmrhcbo -> z01_hrcbo     =  $rh70_sequencial;
     $cldbprefcgmrhcbo -> incluir(null) ;
     if ($cldbprefcgmrhcbo->erro_status == 0) {
     $sqlerro = true;
     $erro_msg = $cldbprefcgmrhcbo->erro_msg;
     }
     */
    if($sqlerro==true){
      db_query('ROLLBACK');
    }else{
      db_query('COMMIT');
      $opcao=2;
    }

  }elseif($pessoa=='J'){
    //########################## INCLUIR JURIDICA #############################

    db_query('BEGIN');
    $pessoatab=1;
    $cldbprefcgm -> z01_cgccpf    = $cpf_cnpj;
    $cldbprefcgm -> z01_nome      = $z01_nome;
    $cldbprefcgm -> z01_nomefanta = $z01_nomefanta;
    $cldbprefcgm -> z01_tipcre    = $z01_tipcre;
    $cldbprefcgm -> z01_incest    = $z01_incest;
    $cldbprefcgm -> z01_contato   = $z01_contato;
    $cldbprefcgm -> incluir_dbpref(null) ;
    if ($cldbprefcgm->erro_status == 0) {
      $sqlerro = true;
      $erro_msg = "4-".$cldbprefcgm->erro_msg;
    }
    
    if($sqlerro==false){
	    //dbprefempresa
	    if($q55_dtinc_ano!="" && $q55_dtinc_mes!="" && $q55_dtinc_dia!=""){
	      $q55_dtinc = $q55_dtinc_ano."-".$q55_dtinc_mes."-".$q55_dtinc_dia;
	    }else{
	      $q55_dtinc="";
	    }
	    $cldbprefempresa -> q55_dbprefcgm    = $cldbprefcgm->z01_sequencial;
	    $cldbprefempresa -> q55_usuario      = $id;
	    $cldbprefempresa -> q55_tipo         = $pessoatab;
	    $cldbprefempresa -> q55_dtinc        = $q55_dtinc;
	    $cldbprefempresa -> q55_area         = "0";
	    $cldbprefempresa -> q55_funcionarios = "0";
	    $cldbprefempresa -> q55_inscant      = $q55_inscant;
	    $cldbprefempresa -> q55_matric       = $q55_matric;
      $cldbprefempresa -> q55_recbrutaano  = $q55_recbrutaano;
	    $cldbprefempresa -> incluir(null);
	    if ($cldbprefempresa->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = "5-".$cldbprefempresa->erro_msg;
	    }
    }
    if($sqlerro==false){
	    //dbprefempresajuridica
	    $cldbprefempresajuridica -> q56_dbprefempresa = $cldbprefempresa->q55_sequencial;
	    $cldbprefempresajuridica -> q56_rejuc         = $q56_rejuc;
	    $cldbprefempresajuridica -> incluir(null);
	    if ($cldbprefempresajuridica->erro_status == 0) {
	      $sqlerro = true;
	      $erro_msg = "6-".$cldbprefempresajuridica->erro_msg;
	    }
    }
    if($sqlerro==true){
      db_query('ROLLBACK');
    }else{
      db_query('COMMIT');
      $opcao=2;
    }
  }


}
//########################## ALTERAR #############################
if(isset($alterar)){

  $sqlerro= false;
  
  if($z01_numero==""){
     $sqlerro = true;
     $erro_msg = "Campo Número do imóvel não informado.";
  }
  if($z01_munic==""){
     $sqlerro = true;
     $erro_msg = "Campo Município não informado.";
  }
  if($z01_bairro==""){
     $sqlerro = true;
     $erro_msg = "Campo Bairro não informado.";
  }
  if($z01_ender==""){
     $sqlerro = true;
     $erro_msg = "Campo Logradouro não informado.";
  }
  
  // inclui em ambos... PJ e PF
  $cldbprefcgm -> z01_cep     = $z01_cep;
  $cldbprefcgm -> z01_ender   = $z01_ender;
  $cldbprefcgm -> z01_numero  = $z01_numero;
  $cldbprefcgm -> z01_compl   = $z01_compl;
  $cldbprefcgm -> z01_munic   = $z01_munic;
  $cldbprefcgm -> z01_bairro  = $z01_bairro;
  $cldbprefcgm -> z01_telef   = $z01_telef;
  $cldbprefcgm -> z01_fax     = $z01_fax;
  $cldbprefcgm -> z01_telcel  = $z01_telcel;
  $cldbprefcgm -> z01_email   = $z01_email;
  $cldbprefcgm -> z01_cxpostal= $z01_cxpostal;
  $cldbprefcgm -> z01_situacao= 1 ;

  if($pessoa=='F'){
    //########################## ALTERAR FISICA #############################
    $sqlalt= "select dbprefcgm.z01_sequencial as cgm_seq,q55_sequencial
					from dbprefcgm
					inner join dbprefempresa on z01_sequencial=q55_dbprefcgm 
					inner join dbprefempresafisica on q55_sequencial = q57_dbprefempresa 
					where z01_cgccpf= '".$cpf_cnpj."'";
    $resultalt = db_query($sqlalt);
    $linhasalt = pg_num_rows($resultalt);
    if($linhasalt>0){
      db_fieldsmemory($resultalt,0);

      db_query('BEGIN');
      if($z01_nasc_ano!="" && $z01_nasc_mes!="" && $z01_nasc_dia!=""){
        $z01_nasc = $z01_nasc_ano."-".$z01_nasc_mes."-".$z01_nasc_dia;
      }else{
        $z01_nasc ="";
      }
      $cldbprefcgm -> z01_nome   = "$z01_nome";
      $cldbprefcgm -> z01_mae    = "$z01_mae";
      $cldbprefcgm -> z01_pai    = "$z01_pai";
      $cldbprefcgm -> z01_nasc   = $z01_nasc;
      $cldbprefcgm -> z01_estciv = $z01_estciv;
      $cldbprefcgm -> z01_nacion = $z01_nacion;
      $cldbprefcgm -> z01_sequencial = $cgm_seq;
      $cldbprefcgm -> alterar($cgm_seq) ;
      if ($cldbprefcgm->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefcgm->erro_msg;
      }
      //dbprefempresa
      $cldbprefempresa -> q55_sequencial   = $q55_sequencial;
      //$cldbprefempresa -> q55_area         = $q55_area;
     // $cldbprefempresa -> q55_funcionarios = $q55_funcionarios;
      $cldbprefempresa -> q55_inscant      = $q55_inscant;
      $cldbprefempresa -> q55_matric       = $q55_matric;
      $cldbprefempresa -> q55_recbrutaano  = $q55_recbrutaano;
      $cldbprefempresa -> alterar($q55_sequencial);
      if ($cldbprefempresa->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefempresa->erro_msg;
      }

      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');
        $opcao=2;
      }
    }
  }elseif($pessoa=='J'){
    
    //########################## ALTERAR JURIDICA #############################
    $sqlalt = "
			select dbprefcgm.z01_sequencial as cgm_seq,q55_sequencial,q56_sequencial
			from dbprefcgm 
			inner join dbprefempresa on z01_sequencial=q55_dbprefcgm 
			inner join dbprefempresajuridica on q55_sequencial = q56_dbprefempresa 
			where z01_cgccpf= '".$cpf_cnpj."'";
    //die($sqlalt);
    $resultalt = db_query($sqlalt);
    $linhasalt = pg_num_rows($resultalt);
    if($linhasalt>0){
      db_fieldsmemory($resultalt,0);

      db_query('BEGIN');

      $cldbprefcgm -> z01_nome      = $z01_nome;
      $cldbprefcgm -> z01_nomefanta = $z01_nomefanta;
      $cldbprefcgm -> z01_tipcre    = $z01_tipcre;
      $cldbprefcgm -> z01_incest    = $z01_incest;
      $cldbprefcgm -> z01_contato   = $z01_contato;
      $cldbprefcgm -> z01_sequencial= $cgm_seq;
      $cldbprefcgm -> alterar($cgm_seq) ;
      if ($cldbprefcgm->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefcgm->erro_msg;
      }
      //dbprefempresa,

      $cldbprefempresa -> q55_sequencial   = $q55_sequencial;
      // $cldbprefempresa -> q55_area         = $q55_area;
      //$cldbprefempresa -> q55_funcionarios = $q55_funcionarios;
      $cldbprefempresa -> q55_inscant      = $q55_inscant;
      $cldbprefempresa -> q55_matric       = $q55_matric;
      $cldbprefempresa -> q55_recbrutaano  = $q55_recbrutaano;
      $cldbprefempresa -> alterar($q55_sequencial);
      if ($cldbprefempresa->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefempresa->erro_msg;
      }
      //dbprefempresajuridica
      $cldbprefempresajuridica -> q56_sequencial   = $q56_sequencial;
      $cldbprefempresajuridica -> q56_rejuc        = $q56_rejuc;
      $cldbprefempresajuridica -> alterar($q56_sequencial);
      if ($cldbprefempresajuridica->erro_status == 0) {
        $sqlerro = true;
        $erro_msg = $cldbprefempresajuridica->erro_msg;
      }
      if($sqlerro==true){
        db_query('ROLLBACK');
      }else{
        db_query('COMMIT');
        $opcao=2;
      }
    }
  }
}

if(isset($excluir)){
  
  
  
  $sqlerro = false;
  db_query('BEGIN');
  if($pessoa=='F'){
     
    $sqlati = "select q58_sequencial    as dbempresaatividade,
					        q59_sequencial    as dbempresaatividaderhcbo
					from dbempresaatividade  
					left join dbempresaatividaderhcbo on q59_dbempresaatividade = q58_sequencial
					where q58_dbprefempresa = $dbprefempresa";
		  $resultati = db_query($sqlati);
		  $linhasati = pg_num_rows($resultati);
		  if($linhasati>0){
		    for($a=0;$a<$linhasati;$a++){
		      db_fieldsmemory($resultati,$a);
		      $cldbempresaatividaderhcbo -> q59_sequencial = $dbempresaatividaderhcbo;
		      $cldbempresaatividaderhcbo -> excluir($dbempresaatividaderhcbo);
		      if ($cldbempresaatividaderhcbo->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbempresaatividaderhcbo->erro_msg;
		      }
		       
		      $cldbempresaatividade -> q58_sequencial = $dbempresaatividade;
		      $cldbempresaatividade -> excluir($dbempresaatividade);
		      if ($cldbempresaatividade->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbempresaatividade->erro_msg;
		      }
		       
		    }
		  }

		  $sqlcbo = "	select dbprefcgmrhcbo.z01_sequencial as dbprefcgmrhcbo
						from dbprefcgmrhcbo
						where z01_dbprefcgm = $cgm";
		  $resultcbo = db_query($sqlcbo);
		  $linhascbo = pg_num_rows($resultcbo);
		  if($linhascbo>0){
		    for($b=0;$b<$linhascbo;$b++){
		      db_fieldsmemory($resultcbo,$b);

		      $cldbprefcgmrhcbo -> z01_sequencial =  $dbprefcgmrhcbo;
		      $cldbprefcgmrhcbo -> excluir($dbprefcgmrhcbo);
		      if ($cldbprefcgmrhcbo->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbprefcgmrhcbo->erro_msg;
		      }
		    }
		  }

		  $sqlemp= "
			select 	dbprefcgm.z01_sequencial as dbprefcgm,
				q55_sequencial           as dbprefempresa,
				q57_sequencial           as dbprefempresafisica
			from dbprefcgm 
			inner join dbprefempresa       on dbprefcgm.z01_sequencial=q55_dbprefcgm 
			inner join dbprefempresafisica on q55_sequencial = q57_dbprefempresa 
			where z01_cgccpf= '".$cpf_cnpj."'";
		  //die($sqlemp);
		  $resultemp = db_query($sqlemp);
		  $linhasemp = pg_num_rows($resultemp);
		  if($linhasemp>0){
		    db_fieldsmemory($resultemp,0);
		     
		    $cldbprefempresafisica -> q57_sequencial =  $dbprefempresafisica;
		    $cldbprefempresafisica -> excluir($dbprefempresafisica)  ;
		    if ($cldbprefempresafisica->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefempresafisica->erro_msg;
		    }
		    $cldbprefempresa -> q55_sequencial = $dbprefempresa;
		    $cldbprefempresa -> excluir($dbprefempresa);
		    if ($cldbprefempresa->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefempresa->erro_msg;
		    }

		    $cldbprefcgm -> z01_sequencial = $dbprefcgm;
		    $cldbprefcgm -> excluir($dbprefcgm);
		    if ($cldbprefcgm->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefcgm->erro_msg;
		    }

		     
		  }
		   
		  if($sqlerro==true){
		    db_query('ROLLBACK');
		  }else{
		    db_query('COMMIT');
		    $opcao=2;
		  }
		   
  }else{
    // ################ JURIDICA #######################
    $sqlerro = false;
    db_query('BEGIN');

    $sqlati="
		select q58_sequencial            as dbempresaatividade,
		       q65_sequencial            as dbempresaatividadecnae
		from dbempresaatividade  
		left join dbprefempresaatividadecnae on q65_dbempresaatividade = q58_sequencial
		where q58_dbprefempresa = $dbprefempresa";
    $resultati = db_query($sqlati);
    $linhasati = pg_num_rows($resultati);
    if($linhasati>0){
		    for($c=0;$c<$linhasati;$c++){
		      db_fieldsmemory($resultati,$c);
		      //  echo "<br>dbempresaatividade = $dbempresaatividade
		      //		 <br>dbempresaatividadecnae=$dbempresaatividadecnae";
		      $cldbprefempresaatividadecnae -> q65_sequencial = $dbempresaatividadecnae;
		      $cldbprefempresaatividadecnae -> excluir($dbempresaatividadecnae) ;
		      if ($cldbprefempresaatividadecnae->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbprefempresaatividadecnae->erro_msg;
		      }
		      $cldbempresaatividade -> q58_sequencial = $dbempresaatividade;
		      $cldbempresaatividade -> excluir($dbempresaatividade) ;
		      if ($cldbempresaatividade->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbempresaatividade->erro_msg;
		      }
		    }
    }
    $sqlcnae="
		select dbprefcgmcnae.z01_sequencial as dbprefcgmcnae
		from dbprefcgmcnae
		where z01_dbprefcgm = $cgm";
    $resultcnae = db_query($sqlcnae);
    $linhascnae = pg_num_rows($resultcnae);
    if($linhascnae>0){
		    for($d=0;$d<$linhascnae;$d++){
		      db_fieldsmemory($resultcnae,$d);
		      //echo "<br>dbprefcgmcnae=$dbprefcgmcnae";
		      $cldbprefcgmcnae-> z01_sequencial = $dbprefcgmcnae;
		      $cldbprefcgmcnae-> excluir($dbprefcgmcnae);
		      if ($cldbprefcgmcnae->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbprefcgmcnae->erro_msg;
		      }
		    }
    }
    $sqlsoc="
		select q66_sequencial as dbprefempresasocios
		from dbprefempresasocios
		where q66_dbprefempresa = $dbprefempresa";
    $resultsoc = db_query($sqlsoc);
    $linhassoc = pg_num_rows($resultsoc);
    if($linhassoc>0){
		    for($e=0;$e<$linhassoc;$e++){
		      db_fieldsmemory($resultsoc,$e);
		      //echo "<br>dbprefempresasocios=$dbprefempresasocios";
		      $cldbprefempresasocios -> q66_sequencial = $dbprefempresasocios;
		      $cldbprefempresasocios -> excluir($dbprefempresasocios);
		      if ($cldbprefempresasocios->erro_status == 0) {
		        $sqlerro = true;
		        $erro_msg = $cldbprefempresasocios->erro_msg;
		      }
		    }
    }
    $sqlemp ="
		select 	dbprefcgm.z01_sequencial as dbprefcgm,
			q55_sequencial           as dbprefempresa,
			q56_sequencial           as dbprefempresajuridica
		from dbprefcgm 
		inner join dbprefempresa       on dbprefcgm.z01_sequencial=q55_dbprefcgm 
		inner join dbprefempresajuridica on q55_sequencial = q56_dbprefempresa 
		where z01_cgccpf= '".$cpf_cnpj."'";
    $resultemp = db_query($sqlemp);
		  $linhasemp = pg_num_rows($resultemp);
		  if($linhasemp>0){
		    db_fieldsmemory($resultemp,0);
		    /* echo "<br>dbprefcgm = $dbprefcgm
		     <br>dbprefempresa = $dbprefempresa
		     <br>dbprefempresajuridica=$dbprefempresajuridica";*/
		    $cldbprefempresajuridica -> q57_sequencial = $dbprefempresajuridica ;
		    $cldbprefempresajuridica -> excluir($dbprefempresajuridica)  ;
		    if ($cldbprefempresajuridica->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefempresajuridica->erro_msg;
		    }
		    $cldbprefempresa -> q55_sequencial = $dbprefempresa;
		    $cldbprefempresa -> excluir($dbprefempresa);
		    if ($cldbprefempresa->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefempresa->erro_msg;
		    }

		    $cldbprefcgm -> z01_sequencial = $dbprefcgm;
		    $cldbprefcgm -> excluir($dbprefcgm);
		    if ($cldbprefcgm->erro_status == 0) {
		      $sqlerro = true;
		      $erro_msg = $cldbprefcgm->erro_msg;
		    }

		  }

		  if($sqlerro==true){
		    db_query('ROLLBACK');
		  }else{
		    db_query('COMMIT');
		    $opcao=2;
		  }
  }
}
if($pessoa=='F'){

  //left join dbprefcgmrhcbo on z01_dbprefcgm = dbprefcgm.z01_sequencial
  //left join rhcbo on z01_hrcbo = rh70_sequencial
  $sqlcarrega = "select * from dbprefcgm
inner join dbprefempresa on z01_sequencial=q55_dbprefcgm 
inner join dbprefempresafisica on q55_sequencial = q57_dbprefempresa 
where z01_cgccpf= '".$cpf_cnpj."'";

}elseif($pessoa=='J'){
  $sqlcarrega = "
select * from dbprefcgm 
inner join dbprefempresa on z01_sequencial=q55_dbprefcgm 
inner join dbprefempresajuridica on q55_sequencial = q56_dbprefempresa 
where z01_cgccpf= '".$cpf_cnpj."'";
}
//echo "<br>$sqlcarrega<br>";

$resultcarrega = db_query($sqlcarrega);
$linhascarrega = pg_num_rows($resultcarrega);
if($linhascarrega>0){
  db_fieldsmemory($resultcarrega,0);

}

?>

<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<style type="text/css"><?db_estilosite();?></style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"
	bgcolor="<?=$w01_corbody?>">
<center>
<form name="form1" method="post" action=""><input name="pessoa"
	type="hidden" value="<?=@$pessoa?>">
<table width="700px" border="0" cellspacing="2" cellpadding="2"	class="texto">

	<tr>
		<td width="200px">&nbsp;</td>
		<td width="500px">&nbsp;</td>
	</tr>
	<tr>
		<td width="100px"><B>Pessoa: <?=($pessoa=='F'?'Física':'Jurídica') ?></B></td>
		<?if($pessoa=="F"){
		  $portefis='t';
		  echo"<td width='500px'><B>CPF ".db_formatar($cpf_cnpj,"cpf")."</B></td>";
		}else{
		  $portefis='f';
		  echo"<td width='500px'><B>CNPJ: ".db_formatar($cpf_cnpj,"cnpj")."</B></td>";
		}
		?>

	</tr>
	

	<?if($pessoa=="F"){?>

	<tr>
		<td>*Nome:</td>
		<td><input name="z01_nome" type="text" value="<?=@$z01_nome?>" size="60"></td>
	</tr>
	<tr>
		<td>Mãe:</td>
		<td><input name="z01_mae" type="text" value="<?=@$z01_mae?>" size="60"></td>
	</tr>
	<tr>
		<td>Pai:</td>
		<td><input name="z01_pai" type="text" value="<?=@$z01_pai?>" size="60"></td>
	</tr>
	<tr>
		<td>Nascimento:</td>
		<?
		//2006-05-01
		if(@$z01_nasc!=""){
		  $z01_nasc_dia = substr($z01_nasc,8,2);
		  $z01_nasc_mes = substr($z01_nasc,5,2);
		  $z01_nasc_ano = substr($z01_nasc,0,4);
		}
		?>
		<td><? db_inputdata("z01_nasc",@$z01_nasc_dia,@$z01_nasc_mes,@$z01_nasc_ano, true, 'text', 1);?></td>
	</tr>
	<tr>
		<td>Estado Civil:</td>
		<td><select name="z01_estciv">
			<option value="1" <?=(@$z01_estciv=="1")?"selected":""?>>Solteiro</option>
			<option value="2" <?=(@$z01_estciv=="2")?"selected":""?>>Casado</option>
			<option value="3" <?=(@$z01_estciv=="3")?"selected":""?>>Viúvo</option>
			<option value="4" <?=(@$z01_estciv=="4")?"selected":""?>>Divorciado</option>
		</select></td>
	</tr>

	<tr>
		<td>Nacionalidade:</td>
		<td><select name="z01_nacion">
			<option value="1" <?=(@$z01_nacion=="1")?"selected":""?>>Brasileiro</option>
			<option value="2" <?=(@$z01_nacion=="2")?"selected":""?>>Estrangeiro</option>
		</select></td>
	</tr>
	<?}else{?>
	<tr>
		<td>*Razão social:</td>
		<td><input name="z01_nome" type="text" value="<?=@$z01_nome?>" size="60"></td>
	</tr>
	<tr>
		<td>*Nome fantasia:</td>
		<td><input name="z01_nomefanta" type="text" value="<?=@$z01_nomefanta?>" size="60"></td>
	</tr>
	<tr>
		<td>*Tipo de credor:</td>
		<td><select name="z01_tipcre">
			<option value="2" <?=(@$z01_tipcre=="2")?"selected":""?>>Empresa privada</option>
			<option value="1" <?=(@$z01_tipcre=="1")?"selected":""?>>Empresa pública</option>
		</select></td>
	</tr>
	<tr>
		<td>Inscrição estadual:</td>
		<td><input name="z01_incest" type="text" value="<?=@$z01_incest?>"  size="20"></td>
	</tr>
	<tr>
		<td>Contato:</td>
		<td><input name="z01_contato" type="text" value="<?=@$z01_contato?>" size="60"></td>
	</tr>
	<tr>
		<td>*Registro na junta comercial:</td>
		<td><input name="q56_rejuc" type="text" value="<?=@$q56_rejuc?>" size="20"></td>
	</tr>
	
	<?}?>
  <tr>
		<td>*Data de início:</td>
		<td><? db_inputdata("q55_dtinc",@$q55_dtinc_dia,@$q55_dtinc_mes,@$q55_dtinc_ano ,true, 'text', 1);?></td>

	</tr>
	<tr>
		<td>Inscrição Municipal do Imóvel:</td>
		<td><input name="q55_matric" type="text" value="<?=@$q55_matric?>" size="15" ></td>

	</tr>
	<tr>
		<td><?db_ancora("CEP","js_pesquisa_cep(true);",1);?></td>
		<td><input name="z01_cep" type="text" value="<?=@$z01_cep?>" size="15" onchange='js_pesquisa_cep(false);'></td>

	</tr>
	<tr>
		<td>*Logradouro:</td>
		<td><input name="z01_ender" id="z01_ender" ntype="text" value="<?=@$z01_ender?>" size="50"></td>
	</tr>
	<tr>
		<td>*Número do imóvel:</td>
		<td><input name="z01_numero" type="text" value="<?=@$z01_numero?>" size="20"> 
		Complemento do imóvel: <input name="z01_compl" type="text" value="<?=@$z01_compl?>" size="20"></td>
	</tr>
	<tr>
		<td>*Município:</td>
		<td><input name="z01_munic" type="text" value="<?=@$z01_munic?>"
			size="60"></td>
	</tr>
	<tr>
		<td>*Bairro</td>
		<td><input name="z01_bairro" type="text" value="<?=@$z01_bairro?>" size="30"> 
		  Caixa Postal: <input name="z01_cxpostal" type="text" value="<?=@$z01_cxpostal?>" size="20"></td>
	</tr>
	<tr>
		<td>Telefone:</td>
		<td><input name="z01_telef" type="text" value="<?=@$z01_telef?>" size="20"> 
		 Fax: <input name="z01_fax" type="text"	value="<?=@$z01_fax?>" size="20"></td>
	</tr>
	<tr>
		<td>Mail:</td>
		<td><input name="z01_email" type="text" value="<?=@$z01_email?>" size="40"> 
		Celular: <input name="z01_telcel" type="text"	value="<?=@$z01_telcel?>" size="20"></td>
	</tr>
	<tr>
		<td>CMC Anterior:</td>
		<td><input name="q55_inscant" type="text" value="<?=@$q55_inscant?>" size="20"></td>
	</tr>
	<tr>
		<td>Receita Bruta anual estimada:</td>
		<td><input name="q55_recbrutaano" type="text" value="<?=@$q55_recbrutaano?>" size="20"></td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<?
		if($opcao==2){
		  echo"<input class='botao' type='submit' name='alterar' value='Alterar'>&nbsp;";
		  echo"<input class='botao' type='submit' name='excluir' value='Excluir' onclick='return js_confirma()'>";
		}else{
		  echo"<input class='botao' type='submit' name='incluir' value='Incluir'>";
		}
		?>
		<input class='botao' type='submit' name='voltar' value='Voltar' onclick="parent.location.href = 'cadempresa001.php';">
		</td>
	</tr>
  <tr>
		<td colspan="2">(*) - campos de preenchimento obrigatório</td>
	</tr>
	<tr>
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
		    $cgm = $cldbprefcgm->z01_sequencial;
		    $dbprefempresa = $cldbprefempresa ->q55_sequencial;
		    $_SESSION["dbprefcgm"] = $cgm;
		    $_SESSION["dbprefempresa"] = $dbprefempresa;
		    echo "<script>
				
           		parent.document.form1.disabilitado.value='nao';
				parent.trocacor('2');
				alert('Inclusão efetuada com sucesso.');
			</script>
		";

		  }
		}elseif(isset($alterar)){
		  if($sqlerro == true){
		    db_msgbox($erro_msg);
		  }else{
		    db_msgbox('Alteração efetuada com sucesso.');
		  }
		}elseif(isset($excluir)){
		  if($sqlerro == true){
		    db_msgbox($erro_msg);
		  }else{
		    db_msgbox('Exclusão efetuada com sucesso.');
		    echo "<script> parent.location.href = 'cadempresa001.php' </script>";
		  }
		}


		if($opcao==2){

	   echo "<script>
				//alert('abilita');
           		parent.document.form1.disabilitado.value='nao';
			</script>
		";
		}
		?>
<script>	

function js_pesquisa_cep(mostra){
  if(mostra==true){
  
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro','Pesquisa',true);
  }else{
     if(document.form1.z01_cep.value != ''){
        js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_cep.value+'&funcao_js=parent.js_preenchecep|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|codigo','Pesquisa',false);
     }else{     
       document.form1.z01_cep.value = ''; 
     }
  }
}


function js_preenchecep(chave,chave1,chave2,chave3,chave4){

  document.form1.z01_cep.value = chave;
  document.form1.z01_ender.value = chave1;
  document.form1.z01_munic.value = chave2;
  document.form1.z01_bairro.value = chave4;
  if(chave1!=""){
  	document.form1.z01_ender.readOnly = true;
  	document.form1.z01_ender.style.backgroundColor = '#CCCCCC';
  }
  if(chave2!=""){
  	document.form1.z01_munic.readOnly = true;
  	document.form1.z01_munic.style.backgroundColor = '#CCCCCC';
  }
  if(chave4!=""){
  	document.form1.z01_bairro.readOnly = true;
  	document.form1.z01_bairro.style.backgroundColor = '#CCCCCC';
  }

  
  db_iframe_cep.hide();
}
function js_confirma(){

var confirma = confirm("Confirma a exclusão ?");
    if(confirma==true){
      return true;
    }else{
     return false;
	}
}
</script>