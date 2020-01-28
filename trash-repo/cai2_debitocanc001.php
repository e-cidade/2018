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

$aux = new cl_arquivo_auxiliar;


$clrotulo = new rotulocampo;
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label("c58_sequencial");
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js">
        </script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
        <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
            <tr>
                <td width="360" height="18">
                    &nbsp;
                </td>
                <td width="263">
                    &nbsp;
                </td>
                <td width="25">
                    &nbsp;
                </td>
                <td width="140">
                    &nbsp;
                </td>
            </tr>
        </table>
        <table align="center" border="0">
            <tr>
                <td>
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td>
                    <form name="form1" method="post" action="">
                        <fieldset>
                            <table align="center" width="650px" border="0">
                                <tr>
                                    <td nowrap title="<?=@$Tz01_numcgm?>" align="right" width="30px">
                                        <b>
                                            <?
											                      db_ancora("CGM :","js_pesquisaz01_numcgm(true);",1);
										                        ?>
                                        </b>
                                    </td>
                                    <td>
                                        <?
										db_input('z01_numcgm',15,$Iz01_numcgm,true,'text',1," onchange='js_pesquisaz01_numcgm(false);'");
									    db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?=@$Tj01_matric?>" align="right">
                                        <b>
                                            <?
                                						db_ancora('Matrícula:',"js_pesquisaj01_matric(true);",1);
                                						?>
                                        </b>
                                    </td>
                                    <td>
                                        <?
                                				db_input('j01_matric',15,$Ij01_matric,true,'text',1,"");
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td nowrap title="<?=@$Tq02_inscr?>" align="right">
                                        <b>
                                            <?
                                						db_ancora('Inscrição :',"js_pesquisaq02_inscr(true);",1);
                                						?>
                                        </b>
                                    </td>
                                    <td>
                                        <?
                                				db_input('q02_inscr',15,$Iq02_inscr,true,'text',1,"");
                                        ?>
                                    </td>
                                </tr>
                                <?
										            $dtd = date("d",db_getsession("DB_datausu"));
										            $dtm = date("m",db_getsession("DB_datausu"));
										            $dta = date("Y",db_getsession("DB_datausu"));
								                ?>
                                <tr>
                                    <td align="right">
                                        <b>Data Inicial :</b>
                                    </td>
                                    <td>
                                        <?
										                    db_inputdata("datai","$dtd","$dtm","$dta","true","text",2);
										                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <b>Data Final &nbsp; :</b>
                                    </td>
                                    <td>
                                        <?
												                db_inputdata("dataf","$dtd","$dtm","$dta","true","text",2);      
										                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="right">
                                        <strong>Tipo de cancelamento:</strong>
                                    </td>
                                    <td>
                                        <?
				  $resulttipo = pg_query("select 3 as k73_sequencial,'Todos' as k73_descricao union all select k73_sequencial,k73_descricao from cancdebitostipo ");
				  $linhasTipo = pg_num_rows($resulttipo);
				  $tipo = array();
				  if($linhasTipo > 0 ){
				    for($t=0;$t<$linhasTipo;$t++){
				    	db_fieldsmemory($resulttipo, $t);
						$tipo[$k73_sequencial] = $k73_descricao;
					
				    }	
				  }
				  db_select("tipoDebito",$tipo,true,1,"onChange='js_mostraAgrupar(document.form1.tipoDebito.value);'");			
			
				?>
                                    </td>
                                </tr>
																<tr id="agr" >
                                    <td align="right">
                                        <b>Agrupar por:</b>
                                    </td>
                                    <td>
                                        <?
                                        $arr = array("N"=> "Nenhum", "CP"=>"Caracteristica Peculiar"); 
																				db_select("agrupar",$arr,true,1,"onChange='js_mostraQuebrar(document.form1.agrupar.value);'");
										                    ?>
                                    </td>
                                </tr>
																<tr id="queb"> 
                                    <td align="right">
                                        <b>Quebrar a página:</b>
                                    </td>
                                    <td>
                                        <?
                                        $arrQuebra = array("N"=> "Não", "S"=>"Sim"); 
																				db_select("quebrar",$arrQuebra,true,1);
										                    ?>
                                    </td>
                                </tr>
                                <tr> 
                                    <td align="right" width="30px">
                                        <b>Mostrar endereço:</b>
                                    </td>
                                    <td>
                                        <?
                                        $aMostEnder = array("S"=>"Sim","N"=> "Não"); 
                                        db_select("mostender",$aMostEnder,true,1);
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        &nbsp; 
                        <fieldset>
                            <table align="center" border="0" width="100%">
                                <tr>
                                    <td align="left" nowrap title="">
                                        <strong>Tipo : </strong>
                                    </td>
                                    <td align="left">
                                        <?
                    $xx = array("c"=>"Completo","r"=>"Resumido por tipo","rc"=>"Resumido por contribuinte");
									  db_select('seltipo',$xx,true,4,"");
                  ?>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td align="right" nowrap title="Ordem para a emissão do relatório">
                                        <strong>Ordem : </strong>
                                    </td>
                                    <td align="left">
                                        <?
									  $xx = array("d"=>"Data","c"=>"CGM","m"=>"Matrícula","i"=>"Inscrição");
								 	  db_select('selordem',$xx,true,4,"");
                  ?>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                    <td align="right" nowrap title="">
                                        <strong>Histórico : </strong>
                                    </td>
                                    <td align="left">
                                        <?
                    $xx = array("s"=>"Sim","n"=>"Não");
								 	  db_select('selhist',$xx,true,4,"");
                  ?>
                                        &nbsp;&nbsp;&nbsp;
                                    </td>
                                </tr>
                            </table>
                            <table align="center" width="100%">
                                <tr>
                                    <td>
                                        <?
                    $aux->codigo = "k00_tipo";
                    $aux->descr  = "k00_descr";
                    $aux->nomeobjeto = 'arqarretipo';
                    $aux->funcao_js = 'js_funcaotipo';
                    $aux->funcao_js_hide = 'js_funcaotipo1';
                    $aux->sql_exec  = "";
                    $aux->func_arquivo = "func_arretipo.php";
                    $aux->nomeiframe = "iframe_arretipo";
                    $aux->localjan = "";
                    $aux->tipo = 2;
                    $aux->db_opcao = 2;
                    $aux->top = 0;
                    $aux->linhas = 10;
                    $aux->vwidth = 520;
                    $aux->funcao_gera_formulario();
                  ?>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        <tr>
                            <td colspan="2" align = "center">
                                <input name="emite2" id="emite2" type="button" value="Emitir Relatório" onclick="js_emite();">
                            </td>
                        </tr>
                    </form>
                </td>
            </tr>
        </table>
        <?
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	 ?>
    </body>
</html>
<script>
    
    function js_emite(){
    
    
        deb = "";
        vir = "";
        for (y = 0; y < document.getElementById('arqarretipo').length; y++) {
            deb += vir + document.getElementById('arqarretipo').options[y].value;
            vir = ",";
        }
        
        qry = '?seltipo=' + document.form1.seltipo.value;
        qry += '&selordem=' + document.form1.selordem.value;
        qry += '&selhist=' + document.form1.selhist.value;
        qry += '&datai=' + document.form1.datai_ano.value + '-' + document.form1.datai_mes.value + '-' + document.form1.datai_dia.value;
        qry += '&dataf=' + document.form1.dataf_ano.value + '-' + document.form1.dataf_mes.value + '-' + document.form1.dataf_dia.value;
        
        qry += '&z01_numcgm=' + document.form1.z01_numcgm.value;
        qry += '&j01_matric=' + document.form1.j01_matric.value;
        qry += '&q02_inscr=' + document.form1.q02_inscr.value;
        qry += '&tipoDebito=' + document.form1.tipoDebito.value;
				qry += '&agrupar=' + document.form1.agrupar.value;
				qry += '&quebrar=' + document.form1.quebrar.value;
				qry += '&mostender=' + document.form1.mostender.value;
				qry += '&arqarretipo=' + deb;
        
        jan = window.open('cai2_debitocanc002.php' + qry, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }
    
    
    function js_pesquisaz01_numcgm(mostra){
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_nome', 'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome', 'Pesquisa', true);
        }
        else {
            if (document.form1.z01_numcgm.value != '') {
                js_OpenJanelaIframe('top.corpo', 'db_iframe_nome', 'func_nome.php?pesquisa_chave=' + document.form1.z01_numcgm.value + '&funcao_js=parent.js_mostracgm', 'Pesquisa', false);
            }
            else {
                document.form1.kz01_numcgm.value = '';
            }
        }
    }
    
    function js_mostracgm(erro, chave){
        document.form1.z01_nome.value = chave;
        if (erro == true) {
            document.form1.z01_numcgm.focus();
            document.form1.z01_numcgm.value = '';
        }
    }
    
    function js_mostracgm1(chave1, chave2){
        document.form1.z01_numcgm.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_nome.hide();
    }
    
    
    function js_pesquisaj01_matric(mostra){
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_matric', 'func_iptubase.php?funcao_js=parent.js_mostramatric|j01_matric', 'Pesquisa', true);
        }
        else {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_matric', 'func_iptubase.php?pesquisa_chave=' + document.form1.j01_matric.value + '&funcao_js=parent.js_mostramatric', 'Pesquisa', false);
        }
    }
    
    
    function js_mostramatric(chave){
        document.form1.j01_matric.value = chave;
        db_iframe_matric.hide();
    }
    
    
    function js_pesquisaq02_inscr(mostra){
        if (mostra == true) {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_inscr', 'func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr', 'Pesquisa', true);
        }
        else {
            js_OpenJanelaIframe('top.corpo', 'db_iframe_inscr', 'func_issbase.php?pesquisa_chave=' + document.form1.q02_inscr.value + '&funcao_js=parent.js_mostrainscr', 'Pesquisa', false);
        }
    }
    
    function js_mostrainscr(chave){
        document.form1.q02_inscr.value = chave;
        db_iframe_inscr.hide();
    }
    
    function js_pesquisac58_sequencial(mostra){
        if (mostra == true) {
            js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr&filtro=receita', 'Pesquisa', true, '0', '1');
        }
        else {
            if (document.form1.c58_sequencial.value != '') {
                js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', 'func_concarpeculiar.php?pesquisa_chave=' + document.form1.c58_sequencial.value + '&funcao_js=parent.js_mostraconcarpeculiar&filtro=receita', 'Pesquisa', false);
            }
            else {
                document.form1.c58_descr.value = '';
            }
        }
    }
    
    function js_mostraconcarpeculiar(chave, erro){
        document.form1.c58_descr.value = chave;
        if (erro == true) {
            document.form1.c58_sequencial.focus();
            document.form1.c58_sequencial.value = '';
        }
    }
    
    function js_mostraconcarpeculiar1(chave1, chave2){
        document.form1.c58_sequencial.value = chave1;
        document.form1.c58_descr.value = chave2;
        db_iframe_concarpeculiar.hide();
    }


function js_mostraAgrupar(id){
  if(id==1){
    document.getElementById("agr").style.display='none';
  }else{
    document.getElementById("agr").style.display='';
  }
  js_mostraQuebrar("N");
  document.form1.agrupar.value= "N";

}

function js_mostraQuebrar(qb){
  if(qb=="N"){
    document.getElementById("queb").style.display='none'; 
  }else{
    document.getElementById("queb").style.display=''; 
  }
                        
}
js_mostraQuebrar(document.form1.quebrar.value);
</script>