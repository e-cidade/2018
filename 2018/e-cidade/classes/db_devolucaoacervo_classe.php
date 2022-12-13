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

//MODULO: biblioteca
//CLASSE DA ENTIDADE devolucaoacervo
class cl_devolucaoacervo { 
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
   var $bi21_codigo = 0; 
   var $bi21_emprestimoacervo = 0; 
   var $bi21_entrega_dia = null; 
   var $bi21_entrega_mes = null; 
   var $bi21_entrega_ano = null; 
   var $bi21_entrega = null; 
   var $bi21_usuario = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 bi21_codigo = int8 = Código 
                 bi21_emprestimoacervo = int8 = Empréstimo Acervo 
                 bi21_entrega = date = Data de Entrega 
                 bi21_usuario = int8 = Usuário 
                 ";
   //funcao construtor da classe 
   function cl_devolucaoacervo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("devolucaoacervo"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='bib1_devolucao001.php'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->bi21_codigo = ($this->bi21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_codigo"]:$this->bi21_codigo);
       $this->bi21_emprestimoacervo = ($this->bi21_emprestimoacervo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_emprestimoacervo"]:$this->bi21_emprestimoacervo);
       if($this->bi21_entrega == ""){
         $this->bi21_entrega_dia = ($this->bi21_entrega_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_entrega_dia"]:$this->bi21_entrega_dia);
         $this->bi21_entrega_mes = ($this->bi21_entrega_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_entrega_mes"]:$this->bi21_entrega_mes);
         $this->bi21_entrega_ano = ($this->bi21_entrega_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_entrega_ano"]:$this->bi21_entrega_ano);
         if($this->bi21_entrega_dia != ""){
            $this->bi21_entrega = $this->bi21_entrega_ano."-".$this->bi21_entrega_mes."-".$this->bi21_entrega_dia;
         }
       }
       $this->bi21_usuario = ($this->bi21_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_usuario"]:$this->bi21_usuario);
     }else{
       $this->bi21_codigo = ($this->bi21_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["bi21_codigo"]:$this->bi21_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($bi21_codigo){ 
      $this->atualizacampos();
     if($this->bi21_emprestimoacervo == null ){ 
       $this->erro_sql = " Campo Empréstimo Acervo não informado.";
       $this->erro_campo = "bi21_emprestimoacervo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi21_entrega == null ){ 
       $this->erro_sql = " Campo Data de Entrega não informado.";
       $this->erro_campo = "bi21_entrega_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->bi21_usuario == null ){ 
       $this->erro_sql = " Campo Usuário não informado.";
       $this->erro_campo = "bi21_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->bi21_codigo = $bi21_codigo; 
     if(($this->bi21_codigo == null) || ($this->bi21_codigo == "") ){ 
       $this->erro_sql = " Campo bi21_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into devolucaoacervo(
                                       bi21_codigo 
                                      ,bi21_emprestimoacervo 
                                      ,bi21_entrega 
                                      ,bi21_usuario 
                       )
                values (
                                $this->bi21_codigo 
                               ,$this->bi21_emprestimoacervo 
                               ,".($this->bi21_entrega == "null" || $this->bi21_entrega == ""?"null":"'".$this->bi21_entrega."'")." 
                               ,$this->bi21_usuario 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Devolução do Acervo ($this->bi21_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Devolução do Acervo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Devolução do Acervo ($this->bi21_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi21_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi21_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008167,'$this->bi21_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1008027,1008167,'','".AddSlashes(pg_result($resaco,0,'bi21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008027,1008168,'','".AddSlashes(pg_result($resaco,0,'bi21_emprestimoacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008027,1008169,'','".AddSlashes(pg_result($resaco,0,'bi21_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1008027,1008938,'','".AddSlashes(pg_result($resaco,0,'bi21_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($bi21_codigo=null) { 
      $this->atualizacampos();
     $sql = " update devolucaoacervo set ";
     $virgula = "";
     if(trim($this->bi21_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi21_codigo"])){ 
       $sql  .= $virgula." bi21_codigo = $this->bi21_codigo ";
       $virgula = ",";
       if(trim($this->bi21_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "bi21_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi21_emprestimoacervo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi21_emprestimoacervo"])){ 
       $sql  .= $virgula." bi21_emprestimoacervo = $this->bi21_emprestimoacervo ";
       $virgula = ",";
       if(trim($this->bi21_emprestimoacervo) == null ){ 
         $this->erro_sql = " Campo Empréstimo Acervo não informado.";
         $this->erro_campo = "bi21_emprestimoacervo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->bi21_entrega)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi21_entrega_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["bi21_entrega_dia"] !="") ){ 
       $sql  .= $virgula." bi21_entrega = '$this->bi21_entrega' ";
       $virgula = ",";
       if(trim($this->bi21_entrega) == null ){ 
         $this->erro_sql = " Campo Data de Entrega não informado.";
         $this->erro_campo = "bi21_entrega_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["bi21_entrega_dia"])){ 
         $sql  .= $virgula." bi21_entrega = null ";
         $virgula = ",";
         if(trim($this->bi21_entrega) == null ){ 
           $this->erro_sql = " Campo Data de Entrega não informado.";
           $this->erro_campo = "bi21_entrega_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->bi21_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["bi21_usuario"])){ 
       $sql  .= $virgula." bi21_usuario = $this->bi21_usuario ";
       $virgula = ",";
       if(trim($this->bi21_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário não informado.";
         $this->erro_campo = "bi21_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($bi21_codigo!=null){
       $sql .= " bi21_codigo = $this->bi21_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->bi21_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008167,'$this->bi21_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi21_codigo"]) || $this->bi21_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1008027,1008167,'".AddSlashes(pg_result($resaco,$conresaco,'bi21_codigo'))."','$this->bi21_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi21_emprestimoacervo"]) || $this->bi21_emprestimoacervo != "")
             $resac = db_query("insert into db_acount values($acount,1008027,1008168,'".AddSlashes(pg_result($resaco,$conresaco,'bi21_emprestimoacervo'))."','$this->bi21_emprestimoacervo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi21_entrega"]) || $this->bi21_entrega != "")
             $resac = db_query("insert into db_acount values($acount,1008027,1008169,'".AddSlashes(pg_result($resaco,$conresaco,'bi21_entrega'))."','$this->bi21_entrega',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["bi21_usuario"]) || $this->bi21_usuario != "")
             $resac = db_query("insert into db_acount values($acount,1008027,1008938,'".AddSlashes(pg_result($resaco,$conresaco,'bi21_usuario'))."','$this->bi21_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução do Acervo não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Devolução do Acervo não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->bi21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->bi21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($bi21_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($bi21_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008167,'$bi21_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1008027,1008167,'','".AddSlashes(pg_result($resaco,$iresaco,'bi21_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008027,1008168,'','".AddSlashes(pg_result($resaco,$iresaco,'bi21_emprestimoacervo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008027,1008169,'','".AddSlashes(pg_result($resaco,$iresaco,'bi21_entrega'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1008027,1008938,'','".AddSlashes(pg_result($resaco,$iresaco,'bi21_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from devolucaoacervo
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($bi21_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " bi21_codigo = $bi21_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução do Acervo não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$bi21_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Devolução do Acervo não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$bi21_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$bi21_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:devolucaoacervo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($bi21_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= " from devolucaoacervo ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = devolucaoacervo.bi21_usuario";
     $sql .= "      inner join emprestimoacervo  on  emprestimoacervo.bi19_codigo = devolucaoacervo.bi21_emprestimoacervo";
     $sql .= "      inner join emprestimo  on   emprestimo.bi18_codigo = emprestimoacervo.bi19_emprestimo";
     $sql .= "      inner join exemplar  on  exemplar.bi23_codigo = emprestimoacervo.bi19_exemplar";
     $sql .= "      inner join acervo  on  acervo.bi06_seq = exemplar.bi23_acervo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi21_codigo)) {
         $sql2 .= " where devolucaoacervo.bi21_codigo = $bi21_codigo "; 
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
   public function sql_query_file ($bi21_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from devolucaoacervo ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($bi21_codigo)){
         $sql2 .= " where devolucaoacervo.bi21_codigo = $bi21_codigo "; 
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
