<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: material
//CLASSE DA ENTIDADE matestoquedevitem
class cl_matestoquedevitem { 
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
   var $m46_codigo = 0; 
   var $m46_codmatestoquedev = 0; 
   var $m46_codmatrequiitem = 0; 
   var $m46_codatendrequiitem = 0; 
   var $m46_codmatmater = 0; 
   var $m46_quantdev = 0; 
   var $m46_quantexistia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m46_codigo = int4 = Código 
                 m46_codmatestoquedev = int8 = Código da Devolução 
                 m46_codmatrequiitem = int8 = Codigo requisição item 
                 m46_codatendrequiitem = int8 = Código item do atendimento 
                 m46_codmatmater = int8 = Código do material 
                 m46_quantdev = float8 = Quant. Devolvida 
                 m46_quantexistia = float8 = Quant. Existia 
                 ";
   //funcao construtor da classe 
   function cl_matestoquedevitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matestoquedevitem"); 
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
       $this->m46_codigo = ($this->m46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codigo"]:$this->m46_codigo);
       $this->m46_codmatestoquedev = ($this->m46_codmatestoquedev == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codmatestoquedev"]:$this->m46_codmatestoquedev);
       $this->m46_codmatrequiitem = ($this->m46_codmatrequiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codmatrequiitem"]:$this->m46_codmatrequiitem);
       $this->m46_codatendrequiitem = ($this->m46_codatendrequiitem == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codatendrequiitem"]:$this->m46_codatendrequiitem);
       $this->m46_codmatmater = ($this->m46_codmatmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codmatmater"]:$this->m46_codmatmater);
       $this->m46_quantdev = ($this->m46_quantdev == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_quantdev"]:$this->m46_quantdev);
       $this->m46_quantexistia = ($this->m46_quantexistia == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_quantexistia"]:$this->m46_quantexistia);
     }else{
       $this->m46_codigo = ($this->m46_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["m46_codigo"]:$this->m46_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($m46_codigo){ 
      $this->atualizacampos();
     if($this->m46_codmatestoquedev == null ){ 
       $this->erro_sql = " Campo Código da Devolução nao Informado.";
       $this->erro_campo = "m46_codmatestoquedev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m46_codmatrequiitem == null ){ 
       $this->erro_sql = " Campo Codigo requisição item nao Informado.";
       $this->erro_campo = "m46_codmatrequiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m46_codatendrequiitem == null ){ 
       $this->erro_sql = " Campo Código item do atendimento nao Informado.";
       $this->erro_campo = "m46_codatendrequiitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m46_codmatmater == null ){ 
       $this->erro_sql = " Campo Código do material nao Informado.";
       $this->erro_campo = "m46_codmatmater";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m46_quantdev == null ){ 
       $this->erro_sql = " Campo Quant. Devolvida nao Informado.";
       $this->erro_campo = "m46_quantdev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m46_quantexistia == null ){ 
       $this->erro_sql = " Campo Quant. Existia nao Informado.";
       $this->erro_campo = "m46_quantexistia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m46_codigo == "" || $m46_codigo == null ){
       $result = db_query("select nextval('matestoquedevitem_m46_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matestoquedevitem_m46_codigo_seq do campo: m46_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->m46_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matestoquedevitem_m46_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $m46_codigo)){
         $this->erro_sql = " Campo m46_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m46_codigo = $m46_codigo; 
       }
     }
     if(($this->m46_codigo == null) || ($this->m46_codigo == "") ){ 
       $this->erro_sql = " Campo m46_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matestoquedevitem(
                                       m46_codigo 
                                      ,m46_codmatestoquedev 
                                      ,m46_codmatrequiitem 
                                      ,m46_codatendrequiitem 
                                      ,m46_codmatmater 
                                      ,m46_quantdev 
                                      ,m46_quantexistia 
                       )
                values (
                                $this->m46_codigo 
                               ,$this->m46_codmatestoquedev 
                               ,$this->m46_codmatrequiitem 
                               ,$this->m46_codatendrequiitem 
                               ,$this->m46_codmatmater 
                               ,$this->m46_quantdev 
                               ,$this->m46_quantexistia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "matestoquedevitem ($this->m46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "matestoquedevitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "matestoquedevitem ($this->m46_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m46_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m46_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6912,'$this->m46_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1137,6912,'','".AddSlashes(pg_result($resaco,0,'m46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6915,'','".AddSlashes(pg_result($resaco,0,'m46_codmatestoquedev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6916,'','".AddSlashes(pg_result($resaco,0,'m46_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6948,'','".AddSlashes(pg_result($resaco,0,'m46_codatendrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6921,'','".AddSlashes(pg_result($resaco,0,'m46_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6913,'','".AddSlashes(pg_result($resaco,0,'m46_quantdev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1137,6914,'','".AddSlashes(pg_result($resaco,0,'m46_quantexistia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m46_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matestoquedevitem set ";
     $virgula = "";
     if(trim($this->m46_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_codigo"])){ 
       $sql  .= $virgula." m46_codigo = $this->m46_codigo ";
       $virgula = ",";
       if(trim($this->m46_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "m46_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_codmatestoquedev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatestoquedev"])){ 
       $sql  .= $virgula." m46_codmatestoquedev = $this->m46_codmatestoquedev ";
       $virgula = ",";
       if(trim($this->m46_codmatestoquedev) == null ){ 
         $this->erro_sql = " Campo Código da Devolução nao Informado.";
         $this->erro_campo = "m46_codmatestoquedev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_codmatrequiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatrequiitem"])){ 
       $sql  .= $virgula." m46_codmatrequiitem = $this->m46_codmatrequiitem ";
       $virgula = ",";
       if(trim($this->m46_codmatrequiitem) == null ){ 
         $this->erro_sql = " Campo Codigo requisição item nao Informado.";
         $this->erro_campo = "m46_codmatrequiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_codatendrequiitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_codatendrequiitem"])){ 
       $sql  .= $virgula." m46_codatendrequiitem = $this->m46_codatendrequiitem ";
       $virgula = ",";
       if(trim($this->m46_codatendrequiitem) == null ){ 
         $this->erro_sql = " Campo Código item do atendimento nao Informado.";
         $this->erro_campo = "m46_codatendrequiitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_codmatmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatmater"])){ 
       $sql  .= $virgula." m46_codmatmater = $this->m46_codmatmater ";
       $virgula = ",";
       if(trim($this->m46_codmatmater) == null ){ 
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m46_codmatmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_quantdev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_quantdev"])){ 
       $sql  .= $virgula." m46_quantdev = $this->m46_quantdev ";
       $virgula = ",";
       if(trim($this->m46_quantdev) == null ){ 
         $this->erro_sql = " Campo Quant. Devolvida nao Informado.";
         $this->erro_campo = "m46_quantdev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m46_quantexistia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m46_quantexistia"])){ 
       $sql  .= $virgula." m46_quantexistia = $this->m46_quantexistia ";
       $virgula = ",";
       if(trim($this->m46_quantexistia) == null ){ 
         $this->erro_sql = " Campo Quant. Existia nao Informado.";
         $this->erro_campo = "m46_quantexistia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m46_codigo!=null){
       $sql .= " m46_codigo = $this->m46_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m46_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6912,'$this->m46_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1137,6912,'".AddSlashes(pg_result($resaco,$conresaco,'m46_codigo'))."','$this->m46_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatestoquedev"]))
           $resac = db_query("insert into db_acount values($acount,1137,6915,'".AddSlashes(pg_result($resaco,$conresaco,'m46_codmatestoquedev'))."','$this->m46_codmatestoquedev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatrequiitem"]))
           $resac = db_query("insert into db_acount values($acount,1137,6916,'".AddSlashes(pg_result($resaco,$conresaco,'m46_codmatrequiitem'))."','$this->m46_codmatrequiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_codatendrequiitem"]))
           $resac = db_query("insert into db_acount values($acount,1137,6948,'".AddSlashes(pg_result($resaco,$conresaco,'m46_codatendrequiitem'))."','$this->m46_codatendrequiitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_codmatmater"]))
           $resac = db_query("insert into db_acount values($acount,1137,6921,'".AddSlashes(pg_result($resaco,$conresaco,'m46_codmatmater'))."','$this->m46_codmatmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_quantdev"]))
           $resac = db_query("insert into db_acount values($acount,1137,6913,'".AddSlashes(pg_result($resaco,$conresaco,'m46_quantdev'))."','$this->m46_quantdev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m46_quantexistia"]))
           $resac = db_query("insert into db_acount values($acount,1137,6914,'".AddSlashes(pg_result($resaco,$conresaco,'m46_quantexistia'))."','$this->m46_quantexistia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedevitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedevitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m46_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m46_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6912,'$m46_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1137,6912,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6915,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_codmatestoquedev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6916,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_codmatrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6948,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_codatendrequiitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6921,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_codmatmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6913,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_quantdev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1137,6914,'','".AddSlashes(pg_result($resaco,$iresaco,'m46_quantexistia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matestoquedevitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m46_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m46_codigo = $m46_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "matestoquedevitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m46_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "matestoquedevitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m46_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m46_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matestoquedevitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedevitem ";
     $sql .= "      inner join matmater  on  matmater.m60_codmater = matestoquedevitem.m46_codmatmater";
     $sql .= "      inner join matrequiitem  on  matrequiitem.m41_codigo = matestoquedevitem.m46_codmatrequiitem";
     $sql .= "      inner join atendrequiitem  on  atendrequiitem.m43_codigo = matestoquedevitem.m46_codatendrequiitem";
     $sql .= "      inner join matestoquedev  on  matestoquedev.m45_codigo = matestoquedevitem.m46_codmatestoquedev";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql .= "      inner join matmater as e on  e.m60_codmater = matrequiitem.m41_codmatmater";
     $sql .= "      inner join matrequi  as a on   a.m40_codigo = matrequiitem.m41_codmatrequi";
     $sql .= "      inner join matrequiitem  as b on   b.m41_codigo = atendrequiitem.m43_codmatrequiitem";
     $sql .= "      inner join atendrequi  as c on   c.m42_codigo = atendrequiitem.m43_codatendrequi";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = matestoquedev.m45_login";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoquedev.m45_depto";
     $sql .= "      inner join matrequi  as d on   d.m40_codigo = matestoquedev.m45_codmatrequi";
     $sql .= "      inner join atendrequi  as f on   f.m42_codigo = matestoquedev.m45_codatendrequi";
     $sql2 = "";
     if($dbwhere==""){
       if($m46_codigo!=null ){
         $sql2 .= " where matestoquedevitem.m46_codigo = $m46_codigo "; 
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
   function sql_query_file ( $m46_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matestoquedevitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($m46_codigo!=null ){
         $sql2 .= " where matestoquedevitem.m46_codigo = $m46_codigo "; 
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