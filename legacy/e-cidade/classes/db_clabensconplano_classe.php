<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE clabensconplano
class cl_clabensconplano { 
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
   var $t86_sequencial = 0; 
   var $t86_clabens = 0; 
   var $t86_conplano = 0; 
   var $t86_anousu = 0; 
   var $t86_conplanodepreciacao = 0; 
   var $t86_anousudepreciacao = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t86_sequencial = int4 = Sequencial 
                 t86_clabens = int8 = Código 
                 t86_conplano = int4 = Código da conta 
                 t86_anousu = int4 = Exercício 
                 t86_conplanodepreciacao = int4 = Código 
                 t86_anousudepreciacao = int4 = Exercício 
                 ";
   //funcao construtor da classe 
   function cl_clabensconplano() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("clabensconplano"); 
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
       $this->t86_sequencial = ($this->t86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_sequencial"]:$this->t86_sequencial);
       $this->t86_clabens = ($this->t86_clabens == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_clabens"]:$this->t86_clabens);
       $this->t86_conplano = ($this->t86_conplano == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_conplano"]:$this->t86_conplano);
       $this->t86_anousu = ($this->t86_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_anousu"]:$this->t86_anousu);
       $this->t86_conplanodepreciacao = ($this->t86_conplanodepreciacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_conplanodepreciacao"]:$this->t86_conplanodepreciacao);
       $this->t86_anousudepreciacao = ($this->t86_anousudepreciacao == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_anousudepreciacao"]:$this->t86_anousudepreciacao);
     }else{
       $this->t86_sequencial = ($this->t86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["t86_sequencial"]:$this->t86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($t86_sequencial){ 
      $this->atualizacampos();
     if($this->t86_clabens == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "t86_clabens";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t86_conplano == null ){ 
       $this->erro_sql = " Campo Código da conta nao Informado.";
       $this->erro_campo = "t86_conplano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t86_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "t86_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t86_conplanodepreciacao == null ){ 
       $this->erro_sql = " Campo Código nao Informado.";
       $this->erro_campo = "t86_conplanodepreciacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t86_anousudepreciacao == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "t86_anousudepreciacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t86_sequencial == "" || $t86_sequencial == null ){
       $result = db_query("select nextval('clabensconplano_t86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: clabensconplano_t86_sequencial_seq do campo: t86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from clabensconplano_t86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $t86_sequencial)){
         $this->erro_sql = " Campo t86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t86_sequencial = $t86_sequencial; 
       }
     }
     if(($this->t86_sequencial == null) || ($this->t86_sequencial == "") ){ 
       $this->erro_sql = " Campo t86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into clabensconplano(
                                       t86_sequencial 
                                      ,t86_clabens 
                                      ,t86_conplano 
                                      ,t86_anousu 
                                      ,t86_conplanodepreciacao 
                                      ,t86_anousudepreciacao 
                       )
                values (
                                $this->t86_sequencial 
                               ,$this->t86_clabens 
                               ,$this->t86_conplano 
                               ,$this->t86_anousu 
                               ,$this->t86_conplanodepreciacao 
                               ,$this->t86_anousudepreciacao 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->t86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->t86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19546,'$this->t86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3474,19546,'','".AddSlashes(pg_result($resaco,0,'t86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3474,19547,'','".AddSlashes(pg_result($resaco,0,'t86_clabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3474,19548,'','".AddSlashes(pg_result($resaco,0,'t86_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3474,19549,'','".AddSlashes(pg_result($resaco,0,'t86_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3474,19550,'','".AddSlashes(pg_result($resaco,0,'t86_conplanodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3474,19551,'','".AddSlashes(pg_result($resaco,0,'t86_anousudepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update clabensconplano set ";
     $virgula = "";
     if(trim($this->t86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_sequencial"])){ 
       $sql  .= $virgula." t86_sequencial = $this->t86_sequencial ";
       $virgula = ",";
       if(trim($this->t86_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "t86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t86_clabens)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_clabens"])){ 
       $sql  .= $virgula." t86_clabens = $this->t86_clabens ";
       $virgula = ",";
       if(trim($this->t86_clabens) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t86_clabens";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t86_conplano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_conplano"])){ 
       $sql  .= $virgula." t86_conplano = $this->t86_conplano ";
       $virgula = ",";
       if(trim($this->t86_conplano) == null ){ 
         $this->erro_sql = " Campo Código da conta nao Informado.";
         $this->erro_campo = "t86_conplano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t86_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_anousu"])){ 
       $sql  .= $virgula." t86_anousu = $this->t86_anousu ";
       $virgula = ",";
       if(trim($this->t86_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "t86_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t86_conplanodepreciacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_conplanodepreciacao"])){ 
       $sql  .= $virgula." t86_conplanodepreciacao = $this->t86_conplanodepreciacao ";
       $virgula = ",";
       if(trim($this->t86_conplanodepreciacao) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "t86_conplanodepreciacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t86_anousudepreciacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t86_anousudepreciacao"])){ 
       $sql  .= $virgula." t86_anousudepreciacao = $this->t86_anousudepreciacao ";
       $virgula = ",";
       if(trim($this->t86_anousudepreciacao) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "t86_anousudepreciacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t86_sequencial!=null){
       $sql .= " t86_sequencial = $this->t86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19546,'$this->t86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_sequencial"]) || $this->t86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3474,19546,'".AddSlashes(pg_result($resaco,$conresaco,'t86_sequencial'))."','$this->t86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_clabens"]) || $this->t86_clabens != "")
           $resac = db_query("insert into db_acount values($acount,3474,19547,'".AddSlashes(pg_result($resaco,$conresaco,'t86_clabens'))."','$this->t86_clabens',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_conplano"]) || $this->t86_conplano != "")
           $resac = db_query("insert into db_acount values($acount,3474,19548,'".AddSlashes(pg_result($resaco,$conresaco,'t86_conplano'))."','$this->t86_conplano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_anousu"]) || $this->t86_anousu != "")
           $resac = db_query("insert into db_acount values($acount,3474,19549,'".AddSlashes(pg_result($resaco,$conresaco,'t86_anousu'))."','$this->t86_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_conplanodepreciacao"]) || $this->t86_conplanodepreciacao != "")
           $resac = db_query("insert into db_acount values($acount,3474,19550,'".AddSlashes(pg_result($resaco,$conresaco,'t86_conplanodepreciacao'))."','$this->t86_conplanodepreciacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t86_anousudepreciacao"]) || $this->t86_anousudepreciacao != "")
           $resac = db_query("insert into db_acount values($acount,3474,19551,'".AddSlashes(pg_result($resaco,$conresaco,'t86_anousudepreciacao'))."','$this->t86_anousudepreciacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19546,'$t86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3474,19546,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3474,19547,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_clabens'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3474,19548,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_conplano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3474,19549,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3474,19550,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_conplanodepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3474,19551,'','".AddSlashes(pg_result($resaco,$iresaco,'t86_anousudepreciacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from clabensconplano
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t86_sequencial = $t86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:clabensconplano";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $t86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clabensconplano ";
     $sql .= "      inner join conplano  on  conplano.c60_codcon = clabensconplano.t86_conplano and  conplano.c60_anousu = clabensconplano.t86_anousu";
     /*
      * Adicionado mais um inner com a conplano pois esta tabela possui duas FK com a tabela conplano e o gerador 
      * de classe nao esta preparado para criar os joins com a mesma tabela
      */
     $sql .= "      inner join conplano as conplanodepreciacao on conplanodepreciacao.c60_codcon = clabensconplano.t86_conplanodepreciacao and  conplanodepreciacao.c60_anousu = clabensconplano.t86_anousudepreciacao";
     $sql .= "      inner join clabens  on  clabens.t64_codcla = clabensconplano.t86_clabens";
     $sql .= "      inner join conclass  on  conclass.c51_codcla = conplano.c60_codcla";
     $sql .= "      inner join consistema  on  consistema.c52_codsis = conplano.c60_codsis";
     $sql .= "      inner join consistemaconta  on  consistemaconta.c65_sequencial = conplano.c60_consistemaconta";
     $sql .= "      inner join db_config  on  db_config.codigo = clabens.t64_instit";
     $sql .= "      inner join bemtipos  on  bemtipos.t24_sequencial = clabens.t64_bemtipos";
     $sql .= "      inner join benstipodepreciacao  on  benstipodepreciacao.t46_sequencial = clabens.t64_benstipodepreciacao";
     $sql2 = "";
     if($dbwhere==""){
       if($t86_sequencial!=null ){
         $sql2 .= " where clabensconplano.t86_sequencial = $t86_sequencial "; 
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
   function sql_query_file ( $t86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from clabensconplano ";
     $sql2 = "";
     if($dbwhere==""){
       if($t86_sequencial!=null ){
         $sql2 .= " where clabensconplano.t86_sequencial = $t86_sequencial "; 
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