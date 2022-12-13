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

//MODULO: tfd
//CLASSE DA ENTIDADE fechamentoarquivotfd
class cl_fechamentoarquivotfd { 
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
   var $tf36_sequencial = 0; 
   var $tf36_id_usuario = 0; 
   var $tf36_tfd_fechamento = 0; 
   var $tf36_data_dia = null; 
   var $tf36_data_mes = null; 
   var $tf36_data_ano = null; 
   var $tf36_data = null; 
   var $tf36_hora = null; 
   var $tf36_nomearquivo = null; 
   var $tf36_oidarquivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 tf36_sequencial = int4 = Código arquivo 
                 tf36_id_usuario = int4 = Cod. Usuário 
                 tf36_tfd_fechamento = int4 = Código 
                 tf36_data = date = Data da Geraçao 
                 tf36_hora = char(5) = Hora da Geração 
                 tf36_nomearquivo = varchar(100) = Nome do Arquivo 
                 tf36_oidarquivo = oid = BInário do Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_fechamentoarquivotfd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("fechamentoarquivotfd"); 
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
       $this->tf36_sequencial = ($this->tf36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_sequencial"]:$this->tf36_sequencial);
       $this->tf36_id_usuario = ($this->tf36_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_id_usuario"]:$this->tf36_id_usuario);
       $this->tf36_tfd_fechamento = ($this->tf36_tfd_fechamento == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_tfd_fechamento"]:$this->tf36_tfd_fechamento);
       if($this->tf36_data == ""){
         $this->tf36_data_dia = ($this->tf36_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_data_dia"]:$this->tf36_data_dia);
         $this->tf36_data_mes = ($this->tf36_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_data_mes"]:$this->tf36_data_mes);
         $this->tf36_data_ano = ($this->tf36_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_data_ano"]:$this->tf36_data_ano);
         if($this->tf36_data_dia != ""){
            $this->tf36_data = $this->tf36_data_ano."-".$this->tf36_data_mes."-".$this->tf36_data_dia;
         }
       }
       $this->tf36_hora = ($this->tf36_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_hora"]:$this->tf36_hora);
       $this->tf36_nomearquivo = ($this->tf36_nomearquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_nomearquivo"]:$this->tf36_nomearquivo);
       $this->tf36_oidarquivo = ($this->tf36_oidarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_oidarquivo"]:$this->tf36_oidarquivo);
     }else{
       $this->tf36_sequencial = ($this->tf36_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["tf36_sequencial"]:$this->tf36_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($tf36_sequencial){ 
      $this->atualizacampos();
     if($this->tf36_id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "tf36_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf36_tfd_fechamento == null ){ 
       $this->erro_sql = " Campo Código não informado.";
       $this->erro_campo = "tf36_tfd_fechamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf36_data == null ){ 
       $this->erro_sql = " Campo Data da Geraçao não informado.";
       $this->erro_campo = "tf36_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf36_hora == null ){ 
       $this->erro_sql = " Campo Hora da Geração não informado.";
       $this->erro_campo = "tf36_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf36_nomearquivo == null ){ 
       $this->erro_sql = " Campo Nome do Arquivo não informado.";
       $this->erro_campo = "tf36_nomearquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->tf36_oidarquivo == null ){ 
       $this->erro_sql = " Campo BInário do Arquivo não informado.";
       $this->erro_campo = "tf36_oidarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($tf36_sequencial == "" || $tf36_sequencial == null ){
       $result = db_query("select nextval('fechamentoarquivotfd_tf36_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: fechamentoarquivotfd_tf36_sequencial_seq do campo: tf36_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->tf36_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from fechamentoarquivotfd_tf36_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $tf36_sequencial)){
         $this->erro_sql = " Campo tf36_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->tf36_sequencial = $tf36_sequencial; 
       }
     }
     if(($this->tf36_sequencial == null) || ($this->tf36_sequencial == "") ){ 
       $this->erro_sql = " Campo tf36_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into fechamentoarquivotfd(
                                       tf36_sequencial 
                                      ,tf36_id_usuario 
                                      ,tf36_tfd_fechamento 
                                      ,tf36_data 
                                      ,tf36_hora 
                                      ,tf36_nomearquivo 
                                      ,tf36_oidarquivo 
                       )
                values (
                                $this->tf36_sequencial 
                               ,$this->tf36_id_usuario 
                               ,$this->tf36_tfd_fechamento 
                               ,".($this->tf36_data == "null" || $this->tf36_data == ""?"null":"'".$this->tf36_data."'")." 
                               ,'$this->tf36_hora' 
                               ,'$this->tf36_nomearquivo' 
                               ,$this->tf36_oidarquivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo de Fechamento do TFD ($this->tf36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo de Fechamento do TFD já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo de Fechamento do TFD ($this->tf36_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf36_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf36_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20313,'$this->tf36_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3652,20313,'','".AddSlashes(pg_result($resaco,0,'tf36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20314,'','".AddSlashes(pg_result($resaco,0,'tf36_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20315,'','".AddSlashes(pg_result($resaco,0,'tf36_tfd_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20316,'','".AddSlashes(pg_result($resaco,0,'tf36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20317,'','".AddSlashes(pg_result($resaco,0,'tf36_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20318,'','".AddSlashes(pg_result($resaco,0,'tf36_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3652,20319,'','".AddSlashes(pg_result($resaco,0,'tf36_oidarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($tf36_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update fechamentoarquivotfd set ";
     $virgula = "";
     if(trim($this->tf36_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_sequencial"])){ 
       $sql  .= $virgula." tf36_sequencial = $this->tf36_sequencial ";
       $virgula = ",";
       if(trim($this->tf36_sequencial) == null ){ 
         $this->erro_sql = " Campo Código arquivo não informado.";
         $this->erro_campo = "tf36_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf36_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_id_usuario"])){ 
       $sql  .= $virgula." tf36_id_usuario = $this->tf36_id_usuario ";
       $virgula = ",";
       if(trim($this->tf36_id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "tf36_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf36_tfd_fechamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_tfd_fechamento"])){ 
       $sql  .= $virgula." tf36_tfd_fechamento = $this->tf36_tfd_fechamento ";
       $virgula = ",";
       if(trim($this->tf36_tfd_fechamento) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "tf36_tfd_fechamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf36_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["tf36_data_dia"] !="") ){ 
       $sql  .= $virgula." tf36_data = '$this->tf36_data' ";
       $virgula = ",";
       if(trim($this->tf36_data) == null ){ 
         $this->erro_sql = " Campo Data da Geraçao não informado.";
         $this->erro_campo = "tf36_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_data_dia"])){ 
         $sql  .= $virgula." tf36_data = null ";
         $virgula = ",";
         if(trim($this->tf36_data) == null ){ 
           $this->erro_sql = " Campo Data da Geraçao não informado.";
           $this->erro_campo = "tf36_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->tf36_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_hora"])){ 
       $sql  .= $virgula." tf36_hora = '$this->tf36_hora' ";
       $virgula = ",";
       if(trim($this->tf36_hora) == null ){ 
         $this->erro_sql = " Campo Hora da Geração não informado.";
         $this->erro_campo = "tf36_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf36_nomearquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_nomearquivo"])){ 
       $sql  .= $virgula." tf36_nomearquivo = '$this->tf36_nomearquivo' ";
       $virgula = ",";
       if(trim($this->tf36_nomearquivo) == null ){ 
         $this->erro_sql = " Campo Nome do Arquivo não informado.";
         $this->erro_campo = "tf36_nomearquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->tf36_oidarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["tf36_oidarquivo"])){ 
       $sql  .= $virgula." tf36_oidarquivo = $this->tf36_oidarquivo ";
       $virgula = ",";
       if(trim($this->tf36_oidarquivo) == null ){ 
         $this->erro_sql = " Campo BInário do Arquivo não informado.";
         $this->erro_campo = "tf36_oidarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($tf36_sequencial!=null){
       $sql .= " tf36_sequencial = $this->tf36_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->tf36_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20313,'$this->tf36_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_sequencial"]) || $this->tf36_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3652,20313,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_sequencial'))."','$this->tf36_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_id_usuario"]) || $this->tf36_id_usuario != "")
             $resac = db_query("insert into db_acount values($acount,3652,20314,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_id_usuario'))."','$this->tf36_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_tfd_fechamento"]) || $this->tf36_tfd_fechamento != "")
             $resac = db_query("insert into db_acount values($acount,3652,20315,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_tfd_fechamento'))."','$this->tf36_tfd_fechamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_data"]) || $this->tf36_data != "")
             $resac = db_query("insert into db_acount values($acount,3652,20316,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_data'))."','$this->tf36_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_hora"]) || $this->tf36_hora != "")
             $resac = db_query("insert into db_acount values($acount,3652,20317,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_hora'))."','$this->tf36_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_nomearquivo"]) || $this->tf36_nomearquivo != "")
             $resac = db_query("insert into db_acount values($acount,3652,20318,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_nomearquivo'))."','$this->tf36_nomearquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["tf36_oidarquivo"]) || $this->tf36_oidarquivo != "")
             $resac = db_query("insert into db_acount values($acount,3652,20319,'".AddSlashes(pg_result($resaco,$conresaco,'tf36_oidarquivo'))."','$this->tf36_oidarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Fechamento do TFD nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Fechamento do TFD nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->tf36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->tf36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($tf36_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($tf36_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20313,'$tf36_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3652,20313,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20314,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20315,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_tfd_fechamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20316,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20317,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20318,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_nomearquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3652,20319,'','".AddSlashes(pg_result($resaco,$iresaco,'tf36_oidarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from fechamentoarquivotfd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($tf36_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " tf36_sequencial = $tf36_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo de Fechamento do TFD nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$tf36_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo de Fechamento do TFD nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$tf36_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$tf36_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:fechamentoarquivotfd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $tf36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fechamentoarquivotfd ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = fechamentoarquivotfd.tf36_id_usuario";
     $sql .= "      inner join tfd_fechamento  on  tfd_fechamento.tf32_i_codigo = fechamentoarquivotfd.tf36_tfd_fechamento";
     $sql .= "      inner join sau_financiamento  on  sau_financiamento.sd65_i_codigo = tfd_fechamento.tf32_i_financiamento";
     $sql2 = "";
     if($dbwhere==""){
       if($tf36_sequencial!=null ){
         $sql2 .= " where fechamentoarquivotfd.tf36_sequencial = $tf36_sequencial "; 
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
   function sql_query_file ( $tf36_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from fechamentoarquivotfd ";
     $sql2 = "";
     if($dbwhere==""){
       if($tf36_sequencial!=null ){
         $sql2 .= " where fechamentoarquivotfd.tf36_sequencial = $tf36_sequencial "; 
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