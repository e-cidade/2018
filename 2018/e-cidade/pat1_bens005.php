<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include(modification("classes/db_bens_classe.php"));
include(modification("classes/db_clabens_classe.php"));
include(modification("classes/db_bensmater_classe.php"));
include(modification("classes/db_bensimoveis_classe.php"));
include(modification("classes/db_bensbaix_classe.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_bensplaca_classe.php"));
include(modification("classes/db_departdiv_classe.php"));
include(modification("classes/db_histbemdiv_classe.php"));
include(modification("classes/db_bensdiv_classe.php"));
include(modification("classes/db_cfpatri_classe.php"));
include(modification("classes/db_cfpatriplaca_classe.php"));
include(modification("classes/db_benslote_classe.php"));
include(modification("classes/db_bensmarca_classe.php"));
include(modification("classes/db_bensmedida_classe.php"));
include(modification("classes/db_bensmodelo_classe.php"));
include(modification("classes/db_benscedente_classe.php"));
$clbenscedente  = new cl_benscedente();
$cldepartdiv    = new cl_departdiv;
$cldb_estrut    = new cl_db_estrut;
$clbens         = new cl_bens;
$clbensmater    = new cl_bensmater;
$clbensimoveis  = new cl_bensimoveis;
$clclabens      = new cl_clabens;
$clbensbaix     = new cl_bensbaix;
$clbensplaca    = new cl_bensplaca;
$clhistbemdiv   = new cl_histbemdiv;
$clbensdiv      = new cl_bensdiv;
$clcfpatri      = new cl_cfpatri;
$clcfpatriplaca = new cl_cfpatriplaca;
$clbenslote     = new cl_benslote;
$clbensmarca		= new cl_bensmarca;
$clbensmedida		= new cl_bensmedida;
$clbensmodelo		= new cl_bensmodelo;

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);

if (isset($db_atualizar) || isset($alterar)) {
  $db_opcao = 2;
  $db_botao = true;
} else {
  $db_opcao = 22;
  $db_botao = false;
}
if (isset($alterar)) {
  $sqlerro = false;

  $result  = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file($t52_instit));
  if ($clcfpatriplaca->numrows > 0) {
    db_fieldsmemory($result,0);
  }

  if (trim(@$t52_ident) == "0"){
    $clbens->erro_campo = "t52_ident";
    $sqlerro            = true;
    $erro_msg           = "Bens não Alterado. Alteração Abortada. \\n\\nUsuário: \\n\\n Placa de identificação não pode ser zero.\\n\\n Administrador.";
  }

  if (isset($t64_class) && trim($t64_class) == "" && $sqlerro == false) {
    if (isset($t52_descr) && trim($t52_descr) != '') {
      $erro_msg = "Usuário: \\n\\n Campo Classificação do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't64_class';
    } else {
      $erro_msg = "Usuário: \\n\\n Campo Descrição do Material nao Informado \\n\\n Administrador.";
      $sqlerro = true;
      $clbens->erro_campo = 't52_descr';
    }
  }

  if ($sqlerro==false) {
    //rotina q retira os pontos do estrutural da classe e busca o código do estrutural na tabela clabens
    $t64_class = str_replace(".","",$t64_class);
    $result_t64_codcla = $clclabens->sql_record($clclabens->sql_query_file(null,"t64_codcla as class",null," t64_class = '$t64_class' and t64_instit = ".db_getsession('DB_instit')));
    if ($clclabens->numrows>0) {
      db_fieldsmemory($result_t64_codcla,0);
    } else {
      $erro_msg = "Usuário: \\n\\n Alteração não concluída, Classificação Informada nao Existe \\n\\n Administrador.";
      $sqlerro=true;
    }
  }
  if ($sqlerro==false) {
    db_inicio_transacao();

    if ($t07_obrigplaca == "t" && strlen(trim($t52_ident)) == 0) {
      $sqlerro = true;
      $clbens->erro_campo = "t52_ident";
      $erro_msg = "Bens não Alterado. Alteração Abortada. \\n\\nUsuário: \\n\\n Placa de identificação não cadastrada\\n\\n Administrador.";
    }

    if ($sqlerro == false) {
      $clbens->t52_instit = $t52_instit;
      $clbens->t52_bem    = $t52_bem;
      $clbens->t52_descr  = $t52_descr;
      $clbens->t52_codcla = $class;
      $clbens->t52_numcgm = $t52_numcgm;
      $clbens->t52_valaqu = $t52_valaqu;
      $clbens->t52_bensmarca  = $t65_sequencial;
      $clbens->t52_bensmodelo = $t66_sequencial;
      $clbens->t52_bensmedida = $t67_sequencial;
      $clbens->t52_dtaqu  = $t52_dtaqu_ano."-".$t52_dtaqu_mes."-".$t52_dtaqu_dia;
      $clbens->t52_ident  = str_replace(".","",$t52_ident);
      $clbens->t52_obs    = $t52_obs;
      $clbens->t52_depart = $t52_depart;
      $clbens->alterar($t52_bem);
      if ($clbens->erro_status==0) {
        $sqlerro=true;
      }
      $erro_msg = $clbens->erro_msg;
    }
    
    if ($sqlerro == false) {
      $result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t52_bem));
      if ($clbensdiv->numrows>0) {
        $clbensdiv->excluir($t52_bem);
        if ($clbensdiv->erro_status==0) {
          $sqlerro=true;
          $erro_msg=$clbensdiv->erro_msg;
        }
      }
      if ($sqlerro == false) {
        if ($t33_divisao!="") {
          $clbensdiv->t33_divisao=$t33_divisao;
          $clbensdiv->incluir($t52_bem);
          if ($clbensdiv->erro_status==0) {
            $sqlerro=true;
            $erro_msg=$clbensdiv->erro_msg;
          }
        }
      }
    }


    if ($sqlerro == false) {
      if (isset($t52_ident)&&trim(str_replace(".","",$t52_ident))!=""&&$t07_confplaca==4) {
        $placaseq      = str_replace(".","",$t52_ident);
        //        $flag_grava    = true;
        $sqlbensplaca = $clbensplaca->sql_query(null,"t41_bem as codbem,t52_ident as identificacao",null,"t52_ident = '$placaseq' and t41_placaseq = $placaseq and t52_instit = $t52_instit and t41_bem <> $t52_bem");
        $res_t52_ident = $clbensplaca->sql_record($sqlbensplaca);
        //        echo($sqlbensplaca);
        //        exit;

        if ($clbensplaca->numrows > 0) {
          //db_fieldsmemory($res_t52_ident,0);
          //if ($codbem != $t52_bem) { // && $identificacao == $t52_ident){

            $clbens->erro_campo = "t52_ident";
            $sqlerro            = true;
            $erro_msg           = "Usuário: \\n\\n Alteração não concluída, placa de identificação já cadastrada para outro bem\\n\\n Administrador.";
          //}
          /*
             if ($t52_bem == $codbem && $t52_ident == $identificacao){
             $flag_grava = false;
             }
           */          
        }

        //        if ($sqlerro == false && $flag_grava == true) {
        if ($sqlerro == false) {
          if ($t52_ident != $t52_ident_atual){
            $clbensplaca->t41_bem=$t52_bem;
            $clbensplaca->t41_placa="";
            $clbensplaca->t41_placaseq="$placaseq";
            $clbensplaca->t41_obs="";
            $clbensplaca->t41_data=date('Y-m-d',db_getsession("DB_datausu"));
            $clbensplaca->t41_usuario=db_getsession("DB_id_usuario");

            $clbensplaca->incluir(null);
            if ($clbensplaca->erro_status==0) {
              $sqlerro=true;
              $erro_msg=$clbensplaca->erro_msg;
            }
          }
        }
        
      }

    	if ($sqlerro == false) {
        
    		//verifica se posusi benscedente
    		$clbenscedente->sql_record($clbenscedente->sql_query(null,"*","","t09_bem = $t52_bem"));
    		if ($clbenscedente->numrows > 0) {
    		 
    			$clbenscedente->excluir(null,"t09_bem = $t52_bem");
    		 
    		}
    		if($t04_sequencial != "" ){
    				$clbenscedente->t09_bem = $t52_bem;
	          $clbenscedente->t09_benscadcedente  = $t04_sequencial;
	          $clbenscedente->incluir(null);
	          $erro_msg = $clbenscedente->erro_msg;
	          if ($clbenscedente->erro_status==0) {
	            $sqlerro=true;
	            //db_msgbox("9 -> ".$erro_msg);
	            break;
	          }
    		}
      }
      if (isset($t52_ident)&&trim(str_replace(".","",$t52_ident))==""&&$t07_confplaca==4&&$t07_obrigplaca=="f") {
        $clbensplaca->t41_bem=$t52_bem;
        $clbensplaca->t41_placa="";
        $clbensplaca->t41_placaseq="0";
        $clbensplaca->t41_obs = "PLACA NÃO INFORMADA";
        $clbensplaca->t41_data=date('Y-m-d',db_getsession("DB_datausu"));
        $clbensplaca->t41_usuario=db_getsession("DB_id_usuario");

        $clbensplaca->incluir(null);
        if ($clbensplaca->erro_status==0) {
          $sqlerro=true;
          $erro_msg=$clbensplaca->erro_msg;
        }
      }
      }

      if ($sqlerro == false) {
        $erro_msg = $clbens->erro_msg;
      }
      db_fim_transacao($sqlerro);
    }

  } else if (isset($chavepesquisa)&&$chavepesquisa!="") {
  	$resultbensbaix = $clbensbaix->sql_record($clbensbaix->sql_query_file($chavepesquisa));
    if ($clbensbaix->numrows > 0) {
      $db_opcao = 3;
      $db_botao = false;
      $desabilitar_campos = 'true';
      echo "<center><b><h2> Bem Baixado </h2></b></center>";
    } else {
    	
      $db_opcao = 2;
      $db_botao = true;
      $desabilitar_campos = 'false';
    }
  }
  if (isset($alterar) || (isset($chavepesquisa)&&$chavepesquisa!="")) {
    if (isset($chavepesquisa)&&$chavepesquisa!="") {
      $t52_bem=$chavepesquisa;
    }
    if (isset($t52_depart)&&$t52_depart!="") {
      $t52_depart_alt=$t52_depart;
      $descrdepto_alt=$descrdepto;
    }
    //$result = $clbens->sql_record($clbens->sql_query($t52_bem));
    $result = $clbens->sql_record($clbens->sql_query($t52_bem));
    if ($clbens->numrows>0) {
      db_fieldsmemory($result,0);
    }
    $result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t52_bem));
    if ($clbensdiv->numrows>0) {
      db_fieldsmemory($result_bensdiv,0);
    }
    
    $result_placa = $clbensplaca->sql_record($clbensplaca->sql_query_file(null,"*"," t41_codigo desc limit 1 "," t41_bem=$t52_bem "));
    if ($clbensplaca->numrows>0) {
      db_fieldsmemory($result_placa,0);
      $result = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
      if ($clcfpatriplaca->numrows>0) {
        db_fieldsmemory($result,0);
        db_sel_instit(null,"db21_usasisagua");
        if($db21_usasisagua == 't') {
          $t52_ident = $t52_ident;
        } elseif ($t07_confplaca==1) {
          $t52_ident=$t41_placa.$t41_placaseq;
        } else if ($t07_confplaca==2) {
          $t52_ident=$t41_placa.db_formatar($t41_placaseq,'f','0',$t07_digseqplaca,'e',0);
        } else if ($t07_confplaca==3) {
          $t52_ident=$t41_placa;
          $t52_ident_seq=db_formatar($t41_placaseq,'f','0',$t07_digseqplaca,'e',0);
        } else if ($t07_confplaca==4) {
          //      $t52_ident=$t41_placa.$t41_placaseq;
        }
      }
    }

    if (isset($t52_depart_alt)&&$t52_depart_alt!="") {
      $t52_depart=$t52_depart_alt;
      $descrdepto=$descrdepto_alt;
    }
  }


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br />
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    	<?
    	  include(modification("forms/db_frmbens.php"));
    	?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($alterar) && $erro_msg!=""){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clbens->erro_campo!=""){
      echo "<script> document.form1.".$clbens->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clbens->erro_campo.".focus();</script>";
    };
  }
}
if(isset($chavepesquisa)){
    if (isset($importar)&&trim($importar)!=""){
         $parametros = "importar=true&codbem=$codbem&";
    } else {
         $parametros = "";
    }
  if(isset($desabilitar_campos)&&$desabilitar_campos == 'false'){
   echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.bensimoveis.disabled=false;
         parent.document.formaba.bensmater.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?".$parametros."db_opcaoal=22&t54_codbem=".@$chavepesquisa."';
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensmater.location.href='pat1_bensmater001.php?".$parametros."db_opcaoal=22&t53_codbem=".@$chavepesquisa."';
       }\n
    js_db_libera();
  </script>\n
 ";
  }else{
  echo "
  <script>
      function js_db_bloqueia(){
         parent.document.formaba.bensimoveis.disabled=false;
         parent.document.formaba.bensmater.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensimoveis.location.href='pat1_bensimoveis001.php?db_opcaoal=33&t54_codbem=".@$chavepesquisa."';
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_bensmater.location.href='pat1_bensmater001.php?db_opcaoal=33&t53_codbem=".@$chavepesquisa."';
      }\n
    js_db_bloqueia();
  </script>\n   
 ";
  }
}

 if(($db_opcao==22||$db_opcao==33) && $msg_erro==""){
    echo "<script>document.form1.pesquisar.click();</script>";
 } else if ($msg_erro!=""){
    db_msgbox($msg_erro);   
 }

?>