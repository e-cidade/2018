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
include("classes/db_veiculoscomb_classe.php");
include("libs/db_utils.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clveiculoscomb = new cl_veiculoscomb;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<?
$sqlerro  = false;
$erro_msg = "";
$db_botao = true;
$query    = "";
if ($db_opcao != 1) {
  if (isset($ve06_veiculos) && trim($ve06_veiculos) != ""){
    $query = "ve06_veiculos=".$ve06_veiculos;
  }
}

if (isset($confirmar) && trim($confirmar)!=""){
  $vet_combustiveis = split(",",$ve06_veiccadcomb);
  $vetor            = array(array("ve06_veiccadcomb","ve06_veiculos","ve06_padrao"));
  $cont_vetor       = 0;
  for($x = 0; $x < count($vet_combustiveis); $x++){
    if ($comb_padrao == $vet_combustiveis[$x]){
      $padrao = 1;
    } else {
      $padrao = 0;
    }

    $vetor["ve06_veiccadcomb"][$cont_vetor] = $vet_combustiveis[$x];

    if (isset($ve06_veiculos) && trim($ve06_veiculos) != ""){
      $cod_veiculo = $ve06_veiculos;
    } else {
      $cod_veiculo = "";
    }

    $vetor["ve06_veiculos"][$cont_vetor] = $cod_veiculo;
    $vetor["ve06_padrao"][$cont_vetor]   = $padrao;
    $cont_vetor++;
  }

  if ($db_opcao == 1){
    $virgula     = "";
    $cod_comb    = "";
    $comb_padrao = "";
    for($x = 0; $x < $cont_vetor; $x++){
      $cod_comb    .= $virgula.$vetor["ve06_veiccadcomb"][$x];
      $comb_padrao .= $virgula.$vetor["ve06_padrao"][$x];

      $virgula = ",";
    }
  }

  if ($db_opcao == 2){
    db_inicio_transacao();
    // Trocado combustivel padrao
    if (isset($marcados)){
      $rsVeicComb = $clveiculoscomb->sql_record($clveiculoscomb->sql_query_file(null,"*",null,"ve06_veiculos=$ve06_veiculos "));

      // exclui os registros dos combustíves.
      $iNumRowsVeic = $clveiculoscomb->numrows; 
      if ($iNumRowsVeic >0){
        for ($i=0;$i < $iNumRowsVeic;$i++){
          $oMarcado = db_utils::fieldsMemory($rsVeicComb,$i);
          $clveiculoscomb->ve06_sequencial = $oMarcado->ve06_sequencial;
          $clveiculoscomb->excluir($oMarcado->ve06_sequencial);

          if ($clveiculoscomb->erro_status == 0){
            $erro_msg = $clveiculoscomb->erro_msg;
            db_msgbox("Não será permitido alterar,pois já existe abastecimento para esse veículo.\n{$erro_msg}");
            $sqlerro  = true;
            break;

          }
        }

        $array_marcados=explode(',',$marcados);
        print_r($array_marcados);
        $cont_vetor=count($array_marcados);
        
         for($x = 0; $x < $cont_vetor; $x++){
          
          $clveiculoscomb->ve06_veiccadcomb = $array_marcados[$x]; 
          $clveiculoscomb->ve06_veiculos    = $ve06_veiculos;

          if (($comb_padrao)==($array_marcados[$x])){
          $clveiculoscomb->ve06_padrao   = 'true';
          }else{
            $clveiculoscomb->ve06_padrao   = 'false';
          }
          $clveiculoscomb->incluir(null);
          if ($clveiculoscomb->erro_status == 0){
            $erro_msg = $clveiculoscomb->erro_msg;
            $sqlerro  = true;
          }
        }
      }
      //$sqlerro = true;
      db_fim_transacao($sqlerro);
      //exit;
    }
  }


  if ($sqlerro == false){
    $db_botao = false;


    echo "<script>\n";

    if ($db_opcao == 1){
      echo "parent.document.form1.cod_comb.value    = '".$cod_comb."';\n";
      echo "parent.document.form1.comb_padrao.value = '".$comb_padrao."';\n";
    }

    echo "parent.document.form1.ve06_veiccadcomb.value = '{$descr_veiccadcomb}';";
    echo "parent.db_iframe_veiculoscomb.hide();";
    echo "     </script>";
  } else {
    echo "<script>parent.veiculoscomb.document.form2.descr_veiccadcomb.value = '';";
  }
}


?>
<center>
<form name="form1" method="post" action="">

      <?
         db_input("comb_padrao",10,"",true,"hidden",3);
         db_input("ve06_veiccadcomb",10,"",true,"hidden",3);
         db_input("descr_veiccadcomb",10,"",true,"hidden",3);
         db_input("marcados",10,"",true,"hidden",3);
      ?>

<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td nowrap height="50">&nbsp;
    </td>
  </tr>
  <tr> 
    <td nowrap align="center" valign="top" bgcolor="#CCCCCC"> 
      <?
         db_input("db_opcao",10,"",true,"hidden",3);
         if (isset($ve06_veiculos) && trim($ve06_veiculos) != ""){
           db_input("ve06_veiculos",10,"",true,"hidden",3);
         }
      ?>
      <iframe name="veiculoscomb" src="forms/db_frmveiculoscomb.php?<?=$query?>" frameborder="0" align="middle" width="780">
      </iframe>
    </td>
  </tr>
</table>
<table width="790" border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td align="right">
      <input name="confirmar" type="button" value="Confirmar" onClick="js_confirma_dados();" <?=($db_botao == false?"DISABLED":"")?>>
    </td>
    <td align="left">
      <input name="fechar"    type="button" value="Fechar" onClick="parent.db_iframe_veiculoscomb.hide();">
    </td>
  </tr>

</table>
</form>
</center>
</body>
</html>
<script>
function js_confirma_dados(){

  var cont_padrao   = 0;
  var contador      = new Number (veiculoscomb.document.form2.length);
  var erro          = false;
  var virgula       = "";
  var vir           = "";
  var marcados      = ""; 
  var inc           = "";
  var verifica      = ""; 
  var descr_veiccadcomb="";
  var cod_veiccadcomb="";
  document.form1.comb_padrao.value = '';
  document.form1.marcados.value = '';
  //
  // Buscando os combustiveis selecionados
  //
  var oObjs = veiculoscomb.document.getElementsByTagName('input');
  
            for (i=0;i< oObjs.length; i++){

                if (oObjs[i].type == 'checkbox' && oObjs[i].checked == true) {
                   document.form1.marcados.value += new String(virgula+oObjs[i].value).replace('inc','');
                   descr_veiccadcomb += virgula+veiculoscomb.document.getElementById("descr_"+oObjs[i].value).innerHTML.trim();
                   cod_veiccadcomb  += virgula+oObjs[i].value;         
                   virgula = ",";
                } else if (oObjs[i].type == 'radio' && oObjs[i].checked == true) {
                   document.form1.comb_padrao.value = new String(oObjs[i].value).replace('inc','');
                }
            } 
            
            document.form1.descr_veiccadcomb.value  = descr_veiccadcomb;
            document.form1.ve06_veiccadcomb.value   = cod_veiccadcomb;


          if (document.form1.comb_padrao.value == ''){
              alert("Selecione um combústivel padrão.");
              erro = true;
             }

         if (erro == false){

             var obj = document.createElement("input");

             obj.setAttribute("name","confirmar");
             obj.setAttribute("type","hidden");
             obj.setAttribute("value","confirmar");
             document.form1.appendChild(obj);
             document.form1.submit();
           }
}
</script>
<?
  if (trim($erro_msg) != ""){
    db_msgbox($erro_msg);
  }
?>