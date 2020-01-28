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
require_once("classes/db_tabdesccadban_classe.php");
require_once("dbforms/db_funcoes.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cltabdesc              = new cl_tabdesc;
$cltabdescarretipo      = new cl_tabdescarretipo;
$cltabdescadban = new cl_tabdesccadban; 
$cltabdesc->k07_instit  = db_getsession("DB_instit");
$db_opcao = 22;
$db_botao = false;
if((isset($alterar))){

  $k07_valorf=str_replace(",",".",$k07_valorf); 
  $k07_valorv=str_replace(",",".",$k07_valorv); 
  $k07_quamin=str_replace(",",".",$k07_quamin); 
  $k07_percde=str_replace(",",".",$k07_percde); 
  try{

	  db_inicio_transacao();
	  $cltabdesc->codsubrec = $codsubrec;
    $cltabdesc->alterar($codsubrec);
    if($cltabdesc->erro_status="0"){
      
	    throw new Exception($cltabdesc->erro_msg);
    }
	  // exclui 
	  $codtabdesc = $cltabdesc->codsubrec;
	  $cltabdescarretipo-> k78_tabdesc  = $codtabdesc;
	  $cltabdescarretipo->excluir(null," k78_tabdesc = $codtabdesc ");
	  if($cltabdescarretipo->erro_status="0") {

	  	throw new Exception($cltabdescarretipo->erro_msg);
    } 
	   
	  if(isset($k78_arretipo) and $k78_arretipo!="" ) {
      
	  	$cltabdescarretipo-> k78_tabdesc  = $codtabdesc;
	  	$cltabdescarretipo-> k78_arretipo = $k78_arretipo;
	  	$cltabdescarretipo->incluir(null);
	  	if($cltabdescarretipo->erro_status="0") {

	  		throw new Exception($cltabdescarretipo->erro_msg);
	    } 
	  }

    $sSql  = $cltabdescadban->sql_query_file(null, "k114_sequencial", null, "k114_tabdesc = {$codtabdesc}");
    $rsSql = db_query($sSql);
    if(pg_num_rows($rsSql) != 0) {

      db_fieldsmemory($rsSql,0);  
    }
    
    if (trim($k114_codban) != "") {
            
      $cltabdescadban->k114_tabdesc       = $codtabdesc;
      $cltabdescadban->k114_codban = $k114_codban;
      if(isset($k114_sequencial) && $k114_sequencial != null) {
        
        $cltabdescadban->k114_sequencial = $k114_sequencial;
        $cltabdescadban->alterar($cltabdescadban->k114_sequencial);  
        if ($cltabdescadban->erro_status == "0") {

          throw new Exception($cltabdescadban->erro_msg);
        }
      } else {
      
        $cltabdescadban->incluir(null);
        if ($cltabdescadban->erro_status == "0") {

          throw new Exception($cltabdescadban->erro_msg);
        }
      }
      
      if ($cltabdescadban->erro_status == "0") {

        throw new Exception($cltabdescadban->erro_msg);
      }
      
    } else {
      
      if (isset($k114_sequencial) && $k114_sequencial != null) {

        $cltabdescadban->k114_sequencial = $k114_sequencial;
        $cltabdescadban->excluir($cltabdescadban->k114_sequencial);
        if ($cltabdescadban->erro_status == "0") {

          throw new Exception($cltabdescadban->erro_msg);
        }
      }
    }
    $db_opcao = 2;
    db_fim_transacao(false);
    db_msgbox("Alteração efetuada com sucesso!");
    db_redireciona("cai1_tabdesc_abataxa002.php?opcao=1&liberaaba=true&chavepesquisa={$codtabdesc}&k07_descr={$k07_descr}"); 
  } catch (Exception $oErro) {
    
    db_fim_transacao(true);
    db_msgbox($oErro->getMessage());
  }  
  
  
} else if (isset($chavepesquisa)){
  
  $db_opcao = 2;
	$sql = "select tabdesc.*,k78_arretipo,k00_descr ,k02_descr,i01_descr, k114_codban, z01_nome
	        from tabdesc 
					inner join tabrec          on k02_codigo  = k07_codigo
					inner join inflan          on k07_codinf  = i01_codigo
          left  join tabdescarretipo on k78_tabdesc = codsubrec 
					left  join arretipo        on k00_tipo    = k78_arretipo 
          left join tabdesccadban    on k114_tabdesc = codsubrec
          left join cadban           on k114_codban = k15_codigo
          left join cgm              on z01_numcgm  = k15_numcgm
					where codsubrec = $chavepesquisa";
	$rs = db_query($sql);
	$linhas = pg_num_rows($rs);
	if($linhas > 0) {
		db_fieldsmemory($rs,0);
	}

  $db_botao = true;
	$codsubrec = $chavepesquisa;
  if(!isset($opcao)){

  	$opcao=2;
  }
 echo "
  <script>
      function js_db_libera(){
        parent.document.formaba.depto.disabled=false;
			  parent.document.formaba.taxa.disabled=false;
		    top.corpo.iframe_depto.location.href='cai1_tabdesc_abadepto001.php?db_opcao=$opcao&coddepto=".@$coddepto."&codsubrec=".@$codsubrec."&k07_descr=$k07_descr';
			 // top.corpo.iframe_taxa.location.href='cai1_tabdesc_abataxa002.php?codsubrec=".@$codsubrec."&k07_descr=$k07_descr';
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


	<?
	include("forms/db_frmtabdesc.php");
	?>

</body>
</html>
<?
if($db_opcao==22){
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>