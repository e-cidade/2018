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

class cl_pctipocompratribunal { 
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
   var $l44_sequencial = 0; 
   var $l44_codigotribunal = null; 
   var $l44_descricao = null; 
   var $l44_uf = null; 
   var $l44_sigla = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 l44_sequencial = int4 = Código Sequencial 
                 l44_codigotribunal = varchar(10) = Código do Tribunal 
                 l44_descricao = varchar(70) = Descrição 
                 l44_uf = char(2) = UF 
                 l44_sigla = varchar(3) = Sigla 
                 ";
   //funcao construtor da classe 
   function cl_pctipocompratribunal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pctipocompratribunal"); 
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
       $this->l44_sequencial = ($this->l44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_sequencial"]:$this->l44_sequencial);
       $this->l44_codigotribunal = ($this->l44_codigotribunal == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_codigotribunal"]:$this->l44_codigotribunal);
       $this->l44_descricao = ($this->l44_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_descricao"]:$this->l44_descricao);
       $this->l44_uf = ($this->l44_uf == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_uf"]:$this->l44_uf);
       $this->l44_sigla = ($this->l44_sigla == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_sigla"]:$this->l44_sigla);
     }else{
       $this->l44_sequencial = ($this->l44_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["l44_sequencial"]:$this->l44_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($l44_sequencial){ 
      $this->atualizacampos();
     if($this->l44_codigotribunal == null ){ 
       $this->erro_sql = " Campo Código do Tribunal não informado.";
       $this->erro_campo = "l44_codigotribunal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l44_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "l44_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->l44_uf == null ){ 
       $this->erro_sql = " Campo UF não informado.";
       $this->erro_campo = "l44_uf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($l44_sequencial == "" || $l44_sequencial == null ){
       $result = db_query("select nextval('pctipocompratribunal_l44_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pctipocompratribunal_l44_sequencial_seq do campo: l44_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->l44_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pctipocompratribunal_l44_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $l44_sequencial)){
         $this->erro_sql = " Campo l44_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->l44_sequencial = $l44_sequencial; 
       }
     }
     if(($this->l44_sequencial == null) || ($this->l44_sequencial == "") ){ 
       $this->erro_sql = " Campo l44_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pctipocompratribunal(
                                       l44_sequencial 
                                      ,l44_codigotribunal 
                                      ,l44_descricao 
                                      ,l44_uf 
                                      ,l44_sigla 
                       )
                values (
                                $this->l44_sequencial 
                               ,'$this->l44_codigotribunal' 
                               ,'$this->l44_descricao' 
                               ,'$this->l44_uf' 
                               ,'$this->l44_sigla' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pctipocompratribunal ($this->l44_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pctipocompratribunal já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pctipocompratribunal ($this->l44_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l44_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l44_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17813,'$this->l44_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3145,17813,'','".AddSlashes(pg_result($resaco,0,'l44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3145,17814,'','".AddSlashes(pg_result($resaco,0,'l44_codigotribunal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3145,17815,'','".AddSlashes(pg_result($resaco,0,'l44_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3145,17816,'','".AddSlashes(pg_result($resaco,0,'l44_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3145,21717,'','".AddSlashes(pg_result($resaco,0,'l44_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($l44_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pctipocompratribunal set ";
     $virgula = "";
     if(trim($this->l44_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l44_sequencial"])){ 
       $sql  .= $virgula." l44_sequencial = $this->l44_sequencial ";
       $virgula = ",";
       if(trim($this->l44_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial não informado.";
         $this->erro_campo = "l44_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l44_codigotribunal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l44_codigotribunal"])){ 
       $sql  .= $virgula." l44_codigotribunal = '$this->l44_codigotribunal' ";
       $virgula = ",";
       if(trim($this->l44_codigotribunal) == null ){ 
         $this->erro_sql = " Campo Código do Tribunal não informado.";
         $this->erro_campo = "l44_codigotribunal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l44_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l44_descricao"])){ 
       $sql  .= $virgula." l44_descricao = '$this->l44_descricao' ";
       $virgula = ",";
       if(trim($this->l44_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "l44_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l44_uf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l44_uf"])){ 
       $sql  .= $virgula." l44_uf = '$this->l44_uf' ";
       $virgula = ",";
       if(trim($this->l44_uf) == null ){ 
         $this->erro_sql = " Campo UF não informado.";
         $this->erro_campo = "l44_uf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->l44_sigla)!="" || isset($GLOBALS["HTTP_POST_VARS"]["l44_sigla"])){ 
       $sql  .= $virgula." l44_sigla = '$this->l44_sigla' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($l44_sequencial!=null){
       $sql .= " l44_sequencial = $this->l44_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->l44_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,17813,'$this->l44_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l44_sequencial"]) || $this->l44_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3145,17813,'".AddSlashes(pg_result($resaco,$conresaco,'l44_sequencial'))."','$this->l44_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l44_codigotribunal"]) || $this->l44_codigotribunal != "")
             $resac = db_query("insert into db_acount values($acount,3145,17814,'".AddSlashes(pg_result($resaco,$conresaco,'l44_codigotribunal'))."','$this->l44_codigotribunal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l44_descricao"]) || $this->l44_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3145,17815,'".AddSlashes(pg_result($resaco,$conresaco,'l44_descricao'))."','$this->l44_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l44_uf"]) || $this->l44_uf != "")
             $resac = db_query("insert into db_acount values($acount,3145,17816,'".AddSlashes(pg_result($resaco,$conresaco,'l44_uf'))."','$this->l44_uf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["l44_sigla"]) || $this->l44_sigla != "")
             $resac = db_query("insert into db_acount values($acount,3145,21717,'".AddSlashes(pg_result($resaco,$conresaco,'l44_sigla'))."','$this->l44_sigla',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pctipocompratribunal não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->l44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pctipocompratribunal não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->l44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->l44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($l44_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($l44_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,17813,'$l44_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3145,17813,'','".AddSlashes(pg_result($resaco,$iresaco,'l44_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3145,17814,'','".AddSlashes(pg_result($resaco,$iresaco,'l44_codigotribunal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3145,17815,'','".AddSlashes(pg_result($resaco,$iresaco,'l44_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3145,17816,'','".AddSlashes(pg_result($resaco,$iresaco,'l44_uf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3145,21717,'','".AddSlashes(pg_result($resaco,$iresaco,'l44_sigla'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pctipocompratribunal
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($l44_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " l44_sequencial = $l44_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pctipocompratribunal não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$l44_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "pctipocompratribunal não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$l44_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$l44_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:pctipocompratribunal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($l44_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pctipocompratribunal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l44_sequencial)) {
         $sql2 .= " where pctipocompratribunal.l44_sequencial = $l44_sequencial "; 
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
   public function sql_query_file ($l44_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from pctipocompratribunal ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($l44_sequencial)){
         $sql2 .= " where pctipocompratribunal.l44_sequencial = $l44_sequencial "; 
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
