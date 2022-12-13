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
include("classes/db_clientesmodulos_classe.php");
include("classes/db_db_versao_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

db_postmemory($HTTP_POST_VARS);

$clrotulo      		= new rotulocampo;

$clclientesmodulos       = new cl_clientesmodulos;
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
function js_preenche(item){
  document.form1.id_item.value = item;
  js_pesquisa();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" align="center" cellspacing="0" bgcolor="#CCCCCC">
<form name='form1' method='post'>
<tr>
<td>
<?
if( !isset($id_item) || $id_item == 0 ){

  echo "<input name='id_item' type='hidden' value=''>";  
  if(isset($verifica_area)){
     $res = $clclientesmodulos->sql_query_areas(null,"at74_id_item as id_item,nome_modulo,at25_descr,at74_data,at74_obs::varchar,$cod_usuario as cod_usuario",'nome_modulo'," clientesmodulos.at74_codcli = $cliente");
  }else{
     $res = $clclientesmodulos->sql_query(null,"at74_id_item as id_item,nome_modulo,at74_data,at74_obs::varchar,$cod_usuario as cod_usuario",'nome_modulo'," clientesmodulos.at74_codcli = $cliente");
  }
  //db_criatabela($res);

  db_lovrot($res,100,"()","","js_preenche|id_item|cod_usuario");


}else{
?>
  <strong>Modulo:
  <input name='cod_usuario' type='hidden' value='<?=$cod_usuario?>'>  
  <?
  $res = $clclientesmodulos->sql_record($clclientesmodulos->sql_query(null,"at74_id_item as id_item,trim(nome_modulo)||' Liberado: '||case when at74_data is null then '  ' else to_char(at74_data,'dd-mm-YYYY') end as nome_modulo",'nome_modulo'," clientesmodulos.at74_codcli = $cliente"));
  db_selectrecord('id_item',$res,true,2,'','','','0-Todos',' js_pesquisa();');
  ?>
  </strong>
  <?
}
?>
  </td>
</tr>
<tr>
</td>
<?

function monta_menu_func_contarefa($item_modulo,$id_modulo,$espacos,$nivel=1,$procedimento=0,$cliente=0){

  global $cod_usuario;

  if($nivel==0){
    echo "<table>";
    echo "<tr valign='top'>";
  }
	
  $sql = "select id_item_filho,descricao ,funcao, i.id_item, (select riproced from fc_syscadproced(i.id_item)) as codproced, (select rcdescr from fc_syscadproced(i.id_item)) as descrproced
          from db_menu m
               inner join db_itensmenu i on i.id_item = id_item_filho
          where m.id_item = $item_modulo
            and m.modulo = $id_modulo and
						itemativo = '1'
          order by menusequencia";
  $res = pg_exec($sql) or die($sql);

  if(pg_numrows($res)>0){

    for($i=0;$i<pg_numrows($res);$i++){

      $item_filho		= pg_result($res,$i,0);
      $descricao		= pg_result($res,$i,1);
      $funcao				= trim(pg_result($res,$i,2));
      $item					= trim(pg_result($res,$i,3));
      $codproced		= trim(pg_result($res,$i,4));
			$descrproced	= trim(pg_result($res,$i,5));

      $sqlbuscamenus = "select upper(rsmenu) as rsmenu from fc_buscamenus('$item', 1) order by riseq desc";
      $resultbuscamenus = pg_query($sqlbuscamenus) or die($sqlbuscamenus);
      global $rsmenu;
      $menu_completo="";
      if (pg_numrows($resultbuscamenus) > 0) {
        for ($id_busca=0; $id_busca<pg_numrows($resultbuscamenus); $id_busca++){
          db_fieldsmemory($resultbuscamenus, $id_busca);
          $menu_completo.=($menu_completo==""?"":" > ").strtoupper($rsmenu);
        }
        $menu_completo.="\\n\\n";
      }
			
      if($nivel ==0){
        echo "<td nowrap>";
      }
      if( $funcao != "" ){

        $sql = "select distinct p.codproced, p.descrproced, m.codmod, m.nomemod, at75_data ".(isset($cod_usuario)?", at76_usuario":"")."
                from db_syscadproceditem i
                     inner join db_syscadproced p on p.codproced = i.codproced 
                     inner join db_sysmodulo m on m.codmod = p.codmod
                     left join ( 
                        select at75_codproced,at75_data,at76_usuario
                        from clientesmodulos m
                             inner join clientesmodulosproc c on c.at75_seqclimod = m.at74_sequencial ";
        if(isset($cod_usuario)){
          $sql .= "
                             left join clientesmodulosprocusu u on c.at75_sequen = u.at76_seqproc
                      where at74_codcli = $cliente and at76_usuario = $cod_usuario ) as x on p.codproced = at75_codproced ";
        }else{
          $sql .= "  where at74_codcli = $cliente ) as x on p.codproced = at75_codproced ";
        }
        $sql .= "
                where id_item = $item_filho";
        $resproced = pg_exec($sql);

        if(pg_numrows($resproced)>0){
          global $codproced,$descrproced,$codmod,$nomemod,$at75_data,$at76_usuario;
          db_fieldsmemory($resproced,0);
          $descr = $descrproced."\n";
          if(pg_numrows($resproced)>1){
            for($p=0;$p<pg_numrows($resproced);$p++){
              global $codproced,$descrproced,$codmod,$nomemod,$at75_data,$at76_usuario;
              db_fieldsmemory($resproced,$p);

              echo "<font size='2'  >$espacos ".($at76_usuario==""?"":"*")."<a ".($at75_data==""?"style='color:red'":"")." href='#' title='$descrproced $codmod' onclick='js_busca_procedimento(\"$item_filho\",\"$descricao\",\"$codproced\",\"$codmod\",\"$menu_completo\")'>$descricao $descrproced</a></font><br>";
              //$descr .= $descrproced."\n";
            }
          }else{
              echo "<font size='2' >$espacos ".($at76_usuario==""?"":"*")."<a ".($at75_data==""?"style='color:red'":"")." href='#' title='$descrproced $codmod' onclick='js_busca_procedimento(\"$item_filho\",\"$descricao\",\"$codproced\",\"$codmod\",\"$menu_completo\")'>$descricao</a></font><br>";
          }
        }else{
          if($procedimento>0){
            $sql = "select p.descrproced ,p.codmod, at75_data
                from db_syscadproced  p
                     inner join db_sysmodulo m on m.codmod = p.codmod
                      left join ( 
                        select at75_codproced,at75_data 
                        from clientesmodulos m
                             inner join clientesmodulosproc c on c.at75_seqclimod = m.at74_sequencial
                        where at74_codcli = $cliente ) as x on p.codproced = at75_codproced
 
                where codproced = $procedimento";
            $resproced = pg_exec($sql);
            global $descrproced,$codmod,$at75_data,$at76_usuario;
            db_fieldsmemory($resproced,0);
 
            echo "<font size='2' >$espacos  ".($at76_usuario==""?"":"*")."<a ".($at75_data==""?"style='color:red'":"")." href='#' title='$descrproced $codmod' onclick='js_busca_procedimento(\"$item_filho\",\"$descricao\",\"$codproced\",\"$codmod\",\"$menu_completo\")'>$descricao</a></font><br>";
          }else{

            echo "<font size='2'  >$espacos  $descricao</font><br>";
          
          }
        }
      }else{
        echo "<font size='2' >$espacos  $descricao</font><br>";
        $sql = "select distinct p.codproced, p.descrproced 
                from db_syscadproceditem i
                     inner join db_syscadproced p on p.codproced = i.codproced 
                where id_item = $item_filho";
        $resproced = pg_exec($sql);

        if(pg_numrows($resproced)>0){
          global $codproced;
          db_fieldsmemory($resproced,0);
          monta_menu_func_contarefa($item_filho,$id_modulo,$espacos."&nbsp&nbsp&nbsp",1,@$codproced,$cliente);
        }else{
          monta_menu_func_contarefa($item_filho,$id_modulo,$espacos."&nbsp&nbsp&nbsp",1,0,$cliente);
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

if( isset( $id_item ) && $id_item != 0 ){
  monta_menu_func_contarefa($id_item,$id_item,'',0,0,$cliente);
} 



?>
</form>
</td>
</tr>
</table>
</body>
</html>
<script>

function js_busca_procedimento(coditem,descrmenu,codproced,codmod,menu_completo){
 
  parent.document.form1.item_menu.value = coditem;
  parent.document.form1.descr_menu.value = descrmenu;

  parent.document.form1.codproced.value = codproced;
  parent.document.form1.codproceddescr.value = codproced;

  parent.document.form1.modulo.value = codmod;
  parent.document.form1.modulodescr.value = codmod;

  parent.document.form1.at05_feito.value = menu_completo + parent.document.form1.at05_feito.value;

  parent.db_iframe_tarefa_menu.hide();
  
  if( parent.document.form1.alterar == undefined ){
    parent.document.form1.codproceddescr.onchange();
  }

/*	if( codproced != null ){
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
	*/

}

</script>