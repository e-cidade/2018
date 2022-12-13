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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE arrecadcompos
class cl_arrecadcompos { 
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
   var $k00_sequencial = 0; 
   var $k00_arreckey = 0; 
   var $k00_vlrhist = 0; 
   var $k00_correcao = 0; 
   var $k00_juros = 0; 
   var $k00_multa = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k00_sequencial = int4 = Código da arrecadação suspensa 
                 k00_arreckey = int4 = arreckey 
                 k00_vlrhist = float8 = Histórico 
                 k00_correcao = float8 = Correcao 
                 k00_juros = float8 = Juros 
                 k00_multa = float8 = Multa 
                 ";
   //funcao construtor da classe 
   function cl_arrecadcompos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("arrecadcompos"); 
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
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
       $this->k00_arreckey = ($this->k00_arreckey == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_arreckey"]:$this->k00_arreckey);
       $this->k00_vlrhist = ($this->k00_vlrhist == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_vlrhist"]:$this->k00_vlrhist);
       $this->k00_correcao = ($this->k00_correcao == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_correcao"]:$this->k00_correcao);
       $this->k00_juros = ($this->k00_juros == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_juros"]:$this->k00_juros);
       $this->k00_multa = ($this->k00_multa == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_multa"]:$this->k00_multa);
     }else{
       $this->k00_sequencial = ($this->k00_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]:$this->k00_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k00_sequencial){ 
      $this->atualizacampos();
     if($this->k00_arreckey == null ){ 
       $this->erro_sql = " Campo arreckey nao Informado.";
       $this->erro_campo = "k00_arreckey";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_vlrhist == null ){ 
       $this->erro_sql = " Campo Histórico nao Informado.";
       $this->erro_campo = "k00_vlrhist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_correcao == null ){ 
       $this->erro_sql = " Campo Correcao nao Informado.";
       $this->erro_campo = "k00_correcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_juros == null ){ 
       $this->erro_sql = " Campo Juros nao Informado.";
       $this->erro_campo = "k00_juros";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k00_multa == null ){ 
       $this->erro_sql = " Campo Multa nao Informado.";
       $this->erro_campo = "k00_multa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k00_sequencial == "" || $k00_sequencial == null ){
       $result = db_query("select nextval('arrecadcompos_k00_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: arrecadcompos_k00_sequencial_seq do campo: k00_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k00_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from arrecadcompos_k00_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k00_sequencial)){
         $this->erro_sql = " Campo k00_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k00_sequencial = $k00_sequencial; 
       }
     }
     if(($this->k00_sequencial == null) || ($this->k00_sequencial == "") ){ 
       $this->erro_sql = " Campo k00_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into arrecadcompos(
                                       k00_sequencial 
                                      ,k00_arreckey 
                                      ,k00_vlrhist 
                                      ,k00_correcao 
                                      ,k00_juros 
                                      ,k00_multa 
                       )
                values (
                                $this->k00_sequencial 
                               ,$this->k00_arreckey 
                               ,$this->k00_vlrhist 
                               ,$this->k00_correcao 
                               ,$this->k00_juros 
                               ,$this->k00_multa 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "arrecadcompos ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "arrecadcompos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "arrecadcompos ($this->k00_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3022,11816,'','".AddSlashes(pg_result($resaco,0,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3022,17106,'','".AddSlashes(pg_result($resaco,0,'k00_arreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3022,17136,'','".AddSlashes(pg_result($resaco,0,'k00_vlrhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3022,17109,'','".AddSlashes(pg_result($resaco,0,'k00_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3022,17110,'','".AddSlashes(pg_result($resaco,0,'k00_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3022,17111,'','".AddSlashes(pg_result($resaco,0,'k00_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k00_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update arrecadcompos set ";
     $virgula = "";
     if(trim($this->k00_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"])){ 
       $sql  .= $virgula." k00_sequencial = $this->k00_sequencial ";
       $virgula = ",";
       if(trim($this->k00_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da arrecadação suspensa nao Informado.";
         $this->erro_campo = "k00_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_arreckey)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_arreckey"])){ 
       $sql  .= $virgula." k00_arreckey = $this->k00_arreckey ";
       $virgula = ",";
       if(trim($this->k00_arreckey) == null ){ 
         $this->erro_sql = " Campo arreckey nao Informado.";
         $this->erro_campo = "k00_arreckey";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_vlrhist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrhist"])){ 
       $sql  .= $virgula." k00_vlrhist = $this->k00_vlrhist ";
       $virgula = ",";
       if(trim($this->k00_vlrhist) == null ){ 
         $this->erro_sql = " Campo Histórico nao Informado.";
         $this->erro_campo = "k00_vlrhist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_correcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_correcao"])){ 
       $sql  .= $virgula." k00_correcao = $this->k00_correcao ";
       $virgula = ",";
       if(trim($this->k00_correcao) == null ){ 
         $this->erro_sql = " Campo Correcao nao Informado.";
         $this->erro_campo = "k00_correcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_juros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_juros"])){ 
       $sql  .= $virgula." k00_juros = $this->k00_juros ";
       $virgula = ",";
       if(trim($this->k00_juros) == null ){ 
         $this->erro_sql = " Campo Juros nao Informado.";
         $this->erro_campo = "k00_juros";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k00_multa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k00_multa"])){ 
       $sql  .= $virgula." k00_multa = $this->k00_multa ";
       $virgula = ",";
       if(trim($this->k00_multa) == null ){ 
         $this->erro_sql = " Campo Multa nao Informado.";
         $this->erro_campo = "k00_multa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k00_sequencial!=null){
       $sql .= " k00_sequencial = $this->k00_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k00_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$this->k00_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_sequencial"]) || $this->k00_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3022,11816,'".AddSlashes(pg_result($resaco,$conresaco,'k00_sequencial'))."','$this->k00_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_arreckey"]) || $this->k00_arreckey != "")
           $resac = db_query("insert into db_acount values($acount,3022,17106,'".AddSlashes(pg_result($resaco,$conresaco,'k00_arreckey'))."','$this->k00_arreckey',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_vlrhist"]) || $this->k00_vlrhist != "")
           $resac = db_query("insert into db_acount values($acount,3022,17136,'".AddSlashes(pg_result($resaco,$conresaco,'k00_vlrhist'))."','$this->k00_vlrhist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_correcao"]) || $this->k00_correcao != "")
           $resac = db_query("insert into db_acount values($acount,3022,17109,'".AddSlashes(pg_result($resaco,$conresaco,'k00_correcao'))."','$this->k00_correcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_juros"]) || $this->k00_juros != "")
           $resac = db_query("insert into db_acount values($acount,3022,17110,'".AddSlashes(pg_result($resaco,$conresaco,'k00_juros'))."','$this->k00_juros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k00_multa"]) || $this->k00_multa != "")
           $resac = db_query("insert into db_acount values($acount,3022,17111,'".AddSlashes(pg_result($resaco,$conresaco,'k00_multa'))."','$this->k00_multa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arrecadcompos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arrecadcompos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k00_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k00_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11816,'$k00_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3022,11816,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3022,17106,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_arreckey'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3022,17136,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_vlrhist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3022,17109,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_correcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3022,17110,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_juros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3022,17111,'','".AddSlashes(pg_result($resaco,$iresaco,'k00_multa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from arrecadcompos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k00_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k00_sequencial = $k00_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "arrecadcompos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k00_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "arrecadcompos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k00_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:arrecadcompos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecadcompos ";
     $sql .= "      inner join arreckey  on  arreckey.k00_sequencial = arrecadcompos.k00_arreckey";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = arreckey.k00_receit";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arrecadcompos.k00_sequencial = $k00_sequencial "; 
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
   function sql_query_file ( $k00_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from arrecadcompos ";
     $sql2 = "";
     if($dbwhere==""){
       if($k00_sequencial!=null ){
         $sql2 .= " where arrecadcompos.k00_sequencial = $k00_sequencial "; 
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