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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_arrecant_classe.php");
include ("classes/db_arrecad_classe.php");
include ("classes/db_arrehist_classe.php");
include ("classes/db_cancdebitos_classe.php");
include ("classes/db_cancdebitosreg_classe.php");
include ("classes/db_cancdebitosproc_classe.php");
include ("classes/db_cancdebitosprocreg_classe.php");
include ("libs/db_sql.php");
$clarrecad = new cl_arrecad;
$clarrecant = new cl_arrecant;
$clarrehist = new cl_arrehist;
$clcancdebitos = new cl_cancdebitos;
$clcancdebitosreg = new cl_cancdebitosreg;
$clcancdebitosproc = new cl_cancdebitosproc;
$clcancdebitosprocreg = new cl_cancdebitosprocreg;
db_postmemory($HTTP_POST_VARS);
$db_opcao = 33;
$db_botao = false;
if (isset ($processa)) {
  $sqlerro = false;
  db_inicio_transacao();
  //declaro a variavel $er pq aum sei o  que faz da erro de variaval indefinida 
  $er = "";
  // die($clcancdebitosreg->sql_query("","k21_sequencia,k21_codigo,k21_numpre,k21_numpar","k21_numpre,k21_numpar","k21_codigo=$k20_codigo"));
  $result = $clcancdebitosreg->sql_record($clcancdebitosreg->sql_query("", "k21_sequencia,k21_codigo,k21_numpre,k21_numpar", "k21_numpre,k21_numpar", "k21_codigo=$k20_codigo"));
  $numrows = $clcancdebitosreg->numrows;
  //db_fieldsmemory($result, 0); //db_msgbox($k21_codigo);exit;
  $clcancdebitosproc->k23_data = date("Y-m-d", db_getsession("DB_datausu"));
  $clcancdebitosproc->k23_hora = date("H:i");
  $clcancdebitosproc->k23_usuario = db_getsession("DB_id_usuario");
  $clcancdebitosproc->k23_obs = "$k23_obs ";
  $clcancdebitosproc->incluir(null); //$k21_codigo);
  if ($clcancdebitosproc->erro_status == "0") {
    //	die($clcancdebitosproc->erro_msg);
    $erro_msg = "5 Operação não Efetuada - $er".$clcancdebitosproc->erro_msg;
    $sqlerro = true;
  }else{
    $codigo_proc=$clcancdebitosproc->k23_codigo;
  }
  //db_criatabela($result);//exit;
  if ($sqlerro == false) {
    for ($x = 0; $x < $numrows; $x ++) {
      db_fieldsmemory($result, $x);
      $result_arrecad=$clarrecad->sql_record($clarrecad->sql_query_file_instit(null,"*",null,"arrecad.k00_numpre=$k21_numpre and k00_numpar=$k21_numpar " . ($k21_receit == 0?"":" and arrecad.k00_receit=$k21_receit and k00_instit = ".db_getsession('DB_instit') )));
      if ($clarrecad->numrows==0){
        continue;
      }
      //		echo "x: $x\n";
      $clarrehist->k00_numpre     = $k21_numpre;
      $clarrehist->k00_numpar     = $k21_numpar;
      $clarrehist->k00_hist       = 502;
      $clarrehist->k00_dtoper     = $clcancdebitosproc->k23_data;
      $clarrehist->k00_hora       = $clcancdebitosproc->k23_hora;
      $clarrehist->k00_id_usuario = $clcancdebitosproc->k23_usuario;
      $clarrehist->k00_histtxt    = "$k23_obs ";
      
      $data = "'".date("Y-m-d", db_getsession("DB_datausu"))."'";
      //db_msgbox($k21_numpre."--".$data."--".$k21_numpar);
      
      $result_deb = debitos_numpre($k21_numpre, 0, 0, $data, db_getsession("DB_anousu"), $k21_numpar);
      //db_criatabela($result_deb);exit;
      if (gettype($result_deb) == "boolean") {
        $sqlerro = true;
      } else {
        db_fieldsmemory($result_deb, 0);
      }
      if ($sqlerro==false){
        $clarrecant->incluir_arrecant($k21_numpre, $k21_numpar, $k21_receit, true);
        if ($clarrecant->erro_status == "0") {
          $sqlerro = true;
          $erro_msg = "1 Operação não Efetuada - $er".$clarrecant->erro_msg;
        }
      }
      if ($sqlerro==false){
        $clarrehist->incluir(null);
        if ($clarrehist->erro_status == "0") {
          $sqlerro = true;
          $erro_msg = "2 Operação não Efetuada - $er".$clarrehist->erro_msg;
        }
      }
      if ($sqlerro==false){
        $vlrhis = 1;
        $clcancdebitosprocreg->k24_codigo = @$codigo_proc;
        $clcancdebitosprocreg->k24_cancdebitosreg = $k21_sequencia;
        $clcancdebitosprocreg->k24_vlrhis = $vlrhis;
        $clcancdebitosprocreg->k24_vlrcor = $vlrcor;
        $clcancdebitosprocreg->k24_juros = $vlrjuros;
        $clcancdebitosprocreg->k24_multa = $vlrmulta;
        $clcancdebitosprocreg->k24_desconto = $vlrdesconto;
        $clcancdebitosprocreg->incluir(null);
        if ($clcancdebitosprocreg->erro_status == "0") {
          $sqlerro = true;				
          $erro_msg = "3 Operação não Efetuada - $er".$clcancdebitosprocreg->erro_msg;
          //die($erro_msg);
        }
      }
    }
  }
  //exit;
  db_fim_transacao($sqlerro);
  if ($sqlerro == true) {
    $erro_msg = "4 Operação não Efetuada - ".@$erro_msg;
  } else {
    $erro_msg = "Operação Efetuada";
  }
  db_msgbox($erro_msg);
  echo "<script>location.href='cai4_processacanc001.php';</script>";
}
if (isset ($chavepesquisa)) {
  $db_opcao = 1;
  $db_botao = true;
  $campos = "cancdebitos.k20_codigo, cancdebitos.k20_usuario, cancdebitos.k20_data, cancdebitos.k20_hora,
  cancdebitosreg.k21_sequencia, cancdebitosreg.k21_numpre, cancdebitosreg.k21_numpar, cancdebitosreg.k21_obs,
  arrecad.k00_valor";
  //die($clcancdebitos->sql_pendentes($campos,"","k21_codigo =".$chavepesquisa));
  $result = $clcancdebitos->sql_record($clcancdebitos->sql_pendentes($campos, "", "k21_codigo =".$chavepesquisa." and k20_instit = ".db_getsession("DB_instit")));
  @ db_fieldsmemory($result, 0);
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left" valign="top" bgcolor="#CCCCCC">
<br><br>
<center>
<?



include ("forms/db_frmcancdebitos.php");
?>
</center>
</td>
</tr>
</table>
<?



db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?



if (!isset ($chavepesquisa)) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>