<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("libs/db_app.utils.php");

db_postmemory($_POST);

if(!isset($setorCodigo)) {
  $setorCodigo = '';
}

if(!isset($quadra)) {
  $quadra = '';
}
if(!isset($lote)) {
  $lote = '';
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label("j01_matric");
$clrotulo = new rotulocampo;

$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("z01_nome");
$clrotulo->label("j04_matricregimo");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j40_refant");

$clrotulo->label("j04_setorregimovel");
$clrotulo->label("j04_quadraregimo");
$clrotulo->label("j04_loteregimo");

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
 db_app::load('estilos.css');
 db_app::load('scripts.js, prototype.js, strings.js, DBViewPesquisaSetorQuadraLote.js, dbcomboBox.widget.js');
?>
</head>
<body bgcolor=#CCCCCC>
<form name="form2" id="form2" method="post">
<table align="center">
<tr> 
  <td title="<?=$Tj01_matric?>">
  <?=$Lj01_matric?>
  </td>
  <td> 
  <?
    db_input("j01_matric",10,$Ij01_matric,true,"text",4,"","chave_j01_matric");
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tj14_codigo?>">
  <?
    db_ancora($Lj14_codigo,' js_mostraruas(true); ',2);
  ?>
  </td>
  <td> 
  <?
    db_input("j14_codigo",10,$Ij14_codigo,true,'text',4," onchange='js_mostraruas(false);'");
    db_input("j14_nome",30,$Ij14_nome,true,"text",3);
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tz01_nome?>">
    <?=$Lz01_nome?>
  </td>
  <td> 
  <?
    db_input("z01_nome",42,$Iz01_nome,true,'text',4);
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tj34_setor?>">
    <?=$Lj34_setor?>/<?=$Lj34_quadra?>/<?=$Lj34_lote?>
  </td>
  <td> 
  <?
    db_input("j34_setor" ,10,$Ij34_setor,true,'text',4);
    db_input("j34_quadra",10,$Ij34_quadra,true,'text',4);
    db_input("j34_lote"  ,10,$Ij34_lote,true,'text',4);
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tj40_refant?>">
    <?=$Lj40_refant?>
  </td>
  <td> 
  <?
    db_input("j40_refant",42,$Ij40_refant,true,'text',4);
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tj04_matricregimo?>">
    <b>Matrícula RI :</b>
  </td>
  <td> 
  <?
    db_input("j04_matricregimo",10,$Ij04_matricregimo,true,"text",4,"","chave_j04_matricregimo");
  ?>
  </td>
</tr>

<tr> 
  <td title="<?=$Tj04_setorregimovel?>">
    <b>Setor/Quadra/Lote RI :</b>
  </td>
  <td> 
  <?
    db_input("j04_setorregimovel", 10, $Ij04_setorregimovel, true, 'text', 1);
    db_input("j04_quadraregimo"  , 10, $Ij04_quadraregimo  , true, 'text', 1);
    db_input("j04_loteregimo"    , 10, $Ij04_loteregimo    , true, 'text', 1);
  ?>
  </td>
</tr>    

<tr> 
  <td colspan="2" align="center">
    <div id="pesquisa"></div>
  </td>
</tr>  

<tr> 
  <td colspan="2" align="center"> 
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
  </td>
</tr>        
  
</table>
</form>

<table id="lovrot" align="center">
<tr>
  <td>
  <?
    $funcao_js_anterior = $funcao_js;

    if (isset($valida) && $valida=="true" ) {
      $funcao_js = "js_verificaMatric|j01_matric";
    }
      
    if(!isset($pesquisa_chave)){
        
      if(isset($campos)==false){
        $campos = "iptubase.*";
      }
        
      $sCampos  = " distinct                                                  \n";
      $sCampos .= " j01_matric,                                               \n";
      $sCampos .= " (select rvnome as z01_nome                                \n";
      $sCampos .= "    from fc_busca_envolvidos(false,                        \n";
      $sCampos .= "                             (select fc_regrasconfig       \n";
      $sCampos .= "                                from fc_regrasconfig(1)),  \n";
      $sCampos .= "                             'M',                          \n";
      $sCampos .= "                             j01_matric)                   \n";
      $sCampos .= "                             limit 1),                     \n";
      $sCampos .= " z01_numcgm as db_z01_numcgm,                              \n";
      $sCampos .= " case                                                      \n";
      $sCampos .= "   when j39_numero is null then 'Terr' else 'Pred'         \n";
      $sCampos .= " end as Tipo,                                              \n";
      $sCampos .= " case                                                      \n";
      $sCampos .= "    when ruase.j14_codigo is null then ruas.j14_nome       \n";
      $sCampos .= "    else ruase.j14_nome                                    \n";
      $sCampos .= " end as j14_nome,                                          \n";
      $sCampos .= " case                                                      \n";
      $sCampos .= "   when j39_numero is null then                            \n";
      $sCampos .= "        (case                                              \n";
      $sCampos .= "             when j15_numero is null then 0                \n";
      $sCampos .= "             else j15_numero                               \n";
      $sCampos .= "        end)                                               \n";
      $sCampos .= "   else j39_numero                                         \n";
      $sCampos .= " end as j39_numero,                                        \n";
      $sCampos .= "  j40_refant,                                              \n";
      $sCampos .= " j39_compl,                                                \n";
      $sCampos .= " j34_setor,                                                \n";
      $sCampos .= " j34_quadra,                                               \n";
      $sCampos .= " j34_lote,                                                 \n";
      $sCampos .= " j05_codigoproprio,                                        \n";
      $sCampos .= " j06_quadraloc,                                            \n";
      $sCampos .= " j06_lote                                                  \n";
      
      $sql2 = "";
       
      if(isset($chave_j01_matric) && (trim($chave_j01_matric)!="") ){
        $sql = $cliptubase->sql_query_regmovel($chave_j01_matric,$sCampos, null, "j01_matric = $chave_j01_matric and j01_baixa is null");
      } else if(isset($chave_j04_matricregimo) && (trim($chave_j04_matricregimo))!="" ){
        $sql = $cliptubase->sql_query_regmovel(null,$sCampos,null,"j04_matricregimo = {$chave_j04_matricregimo} and j02_matric is null and j01_baixa is null");
      } else if(isset($j40_refant) && (trim($j40_refant))!="" ){
        $sql = $cliptubase->sql_query_regmovel(null,$sCampos,"j40_refant","j40_refant like '$j40_refant%' and j02_matric is null and j01_baixa is null");  
      } else if(isset($j14_codigo) && (trim($j14_codigo)!="") ){
        $sql = $cliptubase->sql_query_regmovel(null,$sCampos,"j39_numero","j39_codigo = $j14_codigo and j02_matric is null and j01_baixa is null");         
      } else if(isset($z01_nome) && (trim($z01_nome)!="") ){
        $sql = $cliptubase->sql_query_regmovel(null,$sCampos,"z01_nome","z01_nome like '$z01_nome%' and j02_matric is null and j01_baixa is null");
        
      } else if((isset($j34_setor)   && (trim($j34_setor)  !="")) or 
               ((isset($j34_quadra)  && (trim($j34_quadra) !="")) or 
               ((isset($j34_lote)    && (trim($j34_lote)   !=""))))){
          
        $sWhere = " j02_matric is null ";
            
        if (isset($j34_setor) && trim($j34_setor)!="") {
          $sWhere .= " and j34_setor = '" . str_pad($j34_setor,4,"0",STR_PAD_LEFT) . "'";
        }
        if (isset($j34_quadra) && trim($j34_quadra)!="") {
          $sWhere .= " and j34_quadra = '" . str_pad($j34_quadra,4,"0",STR_PAD_LEFT) . "'";
        }
        if (isset($j34_lote) && trim($j34_lote)!="") {
          $sWhere .= " and j34_lote = '" . str_pad($j34_lote,4,"0",STR_PAD_LEFT) . "'";
         }

         $sWhere .= " and j02_matric is null ";
         $sWhere .= " and j01_baixa is null ";
         $sql = $cliptubase->sql_query_regmovel(null,$sCampos,"j34_setor, j34_quadra, j34_lote",$sWhere);
         
      } else if((isset($j04_setorregimovel) && (trim($j04_setorregimovel)!="" )) or 
               ((isset($j04_quadraregimo)   && (trim($j04_quadraregimo)  !="" )) or 
               ((isset($j04_loteregimo)     && (trim($j04_loteregimo)     !="" ))))){
          
        $sWhere = " j02_matric is null ";
            
        if (isset($j04_setorregimovel) && trim($j04_setorregimovel)!="") {
          $sWhere .= " and j04_setorregimovel = '".str_pad($j04_setorregimovel,4,"0",STR_PAD_LEFT)."'";
        }
        if (isset($j04_quadraregimo) && trim($j04_quadraregimo)!="") {
          $sWhere .= " and j04_quadraregimo = '".str_pad($j04_quadraregimo,4,"0",STR_PAD_LEFT)."'";
        }
        if (isset($j04_loteregimo) && trim($j04_loteregimo)!="") {
          $sWhere .= " and j04_loteregimo = '".str_pad($j04_loteregimo,4,"0",STR_PAD_LEFT)."'";
         }

         $sWhere .= " and j01_baixa is null ";
         $sql = $cliptubase->sql_query_regmovel(null,$sCampos,"j04_setorregimovel, j04_quadraregimo, j04_loteregimo",$sWhere);
         
      }else if((isset($setor)  and ($setor != '' )) || 
               (isset($quadra) and ($quadra != '')) || 
               (isset($lote)   and ($lote != ''  ))) {
        
        $sWhere = " j02_matric is null ";
        
        if(isset($setor) and $setor != '') {
          $sWhere .= " and j05_codigoproprio = '{$setorCodigo}' ";
        }
        if(isset($quadra) and $quadra != '') {
          $sWhere .= " and j06_quadraloc = '{$quadra}' ";
        }
        if(isset($lote) and $lote != '') {
          $sWhere .= " and j06_lote = '{$lote}' ";
        }

        $sWhere .= " and j01_baixa is null";
          
        $sOrderBy = ""; 
        $sql = $cliptubase->sql_query_regmovel(null,$sCampos, $sOrderBy, $sWhere);
        
      }else {
        
         $sql = "";
     
      }
      
      if($sql!="" || isset($dblov)){

        $repassa = array('dblov'=>'0');
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
         
      }
       
    } else {
       
      $result = $cliptubase->sql_record($cliptubase->sql_query($pesquisa_chave, '*', null, "iptubase.j01_matric = $pesquisa_chave and j01_baixa is null"));
      
      if($cliptubase->numrows!=0){
        db_fieldsmemory($result,0);
        echo "<script>".$funcao_js."(\"$z01_nome\",false,$z01_numcgm);</script>";
      }else{
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
      }
        
    }
    
  ?>
  </td>
</tr>
</table>
</body>
</html>
<script>
function js_mostraruas(mostra){
  if(mostra==true){
    db_iframe.jan.location.href = 'func_ruas.php?funcao_js=parent.js_preencheruas|0|1';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'func_ruas.php?pesquisa_chave='+document.form2.j14_codigo.value+'&funcao_js=parent.js_preencheruas';  
  }
}
 function js_preencheruas(chave,chave1){
   document.form2.j14_codigo.value = chave;
   document.form2.j14_nome.value = chave1;
   db_iframe.hide();
 }
 
 function js_verificaMatric(iMatric){
   location.href = "itb1_dadosmatric001.php?matric="+iMatric+"&funcao_anterior=<?=$funcao_js_anterior?>";
 }
 
</script>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
document.form2.chave_j01_matric.focus();
document.form2.chave_j01_matric.select();
  </script>
  <?
}

$db_iframe= new janela('db_iframe','');
$db_iframe ->posX=1;
$db_iframe ->posY=20;
$db_iframe ->largura=770;
$db_iframe ->altura=430;
$db_iframe ->titulo="Pesquisa";
$db_iframe ->iniciarVisivel = false;
$db_iframe ->mostrar();

?>
<script>
var oPesquisa = new DBViewPesquisaSetorQuadraLote('pesquisa', 'oPesquisa');
    oPesquisa.show();
    oPesquisa.appendForm();
<? 
  echo "oPesquisa.setValues('{$setorCodigo}','{$quadra}','{$lote}');"; 
?>
</script>