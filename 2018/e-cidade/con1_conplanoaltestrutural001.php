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
require_once("libs/db_utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_conplano_classe.php");
require_once("classes/db_orcfontes_classe.php");
require_once("classes/db_orcelemento_classe.php");

db_postmemory($HTTP_POST_VARS);

$anousu = db_getsession("DB_anousu");

$cliframe_seleciona_conplano = new cl_iframe_seleciona;

$clconplano                  = new cl_conplano;
$clorcfontes                 = new cl_orcfontes;
$clorcelemento               = new cl_orcelemento;

$clconplano->rotulo->label("c60_codcon");
$clconplano->rotulo->label("c60_descr");
$clconplano->rotulo->label("c60_estrut");

$clrotulo = new rotulocampo;
$clrotulo->label("c61_reduz");

$sqlerro  = false;
$erro_msg = "";
if (isset($alterar) && trim(@$alterar) != ""){
     db_inicio_transacao();

     $resultado = $clconplano->sql_record($clconplano->sql_query_geral(null,null,"c60_codcon as codcon,c60_estrut,c61_reduz","c60_estrut",
                                                                       "c60_anousu = $anousu and c60_codcon in ($c60_codcon)"));
     $numrows   = $clconplano->numrows;

     $vetor_contas_sinteticas = array(array("novo","novo_mae","codcon_mae"));
     $cont_contas_sinteticas  = 0;

     if ($numrows > 0) {

       for($i = 0; $i < $numrows; $i++) {

         db_fieldsmemory($resultado,$i);

         $tam_inicial = strlen($chave_c60_estrut_alt);
         $tam_final   = strlen($c60_estrut) - $tam_inicial;
         $novo_estrutural = $chave_c60_estrut.substr($c60_estrut,$tam_inicial,$tam_final);

         $sWhere       = "c60_codcon = {$codcon}";
         $sSqlConPlan  = $clconplano->sql_query_file(null,null,"max(conplano.c60_anousu) as anomax",null,$sWhere);
         $rsSqlConPlan = $clconplano->sql_record($sSqlConPlan);

         if ($clconplano->numrows > 0) {

           $oConPlan = db_utils::fieldsMemory($rsSqlConPlan, 0);

           for ($iAnoAtual = $anousu; $iAnoAtual <= $oConPlan->anomax; $iAnoAtual++) {

		         $sql = $clconplano->sql_query_file(null, null, "*", null, "c60_anousu = {$iAnoAtual} and
		                                                                    c60_estrut like '$novo_estrutural'");
		         $clconplano->sql_record($sql);

		         if ($clconplano->numrows == 0){  // Verifica se novo estrutural jah nao existe

		           if (isset($c61_reduz) && trim(@$c61_reduz)!=""){  // Se a conta selecionada eh sintetica(tem reduzido)
		                                                          // senao, analitica
		             $cortado = db_le_mae_conplano($c60_estrut);
		             $res_sintetica = $clconplano->sql_record($clconplano->sql_query_geral(null,null,"c60_estrut as estrutural_mae,c60_codcon as codcon_mae",null,"c60_anousu = {$iAnoAtual} and c60_estrut like '$cortado'"));
		             if ($clconplano->numrows > 0) {

		               db_fieldsmemory($res_sintetica,0);

		               $tam_inicial = strlen($chave_c60_estrut_alt);
		               $tam_final   = strlen($estrutural_mae) - $tam_inicial;
		               $novo_estrutural_mae = $chave_c60_estrut.substr($estrutural_mae,$tam_inicial,$tam_final);

		               $sql = $clconplano->sql_query_file(null, null, "*", null, "c60_anousu = {$iAnoAtual} and
		                                                                          c60_estrut like '$novo_estrutural_mae'");
		               $clconplano->sql_record($sql);

		               if ($clconplano->numrows == 0) {

		                 $vetor_contas_sinteticas[$cont_contas_sinteticas]["novo"]       = $novo_estrutural;
		                 $vetor_contas_sinteticas[$cont_contas_sinteticas]["novo_mae"]   = $novo_estrutural_mae;
		                 $vetor_contas_sinteticas[$cont_contas_sinteticas]["codcon_mae"] = $codcon_mae;
		                 $vetor_contas_sinteticas[$cont_contas_sinteticas]["ano"] = $iAnoAtual;
		                 $cont_contas_sinteticas++;
		               }
		             }
		           }

		           $clconplano->c60_codcon = $codcon;
		           $clconplano->c60_anousu = $iAnoAtual;
		           $clconplano->c60_estrut = $novo_estrutural;

		           $clconplano->alterar($codcon,$iAnoAtual);
		           if ($clconplano->erro_status == 0) {

		             $erro_msg = $clconplano->erro_msg;
		             $sqlerro  = true;
		             break;
		           }
		         }
           }
         }

       }
     }

     if ($sqlerro == false) {

       if ($cont_contas_sinteticas > 0) {

         $estrut_mostrar = "";
         for($i = 0; $i < $cont_contas_sinteticas; $i++) {

           $estrut_mostrar .= "Cod. Conta origem ".$vetor_contas_sinteticas[$i]["codcon_mae"]."\\n";
           $estrut_mostrar .= "Ano ".$vetor_contas_sinteticas[$i]["ano"]."\\n";
           $estrut_mostrar .= "Estrutural Sintetico ".$vetor_contas_sinteticas[$i]["novo_mae"]."\\n";
           $estrut_mostrar .= "Estrutural Novo ".$vetor_contas_sinteticas[$i]["novo"]."\\n";
         }

         if (trim(@$estrut_mostrar) != "") {
            db_msgbox($estrut_mostrar);
         }
       }
     }

     if ($sqlerro == false) {
          $msg = "";
          if (trim(@$estrut_mostrar) == ""){
               $msg .= "Estruturais sem pendencias de estrutural sintetico.\\n";
          }

          $msg .= "Estruturais alterados com sucesso.";
          unset($chave_c60_estrut);
     }

     //$sqlerro = true;
     db_fim_transacao($sqlerro);
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
  function js_valida_dados(){
     if (document.form1.chave_c60_estrut.value == ""){
          alert("Informe um estrutural valido");
          document.form1.chave_c60_estrut.select();
          document.form1.chave_c60_estrut.focus();
          return false;
     }

     return true;
  }

  function js_alterar_estrut(codestrut){
     var estrutural   = new String(codestrut);
     var erro         = true;
     var contador     = 0;
     var virgula      = "";
     var lista_codcon = "";

     for(i = 0; i < conplano.document.form1.elements.length; i++){
          if (conplano.document.form1.elements[i].type == "checkbox"){
               if (conplano.document.form1.elements[i].checked == true ){
                      lista_codcon += virgula + conplano.document.form1.elements[i].value;
                      virgula       = ",";
                      contador++;
               }
          }
     }

     if (contador == 0){
          alert("Selecione uma ou mais contas");
          erro = false;
     }

     if (erro == true){
          if (estrutural.length != document.form1.chave_c60_estrut_alt.value.length){
               alert("Informe estrutural para alterar com o mesmo tamanho do pesquisado");
               document.form1.chave_c60_estrut_alt.select();
               document.form1.chave_c60_estrut_alt.focus();
               erro = false;
          }
     }

     if (erro == true){
          document.form1.c60_codcon.value       = lista_codcon;
          document.form1.chave_c60_estrut.value = document.form1.chave_c60_estrut_alt.value;
     }

     return erro;
  }

  function js_novo(){
     document.location.href="con1_conplanoaltestrutural001.php";
  }
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
 <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
     <td width="360" height="18">&nbsp;</td>
     <td width="263">&nbsp;</td>
     <td width="25">&nbsp;</td>
     <td width="140">&nbsp;</td>
   </tr>
 </table>
<center>
<table border="0" cellspacing="5" cellpadding="0">
<form name="form1" action="" method="post">
  <tr>
    <td height="30" align="right" title="<?=@$Tc60_estrut?>"><?=@$Lc60_estrut?></td>
    <td>
    <?
      db_input("c60_estrut",15,@$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
      db_input("c60_codcon",15,0,true,"hidden",3);

      if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != ""){
           $resultado = $clconplano->sql_record($clconplano->sql_query_file(null,null,"*","c60_estrut",
                                                                            "c60_anousu = $anousu and
                                                                             c60_estrut like '$chave_c60_estrut%'"));
           $numrows_contas = $clconplano->numrows;
      } else {
           $numrows_contas = 0;
      }

      if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != "" && !isset($chave_c60_estrut_alt)){
           if ($numrows_contas > 0){
                $db_botao = true;
           } else {
                $db_botao = false;
           }
      } else {
           $db_botao = false;
      }
    ?>
      <input name="pesquisar" type="submit" value="Pesquisar" onClick="return js_valida_dados();" <?=($db_botao==true?"disabled":"")?>>
    <?
      if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != "" && !isset($chave_c60_estrut_alt)){
    ?>
      <input name="novo" type="button" value="Novo" onClick="js_novo();">
    <?
       }
    ?>
    </td>
  </tr>
  <?
     if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != "" && !isset($chave_c60_estrut_alt)){
          if ($numrows_contas > 0){
  ?>
  <tr>
    <td align="right" title="Estrutural a ser modificado"><b>Alterar para:</b></td>
    <td>
    <?
      db_input("c60_estrut",15,@$Ic60_estrut,true,"text",4,"","chave_c60_estrut_alt");
    ?>
      <input name="alterar" type="submit" value="Alterar" onClick="return js_alterar_estrut('<?=$chave_c60_estrut?>');">
    </td>
  </tr>
  <?
          }
     }

     if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != ""){
  ?>
  <tr>
    <td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
    <?

       $campos = "c60_codcon,c61_reduz,c60_anousu,c60_estrut,c60_descr";
       $sql    = $clconplano->sql_query_geral(null,null,$campos,"c60_estrut","c60_anousu = $anousu and
                                                                              c60_estrut like '$chave_c60_estrut%' and
                                                                              (c61_instit is null or c61_instit = ".db_getsession("DB_instit").")");

       $cliframe_seleciona_conplano->campos        = $campos;
       $cliframe_seleciona_conplano->legenda       = "";
       $cliframe_seleciona_conplano->sql           = $sql;
       $cliframe_seleciona_conplano->iframe_height = "500";
       $cliframe_seleciona_conplano->iframe_width  = "600";
       $cliframe_seleciona_conplano->iframe_nome   = "conplano";
       $cliframe_seleciona_conplano->chaves        = "c60_codcon";
       $cliframe_seleciona_conplano->marcador      = true;
		   $cliframe_seleciona_conplano->dbscript      = "onClick='parent.js_selecionados();'";
       $cliframe_seleciona_conplano->js_marcador   = "parent.js_selecionados();";
       $cliframe_seleciona_conplano->iframe_seleciona(1);
    ?>
        </td>
      </tr>
    </table></td>
  </tr>
  <?
    }
  ?>
</form>
</table>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

  if ($sqlerro == false){
       if (isset($msg) && trim(@$msg) != ""){
            db_msgbox($msg);
       }
  }

  if ($sqlerro == true){
       db_msgbox($erro_msg);
  }
?>
<script>
  function js_db_le_mae_conplano(codigo){
     var retorno = "";

//   Nivel 10
     if (retorno == "" && codigo.substr(13,2) != "00"){
          retorno = codigo.substr(0,13);
     }

//   Nivel 9
     if (retorno == "" && codigo.substr(11,2) != "00"){
          retorno = codigo.substr(0,11);
     }

//   Nivel 8
     if (retorno == "" && codigo.substr(9,6) != "000000"){
          retorno = codigo.substr(0,9);
     }

//   Nivel 7
     if (retorno == "" && codigo.substr(7,8) != "00000000"){
          retorno = codigo.substr(0,7);
     }

//   Nivel 6
     if (retorno == "" && codigo.substr(5,10) != "0000000000"){
          retorno = codigo.substr(0,5);
     }

//   Nivel 5
     if (retorno == "" && codigo.substr(4,11) != "00000000000"){
          retorno = codigo.substr(0,4);
     }

//   Nivel 4
     if (retorno == "" && codigo.substr(3,12) != "000000000000"){
          retorno = codigo.substr(0,3);
     }

//   Nivel 3
     if (retorno == "" && codigo.substr(2,13) != "0000000000000"){
          retorno = codigo.substr(0,2);
     }

//   Nivel 2
     if (retorno == "" && codigo.substr(1,14) != "00000000000000"){
          retorno = codigo.substr(0,1);
     }

     return retorno;
  }

  function js_selecionados(){
     var tabela = conplano.document.getElementById("tabela_seleciona");

     for(var i = 1; i < tabela.rows.length; i++){
          var id = tabela.rows[i].id.substr(6);

          if (conplano.document.getElementById("CHECK_"+id).checked == true){
               var estrut     = new String(tabela.rows[i].cells[4].innerHTML);
               var vetor      = estrut.split("&");
               var estrutural = new String(vetor[0]);

               var retorno = js_db_le_mae_conplano(estrutural);
               for(var ii = 1; ii < tabela.rows.length; ii++){
                    var id_marcar      = tabela.rows[ii].id.substr(6);
                    var estrut_marcar  = new String(tabela.rows[ii].cells[4].innerHTML);
                    var vetor_marcar   = estrut_marcar.split("&");
                    var estrut_marcado = new String(vetor_marcar[0]);


                    if (retorno == estrut_marcado.substr(0,retorno.length)){
                         var check     = conplano.document.getElementById("CHECK_"+id_marcar);
                         check.checked = true;
                    }
               }
          }
     }
  }

<?
  if (isset($chave_c60_estrut) && trim(@$chave_c60_estrut) != "" && !isset($chave_c60_estrut_alt)){
       if ($numrows_contas > 0){
?>
  document.form1.chave_c60_estrut.readOnly = true;
<?
       }
?>
  document.form1.chave_c60_estrut_alt.select();
  document.form1.chave_c60_estrut_alt.focus();
<?
  } else {
?>
  document.form1.chave_c60_estrut.select();
  document.form1.chave_c60_estrut.focus();
<?
  }
?>
</script>
</body>
</html>