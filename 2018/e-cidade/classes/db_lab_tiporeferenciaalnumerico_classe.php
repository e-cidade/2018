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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_tiporeferenciaalnumerico
class cl_lab_tiporeferenciaalnumerico {
   // cria variaveis de erro
   var $rotulo     = null;
   var $query_sql  = null;
   var $numrows    = 0;
   var $numrows_incluir = 0;
   var $numrows_alterar = 0;
   var $numrows_excluir = 0;
   var $erro_status= null;
   var $erro_sql   = null;
   var $erro_banco = null;
   var $erro_msg   = null;
   var $erro_campo = null;
   var $pagina_retorno = null;
   // cria variaveis do arquivo
   var $la30_i_codigo = 0;
   var $la30_i_valorref = 0;
   var $la30_f_normalmin = 0;
   var $la30_f_normalmax = 0;
   var $la30_c_calculavel = null;
   var $la30_f_absurdomin = 0;
   var $la30_f_absurdomax = 0;
   var $la30_casasdecimaisapresentacao = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la30_i_codigo = int4 = Código
                 la30_i_valorref = int4 = Valor Referencial
                 la30_f_normalmin = float4 = Normal Mínimo
                 la30_f_normalmax = float4 = Normal Máximo
                 la30_c_calculavel = char(50) = Calculável
                 la30_f_absurdomin = float4 = Absurdo Mínimo
                 la30_f_absurdomax = float4 = Absurdo Máximo
                 la30_casasdecimaisapresentacao = int4 = Casas Decimais para Apresentação
                 ";
   //funcao construtor da classe
   function cl_lab_tiporeferenciaalnumerico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_tiporeferenciaalnumerico");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro
   function erro($mostra,$retorna) {
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
  function atualizacampos($exclusao=false) {

    if ($exclusao==false) {
       $this->la30_i_codigo     = ($this->la30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_i_codigo"]:$this->la30_i_codigo);
       $this->la30_i_valorref   = ($this->la30_i_valorref == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_i_valorref"]:$this->la30_i_valorref);
       $this->la30_f_normalmin  = ($this->la30_f_normalmin == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_f_normalmin"]:$this->la30_f_normalmin);
       $this->la30_f_normalmax  = ($this->la30_f_normalmax == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_f_normalmax"]:$this->la30_f_normalmax);
       $this->la30_c_calculavel  = ($this->la30_c_calculavel == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_c_calculavel"]:$this->la30_c_calculavel);
       $this->la30_f_absurdomin = ($this->la30_f_absurdomin == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomin"]:$this->la30_f_absurdomin);
       $this->la30_f_absurdomax = ($this->la30_f_absurdomax == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomax"]:$this->la30_f_absurdomax);
       $this->la30_casasdecimaisapresentacao = ($this->la30_casasdecimaisapresentacao === ""?@$GLOBALS["HTTP_POST_VARS"]["la30_casasdecimaisapresentacao"]:$this->la30_casasdecimaisapresentacao);
    }else{
      $this->la30_i_codigo = ($this->la30_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la30_i_codigo"]:$this->la30_i_codigo);
    }
  }
   // funcao para inclusao
  function incluir ($la30_i_codigo){
    $this->atualizacampos();
    if($this->la30_i_valorref == null ){
      $this->la30_i_valorref = "0";
    }
    if($this->la30_f_normalmin == null ){
      $this->la30_f_normalmin = "0";
    }
    if($this->la30_f_normalmax == null ){
      $this->la30_f_normalmax = "0";
    }
    if($this->la30_f_absurdomin == null ){
      $this->la30_f_absurdomin = "0";
    }
    if($this->la30_f_absurdomax == null ){
      $this->la30_f_absurdomax = "0";
    }
    if(  is_null($this->la30_casasdecimaisapresentacao) || $this->la30_casasdecimaisapresentacao === "" ){
      $this->la30_casasdecimaisapresentacao = " null ";
    }
    if($la30_i_codigo == "" || $la30_i_codigo == null ) {

      $result = db_query("select nextval('lab_tiporeferenciaalnumerico_la30_i_codigo_seq')");
      if($result==false){
        $this->erro_banco = str_replace("\n","",@pg_last_error());
        $this->erro_sql   = "Verifique o cadastro da sequencia: lab_tiporeferenciaalnumerico_la30_i_codigo_seq do campo: la30_i_codigo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
      $this->la30_i_codigo = pg_result($result,0,0);
    } else {

      $result = db_query("select last_value from lab_tiporeferenciaalnumerico_la30_i_codigo_seq");
      if (($result != false) && (pg_result($result,0,0) < $la30_i_codigo)){

        $this->erro_sql = " Campo la30_i_codigo maior que último número da sequencia.";
        $this->erro_banco = "Sequencia menor que este número.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      } else {
        $this->la30_i_codigo = $la30_i_codigo;
      }
    }
    if(($this->la30_i_codigo == null) || ($this->la30_i_codigo == "") ){

      $this->erro_sql = " Campo la30_i_codigo nao declarado.";
      $this->erro_banco = "Chave Primaria zerada.";
      $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
      $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      $this->erro_status = "0";
      return false;
    }

    //altera caractere @ para +
    if($this->la30_c_calculavel != "" && !preg_match( '/[a-zA-Z]+/',  $texto))
    	$this->la30_c_calculavel = str_replace('@', '+', $this->la30_c_calculavel);
    else
    	$this->la30_c_calculavel = '';

    $sql = "insert into lab_tiporeferenciaalnumerico(
                                      la30_i_codigo
                                     ,la30_i_valorref
                                     ,la30_f_normalmin
                                     ,la30_f_normalmax
                                     ,la30_c_calculavel
                                     ,la30_f_absurdomin
                                     ,la30_f_absurdomax
                                     ,la30_casasdecimaisapresentacao
                      )
               values (
                               $this->la30_i_codigo
                              ,$this->la30_i_valorref
                              ,$this->la30_f_normalmin
                              ,$this->la30_f_normalmax
                              ,'$this->la30_c_calculavel'
                              ,$this->la30_f_absurdomin
                              ,$this->la30_f_absurdomax
                              ,$this->la30_casasdecimaisapresentacao
                     )";
//echo $sql;die();
    $result = db_query($sql);
    if($result==false){
      $this->erro_banco = str_replace("\n","",@pg_last_error());
      if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
        $this->erro_sql   = "Tipo referencial numérico ($this->la30_i_codigo) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_banco = "Tipo referencial numérico já Cadastrado";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }else{
        $this->erro_sql   = "Tipo referencial numérico ($this->la30_i_codigo) nao Incluído. Inclusao Abortada.";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
      }
      $this->erro_status = "0";
      $this->numrows_incluir= 0;
      return false;
    }
    $this->erro_banco = "";
    $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
        $this->erro_sql .= "Valores : ".$this->la30_i_codigo;
    $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
    $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
    $this->erro_status = "1";
    $this->numrows_incluir= pg_affected_rows($result);
    $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
    if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
      && ($lSessaoDesativarAccount === false))) {
     $resaco = $this->sql_record($this->sql_query_file($this->la30_i_codigo  ));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16542,'$this->la30_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2905,16542,'','".AddSlashes(pg_result($resaco,0,'la30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16543,'','".AddSlashes(pg_result($resaco,0,'la30_i_valorref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16544,'','".AddSlashes(pg_result($resaco,0,'la30_f_normalmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16545,'','".AddSlashes(pg_result($resaco,0,'la30_f_normalmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16546,'','".AddSlashes(pg_result($resaco,0,'la30_c_calculavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16547,'','".AddSlashes(pg_result($resaco,0,'la30_f_absurdomin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,16548,'','".AddSlashes(pg_result($resaco,0,'la30_f_absurdomax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2905,20738,'','".AddSlashes(pg_result($resaco,0,'la30_casasdecimaisapresentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
   }
   return true;
  }
   // funcao para alteracao
   public function alterar ($la30_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lab_tiporeferenciaalnumerico set ";
     $virgula = "";
     if(trim($this->la30_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_i_codigo"])){
       $sql  .= $virgula." la30_i_codigo = $this->la30_i_codigo ";
       $virgula = ",";
       if(trim($this->la30_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "la30_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la30_i_valorref)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_i_valorref"])){
        if(trim($this->la30_i_valorref)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la30_i_valorref"])){
           $this->la30_i_valorref = "0" ;
        }
       $sql  .= $virgula." la30_i_valorref = $this->la30_i_valorref ";
       $virgula = ",";
     }
     if(trim($this->la30_f_normalmin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmin"])){
        if(trim($this->la30_f_normalmin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmin"])){
           $this->la30_f_normalmin = "0" ;
        }
       $sql  .= $virgula." la30_f_normalmin = $this->la30_f_normalmin ";
       $virgula = ",";
     }
     if(trim($this->la30_f_normalmax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmax"])){
        if(trim($this->la30_f_normalmax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmax"])){
           $this->la30_f_normalmax = "0" ;
        }
       $sql  .= $virgula." la30_f_normalmax = $this->la30_f_normalmax ";
       $virgula = ",";
     }
     if(trim($this->la30_c_calculavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_c_calculavel"])){
       $sql  .= $virgula." la30_c_calculavel = '$this->la30_c_calculavel' ";
       $virgula = ",";
     }
     if(trim($this->la30_f_absurdomin)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomin"])){
        if(trim($this->la30_f_absurdomin)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomin"])){
           $this->la30_f_absurdomin = "0" ;
        }
       $sql  .= $virgula." la30_f_absurdomin = $this->la30_f_absurdomin ";
       $virgula = ",";
     }
     if(trim($this->la30_f_absurdomax)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomax"])){
        if(trim($this->la30_f_absurdomax)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomax"])){
           $this->la30_f_absurdomax = "0" ;
        }
       $sql  .= $virgula." la30_f_absurdomax = $this->la30_f_absurdomax ";
       $virgula = ",";
     }

     if( is_null($this->la30_casasdecimaisapresentacao) || $this->la30_casasdecimaisapresentacao === "" ){
        $this->la30_casasdecimaisapresentacao = "null" ;
     }
     $sql  .= $virgula." la30_casasdecimaisapresentacao = $this->la30_casasdecimaisapresentacao ";
     $virgula = ",";

     $sql .= " where ";
     if($la30_i_codigo!=null){
       $sql .= " la30_i_codigo = $this->la30_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->la30_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16542,'$this->la30_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_i_codigo"]) || $this->la30_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2905,16542,'".AddSlashes(pg_result($resaco,$conresaco,'la30_i_codigo'))."','$this->la30_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_i_valorref"]) || $this->la30_i_valorref != "")
             $resac = db_query("insert into db_acount values($acount,2905,16543,'".AddSlashes(pg_result($resaco,$conresaco,'la30_i_valorref'))."','$this->la30_i_valorref',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmin"]) || $this->la30_f_normalmin != "")
             $resac = db_query("insert into db_acount values($acount,2905,16544,'".AddSlashes(pg_result($resaco,$conresaco,'la30_f_normalmin'))."','$this->la30_f_normalmin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_f_normalmax"]) || $this->la30_f_normalmax != "")
             $resac = db_query("insert into db_acount values($acount,2905,16545,'".AddSlashes(pg_result($resaco,$conresaco,'la30_f_normalmax'))."','$this->la30_f_normalmax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_c_calculavel"]) || $this->la30_c_calculavel != "")
             $resac = db_query("insert into db_acount values($acount,2905,16546,'".AddSlashes(pg_result($resaco,$conresaco,'la30_c_calculavel'))."','$this->la30_c_calculavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomin"]) || $this->la30_f_absurdomin != "")
             $resac = db_query("insert into db_acount values($acount,2905,16547,'".AddSlashes(pg_result($resaco,$conresaco,'la30_f_absurdomin'))."','$this->la30_f_absurdomin',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_f_absurdomax"]) || $this->la30_f_absurdomax != "")
             $resac = db_query("insert into db_acount values($acount,2905,16548,'".AddSlashes(pg_result($resaco,$conresaco,'la30_f_absurdomax'))."','$this->la30_f_absurdomax',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["la30_casasdecimaisapresentacao"]) || $this->la30_casasdecimaisapresentacao != "")
             $resac = db_query("insert into db_acount values($acount,2905,20738,'".AddSlashes(pg_result($resaco,$conresaco,'la30_casasdecimaisapresentacao'))."','$this->la30_casasdecimaisapresentacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);

     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo referencial numérico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo referencial numérico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($la30_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($la30_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16542,'$la30_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2905,16542,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16543,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_i_valorref'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16544,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_f_normalmin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16545,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_f_normalmax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16546,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_c_calculavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16547,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_f_absurdomin'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,16548,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_f_absurdomax'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2905,20738,'','".AddSlashes(pg_result($resaco,$iresaco,'la30_casasdecimaisapresentacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from lab_tiporeferenciaalnumerico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($la30_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " la30_i_codigo = $la30_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipo referencial numérico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la30_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tipo referencial numérico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la30_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:lab_tiporeferenciaalnumerico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($la30_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from lab_tiporeferenciaalnumerico ";
     $sql .= "      left  join lab_valorreferencia  on  lab_valorreferencia.la27_i_codigo = lab_tiporeferenciaalnumerico.la30_i_valorref";
     $sql .= "      left  join lab_undmedida  on  lab_undmedida.la13_i_codigo = lab_valorreferencia.la27_i_unidade";
     $sql .= "      inner join lab_atributo  on  lab_atributo.la25_i_codigo = lab_valorreferencia.la27_i_atributo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la30_i_codigo)) {
         $sql2 .= " where lab_tiporeferenciaalnumerico.la30_i_codigo = $la30_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql
   public function sql_query_file ($la30_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from lab_tiporeferenciaalnumerico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la30_i_codigo)){
         $sql2 .= " where lab_tiporeferenciaalnumerico.la30_i_codigo = $la30_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

function sql_query_dados_referencia ( $la30_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
    $sql = "select ";
    if($campos != "*" ){
      $campos_sql = split("#",$campos);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }else{
      $sql .= $campos;
    }
    $sql .= " from lab_tiporeferenciaalnumerico ";
    $sql .= "      left  join tiporeferenciaalnumericofaixaidade on la59_tiporeferencialnumerico = la30_i_codigo ";
    $sql .= "      left  join tiporeferenciaalnumericosexo       on la60_tiporeferencialnumerico = la30_i_codigo ";
    $sql .= "      left  join tiporeferenciacalculo              on la61_tiporeferencialnumerico = la30_i_codigo ";


    $sql2 = "";
    if($dbwhere==""){
      if($la30_i_codigo!=null ){
        $sql2 .= " where lab_tiporeferenciaalnumerico.la30_i_codigo = $la30_i_codigo ";
      }
    }else if($dbwhere != ""){
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if($ordem != null ){
      $sql .= " order by ";
      $campos_sql = split("#",$ordem);
      $virgula = "";
      for($i=0;$i<sizeof($campos_sql);$i++){
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

}
