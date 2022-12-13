<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
include("classes/db_notificacao_classe.php");
include("classes/db_notidebitos_classe.php");
include("classes/db_notimatric_classe.php");
include("classes/db_notiinscr_classe.php");
include("classes/db_notinumcgm_classe.php");
include("classes/db_notiusu_classe.php");
include("classes/db_lista_classe.php");
include("classes/db_listadeb_classe.php");
include("classes/db_listanotifica_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

$clpostgresqlutils = new PostgreSQLUtils;
$clnotificacao     = new cl_notificacao;
$clnotidebitos     = new cl_notidebitos;
$clnotimatric      = new cl_notimatric;
$clnotiinscr       = new cl_notiinscr;
$clnotinumcgm      = new cl_notinumcgm;
$clnotiusu         = new cl_notiusu;
$cllista           = new cl_lista;
$cllistadeb        = new cl_listadeb;
$cllistanotifica   = new cl_listanotifica;

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox(_M('tributario.notificacoes.cai2_geranotif001.problema_indices_debitos'));
  $db_botao = false;
  $db_opcao = 3;
} else {

  $db_botao = true;
  $db_opcao = 1;
}

$instit = db_getsession("DB_instit");
$clnotificacao ->k50_instit = $instit;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>
function termo(qual,total){
  document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
}

function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.descr.value;
  var valor=document.form1.codigo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor && F.options[x].text == texto ){

        testa = true;
	    break;
      }
    }
    if(testa == false){
      texto = valor+' - '+texto;
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    }
 }
   texto=document.form1.descr.value="";
   valor=document.form1.codigo.value="";
   document.form1.lanca.onclick = '';
}
function js_verifica(){
    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_geranotif001.selecione_lista'));
       return false;
    }

    var val2 = new Number(document.form1.k51_procede.value);
    if(val2.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_geranotif001.selecione_procedencia'));
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
    return true;
}

function js_emite(){

  var texto=document.form1.descr.value;
  var valor=document.form1.codigo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
	break;
      }
    }
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    }
 }

 texto=document.form1.descr.value="";
 valor=document.form1.codigo.value="";
 document.form1.lanca.onclick = '';

}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc">


    <form class="container" name="form1" method="post" action="" onsubmit="return js_verifica();" >
    <fieldset>
    <legend>Procedimentos - Notificações</legend>
    <table class="form-container">
      <tr>
        <td nowrap title="<?=@$Tk60_codigo?>" >
          <?
	          db_ancora(@$Lk60_codigo,"js_pesquisalista(true);",$db_opcao)
          ?>
        </td>
        <td>
          <?
	          db_input('k60_codigo',10,$Ik60_codigo,true,'text',$db_opcao,"onchange='js_pesquisalista(false);'");
            db_input('k60_descr',40,$Ik60_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tk51_procede?>" >
          <?
            db_ancora(@$Lk51_procede,"js_pesquisanotitipo(true);",$db_opcao)
          ?>
        </td>
        <td>
          <?
            db_input('k51_procede',10,$Ik51_procede,true,'text',$db_opcao,"onchange='js_pesquisanotitipo(false);'");
            db_input('k51_descr',40,$Ik51_descr,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr >
        <td>
          Opção de Seleção :
        </td>
        <td>
         <?
           $x = array("2"=>"Somente Selecionados","3"=>"Menos os Selecionados");
           db_select('tipo',$x,true,$db_opcao);
         ?>
        </td>
      </tr>
	  <tr>
		<td colspan="2">
      	  <fieldset class="separator">
      			<Legend>Selecione os Contribuintes</legend>
      			<table class="form-container">
         		  <tr>
           			<td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
		              <?
		                db_ancora('Contribuinte:',"js_pesquisadb02_idparag(true);",$db_opcao);
		                db_input('codigo',10,'',true,'text',$db_opcao," onchange='js_pesquisadb02_idparag(false);'");
		                db_input('descr',25,'',true,'text',3,'');
		              ?>
	           		  <input name="lanca" type="button" value="Lançar" >
      				</td>
	 			  </tr>
   				  <tr>
	    			<td>
              		  <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              			<?
              			  if(isset($chavepesquisa)){
	         				$sql = "select matric as codigo,
                               						 numcgm,
                                					 z01_nome as descr,
                                					 sum(valor_vencidos)
                         			from listadeb a
                              		inner join devedores b on a.k61_numpre = b.numpre
                              		inner join cgm on z01_numcgm = b.numcgm
                         			where k61_codigo = 3 and matric = $codigo
                         			group by matric,numcgm,z01_nome";
	         				$resulta = db_query($sql);
		 					if(pg_numrows($resulta)!=0){
                      		  $numrows = pg_numrows($resulta);
		    					for($i = 0;$i < $numrows;$i++) {
		      					  db_fieldsmemory($resulta,$i);
                      			  echo "<option value=\"$codigo \">$descr</option>";
                   				}
		 					}
             		 	  }
              			?>
            	 	  </select>
	   				</td>
            		<td align="center" valign="middle" width="11%">
             <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
              <br/><br/>
             <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
              <br/><br/>
             <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" />
	   				</td>
    			  </tr>
      			</table>
      	 	  </fieldset>
    		</td>
  		  </tr>
	</table>
	</fieldset>
	<input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50>
	<br><br>
  <input name="db_opcao" type="submit" id="db_opcao"
                value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>"
                <?=($db_botao==false?"disabled":"")?> >
  </form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


function js_pesquisadb02_idparag(mostra){
  if( document.getElementById('k60_codigo').value !='' ){
		  document.form1.lanca.onclick = "";
		  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
		  if(mostra==true){
		    db_iframe.jan.location.href = 'cai2_geranotif003.php?lista='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostradb_paragrafo1|0|2';
		    db_iframe.mostraMsg();
		    db_iframe.show();
		    db_iframe.focus();
		  }else{
		    db_iframe.jan.location.href = 'cai2_geranotif003.php?lista='+document.form1.k60_codigo.value+'&pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostradb_paragrafo';
		  }
	} else {
	   alert(_M('tributario.notificacoes.cai2_geranotif001.selecione_lista'));
	   document.getElementById('codigo').value = '';
	   document.getElementById('k60_codigo').focus();
	}
}

function js_mostradb_paragrafo(chave,erro){
  document.form1.descr.value = chave;
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

if ( (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Incluir" ) {

	$xcampo = '';

    if ( isset($campos) ) {

	  $xcampo = '(';
      $tamanho=sizeof($campos);
      $virgula = '';

	  for ($i=0; $i < $tamanho; $i++) {
        $xcampo .= $virgula.$campos[$i];
        $virgula = " , ";
      }
      $xcampo .= ')';

    }

    $erro1 = false;

	$resultlista = $cllista->sql_record($cllista->sql_query(""," * ","","k60_codigo = $k60_codigo and k60_instit = $instit"));
    db_fieldsmemory($resultlista,0);

    $database = $k60_datadeb;
    $xxmatric = '';

	if ($k60_tipo == 'M') {

	  if ($xcampo != '' ) {

		if ($tipo == 2){
	      $xxmatric = ' and k22_matric in '.$xcampo;
	    }else{
	      $xxmatric = ' and k22_matric not in '.$xcampo;
	    }
	  }

      $xmatric = ' k22_matric ';

    } else if ($k60_tipo == 'I') {

       if ($xcampo != '' ) {

		 if ($tipo == 2){
	       $xxmatric = ' and k22_inscr in '.$xcampo;
	     }else{
	       $xxmatric = ' and k22_inscr not in '.$xcampo;
	     }
	   }

	   $xmatric = ' k22_inscr ';

    } else {

	   if ($xcampo != '' ) {

		 if ($tipo == 2){
	       $xxmatric = ' and k22_numcgm in '.$xcampo;
	     }else{
	       $xxmatric = ' and k22_numcgm not in '.$xcampo;
	     }
	   }

	   $xmatric = ' k22_numcgm ';

    }

    $sqlmatricula  = "select distinct $xmatric as codigo                                       ";
    $sqlmatricula .= "                from listadeb a                                          ";
	$sqlmatricula .= "               left  join listanotifica on k61_numpre   =   k63_numpre   ";
	$sqlmatricula .= "                                       and k63_codigo   =   $k60_codigo  ";
    $sqlmatricula .= " 		        inner join debitos b     on b.k22_numpre =   a.k61_numpre  ";
	$sqlmatricula .= "	                                    and b.k22_data   =   '$database'   ";
    $sqlmatricula .= "                                       and b.k22_instit   = $instit      ";
	$sqlmatricula .= "				$xxmatric                                                  ";
	$sqlmatricula .= "     where k61_codigo = $k60_codigo                                      ";
	$sqlmatricula .= "       and k63_numpre is null                                            ";
    $sqlmatricula .= "     group by $xmatric ";
	$sqlmatricula .= "     order by $xmatric";


    $resultmatric = db_query($sqlmatricula) or die($sqlmatricula);
    if (pg_numrows($resultmatric) == 0){

      $sMsg = _M('tributario.notificacoes.cai2_geranotif001.nao_existem_contribuintes_nao_notificados');
      db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
    }

    $clnotificacao->k50_dtemite = date('Y-m-d');
    $clnotificacao->k50_procede = $k51_procede;
    $clnotificacao->k50_obs     = $k60_descr;

    db_inicio_transacao();

    $totalreg = pg_numrows($resultmatric);

    for($xx = 0;$xx < $totalreg;$xx++) {

       echo "<script>termo($xx,$totalreg);</script>";
       flush();

       db_fieldsmemory($resultmatric,$xx);

       if ( $codigo == "" || $codigo == 0 ) {
	     continue;
	   }

       //inclui a notificacao
       $clnotificacao->incluir('');
       if ( $clnotificacao->erro_status !="1" ) {
         $erro1 = true;
         $clnotificacao->erro(true,false);
         echo ' nao incluiu notificacao'; exit;
       }

       $clnotiusu->k52_id_usuario = db_getsession("DB_id_usuario");
       $clnotiusu->k52_data       = date('Y-m-d');
       $clnotiusu->k52_hora       = date('H:i');
       $clnotiusu->incluir($clnotificacao->k50_notifica);
       if ( $clnotiusu->erro_status !="1" ) {
         $erro1 = true;
         $clnotiusu->erro(true,false);
       }

       if ($k60_tipo == 'M'){

		 $clnotimatric->incluir($clnotificacao->k50_notifica,$codigo);
         if ($clnotimatric->erro_status !="1") {
           $erro1 = true;
           $clnotimatric->erro(true,false);
         }

       }elseif($k60_tipo == 'I'){

		 $clnotiinscr->incluir($clnotificacao->k50_notifica,$codigo);
         if ($clnotiinscr->erro_status !="1") {
           $erro1 = true;
           $clnotiinscr->erro(true,false);
	     }

       }elseif($k60_tipo == 'N' or $k60_tipo == 'C'){

		 $clnotinumcgm->incluir($clnotificacao->k50_notifica,$codigo);
         if ($clnotinumcgm->erro_status !="1") {
           $erro1 = true;
           $clnotinumcgm->erro(true,false);
	     }

       }

       $sqlnotidebitos  = "insert into notidebitos                                              ";
       $sqlnotidebitos .= "		       select distinct ".$clnotificacao->k50_notifica.",        ";
	   $sqlnotidebitos .= "                             k61_numpre,                             ";
	   $sqlnotidebitos .= "                             k61_numpar                              ";
	   $sqlnotidebitos .= "               from ( select k61_numpre,                             ";
       $sqlnotidebitos .= "		                       k61_numpar                               ";
	   $sqlnotidebitos .= "                        from listadeb                                ";
	   $sqlnotidebitos .= "				         inner join debitos on k22_data   = '$database' ";
       $sqlnotidebitos .= "                                         and k22_numpre = k61_numpre ";
       $sqlnotidebitos .= "                                         and $xmatric   = $codigo    ";
       $sqlnotidebitos .= "                                         and k22_instit = $instit    ";
	   $sqlnotidebitos .= "	   	                 where listadeb.k61_codigo        = $k60_codigo ";
	   $sqlnotidebitos .= "		                 order by k61_codigo) as x                      ";

       $resultnotidebitos = db_query($sqlnotidebitos) or die($sqlnotidebitos);


       $sqllistanotifica  = "insert into listanotifica                                ";
       $sqllistanotifica .= "select distinct $k60_codigo,                             ";
	   $sqllistanotifica .= "                k61_numpre,                              ";
       $sqllistanotifica .= "				 $clnotificacao->k50_notifica             ";
	   $sqllistanotifica .=	"  from ( select k61_numpre,                              ";
	   $sqllistanotifica .= "				 k61_numpar                               ";
	   $sqllistanotifica .= "		    from listadeb                                 ";
	   $sqllistanotifica .= "		   inner join debitos on k22_data   = '$database' ";
       $sqllistanotifica .= "                            and k22_numpre = k61_numpre  ";
       $sqllistanotifica .= "                            and $xmatric   = $codigo     ";
       $sqllistanotifica .= "                            and k22_instit = $instit     ";
	   $sqllistanotifica .= "	   	   where listadeb.k61_codigo        = $k60_codigo ";
	   $sqllistanotifica .= "		   order by k61_codigo) as x                      ";

       $resultlistanotifica = db_query($sqllistanotifica) or die($sqllistanotifica);

    }

    db_fim_transacao($erro1);

    if ($erro1 == false){
      db_msgbox(_M('tributario.notificacoes.cai2_geranotif001.processamento_concluido'));
    }

}

if ( isset($db_opcao)) {
  echo "<script> js_emite(); </script>";
}

$func_iframe = new janela('db_iframe','');
$func_iframe->posX           = 1;
$func_iframe->posY           = 20;
$func_iframe->largura        = 780;
$func_iframe->altura         = 430;
$func_iframe->titulo         = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
<script>

$("k60_codigo").addClassName("field-size2");
$("k60_descr").style.width = "79%";
$("k51_procede").addClassName("field-size2");
$("k51_descr").style.width = "79%";
$("codigo").addClassName("field-size2");
$("descr").addClassName("field-size7");
$("campos").setAttribute("rel","ignore-css");
$("campos").style.width = "100%";
$("termometro").setAttribute("rel","ignore-css");
$("termometro").style.width = "100%";

</script>