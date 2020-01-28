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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
require_once("libs/db_libpostgres.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);

$clpostgresqlutils = new PostgreSQLUtils;
$clrotulo          = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

$instit = db_getsession("DB_instit");
if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox(_M('tributario.notificacoes.cai2_emitenotif001.problem_indices_debitos'));
  $db_botao = false; 
  $db_opcao = 3;
} else {
  
  $db_botao = true;
  $db_opcao = 4;
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
      if(F.options[x].valor == valor){
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
function js_verifica(){
    var val1 = new Number(document.form1.k60_codigo.value);
    if( val1.valueOf() < 1 ) {
        
       alert(_M('tributario.notificacoes.cai2_emitenotif001.selecione_lista'));
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }
    return true;
}

function js_emiteseed(){

    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_emitenotif001.selecione_lista'));
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }

   var H = document.getElementById("campos").options;
   if(H.length > 0){
      campo = 'campo=';
      virgula = '';
      for(var i = 0;i < H.length;i++) {
         campo += virgula+H[i].value;
         virgula = '-';
      }
   }else{
      campo = '';
   }
						      

  
  jan = window.open('cai2_emitenotif004.php?lista='+document.form1.k60_codigo.value+
                    '&'+campo+
                    '&tipo='+document.form1.tipo.value,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_emitear(){

    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_emitenotif001.selecione_lista'));
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }

   var H = document.getElementById("campos").options;
   if(H.length > 0){
      campo = 'campo=';
      virgula = '';
      for(var i = 0;i < H.length;i++) {
         campo += virgula+H[i].value;
         virgula = '-';
      }
   }else{
      campo = '';
   }
  
  jan = window.open('cai2_emitenotif005.php?lista='+document.form1.k60_codigo.value+
                    '&'+campo+
                    '&tipo='+document.form1.tipo.value+
                    '&intervalo='+document.form1.intervalo.value+
                    '&fonte='+document.form1.fonte.value+
                    '&inicio='+document.form1.DBtxt10.value+
                    '&fim='+document.form1.DBtxt11.value+
                    '&ordem='+document.form1.ordem.value+
                    '&tratamento='+document.form1.tratamento.value+
                    '&imprimirmesmoembranco='+document.form1.imprimirmesmoembranco.value+
                    '&datavenc='+document.form1.datavenc_ano.value+
                    '-'+document.form1.datavenc_mes.value+
                    '-'+document.form1.datavenc_dia.value,
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}

function js_emite(tiporel){

    var valini = new Number(document.form1.DBtxt10.value);
    var valfin = new Number(document.form1.DBtxt11.value);
    if(valfin.valueOf() < valini.valueOf() ){
       alert(_M('tributario.notificacoes.cai2_emitenotif001.numero_inicial_maior_numero_final'));
       return false;
    }
    var val1 = new Number(document.form1.k60_codigo.value);
    if(val1.valueOf() < 1){
       alert(_M('tributario.notificacoes.cai2_emitenotif001.selecione_lista'));
       return false;
    }
    var F = document.getElementById("campos").options;
    for(var i = 0;i < F.length;i++) {
      F[i].selected = true;
    }

   var H = document.getElementById("campos").options;
   if(H.length > 0){
      campo = 'campo=';
      virgula = '';
      for(var i = 0;i < H.length;i++) {
         campo += virgula+H[i].value;
         virgula = '-';
      }
   }else{
      campo = '';
   }
  
  var sQuery  = 'intervalo='+document.form1.intervalo.value;
  	  sQuery += '&inicio='+document.form1.DBtxt10.value;
  	  sQuery +=	'&fim='+document.form1.DBtxt11.value;
  	  sQuery +=	'&ordem='+document.form1.ordem.value;
  	  sQuery +=	'&fonte='+document.form1.fonte.value;
  	  sQuery +=	'&tiporel='+tiporel;
  	  sQuery +=	'&lista='+document.form1.k60_codigo.value;
  	  sQuery +=	'&'+campo;
  	  sQuery +=	'&tipo='+document.form1.tipo.value;
	    sQuery +=	'&tratamento='+document.form1.tratamento.value;
  	  sQuery += '&imprimirmesmoembranco='+document.form1.imprimirmesmoembranco.value;
  	  sQuery +=	'&datavenc='+document.form1.datavenc_ano.value+
  	            '-'+document.form1.datavenc_mes.value+'-'+document.form1.datavenc_dia.value;
      sQuery +=	'&imprimirtimbre='+document.form1.imprimirtimbre.value;
  
 	 jan = window.open('cai2_emitenotif002.php?'+sQuery,
 	                   '',
 	                   'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	 jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="a=1" bgcolor="#cccccc">
  
<form class="container" name="form1" method="post" action="" >
  <fieldset>
    <legend>Relatórios - Emissão de Notificações - Notificação</legend>
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
        <td nowrap title="Ordem para a emissão da lista" >
          Ordem:
        </td>
        <td>
          <?
            $xx = array("a"=>"Alfabética",
          	 	 	        "n"=>"Numérica",
          			        "t"=>'Notificação',
	 	    			          "e"=>"Endereço de Entrega",
           	 	    	    "c"=>"Cidade/CEP" );
			db_select('ordem',$xx,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap >
          Tratamento endereco segunda pagina: 
        </td>
        <td>
          <?
	        $xxx = array();
	        if ( isset($k60_codigo) && trim($k60_codigo) != "" ) {
	        	
	          $sSqlLista  = " select k60_tipo					          ";
	          $sSqlLista .= "   from lista					            ";
	          $sSqlLista .= "  where k60_codigo = {$k60_codigo} ";
	        
	          $rsConsultaLista = db_query($sSqlLista);
	          $iLinhasConsulta = pg_num_rows($rsConsultaLista);
	        
	          $oLista = db_utils::fieldsMemory($rsConsultaLista,0);
	        
	          $xxx["1"] = "Sempre do CGM";
	             
	          if ( $oLista->k60_tipo == "M") {
	            $sqlordendent    = "select defcampo, defdescr from db_syscampodef where codcam = 9856";
	            $resultordendent = db_query($sqlordendent) or die($sqlordendent);
	            for ($xord=0; $xord < pg_numrows($resultordendent); $xord++) {
	              db_fieldsmemory($resultordendent, $xord);
	              $defcampo+=10;
	              $xxx["$defcampo"] = "$defdescr";
	            }
	          }
	        }
	        db_select('tratamento',$xxx,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Utilizar end do CGM quando estiver em branco" >
          Utilizar end do CGM quando estiver em branco:
        </td>
        <td>
          <?
            $xx = array("n"=>"Não","s"=>'Sim');
            db_select('imprimirmesmoembranco',$xx,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Escolha o intervalo das notificações a serem impressas ou deixe em branco para todas."> 
          Intervalo:
        </td>
        <td>
          <?
	        $xy = array("q"=>"Quantidade","n"=>'Notificação');
			db_select('intervalo',$xy,true,$db_opcao,"");
	      ?>
	      &nbsp;<strong>De</strong>&nbsp;
          <?
            db_input('DBtxt10',8,'',true,'text',$db_opcao,"");
          ?>
          &nbsp;<strong>até</strong>&nbsp;
          <?
            $DBtxt11 = 0;
            db_input('DBtxt11',8,'',true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="Emissão do Timbre" >
          Emissão do Timbre:
        </td>
        <td>
          <?
            //(Emitir Ambos/Somente Interno/Somente Externo/Sem Timbre)
            $timbre = array("1"=>"Emitir Ambos","2"=>"Somente Interno","3"=>"Somente Externo","4"=>"Sem Timbre");
            db_select('imprimirtimbre',$timbre,true,$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
		<td nowrap title="Data para vencimento do recibo">
		  Data para vencimento do recibo:
		<td>
		  <?
      		db_inputdata('datavenc', @$data_dia, @$data_mes, @$data_ano, true, 'text', $db_opcao)
		  ?>
		</td>
	  </tr>
      <tr>
     	<td nowrap title="<?=@$Tk22_exerc?>">
	      Tamanho da fonte do texto:
	  	</td>
	  	<td>
		  <?
			$fonte=10;
			db_input('fonte', 10, "Tamanho da fonte do texto", true, 'text', @$db_opcao);
		  ?>
    	</td>
   	  </tr>
      <tr>
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
	</table>
    <fieldset class="separator">
      <Legend>Selecione as Notificações</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
            <?
              db_ancora('Nº da Notificação :',"js_pesquisadb02_idparag(true);",$db_opcao);
            ?>
            <?
              db_input('codigo',8,'',true,'text',$db_opcao," onchange='js_pesquisadb02_idparag(false);'");
              db_input('descr',25,'',true,'text',$db_opcao,'');
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
                          inner join debitos b on a.k61_numpre = b.k22_numpre
                                                and k22_instit   = $instit
                          inner join cgm on z01_numcgm = b.k22_numcgm
                         where k61_codigo = $k60_codigo and matric = $codigo
                         group by matric,numcgm,z01_nome";
				  die($sql);
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
          <td align="center" valign="middle" width="9%"> 
 	    	<img style="cursor:hand" onClick="js_sobe();return false" src="imagens/seta_up.gif" width="20" height="20" border="0">
            <br>
            <br>
            <img style="cursor:hand" onClick="js_desce()" src="imagens/seta_down.gif" width="20" height="20" border="0">
            <br>
            <br>
	      	<img style="cursor:hand" onClick="js_excluir()" src="imagens/bt_excluir.gif" width="20" height="20" border="0"> 
	   	  </td>
        </tr>
      </table>
    </fieldset>
	</fieldset>
    <input name="db_opcao"  type="button" id="db_opcao" value="Imprimir Notificação" onClick="js_emite(1);"
           <?=($db_botao ? '' : 'disabled')?>>
    <input name="db_opcao3" type="button" id="db_opcao" value="Aviso Débito" onClick="js_emite(3);"
           <?=($db_botao ? '' : 'disabled')?>>
    <input name="db_opcao1" type="button" id="db_opcao" value="Lista" onClick="js_emite(2);"
           <?=($db_botao ? '' : 'disabled')?>>  
</form>

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
    db_iframe.jan.location.href = 'cai2_emitenotif0033.php?lista='+document.form1.k60_codigo.value+'&funcao_js=parent.js_mostradb_paragrafo1|0|z01_nome';
    db_iframe.mostraMsg();
    db_iframe.show();
    db_iframe.focus();
  }else{
    db_iframe.jan.location.href = 'cai2_emitenotif0033.php?lista='+document.form1.k60_codigo.value+'&pesquisa_chave='+document.form1.codigo.value+'&funcao_js=parent.js_mostradb_paragrafo';
   
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
  } else {
    document.form1.submit();
  }
  
}
function js_mostralista1(chave1,chave2){

     document.form1.k60_codigo.value = chave1;
     document.form1.k60_descr.value = chave2;
     db_iframe.hide();
     document.form1.submit();
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
$("ordem").setAttribute("rel","ignore-css");
$("ordem").addClassName("field-size4");
$("imprimirmesmoembranco").setAttribute("rel","ignore-css");
$("imprimirmesmoembranco").addClassName("field-size2");
$("intervalo").setAttribute("rel","ignore-css");
$("intervalo").addClassName("field-size4");
$("DBtxt10").addClassName("field-size2");
$("DBtxt11").addClassName("field-size2");
$("imprimirtimbre").setAttribute("rel","ignore-css");
$("imprimirtimbre").addClassName("field-size4");
$("datavenc").addClassName("field-size2");
$("fonte").addClassName("field-size2");

$("codigo").addClassName("field-size2");
$("descr").style.width = "61%";
$("campos").setAttribute("rel","ignore-css");
$("campos").style.width = "100%";

</script>