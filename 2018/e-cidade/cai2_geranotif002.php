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
include("classes/db_notidebitos_classe.php");
include("classes/db_notidebitosreg_classe.php");
include("classes/db_notificadoc_classe.php"); 
include("classes/db_notinumcgm_classe.php");
include("classes/db_notiinscr_classe.php");
include("classes/db_notimatric_classe.php");
include("classes/db_notiusu_classe.php");
include("classes/db_noticonf_classe.php");
include("classes/db_notificacao_classe.php");
include("classes/db_listanotifica_classe.php");
include("classes/db_contrinot_classe.php");
include("classes/db_listadoc_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS);
$clnotidebitos    = new cl_notidebitos;
$clnotidebitosreg = new cl_notidebitosreg;
$clnotinumcgm     = new cl_notinumcgm;
$clnotiinscr      = new cl_notiinscr;
$clnotimatric     = new cl_notimatric;
$clnotiusu        = new cl_notiusu;
$clnoticonf       = new cl_noticonf;
$clnotificacao    = new cl_notificacao;
$cllistanotifica  = new cl_listanotifica;
$clcontrinot      = new cl_contrinot;
$cllistadoc       = new cl_listadoc;
$clnotificadoc	  = new cl_notificadoc;
$db_opcao = 1;
$db_botao = true;
$instit = db_getsession("DB_instit");
$clnotificacao ->k50_instit = db_getsession("DB_instit");
if(isset($excluirNotif) and  $excluirNotif == "true" ){
    db_inicio_transacao();
    $erro1 = false;
 
    $minimo = 0;
    $maximo = 0;
    

    if($DBtxt11 > 0){  
      
      $minimo = $DBtxt10;
      $maximo = $DBtxt11;
	
    }elseif ($k60_codigo != '' ){
      
      $resultlistanotifica = $cllistanotifica->sql_record($cllistanotifica->sql_query("","*",""," min(k63_notifica), max(k63_notifica) ",""," k63_codigo =  $k60_codigo and k50_instit = $instit and k60_instit = $instit" ));
      db_fieldsmemory($resultlistanotifica,0);
      $minimo = $min;
      $maximo = $max;
	}
    if($minimo == 0){
      
      db_msgbox(_M('tributario.notificacoes.cai2_geranotif002.nao_existem_notificacoes_serem_excluidas'));
      echo "<script>location.href='cai2_geranotif002.php'</script>";
      exit;
    }
   
     for ($x = $minimo; $x <= $maximo; $x++){
       
       $resultnoticonf = $clnoticonf->sql_record($clnoticonf->sql_query("","*",""," k54_notifica = $x  and k50_instit = $instit "));
       if ($clnoticonf->numrows == 0){ 
          // não posso excluir quando tem coticonf

       if($erro1 == false){
         
	       $resultnotiusu = $clnotiusu->sql_record($clnotiusu->sql_query("","*","","k52_notifica = $x and k50_instit = $instit "));
	       if ($clnotiusu->numrows > 0){ 
	         
	          $clnotiusu->k52_notifica = $x;
	          $clnotiusu->excluir($x);
			 // echo "<br>excluiu notiusu-k52_notifica  = $x";
	          if ($clnotiusu->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro= $clnotiusu->erro(true,false);       
	          } 
	       }
       } 

     
       if($erro1 == false){
         
	       $resultcontrinot = $clcontrinot->sql_record($clcontrinot->sql_query(null,"*",null,"d08_notif = $x and k50_instit = $instit " ));
	       if ($clcontrinot->numrows > 0){ 
	         
	          db_fieldsmemory($resultcontrinot,0);
	          $clcontrinot->d08_notif  = $d08_notif;
	          $clcontrinot->d08_matric = $d08_matric;
	          $clcontrinot->d08_contri = $d08_contri;
	          $clcontrinot->excluir($d08_sequencial);
			 //  echo "<br>excluiu contrinot- d08_sequencial = $d08_sequencial";
	          if ($clcontrinot->erro_status ==0){
	             $erro1 = true;
	             $msgerro= $clcontrinot->erro(true,false);
	          }
	       }
       }
       
       if($erro1 == false){
         
	       $resultnotidebitosreg = $clnotidebitosreg->sql_record($clnotidebitosreg->sql_query(null,"*",""," k43_notifica = $x and k50_instit = $instit"));

	       if ($clnotidebitosreg->numrows > 0){
	          $clnotidebitosreg->k43_notifica = $x;
	          $clnotidebitosreg->excluir(null,"k43_notifica = $x");
	          if ($clnotidebitosreg->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro=$clnotidebitosreg->erro(true,false);       
	          }
	         
	          $resultnotificadoc = $clnotificadoc->sql_record($clnotificadoc->sql_query(null,"*",""," k100_notifica = $x and k50_instit = $instit"));
	          
	          if ($clnotificadoc->numrows > 0){
	            
	          	$clnotificadoc->k100_notifica = $x;
	          	$clnotificadoc->excluir(null,"k100_notifica = $x");
	          	if ($clnotificadoc->erro_status ==0){
	          	  
	             $erro1 = true;
	             $msgerro=$clnotificadoc->erro(true,false);       
	            }
	          } 
	        }
       }       
       
       
       if($erro1 == false){
         
	       $resultnotidebitos = $clnotidebitos->sql_record($clnotidebitos->sql_query("","","","*",""," k53_notifica = $x and k50_instit = $instit"));
	       if ($clnotidebitos->numrows > 0){
	         
	          $clnotidebitos->k53_notifica = $x;
	          $clnotidebitos->excluir($x);
			 // echo "<br>excluiu notidebitos- k53_notifica =$x";
	          if ($clnotidebitos->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro=$clnotidebitos->erro(true,false);       
	          }
	       }
       }
       
       
       if($erro1 == false){
         
	       $resultnotinumcgm = $clnotinumcgm->sql_record($clnotinumcgm->sql_query("","","*",""," k57_notifica = $x  and k50_instit = $instit "));
	       if ($clnotinumcgm->numrows > 0){ 
	         
	          $clnotinumcgm->k57_notifica = $x;
	          $clnotinumcgm->excluir($x);
			 // echo "<br>excluiu notinumcgm->k57_notifica=$x";
	          if ($clnotinumcgm->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro=$clnotinumcgm->erro(true,false);       
	          } 
	       }
       }
       
       if($erro1 == false){
         
	       $resultnotiinscr = $clnotiinscr->sql_record($clnotiinscr->sql_query("","","*",""," k56_notifica = $x and k50_instit = $instit "));
	       if ($clnotiinscr->numrows > 0){ 
	         
	          $clnotiinscr->k56_notifica = $x;
	          $clnotiinscr->excluir($x);
			 // echo "<br>excluiu notiinscr->k56_notifica=$x";
	          if ($clnotiinscr->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro=$clnotiinscr->erro(true,false);       
	          }
	       }
       }
       
       if($erro1 == false){
         
	       $resultnotimatric = $clnotimatric->sql_record($clnotimatric->sql_query("","","*",""," k55_notifica = $x and k50_instit = $instit "));
	       if ($clnotimatric->numrows > 0){ 
	         
	          $clnotimatric->k55_notifica = $x; 
	          $clnotimatric->excluir($x);
			//  echo "<br>excluiu notimatric->k55_notifica= $x";
	          if ($clnotimatric->erro_status ==0){
	            
	             $erro1 = true;
	             $msgerro= $clnotimatric->erro(true,false);       
	          } 
	       }
       }
	     
   
       if($erro1 == false){
         
         $resultlistanotifica = $cllistanotifica->sql_record($cllistanotifica->sql_query("","","","*",""," k63_notifica = $x and k50_instit = $instit and k60_instit = $instit "));
	       if ($cllistanotifica->numrows > 0){ 
	         
	          $cllistanotifica->k63_notifica = $x;
	          $cllistanotifica->excluir(null,null,$cllistanotifica->k63_notifica,null);
		  //echo "<br>excluiu listanotifica->k63_notifica = $x";
	          if ($cllistanotifica->erro_status ==0){
	            
              db_msgbox("aqui-1");
              exit;
	             $erro1 = true;
	             $msgerro=$cllistanotifica->erro(true,false);       
	          }
	       }
       }
       if($erro1 == false){
         
         $resultnotificacao = $clnotificacao->sql_record($clnotificacao->sql_query("","k50_notifica","","k50_notifica = $x and k50_instit = $instit" ));
	       $linhasnotificacao = $clnotificacao->numrows;
	             
	       if ($linhasnotificacao > 0){
	         
	          $clnotificacao->k50_notifica = $x;
	          $clnotificacao->excluir($x,"k50_instit = $instit and k50_notifica = $x");
			     // echo "<br>excluiu notificacao->k50_notifica $x";
	          if ($clnotificacao->erro_status ==0){

              db_msgbox("aqui-22");
              db_msgbox($x);
              exit;
	             $erro1 = true;
	             $msgerro= $clnotificacao->erro(true,false);       
	          } 
	       }
	    }
     } 
    }
	
    
    if ($erro1 == false){
      db_fim_transacao($erro1);
    }else{
      db_fim_transacao($erro1);
    }
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>

<script>

function js_emite(){

    var val1   = new Number(document.form1.k60_codigo.value);
    var valor1 = new Number(document.form1.DBtxt10.value);
    var valor2 = new Number(document.form1.DBtxt11.value);
    if(valor1.valueOf() > valor2.valueOf()){
       alert(_M('tributario.notificacoes.cai2_geranotif002.notificacao_inicial_maior_final'));
       return false;
    }
    
    if(valor1.valueOf() == 0 && valor2.valueOf() == 0 && val1.valueOf() == 0 ){
       alert(_M('tributario.notificacoes.cai2_geranotif002.selecione_lista_intervalo'));
       return false;
    }
    if(valor1.valueOf() > 0 && valor2.valueOf() > 0 && val1.valueOf() > 0 ){
       alert(_M('tributario.notificacoes.cai2_geranotif002.escolha_uma_opcao'));
       return false;
    }
    // verificar se tem situacao cadastrada
    js_OpenJanelaIframe('','db_iframe_verifica','cai2_geranotif004.php?&lista='+val1+'&valor1='+valor1+'&valor2='+valor2,'Pesquisa',false);
//    return true;
}
function js_excluinoti(exc,sit){
  if(exc == false){
    document.form1.excluirNotif.value='true';
    document.form1.submit();
  }else{
    //if ( confirm('Existem Notificações com a(s) seguinte(s) Situações:'+sit+'\nVerifique Relatórios')){
    if ( confirm(_M('tributario.notificacoes.cai2_geranotif002.existem_notificacoes', {sSituacao: sit}))){
      document.form1.excluirNotif.value='true';
      document.form1.submit();
    }
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">


  
<form class="container" name="form1" method="post" action=""  >
  <fieldset>
    <legend>Procedimentos - Notificações</legend>    
	<table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tk60_codigo?>" >
          <?
	   		db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",4)
          ?>
        </td>
        <td>
          <?
	  		db_input('k60_codigo',4,$Ik60_codigo,true,'text',4,"onchange='js_pesquisalista(false);'");
          	db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Notificações:
        </td>
        <td>
          <b>De &nbsp;</b>
		  <?
            $DBtxt10 = 0;
	  		db_input('DBtxt10',10,$IDBtxt10,true,'text',2);
		  ?>
          <b>&nbsp; Até &nbsp;</b>
		  <?
            $DBtxt11 = 0;
	  		db_input('DBtxt11',10,$IDBtxt11,true,'text',2);
		  ?>
        </td>
      </tr>
      <tr>
        <td colspan="2"><br>Obs: Escolha uma lista ou um intervalo de notificações a serem excluídas.&nbsp;
           <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           Só será excluído as Notificações sem movimentação!
        </td>
      </tr>
    </table>
  </fieldset>
  <input name="excluir" type="button" id="excluir" value="Excluir" onClick="return js_emite();">
  <input name="excluirNotif" type="hidden" value="">
</form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_pesquisalista(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_lista.php?funcao_js=parent.js_mostralista1|k60_codigo|k60_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_lista.php?pesquisa_chave='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostralista';
     }
}
function js_mostralista(chave,erro){
  document.form1.k60_descr.value = chave;
  if(erro==true){
     document.form1.k60_descr.focus();
     document.form1.k60_descr.value = '';
  }
}
function js_mostralista1(chave1,chave2){
     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
}


</script>


<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
    
if (isset($erro1)){
  if($erro1 == false){
    db_msgbox(_M('tributario.notificacoes.cai2_geranotif002.processamento_concluido'));
  }else{
    db_msgbox($msgerro);
  }
}
?>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");
$("DBtxt10").addClassName("field-size3");
$("DBtxt11").addClassName("field-size3");

</script>