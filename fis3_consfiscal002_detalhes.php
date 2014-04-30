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
include("classes/db_autotipo_classe.php");
include("classes/db_autorec_classe.php");
include("classes/db_autousu_classe.php");
include("classes/db_autotestem_classe.php");
include("classes/db_autonumpre_classe.php");
$clautotipo= new cl_autotipo;
$clautorec= new cl_autorec;
$clautousu= new cl_autousu;
$clautotestem= new cl_autotestem;
$clautonumpre= new cl_autonumpre;
?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?
db_postmemory($HTTP_GET_VARS,0);
$pesquisaLocalizada = false;
if ($solicitacao == "Proced") {
  $sql=$clautotipo->sql_query_baixa("","y59_codtipo,y29_descr,y29_descr_obs,y59_valor,y87_dtbaixa,p58_codproc",""," y59_codauto = $auto");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Receita") {
  $sql=$clautorec->sql_query("","","y57_receit,y57_descr,y57_valor",""," y57_codauto = $auto");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Fiscais") {
  $sql=$clautousu->sql_query("","","y56_id_usuario,nome,y56_obs ",""," y56_codauto = $auto");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Testemunha") {
  $sql=$clautotestem->sql_query("","","y24_numcgm,z01_nome",""," y24_codauto = $auto");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Calculo") {
  $result_calc=$clautonumpre->sql_record($clautonumpre->sql_query_file("","*","","y17_codauto=$auto"));
  if ($clautonumpre->numrows>0){
    db_fieldsmemory($result_calc,0);    
    echo "<br><br><br><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Auto já Calculado!! Numpre:$y17_numpre<b>";
  }else{
    echo "<br><br><b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Auto não foi calculado!!<b>";
  }
}
if ($pesquisaLocalizada==true) {
  $result = pg_exec($sql);
  if(pg_numrows($result) == 0){
    echo "<br><br><b>Nenhum Registro Cadastrado!!<b>";
  }else{
      db_lovrot($sql,5,"","","");
  }
}
?>
</body>
</html>