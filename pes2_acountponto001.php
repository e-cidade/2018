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
include("classes/db_db_usuarios_classe.php");
$clusuarios = new cl_db_usuarios;
$rotulocampo   = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");

//$dtoper     = date('Y-m-d',db_getsession("DB_datausu"));
//$dtoper_dia = date('d',db_getsession("DB_datausu"));
//$dtoper_mes = date('m',db_getsession("DB_datausu"));
//$dtoper_ano = date('Y',db_getsession("DB_datausu"));

$dtoper     = '';
$dtoper_dia = '';
$dtoper_mes = '';
$dtoper_ano = '';

$dtoper1_dia = '';
$dtoper1_mes = '';
$dtoper1_ano = '';

$dtoper2_dia = '';
$dtoper2_mes = '';
$dtoper2_ano = '';

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
  var F = document.form1;
  var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  selecionados = "";
  virgula_ssel = "";
  for(var i=0; i<document.form1.sselecionados.length; i++){
    selecionados+= virgula_ssel + document.form1.sselecionados.options[i].value;
    virgula_ssel = ",";
  }
  qry  = "colunas="+selecionados;
  qry+= '&ordem='+F.ordem.value;
  qry+= '&tipo_alt='+F.tipo.value;
  qry+= '&dataini='+datai;
  qry+= '&ponto='+F.ponto.value;
  qry+= '&datafin='+dataf;
  if(F.rh27_rubric.value != ''){
    qry+= '&rub='+F.rh27_rubric.value;
  }
  if(F.rh01_regist.value != ''){
    qry+= '&mat='+F.rh01_regist.value;
  }
  jan = window.open('pes2_acountponto002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

/*
  jan = window.open('pes2_afastamentos002.php?
   					   dtafas  ='+datai+
                                         '&dteto   ='+dataf+
					 '&dtlanci ='+data1i+
					 '&dtlancf ='+data1f+
					 '&afasta  ='+document.form1.afasta.value
					 ,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
*/
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" align="center" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table align="center" border="0" cellspacing="0" cellpadding="0">
	    <tr>
               <td width="50%">&nbsp;</td>
               <td width="50%">&nbsp;</td>
	    </tr>
            <tr> 
              <td width="50%">&nbsp;</td>
              <td width="50%">&nbsp;</td>
            </tr>
            <tr> 
              <td align="right" nowrap><strong>Alteracoes Entre:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;
                <?db_inputdata('datai',$dtoper_dia,$dtoper_mes,$dtoper_ano,true,'text',1);?>
              &nbsp;&nbsp;E&nbsp;&nbsp;
                <?db_inputdata('dataf',$dtoper_dia,$dtoper_mes,$dtoper_ano,true,'text',1);?>
              </td>
            </tr>
            <tr >
              <td align="right" ><strong>Ordem :</strong>
              </td>
              <td align="left"nowrap>&nbsp;&nbsp;
                <?
                  $arr_ordem = array("ra"=>"Rubrica/Alfabetica", "rn"=>"Rubrica/Numerica", "rh"=>"Rubrica/Data/Hora"  , "a"=>"Alfabetica","n"=>"Numerica","d"=>"Data/Hora");
                  db_select('ordem',$arr_ordem,true,4,"");
                      ?>
                    </td>
            </tr>
            <tr >
              <td align="right" ><strong>Ponto :</strong>
              </td>
              <td align="left"nowrap>&nbsp;&nbsp;
                <?
                  $arr_ponto = array("s"=>"Salario","f"=>"Fixo");
                  db_select('ponto',$arr_ponto,true,4,"");
                      ?>
                    </td>
            </tr>
            <tr >
              <td align="right" ><strong>Tipo :</strong>
              </td>
              <td align="left"nowrap>&nbsp;&nbsp;
                <?
                  $arr_tipo = array("t"=>"Tudo","a"=>"Alteracoes","i"=>"Inclusoes", "e"=>"Exclusoes");
                  db_select('tipo',$arr_tipo,true,4,"");
                      ?>
                    </td>
            </tr>
            <tr>
              <td align="right" nowrap title="Informar a rubrica a ser consultada ou deixar em branco para todas." >
              <?
               db_ancora("<b>Rubrica :</b>","js_pesquisarub(true)",1);
              ?>
              </td>
              <td>&nbsp;&nbsp;
                <?
                db_input('rh27_rubric',10,'Rubrica 1',true,'text',2,'onchange="js_pesquisarub(false)"');
                db_input('rh27_descr',40,'Rubrica 2',true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td align="right" nowrap title="Informar a matricula do funcionario ou deixar em branco para todos." >
              <?
               db_ancora("<b>Matricula :</b>","js_pesquisamat(true)",1);
              ?>
              </td>
              <td>&nbsp;&nbsp;
                <?
                db_input('rh01_regist',10,'Matricula 1',true,'text',2,'onchange="js_pesquisamat(false)"');
                db_input('z01_nome',40,'Matricula 2',true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr> 
              <td width="360">&nbsp;</td>
              <td width="140">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                    <fieldset>
                      <Legend align="left">
                        <b>Selecione os Usuarios</b>
                      </Legend>
                      <?
                      db_input("valor", 3, 0, true, 'hidden', 3);
                      db_input("colunas_sselecionados", 3, 0, true, 'hidden', 3);
                      db_input("colunas_nselecionados", 3, 0, true, 'hidden', 3);
                       if(!isset($result_usuarios)){
                          $result_usuarios = $clusuarios->sql_record($clusuarios->sql_query_file(null, "id_usuario, id_usuario||'-'||upper(nome) as rh30_descr", "nome"  ));
                          for($x=0; $x<$clusuarios->numrows; $x++){
                               db_fieldsmemory($result_usuarios,$x);
                               $arr_colunas[$id_usuario]= $rh30_descr;
                          }
                        }
                        $arr_colunas_final   = Array();
                        $arr_colunas_inicial = Array();
                        if(isset($colunas_sselecionados) && $colunas_sselecionados != ""){
                           $colunas_sselecionados = split(",",$colunas_sselecionados);
                           for($Ic=0;$Ic < count($colunas_sselecionados);$Ic++){
                              $arr_colunas_final[$colunas_sselecionados[$Ic]] = $arr_colunas[$colunas_sselecionados[$Ic]]; 
                           }
                        }
                        if(isset($colunas_nselecionados) && $colunas_nselecionados != ""){
                           $colunas_nselecionados = split(",",$colunas_nselecionados);
                           for($Ic=0;$Ic < count($colunas_nselecionados);$Ic++){
                              $arr_colunas_inicial[$colunas_nselecionados[$Ic]] = $arr_colunas[$colunas_nselecionados[$Ic]]; 
                           }
                        }
                        if(!isset($colunas_sselecionados) || !isset($colunas_sselecionados) || $colunas_sselecionados == ""){
                           $arr_colunas_final  = Array();
                           $arr_colunas_inicial = $arr_colunas;
                        }
                       db_multiploselect("id_usuario","rh30_descr", "nselecionados", "sselecionados", $arr_colunas_inicial, $arr_colunas_final, 6, 250, "", "", true, "js_complementar('c');");
                       ?>
                    </fieldset>
              </td>
            </tr>
	    <tr>
               <td width="25">&nbsp;</td>
               <td width="140">&nbsp;</td>
	    </tr>
            <tr> 
              <td colspan = "3" align="center" > 
	          <input name="Imprimir" type="button" id="imprimir" onClick="js_relatorio()" value="Imprimir"> 
	      </td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_pesquisarub(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarub1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.rh27_rubric.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh27_rubric.value+'&funcao_js=parent.js_mostrarub','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = '';
     }
  }
}
function js_mostrarub(chave,erro){
  document.form1.rh27_descr.value = chave; 
  if(erro==true){ 
    document.form1.rh27_rubric.focus(); 
    document.form1.rh27_rubric.value = ''; 
  }
}
function js_mostrarub1(chave1,chave2){
  document.form1.rh27_rubric.value = chave1;
  document.form1.rh27_descr.value   = chave2;
  db_iframe_rhrubricas.hide();
}




function js_pesquisamat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostramat1|rh01_regist|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.rh01_regist.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostramat','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostramat(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }
}
function js_mostramat1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
}
</script>