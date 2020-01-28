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
//MODULO: escola
//CLASSE DA ENTIDADE censoregradisc
class cl_censoregradisc { 
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
   var $ed272_i_codigo = 0; 
   var $ed272_i_censoetapa = 0; 
   var $ed272_i_censodisciplina = 0; 
   var $ed272_ano = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed272_i_codigo = int4 = Código 
                 ed272_i_censoetapa = int4 = Etapa 
                 ed272_i_censodisciplina = int4 = Disciplina 
                 ed272_ano = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_censoregradisc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoregradisc"); 
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
       $this->ed272_i_codigo = ($this->ed272_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed272_i_codigo"]:$this->ed272_i_codigo);
       $this->ed272_i_censoetapa = ($this->ed272_i_censoetapa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed272_i_censoetapa"]:$this->ed272_i_censoetapa);
       $this->ed272_i_censodisciplina = ($this->ed272_i_censodisciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed272_i_censodisciplina"]:$this->ed272_i_censodisciplina);
       $this->ed272_ano = ($this->ed272_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed272_ano"]:$this->ed272_ano);
     }else{
       $this->ed272_i_codigo = ($this->ed272_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed272_i_codigo"]:$this->ed272_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed272_i_codigo){ 
      $this->atualizacampos();
     if($this->ed272_i_censoetapa == null ){ 
       $this->erro_sql = " Campo Etapa não informado.";
       $this->erro_campo = "ed272_i_censoetapa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed272_i_censodisciplina == null ){ 
       $this->erro_sql = " Campo Disciplina não informado.";
       $this->erro_campo = "ed272_i_censodisciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed272_ano == null ){ 
       $this->erro_sql = " Campo Ano não informado.";
       $this->erro_campo = "ed272_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed272_i_codigo == "" || $ed272_i_codigo == null ){
       $result = db_query("select nextval('censoregradisc_ed272_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: censoregradisc_ed272_i_codigo_seq do campo: ed272_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed272_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from censoregradisc_ed272_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed272_i_codigo)){
         $this->erro_sql = " Campo ed272_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed272_i_codigo = $ed272_i_codigo; 
       }
     }
     if(($this->ed272_i_codigo == null) || ($this->ed272_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed272_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoregradisc(
                                       ed272_i_codigo 
                                      ,ed272_i_censoetapa 
                                      ,ed272_i_censodisciplina 
                                      ,ed272_ano 
                       )
                values (
                                $this->ed272_i_codigo 
                               ,$this->ed272_i_censoetapa 
                               ,$this->ed272_i_censodisciplina 
                               ,$this->ed272_ano 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Regras Disciplinas x Etapas ($this->ed272_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Regras Disciplinas x Etapas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Regras Disciplinas x Etapas ($this->ed272_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed272_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed272_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13991,'$this->ed272_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2457,13991,'','".AddSlashes(pg_result($resaco,0,'ed272_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2457,13992,'','".AddSlashes(pg_result($resaco,0,'ed272_i_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2457,13993,'','".AddSlashes(pg_result($resaco,0,'ed272_i_censodisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2457,21111,'','".AddSlashes(pg_result($resaco,0,'ed272_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($ed272_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update censoregradisc set ";
     $virgula = "";
     if(trim($this->ed272_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_codigo"])){ 
       $sql  .= $virgula." ed272_i_codigo = $this->ed272_i_codigo ";
       $virgula = ",";
       if(trim($this->ed272_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed272_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed272_i_censoetapa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_censoetapa"])){ 
       $sql  .= $virgula." ed272_i_censoetapa = $this->ed272_i_censoetapa ";
       $virgula = ",";
       if(trim($this->ed272_i_censoetapa) == null ){ 
         $this->erro_sql = " Campo Etapa não informado.";
         $this->erro_campo = "ed272_i_censoetapa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed272_i_censodisciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_censodisciplina"])){ 
       $sql  .= $virgula." ed272_i_censodisciplina = $this->ed272_i_censodisciplina ";
       $virgula = ",";
       if(trim($this->ed272_i_censodisciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina não informado.";
         $this->erro_campo = "ed272_i_censodisciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed272_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed272_ano"])){ 
       $sql  .= $virgula." ed272_ano = $this->ed272_ano ";
       $virgula = ",";
       if(trim($this->ed272_ano) == null ){ 
         $this->erro_sql = " Campo Ano não informado.";
         $this->erro_campo = "ed272_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed272_i_codigo!=null){
       $sql .= " ed272_i_codigo = $this->ed272_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed272_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13991,'$this->ed272_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_codigo"]) || $this->ed272_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2457,13991,'".AddSlashes(pg_result($resaco,$conresaco,'ed272_i_codigo'))."','$this->ed272_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_censoetapa"]) || $this->ed272_i_censoetapa != "")
             $resac = db_query("insert into db_acount values($acount,2457,13992,'".AddSlashes(pg_result($resaco,$conresaco,'ed272_i_censoetapa'))."','$this->ed272_i_censoetapa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed272_i_censodisciplina"]) || $this->ed272_i_censodisciplina != "")
             $resac = db_query("insert into db_acount values($acount,2457,13993,'".AddSlashes(pg_result($resaco,$conresaco,'ed272_i_censodisciplina'))."','$this->ed272_i_censodisciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed272_ano"]) || $this->ed272_ano != "")
             $resac = db_query("insert into db_acount values($acount,2457,21111,'".AddSlashes(pg_result($resaco,$conresaco,'ed272_ano'))."','$this->ed272_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras Disciplinas x Etapas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed272_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regras Disciplinas x Etapas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed272_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed272_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($ed272_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed272_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13991,'$ed272_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2457,13991,'','".AddSlashes(pg_result($resaco,$iresaco,'ed272_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2457,13992,'','".AddSlashes(pg_result($resaco,$iresaco,'ed272_i_censoetapa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2457,13993,'','".AddSlashes(pg_result($resaco,$iresaco,'ed272_i_censodisciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2457,21111,'','".AddSlashes(pg_result($resaco,$iresaco,'ed272_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from censoregradisc
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed272_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed272_i_codigo = $ed272_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Regras Disciplinas x Etapas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed272_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Regras Disciplinas x Etapas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed272_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed272_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoregradisc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($ed272_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from censoregradisc ";
     $sql .= "      inner join censodisciplina  on  censodisciplina.ed265_i_codigo = censoregradisc.ed272_i_censodisciplina";
     $sql .= "      inner join censoetapa  on  censoetapa.ed266_ano = censoregradisc.ed272_i_censoetapa and  censoetapa. = censoregradisc.ed272_ano";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed272_i_codigo)) {
         $sql2 .= " where censoregradisc.ed272_i_codigo = $ed272_i_codigo "; 
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
   public function sql_query_file ($ed272_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from censoregradisc ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed272_i_codigo)){
         $sql2 .= " where censoregradisc.ed272_i_codigo = $ed272_i_codigo "; 
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
