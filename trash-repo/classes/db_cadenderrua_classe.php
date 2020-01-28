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

//MODULO: Configuracoes
//CLASSE DA ENTIDADE cadenderrua
class cl_cadenderrua { 
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
   var $db74_sequencial = 0; 
   var $db74_cadendermunicipio = 0; 
   var $db74_descricao = null; 
   var $db74_bairroinicial = 0; 
   var $db74_bairrofinal = 0; 
   var $db74_numinicial = 0; 
   var $db74_numfinal = 0; 
   var $db74_cep = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db74_sequencial = int4 = Código da Rua 
                 db74_cadendermunicipio = int4 = Código do Município 
                 db74_descricao = varchar(100) = Descrição da Rua 
                 db74_bairroinicial = int4 = Bairro Inicial 
                 db74_bairrofinal = int4 = Bairro Final 
                 db74_numinicial = int4 = Número Inicial 
                 db74_numfinal = int4 = Número Final 
                 db74_cep = char(8) = Cep 
                 ";
   //funcao construtor da classe 
   function cl_cadenderrua() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cadenderrua"); 
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
       $this->db74_sequencial = ($this->db74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_sequencial"]:$this->db74_sequencial);
       $this->db74_cadendermunicipio = ($this->db74_cadendermunicipio == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_cadendermunicipio"]:$this->db74_cadendermunicipio);
       $this->db74_descricao = ($this->db74_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_descricao"]:$this->db74_descricao);
       $this->db74_bairroinicial = ($this->db74_bairroinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_bairroinicial"]:$this->db74_bairroinicial);
       $this->db74_bairrofinal = ($this->db74_bairrofinal == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_bairrofinal"]:$this->db74_bairrofinal);
       $this->db74_numinicial = ($this->db74_numinicial == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_numinicial"]:$this->db74_numinicial);
       $this->db74_numfinal = ($this->db74_numfinal == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_numfinal"]:$this->db74_numfinal);
       $this->db74_cep = ($this->db74_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_cep"]:$this->db74_cep);
     }else{
       $this->db74_sequencial = ($this->db74_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db74_sequencial"]:$this->db74_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db74_sequencial){ 
      $this->atualizacampos();
     if($this->db74_cadendermunicipio == null ){ 
       $this->erro_sql = " Campo Código do Município nao Informado.";
       $this->erro_campo = "db74_cadendermunicipio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db74_descricao == null ){ 
       $this->erro_sql = " Campo Descrição da Rua nao Informado.";
       $this->erro_campo = "db74_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db74_bairroinicial == null ){ 
       $this->db74_bairroinicial = "0";
     }
     if($this->db74_bairrofinal == null ){ 
       $this->db74_bairrofinal = "0";
     }
     if($this->db74_numinicial == null ){ 
       $this->db74_numinicial = "0";
     }
     if($this->db74_numfinal == null ){ 
       $this->db74_numfinal = "0";
     }
     if($db74_sequencial == "" || $db74_sequencial == null ){
       $result = db_query("select nextval('cadenderrua_db74_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cadenderrua_db74_sequencial_seq do campo: db74_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db74_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cadenderrua_db74_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db74_sequencial)){
         $this->erro_sql = " Campo db74_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db74_sequencial = $db74_sequencial; 
       }
     }
     if(($this->db74_sequencial == null) || ($this->db74_sequencial == "") ){ 
       $this->erro_sql = " Campo db74_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cadenderrua(
                                       db74_sequencial 
                                      ,db74_cadendermunicipio 
                                      ,db74_descricao 
                                      ,db74_bairroinicial 
                                      ,db74_bairrofinal 
                                      ,db74_numinicial 
                                      ,db74_numfinal 
                                      ,db74_cep 
                       )
                values (
                                $this->db74_sequencial 
                               ,$this->db74_cadendermunicipio 
                               ,'$this->db74_descricao' 
                               ,$this->db74_bairroinicial 
                               ,$this->db74_bairrofinal 
                               ,$this->db74_numinicial 
                               ,$this->db74_numfinal 
                               ,'$this->db74_cep' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Ruas do Municipio ($this->db74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Ruas do Municipio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Ruas do Municipio ($this->db74_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db74_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db74_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15857,'$this->db74_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2783,15857,'','".AddSlashes(pg_result($resaco,0,'db74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15858,'','".AddSlashes(pg_result($resaco,0,'db74_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15859,'','".AddSlashes(pg_result($resaco,0,'db74_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15860,'','".AddSlashes(pg_result($resaco,0,'db74_bairroinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15861,'','".AddSlashes(pg_result($resaco,0,'db74_bairrofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15862,'','".AddSlashes(pg_result($resaco,0,'db74_numinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15863,'','".AddSlashes(pg_result($resaco,0,'db74_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2783,15864,'','".AddSlashes(pg_result($resaco,0,'db74_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db74_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cadenderrua set ";
     $virgula = "";
     if(trim($this->db74_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_sequencial"])){ 
       $sql  .= $virgula." db74_sequencial = $this->db74_sequencial ";
       $virgula = ",";
       if(trim($this->db74_sequencial) == null ){ 
         $this->erro_sql = " Campo Código da Rua nao Informado.";
         $this->erro_campo = "db74_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db74_cadendermunicipio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_cadendermunicipio"])){ 
       $sql  .= $virgula." db74_cadendermunicipio = $this->db74_cadendermunicipio ";
       $virgula = ",";
       if(trim($this->db74_cadendermunicipio) == null ){ 
         $this->erro_sql = " Campo Código do Município nao Informado.";
         $this->erro_campo = "db74_cadendermunicipio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db74_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_descricao"])){ 
       $sql  .= $virgula." db74_descricao = '$this->db74_descricao' ";
       $virgula = ",";
       if(trim($this->db74_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição da Rua nao Informado.";
         $this->erro_campo = "db74_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db74_bairroinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_bairroinicial"])){ 
        if(trim($this->db74_bairroinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db74_bairroinicial"])){ 
           $this->db74_bairroinicial = "0" ; 
        } 
       $sql  .= $virgula." db74_bairroinicial = $this->db74_bairroinicial ";
       $virgula = ",";
     }
     if(trim($this->db74_bairrofinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_bairrofinal"])){ 
        if(trim($this->db74_bairrofinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db74_bairrofinal"])){ 
           $this->db74_bairrofinal = "0" ; 
        } 
       $sql  .= $virgula." db74_bairrofinal = $this->db74_bairrofinal ";
       $virgula = ",";
     }
     if(trim($this->db74_numinicial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_numinicial"])){ 
        if(trim($this->db74_numinicial)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db74_numinicial"])){ 
           $this->db74_numinicial = "0" ; 
        } 
       $sql  .= $virgula." db74_numinicial = $this->db74_numinicial ";
       $virgula = ",";
     }
     if(trim($this->db74_numfinal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_numfinal"])){ 
        if(trim($this->db74_numfinal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db74_numfinal"])){ 
           $this->db74_numfinal = "0" ; 
        } 
       $sql  .= $virgula." db74_numfinal = $this->db74_numfinal ";
       $virgula = ",";
     }
     if(trim($this->db74_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db74_cep"])){ 
       $sql  .= $virgula." db74_cep = '$this->db74_cep' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($db74_sequencial!=null){
       $sql .= " db74_sequencial = $this->db74_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db74_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15857,'$this->db74_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_sequencial"]) || $this->db74_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2783,15857,'".AddSlashes(pg_result($resaco,$conresaco,'db74_sequencial'))."','$this->db74_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_cadendermunicipio"]) || $this->db74_cadendermunicipio != "")
           $resac = db_query("insert into db_acount values($acount,2783,15858,'".AddSlashes(pg_result($resaco,$conresaco,'db74_cadendermunicipio'))."','$this->db74_cadendermunicipio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_descricao"]) || $this->db74_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2783,15859,'".AddSlashes(pg_result($resaco,$conresaco,'db74_descricao'))."','$this->db74_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_bairroinicial"]) || $this->db74_bairroinicial != "")
           $resac = db_query("insert into db_acount values($acount,2783,15860,'".AddSlashes(pg_result($resaco,$conresaco,'db74_bairroinicial'))."','$this->db74_bairroinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_bairrofinal"]) || $this->db74_bairrofinal != "")
           $resac = db_query("insert into db_acount values($acount,2783,15861,'".AddSlashes(pg_result($resaco,$conresaco,'db74_bairrofinal'))."','$this->db74_bairrofinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_numinicial"]) || $this->db74_numinicial != "")
           $resac = db_query("insert into db_acount values($acount,2783,15862,'".AddSlashes(pg_result($resaco,$conresaco,'db74_numinicial'))."','$this->db74_numinicial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_numfinal"]) || $this->db74_numfinal != "")
           $resac = db_query("insert into db_acount values($acount,2783,15863,'".AddSlashes(pg_result($resaco,$conresaco,'db74_numfinal'))."','$this->db74_numfinal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db74_cep"]) || $this->db74_cep != "")
           $resac = db_query("insert into db_acount values($acount,2783,15864,'".AddSlashes(pg_result($resaco,$conresaco,'db74_cep'))."','$this->db74_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Ruas do Municipio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Ruas do Municipio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db74_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db74_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15857,'$db74_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2783,15857,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15858,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_cadendermunicipio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15859,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15860,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_bairroinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15861,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_bairrofinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15862,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_numinicial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15863,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_numfinal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2783,15864,'','".AddSlashes(pg_result($resaco,$iresaco,'db74_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cadenderrua
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db74_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db74_sequencial = $db74_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Ruas do Municipio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db74_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Ruas do Municipio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db74_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db74_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cadenderrua";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderrua ";
     $sql .= "      inner join cadendermunicipio  on  cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio";
     $sql .= "      inner join cadenderestado  on  cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql2 = "";
     if($dbwhere==""){
       if($db74_sequencial!=null ){
         $sql2 .= " where cadenderrua.db74_sequencial = $db74_sequencial "; 
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
   function sql_query_file ( $db74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderrua ";
     $sql2 = "";
     if($dbwhere==""){
       if($db74_sequencial!=null ){
         $sql2 .= " where cadenderrua.db74_sequencial = $db74_sequencial "; 
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
   function sql_query_left_full ( $db74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from cadenderrua ";
     $sql .= "      left join cadendermunicipio         on db72_sequencial                = db74_cadendermunicipio   ";
     $sql .= "      left join cadenderestado            on db71_sequencial                = db72_cadenderestado      ";
     $sql .= "      left join cadenderbairrocadenderrua on db87_cadenderrua               = db74_sequencial          ";
     $sql .= "      left join cadenderbairro            on db73_sequencial                = db87_cadenderbairro      ";
     $sql .= "      left join cadenderruaruastipo       on db85_cadenderrua               = db74_sequencial          ";
     $sql .= "      left join cadenderruacep            on db86_cadenderrua               = db74_sequencial          ";
     $sql .= "      left join cadenderlocal             on db75_cadenderbairrocadenderrua = db87_sequencial          ";
     $sql .= "      left join endereco                  on db76_cadenderlocal             = db75_sequencial          ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($db74_sequencial!=null ){
         $sql2 .= " where cadenderrua.db74_sequencial = $db74_sequencial "; 
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
  
  function sql_query_endereco_full ( $db74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     
    $sql .= " from cadenderrua ";
    $sql .= "      left join cadendermunicipio         on db72_sequencial                = db74_cadendermunicipio ";   
    $sql .= "      left join cadenderestado            on db71_sequencial                = db72_cadenderestado    ";      
    $sql .= "      left join cadenderbairrocadenderrua on db87_cadenderrua               = db74_sequencial        ";      
    $sql .= "      left join cadenderbairro            on db73_sequencial                = db87_cadenderbairro    ";
    $sql .= "      left join cadenderruaruastipo       on db85_cadenderrua               = db74_sequencial        ";  
    $sql .= "      left join cadenderlocal             on db75_cadenderbairrocadenderrua = db87_sequencial        ";
    $sql .= "      left join endereco                  on db76_cadenderlocal             = db75_sequencial        ";
    $sql2 = "";
    if($dbwhere==""){
      if($db74_sequencial!=null ){
        $sql2 .= " where cadenderrua.db74_sequencial = $db74_sequencial ";
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
  
  
   function sql_query_rua_codigo ( $db74_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cadenderrua ";
     $sql .= "      inner join cadendermunicipio on cadendermunicipio.db72_sequencial = cadenderrua.db74_cadendermunicipio";
     $sql .= "      inner join cadenderestado on cadenderestado.db71_sequencial = cadendermunicipio.db72_cadenderestado";
     $sql .= "      inner join cadenderruaruastipo on cadenderruaruastipo.db85_cadenderrua = cadenderrua.db74_sequencial ";
     $sql .= "      inner join cadenderbairrocadenderrua on cadenderbairrocadenderrua.db87_cadenderrua = cadenderrua.db74_sequencial ";
     $sql .= "      inner join cadenderbairro on cadenderbairro.db73_sequencial = cadenderbairrocadenderrua.db87_cadenderbairro ";
     $sql2 = "";
     if($dbwhere==""){
       if($db74_sequencial!=null ){
         $sql2 .= " where cadenderrua.db74_sequencial = $db74_sequencial "; 
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