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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');

if (isset($z01_i_cgsund)) {
  
  $oDaoVacVacinadose = db_utils::getdao('vac_vacinadose');
  $oDaoCgsUnd        = db_utils::getdao('cgs_und');

  /* Bloco que busca a data de nascimento do CGS */
  $sSql = $oDaoCgsUnd->sql_query_file($z01_i_cgsund, 'z01_d_nasc');
  $rs   = $oDaoCgsUnd->sql_record($sSql);
  if ($oDaoCgsUnd->numrows > 0) {

    $oDados = db_utils::fieldsmemory($rs, 0);
    if (empty($oDados->z01_d_nasc)) {

      $sMsg  = 'Nenhuma informação para a data de nascimento do CGS $z01_i_cgsund encontrada!. \n';
      $sMsg .= 'É necessário lançar a data de nascimento para este CGS';
      
  
    } else {
      
      $dNasc = $oDados->z01_d_nasc;
      $dAtual = date('d/m/Y', db_getsession('DB_datausu'));
      $aAtual = explode('/', $dAtual);
      
      /* Bloco que busca a informação das vacinas e doses */
      $sCampos  = "vc05_c_descr as dl_calendario, vc07_c_nome  as dl_vacina, vc03_c_descr as dl_dose,";
      $sCampos .= " (extract(day FROM z01_d_nasc + (vc07_i_faixainiano::char || ' year '||vc07_i_faixainimes||";
      $sCampos .= "' months '||vc07_i_faixainidias||' days')::interval)"; 
      $sCampos .= " ||'/'|| ";
      $sCampos .= " extract(month FROM z01_d_nasc + (vc07_i_faixainiano::char || ' year '||vc07_i_faixainimes||";
      $sCampos .= "' months '||vc07_i_faixainidias||' days')::interval)";        
      $sCampos .= " ||'/'|| ";
      $sCampos .= " extract(year FROM z01_d_nasc + (vc07_i_faixainiano::char || ' year '||vc07_i_faixainimes||";
      $sCampos .= "' months '||vc07_i_faixainidias||' days')::interval)";
      $sCampos .= " ||' - '|| ";
      $sCampos .= " case when (vc07_i_faixafimano::char ||'-'||vc07_i_faixafimmes||'-'||vc07_i_faixafimdias) = '0-0-0'";
      $sCampos .= "   then ";
      $sCampos .= "     'indefinida' ";
      $sCampos .= "   else ";
      $sCampos .= "     (extract(day FROM z01_d_nasc + (vc07_i_faixafimano::char || ' year '||vc07_i_faixafimmes||";
      $sCampos .= "' months '||vc07_i_faixafimdias||' days')::interval)"; 
      $sCampos .= "       ||'/'|| ";
      $sCampos .= "      extract(month FROM z01_d_nasc + (vc07_i_faixafimano::char || ' year '||vc07_i_faixafimmes||";
      $sCampos .= "' months '||vc07_i_faixafimdias||' days')::interval)";        
      $sCampos .= "       ||'/'|| ";
      $sCampos .= "      extract(year FROM z01_d_nasc + (vc07_i_faixafimano::char || ' year '||vc07_i_faixafimmes||";
      $sCampos .= "' months '||vc07_i_faixafimdias||' days')::interval))";
      $sCampos .= "   end)::char(40) as dl_periodo_de_aplicacao,";
      $sCampos .= "   (select vc16_d_data from vac_aplica where vc16_i_dosevacina = vc07_i_codigo limit 1) "; 
      $sCampos .= " as dl_aplicacao,";
      $sCampos .= "   (select vc16_t_obs  from vac_aplica where vc16_i_dosevacina = vc07_i_codigo limit 1) ";
      $sCampos .= " as dl_observacao";
      $sInner   = " inner join vac_dose       on vc03_i_codigo     = vc07_i_dose";
      $sInner  .= " inner join vac_calendario on vc07_i_calendario = vc07_i_calendario";
      $sInner  .= " inner join cgs_und        on z01_i_cgsund      = z01_i_cgsund";
      $sWhere   = " where z01_i_cgsund = $z01_i_cgsund ";
      $sOrder   = ' order by vc05_i_codigo,vc07_i_faixainiano, vc07_i_faixainimes, vc07_i_faixainidias ';
      $sSql     = $oDaoVacVacinadose->sql_query_file(null, $sCampos).$sInner.$sWhere.$sOrder;
      
    }

  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br>
  <table width="750" border="0" cellspacing="0" cellpadding="0">
    <tr> 
      <td align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
          <fieldset style='width: 98%;'> <legend><b>Agendamentos do Paciente</b></legend> 
            <table border="0" width="90%">
              <tr>
                <td>
                  <?
                    db_input('z01_i_cgsund', 10, '', true, 'hidden', 3, '');
                    if ($sSql != "") {
                       
                      global $cor1;
                      global $cor2;
                      $cor1 = "#FFFAF0";
                      $cor2 = "#FFFAF0";
                      db_lovrot($sSql, $iLinhas, "()", "", "");
                      
                    }
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>
        </center>
      </td>
    </tr>
  </table>
</center>
</body>
</html>