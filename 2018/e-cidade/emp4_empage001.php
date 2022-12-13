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
  
include("classes/db_empage_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagemovforma_classe.php");
include("classes/db_empagemovconta_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_empageslip_classe.php");
include("classes/db_pcfornecon_classe.php");
$clempage = new cl_empage;
$clempagetipo = new cl_empagetipo;
$clempagemov = new cl_empagemov;
$clempagemovforma = new cl_empagemovforma;
$clempagemovconta = new cl_empagemovconta;
$clempord = new cl_empord;
$clempagepag = new cl_empagepag;
$clempageslip = new cl_empageslip;
$clpcfornecon = new cl_pcfornecon;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = false;

if(isset($e80_data_ano)){
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if(isset($atualizar)){
  db_inicio_transacao();
  $sqlerro=false;

  //--------------------------
  //rotina que exclui os todos os movimentos da agenda

  if($dados == "ordem" ){
    $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e81_codmov","","e81_codage=$e80_codage and e82_codord in ($tords) and e81_codmov not in(select e86_codmov from empageconf) and e80_instit = " . db_getsession("DB_instit")));
  }else{
    $result03  = $clempagemov->sql_record($clempagemov->sql_query_slip(null,"e81_codmov","","e81_codage=$e80_codage and e89_codigo in ($tords) and e81_codmov not in(select e86_codmov from empageconf) and e80_instit = " . db_getsession("DB_instit")));
  }
  $numrows03 = $clempagemov->numrows;
  if($numrows03 > 0){

    //------------------
    //rotina de exlusao
    if($sqlerro==false){
       for($i=0; $i<$numrows03; $i++){
          db_fieldsmemory($result03,$i);

          //---------------------------
          //rotina que excluir do empagepag
            if($sqlerro==false){
              $clempagepag->sql_record($clempagepag->sql_query_file($e81_codmov));
              if($clempagepag->numrows>0){
                $clempagepag->e85_codmov =  $e81_codmov;
                $clempagepag->excluir($e81_codmov);
                $erro_msg = $clempagepag->erro_msg;
                if($clempagepag->erro_status==0){
                       $sqlerro = true;
                }
              }
            }
          //---------------------------

          if($dados == "ordem"){
            //---------------------------
            //rotina que excluir do empord
              if($sqlerro==false){
                $clempord->sql_record($clempord->sql_query_file($e81_codmov));
                if($clempord->numrows > 0){
                  $clempord->e82_codmov =  $e81_codmov;
                  $clempord->excluir($e81_codmov);
                  $erro_msg = $clempord->erro_msg;
                  if($clempord->erro_status==0){
                         $sqlerro = true;
                  }
                }
              }
            //---------------------------
          }else{
            //---------------------------
            //rotina que excluir do empord
              if($sqlerro==false){
                $clempageslip->sql_record($clempageslip->sql_query_file($e81_codmov));
                if($clempageslip->numrows > 0){
                  $clempageslip->e82_codigo =  $e81_codmov;
                  $clempageslip->excluir($e81_codmov);
                  $erro_msg = $clempageslip->erro_msg;
                  if($clempageslip->erro_status==0){
                         $sqlerro = true;
                  }
                }
              }
            //---------------------------
          }
          //---------------------------
          //exclui as contas dos movimentos
          if($sqlerro==false){
            $clempagemovconta->excluir($e81_codmov);
            if($clempagemovconta->erro_status==0){
              $erro_msg = $clempagemovconta->erro_msg;
              $sqlerro=true;
            }
          }
          //---------------------------
          //---------------------------
          //exclui a forma de pagamento dos movimentos
          if($sqlerro==false){
            $clempagemovforma->excluir($e81_codmov);
            if($clempagemovforma->erro_status==0){
              $erro_msg = $clempagemovforma->erro_msg;
              $sqlerro=true;
            }
          }
          //---------------------------
          //---------------------------
          //exclui os movimentos
            if($sqlerro==false){
              $clempagemov->sql_record($clempagemov->sql_query_file($e81_codmov));
              if($clempagemov->numrows>0){
                $clempagemov->excluir($e81_codmov);
                $erro_msg = $clempagemov->erro_msg;
                if($clempagemov->erro_status==0){
                       $sqlerro = true;
                }
              }
            }
          //-----------------------------
       }
    }
    //--------------------------------------------------------------------------
    //rotina que exclui da tabela empagemov
  }
  //fim rotina de exclusão
  //--------------------------------------
  //-----------------------------------
  //rotina que inclui os movimentos
  if($sqlerro==false && $ords !=''){
    $arr =  split("XX",$ords);
    for($i=0; $i<count($arr); $i++ ){
       $dad = split("-",$arr[$i]);
       $ord = $dad[0];
       $emp = $dad[1];
       $val = $dad[2];
       $tip = $dad[3];

       //-----------------------------------
       //inclui na tabela empagemov
       if($sqlerro == false){
         $clempagemov->e81_codage = $e80_codage;
         $clempagemov->e81_numemp = "$emp";
         $clempagemov->e81_valor  = "$val";
         $clempagemov->incluir(null);
         $erro_msg = $clempagemov->erro_msg;
         if($clempagemov->erro_status==0){
               $sqlerro = true;
         }else{
           $mov = $clempagemov->e81_codmov;
         }
       }
       //-----------------------------------
       //-----------------------------------
       //inclui contas dos fornecedores tabela empagemovconta
       if($sqlerro==false){
//         echo "<BR><BR>".($clpcfornecon->sql_query_empenho(null,"pc64_contabanco","pc64_contabanco","e60_numemp=$emp"));
         $result_conta = $clpcfornecon->sql_record($clpcfornecon->sql_query_empenho(null,"pc64_contabanco","pc64_contabanco","e60_numemp=$emp"));
         if($clpcfornecon->numrows>0){
           db_fieldsmemory($result_conta,0);
           $clempagemovconta->e98_contabanco = $pc64_contabanco;
           $clempagemovconta->incluir($mov);
           if($clempagemovconta->erro_status==0){
             $erro_msg = $clempagemovconta->erro_msg;
             $sqlerro=true;
           }
         }
       }
       //-----------------------------------

       if($dados == "ordem"){
           //-----------------------------------
           //inclui na tabela empord
           if($sqlerro==false){
             $clempord->e82_codord = $ord;
             $clempord->e82_codmov = $mov;
             $clempord->incluir($mov,$ord);
             $erro_msg = $clempord->erro_msg;
             if($clempord->erro_status==0){
                   $sqlerro = true;
             }
           }
           //-----------------------------------
       }else{
           //-----------------------------------
           //inclui na tabela empageslip
           if($sqlerro==false){
             $clempageslip->e82_codord = $ord;
             $clempageslip->e82_codmov = $mov;
             $clempageslip->incluir($mov,$ord);
             $erro_msg = $clempageslip->erro_msg;
             if($clempageslip->erro_status==0){
                   $sqlerro = true;
             }
           }
           //-----------------------------------
       }

       //-----------------------------------
       //inclui na tabela empagepag
       if($sqlerro==false && $tip != '0'){
         $clempagepag->e85_codtipo = $tip;
         $clempagepag->e85_codmov = $mov;
         $clempagepag->incluir($mov,$tip);
         $erro_msg = $clempagepag->erro_msg;
         if($clempagepag->erro_status==0){
               $sqlerro = true;
         }
       }
       //-----------------------------------

    }
  }
  db_fim_transacao($sqlerro);
}

/*
if(isset($atualizar)){
  db_inicio_transacao();
  $sqlerro=false;
  if($dados == "ordem" ){
    $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null," distinct e81_codmov,e81_cancelado,e86_correto,e82_codord as ordslip ","","e81_codage=$e80_codage and e82_codord in ($tords) and (e86_codmov is null or ((e86_codmov is not null and e86_correto='f') and e81_cancelado is null)) "));
  }else{
    $result03  = $clempagemov->sql_record($clempagemov->sql_query_slip(null," distinct e81_codmov,e81_cancelado,e86_correto,e89_codigo as ordslip ","","e81_codage=$e80_codage and e89_codigo in ($tords) and (e86_codmov is null or (e86_codmov is not null and e86_correto='f') and e81_cancelado is null)) "));
  }  
  $numrows03 = $clempagemov->numrows;
  $arr_movimentosag = Array();
  for($im=0;$im<$numrows03;$im++){  	
    db_fieldsmemory($result03,$im);
    if(trim($e86_correto)!="f"){
      $arr_movimentosag[$e81_codmov] = $ordslip;
    }
    $dataaltera = date("Y-m-d",db_getsession("DB_datausu"));
    $clempagemov->e81_cancelado = $dataaltera;
    $clempagemov->e81_codmov    = $e81_codmov;
    $clempagemov->alterardata($e81_codmov,$dataaltera);
    if($clempagemov->erro_status==0){
      $erro_msg = $clempagemov->erro_msg;
      $sqlerro = true;
      break;
    }
  }
  //-----------------------------------
  //rotina que inclui os movimentos
  if($sqlerro==false && $ords !=''){
    $ords = str_replace("XX",",",$ords);
    $arr =  split(",",$ords);
    $arr_movok = Array();
    if($sqlerro==false){
      for($i=0; $i<count($arr); $i++){
	$dad = split("-",$arr[$i]);  
	$ord = $dad[0];
	$emp = $dad[1];
	$val = $dad[2];
	$tip = $dad[3];
	$caminho = false;
	if($sqlerro==false){
	  $movimentoaltera = array_search($ord,$arr_movimentosag);	  
	  $clempagemov->e81_codage = $e80_codage;
	  $clempagemov->e81_numemp = "$emp";
	  $clempagemov->e81_valor  = "$val";
	  $clempagemov->e81_cancelado = null;
	  if(in_array($ord,$arr_movimentosag)){
	    $caminho = true;
	    $clempagemov->e81_codmov = $movimentoaltera;
	    $clempagemov->alterar($movimentoaltera);
	    $erro_msg = $clempagemov->erro_msg;
	    $mov = $clempagemov->e81_codmov;
	    if($clempagemov->erro_status==0){
	      $sqlerro = true;
	      break;
	    }
	    
	    $clempagemov->e81_cancelado = null;
	    $clempagemov->e81_codmov    = $movimentoaltera;
	    $clempagemov->alterardata($movimentoaltera,null);
	    if($clempagemov->erro_status==0){
	      $erro_msg = $clempagemov->erro_msg;
	      $sqlerro = true;
	      break;
	    }
	    
	    //---------------------------
	    //exclui as contas dos movimentos
	    if($sqlerro==false){
	      $clempagemovconta->excluir($movimentoaltera);
	      if($clempagemovconta->erro_status==0){
		$erro_msg = $clempagemovconta->erro_msg;
		$sqlerro=true;
		break;
	      }
	    }
	    //---------------------------
	    //---------------------------
	    //exclui as contas pagadoras (empagepag)
	    if($sqlerro==false){
	      $clempagepag->excluir($movimentoaltera);
	      if($clempagepag->erro_status==0){
		$erro_msg = $clempagepag->erro_msg;
		$sqlerro = true;
		break;
	      }
	    }
	    //---------------------------
	  }else{
	    $clempagemov->incluir(null);
	    $mov = $clempagemov->e81_codmov;
	    $erro_msg = $clempagemov->erro_msg;
	    if($clempagemov->erro_status==0){
	      $sqlerro = true;
	      break;
	    }
	  }

	  if($caminho==false){
	    if($dados == "ordem"){
	      //---------------------------
	      //inclui na tabela empord
	      if($sqlerro==false){
		$clempord->e82_codord = $ord;
		$clempord->e82_codmov = $mov;
		$clempord->incluir($mov,$ord);
		if($clempord->erro_status==0){
		  $erro_msg = $clempord->erro_msg;
		  $sqlerro = true;
		}     
	      }
	      //---------------------------
	    }else{
	      //---------------------------
	      //inclui na tabela empageslip
	      if($sqlerro==false){
		$clempageslip->e82_codord = $ord;
		$clempageslip->e82_codmov = $mov;
		$clempageslip->incluir($mov,$ord);
		if($clempageslip->erro_status==0){
		  $erro_msg = $clempageslip->erro_msg;
		  $sqlerro = true;
		}     
	      }
	      //---------------------------
	    } 
	  }
	  //---------------------------
	  //inclui contas dos fornecedores
	  if($sqlerro==false){
	    $result_conta = $clpcfornecon->sql_record($clpcfornecon->sql_query_empenho(null,"pc64_contabanco","pc64_contabanco","e60_numemp=$emp"));
	    if($clpcfornecon->numrows>0){
	      db_fieldsmemory($result_conta,0);
	      $clempagemovconta->e98_contabanco = $pc64_contabanco;
	      $clempagemovconta->incluir($mov);
	      if($clempagemovconta->erro_status==0){
		$erro_msg = $clempagemovconta->erro_msg;
		$sqlerro=true;
	      }
	    }
	  }
	  //---------------------------
	  //---------------------------
	  //inclui na tabela empagepag
	  if($sqlerro==false && $tip != '0'){
	    $clempagepag->e85_codtipo = $tip;
	    $clempagepag->e85_codmov = $mov;
	    $clempagepag->incluir($mov,$tip);
	    if($clempagepag->erro_status==0){
	      $erro_msg = $clempagepag->erro_msg;
	      $sqlerro = true;
	    }     
	  }
	  //---------------------------
	}
      }
    }
  }
//  $sqlerro = true;
  db_fim_transacao($sqlerro);
}
*/

//quando entra pela primeira vez
if(empty($e80_data_ano)){
  $e80_data_ano = date("Y",db_getsession("DB_datausu"));
  $e80_data_mes = date("m",db_getsession("DB_datausu"));
  $e80_data_dia = date("d",db_getsession("DB_datausu"));
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

if(isset($data)){
    $result01 = $clempage->sql_record($clempage->sql_query_file(null,'e80_codage','',"e80_data='$data' and e80_instit = " . db_getsession("DB_instit")));
    $numrows01 = $clempage->numrows;
}

if(isset($nova)){
  db_inicio_transacao();
  $sqlerro=false;
  
  $clempage->e80_data = $data;
	$clempage->e80_instit = db_getsession("DB_instit");
  $clempage->incluir(null);
  if($clempage->erro_status==0){
       $sqlerro = true;
  }else{
    $e80_codage = $clempage->e80_codage;
  }	 
//  $sqlerro = true;
  db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1.e50_codord)document.form1.e50_codord.focus();" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
   <?
   $clrotulo = new rotulocampo;
   $clrotulo->label("e80_data");
   
//sempre que ja existir agenda entra nesta opcao  
 if(isset($e80_codage) && empty($pesquisar)){  
	include("forms/db_frmempage.php");

//pela primeira vez que entrar neste arquivo, entra nesta opcao para digitar a data da agenda 
//entra nesta opcao para escolher uma das agendas ou então selecionar uma jah existente
   }else{?>
    <center>
      <table>
        <tr>
	  <td>
       <fieldset><legend><b>Manutenção de agenda</b></legend> 
        <form name="form1" method="post" action="">
	      <br>
         <table>
	   <tr>
	      <td nowrap title="<?=@$Te80_data?>" align='right'>
	      <?=$Le80_data?>
	      </td>	
	      <td>	
	       <?
		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',1);
	       ?>
	      	<input name="pesquisar" type="submit"    value="Pesquisar">
	      </td>
	   </tr>
           <tr>
	     <td class='bordas' align='right'>
                 <? db_ancora("Agendas","js_empage();",$db_opcao);  ?>
	     </td>
<?
          if($numrows01!=0){
	     for($i=0; $i<$numrows01; $i++){
	       db_fieldsmemory($result01,$i);
	       $arr[$e80_codage] = $e80_codage;
	     }
	  }  
?>
             <td class='bordas'><small>
<?
          //variavel setada apenas quando o usuario pesquisar na func 
          if(isset($pri_codage)){
	    $e80_codage = $pri_codage;
	  } 

          if($numrows01==0){
	    echo "Nenhuma encontrado";  
	  }else{  
	       db_select("e80_codage",$arr,true,1);
	  }
?>	   
	       
	     </small></td>
            </tr>
            <tr>
              <td colspan="2" align="center">
	      <br>
	      	<input name="alterar" type="submit" value="Atualizar selecionada" <?=($numrows01==0?"disabled":"")?> >
	 	<input name="nova" type="submit" value="Incluir nova"> 
	      </td>	
            </tr>

	 </table>
       </form>	 
       </fieldset>
       </td>
     </tr>  
   </table>  
    </center>
<?   	
   }  
?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_empage(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empage','func_empage.php?funcao_js=parent.js_mostra|e80_codage|e80_data','Pesquisa',true);
}
function js_mostra(codage,data){
  arr = data.split('-');
  
  obj = document.form1;

  obj.e80_data_ano.value = arr[0];
  obj.e80_data_mes.value = arr[1];
  obj.e80_data_dia.value = arr[2];
  obj.e80_data.value = arr[2]+'/'+arr[1]+'/'+arr[0];
 
            obj=document.createElement('input');
            obj.setAttribute('name','pri_codage');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',codage);
            document.form1.appendChild(obj);

  document.form1.pesquisar.click();
 
  db_iframe_empage.hide();
  
}
</script>


<?
if(isset($atualizar) && $sqlerro==true){
  db_msgbox($erro_msg);
}

?>