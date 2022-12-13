<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

  require(modification("libs/db_stdlib.php"));
  require(modification("libs/db_conecta.php"));
  include(modification("libs/db_sessoes.php"));
  include(modification("libs/db_usuariosonline.php"));
  include(modification("dbforms/db_funcoes.php"));
  include(modification("classes/db_aguabase_classe.php"));
  
  db_postmemory($HTTP_POST_VARS);
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

  $claguabase = new cl_aguabase;
  $claguabase->rotulo->label("x01_matric");
  $claguabase->rotulo->label("x01_numcgm");
  $claguabase->rotulo->label("x01_promit");
  $claguabase->rotulo->label("x01_codrua");
  $claguabase->rotulo->label("x01_codbairro");
  $claguabase->rotulo->label("x01_quadra");

  $clrotulo = new rotulocampo;
  $clrotulo->label("z01_nome");
  $clrotulo->label("j14_nome");
  $clrotulo->label("j13_descr");
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
          <form name="form2" method="post" action="" >
	          <table width="35%" border="0" align="center" cellspacing="0">
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_matric?>">
	                  <?=$Lx01_matric?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                db_input("x01_matric",10,$Ix01_matric,true,"text",4,"","chave_x01_matric");
	                  ?>
	                </td>
	              </tr>
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_numcgm?>">
	                  <?=$Lx01_numcgm?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                //db_input("x01_numcgm",10, $Ix01_numcgm,true,"text",4,"","chave_x01_numcgm");
			                db_input("z01_nome",40, $Iz01_nome,true,"text",4,"","chave_x01_numcgm");
			              ?>
	                </td>
	              </tr>
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_promit?>">
	                  <?=$Lx01_promit?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                //db_input("x01_numcgm",10, $Ix01_numcgm,true,"text",4,"","chave_x01_numcgm");
			                db_input("z01_nome",40, $Iz01_nome,true,"text",4,"","chave_x01_promit");
			              ?>
	                </td>
	              </tr>
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_codrua?>">
	                  <?=$Lx01_codrua?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                //db_input("x01_codrua",10,$Ix01_codrua,true,"text",4,"","chave_x01_codrua");
			                db_input("j14_nome",40, $Ij14_nome,true,"text",4,"","chave_x01_codrua");
		                ?>
	                </td>
	              </tr>
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_codbairro?>">
	                  <?=$Lx01_codbairro?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                //db_input("x01_codrua",10,$Ix01_codrua,true,"text",4,"","chave_x01_codrua");
			                db_input("j13_descr",40, $Ij13_descr,true,"text",4,"","chave_x01_codbairro");
	                  ?>
	                </td>
	              </tr>
	              <tr> 
	                <td width="4%" align="right" nowrap title="<?=$Tx01_quadra?>">
	                  <?=$Lx01_quadra?>
	                </td>
	                <td width="96%" align="left" nowrap> 
	                  <?
			                db_input("x01_quadra",5, $Ix01_quadra,true,"text",4,"","chave_x01_quadra");
		                ?>
	                </td>
	              </tr>
	              <tr> 
	                <td colspan="2" align="center"> 
	                  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
	                  <input name="limpar" type="reset" id="limpar" value="Limpar" >
	                  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguabase.hide();">
	                </td>
	              </tr>
	          </table>
	        </form>
        </td>
      </tr>
      <tr> 
        <td align="center" valign="top"> 
          <?
            if (!isset($pesquisa_chave)) {
              if (isset($campos) == false) {
                if (file_exists("funcoes/db_func_aguabase.php") == true) {
                  include(modification("funcoes/db_func_aguabase.php"));
                } else {
                  $campos = "aguabase.*";
                }
              }
              
              if (isset($chave_x01_matric) && (trim($chave_x01_matric) != "")) {
                $sql = $claguabase->sql_query($chave_x01_matric,$campos,"x01_matric");
              
              } else if (isset($chave_x01_numcgm) && (trim($chave_x01_numcgm) != "")){
                
                $sql = $claguabase->sql_query("", $campos, "a.z01_nome", " a.z01_nome like '$chave_x01_numcgm%' ");
					 
	            } else if (isset($chave_x01_promit) && (trim($chave_x01_promit) != "")) {
	              $sql = $claguabase->sql_query("", $campos, "b.z01_nome", " b.z01_nome like '$chave_x01_promit%' ");
				 
              } else if (isset($chave_x01_codrua) && (trim($chave_x01_codrua) != "")) {
	              $sql = $claguabase->sql_query("", $campos, "j14_nome,x01_numero", " j14_nome like '$chave_x01_codrua%' ");
              
              } else if (isset($chave_x01_codbairro) && (trim($chave_x01_codbairro) != "")) {
	              $sql = $claguabase->sql_query("", $campos, "j13_descr,j14_nome,x01_numero", " j13_descr like '$chave_x01_codbairro%' ");
              
              } else if (isset($chave_x01_quadra) && (trim($chave_x01_quadra) != "")) {
	              $sql = $claguabase->sql_query("", $campos, "x01_quadra,j13_descr,j14_nome,x01_numero", " x01_quadra like '$chave_x01_quadra%' ");
              
              } else {
                //$sql = $claguabase->sql_query("",$campos,"x01_matric"," x01_matric < 0");
	              $sql = "";
              }
				
              //echo $sql;
              db_lovrot($sql,15,"()","",$funcao_js);
            
            } else {
        
              if ($pesquisa_chave != null && $pesquisa_chave != "") {
          
                $result = $claguabase->sql_record($claguabase->sql_query($pesquisa_chave));
          
                if ($claguabase->numrows != 0) {
                  db_fieldsmemory($result,0);

	                //$x01_numcgm = $z01_nome;
                  echo "<script>".$funcao_js."('$z01_nome',false);</script>";
                } else {
	                
                  echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                }
              
              } else {
	              echo "<script>".$funcao_js."('',false);</script>";
              }
            }
          ?>
        </td>
      </tr>
      <?
        echo "<script>
			          document.form2.chave_x01_codrua.focus();
	            </script>
		         ";
      ?>
    </table>
  </body>
</html>

<?
  if(!isset($pesquisa_chave)){
?>
   <script>
	   document.form2.chave_x01_codrua.focus();
   </script>
<?
  }
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
