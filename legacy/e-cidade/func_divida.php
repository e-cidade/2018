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
include(modification("libs/db_utils.php"));
include(modification("classes/db_divida_classe.php"));
include(modification("classes/db_iptubase_classe.php"));

db_postmemory($HTTP_POST_VARS);

$get = db_utils::postMemory($_GET);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldivida = new cl_divida;
$cIptubase = new cl_iptubase;
$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("v03_descr");
$clrotulo->label("v01_coddiv");
$clrotulo->label("v03_codigo");
$clrotulo->label("j01_matric");
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form2.v01_coddiv.focus();">
<table height="100%" border="0" width="600" align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
        <td height="63" align="center" valign="top">
            <table width="100%" border="0" align="center" cellspacing="0">
                <form name="form2" method="post" action="" >
                    <tr>
                        <td width="50%" align="right" nowrap title="<?=$Tv01_coddiv?>">
                            <?=$Lv01_coddiv?>
                        </td>
                        <td width="50%" align="left" nowrap>
                            <?
                            db_input("v01_coddiv",6,$Iv01_coddiv,true,"text",1);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align="right">
                            <?
                            db_ancora($Lj01_matric,' js_matri(true); ',1);
                            ?>
                        </td>
                        <td>
                            <?
                            db_input('j01_matric',6,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="" align="right" nowrap title="<?=$Tz01_numcgm?>">
                            <?=$Lz01_numcgm?>
                        </td>
                        <td width="" align="left" nowrap>
                            <?
                            db_input("z01_numcgm",6,$Iz01_numcgm,true,"text",4,"","z01_numcgm");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="" align="right" nowrap title="<?=$Tz01_nome?>">
                            <?=$Lz01_nome?>
                        </td>
                        <td width="" align="left" nowrap>
                            <?
                            db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","z01_nome");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="" align="right" nowrap title="<?=$Tv03_codigo?>">
                            <?
                            db_ancora(@$Lv03_codigo,"js_pesquisav03_codigo(true);",1);
                            ?>
                        </td>
                        <td width="" align="left" nowrap>
                            <?
                            db_input("v03_codigo",6,$Iv03_codigo,true,"text",4,"onchange='js_pesquisav03_codigo(false);'");
                            ?>
                            <?
                            db_input('v03_descr',40,$Iv03_descr,true,'text',3,'')
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
                            <input name="limpar" type="reset" id="limpar" value="Limpar" >
                            <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_divida.hide();">
                        </td>
                    </tr>
                </form>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" valign="top">
            <?php

            $whereExercicio = '';
            if (!empty($get->anoinclusao)) {
                $whereExercicio = " and extract(year from divida.v01_dtinclusao) = {$get->anoinclusao}";
            }

            if(isset($pesquisar)){
                if(!isset($pesquisa_chave)){
                    if(isset($campos)==false){
                        if(file_exists("funcoes/db_func_divida.php")==true){
                            include(modification("funcoes/db_func_divida.php"));
                        }else{
                            $campos = "divida.oid,divida.*";
                        }
                    }
                    $dbwhere='';
                    if(isset($v01_coddiv) && $v01_coddiv!=""){
                        $dbwhere="and divida.v01_coddiv= '$v01_coddiv'";
                    }else if(isset($j01_matric) && $j01_matric != "") {
                        $rIptubase = $cIptubase->sql_record($cIptubase->sql_query($j01_matric, 'j01_numcgm'));
                        $iCgm      = db_utils::fieldsMemory($rIptubase, 0)->j01_numcgm;
                        $dbwhere   ="and z01_numcgm='$iCgm'";
                    }else if(isset($v03_codigo) && $v03_codigo!=""){
                        $dbwhere="and v01_proced='$v03_codigo'";
                    }else if(isset($z01_numcgm) && $z01_numcgm!=""){
                        $dbwhere="and z01_numcgm=$z01_numcgm";
                    }else if(isset($z01_nome) && $z01_nome!=''){
                        $dbwhere="and z01_nome like '$z01_nome%'";
                    }

                    $dbwhere .= $whereExercicio;
                    $sql = $cldivida->sql_query("","$campos","","v01_instit = ".db_getsession('DB_instit')." $dbwhere");
                    $z01_nome = empty($z01_nome) ? '' : $z01_nome;
                    $v01_coddiv = empty($v01_coddiv) ? '' : $v01_coddiv;
                    $repassa = array();
                    $repassa = array("z01_nome"=>$z01_nome,"v01_coddiv"=>$v01_coddiv);

                    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
                }else{

                    if($pesquisa_chave!=null && $pesquisa_chave!=""){

                        $whereDefault = "v01_instit = ".db_getsession('DB_instit')." and v01_coddiv = $pesquisa_chave ";
                        if (!empty($whereExercicio)) {
                            $whereDefault .= $whereExercicio;
                        }
                        $result = $cldivida->sql_record($cldivida->sql_query($pesquisa_chave,"*",null,$whereDefault ));
                        if($cldivida->numrows!=0){
                            db_fieldsmemory($result,0);

                            if (!empty($retornarNomeCGM)) {
                                echo "<script>".$funcao_js."('$z01_nome',false);</script>";
                            } else {
                                echo "<script>" . $funcao_js . "('$oid',false);</script>";
                            }
                        }else{
                            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
                        }
                    }else{
                        echo "<script>".$funcao_js."('',false);</script>";
                    }
                }
            }else if(isset($v01_coddiv) || isset($v01_proced) || isset($z01_nome)){
                if(isset($campos)==false){
                    if(file_exists("funcoes/db_func_divida.php")==true){
                        include(modification("funcoes/db_func_divida.php"));
                    }else{
                        $campos = "divida.oid,divida.*";
                    }
                }
                $dbwhere='';
                if(isset($v01_coddiv) && $v01_coddiv!=""){
                    $dbwhere=" and divida.v01_coddiv= '$v01_coddiv'";
                }else if(isset($v03_codigo) && $v03_codigo!=""){
                    $dbwhere=" and v01_proced='$v03_codigo'";
                }else if(isset($z01_nome) && $z01_nome!=''){
                    $dbwhere=" and z01_nome like '$z01_nome%'";
                }
                $sql = $cldivida->sql_query("","$campos",""," v01_instit = ".db_getsession('DB_instit')." $dbwhere ");
                //echo $sql;

                $repassa = array();
                //  if(isset($z01_nome)){
                $repassa = array("z01_nome"=>$z01_nome,"v01_coddiv"=>$v01_coddiv);
                // }

                db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);



            }
            ?>
        </td>
    </tr>
</table>
</body>
</html>
<script>
  function js_matri(mostra){
    var iMatric = document.form2.j01_matric.value;
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?pesquisa_chave='+iMatric+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
    }
  }

  function js_mostramatri(chave1,chave2){
    document.form2.j01_matric.value = chave1;
    document.form2.z01_nome.value   = chave2;
    db_iframe3.hide();
  }

  function js_mostramatri1(chave,erro){
    if(erro==true){
      document.form2.j01_matric.focus();
      document.form2.j01_matric.value = '';
    }
  }

  function js_pesquisav03_codigo(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframew1','func_proced.php?funcao_js=parent.js_mostraproced1|v03_codigo|v03_descr','Pesquisa',true,0,0,'750','397');
    }else{
      js_OpenJanelaIframe('','db_iframew1','func_proced.php?pesquisa_chave='+document.form2.v03_codigo.value+'&funcao_js=parent.js_mostraproced','Pesquisa',false);
    }
  }
  function js_mostraproced(chave,erro){
    document.form2.v03_descr.value = chave;
    if(erro==true){
      document.form2.v03_codigo.focus();
      document.form2.v03_codigo.value = '';
    }
  }
  function js_mostraproced1(chave1,chave2){
    document.form2.v03_codigo.value = chave1;
    document.form2.v03_descr.value = chave2;
    db_iframew1.hide();
  }
</script>
<script type="text/javascript">
  (function() {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
