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

//MODULO: Empenho
//CLASSE DA ENTIDADE retencaotiporec
class cl_retencaotiporec { 
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
   var $e21_sequencial = 0; 
   var $e21_retencaotipocalc = 0; 
   var $e21_receita = 0; 
   var $e21_descricao = null; 
   var $e21_aliquota = 0; 
   var $e21_instit = 0; 
   var $e21_retencaotiporecgrupo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 e21_sequencial = int4 = Código Sequencial 
                 e21_retencaotipocalc = int4 = Tipo de Cálculo 
                 e21_receita = int4 = Receita 
                 e21_descricao = varchar(100) = Descrição 
                 e21_aliquota = float8 = Aliquota 
                 e21_instit = int4 = Código da Instituição 
                 e21_retencaotiporecgrupo = int4 = Grupo 
                 ";
   //funcao construtor da classe 
   function cl_retencaotiporec() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("retencaotiporec"); 
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
       $this->e21_sequencial = ($this->e21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_sequencial"]:$this->e21_sequencial);
       $this->e21_retencaotipocalc = ($this->e21_retencaotipocalc == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_retencaotipocalc"]:$this->e21_retencaotipocalc);
       $this->e21_receita = ($this->e21_receita == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_receita"]:$this->e21_receita);
       $this->e21_descricao = ($this->e21_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_descricao"]:$this->e21_descricao);
       $this->e21_aliquota = ($this->e21_aliquota == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_aliquota"]:$this->e21_aliquota);
       $this->e21_instit = ($this->e21_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_instit"]:$this->e21_instit);
       $this->e21_retencaotiporecgrupo = ($this->e21_retencaotiporecgrupo == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_retencaotiporecgrupo"]:$this->e21_retencaotiporecgrupo);
     }else{
       $this->e21_sequencial = ($this->e21_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["e21_sequencial"]:$this->e21_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($e21_sequencial){ 
      $this->atualizacampos();
     if($this->e21_retencaotipocalc == null ){ 
       $this->erro_sql = " Campo Tipo de Cálculo nao Informado.";
       $this->erro_campo = "e21_retencaotipocalc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e21_receita == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "e21_receita";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e21_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "e21_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e21_aliquota == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "e21_aliquota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e21_instit == null ){ 
       $this->erro_sql = " Campo Código da Instituição nao Informado.";
       $this->erro_campo = "e21_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->e21_retencaotiporecgrupo == null ){ 
       $this->erro_sql = " Campo Grupo nao Informado.";
       $this->erro_campo = "e21_retencaotiporecgrupo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($e21_sequencial == "" || $e21_sequencial == null ){
       $result = db_query("select nextval('retencaotiporec_e21_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: retencaotiporec_e21_sequencial_seq do campo: e21_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->e21_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from retencaotiporec_e21_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $e21_sequencial)){
         $this->erro_sql = " Campo e21_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->e21_sequencial = $e21_sequencial; 
       }
     }
     if(($this->e21_sequencial == null) || ($this->e21_sequencial == "") ){ 
       $this->erro_sql = " Campo e21_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into retencaotiporec(
                                       e21_sequencial 
                                      ,e21_retencaotipocalc 
                                      ,e21_receita 
                                      ,e21_descricao 
                                      ,e21_aliquota 
                                      ,e21_instit 
                                      ,e21_retencaotiporecgrupo 
                       )
                values (
                                $this->e21_sequencial 
                               ,$this->e21_retencaotipocalc 
                               ,$this->e21_receita 
                               ,'$this->e21_descricao' 
                               ,$this->e21_aliquota 
                               ,$this->e21_instit 
                               ,$this->e21_retencaotiporecgrupo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Retenções ($this->e21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Retenções já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Retenções ($this->e21_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e21_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->e21_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,12159,'$this->e21_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2112,12159,'','".AddSlashes(pg_result($resaco,0,'e21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,12160,'','".AddSlashes(pg_result($resaco,0,'e21_retencaotipocalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,12161,'','".AddSlashes(pg_result($resaco,0,'e21_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,12162,'','".AddSlashes(pg_result($resaco,0,'e21_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,12163,'','".AddSlashes(pg_result($resaco,0,'e21_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,12550,'','".AddSlashes(pg_result($resaco,0,'e21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2112,14267,'','".AddSlashes(pg_result($resaco,0,'e21_retencaotiporecgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($e21_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update retencaotiporec set ";
     $virgula = "";
     if(trim($this->e21_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_sequencial"])){ 
       $sql  .= $virgula." e21_sequencial = $this->e21_sequencial ";
       $virgula = ",";
       if(trim($this->e21_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "e21_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_retencaotipocalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_retencaotipocalc"])){ 
       $sql  .= $virgula." e21_retencaotipocalc = $this->e21_retencaotipocalc ";
       $virgula = ",";
       if(trim($this->e21_retencaotipocalc) == null ){ 
         $this->erro_sql = " Campo Tipo de Cálculo nao Informado.";
         $this->erro_campo = "e21_retencaotipocalc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_receita)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_receita"])){ 
       $sql  .= $virgula." e21_receita = $this->e21_receita ";
       $virgula = ",";
       if(trim($this->e21_receita) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "e21_receita";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_descricao"])){ 
       $sql  .= $virgula." e21_descricao = '$this->e21_descricao' ";
       $virgula = ",";
       if(trim($this->e21_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "e21_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_aliquota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_aliquota"])){ 
       $sql  .= $virgula." e21_aliquota = $this->e21_aliquota ";
       $virgula = ",";
       if(trim($this->e21_aliquota) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "e21_aliquota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_instit"])){ 
       $sql  .= $virgula." e21_instit = $this->e21_instit ";
       $virgula = ",";
       if(trim($this->e21_instit) == null ){ 
         $this->erro_sql = " Campo Código da Instituição nao Informado.";
         $this->erro_campo = "e21_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->e21_retencaotiporecgrupo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["e21_retencaotiporecgrupo"])){ 
       $sql  .= $virgula." e21_retencaotiporecgrupo = $this->e21_retencaotiporecgrupo ";
       $virgula = ",";
       if(trim($this->e21_retencaotiporecgrupo) == null ){ 
         $this->erro_sql = " Campo Grupo nao Informado.";
         $this->erro_campo = "e21_retencaotiporecgrupo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($e21_sequencial!=null){
       $sql .= " e21_sequencial = $this->e21_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->e21_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12159,'$this->e21_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_sequencial"]) || $this->e21_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2112,12159,'".AddSlashes(pg_result($resaco,$conresaco,'e21_sequencial'))."','$this->e21_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_retencaotipocalc"]) || $this->e21_retencaotipocalc != "")
           $resac = db_query("insert into db_acount values($acount,2112,12160,'".AddSlashes(pg_result($resaco,$conresaco,'e21_retencaotipocalc'))."','$this->e21_retencaotipocalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_receita"]) || $this->e21_receita != "")
           $resac = db_query("insert into db_acount values($acount,2112,12161,'".AddSlashes(pg_result($resaco,$conresaco,'e21_receita'))."','$this->e21_receita',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_descricao"]) || $this->e21_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2112,12162,'".AddSlashes(pg_result($resaco,$conresaco,'e21_descricao'))."','$this->e21_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_aliquota"]) || $this->e21_aliquota != "")
           $resac = db_query("insert into db_acount values($acount,2112,12163,'".AddSlashes(pg_result($resaco,$conresaco,'e21_aliquota'))."','$this->e21_aliquota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_instit"]) || $this->e21_instit != "")
           $resac = db_query("insert into db_acount values($acount,2112,12550,'".AddSlashes(pg_result($resaco,$conresaco,'e21_instit'))."','$this->e21_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["e21_retencaotiporecgrupo"]) || $this->e21_retencaotiporecgrupo != "")
           $resac = db_query("insert into db_acount values($acount,2112,14267,'".AddSlashes(pg_result($resaco,$conresaco,'e21_retencaotiporecgrupo'))."','$this->e21_retencaotiporecgrupo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Retenções nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->e21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Retenções nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->e21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->e21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($e21_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($e21_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,12159,'$e21_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2112,12159,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,12160,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_retencaotipocalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,12161,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_receita'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,12162,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,12163,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_aliquota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,12550,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2112,14267,'','".AddSlashes(pg_result($resaco,$iresaco,'e21_retencaotiporecgrupo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from retencaotiporec
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($e21_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " e21_sequencial = $e21_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Retenções nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$e21_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Retenções nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$e21_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$e21_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:retencaotiporec";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $e21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaotiporec ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join db_config  on  db_config.codigo = retencaotiporec.e21_instit";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join retencaotiporecgrupo  on  retencaotiporecgrupo.e01_sequencial = retencaotiporec.e21_retencaotiporecgrupo";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($e21_sequencial!=null ){
         $sql2 .= " where retencaotiporec.e21_sequencial = $e21_sequencial "; 
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
   function sql_query_file ( $e21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from retencaotiporec ";
     $sql2 = "";
     if($dbwhere==""){
       if($e21_sequencial!=null ){
         $sql2 .= " where retencaotiporec.e21_sequencial = $e21_sequencial "; 
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
   function sql_query_irrf ( $e21_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 

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
     $sql .= " from retencaotiporec ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = retencaotiporec.e21_receita";
     $sql .= "      inner join retencaotipocalc  on  retencaotipocalc.e32_sequencial = retencaotiporec.e21_retencaotipocalc";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql .= "      left  join retencaotiporeccgm       on  e48_retencaotiporec   = e21_sequencial";
     $sql .= "      left  join cgm                      on  z01_numcgm            = e48_cgm";
     $sql .= "      left  join retencaonaturezatiporec  on  e31_retencaotiporec   = e21_sequencial";
     $sql .= "      left  join retencaonatureza         on  e31_retencaonatureza  = e30_sequencial";
     $sql2 = "";
     if($dbwhere==""){
       if($e21_sequencial!=null ){
         $sql2 .= " where retencaotiporec.e21_sequencial = $e21_sequencial "; 
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