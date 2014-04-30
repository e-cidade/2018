<?php
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
  require_once("classes/db_aguabase_classe.php");
  require_once("dbforms/db_funcoes.php");
  require_once("libs/db_app.utils.php");
    
  db_postmemory($HTTP_POST_VARS);
  
  $claguabase = new cl_aguabase;
  $claguabase->rotulo->label();
  
  $clrotulo = new rotulocampo;
  $clrotulo->label('z01_nome');
  $clrotulo->label('z01_numcgm');
  
?>
<html>
  <head>
    <?php 
      db_app::load('scripts.js, estilos.css');
    ?>
  </head>
  <body bgcolor="#CCCCCC" onLoad="document.form1.x01_matric.focus();" >
    <form name="form1" action="" method="post" onSubmit="return js_verificaMatricula();">
      <center>
        <fieldset style="margin: 50px auto 0 auto; width: 700px; height: 180px;">
          <legend>
            <strong>Emissão Parcial de Carnês</strong>
          </legend>
          <br />
          <table>
            <tr> 
              <td width="110px" title="<?php echo $Tz01_nunmcgm?>"> 
                <?php
                  db_ancora('<strong>Matricula:</strong>', 'js_mostranomes(true);', 4);
                ?>
              </td>
              <td width=""> 
                <?php
                  db_input("x01_matric", 10, $Ix01_matric, true, 'text', 4, " onchange='js_mostranomes(false);' ");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <?php
                  db_ancora('<strong>Nome:</strong>','js_mostranomes(true);', 4);
                ?>
              </td>
              <td>
                <?php
                  db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Ano:</strong>
              </td>
              <td>
                <?php
                  $result = pg_query("select " . db_getsession("DB_anousu") . "as j18_anousu");
                  
                  if (pg_numrows($result) > 0) {
                ?>
               <select name="anousu">
                 <?php
                   for($i = 0;$i < pg_numrows($result); $i++) {
                    
                     db_fieldsmemory($result, $i);
                 ?>
                 <option value='<?php echo $j18_anousu;?>'><?php echo $j18_anousu;?></option>
                 <?php
                   }
                 ?>
               </select>
               <?php
                 }
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Mês Inicial:</strong>
              </td>
              <td>
                <?php
                  if (!isset($mesini)) {
                    
                    $mesini = db_subdata(db_getsession("DB_datausu"), "m", "t");
                  }
                  $result = array ("1"  => "Janeiro",
                  		"2"  => "Feveireiro",
                  		"3"  => "Março",
                  		"4"  => "Abril",
                  		"5"  => "Maio",
                  		"6"  => "Junho",
                  		"7"  => "Julho",
                  		"8"  => "Agosto",
                  		"9"  => "Setembro",
                  		"10" => "Outubro",
                  		"11" => "Novembro",
                  		"12" => "Dezembro");
                  
                  db_select("mesini", $result, true, 1, "", "", "", "", "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Mês Final:</strong>
              </td>
              <td>
                <?php
                  if (!isset($mesfim)) {
                  
                    $mesfim = db_subdata(db_getsession("DB_datausu"), "m", "t");
                  }
                  $result = array ("1"  => "Janeiro",
                                    "2"  => "Feveireiro",
                                    "3"  => "Março",
                                    "4"  => "Abril",
                                    "5"  => "Maio",
                                    "6"  => "Junho",
                                    "7"  => "Julho",
                                    "8"  => "Agosto",
                                    "9"  => "Setembro",
                                    "10" => "Outubro",
                                    "11" => "Novembro",
                                    "12" => "Dezembro");
                  db_select("mesfim", $result, true, 1, "", "", "", "", "");
                ?>
              </td>
            </tr>
            <tr>
              <td>
                <strong>Tipo de Emissao:</strong>
              </td>
              <td>
                <?php
                  $xy = array ("pdf" => "PDF",
                                "txt" => "TXT");
                  
                  db_select('tipo_emissao', $xy, true, 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <br />
        <input name="processar" type="submit" id="processar" value="Processar">
      </center>
    </form>
  </body>
</html>

<?php
  db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit"));
?>

<script>
  function js_mostranomes(mostra) {
    
    if (mostra == true) {
     js_OpenJanelaIframe('top.corpo','db_iframe_aguabase',
                         'func_aguabase.php?funcao_js=parent.js_mostra1|x01_matric|z01_nome',
                         'Pesquisa', true, 20);
    } else {
      
      if (document.form1.x01_matric.value != '') {
        js_OpenJanelaIframe('top.corpo','db_iframe_aguabase',
                            'func_aguabase.php?pesquisa_chave=' + document.form1.x01_matric.value + 
                            '&funcao_js=parent.js_mostra', 'Pesquisa', false, 0);
      } else {
        document.form1.z01_nome.value = '';
      }
    }
  }
  
  function js_mostra(chave, erro) {
    
    document.form1.z01_nome.value = chave;
    
    if (erro == true) {
      
      document.form1.x01_matric.focus();
      document.form1.x01_matric.value = '';
    }
  }
  
  function js_mostra1(chave1, chave2) {
    
	  document.form1.x01_matric.value = chave1;
    document.form1.z01_nome.value   = chave2;
    db_iframe_aguabase.hide();
  }

</script>
<?php

  if (isset($processar)) {
?>
  <script>
    js_OpenJanelaIframe('top.corpo','db_iframe',
                        'agu4_emissaoparcial002.php?'           +
                        'matricula=<?php echo $x01_matric;?>'  +
                        '&exercicio=<?php echo $anousu;?>&'    +
                        'parcela_ini=<?php echo $mesini?>&'    +
                        'parcela_fim=<?php echo $mesfim?>&'    +
                        'tipo_emissao=<?php echo $tipo_emissao?>',
                        'Emissao de Carnes',true,20);
  </script>
  
<?php
  }
?>

<script>
  function js_verificaMatricula() {
    
    if (document.form1.x01_matric.value == "") {
      
      alert('Informe uma Matrícula.');
      return false;
    }
  }
</script>