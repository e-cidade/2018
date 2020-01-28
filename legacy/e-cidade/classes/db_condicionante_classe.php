<?php
/**
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

//MODULO: meioambiente
//CLASSE DA ENTIDADE condicionante
class cl_condicionante { 
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
   var $am10_sequencial = 0; 
   var $am10_descricao = null; 
   var $am10_padrao = 'f'; 
   var $am10_vinculatodasatividades = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 am10_sequencial = int4 = Condicionante 
                 am10_descricao = text = Desrição 
                 am10_padrao = bool = Padrão 
                 am10_vinculatodasatividades = bool = Vincula Todas Atividade 
                 ";
   //funcao construtor da classe 
   function cl_condicionante() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("condicionante"); 
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
       $this->am10_sequencial = ($this->am10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am10_sequencial"]:$this->am10_sequencial);
       $this->am10_descricao = ($this->am10_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["am10_descricao"]:$this->am10_descricao);
       $this->am10_padrao = ($this->am10_padrao == "f"?@$GLOBALS["HTTP_POST_VARS"]["am10_padrao"]:$this->am10_padrao);
       $this->am10_vinculatodasatividades = ($this->am10_vinculatodasatividades == "f"?@$GLOBALS["HTTP_POST_VARS"]["am10_vinculatodasatividades"]:$this->am10_vinculatodasatividades);
     }else{
       $this->am10_sequencial = ($this->am10_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am10_sequencial"]:$this->am10_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($am10_sequencial = null){
      $this->atualizacampos();
     if($this->am10_descricao == null ){ 
       $this->erro_sql = " Campo Desrição não informado.";
       $this->erro_campo = "am10_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am10_padrao == null ){ 
       $this->erro_sql = " Campo Padrão não informado.";
       $this->erro_campo = "am10_padrao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am10_vinculatodasatividades == null ){ 
       $this->erro_sql = " Campo Vincula Todas Atividade não informado.";
       $this->erro_campo = "am10_vinculatodasatividades";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am10_sequencial == "" || $am10_sequencial == null ){
       $result = db_query("select nextval('condicionante_am10_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: condicionante_am10_sequencial_seq do campo: am10_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->am10_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from condicionante_am10_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am10_sequencial)){
         $this->erro_sql = " Campo am10_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am10_sequencial = $am10_sequencial; 
       }
     }
     if(($this->am10_sequencial == null) || ($this->am10_sequencial == "") ){ 
       $this->erro_sql = " Campo am10_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into condicionante(
                                       am10_sequencial 
                                      ,am10_descricao 
                                      ,am10_padrao 
                                      ,am10_vinculatodasatividades 
                       )
                values (
                                $this->am10_sequencial 
                               ,'$this->am10_descricao' 
                               ,'$this->am10_padrao' 
                               ,'$this->am10_vinculatodasatividades' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Condicionante ($this->am10_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Condicionante já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Condicionante ($this->am10_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am10_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am10_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20846,'$this->am10_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3751,20846,'','".AddSlashes(pg_result($resaco,0,'am10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3751,20847,'','".AddSlashes(pg_result($resaco,0,'am10_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3751,20848,'','".AddSlashes(pg_result($resaco,0,'am10_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3751,21322,'','".AddSlashes(pg_result($resaco,0,'am10_vinculatodasatividades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($am10_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update condicionante set ";
     $virgula = "";
     if(trim($this->am10_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am10_sequencial"])){ 
       $sql  .= $virgula." am10_sequencial = $this->am10_sequencial ";
       $virgula = ",";
       if(trim($this->am10_sequencial) == null ){ 
         $this->erro_sql = " Campo Condicionante não informado.";
         $this->erro_campo = "am10_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am10_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am10_descricao"])){ 
       $sql  .= $virgula." am10_descricao = '$this->am10_descricao' ";
       $virgula = ",";
       if(trim($this->am10_descricao) == null ){ 
         $this->erro_sql = " Campo Desrição não informado.";
         $this->erro_campo = "am10_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am10_padrao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am10_padrao"])){ 
       $sql  .= $virgula." am10_padrao = '$this->am10_padrao' ";
       $virgula = ",";
       if(trim($this->am10_padrao) == null ){ 
         $this->erro_sql = " Campo Padrão não informado.";
         $this->erro_campo = "am10_padrao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am10_vinculatodasatividades)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am10_vinculatodasatividades"])){ 
       $sql  .= $virgula." am10_vinculatodasatividades = '$this->am10_vinculatodasatividades' ";
       $virgula = ",";
       if(trim($this->am10_vinculatodasatividades) == null ){ 
         $this->erro_sql = " Campo Vincula Todas Atividade não informado.";
         $this->erro_campo = "am10_vinculatodasatividades";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am10_sequencial!=null){
       $sql .= " am10_sequencial = $this->am10_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am10_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20846,'$this->am10_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am10_sequencial"]) || $this->am10_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3751,20846,'".AddSlashes(pg_result($resaco,$conresaco,'am10_sequencial'))."','$this->am10_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am10_descricao"]) || $this->am10_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3751,20847,'".AddSlashes(pg_result($resaco,$conresaco,'am10_descricao'))."','$this->am10_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am10_padrao"]) || $this->am10_padrao != "")
             $resac = db_query("insert into db_acount values($acount,3751,20848,'".AddSlashes(pg_result($resaco,$conresaco,'am10_padrao'))."','$this->am10_padrao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am10_vinculatodasatividades"]) || $this->am10_vinculatodasatividades != "")
             $resac = db_query("insert into db_acount values($acount,3751,21322,'".AddSlashes(pg_result($resaco,$conresaco,'am10_vinculatodasatividades'))."','$this->am10_vinculatodasatividades',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condicionante não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Condicionante não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($am10_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am10_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20846,'$am10_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3751,20846,'','".AddSlashes(pg_result($resaco,$iresaco,'am10_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3751,20847,'','".AddSlashes(pg_result($resaco,$iresaco,'am10_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3751,20848,'','".AddSlashes(pg_result($resaco,$iresaco,'am10_padrao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3751,21322,'','".AddSlashes(pg_result($resaco,$iresaco,'am10_vinculatodasatividades'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from condicionante
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am10_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am10_sequencial = $am10_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Condicionante não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am10_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Condicionante não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am10_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am10_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:condicionante";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($am10_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from condicionante ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am10_sequencial)) {
         $sql2 .= " where condicionante.am10_sequencial = $am10_sequencial "; 
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
   public function sql_query_file ($am10_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from condicionante ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am10_sequencial)){
         $sql2 .= " where condicionante.am10_sequencial = $am10_sequencial "; 
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
