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


// fui la...

require("libs/db_stdlib.php");
db_postmemory($HTTP_POST_VARS);

//if(isset($trocaip)){
//
//  $DB_SERVIDOR = $servidor;
//  $DB_BASE = "template1";
//  global $HTTP_SESSION_VARS;
//  $HTTP_SESSION_VARS["DB_SELLER"] = "on";
//  $HTTP_SESSION_VARS["DB_servidor"] = $servidor;
//
//}
//if(!isset($trocaip) &&   !isset($atualiza)){
  require("libs/db_conecta.php");
  require("libs/db_usuariosonline.php");

//}else{
//  $DB_SERVIDOR = $servidor;
//  $conn = pg_connect("host=$DB_SERVIDOR dbname=template1 user=postgres");
//}
//  echo  db_getsession();



if(isset($atualiza)){
  
  db_putsession("DB_NBASE",$db_base);
  $DB_BASE = db_getsession("DB_NBASE");
  echo "<script>
          top.topo.document.getElementById('auxAcesso').value = '".$DB_BASE."';
        </script>
  ";
  /*
  $hora = time();
  db_query("insert into db_usuariosonline 
               values(".db_getsession("DB_id_usuario").",
			          ".$hora.",
					  '".(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])."',
					  '".db_getsession("DB_login")."',
					  'Entrou no sistema',					  
					  '',
					  ".time().",
					  ' ')") or die("Erro:(27) inserindo arquivo em db_usuariosonline: ".pg_errormessage());
*/
}
//DB_login=dbseller&DB_id_usuario=1&DB_porta=1117&DB_instit=1&DB_modulo=1&DB_nome_modulo=configuracao&DB_anousu=2003&DB_datausu=1062713208 

if(!isset($trocaip) && !isset($atualiza)){
 
  $result = db_query("select nome,login,id_usuario from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario"));

  if(pg_result($result,0,'id_usuario')==1){
    $atualiza = true;
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
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"><br>
      <br>
      <br>
     <form name="form1" action="" method="post">
        <table width="22%" border="0" cellspacing="0" cellpadding="2">
          <tr> 
            <td width="27%" nowrap><strong>Nome:</strong></td>
            <td width="73%" nowrap> 
              <?=@pg_result($result,0,0)?>
              &nbsp;</td>
          </tr>
          <tr> 
            <td nowrap><strong>Login:</strong></td>
            <td nowrap> 
              <?=@pg_result($result,0,1)?>
              &nbsp;</td>
          </tr>
          <tr> 
            <td nowrap><strong>Base de dados atual:&nbsp;</strong></td>
            <td nowrap> 
              <?=$DB_BASE?>
              &nbsp;</td>
          </tr>
          <tr> 
            <td nowrap><strong>Servidor:</strong></td>
            <td nowrap>
	      <input readonly name="servidor" value="<?=$DB_SERVIDOR?>" type="text" size="20">
              &nbsp;</td>
          </tr>
          <tr> 
            <td nowrap><strong>IP:</strong></td>
            <td nowrap> 
              <?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?>
              &nbsp;</td>
          </tr>
          <tr> 
            <td nowrap><strong>Local:</strong></td>
            <td nowrap> 
              <?=$HTTP_SERVER_VARS['PHP_SELF'];?>
            </td>
          </tr>
          <?
					if(isset($HTTP_SESSION_VARS["DB_SELLER"]) || (isset($atualiza) || isset($db_ip))){
           $result = db_query("select datname from pg_database where substr(datname,1,6) != 'templa'"); 
	         if($result!=false && pg_numrows($result)!=0){

              if(!session_is_registered("DB_anousu")){
								$ano = date("Y");
							} else {
                $ano = db_getsession("DB_anousu");
							}

              $permissao_parcelamento=db_permissaomenu($ano,1,5333); // 5333

              ?>
               <tr> 
              <?
							if ($permissao_parcelamento == "true" or db_getsession("DB_id_usuario") == 1) {
							?>
               <td nowrap><strong>Base:</strong></td>
               <td nowrap><select name="db_base">
							<?
								for($bb=0;$bb<pg_numrows($result);$bb++){
								?>
									<option value="<?=pg_result($result,$bb,0)?>" <?=($DB_BASE==pg_result($result,$bb,0)?"selected":"")?>><?=pg_result($result,$bb,0)?></option> 
								<?
								}
							}
								?>
								</select>
              </td></tr>
              <tr align="center"> 
               <td colspan="2" nowrap>
	       <input name="atualiza" type="submit" id="atualiza" value="Atualiza">
	       <input name="trocaip" type="submit" value="Troca Servidor" >
	       </td>
              </tr>
	    <?
            }
	  }
	  ?>
        </table>
	  </form>
	  </td>
  </tr>
</table>

</body>
</html>