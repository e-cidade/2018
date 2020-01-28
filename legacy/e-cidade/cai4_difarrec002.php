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
include("libs/db_libcaixa.php");
$clautenticar= new cl_autenticar;

include("classes/db_cfautent_classe.php");
$clcfautent = new cl_cfautent;
   
   //{============================== 
  //rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada 
      $result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_tipautent as tipautent",'',"k11_ipterm = '".$HTTP_SERVER_VARS['REMOTE_ADDR']."'"));
      if($clcfautent->numrows > 0){
	db_fieldsmemory($result99,0);
      }else{
	db_msgbox("Cadastre o ip ".$HTTP_SERVER_VARS['REMOTE_ADDR']." como um caixa.");
	die();
	//db_redireciona('');
      }    
    //============================}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$ip = db_getsession("DB_ip");
$porta = 5001;

if(isset($HTTP_POST_VARS["numpre"]) && empty($reautenticar)){
    $valor     = $HTTP_POST_VARS["valor"];
    $historico = "S/Historico";
    if (isset($HTTP_POST_VARS["historico"]) && trim($HTTP_POST_VARS["historico"])!=""){
          $historico = $HTTP_POST_VARS["historico"];
    }

    if($acao!=1) {
       $valor = str_replace(",",".",$valor) * (double) (-1);
		}

    $sql = "select fc_difautentica(".substr($HTTP_POST_VARS["numpre"],0,8).",
	                           ".substr($HTTP_POST_VARS["numpre"],8,3).",
      	                          '".date('Y-m-d',db_getsession("DB_datausu"))."',
   	                           ".db_getsession("DB_anousu").",
	                           ".$HTTP_POST_VARS["reduz"].",'".db_getsession('DB_ip')."',
	                           ".$HTTP_POST_VARS["receitas"].",
	                           ".db_formatar($valor, "p").",
	                           ". db_getsession("DB_instit").",
	                          '".$historico."',
														" . db_getsession("DB_id_usuario") . ") as fc_autentica";
    $result = pg_exec($sql) or die($sql);
    db_fieldsmemory($result,0);
    if(substr($fc_autentica,0,1) != '1'){
     ?>
	  <script>
	  parent.alert('Erro ao gerar autenticacao.');
	  location.href = 'cai4_difarrec001.php?acao=<?=$acao?>';
	  </script>
	  <?
	  exit;
    }										 
}
  
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor=#CCCCCC bgcolor="#AAB7D5">
<form name="form1" >
<table width="100%">
  <tr>
    <td>
      <input name="numpre" type="hidden" value="<?=$numpre?>">
      <input name="acao" type="hidden" value="<?=$acao?>">
    </td>
    
  </tr>
  <tr>
    <td align="center"><font id="numeros" size="4">Processando Autentica&ccedil;&atilde;o do Código&nbsp;&nbsp;<?=@$numpre?></font></td>
  </tr>
</table>
</form>
</body>
</html>

<?
  if(isset($reautenticar)){
    $str_aut1= base64_decode($reautenticar);
  }else{  
////    $str_aut1=substr($fc_autentica,1);
  $str_aut1=$fc_autentica;

  }  

  if($tipautent==1){
////    $clautenticar->conectar($ip,$porta);
////    $clautenticar->condensado(true);
////    if(isset($reautenticar)){
////      $clautenticar->autenticar("$str_aut1");
////    }else{  
////      $clautenticar->imprimir_ln("$str_aut1");
////      $clautenticar->autenticar("$str_aut1");
////    }  
////    $clautenticar->fechar();
////    if($clautenticar->erro==true){
////      db_msgbox($clautenticar->erro_msg);
////      db_redireciona("cai4_difarrec001.php?acao=$acao");
////    }
    // abre o socket da impressora
    $fd = fsockopen(db_getsession('DB_ip'),4444);
    // grava a autenticacao
    fputs($fd,chr(15)."$str_aut1".chr(18).chr(10).chr(13));
    // fecha a conecção
    fclose($fd);
  }  

    $encod=base64_encode($str_aut1);
     echo "\n
	<script>
       if(confirm('Autenticar Novamente?')==false) {
	   location.href = 'cai4_difarrec001.php?acao=$acao';
       }else {
	  obj=document.createElement('input');
	   obj.setAttribute('name','reautenticar');
	   obj.setAttribute('type','hidden');
	   obj.setAttribute('value','$encod');
	   document.form1.appendChild(obj);
	  
	   document.form1.submit();
       }
    </script>
    ";

if($clautenticar->erro==true){
  $erro_msg=$clautenticar->erro_msg;
  db_redireciona("cai4_difarrec001.php?acao=$acao");
}  
?>