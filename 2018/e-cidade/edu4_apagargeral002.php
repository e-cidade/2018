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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$claluno                     = new cl_aluno;
$clalunobairro               = new cl_alunobairro;
$clalunocurso                = new cl_alunocurso;
$clalunopossib               = new cl_alunopossib;
$clalunonecessidade          = new cl_alunonecessidade;
$cltelefonealuno             = new cl_telefonealuno;
$clalunoprimat               = new cl_alunoprimat;
$clalunoaltconf              = new cl_alunoaltconf;
$clalunoaltcampos            = new cl_alunoaltcampos;
$clalunoalt                  = new cl_alunoalt;
$cldocaluno                  = new cl_docaluno;
$cllogexcgeral               = new cl_logexcgeral;
$cltransfescolafora          = new cl_transfescolafora;
$cltransfescolarede          = new cl_transfescolarede;
$cltrocaserie                = new cl_trocaserie;
$clatestvaga                 = new cl_atestvaga;
$cllogmatricula              = new cl_logmatricula;
$oDaoAlunoCidadao            = new cl_alunocidadao();
$oDaoAlunoCidadaoContato     = new cl_alunocidadaocontato();
$oDaoAlunoCidadaoResponsavel = new cl_alunocidadaoresponsavel();

$db_opcao = 1;
$db_botao = true;

if ( isset( $excluir ) ) {
  
  if ( isset( $alunos ) ) {
    
    $erroexc = false;
    db_inicio_transacao();
    
    $codalunos = explode(",",$alunos);
    
    for ( $x = 0; $x < count( $codalunos ); $x++) {
      
      $clalunopossib->excluir(""," ed79_i_alunocurso in (select ed56_i_codigo from alunocurso where ed56_i_aluno = $codalunos[$x])");
      if ( $clalunopossib->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunopossib->erro_msg;
        break; 	
      }
        
      $clalunocurso->excluir(""," ed56_i_aluno = $codalunos[$x]");
      if ( $clalunocurso->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunocurso->erro_msg;    
        break; 	
      }
        
      $cltransfescolarede->excluir(""," ed103_i_atestvaga in (select ed102_i_codigo from atestvaga where ed102_i_aluno = $codalunos[$x])");
      if ( $cltransfescolarede->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cltransfescolarede->erro_msg;    
        break; 	
      }
        
      $clatestvaga->excluir(""," ed102_i_aluno = $codalunos[$x]");
      if ( $clatestvaga->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clatestvaga->erro_msg;    
        break; 	
      }
        
      $cltransfescolafora->excluir(""," ed104_i_aluno = $codalunos[$x]");
      if ( $cltransfescolafora->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cltransfescolafora->erro_msg;    
        break; 	
      }
        
      $cltrocaserie->excluir(""," ed101_i_aluno = $codalunos[$x]");
      if ( $cltrocaserie->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cltrocaserie->erro_msg;
        break; 	
      }
        
      $cllogmatricula->excluir(""," ed248_i_aluno = $codalunos[$x]");
      if ( $cllogmatricula->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cllogmatricula->erro_msg;    
        break; 	
      }
        
      $clalunobairro->excluir(""," ed225_i_aluno = $codalunos[$x]");
      if ( $clalunobairro->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunobairro->erro_msg;    
        break; 	
      }
      
      $clalunonecessidade->excluir(""," ed214_i_aluno = $codalunos[$x]");
      if ( $clalunonecessidade->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunonecessidade->erro_msg;    
        break; 	
      }
      
      $cltelefonealuno->excluir(""," ed50_i_aluno = $codalunos[$x]");
      if ( $cltelefonealuno->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cltelefonealuno->erro_msg;    
        break; 	
      }
      
      $clalunoprimat->excluir(""," ed76_i_aluno = $codalunos[$x]");
      if ( $clalunoprimat->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunoprimat->erro_msg;    
        break; 	
      }
      
      $cldocaluno->excluir(""," ed49_i_aluno = $codalunos[$x]");
      if ( $cldocaluno->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $cldocaluno->erro_msg;    
        break; 	
      }
      
      $result5 = db_query("select ed275_i_codigo as codigoalt from alunoalt where ed275_i_aluno = $codalunos[$x]");
      $linhas5 = pg_num_rows($result5);
      
      for ( $t = 0; $t < $linhas5; $t++) {
      	
        db_fieldsmemory($result5,$t);
        $clalunoaltcampos->excluir(""," ed276_i_alunoalt = $codigoalt");
        if ( $clalunoaltcampos->erro_status == "0") {
          
          $erroexc = true;
          $msgerro = $clalunoaltcampos->erro_msg;
          break; 	
        }
        
        $clalunoaltconf->excluir(""," ed277_i_alunoalt = $codigoalt");
        if ( $clalunoaltconf->erro_status == "0") {
          
          $erroexc = true;
          $msgerro = $clalunoaltconf->erro_msg;     
          break; 	
        }
      }
      
      $clalunoalt->excluir(""," ed275_i_aluno = $codalunos[$x]");
      if ( $clalunoalt->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $clalunoalt->erro_msg;    
        break; 	
      }
      
      $oDaoAlunoCidadao->excluir( null, "ed330_aluno = {$codalunos[$x]}" );
      if ( $oDaoAlunoCidadao->erro_status == "0") {
      
        $erroexc = true;
        $msgerro = $oDaoAlunoCidadao->erro_msg;
        break;
      }
      
      $oDaoAlunoCidadaoContato->excluir( null, "ed332_aluno = {$codalunos[$x]}" );
      if ( $oDaoAlunoCidadaoContato->erro_status == "0") {
      
        $erroexc = true;
        $msgerro = $oDaoAlunoCidadaoContato->erro_msg;
        break;
      }
      
      $oDaoAlunoCidadaoResponsavel->excluir( null, "ed331_aluno = {$codalunos[$x]}" );
      if ( $oDaoAlunoCidadaoResponsavel->erro_status == "0") {
      
        $erroexc = true;
        $msgerro = $oDaoAlunoCidadaoResponsavel->erro_msg;
        break;
      }
      
      $claluno->excluir($codalunos[$x]);
      if ( $claluno->erro_status == "0") {
        
        $erroexc = true;
        $msgerro = $claluno->erro_msg;    
        break; 	
      }
    }
    
    $evento = "EXCLUSÃO GERAL DE ALUNOS";
    $descricao = "Código(s) excluídos(s): $alunos";
    $cllogexcgeral->ed256_i_usuario = db_getsession("DB_id_usuario");
    $cllogexcgeral->ed256_i_escola  = db_getsession("DB_coddepto");
    $cllogexcgeral->ed256_d_data    = date("Y-m-d");
    $cllogexcgeral->ed256_c_hora    = date("H:i");
    $cllogexcgeral->ed256_c_evento  = $evento;
    $cllogexcgeral->ed256_t_descr   = $descricao;
    $cllogexcgeral->incluir(null);
    if($cllogexcgeral->erro_status=="0"){
     $erroexc = true;
     $msgerro = $cllogexcgeral->erro_msg;   
    }    
    db_fim_transacao();
    if($erroexc==true){
     db_msgbox("ERRO NA EXCLUSÃO: ".$msgerro);	
    }else{
     db_msgbox("Exclusão efetuada com sucesso!");  	
    }
    db_redireciona("edu4_apagargeral002.php");
    exit;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<form name="form1" method="POST">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <br>
   <fieldset style="width:95%"><legend><b>Exclusão Geral de Alunos</b></legend>
    <?if(!isset($pesquisar)){?>
     <input type="button" value="Buscar Alunos" name="pesquisar" onclick="location.href='edu4_apagargeral002.php?pesquisar'">
    <?}else{?>
      <table border="0" cellspacing="0" width="100%">
	<tr>
	<td>
	  <b>Alunos sem movimentação no sistema:</b><br>
	  <?
	  $sql_aluno = "SELECT DISTINCT ed47_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome
			FROM aluno
			left join historico        on ed47_i_codigo = ed61_i_aluno
			left join matricula        on ed47_i_codigo = ed60_i_aluno
			left join leitoraluno      on ed47_i_codigo = bi11_aluno
			left join diario           on ed47_i_codigo = ed95_i_aluno
			WHERE ed61_i_aluno is null
			AND ed60_i_aluno is null
			AND bi11_aluno is null
			AND ed95_i_aluno is null
			ORDER BY ed47_v_nome
		      ";
	  $result_aluno = db_query($sql_aluno);
	  $linhas_aluno = pg_num_rows($result_aluno);
	  if($linhas_aluno==0){
	  $x = array(''=>'Nenhum registro');
	  db_select('aluno',$x,true,1,"style='width:400px;'");
	  }else{
	  ?>
	  <select name="aluno" id="aluno" size="20" style="width:400px;font-size:9px;" multiple>
	    <?
	    for($x=0;$x<$linhas_aluno;$x++){
	    db_fieldsmemory($result_aluno,$x);
	    ?>
	    <option value="<?=$ed47_i_codigo?>"><?=$ed47_i_codigo?> - <?=$ed47_v_nome?></option>
	    <?
	    }
	    ?>
	  </select>
	  <?
	  }
	  ?>
	</td>
	</tr>
	<tr>
	<td>
	  <input type="button" value="Excluir" name="excluir" onclick="js_prossegue();">
	</td>
	</tr>
	<tr>
	<td>
	  <br>
	  <fieldset style="align:center">
	  Para selecionar mais de um ítem mantenha pressionada a tecla CTRL e clique sobre os ítens.
	  </fieldset>
	</td>
	</tr>
      </table>
    <?}?>
   </fieldset>
  </td>
 </tr>
</table>
<table width="300" height="50" id="tab_aguarde" style="visibility:hidden;border:2px solid #444444;position:absolute;top:200px;left:400px;" cellspacing="1" cellpading="2">
 <tr>
  <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;text-decoration:blink;">
  <b>Aguarde...Processando exclusão dos registros.</b>
  </td>
 </tr>
</table>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_prossegue(){
 conta = 0;
 tam = document.form1.aluno.length;
 alunos = "";
 sep_alunos = "";
 for(i=0;i<tam;i++){
  if(document.form1.aluno[i].selected==true){
   alunos += sep_alunos+document.form1.aluno[i].value;
   sep_alunos = ",";
   conta++;
  }
 }
 if(conta==0){
  alert("Selecione algum ítem para prosseguir!");
  return false;
 }
 if(confirm("Confirmar exclusão dos registros?")){
  document.getElementById("tab_aguarde").style.visibility = "visible";
  document.form1.excluir.disabled = true;
  location.href = "edu4_apagargeral002.php?excluir&alunos="+alunos;
 }
}
</script>