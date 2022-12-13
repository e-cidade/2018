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
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('k13_conta');
$clrotulo->label('k13_descr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');



?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  
    jan = window.open('cai2_emppago002.php?filtraemp='+document.form1.filtraemp.value+'&quebra='+document.form1.quebra.value+'&ordem='+document.form1.ordem.value+'&cod='+document.form1.k13_conta.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&z01_numcgm='+document.form1.z01_numcgm.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
    <form name="form1" method="post" action="">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
      <td nowrap title="<?=@$Tz01_numcgm?>" align='right'><b>
        <?
        db_ancora($Lz01_numcgm,"js_pesquisaz01_numcgm(true);",1);
        ?></b>
       </td>
       <td> 
        <?
        db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'")
          ?>
          <?
           db_input('z01_nome',30,$Iz01_nome,true,'text',3,'')
          ?>
      </td>
      </tr>
 
      <tr>
      <td nowrap title="<?=@$Tk13_conta?>" align='right'><b>
        <?
        db_ancora('Codigo da Conta:',"js_pesquisak13_conta(true);",1);
        ?></b>
       </td>
       <td> 
        <?
        db_input('k13_conta',5,$Ik13_conta,true,'text',1," onchange='js_pesquisak13_conta(false);'")
          ?>
          <?
           db_input('k13_descr',30,$Ik13_descr,true,'text',3,'')
          ?>
      </td>
      </tr>
      
	 <?
		$dtd = date("d",db_getsession("DB_datausu"));
		$dtm = date("m",db_getsession("DB_datausu"));
		$dta = date("Y",db_getsession("DB_datausu"));
         ?>		
         <tr>
	 <td><b>De:</b><?db_inputdata("data","$dtd","$dtm","$dta","true","text",2)      ?>   </td>
	 <td><b>Ate:</b>  <?db_inputdata("data1","$dtd","$dtm","$dta","true","text",2)      ?> </td>
	 </tr>
      <tr >
        <td align="right" nowrap title="Ordem para a emissão do relatório" ><strong>Ordem : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
          $xx = array("e"=>"Empenho","a"=>"Autenticação");
db_select('ordem',$xx,true,4,"");
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="" ><strong>Quebrar por Conta : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
          $xx = array("s"=>"Sim","n"=>"Não");
db_select('quebra',$xx,true,4,"");
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="" ><strong>Lista Empenhos : </strong>
        </td>
        <td align="left">&nbsp;&nbsp;&nbsp;
          <?
          $xx = array("0"=>"Geral","1"=>"Exercício","2"=>"Restos à Pagar");
db_select('filtraemp',$xx,true,4,"");
          ?>
        </td>
      </tr>

      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();" >
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
function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}
<?/*--------------------------------------------------*/?>
function js_pesquisak13_conta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
     if(document.form1.k13_conta.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.k13_conta.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k13_conta.focus(); 
    document.form1.k13_conta = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k13_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.kz01_numcgm.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_nome.hide();
}

<?/*----------------------------------------*/?>
</script>


<?
if(isset($ordem)){
  echo "<script>
       js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>