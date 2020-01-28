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
include("classes/db_fiscaltipo_classe.php");
include("classes/db_fiscalusuario_classe.php");
include("classes/db_fisctestem_classe.php");
include("classes/db_autonumpre_classe.php");
$clfiscaltipo= new cl_fiscaltipo;
$clfiscalusuario= new cl_fiscalusuario;
$clfisctestem= new cl_fisctestem;
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
  $sql=$clfiscaltipo->sql_query(null,null,"y31_codtipo,y29_descr,y29_descr_obs",""," y31_codnoti = $fiscal");
  
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Fiscais") {
  $sql=$clfiscalusuario->sql_query("","","y38_id_usuario,nome,y38_obs ",""," y38_codnoti = $fiscal");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Testemunha") {
  $sql=$clfisctestem->sql_query("","","y23_numcgm,z01_nome",""," y23_codnoti = $fiscal");
  $pesquisaLocalizada = true;
} else if ($solicitacao == "Baixa") {
  $sql=$clfiscalbaixa->sql_query(null,"y47_data,y47_obs,y47_motivo,y46_descr,y48_codproc,p58_requer",null,"y47_codnoti = $fiscal");
  $pesquisaLocalizada = true;
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