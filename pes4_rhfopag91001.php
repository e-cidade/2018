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
include("libs/db_libpessoal.php");
include("classes/db_rhfopag_classe.php");
include("classes/db_rhpesdoc_classe.php");
db_postmemory($HTTP_POST_VARS);
$clrhfopag = new cl_rhfopag;
$clrhpesdoc = new cl_rhpesdoc;
$result = $clrhfopag->sql_record($clrhfopag->sql_query_file(null,null,"*",null,"rh66_instit = ".db_getsession("DB_instit")));  
$excluir = false;
if($clrhfopag->numrows > 0){
  $excluir = true;
}
?>
<script>
  function js_confirma(){
    var confirma = confirm('Eliminar Dados do Arquivo ?');
    if(confirma == true){
      return true;
    }else{
      return false;
    }
  }
  function js_erro(msg){
    alert(msg);
  }
</script>  
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" enctype="multipart/form-data">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
    <td align="left" nowrap title="Leitura do Arquivo do Banco (PASEP)" >
      <strong>Leitura do Arquivo do Banco (PASEP) &nbsp;&nbsp;</strong> 
    </td>
    <td >&nbsp;</td>
    </tr>
      <tr>

	<td nowrap align='left'>
  <?
  db_input('AArquivo',46,"",true,'file',1,"");
  ?>
	</td>
	</tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
	
      <tr>
       	<td colspan="2" align = "center">
          <?
            if($excluir == true){
          ?>    
             <input  name="gera" id="gera" type="submit" value="Gera" onclick ='return js_confirma()'>
          <?
            }else{
          ?>    
             <input  name="gera" id="gera" type="submit" value="Gera">

          <?
            }
          ?>  
        </td>
      </tr>
<?
// testa se esta setado o bota de carregamento e se o input nao esta vazio
if(isset($gera) && $AArquivo != ""){

  $clrhfopag->excluir(null,null,"rh66_instit = ".db_getsession("DB_instit"));  

  // Nome do arquivo temporário gerado no /tmp
  $nomearquivo =  "/tmp/".$_FILES["AArquivo"]["name"];
  // Nome do arquivo temporário gerado no /tmp
  $nometmp     = $_FILES["AArquivo"]["tmp_name"];
  // Faz um upload do arquivo para o local especificado
  move_uploaded_file($nometmp,$nomearquivo) or $erro_msg = "ERRO: Contate o suporte.";
  $LinhasArquivo = file($nomearquivo);

  $tamanho = sizeof($LinhasArquivo)-1;

  db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Leitura do Arquivo de Retorno do Banco');

	$nRegistro = 0;
  $sqlerro = false;
  $erro_msg = "";
  $sql = "select rh16_regist, rh16_pis, rh05_recis,rh01_admiss
          from rhpesdoc
               inner join rhpessoal  on  rhpessoal.rh01_regist = rhpesdoc.rh16_regist
               inner join rhpessoalmov   on  rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                                        and  rhpessoalmov.rh02_anousu = ".db_anofolha()."
		 	 									  	            and  rhpessoalmov.rh02_mesusu = ".db_mesfolha()."
																			  and  rhpessoalmov.rh02_instit = ".db_getsession("DB_instit")." 
               left join rhpesrescisao  on   rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes";


  db_inicio_transacao();
  for($cL=1;$cL<count($LinhasArquivo);$cL++) {

    db_atutermometro($cL,count($LinhasArquivo),'calculo_folha',1);

    $cLinha = $LinhasArquivo[$cL];     
    if(db_substr($cLinha,1,1) == "2"){
      $rh66_regist = db_val(trim(db_substr($cLinha,8,15)));
      $rh66_valor  = round(db_val(db_substr($cLinha,107,11))/100,2);
      $rh66_proces = db_val(db_substr($cLinha,151,1));
      $rh66_pis    = db_substr($cLinha,23,11);

      if(trim($rh66_regist) == 0){
        $result_rhpesdoc = $clrhpesdoc->sql_record($sql." where trim(rh16_pis) = '$rh66_pis' order by rh05_recis desc ,rh01_admiss ");
        if($clrhpesdoc->numrows > 0){
           db_fieldsmemory($result_rhpesdoc,0);
           $rh66_regist = $rh16_regist;
        }else{
           $rh66_pis    = db_substr($cLinha, 34,11);
           $result_rhpesdoc = $clrhpesdoc->sql_record($sql." where trim(rh16_pis) = '$rh66_pis' order by rh05_recis desc ,rh01_admiss desc");
           if($clrhpesdoc->numrows > 0){
             db_fieldsmemory($result_rhpesdoc,0);
             $rh66_regist = $rh16_regist;
           }
        }
      }
      if($rh66_pis+0 == 0 && $rh66_valor == 0){
        continue;
      }
      $clrhfopag->rh66_regist = $rh66_regist;
      $clrhfopag->rh66_pis    = "$rh66_pis";
      $clrhfopag->rh66_valor  = "$rh66_valor";
      $clrhfopag->rh66_proces = "$rh66_proces";
      $clrhfopag->rh66_instit = db_getsession("DB_instit");
      //echo "<BR> --> $tamanho ->".trim(db_substr($cLinha,8,15)) . " - ".$rh66_pis . " - ". $rh66_proces." - ".$rh66_valor." -  regist -->  $rh66_regist ";
      $clrhfopag->incluir($rh66_regist,db_getsession("DB_instit"));
      if($clrhfopag->erro_status=="0"){
         $sqlerro = true;
         $erro_msg = $clrhfopag->erro_msg;
         db_msgbox($erro_msg);
        break;
      }
    }  
	}
  db_fim_transacao($sqlerro);
  unset($gera);
  echo "<script> document.form1.submit()</script>";
}
?>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>