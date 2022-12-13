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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_layoutformat
class cl_db_layoutformat { 
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
   var $db53_codigo = 0; 
   var $db53_descr = null; 
   var $db53_mascara = null; 
   var $db53_tipo = 0; 
   var $db53_tamanho = 0; 
   var $db53_decimais = 0; 
   var $db53_caracdec = null; 
   var $db53_alinha = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db53_codigo = int4 = Código da formatação 
                 db53_descr = varchar(40) = Descrição 
                 db53_mascara = varchar(40) = Máscara do campo 
                 db53_tipo = int4 = Tipo do campo 
                 db53_tamanho = int4 = Espaço ocupado 
                 db53_decimais = int4 = Casas de decimais 
                 db53_caracdec = varchar(1) = Separador 
                 db53_alinha = varchar(1) = Alinhamento 
                 ";
   //funcao construtor da classe 
   function cl_db_layoutformat() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_layoutformat"); 
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
       $this->db53_codigo = ($this->db53_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_codigo"]:$this->db53_codigo);
       $this->db53_descr = ($this->db53_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_descr"]:$this->db53_descr);
       $this->db53_mascara = ($this->db53_mascara == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_mascara"]:$this->db53_mascara);
       $this->db53_tipo = ($this->db53_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_tipo"]:$this->db53_tipo);
       $this->db53_tamanho = ($this->db53_tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_tamanho"]:$this->db53_tamanho);
       $this->db53_decimais = ($this->db53_decimais == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_decimais"]:$this->db53_decimais);
       $this->db53_caracdec = ($this->db53_caracdec == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_caracdec"]:$this->db53_caracdec);
       $this->db53_alinha = ($this->db53_alinha == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_alinha"]:$this->db53_alinha);
     }else{
       $this->db53_codigo = ($this->db53_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db53_codigo"]:$this->db53_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db53_codigo){ 
      $this->atualizacampos();
     if($this->db53_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db53_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db53_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do campo nao Informado.";
       $this->erro_campo = "db53_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db53_tamanho == null ){ 
       $this->db53_tamanho = "0";
     }
     if($this->db53_decimais == null ){ 
       $this->db53_decimais = "0";
     }
     if($this->db53_alinha == null ){ 
       $this->erro_sql = " Campo Alinhamento nao Informado.";
       $this->erro_campo = "db53_alinha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db53_codigo == "" || $db53_codigo == null ){
       $result = db_query("select nextval('db_layoutformat_db53_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_layoutformat_db53_codigo_seq do campo: db53_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db53_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_layoutformat_db53_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db53_codigo)){
         $this->erro_sql = " Campo db53_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db53_codigo = $db53_codigo; 
       }
     }
     if(($this->db53_codigo == null) || ($this->db53_codigo == "") ){ 
       $this->erro_sql = " Campo db53_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_layoutformat(
                                       db53_codigo 
                                      ,db53_descr 
                                      ,db53_mascara 
                                      ,db53_tipo 
                                      ,db53_tamanho 
                                      ,db53_decimais 
                                      ,db53_caracdec 
                                      ,db53_alinha 
                       )
                values (
                                $this->db53_codigo 
                               ,'$this->db53_descr' 
                               ,'$this->db53_mascara' 
                               ,$this->db53_tipo 
                               ,$this->db53_tamanho 
                               ,$this->db53_decimais 
                               ,'$this->db53_caracdec' 
                               ,'$this->db53_alinha' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro da formatação dos campos ($this->db53_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro da formatação dos campos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro da formatação dos campos ($this->db53_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db53_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db53_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9078,'$this->db53_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1556,9078,'','".AddSlashes(pg_result($resaco,0,'db53_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9081,'','".AddSlashes(pg_result($resaco,0,'db53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9082,'','".AddSlashes(pg_result($resaco,0,'db53_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9083,'','".AddSlashes(pg_result($resaco,0,'db53_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9084,'','".AddSlashes(pg_result($resaco,0,'db53_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9085,'','".AddSlashes(pg_result($resaco,0,'db53_decimais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9086,'','".AddSlashes(pg_result($resaco,0,'db53_caracdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1556,9103,'','".AddSlashes(pg_result($resaco,0,'db53_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db53_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_layoutformat set ";
     $virgula = "";
     if(trim($this->db53_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_codigo"])){ 
       $sql  .= $virgula." db53_codigo = $this->db53_codigo ";
       $virgula = ",";
       if(trim($this->db53_codigo) == null ){ 
         $this->erro_sql = " Campo Código da formatação nao Informado.";
         $this->erro_campo = "db53_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db53_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_descr"])){ 
       $sql  .= $virgula." db53_descr = '$this->db53_descr' ";
       $virgula = ",";
       if(trim($this->db53_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db53_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db53_mascara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_mascara"])){ 
       $sql  .= $virgula." db53_mascara = '$this->db53_mascara' ";
       $virgula = ",";
     }
     if(trim($this->db53_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_tipo"])){ 
       $sql  .= $virgula." db53_tipo = $this->db53_tipo ";
       $virgula = ",";
       if(trim($this->db53_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do campo nao Informado.";
         $this->erro_campo = "db53_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db53_tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_tamanho"])){ 
        if(trim($this->db53_tamanho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db53_tamanho"])){ 
           $this->db53_tamanho = "0" ; 
        } 
       $sql  .= $virgula." db53_tamanho = $this->db53_tamanho ";
       $virgula = ",";
     }
     if(trim($this->db53_decimais)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_decimais"])){ 
        if(trim($this->db53_decimais)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db53_decimais"])){ 
           $this->db53_decimais = "0" ; 
        } 
       $sql  .= $virgula." db53_decimais = $this->db53_decimais ";
       $virgula = ",";
     }
     if(trim($this->db53_caracdec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_caracdec"])){ 
       $sql  .= $virgula." db53_caracdec = '$this->db53_caracdec' ";
       $virgula = ",";
     }
     if(trim($this->db53_alinha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db53_alinha"])){ 
       $sql  .= $virgula." db53_alinha = '$this->db53_alinha' ";
       $virgula = ",";
       if(trim($this->db53_alinha) == null ){ 
         $this->erro_sql = " Campo Alinhamento nao Informado.";
         $this->erro_campo = "db53_alinha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db53_codigo!=null){
       $sql .= " db53_codigo = $this->db53_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db53_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9078,'$this->db53_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1556,9078,'".AddSlashes(pg_result($resaco,$conresaco,'db53_codigo'))."','$this->db53_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_descr"]))
           $resac = db_query("insert into db_acount values($acount,1556,9081,'".AddSlashes(pg_result($resaco,$conresaco,'db53_descr'))."','$this->db53_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_mascara"]))
           $resac = db_query("insert into db_acount values($acount,1556,9082,'".AddSlashes(pg_result($resaco,$conresaco,'db53_mascara'))."','$this->db53_mascara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1556,9083,'".AddSlashes(pg_result($resaco,$conresaco,'db53_tipo'))."','$this->db53_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_tamanho"]))
           $resac = db_query("insert into db_acount values($acount,1556,9084,'".AddSlashes(pg_result($resaco,$conresaco,'db53_tamanho'))."','$this->db53_tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_decimais"]))
           $resac = db_query("insert into db_acount values($acount,1556,9085,'".AddSlashes(pg_result($resaco,$conresaco,'db53_decimais'))."','$this->db53_decimais',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_caracdec"]))
           $resac = db_query("insert into db_acount values($acount,1556,9086,'".AddSlashes(pg_result($resaco,$conresaco,'db53_caracdec'))."','$this->db53_caracdec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db53_alinha"]))
           $resac = db_query("insert into db_acount values($acount,1556,9103,'".AddSlashes(pg_result($resaco,$conresaco,'db53_alinha'))."','$this->db53_alinha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da formatação dos campos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db53_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da formatação dos campos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db53_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db53_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db53_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db53_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9078,'$db53_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1556,9078,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9081,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9082,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_mascara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9083,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9084,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9085,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_decimais'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9086,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_caracdec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1556,9103,'','".AddSlashes(pg_result($resaco,$iresaco,'db53_alinha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_layoutformat
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db53_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db53_codigo = $db53_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro da formatação dos campos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db53_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro da formatação dos campos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db53_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db53_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_layoutformat";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db53_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutformat ";
     $sql2 = "";
     if($dbwhere==""){
       if($db53_codigo!=null ){
         $sql2 .= " where db_layoutformat.db53_codigo = $db53_codigo "; 
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
   function sql_query_file ( $db53_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_layoutformat ";
     $sql2 = "";
     if($dbwhere==""){
       if($db53_codigo!=null ){
         $sql2 .= " where db_layoutformat.db53_codigo = $db53_codigo "; 
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