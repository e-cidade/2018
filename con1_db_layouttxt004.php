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
include("classes/db_db_layouttxt_classe.php");
include("classes/db_db_layoutlinha_classe.php");
include("classes/db_db_layoutcampos_classe.php");
$cldb_layouttxt = new cl_db_layouttxt;
$cldb_layoutlinha = new cl_db_layoutlinha;
$cldb_layoutcampos = new cl_db_layoutcampos;
db_postmemory($HTTP_POST_VARS);
   $db_opcao = 1;
$db_botao = true;
if(isset($incluir) || isset($importar)){
  $sqlerro=false;
  db_inicio_transacao();
  $cldb_layouttxt->incluir($db50_codigo);
  $erro_msg = $cldb_layouttxt->erro_msg; 
  if($cldb_layouttxt->erro_status==0){
    $sqlerro=true;
  }else{
    $db50_codigo= $cldb_layouttxt->db50_codigo;
  }
  if(isset($importar) && $sqlerro == false){
    $result_dados_linhas = $cldb_layoutlinha->sql_record($cldb_layoutlinha->sql_query_file(null,"db51_descr, db51_tipolinha, db51_tamlinha, db51_obs, db51_linhasantes, db51_linhasdepois, db51_codigo as codigolinha","db51_tipolinha"," db51_layouttxt = ".$codigoimporta));
    $numrows_dados_linhas = $cldb_layoutlinha->numrows;
    for($i=0; $i<$numrows_dados_linhas; $i++){
      db_fieldsmemory($result_dados_linhas, $i);
      $cldb_layoutlinha->db51_layouttxt = $db50_codigo;
      $cldb_layoutlinha->db51_descr = $db51_descr;
      $cldb_layoutlinha->db51_tipolinha = $db51_tipolinha;
      $cldb_layoutlinha->db51_tamlinha = $db51_tamlinha;
      $cldb_layoutlinha->db51_obs = $db51_obs;
      $cldb_layoutlinha->db51_linhasantes = $db51_linhasantes;
      $cldb_layoutlinha->db51_linhasdepois = $db51_linhasdepois;
      $cldb_layoutlinha->incluir(null);
      if($cldb_layoutlinha->erro_status==0){
        $erro_msg = $cldb_layoutlinha->erro_msg;
        $sqlerro=true;
       	break;
      }else{
        $db51_codigo= $cldb_layoutlinha->db51_codigo;
        $result_campos_importa = $cldb_layoutcampos->sql_record($cldb_layoutcampos->sql_query_file(null,"db52_nome, db52_descr, db52_layoutformat, db52_posicao, db52_default, db52_tamanho, db52_ident, db52_imprimir, db52_alinha, db52_obs, db52_quebraapos","db52_posicao"," db52_layoutlinha=".$codigolinha));
        $numrows_campos_importa = $cldb_layoutcampos->numrows;
        for($ix=0; $ix<$numrows_campos_importa; $ix++){
          db_fieldsmemory($result_campos_importa, $ix);
          $cldb_layoutcampos->db52_layoutlinha  = $db51_codigo;
          $cldb_layoutcampos->db52_nome         = $db52_nome;
          $cldb_layoutcampos->db52_descr        = $db52_descr;
          $cldb_layoutcampos->db52_layoutformat = $db52_layoutformat;
          $cldb_layoutcampos->db52_posicao      = $db52_posicao;
          $cldb_layoutcampos->db52_default      = $db52_default;
          $cldb_layoutcampos->db52_tamanho      = $db52_tamanho;
          $cldb_layoutcampos->db52_ident        = ($db52_ident=='t'?"true":"false");
          $cldb_layoutcampos->db52_imprimir     = $db52_imprimir;
          $cldb_layoutcampos->db52_alinha       = $db52_alinha;
          $cldb_layoutcampos->db52_obs          = $db52_obs;
          $cldb_layoutcampos->db52_quebraapos   = $db52_quebraapos;
          $cldb_layoutcampos->incluir(null);
          if($cldb_layoutcampos->erro_status==0){
            $sqlerro=true;
            $erro_msg = $cldb_layoutcampos->erro_msg;
            break;
          }
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
  $db_opcao = 1;
  $db_botao = true;
}else if(isset($chavepesquisa)){
  echo ($cldb_layouttxt->sql_query($chavepesquisa,"db50_Codigo as codigoimporta, db50_descr, db50_obs, db50_quantlinhas"));
  $result_layout = $cldb_layouttxt->sql_record($cldb_layouttxt->sql_query($chavepesquisa,"db50_Codigo as codigoimporta, db50_descr, db50_obs, db50_quantlinhas"));
  if($cldb_layouttxt->numrows > 0){
    db_fieldsmemory($result_layout, 0);
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
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmdb_layouttxt.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","db50_descr",true,1,"db50_descr",true);
</script>
<?
if(isset($incluir) || isset($importar)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($cldb_layouttxt->erro_campo!=""){
      echo "<script> document.form1.".$cldb_layouttxt->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cldb_layouttxt->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("con1_db_layouttxt005.php?liberaaba=true&chavepesquisa=$db50_codigo");
  }
}
?>