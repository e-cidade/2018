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
include("classes/db_prontuariomedico_classe.php");
include("classes/db_cgs_und_classe.php");
include("classes/db_familiamicroarea_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_jsplibwebseller.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clprontuariomedico = new cl_prontuariomedico;
$clcgs_und          = new cl_cgs_und;
$clfamiliamicroarea = new cl_familiamicroarea;

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$sd32_d_atendimento_dia = date("d",db_getsession("DB_datausu"));
$sd32_d_atendimento_mes = date("m",db_getsession("DB_datausu"));
$sd32_d_atendimento_ano = date("Y",db_getsession("DB_datausu"));


if(!isset($chavepesquisa)){
     $sd32_i_unidade = db_getsession("DB_coddepto");
     $descrdepto=db_getsession("DB_nomedepto");
}

$db_botao1 = false;

if(isset($opcao)){
 $sd32_d_atendimento_dia = substr($sd32_d_atendimento,0,2);
 $sd32_d_atendimento_mes = substr($sd32_d_atendimento,3,2);
 $sd32_d_atendimento_ano = substr($sd32_d_atendimento,6,4);
 $db_botao1 = true;

 if( $opcao=="alterar"  ){
   $db_opcao = 2;
   $result = $clprontuariomedico->sql_record($clprontuariomedico->sql_query($sd32_i_codigo));
   db_fieldsmemory($result,0);
 }elseif( $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
   $db_opcao = 3;
   $result = $clprontuariomedico->sql_record($clprontuariomedico->sql_query($sd32_i_codigo));
   db_fieldsmemory($result,0);
 }
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
  $sd32_c_horaatend = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
  if( isset( $chavepesquisaprontuario ) ){
     $sd32_i_numcgs = $chavepesquisaprontuario;
     //$clprontuariomedico->sql_record( $clprontuariomedico->sql_query("","*","sd32_d_atendimento desc ","sd32_i_numcgs = $chavepesquisaprontuario" ) );
     //db_fieldsmemory($result,0);
  }
 }
}


$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clprontuariomedico->incluir($sd32_i_codigo);
  db_fim_transacao();
}else if(isset($alterar)){
  db_inicio_transacao();
  $clprontuariomedico->sd32_i_medico = (int)$sd32_i_medico==0?"null":$sd32_i_medico;
  $clprontuariomedico->sd32_i_codigo = $sd32_i_codigo;
  $clprontuariomedico->alterar($sd32_i_codigo);
  db_fim_transacao();
}else if(isset($excluir)){
  db_inicio_transacao();
  $clprontuariomedico->excluir($sd32_i_codigo);
  db_fim_transacao();
}

//Familia Micro Area
if(isset($incluir) || isset($alterar) ){
  db_inicio_transacao();
  $clcgs_und->z01_i_cgsund = $sd32_i_numcgs;
  $clcgs_und->z01_i_familiamicroarea = (int)$z01_i_familiamicroarea==0?"null":$z01_i_familiamicroarea;
  $clcgs_und->alterar($sd32_i_numcgs);
  db_fim_transacao();

}

if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $clprontuariomedico->sql_record($clprontuariomedico->sql_query($chavepesquisa));
   db_fieldsmemory($result,0);
   $db_botao = true;
}

///verifica familia micro area
if( (int)@$z01_i_familiamicroarea <> 0 ){
   $result = $clfamiliamicroarea->sql_record($clfamiliamicroarea->sql_query($z01_i_familiamicroarea));
   db_fieldsmemory($result,0);
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<fieldset>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
     <?
		$result = pg_query( "select descrdepto 
		                                 from db_depart
		                                inner join unidades on unidades.sd02_i_codigo = db_depart.coddepto  
		                                where coddepto = ".db_getsession("DB_coddepto") );
		if( pg_num_rows( $result ) == 0 ){
			echo "<table width='100%'>
			        <tr>
			         <td align='center'><font  face='arial'><b><p>Departamento ".db_getsession("DB_coddepto")." não cadastrado como UPS. <p> Selecione um departamento válido.</b></font></td>
			        </tr>
			       </table>";
				
		}else{
			include("forms/db_frmprontuariomedico.php");
		}
     ?>
    </center>
     </td>
  </tr>
</table>
</fieldset>
<fieldset>

<center>
     <table>
     <tr>
       <td valign="top" align="center"><br>
       <?
        $chavepri= array("sd32_i_codigo"=>@$sd32_i_codigo);

        $cliframe_alterar_excluir->chavepri=$chavepri;
        $chavepesquisaprontuario = '0'.@$chavepesquisaprontuario;
        $cliframe_alterar_excluir->sql = $clprontuariomedico->sql_query("","*","sd32_d_atendimento desc ","sd32_i_numcgs = $chavepesquisaprontuario" );

        $cliframe_alterar_excluir->campos  ="sd32_i_codigo, sd32_t_descricao, z01_nome, sd32_d_atendimento, sd32_c_horaatend";
        $cliframe_alterar_excluir->legenda="Registros";
        $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
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
     </table>
</center>
</fieldset>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd32_i_medico",true,1,"sd32_i_medico",true);
</script>
<?
if(isset($incluir) || isset($alterar) ){
  if($clcgs_und->erro_status=="0"){
    $clcgs_und->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clcgs_und->erro_campo!=""){
      echo "<script> document.form1.".$clcgs_und->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcgs_und->erro_campo.".focus();</script>";
    }
  }
  
  
  if($clprontuariomedico->erro_status=="0"){
    $clprontuariomedico->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clprontuariomedico->erro_campo!=""){
      echo "<script> document.form1.".$clprontuariomedico->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontuariomedico->erro_campo.".focus();</script>";
    }
  }else{
    $clprontuariomedico->erro(true,false);
  }
}else if(isset($excluir)){
    $clprontuariomedico->erro(true,false);
}

if( isset($incluir) || isset($alterar) || isset($excluir) || isset($cancelar)){
    ?>
        <script>
          location.href='sau1_prontuariomedico001.php?chavepesquisaprontuario=<?=$sd32_i_numcgs?>&z01_v_nome=<?=$z01_v_nome?>&z01_i_familiamicroarea=<?=$z01_i_familiamicroarea?>'
        </script>
    <?
}
 echo "<script>
       if( document.form1.sd32_i_unidade.value == '' ){
          js_pesquisasd32_i_unidade(true);
       }else if( document.form1.sd32_i_numcgs.value == '' ){
          js_pesquisasd32_i_numcgs(true);
       }else if( document.form1.sd32_i_medico.value == '' ){
          //js_pesquisasd32_i_medico(true);
       }
      </script>";
?>