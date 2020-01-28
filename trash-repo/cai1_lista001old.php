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
include("classes/db_lista_classe.php");
include("classes/db_listadeb_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cllista    = new cl_lista;
$cllistadeb = new cl_listadeb;
$db_opcao = 1;
$db_botao = true;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir"){
  db_inicio_transacao();
  $erro1 = false;
  $data = $k60_datadeb_ano.'-'.$k60_datadeb_mes.'-'.$k60_datadeb_dia;
//  $resverifica = $cllista->sql_record($cllista->sql_query ( '','','',"k60_data = '$data'"));
//  if (pg_numrows($resverifica) > 0){
//     $confirma = 
//  }
  if(isset($campos)){
     $tipos = '';
     $virgula = '';
     for ($i=0;$i < sizeof($campos);$i++){
        $tipos .= $virgula.$campos[$i];
        $virgula = ', ';
     }
  }

  $cllista->k60_tipodeb = $tipos; 
  $cllista->incluir('');
  if($cllista->erro_status !="0")
    $erro1 = true;    
    
  if ($k60_tipo == 'f'){
     $matinsc = ' and (matric is not null or inscr is not null)';
  }else{
     $matinsc = '';
  }
   $sql = "
          select distinct numpre from devedores where tipo in ($tipos) and data = '$data' $matinsc
         ";
  $resultlistadeb = pg_exec($sql);
  if (pg_numrows($resultlistadeb) == 0)
     echo "<script>alert('Não existem devedores para as opções escolhidas')</script>";
  
  for ($ii = 0;$ii < pg_numrows($resultlistadeb);$ii++){
     db_fieldsmemory($resultlistadeb,$ii);
     $cllistadeb->k61_codigo = $cllista->k60_codigo;
     $cllistadeb->k61_numpre = $numpre;
     $cllistadeb->incluir();
     if($cllistadeb->erro_status !="0" && $erro1 == true)
       $erro1 = true;    
  }
  if ($erro1 == true) 
    db_fim_transacao();
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
	include("forms/db_frmlistaalt.php");
	
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
if($cllistadeb->erro_status=="0"){
  $cllistadeb->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllistadeb->erro_campo!=""){
    echo "<script> document.form1.".$cllistadeb->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cllistadeb->erro_campo.".focus();</script>";
  };
}else{
  $cllistadeb->erro(true,true);
};

if($cllista->erro_status=="0"){
  $cllista->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllista->erro_campo!=""){
    echo "<script> document.form1.".$cllista->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cllista->erro_campo.".focus();</script>";
  };
}else{
  $cllista->erro(true,true);
};
?>