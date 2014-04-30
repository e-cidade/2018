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

//MODULO: compras
//CLASSE DA ENTIDADE notificabloqueiofornecedor
class cl_notificabloqueiofornecedor { 
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
   var $pc86_sequencial = 0; 
   var $pc86_numcgm = 0; 
   var $pc86_id_usuario = 0; 
   var $pc86_data_dia = null; 
   var $pc86_data_mes = null; 
   var $pc86_data_ano = null; 
   var $pc86_data = null; 
   var $pc86_hora = null; 
   var $pc86_origem = 0; 
   var $pc86_observacao = null; 
   var $pc86_departamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc86_sequencial = int4 = Código Sequencial 
                 pc86_numcgm = int4 = Código do cgm do fornecedor 
                 pc86_id_usuario = int4 = Código do Usuário 
                 pc86_data = date = Data da movimentação 
                 pc86_hora = char(5) = Hora 
                 pc86_origem = int4 = Origem da notificação 
                 pc86_observacao = text = Observações 
                 pc86_departamento = int4 = Departameto 
                 ";
   //funcao construtor da classe 
   function cl_notificabloqueiofornecedor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("notificabloqueiofornecedor"); 
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
       $this->pc86_sequencial = ($this->pc86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_sequencial"]:$this->pc86_sequencial);
       $this->pc86_numcgm = ($this->pc86_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_numcgm"]:$this->pc86_numcgm);
       $this->pc86_id_usuario = ($this->pc86_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_id_usuario"]:$this->pc86_id_usuario);
       if($this->pc86_data == ""){
         $this->pc86_data_dia = ($this->pc86_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_data_dia"]:$this->pc86_data_dia);
         $this->pc86_data_mes = ($this->pc86_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_data_mes"]:$this->pc86_data_mes);
         $this->pc86_data_ano = ($this->pc86_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_data_ano"]:$this->pc86_data_ano);
         if($this->pc86_data_dia != ""){
            $this->pc86_data = $this->pc86_data_ano."-".$this->pc86_data_mes."-".$this->pc86_data_dia;
         }
       }
       $this->pc86_hora = ($this->pc86_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_hora"]:$this->pc86_hora);
       $this->pc86_origem = ($this->pc86_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_origem"]:$this->pc86_origem);
       $this->pc86_observacao = ($this->pc86_observacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_observacao"]:$this->pc86_observacao);
       $this->pc86_departamento = ($this->pc86_departamento == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_departamento"]:$this->pc86_departamento);
     }else{
       $this->pc86_sequencial = ($this->pc86_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc86_sequencial"]:$this->pc86_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc86_sequencial){ 
      $this->atualizacampos();
     if($this->pc86_numcgm == null ){ 
       $this->erro_sql = " Campo Código do cgm do fornecedor nao Informado.";
       $this->erro_campo = "pc86_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc86_id_usuario == null ){ 
       $this->erro_sql = " Campo Código do Usuário nao Informado.";
       $this->erro_campo = "pc86_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc86_data == null ){ 
       $this->erro_sql = " Campo Data da movimentação nao Informado.";
       $this->erro_campo = "pc86_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc86_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "pc86_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc86_origem == null ){ 
       $this->erro_sql = " Campo Origem da notificação nao Informado.";
       $this->erro_campo = "pc86_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc86_departamento == null ){ 
       $this->erro_sql = " Campo Departameto nao Informado.";
       $this->erro_campo = "pc86_departamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc86_sequencial == "" || $pc86_sequencial == null ){
       $result = db_query("select nextval('notificabloqueiofornecedor_pc86_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: notificabloqueiofornecedor_pc86_sequencial_seq do campo: pc86_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc86_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from notificabloqueiofornecedor_pc86_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc86_sequencial)){
         $this->erro_sql = " Campo pc86_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc86_sequencial = $pc86_sequencial; 
       }
     }
     if(($this->pc86_sequencial == null) || ($this->pc86_sequencial == "") ){ 
       $this->erro_sql = " Campo pc86_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into notificabloqueiofornecedor(
                                       pc86_sequencial 
                                      ,pc86_numcgm 
                                      ,pc86_id_usuario 
                                      ,pc86_data 
                                      ,pc86_hora 
                                      ,pc86_origem 
                                      ,pc86_observacao 
                                      ,pc86_departamento 
                       )
                values (
                                $this->pc86_sequencial 
                               ,$this->pc86_numcgm 
                               ,$this->pc86_id_usuario 
                               ,".($this->pc86_data == "null" || $this->pc86_data == ""?"null":"'".$this->pc86_data."'")." 
                               ,'$this->pc86_hora' 
                               ,$this->pc86_origem 
                               ,'$this->pc86_observacao' 
                               ,$this->pc86_departamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "notificabloqueiofornecedor ($this->pc86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "notificabloqueiofornecedor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "notificabloqueiofornecedor ($this->pc86_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc86_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc86_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17639,'$this->pc86_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3115,17639,'','".AddSlashes(pg_result($resaco,0,'pc86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17640,'','".AddSlashes(pg_result($resaco,0,'pc86_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17641,'','".AddSlashes(pg_result($resaco,0,'pc86_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17642,'','".AddSlashes(pg_result($resaco,0,'pc86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17643,'','".AddSlashes(pg_result($resaco,0,'pc86_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17644,'','".AddSlashes(pg_result($resaco,0,'pc86_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17645,'','".AddSlashes(pg_result($resaco,0,'pc86_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3115,17646,'','".AddSlashes(pg_result($resaco,0,'pc86_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc86_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update notificabloqueiofornecedor set ";
     $virgula = "";
     if(trim($this->pc86_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_sequencial"])){ 
       $sql  .= $virgula." pc86_sequencial = $this->pc86_sequencial ";
       $virgula = ",";
       if(trim($this->pc86_sequencial) == null ){ 
         $this->erro_sql = " Campo Código Sequencial nao Informado.";
         $this->erro_campo = "pc86_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc86_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_numcgm"])){ 
       $sql  .= $virgula." pc86_numcgm = $this->pc86_numcgm ";
       $virgula = ",";
       if(trim($this->pc86_numcgm) == null ){ 
         $this->erro_sql = " Campo Código do cgm do fornecedor nao Informado.";
         $this->erro_campo = "pc86_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc86_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_id_usuario"])){ 
       $sql  .= $virgula." pc86_id_usuario = $this->pc86_id_usuario ";
       $virgula = ",";
       if(trim($this->pc86_id_usuario) == null ){ 
         $this->erro_sql = " Campo Código do Usuário nao Informado.";
         $this->erro_campo = "pc86_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc86_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc86_data_dia"] !="") ){ 
       $sql  .= $virgula." pc86_data = '$this->pc86_data' ";
       $virgula = ",";
       if(trim($this->pc86_data) == null ){ 
         $this->erro_sql = " Campo Data da movimentação nao Informado.";
         $this->erro_campo = "pc86_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_data_dia"])){ 
         $sql  .= $virgula." pc86_data = null ";
         $virgula = ",";
         if(trim($this->pc86_data) == null ){ 
           $this->erro_sql = " Campo Data da movimentação nao Informado.";
           $this->erro_campo = "pc86_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc86_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_hora"])){ 
       $sql  .= $virgula." pc86_hora = '$this->pc86_hora' ";
       $virgula = ",";
       if(trim($this->pc86_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "pc86_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc86_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_origem"])){ 
       $sql  .= $virgula." pc86_origem = $this->pc86_origem ";
       $virgula = ",";
       if(trim($this->pc86_origem) == null ){ 
         $this->erro_sql = " Campo Origem da notificação nao Informado.";
         $this->erro_campo = "pc86_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc86_observacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_observacao"])){ 
       $sql  .= $virgula." pc86_observacao = '$this->pc86_observacao' ";
       $virgula = ",";
     }
     if(trim($this->pc86_departamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc86_departamento"])){ 
       $sql  .= $virgula." pc86_departamento = $this->pc86_departamento ";
       $virgula = ",";
       if(trim($this->pc86_departamento) == null ){ 
         $this->erro_sql = " Campo Departameto nao Informado.";
         $this->erro_campo = "pc86_departamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc86_sequencial!=null){
       $sql .= " pc86_sequencial = $this->pc86_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc86_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17639,'$this->pc86_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_sequencial"]) || $this->pc86_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3115,17639,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_sequencial'))."','$this->pc86_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_numcgm"]) || $this->pc86_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,3115,17640,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_numcgm'))."','$this->pc86_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_id_usuario"]) || $this->pc86_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3115,17641,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_id_usuario'))."','$this->pc86_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_data"]) || $this->pc86_data != "")
           $resac = db_query("insert into db_acount values($acount,3115,17642,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_data'))."','$this->pc86_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_hora"]) || $this->pc86_hora != "")
           $resac = db_query("insert into db_acount values($acount,3115,17643,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_hora'))."','$this->pc86_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_origem"]) || $this->pc86_origem != "")
           $resac = db_query("insert into db_acount values($acount,3115,17644,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_origem'))."','$this->pc86_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_observacao"]) || $this->pc86_observacao != "")
           $resac = db_query("insert into db_acount values($acount,3115,17645,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_observacao'))."','$this->pc86_observacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc86_departamento"]) || $this->pc86_departamento != "")
           $resac = db_query("insert into db_acount values($acount,3115,17646,'".AddSlashes(pg_result($resaco,$conresaco,'pc86_departamento'))."','$this->pc86_departamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificabloqueiofornecedor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificabloqueiofornecedor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc86_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc86_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17639,'$pc86_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3115,17639,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17640,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17641,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17642,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17643,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17644,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17645,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_observacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3115,17646,'','".AddSlashes(pg_result($resaco,$iresaco,'pc86_departamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from notificabloqueiofornecedor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc86_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc86_sequencial = $pc86_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "notificabloqueiofornecedor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc86_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "notificabloqueiofornecedor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc86_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc86_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:notificabloqueiofornecedor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificabloqueiofornecedor ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = notificabloqueiofornecedor.pc86_numcgm";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = notificabloqueiofornecedor.pc86_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = notificabloqueiofornecedor.pc86_departamento";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql2 = "";
     if($dbwhere==""){
       if($pc86_sequencial!=null ){
         $sql2 .= " where notificabloqueiofornecedor.pc86_sequencial = $pc86_sequencial "; 
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
   function sql_query_file ( $pc86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from notificabloqueiofornecedor ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc86_sequencial!=null ){
         $sql2 .= " where notificabloqueiofornecedor.pc86_sequencial = $pc86_sequencial "; 
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
  
   function sql_debitos_notificados( $pc86_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     
     $sql .= " from notificabloqueiofornecedor ";
     $sql .= "      left  join notificacaonotificafornecedor on notificacaonotificafornecedor.pc87_notificabloqueiofornecedor = notificabloqueiofornecedor.pc86_sequencial     ";
     $sql .= "      left  join notificacao                   on notificacao.k50_notifica                                      = notificacaonotificafornecedor.pc87_notificacao ";
     $sql .= "      inner join notitipo                      on notitipo.k51_procede                                          = notificacao.k50_procede                        ";
     $sql .= "      inner join cgm                           on cgm.z01_numcgm                                                = notificabloqueiofornecedor.pc86_numcgm         ";
     $sql .= "      inner join db_usuarios                   on db_usuarios.id_usuario                                        = notificabloqueiofornecedor.pc86_id_usuario     ";
     $sql .= "      inner join db_depart                     on db_depart.coddepto                                            = notificabloqueiofornecedor.pc86_departamento   ";
     $sql .= "      inner join db_config                     on db_config.codigo                                              = db_depart.instit                               ";    
     $sql2 = "";
     if($dbwhere==""){
       if($pc86_sequencial!=null ){
         $sql2 .= " where notificabloqueiofornecedor.pc86_sequencial = $pc86_sequencial "; 
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