<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: caixa

class cl_remessacobrancaregistradarecibo {
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
   var $k148_sequencial = 0;
   var $k148_remessacobrancaregistrada = 0;
   var $k148_numpre = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 k148_sequencial = int4 = Sequencial
                 k148_remessacobrancaregistrada = int4 = Remessa Cobrança Registrada
                 k148_numpre = int4 = Numpre
                 ";
   //funcao construtor da classe
   function cl_remessacobrancaregistradarecibo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("remessacobrancaregistradarecibo");
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
     if($exclusao==false){
       $this->k148_sequencial = ($this->k148_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k148_sequencial"]:$this->k148_sequencial);
       $this->k148_remessacobrancaregistrada = ($this->k148_remessacobrancaregistrada == ""?@$GLOBALS["HTTP_POST_VARS"]["k148_remessacobrancaregistrada"]:$this->k148_remessacobrancaregistrada);
       $this->k148_numpre = ($this->k148_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k148_numpre"]:$this->k148_numpre);
     }else{
       $this->k148_sequencial = ($this->k148_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k148_sequencial"]:$this->k148_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($k148_sequencial){
      $this->atualizacampos();
     if($this->k148_remessacobrancaregistrada == null ){
       $this->erro_sql = " Campo Remessa Cobrança Registrada não informado.";
       $this->erro_campo = "k148_remessacobrancaregistrada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k148_numpre == null ){
       $this->erro_sql = " Campo Numpre não informado.";
       $this->erro_campo = "k148_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k148_sequencial == "" || $k148_sequencial == null ){
       $result = db_query("select nextval('remessacobrancaregistradarecibo_k148_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: remessacobrancaregistradarecibo_k148_sequencial_seq do campo: k148_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->k148_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from remessacobrancaregistradarecibo_k148_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k148_sequencial)){
         $this->erro_sql = " Campo k148_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k148_sequencial = $k148_sequencial;
       }
     }
     if(($this->k148_sequencial == null) || ($this->k148_sequencial == "") ){
       $this->erro_sql = " Campo k148_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into remessacobrancaregistradarecibo(
                                       k148_sequencial
                                      ,k148_remessacobrancaregistrada
                                      ,k148_numpre
                       )
                values (
                                $this->k148_sequencial
                               ,$this->k148_remessacobrancaregistrada
                               ,$this->k148_numpre
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "RemessaCobrancaRegistradaRecibo ($this->k148_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "RemessaCobrancaRegistradaRecibo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "RemessaCobrancaRegistradaRecibo ($this->k148_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k148_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k148_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,22107,'$this->k148_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3982,22107,'','".AddSlashes(pg_result($resaco,0,'k148_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3982,22108,'','".AddSlashes(pg_result($resaco,0,'k148_remessacobrancaregistrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3982,22109,'','".AddSlashes(pg_result($resaco,0,'k148_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($k148_sequencial=null) {
      $this->atualizacampos();
     $sql = " update remessacobrancaregistradarecibo set ";
     $virgula = "";
     if(trim($this->k148_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k148_sequencial"])){
       $sql  .= $virgula." k148_sequencial = $this->k148_sequencial ";
       $virgula = ",";
       if(trim($this->k148_sequencial) == null ){
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "k148_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k148_remessacobrancaregistrada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k148_remessacobrancaregistrada"])){
       $sql  .= $virgula." k148_remessacobrancaregistrada = $this->k148_remessacobrancaregistrada ";
       $virgula = ",";
       if(trim($this->k148_remessacobrancaregistrada) == null ){
         $this->erro_sql = " Campo Remessa Cobrança Registrada não informado.";
         $this->erro_campo = "k148_remessacobrancaregistrada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k148_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k148_numpre"])){
       $sql  .= $virgula." k148_numpre = $this->k148_numpre ";
       $virgula = ",";
       if(trim($this->k148_numpre) == null ){
         $this->erro_sql = " Campo Numpre não informado.";
         $this->erro_campo = "k148_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k148_sequencial!=null){
       $sql .= " k148_sequencial = $this->k148_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->k148_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,22107,'$this->k148_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k148_sequencial"]) || $this->k148_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3982,22107,'".AddSlashes(pg_result($resaco,$conresaco,'k148_sequencial'))."','$this->k148_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k148_remessacobrancaregistrada"]) || $this->k148_remessacobrancaregistrada != "")
             $resac = db_query("insert into db_acount values($acount,3982,22108,'".AddSlashes(pg_result($resaco,$conresaco,'k148_remessacobrancaregistrada'))."','$this->k148_remessacobrancaregistrada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["k148_numpre"]) || $this->k148_numpre != "")
             $resac = db_query("insert into db_acount values($acount,3982,22109,'".AddSlashes(pg_result($resaco,$conresaco,'k148_numpre'))."','$this->k148_numpre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "RemessaCobrancaRegistradaRecibo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k148_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "RemessaCobrancaRegistradaRecibo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($k148_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($k148_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,22107,'$k148_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3982,22107,'','".AddSlashes(pg_result($resaco,$iresaco,'k148_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3982,22108,'','".AddSlashes(pg_result($resaco,$iresaco,'k148_remessacobrancaregistrada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3982,22109,'','".AddSlashes(pg_result($resaco,$iresaco,'k148_numpre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from remessacobrancaregistradarecibo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($k148_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " k148_sequencial = $k148_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "RemessaCobrancaRegistradaRecibo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k148_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "RemessaCobrancaRegistradaRecibo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k148_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k148_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:remessacobrancaregistradarecibo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($k148_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from remessacobrancaregistradarecibo ";
     $sql .= "      inner join remessacobrancaregistrada  on  remessacobrancaregistrada.k147_sequencial = remessacobrancaregistradarecibo.k148_remessacobrancaregistrada";
     $sql .= "      inner join cadconvenio  on  cadconvenio.ar11_sequencial = remessacobrancaregistrada.k147_convenio";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k148_sequencial)) {
         $sql2 .= " where remessacobrancaregistradarecibo.k148_sequencial = $k148_sequencial ";
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

  public function sql_query_recibos($sCampos = "*", $sOrdem = null, $sWhere = null, $sGroup = null) {

    $sSql  = "select {$sCampos}";
    $sSql .= "  from remessacobrancaregistradarecibo ";
    $sSql .= "       inner join remessacobrancaregistrada on remessacobrancaregistrada.k147_sequencial = remessacobrancaregistradarecibo.k148_remessacobrancaregistrada";
    $sSql .= "       inner join cadconvenio on cadconvenio.ar11_sequencial = remessacobrancaregistrada.k147_convenio";
    $sSql .= "       inner join cadtipoconvenio on cadtipoconvenio.ar12_sequencial = cadconvenio.ar11_cadtipoconvenio";
    $sSql .= "       inner join recibopaga on remessacobrancaregistradarecibo.k148_numpre = recibopaga.k00_numnov";
    $sSql .= "       inner join recibopagaboleto on recibopaga.k00_numnov = recibopagaboleto.k138_numnov";
    $sSql .= "       inner join tabrec on recibopaga.k00_receit = tabrec.k02_codigo";
    $sSql .= "       left  join arreinscr on arreinscr.k00_numpre = recibopaga.k00_numpre";
    $sSql .= "       left  join arrematric on arrematric.k00_numpre = recibopaga.k00_numpre";
    $sSql .= "       left  join arrebanco on arrebanco.k00_numpre = recibopaga.k00_numnov";

    if (!empty($sWhere)) {
      $sSql .= " where {$sWhere} ";
    }

    if (!empty($sGroup)) {
      $sSql .= " group by {$sGroup} ";
    }

    if (!empty($sOrdem)) {
      $sSql .= " order by {$sOrdem} ";
    }

    return $sSql;
  }

   // funcao do sql
   public function sql_query_file ($k148_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from remessacobrancaregistradarecibo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($k148_sequencial)){
         $sql2 .= " where remessacobrancaregistradarecibo.k148_sequencial = $k148_sequencial ";
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

}
