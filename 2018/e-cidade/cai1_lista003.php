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
include("classes/db_lista_classe.php");
include("classes/db_listadoc_classe.php");
include("classes/db_listadeb_classe.php");
include("classes/db_listatipos_classe.php");
include("classes/db_listanotifica_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS);
$cllista         = new cl_lista;
$cllistadoc      = new cl_listadoc;
$cllistadeb      = new cl_listadeb;
$cllistatipos    = new cl_listatipos;
$cllistanotifica = new cl_listanotifica;
$db_opcao = 1;
$db_botao = true;
$instit = db_getsession("DB_instit");
if((isset($HTTP_POST_VARS["excluir"]) && $HTTP_POST_VARS["excluir"])=="Excluir"){
    db_inicio_transacao();
    $erro1 = false;
    $resultnotifica = $cllistanotifica->sql_record($cllistanotifica->sql_query("","","","*",""," k63_codigo = $k60_codigo and k50_instit = $instit and k60_instit = $instit"));
    if ($cllistanotifica->numrows > 0 ){
       db_msgbox(_M('tributario.notificacoes.cai1_lista003.lista_possui_notificacoes_geradas'));
    }else{
      
       // exclui do listatipos
       $cllistatipos->k62_lista = $k60_codigo;
       $cllistatipos->excluir_lista($k60_codigo);
       if($cllistatipos->erro_status !="0"){
           $erro1 = true;
       }else{
           $cllistatipos->erro(true,false);       
       } 
       
       // exclui do listadeb
       $cllistadeb->k61_codigo = $k60_codigo;
       $cllistadeb->excluir_lista($k60_codigo);
       if($cllistadeb->erro_status !="0" && $erro1 == true){
          $erro1 = true;
       }else{
          $cllistadeb->erro(true,false);       
       }
       
       // exclui do lista
       $cllistadoc->excluir($k60_codigo);
       if($cllistadoc->erro_status !="0"){
				 $erro1 = true;
       }else{
				 $cllistadoc->erro(true,false);       
       } 

       // exclui do lista
       $cllista->k60_codigo = $k60_codigo;
       $cllista->excluir($k60_codigo);
       if($cllista->erro_status !="0"){
           $erro1 = true;
       }else{
           $cllista->erro(true,false);       
       } 

    }
    if ($erro1 == true){
       db_msgbox(_M('tributario.notificacoes.cai1_lista003.processamento_concluido'));
       db_fim_transacao();
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

    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai1_lista003.selecione_lista'));
       return false;
    }

  
  jan = window.open('cai2_emitelista002.php?lista='+document.form1.k60_codigo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Procedimentos - Listas</legend>
    <table class="form-container">
      <tr>
        <td title="<?=@$Tk60_codigo?>" >
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
    </table>
  </fieldset>
  <input name="excluir" type="submit" id="excluir" value="Excluir" >
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

?>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").addClassName("field-size7");

</script>