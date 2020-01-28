<?
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


function db_sysexportaagata($sArquivoXml) {

  // Select para Buscar Dicionário de Dados do DBPortal
  $sSql  = "  select db_sysmodulo.nomemod, ";
  $sSql .= "         db_sysarquivo.nomearq, ";
  $sSql .= "         db_sysarquivo.rotulo as rotarq, ";
  $sSql .= "         db_syscampo.nomecam, ";
  $sSql .= "         db_syscampo.rotulo as rotcam ";
  $sSql .= "    from db_sysarquivo ";
  $sSql .= "         inner join db_sysarqmod  on db_sysarqmod.codarq  = db_sysarquivo.codarq ";
  $sSql .= "         inner join db_sysmodulo  on db_sysmodulo.codmod  = db_sysarqmod.codmod ";
  $sSql .= "         inner join db_sysarqcamp on db_sysarqcamp.codarq = db_sysarquivo.codarq ";
  $sSql .= "         inner join db_syscampo   on db_syscampo.codcam   = db_sysarqcamp.codcam ";
  $sSql .= "   where db_sysmodulo.ativo is true ";
  $sSql .= "order by db_sysmodulo.nomemod, ";
  $sSql .= "         db_sysarquivo.nomearq, ";
  $sSql .= "         db_sysarqcamp.seqarq ";

  $sXml = "";

  $result  = db_query($sSql);
  $numrows = pg_num_rows($result);

  if($numrows == 0) {
    return $sXml;
  }

  $sXml  = "<?xml version=\"1.0\"?>\n";
  $sXml .= "<tables xmlns:dic='http://agata.dalloglio.net'>\n\n";


  $sTabela  = "";
  $lAbreTag = false;

  for($x=0; $x<$numrows; $x++) {
    $nomemod  = trim(pg_result($result, $x, "nomemod"));
    $nomearq  = trim(pg_result($result, $x, "nomearq"));
    $rotarq   = trim(pg_result($result, $x, "rotarq"));
    $nomecam  = trim(pg_result($result, $x, "nomecam"));
    $rotcam   = trim(pg_result($result, $x, "rotcam"));

    if($sTabela <> $nomearq) {

      if($lAbreTag==true) {
        // Fechar Tags
        $sXml .= "        </dic:fields>\n";
        $sXml .= "    </dic:$sTabela>\n\n";
      }
      $sXml .= "    <dic:$nomearq>\n";
      $sXml .= "        <dic:groups>\n";
      $sXml .= "            <dic:name>$nomemod</dic:name>\n";
      $sXml .= "        </dic:groups>\n\n";

      $rotarq  = trim($rotarq);
      $sTabela = trim($nomearq);
      $rotarq = ($rotarq=="")?$sTabela:"$rotarq ($sTabela)";

      $sXml .= "        <dic:label>$rotarq</dic:label>\n";

      $sXml .= "        <dic:fields>\n";

      $lAbreTag = true;
      $sTabela  = $nomearq;
    }

    // Nao esquecer de verificar se eh Chave Estrangeira

    $sXml .= "          <dic:$nomecam>\n";
    $sXml .= "              <dic:label>$rotcam</dic:label>\n";
    $sXml .= "          </dic:$nomecam>\n\n";

  }
  $sXml .= "        </dic:fields>\n";
  $sXml .= "    </dic:$sTabela>\n\n";
  $sXml .= "</tables>";
  return $sXml;
}

// classe de configuracao do agata
class cl_dbagata {

  var $nomeprojeto = "";
  var $relatorio = "";
  var $api;
  var $arquivo = "";

  function cl_dbagata($_relatorio='') {
    $this->api = new AgataAPI;
    $this->api->setLanguage('pt'); //'en', 'pt', 'es', 'de', 'fr', 'it', 'se'

    if(!empty($_relatorio)) {
      $this->relatorio = $_relatorio;
      $this->api->setReportPath("dbagata/reports/$_relatorio");
    }

    global $codigo, $nomeinst,$logo,$ender,$munic,$uf,$telef,$email,$url;
    db_sel_instit();

    $this->api->setParameter('$db_codigoinst', "$codigo");
    $this->api->setParameter('$db_nomeinst', "$nomeinst");

    // A resolucao da Imagem deve ser 68 x 74 pixels
      $this->api->setParameter('$db_logo', "imagens/files/agata{$logo}");

    $this->api->setParameter('$db_enderinst', "$ender");

    $this->api->setParameter('$db_municinst', "$munic");
    $this->api->setParameter('$db_ufinst', "$uf");
    $this->api->setParameter('$db_foneinst', "$telef");
    $this->api->setParameter('$db_emailinst', "$email");
    $this->api->setParameter('$db_siteinst', "$url");

    /**
      *  query para disponibilizar a variavem de caminho do item de menu
     */
    $sSqlMenuAcess = "SELECT fc_montamenu(funcao) as menu from db_itensmenu where id_item =".db_getsession("DB_itemmenu_acessado");
    $rsMenuAcess   = db_query($sSqlMenuAcess);
    $sMenuAcess    = substr(pg_result($rsMenuAcess,0,"menu"), 0, 50);

    $this->api->setParameter('$db_item_menu_extenso',trim($sMenuAcess));


    $sDadosUsuarioFolha = "
    select nome, rh01_regist, rh37_descr, login
        from configuracoes.db_usuarios
        left join configuracoes.db_usuacgm on db_usuacgm.id_usuario = db_usuarios.id_usuario
        left join pessoal.rhpessoal     on rh01_numcgm = cgmlogin
        left join pessoal.rhpessoalmov  on rh02_regist = rh01_regist
                                       and rh02_anousu = fc_anofolha(fc_getsession('db_instit')::integer)
                                       and rh02_mesusu = fc_mesfolha(fc_getsession('db_instit')::integer)
                                       and rh02_instit = fc_getsession('db_instit')::integer
        left join pessoal.rhpesrescisao on rh02_seqpes = rh05_seqpes
        left join pessoal.rhfuncao      on rh02_funcao = rh37_funcao and rh02_instit = rh37_instit
    where db_usuarios.id_usuario = (fc_getsession('db_id_usuario'::text))::integer and rh05_seqpes is null";
    $rsDadosUsuarioFolha= db_query($sDadosUsuarioFolha);

    $this->api->setParameter('$db_nomeusu',          trim(pg_result($rsDadosUsuarioFolha, 0, "nome"       )) );
    $this->api->setParameter('$db_login',            trim(pg_result($rsDadosUsuarioFolha, 0, "login"       )) );
    $this->api->setParameter('$db_cargofolhausu',    trim(pg_result($rsDadosUsuarioFolha, 0, "rh37_descr" )) );
    $this->api->setParameter('$db_matriculafolhausu',trim(pg_result($rsDadosUsuarioFolha, 0, "rh01_regist")) );

    $sSqlDataExtenso = " SELECT fc_dataextenso('" . date("Y-m-d",db_getsession("DB_datausu")) . "') as data_atual_extenso; ";
    $rsDataExtenso = db_query($sSqlDataExtenso);

    $data_atual_extenso = trim(pg_result($rsDataExtenso, 0, "data_atual_extenso"));
    $this->api->setParameter('$db_data_atual_extenso', "$data_atual_extenso");

    $aProject = array();

    $aProject["host"] = db_getsession("DB_servidor").":".db_getsession("DB_porta");

    if(session_is_registered("DB_NBASE")){
      $this->api->setParameter('$db_base', db_getsession("DB_NBASE"));
      $aProject["name"] = db_getsession("DB_NBASE");
    } else {
      $this->api->setParameter('$db_base', db_getsession("DB_base"));
      $aProject["name"] = db_getsession("DB_base");
    }

    $aProject["user"] = db_getsession("DB_user");
    $aProject["pass"] = db_getsession("DB_senha");
    $aProject["type"] = "native-pgsql";
    $aProject["dict"] = "";

    $this->api->setDataSource($aProject);

	  $nome = @$GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"];
	  $nome = substr($nome,strrpos($nome,"/")+1);
    $this->api->setParameter('$db_programa',"$nome");

    $this->api->setParameter('$db_id_usuario', db_getsession("DB_id_usuario"));
    $this->api->setParameter('$db_login', db_getsession("DB_login"));
    $this->api->setParameter('$db_anousu',  db_getsession("DB_anousu"));
    $this->api->setParameter('$db_datausu', date("d-m-Y",db_getsession("DB_datausu")));
    $this->api->setParameter('$db_horausu', date("H:i:s"));

    $this->api->setParameter('$db_coddepto', db_getsession("DB_coddepto"));

    if(!isset($this->api->format)){
      $this->api->setFormat('pdf'); // 'pdf', 'txt', 'xml', 'html', 'csv', 'sxw'
    }

    $this->api->setLayout('dbseller');

  }

}


?>
