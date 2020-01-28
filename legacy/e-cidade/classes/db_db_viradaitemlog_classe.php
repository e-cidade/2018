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
//CLASSE DA ENTIDADE db_viradaitemlog
class cl_db_viradaitemlog { 
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
   var $c35_sequencial = 0; 
   var $c35_codarq = 0; 
   var $c35_db_viradaitem = 0; 
   var $c35_data_dia = null; 
   var $c35_data_mes = null; 
   var $c35_data_ano = null; 
   var $c35_data = null; 
   var $c35_hora = null; 
   var $c35_log = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c35_sequencial = int4 = Código 
                 c35_codarq = int4 = Código da tabela 
                 c35_db_viradaitem = int4 = Código da virada do item 
                 c35_data = date = Data 
                 c35_hora = char(5) = Hora 
                 c35_log = text = Log 
                 ";
   //funcao construtor da classe 
   function cl_db_viradaitemlog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_viradaitemlog"); 
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
       $this->c35_sequencial = ($this->c35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_sequencial"]:$this->c35_sequencial);
       $this->c35_codarq = ($this->c35_codarq == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_codarq"]:$this->c35_codarq);
       $this->c35_db_viradaitem = ($this->c35_db_viradaitem == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_db_viradaitem"]:$this->c35_db_viradaitem);
       if($this->c35_data == ""){
         $this->c35_data_dia = ($this->c35_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_data_dia"]:$this->c35_data_dia);
         $this->c35_data_mes = ($this->c35_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_data_mes"]:$this->c35_data_mes);
         $this->c35_data_ano = ($this->c35_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_data_ano"]:$this->c35_data_ano);
         if($this->c35_data_dia != ""){
            $this->c35_data = $this->c35_data_ano."-".$this->c35_data_mes."-".$this->c35_data_dia;
         }
       }
       $this->c35_hora = ($this->c35_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_hora"]:$this->c35_hora);
       $this->c35_log = ($this->c35_log == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_log"]:$this->c35_log);
     }else{
       $this->c35_sequencial = ($this->c35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c35_sequencial"]:$this->c35_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c35_sequencial){ 
      $this->atualizacampos();
     if($this->c35_codarq == null ){ 
       $this->erro_sql = " Campo Código da tabela nao Informado.";
       $this->erro_campo = "c35_codarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c35_db_viradaitem == null ){ 
       $this->erro_sql = " Campo Código da virada do item nao Informado.";
       $this->erro_campo = "c35_db_viradaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c35_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "c35_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c35_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "c35_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c35_log == null ){ 
       $this->erro_sql = " Campo Log nao Informado.";
       $this->erro_campo = "c35_log";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c35_sequencial == "" || $c35_sequencial == null ){
       $result = db_query("select nextval('db_viradaitemlog_c35_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_viradaitemlog_c35_sequencial_seq do campo: c35_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c35_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_viradaitemlog_c35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c35_sequencial)){
         $this->erro_sql = " Campo c35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c35_sequencial = $c35_sequencial; 
       }
     }
     if(($this->c35_sequencial == null) || ($this->c35_sequencial == "") ){ 
       $this->erro_sql = " Campo c35_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_viradaitemlog(
                                       c35_sequencial 
                                      ,c35_codarq 
                                      ,c35_db_viradaitem 
                                      ,c35_data 
                                      ,c35_hora 
                                      ,c35_log 
                       )
                values (
                                $this->c35_sequencial 
                               ,$this->c35_codarq 
                               ,$this->c35_db_viradaitem 
                               ,".($this->c35_data == "null" || $this->c35_data == ""?"null":"'".$this->c35_data."'")." 
                               ,'$this->c35_hora' 
                               ,'$this->c35_log' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "log da virada ($this->c35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "log da virada já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "log da virada ($this->c35_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c35_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10825,'$this->c35_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1864,10825,'','".AddSlashes(pg_result($resaco,0,'c35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1864,10826,'','".AddSlashes(pg_result($resaco,0,'c35_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1864,10827,'','".AddSlashes(pg_result($resaco,0,'c35_db_viradaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1864,10828,'','".AddSlashes(pg_result($resaco,0,'c35_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1864,10829,'','".AddSlashes(pg_result($resaco,0,'c35_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1864,10830,'','".AddSlashes(pg_result($resaco,0,'c35_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c35_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_viradaitemlog set ";
     $virgula = "";
     if(trim($this->c35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_sequencial"])){ 
       $sql  .= $virgula." c35_sequencial = $this->c35_sequencial ";
       $virgula = ",";
       if(trim($this->c35_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "c35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c35_codarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_codarq"])){ 
       $sql  .= $virgula." c35_codarq = $this->c35_codarq ";
       $virgula = ",";
       if(trim($this->c35_codarq) == null ){ 
         $this->erro_sql = " Campo Código da tabela nao Informado.";
         $this->erro_campo = "c35_codarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c35_db_viradaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_db_viradaitem"])){ 
       $sql  .= $virgula." c35_db_viradaitem = $this->c35_db_viradaitem ";
       $virgula = ",";
       if(trim($this->c35_db_viradaitem) == null ){ 
         $this->erro_sql = " Campo Código da virada do item nao Informado.";
         $this->erro_campo = "c35_db_viradaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c35_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c35_data_dia"] !="") ){ 
       $sql  .= $virgula." c35_data = '$this->c35_data' ";
       $virgula = ",";
       if(trim($this->c35_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "c35_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c35_data_dia"])){ 
         $sql  .= $virgula." c35_data = null ";
         $virgula = ",";
         if(trim($this->c35_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "c35_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c35_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_hora"])){ 
       $sql  .= $virgula." c35_hora = '$this->c35_hora' ";
       $virgula = ",";
       if(trim($this->c35_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "c35_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c35_log)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c35_log"])){ 
       $sql  .= $virgula." c35_log = '$this->c35_log' ";
       $virgula = ",";
       if(trim($this->c35_log) == null ){ 
         $this->erro_sql = " Campo Log nao Informado.";
         $this->erro_campo = "c35_log";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c35_sequencial!=null){
       $sql .= " c35_sequencial = $this->c35_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c35_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10825,'$this->c35_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1864,10825,'".AddSlashes(pg_result($resaco,$conresaco,'c35_sequencial'))."','$this->c35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_codarq"]))
           $resac = db_query("insert into db_acount values($acount,1864,10826,'".AddSlashes(pg_result($resaco,$conresaco,'c35_codarq'))."','$this->c35_codarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_db_viradaitem"]))
           $resac = db_query("insert into db_acount values($acount,1864,10827,'".AddSlashes(pg_result($resaco,$conresaco,'c35_db_viradaitem'))."','$this->c35_db_viradaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_data"]))
           $resac = db_query("insert into db_acount values($acount,1864,10828,'".AddSlashes(pg_result($resaco,$conresaco,'c35_data'))."','$this->c35_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_hora"]))
           $resac = db_query("insert into db_acount values($acount,1864,10829,'".AddSlashes(pg_result($resaco,$conresaco,'c35_hora'))."','$this->c35_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c35_log"]))
           $resac = db_query("insert into db_acount values($acount,1864,10830,'".AddSlashes(pg_result($resaco,$conresaco,'c35_log'))."','$this->c35_log',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "log da virada nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "log da virada nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c35_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c35_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10825,'$c35_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1864,10825,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1864,10826,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_codarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1864,10827,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_db_viradaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1864,10828,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1864,10829,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1864,10830,'','".AddSlashes(pg_result($resaco,$iresaco,'c35_log'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_viradaitemlog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c35_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c35_sequencial = $c35_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "log da virada nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "log da virada nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_viradaitemlog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_viradaitemlog ";
     $sql .= "      inner join db_sysarquivo  on  db_sysarquivo.codarq = db_viradaitemlog.c35_codarq";
     $sql .= "      inner join db_viradaitem  on  db_viradaitem.c31_sequencial = db_viradaitemlog.c35_db_viradaitem";
     $sql .= "      inner join db_virada  as a on   a.c30_sequencial = db_viradaitem.c31_db_virada";
     $sql .= "      inner join db_viradacaditem  on  db_viradacaditem.c33_sequencial = db_viradaitem.c31_db_viradacaditem";
     $sql2 = "";
     if($dbwhere==""){
       if($c35_sequencial!=null ){
         $sql2 .= " where db_viradaitemlog.c35_sequencial = $c35_sequencial "; 
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
   function sql_query_file ( $c35_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_viradaitemlog ";
     $sql2 = "";
     if($dbwhere==""){
       if($c35_sequencial!=null ){
         $sql2 .= " where db_viradaitemlog.c35_sequencial = $c35_sequencial "; 
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