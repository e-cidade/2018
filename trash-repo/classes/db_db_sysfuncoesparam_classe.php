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
//CLASSE DA ENTIDADE db_sysfuncoesparam
class cl_db_sysfuncoesparam { 
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
   var $db42_sysfuncoesparam = 0; 
   var $db42_funcao = 0; 
   var $db42_ordem = 0; 
   var $db42_nome = null; 
   var $db42_tipo = null; 
   var $db42_tamanho = 0; 
   var $db42_precisao = 0; 
   var $db42_valor_default = null; 
   var $db42_descricao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db42_sysfuncoesparam = int4 = Codigo 
                 db42_funcao = int4 = Código Função 
                 db42_ordem = int4 = Ordem na assinatura da funcao 
                 db42_nome = varchar(20) = Nome do parametro na assinatura 
                 db42_tipo = varchar(20) = Tipo de dado 
                 db42_tamanho = int4 = Tamanho 
                 db42_precisao = int4 = Precisao 
                 db42_valor_default = text = Valor default 
                 db42_descricao = text = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_db_sysfuncoesparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysfuncoesparam"); 
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
       $this->db42_sysfuncoesparam = ($this->db42_sysfuncoesparam == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_sysfuncoesparam"]:$this->db42_sysfuncoesparam);
       $this->db42_funcao = ($this->db42_funcao == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_funcao"]:$this->db42_funcao);
       $this->db42_ordem = ($this->db42_ordem == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_ordem"]:$this->db42_ordem);
       $this->db42_nome = ($this->db42_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_nome"]:$this->db42_nome);
       $this->db42_tipo = ($this->db42_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_tipo"]:$this->db42_tipo);
       $this->db42_tamanho = ($this->db42_tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_tamanho"]:$this->db42_tamanho);
       $this->db42_precisao = ($this->db42_precisao == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_precisao"]:$this->db42_precisao);
       $this->db42_valor_default = ($this->db42_valor_default == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_valor_default"]:$this->db42_valor_default);
       $this->db42_descricao = ($this->db42_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_descricao"]:$this->db42_descricao);
     }else{
       $this->db42_sysfuncoesparam = ($this->db42_sysfuncoesparam == ""?@$GLOBALS["HTTP_POST_VARS"]["db42_sysfuncoesparam"]:$this->db42_sysfuncoesparam);
     }
   }
   // funcao para inclusao
   function incluir ($db42_sysfuncoesparam){ 
      $this->atualizacampos();
     if($this->db42_funcao == null ){ 
       $this->erro_sql = " Campo Código Função nao Informado.";
       $this->erro_campo = "db42_funcao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db42_ordem == null ){ 
       $this->erro_sql = " Campo Ordem na assinatura da funcao nao Informado.";
       $this->erro_campo = "db42_ordem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db42_nome == null ){ 
       $this->erro_sql = " Campo Nome do parametro na assinatura nao Informado.";
       $this->erro_campo = "db42_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db42_tipo == null ){ 
       $this->erro_sql = " Campo Tipo de dado nao Informado.";
       $this->erro_campo = "db42_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db42_tamanho == null ){ 
       $this->db42_tamanho = "0";
     }
     if($this->db42_precisao == null ){ 
       $this->db42_precisao = "0";
     }
     if($this->db42_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db42_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db42_sysfuncoesparam == "" || $db42_sysfuncoesparam == null ){
       $result = db_query("select nextval('db_sysfuncoesparam_db42_sysfuncoesparam_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_sysfuncoesparam_db42_sysfuncoesparam_seq do campo: db42_sysfuncoesparam"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db42_sysfuncoesparam = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_sysfuncoesparam_db42_sysfuncoesparam_seq");
       if(($result != false) && (pg_result($result,0,0) < $db42_sysfuncoesparam)){
         $this->erro_sql = " Campo db42_sysfuncoesparam maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db42_sysfuncoesparam = $db42_sysfuncoesparam; 
       }
     }
     if(($this->db42_sysfuncoesparam == null) || ($this->db42_sysfuncoesparam == "") ){ 
       $this->erro_sql = " Campo db42_sysfuncoesparam nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysfuncoesparam(
                                       db42_sysfuncoesparam 
                                      ,db42_funcao 
                                      ,db42_ordem 
                                      ,db42_nome 
                                      ,db42_tipo 
                                      ,db42_tamanho 
                                      ,db42_precisao 
                                      ,db42_valor_default 
                                      ,db42_descricao 
                       )
                values (
                                $this->db42_sysfuncoesparam 
                               ,$this->db42_funcao 
                               ,$this->db42_ordem 
                               ,'$this->db42_nome' 
                               ,'$this->db42_tipo' 
                               ,$this->db42_tamanho 
                               ,$this->db42_precisao 
                               ,'$this->db42_valor_default' 
                               ,'$this->db42_descricao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parametros das funcoes ($this->db42_sysfuncoesparam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parametros das funcoes já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parametros das funcoes ($this->db42_sysfuncoesparam) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db42_sysfuncoesparam;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db42_sysfuncoesparam));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9471,'$this->db42_sysfuncoesparam','I')");
       $resac = db_query("insert into db_acount values($acount,1626,9471,'','".AddSlashes(pg_result($resaco,0,'db42_sysfuncoesparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9472,'','".AddSlashes(pg_result($resaco,0,'db42_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9473,'','".AddSlashes(pg_result($resaco,0,'db42_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9474,'','".AddSlashes(pg_result($resaco,0,'db42_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9475,'','".AddSlashes(pg_result($resaco,0,'db42_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9476,'','".AddSlashes(pg_result($resaco,0,'db42_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9477,'','".AddSlashes(pg_result($resaco,0,'db42_precisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9478,'','".AddSlashes(pg_result($resaco,0,'db42_valor_default'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1626,9479,'','".AddSlashes(pg_result($resaco,0,'db42_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db42_sysfuncoesparam=null) { 
      $this->atualizacampos();
     $sql = " update db_sysfuncoesparam set ";
     $virgula = "";
     if(trim($this->db42_sysfuncoesparam)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_sysfuncoesparam"])){ 
       $sql  .= $virgula." db42_sysfuncoesparam = $this->db42_sysfuncoesparam ";
       $virgula = ",";
       if(trim($this->db42_sysfuncoesparam) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "db42_sysfuncoesparam";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db42_funcao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_funcao"])){ 
       $sql  .= $virgula." db42_funcao = $this->db42_funcao ";
       $virgula = ",";
       if(trim($this->db42_funcao) == null ){ 
         $this->erro_sql = " Campo Código Função nao Informado.";
         $this->erro_campo = "db42_funcao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db42_ordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_ordem"])){ 
       $sql  .= $virgula." db42_ordem = $this->db42_ordem ";
       $virgula = ",";
       if(trim($this->db42_ordem) == null ){ 
         $this->erro_sql = " Campo Ordem na assinatura da funcao nao Informado.";
         $this->erro_campo = "db42_ordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db42_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_nome"])){ 
       $sql  .= $virgula." db42_nome = '$this->db42_nome' ";
       $virgula = ",";
       if(trim($this->db42_nome) == null ){ 
         $this->erro_sql = " Campo Nome do parametro na assinatura nao Informado.";
         $this->erro_campo = "db42_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db42_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_tipo"])){ 
       $sql  .= $virgula." db42_tipo = '$this->db42_tipo' ";
       $virgula = ",";
       if(trim($this->db42_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo de dado nao Informado.";
         $this->erro_campo = "db42_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db42_tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_tamanho"])){ 
        if(trim($this->db42_tamanho)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db42_tamanho"])){ 
           $this->db42_tamanho = "0" ; 
        } 
       $sql  .= $virgula." db42_tamanho = $this->db42_tamanho ";
       $virgula = ",";
     }
     if(trim($this->db42_precisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_precisao"])){ 
        if(trim($this->db42_precisao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["db42_precisao"])){ 
           $this->db42_precisao = "0" ; 
        } 
       $sql  .= $virgula." db42_precisao = $this->db42_precisao ";
       $virgula = ",";
     }
     if(trim($this->db42_valor_default)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_valor_default"])){ 
       $sql  .= $virgula." db42_valor_default = '$this->db42_valor_default' ";
       $virgula = ",";
     }
     if(trim($this->db42_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db42_descricao"])){ 
       $sql  .= $virgula." db42_descricao = '$this->db42_descricao' ";
       $virgula = ",";
       if(trim($this->db42_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db42_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db42_sysfuncoesparam!=null){
       $sql .= " db42_sysfuncoesparam = $this->db42_sysfuncoesparam";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db42_sysfuncoesparam));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9471,'$this->db42_sysfuncoesparam','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_sysfuncoesparam"]))
           $resac = db_query("insert into db_acount values($acount,1626,9471,'".AddSlashes(pg_result($resaco,$conresaco,'db42_sysfuncoesparam'))."','$this->db42_sysfuncoesparam',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_funcao"]))
           $resac = db_query("insert into db_acount values($acount,1626,9472,'".AddSlashes(pg_result($resaco,$conresaco,'db42_funcao'))."','$this->db42_funcao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_ordem"]))
           $resac = db_query("insert into db_acount values($acount,1626,9473,'".AddSlashes(pg_result($resaco,$conresaco,'db42_ordem'))."','$this->db42_ordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_nome"]))
           $resac = db_query("insert into db_acount values($acount,1626,9474,'".AddSlashes(pg_result($resaco,$conresaco,'db42_nome'))."','$this->db42_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1626,9475,'".AddSlashes(pg_result($resaco,$conresaco,'db42_tipo'))."','$this->db42_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_tamanho"]))
           $resac = db_query("insert into db_acount values($acount,1626,9476,'".AddSlashes(pg_result($resaco,$conresaco,'db42_tamanho'))."','$this->db42_tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_precisao"]))
           $resac = db_query("insert into db_acount values($acount,1626,9477,'".AddSlashes(pg_result($resaco,$conresaco,'db42_precisao'))."','$this->db42_precisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_valor_default"]))
           $resac = db_query("insert into db_acount values($acount,1626,9478,'".AddSlashes(pg_result($resaco,$conresaco,'db42_valor_default'))."','$this->db42_valor_default',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db42_descricao"]))
           $resac = db_query("insert into db_acount values($acount,1626,9479,'".AddSlashes(pg_result($resaco,$conresaco,'db42_descricao'))."','$this->db42_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros das funcoes nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db42_sysfuncoesparam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros das funcoes nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db42_sysfuncoesparam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db42_sysfuncoesparam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db42_sysfuncoesparam=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db42_sysfuncoesparam));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9471,'$db42_sysfuncoesparam','E')");
         $resac = db_query("insert into db_acount values($acount,1626,9471,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_sysfuncoesparam'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9472,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_funcao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9473,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_ordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9474,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9475,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9476,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9477,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_precisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9478,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_valor_default'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1626,9479,'','".AddSlashes(pg_result($resaco,$iresaco,'db42_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysfuncoesparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db42_sysfuncoesparam != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db42_sysfuncoesparam = $db42_sysfuncoesparam ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parametros das funcoes nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db42_sysfuncoesparam;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Parametros das funcoes nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db42_sysfuncoesparam;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db42_sysfuncoesparam;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysfuncoesparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db42_sysfuncoesparam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysfuncoesparam ";
     $sql .= "      inner join db_sysfuncoes  on  db_sysfuncoes.codfuncao = db_sysfuncoesparam.db42_funcao";
     $sql2 = "";
     if($dbwhere==""){
       if($db42_sysfuncoesparam!=null ){
         $sql2 .= " where db_sysfuncoesparam.db42_sysfuncoesparam = $db42_sysfuncoesparam "; 
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
   function sql_query_file ( $db42_sysfuncoesparam=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_sysfuncoesparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($db42_sysfuncoesparam!=null ){
         $sql2 .= " where db_sysfuncoesparam.db42_sysfuncoesparam = $db42_sysfuncoesparam "; 
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