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
include("dbforms/db_classesgenericas.php");
include("classes/db_orcparamseq_classe.php");
include("classes/db_orcparamelemento_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clorcparamseq            = new cl_orcparamseq;
$clorcparamelemento       = new cl_orcparamelemento;
$cliframe_seleciona_plano = new cl_iframe_seleciona;

$clorcparamseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");

$instit = db_getsession("DB_instit");
$anousu = db_getsession("DB_anousu");
?>
<script>
function js_voltar(){
  parent.document.location.href="con4_parametrosrelatorioslegais001.php?c83_codrel=<?=$o69_codparamrel?>";
}
</script>
<?
if (isset ($atualizar) && $atualizar == "atualizar") {
	db_inicio_transacao();
	$erro = false;
	$msg = "";
	$clorcparamelemento->o44_instit = $instit;
	
    $clorcparamelemento->sql_record("select * from orcparamelemento 
                                         where o44_codparrel=$c83_codrel and 
				               o44_sequencia=$c69_codseq and 
					       o44_instit=$instit and
					       o44_anousu=$anousu and
					       (o44_exclusao ='f' or o44_exclusao is null) ");
	if ($clorcparamelemento->numrows > 0) {

		$clorcparamelemento->excluir(
		      null,
		      null,
		      null,
		      null, 
          null,
		      "o44_codparrel=$c83_codrel and 
		       o44_sequencia=$c69_codseq and 
		       o44_anousu   =$anousu     and
		       o44_instit=$instit and (o44_exclusao ='f' or o44_exclusao is null)");
		if ($clorcparamelemento->erro_status == 0) {
			$erro = true;
			$msg = $clorcparamelemento->erro_msg;
		}

	}
	$matriz = explode("#", $lista); //gera matriz com as chaves

	for ($i = 0; $i < sizeof($matriz); $i ++) {
		// o teste abaixo e necessario porque quando desmerca todos os itens na tela, o expode acima gera 1 vazio
		if ($matriz[$i] != "") {
			//db_msgbox($matriz[$i]);
      $result  = $clorcparamelemento->sql_record($clorcparamelemento->sql_query_file($anousu,$c83_codrel,$c69_codseq,$matriz[$i],$instit));
      $numrows = $clorcparamelemento->numrows; 
      if ($numrows == 0){
           $clorcparamelemento->o44_exclusao="false";
       		 $clorcparamelemento->incluir($anousu,$c83_codrel,$c69_codseq,$matriz[$i],$instit);
      		 if ($clorcparamelemento->erro_status == 0) {
				        $erro = true;
				        $msg = $clorcparamelemento->erro_msg;
			     }
      }
		}
	}
	db_fim_transacao($erro);
	if ($erro == true) {
		db_msgbox($msg);
	}

	echo "<script>
         js_voltar();
	       parent.js_refresh(); 
	      </script>";

}
// ---------------------------------------------------------------------------------


function espaco($estrutural=""){
    $espaco ="";
    if(substr($estrutural,1,14)     == '00000000000000'){
       $espaco="";    
    }elseif(substr($estrutural,2,13)== '0000000000000'){  
       $espaco="&nbsp;&nbsp;";    
    }elseif(substr($estrutural,3,12)== '000000000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,4,11) == '00000000000'){	
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,5,10) == '0000000000'){  
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,7,8)  == '00000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";   
    }elseif(substr($estrutural,9,6)  == '000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";   
    }elseif(substr($estrutural,11,4) == '0000'){ 	
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,12,3) == '000'){
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,13,2) == '00'){ 
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }elseif(substr($estrutural,14,1) == '0'){ 
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }

    
    return $espaco;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_filtrarContas(){
  document.form1.submit();  
}
function js_filtrarEstrut(){
  if (document.form1.ini_estrut.value != "" && 
      document.form1.fim_estrut.value != ""){
       document.form1.submit();
       document.form1.ini_estrut.value = "";
       document.form1.fim_estrut.value = "";
  } else {
       alert("Informe intervalo corretamente.");
       document.form1.ini_estrut.focus();
       document.form1.ini_estrut.select();
  }
}
function js_recarregar(grupo1,grupo2){
  document.form1.grupo1.value = grupo1;
  document.form1.grupo2.value = grupo2;

  document.form1.submit();  
}

function js_emite(){
  c69_codseq ="<?=$o69_codparamrel?>";
  obj = document.form1;
  
  jantes = window.open('con2_imprimeseqelemento002.php?c69_codseq='+c69_codseq,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jantes.moveTo(0,0);
}

function js_getChaves(){
   // usada no botão submit para capturar as chaves do iframe 
   // usar um campo hidden no form com o nome chaves
   lista                = "";
   sep                  = "";
   virgula              = "";  
   obj                  = plano.document.form1;   
   lista_con_analiticas = new String(document.form1.lista_contas_analiticas.value);
   lista_con_sinteticas = new String(document.form1.lista_contas_sinteticas.value);
   erro = false;
   tem_analitica = false;
   tem_sintetica = false;
   
   for(i=0;i < obj.length;i++){
     if (obj[i].type == 'checkbox'){         
      if (obj[i].checked == true){
        var selecionado = new String(obj[i].value);
	    lista = lista + sep + selecionado;
	    sep ='#';

        if (lista_con_analiticas.length > 0){
             var vetor_con_analiticas = lista_con_analiticas.split(",");
             var vetor_con_sinteticas = lista_con_sinteticas.split(",");

             if (js_search_in_array(vetor_con_analiticas,selecionado)==true){
                  tem_analitica = true;
             }

             if (js_search_in_array(vetor_con_sinteticas,selecionado)==true){
                  tem_sintetica = true;
             }

             if (tem_analitica == true && tem_sintetica == true){
                  alert("Sua seleção não será gravada porque causará diferenças no relatório.\n"+ 
                        "Você não deve selecionar contas analíticas e sintéticas ao mesmo tempo.\n"+ 
                        "Revise sua seleção.\n");
                  lista = "";
                  erro  = true;
                  break;
             }
        }
	  }
     } 
   }

   if (erro == false){
        var lista_selecionados = new String(document.form1.lista_selecionados.value);     
		  
        if (lista_selecionados.length > 0){
             lista_selecionados = lista;
        }
		
        document.form1.lista.value         = lista;   

        var op = document.createElement("input");
        op.setAttribute("type","hidden");
        op.setAttribute("name","atualizar");
        op.setAttribute("value","atualizar");
        document.form1.appendChild(op);       
   
        document.form1.submit();   
   }        
}  

function js_desmarcarTodos(){
   obj = plano.document.form1;   
   for(i=0;i < obj.length;i++){
     if (obj[i].type == 'checkbox'){         
          obj[i].checked = false;
     } 
   }
}  
</script>
</head>

<body>

<form name=form1 action=""  method="POST">

 <input type="hidden" name="lista" value="">
 <input type="hidden" name="lista_excluir" value="">
 <input type="hidden" name="c83_codrel" value="<?=$o69_codparamrel ?>">
 <input type="hidden" name="c69_codseq" value="<?=$o69_codseq ?>">
<center>
<table border="0">
 <tr>
  <td>
   <fieldset> 
<table border="0" align="center">
 <tr>
   <td colspan="2" align="center">
     <?
     
     $s = "select o69_descr 
              from orcparamseq 
	      where o69_codparamrel = $o69_codparamrel
	             and o69_codseq = $o69_codseq 
              ";
	$r = pg_exec($s);
	if (pg_numrows($r)>0){
            db_fieldsmemory($r,0);
	    echo  "<b>$o69_descr</b>";	
	}  
     ?>
   </td>
 </tr>
 <tr>
   <td colspan="2" nowrap align="center">
      <input type="button" value="Gravar Parametros" onclick="js_getChaves();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Desmarcar Todos" onclick="js_desmarcarTodos();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Filtrar estruturais" onClick="js_filtrarEstrut();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Imprimir" onclick="js_emite();">
      <input type="button" value="Voltar" onclick="js_voltar();">
   </td>
 </tr>
 <?
    if ($flag_permissao == true){
 ?>
 <tr>
   <td nowrap><b>Intervalo - Inicial:</b><input name="ini_estrut" type="text" size="15" maxlength="15">
    &nbsp;&nbsp;<b>Final:</b><input name="fim_estrut" type="text" size="15" maxlength="15"></td>
    <td nowrap>
    <?
      $matriz = array("T"=>"TODAS AS CONTAS","A"=>"SOMENTE CONTAS ANALITICAS","S"=>"SOMENTE CONTAS SINTETICAS");
      db_select("filtrar_contas",$matriz,true,4,"onChange='js_filtrarContas();'");
    ?>
    </td>
 </tr>
 <?
   }
 ?>
 <tr>
 <td colspan="2" nowrap align="center">
 <table border="0">
 <tr> 
   <td colspan="2" valign="middle" align="left" nowrap>

   <? 
      if (isset($grupo) && $grupo !='' && $grupo !='0') {   
           $grupo1 = $grupo;
           if ($grupo1 == 5){
                $grupo2 = 6;
           } else if ($grupo1 == 4){
                $grupo2 = 9;
           } else {
                $grupo2 = 0;
           }
      switch($grupo){
      	 case 1:
                  echo "<input id=ativo type=button value=ATIVO onclick=\"js_recarregar(1,0);\" >";
	                break;
         case 2:
                  echo "<input id=passivo type=button value=PASSIVO onclick=\"js_recarregar(2,0);\" >";
	                break;
         case 3:  echo " <input id=desp type=button value=DESP onclick=\"js_recarregar(3,0);\" >";
                  break;
         case 4:  
         case 9:
                  echo " <input id=rec type=button value=REC onclick=\"js_recarregar(4,9);\" >";
	                break;
         default: echo "<input id=outros type=button value=OUTROS onclick=\"js_recarregar(5,6);\" >";
      }	 
   } else {  ?>    
      <input id="ativo"   type="button" value="ATIVO"   onClick="js_recarregar(1,0);">
      <input id="passivo" type="button" value="PASSIVO" onClick="js_recarregar(2,0);">
      <input id="rec"     type="button" value="RECEITA" onClick="js_recarregar(4,9);">
      <input id="desp"    type="button" value="DESPESA" onClick="js_recarregar(3,0);">
      <input id="outros"  type="button" value="OUTROS"  onClick="js_recarregar(5,6);">
   <? 
    } 

    db_input("grupo1",1,"",true,"hidden",3);
    db_input("grupo2",1,"",true,"hidden",3);
   ?>
   </td>
  </tr>
 </table>
  </td>
 </tr>
</table>
</fieldset>
</td>
</tr>
</table>
<!--   -->
<?
  if (!isset($o69_codparamrel) || $o69_codparamrel ==""){
         $o69_codparamrel=0;
         $o69_codseq=0;  	 
  }        

  if ($grupo == 3 || $grupo == 0){
       $sql_contas  = "select distinct c60_codcon, c61_reduz
                       from conplano
                            inner join consistema      on c52_codsis = conplano.c60_codsis
                            left  join conplanoreduz   on c61_codcon = c60_codcon and 
                                                          c61_anousu = c60_anousu and
                         				                          c61_instit = $instit
                            left join orcparamelemento on o44_codparrel = $o69_codparamrel  and
                                                          o44_sequencia = $o69_codseq and
                                                          o44_anousu    = c60_anousu  and
      	                                                  o44_instit    = $instit     and	
					                                                o44_codele    = conplano.c60_codcon and
                                                          (o44_exclusao  is false or o44_exclusao is null)
                            where c60_anousu = $anousu";
        $res_contas = @pg_query($sql_contas);
        $numrows    = @pg_numrows($res_contas);

        if ($numrows > 0){
             $lista_contas_analiticas = "";
             $lista_contas_sinteticas = "";
             $virgula = "";
             for($i = 0; $i < $numrows; $i++){
                  $c60_codcon = pg_result($res_contas,$i,"c60_codcon");
                  $c61_reduz  = pg_result($res_contas,$i,"c61_reduz");
                  if (isset($c61_reduz) && strlen(trim($c61_reduz)) > 0){
                       $lista_contas_analiticas .= $virgula.$c60_codcon;
                  } else {
                       $lista_contas_sinteticas .= $virgula.$c60_codcon;
                  }

                  $virgula = ",";
             }
        }
  }

  db_input("lista_contas_analiticas",500,0,true,"hidden",3);
  db_input("lista_contas_sinteticas",500,0,true,"hidden",3);
  db_input("lista_selecionados",     500,0,true,"hidden",3);

  $sql = "select distinct c60_codcon,c60_estrut,c52_descrred,c60_descr,o44_codele,c61_codigo, c61_codigo as recurso,c61_reduz,c61_instit
          from conplano 
               inner join consistema      on c52_codsis = conplano.c60_codsis
               left  join conplanoreduz   on c61_codcon = c60_codcon and 
                                             c61_anousu = c60_anousu and
                                             c61_instit = $instit
               left join orcparamelemento on o44_codparrel = $o69_codparamrel  and
	                                           o44_sequencia = $o69_codseq and
	                                           o44_anousu    = c60_anousu  and
	                                           o44_instit    = $instit     and	
					                                   o44_codele    = conplano.c60_codcon and
                                            (o44_exclusao  is false or o44_exclusao is null)
          where c60_anousu = $anousu";

  if (isset($grupo2) && trim(@$grupo2) != "" && @$grupo2 > 0){	
       $sql .=" and (c60_estrut like '$grupo1%' or c60_estrut like '$grupo2%') ";      
  } else if (isset($grupo1) && trim(@$grupo1) != ""){	
       $sql .=" and c60_estrut like '$grupo1%' ";      
  }

  if (isset($ini_estrut) && trim(@$ini_estrut)!=""){
       $tam_ini = strlen(trim($ini_estrut));
       $tam_fim = strlen(trim($fim_estrut));

       $tam     = min($tam_ini,$tam_fim);

       $sql .= " and (substr(c60_estrut,1,$tam) between '".substr($ini_estrut,0,$tam)."' and '".substr($fim_estrut,0,$tam)."') ";
       unset($ini_estrut);
       unset($fim_estrut);
  }

  $sql_instit     = "select codigo from db_config";
  $res_insit      = @pg_query($sql_instit);
  $numrows_instit = @pg_numrows($res_insit);
  if ($numrows_instit > 0){
       $lista_instit = "";
       $virgula      = "";

       for($i=0; $i < $numrows_instit; $i++){
            $lista_instit .= $virgula.pg_result($res_insit,$i,"codigo");
            $virgula       = ",";
       }

       $lista_reduz   = "";
       $virgula       = "";

       $sql_reduz     = "select distinct c61_codcon from conplanoreduz where c61_anousu = $anousu and 
                                                                             c61_instit in ($lista_instit)";
       $res_reduz     = @pg_query($sql_reduz);
       $numrows_reduz = @pg_numrows($res_reduz);
       if ($numrows_reduz > 0){
            for($i=0; $i < $numrows_reduz; $i++){
                 $lista_reduz .= $virgula.pg_result($res_reduz,$i,"c61_codcon");
                 $virgula      = ",";
            }
       }
  }

  if (isset($filtrar_contas) && trim($filtrar_contas)!=""){
       if ($filtrar_contas == "A"){
            $sql .= " and c61_reduz is not null ";
       }
       
       if ($filtrar_contas == "S"){
            $sql .= " and c60_codcon not in ($lista_reduz) ";
       }
  }

  $sql_marca  = $sql." and o44_codele = conplano.c60_codcon ";
  $sql       .= " order by c60_estrut ";           					  
  $sql_marca .= " order by c60_estrut "; 

  //echo $sql;
?>
  <table border="0" align="center"> 
    <tr>
      <td colspan="2">
      <?
         $campos = "c60_codcon,c60_estrut,c61_reduz,c60_descr";
         $cliframe_seleciona_plano->campos        = $campos;
         $cliframe_seleciona_plano->legenda       = "";
         $cliframe_seleciona_plano->sql           = $sql;
         $cliframe_seleciona_plano->sql_marca     = $sql_marca;
         $cliframe_seleciona_plano->iframe_height = "400";
         $cliframe_seleciona_plano->iframe_width  = "700";
         $cliframe_seleciona_plano->iframe_nome   = "plano";
         $cliframe_seleciona_plano->chaves        = "c60_codcon";
         $cliframe_seleciona_plano->js_marcador   = "";
//         $cliframe_seleciona_plano->dbscript      = "";
         $cliframe_seleciona_plano->dbscript      = "onClick='parent.js_selecao();'";
         $cliframe_seleciona_plano->iframe_seleciona(1);
      ?>
      </td>
    </tr>
  </table>
  </table>
  </div>  
</form>
</center>
</body>

<script>
function js_selecao(){
  var obj                = plano.document.form1;
  var tam                = plano.document.form1.length;
  var lista_selecionados = eval("document.form1.lista_selecionados");
  var sep                = "";

  for(i=0; i < tam; i++){
       if (obj[i].type == "checkbox"){
            if (obj[i].checked == true){
                 var str         = new String(obj[i].value);
                 var str_procura = new String(lista_selecionados.value); 

                 if (str_procura.indexOf(str)==-1){
                      if (lista_selecionados.value!=""){
                           sep = "#";
                      }

                      lista_selecionados.value += sep + str;
                      sep = "#";
                 }
            }
       }
  }
}

function js_pesquisao69_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true);
  }else{
     if(document.form1.o69_codparamrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o69_codparamrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o69_codparamrel.focus(); 
    document.form1.o69_codparamrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o69_codparamrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
}
</script>
</html>