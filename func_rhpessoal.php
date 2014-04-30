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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_libpessoal.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_rhpessoal_classe.php");


db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

$chave_z01_nome = isset($chave_z01_nome) ? stripslashes($chave_z01_nome) : '';
$db_opcao       = 1;

if (!isset($sQuery)) {
  $sQuery = null;
}

if ($sQuery || isset($sAtivos)) {
  $db_opcao = 3;
  $selecao  = 'A';
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhpessoal = new cl_rhpessoal;
$clrotulo    = new rotulocampo;
$clrhpessoal->rotulo->label("rh01_regist");
$clrhpessoal->rotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
if(isset($valor_testa_rescisao)){

  $chave_rh01_regist = $valor_testa_rescisao;
  $retorno           = db_alerta_dados_func($testarescisao,$valor_testa_rescisao,db_anofolha(), db_mesfolha());
  if($retorno != ""){
    db_msgbox($retorno);
  }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>

 #tabela_principal {
   border-top: 2px solid #ccc;
 }

 #tabela_principal tr td:first-child {
   text-align: left !important;
 }  

 #chave_rh01_regist,
 #chave_rh01_numcgm,
 #chave_z01_cgccpf,
 #selecao {
   width : 150px !important;
 }

 #chave_z01_nome { 
   width : 400px !important;
 }
 
</style>
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form2.chave_rh01_regist.focus();" >
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC" id="tabela_principal">
  <tr> 
    <td height="63" align="center" valign="top">
        <table  border="0" align="center" cellspacing="0">
       <form name="form2" method="post" action="" >
          <tr> 
            <td align="right" nowrap title="<?=$Trh01_regist?>">
              <?=$Lrh01_regist?>
            </td>
            <td align="left" nowrap> 
              <?
           db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist");
           ?>
            </td>
          </tr>
          <tr> 
            <td align="right" nowrap title="<?=$Trh01_numcgm?>">
              <?=$Lrh01_numcgm?>
            </td>
            <td align="left" nowrap> 
              <?
           db_input("rh01_numcgm",10,$Irh01_numcgm,true,"text",4,"","chave_rh01_numcgm");
           ?>
            </td>
          </tr>
          <tr> 
            <td  align="right" nowrap title="<?=$Tz01_cgccpf?>">
              <?=$Lz01_cgccpf?>
            </td>
            <td  align="left" nowrap> 
<?
           db_input("z01_cgccpf",14,1,true,"text",4,"","chave_z01_cgccpf");//SomenteNumeros
           ?>
            </td>
          </tr>
          <tr> 
            <td  align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td  align="left" nowrap colspan='3'> 
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
          ?>
            </td>
          </tr>
          <tr>
            <td align="right">
              <b>Seleção Por:</b>
            </td>
            <td>
              <?
               $aSelecao = array("T" => "Todos",
                                 "A" => "Ativos",
                                 "R" => "Rescindidos"
                                 );
              db_select("selecao", $aSelecao, true, $db_opcao);
              ?>
            <td>
          </tr>
          <tr> 
            <td colspan="3" id="botoes_pesquisa"> 
   
              <center>
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"  onclick="return js_valida(arguments[0]);"> 
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpessoal.hide();">
              </center>
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $chave_z01_nome = addslashes($chave_z01_nome);

      $sFiltraInstit = " rh01_instit = ".db_getsession('DB_instit');
      if (isset($_GET['lTodos'])) {
        
        $sFiltraInstit = " 1 = 1 ";
      }
      $dbwhere  = " and ( 
                          ( 
                                rh02_instit is null
                            and $sFiltraInstit 
                          )
                          or $sFiltraInstit 
                        ) " ;
      if (isset($selecao)) {
        
        switch ($selecao) {
          case "A" :
            
            $dbwhere .= " and rh05_seqpes is null ";
            break;
          case "R":
            
            $dbwhere .= " and rh05_seqpes is not null ";
            break;
        }            
      }


      if( !isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhpessoal.php")==true){
             include("funcoes/db_func_rhpessoal.php");
           }else{
           $campos = "rhpessoal.*";
           }
        }


        $repassa = array("chave_z01_nome"=>@$chave_z01_nome,"chave_rh01_regist"=>@$chave_rh01_regist,"chave_rh01_numcgm"=>@$chave_rh01_numcgm,"rh01_instit"=>@$instit);
        
        if(isset($chave_rh01_regist) && (trim($chave_rh01_regist) != "" ) ){
          
           $sql = $clrhpessoal->sql_query_func_rhpessoal(null,$campos,"rh01_regist"," rh01_regist = $chave_rh01_regist $dbwhere ", $sQuery);
           
        }else if(isset($chave_rh01_numcgm) && (trim($chave_rh01_numcgm)!="") ){
           $sql = $clrhpessoal->sql_query_func_rhpessoal("",$campos,"rh01_numcgm"," rh01_numcgm = $chave_rh01_numcgm $dbwhere ", $sQuery);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
           $sql = $clrhpessoal->sql_query_func_rhpessoal("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' $dbwhere ", $sQuery);
        }else if(isset($chave_z01_cgccpf) && (trim($chave_z01_cgccpf)!="") ){
           $sql = $clrhpessoal->sql_query_func_rhpessoal("",$campos,"z01_nome"," z01_cgccpf like '$chave_z01_cgccpf%' $dbwhere ", $sQuery);
        }
         
        if(isset($sql) && trim($sql) != ""){
                db_lovrot($sql,15,"()","",(isset($testarescisao) && !isset($valor_testa_rescisao) ? "js_recebe_click|rh01_regist" : $funcao_js),"","NoMe",$repassa);
        }
        
      } else {  // com chave pesquisa
        
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sSql = $clrhpessoal->sql_query_func_rhpessoal(null,"*","rh01_regist"," rh01_regist = $pesquisa_chave $dbwhere", $sQuery);
          
          $result = $clrhpessoal->sql_record($sSql);
          
          if($clrhpessoal->numrows!=0){
            db_fieldsmemory($result,0);
          if(isset($testarescisao)){
              $retorno = db_alerta_dados_func($testarescisao,$pesquisa_chave,db_anofolha(), db_mesfolha());
              if($retorno != ""){
                db_msgbox($retorno);
              }
      }
            echo "<script>".$funcao_js."('$z01_nome',false,'".@db_formatar($rh01_admiss,'d')."', false);</script>";
          }else{
           echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
         echo "<script>".$funcao_js."('',false);</script>";
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

  function js_valida(event) {
    document.getElementById('chave_z01_nome').onkeyup(event);
    document.getElementById('chave_rh01_regist').onkeyup(event);
    document.getElementById('chave_rh01_numcgm').onkeyup(event);
    document.getElementById('chave_z01_cgccpf').onkeyup(event);
    return true;
  }


 

  </script>
  <?
}
?>