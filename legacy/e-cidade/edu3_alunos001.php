<?php
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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );

$claluno       = new cl_aluno;
$clalunocurso  = new cl_alunocurso;
$clalunopossib = new cl_alunopossib;
$clhistorico   = new cl_historico;
$clmatricula   = new cl_matricula;
$clrotulo      = new rotulocampo;

$clrotulo->label("ed31_i_curso");
$claluno->rotulo->label();
$clalunocurso->rotulo->label();
$clalunopossib->rotulo->label();
$clhistorico->rotulo->label();

$db_opcao = 1;
$db_botao = true;

if( isset( $chavepesquisa ) ) {

  $campos = "aluno.*,
             censoufident.ed260_c_sigla as ufident,
             censoufnat.ed260_c_sigla as ufnat,
             censoufcert.ed260_c_sigla as ufcert,
             censoufend.ed260_c_sigla as ufend,
             censomunicnat.ed261_c_nome as municnat,
             censomuniccert.ed261_c_nome as municcert,
             censomunicend.ed261_c_nome as municend,
             censoorgemissrg.ed132_c_descr as orgemissrg,
             pais.ed228_c_descr";

  $result = $claluno->sql_record( $claluno->sql_query( "", $campos, "", "ed47_i_codigo = {$chavepesquisa}" ) );
  db_fieldsmemory( $result, 0 );

  $oDaoTipoSanguineo = new cl_tiposanguineo();
  $sSqlTipoSanguineo = $oDaoTipoSanguineo->sql_query_file("", "*", "sd100_sequencial", "");
  $rsTipoSanguineo   = $oDaoTipoSanguineo->sql_record($sSqlTipoSanguineo);
  $iLinhas           = $oDaoTipoSanguineo->numrows;

  $aTiposSanguineos = array();

  if ( isset( $rsTipoSanguineo ) && $iLinhas > 0) {

    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $oDados = db_utils::fieldsMemory($rsTipoSanguineo, $i);
      $aTiposSanguineos[$oDados->sd100_sequencial] = $oDados->sd100_tipo;
    }
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, prototype.js, strings.js");
    db_app::load("estilos.css");
  ?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: center;
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top" bgcolor="#CCCCCC">
        <fieldset style="width:97%">
          <legend>
            <label class="bold">Consulta de Alunos</label>
          </legend>
          <table border="0" width="100%" cellspacing="0" cellpading="0" bgcolor="#f3f3f3">
            <tr>
              <td>
                <fieldset style="background:#f3f3f3;padding:0px;border:2px solid #000000">
                  <legend class="cabec">
                    <label class="bold">Nome</label>
                  </legend>
                  <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
                    <tr>
                      <td style="font-size:18px;font-weight:bold;font-family:verdana;">
                       &nbsp;&nbsp;<?=$ed47_i_codigo?>&nbsp;&nbsp;-&nbsp;&nbsp;<?=$ed47_v_nome?>
                      </td>
                      <td align="right">
                        <input type="button" value="Fechar" id='btnFecharJanela' onclick="js_fechajanela();">&nbsp;&nbsp;
                        <input type="button" value="Imprimir" onclick="js_imprimir(<?=$chavepesquisa?>)">&nbsp;&nbsp;
                      </td>
                    </tr>
                  </table>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td>
                <table border="0" width="100%" cellspacing="0" cellpading="0">
                  <tr>
                    <td width="21%">
                      <fieldset style="height:167px;background:#f3f3f3;padding:0px;border:4px outset #000000">
                        <legend class="cabec">
                          <label class="bold">Foto</label>
                        </legend>
                        <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
                          <tr>
                            <td align="center">
                              <?php
                              if( $ed47_o_oid != 0 ) {

                                 $arquivo = "tmp/".$ed47_c_foto;

                                 db_query("begin");
                                 $lResultExport = pg_lo_export($ed47_o_oid, $arquivo, $conn);
                                 db_query("end");

                                 if (!$lResultExport) {
                                   db_msgbox("Erro ao recuperar o foto do aluno.");
                                 } elseif (!file_exists($arquivo)) {
                                   db_msgbox("Foto do aluno não encontrada.");
                                 }
                              } else {
                               $arquivo = "imagens/none1.jpeg";
                              }
                              ?>
                              <img src="<?=$arquivo?>" width="120" height="150" style="border:0px solid #000000">
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                    <td valign="top">
                      <fieldset style="background:#f3f3f3;border:2px solid #000000">
                        <legend class="cabec">
                          <label class="bold">Dados Pessoais</label>
                        </legend>
                        <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
                          <tr>
                            <td>
                              <?=$Led47_c_codigoinep?> <?=$ed47_c_codigoinep == "" ? "Não Informado" : $ed47_c_codigoinep?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_d_nasc?> <?=db_formatar($ed47_d_nasc,'d')?>
                              &nbsp;&nbsp;
                              <?=$Led47_i_censomunicnat?> <?=$ed47_i_censomunicnat == "" ? "Não Informado" : $municnat?>
                              &nbsp;&nbsp;
                              <?=$Led47_i_censoufnat?> <?=$ed47_i_censoufnat == "" ? "Não Informado" : $ufnat?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_i_nacion?> <?=$ed47_i_nacion == "1" ? "Brasileira" : ( $ed47_i_nacion == "2" ? "Brasileira no Exterior ou Naturalizado" : "Estrangeira" );?>
                              &nbsp;&nbsp;
                              <?=$Led47_i_pais?> <?=$ed228_c_descr?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_v_ender?> <?=$ed47_v_ender == "" ? "Não Informado" : $ed47_v_ender?>
                              &nbsp;&nbsp;
                              <?=$Led47_c_numero?> <?=$ed47_c_numero == "" ? "Não Informado" : $ed47_c_numero?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_v_bairro?> <?=$ed47_v_bairro == "" ? "Não Informado" : $ed47_v_bairro?>
                              &nbsp;&nbsp;
                              <?=$Led47_v_compl?> <?=$ed47_v_compl == "" ? "Não Informado" : $ed47_v_compl?>
                              &nbsp;&nbsp;
                              <?=$Led47_c_zona?> <?=$ed47_c_zona == "" ? "Não Informado" : $ed47_c_zona?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_i_censomunicend?> <?=$ed47_i_censomunicend == "" ? "Não Informado" : $municend?>
                              &nbsp;&nbsp;
                              <?=$Led47_i_censoufend?> <?=$ed47_i_censoufend == "" ? "Não Informado" : $ufend?>
                              &nbsp;&nbsp;
                              <?=$Led47_v_cep?> <?=$ed47_v_cep == "" ? "Não Informado" : $ed47_v_cep?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_v_sexo?> <?=$ed47_v_sexo == "M" ? "MASCULINO" : "FEMININO"?>
                              &nbsp;&nbsp;
                              <?=$Led47_i_estciv?>
                              <?php
                              if( $ed47_i_estciv == 1 ) {
                                echo "SOLTEIRO";
                              } else if( $ed47_i_estciv == 2 ) {
                                echo "CASADO";
                              } else if( $ed47_i_estciv == 3 ) {
                                echo "VIÚVO";
                              } else if( $ed47_i_estciv == 4 ) {
                                echo "DIVORCIADO";
                              } else {
                                echo "Não Informado";
                              }
                              ?>
                              <?=$Led47_c_raca?> <?=$ed47_c_raca?>

                              <?php
                                echo $Led47_tiposanguineo;
                                echo $ed47_tiposanguineo  == "" ? " Não Informado" : " " . $aTiposSanguineos[$ed47_tiposanguineo];
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <?=$Led47_v_telef?> <?=$ed47_v_telef == "" ? "Não Informado" : $ed47_v_telef?>
                              &nbsp;&nbsp;
                              <?=$Led47_v_telcel?> <?=$ed47_v_telcel == "" ? "Não Informado" : $ed47_v_telcel?>
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" colspan="2">
                      <table border="0" width="100%">
                        <tr align="center">
                          <td id="menu1"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu1').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu1').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=1"
                               target="iframe_dados">
                              Documentos
                            </a>
                          </td>
                          <td id="menu2"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu2').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu2').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=2"
                               target="iframe_dados">
                              Outras Informações
                            </a>
                          </td>
                          <td id="menu5"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu5').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu5').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=5"
                               target="iframe_dados">
                              Necessidades Especiais
                            </a>
                          </td>
                          <td id="menu3"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu3').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu3').style.border='2px outset #f3f3f3'">
                            <?php
                            $sSqlMatricula = $clmatricula->sql_query("","ed60_i_codigo","ed60_i_codigo desc limit 1"," ed60_i_aluno = $chavepesquisa AND ed60_c_ativa = 'S'");
                            $result1       = $clmatricula->sql_record( $sSqlMatricula );

                            if( $clmatricula->numrows > 0 ) {
                              db_fieldsmemory( $result1, 0 );
                            } else {
                              $ed60_i_codigo = 0;
                            }
                            ?>
                            <a id="matr"
                               style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>"
                               target="iframe_dados">
                              Matrículas
                            </a>
                          </td>
                          <td id="menu4"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu4').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu4').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=4"
                               target="iframe_dados">
                              Histórico
                            </a>
                          </td>
                          <td id="menu6"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu6').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu6').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=6"
                               target="iframe_dados">
                              Movimentação Escolar
                            </a>
                          </td>
                          <td id="menu7"
                              bgcolor="#444444"
                              style="border:2px outset #f3f3f3"
                              onmouseover="document.getElementById('menu7').style.border='2px inset #f3f3f3'"
                              onmouseout="document.getElementById('menu7').style.border='2px outset #f3f3f3'">
                            <a style="color:#DEB887;font-weight:bold;"
                               href="edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=7"
                               target="iframe_dados">
                              Consulta Faltas
                            </a>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td valign="top" colspan="2">
                      <iframe name="iframe_dados" src="" frameborder="0" width="100%" height="1400"></iframe>
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
</body>
</html>
<script>
var lAlunosDuplos = false;
<?php if ( isset($lAlunosDuplos) ) {?>
  lAlunosDuplos = true;
<?php } ?>

function js_imprimir( chave ) {

  jan = window.open('edu2_fichaaluno002.php?alunos='+chave,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_fechajanela() {
  
  if (lAlunosDuplos) {
    parent.db_iframe_aluno.hide();
  } else {
    
	  parent.document.getElementById('ed47_i_codigo').value   ='';
	  parent.document.getElementById('ed47_v_nome').value     ='';
	  parent.document.getElementById('ed47_v_pai').value      ='';
	  parent.document.getElementById('ed47_v_mae').value      ='';
	  parent.document.getElementById('ed56_i_escola').value   ='';
	  parent.document.getElementById('ed56_c_situacao').value ='';
	  parent.document.getElementById('ed31_i_curso').value    ='';
	  parent.document.getElementById('ed223_i_serie').value   ='';
	  parent.document.getElementById('ed47_i_codigo').focus();
	  parent.db_iframe_aluno.hide();
  }
}

iframe_dados.location.href = "edu3_alunos002.php?chavepesquisa=<?=$chavepesquisa?>&evento=3&ed60_i_codigo=<?=$ed60_i_codigo?>";
parent.db_iframe_aluno.liberarJanBTFechar('false');
parent.db_iframe_aluno.liberarJanBTMinimizar('false');
parent.db_iframe_aluno.liberarJanBTMaximizar('false');
</script>
<?php
if( isset( $_GET["fc_close"] ) ) {
?>
  <script>
    document.getElementById('btnFecharJanela').onclick=<?=$_GET["fc_close"]?>;
  </script>
<?php
}
?>