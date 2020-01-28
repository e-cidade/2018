<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: contabilidade
//CLASSE DA ENTIDADE reconhecimentocontabiltipo
class cl_reconhecimentocontabiltipo { 
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
   var $c111_sequencial = 0; 
   var $c111_descricao = null; 
   var $c111_conhistdoc = 0; 
   var $c111_conhistdocestorno = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c111_sequencial = int4 = Sequencial de Tipos de Rec. Contábil 
                 c111_descricao = varchar(100) = Descrição 
                 c111_conhistdoc = int4 = Código do documento 
                 c111_conhistdocestorno = int4 = Código do documento 
                 ";
   //funcao construtor da classe 
   function cl_reconhecimentocontabiltipo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("reconhecimentocontabiltipo"); 
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
       $this->c111_sequencial = ($this->c111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c111_sequencial"]:$this->c111_sequencial);
       $this->c111_descricao = ($this->c111_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["c111_descricao"]:$this->c111_descricao);
       $this->c111_conhistdoc = ($this->c111_conhistdoc == ""?@$GLOBALS["HTTP_POST_VARS"]["c111_conhistdoc"]:$this->c111_conhistdoc);
       $this->c111_conhistdocestorno = ($this->c111_conhistdocestorno == ""?@$GLOBALS["HTTP_POST_VARS"]["c111_conhistdocestorno"]:$this->c111_conhistdocestorno);
     }else{
       $this->c111_sequencial = ($this->c111_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c111_sequencial"]:$this->c111_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c111_sequencial){ 
      $this->atualizacampos();
     if($this->c111_descricao == null ){ 
       $this->erro_sql = " Campo Descrição não informado.";
       $this->erro_campo = "c111_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c111_conhistdoc == null ){ 
       $this->erro_sql = " Campo Código do documento não informado.";
       $this->erro_campo = "c111_conhistdoc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c111_conhistdocestorno == null ){ 
       $this->erro_sql = " Campo Código do documento não informado.";
       $this->erro_campo = "c111_conhistdocestorno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c111_sequencial == "" || $c111_sequencial == null ){
       $result = db_query("select nextval('reconhecimentocontabiltipo_c111_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: reconhecimentocontabiltipo_c111_sequencial_seq do campo: c111_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c111_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from reconhecimentocontabiltipo_c111_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c111_sequencial)){
         $this->erro_sql = " Campo c111_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c111_sequencial = $c111_sequencial; 
       }
     }
     if(($this->c111_sequencial == null) || ($this->c111_sequencial == "") ){ 
       $this->erro_sql = " Campo c111_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into reconhecimentocontabiltipo(
                                       c111_sequencial 
                                      ,c111_descricao 
                                      ,c111_conhistdoc 
                                      ,c111_conhistdocestorno 
                       )
                values (
                                $this->c111_sequencial 
                               ,'$this->c111_descricao' 
                               ,$this->c111_conhistdoc 
                               ,$this->c111_conhistdocestorno 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tipos de Reconhecimento Contábil ($this->c111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tipos de Reconhecimento Contábil já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tipos de Reconhecimento Contábil ($this->c111_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c111_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c111_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20197,'$this->c111_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3626,20197,'','".AddSlashes(pg_result($resaco,0,'c111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3626,20198,'','".AddSlashes(pg_result($resaco,0,'c111_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3626,20199,'','".AddSlashes(pg_result($resaco,0,'c111_conhistdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3626,20200,'','".AddSlashes(pg_result($resaco,0,'c111_conhistdocestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c111_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update reconhecimentocontabiltipo set ";
     $virgula = "";
     if(trim($this->c111_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c111_sequencial"])){ 
       $sql  .= $virgula." c111_sequencial = $this->c111_sequencial ";
       $virgula = ",";
       if(trim($this->c111_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial de Tipos de Rec. Contábil não informado.";
         $this->erro_campo = "c111_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c111_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c111_descricao"])){ 
       $sql  .= $virgula." c111_descricao = '$this->c111_descricao' ";
       $virgula = ",";
       if(trim($this->c111_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição não informado.";
         $this->erro_campo = "c111_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c111_conhistdoc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c111_conhistdoc"])){ 
       $sql  .= $virgula." c111_conhistdoc = $this->c111_conhistdoc ";
       $virgula = ",";
       if(trim($this->c111_conhistdoc) == null ){ 
         $this->erro_sql = " Campo Código do documento não informado.";
         $this->erro_campo = "c111_conhistdoc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c111_conhistdocestorno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c111_conhistdocestorno"])){ 
       $sql  .= $virgula." c111_conhistdocestorno = $this->c111_conhistdocestorno ";
       $virgula = ",";
       if(trim($this->c111_conhistdocestorno) == null ){ 
         $this->erro_sql = " Campo Código do documento não informado.";
         $this->erro_campo = "c111_conhistdocestorno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c111_sequencial!=null){
       $sql .= " c111_sequencial = $this->c111_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->c111_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20197,'$this->c111_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c111_sequencial"]) || $this->c111_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3626,20197,'".AddSlashes(pg_result($resaco,$conresaco,'c111_sequencial'))."','$this->c111_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c111_descricao"]) || $this->c111_descricao != "")
             $resac = db_query("insert into db_acount values($acount,3626,20198,'".AddSlashes(pg_result($resaco,$conresaco,'c111_descricao'))."','$this->c111_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c111_conhistdoc"]) || $this->c111_conhistdoc != "")
             $resac = db_query("insert into db_acount values($acount,3626,20199,'".AddSlashes(pg_result($resaco,$conresaco,'c111_conhistdoc'))."','$this->c111_conhistdoc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["c111_conhistdocestorno"]) || $this->c111_conhistdocestorno != "")
             $resac = db_query("insert into db_acount values($acount,3626,20200,'".AddSlashes(pg_result($resaco,$conresaco,'c111_conhistdocestorno'))."','$this->c111_conhistdocestorno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Reconhecimento Contábil nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Reconhecimento Contábil nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c111_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($c111_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20197,'$c111_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3626,20197,'','".AddSlashes(pg_result($resaco,$iresaco,'c111_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3626,20198,'','".AddSlashes(pg_result($resaco,$iresaco,'c111_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3626,20199,'','".AddSlashes(pg_result($resaco,$iresaco,'c111_conhistdoc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3626,20200,'','".AddSlashes(pg_result($resaco,$iresaco,'c111_conhistdocestorno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from reconhecimentocontabiltipo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c111_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c111_sequencial = $c111_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tipos de Reconhecimento Contábil nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c111_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tipos de Reconhecimento Contábil nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c111_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:reconhecimentocontabiltipo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $c111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from reconhecimentocontabiltipo ";
  	 $sql .= "      inner join conhistdoc                           on conhistdoc.c53_coddoc                = reconhecimentocontabiltipo.c111_conhistdoc";
  	 $sql .= "      inner join conhistdoc  conhistdocestorno        on conhistdocestorno.c53_coddoc         = reconhecimentocontabiltipo.c111_conhistdocestorno";
     $sql2 = "";
     if($dbwhere==""){
       if($c111_sequencial!=null ){
         $sql2 .= " where reconhecimentocontabiltipo.c111_sequencial = $c111_sequencial "; 
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
   function sql_query_file ( $c111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from reconhecimentocontabiltipo ";
     $sql2 = "";
     if($dbwhere==""){
       if($c111_sequencial!=null ){
         $sql2 .= " where reconhecimentocontabiltipo.c111_sequencial = $c111_sequencial "; 
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
  function sql_queryReconhecimento ( $c111_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
  	$sql .= " from reconhecimentocontabiltipo ";
  	$sql .= "      inner join conhistdoc                           on conhistdoc.c53_coddoc                = reconhecimentocontabiltipo.c111_conhistdoc";
  	$sql .= "      inner join conhistdoc  conhistdocestorno        on conhistdocestorno.c53_coddoc         = reconhecimentocontabiltipo.c111_conhistdocestorno";
  	//$sql .= "      inner join conhistdoctipo                       on conhistdoctipo.c57_sequencial        = conhistdoc.c53_tipo";
  	//$sql .= "      inner join conhistdoctipo conhistdoctipoestorno on conhistdoctipoestorno.c57_sequencial = conhistdocestorno.c53_tipo";
  	$sql2 = "";
  	if($dbwhere==""){
  		if($c111_sequencial!=null ){
  			$sql2 .= " where reconhecimentocontabiltipo.c111_sequencial = $c111_sequencial ";
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
?>