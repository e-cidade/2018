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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_gerfcom_classe.php");

db_postmemory($HTTP_POST_VARS);

$clgerfcom = new cl_gerfcom;
$clrotulo  = new rotulocampo;

$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('r48_semest');

?>
<html>
<head>
<?php 
  db_app::load('scripts.js, estilos.css');
?>
<script>
function js_verifica(){
  
  var iAnoInicial = new Number(document.form1.datai_ano.value);
  var iAnoFinal   = new Number(document.form1.dataf_ano.value);
  
  if(iAnoInicial.valueOf() > iAnoFinal.valueOf()){
    alert('Intervalo de data invalido. Velirique !.');
    return false;
  }
  
  return true;
  
}

function js_processar() {
  
  sQuery = "";

  if (document.form1.ponto.value == 'r48') {
    if(document.form1.r48_semest){
      sQuery = "&complementar="+document.form1.r48_semest.value;
    } else {
      alert('Sem complementar encerrada para o período.');
      return false;
    }
  }
  
  jan = window.open('pes2_resumoregimeempenhos002.php?' +
                    'ponto='+document.form1.ponto.value +
                    '&ano='+document.form1.DBtxt23.value+
                    '&mes='+document.form1.DBtxt25.value+
                    sQuery, 
                    '', 
                    'width='+(screen.availWidth-5)+', height='+(screen.availHeight-40)+', scrollbars=1, location=0');
  jan.moveTo(0,0);
  
}
</script> 
</head>
<body bgcolor="#cccccc">
  <form name="form1" method="post" action="" onsubmit="return js_verifica();">
    <fieldset style="width: 300px; margin: 20px auto 10px;">
      <legend><strong>Resumo por Regime</strong></legend>
      <form name="form1" method="post" action="" onsubmit="return js_verifica();">
      <table  align="center">
        <tr>
          <td title="Digite o Ano / Mes de competência" >
            <strong>Ano / Mês :&nbsp;&nbsp;</strong>
          </td>
          <td>
          <?
            $DBtxt23 = db_anofolha();
            db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'')
          ?>
          &nbsp;/&nbsp;
          <?
            $DBtxt25 = db_mesfolha();
            db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'')
          ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Ponto:</strong>
          </td>
          <td>
          <?
            $aTipoPonto = array("r14"=>"Salário",
                                "r48"=>"Complementar",
                                "r35"=>"13o. Salário",
                                "r20"=>"Rescisão",
                                "r22"=>"Adiantamento");
            db_select('ponto', $aTipoPonto, true, 4, "onchange='document.form1.submit();'");
          ?>
          </td>
        </tr>
        <?
          if (isset($ponto) && $ponto == "r48") {
          
            echo "<tr>                                                  ";

            $sSqlComplementares = $clgerfcom->sql_query_file($DBtxt23, 
                                                             $DBtxt25, 
                                                             null, 
                                                             null, 
                                                             "distinct r48_semest",
                                                             "r48_semest",
                                                             "r48_semest <> 0");

            $rsComplementares = $clgerfcom->sql_record($sSqlComplementares);
            
            if ($clgerfcom->numrows > 0) {
              echo "  <td><strong>Nro. Complementar:</strong></td>                                  ";
              echo "  <td>                                                                          ";
              echo "  <select name='r48_semest'>                                                    ";
              echo "    <option value = ''>Todos</option>                                           ";
              echo "                                                                                ";
              for($i = 0; $i<$clgerfcom->numrows; $i++){                                            
                db_fieldsmemory($rsComplementares, $i);                                             
                echo "  <option value = '$r48_semest'>$r48_semest</option>                          ";
              }                                                                                     
              echo "  </select>                                                                     ";
              echo "  </td>                                                                         ";
              
            } else {
              
              echo "<td colspan='2'><strong>Sem complementar encerrada para o período.</strong></td>";
              
            }
            
            echo "</tr>                                                                             ";
            
          }
        ?>
	  </table>
    
    </fieldset>
    
	  <center>
      <input name="processar" id="processar" type="button" value="Processar" onclick="js_processar();" >
    </center>
    
  </form>
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
</script>
<?
if(isset($ordem)){
  echo "<script>
       js_processar();
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