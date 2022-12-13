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

//MODULO: escola
//CLASSE DA ENTIDADE alunocidadaocontato
class cl_alunocidadaocontato { 
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
   var $ed332_sequencial = 0; 
   var $ed332_aluno = 0; 
   var $ed332_cidadao = 0; 
   var $ed332_cidadao_seq = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed332_sequencial = int4 = Código AlunoCidadaoContato 
                 ed332_aluno = int4 = Aluno 
                 ed332_cidadao = int4 = Cidadão 
                 ed332_cidadao_seq = int4 = Cidadão Sequencial 
                 ";
   //funcao construtor da classe 
   function cl_alunocidadaocontato() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunocidadaocontato"); 
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
       $this->ed332_sequencial = ($this->ed332_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed332_sequencial"]:$this->ed332_sequencial);
       $this->ed332_aluno = ($this->ed332_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed332_aluno"]:$this->ed332_aluno);
       $this->ed332_cidadao = ($this->ed332_cidadao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed332_cidadao"]:$this->ed332_cidadao);
       $this->ed332_cidadao_seq = ($this->ed332_cidadao_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed332_cidadao_seq"]:$this->ed332_cidadao_seq);
     }else{
       $this->ed332_sequencial = ($this->ed332_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed332_sequencial"]:$this->ed332_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed332_sequencial){ 
      $this->atualizacampos();
     if($this->ed332_aluno == null ){ 
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed332_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed332_cidadao == null ){ 
       $this->erro_sql = " Campo Cidadão nao Informado.";
       $this->erro_campo = "ed332_cidadao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed332_cidadao_seq == null ){ 
       $this->erro_sql = " Campo Cidadão Sequencial nao Informado.";
       $this->erro_campo = "ed332_cidadao_seq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed332_sequencial == "" || $ed332_sequencial == null ){
       $result = db_query("select nextval('alunocidadaocontato_ed332_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunocidadaocontato_ed332_sequencial_seq do campo: ed332_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed332_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from alunocidadaocontato_ed332_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed332_sequencial)){
         $this->erro_sql = " Campo ed332_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed332_sequencial = $ed332_sequencial; 
       }
     }
     if(($this->ed332_sequencial == null) || ($this->ed332_sequencial == "") ){ 
       $this->erro_sql = " Campo ed332_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunocidadaocontato(
                                       ed332_sequencial 
                                      ,ed332_aluno 
                                      ,ed332_cidadao 
                                      ,ed332_cidadao_seq 
                       )
                values (
                                $this->ed332_sequencial 
                               ,$this->ed332_aluno 
                               ,$this->ed332_cidadao 
                               ,$this->ed332_cidadao_seq 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "AlunoCidadaoContato ($this->ed332_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "AlunoCidadaoContato já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "AlunoCidadaoContato ($this->ed332_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed332_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed332_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20168,'$this->ed332_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3620,20168,'','".AddSlashes(pg_result($resaco,0,'ed332_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3620,20169,'','".AddSlashes(pg_result($resaco,0,'ed332_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3620,20170,'','".AddSlashes(pg_result($resaco,0,'ed332_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3620,20171,'','".AddSlashes(pg_result($resaco,0,'ed332_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed332_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update alunocidadaocontato set ";
     $virgula = "";
     if(trim($this->ed332_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed332_sequencial"])){ 
       $sql  .= $virgula." ed332_sequencial = $this->ed332_sequencial ";
       $virgula = ",";
       if(trim($this->ed332_sequencial) == null ){ 
         $this->erro_sql = " Campo Código AlunoCidadaoContato nao Informado.";
         $this->erro_campo = "ed332_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed332_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed332_aluno"])){ 
       $sql  .= $virgula." ed332_aluno = $this->ed332_aluno ";
       $virgula = ",";
       if(trim($this->ed332_aluno) == null ){ 
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed332_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed332_cidadao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed332_cidadao"])){ 
       $sql  .= $virgula." ed332_cidadao = $this->ed332_cidadao ";
       $virgula = ",";
       if(trim($this->ed332_cidadao) == null ){ 
         $this->erro_sql = " Campo Cidadão nao Informado.";
         $this->erro_campo = "ed332_cidadao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed332_cidadao_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed332_cidadao_seq"])){ 
       $sql  .= $virgula." ed332_cidadao_seq = $this->ed332_cidadao_seq ";
       $virgula = ",";
       if(trim($this->ed332_cidadao_seq) == null ){ 
         $this->erro_sql = " Campo Cidadão Sequencial nao Informado.";
         $this->erro_campo = "ed332_cidadao_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed332_sequencial!=null){
       $sql .= " ed332_sequencial = $this->ed332_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed332_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20168,'$this->ed332_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed332_sequencial"]) || $this->ed332_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3620,20168,'".AddSlashes(pg_result($resaco,$conresaco,'ed332_sequencial'))."','$this->ed332_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed332_aluno"]) || $this->ed332_aluno != "")
             $resac = db_query("insert into db_acount values($acount,3620,20169,'".AddSlashes(pg_result($resaco,$conresaco,'ed332_aluno'))."','$this->ed332_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed332_cidadao"]) || $this->ed332_cidadao != "")
             $resac = db_query("insert into db_acount values($acount,3620,20170,'".AddSlashes(pg_result($resaco,$conresaco,'ed332_cidadao'))."','$this->ed332_cidadao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed332_cidadao_seq"]) || $this->ed332_cidadao_seq != "")
             $resac = db_query("insert into db_acount values($acount,3620,20171,'".AddSlashes(pg_result($resaco,$conresaco,'ed332_cidadao_seq'))."','$this->ed332_cidadao_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "AlunoCidadaoContato nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed332_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "AlunoCidadaoContato nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed332_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed332_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed332_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($ed332_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20168,'$ed332_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3620,20168,'','".AddSlashes(pg_result($resaco,$iresaco,'ed332_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3620,20169,'','".AddSlashes(pg_result($resaco,$iresaco,'ed332_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3620,20170,'','".AddSlashes(pg_result($resaco,$iresaco,'ed332_cidadao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3620,20171,'','".AddSlashes(pg_result($resaco,$iresaco,'ed332_cidadao_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from alunocidadaocontato
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed332_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed332_sequencial = $ed332_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "AlunoCidadaoContato nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed332_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "AlunoCidadaoContato nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed332_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed332_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunocidadaocontato";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed332_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunocidadaocontato ";
     $sql .= "      inner join cidadao  on  cidadao.ov02_sequencial = alunocidadaocontato.ed332_cidadao and  cidadao.ov02_seq = alunocidadaocontato.ed332_cidadao_seq";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunocidadaocontato.ed332_aluno";
     $sql .= "      inner join situacaocidadao  on  situacaocidadao.ov16_sequencial = cidadao.ov02_situacaocidadao";
     $sql .= "      inner join pais  on  pais.ed228_i_codigo = aluno.ed47_i_pais";
     $sql .= "      left  join censouf  on  censouf.ed260_i_codigo = aluno.ed47_i_censoufnat and  censouf.ed260_i_codigo = aluno.ed47_i_censoufident and  censouf.ed260_i_codigo = aluno.ed47_i_censoufcert and  censouf.ed260_i_codigo = aluno.ed47_i_censoufend";
     $sql .= "      left  join censomunic  on  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicnat and  censomunic.ed261_i_codigo = aluno.ed47_i_censomunicend and  censomunic.ed261_i_codigo = aluno.ed47_i_censomuniccert";
     $sql .= "      left  join censoorgemissrg  on  censoorgemissrg.ed132_i_codigo = aluno.ed47_i_censoorgemissrg";
     $sql .= "      left  join aluno  as a on   a.ed47_i_codigo = aluno.ed47_i_censocartorio";
     $sql2 = "";
     if($dbwhere==""){
       if($ed332_sequencial!=null ){
         $sql2 .= " where alunocidadaocontato.ed332_sequencial = $ed332_sequencial "; 
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
   function sql_query_file ( $ed332_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from alunocidadaocontato ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed332_sequencial!=null ){
         $sql2 .= " where alunocidadaocontato.ed332_sequencial = $ed332_sequencial "; 
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