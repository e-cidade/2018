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
include("classes/db_db_cadhelp_classe.php");
include("classes/db_db_itenshelp_classe.php");
include("classes/db_db_tipohelp_classe.php");
include("classes/db_db_modulos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
var ja_executado = false;
var ultimoclicado = 0;

function js_atualiza_help(menu){
  
  //parent.document.getElementById('labelhelp').value = descr ;
  //parent.alert(modulo+'--'+parent.document.getElementById('nome_modulo').value+' -- item '+item);
  
  
  //if( parent.document.getElementById('nome_modulo').value != modulo ){
  
  //parent.alert(modulo);
  
  //  parent.document.form1.nome_modulo.value = modulo;
  //  parent.document.form1.nome_modulo.onchange();
    
  //}else{
 
    
    //parent.mostrahelp.js_processa_agora(menu);

    parent.mostrahelp.location.href = 'con1_help002.php?#db'+menu+'db';

//	  parent.document.form1.helps_acessados_r.value += "-I"+item;
    
//    parent.mostrahelp.location.href='con1_help002.php?id_help='+item;
//	  parent.document.form1.itens_retorna.disabled = false;
  //}
  
}
function js_atualiza_help_proced(item){
  
	  parent.document.form1.helps_acessados_r.value += "-P"+item;
    parent.mostrahelp.location.href='con1_help002.php?proced_help='+item;
	  parent.document.form1.itens_retorna.disabled = false;
    
}
function js_contageral(numero,idobj){

  if(ultimoclicado!=0){ 
    document.getElementById(ultimoclicado).style.color = 'blue';
    document.getElementById(ultimoclicado).style.fontWeight = 'bold';
  }
  document.getElementById(idobj).style.color = 'red';
  document.getElementById(idobj).style.fontWeight = 'bold';
  
  ultimoclicado = idobj;
//  if(ja_executado == false){
//    ja_executado = true;
//    alert(numero);
//    numero = numero/40;
//    numero = numero.toFixed(0);
//    document.body.scrollTop = (numero*319);
//  }
}

</script>
<style>
.clicado {
  color : red;
}
.desclicado {
  color : blue;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post">
<table>
<tr>
<td valign="top">
<?
  $seleciona_help = true;
//  if(isset($cadhelp)){
    $qhelp = 0;
    $cldb_modulos = new cl_db_modulos;
    $result_modulo = $cldb_modulos->sql_record($cldb_modulos->sql_query($modulo,'nome_modulo,nome_manual'));
    if($result_modulo!=false && $cldb_modulos->numrows>0){
      db_fieldsmemory($result_modulo,0);
      echo "Manual: ";
      if( is_file("manuais/".strtolower($nome_manual)."/Manual_".ucfirst($nome_manual).".odt")){
          echo "<a href='#' onclick='window.open(\"manuais/".strtolower($nome_manual)."/Manual_".ucfirst($nome_manual).".odt\")'>ODT</a>&nbsp&nbsp";
            if( is_file("manuais/".strtolower($nome_manual)."/Manual_".ucfirst($nome_manual).".pdf")){
                  echo "<a href='#' onclick='window.open(\"manuais/".strtolower($nome_manual)."/Manual_".ucfirst($nome_manual).".pdf\")'>PDF</a>";
                    }
      }else{
          echo "Manual não disponível.";
      } 
    }
    echo "<hr>";
    $seleciona_help = false;
//  }
  if($item != 0 || $modulo != 0){
    
  // verifica se tem item, senao pesquisa por modulo
  //echo "Programa: ".$pagina."  -  item : ".$item."  modulo: ".$modulo;
  //$cldb_cadhelp = new cl_db_cadhelp;
  //$result = $cldb_cadhelp->sql_record($cldb_cadhelp->sql_query());
  if($item!=""){
    $qitem = $item;    
  }else{
    $qitem = $modulo;
  }
  ?>
  <table>
  <?
  $ambiente = 1;		  		   
  $mod = $modulo;
  $wid = 15;
  $conta = 0;
  $qhelp = 0;
  $contageral = 0;
  $contageralaqui = 0;
  /***************/			


  function lista_help($item,$descr){
      global $seleciona_help,$contageral,$mod,$qitem,$conta,$wid,$DB_SELLER;
			// lista os helps existentes
      echo " <a title='Item:$item' style=\"color:blue;font-weight:bold\" href='#' onclick=\"js_atualiza_help($item);return false;\">".$descr."</a><br>\n";
        //echo "<font ".(isset($DB_SELLER)?" title='Item:".$item."'":"").">".$descr."</font><br>\n";

  }

  function submenus($item,$id,$mod) {
      global $conta, $contageral, $qhelp, $contageralaqui, $DB_SELLER;
      global $wid;
      global $ambiente;
      global $qitem, $seleciona_help;
			// lista os procedimentos existentes
      /*
      $help = pg_exec("select distinct
                              p.codproced,
															p.descrproced
                       from db_syscadproceditem pi 
														inner join db_syscadproced p on p.codproced = pi.codproced
		                   where pi.id_item = $item");
      if($help!=false&&pg_numrows($help)>0){

        for($x=0;$x<pg_numrows($help);$x++){
           echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" >";
	         echo "<a ".(isset($DB_SELLER)?" title='Procedimento:".pg_result($help,$x,"codproced")."'":"")." style=\"color:green;font-weight:bold;font-style:italic\" href='' id='".pg_result($help,$x,"codproced")."' onclick=\"js_atualiza_help_proced('".pg_result($help,$x,"codproced")."');return false;\">".ucfirst(strtolower(pg_result($help,$x,"descrproced")))."</a><br>\n";
        }
				
      }
      */
//  lista helps e aqui
      
      $sub = pg_exec("
      select m.id_item_filho,i.descricao,i.help,i.funcao,m.id_item,m.modulo 
		  from db_menu m 
			     inner join db_itensmenu i on i.id_item = m.id_item_filho 
		  where m.modulo = $mod 
			  and m.id_item = $item 
        and i.libcliente = true
		  	and i.itemativo = $ambiente
      order by menusequencia"); 
//			and m.id_item_filho in (select id_item from db_itenshelp h where h.id_item = m.id_item_filho) ");
      $numrows = pg_numrows($sub);
      if($numrows > 0) {
	      for($x = 0;$x < $numrows;$x++) {                  
	        $valor = pg_result($sub,$x,"id_item_filho");
	        echo "<img src=\"imagens/alinha.gif\" height=\"5\" id=\"Img".$conta."\" width=\"".$wid."\" >";
	         
          lista_help(pg_result($sub,$x,"id_item_filho"),pg_result($sub,$x,"descricao"));
          //echo pg_result($sub,$x,"descricao")."<br>\n";


	        $wid += 15;
	        $conta++;
	        $contageral ++;
	        submenus(pg_result($sub,$x,"id_item_filho"),$id,$mod);
	        $wid -= 15;
	      }				                
      }
    }
    
    $SQL = "select i.id_item as pai,m.id_item,m.id_item_filho,m.modulo,i.descricao,i.help,i.funcao 
	    from db_itensmenu i 
		 inner join db_menu m on m.id_item_filho = i.id_item 
	    where m.modulo = ".$modulo."
		  and i.itemativo = $ambiente							  
      and i.libcliente = true
		  and m.id_item = ".$modulo." order by menusequencia";
    $result = pg_exec($SQL);			
    for($i = 0;$i < pg_numrows($result);$i++) {
      $valor = pg_result($result,$i,"id_item_filho");
      echo "<tr><td valign=\"top\" nowrap>
	    <label for=\"ID$valor\">".pg_result($result,$i,"descricao")."</label><br>\n";
      submenus(pg_result($result,$i,"pai"),"col".$i,db_strpos($modulo,"##"));
      echo "</td></tr>\n";
    }	   
  }
  ?>
</td>
</tr>
</table>
</form>
</body>
</html>
<?
if(isset($item)){
 echo "<script>
       js_atualiza_help($item);
       </script>";
}
