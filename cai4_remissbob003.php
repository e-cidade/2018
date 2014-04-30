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
include("libs/db_libcaixa.php");
include("classes/db_corrente_classe.php");
include("classes/db_cfautent_classe.php");
$clcorrente = new cl_corrente;
$clcfautent = new cl_cfautent;
$clautenticar= new cl_autenticar;
$ip = db_getsession("DB_ip");
$porta = 5001;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#AAB7D5">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center"><font id="numeros" size="2">Processando...</font></td>
  </tr>
</table>
</body>
</html>
<?
$clautenticar->verifica($ip,$porta);
if($clautenticar->erro==true){
 db_msgbox($clautenticar->erro_msg);
 echo "<script>parent.db_iframe_imprime.hide();</script>";
}else{    
    if($tipo=='cabecalho'){
	$clautenticar->conectar($ip,$porta);
	$clautenticar->data_dia=$dia;
	$clautenticar->data_mes=$mes;
	$clautenticar->data_ano=$ano;
	$clautenticar->cabecalho();
	$clautenticar->fechar();
	if($clautenticar->erro==true){
	  db_msgbox($clautenticar->erro_msg);
	} 
        echo "<script>parent.db_iframe_imprime.hide();</script>";
    }else if($tipo='autenticacao'){
         $result=$clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_id",null,"k11_ipterm='$ip'"));
	 db_fieldsmemory($result,0);
         
	 $result = $clcorrente->sql_record($clcorrente->sql_query_file($k11_id,"$ano-$mes-$dia",null,"*","k12_autent"));
	 $clautenticar->conectar($ip,$porta);
	 for($i=0; $i<$clcorrente->numrows; $i++){
	   db_fieldsmemory($result,$i);
	   $clautenticar->imprimir_ln("$k12_valor");
	 }
	 $clautenticar->fechar();

 	 if($clautenticar->erro==true){
	    db_msgbox($clautenticar->erro_msg);
	 } 

	 echo "<script>parent.db_iframe_imprime.hide();</script>";
    }else if($tipo='fechamento'){
	  echo "<script>parent.db_iframe_imprime.hide();</script>";
    }
}


/*
if(isset($HTTP_POST_VARS["pesquisar"])) {
  db_postmemory($HTTP_POST_VARS);
  $data = $data_ano."-".$data_mes."-".$data_dia;
  
  $result = pg_exec("select distinct cfa.k11_ipterm,cfa.k11_local,(case when cn.k12_numpre is not null then cn.k12_numpre::bpchar else (case when cl.k12_autent is not null then cl.k12_autent::bpchar else (case when ce.k12_empen is not null then ce.k12_empen::bpchar end) end) end) as codigo,cfa.k11_ident1,cfa.k11_ident2,cfa.k11_ident3,to_char(c.k12_data,'DD-MM-YYYY') as data,c.k12_autent,c.k12_conta,c.k12_valor
                      from corrente c
                      left outer join cornump cn
                      on cn.k12_id = c.k12_id
                      and cn.k12_data = c.k12_data
                      and cn.k12_autent = c.k12_autent
                      left outer join corlanc cl
                      on cl.k12_id = c.k12_id
                      and cl.k12_data = c.k12_data
                      and cl.k12_autent = c.k12_autent
                      left outer join coremp ce
                      on ce.k12_id = c.k12_id
                      and ce.k12_data = c.k12_data
                      and ce.k12_autent = c.k12_autent
                      inner join cfautent cfa
                      on cfa.k11_id = c.k12_id
                      where c.k12_data = '$data'
                      and c.k12_id = $caixa
                      order by c.k12_autent");
  $numrows = pg_numrows($result);
  if($numrows == 0) {
    echo "<script>alert('Nenhuma autenticação encontrada.');</script>\n";
  } else {
    $ipterm = trim(pg_result($result,0,"k11_ipterm"));
    $local  = trim(pg_result($result,0,"k11_local"));
    echo "<script>\n";
	echo "ob = new ActiveXObject('WScript.Shell');\n";
	$str  =  "' **********************************************'";
	$str .= ",' *                                            *'";
    $str .= ",' *            Reemissão da Bobina             *'";
    $str .= ",' *            ID:    ".str_pad($caixa,25)."*'";
    $str .= ",' *            IP:    ".str_pad($ipterm,25)."*'";
    $str .= ",' *            Local: ".str_pad($local,25)."*'";
	$str .= ",' *                                            *'";	
	$str .= ",' **********************************************'";
    for($i = 0;$i < $numrows;$i++) {
	  $str .= ",'".str_pad(pg_result($result,$i,"k12_autent"),5).str_pad(pg_result($result,$i,"k11_ident1"),3).str_pad(pg_result($result,$i,"k11_ident2"),3).str_pad(pg_result($result,$i,"k11_ident3"),3).str_pad(pg_result($result,$i,"data"),13).str_pad(pg_result($result,$i,"codigo"),13).str_pad(pg_result($result,$i,"k12_conta"),5).str_pad(number_format(pg_result($result,$i,"k12_valor"),2,".",","),13," ",STR_PAD_LEFT)."'";	 
	}	
	echo "boletim = new Array($str);\n";
	?>
ControlaAutent = 0;
function js_aut1() {
  if(ControlaAutent < boletim.length) {
//	alert(boletim[ControlaAutent]+' -- '+ControlaAutent);  
    ob.Run('c:\autentica.exe "<?=chr(27).chr(15)?>' + boletim[ControlaAutent++] + '<?=chr(27).chr(18)?>"',0,false);
    setTimeout("js_aut1()",500);  
  } else {
    alert("Fim da impressão");
  }
}
function js_aut() {
  if(ControlaAutent < 8) {
//	alert(boletim[ControlaAutent]+' -- '+ControlaAutent);  
    ob.Run('c:\autentica.exe "' + boletim[ControlaAutent++] + '"',0,false);
    setTimeout("js_aut()",500);
  } else {
    js_aut1();
  }
}
js_aut();
	<?
	echo "</script>\n";
  }
}
*/
?>