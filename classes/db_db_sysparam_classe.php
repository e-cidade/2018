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
//CLASSE DA ENTIDADE db_sysparam
class cl_db_sysparam { 
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
   var $db23_codigo = 0; 
   var $db23_modulo = 0; 
   var $db23_instit = 0; 
   var $db23_tipo = 0; 
   var $db23_anousu = 0; 
   var $db23_nome = null; 
   var $db23_valor = null; 
   var $db23_funcpesquisa = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db23_codigo = int4 = Parâmetro 
                 db23_modulo = int4 = Módulo 
                 db23_instit = int4 = Instituição 
                 db23_tipo = int4 = Tipo 
                 db23_anousu = int4 = Exercício 
                 db23_nome = varchar(40) = Nome 
                 db23_valor = text = Valor 
                 db23_funcpesquisa = varchar(100) = Função Pesquisa 
                 ";
   //funcao construtor da classe 
   function cl_db_sysparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_sysparam"); 
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
       $this->db23_codigo = ($this->db23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_codigo"]:$this->db23_codigo);
       $this->db23_modulo = ($this->db23_modulo == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_modulo"]:$this->db23_modulo);
       $this->db23_instit = ($this->db23_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_instit"]:$this->db23_instit);
       $this->db23_tipo = ($this->db23_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_tipo"]:$this->db23_tipo);
       $this->db23_anousu = ($this->db23_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_anousu"]:$this->db23_anousu);
       $this->db23_nome = ($this->db23_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_nome"]:$this->db23_nome);
       $this->db23_valor = ($this->db23_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_valor"]:$this->db23_valor);
       $this->db23_funcpesquisa = ($this->db23_funcpesquisa == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_funcpesquisa"]:$this->db23_funcpesquisa);
     }else{
       $this->db23_codigo = ($this->db23_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["db23_codigo"]:$this->db23_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($db23_codigo){ 
      $this->atualizacampos();
     if($this->db23_modulo == null ){ 
       $this->erro_sql = " Campo Módulo nao Informado.";
       $this->erro_campo = "db23_modulo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db23_instit == null ){ 
       $this->erro_sql = " Campo Instituição nao Informado.";
       $this->erro_campo = "db23_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db23_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "db23_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db23_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "db23_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db23_nome == null ){ 
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "db23_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db23_funcpesquisa == null ){ 
       $this->erro_sql = " Campo Função Pesquisa nao Informado.";
       $this->erro_campo = "db23_funcpesquisa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db23_codigo == "" || $db23_codigo == null ){
       $result = db_query("select nextval('db_sysparam_db23_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_sysparam_db23_codigo_seq do campo: db23_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db23_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_sysparam_db23_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $db23_codigo)){
         $this->erro_sql = " Campo db23_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db23_codigo = $db23_codigo; 
       }
     }
     if(($this->db23_codigo == null) || ($this->db23_codigo == "") ){ 
       $this->erro_sql = " Campo db23_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_sysparam(
                                       db23_codigo 
                                      ,db23_modulo 
                                      ,db23_instit 
                                      ,db23_tipo 
                                      ,db23_anousu 
                                      ,db23_nome 
                                      ,db23_valor 
                                      ,db23_funcpesquisa 
                       )
                values (
                                $this->db23_codigo 
                               ,$this->db23_modulo 
                               ,$this->db23_instit 
                               ,$this->db23_tipo 
                               ,$this->db23_anousu 
                               ,'$this->db23_nome' 
                               ,'$this->db23_valor' 
                               ,'$this->db23_funcpesquisa' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Parâmetros ($this->db23_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Parâmetros já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Parâmetros ($this->db23_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db23_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db23_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9533,'$this->db23_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1636,9533,'','".AddSlashes(pg_result($resaco,0,'db23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9534,'','".AddSlashes(pg_result($resaco,0,'db23_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9535,'','".AddSlashes(pg_result($resaco,0,'db23_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9536,'','".AddSlashes(pg_result($resaco,0,'db23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9537,'','".AddSlashes(pg_result($resaco,0,'db23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9538,'','".AddSlashes(pg_result($resaco,0,'db23_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9539,'','".AddSlashes(pg_result($resaco,0,'db23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1636,9540,'','".AddSlashes(pg_result($resaco,0,'db23_funcpesquisa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db23_codigo=null) { 
      $this->atualizacampos();
     $sql = " update db_sysparam set ";
     $virgula = "";
     if(trim($this->db23_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_codigo"])){ 
       $sql  .= $virgula." db23_codigo = $this->db23_codigo ";
       $virgula = ",";
       if(trim($this->db23_codigo) == null ){ 
         $this->erro_sql = " Campo Parâmetro nao Informado.";
         $this->erro_campo = "db23_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_modulo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_modulo"])){ 
       $sql  .= $virgula." db23_modulo = $this->db23_modulo ";
       $virgula = ",";
       if(trim($this->db23_modulo) == null ){ 
         $this->erro_sql = " Campo Módulo nao Informado.";
         $this->erro_campo = "db23_modulo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_instit"])){ 
       $sql  .= $virgula." db23_instit = $this->db23_instit ";
       $virgula = ",";
       if(trim($this->db23_instit) == null ){ 
         $this->erro_sql = " Campo Instituição nao Informado.";
         $this->erro_campo = "db23_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_tipo"])){ 
       $sql  .= $virgula." db23_tipo = $this->db23_tipo ";
       $virgula = ",";
       if(trim($this->db23_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "db23_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_anousu"])){ 
       $sql  .= $virgula." db23_anousu = $this->db23_anousu ";
       $virgula = ",";
       if(trim($this->db23_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "db23_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_nome"])){ 
       $sql  .= $virgula." db23_nome = '$this->db23_nome' ";
       $virgula = ",";
       if(trim($this->db23_nome) == null ){ 
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "db23_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db23_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_valor"])){ 
       $sql  .= $virgula." db23_valor = '$this->db23_valor' ";
       $virgula = ",";
     }
     if(trim($this->db23_funcpesquisa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db23_funcpesquisa"])){ 
       $sql  .= $virgula." db23_funcpesquisa = '$this->db23_funcpesquisa' ";
       $virgula = ",";
       if(trim($this->db23_funcpesquisa) == null ){ 
         $this->erro_sql = " Campo Função Pesquisa nao Informado.";
         $this->erro_campo = "db23_funcpesquisa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db23_codigo!=null){
       $sql .= " db23_codigo = $this->db23_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db23_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9533,'$this->db23_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1636,9533,'".AddSlashes(pg_result($resaco,$conresaco,'db23_codigo'))."','$this->db23_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_modulo"]))
           $resac = db_query("insert into db_acount values($acount,1636,9534,'".AddSlashes(pg_result($resaco,$conresaco,'db23_modulo'))."','$this->db23_modulo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_instit"]))
           $resac = db_query("insert into db_acount values($acount,1636,9535,'".AddSlashes(pg_result($resaco,$conresaco,'db23_instit'))."','$this->db23_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1636,9536,'".AddSlashes(pg_result($resaco,$conresaco,'db23_tipo'))."','$this->db23_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1636,9537,'".AddSlashes(pg_result($resaco,$conresaco,'db23_anousu'))."','$this->db23_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_nome"]))
           $resac = db_query("insert into db_acount values($acount,1636,9538,'".AddSlashes(pg_result($resaco,$conresaco,'db23_nome'))."','$this->db23_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_valor"]))
           $resac = db_query("insert into db_acount values($acount,1636,9539,'".AddSlashes(pg_result($resaco,$conresaco,'db23_valor'))."','$this->db23_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db23_funcpesquisa"]))
           $resac = db_query("insert into db_acount values($acount,1636,9540,'".AddSlashes(pg_result($resaco,$conresaco,'db23_funcpesquisa'))."','$this->db23_funcpesquisa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Parâmetros nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Parâmetros nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db23_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db23_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9533,'$db23_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1636,9533,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9534,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_modulo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9535,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9536,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9537,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9538,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9539,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1636,9540,'','".AddSlashes(pg_result($resaco,$iresaco,'db23_funcpesquisa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_sysparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db23_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db23_codigo = $db23_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Parâmetros nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db23_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Parâmetros nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db23_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db23_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_sysparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
}
?>