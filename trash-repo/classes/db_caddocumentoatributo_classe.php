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

//MODULO: configuracoes
//CLASSE DA ENTIDADE caddocumentoatributo
class cl_caddocumentoatributo { 
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
   var $db45_sequencial = 0; 
   var $db45_caddocumento = 0; 
   var $db45_codcam = 0; 
   var $db45_descricao = null; 
   var $db45_valordefault = null; 
   var $db45_tipo = 0; 
   var $db45_tamanho = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db45_sequencial = int4 = Código Atributo 
                 db45_caddocumento = int4 = Documento 
                 db45_codcam = int4 = Campo Referência 
                 db45_descricao = varchar(100) = Descrição 
                 db45_valordefault = varchar(100) = Valor Default 
                 db45_tipo = int4 = Tipo de Atributo 
                 db45_tamanho = int4 = Tamanho do Campo 
                 ";
   //funcao construtor da classe 
   function cl_caddocumentoatributo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("caddocumentoatributo"); 
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
       $this->db45_sequencial = ($this->db45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]:$this->db45_sequencial);
       $this->db45_caddocumento = ($this->db45_caddocumento == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_caddocumento"]:$this->db45_caddocumento);
       $this->db45_codcam = ($this->db45_codcam == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_codcam"]:$this->db45_codcam);
       $this->db45_descricao = ($this->db45_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_descricao"]:$this->db45_descricao);
       $this->db45_valordefault = ($this->db45_valordefault == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_valordefault"]:$this->db45_valordefault);
       $this->db45_tipo = ($this->db45_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_tipo"]:$this->db45_tipo);
       $this->db45_tamanho = ($this->db45_tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_tamanho"]:$this->db45_tamanho);
     }else{
       $this->db45_sequencial = ($this->db45_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]:$this->db45_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db45_sequencial){ 
      $this->atualizacampos();
     if($this->db45_caddocumento == null ){ 
       $this->erro_sql = " Campo Documento nao Informado.";
       $this->erro_campo = "db45_caddocumento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db45_codcam == null ){ 
       $this->db45_codcam = "0";
     }
     if($this->db45_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db45_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db45_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de Atributo nao Informado.";
       $this->erro_campo = "db45_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db45_tamanho == null ){ 
       $this->erro_sql = " Campo Tamanho do Campo nao Informado.";
       $this->erro_campo = "db45_tamanho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db45_sequencial == "" || $db45_sequencial == null ){
       $result = db_query("select nextval('caddocumentoatributo_db45_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: caddocumentoatributo_db45_sequencial_seq do campo: db45_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db45_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from caddocumentoatributo_db45_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db45_sequencial)){
         $this->erro_sql = " Campo db45_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db45_sequencial = $db45_sequencial; 
       }
     }
     if(($this->db45_sequencial == null) || ($this->db45_sequencial == "") ){ 
       $this->erro_sql = " Campo db45_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into caddocumentoatributo(
                                       db45_sequencial 
                                      ,db45_caddocumento 
                                      ,db45_codcam 
                                      ,db45_descricao 
                                      ,db45_valordefault 
                                      ,db45_tipo 
                                      ,db45_tamanho 
                       )
                values (
                                $this->db45_sequencial 
                               ,$this->db45_caddocumento 
                               ,$this->db45_codcam 
                               ,'$this->db45_descricao' 
                               ,'$this->db45_valordefault' 
                               ,$this->db45_tipo 
                               ,$this->db45_tamanho 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atributos do Documento ($this->db45_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atributos do Documento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atributos do Documento ($this->db45_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db45_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15678,'$this->db45_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2750,15678,'','".AddSlashes(pg_result($resaco,0,'db45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15679,'','".AddSlashes(pg_result($resaco,0,'db45_caddocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15680,'','".AddSlashes(pg_result($resaco,0,'db45_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15681,'','".AddSlashes(pg_result($resaco,0,'db45_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15682,'','".AddSlashes(pg_result($resaco,0,'db45_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,15683,'','".AddSlashes(pg_result($resaco,0,'db45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2750,17924,'','".AddSlashes(pg_result($resaco,0,'db45_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db45_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update caddocumentoatributo set ";
     $virgula = "";
     if(trim($this->db45_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_sequencial"])){ 
       $sql  .= $virgula." db45_sequencial = $this->db45_sequencial ";
       $virgula = ",";
       if(trim($this->db45_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Atributo nao Informado.";
         $this->erro_campo = "db45_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_caddocumento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_caddocumento"])){ 
       $sql  .= $virgula." db45_caddocumento = $this->db45_caddocumento ";
       $virgula = ",";
       if(trim($this->db45_caddocumento) == null ){ 
         $this->erro_sql = " Campo Documento nao Informado.";
         $this->erro_campo = "db45_caddocumento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_codcam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"])){ 
        if(trim($this->db45_codcam)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"])){ 
           $this->db45_codcam = "0" ; 
        } 
       $sql  .= $virgula." db45_codcam = $this->db45_codcam ";
       $virgula = ",";
     }
     if(trim($this->db45_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_descricao"])){ 
       $sql  .= $virgula." db45_descricao = '$this->db45_descricao' ";
       $virgula = ",";
       if(trim($this->db45_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db45_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_valordefault)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_valordefault"])){ 
       $sql  .= $virgula." db45_valordefault = '$this->db45_valordefault' ";
       $virgula = ",";
     }
     if(trim($this->db45_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_tipo"])){ 
       $sql  .= $virgula." db45_tipo = $this->db45_tipo ";
       $virgula = ",";
       if(trim($this->db45_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de Atributo nao Informado.";
         $this->erro_campo = "db45_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db45_tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db45_tamanho"])){ 
       $sql  .= $virgula." db45_tamanho = $this->db45_tamanho ";
       $virgula = ",";
       if(trim($this->db45_tamanho) == null ){ 
         $this->erro_sql = " Campo Tamanho do Campo nao Informado.";
         $this->erro_campo = "db45_tamanho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db45_sequencial!=null){
       $sql .= " db45_sequencial = $this->db45_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db45_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15678,'$this->db45_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_sequencial"]) || $this->db45_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2750,15678,'".AddSlashes(pg_result($resaco,$conresaco,'db45_sequencial'))."','$this->db45_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_caddocumento"]) || $this->db45_caddocumento != "")
           $resac = db_query("insert into db_acount values($acount,2750,15679,'".AddSlashes(pg_result($resaco,$conresaco,'db45_caddocumento'))."','$this->db45_caddocumento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_codcam"]) || $this->db45_codcam != "")
           $resac = db_query("insert into db_acount values($acount,2750,15680,'".AddSlashes(pg_result($resaco,$conresaco,'db45_codcam'))."','$this->db45_codcam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_descricao"]) || $this->db45_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2750,15681,'".AddSlashes(pg_result($resaco,$conresaco,'db45_descricao'))."','$this->db45_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_valordefault"]) || $this->db45_valordefault != "")
           $resac = db_query("insert into db_acount values($acount,2750,15682,'".AddSlashes(pg_result($resaco,$conresaco,'db45_valordefault'))."','$this->db45_valordefault',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_tipo"]) || $this->db45_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2750,15683,'".AddSlashes(pg_result($resaco,$conresaco,'db45_tipo'))."','$this->db45_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db45_tamanho"]) || $this->db45_tamanho != "")
           $resac = db_query("insert into db_acount values($acount,2750,17924,'".AddSlashes(pg_result($resaco,$conresaco,'db45_tamanho'))."','$this->db45_tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos do Documento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atributos do Documento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db45_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db45_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15678,'$db45_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2750,15678,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15679,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_caddocumento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15680,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_codcam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15681,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15682,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_valordefault'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,15683,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2750,17924,'','".AddSlashes(pg_result($resaco,$iresaco,'db45_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from caddocumentoatributo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db45_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db45_sequencial = $db45_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atributos do Documento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db45_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Atributos do Documento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db45_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db45_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:caddocumentoatributo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db45_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caddocumentoatributo ";
     $sql .= "      left  join db_syscampo  on  db_syscampo.codcam = caddocumentoatributo.db45_codcam";
     $sql .= "      inner join caddocumento  on  caddocumento.db44_sequencial = caddocumentoatributo.db45_caddocumento";
     $sql2 = "";
     if($dbwhere==""){
       if($db45_sequencial!=null ){
         $sql2 .= " where caddocumentoatributo.db45_sequencial = $db45_sequencial "; 
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
   function sql_query_file ( $db45_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from caddocumentoatributo ";
     $sql2 = "";
     if($dbwhere==""){
       if($db45_sequencial!=null ){
         $sql2 .= " where caddocumentoatributo.db45_sequencial = $db45_sequencial "; 
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