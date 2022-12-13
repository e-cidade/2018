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

//MODULO: dividaativa
//CLASSE DA ENTIDADE termoanu
class cl_termoanu { 
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
   var $v09_sequencial = 0; 
   var $v09_parcel = 0; 
   var $v09_usuario = 0; 
   var $v09_data_dia = null; 
   var $v09_data_mes = null; 
   var $v09_data_ano = null; 
   var $v09_data = null; 
   var $v09_hora = null; 
   var $v09_motivo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v09_sequencial = int4 = Código sequencial 
                 v09_parcel = int4 = Parcelamento 
                 v09_usuario = int4 = Usuário 
                 v09_data = date = Data 
                 v09_hora = varchar(5) = Hora 
                 v09_motivo = text = Motivo 
                 ";
   //funcao construtor da classe 
   function cl_termoanu() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("termoanu"); 
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
       $this->v09_sequencial = ($this->v09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_sequencial"]:$this->v09_sequencial);
       $this->v09_parcel = ($this->v09_parcel == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_parcel"]:$this->v09_parcel);
       $this->v09_usuario = ($this->v09_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_usuario"]:$this->v09_usuario);
       if($this->v09_data == ""){
         $this->v09_data_dia = ($this->v09_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_data_dia"]:$this->v09_data_dia);
         $this->v09_data_mes = ($this->v09_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_data_mes"]:$this->v09_data_mes);
         $this->v09_data_ano = ($this->v09_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_data_ano"]:$this->v09_data_ano);
         if($this->v09_data_dia != ""){
            $this->v09_data = $this->v09_data_ano."-".$this->v09_data_mes."-".$this->v09_data_dia;
         }
       }
       $this->v09_hora = ($this->v09_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_hora"]:$this->v09_hora);
       $this->v09_motivo = ($this->v09_motivo == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_motivo"]:$this->v09_motivo);
     }else{
       $this->v09_sequencial = ($this->v09_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v09_sequencial"]:$this->v09_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v09_sequencial){ 
      $this->atualizacampos();
     if($this->v09_parcel == null ){ 
       $this->erro_sql = " Campo Parcelamento nao Informado.";
       $this->erro_campo = "v09_parcel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v09_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "v09_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v09_data == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "v09_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v09_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "v09_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v09_motivo == null ){ 
       $this->erro_sql = " Campo Motivo nao Informado.";
       $this->erro_campo = "v09_motivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v09_sequencial == "" || $v09_sequencial == null ){
       $result = db_query("select nextval('termoanu_v09_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: termoanu_v09_sequencial_seq do campo: v09_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v09_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from termoanu_v09_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v09_sequencial)){
         $this->erro_sql = " Campo v09_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v09_sequencial = $v09_sequencial; 
       }
     }
     if(($this->v09_sequencial == null) || ($this->v09_sequencial == "") ){ 
       $this->erro_sql = " Campo v09_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into termoanu(
                                       v09_sequencial 
                                      ,v09_parcel 
                                      ,v09_usuario 
                                      ,v09_data 
                                      ,v09_hora 
                                      ,v09_motivo 
                       )
                values (
                                $this->v09_sequencial 
                               ,$this->v09_parcel 
                               ,$this->v09_usuario 
                               ,".($this->v09_data == "null" || $this->v09_data == ""?"null":"'".$this->v09_data."'")." 
                               ,'$this->v09_hora' 
                               ,'$this->v09_motivo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "termoanu ($this->v09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "termoanu já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "termoanu ($this->v09_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v09_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v09_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9886,'$this->v09_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1697,9886,'','".AddSlashes(pg_result($resaco,0,'v09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1697,9887,'','".AddSlashes(pg_result($resaco,0,'v09_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1697,9890,'','".AddSlashes(pg_result($resaco,0,'v09_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1697,9888,'','".AddSlashes(pg_result($resaco,0,'v09_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1697,9889,'','".AddSlashes(pg_result($resaco,0,'v09_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1697,9923,'','".AddSlashes(pg_result($resaco,0,'v09_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v09_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update termoanu set ";
     $virgula = "";
     if(trim($this->v09_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_sequencial"])){ 
       $sql  .= $virgula." v09_sequencial = $this->v09_sequencial ";
       $virgula = ",";
       if(trim($this->v09_sequencial) == null ){ 
         $this->erro_sql = " Campo Código sequencial nao Informado.";
         $this->erro_campo = "v09_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v09_parcel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_parcel"])){ 
       $sql  .= $virgula." v09_parcel = $this->v09_parcel ";
       $virgula = ",";
       if(trim($this->v09_parcel) == null ){ 
         $this->erro_sql = " Campo Parcelamento nao Informado.";
         $this->erro_campo = "v09_parcel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v09_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_usuario"])){ 
       $sql  .= $virgula." v09_usuario = $this->v09_usuario ";
       $virgula = ",";
       if(trim($this->v09_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "v09_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v09_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v09_data_dia"] !="") ){ 
       $sql  .= $virgula." v09_data = '$this->v09_data' ";
       $virgula = ",";
       if(trim($this->v09_data) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "v09_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v09_data_dia"])){ 
         $sql  .= $virgula." v09_data = null ";
         $virgula = ",";
         if(trim($this->v09_data) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "v09_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v09_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_hora"])){ 
       $sql  .= $virgula." v09_hora = '$this->v09_hora' ";
       $virgula = ",";
       if(trim($this->v09_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "v09_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v09_motivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v09_motivo"])){ 
       $sql  .= $virgula." v09_motivo = '$this->v09_motivo' ";
       $virgula = ",";
       if(trim($this->v09_motivo) == null ){ 
         $this->erro_sql = " Campo Motivo nao Informado.";
         $this->erro_campo = "v09_motivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v09_sequencial!=null){
       $sql .= " v09_sequencial = $this->v09_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v09_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9886,'$this->v09_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1697,9886,'".AddSlashes(pg_result($resaco,$conresaco,'v09_sequencial'))."','$this->v09_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_parcel"]))
           $resac = db_query("insert into db_acount values($acount,1697,9887,'".AddSlashes(pg_result($resaco,$conresaco,'v09_parcel'))."','$this->v09_parcel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1697,9890,'".AddSlashes(pg_result($resaco,$conresaco,'v09_usuario'))."','$this->v09_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_data"]))
           $resac = db_query("insert into db_acount values($acount,1697,9888,'".AddSlashes(pg_result($resaco,$conresaco,'v09_data'))."','$this->v09_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_hora"]))
           $resac = db_query("insert into db_acount values($acount,1697,9889,'".AddSlashes(pg_result($resaco,$conresaco,'v09_hora'))."','$this->v09_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v09_motivo"]))
           $resac = db_query("insert into db_acount values($acount,1697,9923,'".AddSlashes(pg_result($resaco,$conresaco,'v09_motivo'))."','$this->v09_motivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoanu nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "termoanu nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v09_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v09_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9886,'$v09_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1697,9886,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1697,9887,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_parcel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1697,9890,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1697,9888,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1697,9889,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1697,9923,'','".AddSlashes(pg_result($resaco,$iresaco,'v09_motivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from termoanu
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v09_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v09_sequencial = $v09_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "termoanu nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v09_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "termoanu nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v09_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v09_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:termoanu";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $v09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termoanu ";
     $sql .= "      inner join termo  on  termo.v07_parcel = termoanu.v09_parcel";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = termoanu.v09_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = termo.v07_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = termo.v07_instit";
     $sql2 = "";
     if($dbwhere==""){
       if($v09_sequencial!=null ){
         $sql2 .= " where termoanu.v09_sequencial = $v09_sequencial "; 
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
   function sql_query_file ( $v09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termoanu ";
     $sql2 = "";
     if($dbwhere==""){
       if($v09_sequencial!=null ){
         $sql2 .= " where termoanu.v09_sequencial = $v09_sequencial "; 
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
   function sqlQueryTermoOrigem ( $v09_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from termoanu ";
     $sql .= "      inner join termo        on termo.v07_parcel       = termoanu.v09_parcel  ";

     $sql .= "      left  join termoini     on termoini.parcel        = termo.v07_parcel     ";
     $sql .= "      left  join termodiv     on termodiv.parcel        = termo.v07_parcel     ";
     $sql .= "      left  join termodiver   on termodiver.dv10_parcel = termo.v07_parcel     ";
     $sql .= "      left  join termocontrib on termocontrib.parcel    = termo.v07_parcel     ";
     $sql .= "      left  join termoreparc  on termoreparc.v08_parcel = termo.v07_parcel     ";

     $sql .= "      left  join arreinscr    on arreinscr.k00_numpre   = termo.v07_numpre     ";
     $sql .= "      left  join arrematric   on arrematric.k00_numpre  = termo.v07_numpre     ";
     $sql .= "      left  join arrenumcgm   on arrenumcgm.k00_numpre  = termo.v07_numpre     ";
     $sql .= "      inner join db_usuarios  on db_usuarios.id_usuario = termoanu.v09_usuario ";
     $sql .= "      inner join cgm          on cgm.z01_numcgm         = termo.v07_numcgm     ";
     $sql2 = "";

     if($dbwhere==""){
       if($v09_sequencial!=null ){
         $sql2 .= " where termoanu.v09_sequencial = $v09_sequencial "; 
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