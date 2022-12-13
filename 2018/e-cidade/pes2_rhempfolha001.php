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
require_once("classes/db_gerfcom_classe.php");

$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);

?>

<html>
<head>

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
  
  qry = "";
  
  if (document.form1.arquivo.value == 'c') {
    
    if(document.form1.rh40_sequencia){
      qry = "&rh40_sequencia="+document.form1.rh40_sequencia.value;
    } else {
      alert('Sem complementar encerrada para o período informado.');
      return false;
    }
    
  }
  
  jan = window.open('pes2_rhempfolha002.php?mostra='+document.form1.mostra.value+'&tipo='+document.form1.tipo.value+'&ponto='+document.form1.arquivo.value+'&ano='+document.form1.DBtxt23.value+'&mes='+document.form1.DBtxt25.value+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
  
}

</script>
  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor="#CCCCCC">

<form name="form1" method="post" action="" onsubmit="return js_verifica();">

<fieldset style="width: 300px;  margin: 25px auto 10px;">
<legend><strong>Relatório de Empenho da Folha</strong></legend>

<table  align="center">
  <tr >
    <td align="left" nowrap title="Digite o Ano / Mes de competência" >
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
      <b>Arquivo:</b>
    </td>
    <td>
    <?
      $x = array("s"=>"Salário","c"=>"Complementar","d"=>"13o. Salário","r"=>"Rescisão","a"=>"Adiantamento","f"=>"Férias");
      db_select('arquivo',$x,true,4,"onchange='document.form1.submit();'");
    ?>
    </td>
  </tr>
  
  <?
  if(isset($arquivo) && $arquivo == "c") {

    $sSqlGerfcom   = $clgerfcom->sql_query_file($DBtxt23,
                                                $DBtxt25,
                                                null,
                                                null,
                                                "distinct r48_semest as rh40_sequencia", 
                                                "rh40_sequencia", "
                                                r48_semest <> 0");

    $result_semest = $clgerfcom->sql_record($sSqlGerfcom);
       
    if($clgerfcom->numrows > 0) {

      echo "<tr>                                          ";
      echo "  <td align='left' title='Nro. Complementar'> ";
      echo "    <strong>Nro. Complementar:</strong>       ";
      echo "  </td>                                       ";
      echo "  <td>                                        ";
      echo "    <select name='rh40_sequencia'>            ";
      echo "      <option value = ''>Todos</option>       ";

      for($i=0; $i<$clgerfcom->numrows; $i++){
        db_fieldsmemory($result_semest, $i);
        echo "<option value = '$rh40_sequencia'>$rh40_sequencia</option>";
      }

      echo "  </td> ";
      echo "</tr>   ";

    } else{

      echo "<tr>                                                                            "; 
      echo "  <td colspan='2' align='center'>                                               ";
      echo "  <font color='red'>Sem complementar encerrada para o período informado.</font> ";
      echo "  </td>                                                                         ";
      echo "</tr>                                                                           ";
      
    }
  }
  ?>
  <tr>
    <td>
       <b>Tipo</b>
    </td>
    <td >
    <?
      $x = array("n"=>"Salário","p"=>"Previdência","f"=>"FGTS");
      db_select('tipo',$x,true,4,"");
    ?>

  	</td>
  </tr>
	<tr>
		<td>
		  <b>Totalização</b>
		</td>
		<td>
		<?
		  $mostra = "s";
		  $x = array("a"=>"Analítico","s"=>"Sintético");
		  db_select('mostra',$x,true,4,"");
		?>
		</td>
  </tr>
</table>

</fieldset>

<center>
  <input name="emite" id="emite" type="button" value="Processar" onclick="js_emite();">
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