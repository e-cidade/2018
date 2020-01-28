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
include("classes/db_orcreserva_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcdotacao_classe.php");
include("libs/db_liborcamento.php");      // funções do orçamento

db_postmemory($HTTP_POST_VARS);

$clorcreserva = new cl_orcreserva;
$clorcdotacao = new cl_orcdotacao;  //instancia dotação
$db_opcao = 1;
$db_botao = true;

//===
    $dtlan="";  $dtini=""; $dtfim="";
    if (@checkdate($o80_dtlanc_mes,$o80_dtlanc_dia,$o80_dtlanc_ano)) {
        $dtlan="$o80_dtlanc_ano-$o80_dtlanc_mes-$o80_dtlanc_dia";
    } 
    if (@checkdate($o80_dtini_mes,$o80_dtini_dia,$o80_dtini_ano)) {
        $dtini="$o80_dtini_ano-$o80_dtini_mes-$o80_dtini_dia";
    }
    if (@checkdate($o80_dtfim_mes,$o80_dtfim_dia,$o80_dtfim_ano)) {
        $dtfim="$o80_dtfim_ano-$o80_dtfim_mes-$o80_dtfim_dia";
    }
     $dtatual= date("Y-m-d",db_getsession("DB_datausu"));
							
//    $dtatual= date("Y-m-d"); //data atual
    if (($dtlan !="") and ($dtini !="") and ($dtfim!=""))  {
      if (($dtlan >= $dtatual) and ($dtini >= $dtatual)  and ($dtfim >=$dtatual)) {
          $dtok = 1 ;  // somente cria variavel, conteudo nao importa
      }	

    }

// ==
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  /*
    -- as datas devem ser maiores ou iguais a data atual
    -- o valor da reserva não pode ultrapassaro saldo da dotação  
    -- validação por script e tambem na inclusão

   */
  // valida saldo  
  if (($o80_valor - $original) > $atual_menos_reservado ){
       echo "<script> alert('Dotação sem saldo para este valor'); </script> "; 
  } else if (!isset($dtok)){
       // datas não conferem
       echo "<script> alert('Datas inconsistentes ! '); </script>";
  } else {
     db_inicio_transacao();
     $clorcreserva->incluir($o80_codres);
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
<script>
  function dot() {
     document.form1.submit();
  }
  function critica_form(){
     obj = document.form1;
     var valor = new Number(obj.o80_valor.value);
     var original = new Number(obj.original.value);
     var reservado = new Number(obj.atual_menos_reservado.value);
     var dt = new Date();
     var objData  = new Date(<?=db_getsession("DB_anousu")?>,<?=date("m",db_getsession("DB_datausu"))?>,<?=date("d",db_getsession("DB_datausu"))?>);
     var datalan = new Date(obj.o80_dtlanc_ano.value,(obj.o80_dtlanc_mes.value-1),obj.o80_dtlanc_dia.value);
     var dataini = new Date(obj.o80_dtini_ano.value,(obj.o80_dtini_mes.value-1),obj.o80_dtini_dia.value);
     var datafim = new Date(obj.o80_dtfim_ano.value,(obj.o80_dtfim_mes.value-1),obj.o80_dtfim_dia.value);

      if ( obj.o80_valor.value =="" ) {
           alert('Valor não pode ser nulo ! ');
	   obj.o80_valor.focus();
      } else if ( valor > reservado ) {
	  alert('Sem reserva para este valor ! ');
      } else if  ( datalan.getDate() < objData.getDate())  {
          alert('Data de lançamento Inválida : menor que a data atual');
      } else if  ( dataini.getDate() < objData.getDate())  {
          alert('Data inicial Inválida : menor que a data atual');
      } else if  ( datafim.getDate() < objData.getDate())  {
          alert('Data final Inválida : menor que a data atual');
      } else if  ( datafim.getDate() < dataini.getDate())  {
          alert('Data final não pode ser menor que a data inicial '); 
      } else {
	   // cria imput com dados do botão 'inclui,altera,exclui
           var opcao= document.createElement("input");
	       opcao.setAttribute("type","hidden");
	       opcao.setAttribute("name","db_opcao");
	       opcao.setAttribute("value",document.form1.db_opcao.value);
 	       document.form1.appendChild(opcao);  
           document.form1.submit();  
      }
  }  

</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.o80_coddot.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
   	  include("forms/db_frmorcreserva.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  if($clorcreserva->erro_status=="0"){
    $clorcreserva->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clorcreserva->erro_campo!=""){
      echo "<script> document.form1.".$clorcreserva->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clorcreserva->erro_campo.".focus();</script>";
    };
  }else{
    echo "<script> 
            var confirma = confirm('Deseja imprimir relatório?');
            if(confirma == true){
              jan = window.open('orc2_reservamanual002.php?res=".$clorcreserva->o80_codres."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
              jan.moveTo(0,0);
            }
		    </script>";
   $clorcreserva->erro(true,true);
  };
};

?>
<script>
function js_imprime(res){
//  qry = "";
//  qry += "res="+document.form1.o80_codres.value;
//  qry += "&ano="+document.form1.o80_anousu.value;
  jan = window.open('orc2_reservamanual002.php?res','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>