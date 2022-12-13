<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  include("classes/db_aguabase_classe.php");
  include("classes/db_aguabaseresp_classe.php");
  include("classes/db_aguabasecorresp_classe.php");
  include("classes/db_aguabasecar_classe.php");
  include("classes/db_aguaconstr_classe.php");
  include("classes/db_aguabasevenc_classe.php");
  include("classes/db_histocorrencia_classe.php");
  include("classes/db_histocorrenciamatric_classe.php");

  $claguabase             = new cl_aguabase;
  $claguabasecar          = new cl_aguabasecar;  
  $claguabaseresp         = new cl_aguabaseresp;
  $claguabasecorresp      = new cl_aguabasecorresp;
  $claguaconstr           = new cl_aguaconstr;
  $claguabasevenc         = new cl_aguabasevenc;
  $clhistocorrencia       = new cl_histocorrencia;
  $clhistocorrenciamatric = new cl_histocorrenciamatric;

  db_postmemory($HTTP_POST_VARS);
  
  $db_opcao = 33;
  $db_botao = false;

  if (isset($excluir)) {
    
    $sqlerro = false;
  
    db_inicio_transacao();
  
    $claguabaseresp->x14_matric = $x01_matric;
    
    $claguabaseresp->excluir($x01_matric);

    if ($claguabaseresp->erro_status == 0) {
      $sqlerro = true;
    } 
    
    $erro_msg = $claguabaseresp->erro_msg; 
    $claguabasecorresp->x32_matric = $x01_matric;
    
    $claguabasecorresp->excluir($x01_matric);

    if ($claguabasecorresp->erro_status == 0) {
      $sqlerro = true;
    }
     
    $erro_msg = $claguabasecorresp->erro_msg; 

    $claguabasecar->excluir($x01_matric);

    if ($claguabasecar->erro_status == 0) {
      $sqlerro=true;
    } 
    
    $erro_msg = $claguabasecar->erro_msg; 

    $claguaconstr->x11_codconstr = $x01_matric;
    
    $claguaconstr->excluir($x01_matric);

    if ($claguaconstr->erro_status == 0) {
      $sqlerro=true;
    }
     
    $erro_msg = $claguaconstr->erro_msg; 
    $claguabasevenc->x27_matric = $x01_matric;
    
    $claguabasevenc->excluir($x01_matric);

    if ($claguabasevenc->erro_status == 0) {
      $sqlerro = true;
    } 
    
    $erro_msg = $claguabasevenc->erro_msg; 
  
    $claguabase->excluir($x01_matric);
    
    if ($claguabase->erro_status == 0) {
      $sqlerro = true;
    } 
    
    $erro_msg = $claguabase->erro_msg; 
  
    //exclusao das ocorrencias 
    $result  = $clhistocorrenciamatric->sql_record($clhistocorrenciamatric->sql_query("", "ar25_histocorrencia", "ar25_histocorrencia", "ar25_matric = $x01_matric"));
    $numrows = pg_numrows($result);
    
    if ($numrows > 0) {
      for($i = 0; $i < $numrows; $i++) {
        
        db_fieldsmemory($result, $i);
        
        $ar23_sequencial_excluir .= $ar25_histocorrencia;
      
        if ($i < ($numrows-1)) { 
          $ar23_sequencial_excluir .= ", ";
        }
      }
      
      $clhistocorrenciamatric->excluir("", "ar25_matric = $x01_matric");
      $clhistocorrencia->excluir("", "ar23_sequencial IN ($ar23_sequencial_excluir)");
    
      if ($clhistocorrencia->erro_status == 0) {
        $sqlerro = true;
      }
      
      $erro_msg = $clhistocorrencia->erro_msg;
    }
  
    db_fim_transacao($sqlerro);
  
    $db_opcao = 3;
    $db_botao = true;
  
  } else if(isset($chavepesquisa)) {
    $db_opcao = 3;
    $db_botao = true;
    $result = $claguabase->sql_record($claguabase->sql_query($chavepesquisa)); 

    db_fieldsmemory($result,0);

    // Busca Caracteristicas 
    $result = $claguabasecar->sql_record($claguabasecar->sql_query($x01_matric));
    $caracteristica = null;
    $car = "X";
   
    for ($i = 0; $i < $claguabasecar->numrows; $i++) {
      db_fieldsmemory($result, $i);
      $caracteristica .= $car.$x30_codigo ;
      $car = "X";
    }
    
    $caracteristica .= $car;
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
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
            <center>
  	          <?
	              include("forms/db_frmaguabase.php");
	            ?>
            </center>
	        </td>
        </tr>
      </table>
    </center>
  </body>
</html>

<?
  if (isset($excluir)) {
  
    if ($sqlerro == true) {
      
      db_msgbox($erro_msg);
      
      if ($claguabase->erro_campo != "") {
        echo "<script> document.form1." . $claguabase->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $claguabase->erro_campo . ".focus();</script>";
      }
  
    } else {
      
      db_msgbox($erro_msg);
      echo "
            <script>
              function js_db_tranca(){
                parent.location.href='agu1_aguabase003.php';
              }\n
              js_db_tranca();
            </script>\n
           ";
    }
  }
  
  if (isset($chavepesquisa)) {
    echo "
      <script>
        function js_db_libera(){
           parent.document.formaba.aguabaseresp.disabled=false;
           top.corpo.iframe_aguabaseresp.location.href='agu1_aguabaseresp001.php?db_opcaoal=33&x14_matric=" . @$x01_matric . "';
           parent.document.formaba.aguabasecorresp.disabled=false;
           top.corpo.iframe_aguabasecorresp.location.href='agu1_aguabasecorresp001.php?db_opcaoal=33&x32_matric=" . @$x01_matric . "';
           //parent.document.formaba.aguabasecar.disabled=false;
           //top.corpo.iframe_aguabasecar.location.href='agu1_aguabasecar001.php?db_opcaoal=33&x30_matric=" . @$x01_matric . "';
           parent.document.formaba.aguaconstr.disabled=false;
           //top.corpo.iframe_aguaconstr.location.href='agu1_aguaconstr001.php?db_opcaoal=33&x11_codconstr=" . @$x01_matric . "';
           top.corpo.iframe_aguaconstr.location.href='agu1_aguaconstr001.php?db_opcaoal=33&x11_matric=" . @$x01_matric . "';
           parent.document.formaba.aguabasevenc.disabled=false;
           top.corpo.iframe_aguabasevenc.location.href='agu1_aguabasevenc001.php?db_opcaoal=33&x27_matric=" . @$x01_matric . "';
           parent.document.formaba.histocorrencia.disabled=false;
           top.corpo.iframe_histocorrencia.location.href='agu1_histocorrencia001.php?db_opcaoal=33&ar25_matric=" . @$x01_matric . "';
         ";
         if (isset($liberaaba)) {
           echo "  parent.mo_camada('aguabaseresp');";
         }
         echo "}\n
               js_db_libera();
               </script>\n
              ";
  }
  
  if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
  }
  
?>