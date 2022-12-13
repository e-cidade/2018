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
include("classes/db_bancoagencia_classe.php");
include("dbforms/db_funcoes.php");
require_once 'libs/db_app.utils.php';
db_postmemory($HTTP_POST_VARS);
$clbancoagencia         = new cl_bancoagencia;
$clbancoagenciaendereco = new cl_bancoagenciaendereco;
$db_opcao               = 1;
$db_botao               = true;
$btnDisabled            = '';
if (isset($incluir)) {

  db_inicio_transacao();
  $clbancoagencia->incluir($db89_sequencial);
  if ($clbancoagencia->erro_status != '0' && !empty($_POST["db92_endereco"])) {

    $clbancoagenciaendereco->db92_bancoagencia = $clbancoagencia->db89_sequencial;
    $clbancoagenciaendereco->db92_endereco     = $_POST["db92_endereco"];
    $clbancoagenciaendereco->incluir(null);
    if ($clbancoagenciaendereco->erro_status == 0) {

      $clbancoagencia->erro_status = 0;
      $clbancoagencia->erro_campo  = 'endereco';
      $clbancoagencia->erro_msg    = $clbancoagenciaendereco->erro_msg;
    }
  }
  db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbViewCadEndereco.classe.js,dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css");
  ?>
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
<table style="padding-top:15px;" align="center">
  <tr> 
    <td> 
      <center>
	    <?
	      include("forms/db_frmbancoagencia.php");
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
<script>
js_tabulacaoforms("form1","db89_db_bancos",true,1,"db89_db_bancos",true);
</script>
<?
if(isset($incluir)){
  if($clbancoagencia->erro_status=="0"){
    $clbancoagencia->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($clbancoagencia->erro_campo!=""){
      echo "<script> document.form1.".$clbancoagencia->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbancoagencia->erro_campo.".focus();</script>";
    }
  }else{
    $clbancoagencia->erro(true,true);
  }
}
?>