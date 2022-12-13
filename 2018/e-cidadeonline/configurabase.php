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


  /**
   * Seta variaveis do form
   */
  $aFields = array('instituicao', 'host', 'port', 'login', 'password', 'database', 'configsite');
  foreach ($aFields as $field) {
    $$field = isset($_POST[$field]) ? $_POST[$field] : '';
  }

  if (!empty($_POST )) {
    
    /**
     * Monta nova configuração de acordo com os campos enviados.
     */    

    $sFileContent  = "<?php                                           \n";
    $sFileContent .= " \$DB_INSTITUICAO ='{$instituicao}';            \n";
    $sFileContent .= " \$DB_SERVIDOR='{$host}';                       \n";
    $sFileContent .= " \$DB_BASEDADOS ='{$database}';                 \n";
    $sFileContent .= " \$DB_USUARIO='{$login}';                       \n";
    $sFileContent .= " \$DB_SENHA='{$password}';                      \n";
    $sFileContent .= " \$DB_PORTA='{$port}';                          \n";
    $sFileContent .= " global \$configsite;                           \n";
    $sFileContent .= " \$configsite ='{$configsite}';                 \n";
    
    /**
     * Abre o arquivo e substitui conteudo.
     */
    $sDir = 'libs/db_conn.php';
    $rsFile = fopen($sDir, 'w');
    fwrite($rsFile, $sFileContent);
    fclose($rsFile);

    $saved = true;

  } else {
    if (file_exists('libs/db_conn.php')) {
      require_once('libs/db_conn.php');

      /**
       * Se existir arquivo do database,
       * Pega a configuração e mostra no campos
       */
      $instituicao = $DB_INSTITUICAO;
      $host        = $DB_SERVIDOR;
      $database    = $DB_BASEDADOS;
      $login       = $DB_USUARIO;
      $password    = $DB_SENHA;
      $port        = $DB_PORTA;
    }
  }
?>

<html>
<head>
  <title>Configura&ccedil;&atilde;o de Base DBPref</title>
</head>
<body>

  <style type="text/css">

    body {
      background-color: #EFEFEF;
    }

    * {
      font-family: Arial;
    }

    form {
      margin: auto;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      position: absolute;
      width: 400px;
      font-size: 14px;
      height: 300px;
    }

    form div.input {
      line-height: 30px;
      height: 30px;
    }

    form div.input label {
      font-weight: bold;
      margin: 0px 3px;
      float: left;
      clear: both;
      width: 20%;
      text-align: right;
    }

    form div.input label::after{
      content: ":";
    }

    form div.input input, 
    form div.input select {
      height: 20px;
      line-height: 20px;
      border: 1px solid #CCC;
      border-radius: 3px;
      -moz-border-radius: 3px;
      outline: none;
      padding: 2px 3px;
      margin: 2px 0;
      width: 70%;
    }

    form div.input input[type="checkbox"] {
      height: 30px;
      border: none;
      padding-top: 0;
    } 

    form div.input select {
      -moz-box-sizing: content-box;
      -webkit-box-sizing: content-box;
      box-sizing: content-box;
    }

    form div.input input:focus {
      box-shadow: 0px 0px 3px 0px #DDD;  
    }

    form div.buttons {
      height: 40px;
      text-align: center;
    }

    div.buttons {
      margin: 10px 0;
      text-align: center;
    }

    div.buttons button,
    div.buttons a {
      height: 25px;
      margin: 2px 0;
      padding: 3px;
    }

  </style>

  <form method="post">

    <fieldset>
      <legend>Configura&ccedil;&atilde;o</legend>

      <div class="input">
        <label>Institui&ccedil;&atilde;o</label>
        <input type="text" name="instituicao" value="<?php echo $instituicao; ?>" /> 
      </div>

      <div class="input">
        <label>Host</label>
        <input type="text" name="host" value="<?php echo $host; ?>" /> 
      </div>

      <div class="input">
        <label>Port</label>
        <input type="text" name="port" value="<?php echo $port; ?>"/> 
      </div>

      <div class="input">
        <label>Login</label>
        <input type="text" name="login" value="<?php echo $login; ?>"/> 
      </div>

      <div class="input">
        <label>Password</label>
        <input type="text" name="password" value="<?php echo $password; ?>"/> 
      </div>

      <div class="input">
        <label>Database</label>
        <input type="text" name="database" value="<?php echo $database; ?>"/> 
      </div>

      <div class="input">
        <label>Configsite</label>
        <input type="text" name="configsite" value="<?php echo $configsite; ?>" /> 
      </div>

      <div class="buttons">
        <button type="submit" value="enviar">Salvar</button>
      </div>

    </fieldset>

  </form>

  <?php 

  if (isset($saved)) {
  ?>
    <script type="text/javascript">alert('Configuração salva com sucesso.')</script>
  <?php
  } 

  ?>

</body>
</html>