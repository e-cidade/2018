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
include("classes/db_inssirf_classe.php");
include("classes/db_rhcadregime_classe.php");
$clrotulo = new rotulocampo;
$clinssirf = new cl_inssirf;
$clrhcadrefime = new cl_rhcadregime;
db_postmemory($HTTP_POST_VARS);
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>


function js_emite(){
  //js_controlarodape(true);
  qry  = 'xano='+ document.form1.xano.value;
  qry += '&xmes='+ document.form1.xmes.value;
  qry += '&sal_dec=' + document.form1.sal_dec.value;
  qry += '&prev=' + document.form1.prev.value;
  qry += '&sf=' + document.form1.sf.value;
  qry += '&lg=' + document.form1.lg.value;
  qry += '&ls=' + document.form1.ls.value;
  qry += '&vinculo=' + document.form1.vinculo.value;
  js_OpenJanelaIframe('top.corpo','db_iframe_geraideal','pes2_layoutideal002.php?'+qry,'Gerando Arquivo',false);
}

function js_erro(msg){
  //js_controlarodape(false);
  top.corpo.db_iframe_geraideal.hide();
  alert(msg);
}
function js_fechaiframe(){
  db_iframe_geraideal.hide();
}
function js_controlarodape(mostra){
  if(mostra == true){
    document.form1.rodape.value = parent.bstatus.document.getElementById('st').innerHTML;
    parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;
  }else{
    parent.bstatus.document.getElementById('st').innerHTML = document.form1.rodape.value;
  }
}
function js_detectaarquivo(arquivo,pdf){
//  js_controlarodape(false);
  top.corpo.db_iframe_geraideal.hide();
  listagem = arquivo+"#Download arquivo TXT|";
  listagem+= pdf+"#Download relatório PDF";
  js_montarlista(listagem,"form1");
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
      <table align="center">
        <tr >
            <td align="right" nowrap title="Digite o Ano / Mes " >
              <strong>Ano / Mês:</strong>
            </td>
            <td align="left">
              <?
              $xano = db_anofolha() ;
              db_input('xano',4,'',true,'text',2,'')
              ?>
              &nbsp;/&nbsp;
              <?
              $xmes = db_mesfolha();
              db_input('xmes',2,'',true,'text',2,'')
              ?>
            </td>
        </tr>

        <tr>
          <td align="right">
            <b>Tipo de folha:</b>
          </td>
          <td>
            <?
            $arr_tipofolha = Array("S"=>"Salário",
                                   "D"=>"13o Salário");
            db_select("sal_dec", $arr_tipofolha, true, 1);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap title="Tabela de Previdência">
          <strong>Tabela de Previdência:&nbsp;</strong>
          </td>
          <td>
            <?
///         echo ($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"distinct (r33_codtab - 2) as r33_codtab,r33_nome","r33_codtab"," r33_anousu = ".$xano." and r33_mesusu = ".$xmes." and r33_codtab > 2"));
            $res = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit'),"distinct (r33_codtab - 2) as r33_codtab,r33_nome","r33_codtab"," r33_anousu = ".$xano." and r33_mesusu = ".$xmes." and r33_codtab > 2"));
            db_selectrecord('prev', $res, true, 4);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right" nowrap title="Vínculo">
          <strong>Vínculo:</strong>
          </td>
          <td>
            <?
            $arr_vinculo = Array("t"=>"Todos",
                                 "a"=>"Ativos",
                                 "i"=>"Inativos",
                                 "p"=>"Pensionistas",
                                 "ip"=>"Inativos/Pensionistas"
                                 );
            db_select("vinculo", $arr_vinculo, true, 1);
            ?>
          </td>
        </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
        <tr>
          <td colspan="2" align = "center">
            <fieldset>
              <legend><b>Valores Pagos Pelo RPPS</b>
              </legend>
                <table>
                  <tr>
                    <td align="right"><strong>Salário Família:</strong>
                    </td>
                    <td align="left">
                    <?
                      $arr_sf = array("s"=>"SIM", "n"=>"Não");
                      db_select('sf',$arr_sf,true,4);
                    ?>
                    </td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Licença Gestante:</strong>
                    </td>
                    <td align="left">
                    <?
                      $arr_lg = array("s"=>"SIM", "n"=>"Não");
                      db_select('lg',$arr_lg,true,4);
                    ?>
                    </td>
                  </tr>
                  <tr>
                    <td align="right"><strong>Licença Saúde:</strong>
                    </td>
                    <td align="left">
                    <?
                      $arr_ls = array("s"=>"SIM", "n"=>"Não");
                      db_select('ls',$arr_ls,true,4);
                    ?>
                    </td>
                  </tr>
                </table>
            </fieldset>
          </td>
        </tr>
      </table>
      <table align="center">
        <tr>
	        <td colspan="2" align = "center"> 
            <input  name="gera" id="gera" type="button" value="Gera" onclick="js_emite();" >
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