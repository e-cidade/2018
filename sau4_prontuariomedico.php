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

include("classes/db_prontuarios_classe.php");
include("classes/db_prontproced_classe.php");
include("classes/db_especmedico_classe.php");
include("classes/db_proctipoatend_classe.php");

include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$clprontuarios = new cl_prontuarios;
$clprontproced = new cl_prontproced;
$clespecmedico = new cl_especmedico;
$clproctipoatend = new cl_proctipoatend;

$db_opcao = 1;
$db_botao = true;

if(isset($incluir) || isset($alterar) ){
  $result = $clespecmedico->sql_record( $clespecmedico->sql_query( "", "sd27_i_codigo", "", "sd27_i_especialidade = $sd05_i_codigo and sd27_i_medico = $sd03_i_codigo" ) );
  @db_fieldsmemory($result,0);
  $clprontproced->sd29_i_especmed = @$sd27_i_codigo;

  $result = $clproctipoatend->sql_record( $clproctipoatend->sql_query( "", "sd20_i_codigo", "", "sd20_i_procedimento = $sd29_i_procedimento and sd20_i_tipoatend = $sd14_i_codigo" ) );
  @db_fieldsmemory($result,0);
  $clprontproced->sd29_i_proctipoatend = @$sd20_i_codigo;

  $clprontproced->sd29_i_procafaixaetaria = 1;
  if( empty( $clprontproced->sd29_i_proctipoatend ) ) {
     $clprontproced->erro_status = "0";
     $clprontproced->erro_campo = "sd14_i_codigo";
     $clprontproced->erro_msg = "Campo Tipo de Atendimento não foi localizado com o Procedimento!";
  }elseif( empty( $clprontproced->sd29_i_especmed ) ) {
     $clprontproced->erro_status = "0";
     $clprontproced->erro_campo = "sd03_i_codigo";
     $clprontproced->erro_msg = "Campo Especialidade não localizada com o Médico!";
  }
}


if(isset($incluir)){

  $clprontproced->sd29_i_prontuario = $chavepesquisaprontuario;

  if( !empty( $clprontproced->sd29_i_proctipoatend ) && !empty( $clprontproced->sd29_i_especmed ) ){
     db_inicio_transacao();
     $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
     $clprontproced->incluir("");
     db_fim_transacao();
  }
}else if(isset($alterar)){
  if( !empty( $clprontproced->sd29_i_proctipoatend ) && !empty( $clprontproced->sd29_i_especmed ) ){
     db_inicio_transacao();
     $clprontproced->sd29_i_usuario = DB_getsession("DB_id_usuario");
     $clprontproced->alterar($sd29_i_codigo);
     db_fim_transacao();
  }

}else if(isset($excluir)){
     db_inicio_transacao();
     $clprontproced->excluir($sd29_i_codigo);
     db_fim_transacao();

}else if(isset($chavepesquisaprontuario) && !empty($chavepesquisaprontuario)){
   //$result = $clprontproced->sql_record($clprontproced->sql_query(null,"*",null,"sd29_i_prontuario = $chavepesquisaprontuario"));
   //db_fieldsmemory($result,0);
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
<table width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmprontuariomedico.php");
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
   //$chavepri= array("sd29_i_codigo"=>@$sd29_i_codigo,"sd29_d_data"=>@$sd29_d_data,"sd29_c_hora"=>@$sd29_c_hora,"sd29_i_procedimento"=>@$sd29_i_procedimento,"sd09_c_descr"=>@$sd09_c_descr);
   $chavepri= array("sd29_i_codigo"=>@$sd29_i_codigo,
                    "sd29_i_procedimento"=>@$sd29_i_procedimento,
                    "sd14_i_codigo"=>@$sd14_i_codigo,
                    "sd14_c_descr"=>@$sd14_c_descr,
                    "sd05_i_codigo"=>@$sd05_i_codigo,
                    "sd05_c_descr"=>@$sd05_c_descr,
                    "sd09_c_descr"=>@$sd09_c_descr,
                    "sd03_i_codigo"=>@$sd03_i_codigo,
                    "z01_nome"=>@$z01_nome,
                    "sd29_i_especmed"=>@$sd29_i_especmed,
                    "sd29_i_proctipoatend"=>@$sd29_i_proctipoatend,
                    "sd29_i_procafaixaetaria"=>@$sd29_i_procafaixaetaria,
                    "sd29_d_data"=>@$sd29_d_data,
                    "sd29_c_hora"=>@$sd29_c_hora,
                    "sd29_t_tratamento"=>@$sd29_t_tratamento
                    );
   $cliframe_alterar_excluir->chavepri=$chavepri;
   //echo $clausencias->sql_query("","*","","sd06_i_unidade = $sd06_i_unidade and sd06_i_medico = $sd06_i_medico");
   @$cliframe_alterar_excluir->sql = $clprontproced->sql_query("","*","sd29_i_codigo","sd29_i_prontuario = $chavepesquisaprontuario");
   $cliframe_alterar_excluir->campos  ="sd29_i_codigo,sd29_d_data,sd29_c_hora,sd29_i_procedimento,sd09_c_descr";
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
<table>

<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","z01_v_cgccpf",true,1,"z01_v_cgccpf",true);
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
    ?>
        <script>
          location.href='sau4_fichaatendabas003.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'
        </script>
    <?
  }

}else if(isset($excluir)){
    $clprontproced->erro(true,false);
    ?>
        <script>
          location.href='sau4_fichaatendabas003.php?chavepesquisaprontuario=<?=$chavepesquisaprontuario?>'
        </script>
    <?
}
?>