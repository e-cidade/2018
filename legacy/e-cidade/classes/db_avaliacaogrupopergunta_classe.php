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
//MODULO: habitacao
//CLASSE DA ENTIDADE avaliacaogrupopergunta
class cl_avaliacaogrupopergunta { 
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
   var $db102_sequencial = 0; 
   var $db102_avaliacao = 0; 
   var $db102_descricao = null; 
   var $db102_identificador = null; 
   var $db102_identificadorcampo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db102_sequencial = int4 = Sequencial 
                 db102_avaliacao = int4 = Avaliação 
                 db102_descricao = varchar(50) = Descrição 
                 db102_identificador = varchar(50) = Identificador 
                 db102_identificadorcampo = varchar(255) = Identificador Campo 
                 ";
   //funcao construtor da classe 
   function cl_avaliacaogrupopergunta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacaogrupopergunta"); 
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
       $this->db102_sequencial = ($this->db102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_sequencial"]:$this->db102_sequencial);
       $this->db102_avaliacao = ($this->db102_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_avaliacao"]:$this->db102_avaliacao);
       $this->db102_descricao = ($this->db102_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_descricao"]:$this->db102_descricao);
       $this->db102_identificador = ($this->db102_identificador == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_identificador"]:$this->db102_identificador);
       $this->db102_identificadorcampo = ($this->db102_identificadorcampo == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_identificadorcampo"]:$this->db102_identificadorcampo);
     }else{
       $this->db102_sequencial = ($this->db102_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db102_sequencial"]:$this->db102_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($db102_sequencial){ 
      $this->atualizacampos();
     if($this->db102_avaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação não informado.";
       $this->erro_campo = "db102_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db102_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "db102_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db102_sequencial == "" || $db102_sequencial == null ){
       $result = db_query("select nextval('avaliacaogrupopergunta_db102_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: avaliacaogrupopergunta_db102_sequencial_seq do campo: db102_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db102_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from avaliacaogrupopergunta_db102_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db102_sequencial)){
         $this->erro_sql = " Campo db102_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db102_sequencial = $db102_sequencial; 
       }
     }
     if(($this->db102_sequencial == null) || ($this->db102_sequencial == "") ){ 
       $this->erro_sql = " Campo db102_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacaogrupopergunta(
                                       db102_sequencial 
                                      ,db102_avaliacao 
                                      ,db102_descricao 
                                      ,db102_identificador 
                                      ,db102_identificadorcampo 
                       )
                values (
                                $this->db102_sequencial 
                               ,$this->db102_avaliacao 
                               ,'$this->db102_descricao' 
                               ,'$this->db102_identificador' 
                               ,'$this->db102_identificadorcampo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação Grupo Pergunta ($this->db102_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação Grupo Pergunta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação Grupo Pergunta ($this->db102_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db102_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db102_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16912,'$this->db102_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,2981,16912,'','".AddSlashes(pg_result($resaco,0,'db102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2981,16913,'','".AddSlashes(pg_result($resaco,0,'db102_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2981,16914,'','".AddSlashes(pg_result($resaco,0,'db102_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2981,19377,'','".AddSlashes(pg_result($resaco,0,'db102_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2981,1009511,'','".AddSlashes(pg_result($resaco,0,'db102_identificadorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($db102_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update avaliacaogrupopergunta set ";
     $virgula = "";
     if(trim($this->db102_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db102_sequencial"])){ 
       $sql  .= $virgula." db102_sequencial = $this->db102_sequencial ";
       $virgula = ",";
       if(trim($this->db102_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial não informado.";
         $this->erro_campo = "db102_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db102_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db102_avaliacao"])){ 
       $sql  .= $virgula." db102_avaliacao = $this->db102_avaliacao ";
       $virgula = ",";
       if(trim($this->db102_avaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação não informado.";
         $this->erro_campo = "db102_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db102_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db102_descricao"])){ 
       $sql  .= $virgula." db102_descricao = '$this->db102_descricao' ";
       $virgula = ",";
       if(trim($this->db102_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "db102_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db102_identificador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db102_identificador"])){ 
       $sql  .= $virgula." db102_identificador = '$this->db102_identificador' ";
       $virgula = ",";
     }
     if(trim($this->db102_identificadorcampo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db102_identificadorcampo"])){ 
       $sql  .= $virgula." db102_identificadorcampo = '$this->db102_identificadorcampo' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db102_sequencial!=null){
       $sql .= " db102_sequencial = $this->db102_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->db102_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,16912,'$this->db102_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db102_sequencial"]) || $this->db102_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,2981,16912,'".AddSlashes(pg_result($resaco,$conresaco,'db102_sequencial'))."','$this->db102_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db102_avaliacao"]) || $this->db102_avaliacao != "")
             $resac = db_query("insert into db_acount values($acount,2981,16913,'".AddSlashes(pg_result($resaco,$conresaco,'db102_avaliacao'))."','$this->db102_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db102_descricao"]) || $this->db102_descricao != "")
             $resac = db_query("insert into db_acount values($acount,2981,16914,'".AddSlashes(pg_result($resaco,$conresaco,'db102_descricao'))."','$this->db102_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db102_identificador"]) || $this->db102_identificador != "")
             $resac = db_query("insert into db_acount values($acount,2981,19377,'".AddSlashes(pg_result($resaco,$conresaco,'db102_identificador'))."','$this->db102_identificador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["db102_identificadorcampo"]) || $this->db102_identificadorcampo != "")
             $resac = db_query("insert into db_acount values($acount,2981,1009511,'".AddSlashes(pg_result($resaco,$conresaco,'db102_identificadorcampo'))."','$this->db102_identificadorcampo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Grupo Pergunta não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Grupo Pergunta não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->db102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($db102_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($db102_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,16912,'$db102_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,2981,16912,'','".AddSlashes(pg_result($resaco,$iresaco,'db102_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2981,16913,'','".AddSlashes(pg_result($resaco,$iresaco,'db102_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2981,16914,'','".AddSlashes(pg_result($resaco,$iresaco,'db102_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2981,19377,'','".AddSlashes(pg_result($resaco,$iresaco,'db102_identificador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2981,1009511,'','".AddSlashes(pg_result($resaco,$iresaco,'db102_identificadorcampo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from avaliacaogrupopergunta
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($db102_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " db102_sequencial = $db102_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação Grupo Pergunta não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db102_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação Grupo Pergunta não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db102_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$db102_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacaogrupopergunta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaogrupopergunta ";
     $sql .= "      inner join avaliacao  on  avaliacao.db101_sequencial = avaliacaogrupopergunta.db102_avaliacao";
     $sql .= "      inner join avaliacaotipo  on  avaliacaotipo.db100_sequencial = avaliacao.db101_avaliacaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($db102_sequencial!=null ){
         $sql2 .= " where avaliacaogrupopergunta.db102_sequencial = $db102_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $db102_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacaogrupopergunta ";
     $sql2 = "";
     if($dbwhere==""){
       if($db102_sequencial!=null ){
         $sql2 .= " where avaliacaogrupopergunta.db102_sequencial = $db102_sequencial "; 
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
