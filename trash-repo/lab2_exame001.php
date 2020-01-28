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

require_once('libs/db_utils.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_utils.php');

$oDaoLabSetor      = db_utils::getdao('lab_labsetor');
$oDaoLabRequisicao = db_utils::getdao('lab_requisicao');
$oDaoLabExame      = db_utils::getdao('lab_exame');
$iUsuario          = db_getsession('DB_login');
$iDepto            = db_getsession('DB_coddepto');
$oRotulo           = new rotulocampo;
$oRotulo->label("la09_i_exame");
$oRotulo->label("la24_i_setor");
$oRotulo->label("la02_i_codigo");
$oRotulo->label("la24_c_descr");
$oRotulo->label("la22_i_codigo");
$oRotulo->label("z01_v_nome");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
    <table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
          <center>
            <br><br>
            <fieldset style='width: 45%; padding: 20px;'> <legend><b>Relat�rio de Exame</b></legend>
              <form name='form1'>
                <table>
                  <tr>
                    <td align="left">
                      <b> Per�odo:</b>
                    </td>
                    <td>
                      <?
                      db_inputdata('dData1', @$iDia1, @$iMes1, @$iAno1, true, 'text', 1, "");
                      ?>
                      A
                      <?
                      db_inputdata('dData2', @$iDia2, @$iMes2, @$iAno2, true, 'text', 1, "");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="Laborat&oacute;rio">
                      <?
                      db_ancora('<b>Laborat&oacute;rio:</b>', "js_pesquisala02_i_laboratorio(true);", "");
                      ?>
                    </td>
                    <td> 
                      <?
                      db_input('la02_i_codigo', 10, $Ila02_i_codigo, true, 'text', "",
                               " onchange='js_pesquisala02_i_laboratorio(false);'"
                              );
                      db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tla24_i_setor?>">
                      <?
                      db_ancora(@$Lla24_i_setor, "js_pesquisala24_i_setor(true);", "");
                      ?>
                    </td>
                    <td> 
                      <?
                      db_input('la24_i_setor', 10, $Ila24_i_setor, true, 'text', "",
                               " onchange='js_pesquisala24_i_setor(false);'"
                              );
                      db_input('la24_i_codigo', 10, '', true, 'hidden', '', '');
                      db_input('la23_c_descr', 50, @$Ila23_c_descr, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td nowrap title="<?=@$Tla09_i_exame?>">
                     <?
                     db_ancora(@$Lla09_i_exame, "js_pesquisala09_i_exame(true);", "");
                     ?>
                    </td>
                    <td> 
                      <?
                      db_input('la09_i_exame', 10, @$Ila09_i_exame, true, 'text', "", 
                               " onchange='js_pesquisala09_i_exame(false);'" 
                              );
                      db_input('la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>  
                  <tr>
                    <td align="left" nowrap title="<?=@$Tla22_i_codigo?>">
                      <?
                      db_ancora('<b>Requisi��o:</b>', "js_pesquisala22_i_codigo(true);", "");
                      ?>
                    </td>
                    <td> 
                      <?
                      db_input('la22_i_codigo', 10, @$Ila22_i_codigo, true, 'text', "", 
                               " onchange='js_pesquisala22_i_codigo(false);'"
                              );
                      db_input('z01_v_nome', 50, @$Iz01_v_nome, true, 'text', 3, '');
                      ?>
                    </td>
                  </tr>  
                  <tr>
                    <td align="left">
                      <b>Situa��o:</b>
                    </td>
                    <td>
                      <input type="checkbox" name="autorizado" id="autorizado" value="autorizado"> 
                      <b>Autorizados</b>  
                      <input type="checkbox" name="coletado" id="coletado" value="Coletado"> 
                      <b>Coletados  </b>
                      <input type="checkbox" name="confirmado" id="confirmado" value="confirmado"> 
                      <b>Confirmados</b>
                      <input type="checkbox" name="entregue" id="entregue" value="entregue">
                      <b>Entregues  </b>       
                    </td>
                  </tr>
                  <tr>
                    <td colspan='6' align='center' style="padding-top: 10px;">
                      <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
                    </td>
                  </tr>
                </table>
              </form>
            </fieldset>
          </center>
        </td>
      </tr>
    </table>
    <?
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>

<script>

function js_limpaCamposTrocaLab() {
 
  document.form1.la24_i_setor.value  = '';
  document.form1.la24_i_codigo.value = '';
  document.form1.la23_c_descr.value  = '';
  js_limpaCamposTrocaSetor();

}

function js_limpaCamposTrocaSetor() {

  document.form1.la09_i_exame.value = '';
  document.form1.la08_c_descr.value = '';

}

function js_pesquisala02_i_laboratorio(lMostra) {

  if (lMostra == true) {
    
    js_OpenJanelaIframe('',
                        'db_iframe_laboratorio',
                        'func_lab_laboratorio.php?checkLaboratorio=true'
                        + '&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr',
                        'Pesquisa',
                        true
                       );

  } else {

     if (document.form1.la02_i_codigo.value != '') { 
         
        js_OpenJanelaIframe('',
                            'db_iframe_laboratorio',
                            'func_lab_laboratorio.php?checkLaboratorio=true&pesquisa_chave='
                             + document.form1.la02_i_codigo.value+'&funcao_js=parent.js_mostralaboratorio',
                            'Pesquisa',
                            false
                           );

     } else {
       document.form1.la02_c_descr.value = ''; 
     }

  }

}

function js_mostralaboratorio(la02_c_descr, lErro) {

  document.form1.la02_c_descr.value = la02_c_descr; 
  if (lErro == true) {
     
    document.form1.la02_i_codigo.focus(); 
    document.form1.la02_i_codigo.value = ''; 

  }
  js_limpaCamposTrocaLab();

}

function js_mostralaboratorio1(la02_i_codigo, la02_c_descr) {

  document.form1.la02_i_codigo.value = la02_i_codigo;
  document.form1.la02_c_descr.value  = la02_c_descr;
  db_iframe_laboratorio.hide();
  js_limpaCamposTrocaLab();

}

function js_pesquisala24_i_setor(lMostra) {

  if (document.form1.la02_i_codigo.value == '') {

    alert('Escolha um laboratorio primeiro.');
    js_limpaCamposTrocaLab();
    return false;

  }
  sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';
  if (lMostra == true) {
    
    js_OpenJanelaIframe('',
                      'db_iframe_lab_labsetor',
                      'func_lab_labsetor.php?'
                      + sPesq
                      + 'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo',
                      'Pesquisa',
                      true
                     );

  } else {

    if (document.form1.la24_i_setor.value != '') { 
    
      js_OpenJanelaIframe('', 
                          'db_iframe_lab_labsetor',
                          'func_lab_labsetor.php?'+sPesq
                          + 'pesquisa_chave='+document.form1.la24_i_setor.value
                          + '&funcao_js=parent.js_mostralab_labsetor',
                          'Pesquisa',
                          false
                         );
      
    } else {
      document.form1.la23_c_descr.value = ''; 
    }

  }
  
}

function js_mostralab_labsetor(la23_c_descr, lErro, la24_i_codigo) {

  document.form1.la23_c_descr.value  = la23_c_descr; 
  document.form1.la24_i_codigo.value = la24_i_codigo; 
  if (lErro == true) { 
    
    document.form1.la24_i_setor.focus(); 
    document.form1.la24_i_setor.value  = ''; 
    document.form1.la24_i_codigo.value = ''; 

  }
  js_limpaCamposTrocaSetor();

}

function js_mostralab_labsetor1(la24_i_setor, la23_c_descr, la24_i_codigo) {

  document.form1.la24_i_setor.value  = la24_i_setor;
  document.form1.la24_i_codigo.value = la24_i_codigo;
  document.form1.la23_c_descr.value  = la23_c_descr;
  db_iframe_lab_labsetor.hide();
  js_limpaCamposTrocaSetor();

}
  
function js_pesquisala09_i_exame(lMostra) {

  if (document.form1.la24_i_setor.value == '') {

    alert('Escolha um setor primeiro.');
    js_limpaCamposTrocaSetor();
    return false;

  }
  sPesq = 'la24_i_codigo='+document.form1.la24_i_codigo.value+'&';
  if (document.form1.la24_i_codigo.value != '') {

    if (lMostra == true) {

      js_OpenJanelaIframe('',
                          'db_iframe_lab_setorexame',
                          'func_lab_setorexame.php?'
                          + sPesq
                          + '&la24_i_codigo='+document.form1.la24_i_codigo.value
                          + '&funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr',
                          'Pesquisa',
                          true
                         );
    } else {

      if (document.form1.la09_i_exame.value != '') { 

        js_OpenJanelaIframe('',
                            'db_iframe_lab_setorexame',
                            'func_lab_setorexame.php?'
                            + sPesq
                            + 'pesquisa_chave='+document.form1.la09_i_exame.value
                            + '&funcao_js=parent.js_mostralab_exame',
                            'Pesquisa',
                            false
                           );

      } else {
        document.form1.la08_c_descr.value = ''; 
      }

    }

  } else {
    alert("Escolha o Setor");  
  }

}

function js_mostralab_exame(la08_c_descr, lErro) {

  document.form1.la08_c_descr.value = la08_c_descr; 
  if (lErro == true) { 

    document.form1.la09_i_exame.focus(); 
    document.form1.la09_i_exame.value = ''; 

  }

}

function js_mostralab_exame1(la09_i_exame, la08_c_descr) {

  document.form1.la09_i_exame.value = la09_i_exame;
  document.form1.la08_c_descr.value = la08_c_descr;
  db_iframe_lab_setorexame.hide();

}

function js_mandaDados() {
 
  oF = document.form1;
  if (!js_validaDados()) {
    return false;
  }
  sDataini     = 'dDataini='+oF.dData1.value;
  sDatafim     = '&dDatafim='+oF.dData2.value;
  iLaboratorio = '&iLaboratorio='+oF.la02_i_codigo.value;
  iLabsetor    = '&iLabsetor='+oF.la24_i_codigo.value;
  iExame       = '&iExame='+oF.la09_i_exame.value;
  lColetado    = '';
  if (oF.coletado.checked != false) {
    lColetado = '&lColetado=1';
  }
  lConfirmado = '';
  if (oF.confirmado.checked != false) {
    lConfirmado = '&lConfirmado=1';
  }
  lEntregue = '';
  if (oF.entregue.checked != false) {
     lEntregue = '&lEntregue=1';
  }
  lAutorizado = '';
  if (oF.autorizado.checked != false) {
     lAutorizado = '&lAutorizado=1';
  }
  iRequisicao = '';
  if (oF.la22_i_codigo.value.trim() != "") {
    iRequisicao = '&iRequisicao='+oF.la22_i_codigo.value;
  }
  
  jan = window.open('lab2_exame002.php?'+sDataini+sDatafim+iLaboratorio+iLabsetor+iExame+lColetado+lConfirmado
                    +lEntregue+lAutorizado+iRequisicao,
                    '',
                    'width='+(screen.availWidth-5) +',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0, 0);
 
}

function js_validaDados(){

  if (!js_validadata()) {
    return false;
  }
  return true;

}

function js_validadata() {

  if (document.form1.dData1.value != '' && document.form1.dData2.value != '' ) {

    aIni = document.form1.dData1.value.split('/');
    aFim = document.form1.dData2.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);
    if (dFim < dIni) {
    
      alert("A data final n�o pode ser menor que a data inicial.");
      document.form1.data2.value = '';
      return false;

    }
    return true;

  } else {

    alert('Preencha o periodo.');
    return false

  }

}

function js_pesquisala22_i_codigo(lMostra){

  if (lMostra == true) {

    js_OpenJanelaIframe('',
                        'db_iframe_requisicao',
                        'func_lab_requisicao.php?&funcao_js='
                        + 'parent.js_mostrarequisicao1|la22_i_codigo|z01_v_nome',
                        'Pesquisa',
                        true
                       );
    
  } else {

    if (document.form1.la22_i_codigo.value != '') { 

      js_OpenJanelaIframe('',
                          'db_iframe_requisicao',
                          'func_lab_requisicao.php?&pesquisa_chave='+document.form1.la22_i_codigo.value
                          + '&funcao_js=parent.js_mostrarequisicao',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.z01_v_nome.value = ''; 
    }

  }

}

function js_mostrarequisicao(z01_v_nome, lErro) {

  document.form1.z01_v_nome.value = z01_v_nome; 
  if (lErro == true) { 

    document.form1.la22_i_codigo.focus(); 
    document.form1.la22_i_codigo.value = ''; 

  }
  js_limpaCamposTrocaLab();

}

function js_mostrarequisicao1(la22_i_codigo, z01_v_nome) {

  document.form1.la22_i_codigo.value = la22_i_codigo;
  document.form1.z01_v_nome.value    = z01_v_nome;
  db_iframe_requisicao.hide();    

}

</script>