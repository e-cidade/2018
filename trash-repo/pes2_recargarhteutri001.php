<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_rhteutri_classe.php");
$clrhteutri = new cl_rhteutri;
$clrotulo = new rotulocampo;
$clrotulo->label("rh68_descr");
$clrotulo->label("rh67_rhtipovale");
$clrotulo->label("r07_codigo");
$clrotulo->label("r07_descr");
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
db_postmemory($HTTP_POST_VARS);
if (isset($gera)){
    
  $arq = "/tmp/recarga".$grupo;
  
  $arquivo = fopen($arq,'w');  

  if($ordem == 'a'){
    $xordem = 'z01_nome';
  }else{
    $xordem = 'rh67_regist';
  }

  $where = " rh67_rhtipovale = $rh67_rhtipovale ";

  if($tipo == 'a'){
    $where .= " and rh67_ativo = 't'";
  }elseif($tipo == 'i'){
    $where .= " and rh67_ativo = 'f'";
  }

  $xgrupo = '';
  if(trim($grupo) != ''){
    $where .= " and rh67_grupo = $grupo ";
  }

  $result = $clrhteutri->sql_record($clrhteutri->sql_query(null,"rh67_regist,rh67_dias, rh67_vales, z01_nome, z01_cgccpf ",$xordem,$where));
//  echo ($clrhteutri->sql_query(null,"rh67_regist,rh67_dias, rh67_vales, z01_nome, z01_cgccpf ",$xordem,$where));
//db_criatabela($result);exit;
  $xxnum = pg_numrows($result);
  if($rh67_rhtipovale == 1){
  //fputs($arquivo,"TIPO DE REGISTRO;Nº DA MATRICULA FUNCIONARIO;Nº DO CPF/CNPJ;FILIAL;CONTROLE;CAPACIDADE CIVIL;TIPO DE MOVIMENTO;NOME DO FUNCIONARIO;ENDEREÇO RESIDENCIAL;NUMERO;COMPLEMENTO;BAIRRO;CEP;SUFIXO;FONE (DDD);FONE NÚMERO;FAX (DDD);FAX NÚMERO;ENDEREÇO PARA CORRESPONDÊNCIA;NUMERO DO ENDEREÇO;COMPLEMENTO DO ENDEREÇO;BAIRRO DO ENDER;CEP NUMERO;SUFIXO DO CEP;CÓDIGO DE OCUPAÇÃO;DATA DE NASCIMENTO;NATURALIDADE;UF DE NASCIMENTO;SEXO;NOME DO PAI;NOME DA MÃE;BRASILEIRO/ESTRANGEIRO;NACIONALIDADE;ESTADO CIVIL;TIPO DE DOCUMENTO;NÚMERO DO DOCUMENTO;DATA DE EMISSÃO;ORGÃO EMISSOR;NOME DA EMPRESA;CARGO;RENDA;TEMPO DE SERVIÇO;ENDEREÇO DA EMPRESA;NUMERO CEP;SUFIXO CEP;NOME DO CONJUGÊ;DESTINO DO BANCO;DESTINO DA AGÊNCIA;DÍGITO;DESTINO DO PAB;DESTINO RAZÃO;DESTINO CONTA;DESTINO DIGITO;DESTINO TIPO DA CONTA;FILLER;BANCO ORIGEM;AGENCIA ORIGEM;DÍGITO AG ORIGEM;PAB DE ORIGEM;RAZÀO DE ORIGEM;CONTA DE ORIGEM;DÍGITO CONTA ORIGEM;TIPO DA CONTA;DATA DE RECADASTRAMENTO;DATA DE ABERTURA;DATA DE ÚLTIMO ACERTO;FILLER;AGÊNCIA GESTORA;EMITE CARTÀO;CLIENTE PRIME;CLIENTE PRIVATE;PAB PRIME;BANCO PARA;AGÊNCIA PARA;CONTA PARA;DÍGITO DA CTA PARA;FILLER"."\r\n");
    for($x = 0;$x < pg_numrows($result);$x++){
      db_fieldsmemory($result,$x);
      fputs($arquivo,db_formatar($rh67_regist,'s',' ',15,'e',0).db_formatar($rh67_dias,'s','0',2,'e',0)."\r\n");
    }
  }else{
    $r07_valor = 1;
    if($r07_codigo != ''){
      $sql_diverso = "select r07_valor from pesdiver where r07_codigo = '$r07_codigo' and r07_anousu = ".db_anofolha()." and r07_mesusu = ".db_mesfolha();
      $res_diverso = pg_query($sql_diverso);
      db_fieldsmemory($res_diverso,0);
    }
    fputs($arquivo,"0200"."\r\n");
    for($x = 0;$x < pg_numrows($result);$x++){
      db_fieldsmemory($result,$x);
//      echo "<br><br> valor : ".str_replace(',','',db_formatar($r07_valor*$rh67_vales,'f'));
//      echo "<br><br> valor : ".(($r07_valor*$rh67_vales));exit;
      fputs($arquivo,db_formatar($z01_cgccpf,'s',' ',11,'e',0).'|'.$rh67_dias.'|'.trim(str_replace(',','',db_formatar($r07_valor*$rh67_vales,'f'))).'|'.$z01_nome."\r\n");
    }
  }
  fclose($arquivo);
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>


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
<table border="0" align="center">
  <tr> 
    <td >&nbsp;</td>
  </tr>
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Arquivo de Vale Transporte Integrado</b>
    </Legend>
  <table border="0" align="center">
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Trh67_rhtipovale?>">
           <?
           if(isset($rh67_rhtipovale)){
             $rh67_rhtipovale = '';
             $rh68_descr = '';
           }
           db_ancora(@$Lrh67_rhtipovale,"js_pesquisarh67_rhtipovale(true);",2);
           ?>
        </td>
        <td> 
        <?
          db_input('rh67_rhtipovale',4,$Irh67_rhtipovale,true,'text',2," onchange='js_pesquisarh67_rhtipovale(false);'")
        ?>
        <?
          db_input('rh68_descr',40,$Irh68_descr,true,'text',3,'')
        ?>
        </td>
      </tr>
      <?
        if(@$rh67_rhtipovale == '2'){
          echo "<tr id='camposdiversos' style='display'>";
        }else{
          echo "<tr id='camposdiversos' style='display:none'>";
        }
      ?>
        <td nowrap title="Digite o diverso que corresponde ao valor unitário da passagem."><b>
           <?
           db_ancora('Diverso:',"js_pesquisapesdiver(true);",2);
           ?>
           </b>
        </td>
        <td> 
        <?
          db_input('r07_codigo',4,@$r07_codigo,true,'text',2," onchange='js_pesquisapesdiver(false);'");
          db_input('r07_descr',40,@$Ir07_descr,true,'text',3,'');
        ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Tipo de emissão" >
        <strong>Tipo :&nbsp;</strong>
        </td>
        <td>
           <?
           $xy = array("a"=>"Ativos","t"=>"Todos","i"=>"Inativos");
           db_select('tipo',$xy,true,1,"");
          ?>
        </td>
      </tr>
      <tr >
        <td align="right" nowrap title="Ordem de emissão do relatório" >
        <strong>Ordem :&nbsp;&nbsp;</strong>
        </td>
        <td>
           <?
           $x = array("n"=>"Numérica","a"=>"Alfabética");
           db_select('ordem',$x,true,1,"");
          ?>
        </td>
      </tr>
      <tr >
        <td>
           <?
           $res = $clrhteutri->sql_record($clrhteutri->sql_query(null,"distinct rh67_grupo",'',''));
           if($clrhteutri->numrows > 0){
	         echo "
	         <tr>
	          <td align='right' title=''><strong>Grupo:&nbsp;</strong></td>
            <td>
	            <select name='grupo'>
	            ";
	            for($i=0; $i<$clrhteutri->numrows; $i++){
		            db_fieldsmemory($res, $i);
		            echo "<option value = '$rh67_grupo'>$rh67_grupo";
	            }
	              echo "
	          </td>
	         </tr>
	        ";
           }
          ?>
        </td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="gera" id="gera" type="submit" value="Processar"  >
        </td>
      </tr>
  </table>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
  <?
  if(isset($gera)){
  	echo "js_montarlista('".$arq."#Arquivo gerado em: ".$arq."','form1');";
  }
  ?>

function js_recarregar(iTipo){

  if (iTipo == 2) {
    var display = '';
  } else {
    var display = 'none';    
    document.form1.r07_codigo.value = '';
    document.form1.r07_descr.value = '';
  }

  document.getElementById('camposdiversos').style.display = display;

  js_ajaxRequest(iTipo);

}

function js_ajaxRequest(obj){

  js_divCarregando("Aguarde, buscando grupos","processando");

  var url       = 'pes4_dadosGrupoRPC.php';
  var parametro = 'tipovale='+obj;
  var objAjax   = new Ajax.Request (url,{method:'post',parameters:parametro, onComplete:carregaDadosSelect});
	document.form1.rh67_rhtipovale.disabled = true;

}


function carregaDadosSelect(resposta){

  js_removeObj('processando');

	document.form1.rh67_rhtipovale.disabled = false;
	js_limpaSelect(document.form1.grupo);  
	js_addSelectFromStr(resposta.responseText,document.form1.grupo);

}

function js_limpaSelect(obj){
  obj.length  = 0;	
}

function js_addSelectFromStr(str,obj){
  var linhas  = str.split("|");
  for(i=0;i<linhas.length;i++){
		if(linhas[i] != ''){
		  colunas = linhas[i].split("-");
      obj.options[i] = new Option();
      obj.options[i].value = colunas[0];
      obj.options[i].text  = colunas[1];
		}
  }	
}




function js_pesquisarh67_rhtipovale(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhtipovale','func_rhtipovale.php?funcao_js=parent.js_mostrarhtipovale1|rh68_sequencial|rh68_descr','Pesquisa',true);
  }else{
     if(document.form1.rh67_rhtipovale.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhtipovale','func_rhtipovale.php?pesquisa_chave='+document.form1.rh67_rhtipovale.value+'&funcao_js=parent.js_mostrarhtipovale','Pesquisa',false);
     }else{
       document.form1.rh68_descr.value = ''; 
     }
  }
  ////document.form1.submit();
}
function js_mostrarhtipovale(chave,erro){
  document.form1.rh68_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh67_rhtipovale.focus(); 
    document.form1.rh67_rhtipovale.value = ''; 
  }else{
    js_recarregar(document.form1.rh67_rhtipovale.value);    
  }
}
function js_mostrarhtipovale1(chave1,chave2){
  document.form1.rh67_rhtipovale.value = chave1;
  document.form1.rh68_descr.value = chave2;
  js_recarregar(chave1);    
  db_iframe_rhtipovale.hide();
}



function js_pesquisapesdiver(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pesdiver','func_pesdiver.php?funcao_js=parent.js_mostrapesdiver1|r07_codigo|r07_descr','Pesquisa',true);
  }else{
     if(document.form1.r07_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pesdiver','func_pesdiver.php?pesquisa_chave='+document.form1.r07_codigo.value+'&funcao_js=parent.js_mostrapesdiver','Pesquisa',false);
     }else{
       document.form1.r07_descr.value = ''; 
     }
  }
}
function js_mostrapesdiver(chave,erro){
  document.form1.r07_descr.value = chave; 
  if(erro==true){ 
    document.form1.r07_codigo.focus(); 
    document.form1.r07_codigo.value = ''; 
  }
}
function js_mostrapesdiver1(chave1,chave2){
  document.form1.r07_codigo.value = chave1;
  document.form1.r07_descr.value = chave2;
  db_iframe_pesdiver.hide();
}

function js_verifica(){
  if(document.form1.rh67_rhtipovale.value == ''){
    alert('Escolha um Tipo de Vale');
    return false;
  }
  if(document.form1.rh67_rhtipovale.value == 2 && document.form1.r07_codigo.value == ''){
    alert('Para tipo de vale 2, deve ser escolhido o diverso correspondente ao valor da uma passagem!');
    return false;
  }
}
</script>