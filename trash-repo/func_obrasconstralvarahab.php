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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_obrasconstr_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clRotulo 		 = new rotulocampo;
$clobrasconstr = new cl_obrasconstr;

$clobrasconstr->rotulo->label("ob08_codconstr");
$clobrasconstr->rotulo->label("ob08_codobra");
$clRotulo->label("j01_matric");
$clRotulo->label("ob06_setor");
$clRotulo->label("ob06_quadra");
$clRotulo->label("ob06_lote");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%"  nowrap title="<?=$Tob08_codconstr?>">
              <?=$Lob08_codconstr?>
            </td>
            <td width="96%" nowrap> 
              <?
		       db_input("ob08_codconstr",10,$Iob08_codconstr,true,"text",4,"","chave_ob08_codconstr");
		       ?>
            </td>
          </tr>
          
          <tr> 
            <td width="4%"nowrap title="<?=$Tob08_codobra?>">
              <?=$Lob08_codobra?>
            </td>
            <td width="96%"nowrap> 
              <?
		          db_input("ob08_codobra",10,$Iob08_codobra,true,"text",4,"","chave_ob08_codobra");
		          ?>
            </td>
          </tr>
          
	        <tr>
	        	<td title="<?=@$Tj01_matric?>">
	        	  <?=$Lj01_matric?>
	          </td>
	          <td>
	          	<?
	          		db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1)
	          	?>
	        	</td>
	        </tr>
	        
	        <tr>
	        	<td title="<?=@$Tob06_setor?>">
	        	  <strong>Setor/Quadra/Lote: </strong>
	          </td>
	          <td nowrap>
	          <?
	            db_input('ob06_setor',10,$Iob06_setor,true,'text',1,"")
	          ?>
	          /
	          <?
	            db_input('ob06_quadra',10,$Iob06_quadra,true,'text',1,"")
	          ?>
	          /
	          <?
	            db_input('ob06_lote',10,$Iob06_lote,true,'text',1,"")
	          ?>
	        	</td>
	        </tr>  
          
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obrasconstr.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
			$sWhere = "";
			$aWhere = array();
				
			if ( !isset( $pesquisa_chave ) ) {

			  if ( isset($chave_ob08_codconstr) && ( trim($chave_ob08_codconstr) != "" ) ) {
			    $sWhere =  "where ob08_codconstr = $chave_ob08_codconstr";

			  } else if(isset($chave_ob08_codobra) && (trim($chave_ob08_codobra) != "" ) ) {
			    $sWhere =  "where ob08_codobra like '$chave_ob08_codobra%'";
			  }
			  
			  if(!empty($ob06_setor)) {
			    $aWhere [] = "ob06_setor  = '{$ob06_setor}'";
			  }
			  if(!empty($ob06_quadra)) {
			    $aWhere [] = "ob06_quadra = '{$ob06_quadra}'";
			  }
			  if(!empty($ob06_lote)) {
			    $aWhere [] = "ob06_lote   = '{$ob06_lote}'";
			  }
			  if( !empty($j01_matric) ) {
			    $aWhere [] = "ob24_iptubase = {$j01_matric}";
			  }
			  $sConcatWhere = count($aWhere) == 0 ? "" : " where ";
			  
			  $sSqlHabiteSe = "   select *                                                                                             \n";
			  $sSqlHabiteSe.= "     from (  select obrasconstr.ob08_codconstr,                                                         \n";
			  $sSqlHabiteSe.= "                    obrasconstr.ob08_codobra,                                                           \n";
			  $sSqlHabiteSe.= "                    obrasconstr.ob08_area,                                                              \n";
			  $sSqlHabiteSe.= "                    obras.ob01_nomeobra,                                                                \n";
			  $sSqlHabiteSe.= "                    case when ob01_regular is false then ob06_setor  else j34_setor  end as ob06_setor ,\n";
			  $sSqlHabiteSe.= "                    case when ob01_regular is false then ob06_quadra else j34_quadra end as ob06_quadra,\n";
			  $sSqlHabiteSe.= "                    case when ob01_regular is false then ob06_lote   else j34_lote   end as ob06_lote   \n";
			  $sSqlHabiteSe.= "               from obrasconstr                                                                         \n";
			  $sSqlHabiteSe.= "                    inner join caracter      on caracter.j31_codigo        = obrasconstr.ob08_ocupacao  \n";
			  $sSqlHabiteSe.= "                    inner join obras         on obras.ob01_codobra         = obrasconstr.ob08_codobra   \n";
			  $sSqlHabiteSe.= "                    inner join cargrup       on cargrup.j32_grupo          = caracter.j31_grupo         \n";
			  $sSqlHabiteSe.= "                    inner join obrastiporesp on obrastiporesp.ob02_cod     = obras.ob01_tiporesp        \n";
			  $sSqlHabiteSe.= "                    inner join obrasalvara   on obrasalvara.ob04_codobra   = obrasconstr.ob08_codobra   \n";
			  $sSqlHabiteSe.= "                    left  join obrashabite   on obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr \n";
			  $sSqlHabiteSe.= "                    left  join obrasiptubase on obrasiptubase.ob24_obras   = obras.ob01_codobra         \n";
			  $sSqlHabiteSe.= "                    left  join iptubase      on obrasiptubase.ob24_iptubase= iptubase.j01_matric        \n";
			  $sSqlHabiteSe.= "                    left  join lote          on lote.j34_idbql             = iptubase.j01_idbql         \n";
			  $sSqlHabiteSe.= "                    left  join obraslotei    on obraslotei.ob06_codobra    = obras.ob01_codobra         \n";
			  $sSqlHabiteSe.= "                    {$sWhere}                                                                           \n";
			  $sSqlHabiteSe.= "           group by obrasconstr.ob08_codconstr,                                                         \n";
			  $sSqlHabiteSe.= "                    obrasconstr.ob08_codobra,                                                           \n";
			  $sSqlHabiteSe.= "                    obras.ob01_nomeobra,                                                                \n";
			  $sSqlHabiteSe.= "                    ob08_area,                                                                          \n";
			  $sSqlHabiteSe.= "                    ob01_regular,                                                                       \n";
			  $sSqlHabiteSe.= "                    ob06_setor,                                                                         \n";
			  $sSqlHabiteSe.= "                    ob06_quadra,                                                                        \n";
			  $sSqlHabiteSe.= "                    ob06_lote,                                                                          \n";
			  $sSqlHabiteSe.= "                    j34_setor ,                                                                         \n";
			  $sSqlHabiteSe.= "                    j34_quadra,                                                                         \n";
			  $sSqlHabiteSe.= "                    j34_lote                                                                            \n";
			  $sSqlHabiteSe.= "             having ob08_area <> sum(ob09_area)                                                         \n";
			  $sSqlHabiteSe.= "                 or min(ob09_codhab) is null) as query_pesquisa                                         \n";
			  $sSqlHabiteSe.= "    {$sConcatWhere} ".implode(" and ", $aWhere)."                                                       \n";
			  $sSqlHabiteSe.= " order by ob08_codconstr desc                                                                           ";
			  
			  db_lovrot($sSqlHabiteSe,15,"()","",$funcao_js);
			  
			} else {
			  
			  if ($pesquisa_chave != null && $pesquisa_chave!="") {
			    
			    $sSqlHabiteSe = "   select obrasconstr.ob08_codconstr,                                                         ";
			    $sSqlHabiteSe.= "          obrasconstr.ob08_codobra,                                                           ";
			    $sSqlHabiteSe.= "          obras.ob01_nomeobra,                                                                ";
			    $sSqlHabiteSe.= "          ob08_area                                                                           ";
			    $sSqlHabiteSe.= "     from obrasconstr                                                                         ";
			    $sSqlHabiteSe.= "          inner join caracter      on caracter.j31_codigo        = obrasconstr.ob08_ocupacao  ";
			    $sSqlHabiteSe.= "          inner join obras         on obras.ob01_codobra         = obrasconstr.ob08_codobra   ";
			    $sSqlHabiteSe.= "          inner join cargrup       on cargrup.j32_grupo          = caracter.j31_grupo         ";
			    $sSqlHabiteSe.= "          inner join obrastiporesp on obrastiporesp.ob02_cod     = obras.ob01_tiporesp        ";
			    $sSqlHabiteSe.= "          inner join obrasalvara   on obrasalvara.ob04_codobra   = obrasconstr.ob08_codobra   ";
			    $sSqlHabiteSe.= "          left join obrashabite    on obrashabite.ob09_codconstr = obrasconstr.ob08_codconstr ";
			    $sSqlHabiteSe.= "    where ob08_codconstr = {$pesquisa_chave}                                                  ";
			    $sSqlHabiteSe.= " group by obrasconstr.ob08_codconstr,                                                         ";
			    $sSqlHabiteSe.= "          obrasconstr.ob08_codobra,                                                           ";
			    $sSqlHabiteSe.= "          obras.ob01_nomeobra,                                                                ";
			    $sSqlHabiteSe.= "          ob08_area                                                                           ";
			    $sSqlHabiteSe.= "   having ob08_area <> sum(ob09_area)                                                         ";
			    $sSqlHabiteSe.= "       or min(ob09_codhab) is null                                                            ";
			    $sSqlHabiteSe.= " order by ob08_codconstr                                                                      ";
			     
			    	
			    $rsHabiteSe     = db_query($sSqlHabiteSe);
			    $iNumRowsHabite = pg_num_rows($rsHabiteSe);
			    	
			    if ($iNumRowsHabite != 0) {
			      
			      db_fieldsmemory($rsHabiteSe, 0);
			      echo "<script>".$funcao_js."('$ob08_codobra','$ob08_area',false);</script>";
			    }else{
			      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
			    }
			    
			  }else{
			    echo "<script>".$funcao_js."('','',false);</script>";
			  }
			}
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>