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
include("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_stdlibwebseller.php");
include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_ext_classe.php");
include("classes/db_prontcid_classe.php");
include("classes/db_prontprofatend_ext_classe.php");

include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");

db_postmemory($HTTP_POST_VARS);

$clprontuarios    = new cl_prontuarios;
$clprontproced    = new cl_prontproced_ext;
$clprontcid       = new cl_prontcid;
$clprontprofatend = new cl_prontprofatend_ext;
$clprontprocedcid = db_utils::getDao("prontprocedcid");


$db_opcao = 1;
$db_botao = true;
$db_botao1 = false;
$oSauConfig = loadConfig("sau_config");
$sd29_d_data_dia = date("d", time());
$sd29_d_data_mes = date("m", time());
$sd29_d_data_ano = date("Y", time());
$sd29_c_hora = date("H:i", time() );


//Pega Profissional de Atendimento
//echo "<BR> ".$clprontprofatend->sql_query_ext(null, "m.*, rhcbo.*, especmedico.*, medicos.*, prontprofatend.*", "s104_i_codigo", "s104_i_prontuario = $chavepesquisaprontuario") ;
$result_prontprofatend = pg_query( $clprontprofatend->sql_query_ext(null, "m.*, rhcbo.*, especmedico.*, medicos.*, prontprofatend.*", "s104_i_codigo", "s104_i_prontuario = $chavepesquisaprontuario") );
if( pg_num_rows($result_prontprofatend) > 0){
	$obj_prontprofatend = db_utils::fieldsMemory($result_prontprofatend,0);
	if( !isset($sd29_i_profissional)){
		if( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $obj_prontprofatend->s104_i_profissional) ){
	 		db_fieldsmemory($result_prontprofatend,0);
			if( !isset($incluir) && !isset($alterar) && !isset($excluir)){
	 			$sd29_i_profissional = $obj_prontprofatend->s104_i_profissional; 
	 		}	
		}
	}
}


if(isset($opcao)){
 $db_botao1 = true;
 $db_opcao = $opcao=="alterar"?2:3;

 $result = $clprontproced->sql_record($clprontproced->sql_query_ext($sd29_i_codigo));
 db_fieldsmemory($result,0);

}



if(isset($incluir)){
	if( isset($sd24_c_digitada) && $sd24_c_digitada == "N" ){
		db_inicio_transacao();
		if( pg_num_rows($result_prontprofatend) > 0){
			if( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $sd29_i_profissional) ){
				$clprontprofatend->s104_i_codigo       = $obj_prontprofatend->s104_i_codigo;
				$clprontprofatend->s104_i_prontuario   = $obj_prontprofatend->s104_i_prontuario;
				$clprontprofatend->s104_i_profissional = $sd29_i_profissional;
				$clprontprofatend->alterar($obj_prontprofatend->s104_i_codigo); 
				?><!-- atualiza aba paciente - profissional atendimento -->
				<script type="text/javascript">
				parent.iframe_a1.document.form1.sd03_i_codigo.value = '<?=$sd03_i_codigo?>';
				parent.iframe_a1.document.form1.z01_nome.value = '<?=$z01_nome ?>';
				parent.iframe_a1.document.form1.rh70_estrutural.value = '<?=$rh70_estrutural ?>';
				parent.iframe_a1.document.form1.rh70_descr.value = '<?=$rh70_descr ?>';			
				</script>
				<?
			}
		}
		$clprontproced->sd29_i_prontuario = $chavepesquisaprontuario;
		$clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
		$clprontproced->sd29_d_cadastro = date("Y-m-d",db_getsession("DB_datausu"));
		$clprontproced->sd29_c_cadastro = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
		$clprontproced->incluir("");
		if( (int)$sd70_i_codigo > 0 && $clprontproced->erro_status != '0'){
			$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
			$clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
			$clprontprocedcid->incluir(null); 
			if( $clprontprocedcid->numrows_incluir == 0){
				$clprontproced->erro_msg = $clprontprocedcid->erro_msg;
				$clprontproced->erro_status =  $clprontprocedcid->erro_status;
			}
		}     
		db_fim_transacao();
	}else{
		echo "<script>alert('FAA ja foi confirmada como DIGITADA, n�o ser� efetuada a inclus�o do procedimento.')</script>";
	}
}else if(isset($alterar)){
	db_inicio_transacao();
	if( pg_num_rows($result_prontprofatend) > 0){
		if( $clprontprofatend->sql_prontproced($chavepesquisaprontuario, $sd29_i_profissional) ){
			$clprontprofatend->s104_i_codigo       = $obj_prontprofatend->s104_i_codigo;
			$clprontprofatend->s104_i_prontuario   = $obj_prontprofatend->s104_i_prontuario;
			$clprontprofatend->s104_i_profissional = $sd29_i_profissional;
			$clprontprofatend->alterar($obj_prontprofatend->s104_i_codigo); 
			?><!-- atualiza aba paciente - profissional atendimento -->
			<script type="text/javascript">
			parent.iframe_a1.document.form1.sd03_i_codigo.value = '<?=$sd03_i_codigo?>';
			parent.iframe_a1.document.form1.z01_nome.value = '<?=$z01_nome ?>';
			parent.iframe_a1.document.form1.rh70_estrutural.value = '<?=$rh70_estrutural ?>';
			parent.iframe_a1.document.form1.rh70_descr.value = '<?=$rh70_descr ?>';			
			</script>
			<?
		}   
	}
	
	$clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
	$clprontproced->alterar($sd29_i_codigo);
	$clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
	if( (int)$sd70_i_codigo > 0 ){
		$clprontprocedcid->s135_i_prontproced = $clprontproced->sd29_i_codigo;
		$clprontprocedcid->s135_i_cid         = $sd70_i_codigo;
		$clprontprocedcid->incluir(null); 
		if( $clprontprocedcid->numrows_incluir == 0){
			$clprontproced->erro_msg = $clprontprocedcid->erro_msg;
			$clprontproced->erro_status =  $clprontprocedcid->erro_status;
		}
	}     
	
	db_fim_transacao();
}else if(isset($excluir)){
	db_inicio_transacao();
	/**
    //Verifica se � �ltimo procedimento
	$clprontproced->sql_record( $clprontproced->sql_query("","*","sd29_i_codigo","sd29_i_prontuario = $chavepesquisaprontuario") );
	if( $clprontproced->numrows == 1 ){
		//Remove todos os CID's
		$clprontcid->excluir(null, "sd55_i_prontuario = $chavepesquisaprontuario");
		//Atualiza o prontu�rio
		$clprontuarios->sd24_t_diagnostico = "'||null||'";
		$clprontuarios->sd24_c_digitada = "N";
		$clprontuarios->sd24_i_codigo = $chavepesquisaprontuario;
		$clprontuarios->alterar( $chavepesquisaprontuario );		
	}
    */
	$clprontprocedcid->excluir(null, "s135_i_prontproced = $sd29_i_codigo");
	
    $clprontproced->excluir($sd29_i_codigo);
	db_fim_transacao();
}else if(isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)){
   $result = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario, "prontuarios.*, rh70_descr as cbo_triagem, m.z01_nome as profissional_triagem"));
   db_fieldsmemory($result,0);
	 $res_proced = $clprontproced->sql_record($clprontproced->sql_query_nolote_ext(null,"prontuarios.*, cgs_und.*, medicos.*, m.*, rhcbo.*, prontproced.sd29_i_profissional ",null,"sd29_i_prontuario = $chavepesquisaprontuario"));
	 if( $clprontproced->numrows > 0){
	    db_fieldsmemory($res_proced,0);
	 }			   		
}else if(isset($emitirfaa)){
  die(">>>>".$chavepesquisaprontuario);
}




?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
try{
	db_app::load("scripts.js");
	db_app::load("prototype.js");
	db_app::load("strings.js");
	db_app::load("estilos.css");
}catch (Exception $eException){
	die( $eException->getMessage() );
}
?></head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfichaatendproced.php");
        ?>
    </center>
    </td>
  </tr>
 <tr>
</table>
<center>
<table>
<tr>
  <td valign="top" align="center"><br>
  <?
   $chavepri= array("sd29_i_codigo"=>@$sd29_i_codigo );

   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clprontproced->sql_query_ext("","*","sd29_i_codigo","sd29_i_prontuario = $chavepesquisaprontuario");
   $cliframe_alterar_excluir->campos  ="sd29_i_codigo,sd29_d_data,sd29_c_hora,sd29_i_procedimento,sd63_c_nome";
   $cliframe_alterar_excluir->legenda="Registro";
   $cliframe_alterar_excluir->alignlegenda="left";
   $cliframe_alterar_excluir->msg_vazio ="N�o foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
<table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd03_i_codigo",true,1,"sd03_i_codigo",true);
document.form1.sd24_i_unidade.value = parent.iframe_a1.document.form1.sd24_i_unidade.value;
document.form1.descrdepto.value = parent.iframe_a1.document.form1.descrdepto.value;
parent.document.formaba.a4.disabled = true;

</script>
<?
if(isset($incluir) || isset($alterar)){
  if($clprontproced->erro_status=="0"){
    $clprontproced->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontproced->erro_campo!=""){
      echo "<script> document.form1.".$clprontproced->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontproced->erro_campo.".focus();</script>";
    }
  }else{
    $clprontproced->erro(true,false);
    db_redireciona("sau4_fichaatendabas003.php?chavepesquisaprontuario=$chavepesquisaprontuario");
  }

}else if(isset($excluir)){
  if($clprontcid->erro_status=="0"){
  	$clprontcid->erro(true, false);
  }else if($clprontuarios->erro_status=="0"){
  	$clprontuarios->erro(true, false);
  }else {	
    $clprontproced->erro(true,false);
    db_redireciona("sau4_fichaatendabas003.php?chavepesquisaprontuario=$chavepesquisaprontuario");
  }
}else if( isset($prosseguir)){
	$clprontproced->sql_record( $cliframe_alterar_excluir->sql );
	if( $clprontproced->numrows > 0 ){
		?>
			<script>
				parent.document.formaba.a4.disabled = false;
				parent.iframe_a4.location.href='sau4_fichaatendabas004.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>&cgs=<?=$clprontuarios->sd24_i_numcgs?>&chaveprofissional=<?=@$sd29_i_profissional?>';
				parent.mo_camada('a4');
			</script>
		<?
	}else{
		echo "<script>
		         alert('Para informar o Diagn�stico, dever� ser lan�ado um procedimeto');
		      </script>";
	}
	
}
?>