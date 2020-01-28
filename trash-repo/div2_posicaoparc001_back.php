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
include("classes/db_proced_classe.php");
$clproced = new cl_proced;

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$clrotulo->label('DBtxt34');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$db_botao = true;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){

  var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
  var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
  
  if(data1.valueOf() > data2.valueOf()){
    alert('Data inicial maior que data final. Verifique!');
    return false;
  }



   var H = document.getElementById("tipodivida").options;
   if(H.length > 0){
      tipo = 'tipodivida=';
      virgula = '';
      for(var i = 0;i < H.length;i++) {
         tipo += virgula+H[i].value;
         virgula = '-';
      }
   }else{
      tipo = '';
   }

 jan = window.open('div2_posicaoparc002.php?'+tipo+'&grafico=-'+document.form1.grafico.value+'&sele='+document.form1.sele.value+'&atraso='+document.form1.DBtxt34.value+'&datai='+document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value+'&dataf='+document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>  
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
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
  <table border="1" align="center">
    <form  name="form1" method="post" action="" >
      <tr>
        <td colspan="2">
        <table  align="center" border="0" cellspacing="1" >
         <tr>
           <td colspan="2" align="center"><strong>Parcelamentos Emitidos Entre :</strong>
           </td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <tr>
           <td align="right"> <strong>De: </strong>
           </td>
           <td  align="left">&nbsp;&nbsp;&nbsp;
           <?=db_inputdata('DBtxt21','','','',true,'text',4)?>
           </td>
         </tr>
         <tr>
           <td align="right"> <strong>Até: </strong>
           </td>
           <td align="left"> &nbsp;&nbsp;&nbsp;
           <?
            $dia = date('d');
            $mes = date('m');
            $ano = date('Y');
            db_inputdata('DBtxt22',$dia,$mes,$ano,true,'text',4)
           ?>
           </td>
         </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         
          <tr>
            <td align="right" title="<?=$TDBtxt34?>">
            <?=$LDBtxt34?>
	    </td>
	    <td align="left">&nbsp;&nbsp;&nbsp;
            <?
               db_input("DBtxt34",4,$IDBtxt34,true,'text',4)
            ?>
            </td>
         </tr>
         
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <tr >
           <td align="right"> <strong>Emitir :<strong>
           </td>
           <td align="left">&nbsp;&nbsp;&nbsp;
           <?
	     $x = array("RG"=>"Relatório e Gráfico","R"=>"Somente Relatório","G"=>"Somente Gráfico");
	     db_select('grafico',$x,true,2);
          ?>
          </td>
        </tr>
         <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
         </tr>
         <!--
         <tr >
           <td align="right"> <strong>Imprimir Responsável :<strong>
           </td>
           <td align="left">&nbsp;&nbsp;&nbsp;
           <?
	     $xx = array("S"=>"Sim","N"=>"Não");
	     db_select('responsavel',$xx,true,2);
          ?>
          </td>
        </tr>
        -->
        </table>
	</td>
	<td>
      <table>
      <tr >
        <td align="right"> <strong>Opção de Seleção :<strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
	 $xxx = array("S"=>"Somente Selecionados","N"=>"Menos os Selecionados");
	  db_select('sele',$xxx,true,2);
          ?>

        </td>
      </tr>
<?
include("dbforms/db_classesgenericas.php");
$aux = new cl_arquivo_auxiliar;
$aux->cabecalho = "<strong>Selecione um tipo de dívida ou deixe em branco para todos</strong>";
$aux->codigo = "k00_tipo";
$aux->descr  = "k00_descr";
$aux->nomeobjeto = 'tipodivida';
$aux->funcao_js = 'js_funcaotipo';
$aux->funcao_js_hide = 'js_funcaotipo1';
$aux->sql_exec  = "";
$aux->func_arquivo = "func_arretipo.php";
$aux->nomeiframe = "iframe_arretipo";
$aux->db_opcao = 2;
$aux->tipo = 2;
$aux->linhas = 15;
$aux->vwhidth = 250;
$aux->funcao_gera_formulario();			    
?>

      <tr>
        <td colspan="4" align = "center"> 
         <input name="db_opcao" type="button" id="db_opcao" value="Imprimir" onClick="js_emite();">
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisadb02_idparag(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    db_iframe.jan.location.href = 'cai2_emitenotif003.php?lista='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostradb_paragrafo1|1|3';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'cai2_emitenotif003.php?lista='+document.form1.k60_codigo.value+'&pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostradb_paragrafo';
  }
}
function js_mostradb_paragrafo(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.codigo.focus(); 
    document.form1.codigo.value = ''; 
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }  
    parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;
  
}
function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.codigo.value = chave1;
  document.form1.descr.value = chave2;
  db_iframe.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

function js_pesquisanotitipo(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_notitipo.php?funcao_js=parent.js_mostranotitipo1|k51_procede|k51_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_notitipo.php?pesquisa_chave='+document.form1.k51_procede.value+'&funcao_js=parent.js_mostranotitipo';
     }
}
function js_mostranotitipo(chave,erro){
  document.form1.k51_descr.value = chave;
  if(erro==true){
     document.form1.k51_descr.focus();
     document.form1.k51_descr.value = '';
  }
}
function js_mostranotitipo1(chave1,chave2){
     document.form1.k51_procede.value = chave1;
     document.form1.k51_descr.value = chave2;
     db_iframe.hide();
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