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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tipoasse_classe.php");
include("classes/db_portariatipo_classe.php");
include("classes/db_portariaenvolv_classe.php");
include("classes/db_portariatipoato_classe.php");
include("classes/db_portariaproced_classe.php");
include("classes/db_portariatipodocindividual_classe.php");
include("classes/db_portariatipodoccoletiva_classe.php");

db_postmemory($HTTP_POST_VARS);

$cltipoasse              = new cl_tipoasse;
$clportariatipo          = new cl_portariatipo;
$clportariaenvolv        = new cl_portariaenvolv;
$clportariatipoato       = new cl_portariatipoato;
$clportariaproced        = new cl_portariaproced;
$clportariatipodocindividual = new cl_portariatipodocindividual;
$clportariatipodoccoletiva   = new cl_portariatipodoccoletiva;
$clrotulo                = new rotulocampo;

$db_opcao = 1;
$db_botao = true;
$sqlerro  = false;

if(isset($incluir)){
  
  db_inicio_transacao();

  $cltipoasse->h12_relvan = "false";
  $cltipoasse->h12_relass = "true";
  $cltipoasse->h12_assent = $h12_assent = trim($h12_assent);

  $cltipoasse->incluir($h12_codigo);
  
  if ($cltipoasse->erro_status == 0){
       $sqlerro  = true;
  }

  if ($sqlerro == false){
       if (isset($h30_portariaenvolv) && trim($h30_portariaenvolv)!=""){
        
            $h12_codigo = $cltipoasse->h12_codigo;
            $clportariatipo->h30_tipoasse        = $h12_codigo;
            $clportariatipo->h30_portariaenvolv  = $h30_portariaenvolv;
            $clportariatipo->h30_portariatipoato = $h30_portariatipoato;
            $clportariatipo->h30_portariaproced  = $h30_portariaproced;
            $clportariatipo->h30_amparolegal     = $h30_amparolegal;

            $clportariatipo->incluir(null);
            if ($clportariatipo->erro_status == 0){
                 $sqlerro  = true;
            }
       }
  }

  
  if ($sqlerro == false) {
    
    if (isset($h37_modportariaindividual) && $h37_modportariaindividual != "") {
      
      $clportariatipodocindividual->h37_modportariaindividual = $h37_modportariaindividual;
      $clportariatipodocindividual->h37_portariatipo        = $clportariatipo->h30_sequencial;
      $clportariatipodocindividual->incluir(null);
    
      if ($clportariatipodocindividual->erro_status == 0) {
      $sqlerro = true;      
      }
    }  
  }
  
  if ($sqlerro == false) {
    if (isset($h38_modportariacoletiva) && $h38_modportariacoletiva != "") {
        
      $clportariatipodoccoletiva->h38_modportariacoletiva = $h38_modportariacoletiva;
      $clportariatipodoccoletiva->h38_portariatipo      = $clportariatipo->h30_sequencial;
      $clportariatipodoccoletiva->incluir(null);
    
      if ($clportariatipodoccoletiva->erro_status == 0) {
      $sqlerro = true;      
      }
    }  
  }
  
  
  if (!empty($h79_db_cadattdinamico)) {

    $oDaoTipoassedb_cadattdinamico = new cl_tipoassedb_cadattdinamico();
    $oDaoTipoassedb_cadattdinamico->h79_db_cadattdinamico = $h79_db_cadattdinamico;
    $oDaoTipoassedb_cadattdinamico->h79_tipoasse          = $cltipoasse->h12_codigo;
    $oDaoTipoassedb_cadattdinamico->incluir($h79_db_cadattdinamico, $cltipoasse->h12_codigo);

    if ($oDaoTipoassedb_cadattdinamico->erro_sql == '0') {
      $sqlerro = true;      
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <?
      include("forms/db_frmtipoasse.php");
      ?>
      </center>
    </td>
  </tr>
</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","h12_assent",true,1,"h12_assent",true);
</script>
<?
if(isset($incluir)){
  if($cltipoasse->erro_status=="0"){
    $cltipoasse->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cltipoasse->erro_campo!=""){
      echo "<script> document.form1.".$cltipoasse->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cltipoasse->erro_campo.".focus();</script>";
    }
  }else{
    $cltipoasse->erro(true,true);
  }
}
?>
