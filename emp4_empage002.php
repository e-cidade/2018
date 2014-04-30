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
include("dbforms/db_funcoes.php");


parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS,2);


$db_opcao = 1;
$db_botao = false;

$data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
//die();
if(isset($atualizar)){
  
  db_inicio_transacao();
  $sqlerro=false;
  
  

  $clpcmater->incluir(null);
  if($clpcmater->erro_status==0){
    $sqlerro = true;
  }else{
    $codmater =  $clpcmater->pc01_codmater;
  }
  if($sqlerro==false){
    $arr =  split("XX",$codeles);
    for($i=0; $i<count($arr); $i++ ){
       $elemento = $arr[$i];  
       $clpcmaterele->pc07_codmater = $codmater;
       $clpcmaterele->pc07_codele = $elemento;
       $clpcmaterele->incluir($codmater,$elemento); 
       if($clpcmaterele->erro_status==0){
	 db_msgbox('erro');
         $sqlerro = true;
       }	 
      
    }	 
  }
  db_fim_transacao($sqlerro);
}



if(isset($entrar)){
  db_inicio_transacao();
  $sqlerro=false;
  
  $result01 = $clempage->sql_record($clempage->sql_query_file(null,'e80_codage','',"e80_data='$data' and e80_instit = " . db_getsession("DB_instit")));
  $numrows01 = $clempage->numrows;
  if($numrows01 == 0){
    $clempage->e80_data = $data;
		$clempage->e80_instit = db_getsession("DB_instit");
    $clempage->incluir(null);
    if($clempage->erro_status==0){
         $sqlerro = true;
    }else{
      $e80_codage = $clempage->e80_codage;
    }	 
  }else{
    $jatem =  true;
  }
  db_fim_transacao($sqlerro);
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
   $clrotulo = new rotulocampo;
   $clrotulo->label("e80_data");
   
//sempre que ja existir agenda entra nesta opcao  
  if(isset($e80_codage)){  
	include("forms/db_frmempage.php");

//pela primeira vez que entrar neste arquivo, entra nesta opcao para digitar a data da agenda 
  }else if(empty($entrar) && empty($e80_codage)){?>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr><br>
              <td nowrap title="<?=@$Te80_data?>">
                <?=$Le80_data?>
              </td>	
              <td>	
              <?
                db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',1);
              ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center"  height="25" nowrap><input name="entrar" type="submit" id="boletim"  value="Entrar">
	      </td>
              <td>
            </tr>
          </table>
        </form>
	
<?  
//entra nesta opcao para escolher uma das agendas ou então selecionar uma jah existente
   }else if(isset($jatem)){?>
        <form name="form1" method="post" action="">
         <table>
	   <tr>
	     <td colspan='2' align='center'><b>Já existe agenda para esta data.</b></td>
	   </tr>
	   <tr>
	      <td nowrap title="<?=@$Te80_data?>" align='right'>
	      <?=$Le80_data?>
	      </td>	
	      <td>	
	       <?
		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',3);
	       ?>
	      </td>
	   </tr>
	 </table>
       </form>	 
<?   	
   }  
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