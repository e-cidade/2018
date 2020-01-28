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
include("libs/db_libpessoal.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesrescisao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhpessoal = new cl_rhpessoal;
$clrhpesrescisao = new cl_rhpesrescisao;
$clgersubsql = new cl_gera_sql_folha;
$clrotulo = new rotulocampo;
$clrhpessoal->rotulo->label("rh01_regist");
$clrhpessoal->rotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
if(isset($valor_testa_rescisao)){
  $chave_rh01_regist = $valor_testa_rescisao;
  $retorno = db_alerta_dados_func($testarescisao,$valor_testa_rescisao,db_anofolha(), db_mesfolha());
  if($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
    function js_recebe_click(value){
      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','funcao_js');
      obj.setAttribute('id','funcao_js');
      obj.setAttribute('value','<?=$funcao_js?>');
      document.form2.appendChild(obj);

      obj = document.createElement('input');
      obj.setAttribute('type','hidden'); 
      obj.setAttribute('name','valor_testa_rescisao');
      obj.setAttribute('id','valor_testa_rescisao');
      obj.setAttribute('value',value);
      document.form2.appendChild(obj);

      document.form2.submit();
    }
  </script>
  <?
}
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh01_regist?>">
              <?=$Lrh01_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh01_numcgm?>">
              <?=$Lrh01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh01_numcgm",10,$Irh01_numcgm,true,"text",4,"","chave_rh01_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan='3'> 
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	        ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpessoal.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $anofolha = db_anofolha();
      $mesfolha = db_mesfolha();
      $anoanterior = $anofolha;
      $mesanterior = $mesfolha;
      $mesanterior -= 1;
      if($mesanterior == 0){
        $mesanterior = 12;
        $anoanterior -= 1;
      }
      $sqlres_anter = $clrhpesrescisao->sql_query_ngeraferias(null,"rh05_recis","","rh02_anousu = $anoanterior and rh02_mesusu = $mesanterior and rh02_regist = rh01_regist");
      $dbwhere = " and rh02_anousu = ".$anofolha." and rh02_mesusu = ".$mesfolha." and rh02_instit = ".db_getsession("DB_instit")." " ;
      if(!isset($pesquisa_chave)){
        $campos1 = "rhpessoal.rh01_regist,
                    rhpessoal.rh01_numcgm,
                    rhpessoal.rh01_admiss,
                    cgm.z01_nome,
                    rhlota.r70_codigo,
                    rhlota.r70_descr,
                    rhfuncao.rh37_funcao,
                    rhfuncao.rh37_descr,
                    r30_perai,
                    r30_per1f,
                    r30_per2f,
                    r30_per1i,
                    rh05_recis,
                    r30_proc1,
                    r30_proc2,
                    rh02_seqpes,
                    rh02_codreg,
                    rh14_matipe,
                    rh14_dtvinc,
                    rh02_anousu as anousu,
                    rh02_mesusu as mesusu
                   ";
        $sqlres_anter = $clrhpesrescisao->sql_query_ngeraferias(null,"rh05_recis","","rh02_anousu = $anoanterior and rh02_mesusu = $mesanterior and rh02_regist = x.rh01_regist");
        $campos2 = "distinct on (x.rh01_regist) x.rh01_regist,
                    ($sqlres_anter) as db_rescindido,
                    x.rh01_numcgm,
                    z01_nome,
                    r70_codigo,
                    r70_descr,
                    rh37_funcao,
                    rh37_descr,
                    r30_perai as db_r30_perai,
                    r30_per1f as db_r30_per1f,
                    r30_per2f as db_r30_per2f,
                    r30_per1i as db_r30_per1i,
                    rh05_recis as db_rh05_recis,
		    x.rh01_admiss as db_rh01_admiss,
		    r30_proc1 as db_r30_proc1,
		    r30_proc2 as db_r30_proc2,
		    x.rh02_seqpes as db_rh02_seqpes,
		    x.rh02_codreg as db_rh02_codreg,
                    rh14_matipe as db_rh14_matipe,
		    rh14_dtvinc as db_rh14_dtvinc
                   ";
        $clgersubsql->subsqlano = "anousu";
        $clgersubsql->subsqlmes = "mesusu";
        $clgersubsql->subsqlreg = "rh01_regist";
        $repassa = array("chave_z01_nome"=>@$chave_z01_nome,"chave_rh01_regist"=>@$chave_rh01_regist,"chave_rh01_numcgm"=>@$chave_rh01_numcgm,"rh01_instit"=>@$instit);
        if(isset($chave_rh01_regist) && (trim($chave_rh01_regist)!="") ){
	         $sql = $clrhpessoal->sql_query_ferias(null,$campos1,"rh01_regist,r30_perai desc"," rh01_regist = $chave_rh01_regist $dbwhere");
        }else if(isset($chave_rh01_numcgm) && (trim($chave_rh01_numcgm)!="") ){
	         $sql = $clrhpessoal->sql_query_ferias(null,$campos1,"rh01_numcgm,r30_perai desc"," rh01_numcgm = $chave_rh01_numcgm $dbwhere ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clrhpessoal->sql_query_ferias(null,$campos1,"z01_nome,r30_perai desc"," z01_nome like '$chave_z01_nome%' $dbwhere ");
        }
	if(isset($sql) && trim($sql) != ""){
          $clgersubsql->subsql = $sql;
          $sql = $clgersubsql->gerador_sql("", $anofolha, $mesfolha, "x.rh01_regist", null, $campos2);
          db_lovrot($sql,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh01_regist" : $funcao_js),"","NoMe",$repassa);
	}
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
//        die($clrhpessoal->sql_query_ferias(null,"*,($sqlres_anter) as rescindido","r30_perai desc"," rh01_regist = $pesquisa_chave $dbwhere"));
          $result = $clrhpessoal->sql_record($clrhpessoal->sql_query_ferias(null,"*,($sqlres_anter) as rescindido","r30_perai desc"," rh01_regist = $pesquisa_chave $dbwhere"));
          if($clrhpessoal->numrows!=0){
            db_fieldsmemory($result,0);
	    if(isset($testarescisao)){
              $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
              if($retorno != ""){
                db_msgbox($retorno);
              }
	    }
            echo "<script>".$funcao_js."('$z01_nome','$rh01_admiss','$rh02_seqpes','$r30_proc1','$r30_proc2','$r30_per1f','$r30_per2f','$rh02_codreg','$rh14_matipe','$rh14_dtvinc','$rh05_recis','$rescindido',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','','','','','','','','','','','',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('','','','','','','','','','','','',false);</script>";
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