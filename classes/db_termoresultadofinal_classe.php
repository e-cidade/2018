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

//MODULO: secretariadeeducacao
//CLASSE DA ENTIDADE termoresultadofinal
class cl_termoresultadofinal { 
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
   var $ed110_sequencial = 0; 
   var $ed110_ensino = 0; 
   var $ed110_descricao = null; 
   var $ed110_abreviatura = null; 
   var $ed110_referencia = null; 
   var $ed110_ano = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed110_sequencial = int4 = Codigo 
                 ed110_ensino = int4 = Ensino 
                 ed110_descricao = varchar(40) = Descricao 
                 ed110_abreviatura = varchar(3) = Abreviatura 
                 ed110_referencia = char(1) = Referência 
                 ed110_ano = varchar(4) = Ano 
                 ";
   //funcao construtor da classe 
   function cl_termoresultadofinal() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termoresultadofinal"); 
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
       $this->ed110_sequencial = ($this->ed110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_sequencial"]:$this->ed110_sequencial);
       $this->ed110_ensino = ($this->ed110_ensino == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_ensino"]:$this->ed110_ensino);
       $this->ed110_descricao = ($this->ed110_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_descricao"]:$this->ed110_descricao);
       $this->ed110_abreviatura = ($this->ed110_abreviatura == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_abreviatura"]:$this->ed110_abreviatura);
       $this->ed110_referencia = ($this->ed110_referencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_referencia"]:$this->ed110_referencia);
       $this->ed110_ano = ($this->ed110_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_ano"]:$this->ed110_ano);
     }else{
       $this->ed110_sequencial = ($this->ed110_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ed110_sequencial"]:$this->ed110_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ed110_sequencial){ 
      $this->atualizacampos();
     if($this->ed110_ensino == null ){ 
       $this->erro_sql = " Campo Ensino nao Informado.";
       $this->erro_campo = "ed110_ensino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_descricao == null ){ 
       $this->erro_sql = " Campo Descricao nao Informado.";
       $this->erro_campo = "ed110_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_abreviatura == null ){ 
       $this->erro_sql = " Campo Abreviatura nao Informado.";
       $this->erro_campo = "ed110_abreviatura";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_referencia == null ){ 
       $this->erro_sql = " Campo Referência nao Informado.";
       $this->erro_campo = "ed110_referencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed110_ano == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "ed110_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed110_sequencial == "" || $ed110_sequencial == null ){
       $result = db_query("select nextval('termoresultadofinal_ed110_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: termoresultadofinal_ed110_sequencial_seq do campo: ed110_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed110_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from termoresultadofinal_ed110_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed110_sequencial)){
         $this->erro_sql = " Campo ed110_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed110_sequencial = $ed110_sequencial; 
       }
     }
     if(($this->ed110_sequencial == null) || ($this->ed110_sequencial == "") ){ 
       $this->erro_sql = " Campo ed110_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termoresultadofinal(
                                       ed110_sequencial 
                                      ,ed110_ensino 
                                      ,ed110_descricao 
                                      ,ed110_abreviatura 
                                      ,ed110_referencia 
                                      ,ed110_ano 
                       )
                values (
                                $this->ed110_sequencial 
                               ,$this->ed110_ensino 
                               ,'$this->ed110_descricao' 
                               ,'$this->ed110_abreviatura' 
                               ,'$this->ed110_referencia' 
                               ,'$this->ed110_ano' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Termo do Resultado Final ($this->ed110_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Termo do Resultado Final já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Termo do Resultado Final ($this->ed110_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed110_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19392,'$this->ed110_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3443,19392,'','".AddSlashes(pg_result($resaco,0,'ed110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3443,19393,'','".AddSlashes(pg_result($resaco,0,'ed110_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3443,19394,'','".AddSlashes(pg_result($resaco,0,'ed110_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3443,19395,'','".AddSlashes(pg_result($resaco,0,'ed110_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3443,19396,'','".AddSlashes(pg_result($resaco,0,'ed110_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3443,19397,'','".AddSlashes(pg_result($resaco,0,'ed110_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed110_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update termoresultadofinal set ";
     $virgula = "";
     if(trim($this->ed110_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_sequencial"])){ 
       $sql  .= $virgula." ed110_sequencial = $this->ed110_sequencial ";
       $virgula = ",";
       if(trim($this->ed110_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo nao Informado.";
         $this->erro_campo = "ed110_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_ensino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_ensino"])){ 
       $sql  .= $virgula." ed110_ensino = $this->ed110_ensino ";
       $virgula = ",";
       if(trim($this->ed110_ensino) == null ){ 
         $this->erro_sql = " Campo Ensino nao Informado.";
         $this->erro_campo = "ed110_ensino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_descricao"])){ 
       $sql  .= $virgula." ed110_descricao = '$this->ed110_descricao' ";
       $virgula = ",";
       if(trim($this->ed110_descricao) == null ){ 
         $this->erro_sql = " Campo Descricao nao Informado.";
         $this->erro_campo = "ed110_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_abreviatura)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_abreviatura"])){ 
       $sql  .= $virgula." ed110_abreviatura = '$this->ed110_abreviatura' ";
       $virgula = ",";
       if(trim($this->ed110_abreviatura) == null ){ 
         $this->erro_sql = " Campo Abreviatura nao Informado.";
         $this->erro_campo = "ed110_abreviatura";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_referencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_referencia"])){ 
       $sql  .= $virgula." ed110_referencia = '$this->ed110_referencia' ";
       $virgula = ",";
       if(trim($this->ed110_referencia) == null ){ 
         $this->erro_sql = " Campo Referência nao Informado.";
         $this->erro_campo = "ed110_referencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed110_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed110_ano"])){ 
       $sql  .= $virgula." ed110_ano = '$this->ed110_ano' ";
       $virgula = ",";
       if(trim($this->ed110_ano) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "ed110_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed110_sequencial!=null){
       $sql .= " ed110_sequencial = $this->ed110_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed110_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19392,'$this->ed110_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_sequencial"]) || $this->ed110_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3443,19392,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_sequencial'))."','$this->ed110_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_ensino"]) || $this->ed110_ensino != "")
           $resac = db_query("insert into db_acount values($acount,3443,19393,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_ensino'))."','$this->ed110_ensino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_descricao"]) || $this->ed110_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3443,19394,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_descricao'))."','$this->ed110_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_abreviatura"]) || $this->ed110_abreviatura != "")
           $resac = db_query("insert into db_acount values($acount,3443,19395,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_abreviatura'))."','$this->ed110_abreviatura',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_referencia"]) || $this->ed110_referencia != "")
           $resac = db_query("insert into db_acount values($acount,3443,19396,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_referencia'))."','$this->ed110_referencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed110_ano"]) || $this->ed110_ano != "")
           $resac = db_query("insert into db_acount values($acount,3443,19397,'".AddSlashes(pg_result($resaco,$conresaco,'ed110_ano'))."','$this->ed110_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Termo do Resultado Final nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Termo do Resultado Final nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed110_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed110_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed110_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19392,'$ed110_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3443,19392,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3443,19393,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_ensino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3443,19394,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3443,19395,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_abreviatura'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3443,19396,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_referencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3443,19397,'','".AddSlashes(pg_result($resaco,$iresaco,'ed110_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termoresultadofinal
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed110_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed110_sequencial = $ed110_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Termo do Resultado Final nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed110_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Termo do Resultado Final nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed110_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed110_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:termoresultadofinal";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termoresultadofinal ";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = termoresultadofinal.ed110_ensino";
     $sql .= "      inner join tipoensino  on  tipoensino.ed36_i_codigo = ensino.ed10_i_tipoensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed110_sequencial!=null ){
         $sql2 .= " where termoresultadofinal.ed110_sequencial = $ed110_sequencial "; 
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
   function sql_query_file ( $ed110_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termoresultadofinal ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed110_sequencial!=null ){
         $sql2 .= " where termoresultadofinal.ed110_sequencial = $ed110_sequencial "; 
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