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
include("classes/db_db_modulos_classe.php");
include("classes/db_db_versao_classe.php");

db_postmemory($HTTP_POST_VARS);

$clrotulo      		= new rotulocampo;
$cldb_modulos       = new cl_db_modulos;
$cldb_versao          = new cl_db_versao;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_pesquisa(){ 
  document.form1.submit();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<table border="0" width="100%" align="center" cellspacing="0" bgcolor="#CCCCCC">
<form name='form1' method='post'>
<tr>
<td align='center'>
<strong>Modulo:
<?

$sqlmodulo = "select distinct db_modulos.id_item,nome_modulo 
              from db_permissao 
                   inner join db_modulos on db_modulos.id_item = id_modulo 
                   inner join db_itensmenu on db_itensmenu.id_item = db_modulos.id_item
              where db_itensmenu.libcliente is true and id_usuario = $id_usuario
                and anousu= $ano ";
$res = pg_query($sqlmodulo);

db_selectrecord('id_item',$res,true,2,'','','','0-Todos',' js_pesquisa();');
?>
</strong>
</td>
</tr>
<tr>
</td>
<?

function monta_menu_func_contarefa($item_modulo,$id_modulo,$id_usuario,$espacos,$nivel=1,$procedimento=0){


  if($nivel==0){
    echo "<table border ='0'>";
    echo "<tr valign='top'>";
  }
	
  $sql = "select distinct id_item_filho,descricao ,funcao, i.id_item, (select riproced from fc_syscadproced(i.id_item)) as codproced, (select rcdescr from fc_syscadproced(i.id_item)) as descrproced,menusequencia
          from db_menu m
               inner join db_itensmenu i on i.id_item = id_item_filho
               inner join db_permissao p on p.id_item = i.id_item
                                        and p.id_usuario = $id_usuario
          where m.id_item = $item_modulo
            and m.modulo = $id_modulo and
						itemativo = '1'
          order by menusequencia
           ";
  //echo "<br><br>menu pai == $sql <br> ";
  $res = pg_exec($sql) or die($sql);

  if(pg_numrows($res)>0){

    for($i=0;$i<pg_numrows($res);$i++){

      $item_filho		= pg_result($res,$i,0);
      $descricao		= pg_result($res,$i,1);
      $funcao				= trim(pg_result($res,$i,2));
      $item					= trim(pg_result($res,$i,3));
      $codproced		= trim(pg_result($res,$i,4));
			$descrproced	= trim(pg_result($res,$i,5));
			
      if($nivel ==0){
        echo "<td nowrap> ";
       
      }
      if( $funcao != "" ){

        $sql = "select distinct p.codproced, p.descrproced
                from db_syscadproceditem i
                     inner join db_syscadproced p on p.codproced = i.codproced 
                where id_item = $item_filho";
      //  echo "<br><br> menu filho == $sql <br> ";
        $resproced = pg_exec($sql);
        if(pg_numrows($resproced)>0){
          global $codproced,$descrproced;
          db_fieldsmemory($resproced,0);
          $descr = $descrproced."\n";
          if(pg_numrows($resproced)>1){
            for($p=0;$p<pg_numrows($resproced);$p++){
              global $codproced,$descrproced;
              db_fieldsmemory($resproced,$p);
              $descr .= $descrproced."\n";
            }
          }
          echo "$espacos $descricao <br>";
        }else{
          if($procedimento>0){
            $sql = "select descrproced 
                from db_syscadproced  
                where codproced = $procedimento";
            $resproced = pg_exec($sql);
            global $descrproced;
            db_fieldsmemory($resproced,0);
            echo "$espacos  $descricao <br>";
          }else{
            echo "$espacos $descricao <br>";
          
          }
        }
      }else{
       // echo "<font size='2' > $espacos  $descricao</font><br>";
        if($nivel == 0 ){
           echo "<b>$espacos $descricao </b><br>";
        }else{
          echo "$espacos $descricao <br>";
        }
        
        
        $sql = "select distinct p.codproced, p.descrproced 
                from db_syscadproceditem i
                     inner join db_syscadproced p on p.codproced = i.codproced 
                where id_item = $item_filho";
      //  echo "<br><br> menu filho else== $sql <br> ";
        $resproced = pg_exec($sql);

        if(pg_numrows($resproced)>0){
          global $codproced;
          db_fieldsmemory($resproced,0);
          monta_menu_func_contarefa($item_filho,$id_modulo,$id_usuario,$espacos."&nbsp&nbsp&nbsp",1,@$codproced);
        }else{
          monta_menu_func_contarefa($item_filho,$id_modulo,$id_usuario,$espacos."&nbsp&nbsp&nbsp",1);
        }
      }
      if($nivel ==0){
        echo "</td>";
      }
    }

  }
  if($nivel==0){
    echo "</tr>";
    echo "</table>";
    
  }
 
}

if( isset( $id_item ) ){
 
  monta_menu_func_contarefa($id_item,$id_item,$id_usuario,'',0);
} 



?>
</form>
</td>
</tr>
</table>
</body>
</html>
<script>

function js_busca_procedimento(coditem,descrmenu,codproced,descrproced){
 
  parent.document.form1.item_menu.value = coditem;
  parent.document.form1.descr_menu.value = descrmenu;
  parent.document.form1.codproced.value = codproced;
  parent.document.form1.codproceddescr.value = descrproced;
  parent.db_iframe_tarefa_menu.hide();
  if( parent.document.form1.alterar == undefined ){
    parent.document.form1.submit();
  }

	if( codproced != null ){
		P = parent.document.getElementById("codproced");
		PD = parent.document.getElementById("codproceddescr");
		for(i=0;i<P.length;i++){
			if( P[i].value == codproced ){
				break;
			}
		}
		if ( i < P.length ){
			P[i].selected = true;
			PD[i].selected = true;
		}else{
			P.options[P.options.length] = new Option(codproced,codproced);
			PD.options[P.options.length] = new Option(descrproced,codproced);
			P.options[P.options.length-1].selected = true;
			PD.options[PD.options.length-1].selected = true;
		}
	}
	

}

</script>