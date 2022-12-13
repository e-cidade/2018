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
include("classes/db_iptubase_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label("j01_matric");
$clrotulo = new rotulocampo;
$clrotulo->label("j40_refant");
$clrotulo->label("z01_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("d41_testada");
$clrotulo->label("d41_eixo");
$clrotulo->label("d41_obs");
$clrotulo->label("j34_zona");

$result = $cliptubase->sql_record($cliptubase->proprietario_query($j01_matric));
if($result==false || $cliptubase->numrows == 0 ){
  $cliptubase->erro(true,false);
  exit;
}
db_fieldsmemory($result,0);

  $sql="select j36_testad as d41_testada
	from lote
		inner join testada on j36_idbql=j34_idbql 
	where j34_setor='$j34_setor' and j34_quadra='$j34_quadra' and j34_lote='$j34_lote'";
  $resi=pg_query($sql) or die($sql);	
	if (pg_numrows($resi) == 0) {
		$cliptubase->erro(true,false);
		exit;
	}
  db_fieldsmemory($resi,0);
 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_atualiza(){

  
 parent.js_matricontri2(document.form1.j01_matric.value,document.form1.j40_refant.value,document.form1.z01_nome.value,document.form1.j34_setor.value,document.form1.j34_quadra.value,document.form1.j34_lote.value,document.form1.j34_zona.value,document.form1.d41_testada.value,document.form1.d41_eixo.value,document.form1.d41_obs.value,document.form1.j01_idbql.value);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <form name="form1" method="post" action="" >
  <tr> 
    <td height="63" align="center" valign="top">
      <input name="j01_idbql" type="hidden" value="<?=$j01_idbql?>">
      <center>
        <table border="0" align="center" cellspacing="0">
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj01_matric?>">
              <?=$Lj01_matric?>
            </td>
            <td width="66%" align="left" nowrap> 
              <?
              db_input("j01_matric",8,$Ij01_matric,true,"text",3,"");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj40_refant?>">
            <?
	    echo $Lj40_refant;
	    ?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j40_refant",20,$Ij40_refant,true,'text',3)
	    ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("z01_nome",40,$Iz01_nome,true,'text',3)
	    ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_setor?>">
            <?=$Lj34_setor?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_setor",4,$Ij34_setor,true,'text',3)
	    ?>
            </td>
          </tr>
           <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_quadra?>">
            <?=$Lj34_quadra?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_quadra",4,$Ij34_quadra,true,'text',3)
	    ?>
            </td>
          </tr>
            <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_lote?>">
            <?=$Lj34_lote?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_lote",4,$Ij34_lote,true,'text',3)
	    ?>
            </td>
          </tr>
         
	  <tr> 
            <td width="34%" align="left" nowrap title="<?=$Tj34_zona?>">
            <?=$Lj34_zona?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("j34_zona",4,$Ij34_zona,true,'text',3)
	    ?>
            </td>
          </tr>
 
	 
	  <tr> 
            <td width="34%" align="left" nowrap title="<?=$Td41_testada?>">
            <?=$Ld41_testada?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("d41_testada",15,$Id41_testada,true,'text',3)
	    ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Td41_eixo?>">
            <?=$Ld41_eixo?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("d41_eixo",15,$Id41_eixo,true,'text',4)
	    ?>
            </td>
          </tr>
          <tr> 
            <td width="34%" align="left" nowrap title="<?=$Td41_obs?>">
            <?=$Ld41_obs?>
            </td>
            <td width="66%" align="left" nowrap> 
            <?
	    db_input("d41_obs",40,$Id41_obs,true,'text',4)
	    ?>
            </td>
          </tr>
	  <tr> 
            <td align="center" colspan="2" >
	    <input name="confirma" value="Confirma" onclick="js_atualiza()" type="button">
            </td>
          </tr>
	  
        </table>
      </center>
      </td>
    </tr>
   </form>
</table>
</body>
</html>