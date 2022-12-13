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

include("classes/db_boletim_classe.php");

$clboletim    = new cl_boletim;

$clrotulo = new rotulocampo;
$clrotulo->label("k11_data");
$db_opcao = 1;

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

if(isset($liberar) || isset($deslibera)){
  db_inicio_transacao();
  $sqlerro = false;
  $instit = db_getsession("DB_instit");
  $anousu = db_getsession("DB_anousu");
 // $datausu = date("Y-m-d",db_getsession("DB_datausu"));
  
  $data =  "$k11_data_ano-$k11_data_mes-$k11_data_dia";
  $result = $clboletim->sql_record($clboletim->sql_query_file("$data",$instit ));

  //naum existe
  if($clboletim->numrows==0){
    $result = $clboletim->sql_record($clboletim->sql_query_file(null,null," (max(k11_numbol) +1) as k11_numbol","","k11_instit=".$instit." and k11_anousu = $anousu   " ));
    if($clboletim->numrows!=0){
      db_fieldsmemory($result,0); 
      if($k11_numbol==''){
        $k11_numbol=1;
      }
    }else{
      $k11_numbol=1;
    }
    $clboletim->k11_data   = "$data";
    $clboletim->k11_instit = $instit;
    $clboletim->k11_numbol = $k11_numbol;
    $clboletim->k11_libera = 'true';
    $clboletim->k11_lanca  = 'false';
    $clboletim->k11_anousu = $anousu;
    $clboletim->incluir($data,$instit);
    $erro_msg =  $clboletim->erro_msg;
    if($clboletim->erro_status==0){
       $sqlerro=true;
    }
  //jah existir  
  }else{
    db_fieldsmemory($result,0);
    if($k11_lanca=='t'){
      $sqlerro=true;
      $erro_msg = "Boletim já lançado na contabilidade!";  
    }

    //verifica se já não foi liberado
    if($sqlerro==false && $k11_libera=='t' && empty($deslibera)){
      $sqlerro=true;
      $msg_libera = "Boletim já está liberado. Deseja cancelar?";  
    }

    //
    if($sqlerro==false){
	$clboletim->k11_data   = "$data";
  	$clboletim->k11_instit = $instit;
          

	if( isset($deslibera) ){          //para trancar  
          $clboletim->k11_libera = 'false';
	}else{                           //para liberar 
          $clboletim->k11_libera = 'true';
	}  

	$clboletim->alterar($data,$instit);
        $erro_msg =  $clboletim->erro_msg;
	if($clboletim->erro_status==0){
	   $sqlerro=true;
	}
    }
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
<script>
function js_verifica(obj){
  if(obj.k11_data_dia.value == '' || obj.k11_data_mes.value == '' || obj.k11_data_ano.value == '' ){
    alert("Informe uma data válida! ");
    return false;
  }
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">

      <tr>
      <br>
        <td nowrap title="<?=@$Tk11_data?>">
        <?=@$Lk11_data?>
        </td>	
        <td>	
      <?
      if(empty($liberar) && empty($deslibera) && empty($k11_data_dia) ){
	$k11_data_dia = date("d", db_getsession("DB_datausu") );
	$k11_data_mes = date("m", db_getsession("DB_datausu") );
	$k11_data_ano = date("Y", db_getsession("DB_datausu") );
      }
db_inputdata('k11_data',@$k11_data_dia,@$k11_data_mes,@$k11_data_ano,true,'text',$db_opcao,"")
         ?>
        </td>
      </tr>
            <tr>
              <td colspan="2" align="center"  height="25" nowrap><input name="liberar" type="submit" id="boletim" onClick="return js_verifica(this.form)" value="Liberar">
	      </td>
              <td>
            </tr>
          </table>
        </form>
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
if(isset($msg_libera)){
  echo "
<script>
   if((confirm('$msg_libera'))==true){
            obj=document.createElement('input');
            obj.setAttribute('name','deslibera');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value','deslibera');
            document.form1.appendChild(obj);
	    document.form1.submit();
  }
</script>
  ";
}else if(isset($liberar)){
  db_msgbox($erro_msg);
}
?>