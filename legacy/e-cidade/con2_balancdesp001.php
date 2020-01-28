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
include("libs/db_liborcamento.php");
include("classes/db_orctiporec_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_abre(opcao){
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
     alert('Você não escolheu nenhuma Instituição. Verifique!');
     return false;
  }
	      
 if (document.form1.vernivel.value != '' && document.form1.vernivel.value != document.form1.nivel.value){
    if(confirm('Você já escolheu anteriormente dados do nível '+document.form1.vernivel.value+' , deseja altera-los?')==false) 
      return false
    else
      js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }else if(top.corpo.db_iframe_orgao != undefined){
//   alert('entrou');
   
   if(document.form1.nivel.value == document.form1.vernivel.value){
     db_iframe_orgao.show();
   }else{
     js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
   }
 }else{
   js_OpenJanelaIframe('','db_iframe_orgao','func_selorcdotacao.php?&nivel='+document.form1.nivel.value+'&db_selinstit='+document.form1.db_selinstit.value,'pesquisa',true);
 }
 
 
}


variavel = 1;
function js_emite(opcao,origem){
  sel_instit  = new Number(document.form1.db_selinstit.value);
  if(sel_instit == 0){
    alert('Você não escolheu nenhuma Instituição. Verifique!');
    return false;
  }
  if (opcao == 3){
     var data1 = new Date(document.form1.DBtxt21_ano.value,document.form1.DBtxt21_mes.value,document.form1.DBtxt21_dia.value,0,0,0);
     var data2 = new Date(document.form1.DBtxt22_ano.value,document.form1.DBtxt22_mes.value,document.form1.DBtxt22_dia.value,0,0,0);
     if(data1.valueOf() > data2.valueOf()){
       alert('Data inicial maior que data final. Verifique!');
       return false;
     }
     perini = document.form1.DBtxt21_ano.value+'-'+document.form1.DBtxt21_mes.value+'-'+document.form1.DBtxt21_dia.value;
     perfin = document.form1.DBtxt22_ano.value+'-'+document.form1.DBtxt22_mes.value+'-'+document.form1.DBtxt22_dia.value;;
  }else if (opcao == 2){
     if(document.form1.mesfin.value == 0){
       mesfinal = 12;
     }else if(document.form1.mesfin.value < 10){
       mesfinal = '0'+document.form1.mesfin.value;
     }else if(document.form1.mesfin.value == 'mes'){
       alert('Mês final do intervalo invalido.Verifique!');
       return false
     }else{
       mesfinal = document.form1.mesfin.value;
     }

     if(document.form1.mesini.value == 0){
       mesinicial = 12;
     }else if(document.form1.mesini.value < 10){
       mesinicial = '0'+document.form1.mesini.value;
     }else{
       mesinicial = document.form1.mesini.value;
     }
    
     perini = <?=db_getsession("DB_anousu")?>+'-'+mesinicial+'-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-'+mesfinal+'-01';
     opcao = 4;
  }else{
     perini = <?=db_getsession("DB_anousu")?>+'-01-01';
     perfin = <?=db_getsession("DB_anousu")?>+'-01-01';
  }
		      
  jan = window.open('con2_balancdesp002.php?&vernivel='+document.form1.vernivel.value+'&orgaos='+document.form1.orgaos.value+'&totaliza='+document.form1.totaliza.value+'&perfin='+perfin+'&perini='+perini+'&opcao='+opcao+'&origem='+origem+'&db_selinstit='+document.form1.db_selinstit.value+'&recurso='+document.form1.recurso.value+'&recursodescr='+document.form1.recursodescr.value+'&totaliza_atividade='+document.form1.totaliza_atividade.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
    <form name="form1" method="post" action="orc2_balancdesp002.php" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td align="center" colspan="3">
	<?
	db_selinstit('parent.js_limpa',300,100);
	?>
	</td>
      </tr>

      <tr>
        <td align="center" colspan="3">
	  <table>
      <tr>
        <td align="right" ><strong>Órgão/Unidade :</strong></td>
	<td align="left">
          <?
	   $xy = array('1A'=>'Órgão','2A'=>'Unidade');
	   db_select('nivel',$xy,true,2,"");
	  ?>
            <input  name="seleciona" id="seleciona" type="button" value="Seleciona" onclick="js_abre();">
	  </td>
        </td>
      </tr>
      <tr>
        <td  align="right"><strong>Totalização :  </strong></td>
	<td align="left">
          <?
           $x = array('A'=>'ANALÍTICO','S'=>'SINTÉTICO');
           db_select('totaliza',$x,true,2,"");
          ?>
        </td>
      </tr>
      <tr>
        <td  align="right"><strong>Totaliza Atividade :  </strong></td>
  <td align="left">
          <?
           $x = array('N'=>'Não','S'=>'SIM');
           db_select('totaliza_atividade',$x,true,2,"");
          ?>
        </td>
      </tr>


	      <tr>
        <td align="right" ><strong>Recurso:</strong></td>
	<td>
	<?
	   $dbwhere      = " o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
     $clorctiporec = new cl_orctiporec;
	   $res = $clorctiporec->sql_record($clorctiporec->sql_query(null,"*","o15_codigo",$dbwhere));
	   db_selectrecord("recurso",$res,true,2,"","","","0");
	?>
	</td>
      </tr>
						   
      
	  </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
       <?
        db_selorcbalanco();
       ?>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="orgaos" id="orgaos" type="hidden" value="" >
          <input  name="vernivel" id="vernivel" type="hidden" value="" >
        </td>
      </tr>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>