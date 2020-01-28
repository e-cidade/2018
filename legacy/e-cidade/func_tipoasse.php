<?php
/**
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
include(modification("classes/db_tipoasse_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$cltipoasse = new cl_tipoasse;
$cltipoasse->rotulo->label("h12_codigo");
$cltipoasse->rotulo->label("h12_assent");
$cltipoasse->rotulo->label("h12_descr");

if (isset($chave_h12_assent)) {
  $chave_h12_assent = stripslashes($chave_h12_assent);
}

if (isset($chave_h12_descr)) {
  $chave_h12_descr = stripslashes($chave_h12_descr);
}

if (isset($chave_h12_natureza)) {
  $chave_h12_natureza = stripslashes($chave_h12_natureza);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <?
  if(!isset($consulta)){
  ?>
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_codigo?>">
              <?=$Lh12_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_codigo",6,$Ih12_codigo,true,"text",4,"","chave_h12_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_assent?>">
              <?=$Lh12_assent?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_assent",5,$Ih12_assent,true,"text",4,"","chave_h12_assent");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_descr?>">
              <?=$Lh12_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_descr",40,$Ih12_descr,true,"text",4,"","chave_h12_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <?php 
                $lDisabled = false;

                if ( isset($sAcao) && $sAcao == 'lancamento') { 
                  $lDisabled = true;
                } 
              ?>

              <input name="Fechar" type="button" id="fechar" <?php echo ($lDisabled) ? 'disabled' : ''  ?> value="Fechar" onClick="parent.db_iframe_tipoasse.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <?
  }
  ?>
  <tr> 
    <td align="center" valign="top"> 
      <?

      if ( isset($sAcao) && ($sAcao == 'vincularTipoAssentamentoDadosExternos' || $sAcao == 'agendaAssentamento')) {

        if(isset($pesquisa_chave)) {
          $chave_assent   = $pesquisa_chave;
          $pesquisa_chave ='';
        }
      }

      $sWhereTipoassedb_depart = "";
      if(isset($filtro_departamento) && $filtro_departamento == true) {
  	    $iCodigoDepartamento     = db_getsession('DB_coddepto');
  	    $sWhereTipoassedb_depart = " exists (select 1 from tipoassedb_depart where rh184_db_depart = $iCodigoDepartamento and rh184_tipoasse = h12_codigo)";
      }

      if (isset($chave_h12_assent)) {
        $chave_h12_assent = addslashes($chave_h12_assent);
      }

      if (isset($chave_h12_descr)) {
        $chave_h12_descr = addslashes($chave_h12_descr);
      }

      $dbwhere = "";
      $campos  = "tipoasse.h12_codigo,
                  tipoasse.h12_assent,
                  tipoasse.h12_descr,
                  tipoasse.h12_dias,
                  tipoasse.h12_relvan,
                  tipoasse.h12_relass,
                  tipoasse.h12_reltot,
                  tipoasse.h12_relgra,
                  tipoasse.h12_tipo,
                  tipoasse.h12_graefe,
                  tipoasse.h12_efetiv,
                  (CASE WHEN tipoasse.h12_tipefe = 'I' THEN 'INSS'
                        WHEN tipoasse.h12_tipefe = 'C' THEN 'Convertida'
                        ELSE 'Instituição'
                  END)::varchar(20) AS h12_tipefe,
                  tipoasse.h12_regenc,
                  tipoasse.h12_natureza,
                  tipoasse.h12_vinculaperiodoaquisitivo";
      if(isset($chave_h12_tipo) && trim($chave_h12_tipo) != ""){
	     $dbwhere = " h12_tipo = '".$chave_h12_tipo."'";
      }
      
      if(isset($filtro_departamento) && $filtro_departamento == true && isset($chave_h12_tipo) && trim($chave_h12_tipo) != "") {
        $dbwhere .= " and ";
      }
      $dbwhere .= $sWhereTipoassedb_depart;

      if(!isset($pesquisa_chave) && !isset($chave_assent)){
        
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tipoasse.php")==true && !isset($consulta)){
            include(modification("funcoes/db_func_tipoasse.php"));
            $campos = "tipoasse.*";
           }
        }

        $sWhereAdicional   = null;
        if ( isset($sAcao) && $sAcao == 'lancamento') {
          $iInstituicao  = db_getsession('DB_instit');

          $sWhereAdicional = " exists( select 1                                                \n";
          $sWhereAdicional.= "           from assenta                                          \n";
          $sWhereAdicional.= "          where assenta.h16_assent = tipoasse.h12_codigo)        \n";
          $sWhereAdicional.= " and case h12_natureza                                           \n";
          $sWhereAdicional.= "       when 2 /*Substituição*/                                   \n";
          $sWhereAdicional.= "         then true                                               \n";
          $sWhereAdicional.= "       when 3 /*RRA*/                                            \n";
          $sWhereAdicional.= "         then true                                               \n";
          $sWhereAdicional.= "       else exists( select 1                                     \n";
          $sWhereAdicional.= "                      from tipoassefinanceiro                    \n";
          $sWhereAdicional.= "                     where rh165_tipoasse = tipoasse.h12_codigo  \n";
          $sWhereAdicional.= "                     and rh165_instit   = $iInstituicao )        \n";
          $sWhereAdicional.= "     end ";

        }

        if ( isset($sAcao) && $sAcao == 'agendaAssentamento') {
          $iInstituicao  = db_getsession('DB_instit');

          $sWhereAdicional = " h12_codigo in (select h82_tipoassentamento                            \n";
          $sWhereAdicional.= "                  from agendaassentamento                              \n";
          $sWhereAdicional.= "                 where h82_instit = ". db_getsession('DB_instit') .") \n";

        }

        if(!empty($sWhereAdicional)) {
          $dbwhere = empty($dbwhere) ? $sWhereAdicional : " and {$sWhereAdicional}";
        }

        if(isset($chave_h12_codigo) && (trim($chave_h12_codigo)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_codigo","h12_codigo = ".$chave_h12_codigo.($dbwhere != "" ? " and " : "").$dbwhere);
        }else if(isset($chave_h12_assent) && (trim($chave_h12_assent)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_assent"," h12_assent = '".$chave_h12_assent."' ".($dbwhere != "" ? " and " : "").$dbwhere);
        }else if(isset($chave_h12_descr) && (trim($chave_h12_descr)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_descr"," h12_descr like '".$chave_h12_descr."%' ".($dbwhere != "" ? " and " : "").$dbwhere);
        }else{
           $sql = $cltipoasse->sql_query(null,$campos,"h12_codigo",$dbwhere);
        }
        $repassa = array();
        if(isset($chave_h12_assent)){
          $repassa = array("chave_h12_codigo"=>$chave_h12_codigo,"chave_h12_assent"=>$chave_h12_assent);
        }

        
        db_lovrot($sql,20,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if(isset($pesquisa_chave) && $pesquisa_chave!=null && $pesquisa_chave!=""){

          if (!empty($lConfiguracoesPontoEletronico)) {

            $sWhereConfiguracoesPontoEletronico = "h12_assent ilike '" .$pesquisa_chave. "'";

            if(is_int($pesquisa_chave)) {
              $sWhereConfiguracoesPontoEletronico = "h12_codigo = ".$pesquisa_chave;
            }
            
            $result = $cltipoasse->sql_record($cltipoasse->sql_query(null,"*","",$sWhereConfiguracoesPontoEletronico));

          } else {
            $result = $cltipoasse->sql_record($cltipoasse->sql_query(null,"*","","h12_codigo = ".$pesquisa_chave.($dbwhere != "" ? " and " : "").$dbwhere));
          }

          if($cltipoasse->numrows!=0){

            db_fieldsmemory($result,0);
            
            if (isset($lConsultaAssentamento) and $lConsultaAssentamento) {
              echo "<script>".$funcao_js."('$h12_descr', false);</script>";
            } else if (isset($lPesquisaNatureza)) {
              echo "<script>".$funcao_js."('$h12_assent',false, '$h12_descr', $h12_natureza);</script>";
            } else if (!empty($lConfiguracoesPontoEletronico)) {
            	echo "<script>".$funcao_js."($h12_codigo,'$h12_assent','$h12_descr');</script>";
            } else {
              echo "<script>".$funcao_js."('$h12_assent',false, '$h12_descr');</script>";
						}
            
          } else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else if(isset($chave_assent) && $chave_assent!=null && $chave_assent!="") {
          $result = $cltipoasse->sql_record($cltipoasse->sql_query("","*",""," trim(h12_assent) ilike '$chave_assent' "));

          if ($cltipoasse->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h12_assent', '$h12_descr', false, '$h12_codigo', '$h12_natureza', '$h12_vinculaperiodoaquisitivo');</script>";
          } else {
	          echo "<script>".$funcao_js."(true,'Chave(".$chave_assent.") não Encontrado',true);</script>";
          }
        } else {
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
<script>
js_tabulacaoforms("form2","chave_h12_assent",true,1,"chave_h12_assent",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
