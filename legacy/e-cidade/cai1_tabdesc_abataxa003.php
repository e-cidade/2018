<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_tabdesc_classe.php");
require_once("classes/db_tabdescarretipo_classe.php");
require_once("classes/db_tabdescdepto_classe.php");
require_once("classes/db_tabdesccadban_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cltabdesc              = new cl_tabdesc;
$cltabdescarretipo      = new cl_tabdescarretipo;
$cltabdescdepto         = new cl_tabdescdepto;
$cltabdesccadban        = new cl_tabdesccadban; 
$cltabdesc->k07_instit  = db_getsession("DB_instit");
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){

  try {

	  db_inicio_transacao();
	  
	  $codtabdesc = $cltabdesc->codsubrec;
	  $cltabdescarretipo-> k78_tabdesc  = $codsubrec;
	  $cltabdescarretipo->excluir(null," k78_tabdesc = $codsubrec ");
	  if($cltabdescarretipo->erro_status == "0") {
  
  	  	throw new Exception($cltabdescarretipo->erro_msg);
    } 

    $cltabdescdepto->k69_tabdesc  = $codsubrec;
  	$cltabdescdepto->excluir(null, " k69_tabdesc = $codsubrec ");
  	if($cltabdescdepto->erro_status == "0") {
      	    
  		throw new Exception($cltabdescdepto->erro_msg);
	  }
	  
    $cltabdesccadban ->k114_tabdesc = $codsubrec;
    $cltabdesccadban ->excluir(null, " k114_tabdesc = $codsubrec");
    if($cltabdesccadban ->erro_status == "0") {

      throw new Exception($cltabdesccadban ->erro_msg);
    }

	  $cltabdesc->codsubrec = $codsubrec;
    $cltabdesc->excluir($codsubrec);
    if($cltabdesc->erro_status == "0") {
    	
	  	throw new Exception($cltabdesc->erro_msg);
    }
	
    db_fim_transacao(false);
    $db_opcao = 33;
    db_msgbox("Exclução efetuada com sucesso!");
  } catch (Exception $oErro) {

    db_fim_transacao(true);
    db_msgbox($oErro->getMessage());
  }

} else if (isset($chavepesquisa)){
  
  $db_opcao = 3;
	$sql = "select tabdesc.*,k78_arretipo,k00_descr ,k02_descr,i01_descr, k114_codban, z01_nome
	        from tabdesc 
					inner join tabrec          on k02_codigo  = k07_codigo
					inner join inflan          on k07_codinf  = i01_codigo
					left  join tabdescarretipo on k78_tabdesc = codsubrec 
					left  join arretipo        on k00_tipo    = k78_arretipo 
          left join tabdesccadban    on k114_tabdesc = codsubrec
          left join cadban           on k114_codban = k15_codigo
          left join cgm              on z01_numcgm  = k15_numcgm  
					where codsubrec = $chavepesquisa ";
	$rs = db_query($sql);
	$linhas = pg_num_rows($rs);
	if($linhas > 0){
		db_fieldsmemory($rs,0);
	}
  $db_botao = true;
	$codsubrec = $chavepesquisa;
  if(!isset($opcao)){
  	$opcao=3;
  }
 echo "
  <script>
      function js_db_libera(){
        parent.document.formaba.depto.disabled=false;
			  parent.document.formaba.taxa.disabled=false;
		    top.corpo.iframe_depto.location.href='cai1_tabdesc_abadepto001.php?db_opcao=$opcao&coddepto=".@$coddepto."&codsubrec=".@$codsubrec."&k07_descr=$k07_descr';
			  ";
        if(isset($liberaaba)){
          echo "  parent.mo_camada('depto');";
        }
    echo"}\n
    js_db_libera();
		document.form1.codsubrec.value = $chavepesquisa;
  </script>\n
 ";

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
	<?
	include("forms/db_frmtabdesc.php");
	?>

</body>
</html>
<?

if($db_opcao==33){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>