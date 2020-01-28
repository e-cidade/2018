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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("classes/db_ativid_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_clasativ_classe.php");
require_once ("classes/db_ativtipo_classe.php");
require_once ("classes/db_classe_classe.php");
require_once ("classes/db_cnae_classe.php");
require_once ("classes/db_cnaeanalitica_classe.php");
require_once ("classes/db_rhcbo_classe.php");
require_once ("classes/db_atividcnae_classe.php");
require_once ("classes/db_atividcbo_classe.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_db_estruturavalor_classe.php");
require_once ("classes/db_issgruposervicoativid_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);
$clativid                  = new cl_ativid;
$clativtipo                = new cl_ativtipo;
$cl_clasativ               = new cl_clasativ;
$cl_classe                 = new cl_classe;
$clrotulo                  = new rotulocampo;
$clcnae                    = new cl_cnae;
$clcnaeanalitica           = new cl_cnaeanalitica;
$clrhcbo                   = new cl_rhcbo;
$clatividcnae              = new cl_atividcnae;
$clatividcbo               = new cl_atividcbo;
$clDbEstrutValor           = new cl_db_estruturavalor;
$clIssGrupoServicoAtivid   = new cl_issgruposervicoativid;

$clrotulo->label("q80_tipcal");
$clrotulo->label("q81_descr");
$hiddenPJ        = 'hidden';
$hiddenPF        = 'hidden';
$positionPJ      = 'absolute';
$positionPF      = 'absolute';
$sAcao           = 'Altera��o';
$q12_fisica      = '';
$db_opcaoselect  = 1;

$pessoa = 'S';

$db_opcao  = 22;
$db_opcaoc = 3;
$db_botao  = false;
$sServicoStyle ="";

if (isset($chavepesquisa)) {
  $db_opcao = 2;
  
  if (!isset($q03_ativ)) {
  	
  	$sCampos     = "q03_ativ,q03_descr,q03_atmemo,q03_limite,q12_classe, q12_calciss,q12_descr,q12_fisica,q03_horaini,q03_horafim";
  	$sSqlAtivid  = $clativid->sql_query_clas($chavepesquisa,$sCampos);

    $result      = $clativid->sql_record($sSqlAtivid);
  
    db_fieldsmemory($result,0);
    
    if ($q12_calciss == "t") {
      $sServicoStyle = "table-row";
    } else {
      $sServicoStyle = "none";
    }
    
    if ($q12_fisica == "t") {
      $pessoa = 'J';  
    } else {
      $pessoa = 'F';
    }
    
    
    
  }
  
  $result_tipcal = $clativtipo->sql_record($clativtipo->sql_query($chavepesquisa,null,'q80_tipcal,q81_descr'));

  if ($result_tipcal && $clativtipo->numrows > 0) {
    db_fieldsmemory($result_tipcal,0);
  }
   
  // verifica se tem CBO
  $sqlcbo = "select * from atividcbo
				inner join rhcbo on q75_rhcbo = rh70_sequencial
				where q75_ativid = $chavepesquisa";
  $resultcbo = pg_query($sqlcbo);
  $linhascbo= pg_num_rows($resultcbo);
  
  if ($linhascbo > 0) {
    
    $hiddenPF   = 'visible';
    $positionPF = 'relative';
    $db_opcaoselect = 1;
    $alteracbocnae = 'sim' ;
   // db_msgbox('cbo...'.$alteracbocnae);
    db_fieldsmemory($resultcbo,0);
        
  } else {
   
    // verifica se tem CNAE
    $sqlcnae = "select * from atividcnae
          			inner join cnaeanalitica on q74_cnaeanalitica= q72_sequencial 
          			inner join cnae on q71_sequencial = q72_cnae 
          			where q74_ativid = $chavepesquisa";   
    $resultcnae = pg_query($sqlcnae);
    $linhascnae= pg_num_rows($resultcnae);
    
    if ($linhascnae > 0) {
      
      // db_msgbox('cnae');
      $hiddenPJ   = 'visible';
      $positionPJ = 'relative';
      $db_opcaoselect = 1;
      $alteracbocnae = 'sim' ;
      db_fieldsmemory($resultcnae,0);
    } else {
      $alteracbocnae = 'sim' ;
    }
  }
  $sWhereGrpServAtiv  = " ativid.q03_ativ = {$chavepesquisa}"; 
  $sCamposGrpServAtiv = "q126_sequencial as q127_sequencial, db121_descricao ";
  $sSqlGrpServAtiv    = $clIssGrupoServicoAtivid->sql_query('', $sCamposGrpServAtiv, '', $sWhereGrpServAtiv);
  $rsGrpServAtiv      = $clIssGrupoServicoAtivid->sql_record($sSqlGrpServAtiv);
  
  if ($rsGrpServAtiv && $clIssGrupoServicoAtivid->numrows > 0) {
    db_fieldsmemory($rsGrpServAtiv,0);
  }  
  
  $db_botao = true;
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pessoa();">
<div style="margin-top: 30px;"></div>
<div>
<center>
<?
	include("forms/db_frmativid.php");
?>
</center>
</div>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
		
		if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar") {
		  
		  if ($sqlerro==true) {
		    
		    db_msgbox($erro_msg);
        echo "<script>js_pessoa(); alert('teste');  </script>";
		    $db_botao=true;
		    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		    
		    if ($clativid->erro_campo!="") {
		      echo "<script> document.form1.".$clativid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
		      echo "<script> document.form1.".$clativid->erro_campo.".focus();</script>";
		    };
		  } else {
		    $clativid->erro(true,true);
		  }
		} 
		if ($db_opcao==22) {
		  echo "<script>document.form1.pesquisar.click();</script>";
		}
?>