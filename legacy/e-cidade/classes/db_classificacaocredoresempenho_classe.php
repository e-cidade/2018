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

//MODULO: empenho
//CLASSE DA ENTIDADE classificacaocredoresempenho
class cl_classificacaocredoresempenho {
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
   var $cc31_sequencial = null;
   var $cc31_empempenho = null;
   var $cc31_classificacaocredores = null;
   var $cc31_justificativa = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 cc31_sequencial = int4 = Código
                 cc31_empempenho = int4 = Empenho
                 cc31_classificacaocredores = int4 = Classificação de Credores
                 cc31_justificativa = text = Justificativa
                 ";
   //funcao construtor da classe
   function cl_classificacaocredoresempenho() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("classificacaocredoresempenho");
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
   private function atualizaCampo($sNomeCampo) {

    if ($this->$sNomeCampo === null) {

      if (isset($GLOBALS["HTTP_POST_VARS"][$sNomeCampo])) {
        return $GLOBALS["HTTP_POST_VARS"][$sNomeCampo];
      }

      return null;
    }

    return $this->$sNomeCampo;
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->cc31_sequencial            = $this->atualizaCampo('cc31_sequencial');
       $this->cc31_empempenho            = $this->atualizaCampo('cc31_empempenho');
       $this->cc31_classificacaocredores = $this->atualizaCampo('cc31_classificacaocredores');
       $this->cc31_justificativa         = $this->atualizaCampo('cc31_justificativa');
     }else{
       $this->cc31_sequencial = $this->atualizaCampo('cc31_sequencial');
     }
   }
   // funcao para Inclusão
   function incluir ($cc31_sequencial){
     if($this->cc31_empempenho == null ){
       $this->erro_sql = " Campo Empenho não informado.";
       $this->erro_campo = "cc31_empempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->cc31_classificacaocredores == null ){
       $this->erro_sql = " Campo Classificação de Credores não informado.";
       $this->erro_campo = "cc31_classificacaocredores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($cc31_sequencial == "" || $cc31_sequencial == null ){
       $result = db_query("select nextval('classificacaocredoresempenho_cc31_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: classificacaocredoresempenho_cc31_sequencial_seq do campo: cc31_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->cc31_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from classificacaocredoresempenho_cc31_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $cc31_sequencial)){
         $this->erro_sql = " Campo Código maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->cc31_sequencial = $cc31_sequencial;
       }
     }
     if(($this->cc31_sequencial == null) || ($this->cc31_sequencial == "") ){
       $this->erro_sql = " Campo Código não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into classificacaocredoresempenho(
                                       cc31_sequencial
                                      ,cc31_empempenho
                                      ,cc31_classificacaocredores
                                      ,cc31_justificativa
                       )
                values (
                                $this->cc31_sequencial
                               ,$this->cc31_empempenho
                               ,$this->cc31_classificacaocredores
                               ,'$this->cc31_justificativa'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Empenho da Classificação de Credores ($this->cc31_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Empenho da Classificação de Credores já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Empenho da Classificação de Credores ($this->cc31_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc31_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc31_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21599,'$this->cc31_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3879,21599,'','".AddSlashes(pg_result($resaco,0,'cc31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3879,21600,'','".AddSlashes(pg_result($resaco,0,'cc31_empempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3879,21601,'','".AddSlashes(pg_result($resaco,0,'cc31_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3879,21602,'','".AddSlashes(pg_result($resaco,0,'cc31_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($cc31_sequencial=null) {
      $this->atualizacampos();
     $sql = " update classificacaocredoresempenho set ";
     $virgula = "";
     if(trim($this->cc31_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc31_sequencial"])){
       $sql  .= $virgula." cc31_sequencial = $this->cc31_sequencial ";
       $virgula = ",";
       if(trim($this->cc31_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "cc31_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc31_empempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc31_empempenho"])){
       $sql  .= $virgula." cc31_empempenho = $this->cc31_empempenho ";
       $virgula = ",";
       if(trim($this->cc31_empempenho) == null ){
         $this->erro_sql = " Campo Empenho não informado.";
         $this->erro_campo = "cc31_empempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc31_classificacaocredores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc31_classificacaocredores"])){
       $sql  .= $virgula." cc31_classificacaocredores = $this->cc31_classificacaocredores ";
       $virgula = ",";
       if(trim($this->cc31_classificacaocredores) == null ){
         $this->erro_sql = " Campo Classificação de Credores não informado.";
         $this->erro_campo = "cc31_classificacaocredores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->cc31_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["cc31_justificativa"])){
       $sql  .= $virgula." cc31_justificativa = '$this->cc31_justificativa' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($cc31_sequencial!=null){
       $sql .= " cc31_sequencial = $this->cc31_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->cc31_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21599,'$this->cc31_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc31_sequencial"]) || $this->cc31_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3879,21599,'".AddSlashes(pg_result($resaco,$conresaco,'cc31_sequencial'))."','$this->cc31_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc31_empempenho"]) || $this->cc31_empempenho != "")
             $resac = db_query("insert into db_acount values($acount,3879,21600,'".AddSlashes(pg_result($resaco,$conresaco,'cc31_empempenho'))."','$this->cc31_empempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc31_classificacaocredores"]) || $this->cc31_classificacaocredores != "")
             $resac = db_query("insert into db_acount values($acount,3879,21601,'".AddSlashes(pg_result($resaco,$conresaco,'cc31_classificacaocredores'))."','$this->cc31_classificacaocredores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["cc31_justificativa"]) || $this->cc31_justificativa != "")
             $resac = db_query("insert into db_acount values($acount,3879,21602,'".AddSlashes(pg_result($resaco,$conresaco,'cc31_justificativa'))."','$this->cc31_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho da Classificação de Credores não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Empenho da Classificação de Credores não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->cc31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->cc31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($cc31_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($cc31_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21599,'$cc31_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3879,21599,'','".AddSlashes(pg_result($resaco,$iresaco,'cc31_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3879,21600,'','".AddSlashes(pg_result($resaco,$iresaco,'cc31_empempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3879,21601,'','".AddSlashes(pg_result($resaco,$iresaco,'cc31_classificacaocredores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3879,21602,'','".AddSlashes(pg_result($resaco,$iresaco,'cc31_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from classificacaocredoresempenho
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($cc31_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " cc31_sequencial = $cc31_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Empenho da Classificação de Credores não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$cc31_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Empenho da Classificação de Credores não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$cc31_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$cc31_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:classificacaocredoresempenho";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($cc31_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from classificacaocredoresempenho ";
     $sql .= "      inner join empempenho  on  empempenho.e60_numemp = classificacaocredoresempenho.cc31_empempenho";
     $sql .= "      inner join classificacaocredores  on  classificacaocredores.cc30_codigo = classificacaocredoresempenho.cc31_classificacaocredores";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empempenho.e60_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = empempenho.e60_instit";
     $sql .= "      inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot = empempenho.e60_coddot";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = empempenho.e60_codcom";
     $sql .= "      inner join emptipo  on  emptipo.e41_codtipo = empempenho.e60_codtipo";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = empempenho.e60_concarpeculiar";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc31_sequencial)) {
         $sql2 .= " where classificacaocredoresempenho.cc31_sequencial = $cc31_sequencial ";
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
   public function sql_query_file ($cc31_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from classificacaocredoresempenho ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($cc31_sequencial)){
         $sql2 .= " where classificacaocredoresempenho.cc31_sequencial = $cc31_sequencial ";
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
