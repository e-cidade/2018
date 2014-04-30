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
include("classes/db_undmedhorario_classe.php");
include("classes/db_medicos_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clundmedhorario = new cl_undmedhorario;
$clmedicos = new cl_medicos;
$db_botao = true;
$db_opcao = 1;
$datausu = date("Y",db_getsession( "DB_datausu" ) )."/".
           date("m",db_getsession( "DB_datausu" ) )."/".
           date("d",db_getsession( "DB_datausu" ) );

if(isset($incluir)){
  db_inicio_transacao();
  $clundmedhorario->incluir($sd30_i_codigo);
  db_fim_transacao();
}
//não é mais utilizada alteração pois estavam alterando o turno,
//daí teria q criar um referencia do turno no agendamento
if(isset($alterar)){
 //$sql = "select *
 //          from agendamentos
 //          where sd23_d_consulta >= '$datausu'
 //            and sd23_i_turno = $sd30_i_turno ";
 //$result = pg_exec( $sql );
 //if( pg_numrows( $result ) > 0 ){
 //    echo "<script>alert('Profissional tem agendamentos efetuadas posteriormente. Não permitindo a alteração do turno')</script>";
 //}else{
     db_inicio_transacao();
     $clundmedhorario->alterar($sd30_i_codigo);
     db_fim_transacao();
 //}
}

if(isset($excluir)){
 $sql = "select *
           from agendamentos
          inner join unidademedicos on sd04_i_codigo = sd23_i_unidmed
          where sd23_d_consulta >= '$datausu'
            and sd23_i_unidmed = $sd30_i_undmed
            and extract(dow from sd23_d_consulta ) = $sd30_i_diasemana ";

 $result = pg_exec( $sql );
 if( pg_numrows( $result ) > 0 ){
     echo "<script>alert('Profissional tem agendamentos efetuadas posteriormente. Não permitindo a exclusão do horário')</script>";
 }else{
     db_inicio_transacao();
     $clundmedhorario->excluir($sd30_i_codigo);
     db_fim_transacao();
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmundmedhorario3.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd30_i_diasemana",true,1,"sd30_i_diasemana",true);
</script>
<?
if(isset($incluir)){
  if($clundmedhorario->erro_status=="0"){
    $clundmedhorario->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clundmedhorario->erro_campo!=""){
      echo "<script> document.form1.".$clundmedhorario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clundmedhorario->erro_campo.".focus();</script>";
    }
  }else{
    $clundmedhorario->erro(true,true);
  }
}
if(isset($alterar)){
 if($clundmedhorario->erro_status=="0"){
  $clundmedhorario->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clundmedhorario->erro_campo!=""){
   echo "<script> document.form1.".$clundmedhorario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clundmedhorario->erro_campo.".focus();</script>";
  };
 }else{
  $clundmedhorario->erro(true,true);
 };
}
if(isset($excluir)){
 if($clundmedhorario->erro_status=="0"){
  $clundmedhorario->erro(true,false);
 }else{
  $clundmedhorario->erro(true,true);
 };
}
?>