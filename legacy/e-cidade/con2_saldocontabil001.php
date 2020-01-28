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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('o15_codigo');
$clrotulo->label('o15_descr');
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
   var v = '';
   var chk = '';
   var tipo = document.form1.formato.value;   

   for (i=0;i<document.form2.elements.length;i++){
      if (document.form2.elements[i].checked == true){
         chk = chk+v+document.form2.elements[i].value;
         v = '-';
         a += 1;
      }
   } 

   if (chk == '') {
     alert("Nenhum recurso selecionado.");
     return false;
   }
   
   var sQueryString = '&data='+document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value+'&recid='+chk+'&formato='+document.form1.formato.value+'&orientacao='+document.form1.orientacao.value;

   if (tipo == 't') {
     js_OpenJanelaIframe('top.corpo','db_iframe_geratxt','con2_saldocontabil002.php?'+sQueryString,'Aguarde gerando arquivo',true); 
   } else {
     jan = window.open('con2_saldocontabil002.php?'+sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
   }
}
var itens = new Array();
function md(){
     el = document.form2;
     if (document.form1.mtodos.checked==true){
        for (i = 0;i < el.length;i++){
           if (el.elements[i].checked == false){
              el.elements[i].checked = true;
              itens[i] = el.elements[i].parentNode.parentNode.style.background;
               marca(el.elements[i],'#eeeeee');
           }   
     
        }
     }else{
        for (i = 0;i < el.length;i++){
           if (el.elements[i].checked == true){
              el.elements[i].checked = false;
              //document.form1.btncadastrar.disabled=true;
              marca(el.elements[i],itens[i]);
           }   
        }
     
     }
}      

function marca(item,cor){
    if (item.checked == true){
       item.parentNode.parentNode.style.background ='#D7E4EA';
    }else{
      item.parentNode.parentNode.style.background = cor;
    }
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
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <!--<td align="left" nowrap title="<?=@$To15_codigo?>" >
          <?
            // db_ancora(@$Lo15_codigo,"js_pesquisarecurso(true);",4)
          ?>
        </td>-->
        <td>
          <?
          //  db_input('o15_codigo',4,$Io15_codigo,true,'text',4,"onchange='js_pesquisarecurso(false);'")
          ?>
          <?
          //  db_input('o15_descr',40,$Io15_descr,true,'text',3,'')
          ?>

        </td>
      </tr>
      <tr>
        <td align="left" ><strong>Data Final :</strong></td>
        <td>
        <?
         $datausu = date("Y/m/d",db_getsession("DB_datausu"));
         $dataf_ano = substr($datausu,0,4);
         $dataf_mes = substr($datausu,5,2);
         $dataf_dia = substr($datausu,8,2);

        ?>
        <?=db_inputdata('datai',$dataf_dia,$dataf_mes,$dataf_ano,true,'text',4)?>
        </td>
      </tr>

      <tr>
        <td align="left"><strong>Formato:</strong></td>
	      <td >
				<?
					$x = array ('p' => 'PDF', 't' => 'TXT');
					db_select('formato', $x, true, 2, "");
				?>
				</td>
      </tr>
      <tr>
      	<td align="left"><strong>Orientação:</strong></td>
				<td >
				<?
					$x = array ('r' => 'SINTÉTICA (RETRATO)', 'p' => 'ANALÍTICA (PAISAGEM)');
					db_select('orientacao', $x, true, 2, "");
				?>
				</td>
      </tr>


      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>
      <tr>
      <td colspan='4'>
       <?
          $sqlrec  = "   select distinct o15_codigo,o15_descr                                                              ";
          $sqlrec .= "     from orctiporec                                                                                 ";
          $sqlrec .= "    where o15_datalimite is null or o15_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."' ";
          $sqlrec .= " order by o15_codigo                                                                                 ";

          $rsrec   = pg_query($sqlrec);
       ?>
      <fieldset><legend><b> Escolha os recursos</b></legend>
      <table id='tbgrid' width='500' cellspacing=0>
      <thead id='tbhead'>
       <tr><th bgcolor="#eeeee2" style="border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;text-align:left">
       <input type="checkbox" onclick='md();' name='mtodos' title="Marcar/Desmarcar todos">
       </th>
       <th bgcolor="#eeeee2" style="border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;">Código</th>
       <th bgcolor="#eeeee2" style="border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;">Recurso</th>
       </tr></thead>
        <tbody id='tbcorpo' style="height:35ex;overflow:scroll;"> 
       </form>
      <form name="form2" method="post">
        <?
         while ($lnrec = pg_fetch_array($rsrec)){
             if ($i % 2 == 0){
                $cor = '#EEEEEE';
             }else{
               $cor = '#FFFFFF';
            }
            echo "<tr style='background-color:$cor' class='trlinha'>\n";
            echo "<td style='border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;'  width='15'><input type='checkbox'  name='chkrec[]' value='".$lnrec["o15_codigo"].
            "'onClick=\"marca(this,'$cor')\"></td>\n";
           echo "<td style='border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;'>".$lnrec["o15_codigo"]."</td>\n";
           echo "<td style='border-bottom:1px solid threedshadow;border-right:1px solid threedshadow;'>".$lnrec["o15_descr"]."</td>\n";
           echo "</tr>";
      $i++; 
   }
   
?>
   
     </tbody>
     </table>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisarecurso(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_orctiporec.php?funcao_js=parent.js_mostrarecurso1|o15_codigo|o15_descr';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostrarecurso';
     }
}
function js_mostrarecurso(chave,erro){
  document.form1.o15_descr.value = chave;
  if(erro==true){
     document.form1.o15_codigo.focus();
     document.form1.o15_codigo.value = '';
  }
}
function js_mostrarecurso1(chave1,chave2){
     document.form1.o15_codigo.value = chave1;
     document.form1.o15_descr.value = chave2;
     db_iframe.hide();
}
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