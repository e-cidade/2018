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
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_orctiporec_classe.php");

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
$clrotulo->label('o08_reduz');

$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label();

db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_verifica(){
  var anoi = new Number(document.form1.datai_ano.value);
  var anof = new Number(document.form1.dataf_ano.value);
  if(anoi.valueOf() > anof.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  return true;
}


function js_emite(){
  vir = "";
  cods= "";
  var_obj = document.getElementById('receita').length;
  for(y=0;y<var_obj;y++){
    var_if = document.getElementById('receita').options[y].value;
    cods += vir + var_if;
    vir = ",";
  }
 
  if (document.form1.o15_codigo.value == 0){
       recurso = "";
  } else {
       recurso = document.form1.o15_codigo.value;
  }

  qry  = "estrut="+document.form1.estrut.value;
  qry += "&sinana="+document.form1.sinana.value;
  qry += "&ordem="+document.form1.ordem.value;
  qry += "&desdobrar="+document.form1.desdobrar.value;
  qry += "&codrec="+cods;
  qry += "&datai="+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry += "&dataf="+document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
  qry += "&tipo="+document.form1.tipo.value;
  qry += "&recurso="+recurso;

  jan = window.open('cai2_correceitas002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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

  <table  align="center">
    <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <tr>
        <td align="rigth" ><strong>Data Inicial :</strong>
        <?=db_inputdata('datai','01','01',db_getsession("DB_anousu"),true,'text',4)?>
        </td>
        <td align="left" ><strong>Data Final :</strong>
        <?
         $datausu = date("Y/m/d",db_getsession("DB_datausu"));
         $dataf_ano = substr($datausu,0,4);
         $dataf_mes = substr($datausu,5,2);
         $dataf_dia = substr($datausu,8,2);

        ?>
        <?=db_inputdata('dataf',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4)?>
        </td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <table>
            <tr>
              <td align="center">
                 <?
                 $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>RECEITAS</strong>";
                 $aux->codigo = "k02_codigo";
                 $aux->descr  = "k02_drecei";
                 $aux->nomeobjeto = 'receita';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_tabrec_todas.php";
                 $aux->nomeiframe = "db_iframe";
                 $aux->localjan = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 0;
                 $aux->linhas = 6;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
                 ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Estrutural da Receita:</strong> 
	</td>
        <td>
	<?
 	  db_input('estrut',15,0,true,'text',2,"");
	?>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Tipo de Receita:</strong> 
	</td>
        <td>
          <select name="tipo" onchange="js_valor();">
            <option value = 'T'>Todas</option>
            <option value = 'O'>Orçamentarias</option>
            <option value = 'E'>Extra-Orçamentarias</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Desdobrar Receita:</strong> 
	</td>
        <td>
          <select name="desdobrar" onchange="js_valor();">
            <option value = 'N'>Não</option>
            <option value = 'S'>Sim</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Ordem:</strong> 
	</td>
        <td>
          <select name="ordem" >
            <option value = 'r'>Código Receita</option>
            <option value = 'e'>Estrutural</option>
            <option value = 'a'>Alfabética Descrição Receita</option>
            <option value = 'd'>Reduzido Orçamento</option>
            <option value = 'c'>Reduzido Conta</option>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Tipo:</strong> 
	</td>
        <td>
          <select name="sinana" >
            <option value = 'S1'>Sintético/Receita</option>
            <option value = 'S2'>Sintético/Estrutural</option>
            <option value = 'A'>Analítico</option>
            <option value = 'S3'>Sintético/Conta</option>
        </td>
      </tr>
       <tr>
         <td nowrap title="<?=$To15_codigo?>" align="right"><?=$Lo15_codigo?></td>
         <td nowrap>
         <?
           $dbwhere     = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
           $res_tiporec = $clorctiporec->sql_record($clorctiporec->sql_query_file(null,"o15_codigo,o15_descr","o15_codigo",$dbwhere));
           db_selectrecord("o15_codigo",$res_tiporec,true,2,"","","","0");
         ?>
         </td>
       </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
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

function js_pesquisatabrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conclass','func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|0|3','Pesquisa',true,'0');
  }else{
     if(document.form1.c60_codcla.value != ''){
     	js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conclass','func_tabrec_todas.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
        document.form1.k02_drecei.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     db_iframe.hide();
}
</script>


<?
if(isset($ordem)){
  echo "<script>
                   js_emite();
       </script>";  
}
?>